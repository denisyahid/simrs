<template>
  <ConfirmDialog />
  <div class="columns">
    <div class="column is-12 form-layout is-stacked">
      <div class=" form-outer">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>{{ TITLE_PAGE }}</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" class="btn-orderBarang"
                  :to="{ name: 'module-logistik-daftar-order-barang' }" light dark-outlined>
                  Kembali
                </VButton>
              </div>
            </div>
          </div>
        </div>
        <div class="form-body">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VDatePicker v-model="item.tglorder" color="green" trim-weeks mode="dateTime" :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VLabel>Tanggal</VLabel>
                        <VControl icon="feather:calendar">
                          <VInput type="text" placeholder="Select a date" class="is-radiusless" :value="inputValue"
                            v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>

                <div class="column is-4">
                  <VField class="is-radiusless-select is-autocomplete-select">
                    <VLabelText>Ruangan Pemesan</VLabelText>
                    <VControl icon="feather:search" :loading="isLoading">
                      <Multiselect mode="single" v-model="item.ruanganPengirim" :options="d_ruangan"
                        placeholder="Pilih data" :searchable="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField class="is-radiusless-select is-autocomplete-select">
                    <VLabelText>Ruangan Tujuan</VLabelText>
                    <VControl icon="feather:search" :loading="isLoading">
                      <Multiselect mode="single" v-model="item.ruanganTujuan" :options="d_rutu" placeholder="Pilih data"
                        :searchable="true" :disabled="disabledRuangan" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="showRacikanDose">
                  <VField class="is-radiusless-select is-autocomplete-select">
                    <VLabelText>Jenis Order</VLabelText>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="item.jenisKirim" :options="d_jenisKirim"
                        placeholder="Pilih data" :searchable="true" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4">
                  <VField class="is-rounded-select is-autocomplete-select">
                    <VLabelText>Jenis Barang</VLabelText>
                    <VControl class="prime-auto">
                        <AutoComplete v-model="item.status"
                            :suggestions="d_Status" :optionLabel="'label'"
                            @complete="fetchStatus($event)" :dropdown="true"
                            :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Status..."/>
                    </VControl>
                  </VField>
                </div>
                

                <div class="column is-4">
                  <VField>
                    <VLabelText>Nama Produk</VLabelText>
                  <VControl icon="feather:search">
                    <input v-model="item.namaprodukserch" type="text" class="input is-radiusless"
                      @keyup.enter.prevent="fetchDataOrder()" placeholder="Cari nama produk..." />
                  </VControl>
                  </VField>
                </div>

                <!-- <div class="column is-4">
                  <VField>
                    <VLabelText>Kode Produk</VLabelText>
                  <VControl icon="feather:search">
                    <input v-model="item.kodeprodukserch" type="text" class="input is-radiusless"
                      @keyup.enter.prevent="fetchDataOrder()" placeholder="Cari kode produk..." />
                  </VControl>
                  </VField>
                </div> -->

                <div class="column is-4">
                  <VField>
                    <VLabelText>Keterangan</VLabelText>
                    <VControl>
                      <input v-model="item.keterangan" type="text" class="input is-radiusless"
                        placeholder="keterangan..." />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-12">
                  <VButton @click="fetchDataOrder()" :loading="isLoading" type="button" icon="feather:search"
                    class="is-fullwidth mr-3" color="info" raised> Cari Data
                  </VButton>
                </div>
                

              </div>
            </VCard>
          </div>

          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline">

                <div class="column is-12">
                  <Toolbar class="mb-4">
                    <template #start>
                      <VButton icon="feather:plus" color="info" raised class="btn-orderBarang" @click="addPopUp()">
                        Buat Order
                      </VButton>
                    </template>
                  </Toolbar>

                  <!-- <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple" editMode="cell" showGridlines @cell-edit-complete="getSelisih" tableClass="editable-cells-table"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"> -->
                  <DataTable :value="enhancedDataSource" showGridlines editMode="cell" class="p-datatable-sm"
                    @cell-edit-complete="getSelisih" tableClass="editable-cells-table"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} " :paginator="true" :rows="5"
                    :rowsPerPageOptions="[5, 10, 25]">
                    <Column headerStyle="width: 3rem">
                      <template #header>
                        <input type="checkbox" v-model="allSelected" @change="toggleAll" style="transform: scale(1.2); vertical-align: middle;" />
                      </template>
                      <template #body="slotProps">
                        <VCheckbox v-model="item.produkCeklis[slotProps.data.produkfk]" color="info"
                          @change="onPasienSelected()" />
                      </template>
                    </Column>
                    
                    <Column field="no" header="#"></Column>
              <Column field="kdproduk" frozen :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%;">
                    Kode <br> Produk
                  </div>
                </template>
              </Column>
              <Column field="namaproduk" frozen :sortable="true" style="min-width: 400px">
                <template #header>
                  <div style="text-align: center;width: 100%;">
                    Nama <br> Produk
                  </div>
                </template>
              </Column>
              <Column field="satuanstandar" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 80%; margin-left:15px">
                    Satuan
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.satuanstandar }}
                  </div>
                </template>
              </Column>
              <Column field="pengeluaran" :sortable="true" style="min-width:100px">
                <template #header>
                  <div style="text-align: center; width: 100%; margin-left:15px">
                    {{ judulpemakaian ? judulpemakaian : "Pemakaian" }}
                  </div>
                </template>
              <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.pengeluaran }}
                  </div>
                </template>
              </Column>
              <Column field="avgpemakaian" :sortable="true" style="min-width:100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    {{ judulavgpemakaian  ? judulavgpemakaian : "Avg Pemakaian" }}
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.avgpemakaian }}
                  </div>
                </template>
              </Column>
              
              <Column field="kebutuhan6bulan" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    {{ judulkebutuhan ? judulkebutuhan : "Kebutuhan" }}
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.kebutuhan6bulan }}
                  </div>
                </template>
              </Column>
              <Column field="total" :sortable="true" style="min-width: 80px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Sisa <br> Stok
                  </div>
                </template>
                <template #body="slotProps">
                  <div style="text-align: center;width: 100%;">
                    <span :class="{'text-red': slotProps.data.total <= slotProps.data.minstok, 'text-black': slotProps.data.total > slotProps.data.minstok}">
                        {{ slotProps.data.total }}
                    </span>
                   </div>
                 </template>
              </Column>
              <Column field="rencanakebutuhan" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Rencana <br> Kebutuhan
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.rencanakebutuhan }}
                  </div>
                </template>
                
              </Column>
              <Column field="minstok" :sortable="true" style="min-width: 80px;">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Min <br>Stock
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.minstok }}
                  </div>
                </template>
              </Column>
              <Column field="maxstock" :sortable="true" style="min-width: 80px">
                <template #header>
                  <div style="text-align: center;width: 100%;" margin-left:15px>
                    Max  <br> Stock
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.maxstock }}
                  </div>
                </template>
              </Column>
              <Column field="tingkatkecukupan"  :sortable="true" style="min-width: 80px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Tingkat <br> Kecukupan / Hari
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.tingkatkecukupan }}
                  </div>
                </template>
              </Column>
              <Column field="statusCito" :sortable="true" style="min-width: 100px">
                <template #header>
                  <div style="text-align: center;width: 100%; margin-left:15px">
                    Status
                  </div>
                </template>
                <template #body="{ data }">
                  <div style="text-align: center;">
                    {{ data.statusCito }}
                  </div>
                </template>
              </Column>    


                    <template #paginatorstart>
                      <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                    </template>
                    <template #paginatorend>
                      <Button type="button" icon="pi pi-cloud" class="p-button-text" />
                    </template>

                  </DataTable>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </div>
    </div>

    <!-- </div> -->

    <VModal :open="modalInput" title="Add Barang" size="large" actions="right" @close="modalInput = false">
      <template #content>
         <VButton icon="feather:plus" color="info" raised class="btn-orderBarang mb-5" @click="addPopUp2()">
            Tambah Item
        </VButton> 
        <DataTable :value="dataSource" showGridlines editMode="cell" class="p-datatable-sm"
          @cell-edit-complete="getSelisih" tableClass="editable-cells-table"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} " :paginator="true" :rows="5"
          :rowsPerPageOptions="[5, 10, 25]">
          <Column :exportable="false" header="#" style="width:1rem">
            <template #body="slotProps">
              <Button icon="pi pi-trash" class="p-button-rounded p-button-danger" @click="hapusRow(slotProps.data)" />
            </template>
          </Column>

          <Column field="no" header="No" :style="{ width: '10px' }"/>
          <Column field="produkfk" header="Kode Produk" :hidden="true"></Column>
          <!-- <Column field="jumlah" header="Rencana Kebutuhan"></Column> -->
          <Column field="kdproduk" header="Kode Produk"></Column>
          <Column field="namaproduk" header="Produk" :sortable="true"></Column>
          <Column field="satuanstandar" header="Satuan"></Column>
          <Column field="nilaikonversi" header="Konversi" :hidden="true"></Column>
          <Column field="harganetto" header="harganetto" :hidden="true"></Column>
          <Column field="pengeluaran" header="Pemakaian"></Column>
          <Column field="avgpemakaian" header="AVG Stock"></Column>
          <Column field="minstok" header="Min Stock"></Column>
          <Column field="maxstock" header="Max Stock"></Column>
          <Column field="jmlstok" header="Sisa Stok"></Column>
          <Column field="stokpengirim" header="Stok Tujuan"></Column>

          <Column style="text-align: end;" header="Qty Order">
            <template #body="slotProps">
              <span :style="getTextColor(slotProps.data.jumlah)">
                {{ formatValue(slotProps.data.jumlah) }}
              </span>
            </template>
            <template #editor="{ data, field }">
              <input type="number" v-model="data[field]" autofocus />
            </template>
          </Column>
        </DataTable>

      </template>
      <template #action>
        <VButton icon="feather:plus" @click="save()" color="primary" raised  :loading="isSimpan">Simpan</VButton>
      </template>
    </VModal>

    <VModal :open="modalInput2" title="Add Barang" size="large" actions="right" @close="modalInput2 = false">
      <template #content>
        <form class="modal-form">
              <div class="group-header">
                                          <VField>
                                            <div class="column is-12">
                                              <div class="column is-12">
                                                <VControl icon="feather:search">
                                                  <input v-model="item.namaprodukserch" type="text" class="input is-rounded"
                                                    @keyup.enter.prevent="filterProdukna()" placeholder="Cari nama produk..." />
                                                </VControl>
                                                </div> 
                                                <div class="column is-12">
                                                    <VControl icon="feather:search">
                                                  
                                                    <input v-model="item.kodeprodukserch" type="text" class="input is-rounded"
                                                      placeholder="Cari kode produk..." />
                                                    </VControl>    
                                                </div>    
                                            </div> 
                                             
                                             
                                            <div class="column is-12">
                                                <VButton @click="filterProdukna()" :loading="isLoading" type="button" icon="feather:search"
                                                  class="is-fullwidth mr-3" color="info" raised> Cari Data
                                                </VButton>
                                            </div>
                                          
                                          </VField>
                                          
               </div>
         </form>
        <form class="modal-form">
        <div class="column is-12 mt-0">
                                <div class="form-section pt-0 pl-0">
                                    <div class="form-section-inner">
                                     
                                        <div class="column is-12 h-400-o">
                                          <div class="columns is-multiline mb-3" :loading="isLoading">
                                              <div class="column is-4"  v-for="items in filteredLayanan"
                                              :key="items.id"  >
                                              
                                                        <VField grouped>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="item.produkCeklis[items.id]"
                                                                    :label="items.namaprodukuse" color="info"
                                                                    @change="getSelected()" />
                                                            </VControl>
                                                        </VField>

                                              </div>
                                             
                                            
                                          </div>
                                      </div>
                                    </div>
                                </div>
            </div>
          </form>       
      
         </template>
      <template #action>
     <VButton icon="feather:plus" @click="tambah2()" color="primary" raised :disabled="isLoading">Tambah</VButton>
      </template>
    </VModal>
  </div>
