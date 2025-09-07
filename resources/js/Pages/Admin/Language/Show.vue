<!-- resources/js/Pages/Admin/Language/Show.vue -->
<script setup>
import { computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  language: {
    type: Object,
    required: true,
  },
});

// Language levels mapping
const languageLevels = {
  A1: {
    text: "A1 - Beginner",
    class: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300",
    description:
      "Can understand and use familiar everyday expressions and basic phrases",
  },
  A2: {
    text: "A2 - Elementary",
    class:
      "bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300",
    description:
      "Can communicate in simple routine tasks requiring direct exchange of information",
  },
  B1: {
    text: "B1 - Intermediate",
    class:
      "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300",
    description:
      "Can deal with most situations likely to arise whilst travelling",
  },
  B2: {
    text: "B2 - Upper Intermediate",
    class: "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300",
    description: "Can interact with a degree of fluency and spontaneity",
  },
  C1: {
    text: "C1 - Advanced",
    class:
      "bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300",
    description:
      "Can express ideas fluently and spontaneously without searching for expressions",
  },
  C2: {
    text: "C2 - Proficient",
    class: "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300",
    description: "Can understand virtually everything heard or read",
  },
  native: {
    text: "Native",
    class:
      "bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300",
    description: "Native speaker proficiency",
  },
};

// Computed
const levelInfo = computed(() => {
  return (
    languageLevels[props.language.level] || {
      text: props.language.level,
      class: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
      description: "",
    }
  );
});

const proficiencyLabel = computed(() => {
  const proficiency = props.language.proficiency || 0;
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
  const proficiency = props.language.proficiency || 0;
  if (proficiency >= 90) return "bg-green-500";
  if (proficiency >= 70) return "bg-blue-500";
  if (proficiency >= 50) return "bg-yellow-500";
  return "bg-red-500";
});

const proficiencyTextColor = computed(() => {
  const proficiency = props.language.proficiency || 0;
  if (proficiency >= 90) return "text-green-600 dark:text-green-400";
  if (proficiency >= 70) return "text-blue-600 dark:text-blue-400";
  if (proficiency >= 50) return "text-yellow-600 dark:text-yellow-400";
  return "text-red-600 dark:text-red-400";
});

// Methods
const deleteLanguage = () => {
  if (confirm("Are you sure you want to delete this language?")) {
    router.delete(`/admin/languages/${props.language.id}`, {
      onSuccess: () => {
        router.visit("/admin/languages");
      },
    });
  }
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Languages", href: "/admin/languages" },
  {
    name: props.language.name,
    href: `/admin/languages/${props.language.id}`,
    current: true,
  },
];
</script>

