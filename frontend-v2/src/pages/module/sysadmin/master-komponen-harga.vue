<template>
  <ConfirmDialog />
  <VCard>
    <!-- <h3 class="title is-5 mb-2 mr-1">Daftar Produk</h3> -->
    <VTabs id="custom" class="tabsInner" selected="komponenharga" :tabs="[
      { label: 'Komponen Harga', value: 'komponenharga', icon: 'fas fa-users' },
      { label: 'Jenis Komponen Harga', value: 'jeniskomponenharga', icon: 'feather:box' },
    ]">

      <template #tab="{ activeValue }">
        <p v-if="activeValue === 'komponenharga'">
        <div class="columns is-multiline">
          <div class="column is-8" style="margin-top:2.4rem">
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
              </div>
              <div class="column is-6 switch-filter" style="margin-right: 15px;">
                <VControl>
                  <InputSwitch v-model="item.aktif" @change="fetchData()"/>
                </VControl>
                <span>Aktif</span>
              </div>
              <div class="column" style="display:contents">
                <a type="button" color="info" outlined raised>
                  <VButton color="primary" raised @click="add()">
                    <i class="fa fa-plus mr-3"></i>Komponen Harga
                  </VButton>
                </a>
              </div>

            </div>

            <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
              <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="5"
                :rowsPerPageOptions="[5, 10, 25]"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                <Column field="no" header="#"></Column>
                <Column field="komponenharga" header="Komponen Harga" :sortable="true"></Column>
                <Column field="jeniskomponenharga" header="Jenis Komponen Harga" :sortable="true"></Column>
                <Column field="status" header="Status Aktivasi" :sortable="true"></Column>
                <Column :exportable="false" header="Action" style="text-align:center">
                  <template #body="slotProps">
                    <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                      v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                    </VIconButton>
                    <VIconButton type="button" v-if="item.aktif == true" icon="fas fa-trash" class="mr-3" color="danger"
                      circle outlined raised v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                    </VIconButton>
                    <VIconButton type="button" v-else icon="fas fa-trash" class="mr-3" color="danger" circle outlined
                      raised v-tooltip.top="'Hapus'" disabled>
                    </VIconButton>
                  </template>
                </Column>
              </DataTable>
            </div>
            <div class="tile-grid tile-grid-v1" v-else-if="selectView == 'grid'">
              <TransitionGroup name="list" tag="div" class="columns is-multiline">
                <!--Grid item-->
                <div v-for="(item, key) in dataSource" :key="key" class="column is-6">
                  <div class="tile-grid-item">
                    <div class="tile-grid-item-inner">
                      <VAvatar size="medium" picture="/images/avatars/icon/ic-diagnosa.png" color="primary" squared
                        bordered />
                      <div class="meta">
                        <span class="dark-inverted">{{ item.komponenharga }}</span>
                        <span>{{ item.jeniskomponenharga }}</span>
                        <!-- <span> Departemen : {{ item.kategoridiagnosa }}</span> -->
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
                          <a role="menuitem" class="dropdown-item is-media" v-if="item.statusenabled == true"
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
            </div>
          </div>
          <div class="column is-4" style="padding-left: 0px; margin-top:6.6rem">
            <VCard>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <h3 class="title is-5 mb-2 mr-1">Filter</h3>
                </div>
                <div class="column is-6" style="display: flex;justify-content: end;padding-right: 14px;">
                  <a @click="clearFilter()" type="button" class="is-pulled-right" color="info" outlined raised>
                    Kembali
                  </a>
                </div>
                <div class="column is-12">
                  <VField>
                    <VControl icon="feather:search">
                      <input v-model="item.komponenharga" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                        placeholder="Komponen Harga" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField class="is-autocomplete-select">
                    <VLabel>Departemen</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="item.objectdepartemenfk" :options="d_Departemen"
                        placeholder="Departemen" :searchable="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField class="is-autocomplete-select">
                    <VLabel>Produk</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="item.objectprodukpkfk" :options="d_Produk" placeholder="Produk"
                        :searchable="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField class="is-autocomplete-select">
                    <VLabel>Jenis Komponen Harga</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="item.objectjeniskomponenhargafk" :options="d_JKHarga"
                        placeholder="Jenis Komponen Harga" :searchable="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VButton @click="filter()" :loading="isSearch" type="button" icon="feather:search"
                    class="is-fullwidth mr-3" color="info" raised>
                    Pencarian
                  </VButton>
                </div>
              </div>
            </VCard>
          </div>
        </div>

        <VModal :open="modalInput" title="Input Komponen Harga" actions="right" @close="modalInput = false, clear()">
          <template #content>
            <form class="modal-form">
              <div class="columns is-multiline">

                <div class="column is-12">
                  <VField label="Komponen Harga">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.komponenharga" placeholder="Komponen Harga" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-6">
                  <VField class="is-rounded-select is-autocomplete-select">
                    <VLabel>Departemen</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="item.objectdepartemenfk" :options="d_Departemen"
                        placeholder="Pilih Departemen" :searchable="true" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-6">
                  <VField class="is-rounded-select is-autocomplete-select">
                    <VLabel>Jenis Komponen Harga</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="item.objectjeniskomponenhargafk" :options="d_JKHarga"
                        placeholder="Jenis Komponen Harga" :searchable="true" />
                    </VControl>
                  </VField>
                </div>

                <div class="columns is-multiline" v-if="item.id"
                  style="margin-left: 2px;margin-bottom: 0px;padding-bottom: 0px;padding-top: 10px;">
                  <div class="column is-6">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Produk</VLabel>
                      <VControl icon="feather:search">
                        <Multiselect mode="single" v-model="item.objectprodukpkfk" :options="d_Produk"
                          placeholder="Pilih Produk" :searchable="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Status Aktivasi" style="padding-right: 120px;">
                      <VControl>
                        <VSwitchBlock v-model="item.statusenabled" color="danger" label="Aktif" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Cito" style="position:absolute">
                      <VControl raw subcontrol>
                        <VCheckbox style="margin-left: -12px;margin-top: -7px;" v-model="item.iscito" label="True"/>
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-6">
                    <VField label="Nomer Urut">
                      <VControl icon="feather:bookmark">
                        <VInput type="text" v-model="item.nourut" placeholder="Nomer Urut" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6" style="padding-right:18px">
                    <VField label="Nilai Normal">
                      <VControl icon="feather:bookmark">
                        <VInput type="text" v-model="item.nilainormal" placeholder="Nilai Normal" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>

                </div>
                <div class="columns is-multiline" v-else style="margin:0px">
                  <div class="column is-12">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Produk</VLabel>
                      <VControl icon="feather:search">
                        <Multiselect mode="single" v-model="item.objectprodukpkfk" :options="d_Produk"
                          placeholder="Pilih Produk" :searchable="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="Nomer Urut">
                      <VControl icon="feather:bookmark">
                        <VInput type="text" v-model="item.nourut" placeholder="Nomer Urut" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="Nilai Normal">
                      <VControl icon="feather:bookmark">
                        <VInput type="text" v-model="item.nilainormal" placeholder="Nilai Normal" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Cito">
                      <VControl raw subcontrol>
                        <VCheckbox style="margin-left: -12px;margin-top: -7px;" v-model="item.iscito" label="True"/>
                      </VControl>
                    </VField>
                  </div>
                </div>

              </div>
            </form>
          </template>

          <template #action>
            <VButton v-if="item.id" icon="feather:plus" @click="save()" :loading="isLoadingBtn" color="primary" raised>
              Update
            </VButton>
            <VButton v-else icon="feather:plus" @click="save()" :loading="isLoadingBtn" color="primary" raised>Simpan
            </VButton>
          </template>
        </VModal>

        <VModal :open="modalDetail" title="Detail Komponen Harga" actions="right" @close="modalDetail = false, clear()">
          <template #content>
            <form class="modal-form">
              <div class="columns is-multiline">

                <div class="column is-12">
                  <VField label="Komponen Harga">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.komponenharga" placeholder="Komponen Harga" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-6">
                  <VField label="Departemen">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.namadepartemen" placeholder="Departemen" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-6">
                  <VField label="Jenis Komponen Harga">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.jeniskomponenharga" placeholder="Jenis Komponen Harga"
                        class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-6">
                  <VField label="Produk">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.namaproduk" placeholder="Produk" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3">
                  <VField label="Nomer Urut">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.nourut" placeholder="Produk" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3">
                  <VField label="Nilai Normal" style="overflow: hidden ;">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.nilainormal" placeholder="Nilai Normal" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-5">
                  <VField label="Kode External">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.kodeexternal" placeholder="Kode External" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3">
                  <VField label="Cito">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.iscito" placeholder="Cito" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4">
                  <VField label="Status Aktivasi">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.status" placeholder="status" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

              </div>
            </form>
          </template>

          <!-- <template #action>
            <VButton v-if="item.id" icon="feather:plus" @click="save()" :loading="isLoadingBtn" color="primary" raised>
              Update
            </VButton>
            <VButton v-else icon="feather:plus" @click="save()" :loading="isLoadingBtn" color="primary" raised>Simpan
            </VButton>
          </template> -->
        </VModal>

        </p>


        <p v-else-if="activeValue === 'jeniskomponenharga'">
          <MasterJenisKomponenHarga></MasterJenisKomponenHarga>
        </p>
      </template>

    </VTabs>
  </VCard>
