<template>
  <VCard>
    <div class="columns column c-title">
      <h3 class="title is-5 mb-2 mr-1">Satuan Besar</h3>
    </div>

    <div class="columns is-multiline" style="margin-top:1rem">
      <div class="column is-4 ">
        <img src="/images/avatars/label/satuan-besar.png" class="mix-2" />
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
              <VField label="Satuan Besar">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.satuanbesar" placeholder="Satuan Besar" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12" v-if="item.id" style="display:flex; padding: 0px;">
              <VField class="column is-8 is-rounded-select is-autocomplete-select">
                <VLabel>Departemen</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectdepartemenfk" :options="d_departemen"
                    placeholder="Pilih data" :searchable="true" />
                </VControl>
              </VField>

              <VField class="column is-4">
                <VLabel class="ml-2">Aktivasi</VLabel>
                <VControl class="is-pulled-right">
                  <VSwitchBlock style="padding-top:3px" v-model="item.statusenabled" label="Aktif" color="danger" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12" v-else>
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Departemen</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectdepartemenfk" :options="d_departemen"
                    placeholder="Pilih data" :searchable="true" />
                </VControl>
              </VField>
            </div>

            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Kelompok Produk</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectkelompokprodukfk" :options="d_KelompokProduk"
                    placeholder="Pilih data" :searchable="true" />
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

            <Column field="no" header="#"></Column>
            <Column field="satuanbesar" header="Satuan Besar" :sortable="true"></Column>
            <Column field="namadepartemen" header="nama departemen"></Column>
            <Column field="status" header="Status"></Column>
            <Column :exportable="false" header="Action" style="text-align: center;">
              <template #body="slotProps">
                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                  v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" v-if="item.aktif == true" icon="fas fa-trash" class="mr-3" color="danger"
                  circle outlined raised v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" v-else icon="fas fa-trash" class="mr-3" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" disabled>
                </VIconButton>
              </template>
            </Column>
          </DataTable>
        </div>
        <div class="tile-grid tile-grid-v1" v-else>

          <TransitionGroup v-if="item.length > 0" name="list" tag="div" class="columns is-multiline">
            <!--Grid item-->
            <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-6">
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner" @click="edit(item)">
                  <VAvatar size="medium" picture="/images/avatars/icon/ic-scale.png" color="primary" squared bordered />
                  <div class="meta">
                    <span class="dark-inverted">{{ item.satuanbesar }}</span>
                    <span class="mt-2">{{ item.kelompokproduk }}</span>
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
                      <a role="menuitem" v-if="item.statusenabled == true" class="dropdown-item is-media"
                        @click="dialogConfirm(item)">
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
          <div class="flex-list-inner" v-else>
            <VPlaceholderSection title="Not found" subtitle="There is no data that match your query." class="my-6">
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderSection>
          </div>
        </div>
      </div>

    </div>

    <VModal :open="modalDetail" title="Detail Satuan Besar" actions="right" @close="modalDetail = false, clear()">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Satuan Besar">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.satuanbesar" placeholder="Satuan Besar" class="is-rounded" readonly
                    style="cursor:pointer" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Departemen">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namadepartemen" placeholder="Departemen" class="is-rounded" readonly
                    style="cursor:pointer" />
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
            <div class="column is-12">
              <VField label="Kelompok Produk">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kelompokproduk" placeholder="Kelompok Produk" class="is-rounded"
                    readonly style="cursor:pointer" />
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
import { ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import InputSwitch from 'primevue/inputswitch'
import Paginator from 'primevue/paginator';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'


import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Satuan Besar - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  aktif: true,
})

const route = useRoute()
const modalDetail = ref(false)
const confirm = useConfirm()
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
const first = ref(0);
const selectView: any = ref()
selectView.value = 'grid'
let isLoadingBtn: any = ref(false)
let isLoading: any = ref(false)
let itemLength:any

let d_departemen = ref([])
let d_KelompokProduk = ref([])

const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.satuanbesar.match(new RegExp(filters.value, 'i')) ||
      items.namadepartemen.match(new RegExp(filters.value, 'i'))
    )
  })
})
const currentPage: any = ref({
  limit: 5,
  rows: 30
})


const fetchData = async () => {
  isLoading.value = true

  await useApi().get(`/sysadmin/master-satuan-besar?statusenabled=${item.value.aktif}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    })
    dataSource.value = response.data
    isLoading.value = false
    item.value.length = response.data.length
  })
}

const save = async () => {
  if (!item.value.satuanbesar) {
    useToaster().error('Satuan Besar harus di isi')
    return
  }
  if (!item.value.objectdepartemenfk) {
    useToaster().error('Departemen harus di isi')
    return
  }
  if (!item.value.objectkelompokprodukfk) {
    useToaster().error('Kelompok Produk harus di isi')
    return
  }
  var objSave =
  {
    'satuanbesar': {
      'id': item.value.id ? item.value.id : '',
      'satuanbesar': item.value.satuanbesar,
      'objectdepartemenfk': item.value.objectdepartemenfk,
      'objectkelompokprodukfk': item.value.objectkelompokprodukfk,
      'statusenabled': item.value.statusenabled,
    }

  }

  isLoadingBtn.value = true
  await useApi().post(
    `/sysadmin/master-satuan-besar/save`, objSave).then((response: any) => {
      isLoadingBtn.value = false
      clear()
      fetchData()
    }, (error) => {
      isLoadingBtn.value = false
    })
}

const deleteItem = async (e: any) => {
  isLoading.value = true
  await useApi().post(
    `/sysadmin/master-satuan-besar/delete`, { 'id': e.id }).then((response: any) => {
      clear()
      fetchData()
      isLoading.value = false
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

const loadDropdown = async () => {

  const response = await useApi().get(`/sysadmin/master-satuan-besar/select-item`)
  d_KelompokProduk.value = response.kelompokproduk.map((e: any) => {
    return { label: e.kelompokproduk, value: e.id }
  })
  d_departemen.value = response.departemen.map((e: any) => {
    return { label: e.namadepartemen, value: e.id }
  })

}

const edit = (e: any) => {
  item.value.id = e.id
  item.value.satuanbesar = e.satuanbesar
  item.value.objectdepartemenfk = e.objectdepartemenfk
  item.value.objectkelompokprodukfk = e.objectkelompokprodukfk
  item.value.statusenabled = e.statusenabled
}

const detail = (e: any) => {
  item.value.id = e.id
  item.value.satuanbesar = e.satuanbesar
  item.value.kodeexternal = e.kodeexternal
  item.value.status = e.status
  item.value.namadepartemen = e.namadepartemen
  item.value.kelompokproduk = e.kelompokproduk
  modalDetail.value = true
}

const clear = () => {
  item.value.id = ''
  item.value.satuanbesar = ''
  item.value.objectdepartemenfk = ''
  item.value.objectkelompokprodukfk = ''
}

const changeView = (e: any) => {
  selectView.value = e
}

const filter = () => {
  fetchData()
}

loadDropdown()
filter()
fetchData()


</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>