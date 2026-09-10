
<template>
  <div class="columns is-multiline">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Laporan Pengunjung</label>
      </div>
      <div class="column is-12">
        <DataTable :value="dataSource" class="p-datatable-sm" :loading="isPlaceLoad" :paginator="true"
        :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
        v-model:filters="filters" :globalFilterFields="['namapasien', 'nocm' ,'kelompokpasien','noantrian' ,'namalengkap' ,'kelompokpasien']"
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
                    <VDatePicker v-model="item.qFilterTgl"  is-range color="pink" locale="id"   trim-weeks>
                      <template #default="{ inputValue, inputEvents }" >
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                </div>
                <div class="column is-3 pb-0" style="margin-top: -7px;">
                    <VField label="Ruangan">
                        <VControl class="prime-auto">
                            <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan"
                                :optionLabel="'label'" @complete="fetchRuangan($event)"
                                :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Ruangan..." class="mt-2" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3 pb-0" style="margin-top: -7px;">
                    <VField label="Kelompok pasien">
                        <VControl class="prime-auto">
                            <AutoComplete v-model="item.kelompokpasien" :suggestions="d_KelompokPasien"
                                :optionLabel="'label'" @complete="fetchKelompokPasien($event)"
                                :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Kelompok Pasien..." class="mt-2" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3 pb-0" style="margin-top: -7px;">
                    <VField label="kebangsaan pasien">
                        <VControl class="prime-auto">
                            <AutoComplete v-model="item.kebangsaan" :suggestions="d_kebangsaan"
                                :optionLabel="'label'" @complete="fetchkebangsaan($event)"
                                :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Kebangsaan Pasien..." class="mt-2" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3 pb-0">
                  <VField label="Cari">
                    <VInput v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
                  </VField>
                </div>
                <div class="column is-1 mt-5">
                  <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData" :loading="isPlaceLoad" />
                </div>
              </div>
            </div>
          </div>
        </template>
            <Column field="no" header="#" frozen></Column>
            <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px"></Column>
            <Column field="nocm" header="No RM" :sortable="true" style="min-width: 100px"></Column>
            <Column field="jeniskelamin" header="JK" :sortable="true" style="min-width: 200px"></Column>
            <Column field="alamatlengkap" header="Alamat" :sortable="true" style="min-width: 200px"></Column>
            <Column field="tglregistrasi" header="Tanggal" :sortable="true" style="min-width: 200px">
              <!-- <template #body="slotProps">
                <span>{{ H.formatDateToLocalString(slotProps.data.tglregistrasi) }}</span>
              </template> -->
            </Column>
            <Column field="jamregis" header="Jam" :sortable="true" style="min-width: 100px"></Column>
            <Column field="dokter" header="Dokter" :sortable="true" style="min-width: 200px"></Column>
            <Column field="kelompokpasien" header="Cara Bayar" :sortable="true" style="min-width: 200px"></Column>
            <Column field="nosep" header="SEP" :sortable="true" style="min-width: 200px"></Column>
            <Column field="statuspasien" header="Status" :sortable="true" style="min-width: 100px">
              <template #body="slotProps">
                <VTag class="ml-4" color="primary" rounded>{{ slotProps.data.statuspasien }}</VTag>
              </template>
            </Column>
        </DataTable>
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
import { FilterMatchMode } from 'primevue/api'
useHead({
  title: 'Laporan Pengunjung - ' + import.meta.env.VITE_PROJECT,
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
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
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
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const d_KelompokPasien: any = ref([])
const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})

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
  let ruanganfk = item.value.ruangan ? `&ruanganId=${item.value.ruangan.value}` : ''
  let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
  let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''
  let kelompokpasienfk = item.value.kelompokpasien ? `&kpid=${item.value.kelompokpasien.value}` : ''
  let kebangsaanPasienFk = item.value.kebangsaan ? `&kebangsaan=${item.value.kebangsaan.value}` : ''

  await useApi().get(`pelayanan/get-laporan-pengunjung?${tglAwal}${tglAkhir}${ruanganfk}${dokter}${nama}${kelompokpasienfk}${kebangsaanPasienFk}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchKelompokPasien = async (filter: any) => {
  await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}

let d_kebangsaan: any = ref([])

const fetchkebangsaan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/kebangsaan_m?select=id,name&param_search=name&query=${filter.query}&limit=10`
  ).then((response) => {
    d_kebangsaan.value = response
  })
}

const exportExcel = () => {
  console.log(dataSource.value)
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      NamaPasien: e.namapasien, NoRM: e.nocm, JenisKelamin: e.jeniskelamin, Alamat: e.alamatlengkap,
      Antrian: e.noantrian, Tanggal: e.tglregistrasi,Jam: e.jamregis, Dokter: e.dokter,
      CaraBayar: e.kelompokpasien,NoSEP: e.nosep, Status: e.statuspasien,
      kebangsaan: e.kebangsaan
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

fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/module/sysadmin/master-data.scss';
.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}
</style>
