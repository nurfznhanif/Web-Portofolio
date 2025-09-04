<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Portfolio;
use App\Models\Experience;
use App\Models\Skill;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);

        // Portfolio Seeder
        Portfolio::create([
            'title' => 'Geopark Sawahlunto',
            'description' => 'Sebagai backend developer dalam tim dua orang, membangun sistem database MySQL, API, untuk website Geopark Sawahlunto menggunakan Laravel. Mengimplementasikan autentikasi admin, CRUD untuk konten dinamis, serta berbagai endpoint API untuk kebutuhan frontend.',
            'technologies' => 'Laravel, MySQL, API Development, PHP',
            'challenges' => 'Perbedaan pendapat tentang struktur API dan prioritas pengembangan',
            'solutions' => 'Komunikasi intensif dan kompromi teknis untuk menyelesaikan proyek tepat waktu',
            'github_url' => 'https://github.com/nurfznhanif/',
            'project_type' => 'Web Development',
            'project_date' => '2024-01-01'
        ]);

        Portfolio::create([
            'title' => 'Sistem Pemira Mahasiswa Fakultas Hukum UNRI',
            'description' => 'Sebagai fullstack developer, mengembangkan sistem pemilihan online untuk Fakultas Hukum UNRI menggunakan CodeIgniter. Bertanggung jawab mulai dari desain database MySQL, pembuatan sistem autentikasi, hingga pengembangan fitur inti seperti voting berbasis token unik, dan visualisasi hasil real-time.',
            'technologies' => 'CodeIgniter, MySQL, PHP, JavaScript',
            'challenges' => 'Perbedaan pendapat mengenai metode autentikasi dan tampilan hasil voting',
            'solutions' => 'Diskusi terbuka dan kompromi menunda tampilan hasil hingga voting selesai',
            'github_url' => 'https://github.com/nurfznhanif/',
            'project_type' => 'Web Application',
            'project_date' => '2023-12-01'
        ]);

        Portfolio::create([
            'title' => 'Web Sekolah dan PPDB SD 003 YKWI',
            'description' => 'Sebagai fullstack developer dalam proyek pengabdian dosen, mengembangkan tiga sistem terintegrasi: website profil sekolah, sistem PPDB online, dan sistem presensi guru berbasis lokasi dengan geofencing.',
            'technologies' => 'PHP, MySQL, JavaScript, Geofencing API',
            'challenges' => 'Pilihan teknologi sistem presensi, awalnya tim menginginkan sistem dalam satu jaringan',
            'solutions' => 'Mengusulkan solusi geofencing untuk sistem presensi yang lebih fleksibel',
            'github_url' => 'https://github.com/nurfznhanif/',
            'project_type' => 'Integrated System',
            'project_date' => '2024-02-01'
        ]);

        // Experience Seeder
        Experience::create([
            'position' => 'Asisten Dosen',
            'organization' => 'Universitas Islam Riau',
            'description' => 'Mendampingi proses belajar untuk mata kuliah: Basis Data, Pengenalan Pemrograman, Struktur Data, Pemrograman Berbasis Komponen, Artificial Intelligence, Dasar Pemrograman Mobile, Desain dan Analisis Algoritma, Basis Data Grafik, Pembangunan Aplikasi Seluler Native, Logika Samar, dan Pembelajaran Mesin.',
            'start_date' => '2023-10-01',
            'end_date' => null,
            'is_current' => true
        ]);

        Experience::create([
            'position' => 'Ketua',
            'organization' => 'Study Club Programming PRIMATECH – Universitas Islam Riau',
            'description' => 'Memimpin komunitas programming mahasiswa dalam kegiatan pembelajaran dan pengembangan skill programming.',
            'start_date' => '2023-11-01',
            'end_date' => '2024-09-01',
            'is_current' => false
        ]);

        Experience::create([
            'position' => 'Dewan Pengawas Organisasi',
            'organization' => 'Himpunan Mahasiswa Perhentian Raja',
            'description' => 'Mengawasi dan memberikan arahan dalam kegiatan organisasi kemahasiswaan.',
            'start_date' => '2023-11-01',
            'end_date' => '2024-11-01',
            'is_current' => false
        ]);

        // Skills Seeder
        $skills = [
            // Web Programming
            ['name' => 'PHP', 'category' => 'Web Programming', 'level' => 'menengah'],
            ['name' => 'JavaScript', 'category' => 'Web Programming', 'level' => 'menengah'],
            ['name' => 'Laravel', 'category' => 'Web Programming', 'level' => 'menengah'],
            ['name' => 'Vue.js', 'category' => 'Web Programming', 'level' => 'menengah'],
            ['name' => 'React.js', 'category' => 'Web Programming', 'level' => 'menengah'],

            // Data Analysis
            ['name' => 'Python', 'category' => 'Data Analysis', 'level' => 'menengah'],
            ['name' => 'Google Colab', 'category' => 'Data Analysis', 'level' => 'menengah'],
            ['name' => 'Data Visualization', 'category' => 'Data Analysis', 'level' => 'menengah'],

            // Mobile Programming
            ['name' => 'React Native', 'category' => 'Mobile Programming', 'level' => 'menengah'],

            // Office Suite
            ['name' => 'Microsoft Word', 'category' => 'Office Suite', 'level' => 'mahir'],
            ['name' => 'Microsoft Excel', 'category' => 'Office Suite', 'level' => 'menengah'],
            ['name' => 'Microsoft PowerPoint', 'category' => 'Office Suite', 'level' => 'pemula'],

            // Languages
            ['name' => 'Bahasa Indonesia', 'category' => 'Language', 'level' => 'mahir'],
            ['name' => 'Bahasa Inggris', 'category' => 'Language', 'level' => 'pemula'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
