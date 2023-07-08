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
                'title' => '5 Akibat jarang Mandi',
                'description' => 'Kebiasaan jarang mandi dapat memiliki beberapa akibat yang tidak baik bagi kesehatan. Selain menyebabkan penumpukan kotoran pada kulit, mandi yang jarang juga dapat menyebabkan bau badan yang tidak sedap. Selain itu, kulit juga bisa menjadi kering dan gatal akibat kurangnya kelembaban yang diberikan oleh air mandi. Mandi secara teratur sangat penting untuk menjaga kebersihan dan kesehatan tubuh',
                'video_url' => 'https://firebasestorage.googleapis.com/v0/b/fitlife-3ef78.appspot.com/o/1.mp4?alt=media&token=9ebe9f0d-6345-40c2-a24d-ac9e217c3deb',
                'summary' => 'Kebiasaan jarang mandi dapat memiliki beberapa akibat yang tidak baik bagi kesehatan. Selain menyebabkan penumpukan kotoran pada kulit, mandi yang jarang juga dapat menyebabkan bau badan yang tidak sedap. Selain itu, kulit juga bisa menjadi kering dan gatal akibat kurangnya kelembaban yang diberikan oleh air mandi. Mandi secara teratur sangat penting untuk menjaga kebersihan dan kesehatan tubuh',
            ],
            [
                'title' => 'Ini Dia kebiasaan yang sering dilakukan   yang berbahaya',
                'description' => '',
                'video_url' => 'https://firebasestorage.googleapis.com/v0/b/fitlife-3ef78.appspot.com/o/2.mp4?alt=media&token=ebf56c89-d354-4cb5-be9a-d8e2b2ae1f33',
                'summary' => 'Banyak kebiasaan yang sering dilakukan secara tidak sadar dapat membahayakan kesehatan kita. Misalnya, kebiasaan merokok secara rutin dapat meningkatkan risiko terkena penyakit paru-paru dan kanker. Selain itu, kebiasaan mengonsumsi makanan cepat saji secara berlebihan juga dapat menyebabkan masalah kesehatan seperti obesitas dan penyakit jantung. Penting untuk mengenali kebiasaan-kebiasaan berbahaya ini dan berusaha menghindarinya   ',
            ],
            [
                'title' => 'Manfaat mengangkat kaki saat sebelum tidur',
                'description' => 'Mengungkap manfaat yang didapatkan dengan mengangkat kaki sebelum tidur, seperti meningkatkan sirkulasi darah dan mengurangi pembengkakan serta nyeri pada kaki.',
                'video_url' => 'https://firebasestorage.googleapis.com/v0/b/fitlife-3ef78.appspot.com/o/3.mp4?alt=media&token=fbeec078-4c88-4f9b-8a55-8257dd11e862',
                'summary' => 'Mengangkat kaki saat sebelum tidur memiliki manfaat yang penting bagi kesehatan. Salah satu manfaatnya adalah meningkatkan sirkulasi darah ke bagian-bagian tubuh yang lebih rendah, seperti kaki dan kaki. Hal ini dapat membantu mengurangi pembengkakan dan rasa tidak nyaman pada kaki. Selain itu, mengangkat kaki juga dapat membantu mengurangi nyeri dan kejang otot kaki. Ini adalah kebiasaan sederhana namun efektif yang dapat dilakukan untuk meningkatkan kualitas tidur dan kesehatan secara keseluruhan.',

            ],
            [
                'title' => 'Apa itu Ganja ?',
                'description' => 'Ganja adalah sejenis tanaman yang mengandung senyawa psikoaktif bernama tetrahydrocannabinol (THC). Ganja sering digunakan untuk tujuan rekreasi atau pengobatan. Penggunaan ganja secara berlebihan dapat memiliki efek negatif pada kesehatan mental dan fisik. Misalnya, ganja dapat menyebabkan gangguan memori, kehilangan motivasi, dan peningkatan risiko gangguan kecemasan atau depresi. Penting untuk memahami efek dan risiko penggunaan ganja sebelum menggunakannya.',
                'video_url' => 'https://firebasestorage.googleapis.com/v0/b/fitlife-3ef78.appspot.com/o/4.mp4?alt=media&token=5907c9a1-7b6a-4dde-af69-11f448fa2407',
                'summary' => 'Ganja adalah sejenis tanaman yang mengandung senyawa psikoaktif bernama tetrahydrocannabinol (THC). Ganja sering digunakan untuk tujuan rekreasi atau pengobatan. Penggunaan ganja secara berlebihan dapat memiliki efek negatif pada kesehatan mental dan fisik. Misalnya, ganja dapat menyebabkan gangguan memori, kehilangan motivasi, dan peningkatan risiko gangguan kecemasan atau depresi. Penting untuk memahami efek dan risiko penggunaan ganja sebelum menggunakannya.',
            ]
            // Tambahkan data reels lainnya di sini jika diperlukan
        ];

        // Looping melalui array reels dan membuat rekaman di database
        foreach ($reels as $reel) {
            Reel::create($reel);
        }
    }
}
