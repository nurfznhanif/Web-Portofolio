<!-- resources/js/Pages/Admin/Certification/Index.vue -->
<script setup>
import { ref, computed } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  certifications: {
    type: Array,
    required: true,
  },
});

// Page data
const page = usePage();
const flash = computed(() => page.props.flash || {});

// Reactive state
const selectedCertifications = ref([]);
const sortBy = ref("issue_date");
const sortOrder = ref("desc");
const searchQuery = ref("");
const filterStatus = ref("all"); // all, valid, expired, expiring_soon

// Computed
const filteredCertifications = computed(() => {
  let filtered = props.certifications;

  // Search filter
  if (searchQuery.value) {
    filtered = filtered.filter(
      (cert) =>
        cert.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        cert.issuer.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        (cert.description &&
          cert.description
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase()))
    );
  }

  // Status filter
  if (filterStatus.value === "valid") {
    filtered = filtered.filter((cert) => isCertificationValid(cert));
  } else if (filterStatus.value === "expired") {
    filtered = filtered.filter(
      (cert) => !isCertificationValid(cert) && cert.expiry_date
    );
  } else if (filterStatus.value === "expiring_soon") {
    filtered = filtered.filter((cert) => isExpiringSoon(cert));
  }

  // Sort
  filtered.sort((a, b) => {
    let aVal = a[sortBy.value];
    let bVal = b[sortBy.value];

    if (sortBy.value === "issue_date" || sortBy.value === "expiry_date") {
      aVal = new Date(aVal || 0);
      bVal = new Date(bVal || 0);
    }

    if (sortOrder.value === "asc") {
      return aVal < bVal ? -1 : aVal > bVal ? 1 : 0;
    } else {
      return aVal > bVal ? -1 : aVal < bVal ? 1 : 0;
    }
  });

  return filtered;
});

const certificationStats = computed(() => ({
  total: props.certifications.length,
  valid: props.certifications.filter((cert) => isCertificationValid(cert))
    .length,
  expired: props.certifications.filter(
    (cert) => !isCertificationValid(cert) && cert.expiry_date
  ).length,
  expiring_soon: props.certifications.filter((cert) => isExpiringSoon(cert))
    .length,
  no_expiry: props.certifications.filter((cert) => !cert.expiry_date).length,
}));

// Methods
const isCertificationValid = (cert) => {
  if (!cert.expiry_date) return true; // No expiry date means valid
  return new Date(cert.expiry_date) > new Date();
};

const isExpiringSoon = (cert) => {
  if (!cert.expiry_date) return false;
  const expiryDate = new Date(cert.expiry_date);
  const thirtyDaysFromNow = new Date();
  thirtyDaysFromNow.setDate(thirtyDaysFromNow.getDate() + 30);
  return expiryDate <= thirtyDaysFromNow && expiryDate > new Date();
};

const getStatusBadge = (cert) => {
  if (!cert.expiry_date) {
    return {
      text: "No Expiry",
      class: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
    };
  }

  if (!isCertificationValid(cert)) {
    return {
      text: "Expired",
      class: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300",
    };
  }

  if (isExpiringSoon(cert)) {
    return {
      text: "Expiring Soon",
      class:
        "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300",
    };
  }

  return {
    text: "Valid",
    class: "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300",
  };
};

const deleteCertification = (certificationId) => {
  if (confirm("Are you sure you want to delete this certification?")) {
    router.delete(`/admin/certifications/${certificationId}`, {
      onSuccess: () => {
        selectedCertifications.value = selectedCertifications.value.filter(
          (id) => id !== certificationId
        );
      },
    });
  }
};

const bulkDelete = () => {
  if (selectedCertifications.value.length === 0) return;

  if (
    confirm(
      `Are you sure you want to delete ${selectedCertifications.value.length} certification(s)?`
    )
  ) {
    router.delete("/admin/certifications/bulk-delete", {
      data: { ids: selectedCertifications.value },
      onSuccess: () => {
        selectedCertifications.value = [];
      },
    });
  }
};

