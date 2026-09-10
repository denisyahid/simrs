<template>
  <VCard radius="small">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Daftar Map Produk To Kelas</h3>
    </div>
    <div class="columns is-multiline">
      <div class="column is-9">
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
            <VButton color="primary" class="is-pulled-right mr-3" outlined raised>
              <RouterLink :to="{ name: 'module-sysadmin-mapping-paket-to-produk' }">
                <i class="fa fa-plus"></i> Mapping Paket
              </RouterLink>
            </VButton>
          </div>
        </div>

        <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
          <DataTable :value="dataSourcefiltered" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

            <Column field="id" header="Kode"></Column>
            <Column field="namaproduk" header="Nama Produk" :sortable="true"></Column>
            <Column field="namapaket" header="Nama Paket" :sortable="true"></Column>
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
          <TransitionGroup name="list" tag="div" class="columns is-multiline">
            <!--Grid item-->
            <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-12">
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner">
                  <VAvatar size="medium" picture="/images/avatars/svg/map-produk.svg" color="primary" squared
                    bordered />
                  <div class="meta">
                    <span class="dark-inverted">Produk : {{ item.namaproduk }}</span>
                    <span>Paket : {{ item.namapaket }}</span>
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
      <div class="column is-3">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1">Filters</h3>
          </div>
          <div class="column is-6">
            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              Clear All
            </a>
          </div>
          <div class="column is-12">
            <VControl icon="feather:search">
              <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
            </VControl>
          </div>
          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Paket</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.namapaket" :options="d_Paket" placeholder="Pilih Paket"
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
import moment from 'moment'
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Map Paket To Produk - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  aktif: true
})
const modalInput = ref(false)
const modalDetail = ref(false)
const d_Paket = ref([])
const d_Kelas = ref([])
const router = useRouter()
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
      items.namaproduk.match(new RegExp(filters.value, 'i'))
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
  let NamaProduk = ''
  let NamaPaket = ''

  if (item.namaproduk) NamaProduk = '&namaproduk' + item.namaproduk
  if (item.value.namapaket) NamaPaket = '&namapaket=' + item.value.namapaket
  dataSource.value = []
  const response = await useApi().get(
    '/sysadmin/master-map-paket-to-produk?offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    NamaProduk + NamaPaket
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource.value = response.data

}

function loadDropdown() {
  d_Paket.value = []
  useApi().get(
    `/sysadmin/master-map-paket-to-produk-dropdown`).then((response: any) => {
      d_Paket.value = response.namapaket.map((e: any) => { return { label: e.namapaket, value: e.id } })
    })
}

function loadData() {
  dataSource.value = []
  useApi().get(
    `/sysadmin/master-map-paket-to-produk?statusenabled=${item.value.aktif}`).then((response: any) => {
      dataSource.value = response.data
    })
}

function edit(e: any) {
  router.push({
    name: 'module-sysadmin-mapping-paket-to-produk',
    query: {
      id: e.id,
    },
  })
}

async function deleterow(e: any) {
  await useApi().post(
    `/sysadmin/delete-map-paket-to-produk`, { 'id': e.id }).then((response: any) => {
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