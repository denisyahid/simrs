<template>
    <ConfirmDialog />
    <div class="form-layout is-stacked">
        <div class="form-outer" style="margin-top: 15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Form Retur Obat Bebas</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="back()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" @click="saveRetur()" raised :loading="isLoadBtn"
                                icon="feather:save">
                                Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-body" v-if="isLoadingRetur">
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
            <div class="form-body" v-else>
                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-2">
                            <VField label="No MR" v-if="item.ceknomr == true">
                                <VControl icon="feather:search">
                                    <VInput type="text" v-model="item.nocm" v-on:keyup.enter="fetchPasien(item.nocm)"
                                        placeholder="No MR" class="is-rounded" />
                                </VControl>
                            </VField>
                            <VField label="No MR" v-else>
                                <VControl icon="feather:search">
                                    <VInput type="text" v-model="item.nocm" placeholder="No MR" class="is-rounded"
                                        disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 p-0" style="margin-top: 3rem; margin-left:px;margin-right: -94px;">
                            <VField>
                                <VControl raw subcontrol>
                                    <Checkbox v-model="item.ceknomr" :binary="true" disabled />
                                    <label class="ml-2 ingredient1">Ada</label>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VLabel class="required-field">Nama Legkap</VLabel>
                                <VPlaceloadWrap v-if="renderLoader">
                                    <VPlaceload height="30px" class="mx-2" />
                                </VPlaceloadWrap>
                                <VControl v-else icon="feather:bookmark">
                                    <VInput type="text" v-model="item.namalengkap" placeholder="Nama Lengkap"
                                        class="is-rounded" disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VDatePicker v-model="item.tgllahir" color="green" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VLabel class="required-field">Tanggal Lahir</VLabel>
                                        <VPlaceloadWrap v-if="renderLoader">
                                            <VPlaceload height="30px" class="mx-2" />
                                        </VPlaceloadWrap>
                                        <VControl v-else icon="feather:calendar">
                                            <VInput type="text" placeholder="Select a date" class="is-rounded"
                                                :value="inputValue" v-on="inputEvents" disabled />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3">
                            <VField label="No Telepon">
                                <VPlaceloadWrap v-if="renderLoader">
                                    <VPlaceload height="30px" class="mx-2" />
                                </VPlaceloadWrap>
                                <VControl v-else icon="feather:bookmark">
                                    <VInput type="text" v-model="item.notlp" disabled placeholder="No Telepon"
                                        class="is-rounded" />
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
                            <VTextarea v-model="item.alamatlengkap" rows="3" placeholder="Alamat Lengkap" disabled>
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-2">
                            <VDatePicker v-model="item.tglresep" color="green" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VLabel>Tanggal Resep</VLabel>
                                        <VControl icon="feather:calendar">
                                            <VInput type="text" placeholder="Select a date" class="is-rounded"
                                                :value="inputValue" v-on="inputEvents" disabled />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3">
                            <VField label="No Resep">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.noresep" placeholder="No Resep" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="required-field">Penulis Resep</VLabel>
                                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.penulisresep" :options="d_penulisResep" optionLabel="label"
                                        class="is-rounded" disable placeholder="Pilih data" style="width: 100%;"
                                        :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="required-field">Ruangan</VLabel>
                                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.ruangan" disabled :options="d_ruangan" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel class="required-field">keterangan Retur</VLabel>
                        <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="item.keteranganretur" placeholder="Keterangan Retur"
                                class="is-rounded" />
                        </VControl>
                    </VField>
                </div>
            </div>

        </div>
        <div class="column is-12 p-0 mt-5">
            <VCard>
                <DataTable :value="dataSource" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                    class="p-datatable-sm"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                    <Column field="no" header="No"></Column>
                    <Column field="rke" header="R/ke"></Column>
                    <Column field="namaproduk" header="Produk"></Column>
                    <Column field="jeniskemasan" header="Kemasan"></Column>
                    <Column field="jmldosis" header="Jml/Dss/kkuatan"></Column>
                    <Column field="hargasatuan" header="Harga Satuan"></Column>
                    <Column field="satuanstandar" header="Satuan"></Column>
                    <Column field="jumlah" header="Qty"></Column>
                    <Column field="jmlretur" header="Retur"></Column>
                    <Column field="totalChange" header="Total"></Column>
                    <Column :exportable="false" header="#" style="text-align: center">
                        <template #body="slotProps">
                            <VIconButton color="primary" v-tooltip.primary="'Retur'" @click="returPopUp(slotProps.data)"
                                circle icon="fas fa-undo" />
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

    <VModal :open="modalRetur" title="Retur Resep" size="medium" actions="right" @close="modalRetur = false, clear()">
        <template #content>
            <form class="modal-form">
                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-3">
                            <VField label="R/Ke">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.rke" placeholder="No Resep" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-9">
                            <VField label="Asal">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.asal" placeholder="No Resep" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-8">
                            <VField label="Produk">
                                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.produk" :options="d_produk" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Satuan">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.satuanstandar" placeholder="Satuan Standar" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-4">
                            <VField label="Konversi">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.konversi" placeholder="No Resep" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Qty Produk">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.jumlah" placeholder="Produk" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VLabel class="required-field">Qty Retur</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.qtyretur" placeholder="Qty Retur"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </form>
            <div class="column is-12">
                <div class="content">
                    <div class="is-divider" data-content="Total Keseluruhan" />
                </div>
            </div>

            <div class="column is-6 p-0" style="margin-left: auto;">
                <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status" color="danger">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span class="ml-1">TOTAL</span>
                    </div>
                    <small class="text-bold-custom h-100">{{ H.formatRp(item.totalTagihan, 'Rp.') }}</small>
                </VCardCustom>
            </div>
        </template>
        <template #action>
            <VButton icon="feather:plus" :loading="isLoading" :disabled="isDisabled" @click="addRetur(item)" color="primary"
                raised>Retur</VButton>
        </template>
    </VModal>
