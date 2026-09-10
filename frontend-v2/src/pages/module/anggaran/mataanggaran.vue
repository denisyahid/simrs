<template>
    <div class="column">
        <VCard>
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Mata Anggaran</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton type="button" icon="feather:search" :loading="isLoadingCari" color="primary" raised
                                        @click="fetchData()" > Cari
                                    </VButton>
                                    
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="form-body">
                        <div class="form-fieldset">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField class="is-autocomplete-select">
                                        <VLabel>Div</VLabel>
                                        <VControl icon="feather:search" class="prime-auto-select">
                                            <Dropdown v-model="item.divkonsolidasi" :options="d_listDivKonsolidasi"
                                                :optionLabel="'div'" class="is-stacked" placeholder="Pilih data"
                                                style="width: 100%;" showClear :filter="false" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </VCard>
    </div>
    <ConfirmDialog/>

    <div class="column">
        <VCard>
            <div class="form-layout">
                <div class="form-outer">
                    <DataTable :loading="isLoadingTable" paginator :rows="15" v-model:filters="filters" :value="products" removableSort tableStyle="min-width: 50rem" :globalFilterFields="['id', 'kodemataanggaran','namamataanggaran']" filterDisplay="row">
                        
                        <template #header>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VButton type="button" icon="feather:plus" :loading="isLoading" color="primary" raised
                                        @click="openNow()" > Tambah
                                    </VButton>
                                </div>
                                <div class="column is-6">
                                    <span class="p-input-icon-left">
                                        <InputText v-model="filters['global'].value" placeholder="Search" />
                                    </span>
                                </div>
                            </div>
                        </template>
                        <Column field="id" header="ID" sortable style="width: 25%"></Column>
                        <Column field="kodemataanggaran" header="Kode" sortable style="width: 25%"></Column>
                        <Column field="namamataanggaran" header="Mata Anggaran" sortable style="width: 25%"></Column>
                        <Column :exportable="false" header="#" style="width:30px">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="feather:edit" class="mr-3" color="warning"
                                    circle outlined raised v-tooltip-prime="'Edit'"
                                    @click="editMataAnggaran(slotProps.data)">
                                </VIconButton>
                                
                                <VIconButton type="button" icon="feather:trash" class="mr-3" color="danger"
                                    circle outlined raised v-tooltip-prime="'Hapus'"
                                    @click="dialogConfirmHapus(slotProps.data)">
                                </VIconButton>
                            </template>
                        </Column>
                    </DataTable>

                </div>
                <Dialog :loading="isLoadingDialog" v-model:visible="productDialog" :style="{width: '450px'}" header="Tambah Mata Anggaran" :modal="true" class="p-fluid">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VField class="is-autocomplete-select">
                                <VLabel>Div</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.divkonsolidasiInput" :options="d_listDivKonsolidasi"
                                        :optionLabel="'div'" class="is-stacked" placeholder="Pilih data"
                                        style="width: 100%;" showClear :filter="false" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VLabel class="required-field">Kode ({{item.kodeDiv}})</VLabel>
                                <VControl icon="feather:user">
                                    <VInput type="text" v-model="item.kodeAddMT" placeholder="Kode"
                                        class="is-rounded_Z" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VLabel class="required-field">Mata Anggaran</VLabel>
                                <VControl icon="feather:user">
                                    <VInput type="text" v-model="item.keteranganAdd" placeholder="Mata Anggaran"
                                        class="is-rounded_Z" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                                    <VField class="is-autocomplete-select" v-if="jeniskode">
                                        <VLabel>Jenis Anggaran</VLabel>
                                        <VControl  icon="feather:search" class="prime-auto-select">
                                            <Dropdown v-model="item.jenisanggaran" :options="d_jenisAnggaran"
                                                :optionLabel="'keterangan'" class="is-stacked" placeholder="Pilih data"
                                                style="width: 100%;" showClear :filter="false" />
                                        </VControl>
                                    </VField>
                                </div>
                    </div>
                    <template #footer>
                        <VButton icon="lnir lnir-arrow-left rem-100" rounded outlined color="danger" @click="batalPopUpTambahMT()" style="margin-right:5px">
                            Batal
                        </VButton>
                        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" 
                            @click="SimpanPopUpTambahMT()"> Simpan
                        </VButton>
                    </template>
                </Dialog>
            </div>
            
        </VCard>
        
    </div>
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

