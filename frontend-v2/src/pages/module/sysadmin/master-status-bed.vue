<template>
    <ConfirmDialog/>
    <VCard>
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
                    <DataTable :value="dataSourcefiltered" :paginator="true" :rows="5"
                        :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" class="p-datatable-sm"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                        <Column field="no" header="#"></Column>
                        <Column field="statusbed" header="Kondisi Tempat Tidur" :sortable="true"></Column>
                        <Column field="nourut" header="Nomer Urut" :sortable="true"></Column>
                        <Column field="status" header="Status Aktivasi" :sortable="true"></Column>
                        <Column :exportable="false" header="Action" style="text-align: center;">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined
                                    raised v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                                </VIconButton>
                                <VIconButton type="button" v-if="item.aktif == true" icon="fas fa-trash" class="mr-3" color="danger" circle
                                    outlined raised v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
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
                                <div class="tile-grid-item-inner">
                                    <VAvatar size="medium" picture="/images/avatars/icon/ic-tempatTidur.png" color="primary" squared bordered />

                                    <div class="meta">
                                        <span class="dark-inverted mb-2">{{ item.statusbed }}</span>

                                    </div>
                                    <VTag :color="item.status_c" :label="item.status" style="margin-left:25px" />
                                    <VDropdown icon="feather:more-vertical" spaced right>
                                        <template #content>
                                            <a role="menuitem" class="dropdown-item is-media" @click="detail(item)">
                                                <div class="icon">
                                                    <i class="iconify" data-icon="feather:bookmark"
                                                        aria-hidden="true"></i>
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
                                            <a role="menuitem" v-if="item.statusenabled == true" class="dropdown-item is-media" @click="dialogConfirm(item)">
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
                            <VField label="Kondisi Tempat Tidur">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.statusbed" placeholder="Kondisi Tempat Tidur"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12" v-if="item.id" style="display:flex; padding: 0px;">
                            <VField class="column is-8" label="Nomer Urut">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.nourut" placeholder="Nomer Urut"
                                        class="is-rounded" />
                                </VControl>
                            </VField>

                            <VField class="column is-4">
                                <VLabel>Aktivasi</VLabel>
                                <VControl class="is-pulled-right">
                                    <VSwitchBlock style="padding-top:3px" v-model="item.statusenabled" label="Aktif"
                                        color="danger"/>
                                </VControl>
                            </VField>

                        </div>
                        <div class="column is-12" v-else>
                            <VField label="Nomer Urut">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.statusbed" placeholder="Nomer Urut"
                                        class="is-rounded" />
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
        <VModal :open="modalDetail" title="Detail Kategori Diagnosa" actions="right" @close="modalDetail = false,clear()">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VField label="Status Keluar">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kategorydiagnosa" placeholder="Kelompok Produk"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Rincian Kode Diagnosa">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.rinciankodediagnosa" placeholder="Rincian Kode Diagnosa"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Setatus Aktivasi">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.status" placeholder="status" class="is-rounded" readonly style="cursor:pointer"/>
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
import { ref, computed} from 'vue'
import { useRoute} from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ConfirmDialog from 'primevue/confirmdialog'
import InputSwitch from 'primevue/inputswitch'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Kondisi Tempat Tidur - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({aktif: true})
const modalDetail = ref(false)
const confirm = useConfirm()

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
let isLoadingBtn: any = ref(false)
let isLoading: any = ref(false)

const currentPage: any = ref({
    limit: 5,
    rows: 50,
})
const filters = ref('')

const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.statusbed.match(new RegExp(filters.value, 'i'))
        )
    })
})

const fetchData = async ()=>{
    isLoading.value = true

    await useApi().get(`/sysadmin/master-status-bed?statusenabled=${item.value.aktif}`).then((response:any)=>{
        response.data.forEach((element:any,i:any)=>{
            element.no = i+1
        })
        dataSource.value = response.data
        isLoading.value = false
    })
}

const save = async ()=>{
    if (!item.value.statusbed) {
        useToaster().error('Kondisi Tempat Tidur Diagnosa harus di isi')
        return
    }
    var objSave =
    {
        'statusbed': {
            'id': item.value.id ? item.value.id : '',
            'statusbed': item.value.statusbed,
            'nourut': item.value.nourut ? item.value.nourut : '',
            'statusenabled': item.value.statusenabled,
        }
    }
    isLoadingBtn.value = true
    await useApi().post(
        `/sysadmin/master-status-bed/save`, objSave).then((response: any) => {
            isLoadingBtn.value = false
            clear()
            fetchData()
        }, (error) => {
            isLoadingBtn.value = false
            // console.log(error)
        })
}

const deleteItem =  async (e: any)=>{
    isLoading.value = true
    await useApi().post(
        `/sysadmin/master-status-bed/delete`, { 'id': e.id }).then((response: any) => {
            isLoading.value = false
            fetchData()
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

const edit = (e: any)=>{
    item.value.id = e.id
    item.value.statusbed = e.statusbed
    item.value.nourut = e.nourut
    item.value.statusenabled = e.statusenabled
}
const detail = (e: any)=>{
    item.value.id = e.id
    item.value.statusbed = e.statusbed
    item.value.nourut = e.nourut
    item.value.kodeexternal = e.kodeexternal
    item.value.status = e.status
    modalDetail.value = true
}

const clear = ()=>{
    item.value.id = ''
    item.value.statusbed = ''
    item.value.nourut = ''
}
const changeView = (e: any)=>{
    selectView.value = e
}

const changeSwitch = (e:any)=>{
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