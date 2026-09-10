<template>
    <div>
        <Toast />
        <ConfirmDialog />
        <div class="columns">
            <div class="column is-12 form-layout is-stacked">
                <div class=" form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>{{ TITLE_PAGE }}</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton type="button" rounded outlined color="info" raised icon="feather:list"
                                        @click="riwayatResep()"> Riwayat
                                    </VButton>
                                    <!-- <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined>
                                        Cancel
                                    </VButton> -->
                                    <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                        :loading="isSimpan" @click="save()"> Simpan
                                    </VButton>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VCard>
                                            <h3 class="title is-5 mb-2">Data Obat</h3>
                                            <div class="columns is-multiline">
                                                <div class="column is-3">
                                                    <VDatePicker v-model="item.tglAwal" color="green" trim-weeks
                                                        mode="dateTime">
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VField>
                                                                <VLabel class="item">Tgl Resep</VLabel>
                                                                <VControl icon="feather:calendar">
                                                                    <VInput type="text" placeholder="Select a date"
                                                                        class="is-rounded" :value="inputValue"
                                                                        v-on="inputEvents" :disabled="disTanggal" />
                                                                </VControl>
                                                            </VField>
                                                        </template>
                                                    </VDatePicker>
                                                </div>
                                                <div class="column is-3">
                                                    <VField class="is-rounded-select is-autocomplete-select">
                                                        <VLabel class="item">Penulis Resep</VLabel>
                                                        <VControl icon="feather:search" class="prime-auto-select">
                                                            <Dropdown v-model="item.penulisResep" :options="d_penulisResep"
                                                                :optionLabel="'namalengkap'" class="is-rounded"
                                                                placeholder="Pilih data" style="width: 100%;" showClear
                                                                :filter="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField class="is-rounded-select is-autocomplete-select">
                                                        <VLabel class="item">Ruangan</VLabel>
                                                        <VControl icon="feather:search" class="prime-auto-select">
                                                            <Dropdown v-model="item.ruangan" :options="d_ruangan"
                                                                :optionLabel="'namaruangan'" class="is-rounded"
                                                                placeholder="Pilih data" style="width: 100%;" showClear
                                                                :filter="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField label="Registrasi" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="feather:plus-circle" fullwidth>
                                                        <Dropdown v-model="item.pilihRegistrasi" :options="d_Registrasi"
                                                            :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" @change="getRegistrasi(item.pilihRegistrasi)" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </VCard>
                                    </div>
                                    <div class="column is-12">
                                        <VCard>
                                            <div class="columns is-multiline">
                                                <div class="column is-12">
                                                    <Toolbar class="mb-4">
                                                        <template #start>
                                                            <VButton icon="feather:plus" color="info" raised
                                                                @click="addPopUp()">
                                                                Tambah
                                                            </VButton>
                                                        </template>
                                                    </Toolbar>

                                                    <DataTable :value="dataSource" :paginator="true" :rows="10"
                                                        :rowsPerPageOptions="[5, 10, 25]"
                                                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                                        <Column :exportable="false" header="#" style="width:8rem">
                                                            <template #body="slotProps">
                                                                <Button icon="pi pi-pencil"
                                                                    class="p-button-rounded p-button-warning mr-2"
                                                                    @click="editRow(slotProps.data)" />
                                                                <Button icon="pi pi-trash"
                                                                    class="p-button-rounded p-button-danger"
                                                                    @click="hapusRow(slotProps.data)" />
                                                            </template>
                                                        </Column>

                                                        <Column field="no" header="No"></Column>
                                                        <!-- <Column field="rke" header="R/Ke"></Column> -->
                                                        <!-- <Column field="jeniskemasan" header="Kemasan"></Column> -->
                                                        <!-- <Column field="jmldosis" header="Dosis"></Column> -->
                                                        <Column field="namaproduk" header="Produk" :sortable="true">
                                                        </Column>
                                                        <Column field="aturanpakai" header="Aturan Pakai"></Column>
                                                        <Column field="satuanstandar" header="Satuan"></Column>
                                                        <Column field="jumlah" header="Qty"></Column>
                                                        <Column field="hargasatuan" header="Harga">
                                                            <template #body="slotProps">
                                                                {{ formatRp(slotProps.data.hargasatuan, '') }}
                                                            </template>
                                                        </Column>
                                                        <Column field="hargadiscount" header="Diskon"
                                                            footer="Grand Total :">
                                                            <template #body="slotProps">
                                                                {{ formatRp(slotProps.data.hargadiscount, '') }}
                                                            </template>
                                                        </Column>
                                                        <Column field="total" header="Total" :footer="TOTAL">
                                                            <template #body="slotProps">
                                                                {{ formatRp(slotProps.data.total, '') }}
                                                            </template>
                                                        </Column>

                                                        <template #paginatorstart>
                                                            <Button type="button" icon="pi pi-refresh"
                                                                class="p-button-text" />
                                                        </template>
                                                        <template #paginatorend>
                                                            <Button type="button" icon="pi pi-cloud"
                                                                class="p-button-text" />
                                                        </template>
                                                        <!-- <template #footer>
                                                            Total : {{ item.totalAll }}
                                                        </template> -->
                                                    </DataTable>
                                                </div>
                                            </div>
                                        </VCard>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <VModal :open="modalInput" title="Tambah Alkes" size="big" actions="right" @close="modalInput = false">
                <template #content>
                    <form class="modal-form">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel class="item">Produk</VLabel>
                                    <VControl icon="feather:search" class="prime-auto-select">
                                        <AutoComplete v-model="item.produk" :suggestions="d_produk"
                                            @complete="fetchProduk($event)" :optionLabel="'namaproduk'" :dropdown="true"
                                            :minLength="3" class="is-rounded" :appendTo="'body'"
                                            :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                                            placeholder="ketik untuk mencari..." @item-select="changeProduk(item.produk)" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel class="item">Satuan</VLabel>
                                    <VControl icon="feather:search" class="prime-auto-select">
                                        <Dropdown v-model="item.satuan" :options="d_satuan" :optionLabel="'satuanstandar'"
                                            class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear
                                            :filter="true" @change="changeSatuan(item.satuan)" />
                                    </VControl>
                                </VField>
                            </div>

                            <div class="column is-6" v-if="showTgl">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel class="item">Tgl Kadaluarsa</VLabel>
                                    <VControl icon="feather:search" class="prime-auto-select">
                                        <Dropdown v-model="item.tglKadaluarsa" :options="d_tglKadaluarsa"
                                            :optionLabel="'tglkadaluarsa'" class="is-rounded" placeholder="Pilih data"
                                            style="width: 100%;" showClear :filter="true"
                                            @change="changeExpired(item.tglKadaluarsa)" />
                                    </VControl>

                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField>
                                    <VLabel class="item">Qty</VLabel>
                                    <VControl icon="feather:bookmark">
                                        <VInput type="number" v-model="item.jumlah" placeholder="Qty" class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField>
                                    <VLabel class="item">Harga</VLabel>
                                    <VControl icon="feather:bookmark">
                                        <span class="txt-input">{{ H.formatRupiah(item.hargaSatuan, 'Rp.') }}</span>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField>
                                    <VLabel class="item">Harga Diskon</VLabel>
                                    <VControl icon="feather:bookmark">
                                        <VInput type="text" v-model="item.hargadiskon" placeholder="Harga Diskon"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField>
                                    <VLabel class="item">Diskon (%)</VLabel>
                                    <VControl icon="feather:bookmark">
                                        <VInput type="number" v-model="item.persenDiskon" placeholder="Diskon (%)"
                                            class="is-rounded" @input="changeDikson($event.target.value)" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField>
                                    <VLabel class="item">Total</VLabel>
                                    <VControl icon="feather:bookmark">
                                        <span class="txt-input">{{ H.formatRupiah(item.total, 'Rp.') }}</span>
                                    </VControl>

                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField>
                                    <VLabel class="item">Keterangan</VLabel>
                                    <VControl icon="feather:bookmark">
                                        <VInput type="text" v-model="item.KeteranganPakai" placeholder="Keterangan"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </form>
                </template>
                <template #action>
                    <VButton icon="feather:plus" @click="add()" :loading="isLoading" color="primary" raised>Tambah</VButton>
                </template>
            </VModal>

            <VModal :open="modalRiwayat" title="Riwayat Obat Alkes " size="big" actions="right"
                @close="modalRiwayat = false">
                <template #content>
                    <div class="timeline-wrapper" v-if="dataSourceRiwayat.length > 0">
                        <div class="timeline-header"></div>
                        <div class="timeline-wrapper-inner pt-0">
                            <div class="timeline-container">
                                <div class="timeline-item is-unread " v-for="(items, index)  in dataSourceRiwayat"
                                    :key="items.norec">

                                    <div class="date">
                                        <span>{{ items.tglpelayanan
                                        }}</span>
                                    </div>
                                    <div :class="'dot is-' + listColor[index + 1]">
                                    </div>
                                    <div class="collapse-icon is-clickable" @click=" items.isExpand = true"
                                        v-if="!items.isExpand">
                                        <VIcon icon="feather:chevron-down" />
                                    </div>
                                    <div class="collapse-icon  is-clickable mr-1 " open @click=" items.isExpand = false"
                                        v-else>
                                        <VIcon icon="feather:chevron-up" />
                                    </div>
                                    <div class="content-wrap is-grey">
                                        <table class="is-fullwidth">
                                            <tr>
                                                <td style="width:25%" rowspan="2">
                                                    <p class="td-label-x">{{
                                                        items.ruangandepo
                                                    }}</p>
                                                    <span class="td-label-xxx">{{
                                                        items.dokter
                                                    }}</span>
                                                </td>
                                                <td style="width:10%">
                                                    <span class="td-label">No
                                                        Resep</span>
                                                </td>
                                                <td style="width:15%">
                                                    <span class="td-label">Ruangan
                                                        Asal</span>
                                                </td>


                                                <td style="width:10%" rowspan="2">

                                                    <VIconButton icon="feather:trash" @click="DialogConfirm(items)"
                                                        color="danger" raised circle>
                                                    </VIconButton>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="td-label-xx">{{
                                                        items.noresep
                                                    }}</span>
                                                </td>
                                                <td>
                                                    <span class="td-label-xx">{{
                                                        items.namaruangan
                                                    }}</span>
                                                </td>

                                            </tr>

                                        </table>
                                        <VCard custom="card-green" class="mt-1" v-if="items.isExpand">
                                            <div class="columns is-multiline" v-for="(itemsDet, index2)  in items.details"
                                                :key="index2">
                                                <div class="column is-1">
                                                    <VField>
                                                        <VLabelText>R/ke
                                                        </VLabelText>
                                                        <VLabel>{{ itemsDet.rke }}
                                                        </VLabel>
                                                    </VField>
                                                </div>
                                                <div class="column is-2">
                                                    <VField>
                                                        <VLabelText>Jenis Kemasan
                                                        </VLabelText>
                                                        <VLabel>
                                                            {{ itemsDet.jeniskemasan }}
                                                        </VLabel>
                                                    </VField>
                                                </div>

                                                <div class="column is-3">
                                                    <VField>
                                                        <VLabelText>Nama Produk
                                                        </VLabelText>
                                                        <VLabel class="txt-elipsis-2">{{
                                                            itemsDet.namaproduk
                                                        }} |
                                                            {{ itemsDet.satuanstandar }}
                                                        </VLabel>
                                                    </VField>
                                                </div>
                                                <div class="column is-2">
                                                    <VField>
                                                        <VLabelText>Jumlah </VLabelText>
                                                        <VLabel>{{ itemsDet.jumlah }}
                                                        </VLabel>
                                                    </VField>
                                                </div>

                                                <div class="column is-2">
                                                    <VField>
                                                        <VLabelText>Aturan Pakai
                                                        </VLabelText>
                                                        <VLabel>{{ itemsDet.aturanpakai
                                                        }}
                                                        </VLabel>
                                                    </VField>
                                                </div>


                                            </div>
                                        </VCard>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <VCard radius="rounded" class="mt-2" v-if="dataSourceRiwayat.length === 0">
                        <VPlaceholderPage title="Data belum ada."
                            subtitle="Sepertinya data ini belum di inputkan, silahkan melakukan penginputan terlebih dahulu."
                            larger>
                            <template #image>
                                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg"
                                    alt="" />
                                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                    alt="" />
                            </template>
                        </VPlaceholderPage>
                    </VCard>
                </template>
            </VModal>
        </div>
    </div>
