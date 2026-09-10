<template>
  <VCard>
    <!-- <h3 class="title is-5 mb-2 mr-1">Daftar Produk</h3> -->
    <VTabs class="tabsInner" selected="shiftkerja" :tabs="[
      { label: 'Shift Kerja', value: 'shiftkerja', icon: 'fas fa-users' },
      { label: 'Kelompok Shift', value: 'kelompokshift', icon: 'feather:box' },
    ]">

      <template #tab="{ activeValue }">
        <p v-if="activeValue === 'shiftkerja'">
          <ConfirmDialog/>
          <VCard>
            <div class="columns is-multiline c-title" style="padding-bottom: 0px;margin-bottom: 0px;">
              <div class="column" style="display: flex;align-items: center;margin-left: 0px;padding-left: 0px;">
                <h3 class="title is-5 mb-2 mr-1" style="z-index:2">Shift Kerja</h3>
              </div>
              <div class="column" style="display:flex;justify-content:end">
                <VButton color="primary" icon="fas fa-plus" raised @click="add()">
                  Tamnbah Shift Kerja
                </VButton>
              </div>
            </div>
            <div style="position: absolute">
              <img src="/images/avatars/label/coba.png" class="shift" />
            </div>

            <div class="columns is-multiline mt-5">

              <div class="column">
                <div class="user-grid-toolbar">
                  <VControl icon="feather:search">
                    <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
                  </VControl>
                  <div class="column is-7 switch-filter">
                    <VControl>
                      <InputSwitch v-model="item.aktif" @change="fetchData()" />
                    </VControl>
                    <span>Aktif</span>
                  </div>
                  <div class="buttons" style="margin-right:-9px;">
                    <VField v-slot="{ id }" class="is-icon-select">
                      <VControl>
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
                  <DataTable :value="dataSourcefiltered" class="p-datatable-sm" :paginator="true" :rows="10"
                    :rowsPerPageOptions="[5, 10, 25]"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" :loading="isLoading" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                    <Column field="no" header="#"></Column>
                    <Column field="namashift" header="Nama Shift" :sortable="true"></Column>
                    <Column field="kelompokshiftkerja" header="Kelompok Shift" :sortable="true"></Column>
                    <Column field="waktupraktek" header="Ruangan" :sortable="true"></Column>
                    <Column field="kodeexternal" header="Kode Shift" :sortable="true"></Column>
                    <Column field="status" header="Status" :sortable="true"></Column>
                    <Column :exportable="false" header="Action">
                      <template #body="slotProps">
                        <VField addons>
                          <VControl>
                            <VButton color="dark" v-tooltip.top="'Detail'" outlined @click="detail(slotProps.data)"><i
                                class="fas fa-bookmark" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl>
                            <VButton color="warning" v-tooltip.top="'Edit'" outlined @click="edit(slotProps.data)"><i
                                class="fas fa-pencil-alt" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl>
                            <VButton color="danger" v-tooltip.top="'Hapus'" outlined @click="DialogConfirm(slotProps.data)">
                              <i class="fas fa-trash-alt" aria-hidden="true"></i>
                            </VButton>
                          </VControl>
                        </VField>
                      </template>
                    </Column>
                  </DataTable>
                </div>
                <div class="tile-grid tile-grid-v1" v-else-if="selectView == 'grid'">
                  <TransitionGroup name="list" tag="div" class="columns is-multiline">
                    <!--Grid item-->
                    <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-4">
                      <div class="tile-grid-item">
                        <div class="tile-grid-item-inner">
                          <VAvatar size="medium" picture="/images/avatars/icon/ic-timeScheduler.png" color="primary" squared
                            bordered />
                          <div class="meta">
                            <span class="dark-inverted">{{ item.namashift }}</span>
                            <span class="mt-1">{{ item.kelompokshiftkerja }}</span>
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
                              <a role="menuitem" class="dropdown-item is-media" @click="DialogConfirm(item)">
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

            </div>

            <VModal :open="modalInput" title="Input Data Shift Kerja" size="large" noclose actions="right"
              @close="modalInput = false,clear()">
              <template #content>
                <form class="modal-form">
                  <div class="columns is-multiline">

                    <div class="column is-6">
                      <VField label="Nama Shift">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.namashift" placeholder="Nama Shift" class="is-rounded" />
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-6">
                      <VField class="is-rounded-select is-autocomplete-select">
                        <VLabel>Kelompok Shift</VLabel>
                        <VControl icon="feather:search">
                          <Multiselect mode="single" v-model="item.objectkelompokshiftfk" :options="d_KelompokShift"
                            placeholder="Pilih data" :searchable="true" />
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-6">
                      <VField class="is-rounded-select is-autocomplete-select">
                        <VLabel>Jadwal Praktek</VLabel>
                        <VControl icon="feather:search">
                          <Multiselect mode="single" v-model="item.objectjadwalpraktekfk" :options="d_JwLPraktek"
                            placeholder="Pilih data" :searchable="true" />
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-3">
                      <VField label="Operator Factor Rate">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.operatorfactorrate" placeholder="Operator Factor Rate"
                            class="is-rounded" />
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-3">
                      <VField label="Jam Masuk">
                        <VControl icon="feather:clock">
                          <VInput type="text" v-model="item.jammasuk" placeholder="Jam Masuk"
                            class="is-rounded" />
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-6" v-if="item.id" style="display:flex;">

                      <VField label="Factor Rate column is-3">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.factorrate" placeholder="Factor Rate" class="is-rounded" />
                        </VControl>
                      </VField>

                      <VField class="column is-3" style="margin-top:3px;padding-top:0px">
                        <VLabel class="ml-4">Aktivasi</VLabel>
                        <VControl class="is-pulled-right">
                          <VSwitchBlock style="padding-top:0px;margin-top:3px;margin-right: -30px; padding-left: 0px;" v-model="item.statusenabled" label="Aktif"
                            color="danger"/>
                        </VControl>
                      </VField>
                      
                    </div>

                    <div v-else class="column is-6">
                      <VField label="Factor Rate">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.factorrate" placeholder="Factor Rate" class="is-rounded" />
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-3">
                      <VField label="Waktu Istirahat">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.waktuistirahat" placeholder="Waktu Istirahat" class="is-rounded" />
                        </VControl>
                      </VField>
                    </div> 
                    
                    <div class="column is-3">
                      <VField label="Jam Pulang">
                        <VControl icon="feather:clock">
                          <VInput type="text" v-model="item.jampulang" placeholder="Jam Pulang" class="is-rounded" />
                        </VControl>
                      </VField>
                    </div>

                  </div>
                </form>
              </template>

              <template #action>
                <VButton v-if="item.id" icon="feather:plus" @click="save()" :loading="isLoadingTT" color="primary"
                  raised>
                  Update
                </VButton>
                <VButton v-else icon="feather:plus" @click="save()" :loading="isLoadingTT" color="primary" raised>Simpan
                </VButton>
              </template>
            </VModal>

            <VModal :open="modalDetail" title="Detail Shift Kerja"  size="large" actions="right" @close="modalDetail = false,clear()">
              <template #content>
                <form class="modal-form">
                  <div class="columns is-multiline">

                    <div class="column is-6">
                      <VField label="Nama Shift">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.namashift" placeholder="Nama Shift" class="is-rounded" readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-6">
                      <VField label="Kelompok Shift">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.kelompokshiftkerja" placeholder="Kelas" class="is-rounded" readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <VField label="Jam Masuk">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.jammasuk" placeholder="Nama Kamar" class="is-rounded" readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <VField label="Jam Pulang">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.jampulang" placeholder="Nama Kamar" class="is-rounded" readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <VField label="Waktu Praktek">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.waktupraktek" placeholder="Nama Kamar" class="is-rounded" readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-3">
                      <VField label="Operator Factor">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.operatorfactorrate" placeholder="Terisi" class="is-rounded" readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField label="Factor Rate">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.factorrate" placeholder="Kosong" class="is-rounded" readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField label="Waktu Istirahat">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.waktuistirahat" placeholder="Kosong" class="is-rounded" readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField label="Status Aktivasi">
                        <VControl icon="feather:bookmark">
                          <VInput type="text" v-model="item.status" placeholder="Kosong" class="is-rounded" readonly style="cursor:pointer" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </form>
              </template>
            </VModal>
          </VCard>
        </p>

        <p v-else-if="activeValue === 'kelompokshift'">
          <MasterKelompokShift />
        </p>

      </template>

    </VTabs>
  </VCard>
