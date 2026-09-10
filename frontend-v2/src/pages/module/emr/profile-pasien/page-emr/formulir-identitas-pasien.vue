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
// import Fieldset from 'primevue/fieldset';
// import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import * as EMR from '../page-emr-plugins/monitoring&evaluasi-resusitasi'

// Loopingan
let ListTable1 = ref([
    {
        detail: [
            { caption: "1. Memperoleh Informasi mengenai tata tertib & peraturan yang berlaku di rumah sakit" },
            { caption: "1. Achieve Hospital’s Information related to rules & regulation applied" },
        ]
    },
    {
        detail: [
            { caption: "2. Memperoleh informasi mengenai Hak & Kewajiban Pasien" },
            { caption: "2. Achieve Patient’s Rights & Obligations Information" },
        ]
    },
    {
        detail: [
            { caption: "3. Memperoleh Pelayanan yang manusiawi, adil, jujur & tanpa diskriminasi" },
            { caption: "3. Obtain proper service, equal, fair, and honesty as a human being" },
        ]
    },
    {
        detail: [
            { caption: "4. Memperoleh pelayanan kesehatan yang bermutu sesuai standar profesi & standar prosedur operasional (SPO)" },
            { caption: "4. Obtained qualified standard health service in accordance to professional standard and standard operational procedures" },
        ]
    },
    {
        detail: [
            { caption: "5. Memperoleh pelayanan yang efektif & efisien sehingga pasien terhindar dari kerugian fisik & materi" },
            { caption: "5. Obtaining effective & efficient service in the purpose to avoid physical & material last" },
        ]
    },
    {
        detail: [
            { caption: "6. Mengajukan pengaduan atas kualitas pelayanan yang didapatkan" },
            { caption: "6. Able to complain about the service related" },
        ]
    },
    {
        detail: [
            { caption: "7. Memilih dokter dan kelas perawatan sesuai dengan pilihan" },
            { caption: "7. Able to choose the doctor & room occording to will based on Hospital’s regulations applied" },
        ]
    },
    {
        detail: [
            { caption: "8. Berkonsultasi tentang penyakit yang diderita maupun informasi lain yang berkaitan dengan kesehatan kepada Dokter lain yang memiliki Surat Izin Praktik (SIP) baik di dalam maupun diluar Rumah Sakit" },
            { caption: "8. Consulting to other legally licenced doctor related to illness & medical solutions inside or outside the hospital" },
        ]
    },
    {
        detail: [
            { caption: "9. Mendapatkan privasi dan kerahasiaan penyakit yang diderita termasuk data-data medis pasien" },
            { caption: "9. Keep the privacy and all related medical resume about patient confidentiol" },
        ]
    },
    {
        detail: [
            { caption: "10. Mendapat informasi meliputi diagnosis dan tata cara tindakan medis, tujuan tindakan medis, alternative tindakan, resiko & komplikasi yang mungkin terjadi, dan prognosis terhadap tindakan yang dilakukan serta perkiraan biaya pengobatan" },
            {},
        ]
    },
    {
        detail: [
            { caption: "11. Memberi persetujuan atau penolakan atas tindakan medis yang akan dilakukan" },
            { caption: "11. Approval or denial related to medical Act given" },
        ]
    },
    {
        detail: [
            { caption: "12. Didampingi keluarganya saat keadaan kritis" },
            { caption: "12. Under certain circumstances such as critical condition, family may present aside to accompany" },
        ]
    },
    {
        detail: [
            { caption: "13. Menjalankan ibadah sesuai agama dan kepercayaan yang dianut selama tidak merugikan & menggangu pasien lain" },
            { caption: "13. Pray according to own belief wthn no interfere & disturbance to other patients applied" },
        ]
    },
    {
        detail: [
            { caption: "14. Memperoleh keamanan & keselamatan dirinya selama dalam perawatan di Rumah Sakit" },
            { caption: "14. Safety & secure during medical treatment or act" },
        ]
    },
    {
        detail: [
            { caption: "15. Pengajuan usul, saran, serta perbaikan terhadap Rumah Sakit atas pelayanan kesehatan" },
            { caption: "15. Improvement suggestions propose of medical treatment & act" },
        ]
    },
    {
        detail: [
            { caption: "16. Menolak bimbingan rohani yang tidak sesuai agama & kepercayaan yang dianut" },
            { caption: "16. Denial of non related belief spiritual guidance" },
        ]
    },
    {
        detail: [
            { caption: "17. Pengajuan pengaduan kepada instansi terkait apabiÍa pelayanan tidak sesuai standar yang berlaku serta perundang-undangan yang berlaku" },
            { caption: "17. Legally propose compliance to relevant institution for non standard operational treatment under government regulations & provisions" },
        ]
    },
    {
        detail: [
            { caption: "18. Pengajuan keluhan atas pelayanan yang tidak sesuai standar pelayanan melalui bagian Hubungan Masyarakat / Cust. Service" },
            { caption: "18. Propose a comploint act trough Public Relafior:s department or Customer Service Department" },
        ]
    }
])

