<template>
  <div class="min-h-screen modern-bg overflow-x-hidden">
    <!-- Navigation Bar -->
    <nav class="fixed top-0 w-full z-50 glassmorphism border-b border-white/10">
      <div class="container mx-auto px-4 lg:px-6">
        <div class="flex justify-between items-center h-16">
          <!-- Logo -->
          <div class="flex items-center space-x-2">
            <div
              class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center"
            >
              <span class="text-white font-bold text-sm">
                {{ getInitials(profile.name) }}
              </span>
            </div>
            <span class="text-white font-semibold hidden sm:block">
              {{ profile.name }}
            </span>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center space-x-8">
            <a
              v-for="item in navItems"
              :key="item.id"
              :href="item.href"
              @click="handleNavClick"
              class="nav-link text-gray-300 hover:text-white transition-all duration-300 text-sm font-medium hover:scale-105"
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
                :class="{ 'opacity-0': showMobileMenu }"
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
              @click="closeMobileMenu"
              class="text-gray-300 hover:text-white transition-colors py-2 px-2 rounded-lg hover:bg-white/10"
            >
              {{ item.label }}
            </a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section
      id="home"
      class="min-h-screen flex items-center justify-center px-4 lg:px-6 pt-24 pb-16"
    >
      <div class="container mx-auto">
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-16 items-center">
          <!-- Content -->
          <div class="lg:col-span-7 space-y-8 animate-fade-in-up">
            <div class="space-y-6">
              <!-- Availability Badge -->
              <div v-if="profile.availability" class="inline-block pt-4">
                <span
                  class="px-6 py-3 rounded-full bg-gradient-to-r from-blue-500/20 to-purple-500/20 border border-blue-500/30 text-blue-300 text-sm font-medium backdrop-blur-sm shadow-lg"
                >
                  ✨
                  {{
                    profile.availability.status || "Available for opportunities"
                  }}
                </span>
              </div>

              <!-- Main Heading -->
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

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6">
              <button
                @click="letsTalk"
                :disabled="talking"
                class="modern-btn-primary group"
              >
                <span v-if="talking">Talking...</span>
                <span v-else>
                  <span class="mr-2">🚀</span>
                  Let's Talk
                  <span
                    class="ml-2 transform group-hover:translate-x-1 transition-transform"
                    >→</span
                  >
                </span>
              </button>
            </div>
          </div>

          <!-- Profile Photo -->
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
                    @error="handleImageError"
                    loading="eager"
                    class="w-full h-full object-cover transform group-hover:scale-110 transition-all duration-700"
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

    <!-- About Section -->
    <section id="about" class="py-20 lg:py-32 px-4 lg:px-6">
      <div class="container mx-auto">
        <div class="text-center mb-16">
          <h2 class="section-title">About Me</h2>
          <p class="section-subtitle">Get to know me better</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
          <!-- Profile Card -->
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

              <!-- Social Links -->
              <div
                v-if="socialLinks && socialLinks.length > 0"
                class="flex justify-center space-x-4 mt-6"
              >
                <a
                  v-for="social in socialLinks.slice(0, 4)"
                  :key="social.id"
                  :href="social.url"
                  target="_blank"
                  rel="noopener noreferrer"
                  :title="social.platform"
                  class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110"
                  :style="{
                    backgroundColor: social.color + '20',
                    borderColor: social.color + '50',
                  }"
                >
                  <span class="text-lg">{{
                    getSocialIcon(social.platform)
                  }}</span>
                </a>
              </div>
            </div>
          </div>

          <!-- Details -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Education -->
            <div v-if="profile.education" class="glass-card p-6">
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
                <p v-if="profile.education.gpa" class="text-gray-400 mb-3">
                  GPA: {{ profile.education.gpa }}
                </p>
                <div
                  v-if="profile.thesis_title"
                  class="bg-gray-800/50 p-4 rounded-lg"
                >
                  <h5 class="text-sm font-medium text-white mb-2">Thesis:</h5>
                  <p class="text-sm text-gray-300">
                    {{ profile.thesis_title }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Interests -->
            <div
              v-if="featuredInterests && featuredInterests.length > 0"
              class="glass-card p-6"
            >
              <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="mr-3">🎯</span>
                Areas of Interest
              </h3>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div
                  v-for="interest in featuredInterests"
                  :key="interest.id"
                  :title="interest.description"
                  class="px-3 py-2 rounded-lg text-sm text-center transition-transform cursor-default hover:scale-105"
                  :style="{
                    backgroundColor: interest.color + '20',
                    borderColor: interest.color + '50',
                    color: interest.color,
                  }"
                >
                  <span class="mr-1">{{ interest.icon }}</span>
                  {{ interest.name }}
                </div>
              </div>
            </div>

            <!-- Achievements -->
            <div
              v-if="featuredAchievements && featuredAchievements.length > 0"
              class="glass-card p-6"
            >
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

    <!-- Portfolio Section -->
    <section
      v-if="portfolios && portfolios.length > 0"
      id="portfolio"
      class="py-20 lg:py-32 px-4 lg:px-6"
    >
      <div class="container mx-auto">
        <div class="text-center mb-16">
          <h2 class="section-title">Featured Work</h2>
          <p class="section-subtitle">Some of my recent projects</p>
        </div>

        <!-- Filter Tabs -->
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

        <!-- Projects Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="project in filteredProjects"
            :key="project.id"
            @click="showProjectModal(project)"
            class="glass-card p-6 hover:scale-105 hover:shadow-2xl transition-all duration-300 cursor-pointer group"
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
                  title="Source Available"
                  class="text-green-400 text-xs"
                  >🔗</span
                >
                <span
                  v-if="project.challenges"
                  title="Challenges Documented"
                  class="text-yellow-400 text-xs"
                  >⚡</span
                >
                <span
                  v-if="project.solutions"
                  title="Solutions Provided"
                  class="text-blue-400 text-xs"
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
        <div v-if="portfolios.length > 6" class="text-center mt-12">
          <button
            @click="showAllProjects = !showAllProjects"
            class="modern-btn-secondary"
          >
            {{ showAllProjects ? "Show Less" : "View All Projects" }}
          </button>
        </div>
      </div>
    </section>

    <!-- Experience Section -->
    <section
      v-if="experiences && experiences.length > 0"
      id="experience"
      class="py-20 lg:py-32 px-4 lg:px-6"
    >
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

    <!-- Skills Section -->
    <section
      v-if="
        skills &&
        Object.keys(skills).length > 0 &&
        Object.values(skills).some((category) => category.length > 0)
      "
      id="skills"
      class="py-20 lg:py-32 px-4 lg:px-6"
    >
      <div class="container mx-auto">
        <div class="text-center mb-16">
          <h2 class="section-title">Skills & Expertise</h2>
          <p class="section-subtitle">Technologies I work with</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <template
            v-for="(categorySkills, category) in skills"
            :key="category"
          >
            <div
              v-if="
                categorySkills &&
                Array.isArray(categorySkills) &&
                categorySkills.length > 0
              "
              class="glass-card p-6 hover:scale-105 transition-all duration-300"
            >
              <div class="flex items-center mb-6">
                <span class="text-2xl mr-3">{{
                  getCategoryIcon(category)
                }}</span>
                <h3 class="text-xl font-bold text-white">{{ category }}</h3>
              </div>

              <div class="space-y-4">
                <div
                  v-for="skill in categorySkills"
                  :key="skill.id || skill.name"
                >
                  <div class="flex justify-between items-center mb-2">
                    <span class="text-white font-medium">{{
                      skill.name || skill
                    }}</span>
                    <span
                      v-if="skill.level"
                      class="text-xs px-2 py-1 bg-blue-500/20 border border-blue-500/50 rounded text-blue-300 capitalize"
                    >
                      {{ skill.level }}
                    </span>
                  </div>
                  <div
                    v-if="skill.level"
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
          </template>
        </div>

        <!-- Languages Section -->
        <div v-if="languages && languages.length > 0" class="mt-16">
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

        <!-- Certifications Section -->
        <div v-if="certifications && certifications.length > 0" class="mt-16">
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
                    <span v-if="cert.expiry_date"
                      >- {{ formatDate(cert.expiry_date) }}</span
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
              <p v-if="cert.description" class="text-gray-300 text-sm mb-3">
                {{ cert.description }}
              </p>
              <div v-if="cert.skills" class="flex flex-wrap gap-2">
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

    <!-- Contact Section -->
    <section id="contact" class="py-20 lg:py-32 px-4 lg:px-6 scroll-mt-20">
      <div class="container mx-auto">
        <div class="text-center mb-16">
          <h2 class="section-title">Get In Touch</h2>
          <p class="section-subtitle">Let's work together</p>
        </div>

        <div class="max-w-4xl mx-auto grid lg:grid-cols-2 gap-8">
          <!-- Contact Info -->
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
              <div
                v-if="socialLinks && socialLinks.length > 0"
                class="space-y-3"
              >
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

          <!-- Contact Form -->
          <div v-if="config.enable_contact_form" class="glass-card p-8">
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

    <!-- Footer -->
    <footer class="py-12 px-4 lg:px-6 border-t border-white/10">
      <div class="container mx-auto">
        <div class="text-center">
          <div
            v-if="socialLinks && socialLinks.length > 0"
            class="flex justify-center items-center space-x-6 mb-6"
          >
            <a
              v-for="social in socialLinks.slice(0, 5)"
              :key="social.id"
              :href="social.url"
              target="_blank"
              rel="noopener noreferrer"
              :title="social.platform"
              class="w-10 h-10 rounded-full flex items-center justify-center hover:scale-110 transition-all duration-300"
              :style="{
                backgroundColor: social.color + '20',
                borderColor: social.color + '50',
              }"
            >
              <span>{{ getSocialIcon(social.platform) }}</span>
            </a>
          </div>

          <!-- Fallback email link if no social links -->
          <div
            v-else-if="profile.email"
            class="flex justify-center items-center mb-6"
          >
            <a
              :href="`mailto:${profile.email}`"
              title="Email"
              class="w-10 h-10 rounded-full bg-blue-500/20 border border-blue-500/50 flex items-center justify-center hover:scale-110 transition-all duration-300"
            >
              <span>✉️</span>
            </a>
          </div>

          <p class="text-gray-400 text-sm mb-2">
            © {{ new Date().getFullYear() }} {{ profile.name || "Portfolio" }}.
            All rights reserved.
          </p>
        </div>
      </div>
    </footer>

    <!-- Project Modal -->
    <div
      v-if="selectedProject"
      @click="closeProjectModal"
      class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    >
      <div
        @click.stop
        class="glass-card max-w-4xl w-full max-h-[90vh] overflow-y-auto"
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
                <span class="text-gray-400 text-sm">
                  {{ formatDate(selectedProject.project_date) }}
                </span>
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
const talking = ref(false);

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

