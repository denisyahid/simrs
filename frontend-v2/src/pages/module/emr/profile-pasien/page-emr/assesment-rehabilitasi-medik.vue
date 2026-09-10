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
        <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
        <div class="column is-12" v-else>
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="I. Pengkajian Keperawatan">
                            <div class="column is-12" v-for="(data) in pengKeperawatan">
                                <h1 class="emr mb-3">{{ data.title }}</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(detail) in data.value">
                                        <p>{{ detail.subTitle }}</p>
                                        <VField addons v-if="detail.type == 'textfiled'">
                                            <VControl expanded>
                                                <VInput type="text" class="input" v-model="input[detail.model]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ detail.satuan }}</VButton>
                                            </VControl>
                                        </VField>

                                        <VField v-else-if="detail.type == 'textbox'">
                                            <VControl>
                                                <input v-model="input[detail.model]" class="input"
                                                    :placeholder="detail.subTitle + ' ...'" />
                                            </VControl>
                                        </VField>

                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                    :label="detail.satuan" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-3">
                                        <h1 class="emr">Jam</h1>
                                        <VDatePicker class="column is-7" v-model="input.jam" color="green" mode="time"
                                            is24hr>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl>
                                                        <VInput class="input form-timepicker" :value="inputValue"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <div class="column is-4">
                                        <h1 class="emr mb-2">Perawat / Terapi</h1>
                                        <VField class="is-autocomplete-select" v-slot="{ id }">
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai"
                                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="Cari nama perawat / terapi" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                    </div>

                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="II. Pengkajian Medis">
                            <div class="column is-12 pt-0" v-for="(data) in pengMedis">
                                <h1 class="emr pb-3">{{ data.title }}</h1>
                                <div class="columns is-multiline">
                                    <div class="column"
                                        :class="data.type == 'textarea' || data.type == 'select' ? ' is-6' : ' is-2'"
                                        v-for="(detail) in data.value">
                                        <VField v-if="data.type == 'textarea'">
                                            <h1 class="emr mb-3">{{ detail.subTitle }}</h1>
                                            <VControl>
                                                <VTextarea v-model="input[detail.model]" rows="3"
                                                    :placeholder="detail.subTitle + '...'">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                        <VField v-else-if="data.type == 'checkBox'" class="pb-4 pl-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="detail.subTitle"
                                                    :label="detail.subTitle" color="primary" class="p-0"
                                                    style="color:black;" />
                                            </VControl>
                                        </VField>

                                        <VField v-else class="is-autocomplete-select ml-3 mr-3" v-slot="{ id }">
                                            <p class="mt-2 mb-3">{{ detail.subTitle }}</p>
                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input[detail.model]" :suggestions="d_Diagnosa"
                                                    @complete="fetchDiagnosa($event)" :optionLabel="'nama'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'nama'"
                                                    placeholder="Cari diagnosa berdasarkan nama atau kode" />
                                            </VControl>
                                        </VField>

                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                    </div>

                    <div class="column is-12">
                        <VCard class="border-card pink">
                            <div class="column is-12" v-for="(data) in more">
                                <h1 class="emr mb-3">{{ data.title }}</h1>
                                <div class="columns is-multiline">
                                    <div class="column" v-for="(value) in data.value"
                                        v-if="data.type == 'textarea' || data.type == 'checkBox'">
                                        <VField v-if="data.type == 'textarea'">
                                            <h1 class="emr mb-3">{{ value.subTitle }}</h1>
                                            <VControl>
                                                <VTextarea v-model="input[value.model]" rows="3"
                                                    :placeholder="value.subTitle + '...'">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                        <VField v-else-if="data.type == 'checkBox'" class="pb-4 pl-4 pt-2">
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[value.model]" :true-value="value.subTitle"
                                                    :label="value.subTitle" color="primary" class="p-0"
                                                    style="color:black;" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-12" v-else-if="data.type == 'textbox'"
                                        v-for="(value) in data.value">
                                        <h1 class="emr mb-3">{{ value.subTitle }}</h1>
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input[value.model]"
                                                    placeholder="Dirujuk Ke..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12">
                                <table class="assesment">
                                    <tr>
                                        <th colspan="2">Telah Diverifikasi</th>
                                        <th colspan="2">Telah Dikode</th>
                                        <th>Legalisasi</th>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Verifikator</th>
                                        <th>Tanggal</th>
                                        <th>Koder</th>
                                        <th>Komite Medis</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <VField>
                                                <VDatePicker v-model="input.tglVerifikasi" mode="dateTime"
                                                    style="width: 100%" trim-weeks :max-date="new Date()">
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
                                        </td>
                                        <td>
                                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.verifikator" :suggestions="d_Pegawai"
                                                        @complete="fetchPegawai($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="Cari verifikator " />
                                                </VControl>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VDatePicker v-model="input.tglVerifikasi" mode="dateTime"
                                                    style="width: 100%" trim-weeks :max-date="new Date()">
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
                                        </td>
                                        <td>
                                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.koder" :suggestions="d_Pegawai"
                                                        @complete="fetchPegawai($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="Cari Koder" />
                                                </VControl>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.komiteMedis" :suggestions="d_Dokter"
                                                        @complete="fetchDokter($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="Cari komite medis" />
                                                </VControl>
                                            </VField>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="column is-4 mt-4" style="margin-left: auto;">
                                <h1 class="emr mb-3">Dokter</h1>
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="input.dokter" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'nama'"
                                            placeholder="Cari nama dokter" />
                                    </VControl>
                                </VField>
                            </div>
                        </VCard>
                    </div>

                </div>
            </VCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Fieldset from 'primevue/fieldset';
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';

