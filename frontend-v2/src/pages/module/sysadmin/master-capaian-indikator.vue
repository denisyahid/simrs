<template>
    <ConfirmDialog />
    <VCard>
        <div class="columns column c-title">
            <h3 class="title is-5 mb-2 ml-2" style="z-index:1">Capaian Indikator</h3>
        </div>

        <div class="column is-12">
            <DataTable :value="dataSource" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="isLoading"
                class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                <template #header>
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Indikator</VLabel>
                                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.qIndikator" :options="d_Indikator" optionLabel="label"
                                        placeholder="Pilih Indikator" style="width: 100%;" :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-8">
                            <div class="columns is-multiline" style="justify-content: end;">
                                <div class="column is-4">
                                    <VField class="is-rounded-select is-autocomplete-select">
                                        <VLabel>Jenis Indikator</VLabel>
                                        <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                            <Dropdown v-model="item.qJenisIndikator" :options="d_JenisIndikator"
                                                optionLabel="label" placeholder="Pilih Jenis Indikator" style="width: 100%;"
                                                :filter="true" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField label="Tahun">
                                        <Calendar v-model="item.tahun" view="year" dateFormat="yy" showIcon class="modif"
                                            placeholder="YYYY" />
                                    </VField>
                                </div>
                                <div class="column is-1 mt-5 pt-4">
                                    <VIconButton color="success" icon="fas fa-search" :loading="isLoadingButton"
                                        @click="fetchData()" />
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <Column field="no" header="No" style="min-width:10px"></Column>
                <Column field="jenisindikator" header="Jenis" style="min-width:10px"></Column>
                <Column field="indikator" header="Indikator" style="min-width:100px"></Column>
                <Column field="numerator" header="Numerator" style="min-width:100px"></Column>
                <Column field="denumerator" header="Denumerator" style="min-width:100px"></Column>
                <Column field="capaian" header="Capaian" style="min-width:100px"></Column>
                <Column field="target" header="Target" style="min-width:100px"></Column>
                <Column field="bulan" header="Bulan" style="min-width:100px"></Column>
                <Column field="tahun" header="Tahun" style="min-width:100px"></Column>
                <Column field="pic" header="PIC" style="min-width:80px"></Column>
                <Column :exportable="false" header="##" style="text-align: center;">
                    <template #body="slotProps">
                        <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined
                            raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                        </VIconButton>
                        <OverlayPanel ref="op">
                            <VButton type="button" icon="fas fa-print" light circle outlined color="info" raised
                                @click="edit(selected)">
                                Edit
                            </VButton>
                            <VButton type="button" icon="fas fa-trash" class="ml-2" color="danger" circle outlined
                                @click="DialogConfirm(selected)" raised>Hapus
                            </VButton>
                        </OverlayPanel>
                    </template>
                </Column>
                <template #footer>
                    <div class="column pt-0 pb-0" style="text-align:right">
                        <VButtons style="justify-content: flex-end">
                            <!-- <VButton class="mr-4" color="info" raised @click="goToPenerimaan"> Penerimaan </VButton> -->
                            <VButton class="mr-4" color="primary" raised icon="fas fa-edit"
                                style="padding-right: 3rem;padding-left: 3rem;" @click="modalInput = true">Tambah Data
                            </VButton>
                        </VButtons>
                    </div>
                </template>
            </DataTable>
        </div>
    </VCard>

    <VModal is="form" :open="modalInput" title="Form Input Indikator Mutu" size="medium" actions="right"
        @close="modalInput = false, clear()">
        <template #content>

            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-5">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Jenis Indikator</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.jenisIndikator" :options="d_JenisIndikator" optionLabel="label"
                                    placeholder="Pilih Jenis Indikator" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-7">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Indikator</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.indikator" :options="d_Indikator"
                                    optionLabel="label" placeholder="Pilih Indikator" style="width: 100%;" @change="getPic(item.indikator)"
                                    :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>

            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-5">
                        <VField label="PIC">
                            <VControl>
                                <input v-model="item.pic" type="text" class="input" placeholder="PIC" readonly />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Bulan">
                            <VControl>
                                <Calendar v-model="item.bulan" view="month" dateFormat="MM" showIcon class="modif" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Tahun">
                            <VControl>
                                <Calendar v-model="item.tahun" view="year" dateFormat="yy" showIcon class="modif" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField label="Numerator">
                            <VControl>
                                <input v-model="item.numerator" type="text" class="input" placeholder="Numerator" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Denumerator">
                            <VControl>
                                <input v-model="item.denumerator" type="text" class="input" placeholder="Denumerator" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Target">
                            <VControl>
                                <input v-model="item.target" type="text" class="input" placeholder="Target" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Capaian">
                            <VControl>
                                <input v-model="item.capaian" type="text" class="input" placeholder="Capaian" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
        </template>
        <template #action>
            <VButton color="primary" raised @click="save()" :loading="isLoadBtnSave">Simpan</VButton>
        </template>
    </VModal>
