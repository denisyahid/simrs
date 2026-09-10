
<template>
    <div class="columns">
        <div class="column is-12 form-layout is-stacked">
            <div class=" form-outer">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Harga Netto Produk By Kelas</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined>
                                    Batal
                                </VButton>
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isSimpan" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-body">
                    <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-12 p-4">
                                        <div class="columns is-multiline">
                                            <div class="column is-6">
                                                <VField label="Produk" class="is-rounded-select is-autocomplete-select"
                                                    v-slot="{ id }">
                                                    <VControl icon="fas fa-database" fullwidth class="prime-auto-select">
                                                        <AutoComplete v-model="item.objectprodukfk" :suggestions="d_Produk"
                                                            @complete="fetchProduk($event)" :optionLabel="'label'"
                                                            :dropdown="true" :minLength="3" :appendTo="'body'"
                                                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            placeholder="ketik untuk mencari..." />
                                                        <!-- <Dropdown v-model="item.objectprodukfk" :options="d_Produk"
                                                            :optionLabel="'namaproduk'" class="is-rounded"
                                                            placeholder="Produk" style="width: 100%;" :filter="true"
                                                            showClear /> -->
                                                    </VControl>
                                                </VField>
                                            </div>

                                            <div class="column is-6">
                                                <VField label="Penjamin" class="is-rounded-select is-autocomplete-select"
                                                    v-slot="{ id }">
                                                    <VControl icon="fas fa-hospital" fullwidth class="prime-auto-select">
                                                        <Dropdown v-model="item.objectpenjaminfk" :options="d_Rekanan"
                                                            :optionLabel="'namarekanan'" class="is-rounded"
                                                            placeholder="Penjamin " style="width: 100%;" :filter="true"
                                                            showClear />
                                                    </VControl>
                                                </VField>
                                            </div>

                                            <div class="column is-4">
                                                <VField label="Kelas" class="is-rounded-select is-autocomplete-select"
                                                    v-slot="{ id }">
                                                    <VControl icon="fas fa-arrow-up" fullwidth class="prime-auto-select">
                                                        <Dropdown v-model="item.objectkelasfk" :options="d_Kelas"
                                                            :optionLabel="'namakelas'" class="is-rounded"
                                                            placeholder="Kelas" style="width: 100%;" :filter="true"
                                                            showClear />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label=" Jenis Pelayanan"
                                                    class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="fas fa-bed" fullwidth class="prime-auto-select">
                                                        <Dropdown v-model="item.objectjenispelayananfk"
                                                            :options="d_JenisPelayanan" :optionLabel="'jenispelayanan'"
                                                            class="is-rounded" placeholder="Jenis Pelayanan "
                                                            style="width: 100%;" :filter="true" showClear />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Surat Keputusan"
                                                    class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="fas fa-sticky-note" fullwidth class="prime-auto-select">
                                                        <Dropdown v-model="item.suratkeputusanfk"
                                                            :options="d_SuratKeputusan" :optionLabel="'namask'"
                                                            class="is-rounded" placeholder="Surat Keputusan "
                                                            style="width: 100%;" :filter="true" showClear />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <Fieldset legend="Optional" :toggleable="true" :collapsed="collapsedOps">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4">
                                                            <VField label="Jenis Tarif"
                                                                class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="feather:search" fullwidth
                                                                    class="prime-auto-select">
                                                                    <Dropdown v-model="item.objectjenistariffk"
                                                                        :options="d_JenisTarif" :optionLabel="'jenistarif'"
                                                                        class="is-rounded" placeholder="Pilih Data"
                                                                        style="width: 100%;" :filter="true" showClear />
                                                                </VControl>
                                                            </VField>

                                                        </div>

                                                        <div class="column is-4">
                                                            <VField label="Mata Uang"
                                                                class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="feather:search" fullwidth
                                                                    class="prime-auto-select">
                                                                    <Dropdown v-model="item.objectmatauangfk"
                                                                        :options="d_MataUang" :optionLabel="'matauang'"
                                                                        class="is-rounded" placeholder="Pilih Data"
                                                                        style="width: 100%;" :filter="true" showClear />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField label="Asal Produk "
                                                                class="is-rounded-select is-autocomplete-select"
                                                                v-slot="{ id }">
                                                                <VControl icon="feather:search" fullwidth
                                                                    class="prime-auto-select">
                                                                    <Dropdown v-model="item.objectasalprodukfk"
                                                                        :options="d_AsalProduk" :optionLabel="'asalproduk'"
                                                                        class="is-rounded" placeholder="Pilih Data"
                                                                        style="width: 100%;" :filter="true" showClear />
                                                                </VControl>
                                                            </VField>

                                                        </div>

                                                        <div class="column is-4">
                                                            <VDatePicker v-model="item.tglberlakuawal" color="green"
                                                                trim-weeks mode="dateTime">
                                                                <template #default="{ inputValue, inputEvents }">
                                                                    <VField>
                                                                        <VLabel>Tanggal Berlaku Awal</VLabel>
                                                                        <VControl icon="feather:calendar">
                                                                            <VInput type="text" placeholder="Select a date"
                                                                                class="is-rounded" :value="inputValue"
                                                                                v-on="inputEvents" :disabled="disTanggal" />
                                                                        </VControl>
                                                                    </VField>
                                                                </template>
                                                            </VDatePicker>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VDatePicker v-model="item.tglberlakuakhir" color="green"
                                                                trim-weeks mode="dateTime">
                                                                <template #default="{ inputValue, inputEvents }">
                                                                    <VField>
                                                                        <VLabel>Tanggal Berlaku Akhir</VLabel>
                                                                        <VControl icon="feather:calendar">
                                                                            <VInput type="text" placeholder="Select a date"
                                                                                class="is-rounded" :value="inputValue"
                                                                                v-on="inputEvents" :disabled="disTanggal" />
                                                                        </VControl>
                                                                    </VField>
                                                                </template>
                                                            </VDatePicker>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VDatePicker v-model="item.tglkadaluarsalast" color="green"
                                                                trim-weeks mode="dateTime">
                                                                <template #default="{ inputValue, inputEvents }">
                                                                    <VField>
                                                                        <VLabel>Tanggal Kadaluarsa</VLabel>
                                                                        <VControl icon="feather:calendar">
                                                                            <VInput type="text" placeholder="Select a date"
                                                                                class="is-rounded" :value="inputValue"
                                                                                v-on="inputEvents" :disabled="disTanggal" />
                                                                        </VControl>
                                                                    </VField>
                                                                </template>
                                                            </VDatePicker>
                                                        </div>
                                                    </div>
                                                </Fieldset>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="column is-12">
                                        <h3 class="title is-5 mb-2 mr-1">Detail Komponen #{{ listItem.length }}</h3>
                                        <VCard
                                            style="border-top-left-radius: 11px; border-left: solid hsla(323, 100%, 75%, 0.72) 3px;">

                                            <div class="columns is-multiline">
                                                <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                                                    <VCard class="is-grey">
                                                        <div class="columns is-multiline p-1">
                                                            <div class="column is-4">
                                                                <VField label="Komponen Harga"
                                                                    class="is-rounded-select is-autocomplete-select"
                                                                    v-slot="{ id }">
                                                                    <VControl icon="fas fa-credit-card" fullwidth
                                                                        class="prime-auto-select">
                                                                        <Dropdown v-model="item.objectkomponenhargafk"
                                                                            :options="d_KomponenHarga"
                                                                            :optionLabel="'komponenharga'"
                                                                            class="is-rounded" placeholder="Komponen Harga"
                                                                            style="width: 100%;" :filter="true" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-3">
                                                                <VField>
                                                                    <VLabel>Harga Satuan</VLabel>
                                                                    <VControl icon="fas fa-calculator">
                                                                        <InputNumber v-model="item.hargasatuan"
                                                                            class="input is-rounded" locale="id-ID"
                                                                            inputId="minmaxfraction" :minFractionDigits="2"
                                                                            placeholder="Harga Satuan"
                                                                            @blur="changeNomi()" />

                                                                    </VControl>

                                                                </VField>
                                                            </div>
                                                            <div class="column is-3">
                                                                <VField>
                                                                    <VLabel>Harga Diskon</VLabel>
                                                                    <VControl icon="fas fa-calculator">
                                                                        <InputNumber v-model="item.hargadiscount"
                                                                            class="input is-rounded" locale="id-ID"
                                                                            inputId="minmaxfraction" :minFractionDigits="2"
                                                                            placeholder="Harga Diskon"
                                                                            @blur="changeNomi()" />

                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-1 mt-5">
                                                                <VIconButton v-if="index > 0" outlined type="button" raised
                                                                    circle class="is-pulled-right" icon="feather:trash"
                                                                    @click="removeItem(index)" color="danger">
                                                                </VIconButton>
                                                            </div>
                                                            <div class="column is-1 mt-5">
                                                                <VIconButton outlined type="button" raised circle
                                                                    class="is-pulled-right" icon="feather:plus"
                                                                    @click="addNewItem()" color="info"
                                                                    v-tooltip.bubble="'Tambah Komponen'">
                                                                </VIconButton>
                                                            </div>
                                                        </div>
                                                    </VCard>
                                                </div>
                                                <div class="column is-8 is-offset-4 pt-0 mt-0">
                                                    <VCard
                                                        style="border-top-left-radius: 11px; border-left: solid hsla(170, 100%, 75%, 0.72) 3px;">
                                                        <div class="columns is-multiline">
                                                            <div class="column is-2 mt-1">
                                                                <VField>
                                                                    <VLabel class="fs-total-label">TOTAL</VLabel>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-10">
                                                                <VField>
                                                                    <VLabel class="fs-total"
                                                                        style="font-weight: 700;  font-size: 2.4rem;width:300px !important">
                                                                        {{
                                                                            H.formatRp(isNaN(item.totalHarga) ? 0 :
                                                                                item.totalHarga,
                                                                                'Rp.')
                                                                        }}
                                                                    </VLabel>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </VCard>
                                                </div>
                                            </div>
                                        </VCard>
                                        <!--  <VCard>
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
                                                        <Column field="komponenharga" header="Komponen Harga"></Column>
                                                        <Column field="hargasatuan" header="Harga Satuan">
                                                            <template #body="slotProps">
                                                                {{ formatRp(slotProps.data.hargasatuan, '') }}
                                                            </template>
                                                        </Column>

                                                        <Column field="persendiscount" header="Diskon (%)"></Column>
                                                        <Column field="hargadijamin" header="Harga Dijamin">
                                                            <template #body="slotProps">
                                                                {{ formatRp(slotProps.data.hargadijamin, '') }}
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
                                                    </DataTable>
                                                </div>
                                            </div>
                                        </VCard> -->
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- </div> -->

        <VModal :open="modalInput" title="Add Komponen Harga" size="small" actions="right" @close="modalInput = false">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Komponen Harga</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectkomponenhargafk"
                                        :options="d_KomponenHarga" placeholder="Pilih data" :searchable="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VLabel>Harga Satuan</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.hargasatuan" placeholder="Harga "
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VLabel>Persen Diskon</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.persendiscount" placeholder="Persen Diskon"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VLabel>Harga Dijamin</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.hargadijamin" placeholder="Harga Dijamin"
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
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import * as H from '/@src/utils/appHelper'
import Fieldset from 'primevue/fieldset';
import InputNumber from 'primevue/inputnumber';
const TITLE_PAGE = 'Harga Netto Produk By Kelas'
useHead({
    title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_HARGANETTO = useRoute().query.id as string
let ID_HARGANETTO_SET = ref()
const modalInput = ref(false)
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
const listItem: any = ref([
    {
        no: 1,
        hargasatuan: 0,
        hargadiscount: 0,
        objectkomponenhargafk: null
    }
])
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const collapsedOps: any = ref(true)
const d_Kelas: any = ref([])
const d_MataUang: any = ref([])
const d_Rekanan: any = ref([])
const d_JenisTarif: any = ref([])
const d_Produk: any = ref([])
const d_SuratKeputusan: any = ref([])
const d_AsalProduk: any = ref([])
const d_JenisPelayanan: any = ref([])
const d_KomponenHarga: any = ref([])
const dataSource: any = ref([])
const data2: any = ref([])

const isLoading: any = ref(false)
const isSimpan: any = ref(false)

const disabledRuangan: any = ref(false)
const dataSelected: any = ref({})
const disTanggal: any = ref(false)

const fetchProduk = async (filter: any) => {
    console.log(filter)
    let data = filter.query ? filter.query : filter
    const response = await useApi().get(`emr/dropdown/produk_m?select=id,namaproduk&param_search=namaproduk&query=${data}&limit=10`)
    d_Produk.value = response
}
const listDropdown = async () => {
    const response = await useApi().get(
        `/sysadmin/master-harga-netto-produk-by-kelas-dropdown`)
    d_Rekanan.value = response.namarekanan
    d_Kelas.value = response.namakelas
    d_MataUang.value = response.matauang
    d_JenisTarif.value = response.jenistarif
    d_JenisPelayanan.value = response.jenispelayanan
    d_SuratKeputusan.value = response.namask
    if (response.namask.length > 0) {
        item.suratkeputusanfk = response.namask[0]
    }
    d_AsalProduk.value = response.asalproduk
    d_KomponenHarga.value = response.komponenharga
    if (ID_HARGANETTO) {
        ID_HARGANETTO_SET.value = ID_HARGANETTO
        await fetchDataEdit(ID_HARGANETTO)
    }
}
const fetchDataEdit = async (id: any) => {
    isSimpan.value = true
    const data = await useApi().get(`/sysadmin/master-harga-netto-produk-by-kelas/${ID_HARGANETTO}`)
    isSimpan.value = false
    let head = data.head
    let detail = data.detail

    await fetchProduk(head.namaproduk)
    d_Produk.value.forEach(element => {
        if(element.value ==  head.objectprodukfk ){
            item.objectprodukfk = element
            return
        }
    });

    item.id = data.head.id
    // d_Produk.value.forEach((e: any) => {
    //     if (e.id == head.objectprodukfk) {
    //         item.objectprodukfk = e
    //         return
    //     }
    // });
    d_Rekanan.value.forEach((e: any) => {
        if (e.id == head.objectpenjaminfk) {
            item.objectpenjaminfk = e
            return
        }
    });
    d_Kelas.value.forEach((e: any) => {
        if (e.id == head.objectkelasfk) {
            item.objectkelasfk = e
            return
        }
    });
    d_MataUang.value.forEach((e: any) => {
        if (e.id == head.objectmatauangfk) {
            item.objectmatauangfk = e
            collapsedOps.value = false
            return
        }
    });
    d_JenisTarif.value.forEach((e: any) => {
        if (e.id == head.objectjenistariffk) {
            item.objectjenistariffk = e
            return
        }
    });
    d_JenisPelayanan.value.forEach((e: any) => {
        if (e.id == head.objectjenispelayananfk) {
            item.objectjenispelayananfk = e
            return
        }
    });
    d_SuratKeputusan.value.forEach((e: any) => {
        if (e.id == head.suratkeputusanfk) {
            item.suratkeputusanfk = e
            return
        }
    });
    d_AsalProduk.value.forEach((e: any) => {
        if (e.id == head.objectasalprodukfk) {
            item.objectasalprodukfk = e
            return
        }
    })
    item.tglberlakuawal = head.tglberlakuawal ? H.formatDate(head.tglberlakuawal, 'YYYY-MM-DD') : null
    item.tglkadaluarsalast = head.tglkadaluarsalast ? H.formatDate(head.tglkadaluarsalast, 'YYYY-MM-DD') : null
    item.tglberlakuakhir = head.tglberlakuakhir ? H.formatDate(head.tglberlakuakhir, 'YYYY-MM-DD') : null
    if (detail.length != 0) {
        listItem.value = []
        for (let x = 0; x < detail.length; x++) {
            const element = detail[x];
            let komponenharga = null
            for (let y = 0; y < d_KomponenHarga.value.length; y++) {
                const element2 = d_KomponenHarga.value[y];
                if (element2.id == element.objectkomponenhargafk) {
                    komponenharga = element2
                    break
                }
            }
            listItem.value.push({
                no: x + 1,
                hargasatuan: element.hargasatuan,
                hargadiscount: element.hargadiscount,
                objectkomponenhargafk: komponenharga
            })
        }

        setTotal()
    } else {
        item.totalHarga = head.hargasatuan
    }
}
const addPopUp = () => {
    clearInput()
    modalInput.value = true
}
const editRow = (data: any) => {
    item.no = data.no
    item.objectkomponenhargafk = data.objectkomponenhargafk
    item.hargasatuan = data.hargasatuan
    item.hargadijamin = data.hargadijamin
    item.persendiscount = data.persendiscount
    modalInput.value = true
}
const add = () => {
    if (!item.objectkomponenhargafk) {
        H.alert('error', 'Komponen Harga harus di isi')
        return
    }
    let nomor = 0
    if (data2.value.length == 0) {
        nomor = 1
    } else {
        nomor = data2.value.length + 1
    }
    var data: any = {};
    var namakomponen = ''
    for (let x = 0; x < d_KomponenHarga.value.length; x++) {
        const element = d_KomponenHarga.value[x];
        if (element.value == item.objectkomponenhargafk) {
            namakomponen = element.label
            break
        }
    }
    if (item.no != undefined) {
        for (let x = 0; x < data2.value.length; x++) {
            const element = data2.value[x];
            if (element.no == item.no) {
                data.no = item.no
                data.objectkomponenhargafk = item.objectkomponenhargafk
                data.komponenharga = namakomponen
                data.hargasatuan = item.hargasatuan
                data.hargadijamin = item.hargadijamin ? item.hargadijamin : null
                data.persendiscount = item.persendiscount ? item.persendiscount : null
                data2.value[x] = data;
            }
        }
    } else {
        data = {
            no: nomor,
            objectkomponenhargafk: item.objectkomponenhargafk,
            komponenharga: namakomponen,
            hargasatuan: item.hargasatuan,
            hargadijamin: item.hargadijamin ? item.hargadijamin : null,
            persendiscount: item.persendiscount ? item.persendiscount : null,
        }
        data2.value.push(data)

    }
    dataSource.value = data2.value
    clearInput()
}

const hapusRow = async (e: any) => {
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (data2.value[i].no == e.no) {
            data2.value.splice(i, 1);
        }
    }
    dataSource.value = data2.value
    clearInput()
}

const isEdit = () => {

}
const simpan = async () => {

    if (!item.objectprodukfk) {
        H.alert('error', 'Nama Produk harus di pilih')
        return
    }
    if (!item.suratkeputusanfk) {
        H.alert('error', 'Surat  harus di pilih')
        return
    }
    if (!item.objectkelasfk) {
        H.alert('error', 'Kelas  harus di pilih')
        return
    }
    if (!item.objectjenispelayananfk) {
        H.alert('error', 'Jenis Pelayanan harus di pilih')
        return
    }

    if (listItem.value.length == 0) {
        H.alert('error', 'Komponen Harga belum di pilih')
        return
    }
    var hargaSatuan = 0
    var hargadijamin = 0
    var hargadiscount = 0
    for (let x = 0; x < listItem.value.length; x++) {
        const element = listItem.value[x];
        element.persendiscount = null;
        element.hargadijamin = null;
        element.harganetto1 = parseFloat(element.hargasatuan)
        element.harganetto2 = parseFloat(element.hargasatuan)
        hargaSatuan = hargaSatuan + parseFloat(element.hargasatuan)
        if (element.hargadiscount) {
            hargadiscount = hargadiscount + parseFloat(element.hargadiscount)
            element.persendiscount = parseFloat(element.hargadiscount) / parseFloat(element.hargasatuan) * 100;
        }
        element.objectkomponenhargafk = element.objectkomponenhargafk.id
    }
    var objSave =
    {
        'harganettoprodukbykelas': {
            'id': item.id ? item.id : '',
            'suratkeputusanfk': item.suratkeputusanfk.id,
            'objectkelasfk': item.objectkelasfk.id,
            'objectjenistariffk': item.objectjenistariffk ? item.objectjenistariffk.id : null,
            'objectpenjaminfk': item.objectpenjaminfk ? item.objectpenjaminfk.id : null,
            'objectprodukfk': item.objectprodukfk.value,
            'objectasalprodukfk': item.objectasalprodukfk ? item.objectasalprodukfk.id : null,
            'objectjenispelayananfk': item.objectjenispelayananfk.id,
            'objectmatauangfk': item.objectmatauangfk ? item.objectmatauangfk.id : null,
            'tglberlakuawal': item.tglberlakuawal ? H.formatDate(item.tglberlakuawal, 'YYYY-MM-DD') : null,
            'tglberlakuakhir': item.tglberlakuakhir ? H.formatDate(item.tglberlakuakhir, 'YYYY-MM-DD') : null,
            'tglkadaluarsalast': item.tglkadaluarsalast ? H.formatDate(item.tglkadaluarsalast, 'YYYY-MM-DD') : null,
            'hargasatuan': hargaSatuan,
            'hargadijamin': hargadijamin,
            'hargadiscount': hargadiscount,
        },
        'harganettoprodukbykelasd': listItem.value
    }

    isSimpan.value = true
    await useApi().post(
        `/sysadmin/save-harga-netto-produk-by-kelas`, objSave).then((response: any) => {
            isSimpan.value = false
            window.history.back();
        }, (error) => {
            isSimpan.value = false
            // console.log(error)
        })
}
const clearInput = () => {
    delete item.objectkomponenhargafk
    delete item.hargasatuan
    delete item.hargadiscount
    delete item.persendiscount
    delete item.hargadijamin
    delete dataSelected.value
    modalInput.value = false

}
const back = () => {
    window.history.back()
}
const addNewItem = () => {
    if (listItem.value[listItem.value.length - 1].objectkomponenhargafk == null) {
        H.alert('error', 'Nama Komponen belum di pilih')
        return
    }
    listItem.value.push({
        no: listItem.value[listItem.value.length - 1].no + 1,
        hargasatuan: 0,
        hargadiscount: 0,
        objectkomponenhargafk: null
    });
    setTotal()
}
const removeItem = (index: any) => {
    listItem.value.splice(index, 1)
}
const setTotal = () => {
    let total = countTotal()
    item.totalHarga = total
}
const countTotal = () => {
    let total = 0
    for (let x = 0; x < listItem.value.length; x++) {
        const element = listItem.value[x];
        let totals = parseFloat(element.hargasatuan) - parseFloat((element.hargadiscount ? element.hargadiscount : 0))
        total += totals
    }
    return total

}
const changeNomi = () => {
    setTotal()
}
watch(
    () => listItem.value,
    (newValue, oldValue) => {

    }
)
setInterval(() => {
    setTotal()
}, 1000);

listDropdown()


onMounted(() => {

})
</script>
    
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/inacbgs/inacbgs-detail';
</style>
    
    