<template>
    <section>

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-3">
                    <div class="column is-10 p-0">
                        <label class="title-page">Daftar Remunerasi Kelompok Penghasil</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-3" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 30rem" :loading="loadSearch"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2 pb-0" style="padding-top: 2rem;">
                                <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel">
                                    Export To Excel
                                </VButton>
                            </div>
                            <div class="column is-10">
                                <div class="columns is-multiline" style="justify-content: flex-end;">
                                    <div class="column is-4">
                                        <VField label="Periode Pagu" style="margin-bottom: 6px;" />
                                        <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField addons>
                                                    <VControl icon="feather:calendar">
                                                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                                    </VControl>
                                                    <VControl>
                                                        <VButton static><i class="fas fa-arrow-right"
                                                                aria-hidden="true"></i></VButton>
                                                    </VControl>
                                                    <VControl icon="feather:calendar">
                                                        <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <div class="column is-4">
                                        <VField class="is-rounded-select is-autocomplete-select" label="Ruangan">
                                            <VControl icon="feather:search" class="prime-auto-select">
                                                <AutoComplete v-model="item.qruangan" :suggestions="d_Ruangan"
                                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="Nama Ruangan" />
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

                                    <div class="column is-1 btn-search mt-3">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </template>
                    <Column field="noclosing" header="No Closing" />
                    <Column field="tglawal" header="TGL Awal" />
                    <Column field="tglakhir" header="TGL Akhir" />
                    <Column field="namaruangan" header="Ruangan" />
                    <Column field="total" header="Total Remunerasi" style="text-align:right">
                        <template #body="slotProps">
                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.total), 2), '') }}
                        </template>
                    </Column>
                    <Column :exportable="false" header="Action" style="text-align:center">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-search-plus" class="mr-2" color="warning" circle
                                outlined raised v-tooltip.top="'Detail'" @click="detail(slotProps.data)">
                            </VIconButton>
                        </template>
                    </Column>
                    <ColumnGroup type="footer">
                        <Row>
                            <Column footer="Total" style="padding: 0.3rem 0.3rem 0 0.3rem" />
                            <Column />
                            <Column />
                            <Column />
                            <Column :footer="item.FtotalRemun" style="padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                            <Column />
                        </Row>
                    </ColumnGroup>
                </DataTable>
            </VCard>
        </div>
    </section>

    <Dialog v-model:visible="modalDetail" modal header="Detail" :style="{ width: '60vw' }">
        <form class="modal-form">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>No Closing</VLabel>
                        <VControl>
                            <input v-model="item.noclosing" class="input" disabled />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VLabel>Ruangan</VLabel>
                        <VControl>
                            <input v-model="item.namaRuangan" class="input" disabled />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VLabel>Total Remun</VLabel>
                        <VControl>
                            <input v-model="item.totalRemun" class="input" disabled />
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="content">
                <div class="is-divider" data-content="RINCIAN" />
            </div>
            <div class="columns is-multiline">
                <div class="column is-6">
                    <VField class="is-rounded-select is-autocomplete-select" label="Pegawai">
                        <VControl icon="feather:search" class="prime-auto-select">
                            <AutoComplete v-model="item.pegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Pegawai" />
                        </VControl>
                    </VField>
                </div>
                <div class="column">
                    <VField>
                        <VLabel>Jasa Remun</VLabel>
                        <VControl>
                            <input v-model="item.jasaRemun" class="input" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-1 btn-search mt-5">
                    <VIconButton color="success" icon="fas fa-plus-circle" :loading="loadSearch" v-tooltip.top="'Tambah'"
                        @click="tambah(item)" />
                </div>
            </div>
            <DataTable :rows="5" :value="data" :rowsPerPageOptions="[5, 10, 15]" class="p-datatable-sm" breakpoint="960px"
                selectionMode="single" sortMode="multiple" showGridlines tableStyle="min-width: 30rem"
                :loading="isLoadDetail"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                <Column field="no" header="No" style="width:1rem" />
                <Column field="namapegawai" header="Pegawai" />
                <Column field="pagunilai" header="Jasa Remun" />
                <Column header="Action" style="width: 7rem;text-align:center">
                    <template #body="slotProps">
                        <VIconButton outlined color="info" icon="fas fa-pen-square" :loading="loadSearch"
                            v-tooltip.top="'Edit'" style="margin-right:5px" @click="edit(slotProps.data)" />
                        <VIconButton outlined color="danger" icon="fas fa-trash-alt" @click="hapus(slotProps.data)"
                            :loading="loadSearch" v-tooltip.top="'Hapus'" />
                    </template>
                </Column>
            </DataTable>

        </form>

        <template #footer>
            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalDetail = false"
                style="margin-right:1rem">
                Tutup
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
            </VButton>
        </template>

    </Dialog>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import DataTable from 'primevue/datatable'
