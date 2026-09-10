
    
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
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                  @click="bayar()" :disabled="isDisable"> Bayar
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
            <!-- <div class="columns is-multiline">
              
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
                                <VInput type="text" v-model="item.nominal" v-on:input="changeNomi(item.nominal)"
                                  v-mask-currency placeholder="Nominal" class="is-rounded" />
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
            </div> -->
            <div class="column is-12 p-0">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status primary">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">TOTAL TAGIHAN</span>
                    </div>
                    <small class="text-bold-custom h-100 pt-2">{{
                      H.formatRp(item.totalTagihan, 'Rp.')
                    }}</small>
                  </VCardCustom>
                </div>
                <div class="column is-4">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status info">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">SUDAH DIBAYAR</span>
                    </div>
                    <small class="text-bold-custom h-100 pt-2">{{
                      H.formatRp(item.sudahDibayar, 'Rp.')
                    }}</small>
                  </VCardCustom>
                </div>
                <div class="column is-4">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status danger">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">SISA TAGIHAN</span>
                    </div>
                    <small class="text-bold-custom h-100 pt-2">{{
                      H.formatRp(item.sisaTagihan, 'Rp.')
                    }}</small>
                  </VCardCustom>
                </div>
              </div>
            </div>
            <div class="column is-12 pl-0 pr-0">
              <div class="columns is-multiline">
                <div class="column is-8">
                  <VCard>
                    <div class="column c-title pt-2 mb-5">
                      <label style="font-size:15px;font-weight:bold">Daftar Tagihan</label>
                    </div>
                    <div class="column">
                      <DataTable :value="sourceRiwayatPembayaran" :paginator="true" :rows="5"
                        :rowsPerPageOptions="[5, 10, 25]" :loading="penerimaanSuplier" class="p-datatable-sm"
                        tableStyle="min-width: 10rem"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                        showGridlines>
                        <Column field="noSbm" header="Nomor SBM" style="min-width: 80px;"></Column>
                        <Column field="tglPembayaran" header="Tanggal Pembayaran" style="min-width: 50px;"></Column>
                        <Column field="jlhPembayaran" header="Jumlah Pembayaran" style="min-width: 150px;">
                          <template #body="slotProps">
                            {{ formatRp(slotProps.data.jlhPembayaran, '') }}
                          </template>
                        </Column>
                      </DataTable>
                    </div>
                  </VCard>
                </div>
                <div class="column">
                  <VCard>
                    <div class="column c-title pt-2 mb-5">
                      <label style="font-size:15px;font-weight:bold">Jumlah yang Ingin Dibayar</label>
                    </div>
                    <VField>
                      <VLabel class="required-field">Nominal ingin dibayar</VLabel>
                      <VControl :loading="isLoadPrice">
                        <input v-model="item.nominalDibayar" v-on:input="changeNomi(item.nominalDibayar)"
                          class="input is-rounded" />
                      </VControl>
                    </VField>
                  </VCard>
                </div>
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
import Column from 'primevue/column'
import DataTable from 'primevue/datatable'
import Calendar from 'primevue/calendar';
import InputMask from 'primevue/inputmask';
import { formatRp } from '/@src/utils/appHelper'
useHead({
  title: 'Verifikasi Tagihan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let NOREC_SPP = useRoute().query.norec_spp as string
let NOREC_PD = useRoute().query.norec_pd as string
let parameterTambahan = useRoute().query.pageFrom as string
let TOTALBAYAR = useRoute().query.totalBayar

let TITLE = ref('')
const isLoadingPasien: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_SPP: NOREC_SPP != undefined ? NOREC_SPP : '',
  tglBayar: new Date(),
  totalBayarFix: TOTALBAYAR ? TOTALBAYAR : 0,
  title: 'TOTAL HARUS BAYAR'
})
const norecSBM: any = ref('')
const isLoadingBill: any = ref(false)
const listChecked: any = ref([])
const sourceRiwayatPembayaran: any = ref([])
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
const isDisable = ref(false)
const router = useRouter()
const listItem: any = ref([
  {
    caraBayar: null,
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
    `/general/header-pasien?norec_pd=${item.NOREC_PD}`).then((response: any) => {
      pasien.value = response.pasien
      isLoadingPasien.value = false
    })
}

async function totalBayar() {
  await useApi().get('/kasir/detail-piutang-pasien?norec_spp=' + item.NOREC_SPP).then((response) => {
    item.totalTagihan = response.totalTagihan
    item.sisaTagihan = response.sisaPiutang
    item.sudahDibayar = response.sudahDibayar
    if (response.sisaPiutang == 0) {
      isDisable.value = true
    }
    sourceRiwayatPembayaran.value = response.detailPembayaran
  })

}


const bayar = () => {
  router.push({
    name: 'module-kasir-penerimaan-pembayaran',
    query: {
      norec_spp: item.NOREC_SPP,
      nominal: item.nominal,
      norec_pd: NOREC_PD,
    },
  })
}

const changeNomi = (e) => {
  item.nominalDibayar = H.formatRupiah(e)
  item.nominal = H.unFormatRupiah(e)
}

function kembaliKeun() {
  window.history.back()
}

// watch(
//   () => item.nominalDibayar,
//   () => {
//     H.formatRupiah(item.nominalDibayar)
//     console.log(H.formatRupiah(item.nominalDibayar))
//   }
// )


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
</style>
  
