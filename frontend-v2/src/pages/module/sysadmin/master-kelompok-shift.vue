<template>
  <ConfirmDialog />
  <VCard>
    <div class="columns is-multiline" style="margin-top:1rem">
      <div class="column is-4 ">
        <img src="/images/avatars/label/kelompokShift.png" class="ks" />
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
              <VField label="Nama Kelompok Shift">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kelompokshiftkerja" placeholder="kelompok Shift" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12" v-if="item.id" style="display:flex; padding: 0px;">
              <VField class="column is-8" label="Factor Rate">
                <VControl icon="feather:bookmark">
                  <VInput type="number" v-model="item.factorrate" placeholder="factor rate" class="is-rounded" />
                </VControl>
              </VField>
              <VField class="column is-4">
                <VLabel class="ml-2">Aktivasi</VLabel>
                <VControl class="is-pulled-right">
                  <VSwitchBlock style="padding-top:3px" v-model="item.statusenabled" label="Aktif" color="danger" />
                </VControl>
              </VField>
            </div>
            <div v-else class="column is-12">
              <VField label="Factor Rate">
                <VControl icon="feather:bookmark">
                  <VInput type="number" v-model="item.factorrate" placeholder="Factor Rate" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Operator Factor Rate">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.operatorfactorrate" placeholder="Operator Factor Rate"
                    class="is-rounded" />
                </VControl>
              </VField>
            </div>

            <div v-if="item.id" class="column is-12">
              <VButton @click="save()" :loading="isLoadingBtn" type="button" icon="feather:edit" class="is-fullwidth mr-3"
                color="info" raised>
                Update Data
              </VButton>
              <VButton @click="clear()" type="button" icon="feather:x-circle"
                class="is-fullwidth is-outlined is-warning mt-3" raised>
                Batal Edit
              </VButton>
            </div>
            <div v-else class="column is-12">
              <VButton @click="save()" :loading="isLoadingBtn" type="button" icon="feather:save" class="is-fullwidth mr-3"
                color="success" raised>
                Simpan Data
              </VButton>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-8" style="margin-top: 2.7rem;">
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
        </div>

        <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
          <DataTable :value="dataSourcefiltered" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="5"
            :rowsPerPageOptions="[5, 10, 25]"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

            <Column field="no" header="#" :sortable="true"></Column>
            <Column field="kelompokshiftkerja" header="Kelompok Shift Kerja" :sortable="true"></Column>
            <Column field="kodeexternal" header="Kode"></Column>
            <Column field="status" header="Status"></Column>
            <Column :exportable="false" header="Action" style="text-align: center;">
              <template #body="slotProps">
                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                  v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" v-if="item.aktif == true" icon="fas fa-trash" class="mr-3" color="danger"
                  circle outlined raised v-tooltip.top="'Hapus'" @click="DialogConfirm(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" v-else icon="fas fa-trash" class="mr-3" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" disabled>
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
                  <VAvatar size="medium" picture="/images/avatars/svg/produk.svg" color="primary" squared bordered />
                  <div class="meta">
                    <span class="dark-inverted">{{ item.kelompokshiftkerja }}</span>
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
                      <a role="menuitem" class="dropdown-item is-media" v-if="item.aktif == true"
                        @click="DialogConfirm(item)">
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

    <VModal :open="modalDetail" title="Detail Kelompok Shift" actions="right" @close="modalDetail = false, clear()">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Kelompok Produk">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kelompokshiftkerja" placeholder="Kelompok Produk" class="is-rounded"
                    readonly style="cursor:pointer" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Kode External">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kodeexternal" placeholder="Kode External" class="is-rounded" readonly
                    style="cursor:pointer" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Status Aktivasi">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.status" placeholder="status" class="is-rounded" readonly
                    style="cursor:pointer" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Operator Factor Rate">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.operatorfactorrate" placeholder="Kode External" class="is-rounded"
                    readonly style="cursor:pointer" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Factor Rate">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.factorrate" placeholder="status" class="is-rounded" readonly
                    style="cursor:pointer" />
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
import { useApi } from '/@src/composable/useApi'
import { ref, computed } from 'vue'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputSwitch from 'primevue/inputswitch';
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'

import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Kelompok Shift - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const confirm = useConfirm()
const item: any = ref({
  aktif: true
})

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

const selectView: any = ref()
selectView.value = 'list'
let isLoadingBtn: any = ref(false)
let isLoading: any = ref(false)

const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.kelompokshiftkerja.match(new RegExp(filters.value, 'i'))
    )
  })
})

const fetchData = async () => {
  isLoading.value = true

  let StatusEnabled = item.value.aktif ? 'statusenabled=' + item.value.aktif : 'statusenabled=' + item.value.aktif

  await useApi().get('/sysadmin/master-kelompok-shift?' + StatusEnabled).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    })
    dataSource.value = response.data
  })
  isLoading.value = false

}

const save = async () => {
  if (!item.value.kelompokshiftkerja) {
    useToaster().error('Kelompok Shift harus di isi')
    return
  }
  var objSave =
  {
    'kelompokshift': {
      'id': item.value.id ? item.value.id : '',
      'kelompokshiftkerja': item.value.kelompokshiftkerja,
      'factorrate': item.value.factorrate ? item.value.factorrate : '',
      'statusenabled': item.value.statusenabled,
      'operatorfactorrate': item.value.operatorfactorrate ? item.value.operatorfactorrate : '',
    }

  }

  isLoadingBtn.value = true
  await useApi().post(`/sysadmin/master-kelompok-shift/save`, objSave).then((response: any) => {
    isLoadingBtn.value = false
    clear()
    fetchData()
  }, (error) => {
    isLoadingBtn.value = false
  })
}

const deleteItem = async (e: any) => {
  isLoading.value = true
  await useApi().post(`/sysadmin/master-kelompok-shift/delete`, { 'id': e.id }).then((response: any) => {
    clear()
    fetchData()
    isLoading.value = false
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
    reject: () => { },
  })
}

const edit = (e: any) => {
  item.value.id = e.id
  item.value.factorrate = e.factorrate
  item.value.kelompokshiftkerja = e.kelompokshiftkerja
  item.value.statusenabled = e.statusenabled
  item.value.operatorfactorrate = e.operatorfactorrate
}

const detail = (e: any) => {
  item.value.id = e.id
  item.value.kodeexternal = e.kodeexternal
  item.value.status = e.status
  item.value.factorrate = e.factorrate
  item.value.kelompokshiftkerja = e.kelompokshiftkerja
  item.value.operatorfactorrate = e.operatorfactorrate
  modalDetail.value = true
}

const clear = () => {
  item.value.id = ''
  item.value.kelompokshiftkerja = ''
  item.value.factorrate = ''
  item.value.operatorfactorrate = ''
}

const changeView = (e: any) => {
  selectView.value = e
}

const filter = () => {
  fetchData()
}

filter()
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>