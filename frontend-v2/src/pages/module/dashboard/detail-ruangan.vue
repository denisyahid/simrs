<template>
  <div class="personal-dashboard personal-dashboard-v2">
    <!--Personal Dashboard V2-->
    <div class="columns is-multiline">
      <div class="column is-6">
        <div class="dashboard-header" style="margin-bottom: -0.3rem">
          <img
            src="/images/avatars/label/diagnosa-tindakan.png"
            alt=""
            srcset=""
            style="max-width: 60%; opacity: 0.8; margin-bottom: -3rem; margin-left: -3rem"
          />
          <div class="user-meta is-dark-bordered-12" style="margin-left: -1rem">
            <h3 class="title is-5 is-narrow is-bold" style="font-size: 1.2rem">
              {{ item.objectruanganfk }}
            </h3>
            <p class="light-text">{{ item.objectdepartemenfk }}</p>
          </div>
        </div>
      </div>

      <div class="column is-3">
        <div class="dashboard-tile">
          <div class="tile-head">
            <h3 class="dark-inverted">Total Pasien</h3>
            <VIconBox
              color="warning"
              size="medium"
              rounded
              style="margin-bottom: -4rem; margin-right: 2rem"
            >
              <i aria-hidden="true" class="fas fa-user-check"></i>
            </VIconBox>
          </div>
          <div class="tile-body" style="margin-bottom: -0.5rem">
            <span class="dark-inverted"> {{ item.totalRegis }} </span>
          </div>
        </div>

        <div class="dashboard-tile" style="margin-top: 0.5rem">
          <div class="tile-head">
            <h3 class="dark-inverted">Dokter</h3>
            <VIconBox
              color="green"
              size="medium"
              rounded
              style="margin-bottom: -4rem; margin-right: 2rem"
            >
              <i aria-hidden="true" class="fas fa-user"></i>
            </VIconBox>
          </div>
          <div class="tile-body" style="margin-bottom: -0.5rem">
            <span class="dark-inverted"> {{ item.totalDokter }}</span>
          </div>
        </div>
      </div>
      <div class="column is-3">
        <div class="dashboard-tile">
          <div class="tile-head">
            <h3 class="dark-inverted">Produk</h3>
            <VIconBox
              color="purple"
              size="medium"
              rounded
              style="margin-bottom: -4rem; margin-right: 2rem"
            >
              <i aria-hidden="true" class="fas fa-box"></i>
            </VIconBox>
          </div>
          <div class="tile-body" style="margin-bottom: -0.5rem">
            <span class="dark-inverted"> {{ item.totalProduk }}</span>
          </div>
        </div>

        <div class="dashboard-tile" style="margin-top: 0.5rem">
          <div class="tile-head">
            <h3 class="dark-inverted">Jumlah Kamar</h3>
            <VIconBox
              color="blue"
              size="medium"
              rounded
              style="margin-bottom: -4rem; margin-right: 2rem"
            >
              <i aria-hidden="true" class="fas fa-home"></i>
            </VIconBox>
          </div>
          <div class="tile-body" style="margin-bottom: -0.5rem">
            <span class="dark-inverted"> {{ item.totalKamar }} </span>
          </div>
        </div>
      </div>

      <div class="column is-8">
        <div
          class="dashboard-card"
          style="min-height: 400px; max-height: 400px; overflow: auto"
        >
          <div class="card-head">
            <h3 class="dark-inverted">Ketersedian Kamar</h3>
            <VControl icon="feather:search">
              <input
                v-model="filters"
                class="input custom-text-filter"
                placeholder="Cari Kamar ..."
              />
            </VControl>
          </div>
          <div class="active-projects">
            <VPlaceholderPage
              :class="[dataSourcefiltered.length !== 0 && 'is-hidden']"
              title=" Tidak Ada Kamar"
            >
              <template #image>
                <img
                  class="light-image"
                  src="/images/avatars/label/no-doktor.png"
                  alt=""
                  style="margin-top: -10rem; max-width: 50%"
                />
                <img
                  class="dark-image"
                  src="/images/avatars/label/no-doktor.png"
                  alt=""
                  style="margin-top: -10rem; max-width: 60%"
                />
              </template>
            </VPlaceholderPage>
            <!--Project-->
            <DataTable
              :value="dataSourcefiltered"
              :paginator="true"
              :rows="10"
              :rowsPerPageOptions="[5, 10, 25]"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack"
              breakpoint="960px"
              sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
              showGridlines
            >
              <Column field="no" header="No"></Column>
              <Column field="namakamar" header="Kamar" :sortable="true"></Column>
              <Column field="namakelas" header="Kelas" :sortable="true"></Column>
              <Column field="jumlakamarisi" header="Kamar Terisi"></Column>
              <Column field="jumlakamarkosong" header="Kamar Kosong"></Column>
            </DataTable>
          </div>
        </div>

        <div class="dashboard-card">
          <div class="card-head">
            <h3 class="dark-inverted">Grafik Kunjungan Pasien</h3>
          </div>
          <ApexChart
            id="completion-chart"
            :height="completionOptions.chart.height"
            :type="completionOptions.chart.type"
            :series="completionOptions.series"
            :options="completionOptions"
          >
          </ApexChart>
        </div>
      </div>
      <div class="column is-4">
        <div
          class="dashboard-card has-margin-bottom"
          style="min-height: 400px; max-height: 400px; overflow: auto"
        >
          <div class="card-head">
            <h3 class="dark-inverted">Jadwal Praktek Dokter</h3>
          </div>
          <div class="tile-grid tile-grid-v2">
            <VPlaceholderPage
              :class="[dataDokter.length !== 0 && 'is-hidden']"
              title=" Tidak Ada Dokter Praktek"
            >
              <template #image>
                <img
                  class="light-image"
                  src="/images/avatars/label/no-doktor.png"
                  alt=""
                  style="margin-top: -8rem; max-width: 85%"
                />
                <img
                  class="dark-image"
                  src="/images/avatars/label/no-doktor.png"
                  alt=""
                  style="margin-top: -8rem; max-width: 85%"
                />
              </template>
            </VPlaceholderPage>
            <!--Tile Grid v1-->
            <TransitionGroup name="list" tag="div" class="columns is-multiline">
              <!--Grid item-->
              <div v-for="item in dataDokter" :key="item.id" class="column is-12">
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner" @click="detailDokter(item)">
                    <VAvatar
                      size="medium"
                      picture="/images/avatars/svg/user2.svg"
                      color="primary"
                      squared
                      bordered
                    />
                    <div class="meta">
                      <span class="dark-inverted">{{ item.namalengkap }}</span>
                      <span>
                        <span>{{ item.hari }}</span>
                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                        <span> {{ item.jammulai }} s.d {{ item.jamakhir }} </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </TransitionGroup>
          </div>
        </div>

        <div class="dashboard-card" style="max-height: 400px; overflow: auto">
          <div class="card-head">
            <h3 class="dark-inverted">Stok Produk</h3>
          </div>
          <div class="column is-12">
            <VControl icon="feather:search">
              <input
                v-model="filters"
                class="input custom-text-filter"
                placeholder="Cari Produk ..."
                is-rounded
              />
            </VControl>
          </div>
          <div class="tile-grid tile-grid-v2">
            <VPlaceholderPage
              :class="[dataStok.length !== 0 && 'is-hidden']"
              title=" Tidak Ada Produk "
            >
              <template #image>
                <img
                  class="light-image"
                  src="/images/avatars/label/no-produk.png"
                  alt=""
                  style="margin-top: -9rem; max-width: 85%"
                />
                <img
                  class="dark-image"
                  src="/images/avatars/label/no-produk.png"
                  alt=""
                  style="margin-top: -9rem; max-width: 85%"
                />
              </template>
            </VPlaceholderPage>

            <!--Tile Grid v1-->
            <TransitionGroup name="list" tag="div" class="columns is-multiline">
              <!--Grid item-->
              <div
                v-for="item in dataSourceStok"
                :key="item.id"
                class="column is-12"
                style="max-height: 500px; overflow: auto"
              >
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner" @click="detailProduk(item)">
                    <VAvatar
                      size="small"
                      picture="/images/avatars/svg/obat.svg"
                      color="primary"
                      squared
                      bordered
                    />
                    <div class="meta">
                      <span class="dark-inverted">{{ item.namaproduk }}</span>
                      <span>
                        <span>{{ item.asalproduk }}</span>
                        <VTag
                          color="purple"
                          label="Tag Label"
                          rounded
                          elevated
                          style="margin-left: 3rem"
                        >
                          {{ item.qtyproduk }}</VTag
                        >
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </TransitionGroup>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div
          class="dashboard-card has-margin-bottom"
          style="min-height: 400px; max-height: 500px; overflow: auto"
        >
          <div class="card-head">
            <h3 class="dark-inverted">Riwayat Pasien</h3>
            <VField style="margin-left: 15rem;">
              <VLabel>Silahkan Pilih Periode</VLabel>
              <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField addons>
                    <VControl icon="feather:calendar">
                      <VInput :value="inputValue.start" v-on="inputEvents.start" />
                    </VControl>
                    <VControl>
                      <VButton static icon="feather:arrow-right" />
                    </VControl>
                    <VControl subcontrol icon="feather:calendar">
                      <VInput :value="inputValue.end" v-on="inputEvents.end" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
            <VField>
            <VButton
              @click="filter()"
              :loading="ds_PASIEN.loading"
              type="button"
              icon="feather:search"
              class="is-fullwidth mr-2"
              color="info"
              raised
            >
              Cari Data
            </VButton>
          </VField>
            
          </div>
        
          <div class="column is-12">
          <div class="user-grid user-grid-v4">
            <VPlaceholderPage id="cek"
              :class="[dataPasien.length !== 0 && 'is-hidden']"
              title=" Tidak Ada Pasien"
              subtitle ="Silakan Pilih Periode Registrasi Pasien Untuk Melihat Data Pasien"
            >
              <template #image>
                <img
                  class="light-image"
                  src="/images/avatars/label/no-doktor.png"
                  alt=""
                  style="margin-top: -9rem; max-width: 50%"
                />
                <img
                  class="dark-image"
                  src="/images/avatars/label/no-doktor.png"
                  alt=""
                  style="margin-top: -6rem; max-width: 60%"
                />
              </template>
            </VPlaceholderPage>
            <!--Tile Grid v1-->
            <TransitionGroup
              name="list"
              tag="div"
              class="columns is-multiline is-flex-tablet-p is-half-tablet-p"
            >
              <!--Grid item-->
              <div v-for="(item, key) in dataPasien" :key="key" class="column is-3">
                <div class="grid-item">
                  <VTag
                    :label="item.namalengkap"
                    style="margin-bottom: 1rem"
                    color="warning"
                    rounded
                  />
                  <VAvatar
                    size="big"
                    picture="/images/avatars/label/pasien.png"
                    squarred
                  />

                  <h3 class="dark-inverted">{{ item.namapasien }}</h3>
                  <p>{{ item.tglregistrasi }}</p>

                  <div class="button-wrap has-text-centered">
                    <VButton color="primary" raised @click="direct(item)" rounded>
                      <span class="icon">
                        <i aria-hidden="true" class="fas fa-edit"></i>
                      </span>
                      <span>Detail</span>
                    </VButton>
                  </div>
                </div>
              </div>
            </TransitionGroup>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Detail Dokter-->
  <VModal
    :open="modalDetailDokter"
    title="Detail Jadwal Dokter"
    actions="right"
    @close="modalDetailDokter = false"
  >
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField>
              <VLabel>Dokter</VLabel>
              <VControl icon="feather:user">
                <VInput
                  type="textarea"
                  v-model="item.objectpegawaifk"
                  placeholder="Dokter"
                  class="is-rounded"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField>
              <VLabel>Hari</VLabel>
              <VControl icon="feather:bookmark">
                <VInput
                  type="textarea"
                  v-model="item.hari"
                  placeholder="Hari"
                  class="is-rounded"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField>
              <VLabel>Jam Buka</VLabel>
              <VControl icon="feather:clock">
                <VInput
                  type="textarea"
                  v-model="item.jammulai"
                  placeholder="Hari"
                  class="is-rounded"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField>
              <VLabel>Jam Tutup</VLabel>
              <VControl icon="feather:clock">
                <VInput
                  type="textarea"
                  v-model="item.jamakhir"
                  placeholder="Hari"
                  class="is-rounded"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField>
              <VLabel>Ruangan</VLabel>
              <VControl icon="feather:home">
                <VInput
                  type="textarea"
                  v-model="item.objectruanganfk"
                  placeholder="Ruangan"
                  class="is-rounded"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
  </VModal>
  <!-- Detail Produk-->
  <VModal
    :open="modalDetailProduk"
    title="Detail Stok Produk"
    actions="right"
    @close="modalDetailProduk = false"
  >
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField>
              <VLabel>Nama Produk</VLabel>
              <VControl icon="feather:user">
                <VInput
                  type="textarea"
                  v-model="item.objectprodukfk"
                  placeholder="Produk"
                  class="is-rounded"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField>
              <VLabel>Asal Produk</VLabel>
              <VControl icon="feather:bookmark">
                <VInput
                  type="textarea"
                  v-model="item.objectasalprodukfk"
                  placeholder="Asal Produk"
                  class="is-rounded"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField>
              <VLabel>Jumlah Stok</VLabel>
              <VControl icon="feather:clock">
                <VInput
                  type="textarea"
                  v-model="item.qtyproduk"
                  placeholder="Jumlah"
                  class="is-rounded"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField>
              <VLabel>Harga Beli</VLabel>
              <VControl icon="feather:clock">
                <VInput
                  type="textarea"
                  v-model="item.harganetto1"
                  placeholder="Harga Beli"
                  class="is-rounded"
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField>
              <VLabel>Harga Jual</VLabel>
              <VControl icon="feather:clock">
                <VInput
                  type="textarea"
                  v-model="item.harganetto2"
                  placeholder="Harga Jual"
                  class="is-rounded"
                />
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'

