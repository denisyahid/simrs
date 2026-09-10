
<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Pasien</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton icon="lnir lnir-arrow-left rem-100"
                                        :to="{ name: 'module-registrasi-pasien-lama' }" light dark-outlined>
                                        Cancel
                                    </VButton>
                                    <VButton type="button" icon="feather:save" :loading="isLoading" color="primary" raised
                                        @click="savePasien()" v-if="!isRegistrasi"> Save
                                    </VButton>
                                    <VButton type="button" icon="feather:arrow-right-circle" :loading="isLoading"
                                        color="info" raised @click="registrasiPasien()" v-if="isRegistrasi"> Registrasi
                                    </VButton>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h4>Informasi Pasien</h4>

                            </div>

                            <div class="columns is-multiline">
                                <div class="column is-4">

                                    <VField id="nik" v-slot="{ field }">
                                        <VLabel>NIK</VLabel>
                                        <VControl icon="feather:book" :loading="isLoadingNIK">
                                            <VInput type="number" v-model="item.nik" placeholder="Enter untuk pencarian NIK"
                                                class="is-rounded_Z" v-on:keyup.enter="cariBPJS('nik')" />
                                            <p v-if="field?.errorMessage" class="help is-danger">
                                                {{ field.errorMessage }}
                                            </p>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField id="nobpjs" v-slot="{ field }" label="No BPJS">
                                        <VControl icon="feather:book" :loading="isLoadingBPJS">
                                            <VInput type="text" v-model="item.nobpjs"
                                                placeholder="Enter untuk pencarian No BPJS" class="is-rounded_Z"
                                                v-on:keyup.enter="cariBPJS('nobpjs')" />
                                            <p v-if="field?.errorMessage" class="help is-danger">
                                                {{ field.errorMessage }}
                                            </p>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VLabel class="required-field">NOCM Ibu</VLabel>
                                        <VControl icon="feather:user">
                                            <VInput type="text" v-model="item.nocmfkibu" placeholder="Nama Pasien"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-12">
                                    <VField>
                                        <VLabel class="required-field">Nama Pasien</VLabel>
                                        <VControl icon="feather:user">
                                            <VInput type="text" v-model="item.namapasien" placeholder="Nama Pasien"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel class="required-field">Tempat Lahir</VLabel>
                                        <VControl icon="feather:map-pin">
                                            <VInput type="text" v-model="item.tempatlahir" placeholder="Tempat Lahir"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel class="required-field">Tanggal Lahir</VLabel>
                                        <VDatePicker v-model="item.tgllahir" mode="dateTime" style="width: 100%" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel class="required-field">Jenis Kelamin</VLabel>
                                        <VControl>
                                            <div class="columns">
                                                <div class="column is-12" v-if="d_JK.length == 0">
                                                    <VPlaceloadText :lines="1" />
                                                </div>
                                                <div class="column is-6" v-for="items in d_JK" :key="items.id">
                                                    <VRadio v-model="item.jenisKelamin" :value="items.id"
                                                        :label="items.jeniskelamin" name="{{items.id}}" square
                                                        color="primary" />
                                                </div>
                                            </div>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>No HP/Ponsel</VLabel>
                                        <VControl icon="feather:phone">
                                            <VInput type="text" v-model="item.nohp" placeholder="No HP"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Agama</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.agama" :options="d_Agama"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h4>Informasi Alamat </h4>
                            </div>

                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField>
                                        <VLabel class="required-field">Alamat Lengkap</VLabel>
                                        <VControl>
                                            <VTextarea v-model="item.alamat" rows="4" placeholder="Alamat Lengkap">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" style="display: none !important">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel class="required-field">Provinsi</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.provinsi" :options="d_Provinsi"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                @select="changeProvinsi(item.provinsi)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.provinsi" style="display: none !important">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel class="required-field">Kota Kabupaten</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kotaKabupaten"
                                                :options="d_KotaKabupaten" placeholder="Pilih data" :searchable="true"
                                                :attrs="{ id }" :loading="isLoading"
                                                @select="changeKota(item.kotaKabupaten)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6" v-if="item.kotaKabupaten" style="display: none !important">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel class="required-field">Kecamatan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kecamatan" :options="d_Kecamatan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
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
                                <div class="column is-6" v-if="item.kecamatan" style="display: none !important">

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
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }" style="display: none !important">
                                        <VLabel>Kelurahan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.desaKelurahan" :options="d_Kelurahan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                :loading="isLoading" @select="changeDesa(item.desaKelurahan)" />
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



                                <div class="column is-6" v-if="item.kecamatan" style="display: none !important">
                                    <VField>
                                        <VLabel>Kode Pos </VLabel>
                                        <VControl icon="feather:airplay" :loading="isLoadingKodePos">
                                            <VInput type="text" v-model="item.kodePos" placeholder="Kode Pos"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Email </VLabel>
                                        <VControl icon="feather:mail">
                                            <VInput type="email" v-model="item.email" placeholder="Email" inputmode="email"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Nama Ibu </VLabel>
                                        <VControl icon="pi pi-reddit">
                                            <VInput type="text" v-model="item.namaIbu" placeholder="Nama Ibu"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="isInfoTambahan" label="Informasi Tambahan"
                                                color="danger" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <!--Fieldset-->
                        <div class="form-fieldset" v-if="isInfoTambahan">
                            <div class="fieldset-heading">
                                <h4>Informasi Tambahan </h4>
                            </div>

                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Status Perkawinan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.statusPerkawinan"
                                                :options="d_StatusPerkawinan" placeholder="Pilih data" :searchable="true"
                                                :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Golongan Darah</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.golDar" :options="d_GolonganDarah"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Pendidikan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.pendidikan" :options="d_Pendidikan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Pekerjaan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.pekerjaan" :options="d_Pekerjaan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Etnis</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.suku" :options="d_Etnis"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Kebangsaan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.kebangsaan" :options="d_Kebangsaan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Negara</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.negara" :options="d_Negara"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>No Asuransi Lain </VLabel>
                                        <VControl icon="feather:archive">
                                            <VInput type="text" v-model="item.noAsuransiLain" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>No Telepon Rumah </VLabel>
                                        <VControl icon="feather:phone">
                                            <VInput type="text" v-model="item.noTelepon" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Nama Ayah </VLabel>
                                        <VControl icon="feather:code">
                                            <VInput type="text" v-model="item.namaAyah" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Nama Keluarga </VLabel>
                                        <VControl icon="feather:code">
                                            <VInput type="text" v-model="item.namaKeluarga" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Nama Suami/Istri</VLabel>
                                        <VControl icon="feather:code">
                                            <VInput type="text" v-model="item.namaSuamiIstri" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VSwitchBlock v-model="isPenanggungJawab" label="Isi Penanggung Jawab Pasien"
                                                color="warning" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="form-fieldset" v-if="isPenanggungJawab && isInfoTambahan">
                            <div class="fieldset-heading">
                                <h4>Penanggung Jawab
                                    Pasien</h4>
                                <p>Data penanggung jawab atau keluarga pasien</p>
                            </div>

                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Nama Penanggung Jawab</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.penanggungJawabP" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Hubungan Dengan Pasien</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.hubunganP" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>No Telepon</VLabel>
                                        <VControl icon="feather:phone">
                                            <VInput type="text" v-model="item.telponP" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Bahasa Sehari-hari</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.bahasaP" placeholder=""
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Jenis Kelamin</VLabel>
                                        <VControl>
                                            <div class="columns">
                                                <div class="column is-12" v-if="d_JK.length == 0">
                                                    <VPlaceloadText :lines="1" />
                                                </div>
                                                <div class="column is-6" v-for="items in d_JK" :key="items.id">
                                                    <VRadio v-model="item.jenisKelP" :value="items.id"
                                                        :label="items.jeniskelamin" name="{{items.id}}" square
                                                        color="primary" />
                                                </div>
                                            </div>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Umur</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.umurP" placeholder="" class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Pekerjaan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.pekerjaanP" :options="d_Pekerjaan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Alamat Penanggung Jawab</VLabel>
                                        <VControl>
                                            <VTextarea v-model="item.alamatP" rows="4" placeholder="Alamat Lengkap">
                                            </VTextarea>
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