// ===================== Data Source ===================

let pengKeperawatan = ref([
    {
        "title": "",
        "value": [
            {
                "subTitle": "Tekanan Darah",
                "model": "tekananDarah",
                "type": "textfiled",
                "satuan": "mmHg"
            },
            {
                "subTitle": "Berat Badan",
                "model": "beratBadan",
                "type": "textfiled",
                "satuan": "Kg"
            },
            {
                "subTitle": "Frekuensi Nada",
                "model": "frekuensiNada",
                "type": "textfiled",
                "satuan": "x/menit"
            },
            {
                "subTitle": "Tinggi Badan",
                "model": "tinggiBadan",
                "type": "textfiled",
                "satuan": "cm"
            },
            {
                "subTitle": "Suhu",
                "model": "suhu",
                "type": "textfiled",
                "satuan": "°C"
            },
            {
                "subTitle": "Frekuensi Nafas",
                "model": "frekuensiNafas",
                "type": "textfiled",
                "satuan": "x/menit"
            },
            {
                "subTitle": "Skor Nyeri",
                "model": "skorNyeri",
                "type": "textbox",
                "satuan": "",

            },
        ],
    },
    {
        "title": "Skor Jatuh",
        "value": [
            {
                "subTitle": "",
                "model": "rendah",
                "type": "checkBox",
                "satuan": "Rendah"
            },
            {
                "subTitle": "",
                "model": "sedang",
                "type": "checkBox",
                "satuan": "Sedang"
            },
            {
                "subTitle": "",
                "model": "tinggi",
                "type": "checkBox",
                "satuan": "Tinggi"
            },
        ],
    },

])

let pengMedis = ref([
    {
        "title": "",
        "type": "textarea",
        "value": [
            {
                "subTitle": "Anamneis (S)",
                "model": "anamneis",
            },
            {
                "subTitle": "Pemeriksaan Fisik (O) & Uji Fungsi",
                "model": "pemeriksaanFisik",
            },
            {
                "subTitle": "Diagnosa Fungsional",
                "model": "diagnosaFungsional",
            },
            {
                "subTitle": "Catatan",
                "model": "catatan",
            },
            {
                "subTitle": "Rencana dan Terapi (p)",
                "model": "rencanaDanTerapi",
            },
        ]
    },
    {
        "title": "Suspek Penyakit Akibat Kerja",
        "type": "checkBox",
        "value": [
            {
                "subTitle": "Ya",
                "model": "suspekPenyakit",
            },
            {
                "subTitle": "Tidak",
                "model": "suspekPenyakit",
            }
        ]
    },
    {
        "title": "Diagnosa Medis",
        "type": "select",
        "value": [
            {
                "subTitle": "ICD 10",
                "model": "icd10",
            },
            {
                "subTitle": "ICD 9",
                "model": "icd9",
            }
        ]
    }
])

let more = ref([
    {
        "title": "",
        "type": "textarea",
        "value": [
            {
                "subTitle": "Home Program",
                "model": "homeProgram",
            },
            {
                "subTitle": "Edukasi",
                "model": "edukasi",
            },
            {
                "subTitle": "Evaluasi",
                "model": "evaluasi",
            },
            {
                "subTitle": "Penyakit Menular",
                "model": "penyakitMenular",
            },
        ],
    },
    {
        "title": "",
        "type": "textbox",
        "value": [
            {
                "subTitle": "Dirujuk / Konsul ke",
                "model": "dirujuk",
            },
        ]
    },
    {
        "title": "Hasil Skrining Gizi Awal",
        "type": "checkBox",
        "value": [
            {
                "subTitle": "Pasien beresiko Malnutrisi -> Assesment lanjut oleh Gizi",
                "model": "hasilSkrining",
            },
            {
                "subTitle": "Pasien tidak beresiko Malnutrisi",
                "model": "hasilSkrining",
            }
        ]
    }

])

// ===================== End Data Source ===================



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

const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const d_Diagnosa = ref([])
const d_Pegawai = ref([])
const d_Dokter = ref([])
const COLLECTION: any = ref('AssesmentRehabilitasiMedik') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    jam: new Date
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
    // if (NOREC_EMRPASIEN.value == '') return
    let response = await useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan 
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

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

const fetchDiagnosa = async (filter: any) => {
    let nama = filter.query ? `?name=${filter.query}` : ''
    const response = await useApi().get(`/emr/get-data-diagnosa${nama}`)
    d_Diagnosa.value = response
}

const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}
const fetchDokter = async (filter: any) => {

    // let nama = filter.query ? `?name=${filter.query}` : ''
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const setAutoFill = async () => {

    await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN +
        "&collection=VitalSign" +
        "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
    ).then((response) => {

        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
        input.value.tekananDarah = response.tekananDarah
        input.value.frekuensiNafas = response.pernapasan
        input.value.suhu = response.suhu
        input.value.frekuensiNada = response.nadi
    })
}

setAutoFill()
setView()

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


</script>

<style lang="scss">
h1.emr {
    font-weight: bold !important;
}

table.assesment {
    border-collapse: collapse;
    width: 100%;
}

table.assesment,
th,
.assesment td {
    border: 1px solid black;
}

.assesment th,
td {
    padding: 8px;
    vertical-align: middle !important;
    text-align: center !important;
}
</style>