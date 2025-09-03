<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  >
    <div
      class="gothic-card max-w-2xl w-full mx-4 rounded-lg p-6 max-h-[90vh] overflow-y-auto"
    >
      <div class="flex justify-between items-center mb-6">
        <h2 class="gothic-title text-2xl text-red-400">
          {{ isEdit ? "Edit Project" : "Add New Project" }}
        </h2>
        <button @click="$emit('close')" class="text-red-400 hover:text-red-300">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label class="block text-gray-300 text-sm font-bold mb-2">
            Project Title <span class="text-red-400">*</span>
          </label>
          <input
            v-model="form.title"
            type="text"
            required
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
            placeholder="e.g. Portfolio Website"
          />
        </div>

        <div class="mb-4">
          <label class="block text-gray-300 text-sm font-bold mb-2">
            Description <span class="text-red-400">*</span>
          </label>
          <textarea
            v-model="form.description"
            required
            rows="4"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
            placeholder="Detailed description of the project..."
          ></textarea>
        </div>

        <div class="mb-4">
          <label class="block text-gray-300 text-sm font-bold mb-2">
            Technologies <span class="text-red-400">*</span>
          </label>
          <div class="flex flex-wrap gap-2 mb-2">
            <span
              v-for="(tech, index) in form.technologies"
              :key="index"
              class="px-3 py-1 bg-red-600 text-white rounded-full text-sm flex items-center"
            >
              {{ tech }}
              <button
                type="button"
                @click="removeTechnology(index)"
                class="ml-2 text-red-200 hover:text-white"
              >
                <i class="fas fa-times text-xs"></i>
              </button>
            </span>
          </div>
          <div class="flex">
            <input
              v-model="newTech"
              type="text"
              class="flex-1 px-3 py-2 bg-gray-800 border border-gray-600 rounded-l-lg text-white focus:outline-none focus:border-red-400"
              placeholder="e.g. Laravel, Vue.js, MySQL"
              @keyup.enter="addTechnology"
            />
            <button
              type="button"
              @click="addTechnology"
              class="px-4 py-2 bg-red-600 text-white rounded-r-lg hover:bg-red-700"
            >
              Add
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-gray-300 text-sm font-bold mb-2">
              GitHub URL
            </label>
            <input
              v-model="form.github_url"
              type="url"
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
              placeholder="https://github.com/username/project"
            />
          </div>
          <div>
            <label class="block text-gray-300 text-sm font-bold mb-2">
              Demo URL
            </label>
            <input
              v-model="form.demo_url"
              type="url"
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
              placeholder="https://demo.example.com"
            />
          </div>
        </div>

        <div class="mb-6">
          <label class="flex items-center text-gray-300">
            <input
              v-model="form.featured"
              type="checkbox"
              class="mr-2 rounded focus:ring-red-400 focus:ring-2"
            />
            Featured Project
          </label>
          <small class="text-gray-400"
            >Featured projects will be displayed prominently</small
          >
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
            :disabled="loading || form.technologies.length === 0"
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
  name: "ProjectModal",
  props: {
    show: Boolean,
    project: Object,
  },
  emits: ["close", "save"],
  setup(props, { emit }) {
    const loading = ref(false);
    const newTech = ref("");
    const isEdit = computed(() => !!props.project?.id);

    const form = ref({
      title: "",
      description: "",
      technologies: [],
      github_url: "",
      demo_url: "",
      featured: false,
    });

    // Watch for project prop changes
    watch(
      () => props.project,
      (newProject) => {
        if (newProject) {
          form.value = {
            title: newProject.title || "",
            description: newProject.description || "",
            technologies: Array.isArray(newProject.technologies)
              ? [...newProject.technologies]
              : [],
            github_url: newProject.github_url || "",
            demo_url: newProject.demo_url || "",
            featured: newProject.featured || false,
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
        description: "",
        technologies: [],
        github_url: "",
        demo_url: "",
        featured: false,
      };
      newTech.value = "";
    };

    const addTechnology = () => {
      const tech = newTech.value.trim();
      if (tech && !form.value.technologies.includes(tech)) {
        form.value.technologies.push(tech);
        newTech.value = "";
      }
    };

    const removeTechnology = (index) => {
      form.value.technologies.splice(index, 1);
    };

    const handleSubmit = async () => {
      if (form.value.technologies.length === 0) {
        alert("Please add at least one technology");
        return;
      }

      loading.value = true;
      try {
        await emit("save", {
          ...form.value,
          id: props.project?.id,
        });
        resetForm();
      } catch (error) {
        console.error("Error saving project:", error);
      } finally {
        loading.value = false;
      }
    };

    return {
      form,
      loading,
      isEdit,
      newTech,
      addTechnology,
      removeTechnology,
      handleSubmit,
    };
  },
};
</script>
