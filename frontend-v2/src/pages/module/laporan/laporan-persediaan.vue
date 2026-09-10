<template>
  <div class="columns is-multiline">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Laporan Persediaan</label>
      </div>
      <div class="column is-12">
        <div class="columns  is-multiline">
          <div class="column is-1">
            <VField label="Tahun">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.tahun" placeholder="Tahun" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 mt-5">
            <VRadio v-model="item.findBy" value="0" label="Semester 1" name="outlined_radio" color="info" />
            <VRadio v-model="item.findBy" value="1" label="Semester 2" name="outlined_radio" color="primary" />
            <VRadio v-model="item.findBy" value="2" label="Semester 3" name="outlined_radio" color="danger" />
          </div>
          <div class="column is-3">
            <VField label="Produk">
              <VControl icon="feather:database">
                <VInput type="text" v-model="item.nmProduk" placeholder="Produk" v-on:keyup.enter="fetchData()" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField label="Bulan Awal">
              <Calendar v-model="item.bulan" selectionMode="single" :manualInput="false" class="w-100" :showIcon="true"
                :showTime="false" view="month" :date-format="'MM'" />
            </VField>
          </div>
          <div class="column is-2">
            <VField label="Bulan Akhir">
              <Calendar v-model="item.bulanAkhir" selectionMode="single" :manualInput="false" class="w-100"
                :showIcon="true" :showTime="false" view="month" :date-format="'MM'" />
            </VField>
          </div>
          <div class="column btn-search">
            <VIconButton type="button" icon="feather:search" class="is-rounded" :loading="isLoading" @click="fetchData()"
              color="success">

            </VIconButton>
            <VIconButton type="button" icon="feather:filter" class="is-rounded ml-2" @click="showFilter = !showFilter"
              color="info">

            </VIconButton>
          </div>
          <div class="column is-3" v-show="showFilter">
            <VField label="Jenis Produk">
              <VControl icon="feather:search">
                <MultiSelect v-model="item.jenisProduk" display="chip" :options="d_JenisProduk" optionLabel="jenisproduk"
                  filter placeholder="Jenis Produk" :maxSelectedLabels="3" style="display:flex" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4" v-show="showFilter">
            <VField label="Detail Jenis Produk">
              <VControl icon="feather:search">
                <MultiSelect v-model="item.djenisProduk" display="chip" :options="d_DetailJenisProduk"
                  optionLabel="detailjenisproduk" filter placeholder="Detail Jenis Produk" :maxSelectedLabels="3"
                  style="display:flex" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2" v-show="showFilter">
            <VField label="Rows">
              <VControl icon="feather:layers">
                <VInput type="text" v-model="item.rows" placeholder="Rows" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2" v-show="showFilter">
            <VField v-slot="{ id }" class="is-icon-select" label="View">
              <VControl>
                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name" :options="d_View"
                  :searchable="true" track-by="name" mode="single" @select="changeView(selectView)" autocomplete="off">
                  <template #singlelabel="{ value }">
                    <div class="multiselect-single-label">
                      <div class="select-label-icon-wrap">
                        <i :class="value.icon"></i>
                      </div>
                      <span class="select-label-text">
                        {{ value.name }}
                      </span>
                    </div>
                  </template>
                  <template #option="{ option }">
                    <div class="select-option-icon-wrap">
                      <i :class="option.icon"></i>
                    </div>
                    <span class="select-option-text">
                      {{ option.name }}
                    </span>
                  </template>
                </Multiselect>
              </VControl>
            </VField>
          </div>
        </div>
      </div>


      <div class="column is-12 ">
        <div class="column" v-if="isLoading">
          <VPlaceloadWrap v-for="data in 10">
            <VPlaceload class="mx-2 mb-3" />
          </VPlaceloadWrap>
        </div>
        <div class="column" v-else>
          <!-- <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan."
            subtitle="Silakan gunakan filter lain" larger>
            <template #image>
              <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
            </template>
          </VPlaceholderPage> -->
          <div>
            <!-- <div v-else> -->
            <div class="column">
              <!-- <div class="column" v-if="selectView == 'grid'"> -->
              <div class="table-scroll analytics-dashboard">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <DataTable :value="dataSource" :rows="20" v-model:expandedRows="expandedRows"
                      :rowsPerPageOptions="[5, 10, 15, 20, 50, 100]" :loading="isLoading" class="p-datatable-sm p-datatable-gridlines"
                      responsiveLayout="stack" breakpoint="960px" selectionMode="single" sortMode="multiple"
                      dataKey="norec" resizableColumns
                      paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                      paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                      v-model:filters="filters" filterDisplay="row" :globalFilterFields="['namaproduk', 'satuanstandar']">
                      <template #header>
                        <div class="flex">

                          <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                            @click="exportExcel()"> Export to Excel </VButton>
                          <span class="p-input-icon-left">
                            <VField>
                              <VControl icon="feather:search">
                                <VInput type="text" v-model="filters['global'].value" placeholder="Search" />
                              </VControl>
                            </VField>

                          </span>
                        </div>

                      </template>
                      <template #empty> No data found. </template>
                      <ColumnGroup type="header">

                        <Row>
                          <Column header="No" :rowspan="2" style="width: 200px" />
                          <Column header="Kode" :rowspan="2" />
                          <!-- <Column header="ID BMN" :rowspan="2" /> -->
                          <Column header="Nama Barang" :rowspan="2" />
                          <Column header="Satuan" :rowspan="2" />
                          <Column header="Sumber Dana" :rowspan="2" />
                          <Column header="Saldo Awal" :colspan="3" />
                          <Column header="Jumlah Penerimaan/Pengadaan" :colspan="4" />
                          <Column header="Jumlah Pengeluaran" :colspan="4" />
                          <Column header="Stok Expired" :colspan="3" />
                          <Column header="Sisa Stok" :colspan="3" />
                        </Row>
                        <Row>
                          <Column header="Jumlah" field="saldoawal" />
                          <Column header="Harga Satuan" field="hargasatuan" />
                          <Column header="Total Harga" field="totalrpsaldoawal" />

                          <Column header="Jumlah" field="qtyterima" />
                          <Column header="Retur Jual" field="returjual" />
                          <Column header="Harga Satuan" field="hargasatuan" />
                          <Column header="Total Harga" field="totalhargaterima" />

                          <Column header="Jumlah" field="qtykeluar" />
                          <Column header="Retur Beli" field="returbeli" />
                          <Column header="Harga Satuan" field="hargasatuan" />
                          <Column header="Total Harga" field="totalhargakeluar" />

                          <Column header="Jumlah" field="qtyed" />
                          <Column header="Harga Satuan" field="hargasatuan" />
                          <Column header="Total Harga" field="totalrped" />

                          <Column header="Jumlah" field="saldoakhir" />
                          <Column header="Harga Satuan" field="hargasatuan" />
                          <Column header="Total Harga" field="totalrpsaldoakhir" />
                        </Row>
                      </ColumnGroup>
                      <Column field="no" style="min-width: 50px"></Column>
                      <Column field="id" style="min-width: 100px"></Column>
                      <!-- <Column field="kodebmn"></Column> -->
                      <Column field="namaproduk" style="min-width: 200px"></Column>
                      <Column field="satuanstandar" style="min-width: 150px"></Column>
                      <Column field="asalproduk" style="min-width: 150px"></Column>
                      <Column field="saldoawal" style="min-width: 150px">
                        <template #body="slotProps">
                          <!-- {{ H.formatRp(slotProps.data.saldoawal, '') }} -->
                          {{ slotProps.data.saldoawal }}
                        </template>
                      </Column>
                      <Column field="hargasatuan" style="min-width: 150px">
                        <template #body="slotProps">
                          <!-- {{ H.formatRupiah(slotProps.data.hargasatuan, '') }} -->
                          {{ H.formatRupiah(Math.ceil(slotProps.data.hargasatuan),'Rp') }}
                        </template>
                      </Column>
                      <Column field="totalrpsaldoawal" style="min-width: 150px">
                        <template #body="slotProps">
                          {{ H.formatRupiah(Math.ceil(slotProps.data.totalrpsaldoawal),'Rp') }}
                          <!-- {{ H.formatRp(slotProps.data.totalrpsaldoawal, '') }} -->
                        </template>
                      </Column>
                      <Column field="qtyterima" style="min-width: 150px">
                        <template #body="slotProps">
                          <!-- {{ H.formatRp(slotProps.data.qtyterima, '') }} -->
                            {{ slotProps.data.qtyterima }}
                        </template>
                      </Column>
                      <Column field="returjual" style="min-width: 150px">
                        <template #body="slotProps">
                          <!-- {{ H.formatRp(slotProps.data.returjual, '') }} -->
                          {{ slotProps.data.returjual }}
                        </template>
                      </Column>
                      <Column field="hargasatuan" style="min-width: 150px">
                        <template #body="slotProps">
                          {{ H.formatRupiah(slotProps.data.hargasatuan, 'Rp') }}
                        </template>
                      </Column>
                      <Column field="totalhargaterima" style="min-width: 150px">
                        <template #body="slotProps">
                          {{ H.formatRupiah(slotProps.data.totalhargaterima, 'Rp') }}
                        </template>
                      </Column>
                      <Column field="qtykeluar" style="min-width: 150px">
                        <template #body="slotProps">
                          <!-- {{ H.formatRp(slotProps.data.qtykeluar, '') }} -->
                          {{ slotProps.data.qtykeluar }}
                        </template>
                      </Column>
                      <Column field="returbeli" style="min-width: 150px">
                        <template #body="slotProps">
                          <!-- {{ H.formatRp(slotProps.data.returbeli, '') }} -->
                          {{ slotProps.data.returbeli }}
                        </template>
                      </Column>
                      <Column field="hargasatuan" style="min-width: 150px">
                        <template #body="slotProps">
                          {{ H.formatRupiah(slotProps.data.hargasatuan, 'Rp') }}
                        </template>
                      </Column>
                      <Column field="totalhargakeluar" style="min-width: 150px">
                        <template #body="slotProps">
                          {{ H.formatRupiah(slotProps.data.totalhargakeluar, 'Rp') }}
                        </template>
                      </Column>
                      <Column field="qtyed" style="min-width: 150px">
                        <template #body="slotProps">
                          <!-- {{ H.formatRp(slotProps.data.qtyed, '') }} -->
                          {{ slotProps.data.qtyed }}
                        </template>
                      </Column>
                      <Column field="hargasatuan" style="min-width: 150px">
                        <template #body="slotProps">
                          {{ H.formatRupiah(slotProps.data.hargasatuan, 'Rp') }}
                        </template>
                      </Column>
                      <Column field="totalrped" style="min-width: 150px">
                        <template #body="slotProps">
                          {{ H.formatRupiah(slotProps.data.totalrped, 'Rp') }}
                        </template>
                      </Column>
                      <Column field="saldoakhir" style="min-width: 150px">
                        <template #body="slotProps">
                          <!-- {{ H.formatRp(slotProps.data.saldoakhir, '') }} -->
                          {{ slotProps.data.saldoakhir }}
                        </template>
                      </Column>
                      <Column field="hargasatuan" style="min-width: 150px">
                        <template #body="slotProps">
                          {{ H.formatRupiah(slotProps.data.hargasatuan, 'Rp') }}
                        </template>
                      </Column>
                      <Column field="totalrpsaldoakhir" style="min-width: 150px">
                        <template #body="slotProps">
                          {{ H.formatRupiah(slotProps.data.totalrpsaldoakhir, 'Rp') }}
                        </template>
                      </Column>

                      <ColumnGroup type="footer">
                        <Row>
                          <Column footer="TOTAL" :colspan="2" footerStyle="text-align:right" />
                          <Column footer="" :colspan="4" />
                          <Column :footer="H.formatRp(totalSALDOAWAL, 'Rp. ')" :colspan="3"
                            footerStyle="text-align:right" />
                          <Column :footer="H.formatRp(totalPENERIMAAN, 'Rp. ')" :colspan="4"
                            footerStyle="text-align:right" />
                          <Column :footer="H.formatRp(totalPENGELUARAN, 'Rp. ')" :colspan="4"
                            footerStyle="text-align:right" />
                          <Column :footer="H.formatRp(totalEXP, 'Rp. ')" :colspan="3" footerStyle="text-align:right" />
                          <Column :footer="H.formatRp(totalSTOK, 'Rp. ')" :colspan="3" footerStyle="text-align:right" />
                        </Row>
                      </ColumnGroup>
                    </DataTable>
                  </div>
                  <!-- <div class="column is-6 is-offset-6">
                    <div class="dashboard-tile p-2">
                      <div class="tile-head">
                        <h3 class="dark-inverted">Total Stok</h3>
                        <VIconBox color="danger" size="small" rounded>
                          <i aria-hidden="true" class="fas fa-calculator"></i>
                        </VIconBox>
                      </div>
                      <div class="tile-body">
                        <span class="dark-inverted">{{ H.formatRp(totalSTOK, '') }}</span>
                      </div>
                    </div>
                  </div> -->
                </div>
              </div>
            </div>
            <!-- <div class="column" v-else>

              <div class="tile-grid tile-grid-v1">
                <div class="analytics-dashboard mb-4">
                  <div class="dashboard-tile p-2">
                    <div class="tile-head">
                      <h3 class="dark-inverted">TOTAL STOK</h3>
                      <VIconBox color="danger" size="small" rounded>
                        <i aria-hidden="true" class="fas fa-calculator"></i>
                      </VIconBox>
                    </div>
                    <div class="tile-body">
                      <span class="dark-inverted">{{ formatRp(totalSTOK, '') }}</span>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline mt-2" style="height:500px;overflow:auto">
                  <div v-for="(item, key) in dataSource" :key="key" class="column is-4">
                    <div class="tile-grid-item">
                      <div class="tile-grid-item-inner">
                        <VAvatar size="small" :initials="item.initials" :color="item.color" />
                        <div class="meta">
                          <span class="dark-inverted">{{ item.namaproduk }}</span>
                          <span> {{ formatRp(item.jumlah, '') }}</span>
                        </div>
                        <UserCardDropdown />
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </VCard>
  </div>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, watch } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import Calendar from 'primevue/calendar'