import AutoComplete from 'primevue/autocomplete';
import OverlayPanel from 'primevue/overlaypanel';
import ColumnGroup from 'primevue/columngroup';
import Dialog from 'primevue/dialog';
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

const confirm = useConfirm()
const dataSource: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
let d_Ruangan: any = ref([])
let d_Pegawai: any = ref([])
let data: any = ref([])
let loadData: any = ref(true)
let loadSearch: any = ref(false)
let loadSave: any = ref(false)
let isLoading: any = ref(false)
let isLoadDetail: any = ref(false)

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let ruangan = item.value.qruangan ? `&ruanganfk=${item.value.qruangan.value}` : ''
    let noclosing = item.value.noclosing ? `&noclosing=${item.value.noclosing}` : ''

    item.value.TtotalRemunerasi = 0
    loadSearch.value = true
    await useApi().get(`remunerasi/get-daftar-remun-kelompok?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${ruangan}${noclosing}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.TtotalRemunerasi = parseFloat(element.total) + item.value.TtotalRemunerasi
        });

        item.value.FtotalRemun = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.TtotalRemunerasi, 2), '')
        dataSource.value = response
    })
    loadData.value = false
    loadSearch.value = false
}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Remunerasi Kelompok Penghasil'],
        [],
        ['NO', 'NO CLOSING', 'TGL AWAL', 'TGL AKHIR', 'RUANGAN', 'TOTAL REMUNERASI'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.noclosing,
            e.tglawal,
            e.tglakhir,
            e.namaruangan,
            e.total ? H.roundToDecimal(parseFloat(e.total), 2) : 0,
        ]),
        ['', 'TOTAL', '', '', '',
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

    const columnWidths = [5, 13, 18, 18, 24, 18];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 5 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Kelompok Penghasil', true);
    XLSXStyle.writeFile(workbook, 'Daftar Remunerasi Kelompok Penghasil.xlsx');
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

const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const fetchDetail = async (noclosing: any, ruanganfk: any) => {

    isLoadDetail.value = true
    await useApi().get(`remunerasi/get-data-detail-kelompok?noclosing=${noclosing}&ruanganfk=${ruanganfk}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        data.value = response
        isLoadDetail.value = false
    })

}

const tambah = (e: any) => {

    let datas: any = {}

    if (item.value.no != undefined) {
        data.value.forEach((element: any, i: any) => {
            if (element.no == e.no) {
                console.log(element)
                datas.no = element.no
                datas.pegawaiid = e.pegawai.value
                datas.namapegawai = e.pegawai.label
                datas.pagunilai = e.jasaRemun
                data.value[i] = datas
            }
        });
    } else {
        let datas = {
            no: data.value.length == 0 ? 1 : data.value.length + 1,
            pegawaiid: item.value.pegawai.value,
            namapegawai: item.value.pegawai.label,
            pagunilai: item.value.jasaRemun
        }
        data.value.push(datas)
    }
    delete item.value.no
    delete item.value.pegawai
    delete item.value.jasaRemun
}

const edit = (e: any) => {
    item.value.no = e.no
    item.value.pegawai = { value: e.pegawaiid, label: e.namapegawai }
    item.value.jasaRemun = e.pagunilai
}

const hapus = (e: any) => {
    data.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
            data.value.splice(i, 1)
        }
    });
}

const simpan = async () => {

    if (data.value.length < 1) {
        H.alert('error', 'Data Masih Kosong')
        return
    }
    let objSave = {
        'noclosing': item.value.noclosing,
        'ruanganfk': item.value.ruanganfk,
        'djpid': item.value.djpid,
        'jpid': item.value.jpid,
        'details': data.value,
    }
    isLoading.value = true
    let response = useApi().post('remunerasi/save-detail-kelompok', objSave).then((response) => {
        modalDetail.value = false
        delete item.value.noclosing
        delete item.value.totalRemun
        delete item.value.jasaRemun
        delete item.value.namaRuangan
        delete item.value.ruanganfk
        delete item.value.djpid
        delete item.value.jpid
        data.value = [];
    })
    isLoading.value = false

}

const detail = (e: any) => {

    item.value.noclosing = e.noclosing
    item.value.totalRemun = e.total
    item.value.jasaRemun = e.total
    item.value.namaRuangan = e.namaruangan
    item.value.ruanganfk = e.ruid
    item.value.djpid = e.djpid
    item.value.jpid = e.jpid
    modalDetail.value = true
    fetchDetail(e.noclosing, e.ruid)
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
