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
                                <Multiselect mode="single" v-model="item.ruangan" @select="fetchData()" :options="d_ruangan"
                                    placeholder="Pilih Barang" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="No Dokumen">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.noretur" placeholder="No Retur"
                                    v-on:keyup.enter="fetchData()" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column btn-search">
                        <VButton type="button" icon="feather:search" @click="fetchData()">
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
                        <label class="title-page">Daftar Retur Obat Alkes</label>
                        <!-- <label for="">List Stok Penerimaan Dari Supplier</label> -->
                    </div>
                    <div class="column pr-0">
                        <VControl icon="feather:search">
                            <input v-model="filters" class="input custom-text-filter" placeholder="Nama Pasien..." />
                        </VControl>
                    </div>
                </div>
            </div>

            <div class="column is-12 mt-5">
                <DataTable :value="dataSourcefiltered" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="isLoading" class="p-datatable-sm"
                    responsiveLayout="stack" breakpoint="960px" selectionMode="single"  sortMode="multiple"  showGridlines v-model:expanded-rows="expandedRows"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <Column expander style="width: 5rem" />
                    <Column field="no" header="No" style="text-align: center;" />
                    <Column field="tglRetur" header="Tgl Retur" style="text-align: center;" />
                    <Column field="noretur" header="No Retur" />
                    <Column field="namapasien" header="Nama Pasien" />
                    <Column field="namalengkap" header="Pegawai" />
                    <Column field="keteranganlainnya" header="Keterangan" />
                    <!-- <Column :exportable="false" header="#" style="text-align: center;">
                        <template #body="slotProps">
                            <VIconButton v-tooltip.bottom="'Cetak'" color="primary" outlined circle icon="feather:printer"
                                @click="cetak(slotProps.data)" />
                        </template>
                    </Column> -->
                    <template #expansion="slotProps">
                        <div class="p-3">
                            <DataTable :value="slotProps.data.details" :rows="10" showGridlines :loading="isLoading" class="p-datatable-sm"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple" >
                                <Column field="rke" header="Rke"/>
                                <Column field="jeniskemasan" header="Jenis Kemasan"/>
                                <Column field="namaproduk" header="Nama Produk"/>
                                <Column field="satuanstandar" header="Satuan"/>
                                <Column field="jumlah" header="Qty"/>
                                <Column field="hargasatuan" header="Harga"/>
                                <Column field="hargadiscount" header="Discount"/>
                                <Column field="jasa" header="Jasa"/>
                                <Column field="total" header="Total"/>
                            </DataTable>
                        </div>
                    </template>
                </DataTable>
            </div>
        </VCard>
    </div>

    <VModal :open="modalDetail" title="Detail Retur Obat Alkes" size="large" actions="right" @close="modalDetail = false">
        <template #content>
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-2">
                        <VField label="Rke">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.rke" disabled class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Jenis Kemasan">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.jeniskemasan" disabled class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-5">
                        <VField label="Nama Produk">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.namaproduk" disabled class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField label="Satuan">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.satuan" disabled class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>

            <div class="column is-12">
                <div class="columns">
                    <div class="column is-2">
                        <VField label="Qty">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.jumlah" disabled class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Harga Satuan">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.jumlah" disabled class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField label="Discount">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.discount" disabled class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField label="Jasa">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.jasa" disabled class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Total">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.total" disabled class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
        </template>
    </VModal>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import Fieldset from 'primevue/fieldset';
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown'
import Column from 'primevue/column'


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';
import { booleanTypeAnnotation } from '@babel/types';

useHead({
    title: 'Daftar Retur Obat Alkes - ' + import.meta.env.VITE_PROJECT,
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
let isLoadingBtn: any = ref(false)
let idSatuanAsal: any
const confirm = useConfirm()
const d_ruangan = ref([])
const noBukti: any = ref()
const dataSource: any = ref([])
const detailPenerimaan: any = ref([])
const modalDetail: any = ref(false)
const filters = ref('')
let d_Produk: any = ref([])
const expandedRows = ref();
let d_Rekanan: any = ref([])
let isLoading: any = ref(false)

const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.namapasien.match(new RegExp(filters.value, 'i'))
        )
    })
})

const listDataCombo = async () => {
    await useApi().get('/farmasi/input-resep-cbo').then((response) => {
        d_ruangan.value = response.ruanganFarmasi.map((e: any) => { return { label: e.namaruangan, value: e.id } })
    })
}

const fetchData = async () => {

    let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let idRuangan = item.value.ruangan ? `&idRuangan=${item.value.ruangan}` : ''
    let noRetur = item.value.noretur ? `&noretur=${item.value.noretur}` : ''
    isLoading.value = true
    await useApi().get(`/farmasi/daftar-retur-obat-alkes?${tglAwal}${tglAkhir}${idRuangan}${noRetur}`).then((response) => {
        expandedRows.value = response.details
        response.daftar.forEach((element: any, i: any) => {
            element.no = i + 1
            element.nosppb = element.nosppb ? element.nosppb : '',
                element.tglRetur = H.formatDate(element.tglretur, 'DD-MMM-YYYY')
        });
        isLoading.value = false
        dataSource.value = response.daftar
    })

}

const cetak = async(e: any) => {
    H.printBlade(`farmasi/cetak-bukti-retur?norec=${e.norec}`)
}

const cetakBukti = async (e:any)=>{
    if(!e.pegawaipenyerah){
        H.alert('error','Petugas Penyerahan Harus Diisi');
        return
    }
    if(!e.pegawaipenerima){
        H.alert('error','Petugas Penerima Harus Diisi');
        return
    }

    if(!e.pegawaiketahui){
        H.alert('error','Petugas Mengetahui Harus Diisi');
        return
    }
    let pegawaiPenyerah = `&pegawaiMeminta=${e.pegawaipenyerah.id}`
    let pegawaiMengetahui = `&pegawaiMengetahui=${e.pegawaiketahui.id}`
    let pegawaiPenerima = `&pegawaiPenerima=${e.pegawaipenerima.id}`
    H.printBlade(`logistik/report/cetak-bukti-penerimaan-barang?norec=${e.norec}${pegawaiPenyerah}${pegawaiPenerima}${pegawaiMengetahui}`);

}

listDataCombo()
fetchData()

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
