<script setup>
import { ref, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  visitors: {
    type: Object,
    default: () => ({
      data: [],
      links: [],
      total: 0,
      from: 0,
      to: 0,
      current_page: 1,
      per_page: 20,
      last_page: 1,
    }),
  },
});

// Reactive state
const sortBy = ref("last_visit");
const sortOrder = ref("desc");
const searchQuery = ref("");

// Computed properties
const filteredVisitors = computed(() => {
  const visitors = props.visitors.data || [];
  if (!searchQuery.value) return visitors;

  const query = searchQuery.value.toLowerCase();
  return visitors.filter(
    (visitor) =>
      visitor.ip_address && visitor.ip_address.toLowerCase().includes(query)
  );
});

const visitorStats = computed(() => {
  const data = props.visitors.data || [];
  return {
    total: props.visitors.total || data.length,
    returning: data.filter((v) => (v.visits || 0) >= 3).length,
    totalVisits: data.reduce((sum, v) => sum + (v.visits || 0), 0),
    avgVisits:
      data.length > 0
        ? (
            data.reduce((sum, v) => sum + (v.visits || 0), 0) / data.length
          ).toFixed(1)
        : 0,
  };
});

// Methods
const formatDate = (date) => {
  if (!date) return "Unknown";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const getTimeAgo = (date) => {
  if (!date) return "Unknown";
  const now = new Date();
  const visitDate = new Date(date);
  const diffInHours = Math.floor((now - visitDate) / (1000 * 60 * 60));

  if (diffInHours < 1) return "Just now";
  if (diffInHours < 24)
    return `${diffInHours} hour${diffInHours > 1 ? "s" : ""} ago`;

  const diffInDays = Math.floor(diffInHours / 24);
  if (diffInDays < 7)
    return `${diffInDays} day${diffInDays > 1 ? "s" : ""} ago`;

  const diffInWeeks = Math.floor(diffInDays / 7);
  return `${diffInWeeks} week${diffInWeeks > 1 ? "s" : ""} ago`;
};

const getVisitFrequencyClass = (visits) => {
  const v = visits || 0;
  if (v >= 10) return "text-red-600 font-bold";
  if (v >= 5) return "text-orange-600 font-semibold";
  if (v >= 3) return "text-blue-600 font-medium";
  return "text-gray-600";
};

const getVisitFrequencyLabel = (visits) => {
  const v = visits || 0;
  if (v >= 10) return "Frequent";
  if (v >= 5) return "Regular";
  if (v >= 3) return "Returning";
  return "New";
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
  { name: "Visitors", href: "/admin/analytics/visitors", current: true },
];
</script>

<template>
  <Head title="Visitor Analytics" />

  <DashboardLayout :title="'Visitor Analytics'" :breadcrumbs="breadcrumbs">
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
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                />
              </svg>
              Visitor Analytics
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Track unique visitors and their engagement patterns
            </p>
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
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                  Unique Visitors
                </p>
                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                  {{ visitorStats.total }}
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
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <p
                  class="text-sm font-medium text-green-600 dark:text-green-400"
                >
                  Returning Visitors
                </p>
                <p
                  class="text-2xl font-bold text-green-900 dark:text-green-100"
                >
                  {{ visitorStats.returning }}
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
                  Total Visits
                </p>
                <p
                  class="text-2xl font-bold text-purple-900 dark:text-purple-100"
                >
                  {{ visitorStats.totalVisits }}
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
                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <p
                  class="text-sm font-medium text-orange-600 dark:text-orange-400"
                >
                  Avg. Visits/Visitor
                </p>
                <p
                  class="text-2xl font-bold text-orange-900 dark:text-orange-100"
                >
                  {{ visitorStats.avgVisits }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Search -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
          >
            <svg
              class="h-5 w-5 text-gray-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
              />
            </svg>
          </div>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by IP address..."
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          />
        </div>
      </div>

      <!-- Visitors Table -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
      >
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Visitor Details
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Showing {{ filteredVisitors.length }} of
            {{ visitorStats.total }} visitors
          </p>
        </div>

        <div v-if="filteredVisitors.length === 0" class="text-center py-12">
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
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
            />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
            No visitors found
          </h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{
              searchQuery
                ? "Try adjusting your search terms."
                : "No visitor data available yet."
            }}
          </p>
        </div>

        <div v-else class="overflow-x-auto">
          <table
            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
          >
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th
                  @click="setSortBy('ip_address')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                >
                  <div class="flex items-center space-x-1">
                    <span>Visitor</span>
                  </div>
                </th>
                <th
                  @click="setSortBy('visits')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                >
                  <div class="flex items-center space-x-1">
                    <span>Visits</span>
                  </div>
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Visitor Type
                </th>
                <th
                  @click="setSortBy('last_visit')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                >
                  <div class="flex items-center space-x-1">
                    <span>Last Visit</span>
                  </div>
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Location
                </th>
              </tr>
            </thead>
            <tbody
              class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
            >
              <tr
                v-for="visitor in filteredVisitors"
                :key="visitor.ip_address"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                      <div
                        class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-medium"
                      >
                        {{
                          (visitor.ip_address || "0.0.0.0").split(".")[3] || "0"
                        }}
                      </div>
                    </div>
                    <div class="ml-4">
                      <div
                        class="text-sm font-medium text-gray-900 dark:text-white"
                      >
                        {{ visitor.ip_address || "Unknown IP" }}
                      </div>
                      <div class="text-sm text-gray-500 dark:text-gray-400">
                        IP Address
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <span
                      :class="[
                        'text-2xl font-bold mr-2',
                        getVisitFrequencyClass(visitor.visits),
                      ]"
                    >
                      {{ visitor.visits || 0 }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                      {{ (visitor.visits || 0) === 1 ? "visit" : "visits" }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                      (visitor.visits || 0) >= 10
                        ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'
                        : (visitor.visits || 0) >= 5
                        ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400'
                        : (visitor.visits || 0) >= 3
                        ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400'
                        : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400',
                    ]"
                  >
                    {{ getVisitFrequencyLabel(visitor.visits) }}
                  </span>
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                >
                  {{ formatDate(visitor.last_visit) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <span class="mr-2">🌍</span>
                    <span class="text-sm text-gray-600 dark:text-gray-400"
                      >Unknown Location</span
                    >
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
