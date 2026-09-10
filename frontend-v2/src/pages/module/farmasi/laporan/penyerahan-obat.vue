<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Laporan Penyerahan Obat</h3>
    </div>
    <div class="columns">
      <div class="column is-8">
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
        <div v-else>
          <DataTable v-model:expandedRowGroups="expandedRowGroups" tableStyle="min-width: 50rem"
            :class="`p-datatable-small`" :value="dataSource" v-model:filters="filtersTrans"
            :globalFilterFields="['namapasien']" rowGroupMode="subheader" :rowsPerPageOptions="[5, 10, 25, 100]"
            :rows="5" paginator groupRowsBy="namapasien" @rowgroup-expand="onRowGroupExpand"
            @rowgroup-collapse="onRowGroupCollapse" sortMode="single" sortField="namapasien" :sortOrder="1">

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

            <template #empty style="text-align: center;"> No data found. </template>
            <Column field="number" style="min-width: 100px;" header="No Antrian / No Registrasi"></Column>
            <Column field="namapasien" frozen style="min-width: 150px;" header="Nama Pasien"></Column>
            <Column field="kelompokpasien" style="min-width: 80px;" header="Penjamin"></Column>
            <Column field="namaruanganapotik" style="min-width: 90px;" header="Apotik"></Column>
            <Column field="tglverifikasi" style="min-width: 120px;" header="Waktu Selesai Skrining Resep"></Column>
            <Column field="tglambilorder" style="min-width: 120px;" header="Waktu Penyerahan"></Column>
            <Column field="durasi" style="min-width: 50px;" header="Durasi"></Column>
            <Column field="namapengambilorder" style="min-width: 150px;" header="Nama Pengamabil Order"></Column>
            <Column field="keterangan" style="min-width: 150px;" header="Keterangan"></Column>
          </DataTable>
        </div>
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
            <VField class="is-rounded-select is-autocomplete-select required-vfield" label="Ruangan Asal">
              <VControl icon="feather:search" class="prime-auto-select">
                <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'namaruangan'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'namaruangan'" placeholder=" Ruangan Asal" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField label="Jenis Kemasan" class="is-rounded-select is-autocomplete-select
                            mt-0 pt-0" v-slot="{ id }">
              <VControl icon="fas fa-archway" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.jenisKemasan" :options="d_JenisKemasan" :optionLabel="'label'"
                  placeholder="Jenis Kemasan" style="width: 100%;" :filter="true" />
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
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { FilterMatchMode } from 'primevue/api'
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
import * as XLSXStyle from 'xlsx-js-style';
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
useHead({
  title: 'Laporan Penyerahan Obat - ' + import.meta.env.VITE_PROJECT,
})

const item: any = reactive({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const dataSource: any = ref([])
const d_Ruangan: any = ref([])
const isLoadingBtn: any = ref(false)
const isLoading: any = ref(false)
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const d_JenisKemasan: any = ref([
  {
    label: 'Racikan',
    value: 1
  },
  {
    label: 'Non Racikan',
    value: 2
  }
])
const expandedRowGroups = ref();
const onRowGroupExpand = (event) => {

};
const onRowGroupCollapse = (event) => {

};
const exportExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Daftar Pernyarahan Obat'],
    [],
    ['NO', 'NO Antrian', 'Nama Pasien', 'Penjamin', 'Apotik', 'Waktu Selesai Skring Resep', 'Waktu Peyerahan Obat', 'Durasi (menit)', 'Ruang Rawat', 'Cito', 'Resep Pulang', 'Keterangan'],
    ...dataSource.value.map((e: any, index: number) => [
      index + 1,
      e.noantri,
      e.namapasien,
      e.namapasien,
      e.kelompokpasien,
      e.namaruanganapotik,
      e.tglverifikasi,
      e.tglambilorder,
      e.durasi,
      e.namaruanganrawat,
      e.cito != null ? '✓' : '✗',
      e.checkreseppulang != null ? '✓' : '✗',
      e.keterangan
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
  const columnWidths = [5, 20, 20, 20, 30, 25, 25, 10, 25, 12, 12, 20, 12, 14, 20, 20];

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
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 13 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Daftar Penyerahan Obat', true);

  XLSXStyle.writeFile(workbook, 'Daftar Penyerahan Obat.xlsx');
}

const clearFilter = () => {
  delete item.ruangan;
  delete item.jenisKemasan
}
const fetchData = async () => {
  isLoading.value = true
  let tglAwal = moment(item.periode.start).format('YYYY-MM-DD 00:00:00')
  let tglAkhir = moment(item.periode.end).format('YYYY-MM-DD 23:59:59')
  let ruangan = item.ruangan ? item.ruangan.id : ' ';
  let jenisKemasan = item.jenisKemasan ? item.jenisKemasan.value : '';
  await useApi().get(`pelayanan/get-laporan-penyerahan-obat?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}&IdFarmasi=${ruangan}&jeniskemasan=${jenisKemasan}`).then((response: any) => {
    response.daftar.forEach((element: any, index: number) => {
      var diff = Math.abs(new Date(element.tglambilorder) - new Date(element.tglverifikasi));
      var durasi = Math.round((diff / 1000) / 60)
      element.number = element.noantri + '/' + element.noregistrasi
      element.durasi = durasi
    })
    dataSource.value = response.daftar
  })
  isLoading.value = false
}
const fetchRuangan = async (filter: any) => {
  await useApi().get(`farmasi/ruangan-depo?namaruangan=${filter.query}&limit=10`).then((response: any) => {
    d_Ruangan.value = response.ruangan
  })
}
</script>
