<script setup>
import { ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import FormInput from "@/Components/Form/FormInput.vue";
import FormTextarea from "@/Components/Form/FormTextarea.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";

// Props
const props = defineProps({
  skill: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    default: () => [],
  },
});

// Form data
const form = useForm({
  name: props.skill.name,
  category: props.skill.category,
  level: props.skill.level,
  description: props.skill.description || "",
  proficiency: props.skill.proficiency,
  order: props.skill.order,
});

// Reactive state
const isSubmitting = ref(false);
const previewMode = ref(false);
const showCustomCategory = ref(false);
const customCategory = ref("");
const showDeleteModal = ref(false);

// Delete form
const deleteForm = useForm({});

// Level options
const levelOptions = [
  { value: "", label: "Select Skill Level" },
  { value: "pemula", label: "Beginner" },
  { value: "menengah", label: "Intermediate" },
  { value: "mahir", label: "Advanced" },
];

// Category options
const categoryOptions = computed(() => {
  const defaultOptions = [
    { value: "", label: "Select Category" },
    ...props.categories.map((cat) => ({ value: cat, label: cat })),
    { value: "custom", label: "Add New Category..." },
  ];
  return defaultOptions;
});

// Computed properties
const isFormValid = computed(() => {
  return (
    form.name &&
    (form.category || customCategory.value) &&
    form.level &&
    form.proficiency >= 0 &&
    form.proficiency <= 100
  );
});

const finalCategory = computed(() => {
  return showCustomCategory.value ? customCategory.value : form.category;
});

const hasChanges = computed(() => {
  return form.isDirty || showCustomCategory.value;
});

// Methods
const submitForm = () => {
  if (!isFormValid.value) return;

  // Set final category
  if (showCustomCategory.value) {
    form.category = customCategory.value;
  }

  isSubmitting.value = true;

  form.patch(`/admin/skills/${props.skill.id}`, {
    onSuccess: () => {
      // Redirect handled by controller
    },
    onError: () => {
      isSubmitting.value = false;
    },
    onFinish: () => {
      isSubmitting.value = false;
    },
  });
};

const resetForm = () => {
  form.reset();
  showCustomCategory.value = false;
  customCategory.value = "";
};

const handleCategoryChange = () => {
  if (form.category === "custom") {
    showCustomCategory.value = true;
    form.category = "";
  } else {
    showCustomCategory.value = false;
    customCategory.value = "";
  }
};

const confirmDelete = () => {
  showDeleteModal.value = true;
};

const deleteSkill = () => {
  deleteForm.delete(`/admin/skills/${props.skill.id}`, {
    onSuccess: () => {
      // Redirect handled by controller
    },
    onError: () => {
      // Handle error
    },
  });
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
};

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

const getProficiencyLabel = (proficiency) => {
  if (proficiency >= 90) return "Expert";
  if (proficiency >= 80) return "Very Good";
  if (proficiency >= 70) return "Good";
  if (proficiency >= 60) return "Fair";
  if (proficiency >= 40) return "Basic";
  return "Learning";
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Skills", href: "/admin/skills" },
  { name: "Edit", href: `/admin/skills/${props.skill.id}/edit`, current: true },
];
</script>

