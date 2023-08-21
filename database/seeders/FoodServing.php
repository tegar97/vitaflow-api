<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodServing extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servings = [
            ['name' => 'Gram', 'serving_size' => 1],
            ['name' => 'Porsi', 'serving_size' => 500],
            ['name' => 'Gelas', 'serving_size' => 200],
            ['name' => 'Sendok Makan', 'serving_size' => 15],
            ['name' => 'Sendok Teh', 'serving_size' => 5],
        ];

        DB::table('food_serving_units')->insert($servings);

    }
}
