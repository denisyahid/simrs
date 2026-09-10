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

    <div class="column is-12">
        <VCard>
            <div class="column is-12" v-for="(datas) in dataGeneral">
                <h1>{{ datas.title }}</h1>
                <div class="columns is-multiline p-3">
                    <div class="column pb-0" v-for="(data) in datas.detail"
                        :class="datas.detail.length == 2 ? 'is-6' : 'is-4'">
                        <span style="font-weight: 500;">{{ data.label }}</span>
                        <VField v-if="data.type == 'textBox'" class="mt-2">
                            <VControl>
                                <VInput type="text" class="input" v-model="input[data.model]" />
                            </VControl>
                        </VField>
                        <VField v-else class="mt-2">
                            <VDatePicker v-model="input.tanggalMedik" color="green" trim-weeks mode="dateTime"
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }" class="pb-0">
                                    <VField>
                                        <VControl icon="feather:calendar">
                                            <VInput type="text" placeholder="Select a date" :value="inputValue"
                                                v-on="inputEvents" class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                </div>
            </div>

            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-8">
                        <h1 class="mb-3">Posisi yang akan di lamar (deskripsikan kegiatan dan tugas dalam pekerjaan)</h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.posisiLamaran" rows="3" placeholder="Posisi yang akan dilamar...">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column">
                        <h1>Riwayat pernikahan</h1>
                        <div class="columns is-multiline pt-3">
                            <div class="column" v-for="(data) in statusHubungan">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input.status" class="p-0" :true-value="data" :label="data"
                                            color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <h1>Apakah pernah melakukan medical check up sebelumnya?</h1>
                        <VField class="mt-2">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.medicalCheckup" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <h1>Tanggal</h1>
                        <VDatePicker class="pt-2" v-model="input.tanggalMedik" color="green" trim-weeks mode="dateTime"
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                <VField>
                                    <VControl icon="feather:calendar">
                                        <VInput type="text" placeholder="Select a date" :value="inputValue"
                                            v-on="inputEvents" class="is-rounded_Z" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>
                </div>
            </div>
            <div class="column is-8">
                <h1 class="mb-3">Hasil dan rekomendasi:</h1>
                <VField>
                    <VControl>
                        <VTextarea v-model="input.posisiLamaran" rows="3" placeholder="Posisi yang akan dilamar...">
                        </VTextarea>
                    </VControl>
                </VField>
            </div>

            <div class="column is-12">
                <h1 class="mb-3">Riwayat Keluarga</h1>
                <table class="table">
                    <thead>
                        <tr class="tr">
                            <th class="th" width="10%">Hubungan</th>
                            <th class="th" width="13%">Usia (jika masih hidup)</th>
                            <th class="th" width="35%">Status Kesehatan (jika hidup cantumkan status Kesehatan, jika
                                meninggal cantumkan penyebab kematian)</th>
                            <th class="th" width="10%">Usia Kematian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="tr" v-for="(data) in riwayatKeluarga">
                            <td class="td">
                                <span style="font-weight: 500; color: black;">{{ data.hubungan }}</span>
                            </td>
                            <td class="td">
                                <VField class="mt-2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input[data.usia]"
                                            style="border: 1.6px solid;" />
                                    </VControl>
                                </VField>
                            </td>
                            <td class="td">
                                <VField class="mt-2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input[data.kondisi]"
                                            style="border: 1.6px solid;" />
                                    </VControl>
                                </VField>
                            </td>
                            <td class="td">
                                <VField class="mt-2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input[data.usiaKem]"
                                            style="border: 1.6px solid;" />
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="column is-12">
                <h1 class="mb-3">Adakah di keluarga yang menderita penyakit di bawah ini ?</h1>
                <table class="table">
                    <thead>
                        <tr class="tr">
                            <th class="th" width="25%">Jenis Sakit</th>
                            <th class="th" width="3%">Ya</th>
                            <th class="th" width="3%">Tidak</th>
                            <th class="th" width="18%">Siapa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="tr" v-for="(data, i) in menderitaPenyakit">
                            <td class="td">
                                <h1>{{ data.jenisSakit }}</h1>
                            </td>
                            <td class="td" style="text-align: center;">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model]" class="p-0 check"
                                            :true-value="data.pilihan_1" color="primary" circle />
                                    </VControl>
                                </VField>
                            </td>
                            <td class="td" style="text-align: center;">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model]" class="p-0 check"
                                            :true-value="data.pilihan_2" color="primary" circle />
                                    </VControl>
                                </VField>
                            </td>
                            <td class="td">
                                <VField class="mt-2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input[data.siapa + i]"
                                            style="border: 1.6px solid;" />
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="column is-12">
                <h1>Riwayat Penyakit Sekarang dan Riwayat Penyakit Dahulu</h1>
                <div class="column is-12">
                    <h1 class="mb-3">1. Apakah anda sedang atau pernah menderita penyakit di bawah ini?</h1>
                    <table class="table">
                        <thead>
                            <tr class="tr">
                                <th class="th" width="20%">Penyakit</th>
                                <th class="th" width="10%">Ya, Tahun</th>
                                <th class="th" width="5%">Tidak</th>
                                <th class="th" width="20%">Penyakit</th>
                                <th class="th" width="10%">Ya,Tahun</th>
                                <th class="th" width="5%">Tidak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, i) in riwayatPenyakit" class="tr">
                                <td class="td">
                                    <span style="font-weight: 500; color:black">{{ data.penyakit.L }}</span>
                                </td>
                                <td class="td">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input[data.tahun.L + i]"
                                                style="border: 1.6px solid;" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td style="text-align: center;">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.check.L + i]" class="p-0 check"
                                                true-value="Tidak" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="td">
                                    <span style="font-weight: 500;">{{ data.penyakit.R }}</span>
                                </td>
                                <td class="td">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input[data.tahun.R + i]"
                                                style="border: 1.6px solid;" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td style="text-align: center;" class="td">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.check.R + i]" class="p-0 check"
                                                true-value="Tidak" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="column is-12 pb-0" v-for="(datas) in pertanyaanPenyakit">
                    <h1>{{ datas.title }}</h1>
                    <div class="columns is-multiline pt-3 pl-5 pr-5">
                        <div class="column is-2 pb-0" v-for="(data) in datas.value">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" class="" :true-value="data.label"
                                        :label="data.label" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline pl-5 pr-5">
                        <div class="column is-5 pt-0" v-if="datas.optional" v-for="(data) in datas.optional">
                            <span style="font-weight: 500;">{{ data.label }}</span>
                            <VField class="mt-2">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input[data.model]" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column is-12" v-for="(datas) in pertanyaanMerokok">
                    <h1 class="pb-3">{{ datas.title }}</h1>
                    <div class="columns is-multiline pl-5" v-for="(data) in datas.detail">
                        <span style="font-weight: 500; margin-top: 10px;margin-left: 14px;" v-if="data.subTitle">{{
                            data.subTitle }}</span>
                        <div class="column is-2 pt-0" v-for="(pilihan) in data.value">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" class="pb-0" :true-value="data.label"
                                        :label="pilihan" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column is-12">
                    <h1>16. Apakah anda mengonsumsi alkohol ?</h1>
                    <div class="columns is-multiline pl-5 pr-5">
                        <div class="column is-6" style="margin-top: 18px;">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.konsumsiAlkohol" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6 pt-0">
                            <span style="font-weight: 500;">berapa banyak dalam sehari/seminggu?</span>
                            <VField class="mt-2">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.jmlAlkohol" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-8">
                    <h1>17. Bagaimana kegiatan anda ketika bekerja ?</h1>
                    <VField class="pt-3 pl-5 pr-5">
                        <VControl>
                            <VTextarea v-model="input.keluahan" rows="3" placeholder="Kegiatan ketika bekerja ...">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>

                <div class="column is-12" v-for="(datas) in pertanyaanPerem">
                    <h1>{{ datas.title }}</h1>
                    <div class="column is-12" v-for="(data) in datas.detail">
                        <div class="column is-multiline" v-if="data.type == 'textBox'">
                            <div class="column">
                                <span style="font-weight: 500;">{{ data.label }}</span>
                                <VField class="mt-2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input[data.model]" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <span style="font-weight: 500;" :class="data.type != 'checkBox' ? 'none' : ''">{{ data.label }}</span>
                        <div class="columns is-multiline">
                            <div class="column is-2" v-for="(pilihan) in data.value" v-if="data.type == 'checkBox'">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model]" class="pb-0" :true-value="pilihan"
                                            :label="pilihan" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </VCard>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, watch } from 'vue'
