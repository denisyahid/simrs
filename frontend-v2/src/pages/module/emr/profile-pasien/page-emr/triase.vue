<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Triase</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                             @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>
                <div class="form-body p-2">
                    <div class="business-dashboard hr-dashboard">
                        <div class="columns is-multiline">
                            <div class="column is-12" v-if="isLoadingPasien">
                                <PlaceloadHeader class="m-3" />
                            </div>
                            <div class="column is-12" v-if="!isLoadingPasien">
                                <VCard class="border-card pink">
                                    <div class="columns is-multiline p-3">
                                        <div class="column is-7">
                                            <div class="column is-12 p-0">
                                                <h1 style="font-weight: bold;">Cara Masuk</h1>
                                                <div class="is-flex">
                                                    <VField v-for="(caramasuk) in listCaraMasuk">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.caramasuk" :true-value="caramasuk"
                                                                :label="caramasuk" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="column is-12 p-0">
                                                <h1 style="font-weight: bold;">Alat Bantu</h1>
                                                <div class="is-flex">
                                                    <VField v-for="(alat) in listAlatBantu">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.alatbantu" :true-value="alat"
                                                                :label="alat" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="column is-12 p-0">
                                                <h1 style="font-weight: bold;">Kasus</h1>
                                                <div class="is-flex">
                                                    <VField v-for="(kasus) in listKasus">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.kasus" :true-value="kasus"
                                                                :label="kasus" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column is-5">
                                            <h1 style="font-weight: bold;">Tanggal dan Jam datang</h1>
                                            <div class="columns pt-3 pb-0">
                                                <div class="column is-6">
                                                    <VDatePicker v-model="input.date.tanggal" color="green" trim-weeks
                                                        :input="'YYYY-MM-DD'" mode="date">
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VField>
                                                                <VControl icon="feather:calendar">
                                                                    <VInput type="text" placeholder="Select a date"
                                                                        :value="inputValue" v-on="inputEvents"
                                                                        class="is-rounded_Z" />
                                                                </VControl>
                                                            </VField>
                                                        </template>
                                                    </VDatePicker>
                                                </div>
                                                <div class="column is-6 p-0">
                                                    <VDatePicker class="column is-7" v-model="input.date.jam" color="green"
                                                        mode="time" is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput class="input form-timepicker"
                                                                        :value="inputValue" v-on="inputEvents" />
                                                                </VControl>
                                                            </VField>
                                                        </template>
                                                    </VDatePicker>
                                                </div>

                                            </div>
                                            <div class="column is-12 p-0" style="position: relative;top:-18px">
                                                <h1 style="font-weight: bold;">Keluhan Utama</h1>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.keluahan" rows="3"
                                                            placeholder="Keluhan Utama ...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>

                                            </div>
                                            <div class="column is-12 p-0">
                                                <h1 style="font-weight: bold;">Resiko Jatuh : get up and go</h1>
                                                <div style="display:flex;margin-top: 12px;">
                                                    <div class="flex align-items-center"
                                                        style="margin-left: 1rem;margin-right: 5rem;">
                                                        <RadioButton v-model="input.resiko" value="Ya" />
                                                        <label class="ml-2">Ya</label>
                                                    </div>
                                                    <div class="flex align-items-center">
                                                        <RadioButton v-model="input.resiko" value="Tidak" />
                                                        <label class="ml-2">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </VCard>

                                <VCard class="border-card pink mt-5">
                                    <div class="column is-12">
                                        <table border="1" class="triase">
                                            <tr>
                                                <th colspan="7" style="text-align: center;">TRIASE</th>
                                            </tr>

                                            <tr>
                                                <td>Kriteria</td>
                                                <td v-for="(data, i) in listKriteria">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]" :true-value="data.value"
                                                                :label="data.title" color="primary" class="p-0"
                                                                style="color:black;" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td rowspan="5">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.doa" true-value="Doa" label="Doa"
                                                                color="primary" class="p-0" style="color:black;" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Airway</td>
                                                <td v-for="(datas, i) in listAirway">
                                                    <VField v-for="(data) in datas.value">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[datas.model]" :true-value="data"
                                                                :label="data" color="primary" class="p-0"
                                                                style="color:black;" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Breathing</td>
                                                <td>
                                                    <VField v-for="(breathing) in listBreathing.breathing1">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[breathing.model]"
                                                                :true-value="breathing.value" :label="breathing.title"
                                                                color="primary" class="p-0" style="color:black" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td>
                                                    <VField v-for="(breathing) in listBreathing.breathing2">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[breathing.model]"
                                                                :true-value="breathing.value" :label="breathing.title"
                                                                color="primary" class="p-0" style="color:black" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td>
                                                    <VField v-for="(breathing) in listBreathing.breathing3">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[breathing.model]"
                                                                :true-value="breathing.value" :label="breathing.title"
                                                                color="primary" class="p-0" style="color:black" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td>
                                                    <VField v-for="(breathing) in listBreathing.breathing4">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[breathing.model]"
                                                                :true-value="breathing.value" :label="breathing.title"
                                                                color="primary" class="p-0" style="color:black" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td>
                                                    <VField v-for="(breathing) in listBreathing.breathing5">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[breathing.model]"
                                                                :true-value="breathing.value" :label="breathing.title"
                                                                color="primary" class="p-0" style="color:black" />
                                                        </VControl>
                                                    </VField>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Circulation</td>
                                                <td>
                                                    <VField v-for="(circulations) in listCirculation.circulat1">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[circulations.model]"
                                                                :true-value="circulations.value" :label="circulations.title"
                                                                color="primary" class="p-0" style="color:black" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td>
                                                    <VField v-for="(circulations) in listCirculation.circulat2">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[circulations.model]"
                                                                :true-value="circulations.value" :label="circulations.title"
                                                                color="primary" class="p-0" style="color:black" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td>
                                                    <VField v-for="(circulations) in listCirculation.circulat3">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[circulations.model]"
                                                                :true-value="circulations.value" :label="circulations.title"
                                                                color="primary" class="p-0" style="color:black" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td>
                                                    <VField v-for="(circulations) in listCirculation.circulat4">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[circulations.model]"
                                                                :true-value="circulations.value" :label="circulations.title"
                                                                color="primary" class="p-0" style="color:black" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td>
                                                    <VField v-for="(circulations) in listCirculation.circulat5">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[circulations.model]"
                                                                :true-value="circulations.value" :label="circulations.title"
                                                                color="primary" class="p-0" style="color:black" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Disability</td>
                                                <td v-for="(disability) in listDisability">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[disability.model]" :true-value="disability.value"
                                                                :label="disability.value" color="primary" class="p-0"
                                                                style="color:black" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </VCard>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import RadioButton from 'primevue/radiobutton';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
