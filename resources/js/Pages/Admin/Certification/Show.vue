<!-- resources/js/Pages/Admin/Certification/Show.vue -->
<script setup>
import { computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  certification: {
    type: Object,
    required: true,
  },
});

// Computed
const statusInfo = computed(() => {
  if (!props.certification.expiry_date) {
    return {
      text: "No Expiry",
      class: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
      icon: "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z",
    };
  }

  const expiryDate = new Date(props.certification.expiry_date);
  const now = new Date();

  if (expiryDate < now) {
    return {
      text: "Expired",
      class: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300",
      icon: "M6 18L18 6M6 6l12 12",
    };
  }

  const thirtyDaysFromNow = new Date();
  thirtyDaysFromNow.setDate(thirtyDaysFromNow.getDate() + 30);

  if (expiryDate <= thirtyDaysFromNow) {
    return {
      text: "Expiring Soon",
      class:
        "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300",
      icon: "M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.866-.833-2.464 0L4.732 18.5c-.77.833.192 2.5 1.732 2.5z",
    };
  }

  return {
    text: "Valid",
    class: "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300",
    icon: "M5 13l4 4L19 7",
  };
});

const daysUntilExpiry = computed(() => {
  if (!props.certification.expiry_date) return null;

  const expiryDate = new Date(props.certification.expiry_date);
  const now = new Date();
  const diffTime = expiryDate - now;
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  return diffDays;
});

// Methods
const deleteCertification = () => {
  if (confirm("Are you sure you want to delete this certification?")) {
    router.delete(`/admin/certifications/${props.certification.id}`, {
      onSuccess: () => {
        router.visit("/admin/certifications");
      },
    });
  }
};

