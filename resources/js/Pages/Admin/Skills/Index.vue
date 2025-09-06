<script setup>
import { ref, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  skills: {
    type: Array,
    default: () => [],
  },
  skillsByCategory: {
    type: Object,
    default: () => ({}),
  },
  flash: {
    type: Object,
    default: () => ({}),
  },
});

// Reactive state
const searchQuery = ref("");
const selectedCategory = ref("");
const sortBy = ref("order");
const sortOrder = ref("asc");
const viewMode = ref("grid");
const showDeleteModal = ref(false);
const skillToDelete = ref(null);

// Delete form
const deleteForm = useForm({});

// Computed properties
const categories = computed(() => {
  return Object.keys(props.skillsByCategory).sort();
});

const filteredSkills = computed(() => {
  let filtered = [...props.skills];

  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(
      (skill) =>
        skill.name.toLowerCase().includes(query) ||
        skill.category.toLowerCase().includes(query) ||
        (skill.description && skill.description.toLowerCase().includes(query))
    );
  }

  // Category filter
  if (selectedCategory.value) {
    filtered = filtered.filter(
      (skill) => skill.category === selectedCategory.value
    );
  }

  // Sort
  filtered.sort((a, b) => {
    let aVal = a[sortBy.value];
    let bVal = b[sortBy.value];

    if (sortBy.value === "proficiency") {
      aVal = parseInt(aVal);
      bVal = parseInt(bVal);
    }

    if (sortOrder.value === "asc") {
      return aVal > bVal ? 1 : -1;
    } else {
      return aVal < bVal ? 1 : -1;
    }
  });

  return filtered;
});

const skillStats = computed(() => {
  const skills = props.skills;
  const avgProficiency =
    skills.length > 0
      ? Math.round(
          skills.reduce((sum, skill) => sum + skill.proficiency, 0) /
            skills.length
        )
      : 0;

  const levelCounts = skills.reduce((counts, skill) => {
    counts[skill.level] = (counts[skill.level] || 0) + 1;
    return counts;
  }, {});

  return {
    total: skills.length,
    categories: categories.value.length,
    avgProficiency,
    levelCounts,
  };
});

// Methods
const getLevelColor = (level) => {
  const colors = {
    pemula:
      "bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400",
    menengah:
      "bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400",
    mahir:
      "bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400",
  };
  return colors[level] || colors.pemula;
};

const getLevelLabel = (level) => {
  const labels = {
    pemula: "Beginner",
    menengah: "Intermediate",
    mahir: "Advanced",
  };
  return labels[level] || level;
};

const getProficiencyColor = (proficiency) => {
  if (proficiency >= 80) return "bg-green-500";
  if (proficiency >= 60) return "bg-blue-500";
  if (proficiency >= 40) return "bg-yellow-500";
  return "bg-red-500";
};

const confirmDelete = (skill) => {
  skillToDelete.value = skill;
  showDeleteModal.value = true;
};

