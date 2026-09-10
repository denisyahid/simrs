<template>
  <div class="colum is-12">
    <TabView>
      <TabPanel header="Input Supervisi">
        <VCard class="py-4">
          <div class="columns is-multiline form-search">
            <div class="column is-2">
              <VField label="Tanggal">
                <VControl class="prime-auto">
                  <Calendar v-model="item.tanggal" selectionMode="single" :manualInput="true" class="w-100 is-rounded"
                    showTime :showIcon="true" hourFormat="24" :date-format="'yy-mm-dd'" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Departemen" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:home" fullwidth class="prime-auto">
                  <AutoComplete v-model="item.departemenfk" :suggestions="d_Departement" :optionLabel="'label'"
                    @complete="fetchDepartement($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Departement..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:home" fullwidth class="prime-auto">
                  <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" :optionLabel="'label'"
                    @complete="fetchRuangan($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan..." />
                </VControl>
              </VField>
            </div>
            <div class="column mt-5 ">
              <VButton type="button" icon="pi pi-plus" @click="InputSupervisi()" color="info" class="ml-5" outlined circle
                raised v-tooltip-prime="item.isInput ? 'Tutup Table' : 'Input Edukasi'">{{ item.isInput ? 'Tutup' :
                  'Input' }}</VButton>
            </div>
          </div>
          <Divider />
          <div class="columns is-multiline">
            <div class="column is-3" style="margin-top:10px; text-align: right;">
              <VCardCustom :style="'padding:5px 15px;margin:0;background:#fafafa'">
                <div :class="'label-status info'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1">Iya</span>
                </div>
                <small class="text-bold-custom">{{ item.jmliya ?? 0 }}</small>
              </VCardCustom>
            </div>
            <div class="column is-3" style="margin-top:10px; text-align: right;">
              <VCardCustom :style="'padding:5px 15px;margin:0;background:#fafafa'">
                <div :class="'label-status danger'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1">Tidak</span>
                </div>
                <small class="text-bold-custom">{{ item.jmltidak ?? 0 }}</small>
              </VCardCustom>
            </div>
            <div class="column is-3" style="margin-top:10px; text-align: right;">
              <VCardCustom :style="'padding:5px 15px;margin:0;background:#fafafa'">
                <div :class="'label-status warning'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1">N/A</span>
                </div>
                <small class="text-bold-custom">{{ item.jmlna ?? 0 }}</small>
              </VCardCustom>
            </div>
            <div class="column is-3" style="margin-top:10px; text-align: right;">
              <VCardCustom :style="'padding:5px 15px;margin:0;background:#fafafa'">
                <div :class="'label-status primary'">
                  <i aria-hidden="true" class="fas fa-circle"></i>
                  <span class="ml-1">Nilai skoring</span>
                </div>
                <small class="text-bold-custom">{{ item.skor ?? 0 }}</small>
              </VCardCustom>
            </div>
          </div>
          <div>
            <div class="column is-12">
              <DataTable v-model:expandedRowGroups="expandedRowGroups" :class="`p-datatable-small`" :value="dataSource"
                v-model:filters="filters" :globalFilterFields="['indikatoripcn', 'kelompok']" rowGroupMode="subheader"
                :rowsPerPageOptions="[5, 10, 25, 100]" :rows="5" paginator groupRowsBy="kelompok" :loading="isLoading"
                @rowgroup-expand="onRowGroupExpand" @rowgroup-collapse="onRowGroupCollapse" sortMode="single"
                sortField="kelompok" :sortOrder="5" showGridlines editMode="cell" @cell-edit-complete="onCellEditComplete"
                :editable="true">
                <template #header>
                  <div class="column is-3 pb-0" style="margin-left: auto;">
                    <VField label="Cari">
                      <VInput v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
                    </VField>
                  </div>
                </template>
                <ColumnGroup type="header">
                  <Row>
                    <Column class="align-items-center" header="No" :rowspan="2" style="min-width: 100px" />
                    <Column class="align-items-center" header="Indikator" :rowspan="2" style="min-width: 100px" />
                    <Column class="align-items-center" header="Temuan" style="min-width: 200px" :colspan="4" />
                    <Column class="align-items-center" header="REKOMENDASI / RTL" :rowspan="2" style="min-width: 100px" />
                  </Row>
                  <Row>
                    <Column class="align-items-center" header="Ya" style="min-width: 25px" />
                    <Column class="align-items-center" header="Tidak" style="min-width: 25px" />
                    <Column class="align-items-center" header="Na" style="min-width: 25px" />
                    <Column class="align-items-center" header="Keterangan" style="min-width: 25px" />
                  </Row>
                </ColumnGroup>
                <template #groupheader="slotProps">
                  <span class="vertical-align-middle ml-2 font-bold line-height-3">{{ slotProps.data.kelompok
                  }}</span>
                </template>
                <Column field="no" />
                <Column field="indikatoripcn" />
                <!--
                <Column field="ya" />
                <Column field="tidak" />
                <Column field="na" />
                <Column field="keterangan" />
                <Column field="rekomendasi" /> -->
                <Column v-for="col in columns" :key="col.field" :field="col.field" :header="col.header" :style="col.style"
                  :editable="col.editable">
                  <template #editor="{ data, field }">
                    <template v-if="field !== 'xx'">
                      <InputText v-model="data[field]" autofocus />
                    </template>
                    <template v-else>
                      <InputText v-model="data[field]" autofocus />
                    </template>
                  </template>
                </Column>
              </DataTable>
            </div>
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
import { useApi } from '/@src/composable/useApi';
import Calendar from 'primevue/calendar';
import * as H from '/@src/utils/appHelper';
import { FilterMatchMode } from 'primevue/api';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Divider from 'primevue/divider';
import moment from 'moment';
useHead({
  title: 'Supervisi IPCN - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = reactive({
  tanggal: new Date()
});
const expandedRowGroups: any = ref()
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, })
const d_Departement: any = ref([]);
const d_Ruangan: any = ref([]);
const isLoading: any = ref(false);
const dataSource: any = ref([]);
const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const columns = ref([
  { field: 'ya', header: 'Ya', style: 'min-width: 25px', editable: true },
  { field: 'tidak', header: 'Tidak', style: 'min-width: 25px', editable: true },
  { field: 'na', header: 'Na', style: 'min-width: 25px', editable: true },
  { field: 'keterangan', header: 'Keterangan', style: 'min-width: 25px', editable: true },
  { field: 'rekomendasi', header: 'REKOMENDASI / RTL', style: 'min-width: 100px', editable: true },
]);

