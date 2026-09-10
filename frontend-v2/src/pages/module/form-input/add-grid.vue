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
                            <h3>Form Input</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100"
                                    :to="{ name: 'module-registrasi-pasien-lama' }" light dark-outlined>
                                    Cancel
                                </VButton>
                                <VButton icon="feather:save" type="submit" color="primary" raised @click="save()">
                                    Save
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body">
                    <div class="columns is-multiline">
                        <div class="column is-3 ">
                            <VCard>
                                <h3 class="title is-5 mb-2">Data Resep</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VLabelText>Ruang Rawat</VLabelText>
                                            <VLabel>Poli Gigi</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabelText>Nomor Resep</VLabelText>
                                            <VLabel>-</VLabel>
                                        </VField>
                                    </div>

                                </div>
                            </VCard>
                        </div>
                        <div class="column is-6 ">
                            <VCard>
                                <h3 class="title is-5 mb-2">Data Pasien</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>No RM</VLabelText>
                                            <VLabel>11251476</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>Nama Pasien</VLabelText>
                                            <VLabel>KARTINI TAMBUNAN</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>Jenis Kelamin</VLabelText>
                                            <VLabel>PEREMPUAN</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>Umur</VLabelText>
                                            <VLabel>65 thn 0 bln 23 hari</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>Jenis Pasien</VLabelText>
                                            <VLabel>BPJS</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabelText>Penjamin</VLabelText>
                                            <VLabel>BPJS Kesehatan</VLabel>
                                        </VField>
                                    </div>

                                </div>
                            </VCard>
                        </div>
                        <div class="column is-3 ">
                            <VCard>
                                <h3 class="title is-5 mb-2">Pengkajian</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VLabelText>Berat Badan</VLabelText>
                                            <VLabel>11251476</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabelText>Tinggi Badan</VLabelText>
                                            <VLabel>KARTINI TAMBUNAN</VLabel>
                                        </VField>
                                    </div>

                                </div>
                            </VCard>
                        </div>

                        <div class="column is-12">
                            <VCard>
                                <h3 class="title is-5 mb-2">Data Table</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <Toolbar class="mb-4">
                                            <template #start>
                                                <VButton icon="feather:plus" color="info" raised @click="addPopUp()">
                                                    Add
                                                </VButton>
                                            </template>

                                        </Toolbar>


                                        <DataTable :value="dataSource" :paginator="true" :rows="10"
                                            :rowsPerPageOptions="[5, 10, 25]"
                                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                            responsiveLayout="scroll" sortMode="multiple"
                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                            <!-- <Column v-for="col of dataColumns" :field="col.field" :header="col.header"
                                                :key="col.field"></Column> -->
                                            <Column :exportable="false" header="#" style="width:8rem">
                                                <template #body="slotProps">
                                                    <Button icon="pi pi-pencil"
                                                        class="p-button-rounded p-button-success mr-2"
                                                        @click="editRow(slotProps.data)" />
                                                    <Button icon="pi pi-trash" class="p-button-rounded p-button-warning"
                                                        @click="hapusRow(slotProps.data)" />
                                                </template>
                                            </Column>
                                            <Column field="produk" header="Produk" :sortable="true"></Column>
                                            <Column field="qty" header="Qty"></Column>
                                            <Column field="harga" header="Harga">
                                                <template #body="slotProps">
                                                    {{ formatRp(slotProps.data.harga, '') }}
                                                </template>
                                            </Column>
                                            <Column field="diskon" header="Diskon">
                                                <template #body="slotProps">
                                                    {{ formatRp(slotProps.data.diskon, '') }}
                                                </template>
                                            </Column>
                                            <Column field="total" header="Total">
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
                                        <!-- <VSimpleDatatables>
                                            <thead>
                                                <div class="column is-12">
                                                    <VButton icon="feather:plus" color="info" raised
                                                        @click="addPopUp()">
                                                        Add
                                                    </VButton>
                                                </div>
                                                <tr>
                                                    <th scope="col">Produk</th>
                                                    <th scope="col">Ext.</th>
                                                    <th scope="col">City</th>
                                                    <th scope="col" data-format="YYYY/MM/DD">Start Date</th>
                                                    <th scope="col" data-sort="asc">Completion</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="dataSource.length > 0" v-for="(row, index) in dataSource"
                                                    :key="index">
                                                    <td> {{ row.produk }} </td>
                                                    <td>{{ row.qty }} </td>
                                                    <td>{{ formatRp(row.harga, '') }} </td>
                                                    <td>{{ formatRp(row.diskon, '') }} </td>
                                                    <td>{{ formatRp(row.total, '') }} </td>

                                                </tr>
                                            </tbody>
                                        </VSimpleDatatables> -->
                                    </div>
                                </div>

                            </VCard>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <VModal :open="centeredActionsOpen" title="Input" size="big" actions="right" @close="centeredActionsOpen = false">
        <template #content>
            <form class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-2">
                        <VField>
                            <VLabel>R/Ke</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="number" v-model="item.rke" placeholder="R/Ke" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VLabel>Jenis Kemasan</VLabel>
                            <VControl>
                                <VRadio v-for="items in listKemasan" :key="items.id" v-model="item.jenisKel"
                                    :value="items.id" :label="items.jeniskemasan" name="{{items.id}}" color="primary" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField>
                            <VLabel>Konversi</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.konversi" placeholder="Konversi" disabled />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField>
                            <VLabel>Stok</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.stok" placeholder="Stok" />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-6">
                        <VField class="is-autocomplete-select">
                            <VLabel>Produk</VLabel>
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.produk" :options="item.listProduk"
                                    placeholder="Pilih data" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-autocomplete-select">
                            <VLabel>Satuan</VLabel>
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.satuan" :options="item.listProduk"
                                    placeholder="Pilih data" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <div class="checkboxes">
                            <VField>
                                <VLabel>Aturan Pakai</VLabel>

                                <VFlex column-gap="1rem">
                                    <VControl raw subcontrol v-for="items in listAturanPakai" :key="items.id">
                                        <VCheckbox v-model="item.aturanPakai[items.id]" :true-value="items.nama"
                                            :label="items.nama" color="info" square />
                                    </VControl>

                                </VFlex>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <VLabel>Qty</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="number" v-model="item.qty" placeholder="Qty" />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-6">
                        <VField>
                            <VLabel>Harga</VLabel>
                            <VControl icon="feather:bookmark">
                                <VIMaskInput v-model="item.harga" autocomplete="cc-csc" class="input" :options="{
                                    mask: '000',
                                }" placeholder="Harga" />
                                <VInput type="text" v-model="item.harga" placeholder="Harga" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <VLabel>Diskon</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.diskon" placeholder="Diskon" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <VLabel>Total</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" disabled v-model="item.total" placeholder="Total" />
                            </VControl>
                        </VField>
                    </div>


                </div>
            </form>
        </template>
        <template #action>
            <VButton icon="feather:plus" @click="add()" color="primary" raised>Save</VButton>
        </template>
    </VModal>
