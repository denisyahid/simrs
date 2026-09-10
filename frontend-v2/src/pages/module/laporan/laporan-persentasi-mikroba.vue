
<template>
    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-5">
          <label class="title-page">Laporan Persentase Antimikroba</label>
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
              <div class="column is-1" style="padding-top:2rem">
                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                  Export To Excel
                </VButton>
              </div>

              <div class="column is-11">
                <div class="columns is-multiline" style="justify-content: flex-end;">
                  <div class="column is-6 pb-0">
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
              
                  <div class="column is-2 pb-0">
                          <VField label="Filter Departemen" style="margin-bottom: 6px;" />
                          <VField>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="item.status"
                                    :suggestions="d_Status" :optionLabel="'label'"
                                    @complete="fetchStatus($event)" :dropdown="true"
                                    :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Status..."/>
                            </VControl>
                          </VField>
                  </div>

                  <div class="column is-2">
                    <VField label="Filter Ruangan">
                      <VControl class="prime-auto">
                        <AutoComplete v-model="item.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :disabled="isDisabled"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ..." />
                      </VControl>
                    </VField>
                  </div>
                
              <div class="card flex justify-content-center">
                <Dropdown v-model="selectedCity" :options="cities" optionLabel="name" placeholder="Select a City" class="w-full md:w-14rem" />
              </div>
              
              <div class="column is-1 mt-5">
                <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData" :loading="isPlaceLoad" />
              </div>
                </div>
              </div>
            </div>
          </template>
              <Column field="totalpasien" :sortable="true" style="width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Jumlah Pasien
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.totalpasien }}
                  </div>
                </template>
              </Column>
              <Column field="totalpasienmikroba" :sortable="true" style="width: 80px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Jumlah Pasien Dengan Antimikroba 
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.totalpasienmikroba }}
                  </div>
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
    title: 'Laporan Persentase AntiMikroba - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  
  const modalFilter: any = ref(false)
  const themeColors = useThemeColors()
  const userLogin = useUserSession().getUser()
  const total = ref(0)
  const router = useRouter()
  const modalInput = ref(false)
  let d_Status: any = ref([])
  let d_KelompokPasien: any = ref([])
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
  

const fetchStatus = async (filter) => {
  const StatusOptions = [
  { label: "Rawat Inap", value: "ranap" },
  { label: "Rawat Jalan", value: "rajal" },
];

  // const filteredStatus = StatusOptions.filter(status =>
  //   status.label.toLowerCase().includes(filter.query.toLowerCase())
  // );

  d_Status.value = StatusOptions;
};



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
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
    let ruanganfk = item.value.namaruangan ? `&ruanganId=${item.value.namaruangan.value}` : ''
    let status = item.value.status ? `&status=${item.value.status.value}` : ''
    
    await useApi().get(`pelayanan/get-laporan-persentase-mikroba?${tglAwal}${tglAkhir}${ruanganfk}${status}`).then((response: any) => {
      dataSource.value = response
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
  
  const exportExcel = () => {
    remakeData.value = dataSource.value.map((e: any) => {
      return {
        JumlahPasien : Number(e.totalpasien),
        JumlahPasienAntiMikroba : Number(e.totalpasienmikroba),
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
  