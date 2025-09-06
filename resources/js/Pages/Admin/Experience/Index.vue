<script setup>
import { ref, computed } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  experiences: {
    type: Array,
    default: () => [],
  },
  flash: {
    type: Object,
    default: () => ({}),
  },
});

// Reactive state
const searchQuery = ref("");
const sortBy = ref("order");
const sortOrder = ref("asc");
const showDeleteModal = ref(false);
const experienceToDelete = ref(null);

// Delete form
const deleteForm = useForm({});

// Computed properties
const filteredExperiences = computed(() => {
  let filtered = [...props.experiences];

  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(
      (experience) =>
        experience.position.toLowerCase().includes(query) ||
        experience.organization.toLowerCase().includes(query) ||
        experience.description.toLowerCase().includes(query)
    );
  }

  // Sort
  filtered.sort((a, b) => {
    let aVal = a[sortBy.value];
    let bVal = b[sortBy.value];

    if (sortBy.value === "start_date") {
      aVal = new Date(aVal);
      bVal = new Date(bVal);
    }

    if (sortOrder.value === "asc") {
      return aVal > bVal ? 1 : -1;
    } else {
      return aVal < bVal ? 1 : -1;
    }
  });

  return filtered;
});

const totalExperiences = computed(() => props.experiences.length);
const currentExperiences = computed(
  () => props.experiences.filter((exp) => exp.is_current).length
);

// Methods
const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
  });
};

const formatDuration = (startDate, endDate, isCurrent) => {
  const start = new Date(startDate);
  const end = isCurrent ? new Date() : new Date(endDate);

  const years = end.getFullYear() - start.getFullYear();
  const months = end.getMonth() - start.getMonth();

  let totalMonths = years * 12 + months;
  if (totalMonths < 1) totalMonths = 1;

  const displayYears = Math.floor(totalMonths / 12);
  const displayMonths = totalMonths % 12;

  let duration = "";
  if (displayYears > 0) {
    duration += `${displayYears} year${displayYears > 1 ? "s" : ""}`;
  }
  if (displayMonths > 0) {
    if (duration) duration += " ";
    duration += `${displayMonths} month${displayMonths > 1 ? "s" : ""}`;
  }

  return duration || "1 month";
};

const confirmDelete = (experience) => {
  experienceToDelete.value = experience;
  showDeleteModal.value = true;
};

const deleteExperience = () => {
  if (!experienceToDelete.value) return;

  deleteForm.delete(`/admin/experience/${experienceToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      experienceToDelete.value = null;
    },
    onError: () => {
      // Handle error
    },
  });
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  experienceToDelete.value = null;
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
  { name: "Experience", href: "/admin/experience", current: true },
];
</script>

<template>
  <Head title="Experience Management" />

  <DashboardLayout :title="'Experience Management'" :breadcrumbs="breadcrumbs">
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
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h8zM16 10h.01M12 14h.01M8 14h.01"
                />
              </svg>
              Experience Management
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Manage your professional experience and work history
            </p>
          </div>

          <Link
            href="/admin/experience/create"
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
            Add New Experience
          </Link>
        </div>

        <!-- Statistics -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
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
                  Total Experiences
                </p>
                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                  {{ totalExperiences }}
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
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <p
                  class="text-sm font-medium text-green-600 dark:text-green-400"
                >
                  Current Positions
                </p>
                <p
                  class="text-2xl font-bold text-green-900 dark:text-green-100"
                >
                  {{ currentExperiences }}
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
                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <p
                  class="text-sm font-medium text-purple-600 dark:text-purple-400"
                >
                  Organizations
                </p>
                <p
                  class="text-2xl font-bold text-purple-900 dark:text-purple-100"
                >
                  {{
                    [...new Set(experiences.map((exp) => exp.organization))]
                      .length
                  }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters and Search -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0"
        >
          <!-- Search -->
          <div class="flex-1 max-w-md">
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
                placeholder="Search experiences..."
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
            </div>
          </div>

          <!-- Sort Options -->
          <div class="flex items-center space-x-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
              Sort by:
            </label>
            <select
              v-model="sortBy"
              class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="order">Order</option>
              <option value="start_date">Start Date</option>
              <option value="position">Position</option>
              <option value="organization">Organization</option>
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
        </div>
      </div>

      <!-- Experience List -->
      <div class="space-y-4">
        <div
          v-if="filteredExperiences.length === 0"
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
              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h8zM16 10h.01M12 14h.01M8 14h.01"
            />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
            No experiences found
          </h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{
              searchQuery
                ? "Try adjusting your search terms."
                : "Get started by adding your first experience."
            }}
          </p>
          <div class="mt-6" v-if="!searchQuery">
            <Link
              href="/admin/experience/create"
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
              Add Experience
            </Link>
          </div>
        </div>

        <div
          v-for="experience in filteredExperiences"
          :key="experience.id"
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200"
        >
          <div class="flex flex-col lg:flex-row lg:justify-between">
            <div class="flex-1">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center space-x-3">
                    <h3
                      class="text-lg font-semibold text-gray-900 dark:text-white"
                    >
                      {{ experience.position }}
                    </h3>
                    <span
                      v-if="experience.is_current"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400"
                    >
                      <span
                        class="w-2 h-2 mr-1 bg-green-500 rounded-full animate-pulse"
                      ></span>
                      Current
                    </span>
                  </div>
                  <p class="text-indigo-600 dark:text-indigo-400 font-medium">
                    {{ experience.organization }}
                  </p>
                  <div
                    class="mt-2 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400"
                  >
                    <span class="flex items-center">
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
                          d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2z"
                        />
                      </svg>
                      {{ formatDate(experience.start_date) }} -
                      {{
                        experience.is_current
                          ? "Present"
                          : formatDate(experience.end_date)
                      }}
                    </span>
                    <span class="flex items-center">
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
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                      {{
                        formatDuration(
                          experience.start_date,
                          experience.end_date,
                          experience.is_current
                        )
                      }}
                    </span>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-2 ml-4">
                  <Link
                    :href="`/admin/experience/${experience.id}/edit`"
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
                    @click="confirmDelete(experience)"
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

              <!-- Description -->
              <div class="mt-4">
                <p class="text-gray-600 dark:text-gray-300 text-sm leading-6">
                  {{ experience.description }}
                </p>
              </div>
            </div>
          </div>
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
              Delete Experience
            </h3>
            <div class="mt-2 px-7 py-3">
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Are you sure you want to delete
                <span class="font-semibold">{{
                  experienceToDelete?.position
                }}</span>
                at
                <span class="font-semibold">{{
                  experienceToDelete?.organization
                }}</span
                >? This action cannot be undone.
              </p>
            </div>
            <div class="items-center px-4 py-3">
              <div class="flex justify-center space-x-3">
                <button
                  @click="closeDeleteModal"
                  class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300"
                >
                  Cancel
                </button>
                <button
                  @click="deleteExperience"
                  :disabled="deleteForm.processing"
                  class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50"
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
