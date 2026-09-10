<template>
  <VCard radius="small">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Data Detail Jenis Produk</h3>
    </div>

    <div class="columns is-multiline">
      <div class="column is-8">
        <div class="user-grid-toolbar">

          <div class="buttons">
            <VField v-slot="{ id }" class="is-icon-select">
              <VControl>
                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name"
                  :options="d_View" :searchable="true" track-by="name" mode="single" @select="changeView(selectView)"
                  autocomplete="off">
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
            <VButton color="primary" raised @click="add()">
              <span class="icon">
                <i aria-hidden="true" class="fas fa-plus"></i>
              </span>
              <span>Tambah Data</span>
            </VButton>
          </div>
        </div>

        <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
          <DataTable :value="dataSourcefiltered" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

            <Column field="id" header="Kode"></Column>
            <Column field="detailjenisproduk" header="Detail Jenis Produk" :sortable="true"></Column>
            <Column field="jenisproduk" header="Jenis Produk" :sortable="true"></Column>
            <Column field="namadepartemen" header="Departemen" :sortable="true"></Column>
            <Column :exportable="false" header="Action">
              <template #body="slotProps">
                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                  v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" icon="fas fa-trash" class="mr-3" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" @click="deleterow(slotProps.data)">
                </VIconButton>
              </template>
            </Column>
          </DataTable>
        </div>
        <div class="tile-grid tile-grid-v1" v-else-if="selectView == 'grid'">
          <div class="page-placeholder" v-if="dataSourcefiltered.length == 0">
              <div class="placeholder-content">
                  <img class="light-image"
                      style=" max-width: 340px;"
                      :src="H.assets().iconNotFound_rev"
                      alt="" />
                  <img class="dark-image"
                      style=" max-width: 340px;"
                      :src="H.assets().iconNotFound_rev"
                      alt="" />
                  <h3>{{H.assets().notFound}}</h3>
                  <p class="is-larger">
                      {{H.assets().notFoundSubtitle}}
                  </p>
              </div>
          </div>
          <TransitionGroup name="list" tag="div" class="columns is-multiline">
            <!--Grid item-->
            <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-12">
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner">
                  <VAvatar size="medium" picture="/images/avatars/svg/jenisproduk.svg" color="primary" squared
                    bordered />
                  <div class="meta">
                    <span class="dark-inverted">Detail Produk : {{ item.detailjenisproduk }}</span>
                    <span>Jenis Produk : {{ item.jenisproduk }}</span>
                    <span>Departemen : {{ item.namadepartemen }}</span>
                  </div>
                  <VDropdown icon="feather:more-vertical" spaced right>
                    <template #content>
                      <a role="menuitem" class="dropdown-item is-media" @click="detail(item)">
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
                      <a role="menuitem" class="dropdown-item is-media" @click="deleterow(item)">
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
        </div>
      </div>
      <div class="column is-4">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1">Filters</h3>
          </div>
          <div class="column is-6">
            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              Clear All
            </a>
          </div>
          <div class="column is-12" style="margin-top: 12px;">
            <VControl icon="feather:search">
              <input v-model="filters" class="input custom-text-filter" placeholder="Cari Data..." />
            </VControl>
          </div>
          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Jenis Produk</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.jenisproduk" :options="d_JenisProduk" placeholder="Pilih asal"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">

            <VButton @click="filter()" :loading="isLoading" type="button" icon="feather:search"
              class="is-fullwidth mr-3" color="info" raised>
              Apply Filters
            </VButton>
          </div>
        </div>
      </div>
    </div>
    <VModal :open="modalInput" title="Tambah Detail Jenis Produk" actions="right" @close="modalInput = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Detail Jenis Produk">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.detailjenisproduk" placeholder="Detail Jenis Produk"
                    class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Jenis Produk</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectjenisprodukfk" :options="d_JenisProduk"
                    placeholder="Pilih data" :searchable="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Departemen</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectdepartemenfk" :options="d_Departemen"
                    placeholder="Pilih data" :searchable="true" />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:plus" @click="save()" :loading="isLoadingTT" color="primary" raised>Simpan</VButton>
      </template>
    </VModal>
    <VModal :open="modalDetail" title="Detail Jenis Produk" actions="right" @close="modalDetail = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Jenis Produk">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jenisproduk" placeholder="Jenis Produk" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Kelompok Produk">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jenisproduk" placeholder="Kelompok Produk" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Nama Eksternal">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namaexternal" placeholder="Nama Eksternal" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Report Display">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.reportdisplay" placeholder="Report Display" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Norec">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.norec" placeholder="Norec" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Kode Jenis Produk">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.id" placeholder="Kode Jenis Produk" class="is-rounded" />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
    </VModal>


  </VCard>

