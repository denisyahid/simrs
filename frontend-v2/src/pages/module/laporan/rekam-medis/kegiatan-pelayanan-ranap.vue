<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Formulir RL 3.2</label>
                        <label for="">REKAPITULASI KEGIATAN PELAYANAN RAWAT INAP</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="100" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" groupRowsBy="group"
                    rowGroupMode="subheader">
                    <template #header>
                        <div class="columns is-multiline pb-3" style="justify-content: space-between;">
                            <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel"
                                class="mt-5 ml-3">
                                Export To Excel
                            </VButton>
                            <div class="column is-4">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-11">
                                        <VField label="Periode" />
                                        <VDatePicker class="mt-2" v-model="item.filterDate" is-range color="pink"
                                            trim-weeks>
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
                                    <div class="column is-1"  style="padding-top: 38px;text-align:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData()" :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <ColumnGroup type="header">
                        <Row>
                            <Column header="No" style="min-width: 15px; text-align: center;" :rowspan="2" />
                            <Column header="JENIS PELAYANAN" style="min-width: 80px; text-align: center;" :rowspan="2" />
                            <Column header="PASIEN AWAL BULAN" style="text-align: center;" :rowspan="2" />
                            <Column header="PASIEN MASUK" style="text-align: center" :rowspan="2" />
                            <Column header="PASIEN PINDAHAN" style="text-align: center" :rowspan="2" />
                            <Column header="PASIEN DIPINDAHKAN" style="text-align: center" :rowspan="2" />
                            <Column header="PASIEN KELUAR HIDUP" style="text-align: center;"
                                :rowspan="2" />
                            <Column header="PASIEN KELUAR MATI LAKI-LAKI" style="text-align: center;"
                                :colspan="2" />
                            <Column header="PASIEN KELUAR MATI PEREMPUAN" style="text-align: center;"
                                :colspan="2" />   
                            <Column header="JUMLAH LAMA DIRAWAT" style="text-align: center;"
                                :rowspan="2" />
                            <Column header="PASIEN AKHIR BULAN" style="text-align: center;"
                                :rowspan="2" />
                            <Column header="JUMLAH HARI PERAWATAN" style="text-align: center;"
                                :rowspan="2" />
                            <Column header="RINCIAN HARI PERAWATAN PERKELAS" style="text-align: center;"
                                :colspan="6" />
                            <Column header="JUMLAH ALOKASI TEMPAT TIDUR AWAL BULAN" style="text-align: center;"
                                :rowspan="2" />
                        </Row>
                        <Row>
                            <Column header="< 48 JAM" style="text-align: center;" />
                            <Column header="> 48 JAM" style="text-align: center;" />
                            <Column header="< 48 JAM" style="text-align: center;" />
                            <Column header="> 48 JAM" style="text-align: center;" />   
                            <Column header="VVIP" style="text-align: center;" />
                            <Column header="VIP" style="text-align: center;" />
                            <Column header="I" style="text-align: center;" />
                            <Column header="II" style="text-align: center;" />
                            <Column header="III" style="text-align: center;" />
                            <Column header="KELAS KHUSUS" style="text-align: center;" />
                        </Row>
                    </ColumnGroup>
                    <Column field="no" style="min-width: 10px; text-align:center" />
                    <Column field="jenis_spesialisasi" style="min-width: 80px;" />
                    <Column field="pasienakhirbulan" style="min-width: 50px;" />
                    <Column field="pasienmasuk"/>
                    <Column field="pasienmasuk"/>
                    <Column field="pasiendipindahkan"/>
                    <Column field="pasienkeluarhidup"/>
                    <Column field="matikurang48jaml"/>
                    <Column field="matilebih48jaml"/>
                    <Column field="matikurang48jamp"/>
                    <Column field="matilebih48jamp"/>
                    <Column field="lamadirawat"/>
                    <Column field="pasienakhirbulan"/>
                    <Column field="jumlahharidirawat"/>
                    <Column field="rincianvvip"/>
                    <Column field="rincianvip"/>
                    <Column field="rinciankel1"/>
                    <Column field="rinciankel2"/>
                    <Column field="rinciankel3"/>
                    <Column field="rinciankelaskhusus"/>
                    <Column field="jumlah"/>
                </DataTable>
            </VCard>
        </div>
    </section>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import ColumnGroup from 'primevue/columngroup';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Laporan Indikator Pelayanan Rumah Sakit - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    year: new Date(),
    filterDate: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const confirm = useConfirm()
const dataSource: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
let d_KelompokPasien: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(false)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterDate.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterDate.end, 'YYYY-MM-DD')
    loadSearch.value = true
    await useApi().get(`laporan/indikator-pelayanan-ranap?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`).then((response) => {
        response.data.forEach((element:any,i:any) => {
            element.no = i + 1
        });
        dataSource.value = response.data
    })
    loadData.value = false
    loadSearch.value = false
}


const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['RL 3.2 REKAPITULASI KEGIATAN PELAYANAN RAWAT INAP'],
        [],
        ['NO', 'JENIS PELAYANAN', 'PASIEN AWAL BULAN', 'PASIEN MASUK', 'PASIEN PINDAHAN', 'PASIEN KELUAR HIDUP', 'PASIEN KELUAR MATI LAKI LAKI < 48 JAM', 'PASIEN KELUAR MATI LAKI LAKI >= 48 JAM', 'PASIEN KELUAR MATI PEREMPUAN < 48 JAM', 'PASIEN KELUAR MATI PEREMPUAN >= 48 JAM', 'JUMLAH LAMA DIRAWAT', 'PASIEN AKHIR BULAN', 'JUMLAH HARI PERAWATAN', 'RINCIAN HARI PERAWATAN PER KELAS VVIP', 'RINCIAN HARI PERAWATAN PER KELAS VIP', 'RINCIAN HARI PERAWATAN PER KELAS I', 'RINCIAN HARI PERAWATAN PER KELAS II', 'RINCIAN HARI PERAWATAN PER KELAS III', 'RINCIAN HARI PERAWATAN PER KELAS KELAS KHUSUS', 'JUMLAH ALOKASI TEMPAT TIDUR AWAL TAHUN'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.jenis_spesialisasi,
            e.pasienakhirbulan,
            e.pasienmasuk,
            e.pasienmasuk,
            e.pasienkeluarhidup,
            e.matikurang48jaml,
            e.matilebih48jaml,
            e.matikurang48jamp,
            e.matilebih48jamp,
            e.lamadirawat,
            e.pasienakhirbulan,
            e.jumlahharidirawat,
            e.rincianvvip,
            e.rincianvip,
            e.rinciankel1,
            e.rinciankel2,
            e.rinciankel3,
            e.rinciankelaskhusus,
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

    const columnWidths = [20, 20, 25, 20, 20, 20, 20, 20, 20, 20, 18, 20, 20, 25, 20, 20, 20, 20, 20, 20];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 10 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Indikator', true);
    XLSXStyle.writeFile(workbook, 'RL 3.2 REKAPITULASI KEGIATAN PELAYANAN RAWAT INAP.xlsx');
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
}</style>
