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
              <span class="text-white font-bold text-sm">NH</span>
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
              class="text-gray-300 hover:text-white transition-all duration-300 text-sm font-medium hover:scale-105"
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

    <!-- Hero Section -->
    <section
      id="home"
      class="min-h-screen flex items-center justify-center px-4 lg:px-6 pt-16"
    >
      <div class="container mx-auto">
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-16 items-center">
          <!-- Content -->
          <div class="lg:col-span-7 space-y-6 animate-fade-in-up">
            <div class="space-y-4">
              <div class="inline-block">
                <span
                  class="px-4 py-2 rounded-full bg-gradient-to-r from-blue-500/20 to-purple-500/20 border border-blue-500/30 text-blue-300 text-sm font-medium"
                >
                  💫 Available for opportunities
                </span>
              </div>

              <h1
                class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight"
              >
                Hi, I'm
                <span
                  class="bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 bg-clip-text text-transparent"
                >
                  {{ profile.name.split(" ")[0] }}
                </span>
              </h1>

              <p class="text-xl md:text-2xl text-gray-300 font-light">
                {{ profile.title }}
              </p>

              <p class="text-gray-400 text-lg leading-relaxed max-w-2xl">
                {{ profile.summary }}
              </p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-6">
              <div
                v-for="stat in stats"
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
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
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
            </div>
          </div>

          <!-- Photo -->
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
            </div>
          </div>

          <!-- Details -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Education -->
            <div class="glass-card p-6">
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
                <p class="text-gray-400 mb-3">
                  GPA: {{ profile.education.gpa }}
                </p>
                <div class="bg-gray-800/50 p-4 rounded-lg">
                  <p class="text-sm text-gray-300">
                    {{ profile.education.thesis }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Interests -->
            <div class="glass-card p-6">
              <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                <span class="mr-3">🎯</span>
                Areas of Interest
              </h3>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <span
                  v-for="interest in profile.interests.slice(0, 9)"
                  :key="interest"
                  class="px-3 py-2 bg-gradient-to-r from-blue-500/20 to-purple-500/20 border border-blue-500/30 rounded-lg text-sm text-center text-blue-300 hover:scale-105 transition-transform cursor-default"
                >
                  {{ interest }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-20 lg:py-32 px-4 lg:px-6">
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
                <span v-if="project.github_url" class="text-green-400 text-xs"
                  >🔗</span
                >
                <span v-if="project.challenges" class="text-yellow-400 text-xs"
                  >⚡</span
                >
                <span v-if="project.solutions" class="text-blue-400 text-xs"
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

    <!-- Experience Section -->
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
                class="absolute left-4 top-6 w-4 h-4 bg-blue-500 rounded-full border-4 border-gray-900 shadow-lg hidden sm:block"
                :class="{ 'animate-pulse bg-green-500': experience.is_current }"
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

              <a
                :href="profile.github"
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center space-x-4 p-4 rounded-2xl bg-white/5 hover:bg-white/10 transition-all duration-300 group"
              >
                <div
                  class="w-12 h-12 bg-gradient-to-r from-gray-700 to-gray-900 rounded-full flex items-center justify-center"
                >
                  <span class="text-white text-xl">🔗</span>
                </div>
                <div>
                  <p class="text-gray-400 text-sm">GitHub</p>
                  <p
                    class="text-white font-medium group-hover:text-blue-400 transition-colors"
                  >
                    View Profile
                  </p>
                </div>
              </a>
            </div>
          </div>

          <!-- Contact Form -->
          <div class="glass-card p-8">
            <h3 class="text-2xl font-bold text-white mb-6">Send Message</h3>

            <form @submit.prevent="sendMessage" class="space-y-6">
              <div>
                <input
                  v-model="contactForm.name"
                  type="text"
                  placeholder="Your Name"
                  required
                  class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent backdrop-blur-sm transition-all duration-300"
                />
              </div>

              <div>
                <input
                  v-model="contactForm.email"
                  type="email"
                  placeholder="Your Email"
                  required
                  class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent backdrop-blur-sm transition-all duration-300"
                />
              </div>

              <div>
                <textarea
                  v-model="contactForm.message"
                  placeholder="Your Message"
                  rows="5"
                  required
                  class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent backdrop-blur-sm transition-all duration-300 resize-vertical"
                ></textarea>
              </div>

              <button
                type="submit"
                :disabled="contactForm.processing"
                class="w-full modern-btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="contactForm.processing">Sending...</span>
                <span v-else>Send Message</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 px-4 lg:px-6 border-t border-white/10">
      <div class="container mx-auto">
        <div class="text-center">
          <div class="flex justify-center items-center space-x-6 mb-6">
            <a
              :href="profile.github"
              target="_blank"
              rel="noopener noreferrer"
              class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-all duration-300 hover:scale-110"
            >
              <span>🔗</span>
            </a>
            <a
              :href="`mailto:${profile.email}`"
              class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-all duration-300 hover:scale-110"
            >
              <span>✉️</span>
            </a>
            <a
              :href="`tel:${profile.phone}`"
              class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-all duration-300 hover:scale-110"
            >
              <span>📱</span>
            </a>
          </div>

          <p class="text-gray-400 text-sm mb-2">
            © {{ new Date().getFullYear() }} {{ profile.name }}. All rights
            reserved.
          </p>
          <p class="text-gray-500 text-xs">
            Built with Laravel & Vue.js • GPA: {{ profile.education.gpa }}
          </p>
        </div>
      </div>
    </footer>

    <!-- Project Modal -->
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
              <div class="flex items-center space-x-4">
                <span
                  class="px-3 py-1 bg-gradient-to-r from-blue-500/30 to-purple-500/30 border border-blue-500/50 rounded-full text-sm text-blue-300"
                >
                  {{ selectedProject.project_type }}
                </span>
                <span class="text-gray-400 text-sm">{{
                  formatDate(selectedProject.project_date)
                }}</span>
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
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";

// Props
const props = defineProps({
  portfolios: Array,
  experiences: Array,
  skills: Object,
  profile: Object,
});

// Reactive state
const showMobileMenu = ref(false);
const selectedProject = ref(null);
const activeFilter = ref("all");
const showAllProjects = ref(false);
const showBackToTop = ref(false);

// Contact form
const contactForm = ref({
  name: "",
  email: "",
  message: "",
  processing: false,
});

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
const profilePhoto = computed(() => "/images/poto1.png");

const stats = computed(() => [
  { label: "Projects", value: `${props.portfolios.length}+` },
  { label: "GPA", value: props.profile.education.gpa },
  { label: "Experience", value: `${calculateYearsOfExperience()}yr` },
  { label: "Skills", value: `${Object.values(props.skills).flat().length}+` },
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

// Methods
const calculateYearsOfExperience = () => {
  if (!props.experiences.length) return 0;
  const firstExp = props.experiences.reduce((earliest, exp) => {
    return new Date(exp.start_date) < new Date(earliest.start_date)
      ? exp
      : earliest;
  });
  return new Date().getFullYear() - new Date(firstExp.start_date).getFullYear();
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
    document.querySelector(href)?.scrollIntoView({ behavior: "smooth" });
  }
};

const showProjectModal = (project) => {
  selectedProject.value = project;
  document.body.style.overflow = "hidden";
};

const closeProjectModal = () => {
  selectedProject.value = null;
  document.body.style.overflow = "auto";
};

const formatDate = (date) => {
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

const sendMessage = () => {
  contactForm.value.processing = true;

  const { name, email, message } = contactForm.value;
  const subject = `Portfolio Contact from ${name}`;
  const body = `Name: ${name}\nEmail: ${email}\n\nMessage:\n${message}`;

  window.location.href = `mailto:${
    props.profile.email
  }?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

  setTimeout(() => {
    contactForm.value = { name: "", email: "", message: "", processing: false };
  }, 1000);
};

// Lifecycle
onMounted(() => {
  window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
  document.body.style.overflow = "auto";
});
</script>

<style scoped>
/* Modern gradient background */
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
}

/* Glassmorphism effects */
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
}

.glass-card:hover {
  background: rgba(255, 255, 255, 0.12);
  border-color: rgba(59, 130, 246, 0.3);
  box-shadow: 0 32px 64px rgba(0, 0, 0, 0.3);
}

/* Modern buttons */
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

/* Section titles */
.section-title {
  @apply text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4;
  background: linear-gradient(135deg, #ffffff 0%, #3b82f6 50%, #9333ea 100%);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.section-subtitle {
  @apply text-lg text-gray-400 max-w-2xl mx-auto;
}

/* Animations */
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

/* Line clamp utility */
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
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

/* Mobile optimizations */
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

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
  .animate-fade-in-up,
  .animate-bounce,
  .animate-ping,
  .animate-pulse {
    animation: none;
  }

  .hover\:scale-105:hover,
  .hover\:scale-110:hover {
    transform: none;
  }
}

/* High contrast mode */
@media (prefers-contrast: high) {
  .glass-card {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.3);
  }

  .text-gray-300,
  .text-gray-400 {
    color: #ffffff;
  }
}

/* Focus states for accessibility */
button:focus,
a:focus,
input:focus,
textarea:focus {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}

/* Loading states */
.loading {
  position: relative;
  overflow: hidden;
}

.loading::after {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.1),
    transparent
  );
  animation: loading 1.5s infinite;
}

@keyframes loading {
  0% {
    left: -100%;
  }
  100% {
    left: 100%;
  }
}

/* Hover effects for cards */
.glass-card {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.glass-card:hover {
  transform: translateY(-8px) scale(1.02);
}

/* Gradient text effect */
.gradient-text {
  background: linear-gradient(135deg, #3b82f6, #9333ea, #ec4899);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-size: 200% 200%;
  animation: gradientShift 3s ease infinite;
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

/* Floating animation for decorative elements */
@keyframes float {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }
  33% {
    transform: translateY(-10px) rotate(5deg);
  }
  66% {
    transform: translateY(5px) rotate(-5deg);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

/* Particle effect background */
.particles::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: radial-gradient(
      1px 1px at 25px 5px,
      rgba(59, 130, 246, 0.3),
      transparent
    ),
    radial-gradient(1px 1px at 50px 25px, rgba(147, 51, 234, 0.3), transparent),
    radial-gradient(1px 1px at 125px 20px, rgba(236, 72, 153, 0.3), transparent),
    radial-gradient(1px 1px at 150px 75px, rgba(59, 130, 246, 0.3), transparent);
  background-size: 200px 100px;
  animation: particleMove 20s linear infinite;
  pointer-events: none;
}

@keyframes particleMove {
  0% {
    transform: translateX(0) translateY(0);
  }
  100% {
    transform: translateX(-200px) translateY(-100px);
  }
}
</style>
