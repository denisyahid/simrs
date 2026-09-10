<template>
    <ConfirmDialog />
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 pb-1">
                    <label class="title-page">Daftar Permohonan Perbaikan</label>
                </div>
                
                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource" :rowsPerPageOptions="[5, 10, 15]" :loading="loadSearch"
                    class="p-datatable-sm" breakpoint="960px" selectionMode="single" sortMode="multiple"
                    v-model:expanded-rows="expandedRows" showGridlines tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline p-2">
                            <div class="column is-3">
                                <VField label="Periode Tanggal"></VField>
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
                            <div class="column is-9 p-2">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-3">
                                        <VField label="Ruangan">
                                            <VControl class="prime-auto">
                                                <AutoComplete v-model="item.qruangan" :suggestions="d_Ruangan"
                                                    class="mt-2" :optionLabel="'label'" @complete="fetchRuangan($event)"
                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                    :loadingIcon="'pi pi-spinner'" dropPegawai :field="'label'"
                                                    placeholder="Pilih Ruangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2 pt-4">
                                        <VField class="is-rounded-select is-autocomplete-select mt-1">
                                            <VLabel>Jenis Alat</VLabel>
                                            <VControl icon="feather:search" class="prime-auto">
                                                <Dropdown v-model="item.qjenisalatfk" :options="d_JenisAlat"
                                                    :optionLabel="'label'" placeholder="Jenis Alat"
                                                    style="width: 100%;" :filter="true" appendTo="body" showClear />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-1 btn-search pt-5 mt-5">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData()"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </template>
                    <Column expander style="width: 5rem" />
                    <Column field="no" header="NO" />
                    <Column field="namaruangan" header="RUANGAN" />
                    <Column field="namapelapor" header="PELAPOR" />
                    <Column field="rincianexecuteplanning_askep" header="Laporan kerusakan" />
                    <Column field="tglplanning" header="Tgl diterima">
                        <template #body="slotProps">
                            {{ H.formatDateIndoSimpleNoDay(slotProps.data.tglplanning) }}
                        </template>
                    </Column>
                    <Column field="namalengkap" header="Penanggung Jawab" />
                    <Column field="startdate" header="Tgl Mulai">
                        <template #body="slotProps">
                            {{ slotProps.data.startdate ?
                            H.formatDateIndoSimpleNoDay(slotProps.data.startdate) : '' }}
                        </template>
                    </Column>
                    <!-- <Column field="respon" header="Respon" /> -->
                    <Column field="duedate" header="Tgl Selesai">
                        <template #body="slotProps">
                            {{ slotProps.data.startdate ?
                            H.formatDateIndoSimpleNoDay(slotProps.data.duedate) : '' }}
                        </template>
                    </Column>
                    <!-- <Column field="durasi" header="Pengerjaan" /> -->
                    <Column field="deskripsiplanning" header="WorkList" />
                    <Column field="keteranganverifikasi" header="Identifikasi" />
                    <Column field="jeniskerusakan" header="Jenis kerusakan" />
                    <Column field="jenisalat" header="Jenis alat" />
                    <Column field="statuspekerjaan" header="Status" />
                    <Column :exportable="false" header="##" style="text-align: center;">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle
                                outlined raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
                            </VIconButton>
                            <OverlayPanel ref="op">
                                <VButton type="button" icon="fas fa-trash" class="mr-2" circle color="danger" raised
                                    @click="dialogConfirm(selected)">
                                    Hapus
                                </VButton>
                                <VButton type="button" icon="fas fa-hammer" class="mr-2" color="info" circle outlined
                                    raised @click="showFormPengerjaan(selected)">Pengerjaan
                                </VButton>
                            </OverlayPanel>
                        </template>
                    </Column>
                    <template #expansion="slotProps">
                        <div class="p-3">
                            <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple">
                                <Column field="no" header="No" />
                                <Column field="namalengkap" header="Nama Staff" style="min-width: 80rem;" />
                            </DataTable>
                        </div>
                    </template>
                    <template #footer>
                        <div class="column pt-0 pb-0" style="text-align:right">
                            <VButtons style="justify-content: flex-end">
                                <VButton class="mr-4" color="primary" outlined icon="fas fa-wrench"
                                    style="padding-right: 3rem;padding-left: 3rem;" @click="showFormPerbaikan()"
                                    :loading="loadSave">Buat Permohonan
                                    Perbaikan</VButton>
                            </VButtons>
                        </div>
                    </template>
                </DataTable>

            </VCard>
        </div>
    </section>

    <VModal :open="modalPerbaikan" title="Form Input Permohonan Perbaikan" size="large" actions="right"
        @close="modalPerbaikan = false, clear()">
        <template #content>
            <form class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VDatePicker v-model="item.tglplanning" color="green" trim-weeks mode="dateTime">
                            <template #default="{ inputValue, inputEvents }" :max-date="new Date()">
                                <VField>
                                    <VLabel>Tanggal</VLabel>
                                    <VControl icon="feather:calendar">
                                        <VInput type="text" placeholder="Pilih Tanggal" class="is-rounded"
                                            :value="inputValue" v-on="inputEvents" style="font-weight: bold;" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-4">
                        <VField label="Ruangan / area">
                            <VControl>
                                <AutoComplete v-model="item.ruanganasal" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Ruangan Tujuan" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-5">
                        <VField label="Pelapor">
                            <VControl>
                                <AutoComplete v-model="item.penanggungjawab" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Pegawai Pelapor" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Ruangan Tujuan">
                            <VControl>
                                <AutoComplete v-model="item.ruanganTujuan" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Ruangan Tujuan" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField>
                            <VLabel>Status</VLabel>
                            <VControl>
                                <input v-model="item.status" type="text" class="input is-rounded" placeholder="Status"
                                    disabled style="font-weight: bold;" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel class="max-width">Deskripsi kerusakan / maintenance"</VLabel>
                            <VControl fullwidth>
                                <VTextarea class="textarea" v-model="item.desckerusakan" rows="5"
                                    placeholder="catatan Kerusakan ..." autocomplete="off" autocapitalize="off"
                                    spellcheck="true" />

                            </VControl>
                        </VField>
                    </div>
                </div>
            </form>
        </template>
        <template #action>
            <VButton icon="fas fa-save" @click="simpan(item)" color="primary" raised :loading="loadSave">Simpan
            </VButton>
        </template>
    </VModal>

    <VModal :open="modalPengerjaan" title="Form Input Pengerjaan Permohonan" size="large" actions="right"
        :noclose="true" @close="modalPengerjaan = false, clear()">
        <template #content>
            <form class="modal-form pt-0">
                <div class="form-fieldset pt-0">
                    <Accordion :activeIndex="0">
                        <AccordionTab header="Detail Permohonan">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <VField>
                                        <VLabel>Tanggal Lapor</VLabel>
                                        <VControl>
                                            <input v-model="item.ketTanggalLapor" type="text" class="input is-rounded"
                                                placeholder="Tanggal Lapor" disabled style="font-weight: bold;" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-5">
                                    <VField>
                                        <VLabel>Ruangan</VLabel>
                                        <VControl>
                                            <input v-model="item.ketRuangan" type="text" class="input is-rounded"
                                                placeholder="Ruangan" disabled style="font-weight: bold;" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VLabel>Pelapor</VLabel>
                                        <VControl>
                                            <input v-model="item.ketPelapor" type="text" class="input is-rounded"
                                                placeholder="Pelapor" style="font-weight: bold;" disabled />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div class="column is-12">
                                <VField>
                                    <VLabel class="max-width">Kerusakan</VLabel>
                                    <VControl fullwidth>
                                        <VTextarea class="textarea" v-model="item.ketKerusakan" rows="5"
                                            placeholder="Kerusakan ..." autocomplete="off" autocapitalize="off"
                                            spellcheck="true" readonly />
                                    </VControl>
                                </VField>
                            </div>
                        </AccordionTab>
                        <AccordionTab header="Detail Pengerjaan">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <VDatePicker v-model="item.tglmulai" color="green" trim-weeks mode="dateTime">
                                        <template #default="{ inputValue, inputEvents }" :max-date="new Date()">
                                            <VField>
                                                <VLabel>Tanggal Mulai</VLabel>
                                                <VControl icon="feather:calendar">
                                                    <VInput type="text" placeholder="Pilih Tanggal" class="is-rounded"
                                                        :value="inputValue" v-on="inputEvents"
                                                        style="font-weight: bold;" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </div>
                                <div class="column is-4">
                                    <VDatePicker v-model="item.tglselesai" color="green" trim-weeks mode="dateTime">
                                        <template #default="{ inputValue, inputEvents }" :max-date="new Date()">
                                            <VField>
                                                <VLabel>Tanggal Selesai</VLabel>
                                                <VControl icon="feather:calendar">
                                                    <VInput type="text" placeholder="Pilih Tanggal" class="is-rounded"
                                                        :value="inputValue" v-on="inputEvents"
                                                        style="font-weight: bold;" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </div>
                                <div class="column is-3">
                                    <VField class=" is-rounded-select is-autocomplete-select">
                                        <VLabel>Jenis Alat</VLabel>
                                        <VControl icon="feather:search" class="prime-auto">
                                            <Dropdown v-model="item.jenisalatfk" :options="d_JenisAlat"
                                                class="is-rounded" :optionLabel="'label'" placeholder="Jenis Alat"
                                                style="width: 100%;" :filter="true" appendTo="body" showClear />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <VField>
                                        <VLabel class="max-width">Identifikasi</VLabel>
                                        <VControl fullwidth>
                                            <VTextarea class="textarea" v-model="item.descIdentifikasi" rows="3"
                                                placeholder="Identifikasi ..." autocomplete="off" autocapitalize="off"
                                                spellcheck="true" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField class=" is-rounded-select is-autocomplete-select">
                                        <VLabel>Jenis Kerusakan</VLabel>
                                        <VControl icon="feather:search" class="prime-auto is-rounded">
                                            <Dropdown v-model="item.jeniskerusakanfk" :options="d_JenisKerusakan"
                                                :optionLabel="'label'" placeholder="Jenis Kerusakan" class="is-rounded"
                                                style="width: 100%;" :filter="true" appendTo="body" showClear />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-5">
                                    <VField label="Penanggung Jawab">
                                        <VControl>
                                            <AutoComplete v-model="item.staff" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="Cari Penanggung Jawab"
                                                class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <VField>
                                        <VLabel class="max-width">Work list</VLabel>
                                        <VControl fullwidth>
                                            <VTextarea class="textarea" v-model="item.worklist" rows="3"
                                                placeholder="Work list ..." autocomplete="off" autocapitalize="off"
                                                spellcheck="true" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-5">
                                    <VField class="is-autocomplete-select">
                                        <VLabel>Alokasi Staf</VLabel>
                                        <VControl class="prime-auto" icon="feather:search">
                                            <MultiSelect v-model="item.arrPegawai" :options="d_PegawaiArray" filter
                                                @filter="fetchArrayPegawai($event)" optionLabel="label"
                                                placeholder="Alokasi Staf" :maxSelectedLabels="3" display="chip"
                                                style="width: 100%;" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField class=" is-rounded-select is-autocomplete-select">
                                        <VLabel>Status Pengerjaan</VLabel>
                                        <VControl icon="feather:search" class="prime-auto">
                                            <Dropdown v-model="item.statusfk" :options="d_StatusPekerjaan"
                                                :optionLabel="'label'" placeholder="Status Pengerjaan"
                                                class="is-rounded" style="width: 100%;" :filter="true" appendTo="body"
                                                showClear />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </AccordionTab>
                    </Accordion>
                </div>
            </form>
        </template>
        <template #action>
            <VButton icon="fas fa-save" @click="simpanPengerjaan(item)" color="primary" raised :loading="loadSave">
                Simpan
            </VButton>
        </template>
    </VModal>

