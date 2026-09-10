<template>
    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-5">
          <label class="title-page">Lap. Persediaan yang Akan Kedaluwarsa Satelit</label>
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

              <div class="column is-10">
                <div class="columns is-multiline" style="justify-content: flex-end;">
                  <div class="column is-3 pb-0">
                    <VField class="is-autocomplete-select">
              <VLabel>Ruangan</VLabel>
              <VControl icon="feather:search" :loading="isLoading">
                <Multiselect mode="single" v-model="item.ruangan" :options="d_Ruangan" placeholder="Pilih ruangan"
                  :searchable="true" />
              </VControl>
            </VField>
                  </div>

                  <div class="column is-3 pb-0">
                   <VField label="Cari Nama Produk">
                        <VInput v-model="item.namaproduk" placeholder="Cari Produk" v-on:keyup.enter="fetchData()" style="width:300px" />
                    </VField>
                  </div>

                  <div class="column is-3 pb-0">
                    <VField label="Jenis Produk" style="margin-bottom: 6px;" />
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
                  
                  <div class="column is-1 mt-5">
                    <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData" :loading="isPlaceLoad" />
                  </div>
                </div>
              </div>
            </div>
          </template>
            <Column :headerStyle="{ width: '3em' }">
            <template #header>
              <input type="checkbox" :checked="allSelected" @change="toggleSelectAll" />
            </template>
            <template #body="slotProps">
              <input type="checkbox" v-model="slotProps.data.selected" />
            </template>
          </Column>
              <Column field="no" header="#" frozen></Column>
              <Column field="kdproduk" frozen :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%;">
                    Kode <br> Produk
                  </div>
                </template>
              </Column>
              <Column field="namaproduk" :sortable="true" style="min-width: 300px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Nama Produk
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: left;">
                    {{ data.namaproduk }}
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
              <Column field="harganetto1" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Harga <br> Satuan
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: right;">
                    {{ data.harganetto1 }}
                  </div>
                </template>
              </Column>
              <Column field="totalstok" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Total <br> Stok 
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.totalstok }}
                  </div>
                </template>
              </Column>
              <Column field="totalharga" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Total <br> Harga 
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: right;">
                    {{ data.totalharga }}
                  </div>
                </template>
              </Column>
              <Column field="tglkadaluarsa" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Tanggal <br> Kadaluarsa 
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: right;">
                    {{ data.tglkadaluarsa }}
                  </div>
                </template>
              </Column>
          </DataTable>
          <strong>Total Qty:</strong> {{ totalQty }}
          <br>
          <strong>Total Keseluruhan Harga:</strong> {{ formatRupiah (totalHarga) }}
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
    title: 'Lap. Persediaan yang Akan Kedaluwarsa Satelit' + import.meta.env.VITE_PROJECT,
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
  let d_Status: any = ref([])
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

  const fetchStatus = async (filter) => {
  const StatusOptions = [
  { label: "OBAT", value: "O" },
  { label: "BMHP", value: "B" },
  { label: "AMHP", value: "A" },
];

  d_Status.value = StatusOptions;
};
  
  const fetchData = async () => {
    isPlaceLoad.value = true
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
    let ruangan = item.value.ruangan ? `&ruangan=${item.value.ruangan}` : ''
    let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
    let nama = item.value.namaproduk ? `&nama=${item.value.namaproduk}` : ''
    let status = item.value.status ? `&status=${item.value.status.value}` : ''
  
    await useApi().get(`pelayanan/get-laporan-kadaluarsa-satelit?${tglAwal}${tglAkhir}${ruangan}${nama}${status}`).then((response: any) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      });
      dataSource.value = response.data
    })
    isPlaceLoad.value = false
  
  }
  
  async function listDropdown() {
  isLoading.value = true
  const response = await useApi().get(`/logistik/kartu-stok-cbo`)
  d_Ruangan.value = response.ruangan.map((e: any): any => {
    return { label: e.namaruangan, value: e.id }
  })
  isLoading.value = false
}
  
  const fetchDokter = async (filter: any) => {
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
      d_Dokter.value = response
    })
  }

  function formatRupiah(value) {
  return 'Rp ' + parseFloat(value).toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
}

const enhancedDataSource = computed(() => {
  return dataSource.value
    .map(item => {
      const totalstok = Math.round(
        parseFloat(item.totalstok)
      );

      if (totalstok === 0) return null; // Jika totalstok 0, return null untuk di-filter nanti

      const totalharga = Math.round(parseFloat(item.harganetto1) * totalstok);

      return {
        ...item,
        tglkadaluarsa: H.formatDateNoTime(item.tglkadaluarsa),
        harganetto1: formatRupiah(item.harganetto1),
        totalstok: totalstok,
        totalharga: formatRupiah(totalharga)
      };
    })
    .filter(item => item !== null); // Filter item yang totalstok-nya 0
});

const calculateTotalQty = () => {
  return dataSource.value
    .filter(row => row.selected)
    .reduce((total, row) => total + (parseFloat(row.totalstok) || 0), 0);
};

// Fungsi untuk menghitung total harga produk yang dipilih
const calculateTotalHarga = () => {
  return dataSource.value
    .filter(row => row.selected)
    .reduce((total, row) => total + (parseFloat(row.totalstok) * parseFloat(row.harganetto1) || 0), 0);
};

// Reactive values for totals
const totalQty = ref(calculateTotalQty());
const totalHarga = ref(calculateTotalHarga());

// Watcher untuk mendeteksi perubahan pada dataSourceSR
watch(dataSource, () => {
  totalQty.value = calculateTotalQty();
  totalHarga.value = calculateTotalHarga();
}, { deep: true });

const allSelected = computed({
  get: () => dataSource.value.every(row => row.selected),
  set: (value) => {
    dataSource.value.forEach(row => {
      row.selected = value;
    });
  },
});

// Fungsi untuk toggle "Select All"
const toggleSelectAll = () => {
  
  allSelected.value = !allSelected.value;
};
  
  const exportExcel = () => {
    console.log(dataSource.value)
    remakeData.value = enhancedDataSource.value.map((e: any) => {
      return {
        KodeProduk : e.kdproduk, 
        NamaProduk : e.namaproduk, 
        Satuan : e.satuanstandar, 
        Harga : e.harganetto1,
        SF_Intensif : e.intensif,
        SF_BedahSentral : e.bedsen, 
        SF_Ranap : e.ranap,
        SF_GudangFarmasi : e.gudfar,
        // SF_Rajal : e.rajal,
        SF_Onko : e.onko,
        SF_Sentral : e.sentral,
        TotalStok : e.totalstok,
        TotalHarga :e.totalharga,
        TanggalKadaluarsa : e.tglkadaluarsa 
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
  