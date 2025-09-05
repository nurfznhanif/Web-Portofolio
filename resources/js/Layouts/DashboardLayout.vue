<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Sidebar -->
    <div
      class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
      :class="{
        '-translate-x-full': !sidebarOpen,
        'translate-x-0': sidebarOpen,
      }"
    >
      <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 bg-red-900">
          <h1 class="text-xl font-bold text-white">Admin Dashboard</h1>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
          <!-- Dashboard -->
          <Link
            :href="route('admin.dashboard')"
            class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
            :class="{
              'bg-red-900 text-white':
                $page.url.startsWith('/admin/dashboard') ||
                $page.url === '/dashboard',
            }"
          >
            <span class="mr-3">📊</span>
            Dashboard
          </Link>

          <!-- Content Management Section -->
          <div class="pt-4">
            <h3
              class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"
            >
              Content Management
            </h3>

            <!-- Portfolio -->
            <Link
              :href="route('admin.portfolio.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white':
                  $page.url.startsWith('/admin/portfolio'),
              }"
            >
              <span class="mr-3">💼</span>
              Portfolio
              <span
                v-if="portfolioCount"
                class="ml-auto text-xs bg-gray-600 text-gray-300 px-2 py-0.5 rounded-full"
              >
                {{ portfolioCount }}
              </span>
            </Link>

            <!-- Experience -->
            <Link
              :href="route('admin.experience.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white':
                  $page.url.startsWith('/admin/experience'),
              }"
            >
              <span class="mr-3">🏢</span>
              Experience
              <span
                v-if="experienceCount"
                class="ml-auto text-xs bg-gray-600 text-gray-300 px-2 py-0.5 rounded-full"
              >
                {{ experienceCount }}
              </span>
            </Link>

            <!-- Skills -->
            <Link
              :href="route('admin.skill.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white': $page.url.startsWith('/admin/skill'),
              }"
            >
              <span class="mr-3">🛠️</span>
              Skills
              <span
                v-if="skillsCount"
                class="ml-auto text-xs bg-gray-600 text-gray-300 px-2 py-0.5 rounded-full"
              >
                {{ skillsCount }}
              </span>
            </Link>

            <!-- Certifications -->
            <Link
              :href="route('admin.certifications.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white': $page.url.startsWith(
                  '/admin/certifications'
                ),
              }"
            >
              <span class="mr-3">🏆</span>
              Certifications
            </Link>

            <!-- Achievements -->
            <Link
              :href="route('admin.achievements.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white': $page.url.startsWith(
                  '/admin/achievements'
                ),
              }"
            >
              <span class="mr-3">🎯</span>
              Achievements
            </Link>
          </div>

          <!-- Profile Section -->
          <div class="pt-4">
            <h3
              class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"
            >
              Profile Settings
            </h3>

            <!-- Profile Settings -->
            <Link
              :href="route('admin.settings.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white':
                  $page.url.startsWith('/admin/settings'),
              }"
            >
              <span class="mr-3">⚙️</span>
              Settings
            </Link>

            <!-- Social Links -->
            <Link
              :href="route('admin.social-links.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white': $page.url.startsWith(
                  '/admin/social-links'
                ),
              }"
            >
              <span class="mr-3">🔗</span>
              Social Links
            </Link>

            <!-- Languages -->
            <Link
              :href="route('admin.languages.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white':
                  $page.url.startsWith('/admin/languages'),
              }"
            >
              <span class="mr-3">🗣️</span>
              Languages
            </Link>

            <!-- Interests -->
            <Link
              :href="route('admin.interests.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white':
                  $page.url.startsWith('/admin/interests'),
              }"
            >
              <span class="mr-3">💡</span>
              Interests
            </Link>
          </div>

          <!-- Communication Section -->
          <div class="pt-4">
            <h3
              class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"
            >
              Communication
            </h3>

            <!-- Messages -->
            <Link
              :href="route('admin.messages.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white':
                  $page.url.startsWith('/admin/messages'),
              }"
            >
              <span class="mr-3">💬</span>
              Messages
              <span
                v-if="unreadMessages > 0"
                class="ml-auto text-xs bg-red-500 text-white px-2 py-0.5 rounded-full animate-pulse"
              >
                {{ unreadMessages }}
              </span>
            </Link>
          </div>

          <!-- Analytics Section -->
          <div class="pt-4">
            <h3
              class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"
            >
              Analytics & Reports
            </h3>

            <!-- Analytics Dashboard -->
            <Link
              :href="route('admin.analytics.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white':
                  $page.url.startsWith('/admin/analytics'),
              }"
            >
              <span class="mr-3">📈</span>
              Analytics
            </Link>

            <!-- System Management -->
            <Link
              :href="route('admin.system.index')"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
              :class="{
                'bg-red-900 text-white': $page.url.startsWith('/admin/system'),
              }"
            >
              <span class="mr-3">🔧</span>
              System
            </Link>
          </div>

          <!-- Quick Actions -->
          <div class="pt-4">
            <hr class="border-gray-700 mb-4" />

            <!-- View Website -->
            <a
              :href="route('portfolio.index')"
              target="_blank"
              class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
            >
              <span class="mr-3">🌐</span>
              View Website
              <svg
                class="w-4 h-4 ml-auto"
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

            <!-- Clear Cache -->
            <button
              @click="clearCache"
              :disabled="clearingCache"
              class="w-full flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors disabled:opacity-50"
            >
              <span class="mr-3" :class="{ 'animate-spin': clearingCache }"
                >🗑️</span
              >
              Clear Cache
            </button>
          </div>
        </nav>

        <!-- User Menu -->
        <div class="p-4 border-t border-gray-700">
          <div class="flex items-center">
            <!-- User Avatar -->
            <div
              class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center mr-3"
            >
              <span class="text-white text-sm font-bold">
                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
              </span>
            </div>

            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-white truncate">
                {{ $page.props.auth.user.name }}
              </p>
              <p class="text-xs text-gray-400 truncate">
                {{ $page.props.auth.user.email }}
              </p>
            </div>

            <!-- User Menu Dropdown -->
            <div class="relative">
              <button
                @click="showUserMenu = !showUserMenu"
                class="p-2 text-gray-400 hover:text-white rounded-lg transition-colors"
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
                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                  />
                </svg>
              </button>

              <!-- Dropdown Menu -->
              <div
                v-if="showUserMenu"
                class="absolute right-0 bottom-full mb-2 w-48 bg-gray-700 rounded-lg shadow-lg border border-gray-600"
              >
                <div class="py-1">
                  <Link
                    :href="route('profile.edit')"
                    class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white"
                    @click="showUserMenu = false"
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
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                      />
                    </svg>
                    Edit Profile
                  </Link>

                  <hr class="border-gray-600 my-1" />

                  <button
                    @click="logout"
                    class="w-full flex items-center px-4 py-2 text-sm text-red-400 hover:bg-gray-600 hover:text-red-300"
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
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                      />
                    </svg>
                    Logout
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="lg:pl-64">
      <!-- Top Navigation -->
      <div class="sticky top-0 z-40 bg-gray-800 border-b border-gray-700">
        <div class="flex items-center justify-between px-4 py-3">
          <div class="flex items-center">
            <!-- Mobile Menu Button -->
            <button
              @click="toggleSidebar"
              class="lg:hidden p-2 text-gray-400 hover:text-white rounded-lg mr-3"
            >
              <svg
                class="w-6 h-6"
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

            <!-- Page Title -->
            <h2 class="text-xl font-semibold text-white">{{ title }}</h2>
          </div>

          <!-- Top Navigation Actions -->
          <div class="flex items-center space-x-4">
            <!-- Search (Optional) -->
            <div class="relative hidden md:block">
              <input
                v-model="searchQuery"
                @input="performSearch"
                type="text"
                placeholder="Search..."
                class="w-64 px-4 py-2 pl-10 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
              />
              <svg
                class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"
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

            <!-- Notifications -->
            <button
              @click="showNotifications = !showNotifications"
              class="relative p-2 text-gray-400 hover:text-white rounded-lg transition-colors"
            >
              <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                />
              </svg>
              <span
                v-if="unreadMessages > 0"
                class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center"
              >
                {{ unreadMessages > 9 ? "9+" : unreadMessages }}
              </span>
            </button>
          </div>
        </div>
      </div>

      <!-- Page Content -->
      <main>
        <!-- Flash Messages -->
        <div
          v-if="$page.props.flash?.success"
          class="m-6 p-4 bg-green-900/50 border border-green-700 text-green-300 rounded-lg flex items-center"
        >
          <svg
            class="w-5 h-5 mr-3"
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
          {{ $page.props.flash.success }}
        </div>

        <div
          v-if="$page.props.flash?.error"
          class="m-6 p-4 bg-red-900/50 border border-red-700 text-red-300 rounded-lg flex items-center"
        >
          <svg
            class="w-5 h-5 mr-3"
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
          {{ $page.props.flash.error }}
        </div>

        <slot />
      </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
      @click="closeSidebar"
    ></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link, router } from "@inertiajs/vue3";

