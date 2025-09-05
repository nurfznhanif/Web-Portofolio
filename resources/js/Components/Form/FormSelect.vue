<!-- FormSelect.vue -->
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

    <select
      :id="id"
      :value="modelValue"
      :required="required"
      :disabled="disabled"
      @change="$emit('update:modelValue', $event.target.value)"
      class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
      :class="{
        'border-red-500 focus:border-red-500 focus:ring-red-500/20': hasError,
        'opacity-50 cursor-not-allowed': disabled,
      }"
    >
      <option value="" disabled>{{ placeholder || "Select an option" }}</option>
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>

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
  placeholder: String,
  required: Boolean,
  disabled: Boolean,
  error: String,
  help: String,
  id: String,
  options: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(["update:modelValue"]);

const hasError = computed(() => !!props.error);
</script>
