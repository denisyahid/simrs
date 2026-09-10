
<template>
    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-5">
          <label class="title-page">Laporan Mutasi Ed </label>
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
            <div class="columns is-multiline">
              <div class="column is-2" style="padding-top:2rem">
                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                  Export To Excel
                </VButton>
              </div>
              <div class="column is-10">
                <div class="columns is-multiline" style="justify-content: flex;">
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
                  <div class="column is-1 mt-5">
                    <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData" :loading="isPlaceLoad" />
                  </div>
                </div>
              </div>
            </div>
          </template>
              <Column field="no" header="#" frozen style="width:20px"></Column>
              <Column field="tglkirim" :sortable="true" style="width: 120px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Tgl Kirim
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.tglkirim }}
                  </div>
                </template>
              </Column>
              <Column field="nokirim" :sortable="true" style="width: 150px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    No Kirim
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.nokirim }}
                  </div>
                </template>
              </Column>
              <Column field="kdproduk" :sortable="true" style="width: 120px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Kode Produk
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: left;">
                    {{ data.kdproduk }}
                  </div>
                </template>
              </Column>
              <Column field="namaproduk" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Nama Produk
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: left;">
                    {{ data.namaproduk }}
                  </div>
                </template>
              </Column>
              <Column field="qtyprodukkonfirmasi" :sortable="true" style="width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Jumlah
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.qtyprodukkonfirmasi }}
                  </div>
                </template>
              </Column>
              <Column field="satuanstandar" :sortable="true" style="width: 200px">
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
              <Column field="hargasatuan" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Harga Satuan
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ H.formatRupiah(Math.ceil(data.hargasatuan),'Rp.') }}
                  </div>
                </template>
              </Column>
              <Column field="totalharga" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Harga Total
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ H.formatRupiah(Math.ceil(data.totalharga),'Rp.') }}
                  </div>
                </template>
              </Column>
              <Column field="nobatch" :sortable="true" style="width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    No Batch
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.nobatch }}
                  </div>
                </template>
              </Column>
              <Column field="tglkadaluarsa" :sortable="true" style="width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Tgl Kadaluarsa
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.tglkadaluarsa }}
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
    title: 'Laporan Mutasi Ed - ' + import.meta.env.VITE_PROJECT,
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
    let ruanganpengirim = item.value.ruanganpengirim ? `&ruanganpengirim=${item.value.ruanganpengirim.value}` : ''
    let ruangantujuan = item.value.ruangantujuan ? `&ruangantujuan=${item.value.ruangantujuan.value}` : ''
    
    await useApi().get(`pelayanan/get-laporan-mutasi-ed?${tglAwal}${tglAkhir}${ruangantujuan}${ruanganpengirim}`).then((response: any) => {
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
    const rencanakebutuhan = Math.round (kebutuhan6bulan - item.qtysekaran);
    const minstok = Math.round (avgPemakaian * 2);
    const maxstock = Math.round(minstok +(7* avgPemakaian));
    const tingkatkecukupan = avgPemakaian > 0 
    
      ? Math.round(item.qtysekaran / (avgPemakaian * 30))
      : 0;
      const statusCito = tingkatkecukupan < 30 ? 'Cito' : '-';

    return {
      ...item
    };
  });
});
  
  const exportExcel = () => {
    console.log(enhancedDataSource.value)
    const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0, 
    }).format(value);
  };


  remakeData.value = enhancedDataSource.value.map((e: any) => {
    return {
      TglKirim: e.tglkirim,
      NoKirim: e.nokirim,
      KodeProduk: e.tglkirim,
      NamaProduk: e.namaproduk,
      Jumlah: e.qtyprodukkonfirmasi,
      Satuan: e.satuanstandar,
      HargaSatuan: Number(e.hargasatuan),
      HargaTotal: Number(e.totalharga),
      NoBatch: e.nobatch,
      TglKadaluarsa: e.tglkadaluarsa,
    };
  });
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
  