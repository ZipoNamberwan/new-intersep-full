<script setup lang="ts">
import { h, reactive, ref, shallowRef } from 'vue';

import BaseBreadcrumb from '@/components/shared/BaseBreadcrumb.vue';
import UiParentCard from '@/components/shared/UiParentCard.vue';
import { makeRequest } from '@/api/api';
import { PlusCircleOutlined, ReloadOutlined } from '@ant-design/icons-vue';

interface FilterOption {
    label: string;
    value: string;
}

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
const openAddModal = ref(false);
const selectedKabFilters = ref();
const kabFilters = ref<FilterOption[]>([]);
const loadingKabFilters = ref<boolean>(false);
const selectedKecFilters = ref();
const kecFilters = ref<FilterOption[]>([]);
const loadingKecFilters = ref<Boolean>(false);
const selectedDesFilters = ref();
const desFilters = ref<FilterOption[]>([]);
const loadingDesFilters = ref<Boolean>(false);

const formState = reactive({
    id_sbr: '',
    name: '',
    kabupaten: null,
    kecamatan: null,
    desa: null,
    address: '',
    subsectors: [],
    surveys: [],
});

function onFinish(value: any) {
    console.log(value)
}

function onFinishFailed(errorInfo: any) {
    console.log(errorInfo)
}

const showAddModal = () => {
    openAddModal.value = true;
};

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

function handleKabFilterChange(value: any) {
    getKecFilter(value)
    selectedKecFilters.value = null
    selectedDesFilters.value = null
}
function handleKecFilterChange(value: any) {
    getDesFilter(value)
    selectedDesFilters.value = null
}
const handleKabFiltersClear = () => {
    selectedKecFilters.value = null
    selectedDesFilters.value = null
};
const handleKecFiltersClear = () => {
    selectedDesFilters.value = null
};

function getKabFilter() {
    loadingKabFilters.value = true;
    makeRequest.get('kab').then(async response => {
        response.data.data.forEach((kab: any) => {
            kabFilters.value.push({
                label: '[' + kab.short_code + '] ' + kab.name,
                value: kab.id
            })
        });
        loadingKabFilters.value = false;
    }).catch((error) => {
        loadingKabFilters.value = false;
    });
}

function getKecFilter(idKab: string) {
    kecFilters.value = []
    desFilters.value = []
    if (idKab != null) {
        loadingKecFilters.value = true;

        makeRequest.get('kec/' + idKab).then(async response => {
            response.data.data.forEach((kec: any) => {
                kecFilters.value.push({
                    label: '[' + kec.short_code + '] ' + kec.name,
                    value: kec.id
                })
            });
            loadingKecFilters.value = false;
        }).catch((error) => {
            loadingKecFilters.value = false;
        });
    }
}

function getDesFilter(idKec: string) {
    desFilters.value = []
    if (idKec != null) {
        loadingDesFilters.value = true;

        makeRequest.get('des/' + idKec).then(async response => {
            response.data.data.forEach((des: any) => {
                desFilters.value.push({
                    label: '[' + des.short_code + '] ' + des.name,
                    value: des.id
                })
            });
            loadingDesFilters.value = false;
        }).catch((error) => {
            loadingDesFilters.value = false;
        });
    }
}

function handleTableChange(newPagination: any, filters: any, sorter: any, extra: any) {
    pagination.value = newPagination
    loadItems({ page: pagination.value.current, itemsPerPage: pagination.value.pageSize, sortBy: [] });
}

loadItems({ page: 1, itemsPerPage: itemsPerPage.value, sortBy: [] });
getKabFilter()
</script>

