<!-- resources/js/Pages/Admin/Portfolio/Index.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Portfolio Management</h1>
          <p class="text-gray-400">Manage your portfolio projects</p>
        </div>
        <Link
          :href="route('admin.portfolio.create')"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center"
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
          Add New Project
        </Link>
      </div>

      <!-- Filters & Search -->
      <div class="bg-gray-800 rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Search -->
          <div class="md:col-span-2">
            <FormInput
              v-model="filters.search"
              placeholder="Search projects..."
              @input="handleSearch"
            >
              <template #prepend>
                <svg
                  class="w-5 h-5 text-gray-400"
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
              </template>
            </FormInput>
          </div>

          <!-- Project Type Filter -->
          <FormSelect
            v-model="filters.type"
            placeholder="All Types"
            :options="[
              { value: '', label: 'All Types' },
              ...projectTypeOptions,
            ]"
            @change="handleFilter"
          />

          <!-- Sort -->
          <FormSelect
            v-model="filters.sort"
            :options="sortOptions"
            @change="handleFilter"
          />
        </div>

        <!-- Filter Summary -->
        <div v-if="hasActiveFilters" class="mt-4 flex items-center space-x-2">
          <span class="text-sm text-gray-400">Active filters:</span>
          <span
            v-if="filters.search"
            class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-900 text-blue-200"
          >
            Search: "{{ filters.search }}"
            <button
              @click="clearFilter('search')"
              class="ml-1 text-blue-300 hover:text-white"
            >
              ×
            </button>
          </span>
          <span
            v-if="filters.type"
            class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-900 text-green-200"
          >
            Type: {{ filters.type }}
            <button
              @click="clearFilter('type')"
              class="ml-1 text-green-300 hover:text-white"
            >
              ×
            </button>
          </span>
          <button
            @click="clearAllFilters"
            class="text-sm text-red-400 hover:text-red-300"
          >
            Clear all
          </button>
        </div>
      </div>

      <!-- Portfolio Grid -->
      <div
        v-if="portfolios.data.length > 0"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6"
      >
        <div
          v-for="portfolio in portfolios.data"
          :key="portfolio.id"
          class="bg-gray-800 rounded-lg overflow-hidden hover:shadow-lg transition-shadow"
        >
          <!-- Project Image -->
          <div class="relative h-48 bg-gray-700">
            <img
              v-if="portfolio.image"
              :src="portfolio.image"
              :alt="portfolio.title"
              class="w-full h-full object-cover"
            />
            <div v-else class="w-full h-full flex items-center justify-center">
              <svg
                class="w-16 h-16 text-gray-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>

            <!-- Project Type Badge -->
            <div class="absolute top-3 left-3">
              <span
                class="px-2 py-1 bg-blue-600 text-white text-xs rounded-full"
              >
                {{ portfolio.project_type }}
              </span>
            </div>

            <!-- Actions Dropdown -->
            <div class="absolute top-3 right-3">
              <div class="relative">
                <button
                  @click="toggleActions(portfolio.id)"
                  class="p-2 bg-gray-900 bg-opacity-75 rounded-full text-white hover:bg-opacity-100 transition-all"
                >
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
                    />
                  </svg>
                </button>

                <div
                  v-if="activeActions === portfolio.id"
                  class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg border border-gray-700 z-10"
                >
                  <div class="p-1">
                    <Link
                      :href="route('admin.portfolio.show', portfolio.id)"
                      class="flex items-center px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded"
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
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        />
                      </svg>
                      View Details
                    </Link>
                    <Link
                      :href="route('admin.portfolio.edit', portfolio.id)"
                      class="flex items-center px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded"
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
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                        />
                      </svg>
                      Edit Project
                    </Link>
                    <button
                      @click="confirmDelete(portfolio)"
                      class="flex items-center w-full px-3 py-2 text-sm text-red-400 hover:bg-gray-700 rounded"
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
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"
                        />
                      </svg>
                      Delete Project
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Project Content -->
          <div class="p-4">
            <div class="flex items-start justify-between mb-2">
              <h3 class="text-lg font-semibold text-white truncate flex-1">
                {{ portfolio.title }}
              </h3>
            </div>

            <p class="text-gray-400 text-sm mb-3 line-clamp-2">
              {{ portfolio.description }}
            </p>

            <!-- Technologies -->
            <div class="mb-3">
              <div class="flex flex-wrap gap-1">
                <span
                  v-for="tech in getTechnologies(portfolio.technologies)"
                  :key="tech"
                  class="px-2 py-1 bg-gray-700 text-gray-300 text-xs rounded"
                >
                  {{ tech }}
                </span>
              </div>
            </div>

            <!-- Project Info -->
            <div
              class="flex items-center justify-between text-sm text-gray-400"
            >
              <div class="flex items-center">
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
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
                {{ formatDate(portfolio.project_date) }}
              </div>
              <div class="flex items-center">
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
                {{ formatDate(portfolio.created_at) }}
              </div>
            </div>

            <!-- GitHub Link -->
            <div v-if="portfolio.github_url" class="mt-3">
              <a
                :href="portfolio.github_url"
                target="_blank"
                class="inline-flex items-center text-blue-400 hover:text-blue-300 text-sm"
              >
                <svg
                  class="w-4 h-4 mr-1"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z"
                    clip-rule="evenodd"
                  />
                </svg>
                View Code
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <svg
          class="mx-auto h-24 w-24 text-gray-400"
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
        <h3 class="mt-4 text-lg font-medium text-white">No projects found</h3>
        <p class="mt-2 text-gray-400">
          {{
            hasActiveFilters
              ? "Try adjusting your filters"
              : "Get started by creating your first project."
          }}
        </p>
        <div class="mt-6">
          <Link
            v-if="!hasActiveFilters"
            :href="route('admin.portfolio.create')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
          >
            Create Project
          </Link>
          <button
            v-else
            @click="clearAllFilters"
            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors"
          >
            Clear Filters
          </button>
        </div>
      </div>

      <!-- Pagination -->
      <div
        v-if="portfolios.total > portfolios.per_page"
        class="px-6 py-4 border-t border-gray-700"
      >
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-400">
            Showing {{ portfolios.from }} to {{ portfolios.to }} of
            {{ portfolios.total }} results
          </div>
          <div class="flex space-x-1">
            <Link
              v-for="link in portfolios.links"
              :key="link.label"
              :href="link.url"
              v-html="link.label"
              class="px-3 py-2 text-sm border border-gray-600 rounded"
              :class="{
                'bg-blue-600 text-white border-blue-600': link.active,
                'text-gray-400 hover:text-white hover:border-gray-500':
                  !link.active && link.url,
                'text-gray-600 cursor-not-allowed': !link.url,
              }"
            />
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <ConfirmDialog
        :show="showDeleteModal"
        title="Delete Project"
        :message="`Are you sure you want to delete '${selectedProject?.title}'? This action cannot be undone.`"
        confirm-text="Delete"
        cancel-text="Cancel"
        type="danger"
        @confirm="deleteProject"
        @cancel="showDeleteModal = false"
      />
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import FormInput from "@/Components/Form/FormInput.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";
import ConfirmDialog from "@/Components/UI/ConfirmDialog.vue";

