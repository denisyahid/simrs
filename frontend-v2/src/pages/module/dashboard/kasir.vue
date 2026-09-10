<template>
  <ConfirmDialog />
  <div class="soccer-dashboard">
    <div class="soccer-dashboard-inner">
      <div class="columns">
        <div class="column is-12">
          <div class="columns">
            <div class="column is-4" style="display: none !important">
              <div class="live-match mb-0">
                <div class="head">
                  <h3 class="title is-6">
                    &nbsp;
                    <VTag @click="modalFilter = true" color="danger" rounded elevated class="is-pulled-right
                      is-clickable">{{
                        H.formatDateNoTime(item.filterDate.start) != H.formatDateNoTime(item.filterDate.end) ?
                        H.formatDateNoTime(item.filterDate.start) + ' - ' +
                        H.formatDateNoTime(item.filterDate.end) : H.formatDateNoTime(item.filterDate.start)
                      }} <i class="fas fa-filter ml-3 " aria-hidden="true"></i></VTag>
                  </h3>
                </div>
                <div class="match">
                  <div class="left">
                    <img class="team-logo" src="/images/avatars/svg/totalpasien.png" alt="" />
                    <span class="team-name">Total Pasien</span>
                  </div>
                  <div class="center">
                    <span class="score">{{ item.totalPending }}</span>
                    <span class="separator">:</span>
                    <span class="score">{{ item.totalSelesai }}</span>
                  </div>
                  <div class="right">
                    <img class="team-logo" src="/images/avatars/svg/kwitansi.png" alt="" />
                    <span class="team-name">Total Kwitansi</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-8 h-100" style="display: none !important">
              <div class="illustration-header-2" style="height: 100%">
                <div class="header-image">
                  <img src="/@src/assets/illustrations/dashboards/lifestyle/kasir.png" alt=""
                    style="max-width:85%; margin-left: 4rem; margin-top: 1.5rem;" />
                </div>
                <div class="header-meta">
                  <p><i class="fas fa-cash-register"></i> Kasir </p>
                  <h3 style="color:white"> Layanan Kasir</h3>
                  <p>
                    Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12 pt-0">  
            <TabView class="tabview-custom mt-3" :scrollable="true" @tab-click="klikTab($event)" :activeIndex="1">
              <TabPanel>
                <template #header>
                  <span>Daftar Pasien Pulang</span>
                </template>
                <DaftarPasienPulang v-if="activeTab == 0" />
              </TabPanel>
              <TabPanel>
                <template #header>
                  <span>Daftar Pasien Belum Verif</span>
                </template>
                <DaftarBelumVerif v-if="activeTab == 1" />
              </TabPanel>
              <TabPanel>
                <template #header>
                  <span>Daftar Pasien Sudah Verif</span>
                </template>
                <DaftarSudahVerif v-if="activeTab == 2" />
              </TabPanel>
              <TabPanel>
                <template #header>
                  <span>Daftar Tagihan Non Layanan</span>
                </template>
                <DaftarTagihanNonLayanan :hideColumn="true" v-if="activeTab == 3" />
              </TabPanel>
              <TabPanel>
                <template #header>
                  <span>Input Deposit</span>
                </template>
                <DaftarPasienAktif v-if="activeTab == 4" />
              </TabPanel>
              <TabPanel>
                <template #header>
                  <span>Daftar Deposit Pasien</span>
                </template>
                <DaftarDepositPasien v-if="activeTab == 5" />
              </TabPanel>
              <TabPanel>
                <template #header>
                  <span>Daftar Piutang</span>
                </template>
                <DaftarPiutangPasien :hideColumn="true" v-if="activeTab == 6" />
              </TabPanel>
              <TabPanel>
                <template #header>
                  <span>Laporan</span>
                </template>
                <DaftarPenerimaan :hideColumn="true" v-if="activeTab == 7" />
              </TabPanel>
              <TabPanel>
                <template #header>
                  <span>Riwayat Openbill</span>
                </template>
                <DaftarOpenBill :hideColumn="true" v-if="activeTab == 8" />
              </TabPanel>
            </TabView>
  
            <!--
            <VTabs slider centered selected="tagihan" :tabs="[
              { label: 'Daftar Tagihan Pasien', value: 'tagihan' },
              { label: 'Daftar Tagihan Non Layanan', value: 'nonlayanan' },
              { label: 'Daftar Pasien Aktif', value: 'pasienaktif' },
              { label: 'Daftar Piutang', value: 'piutang' },
              { label: 'Daftar Penerimaan', value: 'sbm' },
  
            ]" style="margin-top: 2rem;;">
              <template #tab="{ activeValue }">
                <p v-if="activeValue === 'tagihan'">
                <div class="matches-card">
                  <DaftarTagihan></DaftarTagihan>
                </div>
                </p>
                <p v-else-if="activeValue === 'piutang'">
  
                </p>
                <p v-else-if="activeValue === 'sbm'">
                  <DaftarPenerimaan :hideColumn="true"></DaftarPenerimaan>
                </p>
              </template>
            </VTabs> -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <VModal :open="modalFilter" title=" Periode" :noclose="false" size="small" actions="right" @close="modalFilter = false">
    <template #content>
      <form class="modal-form">
        <div class="columns">
          <div class="column is-12" style="text-align: center">
            <VField class="is-centered">
              <v-date-picker v-model="item.filterDate" is-range class="is-centered" trim-weeks :max-date="new Date()" />
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:search" @click="fetchTotal()" :loading="isLoading" color="primary" raised>
        Filter</VButton>
    </template>
  </VModal>
  <Dialog v-model:visible="modalDetail" modal header="Detail Verifikasi" :style="{ width: '40vw' }">
    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="flex-list-inner mb-2 mt-5" v-if="isLoadingDetail">
          <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
            <VPlaceloadWrap>
              <VPlaceloadAvatar size="small" />
              <VPlaceloadText last-line-width="60%" class="mx-2" />
              <VPlaceload class="mx-2" disabled />
              <VPlaceload class="mx-2 h-hidden-tablet-p" />
              <VPlaceload class="mx-2 h-hidden-tablet-p" />
              <VPlaceload class="mx-2" />
            </VPlaceloadWrap>
          </div>
        </div>
        <div class="flex-list-inner" v-else-if="dataSourceDetail.length === 0">
          <VPlaceholderSection :title="H.assets().notFound" class="my-6">
            <template #image>
              <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" style="width: 100px;" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                style="width: 100px;" />
            </template>
          </VPlaceholderSection>
        </div>
        <div v-else-if="dataSourceDetail.length > 0" style="max-height: 400px;overflow-x: auto;padding: 10px;">
          <VFlexTable v-if="dataSourceDetail.length" :data="dataSourceDetail" :columns="columns" rounded>
            <template #body>
              <div name="list" tag="div" class="flex-list-inner">
                <!--Table item-->
                <div v-for="(item, i)  in dataSourceDetail" :key="item.id" class="flex-table-item">

                  <VFlexTableCell>
                    <span class="light-text">{{ H.formatDateNoTime(item.tglstruk) }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text">{{ item.nostruk }}</span>
                  </VFlexTableCell>

                  <VFlexTableCell>
                    <VTag :label="item.status" color="warning" rounded elevated
                      style="font-size:0.8rem;font-weight: bold;" v-if="item.status == 'Belum Bayar'" />
                    <VTag :label="item.status" color="success" rounded elevated
                      style="font-size:0.8rem;font-weight: bold;" v-if="item.status == 'Lunas'" />
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text is-pulled-right">{{ H.formatRp(item.totalharusdibayar, 'Rp. ') }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text">{{ item.petugasverif }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell :column="{ align: 'end' }">
                    <VIconButton circle icon="fas fa-times-circle" color="danger" raised bold @click="batalVerif(item)"
                      v-tooltip.bubble="'Batal Verifikasi'">
                    </VIconButton>
                  </VFlexTableCell>
                </div>
              </div>
            </template>
          </VFlexTable>
          <VCard>
            <div class="columns is-multiline">
              <div class="column is-4 is-offset-8">
                <b>TOTAL : {{ H.formatRp(item.totalAll, 'Rp. ') }}</b>
              </div>
            </div>
          </VCard>

        </div>
      </div>
    </div>
  </Dialog>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import MultiSelect from 'primevue/multiselect';
import Calendar from 'primevue/calendar';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import TabView from 'primevue/tabview';
import AutoComplete from 'primevue/autocomplete';
import TabPanel from 'primevue/tabpanel';
import DaftarTagihan from '../kasir/daftar-tagihan.vue';
import DaftarTagihanNonLayanan from '../kasir/daftar-tagihan-non-layanan.vue';
import DaftarPasienAktif from '../kasir/daftar-pasien-aktif-kasir.vue';
import DaftarDepositPasien from '../kasir/daftar-deposit-pasien.vue';
import DaftarPasienPulang from '../kasir/daftar-pasien-pulang.vue';
import DaftarBelumVerif from '../kasir/daftar-belum-verif.vue';
import DaftarSudahVerif from '../kasir/daftar-sudah-verif.vue';
import DaftarPenerimaan from '../kasir/daftar-penerimaan.vue';
import DaftarOpenBill from '../kasir/daftar-riwayat-openbill.vue';
import DaftarPiutangPasien from '../kasir/daftar-piutang-pasien.vue';
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
// const daftarTagihan = () => import('../kasir/daftar-tagihan.vue')


useHead({
  title: 'Dashboard Kasir - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const activeTab = ref(0)
const router = useRouter()
const modalInput = ref(false)
const modalDetail = ref(false)
const isLoadingDetail = ref(false)
const dataSourceDetail: any = ref([])
const d_Ruangan: any = ref([])
let sourceRuangan: any = ref([])
const d_KelompokPasien: any = ref([])
const confirm = useConfirm();
const item: any = ref({
  qAktif: true,
  filterDate: reactive({
    start: new Date(),
    end: new Date(),
  }),
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
  qPeriode: [
    new Date(),
    new Date()
  ],
  totalPending: 0,
  totalSelesai: 0,
  c_antrian: 0,
  c_dilayani: 0,
  c_registrasi: 0,
  c_reservasi: 0,
  totalAll: 0,
  qRows: 50
})


const currentPage: any = ref({
  limit: 5,
  rows: 50,
  activeTab: activeTab
})
const columns = {
  tglstruk: 'TGL',
  nostruk: 'NO VERIF',
  status: 'STATUS',
  totalharusdibayar: {
    label: 'SUBTOTAL',
    cellClass: 'h-hidden-tablet-p',
  },
  petugasverif: 'PETUGAS',
  actions: {
    label: 'Batal',
    align: 'end',
  },
} as const
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let isLoading: any = ref(false)
const filters = ref('')


const dataSourcePulang_F = computed(() => {
  if (!item.value.search) {
    return dataSourcePulang.value
  }

  let key = new RegExp(item.value.search, 'i')
  return dataSourcePulang.value.filter((item: any) => {
    return (
      item.namapasien.match(key) ||
      item.nocm.match(key) ||
      item.noregistrasi.match(key) ||
      item.noregistrasi.match(key)
    )
  })
})

const dataTagihanLunas = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return items.namapasien.match(new RegExp(filters.value, 'i'))
  })
})

