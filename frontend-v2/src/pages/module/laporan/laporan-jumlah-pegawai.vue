
<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Laporan Jumlah Pegawai</label>
      </div>
      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField label="Periode">
              <VControl class="prime-auto">
                <Calendar inputId="range" v-model="item.qBulan" selectionMode="single" :manualInput="false"
                    class="w-100 mb-4 is-rounded" :showIcon="true" date-format="yy-mm-dd" />
              </VControl>
            </VField>
          </div>
          <div class="column mt-5">
            <VButton type="button" icon="feather:search" color="primary" raised :loading="isLoading" @click="fetchData()">
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
                    <VTextarea v-model="item.query" rows="10" placeholder="...">
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
                {idpegawai}, <br />
                {tglmasuk -> (yyyy-mm-dd)}, <br />
                {jenispegawai}, <br />
                from <br />
                {query} <br />
                where <br />
                bulan =
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <div class="column is-12" v-if="dataKunjungan.loading">
              <div class="flex-list-inner mb-4">
                <div class="flex-table-item grid-item mb-4" v-for="key in 3" :key="key">
                  <VFlexTableCell :column="{ grow: true, media: true }">
                    <VPlaceloadAvatar size="medium" />
                    <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                  </VFlexTableCell>
                  <VFlexTableCell :column="{ align: 'end' }">
                    <VPlaceload width="10%" class="mx-1" />
                  </VFlexTableCell>
                </div>
              </div>
            </div>
            <div class="column is-12" v-else-if="dataKunjungan.length === 0">
              <VPlaceholderSection title="Data Tidak Ditemukan" subtitle="Silakan Pilih Periode." class="my-6">
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderSection>
            </div>
            <div class="column is-12" v-else-if="dataKunjungan.length > 0">
            <div class="columns is-multiline">

                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <VCardCustom>
                        <div :class="'label-cing'">
                          <span class="ml-1">Jumlah Dokter : <b>{{
                           dataTotal[0]?  dataTotal[0].dokter:0
                          }}</b></span>
                        </div>

                      </VCardCustom>
                    </div>
                    <div class="column is-3">
                      <VCardCustom>
                        <div :class="'label-cing'">
                          <span class="ml-1">Jumlah Perawat : <b>{{
                              dataTotal[0]?  dataTotal[0].perawat:0

                          }}</b></span>
                        </div>

                      </VCardCustom>
                    </div>
                    <div class="column is-3">
                      <VCardCustom>
                        <div :class="'label-cing'">
                          <span class="ml-1">Jumlah Penunjang (farmasi, lab, dll) : <b>{{
                            dataTotal[0]?  dataTotal[0].penunjang:0

                          }}</b></span>
                        </div>

                      </VCardCustom>
                    </div>
                    <div class="column is-3">
                      <VCardCustom>
                        <div :class="'label-cing'">
                          <span class="ml-1">Jumlah Administrasi : <b>{{
                               dataTotal[0]?  dataTotal[0].administrasi:0
                          }}</b></span>
                        </div>

                      </VCardCustom>
                    </div>
                  </div>

                </div>


                <div class="column is-12">
                  <DataTable :value="dataKunjungan"
                    tableStyle="min-width: 20rem" rowGroupMode="subheader" groupRowsBy="jenispegawai" sortMode="single"

                    scrollable scrollHeight="400px"
                    sortField="jenispegawai" :sortOrder="1">
                    <template #header>
                      <span> Detail Pegawai Berdasarkan Jenis Pegawai</span>
                    </template>

                    <template #groupheader="slotProps">
                      <span class="vertical-align-middle ml-2 font-bold line-height-3">{{
                        slotProps.data.jenispegawai }}</span>
                    </template>
                    <Column field="jenispegawai" header="Jenis Pegawai" style="width: 20%"></Column>
                    <Column field="namalengkap" header="Nama Pegawai" style="width: 20%"></Column>
                    <Column field="jenispegawai" header="Jenis Pegawai" style="width: 20%"></Column>
                    <Column field="idpegawai" header="Kode Pegawai" style="width: 20%"></Column>
                    <Column field="status" header="Status Pegawai" style="width: 20%"></Column>
                    <Column field="bulan" header="Tanggal Masuk" style="width: 20%"></Column>
                    <template #groupfooter="slotProps">
                      <!-- <div class="flex justify-content-end font-bold w-full">Total Customers: {{
                                                calculateCustomerTotal(slotProps.data.representative.name) }}</div> -->
                    </template>
                  </DataTable>
                  <div class="column is-3">
                      <VCardCustom>
                        <div :class="'label-cing'">
                          <span class="ml-1">TOTAL : <b>{{ dataKunjungan.length
                          }}</b></span>
                        </div>

                      </VCardCustom>
                    </div>
                  <Toast />

                </div>
              </div>


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
import { useToast } from 'primevue/usetoast';
import Fieldset from 'primevue/fieldset';
import 'primeicons/primeicons.css'


