
<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Laporan Diagnosa Pasien</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton icon="lnir lnir-arrow-left rem-100"
                                        :to="{ name: 'module-registrasi-daftar-pembatalan-pasien' }" light dark-outlined>
                                        Cancel
                                    </VButton>
                                    <VButton type="button" icon="feather:search" :loading="isLoading" color="success" raised
                                        @click="cariRiwayat()"> Cari
                                    </VButton>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;">Filter Pencarian</h1>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <label style="margin-bottom: 0.5rem;">Tanggal Diagnosa</label>
                                            <div class="columns is-multiline mb-3 mt-2">
                                                <div class="column is-6">
                                                    <VField>
                                                        <VDatePicker v-model="input.tglAwal" mode="date" style="width: 100%"
                                                            trim-weeks :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue" placeholder="Tanggal"
                                                                            v-on="inputEvents" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                </div>
                                                <div class="column is-6">
                                                    <VField>
                                                        <VDatePicker v-model="input.tglAkhir" mode="date"
                                                            style="width: 100%" trim-weeks :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue" placeholder="Tanggal"
                                                                            v-on="inputEvents" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-6">
                                                    <label style=" margin-bottom: 0.5rem;">Departement
                                                    </label>
                                                    <VField>
                                                        <VControl class="prime-auto">
                                                            <AutoComplete v-model="input.departement"
                                                                :suggestions="d_Departement" :optionLabel="'label'"
                                                                @complete="fetchDepartement($event)" :dropdown="true"
                                                                :minLength="3" :appendTo="'body'"
                                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                placeholder="Departemnt..." class="mt-2" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6">
                                                    <label style=" margin-bottom: 0.5rem;">Ruangan
                                                    </label>
                                                    <VField>
                                                        <VControl class="prime-auto">
                                                            <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan"
                                                                :optionLabel="'label'" @complete="fetchRuangan($event)"
                                                                :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                placeholder="Ruangan..." class="mt-2" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12 mt-5">
                                            <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                                @click="exportExcel()"> Export to Excel </VButton>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="column is-12">
                                <div class="fieldset-heading mb-5">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Daftar Tracer</h1>
                                </div>
                                <div class="mt-5">
                                    <div class="w-full">
                                        <DataTable :value="d_Diagnosis" :loading="isLoading" :paginator="true" :rows="10"
                                            :rowsPerPageOptions="[5, 10, 25]" clas="p-datatable-small"
                                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                            showGridlines>
                                            <Column field="namadiagnosa" header="Diagnosa"></Column>
                                            <Column field="kddiagnosa" header="ICD X"></Column>
                                            <Column field="kasusbarulk" header="Kasus Baru Laki-Laki"></Column>
                                            <Column field="kasusbarup" header="Kasus Baru Perempuan"></Column>
                                            <Column field="total_kasus" header="Kasus 4+5"></Column>
                                            <Column field="jumlah" header="Jumlah"></Column>
                                        </DataTable>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as XLSX from "xlsx";
const input: any = ref({})
let isLoading = ref(false)
const d_Diagnosis: any = ref([])
const dataSourceICD9 = ref([])
const date = ref(new Date())
const d_Departement: any = ref([]);
const d_Ruangan: any = ref([]);
const remakeData: any = ref([]);


useHead({
    title: 'Laporan Diagnosa Pasien - ' + import.meta.env.VITE_PROJECT,
})
async function cariRiwayat() {
    let object: any = {};
    object = input.value;
    let startDate = convertDate(object.tglAwal);
    let endDate = convertDate(object.tglAkhir);
    let ruangan = input.value.ruangan ? input.value.ruangan.value : "";
    let departement = input.value.departement ? input.value.departement.value : "";

    isLoading.value = true;
    useApi().get(
        `/resgistrasi/get-top-ten-diagnosa?tglAwal=${startDate}&tglAkhir=${endDate}&ruangan=${ruangan}&ruangan=${departement}`).then((response: any) => {
            d_Diagnosis.value = response
            isLoading.value = false
        }).catch((e: any) => {
            isLoading.value = false
        })
}
const setAutoFill = () => {
    input.value.tglAwal = new Date()
    input.value.tglAkhir = new Date()
}

const convertDate = (date) => {
    const dateObject = new Date(date);
    const day = dateObject.getDate().toString().padStart(2, '0');
    const month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
    const year = dateObject.getFullYear();
    const formattedDate = `${year}-${month}-${day}`;
    return formattedDate;
}


const fetchDepartement = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/departemen_m?select=id,namadepartemen&param_search=namadepartemen&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Departement.value = response
    })
}
const fetchRuangan = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const exportExcel = () => {
    remakeData.value = d_Diagnosis.value.map((e: any) => {
        return {
            NamaDiagnosa: e.namadiagnosa, KDDiagnosis: e.kddiagnosa, KasusBaruLaki: e.kasusbarulk,
            KasusBaruPerempuan: e.kasusbarup, TotalKasus: e.total_kasus, Jumlah: e.jumlah,
        }
    })
    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}
const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
        type: EXCEL_TYPE
    });
    const _url = window.URL.createObjectURL(data)
    window.open(_url, EXCEL_EXTENSION).focus();
}
setAutoFill();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: 1200px;
    margin: 0 auto;
}

.form-fieldset {
    padding: 10px 0;
    max-width: 100%;
    margin: 0 auto;
}

.table-pi {
    width: 1400px;
    border: 1px solid #929090;
}

.table-scroll {
    overflow-x: scroll;
}

.date {
    background-color: #9b9b9b;
    color: #fff;
}
</style>
