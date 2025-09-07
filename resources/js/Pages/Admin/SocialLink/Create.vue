<!-- resources/js/Pages/Admin/SocialLink/Create.vue -->
<script setup>
import { ref, computed, onMounted } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Form setup
const form = useForm({
  platform: "",
  url: "",
  color: "#6366f1",
  is_active: true,
  order: 0,
});

const previewMode = ref(false);

// Social platforms with their default configurations
const socialPlatforms = {
  GitHub: {
    color: "#333333",
    icon: "👨‍💻",
    urlPlaceholder: "https://github.com/yourusername",
    urlPattern: "github.com",
  },
  LinkedIn: {
    color: "#0077b5",
    icon: "💼",
    urlPlaceholder: "https://linkedin.com/in/yourprofile",
    urlPattern: "linkedin.com",
  },
  Twitter: {
    color: "#1da1f2",
    icon: "🐦",
    urlPlaceholder: "https://twitter.com/yourusername",
    urlPattern: "twitter.com",
  },
  Instagram: {
    color: "#e4405f",
    icon: "📸",
    urlPlaceholder: "https://instagram.com/yourusername",
    urlPattern: "instagram.com",
  },
  Facebook: {
    color: "#4267b2",
    icon: "📘",
    urlPlaceholder: "https://facebook.com/yourprofile",
    urlPattern: "facebook.com",
  },
  YouTube: {
    color: "#ff0000",
    icon: "📺",
    urlPlaceholder: "https://youtube.com/c/yourchannel",
    urlPattern: "youtube.com",
  },
  TikTok: {
    color: "#000000",
    icon: "🎵",
    urlPlaceholder: "https://tiktok.com/@yourusername",
    urlPattern: "tiktok.com",
  },
  Discord: {
    color: "#5865f2",
    icon: "🎮",
    urlPlaceholder: "https://discord.gg/yourinvite",
    urlPattern: "discord",
  },
  Telegram: {
    color: "#0088cc",
    icon: "✈️",
    urlPlaceholder: "https://t.me/yourusername",
    urlPattern: "t.me",
  },
  WhatsApp: {
    color: "#25d366",
    icon: "💬",
    urlPlaceholder: "https://wa.me/yournumber",
    urlPattern: "wa.me",
  },
  Email: {
    color: "#ea4335",
    icon: "📧",
    urlPlaceholder: "mailto:your@email.com",
    urlPattern: "mailto:",
  },
  Website: {
    color: "#6366f1",
    icon: "🌐",
    urlPlaceholder: "https://yourwebsite.com",
    urlPattern: "https://",
  },
  Blog: {
    color: "#ff6b35",
    icon: "📝",
    urlPlaceholder: "https://yourblog.com",
    urlPattern: "blog",
  },
  Portfolio: {
    color: "#8b5a2b",
    icon: "🎨",
    urlPlaceholder: "https://yourportfolio.com",
    urlPattern: "portfolio",
  },
  Resume: {
    color: "#2563eb",
    icon: "📄",
    urlPlaceholder: "https://yourresume.pdf",
    urlPattern: ".pdf",
  },
  Behance: {
    color: "#1769ff",
    icon: "🎭",
    urlPlaceholder: "https://behance.net/yourusername",
    urlPattern: "behance.net",
  },
  Dribbble: {
    color: "#ea4c89",
    icon: "🏀",
    urlPlaceholder: "https://dribbble.com/yourusername",
    urlPattern: "dribbble.com",
  },
  Medium: {
    color: "#000000",
    icon: "📚",
    urlPlaceholder: "https://medium.com/@yourusername",
    urlPattern: "medium.com",
  },
  "Dev.to": {
    color: "#0a0a0a",
    icon: "💻",
    urlPlaceholder: "https://dev.to/yourusername",
    urlPattern: "dev.to",
  },
  "Stack Overflow": {
    color: "#f48024",
    icon: "❓",
    urlPlaceholder: "https://stackoverflow.com/users/yourprofile",
    urlPattern: "stackoverflow.com",
  },
  Reddit: {
    color: "#ff4500",
    icon: "🤖",
    urlPlaceholder: "https://reddit.com/u/yourusername",
    urlPattern: "reddit.com",
  },
  Pinterest: {
    color: "#bd081c",
    icon: "📌",
    urlPlaceholder: "https://pinterest.com/yourusername",
    urlPattern: "pinterest.com",
  },
  Twitch: {
    color: "#9146ff",
    icon: "🎮",
    urlPlaceholder: "https://twitch.tv/yourusername",
    urlPattern: "twitch.tv",
  },
  Spotify: {
    color: "#1db954",
    icon: "🎶",
    urlPlaceholder: "https://open.spotify.com/user/yourusername",
    urlPattern: "spotify.com",
  },
  SoundCloud: {
    color: "#ff5500",
    icon: "🎧",
    urlPlaceholder: "https://soundcloud.com/yourusername",
    urlPattern: "soundcloud.com",
  },
  Other: {
    color: "#6b7280",
    icon: "🔗",
    urlPlaceholder: "https://yourlink.com",
    urlPattern: "https://",
  },
};

// Computed
const selectedPlatform = computed(() => {
  return socialPlatforms[form.platform] || socialPlatforms["Other"];
});

const urlPlaceholder = computed(() => {
  return selectedPlatform.value.urlPlaceholder;
});

