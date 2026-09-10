<template>
  <VCard>
    <div>
      <h3 class="title is-5 mb-2 mr-1">Formulir RL 4.3</h3>
      <span>10 BESAR KEMATIAN PENYAKIT RAWAT INAP</span>
    </div>
    <div class="columns">
      <div class="column is-9">
        <div class="column" v-if="isLoading">
          <VPlaceloadWrap v-for="data in 10">
            <VPlaceload class="mx-2 mb-3" />
          </VPlaceloadWrap>
        </div>
        <div class="column" v-else-if="dataSource.length == 0">
          <VPlaceholderPage title="Data Tidak di Temukan." subtitle="Silakan gunakan filter lain" larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>
        </div>
        <div class="column" v-else>
          <DataTable v-model:expandedRowGroups="expandedRowGroups" :class="`p-datatable-small`" :value="dataSource"
            v-model:filters="filtersTrans" :globalFilterFields="['kddiagnosa']" :rowsPerPageOptions="[10]"
            :rows="10" paginator :loading="isLoading" sortMode="single" :sortOrder="5" showGridlines>

            <template #header>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="info" outlined circle raised
                    v-tooltip-prime="'Export'" @click="exportExcel()">
                    Export Excel
                  </VButton>
                </div>
                
              </div>
            </template>
            <ColumnGroup type="header">
              <Row>
                <Column header="No" :rowspan="2" />
                <Column header="Kelompok ICD-10" :rowspan="2" />
                <!-- <Column header="No. Daftar Terperinci" :rowspan="3" /> -->
                <Column header="Kelompok Diagnosis Penyakit" :rowspan="2" />
                <Column header="Jumlah Pasien Hidup dan Mati menurut Jenis Kelamin" :colspan="3"   />
                <Column header="Jumlah Pasien Keluar Mati" :colspan="3" />
              </Row>
              <Row>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="Total" style="text-align: center;"/>
                <Column header="L" style="text-align: center;" />
                <Column header="P" style="text-align: center;"/>
                <Column header="Total" style="text-align: center;" />
              </Row>
            </ColumnGroup>
            <Column field="no" style="min-width: 30px;" />
            <Column field="kddiagnosa" style="min-width: 100px;" />
            <Column field="namadiagnosa" style="min-width: 100px;" />
            <Column field="hiduplaki" style="min-width: 20px; text-align: center;" />
            <Column field="hidupperempuan" style="min-width: 20px;text-align: center;" />
            <Column field="jumlahhiduppl" style="min-width: 20px;text-align: center;" />
            <Column field="matilaki" style="min-width: 20px;text-align: center;" />
            <Column field="matiperempuan" style="min-width: 20px;text-align: center;" />
            <Column field="matipl" style="min-width: 20px;text-align: center;" />
          </DataTable>
        </div>
      </div>
      <div class="column is-3">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1">Filters</h3>
          </div>
          <div class="column is-6">
            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              Clear All
            </a>
          </div>
          <div class="column is-12">
            <VField label="Periode">
              <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>

                <template #default="{ inputValue, inputEvents }">
                  <VField addons>
                    <VControl icon="feather:calendar">
                      <VInput :value="inputValue.start" v-on="inputEvents.start" />
                    </VControl>
                    <VControl>
                      <VButton static icon="feather:arrow-right" />
                    </VControl>
                    <VControl subcontrol icon="feather:calendar">
                      <VInput :value="inputValue.end" v-on="inputEvents.end" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="fetchData()" :loading="isLoading" type="button" icon="feather:search"
              class="is-fullwidth mr-3" color="info" raised>
              Apply Filters
            </VButton>
          </div>
        </div>
      </div>
    </div>
  </VCard>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { FilterMatchMode } from 'primevue/api'
