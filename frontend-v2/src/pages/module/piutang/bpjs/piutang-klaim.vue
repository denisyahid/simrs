<template>
  <div class="column is-12">
    <VCard>
      <div class="column is-12 p-0">
        <div class="columns column">
          <h3 class="title is-5 mb-2 mr-1">BPJS Klaim</h3>
        </div>
        <div class="columns is-multiline">
          <div class="column is-8 is-pulled-right">
            <div class="columns is-multiline is-pulled-right">
              <div class="flex flex-wrap align-items-center gap-2">
                <a type="button" class="is-pulled-right mr-3" color="info" outlined raised @click="showModal()"
                  v-if="dataSource.length != 0">
                  <span>
                    <i class="fas fa-plus"></i>
                    Collecting
                  </span>
                </a>
                <a type="button" class="is-pulled-right mr-3" color="info" outlined raised @click="exportExcel()"
                  v-if="dataSource.length != 0">
                  <span>
                    <i class="fas fa-file-excel"></i>
                    Export to Excel
                  </span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-8">
          <div class="column">
            <div class="flex-list-inner mb-4" v-if="isLoading">
              <div class="flex-table-item grid-item mb-4" v-for="key in 2" :key="key">
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
            <div class="flex-list-inner" v-else-if="dataSource.length == 0">
              <VCard>
                <VPlaceholderSection title="Data Tidak Ditemukan !" subtitle="Silakan Ubah Pencarian !" class="my-6">
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                  </template>
                </VPlaceholderSection>
              </VCard>
            </div>
            <div class="grid-item mb-4" v-for="(data, i) in dataSource" :key="i" v-else>
              <div class="top-section">
                <div class="head">
                  <div class="title-wrap">
                    <div class="columns">
                      <div class="column is-3">
                        <VAvatar size="small" :color="listColor[0]" :initials="data.initials" />
                      </div>
                      <div class="column is-12 mr-3">
                        <h3>{{ data.namaPasien }}</h3>
                        <p>{{ data.noRegistrasi }}</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="body">
                  <div class="columns">
                    <div class="column is-3">
                      <h4 class="heading">Tanggal</h4>
                      <p class="fs-075">{{ H.formatDate(data.tglpulang, "DD-MM-YYYY") }}</p>
                    </div>
                    <div class="column is-3">
                      <h4 class="heading">No SEP</h4>
                      <p class="fs-075">{{ data.nosep }}</p>
                    </div>
                    <div class="column is-3">
                      <h4 class="heading">Jenis Pasisen</h4>
                      <p class="fs-075">{{ data.jenisPasisen }}</p>
                    </div>
                    <div class="column is-3">
                      <h4 class="heading">Ruangan / Rekanan</h4>
                      <p class="fs-075">{{ data.namaruangan }} / {{ data.rekanan }}</p>
                    </div>
                  </div>
                  <div class="columns mt-5-min">
                    <div class="column is-3">
                      <h4 class="heading">Total Billing </h4>
                      <p class="fs-075 font-bold">{{ data.totalBilling }}</p>
                    </div>
                    <div class="column is-3">
                      <h4 class="heading">Total Tidak Diklaim </h4>
                      <p class="fs-075 font-bold">{{ data.totaltidakdiklaim }}</p>
                    </div>
                    <div class="column is-3">
                      <h4 class="heading">Total Bayar </h4>
                      <p class="fs-075 font-bold">{{ data.totalBayar }}</p>
                    </div>
                    <div class="column is-3">
                      <h4 class="heading">Saldo </h4>
                      <p class="fs-075 font-bold">{{ data.totaltarifrs }}
                      </p>
                    </div>
                  </div>
                  <div class="columns mt-5-min">
                    <div class="column is-3">
                      <h4 class="heading">Total Pengajuan / Disetujuhi </h4>
                      <p class="fs-075 font-bold">{{ data.totalsetujui }} / {{ data.totaldisetujuhi }}
                      </p>
                    </div>
                    <div class="column is-3">
                      <h4 class="heading">No Fpk / No Posting </h4>
                      <p class="fs-075 font-bold">{{ data.nofpk }} / {{ data.noposting }}
                      </p>
                    </div>
                  </div>
                </div>
                <div class="bottom-section is-custom">
                  <div class="column is-12">
                  </div>
                </div>
              </div>
            </div>
            <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
              :total-items="dataSource.total" :max-links-displayed="5">
              <template #before-pagination>
              </template>
              <template #before-navigation>
                <VFlex class="mr-4 mt-1" column-gap="1rem">
                  <VField>

                  </VField>
                  <VField>
                    <VControl>
                      <div class="select is-rounded">
                        <select v-model="currentPage.limit">
                          <option :value="1">1 results per page</option>
                          <option :value="5">5 results per page</option>
                          <option :value="10">10 results per page</option>
                          <option :value="15">15 results per page</option>
                          <option :value="25">25 results per page</option>
                          <option :value="50">50 results per page</option>
                        </select>
                      </div>
                    </VControl>
                  </VField>
                </VFlex>
              </template>
            </VFlexPagination>
          </div>
        </div>
        <div class="column is-4">
          <div class="search-widget">
            <div class="field">
              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1">Data Klaim BPJS</h3>
                <div class="columns is-multiline mt-4">
                  <div class="column is-12">
                    <h1>Periode</h1>
                    <div class="is-flex mt-3">
                      <VField v-for="data in filters" :key="data.value" style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="item.filter" class="pt-1 pb-1" :true-value="data.value"
                            :label="data.label" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column is-12">
                    <h1 class="mb-2">Tanggal Pulang</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <div>
                          <Calendar inputId="range" v-model="item.tanggal" selectionMode="range" :manualInput="false"
                            class="w-100" :showIcon="true" :date-format="'yy-mm-dd'" />
                        </div>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <h1 class="mb-2">No FPK</h1>
                    <VField>
                      <VInput placeholder="nomer FPK.." v-model="item.noFpk"></VInput>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <h1 class="mb-2">Nama Pasien</h1>
                    <VField>
                      <VInput placeholder="nama.." v-model="item.namaPasien"></VInput>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <h1 class="mb-2">Nomer Registrasi</h1>
                    <VField>
                      <VInput placeholder="no regitrasi.." v-model="item.noRegistrasi"></VInput>
                    </VField>
                  </div>
                  <div class="column mt-5" style="margin-left: auto:  !important;">
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
  </div>
  <Dialog v-model:visible="modalShow" modal :header="'NO FPK'" :style="{ width: '40vw' }">
    <div class="columns is-multiline">
      <div class="column is-12">
        <h1 class="mb-2">NO FPK</h1>
        <VField>
          <VInput placeholder="Masukan Nomer FPK" v-model="item.noFPKUpdate"></VInput>
        </VField>
      </div>
    </div>
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" rounded outlined color="danger" @click="modalShow == false"
        style="margin-right:5px">
        Batal
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
        @click="collecPiutang()"> Collecting
      </VButton>
    </template>
  </Dialog>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useHead } from '@vueuse/head';
