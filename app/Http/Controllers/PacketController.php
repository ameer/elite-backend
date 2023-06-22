<?php

namespace App\Http\Controllers;

use App\Http\Requests\PacketRequest;
use App\Models\Packet;
use Illuminate\Http\Request;

class PacketController extends Controller
{
    /**
     * Handle a newly recevied packet.
     */
    public function new(PacketRequest $request)
    {
        $request->handle();
        return $request->response();
    }

    public function latest()
    {
        $packets = Packet::fromQuery("
            SELECT t.*
            FROM (
                SELECT *,
                    ROW_NUMBER() OVER (PARTITION BY module_id ORDER BY id DESC) AS rn
                FROM packets
            ) t
            WHERE t.rn = 1;
            ");
        $packets->load('sensorValues');
        return $packets->mapWithKeys(function ($item) {
            return [$item['module_id'] => $item];
        });
    }
}
