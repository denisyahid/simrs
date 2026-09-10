<template>
<ConfirmDialog/>
<VCard>
  <div class="form-layout">
    <div class="form-outer">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>PANJAR</h3>
          </div>
        </div>
      </div>
          
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VButton type="button" icon="feather:plus" :loading="isLoading" color="primary" raised class="is-rounded"
                    @click="TambahPanjarVerif()" > Tambah Panjar
                </VButton>
              </div>
              
              
            </div>
          </div>
          <div class="column is-8">
            <div class="columns column">
              <h3 class="title is-5 mb-2 mr-1">Daftar Panjar </h3> <span> ( {{ totalPanjar }}
                Totals)</span>
            </div>
            <div class="columns all-projects m-3 mt-0">
              <div class="columns is-multiline  projects-card-grid">
                <div class="column is-12">
                  <div class="flex-list-inner" v-if="dataSourcePanjarPengembalian.length === 0">
                    <VCard>
                      <VPlaceholderSection title="Not found" subtitle="There is no data that match your query." class="my-6">
                        <template #image>
                          <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                          <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                        </template>
                      </VPlaceholderSection>
                    </VCard>
                  </div>
                  <div v-else-if="dataSourcePanjarPengembalian.length > 0">
                    <div class="grid-item mb-4" v-for="(items, i) in dataSourcePanjarPengembalian" :key="items.id">
                      <div class="top-section">
                        <div class="body">
                          <div class="title-wrap">
                            <div class="columns is-multiline">
                              <div class="column is-6">
                                <h1 style="font-weight: bold">PANJAR</h1>
                                <h3>{{ items.nopanjar }}</h3>
                                <!-- <p>{{ moment(items.tglpanjar).format('DD-MM-YYYY')}}</p> -->
                                <p>Jumlah Panjar : {{H.formatRp(items.jumlah,'Rp. ')}}</p>
                                <div class="columns">
                                  <div class="column">
                                    <h4 class="heading">Kegiatan</h4>
                                    <p class="fs-075">{{ items.subsubkegiatan }}</p>
                                    <!-- <p class="fs-075">Tanggal : {{ items.tglregistrasi }}</p> -->
                                  </div>
                                  <div class="column">
                                    <h4 class="heading">Uraian</h4>
                                    <p class="fs-075">{{ items.uraian }}</p>
                                    <!-- <p class="fs-075">Tanggal : {{ items.tglregistrasi }}</p> -->
                                  </div>
                                  <div class="column">
                                    <h4 class="heading">Sumber</h4>
                                    <p class="fs-075">{{ items.sumberpanjar }}</p>
                                    <!-- <p class="fs-075">Tanggal : {{ items.tglregistrasi }}</p> -->
                                  </div>
                                </div>
                              </div>
                              <div class="column is-5">
                                <h1 style="font-weight: bold">SPJ</h1>
                                <h3 style="font-weight: bold" v-if="items.norealisasi !== null">
                                    {{ items.norealisasi }}
                                </h3>
                                <h3 v-else>
                                    <br>
                                </h3>
                                <!-- <p>{{ moment(items.tglpanjar).format('DD-MM-YYYY')}}</p> -->
                                <p>Jumlah SPJ : {{H.formatRp(items.jumlahspj,'Rp. ')}}</p>
                                <div class="columns">
                                  <!-- <div class="column">
                                    <h4 class="heading">Total Pemakaian</h4>
                                    <p class="fs-075">{{ H.formatRp(items.totalpemakaian,'Rp. ') }}</p>
                                  </div> -->
                                  <div class="column">
                                    <h4 class="heading">Jumlah Pengembalian</h4>
                                    <p class="fs-075">{{ H.formatRp(items.jumlahpengembalian,'Rp. ') }}</p>
                                    <!-- <p class="fs-075">Tanggal : {{ items.tglregistrasi }}</p> -->
                                  </div>
                                  <div class="column">
                                    <h4 class="heading">Selisih</h4>
                                    <p class="fs-075">{{ H.formatRp(items.selisih,'Rp. ') }}</p>
                                    <!-- <p class="fs-075">Tanggal : {{ items.tglregistrasi }}</p> -->
                                  </div>
                                </div>
                              </div>
                              <div class="column is-1">
                                <VDropdown icon="feather:more-vertical" spaced right v-tooltip.bubble="'AKSI'">
                                  <template #content>
              
                                    <a role="menuitem" href="#" class="dropdown-item is-media" @click="editPanjar(items)" style="color: red">
                                      <div class="icon">
                                        <i class="iconify" data-icon="feather:trash-2" aria-hidden="true"></i>
                                      </div>
                                      <div class="meta">
                                        <span>Edit Panjar</span>
                                        <span>Edit Semua Data Panjar</span>
                                      </div>
                                    </a>
              
                                  </template>
                                </VDropdown>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="bottom-section">
                        <div class="foot-block">
                          <h4 class="heading">Action</h4>
                          <div class="developers">
                            <VButton type="button" icon="fas fa-undo" class="mr-3" color="primary" outlined :loading="isDetailSPJ"
                              raised @click="PengembalianPanjar(items)">
                              Pengembalian </VButton>
                              <VButton type="button" icon="fas fa-notes-medical" class="mr-3" color="warning" outlined
                              raised @click="hapusPanjar(items)">
                              Hapus </VButton>
                              
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                  :total-items="totalPanjar" :max-links-displayed="5" >
                    <template #before-pagination>
                    </template>
                    <template #before-navigation>
                      <VFlex class="mr-4 mt-1" column-gap="1rem">
                        <VField>
        
                        </VField>
                        <VField>
                          <VControl>
                            <div class="select is-rounded">
                              <select v-model="currentPage.limit">
                                <option :value="1">1 results per page</option>
                                <option :value="5">5 results per page</option>
                                <option :value="10">10 results per page</option>
                                <option :value="15">15 results per page</option>
                                <option :value="25">25 results per page</option>
                                <option :value="50">50 results per page</option>
                              </select>
                            </div>
                          </VControl>
                        </VField>
                      </VFlex>
                    </template>
                  </VFlexPagination>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-4">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VControl icon="feather:search">
                    <input v-model="item.qnama" v-on:keyup.enter="fetchData()" type="text" class="input is-rounded" :loading="isFilter"
                      placeholder="Filter" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <h3 class="title is-5 mb-2 mr-1">Filters </h3>
              </div>
              
              <div class="column is-12">
                <VField>
                  <VLabel>Tanggal Panjar</VLabel>
                  <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                    <VField addons>
                      <VControl icon="feather:calendar" class="is-rounded">
                      <VInput :value="inputValue.start" v-on="inputEvents.start" class="is-rounded"/>
                      </VControl>
                      <VControl>
                      <VButton static class="is-rounded"><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                      </VControl>
                      <VControl icon="feather:calendar" class="is-rounded">
                      <VInput :value="inputValue.end" v-on="inputEvents.end" class="is-rounded" />
                      </VControl>
                    </VField>
                    </template>
                </VDatePicker>
                </VField>
              </div>
              <div class="column is-12">
                <span><br></span>
                  <VButton type="button" icon="feather:search" :loading="isSearch" color="info" raised class="is-rounded"
                    @click="loadData()" > Search
                  </VButton>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</VCard>