import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';
const confirm = useConfirm();
useHead({
  title: 'Mata Anggaran - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let d_listDivKonsolidasi: any = ref([])
let d_jenisAnggaran:any = ref([])
let isPlaceLoad: any = ref(false)
let dataSource: any = ref([])
let products: any = ref([])
const item: any = reactive({})
let  productDialog: any = ref(false);
let  jeniskode: any = ref(false);
let  konsolidasi: any = ref(false);
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
let isLoadingDialog = ref(false)
let isLoadingCari = ref(false)
let isLoadingTable = ref(false)
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
d_listDivKonsolidasi = [
    { id: 1, div: "Div: 1" },
    { id: 2, div: "Div: 2" },
    { id: 3, div: "Div: 3" },
    { id: 4, div: "Div: 4" },
];
async function openNow() {
    item.kodeDiv = ''
    item.id = ''
    item.kodeAddMT = undefined
    item.keteranganAdd = undefined
    jeniskode.value = false
    item.jenisanggaran = undefined
    konsolidasi= true
    item.konsolidasi2 = undefined
    item.konsolidasi3 = undefined
    item.konsolidasi4 = undefined
    item.konsolidasi5 = undefined
    productDialog.value = true;
};
function occurrences(string, subString, allowOverlapping) {

string += "";
subString += "";
if (subString.length <= 0) return (string.length + 1);

var n = 0,
    pos = 0,
    step = allowOverlapping ? 1 : subString.length;

while (true) {
    pos = string.indexOf(subString, pos);
    if (pos >= 0) {
        ++n;
        pos += step;
    } else break;
}
return n;
}
watch(
    () => item.kodeAddMT,
    (newValue, oldValue) => {
        item.kodeDiv = ''
        var yangdicount = item.kodeAddMT
        var buatdiv = ''
        var titikdiv = ''
        if( item.kodeAddMT !== undefined){
            buatdiv = item.kodeAddMT.replace('.', '')
            titikdiv = item.kodeAddMT.split('.').filter(Boolean)
        }
        var count = occurrences(yangdicount, ".");
        if(count == 2){
            jeniskode.value = true;
        } else{
            jeniskode.value = false;
        }

        if(item.divkonsolidasiInput.id == 1){
            item.kodeDiv = 'Div 1'
        }else if(item.divkonsolidasiInput.id == 2){
            item.kodeDiv = 'Div 2'
        }else if(item.divkonsolidasiInput.id == 3){
            item.kodeDiv = 'Div 3'
        } else{
            item.kodeDiv = 'Div 4'
        }
        if (titikdiv.length> 5) {
            // Jika melebihi panjang maksimal, potong string menjadi 5 karakter
            H.alert('error','Maksimal input kode Anggaran!')
            titikdiv = titikdiv.slice(0, 5)
            item.kodeAddMT = titikdiv.join('.')
        }
    }
)
loadData()
loadCombo()
async function loadData() {
    isLoadingTable.value = true
    var kdiv = null
    if(item.divkonsolidasi != undefined){
        kdiv = "div="+ item.divkonsolidasi.id
    }
    await useApi().get('anggaran/get-mata-anggaran?'+kdiv).then((response) => {
        isLoadingCari.value = false
        isLoadingTable.value = false
        products.value = response.data
    })
}
async function fetchData() {
    isLoadingCari.value = true
    loadData()
}
async function SimpanPopUpTambahMT() {
    var a = item.kodeAddMT.split('.')
    if(a[a.length - 1] == ''){
        H.alert('error', 'Hapus titiknya terlebih dahulu!')
        return
    }
    isLoadingDialog.value = true
    var jmlkode = item.kodeAddMT.length
    var divS = 0;
    var IDD = undefined

    if(item.id == undefined){
        IDD = ''
    }else{
        IDD = item.id
    }
    if(jmlkode == 7){
        divS = 1
    }else 
    if(jmlkode == 12){
        divS = 2
    }else if(jmlkode == 15){
        divS = 3
    }else if(jmlkode > 15){
        divS = 4
    }

    var jenisanggaran = null
    if (item.jenisanggaran != undefined) {
        jenisanggaran = item.jenisanggaran.id
    }

    var konsolidasi5 = null
    if (item.konsolidasi5 != undefined) {
        konsolidasi5 = item.konsolidasi5.id
    }

    
    var objSave =
        {
            id: IDD,
            reportdisplay: null,
            kodemataanggaran: item.kodeAddMT,
            namamataanggaran: item.keteranganAdd,
            jenisanggaran: jenisanggaran,
            keterangan: null,
            kodeexternal: null,
            namaexternal: null,
            div: item.divkonsolidasiInput.id,
            mataanggaranpermenfk: konsolidasi5,
        }
        await useApi().post('perencanaan/save-mata-anggaran',objSave).then((response) => {
            isLoadingDialog.value = false
            item.id = ''
            item.kodeAddMT = undefined
            item.keteranganAdd = undefined
            jeniskode.value = false
            item.jenisanggaran = undefined
            konsolidasi= true
            item.konsolidasi2 = undefined
            item.konsolidasi3 = undefined
            item.konsolidasi4 = undefined
            item.konsolidasi5 = undefined
            productDialog.value = false;
            loadData()
        })
   
}
async function loadCombo(){
    await useApi().get(
        `anggaran/get-combo`
    ).then((response) => {
        d_jenisAnggaran.value = response.jenisanggaran
    })
}
async function batalPopUpTambahMT() {
    item.id = ''
    item.kodeAddMT = undefined
    item.keteranganAdd = undefined
    jeniskode.value = false
    item.jenisanggaran = undefined
    konsolidasi= true
    item.konsolidasi2 = undefined
    item.konsolidasi3 = undefined
    item.konsolidasi4 = undefined
    item.konsolidasi5 = undefined
    productDialog.value = false;
}
const editMataAnggaran = async (e: any) => {
    d_listDivKonsolidasi = [
        { id: 1, div: "Div: 1" },
        { id: 2, div: "Div: 2" },
        { id: 3, div: "Div: 3" },
        { id: 4, div: "Div: 4" },
    ];
    item.id = e.id
    item.kodeAddMT = e.kodemataanggaran
    item.keteranganAdd = e.namamataanggaran
    // for (let i = parseFloat(H.formatDate(new Date(),'YYYY')) - 5; i <= parseFloat(H.formatDate(new Date(),'YYYY')) + 5; i++) {
    //     d_Tahun.value.push({
    //         id: i, tahun: i
    //     })
    // }
    item.divkonsolidasiInput = {
        id: e.div_id, div: e.div
    }
    if(e.id_jenis != null){
        jeniskode.value = true
        item.jenisanggaran = {keterangan: e.keterangan, id: e.id_jenis
        }

    }
    productDialog.value = true;

}
const hapusMataAnggaran = async (e: any) => {
    var objHapus = {
            id: e.id,
        }
        await useApi().post('anggaran/hapus-mata-anggaran', objHapus).then((response)=> {
            isLoadingDialog.value = false
            item.id = ''
            item.kodeAddMT = undefined
            item.keteranganAdd = undefined
            jeniskode.value = false
            item.jenisanggaran = undefined
            konsolidasi= true
            productDialog.value = false;
            loadData()
        })
}
const dialogConfirmHapus = async (e: any) => {
    confirm.require({
    message: 'Apakah anda yakin akan Menghapus Mata Anggaran ini?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
        hapusMataAnggaran(e)
    },
    reject: () => {
        loadData()
     },
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