let ListTable2 = ref([
    {
        detail: [
            { caption: "1. Memberikan informasi yang benar, jelas & jujur mengenai masalah kesehatannya" },
            { caption: "1. Provide a fair & truth information related" },
        ]
    },
    {
        detail: [
            { caption: "2. Mengetahui kewajiban & tanggung jawab pasien & keluarga" },
            { caption: "2. Understand responsibilities & obligations of patient also family memder realted" },
        ]
    },
    {
        detail: [
            { caption: "3. Mengajukan pertanyaan atas hal yang tidak atau kurang dimengerti" },
            { caption: "3. Asking for questions regarding unknown & less known information" },
        ]
    },
    {
        detail: [
            { caption: "4. Memahami & menerima konsekuensi pelayanan" },
            { caption: "4. Understand & accept risk and consequences of related services" },
        ]
    },
    {
        detail: [
            { caption: "5. Mematuhi instruksi & menghormati peraturan Rumah Sakit yang berlaku" },
            { caption: "5. In order of instructions and hospital’s regulations respect" },
        ]
    },
    {
        detail: [
            { caption: "6. Menunjukkan sikap menghormati serta tenggang rasa" },
            { caption: "6. Respect & tolerance each other in Hospital’s environment" },
        ]
    },
    {
        detail: [
            { caption: "7. Memenuhi kewajiban financial yang disepakati" },
            { caption: "7. Financial obligations agreement payment" },
        ]
    }
])


