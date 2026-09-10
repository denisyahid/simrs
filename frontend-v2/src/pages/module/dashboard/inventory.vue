<template>
  <div class="business-dashboard hr-dashboard">
    <div class="columns is-multiline">
      <div class="column is-7 mt-3">
        <div class="columns is-multiline">
          <div class="block-header" style="background-color: #215E43;height: 22rem;">
            <div class="column is-6" style="padding-top: 5rem">
              <img src="/images/avatars/label/dashboard/logistik.png">
            </div>
            <div class="column is-6" style="padding-top: 6rem;">
              <span style="color:#F7F7F7"><i class="fas fa-boxes mr-2" aria-hidden="true" style="color:#F7F7F7"></i>
                Inventory</span>
              <h3 class="pt-3 pb-1"
                style="color:#FCFCFC; font-family:Montserrat;font-style: normal;font-weight: 700;font-size: 22.7301px;line-height: 28px;">
                Gudang Inventory</h3>
              <span style="color:#F7F7F7">Selamat Datang , {{ userLogin.pegawai.namaLengkap }}</span>
              <VField class="column is-10  is-autocomplete-select p-0 mt-3">
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.filterRuangan" placeholder="Pilih Ruangan" :searchable="true"
                    :options="d_Ruangan" @select="changeRuang(item.filterRuangan)" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

      </div>

      <div class="column is-5">
        <div class="dashboard-card is-gauge" style="height: 22rem;">
          <div class="column border-custom">
            <span style="font-weight: bold; font-size: 15px">Medis Non Medis
            </span>
          </div>
          <ApexChart height="220" style="height: 220px;" type="bar" :series="chartMedisNonMedis.series"
            :options="chartMedisNonMedis">
          </ApexChart>
        </div>
      </div>
    </div>
  </div>


  <div class="columns" style="margin-top:3rem">
    <div class="column is-7 p-0">
      <VCard style="height: 58px;" />
      <VTabs slider selected="permintaan" :tabs="[
        { label: 'Permintaan', value: 'permintaan' },
        { label: 'Penerimaan', value: 'penerimaan' },
      ]" style="margin-top: -57px;padding:5px">
        <template #tab="{ activeValue }">
          <p v-if="activeValue === 'permintaan'">
            <VCard class=custom-tab-search>
              <VButton color="primary" raised style="left: 36.5rem;top: -2.6rem;padding: 15px;font-size: 14px;"
                @click="addRequest">
                <i class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Permintaan
              </VButton>
              <UIWidget class="search-widget p-0" style="border:#FCFCFC;position:relative;top:-23px">
                <template #body>
                  <div class="field">
                    <div class="control">
                      <input v-model="filters" type="text" class="input" placeholder="Cari Data Order Laboratorium" />
                      <button class="searcv-button">
                        <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                      </button>
                    </div>
                  </div>
                  <div class="field">
                    <div class="control">
                      <VRadio v-model="order" value="0" label="Pending" name="outlined_radio" color="warning"
                        @change="changeSwitch(order)" />
                      <VRadio v-model="order" value="1" label="Verifikasi" name="outlined_radio" color="success"
                        @change="changeSwitch(order)" />
                      <VRadio v-model="order" value="2" label="Success" name="outlined_radio" color="primary"
                        @change="changeSwitch(order)" />
                    </div>
                  </div>
                </template>
              </UIWidget>
            </VCard>

          <div class="column is-12 p-0" style="overflow: scroll;height: 44rem;">

            <div class="column is-12 p-0 pb-3" v-if="requestLenght > 0" v-for="(items) in dataOrder" :key="items.id">
              <VCard style="cursor:pointer" @click="detailPermintaan(items)">
                <div class="columns is-multiline">
                  <div class="column is-1" style="padding-top:37px">
                    <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared bordered />
                  </div>
                  <div class="column is-11" style="padding-left: 23px;">
                    <label style="font-wight:400">Keterangan</label>
                    <h3 class="field mt-1">{{ items.keterangan }}</h3>
                    <div class="columns is-multiline">
                      <div class="column is-4">
                        <label style="font-wight:400">Unit Pengorder</label>
                        <h3 class="field mt-1">{{ items.ruangan }}</h3>
                      </div>
                      <div class="column is-4">
                        <label style="font-wight:400">Tanggal order</label>
                        <h3 class="field mt-1">{{ items.tglOrder }}</h3>
                      </div>
                      <div class="column is-4">
                        <label style="font-wight:400;display:block;margin-left: 8rem;">Status</label>
                        <VTag color="warning" v-if="items.statusOrder == 0" label="Pending"
                          style="margin-left: 7.4rem;color: seashell;font-weight: 500;" rounded />
                        <VTag color="primary" v-if="items.statusOrder == 1" label="Verify"
                          style="margin-left: 7.4rem;color: seashell;font-weight: 500;" rounded />
                        <VTag color="success" v-if="items.statusOrder == 2" label="Success"
                          style="margin-left: 7.4rem;color: seashell;font-weight: 500;" rounded />
                      </div>
                    </div>
                  </div>
                </div>
              </VCard>
            </div>

            <div v-else class="p-0" style="margin-top: -58px;">
              <div class="column p-0 m-0" style="display: flex;justify-content: center;">
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                  style="max-width: 38%;margin-top: 2rem;" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                  style="max-width: 38%;margin-top: 2rem;" />
              </div>
              <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">
                Daftar Permintaan Saat ini tidak tersedia</h3>
            </div>
          </div>
          </p>

          <p v-else-if="activeValue === 'penerimaan'">
          <div class="column is-5" style="margin-left: 24rem;margin-bottom: 20px;padding: 0px;margin-top: -4rem;">
            <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField addons>
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue.start" v-on="inputEvents.start" />
                  </VControl>
                  <VControl>
                    <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                  </VControl>
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue.end" v-on="inputEvents.end" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
            <VIconButton style="margin-left: 20rem;margin-top: -48px;height: 36px;" @click="filter()" color="primary"
              outlined icon="feather:search" />
          </div>
          <!-- <VIconButton color="info" light icon="fab fa-search" /> -->
          <div class="column is-12 p-0 pb-3" v-if="isData > 0" v-for="(items) in dataPenerimaan" :key="items.id">
            <VCard style="cursor:pointer" @click="detailPenerimaan(items)">
              <div class="columns is-multiline">
                <div class="column is-1" style="padding-top:37px">
                  <VAvatar size="medium" picture="/images/avatars/svg/lab.svg" squared bordered />
                </div>
                <div class="column is-11" style="padding-left: 23px;">
                  <label style="font-wight:400">Rekanan</label>
                  <h3 class="field mt-1">{{ items.namarekanan }}</h3>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <label style="font-wight:400">Nomer Terima</label>
                      <h3 class="field mt-1">{{ items.nostruk }}</h3>
                    </div>
                    <div class="column is-3">
                      <label style="font-wight:400">Item</label>
                      <h3 class="field mt-1">{{ items.jmlitem }}</h3>
                    </div>
                    <div class="column is-3">
                      <label style="font-wight:400">Tanggal Diterima</label>
                      <h3 class="field mt-1">{{ items.tglDiterima }}</h3>
                    </div>
                    <!-- <div class="column is-3">
                      <label style="font-wight:400;display:block;margin-left: 10rem;">Status</label>
                      <VTag color="warning" v-if="items.statusOrder == 0" label="Pending"
                        style="margin-left: 9.3rem;color: seashell;font-weight: 500;" rounded />
                      <VTag color="warning" v-if="items.statusOrder == 1" label="Verify"
                        style="margin-left: 9.3rem;color: seashell;font-weight: 500;" rounded />
                      <VTag color="warning" v-if="items.statusOrder == 2" label="Success"
                        style="margin-left: 9.3rem;color: seashell;font-weight: 500;" rounded />
                    </div> -->
                  </div>
                </div>
              </div>
            </VCard>
          </div>
          <div v-else class="p-0" style="margin-top: -51px;">
            <div class="column p-0 m-0" style="display: flex;justify-content: center;">
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                style="max-width: 38%;" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                style="max-width: 38%;" />
            </div>
            <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">Data
              yang dicari tidak ada</h3>
          </div>
          </p>

        </template>
      </VTabs>

    </div>

    <div class="column is-5 pt-0">
      <UIWidget class="search-widget">
        <template #body>
          <div class="field">
            <div class="control">
              <input v-model="filters" class="input custom-text-filter" placeholder="Cari Master Data..." />
              <button class="searcv-button">
                <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
              </button>
            </div>
          </div>
        </template>
      </UIWidget>

      <div class="column is-multiline p-0" style="max-height:210px;overflow: auto;">
        <div class="column is-12 p-0 mb-2" v-for="products in dataSourcefiltered" :key="products.norec">
          <VCard>
            <div class="columns">
              <div class="column is-3 p-0 ml-3 mt-3">
                <VAvatar size="medium" picture="/images/avatars/icon/ic_generic.png" color="primary" squared bordered />
              </div>
              <div class="column p-0">
                <h3 class="field" style="font-weight: 600; margin-bottom: 0px;">{{ products.namaproduk }}</h3>
                <span class="field" style="font-weight: 300;color: var(--light-text);">Jenis : {{ products.jenis }} |
                  Stok
                  : {{ products.stok }} Pcs</span>
                <VButton color="primary" raised style="display:flex;margin-top: 8px;">Kartu Stock <i
                    class="fas fa-print ml-3" aria-hidden="true"></i></VButton>
              </div>
            </div>
          </VCard>
        </div>
      </div>


      <div class="dashboard-card is-gauge mt-3">
        <div class="columns is-multiline" style="margin:0px;padding:0px">
          <div class="column is-8" style="margin:0px;padding:0px">
            <h4 class="dark-inverted" style="font-weight: bold; font-size: 15px">Jumlah Permintaan tiap Ruangan dalam 1
              Bulan</h4>
          </div>
          <div class="column m-0 p-0" style="display: flex;justify-content: end;">
            <VTag color="warning" :label="dateNow" rounded elevated
              style="font-weight: 600; margin-top:10px margin-left:20px" />
          </div>
        </div>
        <ApexChart id="apex-chart-18" :height="265" :type="'donut'" :series="chartRNG.series" :options="chartRNG" />
      </div>
    </div>
  </div>


  <VModal :open="modalDetailPenerimaan" title="Detail Penerimaan" size="big" actions="right"
    @close="modalDetailPenerimaan = false" cancelLabel="Tutup">
    <template #content>
      <div class="column is-12 p-0">
        <VCard color="primary">
          <div class="columns ismultiline">
            <div class="column is-2">
              <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared bordered />
            </div>
            <div class="column is-3">
              <div class="mb-4">
                <label for="TGL Struk " class="labelin">No Faktur</label>
                <h3>{{ dataDetail.noFaktur }}</h3>
              </div>
            </div>

            <div class="column is-3">
              <div class="mb-4">
                <label for="No Struk" class="labelin">Supplier</label>
                <h3>{{ dataDetail.suplayer }}</h3>
              </div>
            </div>

            <div class="column is-3 pl-0">
              <div class="mb-4">
                <label for="Ruang Asal" class="labelin">Nama Ruangan</label>
                <h3>{{ dataDetail.namaruangan }}</h3>
              </div>
            </div>

          </div>
        </VCard>
      </div>

      <form class="modal-form">
        <DataTable :value="dataSourceDetailSuplier" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
          :loading="dataSourceDetailSuplier.loading">
          <Column field="no" header="No"></Column>
          <Column field="namaproduk" header="Nama Produk"></Column>
          <Column field="satuanstandar" header="Satuan Standar" :sortable="true"></Column>
          <Column field="qtyproduk" header="Qty" :sortable="true"></Column>
          <Column field="hargadiscount" header="Discount" :sortable="true"></Column>
          <Column field="hargasatuan" header="Harga Satuan"></Column>
          <Column field="hargappn" header="PPN"></Column>
          <Column field="total" header="Total"></Column>
          <Column field="kadaluarsa" header="Kadaluarsa"></Column>
          <Column field="nobatch" header="nobatch"></Column>

        </DataTable>
      </form>
    </template>
  </VModal>

  <VModal :open="modalDetailPermintaan" title="Detail Permintaan" size="big" actions="right"
    @close="modalDetailPermintaan = false" cancelLabel="Tutup">
    <template #content>

      <div class="column is-12 p-0">
        <VCard color="primary">
          <div class="columns ismultiline">
            <div class="column is-2">
              <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared bordered />
            </div>
            <div class="column is-3">
              <div class="mb-4">
                <label for="TGL Struk " class="labelin">Keterangan</label>
                <h3>{{ dataDetail.keterangan }}</h3>
              </div>
            </div>

            <div class="column is-3">
              <div class="mb-4">
                <label for="No Struk" class="labelin">Unit Pengorder</label>
                <h3>{{ dataDetail.unitOrder }}</h3>
              </div>
            </div>

            <div class="column is-3 pl-0">
              <div class="mb-4">
                <label for="Ruang Asal" class="labelin">Tanggal Permintaan</label>
                <h3>{{ dataDetail.tglOrder }}</h3>
              </div>
            </div>

            <div class="column is-1 pl-0">
              <div class="mb-4">
                <label for="Ruang Asal" class="labelin" style="display:block">Status</label>

                <VTag color="warning" label="Pending" style="color: seashell;font-weight: 500;" rounded
                  v-if="dataDetail.status == 0" />
                <VTag color="primary" label="verify" style="color: seashell;font-weight: 500;" rounded
                  v-if="dataDetail.status == 1" />
                <VTag color="info" label="success" style="color: seashell;font-weight: 500;" rounded
                  v-if="dataDetail.status == 2" />
              </div>
            </div>

          </div>
        </VCard>
      </div>
      <form class="modal-form">
        <DataTable :value="dataSourceDetailPermintaan" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
          :loading="dataSourceDetailPermintaan.loading">
          <Column field="no" header="No"></Column>
          <Column field="tglkebutuhan" :sortable="true" header="Tanggal Dibutuhkan"></Column>
          <Column field="namaproduk" header="Nama Produk"></Column>
          <Column field="prid" header="Kode Produk"></Column>
          <Column field="spesifikasi" header="Spesifikasi"></Column>
          <Column field="satuanstandar" header="Satuan" :sortable="true"></Column>
          <Column field="qtyproduk" header="Qty" :sortable="true"></Column>
          <Column field="hargasatuan" header="Harga Satuan"></Column>
          <Column field="total" header="Total"></Column>
        </DataTable>
      </form>
    </template>
  </VModal>

  <VModal :open="modalAddPermintaan" title="Form Tambah Permintaan" size="large" actions="right"
    @close="modalAddPermintaan = false" cancelLabel="Tutup">
    <template #content>
      <div class="columns">
        <VField class="column is-4 is-rounded-select  is-autocomplete-select" label="Keterangan">
          <VControl icon="feather:search">
            <Multiselect mode="single" v-model="item.filterRuangan" placeholder="Pilih Ruangan" :searchable="true"
              :options="d_Ruangan" @select="changeRuang(item.filterRuangan)" />
          </VControl>
        </VField>
        <VField class="column is-4" label="Unit Pengorder">
          <VControl icon="feather:bookmark">
            <VInput type="text" v-model="item.namaruangan" placeholder="Nama Ruangan" class="is-rounded" />
          </VControl>
        </VField>
        <div class="column is-4">
          <VDatePicker v-model="item.tgllahir" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel>Tanggal Permintaan</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                    class="is-rounded" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
      </div>

      <VCard style="background-color: #f9f9f9;">
        <div class="columns">
          <div class="column is-3">
            <VDatePicker v-model="item.tgllahir" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VLabel>Tanggal Permintaan</VLabel>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-4">
            <VField class="is-rounded-select  is-autocomplete-select" label="Produk">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.filterRuangan" placeholder="Pilih Ruangan" :searchable="true"
                  :options="d_Ruangan" @select="changeRuang(item.filterRuangan)" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField class="is-rounded-select is-autocomplete-select" label="Kode Produk">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.namaruangan" placeholder="Kode" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select  is-autocomplete-select" label="Spesifikasi">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.filterRuangan" placeholder="Spesifikasi" :searchable="true"
                  :options="d_Ruangan" @select="changeRuang(item.filterRuangan)" />
              </VControl>
            </VField>
          </div>

        </div>
        <div class="columns">
          <div class="column is-4 pt-0">
            <VField class="is-rounded-select  is-autocomplete-select" label="Satuan">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.filterRuangan" placeholder="Spesifikasi" :searchable="true"
                  :options="d_Ruangan" @select="changeRuang(item.filterRuangan)" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 pt-0">
            <VField class="is-rounded-select  is-autocomplete-select" label="Qty">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.filterRuangan" placeholder="Spesifikasi" :searchable="true"
                  :options="d_Ruangan" @select="changeRuang(item.filterRuangan)" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 pt-0">
            <VField class="is-rounded-select is-autocomplete-select" label="Satuan Harga">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.namaruangan" placeholder="Satuan Harga" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <VButton @click="save()" :loading="isLoadingTT" type="button" class="is-fullwidth mr-3 btn-add" color="info"
            raised> <i class="fas fa-plus mr-2" aria-hidden="true"></i>
            Tambah Permintaan
          </VButton>
        </div>
      </VCard>
    </template>

    <template #action>
      <VButton icon="feather:plus" style="height: 20px;" :loading="isLoadingBtn" color="primary" raised>Simpan</VButton>
    </template>
  </VModal>
