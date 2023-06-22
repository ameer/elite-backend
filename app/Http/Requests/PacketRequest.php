<?php

namespace App\Http\Requests;

use App\Events\NewPacketEvent;
use App\Events\ZoneUpdateEvent;
use App\Models\Module;
use App\Models\Packet;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class PacketRequest extends FormRequest
{
	private const SAVE_INTERVAL = 10;

	protected $module;
	protected $packet;
	protected $values;
	protected $response = [];

	public function getModule(): Module
	{
		if (!$this->module)
			$this->module = Module::where("muid", $this->ID)->firstOrFail();
		return $this->module;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			//
		];
	}

	public function handle()
	{
		$this->headers->set('Content-Type', 'application/json');
		$this->headers->set('Accept', 'application/json');
		$this->save();
		$this->generateResponse();
		$this->broadcast();
	}

	public function response()
	{
		return response()->json($this->response);
	}

	public function broadcast()
	{
		$packet = $this->packet->toArray();
		$packet['recorded_at'] = Carbon::now();
		$packet['sensor_values'] = $this->values;
		event(new NewPacketEvent($packet));
	}

	public function save()
	{
		$module = $this->getModule();
		$this->packet = $this->makePacket();
		$this->values = $this->makeSensorValues($this->packet);
		
		// Save Packets every T seconds.
		$now = now()->timestamp;
		$lastPacket = Cache::get("LAST_PACKET_{$module->id}", 0);
		if (($now - $lastPacket) > self::SAVE_INTERVAL) {	
			$this->packet->save();
			$this->packet->sensorValues()->saveMany($this->values);
			$this->updateZonesLable($module);
			Cache::forever("LAST_PACKET_{$module->id}", $now);
		}

	}

	public function makePacket()
	{
		$data = $this->only(['T', 'ETH', 'GSQ', 'AC', 'BAT', 'CR']);
		return $this->getModule()->packets()->make($data);
	}

	public function makeSensorValues(Packet $packet)
	{
		$result = [];
		$matches = [];
		foreach ($this->input() as $key => $value) {
			if (preg_match('/Z(\d)_(.)_N/', $key, $matches)) {
				$result[] = $packet->sensorValues()->make([
					'zone'	=> intval($matches[1]),
					'type'	=> $this->getSensorType($matches[2]),
					'value'	=> $matches[2] === "T" || $matches[2] === "P" ? $value / 10 : $value,
					'high'	=> $this->get("Z{$matches[1]}_{$matches[2]}_H"),
					'low'	=> $this->get("Z{$matches[1]}_{$matches[2]}_L"),
				]);
			}
		}
		return $result;
	}

	public function updateZonesLable(Module $module)
	{
		$matches = [];
		foreach ($this->input() as $key => $value) {
			if (preg_match('/Z(\d)_LB/', $key, $matches)) {
				$zone = $module->zones()->updateOrCreate([
					'num'	=> $matches[1],
				], [
					'title' => $value,
				]);
				if ($zone->wasChanged())
					event(new ZoneUpdateEvent($zone));
			}
		}
	}

	private function getSensorType($type)
	{
		switch ($type) {
			case 'T':
				return 'temp';
			case 'H':
				return 'humidity';
			case 'L':
				return 'light';
			case 'C':
				return 'co2';
			case 'V':
				return 'tvoc';
			case 'P':
				return 'pt100';
		}
	}

	public function generateResponse()
	{
		$this->response['T'] = time();

		$cacheName = "SENSOR_" . $this->getModule()['id'];
		$sensor = Cache::get($cacheName, []);
		foreach ($sensor as $key => $value) {
			if ($this->get($key) == $value)
				unset($sensor[$key]);
		}
		$this->response = array_merge($this->response, $sensor);
		Cache::forever($cacheName, $sensor);
	}
}
