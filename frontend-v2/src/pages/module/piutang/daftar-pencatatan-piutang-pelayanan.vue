<template>
  <!-- modal rekanan -->
  <VModal :open="modalInput" title="Tambah Asal Rujukan" actions="right" @close="modalInput = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField label="Penjamin" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:command">
                <Multiselect mode="single" class="is-rounded" v-model="item.rekanan" :options="d_Rekanan"
                  placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT" />
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:plus" @click="save()" :loading="isLoadingTT" color="primary" raised>Simpan</VButton>
    </template>
  </VModal>
  <!-- end modak rekanan -->
  <VCard radius="rounded">
    <div class="column is-12 p-0">
      <div class="columns column">
        <h3 class="title is-5 mb-2 mr-1">Daftar Pencatatan Piutang Pasien ({{  dataSource.length }} Data)</h3>
      </div>
      <div class="columns is-multiline">
        <div class="column is-8 is-pulled-right">
          <div class="columns is-multiline is-pulled-right">
            <div class="flex flex-wrap align-items-center gap-2">
              <a type="button" class="is-pulled-right mr-3" color="info" outlined raised @click="exportExcel()"
                v-if="dataSource.length != 0">
                <span>
                  <i class="fas fa-file-excel"></i>
                  Export to Excel
                </span>
              </a>
              <a @click="collection()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
                <span>
                  <i class="fa fa-plus"></i> Collection
                </span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="columns is-multiline mt-5">
        <div class="column is-8">
          <div class="tile-grid tile-grid-v2">
            <div class="flex-list-inner mb-2 mt-5" v-if="isLoading">
              <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
                <div class="tile-grid-item" style="padding:0.75rem">
                  <VPlaceloadWrap style="padding:3.5rem 0rem">
                    <VPlaceloadAvatar size="small" />
                    <VPlaceloadText class="mx-5" />
                    <VPlaceload class="mx-2" disabled />
                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                    <VPlaceload class="mx-2" />
                  </VPlaceloadWrap>
                </div>
              </div>
            </div>
            <div class="flex-list-inner" v-else-if="dataSource == 0">
              <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
                <template #image>
                  <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderSection>
            </div>
            <div v-else class="columns is-multiline">
              <div v-for="item in dataSource" :key="item.id" class="column is-12">
                <div class="tile-grid-item" style="padding:0.75rem">
                  <VDropdown icon="feather:more-vertical" spaced right class="is-pulled-right">
                    <template #content>
                      <a role="menuitem" @click="editRekanan(item)" class="dropdown-item is-media">
                        <div class="icon">
                          <i aria-hidden="true" class="lnir lnir-checkmark-circle"></i>
                        </div>
                        <div class="meta">
                          <span>Edit Rekanan</span>
                        </div>
                      </a>
                    </template>
                  </VDropdown>
                  <div class="tile-grid-item-inner">
                    <div class="meta">
                      <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" squared bordered />
                      <div class="label">
                        <span class="dark-inverted">{{ item.namaPasien }}({{ item.noRegistrasi }})</span>
                        <span class="dark-inverted">{{ item.tglTransaksi }}</span>
                        <span class="dark-inverted">{{ item.jenisPasien }} / {{ item.rekanan }}</span>
                      </div>
                    </div>
                    <div class="xx">
                      <p style="font-size: 0.8rem; padding-top: 0px;margin-top:2px;display:flex">
                      <table class="w-100">
                        <tr>
                          <td>Total Tagihan</td>
                          <td style="width:10px">:</td>
                          <td style="font-weight:bold"> {{ item.totalBilling }}</td>
                        </tr>
                        <tr>
                          <td>Total Bayar</td>
                          <td style="width:10px">:</td>
                          <td style="font-weight:bold"> {{ item.totalBayar }}</td>
                        </tr>
                        <tr>
                          <td>Total Klaim</td>
                          <td>:</td>
                          <td style="font-weight:bold"> {{ item.totalKlaim }}</td>
                        </tr>
                        <tr>
                          <td>Sisa Belum Dibayar</td>
                          <td>:</td>
                          <td style="font-weight:bold"> {{ item.sisautang }}</td>
                        </tr>
                        <tr>
                          <td>Total Tidak Diklaim</td>
                          <td>:</td>
                          <td style="font-weight:bold"> {{ item.totaltidakdiklaim }}</td>
                        </tr>
                      </table>
                      <table class="w-100">
                        <tr>
                          <td>Ruangan</td>
                          <td>:</td>
                          <td style="font-weight:bold"> {{ item.ruangan }}</td>
                        </tr>
                        <tr>
                          <td>kelas Rawat</td>
                          <td>:</td>
                          <td style="font-weight:bold"> {{ item.kelasRawat }}</td>
                        </tr>
                        <tr>
                          <td>Umur</td>
                          <td>:</td>
                          <td style="font-weight:bold"> {{ item.umur }}</td>
                        </tr>
                        <tr>
                          <td>Tanggal Transaksi</td>
                          <td>:</td>
                          <td style="font-weight:bold"> {{ item.tglTransaksi }}</td>
                        </tr>
                        <tr>
                          <td>NO Posting</td>
                          <td>:</td>
                          <td style="font-weight:bold"> {{ item.noposting }}</td>
                        </tr>
                      </table>
                      </p>

                    </div>
                  </div>
                  <div style="justify-content: space-between;display: flex;margin-top:10px">
                    <VTag :label="item.status" color="info" style="margin-left: 10px; margin-top: 10px;" rounded
                      elevated />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-4">
          <div class="search-widget">
            <div class="field">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                    <template #default="{ inputValue, inputEvents }">
                      <VField addons>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                        </VControl>
                        <VControl>
                          <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                        </VControl>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12 ">
                  <VField class="is-autocomplete-select">
                    <VControl icon="feather:search" class="prime-auto-select">
                      <AutoComplete v-model="item.kelompokpasien" :suggestions="d_KelompokPasien"
                        @complete="fetchKelompokPasien($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Kelompok Pasien..." />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField class="is-autocomplete-select">
                    <VControl icon="feather:search" class="prime-auto-select">
                      <AutoComplete v-model="item.rekanan" :suggestions="d_Rekanan" @complete="fetchRekanan($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama rekanan..." />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField>
                    <VInput placeholder="Cari..." class="mt-1" v-model="item.search"></VInput>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl class="prime-auto-select">
                      <Dropdown v-model="item.status" :options="d_Status" :optionLabel="'label'" placeholder="Pilih data"
                        style="width: 100%;" showClear :filter="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VButton @click="fetchData()" :loading="isLoading" type="button" icon="feather:search"
                    class="is-fullwidth mr-3" color="info" raised> Apply Filters
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </VCard>
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
import * as XLSX from "xlsx";
useHead({
  title: 'Laporan Pencatatan Pelayanan- ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
})
const d_KelompokPasien: array = ref([]);
const d_Rekanan: array = ref([]);
const dataSource: any = ref([]);
const d_Status: array = ref([
  {
    label: 'Piutang',
    value: 'Piutang'
  },
  {
    label: 'Collecting',
    value: 'Collecting'
  },
  {
    label: 'Lunas',
    value: 'Lunas'
  }
]);
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
const isLoading: boolean = ref(false);
const isLoadingTT: boolean = ref(false);
const remakeData: any = ref([]);
const modalInput = ref(false);
const router = useRouter();
const fetchKelompokPasien = async (filter: any) => {
  await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}
