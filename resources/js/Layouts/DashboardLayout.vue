<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

// Props
const props = defineProps({
  title: {
    type: String,
    default: "Admin Dashboard",
  },
  breadcrumbs: {
    type: Array,
    default: () => [],
  },
});

// Reactive state
const mobileSidebarOpen = ref(false);
const profileDropdownOpen = ref(false);
const page = usePage();
const expandedGroups = ref(new Set(["content"]));

// SIMPLIFIED Navigation Structure
const navigation = ref([
  {
    name: "Dashboard",
    href: "/dashboard",
    icon: "M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z",
    current: false,
  },
  {
    id: "content",
    name: "Content Management",
    type: "group",
    icon: "📝",
    children: [
      {
        name: "Portfolio",
        href: "/admin/portfolio",
        icon: "M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10",
        gradient: "from-blue-500 to-purple-600",
      },
      {
        name: "Experience",
        href: "/admin/experience",
        icon: "M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6",
        gradient: "from-emerald-500 to-teal-600",
      },
      {
        name: "Skills",
        href: "/admin/skills",
        icon: "M13 10V3L4 14h7v7l9-11h-7z",
        gradient: "from-yellow-500 to-orange-600",
      },
      {
        name: "Achievements",
        href: "/admin/achievements",
        icon: "M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z",
        gradient: "from-pink-500 to-red-600",
      },
      {
        name: "Certifications",
        href: "/admin/certifications",
        icon: "M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4",
        gradient: "from-indigo-500 to-purple-600",
      },
      {
        name: "Languages",
        href: "/admin/languages",
        icon: "M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129",
        gradient: "from-green-500 to-emerald-600",
      },
      {
        name: "Interests",
        href: "/admin/interests",
        icon: "M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z",
        gradient: "from-rose-500 to-pink-600",
      },
      {
        name: "Social Links",
        href: "/admin/social-links",
        icon: "M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1",
        gradient: "from-cyan-500 to-blue-600",
      },
    ],
  },
  {
    id: "analytics",
    name: "Analytics & Reports",
    type: "group",
    icon: "📊",
    children: [
      {
        name: "Analytics",
        href: "/admin/analytics",
        icon: "M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z",
        gradient: "from-violet-500 to-purple-600",
      },
    ],
  },
  {
    name: "Settings",
    href: "/admin/profile-settings",
    icon: "M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z",
    current: false,
  },
]);

// Computed properties
const currentUser = computed(() => page.props.auth?.user);

const isCurrentRoute = (href) => {
  if (!href) return false;
  return page.url === href || page.url.startsWith(href);
};

// Methods
const toggleMobileSidebar = () => {
  mobileSidebarOpen.value = !mobileSidebarOpen.value;
};

const closeMobileSidebar = () => {
  mobileSidebarOpen.value = false;
};

const toggleGroup = (groupId) => {
  if (expandedGroups.value.has(groupId)) {
    expandedGroups.value.delete(groupId);
  } else {
    expandedGroups.value.add(groupId);
  }
};

const isGroupExpanded = (groupId) => {
  return expandedGroups.value.has(groupId);
};

// SIMPLE CLICK OUTSIDE
const handleClickOutside = (event) => {
  if (
    profileDropdownOpen.value &&
    !event.target.closest(".profile-dropdown-container")
  ) {
    profileDropdownOpen.value = false;
  }
};

