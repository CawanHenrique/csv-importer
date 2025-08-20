<script setup lang="ts">
defineProps<{
  summary: {
    total?: number;
    imported?: number;
    duplicates?: number;
    invalid?: number;
  }
}>();
</script>

<template>
  <Transition
    enter-active-class="transition ease-out duration-300"
    enter-from-class="transform opacity-0 scale-95"
    enter-to-class="transform opacity-100 scale-100"
  >
    <div class="bg-white shadow-sm border border-slate-200 p-6 rounded-xl">
      <h2 class="font-semibold text-xl text-slate-800 mb-4">Import Summary</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
        <div class="bg-slate-100 p-4 rounded-lg"><div class="text-3xl font-bold text-slate-700">{{ summary.total }}</div><div class="text-sm text-slate-500">Total Records</div></div>
        <div class="bg-green-50 p-4 rounded-lg"><div class="text-3xl font-bold text-green-600">{{ summary.imported }}</div><div class="text-sm text-green-500">Imported</div></div>
        <div class="bg-yellow-50 p-4 rounded-lg"><div class="text-3xl font-bold text-yellow-600">{{ summary.duplicates }}</div><div class="text-sm text-yellow-500">Duplicates</div></div>
        <div class="bg-red-50 p-4 rounded-lg"><div class="text-3xl font-bold text-red-600">{{ summary.invalid }}</div><div class="text-sm text-red-500">Invalid</div></div>
      </div>
    </div>
  </Transition>
</template>