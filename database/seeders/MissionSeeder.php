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
            'icon' => 'icon_m1.png',
            'color_Theme' => '0xFFE88441',
            'point' => 15,
            'coin' => 2,
            'bamboo' => 5,
            'order_number' => 1,
            'route' => '/food-record',


          ],[

            'name' => 'Catat Aktivitas Olahraga',
            'alternatif_name' => 'Olahraga ',
            'mission_code' => 'M2',
            'description' => ' Catat Aktivitas Olahraga',
            'icon' => 'icon_m2.png',
            'color_Theme' => '0xFFF9D171',
            'point' => 10,
            'coin' => 2,
            'bamboo' => 5,
            'order_number' => 2,
            'route' => '/sport-record',

          ], [
                    'name' => 'Catat Aktivitas  Lari',
                    'alternatif_name' => 'Track jogging',
                    'mission_code' => 'M3',
                    'description' => ' Catat Aktivitas  Lari',
                    'icon' => 'icon_m3.png',
                    'color_Theme' => '0XFFF39CFF',
                    'point' => 10,
                    'coin' => 1,
                    'bamboo' => 2,
                    'order_number' => 3,
                    'route' => '/record-sport-run',




                ],
          [

            'name' => 'Catat Aktivitas Minum',
            'alternatif_name' => 'Track Dehidrasi',
            'mission_code' => 'M4',
            'description' => ' Catat Aktivitas Minum',
            'icon' => 'icon_m4.png',
            'color_Theme' => '0XFF6FB6E1',
            'point' => 10,
            'coin' => 2,
            'bamboo' => 5,
            'order_number' => 4,
            'route' => '/water-record',
          ],[

            'name' => 'Catat Aktivitas Berat Badan',
            'alternatif_name' => 'Catat Berat Badan'  ,
                'mission_code' => 'M5',
            'description' => ' Catat Aktivitas Berat Badan',
            'icon' => 'icon_m5.png',
            'color_Theme' => '0XFF8D5EBC',
            'point' => 10,
            'coin' => 1,
            'bamboo' => 2,
            'order_number' => 5,
            'route' => '/record-weight',
          ] ,
          [

            'name' => 'Check Kesehatan Jantung',
            'alternatif_name' => 'Check Kesehatan Jantung ',
            'mission_code' => 'M6',
            'description' => ' Catat   Kesehatan',
            'icon' => 'icon_m6.png',
            'color_Theme' => '0XFFF5D6D0',
            'point' => 10,
            'coin' => 2,
            'bamboo' => 5,
            'order_number' => 6,
            'route' => '/vita-pulse',
          ],
         ]);
    }
}
