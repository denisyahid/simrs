<template>
    <ConfirmDialog group="templating">
        <template #message="slotProps">
            <div style="width:500px;height:300px;">
                <table style="width:100%;height:100%;border-collapse: collapse">
                    <tr>
                        <td style="text-align:center;vertical-align:middle">
                            <i :class="slotProps.message.icon"
                                style="font-size:125px;text-align:center;color:#FDDA0D"></i>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:7px;text-align:center">
                            <p style="font-size:large">{{ slotProps.message.message }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </template>
    </ConfirmDialog>

    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Rencana Keperawatan</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12" style="border-bottom: 2px solid #000;">
                    <div class="column is-12">
                        <div style="overflow-y:auto;" class="mt-1">
                            <table class="tabels" border="1" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="25%" style="vertical-align: inherit;text-align:center">
                                            DIAGNOSA KEPERAWATAN</th>
                                        <th width="40%" style="vertical-align: inherit;text-align:center">
                                            TUJUAN
                                        </th>
                                        <th width="25%" style="vertical-align: inherit;text-align:center">
                                            RENCANA TINDAKAN
                                        </th>
                                        <th style="vertical-align: inherit;text-align:center;" width="10%">
                                            PARAF/NAMA TERANG
                                        </th>
                                    </tr>
                                    <div style="overflow-y:auto;" class="mt-1">
                                        <th width="25%"
                                            style="vertical-align: inherit; text-align: left; word-wrap: break-word; white-space: normal; line-height: 1.5; border-left: 4px solid #000;">
                                            D.0112 Kesiapan Peningkatan Manajemen
                                            D.0112 Kesiapan Peningkatan Manajemen Kesehatan
                                            <br><br>
                                            DS :
                                            <br> 1. Mengekspresikan keinginan untuk mengelola masalah kesehatan dan
                                            pencegahannya
                                            <br> 2. Mengekspresikan tidak adanya hambatan yang berarti dalam
                                            mengintegrasikan program yang ditetapkan untuk mengatasi masalah kesehatan
                                            <br> 3. Menggambarkan berkurangnya faktor risiko terjadinya masalah
                                            kesehatan
                                            <br><br>
                                            DO :
                                            <br> 1. Pilihan ditemukannya adanya gejala masalah kesehatan atau penyakit
                                            yang
                                            tidak terduga
                                            <br> 2. Tidak ditemukan adanya gejala masalah kesehatan atau penyakit yang
                                            tidak
                                            berguna
                                            <br><br>
                                            Kondisi klinis terkait :
                                            <br> 1. Diabetes mellitus
                                            <br> 2. Penyakit jantung kongestif
                                            <br> 3. Penyakit paru obstruktif kronis
                                            <br> 4. Asma
                                            <br> 5. Sklerosis multipel
                                            <br> 6. Lupus sistemik
                                            <br> 7. HIV positif
                                            <br> 8. AIDS
                                            <br> 9. Prematuritas
                                        </th>
                                    </div>
                                    <div style="overflow-y:auto;" class="mt-1">
                                        <th width="25%"
                                            style="vertical-align: top; text-align: left; word-wrap: break-word; white-space: normal; line-height: 1.5; border-left: 4px solid #000;">
                                            Setelah dilakukan tindakan keperawatan selama x menit/jam,
                                            diharapkan manajemen kesehatan meningkat

                                            <br><br>
                                            <strong>Kriteria hasil:</strong>
                                            <br> 1. Mampu melakukan tindak-an untuk mengurangi faktor risiko
                                            <br> 2. Mampu menerapkan program perawatan
                                            <br> 3. Mampu melakukan aktivitas hidup sehari-hari untuk memenuhi
                                            <br> 4. Verbalisasi kesulitan dalam menjalani program perawatan/pengobatan
                                            <br><br>
                                        </th>
                                    </div>
                                    <div style="overflow-y:auto;" class="mt-1">
                                        <th width="25%"
                                            style="vertical-align: top; text-align: left; word-wrap: break-word; white-space: normal; line-height: 1.5; border-left: 4px solid #000;">
                                            Bimbingan antisipatif (L.12359)
                                            <br><br>
                                            <br> 1. Identifikasi metode penyelesaian masalah yang biasa digunakan
                                            <br> 2. Jadwal kan kunjungan pada setiap tahap perkembangan atau sesuai
                                            kebutuhan
                                            <br> 3. Libatkan keluarga dan pihak terkait
                                            <br> 4. Latih teknik koping yang dibutuhkan untuk mengatasi perkembangan
                                            atau
                                            krisis situasional
                                            <br> 5. Berikan referensi baik cetak maupun elektronik(pamflet, dll)
                                            <br><br>
                                            Edukasi kesehatan (L. 12383) :
                                            <br> 1. Identifikasi kesiapan dan kemampuan menerima informasi
                                            <br> 2. Sediakan materi dan media pendidikan kesehatan
                                            <br> 3. Jadwalkan pendidikan kesehatan sesuai kesepakatan
                                            <br> 4. Berikan kesempatan bertanya
                                            <br> 5. Jelaskan faktor risiko yang dapat mempengaruhi kesehatan
                                            <br> 6. Ajarkan perilaku hidup bersih dan sehat
                                            <br><br>
                                        </th>
                                    </div>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

            </div>
        </div>
    </div>



</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import moment from 'moment'
import { useToaster } from '/@src/composable/toaster'


useHead({
    title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
let detailStatusFungsional = ref(EMR.detailStatusFungsional())
let statusFungsional: any = ref(EMR.statusFungsional())

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
const d_pegawai: any = ref([])
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

const COLLECTION: any = ref('Rencanakeperawatan') //table mongodb

const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    kebjamKedatangan: new Date(),
    kebjamAsesmenAwal: new Date(),
    kebtanggalKedatangan: new Date(),
    nilaiSkrining: 0,
    penurunanbb: 0,
    penurunannafsu: 0,
    penurunanbbYa: 0,
    nilai: "RISIKO RENDAH (MST 0-1)",
    perawat: '',

    mengontrolbab: 0,
    mengontrolbak: 0,
    bersihdiri: 0,
    toilet: 0,
    makan: 0,
    berpindahtt: 0,
    mobilisasi: 0,
    berpakaian: 0,
    tangga: 0,
    mandi: 0,
    nilaimandi: 0,
    CBKetergantunganTotal: "Ketergantungan total (0-4)",
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const d_freknyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang Timbul' }, { value: 3, label: 'Terus Menerus' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lain-lain' }])
const d_gangguanpsikologis: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Gelisah' }, { value: 3, label: 'Takut' }, { value: 4, label: 'Sedih' }, { value: 5, label: 'Rendah diri' }, { value: 6, label: 'Acuh tak acuh' }, { value: 7, label: 'Mudah tersinggung' }, { value: 8, label: 'Menarik diri' }])
const d_masalahkawin: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_kekerasan: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaankesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi Lainnya' }])
const d_rohaniawan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_penurunanbb: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }])
const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_penurunanbbYa: any = ref([
    { value: 3, label: '1-5 kg' },
    { value: 4, label: '6-10 kg' },
    { value: 5, label: '11-15 kg' },
    { value: 6, label: '>15 kg' }
])
const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_mengontrolbab: any = ref([{ value: 1, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 2, label: 'Kadang inkontinen (1xseminggu)' }, { value: 3, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 1, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 2, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 3, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 1, label: 'Butuh pertolongan orang lain' }, { value: 2, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 1, label: 'Tergantung pertolongan orang lain' }, { value: 2, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 3, label: 'Mandiri' }])
const d_makan: any = ref([{ value: 1, label: 'Tidak mampu' }, { value: 2, label: 'Perlu seseorang menolong memotong makanan' }, { value: 3, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Dengan kursi roda' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 1, label: 'Tergantung orang lain' }, { value: 2, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 3, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Butuh Pertolongan' }, { value: 3, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 1, label: 'Teragantung orang lain' }, { value: 2, label: 'Mandiri' }])
const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const filterMenu: any = ref('')

