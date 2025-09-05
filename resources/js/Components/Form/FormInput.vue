// resources/js/Components/Form/FormInput.vue
<template>
  <div class="mb-4">
    <label v-if="label" class="block text-sm font-medium text-gray-300 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-400">*</span>
    </label>

    <div class="relative">
      <input
        :type="type"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        :maxlength="maxLength"
        :class="[
          'w-full px-3 py-2 bg-gray-700 border rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors',
          hasError ? 'border-red-500' : 'border-gray-600',
          disabled ? 'opacity-50 cursor-not-allowed' : '',
        ]"
      />

      <!-- Character count -->
      <div
        v-if="maxLength && showCharCount"
        class="absolute right-2 top-2 text-xs text-gray-400"
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
  modelValue: [String, Number],
  label: String,
  type: {
    type: String,
    default: "text",
  },
  placeholder: String,
  required: Boolean,
  disabled: Boolean,
  error: String,
  help: String,
  maxLength: Number,
  showCharCount: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["update:modelValue"]);

const hasError = computed(() => !!props.error);
const characterCount = computed(() => String(props.modelValue || "").length);
</script>
