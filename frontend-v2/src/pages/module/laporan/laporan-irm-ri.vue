
<template>
    <div class="column">
        <VCard>
            <div class="column is-12">
                <div class="search-widget">
                    <div class="field">
                        <div class="columns is-multiline">
                            <div class="column is-4 pt-0 pb-0">
                                <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" class="input-calendar"
                                                    v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" class="input-calendar"
                                                    v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-2 pt-0 pb-0">
                                <VIconButton type="button" color="success" class="searcv-button pt-3" raised
                                    icon="fas fa-search"
                                    @click="fetchKelomopok(); fetchPendidikan(); fetchDaerah(); fetchUsia(); fetchPekerjaan(); fetchAgama(); fetchItem();"
                                    :loading="isPlaceLoad">
                                </VIconButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </VCard>
    </div>

    <div class="column">
        <VCard>
            <h3 class="title is-5 mb-2">Summary Register Pendaftaran Instalasi RI</h3>

            <VTabs slider align="centered" selected="carabayar" style="font-weight: bold;" :tabs="[
                { label: 'CARA BAYAR', value: 'carabayar' },
                { label: 'PENDIDIKAN', value: 'pendidikan' },
                { label: 'DAERAH ASAL', value: 'daerahasal' },
                { label: 'KELOMPOK UMUR', value: 'kelompokumur' },
                { label: 'PEKERJAAN', value: 'pekerjaan' },
                { label: 'AGAMA', value: 'agama' },
                { label: 'ITEM RI', value: 'itemri' },
            ]" class="mt-5 px-3">
                <template #tab="{ activeValue }">
                    <p v-if="activeValue === 'carabayar'">
                        <DataTable :value="dataSource" tableStyle="min-width: 50rem">
                            <template #header>
                                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                        @click="exportExcelKelompok()"> Export
                                        to
                                        Excel </VButton>
                                </div>
                            </template>
                            <Column field="no" header="no" style="width:10px;"></Column>
                            <Column field="kelompokpasien" header="Cara Bayar"></Column>
                            <Column field="jumlah" header="Jumlah"></Column>
                        </DataTable>
                    </p>
                    <p v-if="activeValue === 'pendidikan'">
                        <DataTable :value="dataPenddikan" tableStyle="min-width: 50rem">
                            <template #header>
                                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                        @click="exportExcelPendidikan()"> Export
                                        to
                                        Excel </VButton>
                                </div>
                            </template>
                            <Column field="no" header="no" style="width:10px;"></Column>
                            <Column field="pendidikan" header="Pendidikan"></Column>
                            <Column field="jumlah" header="Jumlah"></Column>
                        </DataTable>
                    </p>
                    <p v-if="activeValue === 'daerahasal'">
                        <DataTable :value="dataDaerah" tableStyle="min-width: 50rem">
                            <template #header>
                                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                        @click="exportExcelDaerah()"> Export
                                        to
                                        Excel </VButton>
                                </div>
                            </template>
                            <Column field="no" header="no" style="width:10px;"></Column>
                            <Column field="namakotakabupaten" header="Daerah"></Column>
                            <Column field="jumlah" header="Jumlah"></Column>
                        </DataTable>
                    </p>
                    <p v-if="activeValue === 'kelompokumur'">
                        <DataTable :value="dataUsia" tableStyle="min-width: 50rem">
                            <template #header>
                                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                        @click="exportExcelUsia()"> Export
                                        to
                                        Excel </VButton>
                                </div>
                            </template>
                            <Column field="no" header="no" style="width:10px;"></Column>
                            <Column field="usia" header="Usia"></Column>
                            <Column field="jml" header="Jumlah"></Column>
                        </DataTable>
                    </p>
                    <p v-if="activeValue === 'pekerjaan'">
                        <DataTable :value="dataPekerjaan" tableStyle="min-width: 50rem">
                            <template #header>
                                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                        @click="exportExcelPekerjaan()"> Export
                                        to
                                        Excel </VButton>
                                </div>
                            </template>
                            <Column field="no" header="no" style="width:10px;"></Column>
                            <Column field="pekerjaan" header="Pekerjaan"></Column>
                            <Column field="jumlah" header="Jumlah"></Column>
                        </DataTable>
                    </p>
                    <p v-if="activeValue === 'agama'">
                        <DataTable :value="dataAgama" tableStyle="min-width: 50rem">
                            <template #header>
                                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                        @click="exportExcelAgama()"> Export
                                        to
                                        Excel </VButton>
                                </div>
                            </template>
                            <Column field="no" header="no" style="width:10px;"></Column>
                            <Column field="agama" header="Agama"></Column>
                            <Column field="jumlah" header="Jumlah"></Column>
                        </DataTable>
                    </p>
                    <p v-if="activeValue === 'itemri'">
                        <DataTable :value="dataItem" tableStyle="min-width: 50rem">
                            <template #header>
                                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                        @click="exportExcelItem()"> Export
                                        to
                                        Excel </VButton>
                                </div>
                            </template>
                            <Column field="no" header="no" style="width:10px;"></Column>
                            <Column field="ket" header="Item RI"></Column>
                            <Column field="jumlah" header="Jumlah"></Column>
                        </DataTable>
                    </p>
                </template>
            </VTabs>

        </VCard>
    </div>
