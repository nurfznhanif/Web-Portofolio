<template>
  <DashboardLayout title="Edit Portfolio">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center space-x-4">
        <Link
          :href="route('admin.portfolio.index')"
          class="text-gray-400 hover:text-white transition-colors"
        >
          ← Back to Portfolio
        </Link>
        <h1 class="text-2xl font-bold text-white">Edit Portfolio Project</h1>
      </div>

      <!-- Form -->
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Title -->
          <div>
            <label
              for="title"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Project Title *
            </label>
            <input
              id="title"
              v-model="form.title"
              type="text"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="Enter project title"
              required
            />
            <div v-if="errors.title" class="mt-1 text-sm text-red-400">
              {{ errors.title }}
            </div>
          </div>

          <!-- Project Type -->
          <div>
            <label
              for="project_type"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Project Type *
            </label>
            <select
              id="project_type"
              v-model="form.project_type"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              required
            >
              <option value="">Select project type</option>
              <option value="Web Development">Web Development</option>
              <option value="Web Application">Web Application</option>
              <option value="Mobile Application">Mobile Application</option>
              <option value="Desktop Application">Desktop Application</option>
              <option value="Integrated System">Integrated System</option>
              <option value="API Development">API Development</option>
              <option value="Other">Other</option>
            </select>
            <div v-if="errors.project_type" class="mt-1 text-sm text-red-400">
              {{ errors.project_type }}
            </div>
          </div>

          <!-- Technologies -->
          <div>
            <label
              for="technologies"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Technologies Used *
            </label>
            <input
              id="technologies"
              v-model="form.technologies"
              type="text"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="e.g., Laravel, Vue.js, MySQL (separated by commas)"
              required
            />
            <p class="mt-1 text-xs text-gray-400">
              Separate multiple technologies with commas
            </p>
            <div v-if="errors.technologies" class="mt-1 text-sm text-red-400">
              {{ errors.technologies }}
            </div>
          </div>

          <!-- Project Date -->
          <div>
            <label
              for="project_date"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Project Date *
            </label>
            <input
              id="project_date"
              v-model="form.project_date"
              type="date"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              required
            />
            <div v-if="errors.project_date" class="mt-1 text-sm text-red-400">
              {{ errors.project_date }}
            </div>
          </div>

          <!-- Description -->
          <div>
            <label
              for="description"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Project Description *
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="5"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="Describe your project, your role, and what you accomplished..."
              required
            ></textarea>
            <div v-if="errors.description" class="mt-1 text-sm text-red-400">
              {{ errors.description }}
            </div>
          </div>

          <!-- Challenges -->
          <div>
            <label
              for="challenges"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Challenges Faced
            </label>
            <textarea
              id="challenges"
              v-model="form.challenges"
              rows="3"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="What challenges did you face during this project?"
            ></textarea>
            <div v-if="errors.challenges" class="mt-1 text-sm text-red-400">
              {{ errors.challenges }}
            </div>
          </div>

          <!-- Solutions -->
          <div>
            <label
              for="solutions"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Solutions & Lessons Learned
            </label>
            <textarea
              id="solutions"
              v-model="form.solutions"
              rows="3"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="How did you solve the challenges? What did you learn?"
            ></textarea>
            <div v-if="errors.solutions" class="mt-1 text-sm text-red-400">
              {{ errors.solutions }}
            </div>
          </div>

          <!-- GitHub URL -->
          <div>
            <label
              for="github_url"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              GitHub URL
            </label>
            <input
              id="github_url"
              v-model="form.github_url"
              type="url"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="https://github.com/username/repository"
            />
            <div v-if="errors.github_url" class="mt-1 text-sm text-red-400">
              {{ errors.github_url }}
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end space-x-4 pt-6">
            <Link
              :href="route('admin.portfolio.index')"
              class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2 bg-red-900 hover:bg-red-800 text-white rounded-lg transition-colors disabled:opacity-50"
            >
              <span v-if="form.processing">Updating...</span>
              <span v-else>Update Portfolio</span>
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

const props = defineProps({
  portfolio: Object,
  errors: Object,
});

const form = useForm({
  title: props.portfolio.title,
  project_type: props.portfolio.project_type,
  technologies: props.portfolio.technologies,
  project_date: props.portfolio.project_date,
  description: props.portfolio.description,
  challenges: props.portfolio.challenges,
  solutions: props.portfolio.solutions,
  github_url: props.portfolio.github_url,
});

const submit = () => {
  form.put(route("admin.portfolio.update", props.portfolio.id), {
    onSuccess: () => {
      // Form will redirect automatically on success
    },
  });
};
</script>
