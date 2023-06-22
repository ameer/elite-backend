<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PacketController;
use App\Http\Controllers\SensorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('admin/v1')->group(function () {
	Route::apiResource('module', ModuleController::class);

	Route::post('sensor/set', SensorController::class . '@set');
	Route::post('chart/get/{module}/{zone}/{type}', ChartController::class . '@get');
});

Route::post('data/zones', DataController::class . '@zones');

Route::post('packet/new', PacketController::class . '@new');
Route::post('packet/latest', PacketController::class . '@latest');