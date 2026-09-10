<template>
    <ConfirmDialog/>
<Dialog v-model:visible="visible" header="Cetak Surat Permintaan Pembayaran" :style="{ width: '50rem' }" :position="position" :modal="true" :draggable="false">
    <div class="columns is-multiline">
        <div class="column is-3">
            <VButton type="button" icon="feather:arrow-right" :loading="isSearch" color="info" raised class="is-rounded"
                @click="cetakSurat(1)" > Surat Pengantar
            </VButton>
        </div>
        <div class="column is-5">
            <VButton type="button" icon="feather:arrow-right" :loading="isSearch" color="info" raised class="is-rounded"
                @click="cetakSurat(2)" > Surat Pertanggung Jawaban
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
                        <h3>Surat Perintah Membayar</h3>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField>
                            <VLabel>Periode</VLabel>
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
                <DataTable paginator :rows="20" :value="dataSourceCariSPM" tableStyle="min-width: 50rem" v-model:filters="filters" scrollable scrollHeight="400px">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-6"></div>
                            <div class="column is-6">
                                <span class="p-input-icon-left">
                                    <InputText v-model="filters['global'].value" placeholder="Search" class="is-rounded" />
                                </span>
                            </div>
                        </div>
                    </template>
                    <Column :exportable="false" header="#" style="min-width:15px">
                        <template #body="slotProps">
                            <div class="columns">
                                <div class="column is-6">
                                    <VButton type="button" icon="feather:printer" :loading="isSearch" color="warning" raised class="is-rounded"
                                        @click="openPosition(slotProps.data)" > Cetak
                                    </VButton>
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column v-for="col of columnCariSPM" :field="col.field" :header="col.title" :style="`min-width:${col.width}`">
                        <template #body="slotProps">
                            <span>{{ col.template != undefined ?
                            H.formatRupiah(slotProps.data[col.field], '')
                            : slotProps.data[col.field] }}</span>
                        </template>
                    </Column>
                    <Column :exportable="false" header="#" style="min-width:15px">
                        <template #body="slotProps">
                            <VButton type="button" icon="feather:trash" :loading="isDeleteSPP" color="danger" raised class="is-rounded"
                                @click="DeleteSPM(slotProps.data)" > Hapus
                            </VButton>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </div>
</VCard>
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
  title: 'SPM - ' + import.meta.env.VITE_PROJECT,
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
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

let dataSourceCariSPM: any = ref(false)
const loadData = async()=> {
    await useApi().get(`perencanaan/get-data-spm?tglAwal=${moment(item.filterTgl.start).format('YYYY-MM-DD')}&tglAkhir=${moment(item.filterTgl.end).format('YYYY-MM-DD')}`).then((response)=> {
        dataSourceCariSPM.value = response.data
    })
}
loadData()
const columnCariSPM = [
    {
        "field": "nospm",
        "title": "No SPM",
        "width": "80px"
    },
    {
        "field": "asalproduk",
        "title": "Sumber Dana",
        "width": "80px"
    },
    {
        "field": "jenisbukti",
        "title": "Jenis Bukti",
        "width": "80px"
    },
    {
        "field": "untukpengeluaran",
        "title": "Untuk",
        "width": "80px"
    },
    {
        "field": "status",
        "title": "Status",
        "width": "100px"
    },
    {
        "field": "nospp",
        "title": "No SPP",
        "width": "80px"
    },
    {
        "field": "tglspm",
        "title": "Tgl SPM",
        "width": "80px"
    },
    {
        "field": "tglsah",
        "title": "Tgl Sah",
        "width": "80px"
    },
    {
        "field": "nilaispm",
        "title": "Nilai SPM",
        "width": "80px"
    },
    {
        "field": "noreg",
        "title": "No Reg",
        "width": "80px"
    },
    
]
const DeleteSPM = async(dataItem)=> {
    
    
    // if(dataItem.objectbkufk != null){
    //     toastr.error('sudah dibuat BKU, tidak bisa dihapus')
    //     return
    // }
    await confirm.require({
    message: 'Yakin ingin menghapus data ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
        var objSave =
        {
            norec: dataItem.norec,
        }
         useApi().post('perencanaan/delete-spm', objSave).then((response)=> {

        })
    },
    reject: () => {
        loadData()
        isLoading.value = false
    },
    })
    
}
const position = ref('center');
const visible = ref(false);
let cetakS: any =ref({})
const openPosition = (dataItem) => {
    cetakS.value = dataItem
    position.value = 'left';
    visible.value = true;
}
const cetakSurat = async(dataItem)=> {
    var data = cetakS.value.nilaispm.split(".");
    var data2 = cetakS.value.nilaispm.split(".");
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
        H.printBlade(`report/get-cetak-pengantar-spm?norec=${cetakS.value.norec}&terbilang=${terbilang}&terbilangspd=${terbilangspd}`, '_blank');
    }else if(dataItem == 2){
        H.printBlade(`report/get-cetak-pertanggungjawaban-spm?norec=${cetakS.value.norec}&terbilang=${terbilang}&terbilangspd=${terbilangspd}`, '_blank');
    }else{
        H.printBlade(`report/get-cetak-rincian-spm?norec=${cetakS.value.norec}&terbilang=${terbilang}&terbilangspd=${terbilangspd}`, '_blank');
    }
    visible.value = false;
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