</template>
  
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ConfirmDialog from 'primevue/confirmdialog'
import InputSwitch from 'primevue/inputswitch'
import { useConfirm } from 'primevue/useconfirm'
import MasterJenisKomponenHarga from './master-jenis-komponen-harga.vue'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'

useHead({
  title: 'Transmedic - Komponen Harga',
})

const confirm = useConfirm()
let dataSource: any = ref([])
const item: any = ref({
  aktif: true
})

const d_Departemen = ref([])
const d_Produk = ref([])
const d_JKHarga = ref([])
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

const modalInput = ref(false)
let isLoadingBtn: any = ref(false)
let isSearch: any = ref(false)
let isLoading: any = ref(false)

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const route = useRoute()
isLoading.value = false


const fetchData = async () => {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let komponenHarga = ''
  let namaDepartemen = ''
  let namaProduk = ''
  let jkHarga = ''

  if (item.value.objectdepartemenfk) namaDepartemen = '&objectdepartemenfk=' + item.value.objectdepartemenfk
  if (item.value.objectprodukpkfk) namaProduk = '&objectprodukpkfk=' + item.value.objectprodukpkfk
  if (item.value.objectjeniskomponenhargafk) jkHarga = '&objectjeniskomponenhargafk=' + item.value.objectjeniskomponenhargafk
  if (item.value.komponenharga) komponenHarga = '&komponenharga=' + item.value.komponenharga

  await useApi().get(
    '/sysadmin/master-komponen-harga?statusenabled=' + item.value.aktif + '&offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows + komponenHarga + namaDepartemen + namaProduk + jkHarga
  ).then((response) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    })
    dataSource.value = response.data
    isLoading.value = false
    isSearch.value = false
  })
}

