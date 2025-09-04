<template>
  <DashboardLayout title="Experience Management">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Work Experience</h1>
        <Link
          :href="route('admin.experience.create')"
          class="px-4 py-2 bg-red-900 hover:bg-red-800 text-white rounded-lg transition-colors"
        >
          Add New Experience
        </Link>
      </div>

      <!-- Experience List -->
      <div class="space-y-4">
        <div
          v-for="experience in experiences.data"
          :key="experience.id"
          class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:bg-gray-700/50 transition-colors"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <div class="flex items-center space-x-3 mb-2">
                <h3 class="text-lg font-semibold text-white">
                  {{ experience.position }}
                </h3>
                <span
                  v-if="experience.is_current"
                  class="px-2 py-1 bg-green-900/30 text-green-300 rounded-full text-xs"
                >
                  Current
                </span>
              </div>
              <p class="text-red-400 font-medium mb-2">
                {{ experience.organization }}
              </p>
              <p class="text-gray-300 mb-3 leading-relaxed">
                {{ experience.description }}
              </p>
              <p class="text-sm text-gray-400">
                {{ formatDate(experience.start_date) }} -
                {{
                  experience.is_current
                    ? "Present"
                    : formatDate(experience.end_date)
                }}
              </p>
            </div>

            <div class="flex items-center space-x-2 ml-4">
              <Link
                :href="route('admin.experience.edit', experience.id)"
                class="text-green-400 hover:text-green-300 transition-colors p-2"
                title="Edit"
              >
                ✏️
              </Link>
              <button
                @click="confirmDelete(experience)"
                class="text-red-400 hover:text-red-300 transition-colors p-2"
                title="Delete"
              >
                🗑️
              </button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div
          v-if="experiences.data.length === 0"
          class="text-center py-12 bg-gray-800 border border-gray-700 rounded-lg"
        >
          <div class="text-6xl mb-4">🏢</div>
          <h3 class="text-lg font-semibold text-white mb-2">
            No Work Experience
          </h3>
          <p class="text-gray-400 mb-4">
            Start by adding your first work experience.
          </p>
          <Link
            :href="route('admin.experience.create')"
            class="px-4 py-2 bg-red-900 hover:bg-red-800 text-white rounded-lg transition-colors"
          >
            Add Experience
          </Link>
        </div>
      </div>

      <!-- Pagination -->
      <div
        v-if="experiences.links && experiences.links.length > 3"
        class="flex items-center justify-center space-x-1"
      >
        <Link
          v-for="link in experiences.links"
          :key="link.label"
          :href="link.url"
          :class="[
            'px-3 py-2 text-sm rounded',
            link.active
              ? 'bg-red-900 text-white'
              : 'bg-gray-800 text-gray-300 hover:bg-gray-700',
          ]"
          v-html="link.label"
        >
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
          Are you sure you want to delete this experience at "{{
            experienceToDelete?.organization
          }}"? This action cannot be undone.
        </p>
        <div class="flex justify-end space-x-4">
          <button
            @click="closeDeleteModal"
            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded transition-colors"
          >
            Cancel
          </button>
          <button
            @click="deleteExperience"
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
  experiences: Object,
});

const showDeleteModal = ref(false);
const experienceToDelete = ref(null);

const confirmDelete = (experience) => {
  experienceToDelete.value = experience;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  experienceToDelete.value = null;
};

const deleteExperience = () => {
  if (experienceToDelete.value) {
    router.delete(
      route("admin.experience.destroy", experienceToDelete.value.id)
    );
    closeDeleteModal();
  }
};

const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("id-ID", {
    year: "numeric",
    month: "long",
  });
};
</script>
