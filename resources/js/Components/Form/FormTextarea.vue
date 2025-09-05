<!-- FormTextarea.vue -->
<template>
  <div class="mb-4">
    <label
      v-if="label"
      :for="id"
      class="block text-sm font-medium text-gray-300 mb-2"
    >
      {{ label }}
      <span v-if="required" class="text-red-400">*</span>
    </label>

    <div class="relative">
      <textarea
        :id="id"
        :value="modelValue"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        :rows="rows"
        @input="handleInput"
        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
        :class="{
          'border-red-500 focus:border-red-500 focus:ring-red-500/20': hasError,
          'opacity-50 cursor-not-allowed': disabled,
        }"
      ></textarea>

      <!-- Character Count -->
      <div
        v-if="maxLength"
        class="absolute bottom-3 right-3 text-xs text-gray-400"
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
  id: String,
  rows: {
    type: Number,
    default: 4,
  },
  maxLength: Number,
});

const emit = defineEmits(["update:modelValue"]);

const hasError = computed(() => !!props.error);
const characterCount = computed(() => props.modelValue?.length || 0);

const handleInput = (event) => {
  const value = event.target.value;
  if (props.maxLength && value.length > props.maxLength) {
    return;
  }
  emit("update:modelValue", value);
};
</script>
