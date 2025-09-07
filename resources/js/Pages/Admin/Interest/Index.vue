<!-- resources/js/Pages/Admin/Interest/Index.vue -->
<script setup>
import { ref, computed } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  interests: {
    type: Array,
    required: true,
  },
});

// Page data
const page = usePage();
const flash = computed(() => page.props.flash || {});

// Reactive state
const selectedInterests = ref([]);
const sortBy = ref("name");
const sortOrder = ref("asc");
const searchQuery = ref("");
const filterFeatured = ref("all");

// Interest categories for organization
const interestCategories = {
  Technology: "💻",
  Programming: "⌨️",
  Design: "🎨",
  Science: "🔬",
  Business: "💼",
  Sports: "⚽",
  Music: "🎵",
  Art: "🖼️",
  Gaming: "🎮",
  Travel: "✈️",
  Photography: "📸",
  Reading: "📚",
  Cooking: "👨‍🍳",
  Learning: "📖",
  Innovation: "💡",
  Research: "🔍",
  Development: "🚀",
  Community: "🤝",
  Health: "💪",
  Nature: "🌿",
};

// Predefined colors for easy selection
const colorOptions = [
  "#3b82f6", // Blue
  "#10b981", // Green
  "#f59e0b", // Yellow
  "#ef4444", // Red
  "#8b5cf6", // Purple
  "#06b6d4", // Cyan
  "#f97316", // Orange
  "#84cc16", // Lime
  "#ec4899", // Pink
  "#6366f1", // Indigo
  "#14b8a6", // Teal
  "#f43f5e", // Rose
];

// Computed
const filteredInterests = computed(() => {
  let filtered = props.interests;

  // Search filter
  if (searchQuery.value) {
    filtered = filtered.filter(
      (interest) =>
        interest.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        (interest.description &&
          interest.description
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase()))
    );
  }

  // Featured filter
  if (filterFeatured.value === "featured") {
    filtered = filtered.filter((interest) => interest.is_featured);
  } else if (filterFeatured.value === "not_featured") {
    filtered = filtered.filter((interest) => !interest.is_featured);
  }

  // Sort
  filtered.sort((a, b) => {
    let aVal = a[sortBy.value];
    let bVal = b[sortBy.value];

    if (sortBy.value === "is_featured") {
      aVal = aVal ? 1 : 0;
      bVal = bVal ? 1 : 0;
    }

    if (sortOrder.value === "asc") {
      return aVal < bVal ? -1 : aVal > bVal ? 1 : 0;
    } else {
      return aVal > bVal ? -1 : aVal < bVal ? 1 : 0;
    }
  });

  return filtered;
});

const interestStats = computed(() => ({
  total: props.interests.length,
  featured: props.interests.filter((interest) => interest.is_featured).length,
  not_featured: props.interests.filter((interest) => !interest.is_featured)
    .length,
  with_description: props.interests.filter(
    (interest) => interest.description && interest.description.trim()
  ).length,
}));

// Methods
const deleteInterest = (interestId) => {
  if (confirm("Are you sure you want to delete this interest?")) {
    router.delete(`/admin/interests/${interestId}`, {
      onSuccess: () => {
        selectedInterests.value = selectedInterests.value.filter(
          (id) => id !== interestId
        );
      },
    });
  }
};

const bulkDelete = () => {
  if (selectedInterests.value.length === 0) return;

  if (
    confirm(
      `Are you sure you want to delete ${selectedInterests.value.length} interest(s)?`
    )
  ) {
    router.delete("/admin/interests/bulk-delete", {
      data: { ids: selectedInterests.value },
      onSuccess: () => {
        selectedInterests.value = [];
      },
    });
  }
};

const toggleFeatured = (interest) => {
  router.patch(
    `/admin/interests/${interest.id}/toggle-featured`,
    {},
    {
      preserveScroll: true,
      onSuccess: () => {
        // Success handled by page refresh
      },
    }
  );
};

const getColorStyle = (color) => {
  return {
    backgroundColor: color || "#3b82f6",
    color: "#ffffff",
  };
};

const getColorClass = (color) => {
  // Generate text color class based on background color
  const colorMap = {
    "#3b82f6": "text-blue-600",
    "#10b981": "text-emerald-600",
    "#f59e0b": "text-amber-600",
    "#ef4444": "text-red-600",
    "#8b5cf6": "text-violet-600",
    "#06b6d4": "text-cyan-600",
    "#f97316": "text-orange-600",
    "#84cc16": "text-lime-600",
    "#ec4899": "text-pink-600",
    "#6366f1": "text-indigo-600",
    "#14b8a6": "text-teal-600",
    "#f43f5e": "text-rose-600",
  };
  return colorMap[color] || "text-blue-600";
};

const toggleSelectAll = () => {
  if (selectedInterests.value.length === filteredInterests.value.length) {
    selectedInterests.value = [];
  } else {
    selectedInterests.value = filteredInterests.value.map((i) => i.id);
  }
};

