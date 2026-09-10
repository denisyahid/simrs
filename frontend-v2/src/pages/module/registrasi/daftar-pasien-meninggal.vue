
<template>
  <div class="page-content-inner">
    <div class="is-navbar">
      <div class="form-layout">
        <div class="form-outer">
          <div class="form-body">
            <div class="form-fieldset">
              <div class="fieldset-heading mb-5">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Daftar Pasien Meninggal</h1>
              </div>
              <div class="column">
                <div>
                  <div class="columns is-multiline mt-5">
                    <div class="column is-12">
                      <DataTable :value="d_ListPasien" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                        :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
                        v-model:filters="filters" :globalFilterFields="['noregistrasi', 'nocm', 'namapasien']"
                        filterDisplay="menu">
                        <template #header>
                          <div class="columns is-multiline pb-3">
                            <div class="column is-2" style="padding-top:2rem">
                              <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                                Export To Excel
                              </VButton>
                            </div>
                            <div class="column is-10">
                              <div class="columns is-multiline" style="justify-content: flex-end;">
                                <div class="column is-5 pb-0">
                                  <VField label="Periode" style="margin-bottom: 6px;" />
                                  <VControl class="prime-auto">
                                    <VField>
                                      <Calendar :locale="'id'" :dateFormat="H.dateTimeFormat().prime.date" inputId="range" v-model="item.periode" selectionMode="range" :manualInput="false"
                                        class="w-100" :showIcon="true" :hideOnRangeSelection="true" />
                                    </VField>
                                  </VControl>
                                </div>
                                <div class="column is-3 pb-0">
                                  <VField label="Cari">
                                    <VInput v-model="filters.global.value" placeholder="Keyword Search"
                                      style="width:300px" />
                                  </VField>
                                </div>
                                <div class="column is-1 mt-5">
                                  <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData"
                                    :loading="loadSearch" />
                                </div>
                              </div>
                            </div>
                          </div>
                        </template>
                        <template #empty> {{ H.assets().notFound }}</template>
                        <Column field="tglmeninggal" frozen :sortable="true" header="Tgl Meninggal"></Column>
                        <Column field="noregistrasi" header="No Reg"></Column>
                        <Column field="nocm" header="No Rm"></Column>
                        <Column field="namapasien" header="Nama Pasien"></Column>
                        <Column field="penyebabkematian" header="Penyebab Kematian"></Column>
                        <Column field="namaruangan" header="Ruangan Terakhir"></Column>
                      </DataTable>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import moment from 'moment'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import Calendar from 'primevue/calendar';
const input: any = ref({
  periode: [
    new Date(),
    new Date()
  ],
})
const item: any = reactive({
  periode: [
    new Date(),
    new Date()
  ]
})
let isLoading = ref(false)
const d_ListPasien: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
useHead({
  title: 'Daftar Pasien Meninggal - ' + import.meta.env.VITE_PROJECT,
})
const route = useRoute()
let caches = H.cacheInput().get(route.name);
if (caches) input.value = caches
async function fetchData() {
  let object: any = {}
  object = input.value
  isLoading.value = true;
  let startDate = '';
  let endDate = '';
  if (item.periode) {
    if (item.periode[0]) {
      startDate = H.formatDate(item.periode[0], 'YYYY-MM-DD 00:00')
    }
    if (item.periode[1]) {
      endDate = H.formatDate(item.periode[1], 'YYYY-MM-DD 23:59')
    } else {
      endDate = H.formatDate(item.periode[0], 'YYYY-MM-DD 23:59')
    }
  }
  const noReg = object.noReg ? object.noReg : "";
  const noCm = object.noCm ? object.noCm : "";
  const namaPasien = object.namaPasien ? object.namaPasien : "";
  H.cacheInput().set(route.name ,input.value);

  useApi().get(
    `/resgistrasi/get-daftar-pasien-meninggal?tglAwal=${startDate}&tglAkhir=${endDate}&noReg=${noReg}&noCm=${noCm}&namaPasien=${namaPasien}`).then((response: any) => {
      d_ListPasien.value = response.data
      isLoading.value = false
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const setAutoFill = () => {
  input.value.tglAwal = new Date()
  input.value.tglAkhir = new Date()
}

const exportExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Daftar Pasien Meninggal'],
    [],
    ['TANGGAL MENINGGAL', 'NO REGISTRASI', 'NOCM', 'NAMA PASIEN', 'PENYEBAB KEMATIAN', 'NAMA RUANGAN'],
    ...d_ListPasien.value.map((e: any) => [
      e.tglmeninggal,
      e.noregistrasi,
      e.nocm,
      e.namapasien,
      e.penyebabkematian,
      e.namaruangan,
    ]),
  ]);

  // Defining style for the header (centered)
  const headerStyle = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      color: { rgb: 'FFFFFF' },
      bold: true,
    },
    fill: { fgColor: { rgb: '807C7C' } },
  };

  // Applying header style
  const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
    worksheet[headerCell].s = headerStyle;
  }

  // Setting column widths
  const columnWidths = [20, 12, 12, 25, 30, 30];

  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    worksheet['!cols'] = worksheet['!cols'] || [];
    worksheet['!cols'][col] = { wch: columnWidths[col] };
  }

  // Centering the text in cell A1
  const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
  worksheet[titleCell].s = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      bold: true,
      sz: 18,
    },
  };

  // Merging the first two rows
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 5 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Daftar Pasien Meninggal', true);

  XLSXStyle.writeFile(workbook, 'Daftar Pasien Meninggal.xlsx');
};
setAutoFill();
fetchData();
setAutoFill();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
  max-width: 1300px;
  margin: 0 auto;
}

.form-fieldset {
  padding: 10px 0;
  max-width: 100%;
  margin: 0 auto;
}

.table-pi {
  width: 1400px;
  border: 1px solid #929090;
}

.table-scroll {
  overflow-x: scroll;
}

.date {
  background-color: #9b9b9b;
  color: #fff;
}
</style>
