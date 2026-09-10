<template>
    <section>

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Daftar Remunerasi Pegawai</label>
                    </div>
                    <div class="column is-12 pt-3">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField label="Periode Pagu" style="margin-bottom: 6px;" />
                                <VDatePicker v-model="item.filterTgl" is-range class="mt-2" color="pink" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-2">
                                <VField class="is-rounded-select is-autocomplete-select" label="Pegawai">
                                    <VControl icon="feather:search" class="prime-auto-select">
                                        <AutoComplete v-model="item.qpegawai" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                            placeholder="Nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField class="is-rounded-select is-autocomplete-select" label="Ruangan">
                                    <VControl icon="feather:search" class="prime-auto-select">
                                        <AutoComplete v-model="item.qruangan" :suggestions="d_Ruangan"
                                            @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                            placeholder="Nama Ruangan" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <VLabel>No Closing</VLabel>
                                    <VControl>
                                        <input v-model="item.noclosing" class="input" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2 mt-4">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="item.isKelompokPenghasil" :value="true"
                                            :label="'Kelompok Penghasil'" color="info" square
                                            :class="item.cekAll == true ? 'is-solid' : ''" />
                                    </VControl>
                                </VField>
                            </div>

                            <div class="column btn-search mt-3">
                                <VIconButton color="success" icon="fas fa-search" @click="fetchData" :loading="loadSearch" />
                            </div>
                        </div>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadSearch" />
                <DataTable v-else :rows="10" :value="dataSource" :rowsPerPageOptions="[5, 10, 15, 50, 100, 1000]" class="p-datatable-sm mt-3"
                    breakpoint="960px" selectionMode="single" sortMode="multiple" v-model:expanded-rows="expandedRows"
                    showGridlines tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="column pt-0 pb-0">
                            <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel">
                                Export To Excel
                            </VButton>
                        </div>
                    </template>
                    <Column field="noclosing" header="No Closing" />
                    <Column field="namakaryawan" header="Pegawai" />
                    <Column field="tglawal" header="TGL Awal" />
                    <Column field="tglakhir" header="TGL Akhir" />
                    <Column field="golongan" header="Golongan" />
                    <Column field="jabatan" header="Jabatan" />
                    <Column field="ruangankerja" header="Ruangan / Bagian" />
                    <Column field="nip" header="NIP" />
                    <Column field="total" header="Total Remunerasi" style="text-align:right" sortable>
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.total), 2), '') }}
                        </template>
                    </Column>
                    <Column :exportable="false"  header="Action" style="text-align:center">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-search-plus" class="mr-2" color="warning" circle outlined
                                raised  v-tooltip.top="'Detail'" @click="gotoDetail(slotProps.data)">
                            </VIconButton>
                        </template>
                    </Column>
                    <ColumnGroup type="footer">
                        <Row>
                            <Column footer="Total" style="padding: 0.3rem 0.3rem 0 0.3rem" />
                            <Column />
                            <Column />
                            <Column />
                            <Column />
                            <Column />
                            <Column />
                            <Column />
                            <Column :footer="item.FtotalRemun" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column />
                        </Row>
                    </ColumnGroup>
                    <template #footer>
                        <div class="column pt-0 pb-0" style="text-align:right">
                            <VButtons style="justify-content: flex-end">
                                <VButton class="mr-4" color="primary" icon="fas fa-print"
                                    style="padding-right: 3rem;padding-left: 3rem;" @click="cetak"> Cetak
                                </VButton>
                                <!-- <VButton class="mr-4" color="info" icon="fas fa-book"
                                    style="padding-right: 3rem;padding-left: 3rem;" @click="detail"> Detail
                                </VButton> -->
                                <!-- <VButton class="mr-4" color="warning" raised> Potongan </VButton>
                                <VButton class="mr-4" color="primary" raised @click="goToMap"> Map Pegawai </VButton>
                                <VButton color="info" raised> Detail Sumber Dana </VButton> -->
                            </VButtons>
                        </div>
                    </template>
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
    title: 'Daftar Remunerasi Pegawai - ' + import.meta.env.VITE_PROJECT,
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
let loadSearch: any = ref(true)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let pegawai = item.value.qpegawai ? `&pegawaifk=${item.value.qpegawai.value}` : ''
    let ruangan = item.value.qruangan ? `&ruanganfk=${item.value.qruangan.value}` : ''
    let kelompok = item.value.isKelompokPenghasil ? `&iskelompokpenghasil=${item.value.isKelompokPenghasil}` : ''
    let noclosing = item.value.noclosing ? `&noclosing=${item.value.noclosing}` : ''

    item.value.TtotalRemunerasi = 0

    await useApi().get(`remunerasi/get-daftar-remun-pegawai?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${pegawai}${ruangan}${kelompok}${noclosing}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.TtotalRemunerasi = parseFloat(element.total) + item.value.TtotalRemunerasi
        });

        item.value.FtotalRemun = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.TtotalRemunerasi, 2), '')
        dataSource.value = response
    })
    loadSearch.value = false
}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Remunerasi Pegawai'],
        [],
        ['NO', 'NO CLOSING', 'TGL AWAL', 'TGL AKHIR', 'NAMA PEGAWAI', 'SK PERTAMA', 'GOLONGAN',
            'RUANGAN / BAGIAN', 'NPWP', 'NIP', 'NO REKENING', 'NAMA REKENING', 'TOTAL REMUNERASI'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.noclosing,
            e.tglawal,
            e.tglakhir,
            e.namakaryawan,
            e.skpertamamasukrs,
            e.golongan,
            e.ruangankerja,
            e.npwp,
            e.nip,
            e.nomorrekening,
            e.namarekening,
            e.total ? H.roundToDecimal(parseFloat(e.total), 2) : 0,
        ]),
        ['', 'TOTAL', '', '', '', '', '', '', '', '', '', '',
            H.roundToDecimal(parseFloat(item.value.TtotalRemunerasi), 2),
        ]
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

    const columnWidths = [5, 13, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 12 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Remunerasi Pegawai', true);
    XLSXStyle.writeFile(workbook, 'Daftar Remunerasi Pegawai.xlsx');
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

const closingData = async () => {
    let awal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD 00:00:00')
    let akhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD 23:59:59')

    let objSave = {
        periodeawal: awal,
        periodeakhir: akhir
    }
    let norecsc = ''
    await useApi().post('remunerasi/closing-direksi', objSave).then((response: any) => {
        norecsc = response.norecsc
        let objSave =
        {
            periodeawal: awal,
            periodeakhir: akhir,
            norecsc: norecsc
        }
        useApi().post('remunerasi/closing-rcd', objSave).then((response2: any) => {
            useApi().post('remunerasi/closing-rc', objSave).then(function (e) {
                useApi().post('remunerasi/closing-cc', objSave).then(function (e) {
                    useApi().post('remunerasi/closing-ccs', objSave).then(function (e) {
                        // useApi().post('remunerasi/save-closing-potongan', objSave).then(function (e) {

                        // })
                    })
                })
            })
        })

    })
}

const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const gotoDetail = (e:any) => {
    router.push({
        name: 'module-remunerasi-detail-remunerasi-pegawai',
        query: {
            iddokter : e.pgid,
            noclosing : e.noclosing,
            namapegawai :  e.namakaryawan,
            klmpenghasil :  item.value.isKelompokPenghasil ? item.value.isKelompokPenghasil : false
        }
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
