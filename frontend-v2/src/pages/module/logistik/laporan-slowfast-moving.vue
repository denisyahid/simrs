<template>
  <VCard>
    <div class="tabs-wrapper" :class="['tab-naver']">
      <div class="tabs-inner">
        <div class="tabs is-boxed">
          <ul>
            <li v-for="(tab, key) in tabs" :key="key" :class="[activeValue === tab.value && 'is-active']">
              <slot name="tab-link" :active-value="activeValue" :tab="tab" :index="key" :toggle="toggle">
                <a tabindex="0" @keydown.space.prevent="toggle(tab.value)" @click="toggle(tab.value)">
                  <VIcon v-if="tab.icon" :icon="tab.icon" />
                  <span>
                    <slot name="tab-link-label" :active-value="activeValue" :tab="tab" :index="key">
                      {{ tab.label }}
                    </slot>
                  </span>
                </a>
              </slot>
            </li>
            <li v-if="sliderClass" class="tab-naver"></li>
          </ul>
        </div>
      </div>

      <div class="tab-content is-active">
        <Transition :name="'fade-fast'" mode="out-in">
          <slot name="tab" :active-value="activeValue"></slot>
        </Transition>
      </div>
    </div>

  </VCard>
  <VCard v-if="activeValue == 1">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Laporan Fast Moving</h3>
    </div>
    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
      <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
        to
        Excel </VButton>
    </div>

    <div class="columns">
      <div class="column is-8">
        <div class="ruangan-row">
          <div>Ruangan : {{ namaruangan }}</div>
        </div>
        <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
          :loading="isLoadingBtn"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
          
          <Column field="no" header="No."></Column>
          <Column field="namaproduk" header="Nama Barang"></Column>
          <Column field="namaruangan" header="Ruangan"></Column>
          <Column field="total" header="Jumlah"></Column>
          
          <!-- 'sp.tglkirim',
                'pr.id as kdproduk',
                'pr.namaproduk',
                'spd.qtyprodukretur',
                'spd.objectprodukfk',
                'spd.nokirimfk',
                'ss.satuanstandar',
                DB::raw("sum(spd.qtyproduk) as qtyproduk") -->
       
        </DataTable>
       
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
  <VCard v-if="activeValue == 2">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Laporan Slow  Moving</h3>
    </div>
    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
      <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel1()"> Export
        to
        Excel </VButton>
    </div>

    <div class="columns">
      <div class="column is-8">
       
        <DataTable :value="dataSource1" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
          :loading="isLoadingBtn"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
          
          <Column field="no" header="No."></Column>
          <Column field="namaproduk" header="Nama Barang"></Column>
          <Column field="namaruangan" header="Ruangan"></Column>
          <Column field="total" header="Jumlah"></Column>
          
          <!-- 'sp.tglkirim',
                'pr.id as kdproduk',
                'pr.namaproduk',
                'spd.qtyprodukretur',
                'spd.objectprodukfk',
                'spd.nokirimfk',
                'ss.satuanstandar',
                DB::raw("sum(spd.qtyproduk) as qtyproduk") -->
       
        </DataTable>
       
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
            <VButton @click="filter1()" :loading="isLoadingBtn" type="button" icon="feather:search"
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

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
// const item: any = ref({
//   periode: reactive({
//     start: new Date(),
//     end: new Date(),
//   })
// })

const emit = defineEmits<{
  (e: 'update:selected', value: string): void
}>()

const activeValue: any = ref(1)
const remakeData: any = ref([])
const totalSaldoAwal = ref(0)
const totalMasuk = ref(0)
const totalKeluar = ref(0)
const totalSisa = ref(0)
const namaruangan = ref('');
const namaproduk = ref('');
const periode = ref('');
let dataSource: any = ref([])
let dataSource1: any = ref([])
let isLoading: any = ref(false)
let isLoadingBtn: any = ref(false)
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

