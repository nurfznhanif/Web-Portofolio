<!-- Admin/Analytics/Dashboard.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Analytics Dashboard</h1>
          <p class="text-gray-400">
            Track your portfolio performance and visitor insights
          </p>
        </div>
        <div class="flex space-x-3">
          <!-- Date Range Selector -->
          <FormSelect
            v-model="selectedTimeRange"
            :options="timeRangeOptions"
            @update:modelValue="loadAnalytics"
            class="w-40"
          />
          <button
            @click="exportAnalytics"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center"
          >
            <svg
              class="w-4 h-4 mr-2"
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
            Export
          </button>
        </div>
      </div>

      <!-- Key Metrics -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Views -->
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
                {{ formatNumber(analytics.totalViews) }}
              </p>
              <p class="text-gray-400 text-sm">Total Views</p>
              <div class="flex items-center mt-1">
                <span
                  class="text-xs"
                  :class="
                    analytics.viewsChange >= 0
                      ? 'text-green-400'
                      : 'text-red-400'
                  "
                >
                  {{ analytics.viewsChange >= 0 ? "+" : ""
                  }}{{ analytics.viewsChange }}%
                </span>
                <span class="text-xs text-gray-500 ml-1">vs last period</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Unique Visitors -->
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
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">
                {{ formatNumber(analytics.uniqueVisitors) }}
              </p>
              <p class="text-gray-400 text-sm">Unique Visitors</p>
              <div class="flex items-center mt-1">
                <span
                  class="text-xs"
                  :class="
                    analytics.visitorsChange >= 0
                      ? 'text-green-400'
                      : 'text-red-400'
                  "
                >
                  {{ analytics.visitorsChange >= 0 ? "+" : ""
                  }}{{ analytics.visitorsChange }}%
                </span>
                <span class="text-xs text-gray-500 ml-1">vs last period</span>
              </div>
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
                {{ formatNumber(analytics.cvDownloads) }}
              </p>
              <p class="text-gray-400 text-sm">CV Downloads</p>
              <div class="flex items-center mt-1">
                <span
                  class="text-xs"
                  :class="
                    analytics.downloadsChange >= 0
                      ? 'text-green-400'
                      : 'text-red-400'
                  "
                >
                  {{ analytics.downloadsChange >= 0 ? "+" : ""
                  }}{{ analytics.downloadsChange }}%
                </span>
                <span class="text-xs text-gray-500 ml-1">vs last period</span>
              </div>
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
                {{ formatNumber(analytics.contactForms) }}
              </p>
              <p class="text-gray-400 text-sm">Contact Forms</p>
              <div class="flex items-center mt-1">
                <span
                  class="text-xs"
                  :class="
                    analytics.formsChange >= 0
                      ? 'text-green-400'
                      : 'text-red-400'
                  "
                >
                  {{ analytics.formsChange >= 0 ? "+" : ""
                  }}{{ analytics.formsChange }}%
                </span>
                <span class="text-xs text-gray-500 ml-1">vs last period</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Visitors Chart -->
        <div class="bg-gray-800 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">
            Visitors Over Time
          </h3>
          <div class="h-64 flex items-center justify-center">
            <div class="text-gray-400">
              <svg
                class="w-16 h-16 mx-auto mb-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                />
              </svg>
              <p class="text-center">Chart.js integration would go here</p>
            </div>
          </div>
        </div>

        <!-- Top Projects -->
        <div class="bg-gray-800 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">
            Most Viewed Projects
          </h3>
          <div class="space-y-4">
            <div
              v-for="project in analytics.topProjects"
              :key="project.id"
              class="flex items-center justify-between"
            >
              <div class="flex items-center space-x-3">
                <div
                  class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center"
                >
                  💼
                </div>
                <div>
                  <p class="text-white font-medium">{{ project.title }}</p>
                  <p class="text-gray-400 text-sm">{{ project.type }}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-white font-medium">
                  {{ formatNumber(project.views) }}
                </p>
                <p class="text-gray-400 text-sm">views</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Analytics -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Traffic Sources -->
        <div class="bg-gray-800 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Traffic Sources</h3>
          <div class="space-y-3">
            <div
              v-for="source in analytics.trafficSources"
              :key="source.source"
              class="flex items-center justify-between"
            >
              <span class="text-gray-300">{{ source.source }}</span>
              <div class="flex items-center space-x-2">
                <div class="w-16 bg-gray-700 rounded-full h-2">
                  <div
                    class="bg-blue-500 h-2 rounded-full"
                    :style="{ width: source.percentage + '%' }"
                  ></div>
                </div>
                <span class="text-white text-sm">{{ source.percentage }}%</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Device Types -->
        <div class="bg-gray-800 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Device Types</h3>
          <div class="space-y-3">
            <div
              v-for="device in analytics.deviceTypes"
              :key="device.type"
              class="flex items-center justify-between"
            >
              <div class="flex items-center space-x-2">
                <span class="text-lg">{{ getDeviceIcon(device.type) }}</span>
                <span class="text-gray-300">{{ device.type }}</span>
              </div>
              <span class="text-white">{{ device.percentage }}%</span>
            </div>
          </div>
        </div>

        <!-- Geographic Data -->
        <div class="bg-gray-800 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Top Countries</h3>
          <div class="space-y-3">
            <div
              v-for="country in analytics.topCountries"
              :key="country.country"
              class="flex items-center justify-between"
            >
              <div class="flex items-center space-x-3">
                <span class="text-lg">{{ country.flag }}</span>
                <span class="text-gray-300">{{ country.country }}</span>
              </div>
              <span class="text-white">{{ formatNumber(country.visits) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";

const props = defineProps({
  analytics: Object,
});

const selectedTimeRange = ref("7d");

const timeRangeOptions = [
  { value: "24h", label: "Last 24 Hours" },
  { value: "7d", label: "Last 7 Days" },
  { value: "30d", label: "Last 30 Days" },
  { value: "90d", label: "Last 90 Days" },
  { value: "1y", label: "Last Year" },
];

const formatNumber = (num) => {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + "M";
  } else if (num >= 1000) {
    return (num / 1000).toFixed(1) + "K";
  }
  return num.toString();
};

const getDeviceIcon = (type) => {
  const icons = {
    Desktop: "🖥️",
    Mobile: "📱",
    Tablet: "📱",
  };
  return icons[type] || "💻";
};

const loadAnalytics = () => {
  // Reload analytics data with new time range
  router.get(
    route("admin.analytics.dashboard"),
    {
      timeRange: selectedTimeRange.value,
    },
    { preserveState: true }
  );
};

const exportAnalytics = () => {
  window.open(
    route("admin.analytics.export", {
      timeRange: selectedTimeRange.value,
    })
  );
};

onMounted(() => {
  // Initialize any charts here
  console.log("Analytics dashboard mounted");
});
</script>
