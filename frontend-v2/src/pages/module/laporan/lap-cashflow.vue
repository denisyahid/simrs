<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">CASH FLOW STATEMENT</label>
      </div>
      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField label="Periode">
              <VControl class="prime-auto">
                <Calendar
                  inputId="range"
                  v-model="item.qBulan"
                  selectionMode="range"
                  :manualInput="false"
                  class="w-100 mb-4 is-rounded"
                  :showIcon="true"
                  date-format="yy-mm-dd"
                />
              </VControl>
            </VField>
          </div>
          <div class="column mt-5">
            <VButton
              type="button"
              icon="feather:search"
              color="primary"
              raised
              :loading="isLoading"
              @click="fetchData()"
            >
              Cari
            </VButton>
            <VButton
              type="button"
              icon="feather:send"
              color="info"
              raised
              :loading="isLoading2"
              @click="kirimData()"
              class="ml-2"
            >
              Kirim Data
            </VButton>
          </div>

          <div class="column is-12">
            <div class="columns is-multiline">
              <br />
              <div class="column is-4">
                <Card>
                  <template #title>Rekap Arus Kas</template>

                  <template #content>
                    <!-- <p class="subtitle" style="font-size: 14px;"> Periode : {{
                                    H.formatMonthOnly(arrGroup.bulan) }} -> TOTAL : <b style="float: right;"> {{
                                    H.formatRupiah(arrGroup.jumlah,
                                        'Rp.') }}</b></p> -->
                    <span class="subtitle" style="font-size: 14px">
                      <b>AKTIVITAS OPERASI</b></span
                    >
                    <br />

                    <span class="subtitle" style="font-size: 14px">
                      <b>Arus Masuk : </b></span
                    >
                    <br />

                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      a. Penerimaan Usaha dari Jasa Layanan
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.PenerimaanJasaLayanan, 'Rp.') }}</span
                      >
                    </span>
                    <br />

                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      b. Penerimaan APBN/RM diluar gaji pegawai dan belanja modal<span
                        style="float: right"
                      >
                        {{ H.formatRupiah(arrGroup.PenerimaanAPBNLuar, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      c. Penerimaan APBN/RM untuk Gaji Pegawai
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.PenerimaanAPBN, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      d. Penerimaan Hibah
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.PenerimaanHibah, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      e. Penerimaan Usaha Lainnya
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.penerimaanUsaha, 'Rp.') }}</span
                      >
                    </span>
                    <br />

                    <p class="subtitle" style="font-size: 14px"></p>

                    <span class="subtitle" style="font-size: 14px">
                      <b>Arus Keluar : </b></span
                    >
                    <br />

                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      a. Pembayaran Pegawai
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pembayaranPegawai, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      b. Pembayaran Barang Menghasilkan Persediaan
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pembayaranBarangPer, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="
                        font-size: 14px;
                        margin-bottom: -1px;
                        margin-left: 30px !important;
                      "
                    >
                      (1) Pengeluaran Farmasi
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pengeluaranFarmasi, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="
                        font-size: 14px;
                        margin-bottom: -1px;
                        margin-left: 30px !important;
                      "
                    >
                      (2) Pengeluaran Non Farmasi
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pengeluaranNonFarmasi, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      c. Pembayaran Jasa
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pembayaranJasa, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      d. Pembayaran Barang
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pembayaranBarang, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      e. Pembayaran Pemeliharaan
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pengJasa + arrGroup.pengUT, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="
                        font-size: 14px;
                        margin-bottom: -1px;
                        margin-left: 30px !important;
                      "
                    >
                      (1) Pengeluaran Jasa & Pemeliharaan
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pengJasa, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="
                        font-size: 14px;
                        margin-bottom: -1px;
                        margin-left: 30px !important;
                      "
                    >
                      (2) Pengeluaran Utilities & Others
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pengUT, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      f. Pembayaran Perjalanan Dinas
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pembayaranDinas, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      g. Pembayaran Barang dan Jasa Kekhususnan BLU
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pembayaranBLU, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      h. Penyetoran PNBP ke Kas Negara
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.penyetoranPNBP, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <p class="subtitle" style="font-size: 14px"></p>

                    <span class="subtitle" style="font-size: 14px">
                      <b>KAS DIPEROLEH DARI AKTIVITAS OPERASI : </b>
                      <span style="float: right">
                        {{
                          H.formatRupiah(
                            arrGroup.PenerimaanJasaLayanan +
                              arrGroup.PenerimaanAPBN +
                              arrGroup.PenerimaanAPBNLuar +
                              arrGroup.PenerimaanHibah +
                              arrGroup.penerimaanUsaha +
                              arrGroup.pembayaranPegawai +
                              arrGroup.pembayaranBarang +
                              arrGroup.pengeluaranFarmasi +
                              arrGroup.pengeluaranNonFarmasi +
                              arrGroup.pembayaranJasa +
                              arrGroup.pembayaranBarangPer +
                              arrGroup.pembayaranPem +
                              arrGroup.pengJasa +
                              arrGroup.pengUT +
                              arrGroup.pembayaranDinas +
                              arrGroup.pembayaranBLU +
                              arrGroup.penyetoranPNBP,
                            'Rp.'
                          )
                        }}</span
                      ></span
                    >
                    <br />

                    <p class="subtitle" style="font-size: 14px"></p>
                    <span class="subtitle" style="font-size: 14px">
                      <b>AKTIVITAS INVESTASI</b></span
                    >
                    <br />

                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      a. Penjualan atas Aset Tetap
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.penjualanAT, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      b. Perolehan atas Aset Tetap
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.perolehanAT, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      c. Penerimaan APBN/RM untuk Belanja Modal
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.penerimaanAPBNModal, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <p class="subtitle" style="font-size: 14px"></p>

                    <span class="subtitle" style="font-size: 14px">
                      <b>KAS DIPEROLEH DARI AKTIVITAS INVESTASI : </b>
                      <span style="float: right">
                        {{
                          H.formatRupiah(
                            arrGroup.penjualanAT + arrGroup.perolehanAT + arrGroup.penerimaanAPBNModal,
                            'Rp.'
                          )
                        }}</span
                      ></span
                    >
                    <br />

                    <p class="subtitle" style="font-size: 14px"></p>
                    <span class="subtitle" style="font-size: 14px">
                      <b>AKTIVITAS PENDANAAN</b></span
                    >
                    <br />

                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      a. Penerimaan Perhitungan Pihak Ketiga
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.penerimaanKetiga, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      b. Pengeluaran Perhitungan Pihak Ketiga
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pengeluaranKetiga, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <span
                      class="subtitle ml-3"
                      style="font-size: 14px; margin-bottom: -1px"
                    >
                      c. Penerimaan (Pembayaran) lain-lain
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.pembayaranLain, 'Rp.') }}</span
                      >
                    </span>
                    <br />
                    <p class="subtitle" style="font-size: 14px"></p>

                    <span class="subtitle" style="font-size: 14px">
                      <b>KAS DIPEROLEH DARI AKTIVITAS PENDANAAN : </b>
                      <span style="float: right">
                        {{
                          H.formatRupiah(
                            arrGroup.penerimaanKetiga +
                              arrGroup.pengeluaranKetiga +
                              arrGroup.pembayaranLain,
                            'Rp.'
                          )
                        }}</span
                      ></span
                    >
                    <br />

                    <p class="subtitle" style="font-size: 14px"></p>
                    <span class="subtitle" style="font-size: 14px">
                      <b>KENAIKAN BERSIH KAS DAN SETARA KAS : </b>
                      <span style="float: right">
                        {{
                          H.formatRupiah(
                            arrGroup.PenerimaanJasaLayanan +
                              arrGroup.PenerimaanAPBN +
                              arrGroup.PenerimaanAPBNLuar +
                              arrGroup.PenerimaanHibah +
                              arrGroup.penerimaanUsaha +
                              arrGroup.pembayaranPegawai +
                              arrGroup.pembayaranBarang +
                              arrGroup.pengeluaranFarmasi +
                              arrGroup.pengeluaranNonFarmasi +
                              arrGroup.pembayaranJasa +
                              arrGroup.pembayaranBarangPer +
                              arrGroup.pembayaranPem +
                              arrGroup.pengJasa +
                              arrGroup.pengUT +
                              arrGroup.pembayaranDinas +
                              arrGroup.pembayaranBLU +
                              arrGroup.penyetoranPNBP +
                              arrGroup.penjualanAT +
                              arrGroup.perolehanAT +
                              arrGroup.penerimaanAPBNModal +
                              arrGroup.penerimaanKetiga +
                              arrGroup.pengeluaranKetiga +
                              arrGroup.pembayaranLain,
                            'Rp.'
                          )
                        }}</span
                      ></span
                    >
                    <br />

                    <p class="subtitle" style="font-size: 14px"></p>

                    <span class="subtitle" style="font-size: 14px">
                      <b>Kas dan setara kas awal bulan: </b>
                      <span style="float: right">
                        {{ H.formatRupiah(arrGroup.kasAwal, 'Rp.') }}</span
                      ></span
                    >
                    <br />

                    <p class="subtitle" style="font-size: 14px"></p>
                    <span class="subtitle" style="font-size: 14px">
                      <b>KAS DAN SETARA KAS AKHIR BULAN : </b>
                      <span style="float: right">
                        {{
                          H.formatRupiah(
                            arrGroup.PenerimaanJasaLayanan +
                              arrGroup.PenerimaanAPBN +
                              arrGroup.PenerimaanAPBNLuar +
                              arrGroup.PenerimaanHibah +
                              arrGroup.penerimaanUsaha +
                              arrGroup.pembayaranPegawai +
                              arrGroup.pembayaranBarang +
                              arrGroup.pengeluaranFarmasi +
                              arrGroup.pengeluaranNonFarmasi +
                              arrGroup.pembayaranJasa +
                              arrGroup.pembayaranBarangPer +
                              arrGroup.pembayaranPem +
                              arrGroup.pengJasa +
                              arrGroup.pengUT +
                              arrGroup.pembayaranDinas +
                              arrGroup.pembayaranBLU +
                              arrGroup.penyetoranPNBP +
                              arrGroup.penjualanAT +
                              arrGroup.perolehanAT +
                              arrGroup.penerimaanAPBNModal +
                              arrGroup.penerimaanKetiga +
                              arrGroup.pengeluaranKetiga +
                              arrGroup.pembayaranLain +
                              arrGroup.kasAwal,
                            'Rp.'
                          )
                        }}</span
                      ></span
                    >
                    <br />
                  </template>
                </Card>
              </div>
              <div class="column is-8">
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <DataTable
                        v-model:filters="filters"
                        :value="dataPasien"
                        paginator
                        :rows="10"
                        dataKey="no"
                        filterDisplay="row"
                        :globalFilterFields="[
                          'notransaksi',
                          'namaproduk',
                          'ketlainya',
                          'jenis',
                        ]"
                        :class="`p-datatable-small`"
                        showGridlines
                        stripedRows
                        :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                      >
                        <template #header>
                          <div class="flex justify-content-between">
                            <span class="p-input-icon-left">
                              <InputText
                                v-model="filters['global'].value"
                                placeholder="Cari Data"
                              />
                            </span>
                            <VButton
                              type="button"
                              icon="pi pi-file-excel"
                              class="mr-3"
                              v-tooltip-prime="'Export'"
                              @click="exportExcel(dataPasien, 'LapKunjungan')"
                              color="primary"
                            >
                              Export Excel
                            </VButton>
                          </div>
                          <div
                            class="flex flex-wrap align-items-center justify-content-between gap-2"
                          ></div>
                        </template>

                        <template #empty style="text-align: center">
                          No data found.
                        </template>
                        <Column
                          field="no"
                          header="No"
                          style="width: 50px; text-align: center"
                        />
                        <Column field="tanggal" header="Tgl Transaksi" />
                        <Column field="transaksi" header="Jenis Transaksi" />
                        <Column field="keterangan" header="Deskripsi" />
                        <Column
                          field="total"
                          header="Nominal"
                          style="text-align: right; width: 150px"
                        >
                          <template #body="slotProps">
                            {{ H.formatRupiah(slotProps.data.total, 'Rp.') }}
                          </template>
                        </Column>
                      </DataTable>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
