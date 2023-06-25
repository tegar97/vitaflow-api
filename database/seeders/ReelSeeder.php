<?php

namespace Database\Seeders;

use App\Models\Reel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $reels = [
            [
                'title' => 'Reel 1',
                'description' => 'Description for Reel 1',
                'video_url' => 'https://www.example.com/video1',
                'summary' => 'Summary for Reel 1',
            ],
            [
                'title' => 'Reel 2',
                'description' => 'Description for Reel 2',
                'video_url' => 'https://www.example.com/video2',
                'summary' => 'Summary for Reel 2',
            ],
            // Tambahkan data reels lainnya di sini jika diperlukan
        ];

        // Looping melalui array reels dan membuat rekaman di database
        foreach ($reels as $reel) {
            Reel::create($reel);
        }
    }
}
