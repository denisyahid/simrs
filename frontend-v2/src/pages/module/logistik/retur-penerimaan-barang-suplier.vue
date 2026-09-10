<template>
    <ConfirmDialog />
    <div class="form-layout is-stacked">
        <div class="form-outer" style="margin-top: 15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Form Retur Penerimaan Barang Supplier</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="back()" >
                                Kembali 
                            </VButton>
                            <VButton type="button" rounded outlined :loading="btnLoadSave" :disabled="saveDisabled" color="primary" @click="saveRetur(item)" raised
                                icon="feather:save">
                                Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-body ">

                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-4">
                            <VField label="No SPPB / SPK">
                                <VPlaceload v-if="isPlaceLoad" height="30px" />
                                <VControl v-else icon="feather:bookmark">
                                    <VInput type="text" v-model="item.nospk" class="is-rounded" disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="No Terima">
                                <VPlaceload v-if="isPlaceLoad" height="30px" />
                                <VControl v-else icon="feather:bookmark">
                                    <VInput type="text" v-model="item.noterima" class="is-rounded" disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="No Dokumen">
                                <VPlaceload v-if="isPlaceLoad" height="30px" />
                                <VControl v-else icon="feather:bookmark">
                                    <VInput type="text" v-model="item.nodokumen" class="is-rounded" disabled />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-4">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Nama Supplier</VLabel>
                                <VPlaceload v-if="isPlaceLoad" height="30px" />
                                <VControl v-else icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.supplier" :options="d_suplier" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true"
                                        disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Gudang</VLabel>
                                <VPlaceload v-if="isPlaceLoad" height="30px" />
                                <VControl v-else icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.gudang" :options="d_Gudang" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true"
                                        disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Pegawai Penerima</VLabel>
                                <VPlaceload v-if="isPlaceLoad" height="30px" />
                                <VControl v-else icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.pegawaipenerima" :options="d_Pegawai" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true"
                                        disabled />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column is-12">
                    <VField>
                        <VLabel class="required-field">keterangan Retur</VLabel>
                        <VPlaceload v-if="isPlaceLoad" height="30px" />
                        <VControl v-else icon="feather:bookmark">
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
                    class="p-datatable-sm" editMode="cell"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                    <Column field="no" header="No"></Column>
                    <Column field="namaproduk" header="Nama Produk"></Column>
                    <Column field="satuan" header="Satuan"></Column>
                    <Column field="jumlah" header="Qty"></Column>
                    <Column field="qtyprodukretur" header="Qty Retur"></Column>
                    <Column field="hargasatuan" header="Harga Satuan"></Column>
                    <Column field="hargadiskon" header="Discount"></Column>
                    <Column field="nilaippn" header="PPN"></Column>
                    <Column field="total" header="Total"></Column>
                    <Column field="tglKadaluarsa" header="Tgl Kadaluarsa"></Column>
                    <Column :exportable="false" header="#" style="text-align: center">
                        <template #body="slotProps">
                            <VIconButton color="primary" @click="loadDetailPenerimaan(slotProps.data)"
                                v-tooltip.primary="'Retur'" circle icon="fas fa-undo"/>
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
            <div class="columns is-multiline">
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status primary">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">SUB TOTAL</span>
                        </div>
                        <!-- <small>{{ item.subtotal }}</small> -->
                        <small class="text-bold-custom h-100">{{
                            H.formatRp(item.totalsub, 'Rp.')
                        }}</small>
                    </VCardCustom>
                </div>
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status info">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">DISKON</span>
                        </div>
                        <small class="text-bold-custom h-100">{{
                            H.formatRp(item.discount, 'Rp.')
                        }}</small>
                    </VCardCustom>
                </div>
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status danger">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">PPN</span>
                        </div>
                        <small class="text-bold-custom h-100">{{
                            H.formatRp(item.ppnTotal, 'Rp.')
                        }}</small>
                    </VCardCustom>
                </div>
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status" color="danger">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">TOTAL</span>
                        </div>
                        <small class="text-bold-custom h-100">{{
                            H.formatRp(item.totalall, 'Rp.')
                        }}</small>
                    </VCardCustom>
                </div>
            </div>
        </div>

    </div>

    <VModal :open="modalRetur" title="Retur Resep" size="large" actions="right" @close="modalRetur = false,clear()">
        <template #content>
            <form class="modal-form">
                <div class="column">
                    <div class="columns">
                        <div class="column is-5">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="required-field">Nama Produk</VLabel>
                                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown disabled v-model="item.produk" :options="d_Produk" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih Produk" style="width: 100%;" :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="required-field">Satuan</VLabel>
                                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown disabled v-model="item.satuan" :options="d_Satuan" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel class="required-field">Konversi</VLabel>
                                <VControl>
                                    <input disabled v-model="item.konversi" type="number" class="input is-rounded"
                                        placeholder="Konversi" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel disabled class="required-field">Jumlah</VLabel>
                                <VControl>
                                    <input disabled v-model="item.jumlah" type="number" class="input is-rounded"
                                        placeholder="QTY" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="columns">
                        <div class="column is-3">
                            <VField>
                                <VLabel class="required-field">Jumlah Retur</VLabel>
                                <VControl>
                                    <input v-model="item.jmlretur" type="number" class="input is-rounded"
                                        placeholder="Jumlah Retur" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <VLabel class="required-field">Harga Satuan</VLabel>
                                <VControl>
                                    <input disabled v-model="item.hargasatuan" type="number" class="input is-rounded"
                                        placeholder="Harga Satuan" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-3">
                            <VField label="Jumlah Diskon(%)">
                                <VControl>
                                    <input disabled v-model="item.persendiscount" type="number" class="input is-rounded"
                                        placeholder="Jumlah Diskon" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-3">
                            <VField label="Harga Diskon">
                                <VControl>
                                    <input disabled v-model="item.hargadiskon" type="text" class="input is-rounded"
                                        placeholder="Harga Diskon" />
                                </VControl>
                            </VField>
                        </div>

                    </div>
                </div>
                <div class="column">
                    <div class="columns">
                        <div class="column is-3">
                            <VField label="PPN(%)">
                                <VControl>
                                    <input disabled v-model="item.persenppn" type="number" class="input is-rounded"
                                        placeholder="PPN(%)" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="PPN">
                                <VControl>
                                    <input disabled v-model="item.nilaippn" type="text" class="input is-rounded"
                                        placeholder="PPN" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VDatePicker v-model="item.tglkadaluarsa" color="green">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VLabel>Tanggal Kadaluarsa</VLabel>
                                        <VControl icon="feather:calendar">
                                            <VInput disabled type="text" placeholder="Select a date" class="is-rounded"
                                                :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3">
                            <VField label="No Batch">
                                <VControl>
                                    <input disabled v-model="item.nobatch" type="text" class="input is-rounded"
                                        placeholder="No Batch" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column pt-0">

                    <div class="columns">
                        <div class="column is-5">
                            <VField label="Keterangan">
                                <VControl>
                                    <input disabled v-model="item.keterangan" type="text" class="input is-rounded"
                                        placeholder="Keterangan" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-4">
                            <VField label="Sub Total">
                                <VControl>
                                    <input disabled v-model="item.subTotal" type="float" class="input is-rounded"
                                        placeholder="Sub Total" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </form>
        </template>

        <template #action>
            <VButton :disabled="isDisabled" color="primary" @click="subChangeRetur(item)" raised>Simpan</VButton>
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
let NOREC_SP: any = useRoute().query.norec as string
let item: any = reactive({})

