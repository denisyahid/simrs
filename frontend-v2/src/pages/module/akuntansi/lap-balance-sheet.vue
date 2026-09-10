<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">BALANCE SHEET </label>
      </div>
      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField label="Periode">
              <VControl class="prime-auto">
                <Calendar inputId="range" v-model="item.qBulan" selectionMode="range" :manualInput="false"
                  class="w-100 mb-4 is-rounded" :showIcon="true" date-format="yy-mm-dd" />
              </VControl>
            </VField>
          </div>
          <div class="column mt-5">
            <VButton type="button" icon="feather:search" color="primary" raised :loading="isLoading"
              @click="fetchData()">
              Cari
            </VButton>
            <VButton type="button" icon="feather:send" color="info" raised :loading="isLoading2" @click="kirimData()"
              class="ml-2">
              Kirim Data
            </VButton>
          </div>

          <div class="column is-12">
            <div class="columns is-multiline mb-3">
              <br />
              <div class="column is-4">

                <Card>
                  <!-- <Card style="height: 800px;overflow: auto;"> -->

                  <template #title>BALANCE SHEET</template>

                  <template #content>

                    <p class="subtitle" style="font-size: 14px;"> Periode : {{
                  H.formatMonthOnly(arrGroup.bulan) }} <b style="float: right;"> {{ H.formatRupiah(arrGroup.jumlah,
                  'Rp.') }}</b></p>

                    <span class="subtitle" style="font-size: 14px;"> <b>Total kas </b>
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.kasDanSetaraKas + arrGroup.bank
                  + arrGroup.investasiJangkaPendek, 'Rp.') }}</span></span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">a. Kas dan setara kas
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.kasDanSetaraKas, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">b. Bank
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.bank, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">c. Investasi Jangka Pendek
                      (Deposito)
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.investasiJangkaPendek, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">d. Piutang operasional
                      (Piutang Usaha)
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.piutangOperasional, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">e. Piutang Karyawan
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.piutangKaryawan, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">f. Piutang Lain-Lain
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.piutangLainLain, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">g. Persediaan
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.persediaan, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">h. Biaya Dibayar Dimuka
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.biayaDibayarDimuka, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">i. Uang Muka
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.uangMuka, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">j. Uang Muka Pajak
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.uangMukaPajak, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">k. Aset Lancar Lainnya
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.asetLancarLainnya, 'Rp.') }}</span> </span>
                    <br />


                    <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> <b>JUMLAH ASET LANCAR</b>
                      <b style="float: right;">
                        {{ H.formatRupiah(
                  arrGroup.kasDanSetaraKas
                  + arrGroup.bank
                  + arrGroup.investasiJangkaPendek
                  + arrGroup.piutangOperasional
                  + arrGroup.piutangKaryawan
                  + arrGroup.piutangLainLain
                  + arrGroup.persediaan
                  + arrGroup.biayaDibayarDimuka
                  + arrGroup.uangMuka
                  + arrGroup.uangMukaPajak
                  + arrGroup.persediaan
                  + arrGroup.asetLancarLainnya

                  , 'Rp.') }}</b>
                    </p>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">a. Aset Tetap
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.asetTetap, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">b. Aset Pajak tangguhan
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.asetPajakTangguhan, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">c. Aset Lain
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.asetLain, 'Rp.') }}</span> </span>
                    <br />
                    <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> <b>JUMLAH ASET TIDAK
                        LANCAR</b>
                      <b style="float: right;">
                        {{ H.formatRupiah(
                  arrGroup.asetTetap
                  + arrGroup.asetPajakTangguhan
                  + arrGroup.asetLain

                  , 'Rp.') }}</b>
                    </p>
                    <br />
                    <p class="subtitle ml-3 mt-3-min" style="font-size: 14px; margin-bottom: -1px;"> <b>JUMLAH ASET</b>

                      <b style="float: right;">
                        {{ H.formatRupiah(

                  arrGroup.kasDanSetaraKas
                  + arrGroup.bank
                  + arrGroup.investasiJangkaPendek
                  + arrGroup.piutangOperasional
                  + arrGroup.piutangKaryawan
                  + arrGroup.piutangLainLain
                  + arrGroup.persediaan
                  + arrGroup.biayaDibayarDimuka
                  + arrGroup.uangMuka
                  + arrGroup.uangMukaPajak
                  + arrGroup.persediaan
                  + arrGroup.asetLancarLainnya

                  + arrGroup.asetTetap
                  + arrGroup.asetPajakTangguhan
                  + arrGroup.asetLain

                  , 'Rp.') }}</b>
                    </p>
                    <i class=" ml-3 mt-3-min"> (JUMLAH ASET LANCAR + JUMLAH ASET TIDAK )</i>
                    <br />
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">a. Hutang Usaha
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.hutangUsaha, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">b. Hutang Bank
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.hutangBank, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">c. Hutang Jangka Pendek
                      Lainnya
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.hutangJangkaPendekLainnya, 'Rp.') }}</span>
                    </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">d. Pendapatan Diterima
                      Dimuka
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.pendapatanDiterimaDimuka, 'Rp.') }}</span>
                    </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">e. Biaya Yang Masih Harus
                      Dibayar
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.biayaYangMasihHarusDibayar, 'Rp.') }}</span>
                    </span>
                    <br />
                    <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> <b>JUMLAH KEWAJIBAN JANGKA
                        PENDEK</b>

                      <b style="float: right;">
                        {{ H.formatRupiah(

                  arrGroup.hutangUsaha
                  + arrGroup.hutangBank
                  + arrGroup.hutangJangkaPendekLainnya
                  + arrGroup.pendapatanDiterimaDimuka
                  + arrGroup.biayaYangMasihHarusDibayar


                  , 'Rp.') }}</b>
                    </p>
                    <br />
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">a. Kredit SLA Bunga
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.kreditSLABunga, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">b. Kredit RDI Bunga
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.kreditRDIBunga, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">c. Pinjaman MTN
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.pinjamanMTN, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">d. Hutang Jangka Panjang
                      Lainnya
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.hutangJangkaPanjangLainnya, 'Rp.') }}</span>
                    </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">e. Kewajiban lain-lain
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.kewajibanLainLain, 'Rp.') }}</span> </span>
                    <br />
                    <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> <b>JUMLAH KEWAJIBAN JANGKA
                        PANJANG</b>

                      <b style="float: right;">
                        {{ H.formatRupiah(

                  arrGroup.kreditSLABunga
                  + arrGroup.kreditRDIBunga
                  + arrGroup.pinjamanMTN
                  + arrGroup.hutangJangkaPanjangLainnya
                  + arrGroup.kewajibanLainLain


                  , 'Rp.') }}</b>
                    </p>

                    <br />
                    <p class="subtitle ml-3  mt-3-min" style="font-size: 14px; margin-bottom: -1px;"> <b>JUMLAH
                        KEWAJIBAN</b>
                      <b style="float: right;"> {{ H.formatRupiah(

                  arrGroup.kreditSLABunga
                  + arrGroup.kreditRDIBunga
                  + arrGroup.pinjamanMTN
                  + arrGroup.hutangJangkaPanjangLainnya
                  + arrGroup.kewajibanLainLain

                  + arrGroup.hutangUsaha
                  + arrGroup.hutangBank
                  + arrGroup.hutangJangkaPendekLainnya
                  + arrGroup.pendapatanDiterimaDimuka
                  + arrGroup.biayaYangMasihHarusDibayar

                  , 'Rp.') }}</b>
                    </p>
                    <i class=" ml-3 mt-3-min"> (JUMLAH KEWAJIBAN JANGKA PENDEK + JUMLAH KEWAJIBAN JANGKA PANJANG )</i>

                    <br />
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">a. Ekuitas Awal
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.ekuitasAwal, 'Rp.') }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">b. Surplus/defisit tahun
                      periode Berjalan
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.surplusDefisitTahunPeriodeBerjalan, 'Rp.')
                        }}</span> </span>
                    <br />
                    <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">c. Komponen Ekuitas
                      Lainnya
                      <span style="float: right;"> {{ H.formatRupiah(arrGroup.komponenEkuitasLainnya, 'Rp.') }}</span> </span>
                    <br />
                    <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> <b> EKUITAS AKHIR</b>

                      <b style="float: right;"> {{
                  H.formatRupiah(
                    arrGroup.ekuitasAwal
                    + arrGroup.surplusDefisitTahunPeriodeBerjalan
                    + arrGroup.komponenEkuitasLainnya , 'Rp.') }}</b>
                    </p>

                  </template>
                </Card>
              </div>
              <div class="column is-8">
                <div class="column is-12">
                  <div class="columns is-multiline">

                    <div class="column is-12">
                      <DataTable v-model:filters="filters" :value="dataPasien" paginator :rows="10" dataKey="no"
                        filterDisplay="row" :globalFilterFields="['notransaksi', 'namaproduk', 'ketlainya', 'jenis']"
                        :class="`p-datatable-small`" showGridlines stripedRows
                        :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]">

                        <template #header>
                          <div class="flex justify-content-between">
                            <span class="p-input-icon-left">

                              <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                            </span>
                            <VButton type="button" icon="pi pi-file-excel" class="mr-3" v-tooltip-prime="'Export'"
                              @click="exportExcel(dataPasien, 'LapKunjungan')" color="primary">
                              Export Excel
                            </VButton>
                          </div>
                          <div class="flex flex-wrap align-items-center justify-content-between gap-2">

                          </div>
                        </template>

                        <template #empty style="text-align: center;"> No data found.
                        </template>
                        <Column field="no" header="No" style="width: 50px; text-align: center;" />
                        <Column field="tgltransaksi" header="Tgl Transaksi" />
                        <Column field="notransaksi" header="No. Transaksi" />
                        <Column field="namaproduk" header="Deskripsi" />
                        <Column field="ketlainya" header="Keterangan" />
                        <Column field="jenis" header="Jenis" />
                        <!-- <Column field="namaproduk" header="Layanan / Obat" />
                          <Column field="hargasatuan" header="Harga Satuan" style="text-align:right">
                            <template #body="slotProps">
                              {{ H.formatRupiah(slotProps.data.hargasatuan, 'Rp.') }}
                            </template>
                          </Column>
                          <Column field="jumlah" header="Jumlah" style="text-align:center;" /> -->
                        <Column field="beban" header="Total" style="text-align:right;width: 150px; ">

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
import Dropdown from 'primevue/dropdown';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import Card from 'primevue/card';
import { FilterMatchMode } from 'primevue/api'
import InputText from 'primevue/inputtext';
import Fieldset from 'primevue/fieldset';
useHead({
  title: 'Balance Sheet  - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const arrGroup: any = ref([{
  'bulan': new Date(),
  "kasDanSetaraKas": 0,
  "bank": 0,
  "investasiJangkaPendek": 0,
  "piutangOperasional": 0,
  "piutangKaryawan": 0,
  "piutangLainLain": 0,
  "persediaan": 0,
  "biayaDibayarDimuka": 0,
  "uangMuka": 0,
  "uangMukaPajak": 0,
  "asetLancarLainnya": 0,
  "asetTetap": 0,
  "asetPajakTangguhan": 0,
  "asetLain": 0,
  "hutangUsaha": 0,
  "hutangBank": 0,
  "hutangJangkaPendekLainnya": 0,
  "pendapatanDiterimaDimuka": 0,
  "biayaYangMasihHarusDibayar": 0,
  "kreditSLABunga": 0,
  "kreditRDIBunga": 0,
  "pinjamanMTN": 0,
  "hutangJangkaPanjangLainnya": 0,
  "kewajibanLainLain": 0,
  "ekuitasAwal": 0,
  "surplusDefisitTahunPeriodeBerjalan": 0,
  "komponenEkuitasLainnya": 0,
  'jumlah': 0,
}
])
const item: any = ref({
  qBulan: [
    new Date(),
    new Date()
  ],

})
const isLoading2: any = ref(false)
const dataKunjungan: any = ref([])
const dataPasien: any = ref([])
const isLoading = ref(false);