</template>

<script setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useHead } from '@vueuse/head'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable'
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import AutoComplete from 'primevue/autocomplete';
import OverlayPanel from 'primevue/overlaypanel';
import * as H from '/@src/utils/appHelper'
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Permohonan Peraikan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const confirm = useConfirm()

const item: any = ref({
    aktif: true,
    isKK: false,
    tglplanning: new Date(),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const op = ref();
const selected: any = ref({})
const activeTab = ref(0);
const dataSource: any = ref([])
const dataSourceNilaiPagu: any = ref([])
const expandedRows = ref();
let d_KelompokPasien: any = ref([])
let d_Pegawai: any = ref([])
let d_Ruangan: any = ref([])
let d_JenisAlat: any = ref([])
let d_JenisKerusakan: any = ref([])
let d_StatusPekerjaan: any = ref([])
let d_PegawaiArray: any = ref([])
let loadSearch: any = ref(false)
let loadSave: any = ref(false)
let loadData: any = ref(true)
let modalPerbaikan: any = ref(false)
let modalPengerjaan: any = ref(false)
let IsBayar: any = ref(false)


const fetchData = async () => {

    let tglAwal = `tglAwal=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
    let tglAkhir = `&tglAkhir=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
    let ruangan = item.value.qruangan ? `&ruanganfk=${item.value.qruangan.value}` : ''
    let jenisalat = item.value.qjenisalatfk ? `&jenisalatfk=${item.value.qjenisalatfk.value}` : ''
    loadSearch.value = true

    let response = await useApi().get(`iprs/get-daftar-ipsrs?${tglAwal}${tglAkhir}${ruangan}${jenisalat}`)
    response.data.forEach((element: any, i: any) => {
        element.no = i + 1

        expandedRows.value = element.details.forEach((data: any, i: any) => {
            data.no = i + 1
        })
    });
    loadSearch.value = false
    dataSource.value = response.data
    loadData.value = false
}


const showFormPerbaikan = () => {
    modalPerbaikan.value = true
}

const showFormPengerjaan = (e: any) => {

    // let dataa = []
    if (e.statuspekerjaan == 'Selesai') {
        H.alert('error', 'Status Pekerjaan Telah Selesai')
        return
    }
    item.value.norec = e.norec
    item.value.ketPelapor = e.namapelapor
    item.value.ketRuangan = e.namaruangan
    item.value.ketTanggalLapor = e.tglplanning
    item.value.ketKerusakan = e.rincianexecuteplanning_askep
    item.value.tglmulai = e.startdate
    item.value.tglselesai = e.duedate
    d_JenisAlat.value.forEach(element => {
        if (element.label == e.jenisalat) {
            item.value.jenisalatfk = element
        }
    });
    d_JenisKerusakan.value.forEach(element => {
        if (element.label == e.jeniskerusakan) {
            item.value.jeniskerusakanfk = element
        }
    });
    d_StatusPekerjaan.value.forEach(element => {
        if (element.label == e.statuspekerjaan) {
            item.value.statusfk = element
        }
    });

    item.value.descIdentifikasi = e.keteranganverifikasi
    item.value.staff = e.namainspektor ? { label: e.namainspektor, value: e.namainspektorfk } : ''
    item.value.worklist = e.deskripsiplanning
    // item.value.arrPegawai = e.details.map((elem:any)=>{
    //     let nama = {value : elem.namalengkap}
    //     fetchArrayPegawai(nama)
    //     return { label: elem.namalengkap , value : e.id}
    // })
    // console.log(item.value.arrPegawai)
    // item.value.arrPegawai = e.
    modalPengerjaan.value = true
}

const fetchArrayPegawai = async (filter: any) => {
    if (filter.value.length >= 3) {
        await useApi().get(
            `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.value}&limit=10`
        ).then((response) => {
            d_PegawaiArray.value = response
        })
    }

}
const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })

}


