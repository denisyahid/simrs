<template>
    <div class="page-content-inner">
      <div class="is-navbar">
        <div class="form-layout">
          <div class="form-outer">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
              <div class="form-header-inner">
                <div class="left">
                  <h3>Laporan Narkotik</h3>
                </div>
                <div class="right">
                  <div class="buttons">
                    <VButton
                      icon="lnir lnir-arrow-left rem-100"
                      :to="{ name: 'module-registrasi-daftar-pembatalan-pasien' }"
                      light
                      dark-outlined
                    >
                      Cancel
                    </VButton>
                    <VButton
                      type="button"
                      icon="feather:search"
                      :loading="isLoading"
                      color="success"
                      raised
                      @click="cariRiwayat()"
                    >
                      Cari
                    </VButton>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-body">
              <!--Fieldset-->
              <div class="form-fieldset">
                <div class="fieldset-heading">
                  <h1 style="font-weight: bold; margin-bottom: 1rem">Filter Pencarian</h1>
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <div class="columns is-multiline mb-3">
                          <div class="column is-4">
                            <label style="margin-bottom: 2rem">Bulan </label>
                            <VField>
                              <div
                                class="card flex justify-content-center"
                                style="box-shadow: unset !important"
                              >
                                <Dropdown
                                  v-model="selectmonth"
                                  :options="month"
                                  optionLabel="name"
                                  placeholder="Pilih bulan"
                                  class="w-full"
                                />
                              </div>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <label style="margin-bottom: 2rem">Tahun </label>
                            <VField>
                              <div
                                class="card flex justify-content-center"
                                style="box-shadow: unset !important"
                              >
                                <Dropdown
                                  v-model="selectyear"
                                  :options="year"
                                  optionLabel="name"
                                  placeholder="Pilih tahun"
                                  class="w-full md:w-14rem"
                                />
                              </div>
                            </VField>
                          </div>
                          <!-- <div class="column is-4">
                            <label>Produk</label>
                            <VField>
                              <VControl class="prime-auto">
                                <AutoComplete
                                  v-model="input.produk"
                                  :suggestions="d_Produk"
                                  :optionLabel="'namaproduk'"
                                  @complete="fetchProduk($event)"
                                  :dropdown="true"
                                  :minLength="3"
                                  :appendTo="'body'"
                                  :loadingIcon="'pi pi-spinner'"
                                  :field="'namaproduk'"
                                  placeholder="Produk..."
                                  class=""
                                />
                              </VControl>
                            </VField>
                          </div> -->
                        </div>
                      </div>
  
                      <div class="column is-12 mt-1">
                        <VButton
                          color="warning"
                          class="mr-4 mb-3"
                          icon="fas fa-file-excel"
                          raised
                          @click="exportExcel()"
                        >
                          Export to Excel
                        </VButton>
                        <!--       <VButton color="primary" class="mr-4 mb-3" icon="fas fa-file-pdf" raised 
                                                  @click="cetakLaporanPasienDaftar(item)"
                                                  > Export to PDF </VButton> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--Fieldset-->
              <div class="form-fieldset">
                <div class="column" v-if="isLoading">
                  <VPlaceloadWrap v-for="data in 10">
                    <VPlaceload class="mx-2 mb-3" />
                  </VPlaceloadWrap>
                </div>
                <div class="column" v-else>
                  <VPlaceholderPage
                    v-if="d_Pendaftaran.length == 0"
                    title="Data Tidak di Temukan."
                    subtitle="Silakan gunakan filter lain"
                    larger
                  >
                    <template #image>
                      <img
                        class="light-image"
                        src="/@src/assets/illustrations/placeholders/search-1.svg"
                        alt=""
                      />
                      <img
                        class="dark-image"
                        src="/@src/assets/illustrations/placeholders/search-1-dark.svg"
                        alt=""
                      />
                    </template>
                  </VPlaceholderPage>
                  <div v-else>
                    <div class="column mt-5">
                      <div class="table-scroll">
                        <DataTable
                          :value="d_Pendaftaran"
                          :paginator="true"
                          :rows="20"
                          :rowsPerPageOptions="[5, 10, 25]"
                          class="p-datatable-customers p-datatable-sm"
                          filterDisplay="menu"
                          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                          responsiveLayout="stack"
                          breakpoint="960px"
                          sortMode="multiple"
                          showGridlines
                          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                          :loading="isLoading"
                        >
                          <Column field="no" header="No" style="min-width: 50px" />
                          <Column
                            field="namaproduk"
                            header="Nama Produk"
                            style="min-width: 300px"
                          />
                          <Column
                            field="saldoawal"
                            header="Saldo Awal"
                            style="min-width: 50px"
                          />
                          <Column
                            field="qtyterima"
                            header="Qty Masuk"
                            style="min-width: 50px"
                          />
                          <Column
                            field="qtykeluar"
                            header="Qty Keluar"
                            style="min-width: 50px"
                          />
                          <Column
                            field="saldoakhir"
                            header="Saldo Akhr"
                            style="min-width: 50px"
                          />
                        </DataTable>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import AutoComplete from 'primevue/autocomplete'
  import DataTable from 'primevue/datatable'
  import Dropdown from 'primevue/dropdown'
  import { useToaster } from '/@src/composable/toaster'
  import Column from 'primevue/column'
  import moment from 'moment'
  const input: any = ref({})
  const d_Departement: any = ref([])
  const d_Ruangan: any = ref([])
  const d_Produk: any = ref([])
  const d_KelompokPasien: any = ref([])
  let isLoading = ref(false)
  const d_Pendaftaran: any = ref([])
  const dataSourceICD9 = ref([])
  const remakeData: any = ref([])
  import * as XLSX from 'xlsx'
  import * as qzService from '/@src/utils/qzTrayService'
  import axios from 'axios'
  
  const selectmonth = ref()
  const selectyear = ref()
  useHead({
    title: 'Laporan Narkotik - ' + import.meta.env.VITE_PROJECT,
  })
  async function cariRiwayat() {
    // console.log(selectmonth.value, '~~~~~~~~~~')
    if (selectmonth.value == undefined || selectyear.value == undefined) {
      useToaster().info('Tahun dan Bulan Harus diisi')
      return
    }
  
    let object: any = {}
    object = input.value
    isLoading.value = true
    let date = functionAwalDanAkhir(`${selectyear.value.code}-${selectmonth.value.code}`)
    let kelompokpasien = input.value.kelompokpasien ? input.value.kelompokpasien.value : ''
    console.log('🚀 ~ cariRiwayat ~ date:', date)
    let ruangan = input.value.ruangan ? input.value.ruangan.value : ''
    let departement = input.value.departement ? input.value.departement.value : ''
    let produk = input.value.produk ? input.value.produk.namaproduk : ''
    // console.log()
    // console.log(input.value.ksm);
    useApi()
      .get(
        `/pelayanan/laporan-persediaan-narkotik?tglAwal=${`${selectyear.value.code}-${selectmonth.value.code}-01`}&tglAkhir=${
          date.akhir
        }&produk=${produk}`
      )
      .then((response: any) => {
        response.forEach((element: any, i: any) => {
          element.no = i + 1
        })
        d_Pendaftaran.value = response
        console.log(response)
        isLoading.value = false
      })
      .catch((e: any) => {
        isLoading.value = false
      })
  }
  const setAutoFill = () => {
    input.value.tglAwal = new Date()
    input.value.tglAkhir = new Date()
  }
  
  const convertDate = (date) => {
    const dateObject = new Date(date)
    const day = dateObject.getDate().toString().padStart(2, '0')
    const month = (dateObject.getMonth() + 1).toString().padStart(2, '0')
    const year = dateObject.getFullYear()
    const formattedDate = `${year}-${month}-${day}`
    return formattedDate
  }
  const fetchProduk = async (filter: any) => {
    const produk = input.value.produk ? input.value.produk : ''
    await useApi()
      .get(`farmasi/laporan/laporan-narkotik-produk?produk=${produk}`)
      .then((response) => {
        d_Produk.value = response
      })
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
  const fetchKelompokPasien = async (filter: any) => {
    await useApi()
      .get(
        `emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
      )
      .then((response) => {
        d_KelompokPasien.value = response
      })
  }
  
  const exportExcel = () => {
    remakeData.value = d_Pendaftaran.value.map((e: any) => {
      console.log(e)
      return {
        no: e.no,
        namaProduk: e.namaproduk,
        saldoawal: e.saldoawal,
        qtyterima: e.qtyterima,
        qtykeluar: e.qtykeluar,
        saldoakhir: e.saldoakhir,
      }
    })
    // Assume you have already created the worksheet
    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
  
    // Auto-fit columns
    const range = XLSX.utils.decode_range(worksheet['!ref'])
    const cols = worksheet['!cols'] || []
    for (let C = range.s.c; C <= range.e.c; ++C) {
      let maxContentLength = 0
      for (let R = range.s.r; R <= range.e.r; ++R) {
        const cellRef = XLSX.utils.encode_cell({ r: R, c: C })
        const cell = worksheet[cellRef]
        if (cell && cell.v) {
          const cellContentLength = String(cell.v).length
          maxContentLength = Math.max(maxContentLength, cellContentLength)
        }
      }
      cols[C] = { wch: maxContentLength + 5 } // Add some extra width for padding
    }
    worksheet['!cols'] = cols
  
    // Create workbook and export
    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] }
    const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' })
    saveAsExcelFile(excelBuffer, 'products')
  }
  const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE =
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8'
    let EXCEL_EXTENSION = '.xlsx'
    const data: Blob = new Blob([buffer], {
      type: EXCEL_TYPE,
    })
    const _url = window.URL.createObjectURL(data)
    window.open(_url, EXCEL_EXTENSION).focus()
  }
  
  const cetakLaporanPasienDaftar = (item) => {
    // console.log(item)
    var departement = ''
    if (input.value.departement != undefined) {
      departement = input.value.departement.value
    }
  
    var ruangan = ''
    if (input.value.ruangan != undefined) {
      ruangan = input.value.ruangan.value
    }
    var kelompokpasien = ''
    if (input.value.kelompokpasien != undefined) {
      kelompokpasien = input.value.kelompokpasien.value
    }
    let startDate = moment(input.value.tglAwal).format('YYYY-MM-DD')
    let endDate = moment(input.value.tglAkhir).format('YYYY-MM-DD')
    // console.log(input.value.tglAwal);
    // let kelompokpasien = input.value.kelompokpasien ? input.value.kelompokpasien : "";
    ruangan = input.value.ruangan ? input.value.ruangan : ''
    departement = input.value.departement ? input.value.departement : ''
  
    qzService.printData(
      `registrasi/waktu-tunggu-obat?tglAwal=${startDate}&tglAkhir=${endDate}&departement=${departement}&ruangan=${ruangan}`,
      'Persentase Ketepatan Waktu Tunggu Obat Jadi 30 Menit',
      1
    )
  }
  
  qzService.connect()
  // cariRiwayat();
  setAutoFill()
  
  const month = ref([
    { name: 'Januari', code: '01' },
    { name: 'Februari', code: '02' },
    { name: 'Maret', code: '03' },
    { name: 'April', code: '04' },
    { name: 'Mei', code: '05' },
    { name: 'Juni', code: '06' },
    { name: 'Juli', code: '07' },
    { name: 'Agustus', code: '08' },
    { name: 'September', code: '09' },
    { name: 'Oktober', code: '10' },
    { name: 'November', code: '11' },
    { name: 'Desember', code: '12' },
  ])
  const year = ref([
    { name: '2024', code: '2024' },
    { name: '2025', code: '2025' },
    { name: '2026', code: '2026' },
    { name: '2027', code: '2027' },
    { name: '2028', code: '2028' },
    { name: '2029', code: '2029' },
    { name: '2030', code: '2030' },
  ])
  function functionAwalDanAkhir(bulanTahun) {
    const [tahun, bulan] = bulanTahun.split('-')
  
    // Mendapatkan tanggal awal bulan (tanggal 1 di bulan yang dipilih)
    const tanggalAwal = new Date(tahun, bulan - 1, 1)
  
    // Mendapatkan tanggal awal bulan berikutnya, lalu kurangi satu hari
    const tanggalAkhir = new Date(tahun, bulan, 1)
    tanggalAkhir.setDate(tanggalAkhir.getDate())
  
    return {
      awal: tanggalAwal.toISOString().split('T')[0], // Mendapatkan tanggal awal dalam format YYYY-MM-DD
      akhir: tanggalAkhir.toISOString().split('T')[0], // Mendapatkan tanggal akhir dalam format YYYY-MM-DD
    }
  }
  </script>
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  @import '/@src/scss/components/forms-outer';
  
  .form-layout {
    max-width: 1300px;
    margin: 0 auto;
  }
  
  .form-fieldset {
    padding: 10px 0;
    max-width: 100%;
    margin: 0 auto;
  }
  
  .table-pi {
    width: 1400px;
    border: 1px solid #929090;
  }
  
  .table-scroll {
    overflow-x: scroll;
  }
  
  .date {
    background-color: #9b9b9b;
    color: #fff;
  }
  </style>
  