
<template>
  <div class="page-content-inner">
    <div class="is-navbar">
      <div class="form-layout">
        <div class="form-outer">
          <div class="form-body">
            <div class="form-fieldset">
              <div class="fieldset-heading mb-5">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Daftar Pembatalan Pasien</h1>
              </div>
              <div class="columns is-multiline mt-5">
                <div class="column is-12">
                  <DataTable :value="d_Pembatalan" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                    :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
                    v-model:filters="filters" :globalFilterFields="['noregistrasi', 'nocm', 'namapasien', 'namaruangan']"
                    filterDisplay="menu">
                    <template #empty> {{ H.assets().notFound }}</template>
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
                                  <Calendar :locale="'id'" :dateFormat="H.dateTimeFormat().prime.date" inputId="range" v-model="input.range" selectionMode="range" :manualInput="false"
                                  class="w-100" :showIcon="true" :hideOnRangeSelection="true" />
                                </VField>
                              </VControl>
                            </div>
                            <div class="column is-3 pb-0">
                              <VField label="Cari">
                                <VInput v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
                              </VField>
                            </div>
                            <div class="column is-1 mt-5">
                              <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData"
                                :loading="isLoading" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </template>
                    <Column header="Tanggal">
                      <template #body="slotProps">
                        <span>{{ H.formatDateIndoSimple(slotProps.data.tanggalpembatalan) }}</span>
                      </template>
                    </Column>
                    <Column field="noregistrasi" header="No Reg"></Column>
                    <Column field="nocm" header="No Rm"></Column>
                    <Column field="namapasien" header="Nama Pasien"></Column>
                    <Column field="namaruangan" header="Ruangan"></Column>
                    <Column field="namalengkap" header="Pegawai Pembatal"></Column>
                    <Column field="alasanpembatalan" header="Alasan Pembatalan"></Column>
                  </DataTable>
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
import Column from 'primevue/column';
import moment from 'moment'
import { FilterMatchMode } from 'primevue/api'
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import Calendar from 'primevue/calendar';

const input: any = ref({
  tglAwal: new Date(),
  tglAkhir: new Date(),
  range:[new Date(),new Date()]
})

const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
let isLoading = ref(false)
const d_Pembatalan: any = ref([])
const dataSourceICD9 = ref([])
const date = ref(new Date())


useHead({
  title: 'Daftar Pembatalan Pasien - ' + import.meta.env.VITE_PROJECT,
})
const route = useRoute();
let caches = H.cacheInput().get(route.name);
// if (caches) input.value = caches
async function fetchData() {
  let object: any = {};
  object = input.value;
  let startDate = ''
  let endDate = ''
  if (input.value.range) {
    if (input.value.range[0]) {
      startDate = H.formatDate(input.value.range[0], 'YYYY-MM-DD 00:00')
    }
    if (input.value.range[1]) {
      endDate = H.formatDate(input.value.range[1], 'YYYY-MM-DD 23:59')
    } else {
      endDate = H.formatDate(input.value.range[0], 'YYYY-MM-DD 23:59')
    }
  }
  isLoading.value = true;
  H.cacheInput().set(route.name ,input.value);
  useApi().get(
    `/resgistrasi/get-daftar-pasienbatal?tglAwal=${startDate}&tglAkhir=${endDate}&noReg=${object.noReq}&noCm=${object.noCm}&namaPasien=${object.namaPasien}`).then((response: any) => {
      d_Pembatalan.value = response.data
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
    ['Daftar Pembatalan Pasien'],
    [],
    ['NO', 'NO REGISTRASI', 'NOCM', 'NAMA PASIEN', 'NAMA RUANGAN', 'PEGAWAI PEMBATAL', 'ALASAN PEMBATALAN'],
    ...d_Pembatalan.value.map((e: any, index: number) => [
      index + 1,
      e.noregistrasi,
      e.nocm,
      e.namapasien,
      e.namaruangan,
      e.namalengkap,
      e.alasanpembatalan,
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
  const columnWidths = [5, 20, 12, 12, 25, 30, 30];

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
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 6 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Daftar Pembatalan Pasien', true);

  XLSXStyle.writeFile(workbook, 'Daftar Pembatalan Pasien.xlsx');
};
setAutoFill();
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
  max-width: 1200px;
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
