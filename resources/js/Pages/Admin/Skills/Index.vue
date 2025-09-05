<!-- Admin/Skills/Index.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Skills Management</h1>
          <p class="text-gray-400">Manage your technical and soft skills</p>
        </div>
        <Link
          :href="route('admin.skill.create')"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center"
        >
          <svg
            class="w-5 h-5 mr-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"
            />
          </svg>
          Add Skill
        </Link>
      </div>

      <!-- Filter Tabs -->
      <div class="mb-6">
        <div class="border-b border-gray-700">
          <nav class="-mb-px flex space-x-8">
            <button
              v-for="category in categories"
              :key="category.key"
              @click="activeCategory = category.key"
              class="py-2 px-1 border-b-2 font-medium text-sm transition-colors"
              :class="{
                'border-blue-500 text-blue-400':
                  activeCategory === category.key,
                'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300':
                  activeCategory !== category.key,
              }"
            >
              {{ category.label }}
              <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-gray-700">
                {{ category.count }}
              </span>
            </button>
          </nav>
        </div>
      </div>

      <!-- Skills Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="skill in filteredSkills"
          :key="skill.id"
          class="bg-gray-800 rounded-lg p-6 hover:bg-gray-700/50 transition-colors"
        >
          <!-- Skill Header -->
          <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-3">
              <div
                class="w-10 h-10 rounded-lg flex items-center justify-center text-xl"
                :style="{ backgroundColor: skill.color || '#374151' }"
              >
                {{ skill.icon || "🛠️" }}
              </div>
              <div>
                <h3 class="text-lg font-semibold text-white">
                  {{ skill.name }}
                </h3>
                <p class="text-sm text-gray-400">{{ skill.category }}</p>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-1">
              <Link
                :href="route('admin.skill.edit', skill.id)"
                class="text-blue-400 hover:text-blue-300 p-1 rounded hover:bg-blue-500/10 transition-colors"
                title="Edit"
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
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                  />
                </svg>
              </Link>
              <button
                @click="confirmDelete(skill)"
                class="text-red-400 hover:text-red-300 p-1 rounded hover:bg-red-500/10 transition-colors"
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
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                  />
                </svg>
              </button>
            </div>
          </div>

          <!-- Skill Level -->
          <div class="mb-4">
            <div class="flex items-center justify-between text-sm mb-2">
              <span class="text-gray-400">Proficiency</span>
              <span class="text-white font-medium">{{
                getSkillLevelLabel(skill.level)
              }}</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-2">
              <div
                class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300"
                :style="{ width: getSkillLevelPercentage(skill.level) }"
              ></div>
            </div>
          </div>

          <!-- Description -->
          <p
            v-if="skill.description"
            class="text-gray-300 text-sm leading-relaxed"
          >
            {{ skill.description }}
          </p>

          <!-- Featured Badge -->
          <div v-if="skill.is_featured" class="mt-3">
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
            >
              ⭐ Featured
            </span>
          </div>
        </div>

        <!-- Empty State -->
        <div
          v-if="filteredSkills.length === 0"
          class="col-span-full text-center py-12"
        >
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
              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
            />
          </svg>
          <h3 class="text-lg font-medium text-white mb-2">
            No skills in this category
          </h3>
          <p class="text-gray-400 mb-4">Add your first skill to get started</p>
          <Link
            :href="route('admin.skill.create')"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            Add Skill
          </Link>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <ConfirmDialog
        :show="showDeleteModal"
        title="Delete Skill"
        :message="`Are you sure you want to delete '${selectedSkill?.name}'? This action cannot be undone.`"
        confirm-text="Delete"
        cancel-text="Cancel"
        type="danger"
        @confirm="deleteSkill"
        @cancel="showDeleteModal = false"
      />
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import ConfirmDialog from "@/Components/UI/ConfirmDialog.vue";

const props = defineProps({
  skills: Array,
});

const activeCategory = ref("all");
const showDeleteModal = ref(false);
const selectedSkill = ref(null);

// Group skills by category and create filter categories
const categories = computed(() => {
  const categoryMap = {};

  props.skills.forEach((skill) => {
    const category = skill.category || "Uncategorized";
    if (!categoryMap[category]) {
      categoryMap[category] = 0;
    }
    categoryMap[category]++;
  });

  const cats = [
    { key: "all", label: "All Skills", count: props.skills.length },
  ];

  Object.entries(categoryMap).forEach(([category, count]) => {
    cats.push({
      key: category.toLowerCase().replace(/\s+/g, "_"),
      label: category,
      count,
    });
  });

  return cats;
});

const filteredSkills = computed(() => {
  if (activeCategory.value === "all") {
    return props.skills;
  }

  const categoryName = categories.value.find(
    (cat) => cat.key === activeCategory.value
  )?.label;
  return props.skills.filter((skill) => skill.category === categoryName);
});

const getSkillLevelLabel = (level) => {
  const levels = {
    beginner: "Beginner",
    intermediate: "Intermediate",
    advanced: "Advanced",
    expert: "Expert",
  };
  return levels[level] || "Unknown";
};

const getSkillLevelPercentage = (level) => {
  const percentages = {
    beginner: "25%",
    intermediate: "50%",
    advanced: "75%",
    expert: "100%",
  };
  return percentages[level] || "0%";
};

const confirmDelete = (skill) => {
  selectedSkill.value = skill;
  showDeleteModal.value = true;
};

const deleteSkill = () => {
  router.delete(route("admin.skill.destroy", selectedSkill.value.id), {
    onSuccess: () => {
      showDeleteModal.value = false;
      selectedSkill.value = null;
    },
  });
};
</script>