</template>
  
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable'
import OverlayPanel from 'primevue/overlaypanel';
import Column from 'primevue/column'
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'

import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue'
useHead({
    title: 'Master Jenis Indikator - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({})
const d_JenisIndikator = ref([])
const d_Indikator = ref([])
const modalInput = ref(false)
const op = ref();
const selected: any = ref({})
const isAktif = ref(true)

let dataSource: any = ref([])
const d_View = [
    {
        name: 'Grid View',
        value: 'grid',
        icon: 'fas fa-id-card-alt',
    },
    {
        name: 'List View',
        value: 'list',
        icon: 'fas fa-list',
    },
]

const selectView: any = ref()
const confirm = useConfirm()
selectView.value = 'list'
let isLoadingButton: any = ref(false)
let isLoadBtnSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {

    isLoadingButton.value = true
    isLoading.value = true
    let indikator = item.value.qIndikator ? item.value.qIndikator.value : ''
    let pic = item.value.qPic ? `&pic=${item.value.qPic}` : ''
    let jenisIndikator = item.value.qJenisIndikator ? `&jenisindikator=${item.value.qJenisIndikator.value}` : ''
    let tahun = item.value.qTahun ? `&tahun=${H.formatDate(item.value.qTahun,'YYYY')}` : ''
    await useApi().get(`/sysadmin/master-capaian-indikator?indikator=${item.value.indikator}${pic}${jenisIndikator}${tahun}`).then((response: any) => {
        response.forEach((items: any, i: any) => {
            items.no = i + 1
        })
        isLoading.value = false
        dataSource.value = response
    })
    isLoadingButton.value = false
}

const fetchCombo = async () => {
    await useApi().get('/sysadmin/master-capaian-indikator/get-data-combo').then((response) => {
        d_JenisIndikator.value = response.jenisIndikator.map((e: any) => {
            return { label: e.jenisindikator, value: e.id }
        })
        d_Indikator.value = response.indikator.map((e: any) => {
            return { label: e.indikator, value: e.id, optional : e.pic }
        })
    })
}

const save = async () => {

    let objSave =
    {
        norec: item.value.norec ? item.value.norec : '',
        pic: item.value.pic ? item.value.pic : '',
        indikatorfk: item.value.indikator ? item.value.indikator.value : null,
        jenisindikatorfk: item.value.jenisIndikator ? item.value.jenisIndikator.value : null,
        pic: item.value.pic ? item.value.pic : '',
        bulan: item.value.bulan ? H.formatDate(item.value.bulan, 'MMMM') : '',
        tahun: item.value.tahun ? H.formatDate(item.value.tahun, 'YYYY') : '',
        numerator: item.value.numerator ? item.value.numerator : '',
        target: item.value.target ? item.value.target : '',
        denumerator: item.value.denumerator ? item.value.denumerator : '',
        capaian: item.value.capaian ? item.value.capaian : '',
    }
    isLoadBtnSave.value = true
    await useApi().post(`/sysadmin/master-capaian-indikator/save`, objSave).then((response: any) => {
        isLoadBtnSave.value = false
        clear()
        fetchData()
    }, (error) => {
        isLoadBtnSave.value = false
        // console.log(error)
    })
}

const deleterow = async (e: any) => {
    isLoading.value = true
    await useApi().post(
        `/sysadmin/master-capaian-indikator/delete`, { 'norec': e.norec }).then((response: any) => {
            isLoading.value = false
            clear()
            fetchData()
        }, (error) => {
        })
}

const getPic = (e:any)=>{
    item.value.pic = e.optional
}
const edit = (e: any) => {
    console.log(e)
    item.value.norec = e.norec
    item.value.bulan = e.bulan
    item.value.tahun =  e.tahun
    item.value.capaian = e.capaian
    item.value.denumerator = e.denumerator
    item.value.target = e.target
    item.value.indikator = {label : e.indikator , value : e.indikatorfk, optional : e.pic }
    item.value.jenisIndikator = {label : e.jenisindikator , value : e.jenisindikatorfk }
    item.value.numerator = e.numerator
    item.value.pic = e.pic

    modalInput.value = true
}

const DialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleterow(e)

        },
        reject: () => { },
    })
}

const clear = () => {
    delete item.value.norec
    delete item.value.bulan
    delete item.value.tahun
    delete item.value.capaian
    delete item.value.pic
    delete item.value.denumerator
    delete item.value.target
    delete item.value.indikator
    delete item.value.numerator
    modalInput.value = false
}

const toggle = (event: any, e: any) => {
    console.log(event)
    op.value.toggle(event);
    selected.value = e
}

fetchData()
fetchCombo()

watch(isAktif, () => {
    fetchData()
})

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