</template>


<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed } from 'vue'
import DataTable from 'primevue/datatable';
// import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import moment, { isDate } from 'moment'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as formatters from '/@src/utils/apex-formatters'
import * as H from '/@src/utils/appHelper'
useHead({
  title: 'Dashboard Logistik - Transmedic',
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const themeColors = useThemeColors()
const dataSourceDetailSuplier: any = ref([])
const dataSourceDetailPermintaan: any = ref([])
const dataDetail: any = ref([])
const modalDetailPenerimaan = ref(false)
const modalDetailPermintaan = ref(false)
const modalAddPermintaan = ref(false)

let chartRNG: any = ref({
  series: [],
})

let chartOP: any = ref({
  series: [],
})
const chartMedisNonMedis: any = ref({
  series: [],
})

let date = new Date();
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})

const userLogin = useUserSession().getUser()
const route = useRoute()
let countRuangan: any = ref([])
let d_Ruangan: any = ref([])
let isData: any = ref(true)
let isLoadingBtn: any = ref(false)
let requestLenght: any = ref(true)
let isOrder: any = ref(true)
let itsTime = new Date().toISOString().slice(0, 10);
let item: any = ref({
  isDate: true,
  exp: true,
  status: '',
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const order: any = ref(0)
const dataOrder: any = ref(0)
const dataPenerimaan: any = ref()
const dataDistribusi: any = ref()
const dataStokProduk: any = ref()

const filters = ref('')

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataStokProduk.value
  }

  return dataStokProduk.value.filter((item: any) => {
    return (
      item.namaproduk.match(new RegExp(filters.value, 'i'))
    )
  })
})

