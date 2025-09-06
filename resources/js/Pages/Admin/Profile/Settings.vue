<script setup>
import { ref, reactive } from "vue";
import { Head } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import axios from "axios";

// Props
const props = defineProps({
  profile: {
    type: Object,
    required: true,
  },
  status: {
    type: String,
    default: null,
  },
});

// Form state using reactive instead of useForm
const form = reactive({
  name: props.profile.name || "",
  title: props.profile.title || "",
  summary: props.profile.summary || "",
  email: props.profile.email || "",
  phone: props.profile.phone || "",
  location: props.profile.location || "Pekanbaru, Riau",
  thesis_title: props.profile.thesis_title || "",
  photo: null,
  processing: false,
  errors: {},
});

// Methods
const updateProfile = async () => {
  try {
    const formData = new FormData();

    // Append all form data
    formData.append("name", form.name);
    formData.append("title", form.title);
    formData.append("summary", form.summary);
    formData.append("email", form.email);
    formData.append("phone", form.phone || "");
    formData.append("location", form.location);
    formData.append("thesis_title", form.thesis_title || "");

    if (form.photo) {
      formData.append("photo", form.photo);
    }

    form.processing = true;
    form.errors = {};

    const response = await axios.post("/admin/profile-settings", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        "X-CSRF-TOKEN": document
          .querySelector('meta[name="csrf-token"]')
          .getAttribute("content"),
      },
    });

    if (response.data.success) {
      alert("Profile updated successfully!");
      window.location.reload();
    } else {
      alert("Update failed: " + response.data.message);
    }
  } catch (error) {
    console.error("Update failed:", error);

    if (error.response?.status === 422 && error.response?.data?.errors) {
      form.errors = error.response.data.errors;
    } else if (error.response?.data?.message) {
      alert("Error: " + error.response.data.message);
    } else {
      alert("An unexpected error occurred");
    }
  } finally {
    form.processing = false;
  }
};
</script>

<template>
  <Head title="Profile Settings" />

  <DashboardLayout>
    <template #header>
      <h2
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
      >
        Profile Settings
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
        <!-- Success Message -->
        <div
          v-if="status === 'profile-updated'"
          class="mb-6 rounded-md bg-green-50 p-4 dark:bg-green-900/50"
        >
          <div class="flex">
            <div class="flex-shrink-0">
              <svg
                class="h-5 w-5 text-green-400"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                  clip-rule="evenodd"
                />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800 dark:text-green-200">
                Profile updated successfully.
              </p>
            </div>
          </div>
        </div>

        <!-- Profile Settings Form -->
        <div class="bg-white shadow sm:rounded-lg dark:bg-gray-800">
          <div class="px-4 py-5 sm:p-6">
            <h3
              class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100"
            >
              Portfolio Profile Information
            </h3>

            <form @submit.prevent="updateProfile" class="mt-5 space-y-6">
              <!-- Profile Photo -->
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                >
                  Profile Photo
                </label>
                <div class="flex items-center space-x-4">
                  <!-- Current Photo -->
                  <div class="flex-shrink-0">
                    <img
                      :src="
                        props.profile.photo
                          ? `/storage/${props.profile.photo}`
                          : '/images/default-avatar.png'
                      "
                      alt="Current Profile Photo"
                      class="h-20 w-20 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600"
                    />
                  </div>
                  <!-- File Input -->
                  <div class="flex-1">
                    <input
                      type="file"
                      id="photo"
                      @change="form.photo = $event.target.files[0]"
                      accept="image/*"
                      class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-200"
                    />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                      JPG, PNG, GIF up to 2MB
                    </p>
                    <div
                      v-if="form.errors.photo"
                      class="mt-1 text-sm text-red-600"
                    >
                      {{ form.errors.photo[0] }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Basic Information -->
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Name -->
                <div>
                  <label
                    for="name"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    Full Name
                  </label>
                  <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm"
                  />
                  <div
                    v-if="form.errors.name"
                    class="mt-1 text-sm text-red-600"
                  >
                    {{ form.errors.name[0] }}
                  </div>
                </div>

                <!-- Title -->
                <div>
                  <label
                    for="title"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    Professional Title
                  </label>
                  <input
                    id="title"
                    v-model="form.title"
                    type="text"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm"
                    placeholder="e.g., Full Stack Developer"
                  />
                  <div
                    v-if="form.errors.title"
                    class="mt-1 text-sm text-red-600"
                  >
                    {{ form.errors.title[0] }}
                  </div>
                </div>

                <!-- Email -->
                <div>
                  <label
                    for="email"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    Email Address
                  </label>
                  <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm"
                  />
                  <div
                    v-if="form.errors.email"
                    class="mt-1 text-sm text-red-600"
                  >
                    {{ form.errors.email[0] }}
                  </div>
                </div>

                <!-- Phone -->
                <div>
                  <label
                    for="phone"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    Phone Number
                  </label>
                  <input
                    id="phone"
                    v-model="form.phone"
                    type="tel"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm"
                    placeholder="+62 xxx xxxx xxxx"
                  />
                  <div
                    v-if="form.errors.phone"
                    class="mt-1 text-sm text-red-600"
                  >
                    {{ form.errors.phone[0] }}
                  </div>
                </div>

                <!-- Location -->
                <div class="sm:col-span-2">
                  <label
                    for="location"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    Location
                  </label>
                  <input
                    id="location"
                    v-model="form.location"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm"
                    placeholder="City, Province/State"
                  />
                  <div
                    v-if="form.errors.location"
                    class="mt-1 text-sm text-red-600"
                  >
                    {{ form.errors.location[0] }}
                  </div>
                </div>
              </div>

              <!-- Summary -->
              <div>
                <label
                  for="summary"
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                  Professional Summary
                </label>
                <textarea
                  id="summary"
                  v-model="form.summary"
                  rows="4"
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm"
                  placeholder="Brief description about yourself..."
                ></textarea>
                <div
                  v-if="form.errors.summary"
                  class="mt-1 text-sm text-red-600"
                >
                  {{ form.errors.summary[0] }}
                </div>
              </div>

              <!-- Thesis Title -->
              <div>
                <label
                  for="thesis_title"
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                  Thesis Title (Optional)
                </label>
                <input
                  id="thesis_title"
                  v-model="form.thesis_title"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm"
                  placeholder="Your thesis or final project title"
                />
                <div
                  v-if="form.errors.thesis_title"
                  class="mt-1 text-sm text-red-600"
                >
                  {{ form.errors.thesis_title[0] }}
                </div>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end">
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                >
                  {{ form.processing ? "Updating..." : "Update Profile" }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
