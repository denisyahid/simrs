
<template>
    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-5">
          <label class="title-page">Laporan Perencanaan PerSatelit (BMHP)</label>
        </div>
        <div class="column is-12">
          <DataTable :value="enhancedDataSource" class="p-datatable-sm" :loading="isPlaceLoad" :paginator="true"
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
              <div style="padding-top:2rem;">
                <VButton color="info" RouterLink :to="{ name: 'module-logistik-order-barang-new' }" raised
                   icon="fas fa-plus">Order Barang Perencanaan</VButton>
              
              </div>
              <div class="column is-6">
                <div class="columns is-multiline" style="justify-content: flex-end;">
                  <div class="column is-5 pb-0">
                      <!-- <VField label="Periode" style="margin-bottom: 6px;" /> -->
                      <VDatePicker v-model="item.qFilterTgl"  is-range color="pink" locale="id"   trim-weeks>
                        <template #default="{ inputValue, inputEvents }" >
                          <!-- <VField addons>
                            <VControl icon="feather:calendar">
                              <VInput :value="inputValue.start" v-on="inputEvents.start" />
                            </VControl>
                            <VControl>
                              <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                            </VControl>
                            <VControl icon="feather:calendar">
                              <VInput :value="inputValue.end" v-on="inputEvents.end" />
                            </VControl>
                          </VField> -->
                        </template>
                      </VDatePicker>
                  </div>
                  <!-- <div class="column is-3 pb-0">
                    <VField label="Cari">
                      <VInput v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
                    </VField>
                  </div> -->
                  <div class="column is-4">
                      <label style=" margin-bottom: 0.5rem;">Ruangan
                                                    </label>
                                                    <VField>
                                                        <VControl class="prime-auto">
                                                            <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan"
                                                                :optionLabel="'label'" @complete="fetchRuangan($event)"
                                                                :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                placeholder="Ruangan..." class="mt-2" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                  <div class="column is-1 mt-5">
                    <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData" :loading="isPlaceLoad" />
                  </div>
                </div>
              </div>
            </div>
          </template>
              <Column field="no" header="#"></Column>
              <Column field="kdproduk" frozen :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%;">
                    Kode <br> Produk
                  </div>
                </template>
              </Column>
              <Column field="namaproduk" frozen :sortable="true" style="min-width: 200px">
                <template #header>
                  <div style="text-align: center;width: 100%;">
                    Nama <br> Produk
                  </div>
                </template>
              </Column>
              <Column field="satuanstandar" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Satuan
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.satuanstandar }}
                  </div>
                </template>
              </Column>
              <!-- <Column field="namaruangan" header="Nama Satelit" :sortable="true" style="min-width: 80px"></Column> -->
              <Column field="latest_harganetto1" :sortable="true" style="min-width: 120px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Harga <br> Satuan
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: right;">
                    {{ data.latest_harganetto1 }}
                  </div>
                </template>
              </Column>
              <Column field="pengeluaran" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Pemakaian <br>7 Hari
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.pengeluaran }}
                  </div>
                </template>
              </Column>
              <Column field="avgpemakaian" :sortable="true" style="min-width:100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Avg <br> Pemakaian
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.avgpemakaian }}
                  </div>
                </template>
              </Column>
              
              <Column field="kebutuhan6bulan" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Kebutuhan <br> 4 Hari
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.kebutuhan6bulan }}
                  </div>
                </template>
              </Column>
              <Column field="total" :sortable="true" style="min-width: 80px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Sisa <br> Stok
                  </div>
                </template>
                <template #body="slotProps">
                  <div style="text-align: center;width: 100%;">
                    <span :class="{'text-red': slotProps.data.total <= slotProps.data.minstok, 'text-black': slotProps.data.total > slotProps.data.minstok}">
                        {{ slotProps.data.total }}
                    </span>
                   </div>
                 </template>
              </Column>
              <Column field="rencanakebutuhan" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Rencana <br> Kebutuhan
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.rencanakebutuhan }}
                  </div>
                </template>
                
              </Column>
              <Column field="minstok" :sortable="true" style="min-width: 80px;">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Min <br>Stock
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.minstok }}
                  </div>
                </template>
              </Column>
              <Column field="maxstock" :sortable="true" style="min-width: 80px">
                <template #header>
                  <div style="text-align: center;width: 100%;" margin-left:15px>
                    Max  <br> Stock
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.maxstock }}
                  </div>
                </template>
              </Column>
              <Column field="tingkatkecukupan"  :sortable="true" style="min-width: 80px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Tingkat <br> Kecukupan
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.tingkatkecukupan }}
                  </div>
                </template>
              </Column>
              <Column field="statusCito" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Status
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.statusCito }}
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
    title: 'Laporan Perencanaan BMHP (Satelit) - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  
  const modalFilter: any = ref(false)
  const themeColors = useThemeColors()
  const userLogin = useUserSession().getUser()
  const total = ref(0)
  const router = useRouter()
  let d_Ruangan: any = ref([]);
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
    let ruangan = item.value.ruangan ? `&ruangan=${item.value.ruangan.value}` : ''
    let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
    let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''
  
    await useApi().get(`pelayanan/get-laporan-perencanaan-BMHP-2?${tglAwal}${tglAkhir}${ruangan}${dokter}${nama}`).then((response: any) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      });
      dataSource.value = response.data
    })
    isPlaceLoad.value = false
  
  }
  
  const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan,objectdepartemenfk&param_search=namaruangan&query=${filter.query}&objectdepartemenfk=14`
    ).then((response) => {
        // Filter the response to only include items with objectdepartemenfk = 14
        d_Ruangan.value = response.filter((item: any) => item.objectdepartemenfk === 14);
    });
}
  
  const fetchDokter = async (filter: any) => {
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
      d_Dokter.value = response
    })
  }

  const enhancedDataSource = computed(() => {
  return dataSource.value.map(item => {
    const avgPemakaian = Math.round(item.pengeluaran / 7); // rounding the average usage
    const kebutuhan6bulan = Math.round(avgPemakaian * 5.7);
    const rencanakebutuhan = Math.round (kebutuhan6bulan - item.total);
    const minstok = Math.round (avgPemakaian * 2);
    const maxstock = Math.round(minstok +(5* avgPemakaian));
    const tingkatkecukupan = avgPemakaian > 0 
      ? Math.round(item.total / avgPemakaian)
      : 0;

    // console.log('kecukupan', tingkatkecukupan);
    // console.log('item', item.total);

    const statusCito = tingkatkecukupan < 30 ? 'Cito' : '-';
    
    // calculating the 6-month requirement

    return {
      ...item,
      avgpemakaian: avgPemakaian,
      kebutuhan6bulan: kebutuhan6bulan, 
      rencanakebutuhan: rencanakebutuhan,
      minstok: minstok,
      maxstock: maxstock,
      tingkatkecukupan: tingkatkecukupan,
      statusCito: statusCito
      // adding the new field
    };
  });
});
  
  const exportExcel = () => {
    console.log(enhancedDataSource.value)
    remakeData.value = enhancedDataSource.value.map((e: any) => {
      return {
        KodeProduk : e.kdproduk, NamaProduk : e.namaproduk, Satuan : e.satuanstandar, 
        NamaSatelit : e.namaruangan,
        Harga : formatRupiah(e.latest_harganetto1),
        Pemakaian3BulanTerakhir : e.pengeluaran, AVG_Pemakaian : e.avgpemakaian,
        Kebutuhan_6Bulan : e.kebutuhan6bulan,
        SisaStok : e.total,
        Rencana_Kebutuhan : e.rencanakebutuhan,
        MinStok : e.minstok,
        MaxStok : e.maxstock,
        Tingkat_Kecukupan :e.tingkatkecukupan, 
        Status : e.statusCito
      }
    })
    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
  }

  function formatRupiah(value) {
    if (!value) return 'Rp 0';  // Handle cases where value is null or undefined
    return 'Rp ' + value.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
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
  
  // fetchData()
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
  .text-red {
  color: red !important;
}
.text-black {
  color: black !important;
}
  </style>
  