import MultiSelect from 'primevue/multiselect'
import sleep from '/@src/utils/sleep'
import { FilterMatchMode } from 'primevue/api'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import moment from 'moment';
import InputText from 'primevue/inputtext';
useHead({
  title: 'Laporan Persediaan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const isLoading = ref(false)
const dataSource: any = ref([])
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const d_JenisProduk: any = ref([]);
const d_DetailJenisProduk: any = ref([]);
const totalSALDOAWAL: any = ref(0)
const totalPENERIMAAN: any = ref(0)
const totalPENGELUARAN: any = ref(0)
const totalEXP: any = ref(0)
const totalSTOK: any = ref(0)
const expandedRows = ref();
const jenis = ref('')
const listColor: any = ref(Object.keys(useThemeColors()))

const d_View = [
  {
    name: 'Grid View',
    value: 'grid',
    icon: 'fas fa-id-card-alt',
  },
  {
    name: 'List View',
    value: 'list',
    icon: 'fas fa-list',
  },
]
const selectView: any = ref()
selectView.value = 'list'

const item: any = reactive({
  bulan: new Date(),
  bulanAkhir: new Date(),
  rows: 100,
  tahun: new Date().getFullYear()
})
const showFilter: any = ref(false)

const fetchData = async () => {
  isLoading.value = true;
  let startDate = H.formatDate(item.tglclosing, 'YYYY-MM-DD');
  let ruangan = item.ruangan ? item.ruangan.id : "";
  let rows = item.rows ?? "";
  let nmproduk = item.nmProduk ?? "";
  let listJenis = ""
  if (item.jenisProduk) {
    let arrayOfKeys = item.jenisProduk.map((obj: any) => obj.id)
    listJenis = arrayOfKeys.join(',');
  }
  let listDetailJenis = ""
  if (item.djenisProduk) {
    let arrayOfKeys = item.djenisProduk.map((obj: any) => obj.id)
    listDetailJenis = arrayOfKeys.join(',');
  }
  totalSTOK.value = 0

  let ttlsaldoawal = 0
  let ttlpenerimaan = 0
  let ttlpengeluaran = 0
  let ttlsaldoakhir = 0

  let date = new Date(item.bulan);
  let dateAkhir = new Date(item.bulanAkhir);
  let firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
  let lastDay = new Date(dateAkhir.getFullYear(), dateAkhir.getMonth() + 1, 0);
  let tglAwal = moment(firstDay).format('YYYY-MM-DD 00:00');
  let tglAkhir = moment(lastDay).format('YYYY-MM-DD 23:59');
  let tglawalsaldo = moment(lastDay).format('YYYY-MM-DD 00:00');
  let tglakhirsaldo = moment(lastDay).format('YYYY-MM-DD 23:59');

  let lalu = moment(item.bulan)
  let depan = moment(item.bulanAkhir).add(1, 'months')
  let bulanlalu = moment(lalu).format('MM.YYYY');
  let bln = moment(item.bulan).format('MM.YYYY');
  let bulanAwal = moment(date).format('YYYY-MM-01 00:00:00');
  let bulanAkhir = moment(depan).format('YYYY-MM-01 00:00:00');

  useApi().get(
        "/farmasi/get-data-laporan-persediaan-v5?" +
        "&blnLalu=" + bulanlalu +
        "&tglawal=" + tglAwal +
        "&tglakhir=" + tglAkhir +
        "&tglawalsaldo=" + tglawalsaldo +
        "&tglakhirsaldo=" + tglakhirsaldo +
        "&djp=" + listDetailJenis +
        "&jp=" + listJenis +
        "&blnawal=" + bulanAwal +
        "&blnakhir=" + bulanAkhir +
        "&nmproduk=" + nmproduk +
        "&idRuangan=" + ruangan).then(async (dat: any) => {
          isLoading.value = false
          for (let i = 0; i < dat.data.length; i++) {
            const element = dat.data[i];
            element.no = i + 1
          }
          hitungJML(dat.data)
          dataSource.value = dat.data;
        });
  // if (item.tahun != 'closingpersediaan') {
  //   if (tglAkhir > moment(new Date()).format('YYYY-MM-DD 00:00')) {
  //     useApi().get(
  //       "/farmasi/get-data-laporan-persediaan-v5?" +
  //       "&blnLalu=" + bulanlalu +
  //       "&tglawal=" + tglAwal +
  //       "&tglakhir=" + tglAkhir +
  //       "&tglawalsaldo=" + tglawalsaldo +
  //       "&tglakhirsaldo=" + tglakhirsaldo +
  //       "&djp=" + listDetailJenis +
  //       "&jp=" + listJenis +
  //       "&blnawal=" + bulanAwal +
  //       "&blnakhir=" + bulanAkhir +
  //       "&nmproduk=" + nmproduk +
  //       "&idRuangan=" + ruangan).then(async (dat: any) => {
  //         isLoading.value = false
  //         for (let i = 0; i < dat.data.length; i++) {
  //           const element = dat.data[i];
  //           element.no = i + 1
  //         }
  //         hitungJML(dat.data)
  //         dataSource.value = dat.data;
  //       });
  //   } else {
  //     useApi().get(
  //       "/farmasi/get-data-laporan-persediaan-v4?" +
  //       "&blnLalu=" + bulanlalu +
  //       "&tglawal=" + tglAwal +
  //       "&tglakhir=" + tglAkhir +
  //       "&tglawalsaldo=" + tglawalsaldo +
  //       "&tglakhirsaldo=" + tglakhirsaldo +
  //       "&djp=" + listDetailJenis +
  //       "&jp=" + listJenis +
  //       "&nmproduk=" + nmproduk +
  //       "&idRuangan=" + ruangan).then(async (dat: any) => {
  //         isLoading.value = false
  //         for (let i = 0; i < dat.data.length; i++) {
  //           const element = dat.data[i];
  //           element.no = i + 1
  //         }
  //         hitungJML(dat.data)
  //         dataSource.value = dat.data;
  //       });
  //   }
  // } else {
  //   useApi().get(
  //     "/farmasi/get-data-laporan-persediaan-v5?" +
  //     "&blnLalu=" + bulanlalu +
  //     "&tglawal=" + tglAwal +
  //     "&tglakhir=" + tglAkhir +
  //     "&tglawalsaldo=" + tglawalsaldo +
  //     "&tglakhirsaldo=" + tglakhirsaldo +
  //     "&djp=" + listDetailJenis +
  //     "&jp=" + listJenis +
  //     "&blnawal=" + bulanAwal +
  //     "&blnakhir=" + bulanAkhir +
  //     "&nmproduk=" + nmproduk +
  //     "&idRuangan=" + ruangan).then(async (dat: any) => {
  //       isLoading.value = false
  //       for (let i = 0; i < dat.data.length; i++) {
  //         const element = dat.data[i];
  //         element.no = i + 1
  //       }
  //       hitungJML(dat.data)
  //       dataSource.value = dat.data;
  //     }).catch((e: any) => {
  //       isLoading.value = false
  //     })

  // }


}

const hitungJML = (e: any) => {
  totalSALDOAWAL.value = 0
  totalPENERIMAAN.value = 0
  totalPENGELUARAN.value = 0
  totalEXP.value = 0
  totalSTOK.value = 0
  for (let x = 0; x < e.length; x++) {
    const element = e[x];
    totalSALDOAWAL.value = totalSALDOAWAL.value + element.totalrpsaldoawal
    totalPENERIMAAN.value = totalPENERIMAAN.value + element.totalhargaterima
    totalPENGELUARAN.value = totalPENGELUARAN.value + element.totalhargakeluar
    totalEXP.value = totalEXP.value + element.totalrped
    totalSTOK.value = totalSTOK.value + element.totalrpsaldoakhir
  }
}



const dropdown = async () => {
  const response = await useApi().get(`/farmasi/persediaan-dropdown`)

  d_JenisProduk.value = response.jenisproduk
  d_DetailJenisProduk.value = response.detailjenisproduk

}

const changeView = (e: any) => {
  selectView.value = e
}

const exportExcel = () => {
  H.exportExcel(dataSource.value, 'LaporanPersediaan')
}


watch(
  () => item.findBy,
  (newValue, oldValue) => {
    if (newValue == 0) {
      item.bulan = new Date(item.tahun + '-01-01 00:00');
      item.bulanAkhir = new Date(item.tahun + '-06-01 00:00');
    }
    if (newValue == 1) {
      item.bulan = new Date(item.tahun + '-07-01 00:00');
      item.bulanAkhir = new Date(item.tahun + '-12-01 00:00');
    }
    if (newValue == 2) {
      item.bulan = new Date(item.tahun + '-01-01 00:00');
      item.bulanAkhir = new Date(item.tahun + '-12-01 00:00');
    }

  }
)
dropdown();
// fetchData();
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
  margin-top: 8px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
  margin-top: 14px;
}

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.tile-grid-v3 {
  .tile {
    &.is-ancestor {
      margin-left: -0.5rem;
      margin-right: -0.5rem;
      margin-top: -0.5rem;
    }

    &.is-parent {
      padding: 0.5rem;
    }
  }

  .tile-grid-item {
    @include vuero-s-card;

    padding: 10px;
    border-radius: 16px;

    &.is-medium {
      max-height: 132px;

      .tile-grid-item-inner {
        display: flex;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;
          max-width: 110px;
          min-width: 110px;
          height: 100%;
          min-height: 110px;
          max-height: 110px;
          object-fit: cover;
        }

        .meta {
          margin-left: 12px;
          display: flex;
          flex-direction: column;
          justify-content: space-between;

          .tile-title {
            h3 {
              font-family: var(--font);
              font-family: var(--font-alt);
              font-size: 1rem;
              font-weight: 600;
              color: var(--dark-text);
              line-height: 1.3;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-family: var(--font-alt);
                  font-size: 0.85rem;
                  font-weight: 600;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  font-family: var(--font);
                  color: var(--light-text);
                }
              }
            }
          }
        }
      }
    }

    &.is-large {
      .tile-grid-item-inner {
        display: flex;
        flex-direction: column;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;
          height: 180px;
          object-fit: cover;
        }

        .meta {
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          flex: 1 1 0;
          padding: 0 5px;

          .tile-title {
            padding-top: 10px;

            h3 {
              font-family: var(--font);
              font-size: 1.2rem;
              font-weight: 500;
              color: var(--dark-text);
              line-height: 1.1;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-size: 0.9rem;
                  font-weight: 500;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  color: var(--light-text);
                }
              }
            }
          }
        }
      }
    }

    &.is-wide {
      .tile-grid-item-inner {
        display: flex;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;
          max-width: 280px;
          height: 100%;
          min-height: 160px;
          object-fit: cover;
        }

        .meta {
          margin-left: 12px;
          display: flex;
          flex-direction: column;
          justify-content: space-between;

          .tile-title {
            padding-top: 5px;

            h3 {
              font-family: var(--font);
              font-size: 1.3rem;
              font-weight: 500;
              color: var(--dark-text);
              line-height: 1.1;
            }

            p {
              color: var(--light-text);
              font-size: 0.95rem;
              padding-top: 5px;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-size: 0.9rem;
                  font-weight: 500;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  color: var(--light-text);
                }
              }
            }
          }
        }
      }
    }

    &.is-tall {
      .tile-grid-item-inner {
        display: flex;
        flex-direction: column;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;

          // max-width: 110px;
          height: 220px;
          object-fit: cover;
        }

        .meta {
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          flex: 1 1 0;
          padding: 0 5px;

          .tile-title {
            padding-top: 10px;

            h3 {
              font-family: var(--font);
              font-size: 1.2rem;
              font-weight: 500;
              color: var(--dark-text);
              line-height: 1.1;
            }

            p {
              color: var(--light-text);
              font-size: 0.95rem;
              padding-top: 5px;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-size: 0.9rem;
                  font-weight: 500;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  color: var(--light-text);
                }
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }

  .tile-grid-v3 {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }
}

@media only screen and (max-width: 767px) {
  .tile-grid-v3 {
    .tile-grid-item {
      max-height: 132px !important;

      .tile-grid-item-inner {
        display: flex !important;
        flex-direction: row !important;
        height: 100% !important;

        >img {
          display: block !important;
          border-radius: 12px !important;
          width: 100% !important;
          max-width: 110px !important;
          min-width: 110px !important;
          height: 100% !important;
          min-height: 110px !important;
          max-height: 110px !important;
          object-fit: cover !important;
        }

        .meta {
          margin-left: 12px !important;
          display: flex !important;
          flex-direction: column !important;
          justify-content: space-between !important;

          .tile-title {
            h3 {
              font-family: var(--font) !important;
              font-size: 1.1rem !important;
              font-weight: 500;
              color: var(--dark-text) !important;
              line-height: 1.1 !important;
            }

            p {
              display: none !important;
            }
          }

          .tile-meta {
            display: flex !important;
            align-items: center !important;
            margin-top: auto !important;
            padding: 0 0 5px !important;

            .v-avatar {
              max-width: 26px !important;
              max-height: 26px !important;
              min-width: 26px !important;

              .avatar {
                max-width: 26px !important;
                max-height: 26px !important;
                min-width: 26px !important;
              }
            }

            .meta-inner {
              margin-left: 8px !important;
              line-height: 1.2 !important;

              span {
                display: block !important;
                font-weight: 400 !important;

                &:first-child {
                  color: var(--dark-text) !important;
                  font-size: 0.9rem !important;
                  font-weight: 500 !important;
                }

                &:nth-child(2) {
                  font-size: 0.8rem !important;
                  color: var(--light-text) !important;
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .tile-grid-v3 {
    .tile-grid-item {
      max-height: 132px !important;

      .tile-grid-item-inner {
        display: flex !important;
        flex-direction: row !important;
        height: 100% !important;

        >img {
          display: block !important;
          border-radius: 12px !important;
          width: 100% !important;
          max-width: 110px !important;
          min-width: 110px !important;
          height: 100% !important;
          min-height: 110px !important;
          max-height: 110px !important;
          object-fit: cover !important;
        }

        .meta {
          margin-left: 12px !important;
          display: flex !important;
          flex-direction: column !important;
          justify-content: space-between !important;

          .tile-title {
            h3 {
              font-family: var(--font) !important;
              font-size: 1.1rem !important;
              font-weight: 500;
              color: var(--dark-text) !important;
              line-height: 1.1 !important;
            }

            p {
              display: block;
              max-width: 460px;
            }
          }

          .tile-meta {
            display: flex !important;
            align-items: center !important;
            margin-top: auto !important;
            padding: 0 0 5px !important;

            .v-avatar {
              max-width: 26px !important;
              max-height: 26px !important;
              min-width: 26px !important;

              .avatar {
                max-width: 26px !important;
                max-height: 26px !important;
                min-width: 26px !important;
              }
            }

            .meta-inner {
              margin-left: 8px !important;
              line-height: 1.2 !important;

              span {
                display: block !important;
                font-weight: 400 !important;

                &:first-child {
                  color: var(--dark-text) !important;
                  font-size: 0.9rem !important;
                  font-weight: 500 !important;
                }

                &:nth-child(2) {
                  font-size: 0.8rem !important;
                  color: var(--light-text) !important;
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .tile-grid-v3 {
    .tile-grid-item {
      &.is-medium {
        .tile-grid-item-inner {
          .tile-title {
            h3 {
              font-size: 1rem !important;
            }
          }
        }
      }

      &.is-large {
        .tile-grid-item-inner {
          .tile-title {
            h3 {
              font-size: 1.1rem !important;
            }
          }
        }
      }

      &.is-tall {
        .tile-grid-item-inner {
          .tile-title {
            h3 {
              font-size: 1.2rem !important;
            }
          }
        }
      }

      &.is-wide {
        .tile-grid-item-inner {
          >img {
            max-width: 180px;
            min-height: 160px;
          }

          .tile-title {
            h3 {
              font-size: 1.2rem !important;
            }
          }
        }
      }
    }
  }
}
</style>