</template>
    
<script  setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Detail Jenis Produk - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
  aktif: true
})
const modalInput = ref(false)
const modalDetail = ref(false)
const d_JenisProduk = ref([])
const d_Departemen = ref([])

let dataSource: any = ref([])
let isRegistrasi = ref(false)
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
selectView.value = 'list'
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)


const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.detailjenisproduk.match(new RegExp(filters.value, 'i'))
      || items.jenisproduk.match(new RegExp(filters.value, 'i'))
    )
  })
})

const route = useRoute()
isLoading.value = false

async function fetchData() {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let DetailJenisProduk = ''
  let JenisProduk = ''
  let Departemen = ''

  if (item.jenisproduk) JenisProduk = '&jenisproduk' + item.jenisproduk
  if (item.value.detailjenisproduk) DetailJenisProduk = '&detailjenisproduk=' + item.value.detailjenisproduk
  if (item.value.namadepartemen) Departemen = '&namadepartemen=' + item.namadepartemen
  dataSource.value = []
  const response = await useApi().get(
    '/sysadmin/master-detail-jenis-produk?offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    DetailJenisProduk + JenisProduk + Departemen
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource.value = response.data
}

function loadDropdown() {
  d_JenisProduk.value = []
  useApi().get(
    `/sysadmin/master-detail-jenis-produk-dropdown`).then((response: any) => {
      d_JenisProduk.value = response.jenisproduk.map((e: any) => { return { label: e.jenisproduk, value: e.id } })
      d_Departemen.value = response.namadepartemen.map((e: any) => { return { label: e.namadepartemen, value: e.id } })
    })
}

function loadData() {
  dataSource.value = []
  useApi().get(
    `/sysadmin/master-detail-jenis-produk?statusenabled=${item.value.aktif}`).then((response: any) => {
      dataSource.value = response.data
    })
}

function add() {
  clear()
  modalInput.value = true
}

function edit(e: any) {
  item.value.id = e.id
  item.value.detailjenisproduk = e.detailjenisproduk
  item.value.objectjenisprodukfk = e.objectjenisprodukfk
  item.value.objectdepartemenfk = e.objectdepartemenfk
  item.value.norec = e.norec
  item.value.reportdisplay = e.reportdisplay
  modalInput.value = true
}
function detail(e: any) {
  item.value.id = e.id
  item.value.detailjenisproduk = e.detailjenisproduk
  item.value.objectjenisprodukfk = e.objectjenisprodukfk
  item.value.objectdepartemenfk = e.objectdepartemenfk
  item.value.reportdisplay = e.reportdisplay
  item.value.norec = e.norec
  modalDetail.value = true
}

async function save() {
  
  if (!item.value.detailjenisproduk) {
    useToaster().error('Detail Jenis Produk harus di isi')
    return
  }
  var objSave =
  {
    'detailjenisproduk': {
      'id': item.value.id ? item.value.id : '',
      'detailjenisproduk': item.value.detailjenisproduk,
      'objectjenisprodukfk': item.value.objectjenisprodukfk,
      'objectdepartemenfk': item.value.objectdepartemenfk,
    }
  }
  isLoadingTT.value = true
  await useApi().post(
    `/sysadmin/save-master-detail-jenis-produk`, objSave).then((response: any) => {
      isLoadingTT.value = false
      clear()
      loadData()
    }, (error) => {
      isLoadingTT.value = false
    })
}

async function deleterow(e: any) {
  await useApi().post(
    `/sysadmin/delete-detail-jenis-produk`, { 'id': e.id }).then((response: any) => {
      loadData()
    }, (error) => {
    })
}

function clear() {
  item.value = {}
  modalInput.value = false
}
function changeView(e: any) {
  selectView.value = e
}
function filter() {
  fetchData()
}
function clearFilter() {
  fetchData()
}
loadDropdown()
loadData()
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

</style>