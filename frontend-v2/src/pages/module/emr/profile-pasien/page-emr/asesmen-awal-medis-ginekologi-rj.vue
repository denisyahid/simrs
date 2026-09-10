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
    // text-align: center !important;
    // vertical-align: middle !important;
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
import Gambarin from '../page-emr-plugins/img-draw.vue'

// Loopingan
let listPMG1 = ref([
    { caption: "Usia ibu diatas 35 tahun" },
    { caption: "Penyakit haematologi" },
    { caption: "Neural Tube Defect (Meningomyelocele, Apina Bifida, Anencephali)" },
    { caption: "Down syndrom" },
    { caption: "Huntington chorea" },
    { caption: "Retardasi mental" },
    { caption: "Kelainan kromosom" },
    { caption: "Riwayat lahir cacat" },
    { caption: "Riwayat abortus pada trimester I dan KJDR" },
    { caption: "Riwayat narkoba" },
    {}
])

let listPMG2 = ref([
    { caption: "Risiko tinggi HIV" },
    { caption: "Risiko tinggi Hepatitis B" },
    { caption: "Tinggal bersama penderita /infeksi kronis" },
    { caption: "Riwayat penyakit menular seksual" },
    { caption: "Riwayat penyakit menular seksual pasangan" }
])

let listPMG3 = ref([
    { caption: "Gagal KB" },
    { caption: "Korban pemerkosaan" },
    { caption: "Usia menggugurkan" },
    {}
])

let listPF = ref([
    { caption: "Kepala" },
    { caption: "Mata" },
    { caption: "Gigi" },
    { caption: "Tiroid" },
    { caption: "Payudara" },
    { caption: "Jantung" },
    { caption: "Paru" },
    { caption: "Perut" },
    { caption: "Pelvic" },
    { caption: "Tungkai atas" },
    { caption: "Tungkai bawah" },
    { caption: "Kelenjar limfe" },
])

