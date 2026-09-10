<template>
    <VCard>
        <div class="page-content-inner">
            <div class="is-navbar">
                <div class="form-layout">
                    <div class="form-outer">
                        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                            <div class="form-header-inner">
                                <ConfirmDialog/>
                                <div class="left">
                                    <h3>Setting Anggaran</h3>
                                </div>
                                <div class="right">
                                    <div class="buttons">
                                        <VButton type="button" icon="feather:save" :loading="isLoading" color="primary" raised
                                            @click="save()" v-if="!isSettingRBA"> Save
                                        </VButton>
                                        
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="form-body">
                            <div class="form-fieldset">
                                <div class="fieldset-heading">
                                    <!-- <h4>Informasi Pasien</h4> -->

                                </div>
                                <div class="columns is-multiline">
                                    <div class="column is-6">
                                        <VField>
                                            <VLabel class="required-field">Nama Pemda</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.namapemda" placeholder="Nama Pemda"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <VField>
                                            <VLabel class="required-field">Kepala Daerah</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.kepaladaerah" placeholder="Kepala Daerah"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>

                                    <div class="column is-6">
                                        <VField>
                                            <VLabel class="required-field">Nama Kepala Daerah</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.namakepaladaerah" placeholder="Nama Kepala Daerah"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <VField>
                                            <VLabel class="required-field">Nama Sekda</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.namasekda" placeholder="Nama Sekda"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <VField>
                                            <VLabel class="required-field">NIP Sekda</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.nipsekda" placeholder="NIP Sekda"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <VField>
                                            <VLabel class="required-field">Nama PPKD</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.namappkd" placeholder="Nama PPKD"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <VField>
                                            <VLabel class="required-field">NIP PPKD</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.nipppkd" placeholder="Nama PPKD"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <hr>
                                    </div>
                                    <div class="column is-6">
                                        <VField>
                                            <VLabel class="required-field">Pengelola Keuangan BLUD</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.pengelolakeuanganblud" placeholder="Pengelola Keuangan BLUD"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6"></div>
                                    <div class="column is-6">
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VLabel class="required-field">Nama Kepala BLUD</VLabel>
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="item.objectkepalabludfk"
                                                    :suggestions="d_Pegawai" @complete="fetchDokter($event)"
                                                    :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik nama petugas" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <VField>
                                            <VLabel class="required-field">NIP Kepala BLUD</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.nipkepalablud" placeholder="NIP Kepala BLUD"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel class="required-field">Alamat</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.alamat" placeholder="Alamat"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel class="required-field">Kota</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.kota" placeholder="Kota"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel class="required-field">Telepon</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.telepon" placeholder="Telepon"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel class="required-field">Faximile</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.faximile" placeholder="Faximile"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel class="required-field">Tahun Anggaran</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.tahunanggaran" placeholder="Tahun Anggaran"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField class="is-autocomplete-select">
                                            <VLabel class="required-field">Tahap Aktif</VLabel>
                                            <VControl icon="feather:search" class="prime-auto-select">
                                                <Dropdown v-model="item.objecttahapaktivfk" :options="d_Tahap"
                                                    :optionLabel="'tahap'" class="is-stacked" placeholder="Pilih data"
                                                    style="width: 100%;" showClear :filter="false" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel class="required-field">Nomor NPWP</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.nomornpwp" placeholder="Nomor NPWP"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel class="required-field">Organisasi</VLabel>
                                            <VControl icon="feather:user">
                                                <VInput type="text" v-model="item.organisasi" placeholder="Organisasi"
                                                    class="is-rounded_Z" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </VCard>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useUserSession } from '/@src/stores/userSession'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';

import { useToast } from 'primevue/usetoast';

import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';

const toast = useToast();

useHead({
    title: 'Setting Anggaran',
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const confirm = useConfirm();
const item: any = reactive({})
let d_Pegawai: any = ref([])
let d_Tahap: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})

loadCombo()
loadData()

