<template>
  <Head title="Edit Skill" />

  <AdminLayout>
    <div class="max-w-2xl mx-auto">
      <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Skill</h1>

        <form @submit.prevent="submit">
          <div class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2"
                >Category</label
              >
              <input
                v-model="form.category"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
              <div v-if="errors.category" class="text-red-600 text-sm mt-1">
                {{ errors.category }}
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2"
                >Skill Name</label
              >
              <input
                v-model="form.name"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
              <div v-if="errors.name" class="text-red-600 text-sm mt-1">
                {{ errors.name }}
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2"
                >Level</label
              >
              <select
                v-model="form.level"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              >
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
                <option value="expert">Expert</option>
              </select>
              <div v-if="errors.level" class="text-red-600 text-sm mt-1">
                {{ errors.level }}
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2"
                >Description</label
              >
              <textarea
                v-model="form.description"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              ></textarea>
              <div v-if="errors.description" class="text-red-600 text-sm mt-1">
                {{ errors.description }}
              </div>
            </div>
          </div>

          <div class="flex justify-end space-x-4 mt-8">
            <Link
              :href="route('admin.skills.index')"
              class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
            >
              <span v-if="form.processing">Updating...</span>
              <span v-else>Update Skill</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AdminLayout from "../../../Layouts/AdminLayout.vue";

const props = defineProps({
  skill: Object,
  errors: Object,
});

const form = useForm({
  category: props.skill.category,
  name: props.skill.name,
  level: props.skill.level,
  description: props.skill.description,
});

const submit = () => {
  form.put(route("admin.skills.update", props.skill.id));
};
</script>
