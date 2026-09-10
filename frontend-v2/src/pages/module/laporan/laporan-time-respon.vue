
<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">

      <div class="column is-12">
        <div class="columns">
          <div class="column is-4">
            <VField class="is-autocomplete-select" label="Tanggal Order">
              <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks>
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
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-autocomplete-select" label="Tindakan">
              <VControl icon="feather:search">
                <AutoComplete v-model="item.namaObat" :suggestions="d_Layanan" @complete="fetchLayanan($event)"
                                    :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                     :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                                    placeholder="Cari Nama" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField class="is-autocomplete-select" label="Ruangan Pengorder">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.ruanganfk" @select="fetchData()" :options="d_Ruangan"
                  placeholder="Pilih" :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField class="is-autocomplete-select" label="Nama Pasien">
              <input type="text" v-model="item.namaPasien" v-on:keyup.enter="fetchData()" class="input"
                placeholder="Search..." />
            </VField>
          </div>
          <div class="column btn-search">
            <!-- <VIconButton v-tooltip.bottom.left="'Cari Data'" label="Bottom Right" color="success" class="searcv-button" raised icon="fas fa-search"
                      @click="fetchData()" :loading="isPlaceLoad">
                    </VIconButton> -->
            <VIconButton v-tooltip.bottom.right="'Cari Data'" label="Bottom Right" color="primary" circle
              icon="pi pi-search" @click="fetchData()" :loading="isPlaceLoad" style="margin-top: 20px;" />
            <!-- <VButton type="button" icon="feather:search" @click="fetchData()">
                            Cari Data
                        </VButton> -->
          </div>

        </div>
      </div>
    </VCard>
  </div>

  <div class="column">
    <VCard>
      <h3 class="title is-5 mb-2">Laporan Time Respon Pelayanan Radiologi</h3>
      <div class="column" v-if="isPlaceLoad">
        <VPlaceloadWrap v-for="data in 25">
          <VPlaceload class="mx-2 mb-3" />
          <VPlaceload class="mx-2" />
        </VPlaceloadWrap>
      </div>
      <div class="column" v-else>
        <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan."
          subtitle="Silakan filter pencarian di tanggal lain" larger>
          <template #image>
            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
          </template>
        </VPlaceholderPage>

        <div v-else>
          <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
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

            <Column field="no" header="#" frozen></Column>
            <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px"></Column>
            <Column field="namaruangan" header="Ruangan Asal" style="min-width: 200px"></Column>
            <Column field="namaproduk" header="Tindakan" style="min-width: 200px"></Column>
            <Column field="total" header="Durasi Pelayanan" style="min-width: 200px"></Column>
            <Column field="tglorder" header="Tanggal Order" style="min-width: 200px"></Column>
            <Column field="tglverif" header="Tanggal Verifikasi Order" style="min-width: 200px"></Column>
            <Column field="tanggal" header="Tanggal Expertise" style="min-width: 200px"></Column>
            <Column field="selisih" header="Selisih Waktu Order Ke Verifikasi" style="min-width: 200px"></Column>
            <Column field="selisih2" header="Selisih Waktu Expertise" style="min-width: 200px"></Column>
            <Column field="pengorder" header="Pengorder" style="min-width: 200px"></Column>
            <Column field="dokter" header="Dokter Pemeriksa" style="min-width: 200px"></Column>

          </DataTable>
        </div>
      </div>

    </VCard>
  </div>
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
import * as XLSX from "xlsx";
useHead({
  title: 'Laporan Time Response Radiologi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const item: any = ref({
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
})


const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const remakeData: any = ref([])
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let d_Ruangan: any = ref([])
let d_Layanan: any = ref([])
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters = ref('')

const dataTagihanBelumLunas = computed(() => {
  if (!item.value.qFilter) {
    return dataHutang.value
  }
  return dataHutang.value.filter((items: any) => {
    return (
      items.namapasien.match(new RegExp(item.value.qFilter, 'i')) ||
      items.noRegistrasi.match(new RegExp(item.value.qFilter, 'i'))
    )
  })
})

const fetchData = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfk = item.value.ruanganfk ? `&ruanganfk=${item.value.ruanganfk}` : ''
  let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
  let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''
  let produk = item.value.namaObat ? `&produk=${item.value.namaObat.idpro}` : ''

  await useApi().get(`laporan/laporan-time-respon?${tglAwal}${tglAkhir}${nama}${ruanganfk}${produk}`).then((response: any) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response
  })
  isPlaceLoad.value = false

}

const fetchLayanan = async (filter: any) => {
    let namaproduk = filter ? `?namaproduk=${filter.query}` : ''
    useApi().get(`laboratorium/get-dd-layanan${namaproduk}`).then((response) => {
        d_Layanan.value = response.layanan
    })
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const listDataCombo = async () => {
  await useApi().get('/laporan/pilihan-search').then((response) => {
    d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
  })
}
const exportExcel = () => {
  console.log(dataSource.value)
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      NamaPasien: e.namapasien, NoRM: e.nocm, JenisKelamin: e.jeniskelamin,
      Antrian: e.noantrian, Tanggal: e.tglregistrasi, Dokter: e.namalengkap, Ruangan: e.namaruangan,
      CaraBayar: e.kelompokpasien, Bayar: e.bayar, Status: e.statuspasien,
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
  // window.open(_url,EXCEL_EXTENSION).focus()
  // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

listDataCombo()
fetchData()
</script>
  