</template>
    
<script setup lang="ts">

import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
    ref,
    computed,
    defineComponent,
    watch,
    nextTick,
    onMounted,
    reactive,
    watchEffect
} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { formatRp } from '/@src/utils/appHelper'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import PrimeVue from 'primevue/config';
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import AutoComplete from 'primevue/autocomplete';
import moment from 'moment'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import { useToast } from "primevue/usetoast";

const toast = useToast();

const TITLE_PAGE = 'Floor Stock'
useHead({
    title: TITLE_PAGE + ' ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
const props = defineProps({
    registrasi: {
        type: Object as PropType<any>,
    },
    pasien: {
        type: Object as PropType<any>,
    },
    selected: undefined,
    type: undefined,
    align: undefined,
})
useViewWrapper().setFullWidth(props.pasien ? true : false)
let NOREC_APD = props.registrasi.norec_apd
let NOREC_ORDER: any = useRoute().query.norec_order as string
let NOREC_RESEP: any = useRoute().query.norec_resep as string

let item: any = reactive({
    header: {
        "nocm": props.pasien.nocm,
        "namapasien": props.pasien.namapasien,
        "tglregistrasi": props.registrasi.tglregistrasi,
        "klsid": props.registrasi.objectkelasfk,
        "noregistrasi": props.registrasi.noregistrasi,
    },
    totalAll: 0,
    jumlah: 0,
    persenDiskon: 0,
    // aturanPakai: [],
    hargadiskon: 0,
    tglAwal: new Date(),
    rke: 1,
    resep: '-',
})
const modalInput = ref(false)
const modalRiwayat = ref(false)
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const confirm = useConfirm();
const d_penulisResep: any = ref([])
const d_ruangan: any = ref([])
const d_produk: any = ref([])
const d_satuan: any = ref([])
const d_aturanPakai: any = ref([])
const d_kemasan: any = ref([])
const d_jenisRacikan: any = ref([])
const d_route: any = ref([])
const d_tglKadaluarsa: any = ref([])
const d_satuanResep: any = ref([])
const d_asalProduk: any = ref([])
const d_Registrasi: any = ref([])
const dataSource: any = ref([])
const dataSourceRiwayat: any = ref([])
const listRiwayat = ref([])
const data2: any = ref([])
const dataOK: any = ref([])
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const isLoading: any = ref(false)
const isLoadInput: any = ref(false)
const isSimpan: any = ref(false)
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const dataProdukDetail: any = ref([])
const disabledRuangan: any = ref(false)
const showGridKronis: any = ref(false)
const dataGridKronis: any = ref([])
const dataSelected: any = ref({})
const isPemakaianObatAlkes: any = ref(false)
const isLoadingRiw: any = ref(false)
const disTanggal: any = ref(false)
const checkResepPulang: any = ref(false)
const isEdit: any = ref(false)
const showRacikanDose: any = ref(false)
const showTgl = ref(false)
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
for (let i = 0; i < colors.value.length; i++) {
    const element = colors.value[i];
    if (i <= 9 && element != 'primary')
        listColor.value.push(element)
}
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])