const route = useRoute()
isLoading.value = false

async function fetchJumlah() {
  // await useApi()
  //   .get(`/dashboard/order-today`)
  //   .then((response: any) => {
  //     item.value = response
  //   })
}

async function fetchTotal() {

  let dari = H.formatDate(item.value.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.value.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true
  item.value.totalPending = 0
  item.value.totalSelesai = 0
  const response = await useApi().get(`/dashboard/kasir?dari=${dari}&sampai=${sampai}`)
  item.value.totalPending = response.c_total
  item.value.totalSelesai = response.c_lunas
  isLoading.value = false
  modalFilter.value = false
}

async function fetchDropdown() {
  const response = await useApi().get(`/dashboard/dropdown-rawat-jalan`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  if(H.cacheHelper().get('ruanganDipilih') && H.cacheHelper().get('ruanganDipilih').length > 0) {
    sourceRuangan.value = H.cacheHelper().get('ruanganDipilih')
  }else {
    d_Ruangan.value.forEach((element) => {
      sourceRuangan.value.push(element)
    })
  }
}

async function fetchPulang() {
  let dari = ''
  if (item.value.qFilterTgl) {
    dari = H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.value.qFilterTgl) {
    sampai = H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')
  }

  let pasien = item.value.qnama ? `&namapasien=${item.value.qnama}` : ''
  let nocm = item.value.qnocm ? `&nocm=${item.value.qnocm}` : ''
  let noreg = item.value.qnoreg ? `&noreg=${item.value.qnoreg}` : ''
  let ruangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan.value}` : ''
  let kelompokpasien = item.value.kelompokpasien ? `&kelompokpasienfk=${item.value.kelompokpasien.value}` : ''
  let rows = item.value.qRows ? `&rows=${item.value.qRows}` : ''
  let search = item.value.search ? `&search=${item.value.search}` : ''

  console.log(sourceRuangan.value)

  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
          itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
          ruanganid = `&ruanganid=${itemsRuang}`
  }

  isLoading.value = true
  await useApi().get(`/dashboard/daftar-pasien-pulang?dari=${dari}&sampai=${sampai}${pasien}${nocm}${noreg}${ruangan}${ruanganid}${kelompokpasien}${rows}${search}`).then((response: any) => {
      isLoading.value = false
      dataSourcePulang.value = response
    })

}

const changeRuang = async (e: any) => {
        // setCache(e)
        H.cacheHelper().set('ruanganDipilih', sourceRuangan.value)
        fetchPulang()
        // fetchReservasi()
        // fetchJadwalDokter(items)
    }

 async function verifikasi(e: any) {
    await H.statusClosingPasien(e.noregistrasi);
    router.push({
      name: 'module-kasir-verifikasi-tagihan',
      query: {
        nocmfk: e.nocmfk,
        norec_pd: e.norec_pd,
      },
    });
}
function billing(e: any) {
  useApi().post('/general/save-jurnal-pelayananpasien_t-noreg', {'noregistrasi' : e.noregistrasi} )
  router.push({
    name: 'module-kasir-billing',
    query: {
      norec_pasien_daftar: e.norec_pd,
    },
  })
}

function batalPulang(e: any) {

  useApi().post('/rawatinap/batal-pulang-pasien',{'norec_pd' : e.norec_pd}).then((response)=>{
    fetchPulang()
  })
}

async function detailVerifikasi(e: any) {
  isLoadingDetail.value = true
  dataSourceDetail.value = []
  item.value.totalAll = 0
  await useApi()
    .get('/dashboard/daftar-pasien-pulang/detail-verif?noregistrasi=' + e.noregistrasi)
    .then((response: any) => {
      isLoadingDetail.value = false
      for (let x = 0; x < response.length; x++) {
        const element = response[x];
        item.value.totalAll = item.value.totalAll + parseFloat(element.totalharusdibayar)
      }
      dataSourceDetail.value = response
    })
  modalDetail.value = true
}
async function batalVerif(e: any) {
  let json = {
    'norec_pd': e.norec_pd,
    'norec_sp': e.norec
  }
  isLoadingDetail.value = true
  await useApi()
    .post('/dashboard/daftar-pasien-pulang/batal-verif', json)
    .then((response: any) => {
      isLoadingDetail.value = false
      // detailVerifikasi(e)
      fetchPulang()
    }, (err => {
      isLoadingDetail.value = false
    }))

}

function klikTab(e: any) {
  activeTab.value = e.index
}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const fetchKelompokPasien = async (filter: any) => {
    await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
    })
}
const batalClosingPemeriksaan = async (data: any) => {
  if (data.statusbayar == "Belum Verifikasi" ) {
    H.alert("warning","Pasien Belum DiVerifikasi !");
    return;
  }
  saveClosingPemeriksaan(data,false);
}
const closingPemeriksaan = async (data: any) => {
  const tindakan = await useApi().get(`kasir/list-tindakan-pasien?norec_pd=${data.norec_pd}`)
  if(tindakan.length > 0){
    H.alert("error","Ada tindakan yang belum terverifikasi !");
    return;
  }
  if (data.statusbayar == "Belum Verifikasi" ) {
    H.alert("warning","Pasien Belum DiVerifikasi !");
    return;
  }
  const response = await useApi().get(`general/get-penunjang-close?noregistrasi=${data.norec_pd}`)
  let msg = response.length > 0 ? `Pasien ${response[0].namapasien} masih memiliki order penunjang. closing Pemeriksaan pasien, Lanjut simpan ?` : 'Selesai Periksa akan menutup / closing Pemeriksaan pasien, Lanjut simpan ?';
  confirm.require({
        message:msg,
        header: 'Konfirmasi',
        icon: 'pi pi-check-circle',
        acceptClass: 'p-button-success',
        accept: () => {
          saveClosingPemeriksaan(data ,true)
        },
        reject: () => { },
    })
}

const saveClosingPemeriksaan = async (data:any,status:any)=>{
  let objectSave ={
    close : status,
    noregistrasi : data.noregistrasi,
  }
  await useApi().post('/kasir/closing-pemeriksaan', objectSave).then((response: any) => {
    fetchPulang()
    H.cacheHelper().set('status_closing',null)
  })
}
watch(
  () => item.value.qFilterTgl,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchPulang()
    }
  }
)

watch(
  () => sourceRuangan.value,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchPulang()
    }
  }
)

onMounted(() => {
  // activeTab.value = 1;
  let ss = {
    index: 1
  }
  klikTab(ss);
  fetchTotal()
  fetchPulang()
  fetchDropdown()
  fetchJumlah()
  // if(activeTab.value == 0) {
  // }
})
</script>

<style lang="scss">
@import '/@src/scss/module/kasir/dashboard-kasir';

.form-layout .form-outer .form-body {
  padding: 0;
}

.p-tabview .p-tabview-nav li.p-highlight .p-tabview-nav-link {

  font-weight: bold;
}
</style>
