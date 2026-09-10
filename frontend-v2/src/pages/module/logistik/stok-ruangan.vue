<template>
  <section>
    <div class="column is-12">
      <VCard>
      <div class="tabs-wrapper" :class="['tab-naver']">
        <div class="tabs-inner">
          <div class="tabs is-boxed">
            <ul>
              <li v-for="(tab, key) in tabs" :key="key" :class="[activeValue === tab.value && 'is-active']">
                <slot name="tab-link" :active-value="activeValue" :tab="tab" :index="key" :toggle="toggle">
                  <a tabindex="0" @keydown.space.prevent="toggle1(tab.value)" @click="toggle1(tab.value)">
                    <VIcon v-if="tab.icon" :icon="tab.icon" />
                    <span>
                      <slot name="tab-link-label" :active-value="activeValue" :tab="tab" :index="key">
                        {{ tab.label }}
                      </slot>
                    </span>
                  </a>
                </slot>
              </li>
              <li v-if="sliderClass" class="tab-naver"></li>
            </ul>
          </div>
        </div>

        <div class="tab-content is-active">
          <Transition :name="'fade-fast'" mode="out-in">
            <slot name="tab" :active-value="activeValue"></slot>
          </Transition>
        </div>
      </div>

      </VCard>
      <VCard v-if="activeValue == 1">
        <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Stok Ruangan</h3>
    </div>
    <div class="column is-2" >
          <VButton color="primary" @click="exportExcelSR()" outlined icon="fas fa-file-excel">
                Export To Excel
          </VButton>
      </div>
    <div class="columns">
      <div class="column is-9">
        <DataTable :value="dataSourceSR" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
            v-model:selection="selectedRows"
            selectionMode="multiple"
          >
          <Column :headerStyle="{ width: '3em' }">
          <template #header>
            <input type="checkbox" :checked="allSelected" @change="toggleSelectAll" />
          </template>
          <template #body="slotProps">
            <input type="checkbox" v-model="slotProps.data.selected" />
          </template>
        </Column>
          <Column field="noterima" header="No"></Column>
          <Column field="namaproduk" header="Produk" :sortable="true"></Column>
          <Column field="kdsirs" header="Kode Produk"></Column>
          <Column field="asalproduk" header="Asal"></Column>
          <Column field="namaruangan" header="Ruangan"></Column>
          <Column field="tglkadaluarsa" header="Tgl Kadaluarsa"></Column>
          <Column field="nobatch" header="No Batch"></Column>
          <Column field="namarekanan" header="Supplier"></Column>
          <Column field="qtyproduk" header="Qty"></Column>
          <!-- <Column field="satuanstandar" header="Satuan"></Column> -->
          <Column field="harga" header="Harga">
            <template #body="slotProps">
              {{ formatRupiah(slotProps.data.harga) }}
            </template>
          </Column>

          <Column header="Total Harga">
            <template #body="slotProps">
              {{ formatRupiah(slotProps.data.qtyproduk * slotProps.data.harga) }}
            </template>
          </Column>
        </DataTable>
        <div class="mt-3">
      <strong>Total Qty:</strong> {{ totalQty }}
    </div>
    <div class="mt-3">
      <strong>Total Keseluruhan Harga:</strong> {{ formatRupiah (totalHarga) }}
    </div>
      </div>
      <div class="column is-3">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField>
              <VControl icon="feather:search">
                <input v-model="item.namaproduk" v-on:keyup.enter="filtersr()" type="text" class="input is-rounded"
                  placeholder="Filter produk..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1">Filters</h3>
          </div>
          <div class="column is-6">
            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              Clear All
            </a>
          </div>

          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Nama Produk</VLabel>
              <VControl icon="feather:search" :loading="isLoading">
                <AutoComplete v-model="item.nmProduk" :suggestions="listNamaProduk"
                  :optionLabel="'namaproduk'" :dropdown="true" :appendTo="'body'" @complete="fetchProduk($event)"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Produk" />
                <!-- <Multiselect mode="single" v-model="item.nmProduk" :options="listNamaProduk" placeholder="Pilih produk"
                  :searchable="true" /> -->
              </VControl>
            </VField>
          </div>

          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Asal Produk</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.asalProduk" :options="listAsalProduk" placeholder="Pilih asal"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Ruangan</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.ruangan" :options="listRuanganStok" placeholder="Pilih ruangan"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="filtersr()" :loading="isLoading" type="button" icon="feather:search" class="is-fullwidth mr-3"
              color="info" raised>
              Apply Filters
            </VButton>
          </div>
        </div>
      </div>
    </div>
      </VCard>
      <VCard v-if="activeValue == 3">
          <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Adjustment Produk</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4 pt-4" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource" :loading="isLoading" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4 pt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2" style="margin-top:23px">
                                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                                    Export To Excel
                                </VButton>
                            </div>
                            <div class="column is-6 p-0 ml-5" style="margin-top: 3rem;">
                             
                                <!-- <VControl raw subcontrol>
                                    <VCheckbox v-model="item.checkisKosong" class="p-0" :label="'Stok Kosong'"
                                        color="warning" square />
                                </VControl> -->
                          </div>
                            <div class="column is-10">
                                <div class="columns is-multiline" style="justify-content: right;">
                                  <div class="column is-2">
                                    <VField class="is-rounded-select is-autocomplete-select">
                                      <VLabelText>Jenis Barang</VLabelText>
                                      <VControl icon="feather:search">
                                        <!-- <Multiselect mode="single" v-model="item.detailjenisbarang" :options="listdetailjenisbarang"
                                          placeholder="Pilih data" :searchable="true" /> -->
                                          <Dropdown v-model="item.detailjenisbarang" :options="listdetailjenisbarang"
                                                    :optionLabel="'label'" placeholder="Jenis Barang" :optionValue="'value'"
                                                    style="width: 100%;" :filter="true" appendTo="body" showClear />
                                      </VControl>
                                    </VField>
                                  </div>
                                    <div class="column is-2">
                                        <VField class=" is-rounded-select is-autocomplete-select">
                                            <VLabel>Asal Produk</VLabel>
                                            <VControl icon="feather:search" class="prime-auto">
                                                <Dropdown v-model="item.asalProduk" :options="listAsalProduk"
                                                    :optionLabel="'label'" placeholder="Asal Produk" :optionValue="'value'"
                                                    style="width: 100%;" :filter="true" appendTo="body" showClear />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField class=" is-rounded-select is-autocomplete-select">
                                            <VLabel>Ruangan</VLabel>
                                            <VControl icon="feather:search" class="prime-auto">
                                                <Dropdown v-model="item.ruangan" :options="listRuanganStok"
                                                    :optionLabel="'label'" placeholder="Pilih Ruangan" :optionValue="'value'"
                                                    style="width: 100%;" :filter="true" appendTo="body" showClear />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                      <VField>
                                        <VLabel>Nama Produk</VLabel>
                                        <VControl icon="feather:search">
                                          <input v-model="item.namaproduk" v-on:keyup.enter="filter()" type="text" class="input"
                                            placeholder="Nama produk..." />
                                        </VControl>
                                      </VField>
                                    </div>
                                     <div class="column is-1 btn-search mt-5" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="filter()"
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
                   
                    <Column field="no" header="No"   />
                    <!-- <Column field="noterima" header="No Terima" /> -->
                    <Column field="kdsirs" header="Kode Produk" />
                    <!-- <Column field="'pr.kodeexternal'" header="KODE PRODUK INTERNAL" /> -->
                    <Column field="namaproduk" header="Produk" />
                    <Column field="satuanstandar" header="Satuan" />
                    <!-- <Column field="saldokeluar" header="PEMAKAIAN (6 BULAN)" /> -->
                    <!-- <Column field="saldokeluarAVG" header="AVG STOK /BULAN" /> -->
                    <!-- <Column field="saldokebutuhan" header="KEBUTUHAN (6 BULAN)" /> -->
                    <!-- <Column field="saldorencanakebutuhan" header="RENCANA KEBUTUHAN" /> -->
                    <Column field="detailjenisproduk" header="Jenis Produk" />                  
                    <Column field="asalproduk" header="Asal Produk" />
                    <!-- <Column field="nilaikonversi" header="Nilai Konversi" /> -->
                    <Column field="namaruangan" header="Ruangan" />
                    <Column field="tglkadaluarsa" header="Tgl Kadaluarsa" >
                       <template #body="slotProps"  >
                            {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
                        </template>
                    </Column>
                    <!-- <Column field="statused" header="STATUS KADALUARSA"> -->
                        <!-- <template #body="slotProps"> -->
                          <!-- <div class="product-name" v-if="slotProps.data.statused == 'SUDAH MENDEKATI'">
                            {{slotProps.data.statused}}
                          </div> -->
                          
                        <!-- </template> -->
                    <!-- </Column> -->
                    <!-- <Column field="stokminimum" header="Minimum Stok" /> -->
                    <!-- <Column field="stokmaximum" header="Maximum Stok" /> -->
                    <!-- <Column field="bufferstok" header="Buffer Stok" /> -->
                    <Column field="jumlah" header="Qty" />
                    <!-- <Column field="statusstokuse" header="STATUS STOK"> -->
                        <!-- <template #body="slotProps"> -->
                          <!-- <div class="product-name2" v-if="slotProps.data.statusstokuse == 'TIDAK'"> -->
                            <!-- {{slotProps.data.statusstokuse}} -->
                          <!-- </div> -->
                          
                        <!-- </template> -->
                    <!-- </Column> -->
                    
                    <Column field="harga" header="Harga" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.harga), 2), '') }}
                        </template>
                    </Column>

                    <Column header="#" style="text-align:center">
                       <template #body="slotProps">
                             <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle
                                outlined raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                            </VIconButton>
                             <OverlayPanel ref="op">
                              <VButtons>
                                <VButton color="info" outlined @click="showFormEd(selected)">
                                  <i class="fas fa-pen-square mr-2" aria-hidden="true"></i> Adjustment Tgl Kadaluarsa
                                </VButton>
                                <VButton color="danger" raised @click="showFormAJ(selected)" :loading="dataSource.loadDelete">
                                  <i class="fas fa-times-circle mr-2" aria-hidden="true"></i> Adjustment Stok
                                </VButton>
                              </VButtons>
                            </OverlayPanel>
                        </template>
                    </Column>
                </DataTable>
                <div class="column is-12">
                  <div class="content">
                    <div class="is-divider" data-content="Total Keseluruhan" />
                  </div>
                </div>

                <div class="columns is-multiline" style="justify-content: right;">
                  <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                      <div class="label-status primary">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                          <span class="ml-1">Total Harga</span>
                        </div>
                          <!-- <small>{{ item.subtotal }}</small> -->
                        <small class="text-bold-custom h-100">{{H.formatRp(item.totalAllharga, 'Rp.')}}</small>
                    </VCardCustom>
                    
                  </div>
                  <div class="column is-3">
                  <VCardCustom :style="'padding:5px 25px'">
                      <div class="label-status primary">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                          <span class="ml-1">TOTAL Stok</span>
                        </div>
                          <!-- <small>{{ item.subtotal }}</small> -->
                        <small class="text-bold-custom h-100">{{item.totalAllStok}}</small>
                    </VCardCustom>
                  </div>
                </div>   
      </VCard>
      <VCard v-if="activeValue == 2">
          <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Daftar Rekap Stok</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4 pt-4" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource1" :loading="isLoading" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4 pt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2" style="margin-top:23px">
                                <VButton color="primary" @click="exportExcel1()" outlined icon="fas fa-file-excel">
                                    Export To Excel
                                </VButton>
                            </div>
                            <div class="column is-10">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    
                                    <div class="column is-3">
                                      <VField>
                                        <VLabel>Nama Produk</VLabel>
                                        <VControl icon="feather:search">
                                          <input v-model="item.namaproduk1" v-on:keyup.enter="filter1()" type="text" class="input"
                                            placeholder="Nama produk..." />
                                        </VControl>
                                      </VField>
                                    </div>
                                     <div class="column is-1 btn-search mt-5" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="filter1()"
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
                    <Column field="no" header="NO" />
                    <Column field="kdproduk" header="KODE PRODUK" />
                    <Column field="namaproduk" header="PRODUK" />
                    <Column field="satuanstandar" header="SATUAN" />
                    <Column field="jumlah" header="QTY" />
                    <Column field="stokminimum" header="Minimum Stok" />
                    <Column field="stokmaximum" header="Maximum Stok" />
                    <Column field="bufferstok" header="Buffer Stok" />
                </DataTable>
                <div class="column is-12">
                  <div class="content">
                    <div class="is-divider" data-content="Total Keseluruhan" />
                  </div>
                </div>

                <div class="columns is-multiline" style="justify-content: right;">
                 
                  <div class="column is-3">
                  <VCardCustom :style="'padding:5px 25px'">
                      <div class="label-status primary">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                          <span class="ml-1">Total Stok</span>
                        </div>
                          <!-- <small>{{ item.subtotal }}</small> -->
                        <small class="text-bold-custom h-100">{{item.totalAllStok1}}</small>
                    </VCardCustom>
                  </div>
                </div>  
      </VCard>
    </div>
  </section>

  <Dialog v-model:visible="modalInputED" modal header="Input Tanggal Kadaluarsa" :style="{ width: '20vw' }">
    <div class="column is-12">
      <VDatePicker v-model="item.tglkadaluarsa" color="green" trim-weeks>
          <template #default="{ inputValue, inputEvents }">
              <VField>
                  <VLabel>Tanggal Kadaluarsa</VLabel>
                  <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Pilih Tanggal" class="is-rounded" :value="inputValue" v-on="inputEvents" />
                  </VControl>
              </VField>
          </template>
      </VDatePicker>
    </div>
    <div class="column is-12">
         <VField label="Harga Real" class="required-vfield" style="overflow: hidden;">
            <VControl >
              <input v-model="item.hargareal" class="input" />
            </VControl>
        </VField>
      </div>
      <div class="column is-12  ">
         <VField label="Harga adjustment" class="required-vfield" style="overflow: hidden;">
            <VControl >
              <input v-model="item.hargaadjustment" class="input" />
            </VControl>
        </VField>
      </div>
    <template #footer>
      <VButton raised class="mr-3" @click="modalInputED = false">Batal</VButton>
      <VButton icon="feather:save" color="primary" raised @click="updateEd(selected)" :loading="loadSave">Update</VButton>
    </template>
  </Dialog>

  <Dialog v-model:visible="modalInputAj" modal header="Adjustment Stok" :style="{ width: '38vw' }">
    <div class="columns is-multiline">
      <div class="column is-3">
         <VField label="ID Produk" class="required-vfield">
            <VControl >
              <input v-model="item.idproduk" class="input" />
            </VControl>
        </VField>
      </div>
      <div class="column is-5">
         <VField label="Nama Produk" class="required-vfield">
            <VControl >
              <input v-model="item.namaproduk" class="input" />
            </VControl>
        </VField>
      </div>
      <div class="column is-2">
         <VField label="Qty Real" class="required-vfield">
            <VControl >
              <input v-model="item.qtyreal" class="input" />
            </VControl>
        </VField>
      </div>
      <div class="column is-2">
         <VField label="Qty adjustment" class="required-vfield" style="overflow: hidden;">
            <VControl >
              <input v-model="item.qtyadjustment" class="input" />
            </VControl>
        </VField>
      </div>
    
    </div>
    <Dialog v-model:visible="modalPassword" modal header="Konfirmasi Password" :style="{ width: '20vw' }">
    <div class="columns is-multiline">
      <div class="column is-12">
         <VField label="Password" class="required-vfield">
            <VControl >
              <input  type="password"  v-model="item.password" class="input" v-on:keyup.enter="save()" />
            </VControl>
        </VField>
      </div>
    </div>
    <template #footer>
      <VButton color="primary" raised @click="save()">Konfirmasi</VButton>
    </template>
  </Dialog>
  
  <template #footer>
      <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="modalPassword == false">
        Batal
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="btnLoad"
        @click="inputPassword()"> Simpan
      </VButton>
    </template>
  </Dialog>

  
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
import Button from 'primevue/button'
import OverlayPanel from 'primevue/overlaypanel';
import AutoComplete from 'primevue/autocomplete'
import { useCurrencyInput } from 'vue-currency-input'
import { useHead } from '@vueuse/head'
import Dialog from 'primevue/dialog';
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment from 'moment'
import * as XLSX from "xlsx";
import * as H from '/@src/utils/appHelper'
useHead({
  title: 'Transmedic - Stok Ruangan',
})
useViewWrapper().setFullWidth(true)

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
// useViewWrapper().setFullWidth(true)
// const item: any = ref({
//   periode: reactive({
//     start: new Date(),
//     end: new Date(),
//   })
// })