import Dropdown from 'primevue/dropdown'
import ColumnGroup from 'primevue/columngroup' // optional
import Row from 'primevue/row'
import * as XLSX from 'xlsx'
import Card from 'primevue/card'
import { FilterMatchMode } from 'primevue/api'
import InputText from 'primevue/inputtext'
import Fieldset from 'primevue/fieldset'
useHead({
  title: 'Rekap Arus Kas - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const arrGroup: any = ref([
  {
    bulan: new Date(),
    PenerimaanJasaLayanan: 0,
    PenerimaanAPBN: 0,
    PenerimaanAPBNLuar: 0,
    PenerimaanHibah: 0,
    penerimaanUsaha: 0,
    pembayaranPegawai: 0,
    pembayaranBarangPer: 0,
    pengeluaranFarmasi: 0,
    pengeluaranNonFarmasi: 0,
    pembayaranJasa: 0,
    pembayaranBarang: 0,
    pembayaranPem: 0,
    pengJasa: 0,
    pengUT: 0,
    pembayaranDinas: 0,
    pembayaranBLU: 0,
    penyetoranPNBP: 0,
    penjualanAT: 0,
    perolehanAT: 0,
    penerimaanAPBNModal: 0,
    penerimaanKetiga: 0,
    pengeluaranKetiga: 0,
    pembayaranLain: 0,
    jumlah: 0,
  },
])
const item: any = ref({
  qBulan: [new Date(), new Date()],
})
const isLoading2: any = ref(false)
const dataKunjungan: any = ref([])
const dataPasien: any = ref([])
const isLoading = ref(false)

