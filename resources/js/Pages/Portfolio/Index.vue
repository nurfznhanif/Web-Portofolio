<template>
  <Head title="Portfolio" />

  <div class="min-h-screen bg-gray-900 text-white">
    <div class="container mx-auto px-6 py-20">
      <div class="text-center">
        <h1 class="text-6xl font-bold mb-8 text-red-400">
          {{ profile?.name || "Nurfauzan Hanif" }}
        </h1>
        <p class="text-xl mb-8 text-gray-300 max-w-3xl mx-auto">
          {{ profile?.bio || "Portfolio Website" }}
        </p>

        <div v-if="profile" class="mb-16">
          <div
            class="flex flex-col md:flex-row gap-4 justify-center items-center"
          >
            <div class="flex items-center text-gray-300">
              <i class="fas fa-map-marker-alt mr-2 text-red-400"></i>
              {{ profile.location }}
            </div>
            <div class="flex items-center text-gray-300">
              <i class="fas fa-envelope mr-2 text-red-400"></i>
              {{ profile.email }}
            </div>
            <div class="flex items-center text-gray-300">
              <i class="fas fa-phone mr-2 text-red-400"></i>
              {{ profile.phone }}
            </div>
          </div>
        </div>

        <!-- Projects Section -->
        <div v-if="projects && projects.length > 0" class="mb-16">
          <h2 class="text-4xl font-bold mb-8 text-red-400">
            Featured Projects
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
              v-for="project in projects"
              :key="project.id"
              class="bg-gray-800 p-6 rounded-lg"
            >
              <h3 class="text-xl font-bold mb-4 text-red-400">
                {{ project.title }}
              </h3>
              <p class="text-gray-300 mb-4">{{ project.description }}</p>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="tech in project.technologies"
                  :key="tech"
                  class="px-3 py-1 bg-red-600 text-white rounded-full text-sm"
                >
                  {{ tech }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Skills Section -->
        <div v-if="skills && skills.length > 0" class="mb-16">
          <h2 class="text-4xl font-bold mb-8 text-red-400">Skills</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="skill in skills"
              :key="skill.id"
              class="bg-gray-800 p-4 rounded-lg"
            >
              <h3 class="text-lg font-bold text-white mb-2">
                {{ skill.name }}
              </h3>
              <p class="text-sm text-gray-400 mb-2">{{ skill.category }}</p>
              <p class="text-xs text-gray-300">{{ skill.description }}</p>
              <span
                class="inline-block mt-2 px-2 py-1 bg-blue-600 text-white rounded text-xs"
              >
                {{ skill.level }}
              </span>
            </div>
          </div>
        </div>

        <!-- Experience Section -->
        <div v-if="experiences && experiences.length > 0" class="mb-16">
          <h2 class="text-4xl font-bold mb-8 text-red-400">Experience</h2>
          <div class="space-y-6">
            <div
              v-for="exp in experiences"
              :key="exp.id"
              class="bg-gray-800 p-6 rounded-lg text-left"
            >
              <h3 class="text-xl font-bold text-red-400 mb-2">
                {{ exp.title }}
              </h3>
              <p class="text-lg text-gray-300 mb-2">{{ exp.company }}</p>
              <p class="text-sm text-gray-400 mb-4">
                {{ formatDate(exp.start_date) }} -
                {{ exp.is_current ? "Present" : formatDate(exp.end_date) }}
              </p>
              <p class="text-gray-300">{{ exp.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";

// Props dari server (Inertia)
const props = defineProps({
  profile: Object,
  projects: Array,
  skills: Array,
  experiences: Array,
});

// Methods
const formatDate = (dateString) => {
  if (!dateString) return "";
  const date = new Date(dateString);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
  });
};
</script>
