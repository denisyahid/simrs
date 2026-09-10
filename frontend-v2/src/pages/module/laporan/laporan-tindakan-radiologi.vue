
<template>
  <div class="page-content-inner">
    <div class="is-navbar">
      <div class="form-layout">
        <div class="form-outer">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3>Laporan Tindakan Radiologi</h3>
              </div>
              <div class="right">

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
                            <div class="column is-4">
                              <VField label="Tanggal Struk" style="margin-bottom: 1rem;" />
                              <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                            <div class="column is-4">
                              <label style=" margin-bottom: 0.5rem;">
                              </label>
                              <VField label="Kelompok Pasien">
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="input.kelompokpasien" :suggestions="d_KelompokPasien"
                                    :optionLabel="'label'" @complete="fetchKelompokPasien($event)" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Kelompok Pasien..." class="mt-2" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-4">
                              <VField label="Dokter DPJP">
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="input.dokterdpjp" :suggestions="d_Dokter" :optionLabel="'label'"
                                    @complete="fetchDokter($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter DPJP..."
                                    class="mt-2" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12">
                          <div class="columns is-multiline">
                            <div class="column is-4 mt-2">
                              <VField label="Cari">
                                <VControl class="prime-auto">
                                  <VInput v-model="input.search" placeholder="Cari Berdasrkan Nama..."></VInput>
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-4">
                              <VField label="Dokter Pemeriksa">
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_Dokter"
                                    :optionLabel="'label'" @complete="fetchDokter($event)" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Dokter pemeriksa..." class="mt-2" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-4">
                              <VButton type="button" icon="feather:search" :loading="isLoading" color="success" raised
                                @click="cariRiwayat()" style="margin-top: 30px;"> Cari
                              </VButton>
                              <VButton color="warning" icon="fas fa-file-excel" raised
                                @click="exportExcel()" style="margin-left: 30px; margin-top: 30px;">
                                Export to Excel </VButton>
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
                      <DataTable :value="d_LaporanRadiologi" :paginator="true" :rows="5"
                        :rowsPerPageOptions="[5, 10, 25, 50, 100, 200, 500, 1000]"
                        class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
                        <Column field="no" header="#" frozen></Column>
                        <Column field="tglpelayanan" header="Tanggal" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="tglexpertise" header="Expertise" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="noregistrasi" header="No Reg" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="nocm" header="No RM" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="jeniskelamin" header="Jenis Kelamin" frozen :sortable="true"
                          style="min-width: 200px">
                        </Column>
                        <Column field="ruangan" header="Poli" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="kelompokpasien" header="Tipe" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="namaproduk" header="Tindakan" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="jumlah" header="Qty" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="dokterdpjp" header="Dokter DPJP" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="dokter" header="Dokter Pemeriksa" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="radiografer" header="Radiografer" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="hargajual" header="Harga" frozen :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="total" header="Total" frozen :sortable="true" style="min-width: 200px">
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
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import moment from 'moment'
const input: any = ref({})
const d_Departement: any = ref([]);
const d_Ruangan: any = ref([]);
const d_KelompokPasien: any = ref([]);
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

const item: any = ref({
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
})


useHead({
  title: 'Laporan Tindakan Radiologi- ' + import.meta.env.VITE_PROJECT,
})
async function cariRiwayat() {
  let object: any = {}

  object = input.value

  isLoading.value = true;
  let startDate = moment(item.value.filterTgl.start).format('YYYY-MM-DD');
  let endDate = moment(item.value.filterTgl.end).format('YYYY-MM-DD');
  let kelompokpasien = input.value.kelompokpasien ? input.value.kelompokpasien.value : "";
  let dokterdpjp = input.value.dokterdpjp ? input.value.dokterdpjp.value : "";
  let dokterPemeriksa = input.value.dokterPemeriksa ? input.value.dokterPemeriksa.value : "";
  let search = input.value.search ? input.value.search : "";
  useApi().get(
    `/radiologi/laporan-tindakana-radiologi?tglAwal=${startDate}&tglAkhir=${endDate}&dokterdpjp=${dokterdpjp}&dokter=${dokterPemeriksa}&kelompokpasien=${kelompokpasien}&search=${search}`).then((response: any) => {
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
const setAutoFill = () => {
  input.value.tglAwal = new Date()
  input.value.tglAkhir = new Date()
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
