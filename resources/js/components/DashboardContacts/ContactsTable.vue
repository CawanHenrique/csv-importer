<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, Users } from 'lucide-vue-next'; 

defineProps<{
  contacts: {
    data: Array<{ id: number; name: string; email: string; phone: string; birthdate: string }>;
    links: Array<{ url: string | null; label: string; active: boolean }>;
  };
}>();
</script>

<template>
  <div class="bg-white shadow-md border border-blue-200 rounded-xl overflow-hidden">

    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="border-b border-blue-200 bg-blue-100">
          <tr>
            <th class="px-6 py-4 text-left font-bold text-gray-800 uppercase tracking-wider">Name</th>
            <th class="px-6 py-4 text-left font-bold text-gray-800 uppercase tracking-wider">Email</th>
            <th class="px-6 py-4 text-left font-bold text-gray-800 uppercase tracking-wider">Phone</th>
            <th class="px-6 py-4 text-left font-bold text-gray-800 uppercase tracking-wider">Birthdate</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="contact in contacts.data" :key="contact.id" class="border-b border-blue-100 last:border-b-0 hover:bg-blue-50 transition-colors duration-200">
            <td class="px-6 py-4 text-gray-900 font-semibold whitespace-nowrap">{{ contact.name }}</td>
            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ contact.email }}</td>
            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ contact.phone }}</td>
            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ contact.birthdate }}</td>
          </tr>

          <tr v-if="contacts.data.length === 0">
            <td colspan="4" class="text-center py-16">
                <div class="flex flex-col items-center justify-center text-blue-700">
                    <Users class="h-12 w-12 mb-2 text-blue-400" />
                    <h3 class="text-lg font-semibold">No Contacts Found</h3>
                    <p class="text-sm">When you add a new contact, it will appear here.</p>
                </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="contacts.links.length > 3" class="flex justify-between items-center p-4 border-t border-blue-200 bg-white">
      <Link
        :href="contacts.links[0].url || ''"
        :disabled="!contacts.links[0].url"
        class="flex items-center justify-center px-3 h-8 text-sm font-medium text-black bg-white border  rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <ChevronLeft class="h-4 w-4 mr-1" />
        Previous
      </Link>

      <nav class="flex items-center space-x-2">
        <template v-for="link in contacts.links.slice(1, -1)" :key="link.label">
          <Link
            :href="link.url || ''"
            class="flex items-center justify-center w-8 h-8 text-sm font-medium rounded-md transition-colors"
            :class="{
                'bg-blue-600 text-white hover:bg-blue-700 shadow-sm': link.active,
                'text-blue-700 bg-white hover:bg-blue-100': !link.active
            }"
          >
            {{ link.label }}
          </Link>
        </template>
      </nav>

      <Link
        :href="contacts.links[contacts.links.length - 1].url || ''"
        :disabled="!contacts.links[contacts.links.length - 1].url"
        class="flex items-center justify-center px-3 h-8 text-sm font-medium text-black bg-white border  rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Next
        <ChevronRight class="h-4 w-4 ml-1" />
      </Link>
    </div>
  </div>
</template>