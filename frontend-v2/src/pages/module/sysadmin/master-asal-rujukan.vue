<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Asal Rujukan</h3>
    </div>
    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="user-grid-toolbar">
          <VField>
            <VControl icon="feather:search">
              <input v-model="filters" type="text" class="input is-rounded" placeholder="Filter Asal Rujukan..." />
            </VControl>
          </VField>
          <VControl class="is-pulled-right">
            <VSwitchBlock v-model="isAktif" label="Aktif" color="danger" />
          </VControl>
          <div class="buttons">
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
            :loading="isLoading"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

            <Column field="no" header="No" style="text-align: center;"></Column>
            <Column field="asalrujukan" header="Asal Rujukan" :sortable="true"></Column>
            <Column field="kdasalrujukan" header="Kode Rujukan" :sortable="true"></Column>
            <Column :exportable="false" header="Status" style="text-align: center;">
              <template #body="slotProps">
                <VTag :color="isAktif == true ? 'primary' : 'danger'" :label="slotProps.data.status" rounded />
              </template>
            </Column>
            <Column :exportable="false" header="Action" style="text-align: center;">
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
        <div class="user-grid user-grid-v4" v-else-if="selectView == 'grid'">

          <TransitionGroup name="list" tag="div" class="columns is-multiline is-flex-tablet-p is-half-tablet-p">
            <!--Grid item-->
            <div v-if="isLoading" v-for="dumy in 8" :key="dumy" class="column is-3">
              <div class="grid-item">
                <VPlaceloadAvatar class="mx-1" size="large" style="display: inline-block;" />
                <VPlaceloadText class="mt-3 mb-3" />
                <VButton placeload="40px" color="warning" style="cursor: not-allowed;"> Button </VButton>
              </div>
            </div>

            <div v-else v-for="item in dataSourcefiltered" :key="item.id" class="column is-3">

              <div class="grid-item">
                <VAvatar size="big" picture="/images/avatars/svg/rujukan.svg" squared />

                <h3 class="dark-inverted">{{ item.asalrujukan }}</h3>
                <p>Kode Rujukan : {{ item.kdasalrujukan }}</p>
                <VTag :color="item.status_c" :label="item.status" rounded />
                <div class="button-wrap has-text-centered">
                  <VButton color="warning" raised @click="edit(item)">
                    <span class="icon">
                      <i aria-hidden="true" class="fas fa-edit"></i>
                    </span>
                    <span>Edit Data</span>
                  </VButton>
                  <div>
                    <a class="dark-inverted-hover" @click="deleterow(item)">
                      Hapus Data</a>
                  </div>
                </div>
              </div>
            </div>
          </TransitionGroup>
        </div>
      </div>

    </div>
    <VModal :open="modalInput" title="Tambah Asal Rujukan" actions="right" @close="modalInput = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Asal Rujukan">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.asalrujukan" placeholder="Asal Rujukan" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Kode Asal Rujukan">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kdasalrujukan" placeholder="Kode Asal Rujukan" class="is-rounded" />
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
    <VModal :open="modalDetail" title="Detail Asal Rujukan" actions="right" @close="modalDetail = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Asal Rujukan">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.asalrujukan" placeholder="Asal Rujukan" class="is-rounded" />
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
              <VField label="Kode Asal Rujukan">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kdasalrujukan" placeholder="Kode Asal Rujukan" class="is-rounded" />
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
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment from 'moment'
useHead({
  title: 'Asal Rujukan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({})
const isAktif = ref(true)
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
selectView.value = 'grid'
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)

const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.asalrujukan.match(new RegExp(filters.value, 'i'))
    )
  })
})

async function fetchData() {

  isLoading.value = true
  await useApi().get(`/sysadmin/master-asal-rujukan?statusenabled=${isAktif.value}`).then((response) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response.data
    isLoading.value = false
  }).catch((e) => {
    isLoading.value = false
  })
}

function add() {
  clear()
  modalInput.value = true
}

function edit(e: any) {
  item.value.id = e.id
  item.value.asalrujukan = e.asalrujukan
  item.value.kdasalrujukan = e.kdasalrujukan
  item.value.statusenabled = e.statusenabled
  modalInput.value = true
}
function detail(e: any) {
  item.value.id = e.id
  item.value.asalrujukan = e.asalrujukan
  item.value.kdasalrujukan = e.kdasalrujukan
  item.value.statusenabled = e.statusenabled
  modalDetail.value = true
}

async function save() {
  if (!item.value.asalrujukan) {
    useToaster().error('Asal Rujukan harus di isi')
    return
  }
  isLoading.value = true
  var objSave =
  {
    'asalrujukan': {
      'id': item.value.id ? item.value.id : '',
      'asalrujukan': item.value.asalrujukan,
      'kdasalrujukan': item.value.kdasalrujukan ? item.value.kdasalrujukan : null,
      'statusenabled': item.value.statusenabled,
    }
  }
  isLoadingTT.value = true
  await useApi().post(
    `/sysadmin/save-asal-rujukan`, objSave).then((response: any) => {
      isLoadingTT.value = false
      isLoading.value = false
      clear()
      fetchData()
    }, (error) => {
      isLoadingTT.value = false
      isLoading.value = false
      // console.log(error)
    })
}

async function deleterow(e: any) {
  isLoading.value = true
  await useApi().post(
    `/sysadmin/delete-asal-rujukan`, { 'id': e.id }).then((response: any) => {
      fetchData()
      isLoading.value = false
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

fetchData()


watch(isAktif, () => {
  fetchData()
}
)


</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/main.scss';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

@import '/@src/scss/module/sysadmin/master-data.scss';
</style>