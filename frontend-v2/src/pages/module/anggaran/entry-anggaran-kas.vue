<template>
<VCard>
    <ConfirmDialog/>
    <div class="form-layout">
        <div class="form-outer">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Anggaran Kas</h3>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <VField class="is-autocomplete-select">
                                    <span>Sub Kegiatan</span>
                                    <VControl icon="feather:search">
                                        <Dropdown v-model="item.combokegiatan" :options="d_subKegiatan" :optionLabel="'keterangan'"
                                            class="is-rounded" placeholder="Sub Kegiatan" :loading="isLoadingSearch" style="width: 100%;" :filter="true" @change="changeSubsubKegiatan($event)"
                                            showClear  />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField class="is-autocomplete-select">
                                    <span>Sub Sub Kegiatan</span>
                                    <VControl icon="feather:search">
                                        <Dropdown v-model="item.combosubsubkegiatan" :options="d_subSubKegiatan" :optionLabel="'keterangan'"
                                            class="is-rounded" placeholder="Sub sub Kegiatan" style="width: 100%;" :filter="true" :loading="isLoadingSearch"
                                            showClear />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-3">
                        <VField>
                            <VLabel class="required-field">Organisasi</VLabel>
                            <VControl icon="feather:user">
                                <VInput type="text" v-model="item.organisasi" placeholder=""
                                class="is-rounded" disabled :loading="isLoadingSearch"/>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField class="is-autocomplete-select">
                            <span>Tahap</span>
                            <VControl icon="feather:search" class="prime-auto-select">
                                <Dropdown v-model="item.searchtahapkeg" :options="d_listTahapKegiatan"
                                    :optionLabel="'tahap'" class="is-rounded" placeholder="Pilih data"
                                    style="width: 100%;" showClear :filter="false" :loading="isLoadingSearch"/>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField class="is-rounded-select is-autocomplete-select
                        mt-0 pt-0" v-slot="{ id }">
                            <span>Tahun</span>
                            <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.tahun" :options="d_Tahun" :optionLabel="'tahun'"
                                    class="is-rounded" placeholder="Tahun" style="width: 100%;" :filter="true"
                                    showClear :loading="isLoadingSearch"/>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <span><br></span>
                        <VButton type="button" icon="feather:search" :loading="isLoadingBtn" color="primary" raised class="is-rounded"
                            @click="fetchData()"  > Search
                        </VButton>
                    </div>
                    <div class="column is-12">
                        <p style="color: #ff0000;font-weight: bold; font-style: italic;">
                        *Warna Merah : Terdapat anggaran yang belum di alokasikan
                        </p>
                    </div>
                </div>
            </div>

           <DataTable scrollable scrollHeight="400px"  paginator :rows="20" :loading="isLoadingTableAll" v-model:expandedRows="expandedRows" :value="dataSourceKeteranganBelanja" removableSort tableStyle="min-width: 50rem" >
            <template #header>
                <div class="columns is-multiline">
                    
                </div>
            </template>
            <Column expander style="width: 5rem" frozen />
            <Column v-for="col in columnKeteranganBelanja"  :class="col.field == 'keterangan' ? 'font-bold' : ''" :frozen="col.field == 'keterangan' || col.field == 'subtotal' || col.title == 'Sub Total' ? true : false" :field="col.field" :header="col.title" :style="'min-width:' + col.width">
                <template #body="slotProps">
                    <span :style="{ color: slotProps.data.subtotal !== slotProps.data.totalbulanan ? 'red' : 'none' }">{{ col.template != undefined ?
                        H.formatRupiah(slotProps.data[col.field], '')
                        : slotProps.data[col.field] }}</span>
                </template>
            </Column>
            <template #expansion="slotProps">
                <div class="p-3">
                    <h2><span style="font-weight:bold;"> Kegiatan :  {{ slotProps.data.kegiatan }}</span></h2>
                    <DataTable paginator :rows="20"  scrollable :value="slotProps.data.detail">
                        <Column :exportable="false" header="Pilih" style="width:5%" frozen>
                            <template #body="slotProps">
                                <VButton type="button" icon="feather:arrow-right" :loading="isLoadingBtn" color="success" raised class="is-rounded"
                                @click="alokasiBelanja(slotProps.data)" > Alokasi
                            </VButton>
                            </template>
                        </Column>
                        <Column v-for="col in columnKeteranganBelanja2"  :class="col.field == 'mataanggaran' ? 'font-bold' : ''" :frozen="col.field == 'mataanggaran' ? true : false" :field="col.field" :header="col.title" :style="'min-width:' + col.width ">
                            <template #body="slotProps">
                                <span :style="{ color: slotProps.data.subtotal !== slotProps.data.totalbulanan ? 'red' : 'none' }">{{ col.template != undefined ?
                                    H.formatRupiah(slotProps.data[col.field], '')
                                    : slotProps.data[col.field] }}</span>
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </template>
           </DataTable>
        </div>
    </div>
