<script setup>
import { ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import FormInput from "@/Components/Form/FormInput.vue";
import FormTextarea from "@/Components/Form/FormTextarea.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";
import FormFileUpload from "@/Components/Form/FormFileUpload.vue";

// Form data
const form = useForm({
  title: "",
  description: "",
  project_type: "",
  project_date: "",
  technologies: "",
  challenges: "",
  solutions: "",
  github_url: "",
  live_url: "",
  images: null,
  is_featured: false,
  order: 0,
});

// Reactive state
const isSubmitting = ref(false);
const previewMode = ref(false);

// Project type options
const projectTypeOptions = [
  { value: "", label: "Select Project Type" },
  { value: "Web Development", label: "Web Development" },
  { value: "Web Application", label: "Web Application" },
  { value: "Web System", label: "Web System" },
  { value: "Mobile Application", label: "Mobile Application" },
  { value: "Desktop Application", label: "Desktop Application" },
  { value: "API Development", label: "API Development" },
  { value: "E-commerce", label: "E-commerce" },
  { value: "CMS", label: "Content Management System" },
  { value: "Other", label: "Other" },
];

// Computed properties
const isFormValid = computed(() => {
  return (
    form.title &&
    form.description &&
    form.project_type &&
    form.project_date &&
    form.technologies
  );
});

const technologiesArray = computed(() => {
  return form.technologies
    .split(",")
    .map((tech) => tech.trim())
    .filter((tech) => tech);
});

const currentDate = computed(() => {
  return new Date().toISOString().split("T")[0];
});

// Methods
const submitForm = () => {
  if (!isFormValid.value) return;

  isSubmitting.value = true;

  form.post("/admin/portfolio", {
    onSuccess: () => {
      // Redirect handled by controller
    },
    onError: () => {
      isSubmitting.value = false;
    },
    onFinish: () => {
      isSubmitting.value = false;
    },
  });
};

const saveDraft = () => {
  form.post("/admin/portfolio", {
    data: {
      ...form.data(),
      is_draft: true,
    },
  });
};

const addTechnology = (tech) => {
  const techs = form.technologies
    ? form.technologies.split(",").map((t) => t.trim())
    : [];
  if (!techs.includes(tech)) {
    techs.push(tech);
    form.technologies = techs.join(", ");
  }
};

const removeTechnology = (index) => {
  const techs = technologiesArray.value;
  techs.splice(index, 1);
  form.technologies = techs.join(", ");
};

const commonTechnologies = [
  "Laravel",
  "Vue.js",
  "React",
  "PHP",
  "JavaScript",
  "MySQL",
  "PostgreSQL",
  "Node.js",
  "Express",
  "MongoDB",
  "HTML5",
  "CSS3",
  "Tailwind CSS",
  "Bootstrap",
  "jQuery",
  "Python",
  "Django",
  "Flask",
  "Java",
  "Spring Boot",
  "C#",
  ".NET",
  "Angular",
  "TypeScript",
  "GraphQL",
  "REST API",
  "Docker",
  "Git",
  "GitHub",
  "GitLab",
  "AWS",
  "Firebase",
  "Vercel",
  "Netlify",
];

const validateUrl = (url) => {
  if (!url) return true;
  try {
    new URL(url);
    return true;
  } catch {
    return false;
  }
};
</script>

<template>
  <DashboardLayout
    title="Add New Project"
    :breadcrumbs="[
      { name: 'Dashboard', href: '/dashboard' },
      { name: 'Portfolio', href: '/admin/portfolio' },
      { name: 'Add New Project' },
    ]"
  >
    <Head title="Add New Project" />

    <div class="p-6 max-w-4xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
          Create New Portfolio Project
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
          Add a new project to showcase your work and skills
        </p>
      </div>

      <!-- Form -->
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Basic Information -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2
            class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
          >
            <svg
              class="w-5 h-5 mr-2 text-blue-600"
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
            Basic Information
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <FormInput
                v-model="form.title"
                label="Project Title"
                placeholder="Enter project title"
                required
                :error="form.errors.title"
                help="Give your project a clear, descriptive title"
              />
            </div>

            <FormSelect
              v-model="form.project_type"
              label="Project Type"
              :options="projectTypeOptions"
              required
              :error="form.errors.project_type"
              help="Select the category that best describes your project"
            />

            <FormInput
              v-model="form.project_date"
              label="Project Date"
              type="date"
              :max="currentDate"
              required
              :error="form.errors.project_date"
              help="When was this project completed?"
            />

            <div class="md:col-span-2">
              <FormTextarea
                v-model="form.description"
                label="Project Description"
                placeholder="Describe your project, what it does, and what makes it special"
                :rows="4"
                required
                :error="form.errors.description"
                help="Provide a detailed description of your project"
                :max-length="1000"
              />
            </div>
          </div>
        </div>

        <!-- Technologies -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2
            class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
          >
            <svg
              class="w-5 h-5 mr-2 text-purple-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
              ></path>
            </svg>
            Technologies Used
          </h2>

          <FormInput
            v-model="form.technologies"
            label="Technologies"
            placeholder="Laravel, Vue.js, MySQL, Tailwind CSS"
            required
            :error="form.errors.technologies"
            help="Enter technologies separated by commas"
          />

          <!-- Technology Tags -->
          <div v-if="technologiesArray.length > 0" class="mt-3">
            <div class="flex flex-wrap gap-2">
              <span
                v-for="(tech, index) in technologiesArray"
                :key="index"
                class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
              >
                {{ tech }}
                <button
                  type="button"
                  @click="removeTechnology(index)"
                  class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100"
                >
                  <svg
                    class="w-4 h-4"
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
                </button>
              </span>
            </div>
          </div>

          <!-- Common Technologies -->
          <div class="mt-4">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
              Quick add common technologies:
            </p>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="tech in commonTechnologies.slice(0, 12)"
                :key="tech"
                type="button"
                @click="addTechnology(tech)"
                class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                :class="{ 'opacity-50': technologiesArray.includes(tech) }"
                :disabled="technologiesArray.includes(tech)"
              >
                {{ tech }}
              </button>
            </div>
          </div>
        </div>

        <!-- Project Details -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2
            class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
          >
            <svg
              class="w-5 h-5 mr-2 text-green-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
              ></path>
            </svg>
            Project Details
          </h2>

          <div class="space-y-6">
            <FormTextarea
              v-model="form.challenges"
              label="Challenges Faced"
              placeholder="Describe the main challenges you encountered during this project"
              :rows="3"
              :error="form.errors.challenges"
              help="What difficulties did you overcome? (Optional)"
              :max-length="500"
            />

            <FormTextarea
              v-model="form.solutions"
              label="Solutions & Approach"
              placeholder="Explain how you solved the challenges and your approach"
              :rows="3"
              :error="form.errors.solutions"
              help="How did you solve the problems? What was your methodology? (Optional)"
              :max-length="500"
            />
          </div>
        </div>

        <!-- Links & Media -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2
            class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
          >
            <svg
              class="w-5 h-5 mr-2 text-indigo-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
              ></path>
            </svg>
            Links & Media
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormInput
              v-model="form.github_url"
              label="GitHub Repository"
              type="url"
              placeholder="https://github.com/username/repository"
              :error="form.errors.github_url"
              help="Link to your source code (Optional)"
            />

            <FormInput
              v-model="form.live_url"
              label="Live Demo URL"
              type="url"
              placeholder="https://example.com"
              :error="form.errors.live_url"
              help="Link to live version of your project (Optional)"
            />

            <div class="md:col-span-2">
              <FormFileUpload
                v-model="form.images"
                label="Project Images"
                accept="image/*"
                :multiple="true"
                :error="form.errors.images"
                help="Upload screenshots or images of your project (Optional)"
              />
            </div>
          </div>
        </div>

        <!-- Settings -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2
            class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
          >
            <svg
              class="w-5 h-5 mr-2 text-orange-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
              ></path>
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
              ></path>
            </svg>
            Project Settings
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center">
              <input
                id="is_featured"
                v-model="form.is_featured"
                type="checkbox"
                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
              />
              <label
                for="is_featured"
                class="ml-2 block text-sm text-gray-900 dark:text-white"
              >
                Feature this project
              </label>
            </div>

            <FormInput
              v-model="form.order"
              label="Display Order"
              type="number"
              min="0"
              placeholder="0"
              :error="form.errors.order"
              help="Lower numbers appear first (0 = highest priority)"
            />
          </div>

          <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <div class="flex">
              <svg
                class="w-5 h-5 text-blue-600 mr-2 mt-0.5"
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
              <div class="text-sm text-blue-800 dark:text-blue-200">
                <p class="font-medium">Featured Projects</p>
                <p>
                  Featured projects will be highlighted on your portfolio
                  homepage and shown prominently to visitors.
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div
          class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700"
        >
          <button
            type="button"
            @click="$inertia.visit('/admin/portfolio')"
            class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
          >
            Cancel
          </button>

          <div class="flex items-center space-x-4">
            <button
              type="button"
              @click="saveDraft"
              class="px-6 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
              :disabled="isSubmitting"
            >
              Save as Draft
            </button>

            <button
              type="submit"
              :disabled="!isFormValid || isSubmitting"
              class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white rounded-lg transition-colors flex items-center"
            >
              <svg
                v-if="isSubmitting"
                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
              {{ isSubmitting ? "Creating..." : "Create Project" }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </DashboardLayout>
</template>

<style scoped>
/* Form specific styles */
.form-section {
  @apply bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6;
}

.section-title {
  @apply text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center;
}

/* Technology tags animation */
.tech-tag {
  @apply inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 transition-all duration-200;
}

.tech-tag:hover {
  @apply bg-blue-200 dark:bg-blue-800;
}

/* Loading animation */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Custom scrollbar for textarea */
textarea::-webkit-scrollbar {
  width: 6px;
}

textarea::-webkit-scrollbar-track {
  background: transparent;
}

textarea::-webkit-scrollbar-thumb {
  background: rgba(156, 163, 175, 0.5);
  border-radius: 3px;
}

textarea::-webkit-scrollbar-thumb:hover {
  background: rgba(156, 163, 175, 0.7);
}
</style>
