<?php

use App\Models\Packet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('sensor_values', function (Blueprint $table) {
			$table->foreignIdFor(Packet::class)->constrained();
			$table->tinyInteger('zone');
			$table->enum('type', ['temp', 'humidity', 'light', 'co2', 'tvoc', 'pt100']);
			$table->float('value', 5, 1)->default(0);
			$table->float('high', 5, 1)->default(0);
			$table->float('low', 5, 1)->default(0);

			$table->primary(['packet_id', 'zone', 'type']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('sensor_values');
	}
};
