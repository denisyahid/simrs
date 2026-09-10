<template>
    <ConfirmDialog />
    <section>

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Daftar Insiden Keselamatan Pasien</label>
                    </div>
                </div>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <VField label="Periode" style="margin-bottom: 6px;" />
                            <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                        <div class="column is-3">
                            <VField class="is-rounded-select is-autocomplete-select" label="Departemen">
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <AutoComplete v-model="item.qdepartemenfk" :suggestions="d_Departement"
                                        @complete="fetchDepartement($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Pilih Departemen" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField label="Jenis Keselamatan">
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.qjenisKeselamatanfk" :options="d_JenisKeselamatan"
                                        :optionLabel="'label'" placeholder="Pilih Data" style="width: 100%;" :filter="true"
                                        showClear />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField class=" is-autocomplete-select" label="Keselamatan">
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.qkeselamatanfk" :options="d_KeselamatanReal"
                                        placeholder="Pilih Keselamatan" :searchable="true"
                                        @select="getInit(item.keselamatanfk)" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1 btn-search mt-3" style="justify-content:center">
                            <VIconButton color="success" icon="fas fa-search" @click="fetchData" :loading="loadSearch" />
                        </div>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2">
                                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                                    Export To Excel
                                </VButton>
                            </div>
                        </div>
                    </template>
                    <Column field="no" header="No" style="min-width: 10px;" />
                    <Column field="tanggal" header="TGL Insiden">
                        <template #body="slotProps">
                            {{ H.formatDate(slotProps.data.tanggal, 'YYYY-MM-DD') }}
                        </template>
                    </Column>
                    <Column field="jeniskeselamatan" header="Jenis Keselamatan" />
                    <Column field="keselamatan" header="Keselamatan" />
                    <Column field="departemen" header="Departemen / Unit" />
                    <Column field="jumlah" header="JML Insiden" />
                    <Column header="Action" style="text-align: center;">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined
                                raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                            </VIconButton>
                            <OverlayPanel ref="op">
                                <VButton type="button" icon="fas fa-print" class="mr-2" light circle outlined color="info"
                                    raised @click="showModel(selected)">
                                    Edit
                                </VButton>
                                <VButton type="button" icon="fas fa-trash" class="mr-2" color="danger" circle outlined
                                    @click="dialogConfirm(selected)" raised>Hapus
                                </VButton>
                                <VButton type="button" icon="fas fa-print" class="mr-2" color="purple" circle outlined
                                    raised>Cetak
                                </VButton>
                            </OverlayPanel>
                        </template>
                    </Column>
                    <template #footer>
                        <div class="column is-12" style="text-align:right">
                            <VButton color="primary" elevated style="padding-right: 25px;" @click="showModel()">
                                <i class="fas fa-plus-circle" aria-hidden="true" style="margin-right: 14px;"></i>
                                Tambah
                            </VButton>
                        </div>

                    </template>
                </DataTable>
            </VCard>
        </div>
    </section>

    <Dialog v-model:visible="modalInput" header="Input Data" :style="{ width: '55vw' }">

        <form class="modal-form">
            <div class="columns is-mulitline">
                <div class="column is-7">
                    <VField class=" is-autocomplete-select" label="Keselamatan">
                        <VControl icon="feather:search">
                            <Multiselect mode="single" v-model="item.keselamatanfk" :options="d_KeselamatanReal"
                                placeholder="Pilih Keselamatan" :searchable="true" @select="getInit(item.keselamatanfk)" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-5">
                    <VField label="Jenis Keselamatan">
                        <VControl icon="feather:search">
                            <Dropdown v-model="item.jeniskeselamatan" :options="d_JenisKeselamatan" optionLabel="label"
                                placeholder="Jenis Keselamatan" style="width: 100%; font-weight:bold" :filter="true"
                                disabled appendTo="body" />
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="columns is-multiline">
                <div class="column is-5">
                    <VField class="is-rounded-select is-autocomplete-select" label="Departemen">
                        <VControl icon="feather:search" class="prime-auto-select">
                            <AutoComplete v-model="item.departemenfk" :suggestions="d_Departement"
                                @complete="fetchDepartement($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Pilih Departemen" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField label="Tanggal & waktu Insiden">
                        <VDatePicker v-model="item.waktuKejadian" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                                <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </VField>
                </div>
                <div class="column is-3">
                    <h1 class="mb-2">Jumlah Insiden</h1>
                    <VField>
                        <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="item.jumlahInsiden" placeholder="QTY" />
                        </VControl>
                    </VField>
                </div>
            </div>
        </form>

        <template #footer>
            <VButtons style="justify-content: right;">
                <VButton color="warning" elevated icon="fas fa-times-circle" @click="clear()">
                    Batal
                </VButton>
                <VButton color="primary" icon="fas fa-plus-square" elevated @click="save()" :loading="loadBtnSave">
                    Simpan
                </VButton>
            </VButtons>
        </template>
    </Dialog>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import Dialog from 'primevue/dialog'
