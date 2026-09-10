

<template>
  <ConfirmDialog/>
  <div>
    <form class="">
      <div class="form-outer">
        <div class="form-body" style="padding:0 !important;">
          <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
            <div class="columns is-multiline">
              <div class="column is-4 ">
                <div class="columns is-multiline">
                  <div class="column is-12" v-if="isLoadingPasien">
                    <PlaceloadHeader class="m-3" />
                  </div>
                  <div class="column is-12 mb-0 pb-0 px-0 mx-0" v-if="!isLoadingPasien">
                    <div class="hr-dashboard">
                      <HeadPasien :pasien="pasien" class="m-3" />
                    </div>
                  </div>
                </div>
                <!-- <div class="business-dashboard hr-dashboard">
                  <HeadPasien :pasien="pasien" class="m-3" />
                </div> -->
                <!-- <div class="form-body p-2">
                </div> -->
                <div class="banking-dashboard banking-dashboard-v2">
                  <div class="dashboard-card is-card-panel" style="border-radius: 0;">
                    <div class="inner-box">
                      <div class="box-title" style="margin-bottom: 0;">
                        <h3>{{ item.title }}</h3>
                      </div>
                      <div class="card-balance-wrap">
                        <div class="card-balance">
                          <span style="font-weight: 700;font-size: 1.8rem;">{{ H.formatRp(Math.round(item.jumlahBayar), 'Rp.')
                          }}</span>
                          <span>{{ item.terbilang }}</span>
                        </div>
                        <div class="card-balance-stats">
                          <div class="card-balance-stat">
                            <div class="stat-block">
                              <div class="stat-icon is-up">
                                <i class="fas fa-calculator" aria-hidden="true"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="columns is-multiline mt-5">
                          <div class="column is-12">
                            <VField label="Tgl Bayar">
                              <VControl class="prime-auto">
                                <Calendar v-model="item.tglBayar" selectionMode="single" :manualInput="false"
                                  class="w-100" :showIcon="true" showTime hourFormat="24" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12" style="display: none !important">
                            <VField label="Diterima dari">
                              <VControl icon="feather:user">
                                <VInput type="text" v-model="item.diterimaDari" placeholder="Diterima dari" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12" style="display: none !important">
                            <VField>
                              <VControl>
                                <VSwitchBlock v-model="item.changeNama" label="Ubah Nama" color="danger" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12" v-if="item.changeNama" style="display: none !important">
                            <VField>
                              <VControl>
                                <VInput type="text" v-model="item.namabaru" class="input prime-auto" placeholder="Nama Pasien" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField  class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                              <VLabel class="required-field">Ruangan</VLabel>
                              <VControl icon="fas fa-home" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.ruangan" :options="d_Ruangan" :optionLabel="'namaruangan'"
                                  class="is-rounded w-100" placeholder="Ruangan" style="width: 100%;" :filter="true"
                                  @change="setCache(item.ruangan)"/>
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-8 mt-3">
                <div class="form-layout is-stacked px-0 mx-0" style="max-width: 100%;">
                  <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                      <div class="form-header-inner">
                        <div class="left">
                          <h3> {{ TITLE }} </h3>
                        </div>
                        <div class="right">
                          <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                              Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="info" raised icon="feather:printer" v-if="norecSBM != ''"
                              @click="cetakBilling()"> Cetak Invoice
                            </VButton>
                            <VButton type="button" rounded outlined color="info" raised icon="feather:printer" v-if="norecSBM != ''"
                              @click="cetakKwitansi2()"> Cetak Kwitansi
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading" :disabled="isDisabled"
                              @click="simpan()"> Simpan
                            </VButton>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="form-body p-2">
                      <div class="business-dashboard hr-dashboard">
                        <div class="columns is-multiline">
                          <div class="column is-12" v-if="isLoadingPasien">
                            <PlaceloadHeader class="m-3" />
                          </div>
                          <div class="column is-12" v-if="!isLoadingPasien">
                            <HeadPasien :pasien="pasien" class="m-3" />
                          </div>
                        </div>
                      </div>
                    </div> -->
                  </div>
                </div>
                <VCard class="card-0-radius" style="/* border-top-left-radius: 11px; */ border-left: solid hsla(323, 100%, 75%, 0.72) 3px;">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <VCard class="is-grey">
                        <div class="columns is-multiline p-1" v-for="(item, index) in listItem" :key="index">
                          <div class="column is-6">
                            <!-- <h1>Cara Bayar</h1> -->
                            <VField :label="index == 0 ? 'Cara Bayar' : ''" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                              <VControl icon="fas fa-credit-card" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.caraBayar" :options="d_CaraBayar" :optionLabel="'carabayar'"
                                  class="is-rounded" placeholder="Cara Bayar" style="width: 100%;" :filter="true" 
                                  @change="changeCaraBayar($event)" disabled/>
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <!-- <h1>Nominal</h1> -->
                            <VField :label="index == 0 ? 'Nominal' : ''">
                              <VControl class="prime-auto">
                                <VInput type="text" v-model="item.nominal" v-on:input="changeNomi(item.nominal,index)"
                                  v-mask-currency placeholder="Nominal" class="is-rounded" :disabled="disabledAllNominal"/>
                              </VControl>
                            </VField>
                          </div>
                          <!-- <div class="column is-1 mt-5" style="display: none !important">
                            <VIconButton v-if="index > 0" outlined type="button" raised circle class="is-pulled-right"
                              icon="feather:trash" @click="removeItem(index)" color="danger">
                            </VIconButton>
                          </div>
                          <div class="column is-1 mt-5" style="display: none !important">
                            <VIconButton outlined type="button" raised circle class="is-pulled-right" icon="feather:plus"
                              @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah Cara Bayar'">
                          </VIconButton>
                          <VButton type="button" rounded outlined color="info" raised icon="feather:plus"
                            @click="addNewItem()"> Tambah
                          </VButton>
                          </div> -->
                        </div>
                      </VCard>
                    </div>
                  </div>
                </VCard>
                <VCard>
                  <div class="columns is-multiline">
                    <div class="column is-8 pt-0 mt-0">
                      <VCard style="/* border-top-left-radius: 11px; */border-radius: 0 !important; border-left: solid hsla(170, 100%, 75%, 0.72) 3px;">
                        <div class="columns is-multiline">
                          <div class="column is-2 mt-1">
                            <VField>
                              <VLabel class="fs-total-label">TOTAL</VLabel>
                            </VField>
                          </div>
                          <div class="column is-10">
                            <VField>
                              <VLabel class="fs-total" style="font-weight: 700;width:500px !important;
                  font-size: 2.4rem;">{{
                    H.formatRp(isNaN(item.totalBayarFix) ? 0 : item.totalBayarFix,
                      'Rp.')
                  }} </VLabel>
                            </VField>
                          </div>
                        </div>
                      </VCard>
                    </div>
                    <div class="column is-4 mt-4">
                      <VField>
                        <VControl>
                          <VRadio v-model="options" value="LOST" label="LOST" style="color: black;"/>
                          <VRadio v-model="options" value="KASBON" label="KASBON" style="color: black;"/>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-8">
                      <h1>Diskon</h1>
                      <VField :label="index == 0 ? 'Nominal' : ''">
                        <VControl class="prime-auto">
                          <VInput type="text" v-model="item.diskon" v-on:input="changeDiskon(item.diskon)"
                            v-mask-currency placeholder="Nominal" class="is-rounded"/>
                        </VControl>
                      </VField>
                    </div>
                    <VButton color="primary ml-5" raised class="search-button" @click="bpdCheckout()" :loading="isLoading" v-if="hidetombol == false"> Buat Billing </VButton>
                    <VButton color="primary ml-2" raised class="search-button" @click="riwayatBpd()" :loading="isLoading" v-if="hidetombol == false"> History Transaksi </VButton>
                  </div>
                </VCard>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>


  <VModal :open="showModalTemplate" title="Template CPPT" :noclose="true" size="large" actions="right"
    @close="showModalTemplate = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Template</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="25%">Nomor Billing</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal Transaksi</td>
                  <td class="tg-0lax text-center" width="25%">Status</td>
                  <td class="tg-0lax text-center" width="20%">Jumlah Bayar</td>
                  <td class="tg-0lax text-center" width="20%">Tanggal Pembayaran</td>
                  <td class="tg-0lax text-center" width="20%">No Bukti</td>
                  <td class="tg-0lax text-center" width="5%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listHistory">
                <tr>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.noid }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.tanggal_transaksi }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.status_bayar }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.tagihan }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.tanggal_bayar }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.nobukti }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="cetakVABPD(resep)"
                      color="info" v-tooltip-prime.top="'Cetak'">
                    </VIconButton>
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="deleteHistoryBpd(resep)"
                      color="info" v-tooltip-prime.top="'Cetak'">
                    </VIconButton>
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="cekstatusbpd(resep)"
                      color="info" v-tooltip-prime.top="'Cetak'">
                    </VIconButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { useUserSession } from '/@src/stores/userSession'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import InputMask from 'primevue/inputmask';