</template>
<script setup lang="ts">

import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
    ref,
    computed, defineComponent, watch, nextTick, onMounted
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
useHead({
    title: 'Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
onMounted(() => {

})

const centeredActionsOpen = ref(false)
const dataColumns = ref(
    [
        { field: 'produk', header: 'Produk', },
        { field: 'qty', header: 'Qty' },
        { field: 'harga', header: 'Harga' },
        { field: 'diskon', header: 'Diskon' },
        { field: 'total', header: 'Total' }
    ]
)
const date = ref({
    start: new Date(),
    end: new Date(),
})
let item: any = ref({
    aturanPakai: [],
    listProduk: [
        { label: 'Produk 1', value: { namaproduk: 'Produk 1', id: 1 } },
        { label: 'Produk 2', value: { namaproduk: 'Produk 2', id: 2 } }
    ]
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const listRuangan = ref([])
const listAturanPakai = ref([
    { "id": 1, "nama": "P", 'isChecked': false },
    { "id": 2, "nama": "S", 'isChecked': false },
    { "id": 3, "nama": "Sr", 'isChecked': false },
    { "id": 4, "nama": "M", 'isChecked': false }
])
const listKemasan = ref([{ id: 1, jeniskemasan: 'Racikan' }, { id: 2, jeniskemasan: 'Non Racikan' }])
var dataSource: any = ref([])
let data2: any = ref([])
item.value.totalAll = 0
item.value.qty = 1
item = item.value
function addPopUp() {
    centeredActionsOpen.value = true
}
function add() {
    if (!item.produk) {
        useToaster().error('Produk harus di isi')
        return
    }
    let nomor = 0
    if (data2.value.length == 0) {
        nomor = 1
    } else {
        nomor = data2.value.length + 1
    }
    var data: any = {};
    if (item.no != undefined) {
        for (let x = 0; x < data2.value.length; x++) {
            const element = data2.value[x];
            if (element.no == item.no) {
                data.no = item.no
                data.produk = item.produk.namaproduk
                data.produkfk = item.produk.id
                data.qty = item.qty
                data.name = item.produk.namaproduk
                data.harga = item.harga
                data.diskon = item.diskon ? item.diskon : 0
                data.total = item.total ? item.total : 0
                data2.value[x] = data;
            }
        }
    } else {
        data = {
            no: nomor,
            produk: item.produk.namaproduk,
            produkfk: item.produk.id,
            qty: item.qty,
            name: item.produk.namaproduk,
            harga: item.harga,
            diskon: item.diskon ? item.diskon : 0,
            total: item.total ? item.total : 0,
        }
        data2.value.push(data)
    }
    dataSource.value = data2.value
    countTotal()
    clearInput()
}
function clearInput() {
    delete item.produk
    delete item.no
    delete item.diskon
    delete item.harga
    item.qty = 1
    item.totalAll = 0
    centeredActionsOpen.value = false
}
function countTotal() {
    let total = 0
    for (let x = 0; x < data2.value.length; x++) {
        const element = data2.value[x];
        total = total + parseFloat(element.total)
    }
    item.totalAll = formatRp(total, '')
}
function countTotalSub() {
    item.total = ((item.qty ? item.qty : 0) * (item.harga ? item.harga : 0)) - (item.diskon ? item.diskon : 0)
}
function editRow(e: any) {
    item.no = e.no
    item.produk = { id: e.produkfk, namaproduk: e.produk }
    item.qty = e.qty
    item.harga = e.harga
    item.diskon = e.diskon
    item.total = e.total
    centeredActionsOpen.value = true
}
function hapusRow(e: any) {
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (data2.value[i].no == e.no) {
            data2.value.splice(i, 1);
        }
    }
    dataSource.value = data2.value
    countTotal()
}


function save() {
    useToaster().success('Data Save Successfully')
    return
}
watch(item, (items) => {
    countTotalSub()
})

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';


.page-content-wrapper {
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
}

.form-layout .form-outer {
    flex: 1;
    display: inline-block;
    width: 100%;
    padding: 20px;
    background-color: transparent;
    border-radius: var(--radius-large);
    border: transparent;
    transition: all 0.3s;
    padding: 0;
}

.is-dark .p-toolbar {
    background: var(--dark-sidebar-light-6);
    border-color: var(--dark-sidebar-light-12);
}

.is-dark .p-toolbar {
    background: var(--dark-sidebar-light-6);
    border-color: var(--dark-sidebar-light-12);
}

.is-dark {
    .p-datatable .p-datatable-thead>tr>th {
        text-align: left;
        padding: 1rem 1rem;
        border: 1px solid var(--dark-sidebar-light-12);
        border-width: 0 0 1px 0;
        font-weight: 600;
        color: white;
        background: var(--dark-sidebar-light-6);
        transition: box-shadow 0.2s;
    }

    .p-datatable .p-datatable-tbody>tr {
        background: var(--dark-sidebar-light-6);
        color: white;

        transition: box-shadow 0.2s;
    }

    .p-datatable .p-datatable-tbody>tr>td {
        text-align: left;
        border: 1px solid var(--dark-sidebar-light-12);
        border-width: 0 0 1px 0;
        padding: 1rem 1rem;
    }

    .p-paginator {
        background: var(--dark-sidebar-light-6);
        color: white;
        border: solid var(--dark-sidebar-light-12);
        border-width: 0;
        padding: 0.5rem 1rem;
        border-radius: 3px;
    }

    .p-dropdown {
        background: var(--dark-sidebar-light-6);
        border: 1px solid var(--dark-sidebar-light-12);
        transition: background-color 0.2s, color 0.2s, border-color 0.2s, box-shadow 0.2s;
        border-radius: 3px;
    }

    .p-paginator .p-paginator-pages .p-paginator-page.p-highlight {
        background: var(--dark-sidebar-light-6);
        border-color: var(--dark-sidebar-light-12);
        color: white;
    }
}
</style>