// Methods
const submitForm = () => {
  form.post("/admin/social-links", {
    onSuccess: () => {
      // Success handled by redirect
    },
  });
};

const resetForm = () => {
  form.reset();
  form.clearErrors();
};

const cancelCreate = () => {
  router.visit("/admin/social-links");
};

const selectPlatform = (platform) => {
  form.platform = platform;
  form.color = socialPlatforms[platform].color;

  // Clear URL when changing platform
  form.url = "";
};

const getColorStyle = (color) => {
  return {
    backgroundColor: color,
    color: "#ffffff",
  };
};

const validateUrl = (url) => {
  if (!url) return false;
  try {
    new URL(url);
    return true;
  } catch {
    // Check for mailto: links
    return url.startsWith("mailto:");
  }
};

const getUrlSuggestion = () => {
  if (!form.platform || form.platform === "Other") return "";
  return selectedPlatform.value.urlPlaceholder;
};

// Initialize with URL params if provided
onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search);
  const platform = urlParams.get("platform");
  const color = urlParams.get("color");

  if (platform && socialPlatforms[platform]) {
    form.platform = platform;
    form.color = color || socialPlatforms[platform].color;
  }
});

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Social Links", href: "/admin/social-links" },
  { name: "Create", href: "/admin/social-links/create", current: true },
];
</script>

<template>
  <Head title="Add New Social Link" />

  <DashboardLayout :title="'Add New Social Link'" :breadcrumbs="breadcrumbs">
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

      <!-- Preview Toggle -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Social Link Details
          </h2>
          <button
            type="button"
            @click="previewMode = !previewMode"
            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
          >
            {{ previewMode ? "Edit" : "Preview" }}
          </button>
        </div>
      </div>

      <!-- Preview Mode -->
      <div
        v-if="previewMode"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="max-w-md mx-auto">
          <div class="text-center">
            <div
              class="w-20 h-20 mx-auto rounded-full flex items-center justify-center text-3xl mb-4 shadow-lg"
              :style="getColorStyle(form.color)"
            >
              {{ selectedPlatform.icon }}
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
              {{ form.platform || "Platform Name" }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
              {{ form.url || "URL will appear here" }}
            </p>
            <div class="mt-4 flex items-center justify-center space-x-4">
              <div class="flex items-center">
                <div
                  class="w-3 h-3 rounded-full mr-2"
                  :style="{ backgroundColor: form.color }"
                ></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                  {{ form.color }}
                </span>
              </div>
              <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="
                  form.is_active
                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                "
              >
                {{ form.is_active ? "Active" : "Inactive" }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Mode -->
      <div v-else class="space-y-6">
        <!-- Platform Selection -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Select Platform
          </h2>

          <!-- Popular Platforms Grid -->
          <div
            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6"
          >
            <button
              v-for="(config, platform) in socialPlatforms"
              :key="platform"
              type="button"
              @click="selectPlatform(platform)"
              class="p-4 border-2 rounded-lg transition-all duration-200 hover:shadow-md"
              :class="
                form.platform === platform
                  ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20'
                  : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'
              "
            >
              <div class="flex flex-col items-center space-y-2">
                <div
                  class="w-12 h-12 rounded-full flex items-center justify-center text-xl"
                  :style="getColorStyle(config.color)"
                >
                  {{ config.icon }}
                </div>
                <span class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ platform }}
                </span>
              </div>
            </button>
          </div>

          <!-- Custom Platform Input -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
              Or enter custom platform name:
            </label>
            <input
              v-model="form.platform"
              type="text"
              placeholder="e.g., Custom Platform"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
            <p v-if="form.errors.color" class="mt-1 text-sm text-red-500">
              {{ form.errors.color }}
            </p>
            <p class="mt-1 text-sm text-gray-500">
              Choose a color that represents the platform or matches your brand
            </p>
          </div>
        </div>

        <!-- Settings -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Settings
          </h2>

          <div class="space-y-4">
            <!-- Active Status -->
            <div>
              <div class="flex items-center">
                <input
                  v-model="form.is_active"
                  id="is_active"
                  type="checkbox"
                  class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                />
                <label
                  for="is_active"
                  class="ml-2 block text-sm text-gray-700 dark:text-gray-300"
                >
                  Active (visible on portfolio)
                </label>
              </div>
              <p class="mt-1 text-sm text-gray-500">
                Active links will be displayed on your portfolio. Inactive links
                are hidden but can be activated later.
              </p>
              <p v-if="form.errors.is_active" class="mt-1 text-sm text-red-500">
                {{ form.errors.is_active }}
              </p>
            </div>

            <!-- Display Order -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Display Order
              </label>
              <input
                v-model.number="form.order"
                type="number"
                min="0"
                placeholder="0"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
              <p v-if="form.errors.order" class="mt-1 text-sm text-red-500">
                {{ form.errors.order }}
              </p>
              <p class="mt-1 text-sm text-gray-500">
                Lower numbers appear first. Use 0 for automatic ordering.
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
              Reset Form
            </button>

            <button
              type="button"
              @click="cancelCreate"
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
              Saving...
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
              {{ form.processing ? "Creating..." : "Create Social Link" }}
            </button>
          </div>
        </div>
      </div>
    </form>
  </DashboardLayout>
</template>
