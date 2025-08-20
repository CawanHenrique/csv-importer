<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
  file: null as File | null
});

const summary = ref<any>(null);
function submit() {
  form.post(route('contacts.import'), {
    forceFormData: true,
    onSuccess: () => {
      summary.value = form.progress ? form.progress : null;
    }
  });
}

defineProps<{
  contacts: {
    data: Array<{ id: number; name: string; email: string; phone: string; birthdate: string }>
  };
  summary?: {
    total: number;
    imported: number;
    duplicates: number;
    invalid: number;
  }
}>();
</script>

<template>
  <Head title="Importar Contatos" />

  <AppLayout>
    <div class="p-6 space-y-6">
      <!-- título -->
      <h1 class="text-2xl font-bold text-gray-800">Importar Contatos</h1>

      <!-- formulário -->
      <form @submit.prevent="submit" class="space-y-4">
        <input 
          type="file" 
          @change="e => form.file = (e.target as HTMLInputElement).files?.[0] ?? null"
          accept=".csv,.txt,.xlsx,.xls"
          class="block w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-200"
        />

        <button
          type="submit"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
          :disabled="form.processing"
        >
          Enviar Arquivo
        </button>
      </form>

      <!-- progresso -->
      <div v-if="form.progress" class="text-gray-700">
        Upload: {{ form.progress.percentage }}%
      </div>

      <!-- resumo -->
      <div v-if="summary || $page.props.summary" class="bg-white shadow p-4 rounded">
        <h2 class="font-semibold text-lg text-gray-800 mb-3">Resumo da Importação</h2>
        <ul class="space-y-1 text-gray-700">
          <li>Total de registros: <span class="font-semibold">{{ (summary || $page.props.summary).total }}</span></li>
          <li class="text-green-600">Importados: <span class="font-semibold">{{ (summary || $page.props.summary).imported }}</span></li>
          <li class="text-yellow-600">Duplicados: <span class="font-semibold">{{ (summary || $page.props.summary).duplicates }}</span></li>
          <li class="text-red-600">Inválidos: <span class="font-semibold">{{ (summary || $page.props.summary).invalid }}</span></li>
        </ul>
      </div>

      <!-- contatos -->
      <div class="bg-white shadow p-4 rounded">
        <h2 class="font-semibold text-lg text-gray-800 mb-3">Contatos</h2>
        <div class="overflow-x-auto">
          <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
              <tr>
                <th class="p-2 text-left text-gray-800 font-semibold">Nome</th>
                <th class="p-2 text-left text-gray-800 font-semibold">Email</th>
                <th class="p-2 text-left text-gray-800 font-semibold">Telefone</th>
                <th class="p-2 text-left text-gray-800 font-semibold">Nascimento</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="contact in contacts.data" :key="contact.id" class="border-t hover:bg-gray-50">
                <td class="p-2 text-gray-700">{{ contact.name }}</td>
                <td class="p-2 text-gray-700">{{ contact.email }}</td>
                <td class="p-2 text-gray-700">{{ contact.phone }}</td>
                <td class="p-2 text-gray-700">{{ contact.birthdate }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
