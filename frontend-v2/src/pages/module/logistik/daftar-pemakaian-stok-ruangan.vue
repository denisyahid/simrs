<template>
    <ConfirmDialog />
    <section>

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Daftar Pemakaian Stok Ruangan</label>
                    </div>
                </div>

                <div class="column is-12">
                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :value="dataSource" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="loadSearch"
                    class="p-datatable-sm mt-4" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                    sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #empty> 
                        <div class="column is-12" style="text-align:center !important">
                            {{ H.assets().notFound }}
                        </div>
                    </template>
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-3">
                            <VField label="Periode"/>
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
                            <div class="column is-9">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-3">
                                       <VField class="is-rounded-select is-autocomplete-select" label="Ruangan">
                                            <VControl icon="feather:search" class="prime-auto-select">
                                                <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan"
                                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    placeholder="Pilih Ruangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-1 mt-5 pt-4" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData()"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <Column expander style="width: 5rem" />
                    <Column field="no" header="No"></Column>
                    <Column field="tglstruk" header="Tanggal"></Column>
                    <Column field="nostruk" header="No Pemakaian"></Column>
                    <Column field="keterangan" header="keterangan"></Column>
                    <Column field="namaruangan" header="Ruangan"></Column>
                    <Column field="namapegawai" header="Pegawai"></Column>
                    <Column field="total" header="Total"></Column>
                    <Column :exportable="false" header="##" style="text-align: center;">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle
                                outlined raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                            </VIconButton>
                            <OverlayPanel ref="op">
                                <VButtons>
                                    <VButton color="info" outlined @click="gotoPageEdit(selected)">
                                        <i class="fas fa-pen-square mr-2" aria-hidden="true"></i> Edit
                                    </VButton>
                                    <VButton color="danger" raised @click="dialogConfirm(selected)"
                                        :loading="dataSource.loadDelete">
                                        <i class="fas fa-times-circle mr-2" aria-hidden="true"></i> Hapus
                                    </VButton>
                                </VButtons>
                            </OverlayPanel>
                        </template>
                    </Column>
                    <template #expansion="slotProps">
                        <div class="p-3">
                            <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple">
                                <Column field="no" header="No" />
                                <Column field="namaproduk" header="Deskripsi" style="min-width:40px" />
                                <Column field="satuanstandar" header="Satuan" style="min-width:150px" />
                                <Column field="qtyproduk" header="Qty" style="min-width:200px" />
                                <Column field="hargasatuan" header="Harga Satuan" style="min-width:80px" />
                                <Column field="qtyproduk" style="text-align: center;" header="Qty" />
                                <Column field="hargasatuan" style="text-align: right;" header="Harga Satuan" />
                                <Column field="total" style="text-align: right;min-width:80px" header="Total" />
                            </DataTable>
                        </div>
                    </template>
                    <template #footer>
                        <div class="column is-12" style="text-align:right">
                            <VButton color="primary" elevated style="padding-right: 25px;" RouterLink
                                :to="{ name: 'module-logistik-form-pemakaian-stok-ruangan' }">
                                <i class="fas fa-plus-circle" aria-hidden="true" style="margin-right: 14px;"></i>
                                Tambah
                            </VButton>
                        </div>
                    </template>
                </DataTable>
                </div>

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
import Dialog from 'primevue/dialog'
import OverlayPanel from 'primevue/overlaypanel';
import DataTable from 'primevue/datatable'
import AutoComplete from 'primevue/autocomplete';
import ColumnGroup from 'primevue/columngroup';
import Dropdown from 'primevue/dropdown';
import ConfirmDialog from 'primevue/confirmdialog'
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Daftar Pemakaian Stok Ruangan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    waktuKejadian: new Date(),
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
let d_Keselamatan: any = ref([])
let d_JenisKeselamatan: any = ref([])
let d_KeselamatanReal: any = ref([])
let loadSearch: any = ref(false)
const expandedRows = ref();
let loadData: any = ref(true)
let modalInput: any = ref(false)
let isLoading: any = ref(false)
let loadBtnSave: any = ref(false)

const fetchData = async () => {
    let tglAwal = `tglAwal=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
    let tglAkhir = `&tglAkhir=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
    let idRuangan = item.value.ruanganfk ? `&ruanganid=${item.value.ruanganfk.value}` : ''

    item.value.Ttotal = 0
    loadSearch.value = true
    await useApi().get(`/logistik/daftar-pemakaian-stok-ruangan?${tglAwal}${tglAkhir}${idRuangan}`).then((response: any) => {
        response.forEach((element: any, i: any) => {
            expandedRows.value = element.details.forEach((data: any, i: any) => {
                data.no = i + 1
            })
            element.no = i + 1
        });
    dataSource.value = response
    })
    loadData.value = false
    loadSearch.value = false
    
}

const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Insiden Keselamatan Pasien'],
        [],
        ['NO', 'TANGGAL KEJADIAN', 'JENIS KESELAMATAN', 'KESELAMATAN', 'JUMLAH'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.tanggal,
            e.jeniskeselamatan,
            e.keselamatan,
            e.departemen,
            e.jumlah,
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

    const columnWidths = [5, 15, 18 , 25, 20, 10];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 4 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Insiden Keselamatab', true);
    XLSXStyle.writeFile(workbook, 'Daftar Insiden Keselamtan Pasien.xlsx');
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}


const toggle = (event: any, e: any) => {
    op.value.toggle(event);
    selected.value = e
}

const gotoPageEdit = (e: any) => {
    router.push({
        name: 'module-logistik-form-pemakaian-stok-ruangan',
        query: {
            norec: e.norec,
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
    loadSearch.value = true
    await useApi().post('/logistik/hapus-pemakaian-stok-ruangan', { 'nostruk': e.norec }).then((response) => {
        fetchData()
    }).catch((err: any) => {
    loadSearch.value = false
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
</style>