// Judul
useHead({
    title: 'Formulir Identitas Pasien - ' + import.meta.env.VITE_PROJECT,
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
const d_Petugas: any = ref([])
const input: any = ref({})
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
const COLLECTION: any = ref('FormulirIdentitasPasien') //table mongodb
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
    H.tandaTangan().set("TTDPetugas", dataTTD.value.TTDPetugas)
    H.tandaTangan().set("TTDPasien", dataTTD.value.TTDPasien)
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    object['TTDPetugas'] = H.tandaTangan().get("TTDPetugas");
    object['TTDPasien'] = H.tandaTangan().get("TTDPasien");
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

const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
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
// setAutoFill()
</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Formulir Identitas Pasien</h3>
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

                <div class="columns is-multiline" style="padding: 10px;">
                    <div class="column is-12">
                        <p>Dengan ini saya menyatakan bahwa :</p>
                        <p>Data — data / pengantar yang saya tulis dalam form adalah benar, serta telah mendapatkan
                            penjelasan dan leaflet tentang hak dan kewa jiban sebagai pasien di RSUD. Bali Mandara
                            seperti
                            yang tertera dalam lembar form ini.</p>
                        <p>Hereby, I declare, all of my statement within this form are true, all explanation,
                            information
                            and leaflet regarding rights also obligation of being patient at Bali Mandara Hospital are
                            obtained.</p>
                    </div>

                    <div class="column is-12">
                        <table class="tg">
                            <thead>
                                <tr>
                                    <th width="50%">Hak Pasien</th>
                                    <th width="50%">Patient’s Rights</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in ListTable1" :key="index">
                                    <td v-for="(dataz, idx) in data.detail" :key="idx">
                                        <div v-if="index === 9 && idx === 1">
                                            <label>10. Achieve information related to :</label>
                                            <ul>
                                                <li>- Diagnose & medical treatment procedures</li>
                                                <li>- Purpose of medical treatment</li>
                                                <li>- Alternative treatment suggestion</li>
                                                <li>- Risks & Possible Complications</li>
                                                <li>- Prognosis of Medical Act given</li>
                                                <li>- Approximate cost applied</li>
                                            </ul>
                                        </div>
                                        <label v-else>
                                            {{ dataz.caption }}
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="column is-12">
                        <table class="tg">
                            <thead>
                                <tr>
                                    <th width="50%">Kewajiban Pasien</th>
                                    <th width="50%">Patient’s Obligations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="data in ListTable2">
                                    <td v-for="dataz in data.detail">
                                        <label>{{ dataz.caption }}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p>
                                            - Menyetujui untuk mendapatkan pelayanan kesehatan di RSUD Bali Mandara dan
                                            memberikan kuasa kepada pihak Rumah Sakit, dokter, perawat, dan tenaga
                                            kesehatan
                                            lainnya untuk memberikan pelayanan kesehatan yang bermutu dan aman, sesuai
                                            dengan standar profesi dan standar operasional terhadap diri saya<br />
                                            Agree to
                                            obtain health care in Bali Mandara Hospital and also authority given to Bali
                                            Mandara Hospital, doctors, nurses, and other related health officer
                                            qualified and safety health treatment service according to professional and
                                            standard operational procedure
                                        </p><br />
                                        <p>
                                            - Apabila asuransi kesehatan swasta / jaminan kesehatan program pemerintah
                                            atau instansi tempat saya bekerja menanggung pembiayaan saya, saya memberi
                                            wewenang kepada RSUD Bali Mandara untuk memberi tagihan dari semua pelayanan
                                            serta tindakan medis yang diberikan termasuk informasi medis dan fotokopi
                                            hasil pemeriksaan saya apabila dibutuhkan dalam proses claim<br /> In case of
                                            insurance & institution cover regarding to financial, I hereby author Bali
                                            Mandara Hospital to provide invoice payment of all medical treatment and act
                                            given to me, include medical information copy of examinations findings and
                                            all medical related information needed of me ini order to fulfill insurance
                                            claiming requirement process
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="columns">
                                            <div class="column is-6">
                                                <p>Pernyataan ini saya buat dengan sebenar-benamya dalam keadaan sadar
                                                    dan tanpa
                                                    paksaan dari pihak manapun</p>
                                                <p>Therefore, this statement true made consciously and honestly with no
                                                    pressure
                                                </p>
                                            </div>
                                            <div class="column is-6" style="display: flex;justify-content: center;">
                                                <VField label="Garut">
                                                    <VField addons>
                                                        <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks
                                                            :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VControl icon="feather:calendar" fullwidth>
                                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                                </VControl>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                </VField>
                                            </div>
                                        </div>
                                        <div class="columns" style="margin-top: 20px;">
                                            <div class="column is-6" style="text-align: center;">
                                                <TandaTangan :elemenID="'TTDPasien'" :width="'150'" :height="'150'"
                                                    class="dek" />
                                                <h1 style="margin-top: 10px;">Nama & Tanda Tangan
                                                    Pasien/Keluarga<br>Patient’s/Family’s Signature</h1>
                                            </div>
                                            <div class="column is-6" style="display: flex;justify-content: center;">
                                                <div class="column is-6" style="text-align: center;">
                                                    <TandaTangan :elemenID="'TTDPetugas'" :width="'150'" :height="'150'"
                                                        class="dek" />
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="input.CBPetugas" :suggestions="d_Petugas"
                                                            @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                            :dropdown="true" :minLength="3" :appendTo="'body'"
                                                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            class="mt-2" />
                                                    </VControl>
                                                    <h1 style="margin-top: 10px;">Nama & Tanda Tangan
                                                        Petugas<br>Ofices’s Signature</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- form baru -->
            </div>
        </div>
    </div>
</template>