useHead({
  title: 'Laporan Jumlah Pegawai - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  qBulan:
    new Date()
  ,
  query:
    `SELECT
    pg.ID AS idpegawai,
    pg.namalengkap,
    pg.objectjenispegawaifk,
    jp.jenispegawai,
    '$BULAN' AS bulan,
    CASE when pg.tglkeluar is null then 'Aktif' else 'Tidak Aktif' END AS status,
    CASE
        WHEN jp.ID = 1 THEN 'Dokter'
        WHEN jp.ID = 2 THEN 'Perawat'
        WHEN jp.ID IN (8, 29) THEN 'Administrasi'
        else 'Penunjang'
    END AS jenispegawai
FROM
    pegawai_m AS pg
    LEFT JOIN jenispegawai_m AS jp ON jp.ID = pg.objectjenispegawaifk
WHERE
    pg.statusenabled = 't'
    AND pg.kdprofile = 1
    AND (CASE WHEN pg.tglkeluar IS NULL THEN TRUE ELSE pg.tglkeluar BETWEEN '$START_DATE' AND '$END_DATE' END)
    and  (case when tglmasuk is not null then  to_char(pg.tglmasuk,'yyyy-MM-dd')  <= '$END_DATE'
    AND to_char(pg.tglkeluar,'yyyy-MM-dd') IS NULL OR to_char(pg.tglmasuk,'yyyy-MM-dd')  >= '$START_DATE' else true end)
`

})
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});

const expandedRowGroups = ref();
const toast = useToast();
const isLoading2: any = ref(false)
const dataKunjungan: any = ref([])
const dataTotal: any = ref();
const isLoading = ref(false);




const fetchData = async () => {

  let tglAwal = `${moment(item.value.qBulan).format('YYYY-MM-DD 00:00:00')}`
  let tglAkhir = `${moment(item.value.qBulan).format('YYYY-MM-DD 23:59:59')}`
  let bln = `${moment(item.value.qBulan).format('YYYY-MM-DD')}`
  let query = item.value.query
  query = query.replaceAll('$START_DATE', tglAwal)
  query = query.replaceAll('$END_DATE', tglAkhir)
  query = query.replace('$BULAN', bln)

  isLoading.value = true
  let dari = tglAwal
  let sampai = tglAkhir

  await useApi().get(`mkko/lap-jml-pegawai?dari=${dari}&sampai=${sampai}&bulan=${bln}`)
  // await useApi().post(`mkko/lap-jml-pegawai`, {
  //   query: query
  // })
  .then((response) => {
    response.details.forEach((element: any, i: any) => {
      element.no = i + 1

    });

    isLoading.value = false
    dataKunjungan.value = response.details
    dataTotal.value = response.data

  })

}



const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}
const kirimData = async () => {
  if (dataTotal.value.length == 0) {
    H.alert('error', 'Data belum ada')
    return
  }
  isLoading2.value = true
  for (let index = 0; index < dataTotal.value.length; index++) {
    const rekap = dataTotal.value[index];
    await useApi().post(`mkko/api-integrate`,
      {
        url: 'sdm-jml-pegawai',
        method: 'POST',
        data: {
          "tanggal": `${moment(item.value.qBulan).format('YYYY-MM-DD')}`,
          "detail": {
            "jumlahpegawai": {
              "perawat":  rekap.perawat,
              "dokter":  rekap.dokter,
              "penunjang": rekap.penunjang,
              "staff_administrasi":  rekap.administrasi,
            }

          }
        }
      })
      isLoading2.value = false
  }

}

fetchData()

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
