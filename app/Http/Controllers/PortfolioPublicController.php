<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Experience;
use App\Models\Skill;
use Inertia\Inertia;

class PortfolioPublicController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('project_date', 'desc')->get();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $skills = Skill::orderBy('category')->orderBy('name')->get()->groupBy('category');

        $profile = [
            'name' => 'Nurfauzan Hanif',
            'title' => 'Fullstack Developer & Teaching Assistant',
            'summary' => 'Mahasiswa Teknik Informatika semester 7 di Universitas Islam Riau dengan IPK 3,68. Berpengalaman sebagai asisten dosen untuk berbagai mata kuliah programming dan aktif mengembangkan proyek web menggunakan teknologi modern seperti Laravel, Vue.js, dan React.',
            'email' => 'nrfznhnf@gmail.com',
            'phone' => '+62 812-3456-7890',
            'location' => 'Pekanbaru, Riau, Indonesia',
            'github' => 'https://github.com/nurfznhanif',
            'education' => [
                'degree' => 'S1 Teknik Informatika',
                'university' => 'Universitas Islam Riau',
                'gpa' => '3,68',
                'thesis' => 'Implementasi Algoritma Machine Learning untuk Prediksi Performa Mahasiswa'
            ],
            'interests' => [
                'Web Development',
                'Machine Learning',
                'Data Analysis',
                'Teaching',
                'Open Source'
            ]
        ];

        return Inertia::render('Portfolio/Index', [
            'portfolios' => $portfolios,
            'experiences' => $experiences,
            'skills' => $skills,
            'profile' => $profile
        ]);
    }
}
