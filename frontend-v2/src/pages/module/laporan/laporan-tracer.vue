
<template>
    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-5">
          <label class="title-page">Laporan Tracer</label>
        </div>
        <div class="column is-12">
        <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true"
            :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
            v-model:filters="filters" :globalFilterFields="['namapasien', 'nocm' ,'kelompokpasien','noantrian' ,'namalengkap' ,'kelompokpasien']"
            filterDisplay="menu">
          <template #empty> {{ H.assets().notFound }}</template>
          <template #header>
            <div class="columns is-multiline pb-3">
              <div class="column is-2" style="padding-top:2rem">
                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                  Export To Excel
                </VButton>
              </div>
              <div class="column is-10">
                <div class="columns is-multiline" style="justify-content: flex-end;">
                  <div class="column is-5 pb-0">
                      <VField label="Periode" style="margin-bottom: 6px;" />
                      <VDatePicker v-model="item.qFilterTgl"  is-range color="pink" locale="id"   trim-weeks>
                        <template #default="{ inputValue, inputEvents }" >
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
                    <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="cariRiwayat" :loading="isLoading" />
                  </div>
                </div>
              </div>
            </div>
          </template>
           <Column field="no" header="No"></Column>
           <Column field="nocm" header="No RM"></Column>
           <Column field="namapasien" header="Nama Pasien"></Column>
           <Column field="unitasal" header="Unit Asal"></Column>
           <Column field="tglregistrasi" header="Tgl Registrasi"></Column>
           <Column field="tglkeluar" header="Tgl Keluar"></Column>
           <Column field="selisih" header="Selisih Waktu Kembali"></Column>
        </DataTable>
        </div>
      </VCard>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as XLSX from "xlsx"
import { FilterMatchMode } from 'primevue/api'
import moment from 'moment'
const input: any = ref({})
let isLoading = ref(false)
const dataSource: any = ref([])
const date = ref(new Date())
const d_Ruangan: any = ref([]);
const remakeData: any = ref([]);
let isPlaceLoad: any = ref(false)
const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const item: any = ref({
    qFilterTgl: {
        start: new Date(),
        end: new Date()
    },
})


useHead({
    title: 'Laporan Diagnosa Pasien - ' + import.meta.env.VITE_PROJECT,
})
async function cariRiwayat() {
    isPlaceLoad.value = true;
    let object: any = {};
    object = input.value;
    let startDate = moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let endDate   = moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
    let ruangan   = input.value.ruangan ? input.value.ruangan.value : "";
    let namapasien = input.value.namapasien ?? "";
    let unitAsal = input.value.ruangan ?? "";

    isLoading.value = true;
    useApi().get(
        `/resgistrasi/get-laporan-tracer?tglAwal=${startDate}&tglAkhir=${endDate}&ruangan=${ruangan}&namapasien=${namapasien}&unitAsal=${unitAsal}`).then((response: any) => {
            response.data.forEach((element: any, i: any) => {
                element.no = i + 1
            });
            dataSource.value = response.data
            isLoading.value = false
            console.log(JSON.stringify(response))
        }).catch((e: any) => {
            isLoading.value = false
        })
}
const setAutoFill = () => {
    input.value.tglAwal = new Date()
    input.value.tglAkhir = new Date()
}

const fetchRuangan = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const exportExcel = () => {
    remakeData.value = dataSource.value.map((e: any) => {
        return {
            No: e.no, NoRm: e.nocm, NamaPasien: e.namapasien,
            unitAsal: e.unitasal
        }
    })
    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}
const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
        type: EXCEL_TYPE
    });
    const _url = window.URL.createObjectURL(data)
    window.open(_url, EXCEL_EXTENSION).focus();
}
setAutoFill();
cariRiwayat();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/module/sysadmin/master-data.scss';
.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}
</style>
