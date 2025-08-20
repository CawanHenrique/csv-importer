<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
  contacts: {
    data: Array<{ id: number; name: string; email: string; phone: string; birthdate: string }>;
    links: Array<{ url: string | null; label: string; active: boolean }>;
  };
}>();
</script>

<template>
  <div class="bg-white shadow-sm border border-slate-200 rounded-xl">
    <div class="p-6">
      <h2 class="font-semibold text-xl text-slate-800">Existing Contacts</h2>
    </div>
    
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
          <tr>
            <th class="p-4 text-left font-semibold text-slate-600 uppercase tracking-wider">Name</th>
            <th class="p-4 text-left font-semibold text-slate-600 uppercase tracking-wider">Email</th>
            <th class="p-4 text-left font-semibold text-slate-600 uppercase tracking-wider">Phone</th>
            <th class="p-4 text-left font-semibold text-slate-600 uppercase tracking-wider">Birthdate</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <tr v-for="contact in contacts.data" :key="contact.id" class="hover:bg-slate-50 transition-colors duration-200">
            <td class="p-4 text-slate-800 font-medium whitespace-nowrap">{{ contact.name }}</td>
            <td class="p-4 text-slate-600 whitespace-nowrap">{{ contact.email }}</td>
            <td class="p-4 text-slate-600 whitespace-nowrap">{{ contact.phone }}</td>
            <td class="p-4 text-slate-600 whitespace-nowrap">{{ contact.birthdate }}</td>
          </tr>
          <tr v-if="contacts.data.length === 0">
            <td colspan="4" class="text-center p-6 text-slate-500">No contacts found.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="contacts.links.length > 3" class="flex justify-center items-center p-4 border-t border-slate-200 bg-white rounded-b-xl">
      <nav class="flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
        <template v-for="(link, index) in contacts.links" :key="link.label">
          <Link v-if="link.url" :href="link.url" class="relative inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-200" :class="{'bg-blue-600 text-white hover:bg-blue-700 z-10': link.active, 'bg-white text-slate-500 hover:bg-slate-100': !link.active, 'rounded-l-md': index === 0, 'rounded-r-md': index === contacts.links.length - 1}" v-html="link.label"/>
          <span v-else class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-400 bg-white cursor-default" :class="{'rounded-l-md': index === 0, 'rounded-r-md': index === contacts.links.length - 1}" v-html="link.label" />
        </template>
      </nav>
    </div>
  </div>
</template>