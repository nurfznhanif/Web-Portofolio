<!-- resources/js/Pages/Dashboard/Index.vue - SAFE VERSION -->
<template>
  <DashboardLayout>
    <div class="p-6 space-y-6">
      <!-- Welcome Header -->
      <div
        class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white"
      >
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold">Welcome back, {{ userName }}!</h1>
            <p class="text-blue-100 mt-1">
              Here's what's happening with your portfolio today.
            </p>
          </div>
          <div class="text-right">
            <div class="text-blue-100 text-sm">{{ currentDate }}</div>
            <div class="text-xl font-semibold">{{ currentTime }}</div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Portfolio Stats -->
        <div
          class="bg-gray-800 rounded-lg p-6 hover:shadow-lg transition-shadow"
        >
          <div class="flex items-center">
            <div class="p-3 bg-blue-900 rounded-lg">
              <svg
                class="w-6 h-6 text-blue-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">
                {{ safeStats.portfolios }}
              </p>
              <p class="text-gray-400 text-sm">Total Projects</p>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="safeRoute('admin.portfolio.index')"
              class="text-blue-400 text-sm hover:text-blue-300"
            >
              Manage Projects →
            </Link>
          </div>
        </div>

        <!-- Experience Stats -->
        <div
          class="bg-gray-800 rounded-lg p-6 hover:shadow-lg transition-shadow"
        >
          <div class="flex items-center">
            <div class="p-3 bg-green-900 rounded-lg">
              <svg
                class="w-6 h-6 text-green-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V6m8 0H8m8 0v6"
                />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">
                {{ safeStats.experiences }}
              </p>
              <p class="text-gray-400 text-sm">Experiences</p>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="safeRoute('admin.experience.index')"
              class="text-green-400 text-sm hover:text-green-300"
            >
              Manage Experience →
            </Link>
          </div>
        </div>

        <!-- Skills Stats -->
        <div
          class="bg-gray-800 rounded-lg p-6 hover:shadow-lg transition-shadow"
        >
          <div class="flex items-center">
            <div class="p-3 bg-purple-900 rounded-lg">
              <svg
                class="w-6 h-6 text-purple-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">
                {{ safeStats.skills }}
              </p>
              <p class="text-gray-400 text-sm">Skills</p>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="safeRoute('admin.skill.index')"
              class="text-purple-400 text-sm hover:text-purple-300"
            >
              Manage Skills →
            </Link>
          </div>
        </div>

        <!-- Messages Stats -->
        <div
          class="bg-gray-800 rounded-lg p-6 hover:shadow-lg transition-shadow"
        >
          <div class="flex items-center">
            <div class="p-3 bg-yellow-900 rounded-lg">
              <svg
                class="w-6 h-6 text-yellow-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4m-4 0H8m-4 0h4m0 0V9a1 1 0 011-1h2a1 1 0 011 1v4M7 7h10"
                />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">{{ unreadMessages }}</p>
              <p class="text-gray-400 text-sm">Unread Messages</p>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="safeRoute('admin.messages.index')"
              class="text-yellow-400 text-sm hover:text-yellow-300"
            >
              View Messages →
            </Link>
          </div>
        </div>
      </div>

      <!-- Analytics Overview -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Analytics Summary -->
        <div class="bg-gray-800 rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Analytics Overview</h3>
            <Link
              :href="safeRoute('admin.analytics.index')"
              class="text-blue-400 text-sm hover:text-blue-300"
            >
              View Details →
            </Link>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-4 bg-gray-700 rounded-lg">
              <div class="text-2xl font-bold text-white">
                {{ formatNumber(safeAnalytics.total_views) }}
              </div>
              <div class="text-sm text-gray-400">Total Views</div>
            </div>
            <div class="text-center p-4 bg-gray-700 rounded-lg">
              <div class="text-2xl font-bold text-white">
                {{ formatNumber(safeAnalytics.unique_visitors) }}
              </div>
              <div class="text-sm text-gray-400">Unique Visitors</div>
            </div>
            <div class="text-center p-4 bg-gray-700 rounded-lg">
              <div class="text-2xl font-bold text-white">
                {{ formatNumber(safeAnalytics.cv_downloads) }}
              </div>
              <div class="text-sm text-gray-400">CV Downloads</div>
            </div>
            <div class="text-center p-4 bg-gray-700 rounded-lg">
              <div class="text-2xl font-bold text-white">
                {{ formatNumber(safeAnalytics.contact_forms) }}
              </div>
              <div class="text-sm text-gray-400">Contact Forms</div>
            </div>
          </div>
        </div>

        <!-- Recent Portfolio Projects -->
        <div class="bg-gray-800 rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Recent Projects</h3>
            <Link
              :href="safeRoute('admin.portfolio.create')"
              class="text-blue-400 text-sm hover:text-blue-300"
            >
              Add New →
            </Link>
          </div>

          <div class="space-y-3">
            <div v-if="recentProjects.length === 0" class="text-center py-8">
              <svg
                class="mx-auto h-12 w-12 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                />
              </svg>
              <p class="text-gray-400 mt-2">No projects yet</p>
              <Link
                :href="safeRoute('admin.portfolio.create')"
                class="mt-2 inline-block text-blue-400 hover:text-blue-300"
              >
                Create your first project
              </Link>
            </div>

            <div
              v-for="project in recentProjects"
              :key="project.id"
              class="flex items-center justify-between p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors"
            >
              <div class="flex-1">
                <h4 class="text-white font-medium">{{ project.title }}</h4>
                <div class="flex items-center space-x-2 mt-1">
                  <span
                    class="px-2 py-1 bg-blue-600 text-blue-100 text-xs rounded"
                  >
                    {{ project.project_type }}
                  </span>
                  <span class="text-gray-400 text-xs">
                    {{ formatDate(project.created_at) }}
                  </span>
                </div>
              </div>
              <Link
                :href="safeRoute('admin.portfolio.edit', project.id)"
                class="text-gray-400 hover:text-white"
              >
                <svg
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                  />
                </svg>
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-gray-800 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-white">Recent Activity</h3>
          <button
            @click="refreshData"
            :disabled="isRefreshing"
            class="text-gray-400 hover:text-white disabled:opacity-50"
          >
            <svg
              :class="{ 'animate-spin': isRefreshing }"
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
              />
            </svg>
          </button>
        </div>

        <div class="space-y-4">
          <div
            v-for="activity in safeActivity"
            :key="activity.id"
            class="flex items-start space-x-3 p-3 bg-gray-700 rounded-lg"
          >
            <div
              :class="[
                'flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm',
                getActivityColor(activity.type),
              ]"
            >
              {{ getActivityIcon(activity.type) }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-white font-medium">{{ activity.title }}</p>
              <p class="text-gray-400 text-sm">{{ activity.description }}</p>
              <p class="text-gray-500 text-xs mt-1">
                {{ formatDateTime(activity.created_at) }}
              </p>
            </div>
          </div>

          <div v-if="safeActivity.length === 0" class="text-center py-8">
            <svg
              class="mx-auto h-12 w-12 text-gray-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <p class="text-gray-400 mt-2">No recent activity</p>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { useRoute } from "@/composables/useRoute";

// Get page data
const page = usePage();
const route = useRoute();

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({}),
  },
  analytics: {
    type: Object,
    default: () => ({}),
  },
  recentActivity: {
    type: Array,
    default: () => [],
  },
});

