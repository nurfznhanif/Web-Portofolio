<script setup>
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

// Props
const props = defineProps({
  contacts: {
    type: Object,
    default: () => ({ data: [], links: [], meta: {} }),
  },
});

// Reactive state
const searchQuery = ref("");
const selectedContact = ref(null);
const showContactModal = ref(false);

// Computed properties
const filteredContacts = computed(() => {
  let filtered = [...props.contacts.data];

  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter((contact) => {
      const data = contact.data || {};
      return (
        (data.name && data.name.toLowerCase().includes(query)) ||
        (data.email && data.email.toLowerCase().includes(query)) ||
        (data.subject && data.subject.toLowerCase().includes(query)) ||
        contact.ip_address.toLowerCase().includes(query)
      );
    });
  }

  return filtered;
});

const contactStats = computed(() => {
  const contacts = props.contacts.data;
  const withSubject = contacts.filter((c) => c.data?.has_subject).length;
  const uniqueEmails = new Set(
    contacts.map((c) => c.data?.email).filter(Boolean)
  ).size;
  const recentContacts = contacts.filter((c) => {
    const contactDate = new Date(c.created_at);
    const weekAgo = new Date();
    weekAgo.setDate(weekAgo.getDate() - 7);
    return contactDate >= weekAgo;
  }).length;

  return {
    total: contacts.length,
    withSubject,
    uniqueEmails,
    recentContacts,
    subjectRate:
      contacts.length > 0
        ? ((withSubject / contacts.length) * 100).toFixed(1)
        : 0,
  };
});

// Methods
const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const getTimeAgo = (date) => {
  const now = new Date();
  const contactDate = new Date(date);
  const diffInHours = Math.floor((now - contactDate) / (1000 * 60 * 60));

  if (diffInHours < 1) {
    return "Just now";
  } else if (diffInHours < 24) {
    return `${diffInHours} hour${diffInHours > 1 ? "s" : ""} ago`;
  } else {
    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 7) {
      return `${diffInDays} day${diffInDays > 1 ? "s" : ""} ago`;
    } else {
      const diffInWeeks = Math.floor(diffInDays / 7);
      return `${diffInWeeks} week${diffInWeeks > 1 ? "s" : ""} ago`;
    }
  }
};

const showContactDetails = (contact) => {
  selectedContact.value = contact;
  showContactModal.value = true;
};

const closeContactModal = () => {
  showContactModal.value = false;
  selectedContact.value = null;
};

const getContactInitials = (name) => {
  if (!name) return "?";
  return name
    .split(" ")
    .map((word) => word.charAt(0))
    .join("")
    .toUpperCase()
    .slice(0, 2);
};

const getContactBgColor = (index) => {
  const colors = [
    "bg-blue-500",
    "bg-green-500",
    "bg-purple-500",
    "bg-orange-500",
    "bg-red-500",
    "bg-indigo-500",
    "bg-pink-500",
    "bg-yellow-500",
  ];
  return colors[index % colors.length];
};

const copyToClipboard = (text) => {
  navigator.clipboard.writeText(text).then(() => {
    // Could show a toast notification here
  });
};

// Breadcrumbs
const breadcrumbs = [
  { name: "Dashboard", href: "/dashboard" },
  { name: "Analytics", href: "/admin/analytics" },
  {
    name: "Contact Forms",
    href: "/admin/analytics/contact-forms",
    current: true,
  },
];
</script>

