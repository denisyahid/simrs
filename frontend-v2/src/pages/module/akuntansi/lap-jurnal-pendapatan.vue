<template>
  <section>
    <ConfirmDialog />
    <div class="columns is-multiline">
      <div class="column is-12">
        <VCard style="padding-bottom: 0px">
          <div class="column c-title-x">
            <h3 class="title is-5 mb-2 mr-1">Jurnal Pendapatan</h3>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-6"></div>
              <div class="column is-3">
                <VField
                  label="Jenis "
                  class="is-rounded-selectZ is-autocomplete-select"
                  v-slot="{ id }"
                >
                  <VControl icon="fa:bookmark" class="prime-auto-select">
                    <Dropdown
                      v-model="item.jenis"
                      :options="d_Jenis"
                      :optionLabel="'name'"
                      class="is-rounded"
                      placeholder="Pilih data"
                      style="width: 100%"
                      showClear
                      :filter="true"
                    />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 is-pulled-right">
                <VField label="Periode">
                  <VControl class="prime-auto">
                    <Calendar
                      inputId="range"
                      v-model="item.qFilterTgl"
                      selectionMode="range"
                      :manualInput="false"
                      class="w-100 mb-4"
                      :showIcon="true"
                      :showTime="false"
                      date-format="dd-mm-yy"
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
              <div class="column is-12 mt-5-min">
                <VCard class="card-round-1">
                  <DataTable
                    v-model:filters="filtersTrans"
                    :value="dataSource"
                    paginator
                    :rows="50"
                    dataKey="id"
                    filterDisplay="row"
                    :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                    :globalFilterFields="['namaaccount', 'noaccount']"
                    :class="`p-datatable-small`"
                    :loading="isLoading"
                  >
                    <template #header>
                      <div class="columns is-multiline">
                        <div class="column is-5">
                          <VButton
                            type="button"
                            icon="pi pi-file-excel"
                            class="mr-3"
                            color="solid"
                            outlined
                            circle
                            raised
                            v-tooltip-prime="'Export'"
                            @click="exportExcel(dataSource, 'pendapatan')"
                          >
                            Export Excel
                          </VButton>
                          <!-- <VButton type="button" icon="pi pi-file-pdf" class="mr-3" color="solid"
                                                        outlined circle raised v-tooltip-prime="'Export'"
                                                        @click="exportPDF()">
                                                        Export PDF
                                                    </VButton> -->
                        </div>
                        <div class="column is-3 is-offset-4">
                          <VField>
                            <VControl icon="feather:search">
                              <input
                                v-model="filtersTrans['global'].value"
                                v-on:keyup.enter="fetchData()"
                                type="text"
                                class="input is-rounded"
                                placeholder="Search"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </template>
                    <template #empty style="text-align: center">
                      No data found.
                    </template>
                    <Column :exportable="false" header="#" style="width: 50px">
                      <template #body="slotProps">
                        <VIconButton
                          type="button"
                          icon="pi pi-search"
                          class="mr-3"
                          color="info"
                          circle
                          outlined
                          raised
                          v-tooltip-prime="'Detail Jurnal'"
                          @click="detailJurnal(slotProps.data)"
                          :loading="slotProps.data.isLoading"
                        >
                        </VIconButton>
                      </template>
                    </Column>
                    <Column
                      v-for="col in columnGrid"
                      :field="col.field"
                      :header="col.title"
                      :style="'width:' + col.width"
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
                    <ColumnGroup type="footer">
                      <Row>
                        <Column :footer="'TOTAL'" :colspan="5" />
                        <Column :footer="H.formatRupiah(item.ttlDebet, 'Debit : Rp. ')" />
                        <Column
                          :footer="H.formatRupiah(item.ttlKredit, 'Kredit : Rp. ')"
                        />
                        <Column :footer="''" :colspan="2" />
                      </Row>
                    </ColumnGroup>
                  </DataTable>
                </VCard>
              </div>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
        <VCard style="padding-bottom: 0px">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12 mt-5-min">
                <VCard class="card-round-2">
                  <h3 class="title is-5 mb-2 mr-1">Pendapatan Belum Verifikasi</h3>

                  <DataTable
                    v-model:filters="filtersTrans"
                    :value="dataSource2"
                    paginator
                    :rows="50"
                    dataKey="id"
                    filterDisplay="row"
                    :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                    :globalFilterFields="['namaaccount', 'noaccount']"
                    :class="`p-datatable-small`"
                    :loading="isLoading"
                  >
                    <template #header>
                      <div class="columns is-multiline">
                        <div class="column is-5">
                          <VButton
                            type="button"
                            icon="pi pi-file-excel"
                            class="mr-3"
                            color="solid"
                            outlined
                            circle
                            raised
                            v-tooltip-prime="'Export'"
                            @click="exportExcel(dataSource2, 'pendapatanbelumverif')"
                          >
                            Export Excel
                          </VButton>
                        </div>
                        <div class="column is-3 is-offset-4">
                          <VField>
                            <VControl icon="feather:search">
                              <input
                                v-model="filtersTrans['global'].value"
                                v-on:keyup.enter="fetchData()"
                                type="text"
                                class="input is-rounded"
                                placeholder="Search"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </template>
                    <template #empty style="text-align: center">
                      No data found.
                    </template>
                    <Column :exportable="false" header="#" style="width: 50px">
                      <template #body="slotProps">
                        <VIconButton
                          type="button"
                          icon="pi pi-search"
                          class="mr-3"
                          color="info"
                          circle
                          outlined
                          raised
                          v-tooltip-prime="'Detail Jurnal'"
                          @click="detailJurnalbelumverif(slotProps.data)"
                          :loading="slotProps.data.isLoading"
                        >
                        </VIconButton>
                      </template>
                    </Column>
                    <Column
                      v-for="col in columnGridBelum"
                      :field="col.field"
                      :header="col.title"
                      :style="'width:' + col.width"
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
                    <ColumnGroup type="footer">
                      <Row>
                        <Column :footer="'TOTAL'" :colspan="5" />
                        <Column
                          :footer="H.formatRupiah(item.ttlDebetBv, 'Debit : Rp. ')"
                        />
                        <Column
                          :footer="H.formatRupiah(item.ttlKreditBv, 'Kredit : Rp. ')"
                        />
                        <Column :footer="''" :colspan="2" />
                      </Row>
                    </ColumnGroup>
                  </DataTable>
                </VCard>
              </div>
            </div>
          </div>
        </VCard>
      </div>
    </div>
    <Dialog
      v-model:visible="modalDetail"
      modal
      :header="'Detail'"
      :style="{ width: '70vw' }"
    >
      <DataTable
        v-model:filters="filtersDetail"
        :value="dataPopUp"
        paginator
        :rows="5"
        dataKey="id"
        filterDisplay="row"
        :rowsPerPageOptions="[5, 10, 25, 100]"
        :globalFilterFields="['namapasien']"
        :class="`p-datatable-small`"
        :size="'small'"
        :loading="isloadPop"
      >
        <template #header>
          <div class="columns is-multiline">
            <div class="column is-3 is-offset-9">
              <VField>
                <VControl icon="feather:search">
                  <input
                    v-model="filtersDetail['global'].value"
                    type="text"
                    class="input is-rounded"
                    placeholder="Search"
                  />
                </VControl>
              </VField>
            </div>
          </div>
        </template>
        <template #empty style="text-align: center"> No data found. </template>

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
        <ColumnGroup type="footer">
          <Row>
            <Column :footer="'Terdapat ' + dataPopUp.length + ' data.'" :colspan="10" />
            <Column :footer="H.formatRupiah(item.totalPop, 'Rp. ')" />
          </Row>
        </ColumnGroup>
      </DataTable>
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
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as XLSX from 'xlsx'
import { useUserSession } from '/@src/stores/userSession'
import Button from 'primevue/button'
const title = 'Laporan Akuntansi'
useHead({
  title: title + ' - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const jmlFilter: any = ref(0)
const isLoadingUpload: any = ref(false)
const totalSize = ref(0)
const totalSizePercent = ref(0)
const confirm = useConfirm()
const modalFilter: any = ref(false)
const isLoading: any = ref(false)
const isLoading2: any = ref(false)
const isLoading3: any = ref(false)
const isLoading4: any = ref(false)
const route = useRoute()
const router = useRouter()
const dataSource: any = ref([])
const dataSource2: any = ref([])
const dataSource3: any = ref([])
const dataSource4: any = ref([])
const isClosing2: any = ref(false)
const isClosing: any = ref(false)
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } })
const filtersDetail = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const dataDetailJurnal: any = ref([])
const dataPopUp: any = ref([])
const modalDetail: any = ref(false)
const data2: any = ref([])
const isLoadingpop: any = ref(false)
const modalJurnalPost: any = ref(false)
const isloadPop: any = ref(false)
const valueProgress: any = ref(0)
const dataExcel: any = ref({})
const modalJurnalEntry: any = ref(false)
const item: any = reactive({
  qFilterTgl: [new Date(), new Date()],
  ttlDebet: 0,
  ttlKredit: 0,
  bulan: new Date(),
})
const d_Jenis = [
  { id: 'RI', name: 'Rawat Inap' },
  { id: 'RJ', name: 'Rawat Jalan' },
]
const currentPage: any = ref({
  limit: 20,
})
const tittlenow: any = ref('')
const tittlebefore: any = ref('')
const getLastDayOfMonth = (year, month) => {
  // Create a Date object set to the next month's first day
  let firstDayOfNextMonth = new Date(year, month, 1)

  // Subtract one day to get the last day of the current month
  let lastDayOfMonth = new Date(firstDayOfNextMonth - 1)

  return lastDayOfMonth.getDate()
}
const columnGrid = ref([
  {
    field: 'no',
    title: 'No',
    width: '20px',
  },
  {
    field: 'noaccount',
    title: 'Kode',
    width: '60px',
  },
  {
    field: 'namaaccount',
    title: 'Perkiraan',
    width: '130px',
  },
  {
    field: 'keteranganlainnya',
    title: 'Keterangan',
    width: '100px',
  },
  {
    field: 'hargasatuand',
    title: 'Debit',
    width: '70px',
    // "aggregates": ["sum"],
    // "footerTemplate": "#=sum#",
    // "groupFooterTemplate": "#=sum#",
    template:
      "<span class='style-right'>{{formatRupiah('#: hargasatuand #', '')}}</span>",
  },
  {
    field: 'hargasatuank',
    title: 'Kredit',
    width: '70px',
    // "aggregates": ["sum"],
    // "footerTemplate": "#=sum#",
    // "groupFooterTemplate": "#=sum#",
    template:
      "<span class='style-right'>{{formatRupiah('#: hargasatuank #', '')}}</span>",
    // attributes: { style: "text-align:right;" },
    // aggregates: ["sum"],
    // footerTemplate: "<span class='style-right'>{{formatRupiah('#: data.hargasatuank.sum #', '')}}</span>",
    // template: "<span class='style-right'>{{formatRupiah('#: hargasatuank #', '')}}</span>",
    // footerAttributes: { style: "text-align: right;" }
  },
  {
    field: 'funct',
    title: 'Funct',
    width: '80px',
  },
  {
    field: 'namafunct',
    title: 'Nama Funct',
    width: '100px',
  },
])
const columnGridBelum = ref([
  {
    field: 'no',
    title: 'No',
    width: '20px',
  },
  {
    field: 'noaccount',
    title: 'Kode',
    width: '60px',
  },
  {
    field: 'namaaccount',
    title: 'Perkiraan',
    width: '130px',
  },
  {
    field: 'keteranganlainnya',
    title: 'Keterangan',
    width: '100px',
  },
  {
    field: 'hargasatuand',
    title: 'Debit',
    width: '70px',
    // "aggregates": ["sum"],
    // "footerTemplate": "#=sum#",
    // "groupFooterTemplate": "#=sum#",
    template:
      "<span class='style-right'>{{formatRupiah('#: hargasatuand #', '')}}</span>",
  },
  {
    field: 'hargasatuank',
    title: 'Kredit',
    width: '70px',
    // "aggregates": ["sum"],
    // "footerTemplate": "#=sum#",
    // "groupFooterTemplate": "#=sum#",
    template:
      "<span class='style-right'>{{formatRupiah('#: hargasatuank #', '')}}</span>",
    // attributes: { style: "text-align:right;" },
    // aggregates: ["sum"],
    // footerTemplate: "<span class='style-right'>{{formatRupiah('#: data.hargasatuank.sum #', '')}}</span>",
    // template: "<span class='style-right'>{{formatRupiah('#: hargasatuank #', '')}}</span>",
    // footerAttributes: { style: "text-align: right;" }
  },
  {
    field: 'funct',
    title: 'Funct',
    width: '80px',
  },
  {
    field: 'namafunct',
    title: 'Nama Funct',
    width: '100px',
  },
])
const fetchData = async () => {
  let dari = '',
    sampai = '',
    Jra = ''

  if (item.qFilterTgl[0]) {
    dari = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 00:00:00')
  }
  if (item.qFilterTgl[1]) {
    sampai = H.formatDate(item.qFilterTgl[1], 'YYYY-MM-DD 23:59:59')
  } else {
    sampai = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 23:59:59')
  }

  if (item.jenis != undefined) {
    Jra = item.jenis.id
  }
  isLoading.value = true
  const response = await useApi().get(
    '/akuntansi/get-data-jurnal-pendapatan?tglAwal=' +
      dari +
      '&tglAkhir=' +
      sampai +
      '&jenis=' +
      Jra
  )

  const response2 = await useApi().get(
    '/akuntansi/get-data-jurnal-pendapatan-belum-verif?tglAwal=' +
      dari +
      '&tglAkhir=' +
      sampai +
      '&jenis=' +
      Jra
  )

  var debetX: any = 0
  var kreditX: any = 0
  for (var i = response.length - 1; i >= 0; i--) {
    response[i].no = i + 1
    debetX = parseFloat(debetX) + parseFloat(response[i].hargasatuand)
    kreditX = parseFloat(kreditX) + parseFloat(response[i].hargasatuank)
  }
  item.ttlDebet = debetX
  item.ttlKredit = kreditX

  debetX = 0
  kreditX = 0
  for (var i = response2.length - 1; i >= 0; i--) {
    response2[i].no = i + 1
    debetX = parseFloat(debetX) + parseFloat(response2[i].hargasatuand)
    kreditX = parseFloat(kreditX) + parseFloat(response2[i].hargasatuank)
  }
  item.ttlDebetBv = debetX
  item.ttlKreditBv = kreditX

  isLoading.value = false
  dataSource.value = response
  dataSource2.value = response2
  let c_set = {
    0: dari,
    1: sampai,
  }
  H.cacheHelper().set('c_jurnal_pendapatan', c_set)
}

