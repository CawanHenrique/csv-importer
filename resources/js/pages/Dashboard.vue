<script setup lang="ts">
import ContactsTable from '@/components/DashboardContacts/ContactsTable.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'View Contacts',
        href: '/dashboard',
    },
];

const props = defineProps<{
    contacts: any;
}>()

const search = ref('');

function submitSearch(){
    router.get(route('dashboard'), {search: search.value})
}
</script>

<template>

    <Head title="Alberon Importer" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <Input type="search" @keyup.enter="submitSearch" v-model="search" class="border border-green-800" />
                <Button type="button"  variant="outline" @click="submitSearch">Search</Button>
                 <ContactsTable :contacts="contacts" />
        </div>
    </AppLayout>
</template>