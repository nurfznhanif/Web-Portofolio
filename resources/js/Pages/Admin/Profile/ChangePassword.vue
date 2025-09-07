<script setup>
import { ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Form data
const form = useForm({
  current_password: "",
  password: "",
  password_confirmation: "",
});

// Reactive state
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);
const isSubmitting = ref(false);

// Computed properties
const isFormValid = computed(() => {
  return (
    form.current_password.length >= 8 &&
    form.password.length >= 8 &&
    form.password === form.password_confirmation &&
    form.password !== form.current_password
  );
});

const passwordStrength = computed(() => {
  const password = form.password;
  if (password.length === 0) return { strength: 0, label: "", color: "" };

  let score = 0;
  let label = "";
  let color = "";

  // Length check
  if (password.length >= 8) score += 1;
  if (password.length >= 12) score += 1;

  // Character variety
  if (/[a-z]/.test(password)) score += 1;
  if (/[A-Z]/.test(password)) score += 1;
  if (/[0-9]/.test(password)) score += 1;
  if (/[^A-Za-z0-9]/.test(password)) score += 1;

  if (score <= 2) {
    label = "Weak";
    color = "red";
  } else if (score <= 4) {
    label = "Medium";
    color = "yellow";
  } else {
    label = "Strong";
    color = "green";
  }

  return { strength: Math.min(score, 6), label, color };
});

const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Profile", href: "/profile" },
  { name: "Change Password", href: "/profile/password", current: true },
];

// Methods
const submitForm = () => {
  if (!isFormValid.value) return;

  isSubmitting.value = true;

  form.put("/profile/password", {
    onSuccess: () => {
      form.reset();
      // Success handled by controller
    },
    onError: () => {
      // Error handled by Inertia
    },
    onFinish: () => {
      isSubmitting.value = false;
    },
  });
};

const togglePasswordVisibility = (field) => {
  if (field === "current") {
    showCurrentPassword.value = !showCurrentPassword.value;
  } else if (field === "new") {
    showNewPassword.value = !showNewPassword.value;
  } else if (field === "confirm") {
    showConfirmPassword.value = !showConfirmPassword.value;
  }
};

const resetForm = () => {
  form.reset();
  form.clearErrors();
};
</script>

