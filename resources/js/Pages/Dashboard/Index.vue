<template>
  <DashboardLayout title="Dashboard">
    <div class="space-y-6">
      <!-- Welcome Header -->
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-white">
              Welcome back, {{ $page.props.auth.user.name }}! 👋
            </h1>
            <p class="text-gray-400 mt-1">
              Here's an overview of your portfolio system
            </p>
          </div>
          <div class="text-gray-400 text-sm">
            {{ currentDateTime }}
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Portfolio Projects Card -->
        <div
          class="bg-gray-800 border border-gray-700 rounded-lg p-6 transition-all hover:border-red-500 hover:shadow-lg hover:shadow-red-500/20"
        >
          <div class="flex items-center">
            <div class="p-3 bg-red-900 rounded-lg">
              <span class="text-2xl">💼</span>
            </div>
            <div class="ml-4 flex-1">
              <h3 class="text-lg font-semibold text-white">
                Portfolio Projects
              </h3>
              <p class="text-3xl font-bold text-red-400">
                {{ stats.portfolios }}
              </p>
              <p class="text-sm text-gray-400 mt-1">Active projects</p>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="route('admin.portfolio.index')"
              class="text-red-400 hover:text-red-300 text-sm font-medium"
            >
              Manage Projects →
            </Link>
          </div>
        </div>

        <!-- Experiences Card -->
        <div
          class="bg-gray-800 border border-gray-700 rounded-lg p-6 transition-all hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/20"
        >
          <div class="flex items-center">
            <div class="p-3 bg-blue-900 rounded-lg">
              <span class="text-2xl">🏢</span>
            </div>
            <div class="ml-4 flex-1">
              <h3 class="text-lg font-semibold text-white">Experiences</h3>
              <p class="text-3xl font-bold text-blue-400">
                {{ stats.experiences }}
              </p>
              <p class="text-sm text-gray-400 mt-1">Work history</p>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="route('admin.experience.index')"
              class="text-blue-400 hover:text-blue-300 text-sm font-medium"
            >
              Manage Experience →
            </Link>
          </div>
        </div>

        <!-- Skills Card -->
        <div
          class="bg-gray-800 border border-gray-700 rounded-lg p-6 transition-all hover:border-green-500 hover:shadow-lg hover:shadow-green-500/20"
        >
          <div class="flex items-center">
            <div class="p-3 bg-green-900 rounded-lg">
              <span class="text-2xl">🛠️</span>
            </div>
            <div class="ml-4 flex-1">
              <h3 class="text-lg font-semibold text-white">Skills</h3>
              <p class="text-3xl font-bold text-green-400">
                {{ stats.skills }}
              </p>
              <p class="text-sm text-gray-400 mt-1">Technologies</p>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="route('admin.skill.index')"
              class="text-green-400 hover:text-green-300 text-sm font-medium"
            >
              Manage Skills →
            </Link>
          </div>
        </div>

        <!-- Messages Card -->
        <div
          class="bg-gray-800 border border-gray-700 rounded-lg p-6 transition-all hover:border-yellow-500 hover:shadow-lg hover:shadow-yellow-500/20"
        >
          <div class="flex items-center">
            <div class="p-3 bg-yellow-900 rounded-lg">
              <span class="text-2xl">💬</span>
            </div>
            <div class="ml-4 flex-1">
              <h3 class="text-lg font-semibold text-white">Messages</h3>
              <p class="text-3xl font-bold text-yellow-400">
                {{ unreadMessages }}
              </p>
              <p class="text-sm text-gray-400 mt-1">Unread contacts</p>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="route('admin.messages.index')"
              class="text-yellow-400 hover:text-yellow-300 text-sm font-medium"
            >
              View Messages →
            </Link>
          </div>
        </div>
      </div>

      <!-- Quick Actions Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
          <h2 class="text-xl font-bold text-white mb-4">Quick Actions</h2>
          <div class="grid grid-cols-2 gap-4">
            <Link
              :href="route('admin.portfolio.create')"
              class="flex flex-col items-center p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
            >
              <span class="text-2xl mb-2">➕</span>
              <span class="text-white text-sm font-medium">Add Project</span>
            </Link>
            <Link
              :href="route('admin.experience.create')"
              class="flex flex-col items-center p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
            >
              <span class="text-2xl mb-2">💼</span>
              <span class="text-white text-sm font-medium">Add Experience</span>
            </Link>
            <Link
              :href="route('admin.skill.create')"
              class="flex flex-col items-center p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
            >
              <span class="text-2xl mb-2">⚡</span>
              <span class="text-white text-sm font-medium">Add Skill</span>
            </Link>
            <Link
              :href="route('admin.analytics.index')"
              class="flex flex-col items-center p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
            >
              <span class="text-2xl mb-2">📊</span>
              <span class="text-white text-sm font-medium">View Analytics</span>
            </Link>
          </div>
        </div>

        <!-- Recent Portfolio Projects -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">Recent Projects</h2>
            <Link
              :href="route('admin.portfolio.index')"
              class="text-red-400 hover:text-red-300 text-sm font-medium"
            >
              View All →
            </Link>
          </div>
          <div
            class="space-y-3"
            v-if="stats.recent_portfolios && stats.recent_portfolios.length > 0"
          >
            <div
              v-for="project in stats.recent_portfolios"
              :key="project.id"
              class="flex items-center justify-between p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors"
            >
              <div class="flex-1">
                <h3 class="text-white font-medium">{{ project.title }}</h3>
                <p class="text-gray-400 text-sm">{{ project.project_type }}</p>
              </div>
              <div class="flex space-x-2">
                <Link
                  :href="route('admin.portfolio.edit', project.id)"
                  class="text-blue-400 hover:text-blue-300 text-sm"
                >
                  Edit
                </Link>
                <Link
                  :href="route('admin.portfolio.show', project.id)"
                  class="text-green-400 hover:text-green-300 text-sm"
                >
                  View
                </Link>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <span class="text-6xl mb-4 block">📝</span>
            <p class="text-gray-400">No projects yet</p>
            <Link
              :href="route('admin.portfolio.create')"
              class="inline-block mt-3 px-4 py-2 bg-red-900 hover:bg-red-800 text-white rounded-lg text-sm transition-colors"
            >
              Create Your First Project
            </Link>
          </div>
        </div>
      </div>

      <!-- System Status and Analytics -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- System Health -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
          <h2 class="text-xl font-bold text-white mb-4">System Status</h2>
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-gray-400">Database</span>
              <span class="flex items-center text-green-400">
                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                Healthy
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-400">Cache</span>
              <span class="flex items-center text-green-400">
                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                Active
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-400">Storage</span>
              <span class="flex items-center text-green-400">
                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                Available
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-400">Last Backup</span>
              <span class="text-gray-300 text-sm">{{ lastBackup }}</span>
            </div>
          </div>
        </div>

        <!-- Analytics Overview -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">Analytics</h2>
            <Link
              :href="route('admin.analytics.index')"
              class="text-blue-400 hover:text-blue-300 text-sm font-medium"
            >
              View Details →
            </Link>
          </div>
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-gray-400">Total Views</span>
              <span class="text-white font-medium">{{
                formatNumber(totalViews)
              }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-400">Unique Visitors</span>
              <span class="text-white font-medium">{{
                formatNumber(uniqueVisitors)
              }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-400">CV Downloads</span>
              <span class="text-white font-medium">{{
                formatNumber(cvDownloads)
              }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-400">Contact Forms</span>
              <span class="text-white font-medium">{{
                formatNumber(contactForms)
              }}</span>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
          <h2 class="text-xl font-bold text-white mb-4">Quick Links</h2>
          <div class="space-y-3">
            <a
              :href="route('portfolio.index')"
              target="_blank"
              class="flex items-center justify-between p-3 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
            >
              <span class="text-white">View Public Portfolio</span>
              <span class="text-gray-400">↗</span>
            </a>
            <Link
              :href="route('admin.profile.edit')"
              class="flex items-center justify-between p-3 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
            >
              <span class="text-white">Profile Settings</span>
              <span class="text-gray-400">→</span>
            </Link>
            <button
              @click="clearCache"
              class="w-full flex items-center justify-between p-3 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors"
              :disabled="clearingCache"
            >
              <span class="text-white">Clear Cache</span>
              <span class="text-gray-400" v-if="!clearingCache">🗑️</span>
              <span class="text-gray-400" v-else>⏳</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Recent Activity Feed -->
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
        <h2 class="text-xl font-bold text-white mb-4">Recent Activity</h2>
        <div
          class="space-y-4"
          v-if="recentActivity && recentActivity.length > 0"
        >
          <div
            v-for="activity in recentActivity"
            :key="activity.id"
            class="flex items-start space-x-4 p-4 bg-gray-700 rounded-lg"
          >
            <div class="flex-shrink-0">
              <span class="text-2xl">{{ getActivityIcon(activity.type) }}</span>
            </div>
            <div class="flex-1">
              <p class="text-white">{{ activity.description }}</p>
              <p class="text-gray-400 text-sm">
                {{ formatDate(activity.created_at) }}
              </p>
            </div>
          </div>
        </div>
        <div v-else class="text-center py-8">
          <span class="text-4xl mb-2 block">📋</span>
          <p class="text-gray-400">No recent activity</p>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
defineProps({
  stats: {
    type: Object,
    required: true,
  },
});

// Reactive data
const clearingCache = ref(false);
const currentTime = ref(new Date());
const unreadMessages = ref(0);
const totalViews = ref(0);
const uniqueVisitors = ref(0);
const cvDownloads = ref(0);
const contactForms = ref(0);
const recentActivity = ref([]);

// Computed properties
const currentDateTime = computed(() => {
  return currentTime.value.toLocaleString("id-ID", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
});

const lastBackup = computed(() => {
  // You can implement this based on your backup system
  return "2 hours ago";
});

// Methods
const formatNumber = (num) => {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + "M";
  } else if (num >= 1000) {
    return (num / 1000).toFixed(1) + "K";
  }
  return num.toString();
};

const formatDate = (date) => {
  return new Date(date).toLocaleString("id-ID", {
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
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

const clearCache = async () => {
  clearingCache.value = true;
  try {
    await router.post("/clear-cache");
    // Add success feedback here
  } catch (error) {
    console.error("Failed to clear cache:", error);
    // Add error feedback here
  } finally {
    clearingCache.value = false;
  }
};

const loadAnalytics = async () => {
  try {
    const response = await fetch("/admin/api/quick-stats");
    const data = await response.json();

    unreadMessages.value = data.messages || 0;
    totalViews.value = data.total_views || 0;
    cvDownloads.value = data.cv_downloads || 0;
    // Update other analytics data
  } catch (error) {
    console.error("Failed to load analytics:", error);
  }
};

const updateTime = () => {
  currentTime.value = new Date();
};

// Lifecycle
onMounted(() => {
  // Update time every minute
  setInterval(updateTime, 60000);

  // Load analytics data
  loadAnalytics();

  // Set up periodic analytics refresh (every 5 minutes)
  setInterval(loadAnalytics, 300000);
});
</script>

<style scoped>
/* Add any custom styles if needed */
.transition-all {
  transition: all 0.3s ease;
}
</style>
