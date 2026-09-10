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
    title: 'Hasil Pemeriksaan MCU - ' + import.meta.env.VITE_PROJECT,
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
const COLLECTION: any = ref('HasilPemeriksaanMCU') //table mongodb
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
        input.value.TBNamaPasien = dataPasien.namapasien
        input.value.DTanggalLahir = dataPasien.tgllahir
        input.value.TBSUmurPasien = calculateAge(dataPasien.tgllahir)
        input.value.TBJenisKelaminPasien = dataPasien.jeniskelamin
        input.value.TAAlamatPasien = dataPasien.alamatlengkap
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
        'name_form': 'Hasil Pemeriksaan MCU',
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
            input.value.TBSBeratBadan = response.beratBadan
            input.value.TBSTinggiBadan = response.tinggiBadan
            // input.value.IMT = response.IMT
            // input.value.lingkarPerut = response.lingkarPerut
            input.value.TBSNadi = response.nadi
            // input.value.celciusObgyn = response.suhu
            input.value.TBSTekananDarah = response.tekananDarah
            input.value.TBSPernafasan = response.pernapasan
            // input.value.sao2Obgyn = response.SPO2
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
                            <h3>Hasil Pemeriksaan MCU</h3>
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
                        <div class="column is-12">
                            <VField label="Nama">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12 columns">
                            <div class="column is-6">
                                <VField label="Umur">
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBSUmurPasien" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>Tahun</VButton>
                                        </VControl>
                                    </VField>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField label="Jenis Kelamin">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBJenisKelaminPasien" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-12">
                            <VField label="Nama Dokter">
                                <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VField>
                        </div>
                    </div>
                    <div class="column is-6">
                        <VField label="Alamat">
                            <VTextarea rows="2" v-model="input.TAAlamatPasien"></VTextarea>
                        </VField>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline column is-12">
                    <div class="column is-12" style="font-weight: bold;font-size: large;">Pemeriksaan Fisik :</div>
                    <div class="column is-3">
                        <VField label="TB">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBSTinggiBadan" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Cm</VButton>
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="BB">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Kg</VButton>
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Nadi">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBSNadi" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/mnt</VButton>
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Tekanan Darah">
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
                        <VField label="Pernafasan">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBSPernafasan" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/mnt</VButton>
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline column is-12">
                    <div class="column is-12" style="font-weight: bold;font-size: large;">Kepala :</div>
                    <div class="column is-6 columns is-multiline">
                        <div class="column is-12">
                            <VField label="Mata">
                                <VTextarea rows="2" v-model="input.TAMata"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12" style="font-weight: bold;">Mulut</div>
                        <div class="column is-3">
                            <VField label="Bibir">
                                <VTextarea rows="2" v-model="input.TABibir"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Lidah">
                                <VTextarea rows="2" v-model="input.TALidah"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Jaringan Lunak">
                                <VTextarea rows="2" v-model="input.TAJaringanLunak"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Gigi">
                                <VTextarea rows="2" v-model="input.TAGigi"></VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-6 columns is-multiline">
                        <div class="column is-12">
                            <VField label="Leher">
                                <VTextarea rows="2" v-model="input.TALeher"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12" style="font-weight: bold;">THT</div>
                        <div class="column is-3">
                            <VField label="Telinga">
                                <VTextarea rows="2" v-model="input.TATelinga"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Hidung">
                                <VTextarea rows="2" v-model="input.TAHidung"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Tenggorokan">
                                <VTextarea rows="2" v-model="input.TATenggorokan"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-3"></div>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline column is-12">
                    <div class="column is-12" style="font-weight: bold;font-size: large;">Thorax :</div>
                    <div class="column is-6">
                        <VField label="Jantung">
                            <VTextarea rows="2" v-model="input.TAJantung"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Paru">
                            <VTextarea rows="2" v-model="input.TAParu"></VTextarea>
                        </VField>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline column is-12">
                    <div class="column is-12" style="font-weight: bold;font-size: large;">Abdomen :</div>
                    <div class="column is-4">
                        <VField label="Bising Usus">
                            <VTextarea rows="2" v-model="input.TABisingUsus"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Hepar">
                            <VTextarea rows="2" v-model="input.TAHepar"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Lien">
                            <VTextarea rows="2" v-model="input.TALien"></VTextarea>
                        </VField>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline column is-12">
                    <div class="column is-12" style="font-weight: bold;font-size: large;">Extremitas :</div>
                    <div class="column is-6">
                        <VField label="Atas">
                            <VTextarea rows="2" v-model="input.TAAtasExtremitas"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Bawah">
                            <VTextarea rows="2" v-model="input.TABawahExtremitas"></VTextarea>
                        </VField>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline column is-12">
                    <div class="column is-12" style="font-weight: bold;font-size: large;">Pemeriksaan Laboratorium :
                    </div>
                    <div class="column is-6">
                        <div class="column is-12">
                            <VField label="Analisa Darah Lengkap">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBAnalisaDarahLengkap" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Analisa Urine Lengkap">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBAnalisaUrineLengkap" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Gula Darah Puasa">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBGulaDarahPuasa" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Fungsi Hati">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBFungsiHati" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="column is-12">
                            <VField label="Fungsi Ginjal">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBFungsiGinjal" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Lipid Profil">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLipidProfil" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="HBsAg">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBHBsAg" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-12" style="font-weight: bold;">Pemeriksaan Lab Lainnya :</div>
                    <div class="column is-12">
                        <table class="tg">
                            <thead>
                                <tr>
                                    <th>Nama Pemeriksaan</th>
                                    <th>Hasil Pemeriksaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="input.TANP1"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="input.TAHP1"></VTextarea>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="input.TANP2"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="input.TAHP2"></VTextarea>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="input.TANP3"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="input.TAHP3"></VTextarea>
                                        </VField>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline column is-12">
                    <div class="column is-6">
                        <VField label="Analisa Foto Thorax : (kesan)">
                            <VTextarea rows="2" v-model="input.TAAnalisaFotoThorax"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="USG Abdomen : (kesan)">
                            <VTextarea rows="2" v-model="input.TAUSGAbdomen"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="EKG">
                            <VTextarea rows="2" v-model="input.TAEKG"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Echochardiografi">
                            <VTextarea rows="2" v-model="input.TAEchochardiografi"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Treadmill">
                            <VTextarea rows="2" v-model="input.TATreadmill"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Sirometri">
                            <VTextarea rows="2" v-model="input.TASirometri"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Assesment">
                            <VTextarea rows="2" v-model="input.TAAssesment"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Saran">
                            <VTextarea rows="2" v-model="input.TASaran"></VTextarea>
                        </VField>
                    </div>
                </div>
                <!-- form baru -->

            </div>
        </div>

    </div>
</template>