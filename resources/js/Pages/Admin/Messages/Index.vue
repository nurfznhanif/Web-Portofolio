<!-- resources/js/Pages/Admin/Messages/Index.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Messages Management</h1>
          <p class="text-gray-400">Manage contact form submissions</p>
        </div>

        <!-- Stats Summary -->
        <div class="flex space-x-4 text-sm">
          <div class="bg-blue-900 px-3 py-2 rounded-lg">
            <span class="text-blue-300">Total: </span>
            <span class="text-white font-semibold">{{
              stats?.total || 0
            }}</span>
          </div>
          <div class="bg-yellow-900 px-3 py-2 rounded-lg">
            <span class="text-yellow-300">Unread: </span>
            <span class="text-white font-semibold">{{
              stats?.unread || 0
            }}</span>
          </div>
          <div class="bg-green-900 px-3 py-2 rounded-lg">
            <span class="text-green-300">Replied: </span>
            <span class="text-white font-semibold">{{
              stats?.replied || 0
            }}</span>
          </div>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="bg-gray-800 rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Search -->
          <div class="md:col-span-2">
            <FormInput
              v-model="filters.search"
              placeholder="Search messages..."
              @input="handleSearch"
            >
              <template #prepend>
                <svg
                  class="w-5 h-5 text-gray-400"
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
              </template>
            </FormInput>
          </div>

          <!-- Status Filter -->
          <FormSelect
            v-model="filters.status"
            :options="statusOptions"
            @change="handleFilter"
          />

          <!-- Time Filter -->
          <FormSelect
            v-model="filters.timeframe"
            :options="timeframeOptions"
            @change="handleFilter"
          />
        </div>

        <!-- Bulk Actions -->
        <div
          v-if="selectedMessages.length > 0"
          class="mt-4 flex items-center space-x-4"
        >
          <span class="text-sm text-gray-400"
            >{{ selectedMessages.length }} selected</span
          >
          <button
            @click="bulkMarkAsRead"
            class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700"
          >
            Mark as Read
          </button>
          <button
            @click="bulkDelete"
            class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700"
          >
            Delete Selected
          </button>
        </div>
      </div>

      <!-- Messages List -->
      <div
        v-if="messages.data.length > 0"
        class="bg-gray-800 rounded-lg overflow-hidden"
      >
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-700">
              <tr>
                <th class="px-4 py-3 text-left">
                  <input
                    type="checkbox"
                    @change="toggleSelectAll"
                    :checked="allSelected"
                    class="rounded border-gray-600 bg-gray-700 text-blue-600 focus:ring-blue-500"
                  />
                </th>
                <th
                  class="px-4 py-3 text-left text-sm font-medium text-gray-300"
                >
                  Status
                </th>
                <th
                  class="px-4 py-3 text-left text-sm font-medium text-gray-300"
                >
                  Name
                </th>
                <th
                  class="px-4 py-3 text-left text-sm font-medium text-gray-300"
                >
                  Email
                </th>
                <th
                  class="px-4 py-3 text-left text-sm font-medium text-gray-300"
                >
                  Subject
                </th>
                <th
                  class="px-4 py-3 text-left text-sm font-medium text-gray-300"
                >
                  Date
                </th>
                <th
                  class="px-4 py-3 text-left text-sm font-medium text-gray-300"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
              <tr
                v-for="message in messages.data"
                :key="message.id"
                :class="[
                  'hover:bg-gray-700 transition-colors',
                  message.status === 'new' ? 'bg-blue-900 bg-opacity-20' : '',
                ]"
              >
                <td class="px-4 py-3">
                  <input
                    type="checkbox"
                    :value="message.id"
                    v-model="selectedMessages"
                    class="rounded border-gray-600 bg-gray-700 text-blue-600 focus:ring-blue-500"
                  />
                </td>

                <td class="px-4 py-3">
                  <span
                    :class="[
                      'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      getStatusColor(message.status),
                    ]"
                  >
                    {{ getStatusLabel(message.status) }}
                  </span>
                </td>

                <td class="px-4 py-3">
                  <div class="flex items-center">
                    <div>
                      <div class="text-sm font-medium text-white">
                        {{ message.name }}
                      </div>
                      <div
                        v-if="message.ip_address"
                        class="text-xs text-gray-400"
                      >
                        {{ message.ip_address }}
                      </div>
                    </div>
                  </div>
                </td>

                <td class="px-4 py-3">
                  <a
                    :href="`mailto:${message.email}`"
                    class="text-blue-400 hover:text-blue-300 text-sm"
                  >
                    {{ message.email }}
                  </a>
                </td>

                <td class="px-4 py-3">
                  <div class="text-sm text-white truncate max-w-xs">
                    {{ message.subject }}
                  </div>
                </td>

                <td class="px-4 py-3">
                  <div class="text-sm text-gray-400">
                    {{ formatDateTime(message.created_at) }}
                  </div>
                </td>

                <td class="px-4 py-3">
                  <div class="flex items-center space-x-2">
                    <Link
                      :href="route('admin.messages.show', message.id)"
                      class="text-blue-400 hover:text-blue-300"
                      title="View Details"
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

                    <button
                      v-if="message.status === 'new'"
                      @click="markAsRead(message.id)"
                      class="text-green-400 hover:text-green-300"
                      title="Mark as Read"
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
                          d="M5 13l4 4L19 7"
                        />
                      </svg>
                    </button>

                    <button
                      @click="
                        showReplyModal = true;
                        selectedMessage = message;
                      "
                      class="text-yellow-400 hover:text-yellow-300"
                      title="Reply"
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
                          d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"
                        />
                      </svg>
                    </button>

                    <button
                      @click="confirmDelete(message)"
                      class="text-red-400 hover:text-red-300"
                      title="Delete"
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
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"
                        />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <svg
          class="mx-auto h-24 w-24 text-gray-400"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4m-4 0H8m-4 0h4m0 0V9a1 1 0 011-1h2a1 1 0 011 1v4M7 7h10"
          />
        </svg>
        <h3 class="mt-4 text-lg font-medium text-white">No messages found</h3>
        <p class="mt-2 text-gray-400">
          {{
            hasActiveFilters
              ? "Try adjusting your filters"
              : "No contact form submissions yet."
          }}
        </p>
      </div>

      <!-- Pagination -->
      <div v-if="messages.total > messages.per_page" class="mt-6">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-400">
            Showing {{ messages.from }} to {{ messages.to }} of
            {{ messages.total }} results
          </div>
          <div class="flex space-x-1">
            <Link
              v-for="link in messages.links"
              :key="link.label"
              :href="link.url"
              v-html="link.label"
              class="px-3 py-2 text-sm border border-gray-600 rounded"
              :class="{
                'bg-blue-600 text-white border-blue-600': link.active,
                'text-gray-400 hover:text-white hover:border-gray-500':
                  !link.active && link.url,
                'text-gray-600 cursor-not-allowed': !link.url,
              }"
            />
          </div>
        </div>
      </div>

      <!-- Reply Modal -->
      <div v-if="showReplyModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div
          class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center"
        >
          <div
            class="fixed inset-0 bg-gray-900 bg-opacity-75"
            @click="showReplyModal = false"
          ></div>

          <div
            class="inline-block bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
          >
            <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <h3 class="text-lg leading-6 font-medium text-white mb-4">
                Reply to {{ selectedMessage?.name }}
              </h3>

              <form @submit.prevent="sendReply">
                <FormTextarea
                  v-model="replyForm.message"
                  label="Reply Message"
                  placeholder="Type your reply..."
                  :rows="6"
                  required
                  :error="replyForm.errors.message"
                />

                <div class="mt-6 flex justify-end space-x-4">
                  <button
                    type="button"
                    @click="showReplyModal = false"
                    class="px-4 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    :disabled="replyForm.processing"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                  >
                    {{ replyForm.processing ? "Sending..." : "Send Reply" }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <ConfirmDialog
        :show="showDeleteModal"
        title="Delete Message"
        :message="`Are you sure you want to delete this message from ${selectedMessage?.name}? This action cannot be undone.`"
        confirm-text="Delete"
        cancel-text="Cancel"
        type="danger"
        @confirm="deleteMessage"
        @cancel="showDeleteModal = false"
      />
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import FormInput from "@/Components/Form/FormInput.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";
import FormTextarea from "@/Components/Form/FormTextarea.vue";
import ConfirmDialog from "@/Components/UI/ConfirmDialog.vue";

const props = defineProps({
  messages: Object,
  stats: Object,
  filters: Object,
});

const showDeleteModal = ref(false);
const showReplyModal = ref(false);
const selectedMessage = ref(null);
const selectedMessages = ref([]);

const filters = ref({
  search: props.filters?.search || "",
  status: props.filters?.status || "",
  timeframe: props.filters?.timeframe || "all",
});

const replyForm = useForm({
  message: "",
});

const statusOptions = [
  { value: "", label: "All Status" },
  { value: "new", label: "New" },
  { value: "read", label: "Read" },
  { value: "replied", label: "Replied" },
];

const timeframeOptions = [
  { value: "all", label: "All Time" },
  { value: "today", label: "Today" },
  { value: "week", label: "This Week" },
  { value: "month", label: "This Month" },
];

const hasActiveFilters = computed(() => {
  return (
    filters.value.search ||
    filters.value.status ||
    filters.value.timeframe !== "all"
  );
});

const allSelected = computed(() => {
  return (
    props.messages.data.length > 0 &&
    selectedMessages.value.length === props.messages.data.length
  );
});

const getStatusColor = (status) => {
  const colors = {
    new: "bg-blue-900 text-blue-300",
    read: "bg-gray-900 text-gray-300",
    replied: "bg-green-900 text-green-300",
  };
  return colors[status] || "bg-gray-900 text-gray-300";
};

const getStatusLabel = (status) => {
  const labels = {
    new: "New",
    read: "Read",
    replied: "Replied",
  };
  return labels[status] || "Unknown";
};

const formatDateTime = (date) => {
  return new Date(date).toLocaleString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const toggleSelectAll = () => {
  if (allSelected.value) {
    selectedMessages.value = [];
  } else {
    selectedMessages.value = props.messages.data.map((m) => m.id);
  }
};

const handleSearch = () => {
  router.get(route("admin.messages.index"), filters.value, {
    preserveState: true,
  });
};

const handleFilter = () => {
  router.get(route("admin.messages.index"), filters.value, {
    preserveState: true,
  });
};

const markAsRead = (messageId) => {
  router.patch(
    route("admin.messages.mark-read", messageId),
    {},
    {
      preserveState: true,
    }
  );
};

const bulkMarkAsRead = () => {
  router.post(
    route("admin.messages.bulk-actions"),
    {
      action: "mark-read",
      message_ids: selectedMessages.value,
    },
    {
      preserveState: true,
      onSuccess: () => {
        selectedMessages.value = [];
      },
    }
  );
};

const bulkDelete = () => {
  if (confirm("Are you sure you want to delete the selected messages?")) {
    router.post(
      route("admin.messages.bulk-actions"),
      {
        action: "delete",
        message_ids: selectedMessages.value,
      },
      {
        preserveState: true,
        onSuccess: () => {
          selectedMessages.value = [];
        },
      }
    );
  }
};

const confirmDelete = (message) => {
  selectedMessage.value = message;
  showDeleteModal.value = true;
};

const deleteMessage = () => {
  router.delete(route("admin.messages.destroy", selectedMessage.value.id), {
    onSuccess: () => {
      showDeleteModal.value = false;
      selectedMessage.value = null;
    },
  });
};

const sendReply = () => {
  replyForm.post(route("admin.messages.reply", selectedMessage.value.id), {
    onSuccess: () => {
      showReplyModal.value = false;
      selectedMessage.value = null;
      replyForm.reset();
    },
  });
};
</script>
