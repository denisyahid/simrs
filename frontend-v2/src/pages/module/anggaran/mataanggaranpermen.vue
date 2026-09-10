<template>
    <div class="column">
        <VCard>
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Mata Anggaran Permen</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton type="button" icon="feather:plus" :loading="isLoadingMap" color="primary" raised
                                        @click="openNowMapRBA()" > Mapping Rek Anggaran Ke Rek Permendagri
                                    </VButton>
                                    <VButton type="button" icon="feather:refresh-cw" :loading="isLoadingCari" color="warning" raised
                                        @click="fetchData()" > Refresh
                                    </VButton>
                                </div>
                            </div>
                        </div>


                    </div>
                    <DataTable paginator :rows="20" :loading="isLoadingTableAll" v-model:filters="filters" :value="dataSourceMAP" removableSort tableStyle="min-width: 50rem" :globalFilterFields="['id', 'kode','mataanggaranpermen']" filterDisplay="row">
                        <template #header>
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <VButton type="button" icon="feather:plus" :loading="isLoading" color="primary" raised
                                        @click="openNowDigit2()" > Master Rekening Permendagri Digit Ke 2
                                    </VButton>
                                </div>
                                <div class="column is-3">
                                    <VButton type="button" icon="feather:plus" :loading="isLoading" color="primary" raised
                                        @click="openNowDigit3()" > Master Rekening Permendagri Digit Ke 3
                                    </VButton>
                                </div>
                                <div class="column is-3">
                                    <VButton type="button" icon="feather:plus" :loading="isLoading" color="primary" raised
                                        @click="openNowDigit4()" > Master Rekening Permendagri Digit Ke 4
                                    </VButton>
                                </div>
                                <div class="column is-3">
                                    <VButton type="button" icon="feather:plus" :loading="isLoading" color="primary" raised
                                        @click="openNowDigit5()" > Master Rekening Permendagri Digit Ke 5
                                    </VButton>
                                </div>
                                <div class="column is-10">
                                    
                                </div>
                                <div class="column is-2">
                                    <span class="p-input-icon-left">
                                        <InputText v-model="filters['global'].value" placeholder="Search" />
                                    </span>
                                </div>
                            </div>
                        </template>
                        <Column field="id" header="ID" sortable style="width: 10%"></Column>
                        <Column field="kode" header="Kode" sortable style="width: 25%"></Column>
                        <Column field="mataanggaranpermen" header="Mata Anggaran Permen" sortable style="width: 50%"></Column>
                        <Column :exportable="false" header="#" style="width:15%">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="feather:trash" class="mr-3" color="danger"
                                    circle outlined raised v-tooltip-prime="'Hapus'"
                                    @click="dialogConfirmHapus(slotProps.data)">
                                </VIconButton>
                            </template>
                        </Column>
                        
                    </DataTable>

                </div>
                
            </div>
            
        </VCard>
        
    </div>

    <Dialog :loading="isLoadingDialog"  v-model:visible="MasterDigit2" :style="{width: '100%'}" header="Tambah Kode Rekening APBD Digit 2" :modal="true" class="p-fluid">
        <div class="columns is-multiline">
            <div class="column is-4">
                <VField>
                    <span>Kode Rekening APBD Digit 1(Akun & Kelompok)</span>
                    <VControl icon="feather:edit-3">
                        <VInput  type="text" v-model="item.digit1" placeholder="Kode Rekening APBD Digit 1 Contoh: X.X"
                            class="is-rounded_Z" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <span>Kode Rekening APBD Digit 2(Jenis)</span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.digit2" placeholder="Kode Rekening APBD Digit 2 Contoh: XX"
                            class="is-rounded_Z" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <span>Nama Rekening APBD Digit 2</span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.mataanggaranpermen" placeholder="Nama Rekening APBD Digit 2"
                            class="is-rounded_Z" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <DataTable paginator :rows="10" :loading="isLoadingTableDigit2"  :value="dataSourceTableDigit2" removableSort tableStyle="min-width: 50rem"  filterDisplay="row">
                    <!-- <Column field="id" header="ID" sortable style="width: 25%"></Column> -->
                    <Column field="kode" header="Kode" sortable style="width: 15%"></Column>
                    <Column field="mataanggaranpermen" header="Nama Rekening APBD Digit 2" sortable style="width: 25%"></Column>
                    
                </DataTable>
            </div>
        </div>
        <template #footer>
            <VButton icon="lnir lnir-arrow-left rem-100" rounded outlined color="danger" @click="batalPopupDigit()" style="margin-right:5px">
                Batal
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
                @click="simpanPopupDigit()"> Simpan
            </VButton>
        </template>
    </Dialog>
    <Dialog :loading="isLoadingDialog"  v-model:visible="MasterDigit3" :style="{width: '100%'}" header="Tambah Kode Rekening APBD Digit 3" :modal="true" class="p-fluid">
        <div class="columns is-multiline">
            <div class="column is-12">
                <VField class="is-autocomplete-select">
                    <span>Nama Rekening APBD Digit 2(jenis)</span>
                    <VControl icon="feather:search" class="prime-auto-select">
                        <Dropdown v-model="item.listDigit2" :options="d_listDigit2"
                            :optionLabel="'mataanggaranpermen'" class="is-stacked" placeholder="Pilih data"
                            style="width: 100%;" showClear :filter="false" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <span>Kode Rekening APBD Digit 3</span>
                    <VControl icon="feather:edit-3">
                        <VInput  type="text" v-model="item.digit2Disabled" placeholder="Kode Rekening APBD Digit 2" disabled
                            class="is-rounded_Z" />
                    </VControl>
                    
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <span> <br></span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.digit3" placeholder="Kode Rekening APBD Digit 3 Contoh: XX"
                            class="is-rounded_Z" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <VField>
                    <span>Nama Rekening APBD Digit 3</span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.mataanggaranpermen" placeholder="Mata Anggaran Permen"
                            class="is-rounded_Z" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <DataTable paginator :rows="10" :loading="isLoadingTableDigit3"  :value="dataSourceTableDigit3" removableSort tableStyle="min-width: 50rem"  filterDisplay="row">
                    <!-- <Column field="id" header="ID" sortable style="width: 25%"></Column> -->
                    <Column field="kode" header="Kode Rek" sortable style="width: 5%"></Column>
                    <Column field="mataanggaranpermen" header="Nama Rekening" sortable style="width: 25%"></Column>
                    <Column field="kodeup" header="Kode Head" sortable style="width: 5%"></Column>
                    <Column field="mataanggaranpermenup" header="Nama Rekening Head" sortable style="width: 25%"></Column>
                    
                </DataTable>
            </div>
        </div>
        <template #footer>
            <VButton icon="lnir lnir-arrow-left rem-100" rounded outlined color="danger" @click="batalPopupDigit()" style="margin-right:5px">
                Batal
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
                @click="simpanPopupDigit()"> Simpan
            </VButton>
        </template>
    </Dialog>
    <Dialog :loading="isLoadingDialog"  v-model:visible="MasterDigit4" :style="{width: '100%'}" header="Tambah Kode Rekening APBD Digit 4" :modal="true" class="p-fluid">
        <div class="columns is-multiline">
            <div class="column is-12">
                <VField class="is-autocomplete-select">
                    <span>Nama Rekening APBD Digit 3(Objek)</span>
                    <VControl icon="feather:search" class="prime-auto-select">
                        <Dropdown v-model="item.listDigit3" :options="d_listDigit3"
                            :optionLabel="'text'" class="is-stacked" placeholder="Pilih data"
                            style="width: 100%;" showClear :filter="false" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <span>Kode Rekening APBD Digit 4</span>
                    <VControl icon="feather:edit-3">
                        <VInput  type="text" v-model="item.digit3Disabled" placeholder="Kode Rekening APBD Digit 4" disabled
                            class="is-rounded_Z" />
                    </VControl>
                    
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <span> <br></span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.digit4" placeholder="Kode Rekening APBD Digit 4 Contoh: XX"
                            class="is-rounded_Z" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <VField>
                    <span>Nama Rekening APBD Digit 4</span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.mataanggaranpermen" placeholder="Nama Rekening APBD Digit 4"
                            class="is-rounded_Z" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <DataTable paginator :rows="10" :loading="isLoadingTableDigit4"  :value="dataSourceTableDigit4" removableSort tableStyle="min-width: 50rem"  filterDisplay="row">
                    <!-- <Column field="id" header="ID" sortable style="width: 25%"></Column> -->
                    <Column field="kode" header="Kode Rek" sortable style="width: 5%"></Column>
                    <Column field="mataanggaranpermen" header="Nama Rekening" sortable style="width: 25%"></Column>
                    <Column field="kodeup" header="Kode Head" sortable style="width: 5%"></Column>
                    <Column field="mataanggaranpermenup" header="Nama Rekening Head" sortable style="width: 25%"></Column>
                    
                </DataTable>
            </div>
        </div>
        <template #footer>
            <VButton icon="lnir lnir-arrow-left rem-100" rounded outlined color="danger" @click="batalPopupDigit()" style="margin-right:5px">
                Batal
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
                @click="simpanPopupDigit()"> Simpan
            </VButton>
        </template>
    </Dialog>
    <Dialog :loading="isLoadingDialog"  v-model:visible="MasterDigit5" :style="{width: '100%'}" header="Tambah Kode Rekening APBD Digit 5" :modal="true" class="p-fluid">
        <div class="columns is-multiline">
            <div class="column is-12">
                <VField class="is-autocomplete-select">
                    <span>Nama Rekening APBD Digit 4(Rincian Objek)</span>
                    <VControl icon="feather:search" class="prime-auto-select">
                        <Dropdown v-model="item.listDigit4" :options="d_listDigit4"
                            :optionLabel="'text'" class="is-stacked" placeholder="Pilih data"
                            style="width: 100%;" showClear :filter="false" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <span>Kode Rekening APBD Digit 4</span>
                    <VControl icon="feather:edit-3">
                        <VInput  type="text" v-model="item.digit4Disabled" placeholder="Kode Rekening APBD Digit 5" disabled
                            class="is-rounded_Z" />
                    </VControl>
                    
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <span> <br></span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.digit5" placeholder="Kode Rekening APBD Digit 5 Contoh: XXXX"
                            class="is-rounded_Z" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <VField>
                    <span>Nama Rekening APBD Digit 5</span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.mataanggaranpermen" placeholder="Mata Anggaran Permen"
                            class="is-rounded_Z" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <DataTable paginator :rows="10" :loading="isLoadingTableDigit5"  :value="dataSourceTableDigit5" removableSort tableStyle="min-width: 50rem"  filterDisplay="row">
                    <!-- <Column field="id" header="ID" sortable style="width: 25%"></Column> -->
                    <Column field="kode" header="Kode Rek" sortable style="width: 5%"></Column>
                    <Column field="mataanggaranpermen" header="Nama Rekening" sortable style="width: 40%"></Column>
                    <Column field="kodeup" header="Kode Head" sortable style="width: 5%"></Column>
                    <Column field="mataanggaranpermenup" header="Nama Rekening Head" sortable style="width: 40%"></Column>
                    
                </DataTable>
            </div>
        </div>
        <template #footer>
            <VButton icon="lnir lnir-arrow-left rem-100" rounded outlined color="danger" @click="batalPopupDigit()" style="margin-right:5px">
                Batal
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
                @click="simpanPopupDigit()"> Simpan
            </VButton>
        </template>
    </Dialog>
    <Dialog :loading="isLoadingDialog"  v-model:visible="dialogMapRBA" :style="{width: '100%'}" header="Mapping Rek RBA Ke Rek Permendagri" :modal="true" class="p-fluid">
        <div class="columns is-multiline">
            <div class="column is-12">
                <VField class="is-autocomplete-select">
                    <span>Nama Rekening APBD Digit 5(Sub Rincian Objek)</span>
                    <VControl icon="feather:search">
                        <AutoComplete v-model="item.mappingrek"
                            :suggestions="d_listDigit5" @complete="fetchMataPermen($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                            :field="'label'" placeholder="ketik nama Mata Anggaran Permendagri" @item-select="changeMataPermen($event)" />
                    </VControl>
                    <!-- <VControl icon="feather:search" class="prime-auto-select">
                        <Dropdown v-model="item.mappingrek" :options="d_listDigit5"
                            :optionLabel="'text'" class="is-stacked" placeholder="Pilih data"
                            style="width: 100%;" showClear :filter="false" />
                    </VControl> -->
                </VField>
            </div>
            <div class="column is-6">
                <p class="title-c">REKENING RBA YANG BELUM MASUK KE REK. PERMENDAGRI</p>
                <DataTable paginator :rows="10" :loading="isLoadingTableDigit5"  v-model:filters="filtersMap" :globalFilterFields="['kodemataanggaran', 'namamataanggaran']" :value="dataSourceRBABelum" removableSort tableStyle="min-width: 50rem"  filterDisplay="row">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2">
                                <span class="p-input-icon-left">
                                    <InputText v-model="filtersMap['global'].value" placeholder="Search" />
                                </span>
                            </div>
                            <div class="column is-10"></div>
                        </div>
                    </template>
                    <Column :exportable="false" header="#" style="width:15%">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="feather:copy" class="mr-3" color="primary"
                                circle outlined raised v-tooltip-prime="'Copy'"
                                @click="copy(slotProps.data)">
                            </VIconButton>
                        </template>
                    </Column>
                    <Column field="id" header="Id" sortable style="width: 5%"></Column>
                    <Column field="kodemataanggaran" header="kode" sortable style="width: 20%"></Column>
                    <Column field="namamataanggaran" header="Mata Anggaran" sortable style="width: 30%"></Column>
                </DataTable>
            </div>
            <div class="column is-6">
                <p class="title-c">REKENING RBA YANG SUDAH MASUK KE REK. PERMENDAGRI</p>
                <DataTable paginator :rows="10" :loading="isLoadingTableDigit5"  :value="dataSourceRBASudah" removableSort tableStyle="min-width: 50rem"  filterDisplay="row">
                    <!-- <Column field="id" header="ID" sortable style="width: 25%"></Column> -->
                    <Column field="id" header="Id" sortable style="width: 5%"></Column>
                    <Column field="kodemataanggaran" header="kode" sortable style="width: 20%"></Column>
                    <Column field="namamataanggaran" header="Mata Anggaran" sortable style="width: 30%"></Column>
                </DataTable>
            </div>
        </div>
    </Dialog>
    <ConfirmDialog/>
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
import AutoComplete from 'primevue/autocomplete';
const confirm = useConfirm();
useHead({
  title: 'Mata Anggaran - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let d_listDigit2:any = ref([])
let d_listDigit3:any = ref([])
let d_listDigit4:any = ref([])
let d_listDigit5:any = ref([])
let isPlaceLoad: any = ref(false)
let dataSource: any = ref([])
let dataSourceMAP: any = ref([])
let dataSourceTableDigit2: any = ref([])
let dataSourceTableDigit3: any = ref([])
let dataSourceTableDigit4: any = ref([])
let dataSourceTableDigit5: any = ref([])
let dataSourceRBABelum: any = ref([])
let dataSourceRBASudah:any = ref([])
const item: any = reactive({})
let  MasterDigit2: any = ref(false);
let  MasterDigit3: any = ref(false);
let  MasterDigit4: any = ref(false);
let  MasterDigit5: any = ref(false);
let  dialogMapRBA: any = ref(false);
let  jeniskode: any = ref(false);
let  konsolidasi: any = ref(false);
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})

