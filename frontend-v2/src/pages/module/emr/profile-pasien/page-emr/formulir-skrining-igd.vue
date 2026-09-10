<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                                @click="print()"> Cetak
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h1 style="font-weight:bold;">Anamnesa dan Riwayat (14 Hari SMRS)</h1>
                        <div class="columns p-3">
                            <div class="column is-12 pb-0">
                                <div class="columns">
                                    <div class="column is-1">
                                        <h1 class="mb-3 emr" style="text-align: center;font-weight:bold;">No</h1>
                                    </div>
                                    <div class="column is-4" style="text-align: left;">
                                        <h1 class="mb-3 emr" style="font-weight:bold;">Pertanyaan</h1>
                                    </div>
                                    <div class="column is-4" style="text-align: left; ">
                                        <h1 class="mb-3 emr"></h1>
                                    </div>
                                    <div class="column is-3" style="text-align: left; font-weight:bold;">
                                        <h1 class="mb-3 emr" style="font-weight:bold;">Skor</h1>
                                    </div>
                                </div>
                                <hr style="margin-top:-1rem" />

                                <div class="columns pb-4" v-for="(data) in Anamnesa">
                                    <div class="column is-1 " style="text-align: center;">
                                        <h1 class="mb-3 emr" style="font-weight:bold;"> {{ data.no }}</h1>
                                    </div>
                                    <div class="column  is-4" style="text-align: left;">
                                        <h1 class="mb-3 emr" style="font-weight:bold;">{{ data.parameter }}</h1>
                                    </div>
                                    <div class="column  is-4 pt-0" style="text-align: left;">
                                        <VField v-for="(pilihan) in data.nilai">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="pb-0" :true-value="pilihan.skor"
                                                    v-model="input[pilihan.input]" :label="pilihan.label" color="primary" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column  is-3 pb-1" style="text-align: left;">
                                        <VField v-for="(pilihan) in data.nilai">
                                            <Vlabel>{{ pilihan.skor }}</Vlabel>
                                        </VField>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="column is-4" style="margin-left: auto;">
                            <VField>
                                <h1 style="font-weight: bold;" class="mb-2">TOTAL SCORE
                                </h1>
                                <VControl>
                                    <input v-model="input.totalSkorAnamnesa" class="input" placeholder="Jumlah" disabled />
                                </VControl>
                            </VField>
                        </div>

                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight:bold;">Gejala Klinis</h1>
                        <div class="columns p-3">
                            <div class="column is-12 pb-0">
                                <div class="columns">
                                    <div class="column is-1">
                                        <h1 class="mb-3 emr" style="text-align: center; font-weight:bold;">No</h1>
                                    </div>
                                    <div class="column is-4" style="text-align: left;">
                                        <h1 class="mb-3 emr" style="font-weight:bold;">Pertanyaan</h1>
                                    </div>
                                    <div class="column is-4" style="text-align: left;">
                                        <h1 class="mb-3 emr"></h1>
                                    </div>
                                    <div class="column is-3" style="text-align: left;">
                                        <h1 class="mb-3 emr" style="font-weight:bold;">Skor</h1>
                                    </div>
                                </div>
                                <hr />

                                <div class="columns pb-4" v-for="(data) in gejalaKlinis">
                                    <div class="column is-1 " style="text-align: center;">
                                        <h1 class="mb-3 emr" style="font-weight:bold;"> {{ data.no }}</h1>
                                    </div>
                                    <div class="column  is-4" style="text-align: left;">
                                        <h1 class="mb-3 emr" style="font-weight:bold;">{{ data.parameter }}</h1>
                                    </div>
                                    <div class="column  is-4" style="text-align: left;">
                                        <VField v-for="(pilihan) in data.nilai">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="pb-0" :true-value="pilihan.skor"
                                                    v-model="input[pilihan.input]" :label="pilihan.label" color="primary" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column  is-3 pt-6" style="text-align: left;">
                                        <VField v-for="(pilihan) in data.nilai">
                                            <Vlabel>{{ pilihan.skor }}</Vlabel>
                                        </VField>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="column is-4 pt-0" style="margin-left: auto;">
                            <VField>
                                <h1 style="font-weight: bold;" class="mb-2">TOTAL SCORE
                                </h1>
                                <VControl>
                                    <input v-model="input.totalSkorGejala" class="input" placeholder="Jumlah" disabled />
                                </VControl>
                            </VField>
                        </div>

                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">SKOR</h1>
                        <table class="tg">
                            <tr>
                                <th style="text-align: center">Pemeriksaan</th>
                                <td style="text-align: center">Kriteria</td>
                                <td style="text-align: center">Skor</td>
                            </tr>
                            <tr>
                                <th rowspan="5">Anamnesa dan Riwayat (14 hari SMRS)</th>
                                <td>Demam</td>
                                <td>0 = Tidak Ada</td>
                            </tr>
                            <tr>
                                <td>Batuk/Pilek/Nyeri Tenggorokan</td>
                                <td>1 = Ada Salah Satu</td>
                            </tr>
                            <tr>
                                <td>Nyeri Otot</td>
                                <td>2 = Ada > 1</td>
                            </tr>
                            <tr>
                                <td> Kontak erat/Kasus konfirmasi </td>
                                <td>3 = Jika Kontak (+), Swab (+)</td>
                            </tr>
                            <tr>
                                <td> Riwayat Pemeriksaan Swab test (Pn Cr)</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th rowspan="6">Anamnesa dan Riwayat (14 hari SMRS)</th>
                                <td> Suhu 38°C</td>
                                <td> 0 = Tidak Ada</td>

                            </tr>
                            <tr>
                                <td>Sesak Nafas</td>
                                <td>1 = Jika hipertermia</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td> 3 = Jika Sesak </td>
                            </tr>

                        </table>
                        <table class="tg" style="margin-top: 2rem">
                            <tr>
                                <th rowspan="2"
                                    style="width: 20%; text-align:center; margin-top: 2rem; background-color: #009000; font-weight: bold;">
                                    SKOR = 0</th>
                                <td>1. Pasien/pengunjung boleh melanjutkan ke area pelayanan kesehatan di zona non COVID-19
                                </td>
                            </tr>
                            <tr>
                                <td>2. Memakai masker, cuci tangan dengan sabun dan air mengalir serta penerapan jarak fisik
                                    > 1m</td>
                            </tr>

                        </table>
                        <table class="tg" style="margin-top: 2rem">
                            <tr>
                                <th rowspan="4"
                                    style="width: 20%; text-align:center; margin-top: 2rem; background-color: red; font-weight: bold;">
                                    SKOR = 1-3 </th>
                                <td>1. Penunjung yang memiliki skor 1-3 diterapkan sistem penanganan pasien gejala COVID-19
                                </td>
                            </tr>
                            <tr>
                                <td>2. Pasien diarahkan ke IGD khusus COVID-19 untuk evaluasi lebih lanjut</td>
                            </tr>
                            <tr>
                                <td>3. Pasien menunggu di ruang COVID-19</td>
                            </tr>
                            <tr>
                                <td>4. Memakai masker, cuci tangan dengan sabun dan air mengalir serta penerapan jarak fisik
                                    > 1m</td>
                            </tr>

                        </table>


                    </div>
                </div>
                <div class="column is-4 mt-3" style="margin-left: auto;">
                                        <VCard class="border-card pink">
                                            <div class="column is-9">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Tanggal</h1>
                                                </VField>
                                                <VField>
                                                    <VDatePicker v-model="input.tanggal" mode="dateTime"
                                                        style="width: 100%" trim-weeks :max-date="new Date()">
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VField>
                                                                <VControl icon="feather:calendar" fullwidth>
                                                                    <VInput :value="inputValue" placeholder="Jam Keluar IGD"
                                                                        v-on="inputEvents" />
                                                                </VControl>
                                                            </VField>
                                                        </template>
                                                    </VDatePicker>
                                                </VField>
                                            </div>
                                            <div class="column pt-0">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Petugas Skrining</h1>
                                                </VField>
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:search">
                                                        <AutoComplete v-model="input.petugasSkrining"
                                                            :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                            :field="'label'" placeholder="ketik nama petugas" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </VCard>
                                    </div>

            </VCard>
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
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';

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

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('FormulirSkriningIGD') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal : new Date(),
})
const d_Petugas = ref([])
const Anamnesa = ref([
    {
        "no": 1,
        "parameter": "Demam",
        "nilai": [
            {
                "input": "Demam",
                "skor": "0",
                "label": "a. Tidak"
            },
            {
                "input": "Demam",
                "skor": "1",
                "label": "b. Ya"
            },
        ]
    },
    {
        "no": 2,
        "parameter": "Batuk/ Pilek / Nyeri Tenggorokan",
        "nilai": [
            {
                "input": "Batuk",
                "skor": "0",
                "label": "a. Tidak"
            },
            {
                "input": "Batuk",
                "skor": "1",
                "label": "b. Ya"
            },

        ]
    },
    {
        "no": 3,
        "parameter": "Kontak erat/Kasus Konfirmasi",
        "nilai": [
            {
                "input": "KontakErat",
                "skor": "0",
                "label": "a. Tidak"
            },
            {
                "input": "KontakErat",
                "skor": "1",
                "label": "b. Ya"
            },

        ]
    },
    {
        "no": 4,
        "parameter": "Riwayat pemeriksaan Swab test (PCR)",
        "nilai": [
            {
                "input": "PernahPCR",
                "skor": "0",
                "label": "a. Tidak"
            },
            {
                "input": "PernahPCR",
                "skor": "1",
                "label": "b. Ya"
            },

        ]
    },
])

