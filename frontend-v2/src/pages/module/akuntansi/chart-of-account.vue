<template>
  <section>
    <ConfirmDialog />
    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title-x">
          <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-3">
              <VField label="Kode Akun">
                <VControl icon="feather:search">
                  <input
                    v-model="item.noakunS"
                    v-on:keyup.enter="fetchData()"
                    type="text"
                    class="input is-rounded"
                    placeholder="Kode Akun"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Nama Akun">
                <VControl icon="feather:search">
                  <input
                    v-model="item.namaAkunS"
                    v-on:keyup.enter="fetchData()"
                    type="text"
                    class="input is-rounded"
                    placeholder="Nama Akun"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <VField label="Rows">
                <VControl icon="feather:airplay">
                  <input
                    v-model="item.rows"
                    v-on:keyup.enter="fetchData()"
                    type="text"
                    class="input is-rounded"
                    placeholder="Rows"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-1 mt-5">
              <VIconButton
                type="button"
                color="success"
                circle
                raised
                icon="fas fa-search"
                @click="fetchData()"
                :loading="isLoading"
              >
              </VIconButton>
            </div>

            <div class="column is-9">
              <VCard class="card-round-1">
                <DataTable
                  v-model:filters="filtersTrans"
                  :value="dataSource"
                  paginator
                  :rows="10"
                  dataKey="id"
                  filterDisplay="row"
                  :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                  size="small"
                  :globalFilterFields="['noaccount', 'namaaccount']"
                  showGridlines
                  stripedRows
                >
                  <template #header>
                    <div class="columns is-multiline">
                      <div class="column is-5">
                        <VButton
                          type="button"
                          icon="pi pi-file-excel"
                          class="mr-3"
                          color="info"
                          outlined
                          circle
                          raised
                          v-tooltip-prime="'Export'"
                          @click="exportExcel()"
                        >
                          Export Excel
                        </VButton>
                      </div>
                      <div class="column is-4 is-offset-3"></div>
                    </div>
                  </template>
                  <template #empty style="text-align: center"> No data found. </template>
                  <Column :exportable="false" header="#" style="width: 100px">
                    <template #body="slotProps">
                      <VIconButton
                        type="button"
                        icon="pi pi-trash"
                        class="mr-3"
                        color="danger"
                        circle
                        outlined
                        raised
                        v-tooltip-prime="'Hapus '"
                        @click="dialogConfirm(slotProps.data)"
                        :loading="slotProps.data.isLoading"
                      >
                      </VIconButton>
                      <VIconButton
                        type="button"
                        icon="pi pi-pencil"
                        class="mr-3"
                        color="warning"
                        circle
                        outlined
                        raised
                        v-tooltip-prime="'Edit '"
                        @click="edit(slotProps.data)"
                      >
                      </VIconButton>
                      <VIconButton
                        type="button"
                        icon="pi pi-history"
                        class="mr-3"
                        color="info"
                        circle
                        outlined
                        raised
                        :loading="slotProps.data.isLoading2"
                        v-tooltip-prime="'Riwayat Saldo '"
                        @click="riwayatSaldo(slotProps.data)"
                      >
                      </VIconButton>
                    </template>
                  </Column>
                  <Column
                    v-for="col in column"
                    :field="col.field"
                    :header="col.title"
                    :style="
                      'width:' +
                      col.width +
                      ';text-align:' +
                      (col.template != undefined ? 'right' : '')
                    "
                  >
                    <template #body="slotProps">
                      <span v-if="col.tag == undefined">{{
                        col.template != undefined
                          ? H.formatRupiah(slotProps.data[col.field], '')
                          : slotProps.data[col.field]
                      }}</span>
                      <span v-else>
                        <VTag
                          class="mr-1 mb-1"
                          :color="'success'"
                          :label="slotProps.data[col.field]"
                        />
                      </span>
                    </template>
                  </Column>
                </DataTable>
              </VCard>
            </div>
            <div class="column is-3">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <VLabel class="required-field">Kode Akun</VLabel>
                    <VControl icon="feather:edit">
                      <input
                        v-model="item.noakun"
                        type="text"
                        class="input is-rounded"
                        placeholder="Kode Akun"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12 mt-2-min">
                  <VField>
                    <VLabel class="required-field">Nama Akun</VLabel>
                    <VControl icon="feather:bookmark">
                      <input
                        v-model="item.namaAkun"
                        type="text"
                        class="input is-rounded"
                        placeholder="Nama Akun"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12 mt-2-min">
                  <VField
                    label="Struktur"
                    class="is-rounded-select is-autocomplete-select mt-0 pt-0"
                    v-slot="{ id }"
                  >
                    <VControl icon="fas fa-database" fullwidth class="prime-auto-select">
                      <Dropdown
                        v-model="item.struktur"
                        :options="d_Struktur"
                        :optionLabel="'strukturaccount'"
                        class="is-rounded"
                        placeholder="Struktur"
                        style="width: 100%"
                        :filter="true"
                        showClear
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12 mt-2-min">
                  <VField
                    label="Jenis"
                    class="is-rounded-select is-autocomplete-select mt-0 pt-0"
                    v-slot="{ id }"
                  >
                    <VControl icon="fas fa-database" fullwidth class="prime-auto-select">
                      <Dropdown
                        v-model="item.jenis"
                        :options="d_Jenis"
                        :optionLabel="'jenisaccount'"
                        class="is-rounded"
                        placeholder="Jenis"
                        style="width: 100%"
                        :filter="true"
                        showClear
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12 mt-2-min">
                  <VField
                    label="Kategory"
                    class="is-rounded-select is-autocomplete-select mt-0 pt-0"
                    v-slot="{ id }"
                  >
                    <VControl icon="fas fa-database" fullwidth class="prime-auto-select">
                      <Dropdown
                        v-model="item.kategory"
                        :options="d_Kategory"
                        :optionLabel="'kategoryaccount'"
                        class="is-rounded"
                        placeholder="Kategory"
                        style="width: 100%"
                        :filter="true"
                        showClear
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12 mt-2-min">
                  <VField
                    label="Status"
                    class="is-rounded-select is-autocomplete-select mt-0 pt-0"
                    v-slot="{ id }"
                  >
                    <VControl icon="fas fa-database" fullwidth class="prime-auto-select">
                      <Dropdown
                        v-model="item.status"
                        :options="d_Status"
                        :optionLabel="'statusaccount'"
                        class="is-rounded"
                        placeholder="Status"
                        style="width: 100%"
                        :filter="true"
                        showClear
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6 mt-2-min">
                  <VField>
                    <VLabel class="required-field">Saldo (+)</VLabel>
                    <VControl>
                      <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">
                        <div class="column is-12" v-if="d_Saldo.length == 0">
                          <VPlaceloadText :lines="1" />
                        </div>
                        <div
                          class="column is-6 p-0"
                          v-for="items in d_Saldo"
                          :key="items.id"
                        >
                          <VRadio
                            v-model="item.saldoAdd"
                            :value="items"
                            class="p-0 mb-3"
                            :label="items.saldo"
                            name="sall"
                            square
                            color="primary"
                          />
                        </div>
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6 mt-2-min">
                  <VField>
                    <VLabel class="required-field">Saldo (-)</VLabel>
                    <VControl>
                      <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">
                        <div class="column is-12" v-if="d_Saldo.length == 0">
                          <VPlaceloadText :lines="1" />
                        </div>
                        <div
                          class="column is-6 p-0"
                          v-for="items in d_Saldo"
                          :key="items.id"
                        >
                          <VRadio
                            v-model="item.saldoMin"
                            :value="items"
                            class="p-0 mb-3"
                            :label="items.saldo"
                            name="sal"
                            square
                            color="primary"
                          />
                        </div>
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12 mt-2-min switch-filter">
                  <span>Aktif</span>
                  <VControl>
                    <InputSwitch v-model="item.statusenabled" />
                  </VControl>
                </div>
                <div v-if="item.id" class="column is-12">
                  <VButton
                    @click="simpan()"
                    :loading="isLoadingBtn"
                    type="button"
                    icon="feather:edit"
                    class="is-fullwidth mr-3"
                    color="info"
                    raised
                  >
                    Update Data
                  </VButton>
                  <VButton
                    @click="Batal()"
                    type="button"
                    icon="feather:x-circle"
                    class="is-fullwidth is-outlined is-warning mt-3"
                    raised
                  >
                    Batal Edit
                  </VButton>
                </div>
                <div v-else class="column is-12">
                  <VButton
                    @click="simpan()"
                    :loading="isLoadingBtn"
                    type="button"
                    icon="feather:save"
                    class="is-fullwidth mr-3"
                    color="success"
                    raised
                  >
                    Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <Dialog
      v-model:visible="popupRiwayatSaldo"
      modal
      :header="'Riwayat Saldo'"
      :style="{ width: '60vw' }"
    >
      <div class="columns is-multiline">
        <div class="column is-12">
          <VCard class="card-round-4">
            <div class="columns is-multiline">
              <div class="column is-3">
                <VField label="Kode Akun">
                  <VControl icon="feather:search">
                    <input
                      v-model="item.noakunP"
                      type="text"
                      class="input is-rounded"
                      placeholder="Kode Akun"
                    />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <VField label="Nama Akun">
                  <VControl icon="feather:search">
                    <input
                      v-model="item.namaAkunP"
                      type="text"
                      class="input is-rounded"
                      placeholder="Nama Akun"
                    />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 mt-5">
                <VIconButton
                  type="button"
                  icon="pi pi-search"
                  class="mr-3"
                  color="success"
                  circle
                  raised
                  v-tooltip-prime="'Cari'"
                  :loading="isLoadingPop"
                  @click="CariCoaPOP()"
                >
                </VIconButton>
              </div>

              <div class="column is-12">
                <DataTable
                  :value="dataPopUp"
                  paginator
                  :rows="5"
                  dataKey="id"
                  :rowsPerPageOptions="[5, 10, 25, 100]"
                  :class="`p-datatable-small`"
                  :size="'small'"
                >
                  <template #empty style="text-align: center"> No data found. </template>
                  <Column :exportable="false" header="#" style="width: 40px">
                    <template #body="slotProps">
                      <VIconButton
                        type="button"
                        icon="pi pi-trash"
                        class="mr-3"
                        color="danger"
                        circle
                        outlined
                        raised
                        v-tooltip-prime="'Hapus'"
                        :loading="slotProps.data.isLoading2"
                        @click="hapusSaldo(slotProps.data)"
                      >
                      </VIconButton>
                      <VIconButton
                        type="button"
                        icon="pi pi-pencil"
                        class="mr-3"
                        color="info"
                        circle
                        outlined
                        raised
                        v-tooltip-prime="'Edit'"
                        @click="editSaldo(slotProps.data)"
                      >
                      </VIconButton>
                    </template>
                  </Column>
                  <Column
                    v-for="col in columnPopUp"
                    :field="col.field"
                    :header="col.title"
                    :style="'width:' + col.width"
                  >
                    <template #body="slotProps">
                      <span v-if="col.tag == undefined">{{
                        col.template != undefined
                          ? H.formatRupiah(slotProps.data[col.field], '')
                          : (col.field =='statusenabled' ? (slotProps.data[col.field] == 1?'Aktif':'Tidak Aktif'):slotProps.data[col.field])
                      }}</span>
                      <span v-else>
                        <VTag
                          class="mr-1 mb-1"
                          :color="'success'"
                          :label="slotProps.data[col.field]"
                        />
                      </span>
                    </template>
                  </Column>
                  <ColumnGroup type="footer">
                    <Row>
                      <Column
                        :footer="'Terdapat ' + dataPopUp.length + ' data.'"
                        :colspan="columnPopUp.length + 1"
                      />
                    </Row>
                  </ColumnGroup>
                </DataTable>
              </div>
              <div class="column is-3">
                <VField label="Tanggal">
                  <VControl class="prime-auto">
                    <Calendar
                      v-model="item.tglSaldo"
                      selectionMode="single"
                      :manualInput="false"
                      class="w-100"
                      :showIcon="true"
                      :showTime="false"
                      dateFormat="dd-mm-yy" 
                    />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField label="Saldo Debet">
                  <VControl icon="fa:calculator">
                    <input
                      v-model="item.saldoDebet"
                      v-mask-currency
                      type="text"
                      class="input is-rounded"
                      placeholder="Saldo Debet"
                    />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField label="Saldo Kredit">
                  <VControl icon="fa:calculator">
                    <input
                      v-model="item.saldoKredit"
                      v-mask-currency
                      type="text"
                      class="input is-rounded"
                      placeholder="Saldo Kredit"
                    />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField label="Status Aktif">
                <VControl >
                  <VSwitchBlock v-model="item.saldoStatus" color="danger"  />
                </VControl>
              
                  <!-- <VControl icon="feather:bookmark">
                    <input
                      v-model="item.saldoStatus"
                      type="text"
                      class="input is-rounded"
                      placeholder="Nama Akun"
                    />
                  </VControl> -->
                </VField>
              </div>
            
            </div>
            <div class="mb-2"></div>
          
          </VCard>
        </div>
      </div>
      <template #footer>
        <VButton
          icon="lnir lnir-arrow-left rem-100 "
          light
          dark-outlined
          @click="batalSaldo()"
        >
          Batal
        </VButton>
        <VButton
          type="button"
          rounded
          outlined
          color="primary"
          raised
          icon="feather:save"
          :loading="isLoadingSave"
          class="ml-2"
          @click="tambahSaldo()"
        >
          {{item.norecSaldo== undefined ?'Tambah':'Ubah'}}
        </VButton>
      </template>
    </Dialog>
  </section>
