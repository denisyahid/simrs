<template>
  <ConfirmDialog />
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Ruangan</h3>
    </div>

    <div class="columns is-multiline">

      <div class="column is-9">

        <div class="user-grid-toolbar">
          <VField class="switch-filter">
            <VControl>
                  <InputSwitch v-model="item.aktif" @change="fetchData()"/>
                </VControl>
                <span>Aktif</span>
          </VField>
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
              <span> Tambah Data</span>
            </VButton>
          </div>
        </div>

        <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
          <DataTable :value="dataSourcefiltered" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

            <Column field="no" header="Kode"></Column>
            <Column field="namaruangan" header="Ruangan" :sortable="true"></Column>
            <Column field="namadepartemen" header="Nama Departemen" :sortable="true"></Column>
            <Column :exportable="false" header="Action">
              <template #body="slotProps">
                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                  v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" icon="fas fa-trash" class="mr-3" color="danger" circle outlined raised
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
                <div class="tile-grid-item-inner">
                  <VAvatar size="medium" :picture="item.icons != null ? item.icons : '/images/avatars/svg/room.svg'"
                    color="primary" squared bordered />
                  <div class="meta">
                    <span class="dark-inverted">{{ item.namaruangan }}</span>
                    <span>{{ item.namadepartemen }}</span>
                    <VTag :color="item.kdsubspesialisbpjs == null ? 'danger':'success'" :label="(item.kdsubspesialisbpjs?item.kdsubspesialisbpjs:'') + (item.namasubspesialisbpjs!=null?' - ' +item.namasubspesialisbpjs:'')" />
                    <br/>
                    <VTag :color="item.noruangan == null ? 'warning':'info'" :label="'Kode Antrian : ' + (item.noruangan?item.noruangan:'-')" class="mt-1" />
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
                      <a role="menuitem" class="dropdown-item is-media" @click="DialogConfirm(item)">
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
            <h3 class="title is-5 mb-2 mr-1" style="margin-top: 1rem;">Filter</h3>
          </div>
          <div class="column is-6">
            <img src="/images/avatars/svg/keluar.svg" alt="" srcset="" style="margin-top: -3rem;" />
          </div>
          <div class="column is-12">
            <VField style="margin-top: -1rem;">
              <VControl icon="feather:search">
                <input v-model="filters" class="input custom-text-filter" placeholder="Filter Ruangan" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Departemen</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.namadepartemen" :options="d_departemen"
                  placeholder="Pilih departemen" :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="filter()" :loading="isLoading" type="button" icon="feather:search" class="is-fullwidth mr-3"
              color="info" raised>
              Pencarian
            </VButton>

          </div>
        </div>
      </div>
    </div>
    <template>
    </template>
    <VModal :open="modalInput" title="Tambah Ruangan" actions="right" @close="modalInput = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Nama Ruangan">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namaruangan" placeholder="Nama Ruangan" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column " :class="item.id ? 'is-8' : 'is-12'">
              <VField class=" is-rounded-select is-autocomplete-select">
                <VLabel>Departemen</VLabel>
                <VControl icon="fas fa-home" class="prime-auto">
                  <Dropdown v-model="item.objectdepartemenfk" :options="d_departemens" :optionLabel="'namadepartemen'"
                    placeholder="Departemen" style="width: 100%;" :filter="true" appendTo="body" />
                </VControl>
                <!-- <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectdepartemenfk" :options="d_departemen"
                    placeholder="Pilih data" :searchable="true" />
                </VControl> -->
              </VField>
            </div>

            <div class="column is-4" v-if="item.id">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Status</VLabel>
                <VControl>
                  <VSwitchBlock v-model="item.statusenabled" :options="d_status" label="Aktif" color="danger" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class=" is-rounded-select is-autocomplete-select">
                <VLabel>Sub/Spesialis BPJS</VLabel>
                <VControl icon="fas fa-hospital-symbol" class="prime-auto">
                  <Dropdown v-model="item.spesialis" :options="d_Subspesialis" :optionLabel="'nmsubspesialis'"
                    placeholder="Sub/Spesialis BPJS" style="width: 100%;" :filter="true" appendTo="body"
                    optionGroupLabel="nmpoli" optionGroupChildren="items" @change="changeSub($event)" :showClear="true">
                    <template #optiongroup="slotProps">
                      <div class="flex align-items-center country-item">
                        <i class="fas fa-layer-group mr-2 mt-1" aria-hidden="true"></i>
                        <div>{{ slotProps.option.nmpoli }}</div>
                      </div>
                    </template>
                  </Dropdown>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Kode Subspesialis BPJS</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kdsubspesialisbpjs" placeholder="Kode Subspesialis BPJS"
                    class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Kode Spesialis BPJS</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kdspesialisbpjs" placeholder="Kode Spesialis BPJS"
                    class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Kode Antrian</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.noruangan" placeholder="Kode Antrian"
                    class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField v-slot="{ id }">
                <VLabel>Icons</VLabel>
                <VControl>
                  <Multiselect v-model="item.icons" mode="single" :attrs="{ id }" placeholder="Select an Icon"
                    label="name" :options="selectPoli" :searchable="true" track-by="name" :max-height="145">
                    <template #singlelabel="{ value }">
                      <div class="multiselect-single-label">
                        <img class="select-label-icon" :src="value.icon" alt=""
                          style=" margin: 0 6px 0 0;  height: 26px; min-width: 26px; border-radius: 0.15rem;" />
                        {{ value.name }}
                      </div>
                    </template>
                    <template #option="{ option }">
                      <img class="select-option-icon" :src="option.icon" alt=""
                        style=" margin: 0 6px 0 0; height: 26px; min-width: 26px; border-radius: 0.15rem;" />
                      {{ option.name }}
                    </template>
                  </Multiselect>
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
    <VModal :open="modalDetail" title="Detail Ruangan" actions="right" @close="modalDetail = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Nama Ruangan">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namaruangan" placeholder="Nama Ruangan" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Departemen</VLabel>
                <VControl icon="fas fa-home" class="prime-auto">
                  <Dropdown v-model="item.objectdepartemenfk" :options="d_departemens" :optionLabel="'namadepartemen'"
                    placeholder="Departemen" style="width: 100%;" :filter="true" appendTo="body" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Kode Subspesialis BPJS</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kdinternal" placeholder="Kode Subspesialis BPJS" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Kode Spesialis BPJS</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.noruangan" placeholder="Kode Spesialis BPJS" class="is-rounded" />
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
import InputSwitch from 'primevue/inputswitch'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import Dropdown from 'primevue/dropdown';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'

