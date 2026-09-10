<template>
    <VCard>
        <div class="form-layout">
            <div class="form-outer">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <ConfirmDialog/>
                        <div class="left">
                            <h3>Anggaran Kas dan Rencana Jadwal Tiap Sub Kegiatan</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton type="button" icon="feather:printer" :loading="isLoading" color="primary" raised
                                    @click="CetakRBADetail()" v-if="!isSettingRBA"> Cetak
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
                                    <Dropdown v-model="item.tahundetail" :options="d_Tahun" :optionLabel="'tahun'"
                                        class="is-rounded" placeholder="Tahun" style="width: 100%;" :filter="true"
                                        showClear :loading="isLoadingCombo"/>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class="is-autocomplete-select">
                                <span>(Tahap)</span>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.tahapdetail" :options="d_Tahap"
                                        :optionLabel="'tahap'" class="is-rounded" placeholder="Pilih data"
                                        style="width: 100%;" showClear :filter="false" :loading="isLoadingCombo"/>
                                </VControl>
                            </VField>
                        </div>
                        <!-- <div class="column is-2">
                            <VField class="is-autocomplete-select">
                                <span>(Terhadap)</span>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.terhadapdetail" :options="d_Tahap"
                                        :optionLabel="'tahap'" class="is-rounded" placeholder="Pilih data"
                                        style="width: 100%;" showClear :filter="false" :loading="isLoadingCombo"/>
                                </VControl>
                            </VField>
                        </div> -->
                        <div class="column is-4">
                            <VField class="is-autocomplete-select">
                                <span>Sub Kegiatan</span>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.kegiatandetail" :options="d_subKegiatan" :optionLabel="'keterangan'"
                                        class="is-rounded" placeholder="Sub Kegiatan" :loading="isLoadingCombo" style="width: 100%;" :filter="true" @change="changeKegiatan()"
                                        showClear  />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField class="is-autocomplete-select">
                                <span>Sub Sub Kegiatan</span>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.subkegiatandetail" :options="d_subSubKegiatan" :optionLabel="'keterangan'"
                                        class="is-rounded" placeholder="Sub sub Kegiatan" style="width: 100%;" :filter="true" :loading="isLoadingCombo"
                                        showClear />
                                </VControl>
                            </VField>
                        </div>
                    </div>
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
  title: 'Anggaran Kas dan Rencana Jadwal Tiap Sub Kegiatan - ' + import.meta.env.VITE_PROJECT,
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = reactive({})
const d_Tahun: any = ref([])
let d_Tahap: any = ref([])
let isLoadingCombo: any = ref(false)
let d_subSubKegiatan: any = ref([])
let d_subKegiatan: any = ref([])
let d_listTahapKegiatan: any = ref([])

loadCombo()
for (let i = parseFloat(H.formatDate(new Date(),'YYYY')) - 5; i <= parseFloat(H.formatDate(new Date(),'YYYY')) + 5; i++) {
    d_Tahun.value.push({
        id: i, tahun: i
    })
}
loadData()

async function loadCombo(){
    isLoadingCombo.value = true
    await useApi().get(
        `anggaran/get-combo?`
    ).then((response) => {
        d_Tahap.value = response.tahap
        d_listTahapKegiatan.value = response.tahap
        d_subKegiatan.value = response.kegiatancombo
    })
    isLoadingCombo.value = false
}
async function loadData() {
    
}

const changeKegiatan = async (filter: any) => {
    await useApi().get("perencanaan/get-detail-sub-kegiatan?kegiatan=" + item.kegiatandetail.id + "&tahap=" + item.tahapdetail.id + "&tahun=" + moment(item.tahundetail).format('YYYY')).then((e) => {
        d_subSubKegiatan.value = e.data
    });
}
const CetakRBADetail = async(filter: any)=>{
var tahap = ""
if (item.tahapdetail != undefined) {
    tahap = item.tahapdetail.id
}

var terhadap = ""
if (item.terhadapdetail != undefined) {
    terhadap = item.terhadapdetail.id
}

var tahun = ""
if (item.tahundetail != undefined) {
    tahun = item.tahundetail
}
var kode = ""
var subsubkegiatan = ""
if (item.subkegiatandetail != undefined) {
    subsubkegiatan = item.subkegiatandetail.id
    kode = "&kode=" + item.subkegiatandetail.kode
}
var subkegiatan = ""
if (item.kegiatandetail != undefined) {
    subkegiatan = item.kegiatandetail.id
}

if (item.terhadapdetail != undefined) {
    window.open(baseTransaksi + "report/get-cetak-angkas-jadwal?tahun=" + moment(tahun).format('YYYY') + "&subsubkegiatan=" + subsubkegiatan + "&tahap=" + tahap + "&terhadap=" + terhadap + "&tglcetak=" + moment(item.tglCetak4).format('YYYY-MM-DD HH:mm')+kode, '_blank');
} else if(item.terhadapdetail == undefined){
  H.printBlade("report/get-cetak-angkas-jadwal?tahun=" +moment(tahun).format('YYYY')+ "&subsubkegiatan=" + subsubkegiatan+"&subkegiatan=" + subkegiatan + "&tahap="+tahap+"&tglcetak="+moment(item.tglCetak4).format('YYYY-MM-DD HH:mm'), '_blank');
}


// if (item.terhadapSumberDana == undefined || tahap == terhadap) {
//     window.open(baseTransaksi + "report/get-cetak-rekap-SumberDana-anggaran-sebelum?tahap=" + tahap + "&tahun=" + moment($scope.iitem.tahunSumberDana).format('YYYY'), '_blank');
// } else if ($scope.item.terhadapSumberDana != undefined || tahap != terhadap) {
//     window.open(baseTransaksi + "report/get-cetak-rekap-SumberDana-anggaran?tahap=" + tahap + "&tahun=" + moment($scope.item.tahunSumberDana).format('YYYY') + "&terhadap=" + terhadap, '_blank');
// }


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
