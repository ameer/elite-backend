<?php

use App\Models\Module;
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
        Schema::create('packets', function (Blueprint $table) {
            $table->id();
			$table->foreignIdFor(Module::class)->constrained();
			$table->integer('T')->unsigned();
			$table->tinyInteger('ETH')->unsigned();
			$table->tinyInteger('GSQ')->unsigned();
			$table->tinyInteger('AC')->unsigned();
			$table->tinyInteger('BAT')->unsigned();
			$table->integer('CR');
            $table->timestamp("recorded_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packets');
    }
};
