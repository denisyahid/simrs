<style lang="scss">
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import Checkbox from 'primevue/checkbox';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({
    title: 'Medical Report - ' + import.meta.env.VITE_PROJECT,
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
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const d_Diagnosa: any = ref([])
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
const COLLECTION: any = ref('MedicalReport') //table mongodb
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
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    } else {
        let dataRegis = H.setObjectRegistrasi(pasien.value.registrasi)
        let dataPasien = H.setObjectPasien(pasien.value)
        input.value.DDDokter = dataRegis.dokter
        input.value.DTanggalRegis = dataRegis.tglregistrasi
        input.value.TBNamaPasien = dataPasien.namapasien
        input.value.DTanggalLahir = dataPasien.tgllahir
        input.value.TBJenisKelaminPasien = dataPasien.jeniskelamin
        input.value.TAAlamatPasien = dataPasien.alamatlengkap
        input.value.TBNomorTeleponPasien = dataPasien.notelepon ? dataPasien.notelepon : dataPasien.nohp;
        const response_NS = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + "&field=tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn")
        if (response_NS != null) {
            input.value.TBeGCS = response_NS.gcse
            input.value.TBvGCS = response_NS.gcsv
            input.value.TBmGCS = response_NS.gcsm
            input.value.TBSTekananDarah = response_NS.tekananDarahObgyn
            input.value.TBSNadi = response_NS.nadiObgyn
            input.value.TBSPernafasan = response_NS.nafasObgyn
            input.value.TBSSuhu = response_NS.celciusObgyn
            input.value.TBSSaO2 = response_NS.sao2Obgyn
            input.value.TADiagnosis = response_NS.TADiagnosa
        } else if (props.registrasi.namaruangan.toUpperCase().indexOf('IGD') > -1) {
            setAutoFill();
        }
    }
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': route.name,
        'name_form': 'Medical Report',
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    console.log(json)

    isLoading.value = true
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
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
    // NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    // console.log(norec_emr)
}
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`
    ).then((response) => {
        d_Dokter.value = response
    })
}
const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}
function convertText(e) {
    switch (e) {
        case 1:
            return 'Baik'
            break;
        case 2:
            return 'Sedang'
            break;
        case 3:
            return 'Buruk'
            break;
        default:
            break;
    }
}
const setAutoFill = async () => {
    let field = 'TADiagnosis,TArpp,perlukontrol,riwayatkeluar,statuskeluar,TAKeluhanUtama,TARiwayatPenyakitDahulu,TARiwayatPenggunaanObat,TARiwayatVaksin,TARPS,CBisiMOI,TAMOI,isalergi,alergi_tidak_diketahui,CBAlergiObat,TBAlergiObat,CBAlergiMakanan,TBAlergiMakanan,CBAlergiLainnya,TBAlergiLainnya,TAKepalaSG,TBAnemisMata,TBIkterusMata,TBRefleksPupilMata,TBOedemaPalpebraeMata,TBTonsilTHT,TBPharingTHT,TBTelingaTHT,TBHidungTHT,TBBibirTHT,TBLainnyaTHT,TBJVPLeher,TBPembesaranKelenjarLeher,CBKakuKudukLeher,CBSimetrisThoraks,CBAsimetrisThoraks,TBSimetrisORAsimetrisThoraks,TBRetraksiThoraks,TBS1S2Cor,CBRegulerCor,CBIregulerCor,TBMurmurCor,TBLainLainCor,TBRonchiPulmo,TBWheezingPulmo,TBVesikulerPulmo,TBLainnyaPulmo,CBSouffleAbdomen,CBDistensiAbdomen,CBMeteorismusAbdomen,CBNormalPeristaltik,CBMeningkatPeristaltik,CBMenurunPeristaltik,CBAscitesPeristaltik,TBNyeriTekanLokasiPeristaltik,TBHeparPeristaltik,TBLienPeristaltik,CBHangatExtremitas,CBDinginExtremitas,TBOdemaExtremitas,TBLainlainExtremitas,TBLainlainSG,keadaanumum,TBeGCS,TBvGCS,TBmGCS,TBcelciusTTV,TBRespirasiTTV,TBberatBadanTTV,TBNadiTTV,TBtekananDarahTTV,TBnsao2TTV,TBtinggiBadanTTV'
    let collection = 'AsesmenAwalMedisGawatDarurat'
    await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${collection}&field=${field}`).then((response) => {
        let text = ''
        text += 'Tanda-tanda Vital \n'
        text += response.keadaanumum ? `Keadaan Umum : ${convertText(response.keadaanumum)}\n` : ''
        text += response.TBcelciusTTV ? `Suhu : ${response.TBcelciusTTV} °C\n` : ''
        text += response.TBNadiTTV ? `Nadi : ${response.TBNadiTTV} x/mnt\n` : ''
        text += response.TBRespirasiTTV ? `Pernafasan : ${response.TBRespirasiTTV} x/mnt\n` : ''
        text += response.TBtekananDarahTTV ? `Tekanan Darah : ${response.TBtekananDarahTTV} mmHg\n` : ''
        text += response.TBnsao2TTV ? `SPO2 : ${response.TBnsao2TTV} %\n` : ''
        text += response.TBeGCS ? `GCS : E ${response.TBeGCS} V ${response.TBvGCS ? response.TBvGCS : ''} M ${response.TBmGCS ? response.TBmGCS : ''} \n` : ''

        text += '\nStatus Generalis \n'
        text += response.TAKepalaSG ? `Kepala : ${response.TAKepalaSG}\n` : 'Kepala : -\n'
        text += 'Mata \n'
        text += response.TBAnemisMata ? `Anemis : ${response.TBAnemisMata}\n` : 'Anemis : -'
        text += response.TBIkterusMata ? `Ikterus : ${response.TBIkterusMata}\n` : 'Ikterus : -'
        text += response.TBRefleksPupilMata ? `Refleks Pupil : ${response.TBRefleksPupilMata}\n` : 'Refleks Pupil : -'
        text += response.TBOedemaPalpebraeMata ? `Oedema Palpebrae : ${response.TBOedemaPalpebraeMata}\n` : 'Oedema Palpebrae : -'
        text += 'THT \n'
        text += response.TBTonsilTHT ? `Tonsil : ${response.TBTonsilTHT}\n` : 'Tonsil : -\n'
        text += response.TBPharingTHT ? `Pharing : ${response.TBPharingTHT}\n` : 'Pharing : -\n'
        text += response.TBTelingaTHT ? `Telinga : ${response.TBTelingaTHT}\n` : 'Telinga : -\n'
        text += response.TBHidungTHT ? `Hidung : ${response.TBHidungTHT}\n` : 'Hidung : -\n'
        text += response.TBBibirTHT ? `Bibir : ${response.TBBibirTHT}\n` : 'Bibir : -\n'
        text += response.TBLainnyaTHT ? `Lainnya : ${response.TBLainnyaTHT}\n` : 'Lainnya : -\n'
        text += 'Leher \n'
        text += response.TBJVPLeher ? `JVP : ${response.TBJVPLeher}\n` : 'JVP : -\n'
        text += response.TBPembesaranKelenjarLeher ? `Pembesaran Kelenjar : ${response.TBPembesaranKelenjarLeher}\n` : 'Pharing : -\n'
        text += response.CBKakuKudukLeher ? `Kaku Duduk : Ya\n` : 'Kaku Duduk : Tidak\n'
        text += `Thoraks : ${response.CBSimetrisThoraks ? response.CBSimetrisThoraks : (response.CBAsimetrisThoraks ? response.CBAsimetrisThoraks : '')}, ${response.TBSimetrisORAsimetrisThoraks ? response.TBSimetrisORAsimetrisThoraks : ''} \n`
        text += response.TBRetraksiThoraks ? `Retraksi : ${response.TBRetraksiThoraks}\n` : 'Retraksi : -\n'
        text += 'Cor \n'
        text += response.TBS1S2Cor ? `S1, S2 : ${response.TBS1S2Cor}, ${response.CBRegulerCor ? response.CBRegulerCor : (response.CBIregulerCor ? response.CBIregulerCor : '')} \n` : `S1, S2 : -, ${(response.CBIregulerCor ? response.CBIregulerCor : '')}\n`
        text += response.TBMurmurCor ? `Murmur : ${response.TBMurmurCor}\n` : 'Murmur : -\n'
        text += response.TBLainLainCor ? `Lain-lain : ${response.TBLainLainCor}\n` : 'Lain-lain : -\n'
        text += 'Pulmo \n'
        text += response.TBRonchiPulmo ? `Ronchi : ${response.TBRonchiPulmo}\n` : 'Ronchi : -\n'
        text += response.TBWheezingPulmo ? `Wheezing : ${response.TBWheezingPulmo}\n` : 'Wheezing : -\n'
        text += response.TBVesikulerPulmo ? `Vesikuler : ${response.TBVesikulerPulmo}\n` : 'Vesikuler : -\n'
        text += response.TBLainnyaPulmo ? `Lainnya : ${response.TBLainnyaPulmo}\n` : 'Lainnya : -\n'
        text += `Abdomen : ${response.CBSouffleAbdomen ? response.CBSouffleAbdomen + ',' : ''}${response.CBDistensiAbdomen ? response.CBDistensiAbdomen + ',' : ''}${response.CBMeteorismusAbdomen ? response.CBMeteorismusAbdomen : ''} \n`
        text += `Peristaltik : ${response.CBNormalPeristaltik ? response.CBNormalPeristaltik + ',' : ''}${response.CBMeningkatPeristaltik ? response.CBMeningkatPeristaltik + ',' : ''}${response.CBMenurunPeristaltik ? response.CBMenurunPeristaltik + ',' : ''}${response.CBAscitesPeristaltik ? response.CBAscitesPeristaltik : ''} \n`
        text += response.TBNyeriTekanLokasiPeristaltik ? `Nyeri tekan lokasi : ${response.TBNyeriTekanLokasiPeristaltik}\n` : 'Nyeri tekan lokasi : -\n'
        text += response.TBHeparPeristaltik ? `Hepar : ${response.TBHeparPeristaltik}\n` : 'Hepar : -\n'
        text += response.TBLienPeristaltik ? `Lien : ${response.TBLienPeristaltik}\n` : 'Lien : -\n'
        text += `Extrimitas : ${response.CBHangatExtremitas ? response.CBHangatExtremitas + ',' : ''}${response.CBDinginExtremitas ? response.CBDinginExtremitas : ''} \n`
        text += response.TBOdemaExtremitas ? `Odema : ${response.TBOdemaExtremitas}\n` : 'Odema : -\n'
        text += response.TBLainlainExtremitas ? `Lain-lain : ${response.TBLainlainExtremitas}\n` : 'Lain-lain : -\n'
        text += `Lain-lain : ${response.TBLainlainSG ? response.TBLainlainSG : ''}`

        input.value.TAPhysicalExamination = text
        input.value.TBeGCS = response.TBeGCS
        input.value.TBvGCS = response.TBvGCS
        input.value.TBmGCS = response.TBmGCS
        input.value.TBStekananDarah = response.TBtekananDarahTTV
        input.value.TBSNadi = response.TBNadiTTV
        input.value.TBSPernafasan = response.TBRespirasiTTV
        input.value.TBSSuhu = response.TBcelciusTTV
        input.value.TAOtherExamination = response.TArpp
        input.value.TADiagnosis = response.TADiagnosis
        input.value.TAHistoriPasien = response.TAKeluhanUtama
        input.value.TAPassMedicalHistory = response.TARiwayatPenyakitDahulu
        input.value.TAAllergyHistory = (response.isalergi == 'YA' ? (response.CBAlergiObat == "Obat" ? response.TBAlergiObat : '') + ' ' + (response.CBAlergiMakanan == "Makanan" ? response.TBAlergiMakanan : '') + ' ' + (response.CBAlergiLainnya == "Lainnya" ? response.TBAlergiLainnya : '') + '\n' : (response.alergi_tidak_diketahui ? response.alergi_tidak_diketahui : 'Tidak ada \n'))

    }).catch((error) => {
        H.alert('error', error)
    });
}
// const fetchDiagnosa = async (filter: any) => {
//     await useApi().get(
//         `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`
//     ).then((response) => {
//         d_Diagnosa.value = response
//     })
// }
const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
        if (response != null || response != undefined) {
            // input.value.TBSBeratBadan = response.beratBadan
            // input.value.TBSTinggiBadan = response.tinggiBadan
            // input.value.IMT = response.IMT
            // input.value.lingkarPerut = response.lingkarPerut
            input.value.TBSNadi = response.nadi
            input.value.TBSSuhu = response.suhu
            input.value.TBSTekananDarah = response.tekananDarah
            input.value.TBSPernafasan = response.pernapasan
            input.value.TBSSaO2 = response.SPO2
        }
    })
}
const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
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


