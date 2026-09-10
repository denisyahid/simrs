<template>
    <section>
        <div class="column is-12">
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
                        <div class="column is-5">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <AutoComplete v-model="item.qRuangan" :suggestions="d_Ruangan"
                                        @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" @item-select="fetchData()"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Ruangan" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="NO Closing">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.search" v-on:keyup.enter="fetchPagu"
                                        placeholder="No Struk Pagu" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column btn-search mt-3">
                            <VIconButton color="success" icon="fas fa-search" @click="fetchPagu" :loading="loadSearch" />
                        </div>
                    </div>
                </div>
            </VCard>
        </div>

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Daftar Pagu Layanan</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadSearch" />
                <DataTable v-else :rows="5" :value="dataSource" :rowsPerPageOptions="[5, 10, 15]" class="p-datatable-sm"
                    breakpoint="960px" selectionMode="single" sortMode="multiple" v-model:expanded-rows="expandedRows"
                    showGridlines tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="column pt-0 pb-0">
                            <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel">
                                Export To Excel
                            </VButton>
                        </div>
                    </template>
                    <Column field="tglstruk" header="TGL Struk">
                        <template #body="slotProps">
                            {{ H.formatDateIndo(slotProps.data.tglstrukpagu) }}
                        </template>
                    </Column>
                    <Column field="nostrukpagu" header="No Struk Pagu" />
                    <Column field="periodeawal" header="TGL Pagu">
                        <template #body="slotProps">
                            {{ H.formatDateIndo(slotProps.data.periodeawal) }}
                        </template>
                    </Column>
                    <Column field="totalrcdokter" header="DIREKSI">
                        <template #body="slotProps">
                            {{ H.formatRupiah(slotProps.data.totalrcdokter, '') }}
                        </template>
                    </Column>
                    <Column field="totalrc" header="STRUKTURAL">
                        <template #body="slotProps">
                            {{ H.formatRupiah(slotProps.data.totalrc, '') }}
                        </template>
                    </Column>
                    <Column field="totalpostrm" header="CASEMIX">
                        <template #body="slotProps">
                            {{ H.formatRupiah(slotProps.data.totalpostrm, '') }}
                        </template>
                    </Column>
                    <Column field="totalccdireksi" header="JPL">
                        <template #body="slotProps">
                            {{ H.formatRupiah(slotProps.data.totalccdireksi, '') }}
                        </template>
                    </Column>
                    <Column field="totalccstaffdireksi" header="JPTL">
                        <template #body="slotProps">
                            {{ H.formatRupiah(slotProps.data.totalccstaffdireksi, '') }}
                        </template>
                    </Column>
                    <Column field="totalccmanajemen" header="GABUNGAN">
                        <template #body="slotProps">
                            {{ H.formatRupiah(slotProps.data.totalccmanajemen, '') }}
                        </template>
                    </Column>
                    <Column field="isbayar" header="Status Bayar" style="text-align:center">
                        <template #body="slotProps">
                            {{ slotProps.data.isbayar ? '✔' : '' }}
                        </template>
                    </Column>
                    <Column :exportable="false" header="Action">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined
                                raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                            </VIconButton>
                            <OverlayPanel ref="op">
                                <VButton type="button" icon="fas fa-print" class="mr-2" light circle outlined color="info"
                                    raised @click="changeStatusBayar(selected, true)"
                                    :disabled="slotProps.data.isbayar == true">
                                    Verifikasi Bayar
                                </VButton>
                                <VButton type="button" icon="fas fa-undo" class="mr-2" color="warning" circle outlined
                                    raised @click="changeStatusBayar(selected, false)"
                                    :disabled="slotProps.data.isbayar != true"> Unverifikasi
                                </VButton>
                            </OverlayPanel>
                        </template>
                    </Column>
                    <ColumnGroup type="footer">
                        <Row>
                            <Column footer="Total" tyle="min-width: 80px;" />
                            <Column />
                            <Column />
                            <Column :footer="item.FDireksi" />
                            <Column :footer="item.FStruktural" />
                            <Column :footer="item.FCasemix" />
                            <Column :footer="item.FJPL" />
                            <Column :footer="item.FJPTL" />
                            <Column :footer="item.FGabungan" />
                            <Column />
                            <Column />
                        </Row>
                    </ColumnGroup>
                    <template #footer>
                        <div class="column pt-0 pb-0" style="text-align:right">
                            <VButtons style="justify-content: flex-end">
                                <VButton class="mr-4" color="danger" outlined icon="fas fa-lock"
                                    style="padding-right: 3rem;padding-left: 3rem;" @click="closingData"> Closing </VButton>
                                <VButton class="mr-4" color="warning" raised> Potongan </VButton>
                                <VButton class="mr-4" color="primary" raised @click="goToMap"> Map Pegawai </VButton>
                                <VButton color="info" raised> Detail Sumber Dana </VButton>
                            </VButtons>
                        </div>
                    </template>
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
import DataTable from 'primevue/datatable'
import OverlayPanel from 'primevue/overlaypanel';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Perhitungan Index Pegawai - ' + import.meta.env.VITE_PROJECT,
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

