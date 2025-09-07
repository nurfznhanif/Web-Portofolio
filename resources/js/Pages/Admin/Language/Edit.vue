<!-- resources/js/Pages/Admin/Language/Edit.vue -->
<script setup>
import { ref, computed } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  language: {
    type: Object,
    required: true,
  },
});

// Form setup
const form = useForm({
  name: props.language.name,
  level: props.language.level,
  proficiency: props.language.proficiency || 50,
  description: props.language.description || "",
});

const previewMode = ref(false);

// Language levels
const languageLevels = [
  {
    value: "A1",
    text: "A1 - Beginner",
    description:
      "Can understand and use familiar everyday expressions and basic phrases",
  },
  {
    value: "A2",
    text: "A2 - Elementary",
    description:
      "Can communicate in simple routine tasks requiring direct exchange of information",
  },
  {
    value: "B1",
    text: "B1 - Intermediate",
    description:
      "Can deal with most situations likely to arise whilst travelling",
  },
  {
    value: "B2",
    text: "B2 - Upper Intermediate",
    description: "Can interact with a degree of fluency and spontaneity",
  },
  {
    value: "C1",
    text: "C1 - Advanced",
    description:
      "Can express ideas fluently and spontaneously without searching for expressions",
  },
  {
    value: "C2",
    text: "C2 - Proficient",
    description: "Can understand virtually everything heard or read",
  },
  {
    value: "native",
    text: "Native",
    description: "Native speaker proficiency",
  },
];

// Computed
const levelDescription = computed(() => {
  const level = languageLevels.find((l) => l.value === form.level);
  return level ? level.description : "";
});

const proficiencyLabel = computed(() => {
  const proficiency = form.proficiency;
  if (proficiency >= 95) return "Native/Bilingual";
  if (proficiency >= 90) return "Near Native";
  if (proficiency >= 80) return "Very Advanced";
  if (proficiency >= 70) return "Advanced";
  if (proficiency >= 60) return "Upper Intermediate";
  if (proficiency >= 50) return "Intermediate";
  if (proficiency >= 40) return "Lower Intermediate";
  if (proficiency >= 30) return "Elementary";
  if (proficiency >= 20) return "Beginner";
  return "Basic";
});

const proficiencyColor = computed(() => {
  const proficiency = form.proficiency;
  if (proficiency >= 90) return "bg-green-500";
  if (proficiency >= 70) return "bg-blue-500";
  if (proficiency >= 50) return "bg-yellow-500";
  return "bg-red-500";
});

