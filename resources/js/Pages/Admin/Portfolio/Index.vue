<!-- Admin/Portfolio/Index.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Portfolio Management</h1>
          <p class="text-gray-400">Manage your portfolio projects</p>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-700">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-400">
              Showing {{ portfolios.from }} to {{ portfolios.to }} of
              {{ portfolios.total }} results
            </div>
            <div class="flex space-x-1">
              <Link
                v-for="link in portfolios.links"
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
      </div>

      <!-- Delete Confirmation Modal -->
      <ConfirmDialog
        :show="showDeleteModal"
        title="Delete Project"
        :message="`Are you sure you want to delete '${selectedProject?.title}'? This action cannot be undone.`"
        confirm-text="Delete"
        cancel-text="Cancel"
        type="danger"
        @confirm="deleteProject"
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
  portfolios: Object,
  filters: Object,
});

const showDeleteModal = ref(false);
const selectedProject = ref(null);

const filters = ref({
  search: props.filters?.search || "",
  type: props.filters?.type || "",
  sort: props.filters?.sort || "latest",
});

const projectTypeOptions = computed(() => {
  const types = [...new Set(props.portfolios.data.map((p) => p.project_type))];
  return types.map((type) => ({ value: type, label: type }));
});

const sortOptions = ref([
  { value: "latest", label: "Latest First" },
  { value: "oldest", label: "Oldest First" },
  { value: "title", label: "Title A-Z" },
  { value: "type", label: "Type" },
]);

const getTechnologies = (technologies) => {
  return technologies
    ? technologies.split(", ").map((tech) => tech.trim())
    : [];
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const handleSearch = () => {
  router.get(route("admin.portfolio.index"), filters.value, {
    preserveState: true,
  });
};

const handleFilter = () => {
  router.get(route("admin.portfolio.index"), filters.value, {
    preserveState: true,
  });
};

const handleSort = () => {
  router.get(route("admin.portfolio.index"), filters.value, {
    preserveState: true,
  });
};

const confirmDelete = (project) => {
  selectedProject.value = project;
  showDeleteModal.value = true;
};

const deleteProject = () => {
  router.delete(route("admin.portfolio.destroy", selectedProject.value.id), {
    onSuccess: () => {
      showDeleteModal.value = false;
      selectedProject.value = null;
    },
  });
};
</script>