// Reactive data
const currentTimeRef = ref(new Date());
const isRefreshing = ref(false);
const unreadMessages = ref(0);

// Safe computed properties
const userName = computed(() => {
  try {
    return page.props.auth?.user?.name || "Admin User";
  } catch (error) {
    return "Admin User";
  }
});

const currentDate = computed(() => {
  return currentTimeRef.value.toLocaleDateString("en-US", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });
});

const currentTime = computed(() => {
  return currentTimeRef.value.toLocaleTimeString("en-US", {
    hour: "2-digit",
    minute: "2-digit",
  });
});

const safeStats = computed(() => ({
  portfolios: props.stats?.portfolios || 0,
  experiences: props.stats?.experiences || 0,
  skills: props.stats?.skills || 0,
  recent_portfolios: props.stats?.recent_portfolios || [],
}));

const safeAnalytics = computed(() => ({
  total_views: props.analytics?.total_views || 0,
  unique_visitors: props.analytics?.unique_visitors || 0,
  cv_downloads: props.analytics?.cv_downloads || 0,
  contact_forms: props.analytics?.contact_forms || 0,
}));

const recentProjects = computed(() => {
  return safeStats.value.recent_portfolios || [];
});

const safeActivity = computed(() => {
  return props.recentActivity || [];
});

// Safe route helper
const safeRoute = (name, params = null) => {
  try {
    return route(name, params);
  } catch (error) {
    console.warn("Route error:", error);
    return "#";
  }
};

// Methods
const formatNumber = (num) => {
  return new Intl.NumberFormat().format(num || 0);
};

const formatDate = (date) => {
  try {
    return new Date(date).toLocaleDateString("en-US", {
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  } catch (error) {
    return "Invalid date";
  }
};

const formatDateTime = (date) => {
  try {
    return new Date(date).toLocaleString("en-US", {
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  } catch (error) {
    return "Invalid date";
  }
};

const getActivityIcon = (type) => {
  const icons = {
    portfolio_created: "✨",
    experience_added: "💼",
    skill_added: "⚡",
    message_received: "💬",
    profile_updated: "👤",
    analytics_updated: "📊",
    cache_cleared: "🗑️",
    backup_created: "💾",
  };
  return icons[type] || "📝";
};

const getActivityColor = (type) => {
  const colors = {
    portfolio_created: "bg-blue-600",
    experience_added: "bg-green-600",
    skill_added: "bg-purple-600",
    message_received: "bg-yellow-600",
    profile_updated: "bg-indigo-600",
    analytics_updated: "bg-pink-600",
    cache_cleared: "bg-red-600",
    backup_created: "bg-gray-600",
  };
  return colors[type] || "bg-gray-600";
};

const updateTime = () => {
  currentTimeRef.value = new Date();
};

const refreshData = async () => {
  isRefreshing.value = true;
  try {
    await router.reload({ only: ["stats", "analytics", "recentActivity"] });
  } catch (error) {
    console.error("Failed to refresh data:", error);
  } finally {
    isRefreshing.value = false;
  }
};

// Lifecycle
onMounted(() => {
  // Update time every minute
  const timeInterval = setInterval(updateTime, 60000);

  onUnmounted(() => {
    clearInterval(timeInterval);
  });
});
</script>

<style scoped>
.transition-all {
  transition: all 0.3s ease;
}

.hover\:scale-105:hover {
  transform: scale(1.05);
}

@keyframes pulse {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
