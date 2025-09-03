<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Experience;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        Experience::create([
            'title' => 'Asisten Dosen',
            'company' => 'Universitas Islam Riau',
            'start_date' => '2023-10-01',
            'end_date' => null,
            'is_current' => true,
            'description' => 'Mendampingi proses belajar untuk mata kuliah: Basis Data, Pengenalan Pemrograman, Struktur Data, Pemrograman Berbasis Komponen, Artificial Intelligence, Dasar Pemrograman Mobile, Desain dan Analisis Algoritma, Basis Data Grafik, Pembangunan Aplikasi Seluler Native, Logika Samar, dan Pembelajaran Mesin.'
        ]);
    }
}