import moment, { isDate } from 'moment'
import sleep from '/@src/utils/sleep'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
useHead({
  title: 'Verifikasi Tagihan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let NOREC_SP = useRoute().query.norec_sp as string
let NOREC_PD = useRoute().query.norec_pd as string
let ID_PASIEN = useRoute().query.nocmfk as string
let parameterTambahan = useRoute().query.pageFrom as string
let TOTALBAYAR = useRoute().query.totalBayar

let TITLE = ref('')
const isLoadingPasien: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_SP: NOREC_SP != undefined ? NOREC_SP : '',
  registrasi: {},
  tglBayar: new Date(),
  totalBayarFix: TOTALBAYAR ? TOTALBAYAR : 0,
  title: 'TOTAL HARUS BAYAR',
  ID_PASIEN: ID_PASIEN != undefined ? ID_PASIEN : '',
  diskon: 0
})
const confirm = useConfirm();
const showModalTemplate: any = ref(false)
const listHistory:any = ref([])
const norecSBM: any = ref('')
const tanggalBayar: any = ref('')
const isLoadingBill: any = ref(false)
const isDisabled: any = ref(false)
const hidetombol: any = ref(true)
const listChecked: any = ref([])
const registrasi: any = ref({})
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
const disabledAllNominal = ref(false);
for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i];
  if (i <= 9 && element != 'primary')
    listColor.value.push(element)
}
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const options = ref([])
const d_CaraBayar: any = ref([])
const d_Ruangan: any = ref([])
const pasien: any = ref({})
const isLoading = ref(false)
const router = useRouter()
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const userLogin: any = useUserSession().getUser()
const listItem: any = ref([])
const listItem2: any = ref([])
if (parameterTambahan == 'tagihanPasien' || parameterTambahan == 'PiutangPasien') {
  TITLE.value = 'Pembayaran Tagihan'
  if(parameterTambahan == 'PiutangPasien') {
    // options.value = 'KASBON';
    parameterTambahan = 'tagihanPasien';
  }
} else if (parameterTambahan == 'depositPasien') {
  TITLE.value = 'Pembayaran Deposit'
} else if (parameterTambahan == 'pengembalianDeposit') {
  TITLE.value = 'Pengembalian Deposit'
} else if (parameterTambahan == 'tagihanNonLayanan') {
  TITLE.value = 'Pembayaran Tagihan Non Layanan'
}else if (parameterTambahan == 'ubahCaraBayar') {
  TITLE.value = 'Ubah Cara Bayar'
}
function pasienByPD() {
  if (item.NOREC_PD == '') return
  isLoadingPasien.value = true
  useApi().get(
    `/general/header-pasien?nocmfk=${item.ID_PASIEN}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
      pasien.value = response.pasien
      registrasi.value = response.registrasi
      pasien.value.isClosing = response.registrasi[0].isclosing
      pasien.value.namaruangan = response.last_registrasi.namaruangan
      pasien.value.kelompokpasien = response.last_registrasi.kelompokpasien
      pasien.value.noregistrasi = response.last_registrasi.noregistrasi
      pasien.value.tglregistrasi = H.formatDateIndoSimple(response.last_registrasi.tglregistrasi)
      pasien.value.tglregistrasifix = response.last_registrasi.tglregistrasi
      pasien.value.status = response.last_registrasi.statusbayar
      pasien.value.kelas = response.registrasi?.[0]?.namakelas || []
      pasien.value.rekanan = response.registrasi?.[0]?.namarekanan || []
      pasien.value.kelasditanggung = response.registrasi?.[0]?.kelasditanggung || []
      pasien.value.nmprovider = response.registrasi?.[0]?.nmprovider ?? ''
      pasien.value.klsrawathak_kode = response.registrasi?.[0]?.klsrawathak_kode ?? ''
      pasien.value.objectkelompokpasienlastfk = response.registrasi?.[0]?.objectkelompokpasienlastfk ?? ''
      pasien.value.kelompokpasien = response.registrasi?.[0]?.kelompokpasien ?? ''

      item.inacbg_totalgrouper = response.last_registrasi.inacbg_totalgrouper
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.registrasi = response.last_registrasi
      isLoadingPasien.value = false
    })
}
async function totalBayar() {
  isLoadingBill.value = true
  isLoading.value = true
  delete item.jumlahBayar
  delete item.terbilang
  await useApi().get(
    `/kasir/pembayaran-tagihan?norec=${item.NOREC_SP}&norec_pd=${item.NOREC_PD}&from=${parameterTambahan}`).then(async (response: any) => {
      isLoading.value = false
      if (item.NOREC_PD == '') {
        pasien.value = response.pasien
      }
      d_CaraBayar.value = response.carabayar
      d_Ruangan.value = response.ruangan
      // if(H.cacheHelper().get('ruangKasir')){
      //   item.ruangan = H.cacheHelper().get('ruangKasir')
      // }

      d_Ruangan.value.forEach((element: any) => {
        if(kelompokUser == 'kasir-rajal' && element.id == 359){
          item.ruangan = element
        } else if(kelompokUser == 'kasir-igd' && element.id == 361){
          item.ruangan = element
        } else if(kelompokUser == 'kasir-ranap' && element.id == 360){
          item.ruangan = element
        }else if(kelompokUser == 'kasir-kanker' && element.id == 247) {
          item.ruangan = element
        }
      });
      
      item.jumlahBayar = response.jumlahBayar
      item.terbilang = response.terbilang
      let num = 0
      let arr = []
       d_CaraBayar.value.forEach((element: any) => {
        console.log("D_CARABAYAR", element)
        if(element.carabayar.toLowerCase().indexOf('cara bayar') == -1) {
          if (element.carabayar.toLowerCase().indexOf('tunai') > -1) {
            listItem.value.unshift({caraBayar: element, nominal: 0});
          } else if (element.carabayar.toLowerCase().indexOf('perusahaan') > -1) {
            listItem.value.unshift({caraBayar: element, nominal: 0});
          }else {
            listItem.value.push({caraBayar: element, nominal: 0})
          }
          num++
          return
        }
      });

      console.log("LIST ITEM VALUE", listItem.value)
      console.log("PASIEN VALUE", pasien.value.kelompokpasien.toLowerCase())
      listItem.value.forEach(elNominal => {
        console.log('ELNOMINAL', elNominal)
        if(elNominal.caraBayar != null) {
          if(parameterTambahan == "pengembalianDeposit" && elNominal.caraBayar.carabayar.toLowerCase().indexOf('tunai')) {
            item.totalBayarFix = item.totalBayarFix*(-1)
            elNominal.nominal = elNominal.nominal*(-1)
          }else {
            if(pasien.value.kelompokpasien.toLowerCase().indexOf('iks') > -1) {
              if(elNominal.caraBayar.carabayar.toLowerCase().indexOf('perusahaan') > -1) {
                elNominal.nominal = response.title == 'TOTAL BILLING' ? (TOTALBAYAR ? TOTALBAYAR : 0) : parseInt(Math.round(response.jumlahBayar))
                elNominal.nominal = H.formatRupiah(parseInt(Math.round(elNominal.nominal)))
              }
            }else if(pasien.value.kelompokpasien.toLowerCase().indexOf('ketenagakerjaan') > -1) {
              if(elNominal.caraBayar.carabayar.toLowerCase().indexOf('perusahaan') > -1) {
                elNominal.nominal = response.title == 'TOTAL BILLING' ? (TOTALBAYAR ? TOTALBAYAR : 0) : parseInt(Math.round(response.jumlahBayar))
                elNominal.nominal = H.formatRupiah(parseInt(Math.round(elNominal.nominal)))
              }
            }else {
              if(elNominal.caraBayar.carabayar.toLowerCase().indexOf('tunai') > -1) {
                elNominal.nominal = response.title == 'TOTAL BILLING' ? (TOTALBAYAR ? TOTALBAYAR : 0) : parseInt(Math.round(response.jumlahBayar))
                elNominal.nominal = H.formatRupiah(parseInt(Math.round(elNominal.nominal)))
              }
            }
          }
        }
        // if(registrasi.value.length > 0 && registrasi.value[0])
      });
      // listItem.value[0].nominal = response.title == 'TOTAL BILLING' ? (TOTALBAYAR ? TOTALBAYAR : 0) : parseInt(Math.round(response.jumlahBayar))
      // console.log('NOMINAL 1', listItem.value[0].nominal)
      item.totalBayarFix = response.title == 'TOTAL BILLING' ? (TOTALBAYAR ? TOTALBAYAR : 0) : parseInt(Math.round(response.jumlahBayar))
      console.log('TOTAL BAYAR', item.totalBayarFix)
      console.log('NOMINAL 2', listItem.value[0].nominal)
      // listItem.value[0].nominal = H.formatRupiah(parseInt(Math.round(listItem.value[0].nominal)))
      item.title = response.title
      isLoadingBill.value = false
    })
}

const deleteHistoryBpd = async (e: any) => {

        if(e.status_bayar == true){
          H.alert('error', 'Tagihan sudah Lunas, tidak bisa dihapus!')
					return
				}

        confirm.require({
          message: 'Batalkan pembayaran?',
          header: 'Dialog Konfirmasi',
          icon: 'pi pi-info-circle',
          acceptClass: 'p-button-danger',
          accept: () => {
              isLoading.value = true
              useApi().post(
                `/kasir/billing/bridgingbpd/delete-by-id`, {noid: e.noid}).then((response: any) => {
                  isLoading.value = false
                  refreshbpd()
                })
                .catch((error) => {
                  isLoading.value = false
                  H.alert('error', 'Melebihi batas waktu eksekusi')
                  refreshbpd()
                  useApi().post(
                  `/kasir/billing/bridgingbpd/post-log`, {fungsi: 'ws_tagihan_delete_by_id', message: 'Melebihi batas waktu eksekusi'}).then((response: any) => {
                    
                  })
                })
          },
          reject: () => {
          },
      })
}

const bpdCheckout = async (e: any) => {
  item.tglBayar = moment(item.tglBayar).format('YYYY-MM-DD')

  console.log(moment(pasien.value.tglpulang).format('MM'))
  let bulan = moment(pasien.value.tglpulang).format('MM')
  let namabulan = ''

  if(bulan == '01'){
    namabulan = 'Januari'
  } else if(bulan == '02'){
    namabulan = 'Februari'
  } else if(bulan == '03'){
    namabulan = 'Maret'
  } else if(bulan == '04'){
    namabulan = 'April'
  } else if(bulan == '05'){
    namabulan = 'Mei'
  } else if(bulan == '06'){
    namabulan = 'Juni'
  } else if(bulan == '07'){
    namabulan = 'Juli'
  } else if(bulan == '08'){
    namabulan = 'Agustus'
  } else if(bulan == '09'){
    namabulan = 'September'
  } else if(bulan == '10'){
    namabulan = 'Oktober'
  } else if(bulan == '11'){
    namabulan = 'November'
  } else if(bulan == '12'){
    namabulan = 'Desember'
  }

  let dataBpd = {
		'nama': pasien.value.namapasien,
		'tagihan': item.totalBayarFix,
		'ket_1_val': pasien.value.noregistrasi,
		'ket_2_val': moment(pasien.value.tglpulang).format('DD') + ' ' + namabulan + ' ' + moment(pasien.value.tglpulang).format('YYYY'),
		'ket_3_val': pasien.value.alamatlengkap,
		'ket_4_val': pasien.value.nohp,
	}

  isLoading.value = true
  useApi().post(
    `/kasir/billing/bridgingbpd/insert`, dataBpd).then((response: any) => {
      isLoading.value = false
      useApi().get(
      `/kasir/billing/bridgingbpd/get-riwayat?noregistrasi=${pasien.value.noregistrasi}`).then((response: any) => {
        listHistory.value = response.data
      })
    })
    .catch((error) => {
          isLoading.value = false
          H.alert('error', 'Melebihi batas waktu eksekusi')
          console.log(error)
          useApi().post(
          `/kasir/billing/bridgingbpd/post-log`, {fungsi: 'ws_tagihan_insert', message: 'Melebihi batas waktu eksekusi'}).then((response: any) => {
            
          })
					//toastr.error('Melebihi batas waktu eksekusi')
				})


  console.log(dataBpd)
}

const riwayatBpd = async (e: any) => {
  item.tglBayar = moment(item.tglBayar).format('YYYY-MM-DD')

  useApi().get(
      `/kasir/billing/bridgingbpd/get-riwayat?noregistrasi=${pasien.value.noregistrasi}`).then((response: any) => {
        listHistory.value = response.data
        showModalTemplate.value = true

      })
};


const refreshbpd = async (e: any) => {
  useApi().get(
      `/kasir/billing/bridgingbpd/get-riwayat?noregistrasi=${pasien.value.noregistrasi}`).then((response: any) => {
        listHistory.value = response.data
        showModalTemplate.value = true

      })
};

const cekstatusbpd = async (e: any) => {
  isLoading.value = true
  useApi().post(
  `/kasir/billing/bridgingbpd/inquiry`, {noid: e.noid}).then((response: any) => {
    isLoading.value = false
    refreshbpd()
  })
  .catch((error) => {
    isLoading.value = false
    H.alert('error', 'Melebihi batas waktu eksekusi')
    refreshbpd()
    useApi().post(
    `/kasir/billing/bridgingbpd/post-log`, {fungsi: 'ws_inquiry_tagihan', message: 'Melebihi batas waktu eksekusi'}).then((response: any) => {
      
    })
  })
};

function simpan() {

  console.log(options._value)

  if (item.ruangan == null) {
    H.alert('error', 'Ruangan Wajib di Isi')
    return
  }

  if (listItem.value.length == 0) {
    H.alert('error', 'Isi Cara Bayar dulu')
    return
  }
  let objectstatuspiutangfk = 0
  let statuslunas = null
  if(options._value.length != 0){
    if(options._value == 'LOST'){
      objectstatuspiutangfk = 1
    } else if(options._value == 'KASBON'){
      objectstatuspiutangfk = 2
    }
  }

  

  let total = 0
  for (let x = 0; x < listItem.value.length; x++) {
    const element = listItem.value[x];
    element.nominal = H.unFormatRupiah(element.nominal)
    total += parseFloat(element.nominal)
    if (element.caraBayar == null) {
      H.alert('error', 'Isi Cara Bayar dulu')
      return
    }
  }
  if (TITLE.value != 'Pembayaran Deposit' && TITLE.value != 'Pengembalian Deposit') {
    if (total < parseFloat(item.jumlahBayar) && options._value.length == 0) {
      H.alert('error', 'Total Bayar harus sesuai dengan jumlah harus dibayar')
      return
    }
  }
  if(total == parseFloat(item.jumlahBayar) && objectstatuspiutangfk != 0){
    statuslunas = true
  }

  listItem.value.forEach((element: any) => {
    if(element.nominal != 0){
      listItem2.value.push(element)
    }
  });

  norecSBM.value = ''
  var objSave = {
    parameterTambahan: parameterTambahan,
    norec: NOREC_SP,
    norec_pd: NOREC_PD,
    objectstatuspiutangfk: objectstatuspiutangfk,
    islunaspiutang: statuslunas,
    nocm: pasien.value.nocm,
    namapasien: pasien.value.namapasien,
    jumlahbayar: item.jumlahBayar,
    jumlahbayarbefore: item.totalBayarFix,
    diskon: item.diskon,
    namapegawaipenerima: item.diterimaDari ? item.diterimaDari : null,
    tglsbm: H.formatDate(item.tglBayar, 'YYYY-MM-DD HH:mm'),
    ruanganfk: item.ruangan ? item.ruangan.id : null,
    details: listItem2.value,
  }
  isLoading.value = true
  useApi().post(
    `/kasir/pembayaran-tagihan/simpan`, objSave).then((response: any) => {
      isLoading.value = false
      norecSBM.value = response.sbm.norec
      isDisabled.value = true
      NOREC_SP = response.sp.norec
      console.log(NOREC_SP)
      if(item.ruangan)
        H.cacheHelper().set('ruangKasir',item.ruangan)
      cetakKwitansi()
      // window.history.back()
      router.push({
        name: 'module-dashboard-kasir',
      })
      // delete item.NOREC_SO
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const changeCaraBayar = (e: any) => {
  console.log(e.value.carabayar)

  if(e.value.id == 9){
    hidetombol.value = false
  } else{
    hidetombol.value = true
  }
}


function addNewItem() {
  let total = countTotal()
  let last = parseFloat(item.jumlahBayar) - total
  if (last == 0) {
    H.alert('error', 'Silahkan ubah nominal untuk SPLIT BAYAR')
    return
  }
  listItem.value.push({
    caraBayar: null,
    nominal: last,
  });
  setTotal()
}
function countTotal() {
  let total = 0
  for (let x = 0; x < listItem.value.length; x++) {
    const element = listItem.value[x];
    element.nominal = H.unFormatRupiah(element.nominal)
    if(isNaN(   element.nominal)){
      element.nominal = 0
    }
    total += parseFloat(element.nominal)
  }
  return total
}
function removeItem(index: any) {
  listItem.value.splice(index, 1)
}
function kembaliKeun() {
  window.history.back()
}
const isMozilla = () => {
    return navigator.userAgent.indexOf('Firefox') !== -1;
}
async function changeNomi(e: any,index:any) {
  if(isMozilla()){
    await sleep(1000)
    let nome = H.unFormatRupiah(listItem.value[index].nominal)
    if (TITLE.value != 'Pembayaran Deposit') {
      let total = countTotal()
      if (total > parseFloat(item.jumlahBayar)) {
        for (let x = 0; x < listItem.value.length; x++) {
          const element = listItem.value[x];
          element.nominal = H.unFormatRupiah(element.nominal)
          if (nome == element.nominal) {
            element.nominal = 0
          }
        }
      }
    }
    setTotal()
  }else{
    e = H.unFormatRupiah(e)
    if (TITLE.value != 'Pembayaran Deposit') {
      let total = countTotal()
      if (total > parseFloat(item.jumlahBayar)) {
        for (let x = 0; x < listItem.value.length; x++) {
          const element = listItem.value[x];
          element.nominal = H.unFormatRupiah(element.nominal)
          if (e == element.nominal) {
            element.nominal = 0
          }
        }
      }
    }
    setTotal()
  }

}

//const jumlahAwal = item.jumlahBayar; // Atau ambil dari state/item asli

async function changeDiskon(e: any) {

  console.log('INPUT E (before unformat):', e, typeof e);

  const rawDiskon = H.unFormatRupiah(e);
  const diskon = Number(rawDiskon);

  console.log('DISKON (after unformat):', rawDiskon, ' => ', diskon, typeof diskon);

  // Simpan base hanya sekali (misal saat item pertama kali masuk)
  if (!item.jumlahAwal) {
    item.jumlahAwal = Number(item.jumlahBayar);
    console.log('SIMPAN jumlahAwal:', item.jumlahAwal);
  }

  const jumlahAwal = Number(item.jumlahAwal);

  if (!Number.isNaN(diskon) && !Number.isNaN(jumlahAwal)) {
    item.jumlahBayar = jumlahAwal - diskon;
    console.log('JUMLAH BAYAR BARU:', item.jumlahBayar);
  } else {
    console.warn('NaN detected: jumlahAwal or diskon is invalid');
  }
}


async function setTotal() {
  await sleep(1000)
  let total = countTotal()
  item.totalBayarFix = total
}
const cetakKwitansi = async () => {
  console.log(pasien.value)
  let namabaru = item.namabaru ? item.namabaru : '-'
  let bangsa = pasien.value.kebangsaan;
  if(parameterTambahan == "depositPasien"){
    // H.printBlade(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}`)
    // H.printBlade(`report/bukti-kwitansi?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}`)
    await cetakKwitansiKedua()
  } else if(parameterTambahan == "pengembalianDeposit"){
    // H.printBlade(`report/bukti-kwitansi?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}`)
    
    H.printBlade(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}`)
    await cetakKwitansiKedua();
  }else if(parameterTambahan == "tagihanNonLayanan"){
    // H.printBlade(`report/bukti-layanan-bpjs-nonlayanan?norec_sp=${NOREC_SP}`)
    // H.printBlade(`report/bukti-kwitansi?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}`)

    await cetakKwitansiKedua()
  } else{
    if(pasien.value.iddepartemen != 16){
      H.printBlade(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}&bangsa=${bangsa}`)

      await cetakKwitansiKedua()

    } else if(pasien.value.iddepartemen == 16){
      H.printBlade(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}&bangsa=${bangsa}`)

      await cetakKwitansiKedua()
      // await qzService.printData(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}&user=${userLogin.pegawai.namaLengkap}&bangsa=${bangsa}`, 'KWITANSI RANAP', 1)
    } 
  }
}
const cetakKwitansiKedua = async () => {
  console.log(pasien.value)
  let namabaru = item.namabaru ? item.namabaru : '-'
  let bangsa = pasien.value.kebangsaan;
  if(parameterTambahan == "depositPasien"){
    H.printBlade(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}`)
    // await qzService.printData(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&user=${userLogin.pegawai.namaLengkap}`, 'KWITANSI', 1)
  } else if(parameterTambahan == "pengembalianDeposit"){
    H.printBlade(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&bangsa=${bangsa}`)
    // H.printBlade(`report/bukti-kwitansi?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}`)

    // await qzService.printData(`report/bukti-kwitansi?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&user=${userLogin.pegawai.namaLengkap}`, 'FORM KWITANSI', 1)
  }else if(parameterTambahan == "tagihanNonLayanan"){
    H.printBlade(`report/bukti-layanan-carabayar-nonlayanan?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}`)
    
    // await qzService.printData(`report/bukti-layanan-carabayar-nonlayanan?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&user=${userLogin.pegawai.namaLengkap}`, 'KWITANSI', 1)
  } else{
    H.printBlade(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&bangsa=${bangsa}`)
    // if(pasien.value.iddepartemen != 16){
      // await qzService.printData(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&user=${userLogin.pegawai.namaLengkap}&bangsa=${bangsa}`, 'KWITANSI', 1)   
    // } 
  }
}
const cetakBilling = () => {
  console.log(pasien.value)
  let namabaru = item.namabaru ? item.namabaru : '-'
  let bangsa = pasien.value.kebangsaan;
  if(parameterTambahan == "depositPasien"){
    H.printBlade(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}`)
  } else if(parameterTambahan == "pengembalianDeposit"){
    H.printBlade(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}`)
  } else if(parameterTambahan == "tagihanNonLayanan"){
    H.printBlade(`report/bukti-layanan-bpjs-nonlayanan?norec_sp=${NOREC_SP}&bangsa=WNI`)
    // qzService.printData(`report/bukti-layanan-bpjs-nonlayanan?norec_sp=${NOREC_SP}&user=${userLogin.pegawai.namaLengkap}`, 'KWITANSI RAJAL', 1)
  } else{
    if(pasien.value.iddepartemen != 16){
      H.printBlade(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}&bangsa=${bangsa}`)
      // qzService.printData(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}&user=${userLogin.pegawai.namaLengkap}&bangsa=${bangsa}`, 'KWITANSI RAJAL', 1)
    } else if(pasien.value.iddepartemen == 16){
      H.printBlade(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}&bangsa=${bangsa}`)

      // qzService.printData(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}&user=${userLogin.pegawai.namaLengkap}&bangsa=${bangsa}`, 'KWITANSI RANAP', 1)
    }
  }
}
const cetakKwitansi2 = () => {
  console.log(pasien.value)
  let namabaru = item.namabaru ? item.namabaru : '-'
  if(parameterTambahan == "depositPasien"){
    H.printBlade(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}`)
    // qzService.printData(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&user=${userLogin.pegawai.namaLengkap}`, 'KWITANSI', 1)
    // qzService.printData(`report/bukti-kwitansi?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}`, 'FORM KWITANSI', 1)
  } else if(parameterTambahan == "pengembalianDeposit"){
    // qzService.printData(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}`, 'KWITANSI', 1)
    H.printBlade(`report/bukti-kwitansi?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}`)
    // qzService.printData(`report/bukti-kwitansi?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&user=${userLogin.pegawai.namaLengkap}`, 'FORM KWITANSI', 1)
  } else if(parameterTambahan == "tagihanNonLayanan"){
    H.printBlade(`report/bukti-layanan-carabayar-nonlayanan?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}`)
    // qzService.printData(`report/bukti-layanan-carabayar-nonlayanan?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&user=${userLogin.pegawai.namaLengkap}`, 'KWITANSI', 1)
  }else{
    H.printBlade(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&bangsa=${bangsa}`)
    // if(pasien.value.iddepartemen != 16){
    //   // H.printBlade(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&bangsa=${bangsa}`)
    // } else if(pasien.value.iddepartemen == 16){
    //   // qzService.printData(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${NOREC_SP}&user=${userLogin.pegawai.namaLengkap}&bangsa=${bangsa}`, 'KWITANSI', 1)
    // } 

  }
}
const setCache = (e:any) =>{
 H.cacheHelper().set('ruangKasir',e)
}
watch(
  () => options.value,
  (newValue, oldValue) => {
    console.log("OPTIONS", options.value)
    // console.log('newVal', newValue); 
    if(newValue == 'LOST') {
      // changeNomi(0,0)
      for (let x = 0; x < listItem.value.length; x++) {
        const element = listItem.value[x];
        element.nominal = 0;
        disabledAllNominal.value = true;
      }
    }else {
      disabledAllNominal.value = false
    }
  }
)

// qzService.connect()
pasienByPD()
totalBayar()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/kasir/billing';

@import '/@src/scss/module/ext/banking-dashboard';
@import '/@src/scss/module/kasir/pembayaran-tagihan';

.banking-dashboard-v2 .dashboard-card {
    border-radius: 0px !important;
}

.r-card.card-0-radius {
  border-radius: 0px !important;
  height: 390px;
  overflow: scroll !important;
}

.hr-dashboard .block-header {
    display: block !important;
    border-radius: 16px;
    padding: 20px;
    background: var(--primary);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);
}

.hr-dashboard .block-header .center {
    display: flex;
    flex-direction: column;
    width: 100%;
    padding-right: 0;
    margin-right: 0;
    border: none;
}

.hr-dashboard .block-header .left, .hr-dashboard .block-header .right {
    width: 100% !important;
}

</style>