<template>
  <Head title="Change Password" />

  <DashboardLayout title="Change Password" :breadcrumbs="breadcrumbs">
    <div class="max-w-2xl mx-auto space-y-6">
      <!-- Header Section -->
      <div
        class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="flex items-center space-x-4">
          <div
            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center"
          >
            <svg
              class="w-6 h-6 text-white"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
              ></path>
            </svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
              Change Password
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Update your account password to keep your account secure
            </p>
          </div>
        </div>
      </div>

      <!-- Security Tips -->
      <div
        class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6"
      >
        <div class="flex items-start space-x-3">
          <svg
            class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
          <div>
            <h3
              class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2"
            >
              Password Security Tips
            </h3>
            <ul class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
              <li>• Use at least 8 characters (12+ recommended)</li>
              <li>• Mix uppercase and lowercase letters</li>
              <li>• Include numbers and special characters</li>
              <li>• Don't reuse your current password</li>
              <li>• Avoid common words or personal information</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Change Password Form -->
      <div
        class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6"
      >
        <form @submit.prevent="submitForm" class="space-y-6">
          <!-- Current Password -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
              Current Password *
            </label>
            <div class="relative">
              <input
                v-model="form.current_password"
                :type="showCurrentPassword ? 'text' : 'password'"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                placeholder="Enter your current password"
                required
              />
              <button
                type="button"
                @click="togglePasswordVisibility('current')"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <svg
                  v-if="showCurrentPassword"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                  ></path>
                </svg>
                <svg
                  v-else
                  class="w-5 h-5"
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
              </button>
            </div>
            <p
              v-if="form.errors.current_password"
              class="mt-1 text-sm text-red-600 dark:text-red-400"
            >
              {{ form.errors.current_password }}
            </p>
          </div>

          <!-- New Password -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
              New Password *
            </label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showNewPassword ? 'text' : 'password'"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                placeholder="Enter your new password"
                required
              />
              <button
                type="button"
                @click="togglePasswordVisibility('new')"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <svg
                  v-if="showNewPassword"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                  ></path>
                </svg>
                <svg
                  v-else
                  class="w-5 h-5"
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
              </button>
            </div>

            <!-- Password Strength Indicator -->
            <div v-if="form.password" class="mt-2">
              <div class="flex items-center justify-between text-xs mb-1">
                <span class="text-gray-600 dark:text-gray-400"
                  >Password Strength</span
                >
                <span
                  :class="`font-medium text-${passwordStrength.color}-600 dark:text-${passwordStrength.color}-400`"
                >
                  {{ passwordStrength.label }}
                </span>
              </div>
              <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div
                  :class="`h-full rounded-full bg-${passwordStrength.color}-500 transition-all duration-300`"
                  :style="{
                    width: `${(passwordStrength.strength / 6) * 100}%`,
                  }"
                ></div>
              </div>
            </div>

            <p
              v-if="form.errors.password"
              class="mt-1 text-sm text-red-600 dark:text-red-400"
            >
              {{ form.errors.password }}
            </p>
          </div>

          <!-- Confirm Password -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
              Confirm New Password *
            </label>
            <div class="relative">
              <input
                v-model="form.password_confirmation"
                :type="showConfirmPassword ? 'text' : 'password'"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                placeholder="Confirm your new password"
                required
              />
              <button
                type="button"
                @click="togglePasswordVisibility('confirm')"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <svg
                  v-if="showConfirmPassword"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                  ></path>
                </svg>
                <svg
                  v-else
                  class="w-5 h-5"
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
              </button>
            </div>

            <!-- Password Match Indicator -->
            <div v-if="form.password_confirmation" class="mt-2">
              <div
                v-if="form.password === form.password_confirmation"
                class="flex items-center text-green-600 dark:text-green-400 text-xs"
              >
                <svg
                  class="w-4 h-4 mr-1"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  ></path>
                </svg>
                Passwords match
              </div>
              <div
                v-else
                class="flex items-center text-red-600 dark:text-red-400 text-xs"
              >
                <svg
                  class="w-4 h-4 mr-1"
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
                Passwords don't match
              </div>
            </div>

            <p
              v-if="form.errors.password_confirmation"
              class="mt-1 text-sm text-red-600 dark:text-red-400"
            >
              {{ form.errors.password_confirmation }}
            </p>
          </div>

          <!-- Form Actions -->
          <div
            class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700"
          >
            <button
              type="button"
              @click="resetForm"
              class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 transition-all duration-200"
            >
              Reset Form
            </button>

            <button
              type="submit"
              :disabled="!isFormValid || isSubmitting"
              :class="[
                'px-6 py-3 text-sm font-medium text-white rounded-lg transition-all duration-200 flex items-center space-x-2',
                isFormValid && !isSubmitting
                  ? 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:ring-2 focus:ring-green-500'
                  : 'bg-gray-400 dark:bg-gray-600 cursor-not-allowed opacity-60',
              ]"
            >
              <svg
                v-if="isSubmitting"
                class="w-4 h-4 animate-spin"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                ></path>
              </svg>
              <span>{{
                isSubmitting ? "Updating Password..." : "Update Password"
              }}</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Security Notice -->
      <div
        class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800 p-6"
      >
        <div class="flex items-start space-x-3">
          <svg
            class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 flex-shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.902-.833-2.672 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
            ></path>
          </svg>
          <div>
            <h3
              class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-1"
            >
              Security Notice
            </h3>
            <p class="text-xs text-yellow-700 dark:text-yellow-300">
              After changing your password, you will remain logged in on this
              device. However, you may be logged out from other devices for
              security purposes.
            </p>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>
/* Custom input focus states */
input:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Password strength colors */
.text-red-600 {
  color: #dc2626;
}
.text-yellow-600 {
  color: #d97706;
}
.text-green-600 {
  color: #16a34a;
}
.bg-red-500 {
  background-color: #ef4444;
}
.bg-yellow-500 {
  background-color: #eab308;
}
.bg-green-500 {
  background-color: #22c55e;
}

.dark .text-red-400 {
  color: #f87171;
}
.dark .text-yellow-400 {
  color: #facc15;
}
.dark .text-green-400 {
  color: #4ade80;
}
</style>
