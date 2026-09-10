<template>
  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1">Laporan Surveilans </h3>
              </div>
              <div class="column is-3 pt-0 pb-0">
                <span>Periode</span>
                <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks class="pt-2">
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
              </div>
              <div class="column pt-0 pb-0 is-2">
                <span>Departement</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.departement" :suggestions="d_Departement"
                      @complete="fecthDepartemen($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Departemen" />
                  </VControl>
                </VField>
              </div>
              <div class="column pt-0 pb-0 is-3">
                <span>Ruangan</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan" />
                  </VControl>
                </VField>
              </div>
              <div class="column pt-0 pb-0 is-3">
                <span>Kelompok pasien</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.kelompokPasien" :suggestions="d_KelompokPasien"
                      @complete="fetchKelompokPasien($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Kelompok Pasien" />
                  </VControl>
                </VField>
              </div>
              <div class="column mt-4" style="margin-left: auto:  !important;">
                <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                  @click="fetchData()" :loading="isLoadingBtn">
                </VIconButton>
              </div>
            </div>
          </div>
        </div>
        <br />
        <TabView class="tabview-custom " :scrollable="true">
          <TabPanel>
            <template #header>
              <i class="fas fa-file mr-2" aria-hidden="true"></i>
              <span>Details</span>
            </template>
            <br />
            <div class="columns is-multiline">
              <div class="column is-12">
                <DataTable :value="d_Laporan" tableStyle="min-width: 50rem" scrollable :paginator="true" :rows="10"
                  :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" showGridlines>
                  <template #header>
                    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                      <VButton color="warning" icon="feather:printer" raised rounded style="margin-left:20px;"
                        @click="exportExcel()"> Export Excel
                      </VButton>
                    </div>
                  </template>
                  <Column field="no" header="No" frozen></Column>
                  <Column field="tglsurveilans" header="Tgl Surveilans" style="min-width: 150px" frozen></Column>
                  <Column field="nosurvailens" header="No Surveilans" style="min-width: 100px" frozen></Column>
                  <Column field="tglregistrasi" header="Tgl Masuk" style="min-width: 150px;"></Column>
                  <Column field="nocm" header="No Rm" style="min-width: 80px;"></Column>
                  <Column field="noregistrasi" header="No Registrasi" style="min-width: 100px;"></Column>
                  <Column field="namapasien" header="Nama Pasien" style="min-width: 180px;"></Column>
                  <Column field="jk" header="JK" style="min-width: 50px;"></Column>
                  <Column field="kelompokpasien" header="Tipe Pasien" style="min-width: 100px;"></Column>
                  <Column field="namaruangan" header="Unit Layanan" style="min-width: 100px;"></Column>
                  <Column field="tgllahir" header="Tgl Lahir" style="min-width: 100px;"></Column>
                </DataTable>
              </div>
            </div>
          </TabPanel>
          <!-- <TabPanel>
            <template #header>
              <i class="fas fa-file mr-2" aria-hidden="true"></i>
              <span>Surveilans Harian</span>
            </template>
            <br />
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VCard class="card-round-1">
                    <p class="title-c">Laporan Surveilans Harian</p>
                  </VCard>
                </div>
              </div>
            </div>
          </TabPanel>
          <TabPanel>
            <template #header>
              <i class="fas fa-file mr-2" aria-hidden="true"></i>
              <span>Surveilans HAIs</span>
            </template>
            <br />
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VCard class="card-round-1">
                    <p class="title-c">Laporan Surveilans HAIs</p>
                  </VCard>
                </div>
              </div>
            </div>
          </TabPanel> -->
        </TabView>
      </div>
    </VCard>
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import * as XLSX from "xlsx"
import * as XLSXStyle from 'xlsx-js-style'
useHead({
  title: 'Laporan Surveilans - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const d_Departement: any = ref([])
const d_Ruangan: any = ref([])
const d_KelompokPasien: any = ref([])
const d_Laporan: any = ref([])
const d_LaporanHarian: any = ref([])
const isLoadingBtn: any = ref(false)
const isLoading: any = ref(false)
const item: any = reactive({
  bulan: new Date(),
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
});


const fecthDepartemen = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/departemen_m?select=id,namadepartemen&param_search=namadepartemen&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Departement.value = response
  })
}
const fetchRuangan = async (filter: any) => {
  let depId = item.departement ? item.departement.value : ''
  const response = await useApi().get(`/rekammedis/get-ruangan-by-departement?idDepartement=${depId}&namaruangan=${filter.query}`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
}
const fetchKelompokPasien = async (filter: any) => {
  await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}
const fetchData = async () => {
  let startDate = `${moment(item.periode.start).format('YYYY-MM-DD')}`
  let endDate = `${moment(item.periode.end).format('YYYY-MM-DD')}`
  let departement = item.departement ? item.departement.value : ''
  let ruangan = item.ruangan ? item.ruangan.value : ''
  let kelompokpasien = item.kelompokpasien ? item.kelompokpasien.value : ''
  useApi().get(
    `/ppi/get-data-surveilans?tglAwal=${startDate}&tglAkhir=${endDate}&idDept=${departement}&idRuangan=${ruangan}&kelompokPasien=${kelompokpasien}`).then((response: any) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
        element.tglsurveilans = H.formatDateToLocalString(element.tglsurveilans)
        element.tgllahir = H.formatDateToLocalString(element.tgllahir)
        element.tglregistrasi = H.formatDateToLocalString(element.tglregistrasi)
      });
      d_Laporan.value = response.data
      isLoading.value = false
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const fetchDataHarian = async () => {
  let startDate = `${moment(item.periode.start).format('YYYY-MM-DD')}`
  let endDate = `${moment(item.periode.end).format('YYYY-MM-DD')}`
  let departement = item.departement ? item.departement.value : ''
  let ruangan = item.ruangan ? item.ruangan.value : ''
  let kelompokpasien = item.kelompokpasien ? item.kelompokpasien.value : ''
}
const exportExcel = () => {

  const dataSource = d_Laporan.value;
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Data Surveilans Ruangan'],
    [],
    ['NO', 'Tgl Surveilans', 'No Surveilans', 'Tgl Masuk', 'NO RM', 'NO REGISTRASI', 'NAMA PASIEN', 'JK', 'TIPE PASIEN', 'UNIT LAYANAN', 'TGL LAHIR'],
    ...dataSource.map((e: any, index: number) => [
      index + 1,
      e.tglsurveilans,
      e.nosurvailens,
      e.tglregistrasi,
      e.nocm,
      e.noregistrasi,
      e.namapasien,
      e.jk,
      e.kelompokpasien,
      e.namaruangan,
      e.tgllahir
    ]),
  ]);

  // Defining style for the header (centered)
  const headerStyle = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      color: { rgb: 'FFFFFF' },
      bold: true,
    },
    fill: { fgColor: { rgb: '807C7C' } },
  };

  // Applying header style
  const headerRange = XLSX.utils.decode_range(worksheet['!ref'] ?? 'A1:A1');
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
    worksheet[headerCell].s = headerStyle;
  }

  // Setting column widths
  const columnWidths = [5, 15, 15, 15, 8, 15, 30, 10, 20, 15, 18];

  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    worksheet['!cols'] = worksheet['!cols'] || [];
    worksheet['!cols'][col] = { wch: columnWidths[col] };
  }

  // Centering the text in cell A1
  const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
  worksheet[titleCell].s = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      bold: true,
      sz: 18,
    },
  };

  // Merging the first two rows
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 11 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Surveilans Ruangan', true);

  XLSXStyle.writeFile(workbook, 'Data Surveilans Ruangan.xlsx');
}
fetchData();
</script>
<style lang="scss"></style>
