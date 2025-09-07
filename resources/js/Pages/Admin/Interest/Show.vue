<!-- resources/js/Pages/Admin/Interest/Show.vue -->
<script setup>
import { computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  interest: {
    type: Object,
    required: true,
  },
});

// Computed
const getColorStyle = (color) => {
  return {
    backgroundColor: color || "#3b82f6",
    color: "#ffffff",
  };
};

const getTextColorClass = (color) => {
  // Generate appropriate text color based on background
  const colorMap = {
    "#3b82f6": "text-blue-600",
    "#10b981": "text-emerald-600",
    "#f59e0b": "text-amber-600",
    "#ef4444": "text-red-600",
    "#8b5cf6": "text-violet-600",
    "#06b6d4": "text-cyan-600",
    "#f97316": "text-orange-600",
    "#84cc16": "text-lime-600",
    "#ec4899": "text-pink-600",
    "#6366f1": "text-indigo-600",
    "#14b8a6": "text-teal-600",
    "#f43f5e": "text-rose-600",
  };
  return colorMap[color] || "text-blue-600";
};

// Methods
const deleteInterest = () => {
  if (confirm("Are you sure you want to delete this interest?")) {
    router.delete(`/admin/interests/${props.interest.id}`, {
      onSuccess: () => {
        router.visit("/admin/interests");
      },
    });
  }
};

const toggleFeatured = () => {
  router.patch(
    `/admin/interests/${props.interest.id}/toggle-featured`,
    {},
    {
      preserveScroll: true,
      onSuccess: () => {
        // Success handled by page refresh
      },
    }
  );
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Interests", href: "/admin/interests" },
  {
    name: props.interest.name,
    href: `/admin/interests/${props.interest.id}`,
    current: true,
  },
];
</script>

