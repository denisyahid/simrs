<template>
    <section>
        <!-- <div class="column is-12">
            <VCard style="padding-bottom: 0px">
                <div class="column c-title pt-2 mb-0">
                    <label class="title-page">Pencarian</label>
                </div>
                <div class="column is-12 pt-3">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <VField label="Periode Pagu" style="margin-bottom: 6px;" />
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
                        <div class="column is-1 btn-search mt-3">
                            <VIconButton color="success" icon="fas fa-search" @click="fetchData" :loading="loadSearch" />
                        </div>
                    </div>
                </div>
            </VCard>
        </div> -->

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2">
                    <div class="column is-10 p-0">
                        <label class="title-page">Rincian Pendapatan</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                <DataTable v-else :rows="12" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm" breakpoint="960px" selectionMode="single" sortMode="multiple"
                    v-model:expanded-rows="expandedRows" showGridlines tableStyle="min-width: 80rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline" style="justify-content: space-between;">
                            <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel"
                                class="mt-5 ml-3">
                                Export To Excel
                            </VButton>
                            <div class="column is-2">
                                <VField label="Periode">
                                    <Calendar v-model="item.year" view="year" dateFormat="yy" showIcon class="modif"
                                        @date-select="fetchData" />
                                </VField>
                            </div>
                        </div>
                    </template>
                    <ColumnGroup type="header">
                        <Row>
                            <Column header="Bulan" :rowspan="3" style="min-width: 120px;" frozen />
                        </Row>
                        <Row>
                            <Column header="Pendapatan Lain-lain" :colspan="8" style="min-width: 500px;" />
                            <Column header="Total" :rowspan="2" />
                        </Row>
                        <Row>
                            <Column header="UMUM" sortable field="umum" style="min-width: 100px;text-align:right" />
                            <Column header="BPJS" sortable field="bpjs" style="min-width: 100px;text-align:right" />
                            <Column header="SKTM" sortable field="sktm" style="min-width: 100px;text-align:right" />
                            <Column header="JASARAHARJA" sortable field="jasaraharja"
                                style="min-width: 100px;text-align:right" />
                            <Column header="PENDIDIKAN" sortable field="diklat" style="min-width: 100px;text-align:right" />
                            <Column header="MCU" sortable field="mcu" style="min-width: 100px;text-align:right" />
                            <Column header="COVID" sortable field="covid" style="min-width: 100px;text-align:right" />
                            <Column header="LAIN-LAIN" sortable field="lainlain"
                                style="min-width: 100px;text-align:right" />
                            <!-- <Column header="JASA GIRO" sortable field="jasagiro"
                                style="min-width: 100px;text-align:right" />
                            <Column header="DENDA / SUSULAN BPJS" sortable field="jasagiro"
                                style="min-width: 100px;text-align:right" /> -->
                        </Row>
                    </ColumnGroup>
                    <Column field="blnstr" frozen />
                    <Column field="umum" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.umum), 2), '') }}
                        </template>
                    </Column>
                    <Column field="bpjs" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.bpjs), 2), '') }}
                        </template>
                    </Column>
                    <Column field="sktm" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.sktm), 2), '') }}
                        </template>
                    </Column>
                    <Column field="jasaraharja" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.jasaraharja), 2), '') }}
                        </template>
                    </Column>
                    <Column field="diklat" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.diklat), 2), '') }}
                        </template>
                    </Column>
                    <Column field="mcu" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.mcu), 2), '') }}
                        </template>
                    </Column>
                    <Column field="covid" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.covid), 2), '') }}
                        </template>
                    </Column>
                    <Column field="lainlain" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.lainlain), 2), '') }}
                        </template>
                    </Column>
                    <Column field="total" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.total), 2), '') }}
                        </template>
                    </Column>
            

                    <ColumnGroup type="footer">
                        <Row>
                            <Column footer="Total" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:center" />
                            <Column :footer="item.Fumum" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column :footer="item.Fbpjs" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column :footer="item.Fsktm" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column :footer="item.Fjasaraharja" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column :footer="item.Fpendidikan" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column :footer="item.Fmcu" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column :footer="item.Fcovid" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column :footer="item.Flain" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column :footer="item.Ftotal" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
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
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import Calendar from 'primevue/calendar';
import DataTable from 'primevue/datatable'
import AutoComplete from 'primevue/autocomplete';
import OverlayPanel from 'primevue/overlaypanel';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Daftar Remunerasi Pegawai - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    year: new Date(),
    tglPelayanan: new Date(),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const router = useRouter()
const confirm = useConfirm()
const activeTab = ref(0);
const dataSource: any = ref([])
const expandedRows = ref();
let d_Pegawai: any = ref([])
let d_Ruangan: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let isLoading: any = ref(false)