const sortTable = (column) => {
  if (sortBy.value === column) {
    sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
  } else {
    sortBy.value = column;
    sortOrder.value = "asc";
  }
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Interests", href: "/admin/interests", current: true },
];
</script>

<template>
  <Head title="Interests Management" />

  <DashboardLayout :title="'Interests Management'" :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
      <!-- Success Flash Message -->
      <div
        v-if="flash.success"
        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4"
      >
        <div class="flex">
          <div class="flex-shrink-0">
            <svg
              class="h-5 w-5 text-green-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-green-800 dark:text-green-200">
              {{ flash.success }}
            </p>
          </div>
        </div>
      </div>

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
                class="w-8 h-8 mr-3 text-purple-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                />
              </svg>
              Interests Management
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Manage your personal interests and hobbies
            </p>
          </div>

          <Link
            href="/admin/interests/create"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
              />
            </svg>
            Add Interest
          </Link>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-purple-600 dark:text-purple-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                  />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Total Interests
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ interestStats.total }}
              </p>
            </div>
          </div>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-yellow-600 dark:text-yellow-400"
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
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Featured
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ interestStats.featured }}
              </p>
            </div>
          </div>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-gray-600 dark:text-gray-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Regular
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ interestStats.not_featured }}
              </p>
            </div>
          </div>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-blue-600 dark:text-blue-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                With Description
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ interestStats.with_description }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters and Search -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
        >
          <div
            class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4"
          >
            <!-- Search -->
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
                placeholder="Search interests..."
                class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
            </div>

            <!-- Featured Filter -->
            <select
              v-model="filterFeatured"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="all">All Interests</option>
              <option value="featured">Featured Only</option>
              <option value="not_featured">Regular Only</option>
            </select>
          </div>

          <!-- Bulk Actions -->
          <div
            v-if="selectedInterests.length > 0"
            class="flex items-center space-x-4"
          >
            <span class="text-sm text-gray-600 dark:text-gray-400">
              {{ selectedInterests.length }} selected
            </span>
            <button
              @click="bulkDelete"
              class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                />
              </svg>
              Delete Selected
            </button>
          </div>
        </div>
      </div>

      <!-- Interests Grid -->
      <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
      >
        <div
          v-for="interest in filteredInterests"
          :key="interest.id"
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow duration-200"
        >
          <!-- Selection checkbox -->
          <div class="flex items-start justify-between mb-4">
            <input
              v-model="selectedInterests"
              :value="interest.id"
              type="checkbox"
              class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
            />
            <div class="flex items-center space-x-2">
              <!-- Featured badge -->
              <button
                @click="toggleFeatured(interest)"
                :class="
                  interest.is_featured
                    ? 'text-yellow-500'
                    : 'text-gray-300 hover:text-yellow-500'
                "
                class="transition-colors duration-200"
                title="Toggle Featured"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                  />
                </svg>
              </button>
            </div>
          </div>

          <!-- Interest content -->
          <div class="text-center mb-4">
            <div
              class="w-16 h-16 mx-auto rounded-full flex items-center justify-center text-3xl mb-3"
              :style="getColorStyle(interest.color)"
            >
              {{ interest.icon }}
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ interest.name }}
            </h3>
            <p
              v-if="interest.description"
              class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2"
            >
              {{ interest.description }}
            </p>
          </div>

          <!-- Color indicator -->
          <div class="flex items-center justify-center mb-4">
            <div
              class="w-6 h-6 rounded-full border-2 border-gray-200 dark:border-gray-600"
              :style="{ backgroundColor: interest.color }"
            ></div>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-center space-x-3">
            <Link
              :href="`/admin/interests/${interest.id}`"
              class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
              title="View Details"
            >
              <svg
                class="w-5 h-5"
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
            </Link>
            <Link
              :href="`/admin/interests/${interest.id}/edit`"
              class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
              title="Edit"
            >
              <svg
                class="w-5 h-5"
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
            <button
              @click="deleteInterest(interest.id)"
              class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
              title="Delete"
            >
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredInterests.length === 0" class="text-center py-12">
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
            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
          />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
          No interests found
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{
            searchQuery
              ? "Try adjusting your search criteria."
              : "Get started by adding your first interest."
          }}
        </p>
        <div v-if="!searchQuery" class="mt-6">
          <Link
            href="/admin/interests/create"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
              />
            </svg>
            Add Your First Interest
          </Link>
        </div>
      </div>

      <!-- Bulk Select All -->
      <div
        v-if="filteredInterests.length > 0"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              type="checkbox"
              :checked="
                selectedInterests.length === filteredInterests.length &&
                filteredInterests.length > 0
              "
              @change="toggleSelectAll"
              class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
            />
            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
              Select all {{ filteredInterests.length }} interest(s)
            </label>
          </div>
          <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ selectedInterests.length }} of
            {{ filteredInterests.length }} selected
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