// Navigation items - dynamically generated based on available data
const navItems = computed(() => {
  const items = [
    { id: "home", label: "Home", href: "#home" },
    { id: "about", label: "About", href: "#about" },
  ];

  if (props.portfolios && props.portfolios.length > 0) {
    items.push({ id: "portfolio", label: "Portfolio", href: "#portfolio" });
  }

  if (props.experiences && props.experiences.length > 0) {
    items.push({ id: "experience", label: "Experience", href: "#experience" });
  }

  if (
    props.skills &&
    Object.keys(props.skills).length > 0 &&
    Object.values(props.skills).some((category) => category.length > 0)
  ) {
    items.push({ id: "skills", label: "Skills", href: "#skills" });
  }

  items.push({ id: "contact", label: "Contact", href: "#contact" });

  return items;
});

// Computed properties
const profilePhoto = computed(() => {
  if (props.profile.photo) {
    return `/storage/${props.profile.photo}`;
  }
  if (props.profile.avatar) {
    return props.profile.avatar;
  }
  return "/images/poto1.png";
});

const filters = computed(() => {
  if (!props.portfolios || props.portfolios.length === 0) {
    return ["all"];
  }
  const types = [...new Set(props.portfolios.map((p) => p.project_type))];
  return ["all", ...types];
});

