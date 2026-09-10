<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
            <VCard v-else>
                <div class="column is-12">
                    <VCard class="border-card pink">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <h1 class="emr">Diagnosa Medis</h1>
                                <VField class="mt-3">
                                    <VControl>
                                        <VTextarea v-model="input.diagnosaMedis" rows="6">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <h1 class="emr">Diagnosa Gizi</h1>
                                <div class="column is-12">
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.diagnosaGizi_1" :suggestions="d_Diagnosa"
                                                @complete="fetchDiagnosa($event)" :optionLabel="'nama'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'nama'" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12 pt-0 mt-1">
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.diagnosaGizi_2" :suggestions="d_Diagnosa"
                                                @complete="fetchDiagnosa($event)" :optionLabel="'nama'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'nama'" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12 pt-0 mt-1">
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.diagnosaGizi_3" :suggestions="d_Diagnosa"
                                                @complete="fetchDiagnosa($event)" :optionLabel="'nama'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'nama'" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </VCard>

                </div>

                <div class="column is-12">
                    <VCard class="border-card pink">
                        <div class="column is-12">
                            <h1 class="emr">Biokimia</h1>
                            <VField class="mt-3">
                                <VControl>
                                    <VTextarea v-model="input.Biokimia" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <h1 class="emr">Klinik/Fisik</h1>
                            <VField class="mt-3">
                                <VControl>
                                    <VTextarea v-model="input.fisik" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </VCard>
                </div>

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="ASESMEN GIZI/PENGKAJIAN">
                        <div class="columns is-multiline">
                            <div class="column" v-for="(data) in asesmenGizi">
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" :placeholder="data.title"
                                            v-model="input[data.model]" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>{{ data.satuan }}</VButton>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </Fieldset>
                </div>

                <div class="column is-12" v-for="(datas) in more">
                    <Fieldset :toggleable="true" :legend="datas.title">
                        <div class="column is-12" v-for="(data) in datas.detail">
                            <h1 class="emr">{{ data.subTitle }}</h1>
                            <VField class="mt-3">
                                <VControl>
                                    <VTextarea v-model="input[data.model]" rows="3" :placeholder="datas.title + '...'">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </Fieldset>
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
import Fieldset from 'primevue/fieldset';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import AutoComplete from 'primevue/autocomplete';
import { useUserSession } from '/@src/stores/userSession'


// ====================== List ======================

let asesmenGizi = ref([
    {
        "title": "BB",
        "model": "BB",
        "satuan": "Kg",
    },
    {
        "title": "TB",
        "model": "TB",
        "satuan": "Cm",
    },
    {
        "title": "IMT",
        "model": "IMT",
        "satuan": "Kg/Km2",
    },
    {
        "title": "Tinggi Lutut",
        "model": "TL",
        "satuan": "Cm",
    },
    {
        "title": "LLA",
        "model": "LLA",
        "satuan": "Cm",
    },
])

let more = ref([
    {
        "title": "Riwayat Gizi",
        "detail": [
            {
                "subTitle": "Pola Makan",
                "model": "polaMakan",
            },
            {
                "subTitle": "Asupan Makan",
                "model": "asupanMakan",
            },
        ]
    },
    {
        "title": "Riwayat Personal",
        "detail": [
            {
                "subTitle": "",
                "model": "riwayatPersonal",
            },
        ]
    },
    {
        "title": "Diagnosa Gizi/Masalah",
        "detail": [
            {
                "subTitle": "",
                "model": "diagnosaGiziAtauMasalah",
            },
        ]
    },
    {
        "title": "Intervensi Gizi",
        "detail": [
            {
                "subTitle": "",
                "model": "intervensiGizi",
            },
        ]
    },
    {
        "title": "Monitoring dan Evaluasi",
        "detail": [
            {
                "subTitle": "",
                "model": "monitoring&Evaluasi",
            },
        ]
    },
])


// ====================== End List ==================




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
const d_Diagnosa: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('AsesmenAwalGizi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
    // if (NOREC_EMRPASIEN.value == '') return
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
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

const print = async () => {
    H.printBlade(`emr/cetak-asesmen-gizi-awal?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
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


setView()
</script>
