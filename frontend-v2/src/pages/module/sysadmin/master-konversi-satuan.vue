<template>
    <ConfirmDialog/>
    <div class="column is-12">
        <VCard>
            <div class="column c-title">
                <label style="position: relative;top: -10px;font-weight: 500;">Form Konversi Satuan</label>
            </div>
            <div class="column is-12">
                <VControl icon="feather:search">
                    <VInput type="text" v-model="item.nama" v-on:keyup.enter="fetchData(item)" placeholder="Nama Produk"
                        class="is-rounded" />
                </VControl>
            </div>

            <div class="column is-12">
                <div class="columns" v-if="isLoading">
                    <div class="column is-3" v-for="key in 4" :key="key">
                        <VCard class="p-2">
                            <div class="tile-grid-item">
                                <VFlexTableCell :column="{ grow: true, media: true }">
                                    <VPlaceloadText :lines="2" width="90%" last-line-width="50%" class="mx-2" />
                                    <VCheckbox circle />
                                </VFlexTableCell>
                            </div>
                        </VCard>
                    </div>
                </div>
                <div class="columns" v-else>
                    <div class="column is-3" v-for="item in dataSource" :key="item.id">
                        <VCard class="p-2" style="cursor: pointer;">
                            <div class="tile-grid-item">
                                <h3 style="font-weight: 600;">{{ item.namaproduk }}</h3>
                                <div class="flex" style="justify-content: space-between;align-items: end;">
                                    <h3 style="color:darkgray" class="mt-1">ID Produk : {{ item.id }}</h3>
                                    <RadioButton v-model="selectedProduk" :inputId="item.id" :value="item.id"
                                        @change="getData(item)" />
                                </div>
                            </div>
                        </VCard>
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="content">
                        <div class="is-divider" data-content="Konversi Satuan" />
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="columns">
                        <div class="column is-3">
                            <VField label="Satuan Asal">
                                <VControl>
                                    <input v-model="item.satuanasal" type="text" class="input is-rounded"
                                        placeholder="Satuan Asal..." disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField class="is-rounded-select is-autocomplete-select" label="Satuan Tujuan">
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.satuantujuan" :options="d_Satuan"
                                        placeholder="Pilih data" :searchable="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField label="Nilai Konversi">
                                <VControl icon="feather:bookmark">
                                    <input v-model="item.nilaikonversi" type="text" class="input is-rounded"
                                        placeholder="Nilai Konversi..." />
                                </VControl>
                            </VField>
                        </div>
                        <VButtons class="mt-5 ml-5">
                            <VButton type="button" rounded v-if="item.id" color="info" :loading="isLoadingBtn" raised icon="feather:plus-circle" @click="saveData(item)">Update</VButton>
                            <VButton type="button" rounded v-else color="info" :loading="isLoadingBtn" raised icon="feather:plus-circle" @click="saveData(item)">Simpan</VButton>
                            <VButton type="button" rounded color="danger" raised icon="feather:x-circle" @click="clear()">Batal</VButton>
                        </VButtons>

                    </div>
                </div>
            </div>
        </VCard>
    </div>

    <div class="column is-12">
        <VCard>
            <DataTable :value="dataSourceKonSat" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                :loading="dataSourceKonSat.isLoading" class="p-datatable-sm"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                <Column field="no" header="No"></Column>
                <Column field="namaproduk" header="Produk"></Column>
                <Column field="satuanasal" header="Satuan Asal"></Column>
                <Column field="satuantujuan" header="Satuan Tujuan"></Column>
                <Column field="nilaikonversi" header="Nilai Konversi"></Column>
                <Column :exportable="false" header="#" style="text-align: center;">
                    <template #body="slotProps">
                        <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                            v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                        </VIconButton>
                        <VIconButton v-if="item.aktif" type="button" icon="fas fa-trash" class="mr-3" color="danger" circle
                            outlined raised v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                        </VIconButton>
                    </template>
                </Column>
            </DataTable>
        </VCard>
    </div>