const filteredProjects = computed(() => {
  if (!props.portfolios || props.portfolios.length === 0) {
    return [];
  }

  let filtered =
    activeFilter.value === "all"
      ? props.portfolios
      : props.portfolios.filter((p) => p.project_type === activeFilter.value);

  return showAllProjects.value ? filtered : filtered.slice(0, 6);
});

const featuredInterests = computed(() => {
  if (!props.interests || props.interests.length === 0) {
    return [];
  }
  return props.interests.filter((interest) => interest.is_featured).slice(0, 9);
});

const featuredAchievements = computed(() => {
  if (!props.achievements || props.achievements.length === 0) {
    return [];
  }
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
  if (event.target.src !== "/images/poto1.png") {
    event.target.src = "/images/poto1.png";
  } else {
    event.target.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(
      props.profile.name || "NH"
    )}&size=400&background=3b82f6&color=ffffff&bold=true`;
  }
  event.target.style.display = "block";
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const handleScroll = () => {
  showBackToTop.value = window.scrollY > 500;
};

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

      contactForm.value = {
        name: "",
        email: "",
        subject: "",
        message: "",
        honeypot: "",
        processing: false,
      };

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

    if (props.config.enable_analytics) {
      trackEvent("contact_form_error", "contact", {
        error_status: error.response?.status,
      });
    }
  } finally {
    contactForm.value.processing = false;
  }
};

const letsTalk = async () => {
  talking.value = true;

  try {
    const subject = `CV Request - ${props.profile.name || "Portfolio Owner"}`;
    const body = `Hello ${props.profile.name || ""},

I am interested in learning more about your background and would like to request your CV/Resume.

Please send me your latest CV at your earliest convenience.

Thank you for your time!

Best regards`;

    const emailUrl = `mailto:${
      props.profile.email
    }?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

    window.location.href = emailUrl;

    trackEvent("cv_request_email", "contact", {
      recipient: props.profile.email,
      method: "email_client",
    });
  } finally {
    setTimeout(() => {
      talking.value = false;
    }, 2000);
  }
};

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

