<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Rekanan</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton icon="lnir lnir-arrow-left rem-100"
                                        :to="{ name: 'module-sysadmin-master-rekanan' }" light dark-outlined>
                                        Batal
                                    </VButton>
                                    <VButton type="button" icon="feather:save" :loading="isLoading" color="primary"
                                        raised @click="saveRekanan()" v-if="!isRegistrasi"> Simpan
                                    </VButton>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h4>Informasi Rekanan</h4>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Nama Rekanan</VLabel>
                                        <VControl icon="feather:user">
                                            <VInput type="text" v-model="item.namarekanan" placeholder="Nama Rekanan"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Jenis Rekanan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.jenisrekanan" :options="d_jenis"
                                                placeholder="Pilih Jenis Rekanan" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Pegawai Kepala</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.objectpegawaifk"
                                                :options="d_Pegawai" placeholder="Pilih Pegawai" :searchable="true"
                                                :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h4>Alamat Rekanan</h4>
                            </div>

                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Alamat Lengkap</VLabel>
                                        <VControl>
                                            <VTextarea v-model="item.alamatlengkap" rows="4"
                                                placeholder="Alamat Lengkap">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>RT / RW</VLabel>
                                        <VControl icon="feather:home">
                                            <VInput type="text" v-model="item.rtrw" placeholder="Alamat Lengkap"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Provinsi</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.provinsi" :options="d_Provinsi"
                                                placeholder="Pilih Provinsi" :searchable="true" :attrs="{ id }"
                                                @select="changeProvinsi(item.provinsi)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.provinsi">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Kota Kabupaten</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kotaKabupaten"
                                                :options="d_KotaKabupaten" placeholder="Pilih Kota / Kabupaten"
                                                :searchable="true" :attrs="{ id }" :loading="isLoading"
                                                @select="changeKota(item.kotaKabupaten)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.kotaKabupaten">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Kecamatan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kecamatan" :options="d_Kecamatan"
                                                placeholder="Pilih Kecamatan" :searchable="true" :attrs="{ id }"
                                                :loading="isLoading" @select="changeKecamatan(item.kecamatan)" />
                                        </VControl>
                                        <!-- <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kecamatan"
                                                placeholder="Pilih data" :searchable="true" :filter-results="false"
                                                :min-chars="0" :resolve-on-load="false" :loading="isLoading" :delay="0"
                                                :options="async function (query: any) {
                                                    return await fetchKecamatan(query) // check JS block in JSFiddle for implementation
                                                }">

                                            </Multiselect>
                                        </VControl> -->
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.kecamatan">

                                    <!-- <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Kelurahan</VLabel>
                                        <VControl icon="feather:search">

                                            <AutoComplete v-model="item.desaKelurahan" :suggestions="d_Kelurahan"
                                                @complete="fetchDesa($event)" :dropdown="true"
                                                placeholder="Pilih data" field="namadesakelurahan" ppendTo="body">
                                                <template #item="slotProps">
                                                    <div class="flex align-items-center country-item">
                                                        <table class="country-item">
                                                            <tr>
                                                                <th>Kelurahan</th>
                                                                <th>Kecamatan</th>
                                                                <th>Kota Kabupaten</th>
                                                                <th>Provinsi</th>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ slotProps.item.namadesakelurahan }}</td>
                                                                <td>{{ slotProps.item.namakecamatan }}</td>
                                                                <td>{{ slotProps.item.namakotakabupaten }}</td>
                                                                <td>{{ slotProps.item.namapropinsi }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </template>
                                            </AutoComplete>
                                        </VControl>
                                    </VField> -->
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Kelurahan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.desaKelurahan"
                                                :options="d_Kelurahan" placeholder="Pilih Kelurahan" :searchable="true"
                                                :attrs="{ id }" :loading="isLoading"
                                                @select="changeDesa(item.desaKelurahan)" />
                                            <!-- <Multiselect mode="single" v-model="item.desaKelurahan"
                                                placeholder="Pilih data" :searchable="true" :filter-results="false"
                                                :min-chars="0" :resolve-on-load="false" :loading="isLoading" :delay="0"
                                                :options="async function (query: any) {
                                                    return await fetchDesa(query) // check JS block in JSFiddle for implementation
                                                }" @select="changeDesa(item.desaKelurahan)">

                                            </Multiselect> -->
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.kecamatan">
                                    <VField>
                                        <VLabel>Kode Pos </VLabel>
                                        <VControl icon="feather:airplay" :loading="isLoadingKodePos">
                                            <VInput type="number" v-model="item.kodePos" placeholder="Kode Pos"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="form-fieldset">
                                    <div class="fieldset-heading">
                                        <h4>Kontak</h4>
                                    </div>

                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel>Kontak Person</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="number" v-model="item.contactperson"
                                                        placeholder="Kontak Person" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel>Email </VLabel>
                                                <VControl icon="feather:mail">
                                                    <VInput type="email" v-model="item.email" placeholder="Email"
                                                        inputmode="email" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Website</VLabel>
                                                <VControl icon="feather:globe">
                                                    <VInput type="text" v-model="item.website"
                                                        placeholder="Alamat Website" inputmode="website"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Telepon</VLabel>
                                                <VControl icon="feather:phone">
                                                    <VInput type="number" v-model="item.telepon"
                                                        placeholder="Nomor Telepon" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-fieldset">
                                    <div class="fieldset-heading">
                                        <h4>Lain - Lain</h4>
                                    </div>

                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Bank Rekening Atasnama</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.bankrekeningatasnama"
                                                        placeholder="Bank Rekening Nama" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Nama Bank </VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.bankrekeningnama"
                                                        placeholder="Bank Rekening Nama" inputmode="text"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Bank Rekening Nomor</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="number" v-model="item.bankrekeningnomor"
                                                        placeholder="Nomor Rekening Bank" inputmode="text"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>No PKP</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.nopkp" placeholder="Nomor PKP"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>NPWP </VLabel>
                                        <VControl icon="pi pi-reddit">
                                            <VInput type="number" v-model="item.npwp" placeholder="Nomor NPWP"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Rekanan Mou PKS </VLabel>
                                        <VControl icon="pi pi-reddit">
                                            <VInput type="text" v-model="item.rekananmoupksfk"
                                                placeholder="Rekanan Mou PKS" class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <!--Fieldset-->


                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import { useToaster } from '/@src/composable/toaster'

