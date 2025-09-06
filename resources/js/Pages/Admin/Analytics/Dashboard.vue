<script setup>
import { ref, computed, onMounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  stats: {
    type: Object,
    default: () => ({}),
  },
  dailyViews: {
    type: Array,
    default: () => [],
  },
});

// Reactive state
const selectedTimeRange = ref("30");
const showDetails = ref(false);

// Computed properties
const analyticsStats = computed(() => [
  {
    title: "Total Page Views",
    value: props.stats.total_views || 0,
    icon: "👁️",
    color: "blue",
    description: "All time portfolio visits",
    trend: "+12%",
    trendUp: true,
  },
  {
    title: "Project Views",
    value: props.stats.total_project_views || 0,
    icon: "📁",
    color: "green",
    description: "Individual project clicks",
    trend: "+8%",
    trendUp: true,
  },
  {
    title: "Contact Forms",
    value: props.stats.total_contacts || 0,
    icon: "✉️",
    color: "purple",
    description: "Form submissions received",
    trend: "+25%",
    trendUp: true,
  },
  {
    title: "CV Downloads",
    value: props.stats.total_cv_downloads || 0,
    icon: "📄",
    color: "orange",
    description: "Resume download requests",
    trend: "+15%",
    trendUp: true,
  },
]);

const chartData = computed(() => {
  if (!props.dailyViews.length) return [];

  return props.dailyViews.map((view) => ({
    date: new Date(view.date).toLocaleDateString("en-US", {
      month: "short",
      day: "numeric",
    }),
    views: view.views,
    fullDate: view.date,
  }));
});

const maxViews = computed(() => {
  return Math.max(...chartData.value.map((d) => d.views), 1);
});

const totalViewsInPeriod = computed(() => {
  return chartData.value.reduce((sum, data) => sum + data.views, 0);
});

const averageViewsPerDay = computed(() => {
  return chartData.value.length > 0
    ? Math.round(totalViewsInPeriod.value / chartData.value.length)
    : 0;
});

const topViewDays = computed(() => {
  return [...chartData.value].sort((a, b) => b.views - a.views).slice(0, 3);
});

// Methods
const getStatColorClasses = (color) => {
  const colors = {
    blue: "bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-600",
    green:
      "bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-600",
    purple:
      "bg-purple-50 dark:bg-purple-900/20 border-purple-200 dark:border-purple-800 text-purple-600",
    orange:
      "bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-800 text-orange-600",
  };
  return colors[color] || colors.blue;
};

const getTrendColorClasses = (trendUp) => {
  return trendUp
    ? "text-green-600 dark:text-green-400"
    : "text-red-600 dark:text-red-400";
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Analytics", href: "/admin/analytics", current: true },
];
</script>

