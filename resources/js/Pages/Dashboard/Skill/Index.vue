<template>
  <DashboardLayout title="Skills Management">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Skills</h1>
        <Link
          :href="route('admin.skill.create')"
          class="px-4 py-2 bg-red-900 hover:bg-red-800 text-white rounded-lg transition-colors"
        >
          Add New Skill
        </Link>
      </div>

      <!-- Skills by Category -->
      <div class="space-y-6">
        <div
          v-for="(categorySkills, category) in groupedSkills"
          :key="category"
          class="bg-gray-800 border border-gray-700 rounded-lg p-6"
        >
          <h3 class="text-lg font-semibold text-red-400 mb-4">
            {{ category }}
          </h3>
          <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
              v-for="skill in categorySkills"
              :key="skill.id"
              class="bg-gray-700 rounded-lg p-4 flex items-center justify-between"
            >
              <div class="flex-1">
                <h4 class="font-medium text-white">{{ skill.name }}</h4>
                <div class="flex items-center mt-2">
                  <div class="w-20 h-2 bg-gray-600 rounded-full mr-3">
                    <div
                      class="h-full bg-gradient-to-r from-red-600 to-red-400 rounded-full"
                      :style="{ width: getSkillWidth(skill.level) }"
                    ></div>
                  </div>
                  <span class="text-xs text-gray-400 capitalize">{{
                    skill.level
                  }}</span>
                </div>
              </div>
              <div class="flex items-center space-x-2 ml-4">
                <Link
                  :href="route('admin.skill.edit', skill.id)"
                  class="text-green-400 hover:text-green-300 transition-colors"
                  title="Edit"
                >
                  ✏️
                </Link>
                <button
                  @click="confirmDelete(skill)"
                  class="text-red-400 hover:text-red-300 transition-colors"
                  title="Delete"
                >
                  🗑️
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div
          v-if="skills.data.length === 0"
          class="text-center py-12 bg-gray-800 border border-gray-700 rounded-lg"
        >
          <div class="text-6xl mb-4">🛠️</div>
          <h3 class="text-lg font-semibold text-white mb-2">No Skills Added</h3>
          <p class="text-gray-400 mb-4">Start by adding your first skill.</p>
          <Link
            :href="route('admin.skill.create')"
            class="px-4 py-2 bg-red-900 hover:bg-red-800 text-white rounded-lg transition-colors"
          >
            Add Skill
          </Link>
        </div>
      </div>

      <!-- Pagination -->
      <div
        v-if="skills.links && skills.links.length > 3"
        class="flex items-center justify-center space-x-1"
      >
        <Link
          v-for="link in skills.links"
          :key="link.label"
          :href="link.url"
          :class="[
            'px-3 py-2 text-sm rounded',
            link.active
              ? 'bg-red-900 text-white'
              : 'bg-gray-800 text-gray-300 hover:bg-gray-700',
          ]"
          v-html="link.label"
        >
        </Link>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      @click="closeDeleteModal"
    >
      <div
        class="bg-gray-800 border border-gray-700 rounded-lg p-6 max-w-md w-full mx-4"
        @click.stop
      >
        <h3 class="text-lg font-semibold text-white mb-4">Confirm Delete</h3>
        <p class="text-gray-400 mb-6">
          Are you sure you want to delete "{{ skillToDelete?.name }}"? This
          action cannot be undone.
        </p>
        <div class="flex justify-end space-x-4">
          <button
            @click="closeDeleteModal"
            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded transition-colors"
          >
            Cancel
          </button>
          <button
            @click="deleteSkill"
            class="px-4 py-2 bg-red-900 hover:bg-red-800 text-white rounded transition-colors"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps({
  skills: Object,
});

const showDeleteModal = ref(false);
const skillToDelete = ref(null);

// Group skills by category
const groupedSkills = computed(() => {
  const groups = {};
  props.skills.data.forEach((skill) => {
    if (!groups[skill.category]) {
      groups[skill.category] = [];
    }
    groups[skill.category].push(skill);
  });
  return groups;
});

const confirmDelete = (skill) => {
  skillToDelete.value = skill;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  skillToDelete.value = null;
};

const deleteSkill = () => {
  if (skillToDelete.value) {
    router.delete(route("admin.skill.destroy", skillToDelete.value.id));
    closeDeleteModal();
  }
};

const getSkillWidth = (level) => {
  const levels = {
    pemula: "40%",
    menengah: "70%",
    mahir: "95%",
  };
  return levels[level] || "50%";
};
</script>
