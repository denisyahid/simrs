<template>
    <ConfirmDialog />

    <div class="column is-12">
        <VCard>
            <div class="column c-title pt-2 mb-0">
                <div class="columns p-2">
                    <div class="column is-10">
                        <label class="title-page">Detail Pemeriksaan</label>
                    </div>
                    <div class="column pr-0">
                        <VButton color="primary" raised @click="add()">
                            <span class="icon">
                                <i aria-hidden="true" class="fas fa-plus"></i>
                            </span>
                            <span> Tambah Data</span>
                        </VButton>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-4">
                        <VField label="Detail Pemeriksaan" style="margin-bottom: 10px;" />
                        <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="item.detailpemeriksaan" placeholder="Detail Pemeriksaan"
                                v-on:keyup.enter="fetchOrder()" />
                        </VControl>
                    </div>

                    <div class="column is-4">
                        <VField label="Nama Pemeriksaan"  class="is-autocomplete-select">
                            <VControl icon="feather:search">
                                <AutoComplete v-model="item.namaObat" :suggestions="d_Layanan" @complete="fetchLayanan($event)"
                                    :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                     :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                                    placeholder="Cari Nama" />
                            </VControl>
                        </VField>
                        <!-- <VField class="is-autocomplete-select" label="Nama Layanan">
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.produk" :options="d_produk2" :optionLabel="'namaproduk'"
                                        placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                                </VControl>
                        </VField> -->
                    </div>
                    <div class="column btn-search">
                        <VButton type="button" icon="feather:search" :loading="loadSearch" @click="fetchOrder()">
                            Cari Data
                        </VButton>
                    </div>

                </div>
            </div>

            <div class="column is-12 mt-5">
                <DataTable :value="dataSource" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="loadSearch"
                    class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                    sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <Column expander style="width: 5rem" />
                    <Column field="no" header="No"></Column>
                    <Column field="detailjenisproduk" header="Jenis Pemeriksaan"></Column>
                    <Column field="namaproduk" header="Nama Layanan"></Column>
                    <Column field="detailpemeriksaan" header="Detail Pemeriksaan"></Column>
                    <Column field="satuanstandar" header="Satuan Hasil"></Column>
                    <Column field="tipedatahasil" header="Tipe Hasil"></Column>
                    <template #expansion="slotProps">
                        <div class="p-3">
                            <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple">
                                <Column field="no" header="No" />
                                <Column field="jeniskelamin" header="Jenis Kelamin" />
                                <Column field="ageunitbase" header="Kelompok Umur" />
                                <Column field="refrange" header="Nilai Normal" style="text-align: center;" />
                            </DataTable>
                        </div>



                    </template>
                </DataTable>
            </div>
        </VCard>
    </div>
    <VModal :open="modalTambah" title="Tambah Detail Pemeriksaan" actions="right" size="big" @close="modalTambah = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Nama Layanan</VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.produkfk" :options="d_produk2" :optionLabel="'namaproduk'"
                                        placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Detail Pemeriksaan">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.detailpemeriksaan" placeholder="Refrange" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Satuan Hasil</VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.satuanstandarfk" :options="d_satuan"
                                        :optionLabel="'satuanstandar'" placeholder="Pilih data" style="width: 100%;"
                                        showClear :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField label="Tipe Hasil">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.tipedatahasil" placeholder="Tipe Hasil" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField label="No. Urut">
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.nourut" placeholder="No. Urut" />
                                </VControl>
                            </VField>
                        </div>
                        <VCard>
                            <div class="column is-12">
                                <div class="columns is-multiline p-1">
                                    <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                                        <VCard class="is-grey">
                                            <div class="columns is-multiline p-1">

                                                <div class="column is-2">
                                                    <VField class="is-rounded-select is-autocomplete-select">
                                                        <VLabel>Jenis Kelamin</VLabel>
                                                        <VControl icon="feather:search">
                                                            <Multiselect mode="single" v-model="item.jeniskelaminfk"
                                                                :options="d_jk" placeholder="Pilih data" :searchable="true"
                                                                :attrs="{ id }" autocomplete="off" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-1">
                                                    <VField label="Nilai Normal (Min - Max) ">
                                                        <VControl>
                                                            <VInput type="text" v-model="item.rangemin" placeholder="Min" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                                <div class="column is-1">
                                                    <VField style="margin-top: 23px;">
                                                        <VControl>
                                                            <VInput type="text" v-model="item.rangemax" placeholder="Max" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                                <div class="column is-1">
                                                    <VField label="Kelompok Umur (Min - Max)">
                                                        <VControl>
                                                            <VInput type="text" v-model="item.agemin" placeholder="Min" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-1">
                                                    <VField style="margin-top: 23px;">
                                                        <VControl>
                                                            <VInput type="text" v-model="item.agemax" placeholder="Max" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-2">
                                                    <VField label="Berdasarkan (Hari / Tahun)">
                                                        <VControl icon="feather:bookmark">
                                                            <VInput type="text" v-model="item.ageunit"
                                                                placeholder="Age Unit" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-1 mt-3" style="margin-top: 1rem;">
                                                    <VIconButton v-if="index > 0" outlined type="button" raised circle
                                                        class="is-pulled-right" icon="feather:trash"
                                                        @click="removeItem(index)" color="danger">
                                                    </VIconButton>
                                                </div>
                                                <div class="column is-1 is-flex mt-3" style="margin-top: 1rem;">
                                                    <VButton type="button" rounded outlined color="info" raised
                                                        icon="feather:plus" @click="addNewItem()"> Tambah
                                                    </VButton>
                                                </div>
                                            </div>
                                        </VCard>
                                    </div>
                                </div>
                            </div>
                        </VCard>
                    </div>
                </div>
            </form>
        </template>
        <template #action>
            <VButton icon="feather:plus" @click="tambah()" :loading="isLoading" color="primary" raised>Simpan
            </VButton>
        </template>
    </VModal>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';

