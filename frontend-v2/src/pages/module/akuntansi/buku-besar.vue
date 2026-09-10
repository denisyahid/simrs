<template>
  <section>

    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title-x">
          <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField label="Kode Akun " class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="fa:bookmark" class="prime-auto-select">
                  <AutoComplete v-model="item.kdAkun" :suggestions="d_Akun" @complete="fetchAKun($event)"
                    :optionLabel="'namaaccount'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'namaaccount'" placeholder="ketik kode Akun" showClear />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Kode Akun " class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="fa:bookmark" class="prime-auto-select">
                  <AutoComplete v-model="item.kdAkun2" :suggestions="d_Akun" @complete="fetchAKun($event)"
                    :optionLabel="'namaaccount'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'namaaccount'" placeholder="ketik kode Akun" showClear />
                </VControl>
              </VField>
            </div>
            <div class="column is-3 is-pulled-right">
              <VField label="Periode">
                <VControl class="prime-auto">
                  <Calendar inputId="range" v-model="item.qFilterTgl" selectionMode="range" :manualInput="false"
                    class="w-100 mb-4 " :showIcon="true" date-format="dd-mm-yy" />
                </VControl>
              </VField>

            </div>
            <div class="column is-1 mt-5 ">
              <VIconButton type="button" color="success" circle raised icon="fas fa-search" @click="fetchData()"
                :loading="isLoading">
              </VIconButton>
              <!-- <VIconButton circle class="ml-2  is-pulled-" icon="fas fa-filter" raised bold
                              @click="modalFilter = true" v-tooltip.bubble="'Filter'">
                          </VIconButton>
                          <Badge :value="jmlFilter" v-if="jmlFilter > 0" severity="info" class="is-pulled-"
                              style="margin-left:-10px ;z-index: 100;  position: relative; "></Badge> -->
            </div>

            <div class="column is-12 mt-5-min">
              <VCard class="card-round-1">
                <!-- 
                <DataTable v-model:filters="filtersTrans" :value="dataSource" paginator :rows="10" 
                  filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]" :globalFilterFields="['kelompok']"
                  :class="`p-datatable-small`"
                  
      
                  > -->
                <DataTable v-model:expandedRowGroups="expandedRowGroups" tableStyle="min-width: 50rem"
                  :class="`p-datatable-small`" :value="dataSource" v-model:filters="filtersTrans"
                  :globalFilterFields="['KodePerkiraan']" rowGroupMode="subheader" :rowsPerPageOptions="[5, 10, 25, 100]"
                  :rows="50" paginator  groupRowsBy="KodePerkiraan" @rowgroup-expand="onRowGroupExpand"
                  @rowgroup-collapse="onRowGroupCollapse" sortMode="single" sortField="KodePerkiraan" :sortOrder="1">
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
                  <template #empty style="text-align: center;"> No data found. </template>
                  <template #groupheader="slotProps">
                    <span class="vertical-align-middle ml-2 font-bold line-height-3">{{ slotProps.data.KodePerkiraan
                    }}</span>
                  </template>

                  <Column v-for="col in columnTrans" :field="col.field" :header="col.title" :style="'width:' + col.width">
                    <template #body="slotProps">
                      <span v-if="col.tag == undefined">{{ col.template != undefined ?
                        H.formatRp(slotProps.data[col.field], '')
                        : slotProps.data[col.field] }}</span>
                      <span v-else>

                        <VTag class="mr-1 mb-1" :color="slotProps.data[col.field] != null ? 'success' : 'solid'"
                          :label="slotProps.data[col.field]" />
                      </span>
                    </template>
                  </Column>
                  <Column :exportable="false" header="#" style="width:40px">
                    <template #body="slotProps">
                      <VIconButton type="button" icon="pi pi-search" class="mr-3" color="info" circle outlined raised
                        v-tooltip-prime="'Detail Jurnal'" @click="DetailJurnal(slotProps.data)" v-if="slotProps.data.keteranganlainnya !='Saldo Awal' && slotProps.data.keteranganlainnya !='Total'"
                        :loading="slotProps.data.isLoading">
                      </VIconButton>

                    </template>
                  </Column>

                  <!-- <ColumnGroup type="footer">
                    <Row>
                      <Column :footer="'Terdapat ' + dataSource.length + ' data.'" :colspan="columnTrans.length - 3" />
                      <Column :footer="'TOTAL'" />
                      <Column :footer="H.formatRp(item.ttlDebetGRID, 'Rp. ')" />
                      <Column :footer="H.formatRp(item.ttlKreditGRID, 'Rp. ')" />
                      <Column :footer="''" :colspan="2" />
                    </Row>
                  </ColumnGroup> -->
                </DataTable>

              </VCard>
            </div>

          </div>
        </div>
      </VCard>
    </div>

    <Dialog v-model:visible="modalJurnal" modal :header="'Jurnal Detail'" :style="{ width: '70vw' }">
      <div class="columns is-multiline">

        <div class="column is-4">
          <VField>
            <VLabelText>No Junal</VLabelText>
            <VLabel>
              <i aria-hidden="true" class="bulet fas fa-circle"></i>
              {{ item.nojurnal }}
            </VLabel>

          </VField>
        </div>

        <div class="column is-4">
          <VField>
            <VLabelText>Tanggal</VLabelText>
            <VLabel>
              <i aria-hidden="true" class="bulet fas fa-circle"></i>
              {{ item.tanggal }}
            </VLabel>
          </VField>
        </div>

        <div class="column is-4">
          <VField>
            <VLabelText>Deskripsi</VLabelText>
            <VLabel>
              <i aria-hidden="true" class="bulet fas fa-circle"></i>
              {{ item.deskripsi }}
            </VLabel>
          </VField>
        </div>
        <div class="column is-12">
          <VCard class="card-round-4">
            <DataTable v-model:filters="filtersDetail" :value="dataPopUp" paginator :rows="5" dataKey="id"
              filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]"
              :globalFilterFields="['namaaccount', 'keteranganlainnya']" :class="`p-datatable-small`" :size="'small'">
              <template #header>
                <div class="columns is-multiline">
                  <div class="column is-3 is-offset-9">
                    <VField>
                      <VControl icon="feather:search">
                        <input v-model="filtersDetail['global'].value" v-on:keyup.enter="fetchData()" type="text"
                          class="input is-rounded" placeholder="Search" />
                      </VControl>
                    </VField>
                  </div>
                </div>

              </template>
              <template #empty style="text-align: center;"> No data found. </template>
              <Column v-for="col in columnPopUp" :field="col.field" :header="col.title" :style="'width:' + col.width">
                <template #body="slotProps">
                  <span v-if="col.tag == undefined">{{ col.template != undefined ?
                    H.formatRp(slotProps.data[col.field], '')
                    : slotProps.data[col.field] }}</span>
                  <span v-else>
                    <VTag class="mr-1 mb-1" :color="'success'" :label="slotProps.data[col.field]" />
                  </span>
                </template>
              </Column>
              <ColumnGroup type="footer">
                <Row>
                  <Column :footer="'Terdapat ' + dataPopUp.length + ' data.'" :colspan="columnPopUp.length + 1" />
                </Row>
              </ColumnGroup>˝
            </DataTable>
            <div class="columns is-multiline mb-2">
              <div class="column is-6">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status info">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">TOTAL DEBIT</span>
                  </div>
                  <small class="text-bold-custom">{{
                    H.formatRp(item.ttlDebet,
                      'Rp.')
                  }}</small>

                </VCardCustom>
              </div>
              <div class="column is-6">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status danger">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">TOTAL KREDIT</span>
                  </div>
                  <small class="text-bold-custom">{{
                    H.formatRp(item.ttlKredit,
                      'Rp.')
                  }}</small>

                </VCardCustom>
              </div>
            </div>
          </VCard>
        </div>

      </div>


    </Dialog>

  </section>
