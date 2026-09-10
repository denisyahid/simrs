<template>
  <VCard radius="small">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1"> Master Divisi</h3>
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

            <Column field="id" header="Kode" :style="{ width: '90px' }" :hidden="true"></Column>
            <Column field="divisi" header="Nama Divisi" :sortable="true"></Column>
            <Column :exportable="false" header="Action" :style="{ width: '100px' }">
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
                    <span class="dark-inverted">Divisi </span>
                    <span>{{ item.divisi }}</span>
                  </div>
                  <VDropdown icon="feather:more-vertical" spaced right>
                    <template #content>
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
            <VField label="Nama Divisi">
              <VControl icon="feather:search">
                <input v-model="filters" class="input custom-text-filter" placeholder="Cari Nama Divisi..." />
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
    <VModal :open="modalInput" title="Form Master Divisi" actions="right" @close="modalInput = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Nama Divisi">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.divisi" placeholder="Masukkan Nama Divisi"
                    class="is-rounded" />
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
  title: 'Master Divisi - ' + import.meta.env.VITE_PROJECT,
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
      items.divisi.match(new RegExp(filters.value, 'i'))
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
    '/sysadmin/master-ppra-divisi?offset=' + offset +
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

function loadData() {
  dataSource.value = []
  useApi().get(
    `/sysadmin/master-ppra-divisi?statusenabled=${item.value.aktif}`).then((response: any) => {
      dataSource.value = response.data
    })
}

function add() {
  clear()
  modalInput.value = true
}

function edit(e: any) {
  item.value.id = e.id
  item.value.divisi = e.divisi
  modalInput.value = true
}

async function save() {
  
  if (!item.value.divisi) {
    useToaster().error('Nama Divisi Harus Diisi')
    return
  }

  var objSave =
  {
    'datanya': {
      'id': item.value.id ? item.value.id : '',
      'divisi': item.value.divisi,
    }
  }
  isLoadingTT.value = true
  await useApi().post(
    `/sysadmin/save-master-ppra-divisi`, objSave).then((response: any) => {
      isLoadingTT.value = false
      clear()
      loadData()
    }, (error) => {
      isLoadingTT.value = false
    })
}

async function deleterow(e: any) {
  await useApi().post(
    `/sysadmin/delete-master-ppra-divisi`, { 'id': e.id }).then((response: any) => {
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
loadData()
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

</style>