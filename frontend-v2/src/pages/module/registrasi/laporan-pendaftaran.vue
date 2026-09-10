
<template>
    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-5">
          <label class="title-page">Laporan Pasien Daftar</label>
        </div>
        <div class="column is-12">
            <div class="columns is-multiline mt-5">
              <div class="column is-12">
                <DataTable :value="d_Pendaftaran" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                    :rows="currentPage.rows" :rowsPerPageOptions="[5, 10, 25,  currentPage.rows]" scrollable :totalRecords="d_Pendaftaran.length"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
                    v-model:filters="filters" :globalFilterFields="['noregistrasi', 'ruangandaftar' ,'kelompokpasien','dokterpj' ,'petugas' ,'penjamin', 'namapasien', 'namaruangan']"
                    filterDisplay="menu">
                    <template #empty> {{ H.assets().notFound }}</template>
                    <template #header>
                      <div class="columns is-multiline pb-3">
                        <div class="column is-2" style="padding-top:2rem">
                          <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                            Export To Excel
                          </VButton>
                        </div>
                        <div class="column is-10">
                          <div class="columns is-multiline" style="justify-content: flex-end;">
                            <div class="column is-5 pb-0">
                              <VField label="Periode" style="margin-bottom: 6px;" />
                              <VDatePicker v-model="input.periode"  is-range color="pink" locale="id"   trim-weeks>
                                <template #default="{ inputValue, inputEvents }" >
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
                            <div class="column is-2">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                  <VLabel>Kelompok Pasien</VLabel>
                                  <VControl icon="feather:search">
                                    <Multiselect v-model="input.kelompokpasien" :options="listKelompokPasien"
                                      placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                  </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                  <VLabel>Departemen</VLabel>
                                  <VControl icon="feather:search">
                                    <Multiselect v-model="input.departement" :options="listDepartement"
                                      placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                  </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pb-0">
                              <VField label="Cari">
                                <VInput v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
                              </VField>
                            </div>
                            <div class="column is-1 mt-5">
                              <VIconButton color="success" icon="fas fa-search" class="mt-1" @click="fetchPasien()"
                                :loading="isLoading" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </template>
                    <Column header="No"  style="min-width: 50px">
                      <template #body="slotProps">
                            {{ slotProps.index + 1 }}
                        </template>
                    </Column>
                    <Column field="tglregistrasi"  header="Tanggal" style="min-width: 200px">
                      <!-- <template #body="slotProps">
                        <span>{{ H.formatDateIndoSimple(slotProps.data.tglregistrasi) }}</span>
                      </template> -->
                    </Column>
                    <Column field="jammasuk" header="Jam Masuk"  style="min-width: 100px"/>
                    <Column field="noregistrasi" header="No Registrasi" style="min-width: 200px"/>
                    <Column field="namapasien" header="Pasien" style="min-width: 300px"/>
                    <Column field="ruangandaftar" header="Unit Layanan" style="min-width: 200px"/>
                    <Column field="kelompokpasien" header="Tipe Pasien" style="min-width: 100px"/>
                    <Column field="namarekanan" header="Penjamin" style="min-width: 100px"/>
                    <Column field="nosep" header="NO SEP" style="min-width: 100px"/>
                    <Column field="tgllahir" header="Tgl Lahir" style="min-width: 150px"/>
                    <Column field="umur" header="Umur" style="min-width: 50px"/>
                    <Column field="alamatlengkap" header="Alamat" style="min-width: 300px"/>
                    <Column field="dokterpj" header="Dokter" style="min-width: 300px"/>
                    <Column field="tglpulang" header="TGL Pulang" style="min-width: 150px"/>
                    <Column field="petugas" header="Petugas Regis" style="min-width: 300px"/>
                    <!-- <Column field="tglmasuk" header="Tgl Masuk"></Column>
                    <Column field="jammasuk" header="Jam Masuk"></Column>
                    <Column field="noregistrasi" header="No Registrasi"></Column>
                    <Column field="namapasien" header="Nama Pasien"></Column>
                    <Column field="ruangandaftar" header="Unit Layanan"></Column>
                    <Column field="kelompokpasien" header="Tipe Pasien"></Column>
                    <Column field="statuspasien" header="Status Pasien"></Column>
                    <Column field="penjamin" header="Penjamin"></Column>
                    <Column field="jk" header="JK"></Column>
                    <Column field="kecamata" header="Kecamatan"></Column>
                    <Column field="kabupaten" header="Kota/Kabupaten"></Column>
                    <Column field="alamatlengkap" header="Alamat"></Column>
                    <Column field="tgllahir" header="Tgl Lahir"></Column>
                    <Column field="umur" header="Umur"></Column>
                    <Column field="namakelas" header="Kelas"></Column>
                    <Column field="dokterpj" header="Dokter"></Column>
                    <Column field="diagnosamasuk" header="Diagnosa Masuk"></Column>
                    <Column field="tglpulang" header="Tgl Keluar"></Column>
                    <Column field="jampulang" header="Jam Keluar"></Column> -->
                </DataTable>
              </div>
            </div>
        </div>
      </VCard>
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
import MultiSelect from 'primevue/multiselect';
import { FilterMatchMode } from 'primevue/api'
import * as XLSXStyle from 'xlsx-js-style';

