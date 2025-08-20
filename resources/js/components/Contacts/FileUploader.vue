<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  form: ReturnType<typeof useForm<{ file: File | null }>>;
}>();

const emit = defineEmits(['submit']);

const isDragging = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);

const fileName = computed(() => props.form.file?.name);

function handleFileChange(event: Event) {
  const target = event.target as HTMLInputElement;
  props.form.file = target.files?.[0] ?? null;
}

function handleDrop(event: DragEvent) {
  isDragging.value = false;
  props.form.file = event.dataTransfer?.files?.[0] ?? null;
  if (fileInput.value) fileInput.value.value = '';
}

function triggerSubmit() {
  emit('submit');
}
</script>

<template>
  <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
    <form @submit.prevent="triggerSubmit">
      <label
        for="file-upload"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="handleDrop"
        class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-lg cursor-pointer transition-colors duration-300"
        :class="{
          'border-blue-500 bg-blue-50': isDragging,
          'border-slate-300 bg-slate-50 hover:bg-slate-100': !isDragging
        }"
      >
        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
          <svg class="w-10 h-10 mb-4 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/></svg>
          <p v-if="!fileName" class="mb-2 text-sm text-slate-500"><span class="font-semibold text-blue-600">Click to browse</span> or drag and drop the file here.</p>
          <p v-else class="mb-2 text-sm font-semibold text-green-600">File selected: {{ fileName }}</p>
          <p class="text-xs text-slate-400">CSV, TXT, XLSX or XLS</p>
        </div>
        <input id="file-upload" ref="fileInput" type="file" @change="handleFileChange" accept=".csv,.txt,.xlsx,.xls" class="hidden" />
      </label>
      
      <div class="mt-6 flex items-center gap-4">
        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300" :disabled="!form.file || form.processing">
          <svg v-if="!form.processing" class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3.75 3.75M12 9.75l3.75 3.75M3 17.25V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 17.25z" /></svg>
          <svg v-else class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
          {{ form.processing ? 'Processing...' : 'Upload File' }}
        </button>
      </div>
    </form>

    <div v-if="form.progress" class="mt-4 w-full bg-slate-200 rounded-full h-2.5">
      <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-out" :style="{ width: form.progress.percentage + '%' }"></div>
    </div>
  </div>
</template>