<template>
  <Head title="Analytics Dashboard" />

  <DashboardLayout :title="'Analytics Dashboard'" :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
      <!-- Header Section -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
        >
          <div>
            <h1
              class="text-2xl font-bold text-gray-900 dark:text-white flex items-center"
            >
              <svg
                class="w-8 h-8 mr-3 text-indigo-600"
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
              Analytics Overview
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Track your portfolio performance and visitor engagement
            </p>
          </div>

          <div class="flex items-center space-x-3">
            <select
              v-model="selectedTimeRange"
              class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="7">Last 7 days</option>
              <option value="30">Last 30 days</option>
              <option value="90">Last 90 days</option>
            </select>

            <button
              @click="showDetails = !showDetails"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
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
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              {{ showDetails ? "Hide Details" : "Show Details" }}
            </button>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          v-for="stat in analyticsStats"
          :key="stat.title"
          :class="[
            'rounded-lg border p-6 transition-all duration-200 hover:shadow-lg',
            getStatColorClasses(stat.color),
          ]"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium opacity-75">
                {{ stat.title }}
              </p>
              <p class="text-3xl font-bold mt-2">
                {{ stat.value.toLocaleString() }}
              </p>
            </div>
            <div class="text-3xl opacity-75">
              {{ stat.icon }}
            </div>
          </div>

          <div class="mt-4 flex items-center justify-between">
            <p class="text-xs opacity-75">
              {{ stat.description }}
            </p>
            <div
              :class="[
                'flex items-center text-sm font-medium',
                getTrendColorClasses(stat.trendUp),
              ]"
            >
              <svg
                class="w-4 h-4 mr-1"
                :class="{ 'rotate-180': !stat.trendUp }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 11l5-5m0 0l5 5m-5-5v12"
                />
              </svg>
              {{ stat.trend }}
            </div>
          </div>
        </div>
      </div>

      <!-- Charts and Analytics -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Page Views Chart -->
        <div
          class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Page Views Trend
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                Daily visits over the last 30 days
              </p>
            </div>
            <div class="text-right">
              <p class="text-2xl font-bold text-indigo-600">
                {{ totalViewsInPeriod.toLocaleString() }}
              </p>
              <p class="text-xs text-gray-500">total views</p>
            </div>
          </div>

          <!-- Chart -->
          <div v-if="chartData.length > 0" class="space-y-4">
            <div
              class="flex items-end space-x-1 h-48 bg-gray-50 dark:bg-gray-700/20 rounded-lg p-4"
            >
              <div
                v-for="(data, index) in chartData"
                :key="index"
                class="flex-1 bg-gradient-to-t from-indigo-500 to-indigo-400 rounded-t min-h-[4px] relative group cursor-pointer transition-all duration-200 hover:from-indigo-600 hover:to-indigo-500"
                :style="{
                  height: Math.max((data.views / maxViews) * 180, 4) + 'px',
                }"
              >
                <!-- Tooltip -->
                <div
                  class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10"
                >
                  <div class="font-medium">{{ data.views }} views</div>
                  <div class="text-gray-300">
                    {{ formatDate(data.fullDate) }}
                  </div>
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

          <!-- Empty State -->
          <div v-else class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">📊</div>
            <p class="text-gray-500 dark:text-gray-400 text-lg">
              No data available
            </p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">
              Visit your portfolio to start tracking analytics
            </p>
          </div>
        </div>

        <!-- Quick Stats & Insights -->
        <div class="space-y-6">
          <!-- Quick Stats -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h3
              class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
            >
              Quick Stats
            </h3>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                  Average daily views
                </span>
                <span class="font-semibold text-gray-900 dark:text-white">
                  {{ averageViewsPerDay }}
                </span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                  Peak day views
                </span>
                <span class="font-semibold text-gray-900 dark:text-white">
                  {{ topViewDays[0]?.views || 0 }}
                </span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                  Conversion rate
                </span>
                <span class="font-semibold text-green-600">
                  {{
                    totalViewsInPeriod > 0
                      ? (
                          (props.stats.total_contacts / totalViewsInPeriod) *
                          100
                        ).toFixed(1)
                      : 0
                  }}%
                </span>
              </div>
            </div>
          </div>

          <!-- Top Performance Days -->
          <div
            v-if="topViewDays.length > 0"
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h3
              class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
            >
              Top Performance Days
            </h3>
            <div class="space-y-3">
              <div
                v-for="(day, index) in topViewDays"
                :key="day.fullDate"
                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/20 rounded-lg"
              >
                <div>
                  <div class="flex items-center space-x-2">
                    <span
                      :class="[
                        'w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white',
                        index === 0
                          ? 'bg-yellow-500'
                          : index === 1
                          ? 'bg-gray-400'
                          : 'bg-orange-400',
                      ]"
                    >
                      {{ index + 1 }}
                    </span>
                    <span
                      class="text-sm font-medium text-gray-900 dark:text-white"
                    >
                      {{ day.date }}
                    </span>
                  </div>
                  <p class="text-xs text-gray-600 dark:text-gray-400 ml-8">
                    {{ formatDate(day.fullDate) }}
                  </p>
                </div>
                <span class="font-semibold text-indigo-600">
                  {{ day.views }} views
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Detailed Analytics Navigation -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          Detailed Analytics
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <Link
            href="/admin/analytics/visitors"
            class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-200"
          >
            <div class="flex-shrink-0">
              <svg
                class="w-8 h-8 text-blue-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                />
              </svg>
            </div>
            <div class="ml-4">
              <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                Visitor Analytics
              </h4>
              <p class="text-xs text-gray-600 dark:text-gray-400">
                Track unique visitors and their behavior
              </p>
            </div>
          </Link>

          <Link
            href="/admin/analytics/projects"
            class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-200"
          >
            <div class="flex-shrink-0">
              <svg
                class="w-8 h-8 text-green-600"
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
              <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                Project Performance
              </h4>
              <p class="text-xs text-gray-600 dark:text-gray-400">
                See which projects get the most attention
              </p>
            </div>
          </Link>

          <Link
            href="/admin/analytics/contact-forms"
            class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-200"
          >
            <div class="flex-shrink-0">
              <svg
                class="w-8 h-8 text-purple-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                />
              </svg>
            </div>
            <div class="ml-4">
              <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                Contact Forms
              </h4>
              <p class="text-xs text-gray-600 dark:text-gray-400">
                Review messages and inquiries
              </p>
            </div>
          </Link>
        </div>
      </div>

      <!-- Additional Details (Expandable) -->
      <div
        v-if="showDetails"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          Detailed Breakdown
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Event Types -->
          <div>
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
              Event Types
            </h4>
            <div class="space-y-2">
              <div
                class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/20 rounded"
              >
                <span class="text-sm text-gray-600 dark:text-gray-400"
                  >Page Views</span
                >
                <span class="font-medium text-gray-900 dark:text-white">
                  {{ props.stats.total_views || 0 }}
                </span>
              </div>
              <div
                class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/20 rounded"
              >
                <span class="text-sm text-gray-600 dark:text-gray-400"
                  >Project Views</span
                >
                <span class="font-medium text-gray-900 dark:text-white">
                  {{ props.stats.total_project_views || 0 }}
                </span>
              </div>
              <div
                class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/20 rounded"
              >
                <span class="text-sm text-gray-600 dark:text-gray-400"
                  >Contact Forms</span
                >
                <span class="font-medium text-gray-900 dark:text-white">
                  {{ props.stats.total_contacts || 0 }}
                </span>
              </div>
              <div
                class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/20 rounded"
              >
                <span class="text-sm text-gray-600 dark:text-gray-400"
                  >CV Downloads</span
                >
                <span class="font-medium text-gray-900 dark:text-white">
                  {{ props.stats.total_cv_downloads || 0 }}
                </span>
              </div>
            </div>
          </div>

          <!-- Performance Metrics -->
          <div>
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
              Performance Metrics
            </h4>
            <div class="space-y-2">
              <div
                class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/20 rounded"
              >
                <span class="text-sm text-gray-600 dark:text-gray-400"
                  >Contact Rate</span
                >
                <span class="font-medium text-green-600">
                  {{
                    props.stats.total_views > 0
                      ? (
                          (props.stats.total_contacts /
                            props.stats.total_views) *
                          100
                        ).toFixed(2)
                      : 0
                  }}%
                </span>
              </div>
              <div
                class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/20 rounded"
              >
                <span class="text-sm text-gray-600 dark:text-gray-400"
                  >CV Download Rate</span
                >
                <span class="font-medium text-blue-600">
                  {{
                    props.stats.total_views > 0
                      ? (
                          (props.stats.total_cv_downloads /
                            props.stats.total_views) *
                          100
                        ).toFixed(2)
                      : 0
                  }}%
                </span>
              </div>
              <div
                class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/20 rounded"
              >
                <span class="text-sm text-gray-600 dark:text-gray-400"
                  >Project Engagement</span
                >
                <span class="font-medium text-purple-600">
                  {{
                    props.stats.total_views > 0
                      ? (
                          (props.stats.total_project_views /
                            props.stats.total_views) *
                          100
                        ).toFixed(2)
                      : 0
                  }}%
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
