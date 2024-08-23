<script setup lang="ts">
import { ref, shallowRef } from 'vue';

import BaseBreadcrumb from '@/components/shared/BaseBreadcrumb.vue';
import UiParentCard from '@/components/shared/UiParentCard.vue';
import { makeRequest } from '@/api/api';

const page = ref({ title: 'Sample Page' });
const breadcrumbs = shallowRef([
    {
        title: 'Direktori Perusahaan Pertanian',
        disabled: true,
        href: '/directory',
    },
]);

const headers = ref([
    { title: 'ID Perusahaan', align: 'center', sortable: false, key: 'id_sbr' },
    { title: 'Nama', key: 'name', align: 'center' },
    { title: 'Wilayah', key: 'kab', align: 'center' },
    { title: 'Alamat', key: 'address', align: 'center' },
    { title: 'Maps', key: 'coordinate', align: 'center' },
    { title: 'Subsector', key: 'subsectors', align: 'center' },
    { title: 'Survey', key: 'surveys', align: 'center' },
]);

const itemsPerPage = ref(5);
const serverItems = ref([]);
const loading = ref(true);
const totalItems = ref(0);

function loadItems({ page, itemsPerPage, sortBy }: { page: number; itemsPerPage: number; sortBy: any }) {
    loading.value = true;

    makeRequest.get('companies/?page=' + page).then(async response => {
        serverItems.value = response.data.data;
        totalItems.value = response.data.meta.total;
        loading.value = false;
    }).catch((error) => {
        console.log('error')
    });
}

loadItems({ page: 1, itemsPerPage: itemsPerPage.value, sortBy: [] });
</script>

<template>
    <BaseBreadcrumb :title="page.title" :breadcrumbs="breadcrumbs"></BaseBreadcrumb>
    <v-data-table-server v-model:items-per-page="itemsPerPage" :headers="headers" :items="serverItems"
        :items-length="totalItems" :loading="loading" item-value="name"
        @update:options="loadItems"></v-data-table-server>
</template>
