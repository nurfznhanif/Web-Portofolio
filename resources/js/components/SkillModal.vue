<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  >
    <div class="gothic-card max-w-lg w-full mx-4 rounded-lg p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="gothic-title text-2xl text-red-400">
          {{ isEdit ? "Edit Skill" : "Add New Skill" }}
        </h2>
        <button @click="$emit('close')" class="text-red-400 hover:text-red-300">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label class="block text-gray-300 text-sm font-bold mb-2">
            Category <span class="text-red-400">*</span>
          </label>
          <input
            v-model="form.category"
            type="text"
            required
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
            placeholder="e.g. Web Programming, Data Analysis"
          />
        </div>

        <div class="mb-4">
          <label class="block text-gray-300 text-sm font-bold mb-2">
            Skill Name <span class="text-red-400">*</span>
          </label>
          <input
            v-model="form.name"
            type="text"
            required
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
            placeholder="e.g. Laravel, Python, JavaScript"
          />
        </div>

        <div class="mb-4">
          <label class="block text-gray-300 text-sm font-bold mb-2">
            Proficiency Level <span class="text-red-400">*</span>
          </label>
          <select
            v-model="form.level"
            required
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
          >
            <option value="">Select Level</option>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
            <option value="expert">Expert</option>
          </select>
        </div>

        <div class="mb-6">
          <label class="block text-gray-300 text-sm font-bold mb-2">
            Description <span class="text-red-400">*</span>
          </label>
          <textarea
            v-model="form.description"
            required
            rows="3"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
            placeholder="Brief description of your experience with this skill..."
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
  name: "SkillModal",
  props: {
    show: Boolean,
    skill: Object,
  },
  emits: ["close", "save"],
  setup(props, { emit }) {
    const loading = ref(false);
    const isEdit = computed(() => !!props.skill?.id);

    const form = ref({
      category: "",
      name: "",
      level: "",
      description: "",
    });

    // Watch for skill prop changes
    watch(
      () => props.skill,
      (newSkill) => {
        if (newSkill) {
          form.value = {
            category: newSkill.category || "",
            name: newSkill.name || "",
            level: newSkill.level || "",
            description: newSkill.description || "",
          };
        } else {
          resetForm();
        }
      },
      { immediate: true }
    );

    const resetForm = () => {
      form.value = {
        category: "",
        name: "",
        level: "",
        description: "",
      };
    };

    const handleSubmit = async () => {
      loading.value = true;
      try {
        await emit("save", {
          ...form.value,
          id: props.skill?.id,
        });
        resetForm();
      } catch (error) {
        console.error("Error saving skill:", error);
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