const formatDate = (date) => {
  if (!date) return "No expiry";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const getDaysUntilExpiry = (date) => {
  if (!date) return null;
  const expiryDate = new Date(date);
  const now = new Date();
  const diffTime = expiryDate - now;
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return diffDays;
};

const toggleSelectAll = () => {
  if (
    selectedCertifications.value.length === filteredCertifications.value.length
  ) {
    selectedCertifications.value = [];
  } else {
    selectedCertifications.value = filteredCertifications.value.map(
      (c) => c.id
    );
  }
};

const sortTable = (column) => {
  if (sortBy.value === column) {
    sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
  } else {
    sortBy.value = column;
    sortOrder.value = "asc";
  }
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Certifications", href: "/admin/certifications", current: true },
];
</script>

<template>
  <Head title="Certifications Management" />

  <DashboardLayout
    :title="'Certifications Management'"
    :breadcrumbs="breadcrumbs"
  >
    <div class="space-y-6">
      <!-- Success Flash Message -->
      <div
        v-if="flash.success"
        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4"
      >
        <div class="flex">
          <div class="flex-shrink-0">
            <svg
              class="h-5 w-5 text-green-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-green-800 dark:text-green-200">
              {{ flash.success }}
            </p>
          </div>
        </div>
      </div>

      <!-- Header Section -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
        >
          <div>
            <h1
              class="text-2xl font-bold text-gray-900 dark:text-white flex items-center"
            >
              <svg
                class="w-8 h-8 mr-3 text-blue-600"
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
              Certifications Management
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Manage your professional certifications and credentials
            </p>
          </div>

          <Link
            href="/admin/certifications/create"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
          >
            <svg
              class="w-5 h-5 mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
              />
            </svg>
            Add Certification
          </Link>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-blue-600 dark:text-blue-400"
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
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Total Certifications
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ certificationStats.total }}
              </p>
            </div>
          </div>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-green-600 dark:text-green-400"
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
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Valid
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ certificationStats.valid }}
              </p>
            </div>
          </div>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-yellow-600 dark:text-yellow-400"
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
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Expiring Soon
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ certificationStats.expiring_soon }}
              </p>
            </div>
          </div>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-red-600 dark:text-red-400"
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
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Expired
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ certificationStats.expired }}
              </p>
            </div>
          </div>
        </div>

        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-gray-600 dark:text-gray-400"
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
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                No Expiry
              </p>
              <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ certificationStats.no_expiry }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters and Search -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
        >
          <div
            class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4"
          >
            <!-- Search -->
            <div class="relative">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <svg
                  class="h-5 w-5 text-gray-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  />
                </svg>
              </div>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search certifications..."
                class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
            </div>

            <!-- Status Filter -->
            <select
              v-model="filterStatus"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="all">All Status</option>
              <option value="valid">Valid</option>
              <option value="expiring_soon">Expiring Soon</option>
              <option value="expired">Expired</option>
            </select>
          </div>

          <!-- Bulk Actions -->
          <div
            v-if="selectedCertifications.length > 0"
            class="flex items-center space-x-4"
          >
            <span class="text-sm text-gray-600 dark:text-gray-400">
              {{ selectedCertifications.length }} selected
            </span>
            <button
              @click="bulkDelete"
              class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
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
              Delete Selected
            </button>
          </div>
        </div>
      </div>

      <!-- Certifications Table -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
      >
        <div class="overflow-x-auto">
          <table
            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
          >
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-6 py-3 text-left">
                  <input
                    type="checkbox"
                    :checked="
                      selectedCertifications.length ===
                        filteredCertifications.length &&
                      filteredCertifications.length > 0
                    "
                    @change="toggleSelectAll"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                  />
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                  @click="sortTable('name')"
                >
                  <div class="flex items-center space-x-1">
                    <span>Name</span>
                    <svg
                      v-if="sortBy === 'name'"
                      class="w-4 h-4"
                      :class="sortOrder === 'asc' ? 'transform rotate-180' : ''"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                  @click="sortTable('issuer')"
                >
                  <div class="flex items-center space-x-1">
                    <span>Issuer</span>
                    <svg
                      v-if="sortBy === 'issuer'"
                      class="w-4 h-4"
                      :class="sortOrder === 'asc' ? 'transform rotate-180' : ''"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                  @click="sortTable('issue_date')"
                >
                  <div class="flex items-center space-x-1">
                    <span>Issue Date</span>
                    <svg
                      v-if="sortBy === 'issue_date'"
                      class="w-4 h-4"
                      :class="sortOrder === 'asc' ? 'transform rotate-180' : ''"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                  @click="sortTable('expiry_date')"
                >
                  <div class="flex items-center space-x-1">
                    <span>Expiry Date</span>
                    <svg
                      v-if="sortBy === 'expiry_date'"
                      class="w-4 h-4"
                      :class="sortOrder === 'asc' ? 'transform rotate-180' : ''"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Status
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody
              class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
            >
              <tr
                v-for="certification in filteredCertifications"
                :key="certification.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <input
                    v-model="selectedCertifications"
                    :value="certification.id"
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                  />
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div
                        class="h-10 w-10 rounded-lg bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center"
                      >
                        <svg
                          class="h-6 w-6 text-indigo-600 dark:text-indigo-400"
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
                    <div class="ml-4">
                      <div
                        class="text-sm font-medium text-gray-900 dark:text-white"
                      >
                        {{ certification.name }}
                      </div>
                      <div
                        v-if="certification.description"
                        class="text-sm text-gray-500 dark:text-gray-400"
                      >
                        {{ certification.description.substring(0, 60)
                        }}{{
                          certification.description.length > 60 ? "..." : ""
                        }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white">
                    {{ certification.issuer }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white">
                    {{ formatDate(certification.issue_date) }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white">
                    {{ formatDate(certification.expiry_date) }}
                    <div
                      v-if="
                        certification.expiry_date &&
                        getDaysUntilExpiry(certification.expiry_date) !== null
                      "
                      class="text-xs text-gray-500 dark:text-gray-400"
                    >
                      {{
                        getDaysUntilExpiry(certification.expiry_date) > 0
                          ? `${getDaysUntilExpiry(
                              certification.expiry_date
                            )} days left`
                          : `Expired ${Math.abs(
                              getDaysUntilExpiry(certification.expiry_date)
                            )} days ago`
                      }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="getStatusBadge(certification).class"
                  >
                    {{ getStatusBadge(certification).text }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center space-x-3">
                    <Link
                      :href="`/admin/certifications/${certification.id}`"
                      class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                      title="View Details"
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
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        />
                      </svg>
                    </Link>
                    <Link
                      :href="`/admin/certifications/${certification.id}/edit`"
                      class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
                      title="Edit"
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
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                        />
                      </svg>
                    </Link>
                    <a
                      v-if="certification.credential_url"
                      :href="certification.credential_url"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300"
                      title="View Credential"
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
                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                        />
                      </svg>
                    </a>
                    <button
                      @click="deleteCertification(certification.id)"
                      class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                      title="Delete"
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
                        />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div
          v-if="filteredCertifications.length === 0"
          class="text-center py-12"
        >
          <svg
            class="mx-auto h-12 w-12 text-gray-400"
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
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
            No certifications found
          </h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{
              searchQuery
                ? "Try adjusting your search criteria."
                : "Get started by adding your first certification."
            }}
          </p>
          <div v-if="!searchQuery" class="mt-6">
            <Link
              href="/admin/certifications/create"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
            >
              <svg
                class="w-5 h-5 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
              </svg>
              Add Your First Certification
            </Link>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
