<template>
  <div class="column is-12">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1">Audit Cuci Tangan IPCN</h3>
              </div>
              <div class="column is-4">
                <VField label="Bulan">
                  <VControl>
                    <Calendar v-model="item.bulan" showIcon :showOnFocus="true" view="month" dateFormat="mm/yy"
                      style="width: 100%" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 mt-5">
                <VIconButton type="button" color="success" class="mt-1" raised icon="fas fa-search" @click="fetchData()"
                  :loading="isLoading">
                </VIconButton>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div v-if="dataSource.length == 0">
          <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
            <template #image>
              <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
            </template>
          </VPlaceholderSection>
        </div>
        <DataTable v-else v-model:expandedRowGroups="expandedRowGroups" :class="`p-datatable-small`" :value="dataSource"
          v-model:filters="filtersTrans" :globalFilterFields="['tahun,bulan,namaruangan,jenispegawai']"
          rowGroupMode="subheader" :rowsPerPageOptions="[5, 10, 25, 100]" :rows="5" paginator groupRowsBy="namaruangan"
          :loading="isLoading" @rowgroup-expand="onRowGroupExpand" @rowgroup-collapse="onRowGroupCollapse"
          sortMode="single" sortField="namaruangan" :sortOrder="5" showGridlines>
          <template #header>
            <div class="columns is-multiline">
              <div class="column is-3">
                <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="info" outlined circle raised
                  v-tooltip-prime="'Export'" @click="exportExcel()">
                  Export Excel
                </VButton>
              </div>
              <div class="column is-3 is-offset-6">
                <VField>
                  <VControl icon="feather:search">
                    <input v-model="filtersTrans['global'].value" v-on:keyup.enter="fetchData()" type="text"
                      class="input is-rounded" placeholder="Search" />
                  </VControl>
                </VField>
              </div>
            </div>
          </template>
          <ColumnGroup type="header">
            <Row>
              <Column header="Tahun" style="min-width: 100px" />
              <Column header="Bulan" style="min-width: 100px" />
              <Column header="Profesi" style="min-width: 100px" />
              <Column header="Patuh" style="min-width: 100px" />
              <Column header="Tidak Patuh" style="min-width: 100px" />
            </Row>
          </ColumnGroup>
          <template #groupheader="slotProps">
            <span class="vertical-align-middle ml-2 font-bold line-height-3">{{ slotProps.data.namaruangan
            }}</span>
          </template>
          <Column field="tahun" />
          <Column field="bulan" />
          <Column field="jenispegawai" />
          <Column field="patuh" />
          <Column field="tidakpatuh" />
        </DataTable>
      </div>
    </VCard>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useHead } from '@vueuse/head';
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import DataTable from 'primevue/datatable';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import Column from 'primevue/column';
import { FilterMatchMode } from 'primevue/api';
import { useApi } from '/@src/composable/useApi';
import * as H from '/@src/utils/appHelper';
import * as XLSX from "xlsx"
import * as XLSXStyle from 'xlsx-js-style'

useHead({
  title: 'Audit Cuci Tangan IPCN - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const expandedRowGroups: any = ref()
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, })
const isLoading: any = ref(true)
const dataSource: any = ref([])
const item: any = reactive({
  bulan: new Date()
});


const onRowGroupExpand = (event: any) => {

};
const onRowGroupCollapse = (event: any) => {

};

const exportExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Data IPCLN'],
    [],
    ['NO', 'Bulan / Tahun', 'Nama Ruangan', 'Profesi', 'Patuh', 'Tidak Patuh'],
    ...dataSource.value.map((e: any, index: number) => [
      index + 1,
      e.bulan + '' + e.tahub,
      e.namaruangan,
      e.jenispegawai,
      e.patuh,
      e.tidakpatuh
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
  const headerRange = XLSX.utils.decode_range(worksheet['!ref'] ?? 'A1:A1');
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
    worksheet[headerCell].s = headerStyle;
  }

  // Setting column widths
  const columnWidths = [5, 15, 15, 15, 15, 15];

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

  XLSX.utils.book_append_sheet(workbook, worksheet, 'IPCN', true);
  const bulan = H.formatDate(item.bulan, "YYYY-MM")
  XLSXStyle.writeFile(workbook, `${bulan}-IPCN.xlsx`);
}
const fetchData = async () => {
  isLoading.value = true
  const bulan = H.formatDate(item.bulan, "YYYY-MM")
  useApi().get(
    `/ppi/get-data-kepatuhan-handhygiene-ipcn?bln=${bulan}`).then((response: any) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      });
      dataSource.value = response.data
      isLoading.value = false
    }).catch((e: any) => {
      isLoading.value = false
    })
}
fetchData();
</script>
<style lang="scss">
.search-widget {
  flex: 1;
  display: inline-block;
  width: 100%;
  padding: 12px;
  background-color: var(--white);
  border-radius: 16px;
  border: 1px solid var(--fade-grey-dark-3);
  transition: all 0.3s;
}
</style>