</template>
  
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import * as XLSX from "xlsx";
useHead({
    title: 'Laporan Kunjungan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const item: any = ref({
    qFilterTgl: {
        start: new Date(),
        end: new Date()
    },
})


const currentPage: any = ref({
    limit: 5,
    rows: 50,
})
const remakeData: any = ref([])
let dataSource: any = ref([])
let dataPenddikan: any = ref([])
let dataDaerah: any = ref([])
let dataUsia: any = ref([])
let dataPekerjaan: any = ref([])
let dataAgama: any = ref([])
let dataItem: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let d_Ruangan: any = ref([])
let d_KelompokPasien: any = ref([])
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters = ref('')

const dataTagihanBelumLunas = computed(() => {
    if (!item.value.qFilter) {
        return dataHutang.value
    }
    return dataHutang.value.filter((items: any) => {
        return (
            items.namapasien.match(new RegExp(item.value.qFilter, 'i')) ||
            items.noRegistrasi.match(new RegExp(item.value.qFilter, 'i'))
        )
    })
})

const fetchKelomopok = async () => {
    isPlaceLoad.value = true
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')

    await useApi().get(`resgistrasi/get-laporan-demo-ri-kelompok?${tglAwal}${tglAkhir}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataSource.value = response.data
    })
    isPlaceLoad.value = false
}

const fetchPendidikan = async () => {
    isPlaceLoad.value = true
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')

    await useApi().get(`resgistrasi/get-laporan-demo-ri-pendidikan?${tglAwal}${tglAkhir}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataPenddikan.value = response.data
    })
    isPlaceLoad.value = false
}

const fetchDaerah = async () => {
    isPlaceLoad.value = true
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')

    await useApi().get(`resgistrasi/get-laporan-demo-ri-daerah?${tglAwal}${tglAkhir}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataDaerah.value = response.data
    })
    isPlaceLoad.value = false
}

const fetchUsia = async () => {
    isPlaceLoad.value = true
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')

    await useApi().get(`resgistrasi/get-laporan-demo-ri-usia?${tglAwal}${tglAkhir}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataUsia.value = response.data
    })
    isPlaceLoad.value = false
}

const fetchPekerjaan = async () => {
    isPlaceLoad.value = true
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')

    await useApi().get(`resgistrasi/get-laporan-demo-ri-pekerjaan?${tglAwal}${tglAkhir}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataPekerjaan.value = response.data
    })
    isPlaceLoad.value = false
}

const fetchAgama = async () => {
    isPlaceLoad.value = true
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')

    await useApi().get(`resgistrasi/get-laporan-demo-ri-agama?${tglAwal}${tglAkhir}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataAgama.value = response.data
    })
    isPlaceLoad.value = false
}

