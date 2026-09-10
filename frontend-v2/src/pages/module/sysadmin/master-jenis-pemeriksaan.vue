<template>
    <ConfirmDialog />
    <VCard>
        
        <div class="columns is-multiline ml-4">
            <div class="column is-4">
                <img src="/images/avatars/label/validasi.png" slt="image status keluar" class="s-keluar" alt="" style="margin-top: 1.5rem;">
                <VCard>
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <h3 v-if="item.id" class="title is-6 mb-2 mr-1">
                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                                Edit Data
                            </h3>
                            <h3 v-else class="title is-6 mb-2 mr-1">
                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                                Tambah Data
                            </h3>
                        </div>
                        <div class="column is-12">
                            <VField label="Jenis Pemeriksaan">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.detailjenisproduk" placeholder="Jenis Pemeriksaan"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12" v-if="item.id" style="display:flex; padding: 0px;">
                            <VField class="column is-8 is-rounded-select is-autocomplete-select">
                                <VLabel>Jenis Produk</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectjenisprodukfk"
                                        :options="d_JenisProduk" placeholder="Pilih data" :searchable="true" />
                                </VControl>
                            </VField>

                            <VField class="column is-4">
                                <VLabel class="ml-2">Aktivasi</VLabel>
                                <VControl class="is-pulled-right">
                                    <VSwitchBlock style="padding-top:3px" v-model="item.statusenabled" label="Aktif"
                                        color="danger" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12" v-else>
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Jenis Produk</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectjenisprodukfk"
                                        :options="d_JenisProduk" placeholder="Pilih data" :searchable="true" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-12">
                            <div v-if="item.id">
                                <VButton @click="save(item)" :loading="isLoadingButton" type="button" icon="feather:edit"
                                    class="is-fullwidth mr-3" color="info" raised>
                                    Update Data
                                </VButton>
                                <VButton @click="clear()" type="button" icon="feather:x-circle"
                                    class="is-fullwidth is-outlined is-warning mt-3" raised>
                                    Batal Edit
                                </VButton>
                            </div>
                            <div v-else>
                                <VButton @click="save(item)" :loading="isLoadingButton" type="button" icon="feather:save"
                                    class="is-fullwidth mr-3" color="success" raised>
                                    Simpan Data
                                </VButton>
                            </div>
                        </div>
                    </div>
                </VCard>
            </div>
            <div class="column is-8 mt-3">
                <div class="user-grid-toolbar">
                    <VControl icon="feather:search">
                        <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
                    </VControl>
                    <div class="column is-6">
                        <!-- <VControl class="is-pulled-right">
                            <VSwitchBlock v-model="item.isAktif" label="Aktif" color="danger"
                                @change="changeSwitch(item.isAktif)" />
                        </VControl> -->
                        <VControl>
                            <InputSwitch v-model="item.aktif" @change="fetchData()"  label="Aktif"/>
                        </VControl>
                        <span>Aktif</span>
                    </div>
                    <div class="buttons">
                        <VField v-slot="{ id }" class="is-icon-select">
                            <VControl>
                                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name"
                                    :options="d_View" :searchable="true" track-by="name" mode="single"
                                    @select="changeView(selectView)" autocomplete="off">
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
                </div>

                <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
                    <DataTable :value="dataSourcefiltered" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                        :loading="isLoading" class="p-datatable-sm"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                        <Column field="no" header="#"></Column>
                        <Column field="detailjenisproduk" header="Jenis Pemeriksaan" :sortable="true"></Column>
                        <Column field="jenisproduk" header="Jenis Produk" :sortable="true"></Column>
                        <Column field="status" header="Status" :sortable="true"></Column>
                        <Column :exportable="false" header="Action" style="text-align: center;">
                            <template #body="slotProps">
                            <VDropdown icon="feather:more-vertical" spaced right>
                                <template #content>
                                    <a role="menuitem" class="dropdown-item is-media" @click="edit(slotProps.data)">
                                        <div class="icon">
                                            <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Ubah Jenis Pemeriksaan</span>
                                        </div>
                                    </a>
                                    <a role="menuitem" class="dropdown-item is-media"
                                    @click="DialogConfirm(slotProps.data)">
                                        <div class="icon">
                                            <i class="iconify" data-icon="feather:trash" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Hapus Jenis Pemeriksaan</span>
                                        </div>
                                    </a>
                                </template>
                            </VDropdown>
                        </template>
                        </Column>
                    </DataTable>
                </div>
                <div class="tile-grid tile-grid-v1" v-else-if="selectView == 'grid'">

                    <TransitionGroup name="list" tag="div" class="columns is-multiline">
                        <!--Grid item-->
                        <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-6">
                            <div class="tile-grid-item">
                                <div class="tile-grid-item-inner" @click="edit(item)">
                                    <VIconBox size="medium" color="warning" rounded>
                                        <i class="fas fa-envelope-open"></i>
                                    </VIconBox>

                                    <div class="meta">
                                        <span class="dark-inverted mb-2">{{ item.statuskeluar }}</span>
                                        <span class="dark-inverted">{{ item.jeniskondisipasien }}</span>

                                    </div>
                                    <VTag :color="item.status_c" :label="item.status" style="margin-left:25px" />
                                    <VDropdown icon="feather:more-vertical" spaced right>
                                        <template #content>
                                            <a role="menuitem" class="dropdown-item is-media" @click="edit(item)">
                                                <div class="icon">
                                                    <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                                                </div>
                                                <div class="meta">
                                                    <span>Edit</span>
                                                    <span>Untuk merubah data </span>
                                                </div>
                                            </a>
                                            <a role="menuitem" v-if="item.statusenabled == true"
                                                class="dropdown-item is-media" @click="DialogConfirm(item)">
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
        </div>

        <VModal :open="modalDetail" title="Detail Status Keluar" noclose actions="right"
            @close="modalDetail = false, clear()">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VField label="Status Keluar">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" readonly style="cursor:pointer" v-model="item.statuskeluar"
                                        placeholder="Kelompok Produk" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-8">
                            <VField label="Kondisi Pasien">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" readonly style="cursor:pointer" v-model="item.jeniskondisipasien"
                                        placeholder="Kode External" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Kode External">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" readonly style="cursor:pointer" v-model="item.kodeexternal"
                                        placeholder="Kode External" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Status Aktivasi">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" readonly style="cursor:pointer" v-model="item.status"
                                        placeholder="Norec" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Report Display">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" readonly style="cursor:pointer" v-model="item.reportdisplay"
                                        placeholder="Report Display" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </form>
            </template>

        </VModal>
    </VCard>
