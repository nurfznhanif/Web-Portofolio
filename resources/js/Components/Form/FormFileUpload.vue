<!-- FormFileUpload.vue -->
<template>
  <div class="mb-4">
    <label v-if="label" class="block text-sm font-medium text-gray-300 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-400">*</span>
    </label>

    <div class="relative">
      <!-- File Input -->
      <input
        ref="fileInput"
        type="file"
        :accept="accept"
        :multiple="multiple"
        @change="handleFileChange"
        class="hidden"
      />

      <!-- Drop Zone -->
      <div
        @click="$refs.fileInput.click()"
        @dragover.prevent
        @drop.prevent="handleDrop"
        class="border-2 border-dashed border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition-colors"
        :class="{
          'border-red-500': hasError,
          'border-blue-500 bg-blue-500/5': isDragging,
        }"
      >
        <div v-if="!preview" class="text-gray-400">
          <svg
            class="mx-auto h-12 w-12 mb-4"
            stroke="currentColor"
            fill="none"
            viewBox="0 0 48 48"
          >
            <path
              d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
          <p class="text-sm">
            <span class="font-medium text-blue-400">Click to upload</span> or
            drag and drop
          </p>
          <p class="text-xs mt-1">{{ accept || "Any file type" }}</p>
        </div>

        <!-- Preview -->
        <div v-if="preview" class="relative">
          <img
            v-if="isImage"
            :src="preview"
            alt="Preview"
            class="max-h-32 mx-auto rounded"
          />
          <div v-else class="text-blue-400">📄 {{ fileName }}</div>

          <!-- Remove Button -->
          <button
            @click.stop="removeFile"
            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
          >
            ×
          </button>
        </div>
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
import { ref, computed } from "vue";

const props = defineProps({
  modelValue: [File, Array],
  label: String,
  required: Boolean,
  error: String,
  help: String,
  accept: String,
  multiple: Boolean,
});

const emit = defineEmits(["update:modelValue"]);

const fileInput = ref(null);
const isDragging = ref(false);
const preview = ref(null);
const fileName = ref("");

const hasError = computed(() => !!props.error);
const isImage = computed(() => {
  return preview.value && preview.value.startsWith("data:image");
});

const handleFileChange = (event) => {
  const files = event.target.files;
  if (files.length) {
    processFiles(files);
  }
};

const handleDrop = (event) => {
  isDragging.value = false;
  const files = event.dataTransfer.files;
  if (files.length) {
    processFiles(files);
  }
};

const processFiles = (files) => {
  const file = files[0]; // Handle single file for now
  fileName.value = file.name;

  if (file.type.startsWith("image/")) {
    const reader = new FileReader();
    reader.onload = (e) => {
      preview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    preview.value = "file";
  }

  emit("update:modelValue", file);
};

const removeFile = () => {
  preview.value = null;
  fileName.value = "";
  fileInput.value.value = "";
  emit("update:modelValue", null);
};
</script>