const fetchData = async () => {

    item.value.Tumum = 0
    item.value.Tbpjs = 0
    item.value.Tsktm = 0
    item.value.Tjasaraharja = 0
    item.value.Tpendidikan = 0
    item.value.Tmcu = 0
    item.value.Tcovid = 0
    item.value.Tlain = 0
    item.value.Tjasagiro = 0
    item.value.Tdenda = 0
    item.value.Ttotal = 0

    loadSearch.value = true
    await useApi().get(`/remunerasi/get-rincian-pendapatan?tahun=${H.formatDate(item.value.year, 'YYYY')}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.denda = 0
            item.value.Tumum = parseFloat(element.umum) + item.value.Tumum
            item.value.Tsktm = parseFloat(element.sktm) + item.value.Tsktm
            item.value.Tbpjs = parseFloat(element.bpjs) + item.value.Tbpjs
            item.value.Tcovid = parseFloat(element.covid) + item.value.Tcovid
            item.value.Tmcu = parseFloat(element.mcu) + item.value.Tmcu
            item.value.Tpendidikan = parseFloat(element.diklat) + item.value.Tpendidikan
            item.value.Tjasagiro = parseFloat(element.jasagiro) + item.value.Tjasagiro
            item.value.Tjasaraharja = parseFloat(element.jasaraharja) + item.value.Tjasaraharja
            item.value.Tlain = parseFloat(element.lainlain) + item.value.Tlain
            item.value.Ttotal = parseFloat(element.total) + item.value.Ttotal
        });

        item.value.Fumum = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Tumum, 2), '')
        item.value.Fsktm = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Tsktm, 2), '')
        item.value.Fbpjs = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Tbpjs, 2), '')
        item.value.Fcovid = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Tcovid, 2), '')
        item.value.Fmcu = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Tmcu, 2), '')
        item.value.Fpendidikan = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Tpendidikan, 2), '')
        item.value.Fjasagiro = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Tjasagiro, 2), '')
        item.value.Fjasaraharja = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Tjasaraharja, 2), '')
        item.value.Flain = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Tlain, 2), '')
        item.value.Ftotal = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Ttotal, 2), '')
        dataSource.value = response
    })

    loadData.value = false
    loadSearch.value = false
}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Rincian Pendapatan'],
        [],
        ['NO', 'BULAN', 'UMUM', 'BPJS', 'SKTM', 'JASARAHARJA', 'PENDIDIKAN', 'MCU',
            'COVID', 'LAIN-LAIN', 'JASA GIRO', 'DENDA / SUSULAN BPJS'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.blnstr,
            H.roundToDecimal(parseFloat(e.umum), 2),
            H.roundToDecimal(parseFloat(e.bpjs), 2),
            H.roundToDecimal(parseFloat(e.sktm), 2),
            H.roundToDecimal(parseFloat(e.jasaraharja), 2),
            H.roundToDecimal(parseFloat(e.diklat), 2),
            H.roundToDecimal(parseFloat(e.mcu), 2),
            H.roundToDecimal(parseFloat(e.covid), 2),
            H.roundToDecimal(parseFloat(e.lainlain), 2),
            H.roundToDecimal(parseFloat(e.jasagiro), 2),
            H.roundToDecimal(parseFloat(e.jasagiro), 2),
        ]),
        ['', 'TOTAL',
            H.roundToDecimal(parseFloat(item.value.Tumum), 2),
            H.roundToDecimal(parseFloat(item.value.Tbpjs), 2),
            H.roundToDecimal(parseFloat(item.value.Tsktm), 2),
            H.roundToDecimal(parseFloat(item.value.Tjasaraharja), 2),
            H.roundToDecimal(parseFloat(item.value.Tpendidikan), 2),
            H.roundToDecimal(parseFloat(item.value.Tmcu), 2),
            H.roundToDecimal(parseFloat(item.value.Tcovid), 2),
            H.roundToDecimal(parseFloat(item.value.Tlain), 2),
            H.roundToDecimal(parseFloat(item.value.Tjasagiro), 2),
            H.roundToDecimal(parseFloat(item.value.Tjasagiro), 2),

        ]
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

    const columnWidths = [5, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 11 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Rincian Pendapatan', true);
    XLSXStyle.writeFile(workbook, 'Rincian Pendapatan.xlsx');
}

const changeStatusBayar = async (e: any, status: any) => {
    let objsave = {
        'norec': e.norec,
        'status': status
    }
    await useApi().post('remunerasi/verifikasi-bayar', objsave).then((response) => {
        fetchPagu()
    })
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

.modif {
    .p-inputtext {
        border-radius: unset !important
    }
}
</style>
