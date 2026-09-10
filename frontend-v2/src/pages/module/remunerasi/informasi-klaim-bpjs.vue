<template>
    <VCard>
        <div class="column c-title pt-2 mb-0">
            <label class="title-page">Monitoring</label>
        </div>
        <div class="columns is-multiline mt-2">
            <div class="column is-12">
                <TabView>
                    <TabPanel header="Data Klaim">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <form class="form-layout">
                                    <div class="form-outer">
                                        <div class="form-header stuck-header">
                                            <div class="form-body">
                                                <div class="form-section p-0">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-2">
                                                            <VField v-slot="{ id }" class="is-autocomplete-select"
                                                                label="Status Klaim">
                                                                <VControl icon="feather:search">
                                                                    <Multiselect v-model="item.statusklaim" :attrs="{ id }"
                                                                        :options="optionsStatusKlaim"
                                                                        placeholder="Pilih Status Klaim"
                                                                        :searchable="true" />
                                                                </VControl>
                                                            </VField>
                                                        </div>

                                                        <div class="column is-2">
                                                            <VField class=" is-rounded-select is-autocomplete-select">
                                                                <VLabel>Periode</VLabel>
                                                                <VControl>
                                                                    <Calendar v-model="item.tglklaim" view="month"
                                                                        dateFormat="mm/yy" style="width: 100%" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-3">
                                                            <VField>
                                                                <VLabel>No Fpk</VLabel>
                                                                <VControl>
                                                                    <VInput v-model="item.nofpk" type="text"
                                                                        placeholder="No Fpk" />
                                                                </VControl>
                                                            </VField>
                                                        </div>

                                                        <div class="column is-3" style="padding-top: 2rem;">
                                                            <VField>
                                                                <VControl>
                                                                    <VRadio v-model="item.jenispelayanan" value="1"
                                                                        label="Rawat Inap" color="primary" />
                                                                    <VRadio v-model="item.jenispelayanan" value="2"
                                                                        label="Rawat Jalan" color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-2" style="padding-top:2.5rem">
                                                            <VIconButton circle icon="feather:search" raised bold
                                                                @click="fecthData" :loading="isLoading"
                                                                v-tooltip.bubble="'Cari'" class="mt-2-min">
                                                            </VIconButton>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="column is-12" v-if="isLoading">
                                <VPlaceloadWrap>
                                    <VPlaceload height="500px" width="100%" class="mx-2" />
                                </VPlaceloadWrap>
                            </div>
                            <div class="column is-12" v-else>
                                <DataTable v-model:filters="filters" :expandedRows="expandedRows" :value="dataSource"
                                    paginator :rows="10" dataKey="no" filterDisplay="row"
                                    :globalFilterFields="['peserta']['nama']" :class="`p-datatable-small`"
                                    @rowExpand="onRowExpand" @rowCollapse="onRowCollapse">
                                    <template #header>
                                        <div class="flex justify-content-between">
                                            <span class="p-input-icon-left">
                                                <!-- <i class="pi pi-search" /> -->
                                                <InputText v-model="filters['global'].value" placeholder="Search" />
                                            </span>
                                            <VButtons>
                                                <VButton color="primary" icon="feather:save" elevated @click="posting"
                                                    :loading="isSave"> Posting </VButton>
                                            </VButtons>
                                        </div>
                                    </template>
                                    <template #empty style="text-align: center;"> No data found. </template>
                                    <Column expander style="width: 5rem" />
                                    <Column field="no" header="No"></Column>
                                    <Column field="peserta.noMR" header="No MR"></Column>
                                    <Column field="peserta.nama" header="Nama Pasien">
                                        <template #body="slotProps">
                                            {{ slotProps.data.peserta.nama.toUpperCase() }}
                                        </template>
                                    </Column>
                                    <Column field="noSEP" header="No SEP"></Column>
                                    <Column field="tglSep" header="Tgl SEP"></Column>
                                    <Column field="biaya.byTarifRS" header="Biaya Tarif RS">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.biaya.byTarifRS, "Rp.") }}
                                        </template>
                                    </Column>
                                    <Column field="biaya.byPengajuan" header="Biaya Pengajuan">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.biaya.byPengajuan, "Rp.") }}
                                        </template>
                                    </Column>
                                    <Column field="biaya.bySetujui" header="Biaya Setujui">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.biaya.bySetujui, "Rp.") }}
                                        </template>
                                    </Column>
                                    <template #expansion="slotProps">
                                        <div class="p-3">
                                            <DataTable :value="slotProps.data.details">
                                                <Column field="peserta.noKartu" header="No Kartu"></Column>
                                                <Column field="poli" header="Ruangan"></Column>
                                                <Column field="kelasRawat" header="Kelas Rawat"></Column>
                                                <Column field="Inacbg.nama" header="Group INACBG"></Column>
                                                <Column field="status" header="Status"></Column>
                                                <Column field="tglPulang" header="Tgl Pulang"></Column>
                                                <Column field="noFPK" header="No FPK"></Column>
                                            </DataTable>
                                        </div>
                                    </template>
                                </DataTable>
                            </div>
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-3">
                                        <VCardCustom :style="'padding:5px 25px'">
                                            <div class="label-status primary">
                                                <i aria-hidden="true" class="fas fa-circle"></i>
                                                <span class="ml-1">Total Biaya Disetujui</span>
                                            </div>
                                            <small class="text-bold-custom h-100">{{ H.formatRupiah(totalDisetujui, "Rp.")
                                            }}</small>
                                        </VCardCustom>
                                    </div>
                                    <div class="column is-3">
                                        <VCardCustom :style="'padding:5px 25px'">
                                            <div class="label-status info">
                                                <i aria-hidden="true" class="fas fa-circle"></i>
                                                <span class="ml-1">Total Biaya RS</span>
                                            </div>
                                            <small class="text-bold-custom h-100">{{ H.formatRupiah(totalTarifRs, "Rp.")
                                            }}</small>

                                        </VCardCustom>
                                    </div>
                                    <div class="column is-3">
                                        <VCardCustom :style="'padding:5px 25px'">
                                            <div class="label-status"
                                                :class="{ 'danger': surplusDefisit < 0, 'success': surplusDefisit > 0 }">
                                                <i aria-hidden="true" class="fas fa-circle"></i>
                                                <span class="ml-1">Surplus / Defisit</span>
                                            </div>
                                            <small class="text-bold-custom h-100">{{ H.formatRupiah(surplusDefisit, "Rp.")
                                            }}</small>

                                        </VCardCustom>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </TabPanel>
                    <TabPanel header="Data Kunjungan">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <form class="form-layout">
                                    <div class="form-outer">
                                        <div class="form-header stuck-header">
                                            <div class="form-body">
                                                <div class="form-section p-0">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-3">
                                                            <VField label="Tanggal" />
                                                            <VDatePicker v-model="item.filterDate" is-range color="pink"
                                                                class="mt-2" trim-weeks :max-date="new Date()">
                                                                <template #default="{ inputValue, inputEvents }">
                                                                    <VField addons>
                                                                        <VControl icon="feather:calendar">
                                                                            <VInput :value="inputValue.start"
                                                                                v-on="inputEvents.start" />
                                                                        </VControl>
                                                                        <VControl>
                                                                            <VButton static><i class="fas fa-arrow-right"
                                                                                    aria-hidden="true"></i></VButton>
                                                                        </VControl>
                                                                        <VControl icon="feather:calendar">
                                                                            <VInput :value="inputValue.end"
                                                                                v-on="inputEvents.end" />
                                                                        </VControl>
                                                                    </VField>
                                                                </template>
                                                            </VDatePicker>
                                                        </div>
                                                        <div class="column is-3" style="padding-top: 2.5rem;">
                                                            <VField>
                                                                <VControl>
                                                                    <VRadio v-model="item.jenispelayanan" value="1"
                                                                        label="Rawat Inap" color="primary" />
                                                                    <VRadio v-model="item.jenispelayanan" value="2"
                                                                        label="Rawat Jalan" color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-1" style="padding-top:2.5rem">
                                                            <VIconButton circle icon="feather:search" raised bold
                                                                @click="fecthData2" :loading="isLoading"
                                                                v-tooltip.bubble="'Cari'" class="mt-2-min">
                                                            </VIconButton>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="column is-12" v-if="isLoading">
                                <VPlaceloadWrap>
                                    <VPlaceload height="500px" width="100%" class="mx-2" />
                                </VPlaceloadWrap>
                            </div>
                            <div class="column is-12" v-else>
                                <DataTable v-model:filters="filters" :value="dataSource2" paginator :rows="10" dataKey="no"
                                    filterDisplay="row" :globalFilterFields="['nama']" :class="`p-datatable-small`">
                                    <template #header>
                                        <div class="flex justify-content-between">
                                            <span>
                                                Total Kunjungan : {{ dataSource2.length }}
                                            </span>
                                            <span class="p-input-icon-left">
                                                <!-- <i class="pi pi-search" /> -->
                                                <InputText v-model="filters['global'].value" placeholder="Search" />
                                            </span>
                                        </div>
                                    </template>
                                    <template #empty style="text-align: center;"> No data found. </template>
                                    <Column field="no" header="No"></Column>
                                    <Column field="tglSep" header="Tgl SEP"></Column>
                                    <Column field="noSep" header="No SEP"></Column>
                                    <Column field="nama" header="Nama Pasien">
                                        <template #body="slotProps">
                                            {{ slotProps.data.nama.toUpperCase() }}
                                        </template>
                                    </Column>
                                    <Column field="noKartu" header="No Kartu"></Column>
                                    <Column field="poli" header="Poli"></Column>
                                    <Column field="noRujukan" header="No Rujukan"></Column>
                                    <Column field="jnsPelayanan" header="Jns Pelayanan"></Column>
                                    <Column field="diagnosa" header="Diagnosa"></Column>
                                </DataTable>
                            </div>
                        </div>
                    </TabPanel>
                    <TabPanel header="Data History Pelayanan Peserta">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <form class="form-layout">
                                    <div class="form-outer">
                                        <div class="form-header stuck-header">
                                            <div class="form-body">
                                                <div class="form-section p-0">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-3">
                                                            <VField label="Tanggal" />
                                                            <VDatePicker v-model="item.filterDate" is-range color="pink"
                                                                trim-weeks :max-date="new Date()" class="mt-2">
                                                                <template #default="{ inputValue, inputEvents }">
                                                                    <VField addons>
                                                                        <VControl icon="feather:calendar">
                                                                            <VInput :value="inputValue.start"
                                                                                v-on="inputEvents.start" />
                                                                        </VControl>
                                                                        <VControl>
                                                                            <VButton static><i class="fas fa-arrow-right"
                                                                                    aria-hidden="true"></i></VButton>
                                                                        </VControl>
                                                                        <VControl icon="feather:calendar">
                                                                            <VInput :value="inputValue.end"
                                                                                v-on="inputEvents.end" />
                                                                        </VControl>
                                                                    </VField>
                                                                </template>
                                                            </VDatePicker>
                                                        </div>
                                                        <div class="column is-3">
                                                            <VField>
                                                                <VLabel>No Kartu Peserta</VLabel>
                                                                <VControl>
                                                                    <VInput v-model="item.nopeserta" type="text"
                                                                        placeholder="No Kartu Peserta" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-1" style="padding-top:2.8rem">
                                                            <VIconButton circle icon="feather:search" raised bold
                                                                @click="fecthData3" :loading="isLoading"
                                                                v-tooltip.bubble="'Cari'" class="mt-2-min">
                                                            </VIconButton>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- <div class="column is-6">
                                <div class="columns is-multiline">
                                    <div class="column is-2 mt-2">
                                        <VIconButton circle icon="feather:search" raised bold @click="fecthData3"
                                            :loading="isLoading" v-tooltip.bubble="'Cari'" class="mt-2-min">
                                        </VIconButton>
                                    </div>
                                </div>
                            </div> -->
                            <div class="column is-12" v-if="isLoading">
                                <VPlaceloadWrap>
                                    <VPlaceload height="500px" width="100%" class="mx-2" />
                                </VPlaceloadWrap>
                            </div>
                            <div class="column is-12" v-else>
                                <DataTable v-model:filters="filters" :value="dataSource3" paginator :rows="10" dataKey="no"
                                    filterDisplay="row" :globalFilterFields="['namaPeserta']" :class="`p-datatable-small`">
                                    <template #header>
                                        <div class="flex justify-content-end">
                                            <span class="p-input-icon-left">
                                                <!-- <i class="pi pi-search" /> -->
                                                <InputText v-model="filters['global'].value" placeholder="Search" />
                                            </span>
                                        </div>
                                    </template>
                                    <template #empty style="text-align: center;"> No data found. </template>
                                    <Column field="no" header="No"></Column>
                                    <Column field="tglSep" header="Tgl SEP"></Column>
                                    <Column field="noSep" header="No SEP"></Column>
                                    <Column field="namaPeserta" header="Nama Pasien">
                                        <template #body="slotProps">
                                            {{ slotProps.data.namaPeserta.toUpperCase() }}
                                        </template>
                                    </Column>
                                    <Column field="noKartu" header="No Kartu"></Column>
                                    <Column field="poli" header="Poli"></Column>
                                    <Column field="noRujukan" header="No Rujukan"></Column>
                                    <Column field="jnsPelayanan" header="Jns Pelayanan">
                                        <template #body="slotProps">
                                            {{ slotProps.data.jnsPelayanan == "1" ? "R.Inap" : "R.Jalan" }}
                                        </template>
                                    </Column>
                                    <Column field="diagnosa" header="Diagnosa"></Column>
                                    <Column field="ppkPelayanan" header="PPK Pelayanan"></Column>
                                </DataTable>
                            </div>
                        </div>
                    </TabPanel>
                    <TabPanel header="Data Klaim Jasa Raharja">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <form class="form-layout">
                                    <div class="form-outer">
                                        <div class="form-header stuck-header">
                                            <div class="form-body">
                                                <div class="form-section p-0">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-3">
                                                            <VField label="Tanggal"/>
                                                            <VDatePicker v-model="item.filterDate" is-range color="pink"
                                                                trim-weeks :max-date="new Date()"  class="mt-2">
                                                                <template #default="{ inputValue, inputEvents }">
                                                                    <VField addons>
                                                                        <VControl icon="feather:calendar">
                                                                            <VInput :value="inputValue.start"
                                                                                v-on="inputEvents.start" />
                                                                        </VControl>
                                                                        <VControl>
                                                                            <VButton static><i class="fas fa-arrow-right"
                                                                                    aria-hidden="true"></i></VButton>
                                                                        </VControl>
                                                                        <VControl icon="feather:calendar">
                                                                            <VInput :value="inputValue.end"
                                                                                v-on="inputEvents.end" />
                                                                        </VControl>
                                                                    </VField>
                                                                </template>
                                                            </VDatePicker>
                                                        </div>
                                                        <div class="column is-3" style="padding-top:2.4rem">
                                                            <VField>
                                                                <VControl>
                                                                    <VRadio v-model="item.jenispelayanan" value="1"
                                                                        label="Rawat Inap" color="primary" />
                                                                    <VRadio v-model="item.jenispelayanan" value="2"
                                                                        label="Rawat Jalan" color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-2" style="padding-top:2.5rem">
                                                            <VIconButton circle icon="feather:search" raised bold
                                                                @click="fecthData4" :loading="isLoading"
                                                                v-tooltip.bubble="'Cari'" class="mt-2-min">
                                                            </VIconButton>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="column is-12" v-if="isLoading">
                                <VPlaceloadWrap>
                                    <VPlaceload height="500px" width="100%" class="mx-2" />
                                </VPlaceloadWrap>
                            </div>
                            <div class="column is-12" v-else>
                                <DataTable v-model:filters="filters" :value="dataSource4" paginator :rows="10" dataKey="no"
                                    filterDisplay="row" :globalFilterFields="['sep']['peserta']"
                                    :class="`p-datatable-small`">
                                    <template #header>
                                        <div class="flex justify-content-between">
                                            <span class="p-input-icon-left">
                                                <!-- <i class="pi pi-search" /> -->
                                                <InputText v-model="filters['global'].value" placeholder="Search" />
                                            </span>
                                        </div>
                                    </template>
                                    <template #empty style="text-align: center;"> No data found. </template>
                                    <Column field="no" header="No"></Column>
                                    <Column field="sep.tglSep" header="Tgl SEP"></Column>
                                    <Column field="sep.noSep" header="No SEP"></Column>
                                    <Column field="sep.peserta.nama" header="Nama Pasien">
                                        <template #body="slotProps">
                                            {{ slotProps.data.sep.peserta.nama.toUpperCase() }}
                                        </template>
                                    </Column>
                                    <Column field="jasaRaharja.tglKejadian" header="Tgl Kejadian"></Column>
                                    <Column field="jasaRaharja.noRegister" header="No Register"></Column>
                                    <Column field="jasaRaharja.biayaDijamin" header="Biaya Dijamin">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.jasaRaharja.biayaDijamin, "Rp.") }}
                                        </template>
                                    </Column>
                                    <Column field="jasaRaharja.plafon" header="Plafon">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.jasaRaharja.plafon, "Rp.") }}
                                        </template>
                                    </Column>
                                    <Column field="jasaRaharja.jmlDibayar" header="Jumlah Dibayar">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.jasaRaharja.jmlDibayar, "Rp.") }}
                                        </template>
                                    </Column>
                                    <Column field="sep.diagnosa" header="Diagnosa"></Column>
                                </DataTable>
                            </div>
                        </div>
                    </TabPanel>
                </TabView>
            </div>
        </div>
    </VCard>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import { FilterMatchMode } from 'primevue/api'