import type { VAvatarProps } from '/@src/components/base/avatar/VAvatar.vue'
import { completionOptions } from '/@src/data/dashboards/personal-v2/taskCompletionChart'
import { barOptions } from '/@src/data/dashboards/personal-v2/teamEfficiencyChart'
import { popovers } from '/@src/data/users/userPopovers'
import * as usersData from '/@src/data/dashboards/personal-v2/users'
import { useRoute, useRouter } from 'vue-router'
import moment from 'moment'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useHead } from '@vueuse/head'
import { datatableV3 } from '/@src/data/layouts/datatable-v3'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { any } from 'zod'
useHead({
  title: 'Dashboard Ruangan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_RUANGAN = useRoute().query.id as string

const avatarStack1 = usersData.avatarStack1 as VAvatarProps[]
const avatarStack2 = usersData.avatarStack1 as VAvatarProps[]
const avatarStack3 = usersData.avatarStack1 as VAvatarProps[]
const avatarStack4 = usersData.avatarStack1 as VAvatarProps[]
const democheck = ref(['value_2'])
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const item: any = ref({
  aktif: true,
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
let dataSource: any = ref([])
let dataDokter: any = ref([])
let dataStok: any = ref([])
let dataPasien: any = ref([])
let ds_PASIEN: any = ref([])
let isLoading: any = ref(false)
const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }
  return dataSource.value.filter((item: any) => {
    return item.namakamar.match(new RegExp(filters.value, 'i'))
  })
})
const dataSourceStok = computed(() => {
  if (!filters.value) {
    return dataStok.value
  }
  return dataStok.value.filter((item: any) => {
    return item.namaproduk.match(new RegExp(filters.value, 'i'))
  })
})
const modalDetailDokter = ref(false)
const modalDetailProduk = ref(false)
const route = useRoute()
isLoading.value = false

async function DetailRuanganByID(id: any) {
  const { data: ruangan } = await useApi().get(`/dashboard/get-headeru?id=${id}`)
  item.value.objectruanganfk = ruangan[0].namaruangan
  item.value.objectdepartemenfk = ruangan[0].namadepartemen
}

async function fetchDataKamar(id: any) {
  isLoading = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let NmRuangan = ''
  let NmKamar = ''
  if (item.namakamar) NmKamar = '&nmkamar' + item.namakamar
  if (item.namaruangan) NmRuangan = '&nmruangan' + item.namaruangan
  const response = await useApi().get(`/dashboard/get-kamar?id=${id}`)
  isLoading = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x]
    element.no = x + 1
  }

  dataSource.value = response.data
  dataSource.value.total = response.length
}

