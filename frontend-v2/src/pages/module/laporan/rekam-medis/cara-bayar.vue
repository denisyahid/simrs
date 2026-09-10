<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Formulir RL 3.19</label>
                        <label for="">Rekapitulasi Cara Bayar</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="15" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
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
                            <div class="column is-1" style="padding-top: 38px;text-align:center">
                                <VIconButton color="success" icon="fas fa-search" @click="fetchData()" :loading="loadSearch" />
                            </div>
                        </div>
                    </div>
                </div>
            </template>

    <ColumnGroup type="header">
        <Row>
            <Column header="No" style="min-width: 15px; text-align: center;" :rowspan="2" />
            <Column header="Cara Pembayaran" style="min-width: 80px; text-align: center;" :rowspan="2" />
            <Column header="Pasien Rawat Inap" style="text-align: center;" :colspan="2" />
            <Column header="Jumlah Pasien Rawat Jalan" style="text-align: center" :rowspan="2" />
            <Column header="Jumlah Pasien Rawat Jalan" style="text-align: center" :colspan="3" />
        </Row>
        <Row>
            <Column header="Jumlah Pasien Keluar" style="text-align: center;" />
            <Column header="Jumlah Lama Dirawat" style="text-align: center;" />
            <Column header="Laboratorium" style="text-align: center;" />
            <Column header="Radiologi" style="text-align: center;" />
            <Column header="Lain-lain" style="text-align: center;" />
        </Row>
    </ColumnGroup>

    <Column field="no" style="min-width: 10px; text-align:center" />
    <Column field="golbayar" style="min-width: 80px;" />
    <Column field="pasienKeluarRI" style="min-width: 50px;" />
    <Column field="pasienLamaDirawatRI" />
    <Column field="pasienRawatJalan" />
    <Column field="pasienLab" />
    <Column field="pasienRad" />
    <Column field="pasienLain" />

    <ColumnGroup type="footer">
        <Row>
            <Column :footer="'Total'" :colspan="2"/>
            <Column :footer="totalPasienKeluarRI" />
            <Column :footer="totalPasienLamaDirawatRI" />
            <Column :footer="totalPasienRawatJalan" />
            <Column :footer="totalPasienLab" />
            <Column :footer="totalPasienRad" />
            <Column :footer="totalPasienLain" />
        </Row>
    </ColumnGroup>


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
    title: 'Cara Bayar - ' + import.meta.env.VITE_PROJECT,
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
    let tglAwal = H.formatDate(item.value.filterDate.start, 'YYYY-MM-DD');
    let tglAkhir = H.formatDate(item.value.filterDate.end, 'YYYY-MM-DD');

    // Indicate loading state
    loadSearch.value = true;
    loadData.value = true;

    try {
        // Fetch data from the API
        const response = await useApi().get(`/laporan/get-laporan-cara-bayar?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`);
        
        // Check if response.data is an object with numeric keys (like an array)
        let data = response.data;
        if (data && typeof data === 'object') {
            // Convert the object to an array
            data = Object.values(data);
        }

        if (Array.isArray(data)) {
            // Process the response data and compute totals
            const processedData = data.map((element, index) => ({
                ...element, // Retain all existing properties
                no: index + 1, // Add a numbering property
            }));

            // Assign processed data to `dataSource`
            dataSource.value = processedData;

            // Calculate totals for each column (assuming numerical fields)
            const totals = processedData.reduce((acc, element) => {
                acc.totalPasienKeluarRI += element.pasienKeluarRI || 0;
                acc.totalPasienLamaDirawatRI += element.pasienLamaDirawatRI || 0;
                acc.totalPasienRawatJalan += element.pasienRawatJalan || 0;
                acc.totalPasienLab += element.pasienLab || 0;
                acc.totalPasienRad += element.pasienRad || 0;
                acc.totalPasienLain += element.pasienLain || 0;
                return acc;
            }, {
                totalPasienKeluarRI: 0,
                totalPasienLamaDirawatRI: 0,
                totalPasienRawatJalan: 0,
                totalPasienLab: 0,
                totalPasienRad: 0,
                totalPasienLain: 0,
            });

            // Assign totals to a reactive variable for rendering
            totalPasienKeluarRI.value = totals.totalPasienKeluarRI;
            totalPasienLamaDirawatRI.value = totals.totalPasienLamaDirawatRI;
            totalPasienRawatJalan.value = totals.totalPasienRawatJalan;
            totalPasienLab.value = totals.totalPasienLab;
            totalPasienRad.value = totals.totalPasienRad;
            totalPasienLain.value = totals.totalPasienLain;
        } else {
            console.error("Unexpected data structure:", response.data);
            dataSource.value = []; // Fallback to empty data
        }
    } catch (error) {
        // Log and handle errors
        console.error("Error fetching data:", error);
        dataSource.value = []; // Reset data on error
    } finally {
        // Reset loading states
        loadSearch.value = false;
        loadData.value = false;
    }
};





