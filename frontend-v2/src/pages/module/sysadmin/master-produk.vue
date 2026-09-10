<template>
  <ConfirmDialog />
  <VCard>
    <TabView v-model:activeIndex="activeValue">
      <TabPanel header="Produk">
        <div class="columns is-multiline  projects-card-grid">
          <div class="column is-3">
            <VField v-slot="{ id }" class="is-icon-select">
              <VControl>
                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name" :options="d_View"
                  :searchable="true" track-by="name" mode="single" @select="changeView(selectView)" autocomplete="off">
                  <template #singlelabel="{ value }">
                    <div class="multiselect-single-label">
                      <div class="select-label-icon-wrap">
                        <i :class="value.icon"></i>
                      </div>
                      <span class="select-label-text">
                        {{ value.name }}
                      </span>
                    </div>
                  </template>
                  <template #option="{ option }">
                    <div class="select-option-icon-wrap">
                      <i :class="option.icon"></i>
                    </div>
                    <span class="select-option-text">
                      {{ option.name }}
                    </span>
                  </template>
                </Multiselect>
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <a type="button" class="is-pulled-right" color="info" outlined raised>
              <VButton color="primary" RouterLink :to="{ name: 'module-sysadmin-produk-baru' }">
                <i class="fa fa-plus"></i> Produk Baru
              </VButton>
            </a>
          </div>
        </div>

        <div class="columns">
          <div class="column is-9" v-if="selectView == 'grid'">
            <div v-if="isLoading" class="columns is-multiline">
              <div div v-for="key in 5" :key="key" class="column is-4">
                <div class="tile-grid-item">
                  <VPlaceloadWrap>
                    <VPlaceloadAvatar size="medium" />
                    <VPlaceloadText last-line-width="80%" class="mx-2" />
                    <VPlaceload class="mx-2" disabled />
                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                  </VPlaceloadWrap>
                </div>
              </div>
            </div>
            <div class="page-placeholder" v-else-if="dataSource.length == 0">
              <div class="placeholder-content">
                <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                <h3>{{ H.assets().notFound }}</h3>
                <p class="is-larger">
                  {{ H.assets().notFoundSubtitle }}
                </p>
              </div>
            </div>
            <div class="tile-grid tile-grid-v1" v-else>
              <TransitionGroup name="list" tag="div" class="columns is-multiline">
                <div v-for="(item, key) in dataSource" :key="key" class="column is-4">
                  <div class="tile-grid-item">
                    <div class="tile-grid-item-inner">
                      <VAvatar size="small" picture="/images/simrs/produk-ico.png" color="primary" squared bordered />
                      <div class="meta">
                        <span class="dark-inverted"> [{{ item.kdproduk }}] - {{ item.namaproduk }}</span>
                        <span>{{ item.detailjenisproduk }}</span>
                        <span>{{ item.asalproduk }}</span>
                        <span>Jenis : {{ item.jenisproduk }}</span>
                      </div>
                      <VDropdown icon="feather:more-vertical" spaced right>
                        <template #content>
                          <a role="menuitem" class="dropdown-item is-media" @click="edit(item)">
                            <div class="icon">
                              <i class="iconify" data-icon="feather:bookmark" aria-hidden="true"></i>
                            </div>
                            <div class="meta">
                              <span>Detail</span>
                              <span>Untuk melihat data </span>
                            </div>
                          </a>
                          <a role="menuitem" class="dropdown-item is-media" @click="edit(item)">
                            <div class="icon">
                              <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                            </div>
                            <div class="meta">
                              <span>Edit</span>
                              <span>Untuk merubah data </span>
                            </div>
                          </a>
                          <a role="menuitem" class="dropdown-item is-media" @click="hapus(item)">
                            <div class="icon">
                              <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                            </div>
                            <div class="meta">
                              <span>Remove</span>
                              <span>Hapus Data dari Daftar</span>
                            </div>
                          </a>
                        </template>
                      </VDropdown>
                    </div>
                  </div>
                </div>
              </TransitionGroup>
              <!-- <div class="dataTable-bottom">
                <div class="dataTable-info">Menampilkan {{ dataSource.length }} ke {{ currentPage.limit }} dari
                  {{ currentPage.total }} entri data
                </div>
              </div> -->
            </div>
            <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
              :total-items="currentPage.total" :max-links-displayed="5">
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
                          <option :value="3">3 results per page</option>
                          <option :value="9">9 results per page</option>
                          <option :value="12">12 results per page</option>
                          <option :value="15">15 results per page</option>
                          <option :value="18">18 results per page</option>
                          <option :value="72">72 results per page</option>
                        </select>
                      </div>
                    </VControl>
                  </VField>
                </VFlex>
              </template>
            </VFlexPagination>
          </div>
          <div class="column is-9" v-else-if="selectView == 'list'">
            <DataTable :value="dataSource" class="p-datatable-sm" :paginator="true" :rows="10"
              :rowsPerPageOptions="[5, 10, 25]"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">


              <Column field="no" header="#"></Column>
              <Column field="namaproduk" header="Produk" :sortable="true"></Column>
              <Column field="detailjenisproduk" header="Detail Jenis Produk"></Column>
              <Column field="jenisproduk" header="Jenis Produk"></Column>
              <Column field="kelompokprodukbpjs" header="Kelompok BPJS" :sortable="true"></Column>
              <Column :exportable="false" header="Action" style="text-align: center;">
                <template #body="slotProps">
                  <!-- <VIconButton type="button" icon="fas fa-bookmark" class="mr-3" color="primary" circle outlined raised
                    v-tooltip.top="'Detail'" @click="detail(slotProps.data)">
                  </VIconButton>
                  <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                    v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                  </VIconButton>
                  <VIconButton type="button" icon="fas fa-trash" class="mr-3" color="danger" circle outlined raised
                    v-tooltip.top="'Hapus'" @click="hapus(slotProps.data)">
                  </VIconButton> -->
                  <VDropdown icon="feather:more-vertical" spaced right>
                    <template #content>
                      <a role="menuitem" class="dropdown-item is-media" @click="edit(slotProps.data)">
                        <div class="icon">
                          <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                        </div>
                        <div class="meta">
                          <span>Edit</span>
                          <span>Untuk merubah data </span>
                        </div>
                      </a>
                      <a role="menuitem" class="dropdown-item is-media" @click="hapus(slotProps.data)">
                        <div class="icon">
                          <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                        </div>
                        <div class="meta">
                          <span>Remove</span>
                          <span>Hapus Data dari Daftar</span>
                        </div>
                      </a>
                    </template>
                  </VDropdown>
                </template>
              </Column>
            </DataTable>
          </div>
          <div class="column is-3">
            <div class="columns is-multiline">
              <div class="column is-6">
                <h3 class="title is-5 mb-2 mr-1">Filter</h3>
              </div>
              <div class="column is-6">
                <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
                  Clear
                </a>
              </div>
              <div class="column is-12">
                <VField label="Produk">
                  <VControl icon="feather:search">
                    <input v-model="item.namaproduk" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                      placeholder="Nama Produk" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel>Detail Jenis Produk</VLabel>
                  <VControl icon="feather:columns">
                    <Multiselect mode="single" v-model="item.detailjenisproduk" :options="DetailProduk"
                      placeholder="Pilih Detail Jenis Produk" :searchable="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField label="Rows">
                  <VControl icon="fas fa-list-ol">
                    <input v-model="currentPage.limit" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                      placeholder="Rows" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VButton @click="filter()" :loading="isLoading" type="button" icon="feather:search"
                  class="is-fullwidth mr-3" color="info" raised>
                  Pencarian
                </VButton>
              </div>
            </div>
          </div>
        </div>
      </TabPanel>
      <TabPanel header="Kelompok Produk">
        <MasterKelompokProduk v-if="activeValue == 1"></MasterKelompokProduk>
      </TabPanel>
      <TabPanel header="Jenis Produk">
        <MasterJenisProduk v-if="activeValue == 2"></MasterJenisProduk>
      </TabPanel>
      <TabPanel header="Detail Jenis Produk">
        <MasterDetailJenisProduk v-if="activeValue == 3"></MasterDetailJenisProduk>
      </TabPanel>
      <TabPanel header="Bahan Produk">
        <MasterBahanProduk v-if="activeValue == 4"></MasterBahanProduk>
      </TabPanel>
      <TabPanel header="Bentuk Produk">
        <MasterBentukProduk v-if="activeValue == 5"></MasterBentukProduk>
      </TabPanel>
      <TabPanel header="Produsen Produk">
        <MasterProdusenProduk v-if="activeValue == 6"></MasterProdusenProduk>
      </TabPanel>
      <TabPanel header="Map Ruangan To Produk">
        <MasterKelompokProduk v-if="activeValue == 7"></MasterKelompokProduk>
      </TabPanel>
      <TabPanel header="Harga Netto">
        <HargaNetto v-if="activeValue == 8"></HargaNetto>
      </TabPanel>
      <TabPanel header="Master Generik">
        <MappingGenerik v-if="activeValue == 9"></MappingGenerik>
      </TabPanel>
    </TabView>

  </VCard>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import MasterKelompokProduk from './master-kelompok-produk.vue'
