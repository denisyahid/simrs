<template>

          <DataTable :rows="5" :value="dataSource" :loading="isLoading" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4 pt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 100%"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2" style="margin-top:23px">
                                <VButton color="primary" @click="exportExcel()" :disabled="dataSource.length < 1" outlined icon="fas fa-file-excel">
                                    Export To Excel
                                </VButton>
                            </div>
                            <div class="column is-10">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <!-- <div class="column is-3">
                                        <VField label="Periode Pelayanan" style="margin-bottom: 6px;" />
                                        <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField addons>
                                                    <VControl icon="feather:calendar">
                                                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                                    </VControl>
                                                    <VControl>
                                                        <VButton static><i class="fas fa-arrow-right"
                                                                aria-hidden="true"></i></VButton>
                                                    </VControl>
                                                    <VControl icon="feather:calendar">
                                                        <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div> -->
                                    <div class="column is-2">
                                        <VField class=" is-rounded-select is-autocomplete-select">
                                            <VLabel>Kelompok Produk</VLabel>
                                            <VControl icon="feather:search" class="prime-auto">
                                                <Dropdown v-model="item.klmproduk" :options="sourceKelompokProduk"
                                                    :optionLabel="'label'" placeholder="Pilih Kelompok Produk" :optionValue="'value'"
                                                    style="width: 100%;" :filter="true" appendTo="body" showClear />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                      <VField>
                                        <VLabel>Nama Produk</VLabel>
                                        <VControl icon="feather:search">
                                          <input v-model="item.namaproduk" v-on:keyup.enter="fetchData()" type="text" class="input"
                                            placeholder="Nama produk..." />
                                        </VControl>
                                      </VField>
                                    </div>
                                     <div class="column is-1 btn-search mt-5" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData()"
                                            :loading="isLoading" />
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    </template>
                    <template #empty>
                      <div class="column is-12 p-2" style="text-align:center">
                        <span style="font-weight:bold">Data Tidak Tersedia</span>
                      </div>
                    </template>
                    <Column field="no" header="NO" style="min-width: 10px;" />
                    <Column field="namaproduk" header="Nama Produk" style="min-width: 500px;" />
                    <Column field="kelompokproduk" header="Kelompok Produk" style="min-width: 200px;"/>
          </DataTable>
</template>

  
<script  setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Button from 'primevue/button'
import OverlayPanel from 'primevue/overlaypanel';
import AutoComplete from 'primevue/autocomplete'
import { useCurrencyInput } from 'vue-currency-input'
import { useHead } from '@vueuse/head'
import Dialog from 'primevue/dialog';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
useHead({
  title: 'Transmedic - Fast Moving',
})
useViewWrapper().setFullWidth(true)
let dataSource: any = ref([])
let isLoading: any = ref(false)
let loadData: any = ref(false)
let loadSave: any = ref(false)
let modalInputED: any = ref(false)
let modalInputAj: any = ref(false)
let modalPassword: any = ref(false)
const item: any = ref({
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})
let sourceKelompokProduk: any = ref([])
let sourceJenisProduk: any = ref([])
let listJenisProduk: any = ref([])
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})

const passwordStockAdjustman: any = ref('')
const op = ref();
const activeTab = ref(0);
const selected: any = ref({})
const route = useRoute()
isLoading.value = false

const fetchData = async ()=>{

  let tglAwal =  H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
  let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
  let produk = item.value.produk ? `?namaproduk=${item.value.produk}` : ''
  let kelompokProduk = item.value.klmproduk ? `&klmproduk=${item.value.klmproduk}` : ''
  let namaproduk = item.value.namaproduk ? `&namaproduk=${item.value.namaproduk}` : ''

  isLoading.value = true
  await useApi().get(`/logistik/data-dead-moving?tglawal=${tglAwal}&tglakhir=${tglAkhir}${produk}${kelompokProduk}${namaproduk}`).then((response)=>{
    response.forEach((element:any,i:any) => {
      element.no = i + 1
    });
    isLoading.value = false
    dataSource.value = response
  })
  console.log(dataSource.value)
}

const listDropdown = async ()=>{
  await useApi().get(`/logistik/monitoring/combo`).then((response:any)=>{
    sourceKelompokProduk.value = response.kelompokproduk.map((e:any)=>{
      return {label:e.kelompokproduk, value:e.id}
    })
    sourceJenisProduk.value = response.jenisproduk.map((e:any)=>{
      return {label:e.jenisproduk, value:e.id}
    })
  })

}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Barang Produk Dead Moving'],
        [],
        ['NO', 'NAMA PRODUK', 'KELOMPOK PRODUK'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.namaproduk,
            parseFloat(e.jumlah),
            e.kelompokproduk
        ]),
    ]);
    // Mendefinisikan style untuk header(centered)
    const headerStyle = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            color: { rgb: 'FFFFFF' }
        },
        fill: { fgColor: { rgb: '807C7C' } }
    };

    // Mendefinisikan range header
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
        worksheet[headerCell].s = headerStyle;
    }

    // atur lebar column
    const columnWidths = [5, 80, 30 ];


    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        worksheet['!cols'] = worksheet['!cols'] || [];
        worksheet['!cols'][col] = { wch: columnWidths[col] };
    }

    // Centering the text in cell A1
    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            bold: true,
            sz: 18
        }
    };


    // Menggabungkan dua baris pertama
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 2 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Fast Moving', true);
    XLSXStyle.writeFile(workbook, 'Barang Fast Moving.xlsx');
}

listDropdown()
fetchData()


</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 0px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
}

</style>
