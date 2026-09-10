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
                            <VButton type="button" rounded outlined :disabled="!NOREC_EMRPASIEN ? true : false"
                                color="warning" raised icon="lnir lnir-printer" @click="print()"> Cetak
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
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Standar Diagnosa Keperawatan Indonesia (SDKI)">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Definisi</h1>
                                    <h1>Penurunan sirkulasi darah pada level kapiler yang dapat mengganggu metabolisme
                                        tubuh. </h1>
                                </div>

                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Berhubungan dengan :</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6" v-for="(data, i) in Hubungan">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Lainnya"
                                                        v-model="input.Lainnya" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Data Subjektif :</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-3" v-for="(data, i) in dataSubjektif">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-8">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Data Subjektif Lainnya"
                                                        v-model="input.subjektifLainnya" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Data Objektif:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6" v-for="(data, i) in dataObjektif">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-8">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Data Objektif Lainnya"
                                                        v-model="input.objektifLainnya" />
                                                </VControl>
                                            </VField>
                                        </div>

                                    </div>
                                </div>
                                <div class="column is-12">
                                    <h1 class="pb-1" style="font-weight: bold;"> Tanda Tanda Vital</h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-2">
                                            <VField label="TD"></VField>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="Tekanan Darah"
                                                        v-model="input.tekananDarah" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>mmHG</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-2">
                                            <VField label="HR"></VField>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="HR" v-model="input.HR" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>bpm</VButton>
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-2">
                                            <VField label="RR"></VField>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="RR" v-model="input.RR" />
                                                </VControl>

                                            </VField>
                                        </div>
                                        <div class="column is-2">
                                            <VField label="Suhu"></VField>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="Suhu"
                                                        v-model="input.suhu" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>°C </VButton>
                                                </VControl>

                                            </VField>
                                        </div>

                                        <div class="column is-2">
                                            <VField label="SpO2"></VField>
                                            <VField addons>
                                                <VControl expanded>
                                                    <VInput type="text" class="input" placeholder="SpO2"
                                                        v-model="input.SPO2" />
                                                </VControl>

                                            </VField>
                                        </div>

                                    </div>
                                </div>

                                <div class="columns is-12 pt-2">
                                    <div class="column is-4 pt-4">
                                        <h1 style="font-weight: bold;"> Pemeriksaan Penunjang </h1>
                                    </div>
                                    <div class="column is-6" style="margin-left: -8rem;">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Pemeriksaan Penunjang"
                                                    v-model="input.pemeriksaanPenunjang" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>

                            </Fieldset>
                        </div>
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Standar Luar Keperawatan Indonesia (SLKI)">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold">
                                        Perfusi perifer (L.02011)
                                    </h1>
                                    <h1 style="font-weight: bold">
                                        Keadekuatan aliran darah :
                                    </h1>

                                </div>
                                <div class="column is-12">
                                    <h1 style="font-weight: bold">
                                        Kriteria Hasil :
                                    </h1>
                                </div>
                                <div class="column is-12">
                                    <h1 class="pb-1">
                                        1. Kekuatan nadi perifer : Meningkat
                                    </h1>
                                    <h1 class="pb-1">
                                        2. Warna kulit : tidak pucat
                                    </h1>
                                    <h1 class="pb-1">
                                        3. Pengisian kapiler : Membaik
                                    </h1>
                                    <h1 class="pb-1">
                                        4. Nilai CRT : kurang dari 3 detik
                                    </h1>
                                    <div class="column is-8">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Kriteria Hasil Lainnya"
                                                    v-model="input.kriteriaHasil" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>



                            </Fieldset>
                        </div>
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Standar Intervensi Keperawatan Indonesia (SIKI)">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Perawatan sirkulasi
                                        (SIKI,I.02079 hal: 345)</h1>
                                </div>
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Observasi:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6" v-for="(data, i) in Observasi">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4 mr-3">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Keterangan Observasi"
                                                        v-model="input.keteranganObservasi" />
                                                </VControl>
                                            </VField>
                                        </div>

                                    </div>
                                </div>
                               
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Terapeutik:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6" v-for="(data, i) in Terapiutik">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12 mr-3">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Keterangan Terapeutik"
                                                        v-model="input.keteranganTerapeutik" />
                                                </VControl>
                                            </VField>
                                        </div>

                                    </div>
                                </div>
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Edukasi:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6" v-for="(data, i) in Edukasi">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12 mr-3">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Keterangan Lainnya"
                                                        v-model="input.keteranganEdukasi" />
                                                </VControl>
                                            </VField>
                                        </div>

                                    </div>
                                </div>





                            </Fieldset>
                        </div>
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Evaluasi">
                                <h1 style="font-weight: bold;">Masalah teratasi:</h1>
                                <div class="columns is-multiline">
                                    <div class="column is-6">
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <h1 class="pt-4" style="font-weight: bold;">Tanggal dan Jam</h1>
                                                <VField>
                                                    <VDatePicker v-model="input.tanggalSelesai" mode="dateTime"
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
                                            </div>
                                            <div class="column is-12">
                                                <h1 class="p-0" style="font-weight: bold;">Perawat</h1>
                                                <VField>
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="input.perawat" :suggestions="d_pegawai"
                                                            @complete="fetchDokter($event)" :optionLabel="'label'"
                                                            :dropdown="true" :minLength="3" :appendTo="'body'"
                                                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            placeholder="Perawat..." class="mt-2"
                                                            @item-select="setTandaTangan($event)" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-6 is-pulled-right">
                                        <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                                    </div>
                                </div>
                            </Fieldset>
                        </div>
                    </div>
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
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import * as EMR from '../page-emr-plugins/perfusi-perifer-tak-efektif'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let Hubungan = ref(EMR.Hubungan())
let dataObjektif = ref(EMR.dataObjektif())
let dataSubjektif = ref(EMR.dataSubjektif())
let Observasi = ref(EMR.Observasi())
let Edukasi = ref(EMR.Edukasi())
let Terapiutik = ref(EMR.Terapiutik())


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
        COLLECTION: ''
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_pegawai: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('PerfusiPeriferTidakEfektif') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const isDisabled: any = ref(false)
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
                if (response[0].tandaTanganPerawat) {
                    H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
                }

                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                } else { }
            } else {
                isDisabled.value = true
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
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

const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    ).then((response) => {
        if (response) {
            input.value.IMT = response.IMT ? response.IMT : ''
            input.value.tekananDarah = response.tekananDarah ? response.tekananDarah : ''
            input.value.RR = response.pernapasan ? response.pernapasan : ''
            input.value.suhu = response.suhu ? response.suhu : ''
            input.value.HR = response.nadi ? response.nadi : ''
            input.value.SPO2 = response.SPO2 ? response.SPO2 : ''
        }
    })
}

const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_pegawai.value = response
}

const print = async () => {
    H.printBlade(`emr/cetak-pola-nafas-tidak-efektif?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}
const setTandaTangan = async (e: any) => {
    const response = await useApi().get(
        `/emr/tanda-tangan/${e.value.value}`)
    if (response != null) {
        H.tandaTangan().set("signature_1", response.ttd)
        input.value.tandaTanganPerawat = response.ttd
    } else {
        H.tandaTangan().set("signature_1", '')
    }
}

getDataExist()
setView()
loadRiwayat()
</script>
