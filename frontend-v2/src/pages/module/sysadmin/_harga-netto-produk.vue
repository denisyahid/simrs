<route lang="yaml">
    meta:
      requiresAuth: true
    </route>
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
                                    Cancel
                                </VButton>
                                <VButton icon="feather:save" type="submit" color="primary" raised @click="save()"
                                    :loading="isSimpan">
                                    Save
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
                                        <h3 class="title is-5 mb-2">Data Produk</h3>
                                        <div class="columns is-multiline">
                                            <div class="column is-3">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Penjamin</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.objectrekananfk"
                                                            :options="d_Rekanan" placeholder="Pilih data"
                                                            :searchable="true" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Surat Keputusan</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.suratkeputusanfk"
                                                            :options="d_SuratKeputusan" placeholder="Pilih data"
                                                            :searchable="true" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Kelas</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.objectkelasfk"
                                                            :options="d_Kelas" placeholder="Pilih data"
                                                            :searchable="true" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Jenis Tarif</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.objectjenistariffk"
                                                            :options="d_JenisTarif" placeholder="Pilih data"
                                                            :searchable="true" :disabled="disabledRuangan" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText> Mata Uang</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.objectmatauangfk"
                                                            :options="d_MataUang" placeholder="Pilih data"
                                                            :searchable="true" :disabled="disabledRuangan" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Produk</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.objectprodukfk"
                                                            :options="d_Produk" placeholder="Pilih data"
                                                            :searchable="true" :disabled="disabledRuangan" />
                                                    </VControl>
                                                </VField>
                                            </div>

                                            <div class="column is-3">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Jenis Pelayanan</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.objectjenispelayananfk"
                                                            :options="d_JenisPelayanan" placeholder="Pilih data"
                                                            :searchable="true" :disabled="disabledRuangan" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Asal Produk</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.objectasalprodukfk"
                                                            :options="d_AsalProduk" placeholder="Pilih data"
                                                            :searchable="true" :disabled="disabledRuangan" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VDatePicker v-model="item.tglberlakuawal" color="green" trim-weeks
                                                    mode="dateTime">
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
                                                <VDatePicker v-model="item.tglberlakuakhir" color="green" trim-weeks
                                                    mode="dateTime">
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
                                                <VDatePicker v-model="item.tglkadaluarsalast" color="green" trim-weeks
                                                    mode="dateTime">
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
                                    </VCard>
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
import { useCurrencyInput } from 'vue-currency-input'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'