function changeRuang(e: any) {
  for (let x = 0; x < d_Ruangan.value.length; x++) {
    const element = d_Ruangan.value[x];
    if (e == element.value) {
      item.value.namaruangan = element.label
      break
    }
  }
  fetchDataOrder(item.status)
  fetchDataPenerimaanBarang()
  fetchStokProduk()
  fetchDataDistribusi()
}

async function fetchdDropdown() {
  const response = await useApi().get(`/dashboard/logistik/list-ruangan`)
  d_Ruangan.value = response.map((e: any) => {
    return { label: e.namaruangan, value: e.id, default: e }
  })
}

async function fetchDataOrder(q: any) {
  isOrder.value = true
  item.status = q
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let StatusOrder = ''
  let ruanganid = ''

  if (item.value.filterRuangan) {
    ruanganid = '&ruangan=' + item.value.filterRuangan
  }
  if (order) StatusOrder = 'statusorder=' + q

  // const response = await useApi().get(
  //   '/dashboard/logistik?' + StatusOrder + ruanganid
  // )
  await useApi().get('/dashboard/logistik?' + StatusOrder + ruanganid).then((response) => {
    response.daftar.forEach((element: any) => {
      let tglOrder = new Date(element.tglorder).toLocaleDateString('id-ID', { year: "numeric", month: "long", day: "numeric" })
      let ruanganOrder = (element.ruangan.length > 15) ? `${element.ruangan.substr(0, 15)}...` : element.ruangan
      let permintaan = (element.keterangan.length > 15) ? element.keterangan.substr(0, 15) + '...' : element.keterangan
      element.ruanganOrder = ruanganOrder
      element.permintaan = permintaan
      element.tglOrder = tglOrder
    })
    requestLenght.value = response.daftar.length
    dataOrder.value = response.daftar

  })
}

