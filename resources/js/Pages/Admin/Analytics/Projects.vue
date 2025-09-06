<script setup>
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  projectViews: {
    type: Array,
    default: () => [],
  },
});

// Reactive state
const sortBy = ref("views");
const sortOrder = ref("desc");
const viewMode = ref("chart");

// Computed properties
const sortedProjects = computed(() => {
  let sorted = [...props.projectViews];

  sorted.sort((a, b) => {
    let aVal = a[sortBy.value];
    let bVal = b[sortBy.value];

    if (sortBy.value === "views") {
      aVal = parseInt(aVal);
      bVal = parseInt(bVal);
    }

    if (sortOrder.value === "asc") {
      return aVal > bVal ? 1 : -1;
    } else {
      return aVal < bVal ? 1 : -1;
    }
  });

  return sorted;
});

const totalViews = computed(() => {
  return props.projectViews.reduce(
    (sum, project) => sum + parseInt(project.views),
    0
  );
});

const mostPopularProject = computed(() => {
  return props.projectViews.length > 0 ? props.projectViews[0] : null;
});

const averageViews = computed(() => {
  return props.projectViews.length > 0
    ? Math.round(totalViews.value / props.projectViews.length)
    : 0;
});

const maxViews = computed(() => {
  return Math.max(...props.projectViews.map((p) => parseInt(p.views)), 1);
});

// Methods
const getProjectColorClass = (index) => {
  const colors = [
    "bg-blue-500",
    "bg-green-500",
    "bg-purple-500",
    "bg-orange-500",
    "bg-red-500",
    "bg-indigo-500",
    "bg-pink-500",
    "bg-yellow-500",
  ];
  return colors[index % colors.length];
};

const getProjectBorderColor = (index) => {
  const colors = [
    "border-blue-500",
    "border-green-500",
    "border-purple-500",
    "border-orange-500",
    "border-red-500",
    "border-indigo-500",
    "border-pink-500",
    "border-yellow-500",
  ];
  return colors[index % colors.length];
};

const getProjectTextColor = (index) => {
  const colors = [
    "text-blue-600",
    "text-green-600",
    "text-purple-600",
    "text-orange-600",
    "text-red-600",
    "text-indigo-600",
    "text-pink-600",
    "text-yellow-600",
  ];
  return colors[index % colors.length];
};

const getPercentage = (views) => {
  return totalViews.value > 0
    ? ((views / totalViews.value) * 100).toFixed(1)
    : 0;
};

const setSortBy = (column) => {
  if (sortBy.value === column) {
    sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
  } else {
    sortBy.value = column;
    sortOrder.value = "desc";
  }
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Analytics", href: "/admin/analytics" },
  { name: "Projects", href: "/admin/analytics/projects", current: true },
];
</script>

