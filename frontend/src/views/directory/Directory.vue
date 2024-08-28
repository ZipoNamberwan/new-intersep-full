<script setup lang="ts">
import { reactive, ref, shallowRef, watch } from 'vue';

import BaseBreadcrumb from '@/components/shared/BaseBreadcrumb.vue';
import UiParentCard from '@/components/shared/UiParentCard.vue';
import { makeRequest } from '@/api/api';
import { PlusCircleOutlined, ReloadOutlined } from '@ant-design/icons-vue';
import type { Ref } from 'vue';
import { debounce } from 'lodash';

interface FilterOption {
    label: string;
    value: string;
}

interface FormData {
    id_sbr: string,
    name: string,
    kabupaten?: number,
    kecamatan?: number,
    desa?: number,
    bs?: number,
    address?: string,
    subsectors: [],
    surveys: [],
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
    { title: 'Nama', key: 'name', dataIndex: 'name', align: 'left', sorter: true, },
    { title: 'Wilayah', key: 'kab', dataIndex: 'kab', align: 'center', sorter: true, },
    { title: 'Alamat', key: 'address', dataIndex: 'address', align: 'left', sorter: true, },
    { title: 'Maps', key: 'coordinate', dataIndex: 'coordinate', align: 'center', },
    { title: 'Subsector', key: 'subsectors', dataIndex: 'subsectors', align: 'center', },
    { title: 'Survey', key: 'surveys', dataIndex: 'surveys', align: 'center', },
    { title: 'Aksi', key: 'id', dataIndex: 'id', align: 'center', },
];

const itemsPerPage = ref(20);
const loading = ref(false);
const data = ref([]);
const pagination = ref({
    current: 1,
    pageSize: 10,
    total: 0,
    pageSizeOptions: [10, 20, 50],
});

const sort = ref<{ field?: string, order?: string, }>({
    field: undefined,
    order: undefined,
});
const openAddModal = ref(false);

const selectedKabFilters = ref<number>();
const kabFilters = ref<FilterOption[]>([]);
const loadingKabFilters = ref<boolean>(false);
const selectedKecFilters = ref<number>();
const kecFilters = ref<FilterOption[]>([]);
const loadingKecFilters = ref<boolean>(false);
const selectedDesFilters = ref<number>();
const desFilters = ref<FilterOption[]>([]);
const loadingDesFilters = ref<boolean>(false);

const kecFiltersForm = ref<FilterOption[]>([]);
const loadingKecFiltersForm = ref<boolean>(false);
const desFiltersForm = ref<FilterOption[]>([]);
const loadingDesFiltersForm = ref<boolean>(false);
const bsFiltersForm = ref<FilterOption[]>([]);
const loadingBsFiltersForm = ref<boolean>(false);

const subsectorFilter = ref();
const surveyFilter = ref();
const loadingMaster = ref<boolean>(false);

const confirmLoading = ref<boolean>(false);

const selectedSubsectorFilter = ref<number[]>([]);
const selectedSurveyFilter = ref<number[]>([]);
const keywordFilter = ref();

const formState = reactive<FormData>({
    id_sbr: '',
    name: '',
    kabupaten: undefined,
    kecamatan: undefined,
    desa: undefined,
    bs: undefined,
    address: '',
    subsectors: [],
    surveys: [],
});

const showAddModal = () => {
    openAddModal.value = true;
};

const clearFiltersFilter = (filters: Array<Ref>, values: Array<Ref>) => {
    filters.forEach(filter => filter.value = []);
    values.forEach(value => value.value = null);
};

const clearFiltersForm = (filters: Array<Ref>, type: string) => {
    filters.forEach(filter => filter.value = []);
    if (type == 'kab') {
        formState.kecamatan = undefined
        formState.desa = undefined
        formState.bs = undefined
    } else if (type == 'kec') {
        formState.desa = undefined
        formState.bs = undefined
    } else if (type == 'des') {
        formState.bs = undefined
    }
};

