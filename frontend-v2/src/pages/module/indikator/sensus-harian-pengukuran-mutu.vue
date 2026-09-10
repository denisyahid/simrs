<template>
    <ConfirmDialog />
    <VCard>
        <div class="columns column c-title">
            <h3 class="title is-5 mb-2 ml-2" style="z-index:1">Sensus Harian Pengukuran Mutu</h3>
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
            <DataTable v-else :value="dataSource" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
                editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
                tableStyle="min-width: 100rem" groupRowsBy="group" rowGroupMode="subheader">

                <ColumnGroup type="header">
                    <Row>
                        <Column header="No" style="min-width: 10px; text-align: center;" frozen :rowspan="2" />
                        <Column header="Indikator" style="min-width: 500px; text-align: center;" frozen :rowspan="2" />
                        <Column header="Keterangan" style="min-width: 200px; text-align: center;" frozen :rowspan="2" />
                        <Column header="Tanggal" style="min-width: 300px;" class="font-bold" :colspan="tglSource.length" />
                        <Column header="NUM (A)" style="min-width: 100px; text-align: center;" :rowspan="2" />
                        <Column header="DENUM (B)" style="min-width: 100px; text-align: center;" :rowspan="2" />
                        <Column header="Capaian (%)" style="min-width: 100px; text-align: center;" :rowspan="2" />
                    </Row>
                    <Row>
                        <Column :header="tgl.tgl" style="min-width: 50px;" v-for="(tgl) in tglSource" class="font-bold"
                            :rowEditor="true" />
                    </Row>
                </ColumnGroup>

                <Column field="no" style="min-width: 10px; text-align:center" frozen />
                <Column field="indikator" style="min-width: 500px; " frozen />
                <Column field="keterangan" style="min-width: 200px; " frozen />
                <Column style="width: 15%" v-for="(data, i) in tglSource" class="font-bold" :field="data.tgl">
                    <template #body="slotProps">
                        {{ slotProps.data[data.tgl] }}
                    </template>
                    <template v-if="isInput" #editor="{ data, field }">
                        <InputText v-model="data[field]" autofocus />
                    </template>
                </Column>
                <Column style="width: 100%" class="font-bold" field="num">
                    <template #body="slotProps">
                        {{ slotProps.data.num }}
                    </template>
                    <template v-if="isInput" #editor="{ data, field }">
                        <InputText v-model="data[field]" autofocus />
                    </template>
                </Column>
                <Column style="width: 100%" class="font-bold" field="denum">
                    <template #body="slotProps">
                        {{ slotProps.data.denum }}
                    </template>
                    <template v-if="isInput" #editor="{ data, field }">
                        <InputText v-model="data[field]" autofocus />
                    </template>
                </Column>
                <Column style="width: 100%" class="font-bold" field="capaian">
                    <template #body="slotProps">
                        {{ slotProps.data.capaian }}
                    </template>
                    <template v-if="isInput" #editor="{ data, field }">
                        <InputText v-model="data[field]" autofocus />
                    </template>
                </Column>
                <template #footer>
                    <div class="column pt-0 pb-0" style="text-align:right">
                        <VButtons style="justify-content: flex-end">
                            <!-- <VButton class="mr-4" color="info" raised @click="goToPenerimaan"> Penerimaan </VButton> -->
                            <VButton class="mr-4" color="warning" raised icon="fas fa-edit" v-if="isInput == false"
                                style="padding-right: 3rem;padding-left: 3rem;" @click="isInput = true">Tambah Data
                            </VButton>
                            <VButton class="mr-3" color="primary"  icon="feather:save" elevated v-if="isInput" @click="simpan()" :loading="isLoadBtnSave">Simpan Data</VButton>
                            <VButton color="danger"  elevated v-if="isInput" @click="clear()"><i class="fas fa-times-circle mr-2" aria-hidden="true"></i> Batal</VButton>
                            <!-- <VButtons>
                                icon="feather:save"
                            </VButtons> -->
                        </VButtons>
                    </div>
                </template>
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
    title: 'Sensus Harian Pengukuran Mutu - ' + import.meta.env.VITE_PROJECT,
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
        if (currentDate.getMonth() + 1 == H.formatDate(item.value.bulan, 'M')) {
            tglSource.value.push({
                'tanggal': H.formatDate(currentDate, 'YYYY-MM-DD 00-00-00'),
                'tgl': H.formatDate(currentDate, 'D'),
            });
        } else {
            // break;
        }
    }
}

// console.log( H.formatDate(item.value.bulan, 'M'))
getFullMonthDates(H.formatDate(item.value.bulan, 'YYYY'), H.formatDate(item.value.bulan, 'MM'))

// console.log(tglSource)

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