const getLevelInfo = (level) => {
  const levelMap = {
    A1: { class: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300" },
    A2: {
      class:
        "bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300",
    },
    B1: {
      class:
        "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300",
    },
    B2: {
      class: "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300",
    },
    C1: {
      class:
        "bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300",
    },
    C2: {
      class:
        "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300",
    },
    native: {
      class:
        "bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300",
    },
  };
  return (
    levelMap[level] || {
      class: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
    }
  );
};

// Methods
const submitForm = () => {
  form.put(`/admin/languages/${props.language.id}`, {
    onSuccess: () => {
      // Success handled by redirect
    },
  });
};

const resetForm = () => {
  form.reset();
  form.clearErrors();
};

const cancelEdit = () => {
  router.visit("/admin/languages");
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Languages", href: "/admin/languages" },
  {
    name: "Edit",
    href: `/admin/languages/${props.language.id}/edit`,
    current: true,
  },
];
</script>

<template>
  <Head title="Edit Language" />

  <DashboardLayout :title="'Edit Language'" :breadcrumbs="breadcrumbs">
    <form @submit.prevent="submitForm" class="space-y-6">
      <!-- Success/Error Messages -->
      <div
        v-if="$page.props.flash?.error"
        class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4"
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
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-red-800 dark:text-red-200">
              {{ $page.props.flash.error }}
            </p>
          </div>
        </div>
      </div>

      <!-- Header with Current Level -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
              Edit Language
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
              Update the details of "{{ props.language.name }}"
            </p>
          </div>
          <div class="flex items-center space-x-4">
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
              :class="getLevelInfo(props.language.level).class"
            >
              {{
                languageLevels.find((l) => l.value === props.language.level)
                  ?.text || props.language.level
              }}
            </span>
            <button
              type="button"
              @click="previewMode = !previewMode"
              class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
            >
              {{ previewMode ? "Edit" : "Preview" }}
            </button>
          </div>
        </div>
      </div>

      <!-- Preview Mode -->
      <div
        v-if="previewMode"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="max-w-3xl">
          <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
              <div
                class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-8 h-8 text-green-600 dark:text-green-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
                  />
                </svg>
              </div>
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ form.name || "Language Name" }}
              </h3>
              <div class="mt-2 flex items-center space-x-4">
                <span
                  v-if="form.level"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="getLevelInfo(form.level).class"
                >
                  {{
                    languageLevels.find((l) => l.value === form.level)?.text ||
                    form.level
                  }}
                </span>
                <div class="flex items-center space-x-2">
                  <div
                    class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2"
                  >
                    <div
                      class="h-2 rounded-full transition-all duration-300"
                      :class="proficiencyColor"
                      :style="`width: ${form.proficiency}%`"
                    ></div>
                  </div>
                  <span
                    class="text-sm font-medium text-gray-900 dark:text-white"
                  >
                    {{ form.proficiency }}%
                  </span>
                </div>
              </div>
              <p
                class="mt-2 text-sm font-medium text-gray-600 dark:text-gray-400"
              >
                {{ proficiencyLabel }}
              </p>
              <div v-if="levelDescription" class="mt-3">
                <p class="text-sm text-gray-700 dark:text-gray-300">
                  {{ levelDescription }}
                </p>
              </div>
              <div v-if="form.description" class="mt-4">
                <dt
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                  Description
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                  {{ form.description }}
                </dd>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Mode -->
      <div v-else class="space-y-6">
        <!-- Language Information -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Language Information
          </h2>

          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
              Language Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="e.g., English, Spanish, French"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
            <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">
              {{ form.errors.name }}
            </p>
          </div>
        </div>

        <!-- Proficiency Level -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Proficiency Level
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Proficiency Percentage -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Proficiency Percentage <span class="text-red-500">*</span>
              </label>
              <div class="space-y-4">
                <div class="flex items-center space-x-4">
                  <input
                    v-model.number="form.proficiency"
                    type="range"
                    min="0"
                    max="100"
                    step="5"
                    class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 slider"
                  />
                  <span
                    class="text-sm font-medium text-gray-900 dark:text-white min-w-[50px]"
                  >
                    {{ form.proficiency }}%
                  </span>
                </div>
                <div
                  class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3"
                >
                  <div
                    class="h-3 rounded-full transition-all duration-300"
                    :class="proficiencyColor"
                    :style="`width: ${form.proficiency}%`"
                  ></div>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                  {{ proficiencyLabel }}
                </p>
              </div>
              <p
                v-if="form.errors.proficiency"
                class="mt-1 text-sm text-red-500"
              >
                {{ form.errors.proficiency }}
              </p>
            </div>

            <!-- CEFR Level -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                CEFR Level <span class="text-red-500">*</span>
              </label>
              <select
                v-model="form.level"
                required
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              >
                <option value="">Select a level</option>
                <option
                  v-for="level in languageLevels"
                  :key="level.value"
                  :value="level.value"
                >
                  {{ level.text }}
                </option>
              </select>
              <p v-if="form.errors.level" class="mt-1 text-sm text-red-500">
                {{ form.errors.level }}
              </p>
              <p
                v-if="levelDescription"
                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
              >
                {{ levelDescription }}
              </p>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Additional Information
          </h2>

          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
              Description
            </label>
            <textarea
              v-model="form.description"
              rows="4"
              placeholder="Describe your experience with this language, how you learned it, or any certifications..."
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-vertical"
            ></textarea>
            <p v-if="form.errors.description" class="mt-1 text-sm text-red-500">
              {{ form.errors.description }}
            </p>
            <p class="mt-1 text-sm text-gray-500">
              Share your background with this language (Optional)
            </p>
          </div>
        </div>

        <!-- CEFR Reference Guide -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            CEFR Reference Guide
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
              v-for="level in languageLevels"
              :key="level.value"
              class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200"
              @click="form.level = level.value"
              :class="
                form.level === level.value
                  ? 'ring-2 ring-indigo-500 border-indigo-500'
                  : ''
              "
            >
              <div class="flex items-center justify-between mb-2">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="getLevelInfo(level.value).class"
                >
                  {{ level.text }}
                </span>
                <svg
                  v-if="form.level === level.value"
                  class="w-5 h-5 text-indigo-500"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"
                  />
                </svg>
              </div>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ level.description }}
              </p>
            </div>
          </div>
        </div>
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
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200"
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

            <button
              type="button"
              @click="cancelEdit"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200"
            >
              Cancel
            </button>
          </div>

          <div class="flex items-center space-x-4">
            <div
              v-if="form.processing"
              class="flex items-center text-sm text-gray-500 dark:text-gray-400"
            >
              <svg
                class="animate-spin -ml-1 mr-2 h-4 w-4"
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
              Updating...
            </div>

            <button
              type="submit"
              :disabled="form.processing"
              class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
                  d="M5 13l4 4L19 7"
                />
              </svg>
              {{ form.processing ? "Updating..." : "Update Language" }}
            </button>
          </div>
        </div>
      </div>
    </form>
  </DashboardLayout>
</template>

<style scoped>
/* Custom slider styling */
.slider::-webkit-slider-thumb {
  appearance: none;
  height: 20px;
  width: 20px;
  border-radius: 50%;
  background: #4f46e5;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.slider::-moz-range-thumb {
  height: 20px;
  width: 20px;
  border-radius: 50%;
  background: #4f46e5;
  cursor: pointer;
  border: none;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
</style>
