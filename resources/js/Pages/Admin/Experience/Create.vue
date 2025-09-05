<!-- Admin/Experience/Create.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Add New Experience</h1>
          <p class="text-gray-400">
            Add work, education, or volunteer experience
          </p>
        </div>
        <Link
          :href="route('admin.experience.index')"
          class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors"
        >
          Back to List
        </Link>
      </div>

      <!-- Form -->
      <div class="bg-gray-800 rounded-lg p-6">
        <form @submit.prevent="submit" class="space-y-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
              <!-- Experience Type -->
              <FormSelect
                v-model="form.type"
                label="Experience Type"
                placeholder="Select type"
                :options="experienceTypeOptions"
                required
                :error="form.errors.type"
              />

              <!-- Position/Title -->
              <FormInput
                v-model="form.position"
                label="Position/Title"
                placeholder="e.g., Software Engineer, Bachelor of Computer Science"
                required
                :error="form.errors.position"
              />

              <!-- Organization -->
              <FormInput
                v-model="form.organization"
                label="Organization/Institution"
                placeholder="e.g., Tech Company Inc., University Name"
                required
                :error="form.errors.organization"
              />

              <!-- Location -->
              <FormInput
                v-model="form.location"
                label="Location"
                placeholder="e.g., Jakarta, Indonesia"
                required
                :error="form.errors.location"
              />

              <!-- Start Date -->
              <FormInput
                v-model="form.start_date"
                label="Start Date"
                type="date"
                required
                :error="form.errors.start_date"
              />

              <!-- End Date -->
              <div class="space-y-2">
                <FormInput
                  v-model="form.end_date"
                  label="End Date"
                  type="date"
                  :disabled="form.is_current"
                  :error="form.errors.end_date"
                />
                <label class="flex items-center">
                  <input
                    v-model="form.is_current"
                    type="checkbox"
                    class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="ml-2 text-sm text-gray-300"
                    >I currently work/study here</span
                  >
                </label>
              </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
              <!-- Description -->
              <FormTextarea
                v-model="form.description"
                label="Description"
                placeholder="Describe your role, responsibilities, and what you learned..."
                :rows="6"
                required
                :error="form.errors.description"
                :max-length="1000"
              />

              <!-- Key Achievements -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  Key Achievements
                </label>
                <div class="space-y-3">
                  <div
                    v-for="(achievement, index) in form.key_achievements"
                    :key="index"
                    class="flex items-center space-x-2"
                  >
                    <FormInput
                      v-model="form.key_achievements[index]"
                      placeholder="Enter an achievement..."
                      class="flex-1"
                    />
                    <button
                      @click="removeAchievement(index)"
                      type="button"
                      class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-colors"
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
                  <button
                    @click="addAchievement"
                    type="button"
                    class="w-full flex items-center justify-center px-4 py-2 border-2 border-dashed border-gray-600 rounded-lg text-gray-400 hover:border-blue-500 hover:text-blue-400 transition-colors"
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
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                      />
                    </svg>
                    Add Achievement
                  </button>
                </div>
                <p
                  v-if="form.errors.key_achievements"
                  class="mt-1 text-sm text-red-400"
                >
                  {{ form.errors.key_achievements }}
                </p>
              </div>

              <!-- Skills Used -->
              <FormInput
                v-model="form.skills_used"
                label="Skills Used"
                placeholder="e.g., JavaScript, Project Management, Leadership"
                :error="form.errors.skills_used"
                help="Separate skills with commas"
              />
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-4 pt-6 border-t border-gray-700">
            <Link
              :href="route('admin.experience.index')"
              class="px-6 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
            >
              <span v-if="form.processing">Creating...</span>
              <span v-else>Create Experience</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import FormInput from "@/Components/Form/FormInput.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";
import FormTextarea from "@/Components/Form/FormTextarea.vue";

const form = useForm({
  type: "",
  position: "",
  organization: "",
  location: "",
  start_date: "",
  end_date: "",
  is_current: false,
  description: "",
  key_achievements: [""],
  skills_used: "",
});

const experienceTypeOptions = [
  { value: "work", label: "Work Experience" },
  { value: "education", label: "Education" },
  { value: "internship", label: "Internship" },
  { value: "volunteer", label: "Volunteer Work" },
  { value: "freelance", label: "Freelance" },
  { value: "project", label: "Project" },
];

const addAchievement = () => {
  form.key_achievements.push("");
};

const removeAchievement = (index) => {
  if (form.key_achievements.length > 1) {
    form.key_achievements.splice(index, 1);
  }
};

const submit = () => {
  // Filter out empty achievements
  form.key_achievements = form.key_achievements.filter(
    (achievement) => achievement.trim() !== ""
  );

  form.post(route("admin.experience.store"));
};

// Clear end_date when is_current is checked
watch(
  () => form.is_current,
  (newValue) => {
    if (newValue) {
      form.end_date = "";
    }
  }
);
</script>
