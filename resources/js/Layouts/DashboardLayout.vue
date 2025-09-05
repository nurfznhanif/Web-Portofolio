<!-- resources/js/Layouts/DashboardLayout.vue - SAFE VERSION -->
<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Top Navigation -->
    <nav class="bg-gray-800 border-b border-gray-700 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Left side -->
          <div class="flex items-center">
            <!-- Mobile menu button -->
            <button
              @click="sidebarOpen = !sidebarOpen"
              class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
            >
              <svg
                class="h-6 w-6"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 24 24"
              >
                <path
                  :class="{ hidden: sidebarOpen, 'inline-flex': !sidebarOpen }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  :class="{ hidden: !sidebarOpen, 'inline-flex': sidebarOpen }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>

            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center ml-4 md:ml-0">
              <Link
                :href="route('dashboard')"
                class="text-xl font-bold text-white"
              >
                Portfolio Admin
              </Link>
            </div>
          </div>

          <!-- Right side -->
          <div class="flex items-center space-x-4">
            <!-- User menu -->
            <div class="relative">
              <button
                @click="showUserMenu = !showUserMenu"
                class="flex items-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700"
              >
                <div
                  class="h-8 w-8 bg-gray-600 rounded-full flex items-center justify-center"
                >
                  <span class="text-sm text-white">{{ userInitials }}</span>
                </div>
                <svg
                  class="ml-2 h-4 w-4"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                  />
                </svg>
              </button>

              <!-- User menu dropdown -->
              <div
                v-if="showUserMenu"
                class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg border border-gray-700 z-50"
              >
                <div class="p-2">
                  <div
                    class="px-3 py-2 text-sm text-gray-300 border-b border-gray-700"
                  >
                    {{ userName }}
                  </div>
                  <Link
                    :href="route('profile.edit')"
                    class="block px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded"
                  >
                    Profile Settings
                  </Link>
                  <Link
                    href="/"
                    class="block px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded"
                  >
                    View Portfolio
                  </Link>
                  <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded"
                  >
                    Sign out
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <div class="flex">
      <!-- Sidebar -->
      <div
        :class="{
          'translate-x-0': sidebarOpen,
          '-translate-x-full': !sidebarOpen,
        }"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0"
      >
        <!-- Sidebar content -->
        <div class="flex flex-col h-full pt-16 md:pt-0">
          <!-- Navigation -->
          <nav class="flex-1 px-2 py-4 space-y-2">
            <!-- Dashboard -->
            <Link
              :href="route('dashboard')"
              :class="[
                'flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors',
                isCurrentRoute('dashboard')
                  ? 'bg-gray-700 text-white'
                  : 'text-gray-300 hover:bg-gray-700 hover:text-white',
              ]"
            >
              <svg
                class="mr-3 h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 5a2 2 0 012-2h4a2 2 0 012 2v.01M8 5v.01"
                />
              </svg>
              Dashboard
            </Link>

            <!-- Portfolio -->
            <div>
              <button
                @click="portfolioOpen = !portfolioOpen"
                class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white"
              >
                <div class="flex items-center">
                  <svg
                    class="mr-3 h-5 w-5"
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
                  Portfolio
                </div>
                <svg
                  :class="{ 'rotate-90': portfolioOpen }"
                  class="h-4 w-4 transition-transform"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"
                  />
                </svg>
              </button>

              <div v-show="portfolioOpen" class="pl-6 mt-2 space-y-1">
                <Link
                  :href="route('admin.portfolio.index')"
                  :class="[
                    'block px-3 py-2 text-sm rounded-md transition-colors',
                    isCurrentRoute('admin.portfolio')
                      ? 'bg-gray-700 text-white'
                      : 'text-gray-400 hover:text-white hover:bg-gray-700',
                  ]"
                >
                  Manage Projects
                </Link>
                <Link
                  :href="route('admin.portfolio.create')"
                  class="block px-3 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 rounded-md"
                >
                  Add New Project
                </Link>
              </div>
            </div>

            <!-- Experience -->
            <div>
              <button
                @click="experienceOpen = !experienceOpen"
                class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white"
              >
                <div class="flex items-center">
                  <svg
                    class="mr-3 h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V6m8 0H8m8 0v6"
                    />
                  </svg>
                  Experience
                </div>
                <svg
                  :class="{ 'rotate-90': experienceOpen }"
                  class="h-4 w-4 transition-transform"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"
                  />
                </svg>
              </button>

              <div v-show="experienceOpen" class="pl-6 mt-2 space-y-1">
                <Link
                  :href="route('admin.experience.index')"
                  :class="[
                    'block px-3 py-2 text-sm rounded-md transition-colors',
                    isCurrentRoute('admin.experience')
                      ? 'bg-gray-700 text-white'
                      : 'text-gray-400 hover:text-white hover:bg-gray-700',
                  ]"
                >
                  Manage Experience
                </Link>
                <Link
                  :href="route('admin.experience.create')"
                  class="block px-3 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 rounded-md"
                >
                  Add Experience
                </Link>
              </div>
            </div>

            <!-- Skills -->
            <div>
              <button
                @click="skillsOpen = !skillsOpen"
                class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white"
              >
                <div class="flex items-center">
                  <svg
                    class="mr-3 h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                    />
                  </svg>
                  Skills
                </div>
                <svg
                  :class="{ 'rotate-90': skillsOpen }"
                  class="h-4 w-4 transition-transform"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"
                  />
                </svg>
              </button>

              <div v-show="skillsOpen" class="pl-6 mt-2 space-y-1">
                <Link
                  :href="route('admin.skill.index')"
                  :class="[
                    'block px-3 py-2 text-sm rounded-md transition-colors',
                    isCurrentRoute('admin.skill')
                      ? 'bg-gray-700 text-white'
                      : 'text-gray-400 hover:text-white hover:bg-gray-700',
                  ]"
                >
                  Manage Skills
                </Link>
                <Link
                  :href="route('admin.skill.create')"
                  class="block px-3 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 rounded-md"
                >
                  Add Skill
                </Link>
              </div>
            </div>

            <!-- Messages -->
            <Link
              :href="route('admin.messages.index')"
              :class="[
                'flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors relative',
                isCurrentRoute('admin.messages')
                  ? 'bg-gray-700 text-white'
                  : 'text-gray-300 hover:bg-gray-700 hover:text-white',
              ]"
            >
              <svg
                class="mr-3 h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4m-4 0H8m-4 0h4m0 0V9a1 1 0 011-1h2a1 1 0 011 1v4M7 7h10"
                />
              </svg>
              Messages
            </Link>

            <!-- Analytics -->
            <Link
              :href="route('admin.analytics.index')"
              :class="[
                'flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors',
                isCurrentRoute('admin.analytics')
                  ? 'bg-gray-700 text-white'
                  : 'text-gray-300 hover:bg-gray-700 hover:text-white',
              ]"
            >
              <svg
                class="mr-3 h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                />
              </svg>
              Analytics
            </Link>
          </nav>

          <!-- Footer -->
          <div class="p-4 border-t border-gray-700">
            <div class="text-xs text-gray-400 text-center">
              Portfolio Admin v1.0
            </div>
          </div>
        </div>
      </div>

      <!-- Main content -->
      <div class="flex-1 md:ml-0">
        <!-- Overlay for mobile -->
        <div
          v-if="sidebarOpen"
          @click="sidebarOpen = false"
          class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 md:hidden"
        ></div>

        <!-- Page content -->
        <main class="flex-1">
          <slot />
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { useRoute } from "@/composables/useRoute";

