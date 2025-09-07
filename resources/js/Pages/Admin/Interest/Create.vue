<!-- resources/js/Pages/Admin/Interest/Create.vue -->
<script setup>
import { ref, computed } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Form setup
const form = useForm({
  name: "",
  description: "",
  icon: "🎯",
  color: "#3b82f6",
  is_featured: false,
});

const previewMode = ref(false);

// Predefined color options
const colorOptions = [
  { value: "#3b82f6", name: "Blue", class: "bg-blue-500" },
  { value: "#10b981", name: "Green", class: "bg-emerald-500" },
  { value: "#f59e0b", name: "Yellow", class: "bg-amber-500" },
  { value: "#ef4444", name: "Red", class: "bg-red-500" },
  { value: "#8b5cf6", name: "Purple", class: "bg-violet-500" },
  { value: "#06b6d4", name: "Cyan", class: "bg-cyan-500" },
  { value: "#f97316", name: "Orange", class: "bg-orange-500" },
  { value: "#84cc16", name: "Lime", class: "bg-lime-500" },
  { value: "#ec4899", name: "Pink", class: "bg-pink-500" },
  { value: "#6366f1", name: "Indigo", class: "bg-indigo-500" },
  { value: "#14b8a6", name: "Teal", class: "bg-teal-500" },
  { value: "#f43f5e", name: "Rose", class: "bg-rose-500" },
];

// Common interest categories with suggested icons
const interestCategories = {
  Technology: ["💻", "⚡", "🔧", "🖥️", "📱"],
  Programming: ["⌨️", "💾", "🔗", "🖲️", "📊"],
  Design: ["🎨", "✏️", "🖌️", "🎭", "🌈"],
  Science: ["🔬", "🧪", "🔭", "⚗️", "🧬"],
  Business: ["💼", "📈", "💰", "🏢", "📊"],
  Sports: ["⚽", "🏀", "🎾", "🏃‍♂️", "🚴‍♂️"],
  Music: ["🎵", "🎸", "🎹", "🎤", "🎧"],
  Art: ["🖼️", "🎭", "🖌️", "🎪", "📸"],
  Gaming: ["🎮", "🕹️", "🎯", "🎲", "♟️"],
  Travel: ["✈️", "🌍", "🗺️", "🏔️", "🏖️"],
  Food: ["🍳", "🍕", "☕", "🍜", "🧁"],
  Reading: ["📚", "📖", "📝", "✍️", "📰"],
  Learning: ["🎓", "📖", "🧠", "💡", "🔍"],
  Nature: ["🌿", "🌳", "🌻", "🦋", "🌊"],
  Health: ["💪", "🧘‍♂️", "🏃‍♀️", "❤️", "🍎"],
};

// Emoji suggestions
const emojiSuggestions = [
  "🎯",
  "💻",
  "🎨",
  "📚",
  "🎵",
  "⚽",
  "🎮",
  "✈️",
  "🍳",
  "📸",
  "🔬",
  "💼",
  "🌿",
  "💪",
  "🎭",
  "🏃‍♂️",
  "🧠",
  "💡",
  "🌈",
  "🚀",
  "🎸",
  "🏀",
  "🎹",
  "🌍",
  "📝",
  "🎤",
  "🎲",
  "🧘‍♂️",
  "🌊",
  "☕",
];

// Methods
const submitForm = () => {
  form.post("/admin/interests", {
    onSuccess: () => {
      // Success handled by redirect
    },
  });
};

const resetForm = () => {
  form.reset();
  form.clearErrors();
};

const cancelCreate = () => {
  router.visit("/admin/interests");
};

const selectColor = (color) => {
  form.color = color;
};

const selectIcon = (icon) => {
  form.icon = icon;
};

const selectCategory = (category) => {
  form.name = category;
  // Set a random icon from the category
  const icons = interestCategories[category];
  if (icons && icons.length > 0) {
    form.icon = icons[Math.floor(Math.random() * icons.length)];
  }
};

const getColorStyle = (color) => {
  return {
    backgroundColor: color,
    color: "#ffffff",
  };
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Interests", href: "/admin/interests" },
  { name: "Create", href: "/admin/interests/create", current: true },
];
</script>

