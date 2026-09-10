<template>
    <section>
        <!-- <div class="column is-12">
            <VCard style="padding-bottom: 0px">
                <div class="column c-title pt-2 mb-0">
                    <label class="title-page">Pencarian</label>
                </div>
                <div class="column is-12 pt-3">
                    <div class="columns is-multiline">
                        
                    </div>
                </div>
            </VCard>
        </div> -->

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Laporan Pendapatan Rumah Sakit</label>
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
                                        <VField label="Tanggal Pulang" style="margin-bottom: 6px;" />
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
                                    <div class="column is-5">
                                        <VField class=" is-rounded-select is-autocomplete-select">
                                            <VLabel>Kelompok Pasien</VLabel>
                                            <VControl icon="feather:search" class="prime-auto">
                                                <Dropdown v-model="item.kelompokpasien" :options="d_KelompokPasien"
                                                    :optionLabel="'label'" placeholder="Kelompok Pasien" :optionValue="'value'"
                                                    style="width: 100%;" :filter="true" appendTo="body" showClear />
                                            </VControl>
                                        </VField>
                                    </div>
                                     <div class="column is-1 btn-search mt-3" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="column pt-0">
                                <div class="column is-3">
                                    <VField label="Tanggal Pulang" style="margin-bottom: 6px;" />
                                    <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                                <div class="column is-3">
                                    <VField class=" is-rounded-select is-autocomplete-select">
                                        <VLabel>Kelompok Pasien</VLabel>
                                        <VControl icon="feather:search" class="prime-auto">
                                            <Dropdown v-model="item.kelompokpasien" :options="d_KelompokPasien"
                                                :optionLabel="'label'" placeholder="Kelompok Pasien" :optionValue="'value'"
                                                style="width: 100%;" :filter="true" appendTo="body" showClear />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-1 btn-search mt-3">
                                    <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                        :loading="loadSearch" />
                                </div>
                            </div> -->
                        </div>
    
                    </template>
                    <Column field="noregistrasi" header="No Registrasi" />
                    <Column field="nocm" header="No RM" />
                    <Column field="namapasien" header="Nama Pasien" />
                    <Column field="kelompokpasien" header="Jenis" />
                    <Column field="total" header="Total" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.total), 2), '') }}
                        </template>
                    </Column>
                    <ColumnGroup type="footer">
                        <Row>
                            <Column footer="Total" style="padding: 0.3rem 0.3rem 0 0.3rem" />
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
let d_KelompokPasien: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD 00:00:00')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD 23:59:59 ')
    let kelompokpasien = item.value.kelompokpasien ? `&kpId=${item.value.kelompokpasien}` : ''

    item.value.Ttotal = 0
    loadSearch.value = true
    await useApi().get(`remunerasi/get-laporan-pendapatan-rs?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${kelompokpasien}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.Ttotal = parseFloat(element.total) + item.value.Ttotal
        });

        item.value.Ftotal = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.Ttotal, 2), '')
        dataSource.value = response.data
    })
    loadData.value = false
    loadSearch.value = false
}

const fetchKelompokPasien = async () => {
    useApi().get('remunerasi/get-combo-idx').then((response) => {
        d_KelompokPasien.value = response.kelompokpasien.map((e: any) => {
            return { label: e.kelompokpasien, value: e.id }
        })
    })
}

const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Remunerasi Kelompok Penghasil'],
        [],
        ['NO', 'NO REGISTRASI', 'NO CM', 'NAMA PASIEN', 'JENIS', 'TOTAL'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.noregistrasi,
            e.nocm,
            e.namapasien,
            e.kelompokpasien,
            e.total ? H.roundToDecimal(parseFloat(e.total), 2) : 0,
        ]),
        ['', 'TOTAL', '', '', '',
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

    const columnWidths = [5, 13, 10, 20, 18,18];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 5 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Pendapatan', true);
    XLSXStyle.writeFile(workbook, 'Laporan Pendapatan.xlsx');
}

fetchKelompokPasien()
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
