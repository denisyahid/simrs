<template>
<ConfirmDialog/>
<Dialog v-model:visible="visible" header="Cetak Surat Permintaan Pembayaran" :style="{ width: '50rem' }" :position="position" :modal="true" :draggable="false">
    <div class="columns is-multiline">
        <div class="column is-3">
            <VButton type="button" icon="feather:arrow-right" :loading="isSearch" color="info" raised class="is-rounded"
                @click="cetakSurat(1)" > Surat Pengantar
            </VButton>
        </div>
        <div class="column is-3">
            <VButton type="button" icon="feather:arrow-right" :loading="isSearch" color="info" raised class="is-rounded"
                @click="cetakSurat(2)" > Surat Ringkasan
            </VButton>
        </div>
        <div class="column is-3">
            <VButton type="button" icon="feather:arrow-right" :loading="isSearch" color="info" raised class="is-rounded"
                @click="cetakSurat(3)" > Surat Rincian
            </VButton>
        </div>
    </div>
</Dialog>
<VCard>
    <div class="form-layout">
        <div class="form-outer">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Surat Permintaan Pembayaran</h3>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField>
                            <VLabel>Tanggal SPP</VLabel>
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
                    <div class="column is-6">
                        <span><br></span>
                        <VButton type="button" icon="feather:search" :loading="isSearch" color="info" raised class="is-rounded"
                            @click="loadData()" > Search
                        </VButton>
                    </div>
                </div>
            </div>
            
            <div class="column is-12">
            <DataTable paginator :rows="20" :value="dataSourceSPP" rowGroupMode="subheader"
      groupRowsBy="nospp"
      sortMode="single"
      sortField="nospp"
      :sortOrder="1" tableStyle="min-width: 50rem" v-model:filters="filters" scrollable scrollHeight="400px">
                <template #header>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <VButton type="button" icon="feather:plus" :loading="isLoading" color="primary" raised class="is-rounded"
                                @click="TambahSPP()" > Tambah
                            </VButton>
                        </div>
                        <div class="column is-6"></div>
                        <div class="column is-3">
                            <span class="p-input-icon-left">
                                <InputText v-model="filters['global'].value" placeholder="Search" class="is-rounded" />
                            </span>
                        </div>
                    </div>
                </template>
                <template #groupheader="slotProps">
                    <div class="flex align-items-center gap-2">
                        <span style="font-weight: bold"> Nomor SPP : {{ slotProps.data.nospp }}</span>
                    </div>
                </template>
                <Column :exportable="false" header="#" style="width:40px">
                    <template #body="slotProps">
                        <div class="columns">
                            <div class="column is-4">
                                <VButton type="button" icon="feather:plus" :loading="isSearch" color="success" raised class="is-rounded"
                                    @click="inputSPM(slotProps.data)" > Buat SPM
                                </VButton>
                            </div>
                            <div class="column is-3">
                                <VButton type="button" icon="feather:info" :loading="isSearch" color="info" raised class="is-rounded"
                                    @click="DetailSPP(slotProps.data)" > Detail
                                </VButton>
                            </div>
                            <div class="column is-3">
                                <VButton type="button" icon="feather:printer" :loading="isSearch" color="warning" raised class="is-rounded"
                                    @click="openPosition(slotProps.data)" > Cetak
                                </VButton>
                            </div>
                        </div>
                        
                        
                    </template>
                </Column>
                <Column field="jenisbukti" header="Jenis Bukti" style=""></Column>
                <Column field="asalproduk" header="Sumber Dana" style=""></Column>
                <Column field="status" header="Status" style=""></Column>
                <Column field="tglspp" header="Tgl SPP" style=""></Column>
                <Column field="bulan" header="Bulan" style=""></Column>
                <Column field="tglsah" header="Tgl Sah" style=""></Column>
                <Column field="nilaispp" header="Nilai SPP" style="">
                <template #body="slotProps">
                    <span>{{  H.formatRupiah(slotProps.data.nilaispp, '') }}</span>
                </template>
                </Column>
                <!-- <Column v-for="col of columnSPP" :field="col.field" :header="col.title" :style="`width:${col.width}`">
                    <template #body="slotProps">
                        <span>{{ col.template != undefined ?
                        H.formatRupiah(slotProps.data[col.field], '')
                        : slotProps.data[col.field] }}</span>
                    </template>
                </Column> -->
                <Column :exportable="false" header="#" style="min-width:15px">
                    <template #body="slotProps">
                        <VButton type="button" icon="feather:trash" :loading="isDeleteSPP" color="danger" raised class="is-rounded"
                            @click="DeleteSPP(slotProps.data)" > Hapus
                        </VButton>
                    </template>
                </Column>
            </DataTable>
            </div>
        </div>
    </div>
