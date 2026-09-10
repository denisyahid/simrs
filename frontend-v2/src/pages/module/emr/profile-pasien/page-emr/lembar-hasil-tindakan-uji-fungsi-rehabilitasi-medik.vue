<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Lembar Hasil Tindakan Uji Fungsi Rehabilitasi Medik</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading" @click="simpan()"> Simpan
                                </VButton>
                            </div>
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
                                    <div class="column is-12" v-for="(data) in listInputForm">
                                        <h1 class="mb-3 emr">{{ data.title }}</h1>
                                        <VControl v-if="data.type == 'textBox'">
                                            <input v-model="input[data.model]" class="input" />
                                        </VControl>
                                        <VField v-else-if="data.type == 'textarea'">
                                            <VControl>
                                                <VTextarea v-model="input[data.model]" rows="3">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                        <VField v-else-if="data.type == 'date'">
                                            <VDatePicker v-model="input[data.model]" mode="dateTime" style="width: 100%"
                                                trim-weeks :max-date="new Date()">
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField>
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" placeholder="Tanggal"
                                                                v-on="inputEvents" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                        <VField v-else class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                                <AutoComplete v-model="input.dokter" :suggestions="d_dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik untuk mencari..." />
                                            </VControl>
                                        </VField>
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
import { reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'

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
const pasien: any = ref({})
const d_dokter: any = ref({})
const isLoadingPasien: any = ref(false)
const items = ref([]);
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

const COLLECTION: any = ref('LembarHasilTindakanUjiFungsiRehabilitasiMedik') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    waktu: new Date,
})
const value = ref("");
const d_Diagnosa: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)


// ==================== Start List Data =================

const listInputForm = ref([
    {
        "title": "Instrumen Uji Fungsi/Prosedur KFR",
        "model": "prosedurKFR",
        "utils": "",
        "type": "textBox"
    },
    {
        "title": "Diagnosa Fungsional",
        "model": "diagnosaFungsional",
        "utils": "d_Diagnosa",
        "type": "select"
    },
    {
        "title": "Diagnosa Medis",
        "model": "namadiagnosa",
        "utils": "d_Diagnosa",
        "type": "select"
        
    },
    {
        "title": "Hasil yang di dapat",
        "model": "Hasil",
        "utils": "",
        "type": "textarea"
    },
    {
        "title": "Kesimpulan",
        "model": "kesimpulan",
        "utils": "",
        "type": "textarea"
    },
    {
        "title": "Rekomendasi",
        "model": "rekomendasi",
        "utils": "",
        "type": "textBox"
    },
    {
        "title": "Bandung, Jam",
        "model": "waktu",
        "utils": "",
        "type": "date"
    },
    {
        "title": "Dokter Pemeriksa",
        "model": "dokter",
        "utils": "d_Dokter",
        "type": "select"
    },
])

// ==================== End List Data ==================

const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_dokter.value = response
}

const loadRiwayat = async () => {

    await useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan 
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}

const simpan = () => {

    let ID = input.id ? input.id : ''

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
    isLoading.value = true
    // console.log(json)

    // // isLoading.value = true
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
}
const dokterDPJP = async () => {
    await useApi().get(`emr/get-dokter-dpjp?nocmfk=${ID_PASIEN}`).then((response) => {
        input.value.dokter = response.dokter
    })
}
const diagnosa = async () => {
    await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
        input.value.namadiagnosa = response[0].namadiagnosa
    })
}

diagnosa()
dokterDPJP()
fetchPasien()
loadRiwayat()

watch(() => [
    input.value.kesadaranE,
    input.value.kesadaranM,
    input.value.kesadaranV,
    input.value.totalKesadaran,
    // input.value.point2
], () => {
    let poin1 = input.value.kesadaranE ? parseInt(input.value.kesadaranE) : 0
    let poin2 = input.value.kesadaranM ? parseInt(input.value.kesadaranM) : 0
    let poin3 = input.value.kesadaranV ? parseInt(input.value.kesadaranV) : 0

    const jumlahNilai = poin1 + poin2 + poin3
    input.value.totalKesadaran = jumlahNilai
})

</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

.p-fieldset-legend {
    margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
    background: none;
}

.checkbox.is-outlined {
    padding: unset !important;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

h1.emr {
    font-weight: bold;
}

table.assesment {
    border-collapse: collapse;
    width: 100%;
}


.assesment th {
    text-align: center !important;
    border-bottom: 1px solid black;
    // border: 1px solid black;
}


.assesment th,
td {
    padding: 8px;
    vertical-align: middle !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}
</style>
