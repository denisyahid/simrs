<template>
    <ConfirmDialog />

    <div class="column is-12">
        <VCard>
            <div class="column c-title pt-2 mb-0">
                <div class="columns p-2">
                    <div class="column is-10 pt-0">
                        <label class="title-page">Daftar Indikasi Risiko</label>
                        <label for="">List Indikasi Risiko</label>
                    </div>
                </div>
            </div>

            <div class="column is-12 mt-5">
                 <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable :value="dataSource" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="loadSearch" v-else
                    class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                    sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField label="Periode Investigasi" />
                                <VDatePicker class="mt-2" v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-9">
                                <div class="columns is-multiline" style="justify-content: end;">
                                    <div class="column is-4 mt-1">
                                        <VField class="is-rounded-select is-autocomplete-select" label="Unit Kerja">
                                            <VControl icon="feather:search" class="prime-auto-select">
                                                <AutoComplete v-model="item.unitKerjafk" :suggestions="d_Departement"
                                                    @complete="fetchDepartement($event)" :optionLabel="'label'"
                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    placeholder="Pilih Unit Kerja" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-1 mt-5 pt-4">
                                        <VIconButton color="success" icon="fas fa-search" :loading="loadSearch"
                                            @click="fetchOrder()" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <Column expander style="width: 5rem" />
                    <Column field="no" header="No"></Column>
                    <Column field="tanggal" header="Tanggal"></Column>
                    <Column field="namadepartemen" header="Unit Kerja"></Column>
                    <Column field="kategoryrisiko" header="Kategori Risiko"></Column>
                    <Column :exportable="false" header="Action" style="text-align: center;">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined
                                raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                            </VIconButton>
                            <OverlayPanel ref="op">
                                <VButton type="button" icon="fas fa-print" light circle outlined color="info" raised
                                    @click="gotoPageEdit(selected)">
                                    Edit
                                </VButton>
                                <VButton type="button" icon="fas fa-trash" class="ml-2" color="danger" circle outlined
                                    @click="dialogConfirm(selected)" raised>Hapus
                                </VButton>
                                <!-- <VButton type="button" class="ml-2" color="info" circle outlined raised @click="tindakLanjut()">Tindak Lanjut</VButton> -->

                            </OverlayPanel>
                        </template>
                    </Column>
                    <template #expansion="slotProps">
                        <div class="p-3">
                            <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple">
                                <Column field="no" header="No" />
                                <Column field="jenisrisiko" header="Jenis Risiko" />
                                <Column field="keparahan" header="Keparahan" />
                                <Column field="kemungkinan" header="Kemungkinan" />
                                <Column field="skor" header="Skor" />
                                <Column field="rangkingrisiko" header="Rangking Risiko" />
                                <Column field="pengendalian" header="Pengendalian" />
                                <Column field="rangkingaction" header="Rangking Action" />
                            </DataTable>
                        </div>
                    </template>
                    <template #footer>
                        <div class="column pt-0 pb-0" style="text-align:right">
                            <VButtons style="justify-content: flex-end">
                                <VButton class="mr-4" color="primary" raised icon="fas fa-edit"
                                    style="padding-right: 3rem;padding-left: 3rem;" @click="gotoPageForm"
                                    :loading="loadSave"> Buat Indikasi Risiko </VButton>
                            </VButtons>
                        </div>
                    </template>
                </DataTable>
            </div>
        </VCard>
    </div>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import moment from 'moment'
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import OverlayPanel from 'primevue/overlaypanel';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as qzService from '/@src/utils/qzTrayService'


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';

useHead({
    title: 'Daftar Identifikasi Risiko - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const router = useRouter()
const confirm = useConfirm()
let dataSource: any = ref([])
const detailResep: any = ref([])
const op = ref();
const selected: any = ref({})
const loadData: any = ref(true)
const expandedRows = ref()
let d_Departement: any = ref([])
let loadSearch: any = ref(false)

const fetchOrder = async () => {
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let departemen = item.value.unitKerjafk ? `&departemenfk=${item.value.unitKerjafk.value}` : ''
    loadSearch.value = true
    await useApi().get(`/pmkp/get-daftar-laporan-identifikasi-risiko?${tglAwal}${tglAkhir}${departemen}`).then((response: any) => {
        response.forEach((element: any, i: any) => {
            expandedRows.value = element.details.forEach((data: any, i: any) => {
                data.no = i + 1
                data.tanggal = data.tanggal ? H.formatDate(data.tanggal, 'DD/MM/YYYY') : ''
            })
            element.no = i + 1
            element.tglOrder = H.formatDate(element.tglorder, 'DD-MMM-YYYY')
        });
        dataSource.value = response
        loadData.value = false
        loadSearch.value = false
    }).catch((err: any) => {
        loadSearch.value = false
    })

    console.log(dataSource.value)
}

const deleteData = async (e: any) => {

    loadSearch.value = true
    await useApi().post('pmkp/hapus-identifikasi-resiko', { 'norec': e.norec }).then((response) => {
        fetchOrder()
    })
}


const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda yakin menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteData(e)
        },
        reject: () => { },
    })
}

const gotoPageEdit = (e: any) => {
    router.push({
        name: 'module-indikator-form-identifikasi-resiko',
        query: {
            norec: e.norec,
        },
    })
}

const gotoPageForm = () => {
    router.push({ name: 'module-indikator-form-identifikasi-resiko' })
}

const fetchDepartement = async (filter: any) => {

    await useApi().get(`emr/dropdown/departemen_m?select=id,namadepartemen&param_search=namadepartemen&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Departement.value = response
    })
}

const toggle = (event: any, e: any) => {
    console.log(event)
    op.value.toggle(event);
    selected.value = e
}

const tindakLanjut = (e:any)=>{}

fetchOrder()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

.c-title {
    margin-left: -21px;
    padding-top: 21px;
    padding-top: 18px;
    margin-top: -21px;
    border-top-left-radius: 11px;
    border-left: solid hsl(19deg 100% 75% / 72%) 3px;
    padding-bottom: 0px;
    margin-bottom: 2rem;
}

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 8px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
    margin-top: 14px;
}</style>
