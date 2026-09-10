<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3> Resume Medis</h3>
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
                                    :loading="isLoading || isLoadingPasien" @click="simpan()"> Simpan
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

                                    <div class="columns is-multiline p-3">

                                        <div class="column is-12">
                                            <VField label="Anamnesa">
                                                <VControl>
                                                    <VTextarea class="textarea" v-model="input.anamnesa" rows="2"
                                                        placeholder="Anamnesa ..." autocomplete="off" autocapitalize="off"
                                                        spellcheck="true" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField label="Pemeriksaan Fisik">
                                                <VControl>
                                                    <VTextarea class="textarea" v-model="input.pemeriksaanFisik" rows="2"
                                                        placeholder="Pemeriksaan Fisik ..." autocomplete="off"
                                                        autocapitalize="off" spellcheck="true" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField label="Pemeriksaan Penunjang">
                                                <VControl>
                                                    <VTextarea class="textarea" v-model="input.pemeriksaanPenunjang"
                                                        rows="2" placeholder="Pemeriksaan Penunjang ..." autocomplete="off"
                                                        autocapitalize="off" spellcheck="true" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField label="Diagnosa">
                                                <VControl>
                                                    <VTextarea class="textarea" v-model="input.diagonsa" rows="2"
                                                        placeholder="Diagnosa ..." autocomplete="off" autocapitalize="off"
                                                        spellcheck="true" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField label="Terapi">
                                                <VControl>
                                                    <VTextarea class="textarea" v-model="input.terapi" rows="2"
                                                        placeholder="Terapi ..." autocomplete="off" autocapitalize="off"
                                                        spellcheck="true" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField label="Rencana ">
                                                <VControl>
                                                    <VTextarea class="textarea" v-model="input.rencana" rows="2"
                                                        placeholder="Rencana  ..." autocomplete="off" autocapitalize="off"
                                                        spellcheck="true" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <div class="column is-4" style="margin-left: auto;">
                                                <h2 style="font-weight: bold;" class="mb-3">Dokter Pemeriksa</h2>
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:search">
                                                        <AutoComplete v-model="input.komiteMedis" :suggestions="d_Dokter"
                                                            @complete="fetchDokter($event)" :optionLabel="'label'"
                                                            :dropdown="true" :minLength="3" :appendTo="'body'"
                                                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            placeholder="Cari komite medis" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
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
import { h, reactive, ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';

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
const d_Dokter: any = ref([])
const isLoadingPasien: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
})

const COLLECTION: any = ref('ResumeMedis') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isDisabled = ref(false)

const loadRiwayat = () => {

    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length > 0) {
                isDisabled.value = false
                input.value = response[0] //set ke inputan 
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                } else {

                }
            } else {
                isDisabled.value = true
            }
        })
}

const simpan = () => {
    let object: any = {}
    let ID = input.value.id ? input.value.id : ''

    object = input.value //set inputan 
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
    useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const kembaliKeun = () => {
    window.history.back()
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const print = async () => {
    H.printBlade(`emr/cetak-resume-medis-lk?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
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
</style>