</VCard>

<Dialog :loading="isLoadingDialog" :maximizable="true"  v-model:visible="popupAlokasiKeteranganBelanja" :style="{width: '100%'}" header="Alokasi Anggaran Belanja Bulanan" :modal="true" class="p-fluid">
    <div class="columns is-multiline">
        <div class="column is-8">
            <div class="columns is-multiline">
                <div class="column is-6">
                    <VField>
                        <span>TAHUN ANGGARAN</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.thnAnggaran" placeholder=""
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <span>TAHAP</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.tahap" placeholder=""
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <span>SUB KEGIATAN</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.subKegiatan" placeholder=""
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <span>SUB SUB KEGIATAN</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.subSubKegiatan" placeholder=""
                                class="is-rounded" disabled />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <span>KOMPONEN BIAYA</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.mataanggaran" placeholder=""
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
            </div>
        </div>
        <div class="column is-4">
            <div class="columns is-multiline">
                <div class="column is-12">
                    <VField>
                        <span>TOTAL ANGGARAN</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.totalanggaran" placeholder=""
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <span>TOTAL BULANAN</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.totalbulanan" placeholder=""
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <span>SELISIH ANGGARAN</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.selisihanggaran" placeholder=""
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
            </div>
        </div>
        <div class="column is-12">
            <DataTable paginator :rows="20"  scrollable :value="dataSourceAlokasiAnggaran">
                <Column :exportable="false" header="Pilih" style="width:5%" frozen>
                    <template #body="slotProps">
                        <VButton type="button" icon="feather:arrow-right" :loading="isLoadingBtn" color="success" raised class="is-rounded"
                        @click="DetailAlokasi(slotProps.data)" > Detail
                    </VButton>
                    </template>
                </Column>
                <Column v-for="col in columnAlokasiAnggaran"  :class="col.field == 'mataanggaran' ? 'font-bold' : ''" :frozen="col.field == 'mataanggaran' ? true : false" :field="col.field" :header="col.title" :style="'min-width:' + col.width ">
                    <template #body="slotProps">
                        <span>{{ col.template != undefined ?
                            H.formatRupiah(slotProps.data[col.field], '')
                            : slotProps.data[col.field] }}</span>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
    
    <!-- <template #footer>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
            @click="SimpanPopUpAlokasi()"> Simpan
        </VButton>
    </template> -->
</Dialog>