defineProps({
  title: {
    type: String,
    default: "Dashboard",
  },
});

// Reactive state
const sidebarOpen = ref(false);
const showUserMenu = ref(false);
const showNotifications = ref(false);
const clearingCache = ref(false);
const searchQuery = ref("");

// Stats counters
const portfolioCount = ref(0);
const experienceCount = ref(0);
const skillsCount = ref(0);
const unreadMessages = ref(0);

// Methods
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebar = () => {
  sidebarOpen.value = false;
};

const logout = () => {
  router.post(route("logout"));
};

const clearCache = async () => {
  clearingCache.value = true;
  try {
    await router.post("/admin/system/clear-cache");
    // Show success message
  } catch (error) {
    console.error("Failed to clear cache:", error);
  } finally {
    clearingCache.value = false;
  }
};

const performSearch = () => {
  // Implement search functionality
  if (searchQuery.value.length >= 2) {
    // Perform search API call
    console.log("Searching for:", searchQuery.value);
  }
};

const loadStats = async () => {
  try {
    const response = await fetch("/admin/api/quick-stats");
    const data = await response.json();

    portfolioCount.value = data.portfolios || 0;
    experienceCount.value = data.experiences || 0;
    skillsCount.value = data.skills || 0;
    unreadMessages.value = data.messages || 0;
  } catch (error) {
    console.error("Failed to load stats:", error);
  }
};

// Close dropdowns when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest(".relative")) {
    showUserMenu.value = false;
    showNotifications.value = false;
  }
};

// Lifecycle
onMounted(() => {
  loadStats();
  document.addEventListener("click", handleClickOutside);

  // Refresh stats every 5 minutes
  const statsInterval = setInterval(loadStats, 300000);

  onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
    clearInterval(statsInterval);
  });
});
</script>

<style scoped>
/* Smooth transitions */
.transition-all {
  transition: all 0.3s ease;
}

/* Custom scrollbar for sidebar */
nav::-webkit-scrollbar {
  width: 4px;
}

nav::-webkit-scrollbar-track {
  background: #374151;
}

nav::-webkit-scrollbar-thumb {
  background: #6b7280;
  border-radius: 2px;
}

nav::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

/* Animation for counters */
@keyframes pulse {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
