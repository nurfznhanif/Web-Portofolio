<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            ['category' => 'Web Programming', 'name' => 'PHP', 'level' => 'intermediate', 'description' => 'Backend development dengan Laravel'],
            ['category' => 'Web Programming', 'name' => 'JavaScript', 'level' => 'intermediate', 'description' => 'Frontend dan backend development'],
            ['category' => 'Web Programming', 'name' => 'Laravel', 'level' => 'intermediate', 'description' => 'PHP framework untuk web application'],
            ['category' => 'Data Analysis', 'name' => 'Python', 'level' => 'intermediate', 'description' => 'Data science dan machine learning'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
