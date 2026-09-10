<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Dokter DPJP</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="" v-model="input.dokterRawat" disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Dokter Pendamping</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.dokterPendamping" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="ketik nama dokter" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <h1 style="font-weight: bold;">Tanggal Operasi</h1>
                            </VField>
                            <VField>
                                <VDatePicker v-model="input.tanggalOperasi" mode="dateTime" style="width: 100%" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight:bold;">Jam Tiba diruang Operasi</h1>
                            <VDatePicker class="column is-8" v-model="input.jamMasuk" color="green" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Rencana Tindakan</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.rencanaTindakan" rows="2">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Urutan Jadwal Operasi</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="" v-model="input.urutanJadwal" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4" v-for="(data) in areaOperasi">
                            <span style="font-weight: bold;">{{ data.title }}</span>
                            <div class="columns is-multiline p-3">
                                <div class="column" v-for="(pilihan) in data.detail ">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label"
                                                class="p-0" :label="pilihan.label" color="primary" square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12" v-for="(data, i) in kondisi">
                            <span style="font-weight: bold;">{{ data.title }}</span>
                            <div class="columns is-multiline p-3">
                                <div class="column is-4" v-for="(pilihan) in data.detail ">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[pilihan.model + i]" :true-value="pilihan.label"
                                                class="p-0" :label="pilihan.label" color="primary" square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <table class="triase" style="width: 80%; margin-top: 1rem;">

                                    <tr>
                                        <th style="text-align : center"> No </th>
                                        <th style="text-align : center"> Kelengkapan </th>
                                        <th style="text-align: center"> RI </th>
                                        <th style="text-align: center"> OK </th>
                                        <th style="text-align: center"> RR </th>
                                    </tr>
                                    <tr v-for="(datas) in DaftarInformasi">
                                        <td style="width:3%; text-align: center;">{{ datas.no }}</td>
                                        <td style="width:25%">{{ datas.Kriteria }}</td>
                                        <td style="width:5%; text-align: center;">
                                            <div class="column is 12">
                                                <div class="column" v-for="(dot) in datas.pilihanRI">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" v-model="input[dot.model]"
                                                                :true-value="dot.title" :label="dot.title" color="primary"
                                                                square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width:5%; text-align: center;">
                                            <div class="column is 4">
                                                <div class="column" v-for="(dot) in datas.pilihanOK">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" v-model="input[dot.model]"
                                                                :true-value="dot.title" :label="dot.title" color="primary"
                                                                square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width:5%; text-align: center;">
                                            <div class="column is 12">
                                                <div class="column" v-for="(dot) in datas.pilihanRR">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" v-model="input[dot.model]"
                                                                :true-value="dot.title" :label="dot.title" color="primary"
                                                                style="text-align: center;" square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <h1 style="font-weight: bold; margin-bottom: 1rem;">
                                    Keterangan : Bila ada catatan yang perlu ditambahkan bisa mengisi pada kolom catatan
                                </h1>

                            </div>
                        </div>
                        <div class="column is-8">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Catatan</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.catatan" rows="2">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Perawat RI</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.perawatRI" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik nama dokter" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Perawat Pre Med</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.perawatPreMed" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik nama dokter" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Perawat OK</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.perawatPreMed" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik nama dokter" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Penata Anastesi</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.penataAnastesi" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik nama dokter" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Perawat RR</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.perawatRR" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik nama dokter" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Perawat RI</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.perawatRI" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik nama dokter" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="column is-12">
                    <Fieldset legend="Pemberian Obat - Obatan di Ruang Rawat" :toggleable="true">
                        <div class="column is-12 mt-3">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;"> Pemberian Obat - Obatan di Ruang Rawat
                            </h1>
                            <table class="tn" style="width: 80%; margin-top: 1rem;">
                                <thead>
                                    <tr>
                                        <td class="tn-0lax">Nama Antibiotik / Lainnya</td>
                                        <td class="tn-0lax">Waktu Pemberian (Jam)</td>
                                        <td class="tn-0lax">#</td>

                                    </tr>
                                </thead>
                                <tbody v-for="(item, index) in input.details" :key="index">
                                    <tr>
                                        <td>
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="item.namaObat" placeholder="Nama Obat" />
                                                </VControl>
                                            </VField>
                                        </td>
                                        <td>
                                            <VDatePicker v-model="item.jamPemberian" color="green" mode="time" is24hr>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField>
                                                        <VControl icon="feather:clock">
                                                            <VInput class="input form-timepicker" :value="inputValue"
                                                                v-on="inputEvents" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </td>

                                        <td rowspan="2" style="width:13%;vertical-align: middle;">
                                            <div class="columns is-multiline">
                                                <div class="column is-6">
                                                    <VIconButton type="button" raised circle icon="feather:plus"
                                                        @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                                    </VIconButton>
                                                </div>
                                                <div class="column is-6 ml-3-min">
                                                    <VIconButton v-if="index > 0" type="button" raised circle
                                                        icon="feather:trash" @click="removeItem(index)" color="danger">
                                                    </VIconButton>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>



                                </tbody>
                            </table>

                        </div>
                    </Fieldset>
                </div>
                <div class="column is-12">
                    <Fieldset legend="Bahan dan Alat/Obat yang disertakan" :toggleable="true">
                        <div class="column is-12 mt-3">

                            <table class="tn" style="width: 80%; border: none;">
                                <thead>
                                    <tr>
                                        <td class="tn-0lax" style="border: none;">Bahan dan Alat/Obat yang disertakan</td>

                                    </tr>
                                </thead>
                                <tbody v-for="(item, index) in input.details" :key="index">
                                    <tr>
                                        <td style="border: none; margin-top: 2rem;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="item.namaBahan"
                                                        placeholder="Nama Bahan dan Alat/Obat" />
                                                </VControl>
                                            </VField>
                                        </td>
                                        <td rowspan="3" style="width:13%;vertical-align: middle; border: none;">
                                            <div class="columns is-multiline">
                                                <div class="column is-6">
                                                    <VIconButton type="button" raised circle icon="feather:plus"
                                                        @click="addNewItem1()" color="info" v-tooltip.bubble="'Tambah '">
                                                    </VIconButton>
                                                </div>
                                                <div class="column is-6 ml-3-min">
                                                    <VIconButton v-if="index > 0" type="button" raised circle
                                                        icon="feather:trash" @click="removeItem1(index)" color="danger">
                                                    </VIconButton>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </Fieldset>
                </div>
                <div class="column is-12">
                    <Fieldset legend="Perincian Bahan Habis Pakai Kamar Bedah" :toggleable="true">
                        <div class="column is-12 mt-3">
                            <table class="tn" style="width: 80%; margin-top: 1rem;">
                                <thead>
                                    <tr>
                                        <td class="tn-0lax">Nama Obat</td>
                                        <td class="tn-0lax">Jumlah</td>
                                        <td class="tn-0lax">Satuan</td>
                                        <td class="tn-0lax">#</td>

                                    </tr>
                                </thead>
                                <tbody v-for="(item, index) in input.details" :key="index">
                                    <tr>
                                        <td>
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="item.barangHabisPakai"
                                                        placeholder="Barang Habis Pakai" />
                                                </VControl>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="item.jumlah" placeholder="Jumlah" />
                                                </VControl>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="item.satuan" placeholder="Satuan" />
                                                </VControl>
                                            </VField>
                                        </td>



                                        <td rowspan="2" style="width:13%;vertical-align: middle;">
                                            <div class="columns is-multiline">
                                                <div class="column is-6">
                                                    <VIconButton type="button" raised circle icon="feather:plus"
                                                        @click="addNewItem2()" color="info" v-tooltip.bubble="'Tambah '">
                                                    </VIconButton>
                                                </div>
                                                <div class="column is-6 ml-3-min">
                                                    <VIconButton v-if="index > 0" type="button" raised circle
                                                        icon="feather:trash" @click="removeItem2(index)" color="danger">
                                                    </VIconButton>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </Fieldset>
                </div>
                <div class="column is-12">
                    <Fieldset legend="Paska Operas" :toggleable="true">
                        <div class="columns is-multiline">
                            <table class="triase" style="width: 80%; margin: 3rem;">

                                <tr>
                                    <th style="text-align : center"> No </th>
                                    <th style="text-align : center"> Kelengkapan </th>
                                    <th style="text-align: center"> RI </th>
                                    <th style="text-align: center"> OK </th>
                                    <th style="text-align: center"> RR </th>
                                </tr>
                                <tr v-for="(datas) in PascaOperasi">
                                    <td style="width:3%; text-align: center;">{{ datas.no }}</td>
                                    <td style="width:25%">{{ datas.Kriteria }}</td>
                                    <td style="width:5%; text-align: center;">
                                        <div class="column is 12">
                                            <div class="column" v-for="(dot) in datas.pilihanRI">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" v-model="input[dot.model]"
                                                            :true-value="dot.title" :label="dot.title" color="primary"
                                                            square />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width:5%; text-align: center;">
                                        <div class="column is 4">
                                            <div class="column" v-for="(dot) in datas.pilihanOK">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" v-model="input[dot.model]"
                                                            :true-value="dot.title" :label="dot.title" color="primary"
                                                            square />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width:5%; text-align: center;">
                                        <div class="column is 12">
                                            <div class="column" v-for="(dot) in datas.pilihanRR">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" v-model="input[dot.model]"
                                                            :true-value="dot.title" :label="dot.title" color="primary"
                                                            style="text-align: center;" square />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>


                        </div>
                        <div class="column is-8">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Catatan</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.catatanPascaOperasi" rows="2">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Perawat OK</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.perawatOK2" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik nama dokter" />
                                        </VControl>
                                    </VField>
                                </div>
                            
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Perawat RR</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.perawatRR2" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik nama dokter" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Perawat RI</h1>
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.perawatRI2" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik nama dokter" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </Fieldset>
                </div>

            </VCard>
        </div>
    </div>