const input: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const masks = ref({
  modelValue: 'YYYY-DD-MM',
});
let listKelompokPasien: any = ref([]);
let listDepartement: any = ref([]);
let sourceRuangan: any = ref([]);
const d_Departement: any = ref([]);
const d_Ruangan: any = ref([]);
const d_KelompokPasien: any = ref([]);
let isLoading = ref(false)
const route = useRoute()
const d_Pendaftaran: any = ref([])
const dataSourceICD9 = ref([])
const remakeData: any = ref([])
const sortBy = ref("tgljamregistrasi"); 
const sortOrder = ref("asc"); 
import * as XLSX from "xlsx";



async function fetchPasien() {
  d_Pendaftaran.value.loading = true

  let searchQuery = `&q=`
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  let namaPasien = ''
  let queryKelompok = ''
  let queryDepartemen = ''

  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
        itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
          ruanganid = `&ruanganfk=${itemsRuang}`
  }
    let startDate = moment(input.value.periode.start).format('YYYY-MM-DD');
    let endDate = moment(input.value.periode.end).format('YYYY-MM-DD');
    let kelompokpasien = input.value.kelompokpasien ? input.value.kelompokpasien.value : "";
    let ruangan = input.value.ruangan ? input.value.ruangan.value : "";
    let departement = input.value.departement ? input.value.departement.value : "";

  if (input.value.qnama) namaPasien = `&namaPasien=${input.value.qnama}`
  if(input.value.kelompokpasien != undefined && input.value.kelompokpasien != null) queryKelompok = `&kelompokpasien=${input.value.kelompokpasien}`
  if(input.value.departement != undefined && input.value.departement != null) queryDepartemen = `&departement=${input.value.departement}`
  if(input.value.ruangan != undefined && input.value.ruangan.id != null) queryKelompok += `&ruanganId=${input.value.ruangan.id}`

  const response = await useApi().get(
    `/laporan/pendaftaran?tglAwal=${startDate}&tglAkhir=${endDate}&departement=${departement}&ruangan=${ruangan}&kelompokpasien=${kelompokpasien}${queryKelompok}${queryDepartemen}`)
    d_Pendaftaran.value = response.data.sort(
                (a, b) => new Date(a.tgljamregistrasi).getTime() - new Date(b.tgljamregistrasi).getTime()
            );
  d_Pendaftaran.value.loading = false
  console.log(response.data)

  for (let x = 0; x < response.data.length; x++) {
  const element = response.data[x];
  if (!element.namaPasien || typeof element.namaPasien !== 'string') {
    element.initials = ''; // Atau nilai default lainnya
    continue;
  }
  let ini = element.namaPasien.split(' ');
  let init = ini[0].substr(0, 1);
  if (ini.length > 1) {
    init = init + ini[1].substr(0, 1);
  }
  element.initials = init;
}

  console.log(response.data)
  d_Pendaftaran.value = response.data
  d_Pendaftaran.value.total = response.total
  route.query.page = '1'
  listKelompokPasien.value = response.listkelompok.reduce((obj, input) => (obj[input.id] = input.nama, obj) ,{});
  listDepartement.value = response.listdepartemen.reduce((obj, input) => (obj[input.id] = input.nama, obj) ,{});
  console.log(listKelompokPasien)
}

// async function cariRiwayat() {
//     let object: any = {}

//     object = input.value

//     isLoading.value = true;
//     let startDate = moment(input.value.periode.start).format('YYYY-MM-DD');
//     let endDate = moment(input.value.periode.end).format('YYYY-MM-DD');
//     let kelompokpasien = input.value.kelompokpasien ? input.value.kelompokpasien.value : "";
//     let ruangan = input.value.ruangan ? input.value.ruangan.value : "";
//     let departement = input.value.departement ? input.value.departement.value : "";
//     useApi().get(
//         /laporan/pendaftaran?tglAwal=${startDate}&tglAkhir=${endDate}&departement=${departement}&ruangan=${ruangan}&kelompokpasien=${kelompokpasien}).then((response: any) => {
//             response.forEach((element:any,i:any) => {
//                 element.no = i+1
//                 element.tglLahir = H.formatDateToLocalString(element.tgllahir)
//                 element.tglPulang =  element.tglpulang != null ? H.formatDateIndoSimple(element.tglpulang) : ''
//             });
//             d_Pendaftaran.value = response
//             isLoading.value = false
//         }).catch((e: any) => {
//             isLoading.value = false
//         })

