<template>
  <DashboardLayout title="Add Skill">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center space-x-4">
        <Link
          :href="route('admin.skill.index')"
          class="text-gray-400 hover:text-white transition-colors"
        >
          ← Back to Skills
        </Link>
        <h1 class="text-2xl font-bold text-white">Add New Skill</h1>
      </div>

      <!-- Form -->
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Skill Name -->
          <div>
            <label
              for="name"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Skill Name *
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="e.g., Laravel, JavaScript, Python"
              required
            />
            <div v-if="errors.name" class="mt-1 text-sm text-red-400">
              {{ errors.name }}
            </div>
          </div>

          <!-- Category -->
          <div>
            <label
              for="category"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Category *
            </label>
            <select
              id="category"
              v-model="form.category"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              required
            >
              <option value="">Select category</option>
              <option value="Web Programming">Web Programming</option>
              <option value="Mobile Programming">Mobile Programming</option>
              <option value="Data Analysis">Data Analysis</option>
              <option value="Office Suite">Office Suite</option>
              <option value="Language">Language</option>
              <option value="Framework">Framework</option>
              <option value="Database">Database</option>
              <option value="Other">Other</option>
            </select>
            <div v-if="errors.category" class="mt-1 text-sm text-red-400">
              {{ errors.category }}
            </div>
          </div>

          <!-- Custom Category Input -->
          <div v-if="form.category === 'Other'">
            <label
              for="custom_category"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Custom Category *
            </label>
            <input
              id="custom_category"
              v-model="customCategory"
              type="text"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="Enter custom category"
              required
            />
          </div>

          <!-- Level -->
          <div>
            <label
              for="level"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Proficiency Level *
            </label>
            <select
              id="level"
              v-model="form.level"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              required
            >
              <option value="">Select level</option>
              <option value="pemula">Pemula (Beginner)</option>
              <option value="menengah">Menengah (Intermediate)</option>
              <option value="mahir">Mahir (Advanced)</option>
            </select>
            <div v-if="errors.level" class="mt-1 text-sm text-red-400">
              {{ errors.level }}
            </div>
          </div>

          <!-- Level Description -->
          <div class="text-sm text-gray-400 bg-gray-700/30 p-3 rounded">
            <strong>Level Guidelines:</strong><br />
            <strong>Pemula:</strong> Basic understanding, learning phase<br />
            <strong>Menengah:</strong> Can work independently, good
            understanding<br />
            <strong>Mahir:</strong> Expert level, can teach others
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end space-x-4 pt-6">
            <Link
              :href="route('admin.skill.index')"
              class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2 bg-red-900 hover:bg-red-800 text-white rounded-lg transition-colors disabled:opacity-50"
            >
              <span v-if="form.processing">Creating...</span>
              <span v-else>Create Skill</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

defineProps({
  errors: Object,
});

const form = useForm({
  name: "",
  category: "",
  level: "",
});

const customCategory = ref("");

// Watch for custom category changes
watch(customCategory, (newValue) => {
  if (form.category === "Other") {
    form.category = newValue;
  }
});

const submit = () => {
  form.post(route("admin.skill.store"));
};
</script>