const formatDate = (date) => {
  if (!date) return "Not specified";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

const formatDateShort = (date) => {
  if (!date) return "N/A";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Certifications", href: "/admin/certifications" },
  {
    name: props.certification.name,
    href: `/admin/certifications/${props.certification.id}`,
    current: true,
  },
];
</script>

<template>
  <Head :title="certification.name" />

  <DashboardLayout :title="certification.name" :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
      <!-- Header Actions -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
        >
          <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
              <div
                class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-6 h-6 text-blue-600 dark:text-blue-400"
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
            <div>
              <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ certification.name }}
              </h1>
              <p class="text-gray-600 dark:text-gray-400">
                {{ certification.issuer }}
              </p>
            </div>
          </div>

          <div class="flex items-center space-x-3">
            <span
              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
              :class="statusInfo.class"
            >
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
                  :d="statusInfo.icon"
                />
              </svg>
              {{ statusInfo.text }}
            </span>

            <Link
              :href="`/admin/certifications/${certification.id}/edit`"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                />
              </svg>
              Edit Certification
            </Link>

            <button
              @click="deleteCertification"
              class="inline-flex items-center px-4 py-2 border border-red-300 hover:border-red-400 text-red-700 hover:text-red-800 text-sm font-medium rounded-lg transition-colors duration-200"
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
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                />
              </svg>
              Delete
            </button>
          </div>
        </div>
      </div>

      <!-- Certification Details -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Certification Information
          </h2>

          <dl class="space-y-4">
            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Certification Name
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                {{ certification.name }}
              </dd>
            </div>

            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Issuing Organization
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                {{ certification.issuer }}
              </dd>
            </div>

            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Issue Date
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                {{ formatDate(certification.issue_date) }}
              </dd>
            </div>

            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Expiry Date
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                {{ formatDate(certification.expiry_date) || "No expiry" }}
                <span
                  v-if="daysUntilExpiry !== null"
                  class="ml-2 text-xs text-gray-500 dark:text-gray-400"
                >
                  ({{
                    daysUntilExpiry > 0
                      ? `${daysUntilExpiry} days remaining`
                      : `Expired ${Math.abs(daysUntilExpiry)} days ago`
                  }})
                </span>
              </dd>
            </div>

            <div v-if="certification.credential_url">
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Credential URL
              </dt>
              <dd class="mt-1">
                <a
                  :href="certification.credential_url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500"
                >
                  {{ certification.credential_url }}
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
              </dd>
            </div>
          </dl>
        </div>

        <!-- Status and Validity -->
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Status & Validity
          </h2>

          <div class="space-y-4">
            <!-- Current Status -->
            <div
              class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
            >
              <div class="flex items-center">
                <svg
                  class="w-5 h-5 mr-3"
                  :class="
                    statusInfo.text === 'Valid'
                      ? 'text-green-500'
                      : statusInfo.text === 'Expired'
                      ? 'text-red-500'
                      : 'text-yellow-500'
                  "
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    :d="statusInfo.icon"
                  />
                </svg>
                <span class="text-sm font-medium text-gray-900 dark:text-white">
                  Current Status
                </span>
              </div>
              <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="statusInfo.class"
              >
                {{ statusInfo.text }}
              </span>
            </div>

            <!-- Validity Timeline -->
            <div class="space-y-3">
              <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                Validity Timeline
              </h3>

              <div class="space-y-2">
                <div class="flex items-center text-sm">
                  <svg
                    class="w-4 h-4 mr-2 text-green-500"
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
                  <span class="text-gray-600 dark:text-gray-400">Issued:</span>
                  <span class="ml-2 text-gray-900 dark:text-white">{{
                    formatDateShort(certification.issue_date)
                  }}</span>
                </div>

                <div
                  v-if="certification.expiry_date"
                  class="flex items-center text-sm"
                >
                  <svg
                    class="w-4 h-4 mr-2"
                    :class="
                      daysUntilExpiry && daysUntilExpiry > 0
                        ? 'text-red-500'
                        : 'text-gray-400'
                    "
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
                  <span class="text-gray-600 dark:text-gray-400">Expires:</span>
                  <span class="ml-2 text-gray-900 dark:text-white">{{
                    formatDateShort(certification.expiry_date)
                  }}</span>
                </div>

                <div v-else class="flex items-center text-sm">
                  <svg
                    class="w-4 h-4 mr-2 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                  <span class="text-gray-600 dark:text-gray-400">Expires:</span>
                  <span class="ml-2 text-gray-900 dark:text-white">Never</span>
                </div>
              </div>
            </div>

            <!-- Renewal Reminder -->
            <div
              v-if="
                daysUntilExpiry !== null &&
                daysUntilExpiry <= 60 &&
                daysUntilExpiry > 0
              "
              class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg"
            >
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg
                    class="h-5 w-5 text-yellow-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.866-.833-2.464 0L4.732 18.5c-.77.833.192 2.5 1.732 2.5z"
                    />
                  </svg>
                </div>
                <div class="ml-3">
                  <h3
                    class="text-sm font-medium text-yellow-800 dark:text-yellow-200"
                  >
                    Renewal Reminder
                  </h3>
                  <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                    This certification expires in {{ daysUntilExpiry }} days.
                    Consider renewing it soon.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Description -->
      <div
        v-if="certification.description"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          Description
        </h2>
        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
          {{ certification.description }}
        </p>
      </div>

      <!-- Related Skills -->
      <div
        v-if="certification.skills && certification.skills.length > 0"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          Related Skills
        </h2>
        <div class="flex flex-wrap gap-2">
          <span
            v-for="skill in certification.skills"
            :key="skill"
            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200"
          >
            {{ skill }}
          </span>
        </div>
      </div>

      <!-- Actions -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          Quick Actions
        </h2>
        <div class="flex flex-wrap gap-3">
          <Link
            :href="`/admin/certifications/${certification.id}/edit`"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
              />
            </svg>
            Edit Details
          </Link>

          <a
            v-if="certification.credential_url"
            :href="certification.credential_url"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium rounded-lg transition-colors duration-200"
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
              />
            </svg>
            View Credential
          </a>

          <Link
            href="/admin/certifications"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium rounded-lg transition-colors duration-200"
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
                d="M10 19l-7-7m0 0l7-7m-7 7h18"
              />
            </svg>
            Back to List
          </Link>

          <button
            @click="deleteCertification"
            class="inline-flex items-center px-4 py-2 border border-red-300 hover:border-red-400 text-red-700 hover:text-red-800 text-sm font-medium rounded-lg transition-colors duration-200"
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
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
              />
            </svg>
            Delete Certification
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