</template>

<script setup lang="ts">

import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { formatRp } from '/@src/utils/appHelper'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useConfirm } from 'primevue/useconfirm'
import PrimeVue from 'primevue/config';
import Fieldset from 'primevue/fieldset';
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import AutoComplete from 'primevue/autocomplete';
// import { useCurrencyInput } from 'vue-currency-input'
import moment from 'moment'
const TITLE_PAGE = 'Order Barang'
useHead({
  title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let NOREC_ORDER: any = useRoute().query.norec as string
let NOREC_KIRIM: any = useRoute().query.norec_kirim as string
const modalInput = ref(false)
const modalInput2 = ref(false)

let item: any = reactive({
  header: {},
  totalAll: 0,
  jumlah: 0,
  tglorder: new Date(),
  produkCeklis: [],
})
const confirm = useConfirm();
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

const d_ruangan: any = ref([])
const d_rutu: any = ref([])

const d_satuan: any = ref([])
const d_jenisKirim: any = ref([])
const d_jenisBarang: any = ref([])
const d_Jabatan: any = ref([])
const d_Pegawai: any = ref([])
const d_satuanResep: any = ref([])
const dataSource: any = ref([])
const dataSourceOrder: any = ref([])

const data2: any = ref([])
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const isLoading: any = ref(false)
const isSimpan: any = ref(false)
const modalStokProduk: any = ref(false)
const modalTandaTangan: any = ref(false)
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const dataProdukDetail: any = ref([])
const disabledRuangan: any = ref(false)
const disabledJenis: any = ref(false)
let d_Status: any = ref([])
const isReady = ref(false)
const dataSelected: any = ref({})
const dataSourceStok: any = ref([])
const isEdit: any = ref(false)
const allSelected: any = ref(false)
const isLoadingData: any = ref(false)
const d_Produk: any = ref([])
const d_produk: any = ref([])
const d_ProdukDef: any = ref([])
const filterLayanan: any = ref('')
const judulpemakaian: any = ref('')
const judulavgpemakaian: any = ref('')
const judulkebutuhan: any = ref('')
const listChecked: any = ref([])
const getStokProduk = async (e: any) => {
  await useApi().get(`dashboard/logistik/get-informasi-stok?produkfk=${e}`).then((response) => {
    modalStokProduk.value = true
    response.infostok.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceStok.value = response.infostok
  })
}




const fetchStatus = async (filter) => {
  const StatusOptions = [
  { label: "Semua", value: "S" },
  { label: "OBAT", value: "O" },
  { label: "Narkotika", value: "N" },
  { label: "Psikotropika", value: "P" },
  { label: "BMHP", value: "B" },
  { label: "AMHP", value: "A" },
  { label: "GAS MEDIS", value: "G" },
];
  d_Status.value = StatusOptions;
};

watch(()=>item.ruanganPengirim,(newValue,oldValue)=>
{
  if(newValue == 326 || newValue == 327 || newValue == 329 || newValue == 324 || newValue == 328 || newValue == 325)
  {
    judulpemakaian.value='Pemakaian 30 Hari';
    judulavgpemakaian.value='Avg Pemakaian/Hari';
    judulkebutuhan.value='Kebutuhan 4 Hari';

  }
  else 
  {
    judulpemakaian.value='Pemakaian 3 Bulan';
    judulavgpemakaian.value='Avg Pemakaian/Minggu';
    judulkebutuhan.value='Kebutuhan 1 Bulan';
  }
  }
)

const onInit = async () => {
  loadDrop()
}

const loadDrop = async () => {
  isLoading.value = true
  const response = await useApi().get(`/logistik/list-order-cbo`)
  // d_produk.value = response.produk.map((e: any) => { return { label: e.namaproduk, value: e } })
  d_ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
  d_jenisKirim.value = response.jenis.map((e: any) => { return { label: e.jenis, value: e.id } })
  item.jenisKirim = response.jenis[0].id
  // d_jenisBarang.value = response.jenisbarang.map((e: any) => { return { label: e.detailjenisproduk, value: e.id } })
  d_satuanResep.value = response.satuanresep.map((e: any) => { return { label: e.satuanresep, value: e } })
  d_rutu.value = response.rutu.map((e: any) => ({ label: e.namaruangan, value: e.id }))

  const defaultRuangan = d_rutu.value.find(e => e.value === 343)
  if (defaultRuangan) {
    item.ruanganTujuan = defaultRuangan.value
  }
    disabledRuangan.value = false;
    disabledJenis.value = false;
    if (NOREC_ORDER != undefined) {
      loadEdit()
    }
    item.keterangan = ''
    isLoading.value = false
}

const loadEdit = async () => {
  if (NOREC_ORDER != '') {
    isEdit.value = true
    isSimpan.value = true;
    isLoading.value = true
    const response = await useApi().get(`/logistik/get-detail-order?norec=${NOREC_ORDER}`)
    isLoading.value = false
    isSimpan.value = false;
    isEdit.value = false
    disabledRuangan.value = true;
    let headerKirim = response.head
    let detailKirim = response.detail


    item.qtyproduk
    item.jenisKirim = response.head.jeniskirimfk
    item.ruanganPengirim = response.head.objectruanganasalfk
    item.ruanganTujuan = response.head.objectruangantujuanfk
    item.keterangan = headerKirim.keteranganorder
    item.tglorder = new Date(headerKirim.tglorder);

    data2.value = detailKirim
    // dataSource.value = data2.value  //default
    enhancedDataSource.value = data2.value
    isLoadingData.value = false
  } else {

    isSimpan.value = true;
    isLoading.value = true
    const response = await useApi().get(`/logistik/get-detail-order?norec=${NOREC_ORDER}`)
    isSimpan.value = false;
    isLoading.value = false
    isEdit.value = false
    disabledRuangan.value = true;

    let headerKirim = response.head
    let detailKirim = response.detail
    item.namaruangan = { id: headerKirim.objectruanganasalfk, namaruangan: headerKirim.namaruangan }
    item.namaruangan2 = { id: headerKirim.objectruangantujuanfk, namaruangan2: headerKirim.namaruangan2 }
    item.tglorder = new Date(headerKirim.tglorder);

    data2.value = detailKirim
    // dataSource.value = data2.value // default
    enhancedDataSource.value = data2.value
  }
}
// }


const addPopUp2 = () => {
  if (!item.ruanganTujuan) {
    H.alert('error', 'Ruangan Tujuan Tidak Boleh Kosong')
    return
  }

  if (!item.ruanganPengirim) {
    H.alert('error', 'Ruangan Pemesan Tidak Boleh Kosong')
    return
  }

  isLoading.value = false
  modalInput2.value = true
  fetchTindakan()
}


const addPopUp = () => {
  if (!item.ruanganTujuan) {
    H.alert('error', 'Ruangan Tujuan Tidak Boleh Kosong')
    return
  }

  if (!item.ruanganPengirim) {
    H.alert('error', 'Ruangan Pemesan Tidak Boleh Kosong')
    return
  }

  tambah()
  isLoading.value = false
  modalInput.value = true
  
}


const tambah = () => {
  

  if (listChecked == undefined ) {
    H.alert('error', 'Pilih layanan terlebih dahulu')
    return
  }
  
  var dataceklina = listChecked.value;

  var arrobj = Object.keys(item.produkCeklis)
  var datana = []
  let status = true; // Ganti dengan nilai boolean

  for (let i = 0; i < dataceklina.length; i++) {
      const element = dataceklina[i];
   
      if (element.produkfk === undefined) {
          status = false; // Set status ke false jika produkfk undefined
          break; // Keluar dari loop jika ditemukan produkfk undefined
      }
  }


  if(status == true)
  {
      for (var i = 0; i < dataceklina.length; i++) 
      {
        for (var d = 0; d < arrobj.length; d++) 
        {
          if (dataceklina[i]['produkfk'] == parseInt(arrobj[d])) {
      
            let nomor = 0
            if (data2.value.length == 0) {
              nomor = 1
            } else {
              nomor = data2.value.length + 1
            }
            var qtyCetak = 0;
            var total = 0;
            var jumlahreal = 0;

            let jmlbulat = 0;
            let jml = 0;

            jmlbulat = 1;
            jml = 1;
            isLoading.value = true
            disabledRuangan.value = true;
            var data: any = {};
            if (item.no != undefined) {
              for (let x = 0; x < data2.value.length; x++) {
                const element = data2.value[x];
                if (element.no == item.no) {
                  data.no = item.no
                  data.hargajual = String(item.hargaSatuan)
                  data.stock = String(item.stok)
                  data.harganetto = String(item.harganetto)
                  data.ruanganfk = item.ruanganPengirim.id ? item.ruanganPengirim.id : element.ruanganfk
                  data.produkfk = item.produk.id
                  data.kdproduk = item.produk.kdproduk
                  data.avgpemakaian = item.produk.avgpemakaian
                  data.pengeluaran = item.produk.pengeluaran
                  data.minstok = item.produk.minstok
                  data.maxstock = item.produk.maxstock
                  data.stokpengirim = item.produk.stokpengirim
                  data.namaproduk = item.produk.namaproduk
                  data.productname = item.produk.productname
                  data.nilaikonversi = item.nilaiKonversi
                  data.satuanstandarfk = item.satuan.ssid ? item.satuan.ssid : element.satuanstandarfk
                  data.satuanstandar = item.satuan.satuanstandar ? item.satuan.satuanstandar : element.satuanstandar
                  data.satuanviewfk = item.satuan.ssid ? item.satuan.ssid : element.satuanstandarfk
                  data.satuanview = item.satuan.satuanstandar ? item.satuan.satuanstandar : element.satuanstandarfk
                  data.jmlstok = String(item.total)
                  data.jumlah = item.jumlah ? item.jumlah : 1
                  data.qtyorder = item.qtyorder ? item.qtyorder : 1
                  data.hargasatuan = String(item.hargaSatuan)
                  data.hargadiscount = String(item.hargadiskon)
                  data.total = item.total
                  data2.value[x] = data;
                }
              }

            } else {
              data = {
                no: nomor,
                stock: dataceklina[i]['jmlstok'],
                ruanganfk: item.ruanganTujuan,
                produkfk: dataceklina[i]['produkfk'],
                kdproduk: dataceklina[i]['kdproduk'],
                avgpemakaian: dataceklina[i]['avgpemakaian'],
                pengeluaran: dataceklina[i]['pengeluaran'],
                minstok: dataceklina[i]['minstok'],
                harganetto: dataceklina[i]['harganetto'],
                maxstock: dataceklina[i]['maxstock'],
                stokpengirim: dataceklina[i]['stokpengirim'],
                namaproduk: dataceklina[i]['namaproduk'],
                productname: dataceklina[i]['namaproduk'],
                nilaikonversi: dataceklina[i]['nilaikonversi'] ? dataceklina[i]['nilaikonversi'] : 1,
                satuanstandarfk: dataceklina[i]['satuanstandarfk'],
                satuanstandar: dataceklina[i]['satuanstandar'],
                satuanviewfk: dataceklina[i]['satuanstandarfk'],
                satuanview: dataceklina[i]['satuanstandar'],
                jmlstok: dataceklina[i]['jumlah'],
                jumlah: formatValue(dataceklina[i]['qtyorder']) ? formatValue(dataceklina[i]['qtyorder']) : 1,
                total: parseFloat(dataceklina[i]['harganetto'])
              }
            }
          }

        }
        console.log(data);
        data2.value.push(data)
      }
  }

  // var qtyOK: any = 0;

  dataSource.value = data2.value
  isLoading.value = false
  listChecked.value = []
  // item.produkCeklis = []
  clearInput()
}


const editRow = async (e: any) => {
  e.LoadBtnEdit = true
  await fetchProduk({ query: e.namaproduk })
  item.no = e.no
  d_produk.value.forEach((element: any) => {
    if (element.id == e.produkfk) {
      item.produk = element
      return
    }
  });
  d_satuan.value.forEach(element => {
    if (element.value.ssid == e.satuanstandarfk) {
      item.satuan = element
      return
    }
  });
  dataSelected.value = e
  GETKONVERSI()
  modalInput.value = true
  e.LoadBtnEdit = false
  // item.nilaiKonversi = e.nilaikonversi
}

const hapusRow = (e: any) => {


  
  // for (var i = data2.value.length - 1; i >= 0; i--) {
  //   console.log('data2',data2.value);
    
  //   if (data2.value[i].no == e.no) {
  //     data2.value.splice(i, 1);
  //     // dataOK.value.splice(i, 1);
  //   }
  // }
  for (let index = 0; index < data2.value.length; index++) {
    const element = data2.value[index];

    if(element.no == e.no){
      data2.value.splice(index,1)
      
    }
    
  }
  dataSource.value = data2.value
  // dataGridKronis.value = dataOK.value

  // clearInput()
}

const tambah2 = () => {
  if (item.produkCeklis == undefined || item.produkCeklis.length == 0) {
        H.alert('error', 'Pilih layanan terlebih dahulu')
        return
    }
    
  
  var dataceklina = listChecked.value;
  console.log('ini dataceklina', dataceklina)
  var arrobj = Object.keys(item.produkCeklis)
  var datana = []
    for (var i =0;  i < dataceklina.length; i++) {
      for(var d =0; d < arrobj.length; d++)
      {
        if (dataceklina[i]['id']== parseInt(arrobj[d])) {
          let nomor = 0
          if (data2.value.length == 0) {
            nomor = 1
          } else {
            nomor = data2.value.length + 1
          } 
          var qtyCetak = 0;
          var total = 0;
          var jumlahreal = 0;

          let jmlbulat = 0;
          let jml = 0;

          jmlbulat =1;
          jml = 1;
          isLoading.value = false   
          disabledRuangan.value = true;
          var data: any = {};
          if (item.no != undefined) {
            for (let x = 0; x < data2.value.length; x++) {
              const element = data2.value[x];
              if (element.no == item.no) {
                data.no = item.no
                data.hargajual = String(item.hargaSatuan)
                data.stock = String(item.stok)
                data.harganetto = String(item.hargaSatuan)
                data.ruanganfk = item.ruanganPengirim.id ? item.ruanganPengirim.id : element.ruanganfk
                data.namaruanganpengirim = item.namaruanganpengirim
                data.namaruangantujuan = item.namaruangantujuan
                data.produkfk = item.produk.kdproduk
                data.namaproduk = item.produk.namaproduk
                data.productname = item.produk.productname
                data.nilaikonversi = item.nilaiKonversi
                data.avgpemakaian = Math.ceil(item.avgpemakaian)
                data.pengeluaran = Math.ceil(item.pengeluaran)
                data.minstok = Math.ceil(item.minstok)
                data.maxstock = Math.ceil(item.maxstock)
                data.kebutuhan = Math.ceil(item.kebutuhan)
                data.satuanstandarfk = item.satuan.ssid ?  item.satuan.ssid : element.satuanstandarfk
                data.satuanstandar = item.satuan.satuanstandar ? item.satuan.satuanstandar : element.satuanstandar
                data.satuanviewfk = item.satuan.ssid ?  item.satuan.ssid : element.satuanstandarfk
                data.satuanview = item.satuan.satuanstandar ? item.satuan.satuanstandar : element.satuanstandarfk
                data.jmlstok = item.jmlstok
                data.jumlah = formatValue(Math.ceil(item.rencanakebutuhan))
                data.qtyorder = formatValue(Math.ceil(item.rencanakebutuhan))
                data.hargasatuan = String(item.hargaSatuan)
                data.hargadiscount = String(item.hargadiskon)
                data.total = item.total
                data.stokpengirim = item.stokpengirim
                data2.value[x] = data;
              }
            }
            
          } else {
            let nomor = data2.value.length > 0 ? data2.value[data2.value.length - 1].no + 1 : 1;
            data = {
              no: nomor,
              stock:  dataceklina[i]['qtyproduk'],
              stokpengirim:  dataceklina[i]['stokpengirim'],
              ruanganfk: item.ruanganTujuan,
              produkfk: dataceklina[i]['id'],
              kdproduk: dataceklina[i]['kdproduk'],
              namaproduk: dataceklina[i]['namaproduk'],
              productname: dataceklina[i]['namaproduk'],
              avgpemakaian: Math.ceil(dataceklina[i]['avgpemakaian']),
              pengeluaran: Math.ceil(dataceklina[i]['pengeluaran']),
              minstok: Math.ceil(dataceklina[i]['minstok']),
              maxstock: Math.ceil(dataceklina[i]['maxstock']),
              kebutuhan: Math.ceil(dataceklina[i]['kebutuhan']),
              pengeluaran: dataceklina[i]['pengeluaran'],
              nilaikonversi: dataceklina[i]['nilaikonversi']?dataceklina[i]['nilaikonversi']:1,
              satuanstandarfk:dataceklina[i]['satuanstandarfk'],
              satuanstandar: dataceklina[i]['satuanstandar'],
              satuanviewfk:dataceklina[i]['satuanstandarfk'],
              satuanview: dataceklina[i]['satuanstandar'],
              jmlstok:  dataceklina[i]['jmlstok'],
              jumlah: formatValue(Math.ceil(dataceklina[i]['rencanakebutuhan'])),
              total: parseFloat( dataceklina[i]['qtyproduk'])* parseFloat(dataceklina[i]['harganetto'])
     }
                   

          }
        }

      }
        data2.value.push(data)
        dataSource.value = data2.value
        modalInput2.value = false

        
    }
  }

const save = async () => 
{
  console.log(data2.value)
  const toaster = useToaster(); // Panggil sekali di awal

  for (let i = 0; i < data2.value.length; i++) {
      const element = data2.value[i];
  

      if (element.jmlstok > element.minstok) 
      {
          toaster.error(`(${element.namaproduk}) Terdapat Item dengan Sisa Stok + Qty Amprah > Max Stock`);
          return; 
      }
  }

//Buat Tes------------------------------------------------------------
//return;

  if (item.ruanganPengirim == 326 || item.ruanganPengirim == 327 || item.ruanganPengirim == 329 || item.ruanganPengirim == 324 || item.ruanganPengirim == 328 || item.ruanganPengirim == 325) 
  {
    for (let x = 0; x < data2.value.length; x++) {
        let validasi = 0;
        const element2 = data2.value[x];
        validasi = parseFloat(element2.jmlstok)+parseFloat(element2.jumlah)
        console.log('ini validasi',validasi)

        if (validasi > element2.maxstock) {
          if (element2.maxstock !== 0) 
            { 
                toaster.error(`(${element2.namaproduk}) Terdapat Item dengan Sisa Stok + Qty Amprah > Max Stock`);
                return;
            }
          }
    }
  }
// return;
  
  if (data2.value.length == 0) {
    useToaster().error('Produk belum di pilih')
    return
  }

  if (!item.jenisKirim) {
    useToaster().error('Jenis Kirim Tidak Boleh Kosong')
    return
  }
  for (let x = 0; x < data2.value.length; x++) {
    const cek_jumlah = data2.value[x].jumlah;

    if (cek_jumlah == 0 || cek_jumlah == undefined || cek_jumlah == null) {
      useToaster().error('Terdapat jumlah kosong, Silahkan isi');
      return;
    }

    if (!Number.isInteger(cek_jumlah)) {
      useToaster().error('Jumlah tidak boleh berupa desimal');
      return;
    }
  }

  var Keterangan = "-"
  if (item.keterangan !== undefined && item.keterangan !== '') {
    Keterangan = item.keterangan
  }

  

  var strukorder = {
    objectruanganfk: item.ruanganPengirim,
    objectruangantujuanfk: item.ruanganTujuan,
    jenispermintaanfk: item.jenisKirim,
    keteranganorder: Keterangan,
    qtydetailjenisproduk: 0,
    qtyjenisproduk: data2.value.length,
    tglorder: moment(item.tglorder).format('YYYY-MM-DD HH:mm:ss'),
    totalhargasatuan: 0,
    norecorder: NOREC_ORDER ? NOREC_ORDER : '',
    noreckirim: NOREC_KIRIM ? NOREC_KIRIM : '',
  }
  var objSave =
  {
    strukorder: strukorder,
    details: data2.value
  }

  isSimpan.value = true
  await useApi().post(
    `/logistik/save-order-barang`, objSave).then((response: any) => {
      isSimpan.value = false
      window.history.back();
    }, (error) => {
      isSimpan.value = false
      // console.log(error)
    })


}

const changeProduk = (e: any) => {
  if (e != null) {
    GETKONVERSI()
  }
}

const GETKONVERSI = async () => {
  isLoading.value = true
  if (item.produk.konversisatuan.length == 0) {
    d_satuan.value = [
      {
        label: item.produk.satuanstandar, value:
          { ssid: item.produk.ssid, satuanstandar: item.produk.satuanstandar }
      }]
  } else {
    d_satuan.value = item.produk.konversisatuan.map((e: any) => {
      return { label: e.satuanstandar, value: e }
    })
  }
  d_satuan.value.forEach(element => {
    if (item.produk.ssid == element.value.ssid) {
      item.satuan = element
    }
  });
  isReady.value = true
  isLoading.value = true
  item.nilaiKonversi = 1
  dataProdukDetail.value = []
  await useApi().get(
    '/farmasi/get-produkdetail?produkfk=' + item.produk.id +
    '&ruanganfk=' + item.ruanganTujuan).then(function (response: any) {
      if (response.detail.length > 0) {
        if (dataSelected.value.no != undefined) {
          console.log(dataSelected.value)
          item.jumlah = dataSelected.value.jumlah
          item.qtykonfirmasi = dataSelected.value.jumlah
          item.nilaiKonversi = dataSelected.value.nilaikonversi
        }
        dataProdukDetail.value = response.detail
        item.stok = response.jmlstok / item.nilaiKonversi
        if (response.kekuatan == undefined || response.kekuatan == 0) {
          response.kekuatan = 1
        }
        item.kekuatan = response.kekuatan
        item.sediaan = response.sediaan
        item.tglKadaluarsa = response.detail[0]
      } else {
        if (dataSelected.value.no != undefined) {
          console.log(dataSelected.value)
          item.jumlah = dataSelected.value.jumlah
          item.stok = dataSelected.value.jmlstok
          item.qtykonfirmasi = dataSelected.value.jumlah
          item.nilaiKonversi = dataSelected.value.nilaikonversi
        } else {
          item.stok = 0
          item.hargaSatuan = 0
          item.hargadiskon = 0
          item.hargaNetto = 0
          item.total = 0
        }
      }
    });
  isLoading.value = false
  isReady.value = false
}

const clearInput = () => {
  delete item.produk
  delete item.asal
  delete item.satuan
  delete item.no
  delete item.satuanresep
  delete dataSelected.value
  delete item.tglKadaluarsa
  item.qty = 1
  modalInput.value = false
  item.nilaiKonversi = 0
  item.stok = 0
  item.jumlah = 0
  item.jumlahbulat = item.jumlah

}

function toggleAll() {
  enhancedDataSource.value.forEach(row => {
        row.isSelected = allSelected.value ;
    });
    if (enhancedDataSource.value.length > 0) {
        var arrobj = enhancedDataSource.value
        for (var x = 0; x < enhancedDataSource.value.length; x++) {
          const element = arrobj[x];
          if (element.isSelected == true) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element3 = listChecked.value[z];
              if (element3.produkfk == element.produkfk) {
                listChecked.value.splice(z, 1)
              }
            }
            item.produkCeklis[element.produkfk]=true
            listChecked.value.push({
              hargajual: element.harganetto ? element.harganetto : 0,
              stock: String(element.jumlah),
              harganetto: item.harganetto ? item.harganetto : 0 ,
              ruanganfk: item.ruanganPengirim.id ? item.ruanganPengirim.id : element.ruanganfk,
              produkfk: element.produkfk,
              namaproduk: element.namaproduk,
              kdproduk:element.kdproduk,
              avgpemakaian:element.avgpemakaian,
              pengeluaran:element.pengeluaran,
              maxstock:element.maxstock,
              pengeluaran:element.pengeluaran,
              minstok:element.minstok,
              stokpengirim:element.stokpengirim,
              productname: element.namaproduk,
              nilaikonversi: element.nilaiKonversi,
              satuanstandarfk: element.satuanstandarfk,
              satuanstandar: element.satuanstandar,
              satuanviewfk: element.satuanstandarfk,
              satuanview: element.satuanstandar,
              jmlstok: String(element.total),
              jumlah: element.total,
              qtyorder: element.rencanakebutuhan,
              hargasatuan: String(element.harga),
              hargadiscount: 0,
              total: 0,

            })
          }
          else {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element3 = listChecked.value[z];
              if (element3.produkfk == element.produkfk) {
                listChecked.value.splice(z, 1)
                dataSource.value.splice(z, 1);
                item.produkCeklis[element3.produkfk] = false
              }
            }

          }
        }
      }
}

