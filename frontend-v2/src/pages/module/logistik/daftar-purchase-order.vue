<template>
    <ConfirmDialog />

    <div class="column is-12">
        <VCard>
            <div class="column c-title pt-2 mb-0">
                <div class="columns p-2">
                    <div class="column is-10 pt-0">
                        <label class="title-page">Daftar Purchase Order (PO)</label>
                        <label for="">List Purchase Order</label>
                    </div>
                </div>
            </div>

            <div class="column is-12 mt-5">
                <DataTable :value="dataOrder" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="loadSearch"
                    class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                    sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField label="Periode" />
                                <VDatePicker class="mt-2" v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                                <div class="columns is-multiline" style="justify-content: end;">
                                    <div class="column is-4 mt-1">
                                        <VField class="is-autocomplete-select">
                                            <VLabel>Kode Produk</VLabel>
                                            <VControl icon="feather:search" class="prime-auto">
                                                <AutoComplete v-model="item.idproduk" :suggestions="id_Produk"
                                                    @complete="fetchProduk2($event)" :optionLabel="'id'"
                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                    :loadingIcon="'pi pi-spinner'" :field="'id'"
                                                    placeholder="ketik untuk mencari..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4 mt-1">
                                        <VField class="is-autocomplete-select">
                                            <VLabel>Produk</VLabel>
                                            <VControl icon="feather:search" class="prime-auto">
                                                <AutoComplete v-model="item.produk" :suggestions="d_Produk"
                                                    @complete="fetchProduk($event)" :optionLabel="'namaproduk'"
                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                    :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                                                    placeholder="ketik untuk mencari..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <VField label="No Order">
                                            <VControl>
                                                <input v-model="item.noorder" type="text" class="input mt-1"
                                                    placeholder="No Order" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-1 mt-5 pt-4">
                                        <VIconButton color="success" icon="fas fa-search" :loading="loadSearch"
                                            @click="fetchOrder()" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <Column expander style="width: 5rem" />
                    <Column field="no" header="No"></Column>
                    <Column field="noorder" header="TGL PO">
                        <template #body="slotProps">
                            {{ H.formatDate(slotProps.data.tglusulan, 'DD/MM/YYYY') }}
                        </template>
                    </Column>
                    <Column field="tglOrder" header="TGL Kebutuhan">
                        <template #body="slotProps">
                            {{ H.formatDate(slotProps.data.tglkebutuhan, 'DD/MM/YYYY') }}
                        </template>
                    </Column>
                    <Column field="noorder" header="No Order"></Column>
                    <Column field="namarekanansales" header="Supplier"></Column>
                    <Column field="ruangan" header="Unit Pengorder"></Column>
                    <Column field="ruangantujuan" header="Unit Tujuan"></Column>
                    <Column field="penanggungjawab" header="Penangung Jawab"></Column>
                    <Column field="keteranganorder" header="Keterangan"></Column>
                    <Column :exportable="false" header="##" style="text-align: center;">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined
                                raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                            </VIconButton>
                            <OverlayPanel ref="op">
                                <VButton type="button" icon="fas fa-print" light circle outlined color="info" raised
                                    @click="gotoPageEdit(selected)">
                                    Edit
                                </VButton>
                                <VButton type="button" icon="fas fa-trash" class="ml-2" color="danger" circle outlined
                                    @click="dialogConfirm(selected)" raised>Batal PO
                                </VButton>
                                <VButton type="button" class="ml-2" color="black" circle outlined icon="fas fa-print" raised>Catak PO
                                </VButton>
                                <VButton type="button" class="ml-2" color="info" circle outlined raised @click="goToPenerimaan(selected)">Terima Barang
                                </VButton>

                            </OverlayPanel>
                        </template>
                    </Column>
                    <template #expansion="slotProps">
                        <div class="p-3">
                            <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple">
                                <Column field="no" header="No" />
                                <Column field="tglkebutuhan" header="Tanggal Kebutuhan" />
                                <Column field="namaproduk" header="Produk" />
                                <Column field="spesifikasi" header="Spesifikasi" />
                                <Column field="satuanstandar" header="Satuan" />
                                <Column field="qtyproduk" header="QTY" style="min-width: 50px;" />
                                <Column field="hargasatuan" header="Harga Satuan" style="text-align: right;">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargasatuan), 2), '')
                                        }}
                                    </template>
                                </Column>
                                <Column field="total" header="Total" style="min-width: 120px;text-align: right;">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.total), 2), '') }}
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </template>
                    <template #footer>
                        <div class="column pt-0 pb-0" style="text-align:right">
                            <VButtons style="justify-content: flex-end">
                                <!-- <VButton class="mr-4" color="info" raised @click="goToPenerimaan"> Penerimaan </VButton> -->
                                <VButton class="mr-4" color="primary" raised icon="fas fa-edit"
                                    style="padding-right: 3rem;padding-left: 3rem;" @click="gotoPageForm"
                                    :loading="loadSave"> Buat PO </VButton>
                            </VButtons>
                        </div>
                    </template>
                </DataTable>
            </div>
        </VCard>
    </div>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import moment from 'moment'
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import OverlayPanel from 'primevue/overlaypanel';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as qzService from '/@src/utils/qzTrayService'


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';