const handleFilterChange = (value: any, nextFilterFn: (id: string) => void, clearFiltersFn: () => void) => {
    clearFiltersFn();
    if (value) {
        nextFilterFn(value);
    }
};

const loadFilterOptions = async (url: string, targetFilter: Ref<FilterOption[]>, loadingFlag: Ref<boolean>) => {
    loadingFlag.value = true;
    try {
        const response = await makeRequest.get(url);
        targetFilter.value = response.data.data.map((item: any) => ({
            label: `[${item.short_code}] ${item.name}`,
            value: item.id
        }));
    } catch (error) {
        console.error(error);
    } finally {
        loadingFlag.value = false;
    }
};

const loadMaster = async () => {
    loadingMaster.value = true
    try {
        const subsectorsResponse = await makeRequest.get('subsectors');
        subsectorFilter.value = subsectorsResponse.data.data.map((item: any) => ({
            label: item.name,
            value: item.id
        }));

        const surveysResponse = await makeRequest.get('surveys');
        surveyFilter.value = surveysResponse.data.data.map((item: any) => ({
            label: item.name,
            value: item.id
        }));

    } catch (error) {
        console.error(error);
    } finally {
        loadingMaster.value = false
    }
};

const loadKabFilter = () => loadFilterOptions('kab', kabFilters, loadingKabFilters);

const handleKabFilterChange = (value: any) => {
    handleFilterChange(value, (value) => loadFilterOptions(`kec/${value}`, kecFilters, loadingKecFilters), () => clearFiltersFilter([kecFilters, desFilters], [selectedKecFilters, selectedDesFilters]),);
    loadCompanies();
};
const handleKecFilterChange = (value: any) => {
    handleFilterChange(value, (value) => loadFilterOptions(`des/${value}`, desFilters, loadingDesFilters), () => clearFiltersFilter([desFilters], [selectedDesFilters]));
    loadCompanies();
}

const handleKabFormChange = (value: any) => handleFilterChange(value, (value) => loadFilterOptions(`kec/${value}`, kecFiltersForm, loadingKecFiltersForm), () => clearFiltersForm([kecFiltersForm, desFiltersForm, bsFiltersForm], 'kab'),);
const handleKecFormChange = (value: any) => handleFilterChange(value, (value) => loadFilterOptions(`des/${value}`, desFiltersForm, loadingDesFiltersForm), () => clearFiltersForm([desFiltersForm, bsFiltersForm], 'kec'));
const handleDesFormChange = (value: any) => handleFilterChange(value, (value) => loadFilterOptions(`bs/${value}`, bsFiltersForm, loadingBsFiltersForm), () => clearFiltersForm([bsFiltersForm], 'des'));

function loadCompanies() {

    let page: number = pagination.value.current
    let itemsPerPage: number = pagination.value.pageSize

    let sortBy = []
    loading.value = true;
    let subsectorsUrl: string = ''
    if (selectedSubsectorFilter.value.length > 0) {
        subsectorsUrl = '&subsectors=' + JSON.stringify(selectedSubsectorFilter.value)
    }
    let surveysUrl: string = ''
    if (selectedSurveyFilter.value.length > 0) {
        surveysUrl = '&surveys=' + JSON.stringify(selectedSurveyFilter.value)
    }
    let kabUrl: string = ''
    if (selectedKabFilters.value) {
        kabUrl = '&kab=' + selectedKabFilters.value
    }
    let kecUrl: string = ''
    if (selectedKecFilters.value) {
        kecUrl = '&kec=' + selectedKecFilters.value
    }
    let desUrl: string = ''
    if (selectedDesFilters.value) {
        desUrl = '&des=' + selectedDesFilters.value
    }
    let keywordUrl: string = ''
    if (keywordFilter.value) {
        keywordUrl = '&keyword=' + keywordFilter.value
    }
    let sortUrl: string = ''
    if (sort.value.field && sort.value.order) {
        sortUrl = '&sortField=' + sort.value.field + '&sortOrder=' + sort.value.order
    }
    makeRequest.get('companies/?page=' + page + '&pageSize=' + itemsPerPage
        + subsectorsUrl + surveysUrl + kabUrl + kecUrl + desUrl + keywordUrl + sortUrl
    ).then(async response => {
        data.value = response.data.data;
        loading.value = false;
        pagination.value.total = response.data.meta.total
    }).catch((error) => {
        loading.value = false;
    });
}

