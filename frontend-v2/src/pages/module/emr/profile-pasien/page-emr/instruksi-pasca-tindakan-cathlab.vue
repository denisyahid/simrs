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
    <VCard>
        <div class="column is-12">
            <Fieldset :toggleable="true" legend="JENIS TINDAKAN">
                <div class="column is-12">

                    <div class="columns is-multiline pl-3">
                        <div class="column is-6" v-for="(data, i) in JenisTindakan">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.nama" :label="data.nama"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-10">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan Lainnya"
                                        v-model="input.ketLainnya" />
                                </VControl>

                            </VField>
                        </div>
                    </div>

                </div>
            </Fieldset>
        </div>

        <div class="column is-12">
            <Fieldset :toggleable="true" legend="HASIL TINDAKAN">
                <div class="column is-12" v-for="(datas) in hasilTindakan">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column is-6" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" style="margin-top: -1rem;">
                                <VControl raw subcontrol>
                                    <input v-model="input[data.model]" class="input p-0" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>
                <div class="column is-12" v-for="(datas) in stent">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" style="margin-top: -1rem;">
                                <VControl raw subcontrol>
                                    <input v-model="input[data.model]" class="input p-0" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>
            </Fieldset>
        </div>
        <div class="column is-12">
            <Fieldset :toggleable="true" legend="INSTRUKSI PERAWAT PASIEN (MANDIRI DAN KOLABORASI)">
                <h1 style="font-weight: bold"> AKSES ARTERI </h1>
                <div class="column is-6">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="input.pendekatanFemoral" value="pendekatanFemoral"
                                label="Pendekatan Femoral" class="p-0" color="primary"
                                style="font-weight:bold;color: black;" square />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="input.praCabut" value="Pra Cabut Sheath" label="Pra Cabut Sheath:"
                                class="pt-0 pb-0 pl-3" color="primary" style="font-weight:bold;color: black;" square />
                        </VControl>
                    </VField>

                </div>
                <div class="column is-12">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="input.observasiTandaVital" value="Observasi Tanda Vital"
                                label=" Observasi Tanda-Tanda Vital (TTV), lokasi pungsi dan distal pungsi setiap 15 menit sebanyak 4 kali, setiap 30 menit sebanyak 2 kali dan setiap jam sampai sheath di cabut."
                                class="pt-0 pb-0 pl-5" color="primary" square />
                        </VControl>
                    </VField>

                </div>
                <div class="column is-12">
                    <div class="columns is multiline">
                        <VField class="pl-5">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input.cekACT" value="cek ACT"
                                    label="Cek ACT, cabut sheath jika ACT < 150 detik. Pukul" class="pt-0 pb-0 pl-3"
                                    color="primary" square />
                            </VControl>
                        </VField>
                        <VDatePicker v-model="input.jamCekACT" color="green" mode="time" is24hr>
                            <template #default="{ inputValue, inputEvents }">
                                <VField>
                                    <VControl icon="feather:clock">
                                        <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>

                    </div>
                </div>
                <div class="column is-12">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="input.cabutSheat" value="Cabut Sheat"
                                label="Pada saat cabut sheath, jika terjadi bardikardi disertai hipotensi segera tinggikan ekstremitas bawah, loading cairan "
                                class="pt-0 pb-0 pl-5" color="primary" square />
                        </VControl>
                    </VField>

                </div>
                <div class="column is-6">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="input.pascaCabut" value="Pasca Cabut Sheath:" label="Pasca Cabut Sheath:"
                                class="pt-0 pb-0 pl-3" color="primary" style="font-weight:bold;color: black;" square />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <h1 class="pl-5"> 1. Observasi TTV, lokasi pungsi dan distal pungsi setiap 15 menit sebanyak 4 kali,
                        setiap 30 menit sebanyak 2 kali dan setiap</h1>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField class="pl-6">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input.observasiSetiap" value="1 jam sebanyak 4 kali (Ruang Intensif)"
                                    label="1 jam sebanyak 4 kali (Ruang Intensif)" class="pt-0 pb-0 pl-3" color="primary"
                                    square />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-8">
                        <VField class="pl-6">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input.observasiSetiap1"
                                    value="Setiap 2 jam sebanyak 2 kali (Ruang Rawat Inap)."
                                    label="Setiap 2 jam sebanyak 2 kali (Ruang Rawat Inap)." class="pt-0 pb-0 pl-3"
                                    color="primary" square />
                            </VControl>
                        </VField>
                    </div>

                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 2. Pasien bed rest 6 - 9 jam. Bagian kepala tempat tidur boleh dinaikan maksimal
                        setinggi 30</h1>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 3. Pasien tidak boleh menekuk pada persendian kaki daerah pungsi selama <b>6 - 8
                            Jam</b></h1>
                </div>
                <div class="column is-12 pt-0">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <h1 class="pt-0 pl-5"> 4. Balutan tekan dipertahankan selama selama <b>6 - 8 Jam. Dilepas
                                    Pukul</b>
                            </h1>

                        </div>
                        <div class="column is-2" style="margin-left: -4rem;">
                            <VDatePicker v-model="input.jamLepasBalutan" color="green" mode="time" is24hr>

                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>

                        </div>
                        <h1>selanjutnya boleh mobilisasi bertahap</h1>

                    </div>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 5. Anjurkan pasien melakukan penekanan pada daerah balutan jika batuk. Gunakan
                        bantal pasir jika perlu.</h1>
                </div>
                <div class="column is-6">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="input.pendekatanRadial" value="Pendekatan Radial" label="Pendekatan Radial"
                                class="p-0" color="primary" style="font-weight:bold;color: black;" square />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="input.praPelepasanRadial" value="Pra Pelepasan Radial Deivce:"
                                label="Pra Pelepasan Radial Deivce:" class="pt-0 pb-0 pl-3" color="primary"
                                style="font-weight:bold;color: black;" square />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 1. Observasi TTV, lokasi pungsi dan distal pungsi setiap 15 menit sebanyak 4
                        kali, setiap 30 menit sebanyak 2 kali dan
                        setiap jam samapai Radial Device di lepas.</h1>
                </div>
                <div class="column is-12 pt-0">
                    <div class="columns is-multiline">
                        <div class="column is-8">
                            <h1 class="pt-0 pl-5">2. Pelepasan Radial Device disesuaikan dengan prosedur yang berlaku.
                                Pengurangan tekanan dimulai pukul:
                            </h1>

                        </div>
                        <div class="column is-2" style="margin-left: -3rem;">
                            <VDatePicker v-model="input.jamLepasRadial" color="green" mode="time" is24hr>

                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>

                        </div>


                    </div>
                </div>
                <div class="column is-6 pt-0">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="input.pascaPelepasanRadial" value="Pasca Pelepasan Radial Deivce:"
                                label="Pasca Pelepasan Radial Deivce:" class="pt-0 pb-0 pl-3" color="primary"
                                style="font-weight:bold;color: black;" square />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 1. Paska pelepasan Radial Device, gunakan balutan tekan steril dan dipertahankan
                        <b>selama 4 Jam</b>
                    </h1>
                </div>
                <div class="column is-12 pt-0">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <h1 class="pt-0 pl-5">2. Balut tekan radial dilepas pukul:
                            </h1>

                        </div>
                        <div class="column is-2" style="margin-left: -3rem;">
                            <VDatePicker v-model="input.jamBalutTekan" color="green" mode="time" is24hr>

                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>

                        </div>


                    </div>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 3. Pada tindakan <b>Angiografi Koroner</b>: Observasi TTV, lokasi pungsi dan
                        distal pungsi setiap 15 menit sebanyak 4 kali.</h1>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 4. Pada tindakan <b>PCI</b>: Observasi TTV, lokasi pungsi dan distal pungsi
                        setiap 15 menit sebanyak 4 kali, setiap 30 menit sebanyak 2 kali dan setiap jam sebanyak 4 kali.
                    </h1>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 5. Pasien <b>bed rest 4 - 6 jam.</b> Pasien diperbolehkan duduk di tempat tidur.
                    </h1>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 6. Pasien tidak boleh menekuk (fleksi / hiperekstensi) pergelangan pada lengan
                        area pungsi selama 24 jam.</h1>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 7. Pasien tidak boleh mengangkat beban > 2 Kg menggunakan lengan area pungsi
                        selama 24 jam.</h1>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 8. Anjurkan pasien untuk melaporkan jika terdapat perdarahan atau hematoma.</h1>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold"> AKSES VENA </h1>
                </div>
                <div class="column is-6 pt-0">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="input.PascaCabutSheathVena" value="Pasca Cabut Sheath:"
                                label="Pasca Cabut Sheath:" class="pt-0 pb-0 pl-3" color="primary"
                                style="font-weight:bold;color: black;" square />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5">1. Observasi TTV, lokasi pungsi dan distal pungsi setiap 15 menit sebanyak 4 kali,
                        setiap 30 menit sebanyak 2 kali.</h1>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-5"> 2. Pasien tidak boleh menekuk pada daerah persendian daerah pungsi selama 2 jam.
                    </h1>
                </div>
                <div class="column is-12 pt-0">
                    <div class="columns is-multiline">
                        <div class="column is-5">
                            <h1 class="pt-0 pl-5"> 3. Balut tekan dipertahankan selama 2 jam. Dilepas pukul:
                            </h1>

                        </div>
                        <div class="column is-2" style="margin-left: -4rem;">
                            <VDatePicker v-model="input.jamdiTahan" color="green" mode="time" is24hr>

                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>

                        </div>
                        <h1>selanjutnya boleh mobilisasi bertahap</h1>

                    </div>
                </div>
                <div class="column is-12">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="input.anjuranPasienMinum" value="Anjurkan pasien banyak minum"
                                label="Anjurkan pasien banyak minum pada 6 - 8 jam pertama pasca tindakan jika tidak ada kontra indikasi atau pembatasan cairan seperti:
                                edema paru, gagal jantung, gagal ginjal dan lain-lain. Khusus tindakan yang mengunakan media zat kontras." class="pt-0 pb-0 pl-5" color="primary" square />
                        </VControl>
                    </VField>

                </div>
                <div class="column is-6 pt-0 pl-5">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="input.laporkanKeDokter"
                                value="Laporkan ke dokter jika terdapat tanda-tanda berikut:"
                                label=" Laporkan ke dokter jika terdapat tanda-tanda berikut:" class="pt-0 pb-0 pl-3"
                                color="primary" square />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12 pt-0">
                    <h1 class="pt-0 pl-6"> 1. Penurunan pulsasi perifer di bagian distal pungsi</h1>
                    <h1 class="pt-0 pl-6"> 2. Hematoma baru atau peningkatan ukuran hematoma yang sudah ada</h1>
                    <h1 class="pt-0 pl-6"> 3. Perdarahan aktif daerah pungsi</h1>
                    <h1 class="pt-0 pl-6"> 4. Nyeri yang berlebihan pada lokasi pungsi</h1>
                    <h1 class="pt-0 pl-6"> 5. Terjadi perasaan tidak nyaman di dada atau nafas pendek</h1>
                    <h1 class="pt-0 pl-6"> 6. Perubahan EKG, hemodinamik tidak stabil</h1>
                </div>


            </Fieldset>
        </div>
        <div class="column is-12">
            <Fieldset :toggleable="true" legend="INTRUKSI DOKTER">
                <div class="column is-12" v-for="(datas) in intruksi">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column is-3" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" style="margin-top: -1rem;">
                                <VControl raw subcontrol>
                                    <input v-model="input[data.model]" class="input pt-0 pl-3" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>
                <div class="column is-8">
                    <h1 style="font-weight : bold; margin-bottom: 1rem;"> Terapi Cairan</h1>
                    <VField>
                        <VControl>
                            <VInput type="text" v-model="input.terapiCairan" placeholder="Terapi Cairan" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold;" class="mb-3">Catatan Dokter</h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.catatanDokter" rows="3" placeholder="Catatan Dokter....">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
            </Fieldset>


        </div>


    </VCard>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/instruksi-pasca-tindakan-cathlab'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let JenisTindakan = ref(EMR.JenisTindakan())
let hasilTindakan = ref(EMR.hasilTindakan())
let stent = ref(EMR.stent())
let intruksi = ref(EMR.intruksi())

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
    jamCekACT: new Date(),
    jamLepasBalutan: new Date(),
    jamLepasRadial: new Date(),
    jamBalutTekan: new Date(),
    jamdiTahan: new Date()
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

const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {

}
setView()
setAutoFill()
loadRiwayat()
</script>
