<template>
  <VCard>
    <div class="column c-title-x">
      <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
    </div>
    <div class="column is-12">
      <div class="columns is-multiline">
        <div class="column is-3">
          <VField label="Tanggal Awal Piutang">
            <VControl class="prime-auto">
              <Calendar
                inputId="range"
                v-model="item.qBulan"
                selectionMode="range"
                :manualInput="false"
                class="w-100 mb-4 is-rounded"
                :showIcon="true"
                date-format="yy-mm-dd"
              />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField
            label="Nama Rekanan"
            class="is-rounded-select is-autocomplete-select"
            v-slot="{ id }"
          >
            <VControl icon="fa:bookmark" class="prime-auto-select">
              <AutoComplete
                v-model="item.namarekanan"
                :suggestions="d_Rekanan"
                @complete="fetchRekanan($event)"
                :optionLabel="'label'"
                :dropdown="true"
                :minLength="3"
                :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'"
                :field="'label'"
                placeholder="ketik nama rekanan .."
                showClear
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
      </div>
    </div>
    <div class="column is-12">
        <div class="columns is-multiline">
            <div class="column is-3">
              <VCardCustom style="background-color: aliceblue;">
                <div :class="'label-cing'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1"><b> Days Inventory Turnover :  {{ parseFloat(item.totalTurnover).toFixed(1) }} Days</b></span>
                </div>

              </VCardCustom>
            </div>
            <div class="column is-3">
              <VCardCustom style="background-color: aliceblue;">
                <div :class="'label-cing'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1"><b> Total Piutang Inventory :  {{ H.formatRupiah(parseFloat(item.totalTagihanALL).toFixed(2), 'Rp.') }}
                    
                    </b></span>
                </div>
              </VCardCustom>
            </div>
            <div class="column is-3">
              <VCardCustom style="background-color: aliceblue;">
                <div :class="'label-cing'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1"><b> Total Sudah Dibayar :  {{ H.formatRupiah(parseFloat(item.totalBayarALL).toFixed(2), 'Rp.') }}
                    
                    </b></span>
                </div>
              </VCardCustom>
            </div>
            <div class="column is-3">
              <VCardCustom style="background-color: aliceblue;">
                <div :class="'label-cing'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1"><b> Sisa Piutang Inventory :  {{ H.formatRupiah(parseFloat(item.sisahutangALL).toFixed(2), 'Rp.') }}
                    
                    </b></span>
                </div>
              </VCardCustom>
            </div>
        </div>
    </div>
    <div class="column is-12">
      <div class="columns is-multiline">
        <div class="column is-12">
          <div v-if="dataSource.length == 0">
            <VPlaceholderSection
              :title="H.assets().notFound"
              :subtitle="H.assets().notFoundSubtitle"
              class="my-6"
            >
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                <img
                  class="dark-image"
                  src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                  alt=""
                />
              </template>
            </VPlaceholderSection>
          </div>
          <DataTable
            v-else
            v-model:filters="filtersTrans"
            :value="dataSource"
            paginator
            :rows="10"
            dataKey="no"
            filterDisplay="row"
            :globalFilterFields="['namarekanan', 'umur']"
            :class="`p-datatable-small`"
            showGridlines
            stripedRows
            :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
            v-model:expanded-rows="expandedRows"
          >
            <!-- <DataTable v-else v-model:expandedRowGroups="expandedRowGroups" :class="`p-datatable-small`" :value="dataSource"
                v-model:filters="filtersTrans" :globalFilterFields="['namarekanan']" rowGroupMode="subheader"
                :rowsPerPageOptions="[5, 10, 25, 100]" :rows="5" paginator groupRowsBy="namarekanan" :loading="isLoading"
                @rowgroup-expand="onRowGroupExpand" @rowgroup-collapse="onRowGroupCollapse" sortMode="single"
                sortField="namarekanan" :sortOrder="5" showGridlines> -->
            <template #header>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VButton
                    type="button"
                    icon="pi pi-file-excel"
                    class="mr-3"
                    v-tooltip-prime="'Export'"
                    @click="exportExcel(dataSource, 'LapKunjungan')"
                    color="primary"
                  >
                    Export Excel
                  </VButton>
                </div>
                <div class="column is-3 is-offset-6">
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

            <ColumnGroup type="header">
              <Row>
                <Column header="Detail" :rowspan="2" />
                <Column header="Nama Perusahaan" :rowspan="2" />
                <Column header="Tanggal Piutang" :rowspan="2" />
                <Column header="Tanggal Pembayaran" :rowspan="2" />
                <Column header="Umur Piutang (Dalam Hari)" :rowspan="2" />
                <Column header="Total Tagihan" :rowspan="2" />
                <Column header="Aging s.d 30 days" :colspan="3" />
                <Column header="Aging 31-90 days" :colspan="3" />
                <Column header="Aging 91-180 days" :colspan="3" />
                <Column header="Aging 181-360 days" :colspan="3" />
                <Column header="Aging >360 days" :colspan="3" />
                <Column header="Sisa Piutang" :rowspan="2" />
                <Column header="Status" :rowspan="2" />
              </Row>
              <Row>
                <Column header="Sisa" />
                <Column header="Dibayar" />
                <Column header="Persen" />
                <Column header="Sisa" />
                <Column header="Dibayar" />
                <Column header="Persen" />
                <Column header="Sisa" />
                <Column header="Dibayar" />
                <Column header="Persen" />
                <Column header="Sisa" />
                <Column header="Dibayar" />
                <Column header="Persen" />
                <Column header="Sisa" />
                <Column header="Dibayar" />
                <Column header="Persen" />
              </Row>
            </ColumnGroup>

            <Column expander style="width: 5rem" />
            <Column field="namarekanan" />
            <Column field="tglstruk" />
            <Column field="tglsbk" />
            <Column field="umur" />
            <Column field="totaltagihan" />
            <Column field="sisaSatuBulan" />
            <Column field="dibayarSatuBulan" />
            <Column field="persenSatuBulan" />
            <Column field="sisaDuaBulan" />
            <Column field="dibayarDuaBulan" />
            <Column field="persenDuaBulan" />
            <Column field="sisaTigaBulan" />
            <Column field="dibayarTigaBulan" />
            <Column field="persenTigaBulan" />
            <Column field="sisaEnamBulan" />
            <Column field="dibayarEnamBulan" />
            <Column field="persenEnamBulan" />
            <Column field="sisaLebihDuaBelasBulan" />
            <Column field="dibayarLebihDuaBelasBulan" />
            <Column field="persenLebihDuaBelasBulan" />
            <Column field="sisautang" />
            <Column>
              <template #body="slotProps">
                <Tag
                  :value="slotProps.data.status"
                  :severity="getSeverity(slotProps.data)"
                />
              </template>
            </Column>
            <template #expansion="slotProps">
              <div class="p-3">
                <DataTable
                  :value="slotProps.data.details"
                  :rows="10"
                  showGridlines
                  class="p-datatable-sm"
                  responsiveLayout="stack"
                  breakpoint="960px"
                  sortMode="multiple"
                >
                  <Column field="namaproduk" header="Produk" />
                  <Column
                    field="subtotal"
                    header="Total Harga Produk"
                    style="text-align: right"
                  >
                    <template #body="slotProps">
                      {{ H.formatRupiah(slotProps.data.subtotal, 'Rp.') }}
                    </template>
                  </Column>
          
                  <Column
                    field="nominal"
                    header="Total Sudah Dibayar"
                    style="text-align: right"
                  >
                    <template #body="slotProps">
                      {{ H.formatRupiah(parseFloat(slotProps.data.nominal).toFixed(2), 'Rp.') }}
                    </template>
                  </Column>
                  <Column
                    field="persen"
                    header="Presentase Turnover (%)"
                    style="text-align: center"
                  >
                  <template #body="slotProps">
                      {{ parseFloat(slotProps.data.persen).toFixed(2) }}
                    </template>
                  </Column>
                </DataTable>
              </div>
              <!-- <div class="column is-12 p-0">
                <div class="content">
                  <div class="is-divider" data-content="Total Keseluruhan" />
                </div>
              </div>

              <div class="column is-12 p-0">
                <div class="column is-3 p-0" style="margin-left: auto">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status" color="danger">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">TOTAL</span>
                    </div>
                    <small class="text-bold-custom h-100">{{
                      H.formatRp(item.totaltagihan, 'Rp.')
                    }}</small>
                  </VCardCustom>
                </div>
              </div> -->
            </template>
          </DataTable>
        </div>
      </div>
    </div>
    <div class="column is-12">
     <span class="ml-3"><b> Catatan : </b></span>
     <br />
     <span class="ml-3"><b>- Umur Piutang :</b> lamanya waktu yang telah berlalu sejak suatu piutang dibuat atau jatuh tempo hingga piutang tersebut lunas </span>
     <br/>
                           
      </div>
                      
  </VCard>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Column from 'primevue/column'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete'
