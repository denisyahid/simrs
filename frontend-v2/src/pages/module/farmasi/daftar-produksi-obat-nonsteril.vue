<template>
    <ConfirmDialog />

    <div class="column is-12">
        <VCard>
            <div class="column c-title pt-2 mb-0">
                <div class="columns p-2">
                    <div class="column is-10">
                        <label class="title-page">Daftar Produksi Obat Non Steril</label>
                        <label for="">List Obat Non Steril</label>
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
                        <div class="columns is-multiline pb-3 pt-3">
                            <div class="column is-3">
                                   <VField label="Periode Tanggal" style="margin-bottom: 6px;" />
                                    <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField addons>
                                                <VControl icon="feather:calendar">
                                                    <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                                </VControl>
                                                <VControl>
                                                    <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                                                </VControl>
                                                <VControl icon="feather:calendar">
                                                    <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                            </div>

                            <div class="column is-2">
                                <VField>
                                    <VLabel>No Produksi</VLabel>
                                    <VControl>
                                        <input v-model="item.nostruk" v-on:keyup.enter="fetchData()" class="input" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-1 btn-search mt-3">
                                <VIconButton color="success" icon="fas fa-search" @click="fetchData()" :loading="loadSearch" />
                            </div>
                            
                            <div class="column is-6">
                                 <div class="column is-3 p-0" style="margin-left: auto;">
                                     <VButton type="button" icon="fas fa-plus-circle" RouterLink
                                        :to="{ name: 'module-farmasi-produksi-obat' }"
                                        class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
                                        Tambah Produksi
                                    </VButton>
                                </div>  
                            </div>
                        </div>
                          
                    </template>
                    <Column expander style="width: 5rem" />
                    <Column field="no" header="No"></Column>
                    <Column field="tglstruk" header="Tgl Produksi">
                      <template #body="slotProps">
                        {{H.formatDate(slotProps.data.tglstruk, 'DD/MM/YYYY')}}
                      </template>
                    </Column>
                    <Column field="nostruk" header="No Produksi"></Column>
                    <Column field="namaruangan" header="Nama Ruangan Penerima"></Column>
                    <Column field="namapenerima" header="Nama Penerima"></Column>
                    <Column :exportable="false" header="##" style="text-align: center;">
                        <template #body="slotProps">
                            <!-- <VIconButton type="button" icon="fas fa-edit" class="mr-2" color="success" circle outlined
                                raised v-tooltip.top="'Edit'" @click="gotoPageEdit(slotProps.data)">
                            </VIconButton> -->
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
                                <Column field="namaproduk" header="Nama Produk" />
                                <Column field="satuanstandar" header="Satuan" style="text-align: center;" />
                                <Column field="qtyproduk" header="Qty" />
                                <Column field="hargasatuan" header="Harga Satuan" style="text-align:right">
                                   <template #body="slotProps">
                                        {{H.roundToDecimal( parseFloat(slotProps.data.hargasatuan), 2)}}
                                    </template>
                                </Column>
                                <Column field="hargadiscount" header="Discount" style="text-align:right">
                                 <template #body="slotProps">
                                          {{H.roundToDecimal( parseFloat(slotProps.data.hargadiscount), 2)}}
                                    </template>
                                </Column>
                                <Column field="hargappn" header="PPN" style="text-align:right">
                                    <template #body="slotProps">
                                          {{H.roundToDecimal(parseFloat(slotProps.data.hargappn), 2)}}
                                    </template>
                                </Column>
                                <Column field="total" header="Total" style="text-align:right">
                                  <template #body="slotProps">
                                          {{H.roundToDecimal(parseFloat(slotProps.data.total), 2)}}
                                    </template>
                                </Column>
                                <Column field="tglkadaluarsa" header="Tgl Kadaluarsa">
                                  <template #body="slotProps">
                                          {{H.formatDate(slotProps.data.tglkadaluarsa, 'DD/MM/YYYY')}}
                                    </template>
                                </Column>
                                <Column field="nobatch" header="No Batch" />
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
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const router = useRouter()
const confirm = useConfirm()
const dataSource: any = ref([])
const expandedRows = ref();
let loadSearch: any = ref(false)

const fetchData = async () => {

    let produk = item.value.nostruk ? `&nostruk=${item.value.nostruk}` : ''
    let tglAwal = `?tglAwal=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
    let tglAkhir = `&tglAkhir=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
    loadSearch.value = true
    await useApi().get(`/sysadmin/get-daftar-produksi-obat-non-steril${tglAwal}${tglAkhir}${produk}`).then((response: any) => {
        response.daftar.forEach((element: any, i: any) => {
            expandedRows.value = element.details.forEach((data: any, i: any) => {
                data.no = i + 1
            })
            element.no = i + 1
        });
        dataSource.value = response.daftar
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
            loadSearch.value = true
            useApi().post('/sysadmin/delete-obat-produksi-non-steril', { 'norec' : e.norec }).then((response) => {
                fetchData()
            })
        },
        reject: () => { },
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
