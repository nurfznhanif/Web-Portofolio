<!-- resources/js/Pages/Admin/Skill/Edit.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Edit Skill</h1>
          <p class="text-gray-400">Update skill information</p>
        </div>
        <Link
          :href="route('admin.skill.index')"
          class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors"
        >
          Back to Skills
        </Link>
      </div>

      <!-- Form -->
      <div class="bg-gray-800 rounded-lg p-6 max-w-2xl">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Skill Name -->
          <FormInput
            v-model="form.name"
            label="Skill Name"
            placeholder="e.g., Laravel, JavaScript, Project Management"
            required
            :error="form.errors.name"
          />

          <!-- Category and Level -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormSelect
              v-model="form.category"
              label="Category"
              placeholder="Select category"
              :options="categoryOptions"
              required
              :error="form.errors.category"
            />

            <FormSelect
              v-model="form.level"
              label="Proficiency Level"
              placeholder="Select level"
              :options="levelOptions"
              required
              :error="form.errors.level"
            />
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-4 pt-6 border-t border-gray-700">
            <Link
              :href="route('admin.skill.index')"
              class="px-6 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
            >
              <span v-if="form.processing">Updating...</span>
              <span v-else>Update Skill</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import FormInput from "@/Components/Form/FormInput.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";

const props = defineProps({
  skill: Object,
});

const form = useForm({
  name: props.skill.name,
  category: props.skill.category,
  level: props.skill.level,
});

const categoryOptions = [
  { value: "Programming Languages", label: "Programming Languages" },
  { value: "Frameworks & Libraries", label: "Frameworks & Libraries" },
  { value: "Databases", label: "Databases" },
  { value: "DevOps & Tools", label: "DevOps & Tools" },
  { value: "Design & UI/UX", label: "Design & UI/UX" },
  { value: "Mobile Development", label: "Mobile Development" },
  { value: "Cloud Services", label: "Cloud Services" },
  { value: "Project Management", label: "Project Management" },
  { value: "Soft Skills", label: "Soft Skills" },
  { value: "Other Technical", label: "Other Technical" },
];

const levelOptions = [
  { value: "pemula", label: "Pemula (Beginner)" },
  { value: "menengah", label: "Menengah (Intermediate)" },
  { value: "mahir", label: "Mahir (Expert)" },
];

const submit = () => {
  form.put(route("admin.skill.update", props.skill.id));
};
</script>
