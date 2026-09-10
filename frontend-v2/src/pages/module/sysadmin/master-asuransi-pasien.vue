<template>
    <VCard>
        <VTabs id="custom" slider centered selected="asuransipasien" :tabs="[
            { label: 'Asuransi Pasien', value: 'asuransipasien' },
            { label: 'Golongan Asuransi', value: 'golonganasuransi' },
            { label: 'Hubungan Peserta', value: 'hubunganpeserta' },
        ]">
            <template #tab="{ activeValue }">
                <p v-if="activeValue === 'asuransipasien'">
                <div class="columns is-multiline">
                    <div class="column is-8">
                        <div class="user-grid-toolbar">
                            <div class="buttons">
                                <VField v-slot="{ id }" class="is-icon-select">
                                    <VControl>
                                        <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View"
                                            label="name" :options="d_View" :searchable="true" track-by="name"
                                            mode="single" @select="changeView(selectView)" autocomplete="off">
                                            <template #singlelabel="{ value }">
                                                <div class="multiselect-single-label">
                                                    <div class="select-label-icon-wrap">
                                                        <i :class="value.icon"></i>
                                                    </div>
                                                    <span class="select-label-text">
                                                        {{ value.name }}
                                                    </span>
                                                </div>
                                            </template>
                                            <template #option="{ option }">
                                                <div class="select-option-icon-wrap">
                                                    <i :class="option.icon"></i>
                                                </div>
                                                <span class="select-option-text">
                                                    {{ option.name }}
                                                </span>
                                            </template>
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column">
                                <VControl>
                                    <VSwitchBlock v-model="item.aktif" label="Aktif" color="danger"
                                        @change="changeSwitch(item.aktif)" />
                                </VControl>
                            </div>
                            <div class="column" style="display:contents">
                                <a type="button" color="info" outlined raised>
                                    <VButton color="primary" raised  RouterLink :to="{ name: 'module-registrasi-asuransi-pasien-baru' }">
                                        <i class="fa fa-plus mr-3"></i>Komponen Harga
                                    </VButton>
                                </a>
                            </div>

                        </div>

                        <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
                            <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoadingDelete"
                                :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                                <Column field="no" header="#"/>
                                <Column field="namapeserta" header="Nama Peserta" :sortable="true"/>
                                <Column field="nocm" header="CM" :sortable="true"/>
                                <Column field="jeniskelamin" header="JK" :sortable="true"/>
                                <Column field="status" header="Status" :sortable="true"/>
                                <Column :exportable="false" header="Action" style="text-align:center">
                                    <template #body="slotProps">
                                        <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle
                                            outlined raised v-tooltip.top="'Edit'"  @click="edit(slotProps.data)">
                                        </VIconButton>
                                        <VIconButton type="button" icon="fas fa-trash" class="mr-3" color="danger"
                                            circle outlined raised v-tooltip.top="'Hapus'"
                                            @click="deleterow(slotProps.data)">
                                        </VIconButton>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                        <div class="tile-grid tile-grid-v1" v-else-if="selectView == 'grid'">
                            <TransitionGroup name="list" tag="div" class="columns is-multiline">
                                <!--Grid item-->
                                <div v-for="(item, key) in dataSource" :key="key" class="column is-6">
                                    <div class="tile-grid-item">
                                        <div class="tile-grid-item-inner">
                                            <VAvatar size="medium" picture="/images/avatars/icon/ic-diagnosa.png"
                                                color="primary" squared bordered />
                                            <div class="meta">
                                                <span class="dark-inverted">{{ item.komponenharga }}</span>
                                                <span>{{ item.jeniskomponenharga }}</span>
                                                <!-- <span> Departemen : {{ item.kategoridiagnosa }}</span> -->
                                            </div>
                                            <VTag :color="item.status_c" :label="item.status"
                                                style="margin-left:25px" />
                                            <VDropdown icon="feather:more-vertical" spaced right>
                                                <template #content>
                                                    <a role="menuitem" class="dropdown-item is-media"
                                                        @click="detail(item)">
                                                        <div class="icon">
                                                            <i class="iconify" data-icon="feather:bookmark"
                                                                aria-hidden="true"></i>
                                                        </div>
                                                        <div class="meta">
                                                            <span>Detail</span>
                                                            <span>Untuk melihat data </span>
                                                        </div>
                                                    </a>
                                                    <a role="menuitem" class="dropdown-item is-media"
                                                        @click="edit(item)">
                                                        <div class="icon">
                                                            <i class="iconify" data-icon="feather:edit"
                                                                aria-hidden="true"></i>
                                                        </div>
                                                        <div class="meta">
                                                            <span>Edit</span>
                                                            <span>Untuk merubah data </span>
                                                        </div>
                                                    </a>
                                                    <a role="menuitem" class="dropdown-item is-media"
                                                        @click="deleterow(item)">
                                                        <div class="icon">
                                                            <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                                                        </div>
                                                        <div class="meta">
                                                            <span>Remove</span>
                                                            <span>Hapus Data dari Daftar</span>
                                                        </div>
                                                    </a>
                                                </template>
                                            </VDropdown>
                                        </div>
                                    </div>
                                </div>
                            </TransitionGroup>
                        </div>
                    </div>
                    <div class="column is-4" style="padding-left: 0px; margin-top:6.6rem">
                        <VCard>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <h3 class="title is-5 mb-2 mr-1">Filter</h3>
                                </div>
                                <div class="column is-6"
                                    style="display: flex;justify-content: end;padding-right: 14px;">
                                    <a @click="clearFilter()" type="button" class="is-pulled-right" color="info"
                                        outlined raised>
                                        Kembali
                                    </a>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VControl icon="feather:search">
                                            <input v-model="item.namapeserta" v-on:keyup.enter="filter()" type="text"
                                                class="input is-rounded" placeholder="Nama Peserta" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="is-autocomplete-select">
                                        <VLabel>Golongan Asuransi</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.objectgolonganasuransifk"
                                                :options="d_GolonganAsuransi" placeholder="Departemen" :searchable="true" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="is-autocomplete-select">
                                        <VLabel>Jenis Kelamin</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.objectjeniskelaminfk"
                                                :options="d_Jeniskelamin" placeholder="jenis"  :searchable="true" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VButton @click="filter()" :loading="isLoading" type="button" icon="feather:search"
                                        class="is-fullwidth mr-3" color="info" raised>
                                        Pencarian
                                    </VButton>
                                </div>
                            </div>
                        </VCard>
                    </div>
                </div>

                <!-- <VModal :open="modalInput" title="Input Komponen Harga" actions="right"
                    @close="modalInput = false, clear()">
                    <template #content>
                        <form class="modal-form">
                            <div class="columns is-multiline">

                                <div class="column is-12">
                                    <VField label="Komponen Harga">
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.komponenharga"
                                                placeholder="Komponen Harga" class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-6">
                                    <VField class="is-rounded-select is-autocomplete-select">
                                        <VLabel>Departemen</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.objectdepartemenfk"
                                                :options="d_Departemen" placeholder="Pilih Departemen"
                                                :searchable="true" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-6">
                                    <VField class="is-rounded-select is-autocomplete-select">
                                        <VLabel>Jenis Komponen Harga</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.objectjeniskomponenhargafk"
                                                :options="d_JKHarga" placeholder="Jenis Komponen Harga"
                                                :searchable="true" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="columns is-multiline" v-if="item.id"
                                    style="margin-left: 2px;margin-bottom: 0px;padding-bottom: 0px;padding-top: 10px;">
                                    <div class="column is-6">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel>Produk</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectprodukpkfk"
                                                    :options="d_Produk" placeholder="Pilih Produk" :searchable="true" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField label="Status Aktivasi" style="padding-right: 120px;">
                                            <VControl>
                                                <VSwitchBlock v-model="item.statusenabled" color="danger" label="Aktif"
                                                    @change="changeSwitch(item.statusenabled)"
                                                    style="margin-top:-10px;" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField label="Cito" style="position:absolute">
                                            <VControl raw subcontrol>
                                                <VCheckbox style="margin-left: -12px;margin-top: -7px;"
                                                    v-model="item.iscito" label="True" @change="item.iscito"
                                                    color="primary" />
                                            </VControl>
                                        </VField>
                                    </div>

                                    <div class="column is-6">
                                        <VField label="Nomer Urut">
                                            <VControl icon="feather:bookmark">
                                                <VInput type="text" v-model="item.nourut" placeholder="Nomer Urut"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6" style="padding-right:18px">
                                        <VField label="Nilai Normal">
                                            <VControl icon="feather:bookmark">
                                                <VInput type="text" v-model="item.nilainormal"
                                                    placeholder="Nilai Normal" class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>

                                </div>
                                <div class="columns is-multiline" v-else style="margin:0px">
                                    <div class="column is-12">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel>Produk</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectprodukpkfk"
                                                    :options="d_Produk" placeholder="Pilih Produk" :searchable="true" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField label="Nomer Urut">
                                            <VControl icon="feather:bookmark">
                                                <VInput type="text" v-model="item.nourut" placeholder="Nomer Urut"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField label="Nilai Normal">
                                            <VControl icon="feather:bookmark">
                                                <VInput type="text" v-model="item.nilainormal"
                                                    placeholder="Nilai Normal" class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField label="Cito">
                                            <VControl raw subcontrol>
                                                <VCheckbox style="margin-left: -12px;margin-top: -7px;"
                                                    v-model="item.iscito" label="True" @change="item.iscito"
                                                    color="primary" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </template>

                    <template #action>
                        <VButton v-if="item.id" icon="feather:plus" @click="save()" :loading="isLoadingTT"
                            color="primary" raised>
                            Update
                        </VButton>
                        <VButton v-else icon="feather:plus" @click="save()" :loading="isLoadingTT" color="primary"
                            raised>Simpan
                        </VButton>
                    </template>
                </VModal>

                <VModal :open="modalDetail" title="Detail Komponen Harga" actions="right"
                    @close="modalDetail = false, clear()">
                    <template #content>
                        <form class="modal-form">
                            <div class="columns is-multiline">

                                <div class="column is-12">
                                    <VField label="Komponen Harga">
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.komponenharga"
                                                placeholder="Komponen Harga" class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-6">
                                    <VField label="Departemen">
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.namadepartemen" placeholder="Departemen"
                                                class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-6">
                                    <VField label="Jenis Komponen Harga">
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.jeniskomponenharga"
                                                placeholder="Jenis Komponen Harga" class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-6">
                                    <VField label="Produk">
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.namaproduk" placeholder="Produk"
                                                class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-3">
                                    <VField label="Nomer Urut">
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.nourut" placeholder="Produk"
                                                class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-3">
                                    <VField label="Nilai Normal" style="overflow: hidden ;">
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.nilainormal" placeholder="Nilai Normal"
                                                class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-5">
                                    <VField label="Kode External">
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.kodeexternal" placeholder="Kode External"
                                                class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-3">
                                    <VField label="Cito">
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.iscito" placeholder="Cito"
                                                class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-4">
                                    <VField label="Status Aktivasi">
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.status" placeholder="status"
                                                class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>

                            </div>
                        </form>
                    </template>
                </VModal> -->

                </p>

                <p v-else-if="activeValue === 'golonganasuransi'">
                    <MasterDiagnosaTindakan></MasterDiagnosaTindakan>
                </p>

                <p v-else-if="activeValue === 'hubunganpeserta'">
                    <MasterKategoriDiagnosa></MasterKategoriDiagnosa>
                </p>
            </template>
        </VTabs>
    </VCard>