<Dialog :loading="isLoadingDialog" :maximizable="true"  v-model:visible="popupPanjar" :style="{width: '100%'}" header="Entry Panjar" :modal="true" class="p-fluid">
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="columns is-multiline">
        <VCard>
          <div class="column is-2">
            <VDatePicker v-model="item.tglPanjar" color="green" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <span>Tanggal Panjar</span>
                  <VControl icon="feather:calendar">
                    <VInput
                      type="text"
                      placeholder="Pilih Tanggal"
                      :value="inputValue" class="is-rounded"
                      v-on="inputEvents"
                    />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField class="is-autocomplete-select">
                <span>Sub Kegiatan</span>
                <VControl icon="feather:search">
                  <Dropdown v-model="item.subkegiatanpanjar" :options="listSubKegiatanSPJ" :optionLabel="'keterangan'"
                  class="is-rounded" placeholder="Sub Kegiatan" :loading="isLoading" style="width: 100%;" :filter="true" @change="changeSubsubKegiatan($event)"
                  showClear  />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-autocomplete-select">
                <span>Sub Sub Kegiatan</span>
                <VControl icon="feather:search">
                  <Dropdown v-model="item.subsubkegiatanpanjar" :options="listSubSubKegiatanSPJ" :optionLabel="'keterangan'"
                  class="is-rounded" placeholder="Sub sub Kegiatan" style="width: 100%;" :filter="true" :loading="isLoading" @change="changeRekening($event)"
                  showClear />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-autocomplete-select">
                <span>Dari Rekening</span>
                <VControl icon="feather:search">
                <Dropdown v-model="item.darirekeningpanjar" :options="listMataAnggaranSPJ" :optionLabel="'keterangan'"
                    class="is-rounded" placeholder="Dari Rekening" style="width: 100%;" :filter="true" :loading="isLoading" @change="changeCboPilihNoRekening($event)"
                    showClear />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-6">
              <VField>
                <span>Sisa Anggaran</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.sisaAnggaranSPJTxt" placeholder=""
                        class="is-rounded" disabled/>
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12">
            <span>Sumber Panjar</span>
            <div class="flex flex-wrap gap-3">
              <div v-for="category in categories" :key="category.key" class="flex align-items-center">
                <RadioButton v-model="item.sumberPanjar" :inputId="category.key" name="dynamic" :value="category.key" style="margin-left:10px" />
                <label :for="category.key" class="ml-2">{{ category.name }}</label>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <VField>
              <span>Uraian Panjar</span>
              <VTextarea
              class="is-rounded"
              rows="4"
              placeholder=""
              autocomplete="off"
              autocapitalize="off"
              spellcheck="true"
              v-model="item.uraianPanjar"
              ></VTextarea>
            </VField>
          </div>
          <div class="column is-6">
            <VField>
              <span>Jumlah Panjar</span>
              <VControl icon="feather:edit-3">
                <VInput type="text" v-model="item.jumlahpanjar" placeholder=""
                      class="is-rounded"/>
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>
      <div class="columns is-multiline">
        <div class="column is-6">
          <VCard>
              <h4><b>PPh 1:</b></h4>
              <div class="column is-12">
                  <VField class="is-autocomplete-select">
                      <span>Jenis PPh</span>
                      <VControl icon="feather:search">
                          <Dropdown v-model="item.jenispajak" :options="listJenisPajak" :optionLabel="'jenispajak'"
                              class="is-rounded" placeholder="Jenis PPh" :loading="isLoading" style="width: 100%;" :filter="true" @change="changeSubsubKegiatan($event)"
                              showClear  />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-12">
                  <VField>
                      <span>PPh</span>
                      <VControl icon="feather:edit-3">
                          <VInput type="text" v-model="item.pphpanjar" placeholder=""
                              class="is-rounded"/>
                      </VControl>
                  </VField>
              </div>
              <div class="column is-12">
                  <VField>
                      <span>Id Billing</span>
                      <VControl icon="feather:edit-3">
                          <VInput type="text" v-model="item.idbillpph" placeholder=""
                              class="is-rounded"/>
                      </VControl>
                  </VField>
              </div>
              <div class="column is-12">
                  <VField>
                      <span>NTPN</span>
                      <VControl icon="feather:edit-3">
                          <VInput type="text" v-model="item.ntpnpph" placeholder=""
                              class="is-rounded"/>
                      </VControl>
                  </VField>
              </div>
          </VCard>
        </div>
        <div class="column is-6">
          <VCard>
              <h4><b>PPh 2:</b></h4>
              <div class="column is-12">
                  <VField class="is-autocomplete-select">
                      <span>Jenis PPh</span>
                      <VControl icon="feather:search">
                          <Dropdown v-model="item.jenispajak2" :options="listJenisPajak" :optionLabel="'jenispajak'"
                              class="is-rounded" placeholder="Jenis PPh" :loading="isLoading" style="width: 100%;" :filter="true" @change="changeSubsubKegiatan($event)"
                              showClear  />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-12">
                  <VField>
                      <span>PPh</span>
                      <VControl icon="feather:edit-3">
                          <VInput type="text" v-model="item.pphpanjar2" placeholder=""
                              class="is-rounded"/>
                      </VControl>
                  </VField>
              </div>
              <div class="column is-12">
                  <VField>
                      <span>Id Billing</span>
                      <VControl icon="feather:edit-3">
                          <VInput type="text" v-model="item.idbillpph2" placeholder=""
                              class="is-rounded"/>
                      </VControl>
                  </VField>
              </div>
              <div class="column is-12">
                  <VField>
                      <span>NTPN</span>
                      <VControl icon="feather:edit-3">
                          <VInput type="text" v-model="item.ntpnpph2" placeholder=""
                              class="is-rounded"/>
                      </VControl>
                  </VField>
              </div>
          </VCard>
        </div>
        <div class="column is-12">
          <VCard>
              <h4><b>PPN:</b></h4>
              
              <div class="column is-12">
                  <VField>
                      <span>PPN</span>
                      <VControl icon="feather:edit-3">
                          <VInput type="text" v-model="item.ppnpanjar" placeholder=""
                              class="is-rounded"/>
                      </VControl>
                  </VField>
              </div>
              <div class="column is-12">
                  <VField>
                      <span>Id Billing</span>
                      <VControl icon="feather:edit-3">
                          <VInput type="text" v-model="item.idbill" placeholder=""
                              class="is-rounded"/>
                      </VControl>
                  </VField>
              </div>
              <div class="column is-12">
                  <VField>
                      <span>NTPN</span>
                      <VControl icon="feather:edit-3">
                          <VInput type="text" v-model="item.ntpn" placeholder=""
                              class="is-rounded"/>
                      </VControl>
                  </VField>
              </div>
          </VCard>
        </div>
      </div>
      <div class="column is-3">
        <span><br></span>
        <VButton type="button" icon="feather:save" :loading="isSimpanPanjar" color="danger" raised class="is-rounded"
          @click="SimpanPanjar()" > Simpan
        </VButton>
      </div>
    </div>
  </div>