const changeSatuan = (e: any) => {
  item.nilaiKonversi = item.satuan.value.nilaikonversi
}

const fetchJabatan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/jabatan_m?select=id,namajabatan&param_search=namajabatan&query=${filter.query}&limit=20`
  ).then((response) => {
    d_Jabatan.value = response
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=20`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(`/logistik/distribusi-barang-produk?namaproduk=${filter.query}&ruanganfk=${item.ruanganTujuan}&limit=10`)
  d_produk.value = response.produk
}


function back() {
  window.history.back()
}
onInit()

watch(
  () => item.nilaiKonversi,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (item.stok > 0) {
        item.stok = parseFloat(item.stok) * (parseFloat(oldValue) / parseFloat(newValue))
        item.jumlahbulat = 0;
        item.jumlah = 0
        item.hargaSatuan = 0
        item.hargadiskon = 0
        item.hargaNetto = 0
        item.total = 0
      }
    }
  }
)


const getSelected = () => {
  if (item.produkCeklis.length > 0) {
    var arrobj = Object.keys(item.produkCeklis)
    for (var x = 0; x < arrobj.length; x++) {
      const element = arrobj[x];
      if (item.produkCeklis[parseInt(element)] == true) {
        for (var i = 0; i < d_ProdukDef.value.length; i++) {
          const element2 = d_ProdukDef.value[i];
          if (element2.id == element) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element3 = listChecked.value[z];
              if (element3.namaproduk == element2.namaproduk) {
                listChecked.value.splice(z, 1)
              }
            }
            console.log(listChecked.value)
            listChecked.value.push({
              norec: element2.norec,
              namaproduk: element2.namaproduk,
              id: element2.id,
              tgl: element2.tgl,
              objectasalprodukfk: element2.objectasalprodukfk,
              asalproduk: element2.asalproduk,
              harganetto: element2.harganetto,
              hargadiscount: element2.hargadiscount,
              kdproduk: element2.kdproduk,
              minstok: element2.minstok,
              maxstock: element2.maxstock,
              jmlstok:element2.qtyprodukpengirim,
              avgpemakaian: element2.avgpemakaian,
              harganetto: element2.harganetto,
              pengeluaran: element2.pengeluaran,
              kebutuhan6bulan: element2.kebutuhan6bulan,
              qtyorder: element2.kebutuhan6bulan,
              jumlah: element2.kebutuhan6bulan,
              rencanakebutuhan: element2.rencanakebutuhan,
              minstok: element2.minstok,
              tingkatkecukupan: element2.tingkatkecukupan,
              stokpengirim: element2.qtyproduktujuanpengirim,
              hargajual: element2.hargajual,
              persenhargajualproduk: element2.persenhargajualproduk,
              qtyproduk: element2.qtyproduk,
              objectruanganfk: element2.objectruanganfk,
              nostrukterimafk: element2.nostrukterimafk,
              tglkadaluarsa: element2.tglkadaluarsa,
              persenup: element2.persenup,
              norec_spd: element2.norec_spd,
              satuanstandar: element2.satuanstandar,
              nilaikonversi: element2.nilaikonversi,
              satuanstandarfk: element2.satuanstandarfk,
              namaprodukuse: element2.namaprodukuse,

            })
          }
        }
      } else {
        for (var i = 0; i < d_ProdukDef.value.length; i++) {
          const element2 = d_ProdukDef.value[i];
          if (element2.id == element.id) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element3 = listChecked.value[z];
              if (element3.namaproduk == element2.namaproduk) {
                listChecked.value.splice(z, 1)
              }
            }
          }
        }
      }
    }

  }
}

