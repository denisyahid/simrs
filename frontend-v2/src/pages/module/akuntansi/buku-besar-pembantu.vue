<template>
  <section>

    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title-x">
          <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-2">
              <VField label="Jenis " class="is-rounded-selectZ  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="fa:bookmark" class="prime-auto-select">
                  <Dropdown v-model="item.jenisBB" :options="d_Jenis" :optionLabel="'jenis'" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                </VControl>
              </VField>
            </div>
      
            <div class="column is-3">
              <VField label="Rekanan " class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="fa:home" class="prime-auto-select">
                  <AutoComplete v-model="item.nmRekanan" :suggestions="d_Rekanan" @complete="fetchRekanan($event)"
                    :optionLabel="'namarekanan'" :dropdown="true" :minLength="3" :appendTo="'body'" class="is-rounded"
                    :loadingIcon="'pi pi-spinner'" :field="'namarekanan'" placeholder="ketik  Rekanan" showClear />
                </VControl>
              </VField>
            </div>
            <!-- <div class="column is-3">
              <VField label="Kode Akun " class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="fa:bookmark" class="prime-auto-select">
                  <AutoComplete v-model="item.kdAkun" :suggestions="d_Akun" @complete="fetchAKun($event)"
                    :optionLabel="'namaaccount'" :dropdown="true" :minLength="3" :appendTo="'body'" class="is-rounded"
                    :loadingIcon="'pi pi-spinner'" :field="'namaaccount'" placeholder="ketik kode Akun" showClear />
                </VControl>
              </VField>
            </div> -->
            <div class="column is-3 is-pulled-right">
              <VField label="Periode">
                <VControl class="prime-auto">
                  <Calendar inputId="range" v-model="item.qFilterTgl" selectionMode="range" :manualInput="false"
                    class="w-100 mb-4 is-rounded" :showIcon="true" date-format="dd-mm-yy" />
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
                <DataTable tableStyle="min-width: 50rem" :class="`p-datatable-small`" :value="dataSource"
                  v-model:filters="filtersTrans" :globalFilterFields="['KodePerkiraan']"
                  :rowsPerPageOptions="[5, 10, 25, 100]" :rows="10" paginator rows="10">
                  <template #header>
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="info" outlined circle raised
                          v-tooltip-prime="'Export'" @click="exportExcel()">
                          Export Excel
                        </VButton>

                      </div>
                      <div class="column is-3 is-offset-3">
                        <VCardCustom :style="'padding:5px 25px'">
                          <div class="label-status success">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">SALDO AWAL</span>
                          </div>
                          <small class="text-bold-custom">{{
                            H.formatRp(item.saldo,
                              'Rp.')
                          }}</small>

                        </VCardCustom>
                      </div>
                      <div class="column is-3 ">

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
                  <Column v-for="col in columnTrans" :field="col.field" :header="col.title"
                    :style="'width:' + col.width + ';text-align:' + (col.template != undefined ? 'right' : '')">
                    <template #body="slotProps">
                      <span v-if="col.tag == undefined">{{ col.template != undefined ?  H.formatRupiah(slotProps.data[col.field], '') : slotProps.data[col.field] }}</span>
                      <span v-else>
                        <VTag class="mr-1 mb-1" :color="slotProps.data[col.field] != null ? 'success' : 'solid'"
                          :label="slotProps.data[col.field]" />
                      </span>
                    </template>
                  </Column>
                  <!-- <Column :exportable="false" header="#" style="width:40px">
                    <template #body="slotProps">
                      <VIconButton type="button" icon="pi pi-search" class="mr-3" color="info" circle outlined raised
                        v-tooltip-prime="'Detail Jurnal'" @click="DetailJurnal(slotProps.data)"
                        :loading="slotProps.data.isLoading">
                      </VIconButton>

                    </template>
                  </Column> -->

                  <ColumnGroup type="footer">
                    <Row>
                      <Column :footer="'Terdapat ' + dataSource.length + ' data.'"  />
                      <Column :footer="''" :colspan="3" />
                      <Column :footer="H.formatRp(item.totalDebet, 'Rp. ')" style="text-align:right" />
                      <Column :footer="H.formatRp(item.totalKredit, 'Rp. ')" style="text-align:right"/>
              
                    </Row>
                  </ColumnGroup>
                </DataTable>
                <div class="columns is-multiline mb-2">
                  <div class="column is-9">
                  
                  </div>
                  <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                      <div class="label-status info">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span class="ml-1">SALDO AKHIR</span>
                      </div>
                      <small class="text-bold-custom">{{
                        H.formatRp((isNaN(item.saldo  + item.totalDebet -item.totalKredit) ?0:item.saldo  + item.totalDebet -item.totalKredit) ,
                          'Rp.')
                      }}</small>

                    </VCardCustom>
                  </div>
                </div>
              </VCard>
            </div>

          </div>
        </div>
      </VCard>
    </div>

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
import Dropdown from 'primevue/dropdown';
import * as XLSX from "xlsx";
import { useUserSession } from '/@src/stores/userSession'
import Button from 'primevue/button';
const title = 'Buku Besar Pembantu'
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
const d_Rekanan: any = ref([])
const d_Jenis: any = ref([{ id: 1, jenis: 'Piutang' }, { id: 2, jenis: 'Hutang' }])
const onRowGroupExpand = (event) => {

};
const onRowGroupCollapse = (event) => {

};
let sDebet: any = reactive(0);
let sKredit: any = reactive(0);
let sSaldo: any = reactive(0);
const item: any = reactive({
  qFilterTgl: [
    new Date(),
    new Date()
  ],
  ttlDebet: 0,
  ttlKredit: 0,
  jenisBB: d_Jenis.value[0]
})
const currentPage: any = ref({
  limit: 20
})

