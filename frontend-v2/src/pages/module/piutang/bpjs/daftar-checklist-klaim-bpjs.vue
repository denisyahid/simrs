<template>
  <div class="column is-12">
    <VCard>
      <h1 class="title is-4">
        Data Checklist Klaim BPJS
      </h1>
      <div class="columns is-multiline">
        <div class="column">
          <VField label="Tanggal">
            <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
              <template #default="{ inputValue, inputEvents }">
                <VField addons>
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                  </VControl>
                  <VControl>
                    <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </VButton>
                  </VControl>
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>
        <div class="column mt-3">
          <VIconButton type="button" color="success" class="mt-5" raised icon="fas fa-search" @click="fetchData()"
            :loading="isLoading">
          </VIconButton>
        </div>
      </div>
    </VCard>
  </div>
  <div class="column is-12">
    <VCard class="card-round-1">
      <div class="column" v-if="isLoading">
        <VPlaceloadWrap v-for="data in 10">
          <VPlaceload class="mx-2 mb-3" />
        </VPlaceloadWrap>
      </div>
      <div v-else-if="dataSource.length == 0">
        <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
          <template #image>
            <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
          </template>
        </VPlaceholderSection>
      </div>
      <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
        :rowsPerPageOptions="[5, 10, 25]" scrollable
        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines v-else>
        <template #header>
          <div class="flex flex-wrap align-items-center justify-content-between gap-2">
            <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
              to
              Excel </VButton>
          </div>
        </template>
        <ColumnGroup type="header">
          <Row>
            <Column header="TANGGAL" :rowspan="3" />
            <Column class="align-items-center" header="Rawat Jalan" :colspan="2" />
            <Column class="align-items-center" header="Rawat Inap" :colspan="8" />
          </Row>
          <Row>
            <Column class="align-items-center" header="Berkas" :rowspan="2" />
            <Column class="align-items-center" header="BPJS" :rowspan="2" />
            <Column class="align-items-center" header="Berkas" :colspan="4" />
            <Column class="align-items-center" header="BPJS" :colspan="4" />
          </Row>
          <Row>
            <Column header="Kls 1" />
            <Column header="Kls 2" />
            <Column header="Kls 3" />
            <Column header="Total" />
            <Column header="Kls 1" />
            <Column header="Kls 2" />
            <Column header="Kls 3" />
            <Column header="Total" />
          </Row>
        </ColumnGroup>
        <Column field="tgl" />
        <Column field="berkas_rajal" />
        <Column field="bpjs_rajal" />
        <Column field="berkas_kls1" />
        <Column field="berkas_kls2" />
        <Column field="berkas_kls3" />
        <Column field="totalberkas" />
        <Column field="bpjs_kls1" />
        <Column field="bpjs_kls2" />
        <Column field="bpjs_kls3" />
        <Column field="totalbpjs" />
      </DataTable>
    </VCard>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useHead } from '@vueuse/head';
import * as H from '/@src/utils/appHelper';
import { useApi } from '/@src/composable/useApi';
import moment from 'moment';
import * as XLSX from "xlsx";
useHead({
  title: 'Daftar Checklist Klaim BPJS - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = reactive({
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
})
const dataSource: any = ref([])
const isLoading = ref(false)
const remakeData: any = ref([])

const fetchData = async () => {
  let tglAwal = moment(item.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = moment(item.qFilterTgl.end).format('YYYY-MM-DD')
  isLoading.value = true
  await useApi().get(`/piutang/get-checklist-klaim?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`).then((response: any) => {
    response.forEach((element: any, index: number) => {
      element.totalberkas = parseFloat(element.berkas_kls1) + parseFloat(element.berkas_kls2) + parseFloat(element.berkas_kls3),
        element.totalbpjs = parseFloat(element.bpjs_kls1) + parseFloat(element.bpjs_kls2) + parseFloat(element.bpjs_kls3)
    })
    dataSource.value = response
  })
  isLoading.value = false
  console.log(JSON.stringify(dataSource.value));

}
const exportExcel = () => {
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      Tanggal: e.tgl, BerkasRajal: e.berkas_rajal, BPJSRajal: e.bpjs_rajal,
      BerkasKelas1: e.berkas_kls1, BerkasKelas2: e.berkas_kls2, BerkasKelas3: e.berkas_kls3, TotalBerkas: e.totalberkas,
      BPJSKelas1: e.bpjs_kls1, BPJSKelas2: e.bpjs_kls2, BPJSKelas3: e.bpjs_kls3, TotalBPJS: e.totalbpjs
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
  const desiredFileName = 'checklist-klaim-bpjs' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}
fetchData();
</script>
<style lang="scss">
.title {
  font-weight: 600 !important;
}
</style>
