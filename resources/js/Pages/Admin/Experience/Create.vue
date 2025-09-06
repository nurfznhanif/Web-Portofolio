<script setup>
import { ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import FormInput from "@/Components/Form/FormInput.vue";
import FormTextarea from "@/Components/Form/FormTextarea.vue";

// Form data
const form = useForm({
  position: "",
  organization: "",
  description: "",
  start_date: "",
  end_date: "",
  is_current: false,
  order: 0,
});

// Reactive state
const isSubmitting = ref(false);
const previewMode = ref(false);

// Computed properties
const isFormValid = computed(() => {
  return (
    form.position &&
    form.organization &&
    form.description &&
    form.start_date &&
    (form.is_current || form.end_date)
  );
});

const currentDate = computed(() => {
  return new Date().toISOString().split("T")[0];
});

const hasChanges = computed(() => {
  return form.isDirty;
});

// Methods
const submitForm = () => {
  if (!isFormValid.value) return;

  isSubmitting.value = true;

  form.post("/admin/experience", {
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

const saveDraft = () => {
  // For future implementation
  console.log("Save as draft");
};

const resetForm = () => {
  form.reset();
};

const toggleCurrentPosition = () => {
  if (form.is_current) {
    form.end_date = "";
  }
};

const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Experience", href: "/admin/experience" },
  { name: "Create", href: "/admin/experience/create", current: true },
];
</script>

<template>
  <Head title="Create Experience" />

  <DashboardLayout :title="'Create Experience'" :breadcrumbs="breadcrumbs">
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
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
              </svg>
              Add New Experience
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Create a new experience entry for your professional journey
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
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <div class="flex items-center space-x-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                  {{ form.position || "Position Title" }}
                </h3>
                <span
                  v-if="form.is_current"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400"
                >
                  <span
                    class="w-2 h-2 mr-1 bg-green-500 rounded-full animate-pulse"
                  ></span>
                  Current
                </span>
              </div>
              <p class="text-indigo-600 dark:text-indigo-400 font-medium">
                {{ form.organization || "Organization Name" }}
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
                  {{ formatDate(form.start_date) || "Start Date" }} -
                  {{
                    form.is_current
                      ? "Present"
                      : formatDate(form.end_date) || "End Date"
                  }}
                </span>
              </div>
              <div class="mt-4">
                <p class="text-gray-600 dark:text-gray-300 text-sm leading-6">
                  {{
                    form.description ||
                    "Experience description will appear here..."
                  }}
                </p>
              </div>
            </div>
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
              v-model="form.position"
              label="Position Title"
              placeholder="e.g., Software Developer, Teaching Assistant"
              required
              :error="form.errors.position"
              help="Your job title or role in this position"
              :max-length="255"
              show-char-count
            />

            <FormInput
              v-model="form.organization"
              label="Organization"
              placeholder="e.g., Company Name, University"
              required
              :error="form.errors.organization"
              help="Name of the company, university, or organization"
              :max-length="255"
              show-char-count
            />

            <FormInput
              v-model="form.start_date"
              label="Start Date"
              type="date"
              required
              :error="form.errors.start_date"
              help="When did you start this position?"
              :max="currentDate"
            />

            <div>
              <FormInput
                v-model="form.end_date"
                label="End Date"
                type="date"
                :required="!form.is_current"
                :disabled="form.is_current"
                :error="form.errors.end_date"
                help="When did you end this position? (Leave empty if current)"
                :min="form.start_date"
                :max="currentDate"
              />

              <div class="mt-3">
                <label class="flex items-center">
                  <input
                    v-model="form.is_current"
                    @change="toggleCurrentPosition"
                    type="checkbox"
                    class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-600"
                  />
                  <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    This is my current position
                  </span>
                </label>
              </div>
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
            Description & Responsibilities
          </h2>

          <FormTextarea
            v-model="form.description"
            label="Description"
            placeholder="Describe your responsibilities, achievements, and key contributions in this role..."
            required
            :rows="6"
            :error="form.errors.description"
            help="Provide a detailed description of your role, responsibilities, and achievements"
            :max-length="1000"
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
                Reset Form
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
                @click="$inertia.visit('/admin/experience')"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
              >
                Cancel
              </button>

              <button
                type="submit"
                :disabled="!isFormValid || isSubmitting"
                :class="[
                  'inline-flex items-center px-6 py-2 text-sm font-medium text-white rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2',
                  isFormValid && !isSubmitting
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
                {{ isSubmitting ? "Creating..." : "Create Experience" }}
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
    </div>
  </DashboardLayout>
</template>