import Calendar from 'primevue/calendar';
useHead({
    title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoading: any = ref(false)
const isSave: any = ref(false)
const item: any = reactive({
    tglklaim: new Date(),
    filterDate: {
        start: new Date(),
        end: new Date()
    },
})
const dataSource = ref([])
const dataSource2 = ref([])
const dataSource3 = ref([])
const dataSource4 = ref([])
const expandedRows = ref([]);
let totalDisetujui = ref(0)
let totalTarifRs = ref(0)
let surplusDefisit = ref(0)
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const optionsStatusKlaim: any = [
    { value: 1, label: 'Proses Verifikasi' },
    { value: 2, label: 'Pending Verifikasi' },
    { value: 3, label: 'Klaim' },
]

const fecthData = async () => {

    if (!item.jenispelayanan) { useToaster().error('Harap pilih jenis pelayanan terlebih dahulu !'); return }
    if (!item.statusklaim) { useToaster().error('Harap pilih status klaim terlebih dahulu !'); return }
    if (!item.tglklaim) { useToaster().error('Harap isi tanggal terlebih dahulu !'); return }

    const tahun = H.formatDate(item.tglklaim, "YYYY")
    const bulan = H.formatDate(item.tglklaim, "MM")
    const tanggal = getDatesInMonth(tahun, bulan);

    dataSource.value = []
    totalDisetujui.value = 0
    totalTarifRs.value = 0
    surplusDefisit.value = 0
    isLoading.value = true
    for (let i = 0; i < tanggal.length - 25; i++) {
        const element = H.formatDate(tanggal[i], 'YYYY-MM-DD');
        var json = {
            url: `Monitoring/Klaim/Tanggal/${element}/JnsPelayanan/${item.jenispelayanan}/Status/${item.statusklaim}`,
            method: "GET",
            data: null
        }
        await useApi()
            .postNoMessage(`/bridging/bpjs/tools`, json)
            .then((response: any) => {
                if (response.metaData.code == 200 && response.response) {
                    const data = response.response.klaim
                    for (let i = 0; i < data.length; i++) {
                        const element = data[i];
                        element.details = []
                        element.details.push(element)
                        if (!item.nofpk) {
                            dataSource.value.push(element)
                        } else {
                            const noFpk = item.nofpk;
                            if (element.noFPK.match(noFpk))
                                dataSource.value.push(element)
                        }
                    }
                }
            }).catch((err) => {
                console.log(err)
            })
    }

    var subTotalbySetujui = 0;
    var subTotalbyTarifRS = 0;
    for (let i = 0; i < dataSource.value.length; i++) {
        dataSource.value[i].no = i + 1
        subTotalbySetujui = subTotalbySetujui + parseFloat(dataSource.value[i].biaya.bySetujui)
        subTotalbyTarifRS = subTotalbyTarifRS + parseFloat(dataSource.value[i].biaya.byTarifRS)
    }

    totalDisetujui.value = subTotalbySetujui;
    totalTarifRs.value = subTotalbyTarifRS;
    surplusDefisit.value = totalDisetujui.value - totalTarifRs.value;
    isLoading.value = false
}
const fecthData2 = async () => {

    if (!item.filterDate) { useToaster().error('Harap isi tanggal terlebih dahulu !'); return }
    if (!item.jenispelayanan) { useToaster().error('Harap pilih jenis pelayanan terlebih dahulu !'); return }

    const date1 = item.filterDate.start;
    const date2 = item.filterDate.end;
    const Difference_In_Time = date2.getTime() - date1.getTime();
    let diffDays = date2.getDate() - date1.getDate();

    const Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
    diffDays = Difference_In_Days + 1

    dataSource2.value = []
    for (let i = 0; i < diffDays; i++) {
        const someDate = item.filterDate.start
        const numberOfDaysToAdd = i;
        let tglSepHari = H.formatDate(someDate.setDate(someDate.getDate() + numberOfDaysToAdd), 'YYYY-MM-DD');

        var json = {
            url: `Monitoring/Kunjungan/Tanggal/${tglSepHari}/JnsPelayanan/${item.jenispelayanan}`,
            method: "GET",
            data: null
        }

        isLoading.value = true
        await useApi()
            .postNoMessage(`/bridging/bpjs/tools`, json)
            .then((response: any) => {
                if (response.metaData.code == 200) {
                    const data = response.response.sep
                    for (let i = 0; i < data.length; i++) {
                        const element = data[i]
                        dataSource2.value.push(element)
                    }
                }
            }).catch((err) => {
                isLoading.value = false
                dataSource2.value = []
            })
    }

    isLoading.value = false
    for (let i = 0; i < dataSource2.value.length; i++) {
        dataSource2.value[i].no = i + 1
    }
}
const fecthData3 = async () => {
    if (!item.filterDate) { useToaster().error('Harap isi tanggal terlebih dahulu !'); return }
    if (!item.nopeserta) { useToaster().error('Harap isi No Kartu Peserta terlebih dahulu !'); return }

    const tglawal = H.formatDate(item.filterDate.start, "YYYY-MM-DD")
    const tglakhir = H.formatDate(item.filterDate.end, "YYYY-MM-DD")
    var json = {
        url: `monitoring/HistoriPelayanan/NoKartu/${item.nopeserta}/tglMulai/${tglawal}/tglAkhir/${tglakhir}`,
        method: "GET",
        data: null
    }

    dataSource3.value = []
    isLoading.value = true
    await useApi()
        .postNoMessage(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
            isLoading.value = false
            if (response.metaData.code == 200) {
                const data = response.response.histori
                for (let i = 0; i < data.length; i++) {
                    let element = data[i]
                    element.no = i + 1
                    dataSource3.value.push(element)
                }
            } else {
                useToaster().info(response.metaData.message)
            }
        }).catch((err) => {
            isLoading.value = false
            dataSource3.value = []
        })
}
const fecthData4 = async () => {
    if (!item.filterDate) { useToaster().error('Harap isi tanggal terlebih dahulu !'); return }
    if (!item.jenispelayanan) { useToaster().error('Harap pilih jenis pelayanan terlebih dahulu !'); return }

    const tglawal = H.formatDate(item.filterDate.start, "YYYY-MM-DD")
    const tglakhir = H.formatDate(item.filterDate.end, "YYYY-MM-DD")
    var json = {
        url: `monitoring/JasaRaharja/JnsPelayanan/${item.jenispelayanan}/tglMulai/${tglawal}/tglAkhir/${tglakhir}`,
        method: "GET",
        data: null
    }

    isLoading.value = true
    await useApi()
        .postNoMessage(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
            if (response.metaData.code == 200) {
                const data = response.response.jaminan
                dataSource4.value = data
            } else {
                isLoading.value = false
                useToaster().info(response.metaData.message)
            }
        }).catch((err) => {
            isLoading.value = false
            dataSource4.value = []
        })
}
const onRowExpand = (event) => {
    expandedRows.value = dataSource.value.filter((p) => p.no == event.data.no);
}
const onRowCollapse = (event) => {
    expandedRows.value = null
}
const getDatesInMonth = (year: number, month: number) => {
    const dates: Date[] = [];
    const startDate = new Date(year, month - 1, 1); // Month dalam JavaScript dimulai dari 0

    while (startDate.getMonth() === month - 1) {
        dates.push(H.formatDate(new Date(startDate), "YYYY-MM-DD"));
        startDate.setDate(startDate.getDate() + 1);
    }

    return dates;
}
const posting = async () => {
    isSave.value = true
    let datas: any = []
    for (let i = 0; i < dataSource.value.length; i++) {
        const element = dataSource.value[i];
        datas.push({
            'norec': '',
            'nofpk': element.noFPK,
            'tglpulang': element.tglPulang,
            'jenispelayanan': item.jenispelayanan,
            'nosep': element.noSEP,
            'tglsep': element.tglSep,
            'status': element.status,
            'totalpengajuan': element.biaya.byPengajuan,
            'totalsetujui': element.biaya.bySetujui,
            'totaltarifrs': element.biaya.byTarifRS,
            'totalgrouper': element.biaya.byTarifGruper,
        })
    }
    var json = {
        'jenispelayanan': item.jenispelayanan,
        'statusklaimfk': item.statusklaim,
        'bulan': H.formatDate(item.tglklaim, "YYYY") + '-' + H.formatDate(item.tglklaim, "MM"),
        'details': datas
    }
    await useApi()
        .post(`medifirst2000/bridging/bpjs/save-monitoring-klaim`, json)
        .then((response: any) => {
            isSave.value = false
        }).catch((err) => {
            isSave.value = false
            console.log(err)
        })
}

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

.control.has-icon {
    position: relative;
    width: 100%;
}

.field:not(:last-child) {
    margin-bottom: 0px !important;
}

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 0px;
    font-weight: 600;
}
</style>
