
<template>
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
                                <VButton icon="lnir lnir-arrow-left rem-100" @click="back" light dark-outlined>
                                    Batal
                                </VButton>
                                <VButton icon="feather:save" type="submit" color="primary" raised @click="DialogConfirm(e)"
                                    :loading="isSimpan" :disabled="idDisabledBtn">
                                    Simpan
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
                                        <!-- <h3 class="title is-5 mb-2">Data Resep</h3> -->
                                        <div class="columns is-multiline">
                                            <div class="column is-3">
                                                <VDatePicker v-model="item.tglkirim" color="green" trim-weeks
                                                    mode="dateTime">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VLabel>Tanggal</VLabel>
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
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Ruangan Pengirim</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.ruanganPengirim"
                                                            :options="d_ruangan" placeholder="Pilih data" :searchable="true"
                                                            :disabled="disabledRuangan" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Ruangan Tujuan</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.ruanganTujuan"
                                                            :options="d_rutu" placeholder="Pilih data" :searchable="true"
                                                            :disabled="NOREC_ORDER ? true : false" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Jenis Kirim</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.jenisKirim"
                                                            :options="d_jenisKirim" placeholder="Pilih data"
                                                            :searchable="true"
                                                            :disabled="NOREC_KIRIM || NOREC_ORDER ? true : false" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <VField>
                                                    <VLabelText>Keterangan</VLabelText>
                                                    <VControl>
                                                        <input v-model="item.keterangan" type="text"
                                                            class="input is-rounded" placeholder="keterangan..." />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                                <div class="column is-12">
                                    <VCard>
                                        <div class="columns is-multiline">

                                            <!-- v-else-if="!isEdit" -->
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
                                                                @click="editRow(slotProps.data)" :loading="slotProps.data.btnEditLoad" />
                                                            <Button icon="pi pi-trash"
                                                                class="p-button-rounded p-button-danger"
                                                                @click="hapusRow(slotProps.data)" />
                                                        </template>
                                                    </Column>

                                                    <Column field="no" header="No"></Column>
                                                    <Column field="namaproduk" header="Produk" :sortable="true"></Column>
                                                    <Column field="satuanstandar" header="Satuan"></Column>
                                                    <Column field="nilaikonversi" header="Konversi"></Column>
                                                    <Column field="stock" header="Stok"></Column>
                                                    <Column field="jumlah" header="Qty"></Column>
                                                    <Column field="qtyprodukkonfirmasi" header="Qty Konfirmasi"
                                                        v-if="NOREC_ORDER"></Column>

                                                    <template #paginatorstart>
                                                        <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                                                    </template>
                                                    <template #paginatorend>
                                                        <Button type="button" icon="pi pi-cloud" class="p-button-text" />
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

        <!-- </div> -->

        <VModal :open="modalInput" title="Add Barang" size="big" actions="right" @close="modalInput = false">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Produk</VLabel>
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="item.produk" :suggestions="d_produk"
                                        @complete="fetchProduk($event)" :optionLabel="'namaproduk'" :dropdown="true"
                                        :minLength="3" class="is-rounded" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'namaproduk'" placeholder="ketik untuk mencari..."
                                        @item-select="changeProduk(item.produk)" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Satuan</VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.satuan" :options="d_satuan" :optionLabel="'label'"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear
                                        :filter="true" @change="changeSatuan(item.satuan)" />
                                    <!-- <Multiselect mode="single" v-model="item.satuan" :options="d_satuan"
                                        placeholder="Pilih data" :searchable="true" @select="changeSatuan(item.satuan)" /> -->
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1">
                            <VField>
                                <VLabel>Konversi</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.nilaiKonversi" placeholder="konversi"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <VLabel>Total Stok</VLabel>
                                <VControl icon="feather:bookmark" :loading="isLoad">
                                    <VInput type="text" v-model="item.stok" placeholder="Stok" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column" :class="NOREC_ORDER ? 'is-2' : 'is-3'">
                            <VField>
                                <VLabel>Jumlah</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.jumlah" :disabled="NOREC_ORDER ? true : false" placeholder="Jumlah" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column" v-if="NOREC_ORDER" :class="NOREC_ORDER ? 'is-2' : 'is-3'">
                            <VField>
                                <VLabel>Qty Konfirmasi</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.qtyprodukkonfirmasi" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Tgl Kadaluarsa</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select" :loading="isLoad">
                                    <Dropdown v-model="item.tglKadaluarsa" :options="d_tglKadaluarsa"
                                        :optionLabel="'tglkadaluarsa'" class="is-rounded" placeholder="Pilih data"
                                        style="width: 100%;" :filter="true"
                                        @change="changeExpired(item.tglKadaluarsa)" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column mt-4" style="align-content: center;color: #cd2b2b;font-weight: 500;">
                             <small>{{infoStokTgl}}</small>
                        </div>
                    </div>

                    <div  v-if="displayStok == true">
                        <div class="column is-12">
                            <div class="content">
                                <div class="is-divider" :data-content="infoStok" />
                            </div>
                        </div>

                        <div class="column is-12">
                            <DataTable :value="dataSourceStokProduk" :paginator="true" :rows="5"
                                :rowsPerPageOptions="[5, 10, 25]"
                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                                <Column field="no" header="No"></Column>
                                <Column field="namaruangan" header="ruangan"></Column>
                                <Column field="stok" header="Stok"></Column>
                            </DataTable>
                        </div>
                    </div>
                </form>
            </template>
            <template #action>
                <VButton icon="feather:plus" @click="tambah()" color="primary" raised :disabled="isReady">Tambah</VButton>
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
import AutoComplete from 'primevue/autocomplete';
import TdaftarDistribusi from './t_daftar-distribusi.vue'
import DataTable from 'primevue/datatable';
import Dropdown from 'primevue/dropdown';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Button from 'primevue/button';
import moment from 'moment'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'

