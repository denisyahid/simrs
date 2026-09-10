<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="py-4">
        <h1 style="font-weight:bold">Mapping Dokter BPJS</h1>
      </div>
      <div class="column is-12">
        <VCard>
          <div class="columns is-multiline p-1">
            <div class="column is-6">
              <VField label="Per Tanggal">
                <VControl class="prime-auto">
                  <Calendar v-model="item.tanggal" selectionMode="single" :manualInput="false" class="w-100"
                    :showIcon="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Per Bulan">
                <VControl class="prime-auto">
                  <Calendar inputId="single" v-model="item.filterMonth" view="month" dateFormat="mm/yy"
                    selectionMode="single" :manualInput="false" class="w-100" :showIcon="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <div class="columns is-multiline p-1">
                <div class="column is-4">
                  <VButton type="button" color="info" class="searcv-button" raised icon="fas fa-search"
                    @click="fetchData()" :loading="isLoading">Cari
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12 px-4">
        <VCard>
          <div class="flex-list-inner mb-2 mt-5" v-if="isLoading">
            <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
              <VPlaceloadWrap>
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
          </div>
          <div class="flex-list-inner" v-else-if="dataSourcefiltered == 0">
            <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderSection>
          </div>
          <div v-else>
            <DataTable :value="dataSourcefiltered" class="p-datatable-sm" :loading="isLoading" :paginator="true"
              :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

              <template #header>
                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                  <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()">
                    Export
                    to
                    Excel </VButton>
                </div>
              </template>

              <Column field="no" header="#" frozen></Column>
              <Column field="kdppk" header="Kode Faskes" frozen :sortable="true" style="min-width: 200px"></Column>
              <Column field="nmppk" header="Nama Faskes" frozen :sortable="true" style="min-width: 200px"></Column>
              <Column field="tanggal" header="Tanggal" :sortable="true" style="min-width: 100px"></Column>
              <Column field="jumlah_antrean" header="Jumalah Antrean" :sortable="true" style="min-width: 150px"></Column>
              <Column field="namapoli" header="Napa Poli" :sortable="true" style="min-width: 150px"></Column>
              <Column field="waktu_task1" header="Waktu tunggu adminis" :sortable="true" style="min-width: 150px">
              </Column>
              <Column field="avg_waktu_task1" header="Rata2 Waktu tunggu adminis" :sortable="true"
                style="min-width: 150px"></Column>
              <Column field="waktu_task2" header="Waktu layanan adminis" :sortable="true" style="min-width: 150px">
              </Column>
              <Column field="avg_waktu_task2" header="Rata2 Waktu layan adminis" :sortable="true"
                style="min-width: 150px">
              </Column>
              <Column field="waktu_task3" header="Waktu tunggu poli" :sortable="true" style="min-width: 150px"></Column>
              <Column field="avg_waktu_task3" header="Rata2 Waktu tunggu poli" :sortable="true" style="min-width: 150px">
              </Column>
              <Column field="waktu_task4" header="Waktu layanan poli" :sortable="true" style="min-width: 150px">
              </Column>
              <Column field="avg_waktu_task4" header="Rata2 waktu layanan poli" :sortable="true" style="min-width: 150px">
              </Column>
              <Column field="waktu_task5" header="Waktu tunggu farmasi" :sortable="true" style="min-width: 150px">
              </Column>
              <Column field="avg_waktu_task5" header="Rata2 waktu layanan farmasi" :sortable="true"
                style="min-width: 150px"></Column>
              <Column field="avg_waktu_task6" header="Updated" :sortable="true" style="min-width: 150px"></Column>
            </DataTable>
          </div>
        </VCard>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import moment from 'moment';
import * as XLSX from "xlsx";

