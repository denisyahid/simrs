
<template>
  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-4 pt-0 pb-0">
                <span>Periode</span>
                <VDatePicker v-model="item.qFilterTgl" color="pink" class="pt-2">
                  <template #default="{ inputValue, inputEvents }">
                    <!-- <VField addons> -->
                      <VControl icon="feather:calendar" addons>
                        <VInput :value="inputValue" class="input-calendar" v-on="inputEvents" />
                      </VControl>
                    <!-- </VField> -->
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-8 pt-0 pb-0">
                <span>Dokter</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.dokterfk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="field">
            <div class="control">
              <div class="columns is-multiline">
                <div class="column is-11 ">
                  <input type="text" v-model="item.namaPasien" v-on:keyup.enter="fetchData()" class="input"
                    placeholder="Search..." />
                </div>
                <div class="column is-1" style="margin-left: auto !important;">
                  <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                    @click="fetchData()" :loading="isPlaceLoad">
                  </VIconButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>

  <div class="column">
    <VCard>
      <h3 class="title is-5 mb-2">Laporan Antrian Online</h3>
      <div class="column" v-if="isPlaceLoad">
        <VPlaceloadWrap v-for="data in 25">
          <VPlaceload class="mx-2 mb-3" />
          <VPlaceload class="mx-2" />
        </VPlaceloadWrap>
      </div>
      <div class="column" v-else>
        <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan."
          subtitle="Silakan filter pencarian di tanggal lain" larger>
          <template #image>
            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
          </template>
        </VPlaceholderPage>
        <div v-else>
          <div class="column is-12">
            <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
              :rowsPerPageOptions="[5, 10, 25]" scrollable
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
  
              <template #header>
                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                  <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
                    to Excel 
                  </VButton>
                  <VButton color="primary" class="mr-4 mb-3" icon="fas fa-sync" raised @click="syncData()" style="display: none;">
                    Sync Data
                  </VButton>
                </div>
              </template>
              <Column field="no" header="No" frozen></Column>
              <Column field="tanggal" header="TGL Registrasi" :sortable="true" style="min-width: 140px"></Column>
              <Column field="kodebooking" header="Kode Booking" :sortable="true" style="min-width: 150px"></Column>
              <Column field="nokartupeserta" header="No BPJS" :sortable="true" style="min-width: 200px"></Column>
              <Column field="noreferensi" header="Referensi" :sortable="true" style="min-width: 200px"></Column>
              <Column field="norm" header="NoCM" :sortable="true" style="min-width: 200px"> </Column>
              <Column field="sumberdata" header="Sumber" :sortable="true" style="min-width: 200px"> </Column>
              <Column field="kodedokter" header="Kode Dokter" :sortable="true" style="min-width: 200px"></Column>
              <Column field="kelompok" header="Peserta" :sortable="true" style="min-width: 200px"></Column>
              <Column field="nik" header="NIK" :sortable="true" style="min-width: 200px"></Column>
              <Column field="status" header="Status" :sortable="true" style="min-width: 200px"></Column>
            </DataTable>
          </div>
          <div class="column is-12 mt-5">
            <DataTable :value="dataSourceJumlah" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
              :rowsPerPageOptions="[5, 10, 25]" scrollable loading="isLoadingTable"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
              <Column field="jmlantreanjkn" header="JML Antrean JKN"></Column>
              <Column field="jmlantreannonjkn" header="JML Antrean Non JKN"></Column>
              <Column field="jmlantreanbysumberbridging" header="Jumlah Antrean By Sumber (Bridging)"></Column>
              <Column field="jmlantreanbysumbermjkn" header="Jumlah Antrean By Sumber (MJKN)"></Column>
              <Column field="totalsepterbit" header="Total SEP Terbit"></Column>
              <Column field="jmlantreanlengkapbybridging" header="Jumlah Antren Lengkap By Sumber (Bridging)"></Column>
              <Column field="jmlantreanlengkapbymjkn" header="Jumlah Antren Lengkap By Sumber (MJKN)"></Column>
              <Column field="capaianantrol" header="Capaian Antrol">
                <template #body="slotProps">
                    {{ slotProps.data.capaianantrol }}%
                </template>
              </Column>
              <Column field="capaianmjkn" header="Capaian MJKN">
                <template #body="slotProps">
                    {{ slotProps.data.capaianmjkn }}%
                </template>
              </Column>
            </DataTable>
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
import * as XLSX from "xlsx";
useHead({
  title: 'Laporan Antrian Pasien Antrol - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const item: any = ref({
  qFilterTgl: new Date()
})
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const remakeData: any = ref([])
let dataSource: any = ref([])
let dataSourceJumlah: any = ref([])
let dataSourceKuota: any = ref([])
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const isLoadingTable: bool = ref(false);

