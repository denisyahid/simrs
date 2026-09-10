<template>
  <div class="column is-12">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1">Audit Cuci Tangan IPCLN</h3>
              </div>
              <div class="column is-3">
                <VField label="Periode" style="margin-bottom: 6px;" />
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
              <div class="column pt-0 pb-0 is-2">
                <VField class="is-autocomplete-select mt-3" v-slot="{ id }" label="Nama Pegawai">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.namapegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai" />
                  </VControl>
                </VField>
              </div>
              <div class="column pt-0 pb-0 is-2">
                <VField class="is-autocomplete-select mt-3" v-slot="{ id }" label="Jenis Pegawai">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.jenispegawai" :suggestions="d_JenisPegawai"
                      @complete="fetchJenisPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Jenis Pegawai" />
                  </VControl>
                </VField>
              </div>
              <div class="column pt-0 pb-0 is-2">
                <VField class="is-autocomplete-select mt-3" v-slot="{ id }" label="Nama Ruangan">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan" />
                  </VControl>
                </VField>
              </div>
              <div class="column pt-0 pb-0 is-2">
                <VField class="is-autocomplete-select mt-3" v-slot="{ id }" label="Indikasi">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.indikasiFilter" :suggestions="d_Indikasi" @complete="fetchIndikasi($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Indikasi" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-1 mt-5">
                <VIconButton type="button" color="success" class="mt-1" raised icon="fas fa-search" @click="fetchData()"
                  :loading="isLoading">
                </VIconButton>
                <VIconButton type="button" color="warning" class="mt-1 ml-2" raised icon="fas fa-plus"
                  @click="showModal()" :loading="isLoading">
                </VIconButton>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div v-if="dataSource.length == 0">
          <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
            <template #image>
              <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
            </template>
          </VPlaceholderSection>
        </div>
        <DataTable v-else v-model:expandedRowGroups="expandedRowGroups" :class="`p-datatable-small`" :value="dataSource"
          v-model:filters="filtersTrans" :globalFilterFields="['tahun,bulan,namalengkap,jenispegawai']"
          rowGroupMode="subheader" :rowsPerPageOptions="[5, 10, 25, 100]" :rows="5" paginator groupRowsBy="namalengkap"
          :loading="isLoading" @rowgroup-expand="onRowGroupExpand" @rowgroup-collapse="onRowGroupCollapse"
          sortMode="single" sortField="namalengkap" :sortOrder="5" showGridlines>
          <template #header>
            <div class="columns is-multiline">
              <div class="column is-3">
                <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="info" outlined circle raised
                  v-tooltip-prime="'Export'" @click="exportExcel()">
                  Export Excel
                </VButton>
              </div>
              <div class="column is-3 is-offset-6">
                <VField>
                  <VControl icon="feather:search">
                    <input v-model="filtersTrans['global'].value" v-on:keyup.enter="fetchData()" type="text"
                      class="input is-rounded" placeholder="Search" />
                  </VControl>
                </VField>
              </div>
            </div>
          </template>
          <ColumnGroup type="header">
            <Row>
              <Column header="Tanggal" style="min-width: 100px" />
              <Column header="Nama Ruangan" style="min-width: 100px" />
              <Column header="Profesi" style="min-width: 100px" />
              <Column header="Nama Pegawai" style="min-width: 100px" />
              <Column header="Moment" style="min-width: 100px" />
              <Column header="Metode" style="min-width: 100px" />
              <Column header="Audit Cuci tangan" style="min-width: 100px" />
              <Column header="Kesempatan Ke" style="min-width: 100px" />
              <Column header="Total Nilai" style="min-width: 100px" />
              <Column header="Action" style="min-width: 100px" />
            </Row>
          </ColumnGroup>
          <template #groupheader="slotProps">
            <span class="vertical-align-middle ml-2 font-bold line-height-3">{{ slotProps.data.namalengkap
            }}</span>
          </template>
          <Column field="tanggal" />
          <Column field="namaruangan" />
          <Column field="jenispegawai" />
          <Column field="nama" />
          <Column field="indikasi" />
          <Column field="tindakan" />
          <Column field="langkah" />
          <Column field="kesempatan" />
          <Column field="snilailangkah" />
          <Column style="width: 7rem;text-align:center">
            <template #body="slotProps">
              <VIconButton outlined color="info" icon="fas fa-pen-square" :loading="isLoading" v-tooltip.top="'Edit'"
                style="margin-right:5px" @click="edit(slotProps.data)" />
              <VIconButton outlined color="danger" icon="fas fa-trash-alt" @click="hapus(slotProps.data)"
                :loading="isLoading" v-tooltip.top="'Hapus'" />
            </template>
          </Column>
        </DataTable>
      </div>
    </VCard>
  </div>

  <!--modal-->
  <VModal :open="modalIPCLN" title="Form IPCLN" :noclose="false" size="large" actions="right" @close="modalIPCLN = false">
    <template #content>
      <form class="modal-form custom-mod ">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-12">
                  <h1 class="c-title">Informasi</h1>
                </div>
                <div class="column is-6">
                  <VField label="Ruangan">
                    <AutoComplete v-model="item.objectruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan" />
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Tanggal">
                    <Calendar v-model="item.tanggal" selectionMode="single" :manualInput="true" class="w-100" showTime
                      :showIcon="true" hourFormat="24" :date-format="'yy-mm-dd'" />
                  </VField>
                </div>
                <div class="column is-6">
                  <VField class="is-autocomplete-select mt-3" v-slot="{ id }" label="Profesi">
                    <AutoComplete v-model="item.objectjenispegawaifk" :suggestions="d_JenisPegawai"
                      @complete="fetchJenisPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Jenis Pegawai" />
                  </VField>
                </div>
                <div class="column is-6">
                  <VField class="is-autocomplete-select mt-3" v-slot="{ id }" label="Nama Pegawai">
                    <AutoComplete v-model="item.objectpegawaifk" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai" />
                  </VField>
                </div>
                <div class="column is-12 py-1">
                  <h1 class="c-title">Moment</h1>
                </div>
                <div class="column is-5">
                  <VField label="Indikasi">
                    <VControl>
                      <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                        <div class="column is-12" v-if="d_Indikasi.length == 0">
                          <VPlaceloadText :lines="1" />
                        </div>
                        <div class="column is-12 mt-1 p-0" v-for="items in d_Indikasi" :key="items.value">
                          <VRadio v-model="item.indikasi" :value="items.value" class="p-0 mb-3" :label="items.label"
                            square color="primary" />
                        </div>
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Tindakan">
                    <VControl>
                      <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                        <div class="column is-12" v-if="d_Tindakan.length == 0">
                          <VPlaceloadText :lines="1" />
                        </div>
                        <div class="column is-12 mt-1 p-0" v-for="items in d_Tindakan" :key="items.value">
                          <VRadio v-model="item.tindakan" :value="items.value" class="p-0 mb-3" :label="items.label"
                            square color="primary" />
                        </div>
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="Kesempatan Ke">
                    <VInput type="number" v-model="item.kesempatan"></VInput>
                  </VField>
                </div>
                <div class="column is-12 py-1">
                  <h1 class="c-title">Langkah - langkah Cuci Tangan</h1>
                </div>
                <div class="column is-6" v-for="(data, index) in   langkahLangkapCuciTangan  ">
                  <h1 class="title-check">{{ data }}</h1>
                  <div class="columns is-multiline">
                    <VField>
                      <div class="column  mt-2">
                        <VCheckbox v-model="item['langkah' + index]" true-value="1" value="1" class="p-0 mb-3" label="Ya"
                          square color="primary" />
                      </div>
                    </VField>
                    <VField>
                      <div class="column  mt-2">
                        <VCheckbox v-model="item['langkah' + index]" value="0" true-value="0" class="p-0 mb-3"
                          label="Tidak" square color="primary" />
                      </div>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 py-1">
                  <h1 class="c-title">Audit Cuci Tangan</h1>
                </div>
                <div class="column is-12">
                  <div class="pt-4">
                    <VField v-for="(skor) in skors">
                      <VControl raw subcontrol class="p-0">
                        <VCheckbox class="pt-0" v-model="item.skor" :true-value="skor.nilai" :label="skor.nama"
                          color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="simpan()" :loading="isLoadingSave" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>
  <!--end modal -->
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useHead } from '@vueuse/head';
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import DataTable from 'primevue/datatable';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import Column from 'primevue/column';
import { FilterMatchMode } from 'primevue/api';
import { useApi } from '/@src/composable/useApi';
import * as H from '/@src/utils/appHelper';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import moment from 'moment';

