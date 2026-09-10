<template>
  <VCard>
    <div>
      <h3 class="title is-5 mb-2 mr-1">Formulir RL 4A</h3>
      <span>DATA KEADAAN MORBIDITAS PASIEN RAWAT INAP RUMAH SAKIT</span>
    </div>
    <div class="columns">
      <div class="column is-9">
        <div class="column" v-if="isLoading">
          <VPlaceloadWrap v-for="data in 10">
            <VPlaceload class="mx-2 mb-3" />
          </VPlaceloadWrap>
        </div>
        <div class="column" v-else-if="dataSource.length == 0">
          <VPlaceholderPage title="Data Tidak di Temukan." subtitle="Silakan gunakan filter lain" larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>
        </div>
        <div class="column" v-else>
          <DataTable v-model:expandedRowGroups="expandedRowGroups" :class="`p-datatable-small`" :value="dataSource"
            v-model:filters="filtersTrans" :globalFilterFields="['kddiagnosa']" :rowsPerPageOptions="[5, 10, 25, 100]"
            :rows="5" paginator :loading="isLoading" sortMode="single" :sortOrder="5" showGridlines>

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
                <Column header="No Urut" :rowspan="3" />
                <Column header="No DTD" :rowspan="3" />
                <Column header="No. Daftar Terperinci" :rowspan="3" />
                <Column header="Golongan Sebab Penyakit" :rowspan="3" />
                <Column header="Jumlah Pasien Hidup dan Mati menurut Golongan Umur & Jenis Kelamin" :colspan="18" />
                <Column header="Pasien Keluar (Hidup & Mati) Menurut Jenis Kelamin" :colspan="2" />
              </Row>
              <Row>
                <Column header="0-6hr" :colspan="2" />
                <Column header="7-28hr" :colspan="2" />
                <Column header="28hr-<1th" :colspan="2" />
                <Column header="1-4th" :colspan="2" />
                <Column header="5-14th" :colspan="2" />
                <Column header="15-24th" :colspan="2" />
                <Column header="55-44th" :colspan="2" />
                <Column header="45-64th" :colspan="2" />
                <Column header=">65" :colspan="2" />
                <Column header="LK" :rowspan="2" />
                <Column header="PR" :rowspan="2" />
              </Row>
              <Row>
                <Column header="L" />
                <Column header="P" />
                <Column header="L" />
                <Column header="P" />
                <Column header="L" />
                <Column header="P" />
                <Column header="L" />
                <Column header="P" />
                <Column header="L" />
                <Column header="P" />
                <Column header="L" />
                <Column header="P" />
                <Column header="L" />
                <Column header="P" />
                <Column header="L" />
                <Column header="P" />
                <Column header="L" />
                <Column header="P" />
              </Row>
            </ColumnGroup>
            <Column field="no" style="min-width: 70px;" />
            <Column field="nodtd" style="min-width: 70px;" />
            <Column field="kddiagnosa" style="min-width: 100px;" />
            <Column field="golongansebabpenyakit" style="min-width: 120px;" />
            <Column field="jml6HariL" style="min-width: 40px;" />
            <Column field="jml6HariP" style="min-width: 40px;" />
            <Column field="jml28HariL" style="min-width: 40px;" />
            <Column field="jml28HariP" style="min-width: 40px;" />
            <Column field="jml1ThnL" style="min-width: 40px;" />
            <Column field="jml1ThnP" style="min-width: 40px;" />
            <Column field="jml4ThnL" style="min-width: 40px;" />
            <Column field="jml4ThnP" style="min-width: 40px;" />
            <Column field="jml14ThnL" style="min-width: 40px;" />
            <Column field="jml14ThnP" style="min-width: 40px;" />
            <Column field="jml24ThnL" style="min-width: 40px;" />
            <Column field="jml24ThnP" style="min-width: 40px;" />
            <Column field="jml44ThnL" style="min-width: 40px;" />
            <Column field="jml44ThnP" style="min-width: 40px;" />
            <Column field="jml64ThnL" style="min-width: 40px;" />
            <Column field="jml64ThnP" style="min-width: 40px;" />
            <Column field="jml65ThnL" style="min-width: 40px;" />
            <Column field="jml65ThnP" style="min-width: 40px;" />
            <Column field="totalMenurutL" style="min-width: 100px;" />
            <Column field="totalMenurutP" style="min-width: 100px;" />
          </DataTable>
        </div>
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
            <VButton @click="fetchData()" :loading="isLoading" type="button" icon="feather:search"
              class="is-fullwidth mr-3" color="info" raised>
              Apply Filters
            </VButton>
          </div>
        </div>
      </div>
    </div>
  </VCard>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { FilterMatchMode } from 'primevue/api'