useHead({
    title: 'Detail Pemeriksaan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const router = useRouter()
const confirm = useConfirm()
const dataSource: any = ref([])
const detailResep: any = ref([])
const modalDetail: any = ref(false)
const expandedRows = ref();
let d_ruangan: any = ref([])
let d_Layanan: any = ref([])
let d_produk: any = ref([])
let d_produk2: any = ref([])
let d_jk: any = ref([])
let d_satuan: any = ref([])
let loadSearch: any = ref(false)
const isLoading = ref(false)
const modalTambah: any = ref(false)

const listItem: any = ref([
    {
        jeniskelaminfk: [],
        rangemin: [],
        rangemax: [],
        agemin: [],
        agemax: [],
        ageunit: [],
    }
])
function addNewItem() {
    listItem.value.push({
        jeniskelaminfk: [],
        rangemin: [],
        rangemax: [],
        agemin: [],
        agemax: [],
        ageunit: null,
    });
}
function removeItem(index: any) {
    listItem.value.splice(index, 1)
}

const fetchOrder = async () => {

    let produk = item.value.namaObat ? `&produk=${item.value.namaObat.idpro}` : ''
    let detailPem = item.value.detailpemeriksaan ? `&detailPem=${item.value.detailpemeriksaan}` : ''

    await useApi().get(`/laboratorium/get-detail-pemeriksaan?${produk}${detailPem}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            expandedRows.value = element.details.forEach((data: any, i: any) => {
                data.no = i + 1
            })
            element.no = i + 1
        });
        dataSource.value = response.data
        loadSearch.value = false
    }).catch((err: any) => {
        loadSearch.value = false
    })
}

const add = () => {

    modalTambah.value = true
}

const tambah = async () => {
    var objSave = {
        'produkdetaillaborat':
        {
            'id': item.value.id ? item.value.id : '',
            'produkfk': item.value.produkfk.idpro,
            'detailpemeriksaan': item.value.detailpemeriksaan,
            'satuanstandarfk': item.value.satuanstandarfk.id,
            'tipedatahasil': item.value.tipedatahasil ? item.value.tipedatahasil : null,
            'nourut': item.value.nourut ? item.value.nourut : null,

            'details': listItem.value
        },
    }
    isLoading.value = true
    await useApi().post(
        `laboratorium/save-detail-pemeriksaan`, objSave).then((response: any) => {
            isLoading.value = false
            clear()
            fetchOrder()
        }, (error) => {
            isLoading.value = false

            // console.log(error)
        })
}

const getListCombo = async () => {
    const response = await useApi().get(`/laboratorium/get-dd-layanan`)
    d_produk.value = response.layanan
    d_satuan.value = response.satuanstandar
    d_jk.value = response.jeniskelamin.map((e: any) => { return { label: e.jeniskelamin, value: e.id, default: e } })
    d_produk2.value = response.layanan
}

const clear = () => {
    item.value.produkfk.idpro = ''
    item.value.detailpemeriksaan = ''
    item.value.satuanstandarfk = ''
    item.value.tipedatahasil = ''
    item.value.nourut = ''
    listItem.value = ''

    modalTambah.value = false
}

const fetchLayanan = async (filter: any) => {
    let namaproduk = filter ? `?namaproduk=${filter.query}` : ''
    useApi().get(`laboratorium/get-dd-layanan${namaproduk}`).then((response) => {
        d_Layanan.value = response.layanan
    })
}

getListCombo()
fetchOrder()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 8px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
    margin-top: 14px;
}
</style>
