<template>
  <section>
      <div class="column is-12">
          <VCard>
              <div class="column c-title pt-2 mb-0">
                  <div class="column is-10 p-0">
                      <label class="title-page">Laporan Sensus Rawat Inap</label>
                      <label for="">SENSUS RAWAT INAP</label>
                  </div>
              </div>

              <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
              <DataTable v-else :rows="5" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                  class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" groupRowsBy="group"
                  rowGroupMode="subheader">
                  <template #header>
                      <div class="columns is-multiline pb-3" style="justify-content: space-between;">
                          <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel"
                              class="mt-5 ml-3">
                              Export To Excel
                          </VButton>
                          <div class="column is-2">
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
                          <div class="column is-2 pb-0" style="margin-top: -7px;">
                              <VField label="Ruangan">
                                  <VControl class="prime-auto">
                                      <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" :optionLabel="'label'"
                                      @complete="fetchRuangan($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan..." class="mt-2" />
                                  </VControl>
                              </VField>
                          </div>
                          <div class="column is-2 pb-0" style="margin-top: -7px;">
                              <VField label="Ruangan Asal">
                                  <VControl class="prime-auto">
                                      <AutoComplete v-model="item.ruanganasal" :suggestions="d_RuanganAsal" :optionLabel="'label'"
                                      @complete="fetchRuanganAsal($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan Asal..." class="mt-2" />
                                  </VControl>
                              </VField>
                          </div>
                          <div class="column is-1 mt-5">
                            <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchData"
                              :loading="isPlaceLoad" />
                          </div>
                      </div>
                  </template>
                  <ColumnGroup type="header">
                      <Row>
                          <Column header="Tanggal" style="min-width: 80px; text-align: center;" :rowspan="2" />
                          <Column header="PASIEN AWAL" style="text-align: center;" :rowspan="2" />
                          <Column header="PASIEN MASUK" style="text-align: center" :rowspan="2" />
                          <Column header="PASIEN PINDAHAN" style="text-align: center" :rowspan="2" />
                          <Column header="Jumlah (2+3+4)" style="text-align: center" :rowspan="2" />
                          <Column header="PASIEN KELUAR HIDUP" style="text-align: center;"
                              :colspan="5" />
                          <Column header="Pasien Mati (12+13)" style="text-align: center" :rowspan="2" />
                          <Column header="PASIEN MATI" style="text-align: center;"
                              :colspan="2" />
                          <Column header="Jumlah (6+7+8+9+10+11)" style="text-align: center" :rowspan="2" />
                          <Column header="JUMLAH YANG MASIH DIRAWAT" style="text-align: center;"
                              :rowspan="2" />
                          <Column header="Pasien Masuk & Keluar Pada Hari Yang Sama" style="text-align: center;"
                              :rowspan="2" />
                          <Column header="Lama Dirawat" style="text-align: center" :rowspan="2" />
                          <Column header="RINCIAN PERKELAS" style="text-align: center;"
                              :colspan="7" />
                      </Row>
                      <Row>
                          <Column header="Dipindahkan" style="text-align: center;" />
                          <Column header="Pulang" style="text-align: center;" />
                          <Column header="Dirujuk" style="text-align: center;" />
                          <Column header="Pindah Ke Rs Lain" style="text-align: center;" />
                          <Column header="APS" style="text-align: center;" />
                          <Column header="< 48 JAM" style="text-align: center;" />
                          <Column header="> 48 JAM" style="text-align: center;" />
                          <Column header="Kls Suite" style="text-align: center;" />
                          <Column header="Kls VVIP" style="text-align: center;" />
                          <Column header="Kls VIP" style="text-align: center;" />
                          <Column header="Kls I" style="text-align: center;" />
                          <Column header="Kls II" style="text-align: center;" />
                          <Column header="Kls III" style="text-align: center;" />
                          <Column header="Tnp Kls" style="text-align: center;" />
                      </Row>
                  </ColumnGroup>
                  <Column field="tanggal" style="min-width: 10px; text-align:center" />
                  <Column field="banyaknyapasiendiharisebelumnya" style="min-width: 80px;" />
                  <Column field="banyakpasienmasuk" style="min-width: 50px;" />
                  <Column field="banyaknyapasienpindahan"/>
                  <Column field="total_234"/>
                  <Column field="banyakpasiendipindahkan"/>
                  <Column field="banyakpasienpulang"/>
                  <Column field="banyakpasiendirujuk"/>
                  <Column field="banyakpasienpindahrslain"/>
                  <Column field="banyakpasienaps"/>
                  <Column field="banyaknyapasienmeninggal"/>
                  <Column field="pasienmeninggalkurangdari48jam"/>
                  <Column field="pasienmeninggallebihdari48jam"/>
                  <Column field="jumlah67891011"/>
                  <Column field="totalpasienyangmasihdirawat"/>
                  <Column field="keluarmasukharisama"/>
                  <Column field="totallamadirawat"/>
                  <Column field="suite"/>
                  <Column field="vvip"/>
                  <Column field="vip"/>
                  <Column field="kelas1"/>
                  <Column field="kelas2"/>
                  <Column field="kelas3"/>
                  <Column field="nonkelas"/>
              </DataTable>
          </VCard>
      </div>
  </section>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
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
  title: 'Laporan Sensus Rawat Inap - ' + import.meta.env.VITE_PROJECT,
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
let d_Ruangan: any = ref([])
let d_RuanganAsal: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(false)
let loadSave: any = ref(false)
let isLoading: any = ref(false)


const fetchData = async () => {
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfk = item.value.ruangan ? `&ruanganId=${item.value.ruangan.value}` : ''
  let ruanganasalfk = item.value.ruanganasal ? `&ruanganasalId=${item.value.ruanganasal.value}` : ''
  
  loadSearch.value = true
  await useApi().get(`laporan/get-laporan-sensus-ranap?${tglAwal}${tglAkhir}${ruanganfk}${ruanganasalfk}`).then((response) => {
      response.data.forEach((element:any,i:any) => {
          element.no = i + 1
      });
      dataSource.value = response.data
  })
  loadData.value = false
  loadSearch.value = false
}

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