const confirm = useConfirm();
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
let listHipertensi: any = ref(EMR.hipertensi())
let listDiabetes: any = ref(EMR.diabetes())
let listDyslipidemia: any = ref(EMR.dyslipidemia())
let listDuaPilihan: any = ref(EMR.duaPilihan())
let listAgama: any = ref(EMR.agama())
let listStatus: any = ref(EMR.status())
let listKeluarga: any = ref(EMR.keluarga())
let listTempatTinggal: any = ref(EMR.tempatTinggal())
let listPsikologis: any = ref(EMR.psikologis())
let listMore: any = ref(EMR.more())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let resikoNutrisional: any = ref(EMR.resikoNutrisional())
let fungsionalPertama: any = ref(EMR.fungsionalPertama())
let listRangeNilaiPoin: any = ref(EMR.nilaiPoin())
let listDESCNilai: any = ref(EMR.descNilai())
let pertanyaanA: any = ref(EMR.pertanyaanA())
let pertanyaanB: any = ref(EMR.pertanyaanB())
let pertanyaanC: any = ref(EMR.pertanyaanC())
let dropdownAllo: any = ref([
    "Suami/Istri",
    "Orang tua",
    "Anak",
    "Lainnya"
])
const loadRiwayat = async () => {
    isLoading.value = true
    let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    isLoading.value = false
    if (responsex.length) {
        input.value = responsex[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = responsex[0].emrpasienfk
        }
    } else {
        isLoading.value = true
        const responseTglRuangan = await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
        const responseHistori = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
        isLoading.value = false
        if (responseTglRuangan.length && responseHistori.length) {
            console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan)
            var tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
            var convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
            var tgl_Sekarang = moment();
            const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
            console.log("Tanggal EMR terakhir : " + convertTgl);
            console.log("Tanggal Sekarang : " + tgl_Sekarang);
            console.log("Total hari : " + calculateDays)
            if (responseTglRuangan[0].registrasi.namaruangan.trim() == H.setObjectRegistrasi(pasien.value.registrasi).namaruangan.trim() && calculateDays < 90) {
                confirm.require({
                    message: 'Asesmen Keperawatan sudah pernah diinput ' + calculateDays + ' hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya ?',
                    group: 'templating',
                    header: 'Informasi Asesmen Keperawatan',
                    icon: 'pi pi-exclamation-circle',
                    accept: () => {
                        if (responseHistori.length) {
                            input.value = responseHistori[0] //set ke inputan
                            input.value.namatemplate = ''
                            // console.log(input.value)
                        } else {
                            H.alert('warning', 'Data tidak ada')
                        }
                    },
                    reject: () => { }
                })
            }
        } else {
            console.log('Data EMR sebelumnya tidak ada!')
        }
        console.log("Ruangan pasien sekarang : " + H.setObjectRegistrasi(pasien.value.registrasi).namaruangan)
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
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    console.log(json)

    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            // NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
        })

    // console.log(resultValue)
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

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=100`
    ).then((response) => {
        d_pegawai.value = response
    })
}

const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {

        if (response != null || response != undefined) {
            input.value.beratbadanObgyn = response.beratBadan
            input.value.tinggibadanObgyn = response.tinggiBadan
            input.value.IMT = response.IMT
            input.value.lingkarPerut = response.lingkarPerut
            input.value.nadiObgyn = response.nadi
            input.value.celciusObgyn = response.suhu
            input.value.tekananDarahObgyn = response.tekananDarah
            input.value.nafasObgyn = response.pernapasan
            input.value.sao2Obgyn = response.SPO2
        }
    })
}



const print = async () => {
    H.printBlade(`dashboard/registrasi/cetak-rencana-keperawatan?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
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
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.center {
    text-align: center;
}

.vm {
    vertical-align: middle;
}

.bold {
    font-weight: bold;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}

.table.is-borderless th,
tr,
td {
    border: none !important;
    background-color: transparent !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 0px;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150%;
}

.tg2 {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg2 td {
    // border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg2 th {
    // border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.fontcheckbox {
    padding: 0px;
    padding-top: 5px;
    padding-left: 5px;
}

.tg td {
    // border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    // border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: middle
}

mark {
    background-color: yellow;
    /* Pastikan warna yang Anda inginkan ditulis di sini */
    color: black;
    /* Warna teks jika perlu */
}
</style>