async function fetchDataPenerimaanBarang() {
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let ruanganid = ''
  if (item.value.filterRuangan) {
    ruanganid = '&ruangan=' + item.value.filterRuangan
  }
  let tglAwal = 'tglAwal=' + moment(item.value.periode.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.periode.end).format('YYYY-MM-DD')
  // const response = await useApi().get(
  //   '/dashboard/logistik/daftar-penerimaan-barang?' + tglAwal + tglAkhir + ruanganid
  // )
  await useApi().get('/dashboard/logistik/daftar-penerimaan-barang?' + tglAwal + tglAkhir + ruanganid).then((response) => {
    response.daftar.forEach((element: any) => {
      let tglDiterima = new Date(element.tglstruk).toLocaleDateString('id-ID', { year: "numeric", month: "long", day: "numeric" })
      let rekanan = (element.namarekanan.length > 22) ? `${element.namarekanan.substring(0, 23)}...` : element.namarekanan
      element.rekanan = rekanan
      element.tglDiterima = tglDiterima
    });
    isData.value = response.daftar.length
    dataPenerimaan.value = response.daftar
  })
}

async function fetchStokProduk() {
  let ruanganid = ''
  if (item.value.filterRuangan) {
    ruanganid = '?ruangan=' + item.value.filterRuangan
  }
  await useApi().get('/dashboard/logistik/get-stok-produk' + ruanganid).then(response => {
    dataStokProduk.value = response
  })
}

