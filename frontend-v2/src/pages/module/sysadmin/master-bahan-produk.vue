<template>
  <ConfirmDialog />
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Bahan Produk</h3>
    </div>

    <div class="columns is-multiline">

      <div class="column is-4" style="margin-top: 87px;">
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
              <VField label="Bahan Produk">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namabahanproduk" placeholder="Bahan Produk" class="is-rounded" />
                </VControl>
              </VField>
            </div>

            <div class="column is-12" v-if="item.id" style="display:flex; padding: 0px;">
              <VField class="column is-8 is-rounded-select is-autocomplete-select">
                <VLabel>Jenis Kondisi Pasien</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectdepartemenfk" :options="d_departemen"
                    placeholder="Pilih data" :searchable="true" />
                </VControl>
              </VField>

              <VField class="column is-4">
                <VLabel class="ml-2">Aktivasi</VLabel>
                <VControl class="is-pulled-right">
                  <VSwitchBlock style="padding-top:3px" v-model="item.statusenabled" label="Aktif" color="danger"/>
                </VControl>
              </VField>
            </div>
            <div class="column is-12" v-else>
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Jenis Kondisi Pasien</VLabel>
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
          <div class="column is-6">
            <VControl class="is-pulled-right">
              <VSwitchBlock v-model="item.isAktif" label="Aktif" color="danger" @change="changeSwitch(item.isAktif)" />
            </VControl>
          </div>
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

          </div>
        </div>

        <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
          <DataTable :value="dataSourcefiltered" class="p-datatable-sm" :loading="isLoading" :paginator="true"
            :rows="10" :rowsPerPageOptions="[5, 10, 25]"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

            <Column field="no" header="#"></Column>
            <Column field="namabahanproduk" header="Nama Bahan" :sortable="true"></Column>
            <Column field="namadepartemen" header="Departemen" :sortable="true"></Column>
            <Column field="kelompokproduk" header="Kelompok Produk" :sortable="true"></Column>
            <Column field="status" header="status" :sortable="true"></Column>
            <Column :exportable="false" header="Action" style="text-align:center;">
              <template #body="slotProps">
                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                  v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" icon="fas fa-trash" v-if="item.isAktif == true" class="mr-3" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" icon="fas fa-trash" v-else class="mr-3" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" disabled>
                </VIconButton>
              </template>
            </Column>
          </DataTable>
        </div>
        <div class="tile-grid tile-grid-v1" v-else-if="selectView == 'grid'">
          <div class="page-placeholder" v-if="dataSourcefiltered.length == 0">
            <div class="placeholder-content">
              <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
              <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
              <h3>{{ H.assets().notFound }}</h3>
              <p class="is-larger">
                {{ H.assets().notFoundSubtitle }}
              </p>
            </div>
          </div>
          <TransitionGroup name="list" tag="div" class="columns is-multiline">
            <!--Grid item-->
            <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-6">
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner">
                  <VAvatar size="medium" picture="/images/avatars/svg/produk.svg" color="primary" squared bordered />
                  <div class="meta">
                    <span class="dark-inverted">{{ item.namabahanproduk }}</span>
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
                      <a role="menuitem" class="dropdown-item is-media" v-if="item.isAktif == true" @click="dialogConfirm(item)">
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

    <VModal :open="modalDetail" title="Detail Bahan Produk" actions="right" @close="modalDetail = false">

      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-8">
              <VField label="Produsen Produk">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namabahanproduk" placeholder="produsen produk" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Kode External">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kodeexternal" placeholder="Kode" class="is-rounded" />
                </VControl>
              </VField>
            </div>

            <div class="column is-6">
              <VField label="Nama Departemen">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namadepartemen" placeholder="produsen produk" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Kelompok Produk">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kelompokproduk" placeholder="Kelompok Produk" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Report Display">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.reportdisplay" placeholder="report display" class="is-rounded" />
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
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Bahan Produk - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const confirm = useConfirm()

let dataSource: any = ref([])
const item: any = ref({
  isAktif: true
})

let d_KelompokProduk = ref([])
let d_departemen = ref([])


const modalInput = ref(false)
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
let isLoadingBtn: any = ref(false)

const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.namabahanproduk.match(new RegExp(filters.value, 'i'))
    )
  })
})

const fetchData = async () => {
  isLoading.value = true
  let statusEnabled = item.value.isAktif ? `statusenabled=${item.value.isAktif}` : `statusenabled=${item.value.isAktif}`

  await useApi().get(`/sysadmin/master-bahan-produk?${statusEnabled}`).then((response:any)=>{
    response.forEach((elements:any,i:any)=>{
      elements.no = i + 1  
    })
    dataSource.value = response
  })
  isLoading.value = false
}

const loadDropdown = async () => {

  const response = await useApi().get(`/sysadmin/master-bahan-produk/select-item`)
  d_KelompokProduk.value = response.kelompokproduk.map((e: any) => {
    return { label: e.kelompokproduk, value: e.id }
  })
  d_departemen.value = response.namadepartemen.map((e: any) => {
    return { label: e.namadepartemen, value: e.id }
  })

}

const save = async() => {
  if (!item.value.namabahanproduk) {
    useToaster().error('Produsen Produk harus diisi')
    return
  }
  else if(!item.value.objectkelompokprodukfk) {
    useToaster().error('Kelompok Produk Wajib diisi')
    return
  }
  else if (!item.value.objectdepartemenfk) {
    useToaster().error('Departemen Produk Wajib diisi')
    return
  }

  var objSave =
  {
    'namabahanproduk': {
      'id': item.value.id ? item.value.id : '',
      'namabahanproduk': item.value.namabahanproduk,
      'statusenabled': item.value.statusenabled,
      'objectkelompokprodukfk': item.value.objectkelompokprodukfk ? item.value.objectkelompokprodukfk : '',
      'objectdepartemenfk': item.value.objectdepartemenfk ? item.value.objectdepartemenfk : '',
      'norec': item.value.norec ? item.value.norec : '',
    }
  }
  isLoadingBtn.value = true
  await useApi().post(`/sysadmin/master-bahan-produk/save`, objSave).then((response: any) => {
      isLoadingBtn.value = false
      clear()
      fetchData()
    }, (error) => {
      isLoadingBtn.value = false
      console.log(error)
    })
}

const deleteItem = async (e: any) => {
  isLoading.value = true
  await useApi().post(`/sysadmin/master-bahan-produk/delete`, { 'id': e.id }).then((response: any) => {
    isLoading.value = false
    fetchData()
  }, (error) => {
    console.log(error)
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

const edit = (e: any) => {
  item.value.id = e.id
  item.value.namabahanproduk = e.namabahanproduk
  item.value.statusenabled = e.statusenabled
  item.value.objectkelompokprodukfk = e.objectkelompokprodukfk
  item.value.objectdepartemenfk = e.objectdepartemenfk
}
const detail = (e: any) => {
  item.value.id = e.id
  item.value.namabahanproduk = e.namabahanproduk
  item.value.namaexternal = e.namaexternal
  item.value.kodeexternal = e.kodeexternal
  item.value.reportdisplay = e.reportdisplay
  item.value.namadepartemen = e.namadepartemen
  item.value.kelompokproduk = e.kelompokproduk
  modalDetail.value = true
}

const clear = () => {
  item.value.id = ''
  item.value.namabahanproduk = ''
  item.value.objectdepartemenfk = ''
  item.value.objectkelompokprodukfk = ''
  modalInput.value = false
}
const changeSwitch = (e: any) => {
  fetchData()
}
const changeView = (e: any) => {
  selectView.value = e
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