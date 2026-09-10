<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 pb-1">
                    <label class="title-page">Kartu Pemeliharaan</label>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource" :rowsPerPageOptions="[5, 10, 15]" :loading="loadSearch"
                    class="p-datatable-sm" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline p-2">
                            <div class="column is-3">
                                <VField label="Periode Tanggal"></VField>
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
                            <div class="column is-9 p-2">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-4">
                                        <VField label="Ruangan">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.qproduk" :suggestions="d_ProdukAsset"
                                                    class="mt-2" :optionLabel="'label'" @complete="fetchProdukAsset($event)" :dropdown="true" :minLength="3"
                                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" dropPegawai
                                                    :field="'label'" placeholder="Pilih Produk Asset" />
                                            </VControl>
                                        </VField>
                                    </div>

                                    <div class="column is-1 btn-search pt-5 mt-5">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData()"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </template>
                    <Column field="no" header="NO" />
                    <Column field="tglplanning" header="Tgl Pemeliharaan" />
                    <Column field="keteranganlainnya" header="Keterangan" />
                    <Column field="namalengkap" header="PIC" />
                    <Column field="startdate" header="Mulai" />
                    <Column field="duedate" header="Selasai" />
                    <Column field="keteranganverifikasi" header="Inspeksi" />
                </DataTable>

            </VCard>
        </div>
    </section>

</template>

<script setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useHead } from '@vueuse/head'
import { useConfirm } from 'primevue/useconfirm'
import DataTable from 'primevue/datatable'
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import MultiSelect from 'primevue/multiselect';
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Permohonan Peraikan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const confirm = useConfirm()

const item: any = ref({
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const dataSource: any = ref([])
let d_Pegawai: any = ref([])
let d_ProdukAsset: any = ref([])
let loadSearch: any = ref(false)
let loadSave: any = ref(false)
let loadData: any = ref(true)
let modalPerbaikan: any = ref(false)
let modalPengerjaan: any = ref(false)
let IsBayar: any = ref(false)


const fetchData = async () => {

    let tglAwal = `tglAwal=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
    let tglAkhir = `&tglAkhir=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
    let produk = item.value.qproduk ? `&norecasset=${item.value.qproduk.value}` : ''
    loadSearch.value = true

    let response = await useApi().get(`iprs/daftar-pemeliharaan?${tglAwal}${tglAkhir}${produk}`)
    response.forEach((element: any, i: any) => {
        element.no = i + 1
    });

    loadSearch.value = false
    dataSource.value = response
    loadData.value = false
}

const fetchProdukAsset = async (filter: any) => {
    const response = await useApi().get(`/iprs/produk-asset?namaproduk=${filter.query}`)
    d_ProdukAsset.value = response.map((e:any)=>{
        return { label : e.namaproduk , value : e.id}
    })
}

const clear = () => {
    delete item.value.norec
    delete item.value.tglplanning
    delete item.value.ruanganasal
    delete item.value.desckerusakan
    delete item.value.status
    delete item.value.ruanganTujuan
    delete item.value.penanggungjawab
    modalPerbaikan.value = false
}

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
    margin-top: 0px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
}

.label {
    .max-width {
        width: max-content !important;
    }
}

.form-fieldset {
    padding: 20px 0;
    max-width: 66rem;
    margin: 0 auto;
}
</style>