async function fetchDataDistribusi() {
  let ruanganid = ''
  let nmb = ''
  let sob = ''

  if (item.value.filterRuangan) {
    ruanganid = '&ruangan=' + item.value.filterRuangan
  }
  if (item.value.searchNMB) {
    nmb = '&namabarang=' + item.value.searchNMB
  }
  if (item.value.searchSO) {
    sob = '&nostruk=' + item.value.searchSO
  }
  let tglAwal = 'tglAwal=' + moment(item.value.periode.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.periode.end).format('YYYY-MM-DD')
  const response = await useApi().get('/dashboard/logistik/get-data-distribusi?' + tglAwal + tglAkhir + ruanganid + sob + nmb)
  isData.value = response.daftar.length
  dataDistribusi.value = response.daftar
}

async function fetchChartRequestByRuangan() {
  await useApi().get('/dashboard/logistik/chart-request-ruangan').then((response) => {
    chartRNG.value = {
      series: response.chartRNG.count,
      chart: {
        height: 290,
        type: 'donut',
      },
      colors: [
        themeColors.accent,
        themeColors.info,
        themeColors.green,
        themeColors.purple,
        themeColors.orange,
      ],
      labels: response.chartRNG.ruangan,
      responsive: [
        {
          breakpoint: 480,
          options: {
            chart: {
              width: 280,
              toolbar: {
                show: false,
              },
            },
            legend: {
              position: 'top',
            },
          },
        },
      ],
      legend: {
        position: 'right',
        horizontalAlign: 'center',
      },
    }
  })
}