</template>
    
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import RadioButton from 'primevue/radiobutton';
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { object } from 'zod';
useHead({
    title: 'Konversi Satuan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
})
const selectedProduk = ref();
const route = useRoute()
let isLoadingBtn: any = ref(false)
let idSatuanAsal:any
const modalDetail = ref(false)
const confirm = useConfirm()
let dataSource: any = ref([])
let dataSourceKonSat: any = ref([])
let d_Satuan:any = ref([])
let isLoading: any = ref(false)

const fetchData = async (e: any) => {
    isLoading.value = true
    let namaproduk = e.nama ? `namaproduk=${e.nama}` : ''
    let produkfk = e.id ? '&produkfk=' + e.id : ''
    await useApi().get(`/sysadmin/master-konversi-satuan/get-produk?${namaproduk}${produkfk}`).then((response) => {
        isLoading.value = false
        dataSource.value = response
    })
}      

const saveData = async (e:any)=>{

    isLoadingBtn.value = true
    // if(!item.value.satuanasal){
    //     useToaster().error('Satuan Asal harus di isi')
    //     return
    // }
    if(!item.value.satuantujuan){
        useToaster().error('Satuan Tujuan harus di isi')
        return
    }
    if(!item.value.nilaikonversi){
        useToaster().error('Nilai Konversi harus di isi')
        return
    }

    let objSave = {
        'id' : item.value.id ? item.value.id : '',
        // 'statusenabled' : item.value.statusenabled,
        'nilaikonversi' : item.value.nilaikonversi,
        'satuanasal' : idSatuanAsal,
        'produk' : selectedProduk.value,
        'satuantujuan' : item.value.satuantujuan
    }

    await useApi().post(`/sysadmin/master-konversi-satuan/save`,objSave).then((response)=>{
        isLoadingBtn.value = false
        clear()
        fetchDataKonversiSatuan()
    }).catch((error)=>{
        isLoadingBtn.value = false
    })
}

const edit = (e:any)=>{
    item.value.id = e.id
    item.value.satuan = e.nilaikonversi
    item.value.satuantujuan = e.ssidtujuan
    item.value.satuanasal = e.satuanasal
    item.value.nilaikonversi = e.nilaikonversi
}

const getData = async (e: any) => {
    dataSourceKonSat.value.isLoading = true
    await useApi().get(`/sysadmin/master-konversi-satuan?produkfk=${e.id}`).then((response)=>{
        response.forEach((element:any) => {
            item.value.satuanasal = element.satuanstandar_asal
            idSatuanAsal = element.ssidasal
        });
    })   
    fetchDataKonversiSatuan()
}

const listSatuan = async ()=>{
    await useApi().get('/sysadmin/master-konversi-satuan/list-satuan').then((response)=>{
        d_Satuan.value = response.map((e:any)=>{
            return {label:e.satuanstandar, value:e.id}
        })
        dataSourceKonSat.value.isLoading = false
    })
}

const fetchDataKonversiSatuan = async ()=>{
    await useApi().get(`/sysadmin/master-konversi-satuan/data-konversi-satuan-by-produk?produkId=${selectedProduk.value}`).then((response)=>{
        response.forEach((element:any,i:any) => {
            element.no = i+1
        });
        dataSourceKonSat.value = response
        // console.log(dataSourceKonSat)
    })
}

const deleteItem = async (e:any)=>{
  console.log(e)
  isLoading.value = true
  await useApi().post(
    `/sysadmin/master-konversi-satuan/delete`, { 'id': e.id }).then((response: any) => {
        isLoading.value = false
      clear()
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

const clear = ()=>{
    item.value.id = ''
    item.value.nama = ''
    item.value.nilaikonversi = ''
    item.value.satuantujuan = ''
    item.value.satuanasal = ''
    dataSource.value = ''
}

// data-konversi-satuan-by-produk
listSatuan()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>