<template>
    <ConfirmDialog />
    <VCard>
        <div class="columns column c-title">
            <h3 class="title is-5 mb-2 ml-2" style="z-index:1">Master Indikator</h3>
        </div>

        <div class="column is-12">
            <DataTable :value="dataSource" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="loadSearch"
                class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                <template #header>
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <VField label="Indikator">
                                <VControl>
                                    <input v-model="item.qIndikator" type="text" class="input mt-1" placeholder="No Order" />
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
                                    <VField label="PIC">
                                        <VControl>
                                            <input v-model="item.qPic" type="text" class="input" placeholder="PIC" />
                                        </VControl>
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
                <Column field="indikator" header="Indikator" style="min-width:100px"></Column>
                <Column field="pic" header="PIC" style="min-width:80px"></Column>
                <Column field="jenisindikator" header="Jenis Indikator" style="min-width:80px"></Column>
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
                                @click="dialogConfirm(selected)" raised>Hapus
                            </VButton>
                        </OverlayPanel>
                    </template>
                </Column>
                <template #footer>
                    <div class="column pt-0 pb-0" style="text-align:right">
                        <VButtons style="justify-content: flex-end">
                            <!-- <VButton class="mr-4" color="info" raised @click="goToPenerimaan"> Penerimaan </VButton> -->
                            <VButton class="mr-4" color="primary" raised icon="fas fa-edit"
                                style="padding-right: 3rem;padding-left: 3rem;" @click="modalInput = true">Buat Indikator
                            </VButton>
                        </VButtons>
                    </div>
                </template>
            </DataTable>
        </div>
    </VCard>

    <VModal is="form" :open="modalInput" title="Form Input Indikator Mutu" size="large" actions="right"
        @close="modalInput = false, clear()">
        <template #content>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <VField label="Indikator">
                            <VControl>
                                <input v-model="item.indikator" type="text" class="input" placeholder="PIC" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="PIC">
                            <VControl>
                                <input v-model="item.pic" type="text" class="input" placeholder="PIC" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Kategori Indikator</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.kategoriIndikator" :options="d_KategoriIndikator"
                                    optionLabel="label" placeholder="Pilih Kategori Indikator" style="width: 100%;"
                                    :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Jenis Indikator</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.jenisIndikator" :options="d_JenisIndikator" optionLabel="label"
                                    placeholder="Pilih Jenis Indikator" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Departemen</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.departemen" :options="d_Departemen" optionLabel="label"
                                    placeholder="Pilih Departemen" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Dimensi Mutu</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.dimensiMutu" :options="d_DimensiMutu" optionLabel="label"
                                    placeholder="Pilih Dimensi Mutu" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField label="Dasar Pemikiran">
                            <VControl fullwidth>
                                <VTextarea class="textarea" v-model="item.dasarPemikiran" rows="2"
                                    placeholder="Dasar Pemikiran ..." autocomplete="off" autocapitalize="off"
                                    spellcheck="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Tujuan">
                            <VControl fullwidth>
                                <VTextarea class="textarea" v-model="item.tujuan" rows="2" placeholder="Tujuan ..."
                                    autocomplete="off" autocapitalize="off" spellcheck="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Definisi Oprasional">
                            <VControl fullwidth>
                                <VTextarea class="textarea" v-model="item.definisiOprasional" rows="2"
                                    placeholder="Definisi Oprasional ..." autocomplete="off" autocapitalize="off"
                                    spellcheck="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField label="Numerator">
                            <VControl fullwidth>
                                <VTextarea class="textarea" v-model="item.numerator" rows="2" placeholder="Numerator ..."
                                    autocomplete="off" autocapitalize="off" spellcheck="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Dominator">
                            <VControl fullwidth>
                                <VTextarea class="textarea" v-model="item.dominator" rows="2" placeholder="Domintar ..."
                                    autocomplete="off" autocapitalize="off" spellcheck="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Formula">
                            <VControl fullwidth>
                                <VTextarea class="textarea" v-model="item.formula" rows="2" placeholder="Formula ..."
                                    autocomplete="off" autocapitalize="off" spellcheck="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField label="Target Pencapaian">
                            <VControl>
                                <input v-model="item.tragetPencapaian" type="text" class="input"
                                    placeholder="Target Pencapaian" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Inklusi">
                            <VControl>
                                <input v-model="item.inklusi" type="text" class="input" placeholder="Inklusi" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Eksklusi">
                            <VControl>
                                <input v-model="item.eksklusi" type="text" class="input" placeholder="Eksklusi" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Sumber data">
                            <VControl>
                                <input v-model="item.sumberData" type="text" class="input" placeholder="Sumber Data" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Frekuensi Pengumpulan Data</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.frekuensiData" :options="d_FrekuensiData" optionLabel="label"
                                    placeholder="Pilih Frekuensi" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Jangka waktu laporan</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.waktuLaporan" :options="d_WaktuLaporan" optionLabel="label"
                                    placeholder="Pilih Jangka waktu" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Periode analis</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.periodeAnalis" :options="d_PeriodeAnalis" optionLabel="label"
                                    placeholder="Pilih Periode Analis" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Metodologi Pengumpulan Data</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.metodologiPengumpulan" :options="d_Metologi" optionLabel="label"
                                    placeholder="Pilih Metodologi" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Cakupan Data</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.cakupanData" :options="d_CakupanData" optionLabel="label"
                                    placeholder="Pilih Cakupan Data" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Sample">
                            <VControl>
                                <input v-model="item.sample" type="text" class="input" placeholder="Sample" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-5">
                        <VField label="Penangung Jawab">
                            <VControl>
                                <input v-model="item.penangungJawab" type="text" class="input"
                                    placeholder="Penangung Jawab" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Publikasi Data</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.publikasiData" :options="d_PublikasiData" optionLabel="label"
                                    placeholder="Pilih Publikasi Data" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Metodologi Analisi Data</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.metodologiAnalisiData" :options="d_MetologiAnalisa"
                                    optionLabel="label" placeholder="Pilih Metodologi Analisi Data" style="width: 100%;"
                                    :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-5">
                        <VField label="Instrumen pengambilan data">
                            <VControl>
                                <input v-model="item.instrumen" type="text" class="input"
                                    placeholder="Instrumen pengambilan data" />
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
import Dropdown from 'primevue/dropdown'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'