const props = defineProps({
  portfolios: Object,
  filters: Object,
});

const showDeleteModal = ref(false);
const selectedProject = ref(null);
const activeActions = ref(null);

const filters = ref({
  search: props.filters?.search || "",
  type: props.filters?.type || "",
  sort: props.filters?.sort || "latest",
});

const projectTypeOptions = computed(() => {
  const types = [...new Set(props.portfolios.data.map((p) => p.project_type))];
  return types.map((type) => ({ value: type, label: type }));
});

const sortOptions = ref([
  { value: "latest", label: "Latest First" },
  { value: "oldest", label: "Oldest First" },
  { value: "title", label: "Title A-Z" },
  { value: "type", label: "Type" },
]);

const hasActiveFilters = computed(() => {
  return (
    filters.value.search ||
    filters.value.type ||
    filters.value.sort !== "latest"
  );
});

const getTechnologies = (technologies) => {
  return technologies
    ? technologies
        .split(",")
        .map((tech) => tech.trim())
        .slice(0, 3)
    : [];
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const toggleActions = (portfolioId) => {
  activeActions.value =
    activeActions.value === portfolioId ? null : portfolioId;
};

const handleSearch = () => {
  router.get(route("admin.portfolio.index"), filters.value, {
    preserveState: true,
  });
};

const handleFilter = () => {
  router.get(route("admin.portfolio.index"), filters.value, {
    preserveState: true,
  });
};

const clearFilter = (filterName) => {
  filters.value[filterName] = filterName === "sort" ? "latest" : "";
  handleFilter();
};

const clearAllFilters = () => {
  filters.value = {
    search: "",
    type: "",
    sort: "latest",
  };
  handleFilter();
};

const confirmDelete = (project) => {
  selectedProject.value = project;
  showDeleteModal.value = true;
  activeActions.value = null;
};

const deleteProject = () => {
  router.delete(route("admin.portfolio.destroy", selectedProject.value.id), {
    onSuccess: () => {
      showDeleteModal.value = false;
      selectedProject.value = null;
    },
  });
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
