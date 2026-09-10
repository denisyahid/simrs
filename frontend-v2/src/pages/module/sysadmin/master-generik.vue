<template>
  <ConfirmDialog />
  <VCard>
    <!-- <h3 class="title is-5 mb-2 mr-1">Daftar Produk</h3> -->
    <VTabs class="tabsInner" id="custom" selected="generik" :tabs="[
      { label: 'Generik', value: 'generik', icon: 'fas fa-users' },
      { label: 'Jenis Generik', value: 'jenisgenerik', icon: 'feather:box' },
    ]">

      <template #tab="{ activeValue }">
        <p v-if="activeValue === 'generik'">
          <VCard>
            <div class="columns column">
              <h3 class="title is-5 mb-2 mr-1">Generik</h3>
            </div>

            <div class="columns is-multiline">

              <div class="column is-8">
                <div class="user-grid-toolbar">
                  <VControl icon="feather:search">
                    <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
                  </VControl>
                  <div class="column is-6 switch-filter">
                    <VControl>
                      <InputSwitch v-model="item.aktif" @change="fetchData()" />
                    </VControl>
                    <span>Aktif</span>
                  </div>
                  <div class="buttons ml-4">
                    <VField v-slot="{ id }" class="is-icon-select">
                      <VControl class="mr-0">
                        <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name"
                          :options="d_View" :searchable="true" track-by="name" mode="single"
                          @select="changeView(selectView)" autocomplete="off">
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
                </div>

                <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
                  <DataTable :value="dataSourcefiltered" class="p-datatable-sm" :loading="isLoadingDelete"
                    :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                    <Column field="no" header="#"></Column>
                    <Column field="generik" header="Generik" :sortable="true"></Column>
                    <Column field="jenisgenerik" header="Jenis Generik" :sortable="true"></Column>
                    <Column field="kodeexternal" header="Kode" :sortable="true"></Column>
                    <Column field="status" header="status" :sortable="true"></Column>
                    <Column :exportable="false" header="Action" style="text-align:center;">
                      <template #body="slotProps">
                        <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                          v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                        </VIconButton>
                        <VIconButton type="button" icon="fas fa-trash" class="mr-3" color="danger" circle outlined raised
                          v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
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
                          <VAvatar size="medium" picture="/images/avatars/icon/ic_generic.png" color="primary" squared
                            bordered />
                          <div class="meta">
                            <span class="dark-inverted">{{ item.generik }}</span>
                            <!-- <span> Departemen : {{ item.namadepartemen }}</span> -->
                          </div>
                          <VTag :color="item.status_c" :label="item.status" style="margin-left:25px" />
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
                              <a role="menuitem" class="dropdown-item is-media" @click="dialogConfirm(item)">
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
                <img src="/images/avatars/label/generic.png" class="generic" alt="Generik">
                <VCard>
                  <div class="columns is-multiline">
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
                      <VField label="Generik">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.generik" placeholder="Generik" class="is-rounded" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12" v-if="item.id" style="display:flex; padding: 0px;">
                      <VField class="column is-12 is-rounded-select is-autocomplete-select">
                        <VLabel>Jenis Generik</VLabel>
                        <VControl icon="feather:search">
                          <Multiselect mode="single" v-model="item.golongangenerikfk" :options="d_jenisGenerik"
                            placeholder="Pilih data" :searchable="true" />
                        </VControl>
                      </VField>

                      <!-- <VField class="column is-4">
                        <VLabel class="ml-2">Aktivasi</VLabel>
                        <VControl class="is-pulled-right">
                          <VSwitchBlock style="padding-top:3px" v-model="item.statusenabled" label="Aktif"
                            color="danger"/>
                        </VControl>
                      </VField> -->
                    </div>
                    <div class="column is-12" v-else>
                      <VField class="is-rounded-select is-autocomplete-select">
                        <VLabel>Jenis Generik</VLabel>
                        <VControl icon="feather:search">
                          <Multiselect mode="single" v-model="item.golongangenerikfk" :options="d_jenisGenerik"
                            placeholder="Pilih data" :searchable="true" />
                        </VControl>
                      </VField>
                    </div>

                    <div v-if="item.id" class="column is-12">
                      <VButton @click="save()" :loading="isLoadingTT" type="button" icon="feather:edit"
                        class="is-fullwidth mr-3" color="info" raised>
                        Update Data
                      </VButton>
                      <VButton @click="clear()" type="button" icon="feather:x-circle"
                        class="is-fullwidth is-outlined is-warning mt-3" raised>
                        Batal Edit
                      </VButton>
                    </div>
                    <div v-else class="column is-12">
                      <VButton @click="save()" :loading="isLoadingTT" type="button" icon="feather:save"
                        class="is-fullwidth mr-3" color="success" raised>
                        Simpan Data
                      </VButton>
                    </div>
                  </div>
                </VCard>
              </div>

            </div>
            <VModal :open="modalDetail" title="Detail Generik" actions="right" @close="modalDetail = false, clear()">
              <template #content>
                <form class="modal-form">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <VField label="Generik">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.generik" placeholder="Generik" class="is-rounded" readonly
                            style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <VField label="Jenis Generik">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.jenisgenerik" placeholder="Jenis Generik" class="is-rounded"
                            readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <VField label="Kode External">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.kodeexternal" placeholder="Kode External" class="is-rounded"
                            readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <VField label="Status Aktivasi">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.status" placeholder="Status Aktivasi" class="is-rounded"
                            readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </form>
              </template>

            </VModal>
          </VCard>
        </p>


        <p v-else-if="activeValue === 'jenisgenerik'">
          <MasterJenisgenerik></MasterJenisgenerik>
        </p>
      </template>

    </VTabs>
  </VCard>
