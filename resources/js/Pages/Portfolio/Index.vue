<template>
  <div class="min-h-screen modern-bg overflow-x-hidden">
    <!-- Modern Navigation -->
    <nav class="fixed top-0 w-full z-50 glassmorphism border-b border-white/10">
      <div class="container mx-auto px-4 lg:px-6">
        <div class="flex justify-between items-center h-16">
          <!-- Logo -->
          <div class="flex items-center space-x-2">
            <div
              class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center"
            >
              <span class="text-white font-bold text-sm">{{
                getInitials(profile.name)
              }}</span>
            </div>
            <span class="text-white font-semibold hidden sm:block">{{
              profile.name
            }}</span>
          </div>

          <!-- Desktop Menu -->
          <div class="hidden md:flex items-center space-x-8">
            <a
              v-for="item in navItems"
              :key="item.id"
              :href="item.href"
              class="nav-link text-gray-300 hover:text-white transition-all duration-300 text-sm font-medium hover:scale-105"
              @click="handleNavClick"
            >
              {{ item.label }}
            </a>
          </div>

          <!-- Mobile Menu Button -->
          <button
            @click="toggleMobileMenu"
            class="md:hidden p-2 rounded-lg bg-white/10 backdrop-blur-sm"
          >
            <div class="w-5 h-5 flex flex-col justify-center items-center">
              <span
                class="block w-5 h-0.5 bg-white transition-all duration-300"
                :class="{
                  'rotate-45 translate-y-0.5': showMobileMenu,
                  'mb-1': !showMobileMenu,
                }"
              ></span>
              <span
                class="block w-5 h-0.5 bg-white transition-all duration-300"
                :class="{ 'opacity-0': showMobileMenu, '': !showMobileMenu }"
              ></span>
              <span
                class="block w-5 h-0.5 bg-white transition-all duration-300"
                :class="{
                  '-rotate-45 -translate-y-0.5': showMobileMenu,
                  'mt-1': !showMobileMenu,
                }"
              ></span>
            </div>
          </button>
        </div>

        <!-- Mobile Menu -->
        <div
          v-if="showMobileMenu"
          class="md:hidden pb-4 border-t border-white/10 mt-2"
        >
          <div class="flex flex-col space-y-2 pt-4">
            <a
              v-for="item in navItems"
              :key="item.id"
              :href="item.href"
              class="text-gray-300 hover:text-white transition-colors py-2 px-2 rounded-lg hover:bg-white/10"
              @click="closeMobileMenu"
            >
              {{ item.label }}
            </a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section with Dynamic Data -->
    <section
      id="home"
      class="min-h-screen flex items-center justify-center px-4 lg:px-6 pt-24 pb-16"
    >
      <div class="container mx-auto">
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-16 items-center">
          <!-- Content -->
          <div class="lg:col-span-7 space-y-8 animate-fade-in-up">
            <div class="space-y-6">
              <!-- Dynamic Availability Badge -->
              <div class="inline-block pt-4" v-if="profile.availability">
                <span
                  class="px-6 py-3 rounded-full bg-gradient-to-r from-blue-500/20 to-purple-500/20 border border-blue-500/30 text-blue-300 text-sm font-medium backdrop-blur-sm shadow-lg"
                >
                  ✨
                  {{
                    profile.availability.status || "Available for opportunities"
                  }}
                </span>
              </div>

              <!-- Dynamic Main Heading -->
              <h1
                class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mt-6"
              >
                Hi, I'm
                <span
                  class="bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 bg-clip-text text-transparent"
                >
                  {{ getFirstName(profile.name) }}
                </span>
              </h1>

              <p class="text-xl md:text-2xl text-gray-300 font-light mt-4">
                {{ profile.title }}
              </p>

              <p class="text-gray-400 text-lg leading-relaxed max-w-2xl mt-4">
                {{ profile.summary }}
              </p>
            </div>

            <!-- Dynamic Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-8">
              <div
                v-for="stat in dynamicStats"
                :key="stat.label"
                class="text-center p-4 rounded-2xl glass-card hover:scale-105 transition-all duration-300"
              >
                <div class="text-2xl md:text-3xl font-bold text-white mb-1">
                  {{ stat.value }}
                </div>
                <div class="text-gray-400 text-sm">{{ stat.label }}</div>
              </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6">
              <a href="#portfolio" class="modern-btn-primary group">
                <span class="mr-2">🚀</span>
                View My Work
                <span
                  class="ml-2 transform group-hover:translate-x-1 transition-transform"
                  >→</span
                >
              </a>

              <a :href="`mailto:${profile.email}`" class="modern-btn-secondary">
                <span class="mr-2">✉️</span>
                Let's Talk
              </a>

              <!-- CV Download Button -->
              <button
                @click="downloadCV"
                class="modern-btn-secondary"
                :disabled="downloadingCV"
              >
                <span class="mr-2">📄</span>
                <span v-if="downloadingCV">Downloading...</span>
                <span v-else>Download CV</span>
              </button>
            </div>
          </div>

          <!-- Dynamic Photo section -->
          <div
            class="lg:col-span-5 flex justify-center lg:justify-end order-first lg:order-last"
          >
            <div class="relative group">
              <!-- Animated Background -->
              <div
                class="absolute -inset-4 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-full opacity-30 group-hover:opacity-50 blur-xl transition-all duration-1000 animate-pulse"
              ></div>

              <!-- Photo Container -->
              <div class="relative w-64 h-64 md:w-80 md:h-80 lg:w-96 lg:h-96">
                <div
                  class="w-full h-full rounded-full overflow-hidden border-4 border-white/20 shadow-2xl"
                >
                  <img
                    :src="profilePhoto"
                    :alt="profile.name"
                    class="w-full h-full object-cover transform group-hover:scale-110 transition-all duration-700"
                    @error="handleImageError"
                    loading="eager"
                  />
                </div>

                <!-- Floating Elements -->
                <div
                  class="absolute top-4 -right-4 w-8 h-8 bg-blue-500 rounded-full animate-bounce opacity-80"
                ></div>
                <div
                  class="absolute -bottom-2 -left-2 w-6 h-6 bg-purple-500 rounded-full animate-ping opacity-60"
                ></div>
                <div
                  class="absolute top-1/3 -left-6 w-4 h-4 bg-pink-500 rounded-full animate-pulse opacity-70"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Enhanced About Section with Dynamic Data -->
    <section id="about" class="py-20 lg:py-32 px-4 lg:px-6">
      <div class="container mx-auto">
        <div class="text-center mb-16">
          <h2 class="section-title">About Me</h2>
          <p class="section-subtitle">Get to know me better</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
          <!-- Profile Card with Dynamic Data -->
          <div class="lg:col-span-1">
            <div class="glass-card p-6 text-center h-full">
              <div
                class="w-24 h-24 mx-auto mb-6 rounded-full overflow-hidden border-4 border-blue-500/30"
              >
                <img
                  :src="profilePhoto"
                  :alt="profile.name"
                  class="w-full h-full object-cover"
                />
              </div>

              <h3 class="text-xl font-bold text-white mb-2">
                {{ profile.name }}
              </h3>
              <p class="text-blue-400 mb-4">{{ profile.title }}</p>

              <div class="space-y-3 text-sm">
                <a
                  :href="`mailto:${profile.email}`"
                  class="flex items-center justify-center space-x-2 text-gray-400 hover:text-blue-400 transition-colors"
                >
                  <span>✉️</span>
                  <span>{{ profile.email }}</span>
                </a>
                <a
                  v-if="profile.phone"
                  :href="`tel:${profile.phone}`"
                  class="flex items-center justify-center space-x-2 text-gray-400 hover:text-blue-400 transition-colors"
                >
                  <span>📱</span>
                  <span>{{ profile.phone }}</span>
                </a>
                <div
                  class="flex items-center justify-center space-x-2 text-gray-400"
                >
                  <span>📍</span>
                  <span>{{ profile.location }}</span>
                </div>
              </div>

              <!-- Dynamic Social Links -->
              <div
                class="flex justify-center space-x-4 mt-6"
                v-if="socialLinks.length"
              >
                <a
                  v-for="social in socialLinks.slice(0, 4)"
                  :key="social.id"
                  :href="social.url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110"
                  :style="{
                    backgroundColor: social.color + '20',
                    borderColor: social.color + '50',
                  }"
                  :title="social.platform"
                >
                  <span class="text-lg">{{
                    getSocialIcon(social.platform)
                  }}</span>
                </a>
              </div>
            </div>
          </div>

          <!-- Details with Dynamic Data -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Dynamic Education -->
            <div class="glass-card p-6" v-if="profile.education">
              <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="mr-3">🎓</span>
                Education
              </h3>
              <div class="border-l-4 border-blue-500 pl-6">
                <h4 class="text-lg font-semibold text-white mb-2">
                  {{ profile.education.degree }}
                </h4>
                <p class="text-blue-400 mb-2">
                  {{ profile.education.university }}
                </p>
                <p class="text-gray-400 mb-3" v-if="profile.education.gpa">
                  GPA: {{ profile.education.gpa }}
                </p>
                <div
                  class="bg-gray-800/50 p-4 rounded-lg"
                  v-if="profile.thesis_title"
                >
                  <h5 class="text-sm font-medium text-white mb-2">Thesis:</h5>
                  <p class="text-sm text-gray-300">
                    {{ profile.thesis_title }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Dynamic Interests -->
            <div class="glass-card p-6" v-if="featuredInterests.length">
              <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="mr-3">🎯</span>
                Areas of Interest
              </h3>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div
                  v-for="interest in featuredInterests"
                  :key="interest.id"
                  class="px-3 py-2 rounded-lg text-sm text-center transition-transform cursor-default hover:scale-105"
                  :style="{
                    backgroundColor: interest.color + '20',
                    borderColor: interest.color + '50',
                    color: interest.color,
                  }"
                  :title="interest.description"
                >
                  <span class="mr-1">{{ interest.icon }}</span>
                  {{ interest.name }}
                </div>
              </div>
            </div>

            <!-- Dynamic Achievements -->
            <div class="glass-card p-6" v-if="featuredAchievements.length">
              <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="mr-3">🏆</span>
                Key Achievements
              </h3>
              <div class="space-y-4">
                <div
                  v-for="achievement in featuredAchievements"
                  :key="achievement.id"
                  class="border-l-4 border-green-500 pl-4"
                >
                  <h4 class="font-semibold text-white">
                    {{ achievement.title }}
                  </h4>
                  <p class="text-sm text-gray-400 mb-1">
                    {{ achievement.issuer }} •
                    {{ formatDate(achievement.date_achieved) }}
                  </p>
                  <p class="text-sm text-gray-300">
                    {{ achievement.description }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Enhanced Portfolio Section -->
    <section id="portfolio" class="py-20 lg:py-32 px-4 lg:px-6">
      <div class="container mx-auto">
        <div class="text-center mb-16">
          <h2 class="section-title">Featured Work</h2>
          <p class="section-subtitle">Some of my recent projects</p>
        </div>

        <!-- Enhanced Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-2 mb-12">
          <button
            v-for="filter in filters"
            :key="filter"
            @click="activeFilter = filter"
            :class="[
              'px-6 py-3 rounded-full transition-all duration-300 text-sm font-medium',
              activeFilter === filter
                ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg transform scale-105'
                : 'bg-white/10 text-gray-300 hover:bg-white/20 hover:text-white',
            ]"
          >
            {{ filter === "all" ? "All Projects" : filter }}
          </button>
        </div>

        <!-- Enhanced Projects Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="project in filteredProjects"
            :key="project.id"
            class="glass-card p-6 hover:scale-105 hover:shadow-2xl transition-all duration-300 cursor-pointer group"
            @click="showProjectModal(project)"
          >
            <!-- Project Header -->
            <div class="mb-4">
              <div class="flex items-center justify-between mb-2">
                <span
                  class="px-3 py-1 bg-gradient-to-r from-blue-500/30 to-purple-500/30 border border-blue-500/50 rounded-full text-xs font-medium text-blue-300"
                >
                  {{ project.project_type }}
                </span>
                <span class="text-gray-500 text-xs">{{
                  formatDate(project.project_date)
                }}</span>
              </div>

              <h3
                class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors mb-2"
              >
                {{ project.title }}
              </h3>
            </div>

            <!-- Project Description -->
            <p class="text-gray-400 text-sm mb-4 line-clamp-3">
              {{ project.description }}
            </p>

            <!-- Technologies -->
            <div class="mb-4">
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="tech in getTechnologies(project.technologies).slice(
                    0,
                    4
                  )"
                  :key="tech"
                  class="px-2 py-1 bg-gray-800/50 rounded text-xs text-gray-300"
                >
                  {{ tech }}
                </span>
                <span
                  v-if="getTechnologies(project.technologies).length > 4"
                  class="px-2 py-1 bg-gray-700/50 rounded text-xs text-gray-400"
                >
                  +{{ getTechnologies(project.technologies).length - 4 }}
                </span>
              </div>
            </div>

            <!-- Project Footer -->
            <div
              class="flex items-center justify-between pt-4 border-t border-white/10"
            >
              <div class="flex items-center space-x-2">
                <span
                  v-if="project.github_url"
                  class="text-green-400 text-xs"
                  title="Source Available"
                  >🔗</span
                >
                <span
                  v-if="project.challenges"
                  class="text-yellow-400 text-xs"
                  title="Challenges Documented"
                  >⚡</span
                >
                <span
                  v-if="project.solutions"
                  class="text-blue-400 text-xs"
                  title="Solutions Provided"
                  >✅</span
                >
              </div>
              <span
                class="text-blue-400 group-hover:translate-x-2 transition-transform"
                >→</span
              >
            </div>
          </div>
        </div>

        <!-- View More Button -->
        <div class="text-center mt-12" v-if="portfolios.length > 6">
          <button
            @click="showAllProjects = !showAllProjects"
            class="modern-btn-secondary"
          >
            {{ showAllProjects ? "Show Less" : "View All Projects" }}
          </button>
        </div>
      </div>
    </section>

    <!-- Enhanced Experience Section -->
    <section id="experience" class="py-20 lg:py-32 px-4 lg:px-6">
      <div class="container mx-auto">
        <div class="text-center mb-16">
          <h2 class="section-title">Experience</h2>
          <p class="section-subtitle">My professional journey</p>
        </div>

        <div class="max-w-4xl mx-auto">
          <div class="space-y-8">
            <div
              v-for="(experience, index) in experiences"
              :key="experience.id"
              class="relative"
            >
              <!-- Timeline Line -->
              <div
                v-if="index !== experiences.length - 1"
                class="absolute left-6 top-16 w-0.5 h-full bg-gradient-to-b from-blue-500/50 to-transparent hidden sm:block"
              ></div>

              <!-- Timeline Dot -->
              <div
                class="absolute left-4 top-6 w-4 h-4 rounded-full border-4 border-gray-900 shadow-lg hidden sm:block"
                :class="{
                  'animate-pulse bg-green-500': experience.is_current,
                  'bg-blue-500': !experience.is_current,
                }"
              ></div>

              <!-- Content -->
              <div
                class="sm:ml-16 glass-card p-6 hover:scale-105 transition-all duration-300"
              >
                <div
                  class="flex flex-col lg:flex-row lg:items-start lg:justify-between mb-4"
                >
                  <div class="flex-1">
                    <h3 class="text-xl font-bold text-white mb-1">
                      {{ experience.position }}
                    </h3>
                    <p class="text-blue-400 font-medium mb-2">
                      {{ experience.organization }}
                    </p>
                    <div
                      class="flex items-center flex-wrap gap-4 text-sm text-gray-400 mb-4"
                    >
                      <span class="flex items-center">
                        <span class="mr-2">📅</span>
                        {{ formatDate(experience.start_date) }} -
                        {{
                          experience.is_current
                            ? "Present"
                            : formatDate(experience.end_date)
                        }}
                      </span>
                      <span
                        v-if="experience.is_current"
                        class="px-3 py-1 bg-green-500/20 border border-green-500/50 rounded-full text-green-400 text-xs font-medium"
                      >
                        Current
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
      </div>
    </section>

    <!-- Enhanced Skills Section -->
    <section id="skills" class="py-20 lg:py-32 px-4 lg:px-6">
      <div class="container mx-auto">
        <div class="text-center mb-16">
          <h2 class="section-title">Skills & Expertise</h2>
          <p class="section-subtitle">Technologies I work with</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="(skillList, category) in skills"
            :key="category"
            class="glass-card p-6 hover:scale-105 transition-all duration-300"
          >
            <div class="flex items-center mb-6">
              <span class="text-2xl mr-3">{{ getCategoryIcon(category) }}</span>
              <h3 class="text-xl font-bold text-white">{{ category }}</h3>
            </div>

            <div class="space-y-4">
              <div v-for="skill in skillList" :key="skill.id">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-white font-medium">{{ skill.name }}</span>
                  <span
                    class="text-xs px-2 py-1 bg-blue-500/20 border border-blue-500/50 rounded text-blue-300 capitalize"
                  >
                    {{ skill.level }}
                  </span>
                </div>
                <div
                  class="w-full bg-gray-800/50 rounded-full h-2 overflow-hidden"
                >
                  <div
                    class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full transition-all duration-1000 hover:animate-pulse"
                    :style="{ width: getSkillWidth(skill.level) }"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Dynamic Languages Section -->
        <div class="mt-16" v-if="languages.length">
          <h3 class="text-2xl font-bold text-white text-center mb-8">
            Languages
          </h3>
          <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <div
              v-for="language in languages"
              :key="language.id"
              class="glass-card p-6 text-center"
            >
              <h4 class="text-lg font-semibold text-white mb-2">
                {{ language.name }}
              </h4>
              <p class="text-sm text-gray-400 mb-3 capitalize">
                {{ language.level }}
              </p>
              <div class="w-full bg-gray-800/50 rounded-full h-2 mb-2">
                <div
                  class="h-full bg-gradient-to-r from-green-500 to-blue-500 rounded-full"
                  :style="{ width: language.proficiency + '%' }"
                ></div>
              </div>
              <p class="text-xs text-gray-500">{{ language.description }}</p>
            </div>
          </div>
        </div>

        <!-- Dynamic Certifications Section -->
        <div class="mt-16" v-if="certifications.length">
          <h3 class="text-2xl font-bold text-white text-center mb-8">
            Certifications
          </h3>
          <div class="grid md:grid-cols-2 gap-6 max-w-6xl mx-auto">
            <div
              v-for="cert in certifications"
              :key="cert.id"
              class="glass-card p-6"
            >
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                  <h4 class="text-lg font-semibold text-white mb-1">
                    {{ cert.name }}
                  </h4>
                  <p class="text-blue-400 text-sm mb-2">{{ cert.issuer }}</p>
                  <p class="text-gray-400 text-xs">
                    {{ formatDate(cert.issue_date) }}
                    <span v-if="cert.expiry_date">
                      - {{ formatDate(cert.expiry_date) }}</span
                    >
                  </p>
                </div>
                <span
                  v-if="
                    !cert.expiry_date || new Date(cert.expiry_date) > new Date()
                  "
                  class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded"
                >
                  Valid
                </span>
              </div>
              <p class="text-gray-300 text-sm mb-3" v-if="cert.description">
                {{ cert.description }}
              </p>
              <div class="flex flex-wrap gap-2" v-if="cert.skills">
                <span
                  v-for="skill in cert.skills.slice(0, 5)"
                  :key="skill"
                  class="px-2 py-1 bg-purple-500/20 text-purple-300 text-xs rounded"
                >
                  {{ skill }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Enhanced Contact Section -->
    <section id="contact" class="py-20 lg:py-32 px-4 lg:px-6 scroll-mt-20">
      <div class="container mx-auto">
        <div class="text-center mb-16">
          <h2 class="section-title">Get In Touch</h2>
          <p class="section-subtitle">Let's work together</p>
        </div>

        <div class="max-w-4xl mx-auto grid lg:grid-cols-2 gap-8">
          <!-- Contact Info with Dynamic Data -->
          <div class="glass-card p-8">
            <h3 class="text-2xl font-bold text-white mb-6">Let's Connect</h3>

            <div class="space-y-6">
              <a
                :href="`mailto:${profile.email}`"
                class="flex items-center space-x-4 p-4 rounded-2xl bg-white/5 hover:bg-white/10 transition-all duration-300 group"
              >
                <div
                  class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center"
                >
                  <span class="text-white text-xl">✉️</span>
                </div>
                <div>
                  <p class="text-gray-400 text-sm">Email</p>
                  <p
                    class="text-white font-medium group-hover:text-blue-400 transition-colors"
                  >
                    {{ profile.email }}
                  </p>
                </div>
              </a>

              <a
                v-if="profile.phone"
                :href="`tel:${profile.phone}`"
                class="flex items-center space-x-4 p-4 rounded-2xl bg-white/5 hover:bg-white/10 transition-all duration-300 group"
              >
                <div
                  class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center"
                >
                  <span class="text-white text-xl">📱</span>
                </div>
                <div>
                  <p class="text-gray-400 text-sm">Phone</p>
                  <p
                    class="text-white font-medium group-hover:text-blue-400 transition-colors"
                  >
                    {{ profile.phone }}
                  </p>
                </div>
              </a>

              <div
                class="flex items-center space-x-4 p-4 rounded-2xl bg-white/5"
              >
                <div
                  class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center"
                >
                  <span class="text-white text-xl">📍</span>
                </div>
                <div>
                  <p class="text-gray-400 text-sm">Location</p>
                  <p class="text-white font-medium">{{ profile.location }}</p>
                </div>
              </div>

              <!-- Dynamic Social Links -->
              <div class="space-y-3" v-if="socialLinks.length">
                <a
                  v-for="social in socialLinks.filter(
                    (s) => s.platform !== 'Email'
                  )"
                  :key="social.id"
                  :href="social.url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="flex items-center space-x-4 p-4 rounded-2xl bg-white/5 hover:bg-white/10 transition-all duration-300 group"
                >
                  <div
                    class="w-12 h-12 rounded-full flex items-center justify-center"
                    :style="{ backgroundColor: social.color }"
                  >
                    <span class="text-white text-xl">{{
                      getSocialIcon(social.platform)
                    }}</span>
                  </div>
                  <div>
                    <p class="text-gray-400 text-sm">{{ social.platform }}</p>
                    <p
                      class="text-white font-medium group-hover:text-blue-400 transition-colors"
                    >
                      View Profile
                    </p>
                  </div>
                </a>
              </div>
            </div>
          </div>

          <!-- Enhanced Contact Form -->
          <div class="glass-card p-8" v-if="config.enable_contact_form">
            <h3 class="text-2xl font-bold text-white mb-6">Send Message</h3>

            <!-- Success/Error Messages -->
            <div
              v-if="contactMessage.success"
              class="mb-6 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-300"
            >
              {{ contactMessage.text }}
            </div>

            <div
              v-if="contactMessage.error"
              class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-300"
            >
              {{ contactMessage.text }}
            </div>

            <form @submit.prevent="sendMessage" class="space-y-6">
              <div>
                <input
                  v-model="contactForm.name"
                  type="text"
                  placeholder="Your Name"
                  required
                  maxlength="255"
                  class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent backdrop-blur-sm transition-all duration-300"
                />
                <div
                  v-if="contactErrors.name"
                  class="mt-1 text-sm text-red-400"
                >
                  {{ contactErrors.name[0] }}
                </div>
              </div>

              <div>
                <input
                  v-model="contactForm.email"
                  type="email"
                  placeholder="Your Email"
                  required
                  maxlength="255"
                  class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent backdrop-blur-sm transition-all duration-300"
                />
                <div
                  v-if="contactErrors.email"
                  class="mt-1 text-sm text-red-400"
                >
                  {{ contactErrors.email[0] }}
                </div>
              </div>

              <div>
                <input
                  v-model="contactForm.subject"
                  type="text"
                  placeholder="Subject (Optional)"
                  maxlength="255"
                  class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent backdrop-blur-sm transition-all duration-300"
                />
                <div
                  v-if="contactErrors.subject"
                  class="mt-1 text-sm text-red-400"
                >
                  {{ contactErrors.subject[0] }}
                </div>
              </div>

              <div>
                <textarea
                  v-model="contactForm.message"
                  placeholder="Your Message"
                  rows="5"
                  required
                  maxlength="2000"
                  class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent backdrop-blur-sm transition-all duration-300 resize-vertical"
                ></textarea>
                <div class="mt-1 text-xs text-gray-500">
                  {{ contactForm.message.length }}/2000 characters
                </div>
                <div
                  v-if="contactErrors.message"
                  class="mt-1 text-sm text-red-400"
                >
                  {{ contactErrors.message[0] }}
                </div>
              </div>

              <!-- Honeypot field for spam protection -->
              <input
                v-model="contactForm.honeypot"
                type="text"
                style="display: none"
                tabindex="-1"
                autocomplete="off"
              />

              <button
                type="submit"
                :disabled="contactForm.processing || !isContactFormValid"
                class="w-full modern-btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="contactForm.processing">
                  <span
                    class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"
                  ></span>
                  Sending...
                </span>
                <span v-else>Send Message</span>
              </button>
            </form>
          </div>

          <!-- Contact Form Disabled Message -->
          <div v-else class="glass-card p-8 text-center">
            <h3 class="text-xl font-bold text-white mb-4">
              Contact Form Unavailable
            </h3>
            <p class="text-gray-400 mb-6">
              The contact form is currently disabled. Please reach out via email
              or social media.
            </p>
            <a
              :href="`mailto:${profile.email}`"
              class="modern-btn-primary inline-flex items-center"
            >
              <span class="mr-2">✉️</span>
              Send Email
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Enhanced Footer -->
    <footer class="py-12 px-4 lg:px-6 border-t border-white/10">
      <div class="container mx-auto">
        <div class="text-center">
          <div class="flex justify-center items-center space-x-6 mb-6">
            <a
              v-for="social in socialLinks.slice(0, 5)"
              :key="social.id"
              :href="social.url"
              target="_blank"
              rel="noopener noreferrer"
              class="w-10 h-10 rounded-full flex items-center justify-center hover:scale-110 transition-all duration-300"
              :style="{
                backgroundColor: social.color + '20',
                borderColor: social.color + '50',
              }"
              :title="social.platform"
            >
              <span>{{ getSocialIcon(social.platform) }}</span>
            </a>
          </div>

          <p class="text-gray-400 text-sm mb-2">
            © {{ new Date().getFullYear() }} {{ profile.name }}. All rights
            reserved.
          </p>
          <p class="text-gray-500 text-xs">
            Built with Laravel & Vue.js
            <span v-if="profile.education && profile.education.gpa">
              • GPA: {{ profile.education.gpa }}</span
            >
            <span v-if="statistics.overview">
              • {{ statistics.overview.total_projects }}+ Projects</span
            >
          </p>
        </div>
      </div>
    </footer>

    <!-- Enhanced Project Modal -->
    <div
      v-if="selectedProject"
      class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click="closeProjectModal"
    >
      <div
        class="glass-card max-w-4xl w-full max-h-[90vh] overflow-y-auto"
        @click.stop
      >
        <div class="p-8">
          <!-- Modal Header -->
          <div class="flex justify-between items-start mb-6">
            <div>
              <h2 class="text-3xl font-bold text-white mb-2">
                {{ selectedProject.title }}
              </h2>
              <div class="flex items-center space-x-4 flex-wrap">
                <span
                  class="px-3 py-1 bg-gradient-to-r from-blue-500/30 to-purple-500/30 border border-blue-500/50 rounded-full text-sm text-blue-300"
                >
                  {{ selectedProject.project_type }}
                </span>
                <span class="text-gray-400 text-sm">{{
                  formatDate(selectedProject.project_date)
                }}</span>
                <span
                  v-if="selectedProject.github_url"
                  class="px-3 py-1 bg-green-500/20 border border-green-500/50 rounded-full text-sm text-green-300"
                >
                  Open Source
                </span>
              </div>
            </div>
            <button
              @click="closeProjectModal"
              class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-all duration-300 text-xl"
            >
              ×
            </button>
          </div>

          <!-- Modal Content -->
          <div class="space-y-6">
            <div>
              <h3 class="text-xl font-semibold text-white mb-3">📝 Overview</h3>
              <p class="text-gray-300 leading-relaxed">
                {{ selectedProject.description }}
              </p>
            </div>

            <div v-if="selectedProject.challenges">
              <h3 class="text-xl font-semibold text-yellow-400 mb-3">
                ⚡ Challenges
              </h3>
              <div
                class="bg-yellow-500/10 border border-yellow-500/30 rounded-2xl p-4"
              >
                <p class="text-gray-300">{{ selectedProject.challenges }}</p>
              </div>
            </div>

            <div v-if="selectedProject.solutions">
              <h3 class="text-xl font-semibold text-green-400 mb-3">
                ✅ Solutions
              </h3>
              <div
                class="bg-green-500/10 border border-green-500/30 rounded-2xl p-4"
              >
                <p class="text-gray-300">{{ selectedProject.solutions }}</p>
              </div>
            </div>

            <div>
              <h3 class="text-xl font-semibold text-blue-400 mb-3">
                🛠️ Technologies
              </h3>
              <div class="flex flex-wrap gap-3">
                <span
                  v-for="tech in getTechnologies(selectedProject.technologies)"
                  :key="tech"
                  class="px-4 py-2 bg-gradient-to-r from-blue-500/20 to-purple-500/20 border border-blue-500/50 rounded-full text-blue-300 font-medium"
                >
                  {{ tech }}
                </span>
              </div>
            </div>

            <div v-if="selectedProject.github_url" class="pt-4">
              <a
                :href="selectedProject.github_url"
                target="_blank"
                rel="noopener noreferrer"
                class="modern-btn-primary inline-flex items-center"
              >
                <span class="mr-2">🔗</span>
                View on GitHub
                <span class="ml-2">↗</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Back to Top Button -->
    <button
      v-if="showBackToTop"
      @click="scrollToTop"
      class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 z-40 flex items-center justify-center text-white hover:scale-110"
    >
      ↑
    </button>

    <!-- Loading Overlay -->
    <div
      v-if="isLoading"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
    >
      <div class="glass-card p-8 text-center">
        <div
          class="animate-spin w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full mx-auto mb-4"
        ></div>
        <p class="text-white">Loading...</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";