</template>
<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Sidebar from 'primevue/sidebar'
import Dropdown from 'primevue/dropdown'
import MultiSelect from 'primevue/multiselect'
import { useRoute, useRouter } from 'vue-router'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ProgressBar from 'primevue/progressbar'
import ColumnGroup from 'primevue/columngroup' // optional
import Row from 'primevue/row'
import InputText from 'primevue/inputtext'
import { FilterMatchMode } from 'primevue/api'
import { useApi } from '/@src/composable/useApi'
import Panel from 'primevue/panel'
import Dialog from 'primevue/dialog'
import Badge from 'primevue/badge'
import FileUpload from 'primevue/fileupload'
import Calendar from 'primevue/calendar'
import moment from 'moment'
import sleep from '/@src/utils/sleep'
import Divider from 'primevue/divider'
import InputSwitch from 'primevue/inputswitch'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as XLSX from 'xlsx'
import { useUserSession } from '/@src/stores/userSession'
import Button from 'primevue/button'
const title = 'Chart Of Account'
useHead({
  title: title + ' - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const confirm = useConfirm()
const isLoading: any = ref(false)
const route = useRoute()
const router = useRouter()
const dataSource: any = ref([])
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } })
const d_Struktur: any = ref([])
const d_Jenis: any = ref([])
const d_Kategory: any = ref([])
const d_Status: any = ref([])
const isLoadingBtn: any = ref(false)
const popupRiwayatSaldo: any = ref(false)
const dataPopUp: any = ref([])
const isLoadingSave: any = ref(false)
const isLoadingPop: any = ref(false)
const selectedSaldo: any = ref({})
const item: any = reactive({
  rows: 100,
  statusenabled: true,
  tglSaldo: new Date(),
})
const d_Saldo: any = ref([
  { id: 1, saldo: 'D' },
  { id: 2, saldo: 'K' },
])
const column: any = ref([
  {
    field: 'no',
    title: 'No',
    width: '30px',
  },
  {
    field: 'noaccount',
    title: 'No Akun',
    width: '100px',
  },
  {
    field: 'namaaccount',
    title: 'Nama Akun',
    width: '250px',
  },
  {
    field: 'saldonormaladd',
    title: 'Saldo (+)',
    width: '60px',
  },
  {
    field: 'saldonormalmin',
    title: 'Saldo (-)',
    width: '60px',
  },
  // {
  //     "field": "jenisaccount",
  //     "title": "Jenis",
  //     // "width" : "100px"
  // },
  {
    field: 'kategoryaccount',
    title: 'Kategory',
    width: '100px',
  },
  // {
  //     "field": "statusaccount",
  //     "title": "Status",
  //     "width" : "100px"
  // },
  {
    field: 'strukturaccount',
    title: 'Struktur',
    width: '100px',
  },
])

