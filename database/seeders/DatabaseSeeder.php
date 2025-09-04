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
            ProfileDynamicSeeder::class, // Add the new dynamic seeder
        ]);

        // Portfolio Projects - Based on PDF Document
        $portfolios = [
            [
                'title' => 'Geopark Sawahlunto Website',
                'description' => 'Served as backend developer in a two-person team, responsible for building MySQL database system and API for Geopark Sawahlunto website using Laravel. Implemented admin authentication, CRUD operations for dynamic content, and various API endpoints for frontend requirements. The project resulted in a website with content management system and tourist destination listings.',
                'technologies' => 'Laravel, MySQL, PHP, API Development, REST API',
                'challenges' => 'Faced disagreements about API structure and development priorities within the team. Different opinions on technical architecture and implementation approaches created initial friction.',
                'solutions' => 'Through intensive communication and technical compromises, we successfully completed the project on time. Learned the importance of effective collaboration in small teams and balancing flexibility with technical principles.',
                'github_url' => 'https://github.com/nurfznhanif/',
                'project_type' => 'Web Development',
                'project_date' => '2024-01-15'
            ],
            [
                'title' => 'Student Election System - UNRI Law Faculty',
                'description' => 'Developed as fullstack developer, created an online voting system for UNRI Law Faculty using CodeIgniter. Responsible from MySQL database design, authentication system creation, to developing core features such as unique token-based voting and real-time result visualization.',
                'technologies' => 'CodeIgniter, MySQL, PHP, JavaScript, HTML/CSS',
                'challenges' => 'Encountered disagreements regarding authentication methods and voting result display approaches. Team had different perspectives on user experience and security implementation.',
                'solutions' => 'Through open discussions, we reached a compromise to delay result display until voting completion. This approach balanced transparency with election integrity, ensuring fair voting process.',
                'github_url' => 'https://github.com/nurfznhanif/',
                'project_type' => 'Web Application',
                'project_date' => '2023-11-30'
            ],
            [
                'title' => 'Integrated School System - SD 003 YKWI',
                'description' => 'As fullstack developer in lecturer community service project, developed three integrated systems for SD 003 YKWI Pekanbaru. Built school profile website with vision-mission features, activity gallery, and announcement blog. Created online PPDB system with digital registration forms, document verification, and admin dashboard. Implemented location-based teacher attendance system with monthly attendance reports.',
                'technologies' => 'PHP, MySQL, JavaScript, HTML/CSS, Geofencing API, Location Services',
                'challenges' => 'Faced technology choice challenges during development. Initially, the team wanted an attendance system within a single network, but due to various circumstances, alternative solutions were needed.',
                'solutions' => 'Proposed and implemented geofencing solution for the attendance system, providing more flexible and practical approach. This solution allowed teachers to check in from designated areas around the school.',
                'github_url' => 'https://github.com/nurfznhanif/',
                'project_type' => 'Integrated System',
                'project_date' => '2024-02-20'
            ],
            [
                'title' => 'Portfolio Website with Admin Dashboard',
                'description' => 'Developed a comprehensive portfolio website with dynamic content management system using Laravel and Vue.js. Features include portfolio project management, experience tracking, skills assessment, contact form with analytics, and real-time content updates. Implemented modern design with glassmorphism effects and responsive layout.',
                'technologies' => 'Laravel, Vue.js, Inertia.js, Tailwind CSS, MySQL, PHP',
                'challenges' => 'Creating a seamless single-page application experience while maintaining SEO optimization and fast loading times. Balancing modern design aesthetics with accessibility and performance requirements.',
                'solutions' => 'Utilized Inertia.js for smooth SPA experience with server-side rendering benefits. Implemented efficient caching strategies, image optimization, and progressive loading techniques. Used semantic HTML and ARIA labels for accessibility.',
                'github_url' => 'https://github.com/nurfznhanif/portfolio-dynamic',
                'project_type' => 'Web Application',
                'project_date' => '2024-12-01'
            ]
        ];

        foreach ($portfolios as $portfolio) {
            Portfolio::create($portfolio);
        }

        // Work Experience - Based on CV Document
        $experiences = [
            [
                'position' => 'Lecturer Assistant',
                'organization' => 'Universitas Islam Riau',
                'description' => 'Support learning processes for various computer science courses including: Database Systems, Programming Introduction, Data Structures, Component-Based Programming, Artificial Intelligence, Mobile Programming Fundamentals, Algorithm Design and Analysis, Graph Database, Native Mobile Application Development, Fuzzy Logic, and Machine Learning. Assist students with practical assignments, provide guidance on programming concepts, and help evaluate student projects.',
                'start_date' => '2023-10-01',
                'end_date' => '2025-07-30',
                'is_current' => true
            ],
            [
                'position' => 'Chairman',
                'organization' => 'Study Club Programming PRIMATECH – Universitas Islam Riau',
                'description' => 'Led programming study club activities, organized coding workshops, mentored junior students in various programming languages and frameworks. Coordinated study sessions, managed club resources, and facilitated knowledge sharing among members. Promoted programming culture and technical skill development within the university community.',
                'start_date' => '2023-11-01',
                'end_date' => '2024-09-01',
                'is_current' => false
            ],
            [
                'position' => 'Organizational Supervisory Board',
                'organization' => 'Himpunan Mahasiswa Perhentian Raja',
                'description' => 'Supervised and provided guidance for student organization activities. Monitored organizational governance, ensured compliance with regulations, and supported strategic decision-making processes. Facilitated communication between organization members and university administration.',
                'start_date' => '2023-11-01',
                'end_date' => '2024-11-01',
                'is_current' => false
            ],
            [
                'position' => 'Event Division Head',
                'organization' => 'Student Service Camp – Computer Science Student Association',
                'description' => 'Led event planning and execution for student service camp activities. Coordinated logistics, managed team resources, and ensured successful implementation of community service programs. Developed leadership skills while contributing to social impact initiatives.',
                'start_date' => '2024-05-01',
                'end_date' => '2024-05-31',
                'is_current' => false
            ]
        ];

        foreach ($experiences as $experience) {
            Experience::create($experience);
        }

        // Enhanced Skills - Based on CV Document with Additional Categories
        $skills = [
            // Web Programming
            ['name' => 'PHP', 'category' => 'Web Programming', 'level' => 'mahir'],
            ['name' => 'JavaScript', 'category' => 'Web Programming', 'level' => 'mahir'],
            ['name' => 'Laravel', 'category' => 'Web Programming', 'level' => 'mahir'],
            ['name' => 'Vue.js', 'category' => 'Web Programming', 'level' => 'menengah'],
            ['name' => 'React.js', 'category' => 'Web Programming', 'level' => 'menengah'],
            ['name' => 'CodeIgniter', 'category' => 'Web Programming', 'level' => 'menengah'],
            ['name' => 'HTML/CSS', 'category' => 'Web Programming', 'level' => 'mahir'],
            ['name' => 'REST API Development', 'category' => 'Web Programming', 'level' => 'mahir'],
            ['name' => 'Inertia.js', 'category' => 'Web Programming', 'level' => 'menengah'],
            ['name' => 'Tailwind CSS', 'category' => 'Web Programming', 'level' => 'menengah'],

            // Data Analysis & AI
            ['name' => 'Python', 'category' => 'Data Analysis', 'level' => 'mahir'],
            ['name' => 'Google Colab', 'category' => 'Data Analysis', 'level' => 'menengah'],
            ['name' => 'Data Visualization', 'category' => 'Data Analysis', 'level' => 'menengah'],
            ['name' => 'Machine Learning', 'category' => 'Data Analysis', 'level' => 'menengah'],
            ['name' => 'BERT (Transformers)', 'category' => 'Data Analysis', 'level' => 'menengah'],
            ['name' => 'Sentiment Analysis', 'category' => 'Data Analysis', 'level' => 'mahir'],
            ['name' => 'Latent Dirichlet Allocation', 'category' => 'Data Analysis', 'level' => 'menengah'],
            ['name' => 'Pandas', 'category' => 'Data Analysis', 'level' => 'menengah'],
            ['name' => 'NumPy', 'category' => 'Data Analysis', 'level' => 'menengah'],
            ['name' => 'Scikit-learn', 'category' => 'Data Analysis', 'level' => 'menengah'],

            // Mobile Programming
            ['name' => 'React Native', 'category' => 'Mobile Programming', 'level' => 'menengah'],
            ['name' => 'Cross-platform Development', 'category' => 'Mobile Programming', 'level' => 'menengah'],
            ['name' => 'Native Mobile Development', 'category' => 'Mobile Programming', 'level' => 'pemula'],
            ['name' => 'Mobile UI/UX', 'category' => 'Mobile Programming', 'level' => 'menengah'],

            // Database
            ['name' => 'MySQL', 'category' => 'Database', 'level' => 'mahir'],
            ['name' => 'Database Design', 'category' => 'Database', 'level' => 'mahir'],
            ['name' => 'Graph Database', 'category' => 'Database', 'level' => 'menengah'],
            ['name' => 'Data Structures', 'category' => 'Database', 'level' => 'mahir'],
            ['name' => 'PostgreSQL', 'category' => 'Database', 'level' => 'menengah'],
            ['name' => 'SQLite', 'category' => 'Database', 'level' => 'menengah'],
            ['name' => 'Database Optimization', 'category' => 'Database', 'level' => 'menengah'],

            // Office Suite
            ['name' => 'Microsoft Word', 'category' => 'Office Suite', 'level' => 'mahir'],
            ['name' => 'Microsoft Excel', 'category' => 'Office Suite', 'level' => 'menengah'],
            ['name' => 'Microsoft PowerPoint', 'category' => 'Office Suite', 'level' => 'menengah'],
            ['name' => 'Google Workspace', 'category' => 'Office Suite', 'level' => 'menengah'],

            // Programming Concepts
            ['name' => 'Algorithm Design', 'category' => 'Programming Concepts', 'level' => 'mahir'],
            ['name' => 'Data Structures', 'category' => 'Programming Concepts', 'level' => 'mahir'],
            ['name' => 'Object-Oriented Programming', 'category' => 'Programming Concepts', 'level' => 'mahir'],
            ['name' => 'Component-Based Programming', 'category' => 'Programming Concepts', 'level' => 'menengah'],
            ['name' => 'Design Patterns', 'category' => 'Programming Concepts', 'level' => 'menengah'],
            ['name' => 'Clean Code', 'category' => 'Programming Concepts', 'level' => 'menengah'],

            // Artificial Intelligence
            ['name' => 'Artificial Intelligence', 'category' => 'Artificial Intelligence', 'level' => 'menengah'],
            ['name' => 'Fuzzy Logic', 'category' => 'Artificial Intelligence', 'level' => 'menengah'],
            ['name' => 'Neural Networks', 'category' => 'Artificial Intelligence', 'level' => 'menengah'],
            ['name' => 'Natural Language Processing', 'category' => 'Artificial Intelligence', 'level' => 'mahir'],
            ['name' => 'Computer Vision', 'category' => 'Artificial Intelligence', 'level' => 'pemula'],

            // Soft Skills
            ['name' => 'Leadership', 'category' => 'Soft Skills', 'level' => 'mahir'],
            ['name' => 'Team Collaboration', 'category' => 'Soft Skills', 'level' => 'mahir'],
            ['name' => 'Communication', 'category' => 'Soft Skills', 'level' => 'mahir'],
            ['name' => 'Project Management', 'category' => 'Soft Skills', 'level' => 'menengah'],
            ['name' => 'Problem Solving', 'category' => 'Soft Skills', 'level' => 'mahir'],
            ['name' => 'Teaching & Mentoring', 'category' => 'Soft Skills', 'level' => 'mahir'],
            ['name' => 'Public Speaking', 'category' => 'Soft Skills', 'level' => 'menengah'],

            // Languages
            ['name' => 'Bahasa Indonesia', 'category' => 'Language', 'level' => 'mahir'],
            ['name' => 'English', 'category' => 'Language', 'level' => 'menengah'],
            ['name' => 'Arabic', 'category' => 'Language', 'level' => 'pemula'],

            // Specialized Technologies
            ['name' => 'Geofencing', 'category' => 'Specialized Technologies', 'level' => 'menengah'],
            ['name' => 'Location Services', 'category' => 'Specialized Technologies', 'level' => 'menengah'],
            ['name' => 'Authentication Systems', 'category' => 'Specialized Technologies', 'level' => 'mahir'],
            ['name' => 'CRUD Operations', 'category' => 'Specialized Technologies', 'level' => 'mahir'],
            ['name' => 'Version Control (Git)', 'category' => 'Specialized Technologies', 'level' => 'mahir'],
            ['name' => 'RESTful APIs', 'category' => 'Specialized Technologies', 'level' => 'mahir'],
            ['name' => 'JSON/XML Processing', 'category' => 'Specialized Technologies', 'level' => 'mahir'],

            // Development Tools
            ['name' => 'VS Code', 'category' => 'Development Tools', 'level' => 'mahir'],
            ['name' => 'Git', 'category' => 'Development Tools', 'level' => 'mahir'],
            ['name' => 'Postman', 'category' => 'Development Tools', 'level' => 'menengah'],
            ['name' => 'Docker', 'category' => 'Development Tools', 'level' => 'pemula'],
            ['name' => 'Composer', 'category' => 'Development Tools', 'level' => 'menengah'],
            ['name' => 'NPM/Yarn', 'category' => 'Development Tools', 'level' => 'menengah'],

            // Deployment & Hosting
            ['name' => 'cPanel', 'category' => 'Deployment & Hosting', 'level' => 'menengah'],
            ['name' => 'Heroku', 'category' => 'Deployment & Hosting', 'level' => 'menengah'],
            ['name' => 'Vercel', 'category' => 'Deployment & Hosting', 'level' => 'menengah'],
            ['name' => 'Linux Server', 'category' => 'Deployment & Hosting', 'level' => 'pemula'],
            ['name' => 'Apache/Nginx', 'category' => 'Deployment & Hosting', 'level' => 'pemula']
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        $this->command->info('Database seeded successfully with dynamic portfolio data!');
        $this->command->info('Created:');
        $this->command->info('- ' . count($portfolios) . ' portfolio projects');
        $this->command->info('- ' . count($experiences) . ' work experiences');
        $this->command->info('- ' . count($skills) . ' skills across ' . count(array_unique(array_column($skills, 'category'))) . ' categories');
        $this->command->info('- Dynamic profile settings');
        $this->command->info('- Social links');
        $this->command->info('- Achievements');
        $this->command->info('- Languages');
        $this->command->info('- Interests');
        $this->command->info('- Certifications');
    }
}
