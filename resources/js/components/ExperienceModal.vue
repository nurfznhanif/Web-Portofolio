<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  >
    <div class="gothic-card max-w-2xl w-full mx-4 rounded-lg p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="gothic-title text-2xl text-red-400">
          {{ isEdit ? "Edit Experience" : "Add New Experience" }}
        </h2>
        <button @click="$emit('close')" class="text-red-400 hover:text-red-300">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <form @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-gray-300 text-sm font-bold mb-2">
              Job Title <span class="text-red-400">*</span>
            </label>
            <input
              v-model="form.title"
              type="text"
              required
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
              placeholder="e.g. Software Developer"
            />
          </div>
          <div>
            <label class="block text-gray-300 text-sm font-bold mb-2">
              Company <span class="text-red-400">*</span>
            </label>
            <input
              v-model="form.company"
              type="text"
              required
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
              placeholder="e.g. Tech Company Inc."
            />
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-gray-300 text-sm font-bold mb-2">
              Start Date <span class="text-red-400">*</span>
            </label>
            <input
              v-model="form.start_date"
              type="date"
              required
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
            />
          </div>
          <div>
            <label class="block text-gray-300 text-sm font-bold mb-2">
              End Date
            </label>
            <input
              v-model="form.end_date"
              type="date"
              :disabled="form.is_current"
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400 disabled:bg-gray-700 disabled:cursor-not-allowed"
            />
          </div>
        </div>

        <div class="mb-4">
          <label class="flex items-center text-gray-300">
            <input
              v-model="form.is_current"
              type="checkbox"
              class="mr-2 rounded focus:ring-red-400 focus:ring-2"
              @change="if (form.is_current) form.end_date = null;"
            />
            Currently working here
          </label>
        </div>

        <div class="mb-6">
          <label class="block text-gray-300 text-sm font-bold mb-2">
            Job Description <span class="text-red-400">*</span>
          </label>
          <textarea
            v-model="form.description"
            required
            rows="4"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
            placeholder="Describe your responsibilities and achievements..."
          ></textarea>
        </div>

        <div class="flex justify-end space-x-4">
          <button
            type="button"
            @click="$emit('close')"
            class="px-6 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            {{ loading ? "Saving..." : isEdit ? "Update" : "Create" }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref, watch, computed } from "vue";

export default {
  name: "ExperienceModal",
  props: {
    show: Boolean,
    experience: Object,
  },
  emits: ["close", "save"],
  setup(props, { emit }) {
    const loading = ref(false);
    const isEdit = computed(() => !!props.experience?.id);

    const form = ref({
      title: "",
      company: "",
      start_date: "",
      end_date: "",
      description: "",
      is_current: false,
    });

    // Watch for experience prop changes
    watch(
      () => props.experience,
      (newExperience) => {
        if (newExperience) {
          form.value = {
            title: newExperience.title || "",
            company: newExperience.company || "",
            start_date: newExperience.start_date || "",
            end_date: newExperience.end_date || "",
            description: newExperience.description || "",
            is_current: newExperience.is_current || false,
          };
        } else {
          resetForm();
        }
      },
      { immediate: true }
    );

    const resetForm = () => {
      form.value = {
        title: "",
        company: "",
        start_date: "",
        end_date: "",
        description: "",
        is_current: false,
      };
    };

    const handleSubmit = async () => {
      loading.value = true;
      try {
        await emit("save", {
          ...form.value,
          id: props.experience?.id,
        });
        resetForm();
      } catch (error) {
        console.error("Error saving experience:", error);
      } finally {
        loading.value = false;
      }
    };

    return {
      form,
      loading,
      isEdit,
      handleSubmit,
    };
  },
};
</script>
