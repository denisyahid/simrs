<template>
  <div class="column is-12">
    <VCard>
      <div class="column is-12">
        <h1 class="title is-5 mb-2 mr-1">Kartu Piutang Perusahaan</h1>
      </div>
      <div class="search-widget">
        <div class="field">
          <div class="column is-12">
            <div class="columns is-multiline mt-4">
              <div class="column">
                <h1>Periode</h1>
                <div class="is-flex">
                  <VField v-for="data in filters" :key="data.value" style="padding:0px;">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="item.filter" class="pt-1 pb-1" :true-value="data.value" :label="data.label"
                        color="primary" circle />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column">
                <h1 class="mb-2">{{ item.filter == 'Bulan' ? 'Bulan' : 'Tanggal' }}</h1>
                <VField>
                  <VControl class="prime-auto">
                    <div v-if="item.filter == 'Bulan'">
                      <Calendar inputId="range" v-model="item.bulan" selectionMode="single" :manualInput="false"
                        class="w-100" :showIcon="true" view="month" dateFormat="mm/yy" />
                    </div>
                    <div v-else>
                      <Calendar inputId="range" v-model="item.tanggal" selectionMode="range" :manualInput="false"
                        class="w-100" :showIcon="true" :date-format="'yy-mm-dd'" />
                    </div>
                  </VControl>
                </VField>
              </div>
              <div class="column">
                <h1 class="mb-2">Nama Perusahaan</h1>
                <VField class="is-autocomplete-select">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.rekanan" :suggestions="d_Rekanan" @complete="fetchRekanan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Perusahaan.." />
                  </VControl>
                </VField>
              </div>
              <div class="column">
                <h1 class="mb-2">Nomer Registrasi</h1>
                <VField>
                  <VInput placeholder="Nomer Registrasi.." v-model="item.noPosting"></VInput>
                </VField>
              </div>
              <div class="column mt-5" style="margin-left: auto:  !important;">
                <VIconButton type="button" color="success" class="searcv-button  mt-2" raised icon="fas fa-search"
                  @click="fetchData()" :loading="isLoading">
                </VIconButton>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="columns is-multiline">
        <div class="column is-4" style="margin-top:10px">
          <VCardCustom :style="'padding:5px 25px;margin:0;background:#fafafa'">
            <div :class="'label-status ' + listColor[1]">
              <i aria-hidden="true" class="fas fa-circle"></i>
              <span class="ml-1">Total Pasien</span>
            </div>
            <small class="text-bold-custom">{{
              dataSource.length
            }}</small>
          </VCardCustom>
        </div>
        <div class="column is-4" style="margin-top:10px">
          <VCardCustom :style="'padding:5px 25px;margin:0;background:#fafafa'">
            <div :class="'label-status ' + listColor[2]">
              <i aria-hidden="true" class="fas fa-circle"></i>
              <span class="ml-1">Total Piutang</span>
            </div>
            <small class="text-bold-custom">{{
              H.formatRp(item.piutang,
                'Rp')
            }}</small>
          </VCardCustom>
        </div>
        <div class="column is-4" style="margin-top:10px">
          <VCardCustom :style="'padding:5px 25px;margin:0;background:#fafafa'">
            <div :class="'label-status ' + listColor[3]">
              <i aria-hidden="true" class="fas fa-circle"></i>
              <span class="ml-1">Total Dibayar</span>
            </div>
            <small class="text-bold-custom">{{
              H.formatRp(item.totalsudahdibayar,
                'Rp')
            }}</small>
          </VCardCustom>
        </div>
        <div class="column is-12">
          <VButton color="success" icon="fas fa-print" raised rounded style="margin-left:20px;" @click="exportExcel()">
            Laporan
          </VButton>
        </div>
        <div class="column is-12">
          <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan."
            subtitle="Silakan ubah periode pencarian" larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>
          <DataTable :value="dataSource" tableStyle="min-width: 50rem" scrollable :paginator="true" :rows="10"
            :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" showGridlines v-else>
            <template #header>
              <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                <span class="text-xl text-900 font-bold">Rincian Transaksi</span>
              </div>
            </template>
            <Column field="no" header="No"></Column>
            <Column field="status" header="Penjamin" style="width: 20%">
              <template #body="slotProps">
                <Tag :value="slotProps.data.namarekanan" :severity="getSeverity(slotProps.data.namarekanan)" />
              </template>
            </Column>

            <Column field="tanggal" header="Tgl Verifikasi" style="min-width: 150px"></Column>
            <Column field="number" header="Nocm / NoRegistrasi" style="min-width: 150px" frozen></Column>
            <Column field="namapasien" header="Nama Pasien" style="min-width: 120px"></Column>
            <Column field="namaruangan" header="Ruangan" style="min-width: 120px"></Column>
            <Column field="piutang" header="Piutang" style="min-width: 120px"></Column>
            <Column field="totalsudahdibayar" header="Dibayar" style="min-width: 120px"></Column>
            <Column field="administrasi" header="ADM" style="min-width: 120px"></Column>
            <Column field="sistagihan" header="Saldo" style="min-width: 120px"></Column>
            <Column field="age" header="Umur Piutang" style="min-width: 120px"></Column>
            <Column :exportable="false" header="#" style="min-width: 100px">
              <template #body="slotProps">
                <div style=" display: flex; justify-content: space-between;">
                  <VDropdown icon="feather:more-vertical" spaced right class="mt-1" v-tooltip.bubble="'AKSI'">
                    <template #content>
                      <div style="height: 4rem;overflow: auto;">
                        <a role="menuitem" @click="cetakKartuPiutang(slotProps.data)" class="dropdown-item is-media">
                          <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-printer"></i>
                          </div>
                          <div class="meta">
                            <span>Cetak Kartu Piutang</span>
                            <span>Cetak Kartu Piutang Perusahaan</span>
                          </div>
                        </a>
                      </div>
                    </template>
                  </VDropdown>
                </div>
              </template>
            </Column>
          </DataTable>
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
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import * as XLSX from "xlsx";
import Tag from 'primevue/tag';
useHead({
  title: 'Daftar Kartu Piutang Perusahaan- ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = reactive({
  tanggal: [
    new Date(),
    new Date()
  ],
})
const input: any = ref({})
const d_Rekanan: any = ref([])
const dataSource: any = ref([])
const remakeData: any = ref([])
const isLoading: any = ref(false)
const listColor: any = ref(Object.keys(useThemeColors()))
const filters: any = ref([
  {
    label: 'Per Bulan',
    value: 'Bulan',
    model: 'filter'
  },
  {
    label: 'Per Tanggal',
    value: 'Tanggal',
    model: 'filter'
  }
]);

