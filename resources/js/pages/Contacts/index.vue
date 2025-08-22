<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import FileUploader from '@/components/Contacts/FileUploader.vue';
import ImportSummary from '@/components/Contacts/ImportSummary.vue';
import { BreadcrumbItem } from '@/types';

const props = defineProps<{
  contacts: any;
  summary?: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Contacts Import',
    href: '/contacts',
  },
];

const form = useForm({
  file: null as File | null
});

function submit() {
  form.post(route('contacts.import'), {
    forceFormData: true,
    onFinish: () => {
      form.reset('file');
    }
  });
}
function exportCsv(data: any[], filename: string = "invalid_and_duplicates.csv") {
  if (!data.length) return;

  const header = Object.keys(data[0]);
  const rows = data.map(obj => header.map(h => obj[h] ?? ""));
  const csvContent = [header, ...rows].map(e => e.join(";")).join("\n");

  const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = filename;
  link.click();
}
</script>

<template>
  <Head title="Import Contacts | Alberon Importer" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="h-full w-full flex flex-col justify-center items-center p-4">
      
      <div class="w-full max-w-2xl space-y-6">
        <header class="text-center">
          <h1 class="text-3xl font-bold text-slate-800">Import Contacts</h1>
          <p class="mt-1 text-slate-500">Upload a file and see the magic happen.</p>
        </header>

        <FileUploader :form="form" @submit="submit" />
        <ImportSummary v-if="$page.props.summary" :summary="$page.props.summary" />
        <button
          v-if="$page.props.summary"
          @click="exportCsv([
            ...props.summary.invalid_items,
            ...props.summary.duplicate_items
          ])"
          class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg"
        >
          Export invalids and duplicates
        </button>
      </div>
    </div>
  </AppLayout>
</template>