async function loadCombo(){
    await useApi().get(
        `anggaran/get-combo`
    ).then((response) => {
        d_Tahap.value = response.tahap
    })
}
async function loadData() {
    await useApi().get('anggaran/get-data-setting-anggaran').then((response) => {
        item.namapemda = response.data[0].namapemda;
        item.kepaladaerah = response.data[0].kepaladaerah
        item.namakepaladaerah = response.data[0].namakepaladaerah;
        item.namasekda = response.data[0].namasekda;
        item.nipsekda = response.data[0].nipsekda;
        item.namappkd = response.data[0].namappkd;
        item.nipppkd = response.data[0].nipppkd;
        item.pengelolakeuanganblud = response.data[0].pengelolakeuanganblud;
        item.nipkepalablud = response.data[0].nipkepalablud;
        item.alamat = response.data[0].alamat;
        item.kota = response.data[0].kota;
        item.telepon = response.data[0].telepon;
        item.faximile = response.data[0].faximile;
        item.tahunanggaran = response.data[0].tahunanggaran;
        item.organisasi = response.data[0].organisasi;
        item.objectkepalabludfk = {
            value: response.data[0].objectkepalabludfk,
            label: response.data[0].label
        };
        d_Tahap.value.forEach((element: any) => {
            if(element.id == response.data[0].objecttahapaktivfk){
                item.objecttahapaktivfk = element
            }
        });
        // item.objecttahapaktivfk = item.objecttahapaktivfk.id;
        // item.objecttahapaktivfk = {
        //     tahap: response.data[0].tahap,
        //     id: response.data[0].objecttahapaktivfk,
        // }
        console.log(item.objecttahapaktivfk);
        
        item.nomornpwp = response.data[0].nomornpwp;
    })
}






const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

let isLoading = ref(false)
let isSettingRBA = ref(false)
async function save() {
    isLoading.value = true
    var listRawRequired = [
        'item.namapemda|Nama Pemda',
        'item.namakepaladaerah|Nama Kepala Daerah',
        'item.kepaladaerah|Kepala Daerah',
        'item.namasekda|Nama Sekda',
        'item.nipsekda|NIP Sekda',
        'item.namappkd|Nama PPKD',
        'item.nipppkd|NIP PPKD',
        'item.pengelolakeuanganblud|Pengelola Keuangan BLUD',
        'item.nipkepalablud|NIP Kepala BLUD',
        'item.alamat|Alamat',
        'item.kota|Kota',
        'item.telepon|Telepon',
        'item.faximile|Faximile',
        'item.tahunanggaran|Tahun Anggaran',
        'item.objectkepalabludfk|Nama Kepala BLUD',
        'item.objecttahapaktivfk|Tahap Aktif',
        'item.nomornpwp|Nomor NPWP',
        'item.organisasi|Organisasi',
    ]
    for (let i = 0; i < listRawRequired.length; i++) {
        const element = listRawRequired[i];
        if(eval(element.split('|')[0]) == undefined){
            H.alert('error',element.split('|')[1] +' Wajib Diisi!')
            isLoading.value = false
            return
        }
    }

    await useApi().get('perencanaan/get-penjagaan-setting-anggaran?tahunanggaran='+ item.tahunanggaran).then((response)=> {
        if(response.data.length > 0){
            dialogConfirm()
        } else{
            saveData()
        }
    });
                
}
const dialogConfirm = (e: any) => {
    isLoading.value = true
    confirm.require({
    message: 'Pada Tahun Tersebut Sudah Ada Setting Anggaran Ingin Lanjut Simpan ?',
    header: 'Konfirmasi Simpan Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
        saveData()
        isLoading.value = false
    },
    reject: () => {
        loadData()
        isLoading.value = false
     },
  })
}
async function saveData() {
    isLoading.value = true
    var objSave = {
        namapemda: item.namapemda,
        kepaladaerah: item.kepaladaerah,
        namakepaladaerah: item.namakepaladaerah,
        namasekda: item.namasekda,
        nipsekda: item.nipsekda,
        namappkd: item.namappkd,
        nipppkd: item.nipppkd,
        pengelolakeuanganblud: item.pengelolakeuanganblud,
        nipkepalablud: item.nipkepalablud,
        alamat: item.alamat,
        kota: item.kota,
        telepon: item.telepon,
        faximile: item.faximile,
        tahunanggaran: item.tahunanggaran,
        objectkepalabludfk: item.objectkepalabludfk.value,
        objecttahapaktivfk: item.objecttahapaktivfk.id,
        namatahap: item.objecttahapaktivfk.tahap,
        nomornpwp: item.nomornpwp,
        organisasi: item.organisasi,
    }
    await useApi().post('perencanaan/save-setting-anggaran', objSave).then((response) => {
        isLoading.value = false
        loadData()
    })
}
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: '100%';
    margin: 0 auto;
}

.form-fieldset {
    padding: 20px 0;
    max-width: 580px;
    margin: 0 auto;
}
</style>