// Mount event listener
onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
  <div
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-slate-900 dark:to-gray-900"
  >
    <!-- Mobile sidebar overlay -->
    <div
      v-if="mobileSidebarOpen"
      class="fixed inset-0 z-40 lg:hidden"
      @click="closeMobileSidebar"
    >
      <div
        class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
      ></div>
    </div>

    <!-- Mobile sidebar -->
    <div
      :class="[
        'fixed inset-y-0 left-0 z-50 w-72 transform transition-transform duration-300 ease-in-out lg:hidden',
        mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
    >
      <div
        class="flex h-full flex-col bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border-r border-white/20 dark:border-gray-700/50 shadow-2xl"
      >
        <!-- Mobile Header -->
        <div
          class="flex h-16 items-center justify-between px-6 border-b border-gray-200/50 dark:border-gray-700/50 flex-shrink-0"
        >
          <Link href="/dashboard" class="flex items-center space-x-3 group">
            <div class="relative">
              <div
                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg"
              >
                <ApplicationLogo class="h-6 w-6 text-white" />
              </div>
              <div
                class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white dark:border-gray-900"
              ></div>
            </div>
            <div>
              <span class="text-lg font-bold text-gray-900 dark:text-white"
                >Admin</span
              >
              <div class="text-xs text-gray-500 dark:text-gray-400">Panel</div>
            </div>
          </Link>
          <button
            @click="closeMobileSidebar"
            class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-all duration-200"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
        </div>

        <!-- Mobile Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto scrollable-nav">
          <template v-for="item in navigation" :key="item.name">
            <!-- Dashboard Link -->
            <Link
              v-if="!item.children && !item.type"
              :href="item.href"
              :class="[
                'group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200',
                isCurrentRoute(item.href)
                  ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg'
                  : 'text-gray-700 hover:bg-white/60 dark:text-gray-300 dark:hover:bg-gray-800/60',
              ]"
              @click="closeMobileSidebar"
            >
              <svg
                class="mr-3 h-5 w-5 flex-shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  :d="item.icon"
                ></path>
              </svg>
              {{ item.name }}
            </Link>

            <!-- Group with children -->
            <div v-else-if="item.type === 'group'" class="space-y-1">
              <button
                @click="toggleGroup(item.id)"
                class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100 bg-gray-100/50 dark:bg-gray-800/50 rounded-xl hover:bg-gray-200/50 dark:hover:bg-gray-700/50 transition-all duration-200"
              >
                <div class="flex items-center">
                  <span class="mr-3 text-lg">{{ item.icon }}</span>
                  {{ item.name }}
                </div>
                <svg
                  :class="[
                    'h-4 w-4 transform transition-transform duration-200',
                    isGroupExpanded(item.id) ? 'rotate-90' : '',
                  ]"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  ></path>
                </svg>
              </button>

              <!-- Collapsible Group Content -->
              <div
                v-show="isGroupExpanded(item.id)"
                class="ml-4 space-y-1 border-l-2 border-gray-200 dark:border-gray-700 pl-4"
              >
                <template v-for="child in item.children" :key="child.name">
                  <Link
                    :href="child.href"
                    :class="`flex items-center px-4 py-2.5 text-sm font-bold text-white bg-gradient-to-r ${child.gradient} rounded-lg shadow-md hover:shadow-lg transition-all duration-200`"
                    @click="closeMobileSidebar"
                  >
                    <svg
                      class="mr-3 h-4 w-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        :d="child.icon"
                      ></path>
                    </svg>
                    {{ child.name }}
                  </Link>
                </template>
              </div>
            </div>
          </template>
        </nav>

        <!-- Mobile User Profile -->
        <div
          class="border-t border-gray-200/50 dark:border-gray-700/50 p-4 flex-shrink-0"
        >
          <div
            class="flex items-center space-x-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-800"
          >
            <div
              class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center"
            >
              <span class="text-sm font-bold text-white">
                {{ currentUser?.name?.charAt(0).toUpperCase() }}
              </span>
            </div>
            <div class="flex-1 min-w-0">
              <p
                class="text-sm font-semibold text-gray-900 dark:text-white truncate"
              >
                {{ currentUser?.name }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                {{ currentUser?.email }}
              </p>
            </div>
            <Link
              href="/logout"
              method="post"
              as="button"
              class="p-2 text-gray-400 hover:text-red-500 transition-colors rounded-lg"
              title="Logout"
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
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                ></path>
              </svg>
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Desktop sidebar -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-72 lg:flex-col">
      <div
        class="flex flex-col h-full bg-white/70 dark:bg-gray-900/80 backdrop-blur-xl border-r border-white/20 dark:border-gray-700/50 shadow-2xl"
      >
        <!-- Desktop Header -->
        <div
          class="flex h-16 items-center px-6 border-b border-gray-200/30 dark:border-gray-700/30 flex-shrink-0"
        >
          <Link href="/dashboard" class="flex items-center space-x-3 group">
            <div class="relative">
              <div
                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg"
              >
                <ApplicationLogo class="h-6 w-6 text-white" />
              </div>
              <div
                class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white dark:border-gray-900"
              ></div>
            </div>
            <div>
              <span class="text-xl font-bold text-gray-900 dark:text-white"
                >Admin Panel</span
              >
              <div class="text-xs text-gray-500 dark:text-gray-400">
                Portfolio Management
              </div>
            </div>
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto scrollable-nav">
          <template v-for="item in navigation" :key="item.name">
            <!-- Dashboard Link -->
            <Link
              v-if="!item.children && !item.type"
              :href="item.href"
              :class="[
                'group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300',
                isCurrentRoute(item.href)
                  ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg'
                  : 'text-gray-700 hover:bg-white/80 dark:text-gray-300 dark:hover:bg-gray-800/60',
              ]"
            >
              <svg
                class="mr-3 h-5 w-5 flex-shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  :d="item.icon"
                ></path>
              </svg>
              {{ item.name }}
            </Link>

            <!-- Group with children -->
            <div v-else-if="item.type === 'group'" class="space-y-1">
              <button
                @click="toggleGroup(item.id)"
                class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-xl hover:from-gray-100 hover:to-gray-200 dark:hover:from-gray-700 dark:hover:to-gray-600 transition-all duration-300"
              >
                <div class="flex items-center">
                  <span class="mr-3 text-lg">{{ item.icon }}</span>
                  {{ item.name }}
                </div>
                <svg
                  :class="[
                    'h-4 w-4 transform transition-transform duration-300',
                    isGroupExpanded(item.id) ? 'rotate-90' : '',
                  ]"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  ></path>
                </svg>
              </button>

              <!-- Collapsible Group Content -->
              <div
                v-show="isGroupExpanded(item.id)"
                class="ml-4 space-y-2 border-l-2 border-blue-200 dark:border-blue-700 pl-4"
              >
                <template v-for="child in item.children" :key="child.name">
                  <Link
                    :href="child.href"
                    :class="`flex items-center px-4 py-2.5 text-sm font-bold text-white bg-gradient-to-r ${child.gradient} rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-[1.02]`"
                  >
                    <svg
                      class="mr-3 h-4 w-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        :d="child.icon"
                      ></path>
                    </svg>
                    {{ child.name }}
                  </Link>
                </template>
              </div>
            </div>
          </template>
        </nav>

        <!-- Desktop User Profile -->
        <div
          class="border-t border-gray-200/30 dark:border-gray-700/30 p-4 flex-shrink-0"
        >
          <div
            class="flex items-center space-x-3 p-4 rounded-xl bg-gray-50/50 dark:bg-gray-800/50"
          >
            <div
              class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center"
            >
              <span class="text-lg font-bold text-white">
                {{ currentUser?.name?.charAt(0).toUpperCase() }}
              </span>
            </div>
            <div class="flex-1 min-w-0">
              <p
                class="text-sm font-bold text-gray-900 dark:text-white truncate"
              >
                {{ currentUser?.name }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                {{ currentUser?.email }}
              </p>
            </div>
            <Link
              href="/logout"
              method="post"
              as="button"
              class="p-2.5 text-gray-400 hover:text-red-500 transition-all duration-200 rounded-xl group"
              title="Logout"
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
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                ></path>
              </svg>
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="lg:pl-72">
      <!-- Top header -->
      <div
        class="sticky top-0 z-30 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-white/20 dark:border-gray-700/30 shadow-lg"
      >
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 items-center justify-between">
            <!-- Mobile menu button -->
            <button
              @click="toggleMobileSidebar"
              class="lg:hidden p-2.5 rounded-xl text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-all duration-200"
            >
              <svg
                class="h-6 w-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                ></path>
              </svg>
            </button>

            <!-- Page title -->
            <div class="flex-1 min-w-0 ml-4 lg:ml-0">
              <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ title }}
              </h1>
              <nav v-if="breadcrumbs.length > 0" class="flex mt-1">
                <ol class="inline-flex items-center space-x-1">
                  <li
                    v-for="(breadcrumb, index) in breadcrumbs"
                    :key="index"
                    class="inline-flex items-center"
                  >
                    <svg
                      v-if="index > 0"
                      class="w-3 h-3 text-gray-400 mx-1"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      ></path>
                    </svg>
                    <Link
                      v-if="breadcrumb.href"
                      :href="breadcrumb.href"
                      class="text-sm text-gray-500 hover:text-blue-600 dark:text-gray-400"
                    >
                      {{ breadcrumb.name }}
                    </Link>
                    <span
                      v-else
                      class="text-sm text-gray-600 dark:text-gray-300 font-medium"
                    >
                      {{ breadcrumb.name }}
                    </span>
                  </li>
                </ol>
              </nav>
            </div>

            <!-- Header actions -->
            <div class="flex items-center space-x-3">
              <!-- Search button -->
              <button
                class="p-2.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-white/60 dark:hover:bg-gray-800/60 rounded-xl transition-all duration-200 group"
              >
                <span class="sr-only">Search</span>
                <svg
                  class="h-5 w-5 group-hover:scale-110 transition-transform duration-200"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  ></path>
                </svg>
              </button>

              <!-- Notifications -->
              <button
                class="relative p-2.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-white/60 dark:hover:bg-gray-800/60 rounded-xl transition-all duration-200 group"
              >
                <span class="sr-only">View notifications</span>
                <svg
                  class="h-5 w-5 group-hover:scale-110 transition-transform duration-200"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 17h5l-5 5v-5z M15 17H9a2 2 0 01-2-2V5a2 2 0 012-2h6a2 2 0 012 2v10z"
                  ></path>
                </svg>
                <div
                  class="absolute top-1 right-1 w-3 h-3 bg-red-400 rounded-full border-2 border-white dark:border-gray-900 animate-pulse"
                ></div>
              </button>

              <!-- Profile dropdown - SIMPLE VERSION -->
              <div class="relative profile-dropdown-container">
                <!-- Profile Button -->
                <button
                  @click="profileDropdownOpen = !profileDropdownOpen"
                  class="flex items-center p-1.5 rounded-xl hover:bg-white/60 dark:hover:bg-gray-800/60 transition-all duration-200"
                >
                  <div
                    class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center"
                  >
                    <span class="text-sm font-bold text-white">
                      {{ currentUser?.name?.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                  <svg
                    :class="profileDropdownOpen ? 'rotate-180' : ''"
                    class="ml-2 h-4 w-4 text-gray-400 transition-transform duration-200"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7"
                    ></path>
                  </svg>
                </button>

                <!-- Dropdown Menu -->
                <div
                  v-if="profileDropdownOpen"
                  class="absolute right-0 mt-2 w-60 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-1 z-50"
                >
                  <!-- User Info -->
                  <div
                    class="px-4 py-3 border-b border-gray-200 dark:border-gray-700"
                  >
                    <div class="flex items-center">
                      <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3"
                      >
                        <span class="text-sm font-bold text-white">
                          {{ currentUser?.name?.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                      <div>
                        <p
                          class="text-sm font-medium text-gray-900 dark:text-white"
                        >
                          {{ currentUser?.name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                          {{ currentUser?.email }}
                        </p>
                      </div>
                    </div>
                  </div>

                  <!-- Menu Items -->
                  <div class="py-1">
                    <Link
                      href="/profile"
                      @click="profileDropdownOpen = false"
                      class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                      <svg
                        class="mr-3 h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        ></path>
                      </svg>
                      Profile Settings
                    </Link>

                    <Link
                      href="/profile/password"
                      @click="profileDropdownOpen = false"
                      class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                      <svg
                        class="mr-3 h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"
                        ></path>
                      </svg>
                      Change Password
                    </Link>

                    <Link
                      href="/admin/profile-settings"
                      @click="profileDropdownOpen = false"
                      class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                      <svg
                        class="mr-3 h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        ></path>
                      </svg>
                      Admin Settings
                    </Link>

                    <div
                      class="border-t border-gray-200 dark:border-gray-700 my-1"
                    ></div>

                    <Link
                      href="/"
                      @click="profileDropdownOpen = false"
                      class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                      <svg
                        class="mr-3 h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        ></path>
                      </svg>
                      View Portfolio
                    </Link>

                    <div
                      class="border-t border-gray-200 dark:border-gray-700 my-1"
                    ></div>

                    <Link
                      href="/logout"
                      method="post"
                      as="button"
                      @click="profileDropdownOpen = false"
                      class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20"
                    >
                      <svg
                        class="mr-3 h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                        ></path>
                      </svg>
                      Sign Out
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Page content -->
      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
/* FORCE SCROLLABLE NAVIGATION */
.scrollable-nav {
  height: calc(100vh - 12rem) !important;
  overflow-y: scroll !important;
  scrollbar-width: thin;
  scrollbar-color: rgba(99, 102, 241, 0.4) transparent;
}

.scrollable-nav::-webkit-scrollbar {
  width: 8px;
}

.scrollable-nav::-webkit-scrollbar-track {
  background: transparent;
  border-radius: 4px;
}

.scrollable-nav::-webkit-scrollbar-thumb {
  background: linear-gradient(
    180deg,
    rgba(99, 102, 241, 0.4),
    rgba(168, 85, 247, 0.4)
  );
  border-radius: 4px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.scrollable-nav::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(
    180deg,
    rgba(99, 102, 241, 0.6),
    rgba(168, 85, 247, 0.6)
  );
}

/* Smooth transitions */
.transition-transform {
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.transition-colors {
  transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out;
}

.transition-all {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Glassmorphism effects */
.backdrop-blur-xl {
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
}

/* Focus styles */
button:focus,
a:focus {
  outline: 2px solid rgba(99, 102, 241, 0.5);
  outline-offset: 2px;
  border-radius: 0.75rem;
}

/* Mobile optimizations */
@media (max-width: 1024px) {
  .backdrop-blur-xl {
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
  }
}
</style>
