<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Module;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
		Module::create([
			'title' => "دستگاه 1",
		]);
		Module::create([
			'title' => "دستگاه 2",
		]);
		Module::create([
			'title' => "دستگاه 3",
		]);
    }
}
