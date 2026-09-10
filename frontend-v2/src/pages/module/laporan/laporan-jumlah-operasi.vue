<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Laporan Jumlah Tindakan Operasi</label>
      </div>
      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField label="Periode">
              <VControl class="prime-auto">
                <Calendar inputId="range" v-model="item.qBulan" selectionMode="range" :manualInput="false"
                  class="w-100 mb-4 is-rounded" :showIcon="true" date-format="yy-mm-dd" />
              </VControl>
            </VField>
          </div>
          <div class="column mt-5">
            <VButton type="button" icon="feather:search" color="primary" raised :loading="isLoading"
              @click="fetchData()">
              Cari
            </VButton>
            <VButton type="button" icon="feather:send" color="info" raised :loading="isLoading2" @click="kirimData()"
              class="ml-2">
              Kirim Data
            </VButton>
          </div>

          <div class="column is-12">
            <Fieldset legend="Query" :toggleable="true" :collapsed="true">
              <div class="column is-12">
                <VField>
                  <VLabel class="required-field">Query</VLabel>
                  <VControl>
                    <VTextarea v-model="item.query" rows="20" placeholder="...">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset legend="Keterangan" :toggleable="true" :collapsed="true">
              <div class="column is-12">
                <br />
                select <br />
                {noregistrasi}, <br />
                {tglpelayanan -> (yyyy-mm-dd)}, <br />
                {namapasien}, <br />
                {ruanganasal}, <br />
                {namaproduk}, <br />
                {jumlah}, <br />
                from <br />
                {query} <br />
                where <br />
                bulan =
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-3">
              <VCardCustom style="background-color: bisque;">
                <div :class="'label-cing'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1">Total Realisasi Operasi : {{ item.jumlahTindakan
                    }} Tindakan</span>
                </div>

              </VCardCustom>
            </div>
            <div class="column is-3">
              <VCardCustom style="background-color: aliceblue;">
                <div :class="'label-cing'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1">Total Rencana Operasi : {{ item.jumlahRencana
                    }} Tindakan</span>
                </div>

              </VCardCustom>
            </div>
            </div>


            <div class="column is-12">
              <Vcard>

                <TabView class="tabview-custom mt-3">
                <TabPanel>
                  <template #header>
                    <span>Realisasi Operasi </span>
                  </template>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <DataTable v-model:filters="filters" :value="dataKunjungan" paginator :rows="10" dataKey="no"
                        filterDisplay="row" :globalFilterFields="['noregistrasi', 'namapasien', 'namaproduk']"
                        :class="`p-datatable-small`" showGridlines>
                        <template #header>
                          <div class="columns is-multiline">
                            <div class="column is-3">
                              <span> Detail Tindakan Operasi </span>
                            </div>

                          </div>

                          <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                            <span class="p-input-icon-left">

                              <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                            </span>
                            <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="primary"
                              v-tooltip-prime="'Export'" @click="exportExcel(dataKunjungan, 'LapKunjungan')">
                              Export Excel
                            </VButton>
                          </div>
                        </template>

                        <template #empty style="text-align: center;"> No data found. </template>
                        <Column field="no" header="No" style="width: 50px; text-align: center;" />
                        <Column field="noregistrasi" header="No. Registrasi" />
                        <Column field="tanggal" header="Tanggal Pelayanan" />
                        <Column field="namapasien" header="Nama Pasien" />
                        <Column field="ruanganasal" header="Ruangan Asal" />
                        <Column field="ruangOK" header="Ruang Operasi" />
                        <Column field="namaproduk" header="Tindakan Operasi" />
                        <Column field="jumlah" header="Jumlah Tindakan" style="text-align: center;" />

                      </DataTable>
                    </div>
                  </div>
                </TabPanel>
                <TabPanel>
                  <template #header>
                    <span>Rencana Operasi </span>
                  </template>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <DataTable v-model:filters="filter" :value="dataRencana" paginator :rows="10" dataKey="no"
                        filterDisplay="row" :globalFilterFields="['noregistrasi', 'namapasien', 'namaproduk']"
                        :class="`p-datatable-small`" showGridlines>
                        <template #header>
                          <div class="columns is-multiline">
                            <div class="column is-3">
                              <span> Detail Rencana Operasi </span>
                            </div>

                          </div>

                          <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                            <!-- <span class="p-input-icon-left">

                              <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                            </span> -->
                            <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="primary"
                              v-tooltip-prime="'Export'" @click="exportExcel(dataRencana, 'LapRencana')">
                              Export Excel
                            </VButton>
                          </div>
                        </template>

                        <template #empty style="text-align: center;"> No data found. </template>
                        <Column field="no" header="No" style="width: 50px; text-align: center;" />
                        <Column field="noreg" header="No. Registrasi" />
                        <Column field="pasien" header="Nama Pasien" />
                        <Column field="tglorder" header="Tanggal Order" />
                        <Column field="noorder" header="No. Order" />
                        <Column field="rencana" header="Rencana Operasi" />
                        <Column field="ruangasal" header="Ruangan Asal" />
                        <Column field="ruangbedah" header="Ruang Operasi" />
                      </DataTable>
                    </div>
                  </div>
                </TabPanel>

              </TabView>
              </Vcard>





            </div>
          </div>
        </div>
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
import Dropdown from 'primevue/dropdown';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import { FilterMatchMode } from 'primevue/api'
import InputText from 'primevue/inputtext';
import Fieldset from 'primevue/fieldset';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';


