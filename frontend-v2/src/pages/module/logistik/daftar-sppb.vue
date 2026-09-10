<template>
    <ConfirmDialog />

    <div class="column is-12">
        <VCard>
            <div class="column c-title pt-2 mb-0">
                <div class="columns p-2">
                    <div class="column is-10">
                        <label class="title-page">Daftar Pemesanan Barang / SPPB</label>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField label="Tanggal Order" style="margin-bottom: 6px;" />
                        <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                            <template #default="{ inputValue, inputEvents }">
                                <VField addons>
                                    <VControl icon="feather:calendar">
                                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                    </VControl>
                                    <VControl>
                                        <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                                    </VControl>
                                    <VControl icon="feather:calendar">
                                        <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>

                    <div class="column is-3">
                        <span>Nama Perusahaan</span>
                        <VField class="is-autocomplete-select pt-1" v-slot="{ id }">
                            <VControl icon="feather:search">
                                <AutoComplete v-model="item.rekanan" :suggestions="d_Rekanan"
                                    @complete="fetchRekanan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Nama Perusahaan" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <span>Nama Produk</span>
                        <VField class="is-autocomplete-select pt-1" v-slot="{ id }">
                            <VControl icon="feather:search">
                                <AutoComplete v-model="item.produkfk" :suggestions="d_Produk"
                                    @complete="fetchProduk($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Nama Produk" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column" style="margin-top: 25px; margin-left: auto:  !important;">
                        <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                            @click="fetchOrder()" :loading="loadSearch">
                        </VIconButton>
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
                        <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                            <span class="text-xl text-900 font-bold">Rincian Pemesanan Barang</span>
                            <VButton color="primary" icon="feather:plus" RouterLink
                                :to="{ name: 'module-logistik-form-pemesanan-barang' }" raised rounded
                                style="margin-left:10px;"> Buat SPPB
                            </VButton>
                        </div>

                    </template>
                    <Column expander style="width: 5rem" />
                    <Column field="no" header="No"></Column>
                    <Column field="tglorder" header="Tanggal Order"></Column>
                    <Column field="noorder" header="No SPPB"></Column>
                    <Column field="namarekanan" header="Suplier"></Column>
                    <Column field="jmlitem" header="Jumlah Item" style="text-align: center;"></Column>
                    <Column field="petugas" header="Pembuat PO"></Column>
                    <Column :exportable="false" header="##" style="text-align: center;">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined
                                raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                            </VIconButton>
                            <OverlayPanel ref="op">
                                <VButton type="button" icon="feather:edit" class="mr-2 mt-5" light circle outlined
                                    color="info" raised @click="gotoPageEdit(selected)">
                                    Edit
                                </VButton>
                                <!-- <VButton type="button" icon="fas fa-undo" class="mr-2" color="warning" circle outlined
                                    raised @click="gotoPageRetur(selected)">Retur
                                </VButton>
                                <VButton type="button" icon="fas fa-trash" class="mr-2" color="danger" circle outlined
                                    @click="dialogConfirm(selected)" raised>Hapus
                                </VButton>
                                <br /> -->
                                <VButton type="button" icon="feather:arrow-right" class="mr-2 mt-5" color="primary" circle
                                    outlined @click="cetakLabel(selected)" raised>Penerimaan
                                </VButton>
                                <VButton type="button" icon="fas fa-print" class="mr-2 mt-5" color="purple" circle outlined
                                    @click="cetakResep(selected)" raised>Cetak
                                </VButton>
                            </OverlayPanel>
                        </template>
                    </Column>
                    <template #expansion="slotProps">
                        <div class="p-3">
                            <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple">

                                <Column field="namaproduk" header="Nama Produk" />
                                <Column field="satuanstandar" header="Satuan" />
                                <Column field="qtyproduk" header="Jumlah" style="text-align: center;" />
                                <Column field="qtyterimalast" header="Jumlah Diterima" />
                                <Column field="hargasatuan" header="Harga Satuan"
                                    style="min-width: 150px; text-align: right;">
                                    <template #body="slotProps">
                                        {{ H.formatRp(slotProps.data.hargasatuan, 'Rp. ') }}
                                    </template>
                                </Column>

                                <Column field="hargadiscount" style="text-align: right;" header="Diskon">
                                    <template #body="slotProps">
                                        {{ H.formatRp(slotProps.data.hargadiscount, 'Rp. ') }}
                                    </template>
                                </Column>
                                <Column field="hargappn" style="text-align: right;" header="PPN">
                                    <template #body="slotProps">
                                        {{ H.formatRp(slotProps.data.hargappn, 'Rp. ') }}
                                    </template>
                                </Column>
                                <Column field="total" style="text-align: right;" header="Total">
                                    <template #body="slotProps">
                                        {{ H.formatRp(slotProps.data.total, 'Rp. ') }}
                                    </template>
                                </Column>


                            </DataTable>
                        </div>
                        <div class="column is-12 p-0">
                            <div class="content">
                                <div class="is-divider" data-content="Total Keseluruhan" />
                            </div>
                        </div>

                        <div class="column is-12 p-0">
                            <div class="column is-3 p-0" style="margin-left: auto;">
                                <VCardCustom :style="'padding:5px 25px'">
                                    <div class="label-status" color="danger">
                                        <i aria-hidden="true" class="fas fa-circle"></i>
                                        <span class="ml-1">TOTAL</span>
                                    </div>
                                    <small class="text-bold-custom h-100">{{
                                        H.formatRp(slotProps.data.totalhargasatuan, 'Rp.')
                                    }}</small>
                                </VCardCustom>
                            </div>
                        </div>
                    </template>
                </DataTable>
            </div>
        </VCard>
    </div>

    <!-- <VModal :open="modalDetail" title="Detail Resep" size="big" actions="right" @close="modalDetail = false">
        <template #content>
            <div class="column is-12">
                <DataTable :value="detailResep" :rows="10" :loading="loadSearch" class="p-datatable-sm"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines>
                    <Column field="no" header="No" />
                    <Column field="jeniskemasan" header="Jenis Kemasan" />
                    <Column field="aturanpakai" header="Aturan Pakai" style="text-align: center;" />
                    <Column field="namaproduk" header="Produk" />
                    <Column field="satuanstandar" header="Satuan" />
                    <Column field="hargasatuan" style="text-align: center;" header="Harga Satuan" />
                    <Column field="hargadiscount" style="text-align: center;" header="Diskon" />
                    <Column field="qtyproduk" style="text-align: center;" header="Qty" />

                </DataTable>
            </div>
            <div class="column is-12">
                <div class="content">
                    <div class="is-divider" data-content="Total Keseluruhan" />
                </div>
            </div>

            <div class="column is-12 p-0">
                <div class="column is-3 p-0" style="margin-left: auto;">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status" color="danger">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">TOTAL</span>
                        </div>
                        <small class="text-bold-custom h-100">{{
                            H.formatRp(item.totalTagihan, 'Rp.')
                        }}</small>
                    </VCardCustom>
                </div>
            </div>
        </template>
    </VModal> -->
