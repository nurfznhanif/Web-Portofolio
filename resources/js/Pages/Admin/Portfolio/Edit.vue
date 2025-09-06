<script setup>
import { ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import FormInput from "@/Components/Form/FormInput.vue";
import FormTextarea from "@/Components/Form/FormTextarea.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";
import FormFileUpload from "@/Components/Form/FormFileUpload.vue";

// Props
const props = defineProps({
  portfolio: {
    type: Object,
    required: true,
  },
});

// Form data
const form = useForm({
  title: props.portfolio.title,
  description: props.portfolio.description,
  project_type: props.portfolio.project_type,
  project_date: props.portfolio.project_date,
  technologies: props.portfolio.technologies,
  challenges: props.portfolio.challenges || "",
  solutions: props.portfolio.solutions || "",
  github_url: props.portfolio.github_url || "",
  live_url: props.portfolio.live_url || "",
  images: null,
  is_featured: props.portfolio.is_featured,
  order: props.portfolio.order,
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

const hasChanges = computed(() => {
  return form.isDirty;
});

// Methods
const submitForm = () => {
  if (!isFormValid.value) return;

  isSubmitting.value = true;

  form.patch(`/admin/portfolio/${props.portfolio.id}`, {
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

const resetForm = () => {
  form.reset();
  form.clearErrors();
};

const previewProject = () => {
  previewMode.value = !previewMode.value;
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};
</script>

<template>
  <DashboardLayout
    title="Edit Project"
    :breadcrumbs="[
      { name: 'Dashboard', href: '/dashboard' },
      { name: 'Portfolio', href: '/admin/portfolio' },
      { name: portfolio.title },
    ]"
  >
    <Head :title="`Edit ${portfolio.title}`" />

    <div class="p-6 max-w-4xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
              Edit Project: {{ portfolio.title }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
              Update your project information and settings
            </p>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center space-x-3">
            <button
              @click="previewProject"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
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
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                ></path>
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                ></path>
              </svg>
              {{ previewMode ? "Edit Mode" : "Preview" }}
            </button>
          </div>
        </div>

        <!-- Status Info -->
        <div class="mt-4 flex items-center space-x-4 text-sm">
          <div class="flex items-center text-gray-600 dark:text-gray-400">
            <svg
              class="w-4 h-4 mr-1"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4h3a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"
              ></path>
            </svg>
            Created: {{ formatDate(portfolio.created_at) }}
          </div>
          <div class="flex items-center text-gray-600 dark:text-gray-400">
            <svg
              class="w-4 h-4 mr-1"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
              ></path>
            </svg>
            Last updated: {{ formatDate(portfolio.updated_at) }}
          </div>
          <div
            v-if="portfolio.is_featured"
            class="flex items-center text-blue-600 dark:text-blue-400"
          >
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
              ></path>
            </svg>
            Featured Project
          </div>
        </div>
      </div>

      <!-- Preview Mode -->
      <div
        v-if="previewMode"
        class="mb-8 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
          Project Preview
        </h2>

        <div class="space-y-4">
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ form.title }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              {{ form.project_type }} • {{ formatDate(form.project_date) }}
            </p>
          </div>

          <p class="text-gray-700 dark:text-gray-300">{{ form.description }}</p>

          <div>
            <h4 class="font-medium text-gray-900 dark:text-white mb-2">
              Technologies:
            </h4>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="tech in technologiesArray"
                :key="tech"
                class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm"
              >
                {{ tech }}
              </span>
            </div>
          </div>

          <div
            v-if="form.challenges"
            class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg"
          >
            <h4 class="font-medium text-yellow-800 dark:text-yellow-200 mb-2">
              Challenges:
            </h4>
            <p class="text-yellow-700 dark:text-yellow-300">
              {{ form.challenges }}
            </p>
          </div>

          <div
            v-if="form.solutions"
            class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg"
          >
            <h4 class="font-medium text-green-800 dark:text-green-200 mb-2">
              Solutions:
            </h4>
            <p class="text-green-700 dark:text-green-300">
              {{ form.solutions }}
            </p>
          </div>

          <div class="flex space-x-4">
            <a
              v-if="form.github_url"
              :href="form.github_url"
              target="_blank"
              class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors"
            >
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"
                />
              </svg>
              View Source
            </a>

            <a
              v-if="form.live_url"
              :href="form.live_url"
              target="_blank"
              class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
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
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                ></path>
              </svg>
              Live Demo
            </a>
          </div>
        </div>
      </div>

      <!-- Form -->
      <form v-if="!previewMode" @submit.prevent="submitForm" class="space-y-6">
        <!-- Unsaved Changes Warning -->
        <div
          v-if="hasChanges"
          class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4"
        >
          <div class="flex">
            <svg
              class="w-5 h-5 text-yellow-600 mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.856-.833-2.828 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
              ></path>
            </svg>
            <div class="text-sm text-yellow-800 dark:text-yellow-200">
              <p class="font-medium">You have unsaved changes</p>
              <p>Make sure to save your changes before leaving this page.</p>
            </div>
          </div>
        </div>

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
                help="Upload new screenshots or images (existing images will be preserved unless replaced)"
              />
            </div>
          </div>

          <!-- Current Images -->
          <div
            v-if="portfolio.images && portfolio.images.length > 0"
            class="mt-6"
          >
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
              Current Images:
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div
                v-for="(image, index) in portfolio.images"
                :key="index"
                class="relative group"
              >
                <img
                  :src="image"
                  :alt="`${portfolio.title} image ${index + 1}`"
                  class="w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                />
                <div
                  class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center"
                >
                  <button
                    type="button"
                    class="text-white hover:text-red-300 p-1"
                    title="Remove image"
                  >
                    <svg
                      class="w-5 h-5"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                      ></path>
                    </svg>
                  </button>
                </div>
              </div>
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
          <div class="flex items-center space-x-4">
            <button
              type="button"
              @click="$inertia.visit('/admin/portfolio')"
              class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
            >
              Cancel
            </button>

            <button
              type="button"
              @click="resetForm"
              :disabled="!hasChanges"
              class="px-6 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Reset Changes
            </button>
          </div>

          <div class="flex items-center space-x-4">
            <button
              type="submit"
              :disabled="!isFormValid || isSubmitting || !hasChanges"
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
              {{ isSubmitting ? "Updating..." : "Update Project" }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </DashboardLayout>
</template>

<style scoped>
/* Same styles as Create component */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.tech-tag {
  @apply inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 transition-all duration-200;
}

.tech-tag:hover {
  @apply bg-blue-200 dark:bg-blue-800;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>