</template>
  
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import InputSwitch from 'primevue/inputswitch'

import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
    title: 'Jenis Pemeriksaan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({aktif: true})
const d_JenisProduk = ref([])
const modalDetail = ref(false)

let dataSource: any = ref([])
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
const confirm = useConfirm()
selectView.value = 'list'
let isLoadingButton: any = ref(false)
let isLoading: any = ref(false)

const filters = ref('')
const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.detailjenisproduk.match(new RegExp(filters.value, 'i'))
        )
    })
})

const fetchData = async ()=>{
    isLoading.value = true

    await useApi().get(`/laboratorium/get-jenis-pemeriksaan?statusenabled=${item.value.aktif}`).then((response)=>{
        response.data.forEach((element:any,i:any) => {
            element.no = i+1
        });
        dataSource.value = response.data
        isLoading.value = false
    })
}
   

const save = async (item: any) => {
    if (!item.detailjenisproduk) {
        useToaster().error('Status Keluar harus di isi')
        return
    }  
    let objSave =
    {
        'detailjenisproduk': {
            'id': item.id ? item.id : '',
            'detailjenisproduk': item.detailjenisproduk,
            'statusenabled': item.statusenabled,
            'objectjenisprodukfk': item.objectjenisprodukfk ? item.objectjenisprodukfk : '',
        }
    }

    isLoadingButton.value = true
    await useApi().post(
        `/laboratorium/save-jenis-pemeriksaan`, objSave).then((response: any) => {
            isLoadingButton.value = false
            clear()
            fetchData()
        }, (error) => {
            isLoadingButton.value = false
            // console.log(error)
        })
}

const deleterow = async (e: any) => {
    isLoading.value = true
    await useApi().post(
        `/laboratorium/delete-pendukung`, { 'id': e.id }).then((response: any) => {
            isLoading.value = false
            clear()
            fetchData()
        }, (error) => {
        })
}

const loadDropdown = () => {
    useApi().get(`/laboratorium/load-pendukung`).then((response: any) => {
        d_JenisProduk.value = response.jenisproduk.map((e: any) => { return { label: e.jenisproduk, value: e.id } })
    })
}

const edit = (e: any) => {
    item.value.id = e.id
    item.value.detailjenisproduk = e.detailjenisproduk
    item.value.objectjenisprodukfk = e.objectjenisprodukfk
    item.value.statusenabled = e.statusenabled
}

const detail = (e: any) => {
    item.value.id = e.id
    item.value.statuskeluar = e.statuskeluar
    item.value.namaexternal = e.namaexternal
    item.value.kodeexternal = e.kodeexternal
    item.value.status = e.status
    item.value.reportdisplay = e.reportdisplay
    item.value.jeniskondisipasien = e.jeniskondisipasien
    modalDetail.value = true
}

const DialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleterow(e)

        },
        reject: () => { },
    })
}

const clear = () => {
    item.value.id = ''
    item.value.detailjenisproduk = ''
    item.value.objectjenisprodukfk = ''
}
const changeView = (e: any) => {
    selectView.value = e
}

// const changeSwitch = (e: any) => {
//     fetchData(e)

// }


loadDropdown()
fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>