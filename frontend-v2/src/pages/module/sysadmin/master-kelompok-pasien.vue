<template>
    <ConfirmDialog />
    <VCard>
        <div class="columns column c-title">
            <h3 class="title is-5 mb-2 ml-2" style="z-index:1">Kelompok Pasien</h3>
        </div>

        <div class="columns is-multiline ml-4">
            <div class="column is-4">
                <img src="/images/avatars/label/validasi.png" slt="image status keluar" class="s-keluar">
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
                            <VField label="Kelompok Pasien">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kelompokpasien" placeholder="Kelompok Pasien"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12" v-if="item.id" style="display:flex; padding: 0px;">
                            <VField class="column is-8 is-rounded-select is-autocomplete-select">
                                <VLabel>Jenis Tarif</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectjenistariffk" :options="d_jenistarif"
                                        placeholder="Pilih data" :searchable="true" />
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
                                <VLabel>Jenis Tarif</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectjenistariffk" :options="d_jenistarif"
                                        placeholder="Pilih data" :searchable="true" />
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
                    <div class="column is-6 switch-filter" style="margin-right: 15px;">
                        <VControl>
                            <InputSwitch v-model="item.isAktif" @change="fetchData()" />
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
                        <Column field="kelompokpasien" header="Kelompok Pasien" :sortable="true"></Column>
                        <Column field="jenistarif" header="Jenis Tarif" :sortable="true"></Column>
                        <Column field="status" header="Status" :sortable="true"></Column>
                        <Column :exportable="false" header="Action" style="text-align: center;">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined
                                    raised v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                                </VIconButton>
                                <VIconButton type="button" v-if="item.isAktif == true" icon="fas fa-trash" color="danger"
                                    circle outlined raised v-tooltip.top="'Hapus'" @click="DialogConfirm(slotProps.data)">
                                </VIconButton>
                                <VIconButton type="button" v-else disabled icon="fas fa-trash" color="danger" circle
                                    outlined raised v-tooltip.top="'Hapus'" @click="DialogConfirm(slotProps.data)">
                                </VIconButton>
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
                                        <span class="dark-inverted mb-2">{{ item.kelompokpasien }}</span>
                                        <span class="dark-inverted">{{ item.jenistarif }}</span>

                                    </div>
                                    <VTag :color="item.status_c" :label="item.status" style="margin-left:25px" />
                                    <VDropdown icon="feather:more-vertical" spaced right>
                                        <template #content>
                                            <a role="menuitem" class="dropdown-item is-media" @click="detail(item)">
                                                <div class="icon">
                                                    <i class="iconify" data-icon="feather:bookmark" aria-hidden="true"></i>
                                                </div>
                                                <div class="meta">
                                                    <span>Detail</span>
                                                    <span>Untuk melihat data </span>
                                                </div>
                                            </a>
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

        <VModal :open="modalDetail" title="Detail Satuan Besar" actions="right" @close="modalDetail = false, clear()">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VField label="Kelompok Pasien">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kelompokpasien" placeholder="Satuan Besar"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Jenis Tarif">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.jenistarif" placeholder="Departemen"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Kode External">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kodeexternal" placeholder="Kode External"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Status Aktivasi">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.status" placeholder="status" class="is-rounded"
                                        readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Kelompok Produk">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.reportdisplay" placeholder="Kelompok Produk"
                                        class="is-rounded" readonly style="cursor:pointer" />
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
import InputSwitch from 'primevue/inputswitch'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'

import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
    title: 'Kelompok Pasien - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({ isAktif: true })
const d_jenistarif = ref([])
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
            items.kelompokpasien.match(new RegExp(filters.value, 'i'))
        )
    })
})

const fetchData = async () => {

    isLoading.value = true
    await useApi().get(`/sysadmin/master-kelompok-pasien?statusenabled=${item.value.isAktif}`).then((response: any) => {
        response.data.forEach((items: any, i: any) => {
            items.no = i + 1
        })
        isLoading.value = false
        dataSource.value = response.data
    })
}

async function save(e: any) {
    if (!e.kelompokpasien) {
        useToaster().error('Kelompok Pasien harus di isi')
        return
    }
    if (!e.objectjenistariffk) {
        useToaster().error('Jenis Tarif harus di isi')
        return
    }
    var objSave =
    {
        'kelompokpasien': {
            'id': e.id ? e.id : '',
            'kelompokpasien': e.kelompokpasien,
            'objectjenistariffk': e.objectjenistariffk,
            'statusenabled': e.statusenabled
        }
    }
    isLoadingButton.value = true
    await useApi().post(
        `/sysadmin/save-master-kelompok-pasien`, objSave).then((response: any) => {
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
        `/sysadmin/delete-master-kelompok-pasien`, { 'id': e.id }).then((response: any) => {
            isLoading.value = false
            clear()
            fetchData()
        }, (error) => {
        })
}

const loadDropdown = () => {
    d_jenistarif.value = []
    useApi().get(
        `sysadmin/master-kelompok-pasien-dropdown`).then((response: any) => {
            d_jenistarif.value = response.jenistarif.map((e: any) => { return { label: e.jenistarif, value: e.id } })
        })
}

const edit = (e: any) => {
    item.value.id = e.id
    item.value.kelompokpasien = e.kelompokpasien
    item.value.objectjenistariffk = e.objectjenistariffk
    item.value.statusenabled = e.statusenabled
    item.value.status = e.status
}

const detail = (e: any) => {
    console.log(e)
    item.value.kelompokpasien = e.kelompokpasien
    item.value.jenistarif = e.jenistarif
    item.value.kodeexternal = e.kodeexternal == null ? '-' : e.kodeexternal
    item.value.status = e.status
    item.value.reportdisplay = e.reportdisplay
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
    item.value.kelompokpasien = ''
    item.value.objectjenistariffk = ''
}
const changeView = (e: any) => {
    selectView.value = e
}

loadDropdown()
fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>