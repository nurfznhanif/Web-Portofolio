<template>
  <DashboardLayout title="Edit Experience">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center space-x-4">
        <Link
          :href="route('admin.experience.index')"
          class="text-gray-400 hover:text-white transition-colors"
        >
          ← Back to Experience
        </Link>
        <h1 class="text-2xl font-bold text-white">Edit Experience</h1>
      </div>

      <!-- Form -->
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Position -->
          <div>
            <label
              for="position"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Position/Job Title *
            </label>
            <input
              id="position"
              v-model="form.position"
              type="text"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="e.g., Asisten Dosen, Ketua Divisi"
              required
            />
            <div v-if="errors.position" class="mt-1 text-sm text-red-400">
              {{ errors.position }}
            </div>
          </div>

          <!-- Organization -->
          <div>
            <label
              for="organization"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Organization/Company *
            </label>
            <input
              id="organization"
              v-model="form.organization"
              type="text"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="e.g., Universitas Islam Riau"
              required
            />
            <div v-if="errors.organization" class="mt-1 text-sm text-red-400">
              {{ errors.organization }}
            </div>
          </div>

          <!-- Date Range -->
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label
                for="start_date"
                class="block text-sm font-medium text-gray-300 mb-2"
              >
                Start Date *
              </label>
              <input
                id="start_date"
                v-model="form.start_date"
                type="date"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                required
              />
              <div v-if="errors.start_date" class="mt-1 text-sm text-red-400">
                {{ errors.start_date }}
              </div>
            </div>

            <div>
              <label
                for="end_date"
                class="block text-sm font-medium text-gray-300 mb-2"
              >
                End Date
              </label>
              <input
                id="end_date"
                v-model="form.end_date"
                type="date"
                :disabled="form.is_current"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent disabled:opacity-50"
              />
              <div v-if="errors.end_date" class="mt-1 text-sm text-red-400">
                {{ errors.end_date }}
              </div>
            </div>
          </div>

          <!-- Current Position -->
          <div class="flex items-center">
            <input
              id="is_current"
              v-model="form.is_current"
              type="checkbox"
              class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-600 rounded bg-gray-700"
              @change="handleCurrentChange"
            />
            <label for="is_current" class="ml-2 text-sm text-gray-300">
              This is my current position
            </label>
          </div>

          <!-- Description -->
          <div>
            <label
              for="description"
              class="block text-sm font-medium text-gray-300 mb-2"
            >
              Job Description & Responsibilities *
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="5"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
              placeholder="Describe your responsibilities, achievements, and key activities..."
              required
            ></textarea>
            <div v-if="errors.description" class="mt-1 text-sm text-red-400">
              {{ errors.description }}
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end space-x-4 pt-6">
            <Link
              :href="route('admin.experience.index')"
              class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2 bg-red-900 hover:bg-red-800 text-white rounded-lg transition-colors disabled:opacity-50"
            >
              <span v-if="form.processing">Updating...</span>
              <span v-else>Update Experience</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps({
  experience: Object,
  errors: Object,
});

const form = useForm({
  position: props.experience.position,
  organization: props.experience.organization,
  start_date: props.experience.start_date,
  end_date: props.experience.end_date,
  is_current: props.experience.is_current,
  description: props.experience.description,
});

const handleCurrentChange = () => {
  if (form.is_current) {
    form.end_date = "";
  }
};

const submit = () => {
  form.put(route("admin.experience.update", props.experience.id));
};
</script>
