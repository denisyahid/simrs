<template>
    <ConfirmDialog />
    <VCard>
        <div class="columns column c-title">
            <h3 class="title is-5 mb-2 ml-2" style="z-index:1">Jenis Indikator</h3>
        </div>

        <div class="columns is-multiline ml-4">
            <div class="column is-4">
                <img src="/images/avatars/label/validasi.png" slt="image status keluar" class="s-keluar">
                <VCard>
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <h3 class="title is-6 mb-2 mr-1">
                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                                {{ item.id ? 'Edit Data' : ' Tambah Data' }}
                            </h3>
                        </div>
                        <div class="column is-12">
                            <VField label="Jenis Indikator">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.jenisindikator" placeholder="Jenis Indikator"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12 mr-3 ml-3 pt-3" v-if="item.id" style="display:flex; padding: 0px;">
                            <VField label="Kode">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kodeexternal" placeholder="Kode" class="is-rounded" />
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
                            <VField label="Kode">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kode" placeholder="Kode" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-12">
                            <div>
                                <VButton @click="save(item)" :loading="isLoadingButton" type="button"
                                    :icon="item.id ? 'feather:edit' : 'feather:save'" class="is-fullwidth mr-3"
                                    :color="item.id ? 'info' : 'success'" raised>
                                    {{ item.id ? 'Update Data' : 'Simpan Data' }}
                                </VButton>
                                <VButton v-if="item.id" @click="clear()" type="button" icon="feather:x-circle"
                                    class="is-fullwidth is-outlined is-warning mt-3" raised>
                                    Batal Edit
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
                            <VSwitchBlock v-model="isAktif" label="Aktif" color="danger" />
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
                        <Column field="jenisindikator" header="Jenis Indikator" :sortable="true"></Column>
                        <Column field="kodeexternal" header="Kode" :sortable="true"></Column>
                        <Column header="Status" :exportable="false" style="text-align: center">
                            <template #body="slotProps">
                                <VTag :color="isAktif == true ? 'primary' : 'danger'" :label="slotProps.data.status"
                                    rounded />
                            </template>
                        </Column>
                        <Column :exportable="false" header="Action" style="text-align: center;">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined
                                    raised v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                                </VIconButton>
                                <VIconButton type="button" :disabled="!isAktif" icon="fas fa-trash" color="danger" circle
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
                                        <span class="dark-inverted mb-2">{{ item.jenisindikator }}</span>
                                        <span class="dark-inverted">{{ item.kodeexternal }}</span>

                                    </div>
                                    <VTag :color="item.status_c" :label="item.status" style="margin-left:25px" />
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
import { watch } from 'vue'
useHead({
    title: 'Master Jenis Indikator - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({})
const d_JenisKondisi = ref([])
const modalDetail = ref(false)
const isAktif = ref(true)

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
            items.jenisindikator.match(new RegExp(filters.value, 'i'))
        )
    })
})

const fetchData = async () => {

    isLoading.value = true
    await useApi().get(`/sysadmin/master-jenis-indikator?statusenabled=${isAktif.value}`).then((response: any) => {
        response.data.forEach((items: any, i: any) => {
            items.no = i + 1
        })
        isLoading.value = false
        dataSource.value = response.data
    })
}

const save = async (item: any) => {
    if (!item.jenisindikator) {
        useToaster().error('Status Keluar harus di isi')
        return
    }

    let objSave =
    {
        'jenisindikator': {
            'id': item.id ? item.id : '',
            'jenisindikator': item.jenisindikator,
            'statusenabled': item.statusenabled,
            'kodeexternal': item.kodeexternal ? item.kodeexternal : '',
        }
    }

    isLoadingButton.value = true
    await useApi().post(
        `/sysadmin/master-jenis-indikator/save`, objSave).then((response: any) => {
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
        `/sysadmin/master-jenis-indikator/delete`, { 'id': e.id }).then((response: any) => {
            isLoading.value = false
            clear()
            fetchData()
        }, (error) => {
        })
}

const edit = (e: any) => {
    item.value.id = e.id
    item.value.jenisindikator = e.jenisindikator
    item.value.kodeexternal = e.kodeexternal
    item.value.statusenabled = e.statusenabled
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
    item.value.jenisindikator = ''
    item.value.kodeexternal = ''
}
const changeView = (e: any) => {
    selectView.value = e
}

fetchData()

watch(isAktif, () => {
    fetchData()
})

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>