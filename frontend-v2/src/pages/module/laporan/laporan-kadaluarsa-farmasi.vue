<template>
  <section>
      <div class="column is-12">
          <VCard>
              <div class="column c-title pt-2 mb-0">
                  <div class="column is-10 p-0">
                      <label class="title-page">Lap. Persediaan yang Akan Kedaluwarsa Ins. Farmasi</label>
                  </div>
              </div>

              <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
              <DataTable v-else :rows="5" :value="enhancedDataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                  class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" groupRowsBy="group"
                  rowGroupMode="subheader">
                  <template #header>
            <div class="columns is-multiline pb-3">
              <div class="column is-2" style="padding-top:2rem">
                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                  Export To Excel
                </VButton>
              </div>

              <div class="column is-10">
                <div class="columns is-multiline" style="justify-content: flex-end;">
                  <div class="column is-3 pb-0">
                  </div>

                  <div class="column is-3 pb-0">
                   <VField label="Cari Nama Produk">
                        <VInput v-model="item.namaproduk" placeholder="Cari Produk" v-on:keyup.enter="fetchData()" style="width:300px" />
                    </VField>
                  </div>

                  <div class="column is-3 pb-0">
                    <VField label="Jenis Produk" style="margin-bottom: 6px;" />
                      <VField>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="item.status"
                                :suggestions="d_Status" :optionLabel="'label'"
                                @complete="fetchStatus($event)" :dropdown="true"
                                :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Status..."/>
                        </VControl>
                       </VField>
                  </div>
                  
                  <div class="column is-1 mt-5">
                    <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData" :loading="isPlaceLoad" />
                  </div>
                </div>
              </div>
            </div>
          </template>

                  <ColumnGroup type="header">
                    <Row>
                        <Column :headerStyle="{ width: '3em' }" :rowspan="2">
                          <template #header>
                            <input type="checkbox" :checked="allSelected" @change="toggleSelectAll" />
                          </template>
                        </Column>
                          <Column header="#" style="width: 25px; text-align: center;" :rowspan="2" />
                          <Column header="Kode Produk" :sortable="true" style="text-align: center;" :rowspan="2" />
                          <Column header="Nama Produk" style="min-width:200px; text-align: center" :rowspan="2" />
                          <Column header="Satuan" style="text-align: center" :rowspan="2" />
                          <Column header="Harga Satuan" style="text-align: center" :rowspan="2" />
                          <Column header="Qty Produk" style="text-align: center;"
                              :colspan="6" />
                          <Column header="Total Stok" style="min-width: 100px;text-align: center" :rowspan="2" />
                          <Column header="Total Harga" style="min-width: 100px;text-align: center" :rowspan="2" />
                          <Column header="Tanggal Kadaluarsa" style="width: 100px;text-align: center" :rowspan="2" />
                      </Row>
                      <Row>
                          <Column header="Gudang Farmasi" style="width:100px; text-align: center;" />
                          <Column header="Satelit Farmasi Sentral" style="width:150px; text-align: center;" />
                          <Column header="Satelit Farmasi Onkologi" style="width:150px; text-align: center;" />
                          <Column header="Satelit Farmasi IRIT" style="width:100px; text-align: center;" />
                          <Column header="Satelit Farmasi IBSA" style="width:100px; text-align: center;" />
                          <Column header="Satelit Farmasi Rawat Inap" style="width:140px; text-align: center;" />
                      </Row>
                  </ColumnGroup>
                  <Column>
                    <template #body="slotProps">
                          <input type="checkbox" v-model="slotProps.data.selected" />
                        </template>
                      </Column>
                  <Column field="no" style="min-width: 10px; text-align:center" />
                  <Column field="kdproduk" style="min-width: 80px;" />
                  <Column field="namaproduk" style="min-width: 50px;" />
                  <Column field="satuanstandar"/>
                  <Column field="harganetto1"/>
                  <Column field="gudfar"/>
                  <Column field="sentral"/>
                  <Column field="onko"/>
                  <Column field="intensif"/>
                  <Column field="bedsen"/>
                  <Column field="ranap"/>
                  <Column field="totalstok"/>
                  <Column field="totalharga"/>
                  <Column field="tglkadaluarsa"/>
              </DataTable>
              <strong>Total Qty:</strong> {{ totalQty }}
          <br>
          <strong>Total Keseluruhan Harga:</strong> {{ H.formatRupiah(totalHarga) }}
          </VCard>
      </div>
  </section>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import ColumnGroup from 'primevue/columngroup';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment from 'moment'

// app.directive('tooltip', Tooltip);