import moment from 'moment'
import { useApi } from '/@src/composable/useApi'
import Calendar from 'primevue/calendar'
import ProgressBar from 'primevue/progressbar'
import ColumnGroup from 'primevue/columngroup'
import Row from 'primevue/row'
import { FilterMatchMode } from 'primevue/api'

import Tag from 'primevue/tag'
const title = 'Umur Piutang Inventory'
useHead({
  title: 'Umur Piutang Inventory - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const expandedRowGroups: any = ref()
const expandedRows = ref()
const dataSource: any = ref([])
const d_Rekanan: any = ref([])
const item: any = reactive({
  qBulan: [new Date(), new Date()],
})
const isLoading: any = ref(false)
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } })

const fetchData = async () => {
  isLoading.value = true
  let tglAwal = H.formatDate(item.qBulan[0], 'YYYY-MM-DD 00:00:00')
  let tglAkhir = H.formatDate(
    item.qBulan[1] ? item.qBulan[1] : item.qBulan[0],
    'YYYY-MM-DD 23:59:59'
  )
  let test: any
  let namaRekanan = item.namarekanan ? item.namarekanan.value : ''
  await useApi()
    .get(`mkko/lap-inventory?rekananfk=${namaRekanan}&dari=${tglAwal}&sampai=${tglAkhir}`)
    .then((response: any) => {
      response.data.map((data: any, index) => {
        ;(data.tglTransaksi = moment(data.tglstruk).format('DD-MM-YYYY HH:mm:ss')),
          (data.sisaSatuBulan = H.formatRp(data.bulan3.sisa, 'Rp')),
          (data.sisaDuaBulan = H.formatRp(data.bulan6.sisa, 'Rp')),
          (data.sisaTigaBulan = H.formatRp(data.bulan9.sisa, 'Rp')),
          (data.sisaEnamBulan = H.formatRp(data.bulan12.sisa, 'Rp')),
          (data.sisaLebihDuaBelasBulan = H.formatRp(data.lebihdari12.sisa, 'Rp')),
          (data.dibayarSatuBulan = H.formatRp(data.bulan3.totaldibayar, 'Rp')),
          (data.dibayarDuaBulan = H.formatRp(data.bulan6.totaldibayar, 'Rp')),
          (data.dibayarTigaBulan = H.formatRp(data.bulan9.totaldibayar, 'Rp')),
          (data.dibayarEnamBulan = H.formatRp(data.bulan12.totaldibayar, 'Rp')),
          (data.dibayarLebihDuaBelasBulan = H.formatRp(
            data.lebihdari12.totaldibayar,
            'Rp'
          )),
          (data.persenSatuBulan = data.bulan3.persen.toFixed(2) + '%'),
          (data.persenDuaBulan = data.bulan6.persen.toFixed(2) + '%'),
          (data.persenTigaBulan = data.bulan9.persen.toFixed(2) + '%'),
          (data.persenEnamBulan = data.bulan12.persen.toFixed(2) + '%'),
          (data.persenLebihDuaBelasBulan = data.lebihdari12.persen.toFixed(2) + '%'),
          (data.totaltagihan = H.formatRp(data.totaltagihan, 'Rp')),
          (data.sisautang = H.formatRp(data.sisautang, 'Rp'))
        // data.sisaTagihan = H.formatRp((data.totaltagihan - data.sudahbayar), 'Rp')
      })
      isLoading.value = false
      dataSource.value = response.data
      expandedRows.value = response.data.details
      item.sisahutangALL = response.sisahutang
      item.totalTagihanALL = response.totalTagihan
      item.totalBayarALL = response.totalbayar
      countTotal()
    })
    

}

function countTotal() {
let total = 0
let hasil = 0
for (let x = 0; x < dataSource.value.length; x++) {
    const element = dataSource.value[x];
    total = total + parseFloat(element.umur)
}
hasil = total / dataSource.value.length
item.totalTurnover = hasil
}

const fetchRekanan = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Rekanan.value = response
    })
}
const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}
const getSeverity = (data: any) => {
  switch (data.status) {
    case 'LUNAS':
      return 'success'
    default:
      return 'warning'
  }
}
fetchData()
</script>
<style lang="scss"></style>
