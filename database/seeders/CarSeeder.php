<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {
            DB::table('cars')->insert(['model' => Str::random(30), 'year' => rand(1975, 2024), 'mileage' => rand(0, 300000), 'cute' => rand(0, 1) == 1]);
        }
    }
}
