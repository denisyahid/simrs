<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Laporan Inpatient Re-admission Rate</label>
      </div>
      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField label="Periode">
              <VControl class="prime-auto">
                <Calendar
                  inputId="range"
                  selectionMode="range"
                  v-model="item.qBulan"
                  :manualInput="false"
                  class="w-100"
                  :showIcon="true"
                  view="month"
                  dateFormat="MM-yy"
                />
              </VControl>
            </VField>
          </div>
          <div class="column mt-5">
            <VButton
              type="button"
              icon="feather:search"
              color="primary"
              raised
              :loading="isLoading"
              @click="fetchData()"
            >
              Cari
            </VButton>
            <VButton
              type="button"
              icon="feather:send"
              color="info"
              raised
              :loading="isLoading2"
              @click="kirimData()"
              class="ml-2"
            >
              Kirim Data
            </VButton>
          </div>
          <div class="column is-12">
            <Fieldset legend="Query" :toggleable="true" :collapsed="true">
              <div class="column is-12">
                <VField>
                  <VLabel class="required-field">Query</VLabel>
                  <VControl>
                    <VTextarea v-model="item.query" rows="5" placeholder="...">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </Fieldset>
          </div>
          <!-- <div class="column is-12">
            <Fieldset legend="CATATAN" :toggleable="true" :collapsed="true">
              <div class="column is-12">
                <br />
                select <br />
                {tanggal -> (yyyy-mm-dd HH:mm:ss)}, <br />
                {bor}, <br />
                {alos}, <br />
                {toi}, <br />
                {bto}, <br />
                {ndr}, <br />
                {ndr}, <br />
                from <br />
                {query} <br />
                where BETWEEN tanggal <br />
                [ {akan di isi dengan parameter (bulan -> yyyy-mm)}]
              </div>
            </Fieldset>
          </div> -->
          <div class="column is-12">
            <div class="column is-12" v-if="dataKunjungan.loading">
              <div class="flex-list-inner mb-4">
                <div class="flex-table-item grid-item mb-4" v-for="key in 3" :key="key">
                  <VFlexTableCell :column="{ grow: true, media: true }">
                    <VPlaceloadAvatar size="medium" />
                    <VPlaceloadText
                      :lines="2"
                      width="30%"
                      last-line-width="20%"
                      class="mx-2"
                    />
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
              <VPlaceholderSection
                title="Data Tidak Ditemukan"
                subtitle="Silakan Pilih Tanggal Periode Registrasi."
                class="my-6"
              >
                <template #image>
                  <img
                    class="light-image"
                    src="/@src/assets/illustrations/placeholders/search-4.svg"
                    alt=""
                  />
                  <img
                    class="dark-image"
                    src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                    alt=""
                  />
                </template>
              </VPlaceholderSection>
            </div>
            <div class="column is-12" v-else-if="dataKunjungan.length > 0">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <DataTable
                    v-model:filters="filtersTrans"
                    :value="dataKunjungan"
                    paginator
                    :rows="10"
                    dataKey="no"
                    filterDisplay="row"
                    :globalFilterFields="['namarekanan', 'umur']"
                    :class="`p-datatable-small`"
                    showGridlines
                    stripedRows
                    :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                    v-model:expanded-rows="expandedRows"
                  >
                    <!-- <DataTable :value="dataKunjungan" tableStyle="min-width: 50rem" scrollable :paginator="true" :rows="10"
                    :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" showGridlines> -->
                    <template #header>
                      <div
                        class="flex flex-wrap align-items-center justify-content-between gap-2"
                      >
                        <VButton
                          type="button"
                          icon="pi pi-file-excel"
                          class="mr-3"
                          outlined
                          circle
                          raised
                          v-tooltip-prime="'Export'"
                          @click="exportExcel(dataKunjungan, 'LapKunjungan')"
                        >
                          Export Excel
                        </VButton>
                      </div>
                    </template>

                    <template #empty style="text-align: center">
                      No data found.
                    </template>
                    <Column expander style="width: 5rem" header="Detail" />
                    <Column
                      field="no"
                      header="No"
                      style="width: 50px; text-align: center"
                    />
                    <Column field="bulan" header="Periode" />
                    <Column
                      field="total_count"
                      header="Total Pasien"
                      style="text-align: center"
                    />
                    <Column
                      field="jml"
                      header="Jumlah Pasien Dirawat Kembali"
                      style="text-align: center"
                    />
                    <Column
                      field="persentase"
                      header="Inpatient Re-admission Rate (%)"
                      style="text-align: center"
                    />
                    <template #expansion="slotProps">
                      <div class="p-3">
                        <DataTable
                          :value="slotProps.data.details"
                          paginator
                          :rows="10"
                          :class="`p-datatable-small`"
                          showGridlines
                          stripedRows
                          :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                        >
                          <template #header>
                            <div class="columns is-multiline">
                              <div class="column is-3">
                                <span> Pasien Dirawat Kembali </span>
                              </div>
                            </div>

                            <div
                              class="flex flex-wrap align-items-center justify-content-between gap-2"
                            ></div>
                          </template>

                          <template #empty style="text-align: center">
                            No data found.
                          </template>
                          <Column
                            field="no"
                            header="No"
                            style="width: 50px; text-align: center"
                          />
                          <Column field="nama_pasien" header="Nama Pasien"></Column>
                          <Column field="noregistrasi" header="Nomor Registrasi"></Column>
                          <Column
                            field="tglregistrasi"
                            header="Tanggal Registrasi"
                          ></Column>
                          <Column field="tglpulang" header="Tanggal Pulang"></Column>
                          <Column field="dokter" header="Dokter DPJP"></Column>
                        </DataTable>
                      </div>
                    </template>
                  </DataTable>
                </div>
                <div class="column is-12">
                  <span> Catatan : </span>
                  <br />
                  Inpatient Re-admission Rate (%) dihitung berdasarkan jumlah pasien yang
                  dirawat kembali setelah menerima perawatan sebelumnya di rumah sakit
                  dalam waktu 30 hari (1 bulan) terhadap jumlah pasien total
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
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar'
import Dropdown from 'primevue/dropdown'
import ColumnGroup from 'primevue/columngroup' // optional
import Row from 'primevue/row'
import { FilterMatchMode } from 'primevue/api'
import * as XLSX from 'xlsx'
import Fieldset from 'primevue/fieldset'
useHead({
  title: 'Persentase Re-Admission Rate - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  qBulan: [new Date()],
  query: `select bulan, sum(jumlah) as jml, sum(persentase) as persentase, total_count from (
            SELECT bulan, nama_pasien, COUNT(nama_pasien)-1 AS jumlah, (COUNT(nama_pasien) -1) * 100.00 / total_count AS persentase, total_count
        FROM (SELECT pd.tglregistrasi, ps.id AS psId, ps.namapasien AS nama_pasien,  pd.noregistrasi, to_char(pd.tglregistrasi, 'YYYY-MM') AS bulan,
           COUNT(*) OVER(PARTITION BY to_char(pd.tglregistrasi, 'YYYY-MM')) AS total_count
        FROM
            pasiendaftar_t AS pd
            INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
            LEFT JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
        WHERE
        pd.kdprofile = 1 and pd.tglregistrasi between '$START_DATE' and '$END_DATE' and pd.statusenabled=true
        and ru.objectdepartemenfk = 16) AS subquery
        GROUP BY
        bulan, total_count,nama_pasien) as z
        GROUP BY z.bulan, z.total_count`,
})
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } })
const expandedRowGroups: any = ref()
const expandedRows = ref()
const isLoading2: any = ref(false)
const dataKunjungan: any = ref([])
const isLoading = ref(false)
const fetchData = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], 'YYYY-MM-01')
  let mont = item.value.qBulan[0]
  let last = new Date(mont.getFullYear(), mont.getMonth() + 1, 0).getDate()
  let tglAkhir = H.formatDate(item.value.qBulan[0], 'YYYY-MM-' + last)
  if (item.value.qBulan.length == 2 && item.value.qBulan[1] != null) {
    mont = item.value.qBulan[1]
    last = new Date(mont.getFullYear(), mont.getMonth() + 1, 0).getDate()
    tglAkhir = H.formatDate(item.value.qBulan[1], 'YYYY-MM-' + last)
  }
  item.value.tglAwal = new Date(tglAwal)
  item.value.tglAkhir = new Date(tglAkhir)

  let queryParam = item.value.query
  queryParam = queryParam.replace('$START_DATE', tglAwal)
  queryParam = queryParam.replace('$END_DATE', tglAkhir)
  // item.value.query = queryParam

  let dari = tglAwal + ' 00:00:00'
  let sampai = tglAkhir + ' 23:59:59'
  isLoading.value = true
  await useApi()
    .get(`mkko/persentase-inpatien-visit?dari=${dari}&sampai=${sampai}`)
    // await useApi().post(`mkko/persentase-inpatien-visit`, {
    //   query: queryParam
    // })

    .then((response) => {
      response.forEach((element: any, i: any) => {
        expandedRows.value = element.details.forEach((data: any, i: any) => {
          data.no = i + 1
        })
        element.no = i + 1
        element.persentase = parseFloat(element.persentase).toFixed(2)
        element.bulan_str = element.bulan
        element.bulan = H.formatMonthOnly(element.bulan)
      })

      isLoading.value = false
      dataKunjungan.value = response
    })
}
const calculateCustomerTotal = (nama_pasien) => {
  let total = 0

  if (dataKunjungan.value) {
    for (let nama_pasien of dataKunjungan.value) {
      if (dataKunjungan.nama_pasien === nama_pasien) {
        total++
      }
    }
  }

  return total
}
const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}
const kirimData = async () => {
  if (dataKunjungan.value.length == 0) {
    H.alert('error', 'Data belum ada')
    return
  }

  for (let x = 0; x < dataKunjungan.value.length; x++) {
    const element = dataKunjungan.value[x]
    isLoading2.value = true

    await useApi()
      .post(`mkko/api-integrate`, {
        url: 'readmission-date',
        method: 'POST',
        data: {
          bulan: element.bulan_str.substr(5, 2),
          tahun: element.bulan_str.substr(0, 4),
          detail: {
            inpatient_readmission_rate: parseFloat(element.persentase).toFixed(2),
          },
        },
      })
      .then((response) => {
        isLoading2.value = false
      })
      .catch((e: any) => {
        isLoading2.value = false
      })
  }
}
const rowClass = (data) => {
  return [{ 'bg-primary': data.nama_pasien === 'Fitness' }]
}

fetchData()
</script>

<style lang="scss">
.same-nama-pasien {
  background-color: lightyellow; /* Change the background color as desired */
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
  vertical-align: top;
}
</style>