// Props
const props = defineProps({
  portfolios: { type: Array, default: () => [] },
  experiences: { type: Array, default: () => [] },
  skills: { type: Object, default: () => ({}) },
  profile: { type: Object, default: () => ({}) },
  statistics: { type: Object, default: () => ({}) },
  meta: { type: Object, default: () => ({}) },
  config: { type: Object, default: () => ({}) },
  socialLinks: { type: Array, default: () => [] },
  achievements: { type: Array, default: () => [] },
  languages: { type: Array, default: () => [] },
  interests: { type: Array, default: () => [] },
  certifications: { type: Array, default: () => [] },
});

// Reactive state
const showMobileMenu = ref(false);
const selectedProject = ref(null);
const activeFilter = ref("all");
const showAllProjects = ref(false);
const showBackToTop = ref(false);
const isLoading = ref(false);
const downloadingCV = ref(false);

// Contact form state
const contactForm = ref({
  name: "",
  email: "",
  subject: "",
  message: "",
  honeypot: "",
  processing: false,
});

const contactMessage = ref({
  success: false,
  error: false,
  text: "",
});

const contactErrors = ref({});

// Navigation items
const navItems = ref([
  { id: "home", label: "Home", href: "#home" },
  { id: "about", label: "About", href: "#about" },
  { id: "portfolio", label: "Portfolio", href: "#portfolio" },
  { id: "experience", label: "Experience", href: "#experience" },
  { id: "skills", label: "Skills", href: "#skills" },
  { id: "contact", label: "Contact", href: "#contact" },
]);