import MasterJenisProduk from './master-jenis-produk.vue'
import MasterBahanProduk from './master-bahan-produk.vue'
import MasterBentukProduk from './master-bentuk-produk.vue'
import MasterProdusenProduk from './master-produsen-produk.vue'
import MasterDetailJenisProduk from './master-detail-jenis-produk.vue'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import MappingRuanganToProduk from './mapping-ruangan-to-produk.vue'
import HargaNetto from './master-harga-netto-produk-by-kelas.vue'
import MappingGenerik from './master-generik-new.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import TabView from 'primevue/tabview';
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import TabPanel from 'primevue/tabpanel';
useHead({
  title: 'Produk - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setFullWidth(true)
let dataSource: any = ref([])
let isLoading: any = ref(true)
const confirm = useConfirm()
let item: any = reactive({})
let listProduk: any = ref([])
let activeValue: any = ref(0)
let DetailProduk: any = ref([])
const router = useRouter()
const currentPage: any = ref({
  limit: 9,
  rows: 50,
  total: 0,
})
const route = useRoute()
const d_View = [
  {
    name: 'Grid View',
    value: 'grid',
    icon: 'fas fa-id-card-alt',
  },
  {
    name: 'List View',
    value: 'list',
    icon: 'fas fa-list',
  },
]
const selectView: any = ref()
selectView.value = 'grid'
listDropdown()

async function fetchData() {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let nmProduk = ''
  let JenisProduk = ''
  let DetailJenisProduk = ''

  if (item.namaproduk) nmProduk = '&namaproduk=' + item.namaproduk
  if (item.jenisproduk) JenisProduk = '&objectjenisprodukfk=' + item.jenisproduk
  if (item.detailjenisproduk) DetailJenisProduk = '&objectdetailjenisprodukfk=' + item.detailjenisproduk

  const response = await useApi().get(
    '/sysadmin/master-produk?offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    nmProduk + JenisProduk + DetailJenisProduk
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource.value = response.data
  currentPage.value.total = response.count
}

function listDropdown() {
  DetailProduk.value = []
  useApi().get(`/sysadmin/master-produk-dropdown`).then((response: any) => {
    DetailProduk.value = response.detailjenisproduk.map((e: any) => { return { label: e.detailjenisproduk, value: e.id } })
  })
}

function clearFilter() {
  delete item.namaproduk
  delete item.detailjenisproduk
  item.qAktif = false
  fetchData()
}

function filter() {
  fetchData()
}
function edit(e: any) {
  router.push({
    name: 'module-sysadmin-produk-baru',
    query: {
      id: e.id,
    },
  })
}
function detail(e: any) {
  router.push({
    name: 'module-sysadmin-produk-baru',
    query: {
      id: e.id,
    },
  })
}

function hapus(e: any) {
  let message = 'Apakah Anda Yakin Menghapus Produk Ini ?'
  let konfirm ='Konfirmasi Hapus Produk'
  confirm.require({
    message: message,
    header: konfirm,
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
        useApi().post(
      `sysadmin/delete-master-produk`, { 'id': e.id }).then((response: any) => {
        fetchData()
      }).catch((e: any) => {

      })
    },
    reject: () => { },
  })
}
  // useApi().post(
  //   `sysadmin/delete-master-produk`, { 'id': e.id }).then((response: any) => {
  //     fetchData()
  //   }).catch((e: any) => {

  //   })

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})

watch(currentPage.value, () => {
  fetchData()
})

watch(
  () => item.namaproduk,
  (newValue) => {
    H.cacheHelper().set('searchP', newValue);
  }
)

function changeView(e: any) {
  selectView.value = e
}

onMounted(() => {
  fetchData()
  if(H.cacheHelper().get('searchP')) {
    item.namaproduk = H.cacheHelper().get('searchP')
  }
})
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/module/sysadmin/produk.scss';

.tabs-inner {
  margin-right: unset;
}
</style>
