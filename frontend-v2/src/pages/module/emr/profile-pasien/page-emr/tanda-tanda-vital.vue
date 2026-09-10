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
            <h1 style="font-weight: bold;">Tanda-Tanda Vital</h1>
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
                                <span>Suhu</span>
                            </td>
                            <td v-for="index in jumlahIndex">
                                <VField>
                                    <VControl>
                                        <VInput v-model="input['suhu_' + index]" type="text" />
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-stuck bg-colatas">
                                <span>Pernafasan</span>
                            </td>
                            <td v-for="index in jumlahIndex">
                                <VField>
                                    <VControl>
                                        <VInput v-model="input['pernafasan_' + index]" type="text" />
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-stuck bg-colatas">
                                <span>Nadi</span>
                            </td>
                            <td v-for="index in jumlahIndex">
                                <VField>
                                    <VControl>
                                        <VInput v-model="input['nadi_' + index]" type="text" />
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-stuck bg-colatas">
                                <span>Tekanan Darah</span>
                            </td>
                            <td v-for="index in jumlahIndex">
                                <VField>
                                    <VControl>
                                        <VInput v-model="input['tekananDarah_' + index]" type="text" />
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-stuck bg-colatas">
                                <span>Saturasi02</span>
                            </td>
                            <td v-for="index in jumlahIndex">
                                <VField>
                                    <VControl>
                                        <VInput v-model="input['saturasi02_' + index]" type="text" />
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </VCard>
    </div>
    <div class="column">
        <VCard>
            <h1 style="font-weight: bold;" class="text-center">Grafik Tanda Vital</h1>
            <div class="column">
                <div class="column is-12">
                    <VCard style="border-radius: 16px;">
                        <highcharts :options="chartOptions2"></highcharts>
                    </VCard>
                </div>
            </div>
        </VCard>
    </div>
    <div class="column">
        <VCard>
            <h1 style="font-weight: bold;" class="text-center">Grafik Tekanan Darah</h1>
            <div class="column">
                <div class="column is-12">
                    <VCard style="border-radius: 16px;">
                        <highcharts :options="chartOptions4"></highcharts>
                    </VCard>
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
                chartHigh(response[0])
                chartHigh2(response[0])
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



let chartOptions2 = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
                // style: {
                //   fontSize: '20px'
                // },
            },
            enableMouseTracking: false
        }
    },
    series: []
});
let chartOptions4 = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
                // style: {
                //   fontSize: '20px'
                // },
            },
            enableMouseTracking: false
        }
    },
    series: []
});

const chartHigh = (e: any) => {
    let labels = []
    let seriesNadi = []
    let seriesSuhu = []
    let seriesPernafasan = []
    let seriesSaturasi = []
    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndex.value; x++) {
        if (e['suhu_' + x.toString()] != undefined) {
            seriesSuhu.push(parseFloat(e['suhu_' + x.toString()]))
        }
        if (e['nadi_' + x.toString()] != undefined) {
            seriesNadi.push(parseFloat(e['nadi_' + x.toString()]))
        }
        if (e['pernafasan_' + x.toString()] != undefined) {
            seriesPernafasan.push(parseFloat(e['pernafasan_' + x.toString()]))
        }
        if (e['saturasi02_' + x.toString()] != undefined) {
            seriesSaturasi.push(parseFloat(e['saturasi02_' + x.toString()]))
        }
    }

    chartOptions2.xAxis.categories = labels
    chartOptions2.series =
        [{
            name: 'Suhu',
            color: 'red',
            lineWidth: 4,
            marker: {
                radius: 4
            },
            data: seriesSuhu
        }, {
            name: 'Nadi',
            color: 'blue',
            data: seriesNadi
        }, {
            name: 'Pernafasan',
            data: seriesPernafasan
        },
        {
            name: 'Saturasi02',
            data: seriesSaturasi
        },],
        console.log(chartOptions2)
}

const chartHigh2 = (e: any) => {
    let labels = []
    let seriesDiastolik = []
    let seriesSistolik = []
    let seriesPernafasan = []
    let seriesSaturasi = []

    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndex.value; x++) {
        if (e['tekananDarah_' + x.toString()] != undefined) {
            let inputString = e['tekananDarah_' + x.toString()];
            let resultArray = inputString.split('/').map(Number);
            seriesSistolik.push(parseFloat(resultArray[0]))
            seriesDiastolik.push(parseFloat(resultArray[1]))
        }
    }

    chartOptions4.xAxis.categories = labels
    chartOptions4.series =
        [{
            name: 'Sistolik',
            color: 'red',
            lineWidth: 4,
            marker: {
                radius: 4
            },
            data: seriesSistolik
        }, {
            name: 'Diastolik',
            color: 'blue',
            data: seriesDiastolik
        },],
        console.log(chartOptions2)
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
  