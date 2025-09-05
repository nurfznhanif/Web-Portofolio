// resources/js/Components/Form/FormTextarea.vue
<template>
  <div class="mb-4">
    <label v-if="label" class="block text-sm font-medium text-gray-300 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-400">*</span>
    </label>

    <div class="relative">
      <textarea
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        :rows="rows"
        :maxlength="maxLength"
        :class="[
          'w-full px-3 py-2 bg-gray-700 border rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-vertical',
          hasError ? 'border-red-500' : 'border-gray-600',
          disabled ? 'opacity-50 cursor-not-allowed' : '',
        ]"
      ></textarea>

      <!-- Character count -->
      <div
        v-if="maxLength"
        class="absolute right-2 bottom-2 text-xs text-gray-400 bg-gray-700 px-1 rounded"
      >
        {{ characterCount }}/{{ maxLength }}
      </div>
    </div>

    <!-- Error Message -->
    <p v-if="hasError" class="mt-1 text-sm text-red-400">
      {{ error }}
    </p>

    <!-- Help Text -->
    <p v-if="help && !hasError" class="mt-1 text-sm text-gray-400">
      {{ help }}
    </p>
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  modelValue: String,
  label: String,
  placeholder: String,
  required: Boolean,
  disabled: Boolean,
  error: String,
  help: String,
  rows: {
    type: Number,
    default: 3,
  },
  maxLength: Number,
});

const emit = defineEmits(["update:modelValue"]);

const hasError = computed(() => !!props.error);
const characterCount = computed(() => String(props.modelValue || "").length);
</script>
