<template>
    <ConfirmDialog />

    <div class="column is-12">
        <VCard>
            <div class="column c-title pt-2 mb-0">
                <div class="columns p-2">
                    <div class="column is-10">
                        <label class="title-page">Daftar Rencana Usulan</label>
                        <label for="">Rencana Usulan Permintaan Barang</label>
                    </div>
                    <div class="column pr-0">
                        <VButton type="button" icon="feather:x-circle" RouterLink
                            :to="{ name: 'module-ipsrs-rencana-usulan-permintaan-barang' }"
                            class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
                            Tambah Rencana Usulan
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
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField label="Periode Order" style="margin-bottom: 6px;" />
                                <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-9">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-3">
                                        <VField label="Search">
                                            <VControl icon="feather:search">
                                                <input v-model="item.search" class="input" placeholder="Search No Order..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-1 mt-5 pt-4" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchOrder()"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <Column expander style="width: 5rem" />
                    <Column field="no" header="No"></Column>
                    <Column field="tglorder" header="tgl Usulan"></Column>
                    <Column field="tglkebutuhan" header="Tgl Kebutuhan"></Column>
                    <Column field="noorder" header="No Usulan"></Column>
                    <Column field="keterangan" header="Jenis Usulan"></Column>
                    <Column field="koordinator" header="Koordinator Barang"></Column>
                    <Column field="ruangan" header="Unit Pengusul"></Column>
                    <Column field="ruangantujuan" header="Unit Tujuan"></Column>
                    <Column field="penanggungjawab" header="Penangung Jawab"></Column>
                    <Column field="mengetahui" header="Mengetahui"></Column>
                    <Column field="statusverif" header="Verifikasi Pengelola Urusan"
                        style="text-align:center;font-weight: bold;" />
                    <Column :exportable="false" header="##" style="text-align: center;">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle
                                outlined raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                            </VIconButton>
                            <OverlayPanel ref="op">
                                <VButtons>
                                    <VButton color="primary" outlined @click="verif(selected)"
                                        :loading="dataSource.loadVerif">
                                        <i class="fas fa-check-circle mr-2" aria-hidden="true"></i> Verifikasi
                                    </VButton>
                                    <VButton color="info" outlined @click="gotoPageEdit(selected)">
                                        <i class="fas fa-pen-square mr-2" aria-hidden="true"></i> Edit Renacan Usulan
                                    </VButton>
                                    <VButton color="danger" raised @click="dialogConfirm(selected)"
                                        :loading="dataSource.loadDelete">
                                        <i class="fas fa-times-circle mr-2" aria-hidden="true"></i> Batal Rencana Usulan
                                    </VButton>
                                    <!-- <VButton color="black" outlined>
                                        Buat Rencana Jadi Usulan
                                    </VButton> -->
                                </VButtons>
                            </OverlayPanel>
                        </template>
                    </Column>
                    <template #expansion="slotProps">
                        <div class="p-3">
                            <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple">
                                <Column field="no" header="No" />
                                <Column field="tglkebutuhan" header="Tgl Kebutuhan" style="min-width:40px" />
                                <Column field="namaproduk" header="Produk" style="min-width:150px" />
                                <Column field="spesifikasi" header="Spesifikasi" style="min-width:200px" />
                                <Column field="satuanstandar" header="Satuan" style="min-width:80px" />
                                <Column field="qtyproduk" style="text-align: center;" header="Qty" />
                                <Column field="hargasatuan" style="text-align: right;" header="Harga Satuan" />
                                <Column field="total" style="text-align: right;min-width:80px" header="Total" />
                            </DataTable>
                        </div>
                    </template>
                </DataTable>
            </div>
        </VCard>
    </div>

</template>

<script setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import OverlayPanel from 'primevue/overlaypanel';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as qzService from '/@src/utils/qzTrayService'


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';

useHead({
    title: 'Daftar Penjualan Obat Bebas - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const router = useRouter()
const confirm = useConfirm()
const dataSource: any = ref([])
const detailResep: any = ref([])
const op = ref();
const selected: any = ref({})
const modalDetail: any = ref(false)
const modalRetur: any = ref(false)
const expandedRows = ref();
let d_ruangan: any = ref([])
let loadSearch: any = ref(false)

const fetchOrder = async () => {

    let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let produkfk = item.value.produk ? `&produkfk=${item.value.produk.value}` : ''
    let search = item.value.search ? `&search=${item.value.search}` : ''

    loadSearch.value = true
    await useApi().get(`/iprs/get-daftar-rencana-usulan-permintaan?${tglAwal}${tglAkhir}${produkfk}${search}`).then((response: any) => {
        response.forEach((element: any, i: any) => {
            expandedRows.value = element.details.forEach((data: any, i: any) => {
                data.no = i + 1
                data.tglkebutuhan = data.tglkebutuhan ? H.formatDate(data.tglkebutuhan, 'DD/MM/YYYY') : ''
            })
            element.no = i + 1
            element.statusverif = element.noverifpengelolaurusan != null ? 'Telah Diverifikasi' : ''
            element.tglorder = H.formatDate(element.tglorder, 'DD/MM/YYYY')
            element.tglkebutuhan = H.formatDate(element.tglkebutuhan, 'DD/MM/YYYY')
        });
        console.log(response)
        dataSource.value = response
        loadSearch.value = false
    }).catch((err: any) => {
        loadSearch.value = false
    })
}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda yakin Membatalkan data ini ?',
        header: 'Konfirmasi Batal Rencana Usulan Permintaan',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteRencanUsulan(e)
        },
        reject: () => { },
    })
}

const verif = async (e:any)=>{

    let data =
    {
        verifikasifk: e.norecverif,
        norec: e.norec,
        norecrealisasi: e.norecrealisasi,
        tglusulan: e.tglusulan,
        nousulan: e.nousulan,
    }
    dataSource.value.loadVerif = true
    await useApi().post('/iprs/verifikasi-data-rencana-usulan', data).then((response)=>{
        dataSource.value.loadVerif = false
        fetchOrder()
    }).catch((e:any)=>{
        dataSource.value.loadVerif = false
    })
}

const deleteRencanUsulan = async (e: any) => {

    let data = {
        norec: e.norec,
        norecrealisasi: e.norecrealisasi,
        tglusulan: e.tglusulan,
        nousulan: e.nousulan,
    }
    dataSource.value.loadDelete = true
    await useApi().post('/iprs/hapus-data-rencana-usulan', data).then((response) => {
        dataSource.value.loadDelete = false
        fetchOrder()
    }).catch((err: any) => {
        dataSource.value.loadDelete = false
    })
}

const gotoPageEdit = (e: any) => {
    router.push({
        name: 'module-ipsrp-rencana-usulan-permintaan-barang',
        query: {
            norecsp: e.norec,
        },
    })
}


const toggle = (event: any, e: any) => {
    console.log(event)
    op.value.toggle(event);
    selected.value = e
}

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