async function fetchDokter(id: any) {
  isLoading = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let Hari = ''
  let NamaLengkap = ''
  let JamMulai = ''
  let JamAkhir = ''
  if (item.hari) Hari = '&hari' + item.hari
  if (item.namalengkap) NamaLengkap = '&objectpegawaifk=' + item.namalengkap
  if (item.jammulai) JamMulai = '&jammulai=' + item.jammulai

  const response = await useApi().get(
    `/dashboard/get-dokter?id=${id}` + Hari + JamAkhir + JamMulai + NamaLengkap
  )
  isLoading = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x]
    element.no = x + 1
  }

  dataDokter.value = response.data
  dataDokter.value.total = response.length
}
function detailDokter(e: any) {
  item.value.id = e.id
  item.value.objectpegawaifk = e.namalengkap
  item.value.objectruanganfk = e.namaruangan
  item.value.hari = e.hari
  item.value.jammulai = e.jammulai
  item.value.jamakhir = e.jamakhir
  modalDetailDokter.value = true
}
async function fetchStok(id: any) {
  isLoading = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let QtyProduk = ''
  if (item.qtyproduk) QtyProduk = '&qtyproduk' + item.qtyproduk
  const response = await useApi().get(`/dashboard/get-stok?id=${id}`)
  isLoading = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x]
    element.no = x + 1
  }
  dataStok.value = response.data
  dataStok.value.total = response.length
}
function detailProduk(e: any) {
  item.value.id = e.id
  item.value.objectprodukfk = e.namaproduk
  item.value.objectasalprodukfk = e.asalproduk
  item.value.harganetto1 = e.harganetto1
  item.value.harganetto2 = e.harganetto2
  item.value.qtyproduk = e.qtyproduk
  modalDetailProduk.value = true
}