<template>
  <Head :title="interest.name" />

  <DashboardLayout :title="interest.name" :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
      <!-- Header Actions -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
        >
          <div class="flex items-center space-x-4">
            <div
              class="w-16 h-16 rounded-full flex items-center justify-center text-3xl"
              :style="getColorStyle(interest.color)"
            >
              {{ interest.icon }}
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ interest.name }}
              </h1>
              <p class="text-gray-600 dark:text-gray-400">Personal Interest</p>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <button
              @click="toggleFeatured"
              :class="
                interest.is_featured
                  ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                  : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
              "
              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200"
            >
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                />
              </svg>
              {{ interest.is_featured ? "Featured" : "Make Featured" }}
            </button>

            <Link
              :href="`/admin/interests/${interest.id}/edit`"
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
              Edit Interest
            </Link>

            <button
              @click="deleteInterest"
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

      <!-- Interest Details -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Visual Preview -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h2
              class="text-lg font-semibold text-gray-900 dark:text-white mb-6"
            >
              Visual Preview
            </h2>

            <div class="text-center">
              <div
                class="w-32 h-32 mx-auto rounded-full flex items-center justify-center text-6xl mb-6 shadow-lg"
                :style="getColorStyle(interest.color)"
              >
                {{ interest.icon }}
              </div>
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                {{ interest.name }}
              </h3>
              <div class="flex items-center justify-center space-x-2 mb-4">
                <div
                  class="w-4 h-4 rounded-full"
                  :style="{ backgroundColor: interest.color }"
                ></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                  {{ interest.color }}
                </span>
              </div>
              <div v-if="interest.is_featured" class="flex justify-center">
                <span
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200"
                >
                  <svg
                    class="w-4 h-4 mr-1"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                    />
                  </svg>
                  Featured Interest
                </span>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div
            v-if="interest.description"
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h2
              class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
            >
              Description
            </h2>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
              {{ interest.description }}
            </p>
          </div>

          <!-- Portfolio Integration Preview -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h2
              class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
            >
              How it appears on your portfolio
            </h2>
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Sample layout showing how it would appear -->
                <div
                  class="text-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow"
                >
                  <div
                    class="w-12 h-12 mx-auto rounded-full flex items-center justify-center text-2xl mb-2"
                    :style="getColorStyle(interest.color)"
                  >
                    {{ interest.icon }}
                  </div>
                  <h4 class="font-medium text-gray-900 dark:text-white text-sm">
                    {{ interest.name }}
                  </h4>
                  <p
                    v-if="interest.description"
                    class="text-xs text-gray-600 dark:text-gray-400 mt-1"
                  >
                    {{ interest.description.substring(0, 50)
                    }}{{ interest.description.length > 50 ? "..." : "" }}
                  </p>
                </div>
                <!-- Placeholder cards -->
                <div
                  class="text-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow opacity-50"
                >
                  <div
                    class="w-12 h-12 mx-auto rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-2xl mb-2"
                  >
                    🎯
                  </div>
                  <h4 class="font-medium text-gray-900 dark:text-white text-sm">
                    Other Interest
                  </h4>
                </div>
                <div
                  class="text-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow opacity-50"
                >
                  <div
                    class="w-12 h-12 mx-auto rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-2xl mb-2"
                  >
                    🎯
                  </div>
                  <h4 class="font-medium text-gray-900 dark:text-white text-sm">
                    Other Interest
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
          <!-- Interest Details -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h2
              class="text-lg font-semibold text-gray-900 dark:text-white mb-6"
            >
              Interest Details
            </h2>

            <dl class="space-y-4">
              <div>
                <dt
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                  Name
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                  {{ interest.name }}
                </dd>
              </div>

              <div>
                <dt
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                  Icon
                </dt>
                <dd class="mt-1 flex items-center space-x-2">
                  <span class="text-2xl">{{ interest.icon }}</span>
                  <span class="text-sm text-gray-600 dark:text-gray-400">{{
                    interest.icon
                  }}</span>
                </dd>
              </div>

              <div>
                <dt
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                  Color
                </dt>
                <dd class="mt-1 flex items-center space-x-2">
                  <div
                    class="w-6 h-6 rounded border border-gray-200 dark:border-gray-600"
                    :style="{ backgroundColor: interest.color }"
                  ></div>
                  <span class="text-sm text-gray-900 dark:text-white">{{
                    interest.color
                  }}</span>
                </dd>
              </div>

              <div>
                <dt
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                  Status
                </dt>
                <dd class="mt-1">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="
                      interest.is_featured
                        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                        : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                    "
                  >
                    {{ interest.is_featured ? "Featured" : "Regular" }}
                  </span>
                </dd>
              </div>

              <div v-if="interest.created_at">
                <dt
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                  Created
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                  {{
                    new Date(interest.created_at).toLocaleDateString("en-US", {
                      year: "numeric",
                      month: "long",
                      day: "numeric",
                    })
                  }}
                </dd>
              </div>

              <div v-if="interest.updated_at">
                <dt
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                  Last Updated
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                  {{
                    new Date(interest.updated_at).toLocaleDateString("en-US", {
                      year: "numeric",
                      month: "long",
                      day: "numeric",
                    })
                  }}
                </dd>
              </div>
            </dl>
          </div>

          <!-- Featured Status -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h2
              class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
            >
              Featured Status
            </h2>

            <div
              v-if="interest.is_featured"
              class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg"
            >
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg
                    class="h-5 w-5 text-yellow-400"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                    />
                  </svg>
                </div>
                <div class="ml-3">
                  <h3
                    class="text-sm font-medium text-yellow-800 dark:text-yellow-200"
                  >
                    Featured Interest
                  </h3>
                  <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                    This interest is featured and will be prominently displayed
                    on your portfolio.
                  </p>
                </div>
              </div>
            </div>

            <div v-else class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg
                    class="h-5 w-5 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                    Regular Interest
                  </h3>
                  <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    This interest will appear in the general interests section.
                  </p>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <button
                @click="toggleFeatured"
                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200"
              >
                {{
                  interest.is_featured
                    ? "Remove from Featured"
                    : "Make Featured"
                }}
              </button>
            </div>
          </div>

          <!-- Color Information -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h2
              class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
            >
              Color Information
            </h2>

            <div class="space-y-4">
              <div class="text-center">
                <div
                  class="w-20 h-20 mx-auto rounded-lg mb-3"
                  :style="{ backgroundColor: interest.color }"
                ></div>
                <p class="text-sm font-mono text-gray-600 dark:text-gray-400">
                  {{ interest.color }}
                </p>
              </div>

              <div class="grid grid-cols-3 gap-2">
                <div class="text-center">
                  <div
                    class="w-full h-8 rounded mb-1"
                    :style="{ backgroundColor: interest.color }"
                  ></div>
                  <p class="text-xs text-gray-500">Original</p>
                </div>
                <div class="text-center">
                  <div
                    class="w-full h-8 rounded mb-1"
                    :style="{ backgroundColor: interest.color, opacity: 0.7 }"
                  ></div>
                  <p class="text-xs text-gray-500">70% Opacity</p>
                </div>
                <div class="text-center">
                  <div
                    class="w-full h-8 rounded mb-1"
                    :style="{ backgroundColor: interest.color, opacity: 0.3 }"
                  ></div>
                  <p class="text-xs text-gray-500">30% Opacity</p>
                </div>
              </div>
            </div>
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
            :href="`/admin/interests/${interest.id}/edit`"
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

          <button
            @click="toggleFeatured"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium rounded-lg transition-colors duration-200"
          >
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path
                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
              />
            </svg>
            {{ interest.is_featured ? "Remove Featured" : "Make Featured" }}
          </button>

          <Link
            href="/admin/interests"
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
            href="/admin/interests/create"
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
            Add Another Interest
          </Link>

          <button
            @click="deleteInterest"
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
            Delete Interest
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