async function fetchChartMedisNon() {
  await useApi()
    .get(`/dashboard/logistik/chart-medis-non-medis`)
    .then((response: any) => {
      item.value = response
      chartMedisNonMedis.value = {
        series: response.chartMedis.series,
        chart: {
          type: 'bar',
          height: 260,
          toolbar: {
            show: false,
          },
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded',
          },
        },
        colors: [themeColors.accent, themeColors.info, themeColors.green, themeColors.purple],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent'],
        },
        xaxis: {
          categories: response.chartMedis.categories,
        },
        yaxis: {
          title: {
            text: 'Jumlah',
          },
        },
        fill: {
          opacity: 1,
        },
        legend: {
          position: 'top',
          horizontalAlign: 'center',
        },
        title: {
          text: '',
          align: 'left',
        },
        tooltip: {
          // y: {
          //   formatter: formatters.asKDollar,
          // },
        },
      }
      countRuangan.value = response
      countRuangan.value.total = response.length
    })
}

async function detailPermintaan(e: any) {
  modalDetailPermintaan.value = true
  dataSourceDetailPermintaan.value.loading = true
  const response = await useApi().get(
    `/dashboard/logistik?noorder=${e.noorder}`)

  for (let x = 0; x < response.daftar[0].details.length; x++) {
    const element = response.daftar[0].details[x];
    element.no = x + 1
    const date = new Date(element.tglkebutuhan);
    element.tglkebutuhan = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
  }
  dataSourceDetailPermintaan.value = response.daftar[0].details
  dataSourceDetailPermintaan.value.loading = false
  dataDetail.value.keterangan = e.keterangan
  dataDetail.value.tglOrder = e.tglorder
  dataDetail.value.unitOrder = e.ruangan
  dataDetail.value.status = e.statusOrder
}

async function detailPenerimaan(e: any) {
  modalDetailPenerimaan.value = true
  dataSourceDetailSuplier.value.loading = true
  let tglAwal = '&tglAwal=' + moment(item.value.periode.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.periode.end).format('YYYY-MM-DD')

  const response = await useApi().get(`/dashboard/logistik/daftar-penerimaan-barang?nostruk=${e.nostruk}${tglAwal}${tglAkhir}`)
  dataDetail.value.suplayer = e.namarekanan
  dataDetail.value.noFaktur = e.nofaktur
  dataDetail.value.namaruangan = e.namaruangan

  for (let x = 0; x < response.daftar[0].details.length; x++) {
    const element = response.daftar[0].details[x];
    element.no = x + 1
    const date = new Date(element.tglkadaluarsa);
    element.kadaluarsa = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
  }
  dataSourceDetailSuplier.value = response.daftar[0].details
  dataSourceDetailSuplier.value.loading = false
}

// const detailDistribusi = async (e: any) => {
//   modalAddPermintaan.value = true
//   dataSourceDetailDistribusi.value.loading = true
//   let tglAwal = '&tglAwal=' + moment(item.value.periode.start).format('YYYY-MM-DD')
//   let tglAkhir = '&tglAkhir=' + moment(item.value.periode.end).format('YYYY-MM-DD')
//   await useApi().get(`/dashboard/logistik/get-data-distribusi?nostruk=${e.nostruk}${tglAwal}${tglAkhir}`).then((response) => {
//     response.daftar[0].details.forEach((element: any, i: any) => {
//       element.no = i + 1
//       const date = new Date(element.tglkadaluarsa);
//       element.kadaluarsa = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
//     });
//     dataSourceDetailDistribusi.value = response.daftar[0].details
//     dataSourceDetailDistribusi.value.loading = false
//   })
//   dataDetail.value.tglstruk = e.tglstruk
//   dataDetail.value.nostruk = e.nostruk
//   dataDetail.value.item = e.jmlitem
//   dataDetail.value.ruanganasal = e.namaruanganasal
//   dataDetail.value.ruangantujuan = e.namaruangantujuan
//   dataDetail.value.keterangan = e.keterangan
//   dataDetail.value.petugas = e.petugas
// }
const addRequest = () => {
  modalAddPermintaan.value = true
}
function filter() {
  item.isDate = false
  fetchDataPenerimaanBarang()
}

