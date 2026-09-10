<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0" style="color: var(--dark-text) !important;">
                        <label class="title-page">Laporan Stok Opname</label>
                        <label>DAFTAR STOK OPNAME</label>
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
                                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel" class="hard-button">
                                    Export To Excel
                                </VButton>
                            </div>
                            <div class="column is-10">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-3">
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
                                    <div class="column is-3">
                                        <VField label="Ruangan">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField label="Produk">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.produkfk" :suggestions="d_Produk" @complete="fetchProduk($event)"
                                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ..." />
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
                    <Column field="tglclosing" header="Tanggal Closing">
                      <template #body="slotProps">
                        {{ slotProps.data.tglclosing ? H.formatDate(slotProps.data.tglclosing, 'DD-MM-YYYY') : ''}}
                      </template>
                    </Column>    
                    <Column field="namaproduk" header="Nama Barang" />
                    <Column field="satuanstandar" header="Satuan" />
                    <Column field="qtyprodukreal" header="Jumlah"/>
                    <Column field="harga" header="Harga Satuan" style="text-align:right">
                      <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.harga, 'Rp. ') }}
                      </template>
                    </Column>
                    <Column field="nobatch" header="No Batch" />
                    <Column field="tglkadaluarsa" header="Tgl Kadaluarsa">
                      <template #body="slotProps">
                        {{ slotProps.data.tglkadaluarsa ? H.formatDate(slotProps.data.tglkadaluarsa, 'DD-MM-YYYY') : ''}}
                      </template>
                    </Column>    
                    <Column field="total" header="Total"  style="text-align:right">
                      <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.total, 'Rp. ') }}
                      </template>
                    </Column>    
                    <Column field="namaruangan" header="Ruangan" />
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
    title: 'Laporan Stok Opname - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
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
let d_Ruangan: any = ref([])
let d_Produk: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' +  H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let ruanganfk =  item.value.ruanganfk ? `&ruanganfk=${item.value.ruanganfk.value}` : ''
    let produkfk =  item.value.produkfk ? `&produkfk=${item.value.produkfk.value}` : ''

    item.value.Ttotal = 0
    loadSearch.value = true
   await useApi().get(`/logistik/daftar-stok-opname?${tglAwal}${tglAkhir}${ruanganfk}${produkfk}`).then((response) => {
        dataSource.value = response.data
    })
    loadData.value = false
    loadSearch.value = false
}


const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Laporan Stok Opname'],
        [],
        ['TANGGAL CLOSING', 'NAMA BARANG', 'SATUAN', 'JUMLAH', 'HARGA SATUAN', 'NO BATCH','TANGGAL KADALUARSA','TOTAL','NAMA RUANGAN'],
        ...dataSource.value.map((e: any) => [
            H.formatDate(e.tglclosing, 'DD-MM-YYYY'),
            e.namaproduk,
            e.satuanstandar,
            e.qtyprodukreal,
            e.harga,
            e.nobatch,
            H.formatDate(e.tglkadaluarsa, 'DD-MM-YYYY'),
            e.total,
            e.namaruangan,
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

    const columnWidths = [13, 30, 10, 8, 18, 13, 13, 18, 30 ];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 8 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Stok Opname', true);
    XLSXStyle.writeFile(workbook, 'Laporan Stok Opname.xlsx');
}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=20`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(`/logistik/stok-opname-get-produk?namaproduk=${filter.query}&limit=20`)
  d_Produk.value = response.map((e: any) => {
    return { label: e.namaproduk, value: e.id }
  })
}

fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
// @import '/@src/scss/module/sysadmin/master-data.scss';

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}

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

.hard-button{
    padding: 8px 22px !important;
    height: 38px !important;
    line-height: 1.1 !important;
    font-size: 0.95rem !important;
    font-family: var(--font) !important;
    transition: all 0.3s !important;
}
</style>