useHead({
    title: 'Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PASIEN = useRoute().query.id as string
let ID_PASIEN_SET = ref()
const date = ref(new Date())
const item: any = reactive({
    tgllahir: new Date()
})
let d_JK: any = ref([])
let d_Agama: any = ref([])
let d_GolonganDarah: any = ref([])
let d_StatusPerkawinan: any = ref([])
let d_Pendidikan: any = ref([])
let d_Pekerjaan: any = ref([])
let d_Etnis: any = ref([])
let d_Kebangsaan: any = ref([])
let d_Negara: any = ref([])
let d_Kelurahan: any = ref([])
let d_Kecamatan: any = ref([])
let d_KotaKabupaten: any = ref([])
let d_Provinsi: any = ref([])
let isLoading = ref(false)
let isInfoTambahan = ref(false)
let isLoadingKodePos = ref(false)
let isLoadingNIK = ref(false)
let isLoadingBPJS = ref(false)
let isPenanggungJawab = ref(false)
let isRegistrasi = ref(false)
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})

async function listDropdown() {

    const response = await useApi().get(
        `/registrasi/list-dropdown`)
    d_JK.value = []
    for (let x = 0; x < response.jk.length; x++) {
        const element = response.jk[x];
        if (element.jeniskelamin != '-') {
            d_JK.value.push(element)
        }
    }
    d_Agama.value = response.agama.map((e: any) => { return { label: e.agama, value: e } })
    d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e } })
    d_StatusPerkawinan.value = response.statusperkawinan.map((e: any) => { return { label: e.statusperkawinan, value: e } })
    d_Pendidikan.value = response.pendidikan.map((e: any) => { return { label: e.pendidikan, value: e } })
    d_Pekerjaan.value = response.pekerjaan.map((e: any) => { return { label: e.pekerjaan, value: e } })
    d_Etnis.value = response.etnis.map((e: any) => { return { label: e.suku, value: e } })
    d_Kebangsaan.value = response.kebangsaan.map((e: any) => { return { label: e.name, value: e } })
    d_Negara.value = response.negara.map((e: any) => { return { label: e.namanegara, value: e } })
    // d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { label: e.namakotakabupaten, value: e } })
    d_Provinsi.value = response.provinsi.map((e: any) => { return { label: e.namapropinsi, value: e } })

    for (let x = 0; x < response.negara.length; x++) {
        const element = response.negara[x];
        if (element.namanegara.toLowerCase() == 'indonesia') {
            item.negara = element
            break
        }
    }
    for (let x = 0; x < response.kebangsaan.length; x++) {
        const element = response.kebangsaan[x];
        if (element.name.toLowerCase() == 'wni') {
            item.kebangsaan = element
            break
        }
    }
    if (ID_PASIEN) {
        ID_PASIEN_SET.value = ID_PASIEN
        pasienByID(ID_PASIEN)
    }


}
async function pasienByID(id: any) {
    const response = await useApi().get(
        `/registrasi/pasien?id=${id}`)
    if (response.pasien) {
        let r = response.pasien
        // item.nik = r.noidentitas
        item.nobpjs = r.nobpjs
        item.namapasien = r.namapasien + " By Ny"
        item.tempatlahir = r.tempatlahir
        item.jenisKelamin = r.objectjeniskelaminfk
        item.nohp = r.nohp
        item.agama = { 'id': r.objectagamafk, 'agama': r.agama }
        item.email = r.email
        item.namaIbu = r.namapasien
        item.nocmfkibu = r.nocm
        item.statusPerkawinan = r.objectstatusperkawinanfk ? { 'id': r.objectstatusperkawinanfk, 'statusperkawinan': r.statusperkawinan } : undefined
        item.golDar = r.objectgolongandarahfk ? { 'id': r.objectgolongandarahfk, 'golongandarah': r.golongandarah } : undefined
        item.pendidikan = r.objectpendidikanfk ? { 'id': r.objectpendidikanfk, 'pendidikan': r.pendidikan } : undefined
        item.pekerjaan = r.objectpekerjaanfk ? { 'id': r.objectpekerjaanfk, 'pekerjaan': r.pekerjaan } : undefined
        item.suku = r.objectsukufk ? { 'id': r.objectsukufk, 'suku': r.suku } : undefined
        item.noAsuransiLain = r.noaditional
        item.noTelepon = r.notelepon
        item.namaAyah = r.namaayah
        item.namaKeluarga = r.namakeluarga
        item.namaSuamiIstri = r.namasuamiistri
        item.penanggungJawabP = r.penanggungjawab
        item.hubunganP = r.hubungankeluargapj
        item.telponP = r.telponpenanggungjawab
        item.bahasaP = r.bahasa
        item.jenisKelP = r.jeniskelaminpenanggungjawab
        item.umurP = r.umurpenanggungjawab
        item.bahasaP = r.pekerjaanpenangggungjawab
        item.alamatP = r.alamatrmh
        item.kebangsaan = r.objectkebangsaanfk ? { 'id': r.objectkebangsaanfk, 'name': r.name } : undefined
        item.negara = r.objectnegarafk ? { 'id': r.objectnegarafk, 'namanegara': r.namanegara } : undefined
        item.alamat = r.alamatlengkap
        if (r.objectpropinsifk) {
            await changeProvinsi({ id: r.objectpropinsifk })
            item.provinsi = { 'id': r.objectpropinsifk, 'namapropinsi': r.namapropinsi }
        }
        if (r.objectkotakabupatenfk) {
            await changeKota({ id: r.objectkotakabupatenfk })
            item.kotaKabupaten = { 'id': r.objectkotakabupatenfk, 'namakotakabupaten': r.namakotakabupaten }
        }
        if (r.objectkecamatanfk) {
            await changeKecamatan({ id: r.objectkecamatanfk })
            item.kecamatan = { 'id': r.objectkecamatanfk, 'namakecamatan': r.namakecamatan }
        }
        if (r.objectdesakelurahanfk) {
            item.desaKelurahan = { 'id': r.objectdesakelurahanfk, 'namadesakelurahan': r.namadesakelurahan }
        }
        item.kodePos = r.kodepos

    }

}

