<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-4">
                    <div class="column is-10 p-0" style="color: var(--dark-text) !important;">
                        <label class="title-page">Laporan Stok Opname</label>
                        <label>DAFTAR STOK OPNAME</label>
                    </div>
                </div>

                <!-- <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" /> -->
                <DataTable :value="dataSource" showGridlines editMode="cell" class="p-datatable-sm mt-4 "
                  @cell-edit-complete="getSelisih" tableClass="editable-cells-table"
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords} " :paginator="true" :rows="5"
                  :rowsPerPageOptions="[5, 10, 25]">

                  <template #empty>
                    <div class="column p-2" style="text-align: center;">
                      <span style="font-weight:bold">Belum Tersedia Data Untuk Ditampilkan</span>
                    </div>
                  </template>
                  <template #header>
                        <div class="columns is-multiline mb-2">
                            <div class="column is-2" style="margin-top:23px; margin-right: -96px;">
                                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel" class="hard-button">
                                    Export To Excel
                                </VButton>
                            </div>
                            <div class="column is-2" style="margin-top:23px">
                              <FileUpload mode="basic" name="demo[]" url="./upload.php" color="info"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                :maxFileSize="50000000" chooseLabel="Import File" @select="onSelectedFiles" />
                            </div>
                            <div class="column is-8" style="margin-left: 6rem;">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-3">
                                        <VField label="Ruangan">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :disabled="isDisabled"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField label="Produk">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.produkfk" :suggestions="d_Produk" @complete="fetchProduk($event)"
                                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                     <div class="column is-1 btn-search mt-5 pt-4" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                  <ColumnGroup type="header">
                    <Row>
                      <Column header="No" :rowspan="6" />
                    </Row>
                    <Row>
                      <Column header="Kode Produk" :rowspan="6" />
                    </Row>
                    <Row>
                      <Column header="Product" :rowspan="6" />
                    </Row>
                    <Row>
                      <Column header="Tgl Kadaluarsa" :rowspan="6" />
                    </Row>
                    <Row>
                      <Column header="Jumlah" :colspan="3" />
                      <Column header="Satuan" :rowspan="6" />
                    </Row>
                    <Row>
                      <Column header="Sistem" sortable field="qtyproduk" />
                      <Column header="Real" sortable field="qtyproduk" />
                      <Column header="Selisih" sortable field="selisih" />
                    </Row>
                  </ColumnGroup>
                  <Column field="no" />
                  <Column field="kodeproduk" />
                  <Column field="namaproduk" />
                  <Column field="tglkadaluarsa" />
                  <Column field="qtyproduk" style="text-align: end;"/>
                  <Column style="text-align: end;">
                    <template #body="slotProps">
                      {{ slotProps.data.qtyReal }}
                    </template>
                    <template #editor="{ data, field }">
                      <InputText v-model="data[field]" autofocus />
                    </template>
                  </Column>
                  <Column field="selisih" style="text-align: end;"/>
                  <Column field="satuanstandar"></Column>
                    <template #footer>
                        <div class="column">
                          <div class="columns is-multiline" style="justify-content: end;">
                            <div class="column is-4"  v-if="valueProgress > 0">
                              <ProgressBar :value="valueProgress" style="height: 15px" />
                            </div>
                              <VButtons style="justify-content: flex-end">
                                <VButton icon="fas fa-save"  color="info" outlined raised @click="savePassword()" :loading="isLoadSave">Simpan</VButton>
                            </VButtons>
                          </div>
                        </div>
                    </template>     
                </DataTable>
            </VCard>
        </div>
  <Dialog v-model:visible="modalInput" modal header="Masukan Password" :style="{ width: '30vw' }">
    <div class="columns is-multiline">
      <div class="column is-12">
        <VField>
          <VLabel class="required-field">Password</VLabel>
          <VField class="is-autocomplete-select pt-2">
            <VControl icon="feather:bookmark">
              <input v-model="item.password" v-on:keyup.enter="saveData"  type="password" class="input is-rounded" placeholder="Masukan Password" />
            </VControl>
          </VField>
        </VField>
      </div>
    </div>
    <template #footer>
      <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="modalInput == false">
        Batal
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="btnLoad"
        @click="saveData"> Simpan
      </VButton>
    </template>
  </Dialog>
    </section>
</template>
<script  setup lang="ts">
import { useHead } from '@vueuse/head'
import { reactive, ref, computed, watch } from 'vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import { useRoute } from 'vue-router'
import InputText from 'primevue/inputtext';
import LaporanStokOpname from '../laporan/laporan-stok-opname.vue'
import Dropdown from 'primevue/dropdown'
import FileUpload from 'primevue/fileupload';
import AutoComplete from 'primevue/autocomplete';
import ProgressBar from 'primevue/progressbar';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import Calendar from 'primevue/calendar';
import Toolbar from 'primevue/toolbar'
import * as H from '/@src/utils/appHelper'
import moment from 'moment'
import Dialog from 'primevue/dialog';

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Stok Opname - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
   periode: reactive({
    start: new Date(),
    end: new Date(),
    awal: new Date(),
    akhir: new Date()
  })
})

const dataSource: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
let d_KelompokPasien: any = ref([])
let d_Ruangan: any = ref([])
let d_Produk: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let modalInput: any = ref(false)
let btnLoad: any = ref(false)
let isLoading: any = ref(false)
let isDisabled: any = ref(false)
const dataSO: any = ref([])
const isLoadSave: any = ref(false)
const isLoadingProgess: any = ref(false)
const fileSize: any = ref()
const passwordStockOpname: any = ref('')
let valueProgress: any = ref()