// Judul
useHead({
    title: 'Asesmen Awal Medis Ginekologi - ' + import.meta.env.VITE_PROJECT,
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
const COLLECTION: any = ref('AsesmenAwalMedisGinekologiRJ') //table mongodb
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
    await loadGambar("GambarV", dataTTD.value.GambarV)
}
const loadGambar = async (element_id: string, value: string) => {
    let sigCanvas: any = document.getElementById(element_id);
    if (sigCanvas) {
        let context = sigCanvas.getContext("2d");
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        let imagess = value
        let background = new Image();
        background.src = imagess
        background.onload = function () {
            context.drawImage(background, 0, 0, 1300, 300);
        }
    }
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
    object['GambarV'] = H.tandaTangan().get("GambarV");
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

function dalamBatasNormalStatusGeneral() {
  if (input.value.dalamBatasNormalStatusGeneral == false) {
    listPF.value.forEach((_, index) => {
      input.value['CBStatusGeneral_' + index] = "Normal";
    });

  } else {
    listPF.value.forEach((_, index) => {
      input.value['CBStatusGeneral_' + index] = "";
    });

  }
};

function dalamBatasNormalPemeriksaanGine() {
  if (input.value.dalamBatasNormalPemeriksaanGine == false) {
    input.value.CBnormalVulva = "Normal";
    input.value.CBNormalVagina = "Normal";
    input.value.CBNormalCervix = "Normal";
    input.value.CBNormalUterus = "Normal";
    input.value.CBNormalAdnexa = "Normal";
    input.value.CBNormalRectum = "Normal";
  } else {
    input.value.CBnormalVulva = "";
    input.value.CBNormalVagina = "";
    input.value.CBNormalCervix = "";
    input.value.CBNormalUterus = "";
    input.value.CBNormalAdnexa = "";
    input.value.CBNormalRectum = "";

  }
};

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
                            <h3>Asesmen Awal Medis Ginekologi</h3>
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
                            <div class="column is-12" style="padding: 10px; padding-top: 0px;">
                                <div class="column is-12">
                                    <span><b>Tanda-tanda Vital</b></span>
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
                                <div class="column is-12 columns is-multiline" style="margin-bottom: -40px;">
                                    <div class="column is-4">
                                        <VControl>
                                        <VCheckbox class="p-0 mb-3" color="primary" square true-value="Dalam Batas Normal"
                                            label="Dalam Batas Normal" v-model="input.dalamBatasNormalStatusGeneral"
                                            @click="dalamBatasNormalStatusGeneral()" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4"></div>
                                    <div class="column is-4">
                                        <VControl>
                                            <VCheckbox class="p-0 mb-3" color="primary" square true-value="Dalam Batas Normal"
                                            label="Dalam Batas Normal" v-model="input.dalamBatasNormalPemeriksaanGine"
                                            @click="dalamBatasNormalPemeriksaanGine()" />
                                        </VControl>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <table class="tg">
                                        <thead>
                                            <tr>
                                                <th width="33%">Status General</th>
                                                <th width="33%">Keterangan</th>
                                                <th width="33%">Pemeriksaan Ginekologi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div v-for="(data, index) in listPF" :key="index"
                                                        class="column is-12 columns">
                                                        <div class="column is-4">{{ data.caption }}</div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Normal" label="Normal"
                                                                    v-model="input['CBStatusGeneral_' + index]" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Abnormal" label="Abnormal"
                                                                    v-model="input['CBStatusGeneral_' + index]" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="vertical-align:middle">
                                                    <VField>
                                                        <VTextarea rows="2" v-model="input.TAKeteranganPF"></VTextarea>
                                                    </VField>
                                                </td>
                                                <td>
                                                    <div class="column is-12">Pemeriksaan luar</div>
                                                    <div class="columns column is-12 is-multiline">
                                                        <div class="column is-12">Vulva</div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Normal" label="Normal"
                                                                    v-model="input.CBnormalVulva" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Condiloma" label="Condiloma"
                                                                    v-model="input.CBcondilomaVulva" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Lesi" label="Lesi"
                                                                    v-model="input.CBlesiVulva" />
                                                            </VControl>
                                                        </div>

                                                        <div class="column is-12">Vagina</div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Normal" label="Normal"
                                                                    v-model="input.CBNormalVagina" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Inflamasi" label="Inflamasi"
                                                                    v-model="input.CBInflamasiVagina" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Discharge" label="Discharge"
                                                                    v-model="input.CBDischargeVagina" />
                                                            </VControl>
                                                        </div>

                                                        <div class="column is-12">Cervix</div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Normal" label="Normal"
                                                                    v-model="input.CBNormalCervix" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Inflamasi" label="Inflamasi"
                                                                    v-model="input.CBInflamasiCervix" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4"></div>

                                                        <div class="column is-12">Uterus</div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Normal" label="Normal"
                                                                    v-model="input.CBNormalUterus" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Fibroid" label="Fibroid"
                                                                    v-model="input.CBFibroidUterus" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4"></div>

                                                        <div class="column is-12">Adnexa</div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Normal" label="Normal"
                                                                    v-model="input.CBNormalAdnexa" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Massa" label="Massa"
                                                                    v-model="input.CBMassaAdnexa" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4"></div>

                                                        <div class="column is-12">Rectum</div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Normal" label="Normal"
                                                                    v-model="input.CBNormalRectum" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Abnormal" label="Abnormal"
                                                                    v-model="input.CBAbnormalRectum" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4"></div>
                                                    </div>

                                                    <div class="column is-12">
                                                        <VField label="Lainnya">
                                                            <VTextarea rows="2" v-model="input.TAlainnyaPG">
                                                            </VTextarea>
                                                        </VField>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </Fieldset>
                    </div>

                    <div class="column is-12" style="padding: 10px;margin-top:10px;overflow: auto">
                        <Gambarin elemenID="GambarV" height="300" width="1300"
                            imageSrc="/images/simrs/AAM_Ginekologi.png" />
                    </div>
                    <div class="column is-12 columns">
                        <div class="column is-6">
                            <VField label="Ekstremitas">
                                <VTextarea rows="2" v-model="input.TAEkstremitas"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Rectal Toucher">
                                <VTextarea rows="2" v-model="input.TARectalToucher"></VTextarea>
                            </VField>
                        </div>
                    </div>

                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Hasil Pemeriksaan Penunjang">
                            <div class="column is-12 columns is-multiline" style="padding: 10px">
                                <div class="column is-6">
                                    <VField label="USG">
                                        <VTextarea rows="2" v-model="input.TAusgHPP"></VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField label="Laboratorium">
                                        <VTextarea rows="2" v-model="input.TALaboratoriumHPP"></VTextarea>
                                    </VField>
                                </div>
                            </div>
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