const loadDropdown = async () => {

  const response = await useApi().get('/sysadmin/master-komponen-harga/select-item')
  d_Departemen.value = response.departemen.map((e: any) => {
    return { label: e.namadepartemen, value: e.id }
  })

  d_Produk.value = response.produk.map((e: any) => {
    return { label: e.namaproduk, value: e.id }
  })

  d_JKHarga.value = response.jeniskomponenharga.map((e: any) => {
    return { label: e.jeniskomponenharga, value: e.id }
  })
}

const changeView = (e: any) => {
  selectView.value = e
}

const save = async () => {
  if (!item.value.komponenharga) {
    useToaster().error('Status Keluar harus di isi')
    return
  }
  if (!item.value.nilainormal) {
    useToaster().error('Nilai Normal harus di isi')
    return
  }
  var objSave =
  {
    'komponenharga': {
      'id': item.value.id ? item.value.id : '',
      'komponenharga': item.value.komponenharga,
      'statusenabled': item.value.statusenabled,
      'nourut': item.value.nourut ? item.value.nourut : '',
      'objectdepartemenfk': item.value.objectdepartemenfk ? item.value.objectdepartemenfk : '',
      'objectjeniskomponenhargafk': item.value.objectjeniskomponenhargafk ? item.value.objectjeniskomponenhargafk : '',
      'objectprodukpkfk': item.value.objectprodukpkfk ? item.value.objectprodukpkfk : '',
      'norec': item.value.norec ? item.value.norec : '',
      'nilainormal': item.value.nilainormal ? item.value.nilainormal : '',
      'iscito': item.value.iscito ? item.value.iscito : false,
    }
  }
  isLoadingBtn.value = true
  await useApi().post(
    `/sysadmin/master-komponen-harga/save`, objSave).then((response: any) => {
      modalInput.value = false
      clear()
      fetchData()
    }, (error) => {
      modalInput.value = true
      isLoadingBtn.value = false
      // console.log(error)
    })
}

