
<template>
  <VCard>
    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">

              <div class="column is-6 mt-3">
                <VField>
                  <VLabelText style="width: 100%;font-size: 1rem;">Filter Data Pasien Aktif
                  </VLabelText>
                </VField>
              </div>
              <div class="column is-6 ">
                <SelectButton v-model="item.qranapRaj" :options="optionsSelect" aria-labelledby="basic"
                  class="is-pulled-right" />
              </div>
              <div class="column is-6">

                <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VField addons>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                      </VControl>
                      <VControl>
                        <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                      </VControl>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
            </div>
          </div>
          <div class="field">
            <div class="control">
              <div class="columns is-multiline">
                <div class="column is-11 ">
                  <input type="text"  v-model="item.search" v-on:keyup.enter="fetchData" class="input" placeholder="Cari Nama Pasien, No Registrasi, No RM, Atau NIK" />
                </div>
                <div class="column is-1 ">
                  <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                    @click="fetchData()" :loading="isLoading">
                  </VIconButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div class="list-view list-view-v3">
          <VPlaceholderPage v-if="dataSource.length === 0" :title="H.assets().notFound"
            :subtitle="H.assets().notFoundSubtitle" larger>
            <template #image>
              <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>

          <div class="list-view-inner">

            <TransitionGroup name="list-complete" tag="div">
              <!--Item-->
              <div v-for="(item, i) in dataSourcefiltered" :key="item.id" class="list-view-item">
                <div class="list-view-item-inner is-clickable" @click="detailDeposit(item)">
                  <VAvatar size="small" :color="listColor[i]" :initials="item.initials" />
                  <div class="meta-left">
                    <h3>
                      {{ item.namapasien }}
                    </h3>
                    <span>
                      <i class="iconify" data-icon="feather:user" aria-hidden="true"></i>
                      <span>{{ item.nocm }}</span>
                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                      <i class="fas fa-history mr-2" aria-hidden="true"></i>
                      <span>{{ item.noregistrasi }}</span>
                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                      <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                      <span class="txt-elipsis">{{ item.namaruangan }}</span>
                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                      <i class="fas fa-ambulance mr-2" aria-hidden="true"></i>
                      <span>{{ item.kelompokpasien }}</span>
                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                      <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                      <span>{{ H.formatDateIndoSimple(item.tglregistrasi) }}</span>
                    </span>
                  </div>
                  <div class="meta-right">
                    <div class="buttons">
                      <VButton icon="feather:arrow-right" color="primary" outlined raised @click="billing(item)"
                        class="btn-slim"> Biaya Sementara </VButton>
                      <VButton icon="feather:arrow-right" color="primary" outlined raised @click="bayarDeposit(item)"
                        class="btn-slim"> Deposit </VButton>

                    </div>
                  </div>
                </div>
              </div>
            </TransitionGroup>
            <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
              :total-items="dataSource.total" :max-links-displayed="5">
              <template #before-pagination>
              </template>
              <template #before-navigation>
                <VFlex class="mr-4 mt-1" column-gap="1rem">
                  <VField>
                  </VField>
                  <VField>
                    <VControl>
                      <div class="select is-rounded">
                        <select v-model="currentPage.limit">
                          <option :value="1">1 results per page</option>
                          <option :value="5">5 results per page</option>
                          <option :value="10">10 results per page</option>
                          <option :value="15">15 results per page</option>
                          <option :value="25">25 results per page</option>
                          <option :value="50">50 results per page</option>
                        </select>
                      </div>
                    </VControl>
                  </VField>
                </VFlex>
              </template>
            </VFlexPagination>
          </div>
        </div>
      </div>
    </div>
  </VCard>
  <Dialog v-model:visible="modalDetail" modal header="Detail Deposit" :style="{ width: '40vw' }">
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
                    <span class="light-text">{{ H.formatDateNoTime(item.tglsbm) }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text">{{ item.nostruk }}</span>
                  </VFlexTableCell>

                  <VFlexTableCell>
                    <span class="light-text">{{ item.nosbm }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text is-pulled-right">{{ H.formatRp(item.totaldibayar, 'Rp. ') }}</span>
                  </VFlexTableCell>
                  <!-- <VFlexTableCell>
                    <span class="light-text">{{ item.kasir }}</span>
                    </VFlexTableCell> -->
                  <VFlexTableCell :column="{ align: 'end' }">
                    <VIconButton circle icon="fas fa-backward" color="danger" raised bold @click="pengembalian(item)"
                      v-tooltip.bubble="'Pengembalian Deposit'">
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
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import SelectButton from 'primevue/selectbutton';
import Dialog from 'primevue/dialog';
useHead({
  title: 'Daftar Pasien Aktif - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const userLogin = useUserSession().getUser()
const router = useRouter()
const route = useRoute()
const modalDetail = ref(false)
const isLoadingDetail = ref(false)
const dataSourceDetail: any = ref([])
const listColor: any = ref(Object.keys(useThemeColors()))
const optionsSelect = ref(['Rawat Inap', 'Rawat Jalan']);
const item: any = ref({
  qranapRaj: 'Rawat Inap',
  qAktif: true,
  filterDate: new Date(),
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
})
const columns = {
  tglstruk: 'TGL BAYAR',
  nostruk: 'NO DEPOSIT',
  status: 'NO BAYAR',
  totalharusdibayar: {
    label: 'SUBTOTAL',
    cellClass: 'h-hidden-tablet-p',
  },
  // petugasverif: 'PETUGAS',
  actions: {
    label: 'Batal',
    align: 'end',
  },
} as const

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
let dataSource: any = ref([])
let isLoading: any = ref(false)

const dataSourcefiltered = computed(() => {
  if (!item.value.qFilter) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.namapasien.match(new RegExp(item.value.qFilter, 'i')) ||
      items.noregistrasi.match(new RegExp(item.value.qFilter, 'i'))
    )
  })
})

async function fetchData() {

  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit

  let dari = ''
  if (item.value.qFilterTgl) {
    dari = H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.value.qFilterTgl) {
    sampai = H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')
  }
  let jenis = ''
  let search = ''

  if (item.value.search) search = `&search=${item.value.search}`
  if (item.value.qranapRaj) jenis = item.value.qranapRaj

  isLoading.value = true
  dataSource.value = []
  const response = await useApi().get(
    '/kasir/daftar-pasien-aktif?'
    + '&dari=' + dari
    + '&sampai=' + sampai
    + '&search=' + search
    + '&jenis=' + jenis
    + '&offset=' + offset
    + '&limit=' + limit
    + '&rows=' + currentPage.value.rows
  )
  isLoading.value = false
  response.data.forEach((element: any) => {
    element.initials = H.INITIALS(element.namapasien)
  });
  dataSource.value = response.data
  dataSource.value.total = response.count
  route.query.page = '1'
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

function bayarDeposit(e: any) {
  if (e.statusbayar == 'Lunas')
    return
  router.push({
    name: 'module-kasir-pembayaran-tagihan',
    query: {
      norec_sp: e.norec,
      nocmfk: e.nocmfk ?? '',
      norec_pd: e.norec_pd,
      pageFrom: 'depositPasien'
    },
  })
}
const detailDeposit = (async (e: any) => {
  isLoadingDetail.value = true
  dataSourceDetail.value = []
  item.value.totalAll = 0
  await useApi()
    .get('/kasir/daftar-pasien-aktif/detail-deposit?noregistrasi=' + e.noregistrasi)
    .then((response: any) => {
      isLoadingDetail.value = false
      item.value.totalAll = 0
      for (let x = 0; x < response.length; x++) {
        const element = response[x];
        if(element.jenis == 'penerimaan')
          item.value.totalAll = item.value.totalAll + parseFloat(element.totaldibayar)
        else
          item.value.totalAll = item.value.totalAll - parseFloat(element.totaldibayar)
      }
      dataSourceDetail.value = response
    })
  modalDetail.value = true
})
const pengembalian = (async (e: any) => {
  if (e.nosbklastfk != null) {
    H.alert('error', 'Tidak bisa dikembalikan sudah dibayar')
    return
  }

  router.push({
    name: 'module-kasir-pembayaran-tagihan',
    query: {
      norec_pd: e.norec_pd,
      nocmfk: e.nocmfk ?? '',
      totalBayar: - parseFloat(e.totaldibayar),
      pageFrom: 'depositPasien'
    },
  })
})
currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  fetchData();
})
watch(
  () => item.value.qranapRaj,
  (newValue, oldValue) => {
    fetchData()
  }
)
fetchData()
</script>

<style lang="scss">
@import '/@src/scss/main';
@import '/@src/scss/module/kasir/dashboard-kasir';
@import '/@src/scss/module/ext/list-view-3';
</style>
