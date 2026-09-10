<template>
    <VCard>
    <div class="form-layout">
        <div class="form-outer">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <ConfirmDialog/>
                    <div class="left">
                        <h3>Entry Lock RBA</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton type="button" icon="feather:search" :loading="isLoadingSearch" color="primary" raised
                                @click="searchData()" v-if="!isSettingRBA"> Cari
                            </VButton>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-12">
                <div class="columns is-multiline">
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
                        <VField class="is-autocomplete-select">
                            <span>Tahap</span>
                            <VControl icon="feather:search" class="prime-auto-select">
                                <Dropdown v-model="item.tahapdetail" :options="d_Tahap"
                                    :optionLabel="'tahap'" class="is-rounded" placeholder="Pilih data"
                                    style="width: 100%;" showClear :filter="false" :loading="isLoadingCombo"/>
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>

            <div class="column is-12">
               <DataTable paginator :rows="20"  scrollable :value="dataGrid" v-model:filters="filters" removableSort tableStyle="min-width: 50rem" :globalFilterFields="['text']" filterDisplay="row">
               <template #header>
                    <div class="columns is-multiline">
                        <div class="column is-9"></div>
                        <div class="column is-3">
                            <span class="p-input-icon">
                                <InputText v-model="filters['global'].value" placeholder="Search" />
                            </span>
                        </div>
                    </div>
                </template>
                <Column :exportable="false" header="Lock" style="width:5%" frozen>
                    <template #body="slotProps">
                        <VButton type="button" icon="feather:arrow-right" :loading="isLoadingSimpan" :color="slotProps.data.colorLock" raised class="is-rounded"
                            @click="SimpanLock(slotProps.data)" > {{slotProps.data.islockrba == false || slotProps.data.islockrba == null ? 'Unlock' : 'Lock' }}
                        </VButton>
                    </template>
                </Column>
                    <Column field="nomor" header="Nomor" sortable style="width: 10%"></Column>
                    <Column field="tahap" header="Tahap" sortable style="width: 10%"></Column>
                    <Column field="text" header="Nama Kode dan Sub Sub Kegiatan" sortable style="width: 30%"></Column>
                    <Column field="tahun" header="Tahun Anggaran" sortable style="width: 10%"></Column>
                </DataTable>
            </div>
        </div>
    </div>
    </VCard>
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
  title: 'Entry Lock RBA - ' + import.meta.env.VITE_PROJECT,
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = reactive({})
// const colorLock: any = reactive('danger')
const d_Tahun: any = ref([])

let isLoadingSimpan: any = ref(false)
let isLoadingTableDataGrid: any = ref(false)
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
let d_Tahap: any = ref([])

let dataGrid: any =ref([])
loadCombo()
async function loadCombo() {
    for (let i = parseFloat(H.formatDate(new Date(),'YYYY')) - 5; i <= parseFloat(H.formatDate(new Date(),'YYYY')) + 5; i++) {
        d_Tahun.value.push({
            id: i, tahun: i
        })
    }
    await useApi().get(
        `anggaran/get-combo`
    ).then((response) => {
        d_Tahap.value = response.tahap
        d_listJenisBelanja.value = response.jenisbelanja
        d_listDiv.value = response.kelompokanggaran
        d_listAsalProduk.value = response.asalproduk
    })
}

async function loadData() {
    isLoadingSearch.value = true
var tahap = ''
if(item.tahapdetail != undefined){
    tahap = item.tahapdetail.id
}
    await useApi().get('perencanaan/get-lock-entry-rba?tahun='+ moment(item.tahun).format('YYYY') + "&tahap="+tahap).then((response) => {
        dataGrid.value = response.data
        for (let i = 0; i < dataGrid.value.length; i++) {
            const element = dataGrid.value[i];
            element.nomor = i+1 
            if(element.islockrba == true){
                element.colorLock = 'danger'
            }else{
                element.colorLock = 'success'
            }
        }
        isLoadingSearch.value = false

    })
}

async function searchData() {
    if(item.tahun == undefined){
        H.alert('error','Pilih Tahun!')
        return
    }
    if(item.tahapdetail == undefined){
        H.alert('error','Pilih Tahap Anggaran!')
        return
    }
    loadData()

}
async function SimpanLock(data){
    var objSave = {
        "data": data,
    }
    isLoadingSimpan.value = true
    await useApi().post('perencanaan/save-lock-entry-rba', objSave)
    await loadData();
    isLoadingSimpan.value = false

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