const klikTab = (e: any) => {
    activeTab.value = e.index
}

const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
}

const fetchCombo = async () => {

    let response = await useApi().get('/iprs/combo')
    d_JenisAlat.value = response.jenisalat.map((e) => {
        return { label: e.reportdisplay, value: e.id }
    })
    d_JenisKerusakan.value = response.jeniskerusakan.map((e) => {
        return { label: e.reportdisplay, value: e.id }
    })
    d_StatusPekerjaan.value = response.statuspekerjaan.map((e) => {
        return { label: e.statuspekerjaan, value: e.id }
    })

}

const simpan = async () => {

    if (!item.value.ruanganasal) {
        H.alert('error', 'Ruangan / Area Tidak Boleh Kosong')
        return
    }
    if (!item.value.penanggungjawab) {
        H.alert('error', 'Pelapor Tidak Boleh Kosong')
        return
    }
    if (!item.value.ruanganTujuan) {
        H.alert('error', 'Ruangan Tujuan Tidak Boleh Kosong')
        return
    }
    if (!item.value.desckerusakan) {
        H.alert('error', 'Deskripsi Kerusakan Tidak Boleh Kosong')
        return
    }

    let objSave =
    {
        norec: item.value.norec ? item.value.norec : '',
        tglplanning: H.formatDate(item.value.tglplanning, 'YYYY-MM-DD HH:mm:ss'),
        ruangandesc: item.value.ruanganasal.value,
        rincian: item.value.desckerusakan,
        idpelapor: item.value.penanggungjawab.value,
        pelapor: item.value.penanggungjawab.label,
        ruangantujuan: item.value.ruanganTujuan.value
    }
    loadSave.value = true
    await useApi().post('/iprs/save-permohonan-perbaikan', objSave).then((response) => {
        clear()
        fetchData()
        loadSave.value = false
    }).catch((e: any) => {
        loadSave.value = false
    })
}