</Dialog>
<Dialog  :maximizable="true"  v-model:visible="popupPengembalianSPJ" :style="{width: '100%'}" header="Pengembalian Panjar" :modal="true" class="p-fluid">
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="columns is-multiline">
        <div class="column is-2">
          <VDatePicker v-model="item.tglPanjar" color="green" trim-weeks>
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <span>Tanggal Panjar</span>
                <VControl icon="feather:calendar">
                  <VInput disabled
                    type="text"
                    placeholder="Pilih Tanggal"
                    :value="inputValue" class="is-rounded"
                    v-on="inputEvents"
                  />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-2">
          <VField>
            <span>Sumber Panjar</span>
            <VControl icon="feather:edit-3">
              <VInput type="text" v-model="item.sumberpengembalian" placeholder=""
                  class="is-rounded" disabled/>
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField>
            <span>Sub Kegiatan</span>
            <VControl icon="feather:edit-3">
              <VInput type="text" v-model="item.subkegiatanpengembaliann" placeholder=""
                  class="is-rounded" disabled/>
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField>
            <span>Sub Sub Kegiatan</span>
            <VControl icon="feather:edit-3">
              <VInput type="text" v-model="item.subsubkegiatanpengembaliann" placeholder=""
                  class="is-rounded" disabled/>
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField>
            <span>Dari Rekening</span>
            <VControl icon="feather:edit-3">
              <VInput type="text" v-model="item.rekeningpengembaliann" placeholder=""
                  class="is-rounded" disabled/>
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VDatePicker v-model="item.tglPengembalian" color="green" trim-weeks>
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <span>Tanggal Pengembalian</span>
                <VControl icon="feather:calendar">
                  <VInput
                    type="text"
                    placeholder="Pilih Tanggal"
                    :value="inputValue" class="is-rounded"
                    v-on="inputEvents"
                  />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-4">
          <VField>
            <span>Jumlah Pengembalian</span>
            <VControl icon="feather:edit-3">
              <VInput type="text" v-model="item.jumlahpengembalianpanjar" placeholder=""
                  class="is-rounded"/>
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <VField>
            <span>Keterangan</span>
              <VTextarea
              class="is-rounded"
              rows="4"
              placeholder=""
              autocomplete="off"
              autocapitalize="off"
              spellcheck="true"
              v-model="item.keteranganpengembalian"
            ></VTextarea>
          </VField>
        </div>
      </div>
    </div>
  </div>
  <div class="column is-3">
    <span><br></span>
    <VButton type="button" icon="feather:save" :loading="isSimpanPanjar" color="danger" raised class="is-rounded"
      @click="SimpanPengembalianPanjar()" > Simpan
    </VButton>
  </div>
