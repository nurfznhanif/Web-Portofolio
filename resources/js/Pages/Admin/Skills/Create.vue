<!-- resources/js/Pages/Admin/Skill/Create.vue -->
<template>
  <DashboardLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-white">Add New Skill</h1>
          <p class="text-gray-400">Add a new skill to your portfolio</p>
        </div>
        <Link
          :href="route('admin.skill.index')"
          class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors"
        >
          Back to Skills
        </Link>
      </div>

      <!-- Form -->
      <div class="bg-gray-800 rounded-lg p-6 max-w-2xl">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Skill Name -->
          <FormInput
            v-model="form.name"
            label="Skill Name"
            placeholder="e.g., Laravel, JavaScript, Project Management"
            required
            :error="form.errors.name"
            help="Enter the name of the skill or technology"
          />

          <!-- Category -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormSelect
              v-model="form.category"
              label="Category"
              placeholder="Select category"
              :options="categoryOptions"
              required
              :error="form.errors.category"
            />

            <!-- Level -->
            <FormSelect
              v-model="form.level"
              label="Proficiency Level"
              placeholder="Select level"
              :options="levelOptions"
              required
              :error="form.errors.level"
            />
          </div>

          <!-- Custom Category Input -->
          <FormInput
            v-if="form.category === 'custom'"
            v-model="customCategory"
            label="Custom Category"
            placeholder="Enter custom category name"
            required
            help="Enter a new category name"
          />

          <!-- Skill Description (Optional) -->
          <FormTextarea
            v-model="form.description"
            label="Description (Optional)"
            placeholder="Describe your experience with this skill..."
            :rows="3"
            :error="form.errors.description"
            :max-length="500"
            help="Optional: Add details about your experience or projects using this skill"
          />

          <!-- Years of Experience (Optional) -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormInput
              v-model="form.years_experience"
              label="Years of Experience (Optional)"
              type="number"
              min="0"
              max="50"
              step="0.5"
              placeholder="2.5"
              :error="form.errors.years_experience"
              help="How many years have you been using this skill?"
            />

            <!-- Certification (Optional) -->
            <FormInput
              v-model="form.certification"
              label="Certification (Optional)"
              placeholder="e.g., AWS Certified, Oracle Certified"
              :error="form.errors.certification"
              help="Any relevant certifications for this skill"
            />
          </div>

          <!-- Level Description -->
          <div class="bg-gray-700 rounded-lg p-4">
            <h4 class="text-white font-medium mb-2">
              Proficiency Level Guide:
            </h4>
            <div class="space-y-2 text-sm">
              <div class="flex items-center">
                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                <span class="text-yellow-300 font-medium mr-2">Pemula:</span>
                <span class="text-gray-400"
                  >Basic knowledge, learning fundamentals</span
                >
              </div>
              <div class="flex items-center">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                <span class="text-blue-300 font-medium mr-2">Menengah:</span>
                <span class="text-gray-400"
                  >Good understanding, can work independently</span
                >
              </div>
              <div class="flex items-center">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                <span class="text-green-300 font-medium mr-2">Mahir:</span>
                <span class="text-gray-400"
                  >Expert level, can teach and lead others</span
                >
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-4 pt-6 border-t border-gray-700">
            <Link
              :href="route('admin.skill.index')"
              class="px-6 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
            >
              <span v-if="form.processing">Creating...</span>
              <span v-else>Create Skill</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import FormInput from "@/Components/Form/FormInput.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";
import FormTextarea from "@/Components/Form/FormTextarea.vue";

const customCategory = ref("");

const form = useForm({
  name: "",
  category: "",
  level: "",
  description: "",
  years_experience: "",
  certification: "",
});

const categoryOptions = ref([
  { value: "Programming Languages", label: "Programming Languages" },
  { value: "Frameworks & Libraries", label: "Frameworks & Libraries" },
  { value: "Databases", label: "Databases" },
  { value: "DevOps & Tools", label: "DevOps & Tools" },
  { value: "Design & UI/UX", label: "Design & UI/UX" },
  { value: "Mobile Development", label: "Mobile Development" },
  { value: "Cloud Services", label: "Cloud Services" },
  { value: "Project Management", label: "Project Management" },
  { value: "Soft Skills", label: "Soft Skills" },
  { value: "Other Technical", label: "Other Technical" },
  { value: "custom", label: "Custom Category..." },
]);

const levelOptions = ref([
  { value: "pemula", label: "Pemula (Beginner)" },
  { value: "menengah", label: "Menengah (Intermediate)" },
  { value: "mahir", label: "Mahir (Expert)" },
]);

// Watch for custom category selection
watch(
  () => form.category,
  (newValue) => {
    if (newValue !== "custom") {
      customCategory.value = "";
    }
  }
);

// Watch for custom category input
watch(customCategory, (newValue) => {
  if (form.category === "custom" && newValue) {
    form.category = newValue;
  }
});

const submit = () => {
  // Use custom category if selected
  if (customCategory.value && form.category === "custom") {
    form.category = customCategory.value;
  }

  form.post(route("admin.skill.store"));
};
</script>
