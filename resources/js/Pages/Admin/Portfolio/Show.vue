<!-- Admin/Portfolio/Show.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">{{ portfolio.title }}</h1>
          <p class="text-gray-400">Project Details</p>
        </div>
        <div class="flex space-x-3">
          <Link
            :href="route('admin.portfolio.edit', portfolio.id)"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center"
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
          <Link
            :href="route('admin.portfolio.index')"
            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors"
          >
            Back to List
          </Link>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Project Image -->
          <div v-if="portfolio.image" class="bg-gray-800 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-white mb-4">
              Project Preview
            </h2>
            <img
              :src="portfolio.image"
              :alt="portfolio.title"
              class="w-full rounded-lg shadow-lg"
            />
          </div>

          <!-- Description -->
          <div class="bg-gray-800 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Description</h2>
            <div class="text-gray-300 whitespace-pre-wrap leading-relaxed">
              {{ portfolio.description }}
            </div>
          </div>

          <!-- Challenges & Solutions -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Challenges -->
            <div v-if="portfolio.challenges" class="bg-gray-800 rounded-lg p-6">
              <h2
                class="text-lg font-semibold text-white mb-4 flex items-center"
              >
                <span class="text-red-400 mr-2">⚠️</span>
                Challenges
              </h2>
              <div class="text-gray-300 whitespace-pre-wrap leading-relaxed">
                {{ portfolio.challenges }}
              </div>
            </div>

            <!-- Solutions -->
            <div v-if="portfolio.solutions" class="bg-gray-800 rounded-lg p-6">
              <h2
                class="text-lg font-semibold text-white mb-4 flex items-center"
              >
                <span class="text-green-400 mr-2">💡</span>
                Solutions
              </h2>
              <div class="text-gray-300 whitespace-pre-wrap leading-relaxed">
                {{ portfolio.solutions }}
              </div>
            </div>
          </div>

          <!-- Technologies Used -->
          <div class="bg-gray-800 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-white mb-4">
              Technologies Used
            </h2>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="tech in technologies"
                :key="tech"
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
              >
                {{ tech }}
              </span>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Project Info -->
          <div class="bg-gray-800 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-white mb-4">
              Project Information
            </h2>
            <div class="space-y-4">
              <!-- Project Type -->
              <div>
                <dt class="text-sm font-medium text-gray-400">Project Type</dt>
                <dd class="mt-1 text-sm text-white">
                  {{ portfolio.project_type }}
                </dd>
              </div>

              <!-- Project Date -->
              <div>
                <dt class="text-sm font-medium text-gray-400">Project Date</dt>
                <dd class="mt-1 text-sm text-white">
                  {{ formatDate(portfolio.project_date) }}
                </dd>
              </div>

              <!-- GitHub Link -->
              <div v-if="portfolio.github_url">
                <dt class="text-sm font-medium text-gray-400">Repository</dt>
                <dd class="mt-1">
                  <a
                    :href="portfolio.github_url"
                    target="_blank"
                    class="text-blue-400 hover:text-blue-300 text-sm flex items-center"
                  >
                    <svg
                      class="w-4 h-4 mr-2"
                      fill="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        d="M12 0C5.374 0 0 5.373 0 12 0 17.302 3.438 21.8 8.207 23.387c.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"
                      />
                    </svg>
                    View on GitHub
                    <svg
                      class="w-3 h-3 ml-1"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                      />
                    </svg>
                  </a>
                </dd>
              </div>

              <!-- Created/Updated -->
              <div>
                <dt class="text-sm font-medium text-gray-400">Created</dt>
                <dd class="mt-1 text-sm text-white">
                  {{ formatDateTime(portfolio.created_at) }}
                </dd>
              </div>

              <div v-if="portfolio.updated_at !== portfolio.created_at">
                <dt class="text-sm font-medium text-gray-400">Last Updated</dt>
                <dd class="mt-1 text-sm text-white">
                  {{ formatDateTime(portfolio.updated_at) }}
                </dd>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="bg-gray-800 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Quick Actions</h2>
            <div class="space-y-3">
              <Link
                :href="route('admin.portfolio.edit', portfolio.id)"
                class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
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
                @click="confirmDelete"
                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
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
                Delete Project
              </button>

              <a
                v-if="portfolio.github_url"
                :href="portfolio.github_url"
                target="_blank"
                class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
              >
                <svg
                  class="w-4 h-4 mr-2"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M12 0C5.374 0 0 5.373 0 12 0 17.302 3.438 21.8 8.207 23.387c.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"
                  />
                </svg>
                Open Repository
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <ConfirmDialog
        :show="showDeleteModal"
        title="Delete Project"
        :message="`Are you sure you want to delete '${portfolio.title}'? This action cannot be undone.`"
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
import ConfirmDialog from "@/Components/UI/ConfirmDialog.vue";

const props = defineProps({
  portfolio: Object,
});

const showDeleteModal = ref(false);

const technologies = computed(() => {
  return props.portfolio.technologies
    ? props.portfolio.technologies.split(", ").map((tech) => tech.trim())
    : [];
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

const formatDateTime = (date) => {
  return new Date(date).toLocaleString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const confirmDelete = () => {
  showDeleteModal.value = true;
};

const deleteProject = () => {
  router.delete(route("admin.portfolio.destroy", props.portfolio.id), {
    onSuccess: () => {
      router.visit(route("admin.portfolio.index"));
    },
  });
};
</script>
