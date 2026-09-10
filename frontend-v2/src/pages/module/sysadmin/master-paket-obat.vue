<template>
    <ConfirmDialog />

    <div class="column is-12">
        <VCard style="padding-bottom: 0px">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="columns p-2">
                        <div class="column is-10">
                            <label class="title-page">Paket Obat</label>
                        </div>
                        <div class="column is-2" style="margin-left: -15rem;">
                            <VButton color="primary" raised @click="add()">
                                <span class="icon">
                                    <i aria-hidden="true" class="fas fa-plus"></i>
                                </span>
                                <span> Tambah Data</span>
                            </VButton>
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column is-9">

                        <DataTable :value="dataSource" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="loadSearch"
                            class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                            sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                            <Column expander style="width: 5rem" />
                            <!-- <Column field="id" header="No"></Column> -->
                            <Column field="paketId" header="Nomor Paket" style="width: 10rem;"></Column>
                            <Column field="namapaket" header="Nama Paket"></Column>
                            <Column :exportable="false" header="#" style="text-align: center; width: 10rem;">
                                <template #body="slotProps">
                                    <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined
                                        raised v-tooltip.top="'Edit'" @click="editPopUp(slotProps.data)" :loading="btnLoadEdit">
                                    </VIconButton>
                                    <VIconButton type="button" icon="fas fa-trash" class="mr-3" color="danger" circle outlined
                                        raised v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                                    </VIconButton>

                                </template>
                            </Column>
