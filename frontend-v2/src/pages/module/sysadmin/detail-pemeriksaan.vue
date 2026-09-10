<template>
    <ConfirmDialog />
    <div class="form-layout is-stacked">
        <div class="form-outer" style="margin-top: 15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Tambah Detail Pemeriksaan</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined RouterLink @click="back()">
                                Kembali
                            </VButton>
                            <VButton v-if="useRoute().query.norec" type="button" rounded outlined color="primary"
                                :loading="btnSaveandUpdate" @click="save()" raised icon="feather:edit">
                                Update
                            </VButton>
                            <VButton v-else type="button" rounded outlined color="primary" :loading="btnSaveandUpdate"
                                @click="save()" raised icon="feather:save">
                                Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-body ">

                <div v-if="isLoadEdit == true">
                    <VPlaceloadWrap class="mt-5">
                        <VPlaceload height="30px" width="40%" class="mx-2" />
                        <VPlaceload height="30px" width="30%" class="mx-2" />
                        <VPlaceload height="30px" width="40%" class="mx-2" />
                    </VPlaceloadWrap>
                    <VPlaceloadWrap class="mt-5">
                        <VPlaceload height="80px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                    <VPlaceloadWrap class="mt-5">
                        <VPlaceload height="30px" width="40%" class="mx-2" />
                        <VPlaceload height="30px" width="30%" class="mx-2" />
                        <VPlaceload height="30px" width="40%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div v-else>
                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-4">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel class="required-field">Nama Layanan</VLabel>
                                    <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                        <AutoComplete v-model="item.produk" :suggestions="d_produk"
                                            @complete="fetchProduk($event)" :optionLabel="'namaproduk'" :dropdown="true"
                                            :minLength="3" rounded :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'namaproduk'" placeholder="ketik untuk mencari..."
                                            />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Detail Pemeriksaan">
                                    <VPlaceloadWrap v-if="renderLoader">
                                        <VPlaceload height="30px" class="mx-2" />
                                    </VPlaceloadWrap>
                                    <VControl v-else icon="feather:bookmark">
                                        <VInput type="text" v-model="item.detailpemeriksaan" placeholder="Detail Pemeriksaan"
                                             />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel class="required-field">Penulis Resep</VLabel>
                                    <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                        <Dropdown v-model="item.penulisresep" :options="d_penulisResep" optionLabel="label"
                                            class="is-rounded" placeholder="Pilih data" style="width: 100%;"
                                            :filter="true" />
                                    </VControl>
                                </VField>
                            </div>

                        </div>
                    </div>
                    <div class="column is-12 pt-0">
                        <VField label="Alamat">
                            <VPlaceloadWrap v-if="renderLoader">
                                <VPlaceload height="50px" class="mx-2" />
                            </VPlaceloadWrap>
                            <VControl v-else>
                                <VTextarea v-model="item.alamatlengkap" rows="3" placeholder="Alamat Lengkap"></VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-4">
                                <VDatePicker v-model="item.tglresep" color="green" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VLabel>Tanggal Resep</VLabel>
                                            <VControl icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date" class="is-rounded"
                                                    :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-4">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel class="required-field">Penulis Resep</VLabel>
                                    <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                        <Dropdown v-model="item.penulisresep" :options="d_penulisResep" optionLabel="label"
                                            class="is-rounded" placeholder="Pilih data" style="width: 100%;"
                                            :filter="true" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel class="required-field">Ruangan</VLabel>
                                    <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                        <Dropdown v-model="item.ruangan" :options="d_ruangan" optionLabel="label"
                                            :disabled="disabledRuangan" class="is-rounded" placeholder="Pilih data"
                                            style="width: 100%;" :filter="true" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="column is-12 p-0 mt-5">
            <VCard>
                <div class="column is-2 p-0 pb-2" style="margin-left: auto">
                    <VButton type="button" icon="feather:plus-circle" @click="inputPopUp()"
                        class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
                        Tambah
                    </VButton>
                </div>
                <DataTable :value="dataSource" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                    class="p-datatable-sm"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                    <Column field="no" header="No"></Column>
                    <Column field="rke" header="R/ke"></Column>
                    <Column field="jeniskemasan" header="Kemasan"></Column>
                    <Column field="jmldosis" header="Jml/Dss/kkuatan"></Column>
                    <Column field="namaproduk" header="Produk"></Column>
                    <Column field="hargasatuan" header="Harga"></Column>
                    <Column field="hargadiscount" header="Diskon"></Column>
                    <Column field="aturanpakai" header="Aturan Pakai"></Column>
                    <Column field="satuanstandar" header="Satuan"></Column>
                    <Column field="jumlah" header="Qty"></Column>
                    <Column field="total" header="Total"></Column>
                    <Column :exportable="false" header="#" style="text-align: center">
                        <template #body="slotProps">
                            <VDropdown icon="feather:more-vertical" spaced right>
                                <template #content>
                                    <a role="menuitem" class="dropdown-item is-media" @click="editPopUp(slotProps.data)">
                                        <div class="icon">
                                            <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Edit</span>
                                            <span>Untuk merubah data </span>
                                        </div>
                                    </a>
                                    <a role="menuitem" style="background:#ED1C24" class="dropdown-item is-media"
                                        @click="dialogConfirm(slotProps.data)">
                                        <div class="icon">
                                            <i aria-hidden="true" style="color:whitesmoke"
                                                class="lnil lnil-trash-can-alt"></i>
                                        </div>
                                        <div class="meta">
                                            <span style="color:whitesmoke">Remove</span>
                                            <span style="color:whitesmoke">Hapus Data dari Daftar</span>
                                        </div>
                                    </a>
                                </template>
                            </VDropdown>
                        </template>
                    </Column>
                </DataTable>
            </VCard>
        </div>

        <div class="column is-12">
            <div class="content">
                <div class="is-divider" data-content="Total Keseluruhan" />
            </div>
        </div>

        <div class="column is-12 p-0">
            <div class="column is-3 p-0" style="margin-left: auto;">
                <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status" color="danger">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span class="ml-1">TOTAL</span>
                    </div>
                    <small class="text-bold-custom h-100">{{
                        H.formatRp(item.resultTotal, 'Rp.')
                    }}</small>
                </VCardCustom>
            </div>
        </div>

    </div>

    <VModal :open="modalInput" title="Add Resep" size="big" actions="right" @close="modalInput = false, clearInput()">
        <template #content>
            <form class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-2">
                        <VField>
                            <VLabel>R/Ke</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="number" v-model="item.rke" placeholder="R/Ke" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VLabel>Jenis Kemasan</VLabel>
                            <VControl>
                                <VRadio v-for="items in d_kemasan" :key="items.id" v-model="item.jenisKemasan"
                                    :value="items" :label="items.jeniskemasan" name="{{items.id}}" color="primary" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3" v-if="showRacikanDose">
                        <VField>
                            <VLabel>Jumlah </VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.jumlahxmakan" placeholder="Jumlah" class="is-rounded" />

                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3" v-if="showRacikanDose">
                        <VField>
                            <VLabel>Dosis </VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.dosis" placeholder="Dosis" class="is-rounded" />
                                <p class="help"> {{ (item.kekuatan ? 'Kekuatan : ' + item.kekuatan : '') + ' ' +
                                    (item.sediaan ?
                                        item.sediaan : '')
                                }}</p>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6" v-if="showRacikanDose">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Jenis Racikan</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.jenisRacikan" :options="d_jenisRacikan" optionLabel="label"
                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField>
                            <VLabel>Konversi</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.nilaiKonversi" placeholder="Konversi" disabled
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField>
                            <VLabel>Stok</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.stok" placeholder="Stok" class="is-rounded" disabled />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Produk</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <AutoComplete v-model="item.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                                    :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" rounded :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                                    placeholder="ketik untuk mencari..." @item-select="changeProduk(item.produk)" />
                                <!-- <Dropdown v-model="item.produk" :options="d_produk" optionLabel="label" class="is-rounded"
                                    placeholder="Pilih data" style="width: 100%;" :filter="true"
                                    @change="changeProduk(item.produk)" /> -->
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Satuan</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.satuan" :options="d_satuan" optionLabel="label" class="is-rounded"
                                    placeholder="Pilih data" style="width: 100%;" :filter="true"
                                    @change="changeSatuan(item.satuan)" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Route</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.route" :options="d_route" optionLabel="label" class="is-rounded"
                                    placeholder="Pilih data" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Tgl Kadaluarsa</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.tglKadaluarsa" :options="d_tglKadaluarsa" optionLabel="label"
                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true"
                                    @change="changeExpired(item.tglKadaluarsa)" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <div class="checkboxes">
                            <VField>
                                <VLabel>Aturan Pakai</VLabel>
                                <VControl>
                                    <VRadio v-for="items in d_aturanPakai" :key="items.id" v-model="item.aturanPakai"
                                        :value="items" :label="items.aturanpakai" color="primary" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Satuan Resep</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.satuanresep" :options="d_satuanResep" optionLabel="label"
                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <VLabel>Keterangan</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.KeteranganPakai" placeholder="Keterangan"
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <VLabel>Qty</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="number" v-model="item.jumlah" placeholder="Qty" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-6">
                        <VField>
                            <VLabel>Harga</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.hargaSatuan" placeholder="Harga" disabled
                                    class="is-rounded" />
                                <!-- <VCurrency disabled v-model.lazy="item.hargaSatuan" class="is-rounded"
                                            :options="{ currency: 'IDR' }" placeholder="Harga" /> -->
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VLabel>Harga Diskon</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.hargadiskon" placeholder="Harga Diskon"
                                    class="is-rounded" />
                                <!-- <VCurrency v-model.lazy="item.hargadiskon" class="is-rounded"
                                            :options="{ currency: 'IDR' }" placeholder="Harga Diskon" /> -->
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VLabel>Diskon (%)</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="number" v-model="item.persenDiskon" placeholder="Diskon (%)"
                                    class="is-rounded" @input="changeDikson($event.target.value)" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <VLabel>Total</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.total" placeholder="Total" class="is-rounded" disabled />
                                <!-- <VCurrency disabled v-model.lazy="item.total" class="is-rounded"
                                            :options="{ currency: 'IDR' }" placeholder="Total" /> -->
                            </VControl>
                        </VField>
                    </div>
                </div>
            </form>
        </template>
        <template #action>
            <VButton v-if="item.no" icon="feather:plus" @click="add(item)" :loading="isLoading" color="primary" raised>
                Update</VButton>
            <VButton v-else icon="feather:plus" @click="add(item)" :loading="isLoading" color="primary" raised>Tambah
            </VButton>
        </template>
    </VModal>
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
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import Checkbox from 'primevue/checkbox';
import PrimeVue from 'primevue/config';
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import AutoComplete from 'primevue/autocomplete';
import { useCurrencyInput } from 'vue-currency-input'
import moment from 'moment'
import { popperGenerator } from '@popperjs/core'
import { objectMethod } from '@babel/types'
const TITLE_PAGE = 'Obat Alkes'
useHead({
    title: `Form Penjualan Obat Bebas - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let NOREC_ORDER: any = useRoute().query.norec_order as string
let NOREC_RESEP: any = useRoute().query.norec as string
let item: any = reactive({
    header: {},
    totalAll: 0,
    jumlah: 0,
    persenDiskon: 0,
    hargadiskon: 0,
    tglresep: new Date(),
    rke: 1,
    resep: '-',
})
const modalInput = ref(false)
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const confirm = useConfirm()
const isStuck = computed(() => {
    return y.value > 30
})
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
const dataSource: any = ref([])
const listRiwayat = ref([])
const data2: any = ref([])
const dataOK: any = ref([])
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const isLoading: any = ref(false)
const isSimpan: any = ref(false)
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const dataProdukDetail: any = ref([])
const disabledRuangan: any = ref(false)
const btnSaveandUpdate: any = ref(false)
const dataGridKronis: any = ref([])
const dataSelected: any = ref({})
const isPemakaianObatAlkes: any = ref(false)
const disTanggal: any = ref(false)
const checkResepPulang: any = ref(false)
const isEdit: any = ref(false)
const showRacikanDose: any = ref(false)
const renderLoader: any = ref(false)
const isLoadEdit: any = ref(false)

const fetchProduk = async (filter: any) => {

    const response = await useApi().get(
        `/laboratorium/load-pendukung?namaproduk=${filter.query}&limit=10`)
    d_produk.value = response

}
const fetchPasien = async (nocm: any) => {
    renderLoader.value = true
    await useApi().get(`/farmasi/get-pasien?nocm=${nocm}`).then((response) => {
        if (response) {
            item.namalengkap = response.namapasien
            item.notlp = response.notelepon ? response.notelepon : '-'
            item.tgllahir = response.tgllahir
            item.alamatlengkap = response.alamatlengkap
        } else {
            useToaster().error('Pasien Tidak Ditemukan')
        }
        renderLoader.value = false
    })
}

const loadDrop = async () => {
    isLoadEdit.value = true
    const response = await useApi().get(`/farmasi/input-resep-cbo`)
    d_aturanPakai.value = response.signa.map((e: any) => { return { aturanpakai: e.signa, id: e.id } })
    d_kemasan.value = response.jeniskemasan
    // d_produk.value = response.produk.map((e: any) => { return { label: e.namaproduk, value: e } })
    d_ruangan.value = response.ruanganFarmasi.map((e: any) => { return { label: e.namaruangan, value: e } })
    d_penulisResep.value = response.penulisresep.map((e: any) => { return { label: e.namalengkap, value: e } })
    d_jenisRacikan.value = response.jenisracikan.map((e: any) => { return { label: e.jenisracikan, value: e } })
    d_route.value = response.route.map((e: any) => { return { label: e.name, value: e } })
    d_satuanResep.value = response.satuanresep.map((e: any) => { return { label: e.satuanresep, value: e } })
    d_asalProduk.value = response.asalproduk.map((e: any) => { return { label: e.asalproduk, value: e } })
    // item.aturanPakai = d_aturanPakai.value[1]
    item.jenisKemasan = response.jeniskemasan[1]
    item.tarifadminresep = response.tarifadminresep ? response.tarifadminresep : 0

    if (NOREC_RESEP) {
        await loadEdit()
    }
    isLoadEdit.value = false
}

const loadEdit = async () => {

    isLoadEdit.value = true
    await useApi().get(`farmasi/get-detail-pasien?norecResep=${NOREC_RESEP}`).then((response) => {
        let dataPasien = response.detailresep
        item.nocm = dataPasien.nocm ? dataPasien.nocm : ''
        item.ceknomr = dataPasien.nocm ? true : false
        item.namalengkap = dataPasien.nama
        item.tgllahir = dataPasien.tgllahir
        item.notlp = dataPasien.notlp
        item.alamatlengkap = dataPasien.alamat
        item.noresep = dataPasien.nostruk
        item.tglresep = dataPasien.tglresep
        item.resultTotal = dataPasien.totalharusdibayar
        data2.value = response.pelayananPasien
        dataSource.value = response.pelayananPasien
        d_penulisResep.value.forEach((element: any) => {
            if (element.value.id == dataPasien.pgid) {
                item.penulisresep = element
                return
            }
        });
        d_ruangan.value.forEach((element: any) => {
            if (element.value.id == dataPasien.ruid) {
                item.ruangan = element
                return
            }
        });

        isLoadEdit.value = false
    })
    changeDisabledRuangan()
}

const editPopUp = (e: any) => {
    modalInput.value = true
    item.no = e.no
    item.rke = e.rke
    item.jumlahxmakan = e.jmlxmakan
    item.nilaiKonversi = e.nilaikonversi
    item.route = e.routefk
    item.persenDiskon = e.persendiscount
    item.KeteranganPakai = e.keterangan
    d_kemasan.value.forEach((element: any) => {
        if (element.id == e.jeniskemasanfk) {
            item.jenisKemasan = element
            return
        }
    });
    d_jenisRacikan.value.forEach((element: any) => {
        if (element.value.id == e.jenisobatfk) {
            item.jenisRacikan = element
            return
        }
    });
    d_aturanPakai.value.forEach((element: any) => {
        if (element.id == e.aturanpakaifk) {
            item.aturanPakai = element
            return
        }
    });

    d_produk.value.forEach((element: any) => {
        if (element.id == e.produkfk) {
            item.produk = element
            return
        }
    });
    d_route.value.forEach((element: any) => {
        if (element.value.id == e.routefk) {
            item.route = element
            return
        }
    });
    d_tglKadaluarsa.value.forEach((element: any) => {
        if (element.value.tglkadaluarsa == e.tglkadaluarsa) {
            item.tglKadaluarsa = element
            return
        }
    });

    d_satuanResep.value.forEach((element: any) => {
        if (element.value.id == e.satuanresepfk) {
            item.satuanresep = element
        }
    })

    tarifJasa.value = e.jasa
    dataSelected.value = e
    GETKONVERSI()
}

const inputPopUp = () => {
    if (!item.ruangan) {
        useToaster().error('Ruangan harus di pilih')
        return
    }
    if (NOREC_RESEP) {
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
                    item.rke = element.rke + 1
                }
            }
        });
    }
    clearInput()
    modalInput.value = true

}

const add = (e: any) => {
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

    let jmlbulat = 0;
    let jml = 0;

    jmlbulat = item.jumlahbulat;
    jml = item.jumlah;

    var data: any = {};
    if (item.no) {
        dataSource.value.forEach((element: any, i: any) => {
            if (element.no == item.no) {
                data.no = element.no,
                    data.generik = null,
                    data.hargajual = String(item.hargaSatuan),
                    data.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.value.id : null,
                    data.jenisobat = item.jenisRacikan ? item.jenisRacikan.label : null,
                    data.jmlxmakan = item.jumlahxmakan,
                    data.stock = String(item.stok),
                    data.harganetto = String(item.hargaNetto),
                    data.nostrukterimafk = norecTerima.value,
                    data.norec_spd = norecSPD.value,
                    data.ruanganfk = item.ruangan.value.id,
                    data.rke = item.rke,
                    data.jeniskemasanfk = item.jenisKemasan.id,
                    data.jeniskemasan = item.jenisKemasan.jeniskemasan,
                    data.aturanpakaifk = item.aturanPakai.id,
                    data.aturanpakai = item.aturanPakai.aturanpakai,
                    data.routefk = item.route ? item.route.value.id : null,
                    data.route = item.route ? item.route.value.name : null,
                    data.asalprodukfk = item.asal.id,
                    data.asalproduk = item.asal.asalproduk,
                    data.produkfk = item.produk.id,
                    data.namaproduk = item.produk.namaproduk,
                    data.nilaikonversi = item.nilaiKonversi,
                    data.satuanstandarfk = item.satuan.value.ssid,
                    data.satuanstandar = item.satuan.value.satuanstandar,
                    data.satuanviewfk = item.satuan.value.ssid,
                    data.satuanview = item.satuan.value.satuanstandar,
                    data.jmlstok = String(item.stok),
                    data.jumlah = jmlbulat,//item.jumlahbulat,
                    data.jumlahobat = jml,//item.jumlah,
                    data.dosis = dosis,
                    data.hargasatuan = String(item.hargaSatuan),
                    data.hargadiscount = String(item.hargadiskon),
                    data.persendiscount = item.persenDiskon ? item.persenDiskon : 0,
                    data.total = item.total,
                    data.jmldosis = String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan),
                    data.jasa = tarifJasa.value,
                    data.keterangan = item.KeteranganPakai ? item.KeteranganPakai : null,
                    data.satuanresepfk = item.satuanresep ? item.satuanresep.value.id : null,
                    data.satuanresep = item.satuanresep ? item.satuanresep.value.satuanresep : null,
                    data.tglkadaluarsa = item.tglKadaluarsa ? item.tglKadaluarsa.label : null
                data2.value[i] = data
            }
        });

    } else {
        if (data2.length > 0) {
            var racikan = data2.value[data2.value.length - 1].jeniskemasan
            if (racikan == 'Non Racikan') {
                item.rke = data2.value[data2.value.length - 1].rke + 1
            }
        }
        data = {
            no: dataSource.value.length == 0 ? 1 : dataSource.value.length + 1,
            generik: null,
            hargajual: String(item.hargaSatuan),
            jenisobatfk: item.jenisRacikan ? item.jenisRacikan.value.id : null,
            jenisobat: item.jenisRacikan ? item.jenisRacikan.label : null,
            jmlxmakan: item.jumlahxmakan,
            stock: String(item.stok),
            harganetto: String(item.hargaNetto),
            nostrukterimafk: norecTerima.value,
            norec_spd: norecSPD.value,
            ruanganfk: item.ruangan.value.id,
            rke: item.rke,
            jeniskemasanfk: item.jenisKemasan.id,
            jeniskemasan: item.jenisKemasan.jeniskemasan,
            aturanpakaifk: item.aturanPakai.id,
            aturanpakai: item.aturanPakai.aturanpakai,
            // iskronis: item.checkisKronis,
            routefk: item.route ? item.route.value.id : null,
            route: item.route ? item.route.value.name : null,
            asalprodukfk: item.asal.id,
            asalproduk: item.asal.asalproduk,
            produkfk: item.produk.id,
            namaproduk: item.produk.namaproduk,
            nilaikonversi: item.nilaiKonversi,
            satuanstandarfk: item.satuan.value.ssid,
            satuanstandar: item.satuan.value.satuanstandar,
            satuanviewfk: item.satuan.value.ssid,
            satuanview: item.satuan.value.satuanstandar,
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
            satuanresepfk: item.satuanresep ? item.satuanresep.value.id : null,
            satuanresep: item.satuanresep ? item.satuanresep.value.satuanresep : null,
            tglkadaluarsa: item.tglKadaluarsa ? item.tglKadaluarsa.label : null,
            tglregistrasi: moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
        }
        data2.value.push(data)

    }
    dataSource.value = data2.value
    changeDisabledRuangan()
    if (item.jenisKemasan.jeniskemasan != 'Racikan') {
        item.rke = parseFloat(item.rke) + 1
    }

    countTotal()
    clearInput()
}

const changeDisabledRuangan = () => {
    if (dataSource.value.length > 0) {
        disabledRuangan.value = true
    } else {
        disabledRuangan.value = false
    }
}

const countTotal = () => {
    let total = 0
    for (let x = 0; x < data2.value.length; x++) {
        const element = data2.value[x];
        total = total + parseFloat(element.total)
    }
    item.resultTotal = total
}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            for (var i = dataSource.value.length - 1; i >= 0; i--) {
                if (dataSource.value[i].no == e.no) {
                    dataSource.value.splice(i, 1);
                }
            }
            countTotal()
            clearInput()
            changeDisabledRuangan()
        },
        reject: () => { },
    })
}

const save = async () => {
    if (!item.penulisresep) {
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
    btnSaveandUpdate.value = true
    var objSave =
    {
        'strukresep': {
            'alamat': item.alamatlengkap ? item.alamatlengkap : '',
            'karyawan': '',
            'keteranganlainnya': 'Penjualan Obat Bebas',
            'namapasien': item.namalengkap,
            'noTelepon': item.notlp,
            'nocm': item.nocm ? item.nocm : '',
            'nostruk': NOREC_RESEP ? NOREC_RESEP : '',
            'penulisresepfk': item.penulisresep ? item.penulisresep.value.id : '',
            'ruanganfk': item.ruangan ? item.ruangan.value.id : '',
            'tglresep': moment(item.tglresep).format('YYYY-MM-DD HH:mm:ss'),
            'tglLahir': moment(item.tgllahir).format('YYYY-MM-DD HH:mm:ss'),
            'total': item.resultTotal,
        },
        'details': data2.value
    }
    isSimpan.value = true
    await useApi().post(
        `/farmasi/save-input-non-layanan-obat`, objSave).then((response: any) => {
            btnSaveandUpdate.value = false
            window.history.back();
        }, (error) => {
            btnSaveandUpdate.value = false
        })


}
const changeProduk = (e: any) => {
    if (e != null) {
        GETKONVERSI()
    }
}
const changeExpired = (e: any) => {
    setNorecSPD()
}
const GETKONVERSI = () => {
    if (item.produk.konversisatuan.length == 0) {
        item.nilaiKonversi = 1
        d_satuan.value = [
            {
                label: item.produk.satuanstandar, value:
                    { ssid: item.produk.ssid, satuanstandar: item.produk.satuanstandar }
            }]
    } else {
        d_satuan.value = item.produk.konversisatuan.map((e: any) => {
            return { label: e.satuanstandar, value: e }
        })
    }
    item.nilaiKonversi = 1
    dataProdukDetail.value = []
    useApi().get(
        '/farmasi/get-produkdetail?produkfk=' + item.produk.id +
        '&ruanganfk=' + item.ruangan.value.id).then(function (response: any) {
            dataProdukDetail.value = response.detail
            if (response.detail.length > 0) {
                d_satuan.value.forEach((element: any) => {
                    if (element.value.ssid == item.produk.ssid) {
                        item.nilaiKonversi = element.value.nilaikonversi
                        item.satuan = element
                        return
                    }
                });
                d_tglKadaluarsa.value = response.detail.map((e: any) => {
                    return { label: e.tglkadaluarsa, value: e }
                })
                item.stok = response.jmlstok / item.nilaiKonversi
                if (response.kekuatan == undefined || response.kekuatan == 0) {
                    response.kekuatan = 1
                }
                item.kekuatan = response.kekuatan
                item.sediaan = response.sediaan
                d_tglKadaluarsa.value.forEach((element: any) => {
                    if (element.value.tglkadaluarsa == response.detail[0].tglkadaluarsa) {
                        item.tglKadaluarsa = element
                        return
                    }
                });
                item.jumlah = 1
                setNorecSPD()
                if (dataSelected.value.no != undefined) {
                    item.jumlah = dataSelected.value.jumlahobat
                    item.jumlahobat = dataSelected.value.jumlahobat
                    item.dosis = dataSelected.value.dosis
                    item.jumlahxmakan = (parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan)
                    item.nilaiKonversi = dataSelected.value.nilaikonversi
                    d_satuan.value.forEach((element: any) => {
                        if (element.value.ssid == dataSelected.value.satuanstandarfk) {
                            item.satuan = element
                            return
                        }
                    });
                    item.hargaSatuan = dataSelected.value.hargasatuan
                    item.hargadiskon = dataSelected.value.hargadiscount
                    item.hargaNetto = dataSelected.value.harganetto
                    item.total = dataSelected.value.total
                }
            } else {
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
            }
        });

}
const clearInput = () => {
    delete item.produk
    delete item.asal
    delete item.satuan
    delete item.no
    delete item.satuanresep
    delete item.route
    delete item.aturanPakai
    delete item.KeteranganPakai
    delete dataSelected.value
    delete item.tglKadaluarsa
    delete item.jenisRacikan
    delete item.jeniskemasan
    delete item.dosis
    item.qty = 1
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
        let tglExpiredPilih
        if (item.tglKadaluarsa.label != undefined) {
            tglExpiredPilih = moment(item.tglKadaluarsa.label).format('YYYY-MM-DD HH:mm:ss')
        }
        if ((parseFloat(item.jumlah) * parseFloat(item.nilaiKonversi)) <= parseFloat(element.qtyproduk) && tglExpiredPilih == element.tglkadaluarsa) {
            hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi))
            hrgsdk.value = parseFloat(element.hargadiscount) * parseFloat(item.nilaiKonversi)
            item.hargaSatuan = hrg1.value
            item.hargaNetto = Math.round(parseFloat(element.harganetto) * parseFloat(item.nilaiKonversi))
            if (item.hargadiskon == 0) {
                item.hargadiskon = hrgsdk.value
            } else {
                hrgsdk.value = item.hargadiskon
            }
            item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
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
                ruanganfk: item.ruangan.value.id
            }

            isSimpan.value = true;
            useApi().post(
                '/farmasi/save-stock-merger', objSave).then(function (response: any) {
                    // clearInput()
                    GETKONVERSI()
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
    delete item.total
    item.nilaiKonversi = item.satuan.value.nilaikonversi
}

const back = () => {
    window.history.back()
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
                    item.rke = element.rke + 1
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
</style>
    