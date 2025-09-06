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
const sidebarOpen = ref(false);
const mobileSidebarOpen = ref(false);
const page = usePage();

// Navigation items
const navigation = ref([
  {
    name: "Dashboard",
    href: "/dashboard",
    icon: "📊",
    current: false,
    badge: null,
  },
  {
    name: "Portfolio",
    href: "/admin/portfolio",
    icon: "📁",
    current: false,
    children: [
      { name: "All Projects", href: "/admin/portfolio", icon: "📋" },
      { name: "Add New", href: "/admin/portfolio/create", icon: "➕" },
    ],
  },
  {
    name: "Experience",
    href: "/admin/experience",
    icon: "💼",
    current: false,
    children: [
      { name: "All Experience", href: "/admin/experience", icon: "📋" },
      { name: "Add New", href: "/admin/experience/create", icon: "➕" },
    ],
  },
  {
    name: "Skills",
    href: "/admin/skills",
    icon: "⚡",
    current: false,
    children: [
      { name: "All Skills", href: "/admin/skills", icon: "📋" },
      { name: "Add New", href: "/admin/skills/create", icon: "➕" },
    ],
  },
  {
    name: "Analytics",
    href: "/admin/analytics",
    icon: "📈",
    current: false,
    children: [
      { name: "Overview", href: "/admin/analytics", icon: "📊" },
      { name: "Visitors", href: "/admin/analytics/visitors", icon: "👥" },
      { name: "Projects", href: "/admin/analytics/projects", icon: "📁" },
      {
        name: "Contact Forms",
        href: "/admin/analytics/contact-forms",
        icon: "📧",
      },
    ],
  },
  {
    name: "Settings",
    href: "/admin/profile-settings",
    icon: "⚙️",
    current: false,
  },
]);

// Computed properties
const currentUser = computed(() => page.props.auth?.user);

const isCurrentRoute = (href) => {
  return page.url === href || page.url.startsWith(href);
};

const updateActiveStates = () => {
  navigation.value.forEach((item) => {
    item.current = isCurrentRoute(item.href);
    if (item.children) {
      item.children.forEach((child) => {
        child.current = isCurrentRoute(child.href);
      });
    }
  });
};

// Methods
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const toggleMobileSidebar = () => {
  mobileSidebarOpen.value = !mobileSidebarOpen.value;
};

const closeMobileSidebar = () => {
  mobileSidebarOpen.value = false;
};

// Lifecycle
onMounted(() => {
  updateActiveStates();
});

// Watch for route changes
const stopWatching = page.props
  ? () => {
      updateActiveStates();
    }
  : null;

