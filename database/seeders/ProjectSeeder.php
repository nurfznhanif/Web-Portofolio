<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'title' => 'Geopark Sawahlunto',
            'description' => 'Website tourism untuk Geopark Sawahlunto dengan sistem manajemen konten dinamis.',
            'technologies' => ['Laravel', 'MySQL', 'PHP', 'API'],
            'github_url' => 'https://github.com/nurfznhanif/',
            'featured' => true
        ]);
    }
}
