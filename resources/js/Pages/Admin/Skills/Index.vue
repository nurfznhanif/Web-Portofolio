<template>
  <Head title="Skills Management" />

  <AdminLayout>
    <div class="space-y-6">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Skills Management</h1>
        <Link
          :href="route('admin.skills.create')"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
        >
          <i class="fas fa-plus mr-2"></i>Add New Skill
        </Link>
      </div>

      <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                >
                  Category
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                >
                  Name
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                >
                  Level
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="skill in skillsFlat" :key="skill.id">
                <td class="px-6 py-4 text-sm text-gray-900">
                  {{ skill.category }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  {{ skill.name }}
                </td>
                <td class="px-6 py-4 text-sm">
                  <span
                    :class="getLevelColor(skill.level)"
                    class="px-2 py-1 rounded-full text-xs font-medium"
                  >
                    {{ skill.level }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm space-x-2">
                  <Link
                    :href="route('admin.skills.edit', skill.id)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    Edit
                  </Link>
                  <button
                    @click="deleteSkill(skill)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "../../../Layouts/AdminLayout.vue";

defineProps({
  skills: Object,
  skillsFlat: Array,
});

const getLevelColor = (level) => {
  const colors = {
    beginner: "bg-gray-100 text-gray-800",
    intermediate: "bg-blue-100 text-blue-800",
    advanced: "bg-green-100 text-green-800",
    expert: "bg-purple-100 text-purple-800",
  };
  return colors[level] || "bg-gray-100 text-gray-800";
};

const deleteSkill = (skill) => {
  if (confirm("Are you sure you want to delete this skill?")) {
    router.delete(route("admin.skills.destroy", skill.id));
  }
};
</script>