const filteredLayanan = computed(() => {

  var filtered: any = [];
  for (let i = 0; i < d_Produk.value.length; i++) {
    const element2 = d_Produk.value[i];


    filtered.push({
      norec: element2.norec,
      namaproduk: element2.namaproduk,
      id: element2.id,
      tgl: element2.tgl,
      objectasalprodukfk: element2.objectasalprodukfk,
      asalproduk: element2.asalproduk,
      harganetto: element2.harganetto,
      hargadiscount: element2.hargadiscount,
      hargajual: element2.hargajual,
      kdproduk:element2.kdproduk,
      minstok:element2.minstok,
      jmlstok:element2.qtyprodukpengirim,
      maxstock:element2.maxstock,
      pengeluaran:element2.pengeluaran,
      stokpengirim:element2.stokpengirim,
      persenhargajualproduk: element2.persenhargajualproduk,
      qtyproduk: element2.qtyproduk,
      objectruanganfk: element2.objectruanganfk,
      nostrukterimafk: element2.nostrukterimafk,
      tglkadaluarsa: element2.tglkadaluarsa,
      avgpemakaian: element2.avgpemakaian,
      pengeluaran: element2.pengeluaran,
      persenup: element2.persenup,
      norec_spd: element2.norec_spd,
      satuanstandar: element2.satuanstandar,
      nilaikonversi: element2.nilaikonversi,
      satuanstandarfk: element2.satuanstandarfk,
      namaprodukuse: element2.namaprodukuse,
    })




  }
  
  return filtered;


})

