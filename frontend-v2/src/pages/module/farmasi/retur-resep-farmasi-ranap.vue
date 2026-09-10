<!-- <route lang="yaml">
    meta:
      requiresAuth: true
 </route> -->
<template>
    <div class="columns">
        <div class="column is-12 form-layout is-stacked">
            <div class=" form-outer">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>{{ TITLE_PAGE }}</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined>
                                    Cancel
                                </VButton>
                                <VButton
                                    icon="feather:save"
                                    type="submit"
                                    :disabled="isDisabledSave"
                                    color="primary"
                                    @click="handleSaveClick"
                                    raised
                                    :loading="isSimpan"
                                >
                                    Save
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body">
                    <div class="columns is-multiline" v-if="item.header.nocm">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VCard>
                                        <div class="columns is-multiline pt-3">
                                           <div class="column is-4">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText class="item">Ruangan</VLabelText>
                                                    <VControl icon="feather:search" class="prime-auto-select">
                                                        <Dropdown v-model="item.ruangan" :options="d_ruangan"
                                                            :optionLabel="'label'" class="is-radiusless"
                                                            placeholder="Pilih data" style="width: 100%;font-weight:bold" showClear
                                                            :filter="true" />
                                                    </VControl>

                                                </VField>
                                            </div>
                                        </div>

                                        <div class="mb-5">
                                            <VField>
                                                <VLabel class="item required-field">Keterangan Retur</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.keteranganretur"
                                                        placeholder="Keterangan Retur" class="is-radiusless" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <VButton color="warning" class="mr-2" @click="openModalReturRanap" raised>
                                            Pilih Item
                                        </VButton>
                                        <VButton v-if="userLogin.kelompokUser.kelompokUser == 'it'"  color="info" @click="openModalReturPerawat" raised>
                                            Verifikasi Retur Perawat
                                        </VButton>
                                        <!-- <VButton color="info" @click="getDataReturPerawat()" raised>
                                            Retur Perawat
                                        </VButton> -->
                                    </VCard>
                                </div>
                                <div class="column is-12">
                                    <VCard>
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <DataTable :value="dataSourceToUpdate[0]" :paginator="true" :rows="10"
                                                    :rowsPerPageOptions="[5, 10, 25]"
                                                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                                    <Column field="no" header="No" />
                                                    <Column field="namaproduk" header="Produk" :sortable="true" />
                                                    <Column field="aturanpakai" header="Aturan Pakai" />
                                                    <Column field="satuanstandar" header="Satuan" />
                                                    <Column field="jumlah" header="Qty" />
                                                    <Column field="jmlretur" header="Retur" >
                                                        <template #body="{ data, field }">
                                                            <InputText v-model="data[field]" type="text" />
                                                        </template>
                                                    </Column>
                                                    <template #paginatorstart>
                                                        <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                                                    </template>
                                                    <template #paginatorend>
                                                        <Button type="button" icon="pi pi-cloud" class="p-button-text" />
                                                    </template>
                                                </DataTable>
                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12">
                            <div class="content">
                                <div class="is-divider" data-content="Total Keseluruhan" />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <VModal :open="modalRetur" title="Retur Resep" size="medium" actions="right" @close="modalRetur = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-3">
                            <VField>
                                <VLabel>R/sK</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.rke" disabled class="is-rounded" style="font-weight:bold" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-9">
                            <VField>
                                 <VLabel>Asal Produk</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.asal" disabled class="is-rounded" style="font-weight:bold"/>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-8">
                            <VField class="is-rounded-select is-autocomplete-select">
                                 <VLabel>Produk</VLabel>
                                 <VControl icon="feather:search" class="prime-auto">
                                    <AutoComplete v-model="item.produk" :suggestions="d_produk" disabled
                                        @complete="fetchProduk($event)" :optionLabel="'namaproduk'" :dropdown="true"
                                        :minLength="3" class="is-rounded" :appendTo="'body'" style="font-weight:bold!important"
                                        :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                                        placeholder="ketik untuk mencari...">
                                    </AutoComplete>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VLabel>Satuan</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.satuanstandar" placeholder="Satuan Standar" disabled style="font-weight:bold"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns">
                        <div class="column is-4">
                            <VField>
                                  <VLabel>Konversi</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" style="font-weight:bold" v-model="item.konversi" placeholder="No Resep" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                 <VLabel>Qty Produk</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" style="font-weight:bold" v-model="item.jumlah" placeholder="Produk" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VLabel class="required-field">Qty Retur</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number"  v-model="item.qtyretur" placeholder="Qty Retur"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </form>
            <div class="column is-12">
                <div class="content">
                    <div class="is-divider" data-content="Total Keseluruhan" />
                </div>
            </div>

            <div class="column is-6 p-0" style="margin-left: auto;">
                <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status" color="danger">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span class="ml-1">TOTAL</span>
                    </div>
                    <small class="text-bold-custom h-100">{{ H.formatRp(item.totalTagihan, 'Rp.') }}</small>
                </VCardCustom>
            </div>
        </template>
        <template #action>
            <VButton icon="feather:plus" :loading="isLoadBtnRet" :disabled="isDisabled" @click="subChangeRetur(item)"
                color="primary" raised>Retur</VButton>
        </template>
    </VModal>





    <VModal :open="modalReturRanap" title="Retur Resep" size="big" actions="right" @close="modalReturRanap = false">
        <template #content>
            <div class="mb-5"> 
            <VCard>
                <div class="columns is-multiline pt-3">
                    <div class="column is-12 pb-1">
                        <VField>
                            <VLabel class="item">Cari Produk</VLabel>
                                <VControl>
                                    <VInput 
                                        type="text" 
                                        v-model="item.search" 
                                        placeholder="Masukan Nama Produk Kemudian klik Enter untuk mencari data" 
                                        v-on:keyup.enter="dataResep" 
                                        class="is-radiusless" 
                                        />
                                </VControl>
                        </VField>
                    </div>
                </div>
            </VCard>
            </div>
            <DataTable :value="dataSource" :paginator="true" :rows="10"
                :rowsPerPageOptions="[5, 10, 25]"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                
                <!-- Kolom untuk checkbox Pilih Semua -->
                <Column header="" :style="{width: '3rem', textAlign: 'center', verticalAlign: 'top'}">
                <template #header>
                    <input type="checkbox" v-model="allSelected" @change="toggleAll" style="transform: scale(1.2); vertical-align: middle;" />
                </template>
                <template #body="slotProps">
                    <input type="checkbox" v-model="slotProps.data.isSelected" @change="onRowSelectChangeRetur(slotProps.data)" style="transform: scale(1.2); vertical-align: middle;" />
                </template>
                </Column>
                
                <!-- Kolom No -->
                <Column field="no" header="No" />
                <!-- Kolom R/Ke -->
                <Column field="rke" header="R/Ke" :hidden="true"/>
                <Column field="noresep" header="No Resep" />
                <!-- Kolom Kemasan -->
                <Column field="jeniskemasan" header="Kemasan" :hidden="true"/>
                <!-- Kolom Produk -->
                <Column field="namaproduk" header="Produk" :sortable="true" />
                <!-- Kolom Aturan Pakai -->
                <!-- Kolom Satuan -->
                <Column field="satuanstandar" header="Satuan" />
                <!-- Kolom Qty -->
                <Column field="jumlah" header="Qty" />
                <Column field="ruanganresep" header="Satelit" />

                <template #paginatorstart>
                <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                </template>
                <template #paginatorend>
                <Button type="button" icon="pi pi-cloud" class="p-button-text" />
                </template>
            </DataTable>
        </template>
        <template #action>
            <VButton icon="feather:plus" :loading="isLoadBtnRet" :disabled="isDisabled" @click="pushData()"
                color="primary" raised>Retur</VButton>
        </template>
    </VModal>
    <VModal :open="modalReturPerawat" title="Verifikasi Retur Resep Perawat" size="big" actions="center" @close="modalReturPerawat = false">
        <template #content>
            <DataTable :value="dataSourceReturPerawat" :paginator="true" :rows="10"
                :rowsPerPageOptions="[5, 10, 25]"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                
                <Column header="Pilih Semua" :style="{width: '3rem', textAlign: 'center', verticalAlign: 'top'}">
                <template #header>
                    <input type="checkbox" v-model="allSelected" @change="toggleAll" style="transform: scale(1.2); vertical-align: middle;" />
                </template>
                <template #body="slotProps">
                    <input type="checkbox" v-model="slotProps.data.isSelected" @change="onRowSelectChangeRetur(slotProps.data)" style="transform: scale(1.2); vertical-align: middle;" />
                </template>
                </Column>
                
                <!-- Kolom No -->
                <Column field="no" header="No" />
                <!-- Kolom R/Ke -->
                <Column field="rke" header="R/Ke" :hidden="true"/>
                <Column field="noresep" header="No Resep" />
                <!-- Kolom Kemasan -->
                <!-- <Column field="jeniskemasan" header="Kemasan" :hidden="true"/> -->
                <!-- Kolom Produk -->
                <Column field="namaproduk" header="Produk" :sortable="true" />
                <!-- Kolom Aturan Pakai -->
                <!-- Kolom Satuan -->
                <Column field="satuanstandar" header="Satuan" />
                <Column field="jumlah" header="Jumlah"/>
                <!-- Kolom Qty -->
                <Column field="jmlretur" header="Jumlah Permintaan Retur" />
                <Column field="namaruanganpeminta" header="Ruangan Peminta" />

                <template #paginatorstart>
                <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                </template>
                <template #paginatorend>
                <Button type="button" icon="pi pi-cloud" class="p-button-text" />
                </template>
            </DataTable>
        </template>
        <template #action>
            <VButton icon="feather:plus" :loading="isLoadBtnRet" :disabled="isDisabled" @click="pushData()"
                color="info" raised>Retur</VButton>
        </template>
    </VModal>

