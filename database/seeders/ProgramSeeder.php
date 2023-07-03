<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           DB::table('programs')->insert([[
            'program_name' => 'Gain Weight',
            'program_description' => 'Gain Weight',
            'program_duration' => '2 Weeks',
            'program_goal_weight' => 'Gain 2 Kg',
            'program_type' => 'gain',
            'image' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'bmi_min' => 0,
            'bmi_max' => 18.5,
        ], [

            'program_name' => 'Maintain Weight',
            'program_description' => 'Maintain Weight',
            'program_duration' => '2 Weeks',
            'program_goal_weight' => 'Maintain Weight',
            'program_type' => 'maintain',
            'image' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'bmi_min' => 18.5,
            'bmi_max' => 25,
        ], [

            'program_name' => 'Lose Weight',
            'program_description' => 'Lose Weight',
            'program_duration' => '2 Weeks',
            'program_goal_weight' => 'Lose 2 Kg',
            'program_type' => 'loss',
            'image' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'bmi_min' => 25,
            'bmi_max' => 100,






           ]]);



    }
}