const fetchData = async () => {
  isPlaceLoad.value = true
  let tanggal = moment(item.value.qFilterTgl).format('YYYY-MM-DD');
  let tglAwalforq = moment(item.value.qFilterTgl).format('YYYY-MM-DD 00:00:00');
  let tglAkhirforq = moment(item.value.qFilterTgl).format('YYYY-MM-DD 23:59:59');
  let tglAwal = 'tanggal=' + tanggal + '&tglAwal=' + tglAwalforq + '&tglAkhir=' + tglAkhirforq
  let dokter = ((item.value.dokterfk == undefined || item.value.dokterfk.value == undefined) ? "" : item.value.dokterfk.value)
  let search = item.value.namaPasien == undefined ? " " : item.value.namaPasien
  var kdBooking = ""
  if (item.value.kdBooking != undefined) {
      var kdBooking = "&kdBooking=" + item.value.kdBooking
  }

  var kelompok = ""
  if (item.value.kelompokpasien != undefined) {
      kelompok = "&kelId=" + item.value.kelompokpasien
  }

  item.value.totalLengkapByBridging = 0;
  item.value.totalLengkapByMJKN = 0;
  item.value.totalSepTerbitByBridging = 0;
  item.value.totalSepTerbitByMJKN = 0;
  item.value.capaianAntrol = 0;
  item.value.capaianMJKN = 0;
  item.value.totalJkn = 0;
  item.value.jmlAntreanNonJKN = 0;
  item.value.jmlAntreanBySumber = 0;
  item.value.jmlAntreanByMJKN = 0;
  item.value.jmlAntreanJKN = 0;
  item.value.dataDaftarPasien = null;
  item.value.dataMonitoring = null;

  var dt_sep = {
      url: "Monitoring/Kunjungan/Tanggal/" + tanggal + "/JnsPelayanan/2",
      method: "GET",
      data: null,
  };

  await useApi().postBPJS('bridging/bpjs/tools', dt_sep).then(function (x0) {
    console.log("Monitoing BPJS", x0);
    
      if (x0.metaData.code == 200) {
          var res0 = [];
          res0 = x0.response.sep;
          res0.forEach(elm0 => {
              if (elm0.poli != 'IGD') {
                  item.value.totalSepTerbitByBridging += 1;
              }

          })
      }
  });

  var dtreg = [];
  await useApi().get(`pelayanan/get-laporan-antrian-online?${tglAwal}&objectpegawaifk=${dokter}&q=${search}`).then((x1: any) => {
    console.log("GET LAPORAN", x1.length);
    
    if (x1.length > 0) {
        dtreg = x1;
    }
  })

  var data = {
      "url": "antrean/pendaftaran/tanggal/" + tanggal,
      "method": "GET",
      "data": null,
      "jenis": "antrean"
  }

  await useApi().postBPJS('bridging/bpjs/tools', data).then(function (x) {
    if (x.metaData.code == 200) {

        console.log(x.response)
        var res = [];

        res = x.response;

        var dtapop = [];
        var no = 1;

        console.log('JUMLAH RESPONSE', res.length)

        res.forEach(elm => {
          var jeniskunjungan = "";
          switch (elm.jeniskunjungan) {
              case 1:
                  jeniskunjungan = "Rujukan"
                  break;
              case 2:
                  jeniskunjungan = "Rujukan Internal"
                  break;
              case 3:
                  jeniskunjungan = "Kontrol"
                  break;
              case 4:
                  jeniskunjungan = "Rujukan Antar RS"
                  break;
              default:
                  break;
          }

          var kodebooking = elm.kodebooking;
          // if (elm.sumberdata == "Bridging Antrean") {
          //     kodebooking = elm.kodebooking.replace('B', '');
          // }

          let dtregfilter = dtreg.filter(function (flt) {
            return flt.noregistrasi == kodebooking || flt.noreservasi == kodebooking
          });

          

          if (elm.status != 'Batal') {

              var nm_pasien = "-";
              var nm_ruangan = "-";
              var nm_rekanan = "-";
              var no_registrasifk = "";
              if (dtregfilter) {
                console.log('FILTER', dtregfilter)
                  nm_pasien = dtregfilter.namapasien;
                  nm_ruangan = dtregfilter.namaruangan;
                  nm_rekanan = dtregfilter.namarekanan;
                  no_registrasifk = dtregfilter.noregistrasifk

                  //get jml antrean JKN
                  if (elm.ispeserta == true &&
                      elm.status != 'Batal'
                  ) {
                    item.value.jmlAntreanJKN += 1;
                  }
                  //get jml antrean Non JKN
                  if (elm.ispeserta == false &&
                      elm.status != 'Batal'
                  ) {
                    item.value.jmlAntreanNonJKN += 1;
                  }
                  //get jml antrean by sumber mjkn
                  if (elm.ispeserta == true &&
                      elm.kodepoli != "IGD" &&
                      elm.status != 'Batal' &&
                      elm.sumberdata == 'Mobile JKN'
                  ) {
                    item.value.jmlAntreanByMJKN += 1;
                  }
                  //get jml antrean by sumber bridging antrean
                  if (elm.ispeserta == true &&
                      elm.kodepoli != "IGD" &&
                      elm.status != 'Batal' &&
                      elm.sumberdata == 'Bridging Antrean'
                  ) {
                    item.value.jmlAntreanBySumber += 1;
                  }
                  //get jml antrean lengkap by sumber bridging antrean
                  if (elm.ispeserta == true &&
                      elm.status == "Selesai dilayani" &&
                      elm.kodepoli != "IGD" &&
                      elm.status != 'Batal' &&
                      elm.sumberdata == "Bridging Antrean"
                  ) {
                    item.value.totalLengkapByBridging += 1;
                  }
                  //get jml antrean lengkap by sumber MJKN
                  if (elm.ispeserta == true &&
                      elm.status == "Selesai dilayani" &&
                      elm.kodepoli != "IGD" &&
                      elm.status != 'Batal' &&
                      elm.sumberdata == "Mobile JKN"
                  ) {
                    item.value.totalLengkapByMJKN += 1;
                  }


                  dtapop.push({
                      "no": no++, 
                      "tanggal": elm.tanggal,
                      "kodebooking": elm.kodebooking,
                      "jeniskunjungan": jeniskunjungan,
                      "kodepoli": elm.kodepoli,
                      "kodedokter": elm.kodedokter,
                      "noreferensi": elm.nomorreferensi == "" ? "-" : elm.nomorreferensi,
                      "norm": elm.norekammedis,
                      "nik": elm.nik,
                      "nokartupeserta": elm.nokapst,
                      "sumberdata": elm.sumberdata,
                      "kelompok": elm.ispeserta == true ? "JKN" : "NON JKN",
                      "status": elm.status,
                      "namapasien": nm_pasien,
                      "namaruangan": nm_ruangan,
                      "namarekanan": nm_rekanan,
                      "noregistrasifk": no_registrasifk
                  })
              }

          }

        });

        item.value.capaianAntrol = Math.round(((item.value.totalLengkapByBridging + item.value.totalLengkapByMJKN) / item.value.totalSepTerbitByBridging) * 100)
        item.value.capaianMJKN = Math.round((item.value.totalLengkapByMJKN / item.value.totalSepTerbitByBridging) * 100)

        dataSource.value = dtapop; 


        var dtamon = [
            {
                'jmlantreanjkn': item.value.jmlAntreanJKN,
                'jmlantreannonjkn': item.value.jmlAntreanNonJKN,
                'jmlantreanbysumberbridging': item.value.jmlAntreanBySumber,
                'jmlantreanbysumbermjkn': item.value.jmlAntreanByMJKN,
                'totalsepterbit': item.value.totalSepTerbitByBridging,
                'jmlantreanlengkapbybridging': item.value.totalLengkapByBridging,
                'jmlantreanlengkapbymjkn': item.value.totalLengkapByMJKN,
                'capaianantrol': item.value.capaianAntrol,
                'capaianmjkn': item.value.capaianMJKN
            }
        ];
        dataSourceJumlah.value = dtamon;
    }
    isPlaceLoad.value = false;
  })
  // isPlaceLoad.value = false

}
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const exportExcel = () => {
  console.log(dataSource.value)
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      NamaPasien: e.namapasien, NoRM: e.nocm, JenisKelamin: e.jeniskelamin,
      KodeBooking: e.kodebooking, NIK: e.nik, NoNPJS: e.nobpjs, TGLReservasi: e.tglreservasi, Alamat: e.alamat,
      Dokter: e.dokter,
    }
  })
  const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
  const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'laporan_antrol');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  const _url = window.URL.createObjectURL(data)
  window.open(_url, EXCEL_EXTENSION).focus()
  exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