getDataExist()
fetchPasien()
</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Medical Report</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :ID="input.id"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"
                                :isHideCetakWNA="false"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="columns is-multiline column is-12">
                    <div class="column is-4 is-multiline columns">
                        <div class="column is-12">
                            <VField label="Name">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Date of birth">
                                <VDatePicker v-model="input.DTanggalLahir" mode="date" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Address">
                                <VTextarea rows="2" v-model="input.TAAlamatPasien"></VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-4 is-multiline columns">
                        <div class="column is-12">
                            <VField label="Nationality">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBKebangsaanPasien" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Sex">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBJenisKelaminPasien" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Doctor's Name">
                                <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VField>
                        </div>
                    </div>
                    <div class="column is-4 is-multiline columns">
                        <div class="column is-12">
                            <VField label="Phone">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNomorTeleponPasien" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Date of Administration">
                                <VDatePicker v-model="input.DTanggalRegis" mode="datetime" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Date of Discharge">
                                <VDatePicker v-model="input.DTanggalKasir" mode="datetime" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline column is-12">
                    <div class="column is-6">
                        <VField label="Patient History">
                            <VTextarea rows="2" v-model="input.TAHistoriPasien"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Pass Medical History">
                            <VTextarea rows="2" v-model="input.TAPassMedicalHistory"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField label="Allergy History">
                            <VTextarea rows="2" v-model="input.TAAllergyHistory"></VTextarea>
                        </VField>
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
                    <div class="column is-3">
                        <VField label="Blood Pressure">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBSTekananDarah" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>mmHg</VButton>
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Pulse Rate">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBSNadi" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/menit</VButton>
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Respiratory Rate">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBSPernafasan" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/menit</VButton>
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Temperature">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBSSuhu" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>°C</VButton>
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="SPO2">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBSSaO2" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>%</VButton>
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Pregnant Woman"
                                label="For Pregnant Woman" v-model="input.CBPregnantWoman" />
                        </VControl>
                    </div>
                    <div class="column is-12" v-if="input.CBPregnantWoman === 'Pregnant Woman'">
                        <table width="100%">
                            <tr>
                                <td>
                                    <div class="columns is-multiline column is-12">
                                        <div class="column is-3" style="margin-top: 10px;">
                                            <span>Period of Pregnancy :</span>
                                        </div>
                                        <div class="column is-8">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.PeriodofPregnancy" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="columns is-multiline column is-12">
                                        <div class="column is-3" style="margin-top: 10px;">
                                            <span>Expected date of Delivery :</span>
                                        </div>
                                        <div class="column is-8">
                                            <VField>
                                                <VDatePicker v-model="input.ExpecteddateofDelivery" mode="datetime" trim-weeks>
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" v-on="inputEvents" />
                                                        </VControl>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="columns is-multiline column is-12">
                                        <div class="column is-3" style="margin-top: 10px;">
                                            <span>Fitness for the trip :</span>
                                        </div>
                                        <div class="column is-8">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.Fitnessforthetrip" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="columns is-multiline column is-12">
                                        <div class="column is-3" style="margin-top: 10px;">
                                            <span>Other remarks :</span>
                                        </div>
                                        <div class="column is-8">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.Otherremarks" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="column is-6">
                        <VField label="Physical Examination">
                            <VTextarea rows="2" v-model="input.TAPhysicalExamination"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <label style="font-weight: lighter;">Other Examination (Radiology, Lab, ECG, CT-Scan, USG,
                                MRI, etc)</label>
                            <VTextarea rows="2" v-model="input.TAOtherExamination"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Diagnosis">
                            <VTextarea rows="2" v-model="input.TADiagnosis"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Treatment / Medication">
                            <VTextarea rows="2" v-model="input.TATreatmentMedication"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <span style="font-weight: bold;">Doctor's Recomendation :</span>
                    </div>
                    <div class="columns is-multiline column is-6">
                        <div class="column is-12">Patient can be transported</div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Yes" label="Yes"
                                    v-model="input.CBPatientCanBeTransported" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="No" label="No"
                                    v-model="input.CBPatientCanBeTransported" />
                            </VControl>
                        </div>

                        <div class="column is-12">Escorted</div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Medical Escort"
                                    label="Medical Escort" v-model="input.CBEscorted" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Non Medical Escort"
                                    label="Non Medical Escort" v-model="input.CBEscorted" />
                            </VControl>
                        </div>

                        <div class="column is-12">Patient fit to fly</div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Yes" label="Yes"
                                    v-model="input.CBPatientFitToFly" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="No" label="No"
                                    v-model="input.CBPatientFitToFly" />
                            </VControl>
                        </div>
                    </div>
                    <div class="columns is-multiline column is-6">
                        <div class="column is-12">Class</div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Bussiness Class"
                                    label="Bussiness Class" v-model="input.CBbcClass" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Economy Class"
                                    label="Economy Class" v-model="input.CBecClass" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Wheel Chair"
                                    label="Wheel Chair" v-model="input.CBWheelChair" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Stretcher" label="Stretcher"
                                    v-model="input.CBSretcher" />
                            </VControl>
                        </div>

                        <div class="column is-12">Tourist/Expart Patient asking for reperation</div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Yes" label="Yes"
                                    v-model="input.CBTPAFR" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="No" label="No"
                                    v-model="input.CBTPAFR" />
                            </VControl>
                        </div>

                        <div class="column is-12">In Doctor's Opinion this patient requires reparation</div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Yes" label="Yes"
                                    v-model="input.CBInDoctorOpinion" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="No" label="No"
                                    v-model="input.CBInDoctorOpinion" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12">
                        <VField label="Comment">
                            <VTextarea rows="2" v-model="input.TAComment"></VTextarea>
                        </VField>
                    </div>
                </div>

                <!-- form baru -->

            </div>
        </div>

    </div>
</template>
