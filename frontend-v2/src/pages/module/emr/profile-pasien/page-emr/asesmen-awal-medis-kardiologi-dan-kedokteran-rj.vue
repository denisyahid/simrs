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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import * as EMR from '../page-emr-plugins/monitoring&evaluasi-resusitasi'

// Loopingan
let ListBEBAS = ref([

])


// Judul
useHead({
    title: 'Asesmen Awal Medis Kardiologi dan Kedokteran Vaskuler Rawat Jalan - ' + import.meta.env.VITE_PROJECT,
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
const d_Diagnosa = ref([])
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
const COLLECTION: any = ref('AsesmenAwalMedisKardiologidanKedokteranVaskulerRJ') //table mongodb
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
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
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
const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(`/diagnosa/diagnosa-x-paging?name=${filter.query}&limit=10`)
    d_Diagnosa.value = response.diagnosa.map((item: any) => {
        return { value: item.id, label: item.kddiagnosa + ' -- ' + item.namadiagnosa }
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
                            <h3>Asesmen Awal Medis Kardiologi dan Kedokteran Vaskuler Rawat Jalan</h3>
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
                                            <VField addons style="padding: 5px;padding-top:0px" label="PR : ">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBprTTV" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>x/mnt</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3">
                                            <VField addons style="padding: 5px;padding-top:0px" label="RR : ">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBrrTTV" />
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
                                        <div class="column"><b>Inspeksi : </b></div>
                                        <div class="columns is-multiline" style="margin-left: 10px;">
                                            <div class="column is-12">Iktus Kordis :</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Normal" label="Normal"
                                                            v-model="input.CBnormalIK" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Melebar, ke" label="Melebar, ke"
                                                            v-model="input.CBmelebarIK" />
                                                    </VControl>
                                                    <VControl style="margin-top: 5px">
                                                        <VInput type="text" class="input" v-model="input.TBmelebarIK" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VField label="Lokasi :">
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input.TBlokasiIK" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="column is-12">Pulsasi :</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Apex"
                                                            label="Apex" v-model="input.CBapexPulsasi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Prekordium" label="Prekordium"
                                                            v-model="input.CBprekordiumPulsasi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Epigastrium" label="Epigastrium"
                                                            v-model="input.CBepigastriumPulsasi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Lainnya" label="Lainnya"
                                                            v-model="input.CBlainnyaPulsasi" />
                                                    </VControl>
                                                    <VControl style="margin-top: 5px">
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBlainnyaPulsasi" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column"><b>Palpasi : </b></div>
                                        <div class="columns is-multiline" style="margin-left: 10px;">
                                            <div class="column is-12">Iktus Kordis :</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Normal" label="Normal"
                                                            v-model="input.CBnormalIK2" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Kuat angkat" label="Kuat angkat"
                                                            v-model="input.CBkuatAngkatIK2" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Meluas" label="Meluas"
                                                            v-model="input.CBmeluasIK2" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VField label="Lokasi :">
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input.TBlokasiIK2" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="column is-12">Thrill :</div>
                                            <div class="columns column is-12">
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Sistolik" label="Sistolik"
                                                            v-model="input.CBsistolikThrill" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Diastolik" label="Diastolik"
                                                            v-model="input.CBdiastolikThrill" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VField label="Lokasi :">
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input.TBlokasiThrill" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column"><b>Perkusi : </b></div>
                                        <div class="columns">
                                            <div class="column is-3">
                                                <VField label="Batas atas :">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBbatasAtasPerkusi" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField label="Batas bawah :">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBbatasbawahPerkusi" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField label="Batas kanan :">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBbatasKananPerkusi" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VField label="Batas kiri :">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBbatasKiriPerkusi" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>

                                        <div class="column"><b>Auskultasi : </b></div>
                                        <div class="columns is-multiline" style="margin-left: 10px;overflow:auto">
                                            <div class="columns is-multiline column is-12">
                                                <div class="column is-12">Suara jantung utama :</div>
                                                <div class="column is-3">
                                                    <VField>
                                                        <label>S1,S2</label>
                                                        <VControl style="margin-top: 5px">
                                                            <VInput type="text" class="input"
                                                                v-model="input.TBs1s2SJU" />
                                                        </VControl>
                                                    </VField>
                                                    <div class="columns" style="margin-top: 5px;">
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Reguler" label="Reguler"
                                                                    v-model="input.CBRegulerSJU" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ireguler" label="Ireguler"
                                                                    v-model="input.CBIregulerSJU" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Extra systole" label="Extra systole"
                                                            v-model="input.CBextraSystoleAuskultasi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-3">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Gallop" label="Gallop"
                                                            v-model="input.CBgallopAuskultasi" />
                                                    </VControl>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline column is-12">
                                                <div class="column is-12">Suara jantung tambahan :</div>
                                                <div class="column is-12">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Murmur" label="Murmur"
                                                            v-model="input.CBmurmurSJT" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-12 columns is-multiline"
                                                    style="margin-left: 10px">
                                                    <div class="column is-12 columns is-multiline">
                                                        <div class="column is-12">Fase :</div>
                                                        <div class="column is-3">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Sistolik" label="Sistolik"
                                                                    v-model="input.CBsistolikSJTFase" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-3">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Diastolik" label="Diastolik"
                                                                    v-model="input.CBdiastolikSJTfase" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-3">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Lainnya" label="Lainnya"
                                                                    v-model="input.CBlainnyaSJTfase" />
                                                            </VControl>
                                                            <VControl style="margin-top: 5px">
                                                                <VInput type="text" class="input"
                                                                    v-model="input.TBlainnyaSJTfase" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                    <div class="column is-12 columns is-multiline">
                                                        <div class="column is-12">Lokasi :</div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Apex" label="Apex"
                                                                    v-model="input.CBapexLokasi" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="ICS II Kiri PSL Kiri"
                                                                    label="ICS II Kiri PSL Kiri"
                                                                    v-model="input.CBicsKiriLokasi" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="ICS II Kanan PSL Kanan"
                                                                    label="ICS II Kanan PSL Kanan"
                                                                    v-model="input.CBicsKananLokasi" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="ICS IV PSL Kiri" label="ICS IV PSL Kiri"
                                                                    v-model="input.CBicsIVKiriLokasi" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Lainnya" label="Lainnya"
                                                                    v-model="input.CBlainnyaLokasi" />
                                                            </VControl>
                                                            <VControl style="margin-top: 5px">
                                                                <VInput type="text" class="input" v-model="input.TBlainnyaLokasi" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                    <div class="column is-12 columns is-multiline">
                                                        <div class="column is-12">Kualitas :</div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Rumbling" label="Rumbling"
                                                                    v-model="input.CBrumblingKualitas" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Blowing"
                                                                    label="Blowing"
                                                                    v-model="input.CBblowingKualitas" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ejection"
                                                                    label="Ejection"
                                                                    v-model="input.CBejectionKualitas" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Lainnya" label="Lainnya"
                                                                    v-model="input.CBlainnyaKualitas" />
                                                            </VControl>
                                                            <VControl style="margin-top: 5px">
                                                                <VInput type="text" class="input" v-model="input.TBlainnyaKualitas" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                    <div class="column is-12 columns is-multiline">
                                                        <div class="column is-12">Grade :</div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="I" label="I"
                                                                    v-model="input.CBiGrade" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="II" label="II"
                                                                    v-model="input.CBiiGrade" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="III" label="III"
                                                                    v-model="input.CBiiiGrade" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="IV" label="IV"
                                                                    v-model="input.CBivGrade" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="V" label="V"
                                                                    v-model="input.CBvGrade" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="VI" label="VI"
                                                                    v-model="input.CBviGrade" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                    <div class="column is-12 columns is-multiline">
                                                        <div class="column is-12">Penjalaran :</div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Axilla" label="Axilla"
                                                                    v-model="input.CBaxillaPenjalaran" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Punggung"
                                                                    label="Punggung"
                                                                    v-model="input.CBpunggungPenjalaran" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Sekitarnya"
                                                                    label="Sekitarnya"
                                                                    v-model="input.CBsekitarnyaPenjalaran" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Lainnya" label="Lainnya"
                                                                    v-model="input.CBlainnyaPenjalaran" />
                                                            </VControl>
                                                            <VControl style="margin-top: 5px">
                                                                <VInput type="text" class="input" v-model="input.TBlainnyaPenjalaran" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Opening Snap" label="Opening Snap"
                                                            v-model="input.CBopeningSnapSJT" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Friction Rub" label="Friction Rub"
                                                            v-model="input.CBofrictionRubSJT" />
                                                    </VControl>
                                                </div>
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

                                        <div class="column"><b>Lain-lain : </b></div>
                                        <div class="column">
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.TBLainlainSG" />
                                            </VControl>
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