const deleteItem = async (e: any) => {
  isLoading.value = true
  await useApi().post(
    `/sysadmin/master-komponen-harga/delete`, { 'id': e.id }).then((response: any) => {
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

const edit = (e: any) => {
  item.value.id = e.id
  item.value.komponenharga = e.komponenharga
  item.value.statusenabled = e.statusenabled
  item.value.objectdepartemenfk = e.objectdepartemenfk
  item.value.objectprodukpkfk = e.objectprodukpkfk
  item.value.objectjeniskomponenhargafk = e.objectjeniskomponenhargafk
  item.value.kodeexternal = e.kodeexternal
  item.value.nilainormal = e.nilainormal
  item.value.iscito = e.iscito
  item.value.nourut = e.nourut
  isLoadingBtn.value = false
  modalInput.value = true
}

const detail = (e: any) => {
  item.value.id = e.id
  item.value.komponenharga = e.komponenharga
  item.value.status = e.status
  item.value.namadepartemen = e.namadepartemen
  item.value.namaproduk = e.namaproduk
  item.value.jeniskomponenharga = e.jeniskomponenharga
  item.value.kodeexternal = e.kodeexternal
  item.value.nilainormal = e.nilainormal
  item.value.iscito = e.iscito
  item.value.nourut = e.nourut
  modalDetail.value = true
}

const add = () => {
  clear()
  modalInput.value = true
}

const clearFilter = () => {
  item.value.komponenharga = ''
  item.value.objectjeniskomponenhargafk = ''
  item.value.objectdepartemenfk = ''
  item.value.objectprodukpkfk = ''
  item.qAktif = false
  fetchData()
}

const filter = () => {
  isSearch.value = true
  fetchData()
}

const clear = () => {
  item.value.id = ''
  item.value.komponenharga = ''
  item.value.objectjeniskomponenhargafk = ''
  item.value.objectdepartemenfk = ''
  item.value.objectprodukpkfk = ''
  item.value.statusenabled = ''
  item.value.nilainormal = ''
  item.value.iscito = ''
  item.value.nourut = ''
}

loadDropdown()
fetchData()


</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.fs-075 {
  font-size: 0.9rem;
}
.p-inputswitch.p-inputswitch-checked .p-inputswitch-slider {
  background: #e62964;
}
.switch-filter{
  justify-content: end;align-items: center;display: flex;padding: 0px;
}
.p-inputswitch-checked {
  .p-inputswitch-slider:hover {
    background: #e62964 !important;
  }
}

.is-navbar {
  .form-layout {
    margin-top: 30px;
  }
}

.modal-card-body {
  overflow: hidden;
}


.mix {
  margin-bottom: -1.7rem;
  max-width: 83%;
  margin-left: 3rem;
  margin-top: -5.5rem;
  opacity: .9;
}

.form-switch.is-danger {
  margin-top: 4px;
}

.is-pulled-right {
  padding-top: 10px;
  display: contents;
}

#custom {
  .tabs-inner {
    margin-left: -21px;
    padding-left: 20px;
    padding-top: 18px;
    margin-top: -21px;
    border-top-left-radius: 11px;
    border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  }
}

.tabs-wrapper.is-triple-slider.is-squared {
  display: none;
}

.form-layout {
  // max-width: 540px;
  margin: 0 auto;

  &.is-separate {
    // max-width: 1040px;

    .form-outer {
      background: none;
      border: none;

      .form-body {
        display: flex;

        .form-section {
          flex-grow: 2;
          padding: 10px;
          width: 50%;

          .form-section-inner {
            @include vuero-s-card;

            padding: 40px;

            &.has-padding-bottom {
              padding-bottom: 60px;
              height: 100%;
            }

            >h3 {
              font-family: var(--font-alt);
              font-size: 1.2rem;
              font-weight: 600;
              color: var(--dark-text);
              margin-bottom: 30px;
            }

            .columns {
              .column {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
              }
            }

            .radio-boxes {
              display: flex;
              justify-content: space-between;
              margin-left: -8px;
              margin-right: -8px;

              .radio-box {
                position: relative;
                width: calc(50% - 16px);
                margin: 8px;

                &:focus-within {
                  border-radius: 3px;
                  outline-offset: var(--accessibility-focus-outline-offset);
                  outline-width: var(--accessibility-focus-outline-width);
                  outline-style: var(--accessibility-focus-outline-style);
                  outline-color: var(--primary);
                }

                input {
                  position: absolute;
                  top: 0;
                  left: 0;
                  height: 100%;
                  width: 100%;
                  opacity: 0;
                  cursor: pointer;

                  &:checked {
                    +.radio-box-inner {
                      background: var(--primary);
                      border-color: var(--primary);
                      box-shadow: var(--primary-box-shadow);

                      .fee,
                      p {
                        color: var(--smoke-white);
                      }
                    }
                  }
                }

                .radio-box-inner {
                  background: var(--white);
                  border: 1px solid var(--fade-grey-dark-3);
                  text-align: center;
                  border-radius: var(--radius);
                  font-family: var(--font);
                  font-weight: 600;
                  font-size: 0.9rem;
                  transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                    height 0.3s, width 0.3s;
                  padding: 30px 20px;

                  .fee {
                    font-family: var(--font);
                    font-weight: 700;
                    color: var(--dark-text);
                    font-size: 2.4rem;
                    line-height: 1;

                    span {
                      &::after {
                        content: '$';
                        position: relative;
                        top: -10px;
                        font-size: 1.5rem;
                      }
                    }
                  }

                  p {
                    font-family: var(--font-alt);
                  }
                }
              }
            }

            .control {
              >p {
                padding-top: 12px;

                >span {
                  display: block;
                  font-size: 0.9rem;

                  span {
                    font-weight: 500;
                    color: var(--dark-text);
                  }
                }
              }
            }
          }

          .form-section-outer {
            .checkboxes {
              padding: 16px 0;

              .checkbox {
                padding: 0;
                font-size: 0.9rem;
              }
            }

            .button-wrap {
              .button {
                min-height: 60px;
                font-size: 1.05rem;
                font-weight: 600;
                font-family: var(--font-alt);
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .form-layout {
    &.is-separate {
      .form-outer {
        background: none !important;

        .form-body {
          .form-section {
            .form-section-inner {
              @include vuero-card--dark;

              >h3 {
                color: var(--dark-dark-text);
              }

              .radio-boxes {
                .radio-box {
                  input:checked+.radio-box-inner {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    .fee,
                    p {
                      color: var(--smoke-white);
                    }
                  }

                  .radio-box-inner {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);

                    .fee {
                      color: var(--dark-dark-text);
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;
          flex-direction: column;

          .form-section {
            width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;

          // flex-direction: column;

          .form-section {
            // width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

.all-projects {
  .all-projects-header {
    display: flex;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    margin-bottom: 1.5rem;

    .header-item {
      width: 25%;
      border-right: 1px solid var(--fade-grey-dark-3);

      &:last-child {
        border-right: none;
      }

      .item-inner {
        text-align: center;

        .lnil,
        .lnir {
          font-size: 2.2rem;
          margin-bottom: 6px;
          color: var(--primary);
        }

        span {
          display: block;
          font-family: var(--font);
          font-weight: 600;
          font-size: 1.4rem;
          color: var(--dark-text);
        }

        p {
          font-family: var(--font-alt);
        }
      }
    }
  }

  .projects-card-grid {
    .grid-item {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 220px;
      padding: 20px;
      background: var(--white);
      border: 1px solid var(--fade-grey-dark-3);
      border-radius: var(--radius-large);

      .top-section {
        .head {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 8px;

          h3 {
            font-size: 1rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
          }
        }

        .body {
          p {
            font-family: var(--font);
            color: var(--light-text);
          }
        }
      }

      .bottom-section {
        display: flex;

        .foot-block {
          margin-right: 30px;

          .heading {
            font-family: var(--font-alt);
            font-size: 0.75rem;
            color: var(--light-text-dark-22);
          }

          >p {
            padding-top: 5px;
          }

          .developers {
            display: flex;

            .v-avatar {
              margin-right: 6px;
            }
          }
        }
      }
    }
  }
}

.heading {
  font-family: var(--font-alt);
  font-size: 0.75rem;
  color: var(--light-text-dark-22);
}

.is-dark {
  .all-projects {
    .all-projects-header {
      background: var(--dark-sidebar-light-6);
      border-color: var(--dark-sidebar-light-12);

      .header-item {
        border-color: var(--dark-sidebar-light-18);

        span {
          color: var(--dark-dark-text);
        }

        i {
          color: var(--primary) !important;
        }
      }
    }

    .projects-card-grid {
      .grid-item {
        background: var(--dark-sidebar-light-6);
        border-color: var(--dark-sidebar-light-12);

        .top-section {
          .head {
            h3 {
              color: var(--dark-dark-text);
            }
          }
        }

        .bottom-section {
          .foot-block {
            .heading {
              color: var(--light-text-dark-12);
            }
          }
        }
      }
    }
  }
}
</style>