const syncData = () => {
  console.log(dataSource.value.length);
  
  if (dataSource.value.length == 0) return

  let pass = prompt("Masukan password", "");
  if (pass != null) {
      if (pass == 'antrol') {
          sendANTROL(dataSource.value)
      } else {
          toastr.error('Password Salah')
      }
  }
}

const sendANTROL = async (dataSource) => {
  for (let x = 0; x < dataSource.length; x++) {
    const element = dataSource[x];
    if (element.status != 'Selesai dilayani') {
      await repeatSendTaskIdNew(element.kodebooking, element.tanggal, element.noregistrasifk);
    }
    if(x == dataSource.length) {
      fetchData();
    }
  }
}

const repeatSendTaskIdNew = async(kodebooking, dt_tanggal = null, norec_pd = null) => {
  var obj = {
      "url": "antrean/getlisttask",
      "data": {
          "kodebooking": kodebooking
      },
      "method": "POST",
      "jenis": "antrean"
  }
  isPlaceLoad.value = true;
  await useApi().postBPJS('bridging/bpjs/tools', obj).then(async function (e) {
      var resdata = 1;
      // console.log("Res GET Taskid", e);
      var waktu = dt_tanggal;
      if(e) {
        if (e.metaData.code == 200) {
          resdata = e.response.length;
          waktu = moment(e.response[resdata - 1].wakturs.replace(' WIB', ''), "DD/MM/YYYY HH:mm")._d
        } else {
            waktu = new Date(dt_tanggal).getTime();
        }


        isPlaceLoad.value = false;
        for (let index = resdata; index < 8; index++) {
            waktu = moment(waktu).add(10, 'minutes')
            var obj2 = {
                "url": "antrean/updatewaktu",
                "jenis": "antrean",
                "method": "POST",
                "data":
                {
                    "kodebooking": kodebooking,
                    "taskid": index,
                    "waktu": new Date(waktu).getTime()
                }
            }

            isPlaceLoad.value = true;
            await useApi().postBPJS('bridging/bpjs/tools', obj2).then(async function (ela) {
                if (ela.metaData.code == 200) {
                    if (norec_pd != null) {
                        await saveMonitoringTaksId(norec_pd, index, parseInt(new Date(waktu).getTime()), true);
                    }
                }
                isPlaceLoad.value = false;
            })

        }
      }else [
        isPlaceLoad.value = false
      ]
  });
}

const saveMonitoringTaksId = async(noregistrasifk, taskid, waktu, statuskirim) => {
  var json = {
      "noregistrasifk": noregistrasifk,
      "taskid": taskid,
      "waktu": waktu,
      "statuskirim": statuskirim
  }
  await useApi().postNoMessage('bridging/antrol/saveMonitoringTaksId', json).then(async function (e) {
      // await loadData()
  })
}



fetchData()
</script>