</template>
  
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed} from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ConfirmDialog from 'primevue/confirmdialog'
import InputSwitch from 'primevue/inputswitch'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import MasterKelompokShift from './master-kelompok-shift.vue'
import { useToaster } from '/@src/composable/toaster'
useHead({
  title: 'Transmedic - Shift Kerja',
})
const confirm = useConfirm()

const modalInput = ref(false)
const modalDetail = ref(false)
const item: any = ref({
  aktif: true
})
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

let d_JwLPraktek: any = ref([])
let d_KelompokShift: any = ref([])

const selectView: any = ref()
selectView.value = 'list'
let isLoadingTT: any = ref(false)
let isLoading: any = ref(false)

const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.namashift.match(new RegExp(filters.value, 'i'))
    )
  })
})

const fetchData = async ()=>{
  isLoading.value = true

  await useApi().get(`/sysadmin/master-shift-kerja?statusenabled=${item.value.aktif}`).then((response:any)=>{
    response.data.forEach((element:any,i:any)=>{
      element.no = i+1
    })
    dataSource.value = response.data
  })
  isLoading.value = false  
}

const loadDropdown = async ()=>{
  const response = await useApi().get(`/sysadmin/master-shift-kerja/select-item`)
  d_JwLPraktek.value = response.waktupraktek.map((e:any) => {
    return { label: e.waktupraktek, value: e.id }
  })
  d_KelompokShift.value = response.kelompokshiftkerja.map((e:any) => {
    return { label: e.kelompokshiftkerja, value: e.id }
  })
}

