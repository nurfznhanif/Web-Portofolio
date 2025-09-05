<!-- Admin/Portfolio/Create.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Create New Project</h1>
          <p class="text-gray-400">Add a new project to your portfolio</p>
        </div>
        <Link
          :href="route('admin.portfolio.index')"
          class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors"
        >
          Back to List
        </Link>
      </div>

      <!-- Form -->
      <div class="bg-gray-800 rounded-lg p-6">
        <form @submit.prevent="submit" class="space-y-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
              <!-- Title -->
              <FormInput
                v-model="form.title"
                label="Project Title"
                placeholder="Enter project title"
                required
                :error="form.errors.title"
              />

              <!-- Project Type -->
              <FormSelect
                v-model="form.project_type"
                label="Project Type"
                placeholder="Select project type"
                :options="projectTypeOptions"
                required
                :error="form.errors.project_type"
              />

              <!-- Project Date -->
              <FormInput
                v-model="form.project_date"
                label="Project Date"
                type="date"
                required
                :error="form.errors.project_date"
              />

              <!-- GitHub URL -->
              <FormInput
                v-model="form.github_url"
                label="GitHub URL"
                type="url"
                placeholder="https://github.com/username/repo"
                :error="form.errors.github_url"
                help="Link to the project repository"
              />

              <!-- Technologies -->
              <FormInput
                v-model="form.technologies"
                label="Technologies"
                placeholder="Laravel, Vue.js, MySQL, Tailwind CSS"
                required
                :error="form.errors.technologies"
                help="Separate technologies with commas"
              />
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
              <!-- Project Image -->
              <FormFileUpload
                v-model="form.image"
                label="Project Image"
                accept="image/*"
                :error="form.errors.image"
                help="Upload a screenshot or mockup of your project"
              />

              <!-- Description -->
              <FormTextarea
                v-model="form.description"
                label="Description"
                placeholder="Describe your project, your role, and what you accomplished..."
                :rows="6"
                required
                :error="form.errors.description"
                :max-length="1000"
              />

              <!-- Challenges -->
              <FormTextarea
                v-model="form.challenges"
                label="Challenges"
                placeholder="What challenges did you face during this project?"
                :rows="4"
                :error="form.errors.challenges"
                :max-length="500"
              />

              <!-- Solutions -->
              <FormTextarea
                v-model="form.solutions"
                label="Solutions"
                placeholder="How did you solve the challenges?"
                :rows="4"
                :error="form.errors.solutions"
                :max-length="500"
              />
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-4 pt-6 border-t border-gray-700">
            <Link
              :href="route('admin.portfolio.index')"
              class="px-6 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
            >
              <span v-if="form.processing">Creating...</span>
              <span v-else>Create Project</span>
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
import FormTextarea from "@/Components/Form/FormTextarea.vue";
import FormFileUpload from "@/Components/Form/FormFileUpload.vue";

const form = useForm({
  title: "",
  description: "",
  technologies: "",
  challenges: "",
  solutions: "",
  github_url: "",
  project_type: "",
  project_date: "",
  image: null,
});

const projectTypeOptions = [
  { value: "Web Development", label: "Web Development" },
  { value: "Web Application", label: "Web Application" },
  { value: "Mobile Application", label: "Mobile Application" },
  { value: "Desktop Application", label: "Desktop Application" },
  { value: "API Development", label: "API Development" },
  { value: "Integrated System", label: "Integrated System" },
  { value: "E-Commerce", label: "E-Commerce" },
  { value: "CMS", label: "Content Management System" },
  { value: "Dashboard", label: "Dashboard/Analytics" },
  { value: "Game Development", label: "Game Development" },
  { value: "Machine Learning", label: "Machine Learning/AI" },
  { value: "Other", label: "Other" },
];

const submit = () => {
  form.post(route("admin.portfolio.store"), {
    forceFormData: true, // Important for file uploads
  });
};
</script>