function changeSwitch(e: any) {
  // item.status.value = e
  fetchDataOrder(e)
}

fetchdDropdown()
fetchChartMedisNon()
fetchDataDistribusi()
// fetchChartRequestByRuangan()
fetchDataPenerimaanBarang()
changeSwitch(0)
fetchStokProduk();

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.custom-tab-search {
  padding-left: 7px;
  border-top-right-radius: unset;
  margin-top: -16px;
  border-top-left-radius: unset;
  border-top-style: none;
  margin-left: -5px;
  height: 7.6rem;
  width: 47.5rem;
  padding-top: 0px;
}

.search-menu {
  height: 56px;
  white-space: nowrap;
  display: flex;
  flex-shrink: 0;
  align-items: center;
  background-color: white;
  border-radius: 8px;
  width: 100%;
  padding-left: 0.75rem;

  >div:not(:last-of-type) {
    border-right: 1px solid var(--search-border-color);
  }

  .search-bar {
    height: 55px;
    width: 100%;
    position: relative;
    display: flex;
    align-items: center;
    padding-right: 1.5rem;

    .field {
      width: 100%;
    }

    .multiselect-tags {
      padding-left: 2.5rem;
    }
  }

  .search-location,
  .search-job,
  .search-salary {
    display: flex;
    align-items: center;
    width: 50%;
    font-size: 14px;
    font-weight: 500;
    padding: 0 25px;
    height: 100%;
    font-family: var(--font);

    input {
      width: 100%;
      height: 90%;
      display: block;
      font-family: var(--font);
      color: var(--input-color);
      background-color: white;
      border: none;
    }

    svg {
      margin-right: 0.5rem;
      width: 18px;
      color: var(--primary);
      flex-shrink: 0;
    }
  }

  .search-button {
    background-color: var(--primary);
    min-width: 100px;
    height: 56px;
    border: none;
    font-weight: 500;
    font-family: var(--font);
    padding: 0 1rem;
    border-radius: 0 0.75rem 0.75rem 0;
    color: var(--button-color);
    cursor: pointer;
    margin-left: auto;
  }
}

.btn-add {
  height: 32px !important;
  font-weight: 500 !important;
  margin-top: 1.8rem !important;
}

.labelin {
  font-size: 16px;
  font-weight: 700;
  color: white;
}

label {
  font-weight: 400;
  color: darkgray;
  font-size: 12px;
}

.label.form-icon {
  top: -2px !important;
}

.multiselect-placeholder {
  color: #215E43 !important;
}

.tabs-inner {
  margin-right: 26rem;
}

.custom-tabs {
  .tabs {
    margin-right: 32rem;
  }
}

.vue-apexcharts {
  min-height: 225px !important;
}

// .button.v-button {
//   padding: 3px 4px;
//   height: 20px;
//   line-height: 1.1;
//   font-size: 11px;
//   font-family: var(--font);
//   transition: all 0.3s;
// }

.tabs-wrapper.is-squared.is-slider {
  .tabs-inner {
    .tabs {
      max-width: 215px;
    }
  }
}

.field {
  font-size: 100%;
  font-weight: 500;
}

.dashboard-card {
  @include vuero-l-card;

  font-family: var(--font);

  .border-custom {
    margin-left: -21px;
    padding-left: 20px;
    padding-top: 12px;
    margin-top: -21px;
    border-top-left-radius: 18px;
    border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  }

  &:not(:last-child) {
    margin-bottom: 1.5rem;
  }

  &.is-welcome {
    background: var(--widget-grey);
    border: none;
    padding: 40px;

    .welcome-title {
      h3 {
        font-family: var(--font-alt);
        font-weight: 700;
        font-size: 2rem;
        color: var(--dark-text);
      }
    }

    .welcome-progress {
      display: flex;
      align-items: center;
      padding: 10px 0;

      .meta {
        margin-left: 16px;

        span {
          display: block;

          &:first-child {
            color: var(--light-text);
            font-size: 0.95rem;
          }

          &:nth-child(2) {
            font-family: var(--font-alt);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
          }
        }
      }
    }

    .button-wrap {
      .v-button {
        height: 44px;
        border-radius: 10px;
      }
    }
  }

  &.is-interview {
    &:not(:last-child) {
      margin-bottom: 0.75rem;
    }

    .flex-end {
      svg {
        height: 18px;
        width: 18px;
        color: var(--light-text);
      }
    }
  }
}