<!--                             
                            <Column :exportable="false" header="#" style="text-align: center;">
                                <template #body="slotProps">
                                    <VDropdown icon="feather:more-vertical" spaced right>
                                        <template #content>
                                            
                                            
                                            
                                            <a role="menuitem" style="background:#ED1C24" class="dropdown-item is-media"
                                                @click="dialogConfirm(slotProps.data)">
                                                <div class="icon">
                                                    <i aria-hidden="true" style="color:whitesmoke"
                                                        class="lnil lnil-trash-can-alt"></i>
                                                </div>
                                                <div class="meta">
                                                    <span style="color:whitesmoke">Hapus Paket</span>
                                                </div>
                                            </a>
                                            
                                        </template>
                                    </VDropdown>
                                </template>
                            </Column> -->
                            
                            <template #expansion="slotProps">
                                <div class="p-3">
                                    <DataTable :value="slotProps.data.details" :rows="10" showGridlines
                                        class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px"
                                        sortMode="multiple">
                                        <Column field="no" header="No" style="width: 5rem;" />
                                        <Column field="namaproduk" header="Nama Produk" style="width: 15rem;" />
                                        <Column field="satuanresep" header="Satuan" style="width: 10rem;" />
                                        <Column field="jumlah" header="Jumlah" style="text-align: center; width: 8rem;" />
                                        <Column field="aturanpakai" header="Aturan Pakai" style="width: 12rem;" />
                                    </DataTable>
                                </div>



                            </template>
                        </DataTable>
                    </div>
                    <div class="column is-3">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <VField>
                                    <VControl icon="feather:search">
                                        <input v-model="item.namapaket" v-on:keyup.enter="filter()" type="text"
                                            class="input is-rounded" placeholder="Filter Paket..." />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <h3 class="title is-5 mb-2 mr-1">Filters</h3>
                            </div>
                            <div class="column is-6">
                                <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined
                                    raised>
                                    Clear All
                                </a>
                            </div>

                            <!-- <div class="column is-12">
                                <VField class="is-autocomplete-select">
                                    <VLabel>Asal Produk</VLabel>
                                    <VControl icon="feather:search">
                                        <Multiselect mode="single" v-model="item.asalProduk" :options="listAsalProduk"
                                            placeholder="Pilih asal" :searchable="true" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField class="is-autocomplete-select">
                                    <VLabel>Ruangan</VLabel>
                                    <VControl icon="feather:search">
                                        <Multiselect mode="single" v-model="item.ruangan" :options="listRuanganStok"
                                            placeholder="Pilih ruangan" :searchable="true" />
                                    </VControl>
                                </VField>
                            </div> -->
                            <div class="column is-12">
                                <VButton @click="filter()" :loading="isLoading" type="button" icon="feather:search"
                                class="is-fullwidth mr-3" color="info" raised>
                                Pencarian
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
            </VCard>
        </VCard>
    </div>


    <VModal :open="modalKirim" title="Tambah Data Paket Obat" actions="right" size="big" @close="modalKirim = false">
        <template #content>
            <form class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VCard>
                            <div class="column is-6">
                                <VField label="Nama Paket">
                                    <VControl icon="feather:bookmark">
                                        <VInput type="text" v-model="item.keterangan" placeholder="Keterangan" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="columns is-multiline p-1">
                                <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                                    <VCard class="is-grey">
                                        <div class="columns is-multiline p-1">
                                            <div class="column is-4">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabel>Produk</VLabel>
                                                    <VControl icon="feather:search" class="prime-auto-select">
                                                        <AutoComplete v-model="item.produk" :suggestions="d_produk"
                                                            @complete="fetchProduk($event)" :optionLabel="'namaproduk'"
                                                            :dropdown="true" :minLength="3" rounded :appendTo="'body'"
                                                            :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                                                            placeholder="ketik untuk mencari..." />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-2">
                                                <VField>
                                                    <VLabel>Aturan Pakai</VLabel>
                                                    <VControl icon="feather:search" class="prime-auto-select">
                                                        <Dropdown v-model="item.aturanPakai" :options="d_aturanPakai"
                                                            :optionLabel="'aturanpakai'" placeholder="Pilih data"
                                                            style="width: 100%;" showClear :filter="true" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabel>Satuan Resep</VLabel>
                                                    <VControl icon="feather:search" class="prime-auto-select">
                                                        <Dropdown v-model="item.satuanresep" :options="d_satuanResep"
                                                            :optionLabel="'satuanresep'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" />
                                                    </VControl>
                                                    <!-- <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.satuanresep" :options="d_satuanResep"
                                        placeholder="Pilih data" :searchable="true" />
                                </VControl> -->
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField label="Jumlah">
                                                    <VControl icon="feather:bookmark">
                                                        <VInput type="number" v-model="item.qtyproduk"
                                                            placeholder="Jumlah Produk" class="is-rounded" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-1 mt-3" style="margin-top: 1rem;">
                                                <VIconButton v-if="index > 0" outlined type="button" raised circle
                                                    class="is-pulled-right" icon="feather:trash" @click="removeItem(index)"
                                                    color="danger">
                                                </VIconButton>
                                            </div>
                                            <div class="column is-1 is-flex mt-3" style="margin-top: 1rem;">
                                                <VButton type="button" rounded outlined color="info" raised
                                                    icon="feather:plus" @click="addNewItem()"> Tambah
                                                </VButton>
                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                            </div>
                        </VCard>
                    </div>
                </div>
            </form>
        </template>
        <template #action>
            <VButton icon="feather:plus" @click="tambah()" :loading="isLoading" color="primary" raised>Simpan
            </VButton>
        </template>
    </VModal>


    <VModal :open="modalEdit" title="Edit Data Paket Obat" actions="right" size="big" @close="modalEdit = false">
        <template #content>
            <form class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VCard>
                            <div class="column is-6">
                                <VField label="Nama Paket">
                                    <VControl icon="feather:bookmark">
                                        <VInput type="text" v-model="item.keterangan" placeholder="Keterangan" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="columns is-multiline p-1">
                                <div class="column is-12" v-for="(item, index) in listItemEdit" :key="index">
                                    <VCard class="is-grey">
                                        <div class="columns is-multiline p-1">
                                            <div class="column is-4">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabel>Produk</VLabel>
                                                    <VControl icon="feather:search" class="prime-auto-select">
                                                        <AutoComplete v-model="item.cc" :suggestions="d_produk"
                                                            @complete="fetchProduk($event)" :optionLabel="'namaproduk'"
                                                            :dropdown="true" :minLength="3" rounded :appendTo="'body'"
                                                            :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                                                            placeholder="ketik untuk mencari..." />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-2">
                                                <VField>
                                                    <VLabel>Aturan Pakai</VLabel>
                                                    <VControl icon="feather:search" class="prime-auto-select">
                                                        <Dropdown v-model="item.ee" :options="d_aturanPakai"
                                                            :optionLabel="'aturanpakai'" placeholder="Pilih data"
                                                            style="width: 100%;" showClear :filter="true" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabel>Satuan Resep</VLabel>
                                                    <VControl icon="feather:search" class="prime-auto-select">
                                                        <Dropdown v-model="item.dd" :options="d_satuanResep"
                                                            :optionLabel="'satuanresep'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" />
                                                    </VControl>
                                                    <!-- <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.satuanresep" :options="d_satuanResep"
                                        placeholder="Pilih data" :searchable="true" />
                                </VControl> -->
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField label="Jumlah">
                                                    <VControl icon="feather:bookmark">
                                                        <VInput type="number" v-model="item.jumlah"
                                                            placeholder="Jumlah Produk" class="is-rounded" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-1 mt-3" style="margin-top: 1rem;">
                                                <VIconButton v-if="index > 0" outlined type="button" raised circle
                                                    class="is-pulled-right" icon="feather:trash" @click="removeItemEdit(index)"
                                                    color="danger">
                                                </VIconButton>
                                            </div>
                                            <div class="column is-1 is-flex mt-3" style="margin-top: 1rem;">
                                                <VButton type="button" rounded outlined color="info" raised
                                                    icon="feather:plus" @click="addNewItemEdit()"> Tambah
                                                </VButton>
                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                            </div>
                        </VCard>
                    </div>
                </div>
            </form>
        </template>
        <template #action>
            <VButton icon="feather:plus" @click="update()" :loading="isLoading" color="primary" raised>Simpan
            </VButton>
        </template>
    </VModal>
