<template>
  <section>
    <!-- <div class="column is-12">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-5">
          <label class="title-page">Target</label>
        </div> -->
    <div class="column is-12 p-0">
      <div class="columns is-multiline">
        <div class="column is-12">
          <VCard>
            <!-- @cell-edit-complete="onCellEditComplete" editMode="cell"  -->
            <DataTable
              :value="dataSource"
              :paginator="true"
              :rows="200"
              :rowsPerPageOptions="[5, 10, 25, 50, 100, 200, 300]"
              class="p-datatable-customers p-datatable-sm"
              filterDisplay="menu"
              scrollable
              editMode="row"
              dataKey="id"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack"
              breakpoint="960px"
              sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
              :loading="isLoading"
              tableClass="editable-cells-table"
              size="small"
              stripedRows
            >
              <template #header>
                <div class="columns is-multiline">
                  <div class="column is-5">
                    <VButton
                      type="button"
                      icon="pi pi-file-excel"
                      class="mr-3"
                      outlined
                      circle
                      raised
                      v-tooltip-prime="'Export'"
                      @click="exportExcel(dataSource, 'BOR')"
                    >
                      Export Excel
                    </VButton>
                    <VButton
                      type="button"
                      icon="pi pi-cloud-download"
                      class="mr-3"
                      outlined
                      circle
                      raised
                      v-tooltip-prime="'Collect Data dari semua Detail Transaksi ke Table'"
                      @click="closing()"
                      :loading="isLoadingClose"
                    >
                      Collect Data
                    </VButton>
                  </div>
                  <div class="column is-4"></div>
                  <div class="column is-2">
                    <VField label="Tahun">
                      <VControl class="prime-auto">
                        <Calendar
                          inputId="range"
                          v-model="item.qTahun"
                          :manualInput="false"
                          class="w-100"
                          :showIcon="true"
                          view="year"
                          dateFormat="yy"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column" style="margin-top: 22px">
                    <VIconButton
                      type="button"
                      icon="feather:search"
                      color="primary"
                      raised
                      :loading="isLoading"
                      @click="fetchData()"
                    >
                    </VIconButton>
                  </div>
                </div>
              </template>
              <template #empty style="text-align: center"> No data found. </template>
              <ColumnGroup type="header">
                <Row>
                  <Column
                    :rowspan="3"
                    header="No"
                    frozen
                    style="min-width: 50px"
                  ></Column>
                  <Column
                    header="Kinerja Operasional"
                    frozen
                    :rowspan="3"
                    style="min-width: 350px"
                  />
                  <Column header="" frozen :rowspan="3" />
                  <Column
                    :colspan="29"
                    :header="'Tahun ' + H.formatDate(item.qTahun, 'YYYY')"
                  ></Column>
                  <Column :rowspan="3" :header="'#'"></Column>
                </Row>
                <Row>
                  <Column header="Jan" :colspan="2" />
                  <Column header="Feb" :colspan="2" />
                  <Column header="Mar" :colspan="2" />
                  <Column header="Apr" :colspan="2" />
                  <Column header="Mei" :colspan="2" />
                  <Column header="Jun" :colspan="2" />
                  <Column header="Jul" :colspan="2" />
                  <Column header="Agu" :colspan="2" />
                  <Column header="Sep" :colspan="2" />
                  <Column header="Okt" :colspan="2" />
                  <Column header="Nov" :colspan="2" />
                  <Column header="Des" :colspan="2" />
                  <Column header="Year to Date (cumulative)" :colspan="2" />
                  <Column
                    :header="'Annual (Tahunan) ' + H.formatDate(item.qTahun, 'YYYY')"
                    :colspan="3"
                  />
                </Row>
                <Row>
                  <Column header="Real" :sortable="false" />
                  <!-- style="min-width: 100px;" -->
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="Real" :sortable="false" />
                  <Column header="Budget" :sortable="false" />
                  <Column header="% Pencapaian" :sortable="false" />
                  <Column header="Budget Awal" :sortable="false" />
                  <Column header="Budget Terakhir/Revisi" :sortable="false" />
                </Row>
              </ColumnGroup>
              <Column field="no" frozen />
              <Column field="namaaccount" frozen>
                <template #body="slotProps">
                  <span
                    :style="slotProps.data.style"
                    v-html="replaceDashes(slotProps.data.namaaccount)"
                  ></span>
                </template>
              </Column>
              <Column field="satuan" frozen />
              <Column field="jan" style="text-align: end">
                <template #body="slotProps">
                  {{ slotProps.data.jan ? H.formatRupiah(parseFloat(slotProps.data.jan).toFixed(2), '') : 0 }}
                  <!-- {{ H.formatRupiah(parseFloat(slotProps.data.jan).toFixed(2), '') }} -->
                </template>
              </Column>
              <Column field="jan_budget" style="text-align: end">
                <template #body="slotProps">
                  
                  {{ H.formatRupiah(slotProps.data.jan_budget, '') }}
                </template>
              </Column>
              <Column field="feb" style="text-align: end">
                <template #body="slotProps">
                  <!-- {{ H.formatRupiah(slotProps.data.feb, '') }} -->
                  {{ slotProps.data.feb ? H.formatRupiah(parseFloat(slotProps.data.feb).toFixed(2), '') : 0 }}
                </template>
              </Column>
              <Column field="feb_budget" style="text-align: end">
                <template #body="slotProps">
                  {{ slotProps.data.feb_budget }}
                </template>
              </Column>
              <Column field="mar" style="text-align: end">
                <template #body="slotProps">
                  <!-- {{ H.formatRupiah(slotProps.data.mar, '') }} -->
                  {{ slotProps.data.mar ? H.formatRupiah(parseFloat(slotProps.data.mar).toFixed(2), '') : 0 }}
                </template>
              </Column>
              <Column field="mar_budget" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.mar_budget, '') }}
                </template>
              </Column>
              <Column field="apr" style="text-align: end">
                <template #body="slotProps">
                  <!-- {{ slotProps.data.apr ? H.formatRupiah(parseFloat(slotProps.data.apr).toFixed(2), '') : 0 }} -->
                  {{ H.formatRupiah(slotProps.data.apr, '') }}
                </template>
              </Column>
              <Column field="apr_budget" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.apr_budget, '') }}
                </template>
              </Column>
              <Column field="mei" style="text-align: end">
                <template #body="slotProps">
                  <!-- {{ H.formatRupiah(slotProps.data.mei, '') }} -->
                  {{ slotProps.data.mei ? H.formatRupiah(parseFloat(slotProps.data.mei).toFixed(2), '') : 0 }}
                </template>
              </Column>
              <Column field="me_budget" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.mei_budget, '') }}
                </template>
              </Column>
              <Column field="jun" style="text-align: end">
                <template #body="slotProps">
                  {{ slotProps.data.jun ? H.formatRupiah(parseFloat(slotProps.data.jun).toFixed(2), '') : 0 }}
                  <!-- {{ H.formatRupiah(slotProps.data.jun, '') }} -->
                </template>
              </Column>
              <Column field="jun_budget" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.jun_budget, '') }}
                </template>
              </Column>
              <Column field="jul" style="text-align: end">
                <template #body="slotProps">
                  {{ slotProps.data.jul ? H.formatRupiah(parseFloat(slotProps.data.jul).toFixed(2), '') : 0 }}
                  <!-- {{ H.formatRupiah(slotProps.data.jul, '') }} -->
                </template>
              </Column>
              <Column field="jul_budget" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.jul_budget, '') }}
                </template>
              </Column>
              <Column field="agu" style="text-align: end">
                <template #body="slotProps">
                  {{ slotProps.data.agu ? H.formatRupiah(parseFloat(slotProps.data.agu).toFixed(2), '') : 0 }}
                  <!-- {{ H.formatRupiah(slotProps.data.agu, '') }} -->
                </template>
              </Column>
              <Column field="agu_budget" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.agu_budget, '') }}
                </template>
              </Column>
              <Column field="sep" style="text-align: end">
                <template #body="slotProps">
                  {{ slotProps.data.sep ? H.formatRupiah(parseFloat(slotProps.data.sep).toFixed(2), '') : 0 }}
                </template>
              </Column>
              <Column field="sep_budget" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.sep_budget, '') }}
                </template>
              </Column>
              <Column field="okt" style="text-align: end">
                <template #body="slotProps">
                  {{ slotProps.data.okt ? H.formatRupiah(parseFloat(slotProps.data.okt).toFixed(2), '') : 0 }}
                  <!-- {{ H.formatRupiah(slotProps.data.okt, '') }} -->
                </template>
              </Column>
              <Column field="okt_budget" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.okt_budget, '') }}
                </template>
              </Column>
              <Column field="nov" style="text-align: end">
                <template #body="slotProps">
                  {{ slotProps.data.nov ? H.formatRupiah(parseFloat(slotProps.data.nov).toFixed(2), '') : 0 }}
                  <!-- {{ H.formatRupiah(slotProps.data.nov, '') }} -->
                </template>
              </Column>
              <Column field="nov_budget" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.nov_budget, '') }}
                </template>
              </Column>
              <Column field="des" style="text-align: end">
                <template #body="slotProps">
                  {{ slotProps.data.des ? H.formatRupiah(parseFloat(slotProps.data.des).toFixed(2), '') : 0 }}
                  <!-- {{ H.formatRupiah(slotProps.data.des, '') }} -->
                </template>
              </Column>
              <Column field="des_budget" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.des_budget, '') }}
                </template>
              </Column>
              <Column field="year_real_cum" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.year_real_cum, '') }}
                </template>
              </Column>
              <Column field="year_budget_cum" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.year_budget_cum, '') }}
                </template>
              </Column>
              <Column field="persen_capaian" />
              <Column field="annual_budgetawal" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.annual_budgetawal, '') }}
                </template>
              </Column>
              <Column field="annual_budgetakhir" style="text-align: end">
                <template #body="slotProps">
                  {{ H.formatRupiah(slotProps.data.annual_budgetakhir, '') }}
                </template>
              </Column>
              <Column>
                <template #body="slotProps">
                  <Button
                    icon="pi pi-pencil"
                    severity="secondary"
                    text
                    rounded
                    @click="editRows(slotProps.data)"
                  />
                </template>
              </Column>
            </DataTable>
          </VCard>
        </div>
      </div>
    </div>
    <!-- </VCard>
    </div> -->
    <Dialog
      v-model:visible="modalInput"
      modal
      :header="'Update Budget'"
      :style="{ width: '50vw' }"
    >
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-6">
            <VField label="Kinerja Operasional">
              <VControl icon="feather:edit-2">
                <input
                  v-model="input.namaaccount"
                  type="text"
                  class="input is-rounded"
                  placeholder="Kinerja Operasional"
                  disabled
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField label="Satuan">
              <VControl icon="feather:edit-2">
                <input
                  v-model="input.satuan"
                  type="text"
                  class="input is-rounded"
                  placeholder="Satuan"
                  disabled
                />
              </VControl>
            </VField>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12">
              <VCard>
                <div class="column c-title pt-2 mb-5">
                  <label class="title-page">Budget</label>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VField label="Januari">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.jan_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="Januari"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Februari">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.feb_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="Februari"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Maret">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.mar_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="Maret"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="April">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.apr_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="April"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Mei">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.mei_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="Mei"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Juni">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.jun_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="Juni"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Juli">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.jul_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="Juli"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Agustus">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.agu_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="Agustus"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="September">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.sep_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="September"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Oktober">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.okt_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="Oktober"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="November">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.nov_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="November"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Desember">
                      <VControl icon="feather:calendar">
                        <VInput
                          v-mask-currency
                          v-model="input.des_budget"
                          type="text"
                          class="input is-rounded"
                          placeholder="Desember"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </VCard>
            </div>
          </div>
        </div>
      </VCard>
      <template #footer>
        <VButton
          icon="lnir lnir-arrow-left rem-100"
          light
          dark-outlined
          @click="modalInput = false"
        >
          Tutup
        </VButton>
        <VButton
          type="button"
          rounded
          outlined
          color="primary"
          raised
          icon="feather:save"
          :loading="isLoading"
          @click="simpan()"
          >Update
        </VButton>
      </template>
    </Dialog>
    <Dialog
      v-model:visible="modaltgl"
      modal
      :header="'Pilih Bulan'"
      :style="{ width: '20vw' }"
    >
      <VCard>
        <VField label="Bulan">
          <VControl class="prime-auto">
            <Calendar
              inputId="range"
              selectionMode="single"
              v-model="item.bulan"
              :manualInput="false"
              class="w-100"
              :showIcon="true"
              view="month"
              dateFormat="MM-yy"

            />
          </VControl>
        </VField>
      </VCard>
      <ProgressBar mode="indeterminate" style="height: 6px" v-if="isLoadingClose"></ProgressBar>
      <template #footer>
        <VButton
          icon="lnir lnir-arrow-left rem-100"
          light
          dark-outlined
          @click="modaltgl = false"
        >
          Tutup
        </VButton>
        <VButton
          type="button"
          rounded
          outlined
          color="primary"
          raised
          icon="feather:arrow-right"
          :loading="isLoadingClose"
          @click="closingNext()"
          >Lanjut
        </VButton>
      </template>
    </Dialog>
  </section>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ColumnGroup from 'primevue/columngroup'
