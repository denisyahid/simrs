<template>
    <ConfirmDialog />
    <VCard>
        <div class="columns column c-title">
            <h3 class="title is-5 mb-2 mr-1">Tempat Tidur</h3>
        </div>

        <div class="columns is-multiline">

            <div class="column is-8" style="margin-top:18px">
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
                    <DataTable :value="dataSourcefiltered" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                        :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                        <Column field="no" header="#"></Column>
                        <Column field="reportdisplay" header="Nama Tempat Tidur" :sortable="true"></Column>
                        <Column field="namaKamar" header="Nama Kamar" :sortable="true"></Column>
                        <Column field="statusbed" header="Status Tempat Tidur" :sortable="true"></Column>
                        <Column :exportable="false" header="Action" style="text-align:end">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined
                                    raised v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                                </VIconButton>
                                <VIconButton type="button" v-if="item.aktif == true" icon="fas fa-trash" class="mr-3"
                                    color="danger" circle outlined raised v-tooltip.top="'Hapus'"
                                    @click="dialogConfirm(slotProps.data)">
                                </VIconButton>
                                <VIconButton type="button" v-else icon="fas fa-trash" class="mr-3" color="danger" circle
                                    outlined raised v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
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
                                <div class="tile-grid-item-inner">
                                    <VAvatar size="medium" picture="/images/avatars/icon/ic-tempatTidur.png" color="primary"
                                        squared bordered />
                                    <div class="meta">
                                        <span class="dark-inverted">{{ item.reportdisplay }}</span>
                                        <span>{{ item.namaKamar }}</span>
                                        <!-- <span> Departemen : {{ item.kategoridiagnosa }}</span> -->
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
                                            <a role="menuitem" class="dropdown-item is-media"
                                                v-if="item.statusenabled == true" @click="dialogConfirm(item)">
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

            <div class="column is-4" style="margin-top:50px">
                <img src="/images/avatars/label/tempat-tidur.png" class="tt" alt="image tempat tidur" />
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
                            <VField label="Tempat Tidur">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.reportdisplay" placeholder="Nama tempat tidur"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-12" v-if="item.id" style="display:flex;padding: 0px;">
                            <VField class="column is-8 is-rounded-select is-autocomplete-select">
                                <VLabel>Kamar</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectkamarfk" :options="d_Kamar"
                                        placeholder="Pilih data" :searchable="true" />
                                </VControl>
                            </VField>

                            <VField class="column is-4">
                                <VLabel>Aktivasi</VLabel>
                                <VControl class="is-pulled-right">
                                    <VSwitchBlock style="padding:0px;margin-top:1px" v-model="item.statusenabled"
                                        label="Aktif" color="danger" />
                                </VControl>
                            </VField>

                        </div>
                        <div class="column is-12" v-else>
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Kamar</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectkamarfk" :options="d_Kamar"
                                        placeholder="Pilih data" :searchable="true" />
                                </VControl>
                            </VField>
                        </div>


                        <div class="column is-8" style="padding-top: 10px;">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Status Tempat Tidur</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectstatusbedfk" :options="d_statusBed"
                                        placeholder="Pilih data" :searchable="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="No Tempat Tidur">
                                <VControl>
                                    <VInput type="text" v-model="item.nomorbed" placeholder="EX.001" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-12">
                            <div v-if="item.id">
                                <VButton @click="save()" :loading="isLoadingBtn" type="button" icon="feather:edit"
                                    class="is-fullwidth mr-3" color="info" raised>
                                    Update Data
                                </VButton>
                                <VButton @click="clear()" type="button" icon="feather:x-circle"
                                    class="is-fullwidth is-outlined is-warning mt-3" raised>
                                    Batal Edit
                                </VButton>
                            </div>
                            <div v-else>
                                <VButton @click="save()" :loading="isLoadingBtn" type="button" icon="feather:save"
                                    class="is-fullwidth mr-3" color="success" raised>
                                    Simpan Data
                                </VButton>
                            </div>
                        </div>
                    </div>
                </VCard>
            </div>

        </div>
        <VModal :open="modalDetail" title="Detail Tempat Tidur" actions="right" @close="modalDetail = false, clear()">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VField label="Tempat Tidur">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.reportdisplay" placeholder="Tempat Tidur"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Kamar">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.namakamar" placeholder="Kamar" class="is-rounded"
                                        readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-8">
                            <VField label="Kondisi Tempat Tidur">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.statusbed" placeholder="Tempat Tidur"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Kode External">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kodeexternal" placeholder="Kode External"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Nomer Tempat Tidur">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.nomorbed" placeholder="Nomer Bed" class="is-rounded"
                                        readonly style="cursor:pointer" />
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
import ConfirmDialog from 'primevue/confirmdialog'
import InputSwitch from 'primevue/inputswitch'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'