async function fetchJumlah(id: any) {
  await useApi()
    .get(`/dashboard/get-jumlah?objectruanganfk=${id}`)
    .then((response: any) => {
      item.value = response
    })
}
async function fetchPasien(id: any) {
  ds_PASIEN.value.loading = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let NamaPasien = ''
  let NamaLengkap = ''
  let TglRegistrasi = ''
  if (item.namapasien) NamaPasien = '&nocmfk=' + item.namapasien
  if (item.namalengkap) NamaLengkap = '&objectpegawaifk=' + item.namalengkap
  if (item.tglregistrasi) TglRegistrasi = '&tglregistrasi=' + item.tglregistrasi

  const response = await useApi().get(
    `/dashboard/get-riwayat?id=${id}&dari=${moment(item.value.periode.start).format(
      'YYYY-MM-DD'
    )}&sampai=${moment(item.value.periode.end).format('YYYY-MM-DD')}` + NamaLengkap
  )
  ds_PASIEN.value.loading = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x]
    element.no = x + 1
  }

  dataPasien.value = response.data
  dataPasien.value.total = response.length
}

function direct(e: any) {
  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.push({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.id,
    },
  })
}

function filter() {
  fetchPasien(ID_RUANGAN)
}

fetchDataKamar(ID_RUANGAN)
fetchDokter(ID_RUANGAN)
fetchStok(ID_RUANGAN)
fetchJumlah(ID_RUANGAN)
DetailRuanganByID(ID_RUANGAN)
fetchPasien(ID_RUANGAN)
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