useHead({
    title: 'Daftar Purchase Order - ' + import.meta.env.VITE_PROJECT,
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
let dataOrder: any = ref([])
const detailResep: any = ref([])
const op = ref();
const selected: any = ref({})
const modalDetail: any = ref(false)
const modalRetur: any = ref(false)
const expandedRows = ref()
let d_Produk: any = ref([])
let id_Produk: any = ref([])
let loadSearch: any = ref(false)

const fetchOrder = async () => {
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let produk = item.value.produk ? `&produk=${item.value.produk.id}` :item.value.idproduk ? `&produk=${item.value.idproduk.id}`: ''
    let noorder = item.value.noorder ? `&noorder=${item.value.noorder}` : ''
    loadSearch.value = true
    await useApi().get(`/logistik/get-daftar-data-po?${tglAwal}${tglAkhir}${produk}${noorder}`).then((response: any) => {
        response.forEach((element: any, i: any) => {
            expandedRows.value = element.details.forEach((data: any, i: any) => {
                data.no = i + 1
                data.tglkebutuhan = data.tglkebutuhan ? H.formatDate(data.tglkebutuhan, 'DD/MM/YYYY') : ''
            })
            element.no = i + 1
            element.tglOrder = H.formatDate(element.tglorder, 'DD-MMM-YYYY')
        });
        dataOrder.value = response
        loadSearch.value = false
    }).catch((err: any) => {
        loadSearch.value = false
    })

    console.log(dataOrder.value)
}

const deleteData = async (e: any) => {

    if(e.noverifikasi != null || e.nokonfirmasi != null || e.nokonfirmasidk != null){
        H.alert('error','Data Sudah Diverifikasi')
        return 
    }
    await useApi().post('logistik/batal-usulan-permintaan', { 'norec': e.norec }).then((response) => {
        fetchOrder()
    })
}

const fetchProduk = async (filter: any) => {
    let response = await useApi().get(`/logistik/get-data-produk?namaproduk=${filter.query}`)

    d_Produk.value = response
   
}
const fetchProduk2 = async (filter: any) => {
    let response = await useApi().get(`/logistik/get-data-produk?id=${filter.query}`)
    id_Produk.value = response
    d_Produk.value = response
}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda yakin menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteData(e)
        },
        reject: () => { },
    })
}

const gotoPageEdit = (e: any) => {
    router.push({
        name: 'module-logistik-form-purchase-order',
        query: {
            norec: e.norec,
        },
    })
}

const goToPenerimaan = (e: any) => {
    router.push({
        name: 'module-logistik-form-penerimaan-barang-suplier',
        query: {
            norecOrder: e.norec,
        },
    })
}

const gotoPageForm = () => {
    router.push({ name: 'module-logistik-form-purchase-order' })
}

const toggle = (event: any, e: any) => {
    console.log(event)
    op.value.toggle(event);
    selected.value = e
}


// getListCombo()
fetchOrder()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

.c-title {
    margin-left: -21px;
    padding-top: 21px;
    padding-top: 18px;
    margin-top: -21px;
    border-top-left-radius: 11px;
    border-left: solid hsl(19deg 100% 75% / 72%) 3px;
    padding-bottom: 0px;
    margin-bottom: 2rem;
}

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
