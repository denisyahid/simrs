<template>
    <ConfirmDialog />
    <div class="form-layout is-stacked">
        <div class="form-outer" style="margin-top: 15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Form Retur Kirim Barang</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="back()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined :loading="btnLoadSave" :disabled="saveDisabled"
                                color="primary" @click="saveRetur(item)" raised icon="feather:save">
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
                            <VField label="No Kirim">
                              <VPlaceload v-if="isPlaceLoad" height="30px" />
                                <VControl v-else ic0on="feather:bookmark">
                                    <VInput type="text" v-model="item.noKirim" class="is-rounded" disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VDatePicker v-model="item.tglKirim" color="green">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VLabel>Tanggal Kadaluarsa</VLabel>
                                        <VPlaceload v-if="isPlaceLoad" height="30px" />
                                        <VControl  v-else icon="feather:calendar">
                                            <VInput type="text" placeholder="Select a date" class="is-rounded"
                                                :value="inputValue" v-on="inputEvents" disabled />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-4">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Jenis Kirim</VLabel>
                                <VPlaceload v-if="isPlaceLoad" height="30px" />
                                <VControl  v-else icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.jenisKirim" :options="d_JenisKirim" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" disabled />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-6">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Ruangan Pengirim</VLabel>
                                <VPlaceload v-if="isPlaceLoad" height="30px" />
                                <VControl  v-else icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.ruanganpengirim" :options="d_RuanganPengirim"
                                        optionLabel="label" class="is-rounded" placeholder="Pilih data" style="width: 100%;"
                                        :filter="true" disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Raungan Pemesan</VLabel>
                                <VPlaceload v-if="isPlaceLoad" height="30px" />
                                <VControl  v-else icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.ruanganpemesan" :options="d_RuanganTujuan" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" disabled />
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
                    <Column field="namaproduk" header="Produk"></Column>
                    <Column field="satuanstandar" header="Satuan"></Column>
                    <Column field="jmlstok" header="Stok"></Column>
                    <Column field="qtyorder" header="Qty"></Column>
                    <Column field="qtyreturSebelumnya" header="Qty Retur Sebelumnya"></Column>
                    <Column field="qtyreturSekarang" header="Qty Retur Sekarang"></Column>
                    <Column :exportable="false" header="#" style="text-align: center">
                        <template #body="slotProps">
                            <VIconButton color="primary" @click="loadDetailPenerimaan(slotProps.data)"
                                v-tooltip.primary="'Retur'" circle icon="fas fa-undo" />
                        </template>
                    </Column>
                </DataTable>
            </VCard>
        </div>

    </div>

    <VModal :open="modalRetur" title="Retur Resep" size="medium" actions="right" @close="modalRetur = false, clear()">
        <template #content>
            <form class="modal-form">
                <div class="column">
                    <div class="columns">
                        <div class="column is-7">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="required-field">Nama Produk</VLabel>
                                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown disabled v-model="item.produk" :options="d_Produk" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih Produk" style="width: 100%;" :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-5">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="required-field">Satuan</VLabel>
                                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown disabled v-model="item.satuanKonversi" :options="d_Satuan" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="columns">
                        <div class="column is-4">
                            <VField label="Konversi">
                                <VControl>
                                    <input disabled v-model="item.konversi" type="number" class="input is-rounded"
                                        placeholder="QTY" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Stok">
                                <VControl>
                                    <input disabled v-model="item.stok" type="number" class="input is-rounded"
                                        placeholder="Stok" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Jumlah">
                                <VControl>
                                    <input disabled v-model="item.jumlah" type="number" class="input is-rounded"
                                        placeholder="QTY" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel class="required-field">Qty Retur</VLabel>
                        <VControl>
                            <input v-model="item.qtyRetur" type="number" class="input is-rounded"
                                placeholder="QTY" />
                        </VControl>
                    </VField>
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
import { useViewWrapper } from '/@src/stores/viewWrapper'
import ConfirmDialog from 'primevue/confirmdialog'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dropdown from 'primevue/dropdown';
import { elements } from '/@src/data/landing/components'
useHead({
    title: `Retur Kirim Barang - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let NOREC: any = useRoute().query.norec as string
let item: any = reactive({})

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const d_JenisKirim: any = ref([])
const d_RuanganPengirim = ref([])
const d_RuanganTujuan = ref([])
const d_koordinator = ref([])
const d_komit = ref([])
const d_Gudang = ref([])
const d_Konversi = ref([])
const d_Produk: any = ref([])
const d_Satuan: any = ref([])
const dataSource: any = ref([])
const isPlaceLoad = ref(false)
const modalRetur = ref(false)
const isDisabled = ref(false)
const saveDisabled = ref(false)
const btnLoadSave = ref(false)
const itemSelected:any = ({})


const loadData = async () => {
    isPlaceLoad.value = true
    await listDataCombo()
    await loadDataRetur()
}

const loadDataRetur = async () => {
    await useApi().get(`/logistik/distribusi-detail?norec=${NOREC}`).then((response) => {
        let information = response.head
        response.detail.forEach(element => {
            element.qtyreturSebelumnya = element.qtyretur
        });
        dataSource.value = response.detail

        d_JenisKirim.value.forEach((element: any) => {
            if (information.jenispermintaanfk == element.value.id) {
                item.jenisKirim = element
            }
        });
        d_RuanganPengirim.value.forEach((element: any) => {
            if (information.id == element.value.id) {
                item.ruanganpengirim = element
            }
        })
        d_RuanganTujuan.value.forEach((element: any) => {
            if (information.ruid2 == element.value.id) {
                item.ruanganpemesan = element
            }
        })

        item.noKirim = information.nokirim
        item.tglKirim = information.tglkirim
    })
    isPlaceLoad.value = false
}

const fetchProduk = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/produk_m?select=id,namaproduk&param_search=id&query=${filter}&limit=10`
  ).then((response) => {
    d_Produk.value = response
  })
}

