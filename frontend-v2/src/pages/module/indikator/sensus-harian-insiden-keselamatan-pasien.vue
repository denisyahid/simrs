<template>
    <ConfirmDialog />
    <VCard>
        <div class="columns column c-title">
            <h3 class="title is-5 mb-2 ml-2" style="z-index:1">Sensus Harian Insiden Keselamatan Pasien</h3>
        </div>

        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-2">
                    <VField label="Bulan">
                        <Calendar v-model="item.bulan" view="month" dateFormat="MM" showIcon class="modif" />
                    </VField>
                </div>
                <div class="column is-3">
                    <VField label="Departemen">
                        <VControl class="prime-auto">
                            <AutoComplete v-model="item.departement" :suggestions="d_Departement" :optionLabel="'label'"
                                @complete="fetchDepartement($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Departemnt..." />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-1 mt-5 pt-4">
                    <VIconButton color="success" icon="fas fa-search" @click="cari()" :loading="loadSearch" />
                </div>
            </div>
        </div>
        <div class="column is-12">
            <VPlaceload height="20rem" width="100%" class="mx-2 mt-5" v-if="loadData" />
            <DataTable v-else :value="dataSourceTitle" v-model:expandedRowGroups="expandedRowGroups" scrollable
                scrollHeight="500px" class="p-datatable-sm" showGridlines editMode="cell"
                @cell-edit-complete="onCellEditComplete" :loading="loadSearch" tableClass="editable-cells-table"
                tableStyle="min-width: 100rem" groupRowsBy="jeniskeselamatan" @rowgroup-expand="onRowGroupExpand"
                @rowgroup-collapse="onRowGroupCollapse" rowGroupMode="subheader">

                <ColumnGroup type="header">
                    <Row>
                        <Column header="INSIDEN KESELAMATAN PASIEN" style="min-width: 500px; text-align: center;" frozen
                            :rowspan="2" />
                        <Column header="Tanggal" style="min-width: 300px;" class="font-bold"
                            :colspan="tglSource.length" />
                        <!-- <Column header="TOTAL" style="text-align: center;" frozen :rowspan="2" /> -->
                    </Row>
                    <Row>
                        <Column :header="tgl.tgl" style="min-width: 50px;" v-for="(tgl) in tglSource" class="font-bold"
                            :rowEditor="true" />
                    </Row>
                </ColumnGroup>
                <template #groupheader="slotProps">
                    <span class="vertical-align-middle ml-2 font-bold line-height-3">{{
                        slotProps.data.jeniskeselamatan}}</span>
                </template>

                <Column field="keselamatan" style="min-width: 500px; " frozen />
                <Column style="width: 15%; text-align: center;" v-for="(data, i) in tglSource" class="font-bold"
                    field="tgl">
                    <template #body="slotProps">
                        {{  data.idkeselamatan == slotProps.data.id ? data.jumlah : '' }}
                    </template>
                </Column>
                <!-- <Column field="totalAll" style="min-width: 50px; " frozen /> -->
            </DataTable>
        </div>
    </VCard>
</template>
  
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable'
import OverlayPanel from 'primevue/overlaypanel';
import Column from 'primevue/column'
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import InputText from 'primevue/inputtext';
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'

import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue'
useHead({
    title: 'Sensus Harian Mutu - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
    bulan: new Date()
})

const expandedRowGroups: any = ref()
const d_Departement = ref([])
const loadSearch = ref(false)
const loadData = ref(true)
const isInput = ref(false);
const selected: any = ref({})
const input: any = ref({})
const isAktif = ref(true)

let dataSource: any = ref([])
let dataSourceTitle: any = ref([])


const selectView: any = ref()
const confirm = useConfirm()
selectView.value = 'list'
let isLoadBtnSave: any = ref(false)
let source: any = ref([])
let isLoading: any = ref(true)

const tglSource: any = ref([])

const getFullMonthDates = (year: any, month: any) => {
    tglSource.value = []
    for (let index = 1; index <= 31; index++) {
        let currentDate = new Date(year, month - 1, index);
        if (currentDate.getMonth() + 1 == H.formatDate(item.value.bulan, 'M')) {
            tglSource.value.push({
                'tanggal': H.formatDate(currentDate, 'YYYY-MM-DD 00-00-00'),
                'tgl': H.formatDate(currentDate, 'D'),
                'bulan': parseInt(H.formatDate(currentDate, 'MM'), 10)
            });
        }
    }

}

const loadCombo = async ()=>{
    let response = await useApi().get('pmkp/get-data-combo-pmkp')
    dataSourceTitle.value = response.insidenkeselamtanpasien
}

const fetchData = async () => {
 
    let bulan = H.formatDate(item.value.bulan, 'YYYY-MM')
    let departemen = item.value.departement ? `&departemenfk=${item.value.departement.value}` : ''
    let response = await useApi().get(`pmkp/get-daftar-insiden-keselamatan-pasien?bulan=${bulan}${departemen}`)

    getFullMonthDates(H.formatDate(item.value.bulan, 'YYYY'), H.formatDate(item.value.bulan, 'MM')) 
    dataSource.value = response
    tglSource.value.forEach((elm:any,i:any) => {
        if (dataSource.value.length >= 0){
            dataSource.value.forEach(elmen => {
                if (elm.bulan == elmen.bulan && elm.tgl == elmen.tgl) {
                    tglSource.value[i] = elmen
                }
            })
        }else{
            getFullMonthDates(H.formatDate(item.value.bulan, 'YYYY'), H.formatDate(item.value.bulan, 'MM')) 
        }

    });
   
    loadData.value = false
    loadSearch.value = false

}

const fetchDepartement = async (filter: any) => {

    await useApi().get(`emr/dropdown/departemen_m?select=id,namadepartemen&param_search=namadepartemen&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Departement.value = response
    })
}

const cari = async () => {
    loadSearch.value = true
    await fetchData()
}

const clear = ()=>{
    isInput.value = false
    fetchData()

}
getFullMonthDates(H.formatDate(item.value.bulan, 'YYYY'), H.formatDate(item.value.bulan, 'MM'))
loadCombo()
cari()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.field>label {
    text-overflow: unset;
    overflow: unset;
}
</style>