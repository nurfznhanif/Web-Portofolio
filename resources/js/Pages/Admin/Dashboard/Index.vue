<script setup>
import { ref, computed, onMounted } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

// Props from controller
const props = defineProps({
  stats: {
    type: Object,
    default: () => ({}),
  },
  recentViews: {
    type: Array,
    default: () => [],
  },
  projectViews: {
    type: Number,
    default: 0,
  },
  contactForms: {
    type: Number,
    default: 0,
  },
});

// Reactive data
const currentTime = ref(new Date());
const greetingMessage = ref("");

// Computed properties
const statsCards = computed(() => [
  {
    title: "Total Projects",
    value: props.stats.total_projects || 0,
    icon: "📁",
    color: "blue",
    link: "/admin/portfolio",
    description: "Portfolio projects",
  },
  {
    title: "Experiences",
    value: props.stats.total_experiences || 0,
    icon: "💼",
    color: "green",
    link: "/admin/experience",
    description: "Work experiences",
  },
  {
    title: "Skills",
    value: props.stats.total_skills || 0,
    icon: "⚡",
    color: "purple",
    link: "/admin/skills",
    description: "Technical skills",
  },
  {
    title: "Page Views",
    value: props.stats.total_page_views || 0,
    icon: "👁️",
    color: "orange",
    link: "/admin/analytics",
    description: "Total portfolio views",
  },
]);

const recentActivity = computed(() => [
  {
    type: "project_views",
    count: props.projectViews,
    label: "Project Views",
    icon: "📊",
    color: "text-blue-400",
    timeframe: "Last 30 days",
  },
  {
    type: "contact_forms",
    count: props.contactForms,
    label: "Contact Forms",
    icon: "✉️",
    color: "text-green-400",
    timeframe: "Last 30 days",
  },
  {
    type: "portfolio_visits",
    count: props.recentViews.reduce((sum, view) => sum + view.views, 0),
    label: "Total Visits",
    icon: "🌐",
    color: "text-purple-400",
    timeframe: "Last 30 days",
  },
]);

const chartData = computed(() => {
  if (!props.recentViews.length) return [];

  return props.recentViews.map((view) => ({
    date: new Date(view.date).toLocaleDateString("en-US", {
      month: "short",
      day: "numeric",
    }),
    views: view.views,
  }));
});

const quickActions = computed(() => [
  {
    title: "Add New Project",
    description: "Create a new portfolio project",
    icon: "➕",
    color: "blue",
    link: "/admin/portfolio/create",
  },
  {
    title: "Add Experience",
    description: "Add work experience",
    icon: "💼",
    color: "green",
    link: "/admin/experience/create",
  },
  {
    title: "Add Skill",
    description: "Add new technical skill",
    icon: "⚡",
    color: "purple",
    link: "/admin/skills/create",
  },
  {
    title: "View Analytics",
    description: "Check portfolio performance",
    icon: "📈",
    color: "orange",
    link: "/admin/analytics",
  },
  {
    title: "Profile Settings",
    description: "Update profile information",
    icon: "⚙️",
    color: "gray",
    link: "/admin/profile-settings",
  },
  {
    title: "Visit Portfolio",
    description: "View public portfolio",
    icon: "🌐",
    color: "indigo",
    link: "/",
    external: true,
  },
]);

// Methods
const getGreeting = () => {
  const hour = currentTime.value.getHours();
  if (hour < 12) return "Good Morning";
  if (hour < 17) return "Good Afternoon";
  return "Good Evening";
};

const updateTime = () => {
  currentTime.value = new Date();
  greetingMessage.value = getGreeting();
};

const getColorClasses = (color) => {
  const colors = {
    blue: "from-blue-500 to-blue-600 border-blue-500/30",
    green: "from-green-500 to-green-600 border-green-500/30",
    purple: "from-purple-500 to-purple-600 border-purple-500/30",
    orange: "from-orange-500 to-orange-600 border-orange-500/30",
    gray: "from-gray-500 to-gray-600 border-gray-500/30",
    indigo: "from-indigo-500 to-indigo-600 border-indigo-500/30",
  };
  return colors[color] || colors.blue;
};

const formatNumber = (num) => {
  if (num >= 1000000) return (num / 1000000).toFixed(1) + "M";
  if (num >= 1000) return (num / 1000).toFixed(1) + "K";
  return num.toString();
};

// Lifecycle
onMounted(() => {
  updateTime();
  // Update time every minute
  setInterval(updateTime, 60000);
});
</script>