// Computed properties
const profilePhoto = computed(() => {
  return props.profile.photo || props.profile.avatar || "/images/poto1.png";
});

const dynamicStats = computed(() => [
  {
    label: "Projects",
    value: `${
      props.statistics.overview?.total_projects || props.portfolios.length
    }+`,
  },
  {
    label: "GPA",
    value: props.profile.education?.gpa || "3.76",
  },
  {
    label: "Experience",
    value: `${
      props.statistics.overview?.years_experience ||
      calculateYearsOfExperience()
    }yr`,
  },
  {
    label: "Skills",
    value: `${
      props.statistics.overview?.total_skills ||
      Object.values(props.skills).flat().length
    }+`,
  },
]);

const filters = computed(() => {
  const types = [...new Set(props.portfolios.map((p) => p.project_type))];
  return ["all", ...types];
});

const filteredProjects = computed(() => {
  let filtered =
    activeFilter.value === "all"
      ? props.portfolios
      : props.portfolios.filter((p) => p.project_type === activeFilter.value);

  return showAllProjects.value ? filtered : filtered.slice(0, 6);
});

const featuredInterests = computed(() => {
  return props.interests.filter((interest) => interest.is_featured).slice(0, 9);
});

const featuredAchievements = computed(() => {
  return props.achievements
    .filter((achievement) => achievement.is_featured)
    .slice(0, 3);
});