</Dialog>
</template>

<script setup lang="ts">
import Textarea from 'primevue/textarea';
import { ref, computed, reactive, watch } from 'vue'
import { useHead } from '@vueuse/head'
import Card from 'primevue/card';
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
import { useWindowScroll } from '@vueuse/core'
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';
import moment from 'moment';
import RadioButton from 'primevue/radiobutton';
const router = useRouter()
const route = useRoute()

const confirm = useConfirm();
useHead({
  title: 'Panjar - ' + import.meta.env.VITE_PROJECT,
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
let popupPanjar: any = ref(false)
let popupPengembalianSPJ: any = ref(false)
let listJenisPajak: any = ref([])
let listSubSubKegiatanSPJ: any = ref([])
let listSubKegiatanSPJ: any = ref([])
let listMataAnggaranSPJ: any = ref([])

let dataSourcePanjarPengembalian: any = ref([])
let dataSourcePengembalian: any = ref([])
let isSimpanPanjar: any = ref(false)
let isSearch: any = ref(false)
let totalPanjar: any = ref(0)
let isLoadingPanjar: any = ref(false)

var norecpanjar: any = ref('')
var norecpengembalian: any = ref('')
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = reactive(
  {
    filterTgl: reactive({start: moment(new Date()).format('01/01/YYYY'),end: new Date(),}),
    tglPanjar:new Date()
  })
  
const currentPage: any = ref({
  limit: 5,
  rows: 50
})


currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})