</VCard>

<Dialog :loading="isLoadingDialog" :maximizable="true" v-model:visible="popupSPP" :style="{width: '40%'}" header="SURAT PERMINTAAN PEMBAYARAN" :modal="true" class="p-fluid">
<!-- <div class="columns is-multiline">
    
</div> -->
    <div class="column is-12">
        <VField>
            <span>Nomor SPP</span>
            <VControl icon="feather:edit-3">
                <VInput type="text" v-model="item.nospp" placeholder=""
                    class="is-rounded" disabled/>
            </VControl>
        </VField>
    </div>
    <div class="column is-12">
        <VField class="is-autocomplete-select">
            <span>Sumber Dana</span>
            <VControl icon="feather:search" class="prime-auto-select">
                <Dropdown v-model="item.sumberDanaSPP" :options="listAsalProduk"
                    :optionLabel="'asalproduk'" class="is-rounded" placeholder="Pilih data"
                    style="width: 100%;" showClear :filter="false" :loading="isLoading"/>
            </VControl>
        </VField>
    </div>
    <div class="column is-12">
        <VField class="is-autocomplete-select">
            <span>Jenis Bukti</span>
            <VControl icon="feather:search" class="prime-auto-select">
                <Dropdown v-model="item.itemJenisBukti" :options="listJenisBukti"
                    :optionLabel="'jenisbukti'" class="is-rounded" placeholder="Pilih data"
                    style="width: 100%;" showClear :filter="false" :loading="isLoading"/>
            </VControl>
        </VField>
    </div>
    <div class="column is-12">
        <VField>
            <span>Dasar Pengeluaran</span>
            <VTextarea
            class="is-rounded"
            rows="4"
            placeholder=""
            autocomplete="off"
            autocapitalize="off"
            spellcheck="true"
            v-model="item.dasarPengeluaran"
            ></VTextarea>
        </VField>
    </div>
    <div class="column is-12">
        <span>Status</span>
        <div class="flex flex-wrap gap-6">
            <div v-for="category in categories" :key="category.key" class="flex align-items-center">
            <RadioButton v-model="item.radioStatusSPP" :inputId="category.key" name="dynamic" :value="category.key" style="margin-left:20px" />
            <label :for="category.key" class="ml-1">{{ category.name }}</label>
            </div>
        </div>
    </div>
    <div class="columns">
        <div class="column is-6">
        <VField>
            <span>Nomor SPD</span>
            <VControl icon="feather:edit-3">
                <VInput type="text" v-model="item.nospd" placeholder=""
                    class="is-rounded"/>
            </VControl>
            
        </VField>
        </div>
        <div class="column is-6">
            <span><br></span>
            <VButton type="button" icon="feather:plus" :loading="isSearch" color="success" raised class="is-rounded"
                @click="TambahSPD()" > Create
            </VButton>
        </div>
        
        
    </div>
    <div class="column is-12">
        <VField>
            <VDatePicker v-model="item.tglSPP" color="green" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <span>Tanggal SPP</span>
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
        </VField>
    </div>
    <div class="column is-12">
        <VField class="is-autocomplete-select">
            <span>Bulan</span>
            <VControl icon="feather:search" class="prime-auto-select">
                <Dropdown v-model="item.itemBulan" :options="listBulan"
                    :optionLabel="'bulan'" class="is-rounded" placeholder="Pilih data"
                    style="width: 100%;" showClear :filter="false" :loading="isLoading"/>
            </VControl>
        </VField>
    </div>
    <div class="column is-12">
        <VField>
            <span>Untuk Pengeluaran</span>
            <VTextarea
            class="is-rounded"
            rows="4"
            placeholder=""
            autocomplete="off"
            autocapitalize="off"
            spellcheck="true"
            v-model="item.untukPengeluaran"
            ></VTextarea>
        </VField>
    </div>
    <div class="column is-12">
        <VField>
            <VDatePicker v-model="item.tglSah" color="green" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <span>Tanggal Sah</span>
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
        </VField>
    </div>
    <div class="column is-12">
        <VField>
            <span>Nilai SPP</span>
            <VControl icon="feather:edit-3">
                <VInput type="text" v-model="item.nilaispp" placeholder=""
                    class="is-rounded"/>
            </VControl>
        </VField>
    </div>
    <div class="column is-12">
        <span><br></span>
        <VButton type="button" icon="feather:save" :loading="isSimpanSPP" color="danger" raised class="is-rounded"
            @click="SimpanSPP()" > Simpan
        </VButton>
    </div>
