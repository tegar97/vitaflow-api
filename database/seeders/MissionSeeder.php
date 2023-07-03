<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

          DB::table('missions')->insert([[

            'name' => 'Catat Aktivitas Makan',
            'description' => ' Catat Aktivitas Makan',
            'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'color_Theme' => '#FF0000',
            'point' => 15,
            'coin' => 2,
            'bamboo' => 5,


          ],[

            'name' => 'Catat Aktivitas Olahraga',
            'description' => ' Catat Aktivitas Olahraga',
            'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'color_Theme' => '#FF0000',
            'point' => 10,
            'coin' => 2,
            'bamboo' => 5,
          ],[

            'name' => 'Catat  Kesehatan',
            'description' => ' Catat   Kesehatan',
            'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'color_Theme' => '#FF0000',
            'point' => 10,
            'coin' => 2,
            'bamboo' => 5,
          ],[

            'name' => 'Catat Aktivitas Minum',
            'description' => ' Catat Aktivitas Minum',
            'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'color_Theme' => '#FF0000',
            'point' => 10,
            'coin' => 2,
            'bamboo' => 5,
          ],[

            'name' => 'Catat Aktivitas Berat Badan',
            'description' => ' Catat Aktivitas Berat Badan',
            'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'color_Theme' => '#FF0000',
            'point' => 10,
            'coin' => 1,
            'bamboo' => 2,
          ] , [
            'name' => 'Catat Aktivitas  Lari',
            'description' => ' Catat Aktivitas  Lari',
            'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'color_Theme' => '#FF0000',
            'point' => 10,
            'coin' => 1,
            'bamboo' => 2,




          ] ]);
    }
}
