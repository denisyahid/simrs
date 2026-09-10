
<template>
    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-5">
          <label class="title-page">Laporan Penjualan Per Resep Detail</label>
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
                  <div class="column is-2 pb-0">
                    <VField label="Cari Nama Pasien">
                      <VInput v-model="item.namapasien" placeholder="Cari Pasien" v-on:keyup.enter="fetchData()" style="width:300px" />
                    </VField>
                  </div>
                  <div class="column is-2 pb-0">
                    <VField label="Cari Nama Produk">
                      <VInput v-model="item.namaproduk" placeholder="Cari Produk" v-on:keyup.enter="fetchData()" style="width:300px" />
                    </VField>
                  </div>
                  <div class="column is-2 pb-0">
                      <VField label="Kelompok Pasien" style="margin-bottom: 6px;" />
                      <VField>
                    <VControl class="prime-auto">
                        <AutoComplete v-model="item.kelompokpasiennya"
                            :suggestions="d_KelompokPasien" :optionLabel="'label'"
                            @complete="fetchKelompokPasien($event)" :dropdown="true"
                            :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Kelompok Pasien..."/>
                    </VControl>
                </VField>
                  </div>
                  <div class="column is-2 pb-0">
                      <VField label="Golongan Produk" style="margin-bottom: 6px;" />
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
                
                  <div class="column is-3 pb-0">
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
              <Column field="no" header="#" frozen style="width: 10px"></Column>
              <Column field="noresep" :sortable="true" style="width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    No Resep
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.noresep }}
                  </div>
                </template>
              </Column>
              <Column field="tglresep" :sortable="true" style="width: 80px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Tgl <br> Pelayanan 
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.tglresep }}
                  </div>
                </template>
              </Column>
              <Column field="namapasien" :sortable="true" style="width: 200px" frozen>
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Nama <br> Pasien  
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: left; white-space: nowrap;">
                    {{ data.namapasien }}
                  </div>
                </template>
              </Column>
              <Column field="nocm" :sortable="true" style="width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    No RM   
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: left;">
                    {{ data.nocm }}
                  </div>
                </template>
              </Column>
              <Column field="alamatrmh" :sortable="true" style="min-width: 250px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Alamat  
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: left;">
                    {{ data.alamatrmh }}
                  </div>
                </template>
              </Column>
              <Column field="kelompokpasien" :sortable="true" style="width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Kelompok <br> Pasien  
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.kelompokpasien }}
                  </div>
                </template>
              </Column>
              <Column field="namalengkap" :sortable="true" style="min-width: 200px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Nama  <br> Dokter  
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: left; white-space: nowrap;">
                    {{ data.namalengkap }}
                  </div>
                </template>
              </Column>
              <Column field="namaruangan" :sortable="true" style="min-width: 120px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Nama  <br> Ruangan  
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.namaruangan }}
                  </div>
                </template>
              </Column>
              <Column field="kdproduk" :sortable="true" style="min-width: 250px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Produk    
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: left; white-space: nowrap;">
                    {{ data.kdproduk }} - {{ data.namaproduk }}
                  </div>
                </template>
              </Column>
              <Column field="satelit" :sortable="true">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Nama  <br> Satelit  
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center; white-space: nowrap;">
                    {{ data.satelit }}
                  </div>
                </template>
              </Column>
              <Column field="detailjenisproduk" :sortable="true" style="width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Detail <br> Produk    
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.detailjenisproduk }}
                  </div>
                </template>
              </Column>
              <Column field="isfornas" header="Fornas" :sortable="true" style="width: 80px">
                <template #body="slotProps">
                    <span v-if="slotProps.data.isfornas === true">Fornas</span>
                    <span v-else-if="slotProps.data.isfornas === false">Non Fornas</span>
                    <span v-else>Tidak Ada</span>
                </template>
                </Column>
                <Column field="jumlah" :sortable="true" style="width: 50px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Qty    
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.jumlah }}
                  </div>
                </template>
              </Column>
              <Column field="harganetto1" :sortable="true" style="min-width: 150px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Harga Penerimaan    
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ H.formatRupiah(data.harganetto1,'Rp. ') }}
                  </div>
                </template>
              </Column>
              <Column field="hargajual" :sortable="true" style="min-width: 150px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Harga Jual    
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ H.formatRupiah(data.hargajual,'Rp. ') }}
                  </div>
                </template>
              </Column>
              <Column field="harga_total" :sortable="true" style="min-width: 150px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Total Harga     
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ H.formatRupiah(data.harga_total, 'Rp.')}}
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
    title: 'Laporan Penjualan Per Resep Detail - ' + import.meta.env.VITE_PROJECT,
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
  { label: "Semua", value: "Semua" },
  { label: "Narkotika", value: "N" },
  { label: "Psikotropika", value: "P" },
  { label: "OBAT", value: "O" },
  { label: "BMHP", value: "B" },
  { label: "AMHP", value: "A" },
  { label: "GAS MEDIS", value: "G" },
];

  // const filteredStatus = StatusOptions.filter(status =>
  //   status.label.toLowerCase().includes(filter.query.toLowerCase())
  // );

  d_Status.value = StatusOptions;
};

const fetchKelompokPasien = async (filter) => {
  const KelompokOptions = [
  { label: "UMUM", value: "1" },
  { label: "BPJS", value: "2" },
  { label: "IKS", value: "3" },
  { label: "BPJS KETENAGAKERJAAN", value: "5" },
];

  // const filteredStatus = StatusOptions.filter(status =>
  //   status.label.toLowerCase().includes(filter.query.toLowerCase())
  // );

  d_KelompokPasien.value = KelompokOptions;
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
    let ruanganfk = item.value.ruanganfk ? `&ruanganId=${item.value.ruanganfk.value}` : ''
    let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
    let nama = item.value.namapasien ? `&nama=${item.value.namapasien}` : ''
    let namaproduk = item.value.namaproduk ? `&namaproduk=${item.value.namaproduk}` : ''
    let status = item.value.status ? `&status=${item.value.status.value}` : ''
    let kelompokpasien = item.value.kelompokpasiennya ? `&kelompokpasien=${item.value.kelompokpasiennya.value}` : ''
  
    await useApi().get(`pelayanan/get-laporan-resep?${tglAwal}${tglAkhir}${ruanganfk}${dokter}${status}${nama}${namaproduk}${kelompokpasien}`).then((response: any) => {
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
  
  const exportExcel = () => {
    console.log(dataSource.value)
    remakeData.value = dataSource.value.map((e: any) => {
      return {
        NoResep : e.noresep,
        Tanggal : e.tglresep,
        NamaPasien: e.namapasien, 
        NoRM: e.nocm,
        Alamat: e.alamatrmh,
        KelompokPasien : e.kelompokpasien, 
        Dokter: e.namalengkap, 
        Ruangan: e.namaruangan, 
        KodeProduk : e.kdproduk, 
        NamaProduk : e.namaproduk,
        Satelit : e.satelit,
        DetailProduk : e.detailjenisproduk,
        Fornas: e.isfornas === true ? 'Fornas' : e.isfornas === false ? 'Non Fornas' : 'Tidak Ada',
        Qty : Number(e.jumlah),
        HargaPenerimaan : Number(e.harganetto1),
        Hargajual : Number(e.hargajual), 
        Total : Number(e.harga_total)
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
  