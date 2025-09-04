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
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section
      id="home"
      class="min-h-screen flex items-center justify-center relative overflow-hidden"
    >
      <div
        class="absolute inset-0 bg-gradient-to-br from-red-900/20 to-black/50"
      ></div>
      <div class="container mx-auto px-6 text-center relative z-10">
        <div class="animate-fade-in-up">
          <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 font-serif">
            {{ profile.name }}
          </h1>
          <p class="text-xl md:text-2xl text-red-400 mb-8 font-light">
            {{ profile.title }}
          </p>
          <p
            class="text-lg text-gray-300 max-w-3xl mx-auto mb-10 leading-relaxed"
          >
            {{ profile.summary }}
          </p>
          <div class="flex justify-center space-x-6">
            <a
              :href="profile.github"
              target="_blank"
              class="blood-red-gradient px-8 py-3 text-white font-semibold rounded-lg hover:scale-105 transition-transform shadow-lg animate-shadow-pulse"
            >
              View GitHub
            </a>
            <a
              href="#portfolio"
              class="border-2 border-red-600 px-8 py-3 text-red-400 hover:bg-red-600 hover:text-white transition-all rounded-lg"
            >
              View Portfolio
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-black/30">
      <div class="container mx-auto px-6">
        <h2 class="text-4xl font-bold text-center text-white mb-16 font-serif">
          About Me
        </h2>
        <div class="grid md:grid-cols-2 gap-12 items-center">
          <div class="gothic-card p-8 rounded-xl">
            <h3 class="text-2xl font-bold text-red-400 mb-4">Education</h3>
            <div class="space-y-4 text-gray-300">
              <div>
                <h4 class="font-semibold text-white">
                  {{ profile.education.degree }}
                </h4>
                <p class="text-red-300">{{ profile.education.university }}</p>
                <p class="text-sm">IPK: {{ profile.education.gpa }}</p>
              </div>
              <div>
                <h5 class="font-semibold text-white mb-2">Thesis:</h5>
                <p class="text-sm leading-relaxed">
                  {{ profile.education.thesis }}
                </p>
              </div>
            </div>
          </div>
          <div class="gothic-card p-8 rounded-xl">
            <h3 class="text-2xl font-bold text-red-400 mb-4">Contact Info</h3>
            <div class="space-y-3 text-gray-300">
              <div class="flex items-center">
                <span class="text-red-400 mr-3">📧</span>
                <a
                  :href="'mailto:' + profile.email"
                  class="hover:text-red-400 transition-colors"
                >
                  {{ profile.email }}
                </a>
              </div>
              <div class="flex items-center">
                <span class="text-red-400 mr-3">📱</span>
                <span>{{ profile.phone }}</span>
              </div>
              <div class="flex items-center">
                <span class="text-red-400 mr-3">📍</span>
                <span>{{ profile.location }}</span>
              </div>
            </div>
            <div class="mt-6">
              <h4 class="font-semibold text-white mb-3">Interests</h4>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="interest in profile.interests"
                  :key="interest"
                  class="px-3 py-1 bg-red-900/30 text-red-300 rounded-full text-sm border border-red-800"
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
    <section id="portfolio" class="py-20">
      <div class="container mx-auto px-6">
        <h2 class="text-4xl font-bold text-center text-white mb-16 font-serif">
          Portfolio
        </h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="project in portfolios"
            :key="project.id"
            class="gothic-card p-6 rounded-xl hover:scale-105 transition-transform group cursor-pointer"
            @click="showProject(project)"
          >
            <div class="mb-4">
              <h3
                class="text-xl font-bold text-white group-hover:text-red-400 transition-colors"
              >
                {{ project.title }}
              </h3>
              <p class="text-red-400 text-sm">{{ project.project_type }}</p>
            </div>
            <p class="text-gray-300 text-sm mb-4 line-clamp-3">
              {{ project.description }}
            </p>
            <div class="mb-4">
              <div class="flex flex-wrap gap-1">
                <span
                  v-for="tech in project.technologies.split(', ')"
                  :key="tech"
                  class="px-2 py-1 bg-gray-800 text-gray-300 rounded text-xs"
                >
                  {{ tech }}
                </span>
              </div>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-400 text-sm">{{
                formatDate(project.project_date)
              }}</span>
              <span
                class="text-red-400 group-hover:translate-x-1 transition-transform"
                >→</span
              >
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Experience Section -->
    <section id="experience" class="py-20 bg-black/30">
      <div class="container mx-auto px-6">
        <h2 class="text-4xl font-bold text-center text-white mb-16 font-serif">
          Experience
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
              class="absolute left-4 top-12 w-0.5 h-full bg-red-600/30"
            ></div>

            <!-- Timeline dot -->
            <div
              class="absolute left-2 top-4 w-4 h-4 bg-red-600 rounded-full border-2 border-gray-900"
            ></div>

            <!-- Content -->
            <div class="ml-12 gothic-card p-6 rounded-xl">
              <div
                class="flex flex-col md:flex-row md:justify-between md:items-start mb-3"
              >
                <div>
                  <h3 class="text-xl font-bold text-white">
                    {{ experience.position }}
                  </h3>
                  <p class="text-red-400">{{ experience.organization }}</p>
                </div>
                <div class="text-gray-400 text-sm mt-2 md:mt-0">
                  {{ formatDate(experience.start_date) }} -
                  {{
                    experience.is_current
                      ? "Present"
                      : formatDate(experience.end_date)
                  }}
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
        <h2 class="text-4xl font-bold text-center text-white mb-16 font-serif">
          Skills
        </h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="(skillList, category) in skills"
            :key="category"
            class="gothic-card p-6 rounded-xl"
          >
            <h3 class="text-xl font-bold text-red-400 mb-4">{{ category }}</h3>
            <div class="space-y-3">
              <div
                v-for="skill in skillList"
                :key="skill.id"
                class="flex justify-between items-center"
              >
                <span class="text-white">{{ skill.name }}</span>
                <div class="flex items-center">
                  <div class="w-20 h-2 bg-gray-800 rounded-full mr-2">
                    <div
                      class="h-full bg-gradient-to-r from-red-600 to-red-400 rounded-full"
                      :style="{ width: getSkillWidth(skill.level) }"
                    ></div>
                  </div>
                  <span class="text-gray-400 text-xs capitalize">{{
                    skill.level
                  }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 bg-black/50">
      <div class="container mx-auto px-6 text-center">
        <div class="mb-6">
          <a
            :href="profile.github"
            target="_blank"
            class="inline-flex items-center text-red-400 hover:text-red-300 transition-colors"
          >
            <span class="mr-2">🔗</span>
            GitHub Portfolio
          </a>
        </div>
        <p class="text-gray-400">
          &copy; {{ new Date().getFullYear() }} {{ profile.name }}. All rights
          reserved.
        </p>
      </div>
    </footer>

    <!-- Project Modal -->
    <div
      v-if="selectedProject"
      class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4"
      @click="closeModal"
    >
      <div
        class="gothic-card max-w-4xl w-full max-h-[90vh] overflow-y-auto rounded-xl"
        @click.stop
      >
        <div class="p-8">
          <div class="flex justify-between items-start mb-6">
            <div>
              <h2 class="text-3xl font-bold text-white mb-2">
                {{ selectedProject.title }}
              </h2>
              <p class="text-red-400">{{ selectedProject.project_type }}</p>
            </div>
            <button
              @click="closeModal"
              class="text-gray-400 hover:text-white text-2xl"
            >
              ×
            </button>
          </div>

          <div class="space-y-6">
            <div>
              <h3 class="text-xl font-semibold text-red-400 mb-3">
                Description
              </h3>
              <p class="text-gray-300 leading-relaxed">
                {{ selectedProject.description }}
              </p>
            </div>

            <div>
              <h3 class="text-xl font-semibold text-red-400 mb-3">
                Technologies
              </h3>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="tech in selectedProject.technologies.split(', ')"
                  :key="tech"
                  class="px-3 py-1 bg-gray-800 text-gray-300 rounded-lg"
                >
                  {{ tech }}
                </span>
              </div>
            </div>

            <div v-if="selectedProject.challenges">
              <h3 class="text-xl font-semibold text-red-400 mb-3">
                Challenges
              </h3>
              <p class="text-gray-300 leading-relaxed">
                {{ selectedProject.challenges }}
              </p>
            </div>

            <div v-if="selectedProject.solutions">
              <h3 class="text-xl font-semibold text-red-400 mb-3">Solutions</h3>
              <p class="text-gray-300 leading-relaxed">
                {{ selectedProject.solutions }}
              </p>
            </div>

            <div class="flex justify-between items-center pt-6">
              <span class="text-gray-400">{{
                formatDate(selectedProject.project_date)
              }}</span>
              <a
                v-if="selectedProject.github_url"
                :href="selectedProject.github_url"
                target="_blank"
                class="blood-red-gradient px-6 py-2 text-white rounded-lg hover:scale-105 transition-transform"
              >
                View on GitHub
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

// Props
defineProps({
  portfolios: Array,
  experiences: Array,
  skills: Object,
  profile: Object,
});

// State
const selectedProject = ref(null);

// Methods
const showProject = (project) => {
  selectedProject.value = project;
};

const closeModal = () => {
  selectedProject.value = null;
};

const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("id-ID", {
    year: "numeric",
    month: "long",
  });
};

const getSkillWidth = (level) => {
  const levels = {
    pemula: "40%",
    menengah: "70%",
    mahir: "95%",
  };
  return levels[level] || "50%";
};
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