</template>
    
<script setup lang="ts">

import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
    ref,
    computed,
    watch,
    reactive,
} from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import Checkbox from 'primevue/checkbox';
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dropdown from 'primevue/dropdown';
import moment from 'moment'
import { elements } from '/@src/data/landing/components'
const TITLE_PAGE = 'Obat Alkes'
useHead({
    title: `Form Penjualan Obat Bebas - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let NOREC_RESEP: any = useRoute().query.norec as string
let item: any = reactive({})
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
const data2: any = ref([])
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const isLoading: any = ref(false)
const isLoadBtn: any = ref(false)
const isLoadingRetur: any = ref(true)
const modalRetur: any = ref(false)
const isDisabled: any = ref(false)
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const op = ref();
const dataProdukDetail: any = ref([])
const dataSelected: any = ref({})
const renderLoader: any = ref(false)


const fetchPasien = async (nocm: any) => {
    renderLoader.value = true
    await useApi().get(`/farmasi/get-pasien?nocm=${nocm}`).then((response) => {
        item.namalengkap = response.namapasien
        item.notlp = response.notelepon ? response.notelepon : '-'
        item.tgllahir = response.tgllahir
        item.alamatlengkap = response.alamatlengkap
        renderLoader.value = false
    })
}

const loadDrop = async () => {
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

    loadRetur()

}

const loadRetur = async () => {
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
        response.pelayananPasien.forEach((element: any) => {
            element.totalChange = element.total
        });
        dataSource.value = response.pelayananPasien
        data2.value = response.pelayananPasien
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
    })
    countTotal()
    isLoadingRetur.value = false
}


const fetchProduk = async (filter: any) => {
    const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&ruanganfk=${item.ruangan.value.id}&limit=10`)
    d_produk.value = response.map((e:any)=>{
        return {label : e.productname , value : e.id}
    })
}

const returPopUp = async (e: any) => {
    console.log(e)
    await fetchProduk({query : e.namaproduk})
    // console.log(d_produk.value[0])
    // item.produk = d_produk.value[0]
    item.produk = d_produk.value[0]
    // d_produk.value.forEach((element: any) => {
    //     console.log(element)
    //     // if (element.id == e.produkfk) {
    //     //     item.produk = element
    //     //     return
    //     // }
    // })
    item.no = e.no
    item.rke = e.rke
    item.hargajual = e.hargajual
    item.asal = e.asalproduk
    item.satuanstandar = e.satuanstandar
    item.konversi = e.nilaikonversi
    item.jumlah = e.jumlah
    item.qtyretur = e.jmlretur
    item.totalTagihan = e.totalChange
    item.hargadiscount = e.hargadiscount
    item.hargaAwal = e.total ? e.total : parseFloat(e.jumlah) * parseFloat(e.hargajual) - parseFloat(e.hargadiscount)
    modalRetur.value = true
}

