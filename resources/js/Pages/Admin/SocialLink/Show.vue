<script setup>
import { Head, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  socialLink: Object,
});

// Refs
const isDeleting = ref(false);

// Computed
const platformIcon = computed(() => {
  const icons = {
    GitHub: "🐙",
    LinkedIn: "💼",
    Twitter: "🐦",
    Instagram: "📷",
    Facebook: "📘",
    YouTube: "🎥",
    TikTok: "🎵",
    Email: "✉️",
    WhatsApp: "📱",
    Telegram: "✈️",
    Discord: "🎮",
    Website: "🌐",
  };
  return icons[props.socialLink.platform] || "🔗";
});

const platformDescription = computed(() => {
  const descriptions = {
    GitHub: "Code repository and development portfolio",
    LinkedIn: "Professional networking and career information",
    Twitter: "Social updates and professional insights",
    Instagram: "Visual content and personal branding",
    Facebook: "Social networking and community engagement",
    YouTube: "Video content and tutorials",
    TikTok: "Short-form video content",
    Email: "Direct communication channel",
    WhatsApp: "Instant messaging and quick communication",
    Telegram: "Secure messaging and updates",
    Discord: "Community and real-time communication",
    Website: "Personal website and blog",
  };
  return descriptions[props.socialLink.platform] || "Social media platform";
});

const statusColor = computed(() => {
  return props.socialLink.is_active
    ? "bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400"
    : "bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400";
});

const statusText = computed(() => {
  return props.socialLink.is_active ? "Active" : "Inactive";
});

// Methods
const toggleActive = () => {
  router.patch(
    route("admin.social-links.toggle-active", props.socialLink.id),
    {},
    {
      preserveScroll: true,
      onSuccess: () => {
        // Success feedback handled by flash messages
      },
    }
  );
};

const deleteSocialLink = () => {
  if (
    !confirm(`Are you sure you want to delete ${props.socialLink.platform}?`)
  ) {
    return;
  }

  isDeleting.value = true;
  router.delete(route("admin.social-links.destroy", props.socialLink.id), {
    onFinish: () => {
      isDeleting.value = false;
    },
  });
};

