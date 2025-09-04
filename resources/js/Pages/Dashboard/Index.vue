<template>
  <DashboardLayout title="Dashboard">
    <div class="space-y-6">
      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-red-900 rounded-lg">
              <span class="text-2xl">💼</span>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-semibold text-white">
                Portfolio Projects
              </h3>
              <p class="text-3xl font-bold text-red-400">
                {{ stats.portfolios }}
              </p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-900 rounded-lg">
              <span class="text-2xl">🏢</span>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-semibold text-white">Experiences</h3>
              <p class="text-3xl font-bold text-blue-400">
                {{ stats.experiences }}
              </p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-900 rounded-lg">
              <span class="text-2xl">🛠️</span>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-semibold text-white">Skills</h3>
              <p class="text-3xl font-bold text-green-400">
                {{ stats.skills }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <Link
            :href="route('admin.portfolio.create')"
            class="flex items-center justify-center p-4 bg-red-900 hover:bg-red-800 rounded-lg text-white transition-colors"
          >
            <span class="mr-2">➕</span>
            Add Portfolio
          </Link>

          <Link
            :href="route('admin.experience.create')"
            class="flex items-center justify-center p-4 bg-blue-900 hover:bg-blue-800 rounded-lg text-white transition-colors"
          >
            <span class="mr-2">➕</span>
            Add Experience
          </Link>

          <Link
            :href="route('admin.skill.create')"
            class="flex items-center justify-center p-4 bg-green-900 hover:bg-green-800 rounded-lg text-white transition-colors"
          >
            <span class="mr-2">➕</span>
            Add Skill
          </Link>
        </div>
      </div>

      <!-- Recent Portfolio Projects -->
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-white">
            Recent Portfolio Projects
          </h3>
          <Link
            :href="route('admin.portfolio.index')"
            class="text-red-400 hover:text-red-300 transition-colors"
          >
            View All →
          </Link>
        </div>

        <div class="space-y-3">
          <div
            v-for="portfolio in stats.recent_portfolios"
            :key="portfolio.id"
            class="flex items-center justify-between p-3 bg-gray-700 rounded-lg"
          >
            <div>
              <h4 class="font-medium text-white">{{ portfolio.title }}</h4>
              <p class="text-sm text-gray-400">{{ portfolio.project_type }}</p>
            </div>
            <div class="text-right">
              <p class="text-sm text-gray-400">
                {{ formatDate(portfolio.project_date) }}
              </p>
              <Link
                :href="route('admin.portfolio.edit', portfolio.id)"
                class="text-red-400 hover:text-red-300 text-sm transition-colors"
              >
                Edit
              </Link>
            </div>
          </div>

          <div
            v-if="stats.recent_portfolios.length === 0"
            class="text-center py-8 text-gray-400"
          >
            <p>No portfolio projects yet.</p>
            <Link
              :href="route('admin.portfolio.create')"
              class="text-red-400 hover:text-red-300 transition-colors"
            >
              Create your first project →
            </Link>
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
  stats: Object,
});

const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("id-ID", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};
</script>
