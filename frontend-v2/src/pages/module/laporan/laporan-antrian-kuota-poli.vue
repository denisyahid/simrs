
<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Pencarian</label>
      </div>
      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-4 pt-0">
            <VField label="Periode">
              <VDatePicker v-model="item.date" mode="date" style="width: 100%" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>

          <div class="column is-4 pt-0">
            <VField class="is-autocomplete-select" label="Ruangan">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.ruangan" :options="d_ruangan" placeholder="Pilih Barang"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <!-- <div class="column is-3">
            <VField label="Nama Pasien">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.namapasien" v-on:keyup.enter="fetchOrder()" placeholder="Nama Pasien" />
              </VControl>
            </VField>
          </div> -->
          <div class="column btn-search pt-0">
            <VButton type="button" icon="feather:search" :loading="isLoading" @click="fetchData()">
              Cari Data
            </VButton>
          </div>

        </div>
      </div>
    </VCard>
  </div>
  <!-- <div class="column">
    <VCard class="r-card">
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-4 pt-0 pb-0">
                <span>Periode</span>
                <VField class="mt-3">
                  <VDatePicker v-model="item.date" mode="date" style="width: 100%" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-4 pt-0 pb-0">
                <VButton type="button" icon="feather:search" :loading="isLoading" color="success" raised
                  @click="fetchData()"> Cari
                </VButton>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div> -->
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Hasil Pencarian</label>
      </div>
      <div class="column is-12">
        <DataTable :value="dataSource" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
          <Column field="no" header="No" style="min-width: 50px" />
          <Column field="namaruangan" header="Nama Ruangan" style="min-width: 70px" />
          <Column field="quotafix" header="Kuota Ruangan" style="min-width: 50px" />
          <Column field="terpakai" header="terdaftar" style="min-width: 50px" />
          <Column field="mjkn" header="MJKN" style="min-width: 50px" />
          <Column field="kiosk" header="KIOSK" style="min-width: 50px" />
          <Column field="sisa" header="Sisa" style="min-width: 50px" />
          <Column field="bersedia" header="Berobat" style="min-width: 50px" />
          <Column field="batal" header="Batal Berobat" style="min-width: 50px" />
        </DataTable>
      </div>
    </VCard>
  </div>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Logging Perubahan Kuota Poli</label>
      </div>
      <div class="column is-12">
        <div class="user-grid-toolbar">
        <VControl icon="feather:search">
          <input v-model="filters" class="input custom-text-filter" placeholder="Search Ruangan..." />
        </VControl>
      </div>
        <DataTable :value="dataSourcefiltered" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
          <Column field="no" header="No" style="min-width: 50px" />
          <Column field="namapegawai" header="Nama Pegawai" style="min-width: 50px" />
          <Column field="namarungan" header="Nama Ruangan" style="min-width: 50px" />
          <Column field="tanggal" header="Tanggal" style="min-width: 50px" />
          <Column field="konvensionalold" header="Sebelumnya" style="min-width: 50px" />
          <Column field="konvensiolanNew" header="Sekarang" style="min-width: 50px" />
        </DataTable>
      </div>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import * as XLSX from "xlsx";
useHead({
  title: 'Laporan Antrian Kuota Poli - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
  date: new Date()
})

const isLoading = ref(false);
const d_ruangan = ref([])
let dataSource: any = ref([])
const dataSource2: any = ref([])

const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource2.value
  }
  return dataSource2.value.filter((items: any) => {
    return (items.namarungan.match(new RegExp(filters.value, 'i')))
  })
})



async function fetchData() {
  isLoading.value = true
  let date = moment(item.value.date).format('YYYY-MM-DD');
  let ruangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan}` : ''
  useApi().get(`/pelayanan/get-laporan-antrian-kuota-poli?date=${date}${ruangan}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    })
    response.loggings.forEach((element: any, i: any) => {
      element.no = i + 1
    })
    dataSource.value = response.data
    dataSource2.value = response.loggings
    isLoading.value = false
  }).catch((e: any) => {
    isLoading.value = false
  })
}

function loadDropdown() {
  d_ruangan.value = []
  useApi().get(
    `/pelayanan/get-ruangan-poli`).then((response: any) => {
      d_ruangan.value = response.map((e: any) => { return { label: e.namaruangan, value: e.id } })
    })
}

loadDropdown()
fetchData();

</script>

<style lang="scss">
.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}

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

// .title-page {
//   font-weight: 600;
//   font-size: 18px;
// }

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
  text-align: center !important;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top
}
</style>
