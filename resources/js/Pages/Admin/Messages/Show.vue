<!-- resources/js/Pages/Admin/Messages/Show.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Message Details</h1>
          <p class="text-gray-400">Contact form submission details</p>
        </div>
        <div class="flex space-x-3">
          <Link
            :href="route('admin.messages.index')"
            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors"
          >
            Back to Messages
          </Link>
          <button
            v-if="message.status === 'new'"
            @click="markAsRead"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors"
          >
            Mark as Read
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Message Content -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Message Header -->
          <div class="bg-gray-800 rounded-lg p-6">
            <div class="flex items-start justify-between mb-4">
              <div class="flex-1">
                <h2 class="text-xl font-semibold text-white mb-2">
                  {{ message.subject }}
                </h2>
                <div class="flex items-center space-x-4 text-sm text-gray-400">
                  <span>From: {{ message.name }}</span>
                  <span>•</span>
                  <span>{{ formatDateTime(message.created_at) }}</span>
                </div>
              </div>
              <span
                :class="[
                  'inline-flex px-3 py-1 text-sm font-semibold rounded-full',
                  getStatusColor(message.status),
                ]"
              >
                {{ getStatusLabel(message.status) }}
              </span>
            </div>

            <!-- Contact Info -->
            <div class="bg-gray-700 rounded-lg p-4 mb-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="text-xs text-gray-400 uppercase tracking-wide"
                    >Name</label
                  >
                  <p class="text-white font-medium">{{ message.name }}</p>
                </div>
                <div>
                  <label class="text-xs text-gray-400 uppercase tracking-wide"
                    >Email</label
                  >
                  <p class="text-white">
                    <a
                      :href="`mailto:${message.email}`"
                      class="text-blue-400 hover:text-blue-300"
                    >
                      {{ message.email }}
                    </a>
                  </p>
                </div>
              </div>
            </div>

            <!-- Message Content -->
            <div>
              <label
                class="text-xs text-gray-400 uppercase tracking-wide mb-2 block"
                >Message</label
              >
              <div class="bg-gray-700 rounded-lg p-4">
                <p class="text-white whitespace-pre-wrap leading-relaxed">
                  {{ message.message }}
                </p>
              </div>
            </div>
          </div>

          <!-- Reply Section -->
          <div class="bg-gray-800 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">
              Reply to Message
            </h3>

            <form @submit.prevent="sendReply" class="space-y-4">
              <FormTextarea
                v-model="replyForm.message"
                label="Your Reply"
                placeholder="Type your reply message..."
                :rows="6"
                required
                :error="replyForm.errors.message"
              />

              <div class="flex items-center space-x-4">
                <button
                  type="submit"
                  :disabled="replyForm.processing"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors disabled:opacity-50"
                >
                  {{ replyForm.processing ? "Sending..." : "Send Reply" }}
                </button>

                <label class="flex items-center text-sm text-gray-400">
                  <input
                    type="checkbox"
                    v-model="replyForm.mark_as_replied"
                    class="mr-2 rounded border-gray-600 bg-gray-700 text-blue-600 focus:ring-blue-500"
                  />
                  Mark as replied after sending
                </label>
              </div>
            </form>
          </div>

          <!-- Previous Replies -->
          <div
            v-if="message.replies && message.replies.length > 0"
            class="bg-gray-800 rounded-lg p-6"
          >
            <h3 class="text-lg font-semibold text-white mb-4">
              Previous Replies
            </h3>

            <div class="space-y-4">
              <div
                v-for="reply in message.replies"
                :key="reply.id"
                class="bg-gray-700 rounded-lg p-4"
              >
                <div class="flex justify-between items-center mb-2">
                  <span class="text-blue-400 font-medium">You replied</span>
                  <span class="text-gray-400 text-sm">{{
                    formatDateTime(reply.created_at)
                  }}</span>
                </div>
                <p class="text-white whitespace-pre-wrap">
                  {{ reply.message }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Message Info -->
          <div class="bg-gray-800 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">
              Message Information
            </h3>

            <div class="space-y-3">
              <div>
                <label class="text-xs text-gray-400 uppercase tracking-wide"
                  >Status</label
                >
                <p class="text-white">{{ getStatusLabel(message.status) }}</p>
              </div>

              <div>
                <label class="text-xs text-gray-400 uppercase tracking-wide"
                  >Submitted</label
                >
                <p class="text-white">
                  {{ formatDateTime(message.created_at) }}
                </p>
              </div>

              <div v-if="message.replied_at">
                <label class="text-xs text-gray-400 uppercase tracking-wide"
                  >Last Reply</label
                >
                <p class="text-white">
                  {{ formatDateTime(message.replied_at) }}
                </p>
              </div>

              <div v-if="message.ip_address">
                <label class="text-xs text-gray-400 uppercase tracking-wide"
                  >IP Address</label
                >
                <p class="text-white font-mono text-sm">
                  {{ message.ip_address }}
                </p>
              </div>

              <div v-if="message.user_agent">
                <label class="text-xs text-gray-400 uppercase tracking-wide"
                  >User Agent</label
                >
                <p class="text-gray-300 text-sm break-all">
                  {{ message.user_agent }}
                </p>
              </div>

              <div v-if="message.referrer">
                <label class="text-xs text-gray-400 uppercase tracking-wide"
                  >Referrer</label
                >
                <p class="text-gray-300 text-sm break-all">
                  {{ message.referrer }}
                </p>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="bg-gray-800 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>

            <div class="space-y-3">
              <button
                v-if="message.status === 'new'"
                @click="markAsRead"
                class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
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
                Mark as Read
              </button>

              <a
                :href="`mailto:${message.email}?subject=Re: ${message.subject}&body=Hi ${message.name},%0A%0A`"
                class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
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
                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                </svg>
                Reply via Email
              </a>

              <button
                @click="confirmDelete"
                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
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
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"
                  />
                </svg>
                Delete Message
              </button>
            </div>
          </div>

          <!-- Message Stats -->
          <div v-if="messageStats" class="bg-gray-800 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Statistics</h3>

            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-400">Response Time</span>
                <span class="text-white">{{
                  messageStats.response_time || "N/A"
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-400">Total Replies</span>
                <span class="text-white">{{
                  messageStats.reply_count || 0
                }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <ConfirmDialog
        :show="showDeleteModal"
        title="Delete Message"
        :message="`Are you sure you want to delete this message from ${message.name}? This action cannot be undone.`"
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
import FormTextarea from "@/Components/Form/FormTextarea.vue";
import ConfirmDialog from "@/Components/UI/ConfirmDialog.vue";

const props = defineProps({
  message: Object,
  messageStats: Object,
});

const showDeleteModal = ref(false);

const replyForm = useForm({
  message: "",
  mark_as_replied: true,
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
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const markAsRead = () => {
  router.patch(
    route("admin.messages.mark-read", props.message.id),
    {},
    {
      preserveState: true,
    }
  );
};

const sendReply = () => {
  replyForm.post(route("admin.messages.reply", props.message.id), {
    onSuccess: () => {
      replyForm.reset();
    },
  });
};

const confirmDelete = () => {
  showDeleteModal.value = true;
};

const deleteMessage = () => {
  router.delete(route("admin.messages.destroy", props.message.id), {
    onSuccess: () => {
      router.visit(route("admin.messages.index"));
    },
  });
};
</script>