const { y } = useWindowScroll()
const isStuck = computed(() => {return y.value > 30})
const d_Pegawai = ref([])
const d_SumberDana = ref([])
const d_kelompokBarang = ref([])
const d_koordinator = ref([])
const d_komit = ref([])
const d_Gudang = ref([])
const d_suplier = ref([])
const d_Produk: any = ref([])
const d_Satuan: any = ref([])
const dataSource: any = ref([])
const isPlaceLoad = ref(false)
const modalRetur = ref(false)
const isDisabled = ref(false)
const saveDisabled = ref(false)
const btnLoadSave = ref(false)


const loadData = async () => {
    isPlaceLoad.value = true
    await listDataCombo()
    await loadDataRetur()
}

const loadDataRetur = async () => {
    await useApi().get(`/logistik/get-detail-penerimaan?norec=${NOREC_SP}`).then((response) => {
        let dataPenerimaan = response.detailterima
        let detailPenerimaan = response.pelayananPasien
       
        item.noterima = dataPenerimaan.nostruk
        item.nodokumen = dataPenerimaan.nofaktur
        item.norecRetur = dataPenerimaan.norecretur
        d_suplier.value.forEach((e: any) => {
            if (e.value.id == dataPenerimaan.objectrekananfk) {
                item.supplier = e
                return
            }
        });
        d_Gudang.value.forEach((element: any) => {
            if (element.value == dataPenerimaan.id) {
                item.gudang = element
                return
            }
        });
        d_Pegawai.value.forEach((e: any) => {
            if (e.value.id == dataPenerimaan.pgid) {
                item.pegawaipenerima = e
                return
            }
        });

        detailPenerimaan.forEach((element: any) => {
            element.total = element.totalall
            element.tglKadaluarsa = H.formatDate(element.tglkadaluarsa, 'DD-MMM-YYYY')
        });
        dataSource.value = detailPenerimaan
        isPlaceLoad.value = false
    })
    count()
}