import * as H from '/@src/utils/appHelper';
import AutoComplete from 'primevue/autocomplete';
import moment from 'moment';
import { useApi } from '/@src/composable/useApi';
import { useThemeColors } from '/@src/composable/useThemeColors'
import Calendar from 'primevue/calendar';
import * as XLSX from "xlsx";
import Dialog from 'primevue/dialog';
useHead({
  title: 'Piutang Klaim - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const router = useRouter();
const item: any = reactive({
  tanggal: [
    new Date(),
    new Date()
  ],
})
const input: any = ref({})
const filters: any = ref([
  {
    label: 'Rawat Inap',
    value: '1',
    model: 'filter'
  },
  {
    label: 'Rawat Jalan',
    value: '2',
    model: 'filter'
  }
]);
const route = useRoute()
const dataSource: any = ref([]);
const dataTotal: any = reactive(0);
const isLoading: any = ref(false);
const modalShow: any = ref(false);
let listColor: any = ref(Object.keys(useThemeColors()))
const fetchData = async () => {
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  isLoading.value = true;
  let pelayanan = item.filter ?? '';
  let startDate = '';
  let endDate = '';
  let namapasien = item.namaPasien ?? '';
  let noregistrasi = item.noRegistrasi ?? '';
  let noFpk = item.noFpk ?? '';
  if (item.tanggal) {
    if (item.tanggal[0]) {
      startDate = H.formatDate(item.tanggal[0], 'YYYY-MM-DD')
    }
    if (item.tanggal[1]) {
      endDate = H.formatDate(item.tanggal[1], 'YYYY-MM-DD')
    } else {
      endDate = H.formatDate(item.tanggal[0], 'YYYY-MM-DD')
    }
  }
  await useApi().get(`piutang/daftar-data-klaim-bpjs?tglAwal=${startDate}&tglAkhir=${endDate}&jenisPelayanan=${pelayanan}&nofpk=${noFpk}&namaPasien=${namapasien}&noReg=${noregistrasi}&offset=${offset}&limit=${limit}`).then((response) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1,
        element.totalBilling = H.formatRp(element.totalBilling, 'Rp'),
        element.totaltidakdiklaim = H.formatRp(element.totaltidakdiklaim, 'Rp'),
        element.totalBayar = H.formatRp(element.totalBayar, 'Rp'),
        element.totaltarifrs = H.formatRp(element.totaltarifrs, 'Rp'),
        element.totalpengajuan = H.formatRp(element.totalpengajuan, 'Rp'),
        element.totalsetujui = H.formatRp(element.totalsetujui, 'Rp'),
        element.initials = element.namaPasien ? calculateInitials(element.namaPasien) : '-'
    });
    dataSource.value = response.data;
    dataSource.value.total = response.total;
  });
  route.query.page = "1"
  isLoading.value = false;
}
function calculateInitials(namaPasien: any) {
  let ini = namaPasien.split(' ');
  let init = namaPasien.substr(0, 1);

  if (ini.length > 1) {
    init = init + ini[1].substr(0, 1);
  }

  return init;
}
const exportExcel = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([

    ['Data Klaim BPJS'],
    [],
    ['Tanggal', 'No. Registrasi', 'No SEP', 'Nama', 'Nama Ruangan', 'Jenis Pasien', 'Penjamin', 'Total Billing', 'Total Tidak DiKlaim', 'Total Bayar', 'Total Tarif RS', 'Total Pengajuan', 'Total Setujui', 'No FPK', 'No Collect'],
    ...dataSource.value.map((e: any) => [
      e.tanggalPulanng,
      e.noRegistrasi,
      e.nosep,
      e.namaPasien,
      e.namaruangan,
      e.jenisPasisen,
      e.rekanan,
      e.totalBilling,
      e.totaltidakdiklaim,
      e.totalBayar,
      e.totaltarifrs,
      e.totalpengajuan,
      e.totalsetujui,
      e.nofpk,
      e.noposting,
      '',
    ])
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 20 },
    { wch: 25 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
  ];
  worksheet['!cols'] = columnWidths;
  const cellRef = XLSX.utils.encode_cell({ r: 0, c: 0 });
  worksheet[cellRef] = { v: 'Data Klaim BPJS', s: { alignment: { horizontal: 'center', vertical: 'center' } } };

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 8 } };
  worksheet['!merges'] = [mergeTitle, mergeSubtitle1];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'DaftarKlaimBPJS');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  const desiredFileName = 'buku-kas-umum' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}
const currentPage: any = ref({
  limit: 5,
  rows: 50
})
const showModal = () => {
  modalShow.value = true;
}

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  fetchData()
})
const collecPiutang = () => {
  if (!item.noFPKUpdate) {
    H.alert("warning", "NO FPK Harus Diisi!")
    return;
  }
  let nofpk = item.noFPKUpdate ? item.noFPKUpdate : ''
  let namaPasien = item.namaPasien ? item.namaPasien : ''
  let noRegistrasi = item.noRegistrasi ? item.noRegistrasi : ''
  H.cacheHelper().set('periodeTransaksiPencatatanPiutangDaftarLayanan', {
    key: 'bpjs_klaim_bpjs_api',
    nofpk: nofpk,
    noRegistrasi :  noRegistrasi,
    namaPasien :  namaPasien
  })
  router.push({
    name: 'module-piutang-collection-piutang',
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
</style>