function handleTableChange(newPagination: any, filters: any, sorter: any, extra: any) {
    pagination.value = newPagination
    sort.value = sorter
    loadCompanies();
}

const submitForm = async () => {
    confirmLoading.value = true;
    try {
        let formData = new FormData();
        formData.append('id_sbr', formState.id_sbr)
        formData.append('name', formState.name)
        formData.append('kab', `${formState.kabupaten}`)
        if (formState.kecamatan) {
            formData.append('kec', `${formState.kecamatan}`)
        }
        if (formState.desa) {
            formData.append('des', `${formState.desa}`)
        }
        if (formState.bs) {
            formData.append('bs', `${formState.bs}`)
        }
        formData.append('address', `${formState.address}`)
        formData.append('subsectors', JSON.stringify(formState.subsectors))
        formData.append('surveys', JSON.stringify(formState.surveys))

        await makeRequest.post('companies', formData);

        openAddModal.value = false;

        loadCompanies()
    } catch (error) {
        console.error(error);
    } finally {
        confirmLoading.value = false;
    }
};

const debouncedKeywordFilter = debounce((value: string) => {
    loadCompanies()
}, 500);

watch(keywordFilter, (newValue) => {
    debouncedKeywordFilter(newValue);
});

loadCompanies();
loadKabFilter()
loadMaster()

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
                <a-modal :confirm-loading="confirmLoading" @ok="submitForm" v-model:open="openAddModal"
                    title="Tambah Perusahaan">
                    <a-form labelAlign="left" class="mt-4" :model="formState" name="basic" :label-col="{ span: 8 }"
                        :wrapper-col="{ span: 16 }" autocomplete="off">
                        <a-form-item label="ID SBR" name="id_sbr">
                            <a-input placeholder="ID SBR" v-model:value="formState.id_sbr" />
                        </a-form-item>
                        <a-form-item label="Nama Perusahaan" name="name"
                            :rules="[{ required: true, message: 'Nama Perusahaan kosong' }]">
                            <a-input v-model:value="formState.name" placeholder="Nama Perusahaan" />
                        </a-form-item>
                        <a-form-item label="Kabupaten" name="kabupaten"
                            :rules="[{ required: true, message: 'Kabupaten belum terisi' }]">
                            <a-select ref="select" @change="handleKabFormChange" v-model:value="formState.kabupaten"
                                :loading="loadingKabFilters" class="mb-1" allowClear style="width: 100%"
                                placeholder="Kabupaten" :options="kabFilters"></a-select>
                        </a-form-item>
                        <a-form-item label="Kecamatan" name="kecamatan">
                            <a-select ref="select" @change="handleKecFormChange" v-model:value="formState.kecamatan"
                                :loading="loadingKecFiltersForm" class="mb-1" allowClear style="width: 100%"
                                placeholder="Kecamatan" :options="kecFiltersForm"></a-select>
                        </a-form-item>
                        <a-form-item label="Desa" name="desa">
                            <a-select ref="select" @change="handleDesFormChange" v-model:value="formState.desa"
                                class="mb-1" allowClear :loading="loadingDesFiltersForm" style="width: 100%"
                                placeholder="Desa" :options="desFiltersForm"></a-select>
                        </a-form-item>
                        <a-form-item label="Blok Sensus" name="bs">
                            <a-select ref="select" v-model:value="formState.bs" class="mb-1" allowClear
                                :loading="loadingBsFiltersForm" style="width: 100%" placeholder="Blok Sensus"
                                :options="bsFiltersForm"></a-select>
                        </a-form-item>
                        <a-form-item label="Alamat" name="address">
                            <a-textarea v-model:value="formState.address" placeholder="Alamat Perusahaan"
                                :auto-size="{ minRows: 3, maxRows: 5 }" />
                        </a-form-item>
                        <a-form-item label="Subsektor" name="subsectors">
                            <a-select v-model:value="formState.subsectors" allowClear mode="multiple"
                                style="width: 100%" placeholder="Subsektor" :options="subsectorFilter"></a-select>

                        </a-form-item>
                        <a-form-item label="Survey" name="surveys">
                            <a-select v-model:value="formState.surveys" allowClear mode="multiple" style="width: 100%"
                                placeholder="Survey" :options="surveyFilter"></a-select>
                        </a-form-item>
                    </a-form>
                </a-modal>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" sm="12" md="6" lg="3">
                <a-input-search v-model:value="keywordFilter" allowClear placeholder="Pencarian" />
            </v-col>
            <v-col cols="12" sm="12" md="6" lg="3">
                <a-select @change="loadCompanies" v-model:value="selectedSubsectorFilter" allowClear mode="multiple"
                    style="width: 100%" placeholder="Subsector" :options="subsectorFilter"></a-select>
            </v-col>
            <v-col cols="12" sm="12" md="6" lg="3">
                <a-select @change="loadCompanies" v-model:value="selectedSurveyFilter" allowClear mode="multiple"
                    style="width: 100%" placeholder="Survey" :options="surveyFilter"></a-select>
            </v-col>
            <v-spacer></v-spacer>
        </v-row>
        <v-row class="mb-3">
            <v-col cols="12" sm="12" md="4" lg="2">
                <a-select @change="handleKabFilterChange" v-model:value="selectedKabFilters"
                    :loading="loadingKabFilters" allowClear style="width: 100%" placeholder="Kabupaten"
                    :options="kabFilters"></a-select>
            </v-col>
            <v-col cols="12" sm="12" md="4" lg="2">
                <a-select @change="handleKecFilterChange" v-model:value="selectedKecFilters"
                    :loading="loadingKecFilters" allowClear style="width: 100%" placeholder="Kecamatan"
                    :options="kecFilters"></a-select>
            </v-col>
            <v-col cols="12" sm="12" md="4" lg="2">
                <a-select @change="loadCompanies" v-model:value="selectedDesFilters" :loading="loadingDesFilters"
                    allowClear style="width: 100%" placeholder="Desa" :options="desFilters"></a-select>
            </v-col>
            <v-spacer></v-spacer>
        </v-row>
        <a-table :scroll="{ x: 1000, y: 1000 }" :loading="loading" :columns="columns" :data-source="data"
            :pagination="pagination" @change="handleTableChange">
            <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'subsectors'">
                    <a-tag v-for="subsector in record.subsectors" :key="subsector" :color="'green'">
                        {{ subsector.name }}
                    </a-tag>
                </template>
                <template v-if="column.key === 'surveys'">
                    <a-tag v-for="survey in record.surveys" :key="survey" :color="'green'">
                        {{ survey.name }}
                    </a-tag>
                </template>
                <template v-if="column.key === 'kab'">
                    <a-space direction="vertical">
                        <a-typography-text>[{{ record.kab.short_code }}] {{ record.kab.name }}</a-typography-text>
                        <a-typography-text v-if="record.kec">[{{ record.kec.short_code }}] {{ record.kec.name
                            }}</a-typography-text>
                        <a-typography-text v-if="record.des">[{{ record.des.short_code }}] {{ record.des.name
                            }}</a-typography-text>
                        <a-typography-text v-if="record.bs">{{ record.bs.name }}</a-typography-text>
                    </a-space>
                </template>
            </template>
        </a-table>
    </UiParentCard>
</template>