const deleteSkill = () => {
  if (!skillToDelete.value) return;

  deleteForm.delete(`/admin/skills/${skillToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      skillToDelete.value = null;
    },
    onError: () => {
      // Handle error
    },
  });
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  skillToDelete.value = null;
};

const setSortBy = (column) => {
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
  { name: "Skills", href: "/admin/skills", current: true },
];
</script>

<template>
  <Head title="Skills Management" />

  <DashboardLayout :title="'Skills Management'" :breadcrumbs="breadcrumbs">
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
                class="w-8 h-8 mr-3 text-indigo-600"
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
              Skills Management
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Manage your technical and soft skills
            </p>
          </div>

          <Link
            href="/admin/skills/create"
            class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
            Add New Skill
          </Link>
        </div>

        <!-- Statistics Cards -->
        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
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
                    d="M13 10V3L4 14h7v7l9-11h-7z"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                  Total Skills
                </p>
                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                  {{ skillStats.total }}
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
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <p
                  class="text-sm font-medium text-green-600 dark:text-green-400"
                >
                  Categories
                </p>
                <p
                  class="text-2xl font-bold text-green-900 dark:text-green-100"
                >
                  {{ skillStats.categories }}
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
                  Avg. Proficiency
                </p>
                <p
                  class="text-2xl font-bold text-purple-900 dark:text-purple-100"
                >
                  {{ skillStats.avgProficiency }}%
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
                  Advanced Skills
                </p>
                <p
                  class="text-2xl font-bold text-orange-900 dark:text-orange-100"
                >
                  {{ skillStats.levelCounts.mahir || 0 }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters and Controls -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0"
        >
          <!-- Search and Category Filter -->
          <div
            class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4"
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
                placeholder="Search skills..."
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
            </div>

            <!-- Category Filter -->
            <select
              v-model="selectedCategory"
              class="min-w-[200px] px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="">All Categories</option>
              <option
                v-for="category in categories"
                :key="category"
                :value="category"
              >
                {{ category }}
              </option>
            </select>
          </div>

          <!-- Sort and View Controls -->
          <div class="flex items-center space-x-4">
            <!-- Sort -->
            <div class="flex items-center space-x-2">
              <label
                class="text-sm font-medium text-gray-700 dark:text-gray-300"
              >
                Sort by:
              </label>
              <select
                v-model="sortBy"
                class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              >
                <option value="order">Order</option>
                <option value="name">Name</option>
                <option value="category">Category</option>
                <option value="level">Level</option>
                <option value="proficiency">Proficiency</option>
              </select>

              <button
                @click="setSortBy(sortBy)"
                class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
              >
                <svg
                  class="h-5 w-5"
                  :class="{
                    'rotate-180': sortOrder === 'desc',
                  }"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                  />
                </svg>
              </button>
            </div>

            <!-- View Mode Toggle -->
            <div
              class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg"
            >
              <button
                @click="viewMode = 'grid'"
                :class="[
                  'px-3 py-2 text-sm font-medium transition-colors rounded-l-lg',
                  viewMode === 'grid'
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
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                  />
                </svg>
              </button>
              <button
                @click="viewMode = 'list'"
                :class="[
                  'px-3 py-2 text-sm font-medium transition-colors rounded-r-lg',
                  viewMode === 'list'
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
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-if="filteredSkills.length === 0"
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
            d="M13 10V3L4 14h7v7l9-11h-7z"
          />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
          No skills found
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{
            searchQuery || selectedCategory
              ? "Try adjusting your search terms or filters."
              : "Get started by adding your first skill."
          }}
        </p>
        <div class="mt-6" v-if="!searchQuery && !selectedCategory">
          <Link
            href="/admin/skills/create"
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
                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
              />
            </svg>
            Add Skill
          </Link>
        </div>
      </div>

      <!-- Grid View -->
      <div
        v-else-if="viewMode === 'grid'"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
      >
        <div
          v-for="skill in filteredSkills"
          :key="skill.id"
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200"
        >
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ skill.name }}
              </h3>
              <p
                class="text-sm text-indigo-600 dark:text-indigo-400 font-medium"
              >
                {{ skill.category }}
              </p>
            </div>
            <span
              :class="[
                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                getLevelColor(skill.level),
              ]"
            >
              {{ getLevelLabel(skill.level) }}
            </span>
          </div>

          <!-- Proficiency Bar -->
          <div class="mb-4">
            <div class="flex items-center justify-between mb-1">
              <span
                class="text-sm font-medium text-gray-700 dark:text-gray-300"
              >
                Proficiency
              </span>
              <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ skill.proficiency }}%
              </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div
                :class="[
                  'h-2 rounded-full transition-all duration-300',
                  getProficiencyColor(skill.proficiency),
                ]"
                :style="{ width: skill.proficiency + '%' }"
              ></div>
            </div>
          </div>

          <!-- Description -->
          <div v-if="skill.description" class="mb-4">
            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">
              {{ skill.description }}
            </p>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-end space-x-2">
            <Link
              :href="`/admin/skills/${skill.id}/edit`"
              class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors duration-200"
            >
              <svg
                class="w-4 h-4 mr-1"
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
              Edit
            </Link>

            <button
              @click="confirmDelete(skill)"
              class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors duration-200"
            >
              <svg
                class="w-4 h-4 mr-1"
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
              Delete
            </button>
          </div>
        </div>
      </div>

      <!-- List View -->
      <div
        v-else
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
      >
        <div class="overflow-x-auto">
          <table
            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
          >
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th
                  @click="setSortBy('name')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                >
                  <div class="flex items-center space-x-1">
                    <span>Name</span>
                    <svg
                      v-if="sortBy === 'name'"
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
                  @click="setSortBy('level')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                >
                  <div class="flex items-center space-x-1">
                    <span>Level</span>
                    <svg
                      v-if="sortBy === 'level'"
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
                  @click="setSortBy('proficiency')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                >
                  <div class="flex items-center space-x-1">
                    <span>Proficiency</span>
                    <svg
                      v-if="sortBy === 'proficiency'"
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
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody
              class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
            >
              <tr
                v-for="skill in filteredSkills"
                :key="skill.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div>
                    <div
                      class="text-sm font-medium text-gray-900 dark:text-white"
                    >
                      {{ skill.name }}
                    </div>
                    <div
                      v-if="skill.description"
                      class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs"
                    >
                      {{ skill.description }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="text-sm text-indigo-600 dark:text-indigo-400 font-medium"
                  >
                    {{ skill.category }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                      getLevelColor(skill.level),
                    ]"
                  >
                    {{ getLevelLabel(skill.level) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div
                      class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3"
                    >
                      <div
                        :class="[
                          'h-2 rounded-full transition-all duration-300',
                          getProficiencyColor(skill.proficiency),
                        ]"
                        :style="{ width: skill.proficiency + '%' }"
                      ></div>
                    </div>
                    <span
                      class="text-sm text-gray-900 dark:text-white font-medium"
                    >
                      {{ skill.proficiency }}%
                    </span>
                  </div>
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                >
                  <div class="flex items-center justify-end space-x-2">
                    <Link
                      :href="`/admin/skills/${skill.id}/edit`"
                      class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors"
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
                    <button
                      @click="confirmDelete(skill)"
                      class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors"
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
      </div>

      <!-- Delete Confirmation Modal -->
      <div
        v-if="showDeleteModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        @click="closeDeleteModal"
      >
        <div
          class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800"
          @click.stop
        >
          <div class="mt-3 text-center">
            <div
              class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20"
            >
              <svg
                class="h-6 w-6 text-red-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"
                />
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-5">
              Delete Skill
            </h3>
            <div class="mt-2 px-7 py-3">
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Are you sure you want to delete
                <span class="font-semibold">{{ skillToDelete?.name }}</span>
                from the
                <span class="font-semibold">{{ skillToDelete?.category }}</span>
                category? This action cannot be undone.
              </p>
            </div>
            <div class="items-center px-4 py-3">
              <div class="flex justify-center space-x-3">
                <button
                  @click="closeDeleteModal"
                  class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors"
                >
                  Cancel
                </button>
                <button
                  @click="deleteSkill"
                  :disabled="deleteForm.processing"
                  class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50 transition-colors"
                >
                  <span v-if="deleteForm.processing">Deleting...</span>
                  <span v-else>Delete</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
