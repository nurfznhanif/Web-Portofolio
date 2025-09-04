<template>
  <div class="min-h-screen dark-gothic">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glossy-effect">
      <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
          <h1 class="text-2xl font-bold text-white font-serif">
            {{ profile.name }}
          </h1>
          <div class="hidden md:flex space-x-8">
            <a
              href="#home"
              class="text-gray-300 hover:text-red-400 transition-colors"
              >Home</a
            >
            <a
              href="#about"
              class="text-gray-300 hover:text-red-400 transition-colors"
              >About</a
            >
            <a
              href="#portfolio"
              class="text-gray-300 hover:text-red-400 transition-colors"
              >Portfolio</a
            >
            <a
              href="#experience"
              class="text-gray-300 hover:text-red-400 transition-colors"
              >Experience</a
            >
            <a
              href="#skills"
              class="text-gray-300 hover:text-red-400 transition-colors"
              >Skills</a
            >
            <a
              href="#contact"
              class="text-gray-300 hover:text-red-400 transition-colors"
              >Contact</a
            >
          </div>
          <!-- Mobile Menu Button -->
          <button
            @click="toggleMobileMenu"
            class="md:hidden text-white hover:text-red-400 transition-colors"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              ></path>
            </svg>
          </button>
        </div>

        <!-- Mobile Menu -->
        <div v-if="showMobileMenu" class="md:hidden mt-4 pb-4">
          <div class="flex flex-col space-y-2">
            <a
              href="#home"
              @click="closeMobileMenu"
              class="text-gray-300 hover:text-red-400 transition-colors py-2"
              >Home</a
            >
            <a
              href="#about"
              @click="closeMobileMenu"
              class="text-gray-300 hover:text-red-400 transition-colors py-2"
              >About</a
            >
            <a
              href="#portfolio"
              @click="closeMobileMenu"
              class="text-gray-300 hover:text-red-400 transition-colors py-2"
              >Portfolio</a
            >
            <a
              href="#experience"
              @click="closeMobileMenu"
              class="text-gray-300 hover:text-red-400 transition-colors py-2"
              >Experience</a
            >
            <a
              href="#skills"
              @click="closeMobileMenu"
              class="text-gray-300 hover:text-red-400 transition-colors py-2"
              >Skills</a
            >
            <a
              href="#contact"
              @click="closeMobileMenu"
              class="text-gray-300 hover:text-red-400 transition-colors py-2"
              >Contact</a
            >
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section with Photo -->
    <section
      id="home"
      class="min-h-screen flex items-center justify-center relative overflow-hidden"
    >
      <div
        class="absolute inset-0 bg-gradient-to-br from-red-900/20 to-black/50"
      ></div>

      <div class="container mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <!-- Photo Section -->
          <div class="flex justify-center lg:justify-end order-2 lg:order-1">
            <div class="relative animate-fade-in-up">
              <!-- Photo Container with Gothic Frame Effect -->
              <div class="relative photo-container">
                <!-- Gothic Border Effect -->
                <div
                  class="absolute -inset-4 bg-gradient-to-r from-red-600 via-red-800 to-red-600 rounded-2xl blur opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200 animate-tilt"
                ></div>

                <!-- Photo -->
                <div
                  class="relative bg-gray-900 rounded-2xl p-2 photo-border-effect"
                >
                  <img
                    :src="profilePhoto"
                    :alt="profile.name"
                    class="w-80 h-80 lg:w-96 lg:h-96 object-cover rounded-xl shadow-2xl profile-photo"
                    @error="handleImageError"
                    loading="lazy"
                  />

                  <!-- Overlay Gradient -->
                  <div
                    class="absolute inset-2 bg-gradient-to-t from-black/20 to-transparent rounded-xl pointer-events-none"
                  ></div>
                </div>

                <!-- Floating Elements -->
                <div
                  class="absolute -top-6 -right-6 w-12 h-12 bg-red-600 rounded-full animate-float opacity-70"
                ></div>
                <div
                  class="absolute -bottom-4 -left-4 w-8 h-8 bg-red-400 rounded-full animate-bounce opacity-80"
                ></div>
                <div
                  class="absolute top-1/2 -left-8 w-6 h-6 bg-red-500 rounded-full animate-pulse opacity-60"
                ></div>
              </div>
            </div>
          </div>

          <!-- Text Content -->
          <div class="text-center lg:text-left order-1 lg:order-2">
            <div class="animate-fade-in-up">
              <h1
                class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 font-serif text-glow"
              >
                {{ profile.name }}
              </h1>
              <p class="text-xl md:text-2xl text-red-400 mb-8 font-light">
                {{ profile.title }}
              </p>
              <p
                class="text-lg text-gray-300 max-w-2xl mx-auto lg:mx-0 mb-10 leading-relaxed"
              >
                {{ profile.summary }}
              </p>

              <!-- Quick Stats -->
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-10">
                <div class="gothic-card p-4 rounded-lg text-center">
                  <div class="text-2xl font-bold text-red-400">
                    {{ portfolios.length }}+
                  </div>
                  <div class="text-sm text-gray-300">Projects</div>
                </div>
                <div class="gothic-card p-4 rounded-lg text-center">
                  <div class="text-2xl font-bold text-red-400">
                    {{ profile.education.gpa }}
                  </div>
                  <div class="text-sm text-gray-300">GPA</div>
                </div>
                <div
                  class="gothic-card p-4 rounded-lg text-center md:col-span-1 col-span-2"
                >
                  <div class="text-2xl font-bold text-red-400">
                    {{ yearsOfExperience }}+
                  </div>
                  <div class="text-sm text-gray-300">Years Experience</div>
                </div>
              </div>

              <div
                class="flex flex-col sm:flex-row justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-6"
              >
                <a
                  :href="profile.github"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="blood-red-gradient px-8 py-3 text-white font-semibold rounded-lg hover:scale-105 transition-transform shadow-lg animate-shadow-pulse inline-flex items-center justify-center"
                >
                  <span class="mr-2">🔗</span>
                  View GitHub
                </a>
                <a
                  href="#portfolio"
                  class="border-2 border-red-600 px-8 py-3 text-red-400 hover:bg-red-600 hover:text-white transition-all rounded-lg inline-flex items-center justify-center"
                >
                  <span class="mr-2">💼</span>
                  View Portfolio
                </a>
                <a
                  :href="`mailto:${profile.email}`"
                  class="bg-gray-800 border border-gray-600 px-8 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-all rounded-lg inline-flex items-center justify-center"
                >
                  <span class="mr-2">✉️</span>
                  Contact Me
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-black/30">
      <div class="container mx-auto px-6">
        <h2
          class="text-4xl font-bold text-center text-white mb-16 font-serif text-glow"
        >
          About Me
        </h2>
        <div class="grid lg:grid-cols-3 gap-12">
          <!-- Profile Summary with photo -->
          <div class="lg:col-span-1">
            <div class="gothic-card p-6 rounded-xl text-center hover-lift">
              <div class="mb-6">
                <img
                  :src="profilePhoto"
                  :alt="profile.name"
                  class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-red-600/50 shadow-lg profile-photo"
                  @error="handleImageError"
                  loading="lazy"
                />
              </div>
              <h3 class="text-xl font-bold text-red-400 mb-2">
                {{ profile.name }}
              </h3>
              <p class="text-gray-300 mb-4">{{ profile.title }}</p>
              <div class="space-y-3 text-sm text-gray-400">
                <div class="flex items-center justify-center">
                  <span class="text-red-400 mr-2">📧</span>
                  <a
                    :href="'mailto:' + profile.email"
                    class="hover:text-red-400 transition-colors"
                  >
                    {{ profile.email }}
                  </a>
                </div>
                <div class="flex items-center justify-center">
                  <span class="text-red-400 mr-2">📱</span>
                  <a
                    :href="'tel:' + profile.phone"
                    class="hover:text-red-400 transition-colors"
                  >
                    {{ profile.phone }}
                  </a>
                </div>
                <div class="flex items-center justify-center">
                  <span class="text-red-400 mr-2">📍</span>
                  <span>{{ profile.location }}</span>
                </div>
                <div class="flex items-center justify-center">
                  <span class="text-red-400 mr-2">🔗</span>
                  <a
                    :href="profile.github"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="hover:text-red-400 transition-colors"
                  >
                    GitHub Profile
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Education & Details -->
          <div class="lg:col-span-2 space-y-8">
            <div class="gothic-card p-8 rounded-xl hover-lift">
              <h3
                class="text-2xl font-bold text-red-400 mb-6 flex items-center"
              >
                <span class="mr-3">🎓</span>
                Education
              </h3>
              <div class="space-y-6 text-gray-300">
                <div class="border-l-4 border-red-600 pl-6">
                  <h4 class="font-semibold text-white text-xl mb-2">
                    {{ profile.education.degree }}
                  </h4>
                  <p class="text-red-300 text-lg mb-2">
                    {{ profile.education.university }}
                  </p>
                  <p class="text-sm mb-3">
                    <span class="font-semibold">GPA:</span>
                    {{ profile.education.gpa }}
                  </p>
                  <div class="bg-gray-800/50 p-4 rounded-lg">
                    <h5 class="font-semibold text-white mb-2">
                      Thesis Research:
                    </h5>
                    <p class="text-sm leading-relaxed">
                      {{ profile.education.thesis }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="gothic-card p-8 rounded-xl hover-lift">
              <h3
                class="text-2xl font-bold text-red-400 mb-6 flex items-center"
              >
                <span class="mr-3">🎯</span>
                Areas of Interest
              </h3>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <span
                  v-for="interest in profile.interests"
                  :key="interest"
                  class="px-4 py-3 bg-red-900/30 text-red-300 rounded-lg text-sm border border-red-800 hover:bg-red-800/30 transition-colors text-center font-medium"
                >
                  {{ interest }}
                </span>
              </div>
            </div>

            <!-- Personal Qualities -->
            <div class="gothic-card p-8 rounded-xl hover-lift">
              <h3
                class="text-2xl font-bold text-red-400 mb-6 flex items-center"
              >
                <span class="mr-3">⭐</span>
                Core Strengths
              </h3>
              <div class="grid md:grid-cols-2 gap-6 text-gray-300">
                <div>
                  <h4 class="font-semibold text-white mb-2">
                    Leadership & Teamwork
                  </h4>
                  <p class="text-sm">
                    Experienced in leading programming study clubs and managing
                    organizational activities.
                  </p>
                </div>
                <div>
                  <h4 class="font-semibold text-white mb-2">
                    Technical Expertise
                  </h4>
                  <p class="text-sm">
                    Proficient in full-stack development with modern frameworks
                    and AI/ML technologies.
                  </p>
                </div>
                <div>
                  <h4 class="font-semibold text-white mb-2">
                    Teaching & Mentoring
                  </h4>
                  <p class="text-sm">
                    Active teaching assistant helping students with various
                    computer science subjects.
                  </p>
                </div>
                <div>
                  <h4 class="font-semibold text-white mb-2">Problem Solving</h4>
                  <p class="text-sm">
                    Strong analytical skills with experience in research and
                    practical implementation.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-20 particles-bg relative">
      <div class="container mx-auto px-6 relative z-10">
        <h2
          class="text-4xl font-bold text-center text-white mb-16 font-serif text-glow"
        >
          Featured Projects
        </h2>

        <!-- Portfolio Filter -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
          <button
            @click="filterProjects('all')"
            :class="[
              'px-6 py-2 rounded-lg transition-all font-medium',
              selectedFilter === 'all'
                ? 'bg-red-600 text-white'
                : 'bg-gray-800 text-gray-300 hover:bg-gray-700',
            ]"
          >
            All Projects
          </button>
          <button
            v-for="type in projectTypes"
            :key="type"
            @click="filterProjects(type)"
            :class="[
              'px-6 py-2 rounded-lg transition-all font-medium',
              selectedFilter === type
                ? 'bg-red-600 text-white'
                : 'bg-gray-800 text-gray-300 hover:bg-gray-700',
            ]"
          >
            {{ type }}
          </button>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="project in filteredProjects"
            :key="project.id"
            class="gothic-card p-6 rounded-xl hover-lift group cursor-pointer transform transition-all duration-300"
            @click="showProject(project)"
          >
            <div class="mb-4">
              <h3
                class="text-xl font-bold text-white group-hover:text-red-400 transition-colors mb-2"
              >
                {{ project.title }}
              </h3>
              <div class="flex items-center justify-between">
                <span
                  class="px-3 py-1 bg-red-900/30 text-red-300 rounded-full text-xs border border-red-800"
                >
                  {{ project.project_type }}
                </span>
                <span class="text-gray-400 text-sm">
                  {{ formatDate(project.project_date) }}
                </span>
              </div>
            </div>

            <p class="text-gray-300 text-sm mb-4 line-clamp-3 leading-relaxed">
              {{ project.description }}
            </p>

            <div class="mb-4">
              <h4 class="text-sm font-semibold text-red-400 mb-2">
                Technologies:
              </h4>
              <div class="flex flex-wrap gap-1">
                <span
                  v-for="tech in getTechnologies(project.technologies).slice(
                    0,
                    4
                  )"
                  :key="tech"
                  class="px-2 py-1 bg-gray-700 text-gray-300 rounded text-xs"
                >
                  {{ tech }}
                </span>
                <span
                  v-if="getTechnologies(project.technologies).length > 4"
                  class="px-2 py-1 bg-gray-600 text-gray-400 rounded text-xs"
                >
                  +{{ getTechnologies(project.technologies).length - 4 }}
                </span>
              </div>
            </div>

            <div class="flex justify-between items-center">
              <div class="flex items-center space-x-2">
                <span
                  v-if="project.challenges"
                  class="text-yellow-400 text-xs"
                  title="Has Challenges"
                  >⚡</span
                >
                <span
                  v-if="project.solutions"
                  class="text-green-400 text-xs"
                  title="Solutions Provided"
                  >✅</span
                >
                <span
                  v-if="project.github_url"
                  class="text-blue-400 text-xs"
                  title="GitHub Available"
                  >🔗</span
                >
              </div>
              <span
                class="text-red-400 group-hover:translate-x-1 transition-transform text-lg"
                >→</span
              >
            </div>
          </div>
        </div>

        <!-- View All Projects Button -->
        <div class="text-center mt-12" v-if="filteredProjects.length >= 6">
          <button
            @click="showAllProjects = !showAllProjects"
            class="blood-red-gradient px-8 py-3 text-white font-semibold rounded-lg hover:scale-105 transition-transform shadow-lg"
          >
            {{ showAllProjects ? "Show Less" : "View All Projects" }}
          </button>
        </div>
      </div>
    </section>

    <!-- Experience Section -->
    <section id="experience" class="py-20 bg-black/30">
      <div class="container mx-auto px-6">
        <h2
          class="text-4xl font-bold text-center text-white mb-16 font-serif text-glow"
        >
          Professional Experience
        </h2>
        <div class="max-w-4xl mx-auto">
          <div
            v-for="(experience, index) in experiences"
            :key="experience.id"
            class="relative mb-8 last:mb-0"
          >
            <!-- Timeline line -->
            <div
              v-if="index !== experiences.length - 1"
              class="absolute left-4 top-16 w-0.5 h-full bg-gradient-to-b from-red-600/50 to-transparent"
            ></div>

            <!-- Timeline dot -->
            <div
              class="absolute left-2 top-4 w-4 h-4 bg-red-600 rounded-full border-4 border-gray-900 shadow-lg z-10"
              :class="{ 'animate-pulse': experience.is_current }"
            ></div>

            <!-- Content -->
            <div class="ml-12 gothic-card p-6 rounded-xl hover-lift">
              <div
                class="flex flex-col md:flex-row md:justify-between md:items-start mb-4"
              >
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-white mb-1">
                    {{ experience.position }}
                  </h3>
                  <p class="text-red-400 font-medium mb-2">
                    {{ experience.organization }}
                  </p>
                  <div
                    class="flex items-center space-x-4 text-sm text-gray-400"
                  >
                    <span class="flex items-center">
                      <span class="mr-1">📅</span>
                      {{ formatDate(experience.start_date) }} -
                      {{
                        experience.is_current
                          ? "Present"
                          : formatDate(experience.end_date)
                      }}
                    </span>
                    <span
                      v-if="experience.is_current"
                      class="px-2 py-1 bg-green-900/30 text-green-300 rounded-full text-xs border border-green-700"
                    >
                      Current Position
                    </span>
                  </div>
                </div>
              </div>
              <p class="text-gray-300 leading-relaxed">
                {{ experience.description }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="py-20">
      <div class="container mx-auto px-6">
        <h2
          class="text-4xl font-bold text-center text-white mb-16 font-serif text-glow"
        >
          Technical Expertise
        </h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="(skillList, category) in skills"
            :key="category"
            class="gothic-card p-6 rounded-xl hover-lift"
          >
            <div class="flex items-center mb-6">
              <span class="text-2xl mr-3">{{ getCategoryIcon(category) }}</span>
              <h3 class="text-xl font-bold text-red-400">{{ category }}</h3>
            </div>

            <div class="space-y-4">
              <div v-for="skill in skillList" :key="skill.id" class="group">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-white font-medium">{{ skill.name }}</span>
                  <span
                    class="text-gray-400 text-xs capitalize px-2 py-1 bg-gray-700 rounded"
                  >
                    {{ skill.level }}
                  </span>
                </div>
                <div
                  class="w-full bg-gray-800 rounded-full h-2 overflow-hidden"
                >
                  <div
                    class="h-full bg-gradient-to-r from-red-600 to-red-400 rounded-full transition-all duration-1000 ease-out group-hover:animate-pulse"
                    :style="{ width: getSkillWidth(skill.level) }"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Skill Summary -->
        <div class="mt-16 text-center">
          <div class="gothic-card p-8 rounded-xl max-w-4xl mx-auto">
            <h3 class="text-2xl font-bold text-red-400 mb-6">
              Skill Highlights
            </h3>
            <div class="grid md:grid-cols-3 gap-6 text-center">
              <div>
                <div class="text-3xl font-bold text-white mb-2">
                  {{ totalSkillCount }}
                </div>
                <div class="text-gray-300">Total Skills</div>
              </div>
              <div>
                <div class="text-3xl font-bold text-green-400 mb-2">
                  {{ advancedSkillCount }}
                </div>
                <div class="text-gray-300">Advanced Level</div>
              </div>
              <div>
                <div class="text-3xl font-bold text-blue-400 mb-2">
                  {{ Object.keys(skills).length }}
                </div>
                <div class="text-gray-300">Categories</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-black/30">
      <div class="container mx-auto px-6">
        <h2
          class="text-4xl font-bold text-center text-white mb-16 font-serif text-glow"
        >
          Let's Connect
        </h2>

        <div class="max-w-4xl mx-auto">
          <div class="grid md:grid-cols-2 gap-8">
            <!-- Contact Info -->
            <div class="gothic-card p-8 rounded-xl hover-lift">
              <h3 class="text-2xl font-bold text-red-400 mb-6">Get In Touch</h3>
              <div class="space-y-4">
                <div class="flex items-center space-x-4">
                  <div
                    class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center"
                  >
                    <span class="text-white text-xl">📧</span>
                  </div>
                  <div>
                    <p class="text-gray-400 text-sm">Email</p>
                    <a
                      :href="`mailto:${profile.email}`"
                      class="text-white hover:text-red-400 transition-colors font-medium"
                    >
                      {{ profile.email }}
                    </a>
                  </div>
                </div>

                <div class="flex items-center space-x-4">
                  <div
                    class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center"
                  >
                    <span class="text-white text-xl">📱</span>
                  </div>
                  <div>
                    <p class="text-gray-400 text-sm">Phone</p>
                    <a
                      :href="`tel:${profile.phone}`"
                      class="text-white hover:text-red-400 transition-colors font-medium"
                    >
                      {{ profile.phone }}
                    </a>
                  </div>
                </div>

                <div class="flex items-center space-x-4">
                  <div
                    class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center"
                  >
                    <span class="text-white text-xl">📍</span>
                  </div>
                  <div>
                    <p class="text-gray-400 text-sm">Location</p>
                    <p class="text-white font-medium">{{ profile.location }}</p>
                  </div>
                </div>

                <div class="flex items-center space-x-4">
                  <div
                    class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center"
                  >
                    <span class="text-white text-xl">🔗</span>
                  </div>
                  <div>
                    <p class="text-gray-400 text-sm">GitHub</p>
                    <a
                      :href="profile.github"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="text-white hover:text-red-400 transition-colors font-medium"
                    >
                      View My Projects
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quick Contact Form -->
            <div class="gothic-card p-8 rounded-xl hover-lift">
              <h3 class="text-2xl font-bold text-red-400 mb-6">
                Quick Message
              </h3>
              <form @submit.prevent="sendQuickMessage" class="space-y-4">
                <div>
                  <input
                    type="text"
                    v-model="contactForm.name"
                    placeholder="Your Name"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    required
                  />
                </div>
                <div>
                  <input
                    type="email"
                    v-model="contactForm.email"
                    placeholder="Your Email"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    required
                  />
                </div>
                <div>
                  <textarea
                    v-model="contactForm.message"
                    placeholder="Your Message"
                    rows="4"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-vertical"
                    required
                  ></textarea>
                </div>
                <button
                  type="submit"
                  :disabled="contactForm.processing"
                  class="w-full blood-red-gradient px-6 py-3 text-white font-semibold rounded-lg hover:scale-105 transition-transform shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="contactForm.processing">Sending...</span>
                  <span v-else>Send Message</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 bg-black/50">
      <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-3 gap-8 mb-8">
          <!-- Profile Summary -->
          <div>
            <h3 class="text-xl font-bold text-white mb-4">
              {{ profile.name }}
            </h3>
            <p class="text-gray-400 text-sm leading-relaxed mb-4">
              Computer Science student passionate about web development, AI, and
              teaching. Currently serving as Teaching Assistant at Universitas
              Islam Riau.
            </p>
            <div class="flex space-x-4">
              <a
                :href="profile.github"
                target="_blank"
                rel="noopener noreferrer"
                class="text-gray-400 hover:text-red-400 transition-colors"
                title="GitHub"
              >
                <span class="text-xl">🔗</span>
              </a>
              <a
                :href="`mailto:${profile.email}`"
                class="text-gray-400 hover:text-red-400 transition-colors"
                title="Email"
              >
                <span class="text-xl">📧</span>
              </a>
              <a
                :href="`tel:${profile.phone}`"
                class="text-gray-400 hover:text-red-400 transition-colors"
                title="Phone"
              >
                <span class="text-xl">📱</span>
              </a>
            </div>
          </div>

          <!-- Quick Links -->
          <div>
            <h3 class="text-xl font-bold text-white mb-4">Quick Links</h3>
            <div class="space-y-2">
              <a
                href="#home"
                class="block text-gray-400 hover:text-red-400 transition-colors text-sm"
                >Home</a
              >
              <a
                href="#about"
                class="block text-gray-400 hover:text-red-400 transition-colors text-sm"
                >About</a
              >
              <a
                href="#portfolio"
                class="block text-gray-400 hover:text-red-400 transition-colors text-sm"
                >Portfolio</a
              >
              <a
                href="#experience"
                class="block text-gray-400 hover:text-red-400 transition-colors text-sm"
                >Experience</a
              >
              <a
                href="#skills"
                class="block text-gray-400 hover:text-red-400 transition-colors text-sm"
                >Skills</a
              >
              <a
                href="#contact"
                class="block text-gray-400 hover:text-red-400 transition-colors text-sm"
                >Contact</a
              >
            </div>
          </div>

          <!-- Current Focus -->
          <div>
            <h3 class="text-xl font-bold text-white mb-4">Current Focus</h3>
            <div class="space-y-3 text-sm text-gray-400">
              <div class="flex items-center space-x-2">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                <span>Teaching Assistant at UIR</span>
              </div>
              <div class="flex items-center space-x-2">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                <span>Final Year Computer Science</span>
              </div>
              <div class="flex items-center space-x-2">
                <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                <span>AI/ML Research Projects</span>
              </div>
              <div class="flex items-center space-x-2">
                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                <span>Full-Stack Development</span>
              </div>
            </div>
          </div>
        </div>

        <div class="border-t border-gray-700 pt-8 text-center">
          <p class="text-gray-400 text-sm">
            &copy; {{ new Date().getFullYear() }} {{ profile.name }}. All rights
            reserved.
          </p>
          <p class="text-gray-500 text-xs mt-2">
            Built with Laravel, Vue.js, and lots of ☕ | GPA:
            {{ profile.education.gpa }}
          </p>
        </div>
      </div>
    </footer>

    <!-- Project Modal -->
    <div
      v-if="selectedProject"
      class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4 backdrop-blur-sm"
      @click="closeModal"
    >
      <div
        class="gothic-card max-w-5xl w-full max-h-[90vh] overflow-y-auto rounded-xl relative"
        @click.stop
      >
        <div class="p-8">
          <!-- Modal Header -->
          <div class="flex justify-between items-start mb-6">
            <div class="flex-1">
              <h2 class="text-3xl font-bold text-white mb-3">
                {{ selectedProject.title }}
              </h2>
              <div class="flex items-center space-x-4 mb-2">
                <span
                  class="px-3 py-1 bg-red-900/30 text-red-300 rounded-full text-sm border border-red-800"
                >
                  {{ selectedProject.project_type }}
                </span>
                <span class="text-gray-400 text-sm">
                  {{ formatDate(selectedProject.project_date) }}
                </span>
              </div>
            </div>
            <button
              @click="closeModal"
              class="text-gray-400 hover:text-white text-3xl font-light ml-4 w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-700 transition-colors"
            >
              ×
            </button>
          </div>

          <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
              <!-- Description -->
              <div>
                <h3
                  class="text-xl font-semibold text-red-400 mb-3 flex items-center"
                >
                  <span class="mr-2">📝</span>
                  Project Overview
                </h3>
                <p class="text-gray-300 leading-relaxed">
                  {{ selectedProject.description }}
                </p>
              </div>

              <!-- Challenges -->
              <div v-if="selectedProject.challenges">
                <h3
                  class="text-xl font-semibold text-yellow-400 mb-3 flex items-center"
                >
                  <span class="mr-2">⚡</span>
                  Challenges Faced
                </h3>
                <div
                  class="bg-yellow-900/20 border-l-4 border-yellow-400 p-4 rounded-r-lg"
                >
                  <p class="text-gray-300 leading-relaxed">
                    {{ selectedProject.challenges }}
                  </p>
                </div>
              </div>

              <!-- Solutions -->
              <div v-if="selectedProject.solutions">
                <h3
                  class="text-xl font-semibold text-green-400 mb-3 flex items-center"
                >
                  <span class="mr-2">✅</span>
                  Solutions & Lessons Learned
                </h3>
                <div
                  class="bg-green-900/20 border-l-4 border-green-400 p-4 rounded-r-lg"
                >
                  <p class="text-gray-300 leading-relaxed">
                    {{ selectedProject.solutions }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
              <!-- Technologies -->
              <div class="bg-gray-800/50 p-6 rounded-lg">
                <h3
                  class="text-lg font-semibold text-red-400 mb-4 flex items-center"
                >
                  <span class="mr-2">🛠️</span>
                  Technologies Used
                </h3>
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="tech in getTechnologies(
                      selectedProject.technologies
                    )"
                    :key="tech"
                    class="px-3 py-2 bg-gray-700 text-gray-300 rounded-lg text-sm font-medium border border-gray-600"
                  >
                    {{ tech }}
                  </span>
                </div>
              </div>

              <!-- Project Details -->
              <div class="bg-gray-800/50 p-6 rounded-lg">
                <h3
                  class="text-lg font-semibold text-red-400 mb-4 flex items-center"
                >
                  <span class="mr-2">ℹ️</span>
                  Project Details
                </h3>
                <div class="space-y-3 text-sm">
                  <div class="flex justify-between">
                    <span class="text-gray-400">Type:</span>
                    <span class="text-gray-300 font-medium">{{
                      selectedProject.project_type
                    }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-400">Date:</span>
                    <span class="text-gray-300 font-medium">{{
                      formatDate(selectedProject.project_date)
                    }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-400">Status:</span>
                    <span class="text-green-400 font-medium">Completed</span>
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="space-y-3">
                <a
                  v-if="selectedProject.github_url"
                  :href="selectedProject.github_url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="block w-full blood-red-gradient px-6 py-3 text-white text-center rounded-lg hover:scale-105 transition-transform shadow-lg font-medium"
                >
                  <span class="mr-2">🔗</span>
                  View on GitHub
                </a>

                <button
                  @click="closeModal"
                  class="block w-full bg-gray-700 hover:bg-gray-600 px-6 py-3 text-gray-300 text-center rounded-lg transition-colors font-medium"
                >
                  Close Details
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Back to Top Button -->
    <button
      v-if="showBackToTop"
      @click="scrollToTop"
      class="fixed bottom-8 right-8 w-12 h-12 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-lg transition-all duration-300 z-40 flex items-center justify-center"
      title="Back to Top"
    >
      <span class="text-xl">↑</span>
    </button>

    <!-- Loading Overlay -->
    <div
      v-if="isLoading"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
      <div class="gothic-card p-8 rounded-xl text-center">
        <div
          class="animate-spin w-12 h-12 border-4 border-red-600 border-t-transparent rounded-full mx-auto mb-4"
        ></div>
        <p class="text-white">Loading...</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";

// Props from Laravel backend
const props = defineProps({
  portfolios: Array,
  experiences: Array,
  skills: Object,
  profile: Object,
});

// State management
const selectedProject = ref(null);
const showMobileMenu = ref(false);
const showAllProjects = ref(false);
const selectedFilter = ref("all");
const showBackToTop = ref(false);
const isLoading = ref(false);

// Contact form state
const contactForm = ref({
  name: "",
  email: "",
  message: "",
  processing: false,
});

// Computed properties
const profilePhoto = computed(() => {
  return "/images/poto1.png";
});

const yearsOfExperience = computed(() => {
  const startYear = 2023; // Based on earliest experience
  const currentYear = new Date().getFullYear();
  return currentYear - startYear;
});

const projectTypes = computed(() => {
  const types = [...new Set(props.portfolios.map((p) => p.project_type))];
  return types;
});

const filteredProjects = computed(() => {
  let filtered =
    selectedFilter.value === "all"
      ? props.portfolios
      : props.portfolios.filter((p) => p.project_type === selectedFilter.value);

  return showAllProjects.value ? filtered : filtered.slice(0, 6);
});

const totalSkillCount = computed(() => {
  return Object.values(props.skills).reduce(
    (total, skillList) => total + skillList.length,
    0
  );
});

const advancedSkillCount = computed(() => {
  return Object.values(props.skills).reduce((total, skillList) => {
    return total + skillList.filter((skill) => skill.level === "mahir").length;
  }, 0);
});

// Methods
const showProject = (project) => {
  selectedProject.value = project;
};

const closeModal = () => {
  selectedProject.value = null;
};

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value;
};

const closeMobileMenu = () => {
  showMobileMenu.value = false;
};

const filterProjects = (filter) => {
  selectedFilter.value = filter;
  showAllProjects.value = false;
};

const handleImageError = (event) => {
  console.warn("Image failed to load:", event.target.src);
  // You could set a placeholder image here
  event.target.style.display = "none";
};

const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
  });
};