const fetchRekanan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Rekanan.value = response
  })
}
const fetchData = async () => {
  isLoading.value = true;
  let startDate = '';
  let endDate = '';
  if (item.filter == 'Bulan') {
    const selectedDate = new Date(item.bulan);
    const firstDayOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
    const lastDayOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0);
    const startDateString = firstDayOfMonth.toISOString();
    const endDateString = lastDayOfMonth.toISOString();
    startDate = H.formatDate(startDateString, 'YYYY-MM-DD 00:00');
    endDate = H.formatDate(endDateString, 'YYYY-MM-DD 23:59');
  } else {
    if (item.tanggal) {
      if (item.tanggal[0]) {
        startDate = H.formatDate(item.tanggal[0], 'YYYY-MM-DD 00:00')
      }
      if (item.tanggal[1]) {
        endDate = H.formatDate(item.tanggal[1], 'YYYY-MM-DD 23:59')
      } else {
        endDate = H.formatDate(item.tanggal[0], 'YYYY-MM-DD 23:59')
      }
    }
  }
  await useApi().get(`piutang/get-daftar-kartupiutang?tglAwal=${startDate}&tglAkhir=${endDate}&noPosting=${item.noPosting || ''}&idPerusahaan=${item.rekanan ? item.rekanan.value : ''}`).then((response) => {
    let piutang = 0;
    let totalsudahdibayar = 0;
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1,
        piutang += parseFloat(element.piutang),
        totalsudahdibayar += parseFloat(element.totalsudahdibayar),
        element.tanggal = moment(element.tglstruk).format('DD-MM-YYYY'),
        element.number = `${element.nocm} / ${element.noregistrasi}`,
        element.age = H.countBirthday(new Date(element.tglstruk), new Date()).days + ' Hari',
        element.piutang = H.formatRp(element.piutang, 'Rp'),
        element.totalsudahdibayar = H.formatRp(element.totalsudahdibayar, 'Rp'),
        element.administrasi = H.formatRp(element.administrasi, 'Rp'),
        element.sistagihan = H.formatRp(element.sistagihan, 'Rp')
    });
    item.piutang = piutang;
    item.totalsudahdibayar = totalsudahdibayar;
    dataSource.value = response.data
    isLoading.value = false;
  })
}
const exportExcel = () => {
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      No: e.no, Tanggal: e.tanggal, NomerRegistrasi: e.noregistrasi,
      Nocm: e.nocm, NamaPasien: e.namapasien,
      NamaRuangan: e.namaruangan, Piutang: e.piutang,
      Dibayar: e.totalsudahdibayar, ADM: e.administrasi,
      Saldo: e.sistagihan, UmurPiutang: e.age
    }
  })
  const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
  const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'products');
}
const getSeverity = (status: any) => {
  switch (status) {
    case 'BPJS KESEHATAN':
      return 'success';
    default:
      return '';
  }
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
const cetakKartuPiutang = (data: any) => {
  let startDate = '';
  let endDate = '';
  if (item.filter == 'Bulan') {
    const selectedDate = new Date(item.bulan);
    const firstDayOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
    const lastDayOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0);
    const startDateString = firstDayOfMonth.toISOString();
    const endDateString = lastDayOfMonth.toISOString();
    startDate = H.formatDate(startDateString, 'YYYY-MM-DD 00:00');
    endDate = H.formatDate(endDateString, 'YYYY-MM-DD 23:59');
  } else {
    if (item.tanggal) {
      if (item.tanggal[0]) {
        startDate = H.formatDate(item.tanggal[0], 'YYYY-MM-DD 00:00')
      }
      if (item.tanggal[1]) {
        endDate = H.formatDate(item.tanggal[1], 'YYYY-MM-DD 23:59')
      } else {
        endDate = H.formatDate(item.tanggal[0], 'YYYY-MM-DD 23:59')
      }
    }
  }
  H.printBlade(`report/piutang/cetak-kartu-piutang-perusahaan?idPerusahaan=${data.idrekanan}&start=${startDate}&end=${endDate}`)

}
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
</style>