</template>
  
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/pra-operasi'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let areaOperasi = ref(EMR.areaOperasi())
let kondisi = ref(EMR.kondisi())
let DaftarInformasi = ref(EMR.DaftarInformasi())
let PascaOperasi = ref(EMR.PascaOperasi())

const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
        COLLECTION?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
        COLLECTION: '',
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    jamMasuk: new Date(),
    tanggalOperasi: new Date(),
    jamPemberian: new Date(),
    details: [{
        no: 1,
        tgl: new Date(),
    }]
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    isLoading.value = true
    useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const fetchPegawai = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}


const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const kembaliKeun = () => {
    window.history.back()
}
const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}
const addNewItem1 = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem2 = (index: any) => {
    input.value.details.splice(index, 1)
}
const addNewItem2 = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem1 = (index: any) => {
    input.value.details.splice(index, 1)
}

const setAutoFill = async () => {
    input.value.dokterRawat = props.registrasi.dokter

}

setAutoFill()
loadRiwayat()
</script>
  
<style lang="scss">
table.triase {
    border-collapse: collapse;
    width: 100%;
}

table.triase,
.triase th,
.triase td {
    border: 0.5px solid grey;
}


.triase th,
.triase td {
    padding: 8px;
    vertical-align: middle !important;
}

.tn {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;

}

.tn td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 3px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tn th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 3px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tn .tg-0lax {
    text-align: center;
    vertical-align: top
}
</style>
  