const gejalaKlinis = ref([
    {
        "no": 1,
        "parameter": "Suhu 38°C",
        "nilai": [
            {
                "input": "suhuTinggi",
                "skor": "0",
                "label": "a. Tidak"
            },
            {
                "input": "suhuTinggi",
                "skor": "1",
                "label": "b. Ya"
            },
        ]
    },
    {
        "no": 2,
        "parameter": "Sesak Nafas",
        "nilai": [
            {
                "input": "sesakNafas",
                "skor": "0",
                "label": "a. Tidak"
            },
            {
                "input": "sesakNafas",
                "skor": "1",
                "label": "b. Ya"
            },

        ]
    },

])
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan 
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}
const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
}

const simpan = () => {
    let ID = input.id ? input.id : ''

    let object: any = {}

    object = input.value
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

const print = async () => {
    H.printBlade(`emr/cetak-formulir-skrining-igd?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

watch(() => [
    input.value.Demam,
    input.value.Batuk,
    input.value.KontakErat,
    input.value.PernahPCR,
    input.value.totalSkorAnamnesa
    // input.value.point2
], () => {
    let skor1 = input.value.Demam ? parseInt(input.value.Demam) : 0
    let skor2 = input.value.Batuk ? parseInt(input.value.Batuk) : 0
    let skor3 = input.value.KontakErat ? parseInt(input.value.KontakErat) : 0
    let skor4 = input.value.PernahPCR ? parseInt(input.value.PernahPCR) : 0


    const jumlahNilai = skor1 + skor2 + skor3 + skor4
    input.value.totalSkorAnamnesa = jumlahNilai
})

watch(() => [
    input.value.suhuTinggi,
    input.value.sesakNafas,
    input.value.totalSkorGejala
    // input.value.point2
], () => {
    let skor1 = input.value.suhuTinggi ? parseInt(input.value.suhuTinggi) : 0
    let skor2 = input.value.sesakNafas ? parseInt(input.value.sesakNafas) : 0

    const jumlahNilai = skor1 + skor2
    input.value.totalSkorGejala = jumlahNilai
})
setView()
loadRiwayat()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

hr {
    background-color: #b3b3b3;
    border: none;
    display: block;
    height: 2px;

    margin-top: -1rem;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
    vertical-align: center;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: top
}</style>