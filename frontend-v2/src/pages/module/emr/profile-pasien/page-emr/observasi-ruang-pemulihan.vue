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
        <div class="columns is-multiline">
            <div class="column is-12">
                <VCard>
                    <h1 style="font-weight: bold; margin-bottom: 1.5rem;">Observasi Di Ruang Pemulihan (Recovery Room)
                    </h1>
                    <div class="columns is-multiline">
                        <div class="column">
                            <div class="column is-4">
                                <span class="label-apb">Masuk Ruang Pemulihan Pukul</span>
                                <VField class="pt-2">
                                    <VControl raw subcontrol>
                                        <VInput type="time" v-model="input.time"></VInput>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column  pb-0 pt-0" v-for="(data) in checklist">
                            <div class="columns is-multiline p-3">
                                <div class="column">
                                    <span class="label-apb">{{ data.label }}</span>
                                    <VField addons class="pt-3">
                                        <VControl expanded>
                                            <VInput type="text" class="input" v-model="input[data.model]"
                                                :placeholder="data.satuan" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ data.satuan }}</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline mt-4 pl-3">
                        <div class="column is-3" v-for="(datas) in checkbox">
                            <span class="label-apb">{{ datas.label }}</span>
                            <div class="columns is-multiline pt-3">
                                <div class="column is-12" v-for="(data) in datas.values">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox :true-value="data.value" :label="data.value" class="p-0"
                                                color="primary" square v-model="input[data.model]" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline pl-3">
                        <div class="column is-6">
                            <span class="label-apb">Konversi Anestesi</span>
                            <div class="columns is-multiline pt-3">
                                <div class="column is-12">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox true-value="ya" label="Ya" class="p-0" color="primary" square
                                                v-model="input.konversiAnestesi" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VInput type="text" class="mt-3" v-model="input.isiKonversiAnestesi"></VInput>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox true-value="tidak" label="Tidak" class="p-0" color="primary" square
                                                v-model="input.konversiAnestesi" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <span class="label-apb">Diagnosa</span>
                            <div class="column is-12 pt-3">
                                <VField>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="item.diagnosa" :suggestions="d_Diagnosa"
                                            @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari Diagnosa..." class="mt-2" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline pl-3">
                        <div class="column" style="overflow: auto;">
                            <table class="tg">
                                <thead>
                                    <tr>
                                        <th width="10%" class="col-stuck">Waktu</th>
                                        <th v-for="index in jumlahIndex">
                                            <VField>
                                                <VControl>
                                                    <VInput v-model="input['waktu_' + index]" type="time"
                                                        placeholder="Pick an hour" />
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
                    </div>
                </VCard>
            </div>
            <div class="column is-12">
                <VCard>
                    <h1 style="font-weight: bold;" class="text-center">Grafik Tanda Vital</h1>
                    <div class="column">
                        <div class="column is-12">
                            <VCard style="border-radius: 16px;">
                                <highcharts :options="chartOptions1"></highcharts>
                            </VCard>
                        </div>
                    </div>
                </VCard>
            </div>
            <div class="column is-12">
                <VCard>
                    <h1 style="font-weight: bold;" class="text-center">Grafik Tekanan Darah</h1>
                    <div class="column">
                        <div class="column is-12">
                            <VCard style="border-radius: 16px;">
                                <highcharts :options="chartOptions2"></highcharts>
                            </VCard>
                        </div>
                    </div>
                </VCard>
            </div>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/observasi-ruang-pemulihan'
const jumlahIndex = ref(15)




let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const d_Diagnosa: any = ref([])
let checklist = ref(EMR.checklist())
let checkbox = ref(EMR.checkbox())


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
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
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


let chartOptions1 = reactive({
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

    chartOptions1.xAxis.categories = labels
    chartOptions1.series =
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

    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndex.value; x++) {
        if (e['tekananDarah_' + x.toString()] != undefined) {
            let inputString = e['tekananDarah_' + x.toString()];
            let resultArray = inputString.split('/').map(Number);
            seriesSistolik.push(parseFloat(resultArray[0]))
            seriesDiastolik.push(parseFloat(resultArray[1]))
        }
    }

    chartOptions2.xAxis.categories = labels
    chartOptions2.series =
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
          console.log(NOREC_EMRPASIEN.value)
        })
}

const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
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
    //   console.log(JSON.stringify(response.norec_emr))
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }

const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {
    await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN +
        "&collection=VitalSign" +
        "&field=tekananDarah,pernapasan,SPO2,nadi"
    ).then((response) => {
        if (response.length) {
            input.value.rr = response.pernapasan
            input.value.SpO2 = response.SPO2
            input.value.n = response.nadi
            input.value.td = response.tekananDarah
        }
    })
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