</Dialog>

<Dialog :position="top" :loading="isLoadingDialog" :maximizable="true" v-model:visible="popupSPD" :style="{width: '50%'}" header="Input Surat Penyediaan Dana" :modal="true" class="p-fluid">

    <div class="column is-6">
        <VField>
            <span>Nomor SPD</span>
            <VControl icon="feather:edit-3">
                <VInput type="text" v-model="item.nospd" placeholder=""
                    class="is-rounded"/>
            </VControl>
        </VField>
    </div>
    <div class="column is-6">
        <VField>
            <span>Nilai SPD</span>
            <VControl icon="feather:edit-3">
                <VInput type="text" v-model="item.nilaispd" placeholder=""
                    class="is-rounded"/>
            </VControl>
        </VField>
    </div>
    <div class="column is-4">
        <VField>
            <VDatePicker v-model="item.tglSPD" color="green" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <span>Tanggal SPD</span>
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
        </VField>
    </div>
    <div class="column is-6">
        <VField class="is-autocomplete-select">
            <span>Sumber Dana</span>
            <VControl icon="feather:search" class="prime-auto-select">
                <Dropdown v-model="item.sumberDanaSPD" :options="listAsalProduk"
                    :optionLabel="'asalproduk'" class="is-rounded" placeholder="Pilih data"
                    style="width: 100%;" showClear :filter="false" :loading="isLoading"/>
            </VControl>
        </VField>
    </div>
    <div class="column is-6">
        <span><br></span>
        <VButton type="button" icon="feather:save" :loading="isSimpanSPD" color="danger" raised class="is-rounded"
            @click="SimpanSPD()" > Simpan
        </VButton>
    </div>
    <div class="column is-12">
        <DataTable :value="dataSourceSPD" tableStyle="min-width: 50rem" v-model:selection="selectedSPD">
            <Column :exportable="false" header="#" style="width:10%">
                <template #body="slotProps">
                    <VButton type="button" icon="feather:arrow-down-right" :loading="isSearch" color="info" raised class="is-rounded"
                        @click="pilihSPD(slotProps.data)" > Pilih
                    </VButton>
                </template>
            </Column>
            <Column v-for="col of columnSPD" :field="col.field" :header="col.title"></Column>
            <!-- <Column :exportable="false" header="#" style="width:10%">
                <template #body="slotProps">
                    <VButton type="button" icon="feather:trash" :loading="isSearch" color="info" raised class="is-rounded"
                        @click="SimpanSPD(slotProps.data)" > Hapus
                    </VButton>
                </template>
            </Column> -->
        </DataTable>
    </div>
</Dialog>