const TITLE_PAGE = 'Distribusi Barang'
useHead({
    title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let NOREC_ORDER: any = useRoute().query.norec_order as string
let NOREC_KIRIM: any = useRoute().query.norec as string
let ISEDIT_KIRIM: any = useRoute().query.iseditkirim as string
const modalInput = ref(false)
const isEdit:any = ref(false)
let item: any = reactive({
    header: {},
    totalAll: 0,
    jumlah: 0,
    tglkirim: new Date(),
})
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const confirm = useConfirm()
const d_ruangan: any = ref([])
const d_rutu: any = ref([])
const d_produk: any = ref([])
const d_satuan: any = ref([])
const d_jenisKirim: any = ref([])
const d_satuanResep: any = ref([])
const d_tglKadaluarsa: any = ref([])
const dataSource: any = ref([])
const data2: any = ref([])
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const isLoading: any = ref(false)
const isSimpan: any = ref(false)
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const dataProdukDetail: any = ref([])
const isLoad: any = ref(false)
const disabledRuangan: any = ref(false)
const disabledJenis: any = ref(false)
const idDisabledBtn: any = ref(false)
const btnEditLoad: any = ref(false)
const dataSelected: any = ref({})
const disTanggal: any = ref(false)
const isReady: any = ref(false)
const infoStokTgl: any = ref('')
const infoStok: any = ref('List Informasi Stok')
const displayStok: any = ref(false)
const dataSourceStokProduk: any = ref([])

const onInit = async () => {
    loadDrop()
    // item.loading = false
    // item.loading = true
    if (NOREC_KIRIM != undefined || NOREC_ORDER != undefined) {
        loadEdit()
    }
    
    // if (NOREC_ORDER != undefined) {
    //     loadEdit()
    // }
}

const loadDrop = async () => {
    const response = await useApi().get(`/logistik/distribusi-barang-cbo`)
    d_ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
    d_jenisKirim.value = response.jenis.map((e: any) => { return { label: e.jenis, value: e.id } })
    d_satuanResep.value = response.satuanresep.map((e: any) => { return { label: e.satuanresep, value: e } })
    d_rutu.value = response.rutu.map((e: any) => { return { label: e.namaruangan, value: e.id } })
    disabledRuangan.value = false;
    disabledJenis.value = false;
}

const fetchProduk = async (filter: any) => {
    const response = await useApi().get(`/logistik/distribusi-barang-produk?namaproduk=${filter.query}&ruanganfk=${item.ruanganPengirim}&limit=10`)
    d_produk.value = response.produk
}




const loadEdit = async () => {
    if (NOREC_KIRIM) {
        dataSource.value = ''
        await useApi().get(`/logistik/distribusi-detail?norec=${NOREC_KIRIM}`).then((response: any) => {
            item.jenisKirim = response.head.jenispermintaanfk
            item.ruanganPengirim = response.head.id
            item.ruanganTujuan = response.head.ruid2
            item.keterangan = response.head.keterangan != '' ? response.head.keterangan : '-'
            item.tglkirim = new Date(response.head.tglkirim)
            disabledRuangan.value = true;
            disTanggal.value = true;
            response.detail.forEach((element: any, i: any) => {
                element.no = i + 1
                element.norec_spd = element.norec_spd ? element.norec_spd : null
                // element.qtyprodukkonfirmasi = element.qtyprodukkonfirmasi ? element.qtyprodukkonfirmasi : element.jumlah
            });

            data2.value = response.detail
            dataSource.value = response.detail
        })

    }
    if (NOREC_ORDER) {
        isSimpan.value = true;
        isLoading.value = true
        dataSource.value = ''
        let url = ISEDIT_KIRIM ? `logistik/get-detail-kirim-order-barang?norec=${NOREC_ORDER}` : `/logistik/get-detail-kirim-order?norec=${NOREC_ORDER}`
        await useApi().get(url).then((response: any) => {
            let head = response.strukorder

            response.order.forEach((element: any, i: any) => {
                element.no = i + 1
                // element.qtyprodukkonfirmasi = element.qtyprodukkonfirmasi
            });
            isLoading.value = false
            isSimpan.value = false;

            item.jenisKirim = head.jenisid
            item.ruanganPengirim = head.ruidasal
            item.ruanganTujuan = head.ruidtujuan
            item.keterangan = head.keterangan
            disabledRuangan.value = true;
            disTanggal.value = true;

            data2.value = response.order
            dataSource.value = data2.value
        })
        // return
    }
}
// }

const addPopUp = () => {
    if (!item.ruanganPengirim) {
        useToaster().error('Ruangan pengirim harus di pilih')
        return
    }

    if (!item.ruanganTujuan) {
        useToaster().error('Ruangan tujuan harus di pilih')
        return
    }
    clearInput()
    isLoading.value = false
    modalInput.value = true
}
const cekProdukSama = () => {       
    
    if (isEdit.value == false && data2.value.length > 0) {
        for (let i = 0; i < data2.value.length; i++) {
            const element = data2.value[i];
            if (item.produk.id == element.produkfk) {
                useToaster().error('Produk ' + element.namaproduk + ', tidak bisa menambahkan produk yang sama !')
                return false
            }
        }
    }
    return true  
}
const tambah = () => {

    if (!item.produk) {
        useToaster().error('Produk harus di isi')
        return
    }
    if (!item.jumlah || item.jumlah == 0) {
        useToaster().error('Jumlah harus di isi')
        return
    }
    if (item.stok < item.qtyprodukkonfirmasi) {
        useToaster().error('Qty Konfirmasi Produk Melebihi Stok Produk')
        return
    }

    if (item.stok == 0) {
        useToaster().error('Stok tidak ada')
        return
    }

    if (norecSPD.value == '') {
        useToaster().error('Norec Tidak Ada')
        return
    }
    if (!item.satuan) {
        useToaster().error('Satuan harus di isi')
        return
    }
    if (!NOREC_KIRIM) {
       let cek = cekProdukSama();
       if(!cek){
        return
       }
    }
    let nomor = 0
    if (data2.value.length == 0) {
        nomor = 1
    } else {
        nomor = data2.value.length + 1
    }

    // var qtyOK: any = 0;
    var qtyCetak = 0;
    var total = 0;
    var jumlahreal = 0;

    let jmlbulat = 0;
    let jml = 0;

    jmlbulat = item.jumlahbulat;
    jml = item.jumlah;
    isLoading.value = true
    disabledRuangan.value = true;

    var data: any = {};
    if (item.no != undefined) {
        data2.value.forEach((element: any, i: any) => {
            if (element.no == item.no) {
                data.no = item.no
                data.hargajual = String(item.hargaSatuan)
                data.stock = String(item.stok)
                data.harganetto = String(item.hargaSatuan)
                data.nostrukterimafk = norecTerima.value
                data.norec_spd = norecSPD.value
                data.ruanganfk = item.ruanganPengirim ? item.ruanganPengirim : element.ruanganfk
                data.asalprodukfk = item.asal.id
                data.asalproduk = item.asal.asalproduk
                data.produkfk = item.produk.id
                data.namaproduk = item.produk.namaproduk
                data.nilaikonversi = item.nilaiKonversi
                data.satuanstandarfk = item.satuan.ssid ? item.satuan.ssid : item.satuan.value.ssid
                data.satuanstandar = item.satuan.satuanstandar ? item.satuan.satuanstandar : item.satuan.label
                data.satuanviewfk = item.satuan.ssid ? item.satuan.ssid : item.satuan.value.ssid
                data.satuanview = item.satuan.satuanstandar ? item.satuan.satuanstandar : item.satuan.label
                data.jmlstok = String(item.stok)
                data.jumlah = item.jumlah
                data.qtyprodukkonfirmasi = NOREC_ORDER ? item.qtyprodukkonfirmasi : item.jumlah
                data.qtyorder = 0
                data.hargasatuan = String(item.hargaSatuan)
                data.hargadiscount = 0
                data.total = item.total
                data2.value[i] = data;
            }
        });

    } else {
        data = {
            no: nomor,
            hargajual: String(item.hargaSatuan),
            stock: String(item.stok),
            harganetto: String(item.hargaSatuan),
            nostrukterimafk: norecTerima.value,
            norec_spd: norecSPD.value,
            ruanganfk: item.ruanganPengirim,
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
            jumlah: item.jumlah,
            qtyprodukkonfirmasi: NOREC_ORDER ? item.qtyprodukkonfirmasi : item.jumlah,
            qtyorder: 0,
            hargasatuan: String(item.hargaSatuan),
            hargadiscount: String(item.hargadiskon),
            total: item.total
        }
        data2.value.push(data)

    }
    dataSource.value = data2.value

    clearInput()
}

const editRow = async (e: any) => {
    console.log(e)
    isEdit.value = true
    e.btnEditLoad = true
    await fetchProduk({ query: e.namaproduk })
    item.no = e.no
    d_produk.value.forEach((element: any) => {
        if (element.id == e.produkfk) {
            item.produk = element
            return
        }
    });
    dataSelected.value = e
    await GETKONVERSI()
    modalInput.value = true
    e.btnEditLoad = false
}

const changeExpired = (e: any) => {
    if (e != null && e != undefined) {
        setNorecSPD()
    }
}

const hapusRow = (e: any) => {
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (data2.value[i].no == e.no) {
            data2.value.splice(i, 1);
        }
    }
    dataSource.value = data2.value

    clearInput()
}
const save = async () => {

    if (!NOREC_KIRIM) {
        if (data2.value.length == 0) {
            useToaster().error('Produk belum di pilih')
            return
        }
    }

    if (!NOREC_KIRIM && !NOREC_ORDER) {
        for (let i = data2.value.length - 1; i >= 0; i--) {
            if (parseFloat(data2.value[i].jmlstok) < parseFloat(data2.value[i].jumlah)) {
                useToaster().error("Terdapat obat dengan jumlah melebihi STOK !! " + data2.value[i].namaproduk)
                return
            }
        }
    }

    if (NOREC_ORDER) {
        for (let i =  dataSource.value.length - 1; i >= 0; i--) {
            if (!dataSource.value[i].qtyprodukkonfirmasi) {
                H.alert('error', 'Qty Konfirmasi Tidak Boleh Kosong')
                return
            }
        }
        // dataSource.value.forEach(element => {
        //     if(!element.qtyprodukkonfirmasi){
        //         H.alert('error', 'Qty Konfirmasi Tidak Boleh Kosong')
        //         return
        //     }
        // });
    }
    
    let strukkirim = {
        editkirim : ISEDIT_KIRIM ? true : false,
        objectruanganfk: item.ruanganPengirim,
        objectruangantujuanfk: item.ruanganTujuan,
        jenispermintaanfk: item.jenisKirim,
        keteranganlainnyakirim: item.keterangan ? item.keterangan : 'Kirim Barang',
        qtydetailjenisproduk: 0,
        qtyproduk: data2.value.length,
        tglkirim: moment(item.tglkirim).format('YYYY-MM-DD HH:mm:ss'),
        totalhargasatuan: 0,
        norecOrder: NOREC_ORDER ? NOREC_ORDER : '',
        noreckirim: NOREC_KIRIM ? NOREC_KIRIM : '',
        norec_apd: 0
    }
    let objSave =
    {
        strukkirim: strukkirim,
        details: dataSource.value
    }
    // console.log(objSave)
    isSimpan.value = true

    let url = NOREC_ORDER ? '/logistik/save-kirim-order-barang' : '/logistik/distribusi-barang-produk-save'
    await useApi().post(url, objSave).then((response: any) => {
        idDisabledBtn.value = true
        isSimpan.value = false
        // window.history.back();
    }, (error) => {
        isSimpan.value = false
    })


}
const changeProduk = (e: any) => {
    if (e != null) {
        GETKONVERSI()
        getProdukListStok(e.id)
    }
}

const GETKONVERSI = async () => {
    if (item.produk.konversisatuan.length == 0) {
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
    d_satuan.value.forEach(element => {
        if (item.produk.ssid == element.value.ssid) {
            item.satuan = element
        }
    });
    // item.satuan = { label: item.produk.ssid, value: item.produk.satuanstandar }
    item.nilaiKonversi = 1
    isLoading.value = true
    dataProdukDetail.value = []
    isReady.value = true
    d_tglKadaluarsa.value = []
    delete item.stokByEd 
    infoStokTgl.value = ''
    isLoad.value = true
    await useApi().get(
        '/farmasi/get-produkdetail?produkfk=' + item.produk.id +
        '&ruanganfk=' + item.ruanganPengirim).then(function (response: any) {
            isLoad.value = false
            if (response.detail.length > 0) {
                 response.detail.forEach((element: any) => {
                    d_tglKadaluarsa.value.push({ tglkadaluarsa: element.tglkadaluarsa })
                });
                item.tglKadaluarsa = d_tglKadaluarsa.value.length > 0 ? d_tglKadaluarsa.value[0] : ''
                infoStokTgl.value = 'Qty Stok Per Tgl : ' + response.detail[0].qtyproduk
                item.stokByEd = response.detail[0].qtyproduk
                if (dataSelected.value.no != undefined) {
                    item.jumlah = dataSelected.value.jumlah
                    item.qtyprodukkonfirmasi = dataSelected.value.qtyprodukkonfirmasi ? dataSelected.value.qtyprodukkonfirmasi : dataSelected.value.jumlah
                    item.nilaiKonversi = dataSelected.value.nilaikonversi
                    d_satuan.value.forEach((elem:any) => {
                        if(elem.value.ssid == dataSelected.value.satuanstandarfk){
                            item.satuan = elem
                        }
                    });
                    // item.satuan = {label :  dataSelected.value.satuanstandar , value : dataSelected.value.satuanstandarfk}
                    response.detail.forEach(el => {
                        if(el.norec_spd == dataSelected.value.norec_spd){
                           item.stokByEd = el.qtyproduk 
                           item.infoStokTgl = 'Qty Stok Per Tgl : ' + el.qtyproduk 
                           item.tglKadaluarsa = {tglkadaluarsa : el.tglkadaluarsa} 
                        }
                    });
                }
                dataProdukDetail.value = response.detail
                item.stok = response.jmlstok / item.nilaiKonversi
                if (response.kekuatan == undefined || response.kekuatan == 0) {
                    response.kekuatan = 1
                }
                item.kekuatan = response.kekuatan
                item.sediaan = response.sediaan
                isLoading.value = false
            } else {
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
                item.jumlah = 0
            }
            isReady.value = false
        });

}

const getProdukListStok = async (e:any)=>{
    displayStok.value = false
    let namaproduk
    await useApi().get(`farmasi/get-stok-produk-by-ruangan?produkfk=${e}`).then((response)=>{
        response.forEach((element:any,i:any) => {
            element.no = i + 1
            namaproduk = element.namaproduk
        });
        dataSourceStokProduk.value = response
        infoStok.value = `List Stok Produk ${namaproduk} Per Ruangan`
        displayStok.value = true
    })

}

const clearInput = () => {
    delete item.produk
    delete item.asal
    delete item.satuan
    delete item.no
    delete item.satuanresep
    delete dataSelected.value
    delete item.tglKadaluarsa
    delete item.stokByEd
    infoStokTgl.value = ''
    dataSourceStokProduk.value = []
    item.qty = 1
    modalInput.value = false
    item.nilaiKonversi = 0
    item.stok = 0
    item.jumlah = 0
    item.jumlahbulat = item.jumlah
    isEdit.value = false

}
const DialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah Anda Yakin Akan Mengirim Barang ?',
        header: 'Konfirmasi Kirim Barang',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            save()

        },
        reject: () => { },
    })
}