import Row from 'primevue/row'
import AutoComplete from 'primevue/autocomplete'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar'
import Dropdown from 'primevue/dropdown'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import * as XLSX from 'xlsx'
import ProgressBar from 'primevue/progressbar';

useHead({
  title: 'MKKO - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  qTahun: new Date(),
  bulan: new Date(),
  qBulan: [
    new Date(),
    new Date()
  ],
  query: ``,


})
const dataSource: any = ref([])
const isLoading = ref(false)
const isLoadingClose = ref(false)
const modalInput: any = ref(false)
const editingRows = ref([])
const modaltgl = ref(false)
const input: any = ref({})
const fetchData = async () => {
  let qTahun = H.formatDate(item.value.qTahun, 'YYYY')

  isLoading.value = true
  let data = []
  try {
    data = await useApi().get(`mkko/get-target-mkko?tahun=${qTahun}`)
  } catch (e) {}
  isLoading.value = false
  for (let x = 0; x < data.length; x++) {
    const element = data[x]
    element.no = x + 1
  }
  dataSource.value = data
}
const editRows = (e: any) => {
  input.value = e
  modalInput.value = true
}
const replaceDashes = (text: any) => {
  return text.replace(/---/g, '&nbsp;&nbsp;&nbsp;')
}
const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}
const isPositiveInteger = (val: any) => {
  let str = String(val)

  str = str.trim()

  if (!str) {
    return false
  }

  str = str.replace(/^0+/, '') || '0'
  var n = Math.floor(Number(str))

  return n !== Infinity && String(n) === str && n >= 0
}
const onCellEditComplete = (event: any) => {
  let { data, newValue, field } = event

  switch (field) {
    case 'quantity':
    case 'price':
      if (isPositiveInteger(newValue)) data[field] = newValue
      else event.preventDefault()
      break
    default:
      if (newValue) data[field] = newValue
      else event.preventDefault()
      break
  }
}
const simpan = async () => {
  isLoading.value = true
  await useApi()
    .post(`/mkko/save-target-mkko`, {
      norec: input.value.norec ? input.value.norec : '',
      data: input.value,
      objectaccountfk: input.value.id,
      tahun: H.formatDate(item.value.qTahun, 'YYYY'),
    })
    .then((response: any) => {
      isLoading.value = false
      modalInput.value = false
      fetchData()
    })
    .catch((e) => {
      isLoading.value = false
    })
}
const onRowEditSave = (event: any) => {
  let { newData, index } = event

  dataSource.value[index] = newData
}
const closing = async () => {
  modaltgl.value = true
}
const closingNext = async () => {
  isLoadingClose.value = true
  let dari = H.formatDate(item.value.bulan, "YYYY-MM-01 00:00:00")
  let last = new Date( item.value.bulan.getFullYear(),  item.value.bulan.getMonth() + 1, 0).getDate();
  let sampai = H.formatDate(item.value.bulan, "YYYY-MM-" + last + " 23:59:59" )
  let bulan = H.formatDate(item.value.bulan, "YYYY-MM")
  let dataKunjungan = await useApi().post(`mkko/jumlah-pasien-by-kelompok-query?dari=${dari}&sampai=${sampai}&closing=true&bulan=${bulan}`,{})
  let dataPendapatan = await useApi().get(`mkko/lap-jml-pendapatan?dari=${dari}&sampai=${sampai}&ebitda=true&closing=true&bulan=${bulan}`)
  let dataReadmisi = await useApi().get(`mkko/persentase-inpatien-visit?dari=${dari}&sampai=${sampai}&closing=true&bulan=${bulan}`)
  let dataOperasi = await useApi().get(`mkko/lap-jumlah-tindakan-operasi?dari=${dari}&sampai=${sampai}&closing=true&bulan=${bulan}`)
  let dataPegawai = await useApi().get(`mkko/lap-jml-pegawai?dari=${dari}&sampai=${sampai}&closing=true&bulan=${bulan}`)
  let dataBOR = await useApi().get(`mkko/get-borlostoi?dari=${dari}&sampai=${sampai}&closing=true&bulan=${bulan}`)
  let dataCashFlow = await useApi().get(`mkko/lap-cashflow?dari=${dari}&sampai=${sampai}&closing=true&bulan=${bulan}`)
  let dataLapBalance = await useApi().get(`mkko/lap-balance-sheet?dari=${dari}&sampai=${sampai}&closing=true&bulan=${bulan}`)
  // let dataBebanUsaha = await useApi().get(`mkko/lap-beban-usaha?dari=${dari}&sampai=${sampai}&closing=true&bulan=${bulan}`)

  // let dataBOR = await useApi().get(`mkko/get-borlostoi?dari=${dari}&sampai=${sampai}`)

  // /jumlah-pasien-by-kelompok-query
  isLoadingClose.value = false
  modaltgl.value = false
  fetchData()

  // await useApi()
  //   .post(`/mkko/save-target-mkko`, {
  //     norec: input.value.norec ? input.value.norec : '',
  //     data: input.value,
  //     objectaccountfk: input.value.id,
  //     tahun: H.formatDate(item.value.qTahun, 'YYYY'),
  //   })
  //   .then((response: any) => {
  //     isLoadingClose.value = false
  //     fetchData()
  //   })
  //   .catch((e) => {
  //     isLoadingClose.value = false
  //   })
}
fetchData()
</script>
<style lang="scss">
// @import '/@src/scss/module/akuntansi/mkko';
.p-tabview .p-tabview-panels {
  background: var(--background-grey);
  padding: 1rem;
  border: 0 none;
  color: #495057;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
  margin-top: 14px;
}

.p-datatable .p-datatable-header {
  background: #ffffff;
  color: #334155;
  border: 1px solid #e2e8f0;
  border-width: 0 0 1px 0;
  padding: 0.75rem 1rem;
  font-weight: 600;
}

.p-datatable .p-datatable-thead > tr > th {
  text-align: left;
  padding: 0.75rem 1rem;
  border: 1px solid #e2e8f0;
  border-width: 0 0 1px 0;
  font-weight: 600;
  color: #334155;
  background: #ffffff;
  transition: background-color 0.2s, color 0.2s, border-color 0.2s, box-shadow 0.2s,
    outline-color 0.2s;
}
</style>
