<template>
  <div class="columns is-multiline">
    <div class="column is-8">
      <div class="columns is-multiline">
        <div class="column is-12">
          <VCard>
            <div class="columns is-multiline">
              <div class="column is-12">
                <h3 class="title is-5 mb-2">Daftar Collecting Piutang
                </h3>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <CardCountRev icon="/images/simrs/icon-registrasi.png" straight
                  :total="H.formatRp(item.totalKlaim ?? 0, 'Rp')" label="Total Klaim" />
              </div>
              <div class="column is-3">
                <CardCountRev icon="/images/simrs/icon-registrasi.png" straight
                  :total="H.formatRp(item.totaldiskon ?? 0, 'Rp')" label="Total Diskon" />
              </div>
              <div class="column is-3">
                <CardCountRev icon="/images/simrs/icon-registrasi.png" straight
                  :total="H.formatRp(item.totalSudahDibayar ?? 0, 'Rp')" label="Total Sudah Dibayar" />
              </div>
              <div class="column is-3">
                <CardCountRev icon="/images/simrs/icon-antrian.png" straight :total="item.totalPasien"
                  label="Total Pasien" />
              </div>
            </div>
          </VCard>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="flex-list-inner">
            <VCard>
              <div class="user-grid user-grid-v2">
                <div class="columns is-multiline" v-if="isLoading">
                  <div class="column is-6" v-for="key in 3" :key="key">
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
                </div>
                <VPlaceholderPage v-else-if="dataSource.length == 0" title="Data Tidak di Temukan."
                  subtitle="Data Tidak Ditemukan !" larger>
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                  </template>
                </VPlaceholderPage>
                <TransitionGroup name="list" tag="div" class="columns is-multiline" v-else>
                  <div class="column is-6" v-for="data in dataSource">
                    <div class="grid-item-wrap is-clickable">
                      <div class="grid-item-head" @click="showDetail(data)">
                        <div class="flex-head">
                          <div class="meta">
                            <span class="title">{{ data.noPosting }}</span>
                            <span class="subtitle" style="margin-bottom:1rem">{{ data.tglTransaksi }}</span>
                            <div class="content">
                              <span class="dark-inverted">Total Klaim :</span>
                              <span class="dark-inverted">{{ data.totalKlaim }} </span>
                            </div>
                            <div class="content">
                              <span class="dark-inverted">Total Sudah Dibayar :</span>
                              <span class="dark-inverted">{{ data.totalSudahDibayar }} </span>
                            </div>
                            <div class="content">
                              <span class="dark-inverted">Total Diskon :</span>
                              <span class="dark-inverted">{{ data.totaldiskon }} </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="flex-head" style=" display: flex; justify-content: space-between;">
                        <VTag v-if="true" class="mt-2 ml-2" :label="`${data.namarekanan} (${data.kelompokpasien})`"
                          :color="data.namarekanan == 'BPJS KESEHATAN' ? 'green' : 'orange'" rounded />
                        <VDropdown icon="feather:more-vertical" spaced right>
                          <template #content>
                            <a @click="bayarTagihan(data)" class="dropdown-item is-media">
                              <div class="icon">
                                <i class="lnil lnil-money-protection" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Bayar</span>
                                <span>Lakukan Pembayaran</span>
                              </div>
                            </a>
                            <a @click="editColllecting(data)" class="dropdown-item is-media">
                              <div class="icon">
                                <i class="lnil lnil-pencil-alt" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Edit</span>
                                <span>Edit Collecting</span>
                              </div>
                            </a>
                            <a @click="kartuPiutang(data)" class="dropdown-item is-media">
                              <div class="icon">
                                <i class="lnil lnil-dollar-card" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Kartu Piutang Perusahaan</span>
                                <span>Kartu Piutang Perusahaan</span>
                              </div>
                            </a>
                            <a @click="showDetail(data)" class="dropdown-item is-media">
                              <div class="icon">
                                <i aria-hidden="true" class="lnil lnil-eye"></i>
                              </div>
                              <div class="meta">
                                <span>Detail</span>
                                <span>Lihat Data</span>
                              </div>
                            </a>
                          </template>
                        </VDropdown>
                      </div>
                      <div class="grid-item">
                        <div class="people" style="padding-bottom:5px;">
                          <VIconBox size="medium" color="primary">
                            <i class="lnil lnil-user"></i>
                          </VIconBox>
                        </div>
                        <p style="font-weight:500; font-size:10pt;">{{ data.jlhPasien }} Pasien</p>
                        <VTag class="mt-2 ml-2" :label="data.status" :color="data.status == 'Lunas' ? 'info' : 'danger'"
                          rounded v-if="true" />
                        <div class="people">
                          <VSnack :title="data.collector == null ? '-' : data.collector" color="info" icon="fa:user-md">
                          </VSnack>
                        </div>
                        <div class="buttons">
                        </div>
                      </div>
                    </div>
                  </div>
                </TransitionGroup>
              </div>
            </VCard>
          </div>
        </div>
      </div>
    </div>
    <div class="column is-4">
      <div class="columns is-multiline">
        <div class="column is-12">
          <VCard>
            <div class="column c-title pt-2 mb-3">
              <label class="title-page">Pencarian</label>
            </div>
            <div class="columns is-multiline">
              <div class="column is-12">
                <a type="button" class="is-pulled-right mr-3" color="info" outlined raised @click="exportExcel()"
                  v-if="dataSource.length != 0">
                  <span>
                    <i class="fas fa-file-excel"></i>
                    Export to Excel
                  </span>
                </a>
              </div>
              <div class="column is-12">
                <span>Periode</span>
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
              <div class="column is-12">
                <VField class="mb-1" label="Nama Collector">
                  <VInput placeholder="Nama Collector..." class="mt-2" v-model="item.namacollector"></VInput>
                </VField>
              </div>
              <div class="column is-12">
                <VField class="mb-1" label="No Collector">
                  <VInput placeholder="No collector..." class="mt-2" v-model="item.noposting"></VInput>
                </VField>
              </div>
              <div class="column is-12">
                <VField class="mt-2 is-rounded-select is-autocomplete-select" label="Jenis Penjamin">
                  <VControl icon="feather:search" class="prime-auto-select">
                    <AutoComplete v-model="item.rekanan" :suggestions="d_Rekanan" @complete="fetchRekanan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari..." />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField class="mt-2 is-autocomplete-select" v-slot="{ id }" label="Status">
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
          </VCard>
        </div>
      </div>
    </div>
  </div>
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
  title: 'Laporan Pencatatan Piutang Collection- ' + import.meta.env.VITE_PROJECT,
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
    label: 'Collecting',
    value: 'Collecting'
  },
  {
    label: 'Lunas',
    value: 'Lunas'
  }
]);
const isLoading: boolean = ref(false);
const isLoadingTT: boolean = ref(false);
const remakeData: any = ref([]);
const modalInput = ref(false)
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
  let nama = item.value.namacollector ? `&namaPasien=${item.value.namacollector}` : ''
  let status = item.value.status ? `&status=${item.value.status.value}` : ''
  let noposting = item.value.noposting ? `&noposting=${item.value.noposting}` : ''
  let rekanan = item.value.rekanan ? `&namaRekanan=${item.value.rekanan.value}` : ''
  await useApi().get(`piutang/daftar-collected-piutang-layanan?${tglAwal}${tglAkhir}${nama}${status}${noposting}${rekanan}`).then((response: any) => {
    let totalPasien = 0;
    let totalKlaim = 0;
    let totaldiskon = 0;
    let totalSudahDibayar = 0;
    response.forEach((element: any, i: any) => {
      element.no = i + 1,
        totalPasien += element.jlhPasien,
        totalKlaim += parseFloat(element.totalKlaim),
        totaldiskon += parseFloat(element.totaldiskon),
        totalSudahDibayar += parseFloat(element.totalSudahDibayar),
        element.tglTransaksi = moment(element.tglTransaksi).format('DD-MMM-YYYY'),
        element.totalKlaim = formatRp(element.totalKlaim, 'Rp.'),
        element.totalSudahDibayar = formatRp(element.totalSudahDibayar, 'Rp.'),
        element.totaldiskon = formatRp(element.totaldiskon, 'Rp.')
    });
    item.value.totalPasien = totalPasien
    item.value.totalKlaim = totalKlaim
    item.value.totaldiskon = totaldiskon
    item.value.totalSudahDibayar = totalSudahDibayar
    dataSource.value = response
    let objSave = {
      tglAwal: moment(item.value.qFilterTgl.start).format('YYYY-MM-DD 00:00:00'),
      tglAkhir: moment(item.value.qFilterTgl.end).format('YYYY-MM-DD 23:59:59')
    }
    useApi().postNoMessage(`akuntansi/post-jurnal-pembayaran-piutang-rekanan`, objSave)
  })
  isLoading.value = false
}
const showDetail = (data: any) => {
  router.push({
    name: 'module-piutang-collection-piutang-detail',
    query: {
      posting: data.noPosting
    }
  })
}
const exportExcel = () => {
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      No: e.no, NoCollecting: e.noPosting, Tanggal: e.tglTransaksi,
      NamaCollector: e.collector, KelompokPasien: e.kelompokpasien,
      Penjamin: e.namarekanan, TotalPasien: e.jlhPasien,
      TotalKlaim: e.totalKlaim, TotalSudahDibayar: e.totalSudahDibayar,
      TotalDiskon: e.totaldiskon, Status: e.status,
      NomerInvoiceBRI: e.nomorreferencebri
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
  e.loading = true
}
const save = async () => {

}
const kartuPiutang = async (data: any) => {
  router.push({
    name: 'module-piutang-kartu-piutang-perusahaan',
    query: {
      rekananfk: data.idrekanan,
      posting: data.noPosting
    }
  })
}
const bayarTagihan = (data: any) => {
  router.push({
    name: 'module-piutang-pembayaran-piutang-kasir',
    query: {
      posting: data.noPosting
    }
  })
}
const editColllecting = (data: any) => {
  H.cacheHelper().set('periodeTransaksiPencatatanPiutangDaftarLayanan', {
    key: 'no_posting',
    noPosting: data.noPosting,
    start: item.value.qFilterTgl.start,
    end: item.value.qFilterTgl.end
  })
  router.push({
    name: 'module-piutang-collection-piutang',
    query: {
      posting: data.noPosting
    }
  })
}
fetchData();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/piutang/collection-piutang';

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

.title {
  font-weight: 600;
}
</style>
