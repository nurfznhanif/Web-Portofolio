<?php
// database/seeders/PortfolioSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProfileSetting;
use App\Models\Portfolio;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\Achievement;
use App\Models\Language;
use App\Models\Interest;
use App\Models\Certification;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        // Create Profile
        ProfileSetting::create([
            'name' => 'Nurfauzan Hanif',
            'title' => 'Fullstack Developer',
            'summary' => 'Computer Science graduate from the Faculty of Engineering, Islamic University of Riau, with intermediate skills in programming using PHP, Python, and JavaScript. Skilled in using Microsoft Word (advanced), Excel (intermediate), and PowerPoint (beginner). Interested in Web Programming, Mobile Programming, Artificial Intelligence, and Data Analysis. Active in various campus and external organizations, and experienced as a Teaching Assistant. Has high enthusiasm to continue learning and developing in the world of technology, and ready to contribute to the team through the skills possessed.',
            'email' => 'nrfznhnf@gmail.com',
            'phone' => '0822-8568-5679',
            'location' => 'Pekanbaru, Riau',
            'photo' => null, // Kita akan gunakan fallback ke /images/poto1.png
            'education' => [
                'degree' => 'Bachelor of Computer Science',
                'university' => 'Islamic University of Riau',
                'gpa' => '3.76',
                'graduation_year' => '2024',
                'faculty' => 'Faculty of Engineering'
            ],
            'thesis_title' => 'Aspect-Based Sentiment Analysis on Generation Gold 2045 Discourse in Social Media using Latent Dirichlet Allocation and Bidirectional Encoder Representations from Transformers (BERT)',
            'availability' => [
                'status' => 'Available for opportunities',
                'type' => 'full-time'
            ]
        ]);

        // Create Portfolio Projects
        Portfolio::create([
            'title' => 'Geopark Sawahlunto Website',
            'description' => 'As a backend developer in a two-person team, I was responsible for building MySQL database system and API for the Geopark Sawahlunto website using Laravel. I implemented admin authentication, CRUD for dynamic content, and various API endpoints for frontend needs. Some challenges we faced included differences of opinion about API structure and development priorities. With intensive communication and technical compromises, we managed to complete the project on time. The result is a website with a content management system and tourist destination listings. This experience taught me the importance of effective collaboration in small teams and the balance between flexibility and technical principles.',
            'project_type' => 'Web Development',
            'project_date' => '2024-01-15',
            'technologies' => 'Laravel, MySQL, PHP, API Development, Authentication',
            'challenges' => 'Differences of opinion about API structure and development priorities between team members.',
            'solutions' => 'Intensive communication and technical compromises to reach consensus and complete the project on time.',
            'github_url' => 'https://github.com/nurfznhanif/',
            'is_featured' => true,
            'order' => 1
        ]);

        Portfolio::create([
            'title' => 'Student Election System for Faculty of Law UNRI',
            'description' => 'As a fullstack developer, I developed an online voting system for the Faculty of Law UNRI using CodeIgniter. Responsible from MySQL database design, authentication system creation, to developing core features such as unique token-based voting and real-time result visualization. Some challenges faced included differences of opinion regarding authentication methods and voting result display. Through open discussions, we reached a compromise to postpone result display until voting is complete.',
            'project_type' => 'Web Application',
            'project_date' => '2023-09-20',
            'technologies' => 'CodeIgniter, MySQL, PHP, Authentication, Real-time Updates',
            'challenges' => 'Differences of opinion regarding authentication methods and voting result display timing.',
            'solutions' => 'Open discussions and compromise to postpone result display until voting completion for fairness.',
            'is_featured' => true,
            'order' => 2
        ]);

        Portfolio::create([
            'title' => 'School Website and PPDB System for SD 003 YKWI',
            'description' => 'As a fullstack developer in a lecturer service project, I was responsible for developing three integrated systems for SD 003 YKWI Pekanbaru. I built a school profile website with vision-mission features, activity gallery, announcement blog, etc. For the online PPDB system, I designed a digital registration form with document verification and admin dashboard. And finally, a location-based teacher attendance system where I implemented monthly attendance reports. During development, several challenges arose regarding technology choices. Initially, the team wanted an attendance system on one network, but due to various reasons, I proposed a geofencing solution.',
            'project_type' => 'Web System',
            'project_date' => '2023-06-10',
            'technologies' => 'Laravel, MySQL, Geofencing, Document Verification, Dashboard',
            'challenges' => 'Technology choice conflicts - team initially wanted network-based attendance system.',
            'solutions' => 'Proposed and implemented geofencing solution as a more flexible alternative for location-based attendance.',
            'github_url' => 'https://github.com/nurfznhanif/',
            'is_featured' => true,
            'order' => 3
        ]);

        // Create Experiences
        Experience::create([
            'position' => 'Teaching Assistant',
            'organization' => 'Islamic University of Riau',
            'description' => 'Assist the learning process for courses: Database, Introduction to Programming, Data Structure, Component-Based Programming, Artificial Intelligence, Basic Mobile Programming, Algorithm Design and Analysis, Graph Database, Native Mobile Application Development, Fuzzy Logic, and Machine Learning.',
            'start_date' => '2023-10-01',
            'is_current' => true,
            'order' => 1
        ]);

        Experience::create([
            'position' => 'Chairman',
            'organization' => 'Study Club Programming PRIMATECH – Islamic University of Riau',
            'description' => 'Led programming study club activities, organized coding workshops, and coordinated learning sessions for students interested in software development.',
            'start_date' => '2023-11-01',
            'end_date' => '2024-09-30',
            'is_current' => false,
            'order' => 2
        ]);

        Experience::create([
            'position' => 'Organizational Supervisory Board',
            'organization' => 'Perhentian Raja Student Association',
            'description' => 'Supervised organizational activities and ensured compliance with organizational guidelines and regulations.',
            'start_date' => '2023-11-01',
            'end_date' => '2024-11-30',
            'is_current' => false,
            'order' => 3
        ]);

        Experience::create([
            'position' => 'Event Division Chief',
            'organization' => 'Student Service Camp – Computer Science Student Association',
            'description' => 'Planned and coordinated student service camp activities, managed event logistics and team coordination.',
            'start_date' => '2024-05-01',
            'end_date' => '2024-05-31',
            'is_current' => false,
            'order' => 4
        ]);

        // Create Skills
        $skills = [
            // Web Programming
            ['name' => 'PHP', 'category' => 'Web Programming', 'level' => 'menengah', 'proficiency' => 75, 'order' => 1],
            ['name' => 'Laravel', 'category' => 'Web Programming', 'level' => 'menengah', 'proficiency' => 70, 'order' => 2],
            ['name' => 'JavaScript', 'category' => 'Web Programming', 'level' => 'menengah', 'proficiency' => 65, 'order' => 3],
            ['name' => 'Vue.js', 'category' => 'Web Programming', 'level' => 'menengah', 'proficiency' => 60, 'order' => 4],
            ['name' => 'React.js', 'category' => 'Web Programming', 'level' => 'pemula', 'proficiency' => 50, 'order' => 5],
            ['name' => 'CodeIgniter', 'category' => 'Web Programming', 'level' => 'menengah', 'proficiency' => 65, 'order' => 6],

            // Data Analysis
            ['name' => 'Python', 'category' => 'Data Analysis', 'level' => 'menengah', 'proficiency' => 70, 'order' => 1],
            ['name' => 'Google Colab', 'category' => 'Data Analysis', 'level' => 'menengah', 'proficiency' => 75, 'order' => 2],
            ['name' => 'Pandas', 'category' => 'Data Analysis', 'level' => 'menengah', 'proficiency' => 65, 'order' => 3],
            ['name' => 'NumPy', 'category' => 'Data Analysis', 'level' => 'menengah', 'proficiency' => 60, 'order' => 4],
            ['name' => 'Matplotlib', 'category' => 'Data Analysis', 'level' => 'pemula', 'proficiency' => 55, 'order' => 5],

            // Mobile Programming
            ['name' => 'React Native', 'category' => 'Mobile Programming', 'level' => 'menengah', 'proficiency' => 60, 'order' => 1],
            ['name' => 'Android Development', 'category' => 'Mobile Programming', 'level' => 'pemula', 'proficiency' => 45, 'order' => 2],

            // Database
            ['name' => 'MySQL', 'category' => 'Database', 'level' => 'menengah', 'proficiency' => 75, 'order' => 1],
            ['name' => 'Database Design', 'category' => 'Database', 'level' => 'menengah', 'proficiency' => 70, 'order' => 2],

            // Office Suite
            ['name' => 'Microsoft Word', 'category' => 'Office Suite', 'level' => 'mahir', 'proficiency' => 90, 'order' => 1],
            ['name' => 'Microsoft Excel', 'category' => 'Office Suite', 'level' => 'menengah', 'proficiency' => 70, 'order' => 2],
            ['name' => 'Microsoft PowerPoint', 'category' => 'Office Suite', 'level' => 'pemula', 'proficiency' => 50, 'order' => 3],

            // Soft Skills
            ['name' => 'Leadership', 'category' => 'Soft Skills', 'level' => 'mahir', 'proficiency' => 85, 'order' => 1],
            ['name' => 'Communication', 'category' => 'Soft Skills', 'level' => 'mahir', 'proficiency' => 80, 'order' => 2],
            ['name' => 'Teamwork', 'category' => 'Soft Skills', 'level' => 'mahir', 'proficiency' => 85, 'order' => 3],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // Create Social Links
        SocialLink::create([
            'platform' => 'GitHub',
            'url' => 'https://github.com/nurfznhanif/',
            'color' => '#333333',
            'is_active' => true,
            'order' => 1
        ]);

        SocialLink::create([
            'platform' => 'Email',
            'url' => 'mailto:nrfznhnf@gmail.com',
            'color' => '#EA4335',
            'is_active' => true,
            'order' => 2
        ]);

        SocialLink::create([
            'platform' => 'WhatsApp',
            'url' => 'https://wa.me/6282285685679',
            'color' => '#25D366',
            'is_active' => true,
            'order' => 3
        ]);

        // Create Languages
        Language::create([
            'name' => 'Indonesian',
            'level' => 'native',
            'proficiency' => 100,
            'description' => 'Native speaker with excellent command of the language'
        ]);

        Language::create([
            'name' => 'English',
            'level' => 'beginner',
            'proficiency' => 40,
            'description' => 'Able to understand simple instructions and communicate in everyday situations'
        ]);

        // Create Achievements
        Achievement::create([
            'title' => 'Teaching Assistant Excellence',
            'issuer' => 'Islamic University of Riau',
            'description' => 'Recognized for outstanding performance as teaching assistant across multiple computer science courses',
            'date_achieved' => '2024-01-15',
            'is_featured' => true
        ]);

        Achievement::create([
            'title' => 'Bachelor Degree with GPA 3.76',
            'issuer' => 'Islamic University of Riau',
            'description' => 'Graduated with excellent academic performance in Computer Science program',
            'date_achieved' => '2024-09-01',
            'is_featured' => true
        ]);

        Achievement::create([
            'title' => 'Programming Study Club Leadership',
            'issuer' => 'PRIMATECH - Islamic University of Riau',
            'description' => 'Successfully led programming study club and organized various coding workshops',
            'date_achieved' => '2024-09-30',
            'is_featured' => true
        ]);

        // Create Interests
        Interest::create([
            'name' => 'Web Programming',
            'description' => 'Passionate about building modern web applications using latest technologies',
            'icon' => '🌐',
            'color' => '#3b82f6',
            'is_featured' => true
        ]);

        Interest::create([
            'name' => 'Mobile Programming',
            'description' => 'Interested in developing cross-platform mobile applications',
            'icon' => '📱',
            'color' => '#10b981',
            'is_featured' => true
        ]);

        Interest::create([
            'name' => 'Artificial Intelligence',
            'description' => 'Exploring machine learning and AI applications in software development',
            'icon' => '🤖',
            'color' => '#8b5cf6',
            'is_featured' => true
        ]);

        Interest::create([
            'name' => 'Data Analysis',
            'description' => 'Analyzing data to extract meaningful insights and patterns',
            'icon' => '📊',
            'color' => '#f59e0b',
            'is_featured' => true
        ]);

        Interest::create([
            'name' => 'Software Architecture',
            'description' => 'Designing scalable and maintainable software systems',
            'icon' => '🏗️',
            'color' => '#ef4444',
            'is_featured' => true
        ]);

        Interest::create([
            'name' => 'Open Source',
            'description' => 'Contributing to open source projects and community development',
            'icon' => '🔓',
            'color' => '#06b6d4',
            'is_featured' => true
        ]);

        // Create Certifications (if any)
        Certification::create([
            'name' => 'Web Development Fundamentals',
            'issuer' => 'Self-taught through Various Online Platforms',
            'description' => 'Comprehensive understanding of web development technologies and best practices',
            'issue_date' => '2023-06-01',
            'skills' => ['PHP', 'JavaScript', 'Laravel', 'Vue.js', 'MySQL']
        ]);

        Certification::create([
            'name' => 'Database Management',
            'issuer' => 'Academic Coursework',
            'description' => 'Advanced knowledge in database design, optimization, and management',
            'issue_date' => '2023-12-01',
            'skills' => ['MySQL', 'Database Design', 'SQL Optimization']
        ]);
    }
}