const isContactFormValid = computed(() => {
  return (
    contactForm.value.name.length >= 2 &&
    contactForm.value.email.includes("@") &&
    contactForm.value.message.length >= 10 &&
    contactForm.value.honeypot === ""
  );
});

// Methods
const calculateYearsOfExperience = () => {
  if (!props.experiences.length) return 1;
  const firstExp = props.experiences.reduce((earliest, exp) => {
    return new Date(exp.start_date) < new Date(earliest.start_date)
      ? exp
      : earliest;
  });
  return Math.max(
    1,
    new Date().getFullYear() - new Date(firstExp.start_date).getFullYear()
  );
};

const getInitials = (name) => {
  if (!name) return "NH";
  return name
    .split(" ")
    .map((n) => n[0])
    .join("")
    .toUpperCase()
    .slice(0, 2);
};

const getFirstName = (name) => {
  if (!name) return "User";
  return name.split(" ")[0];
};

const getSocialIcon = (platform) => {
  const icons = {
    GitHub: "🔗",
    LinkedIn: "💼",
    Email: "✉️",
    WhatsApp: "💬",
    Telegram: "📱",
    Twitter: "🐦",
    Instagram: "📷",
    Facebook: "👥",
  };
  return icons[platform] || "🌐";
};

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value;
};

