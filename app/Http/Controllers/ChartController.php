<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChartController extends Controller
{
    public function get(Module $module, $zone, $type)
    {
		try {

			$fromDate = Carbon::now()->addHours(-24);
			return $module->packets()->where('recorded_at', '>=', $fromDate)->with(['sensorValues' => function ($query) use ($zone, $type) {
				return $query->whereZone($zone)->whereType($type);
			}])->take(1000)->get()->map(function ($packet) {
            return [
				'time'  => $packet->recorded_at->format("H:i"),
                'value'  => $packet->sensorValues->first()->value ?? null,
                'high'  => $packet->sensorValues->first()->high ?? null,
                'low'  => $packet->sensorValues->first()->low ?? null,
            ];
        });
		} catch (Exception $ex) {
			Log::error($ex->getMessage());
			return $ex->getMessage();
		}
    }
}
