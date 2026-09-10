<template>
    <ConfirmDialog />
    <VCard>
        <div class="columns column c-title">
            <h3 class="title is-5 mb-2 ml-2" style="z-index:1">Rekap Data Sensus Harian Sasaran Mutu</h3>
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
            <VPlaceload height="20rem" width="100%" class="mx-2 mt-5" v-if="loadSearch" />
            <DataTable v-else :value="dataSource" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines>

            <Column field="no" style="min-width: 10px; text-align:center"/>
            <Column field="no" style="min-width: 10px; text-align:center"/>


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

const d_Departement = ref([])
const loadSearch = ref(true)
const isInput = ref(false);
const selected: any = ref({})
const input: any = ref({})
const isAktif = ref(true)

let dataSource: any = ref([])


const selectView: any = ref()
const confirm = useConfirm()
selectView.value = 'list'
let isLoadBtnSave: any = ref(false)
let source: any = ref([])
let isLoading: any = ref(true)

const tglSource: any = ref([])

const getFullMonthDates = (year: any, month: any) => {
    for (let index = 1; index <= 31; index++) {
        let currentDate = new Date(year, month - 1, index);
        if (currentDate.getMonth() + 1 === 2) {
            tglSource.value.push({
                'tanggal': H.formatDate(currentDate, 'YYYY-MM-DD 00-00-00'),
                'tgl': H.formatDate(currentDate, 'D'),
            });
        } else {
            break;
        }
    }
}

getFullMonthDates(H.formatDate(item.value.bulan, 'YYYY'), H.formatDate(item.value.bulan, 'MM'))

const fetchData = async () => {
    let bulan = H.formatDate(item.value.bulan, 'YYYY-MM')
    let departemen = item.value.departement ? `&departemenfk=${item.value.departement.value}` : ''
    let response = await useApi().get(`/pmkp/get-data-indikator-departemen?bulan=${bulan}${departemen}`)
    response.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    dataSource.value = response
    source.value = response
}

const loadRiwayat = () => {

    let bulan = H.formatDate(item.value.bulan, 'YYYY-MM')
    let departemen = item.value.departement ? `&departemenfk=${item.value.departement.value}` : ''
    loadSearch.value = true
    useApi().get(`/pmkp/get-hasil-sensus-indikator?bulan=${bulan}${departemen}`).then((response: any) => {
        if (response.length > 0) {
            input.value.id = response[0].id
            dataSource.value = response[0].sensusPenguukuranMutu
            source.value.forEach((e: any) => {
                let isMatch = false;
                response[0].sensusPenguukuranMutu.forEach((element: any) => {
                    if (e.id === element.id && e.keterangan === element.keterangan) {
                        isMatch = true;
                    }
                });
                if (!isMatch) {
                    dataSource.value.push(e)
                }
            })
            loadSearch.value = false
        } else {
            delete input.value.id
            dataSource.value = source.value
            loadSearch.value = false
        }
    })
}

const simpan = () => {

    if(!item.value.departement){
        H.alert('error','Departemen Tidak Boleh Kosong')
        return
    }

    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.sensusPenguukuranMutu = dataSource.value
    object.tanggal = H.formatDate(new Date(), 'YYYY-MM-DD')
    object.bulan = H.formatDate(item.value.bulan, 'YYYY-MM')
    object.departemenfk = item.value.departement.value 
    let json = {
        'id': ID,
        'collection': "SensusPengukuranMutu",
        'data': object
    }
    isLoadBtnSave.value = true
    useApi().post(`/pmkp/simpan-sensus-mutu`, json).then((response: any) => {
        input.value.id = response.id
        isLoadBtnSave.value = false
        clear()
    }).catch((e: any) => {
    })
}

const onCellEditComplete = (event: any) => {
    let { data, newValue, field, newData } = event;
    if (newValue != undefined) {
        data[field] = newValue
    }

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
    await loadRiwayat()
}

const clear = ()=>{
    isInput.value = false
    fetchData()
    loadRiwayat()
}

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