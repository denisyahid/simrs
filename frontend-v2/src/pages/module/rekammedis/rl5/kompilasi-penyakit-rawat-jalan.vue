<template>
  <VCard>
    <div>
      <h3 class="title is-5 mb-2 mr-1">Formulir RL 5.1</h3>
      <span>Kompilasi Penyakit/Morbiditas Pasien Rawat Jalan</span>
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
                <Column header="Kode ICD" :rowspan="2" />
                <Column header="Diagnosis Penyakit" :rowspan="2" />
                <Column header="< 1 jam" :colspan="2" />
                <Column header="1-23 jam" :colspan="2" />
                <Column header="1-7 hr" :colspan="2" />
                <Column header="8-28 hr" :colspan="2" />
                <Column header="29 hr –<3 bln" :colspan="2" />
                <Column header="3 - < 6 bln" :colspan="2" />
                <Column header="6-11 bln" :colspan="2" />
                <Column header="1-4 th" :colspan="2" />
                <Column header="5-9 th" :colspan="2" />
                <Column header="10-14 th" :colspan="2" />
                <Column header="15-19 th" :colspan="2" />
                <Column header="20-24 th" :colspan="2" />
                <Column header="25-29 th" :colspan="2" />
                <Column header="30-34 th" :colspan="2" />
                <Column header="35-39 th" :colspan="2" />
                <Column header="40-44 th" :colspan="2" />
                <Column header="45-49 th" :colspan="2" />
                <Column header="50-54 th" :colspan="2" />
                <Column header="55-59 th" :colspan="2" />
                <Column header="60-64 th" :colspan="2" />
                <Column header="65-69 th" :colspan="2" />
                <Column header="70-74 th" :colspan="2" />
                <Column header="75-79 th" :colspan="2" />
                <Column header="80-84 th" :colspan="2" />
                <Column header="≥85 th" :colspan="2" />
                <!-- <Column header="No. Daftar Terperinci" :rowspan="3" /> -->
                <Column header="Jumlah Kasus Baru Menurut Jenis Kelamin" :colspan="3"   />
                <Column header="Jumlah Kunjungan" :colspan="3" />
              </Row>
              <Row>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
                <Column header="L" style="text-align: center;"/>
                <Column header="P" style="text-align: center;"/>
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
            <Column field="jumlahkurang1jaml" style="min-width: 30px;" />
            <Column field="jumlahkurang1jamp" style="min-width: 30px;" />
            <Column field="jumlahkurang23jaml" style="min-width: 30px;" />
            <Column field="jumlahkurang23jamp" style="min-width: 30px;" />
            <Column field="jumlah17hl" style="min-width: 30px;" />
            <Column field="jumlah17hp" style="min-width: 30px;" />
            <Column field="jumlah828hl" style="min-width: 30px;" />
            <Column field="jumlah828hp" style="min-width: 30px;" />
            <Column field="jumlah293l" style="min-width: 30px;" />
            <Column field="jumlah293p" style="min-width: 30px;" />
            <Column field="jumlah36l" style="min-width: 30px;" />
            <Column field="jumlah36p" style="min-width: 30px;" />
            <Column field="jumlah611l" style="min-width: 30px;" />
            <Column field="jumlah611p" style="min-width: 30px;" />
            <Column field="jumlah14l" style="min-width: 30px;" />
            <Column field="jumlah14p" style="min-width: 30px;" />
            <Column field="jumlah59l" style="min-width: 30px;" />
            <Column field="jumlah59p" style="min-width: 30px;" />
            <Column field="jumlah1014l" style="min-width: 30px;" />
            <Column field="jumlah1014p" style="min-width: 30px;" />
            <Column field="jumlah1519l" style="min-width: 30px;" />
            <Column field="jumlah1519p" style="min-width: 30px;" />
            <Column field="jumlah2024l" style="min-width: 30px;" />
            <Column field="jumlah2024p" style="min-width: 30px;" />
            <Column field="jumlah2529l" style="min-width: 30px;" />
            <Column field="jumlah2529p" style="min-width: 30px;" />
            <Column field="jumlah3034l" style="min-width: 30px;" />
            <Column field="jumlah3034p" style="min-width: 30px;" />
            <Column field="jumlah3539l" style="min-width: 30px;" />
            <Column field="jumlah3539p" style="min-width: 30px;" />
            <Column field="jumlah4044l" style="min-width: 30px;" />
            <Column field="jumlah4044p" style="min-width: 30px;" />
            <Column field="jumlah4549l" style="min-width: 30px;" />
            <Column field="jumlah4549p" style="min-width: 30px;" />
            <Column field="jumlah5054l" style="min-width: 30px;" />
            <Column field="jumlah5054p" style="min-width: 30px;" />
            <Column field="jumlah5559l" style="min-width: 30px;" />
            <Column field="jumlah5559p" style="min-width: 30px;" />
            <Column field="jumlah6064l" style="min-width: 30px;" />
            <Column field="jumlah6064p" style="min-width: 30px;" />
            <Column field="jumlah6569l" style="min-width: 30px;" />
            <Column field="jumlah6569p" style="min-width: 30px;" />
            <Column field="jumlah7074l" style="min-width: 30px;" />
            <Column field="jumlah7074p" style="min-width: 30px;" />
            <Column field="jumlah7579l" style="min-width: 30px;" />
            <Column field="jumlah7579p" style="min-width: 30px;" />
            <Column field="jumlah8084l" style="min-width: 30px;" />
            <Column field="jumlah8084p" style="min-width: 30px;" />
            <Column field="jumlah85l" style="min-width: 30px;" />
            <Column field="jumlah85p" style="min-width: 30px;" />
            <Column field="barulaki" style="min-width: 50px; text-align: center;" />
            <Column field="baruperempuan" style="min-width: 50px;text-align: center;" />
            <Column field="totalbaru" style="min-width: 50px;text-align: center;" />
            <Column field="kunjlaki" style="min-width: 50px;text-align: center;" />
            <Column field="kunjperempuan" style="min-width: 50px;text-align: center;" />
            <Column field="totalkunj" style="min-width: 50px;text-align: center;" />
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
  title: 'RL 5.1 Kompilasi Penyakit/Morbiditas Pasien Rawat Jalan' + import.meta.env.VITE_PROJECT,
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
  await useApi().get(`laporan/get-laporan-rl51?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`).then((response: any) => {
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
    ['Daftar RL 5.1 Kompilasi Morbiditas Pasien Rawat Jalan'],
    [],
    [
      'NO',
      'Kode ICD',
      'Diagnosis Penyakit',
      '< 1 jam',null,'1-23 jam',null,'1-7 hr',null,'8-28 hr',null,'29 hr –<3 bln',null,'3 - < 6 bln',null,'6-11 bln',null,'1-4 th',null,'5-9 th',null,'10-14 th',null,
      '15-19 th',null,'20-24 th',null,'25-29 th',null,'30-34 th',null,'35-39 th',null,'40-44 th',null,'45-49 th',null,'50-54 th',null,'55-59 th',null,'60-64 th',null,
      '65-69 th',null,'70-74 th',null,'75-79 th',null,'80-84 th',null,'≥85 th',null,'Jumlah Kasus Baru Menurut Jenis Kelamin',null,null,'Jumlah Kunjungan',null,null,
    ],
    [
     null,null,null,'L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','L','P','Total','L','P','Total'
    ],
    ...dataSource.value.map((e: any, index: number) => [
      index + 1,
      e.kddiagnosa,
      e.namadiagnosa,
      e.jumlahkurang1jaml,
      e.jumlahkurang1jamp,
      e.jumlahkurang23jaml,
      e.jumlahkurang23jamp,
      e.jumlah17hl,
      e.jumlah17hp,
      e.jumlah828hl,
      e.jumlah828hp,
      e.jumlah293l,
      e.jumlah293p,
      e.jumlah36l,
      e.jumlah36p,
      e.jumlah611l,
      e.jumlah611p,
      e.jumlah14l,
      e.jumlah14p,
      e.jumlah59l,
      e.jumlah59p,
      e.jumlah1014l,
      e.jumlah1014p,
      e.jumlah1519l,
      e.jumlah1519p,
      e.jumlah2024l,
      e.jumlah2024p,
      e.jumlah2529l,
      e.jumlah2529p,
      e.jumlah3034l,
      e.jumlah3034p,
      e.jumlah3539l,
      e.jumlah3539p,
      e.jumlah4044l,
      e.jumlah4044p,
      e.jumlah4549l,
      e.jumlah4549p,
      e.jumlah5054l,
      e.jumlah5054p,
      e.jumlah5559l,
      e.jumlah5559p,
      e.jumlah6064l,
      e.jumlah6064p,
      e.jumlah6569l,
      e.jumlah6569p,
      e.jumlah7074l,
      e.jumlah7074p,
      e.jumlah7579l,
      e.jumlah7579p,
      e.jumlah8084l,
      e.jumlah8084p,
      e.jumlah85l,
      e.jumlah85p,
      e.barulaki,
      e.baruperempuan,
      e.totalbaru,
      e.kunjlaki,
      e.kunjperempuan,
      e.totalkunj,
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
    // worksheet['!cols'] = [
    //     { wch: 10 }, { wch: 15 }, { wch: 10 }, { wch: 10 }, { wch: 20 },
    //     { wch: 30 }, { wch: 15 }, { wch: 15 }, { wch: 10 }, { wch: 10 },
    //     { wch: 10 }, { wch: 10 }, { wch: 25 }, { wch: 25 }, { wch: 20 },
    //     { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 },
    //     { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 20 },
    //     { wch: 20 }, { wch: 20 }
    // ];

    // Merge cells
    worksheet['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } },
        { s: { r: 2, c: 0 }, e: { r: 3, c: 0 } },
        { s: { r: 2, c: 1 }, e: { r: 3, c: 1 } },
        { s: { r: 2, c: 2 }, e: { r: 3, c: 2 } },
        { s: { r: 2, c: 3 }, e: { r: 2, c: 4 } },
        { s: { r: 2, c: 5 }, e: { r: 2, c: 6 } }, 
        { s: { r: 2, c: 7 }, e: { r: 2, c: 8 } },
        { s: { r: 2, c: 9 }, e: { r: 2, c: 10 } },
        { s: { r: 2, c: 11 }, e: { r: 2, c: 12 } },
        { s: { r: 2, c: 13 }, e: { r: 2, c: 14 } },
        { s: { r: 2, c: 15 }, e: { r: 2, c: 16 } },
        { s: { r: 2, c: 17 }, e: { r: 2, c: 18 } },
        { s: { r: 2, c: 19 }, e: { r: 2, c: 20 } },
        { s: { r: 2, c: 21 }, e: { r: 2, c: 22 } },
        { s: { r: 2, c: 23 }, e: { r: 2, c: 24 } },
        { s: { r: 2, c: 25 }, e: { r: 2, c: 26 } },
        { s: { r: 2, c: 27 }, e: { r: 2, c: 28 } },
        { s: { r: 2, c: 29 }, e: { r: 2, c: 30 } },
        { s: { r: 2, c: 31 }, e: { r: 2, c: 32 } },
        { s: { r: 2, c: 33 }, e: { r: 2, c: 34 } },
        { s: { r: 2, c: 35 }, e: { r: 2, c: 36 } }, 
        { s: { r: 2, c: 37 }, e: { r: 2, c: 38 } },
        { s: { r: 2, c: 39 }, e: { r: 2, c: 40 } },
        { s: { r: 2, c: 41 }, e: { r: 2, c: 42 } },
        { s: { r: 2, c: 43 }, e: { r: 2, c: 44 } },
        { s: { r: 2, c: 45 }, e: { r: 2, c: 46 } },
        { s: { r: 2, c: 47 }, e: { r: 2, c: 48 } },
        { s: { r: 2, c: 49 }, e: { r: 2, c: 50 } },
        { s: { r: 2, c: 51 }, e: { r: 2, c: 52 } },
        { s: { r: 2, c: 53 }, e: { r: 2, c: 55 } },
        { s: { r: 2, c: 56 }, e: { r: 2, c: 58 } },
        
    ];
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet 1');
    XLSXStyle.writeFile(workbook, 'RL 5.1 Kompilasi Morbiditas Pasien Rawat Jalan.xlsx');
};


</script>

<style lang="scss"></style>