const clearSelection = () => {
  var arrobj = Object.keys(item.produkCeklis)
  for (let x = 0; x < arrobj.length; x++) {
    const element2 = arrobj[x];
    item.produkCeklis[element2] = false
  }
  getSelected()
}
const clearSelectionItem = (select: any) => {
  var arrobj = Object.keys(item.produkCeklis)
  for (let x = 0; x < arrobj.length; x++) {
    const element2 = arrobj[x];
    if (element2 == select.id) {
      item.produkCeklis[element2] = false
    }
  }
  getSelected()
}
const fetchTindakan = (e: any) => {
  isLoading.value = true
  var filterSerch = ""
  var filterSerch2 = ""

  if (e != undefined) {
    filterSerch = e[0].nama,
      filterSerch2 = e[0].id
  }
  useApi().get(
      `farmasi/get-produkdetail-ceklis2?ruanganfk=${item.ruanganTujuan}&ruanganPemesanfk=${item.ruanganPengirim}&limit=10&namaproduk=${filterSerch}&kodeproduk=${filterSerch2}`).then((response: any) => {
      isLoading.value = false
      d_ProdukDef.value = response
      d_Produk.value = response
    })
}
const filterProdukna = (e: any) => {
  var filteredDatana: any = [];
  if (item.namaprodukserch != undefined) {
    filteredDatana.push({
      nama: item.namaprodukserch,
      id: ""
    })
  } else if (item.kodeprodukserch != undefined) {
    filteredDatana.push({
      nama: "",
      id: item.kodeprodukserch
    })
  } else if (item.kodeprodukserch != undefined && item.namaprodukserch != undefined) {
    filteredDatana.push({
      nama: item.namaprodukserch,
      id: item.kodeprodukserch
    })
  } else {
    filteredDatana.push({
      nama: "",
      id: ""
    })
  }
  fetchTindakan(filteredDatana)
}
const getSelisih = (event: any) => {
  let { data, field, newValue } = event
  // data.jumlah
  data.jumlah = newValue;
  for (let x = 0; x < data2.value.length; x++) {
    const element = data2.value[x];
    if (element.no == data.no) {
      element.jumlah = newValue
    }
  }
  // console.log(event)
}

