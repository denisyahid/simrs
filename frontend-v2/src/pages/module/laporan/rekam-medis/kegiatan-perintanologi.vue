<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Formulir RL 3.7</label>
                        <label for="">Rekapitulasi Kegiatan Pelayanan Neonatal, Bayi, dan Balita</label>
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
                            <Column header="No" style="min-width: 15px; text-align: center;" :rowspan="3" />
                            <Column header="JENIS KEGIATAN" style="min-width: 80px; text-align: center;" :rowspan="3" />
                            <Column header="RUJUKAN" style="text-align: center;" :colspan="10" />
                            <Column header="NON RUJUKAN" style="text-align: center" :colspan="3" />
                            <Column header="DIRUJUK" style="text-align: center" :rowspan="3" />
                        </Row>
                        <Row>
                            <Column header="MEDIS" style="text-align: center;" :colspan="7"/>
                            <Column header="NON MEDIS" style="text-align: center;" :colspan="3" />
                            <Column header="JUMLAH HIDUP" style="text-align: center;" :rowspan="2"/>
                            <Column header="JUMLAH MATI" style="text-align: center;"  :rowspan="2"/>
                            <Column header="JUMLAH TOTAL" style="text-align: center;" :rowspan="2" />
                        </Row>
                        <Row>
                            <Column header="RUMAH SAKIT" style="text-align: center;"/>
                            <Column header="BIDAN" style="text-align: center;" />
                            <Column header="PUSKESMAS" style="text-align: center;" />
                            <Column header="FASKES LAINNYA" style="text-align: center;min-width: 20px;" />
                            <Column header="JUMLAH HIDUP" style="text-align: center;" />
                            <Column header="JUMLAH MATI" style="text-align: center;" />
                            <Column header="JUMLAH TOTAL" style="text-align: center;" />
                            <Column header="JUMLAH HIDUP" style="text-align: center;" />
                            <Column header="JUMLAH MATI" style="text-align: center;" />
                            <Column header="JUMLAH TOTAL" style="text-align: center;" />
                        </Row>
                    </ColumnGroup>
                    <Column field="no" style="min-width: 10px; text-align:center" />
                    <Column field="jenispelayanan" style="min-width: 80px;" />
                    <Column field="rujukanRS" style="min-width: 50px;" />
                    <Column field="rujukanBidan" />
                    <Column field="rujukanPuskes" />
                    <Column field="rujukanFaskesLain" />
                    <Column field="jmlMedisHidup" />
                    <Column field="jmlMedisMati" />
                    <Column field="jmlTotalMedis" />
                    <Column field="jmlNonMedisHidup" />
                    <Column field="jmlNonMedisMati" />
                    <Column field="jmlTotalNonMedis" />
                    <Column field="nonRujukanHidup" />
                    <Column field="nonRujukanMati" />
                    <Column field="totalNonRujukan" />
                    <Column field="dirujuk" />
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
    title: 'Laporan Formulir RL 3.5 - KEGIATAN PERINTANOLOGI - ' + import.meta.env.VITE_PROJECT,
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
    await useApi().get(`/laporan/get-laporan-kegiatan-perinatologi?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`).then((response) => {
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
        ['Rekapitulasi Kegiatan Pelayanan Neonatal, Bayi, dan Balita'],
        [],
        ['NO', 'JENIS KEGIATAN', 'RUJUKAN RS', 'RUJUKAN BIDAN', 'RUJUKAN PUSKESMAS', 'RUJUKAN FASKES LAIN', 'JML MEDIS HIDUP', 'JML MEDIS MATI', 'JML TOTAL MEDIS', 'JML NON MEDIS HIDUP', 'JML NON MEDIS MATI', 'JML TOTAL NON MEDIS', 'NON RUJUKAN HIDUP', 'NON RUJUKAN MATI', 'TOTAL NON RUJUKAN', 'DIRUJUK'],
        ...dataSource.value.map((e: any) => [
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
            e.dirujuk
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

    const columnWidths = [8, 15, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 2 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan RM', true);
    XLSXStyle.writeFile(workbook, 'RL 3.7 Rekapitulasi Kegiatan Pelayanan Neonatal, Bayi, dan Balita.xlsx');
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
</style>
