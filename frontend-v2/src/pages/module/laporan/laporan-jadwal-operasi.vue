
<template>
  <div class="columns is-multiline">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Laporan Jadwal Operasi</label>
      </div>
       <div class="column is-2" style="padding-top:2rem">
              <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                Export To Excel
              </VButton>
            </div>
      <div class="column is-12">
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
                <div class="column is-3 pb-0">
                  <VField label="Cari">
                    <VInput v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
                  </VField>
                </div>
                <div class="column is-1 mt-5">
                  <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData" :loading="isPlaceLoad" />
                </div>
              </div>
        <DataTable :value="dataLaporan" class="p-datatable-sm" :paginator="true" :rows="10"
             :rowsPerPageOptions="[5, 10, 25]"
             paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
             scrollable scrollHeight="flex" tableStyle="min-width: 100rem" breakpoint="960px"
             sortMode="multiple"
             :loading="isLoading"
             currentPageReportTemplate="Showing {first} to {last} of {totalRecords} showGridlines ">
             <!-- <Column :exportable="false" header="#">
                 <template #body="slotProps">
                     <VIconButton type="button" icon="pi pi-bookmark" class="mr-3" color="info"
                         circle outlined raised v-tooltip.top="'Detail'"
                         @click="edit(slotProps.data)">
                     </VIconButton>
                 </template>
             </Column> -->
             <Column field="no" header="No"></Column>
             <Column field="jamoperasi" header="Jam Operasi"></Column>
              <Column field="kamaroperasi" header="Ruang OK"></Column>
             <Column field="namapasien" header="Nama Pasien" :sortable="true"></Column>
             <Column field="jeniskelamin" header="Jenis Kelamin" :sortable="true"></Column>
             <Column field="umur_pasien" header="Umur" :sortable="true"></Column>
             <Column field="nocm" header="NO RM" :sortable="true"></Column>
             <Column field="tgllahir" header="Tanggal Lahir" :sortable="true"></Column>
             <Column field="diagnosis" header="Diagnosa Pre OP" :sortable="true"></Column>
             <Column field="namaproduk" header="Tindakan"></Column>
             <Column field="dokterpemeriksa" header="Dokter Pemeriksa"></Column>
             <Column field="dokteranestesi" header="Dokter Anestesi"></Column>
             <Column field="estimasi" header="Estimasi Jam"></Column>
             <Column field="kelompokpasien" header="Cara Bayar"></Column>
             <Column field="tgloperasi" header="Tanggal Operasi"></Column>
             <Column field="asalruangan" header="Asal Ruangan"></Column>
             <Column field="tinggibadan" header="Tinggi Badan"></Column>
             <Column field="beratbadan" header="Berat Badan"></Column>
             <Column field="riwayatswab" header="Riwayat Swab"></Column>
             <Column field="userpenerima" header="Petugas Verifikator"></Column>
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
  title: 'Laporan Kunjungan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const dataLaporan: any = ref(0)
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
    isLoading.value = true;
    let dari = '';
    let sampai = '';

    // Periksa apakah filterTgl didefinisikan sebelum mengakses properti
    if (item.value.qFilterTgl && item.value.qFilterTgl.start) {
        dari = `&dari=${H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')}`;
    }
    if (item.value.qFilterTgl && item.value.qFilterTgl.end) {
        sampai = `&sampai=${H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')}`;
    }

    let produk = item.value.namaproduk ? `&namaproduk=${item.value.namaproduk}` : '';
    let qnamapasien = item.value.qnama ? `&qnamapasien=${item.value.qnama}` : '';
    let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
    let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
    let search = item.value.qsearch ? `&search=${item.value.qsearch}` : '';

    try {
        const response = await useApi().get(
            `/dashboard/laporan-tindakan-operasi?${dari}${sampai}${produk}${qnamapasien}${qnocm}${qnoregistrasi}${search}`
        );
        response.forEach((element, i) => {
            element.no = i + 1;
            element.jamoperasi = element.jamoperasi || '';
        });
        dataLaporan.value = response;
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};



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
