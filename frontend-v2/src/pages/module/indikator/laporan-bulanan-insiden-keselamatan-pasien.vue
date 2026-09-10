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
                        <Calendar v-model="item.tahun" view="year" dateFormat="yy" showIcon class="modif"
                            @date-select="cari()" />
                    </VField>
                </div>
                <!-- <div class="column is-3">
                    <VField label="Departemen">
                        <VControl class="prime-auto">
                            <AutoComplete v-model="item.departement" :suggestions="d_Departement" :optionLabel="'label'"
                                @complete="fetchDepartement($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Departemnt..." />
                        </VControl>
                    </VField>
                </div> -->
                <!-- <div class="column is-1 mt-5 pt-4">
                    <VIconButton color="success" icon="fas fa-search" @click="cari()" :loading="loadSearch" />
                </div> -->
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
                        <Column header="BULAN" style="min-width: 300px;" class="font-bold"
                            :colspan="blnSource.length" />
                        <!-- <Column header="TOTAL" style="text-align: center;" frozen :rowspan="2" /> -->
                    </Row>
                    <Row>
                        <Column :header="bln.bulan" style="min-width: 50px;" v-for="(bln) in blnSource"
                            class="font-bold" :rowEditor="true" />
                    </Row>
                </ColumnGroup>
                <template #groupheader="slotProps">
                    <span class="vertical-align-middle ml-2 font-bold line-height-3">{{
                        slotProps.data.jeniskeselamatan }}</span>
                </template>

                <Column field="keselamatan" style="min-width: 500px; " frozen />
                <Column field="jan" style="min-width: 80px;" />
                <Column field="feb" style="min-width: 80px;" />
                <Column field="mar" style="min-width: 80px;" />
                <Column field="apr" style="min-width: 80px;" />
                <Column field="may" style="min-width: 80px;" />
                <Column field="jun" style="min-width: 80px;" />
                <Column field="jul" style="min-width: 80px;" />
                <Column field="aug" style="min-width: 80px;" />
                <Column field="sep" style="min-width: 80px;" />
                <Column field="oct" style="min-width: 80px;" />
                <Column field="nov" style="min-width: 80px;" />
                <Column field="dec" style="min-width: 80px;" />
                <!-- <Column style="width: 15%; text-align: center;" class="font-bold" v-for="(bln) in blnSource">
                    <template #body="slotProps">
                        {{ bln.bulan ==  }}
                    </template>
                </Column> -->
                <!-- <Column style="width: 15%; text-align: center;" v-for="(bln) in dataSource" class="font-bold"
                    field="tgl">
                    <template #body="slotProps">
                        {{bln}}
                    </template>
                </Column> -->
                <!-- <Column field="totalAll" style="min-width: 50px; " frozen /> -->
            </DataTable>
        </div>
    </VCard>
</template>

<script setup lang="ts">
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
    tahun: new Date()
})

const expandedRowGroups: any = ref()
const d_Departement = ref([])
const bln = ref(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])

const loadSearch = ref(false)
const loadData = ref(true)
const isInput = ref(false);
const selected: any = ref({})
const input: any = ref({})
const isAktif = ref(true)

let dataSource: any = ref([])
let dataSourceTitle: any = ref([])
let dataSaveSourceTitle: any = ref([])


const selectView: any = ref()
const confirm = useConfirm()
selectView.value = 'list'
let isLoadBtnSave: any = ref(false)
let source: any = ref([])
let isLoading: any = ref(true)

const blnSource: any = ref([])

const getFullMonthDates = () => {
    blnSource.value = []
    for (let month = 1; month <= 12; month++) {
        const date = new Date(2000, month - 1, 1);
        const monthName = date.toLocaleString('en-US', { month: 'long' }).slice(0, 3);;
        const monthValue = String(month).padStart(1);
        blnSource.value.push({ bulan: monthName, bln: monthValue });
    }

}

const loadCombo = async () => {
    dataSourceTitle.value = []
    loadSearch.value = true
    let response = await useApi().get('pmkp/get-data-combo-pmkp')
    response.insidenkeselamtanpasien.forEach(element => {
        blnSource.value.forEach(el => {
            element[el.bulan.toLowerCase()] = ''
        });
    });
    dataSourceTitle.value = response.insidenkeselamtanpasien
}

const fetchData = async () => {

    let tahun = H.formatDate(item.value.tahun, 'YYYY')
    let departemen = item.value.departement ? `&departemenfk=${item.value.departement.value}` : ''
    let response = await useApi().get(`pmkp/get-daftar-sensus-keselamatan-pasien-bulanan?tahun=${tahun}${departemen}`)

    getFullMonthDates()
    dataSource.value = response

    if (dataSource.value.length > 0){
        dataSourceTitle.value.forEach((el: any, i: any) => {
            dataSource.value.forEach((elem: any) => {
                if (elem.keselamatanfk == el.id) {
                    let bln = elem.bulan.toLowerCase()
                    el[bln] = elem[bln];
                }
            });
        })
    }else{
        await loadCombo()
        loadSearch.value = false
        // console.log(dataSource.value.length)
        // dataSourceTitle.value = []
        // dataSourceTitle.value = dataSaveSourceTitle.value
    }

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

const clear = () => {
    isInput.value = false
    fetchData()

}
getFullMonthDates()
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