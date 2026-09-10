<template>
  <ConfirmDialog />
  <VCard>
    <div class="columns is-multiline c-title" style="padding-bottom: 0px;margin-bottom: 0px;">
      <div class="column" style="display: flex;align-items: center;margin-left: 0px;padding-left: 0px;">
        <h3 class="title is-5 mb-2 mr-1" style="z-index:2">Data Kamar</h3>
      </div>
      <div class="column" style="display:flex;justify-content:end">
        <VButton color="primary" icon="fas fa-plus" raised @click="add()">
          Add Kamar
        </VButton>
      </div>
    </div>
    <div style="position: absolute;margin-top: -10rem;">
      <img src="/images/avatars/label/kamar.png" class="kamar" />
    </div>

    <div class="columns is-multiline mt-5">

      <div class="column">
        <div class="user-grid-toolbar">
          <VControl icon="feather:search">
            <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
          </VControl>
          <div class="column is-8 switch-filter" style="margin-right: 15px;">
            <VControl>
              <InputSwitch v-model="item.aktif" @change="fetchData()" />
            </VControl>
            <span>Aktif</span>
          </div>
          <div class="buttons" style="margin-right:-9px;">
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
          <DataTable :value="dataSourcefiltered" class="p-datatable-sm" :paginator="true" :rows="10"
            :rowsPerPageOptions="[5, 10, 25]"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" :loading="isLoadingDelete" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

            <Column field="no" header="#"></Column>
            <Column field="nama" header="Nama Kamar" :sortable="true"></Column>
            <Column field="namakelas" header="Kelas Kamar" :sortable="true"></Column>
            <Column field="namaruangan" header="Ruangan" :sortable="true"></Column>
            <Column field="jumlakamarkosong" header="Tersedia" :sortable="true"></Column>
            <Column field="status" header="Keaktifan" :sortable="true"></Column>
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
                    <VButton color="danger" v-tooltip.top="'Hapus'" outlined @click="DialogConfirm(slotProps.data)"><i
                        class="fas fa-trash-alt" aria-hidden="true"></i></VButton>
                  </VControl>
                </VField>
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
                  <VAvatar size="medium" picture="/images/avatars/label/departemen.svg" color="primary" squared
                    bordered />
                  <div class="meta">
                    <span class="dark-inverted">{{ item.namakamar }}</span>
                    <span class="mt-1">{{ item.namakelas }}</span>
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

    <VModal :open="modalInput" title="Input Data Kamar" actions="right" @close="modalInput = false">
      <template #content>
        <form class="modal-form">
          <div class="column is-12 pl-0 pr-0">
            <VField label="Kamar">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.namakamar" placeholder="Nama Kamar" class="is-rounded" />
              </VControl>
            </VField>
          </div>

          <div class="columns pl-0 pr-0 mt-1">
            <div class="column is-5">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Kelas</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectkelasfk" :options="d_kelas" placeholder="Pilih data"
                    :searchable="true" />
                </VControl>
              </VField>
            </div>

            <div class="column is-7">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Ruangan</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectruanganfk" :options="d_ruangan" placeholder="Pilih data"
                    :searchable="true" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="columns pl-0 pr-0" v-if="item.id">
            <div class="column is-4">
              <VField label="Kamar terisi">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jumlakamarisi" placeholder="Kamar terisi" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Kamar kosong">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jumlakamarkosong" placeholder="Kamar kosong" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VLabel>Status Aktivasi</VLabel>
                <VControl class="is-pulled-right">
                  <VSwitchBlock v-model="item.statusenabled" color="danger" @change="changeSwitch(item.statusenabled)" />
                </VControl>
              </VField>
            </div>
          </div>

          <div v-else class="columns  pl-0 pr-0">
            <div class="column is-6">
              <VField label="Kamar terisi">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jumlakamarisi" placeholder="Kamar terisi" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Kamar kosong">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jumlakamarkosong" placeholder="Kamar kosong" class="is-rounded" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 pl-0 pr-0 pt-0">
            <VField label="Keterangan">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.keterangan" placeholder="Keterangan" class="is-rounded" />
              </VControl>
            </VField>
          </div>

        </form>
      </template>

      <template #action>
        <VButton v-if="item.id" icon="feather:plus" @click="save()" :loading="isLoadingTT" color="primary" raised>Update
        </VButton>
        <VButton v-else icon="feather:plus" @click="save()" :loading="isLoadingTT" color="primary" raised>Simpan
        </VButton>
      </template>
    </VModal>

    <VModal :open="modalDetail" title="Detail Departemen" actions="right" @close="modalDetail = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">

            <div class="column is-12">
              <VField label="Kamar">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namakamar" placeholder="Nama Kamar" class="is-rounded" />
                </VControl>
              </VField>
            </div>

            <div class="column is-5">
              <VField label="Kelas">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namakelas" placeholder="Kelas" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-7">
              <VField label="Ruangan">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namaruangan" placeholder="Nama Kamar" class="is-rounded" />
                </VControl>
              </VField>
            </div>



            <div class="column is-3">
              <VField label="Kamar terisi">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jumlakamarisi" placeholder="Terisi" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Kamar kosong">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jumlakamarkosong" placeholder="Kosong" class="is-rounded" />
                </VControl>
              </VField>
            </div>

            <div class="column is-6">
              <VField label="Kasus Penyakit">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kasuspenyakit" placeholder="Kasus Penyakit" class="is-rounded" />
                </VControl>
              </VField>
            </div>

            <div class="column is-12">
              <VField label="Keterangan">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.keterangan" placeholder="Keterangan" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Tanggal Update">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.tglupdate" placeholder="Tanggal Update" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Ruang Perawat">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.name" placeholder="Keterangan" class="is-rounded" />
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
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputSwitch from 'primevue/inputswitch'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Kamar - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const confirm = useConfirm()
const modalInput = ref(false)
const modalEdit = ref(false)
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