const getTechnologies = (technologies) => {
  if (!technologies) return [];
  return technologies.split(", ").map((tech) => tech.trim());
};

const getSkillWidth = (level) => {
  const levels = {
    pemula: "40%",
    menengah: "70%",
    mahir: "95%",
  };
  return levels[level] || "50%";
};

const getCategoryIcon = (category) => {
  const icons = {
    "Web Programming": "🌐",
    "Data Analysis": "📊",
    "Mobile Programming": "📱",
    Database: "🗄️",
    "Office Suite": "💼",
    "Programming Concepts": "🧠",
    "Artificial Intelligence": "🤖",
    "Soft Skills": "👥",
    Language: "🗣️",
    "Specialized Technologies": "⚙️",
  };
  return icons[category] || "🛠️";
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const handleScroll = () => {
  showBackToTop.value = window.scrollY > 400;
};

const sendQuickMessage = () => {
  contactForm.value.processing = true;

  // Create mailto link with form data
  const subject = `Message from ${contactForm.value.name}`;
  const body = `Name: ${contactForm.value.name}\nEmail: ${contactForm.value.email}\n\nMessage:\n${contactForm.value.message}`;
  const mailtoLink = `mailto:${
    props.profile.email
  }?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

  // Open mail client
  window.location.href = mailtoLink;

  // Reset form
  setTimeout(() => {
    contactForm.value = {
      name: "",
      email: "",
      message: "",
      processing: false,
    };
  }, 1000);
};

// Lifecycle hooks
onMounted(() => {
  window.addEventListener("scroll", handleScroll);

  // Add smooth scroll behavior for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
        closeMobileMenu();
      }
    });
  });
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
});
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Custom animations */
@keyframes tilt {
  0%,
  50%,
  100% {
    transform: rotate(0deg);
  }
  25% {
    transform: rotate(1deg);
  }
  75% {
    transform: rotate(-1deg);
  }
}

@keyframes float {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }
  33% {
    transform: translateY(-10px) rotate(120deg);
  }
  66% {
    transform: translateY(5px) rotate(240deg);
  }
}

.animate-tilt {
  animation: tilt 10s infinite linear;
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

/* Smooth transitions */
* {
  scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #1f2937;
}

::-webkit-scrollbar-thumb {
  background: #dc2626;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #b91c1c;
}
</style>