const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}

const columnPopUp = [
  {
    field: 'tglbuktitransaksi',
    title: 'Tgl Transaksi',
    width: '90px',
  },
  {
    field: 'noregistrasi',
    title: 'No Registrasi',
    width: '90px',
  },
  {
    field: 'namapasien',
    title: 'Nama Pasien',
    width: '120px',
  },
  {
    field: 'nosep',
    title: 'No SEP',
    width: '120px',
  },
  {
    field: 'namarekanan',
    title: 'Rekanan',
    width: '60px',
  },
  {
    field: 'jenis',
    title: 'OPD/IPD',
    width: '80px',
  },
  {
    field: 'namadokter',
    title: 'Nama Dokter Pemeriksa',
    width: '80px',
  },
  {
    field: 'namatindakan',
    title: 'Nama Tindakan',
    width: '80px',
  },
  {
    field: 'funct',
    title: 'Kode Unit',
    width: '100px',
  },
  {
    field: 'namafunct',
    title: 'Ruangan/Unit',
    width: '100px',
  },
  {
    field: 'hargasatuand',
    title: 'Total',
    width: '70px',
    template:
      "<span class='style-right'>{{formatRupiah('#: hargasatuand #', '')}}</span>",
    aggregates: ['sum'],
    attributes: { style: 'text-align:right;' },
    footerTemplate:
      "<span class='style-right'>Rp. {{formatRupiah('#: data.hargasatuand.sum  #', '')}}</span>",
  },
]