const emit = defineEmits<{
  (e: 'update:selected', value: string): void
}>()

const activeValue: any = ref(1)
let dataSourceSR: any = ref([]) 
let dataSource: any = ref([])
let dataSource1: any = ref([])
let isLoading: any = ref(false)
let loadData: any = ref(false)
let loadSave: any = ref(false)
let modalInputED: any = ref(false)
let modalInputAj: any = ref(false)
let modalPassword: any = ref(false)
let item: any = reactive({})
let listRuanganStok: any = ref([])
let listAsalProduk: any = ref([])
let listdetailjenisbarang: any = ref([])
const currentPage: any = ref({
  limit: 50,
  rows: 100,
})

const passwordStockAdjustman: any = ref('')
const d_jenisBarang: any = ref([])
const op = ref();
const selected: any = ref({})
let listNamaProduk: any = ref([])
const route = useRoute()
isLoading.value = false

async function fetchDataSR() {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let nmProduk = ''
  let idRuangan = ''
  let idAsalProduk = ''
  let kosong = ''
  if (item.namaproduk) nmProduk = '&namaproduk=' + item.namaproduk
  if (item.ruangan) idRuangan = '&idruangan=' + item.ruangan
  if (item.asalProduk) idAsalProduk = '&idasalproduk=' + item.asalProduk
  if (item.checkisKosong ) kosong = '&kosong=' + item.checkisKosong
  
  let produkfk = item.nmProduk ? `&idproduk=${item.nmProduk.id}` : '';

  const response = await useApi().get(
    '/logistik/stok-ruangan-grid-sr?offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    nmProduk + idRuangan + idAsalProduk + kosong + produkfk
  )
  isLoading.value = false
  for (let x = 0; x < response.length; x++) {
    const element = response[x];
    element.no = x + 1
  }
  dataSourceSR.value = response
  count()
}