async function fetchData() {

  dataSource.value.loading = true
  loadSearch.value = true
  let ruangan = item.value.ruanganfk ? `&ruanganfk=${item.value.ruanganfk.value}` : ''
  let produk = item.value.produkfk ? `&produkfk=${item.value.produkfk.value}` : ''

  await useApi().get(`/logistik/stok-opname-ed?${ruangan}${produk}`).then((respond) => {
    respond.data.forEach((element: any, i: any) => {
      element.no = i + 1
      element.qtyReal = element.qtyproduk
      element.selisih = element.selisih
    });
    dataSource.value = respond.data
    dataSource.value.loading = false
    loadSearch.value = false
  }).catch((e) => {
    dataSource.value.loading = false
    loadSearch.value = false
  })
  valueProgress.value = 0;
}

const exportExcel = () => {
  if(!item.value.ruanganfk){
    H.alert('error','Ruangan Harus Dipilih Terlebih dulu')
    return 
  }
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['STOK OPNAME'],
        [],
        ['NO','NAMA PRODUK', 'KODE PRODUK', 'TGL KADALUARSA', 'QTY PRODUK', 'QTY REAL', 'SATUAN STANDAR'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.namaproduk,
            e.kodeproduk,
            e.tglkadaluarsa,
           parseFloat(e.qtyproduk),
           parseFloat(e.qtyReal),
            e.satuanstandar,
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

    const columnWidths = [10,30, 20, 20, 15 , 15, 15];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 6 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Stok Opname', true);
    XLSXStyle.writeFile(workbook, `Input Stok Opname Ruangan ${item.value.ruanganfk.label}.xlsx`);
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
        type: EXCEL_TYPE
    });
    const _url = window.URL.createObjectURL(data)
    window.open(_url, EXCEL_EXTENSION).focus();
}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=20`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(`/logistik/stok-opname-get-produk?namaproduk=${filter.query}&limit=20`)
  d_Produk.value = response.map((e: any) => {
    return { label: e.namaproduk, value: e.id }
  })
}

const getSelisih = (event: any) => {
  let { data, field, newValue } = event
  if (!item.value.ruanganfk) {
    H.alert('error', 'Ruangan Mesti dipilih Dahulu')
    return
  }
  if (newValue) {
    data.qtyReal = newValue;
    data.selisih = data.qtyReal - data.qtyproduk
    isDisabled.value = true
  } else {
    data.qtyReal = data.qtyproduk
  }
}

const saveData = async () => {
  if (!item.value.password) {
    H.alert("error", "Masukan Passowrd !");
    btnLoad.value = false
    return;
  }
   btnLoad.value = true
  if (!passwordStockOpname.value) {
    await listDataCombo();
  }
  if (item.value.password != passwordStockOpname.value) {
    H.alert("error", "Password Tidak Sesuai !");
    btnLoad.value = false
    return;
  }

  let dataResult: any = []
  dataSource.value.forEach((element: any) => {
    dataResult.push(element)
    // if (element.selisih) {
    //   dataResult.push(element)
    // }
  });

  // if (dataResult.length <= 0) {
  //   H.alert('error', 'Tidak ada pembaruan data')
  //   btnLoad.value = false
  //   return
  // }
 modalInput.value = false
 isLoadSave.value = true
 valueProgress.value = 0;
 let n = 0
 for (let index = 0; index < dataSource.value.length; index++) {
  const element = dataSource.value[index];
  n = (index + 1) * 100 / dataSource.value.length
  let objSave = {
        'ruanganId': item.value.ruanganfk.value,
        'namaRuangan': item.value.ruanganfk.label,
        'tglClosing': moment().format('YYYY-MM-DD HH:mm'),
        "stokProduk": [element],
        "lengthProgress" : n
    }
  await useApi().post('/logistik/save-stok-opname-ed', objSave)
  valueProgress.value = n.toFixed(2);
  modalInput.value = false
  }
  isDisabled.value = false
  delete item.value.password;
  isLoadSave.value = false
  btnLoad.value = false

}

const listDataCombo = async () => {
  await useApi()
    .get('logistik/penerimaan-barang/get-data-combo')
    .then((response) => {
      passwordStockOpname.value = response.password
    })
}

const onSelectedFiles = async (filez: any) => {
  const file = filez.files[0]

  if (file.type != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
    H.alert('error', 'File yang diizinkan dalam bentuk format Excel.')
    return;
  }
  if (file) {
    fileSize.value = parseInt(formatSize(file.size));
    let totalSizePercent = fileSize.value / 10;

    const reader = new FileReader();
    reader.onload = (e: any) => {
      const bstr = e.target.result;
      const wb = XLSX.read(bstr, { type: 'binary' });
      const wsname = wb.SheetNames[0];
      const ws = wb.Sheets[wsname];
      const data = XLSX.utils.sheet_to_json(ws, { header: 1 });
      setToGRID(data)
    }

    reader.readAsBinaryString(file);
  }
}

const formatSize = (bytes: any) => {
  if (bytes === 0) return "0 B";
  const k = 1024
  const sizes = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
}

const setToGRID = (e: any) => {
  dataSource.value = []
  e.forEach((element: any, i: any) => {
    if (i > 2) {
      dataSource.value.push({
        no: element[0],
        kodeproduk: element[2],
        namaproduk: element[1],
        tglkadaluarsa: element[3],
        qtyproduk: element[4],
        qtyReal: element[5],
        selisih: parseFloat(element[5]) - parseFloat(element[4]),
        satuanstandar: element[6],
      })
    }
  });
}

const savePassword = () => {
  modalInput.value = true
}
fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
// @import '/@src/scss/module/sysadmin/master-data.scss';

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}

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

.hard-button{
    padding: 8px 22px !important;
    height: 38px !important;
    line-height: 1.1 !important;
    font-size: 0.95rem !important;
    font-family: var(--font) !important;
    transition: all 0.3s !important;
}
</style>