// const fetchProduk = async (query: any) => {
//     let qNamaProduk = ''
//     if (query) {
//         qNamaProduk = '?namaproduk=' + query.toLowerCase()
//     }
//     const response = await useApi().get(
//         `/farmasi/input-resep-produk${qNamaProduk}`)

//     return response.produk.map((item: any) => {
//         return { value: item, label: item.namaproduk }
//     })
// }

const getRegistrasi = async (e: any) => {
    console.log(e)
    item.tglAwal =  e.tanggal
}

const loadDrop = async () => {
    useApi().get(
        `/tindakan/list-dropdown-registrasi?nocmfk=${ID_PASIEN}`).then((response: any) => {
            d_Registrasi.value = response.registrasi.map((e: any) => { return { label: e.tglregistrasi + ' - (' + e.noregistrasi + ' - ' + e.namaruangan + ')', value: e, default: e } })
            for (let i = 0; i < response.registrasi.length; i++) {
                console.log(H.formatDate(new Date(), 'DD-MM-YYYY'))
                if(response.registrasi[i].tglregistrasi == H.formatDate(new Date(), 'DD-MM-YYYY') && response.registrasi[i].objectdepartemenfk == 18){
                    console.log('ini sama')
                    console.log(d_Registrasi.value[i])
                    item.pilihRegistrasi = d_Registrasi.value[i].value
                }

                if(response.registrasi[i].tglpulang == null && response.registrasi[i].objectdepartemenfk == 16){
                    console.log('ini sama')
                    console.log(d_Registrasi.value[i])
                    item.pilihRegistrasi = d_Registrasi.value[i].value
                }
            }
            //item.ruangan = d_RuanganRJ.value[0].value
        })
    const response = await useApi().get(
        `/farmasi/input-resep-cbo?floorstock=true`)
    d_aturanPakai.value = response.signa.map((e: any) => { return { aturanpakai: e.signa, id: e.id } })
    d_kemasan.value = response.jeniskemasan
    d_produk.value = response.produk//.map((e: any) => { return { label: e.namaproduk, value: e } })
    // d_ruangan.value = response.ruangan   //.map((e: any) => { return { label: e.namaruangan, value: e } })
    d_ruangan.value = response.ruangan.filter(element => element.id == props.registrasi.objectruanganfk);
    d_penulisResep.value = response.penulisresep//.map((e: any) => { return { label: e.namalengkap, value: e } })
    d_jenisRacikan.value = response.jenisracikan//.map((e: any) => { return { label: e.jenisracikan, value: e } })
    d_route.value = response.route//.map((e: any) => { return { label: e.name, value: e } })
    d_satuanResep.value = response.satuanresep//.map((e: any) => { return { label: e.satuanresep, value: e } })
    d_asalProduk.value = response.asalproduk//.map((e: any) => { return { label: e.asalproduk, value: e } })
    if (d_aturanPakai.value[0])
        item.aturanPakai = d_aturanPakai.value[0]
    if (d_ruangan.value[0])
        item.ruangan = d_ruangan.value[0]
    if (d_kemasan.value[1])
        item.jenisKemasan = d_kemasan.value[1]
    item.tarifadminresep = response.tarifadminresep ? response.tarifadminresep : 0
    disabledRuangan.value = false;
    if (NOREC_RESEP != undefined) {
        NOREC_ORDER = 'EditResep'
        loadEdit()
    }
    if (NOREC_ORDER != undefined) {
        loadEdit()
    }

}

