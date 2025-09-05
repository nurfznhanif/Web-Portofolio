<!-- Components/UI/DataTable.vue -->
<template>
  <div class="bg-gray-800 rounded-lg overflow-hidden">
    <!-- Table Header with Search and Filters -->
    <div v-if="showHeader" class="p-4 border-b border-gray-700">
      <div
        class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0"
      >
        <!-- Search -->
        <div v-if="searchable" class="w-full sm:w-64">
          <FormInput
            v-model="searchQuery"
            placeholder="Search..."
            @update:modelValue="handleSearch"
          />
        </div>

        <!-- Actions Slot -->
        <div class="flex items-center space-x-3">
          <slot name="actions"></slot>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full">
        <!-- Header -->
        <thead class="bg-gray-700">
          <tr>
            <th
              v-for="column in columns"
              :key="column.key"
              class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-600"
              @click="handleSort(column.key)"
            >
              <div class="flex items-center space-x-1">
                <span>{{ column.label }}</span>
                <svg
                  v-if="sortKey === column.key"
                  class="w-4 h-4"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    v-if="sortDirection === 'asc'"
                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"
                  />
                  <path
                    v-else
                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"
                  />
                </svg>
              </div>
            </th>
          </tr>
        </thead>

        <!-- Body -->
        <tbody class="divide-y divide-gray-700">
          <tr
            v-for="(item, index) in paginatedData"
            :key="item.id || index"
            class="hover:bg-gray-700/50 transition-colors"
          >
            <td
              v-for="column in columns"
              :key="column.key"
              class="px-6 py-4 whitespace-nowrap"
            >
              <slot
                :name="`cell-${column.key}`"
                :item="item"
                :value="getNestedValue(item, column.key)"
              >
                <span class="text-sm text-gray-300">
                  {{ getNestedValue(item, column.key) }}
                </span>
              </slot>
            </td>
          </tr>

          <!-- Empty State -->
          <tr v-if="paginatedData.length === 0">
            <td :colspan="columns.length" class="px-6 py-12 text-center">
              <div class="text-gray-400">
                <svg
                  class="mx-auto h-12 w-12 mb-4"
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
                <p class="text-lg font-medium">No data found</p>
                <p class="text-sm">{{ emptyMessage }}</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div
      v-if="showPagination && totalPages > 1"
      class="px-6 py-4 border-t border-gray-700"
    >
      <div class="flex items-center justify-between">
        <!-- Info -->
        <div class="text-sm text-gray-400">
          Showing {{ startIndex + 1 }} to {{ endIndex }} of
          {{ filteredData.length }} results
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center space-x-2">
          <button
            @click="currentPage = 1"
            :disabled="currentPage === 1"
            class="px-3 py-1 text-sm border border-gray-600 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700"
          >
            First
          </button>

          <button
            @click="currentPage--"
            :disabled="currentPage === 1"
            class="px-3 py-1 text-sm border border-gray-600 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700"
          >
            Previous
          </button>

          <span class="px-3 py-1 text-sm text-gray-300">
            Page {{ currentPage }} of {{ totalPages }}
          </span>

          <button
            @click="currentPage++"
            :disabled="currentPage === totalPages"
            class="px-3 py-1 text-sm border border-gray-600 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700"
          >
            Next
          </button>

          <button
            @click="currentPage = totalPages"
            :disabled="currentPage === totalPages"
            class="px-3 py-1 text-sm border border-gray-600 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700"
          >
            Last
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import FormInput from "@/Components/Form/FormInput.vue";

const props = defineProps({
  data: {
    type: Array,
    default: () => [],
  },
  columns: {
    type: Array,
    required: true,
  },
  searchable: {
    type: Boolean,
    default: true,
  },
  showHeader: {
    type: Boolean,
    default: true,
  },
  showPagination: {
    type: Boolean,
    default: true,
  },
  perPage: {
    type: Number,
    default: 10,
  },
  emptyMessage: {
    type: String,
    default: "No items to display",
  },
});

const emit = defineEmits(["search", "sort"]);

// Reactive state
const searchQuery = ref("");
const sortKey = ref("");
const sortDirection = ref("asc");
const currentPage = ref(1);

// Computed properties
const filteredData = computed(() => {
  let filtered = [...props.data];

  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter((item) => {
      return props.columns.some((column) => {
        const value = getNestedValue(item, column.key);
        return value && value.toString().toLowerCase().includes(query);
      });
    });
  }

  // Apply sorting
  if (sortKey.value) {
    filtered.sort((a, b) => {
      const aVal = getNestedValue(a, sortKey.value);
      const bVal = getNestedValue(b, sortKey.value);

      if (aVal < bVal) return sortDirection.value === "asc" ? -1 : 1;
      if (aVal > bVal) return sortDirection.value === "asc" ? 1 : -1;
      return 0;
    });
  }

  return filtered;
});

const totalPages = computed(() => {
  return Math.ceil(filteredData.value.length / props.perPage);
});

const startIndex = computed(() => {
  return (currentPage.value - 1) * props.perPage;
});

const endIndex = computed(() => {
  return Math.min(startIndex.value + props.perPage, filteredData.value.length);
});

const paginatedData = computed(() => {
  return filteredData.value.slice(startIndex.value, endIndex.value);
});

// Methods
const getNestedValue = (obj, path) => {
  return path.split(".").reduce((o, p) => o && o[p], obj);
};

const handleSearch = () => {
  currentPage.value = 1;
  emit("search", searchQuery.value);
};

const handleSort = (key) => {
  if (sortKey.value === key) {
    sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
  } else {
    sortKey.value = key;
    sortDirection.value = "asc";
  }

  emit("sort", { key: sortKey.value, direction: sortDirection.value });
};

// Watch for page changes to reset to first page when data changes
watch(
  () => props.data,
  () => {
    currentPage.value = 1;
  }
);
</script>
