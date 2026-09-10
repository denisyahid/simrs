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
                            <VField label="Tanggal" style="margin-bottom: 6px;" />
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
                        <div class="column is-3">
                            <VField class=" is-rounded-select is-autocomplete-select">
                                <VLabel>Jenis Pagu</VLabel>
                                <VControl icon="feather:search" class="prime-auto">
                                    <Dropdown v-model="item.jenispagu" :options="d_JenisPagu" :optionLabel="'label'"
                                        placeholder="Pilih Jenis Pagu" :optionValue="'value'" style="width: 100%;"
                                        :filter="true" appendTo="body" showClear />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column">
                            <VField>
                                <VLabel>No Closing</VLabel>
                                <VControl>
                                    <input v-model="item.noclosing" v-on:keyup.enter="fetchData()" class="input" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1 btn-search mt-3">
                            <VIconButton color="success" icon="fas fa-search" @click="fetchData" :loading="loadSearch" />
                        </div>
                    </div>
                </div>
            </VCard>
        </div>

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Daftar Remunerasi Jabatan</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="column pt-0 pb-0">
                            <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel">
                                Export To Excel
                            </VButton>
                        </div>
                    </template>
                    <Column field="no" header="No" />
                    <Column field="tglclosing" header="TGL Closing">
                        <template #body="slotProps">
                            {{ H.formatDateToLocalString(slotProps.data.tglclosing) }}
                        </template>
                    </Column>
                    <Column field="noclosing" header="No Closing" />
                    <Column field="tglawal" header="TGL Awal">
                        <template #body="slotProps">
                            {{ H.formatDateToLocalString(slotProps.data.tglawal) }}
                        </template>
                    </Column>
                    <Column field="tglakhir" header="TGL Akhir">
                        <template #body="slotProps">
                            {{ H.formatDateToLocalString(slotProps.data.tglakhir) }}
                        </template>
                    </Column>
                    <Column field="jenispagu" header="Jenis Pagu" />
                    <Column field="detailjenispagu" header="Detail Jenis Pagu" />
                    <Column field="jml" header="Total" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.jml), 2), '') }}
                        </template>
                    </Column>
                    <ColumnGroup type="footer">
                        <Row>
                            <Column footer="Total" style="padding: 0.3rem 0.3rem 0 0.3rem" />
                            <Column />
                            <Column />
                            <Column />
                            <Column />
                            <Column />
                            <Column />
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
    title: 'Daftar Remunerasi Pegawai - ' + import.meta.env.VITE_PROJECT,
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
let d_JenisPagu: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let jenisPagu = item.value.jenispagu ? `&jenispagufk=${item.value.jenispagu}` : ''
    let noclosing = item.value.noclosing ? `&noclosing=${item.value.noclosing}` : ''

    item.value.Ttotal = 0
    loadSearch.value = true
    await useApi().get(`remunerasi/get-daftar-detail-jenis-pagu-remun?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${jenisPagu}${noclosing}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.Ttotal = parseFloat(element.jml) + item.value.Ttotal
        });

        item.value.Ftotal = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Ttotal, 2), '')
        dataSource.value = response
    })
    loadData.value = false
    loadSearch.value = false
}

const fetchJenisPagu = async () => {
    useApi().get('remunerasi/get-combo-idx').then((response) => {
        d_JenisPagu.value = response.jenispagu.map((e: any) => {
            return { label: e.jenispagu, value: e.id }
        })
    })
}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Remunerasi Jabatan'],
        [],
        ['NO', 'TANGGAL CLOSING', 'NO CLOSING', 'TANGGAL AWAL', 'TANGGAL AKHIR', 'JENIS PAGU', 'DETAIL JENIS PAGU', 'TOTAL'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.tglclosing,
            e.noclosing,
            e.tglawal,
            e.tglakhir,
            e.jenispagu,
            e.detailjenispagu,
            e.jml ? H.roundToDecimal(parseFloat(e.jml), 2) : 0,
        ]),
        ['', 'TOTAL', '', '', '', '', '',
            H.roundToDecimal(parseFloat(item.value.Ttotal), 2),
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

    const columnWidths = [5, 10, 13, 10, 10, 15, 20, 18];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 7 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Remun Jabatan', true);
    XLSXStyle.writeFile(workbook, 'Daftar Remun Jabatan.xlsx');
}

fetchJenisPagu()
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