const fetchItem = async () => {
    isPlaceLoad.value = true
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')

    await useApi().get(`resgistrasi/get-laporan-demo-ri-item?${tglAwal}${tglAkhir}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataItem.value = response.data
    })
    isPlaceLoad.value = false
}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const fetchKelompokPasien = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
    })
}

const exportExcelKelompok = () => {
    remakeData.value = dataSource.value.map((e: any) => {
        return {
            'No': e.no,
            'Cara bayar': e.kelompokpasien,
            'Jumlah': e.jumlah,
        }
    })

    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)

    // Customize column widths (adjust the width values as needed)
    const columnWidths = [
        { wch: 3 }, // Kolom A
        { wch: 14 }, // Kolom B
        { wch: 10 }, // Kolom C
    ];

    worksheet['!cols'] = columnWidths;

    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}

const exportExcelPendidikan = () => {
    remakeData.value = dataPenddikan.value.map((e: any) => {
        return {
            'No': e.no,
            'Pendidikan': e.pendidikan,
            'Jumlah': e.jumlah,
        }
    })

    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)

    // Customize column widths (adjust the width values as needed)
    const columnWidths = [
        { wch: 3 }, // Kolom A
        { wch: 14 }, // Kolom B
        { wch: 10 }, // Kolom C
    ];

    worksheet['!cols'] = columnWidths;

    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}

const exportExcelDaerah = () => {
    remakeData.value = dataDaerah.value.map((e: any) => {
        return {
            'No': e.no,
            'Daerah': e.namakotakabupaten,
            'Jumlah': e.jumlah,
        }
    })

    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)

    // Customize column widths (adjust the width values as needed)
    const columnWidths = [
        { wch: 3 }, // Kolom A
        { wch: 14 }, // Kolom B
        { wch: 10 }, // Kolom C
    ];

    worksheet['!cols'] = columnWidths;

    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}
const exportExcelUsia = () => {
    remakeData.value = dataUsia.value.map((e: any) => {
        return {
            'No': e.no,
            'Usia': e.usia,
            'Jumlah': e.jml,
        }
    })

    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)

    // Customize column widths (adjust the width values as needed)
    const columnWidths = [
        { wch: 3 }, // Kolom A
        { wch: 14 }, // Kolom B
        { wch: 10 }, // Kolom C
    ];

    worksheet['!cols'] = columnWidths;

    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}
const exportExcelPekerjaan = () => {
    remakeData.value = dataPekerjaan.value.map((e: any) => {
        return {
            'No': e.no,
            'Pekerjaan': e.pekerjaan,
            'Jumlah': e.jumlah,
        }
    })

    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)

    // Customize column widths (adjust the width values as needed)
    const columnWidths = [
        { wch: 3 }, // Kolom A
        { wch: 14 }, // Kolom B
        { wch: 10 }, // Kolom C
    ];

    worksheet['!cols'] = columnWidths;

    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}
const exportExcelAgama = () => {
    remakeData.value = dataAgama.value.map((e: any) => {
        return {
            'No': e.no,
            'Agama': e.agama,
            'Jumlah': e.jumlah,
        }
    })

    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)

    // Customize column widths (adjust the width values as needed)
    const columnWidths = [
        { wch: 3 }, // Kolom A
        { wch: 14 }, // Kolom B
        { wch: 10 }, // Kolom C
    ];

    worksheet['!cols'] = columnWidths;

    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}
const exportExcelItem = () => {
    remakeData.value = dataItem.value.map((e: any) => {
        return {
            'No': e.no,
            'Item': e.item,
            'Jumlah': e.jumlah,
        }
    })

    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)

    // Customize column widths (adjust the width values as needed)
    const columnWidths = [
        { wch: 3 }, // Kolom A
        { wch: 14 }, // Kolom B
        { wch: 10 }, // Kolom C
    ];

    worksheet['!cols'] = columnWidths;

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
    // window.open(_url,EXCEL_EXTENSION).focus()
    // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

fetchKelomopok()
fetchPendidikan()
fetchDaerah()
fetchUsia()
fetchPekerjaan()
fetchAgama()
fetchItem(); fetchItem()
</script>
  