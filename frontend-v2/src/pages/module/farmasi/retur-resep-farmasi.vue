<!-- <route lang="yaml">
    meta:
      requiresAuth: true
 </route> -->
<template>
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
                                <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined>
                                    Cancel
                                </VButton>
                                <VButton
                                    icon="feather:save"
                                    type="submit"
                                    :disabled="isDisabledSave"
                                    color="primary"
                                    @click="handleSaveClick"
                                    raised
                                    :loading="isSimpan"
                                >
                                    Save
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body">

                    <div class="columns is-multiline">
                        <div class="column is-3" v-if="!item.header.nocm">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Resep</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-6" v-if="!item.header.nocm">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Pasien</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-3" v-if="!item.header.nocm">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Pengkajian</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-12" v-if="!item.header.nocm">
                            <VCard>
                                <h3 class="title is-5 mb-2">Data Resep</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <div class="flex-table-item grid-item mb-4">
                                            <div class="flex-list-inner mb-4">
                                                <div class="flex-table-item grid-item mb-4" v-for="key in 1" :key="key">
                                                    <VFlexTableCell>
                                                        <VPlaceload width="10%" class="mx-1" height="20px" />
                                                        <VPlaceload width="10%" class="mx-1 is-pulled-right"
                                                            height="20px" />
                                                    </VFlexTableCell>
                                                    <VFlexTableCell>
                                                        <VPlaceload width="100%" height="30px" class="mx-1 mt-2" />
                                                    </VFlexTableCell>
                                                    <VFlexTableCell>
                                                        <VPlaceload width="100%" height="30px" class="mx-1 mt-2" />
                                                    </VFlexTableCell>
                                                    <VFlexTableCell>
                                                        <VPlaceload width="100%" height="30px" class="mx-1 mt-2" />
                                                    </VFlexTableCell>
                                                    <VFlexTableCell :column="{ align: 'end' }">
                                                        <VPlaceload width="10%" height="20px" class="mx-1 mt-2" />
                                                    </VFlexTableCell>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                    </div>
                    <div class="columns is-multiline" v-if="item.header.nocm">
                        <div class="column is-3">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Resep</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VLabelText>Ruang Rawat</VLabelText>
                                            <VLabel>{{ item.header.ruangrawat }} </VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabelText>Nomor Resep</VLabelText>
                                            <VLabel>{{ item.resep ? item.resep : '-' }}</VLabel>
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-6">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Pasien</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>No RM</VLabelText>
                                            <VLabel>{{ item.header.nocm }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>Nama Pasien</VLabelText>
                                            <VLabel>{{ item.header.namapasien }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>Jenis Kelamin</VLabelText>
                                            <VLabel>{{ item.header.jeniskelamin }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>Umur</VLabelText>
                                            <VLabel>{{ item.header.umur }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>Jenis Pasien</VLabelText>
                                            <VLabel>{{ item.header.kelompokpasien }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>Penjamin</VLabelText>
                                            <VLabel>{{ item.header.namarekanan }}</VLabel>
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-3">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Pengkajian</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VLabelText>Berat Badan</VLabelText>
                                            <VLabel>{{ item.header.beratbadan }} </VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabelText>Tinggi Badan</VLabelText>
                                            <VLabel>{{ item.header.tinggibadan }} </VLabel>
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VCard>
                                        <h3 class="title is-5 mb-2">Data Resep</h3>
                                        <div class="columns is-multiline pt-3">
                                            <div class="column is-4 pb-0">
                                                <VDatePicker v-model="item.tglAwal" color="green" trim-weeks
                                                    mode="dateTime">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VLabelText class="item">Tgl Resep</VLabelText>
                                                            <VControl icon="feather:calendar">
                                                                <VInput type="text" placeholder="Select a date"
                                                                    class="is-rounded" :value="inputValue"
                                                                    v-on="inputEvents" disabled />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </div>
                                            <div class="column is-4 pb-0">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText class="item">Penulis Resep</VLabelText>
                                                    <VControl icon="feather:search" class="prime-auto-select">
                                                        <Dropdown v-model="item.penulisResep" :options="d_penulisResep"
                                                            :optionLabel="'label'" class="is-rounded" disabled
                                                            placeholder="Pilih data" style="width: 100%; font-weight:bold" showClear
                                                            :filter="true" />
                                                    </VControl>

                                                </VField>
                                            </div>
                                            <div class="column is-4 pb-0">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText class="item">Ruangan</VLabelText>
                                                    <VControl icon="feather:search" class="prime-auto-select">
                                                        <Dropdown v-model="item.ruangan" :options="d_ruangan"
                                                            :optionLabel="'label'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;font-weight:bold" showClear
                                                            :filter="true" />
                                                    </VControl>

                                                </VField>
                                            </div>
                                        </div>
                                        <div class="column pt-0">
                                            <VField>
                                                <VLabel class="item required-field">keterangan Retur</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.keteranganretur"
                                                        placeholder="Keterangan Retur" class="is-rounded" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </VCard>
                                </div>
                                <div class="column is-12">
                                    <VCard>
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <DataTable :value="dataSource" :paginator="true" :rows="10"
                                                    :rowsPerPageOptions="[5, 10, 25]"
                                                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                                    <Column header="Pilih Semua" :style="{width: '3rem', textAlign: 'center', verticalAlign: 'top'}">
                                                        <template #header>
                                                            <input type="checkbox" v-model="allSelected" @change="toggleAll" style="transform: scale(1.2); vertical-align: middle;" />
                                                        </template>
                                                        <template #body="slotProps">
                                                            <input type="checkbox" v-model="slotProps.data.isSelected" @change="onRowSelectChange(slotProps.data)" style="transform: scale(1.2); vertical-align: middle;" />
                                                        </template>
                                                    </Column>

                                                    <Column :exportable="false" header="#" style="width:8rem">
                                                        <template #body="slotProps">
                                                            <VIconButton @click="showModal(slotProps.data)" :loading="slotProps.data.btnLoading"
                                                                v-tooltip.bottom.center="'Retur'" color="primary" outlined
                                                                circle icon="fas fa-undo" />
                                                        </template>
                                                    </Column>

                                                    <Column field="no" header="No" />
                                                    <Column field="rke" header="R/Ke" />
                                                    <Column field="jeniskemasan" header="Kemasan" />
                                                    <!-- <Column field="jmldosis" header="Dosis"/> -->
                                                    <Column field="namaproduk" header="Produk" :sortable="true" />
                                                    <Column field="aturanpakai" header="Aturan Pakai" />
                                                    <Column field="satuanstandar" header="Satuan" />
                                                    <Column field="jumlah" header="Qty" />
                                                    <Column field="jmlretur" header="Retur" />
                                                    <Column field="hargasatuan" header="Harga">
                                                        <template #body="slotProps">
                                                            {{ formatRp(slotProps.data.hargasatuan, '') }}
                                                        </template>
                                                    </Column>
                                                    <Column field="hargadiscount" header="Diskon">
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
                                                        <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                                                    </template>
                                                    <template #paginatorend>
                                                        <Button type="button" icon="pi pi-cloud" class="p-button-text" />
                                                    </template>
                                                </DataTable>
                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                            </div>
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
                </div>
            </div>
        </div>

    </div>

    <VModal :open="modalRetur" title="Retur Resep" size="medium" actions="right" @close="modalRetur = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-3">
                            <VField>
                                <VLabel>R/K</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.rke" disabled class="is-rounded" style="font-weight:bold" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-9">
                            <VField>
                                 <VLabel>Asal Produk</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.asal" disabled class="is-rounded" style="font-weight:bold"/>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-8">
                            <VField class="is-rounded-select is-autocomplete-select">
                                 <VLabel>Produk</VLabel>
                                 <VControl icon="feather:search" class="prime-auto">
                                    <AutoComplete v-model="item.produk" :suggestions="d_produk" disabled
                                        @complete="fetchProduk($event)" :optionLabel="'namaproduk'" :dropdown="true"
                                        :minLength="3" class="is-rounded" :appendTo="'body'" style="font-weight:bold!important"
                                        :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                                        placeholder="ketik untuk mencari...">
                                    </AutoComplete>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VLabel>Satuan</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.satuanstandar" placeholder="Satuan Standar" disabled style="font-weight:bold"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-4">
                            <VField>
                                  <VLabel>Konversi</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" style="font-weight:bold" v-model="item.konversi" placeholder="No Resep" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                 <VLabel>Qty Produk</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" style="font-weight:bold" v-model="item.jumlah" placeholder="Produk" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VLabel class="required-field">Qty Retur</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number"  v-model="item.qtyretur" placeholder="Qty Retur"
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
            <VButton icon="feather:plus" :loading="isLoadBtnRet" :disabled="isDisabled" @click="subChangeRetur(item)"
                color="primary" raised>Retur</VButton>
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
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
const TITLE_PAGE = 'Obat Alkes'
useHead({
    title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let NOREC_PD = useRoute().query.norec_pd as string
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_APD = useRoute().query.norec_apd as string
let NOREC_ORDER: any = useRoute().query.norec_order as string
let NOREC_RESEP: any = useRoute().query.norec_resep as string
let item: any = reactive({
    header: {},
    totalAll: 0,
    jumlah: 0,
    persenDiskon: 0,
    hargadiskon: 0,
    tglAwal: new Date(),
    rke: 1,
    resep: '-',
})
const modalInput = ref(false)
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const d_penulisResep: any = ref([])
const d_ruangan: any = ref([])
const d_produk: any = ref([])
const dataSource: any = ref([])
const dataSelected: any = ref([])
const isSimpan: any = ref(false)
const allSelected = ref(false);
const modalRetur: any = ref(false)
const dataGridKronis: any = ref([])
const isLoadBtnRet: any = ref(false)
const isDisabled: any = ref(false)
const isDisabledSave: any = ref(false)
const handleSaveClick = () => {
    isSimpan.value = true; 
    isDisabledSave.value = true;
    saveRetur(item);
};
const loadData = async () => {
    await listCombo()
    await fetchDetailPasien()
    await dataResep()
}

const fetchDetailPasien = async () => {
    await useApi().get(`/farmasi/input-resep-header?norec_apd=${NOREC_APD}`).then((response: any) => {
        item.header = response.data

    })
}

const dataResep = async () => {
    await useApi().get(`/farmasi/input-resep-edit?norecResep=${NOREC_RESEP}`).then((response: any) => {
        let dataResep = response.detailresep
        let detailResep = response.pelayananPasien
        item.detailResep = dataResep
        item.tglAwal = dataResep.tglresep
        d_penulisResep.value.forEach((e: any) => {
            if (dataResep.pgid == e.value) {
                item.penulisResep = e
            }
        })
        d_ruangan.value.forEach((e: any) => {
            if (dataResep.id == e.value) {
                item.ruangan = e
            }
        });

        dataSource.value = detailResep
    })
    countTotal()
}

const listCombo = async () => {
    await useApi().get(`/farmasi/input-resep-cbo`).then((response: any) => {
        d_penulisResep.value = response.penulisresep.map((e: any) => {
            return { label: e.namalengkap, value: e.id }
        })

        d_ruangan.value = response.ruangan.map((e: any) => {
            return { label: e.namaruangan, value: e.id }
        })

        // d_produk.value = response.produk.map((e: any) => {
        //     return { label: e.namaproduk, value: e }
        // })
    })
}

const showModal = async (e: any) => {
     e.btnLoading = true
    console.log(e)
    await fetchProduk({ query: e.productname })
    d_produk.value.forEach((element: any) => {
        if (element.id == e.produkfk) {
            item.produk = element
        }
    })
    modalRetur.value = true
    dataSelected.value = e
    item.rke = e.rke
    item.asal = e.asalproduk
    item.satuanstandar = e.satuanstandar
    item.konversi = e.nilaikonversi
    item.jumlah = e.jumlah
    item.total = e.totalAwal ? e.totalAwal : e.total
    item.totalTagihan = e.total
    item.hargadiscount = e.hargadiscount
    item.hargajual = e.hargajual
    item.qtyretur = e.jmlretur
     e.btnLoading = false
}

const fetchProduk = async (filter: any) => {
    let isDonasi = item.checkisDonasi ? `&isdonasi=${item.checkisDonasi}` : ''
    const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&ruanganfk=${item.ruangan.value}${isDonasi}&limit=10`)
    d_produk.value = response
}

const subChangeRetur = (e: any) => {
    let element = dataSelected.value
    let data: any = {}
    dataSource.value.forEach((e: any, i: any) => {
        if (e.no == element.no) {
            data.no = element.no
            data.asalproduk = element.asalproduk
            data.asalprodukfk = element.asalprodukfk
            data.aturanpakai = element.aturanpakai
            data.aturanpakaifk = element.aturanpakaifk
            data.dosis = element.dosis
            data.generik = element.generik
            data.hargadiscount = element.hargadiscount
            data.hargajual = element.hargajual
            data.hargasatuan = element.hargasatuan
            data.harganetto = element.harganetto
            data.jasa = element.jasa
            data.jeniskemasan = element.jeniskemasan
            data.jeniskemasanfk = element.jeniskemasanfk
            data.jenisobatfk = element.jenisobatfk
            data.jenisracikanfk = element.jenisracikanfk
            data.jmldosis = element.jmldosis
            data.jmlstok = element.jmlstok
            data.jumlah = element.jumlah
            data.jumlahobat = element.jumlahobat
            data.kekuatan = element.kekuatan
            data.kelasfk = element.kelasfk
            data.keterangan = element.keterangan
            data.keteranganpakai = element.keteranganpakai
            data.namaproduk = element.namaproduk
            data.namaruangan = element.namaruangan
            data.objectruanganfk = element.objectruanganfk
            data.nilaikonversi = element.nilaikonversi
            data.norec_spd = element.norec_spd
            data.norecpp = element.norecpp
            data.noresep = element.noresep
            data.nostrukterimafk = element.nostrukterimafk
            data.noregistrasifk = element.noregistrasifk
            data.persendiscount = element.persendiscount
            data.produkfk = element.produkfk
            data.rke = element.rke
            data.route = element.route
            data.routefk = element.routefk
            data.ruidresep = element.ruidresep
            data.ruanganresep = element.ruanganresep
            data.satuanresep = element.satuanresep
            data.satuanresepfk = element.satuanresepfk
            data.satuanstandar = element.satuanstandar
            data.satuanstandarfk = element.satuanstandarfk
            data.satuanviewfk = element.satuanviewfk
            data.satuanview = element.satuanview
            data.stock = element.stock
            data.tglkadaluarsa = element.tglkadaluarsa
            data.tglresep = element.tglresep
            data.tglregistrasi = element.tglregistrasi
            data.jmlretur = item.qtyretur
            data.total = item.totalTagihan
            data.totalAwal = parseFloat(element.hargajual) * parseFloat(element.jumlah) - parseFloat(element.hargadiscount)

            dataSource.value[i] = data
        }
    })
    countTotal()
    modalRetur.value = false

}

const saveRetur = async (e: any) => {
    isDisabledSave.value = true;
    let jmlRetur = 0
    dataSource.value.forEach((element: any) => {
        let aya = element.jmlretur ? element.jmlretur : 0
        jmlRetur = jmlRetur + parseInt(aya)
    });
    if (!e.keteranganretur) {
        H.alert('error', 'Keterangan retur harus diisi');
        isSimpan.value = false;
        isDisabledSave.value = false;

        return;
    }
    if (!jmlRetur) {
        H.alert('error', 'Jumlah retur tidak boleh kosong');
        isSimpan.value = false;
        isDisabledSave.value = false;
        return;
    }
    isSimpan.value = true
    let objSave = {
        strukresep: {
            alasan: e.keteranganretur,
            namapasien: e.header.namapasien,
            nocm: e.header.nocm,
            noorder: 'EditResep',
            norecResep: NOREC_RESEP,
            noresep: e.detailResep.noresep,
            penulisresepfk: e.penulisResep.value,
            retur: 'RERTUR',
            ruanganfk: e.ruangan.value,
            jumlahitem : dataSource.value.length,
            status: 0,
            totalretur : jmlRetur,
            tglresep: e.detailResep.tglresep,
        },
        pelayananpasien: dataSource.value
    }

    try {
        if (e.header.statusbayar === 'Lunas') {
            await useApi().post('farmasi/save-retur-resep-dibayar', objSave);
        } else {
            await useApi().post('farmasi/save-retur-pelayanan', objSave);
        }
    } catch (error) {
        H.alert('error', 'Gagal menyimpan data');
    } finally {
        isSimpan.value = false;
        isDisabledSave.value = true;
         // Mengaktifkan kembali tombol setelah request selesai
    }
}

const countTotal = () => {
    let total = 0
    dataSource.value.forEach((element: any) => {
        total = total + parseFloat(element.total)
    })
    item.resultTotal = total
}

const back = () => {
    window.history.back();
}

watch(
    () => item.qtyretur,
    () => {
        if (item.qtyretur > item.jumlah) {
            H.alert('error', 'Jumlah retur melebihi batas')
            item.totalTagihan = item.total
            isDisabled.value = true
            return
        }
        if (item.qtyretur) {
            let hargaResult = parseFloat(item.qtyretur) * parseFloat(item.hargajual) - parseFloat(item.hargadiscount)
            let resultTotal = parseFloat(item.total) - hargaResult
            if (isNaN(resultTotal)) {
                item.totalTagihan = item.total
                isDisabled.value = true
            } else {
                item.totalTagihan = resultTotal
                isDisabled.value = false
            }
        } else {
            item.totalTagihan = item.total
            isDisabled.value = true
        }
    }
)



function toggleAll() {
    dataSource.value.forEach(row => {
        row.isSelected = allSelected.value;
        row.jmlretur = allSelected.value ? row.jumlah : 0;
    });
}
function onRowSelectChange(row) {
    row.jmlretur = row.isSelected ? row.jumlah : 0;
    allSelected.value = dataSource.value.every(row => row.isSelected); // Update "Select All" checkbox state
}

watch(dataSource, (newData) => {
    newData.forEach(row => {
        if (row.isSelected) {
            row.jmlretur = row.jumlah;
        }
    });
}, { deep: true });



loadData()

</script>
    
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout .form-outer {
    border: 1px solid transparent;
    background-color: transparent;
}
</style>
    
    