<style lang="scss">
h1 {
    font-weight: bold;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    text-align: center !important;
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: bold;
    overflow: hidden;
    background-color: aquamarine;
    vertical-align: middle;
    padding: 10px 5px;
    word-break: normal;
}
</style>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import Checkbox from 'primevue/checkbox';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset';
// import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import * as EMR from '../page-emr-plugins/monitoring&evaluasi-resusitasi'

// Loopingan
let ListPSF = ref([
    { caption: "1. Mengontrol BAB" },
    { caption: "2. Mengontrol BAK" },
    { caption: "3. Membersihkan diri" },
    { caption: "4. Penggunaan toilet" },
    { caption: "5. Makan" },
    { caption: "6. Berpindah dari tidur ke duduk" },
    { caption: "7. Berjalan" },
    { caption: "8. Berpakaian" },
    { caption: "9. Naik turun tangga" },
    { caption: "10. Mandi" },
    { caption: "Total" },
])

let ListPenapisanDepresi = ref([
    { caption: "Apakah Anda sebenarnya puas dengan kehidupan anda?", nilai0: "Ya", nilai1: "Tidak" },
    { caption: "Apakah Anda telah meninggalkan banyak kegiatan dan minat atau kesenangan Anda?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda merasa bahwa hidup Anda kosong?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda sering merasa bosan?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda sangat berharap terhadap masa depan?", nilai0: "Ya", nilai1: "Tidak" },
    { caption: "Apakah Anda merasa terganggu dengan pikiran bahwa Anda tidak dapat keluar dari pikiran Anda?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda merasa mempunyai semangat yang baik setiap saat?", nilai0: "Ya", nilai1: "Tidak" },
    { caption: "Apakah Anda merasa takut bahwa sesuatu yang buruk akan terjadi pada diri Anda?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda merasa bahagia untuk sebagian besar hidup anda?", nilai0: "Ya", nilai1: "Tidak" },
    { caption: "Apakah Anda sering merasa tidak berdaya?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda sering merasa resah dan gelisah?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda lebih senang berada di rumah daripada keluar dan melakukan hal-hal baru?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda sering merasa khawatir dengan masa depan?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda merasa memiliki lebih banyak masalah dengan daya ingat dibandingkan kebanyakan orang?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah menurut Anda hidup Anda saat ini menyenangkan?", nilai0: "Ya", nilai1: "Tidak" },
    { caption: "Apakah Anda sering merasa sedih?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah saat ini Anda merasa tidak berharga?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda merasa khawatir tentang masa lalu Anda?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda merasa hidup ini sangat menarik dan menyenangkan?", nilai0: "Ya", nilai1: "Tidak" },
    { caption: "Apakah sulit bagi Anda untuk memulai sesuatu hal yang baru?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda merasa penuh semangat?", nilai0: "Ya", nilai1: "Tidak" },
    { caption: "Apakah Anda merasa bahwa keadaan Anda sekarang tidak ada harapan?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda merasa orang lain memiliki keadaan yang lebih baik dari Anda?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda sering merasa sedih atas hal-hal kecil?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda sering merasa ingin menangis?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda memiliki kesulitan berkonsentrasi?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah Anda senang ketika bangun di pagi hari?", nilai0: "Ya", nilai1: "Tidak" },
    { caption: "Apakah Anda lebih memilih menghindari pertemuan sosial atau bermasyarakat?", nilai0: "Tidak", nilai1: "Ya" },
    { caption: "Apakah mudah bagi Anda untuk membuat keputusan?", nilai0: "Ya", nilai1: "Tidak" },
    { caption: "Apakah pikiran Anda secerah biasanya?", nilai0: "Ya", nilai1: "Tidak" },
])

let listPI = ref([
    { caption: "Apakah anda mengompol atau BAB tanpa disadari" },
    { caption: "Tidak pernah (0)" },
    { caption: "Kadang-kadang kehilangan kontrol berkemih/menggunakan alat bantu untuk berkemih & BAB (1)" },
    { caption: "Kehilangan kontrol berkemih sedikitnya sekali dalam sebulan (2,5)" },
    { caption: "Kehilangan kontrol berkemih sedikitnya 2 kali sebulan/kadang-kadang kehilangan kontrol BAB (4)" },
    { caption: "Kehilangan kontrol BAB sedikitnya sekali dalam sebulan (5)" },
    { caption: "Kehilangan kontrol berkemih sedikitnya sekali dalam seminggu (5,5)" },
    { caption: "Kehilangan kontrol BAB sedikitnya 2 kali sebulan (6,5)" },
    { caption: "Kehilangan kontrol BAB sedikitnya sekali seminggu/kehilangan kontrol berkemih sedikitnya sekali setiap hari (8)" },
    { caption: "Kehilangan kontrol BAB sedikitnya sekali sehari (10)" },
    { caption: "Tidak bisa mengontrol fungsi berkemih sama sekali (10,5)" },
    { caption: "Tidak bisa mengontrol BAB sama sekali (11,5)" },
])

let listPDVT = ref([
    { caption: "Kanker aktif (dalam terapi atau paliatif) (1)", value: "1" },
    { caption: "Paralisis, paresis, atau imobilisasi ekstremitas bawah (1)", value: "1" },
    { caption: "Tirah baring lebih dari 3 hari karena pembedahan (dalam 4 bulan) (1)", value: "1" },
    { caption: "Nyeri tekan terlokalisasi sepanjang distribusi vena dalam (1)", value: "1" },
    { caption: "Pembengkakan seluruh tungkai (1)", value: "1" },
    { caption: "Bengkak pada betis unilateral lebih dari 3 cm (di bawah tuberositas tibia) (1)", value: "1" },
    { caption: "Edema pitting unilateral (1)", value: "1" },
    { caption: "Kolateral vena superfisial (1)", value: "1" },
    { caption: "Ada diagnosis alternatif lain selain DVT dengan kemungkinan sama atau lebih (-2)", value: "-2" },
])

let listPenapisanInsomnia = ref([
    { caption: "Sulit memulai tidur", cb0: "(0) tidak ada", cb1: "(1) ringan", cb2: "(2) sedang", cb3: "(3) berat", cb4: "(4) sangat berat" },
    { caption: "Sulit mempertahankan tidur", cb0: "(0) tidak ada", cb1: "(1) ringan", cb2: "(2) sedang", cb3: "(3) berat", cb4: "(4) sangat berat" },
    { caption: "Bangun dari tidur terlalu awal", cb0: "(0) tidak ada", cb1: "(1) ringan", cb2: "(2) sedang", cb3: "(3) berat", cb4: "(4) sangat berat" },
    { caption: "Kepuasan terhadap pola tidur saat ini", cb0: "(0) sangat puas", cb1: "(1) Puas", cb2: "(2) Sedikit puas", cb3: "(3) Tidak puas", cb4: "(4) Sangat tidak puas" },
    { caption: "Apakah gangguan tidur ini mempengaruhi kualitas hidup anda", cb0: "(0) Tidak jelas", cb1: "(1) Sedikit", cb2: "(2) Kadang-kadang", cb3: "(3) Jelas", cb4: "(4) Sangat jelas" },
    { caption: "Apakah anda mengkhawatirkan gangguan tidur anda saat ini", cb0: "(0) tidak", cb1: "(1) Sedikit", cb2: "(2) Kadang-kadang", cb3: "(3) Khawatir", cb4: "(4) Sangat khawatir" },
    { caption: "Apakah gangguan tidur anda mempengaruhi aktivitas/fungsi anda sehari-hari", cb0: "(0) tidak", cb1: "(1) Sedikit", cb2: "(2) Kadang-kadang", cb3: "(3) Banyak mengganggu", cb4: "(4) Sangat mengganggu" }
])