const TITLE_PAGE = 'Harga Netto Produk By Kelas'
useHead({
    title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_HARGANETTO = useRoute().query.id as string
let ID_HARGANETTO_SET = ref()
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
let NOREC_ORDER: any = useRoute().query.norec_order as string
let NOREC_RESEP: any = useRoute().query.norec_resep as string
const modalInput = ref(false)
const showRacikanDose = ref(false)
let item: any = reactive({
    header: {},
    totalAll: 0,
    jumlah: 0,
    persenDiskon: 0,
    // aturanPakai: [],
    hargadiskon: 0,
    tglAwal: new Date(),
    rke: 1,
    resep: '-',
})
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
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
let isRegistrasi = ref(false)
const disabledRuangan: any = ref(false)
const dataSelected: any = ref({})
const disTanggal: any = ref(false)
const fetchProduk = async (query: any) => {
    let qNamaProduk = ''
    if (query) {
        qNamaProduk = '?namaproduk=' + query.toLowerCase()
    }
    const response = await useApi().get(
        `/sysadmin/master-harga-netto-produk-by-kelas${qNamaProduk}`)

    return response.produk.map((item: any) => {
        return { value: item, label: item.namaproduk }
    })
}
async function onInit() {
    item.loading = false
    const response = await useApi().get(
        `/sysadmin/master-harga-netto-produk-by-kelas?noreca_pd=${NOREC_APD}`)

    item.loading = true
    item.header = response.data
    loadDrop()
}

async function loadDrop() {
    const response = await useApi().get(
        `/sysadmin/master-harga-netto-produk-by-kelas-dropdown`)
    d_Rekanan.value = response.namarekanan.map((e: any) => { return { label: e.namarekanan, value: e.id } })
    d_Kelas.value = response.namakelas.map((e: any) => { return { label: e.namakelas, value: e.id } })
    d_MataUang.value = response.matauang.map((e: any) => { return { label: e.matauang, value: e.id } })
    d_JenisTarif.value = response.jenistarif.map((e: any) => { return { label: e.jenistarif, value: e.id } })
    d_JenisPelayanan.value = response.jenispelayanan.map((e: any) => { return { label: e.jenispelayanan, value: e.id } })
    d_Produk.value = response.namaproduk.map((e: any) => { return { label: e.namaproduk, value: e.id } })
    d_SuratKeputusan.value = response.namask.map((e: any) => { return { label: e.namask, value: e.id } })
    d_AsalProduk.value = response.asalproduk.map((e: any) => { return { label: e.asalproduk, value: e.id } })
    d_KomponenHarga.value = response.komponenharga.map((e: any) => { return { label: e.komponenharga, value: e.id } })

    disabledRuangan.value = false;
    if (ID_HARGANETTO) {
        ID_HARGANETTO_SET.value = ID_HARGANETTO
    }

}

function addPopUp() {
    clearInput()
    modalInput.value = true
}
function editRow(data: any) {
    item.no = data.no
    item.objectkomponenhargafk = data.objectkomponenhargafk
    item.hargasatuan = data.hargasatuan
    item.hargadijamin = data.hargadijamin
    item.persendiscount = data.persendiscount
    modalInput.value = true
}
function add() {
    if (!item.objectkomponenhargafk) {
        useToaster().error('Komponen Harga harus di isi')
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


function hapusRow(e: any) {
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (data2.value[i].no == e.no) {
            data2.value.splice(i, 1);
        }
    }
    dataSource.value = data2.value
    clearInput()
}

const isEdit = ()=>{

}

async function save() {
    if (!item.objectprodukfk) {
        useToaster().error('Nama Produk harus di pilih')
        return
    }
    if (!item.suratkeputusanfk) {
        useToaster().error('Surat  harus di pilih')
        return
    }
    if (!item.objectkelasfk) {
        useToaster().error('Kelas  harus di pilih')
        return
    }
    if (!item.objectjenispelayananfk) {
        useToaster().error('Jenis Pelayanan harus di pilih')
        return
    }

    if (data2.value.length == 0) {
        useToaster().error('Komponen Harga belum di pilih')
        return
    }
    debugger
    var hargaSatuan = 0
    var hargadijamin = 0
    var persendiscount = 0
    for (let x = 0; x < data2.value.length; x++) {
        const element = data2.value[x];
        hargaSatuan = hargaSatuan + parseFloat(element.hargasatuan)
        if (element.persendiscount)
            hargadijamin = hargadijamin + parseFloat(element.persendiscount)
        if (element.persendiscount)
            persendiscount = persendiscount + parseFloat(element.persendiscount)
    }
    var objSave =
    {
        'harganettoprodukbykelas': {
            'id': item.id ? item.id : '',
            'suratkeputusanfk': item.suratkeputusanfk,
            'objectkelasfk': item.objectkelasfk,
            'objectjenistariffk': item.objectjenistariffk ? item.objectjenistariffk : null,
            'objectprodukfk': item.objectprodukfk,
            'objectasalprodukfk': item.objectasalprodukfk ? item.objectasalprodukfk : null,
            'objectjenispelayananfk': item.objectjenispelayananfk,
            'objectmatauangfk': item.objectmatauangfk ? item.objectmatauangfk : null,
            'tglberlakuawal': item.tglberlakuawal ? H.formatDate(item.tglberlakuawal, 'YYYY-MM-DD') : null,
            'tglberlakuakhir': item.tglberlakuakhir ? H.formatDate(item.tglberlakuakhir, 'YYYY-MM-DD') : null,
            'tglkadaluarsalast': item.tglkadaluarsalast ? H.formatDate(item.tglkadaluarsalast, 'YYYY-MM-DD') : null,
            'hargasatuan': hargaSatuan,
            'hargadijamin': hargadijamin,
            'persendiscount': persendiscount,
        },
        'harganettoprodukbykelasd': data2.value
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

function clearInput() {
    delete item.objectkomponenhargafk
    delete item.hargasatuan
    delete item.hargadiscount
    delete item.persendiscount
    delete item.hargadijamin
    delete dataSelected.value
    modalInput.value = false

}
// function changeDikson(e: any) {
//     if (e) {
//         item.hargadiskon = 0;
//         if (item.persenDiskon > 100) {
//             item.persenDiskon = 100
//         }
//         if (item.persenDiskon < 0) {
//             item.persenDiskon = 0
//         }

//         if (item.persenDiskon > 0) {
//             var diskon = (parseFloat(item.hargaSatuan) * parseFloat(item.persenDiskon)) / 100
//             if (!isNaN(diskon)) {
//                 item.hargadiskon = diskon;
//                 item.total = parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon);
//             }
//         }
//     } else {
//         item.hargadiskon = 0
//         item.total = parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon);
//     }
// }

function back() {
    window.history.back()
}
onInit()


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
//
</style>
    
    