const router = useRouter()
const confirm = useConfirm()
const activeTab = ref(0);
const dataSource: any = ref([])
const dataSourceNilaiPagu: any = ref([])
const detailResep: any = ref([])
const op = ref();
const selected: any = ref({})
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
const expandedRows = ref();
let d_KelompokPasien: any = ref([])
let d_Ruangan: any = ref([])
let loadSearch: any = ref(false)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchPagu = async () => {

    let search = item.value.search ? `&search=${item.value.search}` : ''
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD 00:00:00')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD 23:59:59')
    item.value.TDireksi = 0
    item.value.TStruktural = 0
    item.value.TCasemix = 0
    item.value.TJPL = 0
    item.value.TJPTL = 0
    item.value.TGabungan = 0
    await useApi().get(`remunerasi/get-daftar-pagu-layanan?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${search}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.TDireksi = parseFloat(element.totalrcdokter) + item.value.TDireksi
            item.value.TStruktural = parseFloat(element.totalrc) + item.value.TStruktural
            item.value.TCasemix = parseFloat(element.totalpostrm) + item.value.TCasemix
            item.value.TJPL = parseFloat(element.totalccdireksi) + item.value.TJPL
            item.value.TJPTL = parseFloat(element.totalccstaffdireksi) + item.value.TJPTL
            item.value.TGabungan = parseFloat(element.totalccmanajemen) + item.value.TGabungan
        });
        dataSource.value = response.data
    })
    item.value.FDireksi = H.formatRupiah(item.value.TDireksi, '')
    item.value.FStruktural = H.formatRupiah(item.value.TStruktural, '')
    item.value.FCasemix = H.formatRupiah(item.value.TCasemix, '')
    item.value.FJPL = H.formatRupiah(item.value.TJPL, '')
    item.value.FJPTL = H.formatRupiah(item.value.TJPTL, '')
    item.value.FGabungan = H.formatRupiah(item.value.TGabungan, '')
}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Pagu Layanan'],
        [],
        ['NO', 'TANGGAL STRUK', 'NO STRUK PAGU', 'TANGGAL PAGU', 'DIREKSI', 'STRUKTURAL',
            'CASEMIX', 'JPL', 'JPTL', 'GABUNGAN', '', 'GABUNGAN', 'STATUS BAYAR'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.tglstrukpagu,
            e.nostrukpagu,
            e.periodeawal,
            e.totalrcdokter ? H.roundToDecimal(parseFloat(e.totalrcdokter), 2) : 0,
            e.totalrc ? H.roundToDecimal(parseFloat(e.totalrc), 2) : 0,
            e.totalpostrm ? H.roundToDecimal(parseFloat(e.totalpostrm), 2) : 0,
            e.totalccdireksi ? H.roundToDecimal(parseFloat(e.totalccdireksi), 2) : 0,
            e.totalccstaffdireksi ? H.roundToDecimal(parseFloat(e.totalccstaffdireksi), 2) : 0,
            e.totalccmanajemen ? H.roundToDecimal(parseFloat(e.totalccmanajemen), 2) : 0,
            e.isbayar == true ? 'Sudah Terbayar' : 'Belum Terbayar'
        ]),
        ['', 'TOTAL', '', '',
            H.roundToDecimal(parseFloat(item.value.TDireksi), 2),
            H.roundToDecimal(parseFloat(item.value.TStruktural), 2),
            H.roundToDecimal(parseFloat(item.value.TCasemix), 2),
            H.roundToDecimal(parseFloat(item.value.TJPL), 2),
            H.roundToDecimal(parseFloat(item.value.TJPTL), 2),
            H.roundToDecimal(parseFloat(item.value.TGabungan), 2),
            ''
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

    // atur lebar column
    const columnWidths = [5, 13, 18, 18, 18, 18, 18, 18, 18, 18, 10];


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

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Pagu Layanan', true);
    XLSXStyle.writeFile(workbook, 'Daftar Pagu Layanan.xlsx');
}

const toggle = (event: any, e: any) => {
    console.log(event)
    op.value.toggle(event);
    selected.value = e
}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const goToMap = () => {
    router.push({
        name: 'module-sysadmin-mapping-jasa-pelayanan-to-pegawai'
    })
}

fetchPagu()

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
