<template>
  <div class="colum is-12">
    <TabView>
      <TabPanel header="Input APD">
        <VCard class="py-4">
          <div class="columns is-multiline form-search">
            <div class="column is-2">
              <VField label="Filter Bedasarakan Tanggal">
                <VControl class="prime-auto">
                  <Calendar v-model="item.bulan" selectionMode="single" :manualInput="false" :showIcon="true"
                    :date-format="'yy-mm-dd'" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:home" fullwidth class="prime-auto">
                  <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" :optionLabel="'label'"
                    @complete="fetchRuangan($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Pegawai" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="fa:user-md" fullwidth class="prime-auto">
                  <AutoComplete v-model="item.pegawai" :suggestions="d_Pegawai" :optionLabel="'label'"
                    @complete="fetchPegawai($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai..." />
                </VControl>
              </VField>
            </div>
            <div class="column mt-5 ">
              <VIconButton type="button" color="success" circle raised icon="fas fa-search" @click="fetchData()"
                :loading="isLoading">
              </VIconButton>
              <VButton type="button" icon="pi pi-plus" @click="generateTable()" color="info" class="ml-5" outlined circle
                raised v-tooltip-prime="item.isInput ? 'Tutup Table' : 'Input Edukasi'">{{ item.isInput ? 'Tutup' :
                  'Input' }}</VButton>
            </div>
          </div>
          <div class="table-scroll">
            <table border="1px" class="tg-table" v-if="item.isInput">
              <thead>
                <tr>
                  <th rowspan="2" style="min-width: 10px">No</th>
                  <th rowspan="2" style="min-width: 500px">Jenis Kegiatan</th>
                  <th :colspan="column.length">{{ item.bulanInput }}</th>
                </tr>
                <tr>
                  <td v-for="data in column" :key="data.title" style="min-width: 20px;">{{ data.title }}</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in kegiatan" :key="data.no">
                  <td style="min-width: 10px;">{{ data.no }}</td>
                  <td style="min-width: 500px;">{{ data.jeniskegiatan }}</td>
                  <td v-for="colum in column" :key="colum.title" style="min-width: 20px;">
                    <VField>
                      <VCheckbox v-model="item[data.no + '-' + colum.title]" class="pt-1 pb-1" :true-value="true"
                        color="primary" square></VCheckbox>
                    </VField>
                  </td>
                </tr>
              </tbody>
            </table>
            <table border="1px" class="tg-table" v-if="item.isShow">
              <thead>
                <tr>
                  <th rowspan="2" style="min-width: 10px">No</th>
                  <th rowspan="2" style="min-width: 500px">Jenis Kegiatan</th>
                  <th :colspan="column.length">{{ item.bulanInput }}</th>
                </tr>
                <tr>
                  <td v-for="data in column" :key="data.title" style="min-width: 20px;">{{ data.title }}</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in dataSource" :key="data.no">
                  <td style="min-width: 10px;">{{ data.no }}</td>
                  <td style="min-width: 500px;">{{ data.jeniskegiatan }}</td>
                  <td v-for="dateColumn in column" :key="dateColumn.title" style="min-width: 20px;">
                    {{ data[dateColumn.title] }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline" style="align-items:right">
              <div class="column is-10">
              </div>
              <div class="column is-1">
                <VButton icon="lnir lnir-arrow-left rem-100" @click="kembali()" light dark-outlined>Kembali</VButton>
              </div>
              <div class="column is-1">
                <VButton icon="feather:save" @click="Save()" :loading="isLoading" color="info">Simpan</VButton>
              </div>
            </div>
          </div>
        </VCard>
      </TabPanel>
    </TabView>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useHead } from '@vueuse/head';
