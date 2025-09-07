<!-- resources/js/Pages/Admin/SocialLink/Index.vue -->
<script setup>
import { ref, computed } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  socialLinks: {
    type: Array,
    required: true,
  },
});

// Page data
const page = usePage();
const flash = computed(() => page.props.flash || {});

// Reactive state
const selectedSocialLinks = ref([]);
const sortBy = ref("order");
const sortOrder = ref("asc");
const searchQuery = ref("");
const filterStatus = ref("all");

// Social platform icons mapping
const platformIcons = {
  GitHub: "👨‍💻",
  LinkedIn: "💼",
  Twitter: "🐦",
  Instagram: "📸",
  Facebook: "📘",
  YouTube: "📺",
  TikTok: "🎵",
  Discord: "🎮",
  Telegram: "✈️",
  WhatsApp: "💬",
  Email: "📧",
  Website: "🌐",
  Blog: "📝",
  Portfolio: "🎨",
  Resume: "📄",
  Behance: "🎭",
  Dribbble: "🏀",
  Medium: "📚",
  "Dev.to": "💻",
  "Stack Overflow": "❓",
  Reddit: "🤖",
  Pinterest: "📌",
  Twitch: "🎮",
  Spotify: "🎶",
  SoundCloud: "🎧",
  Slack: "💬",
  Skype: "📞",
  Zoom: "📹",
  Other: "🔗",
};

// Common social platforms with default colors
const commonPlatforms = [
  { name: "GitHub", color: "#333333", icon: "👨‍💻" },
  { name: "LinkedIn", color: "#0077b5", icon: "💼" },
  { name: "Twitter", color: "#1da1f2", icon: "🐦" },
  { name: "Instagram", color: "#e4405f", icon: "📸" },
  { name: "Facebook", color: "#4267b2", icon: "📘" },
  { name: "YouTube", color: "#ff0000", icon: "📺" },
  { name: "Email", color: "#ea4335", icon: "📧" },
  { name: "Website", color: "#6366f1", icon: "🌐" },
];