<Dialog :loading="isLoadingDialog" :maximizable="true"  v-model:visible="popupDetailAlokasi" :style="{width: '100%'}" header="Alokasi Anggaran" :modal="true" class="p-fluid">
    <div class="columns is-multiline">
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <span>Keterangan Belanja</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.alokasiKeteranganBelanja" placeholder=""
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <VField>
                        <span>Anggaran</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.AnggaranAlokasiDetail" placeholder=""  
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <VField>
                        <span>Total Bulan</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.totalBulanAlokasiDetail" placeholder=""  :style="{ backgroundColor: warnaTotal.color, color: 'white'  }"
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <VField>
                        <span>Anggaran Tersisa</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.sisaAnggaran" placeholder="" 
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
            </div>
        </div>
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-3">
                    <VField>
                        <span>januari</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln1" placeholder="" 
                                class="is-rounded" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>Februari</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln2" placeholder="" 
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>Maret</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln3" placeholder="" 
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>April</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln4" placeholder="" 
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>Mei</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln5" placeholder="" 
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>Juni</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln6" placeholder="" 
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>Juli</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln7" placeholder="" 
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>Agustus</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln8" placeholder="" 
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>September</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln9" placeholder="" 
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>Oktober</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln10" placeholder="" 
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>November</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln11" placeholder="" 
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>Desember</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.bln12" placeholder="" 
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
            </div>
        </div>
    </div>
    <template #footer>
        <!-- <VButton icon="feather:layers" rounded outlined color="info" @click="bagiarataAlokasiDetail()" style="margin-right:10px" :loading="isLoadingSimpan">
            Bagi Rata
        </VButton> -->
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
            @click="saveData()"> Simpan
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
import { useWindowScroll } from '@vueuse/core'
import AutoComplete from 'primevue/autocomplete';

