<template>
  <VCard radius="small">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Slotting Online</h3>
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
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

            <Column field="id" header="No"></Column>
            <Column field="namaruangan" header="Nama Ruangan" :sortable="true"></Column>
            <Column field="jambuka" header="Jam Buka"></Column>
            <Column field="jamtutup" header="Jam Tutup"></Column>
            <Column field="quota" header="Kuota"></Column>
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
            <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-6">
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner">
                  <VAvatar size="medium" picture="/images/avatars/svg/religion.svg" color="primary" squared bordered />


                  <div class="meta">
                    <span class="dark-inverted">{{ item.objectruanganfk }}</span>
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
                          <span>Hapus Data</span>
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
            <h3 class="title is-5 mb-3 mr-1">Filters</h3>
          </div>
          <div class="column is-6">
            <VControl class="is-pulled-right">
              <VSwitchBlock v-model="item.aktif" label="Aktif" color="danger" @change="changeSwitch(item.aktif)" />
            </VControl>

          </div>
          <div class="column is-12">
            <VField>
              <VControl icon="feather:search">
                <input v-model="filters" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                  placeholder="Filter Slotting Online..." />
              </VControl>
            </VField>
            <div class="column is-12">
              <VButton @click="filter()" :loading="isLoadingTT" type="button" icon="feather:search"
                class="is-fullwidth mr-3" color="info" raised>
                Apply Filters
              </VButton>
            </div>
          </div>
        </div>
      </div>
    </div>
    <VModal :open="modalInput" title="Add Slotting Online" actions="right" @close="modalInput = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Nama Ruangan</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectruanganfk" :options="d_ruangan"
                    placeholder="Pilih data" :searchable="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Jam Buka">
                <VControl icon="feather:clock">
                  <VInput type="text" v-model="item.jambuka" placeholder="Jam Buka" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Jam Tutup">
                <VControl icon="feather:clock">
                  <VInput type="text" v-model="item.jamtutup" placeholder="Jam Tutup" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Kuota">
                <VControl icon="feather:bookmark">
                  <VInput type="number" v-model="item.quota" placeholder="Kuota" class="is-rounded" />
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
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Status</VLabel>
                <VControl>
                  <VSwitchBlock v-model="item.statusenabled" :options="d_status" label="Aktif" color="danger" />
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
    <VModal :open="modalDetail" title="Detail Slotting Online" actions="right" @close="modalDetail = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Nama Ruangan</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectruanganfk" :options="d_ruangan"
                    placeholder="Pilih data" :searchable="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Jam Buka">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jambuka" placeholder="Jam Buka" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Jam Tutup">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jamtutup" placeholder="Jam Tutup" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Kuota">
                <VControl icon="feather:bookmark">
                  <VInput type="number" v-model="item.quota" placeholder="Kuota" class="is-rounded" />
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
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Status</VLabel>
                <VControl>
                  <VSwitchBlock v-model="item.statusenabled" :options="d_status" label="Aktif" color="danger" />
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
import moment from 'moment'
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Slotting Online - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
  aktif: true
})
const modalInput = ref(false)
const modalDetail = ref(false)

let dataSource: any = ref([])
const d_status = [
  { value: 't', label: 'True' },
  { value: 'f', label: 'False' },
]
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
const d_ruangan = ref([])


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
      items.namaruangan.match(new RegExp(filters.value, 'i'))
    )
  })
})

const route = useRoute()
isLoading.value = false
// listRuanganStok.value = [{ label: 'List A', value: { id: 1, kelompokpasien: 'List A' } }]
// listNamaProduk.value = [{ label: 'List A', value: { id: 1, kelompokpasien: 'List A' } }]
//listDropdown()

async function fetchData() {
  isLoading = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let Ruangan = ''
  let JamBuka = ''
  let JamTutup = ''
  let Quota = ''
  let id = ''
  let StatusEnabled = ''
  if (item.value.namaruangan) Ruangan = '&ruangan=' + item.value.namaruangan
  if (item.value.jambuka) JamBuka = '&jambuka=' + item.value.jambuka
  if (item.value.jamtutup) JamTutup = '&jamtutup=' + item.value.jamtutup
  if (item.value.quota) Quota = '&qouta=' + item.value.quota
  if (item.value.id) id = '&id=' + item.value.id
  if (item.value.aktif) StatusEnabled = '&Statusnabled=' + item.value.id
  const response = await useApi().get(
    '/sysadmin/master-slotting-online?offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    id + Ruangan + JamBuka + JamTutup + Quota + StatusEnabled
  )
  isLoading = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource.value = response.data
  //   dataSource.value.total = response.length
}
function loadData() {
  dataSource.value = []
  useApi().get(
    `/sysadmin/master-slotting-online?statusenabled=${item.value.aktif}`).then((response: any) => {
      dataSource.value = response.data
    })
}
function loadDropdown() {
  d_ruangan.value = []
  useApi().get(
    `/sysadmin/master-slotting-online-dropdown`).then((response: any) => {
      d_ruangan.value = response.namaruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
    })
}

function add() {
  clear()
  modalInput.value = true
}

function edit(e: any) {
  item.id = e.id
  item.value.objectruanganfk = e.objectruanganfk
  item.value.jambuka = e.jambuka
  item.value.jamtutup = e.jamtutup
  item.value.norec = e.norec
  item.value.quota = e.quota
  item.value.statusenabled = e.statusenabled
  modalInput.value = true
}
function detail(e: any) {
  item.id = e.id
  item.value.objectruanganfk = e.objectruanganfk
  item.value.jambuka = e.jambuka
  item.value.jamtutup = e.jamtutup
  item.value.norec = e.norec
  item.value.quota = e.quota
  item.value.statusenabled = e.statusenabled
  modalDetail.value = true
}

async function save() {
  if (!item.value.objectruanganfk) {
    useToaster().error('Slotting Online harus di isi')
    return
  }
  if (!item.value.jambuka) {
    useToaster().error('Jam Buka harus di isi')
    return
  }
  if (!item.value.jamtutup) {
    useToaster().error('Jam Tutup harus di isi')
    return
  }
  var objSave =
  {
    'slottingonline': {
      'id': item.value.id ? item.value.id : '',
      'objectruanganfk': item.value.objectruanganfk,
      'jambuka': item.value.jambuka,
      'jamtutup': item.value.jamtutup,
      'quota': item.value.quota ? item.value.quota : null,
      'norec': item.value.norec ? item.value.norec : null,
      'statusenabled': item.value.statusenabled,
    }
  }
  isLoadingTT.value = true
  await useApi().post(
    `/sysadmin/save-slotting-online`, objSave).then((response: any) => {
      isLoadingTT.value = false
      clear()
      loadData()
    }, (error) => {
      isLoadingTT.value = false
      // console.log(error)
    })
}

async function deleterow(e: any) {
  await useApi().post(
    `/sysadmin/delete-slotting-online`, { 'id': e.id }).then((response: any) => {
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
function changeSwitch(e: any) {
  loadData()
}
function clearFilter() {
  fetchData()
}
filter()
loadDropdown()
loadData()
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/main.scss';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';


</style>