// Judul
useHead({
    title: 'Asesmen Awal Medis Geriatri Rawat Jalan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
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
const newDate = new Date();
const input: any = ref({
    JAM: newDate,
    TBtotalSkorPDVT: 0
})
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
const loadData: any = ref(true)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    date: {
        tanggal: new Date,
        jam: new Date
    },
    filter: '',
    airway: [],
    disability: []
})
const COLLECTION: any = ref('AsesmenAwalMedisGeriatriRJ') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const loadRiwayat = async () => {
    // if (NOREC_EMRPASIEN.value == '') return
    await useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
                dataTTD.value = response[0]
            }
        })
    // H.tandaTangan().set("TTDPetugas", dataTTD.value.TTDPetugas)
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    // object['TTDPetugas'] = H.tandaTangan().get("TTDPetugas");
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
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}
const calculateTotalSkorPDVT = () => {
    let total = 0;
    listPDVT.value.forEach((data, index) => {
        if (input.value['CBPDVT_' + index]) {
            total += parseFloat(data.value); // Ensure the value is a number
        }
    });
    input.value.TBtotalSkorPDVT = total;
};

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
        loadData.value = false
    } catch (error) {
        console.error('Error mount cache TAB EMR:', error);
    }
});
onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});

// getDataExist()
fetchPasien()
// setAutoFill()
</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Asesmen Awal Medis Geriatri Rawat Jalan</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <!--
                                <VButton type="button" rounded outlined color="warning"
                                    :disabled="NOREC_EMRPASIEN == undefined" raised icon="lnir lnir-printer"
                                    @click="print()"> Cetak
                                </VButton>
                                -->
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading || isLoadingPasien" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="columns is-multiline p-2">
                    <div class="columns column is-12">
                        <div class="column is-4">
                            <VField label="Tanggal :">
                                <VDatePicker v-model="input.DtanggalForm" mode="date" trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Jam Kedatangan :">
                                <VDatePicker v-model="input.HjamKedatangan" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:clock" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Jam Asesmen Awal :">
                                <VDatePicker v-model="input.HjamAW" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:clock" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Alloanamesis">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Suami/Istri"
                                            label="Suami/Istri" v-model="input.CBSuamiORIstriAlloa" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Orang Tua"
                                            label="Orang Tua" v-model="input.CBOrangTuaAlloa" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Anak" label="Anak"
                                            v-model="input.CBAnakAlloa" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                            label="Lainnya" v-model="input.CBLainnyaAlloa" />
                                    </VControl>
                                    <VControl style="margin-top: 5px">
                                        <VInput type="text" class="input" v-model="input.TBLainnyaAlloa" />
                                    </VControl>
                                </div>
                            </div>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Anamnesis">
                            <div class="column is-12">
                                <VField>
                                    <label>1. Keluhan Utama :</label>
                                    <VTextarea v-model="input.TAAnamnesis" rows="3">
                                    </VTextarea>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField>
                                    <label>2. Riwayat Penyakit Sekarang :</label>
                                    <VTextarea v-model="input.TArps" rows="3">
                                    </VTextarea>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField>
                                    <label>3. Riwayat Penyakit Dahulu/ Riwayat Inap Rumah Sakit :</label>
                                    <div class="columns is-multiline" style="margin-top: 7px;margin-bottom: 10px;">
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Hipertensi"
                                                    label="Hipertensi" v-model="input.CBhipertensiRPD" />
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Kencing Manis"
                                                    label="Kencing Manis" v-model="input.CBkencingManisRPD" />
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Jantung"
                                                    label="Jantung" v-model="input.CBjantungRPD" />
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Asthma"
                                                    label="Asthma" v-model="input.CBasthmaRPD" />
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Stroke"
                                                    label="Stroke" v-model="input.CBstrokeRPD" />
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Liver"
                                                    label="Liver" v-model="input.CBliverRPD" />
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Ginjal"
                                                    label="Ginjal" v-model="input.CBginjalRPD" />
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="TBC Paru"
                                                    label="TBC Paru" v-model="input.CBtbc_paruRPD" />
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                                    label="Lain-lain" v-model="input.CBlainlainRPD" />
                                            </VControl>
                                            <VControl style="margin-top: 5px">
                                                <VInput type="text" class="input" v-model="input.TBlainlainRPD" />
                                            </VControl>
                                        </div>
                                    </div>

                                    <label>Riwayat inap rumah sakit :</label>
                                    <div class="columns is-multiline" style="margin-top: 7px;">
                                        <div class="column is-6 columns">
                                            <div class="column is-4">
                                                <VField label="&nbsp;">
                                                    <VDatePicker v-model="input.DTrips" mode="date" trim-weeks
                                                        :max-date="new Date()">
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" v-on="inputEvents" />
                                                            </VControl>
                                                        </template>
                                                    </VDatePicker>
                                                </VField>
                                            </div>

                                            <div class="column is-4">
                                                <VField label="RS :">
                                                    <VControl>
                                                        <VInput type="text" class="input" v-model="input.TBrsRIPS" />
                                                    </VControl>
                                                </VField>
                                            </div>

                                            <div class="column is-4">
                                                <VField label="Diagnosis :">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBdiagnosisRIPS" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField>
                                    <label>4. Riwayat Pengobatan :</label>
                                    <div class="columns is-multiline" style="margin-top: 7px;">
                                        <div class="column is-12">
                                            <label><u>Nama Obat Dosis Lamanya</u></label>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBpertamaNODL1"
                                                        placeholder="1." />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBpertamaNODL2" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBpertamaNODL3" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBkeduaNODL1"
                                                        placeholder="2." />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBkeduaNODL2" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBkeduaNODL3" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBketigaNODL1"
                                                        placeholder="3." />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBketigaNODL2" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBketigaNODL3" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField>
                                    <label>5. Riwayat Pembedahan :</label>
                                    <div class="columns is-multiline" style="margin-top: 7px;">
                                        <div class="column is-12 columns is-multiline">
                                            <div class="column is-3">
                                                <VField label="&nbsp;">
                                                    <VDatePicker v-model="input.DpertamaRP" mode="date" trim-weeks
                                                        :max-date="new Date()">
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" v-on="inputEvents" />
                                                            </VControl>
                                                        </template>
                                                    </VDatePicker>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Jenis pembedahan :">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBjenisPembedahan1RP" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                        <div class="column is-12 columns is-multiline">
                                            <div class="column is-3">
                                                <VField label="&nbsp;">
                                                    <VDatePicker v-model="input.DkeduaRP" mode="date" trim-weeks
                                                        :max-date="new Date()">
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" v-on="inputEvents" />
                                                            </VControl>
                                                        </template>
                                                    </VDatePicker>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Jenis pembedahan :">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBjenisPembedahan2RP" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </VField>
                            </div>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Pemeriksaan Fisik">
                            <div class="columns is-multiline" style="padding: 10px; padding-top: 0px">
                                <div class="column is-12">
                                    <span><b>A.Tanda-tanda Vital</b></span>
                                </div>
                                <div class="column is-12">
                                    <span>Keadaan umum : </span>
                                </div>
                                <div class="column is-12 columns is-multiline">
                                    <div class="column is-6 columns is-multiline">
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Baik"
                                                    label="Baik" v-model="input.CBBaikKU" />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Sedang"
                                                    label="Sedang" v-model="input.CBSedangKU" />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Buruk"
                                                    label="Buruk" v-model="input.CBBurukKU" />
                                            </VControl>
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <VField label="GCS : ">
                                            <VField horizontal>
                                                <VField addons style="padding: 10px;padding-top:0px">
                                                    <VControl class="field-addon-body">
                                                        <VButton static>E</VButton>
                                                    </VControl>
                                                    <VControl>
                                                        <VInput type="text" class="input" v-model="input.TBeGCS" />
                                                    </VControl>
                                                </VField>
                                                <VField addons style="padding: 10px;padding-top:0px">
                                                    <VControl class="field-addon-body">
                                                        <VButton static>V</VButton>
                                                    </VControl>
                                                    <VControl>
                                                        <VInput type="text" class="input" v-model="input.TBvGCS" />
                                                    </VControl>
                                                </VField>
                                                <VField addons style="padding: 10px;padding-top:0px">
                                                    <VControl class="field-addon-body">
                                                        <VButton static>M</VButton>
                                                    </VControl>
                                                    <VControl>
                                                        <VInput type="text" class="input" v-model="input.TBmGCS" />
                                                    </VControl>
                                                </VField>
                                            </VField>
                                        </VField>
                                    </div>
                                    <div class="column is-12 columns is-multiline">
                                        <div class="column is-3">
                                            <VField addons style="padding: 5px;padding-top:0px" label="Tensi baring : ">
                                                <VControl>
                                                    <VInput type="text" class="input"
                                                        v-model="input.TBtensiBaringTTV" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>mmHg</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3">
                                            <VField addons style="padding: 5px;padding-top:0px" label="duduk : ">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBdudukTTV" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>mmHg</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3">
                                            <VField addons style="padding: 5px;padding-top:0px" label="berdiri : ">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBberdiriTTV" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>mmHg</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3"></div>
                                        <div class="column is-3">
                                            <VField addons style="padding: 5px;padding-top:0px" label="Nadi : ">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBnadiTTV" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>x/mnt</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3">
                                            <VField addons style="padding: 5px;padding-top:0px" label="Respirasi : ">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBrespirasiTTV" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>x/m</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3">
                                            <VField addons style="padding: 5px;padding-top:0px" label="Suhu : ">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBcelciusTTV" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>°C</VButton>
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-3">
                                            <VField addons style="padding: 5px;padding-top:0px" label="SaO2 : ">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBnsao2TTV" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>%</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <span><b>B.Status Generalis</b></span>
                                </div>
                                <div class="column is-12 columns is-multiline">
                                    <div class="column is-12">
                                        <VField label="Kepala">
                                            <VTextarea v-model="input.TAKepalaSG" rows="2">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <div class="column"><b>Mata : </b></div>
                                        <div class="columns is-multiline">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Anemis"
                                                        label="Anemis" v-model="input.CBAnemisMata" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBAnemisMata" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ikterus"
                                                        label="Ikterus" v-model="input.CBIkterusMata" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBIkterusMata" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Refleks Pupil" label="Refleks Pupil"
                                                        v-model="input.CBRefleksPupilMata" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input"
                                                        v-model="input.TBRefleksPupilMata" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Oedema Palpebrae" label="Oedema Palpebrae"
                                                        v-model="input.CBOedemaPalpebraeMata" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input"
                                                        v-model="input.TBOedemaPalpebraeMata" />
                                                </VControl>
                                            </div>
                                            <div class="column is-12 columns">
                                                <div class="column is-4">Membaca huruf koran dengan kaca mata :</div>
                                                <div class="column is-4">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                            label="Ya"
                                                            v-model="input.CBMembacaHurufKoranDenganKacamata" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-4">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                            label="Tidak"
                                                            v-model="input.CBMembacaHurufKoranDenganKacamata" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column"><b>THT : </b></div>
                                        <div class="columns is-multiline">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tonsil"
                                                        label="Tonsil" v-model="input.CBTonsilTHT" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBTonsilTHT" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Pharing"
                                                        label="Pharing" v-model="input.CBPharingTHT" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBPharingTHT" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Lidah"
                                                        label="Lidah" v-model="input.CBLidahTHT" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBLidahTHT" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Bibir"
                                                        label="Bibir" v-model="input.CBBibirTHT" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBBibirTHT" />
                                                </VControl>
                                            </div>
                                            <div class="column is-12 columns">
                                                <div class="column is-4">Mendengar suara normal :</div>
                                                <div class="column is-4">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                            label="Ya" v-model="input.CBMendengarSuaraNormal" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-4">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                            label="Tidak" v-model="input.CBMendengarSuaraNormal" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column"><b>Mulut : </b></div>
                                        <div class="columns is-multiline">
                                            <div class="column is-4 columns is-multiline">
                                                <div class="column is-12">Hygiene mulut :</div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                            label="Ya" v-model="input.CBHygieneMulut" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                            label="Tidak" v-model="input.CBHygieneMulut" />
                                                    </VControl>
                                                </div>
                                            </div>
                                            <div class="column is-4 columns is-multiline">
                                                <div class="column is-12">Gigi tiruan :</div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                            label="Ya" v-model="input.CBHGigiTiruan" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                            label="Tidak" v-model="input.CBHGigiTiruan" />
                                                    </VControl>
                                                </div>
                                            </div>
                                            <div class="column is-4 columns is-multiline">
                                                <div class="column is-12">Gigi tiruan terpasang baik :</div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                            label="Ya" v-model="input.CBGigiTiruanTerpasangBaik" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                            label="Tidak" v-model="input.CBGigiTiruanTerpasangBaik" />
                                                    </VControl>
                                                </div>
                                            </div>
                                            <div class="column is-4 columns is-multiline">
                                                <div class="column is-12">Lesi di bawah gigi tiruan :</div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                            label="Ya" v-model="input.CBLesiDibawahGigiTiruan" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                            label="Tidak" v-model="input.CBLesiDibawahGigiTiruan" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column"><b>Leher : </b></div>
                                        <div class="columns is-multiline">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="JVP"
                                                        label="JVP" v-model="input.CBJVPLeher" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBJVPLeher" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Pembesaran Kelenjar" label="Pembesaran Kelenjar"
                                                        v-model="input.CBPembesaranKelenjarLeher" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input"
                                                        v-model="input.TBPembesaranKelenjarLeher" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Kaku Kuduk" label="Kaku Kuduk"
                                                        v-model="input.CBKakuKudukLeher" />
                                                </VControl>
                                            </div>
                                        </div>

                                        <div class="column"><b>Thoraks : </b></div>

                                        <div class="column"><b>-Cor : </b></div>
                                        <div class="columns is-multiline">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="S1,S2"
                                                        label="S1,S2" v-model="input.CBS1S2Cor" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBS1S2Cor" />
                                                </VControl>
                                                <div class="columns" style="margin-top: 5px;">
                                                    <div class="column is-6">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="Reguler" label="Reguler"
                                                                v-model="input.CBRegulerCor" />
                                                        </VControl>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="Ireguler" label="Ireguler"
                                                                v-model="input.CBIregulerCor" />
                                                        </VControl>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Murmur"
                                                        label="Murmur" v-model="input.CBMurmurCor" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBMurmurCor" />
                                                </VControl>
                                            </div>
                                        </div>

                                        <div class="column"><b>-Pulmo : </b></div>
                                        <div class="columns is-multiline">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ronchi"
                                                        label="Ronchi" v-model="input.CBRonchiPulmo" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBRonchiPulmo" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Wheezing"
                                                        label="Wheezing" v-model="input.CBWheezingPulmo" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBWheezingPulmo" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Vesikuler"
                                                        label="Vesikuler" v-model="input.CBVesikulerPulmo" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                                        label="Lain-lain" v-model="input.CBLainnyaPulmo" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBLainnyaPulmo" />
                                                </VControl>
                                            </div>
                                        </div>

                                        <div class="column"><b>Abdomen : </b></div>
                                        <div class="columns is-multiline">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Distensi"
                                                        label="Distensi" v-model="input.CBDistensiAbdomen" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Meteorismus" label="Meteorismus"
                                                        v-model="input.CBMeteorismusAbdomen" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column">Peristaltik</div>
                                        <div class="columns is-multiline">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Normal"
                                                        label="Normal" v-model="input.CBNormalPeristaltik" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Meningkat"
                                                        label="Meningkat" v-model="input.CBMeningkatPeristaltik" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Menurun"
                                                        label="Menurun" v-model="input.CBMenurunPeristaltik" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ascites"
                                                        label="Ascites" v-model="input.CBAscitesPeristaltik" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Nyeri tekan lokasi" label="Nyeri tekan lokasi"
                                                        v-model="input.CBNyeriTekanLokasiPeristaltik" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input"
                                                        v-model="input.TBNyeriTekanLokasiPeristaltik" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Hepar : ">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBHeparPeristaltik" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Lien : ">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBLienPeristaltik" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>

                                        <div class="column"><b>Extremitas : </b></div>
                                        <div class="columns is-multiline">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Hangat"
                                                        label="Hangat" v-model="input.CBHangatExtremitas" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Dingin"
                                                        label="Dingin" v-model="input.CBDinginExtremitas" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Odema"
                                                        label="Odema" v-model="input.CBOdemaExtremitas" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input"
                                                        v-model="input.TBOdemaExtremitas" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                                        label="Lain-lain" v-model="input.CBLainlainExtremitas" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input"
                                                        v-model="input.TBLainlainExtremitas" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Asesmen Sindrom Geriatri" style="margin-bottom: 10px">
                            <div class="columns is-multiline" style="padding: 10px; padding-top: 0px">
                                <div class="columns is-multiline column">
                                    <div class="column is-12" style="font-weight: bold">1. PENAPISAN STATUS FUNGSIONAL
                                        (ACTIVITY DAILY
                                        LIVING BARTHEL INDEX)</div>
                                    <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                                        <table class="tg">
                                            <thead>
                                                <tr>
                                                    <th>Aspek</th>
                                                    <th>Sebelum MRS</th>
                                                    <th>Saat MRS</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(data, index) in ListPSF" :key="index">
                                                    <td>{{ data.caption }}</td>
                                                    <td
                                                        style="text-align:center !important;vertical-align:middle !important">
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input['TBsebelumMRS_' + index]" />
                                                        </VControl>
                                                    </td>
                                                    <td>
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input['TBsaatMRS_' + index]" />
                                                        </VControl>
                                                    </td>
                                                    <td v-if="index === 0" :rowspan="11" style="font-weight:bold">
                                                        Mandiri (20)<br>
                                                        Ketergantungan ringan (12-19)<br>
                                                        Ketergantungan sedang (9-11)<br>
                                                        Ketergantungan berat (5-8)<br>
                                                        Ketergantungan total (0-4)
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">2. PENAPISAN SINDROM DELIRIUM
                                        (CONFUSION ASSESSMENT
                                        METHOD)</div>
                                    <div class="column is-12 columns is-multiline"
                                        style="margin-left: 10px;margin-top:-5px">
                                        <div class="column is-3">1. Onset akut dan fluktuatif</div>
                                        <div class="column is-3 columns">
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBOnsetAkutdanFluktuatif" />
                                                </VControl>
                                            </div>
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBOnsetAkutdanFluktuatif" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column is-3">3. Pikiran tidak terorganisir</div>
                                        <div class="column is-3 columns">
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBPikiranTidakTerorganisir" />
                                                </VControl>
                                            </div>
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBPikiranTidakTerorganisir" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column is-3">2. Inatensi</div>
                                        <div class="column is-3 columns">
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBInatensi" />
                                                </VControl>
                                            </div>
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBInatensi" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column is-3">4. Pikiran tidak terorganisir</div>
                                        <div class="column is-3 columns">
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBPikiranTidakTerorganisir2" />
                                                </VControl>
                                            </div>
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBPikiranTidakTerorganisir2" />
                                                </VControl>
                                            </div>
                                        </div>

                                        <div class="column is-12 columns">
                                            <div class="column is-3">Delirium</div>
                                            <div class="column is-8">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya (poin 1 dan 2 plus salah satu dari 3 atau 4)"
                                                        v-model="input.CBDelirium" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">3. PENILAIAN STATUS NUTRISI
                                        (MINI NUTRITIONAL
                                        ASSESSMENT)</div>
                                    <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                                        <div class="columns is-multiline">
                                            <div class="column is-12">1. IMT (Kg/M<sup>2</sup>)</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) < 19" label="(0) < 19"
                                                            v-model="input.CBimt" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) 19-21" label="(1) 19-21"
                                                            v-model="input.CBimt" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(2) 21-23" label="(2) 21-23"
                                                            v-model="input.CBimt" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(3) >23" label="(3) >23"
                                                            v-model="input.CBimt" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">2. Lingkar lengan atas (Cm)</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) <21" label="(0) <21"
                                                            v-model="input.CBlingkarLenganAtas" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0,5) 21-22" label="(0,5) 21-22"
                                                            v-model="input.CBlingkarLenganAtas" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) >22" label="(1) >22"
                                                            v-model="input.CBlingkarLenganAtas" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">3. Lingkar betis (Cm)</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) ≤31" label="(0) ≤31"
                                                            v-model="input.CBlingkarBetis" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) >31" label="(1) >31"
                                                            v-model="input.CBlingkarBetis" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">4. BB selama 3 bulan terakhir</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) Kehilangan > 3kg"
                                                            label="(0) Kehilangan > 3kg"
                                                            v-model="input.CBbbSelama3bulan" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Tidak tahu" label="(1) Tidak tahu"
                                                            v-model="input.CBbbSelama3bulan" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(2) Kehilangan antara 1-3kg"
                                                            label="(2) Kehilangan antara 1-3kg"
                                                            v-model="input.CBbbSelama3bulan" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(3) Tidak kehilangan BB"
                                                            label="(3) Tidak kehilangan BB"
                                                            v-model="input.CBbbSelama3bulan" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">5. Hidup tidak tergantung (tidak di tempat
                                                perawatan/RS)</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) Ya" label="(0) Ya"
                                                            v-model="input.CBhidupTidakTergantung" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Tidak" label="(1) Tidak"
                                                            v-model="input.CBhidupTidakTergantung" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">6. Menggunakan lebih dari 3 jenis obat per hari
                                            </div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) Ya" label="(0) Ya"
                                                            v-model="input.CBmenggunakanLebihDari3JenisObat" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Tidak" label="(1) Tidak"
                                                            v-model="input.CBmenggunakanLebihDari3JenisObat" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">7. Mengalami stress psikologis atau penyakit akut
                                                dalam 3 bulan
                                                terakhir</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) Ya" label="(0) Ya"
                                                            v-model="input.CBmengalamiStressPsikologis" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Tidak" label="(1) Tidak"
                                                            v-model="input.CBmengalamiStressPsikologis" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">8. Mobilitas</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) Hanya terbaring/di atas kursi roda"
                                                            label="(0) Hanya terbaring/di atas kursi roda"
                                                            v-model="input.CBmobilitas" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Bisa bangkit dari tempat tidur tapi tidak keluar rumah"
                                                            label="(1) Bisa bangkit dari tempat tidur tapi tidak keluar rumah"
                                                            v-model="input.CBmobilitas" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(2) Bisa keluar rumah"
                                                            label="(2) Bisa keluar rumah" v-model="input.CBmobilitas" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">9. Masalah neuropsikologis</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) Demensia berat dan depresi"
                                                            label="(0) Demensia berat dan depresi"
                                                            v-model="input.CBmasalahNeuropsikologis" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Demensia ringan" label="(1) Demensia ringan"
                                                            v-model="input.CBmasalahNeuropsikologis" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(2) Tidak ada masalah psikologis"
                                                            label="(2) Tidak ada masalah psikologis"
                                                            v-model="input.CBmasalahNeuropsikologis" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">10. Nyeri tekan/luka kulit</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) Ya" label="(0) Ya"
                                                            v-model="input.CBnyeriTekanLukaKulit" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Tidak" label="(1) Tidak"
                                                            v-model="input.CBnyeriTekanLukaKulit" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">11. Jumlah daging yang dikonsumsi setiap hari
                                            </div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) 1x makan" label="(0) 1x makan"
                                                            v-model="input.CBjumlahDagingYangDikonsumsi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) 2x makan" label="(1) 2x makan"
                                                            v-model="input.CBjumlahDagingYangDikonsumsi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(2) 3x makan" label="(2) 3x makan"
                                                            v-model="input.CBjumlahDagingYangDikonsumsi" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">12. Asupan protein terpilih</div>
                                            <div class="column is-12" style="margin-left:10px">
                                                <div class="columns">
                                                    <div class="column is-6">
                                                        a. Minimal 1x penyajian produk susu olahan per hari
                                                    </div>
                                                    <div class="column is-3">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="(0) Tidak" label="(0) Tidak"
                                                                v-model="input.CBminimal1xPenyajian" />
                                                        </VControl>
                                                    </div>
                                                    <div class="column is-3">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="(1) Ya" label="(1) Ya"
                                                                v-model="input.CBminimal1xPenyajian" />
                                                        </VControl>
                                                    </div>
                                                </div>
                                                <div class="columns">
                                                    <div class="column is-6">
                                                        b. Dua atau lebih penyajian produk kacang-kacangan dan telur per
                                                        minggu
                                                    </div>
                                                    <div class="column is-3">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="(0) Tidak" label="(0) Tidak"
                                                                v-model="input.CBduaAtauLebihPenyajian" />
                                                        </VControl>
                                                    </div>
                                                    <div class="column is-3">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="(1) Ya" label="(1) Ya"
                                                                v-model="input.CBduaAtauLebihPenyajian" />
                                                        </VControl>
                                                    </div>
                                                </div>
                                                <div class="columns">
                                                    <div class="column is-6">
                                                        c. Daging, ikan, unggas tiap hari
                                                    </div>
                                                    <div class="column is-3">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="(0) Tidak" label="(0) Tidak"
                                                                v-model="input.CBdagingIkanUnggas" />
                                                        </VControl>
                                                    </div>
                                                    <div class="column is-3">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="(1) Ya" label="(1) Ya"
                                                                v-model="input.CBdagingIkanUnggas" />
                                                        </VControl>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="column is-12">13. Konsumsi 2 atau lebih penyajian sayur atau
                                                buah-buahan per hari
                                            </div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) Tidak" label="(0) Tidak"
                                                            v-model="input.CBKonsumsi2ataulebihPenyajianSayur" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Ya" label="(1) Ya"
                                                            v-model="input.CBKonsumsi2ataulebihPenyajianSayur" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">14. Asupan makanan dalam 3 bulan terakhir
                                                (kehilangan nafsu makan)
                                            </div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) Berat" label="(0) Berat"
                                                            v-model="input.CBAsupanMakananDalam3BulanTerakhir" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Sedang" label="(1) Sedang"
                                                            v-model="input.CBAsupanMakananDalam3BulanTerakhir" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(2) Ringan" label="(2) Ringan"
                                                            v-model="input.CBAsupanMakananDalam3BulanTerakhir" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">15. Jumlah cairan yang dikonsumsi per hari
                                            </div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) <3 cangkir" label="(0) <3 cangkir"
                                                            v-model="input.CBJumlahCairanYangDikonsumsi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0,5) 3-5 cangkir" label="(0,5) 3-5 cangkir"
                                                            v-model="input.CBJumlahCairanYangDikonsumsi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) >5 cangkir" label="(1) >5 cangkir"
                                                            v-model="input.CBJumlahCairanYangDikonsumsi" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">16. Pola makan
                                            </div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) Tidak bisa makan tanpa bantuan"
                                                            label="(0) Tidak bisa makan tanpa bantuan"
                                                            v-model="input.CBpolaMakan" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Makan sendiri dengan sedikit kesulitan"
                                                            label="(1) Makan sendiri dengan sedikit kesulitan"
                                                            v-model="input.CBpolaMakan" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(2) Makan sendiri tanpa kesulitan"
                                                            label="(2) Makan sendiri tanpa kesulitan"
                                                            v-model="input.CBpolaMakan" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">17. Apakah pasien merasakan memiliki masalah gizi
                                            </div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) Malntrisi" label="(0) Malntrisi"
                                                            v-model="input.CBapakahPasienMerasakanMasalahGizi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Tidak tahu/malnutrisi sedang"
                                                            label="(1) Tidak tahu/malnutrisi sedang"
                                                            v-model="input.CBapakahPasienMerasakanMasalahGizi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(2) Tidak ada masalah gizi"
                                                            label="(2) Tidak ada masalah gizi"
                                                            v-model="input.CBapakahPasienMerasakanMasalahGizi" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12">18. Penilaian pasien terhadap kesehatannya bila
                                                dibandingkan dengan
                                                kelompok usia yang sama
                                            </div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0) tidak baik" label="(0) tidak baik"
                                                            v-model="input.CBpenilaianPasienTerhadapKesehatannya" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(0,5) Tidak tahu" label="(0,5) Tidak tahu"
                                                            v-model="input.CBpenilaianPasienTerhadapKesehatannya" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(1) Sama baik" label="(1) Sama baik"
                                                            v-model="input.CBpenilaianPasienTerhadapKesehatannya" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="(2) Lebih baik" label="(2) Lebih baik"
                                                            v-model="input.CBpenilaianPasienTerhadapKesehatannya" />
                                                    </VControl>
                                                </div>
                                            </div>

                                            <div class="column is-12 columns is-multiline" style="margin-top: 15px">
                                                <div class="column is-4">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Normal (Skor Penapisan ≥24)"
                                                            label="Normal (Skor Penapisan ≥24)"
                                                            v-model="input.CBtotalSkorPenilaianStatusNutrisi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-4">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Berisiko Malnutrisi (Skor Pengkajian 17-23,5)  "
                                                            label="Berisiko Malnutrisi (Skor Pengkajian 17-23,5)"
                                                            v-model="input.CBtotalSkorPenilaianStatusNutrisi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-4">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Malnutrisi (Skor Pengkajian <17)"
                                                            label="Malnutrisi (Skor Pengkajian <17)"
                                                            v-model="input.CBtotalSkorPenilaianStatusNutrisi" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">4. PENAPISAN KOGNITIF (MI NI
                                        MENTAL STATE
                                        EXAMINATION)</div>
                                    <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                                        <div class="column is-12">
                                            <label style="font-weight:bold">1. ORIENTASI</label>
                                            <p>(5) Sekarang (hari),(tanggal),(bulan),(tahun) berapa,(musim) apa?</p>
                                            <p>(5) Sekarang kita berada di mana ? (jalan),(nomor
                                                rumah),(kota),(kabupaten),(propinsi)</p>
                                        </div>
                                        <div class="column is-12">
                                            <label style="font-weight:bold">2. REGISTRASI</label>
                                            <p>(3) Pasien diminta untuk mengulang tiga kata yang disebutkan oleh
                                                pemeriksa (bola, kursi,
                                                sepatu)</p>
                                            <div class="column is-12">
                                                <VField label="Jumlah Percobaan :">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBjumlahPercobaan" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <label style="font-weight:bold">3. ATENSI dan KALKULASI</label>
                                            <p>(5) Hitunglah berturut-turut selang 7 mulai dari 100 ke bawah. Berilah 1
                                                angka untuk tiap
                                                jawaban yang benar. Berhenti setelah 5 hitungan
                                                (93,86,79,72,65). Kemungkinan lain, ejalah kata “dunia” dari akhir ke
                                                awal (a-i-n-u-d)</p>
                                        </div>
                                        <div class="column is-12">
                                            <label style="font-weight:bold">4. MENGINGAT</label>
                                            <p>(3) Tanyalah kembali nama ke 3 benda yang telah disebutkan di atas.
                                                Berilah 1 angka untuk
                                                tiap jawaban yang benar.</p>
                                        </div>
                                        <div class="column is-12">
                                            <label style="font-weight:bold">5. BAHASA</label>
                                            <p>(2) Apakah nama benda-benda ini? Perlihatkan pensil dan arloji</p>
                                            <p>(1) Ulanglah kalimat berikut : “ Jika tidak, dan Atau Tapi ”.</p>
                                            <p>(3) Laksanakan 3 buah perintah ini : “ Peganglah selembar kertas dengan
                                                tangan kananmu,
                                                lipatlah kertas itu pada pertengahan dan letakkanlah di lantai”.</p>
                                            <p>(1) Bacalah dan laksanakan perintah berikut “PEJAMKAN MATA ANDA”</p>
                                            <p>(1) Tulislah sebuah kalimat</p>
                                            <p>(1) Tirulah gambar ini (di samping gambar tersebut)</p>
                                        </div>
                                        <div class="column is-12">
                                            <img src="/images/simrs/AAM_Geriatri.png">
                                        </div>
                                        <div class="column is-12 columns">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Normal (25 – 30)" label="Normal (25 – 30)"
                                                        v-model="input.CBSkorPenapisanKognitif" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Gangguan Kognitif ringan (MCI) (20 – 25)"
                                                        label="Gangguan Kognitif ringan (MCI) (20 – 25)"
                                                        v-model="input.CBSkorPenapisanKognitif" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Gangguan kognitif pasti (< 20)"
                                                        label="Gangguan kognitif pasti (< 20)"
                                                        v-model="input.CBSkorPenapisanKognitif" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Tidak dapat dievaluasi"
                                                        label="Tidak dapat dievaluasi"
                                                        v-model="input.CBSkorPenapisanKognitif" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">5. PENAPISAN DEPRESI(GERIATRIC
                                        DEPRESSION SCALE)
                                    </div>
                                    <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                                        <table class="tg">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="80%">Deskripsi</th>
                                                    <th width="7%">(0)</th>
                                                    <th width="7%">(1)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(data, index) in ListPenapisanDepresi" :key="index">
                                                    <td style="text-align:center">{{ index + 1 }}</td>
                                                    <td>{{ data.caption }}</td>
                                                    <td style="text-align:center">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.nilai0" :label="data.nilai0"
                                                                v-model="input['CBnilai0_' + index]" />
                                                        </VControl>
                                                    </td>
                                                    <td style="text-align:center">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.nilai1" :label="data.nilai1"
                                                                v-model="input['CBnilai1_' + index]" />
                                                        </VControl>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="column is-12 columns">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Normal (0-9)" label="Normal (0-9)"
                                                        v-model="input.CBnormalPenapisanDepresi" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Depresi Ringan (10 – 19)"
                                                        label="Depresi Ringan (10 – 19)"
                                                        v-model="input.CBdepresiRinganPenapisanDepresi" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Depresi berat (20 – 30)"
                                                        label="Depresi berat (20 – 30)"
                                                        v-model="input.CBdepresiBeratPenapisanDepresi" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Tidak dapat dievaluasi"
                                                        label="Tidak dapat dievaluasi"
                                                        v-model="input.CBtidakDapatDievaluasiPenapisanDepresi" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">6. PENAPISAN INKONTINENSIA
                                    </div>
                                    <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                                        <div class="column is-12" v-for="(data, index) in listPI" :key="index">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square :true-value="data.caption"
                                                    :label="data.caption" v-model="input['CBPI_' + index]" />
                                            </VControl>
                                        </div>
                                        <div class="column is-12 columns" style="font-weight:bold;margin-top:10px">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Tidak ada inkontinensia (0)"
                                                        label="Tidak ada inkontinensia (0)"
                                                        v-model="input.CBtotalSkorPI" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Inkontinensia ringan (1 – 2.5)"
                                                        label="Inkontinensia ringan (1 – 2.5)"
                                                        v-model="input.CBtotalSkorPI" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="I. sedang (4 – 6.5)" label="I. sedang (4 – 6.5)"
                                                        v-model="input.CBtotalSkorPI" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="I. berat (≥ 8)" label="I. berat (≥ 8)"
                                                        v-model="input.CBtotalSkorPI" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">7. PENAPISAN DEEP VEIN
                                        THROMBOSIS (WELLS SCORE
                                        SYSTEM)
                                    </div>
                                    <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                                        <div class="column is-12" v-for="(data, index) in listPDVT" :key="index">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square :true-value="data.caption"
                                                    :label="data.caption" v-model="input['CBPDVT_' + index]"
                                                    :value="data.value" @change="calculateTotalSkorPDVT" />
                                            </VControl>
                                        </div>
                                        <div class="column is-12">
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.TBtotalSkorPDVT"
                                                    disabled />
                                            </VControl>
                                        </div>
                                        <div class="column is-12 columns" style="font-weight:bold;margin-top:10px">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Risiko rendah (< 1)" label="Risiko rendah (< 1)"
                                                        v-model="input.CBtotalSkorPDVT" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Risiko sedang (1 – 2)" label="Risiko sedang (1 – 2)"
                                                        v-model="input.CBtotalSkorPDVT" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Risiko tinggi (> 3)" label="Risiko tinggi (> 3)"
                                                        v-model="input.CBtotalSkorPDVT" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">8. ULKUS DEKUBITUS
                                    </div>
                                    <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                                        <p>Tidak ada Ada (dilanjutkan dengan klarifikasi She)</p>
                                        <p>Stadium I : Eritema nonblanchable pada kulit yang masih utuh atau perubahan
                                            warna kulit yang
                                            hangat, edema, dan berindurasi pada pasien dengan
                                            kulit gelap</p>
                                        <p>Stadium II : Sudah terjadi kehilangan lapisan kulit epidermis dan/atau dermis
                                        </p>
                                        <p>Stadium III: Ulkus sudah berkembang ke jaringan lunak dan ke lapisan fasia
                                            dalam</p>
                                        <p>Stadium IV : Jaringan otot dan tulang sudah terlibat</p>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">9. PENAPISAN INSOMNIA (INSOMNIA
                                        SEVERITY INDEX)
                                    </div>
                                    <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                                        <div class="column is-12 columns is-multiline"
                                            v-for="(data, index) in listPenapisanInsomnia" :key="index">
                                            <div class="column is-12">{{ data.caption }}</div>
                                            <div class="column is-2">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square :true-value="data.cb0"
                                                        :label="data.cb0"
                                                        v-model="input['CB0PenapisanInsomnia_' + index]" />
                                                </VControl>
                                            </div>
                                            <div class="column is-2">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square :true-value="data.cb1"
                                                        :label="data.cb1"
                                                        v-model="input['CB1PenapisanInsomnia_' + index]" />
                                                </VControl>
                                            </div>
                                            <div class="column is-2">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square :true-value="data.cb2"
                                                        :label="data.cb2"
                                                        v-model="input['CB2PenapisanInsomnia_' + index]" />
                                                </VControl>
                                            </div>
                                            <div class="column is-2">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square :true-value="data.cb3"
                                                        :label="data.cb3"
                                                        v-model="input['CB3PenapisanInsomnia_' + index]" />
                                                </VControl>
                                            </div>
                                            <div class="column is-2">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square :true-value="data.cb4"
                                                        :label="data.cb4"
                                                        v-model="input['CB4PenapisanInsomnia_' + index]" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column is-12 columns" style="font-weight:bold;margin-top:10px">
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Tidak Insomnia (0-7)" label="Tidak Insomnia (0-7)"
                                                        v-model="input.CBSkorPenapisanInsomnia" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Borderline Insomnia (8-14))"
                                                        label="Borderline Insomnia (8-14))"
                                                        v-model="input.CBSkorPenapisanInsomnia" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Insomnia Sedang (15-21)"
                                                        label="Insomnia Sedang (15-21)"
                                                        v-model="input.CBSkorPenapisanInsomnia" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Insomnia Berat (22-28)"
                                                        label="Insomnia Berat (22-28)"
                                                        v-model="input.CBSkorPenapisanInsomnia" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">10. IDENTIFIKASI FALLS DAN
                                        RISIKO JATUH (SKALA
                                        MORSE)
                                    </div>
                                    <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                                        <div class="column is-12 columns">
                                            <div class="column is-3">1. Falls :</div>
                                            <div class="column is-9 columns">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="≥ 3 kali" label="≥ 3 kali"
                                                            v-model="input.CBFallsIFRJ" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="1-2 kali" label="1-2 kali"
                                                            v-model="input.CBFallsIFRJ" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="saat ini" label="saat ini"
                                                            v-model="input.CBFallsIFRJ" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="tidak pernah" label="tidak pernah"
                                                            v-model="input.CBFallsIFRJ" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12 columns">
                                            <div class="column is-3">2. Total skor skala Morse :</div>
                                            <div class="column is-9 columns">
                                                <div class="column is-4">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Risiko rendah (0-7)" label="Risiko rendah (0-7)"
                                                            v-model="input.CBTotalSkorMorseIFRJ" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-4">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Risiko tinggi (8-13)"
                                                            label="Risiko tinggi (8-13)"
                                                            v-model="input.CBTotalSkorMorseIFRJ" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-4">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Risiko sangat tinggi (≥14)"
                                                            label="Risiko sangat tinggi (≥14)"
                                                            v-model="input.CBTotalSkorMorseIFRJ" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">11. IDENTIFIKASI FRAILTY
                                    </div>
                                    <div class="column is-12 columns" style="margin-left: 10px;margin-top:-5px">
                                        <div class="column is-6">
                                            <div class="column">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Penurunan berat badan yang progresif (1)"
                                                        label="Penurunan berat badan yang progresif (1)"
                                                        v-model="input.CBPenurunanbbIF" />
                                                </VControl>
                                            </div>
                                            <div class="column">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Energi dan endurance yang lemah (1)"
                                                        label="Energi dan endurance yang lemah (1)"
                                                        v-model="input.CBEnergidanEnduranceIF" />
                                                </VControl>
                                            </div>
                                            <div class="column">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Kecepatan berjalan melambat (1)"
                                                        label="Kecepatan berjalan melambat (1)"
                                                        v-model="input.CBKecepatanBerjalanMelambatIF" />
                                                </VControl>
                                            </div>
                                            <div class="column">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Keletihan atau daya tahan menurun (1)"
                                                        label="Keletihan atau daya tahan menurun (1)"
                                                        v-model="input.CBKeletihanatauDayatahanMenurunIF" />
                                                </VControl>
                                            </div>
                                            <div class="column">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Tingkat aktivitas fisik yang rendah (1)"
                                                        label="Tingkat aktivitas fisik yang rendah (1)"
                                                        v-model="input.CBTingkatAktivitasFisikyangRendahIF" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column is-6 columns">
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Non frail (0)" label="Non frail (0)"
                                                        v-model="input.CBifSkor" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Pre frail (1-2)" label="Pre frail (1-2)"
                                                        v-model="input.CBifSkor" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Frailty (≥3)" label="Frailty (≥3)"
                                                        v-model="input.CBifSkor" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">12. IDENTIFIKASI FAILURE TO
                                        THRIVE
                                    </div>
                                    <div class="column is-12 columns" style="margin-left: 10px;margin-top:-5px">
                                        <div class="column is-6">
                                            <div class="column">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Penurunan berat badan >5% dari berat badan awal (1)"
                                                        label="Penurunan berat badan >5% dari berat badan awal (1)"
                                                        v-model="input.CBPenurunanbbIFTT" />
                                                </VControl>
                                            </div>
                                            <div class="column">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Penurunan nafsu makan (1)"
                                                        label="Penurunan nafsu makan (1)"
                                                        v-model="input.CBPenurunanNafsuIFTT" />
                                                </VControl>
                                            </div>
                                            <div class="column">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Malnutrisi (1)" label="Malnutrisi (1)"
                                                        v-model="input.CBMalnutrisiIFTT" />
                                                </VControl>
                                            </div>
                                            <div class="column">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Imobilitas (1)" label="Imobilitas (1)"
                                                        v-model="input.CBImobilitasIFTT" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column is-6 columns">
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Tidak (<4)" label="Tidak (<4)"
                                                        v-model="input.CBifttSkor" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="FailureTo Thrive (4)" label="FailureTo Thrive (4)"
                                                        v-model="input.CBifttSkor" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">13. IDENTIFIKASI RISIKO FRAKTUR
                                        (FRAX)
                                    </div>
                                    <div class="column is-12 columns is-multiline"
                                        style="margin-left: 10px;margin-top:-5px">
                                        <div class="columns column is-12">
                                            <div class="column is-4">1. Usia</div>
                                            <div class="column is-4">
                                                <VField addons>
                                                    <VControl>
                                                        <VInput type="text" class="input" v-model="input.TBUsiaIRF" />
                                                    </VControl>
                                                    <VControl class="field-addon-body">
                                                        <VButton static>Tahun</VButton>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">2. Jenis kelamin</div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Pria"
                                                        label="Pria" v-model="input.CBjenisKelaminIRF" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Wanita"
                                                        label="Wanita" v-model="input.CBjenisKelaminIRF" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">3. Berat badan</div>
                                            <div class="column is-4">
                                                <VField addons>
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBberatBadanIRF" />
                                                    </VControl>
                                                    <VControl class="field-addon-body">
                                                        <VButton static>Kg</VButton>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">4. Tinggi badan</div>
                                            <div class="column is-4">
                                                <VField addons>
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBtinggiBadanIRF" />
                                                    </VControl>
                                                    <VControl class="field-addon-body">
                                                        <VButton static>cm</VButton>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">5. Riwayat patah tulang</div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBriwayatPatahTulangIRF" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBriwayatPatahTulangIRF" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">6. Riwayat patah tulang femur pada orang tua</div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBriwayatPatahTulangFemurIRF" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBriwayatPatahTulangFemurIRF" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">7. Perokok</div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBperokokIRF" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBperokokIRF" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">8. Glukokortikoid</div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBglukokortikoidIRF" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBglukokortikoidIRF" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">9. Artritis rheumatoid</div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBartritisRheumatoidIRF" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBartritisRheumatoidIRF" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">10. Osteoporosis sekunder</div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBOsteoporosisSekunderIRF" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBOsteoporosisSekunderIRF" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="columns column is-12">
                                            <div class="column is-4">11. Alkohol 3 unit atau lebih per hari</div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBAlkohol3UnitIRF" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBAlkohol3UnitIRF" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <Fieldset :toggleable="true" legend="Nilai FRAX"
                                                style="margin-bottom: 10px">
                                                <div class="columns is-12">
                                                    <div class="column is-6">
                                                        <label style="font-weight: bold">Osteoporosis Mayor :</label>
                                                        <p>a. Risiko berat (≥20%)</p>
                                                        <p>b. Risiko sedang (10-20%)</p>
                                                        <p>c. Risiko ringan (&lt;10%)</p>
                                                    </div>
                                                    <div class="column is-6">
                                                        <label style="font-weight: bold">Hip fracture :</label>
                                                        <p>a. Risiko berat (≥3%)</p>
                                                        <p>b. Risiko sedang (1,5-3%)</p>
                                                        <p>c. Risiko ringan (&lt;1,5%)</p>
                                                    </div>
                                                </div>
                                            </Fieldset>
                                        </div>
                                    </div>

                                    <div class="column is-12" style="font-weight: bold">14. IMPAIRMENT LAINNYA
                                    </div>
                                    <div class="column is-12 columns is-multiline"
                                        style="margin-left: 10px;margin-top:-5px">
                                        <div class="column is-12 columns">
                                            <div class="column is-4">1. Impair of vision</div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBimpairOFvision" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBimpairOFvision" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column is-12 columns">
                                            <div class="column is-4">2. Impair of hearing</div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.CBimpairOFhearing" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.CBimpairOFhearing" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <VField label="Lain-lain">
                                                <VTextarea rows="2" v-model="input.TAlainlainIL"></VTextarea>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Hasil Pemeriksaan Penunjang">
                            <VField>
                                <VTextarea rows="2" v-model="input.TAhpp"></VTextarea>
                            </VField>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="DIAGNOSIS (ICD X)" style="margin-bottom: 10px">
                            <div class="column">
                                <VField addons>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.diagnosaIcd10" :suggestions="d_Diagnosa"
                                            @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder=" ICD 10 ..." class="mt-2" />
                                    </VControl>
                                </VField>
                            </div>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Rencana Kerja Dokter (Plan Of Care)"
                            style="margin-bottom: 10px">
                            <table class="tg">
                                <thead>
                                    <tr>
                                        <th>Daftar Masalah</th>
                                        <th>Rencana Intervensi</th>
                                        <th>Target<br>(Kondisi yang diharapkan dan waktu)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="input.TAdaftarMasalah"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="input.TArencanaIntervensi"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="input.TAtarget"></VTextarea>
                                        </VField>
                                    </td>
                                </tbody>
                            </table>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Instruksi" style="margin-bottom: 10px">
                            <VField>
                                <VTextarea rows="2" v-model="input.TAInstruksi"></VTextarea>
                            </VField>
                        </Fieldset>
                    </div>
                    <div class="columns column is-12">
                        <div class="column is-8"></div>
                        <div class="column is-4">
                            <VField label="Garut">
                                <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                            <div class="column" style="text-align:center;">
                                <h1>Tanda Tangan Dokter</h1>
                                <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" />
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.CBDokter" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" class="mt-2" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
            </div>
        </div>
    </div>
</template>