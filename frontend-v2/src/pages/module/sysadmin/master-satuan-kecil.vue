<template>
    <ConfirmDialog />
    <VCard>
        <!-- <h3 class="title is-5 mb-2 mr-1">Daftar Produk</h3> -->
        <VTabs align="centered" selected="satuankecil" :tabs="[
            { label: 'Satuan Kecil', value: 'satuankecil' },
            { label: 'Satuan Besar', value: 'satuanbesar' },]">
            <template #tab="{ activeValue }">
                <p v-if="activeValue === 'satuankecil'">
                    <VCard>
                        <div class="columns is-multiline">

                            <div class="column is-8">
                                <div class="user-grid-toolbar">
                                    <VControl icon="feather:search">
                                        <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
                                    </VControl>
                                    <div class="column is-6 switch-filter">
                                        <VControl>
                                            <InputSwitch v-model="item.aktif" @change="fetchData()" />
                                        </VControl>
                                        <span>Aktif</span>
                                    </div>
                                    <div class="buttons ml-4">
                                        <VField v-slot="{ id }" class="is-icon-select">
                                            <VControl class="mr-0">
                                                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View"
                                                    label="name" :options="d_View" :searchable="true" track-by="name"
                                                    mode="single" @select="changeView(selectView)" autocomplete="off">
                                                    <template #singlelabel="{ value }">
                                                        <div class="multiselect-single-label">
                                                            <div class="select-label-icon-wrap">
                                                                <i :class="value.icon"></i>
                                                            </div>
                                                            <span class="select-label-text">
                                                                {{ value.name }}
                                                            </span>
                                                        </div>
                                                    </template>
                                                    <template #option="{ option }">
                                                        <div class="select-option-icon-wrap">
                                                            <i :class="option.icon"></i>
                                                        </div>
                                                        <span class="select-option-text">
                                                            {{ option.name }}
                                                        </span>
                                                    </template>
                                                </Multiselect>
                                            </VControl>
                                        </VField>

                                    </div>
                                </div>

                                <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
                                    <DataTable :value="dataSourcefiltered" lazy :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple" :loading="isLoading"
                                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"  @page="onPage($event)" ref="dt"
                                    :totalRecords="totalRecords" dataKey="id">
                                        <Column field="no" header="#"></Column>
                                        <Column field="satuankecil" header="Satuan Kecil" :sortable="true"></Column>
                                        <Column field="namadepartemen" header="Departemen" :sortable="true"></Column>
                                        <Column field="kodeexternal" header="Kode" :sortable="true"></Column>
                                        <Column field="status" header="status" :sortable="true"></Column>
                                        <Column :exportable="false" header="Action" style="text-align:center;">
                                            <template #body="slotProps">
                                                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info"
                                                    circle outlined raised v-tooltip.top="'Edit'"
                                                    @click="edit(slotProps.data)">
                                                </VIconButton>
                                                <VIconButton type="button" v-if="item.aktif == true" icon="fas fa-trash"
                                                    class="mr-3" color="danger" circle outlined raised
                                                    v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                                                </VIconButton>
                                                <VIconButton type="button" v-else icon="fas fa-trash" class="mr-3"
                                                    color="danger" circle outlined raised v-tooltip.top="'Hapus'" disabled>
                                                </VIconButton>
                                            </template>
                                        </Column>
                                    </DataTable>
                                </div>
                                <div class="tile-grid tile-grid-v1" v-else-if="selectView == 'grid'">
                                    <div class="columns is-multiline" v-if="isLoading">
                                        <div class="column is-6" v-for="key in 6" :key="key">
                                            <div class="tile-grid-item">
                                                <VFlexTableCell :column="{ grow: true, media: true }">
                                                    <VPlaceloadAvatar size="medium" />
                                                    <VPlaceloadText :lines="2" width="70%" last-line-width="30%"
                                                        class="mx-2" />
                                                </VFlexTableCell>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-list-inner" v-else-if="item.dataLength === 0">
                                        <VPlaceholderSection title="Not found"
                                            subtitle="There is no data that match your query." class="my-6">
                                            <template #image>
                                                <img class="light-image"
                                                    src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                                                <img class="dark-image"
                                                    src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                    alt="" />
                                            </template>
                                        </VPlaceholderSection>
                                    </div>
                                    <TransitionGroup v-else name="list" tag="div" class="columns is-multiline">
                                        <!--Grid item-->
                                        <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-6">
                                            <div class="tile-grid-item">
                                                <div class="tile-grid-item-inner">
                                                    <VAvatar size="medium" picture="/images/avatars/icon/ic-scale.png"
                                                        color="primary" squared bordered />
                                                    <div class="meta">
                                                        <span class="dark-inverted">{{ item.satuankecil }}</span>
                                                        <span> Kelompok Produk : {{ item.kelompokproduk }}</span>
                                                    </div>
                                                    <VTag :color="item.status_c" :label="item.status"
                                                        style="margin-left:25px" />
                                                    <VDropdown icon="feather:more-vertical" spaced right>
                                                        <template #content>
                                                            <a role="menuitem" class="dropdown-item is-media"
                                                                @click="detail(item)">
                                                                <div class="icon">
                                                                    <i class="iconify" data-icon="feather:bookmark"
                                                                        aria-hidden="true"></i>
                                                                </div>
                                                                <div class="meta">
                                                                    <span>Detail</span>
                                                                    <span>Untuk melihat data </span>
                                                                </div>
                                                            </a>
                                                            <a role="menuitem" class="dropdown-item is-media"
                                                                @click="edit(item)">
                                                                <div class="icon">
                                                                    <i class="iconify" data-icon="feather:edit"
                                                                        aria-hidden="true"></i>
                                                                </div>
                                                                <div class="meta">
                                                                    <span>Edit</span>
                                                                    <span>Untuk merubah data </span>
                                                                </div>
                                                            </a>
                                                            <a role="menuitem" class="dropdown-item is-media"
                                                                v-if="item.statusenabled == true"
                                                                @click="dialogConfirm(item)">
                                                                <div class="icon">
                                                                    <i aria-hidden="true"
                                                                        class="lnil lnil-trash-can-alt"></i>
                                                                </div>
                                                                <div class="meta">
                                                                    <span>Remove</span>
                                                                    <span>Hapus Data dari Daftar</span>
                                                                </div>
                                                            </a>
                                                        </template>
                                                    </VDropdown>
                                                </div>
                                            </div>
                                        </div>

                                        <VFlexPagination v-model:current-page="currentPage.page"
                                            :item-per-page="currentPage.limit"
                                            :total-items="dataSource.total < 5 ? dataSource.total : 50"
                                            :max-links-displayed="5">
                                            <template #before-pagination>
                                            </template>
                                            <template #before-navigation>
                                                <VFlex class="mr-4 mt-1" column-gap="1rem">
                                                    <VField>

                                                    </VField>
                                                    <VField>
                                                        <VControl>
                                                            <div class="select is-rounded">
                                                                <select v-model="currentPage.limit">
                                                                    <option :value="1">1 results per page</option>
                                                                    <option :value="5">5 results per page</option>
                                                                    <option :value="6">6 results per page</option>
                                                                    <option :value="10">10 results per page</option>
                                                                    <option :value="15">15 results per page</option>
                                                                    <option :value="25">25 results per page</option>
                                                                    <option :value="50">50 results per page</option>
                                                                    <option :value="100">100 results per page</option>
                                                                </select>
                                                            </div>
                                                        </VControl>
                                                    </VField>
                                                </VFlex>
                                            </template>
                                        </VFlexPagination>

                                    </TransitionGroup>
                                    <!-- <Paginator :rows="1" :totalRecords="item.countAll"
                                        template="FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" @click="cek(item)" /> -->
                                </div>
                            </div>

                            <div class="column is-4">
                                <img src="/images/avatars/label/satuan-kecil.png" class="kecil">
                                <VCard>
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <h3 v-if="item.id" class="title is-6 mb-2 mr-1">
                                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                                                Edit Data
                                            </h3>
                                            <h3 v-else class="title is-6 mb-2 mr-1">
                                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                                                Tambah Data
                                            </h3>
                                        </div>
                                        <div class="column is-12">
                                            <VField label="Satuan Kecil">
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.satuankecil"
                                                        placeholder="Satuan Kecil" class="is-rounded" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12" v-if="item.id" style="display:flex; padding: 0px;">
                                            <VField class="column is-8 is-rounded-select is-autocomplete-select">
                                                <VLabel>Departemen</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectdepartemenfk"
                                                        :options="d_Departemen" placeholder="Pilih data"
                                                        :searchable="true" />
                                                </VControl>
                                            </VField>

                                            <VField class="column is-4">
                                                <VLabel class="ml-2">Aktivasi</VLabel>
                                                <VControl class="is-pulled-right">
                                                    <VSwitchBlock style="padding-top:3px" v-model="item.statusenabled"
                                                        label="Aktif" color="danger" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12" v-else>
                                            <VField class="is-rounded-select is-autocomplete-select">
                                                <VLabel>Departemen</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectdepartemenfk"
                                                        :options="d_Departemen" placeholder="Pilih data"
                                                        :searchable="true" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField class="is-rounded-select is-autocomplete-select">
                                                <VLabel>Kelompok Produk</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectkelompokprodukfk"
                                                        :options="d_KelompokProduk" placeholder="Pilih data"
                                                        :searchable="true" />
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div v-if="item.id" class="column is-12">
                                            <VButton @click="save()" :loading="isLoadingBtn" type="button"
                                                icon="feather:edit" class="is-fullwidth mr-3" color="info" raised>
                                                Update Data
                                            </VButton>
                                            <VButton @click="clear()" type="button" icon="feather:x-circle"
                                                class="is-fullwidth is-outlined is-warning mt-3" raised>
                                                Batal Edit
                                            </VButton>
                                        </div>
                                        <div v-else class="column is-12">
                                            <VButton @click="save()" :loading="isLoadingBtn" type="button"
                                                icon="feather:save" class="is-fullwidth mr-3" color="success" raised>
                                                Simpan Data
                                            </VButton>
                                        </div>
                                    </div>
                                </VCard>
                            </div>

                        </div>
                        <VModal :open="modalDetail" title="Detail Satuan Kecil" actions="right"
                            @close="modalDetail = false, clear()">
                            <template #content>
                                <form class="modal-form">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <VField label="Satuan Kecil">
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.satuankecil"
                                                        placeholder="Satuan Kecil" class="is-rounded" readonly
                                                        style="cursor:pointer" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField label="Departemen">
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.namadepartemen"
                                                        placeholder="Departemen" class="is-rounded" readonly
                                                        style="cursor:pointer" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField label="Kelompok Produk">
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.kelompokproduk"
                                                        placeholder="Kelompok Produk" class="is-rounded" readonly
                                                        style="cursor:pointer" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField label="Kode External">
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.kodeexternal"
                                                        placeholder="Kode External" class="is-rounded" readonly
                                                        style="cursor:pointer" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField label="Status Aktivasi">
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.status" placeholder="Status Aktivasi"
                                                        class="is-rounded" readonly style="cursor:pointer" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </form>
                            </template>

                        </VModal>
                    </VCard>
                </p>


                <p v-else-if="activeValue === 'satuanbesar'">
                    <MasterSatuanBesar></MasterSatuanBesar>
                </p>
            </template>

        </VTabs>
    </VCard>
