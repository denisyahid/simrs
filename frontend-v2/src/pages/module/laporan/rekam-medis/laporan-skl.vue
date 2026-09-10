<template>
  <section>
    <div class="column is-12">
      <VCard>
        <div class="column is-12">
          <DataTable :value="dataSource" class="p-datatable-sm" :loading="isPlaceLoad" :paginator="true" :rows="10"
            :rowsPerPageOptions="[5, 10, 25]" scrollable
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
            v-model:filters="filters"
            :globalFilterFields="['namaanak', 'norm', 'noskl', 'dokterPenolong', 'namaibuuk', 'nocmfkibu']"
            filterDisplay="menu">
            <template #empty> {{ H.assets().notFound }}</template>
            <template #header>
              <div class="columns is-multiline pb-3">
                <div class="column is-10">
                  <div class="columns is-multiline" style="justify-content: flex-end;">
                    <div class="column is-5 pb-0">
                      <VField label="Periode" style="margin-bottom: 6px;" />
                      <VDatePicker v-model="item.qFilterTgl" is-range color="pink" locale="id" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                          <VField addons>
                            <VControl icon="feather:calendar">
                              <VInput :value="inputValue.start" v-on="inputEvents.start" />
                            </VControl>
                            <VControl>
                              <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                            </VControl>
                            <VControl icon="feather:calendar">
                              <VInput :value="inputValue.end" v-on="inputEvents.end" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </div>
                    <div class="column is-3 pb-0">
                      <VField label="Cari">
                        <VInput v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
                      </VField>
                    </div>
                    <div class="column is-1 mt-5">
                      <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData"
                        :loading="isPlaceLoad" />
                    </div>
                  </div>
                </div>
              </div>
            </template>
            <Column field="no" header="#" frozen></Column>
            <Column field="namaanak" header="Nama Anak" frozen :sortable="true" style="min-width: 200px"></Column>
            <Column field="norm" header="NO RM" :sortable="true" style="min-width: 200px"></Column>
            <Column field="noskl" header="No SKL" :sortable="true" style="min-width: 200px"></Column>
            <Column field="dokterPenolong" header="Dokter Penolong" :sortable="true" style="min-width: 200px"></Column>
            <Column header="Nama Ibu" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                  {{ slotProps.data.namaIBUForReal || slotProps.data.namaibu }}
              </template>
            </Column>
            <Column field="nocmfkibu" header="No RM Ibu" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                  {{ slotProps.data.nocmfkibu || slotProps.data.normIbu }}
              </template>
            </Column>
            <Column header="Cetak" :sortable="true" style="min-width: 10px">
              <template #body="slotProps">
                <VIconButton type="button" raised circle icon="feather:printer"
                  @click="cetakDetail(slotProps.data.norec)" color="warning" v-tooltip.bubble="'Cetak SKL'"></VIconButton>
              </template>
            </Column>
            <Column field="namaruangan" header="Ruangan Di Buat" :sortable="true" style="min-width: 200px"></Column>
            <Column field="created_at" header="Tanggal Di Buat" :sortable="true" style="min-width: 200px"></Column>
          </DataTable>
        </div>
      </VCard>
    </div>
  </section>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import * as XLSX from "xlsx";
import { FilterMatchMode } from 'primevue/api'
import { useConfirm } from 'primevue/useconfirm'

// app.directive('tooltip', Tooltip);

useHead({
  title: 'Laporan Surat Keterangan Lahir - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})

const confirm = useConfirm()
const dataSource: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
let d_KelompokPasien: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(false)
let loadSave: any = ref(false)
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})

const fetchData = async () => {
  isPlaceLoad.value = true
  let tglAwal = H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')
  let tglAkhir = H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')
  let namaAnak = item.value.namaanak ? `&namaanak=${item.value.namaanak}` : ''
  let norm = item.value.norm ? `&norm=${item.value.norm}` : ''
  let dokterPenolong = item.value.dokterPenolong ? `&dokterPenolong=${item.value.dokterPenolong}` : ''
  let nocmfkIbu = item.value.nocmfkibu ? `&nocmfkibu=${item.value.nocmfkibu}` : ''

  loadSearch.value = true
  await useApi().get(`/laporan/get-all-laporan-lahir?dari=${tglAwal}&sampai=${tglAkhir}${namaAnak}${norm}${dokterPenolong}${nocmfkIbu}`).then((response) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response.data
  })
  loadData.value = false
  loadSearch.value = false
  isPlaceLoad.value = false
}

const cetakDetail = (data: any) => {
  H.printBlade('laporan/cetak-laporan-lahir?norec=' + data)
}


fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 0px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
}
</style>
