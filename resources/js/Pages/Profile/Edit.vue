<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Profile Settings", href: null, current: true },
];
</script>

<template>
  <Head title="Profile Settings" />

  <DashboardLayout title="Profile Settings" :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
      <!-- Profile Information Section -->
      <div
        class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-2xl p-6 sm:p-8"
      >
        <div
          class="border-b border-gray-200/50 dark:border-gray-700/50 pb-4 mb-6"
        >
          <h3
            class="text-lg font-semibold text-gray-900 dark:text-white flex items-center"
          >
            <div
              class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3"
            >
              <svg
                class="w-4 h-4 text-white"
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
            </div>
            Profile Information
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Update your account's profile information and email address.
          </p>
        </div>

        <UpdateProfileInformationForm
          :must-verify-email="mustVerifyEmail"
          :status="status"
          class="max-w-2xl"
        />
      </div>

      <!-- Update Password Section -->
      <div
        class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-2xl p-6 sm:p-8"
      >
        <div
          class="border-b border-gray-200/50 dark:border-gray-700/50 pb-4 mb-6"
        >
          <h3
            class="text-lg font-semibold text-gray-900 dark:text-white flex items-center"
          >
            <div
              class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3"
            >
              <svg
                class="w-4 h-4 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z"
                ></path>
              </svg>
            </div>
            Update Password
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Ensure your account is using a long, random password to stay secure.
          </p>
        </div>

        <UpdatePasswordForm class="max-w-2xl" />
      </div>

      <!-- Delete Account Section -->
      <div
        class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl border border-red-200/50 dark:border-red-700/50 shadow-2xl p-6 sm:p-8"
      >
        <div
          class="border-b border-red-200/50 dark:border-red-700/50 pb-4 mb-6"
        >
          <h3
            class="text-lg font-semibold text-red-900 dark:text-red-100 flex items-center"
          >
            <div
              class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mr-3"
            >
              <svg
                class="w-4 h-4 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                ></path>
              </svg>
            </div>
            Delete Account
          </h3>
          <p class="text-sm text-red-600 dark:text-red-400 mt-1">
            Once your account is deleted, all of its resources and data will be
            permanently deleted.
          </p>
        </div>

        <DeleteUserForm class="max-w-2xl" />
      </div>

      <!-- Quick Actions -->
      <div
        class="bg-blue-50/70 dark:bg-blue-900/20 backdrop-blur-xl rounded-2xl border border-blue-200/50 dark:border-blue-700/50 shadow-xl p-6"
      >
        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-4">
          Quick Actions
        </h4>
        <div class="flex flex-wrap gap-3">
          <a
            href="/dashboard"
            class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/70 transition-colors text-sm font-medium"
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
                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"
              ></path>
            </svg>
            Back to Dashboard
          </a>
          <a
            href="/"
            target="_blank"
            class="inline-flex items-center px-4 py-2 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded-lg hover:bg-purple-200 dark:hover:bg-purple-900/70 transition-colors text-sm font-medium"
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
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
              ></path>
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
              ></path>
            </svg>
            View Portfolio
          </a>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
