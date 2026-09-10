<template>
    <VCard>
      <div class="column c-title-x">
        <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
      </div>
      <div class="column is-12">
      <div class="columns is-multiline">
        <div class="column is-3">
          <VField label="Tanggal Pemakaian Inventory">
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
                  <Column header="Produk" :rowspan="2" />
                  <Column header="Tanggal Penerimaan" :rowspan="2" />
                  <Column header="Tanggal Penjualan" :rowspan="2" />
                          
                  <Column header="Umur (Hari)" :rowspan="2" />
                  <Column header="Stok" :rowspan="2" />
                  <Column header="Aging s.d 30 Days" :colspan="3" />
                  <Column header="Aging 31 - 90 Days" :colspan="3" />
                  <Column header="Aging 91 - 180 Days" :colspan="3" />
                  <Column header="Aging 181 - 360 Days" :colspan="3" />
                  <Column header="Aging > 360 Days" :colspan="3" />
                  <Column header="Sisa Stok" :rowspan="2" />
                 
                </Row>
                <Row>
                  <Column header="Terpakai" />
                  <Column header="Persen" />
                  <Column header="Dibayar" />
                  <Column header="Terpakai" />
                  <Column header="Persen" />
                  <Column header="Dibayar" />
                  <Column header="Terpakai" />
                  <Column header="Persen" />
                  <Column header="Dibayar" />
                  <Column header="Terpakai" />
                  <Column header="Persen" />
                  <Column header="Dibayar" />
                  <Column header="Terpakai" />
                  <Column header="Persen" />
                  <Column header="Dibayar" />
                </Row>
              </ColumnGroup>
              <template #groupheader="slotProps">
                <span class="vertical-align-middle ml-2 font-bold line-height-3">{{ slotProps.data.jenisproduk
                }}</span>
              </template>
              <Column field="namaproduk" />
              <Column field="namarekanan" />
             
              <Column field="tglstruk" />
              <Column field="tglpelayanan" />
              <Column field="umur" style="text-align: center"/>
              <Column field="stok" style="text-align: center"/>
              <Column field="sisaTigaBulan" style="text-align: center"/>
              <Column field="persenTigaBulan" style="text-align: center"/>
              <Column field="dibayarTigaBulan" style="text-align: right"/>
              <Column field="sisaEnamBulan" style="text-align: center"/>
              <Column field="persenEnamBulan" style="text-align: center"/>
              <Column field="dibayarEnamBulan" style="text-align: right"/>
              <Column field="sisaSembilanBulan" style="text-align: center"/>
              <Column field="persenSembilanBulan" style="text-align: center"/>
              <Column field="dibayarSembilanBulan" style="text-align: right"/>
              <Column field="sisaDuaBelasBulan" style="text-align: center"/>
              <Column field="persenDuaBelasBulan" style="text-align: center"/>
              <Column field="dibayarDuaBelasBulan" style="text-align: right"/>
              <Column field="sisaLebihDuaBelasBulan" style="text-align: center"/>
              <Column field="persenLebihDuaBelasBulan" style="text-align: center"/>
              <Column field="dibayarLebihDuaBelasBulan" style="text-align: right"/>
              <Column field="sisa" style="text-align: center"/>
    
             
            </DataTable>
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
  const title = 'Laporan Inventory'
  useHead({
    title: 'Laporan Inventory - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  
  const expandedRowGroups: any = ref();
  const dataSource: any = ref([]);
  const item: any = reactive({
  qBulan: [new Date(), new Date()],
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
    .get(`mkko/lap-inventori?dari=${tglAwal}&sampai=${tglAkhir}`).then((response: any) => {
      response.data.map((data: any, index) => {
        data.tglTransaksi = moment(data.tglstruk).format('DD-MM-YYYY HH:mm:ss'),
          data.sisaTigaBulan = data.bulan3.terjual,
          data.sisaEnamBulan = data.bulan6.terjual,
          data.sisaSembilanBulan = data.bulan9.terjual,
          data.sisaDuaBelasBulan = data.bulan12.terjual,
          data.sisaLebihDuaBelasBulan = data.lebihdari12.terjual,
          data.dibayarTigaBulan = H.formatRp((data.bulan3.nominal), 'Rp')
          data.dibayarEnamBulan =  H.formatRp((data.bulan6.nominal), 'Rp') 
          data.dibayarSembilanBulan =  H.formatRp((data.bulan9.nominal), 'Rp')
          data.dibayarDuaBelasBulan =  H.formatRp((data.bulan12.nominal), 'Rp')
          data.dibayarLebihDuaBelasBulan = data.lebihdari12.nominal,
          data.persenTigaBulan = data.bulan3.persen.toFixed(2) + '%',
          data.persenEnamBulan = data.bulan6.persen.toFixed(2) + '%',
          data.persenSembilanBulan = data.bulan9.persen.toFixed(2) + '%',
          data.persenDuaBelasBulan = data.bulan12.persen.toFixed(2) + '%',
          data.persenLebihDuaBelasBulan = data.lebihdari12.persen.toFixed(2) + '%',
          data.sisa = data.stok - (data.sisaTigaBulan +  data.sisaEnamBulan +  data.sisaSembilanBulan + data.sisaLebihDuaBelasBulan )
      })
      isLoading.value = false;
      dataSource.value = response.data
    })
    console.log(data.sisa)
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
  