useHead({
    title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
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
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)

const date = ref(new Date)
const ingredient = ref('');
const pasien: any = ref({})
const isLoadingPasien: any = ref(false)
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

const COLLECTION: any = ref('Triase') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    date: {
        tanggal: new Date,
        jam: new Date
    },
})
const router = useRouter()
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isBtnDisabled = ref(false)


let listCaraMasuk = ref(['Sendiri', 'Ambulan', 'Diantar Polisi', 'Diantar Keluarga'])
let listAlatBantu = ref(['Jalan Kaki', 'Brankard', 'Kursi Roda', 'Tongkat / Walker'])
let listKasus = ref(['Non Trauma', 'Trauma'])
let listKriteria = ref([
    {
        "title": "Kategori 1 Resusutasi RESPON TIME : Segera",
        "value": "Segera",
        "model": "kategori_1"
    },
    {
        "title": "Kategori 2 Emergency/Gawat Darurat RESPON TIME : 10 Menit",
        "value": "10 Menit",
        "model": "kategori_2"
    },
    {
        "title": "Kategori 3 Urgent/Darurat RESPON TIME : 30 Menit",
        "value": "30 Menit",
        "model": "kategori_3"
    },
    {
        "title": "Kategori 4 Semi Darurat RESPON TIME : 60 Menit",
        "value": "60 Menit",
        "model": "kategori_4"
    },
    {
        "title": "Kategori 5 Tidak Darurat RESPON TIME : 120 Menit",
        "value": "120 Menit",
        "model": "kategori_5"
    }
])
let listAirway = ref([
    {
        "value": ['Sumbatan Total', 'Sumbatan Sebagian'],
        "model": "airway_1"
    },
    {
        "value": ['Paten'],
        "model": "airway_2"
    },
    {
        "value": ['Paten'],
        "model": "airway_3"
    },
    {
        "value": ['Paten'],
        "model": "airway_4"
    },
    {
        "value": ['Paten'],
        "model": "airway_5"
    },
])

