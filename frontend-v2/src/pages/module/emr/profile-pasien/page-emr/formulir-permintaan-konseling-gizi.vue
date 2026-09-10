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
                                :disabled="isDisabled" @click="print"> Cetak
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
                        <div class="columns is-multiline">
                            <div class="column is-2 mt-5">
                                <h1 style="font-weight: bold;"> Yth. Ahli Gizi </h1>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold;">Tanggal</h1>
                                <div class="columns pt-3 pb-0">
                                    <div class="column is-12">
                                        <VField>
                                            <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%"
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
                                    </div>

                                </div>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;">Dokter Penanggung Jawab</h1>
                                <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                        <AutoComplete v-model="input.dokter" :suggestions="d_dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="ketik untuk mencari..." />
                                    </VControl>
                                </VField>
                            </div>
                        </div>



                    </div>
                    <div class="column is-12 mt-3">
                        <div class="columns is-multiline">

                            <h1 class="ml-3" style="font-weight: bold;"> Mohon Dilakukan : </h1>
                            <div class="column is-4" style="margin-top: -2rem;">
                                <VField>
                                    <VControl>
                                        <VRadio v-model="input.konselingGizi" value="Ya" label="Konseling Gizi"
                                            name="nyeriAkut" color="info" square style="font-size: 15px" />

                                    </VControl>
                                </VField>
                            </div>

                        </div>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            Hasil Pemeriksaan Laboratorium / Pemeriksaan Klinik Penting
                        </h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.hasilLaboratorium" rows="2">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            Diagnosa Medis
                        </h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.namadiagnosa" rows="2">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            Pengobatan penting
                        </h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.pengobatanPenting" rows="2">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            Diet yang dianjurkan
                        </h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.diet" rows="2">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            PENDAPAT AHLI GIZI
                        </h1>
                    </div>
                    <div class="column is-12 p-0 ml-3">
                        <h1 style="font-weight: bold;">
                            Pengkajian Gizi :
                        </h1>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            A. Antropometri
                        </h1>
                        <div class="columns is-multiline">
                            <div class="column is-2">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> BB :</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.beratBadan" placeholder="" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> TB :</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.tinggiBadan" placeholder="" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> LLA :</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.lingkarPerut" placeholder="" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> IMT :</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.imt" placeholder="" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Perubahan BB :</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.perubahanBB" placeholder="" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            B. Biokimia :
                        </h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.biokimia" rows="2">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            C. Fisik/ Klinik :
                        </h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.fisik" rows="2">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            D. Riwayat Gizi :
                        </h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.riwayatGizi" rows="2">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            E. Riwayat Personal :
                        </h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.riwayatPersonal" rows="2">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            Diagnosis Gizi :
                        </h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.diagnosisGizi" rows="2">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            Intervensi :
                        </h1>
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">A. Tujuan :</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.tujuan" placeholder="" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> B. Intervensi :</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.intervensi" placeholder="" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> C. Konseling Gizi/ Edukasi :</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.konselingGiziEdukasi" placeholder="" />
                                    </VControl>
                                </VField>
                            </div>

                        </div>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">
                            Rencana Monitoring dan Evaluasi Gizi :
                        </h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.rencanaMonitoring" rows="2">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-5">
                        <VCard>
                            <h1 class="mb-3 emr">Pelaksana I</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.pelaksana" :suggestions="d_dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="ketik Nama Dokter Pemeriksa" />
                                </VControl>
                            </VField>
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
import { h, reactive, ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import MultiSelect from 'primevue/multiselect';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
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
const isDisabled: any = ref(false)
const d_dokter: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('FormulirPermintaanKonselingGizi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date(),
})
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
            if (response.length > 0) {
                isDisabled.value = false
                input.value = response[0] //set ke inputan 
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                } else { }
            } else {
                isDisabled.value = true
            }
        })
}
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_dokter.value = response
}

const diagnosa = async () => {
    await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
        input.value.namadiagnosa = response[0].namadiagnosa
    })
}

const dokterDPJP = async () => {
    await useApi().get(`emr/get-dokter-dpjp?nocmfk=${ID_PASIEN}`).then((response) => {
        input.value.dokter = response.dokter
    })
}

const getDataExist = async () => {
    await useApi().get(
        `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    ).then((response) => {
        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
        input.value.IMT = response.IMT
        console.log()
    })
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
            loadRiwayat()
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const kembaliKeun = () => {
    window.history.back()
}

const print = async () => {
    H.printBlade(`emr/cetak-formulir-permintaan-konseling-gizi?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

setView()
getDataExist()
diagnosa()
dokterDPJP()
loadRiwayat()
</script>