const fetchRekanan = async (filter: any) => {
  await useApi().get(`emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Rekanan.value = response
  })
}

const fetchData = async () => {
  isLoading.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let kelompokpasien = item.value.kelompokpasien ? `&kelompokpasienfk=${item.value.kelompokpasien.value}` : ''
  let rekanan = item.value.rekanan ? `&penjaminID=${item.value.rekanan.value}` : ''
  let nama = item.value.namaPasien ? `&namaPasien=${item.value.namaPasien}` : ''
  let noregistrasi = item.value.noregistrasi ? `&noregistrasi=${item.value.noregistrasi}` : ''
  let nocm = item.value.nocm ? `&nocm=${item.value.nocm}` : ''
  let search = item.value.search ? `&search=${item.value.search}` : ''
  let  status = item.value.status ? `&status=${item.value.status.value}` :''

  await useApi().get(`piutang/daftar-piutang-layanan?${tglAwal}${tglAkhir}${kelompokpasien}${nama}${noregistrasi}${nocm}${rekanan}${search}${status}`).then((response: any) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1,
        element.tglTransaksi = moment(element.tglTransaksi).format('DD-MMM-YYYY'),
        element.totalBilling = formatRp(element.totalBilling, 'Rp.'),
        element.totaltidakdiklaim = formatRp(element.totaltidakdiklaim, 'Rp.'),
        element.totalKlaim = formatRp(element.totalKlaim, 'Rp.'),
        element.totalBayar = formatRp(element.totalBayar, 'Rp.'),
        element.sisautang = formatRp(element.sisautang, 'Rp.')
    });
    dataSource.value = response
  })
  isLoading.value = false
}
const exportExcel = () => {
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      No: e.no, NoRegistrasi: e.noRegistrasi, NamaPasien: e.namapasien,
      TanggalTransaksi: e.tglTransaksi, JenisPasien: e.jenisPasien,
      Rekanan: e.rekanan, TotalBilling: e.totalBilling,
      TotalTidakDiklaim: e.totaltidakdiklaim, TotalKlaim: e.TotalKlaim,
      TotalTidakDiklaim: e.totaltidakdiklaim, TotalKlaim: e.TotalKlaim,
      TotalBayar: e.totalBayar, SisaUtang: e.sisautang,
      TotalBayar: e.umur, Status: e.status,
      NoCollect: e.noposting,
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
const editRekanan = async (e: any) => {
  e.loading = true
  await fetchRekanan({ query: "" });
  item.value.rekanan = e.idrekanan
  modalInput.value = true
  e.loading = false
}
const save = async () => {

}
const collection = () => {
  H.cacheHelper().set('periodeTransaksiPencatatanPiutangDaftarLayanan', {
    key: '',
    fileName: item.fileName,
    start: moment(item.value.qFilterTgl.start).format('YYYY-MM-DD'),
    end: moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  })
  router.push({
    name: 'module-piutang-collection-piutang',
    query: {
      start: moment(item.value.qFilterTgl.start).format('YYYY-MM-DD'),
      end: moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
    },
  })
}
fetchData();
</script>
<style lang="scss">
.search-widget {
  flex: 1;
  display: inline-block;
  width: 100%;
  padding: 12px;
  background-color: var(--white);
  border-radius: 16px;
  border: 1px solid var(--fade-grey-dark-3);
  transition: all 0.3s;
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

@import '/@src/scss/module/piutang/piutang.scss';

.form-layout .form-outer .form-body {
  padding: 0;
}
</style>