import ColumnGroup from 'primevue/columngroup'
import Row from 'primevue/row'
import Column from 'primevue/column'
import DataTable from 'primevue/datatable'
import * as XLSX from "xlsx"
import * as XLSXStyle from 'xlsx-js-style'
useHead({
  title: 'RL 4.3 10 Besar Kematian Penyakit Rawat Inap' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = reactive({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const isLoading: any = ref(false)
const dataSource: any = ref([])
const expandedRowGroups: any = ref();
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const onRowGroupExpand = (event) => {

};
const onRowGroupCollapse = (event) => {

};
const clearFilter = () => {

}
const fetchData = async () => {
  isLoading.value = true;
  let tglAwal = moment(item.periode.start).format("YYYY-MM-DD 00:00:00");
  let tglAkhir = moment(item.periode.end).format("YYYY-MM-DD 23:59:59");
  await useApi().get(`laporan/get-laporan-rl43?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`).then((response: any) => {
    if (response.data.length > 0) {
      response.data.forEach((element: any, index: number) => {
        element.no = index + 1
      });
    }
    dataSource.value = response.data
    isLoading.value = false;
  })
}

const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheetData = [
    ['Daftar RL 4.3 10 BESAR KEMATIAN PENYAKIT RAWAT INAP'],
    [],
    [
      'NO',
      'Kelompok ICD-10',
      'Kelompok Diagnosis Penyakit',
      'Jumlah Pasien Hidup dan Mati Menurut Jenis kelamin',
      null,null,
      'Jumlah Pasien Keluar Mati',
      null,null,
      
    ],
    [
      null,
      null,
      null,
     'Laki-Laki Hidup',
      'Perempuan Hidup',
      'Jumlah Hidup',
      'Laki-Laki Mati',
      'Perempuan Mati',
      'Jumlah Mati',
    ],
    ...dataSource.value.map((e: any, index: number) => [
      index + 1,
      e.kddiagnosa,
      e.namadiagnosa,
      e.hiduplaki,
      e.hidupperempuan,
      e.jumlahhiduppl,
      e.matilaki,
      e.matiperempuan,
      e.matipl,
    ]),
  ];


    const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);

    // Define styles
    const headerStyle = {
        alignment: { horizontal: 'center', vertical: 'center' },
        font: { color: { rgb: 'FFFFFF' }, bold: true },
        fill: { fgColor: { rgb: '807C7C' } }
    };

    const titleStyle = {
        alignment: { horizontal: 'center', vertical: 'center' },
        font: { bold: true, sz: 18 }
    };

    // Apply title style
    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = titleStyle;

    // Apply header styles
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let row = 2; row <= 3; row++) {
        for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
            const cell = XLSX.utils.encode_cell({ r: row, c: col });
            if (worksheet[cell]) {
                worksheet[cell].s = headerStyle;
            }
        }
    }

    // Column widths
    worksheet['!cols'] = [
        { wch: 10 }, { wch: 15 }, { wch: 10 }, { wch: 10 }, { wch: 20 },
        { wch: 30 }, { wch: 15 }, { wch: 15 }, { wch: 10 }, { wch: 10 },
        { wch: 10 }, { wch: 10 }, { wch: 25 }, { wch: 25 }, { wch: 20 },
        { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 },
        { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 20 },
        { wch: 20 }, { wch: 20 }
    ];

    // Merge cells
    worksheet['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } },
        { s: { r: 2, c: 0 }, e: { r: 3, c: 0 } },
        { s: { r: 2, c: 1 }, e: { r: 3, c: 1 } },
        { s: { r: 2, c: 2 }, e: { r: 3, c: 2 } },
        { s: { r: 2, c: 3 }, e: { r: 2, c: 5 } }, // NRM
        { s: { r: 2, c: 6 }, e: { r: 2, c: 8 } }, // Tipe Pasien
    ];
    XLSX.utils.book_append_sheet(workbook, worksheet, '10 Besar penyakit Rawat Inap');
    XLSXStyle.writeFile(workbook, 'RL 4.3 10 Besar Kematian Penyakit Rawat Inap.xlsx');
};


</script>

<style lang="scss"></style>
