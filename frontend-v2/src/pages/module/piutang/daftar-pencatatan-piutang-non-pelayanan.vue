<template>
  <div class="column is-12">
    <VCard>
      <div class="column c-title pt-2 mb-3">
        <label class="title-page">Pencarian</label>
      </div>
      <div class="search-widget">
        <div class="field">
          <div class="columns is-multiline">
            <div class="column is-4">
              <span>Periode</span>
              <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                <template #default="{ inputValue, inputEvents }">
                  <VField addons>
                    <VControl icon="feather:calendar">
                      <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                    </VControl>
                    <VControl>
                      <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                    </VControl>
                    <VControl icon="feather:calendar">
                      <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4">
              <span>Nama Pasien</span>
              <VField>
                <VInput placeholder="Nama Pasien..." class="mt-1" v-model="item.namaPasien"></VInput>
              </VField>
            </div>
            <div class="column is-4">
              <span class="mb-1">Status</span>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl class="prime-auto-select">
                  <Dropdown v-model="item.status" :options="d_Status" :optionLabel="'label'" placeholder="Pilih data"
                    style="width: 100%;" showClear :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column" style="margin-left: auto:  !important;">
              <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                @click="fetchData()" :loading="isPlaceLoad">
              </VIconButton>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="column c-title pt-2 mb-3">
        <label class="title-page">Daftar Pencatatan Piutang Non Layanan</label>
      </div>
      <div class="column" v-if="isLoading">
        <VPlaceloadWrap v-for="data in 5">
          <VPlaceload class="mx-2 mb-3" />
          <VPlaceload class="mx-2" />
        </VPlaceloadWrap>
      </div>
      <div class="column" v-else>
        <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan." subtitle="Ubah Filter Pencari !"
          larger>
          <template #image>
            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
          </template>
        </VPlaceholderPage>
        <DataTable v-else :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
          :rowsPerPageOptions="[5, 10, 25]" scrollable
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <template #header>
            <div class="flex flex-wrap align-items-center justify-content-between gap-2">
              <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
                to
                Excel </VButton>
            </div>
          </template>
          <Column field="no" header="No" frozen></Column>
          <Column field="noRegistrasi" header="No Registrasi" frozen :sortable="true" style="min-width: 150px"></Column>
          <Column field="tglTransaksi" header="Tanggal" frozen :sortable="true" style="min-width: 100px"></Column>
          <Column field="namaPasien" header="Nama" frozen :sortable="true" style="min-width: 200px"></Column>
          <Column field="jenisPasien" header="Jenis Penjamin" :sortable="true" style="min-width: 200px"></Column>
          <Column field="totalBilling" header="Total Tagihan" :sortable="true" style="min-width: 200px"></Column>
          <Column field="totalKlaim" header="Total Klaim" :sortable="true" style="min-width: 200px"></Column>
          <Column field="umur" header="Umur" :sortable="true" style="min-width: 200px"></Column>
          <Column field="status" header="Status" :sortable="true" style="min-width: 200px"></Column>
        </DataTable>
      </div>
    </VCard>
  </div>
  <!-- modal rekanan -->
  <VModal :open="modalInput" title="Tambah Asal Rujukan" actions="right" @close="modalInput = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField label="Penjamin" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:command">
                <Multiselect mode="single" class="is-rounded" v-model="item.rekanan" :options="d_Rekanan"
                  placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT" />
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:plus" @click="save()" :loading="isLoadingTT" color="primary" raised>Simpan</VButton>
    </template>
  </VModal>
  <!-- end modak rekanan -->
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import * as XLSX from "xlsx";
useHead({
  title: 'Laporan Pencatatan Pelayanan- ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
})
const d_KelompokPasien: array = ref([]);
const d_Rekanan: array = ref([]);
const dataSource: any = ref([]);
const d_Status: array = ref([
  {
    label: 'Piutang',
    value: 1
  },
  {
    label: 'Collection',
    value: 1
  },
  {
    label: 'Lunas',
    value: 1
  }
]);
const isLoading: boolean = ref(false);
const isLoadingTT: boolean = ref(false);
const remakeData: any = ref([]);
const modalInput = ref(false)
const fetchKelompokPasien = async (filter: any) => {
  await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}
const fetchRekanan = async (filter: any) => {
  await useApi().get(`emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Rekanan.value = response
  })
}

const fetchData = async () => {
  isLoading.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let nama = item.value.namaPasien ? `&namaPasien=${item.value.namaPasien}` : ''
  await useApi().get(`piutang/daftar-piutang-non-layanan?${tglAwal}${tglAkhir}${nama}`).then((response: any) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1,
        element.tglTransaksi = moment(element.tglTransaksi).format('DD-MMM-YYYY'),
        element.totalBilling = formatRp(element.totalBilling, 'Rp.'),
        element.totalKlaim = formatRp(element.totalKlaim, 'Rp.')
    });
    dataSource.value = response
  })
  isLoading.value = false
}
const exportExcel = () => {
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      No: e.no, NoRegistrasi: e.noRegistrasi, NamaPasien: e.namapasien,
      TanggalTransaksi: e.tglTransaksi, JenisPasien: e.jenisPasien,
      Rekanan: e.rekanan, TotalBilling: e.totalBilling,
      TotalTidakDiklaim: e.totaltidakdiklaim, TotalKlaim: e.TotalKlaim,
      TotalTidakDiklaim: e.totaltidakdiklaim, TotalKlaim: e.TotalKlaim,
      TotalBayar: e.totalBayar, SisaUtang: e.sisautang,
      TotalBayar: e.umur, Status: e.status,
      NoCollect: e.noposting,
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
  window.open(_url, EXCEL_EXTENSION).focus();
}
const editRekanan = async (e: any) => {
  e.loading = true
  await fetchRekanan({ query: "" });
  item.value.rekanan = e.idrekanan
  modalInput.value = true
  e.loading = true
}
const save = async () => {

}
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

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
  margin-top: 14px;
}

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}
</style>