async function fetchDataOrder() {
  isLoading.value = true
  let limit: any = 100
  let offset: any = 1
  offset = offset * limit - limit
  let rows: any = 100
  let nmProduk = ''
  let kdProduk = ''
  let jnsProduk = ''
  let idRuangan = ''
  let idRuanganTujuan = ''
  let idAsalProduk = ''
  if (!item.ruanganTujuan) {
    H.alert('error', 'Ruangan Tujuan Tidak Boleh Kosong')
    isLoading.value = false
    return
  }
  if (!item.ruanganPengirim) {
    H.alert('error', 'Ruangan Pengirim Tidak Boleh Kosong')
    isLoading.value = false
    return
  }
  clearInput()
  if (item.namaprodukserch) nmProduk = '&namaproduk=' + item.namaprodukserch
  if (item.kodeprodukserch) kdProduk = '&kodeproduk=' + item.kodeprodukserch
  if (item.ruanganPengirim) idRuangan = '&idruangan=' + item.ruanganPengirim
  if (item.ruanganTujuan) idRuanganTujuan = '&idruangantujuan=' + item.ruanganTujuan
  if (item.status) status = '&status=' + item.status.value

  const response = await useApi().get(
    '/logistik/stok-ruangan-grid-order?offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    nmProduk + idRuangan + kdProduk + status + idRuanganTujuan
  )
  isLoading.value = false
  
  dataSourceOrder.value = response.data
  console.log('asu', enhancedDataSource);

}

