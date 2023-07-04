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
            'alternatif_name' => 'Catat Aktivitas Makan ',
            'mission_code' => 'M1',
            'description' => ' Catat Aktivitas Makan',
            'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'color_Theme' => '#FF0000',
            'point' => 15,
            'coin' => 2,
            'bamboo' => 5,
            'order_number' => 1,


          ],[

            'name' => 'Catat Aktivitas Olahraga',
            'alternatif_name' => 'Olahraga ',
            'mission_code' => 'M2',
            'description' => ' Catat Aktivitas Olahraga',
            'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'color_Theme' => '#FF0000',
            'point' => 10,
            'coin' => 2,
            'bamboo' => 5,
            'order_number' => 2,
          ], [
                    'name' => 'Catat Aktivitas  Lari',
                    'alternatif_name' => 'Track jogging',
                    'mission_code' => 'M3',
                    'description' => ' Catat Aktivitas  Lari',
                    'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
                    'color_Theme' => '#FF0000',
                    'point' => 10,
                    'coin' => 1,
                    'bamboo' => 2,
                    'order_number' => 3,




                ],
          [

            'name' => 'Catat Aktivitas Minum',
            'alternatif_name' => 'Track Dehidrasi',
            'mission_code' => 'M4',
            'description' => ' Catat Aktivitas Minum',
            'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'color_Theme' => '#FF0000',
            'point' => 10,
            'coin' => 2,
            'bamboo' => 5,
            'order_number' => 4,
          ],[

            'name' => 'Catat Aktivitas Berat Badan',
            'alternatif_name' => 'Catat Berat Badan'  ,
                'mission_code' => 'M5',
            'description' => ' Catat Aktivitas Berat Badan',
            'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'color_Theme' => '#FF0000',
            'point' => 10,
            'coin' => 1,
            'bamboo' => 2,
            'order_number' => 5,
          ] ,
          [

            'name' => 'Check Kesehatan Jantung',
            'alternatif_name' => 'Check Kesehatan Jantung ',
            'mission_code' => 'M6',
            'description' => ' Catat   Kesehatan',
            'icon' => 'https://i.ibb.co/0jZ3Q0K/program-1.png',
            'color_Theme' => '#FF0000',
            'point' => 10,
            'coin' => 2,
            'bamboo' => 5,
            'order_number' => 6,
          ],
         ]);
    }
}