<template>
  <Head :title="language.name" />

  <DashboardLayout :title="language.name" :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
      <!-- Header Actions -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
        >
          <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
              <div
                class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-6 h-6 text-green-600 dark:text-green-400"
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
            <div>
              <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ language.name }}
              </h1>
              <p class="text-gray-600 dark:text-gray-400">
                {{ proficiencyLabel }} • {{ language.proficiency || 0 }}%
                Proficiency
              </p>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <span
              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
              :class="levelInfo.class"
            >
              {{ levelInfo.text }}
            </span>

            <Link
              :href="`/admin/languages/${language.id}/edit`"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
              Edit Language
            </Link>

            <button
              @click="deleteLanguage"
              class="inline-flex items-center px-4 py-2 border border-red-300 hover:border-red-400 text-red-700 hover:text-red-800 text-sm font-medium rounded-lg transition-colors duration-200"
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
              Delete
            </button>
          </div>
        </div>
      </div>

      <!-- Language Overview -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Proficiency Details -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Proficiency Overview
          </h2>

          <div class="space-y-6">
            <!-- Proficiency Bar -->
            <div>
              <div class="flex items-center justify-between mb-2">
                <span
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                  Overall Proficiency
                </span>
                <span class="text-sm font-bold" :class="proficiencyTextColor">
                  {{ language.proficiency || 0 }}%
                </span>
              </div>
              <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                <div
                  class="h-4 rounded-full transition-all duration-500"
                  :class="proficiencyColor"
                  :style="`width: ${language.proficiency || 0}%`"
                ></div>
              </div>
              <p class="mt-2 text-sm font-medium" :class="proficiencyTextColor">
                {{ proficiencyLabel }}
              </p>
            </div>

            <!-- CEFR Level -->
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                    CEFR Level
                  </h3>
                  <p
                    class="text-lg font-bold mt-1"
                    :class="
                      levelInfo.class
                        .replace('bg-', 'text-')
                        .replace('100', '600')
                        .replace('800', '400')
                    "
                  >
                    {{ levelInfo.text }}
                  </p>
                </div>
                <span
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                  :class="levelInfo.class"
                >
                  {{ language.level.toUpperCase() }}
                </span>
              </div>
              <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                {{ levelInfo.description }}
              </p>
            </div>

            <!-- Proficiency Breakdown -->
            <div class="grid grid-cols-2 gap-4">
              <div
                class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
              >
                <div class="text-2xl font-bold" :class="proficiencyTextColor">
                  {{ Math.round((language.proficiency || 0) / 20) }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                  out of 5 stars
                </div>
                <div class="flex justify-center mt-2">
                  <div class="flex space-x-1">
                    <svg
                      v-for="star in 5"
                      :key="star"
                      class="w-4 h-4"
                      :class="
                        star <= Math.round((language.proficiency || 0) / 20)
                          ? proficiencyTextColor
                          : 'text-gray-300 dark:text-gray-600'
                      "
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                      />
                    </svg>
                  </div>
                </div>
              </div>

              <div
                class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
              >
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                  {{
                    language.level === "native"
                      ? "∞"
                      : Math.ceil((language.proficiency || 0) / 10)
                  }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                  {{
                    language.level === "native" ? "Native" : "Years studying"
                  }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Language Information -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Language Details
          </h2>

          <dl class="space-y-4">
            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Language Name
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                {{ language.name }}
              </dd>
            </div>

            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Proficiency Level
              </dt>
              <dd class="mt-1">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="levelInfo.class"
                >
                  {{ levelInfo.text }}
                </span>
              </dd>
            </div>

            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Proficiency Percentage
              </dt>
              <dd class="mt-1 text-sm font-bold" :class="proficiencyTextColor">
                {{ language.proficiency || 0 }}%
              </dd>
            </div>

            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Fluency Category
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                {{ proficiencyLabel }}
              </dd>
            </div>

            <div v-if="language.created_at">
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Added
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                {{
                  new Date(language.created_at).toLocaleDateString("en-US", {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                  })
                }}
              </dd>
            </div>

            <div v-if="language.updated_at">
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Last Updated
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                {{
                  new Date(language.updated_at).toLocaleDateString("en-US", {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                  })
                }}
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Description -->
      <div
        v-if="language.description"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          Description
        </h2>
        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
          {{ language.description }}
        </p>
      </div>

      <!-- CEFR Framework Reference -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          CEFR Framework Reference
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
          The Common European Framework of Reference for Languages (CEFR) is an
          international standard for describing language ability.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="(level, key) in languageLevels"
            :key="key"
            class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg"
            :class="
              key === language.level
                ? 'ring-2 ring-indigo-500 border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20'
                : ''
            "
          >
            <div class="flex items-center justify-between mb-2">
              <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="level.class"
              >
                {{ level.text }}
              </span>
              <svg
                v-if="key === language.level"
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

      <!-- Actions -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          Quick Actions
        </h2>
        <div class="flex flex-wrap gap-3">
          <Link
            :href="`/admin/languages/${language.id}/edit`"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
            Edit Details
          </Link>

          <Link
            href="/admin/languages"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium rounded-lg transition-colors duration-200"
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
                d="M10 19l-7-7m0 0l7-7m-7 7h18"
              />
            </svg>
            Back to List
          </Link>

          <Link
            href="/admin/languages/create"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium rounded-lg transition-colors duration-200"
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
            Add Another Language
          </Link>

          <button
            @click="deleteLanguage"
            class="inline-flex items-center px-4 py-2 border border-red-300 hover:border-red-400 text-red-700 hover:text-red-800 text-sm font-medium rounded-lg transition-colors duration-200"
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
            Delete Language
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