import ColumnGroup from 'primevue/columngroup'
import Row from 'primevue/row'
import Column from 'primevue/column'
import DataTable from 'primevue/datatable'
import * as XLSX from "xlsx"
import * as XLSXStyle from 'xlsx-js-style'
useHead({
  title: 'RL4a Penyakit Rawat Inap' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = reactive({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const isLoading: any = ref(false)
const dataSource: any = ref([])
const expandedRowGroups: any = ref();
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const onRowGroupExpand = (event) => {

};
const onRowGroupCollapse = (event) => {

};
const clearFilter = () => {

}
const fetchData = async () => {
  isLoading.value = true;
  let tglAwal = moment(item.periode.start).format("YYYY-MM-DD 00:00:00");
  let tglAkhir = moment(item.periode.end).format("YYYY-MM-DD 23:59:59");
  await useApi().get(`laporan/get-laporan-rl4a?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`).then((response: any) => {
    if (response.data.length > 0) {
      response.data.forEach((element: any, index: number) => {
        element.no = index + 1
      });
    }
    dataSource.value = response.data
    isLoading.value = false;
  })
}
const exportExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Daftar RL4A RAWAT INAP'],
    [],
    ['NO', 'NO DTD', 'NO DAFTAR TERPERINCI', 'GOLONGAN SEBAB PENYAKIT', 'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & JSEX_0-<=6hr_L',
      'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & JSEX_0-<=6hr_P', 'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>6-<=28hr_L', 'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>6-<=28hr_P',
      'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>6-<=28hr_P', 'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>28hr-<=1th_P',
      'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>1-<=4th_L', 'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>1-<=4th_P',
      'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>4-<=14th_L', 'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>4-<=14th_P',
      'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>14-<=24th_L', 'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>14-<=24th_P',
      'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>24-<=44th_L', 'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>24-<=44th_P',
      'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>44-<=64th_L', 'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>44-<=64th_P',
      'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>64th_L', 'JMLH PAS. YG HIDUP & MATI MENURUT GOL UMUR & SEX_>64th_P',
      'PASIEN KELUAR (HIDUP & MATI) MENURUT JENIS KELAMIN_LK', 'PASIEN KELUAR (HIDUP & MATI) MENURUT JENIS KELAMIN_PR', 'JUMLAH PASIEN KELUAR HIDUP & MATI (23 + 24)',
      'JUMLAH PASIEN KELUAR MATI'],
    ...dataSource.value.map((e: any, index: number) => [
      index + 1,
      e.nodtd,
      e.kddiagnosa,
      e.golongansebabpenyakit,
      e.jml6HariL,
      e.jml6HariP,
      e.jml28HariL,
      e.jml28HariP,
      e.jml1ThnL,
      e.jml1ThnP,
      e.jml4ThnL,
      e.jml4ThnP,
      e.jml14ThnL,
      e.jml14ThnP,
      e.jml24ThnL,
      e.jml24ThnP,
      e.jml44ThnL,
      e.jml44ThnP,
      e.jml64ThnL,
      e.jml64ThnP,
      e.jml65ThnL,
      e.jml65ThnP,
      e.totalMenurutL,
      e.totalMenurutP
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
  const columnWidths = [70, 80, 100, 150, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 60, 60];

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
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 24 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'RL4A Rawat Inap', true);

  XLSXStyle.writeFile(workbook, 'RL4ARawatinap.xlsx');
}

</script>

<style lang="scss"></style>