// }

useHead({
    title: 'Laporan Pendaftaran - ' + import.meta.env.VITE_PROJECT,
})
const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const setAutoFill = () => {
    input.value.tglAwal = new Date()
    input.value.tglAkhir = new Date()

}

const fetchDepartement = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/departemen_m?select=id,namadepartemen&param_search=namadepartemen&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Departement.value = response
    })
}
const fetchRuangan = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}
const fetchKelompokPasien = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
    })
}

const currentPage: any = ref({
  limit: 5,
  rows: 50
})

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  console.log(currentPage.value)
  fetchPasien()
})

// const exportExcel = () => {
//     remakeData.value = d_Pendaftaran.value.map((e: any) => {
//         return {
//             TGLMasuk: e.tglmasuk, JamMasuk: e.jammasuk, NORegistrasi: e.noregistrasi,
//             KelompokPasien: e.kelompokpasien, StatusPasien: e.statuspasien, Penjamin: e.penjamin, JenisKelamin: e.jk,
//             Kecamatan: e.kecamatan, Kabupaten: e.kabupaten, Alamat: e.alamatlengkap, TGLLahir: e.tgllahir,
//             Umur: e.umur, NamaKelas: e.namakelas, Alamat: e.alamatlengkap, Dokter: e.dokterpj,
//             Diganosa: e.diagnosamasuk, TGLPulang: e.tglpulang, JamPulang: e.jampulang,Petugas: e.petugas
//         }
//     })
//     const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
//     const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
//     const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
//     saveAsExcelFile(excelBuffer, 'products');
// }
// const saveAsExcelFile = (buffer: any, fileName: string) => {
//     let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
//     let EXCEL_EXTENSION = '.xlsx';
//     const data: Blob = new Blob([buffer], {
//         type: EXCEL_TYPE
//     });
//     const _url = window.URL.createObjectURL(data)
//     window.open(_url, EXCEL_EXTENSION).focus();
// }
const exportExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Daftar Pendaftaran Pasien'],
    [],
    ['NO', 'TANGGAL MASUK', 'JAM MASUK', 'NO REGISTRASI','NAMA PASIEN','KELOMPOK PASIEN' ,'STATUS PASIEN' ,'PENJAMIN','JENIS KELAMIN','ALAMAT' ,'UNIT LAYANAN' ,'DOKTER'],
    ...d_Pendaftaran.value.map((e: any, index: number) => [
      index + 1,
      e.tglmasuk,
      e.jammasuk,
      e.noregistrasi,
      e.namapasien,
      e.kelompokpasien,
      e.statuspasien,
      e.penjamin,
      e.jk,
      e.alamatlengkap,
      e.ruangandaftar,
      e.dokterpj,
    ]),
  ]);

  // Defining style for the header (centered)
  const headerStyle = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      color: { rgb: 'FFFFFF' },
      bold: true,
    },
    fill: { fgColor: { rgb: '807C7C' } },
  };

  // Applying header style
  const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
    worksheet[headerCell].s = headerStyle;
  }

  // Setting column widths
  const columnWidths = [5, 15, 12, 15,30, 20, 20, 15,20,10,40,10,300];

  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    worksheet['!cols'] = worksheet['!cols'] || [];
    worksheet['!cols'][col] = { wch: columnWidths[col] };
  }

  // Centering the text in cell A1
  const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
  worksheet[titleCell].s = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      bold: true,
      sz: 18,
    },
  };

  // Merging the first two rows
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 10 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Daftar Pendaftaran Pasien', true);

  XLSXStyle.writeFile(workbook, 'Daftar Pendaftaran Pasien.xlsx');
};

const sortedData = computed(() => {
    return [...d_Pendaftaran.value].sort((a, b) => {
        if (sortBy.value === "tgljamregistrasi") {
            return sortOrder.value === "asc"
                ? new Date(a.tgljamregistrasi) - new Date(b.tgljamregistrasi)
                : new Date(b.tgljamregistrasi) - new Date(a.tgljamregistrasi);
        }
        return 0;
    });
});
const updateSort = (field: string) => {
    if (sortBy.value === field) {
        sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
    } else {
        sortBy.value = field;
        sortOrder.value = "asc";
    }
};


fetchPasien()
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
@import '/@src/scss/module/sysadmin/master-data.scss';
.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}
</style>