<Dialog :position="top" :loading="isLoadingDialog" :maximizable="true" v-model:visible="popupSPM" :style="{width: '50%'}" header="Buat Surat Perintah Membayar" :modal="true" class="p-fluid">
    <div class="column is-6">
        <VField>
            <span>Nomor SPM</span>
            <VControl icon="feather:edit-3">
                <VInput type="text" v-model="item.nospm" placeholder=""
                    class="is-rounded" disabled/>
            </VControl>
        </VField>
    </div>
    <div class="column is-6">
        <VField class="is-autocomplete-select">
            <span>Sumber Dana</span>
            <VControl icon="feather:search" class="prime-auto-select">
                <Dropdown v-model="item.sumberDanaSPM" :options="listAsalProduk"
                    :optionLabel="'asalproduk'" class="is-rounded" placeholder="Pilih data"
                    style="width: 100%;" showClear :filter="false" :loading="isLoading"/>
            </VControl>
        </VField>
    </div>
    <div class="column is-12">
        <VField class="is-autocomplete-select">
            <span>Jenis Bukti</span>
            <VControl icon="feather:search" class="prime-auto-select">
                <Dropdown v-model="item.itemJenisBuktiSPM" :options="listJenisBukti"
                    :optionLabel="'jenisbukti'" class="is-rounded" placeholder="Pilih data"
                    style="width: 100%;" showClear :filter="false" :loading="isLoading"/>
            </VControl>
        </VField>
    </div>
    <div class="column is-12">
        <VField>
            <span>Untuk</span>
            <VTextarea
            class="is-rounded"
            rows="4"
            placeholder=""
            autocomplete="off"
            autocapitalize="off"
            spellcheck="true"
            v-model="item.untuk"
            ></VTextarea>
        </VField>
    </div>
    <div class="column is-12">
        <span>Status</span>
        <div class="flex flex-wrap gap-6">
            <div v-for="category in categories" :key="category.key" class="flex align-items-center">
            <RadioButton v-model="item.radioStatusSPM" :inputId="category.key" name="dynamic" :value="category.key" style="margin-left:20px" />
            <label :for="category.key" class="ml-1">{{ category.name }}</label>
            </div>
        </div>
    </div>
    <div class="column is-6">
        <VField>
            <span>Nomor Reg</span>
            <VControl icon="feather:edit-3">
                <VInput type="text" v-model="item.noreg" placeholder=""
                    class="is-rounded" disabled/>
            </VControl>
        </VField>
    </div>
    <div class="column is-12">
        <VField>
            <VDatePicker v-model="item.tglSPM" color="green" trim-weeks>
            <template #default="{ inputValue, inputEvents }">
                <VField>
                <span>Tanggal SPM</span>
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
        </VField>
    </div>
    <div class="column is-6">
        <VField>
            <span>Nomor SPP</span>
            <VControl icon="feather:edit-3">
                <VInput type="text" v-model="item.nosppspm" placeholder=""
                    class="is-rounded" disabled/>
            </VControl>
        </VField>
    </div>
    <div class="column is-12">
        <VField>
            <VDatePicker v-model="item.tglSahSPM" color="green" trim-weeks>
            <template #default="{ inputValue, inputEvents }">
                <VField>
                <span>Tanggal Sah SPM</span>
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
        </VField>
    </div>
    <div class="column is-6">
        <VField>
            <span>Nilai SPM</span>
            <VControl icon="feather:edit-3">
                <VInput type="text" v-model="item.nilaispm" placeholder=""
                    class="is-rounded"/>
            </VControl>
        </VField>
    </div>
    <div class="column is-6">
        <span><br></span>
        <VButton type="button" icon="feather:save" :loading="isSimpanSPD" color="danger" raised class="is-rounded"
            @click="SimpanSPM()" > Simpan
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

