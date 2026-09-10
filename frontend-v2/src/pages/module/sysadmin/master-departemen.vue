<template>
  <ConfirmDialog />
  <VCard>
  <div class="columns column">
    <h3 class="title is-5 mb-2 mr-1">Departemen</h3>
  </div>
  <div class="columns is-multiline">
    <div class="column is-8">
      <div class="user-grid-toolbar">
        <VControl icon="feather:search">
          <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
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
          <VControl class="is-pulled-right">
            <VSwitchBlock v-model="item.aktif" label="Aktif" color="danger" @change="changeSwitch(item.aktif)" />
          </VControl>
        </div>
      </div>

      <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
        <DataTable :value="dataSourcefiltered" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

          <Column field="no" header="Kode"></Column>
          <Column field="namadepartemen" header="Departemen" :sortable="true"></Column>
          <Column field="status" header="Status" :sortable="true"></Column>
          <Column :exportable="false" header="Action">
            <template #body="slotProps">
              <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
              </VIconButton>
              <VIconButton type="button"  v-if="item.aktif == true" icon="fas fa-trash" class="mr-3" color="danger" circle outlined raised
                v-tooltip.top="'Hapus'" @click="DialogConfirm(slotProps.data)">
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
              <div class="tile-grid-item-inner"  @click="edit(item)">
                <VAvatar size="medium" picture="/images/avatars/svg/departemen.svg" color="primary" squared bordered />
                <div class="meta">
                  <span class="dark-inverted">{{ item.namadepartemen }}</span>
                  <span>
                    <VTag :color="item.status_c" :label="item.status" rounded elevated />
                  </span>
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
    
    <div class="column is-4" >
      <img src="/images/avatars/label/validasi.png" alt="" srcset=""
          style="max-width:80%;margin-left: 8rem; margin-top: -7rem; opacity: .7;">
      <VCard style="margin-top: -4rem;">
        <div class="columns is-multiline" >
          <div class="column is-6">
                            <h3 v-if="item.id" class="title is-6 mb-2 mr-1">
                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                                Edit Data
                            </h3>
                            <h3 v-else class="title is-6 mb-2 mr-1">
                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                                Tambah Data
                            </h3>
                        </div>
          <div class="column is-12">
            <VField label="Departemen">
              <VControl icon="feather:home">
                <VInput type="text" v-model="item.namadepartemen" placeholder="Departemen" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column is-8">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel>Report Display</VLabel>
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.reportdisplay" placeholder="Report Display" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <VField class="column is-4" v-if="item.id">
                                <VLabel class="ml-2">Aktivasi</VLabel>
                                <VControl class="is-pulled-right">
                                    <VSwitchBlock style="padding-top:3px" v-model="item.statusenabled" label="Aktif"
                                        color="danger" />
                                </VControl>
                            </VField>
          <div class="column is-12">
                            <div v-if="item.id">
                                <VButton @click="save()" :loading="isLoadingButton" type="button" icon="feather:edit"
                                    class="is-fullwidth mr-3" color="info" raised>
                                    Update Data
                                </VButton>
                                <VButton @click="clear()" type="button" icon="feather:x-circle"
                                    class="is-fullwidth is-outlined is-warning mt-3" raised>
                                    Batal Edit
                                </VButton>
                            </div>
                            <div v-else>
                                <VButton @click="save()" :loading="isLoadingButton" type="button" icon="feather:save"
                                    class="is-fullwidth mr-3" color="success" raised>
                                    Simpan Data
                                </VButton>
                            </div>
                        </div>
        </div>
      </VCard>
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
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import { useViewWrapper } from '/@src/stores/viewWrapper'

useHead({
  title: 'Departemen - Transmedic ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  aktif: true
})
const modalInput = ref(false)
const modalDetail = ref(false)

let dataSource: any = ref([])
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
const d_status = [
  { value: 't', label: 'True' },
  { value: 'f', label: 'False' },
]
const d_ReportDisplay = ref([])
const selectView: any = ref()
selectView.value = 'list'
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)

let isLoadingButton: any = ref(false)
const confirm = useConfirm();
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
      items.namadepartemen.match(new RegExp(filters.value, 'i'))
    )
  })
})

const fetchData = async (e: any) => {
    let statusEnabled = ''
    isLoading.value = true
    if (e == undefined) {
        statusEnabled = 'statusenabled=true'
    } else {
        statusEnabled = 'statusenabled=' + e
    }
    await useApi().get(`/sysadmin/master-departemen?${statusEnabled}`).then((response: any) => {
        response.data.forEach((items: any, i: any) => {
            items.no = i + 1
        })
        isLoading.value = false
        dataSource.value = response.data
    })
}

function loadData() {
  fetchData(true)
}


function add() {
  clear()
  modalInput.value = true
}

function edit(e: any) {
  item.value.id = e.id
  item.value.namadepartemen = e.namadepartemen
  item.value.statusenabled = e.statusenabled
  item.value.reportdisplay = e.reportdisplay

}
function detail(e: any) {
  item.value.id = e.id
  item.value.namadepartemen = e.namadepartemen
  item.value.statusenabled = e.statusenabled
  item.value.reportdisplay = e.reportdisplay

}

async function save() {
  if (!item.value.namadepartemen) {
    useToaster().error('Departemen harus di isi')
    return
  }
  var objSave =
  {
    'departemen': {
      'id': item.value.id ? item.value.id : '',
      'namadepartemen': item.value.namadepartemen,
      'reportdisplay': item.value.reportdisplay ? item.value.reportdisplay : null,
      'statusenabled': item.value.statusenabled,
    }
  }
  isLoadingTT.value = true
  await useApi().post(
    `/sysadmin/save-master-departemen`, objSave).then((response: any) => {
      isLoadingTT.value = false
      clear()
      loadData()
    }, (error) => {
      isLoadingTT.value = false
      // console.log(error)
    })
}

const DialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleterow(e)

        },
        reject: () => { },
    })
}
async function deleterow(e: any) {
  await useApi().post(
    `/sysadmin/delete-master-departemen`, { 'id': e.id }).then((response: any) => {
      fetchData(true)
    }, (error) => {
    })
}

function clear() {
  // item.value = {}
  modalInput.value = false
  item.value.id = ''
  item.value.namadepartemen = ''
  item.value.reportdisplay = ''
}
function changeView(e: any) {
  selectView.value = e
}
function changeSwitch(e: any) {
  fetchData(e)
}


// loadData ()
fetchData(true)
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

</style>