<template>
  <Head :title="`Edit ${skill.name}`" />

  <DashboardLayout :title="`Edit Skill`" :breadcrumbs="breadcrumbs">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header Section -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="flex items-center justify-between">
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
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                />
              </svg>
              Edit Skill
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Update skill details for: {{ skill.name }} ({{ skill.category }})
            </p>
          </div>

          <div class="flex items-center space-x-3">
            <button
              @click="previewMode = !previewMode"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
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
              {{ previewMode ? "Edit Mode" : "Preview" }}
            </button>
          </div>
        </div>

        <!-- Changes Indicator -->
        <div
          v-if="hasChanges"
          class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg"
        >
          <div class="flex items-center">
            <svg
              class="h-5 w-5 text-amber-400 mr-2"
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
            <span
              class="text-sm font-medium text-amber-800 dark:text-amber-200"
            >
              You have unsaved changes
            </span>
          </div>
        </div>
      </div>

      <!-- Preview Mode -->
      <div
        v-if="previewMode"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h2
          class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center"
        >
          <svg
            class="w-5 h-5 mr-2 text-indigo-600"
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
          </svg>
          Preview
        </h2>

        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6">
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ form.name }}
              </h3>
              <p
                class="text-sm text-indigo-600 dark:text-indigo-400 font-medium"
              >
                {{ finalCategory }}
              </p>
            </div>
            <span
              v-if="form.level"
              :class="[
                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                getLevelColor(form.level),
              ]"
            >
              {{ getLevelLabel(form.level) }}
            </span>
          </div>

          <!-- Proficiency Bar -->
          <div class="mb-4">
            <div class="flex items-center justify-between mb-1">
              <span
                class="text-sm font-medium text-gray-700 dark:text-gray-300"
              >
                Proficiency - {{ getProficiencyLabel(form.proficiency) }}
              </span>
              <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ form.proficiency }}%
              </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div
                :class="[
                  'h-2 rounded-full transition-all duration-300',
                  getProficiencyColor(form.proficiency),
                ]"
                :style="{ width: form.proficiency + '%' }"
              ></div>
            </div>
          </div>

          <!-- Description -->
          <div v-if="form.description">
            <p class="text-sm text-gray-600 dark:text-gray-300">
              {{ form.description }}
            </p>
          </div>
          <div v-else>
            <p class="text-sm text-gray-400 italic">
              No description provided...
            </p>
          </div>
        </div>
      </div>

      <!-- Form Section -->
      <form @submit.prevent="submitForm" v-show="!previewMode">
        <!-- Basic Information -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2
            class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center"
          >
            <svg
              class="w-5 h-5 mr-2 text-indigo-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            Basic Information
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormInput
              v-model="form.name"
              label="Skill Name"
              placeholder="e.g., PHP, JavaScript, Leadership"
              required
              :error="form.errors.name"
              help="Name of the skill or technology"
              :max-length="255"
              show-char-count
            />

            <div>
              <FormSelect
                v-model="form.category"
                @change="handleCategoryChange"
                label="Category"
                placeholder="Select Category"
                required
                :error="form.errors.category"
                help="Choose an existing category or create a new one"
                :options="categoryOptions"
              />

              <!-- Custom Category Input -->
              <div v-if="showCustomCategory" class="mt-3">
                <FormInput
                  v-model="customCategory"
                  label="New Category Name"
                  placeholder="e.g., New Technology, Soft Skills"
                  required
                  help="Enter the name for the new category"
                  :max-length="255"
                />
              </div>
            </div>

            <FormSelect
              v-model="form.level"
              label="Skill Level"
              placeholder="Select Level"
              required
              :error="form.errors.level"
              help="Your current proficiency level in this skill"
              :options="levelOptions"
            />

            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Proficiency
                <span class="text-red-400">*</span>
              </label>

              <div class="space-y-3">
                <!-- Slider -->
                <div class="relative">
                  <input
                    v-model="form.proficiency"
                    type="range"
                    min="0"
                    max="100"
                    step="5"
                    class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer slider"
                  />
                  <div
                    class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1"
                  >
                    <span>0%</span>
                    <span>25%</span>
                    <span>50%</span>
                    <span>75%</span>
                    <span>100%</span>
                  </div>
                </div>

                <!-- Proficiency Display -->
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <div
                      class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2"
                    >
                      <div
                        :class="[
                          'h-2 rounded-full transition-all duration-300',
                          getProficiencyColor(form.proficiency),
                        ]"
                        :style="{ width: form.proficiency + '%' }"
                      ></div>
                    </div>
                    <span
                      class="text-sm font-medium text-gray-900 dark:text-white"
                    >
                      {{ form.proficiency }}%
                    </span>
                  </div>
                  <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ getProficiencyLabel(form.proficiency) }}
                  </span>
                </div>
              </div>

              <p
                v-if="form.errors.proficiency"
                class="mt-1 text-sm text-red-400"
              >
                {{ form.errors.proficiency }}
              </p>
              <p class="mt-1 text-sm text-gray-400">
                Rate your skill level from 0-100%
              </p>
            </div>

            <div class="md:col-span-2">
              <FormInput
                v-model="form.order"
                label="Display Order"
                type="number"
                min="0"
                :error="form.errors.order"
                help="Lower numbers appear first (0 = highest priority)"
                placeholder="0"
              />
            </div>
          </div>
        </div>

        <!-- Description -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2
            class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center"
          >
            <svg
              class="w-5 h-5 mr-2 text-indigo-600"
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
            Description & Details
          </h2>

          <FormTextarea
            v-model="form.description"
            label="Description"
            placeholder="Describe your experience with this skill, specific tools you've used, projects you've worked on, etc..."
            :rows="4"
            :error="form.errors.description"
            help="Provide additional details about your experience with this skill (Optional)"
            :max-length="500"
          />
        </div>

        <!-- Form Actions -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
          >
            <div class="flex items-center space-x-4">
              <button
                type="button"
                @click="resetForm"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
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
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                  />
                </svg>
                Reset Changes
              </button>

              <span
                v-if="hasChanges"
                class="text-sm text-amber-600 dark:text-amber-400 flex items-center"
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
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"
                  />
                </svg>
                Unsaved changes
              </span>
            </div>

            <div class="flex items-center space-x-3">
              <button
                type="button"
                @click="$inertia.visit('/admin/skills')"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
              >
                Cancel
              </button>

              <button
                type="submit"
                :disabled="!isFormValid || isSubmitting || !hasChanges"
                :class="[
                  'inline-flex items-center px-6 py-2 text-sm font-medium text-white rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2',
                  isFormValid && !isSubmitting && hasChanges
                    ? 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500'
                    : 'bg-gray-400 cursor-not-allowed',
                ]"
              >
                <svg
                  v-if="isSubmitting"
                  class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                  ></circle>
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  ></path>
                </svg>
                <svg
                  v-else
                  class="w-4 h-4 mr-2"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
                {{ isSubmitting ? "Updating..." : "Update Skill" }}
              </button>
            </div>
          </div>

          <!-- Form Validation Summary -->
          <div
            v-if="Object.keys(form.errors).length > 0"
            class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg"
          >
            <div class="flex">
              <div class="flex-shrink-0">
                <svg
                  class="h-5 w-5 text-red-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                  Please fix the following errors:
                </h3>
                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                  <ul class="list-disc list-inside space-y-1">
                    <li v-for="(error, field) in form.errors" :key="field">
                      {{ error }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>

      <!-- Delete Section -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-red-200 dark:border-red-800 p-6"
      >
        <div class="flex items-start justify-between">
          <div>
            <h3
              class="text-lg font-semibold text-red-900 dark:text-red-100 flex items-center"
            >
              <svg
                class="w-5 h-5 mr-2 text-red-600"
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
              Danger Zone
            </h3>
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">
              Permanently delete this skill entry. This action cannot be undone.
            </p>
          </div>

          <button
            @click="confirmDelete"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors duration-200"
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
            Delete Skill
          </button>
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
                <span class="font-semibold">{{ skill.name }}</span>
                from the
                <span class="font-semibold">{{ skill.category }}</span>
                category? This action cannot be undone.
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
                  @click="deleteSkill"
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

<style scoped>
/* Custom slider styles */
.slider::-webkit-slider-thumb {
  appearance: none;
  height: 20px;
  width: 20px;
  border-radius: 50%;
  background: #4f46e5;
  cursor: pointer;
  border: 2px solid #ffffff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.slider::-moz-range-thumb {
  height: 20px;
  width: 20px;
  border-radius: 50%;
  background: #4f46e5;
  cursor: pointer;
  border: 2px solid #ffffff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
</style>
