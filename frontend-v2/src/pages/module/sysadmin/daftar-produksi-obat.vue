<template>
    <ConfirmDialog />

    <div class="column is-12">
        <VCard>
            <div class="column c-title pt-2 mb-0">
                <div class="columns p-2">
                    <div class="column is-10">
                        <label class="title-page">Master Barang Produksi</label>
                        <label for="">List Daftar Barang Produksi</label>
                    </div>
                </div>
            </div>

            <div class="column is-12 mt-5">
                <DataTable :value="dataSource" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="loadSearch"
                    class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                    sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                      <template #header>
                        <div class="columns is-multiline pb-3">
                            <div class="column is-6">
                                <div class="column is-9">
                                    <VField class="is-autocomplete-select">
                                        <VLabel>Produk</VLabel>
                                        <VControl icon="feather:search" class="prime-auto-select">
                                            <AutoComplete v-model="item.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                                            :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                            :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" placeholder="ketik untuk mencari..."
                                            @item-select="fetchData(item.produk)" />
                                        </VControl>
                                     </VField>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="column is-4" style="margin-left: auto;">
                                     <VButton type="button" icon="fas fa-plus-circle" RouterLink
                                        :to="{ name: 'module-sysadmin-form-master-barang-produksi' }"
                                        class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
                                        Produksi Obat
                                    </VButton>
                                </div>  
                            </div>
                        </div>
                    </template>
                    <Column expander style="width: 5rem" />
                    <Column field="no" header="No"></Column>
                    <Column field="namaprodukproduksi" header="Nama Produk Produksi"></Column>
                    <Column field="satuanproduksi" header="Satuan Standar"></Column>
                    <Column field="qtyhasil" header="QTY Produksi"></Column>
                    <Column :exportable="false" header="##" style="text-align: center;">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="fas fa-edit" class="mr-2" color="success" circle outlined
                                raised v-tooltip.top="'Edit'" @click="gotoPageEdit(slotProps.data)">
                            </VIconButton>
                            <VIconButton type="button" icon="fas fa-trash" :loading="loadHapus" class="mr-2" color="danger" circle outlined
                                raised v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                            </VIconButton>
                        </template>
                    </Column>
                    <template #expansion="slotProps">
                        <div class="p-3">
                            <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple">
                                <Column field="no" header="No" />
                                <Column field="namaproduk" header="Nama Produk Bahan" />
                                <Column field="satuanstandar" header="Satuan" style="text-align: center;" />
                                <Column field="jumlah" header="Jumlah" />
                            </DataTable>
                        </div>
                    </template>
                </DataTable>
            </div>
        </VCard>
    </div>

</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import AutoComplete from 'primevue/autocomplete';
import { useHead } from '@vueuse/head'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import OverlayPanel from 'primevue/overlaypanel';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as qzService from '/@src/utils/qzTrayService'


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';

useHead({
    title: 'Daftar Barang Produksi - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const router = useRouter()
const confirm = useConfirm()
const dataSource: any = ref([])
const detailResep: any = ref([])
const op = ref();
const selected: any = ref({})
const modalDetail: any = ref(false)
const modalRetur: any = ref(false)
const expandedRows = ref();
let d_ruangan: any = ref([])
const d_produk: any = ref([])
let loadSearch: any = ref(false)

const fetchData = async () => {

    let produk = item.value.produk ? `?produkProduksiId=${item.value.produk.id}` : ''
  
    loadSearch.value = true
    await useApi().get(`/sysadmin/master-produksi-obat${produk}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            expandedRows.value = element.details.forEach((data: any, i: any) => {
                data.no = i + 1
            })
            element.no = i + 1
        });
        dataSource.value = response.data
        loadSearch.value = false
    }).catch((err: any) => {
        loadSearch.value = false
    })
}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda yakin menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteProduk(e)
        },
        reject: () => { },
    })
}

const deleteProduk = async (e: any) => {
    
    loadSearch.value = true
    await useApi().post('/sysadmin/delete-produksi-obat', { 'produproduksifk': e.produproduksifk }).then((response) => {
        fetchData()
    }).catch((err: any) => {

    })
}

const gotoPageEdit = (e: any) => {

    router.push({
        name: 'module-sysadmin-form-master-barang-produksi',
        query: {
            produkproduksifk: e.produproduksifk,
            },
    })

}

const fetchProduk = async (e: any) => {
let search = e.query ? `?namaproduk=${e.query}` : ''
  await useApi().get(`logistik/get-combo-barang-logistik${search}`).then((response) => {
    d_produk.value = response
  })
}

fetchData()

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