const setView = () => {
  useHead({
    title: 'Dokter - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const isLoadingCall = ref(false)
const input: any = ref({})
const item: any = reactive({

})

const dataSourcefiltered: any = ref([]);
const columns: any = ref({})
const d_DokterBPJS: any = ref([])
const d_Dokter: any = ref([])
const route = useRoute()
const dataSource: any = ref([])
const remakeData: any = ref([])
const isLoading: any = ref(false)
const isLoadingSave: any = ref(false)

const fetchData = async () => {
  if (!item.filterMonth && !item.tanggal) {
    H.alert('warning', 'Masukan salah satu filter !');
    return;
  }

  if (item.filterMonth) {
    var json = {
      "url": `dashboard/waktutunggu/bulan/${H.formatDate(item.filterMonth, 'MM')}/tahun/${H.formatDate(item.filterMonth, 'YYYY')}/waktu/rs`,
      "jenis": "antrean",
      "method": "GET",
      "data": null
    }
  } else {
    var json = {
      "url": "dashboard/waktutunggu/tanggal/" + H.formatDate(item.tanggal, 'YYYY-MM-DD') + "/waktu/rs",
      "jenis": "antrean",
      "method": "GET",
      "data": null
    }
  }
  isLoading.value = true
  await useApi()
    .postNoMessage(`/bridging/bpjs/tools`, json)
    .then((response: any) => {
      dataSourcefiltered.value = response.response.list.map((item) => {
        item.waktu_task1 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.waktu_task1).toFixed(2)));
        item.waktu_task2 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.waktu_task2).toFixed(2)));
        item.waktu_task3 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.waktu_task3).toFixed(2)));
        item.waktu_task4 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.waktu_task4).toFixed(2)));
        item.waktu_task5 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.waktu_task5).toFixed(2)));
        item.waktu_task6 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.waktu_task6).toFixed(2)));
        item.avg_waktu_task1 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.avg_waktu_task1).toFixed(2)));
        item.avg_waktu_task2 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.avg_waktu_task2).toFixed(2)));
        item.avg_waktu_task3 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.avg_waktu_task3).toFixed(2)));
        item.avg_waktu_task4 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.avg_waktu_task4).toFixed(2)));
        item.avg_waktu_task5 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.avg_waktu_task5).toFixed(2)));
        item.avg_waktu_task6 = convertSecondsToMinutesAndSeconds(parseFloat(parseFloat(item.avg_waktu_task6).toFixed(2)));
        return item;
      });
    }).catch((err) => {
      console.log(err);
    })
  isLoading.value = false
  clear();
}
function convertSecondsToMinutesAndSeconds(seconds) {
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;

  const formattedMinutes = minutes < 10 ? `0${minutes}` : `${minutes}`;
  const formattedSeconds = remainingSeconds < 10 ? `0${remainingSeconds}` : `${remainingSeconds}`;

  return `${formattedMinutes}:${formattedSeconds}`;
}

const exportExcel = () => {
  remakeData.value = dataSourcefiltered.value.map((e: any) => {
    return {
      KodeFaskes: e.kdppk, NamaFaskes: e.nmppk, Tanggal: e.tanggal,
      JumlahAntrean: e.jumlah_antrean, NamaPoli: e.namapoli, WaktuTungguAdminis: e.waktu_task1, RataRataWaktuTungguAdminis: e.avg_waktu_task1,
      WaktuLayanan: e.waktu_task2, WaktuTungguPoli: e.waktu_task3, Rata2WaktuTungguPoli: e.avg_waktu_task3,
      WaktuLayananPoli: e.waktu_task4, Rata2WaktuLayananPoli: e.avg_waktu_task4,
      WaktuTungguFarmas: e.waktu_task5, Rata2WaktuLayananFarmas: e.avg_waktu_task5
    }
  })
  const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
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
  const _url = window.URL.createObjectURL(data)
  window.open(_url, EXCEL_EXTENSION).focus()
  exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}
const clear = () => {
  item.filterMonth = null;
  item.tanggal = null;
}
</script>

<style lang="scss">
.text-center {
  justify-content: center;
  tect-align: center;
}
</style>
