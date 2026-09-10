
<template>
    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-5">
          <label class="title-page">Laporan Pasien Rawat Inap</label>
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
                  <div class="column is-4">
                    <VField class="is-autocomplete-select">
                    <VLabel>Ruangan</VLabel>
                        <VControl icon="feather:search" :loading="isLoading">
                            <Multiselect mode="single" v-model="item.ruangan" :options="listRuanganStok" placeholder="Pilih ruangan"
                            :searchable="true" />
                        </VControl>
                    </VField>
                </div>
                  <!-- <div class="column is-3 pb-0">
                    <VField label="Cari">
                      <VInput v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
                    </VField>
                  </div> -->
                  <div class="column is-1 mt-5">
                    <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData" :loading="isPlaceLoad" />
                  </div>
                </div>
              </div>
            </div>
          </template>
              <Column field="no" header="#" frozen></Column>
              <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 150px"></Column>
              <Column field="nocm" header="No CM" :sortable="true" style="min-width: 150px"></Column>
              <Column field="noregistrasi" header="No Registrasi" :sortable="true" style="min-width: 100px"></Column>
              <Column field="kelompokpasien" header="Kelompok Pasien" :sortable="true" style="min-width: 100px"></Column>
              <Column field="namaruangan" header="Ruangan" :sortable="true" style="min-width: 100px"></Column>
              <Column field="namakelas" header="Kelas" :sortable="true" style="min-width: 100px"></Column>
              <Column field="namakamar" header="Kamar" :sortable="true" style="min-width: 100px"></Column>
              <Column field="reportdisplay" header="No Bed" :sortable="true" style="min-width: 100px"></Column>
              <Column field="alamatrmh" header="Alamat" :sortable="true" style="min-width: 100px"></Column>
              <Column field="nobpjs" header="No BPJS" :sortable="true" style="min-width: 100px"></Column>
              <Column field="noidentitas" header="No Identitas" :sortable="true" style="min-width: 100px"></Column>
              <Column field="kebangsaan" header="Kebangsaan" :sortable="true" style="min-width: 100px"></Column>
              <Column field="tglregistrasi" header="Tgl Registrasi" :sortable="true" style="min-width: 100px"></Column>
              <Column field="rawatgabung" header="Status Rawat Gabung" :sortable="true" style="min-width: 100px"></Column>
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
    title: 'Laporan Pasien Rawat Inap - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  
  const modalFilter: any = ref(false)
  const themeColors = useThemeColors()
  const userLogin = useUserSession().getUser()
  const total = ref(0)
  const router = useRouter()
  let listRuanganStok: any = ref([])
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
    isPlaceLoad.value = true
    let ruanganCondition = item.value.ruangan ? `&ruanganCondition=${item.value.ruangan}` : ''
    console.log('id ruangan',item.value.ruangan)
    await useApi().get(`pelayanan/get-laporan-pasien-ranap?${ruanganCondition}`).then((response: any) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      });
      dataSource.value = response.data
    })
    isPlaceLoad.value = false
  
  }
  
  async function listDropdown() {
  isLoading.value = true
  const response = await useApi().get(`/logistik/list-ruangan-ranap`)
  listRuanganStok.value = response.ruangan.map((e: any): any => {
    return { label: e.namaruangan, value: e.id }
  })
  isLoading.value = false
}
  
  const exportExcel = () => {
    console.log(dataSource.value)
    remakeData.value = dataSource.value.map((e: any) => {
      return {
        No : e.no,
        NamaPasien : e.namapasien,
        NoCM: e.nocm,
        NoRegistrasi: e.noregistrasi,
        KelompokPasien : e.kelompokpasien,
        Ruangan: e.namaruangan, 
        Kelas: e.namakelas,
        Kamar : e.namakamar,
        Bed : e.reportdisplay,
        Alamat : e.alamatrmh,
        BPJS : e.nobpjs,
        NoIdentitas : e.noidentitas,
        Kebangsaan : e.kebangsaan,
        TglRegis : e.tglregistrasi, 
        Status : e.rawatgabung
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
  listDropdown()
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
  