</template>

<script  setup lang="ts">
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
import AutoComplete from 'primevue/autocomplete';
import * as qzService from '/@src/utils/qzTrayService'


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';

useHead({
    title: 'Daftar Pemesanan Barang / SPPB - ' + import.meta.env.VITE_PROJECT,
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
const op = ref();
const selected: any = ref({})
const modalDetail: any = ref(false)
const modalRetur: any = ref(false)
const expandedRows = ref();
const d_ruangan: any = ref([])
const d_Rekanan: any = ref([])
const d_Produk: any = ref([])
const loadSearch: any = ref(false)

const fetchOrder = async () => {

    let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let rekanan = item.value.rekanan ? `&rekanan=${item.value.rekanan.value}` : ''
    let produkfk = item.value.produkfk ? `&produkfk=${item.value.produkfk.value}` : ''

    loadSearch.value = true
    await useApi().get(`/logistik/daftar-sppb?${tglAwal}${tglAkhir}${rekanan}${produkfk}`).then((response: any) => {
        response.daftar.forEach((element: any, i: any) => {
            expandedRows.value = element.details.forEach((data: any, i: any) => {
                data.no = i + 1
                data.tglorder = data.tglorder ? H.formatDate(data.tglorder, 'DD-MMM-YYYY') : ''

            })
            element.no = i + 1
            element.norm = element.nostruk_intern ? element.nostruk_intern : '-'
            element.namapasien = element.namapasien_klien
            element.tglorder = moment(element.tglorder).format('DD-MM-YYYY')

        });
        dataSource.value = response.daftar
        loadSearch.value = false
    }).catch((err: any) => {
        loadSearch.value = false
    })
}

const fetchRekanan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Rekanan.value = response
    })
}

const fetchProduk = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/produk_m?select=id,namaproduk&param_search=namaproduk&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Produk.value = response
    })
}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda yakin menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deletePenerimaan(e)
        },
        reject: () => { },
    })
}

const deletePenerimaan = async (e: any) => {

    await useApi().post('/farmasi/delete-resep-bebas', { 'norec_sp': e.norec }).then((response) => {
        fetchOrder()
    }).catch((err: any) => {

    })
}

const gotoPageEdit = (e: any) => {
    router.push({
        name: 'module-logistik-form-pemesanan-barang',
        query: {
            norec: e.norec,
        },
    });

}

const gotoPageRetur = (e: any) => {
    console.log(e.no)
    dataSource.value.forEach((element: any) => {
        if (element.no == e.no) {
            if (element.nosbm) {
                H.alert('error', 'Tagihan Sudah Lunas Tidak Bisa Dirubah')
            } else {
                router.push({
                    name: 'module-farmasi-retur-obat-bebas',
                    query: {
                        norec: e.norec,
                    },
                })
            }
        }
    });

}

const getListCombo = async () => {
    const response = await useApi().get(`/farmasi/input-resep-cbo-ruang`)
    d_ruangan.value = response.ruanganFarmasi.map((e: any) => { return { label: e.namaruangan, value: e.id } })
}



const toggle = (event: any, e: any) => {
    console.log(event)
    op.value.toggle(event);
    selected.value = e
}


const cetakLabel = (e: any) => {
    //   console.log(e)
    qzService.printData(`report/farmasi/cetak-apotik-label-kecil-bebas?pdf=true&norec=${e.norec}`, 'LABEL RESEP', 1);
    //   H.printBlade(`report/farmasi/cetak-apotik-label-kecil-bebas?pdf=true&norec=${e.norec}`)
}
const cetakResep = (e: any) => {
    qzService.printData(`report/farmasi/resep-obat-bebas?pdf=true&norec=${e.norec}`, 'RESEP', 1)
    //   H.printBlade(`report/farmasi/resep-obat-bebas?pdf=true&norec=${e.norec}`)
}

getListCombo()
fetchOrder()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';


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
