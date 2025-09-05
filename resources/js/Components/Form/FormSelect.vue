// resources/js/Components/Form/FormSelect.vue
<template>
  <div class="mb-4">
    <label v-if="label" class="block text-sm font-medium text-gray-300 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-400">*</span>
    </label>

    <select
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      :required="required"
      :disabled="disabled"
      :class="[
        'w-full px-3 py-2 bg-gray-700 border rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors',
        hasError ? 'border-red-500' : 'border-gray-600',
        disabled ? 'opacity-50 cursor-not-allowed' : '',
      ]"
    >
      <option value="" v-if="placeholder" disabled>{{ placeholder }}</option>
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
  options: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(["update:modelValue"]);

const hasError = computed(() => !!props.error);
</script>