const setNorecSPD = async () => {
    if (item.stok == 0) {
        item.jumlah = 0
        return;
    }

    dataProdukDetail.value.forEach((element:any) => {
        if(element.tgl == item.tglKadaluarsa.tglkadaluarsa){
            item.stokByEd = element.qtyproduk
            infoStokTgl.value = 'Qty Stok Per Tgl : ' + element.qtyproduk
            if (parseFloat(item.jumlah) * parseFloat(item.nilaiKonversi) <= parseFloat(element.qtyproduk)) {
                norecTerima.value = element.norec
                norecSPD.value = element.norec_spd
                hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi))
                hrgsdk.value = parseFloat(element.hargadiscount) * parseFloat(item.nilaiKonversi)
                item.hargaSatuan = hrg1.value
                item.hargaNetto = Math.round(parseFloat(element.harganetto) * parseFloat(item.nilaiKonversi))
                item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
                item.asal = { id: element.objectasalprodukfk, asalproduk: element.asalproduk }
            }else{
                H.alert('error','Stok Tidak Cukup')
                item.jumlah = 0
                return
            }
        }
    });
}


const changeSatuan = (e: any) => {
    item.nilaiKonversi = item.satuan.value.nilaikonversi
}
function back() {
    window.history.back()
}
onInit()


watch(
    () => item.jumlah,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            setNorecSPD()
        }
    }
)

watch(
    () => item.nilaiKonversi,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (item.stok > 0) {
                item.stok = parseFloat(item.stok) * (parseFloat(oldValue) / parseFloat(newValue))
                item.jumlahbulat = 0;
                item.jumlah = 0
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
            }
        }

    }
)

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout .form-outer {
    border: 1px solid transparent;
    background-color: transparent;
}

.button.v-button {
    padding: 8px 22px !important;
    height: 38px !important;
    line-height: 1.1 !important;
    font-size: 0.95rem !important;
    font-family: var(--font) !important;
    transition: all 0.3s !important;
}
</style>

