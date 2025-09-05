<template>
  <DashboardLayout title="Languages">
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Languages</h1>
          <p class="text-gray-400">Manage your language skills</p>
        </div>
        <Link
          :href="route('admin.languages.create')"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
        >
          Add Language
        </Link>
      </div>

      <div class="bg-gray-800 rounded-lg p-6">
        <div v-if="languages.length === 0" class="text-center py-12">
          <h3 class="text-lg font-medium text-white mb-2">No languages yet</h3>
          <p class="text-gray-400 mb-4">Add your first language skill</p>
          <Link
            :href="route('admin.languages.create')"
            class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
          >
            Add Language
          </Link>
        </div>

        <div
          v-else
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
          <div
            v-for="language in languages"
            :key="language.id"
            class="bg-gray-700 rounded-lg p-6"
          >
            <div class="flex justify-between items-start mb-4">
              <div>
                <h3 class="text-lg font-semibold text-white">
                  {{ language.name }}
                </h3>
                <p v-if="language.native_name" class="text-gray-400 text-sm">
                  {{ language.native_name }}
                </p>
              </div>
              <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded">
                {{ language.code }}
              </span>
            </div>

            <div class="mb-4">
              <div class="flex justify-between items-center mb-2">
                <span class="text-gray-400 text-sm">Proficiency</span>
                <span class="text-white text-sm font-medium">{{
                  language.level_label
                }}</span>
              </div>
              <div class="w-full bg-gray-600 rounded-full h-2">
                <div
                  class="bg-blue-500 h-2 rounded-full"
                  :style="{ width: language.level_percentage + '%' }"
                ></div>
              </div>
            </div>

            <div class="flex justify-between items-center">
              <div class="flex space-x-2">
                <Link
                  :href="route('admin.languages.edit', language.id)"
                  class="text-blue-400 hover:text-blue-300 text-sm"
                >
                  Edit
                </Link>
                <button
                  @click="confirmDelete(language)"
                  class="text-red-400 hover:text-red-300 text-sm"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

defineProps({
  languages: Array,
  levelOptions: Array,
});

const confirmDelete = (language) => {
  if (confirm(`Are you sure you want to delete ${language.name}?`)) {
    // Delete logic here
  }
};
</script>