const exportExcel = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['RSUD TUANKU IMAM BONJOL'],
    ['Jl. Jend. Sudirman No.33, Pauah, Kec. Lubuk Sikaping, Kabupaten Pasaman, Sumatera Barat 26566'],
    [],
    ['Laporan Fast Moving'],
    [periode.value],
    [],
    // <Column field="no" header="No."></Column>
    //       <Column field="namaproduk" header="Nama Barang"></Column>
    //       <Column field="ruangan" header="Ruangan"></Column>
    //       <Column field="total" header="Jumlah"></Column>
    ['No.', 'Nama Barang', 'Ruangan', 'Jumlah'],
    ...dataSource.value.map((e: any) => [
      e.no,
      e.namaproduk,
      e.namaruangan,
      e.total,
      
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
  const desiredFileName = 'LaporanFastMoving' + EXCEL_EXTENSION;
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
  let tglAwal = '?tglawal=' + moment(item.value.periode.start).format('YYYY-MM-DD 00:00:00');
  let tglAkhir = '&tglakhir=' + moment(item.value.periode.end).format('YYYY-MM-DD 23:59:59');
  let produkfk = item.value.nmProduk ? `&idproduk=${item.value.nmProduk.id}` : '';
  let ruanganfk = item.value.ruangan ? `&idruangan=${item.value.ruangan}` : '';

  await useApi().get('/logistik/laporan-fast-moving' + tglAwal + tglAkhir + ruanganfk ).then((response) => {
  dataSource.value = response.data;
});


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

const exportExcel1 = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['RSUD TUANKU IMAM BONJOL'],
    ['Jl. Jend. Sudirman No.33, Pauah, Kec. Lubuk Sikaping, Kabupaten Pasaman, Sumatera Barat 26566'],
    [],
    ['Laporan Slow Moving'],
    [periode.value],
    [],
    // <Column field="no" header="No."></Column>
    //       <Column field="namaproduk" header="Nama Barang"></Column>
    //       <Column field="ruangan" header="Ruangan"></Column>
    //       <Column field="total" header="Jumlah"></Column>
    ['No.', 'Nama Barang', 'Ruangan', 'Jumlah'],
    ...dataSource1.value.map((e: any) => [
      e.no,
      e.namaproduk,
      e.namaruangan,
      e.total,
      
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
  saveAsExcelFile1(excelBuffer, 'products');
}

const saveAsExcelFile1 = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'LaporanSlowMoving' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

async function fetchData1() {
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
  let tglAwal = '?tglawal=' + moment(item.value.periode.start).format('YYYY-MM-DD 00:00:00');
  let tglAkhir = '&tglakhir=' + moment(item.value.periode.end).format('YYYY-MM-DD 23:59:59');
  let produkfk = item.value.nmProduk ? `&idproduk=${item.value.nmProduk.id}` : '';
  let ruanganfk = item.value.ruangan ? `&idruangan=${item.value.ruangan}` : '';

  await useApi().get('/logistik/laporan-slow-moving' + tglAwal + tglAkhir + ruanganfk ).then((response) => {
  dataSource1.value = response.data;
});


isLoadingBtn.value = false;
}

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

const tabs: any = ref([
  { label: 'Fast Moving', value: 1, icon: 'fas fa-list' },
  { label: 'Slow Moving', value: 2, icon: 'fas fa-list' },
])
const selectedTabs: any = ref()
const props: any = defineProps({
  registrasi: {
    type: Object as PropType<any>,
  },
  pasien: {
    type: Object as PropType<any>,
  },
  selected: undefined,
  type: undefined,
  align: undefined,
  hilangkanStuck: false
})
const sliderClass = computed(() => {
  if (!props.slider) {
    return ''
  }

  if (props.type === 'rounded') {
    if (props.tabs.length === 2) {
      return 'is-triple-slider'
    }

    return ''
  }

  if (!props.type) {
    if (props.tabs.length === 2) {
      return 'is-squared is-triple-slider'
    }
  }

  return ''
})

function toggle(value: string) {
  activeValue.value = value
}
watch(
  () => selectedTabs,
  (value) => {
    activeValue.value = value
  }
)
watch(activeValue, (value: any) => {
  emit('update:selected', value)
})
function clearFilter() {
  delete item.value.nmProduk
  delete item.value.ruangan
  fetchData()
}
function filter() {
  fetchData()
}
function filter1() {
  fetchData1()
}
watch(
  () => activeValue.value,
  (newValue, oldValue) => {
    if (newValue) {
      if (newValue == 1) {
        fetchData()
      } else {
        fetchData1()
      }
    } else {
    }
  }
)
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