import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';
import moment from 'moment'
const confirm = useConfirm();
useHead({
  title: 'Entry Anggaran - ' + import.meta.env.VITE_PROJECT,
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = reactive({})
const dataItemzzzzz: any = ref([])
const warnaTotal: any = reactive({ color: 'green' })
const d_Tahun: any = ref([])

// const filters = ref({
//     global: { value: null, matchMode: FilterMatchMode.CONTAINS },
// });

let isLoadingSimpan: any = ref(false)
let isLoadingTableAll: any = ref(false)
let isLoadingBtn: any = ref(false)
let isLoadingSearch: any = ref(false)
let isLoadingDialog: any = ref(false)
let isLoading: any= ref(false)
let d_listDiv: any = ref([])
let d_listTahapKegiatan: any = ref([])
let d_listJenisBelanja: any = ref([])
let d_listPenerima: any = ref([])
let d_listAsalProduk: any = ref([])
let d_subSubKegiatan: any = ref([])
let d_subKegiatan: any = ref([])

let dataSourceKeteranganBelanja: any = ref([])
let dataSourceAlokasiAnggaran: any = ref([])
let dataSourceDetailKeteranganBelanja: any = ref([])
var dataAlokasi2222: any =reactive([])

let popupAlokasiKeteranganBelanja: any = ref(false)
let popupDetailAlokasi: any = ref(false)
const expandedRows = ref([]);
loadCombo()
async function loadCombo(){
    for (let i = parseFloat(H.formatDate(new Date(),'YYYY')) - 5; i <= parseFloat(H.formatDate(new Date(),'YYYY')) + 5; i++) {
        d_Tahun.value.push({
            id: i, tahun: i
        })
    }
    isLoadingSearch.value =true
    await useApi().get(
        `anggaran/get-combo`
    ).then((response) => {
        d_listTahapKegiatan.value = response.tahap
        d_listJenisBelanja.value = response.jenisbelanja
        d_listDiv.value = response.kelompokanggaran
        d_listAsalProduk.value = response.asalproduk
    })
    await useApi().get('anggaran/get-data-setting-anggaran').then((response) => {
        isLoadingSearch.value =false
        d_subKegiatan.value = response.kegiatancombo
        d_listTahapKegiatan.value.forEach((element: any) => {
            if(element.id == response.data[0].objecttahapaktivfk){
                item.searchtahapkeg = element
                item.tahapdetail = element
            }
        });
        d_Tahun.value.forEach((element: any) => {
            if(element.tahun == response.data[0].tahunanggaran){
                item.tahun = element
            }
        });
        item.organisasi = response.data[0].organisasi

    })
}
async function fetchData() {
    loadData()
}
const changeSubsubKegiatan = async (filter: any) => {
    d_subSubKegiatan.value = item.combokegiatan.detail
}
async function loadData() {
    isLoadingBtn.value = true
    var subkegiatan = '';
    var subsubkegiatan = '';
    if (item.combokegiatan != undefined) {
        subkegiatan = '&subkegiatan=' + item.combokegiatan.kode
    }
    if (item.combosubsubkegiatan != undefined) {
        subsubkegiatan = '&subsubkegiatan=' + item.combosubsubkegiatan.kode
    }
    var idpegawai = ''
    if(item.searchtahapkeg == undefined){
        isLoadingBtn.value = false
    }
    await useApi().get('anggaran/get-kegiatan-anggaran-kas?tahun=' + moment(item.tahun).format('YYYY') + "&tahap=" + item.searchtahapkeg.id + "&idpegawai=" + idpegawai + subkegiatan + subsubkegiatan).then((response) => {
        isLoadingBtn.value = false
        dataSourceKeteranganBelanja.value = response.alokasimataanggaran
        dataSourceDetailKeteranganBelanja.value = response.detailalokasimataanggaran 
    })
    
}
let columnAlokasiAnggaran = ref(
    [
    {
        "field": "keteranganbelanja",
        "title": "Nama Biaya",
        "width": "100px"
    },
    {
        "field": "subtotal",
        "title": "Anggaran",
        "width": "40px",
        "template": "<span class='style-right'>{{formatRupiah2('#: subtotal #', '')}}</span>",
    },
    {
        "field": "jan",
        "title": "Jan",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: jan #', '')}}</span>",
    },
    {
        "field": "feb",
        "title": "Feb",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: feb #', '')}}</span>",
    },
    {
        "field": "mar",
        "title": "Mar",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: mar #', '')}}</span>",
    },
    {
        "field": "apr",
        "title": "Apr",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: apr #', '')}}</span>",
    },
    {
        "field": "mei",
        "title": "Mei",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: mei #', '')}}</span>",
    },
    {
        "field": "jun",
        "title": "Jun",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: jun #', '')}}</span>",
    },
    {
        "field": "jul",
        "title": "Jul",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: jul #', '')}}</span>",
    },
    {
        "field": "agt",
        "title": "Agt",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: agt #', '')}}</span>",
    },
    {
        "field": "sep",
        "title": "Sep",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: sep #', '')}}</span>",
    },
    {
        "field": "okt",
        "title": "Okt",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: okt #', '')}}</span>",
    },
    {
        "field": "nov",
        "title": "Nov",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: nov #', '')}}</span>",
    },
    {
        "field": "des",
        "title": "Des",
        "width": "30px",
        "template": "<span class='style-right'>{{formatRupiah2('#: des #', '')}}</span>",
    }
]
)
let columnKeteranganBelanja = ref(
    [
    // {
    //     "command": [
    //             {
    //                 text: "Alokasi",
    //                 click: alokasiBelanja,
    //                 imageClass: "clinic fa"
    //             },
    //         ],
    //             title: "",
    //             width: "30px",
    // },
    {
        "field": "keterangan",
        "title": "Kegiatan",
        "width": "250px",
    },
    {
        "field": "subtotal",
        "title": "Sub Total",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: subtotal #', '')}}</span>",
    },
    {
        "field": "jan",
        "title": "Jan",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "feb",
        "title": "Feb",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "mar",
        "title": "Mar",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "apr",
        "title": "Apr",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "mei",
        "title": "Mei",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "jun",
        "title": "Juni",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "jul",
        "title": "Juli",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "agt",
        "title": "Agst",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "sep",
        "title": "Sep",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "okt",
        "title": "Okt",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "nov",
        "title": "Nov",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "des",
        "title": "Des",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",
        
    },
    {
        "field": "totalbulanan",
        "title": "Total Bulanan",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah('#: totalbulanan #', '')}}</span>",
    },
]
)
let columnKeteranganBelanja2 = ref(
    [
    {
        "field": "mataanggaran",
        "title": "Rekening",
        "width": "200px",
    },
    {
        "field": "subtotal",
        "title": "Sub Total",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: subtotal #', '')}}</span>",
    },
    {
        "field": "jan",
        "title": "Jan",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "feb",
        "title": "Feb",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "mar",
        "title": "Mar",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "apr",
        "title": "Apr",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "mei",
        "title": "Mei",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "jun",
        "title": "Juni",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "jul",
        "title": "Juli",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "agt",
        "title": "Agst",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "sep",
        "title": "Sep",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "okt",
        "title": "Okt",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "nov",
        "title": "Nov",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "des",
        "title": "Des",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",

    },
    {
        "field": "totalbulanan",
        "title": "Total Bulanan",
        "width": "100px",
        "template": "<span class='style-right'>{{formatRupiah2('#: totalbulanan #', '')}}</span>",
    },
    // {
    //     "command": [{
    //         text: "Hapus",
    //         click: HapusAnggaranKas,
            
    //     }, ],
    //     title: "",
    //     width: "100px",
    // },
]
)
function alokasiBelanja(dataItem: any) {
    let totalAnggaran = 0;
    var DataTabKeteranganBelanja =[]
    dataAlokasi2222 =[]
    dataItemzzzzz.value = dataItem
    for (let x = 0; x < dataSourceDetailKeteranganBelanja.value.length; x++) {
        var el = dataSourceDetailKeteranganBelanja.value[x]
        if (el.kodemataanggaran == dataItem.kodemataanggaran && el.keterangan == dataItem.keterangan) {
            el.subtotalRP = H.formatRupiah(el.subtotal, "Rp. ")
            
            totalAnggaran = totalAnggaran + parseFloat(el.subtotal)
    
            dataAlokasi2222.push({
                "norec": el.norec,
                "kdprofile":el.kdprofile,
                "statusenabled":el.statusenabled,
                "objectkegiatanfk":el.objectkegiatanfk,
                "objectmataanggaranfk":el.objectmataanggaranfk,
                "keteranganbelanja": el.keteranganbelanja,
                "nourut": el.nourut,
                "jml": el.jml,
                "hargasatuan":el.hargasatuan,
                "subtotal": el.subtotal,
                "satuan": el.satuan,
                "objectasalprodukfk":el.objectasalprodukfk,
                "mataanggaran": el.mataanggaran,
                "kegiatan":el.kegiatan,
                "asalproduk":el.asalproduk,
                "kodemataanggaran": el.kodemataanggaran,
                "namamataanggaran": el.namamataanggaran,
                "kode": el.kode,
                "keterangan":  el.keterangan,
                "tahun": el.tahun,
                "tahap": el.tahap,
                "agt": el.agt,
                "jan": el.jan,
                "feb": el.feb,
                "mar": el.mar,
                "apr": el.apr,
                "mei": el.mei,
                "jun": el.jun,
                "jul": el.jul,
                "sep": el.sep,
                "okt": el.okt,
                "nov": el.nov,
                "des": el.des,
                "subtotalRP":  el.subtotalRP,
            })
        }
    }
                
    dataSourceAlokasiAnggaran.value = dataAlokasi2222

    
    item.thnAnggaran = dataItem.tahun
    item.tahap = dataItem.tahap
    item.subKegiatan = dataItem.kodesubkegiatan+' - '+dataItem.keterangansubkegiatan
    item.subSubKegiatan = dataItem.kode+' - '+dataItem.keterangan
    item.mataanggaran = dataItem.mataanggaran
    // var totalanggaranperalokasi = totalAnggaran
    item.totalanggaran = H.formatRupiah(totalAnggaran, "Rp. ")
    item.totalbulanan = H.formatRupiah(dataItem.totalbulanan, "Rp. ")
    // kodemataanggaranalokasi = dataItem.kodemataanggaran
    let selisih = 0
    selisih = totalAnggaran - dataItem.totalbulanan
    item.selisihanggaran = H.formatRupiah(selisih, "Rp. ")
    popupAlokasiKeteranganBelanja.value = true
}
function DetailAlokasi(dataItem: any) {
    item.alokasinorecDetail = dataItem.norec
    item.alokasiKeteranganBelanja = dataItem.keteranganbelanja
    item.alokasiSubtotal = dataItem.subtotal
    item.AnggaranAlokasiDetail = dataItem.subtotalRP
    item.AnggaranAlokasiDetail2 = parseFloat(dataItem.subtotal)
    item.bln1 = dataItem.jan
    item.bln2 = dataItem.feb
    item.bln3 = dataItem.mar
    item.bln4 = dataItem.apr
    item.bln5 = dataItem.mei
    item.bln6 = dataItem.jun
    item.bln7 = dataItem.jul
    item.bln8 = dataItem.agt
    item.bln9 = dataItem.sep
    item.bln10 = dataItem.okt
    item.bln11 = dataItem.nov
    item.bln12 = dataItem.des
    item.totalBulanAlokasiDetail = 
    parseFloat(item.bln1) + 
    parseFloat(item.bln2) +
    parseFloat(item.bln3) +
    parseFloat(item.bln4) +
    parseFloat(item.bln5) +
    parseFloat(item.bln6) +
    parseFloat(item.bln7) +
    parseFloat(item.bln8) +
    parseFloat(item.bln9) +
    parseFloat(item.bln10) +
    parseFloat(item.bln11) + 
    parseFloat(item.bln12) 
    if(item.totalBulanAlokasiDetail == item.AnggaranAlokasiDetail2){
        warnaTotal.color = 'green';
    }
    else if(item.totalBulanAlokasiDetail > item.AnggaranAlokasiDetail2){
        warnaTotal.color = 'red';
    }else if(item.totalBulanAlokasiDetail < item.AnggaranAlokasiDetail2){
        warnaTotal.color = 'green';
    }
    popupDetailAlokasi.value = true
    
}
function bagiarataAlokasiDetail() {
    let totalAnggaran = parseFloat(item.alokasiSubtotal)
    

    if(totalAnggaran % 12 == 0){
        let blnrata = totalAnggaran/12
        blnrata = blnrata

        item.bln1 = blnrata
        item.bln2 = blnrata
        item.bln3 = blnrata
        item.bln4 = blnrata
        item.bln5 = blnrata
        item.bln6 = blnrata
        item.bln7 = blnrata
        item.bln8 = blnrata
        item.bln9 = blnrata
        item.bln10 = blnrata
        item.bln11 = blnrata
        item.bln12 = blnrata

    } else{

        let blnrata = totalAnggaran/12

        blnrata = Math.floor(blnrata)

        item.bln1 = blnrata
        item.bln2 = blnrata
        item.bln3 = blnrata
        item.bln4 = blnrata
        item.bln5 = blnrata
        item.bln6 = blnrata
        item.bln7 = blnrata
        item.bln8 = blnrata
        item.bln9 = blnrata
        item.bln10 = blnrata
        item.bln11 = blnrata
        item.bln12 = (totalAnggaran - (11*blnrata)).toFixed(0)
    }

    
}

