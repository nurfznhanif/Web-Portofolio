<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        Profile::create([
            'name' => 'Nurfauzan Hanif',
            'phone' => '0822-8568-5679',
            'email' => 'nrfznhnf@gmail.com',
            'location' => 'Pekanbaru, Riau',
            'bio' => 'Lulusan Program Studi Teknik Informatika, Fakultas Teknik, Universitas Islam Riau, dengan kemampuan menengah dalam pemrograman menggunakan PHP, Python, dan JavaScript.',
            'education_degree' => 'S1-Teknik Informatika',
            'education_gpa' => 3.76,
            'education_thesis' => 'Analisis Sentimen Berbasis Aspek terhadap Diskursus Generasi Emas 2045 di Media Sosial menggunakan Latent Dirichlet Allocation dan Bidirectional Encoder Representations from Transformers (BERT)',
            'github_url' => 'https://github.com/nurfznhanif/',
        ]);
    }
}
