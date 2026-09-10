<template>
    <div class="column">
      <VCard>
        <div class="column is-12">
          <div class="search-widget">
            <div class="field">
              <div class="columns is-multiline">
                <div class="column is-4 pt-0 pb-0">
                  <span>Periode</span>
                  <VDatePicker
                    v-model="item.qFilterTgl"
                    is-range
                    color="pink"
                    trim-weeks
                    class="pt-2"
                  >
                    <template #default="{ inputValue, inputEvents }">
                      <VField addons>
                        <VControl icon="feather:calendar">
                          <VInput
                            :value="inputValue.start"
                            class="input-calendar"
                            v-on="inputEvents.start"
                          />
                        </VControl>
                        <VControl>
                          <VButton static
                            ><i class="fas fa-arrow-right" aria-hidden="true"></i
                          ></VButton>
                        </VControl>
                        <VControl icon="feather:calendar">
                          <VInput
                            :value="inputValue.end"
                            class="input-calendar"
                            v-on="inputEvents.end"
                          />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-3 pt-0 pb-0">
                  <span>Ruangan</span>
                  <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete
                        v-model="item.ruanganfk"
                        :suggestions="d_Ruangan"
                        @complete="fetchRuangan($event)"
                        :optionLabel="'label'"
                        :dropdown="true"
                        :minLength="3"
                        :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'"
                        :field="'label'"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column pt-0 pb-0 is-4">
                  <span>Barang</span>
                  <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete
                        v-model="item.barangfk"
                        :suggestions="d_barang"
                        @complete="fetchbarang($event)"
                        :optionLabel="'productname'"
                        :dropdown="true"
                        :minLength="3"
                        class="is-rounded"
                        :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'"
                        :field="'productname'"
                        placeholder="ketik untuk mencari..."
                      >
                        <template #option="slotProps">
                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <span style="font-weight: bold">{{
                                slotProps.option.name
                              }}</span>
                            </div>
                            <div class="column is-12 mt-5-min">
                              <table style="width: 50%">
                                <tr>
                                  <td>
                                    <b>{{ slotProps.option.namaproduk }}</b>
                                  </td>
                                </tr>
                                <tr>
                                  <td>Kategory : {{ slotProps.option.satuanstandar }}</td>
                                </tr>
                              </table>
                            </div>
                          </div>
                        </template>
                      </AutoComplete>
                      <!-- <AutoComplete
                        v-model="item.barangfk"
                        :suggestions="d_barang"
                        @complete="fetchbarang($event)"
                        :optionLabel="'namaproduk'"
                        :dropdown="true"
                        :minLength="3"
                        :loadingIcon="'pi pi-spinner'"
                        :field="'namaproduk'"
                      /> -->
                    </VControl>
                  </VField>
                </div>
                <!-- {{ isLoading }} -->
                <div class="column pt-5 pb-0">
                  <span></span>
                  <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                    <VIconButton
                      type="button"
                      color="success"
                      class="searcv-button"
                      raised
                      icon="fas fa-search"
                      @click="fetchData()"
                      :loading="isLoading"
                    >
                    </VIconButton>
                  </VField>
                </div>
              </div>
            </div>
            <!-- <div class="field">
              <div class="control">
                <div class="columns is-multiline">
                  <div class="column is-11 ">
                    <input type="text" v-model="item.namaPasien" v-on:keyup.enter="fetchData()" class="input"
                      placeholder="Search..." />
                  </div>
                  
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </VCard>
    </div>
  
    <div class="column">
      <VCard>
        <div class="column" v-if="isPlaceLoad">
          <VPlaceloadWrap v-for="data in 25">
            <VPlaceload class="mx-2 mb-3" />
            <VPlaceload class="mx-2" />
          </VPlaceloadWrap>
        </div>
  
        <div class="column p-0" v-else>
          <div class="column c-title">
            <div class="columns">
              <div class="column is-10 pt-0">
                <label class="title-page"><h3 style="font-size: 18px; font-weight: bold; text-transform: uppercase;">Daftar Produk Kadaluarsa</h3></label>
                <!-- <label for="">Form Stok Opname</label> -->
              </div>
            </div>
          </div>
          <div style="display: flex; justify-content: flex-end;">
                  
  
                  <VButton
                    icon="fas fa-plus-square"
                    color="info"
                    :to="{ name: 'module-logistik-form-kadaluarsa' }"
                  >
                    Tambah</VButton>
                </div>
  
          <div class="column">
            
            <DataTable :value="dataSource" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="isLoading"
                              class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                              sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                              paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                              <Column expander style="width: 5rem" />
                              <template #header>
                <div class="flex flex-wrap align-items-center gap-2">
                  <VButton color="success" icon="fas fa-file-excel" raised @click="exportExcel()">
                    Export
                    to
                    Excel </VButton>
                    <VButton icon="fas fa-file-excel" color="danger" @click="cetakKadarluarsa(item)"> Export to PDF</VButton>
                </div>
                
              </template>
              <Column field="no" header="No"></Column>
              <Column
                field="namaruangan"
                header="Ruangan"
                :sortable="true"
                style="min-width: 150px"
              ></Column>
              <Column
                field="nodokumen"
                header="No Dokumen"
                :sortable="true"
                style="min-width: 150px"
              ></Column>
              <Column header="Tanggal Input" :sortable="true" style="min-width: 80px">
                <template #body="slotProps">
                  <span>{{ H.formatDateToLocalString(slotProps.data.tglinput) }}</span>
                </template>
              </Column>
               <!-- Template for the expandable rows -->
      <template #expansion="slotProps">
        <div class="p-3">
          <DataTable 
            :value="slotProps.data.details" 
            :rows="10" 
            showGridlines
            class="p-datatable-sm" 
            responsiveLayout="stack" 
            breakpoint="960px"
            sortMode="multiple">
            <Column field="kdproduk" header="Kode Produk" />
            <Column field="namaproduk" header="Nama Produk" />
            <Column field="satuanstandar" header="Satuan" />
            <Column header="Tanggal Kadaluarsa" :sortable="true" style="min-width: 80px">
                <template #body="slotProps">
                  <span>{{ H.formatDateToLocalString(slotProps.data.tglkadaluarsa) }}</span>
                </template>
              </Column>
            <Column field="harganetto1" header="Harga" />
            <Column field="qtyproduk" header="Qty" />
  
            <!-- <Column
                field="nobatch"
                header="No Batch"
              /> -->
  
            
          </DataTable>
        </div>
      </template>
            </DataTable>
          </div>
          
        </div>
      </VCard>
  
    </div>
  </template>
  
  <script setup lang="ts">
  import { useRoute, useRouter } from 'vue-router'
  import { ref, computed, watch, reactive } from 'vue'
  import DataTable from 'primevue/datatable'
  import Column from 'primevue/column'
  import * as XLSX from 'xlsx';
  import AutoComplete from 'primevue/autocomplete'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import * as H from '/@src/utils/appHelper'
  import { formatRp } from '/@src/utils/appHelper'
  import { useApi } from '/@src/composable/useApi'
  import { useUserSession } from '/@src/stores/userSession'
  import moment from 'moment'
  import { useHead } from '@vueuse/head'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import Calendar from 'primevue/calendar'
  useHead({
    title: 'Daftar Barang Kadaluarsa - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(false)
  
  const modalFilter: any = ref(false)
  const themeColors = useThemeColors()
  const userLogin = useUserSession().getUser()
  const total = ref(0)
  const router = useRouter()
  const expandedRows = ref();
  const modalInput = ref(false)
  const item: any = ref({
    qFilterTgl: {
      start: new Date(),
      end: new Date(),
    },
  })
  
  const currentPage: any = ref({
    limit: 5,
    rows: 50,
  })
  let dataSource: any = ref([])
  let dataSourcePulang: any = ref([])
  let dataHutang: any = ref([])
  let ds_Barang: any = ref([])
  let d_Ruangan: any = ref([])
  let d_barang: any = ref([])
  let isLoading: any = ref(false)
  let isPlaceLoad: any = ref(false)
  const filters = ref('')
  
  const fetchData = async () => {
    isPlaceLoad.value = true
    isLoading.value = true
    let tglAwal = '?tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
    // let ruanganfk = item.value.ruanganfk ? `&ruanganId=${item.value.ruanganfk.value}` : ''
    // let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
    let produk = item.value.barangfk
      ? `&produk=${encodeURIComponent(item.value.barangfk.productname)}`
      : ''
    let ruangan = item.value.ruanganfk
      ? `&ruangan=${encodeURIComponent(item.value.ruanganfk.label)}`
      : ''
    // console.log(ruangan)
    await useApi()
      .get(
        `dashboard/logistik/get-barang-kadaluarsa${tglAwal}${tglAkhir}${produk}${ruangan}`
      )
      .then((response: any) => {
        response.data.forEach((element: any, i: any) => {
          element.no = i + 1
        })
        isPlaceLoad.value = false
        dataSource.value = response.data
      })
    isLoading.value = false
  }
  const cetakKadarluarsa = (e: any) => {
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
    H.printBlade(`logistik/cetak-kadaluarsa?${tglAwal}${tglAkhir}`)
  }
  const fetchRuangan = async (filter: any) => {
    await useApi()
      .get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
      )
      .then((response) => {
        d_Ruangan.value = response
      })
  }
  
  const fetchbarang = async (filter: any) => {
    console.log('🚀 ~ fetchbarang ~ filter:', item.value.ruanganfk)
    // consol
    const ruangantemporary = item.value.ruanganfk
      ? `&ruanganfk=${item.value.ruanganfk.value}`
      : ''
    await useApi()
      .get(`/farmasi/dropdown-obat?namaproduk=${filter.query}${ruangantemporary}&limit=10`)
      .then((response) => {
        d_barang.value = response
        console.log(response)
      })
  }
  
  const exportExcel = () => {
    // Array untuk menampung data yang akan diekspor
    let dataToExport = [];
  
    // Loop melalui setiap item di dataSource
    dataSource.value.forEach(item => {
      // Jika item memiliki lebih dari satu detail, buat baris baru untuk setiap detail
      item.details.forEach(detail => {
        dataToExport.push({
          'No Dokumeen': item.nodokumen,
          'Ruang Pengirim': item.namaruangan,
          'Nama Produk': detail.namaproduk,    // Setiap produk di baris terpisah
          'Katalog': detail.kdproduk,          // Setiap katalog di baris terpisah
          'Satuan': detail.satuanstandar,      // Setiap satuan di baris terpisah
          'No Batch': detail.nobatch,          // Setiap no batch di baris terpisah
          'Qty Produk': detail.qtyproduk,      // Setiap qty produk di baris terpisah
          'Harga': detail.harganetto1,         // Setiap harga di baris terpisah
          'Total Harga': detail.totalharga,      // Setiap qty produk di baris terpisah
          'Tanggal Kadaluarsa': moment(detail.tglkadaluarsa).format('DD-MM-YYYY')  // Setiap tanggal kadaluarsa di baris terpisah
        });
      });
    });
  
    // Membuat worksheet dari data
    const ws = XLSX.utils.json_to_sheet(dataToExport);
  
    // Mengatur lebar kolom
    const colWidths = dataToExport.reduce((widths, row) => {
      Object.keys(row).forEach((key, i) => {
        const value = row[key] ? row[key].toString() : '';
        widths[i] = Math.max(widths[i] || 10, value.length + 2); // Tambahkan padding
      });
      return widths;
    }, []);
    ws['!cols'] = colWidths.map(w => ({ wch: w }));
  
    // Buat workbook dan tambahkan worksheet
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Laporan Kadaluarsa");
  
    // Simpan file Excel dengan nama yang sesuai
    XLSX.writeFile(wb, `Laporan_Kadaluarsa_${moment().format('DD-MM-YYYY')}.xlsx`);
  };
  
  
  fetchData()
  </script>
  
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  @import '/@src/scss/module/sysadmin/master-data.scss';
  </style>
  