useHead({
  title: 'Audit Cuci Tangan IPCN - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const expandedRowGroups: any = ref()
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, })
const isLoading: any = ref(false)
const isLoadingSave: any = ref(false)
const dataSource: any = ref([])
const d_Pegawai: any = ref([])
const d_Indikasi: any = ref([])
const d_Tindakan: any = ref([])
const d_Ruangan: any = ref([])
const modalIPCLN: any = ref(false)
const d_JenisPegawai: any = ref([])
const item: any = reactive({
  tanggal: new Date(),
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
});


const onRowGroupExpand = (event: any) => {

};
const onRowGroupCollapse = (event: any) => {

};
const langkahLangkapCuciTangan: any = ref([
  "1. Basahi kedua telapak tangan setinggi pertengahan lengan memakai air yang mengalir, ambil sabun kemudian usap dan gosok kedua telapak tangan secara lembut",
  "2. Usap dan gosok juga kedua punggung tangan secara bergantian",
  "3. Jangan lupa jari-jari tangan, gosok sela-sela jari hingga bersih",
  "4. Bersihkan ujung jari secara bergantian dengan mengatupkan",
  "5. Gosok dan putar kedua ibu jari secara bergantian",
  "6. Letakkan ujung jari ke telapak tangan kemudian gosok perlahan",
  "7. Bersihkan kedua pergelangan tangan secara bergantian dengan cara memutar, kemudian diakhiri dengan membilas seluruh bagian tangan dengan air bersih yang mengalir lalu keringkan memakai handuk atau tisu."
])
const skors: any = ref([
  {
    nama: 'Tidak bisa melakukan',
    nilai: 0
  },
  {
    nama: 'Melakukan tapi salah',
    nilai: 1
  },
  {
    nama: 'Melakukan dengan benar',
    nilai: 2
  },
])
const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
};
const fetchJenisPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/jenispegawai_m?select=id,jenispegawai&param_search=jenispegawai&query=${filter.query}&limit=10`
  ).then((response) => {
    d_JenisPegawai.value = response
  })
};
const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
};
const fetchIndikasi = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/indikasi_m?select=id,indikasi&param_search=indikasi&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Indikasi.value = response
  })
};
const fetchTindakan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/handhygiene_m?select=id,tindakan&param_search=tindakan&query=${filter.query}&limit=10`
  ).then((response) => {
    console.log(response);
    d_Tindakan.value = response
  })
};
const fetchData = async () => {
  isLoading.value = true
  let startDate = moment(item.filterTgl.start).format('YYYY-MM-DD');
  let endDate = moment(item.filterTgl.end).format('YYYY-MM-DD');
  let pegawai = item.namapegawai ? item.namapegawai.value : '';
  let namaruangan = item.namaruangan ? item.namaruangan.value : '';
  let indikasi = item.indikasiFilter ? item.indikasiFilter.value : '';
  useApi().get(
    `/ppi/get-data-kepatuhan-handhygiene?tglAwal=${startDate}&tglakhir=${endDate}&pgid=${pegawai}&bngid=${namaruangan}&indikasiid=${indikasi}`).then((response: any) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
        element.nama = element.namalengkap
        var langkah = element.langkah
        if (langkah == 0) {
          element.nilailangkah = 0
          element.snilailangkah = "0 %"
        } else if (langkah == 1) {
          element.nilailangkah = 50
          element.snilailangkah = "50 %"
        } else if (langkah == 2) {
          element.nilailangkah = 100
          element.snilailangkah = "100 %"
        }
      });
      dataSource.value = response.data
      isLoading.value = false
    }).catch((e: any) => {
      isLoading.value = false
    })

}
const showModal = async () => {
  modalIPCLN.value = true;
  await fetchIndikasi({ query: '' })
  await fetchTindakan({ query: '' })
}
const edit = async (e :any) => {
  item.norec = e.norec;
  item.tanggal = e.tanggal;
  item.objectruanganfk = { value: e.objectruanganfk, label: e.namaruangan }
  item.objectjenispegawaifk = { value: e.objectjenispegawaifk, label: e.jenispegawai }
  item.objectpegawaifk = { value: e.objectpegawaifk, label: e.namalengkap }
  item.indikasi = e.objectindikasifk
  item.tindakan = e.objecthygienefk
  item.skor = e.langkah
  item.kesempatan = e.kesempatan
  item.langkah0 = e.langkah1
  item.langkah1 = e.langkah2
  item.langkah2 = e.langkah3
  item.langkah3 = e.langkah4
  item.langkah4 = e.langkah5
  item.langkah5 = e.langkah6
  item.langkah6 = e.langkah7

  console.log(JSON.stringify(e));
  await showModal();

}
const hapus = async (e :any) => {
  isLoading.value = true;
  useApi().post(`/ppi/batal-kepatuhanhandhygiene`, { norec: e.norec }).then((response: any) => {
    isLoading.value = false
    fetchData()
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const exportExcel = () => {

  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['IPCLN'],
    [],
    ['NO', 'Tanggal', 'Nama Ruangan', 'Profesi', 'Nama Pegawai', 'Moment', 'Metode'],
    ...dataSource.value.map((e: any, index: number) => [
      index + 1,
      e.tanggal,
      e.namaruangan,
      e.jenispegawai,
      e.nama,
      e.indikasi,
      e.tindakan,
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
  const headerRange = XLSX.utils.decode_range(worksheet['!ref'] ?? 'A1:A1');
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
    worksheet[headerCell].s = headerStyle;
  }

  // Setting column widths
  const columnWidths = [5, 20, 25, 15, 30, 35, 35];

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
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 7 } };
  worksheet['!merges'] = [mergeTitle];
  let startDate = moment(item.filterTgl.start).format('YYYY-MM-DD');
  let endDate = moment(item.filterTgl.end).format('YYYY-MM-DD');
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Surveilans Ruangan', true);

  XLSXStyle.writeFile(workbook, `Data Surveilans Ruangan - ${startDate} - ${endDate}.xlsx`);
}

const simpan = async () => {
  var objSave = {
    "norec": item.norec ?? null,
    "tanggal": moment(item.tanggal).format('YYYY-MM-DD HH:mm'),
    "objectruanganfk": item.objectruanganfk ? item.objectruanganfk.value : null,
    "objectjenispegawaifk": item.objectjenispegawaifk ? item.objectjenispegawaifk.value : null,
    "objectpegawaifk": item.objectpegawaifk ? item.objectpegawaifk.value : null,
    "objectindikasifk": item.indikasi ? item.indikasi : null,
    "objecthygienefk": item.tindakan ? item.tindakan : null,
    "langkah": item.skor ? item.skor : 0,
    "kesempatan": item.kesempatan,
    "langkah1": item.langkah0 != undefined ? item.langkah0 : null,
    "langkah2": item.langkah1 != undefined ? item.langkah1 : null,
    "langkah3": item.langkah2 != undefined ? item.langkah2 : null,
    "langkah4": item.langkah3 != undefined ? item.langkah3 : null,
    "langkah5": item.langkah4 != undefined ? item.langkah4 : null,
    "langkah6": item.langkah5 != undefined ? item.langkah5 : null,
    "langkah7": item.langkah6 != undefined ? item.langkah6 : null,
  }
  isLoadingSave.value = true
  useApi().post(
    `/ppi/save-kepatuhanhandhygiene`, objSave).then((response: any) => {
      isLoadingSave.value = false
      modalIPCLN.value = false;
      fetchData()
    }).catch((e: any) => {
      modalIPCLN.value = false;
      isLoadingSave.value = false
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

.c-title {
  font-size: 20px;
  font-weight: 600;
}

.field>label {
  width: auto !important;
}

.title-check {
  font-family: var(--font);
  font-size: 0.9rem;
  color: var(--light-text) !important;
  font-weight: 400;
}
</style>
