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
        <nav class="flex-1 px-4 py-6 space-y-2">
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

          <Link
            :href="route('admin.portfolio.index')"
            class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
            :class="{
              'bg-red-900 text-white': $page.url.startsWith('/admin/portfolio'),
            }"
          >
            <span class="mr-3">💼</span>
            Portfolio
          </Link>

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
          </Link>

          <Link
            :href="route('admin.skill.index')"
            class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
            :class="{
              'bg-red-900 text-white': $page.url.startsWith('/admin/skill'),
            }"
          >
            <span class="mr-3">🛠️</span>
            Skills
          </Link>

          <hr class="border-gray-700 my-4" />

          <Link
            :href="route('portfolio.index')"
            target="_blank"
            class="flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors"
          >
            <span class="mr-3">🌐</span>
            View Website
          </Link>
        </nav>

        <!-- User Menu -->
        <div class="p-4 border-t border-gray-700">
          <div class="flex items-center">
            <div class="flex-1">
              <p class="text-sm font-medium text-white">
                {{ $page.props.auth.user.name }}
              </p>
              <p class="text-xs text-gray-400">
                {{ $page.props.auth.user.email }}
              </p>
            </div>
            <form @submit.prevent="logout" class="ml-2">
              <button
                type="submit"
                class="p-2 text-gray-400 hover:text-red-400 transition-colors"
                title="Logout"
              >
                🚪
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="lg:pl-64">
      <!-- Top Navigation -->
      <div class="sticky top-0 z-40 bg-gray-800 border-b border-gray-700">
        <div class="flex items-center justify-between px-4 py-3">
          <button
            @click="toggleSidebar"
            class="lg:hidden p-2 text-gray-400 hover:text-white"
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

          <h2 class="text-xl font-semibold text-white">{{ title }}</h2>

          <div class="flex items-center space-x-4">
            <!-- Add any additional top nav items here -->
          </div>
        </div>
      </div>

      <!-- Page Content -->
      <main class="p-6">
        <!-- Flash Messages -->
        <div
          v-if="$page.props.flash?.success"
          class="mb-4 p-4 bg-green-900/50 border border-green-700 text-green-300 rounded-lg"
        >
          {{ $page.props.flash.success }}
        </div>

        <div
          v-if="$page.props.flash?.error"
          class="mb-4 p-4 bg-red-900/50 border border-red-700 text-red-300 rounded-lg"
        >
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
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";

defineProps({
  title: {
    type: String,
    default: "Dashboard",
  },
});

const sidebarOpen = ref(false);

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebar = () => {
  sidebarOpen.value = false;
};

const logout = () => {
  router.post(route("logout"));
};
</script>
