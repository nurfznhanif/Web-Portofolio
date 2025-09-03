<template>
  <Head title="Login" />

  <div
    class="min-h-screen gothic-bg flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
  >
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h1 class="text-4xl font-bold gothic-title text-red-400 mb-2">
          Admin Login
        </h1>
        <p class="text-gray-400">Sign in to manage your portfolio</p>
      </div>

      <div class="gothic-card rounded-lg p-8">
        <form @submit.prevent="submit" class="space-y-6">
          <div>
            <label
              for="email"
              class="block text-sm font-medium text-red-400 mb-2"
            >
              Email Address
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="w-full px-3 py-3 bg-gray-800 border border-red-800/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
              placeholder="Enter your email"
            />
            <div v-if="errors.email" class="mt-1 text-red-400 text-sm">
              {{ errors.email }}
            </div>
          </div>

          <div>
            <label
              for="password"
              class="block text-sm font-medium text-red-400 mb-2"
            >
              Password
            </label>
            <div class="relative">
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="w-full px-3 py-3 bg-gray-800 border border-red-800/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors pr-10"
                placeholder="Enter your password"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-400 transition-colors"
              >
                <i
                  :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                ></i>
              </button>
            </div>
            <div v-if="errors.password" class="mt-1 text-red-400 text-sm">
              {{ errors.password }}
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember"
                v-model="form.remember"
                type="checkbox"
                class="h-4 w-4 text-red-600 bg-gray-800 border-red-800/30 rounded focus:ring-red-500 focus:ring-2"
              />
              <label for="remember" class="ml-2 block text-sm text-gray-300">
                Remember me
              </label>
            </div>
          </div>

          <div>
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <i v-if="form.processing" class="fas fa-spinner fa-spin mr-2"></i>
              {{ form.processing ? "Signing in..." : "Sign in" }}
            </button>
          </div>
        </form>

        <div class="mt-6 text-center">
          <Link
            :href="route('portfolio')"
            class="text-red-400 hover:text-red-300 text-sm transition-colors"
          >
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Portfolio
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const showPassword = ref(false);

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login.store"));
};

defineProps({
  errors: Object,
});
</script>