const fetchData = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], 'YYYY-MM-DD 00:00:00')
  let tglAkhir = H.formatDate(
    item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0],
    'YYYY-MM-DD 23:59:59'
  )

  isLoading.value = true

  arrGroup.value = {
    'bulan': new Date(),
    PenerimaanJasaLayanan: 0,
    PenerimaanAPBN: 0,
    PenerimaanAPBNLuar: 0,
    PenerimaanHibah: 0,
    penerimaanUsaha: 0,
    pembayaranPegawai: 0,
    pembayaranBarangPer: 0,
    pengeluaranFarmasi: 0,
    pengeluaranNonFarmasi: 0,
    pembayaranJasa: 0,
    pembayaranBarang: 0,
    pembayaranPem: 0,
    pengJasa: 0,
    pengUT: 0,
    pembayaranDinas: 0,
    pembayaranBLU: 0,
    penyetoranPNBP: 0,
    penjualanAT: 0,
    perolehanAT: 0,
    penerimaanAPBNModal: 0,
    penerimaanKetiga: 0,
    pengeluaranKetiga: 0,
    pembayaranLain: 0,
    jumlah: 0,
    kasAwal : 0,
  }

  await useApi()
    .get(`mkko/lap-cashflow?dari=${tglAwal}&sampai=${tglAkhir}`)
    .then((response) => {
      response.detail.forEach((element: any, i: any) => {
        element.no = i + 1
        // element.Reg_umum_rajal = parseFloat(element.Reg_umum_rajal).toFixed(2)
      });

      isLoading.value = false
      // dataKunjungan.value = response.data
      dataPasien.value = response.detail

      // if (response.data.length) arrGroup.value = response.data //groupMonth(  dataKunjungan.value )
      if (response.data.length)
      groupMonth(response.data)
    })
}
const groupMonth = (result: any) => {

for (let i = 0; i < result.length; i++) {
  arrGroup.value.PenerimaanJasaLayanan = arrGroup.value.PenerimaanJasaLayanan + result[i].PenerimaanJasaLayanan
  arrGroup.value.PenerimaanAPBN = arrGroup.value.PenerimaanAPBN + result[i].PenerimaanAPBN
  arrGroup.value.PenerimaanAPBNLuar = arrGroup.value.PenerimaanAPBNLuar + result[i].PenerimaanAPBNLuar
  arrGroup.value.PenerimaanHibah = arrGroup.value.PenerimaanHibah + result[i].PenerimaanHibah
  arrGroup.value.penerimaanUsaha = arrGroup.value.penerimaanUsaha + result[i].penerimaanUsaha
  arrGroup.value.pembayaranPegawai = arrGroup.value.pembayaranPegawai + result[i].pembayaranPegawai
  arrGroup.value.pembayaranBarangPer = arrGroup.value.pembayaranBarangPer + result[i].pembayaranBarangPer
  arrGroup.value.pengeluaranFarmasi = arrGroup.value.pengeluaranFarmasi + result[i].pengeluaranFarmasi
  arrGroup.value.pengeluaranNonFarmasi = arrGroup.value.pengeluaranNonFarmasi + result[i].pengeluaranNonFarmasi
  arrGroup.value.pembayaranJasa = arrGroup.value.pembayaranJasa + result[i].pembayaranJasa
  // arrGroup.value.pembayaranPem = arrGroup.value.pembayaranPem + result[i].pembayaranPem
  arrGroup.value.pengJasa = arrGroup.value.pengJasa + result[i].pengJasa
  arrGroup.value.pengUT = arrGroup.value.pengUT + result[i].pengUT
  arrGroup.value.pembayaranDinas = arrGroup.value.pembayaranDinas + result[i].pembayaranDinas
  arrGroup.value.pembayaranPem = arrGroup.value.pembayaranPem + result[i].pembayaranPem

  arrGroup.value.penyetoranPNBP = arrGroup.value.penyetoranPNBP + result[i].penyetoranPNBP
  arrGroup.value.penjualanAT = arrGroup.value.penjualanAT + result[i].penjualanAT

  arrGroup.value.perolehanAT = arrGroup.value.perolehanAT + result[i].perolehanAT
  arrGroup.value.penerimaanAPBNModal = arrGroup.value.penerimaanAPBNModal + result[i].penerimaanAPBNModal

  arrGroup.value.penerimaanKetiga = arrGroup.value.penerimaanKetiga + result[i].penerimaanKetiga
  arrGroup.value.pengeluaranKetiga = arrGroup.value.pengeluaranKetiga + result[i].pengeluaranKetiga

  arrGroup.value.penyetoranPNBP = arrGroup.value.penyetoranPNBP + result[i].penyetoranPNBP
  arrGroup.value.pembayaranLain = arrGroup.value.pembayaranLain + result[i].pembayaranLain
  arrGroup.value.kasAwal = arrGroup.value.kasAwal + result[i].kasAwal

}

}
// const groupMonth = (result: any) => {
//   let sama = false
//   let arrGroup: any = []
//   for (let i = 0; i < result.length; i++) {
//     sama = false
//     for (let x = 0; x < arrGroup.length; x++) {
//       if (arrGroup[x].bulan.substr(0, 7) == result[i].bulan.substr(0, 7)) {
//         arrGroup[x].PenerimaanJasaLayanan =
//           arrGroup[x].PenerimaanJasaLayanan + result[i].PenerimaanJasaLayanan
//         arrGroup[x].PenerimaanAPBN = arrGroup[x].PenerimaanAPBN + result[i].PenerimaanAPBN
//         arrGroup[x].PenerimaanAPBNLuar =
//           arrGroup[x].PenerimaanAPBNLuar + result[i].PenerimaanAPBNLuar
//         arrGroup[x].PenerimaanHibah =
//           arrGroup[x].PenerimaanHibah + result[i].PenerimaanHibah
//         arrGroup[x].penerimaanUsaha =
//           arrGroup[x].penerimaanUsaha + result[i].penerimaanUsaha
//         arrGroup[x].pembayaranPegawai =
//           arrGroup[x].pembayaranPegawai + result[i].pembayaranPegawai
//         arrGroup[x].pembayaranBarangPer =
//           arrGroup[x].pembayaranBarangPer + result[i].pembayaranBarangPer
//         arrGroup[x].pengeluaranFarmasi =
//           arrGroup[x].pengeluaranFarmasi + result[i].pengeluaranFarmasi
//         arrGroup[x].pengeluaranNonFarmasi =
//           arrGroup[x].pengeluaranNonFarmasi + result[i].pengeluaranNonFarmasi
//         arrGroup[x].pembayaranJasa = arrGroup[x].pembayaranJasa + result[i].pembayaranJasa
//         arrGroup[x].pembayaranBarang =
//           arrGroup[x].pembayaranBarang + result[i].pembayaranBarang
//         arrGroup[x].pembayaranPem = arrGroup[x].pembayaranPem + result[i].pembayaranPem
//         arrGroup[x].pengJasa = arrGroup[x].pengJasa + result[i].pengJasa
//         arrGroup[x].pengUT = arrGroup[x].pengUT + result[i].pengUT
//         arrGroup[x].pembayaranDinas =
//           arrGroup[x].pembayaranDinas + result[i].pembayaranDinas
//         arrGroup[x].pembayaranPem = arrGroup[x].pembayaranPem + result[i].pembayaranPem
//         arrGroup[x].penyetoranPNBP = arrGroup[x].penyetoranPNBP + result[i].penyetoranPNBP