let d_kelas: any = ref([])
let d_ruangan: any = ref([])

const selectView: any = ref()
selectView.value = 'list'
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let isLoadingDelete: any = ref(false)

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
      items.namakamar.match(new RegExp(filters.value, 'i'))
    )
  })
})

const route = useRoute()

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  fetchData()
})

const fetchData = async () => {
  isLoading.value = true
  // let limit: any = currentPage.value.limit
  // let offset: any = route.query.page ? route.query.page : 1
  // offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let StatusEnabled = ''

  item.value.aktif ? StatusEnabled = '&statusenabled=' + item.value.aktif : StatusEnabled = '&statusenabled=false'

  await useApi().get('/sysadmin/master-kamar?statusenabled=' + item.value.aktif)
    .then((response) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
        element.nama = (element.namakamar.length > 40) ? element.namakamar.substring(0, 40) + '...' : element.nama = element.namakamar
      });
      dataSource.value = response.data
      isLoading.value = false
    })

}

const loadDropdown = async () => {
  await useApi().get(`/sysadmin/master-kamar/select-item`).then((response) => {
    d_kelas.value = response.namakelas.map((e: any) => {
      return { label: e.namakelas, value: e.id }
    })
    d_ruangan.value = response.namaruangan.map((e: any) => {
      return { label: e.namaruangan, value: e.id }
    })
  })
}



const add = () => {
  clear()
  modalInput.value = true
}

const edit = (e: any) => {
  item.value.id = e.id
  item.value.namabentukproduk = e.namabentukproduk
  item.value.namaexternal = e.namaexternal
  item.value.reportdisplay = e.reportdisplay
  item.value.objectkelasfk = e.objectkelasfk
  item.value.objectruanganfk = e.objectruanganfk
  item.value.objectkasuspenyakitfk = e.objectkasuspenyakitfk
  item.value.jumlakamarkosong = e.jumlakamarkosong
  item.value.jumlakamarisi = e.jumlakamarisi
  item.value.keterangan = e.keterangan
  item.value.kodesirs = e.kodesirs
  item.value.statusenabled = e.statusenabled
  item.value.namakamar = e.namakamar
  item.value.kasuspenyakit = e.kasuspenyakit
  item.value.tglupdate = new Date()
  modalInput.value = true
}
const detail = (e: any) => {
  item.value.id = e.id
  item.value.namabentukproduk = e.namabentukproduk
  item.value.namaexternal = e.namaexternal
  item.value.reportdisplay = e.reportdisplay
  item.value.kasuspenyakit = e.kasuspenyakit
  item.value.namakelas = e.namakelas
  item.value.namaruangan = e.namaruangan
  item.value.name = e.name
  item.value.jumlakamarkosong = e.jumlakamarkosong
  item.value.jumlakamarisi = e.jumlakamarisi
  item.value.keterangan = e.keterangan
  item.value.kodesirs = e.kodesirs
  item.value.tglupdate = e.tglupdate
  item.value.namakamar = e.namakamar
  modalDetail.value = true
}

const save = async () => {
  if (!item.value.namakamar) {
    useToaster().error('Nama Kamar harus di isi')
    return
  }
  if (!item.value.objectruanganfk) {
    useToaster().error('Ruangan harus di isi')
    return
  }
  if (!item.value.objectkelasfk) {
    useToaster().error('Kelas harus di isi')
    return
  }
  var objSave =
  {
    'kamar': {
      'id': item.value.id ? item.value.id : '',
      'namakamar': item.value.namakamar,
      'objectkelasfk': item.value.objectkelasfk,
      'objectruanganfk': item.value.objectruanganfk,
      'objectkasuspenyakitfk': item.value.objectkasuspenyakitfk ? item.value.objectkasuspenyakitfk : '',
      'jumlakamarkosong': item.value.jumlakamarkosong,
      'jumlakamarisi': item.value.jumlakamarisi,
      'keterangan': item.value.keterangan,
      'statusenabled': item.value.statusenabled,
      'norec': item.value.norec ? item.value.norec : null,
      'qtybed': item.value.qtybed ? item.value.qtybed : null,
      'kodesirs': item.value.kodesirs ? item.value.kodesirs : null,
      'tglupdate': item.value.tglupdate ? item.value.tglupdate : null,
    }
  }
  isLoadingTT.value = true
  await useApi().post(
    `/sysadmin/master-kamar/save`, objSave).then((response: any) => {
      isLoadingTT.value = false
      modalInput.value = false
      clear()
      fetchData()
    }, (error) => {
      isLoadingTT.value = false
      // console.log(error)
    })
}

async function deleteItem(e: any) {
  isLoadingDelete.value = true
  await useApi().post(
    `/sysadmin/master-kamar/delete`, { 'id': e.id }).then((response: any) => {
      isLoadingDelete.value = false
      fetchData()
    }, (error) => {
      isLoadingDelete.value = false
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

const clear = () => {
  item.value.id = ''
  item.value.namabentukproduk = ''
  item.value.namaexternal = ''
  item.value.reportdisplay = ''
  item.value.kasuspenyakit = ''
  item.value.namakelas = ''
  item.value.objectkelasfk = ''
  item.value.namaruangan = ''
  item.value.name = ''
  item.value.jumlakamarkosong = ''
  item.value.jumlakamarisi = ''
  item.value.keterangan = ''
  item.value.kodesirs = ''
  item.value.tglupdate = ''
  item.value.namakamar = ''
}
function changeView(e: any) {
  selectView.value = e
}
function changeSwitch(e: any) {
  fetchData()
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