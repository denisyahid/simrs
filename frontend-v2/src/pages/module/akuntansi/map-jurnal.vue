<template>
  <section>
    <ConfirmDialog group="positionDialog"></ConfirmDialog>
      <div class="columns is-multiline">
          <VCard style="padding-bottom: 0px">
              <div class="column c-title-x">
                  <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
              </div>
              <div class="column is-12">
                  <div class="columns is-multiline">

                      <!-- <div class="column is- is-pulled-right"> -->
                          <!-- <VField label="Periode">
                              <VControl class="prime-auto">
                                  <Calendar inputId="range" v-model="item.bulan" :manualInput="false" class="w-100 mb-4 "
                                      :showIcon="true" view="month" dateFormat="MM-yy" />
                              </VControl>
                          </VField> -->
                      <!-- </div> -->

                      <div class="column is-9">
                        <VField label="Nama Produk">
                          <VControl icon="feather:search">
                            <input
                              v-model="item.namaproduk"
                              v-on:keyup.enter="fetchData()"
                              type="text"
                              class="input is-rounded"
                              placeholder="Nama Produk"
                            />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-1">
                        <VField label="Rows">
                          <VControl icon="feather:airplay">
                            <input
                              v-model="item.rows"
                              v-on:keyup.enter="fetchData()"
                              type="text"
                              class="input is-rounded"
                              placeholder="Rows"
                            />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-1 mt-5 ">
                          <VIconButton type="button" color="success" circle raised icon="fas fa-search"
                              @click="fetchData()" :loading="isLoading">
                          </VIconButton>
                      </div>
                      <div class="column is-12">
                        <DataTable :value="dataSource" class="p-datatable-sm" :loading="isPlaceLoad" :paginator="true"
                        :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
                        v-model:filters="filters" :globalFilterFields="['namapasien', 'nocm' ,'kelompokpasien','noantrian' ,'namalengkap' ,'kelompokpasien']"
                        filterDisplay="menu">
                        <template #empty> {{ H.assets().notFound }}</template>
                        <template #header>

                        <div class="">
                            <VIconButton type="button" icon="feather:plus" class="mr-3" color="success"
                                circle raised v-tooltip-prime="'Tambah'" @click="addMap()">
                            </VIconButton>
                            <!-- <span class="p-input-icon-left is-pulled-right">
                                <InputText v-model="filtersMap['global'].value" placeholder="Search"
                                    style="width:500px" />
                            </span> -->
                        </div>
                        </template>
                        <Column :exportable="false" header="Mapping" style="width:30px">
                            <template #body="slotProps">
                              <div style="display: flex; justify-content: flex-end; align-items: center;">
                                  <!-- <VIconButton type="button" icon="feather:edit" class="mr-3" color="warning"
                                      circle outlined raised v-tooltip-prime="'Edit'"
                                      @click="editMap(slotProps.data)" :loading="slotProps.data.isLoading">
                                  </VIconButton> -->
                                  <VIconButton type="button" icon="feather:trash" class="mr-3" color="danger"
                                      circle outlined raised v-tooltip-prime="'Hapus'"
                                      @click="disMap(slotProps.data)" :loading="slotProps.data.loadingHapus">
                                  </VIconButton>
                              </div>
                            </template>
                        </Column>
                        <Column field="no" header="No" style="width:5%" frozen></Column>
                              <Column field="jenistransaksi" header="Jenis Jurnal" frozen></Column>
                              <Column field="namaproduk" header="Nama Produk"></Column>
                              <Column field="no_debit" header="KdDebet"></Column>
                              <Column field="coa_debit" header="Nm Debet"></Column>
                              <Column field="no_kredit" header="NmKredit"></Column>
                              <Column field="coa_kredit" header="NmKredit"></Column>
                              <Column field="namadepartemen" header="Instalasi"></Column>
                              <Column field="namaruangan" header="Ruangan"></Column>
                              <Column field="kelompokpasien" header="kelompok Pasien"></Column>
                              <Column field="detailjenisproduk" header="Detail Jenis Produk"></Column>
                              <Column field="carabayar" header="Cara Bayar"></Column>
                              <Column field="namarekanan" header="Nama Penjamin"></Column>
                              <Column field="nama" header="Nama Bank"></Column>
                        </DataTable>
                      </div>
                  </div>
              </div>
          </VCard>
      </div>
  </section>
  <Dialog v-model:visible="modalJurnal" modal
            :header="'Mapping Jurnal' + (item.tittlegridmap ? item.tittlegridmap : '')" :style="{ width: '70vw' }">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField label="Produk" class="is-rounded-select is-autocomplete-select"
                    v-slot="{ id }">
                    <VControl icon="fas fa-database" fullwidth class="prime-auto-select">
                        <AutoComplete v-model="item.objectprodukfk" :suggestions="d_Produk"
                            @complete="fetchProduk($event)" :optionLabel="'label'"
                            :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik untuk mencari..." />
                    </VControl>
                </VField>
                </div>
            </div>
            <div class="columns is-multiline">

                <div class="column is-6">
                    <VCard class="card-round-4">
                        <p class="title-c"> DEBIT</p>

                        <DataTable v-model:filters="filtersCoaDebet" :value="dataSourceCoaDebet" paginator :rows="5"
                            dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]"
                            :globalFilterFields="['namaaccount', 'kdaccount']" :class="`p-datatable-small`">
                            <template #header>
                                <div class="flex justify-content-end">
                                    <span class="p-input-icon-left ">
                                        <InputText v-model="filtersCoaDebet['global'].value" placeholder="Search" />
                                    </span>
                                </div>
                            </template>
                            <template #empty style="text-align: center;"> No data found. </template>
                            <Column :exportable="false" header="#" style="width:30px">
                                <template #body="slotProps">
                                    <VIconButton type="button" icon="feather:check" class="mr-3" color="warning" circle
                                        outlined raised v-tooltip-prime="'Pilih'" @click="klikDebet(slotProps.data)"
                                        :loading="slotProps.data.isLoading">
                                    </VIconButton>
                                </template>
                            </Column>
                            <Column v-for="col in columnCoaDebet"
                                :field="col.template ? H.formatRupiah(col.field, '') : col.field" :header="col.title"
                                :style="'width:' + col.width"></Column>
                            <ColumnGroup type="footer">
                                <Row>
                                    <Column :footer="'Terdapat ' + dataSourceCoaDebet.length + ' data.'"
                                        :colspan="columnCoaDebet.length + 1" />
                                </Row>
                            </ColumnGroup>
                        </DataTable>
                        <div class="columns is-multiline mb-2">
                            <div class="column is-2 mt-2">
                                <VField>
                                    <VLabel>Junal Debit</VLabel>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField>
                                    <VControl icon="feather:arrow-left">
                                        <input v-model="item.coaNoDebet" type="text" class="input is-rounded"
                                            placeholder="Kode" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField>
                                    <VControl icon="feather:arrow-right">
                                        <input v-model="item.coaNmDebet" type="text" class="input is-rounded"
                                            placeholder="Nama Akun" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </VCard>
                </div>
                <div class="column is-6">
                    <VCard class="card-round-5">
                        <p class="title-c"> KREDIT</p>
                        <DataTable v-model:filters="filtersCoaKredit" :value="dataSourceCoaKredit" paginator :rows="5"
                            dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]"
                            :globalFilterFields="['namaaccount', 'kdaccount']" :class="`p-datatable-small`" :size="'small'">
                            <template #header>
                                <div class="flex justify-content-end">
                                    <span class="p-input-icon-left ">
                                        <InputText v-model="filtersCoaKredit['global'].value" placeholder="Search" />
                                    </span>
                                </div>
                            </template>
                            <template #empty style="text-align: center;"> No data found. </template>
                            <Column :exportable="false" header="#" style="width:30px">
                                <template #body="slotProps">
                                    <VIconButton type="button" icon="feather:check" class="mr-3" color="warning" circle
                                        outlined raised v-tooltip-prime="'Pilih'" @click="klikKredit(slotProps.data)"
                                        :loading="slotProps.data.isLoading">
                                    </VIconButton>
                                </template>
                            </Column>
                            <Column v-for="col in columnCoaKredit"
                                :field="col.template ? H.formatRupiah(col.field, '') : col.field" :header="col.title"
                                :style="'width:' + col.width"></Column>
                            <ColumnGroup type="footer">
                                <Row>
                                    <Column :footer="'Terdapat ' + dataSourceCoaKredit.length + ' data.'"
                                        :colspan="columnCoaKredit.length + 1" />
                                </Row>
                            </ColumnGroup>
                        </DataTable>
                        <div class="columns is-multiline mb-2">
                            <div class="column is-2 mt-2">
                                <VField>
                                    <VLabel>Junal Kredit</VLabel>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField>
                                    <VControl icon="feather:arrow-left">
                                        <input v-model="item.coaNoKredit" type="text" class="input is-rounded"
                                            placeholder="Kode" v-on:keyup.enter="SearchEnterKredit()" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField>
                                    <VControl icon="feather:arrow-right">
                                        <input v-model="item.coaNmKredit" type="text" class="input is-rounded"
                                            placeholder="Nama Akun" v-on:keyup.enter="SearchEnterKredit()" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </VCard>
                </div>
                <div class="column is-4">
                    <VField label="Jenis Jurnal" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-book" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.jenisjurnal" :options="d_JenisJurnal" :optionLabel="'jenistransaksi'"
                                class="is-rounded" placeholder="Jenis Jurnal" style="width: 100%;" :filter="true"
                                showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField label="Instalasi" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-archway" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.departemen" :options="d_Departemen" :optionLabel="'namadepartemen'"
                                class="is-rounded" placeholder="Instalasi" style="width: 100%;" :filter="true"
                                @change="changeInst($event)" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField label="Ruangan" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-home" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.ruangan" :options="d_Ruangan" :optionLabel="'namaruangan'"
                                class="is-rounded" placeholder="Ruangan" style="width: 100%;" :filter="true" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField label="Kelompok Pasien" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-pie-chart" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.kelompokPasien" :options="d_KelompokPasien"
                                :optionLabel="'kelompokpasien'" class="is-rounded" placeholder="Kelompok Pasien"
                                style="width: 100%;" :filter="true" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField label="Cara Bayar" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-calculator" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.caraBayar" :options="d_Carabayar" :optionLabel="'carabayar'"
                                class="is-rounded" placeholder="Cara Bayar" style="width: 100%;" :filter="true" showClear />
                        </VControl>
                    </VField>
                </div>

            </div>
            <template #footer>
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalJurnal = false">
                    Batal
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                    @click="saveSetting()"> Simpan
                </VButton>
            </template>
        </Dialog>
