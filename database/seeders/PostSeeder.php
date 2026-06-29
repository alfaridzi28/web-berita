<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin  = User::first();
        $cats   = Category::all()->keyBy('slug');

        $posts = [
            [
                'title'            => 'Pemerintah Luncurkan Program Digitalisasi UMKM Nasional 2025',
                'slug'             => 'pemerintah-luncurkan-program-digitalisasi-umkm',
                'content'          => '<h2>Program Digitalisasi Dimulai</h2><p>Pemerintah Indonesia resmi meluncurkan program digitalisasi UMKM Nasional 2025 yang bertujuan untuk meningkatkan daya saing pelaku usaha kecil dan menengah di era digital. Program ini ditargetkan menjangkau lebih dari 10 juta UMKM di seluruh Indonesia.</p><p>Menteri Koperasi dan UKM menyatakan bahwa program ini akan memberikan akses pelatihan digital, platform e-commerce, dan dukungan teknologi bagi para pelaku UMKM.</p><blockquote>Digitalisasi bukan pilihan, melainkan keharusan untuk bertahan di era persaingan global.</blockquote><p>Program ini akan dijalankan bersama dengan berbagai platform teknologi terkemuka di Indonesia.</p>',
                'meta_description' => 'Pemerintah Indonesia meluncurkan program digitalisasi UMKM Nasional 2025 untuk meningkatkan daya saing 10 juta pelaku usaha kecil.',
                'meta_keywords'    => 'UMKM, digitalisasi, pemerintah, ekonomi digital',
                'status'           => 'published',
                'published_at'     => now()->subDays(1),
                'category_id'      => $cats['nasional']?->id,
            ],
            [
                'title'            => 'Teknologi AI Generatif Ubah Cara Kerja Industri Kreatif',
                'slug'             => 'ai-generatif-ubah-industri-kreatif',
                'content'          => '<h2>Revolusi AI di Industri Kreatif</h2><p>Kecerdasan buatan generatif (Generative AI) kini semakin mengubah lanskap industri kreatif secara global, termasuk di Indonesia. Dari desain grafis hingga pembuatan konten video, AI telah menjadi alat bantu yang tak terpisahkan bagi para kreator.</p><p>Berbagai platform seperti Midjourney, DALL-E, dan Sora telah memungkinkan siapa pun untuk menciptakan konten visual berkualitas tinggi hanya dengan perintah teks.</p><ul><li>Pembuatan desain otomatis dengan AI</li><li>Generasi musik dan audio berbasis AI</li><li>Penulisan konten dengan bantuan LLM</li></ul><p>Para ahli memprediksi bahwa kolaborasi antara manusia dan AI akan menjadi standar baru dalam industri kreatif di masa depan.</p>',
                'meta_description' => 'Kecerdasan buatan generatif mengubah cara kerja industri kreatif. Simak bagaimana AI merevolusi desain, musik, dan konten digital.',
                'meta_keywords'    => 'AI, kecerdasan buatan, industri kreatif, teknologi',
                'status'           => 'published',
                'published_at'     => now()->subDays(2),
                'category_id'      => $cats['teknologi']?->id,
            ],
            [
                'title'            => 'Bursa Saham Asia Menguat Ditopang Data Ekonomi AS yang Positif',
                'slug'             => 'bursa-saham-asia-menguat-data-ekonomi-as',
                'content'          => '<h2>Pasar Saham Asia Bergairah</h2><p>Bursa saham di kawasan Asia-Pasifik menguat pada perdagangan awal pekan ini, didorong oleh rilis data ekonomi Amerika Serikat yang lebih baik dari ekspektasi pasar. Indeks Nikkei di Jepang, Hang Seng di Hong Kong, dan KOSPI di Korea Selatan semuanya mencatat kenaikan signifikan.</p><p>IHSG Indonesia turut merespons positif dengan menguat 1,2% pada pembukaan perdagangan pagi ini, dipimpin oleh sektor perbankan dan konsumer.</p><p>Analis pasar modal memperkirakan tren positif ini akan berlanjut hingga akhir kuartal, seiring dengan ekspektasi penurunan suku bunga oleh Federal Reserve.</p>',
                'meta_description' => 'Bursa saham Asia menguat didorong data ekonomi AS yang positif. IHSG turut naik 1,2% dipimpin sektor perbankan.',
                'meta_keywords'    => 'bursa saham, IHSG, ekonomi, investasi',
                'status'           => 'published',
                'published_at'     => now()->subDays(3),
                'category_id'      => $cats['ekonomi']?->id,
            ],
            [
                'title'            => 'Timnas Indonesia Raih Kemenangan Dramatis di Kualifikasi Piala Dunia',
                'slug'             => 'timnas-indonesia-menang-kualifikasi-piala-dunia',
                'content'          => '<h2>Garuda Terbang Tinggi</h2><p>Timnas Indonesia meraih kemenangan dramatis 2-1 atas tuan rumah dalam laga kualifikasi Piala Dunia 2026 yang berlangsung di Stadion Gelora Bung Karno. Gol penentu kemenangan tercipta di menit-menit akhir pertandingan melalui tendangan keras dari lini belakang.</p><p>Pelatih Timnas Indonesia menyatakan kepuasannya atas perjuangan seluruh pemain yang tidak menyerah hingga peluit panjang dibunyikan.</p><p>Kemenangan ini menempatkan Indonesia di posisi yang lebih menguntungkan dalam perburuan tiket lolos ke putaran final Piala Dunia 2026.</p>',
                'meta_description' => 'Timnas Indonesia menang dramatis 2-1 di kualifikasi Piala Dunia 2026. Gol penentu dicetak di menit akhir.',
                'meta_keywords'    => 'timnas Indonesia, Piala Dunia, sepak bola, olahraga',
                'status'           => 'published',
                'published_at'     => now()->subDays(4),
                'category_id'      => $cats['olahraga']?->id,
            ],
            [
                'title'            => 'KTT G20 Brasil Hasilkan Kesepakatan Pajak Global untuk Miliarder',
                'slug'             => 'ktt-g20-brasil-kesepakatan-pajak-global-miliarder',
                'content'          => '<h2>Pajak Global untuk Orang Terkaya</h2><p>Pertemuan Puncak G20 yang diselenggarakan di Brasil menghasilkan kesepakatan bersejarah mengenai pengenaan pajak minimum global bagi para miliarder dunia. Kesepakatan ini merupakan langkah besar dalam upaya mengatasi ketimpangan ekonomi global.</p><p>Sebanyak 19 negara anggota G20 menyetujui kerangka pajak minimum sebesar 2% atas kekayaan bersih bagi individu dengan aset di atas 1 miliar dolar AS.</p><p>Indonesia sebagai salah satu anggota G20 menyatakan dukungannya terhadap inisiatif ini dan berkomitmen untuk mengimplementasikannya dalam sistem perpajakan nasional.</p>',
                'meta_description' => 'KTT G20 di Brasil sepakat terapkan pajak minimum global 2% untuk miliarder dunia. Indonesia dukung kebijakan ini.',
                'meta_keywords'    => 'G20, pajak global, ekonomi internasional, miliarder',
                'status'           => 'published',
                'published_at'     => now()->subDays(5),
                'category_id'      => $cats['internasional']?->id,
            ],
            [
                'title'            => 'Film Indonesia "Tanah Pusaka" Tembus 5 Juta Penonton dalam Sepekan',
                'slug'             => 'film-tanah-pusaka-5-juta-penonton',
                'content'          => '<h2>Rekor Baru Perfilman Nasional</h2><p>Film Indonesia berjudul "Tanah Pusaka" berhasil mencetak rekor baru dengan menembus angka 5 juta penonton hanya dalam tujuh hari penayangannya. Film drama epik ini menjadi bukti nyata kebangkitan industri perfilman nasional yang semakin dipercaya oleh masyarakat.</p><p>Sutradara film ini menyatakan rasa terima kasihnya kepada seluruh penonton yang telah mendukung karya sineas lokal.</p><blockquote>Ini bukan sekadar soal angka, tapi tentang bagaimana film Indonesia mampu menyentuh hati jutaan penonton.</blockquote><p>Film ini juga berhasil diekspor ke beberapa negara Asia Tenggara dan mulai diminati pasar internasional.</p>',
                'meta_description' => 'Film "Tanah Pusaka" cetak rekor 5 juta penonton dalam sepekan, membuktikan kebangkitan perfilman Indonesia.',
                'meta_keywords'    => 'film Indonesia, bioskop, hiburan, perfilman nasional',
                'status'           => 'published',
                'published_at'     => now()->subDays(6),
                'category_id'      => $cats['hiburan']?->id,
            ],
        ];

        foreach ($posts as $data) {
            Post::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['author_id' => $admin->id])
            );
        }

        $this->command->info(count($posts) . ' artikel demo berhasil dibuat.');
    }
}
