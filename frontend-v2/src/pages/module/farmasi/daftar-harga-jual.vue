
<template>
    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-5">
          <label class="title-page"> Daftar Harga Jual Produk</label>
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
            <div class="columns is-multiline pb-2">
              <div class="column is-1" style="padding-top:2rem">
                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                  Export To Excel
                </VButton>
              </div>
              <div class="column is-10">
                <div class="columns is-multiline" style="justify-content: flex-end;">
                  <div class="column is-3">
                    <VField label="Jenis Barang">
                      <VControl class="prime-auto">
                        <AutoComplete v-model="item.jenisfk" :suggestions="d_jnsProduk" @complete="fetchJenis($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :disabled="isDisabled"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ..." />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pb-0">
                    <VField label="Cari Nama Produk">
                      <VInput v-model="item.namaproduk" placeholder="Cari Produk" v-on:keyup.enter="fetchData()" style="width:300px" />
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
                     {{ H.formatRupiah(data.latest_harganetto1,'Rp ') }}
                  </div>
                </template>
              </Column>
              <Column field="bpjs" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Harga <br> BPJS 
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: right;">
                    {{ H.formatRupiah(data.bpjs,'Rp ') }}
                  </div>
                </template>
              </Column>
              
              <Column field="umum" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Harga <br> UMUM 
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: right;">
                    {{ H.formatRupiah(data.umum,'Rp ') }}
                  </div>
                </template>
              </Column>

              <Column field="iks" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Harga <br> IKS 
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: right;">
                    {{ H.formatRupiah(data.iks,'Rp ') }}
                  </div>
                </template>
              </Column>

              <Column field="umumwna" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Harga <br> WNA 
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: right;">
                    {{ H.formatRupiah(data.umumwna,'Rp ') }}
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
    title: 'Daftar Harga Jual Produk ' + import.meta.env.VITE_PROJECT,
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
  let d_jnsProduk: any = ref([])
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
    console.log(item.value.namaproduk);
    let jenis = item.value.jenisfk ? `&jenis=${item.value.jenisfk.value}` : '';
    let produk = item.value.namaproduk ? `&produk=${item.value.namaproduk}` : ''
    await useApi().get(`logistik/daftar-harga-jual?${produk}${jenis}`).then((response: any) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      });
      dataSource.value = response.data
    })
    isPlaceLoad.value = false
  
  }

  const fetchJenis = async (filter: any) => {
  const response = await useApi().get(`/logistik/list-order-cbo`)
  d_jnsProduk.value = response.jenisbarang.map((e: any) => { return { label: e.detailjenisproduk, value: e.id } })
}

  const formatRupiah = (angka) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(angka);
};

  const enhancedDataSource = computed(() => {
  return dataSource.value.map(item => {
    let hargasatuan = parseFloat(Math.round(item.latest_harganetto1)); 
    const bpjs = hargasatuan > 100000000 
    ? hargasatuan + (hargasatuan*7/100) 
    : hargasatuan > 5000000
    ? hargasatuan + (hargasatuan*9/100)
    : hargasatuan > 1000000
    ? hargasatuan + (hargasatuan*11/100)
    : hargasatuan > 500000
    ? hargasatuan + (hargasatuan*16/100)
    : hargasatuan > 250000
    ? hargasatuan + (hargasatuan*21/100)
    : hargasatuan < 250000
    ? hargasatuan + (hargasatuan*25/100)
    : 0 ; 
    const bpjsnaker = Math.ceil(hargasatuan+(hargasatuan*28/100));
    const umum = Math.ceil(hargasatuan+(hargasatuan*28/100));
    const iks = Math.ceil(hargasatuan+(hargasatuan*28/100));
    const bpjswna = Math.ceil(bpjs*1.5);
    const umumwna = Math.ceil(umum*1.5);
    return {
      ...item,
      latest_harganetto1:hargasatuan,
      bpjs: bpjs,
      bpjsnaker: bpjsnaker, 
      umum: umum,
      iks: iks,
      bpjswna:bpjswna,  
      umumwna:umumwna
    };
  });
});
  
  const exportExcel = () => {
    console.log(enhancedDataSource.value)
    remakeData.value = enhancedDataSource.value.map((e: any) => {
      return {
        KodeProduk : e.kdproduk, 
        NamaProduk : e.namaproduk, 
        Satuan : e.satuanstandar, 
        HargaSatuan : Number(e.latest_harganetto1),
        HargaBPJS : Number(e.bpjs),
        HargaUmum : Number(e.umum),
        HargaIKS : Number(e.iks),
        HargaWNA : Number(e.umumwna),
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
  .text-red {
  color: red !important;
}
.text-black {
  color: black !important;
}
  </style>
  