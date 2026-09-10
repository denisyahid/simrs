
<template>
  <div class="page-content-inner">
    <div class="is-navbar">
      <div class="form-layout">
        <div class="form-outer">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3>Laporan Rekapitulasi</h3>
              </div>
              <div class="right">
                <div class="buttons">
                  <VButton icon="lnir lnir-arrow-left rem-100" :to="{ name: 'module-dashboard-registrasi' }" light
                    dark-outlined>
                    Kembali
                  </VButton>
                  <VButton type="button" icon="feather:search" :loading="isLoading" color="success" raised
                    @click="cariRiwayat()"> Cari
                  </VButton>
                </div>
              </div>
            </div>
          </div>
          <div class="form-body">
            <!--Fieldset-->
            <div class="form-fieldset">
              <VCard>
                <div class="column c-title pt-2 mb-0">
                  <div class="fieldset-heading">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Filter Pencarian</h1>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <div class="columns is-multiline mb-3 mt-2">
                            <div class="column is-6">
                              <VField label="Tanggal Struk" style="margin-bottom: 1rem;" />
                              <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                  <VField addons>
                                    <VControl icon="feather:calendar">
                                      <VInput :value="inputValue.start" v-on="inputEvents.start"/>
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
                            <div class="column is-6">
                              <VField label="Dokter Pemeriksa">
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_Dokter"
                                    :optionLabel="'label'" @complete="fetchDokter($event)" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" :showClear="true"
                                    placeholder="Dokter pemeriksa..." class="mt-2" @change="cariDokter()"/>
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12">
                          <div class="columns is-multiline">
                            <div class="column is-3 mt-2">
                              <VButton color="warning" class="mt-3 mr-4" fullwidth="true" icon="fas fa-file-excel" raised
                                @click="exportExcel()">
                                Export to Excel </VButton>
                            </div>
                            <div class="column is-3 mt-2">
                              <VButton color="info" class="mt-3 mr-4" fullwidth="true" icon="fas fa-file-pdf" raised
                                @click="cetakRekap()">
                                Cetak Rekapitulasi </VButton>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </VCard>

            </div>
            <!--Fieldset-->
            <div class="form-fieldset">
              <div class="column" v-if="isLoading">
                <VPlaceloadWrap v-for="data in 10">
                  <VPlaceload class="mx-2 mb-3" />
                </VPlaceloadWrap>
              </div>
              <div class="column" v-else>
                <VPlaceholderPage v-if="d_LaporanRadiologi.length == 0" title="Data Tidak di Temukan."
                  subtitle="Silakan gunakan filter lain" larger>
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                  </template>
                </VPlaceholderPage>
                <div v-else>
                  <div class="column mt-5">
                    <div class="column-12">
                      <DataTable :value="d_LaporanRadiologi" rowGroupMode="rowspan" groupRowsBy="detailjenisproduk" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                        class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
                        <Column field="no" header="#" frozen></Column>
                        <Column field="detailjenisproduk" header="Nama Kelompok" :rowspan="4" frozen :sortable="true" style="min-width: 200px"></Column>
                        <Column field="namaproduk" header="Nama Pelayanan" :rowspan="4" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="dokter" header="Dokter Pemeriksa" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="qty" header="Qty" frozen :sortable="true" style="min-width: 200px">
                        </Column>
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
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useUserSession } from '/@src/stores/userSession'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import moment from 'moment'
const input: any = ref({})
const d_Departement: any = ref([]);
const d_Ruangan: any = ref([]);
const d_KelompokPasien: any = ref([]);
const userLogin = useUserSession().getUser()
let isLoading = ref(false)
const d_LaporanRadiologi: any = ref([])
const dataSourceICD9 = ref([])
const remakeData: any = ref([])
import * as XLSX from "xlsx";
const d_Dokter: any = ref([])



const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

// Untuk mendapatkan tanggal 1 di input start
let startDate = new Date()
startDate = startDate.setDate(1)