onUnmounted(() => {
  if (stopWatching) stopWatching();
});
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Mobile sidebar overlay -->
    <div
      v-if="mobileSidebarOpen"
      class="fixed inset-0 z-40 lg:hidden"
      @click="closeMobileSidebar"
    >
      <div
        class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity"
      ></div>
    </div>

    <!-- Mobile sidebar -->
    <div
      :class="[
        'fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out lg:hidden',
        mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
    >
      <div
        class="flex h-16 items-center justify-between px-4 border-b border-gray-200 dark:border-gray-700"
      >
        <Link href="/dashboard" class="flex items-center space-x-2">
          <ApplicationLogo class="h-8 w-8 text-indigo-600" />
          <span class="text-lg font-semibold text-gray-900 dark:text-white"
            >Admin</span
          >
        </Link>
        <button
          @click="closeMobileSidebar"
          class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
        >
          <span class="sr-only">Close sidebar</span>
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
            />
          </svg>
        </button>
      </div>

      <!-- Mobile Navigation -->
      <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
        <template v-for="item in navigation" :key="item.name">
          <div v-if="item.children" class="space-y-1">
            <div
              class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-2"
            >
              {{ item.name }}
            </div>
            <Link
              v-for="child in item.children"
              :key="child.name"
              :href="child.href"
              :class="[
                'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                isCurrentRoute(child.href)
                  ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700',
              ]"
              @click="closeMobileSidebar"
            >
              <span class="mr-3">{{ child.icon }}</span>
              {{ child.name }}
            </Link>
          </div>
          <Link
            v-else
            :href="item.href"
            :class="[
              'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
              isCurrentRoute(item.href)
                ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700',
            ]"
            @click="closeMobileSidebar"
          >
            <span class="mr-3">{{ item.icon }}</span>
            {{ item.name }}
            <span
              v-if="item.badge"
              class="ml-auto bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full"
            >
              {{ item.badge }}
            </span>
          </Link>
        </template>
      </nav>
    </div>

    <!-- Desktop sidebar -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
      <div
        class="flex flex-col flex-grow bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-sm"
      >
        <!-- Logo -->
        <div
          class="flex h-16 items-center px-4 border-b border-gray-200 dark:border-gray-700"
        >
          <Link href="/dashboard" class="flex items-center space-x-2">
            <ApplicationLogo class="h-8 w-8 text-indigo-600" />
            <span class="text-lg font-semibold text-gray-900 dark:text-white"
              >Admin Panel</span
            >
          </Link>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
          <template v-for="item in navigation" :key="item.name">
            <div v-if="item.children" class="space-y-1">
              <div
                class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-2"
              >
                {{ item.name }}
              </div>
              <Link
                v-for="child in item.children"
                :key="child.name"
                :href="child.href"
                :class="[
                  'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                  isCurrentRoute(child.href)
                    ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200'
                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700',
                ]"
              >
                <span class="mr-3">{{ child.icon }}</span>
                {{ child.name }}
              </Link>
            </div>
            <Link
              v-else
              :href="item.href"
              :class="[
                'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                isCurrentRoute(item.href)
                  ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700',
              ]"
            >
              <span class="mr-3">{{ item.icon }}</span>
              {{ item.name }}
              <span
                v-if="item.badge"
                class="ml-auto bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full"
              >
                {{ item.badge }}
              </span>
            </Link>
          </template>
        </nav>

        <!-- User section -->
        <div
          class="flex-shrink-0 border-t border-gray-200 dark:border-gray-700 p-4"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center"
              >
                <span class="text-sm font-medium text-white">
                  {{ currentUser?.name?.charAt(0).toUpperCase() }}
                </span>
              </div>
            </div>
            <div class="ml-3 flex-1 min-w-0">
              <p
                class="text-sm font-medium text-gray-900 dark:text-white truncate"
              >
                {{ currentUser?.name }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                {{ currentUser?.email }}
              </p>
            </div>
            <div class="ml-3">
              <Link
                href="/logout"
                method="post"
                as="button"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
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
                  />
                </svg>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="lg:pl-64">
      <!-- Top header -->
      <div
        class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm"
      >
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 items-center justify-between">
            <!-- Mobile menu button -->
            <button
              @click="toggleMobileSidebar"
              class="lg:hidden -ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
              <span class="sr-only">Open sidebar</span>
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
                />
              </svg>
            </button>

            <!-- Page title and breadcrumbs -->
            <div class="flex-1 min-w-0">
              <h1 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ title }}
              </h1>
              <nav
                v-if="breadcrumbs.length > 0"
                class="flex mt-1"
                aria-label="Breadcrumb"
              >
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
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
                        fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    <Link
                      v-if="breadcrumb.href"
                      :href="breadcrumb.href"
                      class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    >
                      {{ breadcrumb.name }}
                    </Link>
                    <span
                      v-else
                      class="text-sm text-gray-500 dark:text-gray-400"
                    >
                      {{ breadcrumb.name }}
                    </span>
                  </li>
                </ol>
              </nav>
            </div>

            <!-- Right side actions -->
            <div class="flex items-center space-x-4">
              <!-- Visit portfolio -->
              <Link
                href="/"
                target="_blank"
                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
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
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                  />
                </svg>
                View Portfolio
              </Link>

              <!-- Profile dropdown -->
              <div class="relative">
                <button
                  class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  <div
                    class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center"
                  >
                    <span class="text-sm font-medium text-white">
                      {{ currentUser?.name?.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Page content -->
      <main class="flex-1">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Smooth transitions */
.transition-transform {
  transition: transform 0.3s ease-in-out;
}

.transition-colors {
  transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out;
}

/* Scrollbar styling */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: rgba(156, 163, 175, 0.5);
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(156, 163, 175, 0.7);
}

/* Active navigation states */
.router-link-active {
  @apply bg-indigo-100 text-indigo-700;
}

.dark .router-link-active {
  @apply bg-indigo-900 text-indigo-200;
}

/* Mobile sidebar animations */
@media (max-width: 1023px) {
  .sidebar-enter-active,
  .sidebar-leave-active {
    transition: transform 0.3s ease-in-out;
  }

  .sidebar-enter-from,
  .sidebar-leave-to {
    transform: translateX(-100%);
  }
}

/* Focus styles */
button:focus,
a:focus {
  outline: 2px solid rgba(99, 102, 241, 0.5);
  outline-offset: 2px;
}

/* Hover animations */
.hover-scale:hover {
  transform: scale(1.05);
}

/* Loading states */
.loading {
  opacity: 0.6;
  pointer-events: none;
}

/* Print styles */
@media print {
  .no-print {
    display: none !important;
  }
}
</style>