<template>
  <DashboardLayout
    title="Dashboard Overview"
    :breadcrumbs="[{ name: 'Dashboard' }]"
  >
    <Head title="Dashboard" />

    <div class="p-6 space-y-6">
      <!-- Welcome Section -->
      <div
        class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white"
      >
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold mb-2">{{ greetingMessage }}! 👋</h2>
            <p class="text-blue-100">
              Welcome back to your portfolio admin panel. Here's your overview.
            </p>
          </div>
          <div class="text-right text-blue-100">
            <p class="text-sm">{{ currentTime.toLocaleDateString() }}</p>
            <p class="text-sm">{{ currentTime.toLocaleTimeString() }}</p>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          v-for="stat in statsCards"
          :key="stat.title"
          class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 group"
        >
          <Link
            :href="stat.link"
            class="block p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
          >
            <div class="flex items-center justify-between">
              <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                  <span class="text-2xl">{{ stat.icon }}</span>
                  <h3
                    class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide"
                  >
                    {{ stat.title }}
                  </h3>
                </div>
                <p
                  class="text-3xl font-bold text-gray-900 dark:text-white mb-1"
                >
                  {{ formatNumber(stat.value) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ stat.description }}
                </p>
              </div>
              <div
                class="ml-4 opacity-0 group-hover:opacity-100 transition-opacity"
              >
                <svg
                  class="w-5 h-5 text-gray-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  ></path>
                </svg>
              </div>
            </div>
          </Link>
        </div>
      </div>

      <!-- Charts and Recent Activity -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Views Chart -->
        <div
          class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700"
        >
          <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3
              class="text-lg font-semibold text-gray-900 dark:text-white flex items-center"
            >
              <span class="mr-2">📈</span>
              Portfolio Views (Last 30 Days)
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Daily page views on your portfolio
            </p>
          </div>
          <div class="p-6">
            <div v-if="chartData.length > 0" class="space-y-4">
              <!-- Simple Bar Chart -->
              <div class="flex items-end space-x-1 h-48">
                <div
                  v-for="(data, index) in chartData"
                  :key="index"
                  class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t min-h-[4px] relative group"
                  :style="{
                    height:
                      Math.max(
                        (data.views /
                          Math.max(...chartData.map((d) => d.views))) *
                          180,
                        4
                      ) + 'px',
                  }"
                >
                  <!-- Tooltip -->
                  <div
                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap"
                  >
                    {{ data.date }}: {{ data.views }} views
                  </div>
                </div>
              </div>

              <!-- Chart Labels -->
              <div
                class="flex justify-between text-xs text-gray-600 dark:text-gray-400"
              >
                <span>{{ chartData[0]?.date }}</span>
                <span>{{
                  chartData[Math.floor(chartData.length / 2)]?.date
                }}</span>
                <span>{{ chartData[chartData.length - 1]?.date }}</span>
              </div>
            </div>
            <div v-else class="text-center py-12">
              <div class="text-gray-400 text-4xl mb-4">📊</div>
              <p class="text-gray-500 dark:text-gray-400">No data available</p>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div
          class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700"
        >
          <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3
              class="text-lg font-semibold text-gray-900 dark:text-white flex items-center"
            >
              <span class="mr-2">⚡</span>
              Recent Activity
            </h3>
          </div>
          <div class="p-6 space-y-4">
            <div
              v-for="activity in recentActivity"
              :key="activity.type"
              class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg"
            >
              <div class="flex items-center space-x-3">
                <span class="text-xl">{{ activity.icon }}</span>
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">
                    {{ activity.label }}
                  </p>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ activity.timeframe }}
                  </p>
                </div>
              </div>
              <div class="text-right">
                <p :class="activity.color" class="text-xl font-bold">
                  {{ formatNumber(activity.count) }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div
        class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700"
      >
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h3
            class="text-lg font-semibold text-gray-900 dark:text-white flex items-center"
          >
            <span class="mr-2">🚀</span>
            Quick Actions
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Frequently used actions and shortcuts
          </p>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <component
              :is="action.external ? 'a' : Link"
              v-for="action in quickActions"
              :key="action.title"
              :href="action.link"
              :target="action.external ? '_blank' : undefined"
              :rel="action.external ? 'noopener noreferrer' : undefined"
              class="group p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-solid hover:shadow-md transition-all duration-300 cursor-pointer"
              :class="`hover:border-${action.color}-500 hover:bg-${action.color}-50 dark:hover:bg-${action.color}-900/20`"
            >
              <div class="flex items-start space-x-3">
                <div
                  class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center text-lg transition-transform group-hover:scale-110"
                  :class="`bg-gradient-to-br ${getColorClasses(
                    action.color
                  )} border`"
                >
                  {{ action.icon }}
                </div>
                <div class="flex-1 min-w-0">
                  <h4
                    class="font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                  >
                    {{ action.title }}
                  </h4>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ action.description }}
                  </p>
                </div>
                <div
                  class="opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <svg
                    v-if="action.external"
                    class="w-4 h-4 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                    ></path>
                  </svg>
                  <svg
                    v-else
                    class="w-4 h-4 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"
                    ></path>
                  </svg>
                </div>
              </div>
            </component>
          </div>
        </div>
      </div>

      <!-- System Status -->
      <div
        class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700"
      >
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h3
            class="text-lg font-semibold text-gray-900 dark:text-white flex items-center"
          >
            <span class="mr-2">💾</span>
            System Information
          </h3>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
              <div
                class="w-12 h-12 mx-auto mb-3 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center"
              >
                <span class="text-green-600 dark:text-green-400 text-xl"
                  >✅</span
                >
              </div>
              <h4 class="font-medium text-gray-900 dark:text-white mb-1">
                System Status
              </h4>
              <p class="text-sm text-green-600 dark:text-green-400">
                All systems operational
              </p>
            </div>
            <div class="text-center">
              <div
                class="w-12 h-12 mx-auto mb-3 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center"
              >
                <span class="text-blue-600 dark:text-blue-400 text-xl">🔄</span>
              </div>
              <h4 class="font-medium text-gray-900 dark:text-white mb-1">
                Last Backup
              </h4>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ currentTime.toLocaleDateString() }}
              </p>
            </div>
            <div class="text-center">
              <div
                class="w-12 h-12 mx-auto mb-3 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center"
              >
                <span class="text-purple-600 dark:text-purple-400 text-xl"
                  >🛡️</span
                >
              </div>
              <h4 class="font-medium text-gray-900 dark:text-white mb-1">
                Security
              </h4>
              <p class="text-sm text-green-600 dark:text-green-400">
                Protected
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