<template>
    <BaseBreadcrumb :breadcrumbs="breadcrumbs"></BaseBreadcrumb>

    <UiParentCard :title="page.title">
        <v-row>
            <v-col>
                <v-btn @click="showAddModal" color="primary" variant="flat" type="submit">
                    <template v-slot:prepend>
                        <PlusCircleOutlined />
                    </template>
                    Tambah Perusahaan
                </v-btn>
                <a-alert v-if="false" banner class="my-2" message="Gagal Mengambil Data Master" type="error">
                    <template #action>
                        <v-btn color="error" size="small" variant="flat" type="submit">
                            <template v-slot:prepend>
                                <ReloadOutlined />
                            </template>
                            Refresh
                        </v-btn> </template>
                </a-alert>
                <a-modal v-model:open="openAddModal" title="Tambah Perusahaan">
                    <a-form labelAlign="left" class="mt-4" :model="formState" name="basic" :label-col="{ span: 8 }"
                        :wrapper-col="{ span: 16 }" autocomplete="off" @finish="onFinish"
                        @finishFailed="onFinishFailed">
                        <a-form-item label="ID SBR" name="id_sbr">
                            <a-input placeholder="ID SBR" v-model:value="formState.id_sbr" />
                        </a-form-item>
                        <a-form-item label="Nama Perusahaan" name="name"
                            :rules="[{ required: true, message: 'Nama Perusahaan kosong' }]">
                            <a-input v-model:value="formState.name" placeholder="Nama Perusahaan" />
                        </a-form-item>
                        <a-form-item label="Kabupaten" name="kabupaten"
                            :rules="[{ required: true, message: 'Kabupaten belum terisi' }]">
                            <a-select ref="select" v-model:value="formState.kabupaten" class="mb-1" allowClear
                                style="width: 100%" placeholder="Kabupaten"
                                :options="[...Array(25)].map((_, i) => ({ value: (i + 10).toString(36) + (i + 1) }))"></a-select>
                        </a-form-item>
                        <a-form-item label="Kecamatan" name="kecamatan">
                            <a-select ref="select" v-model:value="formState.kecamatan" class="mb-1" allowClear
                                style="width: 100%" placeholder="Kecamatan"
                                :options="[...Array(25)].map((_, i) => ({ value: (i + 10).toString(36) + (i + 1) }))"></a-select>
                        </a-form-item>
                        <a-form-item label="Desa" name="desa">
                            <a-select ref="select" v-model:value="formState.desa" class="mb-1" allowClear
                                style="width: 100%" placeholder="Desa"
                                :options="[...Array(25)].map((_, i) => ({ value: (i + 10).toString(36) + (i + 1) }))"></a-select>
                        </a-form-item>
                        <a-form-item label="Alamat" name="address">
                            <a-textarea v-model:value="formState.address" placeholder="Alamat Perusahaan"
                                :auto-size="{ minRows: 3, maxRows: 5 }" />
                        </a-form-item>
                        <a-form-item label="Subsektor" name="subsectors">
                            <a-select allowClear mode="multiple" style="width: 100%" placeholder="Subsektor"
                                :options="[...Array(25)].map((_, i) => ({ value: (i + 10).toString(36) + (i + 1) }))"></a-select>

                        </a-form-item>
                        <a-form-item label="Survey" name="surveys">
                            <a-select allowClear mode="multiple" style="width: 100%" placeholder="Survey"
                                :options="[...Array(25)].map((_, i) => ({ value: (i + 10).toString(36) + (i + 1) }))"></a-select>
                        </a-form-item>
                    </a-form>
                </a-modal>
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
                <a-select @clear="handleKabFiltersClear" v-model:value="selectedKabFilters"
                    @change="handleKabFilterChange" :loading="loadingKabFilters" allowClear style="width: 100%"
                    placeholder="Kabupaten" :options="kabFilters"></a-select>
            </v-col>
            <v-col cols="12" sm="12" md="4" lg="2">
                <a-select @clear="handleKecFiltersClear" v-model:value="selectedKecFilters"
                    @change="handleKecFilterChange" :loading="loadingKecFilters" allowClear style="width: 100%"
                    placeholder="Kecamatan" :options="kecFilters"></a-select>
            </v-col>
            <v-col cols="12" sm="12" md="4" lg="2">
                <a-select v-model:value="selectedDesFilters" :loading="loadingDesFilters" allowClear style="width: 100%"
                    placeholder="Desa" :options="desFilters"></a-select>
            </v-col>
            <v-spacer></v-spacer>
        </v-row>
        <a-table :scroll="{ x: 1000, y: 1000 }" :loading="loading" :columns="columns" :data-source="data"
            :pagination="pagination" @change="handleTableChange">
            <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'subsector'">
                    <a-tag v-for="tag in record.subsector" :key="tag"
                        :color="tag === 'loser' ? 'volcano' : tag.length > 5 ? 'geekblue' : 'green'">
                        {{ tag.toUpperCase() }}
                    </a-tag>
                </template>
            </template>
        </a-table>
    </UiParentCard>
</template>