const fetchData = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], "YYYY-MM-DD 00:00:00")
  let tglAkhir = H.formatDate(item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0], "YYYY-MM-DD 23:59:59")

  isLoading.value = true

  arrGroup.value = {
  bulan: new Date(),
  kasDanSetaraKas: 0,
  bank: 0,
  investasiJangkaPendek: 0,
  piutangOperasional: 0,
  piutangKaryawan: 0,
  piutangLainLain: 0,
  persediaan: 0,
  biayaDibayarDimuka: 0,
  uangMuka: 0,
  uangMukaPajak: 0,
  asetLancarLainnya: 0,
  asetTetap: 0,
  asetPajakTangguhan: 0,
  asetLain: 0,
  hutangUsaha: 0,
  hutangBank: 0,
  hutangJangkaPendekLainnya: 0,
  pendapatanDiterimaDimuka: 0,
  biayaYangMasihHarusDibayar: 0,
  kreditSLABunga: 0,
  kreditRDIBunga: 0,
  pinjamanMTN: 0,
  hutangJangkaPanjangLainnya: 0,
  kewajibanLainLain: 0,
  ekuitasAwal: 0,
  surplusDefisitTahunPeriodeBerjalan: 0,
  komponenEkuitasLainnya: 0,
  jumlah: 0,

  }
  await useApi().get(`mkko/lap-balance-sheet?dari=${tglAwal}&sampai=${tglAkhir}`).then((response) => {
    response.detail.forEach((element: any, i: any) => {
      element.no = i + 1
      // element.Reg_umum_rajal = parseFloat(element.Reg_umum_rajal).toFixed(2)
    });


    isLoading.value = false
    // dataKunjungan.value = response.data
    dataPasien.value = response.detail
    if (response.data.length)
      // arrGroup.value = response.data;
    groupMonth(response.data)

  })

}
const groupMonth = (result: any) => {

for (let i = 0; i < result.length; i++) {
  arrGroup.value.kasDanSetaraKas = arrGroup.value.kasDanSetaraKas + result[i].kasDanSetaraKas
  arrGroup.value.bank = arrGroup.value.bank + result[i].bank
  arrGroup.value.investasiJangkaPendek = arrGroup.value.investasiJangkaPendek + result[i].investasiJangkaPendek
  arrGroup.value.piutangOperasional = arrGroup.value.piutangOperasional + result[i].piutangOperasional
  arrGroup.value.piutangKaryawan = arrGroup.value.piutangKaryawan + result[i].piutangKaryawan
  arrGroup.value.piutangLainLain = arrGroup.value.piutangLainLain + result[i].piutangLainLain
  arrGroup.value.persediaan = arrGroup.value.persediaan + result[i].persediaan
  arrGroup.value.biayaDibayarDimuka = arrGroup.value.biayaDibayarDimuka + result[i].biayaDibayarDimuka
  arrGroup.value.uangMuka = arrGroup.value.uangMuka + result[i].uangMuka
  arrGroup.value.uangMukaPajak = arrGroup.value.uangMukaPajak + result[i].uangMukaPajak
  // arrGroup.value.pembayaranPem = arrGroup.value.pembayaranPem + result[i].pembayaranPem
  arrGroup.value.asetLancarLainnya = arrGroup.value.asetLancarLainnya + result[i].asetLancarLainnya
  arrGroup.value.asetTetap = arrGroup.value.asetTetap + result[i].asetTetap
  arrGroup.value.asetPajakTangguhan = arrGroup.value.asetPajakTangguhan + result[i].asetPajakTangguhan
  arrGroup.value.asetLain = arrGroup.value.asetLain + result[i].asetLain

  arrGroup.value.hutangUsaha = arrGroup.value.hutangUsaha + result[i].hutangUsaha
  arrGroup.value.hutangBank = arrGroup.value.hutangBank + result[i].hutangBank

  arrGroup.value.hutangJangkaPendekLainnya = arrGroup.value.hutangJangkaPendekLainnya + result[i].hutangJangkaPendekLainnya
  arrGroup.value.pendapatanDiterimaDimuka = arrGroup.value.pendapatanDiterimaDimuka + result[i].pendapatanDiterimaDimuka

  arrGroup.value.biayaYangMasihHarusDibayar = arrGroup.value.biayaYangMasihHarusDibayar + result[i].biayaYangMasihHarusDibayar
  arrGroup.value.kreditSLABunga = arrGroup.value.kreditSLABunga + result[i].kreditSLABunga

  arrGroup.value.kreditRDIBunga = arrGroup.value.kreditRDIBunga + result[i].kreditRDIBunga
  arrGroup.value.kewajibanLainLain = arrGroup.value.kewajibanLainLain + result[i].kewajibanLainLain
  arrGroup.value.hutangJangkaPanjangLainnya = arrGroup.value.hutangJangkaPanjangLainnya + result[i].hutangJangkaPanjangLainnya
  arrGroup.value.ekuitasAwal = arrGroup.value.ekuitasAwal + result[i].ekuitasAwal
  arrGroup.value.surplusDefisitTahunPeriodeBerjalan = arrGroup.value.surplusDefisitTahunPeriodeBerjalan + result[i].surplusDefisitTahunPeriodeBerjalan
  arrGroup.value.komponenEkuitasLainnya = arrGroup.value.komponenEkuitasLainnya + result[i].komponenEkuitasLainnya
  arrGroup.value.jumlah = arrGroup.value.jumlah + result[i].jumlah

}

}
// const groupMonth = (result: any) => {
//   let sama = false

