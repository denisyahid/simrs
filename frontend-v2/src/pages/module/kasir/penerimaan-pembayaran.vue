
    
<template>
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top:15px">
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
                  @click="cetakKwitansi()"> Cetak
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                  @click="simpan()"> Simpan
                </VButton>
              </div>
            </div>
          </div>
        </div>
        <div class="form-body p-2">
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
        </div>
      </div>
    </div>
    <form class="form-layout is-separate">
      <div class="form-outer">
        <div class="form-body" style="padding:0 !important;">
          <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
            <div class="columns is-multiline">
              <div class="column is-4 ">
                <div class="banking-dashboard banking-dashboard-v2">
                  <div class="dashboard-card is-card-panel">
                    <div class="inner-box">
                      <div class="box-title" style="margin-bottom: 0;">
                        <h3>{{ item.title }}</h3>
                      </div>
                      <div class="card-balance-wrap">
                        <div class="card-balance">
                          <span style="font-weight: 700;font-size: 1.8rem;">{{ H.formatRp(item.jumlahBayar, 'Rp.')
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
                          <div class="column is-12">
                            <VField label="Diterima dari">
                              <VControl icon="feather:user">
                                <VInput type="text" v-model="item.diterimaDari" placeholder="Diterima dari" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-8">

                <VCard style="border-top-left-radius: 11px; border-left: solid hsla(323, 100%, 75%, 0.72) 3px;">
                  <div class="columns is-multiline">
                    <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                      <VCard class="is-grey">
                        <div class="columns is-multiline p-1">
                          <div class="column is-5">
                            <VField label="Cara Bayar" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                              <VControl icon="fas fa-credit-card" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.caraBayar" :options="d_CaraBayar" :optionLabel="'carabayar'"
                                  class="is-rounded" placeholder="Cara Bayar" style="width: 100%;" :filter="true" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-5">
                            <VField label="Nominal">
                              <VControl class="prime-auto">
                                <VInput type="text" v-model="item.nominalBayar" v-mask-currency placeholder="Nominal"
                                  class="is-rounded" v-on:input="changeNomi(item.nominalBayar)" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-1 mt-5">
                            <VIconButton v-if="index > 0" outlined type="button" raised circle class="is-pulled-right"
                              icon="feather:trash" @click="removeItem(index)" color="danger">
                            </VIconButton>
                          </div>
                          <div class="column is-1 mt-5">
                            <VIconButton outlined type="button" raised circle class="is-pulled-right" icon="feather:plus"
                              @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah Cara Bayar'">
                            </VIconButton>
                            <!-- <VButton type="button" rounded outlined color="info" raised icon="feather:plus"
                                                                @click="addNewItem()"> Tambah 
                                                              </VButton> -->
                          </div>
                        </div>
                      </VCard>
                    </div>
                    <div class="column is-12  pt-0 mt-0">
                      <VCard style="border-top-left-radius: 11px; border-left: solid hsla(170, 100%, 75%, 0.72) 3px;">
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
                  </div>
                </VCard>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
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
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { useUserSession } from '/@src/stores/userSession'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import InputMask from 'primevue/inputmask';
import * as qzService from '/@src/utils/qzTrayService'
useHead({
  title: 'Verifikasi Tagihan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let NOREC_SPP = useRoute().query.norec_spp as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOMINAL = useRoute().query.nominal as string
let parameterTambahan = useRoute().query.pageFrom as string
let TOTALBAYAR = useRoute().query.totalBayar

let TITLE = ref('')
const isLoadingPasien: any = ref(false)
const item: any = reactive({
  NOREC_SP: NOREC_SPP != undefined ? NOREC_SPP : '',
  tglBayar: new Date(),
  jumlahBayar: NOMINAL,
  totalBayarFix: TOTALBAYAR ? TOTALBAYAR : 0,
  title: 'TOTAL TAGIHAN'
})
const norecSBM: any = ref('')
const isLoadingBill: any = ref(false)
const listChecked: any = ref([])
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
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
const d_CaraBayar: any = ref([])
const pasien: any = ref({})
const isLoading = ref(false)
const router = useRouter()
const listItem: any = ref([
  {
    caraBayar: null,
    nominalBayar: 0,
    nominal: 0,
  }
])

if (parameterTambahan == 'tagihanPasien') {
  TITLE.value = 'Pembayaran Tagihan'
} else if (parameterTambahan == 'depositPasien') {
  TITLE.value = 'Pembayaran Deposit'
} else if (parameterTambahan == 'pengembalianDeposit') {
  TITLE.value = 'Pengembalian Deposit'
} else if (parameterTambahan == 'tagihanNonLayanan') {
  TITLE.value = 'Pembayaran Tagihan Non Layanan'
}
function pasienByPD() {
  if (item.NOREC_PD == '') return
  isLoadingPasien.value = true
  useApi().get(
    `/general/header-pasien?norec_pd=${NOREC_PD}&nominal=${item.jumlahBayar}`).then((response: any) => {
      pasien.value = response.pasien
      item.terbilang = response.nominal
      isLoadingPasien.value = false
    })
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
    nominalBayar: last,
    nominal : parseFloat(last)
  });
  setTotal()
}

const caraBayar = () => {
  useApi().get('/kasir/cara-bayar').then((response) => {
    d_CaraBayar.value = response
    d_CaraBayar.value.forEach((element: any) => {
        if (element.carabayar.toLowerCase().indexOf('tunai') > -1) {
          listItem.value[0].caraBayar = element
          return
        }
      });
    listItem.value[0].nominalBayar = H.formatRupiah(NOMINAL)
    listItem.value[0].nominal = H.unFormatRupiah(NOMINAL)
    item.totalBayarFix = NOMINAL
  })
}

function removeItem(index: any) {
  listItem.value.splice(index, 1)
}

function setTotal() {
  let total = countTotal()
  item.totalBayarFix = total
}

function countTotal() {
  let total = 0
  for (let x = 0; x < listItem.value.length; x++) {
    const element = listItem.value[x];
    element.nominalBayar =  H.unFormatRupiah(element.nominalBayar)
    element.nominal =  H.unFormatRupiah(element.nominalBayar)
    total += parseFloat(element.nominalBayar)
  }
  return total
}

const simpan = async () => {
  if(!listItem.value[0].caraBayar){
    H.alert('error','Cara Bayar Belum Ditentukan')
    return
  }
  isLoading.value = true
  let objSave = {
    jumlahBayar: item.totalBayarFix,
    parameterTambahan: 'cicilanPasien',
    nocm: pasien.value.nocm,
    namapasien: pasien.value.namapasien,
    parameter: {
      noRecStrukPelayanan: item.NOREC_SP,
      tipePembayaran: 'cicilanPasien',
      jumlahBayar: item.jumlahBayar
    },
    pembayaran: listItem.value
  }
  norecSBM.value = ''
  await useApi().post(`/kasir/pembayaran-tagihan/simpan`,objSave).then((response)=>{
    isLoading.value = false
    norecSBM.value = response.sbm.norec
    cetakKwitansi()
    window.history.back()
  })

}

function changeNomi(e: any) {
  e = H.unFormatRupiah(e)
  if (TITLE.value != 'Pembayaran Deposit') {
    let total = countTotal()
    if (total > parseFloat(item.jumlahBayar)) {
      for (let x = 0; x < listItem.value.length; x++) {
        const element = listItem.value[x];
        element.nominalBayar = H.unFormatRupiah(element.nominalBayar)
        if (e == element.nominalBayar) {
          element.nominalBayar = 0
        }
      }
    }
  }
  setTotal()
}
const cetakKwitansi = () => {
  qzService.printData('kasir/daftar-penerimaan/report/kwitansi?pdf=true&norec=' +  norecSBM.value,'KWITANSI', 1)
  // H.printBlade('kasir/daftar-penerimaan/report/kwitansi?norec=' + norecSBM.value);
}
function kembaliKeun() {
  window.history.back()
}

qzService.connect()
pasienByPD()
caraBayar()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/kasir/billing';

@import '/@src/scss/module/ext/banking-dashboard';
@import '/@src/scss/module/kasir/pembayaran-tagihan';
</style>
  
