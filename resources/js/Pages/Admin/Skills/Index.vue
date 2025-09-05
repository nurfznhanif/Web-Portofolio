<!-- resources/js/Pages/Admin/Skill/Index.vue -->
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
          Add New Skill
        </Link>
      </div>

      <!-- Filters -->
      <div class="bg-gray-800 rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <FormInput
            v-model="filters.search"
            placeholder="Search skills..."
            @input="handleSearch"
          />

          <FormSelect
            v-model="filters.category"
            placeholder="All Categories"
            :options="categoryOptions"
            @change="handleFilter"
          />

          <FormSelect
            v-model="filters.level"
            placeholder="All Levels"
            :options="levelOptions"
            @change="handleFilter"
          />
        </div>
      </div>

      <!-- Skills by Category -->
      <div
        v-if="groupedSkills && Object.keys(groupedSkills).length > 0"
        class="space-y-6"
      >
        <div
          v-for="(categorySkills, category) in groupedSkills"
          :key="category"
        >
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-white">{{ category }}</h2>
            <span class="text-gray-400 text-sm"
              >{{ categorySkills.length }} skills</span
            >
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
              v-for="skill in categorySkills"
              :key="skill.id"
              class="bg-gray-800 rounded-lg p-4 hover:shadow-lg transition-shadow relative group"
            >
              <!-- Skill Level Badge -->
              <div class="absolute top-3 right-3">
                <span
                  :class="[
                    'px-2 py-1 text-xs rounded-full',
                    getLevelColor(skill.level),
                  ]"
                >
                  {{ skill.level }}
                </span>
              </div>

              <!-- Actions Dropdown -->
              <div
                class="absolute top-3 right-16 opacity-0 group-hover:opacity-100 transition-opacity"
              >
                <div class="relative">
                  <button
                    @click="toggleActions(skill.id)"
                    class="p-1 bg-gray-700 rounded text-gray-400 hover:text-white"
                  >
                    <svg
                      class="w-4 h-4"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
                      />
                    </svg>
                  </button>

                  <div
                    v-if="activeActions === skill.id"
                    class="absolute right-0 mt-2 w-32 bg-gray-800 rounded-md shadow-lg border border-gray-700 z-10"
                  >
                    <div class="p-1">
                      <Link
                        :href="route('admin.skill.edit', skill.id)"
                        class="flex items-center px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded"
                      >
                        Edit
                      </Link>
                      <button
                        @click="confirmDelete(skill)"
                        class="flex items-center w-full px-3 py-2 text-sm text-red-400 hover:bg-gray-700 rounded"
                      >
                        Delete
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Skill Content -->
              <div class="pr-16">
                <h3 class="text-lg font-semibold text-white mb-2">
                  {{ skill.name }}
                </h3>

                <!-- Skill Level Progress -->
                <div class="mb-3">
                  <div class="flex justify-between text-sm text-gray-400 mb-1">
                    <span>Proficiency</span>
                    <span>{{ getLevelPercentage(skill.level) }}%</span>
                  </div>
                  <div class="w-full bg-gray-700 rounded-full h-2">
                    <div
                      :class="[
                        'h-2 rounded-full transition-all duration-300',
                        getLevelProgressColor(skill.level),
                      ]"
                      :style="{ width: getLevelPercentage(skill.level) + '%' }"
                    ></div>
                  </div>
                </div>

                <!-- Creation Date -->
                <div class="text-xs text-gray-500">
                  Added {{ formatDate(skill.created_at) }}
                </div>
              </div>
            </div>
          </div>
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
            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
          />
        </svg>
        <h3 class="mt-4 text-lg font-medium text-white">No skills found</h3>
        <p class="mt-2 text-gray-400">
          Get started by adding your first skill.
        </p>
        <div class="mt-6">
          <Link
            :href="route('admin.skill.create')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
          >
            Add Skill
          </Link>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="skills.total > skills.per_page" class="mt-6">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-400">
            Showing {{ skills.from }} to {{ skills.to }} of
            {{ skills.total }} results
          </div>
          <div class="flex space-x-1">
            <Link
              v-for="link in skills.links"
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
import FormInput from "@/Components/Form/FormInput.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";
import ConfirmDialog from "@/Components/UI/ConfirmDialog.vue";

const props = defineProps({
  skills: Object,
  filters: Object,
});

const showDeleteModal = ref(false);
const selectedSkill = ref(null);
const activeActions = ref(null);

const filters = ref({
  search: props.filters?.search || "",
  category: props.filters?.category || "",
  level: props.filters?.level || "",
});

const categoryOptions = computed(() => {
  const categories = [...new Set(props.skills.data.map((s) => s.category))];
  return [
    { value: "", label: "All Categories" },
    ...categories.map((cat) => ({ value: cat, label: cat })),
  ];
});

const levelOptions = ref([
  { value: "", label: "All Levels" },
  { value: "pemula", label: "Pemula" },
  { value: "menengah", label: "Menengah" },
  { value: "mahir", label: "Mahir" },
]);

const groupedSkills = computed(() => {
  const filtered = props.skills.data.filter((skill) => {
    const matchesSearch =
      !filters.value.search ||
      skill.name.toLowerCase().includes(filters.value.search.toLowerCase());
    const matchesCategory =
      !filters.value.category || skill.category === filters.value.category;
    const matchesLevel =
      !filters.value.level || skill.level === filters.value.level;

    return matchesSearch && matchesCategory && matchesLevel;
  });

  return filtered.reduce((groups, skill) => {
    const category = skill.category || "Other";
    if (!groups[category]) {
      groups[category] = [];
    }
    groups[category].push(skill);
    return groups;
  }, {});
});

const getLevelColor = (level) => {
  const colors = {
    pemula: "bg-yellow-900 text-yellow-300",
    menengah: "bg-blue-900 text-blue-300",
    mahir: "bg-green-900 text-green-300",
  };
  return colors[level] || "bg-gray-900 text-gray-300";
};

const getLevelPercentage = (level) => {
  const percentages = {
    pemula: 35,
    menengah: 70,
    mahir: 95,
  };
  return percentages[level] || 0;
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const toggleActions = (skillId) => {
  activeActions.value = activeActions.value === skillId ? null : skillId;
};

const handleSearch = () => {
  router.get(route("admin.skill.index"), filters.value, {
    preserveState: true,
  });
};

const handleFilter = () => {
  router.get(route("admin.skill.index"), filters.value, {
    preserveState: true,
  });
};

const confirmDelete = (skill) => {
  selectedSkill.value = skill;
  showDeleteModal.value = true;
  activeActions.value = null;
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
