<!-- Admin/Experience/Index.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Experience Management</h1>
          <p class="text-gray-400">
            Manage your work and educational experience
          </p>
        </div>
        <Link
          :href="route('admin.experience.create')"
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
          Add Experience
        </Link>
      </div>

      <!-- Experience List -->
      <div class="space-y-4">
        <div
          v-for="experience in experiences"
          :key="experience.id"
          class="bg-gray-800 rounded-lg p-6 hover:bg-gray-700/50 transition-colors"
        >
          <div class="flex items-start justify-between">
            <!-- Experience Info -->
            <div class="flex-1">
              <div class="flex items-center space-x-3 mb-2">
                <h3 class="text-lg font-semibold text-white">
                  {{ experience.position }}
                </h3>
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="getExperienceTypeClass(experience.type)"
                >
                  {{ experience.type }}
                </span>
              </div>

              <div class="flex items-center text-gray-300 mb-2">
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
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                  />
                </svg>
                <span class="font-medium">{{ experience.organization }}</span>
                <span class="mx-2">•</span>
                <span>{{ experience.location }}</span>
              </div>

              <div class="flex items-center text-gray-400 text-sm mb-3">
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
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
                {{ formatDate(experience.start_date) }} -
                {{
                  experience.end_date
                    ? formatDate(experience.end_date)
                    : "Present"
                }}
                <span class="ml-2"
                  >({{
                    calculateDuration(
                      experience.start_date,
                      experience.end_date
                    )
                  }})</span
                >
              </div>

              <p class="text-gray-300 text-sm leading-relaxed">
                {{ experience.description }}
              </p>

              <!-- Key Achievements -->
              <div
                v-if="
                  experience.key_achievements &&
                  experience.key_achievements.length
                "
                class="mt-3"
              >
                <h4 class="text-sm font-medium text-white mb-2">
                  Key Achievements:
                </h4>
                <ul
                  class="list-disc list-inside text-sm text-gray-300 space-y-1"
                >
                  <li
                    v-for="achievement in experience.key_achievements"
                    :key="achievement"
                  >
                    {{ achievement }}
                  </li>
                </ul>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-2 ml-6">
              <Link
                :href="route('admin.experience.edit', experience.id)"
                class="text-blue-400 hover:text-blue-300 p-2 rounded-lg hover:bg-blue-500/10 transition-colors"
                title="Edit"
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
                @click="confirmDelete(experience)"
                class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/10 transition-colors"
                title="Delete"
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
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="experiences.length === 0" class="text-center py-12">
          <svg
            class="mx-auto h-12 w-12 text-gray-400 mb-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V6m8 0H8m8 0l-3-3m-5 3l-3-3"
            />
          </svg>
          <h3 class="text-lg font-medium text-white mb-2">
            No experience added yet
          </h3>
          <p class="text-gray-400 mb-4">
            Start building your professional timeline
          </p>
          <Link
            :href="route('admin.experience.create')"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            Add Your First Experience
          </Link>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <ConfirmDialog
        :show="showDeleteModal"
        title="Delete Experience"
        :message="`Are you sure you want to delete the experience at '${selectedExperience?.organization}'? This action cannot be undone.`"
        confirm-text="Delete"
        cancel-text="Cancel"
        type="danger"
        @confirm="deleteExperience"
        @cancel="showDeleteModal = false"
      />
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import ConfirmDialog from "@/Components/UI/ConfirmDialog.vue";

const props = defineProps({
  experiences: Array,
});

const showDeleteModal = ref(false);
const selectedExperience = ref(null);

const getExperienceTypeClass = (type) => {
  const classes = {
    work: "bg-blue-100 text-blue-800",
    education: "bg-green-100 text-green-800",
    volunteer: "bg-purple-100 text-purple-800",
    internship: "bg-yellow-100 text-yellow-800",
  };
  return classes[type] || "bg-gray-100 text-gray-800";
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
  });
};

const calculateDuration = (startDate, endDate) => {
  const start = new Date(startDate);
  const end = endDate ? new Date(endDate) : new Date();

  const years = end.getFullYear() - start.getFullYear();
  const months = end.getMonth() - start.getMonth();

  let totalMonths = years * 12 + months;
  if (totalMonths < 1) totalMonths = 1;

  if (totalMonths >= 12) {
    const y = Math.floor(totalMonths / 12);
    const m = totalMonths % 12;
    return m > 0 ? `${y}y ${m}m` : `${y}y`;
  }

  return `${totalMonths}m`;
};

const confirmDelete = (experience) => {
  selectedExperience.value = experience;
  showDeleteModal.value = true;
};

const deleteExperience = () => {
  router.delete(
    route("admin.experience.destroy", selectedExperience.value.id),
    {
      onSuccess: () => {
        showDeleteModal.value = false;
        selectedExperience.value = null;
      },
    }
  );
};
</script>