const saveRetur = async () => {
    let cekAyaRetur = 0
    dataSource.value.forEach((element: any) => {
        if (!element.jmlretur) {
            element.jmlretur = 0
        }
        let aya = element.jmlretur ? element.jmlretur : 0
        cekAyaRetur = cekAyaRetur + parseInt(aya)
    });

    if (!item.keteranganretur) {
        H.alert('error', 'Keterangan retur tidak boleh kosong')
        return
    }

    // if (!cekAyaRetur) {
    //     H.alert('error', 'Jumlah retur tidak oleh kosong')
    //     return
    // }
    
    dataSource.value.forEach((elements: any) => {
        if (!elements.jmlretur) {
            elements.jmlretur = 0
        }
    })

    isLoadBtn.value = true
   
    const objSave = {
        strukresep: {
            alamat: item.alamatlengkap,
            alasan: item.keteranganretur,
            karyawan: '',
            norec_sp: NOREC_RESEP,
            keteranganlainnya: "Penjualan Obat Bebas",
            namapasien: item.namalengkap,
            noTelepon: item.notlp,
            nocm: item.nocm,
            noresep: item.noresep,
            penulisresepfk: item.penulisresep.value.id,
            retur: 'RETUR',
            ruanganfk: item.ruangan.value.id,
            tglLahir: H.formatDate(item.tgllahir, 'YYYY-MM-DD'),
            tglresep: H.formatDate(item.tglresep, 'YYYY-MM-DD HH:mm:ss'),
            totalharusdibayar: item.resultTotal,
        },
        details: dataSource.value
    }

    await useApi().post('farmasi/save-retur-resep-bebas', objSave).then((response: any) => {
        console.log(response)
    }).catch((err) => {
        console.log(err)
    })
    isLoadBtn.value = false
}

const addRetur = (e: any) => {
    let data: any = {}
    if (!e.qtyretur) {
        H.alert('error', 'Nilai retur tidak boleh kosong');
        return
    }
    dataSource.value.forEach((element: any, i: any) => {
        if (e.no == element.no) {
            data.no = element.no
            data.asalproduk = element.asalproduk
            data.asalprodukfk = element.asalprodukfk
            data.aturanpakai = element.aturanpakai
            data.dosis = element.dosis
            data.generik = element.generik
            data.hargadiscount = element.hargadiscount
            data.hargajual = element.hargajual
            data.harganetto = element.harganetto
            data.hargasatuan = element.hargasatuan
            data.jasa = element.jasa
            data.jeniskemasan = element.jeniskemasan
            data.jeniskemasanfk = element.jeniskemasanfk
            data.jenisobatfk = element.jenisobatfk ? element.jenisobatfk : null
            data.jmldosis = element.jmldosis
            data.jmlretur = e.qtyretur ? e.qtyretur : 0
            data.jmlstok = element.jmlstok
            data.jumlah = element.jumlah
            data.kelasfk = element.kelasfk
            data.namaproduk = element.namaproduk
            data.nilaikonversi = element.nilaikonversi
            data.noregistrasifk = element.noregistrasifk
            data.nostrukterimafk = element.nostrukterimafk
            data.produkfk = element.produkfk
            data.rke = element.rke
            data.routefk = element.routefk
            data.ruanganfk = element.ruanganfk
            data.satuanstandar = element.satuanstandar
            data.satuanstandarfk = element.satuanstandarfk
            data.satuanview = element.satuanview
            data.satuanviewfk = element.satuanviewfk
            data.tglkadaluarsa = element.tglkadaluarsa
            data.stock = element.stock
            data.tglregistrasi = element.tglregistrasi
            data.totalChange = e.totalTagihan
            dataSource.value[i] = data
        }
    });
    countTotal()
    modalRetur.value = false
}

const clear = () => {
    delete item.rke
    delete item.asal
    delete item.produk
    delete item.satuanstandar
    delete item.konversi
    delete item.jumlah
    delete item.qtyretur
    delete item.totalTagihan
}

const countTotal = () => {
    let total = 0
    for (let x = 0; x < dataSource.value.length; x++) {
        const element = dataSource.value[x]
        total = total + parseFloat(element.totalChange)
    }
    item.resultTotal = total
}

const back = () => {
    window.history.back()
}


watch(
    () => item.qtyretur,
    () => {
        if (item.qtyretur > item.jumlah) {
            H.alert('error', 'Jumlah retur melebihi batas')
            item.totalTagihan = item.hargaAwal
            isDisabled.value = true
            return
        }
        if (item.qtyretur) {
            let hargaResult = parseFloat(item.qtyretur) * (parseFloat(item.hargajual) - parseFloat(item.hargadiscount))
            let resultTotal = parseFloat(item.hargaAwal) - hargaResult
            if (isNaN(resultTotal)) {
                item.totalTagihan = item.hargaAwal
                isDisabled.value = true
            } else {
                item.totalTagihan = resultTotal
                isDisabled.value = false
            }
        } else {
            item.totalTagihan = item.hargaAwal
            isDisabled.value = true
        }
    }
)

loadDrop()

</script>
    
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
// @import '/@src/scss/custom/config';
</style>
    