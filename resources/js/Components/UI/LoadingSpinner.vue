<!-- Components/UI/LoadingSpinner.vue -->
<template>
  <div
    v-if="show"
    class="flex items-center justify-center"
    :class="containerClass"
  >
    <div class="relative">
      <!-- Spinner -->
      <div
        class="animate-spin rounded-full border-4 border-gray-600"
        :class="[sizeClass, `border-t-${color}-500`]"
      ></div>

      <!-- Center icon (optional) -->
      <div
        v-if="icon"
        class="absolute inset-0 flex items-center justify-center"
      >
        <span :class="iconSizeClass">{{ icon }}</span>
      </div>
    </div>

    <!-- Loading text -->
    <div v-if="text" class="ml-3 text-gray-300">
      {{ text }}
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  show: {
    type: Boolean,
    default: true,
  },
  size: {
    type: String,
    default: "md",
    validator: (value) => ["sm", "md", "lg", "xl"].includes(value),
  },
  color: {
    type: String,
    default: "blue",
  },
  text: String,
  icon: String,
  overlay: Boolean,
  containerClass: {
    type: String,
    default: "p-4",
  },
});

const sizeClass = computed(() => {
  const sizes = {
    sm: "w-4 h-4",
    md: "w-6 h-6",
    lg: "w-8 h-8",
    xl: "w-12 h-12",
  };
  return sizes[props.size];
});

const iconSizeClass = computed(() => {
  const sizes = {
    sm: "text-xs",
    md: "text-sm",
    lg: "text-base",
    xl: "text-xl",
  };
  return sizes[props.size];
});
</script>
