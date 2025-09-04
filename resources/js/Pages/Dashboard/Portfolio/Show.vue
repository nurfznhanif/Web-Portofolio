<template>
  <DashboardLayout title="Portfolio Details">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <Link
            :href="route('admin.portfolio.index')"
            class="text-gray-400 hover:text-white transition-colors"
          >
            ← Back to Portfolio
          </Link>
          <h1 class="text-2xl font-bold text-white">{{ portfolio.title }}</h1>
        </div>
        <div class="flex space-x-3">
          <Link
            :href="route('admin.portfolio.edit', portfolio.id)"
            class="px-4 py-2 bg-green-900 hover:bg-green-800 text-white rounded-lg transition-colors"
          >
            Edit Project
          </Link>
        </div>
      </div>

      <!-- Project Details -->
      <div class="grid md:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
          <!-- Description -->
          <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">
              Project Description
            </h3>
            <p class="text-gray-300 leading-relaxed">
              {{ portfolio.description }}
            </p>
          </div>

          <!-- Challenges -->
          <div
            v-if="portfolio.challenges"
            class="bg-gray-800 border border-gray-700 rounded-lg p-6"
          >
            <h3 class="text-lg font-semibold text-red-400 mb-4">
              Challenges Faced
            </h3>
            <p class="text-gray-300 leading-relaxed">
              {{ portfolio.challenges }}
            </p>
          </div>

          <!-- Solutions -->
          <div
            v-if="portfolio.solutions"
            class="bg-gray-800 border border-gray-700 rounded-lg p-6"
          >
            <h3 class="text-lg font-semibold text-green-400 mb-4">
              Solutions & Lessons Learned
            </h3>
            <p class="text-gray-300 leading-relaxed">
              {{ portfolio.solutions }}
            </p>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Project Info -->
          <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">
              Project Information
            </h3>
            <div class="space-y-4">
              <div>
                <label class="text-sm font-medium text-gray-400">Type</label>
                <p class="text-white">{{ portfolio.project_type }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-400">Date</label>
                <p class="text-white">
                  {{ formatDate(portfolio.project_date) }}
                </p>
              </div>
              <div v-if="portfolio.github_url">
                <label class="text-sm font-medium text-gray-400"
                  >Repository</label
                >
                <a
                  :href="portfolio.github_url"
                  target="_blank"
                  class="block text-red-400 hover:text-red-300 transition-colors break-all"
                >
                  {{ portfolio.github_url }}
                </a>
              </div>
            </div>
          </div>

          <!-- Technologies -->
          <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">
              Technologies Used
            </h3>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="tech in getTechnologies(portfolio.technologies)"
                :key="tech"
                class="px-3 py-1 bg-red-900/30 text-red-300 rounded-full text-sm border border-red-800"
              >
                {{ tech }}
              </span>
            </div>
          </div>

          <!-- Timestamps -->
          <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Record Info</h3>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-400">Created:</span>
                <span class="text-gray-300">{{
                  formatDateTime(portfolio.created_at)
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-400">Updated:</span>
                <span class="text-gray-300">{{
                  formatDateTime(portfolio.updated_at)
                }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

defineProps({
  portfolio: Object,
});

const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("id-ID", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

const formatDateTime = (datetime) => {
  if (!datetime) return "";
  return new Date(datetime).toLocaleString("id-ID", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const getTechnologies = (technologies) => {
  if (!technologies) return [];
  return technologies.split(", ").map((tech) => tech.trim());
};
</script>