const simpanPengerjaan = async (e: any) => {
    if (H.formatDate(e.tglmulai, 'YYYY-MM-DD HH:mm:ss') == H.formatDate(e.tglselesai, 'YYYY-MM-DD HH:mm:ss')) {
        H.alert('error', 'Tanggal Mulai dan Tanggal Selasai Tidak Boleh Sama!')
        return
    }
    if (!item.value.staff) {
        H.alert('error', 'Petugas Penanggung Jawab Belum Diisi')
        return
    }
    if (item.value.arrPegawai.length == 0) {
        H.alert('error', 'Alokasi Staff Masih Kosong!')
        return
    }

    let datapegawai = [];
    if (item.value.arrPegawai.length != 0) {
        item.value.arrPegawai.forEach((element: any) => {
            let data = {
                'idpegawai': element.value,
                'namalengkap': element.label,
            }
            datapegawai.push(data);
        });
    }

    let strukplanning = {
        'tglmulai': e.tglmulai ? H.formatDate(e.tglmulai, 'YYYY-MM-DD HH:mm:ss') : null,
        'tglselesai': e.tglselesai ? H.formatDate(e.tglselesai, 'YYYY-MM-DD HH:mm:ss') : null,
        'jenisalat': e.jenisalatfk ? e.jenisalatfk.value : null,
        'identifikasikerusakan': e.descIdentifikasi != undefined ? e.descIdentifikasi : null,
        'penanngungjawab': e.staff != undefined ? e.staff.value : null,
        'jeniskerusakan': e.jeniskerusakanfk != undefined ? e.jeniskerusakanfk.value : null,
        'worklist': e.worklist != undefined ? e.worklist : null,
        'status': e.statusfk != undefined ? e.statusfk.value : null,
    }

    let objSave = {
        norec: item.value.norec,
        strukplanning: strukplanning,
        datapegawai: datapegawai,
    }
    loadSave.value = true
    await useApi().post('/iprs/save-pengerjaan-permohonan-perbaikan', objSave).then((response) => {
        modalPengerjaan.value = false
        clear()
        fetchData()
        loadSave.value = false
    })

}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            loadSearch.value = true
            useApi().post('/iprs/hapus-permohonan-ipsrs', { norec: e.norec }).then((response) => {
                fetchData()
            })
        },
        reject: () => { },
    })
}

const hapus = async (e: any) => {

}

const clear = () => {
    delete item.value.norec
    delete item.value.tglplanning
    delete item.value.ruanganasal
    delete item.value.desckerusakan
    delete item.value.status
    delete item.value.ruanganTujuan
    delete item.value.penanggungjawab
    modalPerbaikan.value = false
}

const toggle = (event: any, e: any) => {
    op.value.toggle(event);
    selected.value = e
}

fetchCombo()
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

.label {
    .max-width {
        width: max-content !important;
    }
}

.form-fieldset {
    padding: 20px 0;
    max-width: 66rem;
    margin: 0 auto;
}
</style>