const closeMobileMenu = () => {
  showMobileMenu.value = false;
};

const handleNavClick = (event) => {
  const href = event.target.getAttribute("href");
  if (href && href.startsWith("#")) {
    event.preventDefault();
    const target = document.querySelector(href);
    if (target) {
      const navbarHeight = 64;
      const elementPosition = target.getBoundingClientRect().top;
      const offsetPosition =
        elementPosition + window.pageYOffset - navbarHeight - 16;

      window.scrollTo({
        top: offsetPosition,
        behavior: "smooth",
      });
      closeMobileMenu();
    }
  }
};

const showProjectModal = (project) => {
  selectedProject.value = project;
  document.body.style.overflow = "hidden";

  // Track project view
  if (props.config.enable_analytics) {
    trackEvent("project_view", "project_modal", {
      project_id: project.id,
      project_title: project.title,
    });
  }
};

const closeProjectModal = () => {
  selectedProject.value = null;
  document.body.style.overflow = "auto";
};

const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
  });
};

const getTechnologies = (technologies) => {
  return technologies
    ? technologies.split(", ").map((tech) => tech.trim())
    : [];
};

const getSkillWidth = (level) => {
  const levels = { pemula: "40%", menengah: "70%", mahir: "95%" };
  return levels[level] || "50%";
};