<template>
  <Head title="Contact Forms Analytics" />

  <DashboardLayout :title="'Contact Forms'" :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
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
                class="w-8 h-8 mr-3 text-indigo-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                />
              </svg>
              Contact Forms
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Review messages and inquiries from portfolio visitors
            </p>
          </div>

          <Link
            href="/admin/analytics"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
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
            Back to Analytics
          </Link>
        </div>

        <!-- Quick Stats -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
          <div
            class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg
                  class="h-6 w-6 text-blue-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                  Total Contacts
                </p>
                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                  {{ contactStats.total }}
                </p>
              </div>
            </div>
          </div>

          <div
            class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg
                  class="h-6 w-6 text-green-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <p
                  class="text-sm font-medium text-green-600 dark:text-green-400"
                >
                  Unique Contacts
                </p>
                <p
                  class="text-2xl font-bold text-green-900 dark:text-green-100"
                >
                  {{ contactStats.uniqueEmails }}
                </p>
              </div>
            </div>
          </div>

          <div
            class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg
                  class="h-6 w-6 text-purple-600"
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
              <div class="ml-3">
                <p
                  class="text-sm font-medium text-purple-600 dark:text-purple-400"
                >
                  Recent (7 days)
                </p>
                <p
                  class="text-2xl font-bold text-purple-900 dark:text-purple-100"
                >
                  {{ contactStats.recentContacts }}
                </p>
              </div>
            </div>
          </div>

          <div
            class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg
                  class="h-6 w-6 text-orange-600"
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
              <div class="ml-3">
                <p
                  class="text-sm font-medium text-orange-600 dark:text-orange-400"
                >
                  With Subject
                </p>
                <p
                  class="text-2xl font-bold text-orange-900 dark:text-orange-100"
                >
                  {{ contactStats.subjectRate }}%
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Search and Filters -->
      <div
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="flex items-center space-x-4">
          <div class="flex-1 relative">
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
              placeholder="Search by name, email, subject, or IP address..."
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-if="filteredContacts.length === 0"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-12 text-center"
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
            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
          />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
          {{ searchQuery ? "No contacts found" : "No contact submissions yet" }}
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{
            searchQuery
              ? "Try adjusting your search terms."
              : "Contact form submissions will appear here when visitors reach out through your portfolio."
          }}
        </p>
      </div>

      <!-- Contact Cards -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div
          v-for="(contact, index) in filteredContacts"
          :key="contact.id || index"
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200 cursor-pointer"
          @click="showContactDetails(contact)"
        >
          <div class="flex items-start space-x-4">
            <!-- Avatar -->
            <div
              :class="[
                'w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold text-sm',
                getContactBgColor(index),
              ]"
            >
              {{ getContactInitials(contact.data?.name) }}
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between">
                <h3
                  class="text-lg font-semibold text-gray-900 dark:text-white truncate"
                >
                  {{ contact.data?.name || "Anonymous" }}
                </h3>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                  {{ getTimeAgo(contact.created_at) }}
                </span>
              </div>

              <div class="mt-1 flex items-center space-x-2">
                <svg
                  class="w-4 h-4 text-gray-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                </svg>
                <span class="text-sm text-gray-600 dark:text-gray-400 truncate">
                  {{ contact.data?.email || "No email provided" }}
                </span>
              </div>

              <div v-if="contact.data?.subject" class="mt-2">
                <p class="text-sm font-medium text-gray-900 dark:text-white">
                  Subject: {{ contact.data.subject }}
                </p>
              </div>

              <div class="mt-3 flex items-center justify-between">
                <div
                  class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400"
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
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                  </svg>
                  <span>{{ contact.ip_address }}</span>
                </div>
                <div class="flex items-center space-x-2">
                  <span
                    v-if="contact.data?.has_subject"
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400"
                  >
                    With Subject
                  </span>
                  <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ formatDate(contact.created_at) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div
        v-if="contacts.links && contacts.links.length > 3"
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
      >
        <div class="flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <Link
              v-if="contacts.links[0].url"
              :href="contacts.links[0].url"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Previous
            </Link>
            <Link
              v-if="contacts.links[contacts.links.length - 1].url"
              :href="contacts.links[contacts.links.length - 1].url"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Next
            </Link>
          </div>
          <div
            class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
          >
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                Showing
                {{ contacts.meta.from || 0 }}
                to
                {{ contacts.meta.to || 0 }}
                of
                {{ contacts.meta.total || 0 }}
                results
              </p>
            </div>
            <div>
              <nav
                class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
              >
                <Link
                  v-for="(link, index) in contacts.links"
                  :key="index"
                  :href="link.url"
                  v-html="link.label"
                  :class="[
                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors',
                    link.active
                      ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                    index === 0 ? 'rounded-l-md' : '',
                    index === contacts.links.length - 1 ? 'rounded-r-md' : '',
                    !link.url
                      ? 'opacity-50 cursor-not-allowed'
                      : 'cursor-pointer',
                  ]"
                />
              </nav>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Detail Modal -->
      <div
        v-if="showContactModal && selectedContact"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        @click="closeContactModal"
      >
        <div
          class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-800"
          @click.stop
        >
          <div class="mt-3">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                Contact Details
              </h3>
              <button
                @click="closeContactModal"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <svg
                  class="h-6 w-6"
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
            </div>

            <!-- Contact Info -->
            <div class="space-y-4">
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >Name</label
                >
                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                  {{ selectedContact.data?.name || "Not provided" }}
                </p>
              </div>

              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >Email</label
                >
                <div class="mt-1 flex items-center space-x-2">
                  <p class="text-sm text-gray-900 dark:text-white">
                    {{ selectedContact.data?.email || "Not provided" }}
                  </p>
                  <button
                    v-if="selectedContact.data?.email"
                    @click="copyToClipboard(selectedContact.data.email)"
                    class="text-indigo-600 hover:text-indigo-800 text-xs"
                  >
                    Copy
                  </button>
                </div>
              </div>

              <div v-if="selectedContact.data?.subject">
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >Subject</label
                >
                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                  {{ selectedContact.data.subject }}
                </p>
              </div>

              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >Message</label
                >
                <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                  <p
                    class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap"
                  >
                    {{
                      selectedContact.data?.message ||
                      "No message content available"
                    }}
                  </p>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >IP Address</label
                  >
                  <p class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ selectedContact.ip_address }}
                  </p>
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >Submitted</label
                  >
                  <p class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ formatDate(selectedContact.created_at) }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-3">
              <button
                @click="closeContactModal"
                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-md shadow-sm hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors"
              >
                Close
              </button>
              <a
                v-if="selectedContact.data?.email"
                :href="`mailto:${selectedContact.data.email}?subject=Re: ${
                  selectedContact.data?.subject || 'Your message'
                }`"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
              >
                Reply via Email
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