</template>
    
<script setup lang="ts">

import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
    ref,
    computed,
    defineComponent,
    watch,
    nextTick,
    onMounted,
    reactive,
    watchEffect
} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { formatRp } from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
const TITLE_PAGE = 'Retur Resep'
useHead({
    title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let NOREC_PD = useRoute().query.norec_pd as string
// let ID_PASIEN = useRoute().query.nocmfk as string
// let NOREC_APD = useRoute().query.norec_apd as string
// let NOREC_ORDER: any = useRoute().query.norec_order as string
// let NOREC_RESEP: any = useRoute().query.norec_resep as string
let item: any = reactive({
    header: {},
    totalAll: 0,
    jumlah: 0,
    persenDiskon: 0,
    hargadiskon: 0,
    tglAwal: new Date(),
    rke: 1,
    resep: '-',
})
const modalInput = ref(false)
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const props =defineProps({
    NOREC_PASIEN_DAFTAR :{
        type: String as PropType<any>,
    },
    NOREC_APD :{
        type: String as PropType<any>,
    }
})
const d_penulisResep: any = ref([])
const d_ruangan: any = ref([])
const d_produk: any = ref([])
const dataSource: any = ref([])
const dataSourceToUpdate: any = ref([])
const dataSelected: any = ref([])
const indexNorecResep: any = ref([])
const isSimpan: any = ref(false)
const allSelected = ref(false);
const modalRetur: any = ref(false)
const modalReturRanap: any = ref(false)
const dataGridKronis: any = ref([])
const isLoadBtnRet: any = ref(false)
const isDisabled: any = ref(false)
const isDisabledSave: any = ref(false)
const userLogin = useUserSession().getUser()
const isVerifOrderPerawat:any =ref(false)
const modalReturPerawat: any = ref(false)
const dataSourceReturPerawat: any = ref([])

const handleSaveClick = () => {
    if(!item.ruangan){
        H.alert('error', 'Silahkan Masukkan Ruangan Terlebih Dahulu')
        return
    }

    isSimpan.value = true; 
    isDisabledSave.value = true;
    if(isVerifOrderPerawat.value === true) saveReturPerawat();
    else saveRetur(item);

};
const loadData = async () => {
    await listCombo()
    await fetchDetailPasien()
    // await dataResep()
}

const fetchDetailPasien = async () => {
    console.log('norec pd pasien',props.NOREC_PASIEN_DAFTAR);
    console.log('norec pd pasien',props.NOREC_APD);
    
    await useApi().get(`/farmasi/input-resep-header?norec_pd=${props.NOREC_PASIEN_DAFTAR}`).then((response: any) => {
        item.header = response.data
    })

    await useApi().get(`/farmasi/detail-resep-retur?norec_pd=${props.NOREC_PASIEN_DAFTAR}&apd_norec=${props.NOREC_APD}`).then((response: any) => {
        let dataResep = response.detailresep
        let detailResep = response.data
        item.detailResep = dataResep
        item.tglAwal = dataResep.tglresep
        d_penulisResep.value.forEach((e: any) => {
            if (dataResep.pgid == e.value) {
                item.penulisResep = e
            }
        });
        
        d_ruangan.value.forEach((e: any) => {
            if (dataResep.id == e.value) {
                item.ruangan = e
            }
        });
        dataSource.value = detailResep
    })
}

const dataResep = async () => {
    isLoadBtnRet.value = true
    let rsearch='',ruanganfk=''

    if(item.search) rsearch=item.search
    if(item.ruangan) ruanganfk=item.ruangan.value

    await useApi().get(`/farmasi/detail-resep-retur?norec_pd=${props.NOREC_PASIEN_DAFTAR}&apd_norec=${props.NOREC_APD}&search=${rsearch}&sr_ruanganfk=${ruanganfk}`).then((response: any) => {
        let dataResep = response.detailresep
        let detailResep = response.data
        item.detailResep = dataResep
        dataSource.value = detailResep
        if(dataResep != null) H.alert('success','sukses load data')
        else H.alert('warning','data not found')
    })
    isLoadBtnRet.value = false
}

const listCombo = async () => {
    const response2 = await useApi().get(`/logistik/kartu-stok-cbo`)
    d_ruangan.value = response2.ruangan.map((e: any) => ({ label: e.namaruangan, id: e.id }));
}

const showModal = async (e: any) => {
     e.btnLoading = true
    console.log(e)
    await fetchProduk({ query: e.productname })
    d_produk.value.forEach((element: any) => {
        if (element.id == e.produkfk) {
            item.produk = element
        }
    })
    modalRetur.value = true
    dataSelected.value = e
    item.rke = e.rke
    item.asal = e.asalproduk
    item.satuanstandar = e.satuanstandar
    item.konversi = e.nilaikonversi
    item.jumlah = e.jumlah
    item.total = e.totalAwal ? e.totalAwal : e.total
    item.totalTagihan = e.total
    item.hargadiscount = e.hargadiscount
    item.hargajual = e.hargajual
    item.qtyretur = e.jmlretur
     e.btnLoading = false
}

const fetchProduk = async (filter: any) => {
    let isDonasi = item.checkisDonasi ? `&isdonasi=${item.checkisDonasi}` : ''
    const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&ruanganfk=${item.ruangan.value}${isDonasi}&limit=10`)
    d_produk.value = response
}
const pushData = () => {    
    dataSourceToUpdate.value.push(dataSelected.value)
    modalReturRanap.value = false
    modalReturPerawat.value = false
}

const subChangeRetur = (e: any) => {
    let element = dataSelected.value
    let data: any = {}

    console.log('data cek',element);

}

const fetchDetailReturResepPerawat = () => {
    useApi().get(`/farmasi/detail-resep-retur-perawat?pd_norec=${NOREC_PD}`)
        .then((response: any) => {
            dataSourceReturPerawat.value = response.data.map((item, index) => ({
                no: index + 1,
                ...item
            }));
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });
};

const saveReturPerawat = async (e: any) => {
    isDisabledSave.value = true;
    let jmlRetur = 0
        
        //VALIDASI JUMLAH OBAT YANG DI RETUR DENGANG JUMLAH OBAT
    for (let b = 0; b < dataSourceToUpdate.value[0].length; b++) {
        const element = dataSourceToUpdate.value[0][b];
        if ( parseInt(element.jmlretur) > parseInt(element.jumlah)) {
            H.alert('warning','jumlah retur tidak boleh melebihi qty '+element.namaproduk)
            return
        }
    }
    // dataSourceToUpdate.value.forEach((element: any) => {
    //     let aya = element.jmlretur ? element.jmlretur : 0
    //     jmlRetur = jmlRetur + parseInt(aya)
    // });
    // if (!e.keteranganretur) {
    //     H.alert('error', 'Keterangan retur harus diisi');
    //     isSimpan.value = false;
    //     isDisabledSave.value = false;
    //     return;
    // }

    isSimpan.value = true
    let objSave = {
        data: dataSourceToUpdate.value,
        id_ruangan_verif:item.ruangan.id
    }
    console.log('objsave retur',objSave);
    try {
        useApi().post('farmasi/save-retur-pelayanan-perawat', objSave).then((response:any)=>{
            isSimpan.value = false
            isDisabledSave.value = true
            isVerifOrderPerawat.value = false
        });
    } catch (error) {
        H.alert('error', 'Gagal menyimpan data');
    } 
}
const openModalReturPerawat = () => {
    modalReturPerawat.value = true
    isVerifOrderPerawat.value = true
    fetchDetailReturResepPerawat()
}

// const getDataReturPerawat = () =>{
//     useApi().get(`/farmasi/detail-resep-retur-perawat?norec_pd=${props.NOREC_PASIEN_DAFTAR}&apd_norec=${props.NOREC_APD}&isreturperawat=true`).then((response: any) => {
//         let dataResep = response.detailresep
//         let detailResep = response.data
//         item.detailResep = dataResep
//         dataSource.value = detailResep
//         if(dataResep != null) H.alert('success','sukses load data')
//         else H.alert('warning','data not found')
//     })
// }

const saveRetur = async (e: any) => {

    // console.log('e data',e);
    
    isDisabledSave.value = true;
    let jmlRetur = 0

    // console.log('dataSourceToUpdate',dataSourceToUpdate.value);
    
        //VALIDASI JUMLAH OBAT YANG DI RETUR DENGANG JUMLAH OBAT
    for (let b = 0; b < dataSourceToUpdate.value[0].length; b++) {
        const element = dataSourceToUpdate.value[0][b];
        if ( parseInt(element.jmlretur) > parseInt(element.jumlah)) {
            H.alert('warning','jumlah retur tidak boleh melebihi qty '+element.namaproduk)
            return
        }
    }

    //VARIABLE TEMPORARY UNTUK  MENAMPUNG VALUE UNTUK HEAD TABLE strukretur_t
    let idnorec_resep:any=[]
    //CEK DATA VARIABLE DATA YANG AKAN DI BUAT HEAD, HARUS TIDAK BOLEH DOUBLE

    for (let x = 0; x < dataSourceToUpdate.value[0].length; x++) {
        const elementReal = dataSourceToUpdate.value[0][x];
        if (!idnorec_resep.some(item => item.norec === elementReal.norec_resep)) {
            idnorec_resep.push({
                norec: elementReal.norec_resep
            });
        }
    }

    dataSourceToUpdate.value.forEach((element: any) => {
        let aya = element.jmlretur ? element.jmlretur : 0
        jmlRetur = jmlRetur + parseInt(aya)
    });
    if (!e.keteranganretur) {
        H.alert('error', 'Keterangan retur harus diisi');
        isSimpan.value = false;
        isDisabledSave.value = false;
        return;
    }
    isSimpan.value = true
    console.log('p',e)
    let objSave = {
        strukresep: {
            alasan: e.keteranganretur,
            namapasien: e.header.namapasien,
            nocm: e.header.nocm,
            noorder: 'EditResep',
            norecResep: dataSourceToUpdate.value[0].norec_resep,
            noresep: e.detailResep.noresep,
            penulisresepfk: e.detailResep.id,
            retur: 'RERTUR',
            ruanganfk: item.ruangan.id,
            jumlahitem : dataSourceToUpdate.value.length,
            status: 0,
            totalretur : jmlRetur,
            tglresep: e.detailResep.tglresep,
        },
        pelayananpasien: dataSourceToUpdate.value,
        norec_resep : idnorec_resep
    }
    console.log('objsave retur',objSave);
    console.log('object array  norec_resep',idnorec_resep);
    

    try {
        if (e.header.statusbayar === 'Lunas') {
            await useApi().post('farmasi/save-retur-resep-dibayar-ranap', objSave);
        } else {
            await useApi().post('farmasi/save-retur-pelayanan-ranap', objSave);
        }
    } catch (error) {
        H.alert('error', 'Gagal menyimpan data');
    } finally {
        isSimpan.value = false;
        isDisabledSave.value = true;
         // Mengaktifkan kembali tombol setelah request selesai
    }
}
const openModalReturRanap = () => {
    modalReturPerawat.value = false
    isVerifOrderPerawat.value = false
    modalReturRanap.value = true
}

const countTotal = () => {
    let total = 0
    dataSource.value.forEach((element: any) => {
        total = total + parseFloat(element.total)
    })
    item.resultTotal = total
}

const back = () => {
    window.history.back();
}

watch(
    () => item.qtyretur,
    () => {
        if (item.qtyretur > item.jumlah) {
            H.alert('error', 'Jumlah retur melebihi batas')
            item.totalTagihan = item.total
            isDisabled.value = true
            return
        }
        if (item.qtyretur) {
            let hargaResult = parseFloat(item.qtyretur) * parseFloat(item.hargajual) - parseFloat(item.hargadiscount)
            let resultTotal = parseFloat(item.total) - hargaResult
            if (isNaN(resultTotal)) {
                item.totalTagihan = item.total
                isDisabled.value = true
            } else {
                item.totalTagihan = resultTotal
                isDisabled.value = false
            }
        } else {
            item.totalTagihan = item.total
            isDisabled.value = true
        }
    }
)



function toggleAll() {
    dataSource.value.forEach(row => {
        row.isSelected = allSelected.value;
        row.jmlretur = allSelected.value ? row.jumlah : 0;
    });
}
function onRowSelectChange(row) {
    row.jmlretur = row.isSelected ? row.jumlah : 0;
    allSelected.value = dataSource.value.every(row => row.isSelected); // Update "Select All" checkbox state
}
function onRowSelectChangeRetur(row) {
    console.log('row selected', row);
    
    if (row.isSelected) {
        if (row.isSelected == true) {
            dataSelected.value.push(row);
        }
    } else {
        const index = dataSelected.value.findIndex(item => item.produkfk === row.produkfk && item.no === row.no && item.norecpp == row.norecpp);
        if (index !== -1) {
            dataSelected.value.splice(index, 1);
        }
    }
    
    console.log('data selected',dataSelected.value)
}


// watch(dataSource, (newData) => {
//     newData.forEach(row => {
//         if (row.isSelected) {
//             row.jmlretur = row.jumlah;
//         }
//     });
// }, { deep: true });



loadData()

</script>
    
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout .form-outer {
    border: 1px solid transparent;
    background-color: transparent;
}
</style>
    
    