//   let arrGroup: any = [];
//   for (let i = 0; i < result.length; i++) {
//     sama = false
//     for (let x = 0; x < arrGroup.length; x++) {
//       if (arrGroup[x].bulan.substr(0, 7) == result[i].bulan.substr(0, 7)) {
//         arrGroup[x].jkn_rajal = arrGroup[x].jkn_rajal + result[i].jkn_rajal
//         arrGroup[x].Eks_Asuransi_rajal = arrGroup[x].Eks_Asuransi_rajal + result[i].Eks_Asuransi_rajal
//         arrGroup[x].Eks_perusahaan_rajal = arrGroup[x].Eks_perusahaan_rajal + result[i].Eks_perusahaan_rajal
//         arrGroup[x].Reg_Asuransi_rajal = arrGroup[x].Reg_Asuransi_rajal + result[i].Reg_Asuransi_rajal
//         arrGroup[x].Reg_perusahaan_rajal = arrGroup[x].Reg_perusahaan_rajal + result[i].Reg_perusahaan_rajal
//         arrGroup[x].Reg_umum_rajal = arrGroup[x].Reg_umum_rajal + result[i].Reg_umum_rajal
//         arrGroup[x].jkn_ranap = arrGroup[x].jkn_ranap + result[i].jkn_ranap
//         arrGroup[x].Eks_Asuransi_ranap = arrGroup[x].Eks_Asuransi_ranap + result[i].Eks_Asuransi_ranap
//         arrGroup[x].Eks_perusahaan_ranap = arrGroup[x].Eks_perusahaan_ranap + result[i].Eks_perusahaan_ranap
//         arrGroup[x].Eks_umum_ranap = arrGroup[x].Eks_umum_ranap + result[i].Eks_umum_ranap
//         arrGroup[x].Reg_Asuransi_ranap = arrGroup[x].Reg_Asuransi_ranap + result[i].Reg_Asuransi_ranap
//         arrGroup[x].Reg_perusahaan_ranap = arrGroup[x].Reg_perusahaan_ranap + result[i].Reg_perusahaan_ranap
//         arrGroup[x].Reg_umum_ranap = arrGroup[x].Reg_umum_ranap + result[i].Reg_umum_ranap
//         arrGroup[x].pendapatan_lain = arrGroup[x].pendapatan_lain + result[i].pendapatan_lain
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
  let tglAwal = H.formatDate(item.value.qBulan[0], "YYYY-MM-DD 00:00:00")
  let tglAkhir = H.formatDate(item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0], "YYYY-MM-DD 23:59:59")

  let response = await useApi().get(`mkko/lap-balance-sheet?dari=${tglAwal}&sampai=${tglAkhir}&send=true`)
  if(response.status == 200){
    H.alert('success','Sukses')
  }else{
    H.alert('error', 'Gagal Kirim Data')
  }
  // for (let index = 0; index < dataKunjungan.value.length; index++) {
  //   const rekap = dataKunjungan.value[index];
  //   await useApi().post(`mkko/api-integrate`,
  //     {
  //       url: 'keuangan',
  //       method: 'POST',
  //       data: {
  //         "tanggal": rekap.bulan,
  //         "detail": {
  //           "pendapatan_rs": {
  //             "outpatient_revenue": {
  //               "pasien_jkn": {
  //                 "jkn_reguler": rekap.jkn_rajal,
  //                 "jkn_naikkelas": 0
  //               },
  //               "pasien_non_jkn_eksekutif": {
  //                 "asuransi": rekap.Eks_Asuransi_rajal,
  //                 "jaminan_perusahaan": rekap.Eks_perusahaan_rajal,
  //                 "pembayaran_mandiri": rekap.Eks_umum_rajal,
  //               },
  //               "pasien_non_jkn_reguler": {
  //                 "asuransi": rekap.Reg_Asuransi_rajal,
  //                 "jaminan_perusahaan": rekap.Reg_perusahaan_rajal,
  //                 "pembayaran_mandiri": rekap.Reg_umum_rajal,
  //               }
  //             },
  //             "inpatient_revenue": {
  //               "pasien_jkn": {
  //                 "jkn_reguler": rekap.jkn_ranap,
  //                 "jkn_naikkelas": 0
  //               },
  //               "pasien_non_jkn_eksekutif": {
  //                 "asuransi": rekap.Eks_Asuransi_ranap,
  //                 "jaminan_perusahaan": rekap.Eks_perusahaan_ranap,
  //                 "pembayaran_mandiri": rekap.Eks_umum_ranap,
  //               },
  //               "pasien_non_jkn_reguler": {
  //                 "asuransi": rekap.Reg_Asuransi_ranap,
  //                 "jaminan_perusahaan": rekap.Reg_perusahaan_ranap,
  //                 "pembayaran_mandiri": rekap.Reg_umum_ranap,
  //               }
  //             },
  //             "pendapatan_layanan_lain": rekap.pendapatan_lain,
  //           },
  //           "rba_pendapatan": "0",
  //           "beban_pokok_pendapatan": {
  //             "beban_pegawai": "0"
  //           },
  //           "beban_administrasi_umum": {
  //             "beban_barang_jasa": "0",
  //             "beban_pemeliharaan": "0",
  //             "beban_perjalanan_dinas": "0",
  //             "beban_penyisihan_piutang_tak_tertagih": "0"
  //           },
  //           "beban_persediaan": {
  //             "beban_persediaan_farmasi": 0,
  //             "beban_persediaan_non_farmasi": 0
  //           },
  //           "beban_penyusutan_dan_amortisasi": "0",
  //           "surplus_defisit_usaha": "0",
  //           "depresiasi_amortisasi": "0",
  //           "EBITDA": "0",
  //           "beban_pegawai": "0",
  //           "EBITDA_plus_beban_pegawai": "0",
  //           "pendapatan_keuangan": {
  //             "pendapatan_bunga_bank": "0",
  //             "deposito": "0",
  //             "pend_lainnya": "0",
  //             "jumlah_pendapatan_keuangan": "0"
  //           },
  //           "biaya_keuangan": "0",
  //           "pendapatan_biaya_lain_lain": {
  //             "pend_apbn_lainnya": "0",
  //             "pendapatan_hibah": "0",
  //             "pendapatan_blu_lainnya": "0",
  //             "jumlah_pendapatan_lain_lain": "0"
  //           },
  //           "surplus_usaha_sebelum_pajak": "0",
  //           "manfaat_beban_pajak": "0",
  //           "surplus_bersih": "0"

  //         }
  //       }
  //     })
  // }
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
  vertical-align: top
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

      >img {
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
