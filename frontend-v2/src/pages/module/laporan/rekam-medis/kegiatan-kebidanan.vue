<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Formulir RL 3.6</label>
                        <label for="">Rekapitulasi Kegiatan Pelayanan Kebidanan</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="50" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
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
                            <Column header="Jenis Kegiatan" style="min-width: 80px; text-align: center;" :rowspan="2" />
                            <Column header="Rujukan Medis" style="text-align: center;" :colspan="7" />
                            <Column header="Rujukan Non Medis" style="text-align: center" :colspan="3" />
                            <Column header="Non Rujukan" style="text-align: center" :colspan="3" />
                            <Column header="Dirujuk" style="text-align: center" :rowspan="2" />
                        </Row>
                        <Row>
                            <Column header="Rumah Sakit" style="text-align: center;"/>
                            <Column header="Bidan" style="text-align: center;" />
                            <Column header="Puskesmas" style="text-align: center;" />
                            <Column header="Faskes Lainnya" style="text-align: center;min-width: 20px;" />
                            <Column header="Jumlah Hidup" style="text-align: center;" />
                            <Column header="Jumlah Mati" style="text-align: center;" />
                            <Column header="Total Rujukan Medis" style="text-align: center;" />
                            <Column header="Jumlah Hidup" style="text-align: center;" />
                            <Column header="Jumlah Mati" style="text-align: center;" />
                            <Column header="Total Rujukan Non Medis" style="text-align: center;" />
                            <Column header="Jumlah Hidup" style="text-align: center;" />
                            <Column header="Jumlah Mati" style="text-align: center;" />
                            <Column header="Total Non Rujukan" style="text-align: center;" />
                        </Row>
                        <Row>
                        </Row>
                    </ColumnGroup>
                    <Column field="no" style="min-width: 10px; text-align:center" />
                    <Column field="jenispelayanan" style="min-width: 80px;" />
                    <Column field="rujukanRS" style="min-width: 50px; text-align:center;" />
                    <Column field="rujukanBidan" style="text-align: center;" />
                    <Column field="rujukanPuskes" style="text-align: center;" />
                    <Column field="rujukanFaskesLain" style="text-align: center;" />
                    <Column field="jmlMedisHidup" style="text-align: center;" />
                    <Column field="jmlMedisMati" style="text-align: center;" />
                    <Column field="jmlTotalMedis" style="text-align: center;" />
                    <Column field="jmlNonMedisHidup" style="text-align: center;" />
                    <Column field="jmlNonMedisMati" style="text-align: center;" />
                    <Column field="jmlTotalNonMedis" style="text-align: center;" />
                    <Column field="nonRujukanHidup" style="text-align: center;" />
                    <Column field="nonRujukanMati" style="text-align: center;" />
                    <Column field="totalNonRujukan" style="text-align: center;" />
                    <Column field="dirujuk" style="text-align: center;" />
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
    title: 'Laporan Formulir RL 3.6 Rekapitulasi Kegiatan Pelayanan Kebidanan - ' + import.meta.env.VITE_PROJECT,
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
    await useApi()
    .get(`/laporan/get-laporan-kegiatan-kebidanan?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`)
    .then((response) => {
    const processedData = response.data.map((element: any, index: number) => ({
        ...element,  // Retain all existing properties
        no: index + 1, // Add a new `no` property
    }));

    // Assign the transformed data to `dataSource` for use
    dataSource.value = processedData;
    })
    .catch((error) => {
    console.error('Error fetching data:', error); // Handle errors appropriately
    })
    .finally(() => {
    // Reset loading indicators regardless of success or failure
    loadData.value = false;
    loadSearch.value = false;
    });

}


const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Formulir RL 3.6 Rekapitulasi Kegiatan Pelayanan Kebidanan'], // Row 0
        [], // Row 1
        ['No.', 'Jenis Kegiatan', 'Rujukan Medis', null, null, null, null, null, null, 'Rujukan Non Medis', null, null, 'Non Rujukan', null, null, 'Dirujuk'], // Row 2
        [null, null, 'Rumah Sakit', 'Bidan', 'Puskesmas', 'Faskes Lainnya', 'Jumlah Hidup', 'Jumlah Mati', 'Total Rujukan Medis', 'Jumlah Hidup', 'Jumlah Mati', 'Total Rujukan Non Medis', 'Jumlah Hidup', 'Jumlah Mati', 'Total Non Rujukan', null], // Row 3
        ...dataSource.value.map((e) => [
            e.no,
            e.jenispelayanan,
            e.rujukanRS,
            e.rujukanBidan,
            e.rujukanPuskes,
            e.rujukanFaskesLain,
            e.jmlMedisHidup,
            e.jmlMedisMati,
            e.jmlTotalMedis,
            e.jmlNonMedisHidup,
            e.jmlNonMedisMati,
            e.jmlTotalNonMedis,
            e.nonRujukanHidup,
            e.nonRujukanMati,
            e.totalNonRujukan,
            e.dirujuk,
        ]),
    ]);

    // Header style definition
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

    // Apply styles to rows 0, 1, 2, and 3
    const applyStyleToRow = (rowIndex) => {
        const range = XLSX.utils.decode_range(worksheet['!ref']);
        for (let col = range.s.c; col <= range.e.c; col++) {
            const cell = XLSX.utils.encode_cell({ r: rowIndex, c: col });
            if (worksheet[cell]) {
                worksheet[cell].s = headerStyle;
            }
        }
    };

    applyStyleToRow(0); // Apply to Row 0
    applyStyleToRow(1); // Apply to Row 1
    applyStyleToRow(2); // Apply to Row 2
    applyStyleToRow(3); // Apply to Row 3

    // Setting column widths dynamically
    const columnWidths = [10, 60, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22];
    worksheet['!cols'] = columnWidths.map((wch) => ({ wch }));

    // Merging cells
    worksheet['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 1, c: 15 } },
        { s: { r: 2, c: 0 }, e: { r: 3, c: 0 } },
        { s: { r: 2, c: 1 }, e: { r: 3, c: 1 } },
        { s: { r: 2, c: 2 }, e: { r: 2, c: 8 } },
        { s: { r: 2, c: 9 }, e: { r: 2, c: 11 } },
        { s: { r: 2, c: 12 }, e: { r: 2, c: 14 } },
        { s: { r: 2, c: 15 }, e: { r: 3, c: 15 } },
    ];

    // Export the workbook
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan RM', true);
    XLSXStyle.writeFile(workbook, 'RL 3.6 Rekapitulasi Kegiatan Pelayanan Kebidanan.xlsx');
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