const formatCurrency = (value: any) => {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
}
const onRowGroupExpand = (event: any) => {

};
const onRowGroupCollapse = (event: any) => {

};
const fetchDepartement = async (filter: any) => {
  delete item.ruanganfk
  await useApi().get(
    `emr/dropdown/departemen_m?select=id,namadepartemen&param_search=namadepartemen&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Departement.value = response
  })
}
const fetchRuangan = async (filter: any) => {
  let dep = item.departemenfk ? item.departemenfk.value : '';
  await useApi().get(
    `/rekammedis/get-ruangan-by-departement?idDepartement=${dep}&ruangan=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  })
}

const InputSupervisi = async () => {
  if (!item.departemenfk) {
    H.alert('warning', 'Departemen Harus Diisi!');
    return;
  }
  let departemenfk = item.departemenfk ? item.departemenfk.value : ''
  await useApi().get(`/ppi/get-data-indikator-ppi?objectdepartemenfk=${departemenfk}`).then((response: any) => {
    response.map((element: any, index: number) => {
      element.ya = 0
      element.tidak = 0
      element.na = 0
      element.rekomendasi = ''
      element.keterangan = ''
      element.no = index + 1
    })
    dataSource.value = response
  })
}
const onCellEditComplete = (event: any) => {
  let { data, newValue, field, newData } = event;
  if (newValue != undefined) {
    data[field] = newValue
  }
  var nilaiiya = 0;
  var nilaitidak = 0;
  var nilaina = 0;
  var total = 0;
  dataSource.value.map((element: any, index: number) => {
    element.ya = parseFloat(element.ya)
    element.tidak = parseFloat(element.tidak)
    element.na = parseFloat(element.na)
    nilaiiya += parseFloat(element.ya)
    nilaitidak += parseFloat(element.tidak)
    nilaina += parseFloat(element.na)
  })
  total = nilaiiya + nilaitidak + nilaina;
  let skor = (nilaiiya / total) * 100;
  item.jmliya = nilaiiya;
  item.jmltidak = nilaitidak;
  item.jmlna = nilaina;
  item.skor = skor.toFixed(2)
}
const kembali = () => {
  window.history.back()
}

const Save = async () => {
  if (!item.departemenfk) {
    H.alert('warning', 'Departemen Harus Diisi!');
    return;
  }
  if (!item.ruanganfk) {
    H.alert('warning', 'Ruangan Harus Diisi!');
    return;
  }
  isLoading.value = true;
  var objSave = {
    norec: item.norec ?? '',
    tanggal: moment(item.tanggal).format('YYYY-MM-DD HH:mm'),
    departemenfk: item.departemenfk ? item.departemenfk.value : '',
    ruanganfk: item.ruanganfk ? item.ruanganfk.value : '',
    jumlahiya: item.jmliya,
    jumlahtidak: item.jmltidak,
    jumlahna: item.jmlna,
    skorkepatuhan: parseFloat(item.skor),
    detailS: dataSource.value
  }
  await useApi().post('/ppi/save-suvervisi-ipcn', objSave).then((response: any) => {
    clear();
    isLoading.value = false;
  });

}

const clear = () => {
  dataSource.value = []
  delete item.departemenfk
  delete item.ruanganfk
}
</script>
<style lang="scss"></style>
