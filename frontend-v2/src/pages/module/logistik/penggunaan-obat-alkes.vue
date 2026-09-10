<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Laporan Penggunaan Obat Alkes {{ item.namaProdukHeader }}</h3>
    </div>
    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
      <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
        to
        Excel </VButton>
    </div>

    <div class="columns">
      <div class="column is-8"><br>
        <DataTable :value="enhancedDataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
          :loading="isLoadingBtn"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
              <Column field="no" frozen :sortable="true" style="min-width: 30px">
                <template #header>
                  <div style="text-align: center;width: 100%;">
                    No
                  </div>
                </template>
              </Column>
              <Column field="tglpelayanan" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Tgl Pelayanan
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.tglpelayanan }}
                  </div>
                </template>
              </Column>
              <Column field="kdproduk" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Kode Produk
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: left;">
                    {{ data.kdproduk }}
                  </div>
                </template>
              </Column>
              <Column field="namaproduk" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Nama Produk
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: left;">
                    {{ data.namaproduk }}
                  </div>
                </template>
              </Column>
              <Column field="jumlah" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Jumlah
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.jumlah }}
                  </div>
                </template>
              </Column>
              <Column field="hargasatuan" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Harga Satuan
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: right;">
                    {{ H.formatRupiah(data.hargasatuan,'Rp.') }}
                  </div>
                </template>
              </Column>
              <Column field="total" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Total
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: right;">
                    {{ H.formatRupiah(data.total,'Rp.') }}
                  </div>
                </template>
              </Column>              
        </DataTable>
        <b style="text-align: right; display: block;">Total Seluruh Penggunaan : {{ H.formatRupiah(totalSum, 'Rp.') }}</b>
      </div>
      <div class="column is-4">
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
            <VField class="is-autocomplete-select">
              <VLabel>Nama Produk</VLabel>
              <VControl icon="feather:search" :loading="isLoading">
                <Multiselect mode="single" v-model="item.nmProduk" :options="listNamaProduk" placeholder="Pilih produk"
                  :searchable="true" />
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
useHead({
  title: 'Transmedic - Laporan Penggunaan Obat Alkes',
})
const remakeData: any = ref([])
const totalSaldoAwal = ref(0)
const totalMasuk = ref(0)
const totalKeluar = ref(0)
const totalSisa = ref(0)
const namaruangan = ref('');
const namaproduk = ref('');
const periode = ref('');
let dataSource: any = ref([])
let isLoading: any = ref(false)
let isLoadingBtn: any = ref(false)
let totalseluruh = 0;
let item: any = ref({
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


const totalSum = computed(() => {
  return enhancedDataSource.value.reduce((sum, item) => sum + item.total, 0);
});

const formatRupiah = (value: number, prefix = 'Rp.') => {
  if (isNaN(value)) return '-';
  return (
    prefix +
    value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
  );
};

const exportExcel = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['RSUD BALI MANDARA'],
    ['Jl. Bypass Ngurah Rai No.548, Kota Garut, Bali'],
    [],
    ['LAPORAN PENGGUNAAN'],
    [periode.value],
    [],
    ['No','Tgl Pelayanan', 'Kode Produk', 'Nama Produk', 'Jumlah', 'Harga Satuan', 'Total'],
    ...dataSource.value.map((e: any) => [
      e.no,
      e.tglpelayanan,
      e.kdproduk,
      e.namaproduk,
      parseFloat(e.jumlah) || 0,
      e.hargasatuan,
      parseFloat(e.total) || 0,
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 20 },
    { wch: 25 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 8 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 8 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 8 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 8 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'rekap_penggunaan_obat_alkes' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

async function fetchData() {
  let limit = currentPage.value.limit;
  let offset = route.query.page ? route.query.page : 1;
  offset = offset * limit - limit;
  let rows = currentPage.value.rows;
  let startDate = moment(item.value.periode.start);
  let endDate = moment(item.value.periode.end);
  if (endDate.diff(startDate, 'months') != 0) {
    H.alert('warning' ,'Maksimal Range 1 Bulan !');
    return;
  }
  isLoadingBtn.value = true;
  let tglAwal = '?tglawal=' + moment(item.value.periode.start).format('YYYY-MM-DD');
  let tglAkhir = '&tglakhir=' + moment(item.value.periode.end).format('YYYY-MM-DD');
  let produkfk = item.value.nmProduk ? `&idproduk=${item.value.nmProduk.id}` : '';
  let ruanganfk = item.value.ruangan ? `&idruangan=${item.value.ruangan}` : '';

  const periodeText = `Periode : ${moment(item.value.periode.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.periode.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  let saldoAwalPertama = null;
  await useApi().get('/logistik/penggunaan-obat-alkes' + tglAwal + tglAkhir + '&offset=' + offset + '&limit=' + limit + '&rows=' + rows + produkfk + ruanganfk).then((response) => {
        response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      });
      dataSource.value = response.data
    })
    isLoadingBtn.value = false;

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

const enhancedDataSource = computed(() => {
  return dataSource.value.map(item => {
    const hargasatuan = Math.ceil(item.hargasatuan); 
    const total = Math.ceil(item.total);
    totalseluruh += total;
    console.log('sk',totalseluruh)
    return {
      ...item,
      hargasatuan: hargasatuan,
      total: total
    };
  });
});


async function fetchProduk(filter: any) {
  let query = ''
  if (filter) {
    query = filter.toLowerCase()
  }
  const response = await useApi().get(
    `/logistik/kartu-stok/list-produk?name=${query}&limit=20`)

  return response.produk.map((item: any) => {
    return { value: item.id, label: item.namaproduk }
  })
}
;
function clearFilter() {
  delete item.value.nmProduk
  delete item.value.ruangan
  fetchData()
}
function filter() {
  if (!item.value.ruangan) {
        useToaster().error('Ruangan harus di isi')
        return
  }
  fetchData()
}

listDropdown()
</script>
<style lang="scss">
.additional-row {
  margin-top: 10px;
  padding: 10px;
  border-top: 1px solid #ccc;
  display: flex;
  justify-content: space-between;
}
.ruangan-row{
  margin-top: 10px;
  padding: 10px;
  display: flex;
  justify-content: space-between;
}

@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
// @import '/@src/scss/module/sysadmin/master-data.scss';
</style>