<template>
  <Head title="Project Analytics" />

  <DashboardLayout :title="'Project Analytics'" :breadcrumbs="breadcrumbs">
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
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                />
              </svg>
              Project Performance
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Analyze which projects get the most attention from visitors
            </p>
          </div>

          <div class="flex items-center space-x-3">
            <!-- View Mode Toggle -->
            <div
              class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg"
            >
              <button
                @click="viewMode = 'chart'"
                :class="[
                  'px-3 py-2 text-sm font-medium transition-colors rounded-l-lg',
                  viewMode === 'chart'
                    ? 'bg-indigo-600 text-white'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700',
                ]"
              >
                <svg
                  class="h-4 w-4"
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
              </button>
              <button
                @click="viewMode = 'table'"
                :class="[
                  'px-3 py-2 text-sm font-medium transition-colors rounded-r-lg',
                  viewMode === 'table'
                    ? 'bg-indigo-600 text-white'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700',
                ]"
              >
                <svg
                  class="h-4 w-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 10h16M4 14h16M4 18h16"
                  />
                </svg>
              </button>
            </div>

            <Link
              href="/admin/analytics"
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
                  d="M10 19l-7-7m0 0l7-7m-7 7h18"
                />
              </svg>
              Back to Analytics
            </Link>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
          <div
            class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg
                  class="h-6 w-6 text-blue-600"
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
              <div class="ml-3">
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                  Total Projects
                </p>
                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                  {{ projectViews.length }}
                </p>
              </div>
            </div>
          </div>

          <div
            class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg
                  class="h-6 w-6 text-green-600"
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
              <div class="ml-3">
                <p
                  class="text-sm font-medium text-green-600 dark:text-green-400"
                >
                  Total Views
                </p>
                <p
                  class="text-2xl font-bold text-green-900 dark:text-green-100"
                >
                  {{ totalViews.toLocaleString() }}
                </p>
              </div>
            </div>
          </div>

          <div
            class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg
                  class="h-6 w-6 text-purple-600"
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
              </div>
              <div class="ml-3">
                <p
                  class="text-sm font-medium text-purple-600 dark:text-purple-400"
                >
                  Average Views
                </p>
                <p
                  class="text-2xl font-bold text-purple-900 dark:text-purple-100"
                >
                  {{ averageViews }}
                </p>
              </div>
            </div>
          </div>

          <div
            class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg
                  class="h-6 w-6 text-orange-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <p
                  class="text-sm font-medium text-orange-600 dark:text-orange-400"
                >
                  Most Popular
                </p>
                <p
                  class="text-2xl font-bold text-orange-900 dark:text-orange-100"
                >
                  {{ mostPopularProject?.views || 0 }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-if="projectViews.length === 0"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-12 text-center"
      >
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
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
          No project data available
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Project views will appear here once visitors start interacting with
          your portfolio projects.
        </p>
        <div class="mt-6">
          <Link
            href="/admin/portfolio"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors duration-200"
          >
            <svg
              class="w-5 h-5 mr-2"
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
            Manage Projects
          </Link>
        </div>
      </div>

      <!-- Chart View -->
      <div
        v-else-if="viewMode === 'chart'"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              Project Performance Chart
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Visual breakdown of project views
            </p>
          </div>
          <div class="text-right">
            <p class="text-2xl font-bold text-indigo-600">
              {{ totalViews.toLocaleString() }}
            </p>
            <p class="text-xs text-gray-500">total views</p>
          </div>
        </div>

        <!-- Horizontal Bar Chart -->
        <div class="space-y-4">
          <div
            v-for="(project, index) in sortedProjects"
            :key="project.project_title"
            class="relative"
          >
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center space-x-3">
                <div
                  :class="['w-4 h-4 rounded-full', getProjectColorClass(index)]"
                ></div>
                <h4
                  class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-xs"
                >
                  {{ project.project_title || "Unnamed Project" }}
                </h4>
              </div>
              <div class="flex items-center space-x-4 text-sm">
                <span class="text-gray-500 dark:text-gray-400">
                  {{ getPercentage(project.views) }}%
                </span>
                <span class="font-semibold text-gray-900 dark:text-white">
                  {{ project.views }} views
                </span>
              </div>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
              <div
                :class="[
                  'h-3 rounded-full transition-all duration-500 ease-out',
                  getProjectColorClass(index),
                ]"
                :style="{ width: getPercentage(project.views) + '%' }"
              ></div>
            </div>
          </div>
        </div>

        <!-- Top Performer Highlight -->
        <div
          v-if="mostPopularProject"
          class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800"
        >
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
              <svg
                class="h-8 w-8 text-indigo-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                />
              </svg>
            </div>
            <div>
              <h4
                class="text-sm font-medium text-indigo-900 dark:text-indigo-100"
              >
                Top Performer
              </h4>
              <p class="text-lg font-bold text-indigo-600">
                {{ mostPopularProject.project_title || "Unnamed Project" }}
              </p>
              <p class="text-sm text-indigo-700 dark:text-indigo-300">
                {{ mostPopularProject.views }} views ({{
                  getPercentage(mostPopularProject.views)
                }}% of total)
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Table View -->
      <div
        v-else
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
      >
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Project Performance Table
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                Detailed breakdown of project views and engagement
              </p>
            </div>
            <div class="flex items-center space-x-2">
              <label
                class="text-sm font-medium text-gray-700 dark:text-gray-300"
              >
                Sort by:
              </label>
              <select
                v-model="sortBy"
                @change="setSortBy(sortBy)"
                class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              >
                <option value="views">Views</option>
                <option value="project_title">Project Name</option>
              </select>
            </div>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table
            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
          >
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Rank
                </th>
                <th
                  @click="setSortBy('project_title')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                >
                  <div class="flex items-center space-x-1">
                    <span>Project Name</span>
                    <svg
                      v-if="sortBy === 'project_title'"
                      class="h-4 w-4"
                      :class="{ 'rotate-180': sortOrder === 'desc' }"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  @click="setSortBy('views')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                >
                  <div class="flex items-center space-x-1">
                    <span>Views</span>
                    <svg
                      v-if="sortBy === 'views'"
                      class="h-4 w-4"
                      :class="{ 'rotate-180': sortOrder === 'desc' }"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Percentage
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Performance
                </th>
              </tr>
            </thead>
            <tbody
              class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
            >
              <tr
                v-for="(project, index) in sortedProjects"
                :key="project.project_title"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <span
                      :class="[
                        'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white',
                        index === 0
                          ? 'bg-yellow-500'
                          : index === 1
                          ? 'bg-gray-400'
                          : index === 2
                          ? 'bg-orange-400'
                          : 'bg-gray-300 text-gray-700',
                      ]"
                    >
                      {{ index + 1 }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div
                      :class="[
                        'w-4 h-4 rounded-full mr-3',
                        getProjectColorClass(index),
                      ]"
                    ></div>
                    <div>
                      <div
                        class="text-sm font-medium text-gray-900 dark:text-white"
                      >
                        {{ project.project_title || "Unnamed Project" }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <span
                      class="text-2xl font-bold text-gray-900 dark:text-white mr-2"
                    >
                      {{ project.views }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                      views
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div
                      class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3"
                    >
                      <div
                        :class="[
                          'h-2 rounded-full transition-all duration-300',
                          getProjectColorClass(index),
                        ]"
                        :style="{ width: getPercentage(project.views) + '%' }"
                      ></div>
                    </div>
                    <span
                      class="text-sm font-medium text-gray-900 dark:text-white"
                    >
                      {{ getPercentage(project.views) }}%
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                      project.views >= maxViews * 0.8
                        ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400'
                        : project.views >= maxViews * 0.5
                        ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400'
                        : project.views >= maxViews * 0.2
                        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400'
                        : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400',
                    ]"
                  >
                    {{
                      project.views >= maxViews * 0.8
                        ? "Excellent"
                        : project.views >= maxViews * 0.5
                        ? "Good"
                        : project.views >= maxViews * 0.2
                        ? "Average"
                        : "Low"
                    }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