.is-navbar {
  .personal-dashboard {
    margin-top: 30px;
  }
}

.personal-dashboard-v2 {
  .dashboard-header {
    @include vuero-s-card;

    display: flex;
    align-items: center;
    padding: 30px;

    .user-meta {
      padding: 0 3rem;
      border-right: 1px solid var(--fade-grey-dark-3) h3 {
        max-width: 180px;
      }
    }

    .user-action {
      padding: 0 3rem;
    }

    .cta {
      position: relative;
      flex-grow: 2;
      max-width: 275px;
      margin-left: auto;
      background: var(--primary-light-8);
      padding: 20px;
      border-radius: var(--radius-large);
      box-shadow: var(--primary-box-shadow);

      .lnil,
      .lnir {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        font-size: 4rem;
        opacity: 0.3;
      }

      .link {
        font-family: var(--font-alt);
        display: block;
        font-weight: 500;
        margin-top: 0.5rem;

        &:hover,
        &:focus {
          color: var(--smoke-white);
          opacity: 0.6;
        }
      }
    }
  }

  .dashboard-card {
    @include vuero-s-card;

    padding: 30px;

    &:not(:last-child) {
      margin-bottom: 1.5rem;
    }

    .card-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0;
      }
    }

    .active-projects,
    .active-team,
    .active-list {
      padding: 10px 0;
    }
  }
}
.dashboard-tile {
  @include vuero-s-card;

  font-family: var(--font);

  .tile-head {
    display: flex;
    align-items: center;
    justify-content: space-between;

    h3 {
      font-family: var(--font-alt);
      color: var(--dark-text);
      font-weight: 600;
    }
  }

  .tile-body {
    font-size: 2rem;
    padding: 4px 0 8px;

    span {
      color: var(--dark-text);
      font-weight: 600;
    }
  }

  .tile-foot {
    span {
      &:first-child {
        font-weight: 500;

        svg {
          height: 16px;
          width: 16px;
          margin-right: 6px;
          stroke-width: 3px;
        }
      }

      &:nth-child(2) {
        color: var(--light-text);
        font-size: 0.9rem;
      }
    }
  }
}