const loadEdit = async () => {
    if (NOREC_ORDER != '') {
        isEdit.value = true
        if (NOREC_ORDER == 'EditResep') {
            isSimpan.value = true;
            isLoading.value = true
            const response = await useApi().get(
                `/farmasi/input-resep-edit?norecResep=${NOREC_RESEP}`)
            console.log(response)
            isLoading.value = false
            isSimpan.value = false;
            isEdit.value = false
            disabledRuangan.value = true;
            let headerRESEP = response.detailresep
            let detailRESEP = response.pelayananPasien

            item.penulisResep = [];
            item.resep = headerRESEP.noresep
            item.ruangan = { id: headerRESEP.id, namaruangan: headerRESEP.namaruangan }
            item.penulisResep = { id: headerRESEP.pgid, namalengkap: headerRESEP.namalengkap }
            item.tglAwal = new Date(headerRESEP.tglresep);
            var resep = headerRESEP.noresep.split("/");
            var bulanNow = moment(new Date()).format('MM');
            if (resep[1].substr(2) != bulanNow) {
                useToaster().warning('Tanggal Resep Tidak Dapat Diubah (Hanya dapat diubah dibulan yang sama)')
                disTanggal.value = true;
            }
            if (detailRESEP.isreseppulang == '1') { checkResepPulang.value = true } else { checkResepPulang.value = false }

            data2.value = detailRESEP
            for (var i = data2.value.length - 1; i >= 0; i--) {
                const element = data2.value[i]
                if (element.iskronis == true)
                    element.obtkronis = "✔"
                else
                    element.obtkronis = ""
                element.noregistrasifk = NOREC_APD
                element.tglregistrasi = item.header.tglregistrasi
                element.kelasfk = data2.value[0].kelasfk

            }
            dataSource.value = data2.value

            countTotal()
        } else {
            isSimpan.value = true;
            isLoading.value = true
            const response = await useApi().get(
                `/farmasi/input-resep-order?norec=${NOREC_ORDER}`)
            isSimpan.value = false;
            isLoading.value = false
            isEdit.value = false
            disabledRuangan.value = true;

            let headerRESEP = response.strukorder
            let detailRESEP = response.orderpelayanan
            item.penulisResep = [];
            item.ruangan = { id: headerRESEP.id, namaruangan: headerRESEP.namaruangan }
            item.penulisResep = { id: headerRESEP.pgid, namalengkap: headerRESEP.namalengkap }
            if (headerRESEP.isreseppulang == '1') { checkResepPulang.value = true } else { checkResepPulang.value = false }
            data2.value = detailRESEP
            for (var i = data2.value.length - 1; i >= 0; i--) {
                const element = data2.value[i]
                element.noregistrasifk = NOREC_APD
                element.tglregistrasi = item.header.tglregistrasi
                element.kelasfk = item.header.klsid
                if (element.nilaikonversi == 0)
                    element.nilaikonversi = 1
                if (element.obatkronis == "1") {
                    var qtyOK: any = 0;
                    element.jumlahreal = parseFloat(element.jumlah);
                    qtyOK = (parseFloat(element.jumlah) * 7) / 30
                    element.jumlah = qtyOK;
                    element.jumlahobat = qtyOK;
                    element.jumlahcetak = parseFloat(element.jumlahreal) - qtyOK;
                    element.total = (parseFloat(qtyOK) * (parseFloat(element.hargasatuan) - parseFloat(element.hargadiscount)) * parseFloat(element.nilaikonversi)) + parseFloat(element.jasa);
                    dataOK.value.push(data2[i]);
                }
            }
            for (let x = 0; x < dataOK.value.length; x++) {
                const element = dataOK.value[x];
                element.no = x + 1;
            }
            for (let j = 0; j < data2.value.length; j++) {
                const element = data2.value[j];
                if (element.iskronis == true) {
                    element.obtkronis = "✔"
                } else {
                    element.obtkronis = ""
                }
            }
            dataSource.value = data2.value
            countTotal()

        }
    }
}
const addPopUp = () => {

    // toast.add({ severity: 'success', summary: 'PrimeVue', detail: 'Welcome to PrimeVue + Create Vue', life: 3000 })
    // return
    if (!item.ruangan) {
        useToaster().error('Ruangan harus di pilih')
        return
    }

    if (NOREC_APD) {
        dataSource.value.forEach((element: any, i: any) => {
            if (dataSource.value.length - 1 == i) {
                d_kemasan.value.forEach((e: any) => {
                    if (e.id == element.jeniskemasanfk) {
                        item.jenisKemasan = e
                        return
                    }
                });
                if (element.jeniskemasanfk == 1) {
                    item.rke = element.rke
                } else {
                    item.rke = parseInt(element.rke) + 1
                }
            }
        });
    }

    clearInput()
    modalInput.value = true
}
const add = () => {
    if (!item.produk) {
        useToaster().error('Produk harus di isi')
        return
    }
    if (!item.jumlah || item.jumlah == 0) {
        useToaster().error('Jumlah harus di isi')
        return
    }
    if (item.hargaSatuan == 0) {
        useToaster().error('Harga Satuan belum ada')
        return
    }

    if (item.stok == 0) {
        useToaster().error('Stok tidak ada')
        return
    }

    if (norecSPD.value == '') {
        useToaster().error('Stok tidak ada')
        return
    }
    if (!item.satuan) {
        useToaster().error('Satuan harus di isi')
        return
    }
    if (!item.satuan) {
        useToaster().error('Satuan harus di isi')
        return
    }
    if (!item.aturanPakai) {
        useToaster().error('Aturan Pakai harus di isi')
        return
    }
    var dosis = 1;
    if (item.jenisKemasan.jeniskemasan == 'Racikan') {
        dosis = item.dosis
        item.jumlahxmakan = (parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan)
    } else {
        item.jumlahxmakan = item.jumlah
    }
    let nomor = 0
    if (data2.value.length == 0) {
        nomor = 1
    } else {
        nomor = data2.value.length + 1
    }

    var qtyOK: any = 0;
    var qtyCetak = 0;
    var total = 0;
    var jumlahreal = 0;

    let jmlbulat = 0;
    let jml = 0;

    jmlbulat = item.jumlahbulat;
    jml = item.jumlah;

    disabledRuangan.value = true;
    var data: any = {};
    if (item.no != undefined) {
        for (let x = 0; x < data2.value.length; x++) {
            const element = data2.value[x];
            if (element.no == item.no) {
                data.no = item.no
                data.noregistrasifk = NOREC_APD
                data.generik = null
                data.hargajual = item.hargaSatuan
                data.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.id : null
                data.jenisobat = item.jenisRacikan ? item.jenisRacikan.jenisracikan : null
                data.kelasfk = item.header.klsid
                data.stock = item.stok
                data.harganetto = item.hargaNetto
                data.nostrukterimafk = norecTerima.value
                data.norec_spd = norecSPD.value
                data.ruanganfk = item.ruangan.id
                data.rke = item.rke
                data.jeniskemasanfk = item.jenisKemasan.id
                data.jeniskemasan = item.jenisKemasan.jeniskemasan
                data.aturanpakai = item.aturanPakai.aturanpakai
                data.aturanpakaifk = item.aturanPakai.id
                data.iskronis = item.checkisKronis ?? null
                data.routefk = item.route ? item.route.id : null
                data.route = item.route ? item.route.name : null
                data.asalprodukfk = item.asal.id
                data.asalproduk = item.asal.asalproduk
                data.produkfk = item.produk.id
                data.namaproduk = item.produk.namaproduk
                data.nilaikonversi = item.nilaiKonversi
                data.satuanstandarfk = item.satuan.ssid
                data.satuanstandar = item.satuan.satuanstandar
                data.satuanviewfk = item.satuan.ssid
                data.satuanview = item.satuan.satuanstandar
                data.jmlstok = item.stok
                data.jumlah = jmlbulat
                data.jumlahobat = jml
                data.dosis = dosis
                data.hargasatuan = String(item.hargaSatuan)
                data.hargadiscount = String(item.hargadiskon)
                data.persendiscount = item.persenDiskon ? item.persenDiskon : 0
                data.total = item.total
                data.jmldosis = String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan)
                data.jasa = tarifJasa.value
                data.keterangan = item.KeteranganPakai ? item.KeteranganPakai : null
                data.satuanresepfk = item.satuanresep ? item.satuanresep.id : null
                data.satuanresep = item.satuanresep ? item.satuanresep.satuanresep : null
                data.tglkadaluarsa = item.tglKadaluarsa ? item.tglKadaluarsa.tglkadaluarsa : null

                for (let i = 0; i < data2.value.length; i++) {
                    const element = data2.value[i];
                    if (element.iskronis == true) {
                        element.obtkronis = "✔"
                    } else {
                        element.obtkronis = ""
                    }
                }
                data2.value[x] = data;
            }
        }
    } else {
        if (data2.length > 0) {
            var racikan = data2.value[data2.value.length - 1].jeniskemasan
            if (racikan == 'Non Racikan') {
                item.rke = data2.value[data2.value.length - 1].rke + 1
            }
        }
        data = {
            no: nomor,
            noregistrasifk: NOREC_APD,
            generik: null,
            hargajual: String(item.hargaSatuan),
            jenisobatfk: item.jenisRacikan ? item.jenisRacikan.id : null,
            jenisobat: item.jenisRacikan ? item.jenisRacikan.jenisracikan : null,
            kelasfk: item.header.klsid,
            stock: String(item.stok),
            harganetto: String(item.hargaNetto),
            nostrukterimafk: norecTerima.value,
            norec_spd: norecSPD.value,
            ruanganfk: item.ruangan.id,
            rke: item.rke,
            jeniskemasanfk: item.jenisKemasan.id,
            jeniskemasan: item.jenisKemasan.jeniskemasan,
            aturanpakaifk: item.aturanPakai.id,
            aturanpakai: item.aturanPakai.aturanpakai,

            // ispagi: item.chkp,
            // issiang: item.chks,
            // issore: item.chksr,
            // ismalam: item.chkm,
            iskronis: item.checkisKronis ?? null,
            // aturanpakai2: item.aturanPakai2 ,
            // sbsmid: item.sbsm.id,
            // sbsmname: item.sbsm.name,
            routefk: item.route ? item.route.id : null,
            route: item.route ? item.route.name : null,
            asalprodukfk: item.asal.id,
            asalproduk: item.asal.asalproduk,
            produkfk: item.produk.id,
            namaproduk: item.produk.namaproduk,
            nilaikonversi: item.nilaiKonversi,
            satuanstandarfk: item.satuan.ssid,
            satuanstandar: item.satuan.satuanstandar,
            satuanviewfk: item.satuan.ssid,
            satuanview: item.satuan.satuanstandar,
            jmlstok: String(item.stok),
            jumlah: jmlbulat,//item.jumlahbulat,
            jumlahobat: jml,//item.jumlah,
            dosis: dosis,
            hargasatuan: String(item.hargaSatuan),
            hargadiscount: String(item.hargadiskon),
            persendiscount: item.persenDiskon ? item.persenDiskon : 0,
            total: item.total,
            jmldosis: String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan),
            jasa: tarifJasa.value,
            keterangan: item.KeteranganPakai ? item.KeteranganPakai : null,
            satuanresepfk: item.satuanresep ? item.satuanresep.id : null,
            satuanresep: item.satuanresep ? item.satuanresep.satuanresep : null,
            tglkadaluarsa: item.tglKadaluarsa ? item.tglKadaluarsa.tglkadaluarsa : null,
        }
        data2.value.push(data)

        for (let i = 0; i < data2.value.length; i++) {
            const element = data2.value[i];
        }

    }
    dataSource.value = data2.value
    if (item.jenisKemasan.jeniskemasan != 'Racikan') {
        item.rke = parseFloat(item.rke) + 1
    }
    console.log(dataSource.value)
    countTotal()
    clearInput()
}
const countTotal = () => {
    let total = 0
    for (let x = 0; x < data2.value.length; x++) {
        const element = data2.value[x];
        total = total + parseFloat(element.total)
    }
    TOTAL.value = formatRp(total, '')
}
const countTotalSub = () => {
    item.total = ((item.qty ? item.qty : 0) * (item.harga
        ? item.harga : 0)) - (item.diskon ? item.diskon : 0)
}
const editRow =async (e: any) => {
    // if (isLoading.value == false)
    //     return
    item.no = e.no
    item.rke = e.rke
    d_kemasan.value.forEach((element: any) => {
        if (element.id == e.jeniskemasanfk) {
            item.jenisKemasan = element
            return
        }
    });
    d_satuanResep.value.forEach((element: any) => {
        if (element.id == e.satuanresepfk) {
            item.satuanresep = element
            return
        }
    });
    d_aturanPakai.value.forEach((element: any) => {
        if (element.id == e.aturanpakaifk) {
            item.aturanPakai = element
            return
        }
    });
    d_jenisRacikan.value.forEach((element: any) => {
        if (element.id == e.jenisobatfk) {
            item.jenisRacikan = element
            return
        }
    });
    d_route.value.forEach((element: any) => {
        if (element.id == e.routefk) {
            item.route = element
            return
        }
    })
    item.KeteranganPakai = e.keterangan
    item.persenDiskon = e.persendiscount

    if (e.iskronis == true) {
        item.checkisKronis = true
    } else {
        item.checkisKronis = false
    }
    if (e.asalprodukfk) {
        d_asalProduk.value.forEach((element: any) => {
            if (element.id == e.asalprodukfk) {
                item.asal = element
                return
            }
        })
    }
    await fetchProduk({ query: e.namaproduk })
    d_produk.value.forEach((element: any) => {
        if (element.id == e.produkfk) {
            item.produk = element
        }
    });



    tarifJasa.value = e.jasa
    dataSelected.value = e
    GETKONVERSI()
    modalInput.value = true
}
const fetchProduk = async (filter: any) => {

    const response = await useApi().get(
        `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
    d_produk.value = response

}
const hapusRow = (e: any) => {
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (data2.value[i].no == e.no) {
            data2.value.splice(i, 1);
            dataOK.value.splice(i, 1);
        }
    }
    dataSource.value = data2.value
    dataGridKronis.value = dataOK.value
    countTotal()
    clearInput()
}
const save = async () => {
    if (!item.penulisResep) {
        useToaster().error('Penulis Resep harus di pilih')
        return
    }
    if (data2.value.length == 0) {
        useToaster().error('Produk belum di pilih')
        return
    }
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (data2.value[i].hargasatuan == 0) {
            useToaster().error("Terdapat obat dengan harga kosong, kemungkinan stock kosong!!")
            return
        }
    }
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (parseFloat(data2.value[i].jmlstok) < parseFloat(data2.value[i].jumlah)) {
            useToaster().error("Terdapat obat dengan jumlah melebihi STOK !! " + data2.value[i].namaproduk)
            return
        }
    }

    var objSave =
    {
        'strukresep': {
            'tglresep': moment(item.tglAwal).format('YYYY-MM-DD HH:mm:ss'),
            'tglregistrasi': item.pilihRegistrasi.tanggal,
            'noregistrasi': item.pilihRegistrasi.noregistrasi,
            'pasienfk': item.pilihRegistrasi.norec_apd,
            'nocm': item.header.nocm,
            'namapasien': item.header.namapasien,
            'penulisresepfk': item.penulisResep.id,
            'ruanganfk': item.ruangan.id,
            'noorder': NOREC_ORDER ? NOREC_ORDER : '',
            'norecResep': NOREC_RESEP ? NOREC_RESEP : '',
            'noresep': item.resep,
            'retur': '-',
            'isobatalkes': 'floor-stock',
            'isreseppulang': item.isreseppulang ? item.isreseppulang : null
        },
        'pelayananpasien': dataSource.value
    }

    // randomprice
    isSimpan.value = true
    await useApi().post(
        `/farmasi/input-resep-save`, objSave).then((response: any) => {
            isSimpan.value = false
            item.resep = response.noresep.norec;
            dataSource.value = []
            if (dataOK.value.length > 0) {
                var objSaveKronis = {
                    strukresep: objSave.strukresep,
                    norecresep: item.resep,
                    pelayananpasienobatkronis: dataOK.value,
                }
                useApi().post(
                    `/farmasi/input-resep-kronis-save`, objSaveKronis)
            }
            
            // window.history.back();
        }, (error) => {
            isSimpan.value = false
            // console.log(error)
        })


}
const changeProduk = (e: any) => {
    if (e != null && e != undefined) {
    GETKONVERSI()
  }
}
const changeExpired = (e: any) => {
    setNorecSPD()
}
const GETKONVERSI = () => {
    d_satuan.value = []
    d_satuan.value = item.produk.konversisatuan
  if (item.produk.konversisatuan.length == 0) {
    d_satuan.value = [
      {
        ssid: item.produk.ssid, satuanstandar: item.produk.satuanstandar
      }]
  }
  item.produk.konversisatuan.forEach((element: any) => {
    if (element.ssid == item.produk.ssid) {
      item.satuan = element
      return
      // item.satuan = { ssid: element.ssid, satuanstandar: element.satuanstandar, nilaikonversi: element.nilaikonversi }
      // return
    }
  });
  d_satuan.value.forEach((element: any) => {
    if (element.ssid == item.produk.ssid) {
      item.satuan = element
    }
  })

    item.nilaiKonversi = 1
    isLoading.value = true
    dataProdukDetail.value = []
    useApi().get(
        '/farmasi/get-produkdetail?produkfk=' + item.produk.id +
        '&ruanganfk=' + item.ruangan.id).then(function (response: any) {
            if (response.detail.length > 0) {
                console.log(response)
                console.log(response.detail[0].hargajual)
                d_tglKadaluarsa.value = response.detail
                // .map((e: any) => {
                //     return { label: e.tglkadaluarsa, value: e }
                // })
                dataProdukDetail.value = response.detail
                item.stok = response.jmlstok / item.nilaiKonversi
                if (response.kekuatan == undefined || response.kekuatan == 0) {
                    response.kekuatan = 1
                }
                item.kekuatan = response.kekuatan
                item.sediaan = response.sediaan
                item.tglKadaluarsa = d_tglKadaluarsa.value[0]
                item.hargaSatuan = response.detail[0].hargajual
                item.hargaNetto = response.detail[0].harganetto

                if (dataSelected.value.no != undefined) {
                    item.jumlah = dataSelected.value.jumlahobat
                    item.jumlahbulat = dataSelected.value.jumlahobat
                    item.dosis = dataSelected.value.dosis
                    item.jumlahxmakan = (parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan)
                    item.nilaiKonversi = dataSelected.value.nilaikonversi

                    d_satuan.value.forEach((element: any) => {
                        if (element.ssid == dataSelected.value.satuanviewfk) {
                            item.satuan = element
                            return
                        }
                    });

                    item.hargaSatuan = dataSelected.value.hargasatuan
                    item.hargadiskon = dataSelected.value.hargadiscount
                    item.hargaNetto = dataSelected.value.harganetto
                    item.total = dataSelected.value.total
                }
                item.jumlah = 1
                setNorecSPD()
                isLoading.value = false
            } else {
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
                H.alert('error', 'Stok Produk Kosong')
                isLoading.value = false
            }

        });

}
const clearInput = () => {
    delete item.produk
    delete item.asal
    delete item.satuan
    delete item.no
    delete item.satuanresep
    delete item.KeteranganPakai
    delete dataSelected.value
    delete item.tglKadaluarsa
    item.qty = 1
    // item.totalAll = 0
    // TOTAL.value =0
    modalInput.value = false
    item.nilaiKonversi = 0
    item.stok = 0
    item.jumlah = 0
    item.jumlahbulat = item.jumlah
    item.jumlahxmakan = 1
    item.hargadiskon = 0
    item.total = 0
    item.hargaSatuan = 0
    item.hargaNetto = 0
    item.persenDiskon = 0
}
const changeDikson = (e: any) => {
    if (e) {
        item.hargadiskon = 0;
        if (item.persenDiskon > 100) {
            item.persenDiskon = 100
        }
        if (item.persenDiskon < 0) {
            item.persenDiskon = 0
        }

        if (item.persenDiskon > 0) {
            var diskon = (parseFloat(item.hargaSatuan) * parseFloat(item.persenDiskon)) / 100
            if (!isNaN(diskon)) {
                item.hargadiskon = diskon;
                item.total = parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon);
            }
        }
    } else {
        item.hargadiskon = 0
        item.total = parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon);
    }
}

const setNorecSPD = () => {
    if (!item.jumlah) return

    if (item.jenisKemasan == undefined) {
        return
    }
    if (item.stok == 0) {
        item.jumlah = 0
        return;
    }
    var qty20 = 0
    tarifJasa.value = parseFloat(item.tarifadminresep)
    if (parseFloat(tarifJasa.value) != 0) {
        if (item.jenisKemasan.id == 2) {
            tarifJasa.value = parseFloat(item.tarifadminresep)
        }
        if (item.jenisKemasan.id == 1) {
            qty20 = Math.floor(parseFloat(item.jumlah) / 20)
            if (parseFloat(item.jumlah) % 20 == 0) {
                qty20 = qty20
            } else {
                qty20 = qty20 + 1
            }

            if (qty20 != 0) {
                tarifJasa.value = tarifJasa.value * qty20
            }
        }
    }
    if (item.no == undefined) {
        for (var i = data2.value.length - 1; i >= 0; i--) {
            if (data2.value[i].rke == item.rke) {
                tarifJasa.value = 0
            }
        }
    }
    item.jumlahbulat = item.jumlah

    var ada = false;
    for (var i = 0; i < dataProdukDetail.value.length; i++) {
        ada = false
        const element = dataProdukDetail.value[i]
        var tglExpiredPilih = element.tglkadaluarsa
        if (item.tglKadaluarsa != undefined) {
            tglExpiredPilih = moment(item.tglKadaluarsa.tglkadaluarsa).format('YYYY-MM-DD HH:mm:ss')
        }


        if (parseFloat(item.jumlah) * parseFloat(item.nilaiKonversi)
            <= parseFloat(element.qtyproduk)
            //&& tglExpiredPilih == element.tglkadaluarsa
        ) {
            hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi))
            hrgsdk.value = parseFloat(element.hargadiscount) * parseFloat(item.nilaiKonversi)
            item.hargaSatuan = hrg1.value
            item.hargaNetto = Math.round(parseFloat(element.harganetto) * parseFloat(item.nilaiKonversi))
            if (item.hargadiskon == 0) {
                item.hargadiskon = hrgsdk.value
            } else {
                hrgsdk.value = item.hargadiskon
            }
            console.log(parseFloat(item.jumlahbulat))
            console.log(hrg1.value - hrgsdk.value)
            console.log(parseFloat(tarifJasa.value))
            item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) 
            //+ parseFloat(tarifJasa.value)
            norecTerima.value = element.norec
            norecSPD.value = element.norec_spd
            item.asal = { id: element.objectasalprodukfk, asalproduk: element.asalproduk }
            ada = true;
            break;
        }
    }
    if (ada == false) {
        item.hargaSatuan = 0
        item.hargadiskon = 0
        item.hargaNetto = 0
        item.total = 0

        norecSPD.value = ''
        norecTerima.value = ''
        if (dataProdukDetail.value.length > 1) {

            var objSave =
            {
                produkfk: item.produk.id,
                ruanganfk: item.ruangan.id
            }

            isSimpan.value = true;
            useApi().postNoMessage(
                '/farmasi/save-stock-merger', objSave).then(function (response: any) {
                    clearInput()
                    isSimpan.value = false
                })


        }
    }
    if (item.jumlah == 0) {
        item.hargaSatuan = 0
        item.hargaNetto = 0
    }
}
const jumlahkan = () => {
    if (item.stok > 0) {
        item.jumlah = (parseFloat(item.jumlahxmakan) * parseFloat(item.dosis)) / parseFloat(item.kekuatan)
        item.jumlahbulat = item.jumlah
    }
}
const changeSatuan = (e: any) => {
    item.nilaiKonversi = item.satuan.nilaikonversi
}
const back = () => {
    window.history.back()
}
const riwayatResep = async () => {
    modalRiwayat.value = true
    dataSourceRiwayat.value = []
    const response = await useApi().get(
        `/farmasi/transaksi-pelayanan-farmasi?norec_pd=${NOREC_PD}`)


    let total = 0
    for (let x = 0; x < response.detail.length; x++) {
        const element = response.detail[x];
        total = total + element.total
        element.tglkadaluarsa = H.formatDate(element.tglkadaluarsa, 'DD-MMM-YYYY')
    }
    TOTAL.value = formatRp(total, '')
    let groupData = group(response.detail)
    let z = 0
    for (let x = 0; x < groupData.length; x++) {
        const element = groupData[x];
        element.isExpand = true
        element.icon = 'fa-inverse lnir lnir-medicine-alt'
        element.color = listColor2.value[z]
        element.tglpelayanan = H.formatDateIndoSimple(new Date(element.tglpelayanan))
        if (z > 4) {
            z = 0
        }
        z++
    }

    dataSourceRiwayat.value = groupData

}

const group = (datas: any) => {
    let sama = false
    let arrGroup = [];
    for (let i = 0; i < datas.length; i++) {
        sama = false
        datas[i].rke = parseInt(datas[i].rke)
        // datas[i].details = []
        for (let x = 0; x < arrGroup.length; x++) {
            if (arrGroup[x].norec_resep == datas[i].norec_resep) {
                arrGroup[x].total = parseFloat(arrGroup[x].total) + parseFloat(datas[i].total)
                // arrGroup[x].details.push(datas[i])
                sama = true;
            }
        }
        if (sama == false) {
            let result = {
                'norec_apd': datas[i].norec_apd,
                'norec_pd': datas[i].norec_pd,
                'norec_resep': datas[i].norec_resep,
                'noresep': datas[i].noresep,
                'dokter': datas[i].dokter,
                'tglpelayanan': datas[i].tglpelayanan,
                'ruangandepoid': datas[i].ruangandepoid,
                'namaruangan': datas[i].namaruangan,
                'ruangandepo': datas[i].ruangandepo,
                'noregistrasi': datas[i].noregistrasi,
                'nostruk': datas[i].nostruk,
                'total': datas[i].total,
                'details': []
            }
            arrGroup.push(result)
        }
    }
    datas.sort(compareRKE);
    arrGroup.sort(compare);
    for (let x = 0; x < arrGroup.length; x++) {
        const element: any = arrGroup[x];
        element.no = x + 1
        for (let z = 0; z < datas.length; z++) {
            const elementx = datas[z];
            elementx.no = z + 1;
            if (elementx.norec_resep == element.norec_resep) {
                element.details.push(elementx)
            }
        }
    }

    return arrGroup

}
const compare = (a: any, b: any) => {
    if (a.tglpelayanan > b.tglpelayanan) {
        return -1;
    }
    if (a.tglpelayanan < b.tglpelayanan) {
        return 1;
    }
    return 0;
}
const compareRKE = (a: any, b: any) => {
    if (a.rke < b.rke) {
        return -1;
    }
    if (a.rke > b.rke) {
        return 1;
    }
    return 0;
}
const DialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            hapusRiwayat(e)

        },
        reject: () => { },
    })
}
function hapusRiwayat(e: any) {
    if (e.nostruk != null) {
        H.alert('error', 'Data Sudah di verifikasi tidak bisa dihapus')
        return
    }
    isSimpan.value = true
    useApi().post(
        `/farmasi/transaksi-pelayanan-farmasi-hapus`, {
        'norec': e.norec_resep
    }).then((response: any) => {
        isSimpan.value = true
        loadRiwayat()
    })

}
watch(
    () => item.jenisKemasan,
    (newValue, oldValue) => {
        if (newValue.jeniskemasan == 'Racikan') {
            showRacikanDose.value = true
        } else {
            showRacikanDose.value = false
        }
    }
)

watch(
    () => item.jumlah,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            setNorecSPD()
        }
    }
)
watch(
    () => item.jumlahxmakan,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            jumlahkan()
        }
    }
)
watch(
    () => item.dosis,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            jumlahkan()
        }
    }
)
watch(
    () => item.hargadiskon,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            hrgsdk.value = item.hargadiskon
            item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
            if (isNaN(item.total)) {
                item.total = 0
            }
        }
    }
)
watch(
    () => item.nilaiKonversi,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (item.stok > 0) {
                item.stok = parseFloat(item.stok) * (parseFloat(oldValue) / parseFloat(newValue))
                item.jumlah = 0
                item.jumlahbulat = 0;
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
            }
        }
    }
)
watch(() => item.rke, (newValue, oldValue) => {
    if (newValue != oldValue) {
        if (tarifJasa == 0) {
            for (var i = data2.value.length - 1; i >= 0; i--) {
                tarifJasa.value = parseFloat(item.tarifadminresep)
                if (data2.value[i].rke == item.rke) {
                    tarifJasa.value = 0
                    break;
                }
            }
        }
    }
})

watch(() => [
    item.jenisKemasan,
], (newValue, oldValue) => {
    if (item.no) {
        item.rke = item.rke
    } else {
        dataSource.value.forEach((element: any, i: any) => {
            if (dataSource.value.length - 1 == i) {
                if (item.jenisKemasan.id == 1 && element.jeniskemasanfk == 1) {
                    item.rke = element.rke
                    return
                } if (item.jenisKemasan.id == 2 && element.jeniskemasanfk == 1) {
                    item.rke = parseFloat(element.rke) + 1
                    return
                }
            }
        });
    }
})

loadDrop()
// loadRiwayat()
onMounted(() => {

})
</script>
    
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
// @import '/@src/scss/custom/config';

.form-layout .form-outer {
    border: 1px solid transparent;
    background-color: transparent;
}


// .form-layout {
//     .form-outer {
//         padding: 20px 40px 40px;
//     }
// }
.txt-input {
    align-items: center;
    border: 1px solid transparent;
    border-radius: var(--radius);
    box-shadow: none;
    display: inline-flex;
    font-size: 1rem;
    height: 2.5em;
    justify-content: flex-start;
    line-height: 1.5;
    position: relative;
    vertical-align: top;
    background-color: hsl(0deg, 0%, 96%);
    border-color: hsl(0deg, 0%, 96%);
    box-shadow: none;
    color: hsl(0deg, 0%, 48%);
    height: 38px;
    width: 100%;
    transition: all 0.3s;
    border-radius: var(--radius-rounded);

    padding-left: 38px;
    padding-right: calc(calc(0.75em - 1px) + 0.375em);

}
</style>
    
    