<script setup lang="ts">
import ContactsTable from '@/components/Contacts/ContactsTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'CSV Importer',
        href: '/dashboard',
    },
];

const props = defineProps<{
    contacts: any;
}>()
</script>

<template>

    <Head title="CsvImporter" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                 <ContactsTable :contacts="contacts" />
        </div>
    </AppLayout>
</template>