// Computed
const filteredSocialLinks = computed(() => {
  let filtered = props.socialLinks;

  // Search filter
  if (searchQuery.value) {
    filtered = filtered.filter(
      (link) =>
        link.platform.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        link.url.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  // Status filter
  if (filterStatus.value === "active") {
    filtered = filtered.filter((link) => link.is_active);
  } else if (filterStatus.value === "inactive") {
    filtered = filtered.filter((link) => !link.is_active);
  }

  // Sort
  filtered.sort((a, b) => {
    let aVal = a[sortBy.value];
    let bVal = b[sortBy.value];

    if (sortBy.value === "order") {
      aVal = parseInt(aVal) || 0;
      bVal = parseInt(bVal) || 0;
    }

    if (sortOrder.value === "asc") {
      return aVal < bVal ? -1 : aVal > bVal ? 1 : 0;
    } else {
      return aVal > bVal ? -1 : aVal < bVal ? 1 : 0;
    }
  });

  return filtered;
});

const socialLinkStats = computed(() => ({
  total: props.socialLinks.length,
  active: props.socialLinks.filter((link) => link.is_active).length,
  inactive: props.socialLinks.filter((link) => !link.is_active).length,
  unique_platforms: [...new Set(props.socialLinks.map((link) => link.platform))]
    .length,
}));

// Methods
const deleteSocialLink = (socialLinkId) => {
  if (confirm("Are you sure you want to delete this social link?")) {
    router.delete(`/admin/social-links/${socialLinkId}`, {
      onSuccess: () => {
        selectedSocialLinks.value = selectedSocialLinks.value.filter(
          (id) => id !== socialLinkId
        );
      },
    });
  }
};

const bulkDelete = () => {
  if (selectedSocialLinks.value.length === 0) return;

  if (
    confirm(
      `Are you sure you want to delete ${selectedSocialLinks.value.length} social link(s)?`
    )
  ) {
    router.delete("/admin/social-links/bulk-delete", {
      data: { ids: selectedSocialLinks.value },
      onSuccess: () => {
        selectedSocialLinks.value = [];
      },
    });
  }
};

const toggleActive = (socialLink) => {
  router.patch(
    `/admin/social-links/${socialLink.id}/toggle-active`,
    {},
    {
      preserveScroll: true,
      onSuccess: () => {
        // Success handled by page refresh
      },
    }
  );
};

const getPlatformIcon = (platform) => {
  return platformIcons[platform] || platformIcons["Other"];
};

const getColorStyle = (color) => {
  return {
    backgroundColor: color || "#6366f1",
    color: "#ffffff",
  };
};

const getDomainFromUrl = (url) => {
  try {
    const domain = new URL(url).hostname.replace("www.", "");
    return domain;
  } catch {
    return url;
  }
};

const moveUp = (socialLink) => {
  const currentIndex = props.socialLinks.findIndex(
    (link) => link.id === socialLink.id
  );
  if (currentIndex > 0) {
    const newOrder = props.socialLinks[currentIndex - 1].order;
    updateOrder(socialLink.id, newOrder);
  }
};

const moveDown = (socialLink) => {
  const currentIndex = props.socialLinks.findIndex(
    (link) => link.id === socialLink.id
  );
  if (currentIndex < props.socialLinks.length - 1) {
    const newOrder = props.socialLinks[currentIndex + 1].order;
    updateOrder(socialLink.id, newOrder);
  }
};

const updateOrder = (id, newOrder) => {
  router.patch(
    `/admin/social-links/${id}`,
    { order: newOrder },
    {
      preserveScroll: true,
      onSuccess: () => {
        // Success handled by page refresh
      },
    }
  );
};

const toggleSelectAll = () => {
  if (selectedSocialLinks.value.length === filteredSocialLinks.value.length) {
    selectedSocialLinks.value = [];
  } else {
    selectedSocialLinks.value = filteredSocialLinks.value.map((l) => l.id);
  }
};

const sortTable = (column) => {
  if (sortBy.value === column) {
    sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
  } else {
    sortBy.value = column;
    sortOrder.value = "asc";
  }
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Social Links", href: "/admin/social-links", current: true },
];
</script>

<template>
  <Head title="Social Links Management" />

  <DashboardLayout
    :title="'Social Links Management'"
    :breadcrumbs="breadcrumbs"
  >
    <div class="space-y-6">
      <!-- Success Flash Message -->
      <div
        v-if="flash.success"
        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4"
      >
        <div class="flex">
          <div class="flex-shrink-0">
            <svg
              class="h-5 w-5 text-green-400"
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
          <div class="ml-3">
            <p class="text-sm font-medium text-green-800 dark:text-green-200">
              {{ flash.success }}
            </p>
          </div>
        </div>
      </div>

      <!-- Header Section -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
        >
          <div>
            <h1
              class="text-2xl font-bold text-gray-900 dark:text-white flex items-center"
            >
              <svg
                class="w-8 h-8 mr-3 text-cyan-600"
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
              Social Links Management
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Manage your social media profiles and external links
            </p>
          </div>

          <Link
            href="/admin/social-links/create"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
            Add Social Link
          </Link>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-cyan-100 dark:bg-cyan-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-cyan-600 dark:text-cyan-400"
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
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Total Links
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ socialLinkStats.total }}
              </p>
            </div>
          </div>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-green-600 dark:text-green-400"
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
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Active
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ socialLinkStats.active }}
              </p>
            </div>
          </div>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-gray-600 dark:text-gray-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 21l-3-3m0 0L9 12m6 6l6-6m-6 6L9 12"
                  />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Inactive
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ socialLinkStats.inactive }}
              </p>
            </div>
          </div>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-purple-600 dark:text-purple-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                  />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Platforms
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ socialLinkStats.unique_platforms }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Popular Platforms Suggestion -->
      <div
        v-if="socialLinkStats.total === 0"
        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6"
      >
        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-200 mb-4">
          Quick Start: Add Popular Social Platforms
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
          <Link
            v-for="platform in commonPlatforms"
            :key="platform.name"
            :href="`/admin/social-links/create?platform=${
              platform.name
            }&color=${encodeURIComponent(platform.color)}`"
            class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
          >
            <div
              class="w-8 h-8 rounded-lg flex items-center justify-center text-sm mr-3"
              :style="getColorStyle(platform.color)"
            >
              {{ platform.icon }}
            </div>
            <span class="text-sm font-medium text-gray-900 dark:text-white">{{
              platform.name
            }}</span>
          </Link>
        </div>
      </div>

      <!-- Filters and Search -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
        >
          <div
            class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4"
          >
            <!-- Search -->
            <div class="relative">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
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
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  />
                </svg>
              </div>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search social links..."
                class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
            </div>

            <!-- Status Filter -->
            <select
              v-model="filterStatus"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="all">All Status</option>
              <option value="active">Active Only</option>
              <option value="inactive">Inactive Only</option>
            </select>
          </div>

          <!-- Bulk Actions -->
          <div
            v-if="selectedSocialLinks.length > 0"
            class="flex items-center space-x-4"
          >
            <span class="text-sm text-gray-600 dark:text-gray-400">
              {{ selectedSocialLinks.length }} selected
            </span>
            <button
              @click="bulkDelete"
              class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
              Delete Selected
            </button>
          </div>
        </div>
      </div>

      <!-- Social Links Table -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
      >
        <div class="overflow-x-auto">
          <table
            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
          >
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-6 py-3 text-left">
                  <input
                    type="checkbox"
                    :checked="
                      selectedSocialLinks.length ===
                        filteredSocialLinks.length &&
                      filteredSocialLinks.length > 0
                    "
                    @change="toggleSelectAll"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                  />
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                  @click="sortTable('order')"
                >
                  <div class="flex items-center space-x-1">
                    <span>Order</span>
                    <svg
                      v-if="sortBy === 'order'"
                      class="w-4 h-4"
                      :class="sortOrder === 'asc' ? 'transform rotate-180' : ''"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                  @click="sortTable('platform')"
                >
                  <div class="flex items-center space-x-1">
                    <span>Platform</span>
                    <svg
                      v-if="sortBy === 'platform'"
                      class="w-4 h-4"
                      :class="sortOrder === 'asc' ? 'transform rotate-180' : ''"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  URL
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Status
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody
              class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
            >
              <tr
                v-for="(socialLink, index) in filteredSocialLinks"
                :key="socialLink.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <input
                    v-model="selectedSocialLinks"
                    :value="socialLink.id"
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                  />
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center space-x-3">
                    <span
                      class="text-sm font-mono text-gray-600 dark:text-gray-400"
                    >
                      {{ socialLink.order }}
                    </span>
                    <div class="flex flex-col space-y-1">
                      <button
                        @click="moveUp(socialLink)"
                        :disabled="index === 0"
                        class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-50"
                        title="Move Up"
                      >
                        <svg
                          class="w-3 h-3"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 15l7-7 7 7"
                          />
                        </svg>
                      </button>
                      <button
                        @click="moveDown(socialLink)"
                        :disabled="index === filteredSocialLinks.length - 1"
                        class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-50"
                        title="Move Down"
                      >
                        <svg
                          class="w-3 h-3"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                          />
                        </svg>
                      </button>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div
                      class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center text-white text-lg"
                      :style="getColorStyle(socialLink.color)"
                    >
                      {{ getPlatformIcon(socialLink.platform) }}
                    </div>
                    <div class="ml-4">
                      <div
                        class="text-sm font-medium text-gray-900 dark:text-white"
                      >
                        {{ socialLink.platform }}
                      </div>
                      <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ socialLink.color }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-2">
                    <a
                      :href="socialLink.url"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 truncate max-w-xs"
                      :title="socialLink.url"
                    >
                      {{ getDomainFromUrl(socialLink.url) }}
                    </a>
                    <svg
                      class="w-3 h-3 text-gray-400"
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
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button
                    @click="toggleActive(socialLink)"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    :class="
                      socialLink.is_active
                        ? 'bg-indigo-600'
                        : 'bg-gray-200 dark:bg-gray-600'
                    "
                  >
                    <span
                      class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                      :class="
                        socialLink.is_active ? 'translate-x-6' : 'translate-x-1'
                      "
                    ></span>
                  </button>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center space-x-3">
                    <Link
                      :href="`/admin/social-links/${socialLink.id}`"
                      class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                      title="View Details"
                    >
                      <svg
                        class="w-5 h-5"
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
                    </Link>
                    <Link
                      :href="`/admin/social-links/${socialLink.id}/edit`"
                      class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
                      title="Edit"
                    >
                      <svg
                        class="w-5 h-5"
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
                    <a
                      :href="socialLink.url"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300"
                      title="Visit Link"
                    >
                      <svg
                        class="w-5 h-5"
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
                    </a>
                    <button
                      @click="deleteSocialLink(socialLink.id)"
                      class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                      title="Delete"
                    >
                      <svg
                        class="w-5 h-5"
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
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="filteredSocialLinks.length === 0" class="text-center py-12">
          <svg
            class="mx-auto h-12 w-12 text-gray-400"
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
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
            No social links found
          </h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{
              searchQuery
                ? "Try adjusting your search criteria."
                : "Get started by adding your first social link."
            }}
          </p>
          <div v-if="!searchQuery" class="mt-6">
            <Link
              href="/admin/social-links/create"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
              Add Your First Social Link
            </Link>
          </div>
        </div>
      </div>

      <!-- Portfolio Preview -->
      <div
        v-if="socialLinkStats.active > 0"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          Portfolio Preview
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
          This is how your active social links will appear on your portfolio:
        </p>
        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
          <div class="flex justify-center space-x-4">
            <a
              v-for="socialLink in filteredSocialLinks
                .filter((link) => link.is_active)
                .slice(0, 6)"
              :key="socialLink.id"
              :href="socialLink.url"
              target="_blank"
              rel="noopener noreferrer"
              class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg"
              :style="getColorStyle(socialLink.color)"
              :title="socialLink.platform"
            >
              <span class="text-xl">{{
                getPlatformIcon(socialLink.platform)
              }}</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