useHead({
  title: 'Ruangan - ' + import.meta.env.VITE_PROJECT,
})
const route = useRoute()
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(route.query.fullwidth?true: false)

const confirm = useConfirm()
const item: any = ref({
  aktif: true
})

const d_status = [
  { value: 't', label: 'True' },
  { value: 'f', label: 'False' },
]
const selectPoli = H.iconPoli()
const modalInput = ref(false)
const modalDetail = ref(false)
const d_Subspesialis: any = ref([])
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
const selectView: any = ref()
selectView.value = 'grid'
const d_departemen = ref([])
const d_departemens = ref([])
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
      items.namaruangan.match(new RegExp(filters.value, 'i'))
    )
  })
})

isLoading.value = false

async function fetchData() {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let Ruangan = ''
  let NamaDepartemen = ''
  let id = ''
  let StatusEnabled = ''

  if (item.namaruangan) Ruangan = '&ruangan=' + item.namaruangan
  if (item.value.namadepartemen) NamaDepartemen = '&objectdepartemenfk=' + item.value.namadepartemen
  if (item.id) id = '&id=' + item.id
  item.value.aktif ? StatusEnabled = '&statusenabled=' + item.value.aktif : StatusEnabled = '&statusenabled=false'

  const response = await useApi().get(
    '/sysadmin/master-ruangan?statusenabled='+item.value.aktif+'&offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    id + Ruangan + NamaDepartemen + StatusEnabled
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource.value = response.data

}


async function loadDropdown() {
  d_departemen.value = []
  d_departemens.value = []
  await useApi().get(`/sysadmin/master-ruangan-dropdown`).then((response: any) => {
      d_departemens.value = response.namadepartemen
      d_departemen.value = response.namadepartemen.map((e: any) => { return { label: e.namadepartemen, value: e.id } })
    })
}

function add() {
  clear()
  modalInput.value = true
}

function edit(e: any) {

  item.value.id = e.id
  item.value.namaruangan = e.namaruangan
  item.value.objectdepartemenfk = { id: e.objectdepartemenfk, namadepartemen: e.namadepartemen }

  item.value.kdsubspesialisbpjs = e.kdsubspesialisbpjs
  item.value.kdspesialisbpjs = e.kdspesialisbpjs
  item.value.namasubspesialisbpjs = e.namasubspesialisbpjs
  item.value.namaspesialisbpjs = e.namaspesialisbpjs
  delete item.value.spesialis 
  if (e.kdsubspesialisbpjs && e.kdspesialisbpjs) {
    for (let x = 0; x < d_Subspesialis.value.length; x++) {
      const element = d_Subspesialis.value[x];
      if (element.kdpoli == e.kdsubspesialisbpjs) {
        for (let y = 0; y < element.items.length; y++) {
          const element2 = element.items[y];
          if (element2.kdsubspesialis == e.kdsubspesialisbpjs) {
            item.value.spesialis = element2
            changeSub({ value: element2 })
            break
          }
        }
      }
    }
  }

  item.value.statusenabled = e.statusenabled
  item.value.icons = e.icons
  modalInput.value = true
}
function detail(e: any) {
  item.value.id = e.id
  item.value.namaruangan = e.namaruangan
  item.value.kdsubspesialisbpjs = e.kdsubspesialisbpjs
  item.value.kdspesialisbpjs = e.kdspesialisbpjs
  item.value.namasubspesialisbpjs = e.namasubspesialisbpjs
  item.value.namaspesialisbpjs = e.namaspesialisbpjs
  if (e.kdsubspesialisbpjs && e.kdspesialisbpjs) {
    for (let x = 0; x < d_Subspesialis.value.length; x++) {
      const element = d_Subspesialis.value[x];
      if (element.kdpoli == e.kdsubspesialisbpjs) {
        for (let y = 0; y < element.items.length; y++) {
          const element2 = element.items[y];
          if (element2.kdsubspesialis == e.kdsubspesialisbpjs) {
            item.value.spesialis = element2
            changeSub({ value: element2 })
            break
          }
        }
      }
    }
  }

  item.value.objectdepartemenfk = { id: e.objectdepartemenfk, namadepartemen: e.namadepartemen }
  item.value.statusenabled = e.statusenabled
  item.value.icons = e.icons
  item.value.noruangan = e.noruangan
  modalDetail.value = true
}

async function save() {
  if (!item.value.namaruangan) {
    useToaster().error('Nama Ruangan harus di isi')
    return
  }
  if (!item.value.objectdepartemenfk) {
    useToaster().error('Departemen harus di isi')
    return
  }
  var objSave =
  {
    'ruangan': {
      'id': item.value.id ? item.value.id : '',
      'namaruangan': item.value.namaruangan,
      'objectdepartemenfk': item.value.objectdepartemenfk.id,
      'statusenabled': item.value.statusenabled ? item.value.statusenabled : null,
      'kdsubspesialisbpjs': item.value.kdsubspesialisbpjs ? item.value.kdsubspesialisbpjs : null,
      'kdspesialisbpjs': item.value.kdspesialisbpjs ? item.value.kdspesialisbpjs : null,
      'namasubspesialisbpjs': item.value.namasubspesialisbpjs ? item.value.namasubspesialisbpjs : null,
      'namaspesialisbpjs': item.value.namaspesialisbpjs ? item.value.namaspesialisbpjs : null,
      'noruangan': item.value.noruangan ? item.value.noruangan : null,
      'icons': item.value.icons ? item.value.icons : null,
    }
  }
  isLoadingTT.value = true
  await useApi().post(
    `/sysadmin/save-master-ruangan`, objSave).then((response: any) => {
      isLoadingTT.value = false
      clear()
      fetchData()
    }, (error) => {
      isLoadingTT.value = false
      // console.log(error)
    })
}

async function deleterow(e: any) {
  await useApi().post(
    `sysadmin/delete-master-ruangan`, { 'id': e.id }).then((response: any) => {
      fetchData()
    }, (error) => {
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
function filter() {
  fetchData()
}

function clear() {

  item.value.id = ''
  item.value.objectdepartemenfk = ''
  item.value.namaruangan = ''
  item.value.kdsubspesialisbpjs = ''
  item.value.namasubspesialisbpjs = ''
  item.value.namaspesialisbpjs = ''
  item.value.kdspesialisbpjs = ''
  delete item.value.iocns

  modalInput.value = false
}
async function subSpesialisBPJS() {
  let json = {
    "url": "ref/poli",
    "jenis": "antrean",
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    let sama = false
    let arrGroup: any = [];
    for (let i = 0; i < res.response.length; i++) {
      sama = false
      const element2 = res.response[i]
      for (let x = 0; x < arrGroup.length; x++) {
        if (arrGroup[x].kdpoli == res.response[i].kdpoli) {
          sama = true;
        }
      }
      if (sama == false) {
        var dataDetail0: any = [];
        for (let x = 0; x < res.response.length; x++) {
          const element = res.response[x];
          if (element.kdpoli == res.response[i].kdpoli) {
            dataDetail0.push(element)
          };
        }
        let result = {
          "nmpoli": element2.nmpoli,
          "kdpoli": element2.kdpoli,
          'items': dataDetail0
        }
        arrGroup.push(result)
      }
    }
    d_Subspesialis.value = arrGroup

  } else {
    H.alert('error', 'BPJS ' + res.metaData.message)
  }

}
function changeView(e: any) {
  selectView.value = e
}

function changeSub(e: any) {
  item.value.kdsubspesialisbpjs = e.value.kdsubspesialis
  item.value.kdspesialisbpjs = e.value.kdpoli
  item.value.namasubspesialisbpjs = e.value.nmsubspesialis
  item.value.namaspesialisbpjs = e.value.nmpoli
}
loadDropdown()
subSpesialisBPJS()
fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>