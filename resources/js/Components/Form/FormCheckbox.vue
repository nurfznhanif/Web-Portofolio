<!-- resources/js/Components/Form/FormCheckbox.vue -->
<template>
  <div class="mb-4">
    <div class="flex items-start">
      <div class="flex items-center h-5">
        <input
          :id="inputId"
          type="checkbox"
          :checked="modelValue"
          @change="$emit('update:modelValue', $event.target.checked)"
          :required="required"
          :disabled="disabled"
          :class="[
            'rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-600',
            hasError ? 'border-red-500' : '',
            disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
          ]"
        />
      </div>
      <div class="ml-3 text-sm">
        <label
          :for="inputId"
          :class="[
            'font-medium cursor-pointer',
            hasError ? 'text-red-400' : 'text-gray-300',
          ]"
        >
          {{ label }}
          <span v-if="required" class="text-red-400">*</span>
        </label>

        <!-- Help Text -->
        <p v-if="help && !hasError" class="text-gray-400 mt-1">
          {{ help }}
        </p>

        <!-- Error Message -->
        <p v-if="hasError" class="text-red-400 mt-1">
          {{ error }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  label: {
    type: String,
    required: true,
  },
  required: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  error: {
    type: String,
    default: null,
  },
  help: {
    type: String,
    default: null,
  },
});

const emit = defineEmits(["update:modelValue"]);

const hasError = computed(() => !!props.error);

// Generate unique ID for the input
const inputId = computed(
  () => `checkbox-${Math.random().toString(36).substr(2, 9)}`
);
</script>
