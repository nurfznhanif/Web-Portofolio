<!-- resources/js/Pages/Admin/Certification/Create.vue -->
<script setup>
import { ref, computed } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Form setup
const form = useForm({
  name: "",
  issuer: "",
  description: "",
  issue_date: "",
  expiry_date: "",
  skills: [],
  credential_url: "",
});

const previewMode = ref(false);
const skillInput = ref("");

// Computed
const hasExpiry = computed(() => !!form.expiry_date);

// Methods
const submitForm = () => {
  form.post("/admin/certifications", {
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
  router.visit("/admin/certifications");
};

const addSkill = () => {
  if (
    skillInput.value.trim() &&
    !form.skills.includes(skillInput.value.trim())
  ) {
    form.skills.push(skillInput.value.trim());
    skillInput.value = "";
  }
};

const removeSkill = (index) => {
  form.skills.splice(index, 1);
};

const handleKeyPress = (event) => {
  if (event.key === "Enter") {
    event.preventDefault();
    addSkill();
  }
};

const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

const validateUrl = (url) => {
  if (!url) return true;
  try {
    new URL(url);
    return true;
  } catch {
    return false;
  }
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Certifications", href: "/admin/certifications" },
  { name: "Create", href: "/admin/certifications/create", current: true },
];
</script>

<template>
  <Head title="Add New Certification" />

  <DashboardLayout :title="'Add New Certification'" :breadcrumbs="breadcrumbs">
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
            Certification Details
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
        <div class="max-w-3xl">
          <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
              <div
                class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-8 h-8 text-blue-600 dark:text-blue-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
              </div>
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ form.name || "Certification Name" }}
              </h3>
              <p class="text-lg text-gray-600 dark:text-gray-400">
                {{ form.issuer || "Issuing Organization" }}
              </p>
              <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <dt
                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                  >
                    Issue Date
                  </dt>
                  <dd class="text-sm text-gray-900 dark:text-white">
                    {{ formatDate(form.issue_date) || "Not specified" }}
                  </dd>
                </div>
                <div>
                  <dt
                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                  >
                    Expiry Date
                  </dt>
                  <dd class="text-sm text-gray-900 dark:text-white">
                    {{ formatDate(form.expiry_date) || "No expiry" }}
                  </dd>
                </div>
              </div>
              <div v-if="form.description" class="mt-4">
                <dt
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                  Description
                </dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                  {{ form.description }}
                </dd>
              </div>
              <div v-if="form.skills.length > 0" class="mt-4">
                <dt
                  class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2"
                >
                  Related Skills
                </dt>
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="skill in form.skills"
                    :key="skill"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200"
                  >
                    {{ skill }}
                  </span>
                </div>
              </div>
              <div v-if="form.credential_url" class="mt-4">
                <a
                  :href="form.credential_url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500"
                >
                  View Credential
                  <svg
                    class="ml-1 w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                    />
                  </svg>
                </a>
              </div>
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

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Certification Name -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Certification Name <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="e.g., AWS Certified Solutions Architect"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
              <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">
                {{ form.errors.name }}
              </p>
            </div>

            <!-- Issuing Organization -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Issuing Organization <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.issuer"
                type="text"
                required
                placeholder="e.g., Amazon Web Services"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
              <p v-if="form.errors.issuer" class="mt-1 text-sm text-red-500">
                {{ form.errors.issuer }}
              </p>
            </div>
          </div>
        </div>

        <!-- Dates -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Certification Dates
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Issue Date -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Issue Date <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.issue_date"
                type="date"
                required
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
              <p
                v-if="form.errors.issue_date"
                class="mt-1 text-sm text-red-500"
              >
                {{ form.errors.issue_date }}
              </p>
            </div>

            <!-- Expiry Date -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >
                Expiry Date
              </label>
              <input
                v-model="form.expiry_date"
                type="date"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
              <p
                v-if="form.errors.expiry_date"
                class="mt-1 text-sm text-red-500"
              >
                {{ form.errors.expiry_date }}
              </p>
              <p class="mt-1 text-sm text-gray-500">
                Leave empty if the certification doesn't expire
              </p>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Description & Details
          </h2>

          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
              Description
            </label>
            <textarea
              v-model="form.description"
              rows="4"
              placeholder="Describe what this certification covers, its significance, or any additional context..."
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-vertical"
            ></textarea>
            <p v-if="form.errors.description" class="mt-1 text-sm text-red-500">
              {{ form.errors.description }}
            </p>
            <p class="mt-1 text-sm text-gray-500">
              Provide additional details about this certification (Optional)
            </p>
          </div>
        </div>

        <!-- Skills -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Related Skills
          </h2>

          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
              Skills Covered
            </label>
            <div class="flex flex-wrap gap-2 mb-3">
              <span
                v-for="(skill, index) in form.skills"
                :key="index"
                class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200"
              >
                {{ skill }}
                <button
                  type="button"
                  @click="removeSkill(index)"
                  class="ml-2 inline-flex items-center justify-center w-4 h-4 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200"
                >
                  <svg
                    class="w-3 h-3"
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
                </button>
              </span>
            </div>
            <div class="flex gap-2">
              <input
                v-model="skillInput"
                type="text"
                placeholder="Add a skill (press Enter)"
                @keypress="handleKeyPress"
                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
              <button
                type="button"
                @click="addSkill"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
              >
                Add
              </button>
            </div>
            <p v-if="form.errors.skills" class="mt-1 text-sm text-red-500">
              {{ form.errors.skills }}
            </p>
            <p class="mt-1 text-sm text-gray-500">
              Add skills that are covered or validated by this certification
            </p>
          </div>
        </div>

        <!-- Credential URL -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Credential Information
          </h2>

          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
              Credential URL
            </label>
            <input
              v-model="form.credential_url"
              type="url"
              placeholder="https://example.com/verify/credential-id"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
            <p
              v-if="form.errors.credential_url"
              class="mt-1 text-sm text-red-500"
            >
              {{ form.errors.credential_url }}
            </p>
            <p
              v-if="form.credential_url && !validateUrl(form.credential_url)"
              class="mt-1 text-sm text-red-500"
            >
              Please enter a valid URL
            </p>
            <p class="mt-1 text-sm text-gray-500">
              Link to verify or view the credential online (Optional)
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
              {{ form.processing ? "Creating..." : "Create Certification" }}
            </button>
          </div>
        </div>
      </div>
    </form>
  </DashboardLayout>
</template>
