<template>
  <ConfirmDialog />
  <div class="columns is-multiline">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title-x">
        <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
      </div>
      <div class="column is-12">
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
            <div class="column is-3">
              <VField class="is-rounded-select_Z  is-autocomplete-select" label="Dokter">
                <VControl icon="feather:search" fullwidth class="prime-auto ">
                  <AutoComplete v-model="item.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
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
            <!-- <div class="column is-2">
              <VControl>
                <VSwitchBlock v-model="item.isRawatInap" label="Rawat Inap" color="danger"
                  @change="changeSwitch(item.isRawatInap)" />
              </VControl>
            </div> -->
            <div class="column is-1 mt-5">
              <VIconButton type="button" color="success" circle raised icon="fas fa-search" @click="fetchData()"
                :loading="isLoading">
              </VIconButton>
            </div>
            <div class="column is-12 mt-5-min">
              <VCard class="card-round-1">
                <DataTable tableStyle="min-width: 50rem" :class="`p-datatable-small`" :value="dataSource"
                  v-model:filters="filtersTrans" :globalFilterFields="['KodePerkiraan']"
                  :rowsPerPageOptions="[5, 10, 25, 100]" :rows="10" paginator  :loading="isLoading">
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
                  <template #empty> No data found. </template>
                    <ColumnGroup type="header">
                    <Row>
                      <Column header="Tanggal" style="min-width: 100px" rowspan="2" />
                      <Column header="Gudang" style="min-width: 120px" rowspan="2" />
                      <Column header="Departemen" style="min-width: 120px" rowspan="2" />
                      <Column header="Ruangan Pengorder" style="min-width: 120px" rowspan="2" />
                      <Column header="Ruangan Terakhir" style="min-width: 120px" rowspan="2" />
                      <Column header="Status Rawat Pasien" style="min-width: 120px" rowspan="2" />
                      <Column header="Jenis Produk" style="min-width: 120px" rowspan="2" />
                      <Column header="Nama Item" style="min-width: 120px" rowspan="2" />
                      <Column header="Satuan" style="min-width: 120px" rowspan="2" />
                      <Column header="Jumlah" style="min-width: 80px" rowspan="2" />
                      <Column header="HPP" style="min-width: 80px" rowspan="2" />
                      <Column header="Harga Satuan" style="min-width: 80px" rowspan="2" />
                      <Column header="Harga" style="min-width: 80px" rowspan="2" />
                      <Column header="Diskon" style="min-width: 80px" rowspan="2" />
                      <Column header="Sub Total" style="min-width: 80px" rowspan="2" />
                      <Column header="Pasien" style="min-width: 80px" colspan="5" />
                      <Column header="Dokter" style="min-width: 80px" colspan="2" />
                      <Column header="Jenis Obat" style="min-width: 80px" colspan="2" />
                    </Row>
                    <Row>
                      <Column header="Noregistrasi" style="min-width: 100px" />
                      <Column header="No. RM" style="min-width: 100px" />
                      <Column header="Nama Pasien" style="min-width: 120px" />
                      <Column header="Tipe Pasien" style="min-width: 150px" />
                      <Column header="Penjamin" style="min-width: 180px" />
                      <Column header="Nama" style="min-width: 100px" />
                      <Column header="No. Resep" style="min-width: 100px" />
                      <Column header="Fornas" style="min-width: 80px" />
                      <Column header="Generik" style="min-width: 80px" />
                    </Row>
                  </ColumnGroup>
                      <Column field="tglresep" />
                      <Column field="gudang" />
                      <Column field="departemen" />
                      <Column field="ruanganpengorder" />
                      <Column field="ruanganterakhir" />
                      <Column field="statrawat" />
                      <Column field="detailjenisproduk" />
                      <Column field="namaproduk" />
                      <Column field="satuanstandar" />
                      <Column field="jumlah" />
                      <Column field="hpp" />
                      <Column field="hna" />
                      <Column field="hargajual" />
                      <Column field="hargadiscount" />
                      <Column field="subtotal" />
                      <Column field="noregistrasi" />
                      <Column field="nocm" />
                      <Column field="namapasien" />
                      <Column field="kelompokpasien" />
                      <Column field="namarekanan" />
                      <Column field="dokter" />
                      <Column field="noresep" />
                      <Column field="fornas" />
                      <Column field="generic" />
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
let title = "Laporan Penjualan Obat Detail";
useHead({
  title: title + "-" + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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
const columnTrans: any = [
  {
    "field": "tglresep",
    "title": "Tanggal",
    "width": "100px",
  },
  {
    "field": "gudang",
    "title": "Gudang",
    "width": "120px",
  },
  {
    "field": "departemen",
    "title": "Departemen",
    "width": "120px",
  },
  {
    "field": "ruanganpengorder",
    "title": "Ruangan Pengorder",
    "width": "120px",
  },
  {
    "field": "ruanganterakhir",
    "title": "Ruangan Terakhir",
    "width": "120px",
  },
  {
    "field": "statrawat",
    "title": "Status Rawat Pasien",
    "width": "120px",
  },
  {
    "field": "detailjenisproduk",
    "title": "Jenis Produk",
    "width": "120px",
  },
  {
    "field": "namaproduk",
    "title": "Nama Item",
    "width": "120px",
  },
  {
   "field": "satuanstandar",
    "title": "Satuan",
    "width": "120px",
  },
  {
  "field": "jumlah",
  "title": "Jumlah",
  "width": "100px",
  },
  {
    "field": "hargasatuank",
    "title": "Kredit",
    "width": "100px",
    "template": "<span class='style-right'>{{formatRupiah('#: hargasatuank #', '')}}</span>"
  }
];
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
  let tglAwal           = moment(item.filterTgl.start).format('YYYY-MM-DD 00:00:00')
  let tglAkhir          = moment(item.filterTgl.end).format('YYYY-MM-DD 23:59:59')
  let dokter            = item.dokter ? item.dokter.value :''
  let kelompokpasien    = item.kelompokpasien ? item.dokter.value :''
  let ruangan           = item.ruangan ? item.ruangan.value :''
  let ranap             = item.isRawatInap && item.isRawatInap.value ? true :''
  let rajal             = item.isRawatInap && item.isRawatInap.value ? false :''
  isLoading.value = true;
  await useApi().get(`farmasi/laporan/detail-penjualan?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}&dokid=${dokter}&kpid=${kelompokpasien}&ruid=${ruangan}&isRanap=${ranap}&isRajal=${rajal}`).then((response:any)=>{
    response.daftar.map((element :any ,index:number)=>{
      if(element.isgeneric == undefined || element.isgeneric == false){
        element.generic = "✘"
      }else{
        element.generic = "✔"
      }
      element.hargajual = H.formatRp(element.hargajual,"Rp")
      element.subtotal = H.formatRp(element.subtotal,"Rp")
      element.hna = H.formatRp(element.hna,"Rp")
      element.hargadiscount = H.formatRp(element.hargadiscount,"Rp")
    })
    dataSource.value = response.daftar
  })
  isLoading.value = false;
}
fetchData();
const changeSwitch = (e: any) => {
}
const exportExcel = () => {
   const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Laporan Pengeluaran Obat'],
    [],
    ['Tanggal', 'Gudang', 'Departemen', 'Ruangan Pengorder', 'Ruangan Terakhir', 'Status Rawat Pasien', 'Jenis Produk', 'Nama Item', 'Satuan' ,'Jumlah' ,'HPP','Harga Satuan' ,'Harga','Discount','Sub Total','Pasien','','','','','Dokter','','Jenis Obat',''],
    ['' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'','' ,'','','','Noregistrasi' ,'No. RM' ,'Nama Pasien','Tipe Pasien','Penjamin' ,'Nama','No. Resep','Fornas','Generik'],
    ...dataSource.value.map((e :any, index : number) => [
      e.tglresep,
      e.gudang,
      e.departemen,
      e.ruanganpengorder,
      e.ruanganterakhir,
      e.statrawat,
      e.detailjenisproduk,
      e.namaproduk,
      e.satuanstandar,
      e.jumlah,
      e.hpp,
      e.hna,
      e.hargajual,
      e.hargadiscount,
      e.subtotal,
      e.noregistrasi,
      e.nocm,
      e.namapasien,
      e.kelompokpasien,
      e.namarekanan,
      e.dokter,
      e.noresep,
      e.fornas,
      e.generic,
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
    border: {
      top: { style: 'thin', color: { rgb: 'FFFFFF' } },
      bottom: { style: 'thin', color: { rgb: 'FFFFFF' } },
      left: { style: 'thin', color: { rgb: 'FFFFFF' } },
      right: { style: 'thin', color: { rgb: 'FFFFFF' } },
    },
  };

  // Applying header style
  const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
    worksheet[headerCell].s = headerStyle;
  }

  // Setting column widths
  const columnWidths = [10, 40, 30, 30, 30, 30, 20, 40, 30,30, 30,30, 30, 30, 30, 30, 30, 30,20,30,25,20,10,10];

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

  const row2Style = {
    font: {
      color: { rgb: 'FFFFFF' },
      bold: true,
    },
    fill: { fgColor: { rgb: '807C7C' } },
    border: {
      top: { style: 'thin', color: { rgb: 'FFFFFF' } },
      bottom: { style: 'thin', color: { rgb: 'FFFFFF' } },
      left: { style: 'thin', color: { rgb: 'FFFFFF' } },
      right: { style: 'thin', color: { rgb: 'FFFFFF' } },
    },
  };

  const row2Range = XLSX.utils.decode_range(worksheet['!ref']);
  for (let col = row2Range.s.c; col <= row2Range.e.c; col++) {
    const cell = XLSX.utils.encode_cell({ r: 3, c: col });
    worksheet[cell].s = row2Style;
  }
  // Merging the first two rows
  const merges = [
    { s: { r: 0, c: 0 }, e: { r: 1, c: 22 } },

    { s: { r: 2, c: 0 }, e: { r: 3, c: 0 } },
    { s: { r: 2, c: 1 }, e: { r: 3, c: 1 } },
    { s: { r: 2, c: 2 }, e: { r: 3, c: 2 } },
    { s: { r: 2, c: 3 }, e: { r: 3, c: 3 } },
    { s: { r: 2, c: 4 }, e: { r: 3, c: 4 } },
    { s: { r: 2, c: 5 }, e: { r: 3, c: 5 } },
    { s: { r: 2, c: 6 }, e: { r: 3, c: 6 } },
    { s: { r: 2, c: 7 }, e: { r: 3, c: 7 } },
    { s: { r: 2, c: 8 }, e: { r: 3, c: 8 } },
    { s: { r: 2, c: 9 }, e: { r: 3, c: 9 } },
    { s: { r: 2, c: 10 }, e: { r: 3, c: 10 } },
    { s: { r: 2, c: 11 }, e: { r: 3, c: 11 } },
    { s: { r: 2, c: 12 }, e: { r: 3, c: 12 } },
    { s: { r: 2, c: 13 }, e: { r: 3, c: 13 } },
    { s: { r: 2, c: 14 }, e: { r: 3, c: 14 } },
    // { s: { r: 2, c: 13 }, e: { r: 3, c: 13 } },

    { s: { r: 2, c: 15 }, e: { r: 2, c: 19 } },
    { s: { r: 2, c: 20 }, e: { r: 2, c: 21 } },
    { s: { r: 2, c: 22 }, e: { r: 2, c: 23 } }
  ];
  worksheet['!merges'] = merges;

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Pengeluaran Obat', true);
  XLSXStyle.writeFile(workbook, 'Laporan Pengeluaran Obat.xlsx');
}
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