const save = async ()=>{
  if (!item.value.namashift) {
    useToaster().error('Nama Shift harus di isi')
    return
  }
  var objSave =
  {
    'shiftkerja': {
      'id': item.value.id ? item.value.id : '',
      'namashift': item.value.namashift,
      'objectkelompokshiftfk': item.value.objectkelompokshiftfk,
      'objectjadwalpraktekfk': item.value.objectjadwalpraktekfk,
      'statusenabled' : item.value.statusenabled,
      'flagketidakhadiran': item.value.flagketidakhadiran,
      'jammasuk': item.value.jammasuk,
      'operatorfactorrate': item.value.operatorfactorrate ? item.value.operatorfactorrate : null,
      'factorrate': item.value.factorrate ? item.value.factorrate : null,
      'waktuistirahat': item.value.waktuistirahat ? item.value.waktuistirahat : null,
      'jampulang': item.value.jampulang ? item.value.jampulang : null,
    }
  }
  isLoadingTT.value = true
  await useApi().post(
    `/sysadmin/master-shift-kerja/save`, objSave).then((response: any) => {
      isLoadingTT.value = false
      modalInput.value = false
      clear()
      fetchData()
    }, (error) => {
      isLoadingTT.value = false
      // console.log(error)
    })
}

const deleteItem = async (e: any)=>{
  isLoading.value = true
  await useApi().post(
    `/sysadmin/master-shift-kerja/delete`, { 'id': e.id }).then((response: any) => {
      isLoading.value = false
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
      deleteItem(e)
    
    },
    reject: () => {},
  })
}

const clear = ()=>{
  item.value.id = ''
  item.value.namashift = ''
  item.value.objectkelompokshiftfk = ''
  item.value.jammasuk = ''
  item.value.objectjadwalpraktekfk = ''
  item.value.operatorfactorrate = ''
  item.value.factorrate = ''
  item.value.jampulang = ''
  item.value.waktuistirahat = ''
  modalInput.value = false
}

const add = () => {
  clear()
  modalInput.value = true
}

const edit = (e: any)=>{
  item.value.id = e.id
  item.value.namashift = e.namashift
  item.value.objectkelompokshiftfk = e.objectkelompokshiftfk
  item.value.jammasuk = e.jammasuk
  item.value.statusenabled = e.statusenabled
  item.value.objectjadwalpraktekfk = e.objectjadwalpraktekfk
  item.value.operatorfactorrate = e.operatorfactorrate
  item.value.factorrate = e.factorrate
  item.value.jampulang = e.jampulang
  item.value.waktuistirahat = e.waktuistirahat
  modalInput.value = true
}
const detail = (e: any)=>{
  item.value.id = e.id
  item.value.namashift = e.namashift
  item.value.kelompokshiftkerja = e.kelompokshiftkerja
  item.value.jammasuk = e.jammasuk
  item.value.jampulang = e.jampulang
  item.value.kodeexternal = e.kodeexternal
  item.value.status = e.status
  item.value.waktupraktek = e.waktupraktek
  item.value.operatorfactorrate = e.operatorfactorrate
  item.value.factorrate = e.factorrate
  item.value.waktuistirahat = e.waktuistirahat
  modalDetail.value = true
}

const changeView = (e: any)=>{
  selectView.value = e
}

loadDropdown()
fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

</style>