</template>
  
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed } from 'vue'
import { useRoute,useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import MasterKategoriDiagnosa from './master-kategori-diagnosa.vue'
import MasterDiagnosaTindakan from './master-diagnosa-tindakan.vue'
import { useHead } from '@vueuse/head'

import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Asuransi Pasien - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let dataSource: any = ref([])
const item: any = ref({
    aktif: true
})

const d_Jeniskelamin = ref([])
const d_GolonganAsuransi = ref([])
const router = useRouter()

const d_View = [
    {
        name: 'Grid View',
        value: 'grid',
        icon: 'fas fa-id-card-alt',
    },
    {
        name: 'List View',
        value: 'list',
        icon: 'fas fa-list',
    },
]
const selectView: any = ref()
selectView.value = 'list'

let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let isLoadingDelete: any = ref(false)
// let listAsalProduk: any = ref([])

const currentPage: any = ref({
    limit: 5,
    rows: 50,
})
function filter() {
  fetchData()
}

const route = useRoute()
isLoading.value = false


async function fetchData() {
    isLoading.value = true
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = offset * limit - limit
    let rows: any = currentPage.value.rows
    let namaPeserta = ''
    let kategoryDiagnosa = ''
    let jenisKelamin = ''
    let statusEnabled = ''

    if (item.namapeserta) namaPeserta = '&namapeserta' + item.namapeserta
    if (item.jeniskelamin) jenisKelamin = '&jeniskelamin' + item.jeniskelamin
    if (item.value.aktif) {
        statusEnabled = '&statusenabled=' + item.value.aktif
    } else {
        statusEnabled = '&statusenabled=false'
    }

    const response = await useApi().get(
        '/sysadmin/master-asuransi-pasien?offset=' + offset +
        '&limit=' + limit +
        '&rows=' + rows +
        namaPeserta + statusEnabled +  jenisKelamin
    )
    isLoading.value = false
    for (let x = 0; x < response.data.length; x++) {
        const element = response.data[x];
        element.no = x + 1
    }

    dataSource.value = response.data
    //   dataSource.value.total = response.length
}

async function loadDropdown() {

    const response = await useApi().get(`/sysadmin/master-asuransi-pasien/select-item`)
    d_GolonganAsuransi.value = response.golonganasuransi.map((e: any) => {
        return { label: e.golonganasuransi, value: e.id }
    })
    d_Jeniskelamin.value = response.jeniskelamin.map((e: any) => {
        return { label: e.jeniskelamin, value: e.id }
    })

}

function changeView(e: any) {
    selectView.value = e
}

async function deleterow(e: any) {
    isLoadingDelete.value = true
    await useApi().post(
        `/sysadmin/master-diagnosa/delete`, { 'id': e.id }).then((response: any) => {
            clear()
            fetchData()
            isLoadingDelete.value = false
        }, (error) => {
        })
}

function edit(e: any) {
  router.push({
    name: 'module-registrasi-asuransi-pasien-baru',
    query: {
      id: e.id,
    },
  })
}

function detail(e: any) {
  router.push({
    name: 'module-sysadmin-detail-produk',
    query: {
      id: e.id,
    },
  })
}

function changeSwitch(e: any) {
    fetchData()
}

function clear() {
    item.value.id = ''
    item.value.namadiagnosa = ''
    item.value.kddiagnosa = ''
    item.value.objectjeniskelaminfk = ''
    item.value.objectkategorydiagnosafk = ''
}

fetchData()
loadDropdown()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>