.hr-dashboard {

  .block-header {
    display: flex;
    border-radius: 16px;
    padding: 10px;
    background: var(--primary);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .left,
    .right {
      width: 30%;
    }

    .center {
      display: flex;
      flex-direction: column;
      width: 40%;
      padding-right: 30px;
      margin-right: 30px;
      border-right: 1px solid var(--primary-light-10);

      .block-text {
        margin-bottom: 16px;
      }

      .candidates {
        margin-top: auto;

        >.v-avatar {
          margin-right: 10px;
        }

        button {
          height: 40px;
          width: 40px;
          display: inline-flex;
          justify-content: center;
          align-items: center;
          border-radius: 10px;
          background: var(--white);
          color: var(--light-text);
          border: none;
          cursor: pointer;
          transition: all 0.3s; // transition-all test

          svg {
            height: 18px;
            width: 18px;
          }
        }
      }
    }

    .left {
      display: flex;
      justify-content: center;
      align-items: center;

      .current-user {
        .v-avatar {
          margin-bottom: 1rem;
        }

        h3 {
          font-family: var(--font-alt);
          font-weight: 700;
          font-size: 1.8rem;
          color: var(--white);
          line-height: 1.2;
        }
      }
    }

    .right {
      display: flex;
      flex-direction: column;

      .button {
        margin-top: auto;
      }
    }

    .block-heading {
      font-family: var(--font-alt);
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--white);
      margin-bottom: 4px;
    }

    .block-text {
      font-family: var(--font);
      font-size: 0.9rem;
      color: var(--white);
      margin-bottom: 16px;
    }

    .header-meta {
      margin-left: 0;
      padding-right: 30px;

      h3 {
        color: var(--smoke-white);
        font-family: var(--font-alt);
        font-weight: 700;
        font-size: 1.3rem;
        max-width: 280px;
      }

      p {
        font-weight: 400;
        color: var(--smoke-white-dark-2);
        margin-bottom: 16px;
        max-width: 320px;
      }

      .action-link {
        span {
          font-size: 0.8rem;
          text-transform: uppercase;
          margin-right: 6px;
        }

        i {
          font-size: 12px;
        }
      }
    }
  }

  .feed-settings {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    .button {
      font-size: 0.8rem;
      border-radius: 8px;
      margin-right: 4px;

      &.is-selected {
        background: var(--primary);
        color: var(--white);
        border-color: var(--primary);
        box-shadow: var(--primary-box-shadow);
      }
    }
  }

  .side-text {
    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
      margin-bottom: 8px;
    }

    p {
      font-size: 0.95rem;
      margin-bottom: 8px;
    }

    .action-link {
      font-size: 0.9rem;
    }
  }

  .recent-rookies {
    .recent-rookies-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-text);
      }
    }

    .user-grid {
      &.user-grid-v4 {
        .grid-item {
          @include vuero-l-card;
        }
      }
    }
  }
}

.user-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    position: relative;
    @include vuero-s-card;

    text-align: center;

    &:hover,
    &:focus {
      .button-wrap {
        >div {
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

    >.v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.85rem;
    }

    .button-wrap {
      margin: 20px 0 0;

      .v-button {
        width: 100%;
        max-width: 140px;
        margin: 0 auto;
      }

      >div {
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
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .hr-dashboard {
    .block-header {
      background: var(--dark-sidebar);
      box-shadow: none;

      .center {
        border-color: var(--dark-sidebar-light-10);

        .candidates {
          button {
            background: var(--dark-sidebar-light-10);
            border: 1px solid transparent;
            transition: all 0.3s; // transition-all test

            &:hover {
              border-color: var(--primary);

              svg {
                color: var(--primary);
              }
            }
          }
        }
      }
    }

    .feed-settings {
      .button {
        &.is-selected {
          background: var(--primary) !important;
          border-color: var(--primary) !important;
          box-shadow: var(--primary-box-shadow) !important;
          color: var(--white) !important;
        }
      }
    }

    .recent-rookies {
      .user-grid {
        &.user-grid-v4 {
          .grid-item {
            @include vuero-card--dark;
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .hr-dashboard {
    .block-header {
      flex-direction: column;
      padding: 30px;

      .left,
      .center,
      .right {
        width: 100%;
      }

      .left {
        justify-content: flex-start;
        margin-bottom: 20px;
      }

      .center {
        padding-right: 0;
        margin-right: 0;
        border-right: none;
        margin-bottom: 20px;
      }
    }

    .feed-settings {
      flex-direction: column;

      h3 {
        margin-bottom: 16px;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .hr-dashboard {
    .block-header {
      padding: 40px;
    }

    .side-text {
      display: none;
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .hr-dashboard {
    .block-header {
      padding: 40px;

      .left {
        .current-user {
          h3 {
            font-size: 1.5rem;
          }
        }
      }

      .center {
        .candidates {
          .v-avatar {
            &:nth-child(3) {
              display: none;
            }
          }
        }
      }
    }

    .column {
      &.is-7 {
        &.is-offset-1 {
          margin-left: 2% !important;
          width: 64.3333% !important;
        }
      }
    }
  }
}
</style>
