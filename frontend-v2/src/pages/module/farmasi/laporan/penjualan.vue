<template>
  <ConfirmDialog />
  <div class="columns is-multiline">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title-x">
        <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
      </div>
      <div class="column">
        <div class="search-widget">
          <div class="columns is-multiline">
            <div class="column is-3">
              <VField>
                <VLabel>Periode</VLabel>
                <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks :max-date="new Date()">
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
              </VField>
            </div>
            <div class="column is-2">
              <VField class="is-rounded-select_Z  is-autocomplete-select" label="Dokter">
                <VControl icon="feather:search" fullwidth class="prime-auto ">
                  <AutoComplete v-model="item.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField class="is-rounded-select_Z  is-autocomplete-select" label="Kelompok Pasien">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="item.kelompokpasien" :suggestions="d_KelompokPasien"
                    @complete="fetchKelompokPasien($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" @item-select="fetchPulang()" :loadingIcon="'pi pi-spinner'" :field="'label'"
                    placeholder="Kelompok Pasien..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField class="is-rounded-select_Z  is-autocomplete-select" label="Ruangan">
                <VControl icon="feather:search" fullwidth class="prime-auto ">
                  <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-3 mt-5">
              <VIconButton type="button" color="success" circle raised icon="fas fa-search" class="mt-1"
                @click="fetchData()" :loading="isLoading">
              </VIconButton>
            </div>
            <div class="column is-12 mt-5-min">
              <VCard class="card-round-1">
                <DataTable tableStyle="min-width: 50rem" :class="`p-datatable-small`" :value="dataSource"
                  v-model:filters="filtersTrans"
                  :globalFilterFields="['tglresep', 'tglresep', 'ruanganapotik', 'noresep', 'nocm', 'namapasien']"
                  :rowsPerPageOptions="[5, 10, 25, 100]" :rows="10" paginator :loading="isLoading">
                  <template #header>
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="info" outlined circle raised
                          v-tooltip-prime="'Export'" @click="exportExcel()">
                          Export Excel
                        </VButton>
                      </div>
                      <div class="column is-3 is-offset-3">
                      </div>
                      <div class="column is-3 ">
                        <VField>
                          <VControl icon="feather:search">
                            <input v-model="filtersTrans['global'].value" v-on:keyup.enter="fetchData()" type="text"
                              class="input is-rounded" placeholder="Search" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </template>
                  <Column field="no" header="No" style="min-width: 20px;" />
                  <Column field="tglresep" header="Tanggal" style="min-width: 50px;" />
                  <Column field="ruanganapotik" header="Depo Farmasi" style="min-width: 120px;" />
                  <Column field="noresep" header="No Resep" style="min-width: 60px;" />
                  <Column field="nocm" header="No. RM" style="min-width: 50px;" />
                  <Column field="namapasien" header="Nama Pasien" style="min-width: 120px;" />
                  <Column field="tunai" header="Tunai" style="min-width: 120px;" />
                  <Column field="penjamin" header="Penjamin" style="min-width: 50px;" />
                  <Column :exportable="false" header="Status Pembayaran" style="width:70px">
                   <template #body="slotProps">
                    <VTag class="mr-1 mb-1" :color="slotProps.data.status_bayar == 'BELUM BAYAR' ? 'danger' : 'success'" :label="slotProps.data.status_bayar" />
                   </template>
                  </Column>
                </DataTable>
              </VCard>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import * as H from '/@src/utils/appHelper'
import moment from 'moment'
import AutoComplete from 'primevue/autocomplete'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ProgressBar from 'primevue/progressbar';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import { FilterMatchMode } from 'primevue/api';
import * as XLSX from "xlsx"
import * as XLSXStyle from 'xlsx-js-style'
let title = "Laporan Penjualan";
useHead({
  title: title + "-" + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)


const api = useApi()
const item: any = reactive({
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  })
})
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const d_Dokter: any = ref([])
const d_KelompokPasien: any = ref([])
const d_Ruangan: any = ref([])
const dataSource: any = ref([])
const isLoading: any = ref(false)


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchKelompokPasien = async (filter: any) => {
  await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const fetchData = async () => {
  let tglAwal = moment(item.filterTgl.start).format('YYYY-MM-DD 00:00:00')
  let tglAkhir = moment(item.filterTgl.end).format('YYYY-MM-DD 23:59:59')
  let dokter = item.dokter ? item.dokter.value : ''
  let kelompokpasien = item.kelompokpasien ? item.dokter.value : ''
  let ruangan = item.ruangan ? item.ruangan.value : ''
  isLoading.value = true
  await useApi().get(`farmasi/laporan/penjualan?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}&dokid=${dokter}&kpid=${kelompokpasien}&ruid=${ruangan}`).then((response) => {
    response.daftar.map((element: any, index: number) => {
      element.no = index + 1
      element.tunai = H.formatRp(element.tunai, ""),
        element.penjamin = H.formatRp(element.penjamin, "")
    })
    dataSource.value = response.daftar
    isLoading.value = false
  })
}
const exportExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Laporan Penjulan'],
    [],
    ['NO', 'Tanggal', 'Depo Farmasi', 'No Resep', 'No. RM', 'Nama Pasien', 'Tunai', 'Penjamin'],
    ...dataSource.value.map((e: any, index: number) => [
      index + 1,
      e.tglresep,
      e.ruanganapotik,
      e.noresep,
      e.nocm,
      e.namapasien,
      e.tunai,
      e.penjamin,
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
  const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
    worksheet[headerCell].s = headerStyle;
  }

  // Setting column widths
  const columnWidths = [5, 10, 20, 30, 20, 10, 50, 20, 20];

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
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 7 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Penjualan', true);

  XLSXStyle.writeFile(workbook, 'Laporan Penjualan.xlsx');
}
fetchData();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

.search-widget {
  flex: 1;
  display: inline-block;
  width: 100%;
  padding: 12px;
  background-color: var(--white);
  border-radius: 16px;
  border: 1px solid var(--fade-grey-dark-3);
  transition: all 0.3s;
}
</style>
