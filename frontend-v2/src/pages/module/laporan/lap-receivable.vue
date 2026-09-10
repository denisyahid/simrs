<template>
  <VCard>
    <div class="column c-title-x">
      <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
    </div>
    <div class="column is-12">
    <div class="columns is-multiline">
      <!-- <div class="column is-3">
        <VField label="Tanggal Posting Piutang">
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
      </div> -->
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
  <div claas="column is-12">
    <div class="columns is-multiline">
            <div class="column is-3">
              <VCardCustom style="background-color: aliceblue;">
                <div :class="'label-cing'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1"><b> Days Receivable Turnover :  {{ parseFloat(item.totalTurnover).toFixed(1) }} Days</b></span>
                </div>

              </VCardCustom>
            </div>
            <div class="column is-3">
              <VCardCustom style="background-color: aliceblue;">
                <div :class="'label-cing'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1"><b> Total Piutang :  {{ H.formatRupiah(parseFloat(item.totalTagihan).toFixed(2), 'Rp.') }}
                    
                    </b></span>
                </div>
              </VCardCustom>
            </div>
            <div class="column is-3">
              <VCardCustom style="background-color: aliceblue;">
                <div :class="'label-cing'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1"><b> Total Sudah Dibayar :  {{ H.formatRupiah(parseFloat(item.totalBayar).toFixed(2), 'Rp.') }}
                    
                    </b></span>
                </div>
              </VCardCustom>
            </div>
            <div class="column is-3">
              <VCardCustom style="background-color: aliceblue;">
                <div :class="'label-cing'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1"><b> Sisa Piutang :  {{ H.formatRupiah(parseFloat(item.sisaHutang).toFixed(2), 'Rp.') }}
                    
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
            <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderSection>
          </div>
          <DataTable v-else v-model:expandedRowGroups="expandedRowGroups" :class="`p-datatable-small`" :value="dataSource"
            v-model:filters="filtersTrans" :globalFilterFields="['namarekanan']" rowGroupMode="subheader"
            :rowsPerPageOptions="[5, 10, 25, 100]" :rows="5" paginator groupRowsBy="namarekanan" :loading="isLoading"
            @rowgroup-expand="onRowGroupExpand" @rowgroup-collapse="onRowGroupCollapse" sortMode="single"
            sortField="namarekanan" :sortOrder="5" showGridlines>
            <template #header>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="info" outlined circle raised
                    v-tooltip-prime="'Export'" @click="exportExcel()">
                    Export Excel
                  </VButton>
                </div>
                <div class="column is-3 is-offset-6">
                  <VField>
                    <VControl icon="feather:search">
                      <input v-model="filtersTrans['global'].value" v-on:keyup.enter="fetchData()" type="text"
                        class="input is-rounded" placeholder="Search" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </template>
            <ColumnGroup type="header">
              <Row>
                <Column header="Nomor Posting" :rowspan="2" />
                <Column header="Tanggal Posting" :rowspan="2" />
                <Column header="Tanggal Pembayaran" :rowspan="2" />
                <Column header="Total Piutang" :rowspan="2" />         
                <Column header="Umur (Hari)" :rowspan="2" />
  
                <Column header="Aging s.d 30 Days" :colspan="2" />
                <Column header="Aging 31 - 90 Days" :colspan="2" />
                <Column header="Aging 91 - 180 Days" :colspan="2" />
                <Column header="Aging 181 - 360 Days" :colspan="2" />
                <Column header="Aging > 360 Days" :colspan="2" />
                <Column header="Sisa Piutang" :rowspan="2" />
               
              </Row>
              <Row>
                <Column header="Dibayar" />
                <Column header="Persen" />
                <Column header="Dibayar" />
                <Column header="Persen" />
                <Column header="Dibayar" />
                <Column header="Persen" />
                <Column header="Dibayar" />
                <Column header="Persen" />
                <Column header="Dibayar" />
                <Column header="Persen" />
                
              </Row>
            </ColumnGroup>
            <template #groupheader="slotProps">
              <span class="vertical-align-middle ml-2 font-bold line-height-3">{{ slotProps.data.namarekanan
              }}</span>
            </template>
          
            <Column field="noposting" />
           
            <Column field="tglposting" />
            <Column field="tglsbm" />
            <Column field="totalTagihan" style="text-align: right;"/>
            <Column field="umur" style="text-align: center"/>
            <Column field="dibayarTigaBulan" style="text-align: right"/>
            <Column field="persenTigaBulan" style="text-align: center"/>
            <Column field="dibayarEnamBulan" style="text-align: right"/>
            <Column field="persenEnamBulan" style="text-align: center"/>
            <Column field="dibayarSembilanBulan" style="text-align: right"/>
            <Column field="persenSembilanBulan" style="text-align: center"/>
            <Column field="dibayarDuaBelasBulan" style="text-align: right"/>
            <Column field="persenDuaBelasBulan" style="text-align: center"/>
            <Column field="dibayarLebihDuaBelasBulan" style="text-align: right"/>
            <Column field="persenLebihDuaBelasBulan" style="text-align: center"/>
            <Column field="sisaTagihan" />
  
           
          </DataTable>
        </div>
        <div class="column is-12">
          <span><i><b>Keterangan :</b></i></span>
          <br />
          <span> - Days Receivable Turnover merupakan Ukuran hari yang digunakan untuk mengukur seberapa efektif rumah sakit vertikal dalam menagih piutang rumah sakit (menerima pembayaran piutang) </span>  
        </div>
      </div>
    </div>
  </VCard>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import DataTable from 'primevue/datatable';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import Column from 'primevue/column';