</template>
<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'

import { useRoute, useRouter } from 'vue-router'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ProgressBar from 'primevue/progressbar';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api'
import { useApi } from '/@src/composable/useApi'
import Panel from 'primevue/panel';
import Dialog from 'primevue/dialog';
import Badge from 'primevue/badge';
import FileUpload from 'primevue/fileupload';
import Calendar from 'primevue/calendar';
import moment from 'moment';
import sleep from '/@src/utils/sleep'
import AutoComplete from 'primevue/autocomplete';

import * as XLSX from "xlsx";
import { useUserSession } from '/@src/stores/userSession'
import Button from 'primevue/button';
const title = 'Buku Besar'
useHead({
  title: title + ' - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const modalFilter: any = ref(false)
const isLoading: any = ref(false)
const route = useRoute()
const dataSource: any = ref([])
const modalJurnal: any = ref(false)
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const filtersDetail = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const dataDetailJurnal: any = ref([])
const dataPopUp: any = ref([])
const data2: any = ref([])
const isLoadingpop: any = ref(false)
const d_Akun: any = ref([])
const expandedRowGroups = ref();
const onRowGroupExpand = (event) => {

};
const onRowGroupCollapse = (event) => {

};
const item: any = reactive({
  qFilterTgl: [
    new Date(),
    new Date()
  ],
  ttlDebet: 0,
  ttlKredit: 0

})
const currentPage: any = ref({
  limit: 20
})

const columnTrans: any = [
  {
    "field": "tglbuktitransaksi",
    "title": "Tanggal",
    "width": "70px",

    // "template": "<span class='style-left'>{{formatTanggal('#: tglbuktitransaksi #')}}</span>"
  },
  {
    "field": "nojurnal",
    "title": "No Jurnal",
    "width": "80px",
  },
  {
    "field": "keteranganlainnya",
    "title": "Keterangan",
    "width": "300px",
  },
  {
    "field": "noref",
    "title": "No Ref",
    "width": "100px",
  },
  {
    "field": "hargasatuand",
    "title": "Debet",
    "width": "100px",
    "template": "<span class='style-right'>{{formatRp('#: hargasatuand #', '')}}</span>"
  },
  {
    "field": "hargasatuank",
    "title": "Kredit",
    "width": "100px",
    "template": "<span class='style-right'>{{formatRp('#: hargasatuank #', '')}}</span>"
  },
  {
    "field": "saldo",
    "title": "Saldo",
    "width": "100px",
    "template": "<span class='style-right'>{{formatRp('#: saldo #', '')}}</span>"
  }
];

const columnPopUp: any = [
  {
    "field": "no",
    "title": "No",
    "width": "20px"
  },
  {
    "field": "noaccount",
    "title": "Kode",
    "width": "60px"
  },
  {
    "field": "namaaccount",
    "title": "Perkiraan",
    "width": "130px"
  },
  {
    "field": "keteranganlainnya",
    "title": "Keterangan",
    "width": "100px"
  },
  {
    "field": "hargasatuand",
    "title": "Debit",
    "width": "70px",
    // "aggregates": ["sum"],
    // "footerTemplate": "#=sum#",
    // "groupFooterTemplate": "#=sum#",
    template: "<span class='style-right'>{{formatRp('#: hargasatuand #', '')}}</span>"
  },
  {
    "field": "hargasatuank",
    "title": "Kredit",
    "width": "70px",
    // "aggregates": ["sum"],
    // "footerTemplate": "#=sum#",
    // "groupFooterTemplate": "#=sum#",
    template: "<span class='style-right'>{{formatRp('#: hargasatuank #', '')}}</span>"
  }
]


const loadDetail = async () => {

}
const fetchData = async () => {
  let limit: any = currentPage.value.limit
  let page: any = route.query.page ? route.query.page : 1

  let dari = '', sampai = '', search = ''

  if (item.filter) {
    search = item.filter
  }
  if (item.qFilterTgl[0]) {
    dari = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 00:00:00')
  }
  if (item.qFilterTgl[1]) {
    sampai = H.formatDate(item.qFilterTgl[1], 'YYYY-MM-DD 23:59:59')
  } else {
    sampai = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 23:59:59')
  }
  item.ttlDebet = 0
  item.ttlKredit = 0
  var jp = "&noaccount=-";
  if (item.kdAkun != undefined && item.kdAkun != null) {
    var jp = "&noaccount=" + item.kdAkun.noaccount;
  }

  var jp2 = "&noaccount2=-";
  if (item.kdAkun2 != undefined && item.kdAkun2 != null) {
    var jp2 = "&noaccount2=" + item.kdAkun2.noaccount;
  }
  isLoading.value = true

  const response = await useApi().get(
    '/akuntansi/get-data-buku-besar-rev2?tglAwal=' + dari
    + '&tglAkhir=' + sampai
    + jp + jp2
  )

  isLoading.value = false
  // countTotalD(response.data)

  dataSource.value = response.data.sort((a, b) => new Date(b.tglbuktitransaksi) - new Date(a.tglbuktitransaksi));

  let c_set = {
    0: dari,
    1: sampai,
  }
  H.cacheHelper().set('c_bukubesar', c_set);

}
const countTotalD = (response: any) => {
  let debetX: any = 0
  let kreditX: any = 0
  for (var i = response.length - 1; i >= 0; i--) {
    debetX = parseFloat(debetX) + parseFloat(response[i].debet)
    kreditX = parseFloat(kreditX) + parseFloat(response[i].kredit)
  }
  item.ttlDebetGRID = debetX
  item.ttlKreditGRID = kreditX

}
const countTotal = (response: any) => {
  let debetX: any = 0
  let kreditX: any = 0
  for (var i = response.length - 1; i >= 0; i--) {
    debetX = parseFloat(debetX) + parseFloat(response[i].hargasatuand)
    kreditX = parseFloat(kreditX) + parseFloat(response[i].hargasatuank)
  }
  item.ttlDebet = debetX
  item.ttlKredit = kreditX

}
const terapkanFilter = () => {
  fetchData()
  modalFilter.value = false
}
const DetailJurnal = async (dataSelected: any) => {
  dataSelected.isLoading = true
  item.srcPerkiraan = undefined;
  item.nojurnal = dataSelected.nojurnal
  item.tanggal = dataSelected.tgl
  item.deskripsi = dataSelected.keteranganlainnya
  dataPopUp.value = []
  const dat = await useApi().get(

    "/akuntansi/get-data-detail-buku-besar?nojurnal=" + dataSelected.nojurnal + "&accountid=" + dataSelected.coaid
  )
  dataSelected.isLoading = false

  dataDetailJurnal.value = dat
  countTotal(dat)
  for (var i = dat.length - 1; i >= 0; i--) {
    dat[i].no = i + 1

  }
  dataPopUp.value = dat
  modalJurnal.value = true

}
const fetchAKun = async (filter: any) => {
  let query = ''
  if (filter) {
    query = filter.query
  }
  const response = await useApi().get(`/akuntansi/get-data-combo-coa-part?name= ${query}&limit=10`)
  for (let x = 0; x < response.length; x++) {
    const element = response[x];
    element.namaaccount = element.noaccount  + ' - ' + element.namaaccount 
  }
  d_Akun.value = response
}
const exportExcel = () => {
  let judul = 'BUKU BESAR'
  let column = ['Nama Account']
  for (let x = 0; x < columnTrans.length; x++) {
    const element = columnTrans[x];
    column.push(element.title)
  }
  const worksheet = XLSX.utils.aoa_to_sheet([

    [judul],
    [],
    column,
    ...dataSource.value.map((e: any) => [
      e.KodePerkiraan,
      e.tglbuktitransaksi,
      e.nojurnal,
      e.keteranganlainnya,
      e.noref,
      parseFloat(e.hargasatuand).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
      parseFloat(e.hargasatuank).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
      parseFloat(e.saldo).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"), '',
    ]),
    [],
  
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
  ];
  worksheet['!cols'] = columnWidths;
  const cellRef = XLSX.utils.encode_cell({ r: 0, c: 0 });
  worksheet[cellRef] = { v: judul, s: { alignment: { horizontal: 'center', vertical: 'center' } } };

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 8 } };
  worksheet['!merges'] = [mergeTitle, mergeSubtitle1];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  H.saveAsExcelFile(excelBuffer, 'bukubesar');
}
const downloadTemplate = () => {
  window.open(import.meta.env.VITE_API_BASE_URL + 'akuntansi/template-excel?token=' + useUserSession().token, '_blank');
}
let c = H.cacheHelper().get('c_bukubesar');
if (c != undefined) {
  item.qFilterTgl[0] = new Date(c[0]);
  item.qFilterTgl[1] = new Date(c[1]);
}


</script>
<style lang="scss"></style>