watch(currentPage.value, () => {
  loadData()
})
let categories: any = reactive([
    {key: 'Bank', name:'Bank'},
    {key: 'Tunai', name:'Tunai'},
])
async function TambahPanjarVerif() {
    norecpanjar.value = ''
    popupPanjar.value = true
}
loadCombo()
loadData()
async function loadData() {
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit

  isSearch.value = true
  await useApi().get(`perencanaan/get-data-panjar?tglAwal=${moment(item.filterTgl.start).format('YYYY-MM-DD')}&tglAkhir=${moment(item.filterTgl.end).format('YYYY-MM-DD')}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}`).then((response)=>{
    isSearch.value = false
    
    dataSourcePanjarPengembalian.value = response.data
    totalPanjar.value = response.total
    route.query.page = '1'
  })
}
async function loadCombo() {
  await useApi().get(
      `anggaran/get-combo`
  ).then((response) => {
      // d_listTahapKegiatan.value = response.tahap
      // d_listJenisBelanja.value = response.jenisbelanja
      // d_listDiv.value = response.kelompokanggaran
      listJenisPajak.value = response.pph
  })

  await useApi().get('anggaran/get-data-setting-anggaran').then((response) => {
      listSubKegiatanSPJ.value = response.kegiatancombo
      item.tahap = response.data[0].objecttahapaktivfk
  })
}

const changeSubsubKegiatan = async (filter: any) => {
    listSubSubKegiatanSPJ.value = item.subkegiatanpanjar.detail
}

