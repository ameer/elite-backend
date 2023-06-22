<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SensorController extends Controller
{
    private function getTypeSymbol($type)
    {
        switch ($type) {
            case 'temp':
                return 'T';
            case 'humidity':
                return 'H';
            case 'light':
                return 'L';
            case 'co2':
                return 'C';
            case 'tvoc':
                return 'V';
            case 'pt100':
                return 'P';
        }
    }

    public function set(Request $request)
    {
        $validated = $request->validate([
            'module_id' => '',
            'zone'  => '',
            'type'  => '',
            'title' => '',
            'high'  => '',
            'low'   => '',
        ]);

        $type = $this->getTypeSymbol($validated['type']);
        $current = Cache::get("SENSOR_" . $validated['module_id'], []);
        Cache::forever("SENSOR_" . $validated['module_id'], array_merge($current, [
            "Z{$validated['zone']}_{$type}_H" => $validated['high'],
            "Z{$validated['zone']}_{$type}_L" => $validated['low'],
            "Z{$validated['zone']}_LB" => $validated['title'],
        ]));

        return ['OK' => true];
    }
}