const item: any = ref({
  filterTgl: reactive({
    start: startDate,
    end: new Date(),
  }),
})


useHead({
  title: 'Laporan Pendaftaran - ' + import.meta.env.VITE_PROJECT,
})

function cariDokter() {
  console.log(input.value.dokterPemeriksa)
  if(input.value.dokterPemeriksa.value){
    cariRiwayat()
  }
}

async function cariRiwayat() {
  let object: any = {}

  object = input.value

  isLoading.value = true;
  let startDate = moment(item.value.filterTgl.start).format('YYYY-MM-DD');
  let endDate = moment(object.tglAkhir).format('YYYY-MM-DD');
  // let kelompokpasien = input.value.kelompokpasien ? input.value.kelompokpasien.value : "";
  // let dokterdpjp = input.value.dokterdpjp ? input.value.dokterdpjp.value : "";
  let dokterPemeriksa = input.value.dokterPemeriksa ? input.value.dokterPemeriksa.value : "";//userLogin.pegawai.id;
  const countElement = []
  useApi().get(
    `/radiologi/laporan-rekap-tindakan-radiologi?tglAwal=${startDate}&tglAkhir=${endDate}&dokter=${dokterPemeriksa}`).then((response: any) => {
      response.forEach((element: any, i: any) => {
        let total = element.hargajual * element.jumlah;
        element.no = i + 1,
          element.hargajual = H.formatRp(element.hargajual, 'Rp. '),
          element.total = H.formatRp(total, 'Rp. ')
      });
      d_LaporanRadiologi.value = response;
      isLoading.value = false
    }).catch((e: any) => {
      isLoading.value = false
    })

}

console.log(input.value)

const setAutoFill = () => {
  input.value.tglAwal = new Date()
  input.value.tglAkhir = new Date()
  // input.label.dokterPemeriksa = userLogin.pegawai.namaLengkap
}

const fetchKelompokPasien = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const exportExcel = () => {
  remakeData.value = d_LaporanRadiologi.value.map((e: any) => {
    return {
      No: e.no, TanggalPelayanan: e.tglpelayanan, Expertise: e.tglexpertise,
      NoReg: e.noregistrasi, NoRM: e.nocm, NamaPasien: e.namapasien, JenisKelamin: e.jeniskelamnin,
      Ruangan: e.ruangan, KelompokPasien: e.kelompokpasien, Tindakan: e.namaproduk, Jumlah: e.jumlah,
      DokterDPJP: e.dokterdpjp, DokterPemeriksa: e.dokter, Radiografer: e.radiografer, Harga: e.hargajual,
      Total: e.total
    }
  })
  const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
  const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'products');
}
const saveAsExcelFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  const _url = window.URL.createObjectURL(data)
  window.open(_url, EXCEL_EXTENSION).focus();
}

const cetakRekap = () => {
  let object: any = {}
  object = input.value
  let startDate = moment(item.value.filterTgl.start).format('YYYY-MM-DD');
  let endDate = moment(object.tglAkhir).format('YYYY-MM-DD');
  let dokterPemeriksa = input.value.dokterPemeriksa ? input.value.dokterPemeriksa.value :""
  H.printBlade(`report/radiologi/cetak-rekap-expertise?tglAwal=${startDate}&tglAkhir=${endDate}&dokter=${dokterPemeriksa}`)
}

watch(
  () => item.value.filterTgl,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      cariRiwayat()
    }
  }
)

cariRiwayat();
setAutoFill();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
  max-width: 1300px;
  margin: 0 auto;
}

.form-fieldset {
  padding: 10px 0;
  max-width: 100%;
  margin: 0 auto;
}

.table-pi {
  width: 1400px;
  border: 1px solid #929090;
}

.table-scroll {
  overflow-x: scroll;
}

.date {
  background-color: #9b9b9b;
  color: #fff;
}
</style>
