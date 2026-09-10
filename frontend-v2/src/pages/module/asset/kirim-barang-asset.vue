
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
                                <VButton icon="feather:save" type="submit" color="primary" raised @click="save()"
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
                                                    <VLabelText>Ruangan Asal</VLabelText>
                                                    <VControl icon="feather:search" :loading="isLoadData">
                                                        <Multiselect mode="single" v-model="item.ruanganPengirim"
                                                            :options="d_ruangan" placeholder="Pilih data" :searchable="true"/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Ruangan Tujuan</VLabelText>
                                                    <VControl icon="feather:search" :loading="isLoadData">
                                                        <Multiselect mode="single" v-model="item.ruanganTujuan"  
                                                            :options="d_rutu" placeholder="Pilih data" :searchable="true"/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <!-- <div class="column is-2">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Jenis Kirim</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.jenisKirim"
                                                            :options="d_jenisKirim" placeholder="Pilih data"
                                                            :searchable="true"
                                                            :disabled="NOREC_KIRIM || NOREC_ORDER ? true : false" />
                                                    </VControl>
                                                </VField>
                                            </div> -->
                                            
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
                                                                @click="editRow(slotProps.data)"/>
                                                            <Button icon="pi pi-trash"
                                                                class="p-button-rounded p-button-danger"
                                                                @click="hapusRow(slotProps.data)" />
                                                        </template>
                                                    </Column>

                                                    <Column field="no" header="No"></Column>
                                                    <Column field="produkname" header="Deskripsi"></Column>
                                                    <Column field="noAsset" header="No Asset"></Column>
                                                    <Column field="stok" header="Stok"></Column>
                                                    <Column field="jumlah" header="Jumlah"></Column>
                                                    <Column field="kondisiaset" header="Kondisi Asset"/>

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

        <VModal :open="modalInput" title="Add Barang" size="big" actions="right" @close="modalInput = false, clearInput">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Produk</VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.produkfk" :options="d_Produk" :optionLabel="'label'"
                                        class="is-rounded" placeholder="Pilih Produk" style="width: 100%;" showClear
                                        :filter="true" @change="getValue(item.produkfk)" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel>No Asset</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput v-model="item.noAsset" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel>Stok</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.stok" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel>Jumlah</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.jumlah" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel>Kondisi Produk</VLabel>
                                    <VControl icon="feather:search" :loading="isLoadData">
                                        <Dropdown v-model="item.kondisiaset" :options="d_KondisiAsset" :optionLabel="'label'"
                                            class="is-rounded" placeholder="Pilih Produk" style="width: 100%;" showClear
                                            :filter="true" />
                                    </VControl>
                                </VField>
                            </div>
                        <!-- <div class="column is-4">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Satuan</VLabel>
                                <VControl icon="feather:search">
                                    <Dropdown v-model="item.satuan" :options="d_satuan" :optionLabel="'label'"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear
                                        :filter="true" @change="changeSatuan(item.satuan)" />
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
                                <VLabel>Stok</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.stok" placeholder="Stok" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <VLabel>Jumlah</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.jumlah" placeholder="Jumlah" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div> -->
                        <!-- <div class="column is-3" v-if="NOREC_ORDER">
                            <VField>
                                <VLabel>Qty Konfirmasi</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.qtyprodukkonfirmasi" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div> -->
                    </div>
                </form>
            </template>
            <template #action>
                <VButton icon="feather:plus" @click="tambahItem()" color="primary" raised :disabled="isReady">Tambah</VButton>
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
import PrimeVue from 'primevue/config';
import DataTable from 'primevue/datatable';
import Dropdown from 'primevue/dropdown';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Button from 'primevue/button';
import moment from 'moment'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'