const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Formulir RL 3.19 Rekapitulasi Cara Bayar'], // Row 0
        [], // Row 1
        ['No.', 'Cara Pembayaran', 'Pasien Rawat Inap', null, 'Jumlah Pasien Rawat Jalan', 'Jumlah Pasien Rawat Jalan', null, null], // Row 2
        [null, null, 'Jumlah Pasien Keluar', 'Jumlah Lama Dirawat', null, 'Laboratorium', 'Radiologi', 'Lain-lain'], // Row 3
        ...dataSource.value.map((e) => [
            e.no,
            e.golbayar,
            e.pasienKeluarRI,
            e.pasienLamaDirawatRI,
            e.pasienRawatJalan,
            e.pasienLab,
            e.pasienRad,
            e.pasienLain,
        ]),
        [], // Empty row for footer space
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

    // Merging cells to match the column groups
    worksheet['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 1, c: 7 } }, // Merge across the first row
        { s: { r: 2, c: 0 }, e: { r: 3, c: 0 } }, // Merge 'No' column
        { s: { r: 2, c: 1 }, e: { r: 3, c: 1 } }, // Merge 'Cara Pembayaran' column
        { s: { r: 2, c: 2 }, e: { r: 2, c: 3 } }, // Merge 'Pasien Rawat Inap' columns
        { s: { r: 2, c: 4 }, e: { r: 3, c: 4 } }, // Merge 'Jumlah Pasien Rawat Jalan' columns
        { s: { r: 2, c: 5 }, e: { r: 2, c: 7 } }, // Merge 'Pasien Rawat Jalan' columns
    ];

    // Adding totals for each column
    const totalPasienKeluarRI = dataSource.value.reduce((sum, e) => sum + (e.pasienKeluarRI || 0), 0);
    const totalPasienLamaDirawatRI = dataSource.value.reduce((sum, e) => sum + (e.pasienLamaDirawatRI || 0), 0);
    const totalPasienRawatJalan = dataSource.value.reduce((sum, e) => sum + (e.pasienRawatJalan || 0), 0);
    const totalPasienLab = dataSource.value.reduce((sum, e) => sum + (e.pasienLab || 0), 0);
    const totalPasienRad = dataSource.value.reduce((sum, e) => sum + (e.pasienRad || 0), 0);
    const totalPasienLain = dataSource.value.reduce((sum, e) => sum + (e.pasienLain || 0), 0);

    // Adding the footer row
    const footerRowIndex = dataSource.value.length + 5; // Adjust for footer row
    worksheet['A' + footerRowIndex] = { v: 'Total', s: headerStyle };
    worksheet['B' + footerRowIndex] = { v: '' }; // Empty column for header merge

    worksheet['C' + footerRowIndex] = { v: totalPasienKeluarRI };
    worksheet['D' + footerRowIndex] = { v: totalPasienLamaDirawatRI };
    worksheet['E' + footerRowIndex] = { v: totalPasienRawatJalan };
    worksheet['F' + footerRowIndex] = { v: totalPasienLab };
    worksheet['G' + footerRowIndex] = { v: totalPasienRad };
    worksheet['H' + footerRowIndex] = { v: totalPasienLain };

    // Footer styling
    const footerStyle = {
    font: { bold: true },
    alignment: { horizontal: 'center', vertical: 'center' },
    border: {
        top: { style: 'thin' },
        bottom: { style: 'thin' },
        left: { style: 'thin' },
        right: { style: 'thin' },
    },
};

// Apply footer style to the footer cells
for (let col = 0; col <= 7; col++) {
    const cell = XLSX.utils.encode_cell({ r: footerRowIndex, c: col });
    if (worksheet[cell]) {
        worksheet[cell].s = footerStyle;
    }
}


    // Export the workbook
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan RM', true);
    XLSXStyle.writeFile(workbook, 'RL 3.19 Rekapitulasi Cara Bayar.xlsx');
};



fetchData()

const totalPasienKeluarRI = ref(0);
const totalPasienLamaDirawatRI = ref(0);
const totalPasienRawatJalan = ref(0);
const totalPasienLab = ref(0);
const totalPasienRad = ref(0);
const totalPasienLain = ref(0);


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