async function savePasien() {
    // if (!item.nik) { H.alert('warning', 'NIK harus di isi'); return }
    if (!item.nocmfkibu) { H.alert('warning', 'NoCm ibu harus di isi'); return }
    if (!item.namapasien) { H.alert('warning', 'Nama harus di isi'); return }
    if (!item.tempatlahir) { H.alert('warning', 'Tempat Lahir harus di isi'); return }
    if (!item.tgllahir) { H.alert('warning', 'Tgl Lahir harus di isi'); return }
    if (!item.jenisKelamin) { H.alert('warning', 'Jenis Kelamin harus di isi'); return }
    if (!item.nohp) { H.alert('warning', 'No HP harus di isi'); return }
    if (!item.alamat) { H.alert('warning', 'Alamat Lenglap harus di isi'); return }

    item.progress = cekProggress()
    let json = {
        'pasien': {
            'id': '',
            'isPenunjang': item.isPenunjang ? item.isPenunjang : false,
            'nocmfkibu': item.nocmfkibu,
            'noidentitas': item.nik ? item.nik : null,
            'nobpjs': item.nobpjs ? item.nobpjs : null,
            'namapasien': item.namapasien,
            'tempatlahir': item.tempatlahir,
            'tgllahir': H.formatDate(item.tgllahir, 'YYYY-MM-DD'),
            'objectjeniskelaminfk': item.jenisKelamin,
            'nohp': item.nohp,
            'objectagamafk': item.agama ? item.agama.id : null,
            'email': item.email ? item.email : null,
            'namaibu': item.namaIbu ? item.namaIbu : null,
            'objectstatusperkawinanfk': item.statusPerkawinan ? item.statusPerkawinan.id : null,
            'objectgolongandarahfk': item.golDar ? item.golDar.id : null,
            'objectpendidikanfk': item.pendidikan ? item.pendidikan.id : null,
            'objectpekerjaanfk': item.pekerjaan ? item.pekerjaan.id : null,
            'objectsukufk': item.suku ? item.suku.id : null,
            'noaditional': item.noAsuransiLain ? item.noAsuransiLain : null,
            'notelepon': item.noTelepon ? item.noTelepon : null,
            'namaayah': item.namaAyah ? item.namaAyah : null,
            'namakeluarga': item.namaKeluarga ? item.namaKeluarga : null,
            'namasuamiistri': item.namaSuamiIstri ? item.namaSuamiIstri : null,
            'penanggungjawab': item.penanggungJawabP ? item.penanggungJawabP : null,
            'hubungankeluargapj': item.hubunganP ? item.hubunganP : null,
            'telponpenanggungjawab': item.telponP ? item.telponP : null,
            'bahasa': item.bahasaP ? item.bahasaP : null,
            'jeniskelaminpenanggungjawab': item.jenisKelP ? item.jenisKelP.jeniskelamin : null,
            'umurpenanggungjawab': item.umurP ? item.umurP : null,
            'pekerjaanpenangggungjawab': item.pekerjaanP ? item.pekerjaanP.id : null,
            'alamatrmh': item.alamatP ? item.alamatP : null,
            'objectkebangsaanfk': item.kebangsaan ? item.kebangsaan.id : null,
            'objectnegarafk': item.negara ? item.negara.id : null,
            'progress': item.progress ? item.progress : 0,
        },
        'alamat': {
            'alamatlengkap': item.alamat,
            'objectpropinsifk': item.provinsi ? item.provinsi.id : null,
            'objectkotakabupatenfk': item.kotaKabupaten ? item.kotaKabupaten.id : null,
            'objectkecamatanfk': item.kecamatan ? item.kecamatan.id : null,
            'objectdesakelurahanfk': item.desaKelurahan ? item.desaKelurahan.id : null,
            'kodepos': item.kodePos ? item.kodePos : null,
        }
    }
    isLoading.value = true
    isRegistrasi.value = false
    await useApi().post(
        `/registrasi/save-pasien-bayi`, json).then((response: any) => {
            isLoading.value = false
            ID_PASIEN = response.data.id
            ID_PASIEN_SET.value = response.data.id
            isRegistrasi.value = true
            if (!ID_PASIEN) {
                registrasiPasien()
            }

        }).catch((e: any) => {
            isLoading.value = false
            console.clear()
            console.log(e)
        })
}
async function cariBPJS(params: any) {
    if (params == 'nik') {
        isLoadingNIK.value = true

        let json = {
            "url": `Peserta/nik/${item.nik}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
            "method": "GET",
            "data": null
        }
        await useApi().post(
            `/bridging/bpjs/tools`, json).then((e: any) => {
                isLoadingNIK.value = false
                if (e.metaData.code == '200') {
                    var data = e.response
                    item.namapasien = data.peserta.nama
                    item.nobpjs = data.peserta.noKartu
                    item.tgllahir = new Date(data.peserta.tglLahir)
                    if (data.peserta.sex.toUpperCase() === "L") {
                        item.jenisKelamin = 1//{ id: 1, jeniskelamin: "LAKI-LAKI" }
                    }
                    if (data.peserta.sex.toUpperCase() === "P") {
                        item.jenisKelamin = 2//{ id: 2, jeniskelamin: "PEREMPUAN" }
                    }
                } else {
                    H.alert('info', e.metaData.message)
                }
            })

    }
    if (params == 'nobpjs') {
        isLoadingBPJS.value = true

        let json = {
            "url": `Peserta/nokartu/${item.nobpjs}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
            "method": "GET",
            "data": null
        }
        await useApi().post(
            `/bridging/bpjs/tools`, json).then((e: any) => {
                isLoadingBPJS.value = false
                if (e.metaData.code == '200') {
                    var data = e.response
                    item.namapasien = data.peserta.nama
                    item.nobpjs = data.peserta.noKartu
                    item.tgllahir = new Date(data.peserta.tglLahir)
                    if (data.peserta.sex.toUpperCase() === "L") {
                        item.jenisKelamin = 1//{ id: 1, jeniskelamin: "LAKI-LAKI" }
                    }
                    if (data.peserta.sex.toUpperCase() === "P") {
                        item.jenisKelamin = 2//{ id: 2, jeniskelamin: "PEREMPUAN" }
                    }
                } else {
                    H.alert('info', e.metaData.message)
                }
            })

    }
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
function cekProggress() {
    var countALL = 0
    var data = 0
    countALL = countALL + 1
    if (item.nik) { data = data + 1 }
    countALL = countALL + 1
    if (item.nobpjs) { data = data + 1 }
    countALL = countALL + 1
    if (item.namapasien) { data = data + 1 }
    countALL = countALL + 1
    if (item.tempatlahir) { data = data + 1 }
    countALL = countALL + 1
    if (item.tgllahir) { data = data + 1 }
    countALL = countALL + 1
    if (item.jenisKelamin) { data = data + 1 }
    countALL = countALL + 1
    if (item.nohp) { data = data + 1 }
    countALL = countALL + 1
    if (item.agama) { data = data + 1 }
    countALL = countALL + 1
    if (item.email) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaIbu) { data = data + 1 }
    countALL = countALL + 1
    if (item.statusPerkawinan) { data = data + 1 }
    countALL = countALL + 1
    if (item.golDar) { data = data + 1 }
    countALL = countALL + 1
    if (item.pendidikan) { data = data + 1 }
    countALL = countALL + 1
    if (item.pekerjaan) { data = data + 1 }
    countALL = countALL + 1
    if (item.suku) { data = data + 1 }
    countALL = countALL + 1
    if (item.noAsuransiLain) { data = data + 1 }
    countALL = countALL + 1
    if (item.noTelepon) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaAyah) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaKeluarga) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaSuamiIstri) { data = data + 1 }
    countALL = countALL + 1
    if (item.kebangsaan) { data = data + 1 }
    countALL = countALL + 1
    if (item.negara) { data = data + 1 }
    countALL = countALL + 1
    if (item.alamat) { data = data + 1 }
    countALL = countALL + 1
    if (item.provinsi) { data = data + 1 }
    countALL = countALL + 1
    if (item.kotaKabupaten) { data = data + 1 }
    countALL = countALL + 1
    if (item.kecamatan) { data = data + 1 }
    countALL = countALL + 1
    if (item.desaKelurahan) { data = data + 1 }
    countALL = countALL + 1
    if (item.kodePos) { data = data + 1 }


    return data / countALL * 100
}
function resetForm() {

}
function registrasiPasien() {
    router.push({
        name: 'module-registrasi-registrasi-ruangan',
        query: {
            nocmfk: ID_PASIEN_SET.value,
            statuspasien: "BARU",
        },
    })
}

listDropdown()


</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: 840px;
    margin: 0 auto;
}

.form-fieldset {
    padding: 20px 0;
    max-width: 580px;
    margin: 0 auto;
}
</style>
