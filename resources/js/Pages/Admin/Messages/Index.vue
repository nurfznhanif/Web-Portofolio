<!-- Admin/Messages/Index.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Contact Messages</h1>
          <p class="text-gray-400">
            Manage incoming messages from your portfolio
          </p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="exportMessages"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center"
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
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
              />
            </svg>
            Export
          </button>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gray-800 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-900 rounded-lg">
              <svg
                class="w-6 h-6 text-blue-400"
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
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">{{ stats.total }}</p>
              <p class="text-gray-400 text-sm">Total Messages</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-yellow-900 rounded-lg">
              <svg
                class="w-6 h-6 text-yellow-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">{{ stats.unread }}</p>
              <p class="text-gray-400 text-sm">Unread</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-900 rounded-lg">
              <svg
                class="w-6 h-6 text-green-400"
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
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">{{ stats.replied }}</p>
              <p class="text-gray-400 text-sm">Replied</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-purple-900 rounded-lg">
              <svg
                class="w-6 h-6 text-purple-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-2xl font-bold text-white">{{ stats.thisWeek }}</p>
              <p class="text-gray-400 text-sm">This Week</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-gray-800 rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <FormInput
            v-model="filters.search"
            placeholder="Search messages..."
            @update:modelValue="handleSearch"
          />

          <FormSelect
            v-model="filters.status"
            placeholder="All Status"
            :options="statusOptions"
            @update:modelValue="handleFilter"
          />

          <FormSelect
            v-model="filters.timeframe"
            :options="timeframeOptions"
            @update:modelValue="handleFilter"
          />

          <FormSelect
            v-model="filters.sort"
            :options="sortOptions"
            @update:modelValue="handleSort"
          />
        </div>
      </div>

      <!-- Messages List -->
      <div class="space-y-4">
        <div
          v-for="message in messages.data"
          :key="message.id"
          class="bg-gray-800 rounded-lg hover:bg-gray-700/50 transition-colors"
        >
          <div class="p-6">
            <div class="flex items-start justify-between">
              <!-- Message Info -->
              <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                  <!-- Status Indicator -->
                  <div
                    class="w-3 h-3 rounded-full"
                    :class="{
                      'bg-yellow-400': message.status === 'new',
                      'bg-blue-400': message.status === 'read',
                      'bg-green-400': message.status === 'replied',
                    }"
                  ></div>

                  <h3 class="text-lg font-semibold text-white">
                    {{ message.name }}
                  </h3>
                  <span class="text-gray-400">{{ message.email }}</span>
                  <span class="text-xs text-gray-500">{{
                    formatDateTime(message.created_at)
                  }}</span>
                </div>

                <div v-if="message.subject" class="mb-2">
                  <span class="text-sm text-gray-400">Subject: </span>
                  <span class="text-white">{{ message.subject }}</span>
                </div>

                <p class="text-gray-300 leading-relaxed">
                  {{ message.message }}
                </p>

                <!-- Reply Preview -->
                <div
                  v-if="message.reply"
                  class="mt-4 p-4 bg-gray-700 rounded-lg border-l-4 border-blue-500"
                >
                  <div class="text-sm text-gray-400 mb-1">Your Reply:</div>
                  <p class="text-gray-300">{{ message.reply }}</p>
                  <div class="text-xs text-gray-500 mt-2">
                    Replied on {{ formatDateTime(message.replied_at) }}
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex items-center space-x-2 ml-6">
                <Link
                  :href="route('admin.messages.show', message.id)"
                  class="text-blue-400 hover:text-blue-300 px-3 py-1 rounded-lg hover:bg-blue-500/10 transition-colors text-sm"
                >
                  View
                </Link>

                <button
                  v-if="message.status === 'new'"
                  @click="markAsRead(message.id)"
                  class="text-yellow-400 hover:text-yellow-300 px-3 py-1 rounded-lg hover:bg-yellow-500/10 transition-colors text-sm"
                >
                  Mark Read
                </button>

                <button
                  v-if="message.status !== 'replied'"
                  @click="quickReply(message)"
                  class="text-green-400 hover:text-green-300 px-3 py-1 rounded-lg hover:bg-green-500/10 transition-colors text-sm"
                >
                  Reply
                </button>

                <button
                  @click="confirmDelete(message)"
                  class="text-red-400 hover:text-red-300 px-3 py-1 rounded-lg hover:bg-red-500/10 transition-colors text-sm"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="messages.data.length === 0" class="text-center py-12">
          <svg
            class="mx-auto h-12 w-12 text-gray-400 mb-4"
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
          <h3 class="text-lg font-medium text-white mb-2">No messages found</h3>
          <p class="text-gray-400">
            Messages from your portfolio contact form will appear here
          </p>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="messages.links" class="mt-6">
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

      <!-- Quick Reply Modal -->
      <div v-if="showReplyModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div
          class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        >
          <div
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="showReplyModal = false"
          ></div>

          <div
            class="inline-block align-bottom bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
          >
            <h3 class="text-lg leading-6 font-medium text-white mb-4">
              Reply to {{ selectedMessage?.name }}
            </h3>

            <form @submit.prevent="sendReply" class="space-y-4">
              <FormTextarea
                v-model="replyForm.message"
                label="Your Reply"
                :rows="6"
                placeholder="Type your reply..."
                required
                :error="replyForm.errors?.message"
              />

              <div class="flex justify-end space-x-3">
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
                  Send Reply
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <ConfirmDialog
        :show="showDeleteModal"
        title="Delete Message"
        :message="`Are you sure you want to delete the message from '${selectedMessage?.name}'? This action cannot be undone.`"
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
import { ref } from "vue";
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

const filters = ref({
  search: props.filters?.search || "",
  status: props.filters?.status || "",
  timeframe: props.filters?.timeframe || "all",
  sort: props.filters?.sort || "latest",
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

const sortOptions = [
  { value: "latest", label: "Latest First" },
  { value: "oldest", label: "Oldest First" },
  { value: "unread", label: "Unread First" },
];

const formatDateTime = (date) => {
  return new Date(date).toLocaleString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
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

const handleSort = () => {
  router.get(route("admin.messages.index"), filters.value, {
    preserveState: true,
  });
};

const markAsRead = (messageId) => {
  router.patch(route("admin.messages.mark-read", messageId));
};

const quickReply = (message) => {
  selectedMessage.value = message;
  showReplyModal.value = true;
  replyForm.reset();
};

const sendReply = () => {
  replyForm.post(route("admin.messages.reply", selectedMessage.value.id), {
    onSuccess: () => {
      showReplyModal.value = false;
      selectedMessage.value = null;
    },
  });
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

const exportMessages = () => {
  window.open(route("admin.messages.export"));
};
</script>
