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
            <div class="column is-2">

            </div>
            <div class="column is-1">
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Produk</VLabel>
                <VControl icon="feather:search">
                  <AutoComplete v-model="item.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                    :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-rounded" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" placeholder="ketik untuk mencari..."
                    @item-select="changeProduk(item.produk)" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3 is-pulled-right">
              <VField label="Periode">
                <VControl class="prime-auto">
                  <Calendar inputId="range" v-model="item.qFilterTgl" selectionMode="range" :manualInput="false"
                    class="w-100 mb-4 " :showIcon="true" date-format="dd-mm-yy" />
                </VControl>
              </VField>

            </div>
            <div class="column is-2 mt-5 ">
              <VIconButton type="button" color="success" circle raised icon="fas fa-search" @click="fetchData()"
                :loading="isLoading" v-tooltip-prime="'Cari '">
              </VIconButton>
              <VIconButton type="button" color="warning" circle raised icon="fas fa-plus" @click="order()" class="ml-3"
                v-tooltip-prime.top="'Tambah '">
              </VIconButton>
            </div>
            <div class="column is-12 mt-5-min">
              <VCard class="card-round-1">

                <DataTable v-model:filters="filtersTrans" :value="dataSource" paginator :rows="10" filterDisplay="row"
                  :rowsPerPageOptions="[5, 10, 25, 100]" :globalFilterFields="['kelompok']"
                  :class="`p-datatable-small`">
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
                  <template #empty> No data found. </template>
                  <Column field="no" style="width: 50px;" header="No"></Column>
                  <Column field="status" style="width: 100px;" header="Status"></Column>
                  <Column field="tglorder" style="width: 60px;" header="Tgl Order"></Column>
                  <Column field="noorder" style="width: 100px;" header="No Order"></Column>
                  <Column field="jeniskirim" style="width: 80px;" header="Jenis Order"></Column>
                  <Column field="jmlitem" style="width: 35px;" header="Item"></Column>
                  <Column field="namaruanganasal" style="width: 100px;" header="Nama Ruangan Asal"></Column>
                  <Column field="namaruangantujuan" style="width: 120px;" header="Nama Ruangan Tujuan"></Column>
                  <Column field="petugas" style="width: 100px;" header="Petugas"></Column>
                  <Column field="keterangan" style="width: 100px;" header="Keterangan"></Column>
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
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as XLSX from "xlsx"
import * as XLSXStyle from 'xlsx-js-style'
import AutoComplete from 'primevue/autocomplete'
import Dropdown from 'primevue/dropdown'
import Calendar from 'primevue/calendar'
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
const title = "Daftar Permintaan Alat Steril"
useHead({
  title: 'Daftar Permintaan Alat Steril' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = reactive({
  qFilterTgl: [
    new Date(),
    new Date()
  ],
})
const router = useRouter()
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const dataSource: any = ref([])
const isLoading: any = ref(false)
const d_produk: any = ref([])
const fetchData = async () => {
  isLoading.value = true
  let tglAwal = moment(item.qFilterTgl[0]).format('YYYY-MM-DD 00:00:00')
  let tglAkhir = moment(item.qFilterTgl[1]).format('YYYY-MM-DD 23:59:59')
  const response = await useApi().get(`/stelilisasi/data-orderalatsteril?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`);
  response.daftar.forEach((element: any, index: number) => {
    element.no = index + 1;
  });;
  isLoading.value = false
  dataSource.value = response.daftar
}
const exportExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Daftar Permintaan Alat Steril'],
    [],
    ['NO', 'STATUS ORDER', 'TANGGAL ORDER', 'NO ORDER', 'JENIS ORDER', 'ITEM', 'NAMA RUANGAN ASAL', 'NAMA RUANGAN TUJUAN', 'PETUGAS', 'KETERANGAN'],
    ...dataSource.value.map((e: any, index: number) => [
      index + 1,
      e.status,
      e.tglorder,
      e.noorder,
      e.jeniskirim,
      e.jmlitem,
      e.namaruanganasal,
      e.namaruangantujuan,
      e.petugas,
      e.keterangan,
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
  const columnWidths = [5, 20, 20, 15, 10, 20, 20, 20, 20, 25, 25, 30];

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
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 9 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Daftar Permintaan Alat Steril', true);

  XLSXStyle.writeFile(workbook, 'Daftar Permintaan Alat Steril.xlsx');
}
const fetchProduk = async (filter: any) => {
  useApi().get(`stelilisasi/get-produk?namaproduk=${filter.query}&limit=10`).then((response: any) => {
    d_produk.value = response
  })
}

const order = () => {
  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.push({
    name: 'module-sterilisasi-order-barang-steril',
  })
}
fetchData();
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
</style>