const enhancedDataSource = computed(() => {
  if (item.ruanganPengirim == 326 || item.ruanganPengirim == 327 || item.ruanganPengirim == 329 || item.ruanganPengirim == 324 || item.ruanganPengirim == 328 || item.ruanganPengirim == 325) 
  {
      const filteredData = dataSourceOrder.value
      .map((item) => {
        const harganetto = Math.ceil(item.latest_harganetto1);
        const avgPemakaian = Math.ceil(item.pengeluaran / 30); 
        const kebutuhan6bulan = Math.ceil(avgPemakaian * 7);  //kebutuhan 4 hari
        const rencanakebutuhan = Math.ceil(kebutuhan6bulan - item.total); 
        const minstok = Math.ceil(avgPemakaian * 5);  
        const total = Math.ceil(item.total); //
        const maxstock = Math.ceil(minstok + (14 * avgPemakaian));
        const tingkatkecukupan = avgPemakaian > 0
          ? Math.ceil(item.total / avgPemakaian)
          : 0;
        const statusCito = tingkatkecukupan <= 2 ? 'Cito' : '-';

        return {
          ...item,
          harganetto:harganetto ? harganetto : 0,
          nilaikonversi: 1,
          total:total,
          avgpemakaian: avgPemakaian,
          kebutuhan6bulan: kebutuhan6bulan,
          rencanakebutuhan: rencanakebutuhan,
          minstok: minstok,
          maxstock: maxstock,
          tingkatkecukupan: tingkatkecukupan,
          statusCito: statusCito,
          isSelected:false
        };
      })
      .filter(item => item.total <= item.minstok); 
      filteredData.forEach((item, index) => {
      item.no = index + 1; 
    })
    return filteredData;
  }
  else 
  {
    const filteredData = dataSourceOrder.value
    .map((item) => {
      const harganetto = Math.ceil(item.latest_harganetto1);
      const avgPemakaian = Math.ceil(item.pengeluaran / 12); //per minggu
      const kebutuhan6bulan = Math.ceil(avgPemakaian * 5.7);  
      const rencanakebutuhan = Math.ceil(kebutuhan6bulan - item.total); //kebutuhan 1 bulan
      const minstok = Math.ceil(avgPemakaian * 1.5); 
      const total = Math.ceil(item.total); 
      const maxstock = Math.ceil(minstok + (5.7 * avgPemakaian)); 
      const tingkatkecukupan = avgPemakaian > 0
        ? Math.ceil((item.total / avgPemakaian) * 7) //Tingkat Kecukupan per hari
        : 0;
      const statusCito = tingkatkecukupan <= 2 ? 'Cito' : '-';

      return {
        ...item,
        harganetto:harganetto ? harganetto : 0,
        nilaikonversi: 1,
        total:total,
        avgpemakaian: avgPemakaian,
        kebutuhan6bulan: kebutuhan6bulan,
        rencanakebutuhan: rencanakebutuhan,
        minstok: minstok,
        maxstock: maxstock,
        tingkatkecukupan: tingkatkecukupan,
        statusCito: statusCito,
        isSelected:false
      };
    })
    .filter(item => item.total <= item.minstok); 
    filteredData.forEach((item, index) => {
    item.no = index + 1; 
  })
    return filteredData;
  } 
});


