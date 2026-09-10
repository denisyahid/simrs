<template>
    <ConfirmDialog />

    <div class="column is-12">
        <VCard style="padding-bottom: 0px">
            <div class="column c-title pt-2 mb-5">
                <label class="title-page">Pencarian</label>
            </div>
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-4">
                        <VField label="Tanggal Struk" style="margin-bottom: 6px;" />
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
                        <VField class="is-autocomplete-select" label="Ruangan">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" @select="fetchOrder()" v-model="item.ruangan"
                                    :options="d_ruangan" placeholder="Pilih Barang" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Nama Pasien">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.namapasien" v-on:keyup.enter="fetchOrder()"
                                    placeholder="Nama Pasien" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column btn-search">
                        <VButton type="button" icon="feather:search" :loading="loadSearch" @click="fetchOrder()">
                            Cari Data
                        </VButton>
                    </div>

                </div>
            </div>
        </VCard>
    </div>

    <div class="column is-12">
        <VCard>
            <div class="column c-title pt-2 mb-0">
                <div class="columns p-2">
                    <div class="column is-10">
                        <label class="title-page">Daftar Penjualan</label>
                        <label for="">List Penjualan Obat Bebas</label>
                    </div>
                    <div class="column pr-0">
                        <VButton type="button" icon="feather:x-circle" RouterLink
                            :to="{ name: 'module-farmasi-penjualan-obat-bebas' }"
                            class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
                            Tambah Obat Bebas
                        </VButton>
                    </div>
                </div>
            </div>

            <div class="column is-12 mt-5">
                <DataTable :value="dataSource" :rows="10" :loading="loadSearch" class="p-datatable-sm"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines>

                    <Column header="#">
                        <template #body="slotProps">
                            <VIconButton color="success" icon="fas fa-external-link-alt" v-tooltip.bottom.center="'Detail'"
                                @click="showDetail(slotProps.data)" />
                        </template>
                    </Column>
                    <Column field="no" header="No"></Column>
                    <Column field="tglstruk" header="tgl Struk"></Column>
                    <Column field="nostruk" header="No Resep"></Column>
                    <Column field="norm" header="No MR"></Column>
                    <Column field="namapasien" header="Nama Pasien"></Column>
                    <Column field="namaruangan" header="Nama Ruangan"></Column>
                    <Column field="namalengkap" header="Dokter"></Column>
                    <Column field="sbm" header="SBM" style="text-align: center;"></Column>
                    <Column :exportable="false" header="##" style="text-align: center;">
                        <template #body="slotProps">
                            <VDropdown icon="feather:more-vertical" spaced right>
                                <template #content>
                                    <a role="menuitem" class="dropdown-item is-media" @click="gotoPageEdit(slotProps.data)">
                                        <div class="icon">
                                            <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Ubah Resep</span>
                                        </div>
                                    </a>
                                    <a role="menuitem" class="dropdown-item is-media"
                                        @click="gotoPageRetur(slotProps.data)">
                                        <div class="icon">
                                            <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Retur Resep</span>
                                        </div>
                                    </a>
                                    <a role="menuitem" style="background:#ED1C24" class="dropdown-item is-media"
                                        @click="dialogConfirm(slotProps.data)">
                                        <div class="icon">
                                            <i aria-hidden="true" style="color:whitesmoke"
                                                class="lnil lnil-trash-can-alt"></i>
                                        </div>
                                        <div class="meta">
                                            <span style="color:whitesmoke">Hapus Resep</span>
                                        </div>
                                    </a>
                                </template>
                            </VDropdown>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </VCard>
    </div>

    <VModal :open="modalDetail" title="Detail Resep" size="big" actions="right" @close="modalDetail = false">
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
                    <Column field="tglKadaluarsa" style="text-align: center;" header="Tgl Kadaluarsa" />
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


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';

useHead({
    title: 'Daftar Penjualan Obat Bebas - ' + import.meta.env.VITE_PROJECT,
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
const modalRetur: any = ref(false)

let d_ruangan: any = ref([])
let loadSearch: any = ref(false)

const fetchOrder = async () => {

    let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    // let idRuangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan}` : ''
    // let namaPasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''
    loadSearch.value = true
    await useApi().get(`/farmasi/get-tagihan-non-layanan?${tglAwal}${tglAkhir}`).then((response: any) => {
        console.log(response)
        // response.daftar.forEach((element: any, i: any) => {
        //     element.no = i + 1
        //     element.norm = element.nostruk_intern ? element.nostruk_intern : '-'
        //     element.namapasien = element.namapasien_klien
        //     element.tglstruk = moment(element.tglstruk).format('DD-MM-YYYY')

        // });
        // dataSource.value = response.daftar
        loadSearch.value = false
    }).catch((err: any) => {
        loadSearch.value = false
    })
}

// const dialogConfirm = (e: any) => {
//     confirm.require({
//         message: 'Apakah anda yakin menghapus data ini ?',
//         header: 'Konfirmasi Hapus Data',
//         icon: 'pi pi-info-circle',
//         acceptClass: 'p-button-danger',
//         accept: () => {
//             deletePenerimaan(e)
//         },
//         reject: () => { },
//     })
// }

// const deletePenerimaan = async (e: any) => {

//     await useApi().post('/farmasi/delete-resep-bebas', { 'norec_sp': e.norec }).then((response) => {
//         fetchOrder()
//     }).catch((err: any) => {

//     })
// }

// const gotoPageEdit = (e: any) => {
//     router.push({
//         name: 'module-farmasi-penjualan-obat-bebas',
//         query: {
//             norec: e.norec,
//         },
//     })
// }

// const gotoPageRetur = (e: any) => {
//     router.push({
//         name: 'module-farmasi-retur-obat-bebas',
//         query: {
//             norec: e.norec,
//         },
//     })
// }

const getListCombo = async () => {
    const response = await useApi().get(`/farmasi/input-resep-cbo`)
    d_ruangan.value = response.ruanganFarmasi.map((e: any) => { return { label: e.namaruangan, value: e.id } })
}

// const showDetail = (e: any) => {

//     let total = 0
//     e.details.forEach((element: any, i: any) => {
//         element.no = i + 1
//         element.tglKadaluarsa = moment(element.tglkadaluarsa).format('DD-MM-YYYY')
//         total = parseFloat(element.total) + total
//     });
//     item.value.totalTagihan = total
//     detailResep.value = e.details
//     modalDetail.value = true
// }

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
