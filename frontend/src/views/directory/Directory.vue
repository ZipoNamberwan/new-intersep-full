<script setup lang="ts">
import { h, ref, shallowRef } from 'vue';

import BaseBreadcrumb from '@/components/shared/BaseBreadcrumb.vue';
import UiParentCard from '@/components/shared/UiParentCard.vue';
import { makeRequest } from '@/api/api';
import { PlusCircleOutlined, SearchOutlined } from '@ant-design/icons-vue';

const page = ref({ title: 'Direktori Perusahaan Pertanian' });
const breadcrumbs = shallowRef([
    {
        title: 'Direktori Perusahaan Pertanian',
        disabled: true,
        href: '/directory',
    },
]);

const columns = [
    { title: 'ID Perusahaan', key: 'id_sbr', dataIndex: 'id_sbr', align: 'center', },
    { title: 'Nama', key: 'name', dataIndex: 'name', align: 'left', sorter: (a: any, b: any) => a.name.localeCompare(b.name), },
    { title: 'Wilayah', key: 'kab', dataIndex: 'kab', align: 'center', sorter: (a: any, b: any) => a.name.localeCompare(b.name), },
    { title: 'Alamat', key: 'address', dataIndex: 'address', align: 'left', },
    { title: 'Maps', key: 'coordinate', dataIndex: 'coordinate', align: 'center', },
    { title: 'Subsector', key: 'subsectors', dataIndex: 'subsectors', align: 'center', },
    { title: 'Survey', key: 'surveys', dataIndex: 'surveys', align: 'center', },
];

const itemsPerPage = ref(20);
const loading = ref(false);
const data = ref([]);
const pagination = ref({
    current: 1,
    pageSize: 10,
    total: 0,
    pageSizeOptions: ['10', '20', '50'], // Customize the page size options here
});

function loadItems({ page, itemsPerPage, sortBy }: { page: number; itemsPerPage: number; sortBy: any }) {
    loading.value = true;

    makeRequest.get('companies/?page=' + page + '&pageSize=' + itemsPerPage).then(async response => {
        data.value = response.data.data;
        loading.value = false;
        pagination.value.total = response.data.meta.total
    }).catch((error) => {
        loading.value = false;
    });
}

function handleTableChange(newPagination: any, filters: any, sorter: any, extra: any) {
    pagination.value = newPagination
    loadItems({ page: pagination.value.current, itemsPerPage: pagination.value.pageSize, sortBy: [] });
}

loadItems({ page: 1, itemsPerPage: itemsPerPage.value, sortBy: [] });
</script>

<template>
    <BaseBreadcrumb :breadcrumbs="breadcrumbs"></BaseBreadcrumb>

    <UiParentCard :title="page.title">
        <v-row>
            <v-col>
                <a-button type="primary" :icon="h(PlusCircleOutlined)">Tambah Baru</a-button>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" sm="12" md="6" lg="3">
                <a-input-search allowClear placeholder="Pencarian" />
            </v-col>
            <v-col cols="12" sm="12" md="6" lg="3">
                <a-select allowClear mode="multiple" style="width: 100%" placeholder="Subsector"
                    :options="[...Array(25)].map((_, i) => ({ value: (i + 10).toString(36) + (i + 1) }))"></a-select>
            </v-col>
            <v-col cols="12" sm="12" md="6" lg="3">
                <a-select allowClear mode="multiple" style="width: 100%" placeholder="Survey"
                    :options="[...Array(25)].map((_, i) => ({ value: (i + 10).toString(36) + (i + 1) }))"></a-select>
            </v-col>
            <v-spacer></v-spacer>
        </v-row>
        <v-row class="mb-3">
            <v-col cols="12" sm="12" md="4" lg="2">
                <a-select allowClear style="width: 100%" placeholder="Kabupaten"
                    :options="[...Array(25)].map((_, i) => ({ value: (i + 10).toString(36) + (i + 1) }))"></a-select>
            </v-col>
            <v-col cols="12" sm="12" md="4" lg="2">
                <a-select allowClear style="width: 100%" placeholder="Kecamatan"
                    :options="[...Array(25)].map((_, i) => ({ value: (i + 10).toString(36) + (i + 1) }))"></a-select>
            </v-col>
            <v-col cols="12" sm="12" md="4" lg="2">
                <a-select allowClear style="width: 100%" placeholder="Desa"
                    :options="[...Array(25)].map((_, i) => ({ value: (i + 10).toString(36) + (i + 1) }))"></a-select>
            </v-col>
            <v-spacer></v-spacer>
        </v-row>
        <a-table :scroll="{ x: 1000, y: 1000 }" :loading="loading" :columns="columns" :data-source="data"
            :pagination="pagination" @change="handleTableChange">

        </a-table>
    </UiParentCard>

</template>
