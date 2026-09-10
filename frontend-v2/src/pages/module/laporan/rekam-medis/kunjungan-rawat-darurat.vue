<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Formulir RL 3.3</label>
                        <label for="">REKAPITULASI KEGIATAN PELAYANAN RAWAT DARURAT</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="100" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" groupRowsBy="group"
                    rowGroupMode="subheader">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2 pb-0 mb-0" style="padding-top: 2rem;">
                                <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel">
                                    Export To Excel
                                </VButton>
                            </div>

                            <div class="column is-10 pb-0 mb-3">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-3">
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
                            <Column header="TOTAL PASIEN" style="text-align: center;" :colspan="2" />
                            <Column header="TINDAKAN LANJUT PELAYANAN" style="text-align: center" :colspan="3" />
                            <Column header="MATI IGD" style="text-align: center" :colspan="2"  />
                            <Column header="DOA" style="text-align: center" :colspan="2"  />
                            <Column header="Luka-luka" style="text-align: center" :colspan="2"  />
                            <Column header="False Emergency" style="text-align: center" :rowspan="2"  />
                        </Row>
                        <Row>
                            <Column header="RUJUKAN" style="text-align: center;" />
                            <Column header="NON RUJUKAN" style="text-align: center;" />
                            <Column header="DIRAWAT" style="text-align: center;" />
                            <Column header="DIRUJUK" style="text-align: center;" />
                            <Column header="PULANG" style="text-align: center;" />
                            <Column header="Laki-laki" style="text-align: center;" />
                            <Column header="Perempuan" style="text-align: center;" />
                            <Column header="Laki-laki" style="text-align: center;" />
                            <Column header="Perempuan" style="text-align: center;" />
                            <Column header="Laki-laki" style="text-align: center;" />
                            <Column header="Perempuan" style="text-align: center;" />
                        </Row>
                    </ColumnGroup>
                    <Column field="no" style="min-width: 10px; text-align:center" />
                    <Column field="jenispelayanan" style="min-width: 80px;" />
                    <Column field="rujukan" style="min-width: 50px;" />
                    <Column field="nonrujukan" />
                    <Column field="dirawat" />
                    <Column field="dirujuk" />
                    <Column field="pulang" />
                    <Column field="matil" />
                    <Column field="matip" />
                    <Column field="doal" />
                    <Column field="doap" />
                    <Column field="" />
                    <Column field="" />
                    <Column field="tidakgawatdarurat" />
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
    title: 'Laporan Kunjungan Rawat Darurat - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
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
    await useApi().get(`/laporan/get-laporan-kunjugan-gd?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
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
        ['Laporan RL 3.3 Rekapitulasi Kegiatan Pelayanan Rawat Darurat'],
        [],
        ['NO', 'JENIS PELAYANAN', 'TOTAL PASIEN', '', 'TINDAKAN LANJUT PELAYANAN', '', '', 'MATI IGD', '', 'DOA', '','Luka-luka','', 'False Emergency'],
        [ '', '', 'RUJUKAN', 'NON RUJUKAN', 'DIRAWAT', 'DIRUJUK', 'PULANG', 'Laki-laki', 'Perempuan', 'Laki-laki', 'Perempuan', 'Laki-laki', 'Perempuan'],
        ...dataSource.value.map((e, index) => [
            e.no,
            e.jenispelayanan,
            e.rujukan,
            e.nonrujukan,
            e.dirawat,
            e.dirujuk,
            e.pulang,
            e.matil,
            e.matip,
            e.doal,
            e.doap,
            '',
            '',
            e.tidakgawatdarurat
        ])
    ]);

    // Defining style for the header (centered)
    const headerStyle = {
        alignment: {
            horizontal: 'center',
            vertical: 'center',
        },
        font: {
            color: { rgb: 'FFFFFF' },
            bold: true,
        },
        fill: { fgColor: { rgb: '807C7C' } },
        border: {
            top: { style: 'thin', color: { rgb: 'FFFFFF' } },
            bottom: { style: 'thin', color: { rgb: 'FFFFFF' } },
            left: { style: 'thin', color: { rgb: 'FFFFFF' } },
            right: { style: 'thin', color: { rgb: 'FFFFFF' } },
        },
    };

    // Applying header style
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
        if (!worksheet[headerCell]) worksheet[headerCell] = {}; // Ensure the cell exists
        worksheet[headerCell].s = headerStyle;
    }

    // Setting column widths
    const columnWidths = [10, 40, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20];
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        worksheet['!cols'] = worksheet['!cols'] || [];
        worksheet['!cols'][col] = { wch: columnWidths[col] };
    }

    // Centering the text in cell A1
    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = {
        alignment: {
            horizontal: 'center',
            vertical: 'center',
        },
        font: {
            bold: true,
            sz: 18,
        },
    };

    // Row 2 style
    const row2Style = {
        font: {
            color: { rgb: 'FFFFFF' },
            bold: true,
        },
        fill: { fgColor: { rgb: '807C7C' } },
        border: {
            top: { style: 'thin', color: { rgb: 'FFFFFF' } },
            bottom: { style: 'thin', color: { rgb: 'FFFFFF' } },
            left: { style: 'thin', color: { rgb: 'FFFFFF' } },
            right: { style: 'thin', color: { rgb: 'FFFFFF' } },
        },
    };

    const row2Range = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = row2Range.s.c; col <= row2Range.e.c; col++) {
        const cell = XLSX.utils.encode_cell({ r: 3, c: col });
        if (!worksheet[cell]) worksheet[cell] = {}; // Ensure the cell exists
        worksheet[cell].s = row2Style;
    }

    // Merging the first two rows
    const merges = [
        { s: { r: 0, c: 0 }, e: { r: 1, c: 13 } },
        { s: { r: 2, c: 0 }, e: { r: 3, c: 0 } },
        { s: { r: 2, c: 1 }, e: { r: 3, c: 1 } },
        { s: { r: 2, c: 2 }, e: { r: 2, c: 3 } },
        { s: { r: 2, c: 4 }, e: { r: 2, c: 6 } },
        { s: { r: 2, c: 7 }, e: { r: 2, c: 8 } },
        { s: { r: 2, c: 9 }, e: { r: 2, c: 10 } },
        { s: { r: 2, c: 11 }, e: { r: 2, c: 12 } },
        { s: { r: 2, c: 13 }, e: { r: 3, c: 13 } },
        { s: { r: 3, c: 2 }, e: { r: 2, c: 2 } },
        { s: { r: 3, c: 3 }, e: { r: 2, c: 3 } },
        { s: { r: 3, c: 4 }, e: { r: 2, c: 4 } },
        { s: { r: 3, c: 5 }, e: { r: 2, c: 5 } },
        { s: { r: 3, c: 6 }, e: { r: 2, c: 6 } },
    ];
    worksheet['!merges'] = merges;

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan RL 3.3', true);
    XLSXStyle.writeFile(workbook, 'Laporan RL 3.3 Rekapitulasi Kegiatan Pelayanan Rawat Darurat.xlsx');
};



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