</template>
    
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import InputSwitch from 'primevue/inputswitch'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import MasterSatuanBesar from './master-satuan-besar.vue'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'


import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
    title: 'Satuan Kecil - ' + import.meta.env.VITE_PROJECT,
})

onMounted(() => {
    isLoading.value = true;

    lazyParams.value = {
        first: 0,
        rows: dt.value != undefined ? dt.value.rows : 5,
        sortField: null,
        sortOrder: null,
        filters: filters.value
    };

    fetchData();
});

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let dataSource: any = ref([])
const route = useRoute()
const router = useRouter()
const confirm = useConfirm()
const item: any = ref({ aktif: true })
const d_Departemen = ref([])
const d_KelompokProduk = ref([])
const modalDetail = ref(false)
const lazyParams: any = ref({});
const dt = ref();
const totalRecords = ref(0);

const d_View = [
    {
        name: 'Grid View',
        value: 'grid',
        icon: 'fas fa-id-card-alt',
    },
    {
        name: 'List View',
        value: 'list',
        icon: 'fas fa-list',
    },
]
const selectView: any = ref()
selectView.value = 'grid'

let isLoadingBtn: any = ref(false)
let isLoading: any = ref(false)

const filters = ref('')

const currentPage: any = ref({
    limit: 5,
    rows: 50,
})

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})