import AutoComplete from 'primevue/autocomplete';
import moment from 'moment';
import { useApi } from '/@src/composable/useApi';
import Calendar from 'primevue/calendar';
import ProgressBar from 'primevue/progressbar';
import ColumnGroup from 'primevue/columngroup';
import * as H from '/@src/utils/appHelper'
import Row from 'primevue/row';
import { FilterMatchMode } from 'primevue/api';
import Tag from 'primevue/tag';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
useHead({
  title: 'Input Edukasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = reactive({
  bulan: new Date()
});
const d_Pegawai: any = ref([]);
const d_Ruangan: any = ref([]);
const isLoading: any = ref(false);
const column: any = ref([]);
const dataSource: any = ref([]);
const kegiatan: any = ref([
  {
    'no': '1', 'jeniskegiatan': 'Masker'
  },
  {
    'no': '2', 'jeniskegiatan': 'Hand Scone'
  },
  {
    'no': '3', 'jeniskegiatan': 'Sepatu Boot'
  },
  {
    'no': '4', 'jeniskegiatan': 'Penutup Kepala'
  },
  {
    'no': '5', 'jeniskegiatan': 'Penutup Kepala	'
  },
  {
    'no': '6', 'jeniskegiatan': 'Hand Srub'
  },
  {
    'no': '7', 'jeniskegiatan': 'APD yang digunakan'
  }
]);

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const fetchData = async () => {
  column.value = [];
  item.isInput = false;
  item.isShow = true;
  let ruanganfk = item.ruangan ? item.ruangan.value : '';
  let pegawaifk = item.pegawai ? item.pegawai.value : '';
  let bulan = moment(item.bulan).format("YYYY-MM-DD")
  createColumn();
  var dataGrid: any = [];
  await useApi().get(`/ppi/get-data-cheklis-apd?ruanganfk=${ruanganfk}&pegawaifk=${pegawaifk}&bulan=${bulan}`).then((response: any) => {
    response.map((element: any, index: number) => {
      element.isian = element.isi == '01' ? '✔' : '-'
      element.no = index + 1
    })
    if (response.length <= 0) {
      H.alert('warning', 'Data tidak ditemukan')
    }
    response.map((element: any) => {
      var customData: any = {};
      for (var key in element) {
        switch (key) {
          case 'tgl':
            var tgl = element.tgl;
            var day = tgl.slice(-2);
            console.log(parseInt(day));
            customData[parseInt(day)] = element.isian;
            break;
          default:
            customData[key] = element[key];
            break;
        }
      }
      dataGrid.push(customData);
    });
  })
  dataSource.value = dataGrid;
}
const generateTable = () => {
  column.value = [];
  item.isInput = item.isInput ? false : true
  createColumn();
}
const createColumn = function () {
  var year = parseInt(moment(item.bulan).format("Y"))
  var month = parseInt(moment(item.bulan).format("M"))
  item.bulanInput = moment(item.bulan).format("MMMM YYYY")
  var tempDate = new Date(year, month, 0);


  for (var i = 0; i < tempDate.getDate(); i++) {
    var day = (i + 1).toString().padStart(2, '0');
    var data = {
      field: "[" + (i + 1) + "]",
      title: (i + 1).toString(),
      key: day,
      format: "{0:n1}",
      width: "50px"
    };

    column.value.push(data);
  }
}

const Save = async () => {
  if (!item.ruangan) {
    H.alert("warning", "Ruangan Harus diisi !");
    return;
  }
  if (!item.pegawai) {
    H.alert("warning", "Nama Pegawai Harus diisi !");
    return;
  }
  const savedData: any = [];
  var month = moment(item.bulan).format("YYYY-MM-");
  kegiatan.value.forEach((kegiatanItem: any) => {
    column.value.forEach((colum: any) => {
      const isChecked = item[kegiatanItem.no + '-' + colum.title];
      if (isChecked) {
        const tgl = month + colum.key;
        const newData = {
          jeniskegiatan: kegiatanItem.jeniskegiatan,
          tgl: tgl,
          isi: "01"
        };

        savedData.push(newData);
      }
    });
  });

  var objSave = {
    'ruanganfk': item.ruangan ? item.ruangan.value : null,
    'pegawaifk': item.pegawai ? item.pegawai.value : null,
    'data': savedData
  }
  await useApi().post('/ppi/save-data-apd', objSave).then((response: any) => {
    fetchData();
  })
}
const kembali = () => {
  window.history.back()
}
</script>
<style lang="scss">
.tg-table {
  width: 100%;
  border: 1px solid rgb(118, 118, 118);
  border-collapse: collapse;

  th,
  td {
    border: 1px solid rgb(118, 118, 118);
    vertical-align: middle;
    text-align: center;
    padding: 8px;

    &:first-child {
      border-right: 1px solid rgb(118, 118, 118);
    }
  }

  th {
    background-color: #f2f2f2;
  }
}

.table-scroll {
  overflow-x: auto;
}
</style>