import { useViewWrapper } from '/@src/stores/viewWrapper'

useHead({
    title: 'Tempat Tidur - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const confirm = useConfirm()
const item: any = ref({ aktif: true })

const d_Kamar = ref([])
const d_statusBed = ref([])
const modalDetail = ref(false)
const filters = ref('')

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
let dataSource: any = ref([])
let isLoadingBtn: any = ref(false)
let isLoading: any = ref(false)

const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.reportdisplay.match(new RegExp(filters.value, 'i'))
        )
    })
})

const fetchData = async () => {
    isLoading.value = true

    await useApi().get(`/sysadmin/master-tempat-tidur?statusenabled=${item.value.aktif}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            let nameKamar = (element.namakamar.length > 25) ? `${element.namakamar.substring(0, 25)}...` : element.namakamar
            element.namaKamar = nameKamar
        })
        dataSource.value = response.data
        isLoading.value = false
    })
}

const loadDropdown = async () => {

    const response = await useApi().get(`/sysadmin/master-tempat-tidur/select-item`)
    d_Kamar.value = response.namakamar.map((e: any) => {
        return { label: e.namakamar, value: e.id }
    })
    d_statusBed.value = response.statusbed.map((e: any) => {
        return { label: e.statusbed, value: e.id }
    })

}

const save = async () => {
    if (!item.value.reportdisplay) {
        useToaster().error('Nama Tempat Tidur harus di isi')
        return
    }
    if (!item.value.objectkamarfk) {
        useToaster().error('Kamar harus di isi')
        return
    }
    if (!item.value.objectstatusbedfk) {
        useToaster().error('Status Tempat Tidur harus di isi')
        return
    }
    if (!item.value.nomorbed) {
        useToaster().error('Kode Diagnosa harus di isi')
        return
    }
    var objSave =
    {
        'tempattidur': {
            'id': item.value.id ? item.value.id : '',
            'reportdisplay': item.value.reportdisplay,
            'statusenabled': item.value.statusenabled,
            'nomorbed': item.value.nomorbed,
            'objectstatusbedfk': item.value.objectstatusbedfk,
            'objectkamarfk': item.value.objectkamarfk,
        }
    }
    isLoadingBtn.value = true
    await useApi().post(
        `/sysadmin/master-tempat-tidur/save`, objSave).then((response: any) => {
            isLoadingBtn.value = false
            clear()
            fetchData()
        }, (error) => {
            isLoadingBtn.value = false
            // console.log(error)
        })
}

const deleteItem = async (e: any) => {
    isLoading.value = true
    await useApi().post(
        `/sysadmin/master-tempat-tidur/delete`, { 'id': e.id }).then((response: any) => {
            clear()
            fetchData()
            isLoading.value = false
        }, (error) => {
        })
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

const edit = (e: any) => {
    item.value.id = e.id
    item.value.reportdisplay = e.reportdisplay
    item.value.statusenabled = e.statusenabled
    item.value.nomorbed = e.nomorbed
    item.value.objectkamarfk = e.objectkamarfk
    item.value.namakamar = e.namakamar
    item.value.objectstatusbedfk = e.objectstatusbedfk
}
const detail = (e: any) => {
    item.value.id = e.id
    item.value.reportdisplay = e.reportdisplay
    item.value.namakamar = e.namakamar
    item.value.statusbed = e.statusbed
    item.value.nomorbed = e.nomorbed
    item.value.kodeexternal = e.kodeexternal
    item.value.status = e.status
    modalDetail.value = true
}

const clear = () => {
    item.value.id = ''
    item.value.reportdisplay = ''
    item.value.nomorbed = ''
    item.value.namakamar = ''
    item.value.objectkamarfk = ''
    item.value.objectstatusbedfk = ''
}

const changeView = (e: any) => {
    selectView.value = e
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