const getCategoryIcon = (category) => {
  const icons = {
    "Web Programming": "🌐",
    "Data Analysis": "📊",
    "Mobile Programming": "📱",
    Database: "🗄️",
    "Office Suite": "💼",
    Language: "🗣️",
    Framework: "⚙️",
    "Artificial Intelligence": "🤖",
    "Soft Skills": "👥",
    "Programming Concepts": "💡",
    "Specialized Technologies": "🔧",
  };
  return icons[category] || "🛠️";
};

const handleImageError = (event) => {
  console.warn("Image failed to load");
  event.target.style.display = "none";
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const handleScroll = () => {
  showBackToTop.value = window.scrollY > 500;
};

// Enhanced contact form submission
const sendMessage = async () => {
  if (!isContactFormValid.value) return;

  contactForm.value.processing = true;
  contactErrors.value = {};
  contactMessage.value = { success: false, error: false, text: "" };

  try {
    const response = await axios.post("/contact", {
      name: contactForm.value.name,
      email: contactForm.value.email,
      subject: contactForm.value.subject,
      message: contactForm.value.message,
      honeypot: contactForm.value.honeypot,
    });

    if (response.data.success) {
      contactMessage.value = {
        success: true,
        error: false,
        text: response.data.message,
      };

      // Reset form
      contactForm.value = {
        name: "",
        email: "",
        subject: "",
        message: "",
        honeypot: "",
        processing: false,
      };

      // Track successful contact
      if (props.config.enable_analytics) {
        trackEvent("contact_form_success", "contact", {
          has_subject: !!response.data.subject,
        });
      }
    }
  } catch (error) {
    if (error.response?.status === 422) {
      contactErrors.value = error.response.data.errors || {};
      contactMessage.value = {
        success: false,
        error: true,
        text:
          error.response.data.message ||
          "Please check your input and try again.",
      };
    } else if (error.response?.status === 429) {
      contactMessage.value = {
        success: false,
        error: true,
        text:
          error.response.data.message ||
          "Too many attempts. Please try again later.",
      };
    } else {
      contactMessage.value = {
        success: false,
        error: true,
        text: "Sorry, there was an error sending your message. Please try again later.",
      };
    }

    // Track contact error
    if (props.config.enable_analytics) {
      trackEvent("contact_form_error", "contact", {
        error_status: error.response?.status,
      });
    }
  } finally {
    contactForm.value.processing = false;
  }
};

// CV Download with tracking
const downloadCV = async () => {
  downloadingCV.value = true;

  try {
    // Track download attempt
    if (props.config.enable_analytics) {
      trackEvent("cv_download_attempt", "download");
    }

    const response = await fetch("/download-cv");

    if (response.ok) {
      const blob = await response.blob();
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.style.display = "none";
      a.href = url;
      a.download = `${props.profile.name?.replace(/\s+/g, "_") || "CV"}.pdf`;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
      document.body.removeChild(a);

      // Track successful download
      if (props.config.enable_analytics) {
        trackEvent("cv_download_success", "download");
      }
    } else {
      throw new Error("Download failed");
    }
  } catch (error) {
    console.error("CV download error:", error);

    // Fallback to email
    const subject = `CV Request - ${props.profile.name}`;
    const body = `Hello,\n\nI would like to request your CV/Resume.\n\nThank you!`;
    window.location.href = `mailto:${
      props.profile.email
    }?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

    // Track download error
    if (props.config.enable_analytics) {
      trackEvent("cv_download_error", "download");
    }
  } finally {
    downloadingCV.value = false;
  }
};

// Analytics tracking
const trackEvent = async (eventType, page = null, data = null) => {
  if (!props.config.enable_analytics) return;

  try {
    await axios.post("/api/analytics/track", {
      event_type: eventType,
      page: page,
      data: data,
      timestamp: new Date().toISOString(),
    });
  } catch (error) {
    console.warn("Analytics tracking failed:", error);
  }
};

// Clear contact messages after some time
const clearContactMessage = () => {
  setTimeout(() => {
    contactMessage.value = { success: false, error: false, text: "" };
    contactErrors.value = {};
  }, 8000);
};

// Watch for contact messages to auto-clear
watch(
  () => contactMessage.value.success || contactMessage.value.error,
  (hasMessage) => {
    if (hasMessage) {
      clearContactMessage();
    }
  }
);

// Lifecycle
onMounted(() => {
  window.addEventListener("scroll", handleScroll);

  // Track page view
  if (props.config.enable_analytics) {
    trackEvent("page_view", "portfolio_home");
  }

  // Set up meta tags dynamically
  if (props.meta.title) {
    document.title = props.meta.title;
  }

  if (props.meta.description) {
    let metaDesc = document.querySelector('meta[name="description"]');
    if (!metaDesc) {
      metaDesc = document.createElement("meta");
      metaDesc.setAttribute("name", "description");
      document.head.appendChild(metaDesc);
    }
    metaDesc.setAttribute("content", props.meta.description);
  }
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
  document.body.style.overflow = "auto";
});

// Global error handler
window.addEventListener("error", (event) => {
  console.error("Global error:", event.error);
  if (props.config.enable_analytics) {
    trackEvent("javascript_error", "error", {
      message: event.error?.message,
      filename: event.filename,
      lineno: event.lineno,
    });
  }
});
</script>

<style scoped>
/* Enhanced styles for dynamic portfolio */
.modern-bg {
  background: linear-gradient(
    135deg,
    #0f0f23 0%,
    #1a0b2e 25%,
    #16213e 50%,
    #0f3460 75%,
    #0e4b82 100%
  );
  background-attachment: fixed;
  position: relative;
  min-height: 100vh;
}

.modern-bg::before {
  content: "";
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(
      circle at 20% 20%,
      rgba(59, 130, 246, 0.1) 0%,
      transparent 50%
    ),
    radial-gradient(
      circle at 80% 80%,
      rgba(147, 51, 234, 0.1) 0%,
      transparent 50%
    ),
    radial-gradient(
      circle at 40% 60%,
      rgba(236, 72, 153, 0.05) 0%,
      transparent 50%
    );
  pointer-events: none;
  z-index: -1;
  animation: backgroundShift 20s ease-in-out infinite;
}

@keyframes backgroundShift {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
}

.glassmorphism {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.glass-card {
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 24px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.glass-card:hover {
  background: rgba(255, 255, 255, 0.12);
  border-color: rgba(59, 130, 246, 0.3);
  box-shadow: 0 32px 64px rgba(0, 0, 0, 0.3);
}

.modern-btn-primary {
  @apply px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-2xl;
  @apply hover:from-blue-600 hover:to-purple-700 transform hover:scale-105;
  @apply transition-all duration-300 shadow-lg hover:shadow-xl;
  @apply flex items-center justify-center space-x-2;
}

.modern-btn-secondary {
  @apply px-8 py-4 bg-white/10 backdrop-blur-sm border border-white/20 text-white font-semibold rounded-2xl;
  @apply hover:bg-white/20 hover:border-white/30 transform hover:scale-105;
  @apply transition-all duration-300;
  @apply flex items-center justify-center space-x-2;
}

.section-title {
  @apply text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4;
  background: linear-gradient(135deg, #ffffff 0%, #3b82f6 50%, #9333ea 100%);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-size: 200% 200%;
  animation: gradientShift 8s ease infinite;
}

@keyframes gradientShift {
  0%,
  100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

.section-subtitle {
  @apply text-lg text-gray-400 max-w-2xl mx-auto;
}

.nav-link {
  position: relative;
  padding: 0.5rem 0;
}

.nav-link::after {
  content: "";
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 2px;
  background: linear-gradient(135deg, #3b82f6, #9333ea);
  transition: width 0.3s ease;
}

.nav-link:hover::after {
  width: 100%;
}

.animate-fade-in-up {
  animation: fadeInUp 1s ease-out forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Enhanced responsive design */
@media (max-width: 768px) {
  .section-title {
    @apply text-3xl md:text-4xl;
  }

  .glass-card {
    border-radius: 16px;
    padding: 1.5rem;
  }

  .modern-btn-primary,
  .modern-btn-secondary {
    @apply px-6 py-3 text-sm;
  }
}

/* Enhanced loading states */
.loading-shimmer {
  background: linear-gradient(
    90deg,
    rgba(255, 255, 255, 0.05) 0%,
    rgba(255, 255, 255, 0.1) 50%,
    rgba(255, 255, 255, 0.05) 100%
  );
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

/* Enhanced accessibility */
@media (prefers-reduced-motion: reduce) {
  .animate-fade-in-up,
  .animate-bounce,
  .animate-ping,
  .animate-pulse,
  .animate-spin {
    animation: none;
  }

  .hover\:scale-105:hover,
  .hover\:scale-110:hover {
    transform: none;
  }
}

/* Focus states */
button:focus,
a:focus,
input:focus,
textarea:focus {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #3b82f6, #9333ea);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #2563eb, #7c3aed);
}
</style>