const dataSourcefiltered = computed(() => {

    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.satuankecil.match(new RegExp(filters.value, 'i'))
        )
    })
})



const fetchData = async () => {
    console.log(lazyParams.value)
    isLoading.value = true
    let statusEnabled = item.value.aktif ? 'statusenabled=' + item.value.aktif : 'statusenabled=' + item.value.aktif
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = offset * limit - limit
    let rows: any = currentPage.value.rows

    if (selectView.value == 'list') {
        if (lazyParams.value) {
            rows = lazyParams.value.rows
            offset = lazyParams.value.page
            limit = lazyParams.value.rows
        }
    }

    await useApi().get(`/sysadmin/master-satuan-kecil?${statusEnabled}&offset=${offset}&limit=${limit}$rows=${rows}&total=true`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        })
        dataSource.value = response.data
        dataSource.value.total = response.total
        isLoading.value = false
        totalRecords.value = response.total
    })
}

const loadDropdown = async () => {
    const response = await useApi().get('sysadmin/master-satuan-kecil/select-item')
    d_Departemen.value = response.departemen.map((e: any) => {
        return { label: e.namadepartemen, value: e.id }
    })
    d_KelompokProduk.value = response.kelompokproduk.map((e: any) => {
        return { label: e.kelompokproduk, value: e.id }
    })
}