<template>
  <Head title="Add New Interest" />

  <DashboardLayout :title="'Add New Interest'" :breadcrumbs="breadcrumbs">
    <form @submit.prevent="submitForm" class="space-y-6">
      <!-- Success/Error Messages -->
      <div
        v-if="$page.props.flash?.error"
        class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4"
      >
        <div class="flex">
          <div class="flex-shrink-0">
            <svg
              class="h-5 w-5 text-red-400"
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
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-red-800 dark:text-red-200">
              {{ $page.props.flash.error }}
            </p>
          </div>
        </div>
      </div>

      <!-- Preview Toggle -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Interest Details
          </h2>
          <button
            type="button"
            @click="previewMode = !previewMode"
            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
          >
            {{ previewMode ? "Edit" : "Preview" }}
          </button>
        </div>
      </div>

      <!-- Preview Mode -->
      <div
        v-if="previewMode"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="max-w-md mx-auto">
          <div class="text-center">
            <div
              class="w-20 h-20 mx-auto rounded-full flex items-center justify-center text-4xl mb-4"
              :style="getColorStyle(form.color)"
            >
              {{ form.icon }}
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
              {{ form.name || "Interest Name" }}
            </h3>
            <p
              v-if="form.description"
              class="text-gray-600 dark:text-gray-400 mt-2"
            >
              {{ form.description }}
            </p>
            <div class="mt-4 flex items-center justify-center">
              <span
                v-if="form.is_featured"
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200"
              >
                <svg
                  class="w-3 h-3 mr-1"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                  />
                </svg>
                Featured
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Mode -->
      <div v-else class="space-y-6">
        <!-- Basic Information -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Basic Information
          </h2>

          <div class="space-y-6">
            <!-- Interest Name -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Interest Name <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="e.g., Web Development, Photography, Cooking"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
              <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">
                {{ form.errors.name }}
              </p>
            </div>

            <!-- Quick Category Selection -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Quick Select from Categories
              </label>
              <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <button
                  v-for="(icons, category) in interestCategories"
                  :key="category"
                  type="button"
                  @click="selectCategory(category)"
                  class="p-3 text-left border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
                >
                  <div class="flex items-center">
                    <span class="text-xl mr-2">{{ icons[0] }}</span>
                    <span
                      class="text-sm font-medium text-gray-900 dark:text-white"
                      >{{ category }}</span
                    >
                  </div>
                </button>
              </div>
            </div>

            <!-- Description -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Description
              </label>
              <textarea
                v-model="form.description"
                rows="3"
                placeholder="Describe what interests you about this topic..."
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-vertical"
              ></textarea>
              <p
                v-if="form.errors.description"
                class="mt-1 text-sm text-red-500"
              >
                {{ form.errors.description }}
              </p>
              <p class="mt-1 text-sm text-gray-500">
                Share what makes this interest special to you (Optional)
              </p>
            </div>
          </div>
        </div>

        <!-- Visual Settings -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Visual Settings
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Icon Selection -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Icon
              </label>
              <div class="space-y-4">
                <!-- Current Icon -->
                <div class="flex items-center space-x-3">
                  <div
                    class="w-12 h-12 rounded-lg flex items-center justify-center text-2xl"
                    :style="getColorStyle(form.color)"
                  >
                    {{ form.icon }}
                  </div>
                  <input
                    v-model="form.icon"
                    type="text"
                    placeholder="🎯"
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                  />
                </div>

                <!-- Emoji Suggestions -->
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                    Quick select:
                  </p>
                  <div class="grid grid-cols-10 gap-2">
                    <button
                      v-for="emoji in emojiSuggestions"
                      :key="emoji"
                      type="button"
                      @click="selectIcon(emoji)"
                      class="p-2 text-xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200"
                      :class="
                        form.icon === emoji
                          ? 'bg-indigo-100 dark:bg-indigo-900'
                          : ''
                      "
                    >
                      {{ emoji }}
                    </button>
                  </div>
                </div>
              </div>
              <p v-if="form.errors.icon" class="mt-1 text-sm text-red-500">
                {{ form.errors.icon }}
              </p>
            </div>

            <!-- Color Selection -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Background Color
              </label>
              <div class="space-y-4">
                <!-- Color Preview -->
                <div class="flex items-center space-x-3">
                  <div
                    class="w-12 h-12 rounded-lg border-2 border-gray-200 dark:border-gray-600"
                    :style="{ backgroundColor: form.color }"
                  ></div>
                  <input
                    v-model="form.color"
                    type="text"
                    placeholder="#3b82f6"
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                  />
                </div>

                <!-- Color Palette -->
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                    Quick select:
                  </p>
                  <div class="grid grid-cols-6 gap-2">
                    <button
                      v-for="colorOption in colorOptions"
                      :key="colorOption.value"
                      type="button"
                      @click="selectColor(colorOption.value)"
                      class="w-8 h-8 rounded-lg border-2 border-gray-200 dark:border-gray-600 hover:scale-110 transition-transform duration-200"
                      :class="[
                        colorOption.class,
                        form.color === colorOption.value
                          ? 'ring-2 ring-offset-2 ring-indigo-500'
                          : '',
                      ]"
                      :title="colorOption.name"
                    ></button>
                  </div>
                </div>
              </div>
              <p v-if="form.errors.color" class="mt-1 text-sm text-red-500">
                {{ form.errors.color }}
              </p>
            </div>
          </div>
        </div>

        <!-- Settings -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Settings
          </h2>

          <div>
            <div class="flex items-center">
              <input
                v-model="form.is_featured"
                id="is_featured"
                type="checkbox"
                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
              />
              <label
                for="is_featured"
                class="ml-2 block text-sm text-gray-700 dark:text-gray-300"
              >
                Featured Interest
              </label>
            </div>
            <p class="mt-1 text-sm text-gray-500">
              Featured interests will be highlighted and shown prominently on
              your portfolio
            </p>
            <p v-if="form.errors.is_featured" class="mt-1 text-sm text-red-500">
              {{ form.errors.is_featured }}
            </p>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
        >
          <div class="flex items-center space-x-4">
            <button
              type="button"
              @click="resetForm"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200"
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
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                />
              </svg>
              Reset Form
            </button>

            <button
              type="button"
              @click="cancelCreate"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200"
            >
              Cancel
            </button>
          </div>

          <div class="flex items-center space-x-4">
            <div
              v-if="form.processing"
              class="flex items-center text-sm text-gray-500 dark:text-gray-400"
            >
              <svg
                class="animate-spin -ml-1 mr-2 h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
              </svg>
              Saving...
            </div>

            <button
              type="submit"
              :disabled="form.processing"
              class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
                  d="M5 13l4 4L19 7"
                />
              </svg>
              {{ form.processing ? "Creating..." : "Create Interest" }}
            </button>
          </div>
        </div>
      </div>
    </form>
  </DashboardLayout>
</template>
