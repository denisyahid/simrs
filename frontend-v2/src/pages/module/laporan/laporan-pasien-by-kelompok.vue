
<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Rekap Kunjungan Pasien Berdasarkan Tipe Layanan</label>
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
                {tglregistrasi -> (yyyy-mm-dd HH:mm:ss)}, <br />
                {noregistrasi}, <br />
                {namapasien}, <br />
                {namaruangan}, <br />
                {instalasi -> [Rawat Jalan, Rawat Inap]}, <br />
                {jenispasien -> [JKN, Non JKN Umum, Non JKN Asuransi, Non JKN Perusahaan]}, <br />
                {status_jkn_naik_kelas -> true/false}, <br />
                {jenispelayanan -> [Reguler, Eksekutif']}, <br />
                {bulan -> (yyyy-mm)} <br />
                from <br />
                {query} <br />
                where <br />
                bulan = {akan di isi dengan parameter (bulan -> yyyy-mm)}
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <div class="columns is-multiline">
              <br />
              <div class="column is-4">

                <Card>

                  <template #title>Rekap Kunjungan</template>

                  <template #content v-for="(cb, i) in arrGroup">
                    <p class="subtitle" style="font-size: 14px;"> Periode : {{
                      H.formatMonthOnly(cb.bulan) }} || Total Kunjungan : {{ cb.jumlah }} pasien </p>

                    <p class="subtitle" style="font-size: 14px;"> 1. Rawat Jalan : {{ cb.totalRajal }} </p>
                    <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.1 Pasien
                      JKN </p>
                    <span style="margin-left: 30px; margin-bottom: 20px !important;"> - Jumlah Pasien
                      JKN  : {{ cb.pasienJKNRJ }} </span>
                      <!-- <br />
                      <span style="margin-left: 30px; margin-bottom: 20px !important;"> - Jumlah Pasien
                      JKN Eksekutif : {{ cb.pasienJKNRJEks }} </span> -->
                    <br />
                    <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.2 Pasien
                      Non JKN Eksekutif </p>
                    <span style="margin-left: 30px;"> - Jumlah Pasien Asuransi : {{ cb.pasienEksAsunRJ }}
                    </span>
                    <br />
                    <span style="margin-left: 30px;"> - Jumlah Pasien Perusahaan : {{ cb.pasienEksPerRJ }} </span>
                    <br />
                    <span style="margin-left: 30px;"> - Jumlah Pasien Umum : {{ cb.pasienEksUmumRJ }}
                    </span>
                    <br />
                    <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.3 Pasien
                      Non JKN Reguler </p>
                    <span style="margin-left: 30px;"> - Jumlah Pasien Asuransi : {{ cb.pasienAsuransiRJ
                    }} </span>
                    <br />
                    <span style="margin-left: 30px;"> - Jumlah Pasien Perusahaan : {{ cb.pasienPerRJ }}
                    </span>
                    <br />
                    <span style="margin-left: 30px;"> - Jumlah Pasien Umum : {{ cb.pasienUmumRJ }}
                    </span>
                    <br />
                    <p class="subtitle" style="font-size: 14px;"> </p>
                    <p class="subtitle" style="font-size: 14px;"> 2. Rawat Inap : {{ cb.totalRanap }} </p>
                    <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.1 Pasien
                      JKN </p>
                    <span style="margin-left: 30px; margin-bottom: 20px !important;"> - Jumlah Pasien
                      JKN Reguler : {{ cb.pasienJKNRegRI }} </span>
                    <br />
                    <span style="margin-left: 30px; margin-bottom: 20px !important;"> - Jumlah Pasien
                      JKN Naik Kelas : {{ cb.pasienJKNNonRegRI }} </span>
                    <br />
                    <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.2 Pasien
                      Non JKN Eksekutif </p>
                    <span style="margin-left: 30px;"> - Jumlah Pasien Asuransi : {{ cb.pasienEksAsun }}
                    </span>
                    <br />
                    <span style="margin-left: 30px;"> - Jumlah Pasien Perusahaan : {{ cb.pasienEksPer }}
                    </span>
                    <br />
                    <span style="margin-left: 30px;"> - Jumlah Pasien Umum : {{ cb.pasienEksUmumRI }}
                    </span>
                    <br />
                    <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.3 Pasien
                      Non JKN Reguler </p>
                    <span style="margin-left: 30px;"> - Jumlah Pasien Asuransi : {{ cb.pasienAsuransiRI
                    }} </span>
                    <br />
                    <span style="margin-left: 30px;"> - Jumlah Pasien Perusahaan : {{ cb.pasienPerRI }}
                    </span>
                    <br />
                    <span style="margin-left: 30px;"> - Jumlah Pasien Umum : {{ cb.pasienUmumRI }}
                    </span>
                    <p class="subtitle" style="font-size: 14px;"> 3. Instalasi Lainnya : {{ cb.pasienLainnya }} </p>
                  </template>
                </Card>


              </div>
              <div class="column is-8">
                <div class="column is-12">

                  <div class="column is-12" v-if="dataPasien.length === 0">
                    <VPlaceholderSection title="Data Tidak Ditemukan" subtitle="Silakan Pilih Tanggal Periode Registrasi."
                      class="my-6">
                      <template #image>
                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                      </template>
                    </VPlaceholderSection>
                  </div>
                  <div class="column is-12" v-else-if="dataPasien.length > 0">
                    <div class="columns is-multiline">

                      <div class="column is-12">
                        <DataTable v-model:filters="filters" :value="dataPasien" paginator :rows="10" dataKey="no"
                          filterDisplay="row"
                          :globalFilterFields="['noregistrasi', 'instalasi', 'namapasien', 'jenispasien', 'jenispelayanan']"
                          :class="`p-datatable-small`" showGridlines>
                          <template #header>
                            <div class="flex justify-content-between">
                              <span class="p-input-icon-left">

                                <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                              </span>
                              <VButton type="button" icon="pi pi-file-excel" class="mr-3" v-tooltip-prime="'Export'"
                                @click="exportExcel(dataPasien, 'LapKunjungan')" color="primary">
                                Export Excel
                              </VButton>
                            </div>
                            <div class="flex flex-wrap align-items-center justify-content-between gap-2">

                            </div>
                          </template>

                          <template #empty style="text-align: center;"> No data found.
                          </template>

                          <Column field="no" header="No" style="width: 50px; text-align: center;" />
                          <Column field="tglregistrasi" header="Tgl Registrasi" />
                          <Column field="noregistrasi" header="No. Registrasi" style="text-align: center;" />
                          <Column field="namapasien" header="Nama Pasien" />
                          <Column field="namaruangan" header="Ruangan" />
                          <Column field="instalasi" header="Departemen" />
                          <Column field="jenispasien" header="Jenis Pasien" :sortable="true" />
                          <Column field="jenispelayanan" header="Layanan" />
                          <Column field="status_jkn_naik_kelas" header="JKN Naik Kelas" />
                        </DataTable>
                      </div>

                    </div>


                  </div>
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
import Card from 'primevue/card';
import Fieldset from 'primevue/fieldset';
import { FilterMatchMode } from 'primevue/api'
import InputText from 'primevue/inputtext';
useHead({
  title: 'Rekap Kunjungan Berdasarkan Tipe Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const arrGroup :any= ref([])
const item: any = ref({
  qBulan: [
    new Date(),
    new Date()
  ],
  query: `SELECT
    pd.tglregistrasi,
    pd.noregistrasi,
    ps.namapasien,
    ru.namaruangan,
    CASE
        WHEN dp.ID NOT IN (16) THEN 'Rawat Jalan'
        WHEN dp.ID  IN (16) THEN 'Rawat Inap'
        ELSE dp.namadepartemen
    END AS instalasi,
    CASE
        WHEN kp.ID in (2,18) THEN 'JKN'
        WHEN kp.ID in (31,1) THEN 'Non JKN Umum'
        WHEN kp.ID in (5,22,24) THEN 'Non JKN Asuransi'
        WHEN kp.ID in (29,32) THEN 'Non JKN Perusahaan'
        ELSE 'Non JKN Umum'
    END AS jenispasien,
    CASE
        WHEN pd.objectkelasrawatfk IS NOT NULL
             AND kp.kelompokpasien = 'BPJS'
             AND pd.objectkelasrawatfk != pd.objectkelasfk THEN TRUE
        ELSE FALSE
    END AS status_jkn_naik_kelas,
    CASE
        WHEN jp.ID = 1 THEN 'Reguler'
        WHEN jp.ID = 2 THEN 'Eksekutif'
        ELSE ''
    END AS jenispelayanan,
    to_char(pd.tglregistrasi, 'yyyy-MM-dd') AS bulan
FROM
    pasiendaftar_t AS pd
    INNER JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
    INNER JOIN jenispelayanan_m AS jp ON jp.ID = pd.jenispelayanan
    LEFT JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
    LEFT JOIN departemen_m AS dp ON dp.ID = ru.objectdepartemenfk
    LEFT JOIN kelompokpasien_m AS kp ON kp.ID = pd.objectkelompokpasienlastfk
WHERE
    pd.statusenabled = TRUE
    AND pd.kdprofile = 1
    AND pd.tglregistrasi between '$START_DATE' AND '$END_DATE';`,

})
const dataKunjungan: any = ref([])
const dataPasien: any = ref([])
const isLoading = ref(false);
const isLoading2 = ref(false);

const fetchData = async () => {

  let tglAwal = `${moment(item.value.qBulan[0]).format('YYYY-MM-DD 00:00:00')}`
  let tglAkhir = `${moment(item.value.qBulan[1]).format('YYYY-MM-DD 23:59:59')}`

  let query = item.value.query
  query = query.replace('$START_DATE', tglAwal)
  query = query.replace('$END_DATE', tglAkhir)

  isLoading.value = true
  await useApi().post(`mkko/jumlah-pasien-by-kelompok-query`, {
    query: query,
    dari: tglAwal,
    sampai: tglAkhir
  }).then((response) => {
    response.details.forEach((element: any, i: any) => {
      element.no = i + 1
    });


    isLoading.value = false
    dataKunjungan.value = response.data
    let t =0
    for (let x = 0; x < dataKunjungan.value.length; x++) {
      const element = dataKunjungan.value[x];
      t =t+element.jumlah
    }
    console.log(t)
    dataPasien.value = response.details
    arrGroup.value = groupMonth(  dataKunjungan.value )
  })

}
const groupMonth = (result: any) =>{
  let sama = false

  let arrGroup: any = [];
  for (let i = 0; i < result.length; i++) {
    sama = false
    for (let x = 0; x < arrGroup.length; x++) {
      if (arrGroup[x].bulan.substr(0,7)== result[i].bulan.substr(0,7)) {
        arrGroup[x].jumlah =arrGroup[x].jumlah+ result[i].jumlah
        arrGroup[x].totalRajal =arrGroup[x].totalRajal+ result[i].totalRajal
        arrGroup[x].pasienEksAsunRJ =arrGroup[x].pasienEksAsunRJ+ result[i].pasienEksAsunRJ
        arrGroup[x].pasienEksPerRJ =arrGroup[x].pasienEksPerRJ+ result[i].pasienEksPerRJ
        arrGroup[x].pasienEksUmumRJ =arrGroup[x].pasienEksUmumRJ+ result[i].pasienEksUmumRJ
        arrGroup[x].pasienAsuransiRJ =arrGroup[x].pasienAsuransiRJ+ result[i].pasienAsuransiRJ
        arrGroup[x].pasienPerRJ =arrGroup[x].pasienPerRJ+ result[i].pasienPerRJ
        arrGroup[x].pasienUmumRJ =arrGroup[x].pasienUmumRJ+ result[i].pasienUmumRJ
        arrGroup[x].totalRanap =arrGroup[x].totalRanap+ result[i].totalRanap
        arrGroup[x].pasienJKNRegRI =arrGroup[x].pasienJKNRegRI+ result[i].pasienJKNRegRI
        arrGroup[x].pasienJKNNonRegRI =arrGroup[x].pasienJKNNonRegRI+ result[i].pasienJKNNonRegRI
        arrGroup[x].pasienEksAsun =arrGroup[x].pasienEksAsun+ result[i].pasienEksAsun
        arrGroup[x].pasienEksPer =arrGroup[x].pasienEksPer+ result[i].pasienEksPer
        arrGroup[x].pasienEksUmumRI =arrGroup[x].pasienEksUmumRI+ result[i].pasienEksUmumRI
        arrGroup[x].pasienAsuransiRI =arrGroup[x].pasienAsuransiRI+ result[i].pasienAsuransiRI
        arrGroup[x].pasienPerRI =arrGroup[x].pasienPerRI+ result[i].pasienPerRI
        arrGroup[x].pasienUmumRI =arrGroup[x].pasienUmumRI+ result[i].pasienUmumRI
        arrGroup[x].pasienLainnya =arrGroup[x].pasienLainnya+ result[i].pasienLainnya
        sama = true;
      }
    }
    if (sama == false) {

      arrGroup.push(result[i])
    }
  }

  return arrGroup
}

const kirimData = async () => {
  if (dataKunjungan.value.length == 0) {
    H.alert('error', 'Data belum ada')
    return
  }

  isLoading2.value = true
  for (let index = 0; index < dataKunjungan.value.length; index++) {
    const rekap = dataKunjungan.value[index];
    await useApi().post(`mkko/api-integrate`,
    {
      url: 'kunjungan',
      method: 'POST',
      data: {
        "tanggal": rekap.bulan,
        "detail": {
          "outpatient_visit": {
            "jumlah_pasien_jkn": {
              "jkn_reguler": rekap.pasienJKNRJ,
              "jkn_naikkelas": rekap.pasienJKNNonRegRI
            },
            "jumlah_pasien_non_jkn_eksekutif": {
              "asuransi": rekap.pasienEksAsunRJ,
              "jaminan_perusahaan": rekap.pasienEksPerRJ,
              "pembayaran_mandiri": rekap.pasienEksUmumRJ,
            },
            "jumlah_pasien_non_jkn_reguler": {
              "asuransi": rekap.pasienAsuransiRJ,
              "jaminan_perusahaan": rekap.pasienPerRJ,
              "pembayaran_mandiri": rekap.pasienUmumRJ,
            }
          },
          "inpatient_visit": {
            "jumlah_pasien_jkn": {
              "jkn_reguler": rekap.pasienJKNRegRI,
              "jkn_naikkelas": rekap.pasienJKNNonRegRI
            },
            "jumlah_pasien_non_jkn_eksekutif": {
              "asuransi": rekap.pasienEksAsun,
              "jaminan_perusahaan": rekap.pasienEksPer,
              "pembayaran_mandiri": rekap.pasienEksUmumRI,
            },
            "jumlah_pasien_non_jkn_reguler": {
              "asuransi": rekap.pasienAsuransiRI,
              "jaminan_perusahaan": rekap.pasienPerRI,
              "pembayaran_mandiri": rekap.pasienUmumRI,
            }
          }
        }
      }
    }
  )
  }
  isLoading2.value = false
}

const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}
fetchData()


</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.p-card .p-card-title {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
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

.tile-grid-v2 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;
    cursor: pointer;

    &:hover,
    &:focus {
      border-color: var(--primary);
      box-shadow: var(--light-box-shadow);
    }

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      >img {
        display: block;
        width: 200px;
        height: 200px;
        min-width: 200px;
      }

      .meta {
        margin-left: 10px;
        line-height: 1.4;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1rem;
          }

          &:nth-child(2) {
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.8rem;
              font-weight: 400;
            }

            .icon-separator {
              position: relative;
              font-size: 4px;
              color: var(--light-text);
              padding: 0 6px;
            }
          }
        }
      }

      .dropdown {
        margin-left: auto;
      }
    }
  }
}
</style>
