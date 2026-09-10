<style lang="scss"></style>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({
    title: 'Formulir Rujukan Pasien Ke Klinik DOTS Atau KTS/PDP - ' + import.meta.env.VITE_PROJECT,
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
const route = useRoute()
const pasien: any = ref({})
// const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const dataTTD: any = ref([])
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
    airway: [],
    disability: []

})

const COLLECTION: any = ref('FormulirRujukanPasienKlinikDOTS') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    // kebjamKedatangan: new Date(),
    // kebjamAsesmenAwal: new Date(),
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
function calculateAge(birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
    }
    if (!response || !Array.isArray(response) || response.length === 0) {
        let dataRegis = H.setObjectRegistrasi(pasien.value.registrasi)
        let dataPasien = H.setObjectPasien(pasien.value)
        input.value.TBNoRegisORHIV = dataRegis.noregistrasi
        input.value.TBNamaPasien = dataPasien.namapasien
        input.value.TBNamaPasienFormulir = dataPasien.namapasien
        input.value.TBUsiaPasien = calculateAge(dataPasien.tgllahir)
        input.value.TBUsiaPasienFormulir = calculateAge(dataPasien.tgllahir)
        input.value.TAAlamatPasien = dataPasien.alamatlengkap
        input.value.TAAlamatPasienFormulir = dataPasien.alamatlengkap
        input.value.TBNomorTeleponPasien = dataPasien.nohp
    } else { }
    H.tandaTangan().set("TTDKlien", dataTTD.value.TTDKlien)
    H.tandaTangan().set("TTDInstasiUnit", dataTTD.value.TTDInstasiUnit)
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    object.nocm = pasien.value.nocm

    object['TTDKlien'] = H.tandaTangan().get("TTDKlien");
    object['TTDInstasiUnit'] = H.tandaTangan().get("TTDInstasiUnit");    

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    // console.log(json)

    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            loadRiwayat()
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
// const fetchDokter = async (filter: any) => {
//     await useApi().get(
//         `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//     ).then((response) => {
//         d_Dokter.value = response
//     })
// }
// const fetchRuangan = async (filter: any) => {
//     await useApi().get(
//         `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`
//     ).then((response) => {
//         d_Ruangan.value = response
//     })
// }
const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
        if (response != null || response != undefined) {
            // input.value.TBSBeratBadan = response.beratBadan
            // input.value.TBSTinggiBadan = response.tinggiBadan
            // input.value.IMT = response.IMT
            // input.value.lingkarPerut = response.lingkarPerut
            // input.value.TBSNadi = response.nadi
            // input.value.TBSSuhu = response.suhu
            // input.value.TBSTekananDarah = response.tekananDarah
            // input.value.TBSPernafasan = response.pernapasan
            // input.value.TBSSaO2 = response.SPO2
        }
    })
}

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
</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Formulir Rujukan Pasien Ke Klinik DOTS Atau KTS/PDP</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="columns is-multiline column is-12">
                    <div class="column is-6">
                        <p style="font-weight: lighter;">No. Registrasi TB atau HIV : </p>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBNoRegisORHIV" />
                        </VControl>
                    </div>
                    <div class="column is-6">
                        <VField label="Tanggal Rujukan">
                            <VDatePicker v-model="input.DTanggalRujukan" mode="date" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline column is-12">
                    <div class="column is-6">
                        <VField label="Institusi Pengiriman">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBInstitusiPengiriman" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Institusi Yang Dituju">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBInstitusiYangDituju" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Alamat">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBAlamatIP" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Alamat">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBAlamatIYD" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="No. Telp">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNoTLP_IP" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="No. Telp">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNoTLP_IYD" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12" style="font-weight: bold;">
                        Kepada Sejawat yang terhormat,<br>
                        Bersama ini kami sampaikan pasien/klien tersebut dibawah ini :
                    </div>
                    <div class="column is-4">
                        <table width="100%">
                            <td style="width: 30%;">Nama :</td>
                            <td style="width: 70%;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                                </VControl>
                            </td>
                        </table>
                    </div>
                    <div class="column is-4">
                        <table width="100%">
                            <td style="width: 30%;">Usia :</td>
                            <td style="width: 70%;">
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBUsiaPasien" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Tahun</VButton>
                                    </VControl>
                                </VField>
                            </td>
                        </table>
                    </div>
                    <div class="column is-4">
                        <table width="100%">
                            <td style="width: 30%;">Jenis Kelamin :</td>
                            <td style="width: 70%;">
                                <div class="columns column">
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Laki-laki"
                                                label="Laki-laki" v-model="input.CBJenisKelaminPasien" />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Perempuan"
                                                label="Perempuan" v-model="input.CBJenisKelaminPasien" />
                                        </VControl>
                                    </div>
                                </div>
                            </td>
                        </table>
                    </div>
                    <div class="column is-4">
                        <table width="100%">
                            <td style="width: 30%;">Alamat :</td>
                            <td style="width: 70%;">
                                <VField>
                                    <VTextarea rows="2" v-model="input.TAAlamatPasien"></VTextarea>
                                </VField>
                            </td>
                        </table>
                    </div>
                    <div class="column is-4">
                        <table width="100%">
                            <td style="width: 30%;">No. Telp :</td>
                            <td style="width: 70%;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNomorTeleponPasien" />
                                </VControl>
                            </td>
                        </table>
                    </div>
                    <div class="column is-12" style="font-weight: bold;">
                        Untuk dilakukan :
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Konseling HIV"
                                label="Konseling HIV" v-model="input.CBKonselingHIV" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Diagnosis TB" label="Diagnosis TB"
                                v-model="input.CBDiagnosisTB" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Tes HIV" label="Tes HIV"
                                v-model="input.CBTesHIV" />
                        </VControl>
                        <div class="column columns">
                            <div class="column is-6">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tes HIV Belum"
                                        label="Pasien belum menandatangani pernyataan persetujuan* -terlampir"
                                        v-model="input.CBTesHIVTandaTangan" />
                                </VControl>
                            </div>
                            <div class="column is-6">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tes HIV Sudah"
                                        label="Pasien telah menandatangani pernyataan persetujuan* -terlampir"
                                        v-model="input.CBTesHIVTandaTangan" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Pengobatan TB"
                                label="Pengobatan TB" v-model="input.CBPengobatanTB" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Perawatan dan Pengobatan HIV"
                                label="Perawatan dan Pengobatan HIV" v-model="input.CBPerawatanDanPengobatanHIV" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                                v-model="input.CBLainnyaUntukDilakukan" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                            <VInput type="text" class="input" v-model="input.TBLainnyaUntukDilakukan" />
                        </VControl>
                    </div>
                    <div class="column is-6">
                        Dengan bahan pertimbangan kondisi pasien/klien saat ini :<br>
                        <VField label="Staus HIV Tanggal">
                            <VDatePicker v-model="input.DStatusHIV" mode="date" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Hasil Pos" label="Hasil Pos"
                                v-model="input.CBHasilPos" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Neg*" label="Neg*"
                                v-model="input.CBNeg" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Kotrimoksasol"
                                label="Kotrimoksasol" v-model="input.CBKotrimoksasol" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="ARV" label="ARV"
                                v-model="input.CBARV" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square
                                true-value="Keadaan lain yang perlu perhatian/catatan Klinis"
                                label="Keadaan lain yang perlu perhatian/catatan Klinis"
                                v-model="input.CBKeadaanLain" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Dalam pengobatan TB, OAT kat"
                                label="Dalam pengobatan TB, OAT kat" v-model="input.CBDalamPengobatan" />
                        </VControl>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBDalamPengobatan" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square
                                true-value="Suspek TB yang masih dalam proses diagnosis"
                                label="Suspek TB yang masih dalam proses diagnosis" v-model="input.CBSuspekTB" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Selesai pengobatan TB"
                                label="Selesai pengobatan TB" v-model="input.CBSelesaiPengobatanTB" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Ada faktor risiko HIV"
                                label="Ada faktor risiko HIV" v-model="input.CBAdaFaktorRisikoHIV" />
                        </VControl>
                    </div>
                </div>

                <hr style="border-top: 1px solid black;">

                <div class="column is-12" style="font-weight: bold;">
                    Mohon umpan balik saudara dengan menggunakan formulir di bawah.<br>
                    Bila memerlukan penjelasan lebih lanjut silahkan hubungi kami pada alamat di atas.<br>
                    Terimakasih atas kerjasama yang diberikan
                </div>
                <div class="column columns">
                    <div class="column is-8">
                        *coret yang tidak perlu
                    </div>
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
                            <h1>Hormat Kami</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBHormatKami" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px solid black;">
                <div class="column is-12">
                    Izin Klien untuk pemberitahuan hasil tes kepada petugas TB
                </div>
                <hr style="border-top: 1px solid black;">

                <div class="column is-12 columns is-multiline">
                    <div class="column is-12">
                        <p>Saya yang bertandatangan di bawah ini,</p>
                    </div>
                    <div class="column is-4">
                        <VField label="Nama :">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNamaPasien2" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Jenis Kelamin :">
                            <div class="columns column is-12">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Laki-laki"
                                            label="Laki-laki" v-model="input.CBJenisKelaminPasien2" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Perempuan"
                                            label="Perempuan" v-model="input.CBJenisKelaminPasien2" />
                                    </VControl>
                                </div>
                            </div>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Alamat :">
                            <VTextarea rows="2" v-model="input.TAAlamatPasien2"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-12 columns is-multiline">
                        <div class="column is-12">
                            Menyatakan
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Memberikan izin"
                                    label="Memberikan izin" v-model="input.CBMemberikanIzinHasilTes" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Tidak memberikan izin"
                                    label="Tidak memberikan izin" v-model="input.CBMemberikanIzinHasilTes" />
                            </VControl>
                        </div>
                        <div class="column is-12">
                            konselor untuk menyampaikan informasi hasil tes HIV kepada petugas TB yang merujuk
                        </div>
                    </div>
                </div>

                <div class="column is-12 columns">
                    <div class="column is-8">
                        *Coret yang tidak dipilih
                    </div>
                    <div class="column is-4">
                        <div class="column" style="text-align:center;">
                            <h1>Nama dan tanda tangan klien</h1>
                            <TandaTangan :elemenID="'TTDKlien'" :width="'150'" :height="'150'" class="dek" />
                            <VControl style="margin-top: 10px;">
                                <VInput type="text" class="input" v-model="input.TBNamaKlien" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed black;">

                <div class="column is-12" style="text-align: center;">
                    FORMULIR JAWABAN RUJUKAN DARI KLINIK DOTS ATAU KLINIK KTS/PDP*<br>
                    (Untuk diisi dan dikembalikan ke Unit Pengiriman)
                </div>

                <div class="column is-12 columns is-multiline">
                    <div class="column is-12">
                        Kepada sejawat yang terhormat,<br>
                        Kami sampaikan bahwa, klien/pasien :
                    </div>
                    <div class="column is-6">
                        <table width="100%">
                            <td style="width: 30%;">Nama :</td>
                            <td style="width: 70%;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNamaPasienFormulir" />
                                </VControl>
                            </td>
                        </table>
                    </div>
                    <div class="column is-6">
                        <table width="100%">
                            <td style="width: 30%;">Usia :</td>
                            <td style="width: 70%;">
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBUsiaPasienFormulir" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Tahun</VButton>
                                    </VControl>
                                </VField>
                            </td>
                        </table>
                    </div>
                    <div class="column is-6">
                        <table width="100%">
                            <td style="width: 30%;">Jenis Kelamin :</td>
                            <td style="width: 70%;">
                                <div class="columns column">
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Laki-laki"
                                                label="Laki-laki" v-model="input.CBJenisKelaminPasienFormulir" />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Perempuan"
                                                label="Perempuan" v-model="input.CBJenisKelaminPasienFormulir" />
                                        </VControl>
                                    </div>
                                </div>
                            </td>
                        </table>
                    </div>
                    <div class="column is-6">
                        <table width="100%">
                            <td style="width: 30%;">Alamat :</td>
                            <td style="width: 70%;">
                                <VField>
                                    <VTextarea rows="2" v-model="input.TAAlamatPasienFormulir"></VTextarea>
                                </VField>
                            </td>
                        </table>
                    </div>

                    <div class="column is-12 columns is-multiline">
                        <div class="column is-12">Dengan hasil :</div>
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Konseling pra tes"
                                    label="Konseling pra tes" v-model="input.CBKonselingPraTes" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VDatePicker v-model="input.DKonselingPraTes" mode="date" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-1"></div>
                        <div class="column is-4 columns is-multiline">
                            <div class="column is-12">
                                Hasil
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="R" label="R"
                                        v-model="input.CB_r" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="NR" label="NR"
                                        v-model="input.CB_nr" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="I" label="I"
                                        v-model="input.CB_i" />
                                </VControl>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 columns is-multiline">
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Tes HIV" label="Tes HIV"
                                    v-model="input.CBTesHIVFormulir" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VDatePicker v-model="input.DTesHIVFormulir" mode="date" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-1"></div>
                        <div class="column is-4 columns is-multiline">
                            <div class="column is-12">BTA :</div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Pos" label="Pos"
                                        v-model="input.CB_pos" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Neg" label="Neg"
                                        v-model="input.CB_neg" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="EP" label="EP"
                                        v-model="input.CB_ep" />
                                </VControl>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 columns is-multiline">
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Konseling pos test"
                                    label="Konseling pos test" v-model="input.CBKonselingPosTestFormulir" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VDatePicker v-model="input.DKonselingPosTestFormulir" mode="date" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-1"></div>
                        <div class="column is-4"></div>
                    </div>

                    <div class="column is-12 columns is-multiline">
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Diagnosis TB"
                                    label="Diagnosis TB" v-model="input.CBDiagnosisTBFormulir" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VDatePicker v-model="input.DDiagnosisTBFormulir" mode="date" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-1"></div>
                        <div class="column is-4"></div>
                    </div>

                    <div class="column is-12 columns is-multiline">
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                                    v-model="input.CBLainnyaFormulir" />
                            </VControl>
                            <VControl style="margin-top: 5px">
                                <VInput type="text" class="input" v-model="input.TBLainnyaFormulir" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VDatePicker v-model="input.DLainnyaFormulir" mode="date" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-1"></div>
                        <div class="column is-4"></div>
                    </div>

                    <div class="column is-12 columns">
                        <div class="column is-4">Telah dilakukan :</div>
                        <div class="column is-4" style="text-align: center;"> Dimulai</div>
                        <div class="column is-4"></div>
                    </div>

                    <div class="column is-12 columns is-multiline">
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Pengobatan TB"
                                    label="Pengobatan TB" v-model="input.CBPengobatanTBFormulir" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VDatePicker v-model="input.DPengobatanTBFormulir" mode="date" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-1"></div>
                        <div class="column is-4">
                            <VField label="Panduan OAT">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBPanduanOAT" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                    <div class="column is-12 columns is-multiline">
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square
                                    true-value="Pengobatan Pencegahan Kotrimoksasol"
                                    label="Pengobatan Pencegahan Kotrimoksasol"
                                    v-model="input.CBPengobatanPencegahanKotriFormulir" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VDatePicker v-model="input.DPengobatanPencegahanKotriFormulir" mode="date" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-1"></div>
                        <div class="column is-4">
                            <VField label="Dosis">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBDosisFormulir" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                    <div class="column is-12 columns is-multiline">
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Terapi ARV" label="Terapi ARV"
                                    v-model="input.CBTerapiARV" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VDatePicker v-model="input.DTerapiARV" mode="date" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-1"></div>
                        <div class="column is-4">
                            <VField label="Panduan ARV">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBPanduanARVFormulir" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                    <div class="column is-12 columns is-multiline">
                        <div class="column is-8"></div>
                        <div class="column is-4">
                            <VField label="Garut">
                                <VDatePicker v-model="input.DTttdFormulir" mode="datetime" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                            <div class="column is-12" style="text-align:center;">
                                <h1>Hormat Kami</h1>
                                <TandaTangan :elemenID="'TTDInstasiUnit'" :width="'150'" :height="'150'" class="dek" />
                                <VControl style="margin-top: 10px;">
                                    <VInput type="text" class="input" v-model="input.TBInstansiUnit" />
                                </VControl>
                            </div>
                            <div class="column is-12">
                                <p style="text-align: center;">Instansi dan Unit :</p>
                                <VField label="Alamat :">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBAlamat" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- form baru -->

            </div>
        </div>

    </div>
</template>