useHead({
  title: 'Laporan Tindakan Operasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  qBulan: [
    new Date(),
    new Date()
  ],
  //   query: `SELECT
  //     to_char(pp.tglpelayanan, 'YYYY-MM') AS bulan,
  //     pp.tglpelayanan as tanggal,
  //     SUM(pp.jumlah) AS jumlah_tindakan  FROM
  //     pelayananpasien_t pp
  //     INNER JOIN antrianpasiendiperiksa_t apd ON pp.noregistrasifk = apd.norec
  //     INNER JOIN pasiendaftar_t pd ON apd.noregistrasifk = pd.norec
  //     INNER JOIN ruangan_m ru ON apd.objectruanganfk = ru.id  WHERE
  //     pp.tglpelayanan BETWEEN '$START_DATE' AND '$END_DATE'
  //     AND pp.kdprofile = 1
  //     AND ru.objectdepartemenfk = 45
  //     AND pp.statusenabled = true
  //     AND pd.statusenabled = true
  //     AND pp.strukresepfk IS NULL
  //     GROUP BY
  //     bulan, pp.tglpelayanan
  //     ORDER BY
  //     bulan;
  // `
})
const dataRekap: any = ref([])
const isLoading2: any = ref(false)
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const filter = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});

const dataKunjungan: any = ref([])
const dataRencana: any = ref([])
const isLoading = ref(false);
const fetchData = async () => {


  let tglAwal = H.formatDate(item.value.qBulan[0], "YYYY-MM-DD 00:00:00")
  let tglAkhir = H.formatDate(item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0], "YYYY-MM-DD 23:59:59")

  // let tglAwal = H.formatDate(item.value.qBulan[0], "YYYY-MM-01")
  // let mont = item.value.qBulan[0]
  // let last = new Date(mont.getFullYear(), mont.getMonth() + 1, 0).getDate();
  // let tglAkhir = H.formatDate(item.value.qBulan[0], "YYYY-MM-" + last)
  // if (item.value.qBulan.length == 2 && item.value.qBulan[1] != null) {
  //   mont = item.value.qBulan[1]
  //   last = new Date(mont.getFullYear(), mont.getMonth() + 1, 0).getDate();
  //   tglAkhir = H.formatDate(item.value.qBulan[1], "YYYY-MM-" + last)
  // }
  item.value.tglAwal = new Date(tglAwal)
  item.value.tglAkhir = new Date(tglAkhir)
  // let queryParam = item.value.query
  // queryParam = queryParam.replace('$START_DATE', tglAwal)
  // queryParam = queryParam.replace('$END_DATE', tglAkhir)
  // item.value.query = queryParam


  isLoading.value = true
  await useApi().get(`mkko/lap-jumlah-tindakan-operasi?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`).then((response) => {
    response.dataOperasi.forEach((element: any, i: any) => {
      element.no = i + 1
      // element.bulan_str = i + 1
    });

    response.dataRencana.forEach((element: any, z: any) => {
      element.no = z + 1
    });

    isLoading.value = false
    dataRekap.value = response.data
    dataKunjungan.value = response.dataOperasi
    dataRencana.value = response.dataRencana
    item.value.jumlahTindakan = response.totalAll
    item.value.jumlahRencana = response.totalRencana

  })

}
const kirimData = async () => {
  if (dataRekap.value.length == 0) {
    H.alert('error', 'Data belum ada')
    return
  }

  for (let x = 0; x < dataRekap.value.length; x++) {
    const element = dataRekap.value[x];
    isLoading2.value = true
    await useApi().post(`mkko/api-integrate`,
      {
        url: 'jml-tindakan-operasi',
        method: 'POST',
        data: {

          "tanggal": element.tgl,
          "detail": {
            "jml_tindakan_operasi": element.jumlah_tindakan
          }
        }
      }
    ).then((response) => {
      isLoading2.value = false
    }).catch((e: any) => {
      isLoading2.value = false
    })
  }


}

const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}
fetchData()
watch(
  () => [
    item.value.qBulan
  ], (oldValue, newValue) => {
    // fetchData()
  }
)
</script>

<style lang="scss">
.label-cing {
  display: flex;
  align-items: center;
  font-size: 0.9rem;
  font-family: var(--font-alt);
  font-weight: 600;
  margin-bottom: 0px;
}

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