const changeRekening = async (filter: any) => {
    listMataAnggaranSPJ.value = item.subsubkegiatanpanjar.detail
}
const changeCboPilihNoRekening = async(filter: any)=> {
    await useApi().get('perencanaan/get-total-mata-anggaran-keterangan?tahap='+ item.tahap + '&rekening='+item.darirekeningpanjar.kodemataanggaran+"&subsubkegiatan="+ item.subsubkegiatanpanjar.id).then((response)=>{
        item.sisaAnggaranSPJ = parseFloat(response.data[0].totalketerangan) - parseFloat(response.detail[0].totalrealisasidetail)
        item.sisaAnggaranSPJTxt = H.formatRp(item.sisaAnggaranSPJ,'Rp. ')
        // listItemRincian.value = response.rincianbelanja
    })
}
const SimpanPanjar = async() =>{
  isSimpanPanjar.value = true
  var idbill = null;
  if(item.idbill != undefined){
      idbill = item.idbill
  }

  var ntpn = null;
  if(item.ntpn != undefined){
      ntpn = item.ntpn
  }

  var idbillpph = null;
  if(item.idbillpph != undefined){
      idbillpph = item.idbillpph
  }

  var ntpnpph = null;
  if(item.ntpnpph != undefined){
      ntpnpph = item.ntpnpph
  }

  var jenispph = null;
  if(item.jenispajak != undefined){
      jenispph = item.jenispajak.id
  }

  var pphpanjar = null;
  if(item.pphpanjar != undefined){
      pphpanjar = item.pphpanjar
  }

  var ppnpanjar = null;
  if(item.ppnpanjar != undefined){
      ppnpanjar = item.ppnpanjar
  }

  var idbillpph2 = null;
  if(item.idbillpph2 != undefined){
      idbillpph2 = item.idbillpph2
  }

  var ntpnpph2 = null;
  if(item.ntpnpph2 != undefined){
      ntpnpph2 = item.ntpnpph2
  }

  var jenispph2 = null;
  if(item.jenispajak2 != undefined){
      jenispph2 = item.jenispajak2.id
  }

  var pphpanjar2 = null;
  if(item.pphpanjar2 != undefined){
      pphpanjar2 = item.pphpanjar2
  }

  var objSave =
  {
    norec: norecpanjar.value,
    nopanjar: item.nopanjar,
    tglPanjar: moment(item.tglPanjar).format('YYYY-MM-DD'),
    sumberPanjar: item.sumberPanjar,
    uraianPanjar: item.uraianPanjar,
    jumlahpanjar: item.jumlahpanjar,
    idbill: idbill,
    ntpn: ntpn,
    idbillpph: idbillpph,
    ntpnpph: ntpnpph,
    objectjenispphfk: jenispph,
    pph: pphpanjar,
    ppn: ppnpanjar,
    idbillpph2: idbillpph2,
    ntpnpph2: ntpnpph2,
    objectjenispphfk2: jenispph2,
    pph2: pphpanjar2,
    subkegiatan: item.subkegiatanpanjar.id,
    subsusbkegiatan: item.subsubkegiatanpanjar.id,
    mataanggaran: item.darirekeningpanjar.kodemataanggaran,
  }
  await useApi().post('perencanaan/save-panjar', objSave).then((response)=>{
    isSimpanPanjar.value = false
    loadData()
    clear()
    popupPanjar.value = false
  }, (error)=>{
    isSimpanPanjar.value = false
  })
    // medifirstService.post('perencanaan/save-panjar', objSave).then(function (e) {
    //     medifirstService.get("perencanaan/get-daftar-panjar"
    //     , true).then(function (dat) {
    //         $scope.isRouteLoading = false;
    //         var datas = dat.data.data
    //         //console.log(datas)
    //         for(var i = 0; i < dat.data.data.length; i++){
    //             dat.data.data[i].no = i+1
    //         }
    //         $scope.dataSourcePanjar = new kendo.data.DataSource({
    //             data: datas,
    //             pageSize: 150,
    //             total: datas.length,
    //             serverPaging: false, 
    //             schema: {
    //                 model: {
    //                     fields: {
    //                     }
    //                 }
    //             },
    //         });

    //         loadDataGrid()
            
    //     });
    // }, function (error) {

    // })
          
    //   }
}
async function clear(){
item.tglPanjar = undefined
norecpanjar.value = ''
item.subsubkegiatanpanjar = undefined
item.darirekeningpanjar = undefined
item.nopanjar = undefined
item.sumberPanjar = undefined
item.uraianPanjar = undefined
item.jumlahpanjar = undefined
item.sumberPanjar = undefined
item.idbill = undefined
item.ntpn = undefined
item.idbillpph = undefined
}
async function editPanjar(dataItem) {
  item.subsubkegiatanpanjar = {
      id:dataItem.idsubsubkegiatan,
      keterangan: dataItem.subsubkegiatan
  }
  item.darirekeningpanjar = {
      namamataanggaran:dataItem.namamataanggaran,
      kodemataanggaran:dataItem.kodemataanggaran
  }
  norecpanjar.value = dataItem.norec
  item.nopanjar = dataItem.nopanjar
  item.tglPanjar = new Date(dataItem.tglpanjar) 
  item.sumberPanjar = dataItem.sumberpanjar
  item.uraianPanjar=dataItem.uraian
  item.jumlahpanjar=dataItem.jumlah
  item.sumberPanjar = dataItem.sumberpanjar
  item.idbill = dataItem.idbill
  item.ntpn = dataItem.ntpn
  item.idbillpph = dataItem.idbillpph
  item.idbillpph2 = dataItem.idbillpph2
  item.ntpnpph = dataItem.ntpnpph
  item.ntpnpph2 = dataItem.ntpnpph2
  item.pphpanjar = dataItem.pph
  item.pphpanjar2 = dataItem.pph2
  item.ppnpanjar = dataItem.ppn
  item.jenispajak = {id: dataItem.idpajak, jenispajak: dataItem.jenispajak}
  item.jenispajak2 = {id: dataItem.idpajak2, jenispajak: dataItem.jenispajak2}
  item.statuspanjar = 1
  await useApi().get('anggaran/get-data-setting-anggaran').then((response) => {
    listSubKegiatanSPJ.value = response.kegiatancombo
    listSubKegiatanSPJ.value.forEach((element: any) => {
      if(element.kode == dataItem.kodesubkegiatan){
        item.subkegiatanpanjar = element
        listSubSubKegiatanSPJ.value = item.subkegiatanpanjar.detail
        listSubSubKegiatanSPJ.value.forEach((element2: any)=>{
          if(element2.kode == dataItem.kodesubsubkegiatan){
            item.subsubkegiatanpanjar = element2
            listMataAnggaranSPJ.value = item.subsubkegiatanpanjar.detail
            listMataAnggaranSPJ.value.forEach((element3: any)=>{
              if(element3.kodemataanggaran == dataItem.kodemataanggaran){
                item.darirekeningpanjar = element3
                changeCboPilihNoRekening(item.darirekeningpanjar)
              }
            })
          }
        })
      }
    });
  })
  popupPanjar.value = true
}
async function hapusPanjar(dataItem) {
  confirm.require({
  message: 'Yakin ingin menghapus data ?',
  header: 'Konfirmasi Hapus Data',
  icon: 'pi pi-info-circle',
  acceptClass: 'p-button-danger',
  accept: () => {
    var objSave = {
        data: dataItem.norec
    }
    useApi().post('perencanaan/hapus-panjar',objSave).then((response)=>{
      loadData()
    })
  },
  reject: () => {
      loadData()
      isLoading.value = false
  },
  })
  
}
const  PengembalianPanjar = async (dataItem) =>{

  await useApi().get("perencanaan/get-pengembalian-panjar?norec="+dataItem.norec).then((response) => {
      if(response.data.length > 0){
        var datas = response.data[0]
        norecpengembalian.value = datas.norec
        item.tglPengembalian = new Date(datas.tglpengembalian)
        item.jumlahpengembalianpanjar = datas.jumlahpengembalian
        item.keteranganpengembalian = datas.keterangan
      }
      // norecpengembalian.value = response.data.norec                

  })
  //console.log(dataItem)
  item.tglPanjar = dataItem.tglpanjar
  item.sumberpengembalian = dataItem.sumberpanjar
  item.subkegiatanpengembaliann = dataItem.subkegiatan
  item.subsubkegiatanpengembaliann = dataItem.subsubkegiatan
  item.rekeningpengembaliann = dataItem.kodemataanggaran
  item.norecpengembalian = dataItem.norec                

  item.subkegiatanpengembalian = dataItem.idsubkegiatan
  item.subsubkegiatanpengembalian = dataItem.idsubsubkegiatan
  item.rekeningpengembalian = dataItem.idmataanggaran
  popupPengembalianSPJ.value = true
  
  // // norecspj = dataItem.norecspj
}
const SimpanPengembalianPanjar = async () => {
  isSimpanPanjar.value = true
  var objSave =
  {
    norec: norecpengembalian.value,
    tglpengembalian: item.tglPengembalian,
    keterangan: item.keteranganpengembalian,
    jumlahpengembalian: item.jumlahpengembalianpanjar,
    norecpanjar: item.norecpengembalian,
    idsubkegiatan: item.subkegiatanpengembalian,
    idsubsubkegiatan:item.subsubkegiatanpengembalian,
    idmataanggaran: item.rekeningpengembalian,
  }
  await useApi().post('perencanaan/save-pengembalian', objSave).then((response)=>{
  isSimpanPanjar.value = false

    loadData()
  })
}
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.fs-075 {
  font-size: 0.9rem;
}