</template>
  
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import MasterJenisgenerik from './master-jenis-generik.vue'
import ConfirmDialog from 'primevue/confirmdialog'
import InputSwitch from 'primevue/inputswitch'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'

useHead({
  title: 'Transmedic - Generik',
})
const confirm = useConfirm()
let dataSource: any = ref([])
const item: any = ref({ aktif: true })

const d_jenisGenerik = ref([])

const modalDetail = ref(false)

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
let isLoadingDelete: any = ref(false)

const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.generik.match(new RegExp(filters.value, 'i'))
    )
  })
})

const fetchData = async () => {
  isLoading.value = true
  await useApi().get(`/sysadmin/master-generik?statusenabled=${item.value.aktif}`).then((response) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response.data
    isLoading.value = false
  })
}

const loadDropdown = async () => {
  d_jenisGenerik.value
  useApi().get(`/sysadmin/master-generik/select-item`).then((response: any) => {
    d_jenisGenerik.value = response.jenisgenerik.map((e: any) => {
      return { label: e.jenisgenerik, value: e.id }
    })
  })
}

const save = async () => {
  if (!item.value.generik) {
    useToaster().error('Generik harus di isi')
    return
  }
  var objSave =
  {
    'generik': {
      'id': item.value.id ? item.value.id : '',
      'generik': item.value.generik,
      'statusenabled': item.value.statusenabled,
      'statuspulang': item.value.statuspulang,
      'golongangenerikfk': item.value.golongangenerikfk ? item.value.golongangenerikfk : '',
      'norec': item.value.norec ? item.value.norec : '',
    }
  }
  isLoadingTT.value = true
  await useApi().post(
    `/sysadmin/master-generik/save`, objSave).then((response: any) => {
      isLoadingTT.value = false
      clear()
      fetchData()
    }, (error) => {
      isLoadingTT.value = false
      console.log(error)
    })
}

const deleteItem = async (e: any) => {
  isLoadingDelete.value = true
  await useApi().post(
    `/sysadmin/master-generik/delete`, { 'id': e.id }).then((response: any) => {
      clear()
      fetchData()
      isLoadingDelete.value = false
    }, (error) => {
    })
}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      deleteItem(e)

    },
    reject: () => { },
  })
}

const changeView = (e: any) => {
  selectView.value = e
}

const edit = (e: any) => {
  item.value.id = e.id
  item.value.generik = e.generik
  item.value.statusenabled = e.statusenabled
  item.value.golongangenerikfk = e.golongangenerikfk
}
const detail = (e: any) => {
  console.log(e)
  item.value.id = e.id
  item.value.generik = e.generik
  item.value.kodeexternal = e.kodeexternal
  item.value.status = e.status
  item.value.jenisgenerik = e.jenisgenerik
  modalDetail.value = true
}

const clear = () => {
  item.value.id = ''
  item.value.generik = ''
  item.value.golongangenerikfk = ''
}

fetchData()
loadDropdown()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>