const onPasienSelected = () => {
  
  if (enhancedDataSource.value.length > 0) {
    let arrobj = enhancedDataSource.value;

    arrobj.forEach((element) => {

      let produkfk = parseInt(element.produkfk);

      if (item.produkCeklis[produkfk]) {
        listChecked.value = listChecked.value.filter(el => el.produkfk !== produkfk);
        console.log('ak',listChecked.value)
        listChecked.value.push({
          hargajual: String(element.harganetto),
          stock: String(element.jumlah),
          harganetto: String(element.harganetto),
          ruanganfk: item.ruanganPengirim.id ? item.ruanganPengirim.id : element.ruanganfk,
          produkfk: element.produkfk,
          namaproduk: element.namaproduk,
          kdproduk: element.kdproduk,
          avgpemakaian: element.avgpemakaian,
          pengeluaran: element.pengeluaran,
          maxstock: element.maxstock,
          pengeluaran: element.pengeluaran,
          minstok: element.minstok,
          stokpengirim: element.stokpengirim,
          productname: element.namaproduk,
          nilaikonversi: element.nilaiKonversi,
          satuanstandarfk: element.satuanstandarfk,
          satuanstandar: element.satuanstandar,
          satuanviewfk: element.satuanstandarfk,
          satuanview: element.satuanstandar,
          jmlstok: String(element.total),
          jumlah: element.total,
          qtyorder: element.rencanakebutuhan,
          hargasatuan: String(element.harga),
          hargadiscount: 0,
          total: 0,
        });

      } else {
        let index = listChecked.value.findIndex(el => el.produkfk === produkfk);
        let index2 = dataSource.value.findIndex(el => el.produkfk === produkfk);
        if (index !== -1) {
          listChecked.value.splice(index, 1);
          item.produkCeklis[produkfk] = false;
        }
        if (index2 !== -1) {
          dataSource.value.splice(index, 1);
          item.produkCeklis[produkfk] = false;
        }

      }
    });

  }
}

function formatValue(value) {
  return value < 0 ? Math.abs(value) : value;
}

function getTextColor(value) {
  return value < 0 ? { color: 'red' } : { color: 'black' };
}
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout .form-outer {
  border: 1px solid transparent;
  background-color: transparent;
}

.btn-orderBarang {
  padding: 8px 22px !important;
  height: 38px !important;
  line-height: 1.1 !important;
  font-size: 0.95rem !important;
  font-family: var(--font) !important;
  transition: all 0.3s !important;
}
</style>