.is-navbar {
  .form-layout {
    margin-top: 30px;
  }
}

.form-layout {
  // max-width: 740px;
  margin: 0 auto;

  &.is-separate {
    // max-width: 1040px;

    .form-outer {
      background: none;
      border: none;

      .form-body {
        display: flex;

        .form-section {
          flex-grow: 2;
          padding: 10px;
          width: 50%;

          .form-section-inner {
            @include vuero-s-card;

            padding: 40px;

            &.has-padding-bottom {
              padding-bottom: 60px;
              height: 100%;
            }

            >h3 {
              font-family: var(--font-alt);
              font-size: 1.2rem;
              font-weight: 600;
              color: var(--dark-text);
              margin-bottom: 30px;
            }

            .columns {
              .column {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
              }
            }

            .radio-boxes {
              display: flex;
              justify-content: space-between;
              margin-left: -8px;
              margin-right: -8px;

              .radio-box {
                position: relative;
                width: calc(50% - 16px);
                margin: 8px;

                &:focus-within {
                  border-radius: 3px;
                  outline-offset: var(--accessibility-focus-outline-offset);
                  outline-width: var(--accessibility-focus-outline-width);
                  outline-style: var(--accessibility-focus-outline-style);
                  outline-color: var(--primary);
                }

                input {
                  position: absolute;
                  top: 0;
                  left: 0;
                  height: 100%;
                  width: 100%;
                  opacity: 0;
                  cursor: pointer;

                  &:checked {
                    +.radio-box-inner {
                      background: var(--primary);
                      border-color: var(--primary);
                      box-shadow: var(--primary-box-shadow);

                      .fee,
                      p {
                        color: var(--smoke-white);
                      }
                    }
                  }
                }

                .radio-box-inner {
                  background: var(--white);
                  border: 1px solid var(--fade-grey-dark-3);
                  text-align: center;
                  border-radius: var(--radius);
                  font-family: var(--font);
                  font-weight: 600;
                  font-size: 0.9rem;
                  transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                    height 0.3s, width 0.3s;
                  padding: 30px 20px;

                  .fee {
                    font-family: var(--font);
                    font-weight: 700;
                    color: var(--dark-text);
                    font-size: 2.4rem;
                    line-height: 1;

                    span {
                      &::after {
                        content: '$';
                        position: relative;
                        top: -10px;
                        font-size: 1.5rem;
                      }
                    }
                  }

                  p {
                    font-family: var(--font-alt);
                  }
                }
              }
            }

            .control {
              >p {
                padding-top: 12px;

                >span {
                  display: block;
                  font-size: 0.9rem;

                  span {
                    font-weight: 500;
                    color: var(--dark-text);
                  }
                }
              }
            }
          }

          .form-section-outer {
            .checkboxes {
              padding: 16px 0;

              .checkbox {
                padding: 0;
                font-size: 0.9rem;
              }
            }

            .button-wrap {
              .button {
                min-height: 60px;
                font-size: 1.05rem;
                font-weight: 600;
                font-family: var(--font-alt);
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .form-layout {
    &.is-separate {
      .form-outer {
        background: none !important;

        .form-body {
          .form-section {
            .form-section-inner {
              @include vuero-card--dark;

              >h3 {
                color: var(--dark-dark-text);
              }

              .radio-boxes {
                .radio-box {
                  input:checked+.radio-box-inner {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    .fee,
                    p {
                      color: var(--smoke-white);
                    }
                  }

                  .radio-box-inner {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);

                    .fee {
                      color: var(--dark-dark-text);
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;
          flex-direction: column;

          .form-section {
            width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;

          // flex-direction: column;

          .form-section {
            // width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

.all-projects {
  .all-projects-header {
    display: flex;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    margin-bottom: 1.5rem;

    .header-item {
      width: 25%;
      border-right: 1px solid var(--fade-grey-dark-3);

      &:last-child {
        border-right: none;
      }

      .item-inner {
        text-align: center;

        .lnil,
        .lnir {
          font-size: 2.2rem;
          margin-bottom: 6px;
          color: var(--primary);
        }

        span {
          display: block;
          font-family: var(--font);
          font-weight: 600;
          font-size: 1.4rem;
          color: var(--dark-text);
        }

        p {
          font-family: var(--font-alt);
        }
      }
    }
  }

  .projects-card-grid {
    .grid-item {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 220px;
      padding: 20px;
      background: var(--white);
      border: 1px solid var(--fade-grey-dark-3);
      border-radius: var(--radius-large);

      .top-section {
        .head {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 8px;

          h3 {
            font-size: 1rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
          }
        }

        .body {
          p {
            font-family: var(--font);
            color: var(--light-text);
          }
        }
      }

      .bottom-section {
        display: flex;

        .foot-block {
          margin-right: 30px;

          .heading {
            font-family: var(--font-alt);
            font-size: 0.75rem;
            color: var(--light-text-dark-22);
          }

          >p {
            padding-top: 5px;
          }

          .developers {
            display: flex;

            .v-avatar {
              margin-right: 6px;
            }
          }
        }
      }
    }
  }
}

.heading {
  font-family: var(--font-alt);
  font-size: 0.75rem;
  color: var(--light-text-dark-22);
}

.is-dark {
  .all-projects {
    .all-projects-header {
      background: var(--dark-sidebar-light-6);
      border-color: var(--dark-sidebar-light-12);

      .header-item {
        border-color: var(--dark-sidebar-light-18);

        span {
          color: var(--dark-dark-text);
        }

        i {
          color: var(--primary) !important;
        }
      }
    }

    .projects-card-grid {
      .grid-item {
        background: var(--dark-sidebar-light-6);
        border-color: var(--dark-sidebar-light-12);

        .top-section {
          .head {
            h3 {
              color: var(--dark-dark-text);
            }
          }
        }

        .bottom-section {
          .foot-block {
            .heading {
              color: var(--light-text-dark-12);
            }
          }
        }
      }
    }
  }
}
</style>