const columnTrans: any = [
  {
    "field": "tglbuktitransaksi",
    "title": "Tanggal",
    "width": "70px",
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
    "field": "noaccount",
    "title": "No Ref",
    "width": "100px",
  },
  {
    "field": "hargasatuand",
    "title": "Debet",
    "width": "100px",
    "template": "<span class='style-right'>{{formatRupiah('#: hargasatuand #', '')}}</span>"
  },
  {
    "field": "hargasatuank",
    "title": "Kredit",
    "width": "100px",
    "template": "<span class='style-right'>{{formatRupiah('#: hargasatuank #', '')}}</span>"
  }
  // ,{
  //  "title": "<span class='style-center'>Saldo</span>",
  //  "columns" : [
  //  {
  //      "field": "sDebet",
  //      "title": "Debet",
  //      "width":"100px",
  //      "template": "<span class='style-right'>{{formatRupiah('#: sDebet #', '')}}</span>"
  //  },
  //  {
  //      "field": "sKredit",
  //      "title": "Kredit",
  //      "width":"100px",
  //      "template": "<span class='style-right'>{{formatRupiah('#: sKredit #', '')}}</span>"
  //  }
  //  ]

  // }
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
  if(!item.nmRekanan ){
    H.alert('error','Pilih Rekanan')
    return
  }
  var jp = ''
  if (item.nmRekanan != undefined) {
    jp = "rknid=" + item.nmRekanan.id;
    if (item.nmRekanan.id == undefined) {
      jp = "";
    };
  };
  var bb = '&jenis='
  if (item.jenisBB != undefined) {
    bb = '&jenis=' + item.jenisBB.jenis;
  }

  var akun = "&noaccount=";
  if (item.kdAkun != undefined) {
    akun = "&noaccount=" + item.kdAkun.id;
  }
  isLoading.value = true

  const response = await useApi().get(
    "/akuntansi/get-data-buku-besar-pembantu?" + jp + "&tglAwal=" + dari + "&tglAkhir=" + sampai + bb + akun
  )

  isLoading.value = false
  // countTotalD(response.data)

  dataSource.value = response.data.sort((a, b) => new Date(b.tglbuktitransaksi) - new Date(a.tglbuktitransaksi));
  sSaldo = 0;
  // if (parseFloat(response.saldoawal[0].hargasatuand) > 0) {
  //   sSaldo = parseFloat(response.saldoawal[0].hargasatuand)
  //   item.saldoAwal = sSaldo
  // } else {
  //   // if (data.saldoawal.hargasatuank >0) {
  //   sSaldo = parseFloat(response.saldoawal[0].hargasatuank) * (-1)
  //   item.saldoAwal = sSaldo
  // }
  for (let x = 0; x < response.saldoawal.length; x++) {
    const element = response.saldoawal[x];
    let sal = 0
    if(element.saldonormaladd == 'D'){
      sal =  parseFloat(element.hargasatuand) -  parseFloat(element.hargasatuank) 
      sSaldo = sSaldo+ sal
    }else{
      sal =  parseFloat(element.hargasatuank) -  parseFloat(element.hargasatuand) 
      sSaldo = sSaldo+ sal
    }
    item.saldoAwal = sSaldo
  }

  // var sDebet = 0;
  // var sKredit = 0;
  item.saldo = sSaldo
  sDebet = 0;
  sKredit = 0;

  for (var i = 0; i < response.data.length; i++) {
    if (response.data[i].saldonormaladd == "D") {
      sSaldo = (sSaldo + parseFloat(response.data[i].hargasatuand)) - parseFloat(response.data[i].hargasatuank);
    }
    if (response.data[i].saldonormaladd == "K") {
      sSaldo = (sSaldo + parseFloat(response.data[i].hargasatuank)) - parseFloat(response.data[i].hargasatuand);
    }
    sDebet = sDebet + parseFloat(response.data[i].hargasatuand);
    sKredit = sKredit + parseFloat(response.data[i].hargasatuank);
    // data.data[i].sDebet = sDebet
    // data.data[i].sKredit = sKredit
    response.data[i].saldo = sSaldo
  };
  item.totalDebet = sDebet
  item.totalKredit = sKredit
 
  let c_set = {
    0: dari,
    1: sampai,
  }
  H.cacheHelper().set('c_bukubesarb', c_set);

}

const changeRekanan = (e:any) =>{

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
    element.namaaccount = element.noaccount + ' ' + element.namaaccount
  }
  d_Akun.value = response
}

const fetchRekanan = async (filter: any) => {
  let query = ''
  if (filter) {
    query = filter.query
  }
  const response = await useApi().get(`/akuntansi/get-datacombo-rekanan?name= ${query}&limit=10`)
  d_Rekanan.value = response
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
let c = H.cacheHelper().get('c_bukubesarb');
if (c != undefined) {
  item.qFilterTgl[0] = new Date(c[0]);
  item.qFilterTgl[1] = new Date(c[1]);
}


</script>
<style lang="scss"></style>