const confirm = useConfirm();
useHead({
  title: 'SPP - ' + import.meta.env.VITE_PROJECT,
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = reactive(
{
    filterTgl: reactive({start: moment(new Date()).format('01/01/YYYY'),end: new Date(),}),
    tglPanjar:new Date()
})
const selectedProduct = ref();

// const rowClass = (data) => {
//     return [{ 'bg-primary': data.category === 'Fitness' }];
// };
let isSearch: any = ref(false)
let isSimpanSPD: any = ref(false)
let isSimpanSPP: any = ref(false)
let isDeleteSPP: any = ref(false)

let dataSourceSPP: any = ref([])
let dataSourceSPD: any = ref([])

let listAsalProduk: any = ref([])
let listBulan: any = ref([
    { id: 1, bulan: "Januari" },
    { id: 2, bulan: "Februari" },
    { id: 3, bulan: "Maret" },
    { id: 4, bulan: "April" },
    { id: 5, bulan: "Mei" },
    { id: 6, bulan: "Juni" },
    { id: 7, bulan: "Juli" },
    { id: 8, bulan: "Agustus" },
    { id: 9, bulan: "September" },
    { id: 10, bulan: "Oktober" },
    { id: 11, bulan: "November" },
    { id: 12, bulan: "Desember" },
])
let popupSPP: any = ref(false)
let popupSPD: any =ref(false)
let popupSPM: any =ref(false)

var norecspp: any = ref('')
var norecspd: any = ref('')
var norecspm: any = ref('')
const position = ref('center');
const visible = ref(false);
let cetakS: any =ref({})
const openPosition = (dataItem) => {
    cetakS.value = dataItem
    position.value = 'left';
    visible.value = true;
}
const cetakSurat = async(dataItem)=> {
    var data = cetakS.value.nilaispp.split(".");
    var data2 = cetakS.value.nilaispd.split(".");
    var terbilang = ''
    var terbilangspd = ''
    if(data[1] == undefined){
        terbilang = H.terbilang(data[0]) + " Rupiah"
        terbilangspd = H.terbilang(data2[0]) + " Rupiah"
    }else{
        terbilang = H.terbilang(data[0]) + " Koma " + H.terbilang(data[1]) + " Rupiah"
        terbilangspd = H.terbilang(data2[0]) + " Koma " + H.terbilang(data[1]) + " Rupiah"
    }
    if(dataItem == 1){
        H.printBlade(`report/get-cetak-pengantar-spp?norec=${cetakS.value.norec}&terbilang=${terbilang}&terbilangspd=${terbilangspd}`, '_blank');
    }else if(dataItem == 2){
        H.printBlade(`report/get-cetak-ringkasan-spp?norec=${cetakS.value.norec}&terbilang=${terbilang}&terbilangspd=${terbilangspd}`, '_blank');
    }else{
        H.printBlade(`report/get-cetak-rincian-spp?norec=${cetakS.value.norec}&terbilang=${terbilang}&terbilangspd=${terbilangspd}`, '_blank');
    }
    visible.value = false;
}
let listJenisBukti: any = ref([
    { id: 1, jenisbukti: "Uang Persediaan (UP)" },
])
let categories: any = reactive([
    {key: 'Diterima', name:'Diterima'},
    {key: 'Ditolak', name:'Ditolak'},
])
item.itemJenisBukti = { id: 1, jenisbukti: "Uang Persediaan (UP)" }
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
// const columnSPP: any = ref([
//     {
//     "field": "nospp",
//     "title": "No SPP",
//     "width": "25%"
//     },
//     // {
//     // "field": "asalproduk",
//     // "title": "Sumber Dana",
//     // "width": "30%"
//     // },
//     {
//     "field": "jenisbukti",
//     "title": "Jenis Bukti",
//     "width": "40%"
//     },
//     // {
//     // "field": "dasarpengeluaran",
//     // "title": "Dasar Pengeluaran",
//     // "width": "30%"
//     // },
//     {
//     "field": "status",
//     "title": "Status",
//     "width": "20%"
//     },
//     {
//     "field": "nospd",
//     "title": "SPD",
//     "width": "30%"
//     },
//     {
//     "field": "tglspp",
//     "title": "Tgl SPP",
//     "width": "40%"
//     },
//     {
//     "field": "bulan",
//     "title": "Bulan",
//     "width": "30%"
//     },
//     {
//     "field": "pengeluaran",
//     "title": "Untuk Pengeluaran",
//     "width": "40%"
//     },
//     {
//     "field": "tglsah",
//     "title": "Tgl Sah",
//     "width": "40%"
//     },
//     {
//     "field": "nilaispp",
//     "title": "Nilai SPP",
//     "width": "30%",
//     "template": "oke"
//     },
// ])
const columnSPD: any = ref([
    {
        "field": "nospd",
        "title": "No SPD",
        "width": "80px"
    },
    {
        "field": "tglspd",
        "title": "Tgl SPD",
        "width": "80px"
    },
    {
        "field": "asalproduk",
        "title": "Sumber Dana",
        "width": "80px"
    },
    {
        "field": "nilaispd",
        "title": "Nilai SPD",
        "width": "80px"
    },
    {
        "field": "nospp",
        "title": "No SPP",
        "width": "80px",
    },
    
])

const loadCombo = async()=>{
    await useApi().get(`perencanaan/get-combo-spp`).then((response)=>{
        listAsalProduk.value = response.asalproduk
    })
    
}
const loadData = async ()=> {
    isSearch.value = true
    await useApi().get(`perencanaan/get-data-spp?tglsppawal=${moment(item.filterTgl.start).format('YYYY-MM-DD')}&tglsppakhir=${moment(item.filterTgl.end).format('YYYY-MM-DD')}`).then((response)=>{
        isSearch.value = false
        dataSourceSPP.value = response.data
    })
    await useApi().get(`perencanaan/get-data-spd`).then((response)=>{
        isSearch.value = false
        dataSourceSPD.value = response.data
    })
}
loadCombo()
loadData()
const TambahSPP = async()=>{
    popupSPP.value = true
}
const TambahSPD = async()=>{
    popupSPD.value = true
}
const SimpanSPD = async()=> {
    isSimpanSPD.value = true
    var objSave =
    {
        nospd: item.nospd,
        tglspd: moment(item.tglSPD).format('YYYY-MM-DD'),
        nilaispd: item.nilaispd,
        objectasalprodukfk: item.sumberDanaSPD.id,
        norec: norecspd.value
    }

    await useApi().post('perencanaan/save-spd', objSave).then((response) => {
        useApi().get(`perencanaan/get-data-spd`).then((response)=>{
        isSimpanSPD.value = false
        dataSourceSPD.value = response.data
    })
        
    })
}
const pilihSPD = async(dataItem)=> {
    item.nospd = dataItem.nospd
    popupSPD.value = false
}
const SimpanSPP = async()=> {
    isSimpanSPP.value = true
    var objSave =
    {
        norec: norecspp.value,
        nospp: item.nospp,
        jenisbukti: item.itemJenisBukti.jenisbukti,
        dasarpengeluaran: item.dasarPengeluaran,
        statusspp: item.radioStatusSPP,
        nospd: item.nospd,
        tglspp: moment(item.tglSPP).format('YYYY-MM-DD HH:mm'),
        bulan: item.itemBulan.bulan,
        pengeluaran: item.untukPengeluaran,
        tglsah: moment(item.tglSah).format('YYYY-MM-DD HH:mm'),
        nilaispp: item.nilaispp,
        objectasalprodukfk: item.sumberDanaSPP.id,
    }

    await useApi().post('perencanaan/save-spp', objSave).then((response) =>{
        isSimpanSPP.value = false
        norecspp.value = ""
        loadData()
        popupSPP.value = false

    }, (error) => {
        isSimpanSPP.value = false
    })

}
const DetailSPP = async (dataItem)=> {
    if (dataItem != undefined) {

        listJenisBukti.value = [
            { id: 1, jenisbukti: "Uang Persediaan (UP)" },
        ];
        listBulan.value = [
            { id: 1, bulan: "Januari" },
            { id: 2, bulan: "Februari" },
            { id: 3, bulan: "Maret" },
            { id: 4, bulan: "April" },
            { id: 5, bulan: "Mei" },
            { id: 6, bulan: "Juni" },
            { id: 7, bulan: "Juli" },
            { id: 8, bulan: "Agustus" },
            { id: 9, bulan: "September" },
            { id: 10, bulan: "Oktober" },
            { id: 11, bulan: "November" },
            { id: 12, bulan: "Desember" },
        ];


        norecspp.value = dataItem.norec
        item.nospp = dataItem.nospp
        if(dataItem.jenisbukti != null){
            item.itemJenisBukti = { id: 1, jenisbukti: "Uang Persediaan (UP)" }
        } else{
            item.itemJenisBukti = {}
        }
        item.dasarPengeluaran = dataItem.dasarpengeluaran
        item.radioStatusSPP = dataItem.status
        item.nospd = dataItem.nospd
        item.tglSPP = new Date(dataItem.tglspp)

        if(dataItem.bulan == "Januari"){
            item.itemBulan = { id: 1, bulan: "Januari" }    
        } else if(dataItem.bulan == "Februari"){
            item.itemBulan = { id: 2, bulan: "Februari" }    
        } else if(dataItem.bulan == "Maret"){
            item.itemBulan = { id: 3, bulan: "Maret" }   
        } else if(dataItem.bulan == "April"){
            item.itemBulan = { id: 4, bulan: "April" }   
        } else if(dataItem.bulan == "Mei"){
            item.itemBulan = { id: 5, bulan: "Mei" } 
        } else if(dataItem.bulan == "Juni"){
            item.itemBulan = { id: 6, bulan: "Juni" }
        } else if(dataItem.bulan == "Juli"){
            item.itemBulan = { id: 7, bulan: "Juli" }
        } else if(dataItem.bulan == "Agustus"){
            item.itemBulan = { id: 8, bulan: "Agustus" }
        } else if(dataItem.bulan == "September"){
            item.itemBulan = { id: 9, bulan: "September" }
        } else if(dataItem.bulan == "Oktober"){
            item.itemBulan = { id: 10, bulan: "Oktober" }
        } else if(dataItem.bulan == "November"){
            item.itemBulan = { id: 11, bulan: "November" }
        } else if(dataItem.bulan == "Desember"){
            item.itemBulan = { id: 12, bulan: "Desember" }
        }


        item.untukPengeluaran = dataItem.pengeluaran
        item.tglSah = dataItem.tglsah
        item.nilaispp = dataItem.nilaispp
        item.sumberDanaSPP = {id: dataItem.idasalproduk, asalproduk: dataItem.asalproduk}
        popupSPP.value = true
    }
}
const DeleteSPP = async(dataItem) => {
    isDeleteSPP.value = true
    confirm.require({
    message: 'Yakin ingin menghapus data ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
        var objSave = {
            data: dataItem.norec
        }
        useApi().post('perencanaan/delete-spp',objSave).then((response)=>{
        isDeleteSPP.value = false
        loadData()
        })
    },
    reject: () => {
        isDeleteSPP.value = false
        loadData()
    },
    })
}
const loadDataSPM = async()=> {
    await useApi().get("perencanaan/get-data-spm?nospp="+item.nosppspm).then((response) => {
        // dataSourceSPM.value = e.data.data
    })
}
const inputSPM = async(dataItem)=>{
    listJenisBukti = [
        { id: 1, jenisbukti: "Uang Persediaan (UP)" },
    ];

    item.nosppspm = dataItem.nospp
    item.tglsppspm = dataItem.tglspp
    item.tglSahSPM = dataItem.tglspp
    item.nospm = ''
    item.untuk = ''
    item.noreg = ''
    item.tglSPM = undefined
    item.nilaispm = dataItem.nilaispp
    item.itemJenisBuktiSPM = { id: 1, jenisbukti: "Uang Persediaan (UP)" }
    item.radioStatusSPM = "Diterima"
    loadDataSPM()
    popupSPM.value = true
}
const SimpanSPM = async(dataItem)=> {
    var objSave =
    {
        norec: norecspm.value,
        nospm: item.nospm,
        jenisbukti: item.itemJenisBuktiSPM.jenisbukti,
        untukpengeluaran: item.untuk,
        status: item.radioStatusSPM,
        nospp: item.nosppspm,
        tglspm: moment(item.tglSPM).format('YYYY-MM-DD'),
        tglsah: moment(item.tglSahSPM).format('YYYY-MM-DD'),
        nilaispm: item.nilaispm,
        noreg: item.noreg,
        objectasalprodukfk: item.sumberDanaSPM.id,
    }


    await useApi().post('perencanaan/save-spm', objSave).then((response) => {
        loadDataSPM();
        popupSPM.value = false
    }, (error) => {

    })
}
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: '100%';
    margin: 0 auto;
}

.form-fieldset {
    padding: 20px 0;
    max-width: 580px;
    margin: 0 auto;
}
</style>