let listBreathing = ref({
    breathing1: [
        {
            "title": "Distress Pernapasan",
            "model": 'breathing_1',
            "value": {
                "code": "DPB",
                "value": "Distress Pernapasan",
            }
        },
        {
            "title": "Henti Napas",
            "model": 'breathing_2',
            "value": {
                "code": "HNB",
                "value": "Henti Napas",
            }
        },
        {
            "title": "Hipoventilasi",
            "model": 'breathing_3',
            "value": {
                "code": "HIB",
                "value": "Hipoventilasi",
            }
        },
        {
            "title": "RR < 10x/mnt",
            "model": 'breathing_4',
            "value": {
                "code": "RRB",
                "value": "RR < 10x/mnt",
            }
        },
    ],
    breathing2: [
        {
            "title": "Distress Pernapasan Berat, RR > 32x/mnt",
            "model": 'breathing_5',
            "value": {
                "code": "DPB-RR",
                "value": "Distress Pernapasan Berat, RR > 32x/mnt",
            }
        },
        {
            "title": "Wheezing/Mengi",
            "model": 'breathing_6',
            "value": {
                "code": "WEMG",
                "value": "Wheezing/Mengi",
            }
        }
    ],
    breathing3: [
        {
            "title": "Distress Pernapasan Ringan, RR 25-32x/mnt",
            "model": 'breathing_7',
            "value": {
                "code": "DPR-RR",
                "value": "Distress Pernapasan Ringan, RR 25-32x/mnt",
            }
        },
    ],
    breathing4: [
        {
            "title": "Tidak ada distress pernapasan, RR Normal",
            "model": 'breathing_8',
            "value": {
                "code": "DD-RR",
                "value": "Tidak ada distress pernapasan, RR Normal",
            }
        },
    ],
    breathing5: [
        {
            "title": "Frekuensi Nafas Normal",
            "model": 'breathing_9',
            "value": {
                "code": "FNM",
                "value": "Frekuensi Nafas Normal",
            }
        },
    ]
})

