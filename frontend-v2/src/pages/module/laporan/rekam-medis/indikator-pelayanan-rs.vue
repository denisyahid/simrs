<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Formulir RL 3.1</label>
                        <label for="">INDIKATOR PELAYANAN RUMAH SAKIT</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2" style="margin-top:23px">
                                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                                    Export To Excel
                                </VButton>
                            </div>
                            <div class="column is-10">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-4">
                                        <VField label="Periode" style="margin-bottom: 6px;" />
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
                                     <div class="column is-1 btn-search mt-3" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <Column field="no" style="min-width: 10px; text-align:center" header="NO" />
                    <Column field="kategori_ruangan" style="min-width: 10px; text-align:center" header="Jenis Pelayanan" />
                    <!-- <Column field="koders" header="Kode RS" />
                    <Column field="kodeprov" header="Kode Provinsi" />
                    <Column field="kota" header="Kab / Kota" />
                    <Column field="tahun" header="Tahun"/> -->
                    <Column field="bor" header="BOR" />
                    <Column field="alos" header="LOS" />
                    <Column field="bto" header="BTO" />
                    <Column field="toi" header="TOI" />
                    <Column field="ndr" header="NDR" />
                    <Column field="gdr" header="GDR" />
                    <!-- <Column field="ratarataperhari" header="Rata-rata Kunjungan/Hari" /> -->

                    <ColumnGroup type="footer">
                        <Row>
                            <Column :footer="'77'" style="text-align: center;"/>
                            <Column :footer="'Rata-rata'" style="text-align: center;"/>
                            <Column :footer="avgBor" />
                            <Column :footer="avgAlos" />
                            <Column :footer="avgBto" />
                            <Column :footer="avgToi" />
                            <Column :footer="avgDnr" />
                            <Column :footer="avgGdr" />
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
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import ColumnGroup from 'primevue/columngroup';
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
    isKK: false,
    tglPelayanan: new Date(),
    filterTgl: reactive({
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
let loadData: any = ref(true)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const avgBor = ref(0);
const avgAlos = ref(0);
const avgBto = ref(0);
const avgToi = ref(0);
const avgDnr = ref(0);
const avgGdr = ref(0);

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')

    item.value.Ttotal = 0
    loadSearch.value = true

    

    try {
    // Fetch data from the API
    const response = await useApi().get(`/laporan/indikator-pelayanan-rs?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`);
    
    let data = response.data;

    if (data && typeof data === 'object') {
        data = Object.values(data); // Convert object to array
    }

    if (Array.isArray(data)) {
        // Process data
        const processedData = data.map((element, index) => ({
            ...element,
            no: index + 1, // Add numbering
        }));

        dataSource.value = processedData;

        // Calculate totals and averages
        const totals = processedData.reduce((acc, element) => {
            acc.totalBOR += parseFloat(element.bor) || 0;
            acc.totalALOS += parseFloat(element.alos) || 0;
            acc.totalBTO += parseFloat(element.bto) || 0;
            acc.totalTOI += parseFloat(element.toi) || 0;
            acc.totalNDR += parseFloat(element.ndr) || 0;
            acc.totalGDR += parseFloat(element.gdr) || 0;
            return acc;
        }, {
            totalBOR: 0,
            totalALOS: 0,
            totalBTO: 0,
            totalTOI: 0,
            totalNDR: 0,
            totalGDR: 0,
        });

        const rowCount = processedData.length || 1;

        avgBor.value = (totals.totalBOR / rowCount).toFixed(2);
        avgAlos.value = (totals.totalALOS / rowCount).toFixed(2);
        avgBto.value = (totals.totalBTO / rowCount).toFixed(2);
        avgToi.value = (totals.totalTOI / rowCount).toFixed(2);
        avgDnr.value = (totals.totalNDR / rowCount).toFixed(2);
        avgGdr.value = (totals.totalGDR / rowCount).toFixed(2);
    } else {
        console.error("Unexpected data structure:", response.data);
        dataSource.value = []; // Fallback to empty data
    }
} catch (error) {
    console.error("Error fetching data:", error);
    dataSource.value = []; // Reset data on error
} finally {
    loadSearch.value = false;
    loadData.value = false;
}



}


const exportExcel = () => {
    // Periksa apakah data ada
    if (!dataSource.value || dataSource.value.length === 0) {
        console.error("Data tidak tersedia untuk diekspor.");
        return;
    }

    // Hitung total dan rata-rata jika belum dihitung
    const totals = dataSource.value.reduce((acc, e) => {
        acc.totalBOR += Number(e.bor) || 0; // Pastikan nilai adalah angka
        acc.totalALOS += Number(e.alos) || 0;
        acc.totalBTO += Number(e.bto) || 0;
        acc.totalTOI += Number(e.toi) || 0;
        acc.totalNDR += Number(e.ndr) || 0;
        acc.totalGDR += Number(e.gdr) || 0;
        return acc;
    }, {
        totalBOR: 0,
        totalALOS: 0,
        totalBTO: 0,
        totalTOI: 0,
        totalNDR: 0,
        totalGDR: 0,
    });

    const rowCount = dataSource.value.length || 1;

    // Variabel total dan rata-rata
    const totalBor = totals.totalBOR.toFixed(2); // Total BOR
    const totalAlos = totals.totalALOS.toFixed(2); // Total ALOS
    const totalBto = totals.totalBTO.toFixed(2); // Total BTO
    const totalToi = totals.totalTOI.toFixed(2); // Total TOI
    const totalNdr = totals.totalNDR.toFixed(2); // Total NDR
    const totalGdr = totals.totalGDR.toFixed(2); // Total GDR

    const avgBor = (totals.totalBOR / rowCount).toFixed(2); // Rata-rata BOR
    const avgAlos = (totals.totalALOS / rowCount).toFixed(2); // Rata-rata ALOS
    const avgBto = (totals.totalBTO / rowCount).toFixed(2); // Rata-rata BTO
    const avgToi = (totals.totalTOI / rowCount).toFixed(2); // Rata-rata TOI
    const avgNdr = (totals.totalNDR / rowCount).toFixed(2); // Rata-rata NDR
    const avgGdr = (totals.totalGDR / rowCount).toFixed(2); // Rata-rata GDR

    // Data untuk Excel
    const workbook = XLSX.utils.book_new();
    const header = ['No', 'Jenis Pelayanan', 'BOR', 'ALOS', 'BTO', 'TOI', 'NDR', 'GDR'];

    const data = [
        ['Laporan Indikator Pelayanan Rumah Sakit'], // Judul
        [], // Baris kosong
        header, // Header kolom
        ...dataSource.value.map((e) => [
            e.no,
            e.kategori_ruangan,
            e.bor,
            e.alos,
            e.bto,
            e.toi,
            e.ndr,
            e.gdr,
        ]), // Data baris
        ['77','Rata-rata', avgBor, avgAlos, avgBto, avgToi, avgNdr, avgGdr], // Baris rata-rata
    ];

    const worksheet = XLSX.utils.aoa_to_sheet(data);

    // Tambahkan style pada header
    const headerStyle = {
        fill: { fgColor: { rgb: 'D3D3D3' } }, // Warna abu-abu
        font: { bold: true }, // Teks tebal
        alignment: { horizontal: 'center', vertical: 'center' }, // Pusatkan teks
    };

    // Terapkan style pada header (baris ke-3, indeks 2)
    const headerRowIndex = 2;
    header.forEach((_, colIndex) => {
        const cellAddress = XLSX.utils.encode_cell({ r: headerRowIndex, c: colIndex });
        if (!worksheet[cellAddress]) return;
        worksheet[cellAddress].s = headerStyle;
    });

    // Atur judul agar berada di tengah
    worksheet['!merges'] = worksheet['!merges'] || [];
    worksheet['!merges'].push({
        s: { r: 0, c: 0 }, // Sel awal (baris 0, kolom 0)
        e: { r: 0, c: header.length - 1 }, // Sel akhir (baris 0, kolom terakhir)
    });

    // Style untuk judul
    const titleStyle = {
        alignment: { horizontal: 'center', vertical: 'center' }, // Pusatkan teks
        font: { bold: true, sz: 16 }, // Teks tebal dan ukuran 16
    };

    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 }); // Sel awal untuk judul
    if (worksheet[titleCell]) {
        worksheet[titleCell].s = titleStyle;
    }

    // Atur lebar kolom
    const columnWidths = [10, 30, 15, 15, 15, 15, 15, 15];
    worksheet['!cols'] = columnWidths.map((wch) => ({ wch }));

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Indikator', true);
    XLSXStyle.writeFile(workbook, 'RL 1.2 Indikator Pelayanan RS.xlsx');
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