import { useRoute, } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/formulir-anamnesis-mcu'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let dataGeneral = ref(EMR.formulirGeneral())
let statusHubungan = ref(EMR.riwayatHubungan())
let riwayatKeluarga = ref(EMR.riwayatKeluarga())
let menderitaPenyakit = ref(EMR.listPeyakit())
let riwayatPenyakit = ref(EMR.riwayatPenyakit())
let pertanyaanPenyakit = ref(EMR.pertanyaanPenyakit())
let pertanyaanMerokok = ref(EMR.pertanyaanMerokok())
let pertanyaanPerem = ref(EMR.pertanyaanPerem())

const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_perawat: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('FormulirAnamnesisMCU') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tglKontrol: new Date()
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length > 0) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                } else { }
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.tandaTanganPerawat = H.tandaTangan().get("signaturePerawat")
    object.tandaTanganDokter = H.tandaTangan().get("signatureDokter")
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

const kembaliKeun = () => {
    window.history.back()
}



loadRiwayat()
setView()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

h1 {
    font-weight: bold !important;
    color: black;
}

.none {
    display: none;
}

.table {
    border-collapse: collapse;
}

.table,
.tr,
.th,
.td {
    border: 1.6px solid black !important;
}

.th,
.td {
    padding: 10px;
}

.th {
    font-weight: 500 !important;
    color: black !important;
    text-align: center !important;
}

.check {
    input+span {
        border: 1.6px solid black;
    }
}

// input + span {
//     border: 1.6px solid black;
// }
</style>