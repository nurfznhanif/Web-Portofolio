<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0">
      <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
          <div class="flex items-center justify-start">
            <button
              @click="sidebarOpen = !sidebarOpen"
              class="lg:hidden mr-2 text-gray-600 hover:text-gray-900 cursor-pointer p-2 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 rounded"
            >
              <i class="fas fa-bars"></i>
            </button>
            <Link
              :href="route('portfolio')"
              class="text-xl font-semibold flex items-center lg:ml-2.5"
            >
              <span class="self-center whitespace-nowrap text-gray-900"
                >Portfolio Admin</span
              >
            </Link>
          </div>
          <div class="flex items-center">
            <div class="relative">
              <button
                @click="dropdownOpen = !dropdownOpen"
                class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300"
              >
                <img
                  class="w-8 h-8 rounded-full"
                  src="https://ui-avatars.com/api/?name=Admin&background=dc2626&color=fff"
                  alt="user photo"
                />
              </button>
              <div
                v-show="dropdownOpen"
                class="absolute right-0 z-10 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5"
              >
                <Link
                  :href="route('admin.profile.edit')"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  <i class="fas fa-user mr-2"></i>Profile
                </Link>
                <Link
                  :href="route('logout')"
                  method="post"
                  as="button"
                  type="button"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  <i class="fas fa-sign-out-alt mr-2"></i>Sign out
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Sidebar -->
    <aside
      :class="{
        'translate-x-0': sidebarOpen,
        '-translate-x-full': !sidebarOpen,
      }"
      class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 lg:translate-x-0"
    >
      <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium">
          <li>
            <Link
              :href="route('admin.dashboard')"
              :class="{
                'bg-gray-100 text-gray-900': route().current('admin.dashboard'),
              }"
              class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group"
            >
              <i
                class="fas fa-tachometer-alt text-gray-500 group-hover:text-gray-900"
              ></i>
              <span class="ml-3">Dashboard</span>
            </Link>
          </li>
          <li>
            <Link
              :href="route('admin.profile.edit')"
              :class="{
                'bg-gray-100 text-gray-900': route().current('admin.profile.*'),
              }"
              class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group"
            >
              <i
                class="fas fa-user text-gray-500 group-hover:text-gray-900"
              ></i>
              <span class="ml-3">Profile</span>
            </Link>
          </li>
          <li>
            <Link
              :href="route('admin.projects.index')"
              :class="{
                'bg-gray-100 text-gray-900':
                  route().current('admin.projects.*'),
              }"
              class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group"
            >
              <i
                class="fas fa-briefcase text-gray-500 group-hover:text-gray-900"
              ></i>
              <span class="ml-3">Projects</span>
            </Link>
          </li>
          <li>
            <Link
              :href="route('admin.experiences.index')"
              :class="{
                'bg-gray-100 text-gray-900': route().current(
                  'admin.experiences.*'
                ),
              }"
              class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group"
            >
              <i
                class="fas fa-building text-gray-500 group-hover:text-gray-900"
              ></i>
              <span class="ml-3">Experiences</span>
            </Link>
          </li>
          <li>
            <Link
              :href="route('admin.skills.index')"
              :class="{
                'bg-gray-100 text-gray-900': route().current('admin.skills.*'),
              }"
              class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group"
            >
              <i
                class="fas fa-code text-gray-500 group-hover:text-gray-900"
              ></i>
              <span class="ml-3">Skills</span>
            </Link>
          </li>
        </ul>

        <div class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200">
          <Link
            :href="route('portfolio')"
            target="_blank"
            class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group"
          >
            <i
              class="fas fa-external-link-alt text-gray-500 group-hover:text-gray-900"
            ></i>
            <span class="ml-3">View Portfolio</span>
          </Link>
        </div>
      </div>
    </aside>

    <!-- Mobile sidebar overlay -->
    <div
      v-show="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 z-30 bg-gray-900 bg-opacity-50 lg:hidden"
    ></div>

    <!-- Main content -->
    <div class="p-4 lg:ml-64">
      <div class="p-4 mt-14">
        <!-- Flash Messages -->
        <div
          v-if="$page.props.flash.message"
          class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
        >
          <span class="block sm:inline">{{ $page.props.flash.message }}</span>
          <button
            @click="$page.props.flash.message = null"
            class="absolute top-0 bottom-0 right-0 px-4 py-3"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div
          v-if="$page.props.flash.error"
          class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
        >
          <span class="block sm:inline">{{ $page.props.flash.error }}</span>
          <button
            @click="$page.props.flash.error = null"
            class="absolute top-0 bottom-0 right-0 px-4 py-3"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>

        <slot />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";

const sidebarOpen = ref(false);
const dropdownOpen = ref(false);

const handleClickOutside = (event) => {
  if (dropdownOpen.value && !event.target.closest(".relative")) {
    dropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>
