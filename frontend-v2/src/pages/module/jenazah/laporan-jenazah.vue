<template>
    <ConfirmDialog />
    <section>

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Laporan Kunjungan Rawat Jalan Per Pasien</label>
                        <span>Forensik Pemulasaran Jenazah</span>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSourcefiltered" :loading="loadSearch"
                    :rowsPerPageOptions="[5, 10, 15]" class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single"
                    sortMode="multiple" showGridlines tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2" style="margin-top:23px">
                                <VButton color="primary" @click="cetak()" outlined icon="feather:printer">
                                    Cetak Laporan
                                </VButton>
                            </div>
                            <div class="column is-10">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-4">
                                        <VField label="Tanggal Order" style="margin-bottom: 6px;" />
                                        <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField addons>
                                                    <VControl icon="feather:calendar">
                                                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                                    </VControl>
                                                    <VControl>
                                                        <VButton static><i class="fas fa-arrow-right"
                                                                aria-hidden="true"></i>
                                                        </VButton>
                                                    </VControl>
                                                    <VControl icon="feather:calendar">
                                                        <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <div class="column is-2">
                                        <VField class="is-autocomplete-select" label="Cara Bayar">
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.carabayarfk"
                                                    :options="listCaraBayar" placeholder="Pilih data" :searchable="true" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 mt-5">
                                        <VControl icon="feather:search">
                                            <input v-model="filters" class="input custom-text-filter"
                                                placeholder="Search..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-1 btn-search mt-3" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </template>
                    <Column field="no" header="No" />
                    <Column field="carabayar" header="Cara Bayar" />
                    <Column field="nosep" header="SEP" />
                    <Column field="noregistrasi" header="No Pen" />
                    <Column field="nocm" header="NORM" />
                    <Column field="namapasien" header="Nama Pasien" />
                    <Column field="tglorder" header="Tanggal Daftar" />
                    <Column field="tglpelayanan" header="Tanggal Tindakan" />
                    <Column field="namalengkap" header="Dokter Tindakan" />
                    <Column field="namaproduk" header="Nama Tindakan" />
                    <Column field="hargasatuan" header="Tarif Tindakan" />
                    <Column field="ruangantujuan" header="Ruangan Pelayanan" />
                    <Column field="ruangantujuan" header="Ruangan Pendaftaran" />
                </DataTable>
            </VCard>
        </div>
    </section>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import OverlayPanel from 'primevue/overlaypanel';
import DataTable from 'primevue/datatable'
import AutoComplete from 'primevue/autocomplete';
import ColumnGroup from 'primevue/columngroup';
import ConfirmDialog from 'primevue/confirmdialog'
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Daftar Laporan Jenazah - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    tglPelayanan: new Date(),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
    // carabayarfk : 1
})

const router = useRouter()
const confirm = useConfirm()
const dataSource: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
const op = ref();
const selected: any = ref({})
const filters = ref('')
let listCaraBayar: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.namapasien.match(new RegExp(filters.value, 'i')) ||
            items.nocm.match(new RegExp(filters.value, 'i')) ||
            items.noregistrasi.match(new RegExp(filters.value, 'i'))
        )
    })
})

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    if(!item.value.carabayarfk){
        item.value.carabayarfk = 1
    }
    
    item.value.Ttotal = 0
    loadSearch.value = true
    await useApi().get(`jenazah/get-laporan?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}&carabayar=${item.value.carabayarfk}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataSource.value = response
    })
    loadData.value = false
    loadSearch.value = false
}

const cetak = () => {

    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    if(dataSource.value.length == 0){
        H.alert('error', 'Data tidak Tersedia')
        return
    }
    if (!item.value.carabayarfk) {
        H.alert('error','Cara Bayar Tidak Boleh Kosong')
        return
    }
    
    H.printBlade(`jenazah/cetak-laporan?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}&carabayar=${item.value.carabayarfk}&pdf=true`);
}

const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Kematian Pasien > 48 Jam'],
        [],
        ['NO', 'NO RM', 'NO REGISTRASI', 'TGL Registrasi', 'NAMA PASIEN', 'TGL MENINGGAL', 'RUANGAN'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.nocm,
            e.noregistrasi,
            e.tglregistrasi,
            e.namapasien,
            e.tglmeninggal,
            e.namaruangan,
        ]),
    ]);
    // Mendefinisikan style untuk header(centered)
    const headerStyle = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            color: { rgb: 'FFFFFF' }
        },
        fill: { fgColor: { rgb: '807C7C' } }
    };

    // Mendefinisikan range header
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
        worksheet[headerCell].s = headerStyle;
    }

    const columnWidths = [5, 15, 15, 20, 20, 20, 20];

    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        worksheet['!cols'] = worksheet['!cols'] || [];
        worksheet['!cols'][col] = { wch: columnWidths[col] };
    }

    // Centering the text in cell A1
    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            bold: true,
            sz: 18
        }
    };

    // Menggabungkan dua baris pertama
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 6 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Dokter Kematian Pasien Ranap', true);
    XLSXStyle.writeFile(workbook, 'Daftar Kematian Pasien Rawat Inap.xlsx');
}

const fetchCombo = async () => {
    const response = await useApi().get(`/jenazah/get-combo`)
    listCaraBayar.value = response.carabayar.map((e:any)=>{return {label : e.carabayar , value : e.id}})

    item.value.carabayarfk = 1
}



fetchCombo()
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
</style>