const clearContactMessage = () => {
  setTimeout(() => {
    contactMessage.value = { success: false, error: false, text: "" };
    contactErrors.value = {};
  }, 8000);
};

watch(
  () => contactMessage.value.success || contactMessage.value.error,
  (hasMessage) => {
    if (hasMessage) {
      clearContactMessage();
    }
  }
);

onMounted(() => {
  window.addEventListener("scroll", handleScroll);

  if (props.config.enable_analytics) {
    trackEvent("page_view", "portfolio_home");
  }

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
/* Custom animations */
@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

html {
  scroll-behavior: smooth;
}

.modern-bg {
  background: linear-gradient(
    135deg,
    #0f0f23 0%,
    #1a1a2e 25%,
    #16213e 50%,
    #1a1a2e 75%,
    #0f0f23 100%
  );
  background-size: 400% 400%;
  animation: gradientShift 15s ease infinite;
}

@keyframes gradientShift {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.glassmorphism {
  background: rgba(15, 23, 42, 0.8);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.glass-card {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.section-title {
  @apply text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 bg-clip-text text-transparent mb-4;
}

.section-subtitle {
  @apply text-gray-400 text-lg max-w-2xl mx-auto;
}

.modern-btn-primary {
  @apply px-8 py-4 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white font-semibold rounded-full hover:shadow-xl hover:scale-105 transition-all duration-300 inline-flex items-center justify-center;
}

.modern-btn-secondary {
  @apply px-8 py-4 border-2 border-white/20 text-white font-semibold rounded-full hover:bg-white/10 hover:border-white/40 transition-all duration-300 inline-flex items-center justify-center;
}

.nav-link {
  position: relative;
  transition: all 0.3s ease;
}

.nav-link::after {
  content: "";
  position: absolute;
  bottom: -4px;
}
</style>
