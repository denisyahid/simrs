<template>
    <ConfirmDialog />
    <VCard>
        <div class="columns column c-title">
            <h3 class="title is-5 mb-2 ml-2" style="z-index:1">Kategory Diet</h3>
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
                            <VField label="Kategory Diet">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kategorydiet" placeholder="Kategory Diet"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12" v-if="item.id" style="display:flex; padding: 0px;">
                            <VField class="column is-8 is-rounded-select is-autocomplete-select">
                                <VLabel>Kelompok Produk</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectkelompokprodukfk"
                                        :options="d_KelompokProduk" placeholder="Pilih data" :searchable="true" />
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
                                <VLabel>Kelompok Produk</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectkelompokprodukfk"
                                        :options="d_KelompokProduk" placeholder="Pilih data" :searchable="true" />
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
                        <VControl class="is-pulled-right">
                            <VSwitchBlock v-model="item.isAktif" label="Aktif" color="danger"
                                @change="changeSwitch(item.isAktif)" />
                        </VControl>
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
                        <Column field="kategorydiet" header="Kategory Diet" :sortable="true"></Column>
                        <Column field="kelompokproduk" header="Kelompok Produk"></Column>
                        <Column field="status" header="Status"></Column>
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
                                        <span class="dark-inverted mb-2">{{ item.kategorydiet }}</span>
                                        <span class="dark-inverted">{{ item.kelompokproduk }}</span>

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

import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
    title: 'Kategory Diet - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({ isAktif: true })
const d_KelompokProduk = ref([])
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
            items.kategorydiet.match(new RegExp(filters.value, 'i'))
        )
    })
})

const fetchData = async (e: any) => {
    let statusEnabled = ''
    isLoading.value = true
    if (e == undefined) {
        statusEnabled = 'statusenabled=true'
    } else {
        statusEnabled = 'statusenabled=' + e
    }
    await useApi().get(`/sysadmin/master-kategory-diet?${statusEnabled}`).then((response: any) => {
        response.data.forEach((items: any, i: any) => {
            items.no = i + 1
        })
        isLoading.value = false
        dataSource.value = response.data
    })
}

const save = async (item: any) => {
    if (!item.kategorydiet) {
        useToaster().error('Jenis Diet harus di isi')
        return
    }
    if (!item.objectkelompokprodukfk) {
        useToaster().error('Kelompok Produk Wajib Dipilih')
        return
    }

    var objSave =
    {
        'kategorydiet': {
            'id': item.id ? item.id : '',
            'kategorydiet': item.kategorydiet,
            'statusenabled': item.statusenabled,
            'objectkelompokprodukfk': item.objectkelompokprodukfk ? item.objectkelompokprodukfk : '',
        }
    }

    isLoadingButton.value = true
    await useApi().post(
        `/sysadmin/save-kategory-diet`, objSave).then((response: any) => {
            isLoadingButton.value = false
            clear()
            fetchData(true)
        }, (error) => {
            isLoadingButton.value = false
            // console.log(error)
        })
}

const deleterow = async (e: any) => {
    isLoading.value = true
    await useApi().post(
        `/sysadmin/delete-kategory-diet`, { 'id': e.id }).then((response: any) => {
            isLoading.value = false
            clear()
            fetchData(true)
        }, (error) => {
        })
}

function loadDropdown() {
  d_KelompokProduk.value = []
  useApi().get(
    `/sysadmin/dropdown-kp`).then((response: any) => {
      d_KelompokProduk.value = response.kelompokproduk.map((e: any) => { return { label: e.kelompokproduk, value: e.id } })
    })
}

const edit = (e: any) => {
    item.value.id = e.id
    item.value.kategorydiet = e.kategorydiet
    item.value.statusenabled = e.statusenabled
    item.value.objectkelompokprodukfk = e.objectkelompokprodukfk
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
    item.value.kategorydiet = ''
    item.value.objectkelompokprodukfk = ''
}
const changeView = (e: any) => {
    selectView.value = e
}

const changeSwitch = (e: any) => {
    fetchData(e)

}


loadDropdown()
fetchData(true)

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>