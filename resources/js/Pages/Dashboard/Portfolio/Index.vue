<template>
  <DashboardLayout title="Portfolio Management">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Portfolio Projects</h1>
        <Link
          :href="route('admin.portfolio.create')"
          class="px-4 py-2 bg-red-900 hover:bg-red-800 text-white rounded-lg transition-colors"
        >
          Add New Portfolio
        </Link>
      </div>

      <!-- Portfolio List -->
      <div
        class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden"
      >
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-700">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                >
                  Project
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                >
                  Type
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                >
                  Technologies
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                >
                  Date
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
              <tr
                v-for="portfolio in portfolios.data"
                :key="portfolio.id"
                class="hover:bg-gray-700/50"
              >
                <td class="px-6 py-4">
                  <div>
                    <h3 class="text-sm font-medium text-white">
                      {{ portfolio.title }}
                    </h3>
                    <p class="text-sm text-gray-400 line-clamp-2">
                      {{ truncate(portfolio.description, 100) }}
                    </p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="px-2 py-1 bg-red-900/30 text-red-300 rounded-full text-xs"
                  >
                    {{ portfolio.project_type }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-wrap gap-1">
                    <span
                      v-for="tech in getTechnologies(
                        portfolio.technologies
                      ).slice(0, 3)"
                      :key="tech"
                      class="px-2 py-1 bg-gray-600 text-gray-300 rounded text-xs"
                    >
                      {{ tech }}
                    </span>
                    <span
                      v-if="getTechnologies(portfolio.technologies).length > 3"
                      class="px-2 py-1 bg-gray-600 text-gray-300 rounded text-xs"
                    >
                      +{{ getTechnologies(portfolio.technologies).length - 3 }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-400">
                  {{ formatDate(portfolio.project_date) }}
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-2">
                    <Link
                      :href="route('admin.portfolio.show', portfolio.id)"
                      class="text-blue-400 hover:text-blue-300 transition-colors"
                      title="View"
                    >
                      👁️
                    </Link>
                    <Link
                      :href="route('admin.portfolio.edit', portfolio.id)"
                      class="text-green-400 hover:text-green-300 transition-colors"
                      title="Edit"
                    >
                      ✏️
                    </Link>
                    <button
                      @click="confirmDelete(portfolio)"
                      class="text-red-400 hover:text-red-300 transition-colors"
                      title="Delete"
                    >
                      🗑️
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div
          v-if="portfolios.links && portfolios.links.length > 3"
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
                :class="[
                  'px-3 py-2 text-sm rounded',
                  link.active
                    ? 'bg-red-900 text-white'
                    : 'bg-gray-700 text-gray-300 hover:bg-gray-600',
                ]"
                v-html="link.label"
              >
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-if="portfolios.data.length === 0"
        class="text-center py-12 bg-gray-800 border border-gray-700 rounded-lg"
      >
        <div class="text-6xl mb-4">💼</div>
        <h3 class="text-lg font-semibold text-white mb-2">
          No Portfolio Projects
        </h3>
        <p class="text-gray-400 mb-4">
          Start by adding your first portfolio project.
        </p>
        <Link
          :href="route('admin.portfolio.create')"
          class="px-4 py-2 bg-red-900 hover:bg-red-800 text-white rounded-lg transition-colors"
        >
          Add Portfolio Project
        </Link>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      @click="closeDeleteModal"
    >
      <div
        class="bg-gray-800 border border-gray-700 rounded-lg p-6 max-w-md w-full mx-4"
        @click.stop
      >
        <h3 class="text-lg font-semibold text-white mb-4">Confirm Delete</h3>
        <p class="text-gray-400 mb-6">
          Are you sure you want to delete "{{ portfolioToDelete?.title }}"? This
          action cannot be undone.
        </p>
        <div class="flex justify-end space-x-4">
          <button
            @click="closeDeleteModal"
            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded transition-colors"
          >
            Cancel
          </button>
          <button
            @click="deletePortfolio"
            class="px-4 py-2 bg-red-900 hover:bg-red-800 text-white rounded transition-colors"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

defineProps({
  portfolios: Object,
});

const showDeleteModal = ref(false);
const portfolioToDelete = ref(null);

const confirmDelete = (portfolio) => {
  portfolioToDelete.value = portfolio;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  portfolioToDelete.value = null;
};

const deletePortfolio = () => {
  if (portfolioToDelete.value) {
    router.delete(route("admin.portfolio.destroy", portfolioToDelete.value.id));
    closeDeleteModal();
  }
};

const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("id-ID", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const truncate = (text, length) => {
  if (!text) return "";
  return text.length > length ? text.substring(0, length) + "..." : text;
};

const getTechnologies = (technologies) => {
  if (!technologies) return [];
  return technologies.split(", ").map((tech) => tech.trim());
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
