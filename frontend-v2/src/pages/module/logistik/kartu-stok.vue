<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Kartu Stok {{ item.namaProdukHeader }}</h3>
    </div>
    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
      <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
        to
        Excel </VButton>
    </div>

    <div class="columns">
      <div class="column is-9">
        <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
          :loading="isLoadingBtn"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
          <Column field="no" header="No"></Column>
          <Column field="tglkejadian" header="Tgl Transaksi"></Column>
          <Column field="namaruangan" header="Ruangan"></Column>
          <Column field="keterangan" header="Keterangan"></Column>
          <Column field="saldoawal" header="Saldo Awal"></Column>
          <Column field="saldomasuk" header="Saldo Masuk"></Column>
          <Column field="saldokeluar" header="Saldo Keluar"></Column>
          <Column field="saldoakhir" header="Saldo Akhir"></Column>
          <!-- <Column field="transaksi" header="Transaksi"></Column> -->
        </DataTable>
      </div>
      <div class="column is-3">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1">Filters</h3>
          </div>
          <div class="column is-6">
            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              Clear All
            </a>
          </div>
          <div class="column is-12">
            <VField label="Periode">
              <!-- <VLabel></VLabel> -->
              <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField addons>
                    <VControl icon="feather:calendar">
                      <VInput :value="inputValue.start" v-on="inputEvents.start" />
                    </VControl>
                    <VControl>
                      <VButton static icon="feather:arrow-right" />
                    </VControl>
                    <VControl subcontrol icon="feather:calendar">
                      <VInput :value="inputValue.end" v-on="inputEvents.end" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>

          <div class="column is-12">
            <VField label="Produk" class="is-autocomplete-select">
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <AutoComplete v-model="item.produk" :suggestions="d_Produk" @complete="fetchProduk($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Produk" showClear />
              </VControl>
            </VField>
          </div>

          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Ruangan</VLabel>
              <VControl icon="feather:search" :loading="isLoading">
                <Multiselect mode="single" v-model="item.ruangan" :options="listRuanganStok" placeholder="Pilih ruangan"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="filter()" :loading="isLoadingBtn" type="button" icon="feather:search"
              class="is-fullwidth mr-3" color="info" raised>
              Apply Filters
            </VButton>
          </div>
        </div>
      </div>
    </div>
  </VCard>
</template>

<script  setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete'
import { useCurrencyInput } from 'vue-currency-input'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as XLSX from "xlsx";
import moment from 'moment'
useHead({title: 'Transmedic - Kartu Stok'})

useViewWrapper().setFullWidth(true)

const remakeData: any = ref([])
let d_Produk: any = ref([])
let dataSource: any = ref([])
let isLoading: any = ref(false)
let isLoadingBtn: any = ref(false)
let item: any = ref({
  ruangan: null,
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
let listRuanganStok: any = ref([])
let listNamaProduk: any = ref([])
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const route = useRoute()

const exportExcel = () => {
    remakeData.value = dataSource.value.map((e: any) => {
        return {
            'No': e.no,
            'Tgl Transaksi': e.tglkejadian,
            'Keterangan': e.keterangan,
            'Saldo Awal': parseFloat(e.saldoawal) || 0,
            'Saldo Masuk': parseFloat(e.saldomasuk) || 0,
            'Saldo Keluar': parseFloat(e.saldokeluar) || 0,
            'Saldo Akhir': parseFloat(e.saldoakhir) || 0,
        }
    })
    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
    const columnWidths = [
        { wch: 5 }, // Kolom A
        { wch: 10 }, // Kolom A
        { wch: 19 }, // Kolom B
        { wch: 10 }, // Kolom C
        { wch: 10 }, // Kolom D
        { wch: 10 }, // Kolom E
        { wch: 10 }, // Kolom F
    ];
    worksheet['!cols'] = columnWidths;

    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}
const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
        type: EXCEL_TYPE
    });
    // const _url = window.URL.createObjectURL(data)
    // window.open(_url, EXCEL_EXTENSION).focus();
    const desiredFileName = 'laporan_kartu_stok' + EXCEL_EXTENSION;
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(data);
    link.download = desiredFileName;
    link.click();
    window.URL.revokeObjectURL(link.href);
    // window.open(_url,EXCEL_EXTENSION).focus()
    // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

async function fetchData() {
  isLoadingBtn.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let tglAwal = '?tglawal=' + moment(item.value.periode.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglakhir=' + moment(item.value.periode.end).format('YYYY-MM-DD')
  let produkfk = item.value.produk ? `&idproduk=${item.value.produk.value}` : ''
  let ruangan = item.value.ruangan ? `&idruangan=${item.value.ruangan}` : ''

  await useApi().get('/logistik/kartu-stok-grid' + tglAwal + tglAkhir + '&offset=' + offset + '&limit=' + limit + '&rows=' + rows + produkfk + ruangan).then((response) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response.data
  })
  isLoadingBtn.value = false
}
async function listDropdown() {
  isLoading.value = true
  const response = await useApi().get(`/logistik/kartu-stok-cbo`)
  listRuanganStok.value = response.ruangan.map((e: any): any => {
    return { label: e.namaruangan, value: e.id }
  })
  listNamaProduk.value = response.produk.map((e: any): any => {
    return { label: e.namaproduk, value: e }
  })
  isLoading.value = false
}

const fetchProduk = async (filter:any) =>{

  await useApi().get(`logistik/get-produk?namaproduk=${filter.query}`).then((response)=>{
    d_Produk.value = response.map((e:any)=>{
      return { label: `[${e.kdproduk}] - ${e.namaproduk}`, value : e.id}
    })
  })

}

function clearFilter() {
  delete item.value.nmProduk
  delete item.value.ruangan
  fetchData()
}
function filter() {
  if (!item.value.produk || !item.value.ruangan) {
    H.alert('error','Ruangan dan Produk wajib diisi');
    return;
  }
  fetchData()
}

listDropdown()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
// @import '/@src/scss/module/sysadmin/master-data.scss';
</style>
