<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { router } from "@inertiajs/vue3";

// Props
const props = defineProps({
  socialLink: Object,
});

// Form setup
const form = useForm({
  platform: props.socialLink.platform || "",
  url: props.socialLink.url || "",
  color: props.socialLink.color || "#3b82f6",
  is_active: props.socialLink.is_active ?? true,
  order: props.socialLink.order || 0,
});

// Refs
const isDeleting = ref(false);

// Predefined platforms with their default colors and icons
const predefinedPlatforms = [
  { name: "GitHub", color: "#333333", icon: "🐙" },
  { name: "LinkedIn", color: "#0077B5", icon: "💼" },
  { name: "Twitter", color: "#1DA1F2", icon: "🐦" },
  { name: "Instagram", color: "#E4405F", icon: "📷" },
  { name: "Facebook", color: "#4267B2", icon: "📘" },
  { name: "YouTube", color: "#FF0000", icon: "🎥" },
  { name: "TikTok", color: "#000000", icon: "🎵" },
  { name: "Email", color: "#EA4335", icon: "✉️" },
  { name: "WhatsApp", color: "#25D366", icon: "📱" },
  { name: "Telegram", color: "#0088CC", icon: "✈️" },
  { name: "Discord", color: "#5865F2", icon: "🎮" },
  { name: "Website", color: "#6366F1", icon: "🌐" },
];

// Computed
const selectedPlatform = computed(() => {
  return predefinedPlatforms.find((p) => p.name === form.platform);
});

const platformIcon = computed(() => {
  return selectedPlatform.value?.icon || "🔗";
});

// Methods
const updateSocialLink = () => {
  form.put(route("admin.social-links.update", props.socialLink.id), {
    preserveScroll: true,
    onSuccess: () => {
      // Success feedback is handled by flash messages
    },
  });
};

const deleteSocialLink = () => {
  if (!confirm("Are you sure you want to delete this social link?")) {
    return;
  }

  isDeleting.value = true;
  router.delete(route("admin.social-links.destroy", props.socialLink.id), {
    onFinish: () => {
      isDeleting.value = false;
    },
  });
};

const resetForm = () => {
  form.reset();
};

const onPlatformChange = () => {
  const platform = predefinedPlatforms.find((p) => p.name === form.platform);
  if (platform) {
    form.color = platform.color;
  }
};

const formatUrl = (url) => {
  if (!url) return "";
  if (url.length > 50) {
    return url.substring(0, 47) + "...";
  }
  return url;
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Social Links", href: "/admin/social-links" },
  {
    name: "Edit",
    href: `/admin/social-links/${props.socialLink.id}/edit`,
    current: true,
  },
];
</script>

