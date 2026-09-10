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

    <div class="column is-12">
        <VCard>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Dokter Bedah </h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.dokterBedah" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Asisten Bedah I </h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.asistenBedahI" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Asisten Bedah II </h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.asistenBedahII" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Dokter Anestesi</h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.dokterAnestesi" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Asisten Anestesi</h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.asistenAnestesi" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Instrumenter</h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.instrumenter" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosa Pra-Bedah </h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.diagnosaPraBedah" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Diagnosa" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosa Pasca-Bedah </h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.diagnosaPascaBedah" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Diagnosa" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Operasi </h1>
                        <VField>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Nama Operasi"
                                        v-model="input.namaOperasi" />
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                </div>

                <div class="column is-12" v-for="(datas, i) in laporan">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column is-3" v-for="(data, b) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" style="margin-left: -4rem;">
                                <VControl raw subcontrol>
                                    <input v-model="input[data.model + b]" class="input p-0" />
                                </VControl>
                            </VField>
                        </div>

                    </div>


                </div>
                <div class="column is-6">
                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Jaringan yang di eksisi/insisi </h1>
                    <VField>
                        <VControl>
                            <VInput type="text" class="input" placeholder="Jaringan" v-model="input.jaringanEksisi" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal Operasi </h1>
                            <VField>
                                <VDatePicker v-model="input.tglOperasi" mode="dateTime" style="width: 100%" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jam Operasi Mulai</h1>
                            <VDatePicker v-model="input.jamStartOperasi" color="green" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jam Operasi Berakhir</h1>
                            <VDatePicker v-model="input.jamEndOperasi" color="green" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Lama Operasi</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Durasi Operasi"
                                        v-model="input.lamaOperasi" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-8">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Laporan Operasi</h1>
                            <VControl>
                                <VTextarea class="textarea" v-model="input.laporan" rows="2"
                                    placeholder="Catatan" autocomplete="off" autocapitalize="off" spellcheck="true" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jam Penulisan Laporan</h1>
                            <VDatePicker v-model="input.jamPenulisanLaporan" color="green" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                </div>

            </div>
        </VCard>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/laporan-operasi'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let laporan = ref(EMR.laporan())

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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const d_Diagnosa: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    jamStartOperasi: new Date(),
    jamEndOperasi: new Date(),
    tglOperasi: new Date(),
    jamPenulisanLaporan: new Date()
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}
const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
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
const setAutoFill = async () => {

}
setView()
setAutoFill()
loadRiwayat()
</script>
