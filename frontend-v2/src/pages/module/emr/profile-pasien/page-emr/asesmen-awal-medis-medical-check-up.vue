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
import Gambarin from '../page-emr-plugins/img-draw.vue'
// import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import * as EMR from '../page-emr-plugins/monitoring&evaluasi-resusitasi'

// Loopingan
let ListBEBAS = ref([

])


// Judul
useHead({
    title: 'Asesmen Awal Medis Medical Check Up - ' + import.meta.env.VITE_PROJECT,
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
    HjamKedatangan: newDate,
    HjamAW: newDate,
})
const dataTTD: any = ref([])
const route = useRoute()
const d_Dokter: any = ref([])
// const d_Diagnosa = ref([])
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
const COLLECTION: any = ref('AsesmenAwalMedisMedicalCheckUp') //table mongodb
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
    H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
    await loadGambar("GambarTubuh", dataTTD.value.GambarTubuh)
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
    object['GambarTubuh'] = H.tandaTangan().get("GambarTubuh");
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
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}
// const fetchDiagnosa = async (filter: any) => {
//     const response = await useApi().get(`/diagnosa/diagnosa-x-paging?name=${filter.query}&limit=10`)
//     d_Diagnosa.value = response.diagnosa.map((item: any) => {
//         return { value: item.id, label: item.kddiagnosa + ' -- ' + item.namadiagnosa }
//     })
// }
const loadGambar = async (element_id: string, value: string) => {
    let sigCanvas: any = document.getElementById(element_id);
    if (sigCanvas) {
        let context = sigCanvas.getContext("2d");
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        let imagess = value
        let background = new Image();
        background.src = imagess
        background.onload = function () {
            context.drawImage(background, 0, 0, 1200, 800);
        }
    }
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
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Asesmen Awal Medis Medical Check Up</h3>
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
                                    <VTextarea v-model="input.TAAnamnesis" rows="3">
                                    </VTextarea>
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
                                            <VField addons style="padding: 5px;padding-top:0px"
                                                label="Tekanan Darah : ">
                                                <VControl>
                                                    <VInput type="text" class="input"
                                                        v-model="input.TBtekananDarahTTV" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>mmHg</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3">
                                            <VField addons style="padding: 5px;padding-top:0px" label="Nadi : ">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBNadiTTV" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>x/mnt</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3">
                                            <VField addons style="padding: 5px;padding-top:0px" label="Respirasi : ">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBRespirasiTTV" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>x/mnt</VButton>
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
                                        <div class="columns">
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
                                                    <VCheckbox class="p-0" color="primary" square true-value="Telinga"
                                                        label="Telinga" v-model="input.CBTelingaTHT" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBTelingaTHT" />
                                                </VControl>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Hidung"
                                                        label="Hidung" v-model="input.CBHidungTHT" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBHidungTHT" />
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
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                                        label="Lain-lain" v-model="input.CBLainnyaTHT" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBLainnyaTHT" />
                                                </VControl>
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
                                        <div class="columns is-multiline">
                                            <div class="column is-3 columns is-multiline">
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Simetris" label="Simetris"
                                                            v-model="input.CBSimetrisThoraks" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Asimetris" label="Asimetris"
                                                            v-model="input.CBAsimetrisThoraks" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-12">
                                                    <VControl style="margin-top: 5px">
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBSimetrisORAsimetrisThoraks" />
                                                    </VControl>
                                                </div>
                                            </div>
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Retraksi"
                                                        label="Retraksi" v-model="input.CBRetraksiThoraks" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input"
                                                        v-model="input.TBRetraksiThoraks" />
                                                </VControl>
                                            </div>
                                        </div>

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
                                            <div class="column is-3">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                                        label="Lain-lain" v-model="input.CBLainLainCor" />
                                                </VControl>
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input" v-model="input.TBLainLainCor" />
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
                                                <VControl style="margin-top: 5px">
                                                    <VInput type="text" class="input"
                                                        v-model="input.TBVesikulerPulmo" />
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
                                                    <VCheckbox class="p-0" color="primary" square true-value="Souffle"
                                                        label="Souffle" v-model="input.CBSouffleAbdomen" />
                                                </VControl>
                                            </div>
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
                                                <VField label="- Hepar : ">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBHeparPeristaltik" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="- Lien : ">
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

                                        <div class="column">
                                            <VControl>
                                                <label><b>Lain-lain :</b></label>
                                                <VInput type="text" style="margin-top: 5px;" class="input"
                                                    v-model="input.TBLainlainSG" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Status Lokalis">
                            <Gambarin elemenID="GambarParu" height="800" width="1200"
                                    imageSrc="/images/simrs/outline-human-body.jpg" />
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Resume Pemeriksaan Penunjang">
                            <VField>
                                <VTextarea rows="2" v-model="input.TArpp"></VTextarea>
                            </VField>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Diagnosis" style="margin-bottom: 10px">
                            <VField>
                                <VTextarea rows="2" v-model="input.TADiagnosis"></VTextarea>
                            </VField>
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