<style lang="scss">
    hr {
        padding: 0px;
        margin: 0px;
    }

    .bold {
        font-weight:bold;
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
    title: 'Surat Keterangan Disabilitas - ' + import.meta.env.VITE_PROJECT,
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
const COLLECTION: any = ref('SuratKeteranganDisabilitas') //table mongodb
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
        input.value.DDRuangan = dataRegis.namaruangan
        input.value.DDDokter = dataRegis.dokter
        input.value.TBNamaPasien = dataPasien.namapasien
        input.value.DTanggalLahir = dataPasien.tgllahir
        input.value.TBSUmurPasien = calculateAge(dataPasien.tgllahir)
        input.value.TBJenisKelaminPasien = dataPasien.jeniskelamin
        input.value.TAAlamatPasien = dataPasien.alamatlengkap
        input.value.TBTempatLahirPasien = dataPasien.tempatlahir
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
        'name_form': 'Surat Keterangan Disabilitas',
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    console.log(json)

    isLoading.value = true
    useApi().post(`/emr/simpan-emr-surket`, json).then((response: any) => {
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
// const fetchRuangan = async (filter: any) => {
//     await useApi().get(
//         `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`
//     ).then((response) => {
//         d_Ruangan.value = response
//     })
// }
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
            // input.value.TBSNadi = response.nadi
            // input.value.TBSSuhu = response.suhu
            // input.value.TBSTekananDarah = response.tekananDarah
            // input.value.TBSPernafasan = response.pernapasan
            // input.value.TBSSaO2 = response.SPO2
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
                            <h3>Surat Keterangan Disabilitas</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"
                                :isHideCetakWNA="false"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="columns is-multiline column is-12 pb-0 ">
                    <div class="column is-4 pb-0">
                        <VField label="Dokter">
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VControl>
                        </VField>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline column is-12 pb-0">
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
                                <VField label="Tempat">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBTempatLahirPasien" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField label="Tanggal Lahir">
                                    <VDatePicker v-model="input.DTanggalLahir" mode="date" trim-weeks>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                        </div>

                        <div class="column is-12">
                            <VField label="Umur">
                                <VField addons>
                                    <VControl>
                                        <VInput type="number" class="input" v-model="input.TBSUmurPasien" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Tahun</VButton>
                                    </VControl>
                                </VField>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="column is-12">
                            <VField label="Jenis Kelamin">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBJenisKelaminPasien" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Alamat">
                                <VTextarea rows="2" v-model="input.TAAlamatPasien"></VTextarea>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12" style="font-weight: bold;">1. Jenis/Ragam Disabilitas</div>
                <div class="column is-12 columns is-multiline" style="margin-left: 10px;">
                    <div class="column is-12 pt-0" style="font-weight:bold">a. Disabilitas Fisik</div>
                    <div class="column is-12 columns is-multiline p-0" style="margin-left: 10px;">
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Amputasi" label="1. Amputasi"
                                    v-model="input.CBAmputasi" style="" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Tangan" label="Tangan"
                                    v-model="input.CBTanganAmputasi" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Kaki" label="Kaki"
                                    v-model="input.CBKakiAmputasi" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns is-multiline p-0" style="margin-left: 10px;">
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Lumpuh layuh atau kaku"
                                    label="2. Lumpuh layuh atau kaku" v-model="input.CBLumpuhLayuh" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Tangan" label="Tangan"
                                    v-model="input.CBTanganLumpuhLayuh" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Kaki" label="Kaki"
                                    v-model="input.CBKakiLumpuhLayuh" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns is-multiline p-0" style="margin-left: 10px;">
                        <div class="column is-12">
                            <VControl raw subcontrol>
                               <VCheckbox class="p-0" color="primary" square true-value="Paraplegi" label="3. Paraplegi (anggota tubuh bagian bawah yang meliputi kedua tungkai dan organ panggul)"
                                    v-model="input.CBParaplegi" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns is-multiline p-0" style="margin-left: 10px;">
                        <div class="column is-12">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Cerebral Palsy" label="4. Cerebral Palsy (CP)"
                                    v-model="input.CBCerebalPalsy" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-12 pt-0" style="font-weight:bold">b. Disabilitas Sensorik</div>
                    <div class="column is-12 columns is-multiline p-0" style="margin-left: 10px;">
                        <div class="column is-4">1. Netra</div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="a. Buta Total"
                                    label="a. Buta Total" v-model="input.CBButaTotalNetra" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="b. Persepsi Cahaya Low vision"
                                    label="b. Persepsi Cahaya / Low vision" v-model="input.CBPersepsiCahayaNetra" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns is-multiline p-0" style="margin-left: 10px;">
                        <div class="column is-12">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Rungu" label="2. Rungu"
                                    v-model="input.CBRungu" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns is-multiline p-0" style="margin-left: 10px;">
                        <div class="column is-12">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Wicara" label="3. Wicara"
                                    v-model="input.CBWicara" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-12 pt-0" style="font-weight:bold">c. Disabilitas Intelektual</div>
                    <div class="column is-12 columns is-multiline p-0" style="margin-left: 10px;">
                        <div class="column is-12">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Disabilitas Grahita" label="1. Disabilitas Grahita"
                                    v-model="input.CBDisabilitasGrahitaDI" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns is-multiline p-0" style="margin-left: 10px;">
                        <div class="column is-12">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Down Syndrome" label="2. Down Syndrome"
                                    v-model="input.CBDownSyndromeDI" />
                            </VControl>
                        </div>
                    </div>

                    <div class="column is-12 pt-0" style="font-weight:bold">d. Disabilitas Mental</div>
                    <div class="column is-12 columns is-multiline p-0" style="margin-left: 10px;">
                        <div class="column is-12">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Psikososial" label="1. Psikososial"
                                    v-model="input.CBPsikososial" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Skizofrenia"
                                    label="Skizofrenia" v-model="input.CBSkizofreniaPsikososial" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Bipolar" label="Bipolar"
                                    v-model="input.CBBipolarPsikososial" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Depresi" label="Depresi"
                                    v-model="input.CBDepresiPsikososial" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Anxietas" label="Anxietas"
                                    v-model="input.CBAnxietasPsikososial" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Gangguan Kepribadian"
                                    label="Gangguan Kepribadian" v-model="input.CBGangguanKepribadianPsikososial" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns is-multiline p-0" style="margin-left: 10px;">
                        <div class="column is-4">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Disabilitas Perkembangan"
                                    label="2. Disabilitas Perkembangan" v-model="input.CBDisabilitasPerkembangan" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Autis" label="Autis"
                                    v-model="input.CBAutisDP" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Hiperaktif" label="Hiperaktif"
                                    v-model="input.CBHiperaktifDP" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12 pb-0" style="font-weight: bold;">2. Derajat Disabilitas</div>
                <div class="column is-12">
                    <VField>
                        <VTextarea rows="2" v-model="input.TADerajatDisabilitas"></VTextarea>
                    </VField>
                </div>

                <hr>

                <div class="column is-12 pb-0" style="font-weight: bold;">3. Penyebab</div>
                <div class="column is-12 columns is-multiline pb-0">
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Sejak Lahir" label="Sejak Lahir"
                                v-model="input.CBSejakLahir" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Kecelakaan dalam pekerjaan"
                                label="Kecelakaan dalam pekerjaan" v-model="input.CBKecelakaanDalamPekerjaan" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Kecelakaan Lalu Lintas"
                                label="Kecelakaan Lalu Lintas" v-model="input.CBKecelakaanLaluLintas" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Penyakit" label="Penyakit"
                                v-model="input.CBPenyakit" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Akibat Stroke"
                                label="Akibat Stroke" v-model="input.CBAkibatStroke" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Akibat Kusta" label="Akibat Kusta"
                                v-model="input.CBAkibatKusta" />
                        </VControl>
                    </div>
                    <div class="column is-6">
                        <VField label="Lain-lain">
                            <VTextarea rows="2" v-model="input.TALainlain"></VTextarea>
                        </VField>
                    </div>
                </div>

                <hr>

                <div class="column is-12 pb-0" style="font-weight: bold;">4. Alat bantu yang digunakan</div>
                <div class="column is-12 pb-0 columns is-multiline">
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                v-model="input.CBABYD" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Ada" label="Ada"
                                v-model="input.CBABYD" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBABYD" />
                        </VControl>
                    </div>
                </div>

                <hr>

                <div class="column is-12 pb-0" style="font-weight: bold;">Surat keterangan ini untuk keperluan</div>
                <div class="column is-12">
                    <VField>
                        <VTextarea rows="2" v-model="input.TAskiuk"></VTextarea>
                    </VField>
                </div>
                <!-- form baru -->

            </div>
        </div>

    </div>
</template>