const detailJurnal = async (e: any) => {
  let dari = '',
    sampai = '',
    Jra = ''

  if (item.qFilterTgl[0]) {
    dari = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 00:00:00')
  }
  if (item.qFilterTgl[1]) {
    sampai = H.formatDate(item.qFilterTgl[1], 'YYYY-MM-DD 23:59:59')
  } else {
    sampai = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 23:59:59')
  }
  if (item.jenis != undefined) {
    Jra = item.jenis.id
  }
  modalDetail.value = true
  isloadPop.value = true
  const response = await useApi().get(
    '/akuntansi/get-data-detail-pendapatan?tglAwal=' +
      dari +
      '&tglAkhir=' +
      sampai +
      '&jenis=' +
      Jra +
      '&noacc=' +
      e.noaccount +
      '&idru=' +
      e.funct +
      '&ru=' +
      e.namafunct +
      '&nilaidebet=' +
      e.hargasatuand
  )
  isloadPop.value = false
  item.totalPop = 0
  for (let x = 0; x < response.length; x++) {
    const element = response[x]
    item.totalPop = item.totalPop + parseFloat(element.hargasatuand)
  }
  dataPopUp.value = response
}
const detailJurnalbelumverif = async (e: any) => {
  let dari = '',
    sampai = '',
    Jra = ''

  if (item.qFilterTgl[0]) {
    dari = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 00:00:00')
  }
  if (item.qFilterTgl[1]) {
    sampai = H.formatDate(item.qFilterTgl[1], 'YYYY-MM-DD 23:59:59')
  } else {
    sampai = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 23:59:59')
  }
  if (item.jenis != undefined) {
    Jra = item.jenis.id
  }
  modalDetail.value = true
  isloadPop.value = true
  const response = await useApi().get(
    '/akuntansi/get-data-detail-pendapatan-belumverif?tglAwal=' +
      dari +
      '&tglAkhir=' +
      sampai +
      '&jenis=' +
      Jra +
      '&noacc=' +
      e.noaccount +
      '&idru=' +
      e.funct +
      '&ru=' +
      e.namafunct +
      '&nilaidebet=' +
      e.hargasatuand
  )
  isloadPop.value = false
  for (let x = 0; x < response.length; x++) {
    const element = response[x]
    item.totalPop = item.totalPop + parseFloat(element.hargasatuand)
  }
  dataPopUp.value = response
}

let c = H.cacheHelper().get('c_jurnal_pendapatan')
if (c != undefined) {
  item.qFilterTgl[0] = new Date(c[0])
  item.qFilterTgl[1] = new Date(c[1])
}

fetchData()
</script>
<style lang="scss"></style>