async function fetchData() {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let nmProduk = ''
  let idRuangan = ''
  let iddetailjenisbarang = ''
  let idAsalProduk = ''
  let kosong = ''
  if (item.namaproduk) nmProduk = '&namaproduk=' + item.namaproduk
  if (item.ruangan) idRuangan = '&idruangan=' + item.ruangan
  if (item.asalProduk) idAsalProduk = '&idasalproduk=' + item.asalProduk
  if (item.detailjenisbarang) iddetailjenisbarang = '&iddetailjenisbarang=' + item.detailjenisbarang
  if (item.checkisKosong ) kosong = '&kosong=' + item.checkisKosong
  
  let produkfk = item.nmProduk ? `&idproduk=${item.nmProduk.id}` : '';

  const response = await useApi().get(
    '/logistik/stok-ruangan-grid?offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    nmProduk + idRuangan + idAsalProduk + kosong + iddetailjenisbarang + produkfk
  )
  isLoading.value = false
  for (let x = 0; x < response.length; x++) {
    const element = response[x];
    element.no = x + 1
  }
  dataSource.value = response
  count()
}

const fetchProduk = async (filter: any) => {
  let query = '';
  if (filter != undefined) {
    query = filter.query;
  }
  useApi().get(`/logistik/kartu-stok-cbo?name=${query}&limit=25`).then((response: any) => {
  // Menyatukan kdproduk dan namaproduk di dalam listNamaProduk
  listNamaProduk.value = response.produk.map((produk: any) => {
    return {
      ...produk,
      label: `${produk.kdproduk} - ${produk.namaproduk}`  // Menyatukan kdproduk dan namaproduk dalam satu label
    };
  });
});

}