const dialogConfirm = (e: any) => {
    confirm.require({
    message: 'Masih terdapat sisa anggaran yang belum di alokasikan, Lanjutkan untuk Simpan?',
    header: 'Konfirmasi Simpan Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
        SimpanPopUpDetailAlokasi()
    },
    reject: () => {
        isLoadingSimpan.value = false

    },
  })
}
async function saveData(){
    if(item.totalBulanAlokasiDetail > item.AnggaranAlokasiDetail2){
        isLoadingSimpan.value = false
        H.alert('error','Tidak boleh lebih dari anggaran yang di alokasikan')
        return
    }
    // if (item.bln12 < 0) {
    //     H.alert('error','Tidak boleh kurang dari 0')
    //     return
    // }
    if(item.sisaAnggaran>0){
        dialogConfirm(item.sisaAnggaran)
    }else{
        SimpanPopUpDetailAlokasi()
    }
}
async function SimpanPopUpDetailAlokasi() {
    isLoadingSimpan.value = true

    

    // for (let index = 0; index < dataAlokasi2222.length; index++) {
    //     let element = dataAlokasi2222[index];
    //     if(element.norec == item.alokasinorecDetail){
    //         element.jan = item.bln1
    //         element.feb = item.bln2
    //         element.mar = item.bln3
    //         element.apr = item.bln4
    //         element.mei = item.bln5
    //         element.jun = item.bln6
    //         element.jul = item.bln7
    //         element.agt = item.bln8
    //         element.sep = item.bln9
    //         element.okt = item.bln10
    //         element.nov = item.bln11
    //         element.des = item.bln12
    //     }
    // }
    // dataSourceAlokasiAnggaran.value = dataAlokasi2222;
    
    var objSave = [];
    objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 1,nilai: item.bln1
        });
        objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 2,nilai: item.bln2
        });
        objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 3,nilai: item.bln3
        });
        objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 4,nilai: item.bln4
        });
        objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 5,nilai: item.bln5
        });
        objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 6,nilai: item.bln6
        });
        objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 7,nilai: item.bln7
        });
        objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 8,nilai: item.bln8
        });
        objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 9,nilai: item.bln9
        });
        objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 10,nilai: item.bln10
        });
        objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 11,nilai: item.bln11
        });
        objSave.push({
            norec: '',keteranganbelanjafk: item.alokasinorecDetail,
            bulanint: 12,nilai: item.bln12
        });
    
    var datasave =
        {
            data: objSave
        }
    await useApi().post('perencanaan/save-alokasi-keterangan-belanja',datasave)
    await loadData();
    let totalAnggaran = 0;
    dataAlokasi2222 =[]
    let selisih = 0
    
    for (let x = 0; x < dataSourceDetailKeteranganBelanja.value.length; x++) {
        var el = dataSourceDetailKeteranganBelanja.value[x]
        if (el.kodemataanggaran == dataItemzzzzz.value.kodemataanggaran && el.keterangan == dataItemzzzzz.value.keterangan) {
            el.subtotalRP = H.formatRupiah(el.subtotal, "Rp. ")
            totalAnggaran = totalAnggaran + parseFloat(el.subtotal)
            dataAlokasi2222.push({
                "norec": el.norec,
                "kdprofile":el.kdprofile,
                "statusenabled":el.statusenabled,
                "objectkegiatanfk":el.objectkegiatanfk,
                "objectmataanggaranfk":el.objectmataanggaranfk,
                "keteranganbelanja": el.keteranganbelanja,
                "nourut": el.nourut,
                "jml": el.jml,
                "hargasatuan":el.hargasatuan,
                "subtotal": el.subtotal,
                "satuan": el.satuan,
                "objectasalprodukfk":el.objectasalprodukfk,
                "mataanggaran": el.mataanggaran,
                "kegiatan":el.kegiatan,
                "asalproduk":el.asalproduk,
                "kodemataanggaran": el.kodemataanggaran,
                "namamataanggaran": el.namamataanggaran,
                "kode": el.kode,
                "keterangan":  el.keterangan,
                "tahun": el.tahun,
                "tahap": el.tahap,
                "agt": el.agt,
                "jan": el.jan,
                "feb": el.feb,
                "mar": el.mar,
                "apr": el.apr,
                "mei": el.mei,
                "jun": el.jun,
                "jul": el.jul,
                "sep": el.sep,
                "okt": el.okt,
                "nov": el.nov,
                "des": el.des,
                "subtotalRP":  el.subtotalRP,
            })
        }
    }
    
    dataSourceAlokasiAnggaran.value = dataAlokasi2222
    selisih = totalAnggaran - item.totalBulanAlokasiDetail
    item.selisihanggaran = H.formatRupiah(selisih, "Rp. ")
    isLoadingSimpan.value = false;
    popupDetailAlokasi.value = false;
}