import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue'
useHead({
    title: 'Master Jenis Indikator - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({})
const d_JenisIndikator = ref([])
const d_KategoriIndikator = ref([])
const d_Departemen = ref([])
const d_DimensiMutu = ref([])
const d_FrekuensiData = ref([])
const d_WaktuLaporan = ref([])
const d_PeriodeAnalis = ref([])
const d_Metologi = ref([])
const d_CakupanData = ref([])
const d_PublikasiData = ref([])
const d_MetologiAnalisa = ref([])
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
    let pic = item.value.qPic ? `&pic=${item.value.qPic}` : ''
    let jenisIndikator = item.value.qJenisIndikator ? `&jenisindikator=${item.value.qJenisIndikator.value}` : ''
    isLoading.value = true
    await useApi().get(`/sysadmin/master-indikator?indikator=${item.value.qIndikator}${pic}${jenisIndikator}`).then((response: any) => {
        response.forEach((items: any, i: any) => {
            items.no = i + 1
        })
        isLoading.value = false
        dataSource.value = response
    })
    isLoadingButton.value = false
}

const fetchCombo = async () => {
    await useApi().get('/sysadmin/master-indikator/get-data-combo').then((response) => {
        d_JenisIndikator.value = response.jenisindikator.map((e: any) => {
            return { label: e.jenisindikator, value: e.id }
        })
        d_KategoriIndikator.value = response.kategoryIndikator.map((e: any) => {
            return { label: e.kategoryindikator, value: e.id }
        })
        d_Departemen.value = response.departemen.map((e: any) => {
            return { label: e.namadepartemen, value: e.id }
        })
        d_DimensiMutu.value = response.dimensimutu.map((e: any) => {
            return { label: e.demensimutu, value: e.id }
        })
        d_FrekuensiData.value = response.frekuensidata.map((e: any) => {
            return { label: e.frekuensi, value: e.id }
        })
        d_WaktuLaporan.value = response.waktulaporan.map((e: any) => {
            return { label: e.waktulaporan, value: e.id }
        })
        d_PeriodeAnalis.value = response.periodeanalis.map((e: any) => {
            return { label: e.periode, value: e.id }
        })
        d_Metologi.value = response.metologi.map((e: any) => {
            return { label: e.metologi, value: e.id }
        })
        d_CakupanData.value = response.cakupandata.map((e: any) => {
            return { label: e.cakupandata, value: e.id }
        })
        d_PublikasiData.value = response.publikasidata.map((e: any) => {
            return { label: e.publikasidata, value: e.id }
        })
        d_MetologiAnalisa.value = response.metologiana.map((e: any) => {
            return { label: e.analisisdata, value: e.id }
        })
    })
}

const save = async () => {

    let objSave =
    {
        id: item.value.id ? item.value.id : '',
        statusenabled: item.value.statusenabled ? item.value.statusenabled : '',
        definisioperasional: item.value.definisiOprasional ? item.value.definisiOprasional : '',
        formula: item.value.formula ? item.value.formula : '',
        indikator: item.value.indikator ? item.value.indikator : '',
        pic: item.value.pic ?  item.value.pic : '',
        kategoryindikatorfk: item.value.kategoriIndikator ? item.value.kategoriIndikator.value : null,
        jenisindikatorfk: item.value.jenisIndikator ? item.value.jenisIndikator.value : null,
        objectdepartemenfk: item.value.departemen ? item.value.departemen.value : null,
        numerator: item.value.numerator ? item.value.numerator : '',
        denominator: item.value.dominator ? item.value.dominator : '',
        dasarpemikiran: item.value.dasarPemikiran ?  item.value.dasarPemikiran : '',
        dimensimutu: item.value.dimensiMutu ? item.value.dimensiMutu.value : null,
        tujuan: item.value.tujuan ? item.value.tujuan : '',
        targetpencapaian: item.value.tragetPencapaian ?  item.value.tragetPencapaian : '',
        inklusi: item.value.inklusi ? item.value.inklusi : '',
        eksklusi: item.value.eksklusi ? item.value.eksklusi : '',
        sumberdata: item.value.sumberData ? item.value.sumberData : '',
        pengumpulandata: item.value.frekuensiData ? item.value.frekuensiData.value : null,
        jangkalaporan: item.value.waktuLaporan ? item.value.waktuLaporan.value : null,
        periodeanalis: item.value.periodeAnalis ? item.value.periodeAnalis.value : null,
        metodologipengumpulandata: item.value.metodologiPengumpulan ? item.value.metodologiPengumpulan.value : null,
        cakupandata: item.value.cakupanData ? item.value.cakupanData.value : null,
        sampel: item.value.sample ? item.value.sample : '',
        metodologianalisisdata: item.value.metodologiAnalisiData ? item.value.metodologiAnalisiData.value : null,
        instrumenpengambilandata: item.value.instrumen ? item.value.instrumen : '',
        publikasidata: item.value.publikasiData ? item.value.publikasiData.value : null,
        penanggungjawab: item.value.penangungJawab ? item.value.penangungJawab : '',
    }

    isLoadBtnSave.value = true
    await useApi().post(`/sysadmin/master-indikator/save`, objSave).then((response: any) => {
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
        `/sysadmin/master-indikator/delete`, { 'id': e.id }).then((response: any) => {
            isLoading.value = false
            clear()
            fetchData()
        }, (error) => {
        })
}

const edit = (e: any) => {
    item.value.id = e.id
    item.value.definisiOprasional = e.definisioperasional
    item.value.formula = e.formula
    item.value.indikator = e.indikator
    item.value.pic = e.pic
    item.value.kategoriIndikator = {label : e.kategoryindikator , value : e.kategoryindikatorfk }
    item.value.jenisIndikator = {label : e.jenisindikator , value : e.jenisindikatorfk }
    item.value.departemen = {label : e.namadepartemen , value : e.objectdepartemenfk }
    item.value.numerator = e.numerator
    item.value.denominator = e.denominator
    item.value.dasarPemikiran = e.dasarpemikiran
    item.value.dimensiMutu = { label: e.demensimutu, value: e.demensimutufk }
    item.value.tujuan = e.tujuan
    item.value.targetPencapaian = e.targetpencapaian
    item.value.inklusi = e.inklusi
    item.value.eksklusi = e.eksklusi
    item.value.sumberData = e.sumberdata
    item.value.frekuensiData = { label: e.frekuensi, value: e.frekuensifk }
    item.value.waktuLaporan = { label: e.waktulaporan, value: e.waktulaporanfk }
    item.value.periodeAnalis = { label: e.periodepelaporan, value: e.periodefk }
    item.value.metodologipengumpulandata = { label: e.metologi, value: e.metologifk }
    item.value.cakupanData = { label: e.cakupandata, value: e.cakupandatafk }
    item.value.metodologiAnalisiData = { label: e.analisisdata, value: e.analisisdatafk }
    item.value.publikasiData = { label: e.publikasidata, value: e.publikasidatafk }
    item.value.sampel = e.sampel
    item.value.instrumen = e.instrumenpengambilandata
    item.value.penangungJawab = e.penanggungjawab
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
   delete item.value.id
   delete item.value.definisiOprasional
   delete item.value.formula
   delete item.value.indikator
   delete item.value.pic
   delete item.value.definisiOprasional
   delete item.value.jenisIndikator
   delete item.value.departemen
   delete item.value.numerator
   delete item.value.denominator
   delete item.value.dasarPemikiran
   delete item.value.dimensiMutu
   delete item.value.tujuan
   delete item.value.targetPencapaian
   delete item.value.inklusi
   delete item.value.eksklusi
   delete item.value.sumberData
   delete item.value.frekuensiData
   delete item.value.waktuLaporan
   delete item.value.periodeAnalis
   delete item.value.metodologipengumpulandata
   delete item.value.cakupanData
   delete item.value.metodologiAnalisiData
   delete item.value.publikasiData
   delete item.value.sampel
   delete item.value.instrumen
   delete item.value.penangungJawab
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
}</style>