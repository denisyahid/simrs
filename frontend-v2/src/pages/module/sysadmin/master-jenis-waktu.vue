<template>
    <ConfirmDialog />
    <VCard>
      <div class="columns column">
        <h3 class="title is-5 mb-2 mr-1">Jenis Waktu</h3>
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
              <Column field="jeniswaktu" header="Jenis Waktu" :sortable="true"></Column>
              <Column field="namadepartemen" header="Nama Departemen"></Column>
              <Column field="kategoryproduk" header="Kategory Produk" :sortable="true"></Column>
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
                      <span class="dark-inverted">{{ item.jeniswaktu }}</span>
                      <span>{{ item.kategoryproduk }}</span>
                      <VTag :color="item.status_c" :label="item.status" />
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
                  <input v-model="filters" class="input custom-text-filter" placeholder="Filter Jenis Waktu" />
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
              <VField class="is-autocomplete-select">
                <VLabel>Kelompok Produk</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.kelompokproduk" :options="d_Kelompok"
                    placeholder="Pilih Kelompok" :searchable="true" />
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
      <VModal :open="modalInput" title="Tambah Jenis Waktu" actions="right" @close="modalInput = false">
        <template #content>
          <form class="modal-form">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField label="Jenis Waktu">
                  <VControl icon="feather:bookmark">
                    <VInput type="text" v-model="item.jeniswaktu" placeholder="Jenis Waktu" />
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
              <VField class="is-autocomplete-select">
                <VLabel>Kelompok Produk</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectkelompokprodukfk" :options="d_Kelompok"
                    placeholder="Pilih Kelompok" :searchable="true" />
                </VControl>
              </VField>
            </div>
              <div class="column is-6">
                <VField>
                  <VLabel>Jam Awal</VLabel>
                  <VControl icon="feather:clock">
                    <VInput type="text" v-model="item.jamawal" placeholder="Jam Awal"
                      />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <VField>
                  <VLabel>Jam Akhir</VLabel>
                  <VControl icon="feather:clock">
                    <VInput type="text" v-model="item.jamakhir" placeholder="Jam Akhir"
                     />
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
      <VModal :open="modalDetail" title="Detail Jenis Waktu" actions="right" @close="modalDetail = false">
        <template #content>
          <form class="modal-form">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField label="Jenis Waktu">
                  <VControl icon="feather:bookmark">
                    <VInput type="text" v-model="item.jeniswaktu" placeholder="Jenis Waktu" class="is-rounded" />
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
    title: 'Jenis Waktu - ' + import.meta.env.VITE_PROJECT,
  })
  
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  
  const confirm = useConfirm()
  const item: any = ref({
    aktif: true
  })
  
  const d_status = [
    { value: 't', label: 'True' },
    { value: 'f', label: 'False' },
  ]
  const modalInput = ref(false)
  const modalDetail = ref(false)
  const d_Kelompok: any = ref([])
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
        items.jeniswaktu.match(new RegExp(filters.value, 'i'))
      )
    })
  })
  
  const route = useRoute()
  isLoading.value = false
  
  async function fetchData() {
    isLoading.value = true
    let JenisWaktu = ''
    let NamaDepartemen = ''
    let KelompokProduk = ''
    let JamAwal = ''
    let JamAkhir = ''
    let id = ''
    let StatusEnabled = ''
  
    if (item.jeniswaktu) JenisWaktu = '&jeniswaktu=' + item.jeniswaktu
    if (item.value.namadepartemen) NamaDepartemen = '&objectdepartemenfk=' + item.value.namadepartemen
    if (item.id) id = '&id=' + item.id
    if (item.jamawal) JamAwal = '&jamawal=' + item.jamawal
    if (item.jamakhir) JamAkhir = '&jamakhir=' + item.jamakhir
    if (item.kelompokproduk) KelompokProduk = '&kelompokproduk=' + item.kelompokproduk
    item.value.aktif ? StatusEnabled = '&statusenabled=' + item.value.aktif : StatusEnabled = '&statusenabled=false'
  
    const response = await useApi().get(
      '/sysadmin/master-jenis-waktu?statusenabled='+item.value.aktif
      +id + JenisWaktu + NamaDepartemen + StatusEnabled + KelompokProduk
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
    d_Kelompok.value = []
    await useApi().get(`/sysadmin/dropdown-departemen`).then((response: any) => {
        d_departemens.value = response.namadepartemen
        d_departemen.value = response.namadepartemen.map((e: any) => { return { label: e.namadepartemen, value: e.id } })
        d_Kelompok.value = response.kelompokproduk.map((e: any) => { return { label: e.kelompokproduk, value: e.id } })
      })
  }
  
  function add() {
    clear()
    modalInput.value = true
  }
  
  function edit(e: any) {
  
    item.value.id = e.id
    item.value.jeniswaktu = e.jeniswaktu
    item.value.objectdepartemenfk = { id: e.objectdepartemenfk, namadepartemen: e.namadepartemen }
    item.value.objectkelompokprodukfk = e.objectkelompokprodukfk
  
    item.value.statusenabled = e.statusenabled
    modalInput.value = true
  }
  function detail(e: any) {
    item.value.id = e.id
    item.value.jeniswaktu = e.jeniswaktu  
    item.value.objectdepartemenfk = e.objectdepartemenfk
    item.value.statusenabled = e.statusenabled
    modalDetail.value = true
  }
  
  async function save() {
    if (!item.value.jeniswaktu) {
      useToaster().error('Jenis Waktu harus di isi')
      return
    }
    var objSave =
    {
      'jeniswaktu': {
        'id': item.value.id ? item.value.id : '',
        'jeniswaktu': item.value.jeniswaktu,
        'objectdepartemenfk': item.value.objectdepartemenfk.id,
        'objectkelompokprodukfk' : item.value.objectkelompokprodukfk,
        'statusenabled': item.value.statusenabled ? item.value.statusenabled : null,

      }
    }
    isLoadingTT.value = true
    await useApi().post(
      `/sysadmin/save-jenis-waktu`, objSave).then((response: any) => {
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
      `sysadmin/delete-jenis-waktu`, { 'id': e.id }).then((response: any) => {
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
  fetchData()
  
  </script>
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  
  @import '/@src/scss/components/forms-outer';
  @import '/@src/scss/custom/config';
  @import '/@src/scss/module/sysadmin/master-data.scss';
  </style>