<!-- resources/js/Pages/Admin/Language/Index.vue -->
<script setup>
import { ref, computed } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  languages: {
    type: Array,
    required: true,
  },
});

// Page data
const page = usePage();
const flash = computed(() => page.props.flash || {});

// Reactive state
const selectedLanguages = ref([]);
const sortBy = ref("name");
const sortOrder = ref("asc");
const searchQuery = ref("");
const filterLevel = ref("all");

// Language levels mapping
const languageLevels = {
  A1: {
    text: "A1 - Beginner",
    class: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300",
    order: 1,
  },
  A2: {
    text: "A2 - Elementary",
    class:
      "bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300",
    order: 2,
  },
  B1: {
    text: "B1 - Intermediate",
    class:
      "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300",
    order: 3,
  },
  B2: {
    text: "B2 - Upper Intermediate",
    class: "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300",
    order: 4,
  },
  C1: {
    text: "C1 - Advanced",
    class:
      "bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300",
    order: 5,
  },
  C2: {
    text: "C2 - Proficient",
    class: "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300",
    order: 6,
  },
  native: {
    text: "Native",
    class:
      "bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300",
    order: 7,
  },
};

// Computed
const filteredLanguages = computed(() => {
  let filtered = props.languages;

  // Search filter
  if (searchQuery.value) {
    filtered = filtered.filter(
      (language) =>
        language.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        (language.description &&
          language.description
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase()))
    );
  }

  // Level filter
  if (filterLevel.value !== "all") {
    filtered = filtered.filter(
      (language) => language.level === filterLevel.value
    );
  }

  // Sort
  filtered.sort((a, b) => {
    let aVal = a[sortBy.value];
    let bVal = b[sortBy.value];

    if (sortBy.value === "level") {
      aVal = languageLevels[aVal]?.order || 0;
      bVal = languageLevels[bVal]?.order || 0;
    } else if (sortBy.value === "proficiency") {
      aVal = parseInt(aVal) || 0;
      bVal = parseInt(bVal) || 0;
    }

    if (sortOrder.value === "asc") {
      return aVal < bVal ? -1 : aVal > bVal ? 1 : 0;
    } else {
      return aVal > bVal ? -1 : aVal < bVal ? 1 : 0;
    }
  });

  return filtered;
});

const languageStats = computed(() => {
  const stats = {
    total: props.languages.length,
    native: 0,
    advanced: 0,
    intermediate: 0,
    beginner: 0,
  };

  props.languages.forEach((language) => {
    switch (language.level) {
      case "native":
        stats.native++;
        break;
      case "C1":
      case "C2":
        stats.advanced++;
        break;
      case "B1":
      case "B2":
        stats.intermediate++;
        break;
      case "A1":
      case "A2":
        stats.beginner++;
        break;
    }
  });

  return stats;
});

// Methods
const deleteLanguage = (languageId) => {
  if (confirm("Are you sure you want to delete this language?")) {
    router.delete(`/admin/languages/${languageId}`, {
      onSuccess: () => {
        selectedLanguages.value = selectedLanguages.value.filter(
          (id) => id !== languageId
        );
      },
    });
  }
};

const bulkDelete = () => {
  if (selectedLanguages.value.length === 0) return;

  if (
    confirm(
      `Are you sure you want to delete ${selectedLanguages.value.length} language(s)?`
    )
  ) {
    router.delete("/admin/languages/bulk-delete", {
      data: { ids: selectedLanguages.value },
      onSuccess: () => {
        selectedLanguages.value = [];
      },
    });
  }
};

const getLevelInfo = (level) => {
  return (
    languageLevels[level] || {
      text: level,
      class: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
    }
  );
};

const getProficiencyColor = (proficiency) => {
  if (proficiency >= 90) return "text-green-600 dark:text-green-400";
  if (proficiency >= 70) return "text-blue-600 dark:text-blue-400";
  if (proficiency >= 50) return "text-yellow-600 dark:text-yellow-400";
  return "text-red-600 dark:text-red-400";
};