async function fetchData1() {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let nmProduk = ''
  let idRuangan = ''
  let idAsalProduk = ''

  if (item.namaproduk1) nmProduk = '&namaproduk=' + item.namaproduk1

  const response = await useApi().get(
    '/logistik/stok-ruangan-grid-rekap?offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    nmProduk 
  )
  isLoading.value = false
  for (let x = 0; x < response.length; x++) {
    const element = response[x];
    element.no = x + 1
  }
  
  dataSource1.value = response
  count1()
}

const updateEd = async (e:any)=>{
  
  let objSave = {
    norec : e.norec_spd,
    hargareal : item.hargareal,
    hargaad : item.hargaadjustment,
    tglkadaluarsa : H.formatDate(item.tglkadaluarsa, 'YYYY-MM-DD')
  }
  // console.log(objSave)
  loadSave.value = true
  await useApi().post('logistik/update-tanggal-kadaluarsa',objSave).then((response)=>{
    fetchData()
    modalInputED.value = false
  }).catch((e)=>{
    modalInputED.value = false
  })
  loadSave.value = false
  
}

async function listDropdown() {
  await useApi().get(`/logistik/stok-ruangan-cbo`).then((response:any)=>{
    listRuanganStok.value = response.ruangan.map((e:any)=>{
      return {label:e.namaruangan, value:e.id}
    })
    listAsalProduk.value = response.asalproduk.map((e:any)=>{
      return {label:e.asalProduk, value:e.id}
    })
    listdetailjenisbarang.value = response.detailjenisproduk.map((e:any)=>{
      return {label:e.detailjenisproduk, value:e.id}
    })

    passwordStockAdjustman.value = response.password

  })
}