</template>
    
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';

useHead({
    title: 'Master Paket Obat - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = reactive({
    aktif: true,
    isKK: false,
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const listItem: any = ref([
    {
        produk: [],
        qtyproduk: [],
    }
])

const listItemEdit: any = ref([
    {
        produk: [],
        qtyproduk: [],
    }
])

function addNewItem() {
    listItem.value.push({
        produk: [],
        qtyproduk: null,
    });
}

function addNewItemEdit() {
    listItemEdit.value.push({
        produk: [],
        qtyproduk: null,
    });
}

function removeItem(index: any) {
    listItem.value.splice(index, 1)
}

function removeItemEdit(index: any) {
    listItemEdit.value.splice(index, 1)
}

const router = useRouter()
const modalKirim: any = ref(false)
const d_produk: any = ref([])
const d_aturanPakai: any = ref([])
const d_satuanResep: any = ref([])
const confirm = useConfirm()
const dataSource: any = ref([])
const detailResep: any = ref([])
const dataEditPaket: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
const modalRetur: any = ref(false)
const expandedRows = ref();
const d_kemasan: any = ref([])
const d_jenisRacikan: any = ref([])
const d_route: any = ref([])
const d_asalProduk: any = ref([])
const isLoading = ref(false)
let d_ruangan: any = ref([])
let loadSearch: any = ref(false)
let paketid = ref();

const fetchOrder = async () => {

    // let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    // let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    // let idRuangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan}` : ''
    let namaPaket = item.namapaket ? `&namaPaket=${item.namapaket}` : ''
    loadSearch.value = true
    // if (item.namaproduk) nmProduk = '&namaproduk=' + item.namaproduk
    await useApi().get(`/sysadmin/master-paket-obat?${namaPaket}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            expandedRows.value = element.details.forEach((data: any, i: any) => {
                data.no = i + 1
                // data.tglkadaluarsa = H.formatDate(data.tglkadaluarsa, 'DD-MMM-YYYY')

            })
            element.no = i + 1
            // element.norm = element.nostruk_intern ? element.nostruk_intern : '-'
            // element.namapasien = element.namapasien_klien
            // element.tglstruk = moment(element.tglstruk).format('DD-MM-YYYY')

        });
        dataSource.value = response.data
        loadSearch.value = false
    }).catch((err: any) => {
        loadSearch.value = false
    })
}
function add(e: any) {
    item.keterangan = '';
    //   item.tglkirim = new Date()
    //   item.norec_op = e.norec_op
    modalKirim.value = true
}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda yakin menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deletePaket(e)
        },
        reject: () => { },
    })
}

const editPopUp = async (e: any) => {
    console.log(e.details)
    console.log('ini id paket',e.paketId)
    paketid.value = e.paketId;

    listItemEdit.value = e.details

    listItemEdit.value.dd = listItemEdit.value.map(e => {
        e.dd = {id: e.satuanresepfk, satuanresep: e.satuanresep}; 
        return e; 
    });

    listItemEdit.value.cc = listItemEdit.value.map(e => {
        e.cc = {id: e.produkfk, namaproduk: e.namaproduk}; 
        return e; 
    });

    listItemEdit.value.ee = listItemEdit.value.map(e => {
        e.ee = {id: e.aturanpakaifk, aturanpakai: e.aturanpakai}; 
        return e; 
    });

    // dataEditPaket.value.produk = dataEditPaket.value.map(e => {
    //     e.produk = {id: e.produkfk, produk: e.namaparoduk}; 
    //     return e; 
    // });    
    modalEdit.value = true
    item.keterangan = e.namapaket
    listItemEdit.value.idpaket = e.objectpaketobatfk

    
}

const deletePaket = async (e: any) => {

    await useApi().post('/sysadmin/delete-master-paket-obat', { 'idpaket': e.paketId }).then((response) => {
        fetchOrder()
    }).catch((err: any) => {

    })
}

function filter() {
    fetchOrder()
}

const fetchProduk = async (filter: any) => {
    const response = await useApi().get(
        `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
    d_produk.value = response

}

const getListCombo = async () => {
    const response = await useApi().get(`/farmasi/input-resep-cbo`)
    d_ruangan.value = response.ruanganFarmasi.map((e: any) => { return { label: e.namaruangan, value: e.id } })
    d_aturanPakai.value = response.signa.map((e: any) => { return { aturanpakai: e.signa, id: e.id } })
    d_kemasan.value = response.jeniskemasan

    d_jenisRacikan.value = response.jenisracikan//.map((e: any) => { return { label: e.jenisracikan, value: e } })
    d_route.value = response.route//.map((e: any) => { return { label: e.name, value: e } })
    d_satuanResep.value = response.satuanresep//.map((e: any) => { return { label: e.satuanresep, value: e } })
    d_asalProduk.value = response.asalproduk//.map((e: any) => { return { label: e.asalproduk, value: e } })
    item.jenisKemasan = response.jeniskemasan[1]
}

const showDetail = (e: any) => {

    let total = 0
    e.details.forEach((element: any, i: any) => {
        element.no = i + 1
        element.tglKadaluarsa = moment(element.tglkadaluarsa).format('DD-MM-YYYY')
        total = parseFloat(element.total) + total
    });
    item.value.totalTagihan = total
    detailResep.value = e.details
    modalDetail.value = true
}

function tambah() {
    if (listItem.value.length == 0) {
        H.alert('error', 'Harap isi terlebih dahulu item obat')
        return
    }

    if (item.keterangan == undefined) {
        H.alert('error', 'Harap isi nama paket terlebih dahulu')
        return
    }

    let detailPaket = []

    listItem.value.forEach((element: any, i: any) => {
        let data = {
            produkfk: element.produk.id,
            satuanresepfk: element.satuanresep.id,
            aturanpakai: element.aturanPakai.id,
            jumlah: element.qtyproduk,
        }
        detailPaket.push(data)
        console.log('ini detail',data);
    })
    console.log(detailPaket);

    let objSave = {
        idPaket: paketid.value,
        namapaket: item.keterangan,
        paketobat: detailPaket
    }

    isLoading.value = true
    useApi().post(`/sysadmin/save-master-paket-obat`, objSave).then((response: any) => {
        isLoading.value = false
        delete paketid.value
        modalKirim.value = false
        item.keterangan = undefined
        listItem.value = []
    })

    fetchOrder()
}

function update() {
    if (listItemEdit.value.length == 0) {
        H.alert('error', 'Harap isi terlebih dahulu item obat')
        return
    }

    if (item.keterangan == undefined) {
        H.alert('error', 'Harap isi nama paket terlebih dahulu')
        return
    }

   
    let detailPaket = []

listItemEdit.value.forEach((element: any, i: any) => {
    let data = {
        produkfk: element.cc?.id,  // Gunakan optional chaining untuk menghindari error jika produk undefined
        satuanresepfk: element.dd?.id,  // Sama untuk satuanresep
        aturanpakai: element.ee?.id,  // Sama untuk aturanPakai
        jumlah: element.jumlah || 0,  // Pastikan qtyproduk tidak undefined
    }
    detailPaket.push(data)
})


    let objSave = {
        idPaket: paketid.value,
        namapaket: item.keterangan,
        paketobat: detailPaket
    }
    console.log('data ',listItemEdit);
    console.log('ini ditel',detailPaket);        

        isLoading.value = true
        useApi().post(`/sysadmin/update-master-paket-obat`, objSave).then((response: any) => {
            isLoading.value = false
            modalKirim.value = false
            item.keterangan = undefined
            listItem.value = []
        })

        fetchOrder()
        modalEdit.value = false
}

getListCombo()
fetchOrder()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 8px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
    margin-top: 14px;
}
</style>
