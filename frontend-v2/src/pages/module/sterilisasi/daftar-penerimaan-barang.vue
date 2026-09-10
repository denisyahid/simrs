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
            <div class="column is-6">

            </div>
            <div class="column is-2">
            </div>
            <div class="column is-3 is-pulled-right">
              <VField label="Periode">
                <VControl class="prime-auto">
                  <Calendar inputId="range" v-model="item.qFilterTgl" selectionMode="range" :manualInput="false"
                    class="w-100 mb-4 " :showIcon="true" date-format="dd-mm-yy" />
                </VControl>
              </VField>

            </div>
            <div class="column is-1 mt-5 ">
              <VIconButton type="button" color="success" circle raised icon="fas fa-search" @click="fetchData()"
                :loading="isLoading">
              </VIconButton>
            </div>
            <div class="column is-12 mt-5-min">
              <VCard class="card-round-1">
                <DataTable v-model:filters="filtersTrans" :value="dataSource" paginator :rows="10" filterDisplay="row"
                  :rowsPerPageOptions="[5, 10, 25, 100]"
                  :globalFilterFields="['nostruk', 'namaruangantujuan', 'namaruanganasal']" :class="`p-datatable-small`"
                  v-model:expandedRows="expandedRows" dataKey="no" @rowExpand="onRowExpand"
                  @rowCollapse="onRowCollapse">
                  <template #header>
                    <div class="columns is-multiline">
                      <div class="column is-3">
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
                  <template #empty> No data found. </template>
                  <Column expander style="width: 5px" />
                  <Column field="no" style="width: 5px;" header="No"></Column>
                  <Column field="status" style="width: 60px;" header="Status"></Column>
                  <Column field="tglstruk" style="width: 50px;" header="Tgl Struk"></Column>
                  <Column field="nostruk" style="width: 80px;" header="No Terima"></Column>
                  <Column field="jeniskirim" style="width: 80px;" header="Jenis Kirim"></Column>
                  <Column field="jmlitem" style="width: 35px;" header="jmlitem"></Column>
                  <Column field="namaruanganasal" style="width: 100px;" header="Nama Ruangan Asal"></Column>
                  <Column field="namaruangantujuan" style="width: 100px;" header="Nama Ruangan Tujuan"></Column>
                  <Column field="petugas" style="width: 100px;" header="Petugas"></Column>
                  <Column field="keterangan" style="width: 100px;" header="Keterangan"></Column>
                  <Column field="statussteril" style="width: 80px;" header="Status"></Column>
                  <Column field="statusbersih" style="width: 80px;" header="Status Bersih"></Column>
                  <Column field="statussterilisasi" style="width: 80px;" header="Status Sterilisasi"></Column>
                  <template #expansion="slotProps">
                    <div class="p-3">
                      <DataTable :value="slotProps.data.details">
                        <Column field="no" style="width: 25px;" header="No"></Column>
                        <Column field="kdproduk" style="width: 50px;" header="Kd Produk"></Column>
                        <Column field="namaproduk" style="width: 150px;" header="Nama Produk"></Column>
                        <Column field="satuanstandar" style="width: 40px;" header="Satuan"></Column>
                        <Column field="qtyproduk" style="width: 40px;" header="Qty"></Column>
                        <Column field="qtyprodukretur" style="width: 40px;" header="Qty Retur"></Column>
                      </DataTable>
                    </div>
                  </template>
                </DataTable>
              </VCard>
            </div>
          </div>
        </div>
      </VCard>
    </div>
  </section>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Dropdown from 'primevue/dropdown'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Toolbar from 'primevue/toolbar'
import Button from 'primevue/button'
import AutoComplete from 'primevue/autocomplete'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import Calendar from 'primevue/calendar'
import * as H from '/@src/utils/appHelper'
import moment from 'moment'
import { FilterMatchMode } from 'primevue/api'


const TITLE_PAGE = "Penerimaan Barang Instalasi"
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
useHead({
  title: TITLE_PAGE + import.meta.env.VITE_PROJECT,
})

const item: any = reactive({
  qFilterTgl: [
    new Date(),
    new Date()
  ],
})
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const dataSource: any = ref([])
const isLoading: any = ref(false)
const expandedRows = ref([]);
const onRowExpand = (event: any) => {
  expandedRows.value = dataSource.value.filter((p: any) => p.no == event.data.no);
}
const onRowCollapse = (event: any) => {
};
const fetchData = async () => {
  isLoading.value = true
  let tglAwal = moment(item.qFilterTgl[0]).format('YYYY-MM-DD 00:00:00')
  let tglAkhir = moment(item.qFilterTgl[1]).format('YYYY-MM-DD 23:59:59')
  const response = await useApi().get(`stelilisasi/daftar-distribusi-barang?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`);
  response.data.map((element: any, index: number) => {
    element.no = index + 1
    element.tglstruk = H.formatDate(element.tglstruk, "DD/MM/YYYY")
    element.details.map((element2: any, index2: number) => {
      element2.no = index2 + 1
    })
  })
  dataSource.value = response.data
  isLoading.value = false
}
const exportExcel = async () => {
}
fetchData();
</script>