let listCirculation = ref({
    circulat1: [
        {
            "title": "Gangguan hemodinamik berat",
            "model": "circulate_1",
            "value":
            {
                "value": "Gangguan hemodinamik berat",
                "code": "GHB",
            },

        },
        {
            "title": "TDS < 80 mmHg atau tanda syok",
            "model": "circulate_2",
            "value":
            {
                "value": "TDS < 80 mmHg atau tanda syok",
                "code": "TDS",
            },

        },
        {
            "title": "Nadi tidak teraba",
            "model": "circulate_3",
            "value":
            {
                "value": "Nadi tidak teraba",
                "code": "NTT",
            },

        },
        {
            "title": "Pendarahan yang tiada terkontrol atau pendarahan aktiv",
            "model": "circulate_4",
            "value":
            {
                "value": "Pendarahan yang tiada terkontrol atau pendarahan aktiv",
                "code": "PA",
            },

        },
    ],
    circulat2: [
        {
            "title": "Gangguan hemodinamik sedang",
            "model": "circulate_5",
            "value":
            {
                "value": "Gangguan hemodinamik sedang",
                "code": "GHS",
            },

        },
        {
            "title": "Nadi tidak teraba/sangat halus",
            "model": "circulate_6",
            "value":
            {
                "value": "Nadi tidak teraba/sangat halus",
                "code": "SH",
            },
        },
        {
            "title": "Pengisian kapiler > 2 detik",
            "model": "circulate_7",
            "value":
            {
                "value": "Pengisian kapiler > 2 detik",
                "code": "PKD",
            },

        },
        {
            "title": "Nyeri dada kardiak",
            "model": "circulate_8",
            "value":
            {
                "value": "Nyeri dada kardiak",
                "code": "NDK",
            },

        },
        {
            "title": "HR < 50x/menit",
            "model": "circulate_9",
            "value":
            {
                "value": "HR < 50x/menit",
                "code": "HRK",
            },

        },
        {
            "title": "HR > 50x/menit",
            "model": "circulate_10",
            "value":
            {
                "value": "HR > 50x/menit",
                "code": "HRL",
            },

        },
    ],
    circulat3: [
        {
            "title": "Gangguan hemodinamik riangan",
            "model": "circulate_11",
            "value":
            {
                "value": "Gangguan hemodinamik riangan",
                "code": "GHR",
            },

        },
        {
            "title": "Nadi teraba (lemah-kuat)",
            "model": "circulate_12",
            "value":
            {
                "value": "Nadi teraba (lemah-kuat)",
                "code": "NTL",
            },

        },
        {
            "title": "Pengisian kapiler < 2 detik",
            "model": "circulate_13",
            "value":
            {
                "value": "Pengisian kapiler < 2 detik",
                "code": "NKK",
            },

        },
    ],
    circulat4: [
        {
            "title": "Tidak ada gangguan hemodinamik nadi teraba",
            "model": "circulate_14",
            "value":
            {
                "value": "Tidak ada gangguan hemodinamik nadi teraba",
                "code": "TGHNT",
            },

        },
    ],
    circulat5: [
        {
            "title": "Tidak ada gangguan hemodinamik nadi normal",
            "model": "circulate_15",
            "value":
            {
                "value": "Tidak ada gangguan hemodinamik nadi normal",
                "code": "TGHNN",
            },

        },
    ],
})

let listDisability = ref([
    {
        "model" : 'disability_1',
        "value" : 'GCS < 9',
    },
    {
        "model" : 'disability_2',
        "value" : 'GCS 9-12',
    },
    {
        "model" : 'disability_3',
        "value" :'GCS 12-14',
    },
    {
        "model" : 'disability_4',
        "value" : 'GCS 15',
    },
    {
        "model" : 'disability_5',
        "value" : 'GCS 15',
    }
])


const loadRiwayat = () => {

    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length > 0) {
                isBtnDisabled.value = false
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }else{

                }
            }else{
                isBtnDisabled.value = true
            }
        })
}

const simpan = () => {
    let ID = input.id ? input.id : ''
    let object: any = {}
    object = input.value
    object.nocm = pasien.value.nocm
    object.pasien = H.setObjectPasien(props.pasien)
    object.jamDatang = H.formatDate(item.date.jam, 'HH:mm')
    object.tglDatang = H.formatDate(item.date.tanggal, 'YYYY-MM-DD')
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    isLoading.value = true
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }

    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            NOREC_EMRPASIEN.value = response.norec_emr
            loadRiwayat()
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
}
fetchPasien()
loadRiwayat()

</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

table.triase {
    border-collapse: collapse;
    width: 100%;
}

table.triase,
th,
.triase td {
    border: 1px solid black;
}


.triase th,
td {
    padding: 8px;
    vertical-align: middle !important;
}

// tr:hover {
//     background-color: #f5f5f5;
// }
</style>
