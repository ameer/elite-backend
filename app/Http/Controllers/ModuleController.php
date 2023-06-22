<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Module::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'max:32',
            'muid'    => 'max:50',
        ]);

        return Module::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {
        return $module;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'max:32',
            'muid'  => 'max:50',
        ]);

        if ($validated['title']) {
            $current = Cache::get("SENSOR_" . $module->id, []);
            Cache::forever("SENSOR_" . $module->id, array_merge($current, [
                "D_LB" => $validated['title'],
            ]));
        }

        $module->update($validated);
        return $module;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        // $module->delete();
        // return "OK";
    }
}
