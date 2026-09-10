
<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">BOR LOS TOI</label>
      </div>
      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField label="Periode">
              <VControl class="prime-auto">
                <Calendar inputId="range" selectionMode="range" v-model="item.qBulan" :manualInput="false" class="w-100  "
                  :showIcon="true" view="month" dateFormat="MM-yy" />
              </VControl>
            </VField>
          </div>

          <!-- <div class="column is-3">
            <VField label="Prioritas" class="is-rounded-select is-autocomplete-select">
              <VControl icon="feather:search" class="prime-auto-select ">
                <Dropdown v-model="item.qPrioritas" :options="d_Ruangan" :optionLabel="'prioritas'"
                  placeholder="Prioritas" style="width: 100%;" showClear :filter="true" />
              </VControl>
            </VField>
          </div> -->

          <div class="column mt-5">
            <VButton type="button" icon="feather:search" color="primary" raised :loading="isLoading" @click="fetchData()">
              Cari
            </VButton>
            <VButton type="button" icon="feather:send" color="info" raised :loading="isLoading2" @click="kirimData()"
              class="ml-2">
              Kirim Data
            </VButton>
          </div>
          <!-- <div class="column is-12">
            <Fieldset legend="Query" :toggleable="true" :collapsed="true">
              <div class="column is-12">
                <VField>
                  <VLabel class="required-field">Query</VLabel>
                  <VControl>
                    <VTextarea v-model="item.query" rows="5" placeholder="...">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset legend="CATATAN" :toggleable="true" :collapsed="true">
              <div class="column is-12">
                <br />
                select <br />
                {tanggal -> (yyyy-mm-dd HH:mm:ss)}, <br />
                {bor}, <br />
                {alos}, <br />
                {toi}, <br />
                {bto}, <br />
                {ndr}, <br />
                {ndr}, <br />
                from <br />
                {query} <br />
                where BETWEEN tanggal <br />
                [ {akan di isi dengan parameter (bulan -> yyyy-mm)}]
              </div>
            </Fieldset>
          </div> -->
          <div class="column is-12">
            <TabView class="tabview-custom mt-3" :activeIndex="activeTab"  :scrollable="true" @tab-click="klikTab($event)">
                <TabPanel>
                  <template #header>
                    <span>Rekap </span>
                  </template>
                    <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25, 50, 100]"
                      class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
                      paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                      responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
                      currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
                      <template #header>
                        <div class="columns is-multiline">
                          <div class="column is-5">
                            <VButton type="button" icon="pi pi-file-excel" class="mr-3" outlined circle raised
                              v-tooltip-prime="'Export'" @click="exportExcel(dataSource, 'BOR')">
                              Export Excel
                            </VButton>

                          </div>

                        </div>
                      </template>
                      <template #empty style="text-align: center;"> No data found. </template>
                      <Column field="no" header="No" style="width: 50px" />
                      <Column field="tanggal" header="Tanggal" />
                      <Column field="bor" header="BOR" />
                      <Column field="alos" header="LOS" />
                      <Column field="toi" header="TOI" />
                      <Column field="bto" header="BTO" />
                      <Column field="ndr" header="NDR" />
                      <Column field="gdr" header="GDR" />
                      <Column field="jmltempattidur" header="Jml Tempat Tidur" />
                      <!-- <Column field="ratakunjungan" header="RATA-RATA KUNJUNGAN"  /> -->
                    </DataTable>
                  </TabPanel>
                  <TabPanel>
                  <template #header>
                    <span>Detail </span>
                  </template>
                  <DataTable :value="dataSource2" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25, 50, 100]"
                      class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
                      paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                      responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
                      currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
                      <template #header>
                        <div class="columns is-multiline">
                          <div class="column is-5">
                            <VButton type="button" icon="pi pi-file-excel" class="mr-3" outlined circle raised
                              v-tooltip-prime="'Export'" @click="exportExcel(dataSource, 'BOR')">
                              Export Excel
                            </VButton>
                          </div>
                        </div>
                      </template>
                      <template #empty style="text-align: center;"> No data found. </template>
                      <Column field="no" header="No" style="width: 50px" />
                      <Column field="namaruangan" header="Ruangan" />
                      <Column field="tanggal" header="Tanggal" />
                      <Column field="bor" header="BOR" />
                      <Column field="alos" header="LOS" />
                      <Column field="toi" header="TOI" />
                      <Column field="bto" header="BTO" />
                      <Column field="ndr" header="NDR" />
                      <Column field="gdr" header="GDR" />
                      <Column field="jmltempattidur" header="Jml Tempat Tidur" />
                      <Column field="jmlruang" header="Jml Ruang"  />
                    </DataTable>
                </TabPanel>
              </TabView>

          </div>
        </div>
      </div>
    </VCard>
  </div>
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
import Dropdown from 'primevue/dropdown';
import * as XLSX from "xlsx";
import Fieldset from 'primevue/fieldset';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
useHead({
  title: 'BOR LOS TOI - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const activeTab:any = ref(0)
const item: any = ref({
  qBulan: [
    new Date()
  ],
  query: `SELECT * FROM closingborlostoi_t WHERE tanggal BETWEEN '$START_DATE' AND '$END_DATE' ORDER BY tanggal`
})
const isLoading2: any = ref(false)
const dataSource: any = ref([])
const dataSource2: any = ref([])
const isLoading = ref(false);
const fetchData = async () => {

  let dari = H.formatDate(item.value.qBulan[0], "YYYY-MM-01")
  let mont = item.value.qBulan[0]
  let last = new Date(mont.getFullYear(), mont.getMonth() + 1, 0).getDate();
  let sampai = H.formatDate(item.value.qBulan[0], "YYYY-MM-" + last)
  if (item.value.qBulan.length == 2 && item.value.qBulan[1] != null) {
    mont = item.value.qBulan[1]
    last = new Date(mont.getFullYear(), mont.getMonth() + 1, 0).getDate();
    sampai = H.formatDate(item.value.qBulan[1], "YYYY-MM-" + last)
  }
  item.value.dari = new Date(dari)
  item.value.sampai = new Date(sampai)
  let queryParam = item.value.query
  queryParam = queryParam.replace('$START_DATE', dari)
  queryParam = queryParam.replace('$END_DATE', sampai)
  item.value.query = queryParam


  isLoading.value = true
  let data = []
  try {
    // data = await useApi().get(`mkko/get-borlostoi?query=${queryParam}`)
    data = await useApi().get(`mkko/get-borlostoi?dari=${dari}&sampai=${sampai}`)
  } catch (e) {

  }
  isLoading.value = false
  for (let x = 0; x < data.data.length; x++) {
    const element = data.data[x];
    element.no = x + 1
  }
  for (let x = 0; x < data.detail.length; x++) {
    const element = data.detail[x];
    element.no = x + 1
  }
  dataSource.value = data.data
  dataSource2.value= data.detail
}
const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}

const kirimData = async () => {
  if (dataSource.value.length == 0) {
    H.alert('error', 'Data belum ada')
    return
  }

  let BOR = 0
  let BTO = 0
  let LOS = 0
  let ALOS = 0
  let jumlah_tempat_tidur = 0
  isLoading2.value = true
  for (let x = 0; x < dataSource.value.length; x++) {
    const element = dataSource.value[x];
    await useApi().post(`mkko/api-integrate`,
    {
      url: 'bor',
      method: 'POST',
      data: {
        "tanggal": element.tanggal,
        "detail": {
          "LOS_PASIEN_JIWA": "0",
          "ALOS_PASIEN_JIWA":  "0",
          "BOR": element.bor,
          "BTO":element.bto,
          "LOS_PASIEN_NON_JIWA": element.lamarawat,
          "ALOS_PASIEN_NON_JIWA": element.alos,
          "jumlah_tempat_tidur": element.jmltempattidur
        }
      }
    }
  ).then((response) => {
    isLoading2.value = false
  }).catch((e: any) => {
    isLoading2.value = false
  })
}
}
const klikTab = (e: any) => {
  activeTab.value = e.index
  // if(activeTab.value  == 0){
  //   fetchData()
  // }
  // if(activeTab.value  == 1){
  //   fetchData2()
  // }
}

fetchData()
// watch(item.value.qBulan =>(oldValue, newValue) => {
// })
watch(
  () => [
    item.value.qBulan
  ], (oldValue, newValue) => {
    fetchData()
  }
)
</script>

<style lang="scss">
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

// .title-page {
//   font-weight: 600;
//   font-size: 18px;
// }

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
  text-align: center !important;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top
}
</style>