const formatDate = (dateString) => {
  if (!dateString) return "N/A";
  const date = new Date(dateString);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const copyToClipboard = async (text) => {
  try {
    await navigator.clipboard.writeText(text);
    // You could add a toast notification here
  } catch (err) {
    console.error("Failed to copy: ", err);
  }
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Social Links", href: "/admin/social-links" },
  {
    name: props.socialLink.platform,
    href: `/admin/social-links/${props.socialLink.id}`,
    current: true,
  },
];
</script>

<template>
  <Head :title="`${socialLink.platform} - Social Link Details`" />

  <DashboardLayout
    :title="`${socialLink.platform} Details`"
    :breadcrumbs="breadcrumbs"
  >
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header Section -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div
              class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg"
              :style="{ backgroundColor: socialLink.color }"
            >
              {{ platformIcon }}
            </div>
            <div>
              <h1
                class="text-2xl font-bold text-gray-900 dark:text-white flex items-center"
              >
                {{ socialLink.platform }}
                <span
                  :class="[
                    'ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    statusColor,
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
                  {{ statusText }}
                </span>
              </h1>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ platformDescription }}
              </p>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <Link
              :href="`/admin/social-links/${socialLink.id}/edit`"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150"
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
              Edit
            </Link>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h2
          class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
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
              d="M13 10V3L4 14h7v7l9-11h-7z"
            />
          </svg>
          Quick Actions
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Visit Link -->
          <a
            :href="socialLink.url"
            target="_blank"
            rel="noopener noreferrer"
            class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-200 group"
          >
            <div class="flex-shrink-0">
              <svg
                class="w-8 h-8 text-blue-600 group-hover:text-blue-700"
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
            </div>
            <div class="ml-4">
              <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                Visit {{ socialLink.platform }}
              </h4>
              <p class="text-xs text-gray-600 dark:text-gray-400">
                Open in new tab
              </p>
            </div>
          </a>

          <!-- Copy URL -->
          <button
            @click="copyToClipboard(socialLink.url)"
            class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-200 group"
          >
            <div class="flex-shrink-0">
              <svg
                class="w-8 h-8 text-green-600 group-hover:text-green-700"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                />
              </svg>
            </div>
            <div class="ml-4">
              <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                Copy URL
              </h4>
              <p class="text-xs text-gray-600 dark:text-gray-400">
                Copy to clipboard
              </p>
            </div>
          </button>

          <!-- Toggle Status -->
          <button
            @click="toggleActive"
            class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-200 group"
          >
            <div class="flex-shrink-0">
              <svg
                v-if="socialLink.is_active"
                class="w-8 h-8 text-orange-600 group-hover:text-orange-700"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"
                />
              </svg>
              <svg
                v-else
                class="w-8 h-8 text-green-600 group-hover:text-green-700"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
            <div class="ml-4">
              <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                {{ socialLink.is_active ? "Deactivate" : "Activate" }}
              </h4>
              <p class="text-xs text-gray-600 dark:text-gray-400">
                {{
                  socialLink.is_active ? "Hide from public" : "Show in public"
                }}
              </p>
            </div>
          </button>
        </div>
      </div>

      <!-- Link Details -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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

          <div class="space-y-6">
            <!-- Platform -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Platform
              </label>
              <div class="flex items-center space-x-3">
                <div
                  class="w-8 h-8 rounded flex items-center justify-center text-white text-lg"
                  :style="{ backgroundColor: socialLink.color }"
                >
                  {{ platformIcon }}
                </div>
                <span class="text-sm text-gray-900 dark:text-white font-medium">
                  {{ socialLink.platform }}
                </span>
              </div>
            </div>

            <!-- URL -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                URL
              </label>
              <div class="flex items-center space-x-2">
                <code
                  class="flex-1 px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md text-sm font-mono text-gray-900 dark:text-white"
                >
                  {{ socialLink.url }}
                </code>
                <button
                  @click="copyToClipboard(socialLink.url)"
                  class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                  title="Copy URL"
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
                      d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                    />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Brand Color -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Brand Color
              </label>
              <div class="flex items-center space-x-3">
                <div
                  class="w-8 h-8 rounded border border-gray-200 dark:border-gray-600"
                  :style="{ backgroundColor: socialLink.color }"
                ></div>
                <code
                  class="px-3 py-1 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded text-sm font-mono text-gray-900 dark:text-white"
                >
                  {{ socialLink.color }}
                </code>
              </div>
            </div>

            <!-- Display Order -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Display Order
              </label>
              <span
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400"
              >
                Position {{ socialLink.order }}
              </span>
            </div>

            <!-- Status -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Status
              </label>
              <span
                :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  statusColor,
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
                {{ statusText }}
              </span>
            </div>
          </div>
        </div>

        <!-- Metadata -->
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
                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
              />
            </svg>
            Metadata
          </h2>

          <div class="space-y-6">
            <!-- ID -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                ID
              </label>
              <code
                class="px-3 py-1 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded text-sm font-mono text-gray-900 dark:text-white"
              >
                {{ socialLink.id }}
              </code>
            </div>

            <!-- Created At -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Created
              </label>
              <div
                class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400"
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
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                <span>{{ formatDate(socialLink.created_at) }}</span>
              </div>
            </div>

            <!-- Updated At -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Last Updated
              </label>
              <div
                class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400"
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
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                  />
                </svg>
                <span>{{ formatDate(socialLink.updated_at) }}</span>
              </div>
            </div>
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
          Public Preview
        </h2>

        <div
          class="border border-gray-200 dark:border-gray-600 rounded-lg p-6 bg-gray-50 dark:bg-gray-700/20"
        >
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            This is how your social link appears on the public portfolio:
          </p>

          <div
            class="flex items-center space-x-4 p-4 rounded-2xl bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600"
          >
            <div
              class="w-12 h-12 rounded-full flex items-center justify-center text-white text-xl shadow-lg"
              :style="{ backgroundColor: socialLink.color }"
            >
              {{ platformIcon }}
            </div>
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ socialLink.platform }}
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ platformDescription }}
              </p>
            </div>
            <div class="text-right">
              <span
                v-if="socialLink.is_active"
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400"
              >
                <span
                  class="w-2 h-2 mr-1 bg-green-500 rounded-full animate-pulse"
                ></span>
                Visible
              </span>
              <span
                v-else
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400"
              >
                <span class="w-2 h-2 mr-1 bg-gray-400 rounded-full"></span>
                Hidden
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Danger Zone -->
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
