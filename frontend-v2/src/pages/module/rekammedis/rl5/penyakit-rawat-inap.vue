<template>
  <VCard>
    <div>
      <h3 class="title is-5 mb-2 mr-1">Formulir RL 5.3</h3>
      <span>10 BESAR PENYAKIT RAWAT INAP</span>
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
            v-model:filters="filtersTrans" :globalFilterFields="['kddiagnosa', 'namadiagnosa']"
            :rowsPerPageOptions="[5, 10, 25, 100]" :rows="5" paginator :loading="isLoading" sortMode="single"
            :sortOrder="5" showGridlines>

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
                <Column header="Kode ICD 10" :rowspan="2" />
                <Column header="Deskripsi" :rowspan="2" />
                <Column header="Pasien Keluar Hidup Menurut Jenis Kelamin" :colspan="2" />
                <Column header="Pasien Keluar Meninggal Menurut Jenis Kelamin	" :colspan="2" />
                <Column header="Total (Hidup & Mati)" :rowspan="2" />
              </Row>
              <Row>
                <Column header="Laki-laki" />
                <Column header="Perempuan" />
                <Column header="Laki-laki" />
                <Column header="Perempuan" />
              </Row>
            </ColumnGroup>
            <Column field="kddiagnosa"></Column>
            <Column field="namadiagnosa" style="min-width: 150px;"></Column>
            <Column field="jumlahLKH"></Column>
            <Column field="jumlahPRH"></Column>
            <Column field="jumlahLKM"></Column>
            <Column field="jumlahPRM"></Column>
            <Column field="total"></Column>
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
            <span>Ruangan</span>
            <VField class="is-rounded-select is-autocomplete-select">
              <VControl icon="feather:search" class="prime-auto-select">
                <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'namaruangan'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'namaruangan'" placeholder=" Ruangan Asal" />
              </VControl>
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
import * as XLSX from "xlsx"
import * as XLSXStyle from 'xlsx-js-style'
import AutoComplete from 'primevue/autocomplete'
import ColumnGroup from 'primevue/columngroup'
import Row from 'primevue/row'
import Column from 'primevue/column'
import DataTable from 'primevue/datatable'
import { FilterMatchMode } from 'primevue/api'
useHead({
  title: 'RL5.3 10 Besar Penyakit Rawat Inap' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const item: any = reactive({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const d_Ruangan: any = ref([])
const dataSource: any = ref([])
const isLoading: any = ref(false)

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const clearFilter = () => {

}
const fetchData = async () => {
  isLoading.value = true;
  let ruangan = item.ruanganfk ? item.ruanganfk.value : ''
  let tglAwal = moment(item.periode.start).format("YYYY-MM-DD 00:00:00");
  let tglAkhir = moment(item.periode.end).format("YYYY-MM-DD 23:59:59");
  const response = await useApi().get(`laporan/get-laporan-rl5c?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}&ruanganfk=${ruangan}`)
  response.data.map((element: any, index: number) => {
    element.total = parseFloat(element.jumlahLKH) + parseFloat(element.jumlahPRH) + parseFloat(element.jumlahLKM) + parseFloat(element.jumlahPRM)
  })
  dataSource.value = response.data
  isLoading.value = false;
}
const exportExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Laporan RL 5.3 - Penyakit Rawat Inap'],
    [],
    ['Kode ICD 10', 'Deskripsi', 'Pasien Keluar Hidup Menurut Jenis Kelamin', '', 'Pasien Keluar Meninggal Menurut Jenis Kelamin', '', 'Total (Hidup & Mati)'],
    ['', '', 'Laki Laki', 'Perempuan', 'Laki Laki', 'Perempuan', ''],
    ...dataSource.value.map((e, index) => [
      e.kddiagnosa,
      e.namadiagnosa,
      e.jumlahLKH,
      e.jumlahPRH,
      e.jumlahLKM,
      e.jumlahPRM,
      e.total
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
    border: {
      top: { style: 'thin', color: { rgb: 'FFFFFF' } },
      bottom: { style: 'thin', color: { rgb: 'FFFFFF' } },
      left: { style: 'thin', color: { rgb: 'FFFFFF' } },
      right: { style: 'thin', color: { rgb: 'FFFFFF' } },
    },
  };

  // Applying header style
  const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
    worksheet[headerCell].s = headerStyle;
  }

  // Setting column widths
  const columnWidths = [10, 40, 30, 30, 30, 30, 20];

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

  const row2Style = {
    font: {
      color: { rgb: 'FFFFFF' },
      bold: true,
    },
    fill: { fgColor: { rgb: '807C7C' } },
    border: {
      top: { style: 'thin', color: { rgb: 'FFFFFF' } },
      bottom: { style: 'thin', color: { rgb: 'FFFFFF' } },
      left: { style: 'thin', color: { rgb: 'FFFFFF' } },
      right: { style: 'thin', color: { rgb: 'FFFFFF' } },
    },
  };

  const row2Range = XLSX.utils.decode_range(worksheet['!ref']);
  for (let col = row2Range.s.c; col <= row2Range.e.c; col++) {
    const cell = XLSX.utils.encode_cell({ r: 3, c: col });
    worksheet[cell].s = row2Style;
  }
  // Merging the first two rows
  const merges = [
    { s: { r: 0, c: 0 }, e: { r: 1, c: 6 } },
    { s: { r: 2, c: 0 }, e: { r: 3, c: 0 } },
    { s: { r: 2, c: 1 }, e: { r: 3, c: 1 } },
    { s: { r: 2, c: 6 }, e: { r: 3, c: 6 } },

    { s: { r: 2, c: 2 }, e: { r: 2, c: 3 } },
    { s: { r: 2, c: 4 }, e: { r: 2, c: 5 } },


  ];
  worksheet['!merges'] = merges;

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan RL 5.3 - Inap', true);
  XLSXStyle.writeFile(workbook, 'Laporan RL 5.3 - Penyakit Rawat Inap.xlsx');
};

fetchData();
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
</style>
