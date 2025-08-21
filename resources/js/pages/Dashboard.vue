<script setup lang="ts">
import ContactsTable from '@/components/DashboardContacts/ContactsTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Plus, Search } from 'lucide-vue-next'; 
import Button from '@/components/ui/button/Button.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Contacts',
        href: '/dashboard',
    },
];

const props = defineProps<{
    contacts: any;
}>();
const search = ref('');

watch(search, (value) => {
    router.get(route('dashboard'), { search: value }, {
        preserveState: true,
        replace: true,
    });
});

function deleteContacts(){
    router.delete(route('contacts.destroy'), {
        preserveState: true,
        replace: true,
    })
}

</script>

<template>
    <Head title="Contacts | Alberon Importer" />
    <AppLayout :breadcrumbs="breadcrumbs" class="bg-color-blue">
        <div class="h-full flex-1 flex-col space-y-6 p-6 md:flex md:p-8">

            <div class="flex flex-col justify-between md:flex-row md:items-center">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-800">
                        Contacts
                    </h2>
                    <p class="mt-1 text-gray-500">
                        Manage all your contacts in one place.
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <Link href="/contacts"
                          class="inline-flex items-center justify-center rounded-md bg-blue-800 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <Plus class="mr-2 h-4 w-4" />
                        Add Contact
                    </Link>
                    <Button @click="deleteContacts" class="bg-red-600 hover:bg-red-500 text-white">Delete Contacts</Button>
                </div>
            </div>

            <div class="relative w-full md:max-w-md">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <Search class="h-5 w-5 text-gray-400" />
                </div>
                <input
                    v-model="search"
                    type="search"
                    placeholder="Search by name, email..."
                    class="block w-full rounded-md border-gray-300 bg-white py-2 pl-10 pr-3 shadow-sm transition placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                />
            </div>

            <div class="rounded-lg border shadow-md overflow-hidden">
                <ContactsTable :contacts="contacts" />
            </div>

        </div>
    </AppLayout>
</template>