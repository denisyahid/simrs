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


    <div class="column">
        <VCard>
            <h1 style="font-weight: bold;">Intra Anestesi / Sedasi</h1>
            <div class="column" style="overflow: auto;">
                <table class="tg">
                    <thead>
                        <tr>
                            <th width="10%" class="col-stuck">Waktu</th>
                            <th v-for="index in jumlahIndex">
                                <VField>
                                    <VControl>
                                        <VInput v-model="input['waktu_' + index]" type="time" placeholder="Pick an hour" />
                                    </VControl>
                                </VField>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-stuck bg-colatas">
                                <span>Cairan Infus (ml)</span>
                            </td>
                            <td v-for="index in jumlahIndex">
                                <VField>
                                    <VControl>
                                        <VInput v-model="input['cairainInfus_' + index]" type="text" />
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-stuck bg-colatas">
                                <span>Darah Masuk (ml)</span>
                            </td>
                            <td v-for="index in jumlahIndex">
                                <VField>
                                    <VControl>
                                        <VInput v-model="input['darahMasuk_' + index]" type="text" />
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-stuck bg-colatas">
                                <span>Urin (ml)</span>
                            </td>
                            <td v-for="index in jumlahIndex">
                                <VField>
                                    <VControl>
                                        <VInput v-model="input['urin_' + index]" type="text" />
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-stuck bg-colatas">
                                <span>Pendarahan(ml)</span>
                            </td>
                            <td v-for="index in jumlahIndex">
                                <VField>
                                    <VControl>
                                        <VInput v-model="input['pendarahan_' + index]" type="text" />
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="columns is-multiline p-3" style="margin-top: 1rem;">
                <div class="column is-3" v-for="(data) in pemantauan">
                    <span>{{ data.label }}</span>
                    <VField class="p-0 mt-3">
                        <VControl>
                            <VInput v-model="input[data.model]" type="time" placeholder="Pick an hour" />
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="columns is-multiline p-3">
                <div class="column is-6" v-for="(duration) in durations">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ duration.label }}</h1>
                    <div class="is-flex" v-for="(time) in duration.values">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1> {{ time.name }} : </h1>
                        </div>
                        <div class="column is-5" style="margin-left: -3rem;">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" :placeholder="time.name"
                                        v-model="input[time.model]" />
                                </VControl>
                            </VField>
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
import Calendar from 'primevue/calendar';
import Chart from 'primevue/chart';
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/pemantauan'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let pemantauan = ref(EMR.pemantauan())
let durations = ref(EMR.durations())


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
const jumlahIndex = ref(15)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const chartData = ref();
const chartOptions = ref();
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
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
  
<style lang="scss">
.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150%;
    // width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;

    // font-size: 14px;
    overflow: hidden;
    padding: 7px;
    word-break: normal;
}

.tg tr {
    height: 20px;
}

.tg th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    vertical-align: middle;
    // font-size: 14px;
    text-align: center !important;
    font-weight: bold;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.col-stuck {
    width: 150px;
    position: sticky;
    left: 0;
    z-index: 2;
    background-color: aliceblue;
    vertical-align: inherit;
}

.custom-fieldset {
    border: 1px solid;
    border-radius: 5px;
    border-color: var(--fade-grey-dark-2);
}

.custom-legenda {
    margin-left: 15px;
    font-weight: 500;
}
</style>
  