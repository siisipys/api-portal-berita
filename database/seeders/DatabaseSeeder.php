<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Berita;
use App\Models\Komentar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        $user1 = User::create([
            'name' => 'Admin Portal',
            'email' => 'admin@portal.com',
            'password' => Hash::make('password123'),
        ]);

        $user2 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user3 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Create berita
        $berita1 = Berita::create([
            'user_id' => $user1->id,
            'judul' => 'Teknologi AI Semakin Berkembang di Indonesia',
            'konten' => 'Perkembangan teknologi kecerdasan buatan (AI) di Indonesia semakin pesat. Berbagai sektor mulai mengadopsi teknologi ini untuk meningkatkan efisiensi dan produktivitas. Dari sektor perbankan hingga kesehatan, AI mulai memainkan peran penting dalam transformasi digital Indonesia. Para ahli memperkirakan bahwa dalam 5 tahun ke depan, AI akan menjadi bagian integral dari kehidupan sehari-hari masyarakat Indonesia.',
            'kategori' => 'teknologi',
            'status' => 'published',
        ]);

        $berita2 = Berita::create([
            'user_id' => $user1->id,
            'judul' => 'Tips Menjaga Kesehatan di Musim Hujan',
            'konten' => 'Musim hujan telah tiba dan berbagai penyakit mulai mengancam. Berikut adalah beberapa tips untuk menjaga kesehatan: 1) Selalu bawa payung atau jas hujan, 2) Konsumsi vitamin C secara teratur, 3) Istirahat yang cukup, 4) Makan makanan bergizi, 5) Olahraga teratur meski di dalam rumah. Dengan mengikuti tips ini, Anda dapat tetap sehat selama musim hujan.',
            'kategori' => 'kesehatan',
            'status' => 'published',
        ]);

        $berita3 = Berita::create([
            'user_id' => $user2->id,
            'judul' => 'Ekonomi Indonesia Tumbuh Positif di Kuartal Ini',
            'konten' => 'Bank Indonesia melaporkan pertumbuhan ekonomi yang positif pada kuartal ini. Pertumbuhan didorong oleh sektor konsumsi rumah tangga dan investasi. Ekspor juga menunjukkan tren positif seiring dengan pemulihan ekonomi global. Pemerintah optimis target pertumbuhan ekonomi tahun ini dapat tercapai dengan berbagai stimulus yang telah disiapkan.',
            'kategori' => 'ekonomi',
            'status' => 'published',
        ]);

        $berita4 = Berita::create([
            'user_id' => $user2->id,
            'judul' => 'Timnas Indonesia Raih Kemenangan Bersejarah',
            'konten' => 'Timnas Indonesia berhasil meraih kemenangan bersejarah dalam pertandingan kualifikasi Piala Dunia. Gol-gol indah dari para pemain membawa Indonesia unggul dengan skor telak. Pelatih menyatakan bangga dengan performa tim dan berharap dapat melanjutkan tren positif di pertandingan selanjutnya. Suporter tanah air merayakan kemenangan ini dengan penuh sukacita.',
            'kategori' => 'olahraga',
            'status' => 'published',
        ]);

        $berita5 = Berita::create([
            'user_id' => $user3->id,
            'judul' => 'Destinasi Wisata Baru di Nusa Tenggara Timur',
            'konten' => 'Nusa Tenggara Timur kembali memperkenalkan destinasi wisata baru yang memukau. Pantai dengan pasir pink dan air laut yang jernih menjadi daya tarik utama. Pemerintah daerah telah menyiapkan infrastruktur untuk mendukung pariwisata di kawasan ini. Diharapkan destinasi baru ini dapat meningkatkan kunjungan wisatawan dan ekonomi lokal.',
            'kategori' => 'wisata',
            'status' => 'published',
        ]);

        // Create komentar
        Komentar::create([
            'user_id' => $user2->id,
            'berita_id' => $berita1->id,
            'isi' => 'Artikel yang sangat informatif! AI memang akan mengubah banyak hal di masa depan.',
        ]);

        Komentar::create([
            'user_id' => $user3->id,
            'berita_id' => $berita1->id,
            'isi' => 'Semoga Indonesia bisa menjadi pemimpin dalam pengembangan AI di Asia Tenggara.',
        ]);

        Komentar::create([
            'user_id' => $user1->id,
            'berita_id' => $berita2->id,
            'isi' => 'Tips yang sangat berguna, terima kasih sudah berbagi!',
        ]);

        Komentar::create([
            'user_id' => $user3->id,
            'berita_id' => $berita4->id,
            'isi' => 'Bangga dengan Timnas! Terus berjuang untuk Indonesia!',
        ]);

        Komentar::create([
            'user_id' => $user1->id,
            'berita_id' => $berita5->id,
            'isi' => 'Wah, harus masuk bucket list nih! Pemandangannya pasti indah sekali.',
        ]);
    }
}
