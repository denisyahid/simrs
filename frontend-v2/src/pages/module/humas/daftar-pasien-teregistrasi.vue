<template>
    <section>

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Daftar Informasi Pasien Teregistrasi</label>
                    </div>
                    <div class="column is-12 pt-3">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField label="Periode Registrasi" style="margin-bottom: 6px;" />
                                <VDatePicker v-model="item.filterTgl" is-range class="mt-2" color="pink" trim-weeks>
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
                            <!-- <div class="column is-2">
                                <VField class="is-rounded-select is-autocomplete-select" label="Pegawai">
                                    <VControl icon="feather:search" class="prime-auto-select">
                                        <AutoComplete v-model="item.qpegawai" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div> -->
                            <div class="column is-9">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-4">
                                        <VField class="is-rounded-select is-autocomplete-select" label="Ruangan">
                                            <VControl icon="feather:search" class="prime-auto-select">
                                                <AutoComplete v-model="item.qruangan" :suggestions="d_Ruangan"
                                                    @complete="fetchRuangan($event)" :optionLabel="'label'"
                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    placeholder="Nama Ruangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField class="is-rounded-select is-autocomplete-select" label="Departemen">
                                            <VControl icon="feather:search" class="prime-auto-select">
                                                <AutoComplete v-model="item.departemen" :suggestions="d_Instalasi"
                                                    @complete="fetchDepartemen($event)" :optionLabel="'label'"
                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    placeholder="Nama Departemen" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Pencarian</VLabel>
                                            <VControl>
                                                <input v-model="item.qsearch" class="input"
                                                    placeholder="Nama Pasien, No RM, No Registrasi" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-1 btn-search mt-5">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadSearch" />
                <DataTable v-else :rows="10" :value="dataSource" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-3" breakpoint="960px" selectionMode="single" sortMode="multiple"
                    v-model:expanded-rows="expandedRows" showGridlines tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="column pt-0 pb-0">
                            <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel">
                                Export To Excel
                            </VButton>
                        </div>
                    </template>
                    <Column field="no" header="NO" />
                    <Column field="tglregistrasi" header="TGL Regisrasi">
                        <template #body="slotProps">
                            {{ H.formatDateIndoSimpleNoDay(slotProps.data.tglregistrasi) }}
                        </template>
                    </Column>
                    <Column field="noregistrasi" header="No Regis" />
                    <Column field="nocm" header="No RM" />
                    <Column field="namapasien" header="Nama Pasien" />
                    <Column field="namaruangan" header="Ruangan" />
                    <Column field="namadokter" header="Dokter" />
                    <Column field="kelompokpasien" header="Kelompok Pasien" />
                    <Column field="tglpulang" header="TGL Pulang">
                        <template #body="slotProps">
                            {{ slotProps.data.tglpulang ? H.formatDateIndoSimpleNoDay(slotProps.data.tglpulang) : '' }}
                        </template>
                    </Column>
                    <Column field="statuspasien" header="Status" />
                    <Column field="nostruk" header="No Struk Verif" />
                    <Column field="nosbm" header="No SBM" />
                    <Column field="kasir" header="Kasir" />
                    <Column field="nosep" header="No SEP" />
                </DataTable>
            </VCard>
        </div>
    </section>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import DataTable from 'primevue/datatable'
import AutoComplete from 'primevue/autocomplete';
import OverlayPanel from 'primevue/overlaypanel';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Daftar Pasien Teregistrasi - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    tglPelayanan: new Date(),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const router = useRouter()
const confirm = useConfirm()
const activeTab = ref(0);
const dataSource: any = ref([])
const dataSourceNilaiPagu: any = ref([])
const detailResep: any = ref([])
const op = ref();
const selected: any = ref({})
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
const expandedRows = ref();
let d_Pegawai: any = ref([])
let d_Ruangan: any = ref([])
let d_Instalasi: any = ref([])
let loadSearch: any = ref(true)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let search = item.value.qsearch ? `&keyword=${item.value.qsearch}` : ''
    let ruangan = item.value.qruangan ? `&ruanganfk=${item.value.qruangan.value}` : ''
    let departemen = item.value.departemen ? `&departemen=${item.value.departemen.value}` : ''

    item.value.TtotalRemunerasi = 0
    loadSearch.value = true
    await useApi().get(`humas/get-pasien-teregistrasi?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${search}${ruangan}${departemen}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataSource.value = response
    })
    loadSearch.value = false
}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Pasien Teregistrasi'],
        [],
        ['NO', 'TANGGAL REGISTRASI','NO REGISTRASI','NO RM','NAMA PASIEN','NAMA RUANGAN','NAMA DOKTER','KELOMPOK PASIEN','TANGGAL PULANG',
         'STATUS','NO STRUK VERIF','NO SBM','KASIR','NO SEP'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.tglregistrasi,
            e.noregistrasi,
            e.nocm,
            e.namapasien,
            e.namaruangan,
            e.namadokter,
            e.kelompokpasien,
            e.tglpulang,
            e.statuspasien,
            e.nostruk,
            e.nosbm,
            e.kasir,
            e.nosep,
        ]),
    ]);
    // Mendefinisikan style untuk header(centered)
    const headerStyle = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            color: { rgb: 'FFFFFF' }
        },
        fill: { fgColor: { rgb: '807C7C' } }
    };

    // Mendefinisikan range header
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
        worksheet[headerCell].s = headerStyle;
    }

    const columnWidths = [5, 13, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18];

    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        worksheet['!cols'] = worksheet['!cols'] || [];
        worksheet['!cols'][col] = { wch: columnWidths[col] };
    }

    // Centering the text in cell A1
    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            bold: true,
            sz: 18
        }
    };

    // Menggabungkan dua baris pertama
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 13 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'pasien teregistrasi', true);
    XLSXStyle.writeFile(workbook, 'Daftar Pasien Tergistrasi.xlsx');
}

const changeStatusBayar = async (e: any, status: any) => {
    let objsave = {
        'norec': e.norec,
        'status': status
    }
    await useApi().post('remunerasi/verifikasi-bayar', objsave).then((response) => {
        fetchPagu()
    })
}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}
const fetchDepartemen = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/departemen_m?select=id,namadepartemen&param_search=namadepartemen&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Instalasi.value = response
    })
}
fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 0px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
}
</style>