useHead({
    title: 'Rekanan Baru- ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_REKANAN = useRoute().query.id as string
let ID_REKANAN_SET = ref()
const date = ref(new Date())
const item: any = ref([])
const dataSource: any = ref([])
let d_jenis: any = ref([])
let d_Pegawai: any = ref([])
let d_Negara: any = ref([])
let d_Kelurahan: any = ref([])
let d_Kecamatan: any = ref([])
let d_KotaKabupaten: any = ref([])
let d_Provinsi: any = ref([])
let d_Kelompok: any = ref([])
let isLoading = ref(false)
let isLoadingKodePos = ref(false)
let isRegistrasi = ref(false)
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})

async function listDropdown() {

    const response = await useApi().get(
        `/sysadmin/master-rekanan-dropdown`)
    d_jenis.value = response.jenisrekanan.map((e: any) => { return { label: e.jenisrekanan, value: e.id } })
    d_Pegawai.value = response.pegawai.map((e: any) => { return { label: e.namalengkap, value: e.id } })
    d_Provinsi.value = response.provinsi.map((e: any) => { return { label: e.namapropinsi, value: e } })
    d_Kelompok.value = response.kelompokpasien.map((e: any) => { return { label: e.kelompokpasien, value: e.id } })


    if (ID_REKANAN) {
        ID_REKANAN_SET.value = ID_REKANAN
        rekananByID(ID_REKANAN)
    }
}
async function rekananByID(id: any) {
    const { data: rekanan } = await useApi().get(`/sysadmin/master-rekanan?id=${id}`)
    item.value.namarekanan = rekanan[0].namarekanan
    item.value.objectjenisrekananfk = rekanan[0].objectjenisrekananfk
    item.value.objectpegawaifk = rekanan[0].objectpegawaifk
    item.value.alamatlengkap = rekanan[0].alamatlengkap
    item.value.rtrw = rekanan[0].rtrw
    item.value.contactperson = rekanan[0].contactperson
    item.value.email = rekanan[0].email
    item.value.website = rekanan[0].website
    item.value.telepon = rekanan[0].telepon
    item.value.bankrekeningatasnama = rekanan[0].bankrekeningatasnama
    item.value.bankrekeningnama = rekanan[0].bankrekeningnama
    item.value.nopkp = rekanan[0].nopkp
    item.value.npwp = rekanan[0].npwp
    item.value.rekananmoupksfk = rekanan[0].rekananmoupksfk
}
async function saveRekanan() {
    if (!item.value.namarekanan) {
        useToaster().error('Nama Rekanan harus di isi')
        return
    }
    if (!item.value.alamatlengkap) {
        useToaster().error('Alamat Lengkap harus di isi')
        return
    }

    let json = {
        'rekanan': {
            'id': item.value.id ? item.value.id : '',
            'namarekanan': item.value.namarekanan,
            'objectjenisrekananfk': item.value.jenisrekanan,
            'objectpegawaifk': item.value.objectpegawaifk,
            'alamatlengkap': item.value.alamatlengkap,
            'rtrw': item.value.rtrw,
            'kotakabupaten': item.value.kotaKabupaten,
            'namakecamatan': item.value.kecamatan,
            'namadesakelurahan': item.value.desaKelurahan,
            'kodepos': item.value.kodePos,
            'contactperson': item.value.contactperson,
            'email': item.value.email,
            'website': item.value.website,
            'telepon': item.value.telepon,
            'bankrekeningatasnama': item.value.bankrekeningatasnama,
            'bankrekeningnama': item.value.bankrekeningnama,
            'bankrekeningnomor': item.value.bankrekeningnomor,
            'nopkp': item.value.nopkp,
            'npwp': item.value.npwp,
            'rekananmoupksfk': item.value.rekananmoupksfk,
        }
    }
    isLoading.value = true
    isRegistrasi.value = false
    await useApi().post(
        `/sysadmin/save-master-rekanan`, json).then((response: any) => {
            isLoading.value = false
            ID_REKANAN = response.data.id
            ID_REKANAN_SET.value = response.data.id
            isRegistrasi.value = true

        }).catch((e: any) => {
            isLoading.value = false
            console.clear()
            console.log(e)
        })
}
async function changeProvinsi(event: any) {
    d_KotaKabupaten.value = []
    let query = event == '' ? '' : event.id;
    isLoading.value = true

    const response = await useApi().get(
        `/registrasi/kotakabupaten?provfk=${query}`)
    isLoading.value = false

    d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { label: e.namakotakabupaten, value: e } })

}
async function changeKota(event: any) {
    d_Kecamatan.value = []
    let query = event == '' ? '' : event.id;
    isLoading.value = true

    const response = await useApi().get(
        `/registrasi/kecamatan?kotafk=${query}`)
    isLoading.value = false

    d_Kecamatan.value = response.kecamatan.map((e: any) => { return { label: e.namakecamatan, value: e } })

}
async function changeKecamatan(event: any) {
    d_Kelurahan.value = []
    let query = event == '' ? '' : event.id;
    isLoading.value = true

    const response = await useApi().get(
        `/registrasi/desakelurahan?kecfk=${query}`)
    isLoading.value = false

    d_Kelurahan.value = response.desa.map((e: any) => { return { label: e.namadesakelurahan, value: e } })

}
function changeDesa(event: any) {
    // if (event.objectkecamatanfk)
    //     item.kecamatan = { id: event.objectkecamatanfk, namakecamatan: event.namakecamatan }
    // if (event.objectkotakabupatenfk)
    //     item.kotaKabupaten = { id: event.objectkotakabupatenfk, namakotakabupaten: event.namakotakabupaten }
    // if (event.objectpropinsifk)
    //     item.propinsi = { id: event.objectpropinsifk, namapropinsi: event.namapropinsi }
    // if (event.kodepos)
    item.kodePos = event.kodepos
}

function resetForm() {

}

listDropdown()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: 800px;
    margin: 0 auto;
}

.form-fieldset {
    padding: 20px 0;
    max-width: 580px;
    margin: 0 auto;
}
</style>