watch(
    () =>item.bln1,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        watchBulan(newValue)
    }
});
watch(
    () =>item.bln2,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        watchBulan(newValue)
    }
});
watch(
    () =>item.bln3,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        watchBulan(newValue)
    }
});
watch(
    () =>item.bln4,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        watchBulan(newValue)
    }
});
watch(
    () =>item.bln5,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        watchBulan(newValue)
    }
});
watch(
    () =>item.bln6,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        watchBulan(newValue)
    }
});
watch(
    () =>item.bln7,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        watchBulan(newValue)
    }
});
watch(
    () =>item.bln8,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        watchBulan(newValue)
    }
});
watch(
    () =>item.bln9,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        
        watchBulan(newValue)
    }
});
watch(
    () =>item.bln10,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        watchBulan(newValue)
    }
});
watch(
    () =>item.bln11,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        watchBulan(newValue)
    }
});

watch(
    () =>item.bln12,
(newValue, oldValue) => {
    if (newValue != oldValue) {
        watchBulan(newValue)
    }
});
const watchBulan = (e: any) => {
    let totalAlokasi = item.alokasiSubtotal
    let bln1: any = 0
    if(item.bln1 != undefined && item.bln1 != ""){
        bln1 = item.bln1
    }
    let bln2: any = 0
    if(item.bln2 != undefined && item.bln2 != ""){
        bln2 = item.bln2
    }
    let bln3: any = 0
    if(item.bln3 != undefined && item.bln3 != ""){
        bln3 = item.bln3
    }
    let bln4: any = 0
    if(item.bln4 != undefined && item.bln4 != ""){
        bln4 = item.bln4
    }
    let bln5: any = 0
    if(item.bln5 != undefined && item.bln5 != ""){
        bln5 = item.bln5
    }
    let bln6: any = 0
    if(item.bln6 != undefined && item.bln6 != ""){
        bln6 = item.bln6
    }
    let bln7: any = 0
    if(item.bln7 != undefined && item.bln7 != ""){
        bln7 = item.bln7
    }
    let bln8: any = 0
    if(item.bln8 != undefined && item.bln8 != ""){
        bln8 = item.bln8
    }
    let bln9: any = 0
    if(item.bln9 != undefined && item.bln9 != ""){
        bln9 = item.bln9
    }
    let bln10: any = 0
    if(item.bln10 != undefined && item.bln10 != ""){
        bln10 = item.bln10
    }
    let bln11: any = 0
    if(item.bln11 != undefined && item.bln11 != ""){
        bln11 = item.bln11
    }
    let bln12: any = 0
    if(item.bln12 != undefined && item.bln12 != ""){
        bln12 = item.bln12
    }
    item.totalBulanAlokasiDetail = 
    parseFloat(bln1) + 
    parseFloat(bln2) +
    parseFloat(bln3) +
    parseFloat(bln4) +
    parseFloat(bln5) +
    parseFloat(bln6) +
    parseFloat(bln7) +
    parseFloat(bln8) +
    parseFloat(bln9) +
    parseFloat(bln10) +
    parseFloat(bln11) + 
    parseFloat(bln12) 
    if(item.totalBulanAlokasiDetail == item.AnggaranAlokasiDetail2){
        warnaTotal.color = 'green';
    }
    if(item.totalBulanAlokasiDetail > item.AnggaranAlokasiDetail2){
        warnaTotal.color = 'red';
    }else if(item.totalBulanAlokasiDetail < item.AnggaranAlokasiDetail2){
        warnaTotal.color = 'green';
    }
    item.sisaAnggaran = parseFloat(item.AnggaranAlokasiDetail2) - parseFloat(item.totalBulanAlokasiDetail)
   
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