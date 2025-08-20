<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import FileUploader from '@/components/Contacts/FileUploader.vue';
import ImportSummary from '@/components/Contacts/ImportSummary.vue';

defineProps<{
  contacts: any;
  summary?: any;
}>();

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
</script>

<template>
  <Head title="Import Contacts" />

  <AppLayout>
    <div class="h-full w-full flex flex-col justify-center items-center p-4">
      
      <div class="w-full max-w-2xl space-y-6">
        <header class="text-center">
          <h1 class="text-3xl font-bold text-slate-800">Import Contacts</h1>
          <p class="mt-1 text-slate-500">Upload a file and see the magic happen.</p>
        </header>

        <FileUploader :form="form" @submit="submit" />

        <ImportSummary v-if="$page.props.summary" :summary="$page.props.summary" />
      </div>

    </div>
  </AppLayout>
</template>