// Get page data from Inertia
const page = usePage();

// Get route helper
const route = useRoute();

// Reactive data
const sidebarOpen = ref(false);
const showUserMenu = ref(false);
const portfolioOpen = ref(true);
const experienceOpen = ref(false);
const skillsOpen = ref(false);

// Computed properties with safe fallbacks
const userName = computed(() => {
  try {
    return page.props.auth?.user?.name || "Admin User";
  } catch (error) {
    console.warn("Failed to get user name:", error);
    return "Admin User";
  }
});

const userInitials = computed(() => {
  try {
    const name = userName.value;
    return name
      .split(" ")
      .map((n) => n[0])
      .join("")
      .toUpperCase();
  } catch (error) {
    console.warn("Failed to generate user initials:", error);
    return "AU";
  }
});

// Safe route checking method
const isCurrentRoute = (routePattern) => {
  try {
    const currentPath = window.location.pathname;

    // Simple pattern matching
    if (routePattern === "dashboard") {
      return currentPath === "/dashboard";
    }
    if (routePattern === "admin.portfolio") {
      return currentPath.startsWith("/admin/portfolio");
    }
    if (routePattern === "admin.experience") {
      return currentPath.startsWith("/admin/experience");
    }
    if (routePattern === "admin.skill") {
      return currentPath.startsWith("/admin/skill");
    }
    if (routePattern === "admin.messages") {
      return currentPath.startsWith("/admin/messages");
    }
    if (routePattern === "admin.analytics") {
      return currentPath.startsWith("/admin/analytics");
    }

    return false;
  } catch (error) {
    console.warn("Route checking failed:", error);
    return false;
  }
};

const handleClickOutside = (event) => {
  if (!event.target.closest(".user-menu-dropdown")) {
    showUserMenu.value = false;
  }
};

// Lifecycle
onMounted(() => {
  document.addEventListener("click", handleClickOutside);

  onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
  });
});
</script>