const fetchData = async () => {
  var noakun = 'noaccount=' + item.noakunS
  if (item.noakunS == undefined) {
    noakun = ''
  }
  var namaAkun = '&namaaccount=' + item.namaAkunS
  if (item.namaAkunS == undefined) {
    namaAkun = ''
  }
  var rows = '&rows=' + item.rows
  if (item.rows == undefined) {
    rows = ''
  }

  isLoading.value = true
  const dat = await useApi().get(
    'akuntansi/get-data-daftar-master-coa?' + noakun + namaAkun + rows
  )
  for (let x = 0; x < dat.length; x++) {
    const element = dat[x]
    element.no = x + 1
  }
  isLoading.value = false

  dataSource.value = dat
}
const fetchDropdown = async () => {
  const dat = await useApi().get('akuntansi/get-data-combo-master')
  d_Struktur.value = dat.strukturaccount
  d_Jenis.value = dat.jenisaccount
  d_Kategory.value = dat.kategoryaccount
  d_Status.value = dat.statusaccount
}

const exportExcel = () => {
  H.exportExcel(dataSource.value, 'coa')
}

const Batal = () => {
  delete item.id
  delete item.noakun
  delete item.namaAkun
  // delete item.statusenabled
  delete item.saldoAdd
  delete item.saldoMin
  delete item.jenis
  delete item.kategory
  delete item.status
  delete item.struktur
}
const edit = (e: any) => {
  item.id = e.id
  item.statusenabled = e.statusenabled
  item.noakun = e.noaccount
  item.namaAkun = e.namaaccount
  d_Saldo.value.forEach((element: any) => {
    if (element.saldo == e.saldonormaladd) {
      item.saldoAdd = element
    }
  })
  d_Saldo.value.forEach((element: any) => {
    if (element.saldo == e.saldonormalmin) {
      item.saldoMin = element
    }
  })
  d_Kategory.value.forEach((element: any) => {
    if (element.kategoryaccount == e.kategoryaccount) {
      item.kategory = element
    }
  })
  d_Jenis.value.forEach((element: any) => {
    if (element.jenisaccount == e.jenisaccount) {
      item.jenis = element
    }
  })
  d_Struktur.value.forEach((element: any) => {
    if (element.strukturaccount == e.strukturaccount) {
      item.struktur = element
    }
  })
  d_Status.value.forEach((element: any) => {
    if (element.statusaccount == e.statusaccount) {
      item.status = element
    }
  })
}
const simpan = async () => {
  var idCoa = ''
  if (item.id != undefined) idCoa = item.id

  if (item.noakun == undefined) {
    H.alert('error', 'Kode Akun harus di isi')
    return
  }
  if (item.namaAkun == undefined) {
    H.alert('error', 'Nama Akun harus di isi')
    return
  }

  if (item.statusenabled == undefined) {
    H.alert('error', 'Status enabled belum di pilih')
    return
  }

  if (item.saldoAdd == undefined) {
    H.alert('error', 'Saldo (+) belum di pilih')
    return
  }
  if (item.saldoMin == undefined) {
    H.alert('error', 'Saldo (-) belum di pilih')
    return
  }

  var objectjenisaccountfk = null
  if (item.jenis != undefined) objectjenisaccountfk = item.jenis.id

  var objectkategoryaccountfk = null
  if (item.kategory != undefined) objectkategoryaccountfk = item.kategory.id

  var objectstatusaccountfk = null
  if (item.status != undefined) objectstatusaccountfk = item.status.id

  var objectstrukturaccountfk = null
  var kodeexternal  = null
  if (item.struktur != undefined) {
    objectstrukturaccountfk = item.struktur.id
    kodeexternal = item.struktur.levelaccount
  }
  var objSave = {
    id: idCoa,
    statusenabled: item.statusenabled ? true : false,
    objectjenisaccountfk: objectjenisaccountfk,
    objectkategoryaccountfk: objectkategoryaccountfk,
    objectstatusaccountfk: objectstatusaccountfk,
    objectstrukturaccountfk: objectstrukturaccountfk,
    kdaccount: item.noakun,
    namaaccount: item.namaAkun,
    saldonormaladd: item.saldoAdd.saldo,
    saldonormalmin: item.saldoMin.saldo,
    kodeexternal: kodeexternal,
  }
  isLoadingBtn.value = true
  await useApi()
    .post(`/akuntansi/save-data-master-coa`, objSave)
    .then(
      (response: any) => {
        isLoadingBtn.value = false
        fetchData()
        Batal()
      },
      (error) => {
        isLoadingBtn.value = false
        // console.log(error)
      }
    )
}
const hapus = async (e: any) => {
  var objSave = {
    id: e.id,
    kdaccount: e.noakun,
    namaaccount: e.namaAkun,
  }
  e.isLoading = true
  await useApi()
    .post(`/akuntansi/save-hapus-data-master-coa`, objSave)
    .then(
      (response: any) => {
        e.isLoading = false
        fetchData()
      },
      (error) => {
        e.isLoading = false
        // console.log(error)
      }
    )
}
const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapus(e)
    },
    reject: () => {},
  })
}
const columnPopUp = [
  {
    field: 'no',
    title: 'No',
    width: '20px',
  },
  {
    field: 'tgl',
    title: 'Tanggal',
    width: '60px',
  },
  {
    field: 'hargasatuand',
    title: 'Saldo Debet',
    width: '130px',
    template:
      "<span class='style-right'>{{formatRupiah('#: hargasatuand #', '')}}</span>",
  },
  {
    field: 'hargasatuank',
    title: 'Saldo Kredit',
    width: '100px',
    template:
      "<span class='style-right'>{{formatRupiah('#: hargasatuank #', '')}}</span>",
  },
  {
    field: 'statusenabled',
    title: 'Status',
    width: '20px',
  },
]
const riwayatSaldo = async (dataSelected) => {
  clears()
  selectedSaldo.value = dataSelected
  dataSelected.isLoading2 = true
  item.objectaccountfk = dataSelected.id
  item.noakunP = dataSelected.noaccount
  item.namaAkunP = dataSelected.namaaccount
  popupRiwayatSaldo.value = true
  isLoadingPop.value = true
  const dat = await useApi().get(
    `/akuntansi/get-data-daftar-saldo-awal?coaid=${dataSelected.id}`
  )
  isLoadingPop.value = false
  for (var i = dat.length - 1; i >= 0; i--) {
    dat[i].no = i + 1
  }
  dataPopUp.value = dat

  dataSelected.isLoading2 = false
  item.tglSaldo = new Date()
  item.saldoDebet = 0
  item.saldoKredit = 0
  item.saldoStatus = 1
}
const tambahSaldo = async () => {
  var norec_tea = item.norecSaldo
  if (item.norecSaldo == undefined) {
    norec_tea = '-'
  }
  var tgltgl = H.formatDate(item.tglSaldo, 'YYYYMM')
  var objSave = {
    norec: norec_tea,
    objectaccountfk: item.objectaccountfk,
    hargasatuand: H.unFormatRupiah(item.saldoDebet),
    hargasatuank: H.unFormatRupiah(item.saldoKredit),
    statusenabled: item.saldoStatus,
    ym: tgltgl,
  }
  isLoadingSave.value = true
  try {
    const e = await useApi().post(`akuntansi/save-data-saldo-awal`, objSave)
    clears()
    riwayatSaldo(selectedSaldo.value)
  } catch (error) {}

  isLoadingSave.value = false
}
const CariCoaPOP = () =>{
  riwayatSaldo(selectedSaldo.value)
}
const batalSaldo = () => {
  clears()
}
const hapusSaldo = async (e: any) => {
  e.isLoading2 = true
  var objSave = {
    head: e.norec,
  }
  try {
    const ee = await useApi().post(`akuntansi/save-hapus-saldo-awal`, objSave)
    riwayatSaldo(selectedSaldo.value)
    clears()
  } catch (error) {
   
  }
 

  e.isLoading2 = false

}
const editSaldo = (dataSelectedPopUp: any) => {
  item.norecSaldo = dataSelectedPopUp.norec
  item.tglSaldo = new Date( dataSelectedPopUp.tgl)
  item.saldoDebet = H.formatRupiah(dataSelectedPopUp.hargasatuand,'')
  item.saldoKredit =  H.formatRupiah(dataSelectedPopUp.hargasatuank,'')
  item.saldoStatus = dataSelectedPopUp.statusenabled
  item.objectaccountfk  = selectedSaldo.value.id
}
const clears = () => {
  item.norecSaldo = undefined
  item.tglSaldo = new Date()
  item.saldoDebet = 0
  item.saldoKredit = 0
  item.saldoStatus = 1
}

fetchData()
fetchDropdown()
</script>
<style lang="scss"></style>
