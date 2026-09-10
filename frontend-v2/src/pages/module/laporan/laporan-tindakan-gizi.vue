<template>
  <div class="page-content-inner">
    <div class="is-navbar">
      <div class="form-layout">
        <div class="form-outer">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3>Laporan Order Gizi</h3>
              </div>
              <div class="right">
                <div class="buttons">
                  <VButton icon="lnir lnir-arrow-left rem-100" :to="{ name: 'module-dashboard-gizi' }" light
                    dark-outlined>
                    Kembali
                  </VButton>
                  <VButton type="button" icon="feather:search" :loading="isLoading" color="success" raised
                    @click="fetchLaporanGizi()"> Cari
                  </VButton>
                </div>
              </div>
            </div>
          </div>
          <div class="form-body">
            <!--Fieldset-->
            <div class="form-fieldset">
              <VCard>
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Filter Pencarian</h1>
                <div class="column is-12 p-0">
                  <div class="column is-6">
                    <VField label="Tanggal Order" />
                    <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                </div>
              </VCard>
            </div>
            <!--Fieldset-->
            <div class="form-fieldset">
              <div class="column is-3 pt-0">
                <VButton color="warning" class="mt-3 mr-4" icon="fas fa-file-excel" raised @click="exportExcel()">
                  Export to Excel </VButton>
              </div>
              <div class="column pt-0" v-if="isLoading">
                <VPlaceloadWrap v-for="data in 10">
                  <VPlaceload class="mx-2 mb-3" />
                </VPlaceloadWrap>
              </div>
              <div class="column pt-0" v-else>
                <VPlaceholderPage v-if="dataLaporan.length == 0" title="Data Tidak di Temukan."
                  subtitle="Silakan gunakan filter lain" larger>
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                  </template>
                </VPlaceholderPage>
                <div v-else>
                  <div class="column mt-5">
                    <div class="column-12">
                      <DataTable :value="dataLaporan" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                        class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
                        <template #empty> No order found. </template>
                        <Column field="no" header="No" frozen></Column>
                        <Column field="namaruangan" header="Ruangan" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="jenisdiet" header="Jenis Diet" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="total" header="Total" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                      </DataTable>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import moment from 'moment'
import * as XLSX from "xlsx";
const input: any = ref({})
const d_Departement: any = ref([]);
const d_Ruangan: any = ref([]);
const d_KelompokPasien: any = ref([]);
let isLoading = ref(false)
let dataLaporan: any = ref([])
const dataPasien: any = ref([])
const dataSourceICD9 = ref([])
const remakeData: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

const item: any = ref({
  filterNama: '',
  filterNocm: '',
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
})


useHead({
  title: 'Laporan Tindakan Gizi - ' + import.meta.env.VITE_PROJECT,
})

async function fetchLaporanGizi() {
  try {
    let ruanganid = item.value.filterRuangan || '';
    let namapasien = item.value.filterNama || '';
    let nocm = item.value.filterNocm || '';
    let dari = item.value.filterTgl.start ? H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD') : '';
    let sampai = item.value.filterTgl.end ? H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD') : '';

    isLoading.value = true;
    dataLaporan.value.loading = true;

    const response = await useApi().get(
      `/dashboard/laporan-order-gizi?ruanganid=${ruanganid}&dari=${dari}&sampai=${sampai}&namapasien=${namapasien}&nocm=${nocm}`
    );
    const responseData = response.data || [];
    responseData.forEach((element, i) => {
      element.no = i + 1;
    });
    dataLaporan.value = responseData;
    isLoading.value = false;
    dataLaporan.value.loading = false;
  } catch (error) {
    isLoading.value = false;
    console.error('Error fetching data:', error);
  }
}


const setAutoFill = () => {
  input.value.tglAwal = new Date()
  input.value.tglAkhir = new Date()
}

const fetchKelompokPasien = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}


const exportExcel = () => {
  remakeData.value = dataLaporan.value.map((e: any) => {
    return {
      No: e.no, 
      Ruangan: e.namaruangan,
      Jenis_Diet: e.jenisdiet,
      Total: e.total
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
  // window.open(_url, EXCEL_EXTENSION).focus();
  const link = document.createElement('a');
  link.href = _url;
  link.download = 'Laporan Order Gizi' + EXCEL_EXTENSION;
  link.click();
  window.URL.revokeObjectURL(_url);
}
fetchLaporanGizi();
setAutoFill();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
  max-width: 1300px;
  margin: 0 auto;
}

.form-fieldset {
  padding: 10px 0;
  max-width: 100%;
  margin: 0 auto;
}

.table-pi {
  width: 1400px;
  border: 1px solid #929090;
}

.table-scroll {
  overflow-x: scroll;
}

.date {
  background-color: #9b9b9b;
  color: #fff;
}
</style>