const TITLE_PAGE = 'Kirim Barang Aset'
useHead({
    title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)

let RUANGANFK: any = useRoute().query.ruanganfk as string
let NOREC_ASSET: any = useRoute().query.norecasset as string
const modalInput = ref(false)

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
const d_Produk: any = ref([])
const d_satuan: any = ref([])
const d_jenisKirim: any = ref([])
const d_KondisiAsset: any = ref([])
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
const isLoadData: any = ref(true)
const disabledJenis: any = ref(false)
const idDisabledBtn: any = ref(false)
const btnEditLoad: any = ref(false)
const dataSelected: any = ref({})
const disTanggal: any = ref(false)
const isReady: any = ref(false)



const save =  ()=>{
    
     if (!item.ruanganPengirim) {
        H.alert('error','Pilih Ruanganan Pengirim!!')
        return
    }
    if (!item.ruanganTujuan) {
         H.alert('error', 'Pilih Ruanganan Tujuan!!')
        return
    }
    // if (!item.jenisKirim) {
    //      H.alert('error', 'Pilih Jenis Kirim !!')
    //     return
    // }
    if (dataSource.value.length == 0) {
        H.alert('error', 'Pilih Produk terlebih dahulu!!')
        return
    }
    var noAsset = '';
    if (item.noAsset != undefined) {
        noAsset = item.noAsset;
    }
    var strukkirimaset = {
        noreckirim: '',
        norecAsset: NOREC_ASSET,
        objectruanganfk: item.ruanganPengirim,
        objectruangantujuanfk: item.ruanganTujuan,
        jenispermintaanfk: item.jenisKirim,
        keteranganlainnyakirim: 'Kirim Barang Aset',
        tglkirim: H.formatDate(item.tglkirim,'YYYY-MM-DD HH:mm:ss'),
        norecOrder: '',
    }
    var objSave =
    {
        strukkirimaset: strukkirimaset,
        details: dataSource.value
    }
    console.log(objSave)
     useApi().post('asset/simpan-kirimbarang-aset', objSave).then((response)=>{

    })

   
}


const tambahItem = ()=>{
    if(item.stok < item.jumlah){
        H.alert('error','Jumlah Melebihi Stok yang tersedia')
        return
    }
    let data: any = {};
    if (item.no != undefined) {
        data2.value.forEach((element: any, i: any) => {
            if (element.no == item.no) {
                data.no = item.no
                data.noAsset = item.noAsset
                data.stok = item.stok
                data.jumlah = item.jumlah
                data.d_produk =  item.produkfk
                data.d_kondisiaset = item.kondisiaset
                data.produkname = item.produkfk.label
                data.kondisiasetfk = item.kondisiaset.id
                data.kondisiaset = item.kondisiaset.label
                data2.value[i] = data;
            }
        });

    } else {
        data = {
            no: data2.value.length == 0 ? 1 : data2.value.length + 1,
            noAsset : item.noAsset,
            stok : item.stok,
            jumlah : item.jumlah,
            d_produk : item.produkfk,
            d_kondisiaset : item.kondisiaset,
            produkfk : item.produkfk.id,
            produkname : item.produkfk.label,
            kondisiasetfk : item.kondisiaset.id,
            kondisiaset : item.kondisiaset.label,
        }
        data2.value.push(data)
    }
    dataSource.value = data2.value
    clearInput()
}

const getData = async () => {

    await useApi().get(`/asset/get-produk-kirim?ruanganId=${RUANGANFK}`).then((response) => {
        d_Produk.value = response[0].data.map((e: any) => {
            return { label: e.namaproduk, id: e.id, default: e }
        })

    })
}

const getCombo = async () => {

    await useApi().get(`/logistik/distribusi-barang-cbo`).then((response) => {
        d_ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
        d_rutu.value = response.rutu.map((e: any) => { return { label: e.namaruangan, value: e.id } })
        d_jenisKirim.value = response.jenis.map((e: any) => { return { label: e.jenis, value: e.id } })
        d_KondisiAsset.value = response.kondisiaset.map((e: any) => {return { label: e.name, id: e.id, }})
        // console.log(response)
    })
    isLoadData.value = false
}

const addPopUp = () => {
    modalInput.value = true
}

const getValue = (e: any) => {
    console.log(e)
    item.noAsset = e.default.noregisteraset
    item.stok = e.default.qtyprodukaset
}

const editRow = (e:any)=>{
    console.log(e)
    item.no = e.no
    item.stok = e.stok
    item.produkfk = e.d_produk
    item.noAsset = e.noAsset
    item.jumlah = e.jumlah
    item.kondisiaset = e.d_kondisiaset
    // dataSelected.value = e
    modalInput.value = true
}

const clearInput = () => {
    delete item.produkfk
    delete item.noAsset
    delete item.jumlah
    delete item.no
    delete item.stok
    delete item.kondisiaset
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

getData()
getCombo()

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