import { useHead } from '@vueuse/head';
import * as H from '/@src/utils/appHelper';
import AutoComplete from 'primevue/autocomplete';
import moment from 'moment';
import { useApi } from '/@src/composable/useApi';
import Calendar from 'primevue/calendar';
import ProgressBar from 'primevue/progressbar';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import { FilterMatchMode } from 'primevue/api';
import Tag from 'primevue/tag';
const title = 'Laporan Receivable'
useHead({
  title: 'Laporan Receivable - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const expandedRowGroups: any = ref();
const dataSource: any = ref([]);
const d_Rekanan: any = ref([]);
const item: any = reactive({
qBulan: [new Date(), new Date()],
totalTurnover : 0,
})
const isLoading: any = ref(false);
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });

const fetchData = async () => {
  isLoading.value = true;
  let tglAwal = H.formatDate(item.qBulan[0], 'YYYY-MM-DD 00:00:00')
  let tglAkhir = H.formatDate(
  item.qBulan[1] ? item.qBulan[1] : item.qBulan[0],
  'YYYY-MM-DD 23:59:59'
)
let namaRekanan = item.namarekanan ? item.namarekanan.value : ''
await useApi()
  .get(`mkko/lap-receivable?rekananfk=${namaRekanan}&dari=${tglAwal}&sampai=${tglAkhir}`).then((response: any) => {
    response.data.map((data: any, index) => {
      data.tglTransaksi = moment(data.tglstruk).format('DD-MM-YYYY HH:mm:ss'),
        data.dibayarTigaBulan = H.formatRp((data.bulan1.sudahbayar), 'Rp')
        data.dibayarEnamBulan =  H.formatRp((data.bulan3.sudahbayar), 'Rp') 
        data.dibayarSembilanBulan =  H.formatRp((data.bulan6.sudahbayar), 'Rp')
        data.dibayarDuaBelasBulan =  H.formatRp((data.bulan12.sudahbayar), 'Rp')
        data.dibayarLebihDuaBelasBulan = data.lebihdari12.sudahbayar,
        data.persenTigaBulan = data.bulan1.persen.toFixed(2) + '%',
        data.persenEnamBulan = data.bulan3.persen.toFixed(2) + '%',
        data.persenSembilanBulan = data.bulan6.persen.toFixed(2)+ '%',
        data.persenDuaBelasBulan = data.bulan12.persen.toFixed(2) + '%',
        data.persenLebihDuaBelasBulan = data.lebihdari12.persen.toFixed(2) + '%',
        data.totalTagihan = H.formatRp((data.tagihan), 'Rp'),
        data.sisaTagihan = H.formatRp((data.sisautang), 'Rp')
      
    })
    isLoading.value = false;
    dataSource.value = response.data
    countTotal()
  })
}
function countTotal() {
let total = 0
let hasil = 0
let totalTagihan = 0
let totalBayar = 0
let sisaHutang = 0
for (let x = 0; x < dataSource.value.length; x++) {
    const element = dataSource.value[x];
    total = total + parseFloat(element.umur)
}
for (let x = 0; x < dataSource.value.length; x++) {
    const element = dataSource.value[x];
    totalTagihan = totalTagihan + parseFloat(element.tagihan)
}
for (let x = 0; x < dataSource.value.length; x++) {
    const element = dataSource.value[x];
    totalBayar = totalBayar + parseFloat(element.sudahbayar)
}
for (let x = 0; x < dataSource.value.length; x++) {
    const element = dataSource.value[x];
    sisaHutang = sisaHutang + parseFloat(element.sisautang)
}
let sisa = 0;
    for (let x = 0; x < dataSource.value.length; x++) {
        const element = dataSource.value[x];
        if (element.sisaHutang =! 0) {
            sisa++;
        }
    }

hasil = 365 / (sisaHutang / (sisaHutang / sisa))

item.totalTurnover = hasil
item.totalTagihan = totalTagihan
item.totalBayar = totalBayar
item.sisaHutang = sisaHutang

}

const fetchRekanan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Rekanan.value = response
  })
}
const exportExcel = () => {

}
const getSeverity = (data: any) => {
  switch (data.status) {
    case 'Lunas':
      return 'success';
    default:
      return 'warning';
  }
};
fetchData();
</script>
<style lang="scss"></style>
