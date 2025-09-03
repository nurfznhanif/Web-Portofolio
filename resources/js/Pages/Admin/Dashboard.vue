<template>
  <Head title="Admin Dashboard" />

  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-600">Manage your portfolio content</p>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 text-white"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-briefcase text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-blue-100">Total Projects</p>
              <p class="text-2xl font-semibold">{{ stats.projects }}</p>
            </div>
          </div>
        </div>

        <div
          class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-sm p-6 text-white"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-building text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-green-100">Experiences</p>
              <p class="text-2xl font-semibold">{{ stats.experiences }}</p>
            </div>
          </div>
        </div>

        <div
          class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-sm p-6 text-white"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-code text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-purple-100">Skills</p>
              <p class="text-2xl font-semibold">{{ stats.skills }}</p>
            </div>
          </div>
        </div>

        <div
          class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-sm p-6 text-white"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-star text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-red-100">Featured Projects</p>
              <p class="text-2xl font-semibold">
                {{ stats.featured_projects }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <Link
            :href="route('admin.projects.create')"
            class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors group"
          >
            <i
              class="fas fa-plus text-gray-400 group-hover:text-blue-500 mr-3"
            ></i>
            <span class="text-gray-600 group-hover:text-blue-700 font-medium"
              >Add Project</span
            >
          </Link>

          <Link
            :href="route('admin.experiences.create')"
            class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors group"
          >
            <i
              class="fas fa-plus text-gray-400 group-hover:text-green-500 mr-3"
            ></i>
            <span class="text-gray-600 group-hover:text-green-700 font-medium"
              >Add Experience</span
            >
          </Link>

          <Link
            :href="route('admin.skills.create')"
            class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-colors group"
          >
            <i
              class="fas fa-plus text-gray-400 group-hover:text-purple-500 mr-3"
            ></i>
            <span class="text-gray-600 group-hover:text-purple-700 font-medium"
              >Add Skill</span
            >
          </Link>

          <Link
            :href="route('admin.profile.edit')"
            class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-red-500 hover:bg-red-50 transition-colors group"
          >
            <i
              class="fas fa-edit text-gray-400 group-hover:text-red-500 mr-3"
            ></i>
            <span class="text-gray-600 group-hover:text-red-700 font-medium"
              >Edit Profile</span
            >
          </Link>
        </div>
      </div>

      <!-- Recent Projects -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold text-gray-900">Recent Projects</h2>
          <Link
            :href="route('admin.projects.index')"
            class="text-blue-600 hover:text-blue-800 text-sm"
          >
            View all
          </Link>
        </div>
        <div class="space-y-3">
          <div
            v-for="project in recentProjects"
            :key="project.id"
            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
          >
            <div class="flex items-center">
              <div
                class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center"
              >
                <i class="fas fa-project-diagram text-white text-sm"></i>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-gray-900">
                  {{ project.title }}
                </p>
                <p class="text-xs text-gray-500">
                  {{ formatDate(project.created_at) }}
                </p>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <span
                v-if="project.featured"
                class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800"
              >
                <i class="fas fa-star mr-1"></i>
                Featured
              </span>
              <Link
                :href="route('admin.projects.edit', project.id)"
                class="text-blue-600 hover:text-blue-800"
              >
                <i class="fas fa-edit"></i>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
  projects: Array,
  experiences: Array,
  skills: Array,
});

const stats = computed(() => ({
  projects: props.projects.length,
  experiences: props.experiences.length,
  skills: props.skills.length,
  featured_projects: props.projects.filter((p) => p.featured).length,
}));

const recentProjects = computed(() => {
  return props.projects
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
    .slice(0, 5);
});

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString();
};
</script>