const filtersMap = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
let isLoadingDialog = ref(false)
let isLoadingCari = ref(false)
let isLoadingTableAll = ref(false)
let isLoadingTableDigit2 = ref(false)
let isLoadingTableDigit3 = ref(false)
let isLoadingTableDigit4 = ref(false)
let isLoadingTableDigit5 = ref(false)
let isLoadingSimpan = ref(false)
let isLoadingMap = ref(false)
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
loadCombo()
loadData()

async function fetchData() {
    loadData()
    loadCombo()

}

const changeMataPermen = (e: any) => {
    isLoadingTableDigit5.value = true
    useApi().get('perencanaan/get-data-rba-belum?permen='+item.mappingrek.value).then((response)=>{
        isLoadingTableDigit5.value = false

        dataSourceRBABelum.value = response.data
        dataSourceRBASudah.value = response.detail
    })

}
const fetchMataPermen = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/mataanggaranpermen_m?select=id,mataanggaranpermen&param_search=mataanggaranpermen&query=${filter.query}&limit=10`
    ).then((response) => {
        d_listDigit5.value = response
    })
}
watch(
    () => item.listDigit2,
    (newValue, oldValue) => {
        if(item.listDigit2 != undefined){
            item.digit2Disabled = item.listDigit2.kode + "."
        }
    }
)

watch(
    () => item.listDigit3,
    (newValue, oldValue) => {
        if(item.listDigit3 != undefined){
            item.digit3Disabled = item.listDigit3.kode + "."
        }
    }
)

watch(
    () => item.listDigit4,
    (newValue, oldValue) => {
        if(item.listDigit4 != undefined){
            item.digit4Disabled = item.listDigit4.kode + "."
        }
    }
)
watch(
    () => item.mappingrek,
    (newValue, oldValue) => {
        // console.log(newValue, oldValue);
        
        // if(item.mappingrek != undefined){
        //     isLoadingTableDigit5.value = true
        //     useApi().get('perencanaan/get-data-rba-belum?permen='+item.mappingrek.value).then((response)=>{
        //         isLoadingTableDigit5.value = false

        //         dataSourceRBABelum.value = response.data
        //         dataSourceRBASudah.value = response.detail
        //     })
        // }
    }
)

async function loadCombo(){
    await useApi().get(
        `anggaran/get-combo`
    ).then((response) => {
        d_listDigit2.value = response.digit2
        d_listDigit3.value = response.digit3
        d_listDigit4.value = response.digit4
        // d_listDigit5.value = response.digit5

    })
}

async function loadData() {
    isLoadingCari.value = true
    isLoadingTableAll.value = true
    await useApi().get('anggaran/get-mata-anggaran-permen').then((response) => {
        isLoadingCari.value = false
        isLoadingTableAll.value = false
        dataSourceMAP.value = response.data
        dataSourceTableDigit2.value = response.dataDigit2
        dataSourceTableDigit3.value = response.dataDigit3
        dataSourceTableDigit4.value = response.dataDigit4
        dataSourceTableDigit5.value = response.dataDigit5
    })
}

async function openNowDigit2(){
    MasterDigit2.value = true
    item.div = 2
    item.id = ''
    
}

async function openNowDigit3(){
    MasterDigit3.value = true
    item.div = 3
    item.id = ''
    
}

async function openNowDigit4(){
    MasterDigit4.value = true
    item.div = 4
    item.id = ''
    
}

async function openNowDigit5(){
    MasterDigit5.value = true
    item.div = 5
    item.id = ''
    
}

async function batalPopupDigit() {
    item.div = ''
    item.id = ''
    item.digit1 = undefined 
    item.digit2 = undefined
    item.digit3 = undefined
    item.digit4 = undefined
    item.digit5 = undefined
    item.mataanggaranpermen = undefined
    MasterDigit2.value = false
    MasterDigit3.value = false
    MasterDigit4.value = false
    MasterDigit5.value = false
    item.listDigit2 = undefined
    item.digit2Disabled = undefined
    item.listDigit3 = undefined
    item.digit3Disabled = undefined
    item.listDigit4 = undefined
    item.digit4Disabled = undefined
    item.listDigit5 = undefined
    item.digit5Disabled = undefined
}

async function simpanPopupDigit() {
    item.mataanggaranpermenfk = null
    var kode = null
    if(item.div == 2){
        if(item.digit1 == undefined || item.digit2 == undefined || item.mataanggaranpermen == undefined){
            H.alert('error', 'Mohon isi semua kolom dengan lengkap!')
            return
        }
        kode = item.digit1 + '.'+ item.digit2
        
    }
    if(item.div == 3){
        if(item.digit3 == undefined || item.mataanggaranpermen == undefined){
            H.alert('error', 'Mohon isi semua kolom dengan lengkap!')
            return
        }
        kode = item.digit3
        item.mataanggaranpermenfk = item.listDigit2.id
    }
    
    if(item.div == 4){
        if(item.digit4 == undefined || item.mataanggaranpermen == undefined){
            H.alert('error', 'Mohon isi semua kolom dengan lengkap!')
            return
        }
        kode = item.digit4
        item.mataanggaranpermenfk = item.listDigit3.id
    }
    
    if(item.div == 5){
        if(item.digit5 == undefined || item.mataanggaranpermen == undefined){
            H.alert('error', 'Mohon isi semua kolom dengan lengkap!')
            return
        }
        kode = item.digit5
        item.mataanggaranpermenfk = item.listDigit4.id
    }
    var objSave = {
        id: item.id,
        div: item.div,
        kode: kode,
        mataanggaranpermen: item.mataanggaranpermen,
        mataanggaranpermenfk: item.mataanggaranpermenfk
    }
    isLoadingSimpan.value = true
    await useApi().post('anggaran/save-mata-anggaran-permen', objSave).then((response)=> {
        item.id = ''
        item.digit1 = undefined 
        item.digit2 = undefined
        item.digit3 = undefined
        item.digit4 = undefined
        item.digit5 = undefined
        item.mataanggaranpermen = undefined
        loadData()
        isLoadingSimpan.value = false

    })
    .catch((e => {
        isLoadingSimpan.value = false
    }))
}

async function kosongkan() {
    item.id = ''
    item.div = null
}

const hapusMataAnggaran = async (e: any) => {
    var objHapus = {
            id: e.id,
        }
        await useApi().post('anggaran/hapus-mata-anggaran-permen', objHapus).then((response)=> {
            loadData()
        })
}
const dialogConfirmHapus = async (e: any) => {
    console.log(e);
    
    confirm.require({
    message: 'Apakah anda yakin akan Menghapus Mata Anggaran Permen ini?',
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
async function openNowMapRBA() {
    isLoadingMap.value = true
    await useApi().get('perencanaan/get-data-rba-belum').then((response)=>{
        isLoadingMap.value = false
        dataSourceRBABelum.value = response.data
        dataSourceRBASudah.value = response.detail
    })

    dialogMapRBA.value = true 
}
const copy = async (e: any) => {
    if(item.mappingrek == undefined){
        H.alert('error','Masukan Nama Rekening APBD Digit ke 5')
        return
    }
    var objSave = { 
        idanggaran: e.id,
        idpermen: item.mappingrek.value,
    }
                    
                    
isLoadingTableDigit5.value = true
    await useApi().post('perencanaan/copy-permen', objSave)
isLoadingTableDigit5.value = false

    const response = await useApi().get('perencanaan/get-data-rba-belum?permen=' + item.mappingrek.value)
    dataSourceRBABelum.value = response.data
    dataSourceRBASudah.value = response.detail

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