<template>
    <VCard>
        <ConfirmDialog/>
        <div class="columns is-multiline">
            <div class="column is-8 mt-3">
                <div class="user-grid-toolbar">
                    <VControl icon="feather:search">
                        <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
                    </VControl>
                    <div class="column is-6 switch-filter">
                        <VControl>
                            <InputSwitch v-model="item.aktif" @change="fetchData()" />
                        </VControl>
                        <span>Aktif</span>
                    </div>
                    <div class="buttons ml-4">
                        <VField v-slot="{ id }" class="is-icon-select">
                            <VControl class="mr-0">
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
                    <DataTable :value="dataSourcefiltered" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                        :loading="isLoading" class="p-datatable-sm"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                        <Column field="no" header="#"></Column>
                        <Column field="nama" header="Nama Kategori Diagnosa" :sortable="true"></Column>
                        <Column field="status" header="Status" :sortable="true"></Column>
                        <Column field="rinciankodediagnosa" header="Rincian Kode" :sortable="true"></Column>
                        <Column :exportable="false" header="Action" style="text-align: center;">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined
                                    raised v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                                </VIconButton>
                                <VIconButton type="button" v-if="item.aktif == true" icon="fas fa-trash" class="mr-3"
                                    color="danger" circle outlined raised v-tooltip.top="'Hapus'"
                                    @click="dialogConfirm(slotProps.data)">
                                </VIconButton>
                                <VIconButton type="button" v-else icon="fas fa-trash" class="mr-3" color="danger" circle
                                    outlined raised v-tooltip.top="'Hapus'" disabled>
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
                                    <VAvatar size="medium" picture="/images/avatars/icon/ic-kategoridiagnosa.png"
                                        color="primary" squared bordered />


                                    <div class="meta">
                                        <span class="dark-inverted mb-2">{{ item.kategorydiagnosa }}</span>

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
                                            <a role="menuitem" class="dropdown-item is-media" v-if="item.aktif == true"
                                                @click="dialogConfirm(item)">
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

            <div class="column is-4">
                <img src="/images/avatars/label/kategori-diagnosa.png" class="mix-kategori">
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
                            <VField label="Kategori Diagnosa">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kategorydiagnosa" placeholder="Kategori Diagnosa"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12" v-if="item.id" style="display:flex; padding: 0px;">
                            <VField class="column is-8" label="Rincian Kode">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.rinciankodediagnosa"
                                        placeholder="Rincian Kode Diagnosa" class="is-rounded" />
                                </VControl>
                            </VField>

                            <VField class="column is-4">
                                <VLabel>Aktivasi</VLabel>
                                <VControl class="is-pulled-right">
                                    <VSwitchBlock style="padding-top:3px" v-model="item.statusenabled" label="Aktif"
                                        color="danger" @change="changeSwitch(item.statusenabled)" />
                                </VControl>
                            </VField>

                        </div>
                        <div class="column is-12" v-else>
                            <VField label="Rincian Kode">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.rinciankodediagnosa"
                                        placeholder="Rincian Kode Diagnosa" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <div v-if="item.id">
                                <VButton @click="save()" :loading="isLoadingTT" type="button" icon="feather:edit"
                                    class="is-fullwidth mr-3" color="info" raised>
                                    Update Data
                                </VButton>
                                <VButton @click="clear()" type="button" icon="feather:x-circle"
                                    class="is-fullwidth is-outlined is-warning mt-3" raised>
                                    Batal Edit
                                </VButton>
                            </div>
                            <div v-else>
                                <VButton @click="save()" :loading="isLoadingTT" type="button" icon="feather:save"
                                    class="is-fullwidth mr-3" color="success" raised>
                                    Simpan Data
                                </VButton>
                            </div>
                        </div>
                    </div>
                </VCard>
            </div>

        </div>
        <VModal :open="modalDetail" title="Detail Kategori Diagnosa" actions="right" @close="modalDetail = false, clear()">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VField label="Kategori Diagnosa">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kategorydiagnosa" placeholder="Kategori Diagnosa"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Rincian Kode Diagnosa">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.rinciankodediagnosa"
                                        placeholder="Rincian Kode Diagnosa" class="is-rounded" readonly
                                        style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Setatus Aktivasi">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.status" placeholder="status" class="is-rounded"
                                        readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Report Display">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.reportdisplay" placeholder="Report Display"
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
                    </div>
                </form>
            </template>

        </VModal>
    </VCard>
</template>
  
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputSwitch from 'primevue/inputswitch';
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'


import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
    title: 'Kategori Diagnosa - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const confirm = useConfirm()

const item: any = ref({
    aktif: true
})

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
selectView.value = 'list'
let isLoadingTT: any = ref(false)
let isLoading: any = ref(false)


const filters = ref('')
const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.nama.match(new RegExp(filters.value, 'i'))
        )
    })
})

const fetchData = async () => {
    isLoading.value = true

    let statusEnabled = item.value.aktif ? '&statusenabled=' + item.value.aktif : '&statusenabled=' + item.value.aktif

    await useApi().get('/sysadmin/master-kategori-diagnosa?' + statusEnabled).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            element.nama = element.kategorydiagnosa.length > 45 ? element.kategorydiagnosa.substring(0, 45) + '...' : element.kategorydiagnosa
        })
        dataSource.value = response.data
    })
    isLoading.value = false
}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteItem(e)

        },
        reject: () => { },
    })
}

const save = async () => {
    if (!item.value.kategorydiagnosa) {
        useToaster().error('Nama Kategori Diagnosa harus di isi')
        return
    }
    var objSave =
    {
        'kategorydiagnosa': {
            'id': item.value.id ? item.value.id : '',
            'kategorydiagnosa': item.value.kategorydiagnosa,
            'rinciankodediagnosa': item.value.rinciankodediagnosa ? item.value.rinciankodediagnosa : '',
            'statusenabled': item.value.statusenabled,
            'objectjeniskondisipasienfk': item.value.objectjeniskondisipasienfk ? item.value.objectjeniskondisipasienfk : '',
        }
    }
    isLoadingTT.value = true
    await useApi().post(
        `/sysadmin/master-kategori-diagnosa/save`, objSave).then((response: any) => {
            isLoadingTT.value = false
            clear()
            fetchData()
        }, (error) => {
            isLoadingTT.value = false
            // console.log(error)
        })
}

const deleteItem = async (e: any) => {
    isLoading.value = true
    await useApi().post(
        `/sysadmin/master-kategori-diagnosa/delete`, { 'id': e.id }).then((response: any) => {
            isLoading.value = false
            fetchData()
        }, (error) => {
        })
}

const edit = (e: any) => {
    item.value.id = e.id
    item.value.kategorydiagnosa = e.kategorydiagnosa
    item.value.rinciankodediagnosa = e.rinciankodediagnosa
    item.value.statusenabled = e.statusenabled
}
const detail = (e: any) => {
    item.value.id = e.id
    item.value.kategorydiagnosa = e.kategorydiagnosa
    item.value.rinciankodediagnosa = e.rinciankodediagnosa
    item.value.kodeexternal = e.kodeexternal
    item.value.reportdisplay = e.reportdisplay
    item.value.status = e.status
    modalDetail.value = true
}
const clear = () => {
    item.value.id = ''
    item.value.kategorydiagnosa = ''
    item.value.rinciankodediagnosa = ''
}
const changeView = (e: any) => {
    selectView.value = e
}

const changeSwitch = (e: any) => {
    fetchData()
}

fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>