const listDataCombo = async () => {
    await useApi().get('logistik/penerimaan-barang/get-data-combo').then((response) => {
        d_komit.value = response.pembuatkomit.map((e: any) => {
            return { label: e.namalengkap, value: e }
        })
        d_Pegawai.value = response.pegawai.map((e: any) => {
            return { label: e.namalengkap, value: e }
        })
        d_SumberDana.value = response.sumberdana.map((e: any) => {
            return { label: e.asalproduk, value: e.id }
        })
        d_kelompokBarang.value = response.kelompokbarang.map((e: any) => {
            return { label: e.kelompokproduk, value: e.id }
        })
        d_Gudang.value = response.ruangan.map((e: any) => {
            return { label: e.namaruangan, value: e.id }
        })
        d_suplier.value = response.suplier.map((e: any) => {
            return { label: e.namarekanan, value: e }
        })
    })
}

const loadDetailPenerimaan = (e: any) => {

    modalRetur.value = true
    d_Produk.value = [{ label: e.namaproduk, value: e.produkfk }]
    d_Satuan.value = [{ label: e.satuan, value: e.satuanstandarfk }]
    d_Produk.value.forEach((element: any) => { item.produk = element });
    d_Satuan.value.forEach((element: any) => { item.satuan = element });
    item.konversi = e.nilaikonversi
    item.jumlah = e.jumlah
    item.no = e.no
    item.asalproduk = e.asalproduk
    item.asalprodukfk = e.asalprodukfk
    item.hargasatuan = e.hargasatuan
    item.persendiscount = e.persendiscount
    item.hargadiskon = e.hargadiskon
    item.persenppn = e.persenppn
    item.nilaippn = e.nilaippn
    item.ruanganfk = e.ruanganfk
    item.tglkadaluarsa = e.tglKadaluarsa
    item.nobatch = e.nobatch
    item.keterangan = e.keterangan
    item.subTotal = e.totalall ? e.totalall : e.total
    item.stuckTotal = e.totalall ? e.totalall : parseFloat(e.hargasatuan)*parseFloat(e.jumlah) - parseFloat(e.hargadiskon) + parseFloat(e.nilaippn)
}

