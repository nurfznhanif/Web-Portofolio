<?php
// database/seeders/ProfileDynamicSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProfileSetting;
use App\Models\SocialLink;
use App\Models\Achievement;
use App\Models\Language;
use App\Models\Interest;
use App\Models\Certification;

class ProfileDynamicSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedProfileSettings();
        $this->seedSocialLinks();
        $this->seedAchievements();
        $this->seedLanguages();
        $this->seedInterests();
        $this->seedCertifications();
    }

    private function seedProfileSettings()
    {
        $settings = [
            // Personal Information
            ['key' => 'name', 'value' => 'Nurfauzan Hanif', 'category' => 'personal'],
            ['key' => 'title', 'value' => 'Informatics Engineer', 'category' => 'personal'],
            ['key' => 'email', 'value' => 'nrfznhnf@gmail.com', 'category' => 'contact'],
            ['key' => 'phone', 'value' => '0822-8568-5679', 'category' => 'contact'],
            ['key' => 'location', 'value' => 'Pekanbaru, Riau, Indonesia', 'category' => 'contact'],
            ['key' => 'photo', 'value' => '/images/poto1.png', 'category' => 'personal', 'type' => 'image'],
            ['key' => 'avatar', 'value' => '/images/poto1.png', 'category' => 'personal', 'type' => 'image'],
            ['key' => 'cover_image', 'value' => '/images/cover-bg.jpg', 'category' => 'personal', 'type' => 'image'],

            // Professional Summary
            ['key' => 'summary', 'value' => 'Fresh Graduate of Informatics Engineering at Universitas Islam Riau with a strong foundation in full-stack development, AI/ML, and data analysis. Graduated with a 3.76 GPA and equipped with hands-on experience in modern web technologies, mobile development, and research methodologies. Open to career opportunities where I can contribute my technical expertise, problem-solving skills, and passion for innovation to deliver impactful solutions.', 'category' => 'professional', 'type' => 'text'],

            // Education Information
            ['key' => 'education_degree', 'value' => 'S1 Teknik Informatika', 'category' => 'education'],
            ['key' => 'education_university', 'value' => 'Universitas Islam Riau', 'category' => 'education'],
            ['key' => 'education_faculty', 'value' => 'Fakultas Teknik', 'category' => 'education'],
            ['key' => 'education_gpa', 'value' => '3.76', 'category' => 'education'],
            ['key' => 'education_status', 'value' => 'Final Year Student', 'category' => 'education'],
            ['key' => 'education_graduation_year', 'value' => '2025', 'category' => 'education'],
            ['key' => 'education_start_year', 'value' => '2021', 'category' => 'education'],
            ['key' => 'thesis_title', 'value' => 'Analisis Sentimen Berbasis Aspek terhadap Diskursus Generasi Emas 2045 di Media Sosial menggunakan Latent Dirichlet Allocation dan Bidirectional Encoder Representations from Transformers (BERT)', 'category' => 'education', 'type' => 'text'],
            ['key' => 'thesis_title_en', 'value' => 'Aspect-Based Sentiment Analysis of Golden Generation 2045 Discourse on Social Media using Latent Dirichlet Allocation and BERT', 'category' => 'education', 'type' => 'text'],

            // Career Objectives
            ['key' => 'objective_short_term', 'value' => 'Complete undergraduate degree with honors and gain practical industry experience through internships or part-time work.', 'category' => 'objectives', 'type' => 'text'],
            ['key' => 'objective_medium_term', 'value' => 'Develop expertise in AI/ML applications and contribute to meaningful projects in tech industry.', 'category' => 'objectives', 'type' => 'text'],
            ['key' => 'objective_long_term', 'value' => 'Become a senior software engineer specializing in AI-driven applications and contribute to open-source community.', 'category' => 'objectives', 'type' => 'text'],
            ['key' => 'passion', 'value' => 'Building technology solutions that make a positive impact on society and mentoring the next generation of developers.', 'category' => 'objectives', 'type' => 'text'],

            // Availability & Preferences
            ['key' => 'availability_status', 'value' => 'Available for Opportunities', 'category' => 'availability'],
            ['key' => 'availability_type', 'value' => 'Full-time, Part-time, Internship', 'category' => 'availability'],
            ['key' => 'availability_start_date', 'value' => '2025-01-01', 'category' => 'availability', 'type' => 'date'],
            ['key' => 'preferred_roles', 'value' => json_encode([
                'Backend Developer (Laravel, PHP)',
                'Frontend Developer (Vue.js, React)',
                'Full-Stack Web Developer',
                'AI/ML Engineer Intern',
                'Research & Development'
            ]), 'category' => 'availability', 'type' => 'json'],
            ['key' => 'work_preferences', 'value' => json_encode([
                'Remote Work',
                'Hybrid Work',
                'On-site (Pekanbaru, Riau)',
                'Flexible Schedule'
            ]), 'category' => 'availability', 'type' => 'json'],

            // Technical Stack
            ['key' => 'tech_stack_backend', 'value' => json_encode(['Laravel', 'PHP', 'Node.js', 'Python']), 'category' => 'technical', 'type' => 'json'],
            ['key' => 'tech_stack_frontend', 'value' => json_encode(['Vue.js', 'JavaScript', 'HTML5', 'CSS3', 'Tailwind CSS']), 'category' => 'technical', 'type' => 'json'],
            ['key' => 'tech_stack_database', 'value' => json_encode(['MySQL', 'PostgreSQL', 'SQLite']), 'category' => 'technical', 'type' => 'json'],
            ['key' => 'tech_stack_tools', 'value' => json_encode(['Git', 'VS Code', 'Postman', 'Docker (learning)']), 'category' => 'technical', 'type' => 'json'],
            ['key' => 'tech_stack_deployment', 'value' => json_encode(['cPanel', 'Heroku', 'Vercel']), 'category' => 'technical', 'type' => 'json'],
            ['key' => 'tech_stack_ai_ml', 'value' => json_encode(['Python', 'TensorFlow', 'Scikit-learn', 'Pandas', 'NumPy']), 'category' => 'technical', 'type' => 'json'],

            // Contact Preferences
            ['key' => 'contact_best_time', 'value' => 'Monday-Friday, 9AM-5PM WIB', 'category' => 'contact'],
            ['key' => 'contact_response_time', 'value' => 'Within 24 hours', 'category' => 'contact'],
            ['key' => 'contact_preferred_method', 'value' => 'Email or WhatsApp', 'category' => 'contact'],
            ['key' => 'contact_languages', 'value' => 'Indonesian, English', 'category' => 'contact'],
            ['key' => 'contact_timezone', 'value' => 'Asia/Jakarta (WIB, UTC+7)', 'category' => 'contact'],

            // Fun Facts
            ['key' => 'fun_facts', 'value' => json_encode([
                '🎓 Teaching programming to fellow students since 2023',
                '💻 Built multiple web applications using modern frameworks',
                '📊 Passionate about data analysis and visualization',
                '🤖 Currently researching AI applications in social media',
                '📚 Enjoys reading about emerging technologies',
                '🌱 Always learning new programming languages and frameworks'
            ]), 'category' => 'personal', 'type' => 'json'],

            // Current Focus
            ['key' => 'current_focus', 'value' => json_encode([
                'Completing final year thesis research',
                'Lecturer assistant responsibilities',
                'Building portfolio projects',
                'Learning advanced AI/ML techniques',
                'Contributing to open-source projects',
                'Preparing for industry transition'
            ]), 'category' => 'professional', 'type' => 'json'],

            // Core Skills
            ['key' => 'core_skills', 'value' => json_encode([
                'Laravel & PHP Development',
                'Vue.js & JavaScript',
                'Python & AI/ML Libraries',
                'Database Management (MySQL, PostgreSQL)',
                'RESTful API Design',
                'Git Version Control',
                'Teaching & Knowledge Transfer',
                'Project Management',
                'Research Methodology',
                'Problem Solving'
            ]), 'category' => 'professional', 'type' => 'json'],

            // SEO & Meta Settings
            ['key' => 'site_title', 'value' => 'Nurfauzan Hanif - Informatics Engineer Portfolio', 'category' => 'seo'],
            ['key' => 'site_description', 'value' => 'Portfolio of Nurfauzan Hanif, Computer Science Student at Universitas Islam Riau. Specializing in full-stack development, AI/ML, and data analysis.', 'category' => 'seo', 'type' => 'text'],
            ['key' => 'site_keywords', 'value' => 'Nurfauzan Hanif, Computer Science, Laravel, Vue.js, AI, Machine Learning, Full Stack Developer, Indonesia', 'category' => 'seo'],

            // Analytics & Configuration
            ['key' => 'enable_analytics', 'value' => 'true', 'category' => 'config', 'type' => 'boolean'],
            ['key' => 'enable_contact_form', 'value' => 'true', 'category' => 'config', 'type' => 'boolean'],
            ['key' => 'enable_animations', 'value' => 'true', 'category' => 'config', 'type' => 'boolean'],
            ['key' => 'theme', 'value' => 'modern', 'category' => 'config'],
            ['key' => 'items_per_page', 'value' => '6', 'category' => 'config', 'type' => 'number'],
            ['key' => 'cache_duration', 'value' => '3600', 'category' => 'config', 'type' => 'number']
        ];

        foreach ($settings as $index => $setting) {
            ProfileSetting::create(array_merge($setting, [
                'type' => $setting['type'] ?? 'text',
                'is_public' => true,
                'sort_order' => $index
            ]));
        }
    }

    private function seedSocialLinks()
    {
        $socialLinks = [
            [
                'platform' => 'GitHub',
                'url' => 'https://github.com/nurfznhanif',
                'icon' => 'github',
                'color' => '#333333',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'platform' => 'LinkedIn',
                'url' => 'https://linkedin.com/in/nurfauzan-hanif',
                'icon' => 'linkedin',
                'color' => '#0077B5',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'platform' => 'Email',
                'url' => 'mailto:nrfznhnf@gmail.com',
                'icon' => 'email',
                'color' => '#EA4335',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'platform' => 'WhatsApp',
                'url' => 'https://wa.me/6282285685679',
                'icon' => 'whatsapp',
                'color' => '#25D366',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'platform' => 'Telegram',
                'url' => 'https://t.me/nurfznhanif',
                'icon' => 'telegram',
                'color' => '#0088CC',
                'is_active' => true,
                'sort_order' => 5
            ]
        ];

        foreach ($socialLinks as $link) {
            SocialLink::create($link);
        }
    }

    private function seedAchievements()
    {
        $achievements = [
            [
                'title' => 'High Academic Performance',
                'description' => 'Maintaining GPA 3.76 while serving as Teaching Assistant for multiple computer science courses',
                'date_achieved' => '2024-12-01',
                'category' => 'academic',
                'issuer' => 'Universitas Islam Riau',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'title' => 'Teaching Assistant Selection',
                'description' => 'Selected as Teaching Assistant for Database Systems, Programming Introduction, Data Structures, and other core CS courses',
                'date_achieved' => '2023-10-01',
                'category' => 'professional',
                'issuer' => 'Universitas Islam Riau - Computer Science Department',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'title' => 'Study Club Programming Chairman',
                'description' => 'Led PRIMATECH programming study club, organizing workshops and mentoring junior students',
                'date_achieved' => '2023-11-01',
                'category' => 'professional',
                'issuer' => 'Study Club Programming PRIMATECH',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'title' => 'Research Project Completion',
                'description' => 'Successfully completed thesis research on Aspect-Based Sentiment Analysis using LDA and BERT',
                'date_achieved' => '2024-12-01',
                'category' => 'research',
                'issuer' => 'Universitas Islam Riau',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'title' => 'Multiple Web Application Projects',
                'description' => 'Successfully developed and deployed multiple web applications including Geopark Sawahlunto Website, Student Election System, and School Management System',
                'date_achieved' => '2024-06-01',
                'category' => 'professional',
                'issuer' => 'Personal Projects & Community Service',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'title' => 'Event Division Leadership',
                'description' => 'Successfully led event planning and execution for Student Service Camp activities',
                'date_achieved' => '2024-05-31',
                'category' => 'professional',
                'issuer' => 'Computer Science Student Association',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 6
            ]
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }

    private function seedLanguages()
    {
        $languages = [
            [
                'name' => 'Bahasa Indonesia',
                'level' => 'native',
                'proficiency' => 100,
                'description' => 'Native speaker with excellent written and verbal communication skills',
                'certificate' => null,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'English',
                'level' => 'intermediate',
                'proficiency' => 70,
                'description' => 'Proficient in technical documentation, academic papers, and professional communication',
                'certificate' => null,
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Arabic',
                'level' => 'basic',
                'proficiency' => 40,
                'description' => 'Basic understanding for religious studies and simple conversation',
                'certificate' => null,
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }

    private function seedInterests()
    {
        $interests = [
            [
                'name' => 'Full-Stack Web Development',
                'description' => 'Passionate about building complete web applications from frontend to backend',
                'category' => 'technical',
                'icon' => '💻',
                'color' => '#3B82F6',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Artificial Intelligence & Machine Learning',
                'description' => 'Researching and implementing AI/ML solutions for real-world problems',
                'category' => 'technical',
                'icon' => '🤖',
                'color' => '#9333EA',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Data Science & Analytics',
                'description' => 'Analyzing data to extract meaningful insights and patterns',
                'category' => 'technical',
                'icon' => '📊',
                'color' => '#059669',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Creating cross-platform mobile applications with modern frameworks',
                'category' => 'technical',
                'icon' => '📱',
                'color' => '#DC2626',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Teaching & Mentoring',
                'description' => 'Sharing knowledge and helping others learn programming concepts',
                'category' => 'personal',
                'icon' => '🎓',
                'color' => '#7C3AED',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Research & Innovation',
                'description' => 'Exploring new technologies and methodologies in computer science',
                'category' => 'academic',
                'icon' => '🔬',
                'color' => '#0891B2',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 6
            ],
            [
                'name' => 'Natural Language Processing',
                'description' => 'Working with text analysis, sentiment analysis, and language models',
                'category' => 'technical',
                'icon' => '🗣️',
                'color' => '#EA580C',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 7
            ],
            [
                'name' => 'Database Design',
                'description' => 'Designing efficient and scalable database architectures',
                'category' => 'technical',
                'icon' => '🗄️',
                'color' => '#65A30D',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 8
            ],
            [
                'name' => 'API Development',
                'description' => 'Building robust and well-documented RESTful APIs',
                'category' => 'technical',
                'icon' => '🔗',
                'color' => '#7C2D12',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 9
            ],
            [
                'name' => 'Open Source Contribution',
                'description' => 'Contributing to open source projects and community development',
                'category' => 'personal',
                'icon' => '🌍',
                'color' => '#1F2937',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 10
            ]
        ];

        foreach ($interests as $interest) {
            Interest::create($interest);
        }
    }

    private function seedCertifications()
    {
        $certifications = [
            [
                'name' => 'Web Development Fundamentals',
                'issuer' => 'Self-Study & Practice',
                'issue_date' => '2023-06-01',
                'expiry_date' => null,
                'credential_id' => null,
                'credential_url' => null,
                'skills' => json_encode(['HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel']),
                'image' => null,
                'description' => 'Comprehensive understanding of web development fundamentals including frontend and backend technologies',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Database Management Systems',
                'issuer' => 'University Coursework',
                'issue_date' => '2023-12-01',
                'expiry_date' => null,
                'credential_id' => null,
                'credential_url' => null,
                'skills' => json_encode(['MySQL', 'PostgreSQL', 'Database Design', 'SQL Optimization']),
                'image' => null,
                'description' => 'Advanced coursework in database systems design, implementation, and optimization',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Machine Learning Fundamentals',
                'issuer' => 'Research & Academic Study',
                'issue_date' => '2024-03-01',
                'expiry_date' => null,
                'credential_id' => null,
                'credential_url' => null,
                'skills' => json_encode(['Python', 'TensorFlow', 'Scikit-learn', 'Data Analysis', 'BERT']),
                'image' => null,
                'description' => 'Practical application of machine learning algorithms in thesis research project',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Teaching Assistant Certification',
                'issuer' => 'Universitas Islam Riau',
                'issue_date' => '2023-10-01',
                'expiry_date' => '2025-07-30',
                'credential_id' => 'TA-2023-CS-001',
                'credential_url' => null,
                'skills' => json_encode(['Teaching', 'Mentoring', 'Course Management', 'Student Assessment']),
                'image' => null,
                'description' => 'Certified Teaching Assistant for Computer Science courses including Database Systems, Programming, and AI',
                'is_active' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($certifications as $certification) {
            Certification::create($certification);
        }
    }
}