const fetchPegawai = async (filter: any) => {

  let data = filter.query ? filter.query : filter
  await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${data}&limit=10`
  ).then((response) => {
      d_Pegawai.value = response
  })
}

function clearFilter() {
  fetchData()
}
function filter() {
  if(!item.ruangan){
    H.alert('error','Ruangan Tidak Boleh Kosong')
    return 
  }
  fetchData()
}
function filtersr() {
  // if(!item.ruangan){
  //   H.alert('error','Ruangan Tidak Boleh Kosong')
  //   return 
  // }
  fetchDataSR()
}
function filter1() {
 
  fetchData1()
}


const toggle = (event: any,e:any) => {
    op.value.toggle(event);
    selected.value = e
}

const showFormEd = (e:any)=>{
  item.tglkadaluarsa = e.tglkadaluarsa
  item.hargareal = e.harga
  modalInputED.value = true
}
const showFormAJ = (e:any)=>{
  item.idproduk = e.produkfk
  item.namaproduk = e.namaproduk
  item.qtyreal = e.jumlah
  item.hargareal = e.harga
  item.qtyadjustment = e.jumlah
  item.hargaadjustment = e.harga
  modalInputAj.value = true
}

const inputPassword = (e:any)=>{
    console.log(e)
    modalPassword.value = true
}

const save = async ()=>{
  console.log(passwordStockAdjustman.value)
  // allz ditutup dulu 
  // if(!item.password){
  //   H.alert('error','Password Tidak Boleh Kosong')
  //   return
  // }
  // if(item.password != passwordStockAdjustman.value){
  //   H.alert('error','Password Tidak Sesuai !')
  //   return
  // }
  console.log(selected.value)
  let objSave = {
    norec_spd : selected.value.norec_spd,
    nostruterimafk : selected.value.nostrukterimafk,
    ruanganfk : selected.value.objectruanganfk,
    namaruangan : selected.value.namaruangan,
    namaproduk : selected.value.namaproduk,
    produkfk : selected.value.produkfk,
    qtyreal : item.qtyreal,
    qtyad : item.qtyadjustment,
    hargareal : item.hargareal,
    hargaad : item.hargaadjustment,
  }
    loadSave.value = true
    modalPassword.value = false
  await useApi().post('logistik/save-adjustman-stok',objSave).then((response)=>{
  loadSave.value = false
  fetchData()
  clear()
  }).catch((e)=>{
     loadSave.value = false
  })

  
}

const tableState = ref({
  first: 0,
  rows: 10
});

const calculateTotalQty = () => {
  return dataSourceSR.value
    .filter(row => row.selected)
    .reduce((total, row) => total + (parseFloat(row.qtyproduk) || 0), 0);
};

// Fungsi untuk menghitung total harga produk yang dipilih
const calculateTotalHarga = () => {
  return dataSourceSR.value
    .filter(row => row.selected)
    .reduce((total, row) => total + (parseFloat(row.qtyproduk) * parseFloat(row.harga) || 0), 0);
};

// Reactive values for totals
const totalQty = ref(calculateTotalQty());
const totalHarga = ref(calculateTotalHarga());

// Watcher untuk mendeteksi perubahan pada dataSourceSR
watch(dataSourceSR, () => {
  totalQty.value = calculateTotalQty();
  totalHarga.value = calculateTotalHarga();
}, { deep: true });

const allSelected = computed({
  get: () => dataSourceSR.value.every(row => row.selected),
  set: (value) => {
    dataSourceSR.value.forEach(row => {
      row.selected = value;
    });
  },
});

// Fungsi untuk toggle "Select All"
const toggleSelectAll = () => {
  allSelected.value = !allSelected.value;
};




// const totalQty = computed(() => {
//   // Filter the data to include only the currently displayed rows
//   const currentRows = dataSourceSR.value.slice(
//     tableState.value.first, 
//     tableState.value.first + currentPage.value.rows
//   ); 
//   // Sum the qtyproduk of visible rows
//   return currentRows.reduce((total, row) => total + parseFloat(row.qtyproduk) || 0, 0)
// });

// const totalHarga = computed(() => {
//   // Filter the data to include only the currently displayed rows
//   const currentRows = dataSourceSR.value.slice(
//     tableState.value.first, 
//     tableState.value.first + currentPage.value.rows
//   ); 
//   // Sum the qtyproduk of visible rows
//   return currentRows.reduce((total, row) => total + parseFloat(row.harga) * parseFloat(row.qtyproduk) || 0, 0)
// });


const formatRupiah = (value) => {
  if (!value) return 'Rp 0';
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};

const clear = ()=>{
  delete item.qtyadjustment
  delete item.password
  modalInputAj.value = false
}
const exportExcel = () => {
                    //  <Column field="no" header="NO" />
                    // <Column field="noterima" header="NO TERIMA" />
                    // <Column field="namaproduk" header="PRODUK" />
                    // <Column field="satuanstandar" header="SATUAN" />
                    // <Column field="asalproduk" header="ASAL PRODUK" />
                    // <Column field="namaruangan" header="RUANGAN" />
                    // <Column field="tglkadaluarsa" header="TGL KADALUARSA">
                    //    <template #body="slotProps">
                    //         {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
                    //     </template>
                    // </Column>
                    // <Column field="stokminimum" header="Minimum Stok" />
                    // <Column field="bufferstok" header="Buffer Stok" />
                    // <Column field="jumlah" header="QTY" />
                    // <Column field="harga" header="HARGA" style="text-align:right">
                    //     <template #body="slotProps">
                    //         {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.harga), 2), '') }}
                    //     </template>
                    // </Column>
    let judul = 'Stok Ruangan'
    let column = ['No ', 'NO TERIMA', 'PRODUK', 'SATUAN', 'ASAL PRODUK', 'Nilai Konversi','RUANGAN','TGL KADALUARSA','Minimum Stok','Maximum Stok','Buffer Stok','HARGA']
    const worksheet = XLSX.utils.aoa_to_sheet([
        [judul],
        [],
        column,
        ...dataSource.value.map((e: any) => [
           e.no,
           e.noterima,
           e.namaproduk,
           e.satuanstandar,
           e.asalproduk,
           e.detailjenisproduk,
           e.nilaikonversi,
           e.namaruangan,
           e.tglkadaluarsa,
           e.stokminimum,
           e.stokmaximum,
           e.bufferstok,
           e.harga
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
    H.saveAsExcelFile(excelBuffer, 'datastokruangan');
}
const exportExcelSR = () => {
                    //  <Column field="no" header="NO" />
                    // <Column field="noterima" header="NO TERIMA" />
                    // <Column field="namaproduk" header="PRODUK" />
                    // <Column field="satuanstandar" header="SATUAN" />
                    // <Column field="asalproduk" header="ASAL PRODUK" />
                    // <Column field="namaruangan" header="RUANGAN" />
                    // <Column field="tglkadaluarsa" header="TGL KADALUARSA">
                    //    <template #body="slotProps">
                    //         {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
                    //     </template>
                    // </Column>
                    // <Column field="stokminimum" header="Minimum Stok" />
                    // <Column field="bufferstok" header="Buffer Stok" />
                    // <Column field="jumlah" header="QTY" />
                    // <Column field="harga" header="HARGA" style="text-align:right">
                    //     <template #body="slotProps">
                    //         {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.harga), 2), '') }}
                    //     </template>
                    // </Column>
    let judul = 'Stok Ruangan'
    let column = ['No ', 'PRODUK', 'KODE PRODUK','ASAL' , 'RUANGAN', 'TANGGAL KADALUARSA', 'NO BATCH','SUPPLIER','QTY','HARGA','TOTAL HARGA']
    const worksheet = XLSX.utils.aoa_to_sheet([
        [judul],
        [],
        column,
        ...dataSourceSR.value.map((e: any) => [
           e.no,
           e.namaproduk,
           e.kdsirs,
           e.asalproduk,
           e.namaruangan,
           e.tglkadaluarsa,
           e.nobatch,
           e.namarekanan,
           e.qtyproduk,
           e.harga,
          e.qtyproduk * e.harga
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
    H.saveAsExcelFile(excelBuffer, 'datastokruangan');
}


const tabs: any = ref([
{ label: 'Stok Ruangan', value: 1, icon: 'fas fa-list' },
  { label: 'Adjustment Produk', value: 3, icon: 'fas fa-list' },
  // { label: 'Rekap Stok', value: 2, icon: 'fas fa-list' },
])
const selectedTabs: any = ref()
const props: any = defineProps({
  registrasi: {
    type: Object as PropType<any>,
  },
  pasien: {
    type: Object as PropType<any>,
  },
  selected: undefined,
  type: undefined,
  align: undefined,
  hilangkanStuck: false
})
const sliderClass = computed(() => {
  if (!props.slider) {
    return ''
  }

  if (props.type === 'rounded') {
    if (props.tabs.length === 2) {
      return 'is-triple-slider'
    }

    return ''
  }

  if (!props.type) {
    if (props.tabs.length === 2) {
      return 'is-squared is-triple-slider'
    }
  }

  return ''
})

function toggle1(value: string) {
  activeValue.value = value
}
watch(
  () => selectedTabs,
  (value) => {
    activeValue.value = value
  }
)
watch(activeValue, (value: any) => {
  emit('update:selected', value)
})
listDropdown()
const count = () => {

let totalsub = 0
let totalsubqty = 0

dataSource.value.forEach((element: any) => {
  totalsub = totalsub + (parseFloat(element.harga)*parseFloat(element.jumlah))
  totalsubqty = totalsubqty + parseFloat(element.jumlah)
})
item.totalAllharga = totalsub
item.totalAllStok = totalsubqty

}
const count1 = () => {

let totalsub1 = 0
let totalsubqty1 = 0

dataSource1.value.forEach((element: any) => {
  // totalsub1 = totalsub1 + (parseFloat(element.harga)*parseFloat(element.jumlah))
  totalsubqty1 = totalsubqty1 + parseFloat(element.jumlah)
})
// item.totalAllharga1 = totalsub1
item.totalAllStok1 = totalsubqty1

}
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
.product-name {
        color: #F0FFFF;
        background-color: red;
    }
    .product-name2 {
        color: #F0FFFF;
        background-color: red;
    }    
</style>