.page-placeholder .placeholder-content h3 {
  font-size: 1rem !important;
}
.is-dark {
  .personal-dashboard-v2 {
    .dashboard-header,
    .dashboard-card {
      @include vuero-card--dark;
    }

    .home-header {
      .cta {
        background: var(--primary-light-2);
        box-shadow: var(--primary-box-shadow);
      }
    }
  }
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
        width: 50px;
        height: 50px;
        min-width: 50px;
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
            font-size: 0.8rem;
          }

          &:nth-child(2) {
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.7rem;
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

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }

  .tile-grid-v2 {
    .tile-grid-item {
      @include vuero-card--dark;

      &:hover,
      &:focus {
        border-color: var(--primary) !important;
      }
    }
  }
}

.user-grid-v4 {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.9 rem !important;
  }

  .grid-item {
    position: relative;
    @include vuero-s-card;

    text-align: center;

    &:hover,
    &:focus {
      .button-wrap {
        > div {
          a {
            opacity: 1;
            pointer-events: all;
          }
        }
      }
    }

    .dropdown {
      position: absolute;
      top: 10px;
      right: 10px;
      text-align: left;
    }

    > .v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.8rem;
    }

    .button-wrap {
      margin: 20px 0 0;

      .v-button {
        width: 100%;
        max-width: 140px;
        margin: 0 auto;
      }

      > div {
        margin: 6px 0 0;

        a {
          opacity: 0;
          pointer-events: none;
          color: var(--light-text);
          font-weight: 500;
          font-size: 0.9rem;
          transition: opacity 0.3s, color 0.3s;

          &:hover,
          &:focus {
            color: var(--primary);
          }
        }
      }
    }
  }
}

.is-dark {
  .user-grid-v4 {
    .grid-item {
      @include vuero-card--dark;
    }
  }
}

@media only screen and (max-width: 767px) {
  .personal-dashboard-v2 {
    .dashboard-header {
      flex-direction: column;
      text-align: center;

      .v-avatar {
        margin-bottom: 10px;
      }

      .user-meta {
        padding-top: 10px;
        padding-bottom: 10px;
        border: none;
      }

      .user-action {
        padding-bottom: 30px;
      }

      .cta {
        margin-left: 0;
      }
    }

    .active-projects {
      .media-flex-center {
        .flex-end {
          .avatar-stack {
            display: none;
          }
        }
      }
    }
  }
}
</style>