</template>
<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Sidebar from 'primevue/sidebar';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import { useRoute, useRouter } from 'vue-router'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ProgressBar from 'primevue/progressbar';
import ColumnGroup from 'primevue/columngroup';   // optional
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
import Divider from 'primevue/divider';
import ConfirmDialog from 'primevue/confirmdialog'
import AutoComplete from 'primevue/autocomplete';
import { useConfirm } from 'primevue/useconfirm'
import * as XLSX from "xlsx";
import { useUserSession } from '/@src/stores/userSession'
import Button from 'primevue/button';
const title = 'Mapping Jurnal'
useHead({
  title: title + ' - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const jmlFilter: any = ref(0)
const isLoadingUpload: any = ref(false)
const totalSize = ref(0);
const totalSizePercent = ref(0);
const confirm = useConfirm();
const modalFilter: any = ref(false)
const isLoading: any = ref(false)
const route = useRoute()
const router = useRouter()
const dataSource: any = ref([])
const isClosing2: any = ref(false)
const isClosing: any = ref(false)
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const filtersDetail = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const dataDetailJurnal: any = ref([])
const dataPopUp: any = ref([])
const data2: any = ref([])
const isLoadingpop: any = ref(false)
const modalJurnalPost: any = ref(false)
const isPostingJurnal: any = ref(false)
const valueProgress: any = ref(0)
const dataExcel: any = ref({})
const modalJurnalEntry: any = ref(false)
const modalJurnal: any = ref(false)
const filtersCoaKredit = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const filtersCoaDebet = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const d_KelompokPasien: any = ref([])
const d_Departemen: any = ref([])
const d_Ruangan: any = ref([])
const d_Carabayar: any = ref([])
const d_DetailJenis: any = ref([])
const d_JenisJurnal: any = ref([])
const dataSourceCoaDebet: any = ref([])
const dataSourceCoaKredit: any = ref([])
const d_Produk: any = ref([])

const item: any = reactive({
  rows: 100,
  qFilterTgl: [
      new Date(),
      new Date()
  ],
  ttlDebet: 0,
  ttlKredit: 0,
  bulan: new Date()

})
const currentPage: any = ref({
  limit: 20
})


const getLastDayOfMonth = (year, month) => {
  // Create a Date object set to the next month's first day
  let firstDayOfNextMonth = new Date(year, month, 1);

  // Subtract one day to get the last day of the current month
  let lastDayOfMonth = new Date(firstDayOfNextMonth - 1);

  return lastDayOfMonth.getDate();
}
const fetchData = async () => {

  isLoading.value = true
  // let rows: any = currentPage.value.rows
  // let NamaPaket = ''
  // let JenisPaket = ''
  // let JenisTransaksi = ''
  // let StatusEnabled = ''
  var rows = '&rows=' + item.rows
  if (item.rows == undefined) {
    rows = ''
  }

  var namaproduk = '&namaproduk=' + item.namaproduk
  if (item.namaproduk == undefined) {
    namaproduk = ''
  }
  // if (item.value.qnama) NamaPaket = '&namapaket=' + item.value.qnama
  // if (item.value.jenispaket) JenisPaket = '&objectjenispaketfk=' + item.value.jenispaket
  // if (item.value.jenistransaksi) JenisTransaksi = '&objectjenistransaksifk=' + item.value.jenistransaksi
  // item.value.aktif ? StatusEnabled = '&statusenabled=' + item.value.aktif : StatusEnabled = '&statusenabled=false'

  dataSource.value = []
  const response = await useApi().get(
    '/akuntansi/get-mapping-jurnal?'+ rows + namaproduk
  )
  isLoading.value = false
  for (let x = 0; x < response.length; x++) {
    const element = response[x];
    element.no = x + 1
  }

  dataSource.value = response

}

const addMap = () => {
    item.coaNorec = undefined;
    // if (!item.objectprodukfkygdipilih) {
    //     H.alert('error', 'Pilih pelayanan dulu')
    //     return
    // }

    modalJurnal.value = true
}

const fetchProduk = async (filter: any) => {
    console.log(filter)
    let data = filter.query ? filter.query : filter
    const response = await useApi().get(`emr/dropdown/produk_m?select=id,namaproduk&param_search=namaproduk&query=${data}&limit=10`)
    d_Produk.value = response
}

const fetchDropdown = async () => {
    await useApi().get('/akuntansi/get-data-combo-map-coa').then((r) => {
        d_Departemen.value = r.departemen
        d_KelompokPasien.value = r.kelompokpasien
        d_Carabayar.value = r.carabayar
        d_DetailJenis.value = r.detailjenisproduk
        d_JenisJurnal.value = r.jenistrxjurnal
        dataSourceCoaKredit.value = r.coa
        dataSourceCoaDebet.value = r.coa
    })
}

const columnCoaDebet: any = [
    {
        "field": "id",
        "title": "ID",
        "width": "30px"
    },
    {
        "field": "kdaccount",
        "title": "Kode",
        "width": "80px"
    },
    {
        "field": "namaaccount",
        "title": "Nama Akun",
        "width": "150px"
    }
];
const columnCoaKredit: any = [
    {
        "field": "id",
        "title": "ID",
        "width": "30px"
    },
    {
        "field": "kdaccount",
        "title": "Kode",
        "width": "80px"
    },
    {
        "field": "namaaccount",
        "title": "Nama Akun",
        "width": "150px"
    }
];

const klikDebet = (e: any) => {
    item.coaIdDebet = e.id
    item.coaNoDebet = e.kdaccount
    item.coaNmDebet = e.namaaccount
}
const klikKredit = (e: any) => {
    item.coaIdKredit = e.id
    item.coaNoKredit = e.kdaccount
    item.coaNmKredit = e.namaaccount
}

const saveSetting = async () => {
    if (item.coaIdDebet == undefined) {
        H.alert('error', "Jurnal Debet belum dipilih");
        return;
    }
    if (item.coaIdKredit == undefined) {
        H.alert('error', "Jurnal Kredit belum dipilih");
        return;
    }


    let objSave = {
        "norec": item.coaNorec == undefined ? '-' : item.coaNorec,
        "objectjenistrxfk": item.jenisjurnal == undefined ? null : item.jenisjurnal.id,
        "objectcoadebetfk": item.coaIdDebet == undefined ? null : item.coaIdDebet,
        "objectcoakreditfk": item.coaIdKredit == undefined ? null : item.coaIdKredit,
        "objectdepartemenfk": item.departemen == undefined ? null : item.departemen.id,
        "objectruanganfk": item.ruangan == undefined ? null : item.ruangan.id,
        "objectkelompokpasienfk": item.kelompokPasien == undefined ? null : item.kelompokPasien.id,
        "objectprodukfk": item.objectprodukfk.value == undefined ? null : item.objectprodukfk.value,
    };
    isLoading.value = true
    await useApi().post("/akuntansi/save-map-jurnal", objSave)
        .then((res) => {
            isLoading.value = false
            clearMap()
            fetchData()
        })
        .catch((e => {
            isLoading.value = false
        }))

}
const clearMap = ()=>{
    item.coaNorec = undefined
    item.jenisjurnal = undefined
    item.coaIdDebet = undefined
    item.coaIdKredit = undefined
    item.departemen = undefined
    item.ruangan = undefined
    item.kelompokPasien = undefined
    item.objectprodukfk = undefined
    item.coaNoDebet = undefined
    item.coaNmDebet = undefined
    item.coaNoKredit = undefined
    item.coaNmKredit = undefined
}

const disMap = async (e: any) => {
    confirm.require({
          group: 'positionDialog',
          message: H.alertHapus(),
          header: 'Info ',
          icon: 'pi pi-info-circle',
          acceptClass: 'p-button-danger',
          position: 'top',
          accept: () => {
              if (e.norec == null) {
                  H.alert('error', 'tidak ada data')
                  return
              }
              let objSave = {
                  "norec": e.norec == undefined ? '-' : e.norec
              };
              nextHapus(objSave)
          },
          reject: () => {
          }
      });
    // let objSave = {
    //     "norec": e.norec == undefined ? '-' : e.norec
    // };
    // e.loadingHapus = true
    // const response = await useApi().post("/akuntansi/hapus-map-jurnal", objSave)
    // e.loadingHapus = false
    // loadDataMapJurnal()
}

const nextHapus = (objSave: any) => {
    isLoading.value = true
    useApi().post(
        `/akuntansi/hapus-map-jurnal`, objSave).then((response: any) => {
          isLoading.value = false
            fetchData()
        }).catch((e: any) => {
          isLoading.value = false
        })
}


const editMap = (e: any) => {
    item.coaNorec = e.norec;

    item.coaIdDebet = e.objectcoadebetfk;
    item.coaNoDebet = e.no_debit;
    item.coaNmDebet = e.coa_debit;

    item.coaIdKredit = e.objectcoakreditfk;
    item.coaNoKredit = e.no_kredit;
    item.coaNmKredit = e.coa_kredit;

    if (e.idproduk) {
      d_Produk.value.forEach(element => {
            if (element.id == e.idproduk) {
                item.objectprodukfk = element
                return
            }
        });
    }
    if (e.objectjenistrxfk) {
        d_JenisJurnal.value.forEach(element => {
            if (element.id == e.objectjenistrxfk) {
                item.jenisjurnal = element
                return
            }
        });
    }
    if (e.objectdepartemenfk) {
        d_Departemen.value.forEach(element => {
            if (element.id == e.objectdepartemenfk) {
                item.departemen = element
                changeInst({value:element})
                return
            }
        });
    }
    if (e.objectruanganfk) {
        d_Ruangan.value.forEach(element => {
            if (element.id == e.objectruanganfk) {
                item.ruangan = element
                return
            }
        });
    }
    if (e.objectkelompokpasienfk) {
        d_KelompokPasien.value.forEach(element => {
            if (element.id == e.objectkelompokpasienfk) {
                item.kelompokPasien = element
                return
            }
        });
    }
    if (e.objectcarabayarfk) {
        d_Carabayar.value.forEach(element => {
            if (element.id == e.objectcarabayarfk) {
                item.caraBayar = element
                return
            }
        });
    }

    modalJurnal.value = true
}

const loadMap = async (e) => {

item.tittlegridmap = ' : ' + e.namaproduk
clearMap()
item.objectprodukfkygdipilih = e.prid;


if (e.objectdepartemenfk) {

}
if (e.objectruanganfk) {
    for (let x = 0; x < d_Departemen.value.length; x++) {
        const element =  d_Departemen.value[x];
        for (let index = 0; index < element.ruangan.length; index++) {
            const element2 = element.ruangan[index];
            if(element2.id == e.objectruanganfk ){
                item.departemen = element
                changeInst({value:element})
                item.ruangan = element2
            }
        }
    }
}
e.isLoading = true
await loadDataMapJurnal()
e.isLoading = false
}

const changeInst = (e: any) => {
    d_Ruangan.value = e.value ? e.value.ruangan : []
}

const exportExcel = () => {
  let judul = 'Trial Balance'
  let column1 = ['', '', 'Saldo Awal', '', 'Mutasi', '', 'Saldo Akhir', '']
  let column = ['No Akun', 'Nama Akun', 'Debit', 'Kredit', 'Debit', 'Kredit', 'Debit', 'Kredit']
  const worksheet = XLSX.utils.aoa_to_sheet([

      [judul],
      [],
      column1,
      column,
      ...dataSource.value.map((e: any) => [
          e.noaccount,
          e.namaaccount,
          parseFloat(e.debetAwal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
          parseFloat(e.kreditAwal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
          parseFloat(e.debetMutasi).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
          parseFloat(e.kreditMutasi).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
          parseFloat(e.debetAkhir).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
          parseFloat(e.kreditAkhir).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),

          '',
      ]),
      [],
      ['Saldo Awal Debit :', 'Rp. ' + item.saldoAwalDebet],
      ['Saldo Awal Kredit :', 'Rp. ' + item.saldoAwalKredit],
      ['Mutasi Debit :', 'Rp. ' + item.mutasiDebet],
      ['Mutasi Kredit :', 'Rp. ' + item.mutasiKredit],
      ['Saldo Akhir Debit :', 'Rp. ' + item.saldoAkhirDebet],
      ['Saldo Akhir Kredit :', 'Rp. ' + item.saldoAkhirKredit],

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
  H.saveAsExcelFile(excelBuffer, 'neracasaldo');
}

const closingJurnal = () => {
  confirm.require({
      message: 'Close Jurnal bulan "' + H.formatDate(item.bulan, "MMMM YYYY") + '"',
      header: 'Konfirmasi',
      icon: 'pi pi-check-circle',
      acceptClass: 'p-button-success',
      accept: () => {
          var norec_tea = item.norecSaldo
          if (item.norecSaldo == undefined) {
              norec_tea = '-'
          }
          var tgltgl = H.formatDate(item.bulan, 'YYYYMM');
          var objSave =
          {
              ym: tgltgl,
              data: dataSource.value
          }
          isClosing.value = true
          useApi().post(
              `/akuntansi/save-data-closing-jurnal`, objSave).then((response: any) => {
                  isClosing.value = false
              }).catch((e) => {
                  isClosing.value = false
              })
      },
      reject: () => { },
  })
}
const batalClosingJurnal = () => {
  confirm.require({
      message: 'Batal Close Jurnal bulan "' + H.formatDate(item.bulan, "MMMM YYYY") + '"',
      header: 'Konfirmasi',
      icon: 'pi pi-info-circle',
      acceptClass: 'p-button-danger',
      accept: () => {
          var norec_tea = item.norecSaldo
          if (item.norecSaldo == undefined) {
              norec_tea = '-'
          }
          var tgltgl = H.formatDate(item.bulan, 'YYYYMM');
          var objSave =
          {
              ym: tgltgl,
              data: dataSource.value
          }
          isClosing2.value = true
          useApi().post(
              `/akuntansi/save-batal-closing-jurnal`, objSave).then((response: any) => {
                  isClosing2.value = false
              }).catch((e) => {
                  isClosing2.value = false
              })
      },
      reject: () => { },
  })
}
fetchData()
fetchDropdown()

</script>
<style lang="scss"></style>