//         arrGroup[x].penjualanAT = arrGroup[x].penjualanAT + result[i].penjualanAT
//         arrGroup[x].perolehanAT = arrGroup[x].perolehanAT + result[i].perolehanAT
//         arrGroup[x].penerimaanAPBNModal =
//           arrGroup[x].penerimaanAPBNModal + result[i].penerimaanAPBNModal
//         arrGroup[x].penerimaanKetiga =
//           arrGroup[x].penerimaanKetiga + result[i].penerimaanKetiga
//         arrGroup[x].pengeluaranKetiga =
//           arrGroup[x].pengeluaranKetiga + result[i].pengeluaranKetiga
//         arrGroup[x].pembayaranLain = arrGroup[x].pembayaranLain + result[i].pembayaranLain
//         arrGroup[x].kasAwal = arrGroup[x].kasAwal + result[i].kasAwal
//       }
//     }
//     if (sama == false) {
//       arrGroup.push(result[i])
//     }
//   }

//   return arrGroup
// }

const kirimData = async () => {
  isLoading2.value = true
  let tglAwal = H.formatDate(item.value.qBulan[0], 'YYYY-MM-DD 00:00:00')
  let tglAkhir = H.formatDate(
    item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0],
    'YYYY-MM-DD 23:59:59'
  )
  let response = await useApi().get(`mkko/lap-cashflow?dari=${tglAwal}&sampai=${tglAkhir}&send=true`)
  if(response.status == 200){
    H.alert('success','Sukses')
  }else{
    H.alert('error',response.result)
  }
  isLoading2.value = false
}

const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}
fetchData()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.p-card .p-card-title {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
  margin-top: 14px;
}

// .title-page {
//   font-weight: 600;
//   font-size: 18px;
// }

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
  text-align: center !important;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top;
}

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.tile-grid-v2 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;
    cursor: pointer;

    &:hover,
    &:focus {
      border-color: var(--primary);
      box-shadow: var(--light-box-shadow);
    }

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      > img {
        display: block;
        width: 200px;
        height: 200px;
        min-width: 200px;
      }

      .meta {
        margin-left: 10px;
        line-height: 1.4;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1rem;
          }

          &:nth-child(2) {
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.8rem;
              font-weight: 400;
            }

            .icon-separator {
              position: relative;
              font-size: 4px;
              color: var(--light-text);
              padding: 0 6px;
            }
          }
        }
      }

      .dropdown {
        margin-left: auto;
      }
    }
  }
}
</style>