const subChangeRetur = (e: any) => {
    let data: any = {}
    dataSource.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
            data.asalproduk = e.asalproduk
            data.asalprodukfk = e.asalprodukfk
            data.hargadiskon = e.hargadiskon
            data.hargasatuan = e.hargasatuan
            data.jumlah = e.jumlah
            data.keterangan = e.keterangan
            data.namaproduk = e.produk.label
            data.nilaikonversi = e.konversi
            data.no = e.no
            data.nobatch = e.nobatch
            data.persendiscount = e.persendiscount
            data.persenppn = e.persenppn
            data.resultJml = e.jumlah
            data.nilaippn = e.nilaippn
            data.produkfk = e.produk.value
            data.qtyprodukretur = parseInt(e.jmlretur)
            data.ruanganfk = e.ruanganfk
            data.satuan = e.satuan.label
            data.satuanstandarfk = e.satuan.value
            data.satuanview = e.satuan.label
            data.satuanviewfk = e.satuan.value
            data.tglKadaluarsa =  H.formatDate(e.tglkadaluarsa, 'DD-MMM-YYYY') 
            data.total = e.subTotal
            dataSource.value[i] = data
        }
    })
    count()
    modalRetur.value = false
}
const count = () => {

    let totalsub = 0
    let discount = 0
    let ppn = 0
    let total = 0
    dataSource.value.forEach((element: any) => {
        let subTotal = element.subtotal ? element.subtotal : parseFloat(element.jumlah) * parseFloat(element.hargasatuan)
        totalsub = totalsub + parseFloat(subTotal)
        discount = element.hargadiskon === '' ? discount : discount + parseFloat(element.hargadiskon)
        ppn = element.nilaippn === '' ? ppn : ppn + parseFloat(element.nilaippn)
        total = totalsub
    })
    item.totalsub = totalsub
    item.discount = discount
    item.ppnTotal = ppn
    item.totalall = totalsub - discount + ppn
}

const clear = ()=>{
   delete item.konversi
   delete item.jumlah 
   delete item.no
   delete item.asalproduk 
   delete item.asalprodukfk 
   delete item.hargasatuan 
   delete item.persendiscount
   delete item.hargadiskon 
   delete item.jmlretur 
   delete item.persenppn
   delete item.nilaippn 
   delete item.ruanganfk
   delete item.tglkadaluarsa 
   delete item.nobatch 
   delete item.keterangan 
   delete item.subTotal
   delete item.stuckTotal 
}

const saveRetur = async (e:any)=>{
    let qtyRetur = 0
    dataSource.value.forEach((element:any)=>{
        qtyRetur = qtyRetur + parseInt(element.qtyprodukretur)
    })
    if(qtyRetur == 0){
        H.alert('error','Tidak ada data yang diretur')
        return
    }
    if(!item.keteranganretur){
        H.alert('error','Keterangan retur harus diisi')
        return
    }

    let objSave = {
        struk: {
            keterangan: item.keteranganretur,
            namarekanan: item.supplier.label,
            namaruangan: item.gudang.label,
            nofaktur : item.nodokumen,
            noorder: "-",
            norecRetur: item.norecretur ? item.norecretur : '',
            nostruk: NOREC_SP,
            noterima: item.noterima,
            pegawaimenerimafk: item.pegawaipenerima.value.id,
            qtyproduk: dataSource.value.length,
            rekananfk: item.supplier.value.id,
            ruanganfk: item.gudang.value,
        },
        details: dataSource.value
    }
    btnLoadSave.value = true
    await useApi().post('logistik/penerimaan-barang/retur-penerimaan-suplier',objSave).then(()=>{
        btnLoadSave.value = false
        back()
    }).catch((e)=>{
        btnLoadSave.value = false
    })
}

const back = ()=>{
    window.history.back(); 
}

watch(() => item.jmlretur,
    () => {
        if (item.jmlretur > item.jumlah) {
            H.alert('error', 'Jumlah retur melebihi batas')
            item.subTotal = item.stuckTotal
            isDisabled.value = true
            return
        }

        if (item.jmlretur) {
        
            let nilaiRetur = parseFloat(item.hargasatuan) * parseFloat(item.jmlretur) - parseFloat(item.hargadiskon) + parseFloat(item.nilaippn)
            let priceAftRetur = parseFloat(item.stuckTotal) - nilaiRetur
            if (isNaN(priceAftRetur)) {
                item.subTotal = item.stuckTotal
                isDisabled.value = true
            } else {
                item.subTotal = priceAftRetur
                isDisabled.value = false
            }

        } else {
            item.subTotal = item.stuckTotal
            isDisabled.value = true
        }
    }
)

loadData()


</script>
    
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
// @import '/@src/scss/custom/config';
</style>
    