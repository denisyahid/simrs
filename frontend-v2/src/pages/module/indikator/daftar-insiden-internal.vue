<template>
    <ConfirmDialog />
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Daftar Laporan Insiden ( Internal )</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple"
                    showGridlines tableStyle="min-width: 30rem"
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
                                    <div class="column is-3">
                                        <VField class="is-rounded-select is-autocomplete-select" label="Ruangan Asal">
                                            <VControl icon="feather:search" class="prime-auto-select">
                                                <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan"
                                                    @complete="fetchRuangan($event)" :optionLabel="'label'"
                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    placeholder="Pilih Ruangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-1 btn-search mt-3" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </template>
                    <Column field="no" header="No" />
                    <Column field="tglinsiden" header="TGL Insiden" />
                    <Column field="nocm" header="No RM" />
                    <Column field="namaruangan" header="Ruangan" />
                    <Column field="namapasien" header="Nama Pasien" />
                    <Column field="insiden" header="Insiden" />
                    <Column field="keselamatan" header="Keselamatan" />
                    <Column header="Action">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle
                                outlined raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                            </VIconButton>
                            <OverlayPanel ref="op">
                                <VButton type="button" icon="fas fa-print" class="mr-2" light circle outlined
                                    color="info" raised @click="gotoPageEdit(selected,'edit')">
                                    Edit
                                </VButton>
                                <VButton type="button" icon="fas fa-undo" class="mr-2" color="warning" circle outlined
                                    raised @click="gotoPageEdit(selected,'detail')">Detail
                                </VButton>
                                <VButton type="button" icon="fas fa-trash" class="mr-2" color="danger" circle outlined
                                    @click="dialogConfirm(selected)" raised>Hapus
                                </VButton>
                                <VButton type="button" icon="fas fa-hand-point-right" class="mr-2" color="black" circle
                                    outlined @click="gotoPageTidakan(selected)" raised>Tindak Lanjut
                                </VButton>
                                <VButton type="button" icon="fas fa-print" class="mr-2" color="purple" circle outlined
                                    @click="cetak(selected)" raised>Cetak
                                </VButton>
                            </OverlayPanel>
                        </template>
                    </Column>
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
import { useRouter, useRoute, RouterLink } from 'vue-router';
import OverlayPanel from 'primevue/overlaypanel';
import DataTable from 'primevue/datatable'
import AutoComplete from 'primevue/autocomplete';
import ColumnGroup from 'primevue/columngroup';
import ConfirmDialog from 'primevue/confirmdialog'
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Daftar Insiden Internal - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    tglPelayanan: new Date(),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const router = useRouter()
const confirm = useConfirm()
const dataSource: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
const op = ref();
const selected: any = ref({})
let d_Ruangan: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let ruangan = item.value.ruanganfk ? `&ruanganfk=${item.value.ruanganfk.value}` : ''

    item.value.Ttotal = 0
    loadSearch.value = true
    await useApi().get(`pmkp/get-daftar-laporan-insiden-internal?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${ruangan}`).then((response) => {
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
        ['Daftar Insiden Internal'],
        [],
        ['NO', 'TANGGAL INSIDEN', 'NO CM', 'NAMA PASIEN', 'NAMA RUANGAN', 'insiden','KESELAMATAN'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.tglinsiden,
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

    const columnWidths = [5, 13, 10, 20, 18, 18];

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

const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
}

const toggle = (event: any, e: any) => {
    op.value.toggle(event);
    selected.value = e
}

const gotoPageEdit = (e:any,info)=>{
     router.push({
        name: 'module-indikator-form-insiden-internal',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.noregistrasifk,
            norec_lii: e.norec,
            info: info
        },
    })
}

const gotoPageTidakan = (e:any,info)=>{
     router.push({
        name: 'module-indikator-lembar-kerja-investigasi-sederhana',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.noregistrasifk,
            norec_lii: e.norec,
            norec: e.norec_lk ? e.norec_lk : '',
        },
    })
}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda yakin menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteData(e)
        },
        reject: () => { },
    })
}

const deleteData = async (e: any) => {

    await useApi().post('/pmkp/hapus-sensus-mutu', { 'norec': e.norec }).then((response) => {
        fetchData()
    }).catch((err: any) => {

    })
}

const cetak = (e:any)=>{
    H.printBlade(`pmkp/cetak-insiden-internal?pdf=true&norec=${e.norec}&nocmfk=${e.nocmfk}`);
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