const listDataCombo = async () => {
    await useApi().get('/logistik/distribusi-barang-cbo').then((response) => {
        d_JenisKirim.value = response.jenis.map((e: any) => {
            return { label: e.jenis, value: e }
        })
        d_RuanganPengirim.value = response.ruangan.map((e: any) => {
            return { label: e.namaruangan, value: e }
        })
        d_RuanganTujuan.value = response.rutu.map((e: any) => {
            return { label: e.namaruangan, value: e }
        })
        // d_Produk.value = response.produk.map((e: any) => {
        //     return { label: e.namaproduk, value: e }
        // })
        d_Satuan.value = response.satuankonversi.map((e: any) => {
            return { label: e.satuanstandar, value: e }
        })
    })
    isPlaceLoad.value = false
}

const loadDetailPenerimaan = (e: any) => {
    // console.log(e)
    // debugger
    d_Produk.value.forEach((element:any) => {
        if(element.value.id == e.produkfk){
            item.produk = element
        }
    });
    d_Satuan.value.forEach((element: any) => {
        if(element.value.id == e.satuanstandarfk){
            item.satuanKonversi = element
        }
    });

    item.konversi = e.nilaikonversi
    item.stok = e.stock
    item.no = e.no
    item.jumlah = e.jumlah
    item.qtyRetur = e.qtyreturSekarang
    itemSelected.value = e
    modalRetur.value = true
}

const subChangeRetur = (e: any) => {
    let data: any = {}
    let selected = itemSelected.value
    console.log(selected)
    dataSource.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
            data.asalproduk = selected.asalproduk
            data.asalprodukfk = selected.asalprodukfk
            data.aturanpakai = selected.aturanpakai
            data.aturanpakaifk = selected.aturanpakaifk
            data.dosis = selected.dosis
            data.generik = selected.generik
            data.hargadiscount = selected.hargadiscount
            data.hargajual = selected.hargajual
            data.hargasatuan = selected.hargasatuan
            data.harganetto = selected.harganetto
            data.jasa = selected.jasa
            data.jeniskemasan = selected.jeniskemasan
            data.jeniskemasanfk = selected.jeniskemasanfk
            data.jenisobatfk = selected.jenisobatfk
            data.jmldosis = selected.jmldosis
            data.jmlstok = selected.jmlstok
            data.jumlah = selected.jumlah
            data.kdproduk = selected.kdproduk
            data.kelasfk = selected.kelasfk
            data.namaproduk = selected.namaproduk
            data.nilaikonversi = selected.nilaikonversi
            data.no = selected.no
            data.nostrukterimafk = selected.nostrukterimafk
            data.noregistrasifk = selected.noregistrasifk
            data.qtyorder = selected.qtyorder
            data.qtyreturSebelumnya = selected.qtyretur ? selected.qtyretur : selected.qtyreturSebelumnya
            data.qtyreturSekarang = e.qtyRetur
            data.produkfk = selected.produkfk
            data.route = selected.route
            data.routefk = selected.routefk
            data.ruanganfk = selected.ruanganfk
            data.satuanstandar = selected.satuanstandar
            data.satuanstandarfk = selected.satuanstandarfk
            data.satuanview = selected.satuanview
            data.satuanviewfk = selected.satuanviewfk
            data.stock = selected.stock
            data.tglregistrasi = selected.tglregistrasi
            data.total = selected.total
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

const clear = () => {
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

const saveRetur = async (e: any) => {
    let qtyRetur = 0
    dataSource.value.forEach((element: any) => {
        qtyRetur = qtyRetur + parseInt(element.qtyretur)
    })
    if(!item.keteranganretur){
      H.alert("error","Keterangan Retur Wajib Diisi")
      return
    }
    if (qtyRetur == 0) {
        H.alert('error', 'Tidak ada data yang diretur')
        return
    }

    let objSave = {
        strukkirim: {
            keteranganlainnyakirim: item.keteranganretur,
            objectruanganfk: item.ruanganpengirim.value.id,
            objectruangantujuanfk: item.ruanganpemesan.value.id,
            ruangan: item.ruanganpengirim.label,
            ruangantujuan: item.ruanganpemesan.label,
            jenispermintaanfk: item.jenisKirim.value.id,
            qtydetailjenisproduk: 0,
            tglkirim: H.formatDate(e.tglKirim,'YYYY-MM-DD hh:mm:ss'),
            norecRetur: item.norecretur ? item.norecretur : '',
            qtyproduk: dataSource.value.length,
            noreckirim: NOREC ,
            norec_apd: 0,
        },
        details: dataSource.value
    }
    btnLoadSave.value = true
    await useApi().post('/logistik/retur-distribusi-barang-save', objSave).then(() => {
        btnLoadSave.value = false
        back()
    }).catch((e) => {
        btnLoadSave.value = false
    })
}

const back = () => {
    window.history.back();
}

watch(() => item.qtyRetur,
    () => {
        if (item.qtyRetur > item.jumlah) {
            H.alert('error', 'Jumlah retur melebihi batas')
            item.subTotal = item.stuckTotal
            isDisabled.value = true
            return
        }

        if (item.qtyRetur > 0) {
            isDisabled.value = false

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
