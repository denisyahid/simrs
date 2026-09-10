<template>
  <div class="colum is-12">
    <TabView>
      <TabPanel header="Input Data IPCLN">
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
              <VButton type="button" icon="pi pi-plus" @click="generateTable()" color="info" class="ml-5" outlined
                circle raised v-tooltip-prime="item.isInput ? 'Tutup Table' : 'Input Edukasi'">{{ item.isInput ? 'Tutup'
                    :
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
                      <VInput v-model="item[data.no + '-' + colum.title]" v-if="data.no == '6' || data.no == '2'"
                        placeholder="Masukan data">
                      </VInput>
                      <VCheckbox v-model="item[data.no + '-' + colum.title]" class="pt-1 pb-1" :true-value="true"
                        color="primary" v-else square></VCheckbox>
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
                <tr v-for="(data, index) in dataSource" :key="data.no">
                  <td style="min-width: 10px;">{{ index + 1 }}</td>
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
                <VButton icon="feather:save" @click="Save()" :loading="isLoadingSave" color="info">Simpan</VButton>
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
const dataSource: any = ref([]);
const d_Ruangan: any = ref([]);
const isLoading: any = ref(false);
const isLoadingSave: any = ref(false);
const column: any = ref([]);
const dataARR: any = ref([]);
const kegiatan: any = ref([
  {
    'no': '1', 'jeniskegiatan': 'Surveilans'
  },
  {
    'no': '2', 'jeniskegiatan': 'Memberikan edukasi pengunjung apabila terdapat potensi KLB'
  },
  {
    'no': '3', 'jeniskegiatan': 'Memberikan motivasi dan mengingatkan staf ruangan mengenal standar PPI'
  },
  {
    'no': '4', 'jeniskegiatan': 'Memonitor staf di ruangan dalam penerapan kewaspadaan isolasi'
  },
  {
    'no': '5', 'jeniskegiatan': 'Melapor kepada IPCN apabila ada kecurigaan HAIs pada pasien'
  },
  {
    'no': '6', 'jeniskegiatan': 'Memantau atau memberikan penyuluhan bagi pasien'
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
const generateTable = () => {
  column.value = [];
  item.isInput = item.isInput ? false : true
  item.isShow = false;
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
  dataARR.value = kegiatan.value.map((data: any) => {
    const listTgl = [];
    const isCheckbox = data.no != 2 && data.no != 6;
    const isInput = data.no == '2' || data.no == '6';

    if (isCheckbox) {
      for (let j = 0; j < column.value.length; j++) {
        const columnData = column.value[j];
        const colDateIdx = parseInt(columnData.title);
        const date = `${item.bulan}`;
        const value = item[data.no + '-' + columnData.title];

        if (value !== undefined) {
          listTgl.push({
            tgl: date,
            jeniskegiatan: data.jeniskegiatan,
            isi: value ? "1" : ""
          });
        }
      }
    } else {
      for (let j = 0; j < column.value.length; j++) {
        const columnData = column.value[j];
        const colDateIdx = parseInt(item[data.jeniskegiatan + '-Tanggal']);
        const date = `${item.bulan}`;
        let x = data.no + '-' + columnData.title;
        const isiValue = item[data.no + '-' + columnData.title];
        if (isiValue !== undefined) {
          listTgl.push({
            tgl: date,
            isi: isiValue,
            jeniskegiatan: data.jeniskegiatan
          });
        }
      }
    }
    return {
      ...data,
      listTgl
    };
  });

  var data2 = [];
  for (let i = 0; i < dataARR.value.length; i++) {
    var isi = '';
    if (dataARR.value[i].listTgl.length > 0) {
      dataARR.value[i].listTgl.forEach((tglItem: any) => {
        console.log(tglItem.tgl)
        data2.push({
          'tgl': moment(tglItem.tgl).format("YYYY-MM-DD"),
          'isi': tglItem.isi === '1' ? '01' : (tglItem.isi ?? ""),
          'jeniskegiatan': dataARR.value[i].jeniskegiatan
        });
      });
    } else {
      console.log(dataARR.value[i]);
      data2.push({
        'tgl': [],
        'isi': dataARR.value[i].isi === '1' ? '01' : (dataARR.value[i].isi ?? ""),
        'jeniskegiatan': dataARR.value[i].jeniskegiatan
      });
    }
  }

  let objSave = {
    'ruanganfk': item.ruangan ? item.ruangan.value : null,
    'pegawaifk': item.pegawai ? item.pegawai.value : null,
    'data': data2
  };
  isLoadingSave.value = true
  await useApi().post(
    `/ppi/save-data-edukasi`, objSave).then((response: any) => {
      isLoadingSave.value = false
    }, (error) => {
      isLoadingSave.value = false
    })
}

const kembali = () => {
  window.history.back()
}
const fetchData = async () => {
  column.value = [];
  item.isInput = false;
  item.isShow = true;
  let ruangan = item.ruangan ? item.ruangan.value : '';
  let pegawai = item.pegawai ? item.pegawai.value : '';
  let bulan = moment(item.bulan).format("MM.YYYY");
  createColumn();
  var dataGrid: any = [];
  isLoading.value = true
  await useApi().get(`/ppi/data-ipcln?ruangan=${ruangan}&pegawai=${pegawai}&bulan=${bulan}`).then((response: any) => {
    response.map((element: any, index: number) => {
      element.isian = element.isi == '01' ? '✔' : element.isi
      element.no = index + 1
    })
    isLoading.value = false
    if (response.length <= 0) {
      H.alert('warning', 'Data tidak ditemukan');
      return;
    }
    response.map((element: any) => {
      var customData: any = {};
      for (var key in element) {
        switch (key) {
          case 'tgl':
            var tgl = element.tgl;
            var day = tgl.slice(-2);
            customData[parseInt(day)] = element.isian;
            break;
          default:
            customData[key] = element[key];
            break;
        }
      }
      dataGrid.push(customData);
    });
    dataSource.value = dataGrid;
  })
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