const save = async () => {
    if (!item.value.satuankecil) {
        useToaster().error('Satuan Kecil harus di isi')
        return
    }
    if (!item.value.objectdepartemenfk) {
        useToaster().error('Kondisi Pasien harus di isi')
        return
    }
    if (!item.value.objectkelompokprodukfk) {
        useToaster().error('Kondisi Pasien harus di isi')
        return
    }
    var objSave =
    {
        'satuankecil': {
            'id': item.value.id ? item.value.id : '',
            'satuankecil': item.value.satuankecil,
            'statusenabled': item.value.statusenabled,
            'objectdepartemenfk': item.value.objectdepartemenfk,
            'objectkelompokprodukfk': item.value.objectkelompokprodukfk
        }
    }
    isLoadingBtn.value = true
    await useApi().post(
        `/sysadmin/master-satuan-kecil/save`, objSave).then((response: any) => {
            isLoadingBtn.value = false
            clear()
            fetchData()
        }, (error) => {
            isLoadingBtn.value = false
            // console.log(error)
        })
}

const deleteItem = async (e: any) => {
    isLoading.value = true
    await useApi().post(
        `/sysadmin/master-satuan-kecil/delete`, { 'id': e.id }).then((response: any) => {
            clear()
            fetchData()
            isLoading.value = false
        }, (error) => {
        })
}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteItem(e)

        },
        reject: () => { },
    })
}

const changeView = (e: any) => {
    selectView.value = e
}

const edit = (e: any) => {
    item.value.id = e.id
    item.value.satuankecil = e.satuankecil
    item.value.statusenabled = e.statusenabled
    item.value.objectdepartemenfk = e.objectdepartemenfk
    item.value.objectkelompokprodukfk = e.objectkelompokprodukfk
}
const detail = (e: any) => {
    item.value.id = e.id
    item.value.satuankecil = e.satuankecil
    item.value.kodeexternal = e.kodeexternal
    item.value.status = e.status
    item.value.kelompokproduk = e.kelompokproduk
    item.value.namadepartemen = e.namadepartemen
    modalDetail.value = true
}

const clear = () => {
    item.value.id = ''
    item.value.satuankecil = ''
    item.value.objectdepartemenfk = ''
    item.value.objectkelompokprodukfk = ''
}

const onPage = (event: any) => {
    lazyParams.value = event;
    fetchData();
};

watch(currentPage.value, () => {
    fetchData()
})

fetchData()
loadDropdown()
</script>
  
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';</style>