useHead({
  title: 'Lap. Persediaan yang Akan Kedaluwarsa Ins. Farmasi - ' + import.meta.env.VITE_PROJECT,
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
let d_Status: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(false)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchStatus = async (filter) => {
  const StatusOptions = [
  { label: "OBAT", value: "O" },
  { label: "BMHP", value: "B" },
  { label: "AMHP", value: "A" },
];

  d_Status.value = StatusOptions;
};
// Fungsi untuk toggle "Select All"
const toggleSelectAll = () => {
  console.log('masuk fungsi',event);
  console.log('datasource',enhancedDataSource);
  allSelected.value = !allSelected.value;
};
const fetchData = async () => {
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
    let ruangan = item.value.ruangan ? `&ruangan=${item.value.ruangan.value}` : ''
    let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
    let nama = item.value.namaproduk ? `&nama=${item.value.namaproduk}` : ''
    let status = item.value.status ? `&status=${item.value.status.value}` : ''
  
  loadSearch.value = true
  await useApi().get(`pelayanan/get-laporan-kadaluarsa-obat?${tglAwal}${tglAkhir}${ruangan}${nama}${status}`).then((response: any) => {
  response.data.forEach((element:any,i:any) => {
          element.no = i + 1
      });
      dataSource.value = response.data
  })
  loadData.value = false
  loadSearch.value = false
}

const enhancedDataSource = computed(() => {
  return dataSource.value
    .map(item => {
      const totalstok = Math.round(
        parseFloat(item.intensif) +
        parseFloat(item.bedsen) +
        parseFloat(item.ranap) +
        parseFloat(item.gudfar) +
        parseFloat(item.onko) +
        parseFloat(item.sentral)
      );

      if (totalstok === 0) return null; // Jika totalstok 0, return null untuk di-filter nanti

      const totalharga = Math.round(parseFloat(item.harganetto1) * totalstok);

      return {
        ...item,
        tglkadaluarsa: H.formatDateNoTime(item.tglkadaluarsa),
        harganetto1: H.formatRupiah(Math.ceil(item.harganetto1),'Rp'),
        totalstok: totalstok,
        totalharga: H.formatRupiah(Math.ceil(totalharga),'Rp')
      };
    })
    .filter(item => item !== null); // Filter item yang totalstok-nya 0
});

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}

const fetchRuanganAsal = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_RuanganAsal.value = response
  })
}
const calculateTotalQty = () => {
  return dataSource.value
    .filter(row => row.selected)
    .reduce((total, row) => total + (parseFloat(row.intensif)+parseFloat(row.bedsen)+parseFloat(row.ranap)+parseFloat(row.gudfar)+parseFloat(row.onko)+parseFloat(row.sentral) || 0), 0);
};


const calculateTotalHarga = () => {
  return dataSource.value
    .filter(row => row.selected)
    .reduce((total, row) => total + ((parseFloat(row.intensif)+parseFloat(row.bedsen)+parseFloat(row.ranap)+parseFloat(row.gudfar)+parseFloat(row.onko)+parseFloat(row.sentral)) * parseFloat(row.harganetto1) || 0), 0);
};

const totalQty = ref(calculateTotalQty());
const totalHarga = ref(calculateTotalHarga());

watch(dataSource, () => {
  totalQty.value = calculateTotalQty();
  totalHarga.value = calculateTotalHarga();
}, { deep: true });

const allSelected = computed({
  get: () => dataSource.value.every(row => row.selected),
  set: (value) => {
    dataSource.value.forEach(row => {
      row.selected = value;
    });
  },
  
});

const exportExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
      ['Laporan Sensus Rawat Inap'],
      [],
      ['NO', 'JENIS PELAYANAN', 'PASIEN AWAL TAHUN', 'TAHUN', 'BOR', 'LOS', 'BTO', 'TOI', 'NDR', 'GDR', 'RATA-RATA KUNJUNGAN / HARI'],
      ...dataSource.value.map((e: any) => [
          e.koders,
          e.kodeprov,
          e.kota,
          e.tahun,
          e.bor,
          e.alos,
          e.bto,
          e.toi,
          e.ndr,
          e.gdr,
          e.ratarataperhari,
      ]),
  ]);
  // Mendefinisikan style untuk header(centered)
  const headerStyle = {
      alignment: {
          horizontal: 'center',
          vertical: 'center'
      },
      font: {
          color: { rgb: 'FFFFFF' }
      },
      fill: { fgColor: { rgb: '807C7C' } }
  };
  // Mendefinisikan range header
  const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
      const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
      worksheet[headerCell].s = headerStyle;
  }

  const columnWidths = [10, 10, 15, 10, 10, 10, 10, 10, 10, 10, 18];

  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
      worksheet['!cols'] = worksheet['!cols'] || [];
      worksheet['!cols'][col] = { wch: columnWidths[col] };
  }

  // Centering the text in cell A1
  const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
  worksheet[titleCell].s = {
      alignment: {
          horizontal: 'center',
          vertical: 'center'
      },
      font: {
          bold: true,
          sz: 18
      }
  };

  // Menggabungkan dua baris pertama
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 10 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Sensus', true);
  XLSXStyle.writeFile(workbook, 'Laporan Sensus Rawat Inap.xlsx');
}

// fetchData()

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
}</style>
