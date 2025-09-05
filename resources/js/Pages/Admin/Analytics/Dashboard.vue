<!-- resources/js/Pages/Admin/Analytics/Dashboard.vue -->
<template>
  <DashboardLayout>
    <div class="p-6 space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-white">Analytics Dashboard</h1>
          <p class="text-gray-400">
            Track your portfolio performance and visitor insights
          </p>
        </div>

        <!-- Date Range Selector -->
        <div class="flex items-center space-x-4">
          <select
            v-model="selectedDays"
            @change="updateDateRange"
            class="bg-gray-800 border border-gray-600 text-white rounded-lg px-3 py-2 text-sm"
          >
            <option value="7">Last 7 days</option>
            <option value="30">Last 30 days</option>
            <option value="90">Last 90 days</option>
            <option value="365">Last year</option>
          </select>

          <button
            @click="refreshData"
            :disabled="isRefreshing"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors disabled:opacity-50 text-sm"
          >
            <svg
              :class="{ 'animate-spin': isRefreshing }"
              class="w-4 h-4 mr-2 inline"
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
            {{ isRefreshing ? "Refreshing..." : "Refresh" }}
          </button>
        </div>
      </div>

      <!-- Overview Stats -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Visitors -->
        <div class="bg-gray-800 rounded-lg p-6">
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
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">
                {{ formatNumber(safeOverview.total_visitors) }}
              </p>
              <p class="text-gray-400 text-sm">Total Visitors</p>
            </div>
          </div>
        </div>

        <!-- Page Views -->
        <div class="bg-gray-800 rounded-lg p-6">
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
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">
                {{ formatNumber(safeOverview.total_page_views) }}
              </p>
              <p class="text-gray-400 text-sm">Page Views</p>
            </div>
          </div>
        </div>

        <!-- Contact Forms -->
        <div class="bg-gray-800 rounded-lg p-6">
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
              <p class="text-2xl font-bold text-white">
                {{ formatNumber(safeOverview.total_contacts) }}
              </p>
              <p class="text-gray-400 text-sm">Contact Forms</p>
            </div>
          </div>
        </div>

        <!-- CV Downloads -->
        <div class="bg-gray-800 rounded-lg p-6">
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
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">
                {{ formatNumber(safeOverview.total_downloads) }}
              </p>
              <p class="text-gray-400 text-sm">CV Downloads</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Views Chart -->
        <div class="bg-gray-800 rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Daily Views</h3>
            <div class="text-sm text-gray-400">{{ selectedDays }} days</div>
          </div>

          <div class="h-64 flex items-center justify-center">
            <div v-if="safeChartData.daily_views" class="text-center">
              <div class="text-3xl font-bold text-blue-400 mb-2">
                {{ getTotalFromChart(safeChartData.daily_views) }}
              </div>
              <div class="text-gray-400 text-sm">Total Views</div>
              <!-- Placeholder for actual chart -->
              <div class="mt-4 text-xs text-gray-500">
                Chart visualization would go here
              </div>
            </div>
            <div v-else class="text-gray-400">No chart data available</div>
          </div>
        </div>

        <!-- Device Breakdown -->
        <div class="bg-gray-800 rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Device Breakdown</h3>
          </div>

          <div class="space-y-4">
            <div
              v-if="
                safeChartData.device_breakdown &&
                safeChartData.device_breakdown.labels
              "
              v-for="(label, index) in safeChartData.device_breakdown.labels"
              :key="label"
              class="flex items-center justify-between"
            >
              <div class="flex items-center">
                <div
                  :class="getDeviceColor(label)"
                  class="w-3 h-3 rounded-full mr-3"
                ></div>
                <span class="text-white text-sm">{{ label }}</span>
              </div>
              <span class="text-gray-400 text-sm">
                {{
                  safeChartData.device_breakdown.datasets[0]?.data[index] || 0
                }}
              </span>
            </div>

            <div
              v-if="
                !safeChartData.device_breakdown ||
                !safeChartData.device_breakdown.labels
              "
              class="text-center text-gray-400 py-8"
            >
              No device data available
            </div>
          </div>
        </div>
      </div>

      <!-- Top Content -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Pages -->
        <div class="bg-gray-800 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Top Pages</h3>

          <div class="space-y-3">
            <div
              v-if="
                safeTopContent.top_pages && safeTopContent.top_pages.length > 0
              "
              v-for="page in safeTopContent.top_pages.slice(0, 5)"
              :key="page.page"
              class="flex items-center justify-between p-3 bg-gray-700 rounded-lg"
            >
              <div>
                <div class="text-white font-medium">
                  {{ page.page || "Unknown" }}
                </div>
                <div class="text-gray-400 text-sm">
                  {{ page.views || 0 }} views
                </div>
              </div>
              <div class="text-blue-400 font-semibold">
                {{ formatNumber(page.views || 0) }}
              </div>
            </div>

            <div
              v-if="
                !safeTopContent.top_pages ||
                safeTopContent.top_pages.length === 0
              "
              class="text-center text-gray-400 py-8"
            >
              No page data available
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-gray-800 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Recent Activity</h3>

          <div class="space-y-3">
            <div
              v-if="safeRecentActivity && safeRecentActivity.length > 0"
              v-for="activity in safeRecentActivity.slice(0, 5)"
              :key="activity.created_at"
              class="flex items-start space-x-3 p-3 bg-gray-700 rounded-lg"
            >
              <div
                :class="[
                  'flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm',
                  getActivityColor(activity.event_type),
                ]"
              >
                {{ getActivityIcon(activity.event_type) }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white text-sm">
                  {{ formatEventType(activity.event_type) }}
                </p>
                <p class="text-gray-400 text-xs">
                  {{ activity.page || "Unknown page" }}
                </p>
                <p class="text-gray-500 text-xs">
                  {{ formatDateTime(activity.created_at) }}
                </p>
              </div>
            </div>

            <div
              v-if="!safeRecentActivity || safeRecentActivity.length === 0"
              class="text-center text-gray-400 py-8"
            >
              No recent activity
            </div>
          </div>
        </div>
      </div>

      <!-- Error Message -->
      <div
        v-if="error"
        class="bg-yellow-900 border border-yellow-600 rounded-lg p-4"
      >
        <div class="flex items-center">
          <svg
            class="w-5 h-5 text-yellow-400 mr-3"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.856-.833-2.828 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
            />
          </svg>
          <span class="text-yellow-300">{{ error }}</span>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps({
  overview: {
    type: Object,
    default: () => ({}),
  },
  chartData: {
    type: Object,
    default: () => ({}),
  },
  topContent: {
    type: Object,
    default: () => ({}),
  },
  recentActivity: {
    type: Array,
    default: () => [],
  },
  dateRange: {
    type: Object,
    default: () => ({ days: 30 }),
  },
  error: {
    type: String,
    default: null,
  },
});

// Reactive data
const selectedDays = ref(props.dateRange?.days || 30);
const isRefreshing = ref(false);

// Safe computed properties with fallbacks
const safeOverview = computed(() => ({
  total_visitors: props.overview?.total_visitors || 0,
  total_page_views: props.overview?.total_page_views || 0,
  total_contacts: props.overview?.total_contacts || 0,
  total_downloads: props.overview?.total_downloads || 0,
}));

const safeChartData = computed(() => ({
  daily_views: props.chartData?.daily_views || null,
  device_breakdown: props.chartData?.device_breakdown || null,
  hourly_pattern: props.chartData?.hourly_pattern || null,
  geographic_distribution: props.chartData?.geographic_distribution || null,
}));

const safeTopContent = computed(() => ({
  top_pages: props.topContent?.top_pages || [],
  top_projects: props.topContent?.top_projects || [],
}));

const safeRecentActivity = computed(() => {
  return props.recentActivity || [];
});

// Methods
const formatNumber = (num) => {
  return new Intl.NumberFormat().format(num || 0);
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

const formatEventType = (eventType) => {
  const types = {
    page_view: "Page View",
    project_view: "Project View",
    contact_form: "Contact Form",
    cv_download: "CV Download",
    search: "Search",
  };
  return types[eventType] || eventType || "Unknown Event";
};

const getActivityIcon = (eventType) => {
  const icons = {
    page_view: "👁️",
    project_view: "🔍",
    contact_form: "📧",
    cv_download: "📄",
    search: "🔎",
  };
  return icons[eventType] || "📊";
};

const getActivityColor = (eventType) => {
  const colors = {
    page_view: "bg-blue-600",
    project_view: "bg-green-600",
    contact_form: "bg-yellow-600",
    cv_download: "bg-purple-600",
    search: "bg-indigo-600",
  };
  return colors[eventType] || "bg-gray-600";
};

const getDeviceColor = (device) => {
  const colors = {
    Desktop: "bg-blue-500",
    Mobile: "bg-green-500",
    Tablet: "bg-yellow-500",
  };
  return colors[device] || "bg-gray-500";
};

const getTotalFromChart = (chartData) => {
  try {
    if (
      chartData &&
      chartData.datasets &&
      chartData.datasets[0] &&
      chartData.datasets[0].data
    ) {
      return chartData.datasets[0].data.reduce(
        (sum, value) => sum + (value || 0),
        0
      );
    }
    return 0;
  } catch (error) {
    return 0;
  }
};

const updateDateRange = () => {
  isRefreshing.value = true;
  router.get(
    route("admin.analytics.index"),
    {
      days: selectedDays.value,
    },
    {
      preserveState: true,
      onFinish: () => {
        isRefreshing.value = false;
      },
    }
  );
};

const refreshData = () => {
  isRefreshing.value = true;
  router.reload({
    onFinish: () => {
      isRefreshing.value = false;
    },
  });
};

// Make route function available (using our custom route helper)
const route = (name, params = null) => {
  try {
    return window.route ? window.route(name, params) : "#";
  } catch (error) {
    return "#";
  }
};

onMounted(() => {
  console.log("Analytics Dashboard mounted with props:", {
    overview: props.overview,
    chartData: props.chartData,
    topContent: props.topContent,
    recentActivity: props.recentActivity,
  });
});
</script>