<template>
  <Head :title="`Edit ${socialLink.platform} Link`" />

  <DashboardLayout :title="`Edit Social Link`" :breadcrumbs="breadcrumbs">
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
                  d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                />
              </svg>
              Edit Social Link
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
              Update social media link information and settings
            </p>
          </div>

          <div class="flex items-center space-x-3">
            <!-- Status Badge -->
            <span
              :class="[
                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                socialLink.is_active
                  ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400'
                  : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400',
              ]"
            >
              <span
                :class="[
                  'w-2 h-2 mr-1 rounded-full',
                  socialLink.is_active
                    ? 'bg-green-500 animate-pulse'
                    : 'bg-gray-400',
                ]"
              ></span>
              {{ socialLink.is_active ? "Active" : "Inactive" }}
            </span>
          </div>
        </div>
      </div>

      <!-- Preview Section -->
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
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
            />
          </svg>
          Preview
        </h2>

        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6">
          <div class="flex items-center space-x-4">
            <div
              class="w-12 h-12 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg"
              :style="{ backgroundColor: form.color }"
            >
              {{ platformIcon }}
            </div>
            <div class="flex-1">
              <div class="flex items-center space-x-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                  {{ form.platform || "Platform Name" }}
                </h3>
                <span
                  v-if="form.is_active"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400"
                >
                  <span
                    class="w-2 h-2 mr-1 bg-green-500 rounded-full animate-pulse"
                  ></span>
                  Active
                </span>
              </div>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ formatUrl(form.url) || "URL will appear here" }}
              </p>
              <div class="flex items-center mt-2 text-xs text-gray-400">
                <span class="mr-3">Order: {{ form.order }}</span>
                <span>Color: {{ form.color }}</span>
              </div>
            </div>
            <a
              v-if="form.url"
              :href="form.url"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
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
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                />
              </svg>
              Visit
            </a>
          </div>
        </div>
      </div>

      <!-- Edit Form -->
      <form @submit.prevent="updateSocialLink">
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
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
              />
            </svg>
            Social Link Information
          </h2>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Platform -->
            <div class="lg:col-span-1">
              <label
                for="platform"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Platform <span class="text-red-500">*</span>
              </label>
              <select
                id="platform"
                v-model="form.platform"
                @change="onPlatformChange"
                required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm"
              >
                <option value="">Select Platform</option>
                <option
                  v-for="platform in predefinedPlatforms"
                  :key="platform.name"
                  :value="platform.name"
                >
                  {{ platform.icon }} {{ platform.name }}
                </option>
              </select>
              <div
                v-if="form.errors.platform"
                class="mt-1 text-sm text-red-600"
              >
                {{ form.errors.platform }}
              </div>
            </div>

            <!-- URL -->
            <div class="lg:col-span-1">
              <label
                for="url"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                URL <span class="text-red-500">*</span>
              </label>
              <input
                id="url"
                v-model="form.url"
                type="url"
                required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm"
                placeholder="https://example.com/profile"
              />
              <div v-if="form.errors.url" class="mt-1 text-sm text-red-600">
                {{ form.errors.url }}
              </div>
            </div>

            <!-- Color -->
            <div class="lg:col-span-1">
              <label
                for="color"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Brand Color
              </label>
              <div class="flex items-center space-x-3">
                <input
                  id="color"
                  v-model="form.color"
                  type="color"
                  class="h-10 w-20 rounded border border-gray-300 dark:border-gray-600 cursor-pointer"
                />
                <input
                  v-model="form.color"
                  type="text"
                  class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm"
                  placeholder="#3b82f6"
                />
              </div>
              <div v-if="form.errors.color" class="mt-1 text-sm text-red-600">
                {{ form.errors.color }}
              </div>
            </div>

            <!-- Order -->
            <div class="lg:col-span-1">
              <label
                for="order"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Display Order
              </label>
              <input
                id="order"
                v-model.number="form.order"
                type="number"
                min="0"
                step="1"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm"
                placeholder="0"
              />
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Lower numbers appear first
              </p>
              <div v-if="form.errors.order" class="mt-1 text-sm text-red-600">
                {{ form.errors.order }}
              </div>
            </div>

            <!-- Is Active -->
            <div class="lg:col-span-2">
              <div class="flex items-center">
                <input
                  id="is_active"
                  v-model="form.is_active"
                  type="checkbox"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700"
                />
                <label
                  for="is_active"
                  class="ml-2 block text-sm text-gray-700 dark:text-gray-300"
                >
                  Display this social link publicly
                </label>
              </div>
              <div
                v-if="form.errors.is_active"
                class="mt-1 text-sm text-red-600"
              >
                {{ form.errors.is_active }}
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div
            class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-600"
          >
            <button
              type="button"
              @click="resetForm"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600"
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
              Reset
            </button>

            <div class="flex items-center space-x-3">
              <button
                type="submit"
                :disabled="form.processing"
                class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <svg
                  v-if="form.processing"
                  class="w-4 h-4 mr-2 animate-spin"
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
                {{ form.processing ? "Updating..." : "Update Social Link" }}
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
              Permanently delete this social link. This action cannot be undone.
            </p>
          </div>
          <button
            @click="deleteSocialLink"
            :disabled="isDeleting"
            class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/30"
          >
            <svg
              v-if="isDeleting"
              class="w-4 h-4 mr-2 animate-spin"
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
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
              />
            </svg>
            {{ isDeleting ? "Deleting..." : "Delete Social Link" }}
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