import OverlayPanel from 'primevue/overlaypanel';
import DataTable from 'primevue/datatable'
import AutoComplete from 'primevue/autocomplete';
import ColumnGroup from 'primevue/columngroup';
import Dropdown from 'primevue/dropdown';
import ConfirmDialog from 'primevue/confirmdialog'
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Daftar Insiden Keselamtan Pasien - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    waktuKejadian: new Date(),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const router = useRouter()
const confirm = useConfirm()
const dataSource: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
const op = ref();
const selected: any = ref({})
let d_Departement: any = ref([])
let d_Keselamatan: any = ref([])
let d_JenisKeselamatan: any = ref([])
let d_KeselamatanReal: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let modalInput: any = ref(false)
let isLoading: any = ref(false)
let loadBtnSave: any = ref(false)

const fetchData = async () => {
    let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let departemen = item.value.qdepartemenfk ? `&departemenfk=${item.value.qdepartemenfk.value}` : ''
    let keselamatan = item.value.qkeselamatanfk ? `&keselamatanfk=${item.value.qkeselamatanfk}` : ''
    let jenis = item.value.qjenisKeselamatanfk ? `&jeniskesalamatanfk=${item.value.qjenisKeselamatanfk.value}` : ''

    item.value.Ttotal = 0
    loadSearch.value = true
    await useApi().get(`pmkp/get-daftar-insiden-keselamatan-pasien?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${departemen}${keselamatan}${jenis}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataSource.value = response
    })
    loadData.value = false
    loadSearch.value = false
}

const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Insiden Keselamatan Pasien'],
        [],
        ['NO', 'TANGGAL KEJADIAN', 'JENIS KESELAMATAN', 'KESELAMATAN', 'JUMLAH'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.tanggal,
            e.jeniskeselamatan,
            e.keselamatan,
            e.departemen,
            e.jumlah,
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

    const columnWidths = [5, 15, 18 , 25, 20, 10];

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
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 4 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Insiden Keselamatab', true);
    XLSXStyle.writeFile(workbook, 'Daftar Insiden Keselamtan Pasien.xlsx');
}

const fetchDepartement = async (filter: any) => {

    await useApi().get(`emr/dropdown/departemen_m?select=id,namadepartemen&param_search=namadepartemen&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Departement.value = response
    })
}

const fetchDropdown = async () => {
    let response = await useApi().get(`/pmkp/get-data-combo-pmkp`)
    d_KeselamatanReal.value = response.insidenkeselamtanpasien.map((e: any) => {
        return { label: e.keselamatan, value: e.id }
    })
    d_Keselamatan.value = response.insidenkeselamtanpasien.map((e: any) => {
        return { label: e.keselamatan, value: e }
    })
    d_JenisKeselamatan.value = response.jeniskeselamatan.map((e: any) => {
        return { label: e.jeniskeselamatan, value: e.id }
    })
}

const getInit = (e: any) => {
    console.log(e)
    let jnskeselamatanfk: any
    d_Keselamatan.value.forEach((element: any) => {
        if (element.value.id == e) {
            jnskeselamatanfk = element.value.jeniskesalamatanfk
        }
    })

    d_JenisKeselamatan.value.forEach((element: any) => {
        if (element.value == jnskeselamatanfk) {
            item.value.jeniskeselamatan = element
        }
    });
    console.log(item.value.jeniskeselamatan)
}

const save = async () => {

    if (!item.value.keselamatanfk) {
        H.alert('error', 'Keselamatan Tidak Boleh Kosong')
        return
    }
    loadBtnSave.value = true
    let objSave = {
        "norec": item.value.norec ? item.value.norec : '',
        "departemenfk": item.value.departemenfk ? item.value.departemenfk.value : '',
        "keselamatanfk": item.value.keselamatanfk ? item.value.keselamatanfk : null,
        "jumlah": item.value.jumlahInsiden ? item.value.jumlahInsiden : null,
        "tanggal": H.formatDate(item.value.waktuKejadian, 'YYYY-MM-DD'),
    }
    useApi().post('pmkp/save-insiden-keselamatan', objSave).then((response) => {
        fetchData()
        clear()
        loadBtnSave.value = false
    }).catch((e)=>{
        loadBtnSave.value = false
    })
   
}

const toggle = (event: any, e: any) => {
    op.value.toggle(event);
    selected.value = e
}

const gotoPageEdit = (e: any) => {
    router.push({
        name: 'module-indikator-lembar-kerja-investigasi-sederhana',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.noregistrasifk,
            norec: e.norec,
        },
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

const deleteData = async (e: any) => {

    await useApi().post('/pmkp/hapus-insiden-keselamatan', { 'norec': e.norec }).then((response) => {
        fetchData()
    }).catch((err: any) => {

    })
}

const showModel = (e:any) => {
    clear()
    modalInput.value = true
    if(e.norec){
        item.value.norec = e.norec
        item.value.keselamatanfk = e.keselamatanfk
        getInit(item.value.keselamatanfk)
        item.value.departemenfk = {label : e.departemen , value: e.departemenfk }
        item.value.waktuKejadian = e.tanggal
        item.value.jumlahInsiden = e.jumlah
    }
}

const clear = () => {
    modalInput.value = false
    delete item.value.norec
    delete item.value.keselamatanfk
    delete item.value.jeniskeselamatan
    delete item.value.departemenfk
    delete item.value.waktuKejadian
    delete item.value.jumlahInsiden
}
fetchDropdown()
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
