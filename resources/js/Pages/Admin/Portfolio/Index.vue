<script setup>
import { ref, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import ConfirmDialog from "@/Components/UI/ConfirmDialog.vue";

// Props
const props = defineProps({
  portfolios: {
    type: Array,
    default: () => [],
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

// Reactive state
const searchQuery = ref("");
const selectedType = ref("all");
const selectedPortfolio = ref(null);
const showDeleteDialog = ref(false);
const showBulkActions = ref(false);
const selectedItems = ref([]);
const sortBy = ref("created_at");
const sortDirection = ref("desc");

// Computed properties
const projectTypes = computed(() => {
  const types = [...new Set(props.portfolios.map((p) => p.project_type))];
  return ["all", ...types];
});

const filteredPortfolios = computed(() => {
  let filtered = [...props.portfolios];

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(
      (portfolio) =>
        portfolio.title.toLowerCase().includes(query) ||
        portfolio.description.toLowerCase().includes(query) ||
        portfolio.technologies.toLowerCase().includes(query)
    );
  }

  // Filter by type
  if (selectedType.value !== "all") {
    filtered = filtered.filter(
      (portfolio) => portfolio.project_type === selectedType.value
    );
  }

  // Sort
  filtered.sort((a, b) => {
    const aValue = a[sortBy.value];
    const bValue = b[sortBy.value];

    if (sortDirection.value === "asc") {
      return aValue > bValue ? 1 : -1;
    } else {
      return aValue < bValue ? 1 : -1;
    }
  });

  return filtered;
});

const selectedCount = computed(() => selectedItems.value.length);

const allSelected = computed(
  () =>
    filteredPortfolios.value.length > 0 &&
    selectedItems.value.length === filteredPortfolios.value.length
);

// Methods
const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const getTechnologies = (technologies) => {
  return technologies.split(", ").slice(0, 3);
};

const getMoreTechCount = (technologies) => {
  const all = technologies.split(", ");
  return all.length > 3 ? all.length - 3 : 0;
};

const getStatusBadge = (portfolio) => {
  if (portfolio.is_featured) {
    return {
      text: "Featured",
      class: "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200",
    };
  }
  return {
    text: "Regular",
    class: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
  };
};

const toggleSelectAll = () => {
  if (allSelected.value) {
    selectedItems.value = [];
  } else {
    selectedItems.value = filteredPortfolios.value.map((p) => p.id);
  }
};

const toggleSelect = (id) => {
  const index = selectedItems.value.indexOf(id);
  if (index > -1) {
    selectedItems.value.splice(index, 1);
  } else {
    selectedItems.value.push(id);
  }
};

const handleSort = (field) => {
  if (sortBy.value === field) {
    sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
  } else {
    sortBy.value = field;
    sortDirection.value = "asc";
  }
};

const confirmDelete = (portfolio) => {
  selectedPortfolio.value = portfolio;
  showDeleteDialog.value = true;
};

const deletePortfolio = () => {
  if (selectedPortfolio.value) {
    router.delete(`/admin/portfolio/${selectedPortfolio.value.id}`, {
      onSuccess: () => {
        showDeleteDialog.value = false;
        selectedPortfolio.value = null;
      },
    });
  }
};

const bulkDelete = () => {
  if (selectedItems.value.length > 0) {
    router.post(
      "/admin/portfolio/bulk-delete",
      {
        ids: selectedItems.value,
      },
      {
        onSuccess: () => {
          selectedItems.value = [];
          showBulkActions.value = false;
        },
      }
    );
  }
};

const duplicatePortfolio = (portfolio) => {
  router.post(`/admin/portfolio/${portfolio.id}/duplicate`);
};

const toggleFeatured = (portfolio) => {
  router.patch(
    `/admin/portfolio/${portfolio.id}`,
    {
      is_featured: !portfolio.is_featured,
    },
    {
      preserveScroll: true,
    }
  );
};
</script>

<template>
  <DashboardLayout
    title="Portfolio Management"
    :breadcrumbs="[
      { name: 'Dashboard', href: '/dashboard' },
      { name: 'Portfolio' },
    ]"
  >
    <Head title="Portfolio Management" />

    <div class="p-6 space-y-6">
      <!-- Header Actions -->
      <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
      >
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Portfolio Projects
          </h1>
          <p class="text-gray-600 dark:text-gray-400">
            Manage your portfolio projects and showcase your work
          </p>
        </div>
        <div class="flex items-center gap-3">
          <Link
            href="/admin/portfolio/create"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors shadow-sm"
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
              ></path>
            </svg>
            Add New Project
          </Link>
        </div>
      </div>

      <!-- Filters & Search -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
      >
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
          <!-- Search -->
          <div class="flex-1">
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
                  ></path>
                </svg>
              </div>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search projects..."
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
          </div>

          <!-- Project Type Filter -->
          <div class="lg:w-48">
            <select
              v-model="selectedType"
              class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="all">All Types</option>
              <option
                v-for="type in projectTypes.slice(1)"
                :key="type"
                :value="type"
              >
                {{ type }}
              </option>
            </select>
          </div>

          <!-- Sort -->
          <div class="lg:w-48">
            <select
              v-model="sortBy"
              class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="created_at">Sort by Date</option>
              <option value="title">Sort by Title</option>
              <option value="project_type">Sort by Type</option>
              <option value="is_featured">Sort by Featured</option>
            </select>
          </div>
        </div>

        <!-- Bulk Actions -->
        <div
          v-if="selectedCount > 0"
          class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800"
        >
          <div class="flex items-center justify-between">
            <span class="text-sm text-blue-800 dark:text-blue-200">
              {{ selectedCount }} project{{
                selectedCount !== 1 ? "s" : ""
              }}
              selected
            </span>
            <div class="flex items-center gap-2">
              <button
                @click="bulkDelete"
                class="px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors"
              >
                Delete Selected
              </button>
              <button
                @click="selectedItems = []"
                class="px-3 py-1 text-sm bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors"
              >
                Clear Selection
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Portfolio Table/Grid -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
      >
        <!-- Table Header -->
        <div
          class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50"
        >
          <div class="flex items-center">
            <div class="flex items-center">
              <input
                type="checkbox"
                :checked="allSelected"
                @change="toggleSelectAll"
                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
              />
              <label
                class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300"
              >
                Select All
              </label>
            </div>
          </div>
        </div>

        <!-- Projects List -->
        <div
          v-if="filteredPortfolios.length > 0"
          class="divide-y divide-gray-200 dark:divide-gray-700"
        >
          <div
            v-for="portfolio in filteredPortfolios"
            :key="portfolio.id"
            class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
          >
            <div class="flex items-start space-x-4">
              <!-- Checkbox -->
              <div class="flex-shrink-0 pt-1">
                <input
                  type="checkbox"
                  :checked="selectedItems.includes(portfolio.id)"
                  @change="toggleSelect(portfolio.id)"
                  class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                />
              </div>

              <!-- Project Image/Icon -->
              <div class="flex-shrink-0">
                <div
                  class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center"
                >
                  <svg
                    class="w-8 h-8 text-white"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                    ></path>
                  </svg>
                </div>
              </div>

              <!-- Project Details -->
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <!-- Title & Status -->
                    <div class="flex items-center gap-3 mb-2">
                      <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white"
                      >
                        {{ portfolio.title }}
                      </h3>
                      <span
                        :class="getStatusBadge(portfolio).class"
                        class="px-2 py-1 text-xs font-medium rounded-full"
                      >
                        {{ getStatusBadge(portfolio).text }}
                      </span>
                    </div>

                    <!-- Description -->
                    <p
                      class="text-gray-600 dark:text-gray-400 mb-3 line-clamp-2"
                    >
                      {{ portfolio.description }}
                    </p>

                    <!-- Meta Info -->
                    <div
                      class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-3"
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
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                          ></path>
                        </svg>
                        {{ portfolio.project_type }}
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
                            d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4h3a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"
                          ></path>
                        </svg>
                        {{ formatDate(portfolio.project_date) }}
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
                          ></path>
                        </svg>
                        {{ formatDate(portfolio.created_at) }}
                      </span>
                    </div>

                    <!-- Technologies -->
                    <div class="flex flex-wrap gap-2">
                      <span
                        v-for="tech in getTechnologies(portfolio.technologies)"
                        :key="tech"
                        class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-md"
                      >
                        {{ tech.trim() }}
                      </span>
                      <span
                        v-if="getMoreTechCount(portfolio.technologies) > 0"
                        class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-md"
                      >
                        +{{ getMoreTechCount(portfolio.technologies) }} more
                      </span>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div class="flex-shrink-0 ml-4">
                    <div class="flex items-center gap-2">
                      <!-- Quick Actions -->
                      <button
                        @click="toggleFeatured(portfolio)"
                        :class="[
                          'p-2 rounded-md transition-colors',
                          portfolio.is_featured
                            ? 'text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20'
                            : 'text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700',
                        ]"
                        :title="
                          portfolio.is_featured
                            ? 'Remove from featured'
                            : 'Mark as featured'
                        "
                      >
                        <svg
                          class="w-5 h-5"
                          fill="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                          ></path>
                        </svg>
                      </button>

                      <!-- View -->
                      <Link
                        :href="`/admin/portfolio/${portfolio.id}`"
                        class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors"
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
                          ></path>
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                          ></path>
                        </svg>
                      </Link>

                      <!-- Edit -->
                      <Link
                        :href="`/admin/portfolio/${portfolio.id}/edit`"
                        class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-md transition-colors"
                        title="Edit Project"
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
                          ></path>
                        </svg>
                      </Link>

                      <!-- Duplicate -->
                      <button
                        @click="duplicatePortfolio(portfolio)"
                        class="p-2 text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-md transition-colors"
                        title="Duplicate Project"
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
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                          ></path>
                        </svg>
                      </button>

                      <!-- Delete -->
                      <button
                        @click="confirmDelete(portfolio)"
                        class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors"
                        title="Delete Project"
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
                          ></path>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="p-12 text-center">
          <div
            class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center"
          >
            <svg
              class="w-12 h-12 text-gray-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
              ></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
            No Projects Found
          </h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            {{
              searchQuery || selectedType !== "all"
                ? "No projects match your search criteria."
                : "Get started by creating your first portfolio project."
            }}
          </p>
          <Link
            href="/admin/portfolio/create"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
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
              ></path>
            </svg>
            Add Your First Project
          </Link>
        </div>
      </div>

      <!-- Delete Confirmation Dialog -->
      <ConfirmDialog
        :show="showDeleteDialog"
        title="Delete Project"
        :message="`Are you sure you want to delete '${selectedPortfolio?.title}'? This action cannot be undone.`"
        confirm-text="Delete"
        cancel-text="Cancel"
        type="danger"
        @confirm="deletePortfolio"
        @cancel="showDeleteDialog = false"
      />
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