const toggleSelectAll = () => {
  if (selectedLanguages.value.length === filteredLanguages.value.length) {
    selectedLanguages.value = [];
  } else {
    selectedLanguages.value = filteredLanguages.value.map((l) => l.id);
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
  { name: "Languages", href: "/admin/languages", current: true },
];
</script>

<template>
  <Head title="Languages Management" />

  <DashboardLayout :title="'Languages Management'" :breadcrumbs="breadcrumbs">
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
                class="w-8 h-8 mr-3 text-green-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
                />
              </svg>
              Languages Management
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Manage your language skills and proficiency levels
            </p>
          </div>

          <Link
            href="/admin/languages/create"
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
            Add Language
          </Link>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
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
                    d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
                  />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Total Languages
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ languageStats.total }}
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
                class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-indigo-600 dark:text-indigo-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                  />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Native
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ languageStats.native }}
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
                class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-green-600 dark:text-green-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                  />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Advanced
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ languageStats.advanced }}
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
                    d="M13 10V3L4 14h7v7l9-11h-7z"
                  />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Intermediate
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ languageStats.intermediate }}
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
                class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-red-600 dark:text-red-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                  />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Beginner
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ languageStats.beginner }}
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
                placeholder="Search languages..."
                class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
            </div>

            <!-- Level Filter -->
            <select
              v-model="filterLevel"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="all">All Levels</option>
              <option value="native">Native</option>
              <option value="C2">C2 - Proficient</option>
              <option value="C1">C1 - Advanced</option>
              <option value="B2">B2 - Upper Intermediate</option>
              <option value="B1">B1 - Intermediate</option>
              <option value="A2">A2 - Elementary</option>
              <option value="A1">A1 - Beginner</option>
            </select>
          </div>

          <!-- Bulk Actions -->
          <div
            v-if="selectedLanguages.length > 0"
            class="flex items-center space-x-4"
          >
            <span class="text-sm text-gray-600 dark:text-gray-400">
              {{ selectedLanguages.length }} selected
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

      <!-- Languages Table -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
      >
        <div class="overflow-x-auto">
          <table
            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
          >
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-6 py-3 text-left">
                  <input
                    type="checkbox"
                    :checked="
                      selectedLanguages.length === filteredLanguages.length &&
                      filteredLanguages.length > 0
                    "
                    @change="toggleSelectAll"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                  />
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                  @click="sortTable('name')"
                >
                  <div class="flex items-center space-x-1">
                    <span>Language</span>
                    <svg
                      v-if="sortBy === 'name'"
                      class="w-4 h-4"
                      :class="sortOrder === 'asc' ? 'transform rotate-180' : ''"
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
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                  @click="sortTable('level')"
                >
                  <div class="flex items-center space-x-1">
                    <span>Level</span>
                    <svg
                      v-if="sortBy === 'level'"
                      class="w-4 h-4"
                      :class="sortOrder === 'asc' ? 'transform rotate-180' : ''"
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
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                  @click="sortTable('proficiency')"
                >
                  <div class="flex items-center space-x-1">
                    <span>Proficiency</span>
                    <svg
                      v-if="sortBy === 'proficiency'"
                      class="w-4 h-4"
                      :class="sortOrder === 'asc' ? 'transform rotate-180' : ''"
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
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Description
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody
              class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
            >
              <tr
                v-for="language in filteredLanguages"
                :key="language.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <input
                    v-model="selectedLanguages"
                    :value="language.id"
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                  />
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div
                        class="h-10 w-10 rounded-lg bg-green-100 dark:bg-green-900 flex items-center justify-center"
                      >
                        <svg
                          class="h-6 w-6 text-green-600 dark:text-green-400"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
                          />
                        </svg>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div
                        class="text-sm font-medium text-gray-900 dark:text-white"
                      >
                        {{ language.name }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="getLevelInfo(language.level).class"
                  >
                    {{ getLevelInfo(language.level).text }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-1">
                      <div
                        class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2"
                      >
                        <div
                          class="h-2 rounded-full"
                          :class="getProficiencyColor(language.proficiency)"
                          :style="`width: ${language.proficiency}%; background-color: currentColor;`"
                        ></div>
                      </div>
                    </div>
                    <div class="ml-2">
                      <span
                        class="text-sm font-medium"
                        :class="getProficiencyColor(language.proficiency)"
                      >
                        {{ language.proficiency }}%
                      </span>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white">
                    {{
                      language.description
                        ? language.description.substring(0, 60) +
                          (language.description.length > 60 ? "..." : "")
                        : "No description"
                    }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center space-x-3">
                    <Link
                      :href="`/admin/languages/${language.id}`"
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
                      :href="`/admin/languages/${language.id}/edit`"
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
                      @click="deleteLanguage(language.id)"
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
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="filteredLanguages.length === 0" class="text-center py-12">
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
              d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
            />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
            No languages found
          </h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{
              searchQuery
                ? "Try adjusting your search criteria."
                : "Get started by adding your first language."
            }}
          </p>
          <div v-if="!searchQuery" class="mt-6">
            <Link
              href="/admin/languages/create"
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
              Add Your First Language
            </Link>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
