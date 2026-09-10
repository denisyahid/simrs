<template>
    <div class="columns is-multiline" v-if="!modalInput">
        <div class="column is-12">
            <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3> {{ props.FORM_NAME }}</h3>
                        </div>
                        <div class="right">
                            <div class="right">
                                <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                    :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun" :isHideCetak="true">
                                </ButtonEmr>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>
        <div class="column is-12">
            <div class="columns is-multiline mt-5" v-if="isLoading">
             
                <div class="column is-12" v-for="key in 2" :key="key">
                    <VPlaceloadWrap>
                        <VPlaceload height="70px" width="25%" class="mx-2" rounded="sm" />
                        <VPlaceload height="70px" width="25%" class="mx-2" rounded="sm" />
                        <VPlaceload height="70px" width="25%" class="mx-2" rounded="sm" />
                        <VPlaceload height="70px" width="25%" class="mx-2" rounded="sm" />
                    </VPlaceloadWrap>
                </div>
            </div>
            <div class="columns is-multiline" v-else>

                <div class="column is-12" v-if="!dataTidakAda">
                    <TabView>
                        <TabPanel header="RHYTHM">
                            <div v-for="(chartOptions, index) in chartRhytm" :key="index" class="chart-container">
                                <highcharts :options="chartOptions"></highcharts>
                            </div>
                        </TabPanel>
                        <TabPanel header="BEAT">
                            <div v-for="(chartOptions, index) in chartBeat" :key="index" class="chart-container">
                                <highcharts :options="chartOptions"></highcharts>
                            </div>
                        </TabPanel>
                    </TabView>

                </div>

                <div class="column is-12" v-else>
                    <div class="page-placeholder">
                        <div class="placeholder-content">
                            <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                            <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                            <h3>{{ H.assets().notFound }}</h3>
                            <p class="is-larger">
                                {{ H.assets().notFoundSubtitle }}
                            </p>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
useHead({
    title: 'EKG - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
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
const modalInput: any = ref(false)
const isLoading: any = ref(false)
const listColor: any = ref(Object.keys(useThemeColors()))

const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    registrasi: props.registrasi
})
const COLLECTION: any = ref('EKGFukuda')
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date()
})

const chartBeat: any = ref([])
const chartRhytm: any = ref([])
const dataTidakAda: any = ref(false)
const dataSAVE:any =ref({})


const loadRiwayat = () => {
    let nocm = props.pasien.nocm //'00000184'
    nocm = nocm.padStart(12, "0");
    chartRhytm.value = []
    chartBeat.value = []
    let dataxml = ''
    dataSAVE.value = null
    isLoading.value = true
    // let dari = H.formatDate(new Date('2021-08-28 00:00'), 'YYYY-MM-DD%2000:00');
    let dari = H.formatDate(new Date(props.registrasi.tglregistrasi), 'YYYY-MM-DD%2000:00');
    let sampai = H.formatDate(new Date(props.registrasi.tglregistrasi), 'YYYY-MM-DD%2023:59');
    useApi().get('/emr/get-ecg-fukuda?nocm=' + nocm +
        '&dari=' + dari + '&sampai=' + sampai).then((response: any) => {
            isLoading.value = false
            let e: any = {
                data: JSON.parse(response)
            }
            dataSAVE.value =  JSON.parse(response)
            if (e.data == '') {
                dataTidakAda.value = true
                H.alert('error', 'Data EKG tidak ditemukan')
                return
            }
            dataxml = ''
            let datax = []
            for (let i = 0; i < e.data[0].rhythm.mdcEcgLeadS[0].digits.length; i++) {
                datax.push(e.data[0].rhythm.mdcEcgLeadS[0].digits[i])
            }

            datax.sort(function (a, b) {
                if (a < b) { return -1; }
                if (a > b) { return 1; }
                return 0;
            })

            let tambah = parseFloat(datax[0] < 0 ? datax[0] * -1 : datax[0])
            for (let i = 0; i < e.data[0].rhythm.mdcEcgLeadS[0].digits.length; i++) {
                let digits: any = parseFloat(e.data[0].rhythm.mdcEcgLeadS[0].digits[i]) + tambah
                dataxml = dataxml + ',' + parseInt(digits * parseFloat(e.data[0].rhythm.mdcEcgLeadS[0].scale), 16);
            }

            e.data[0].rhythm.mdcEcgLeadS.forEach((dataset: any, i: any) => {

                dataset.data = dataset.digits
                dataset.valueDecimals = 0
                let axesOptions = {
                    tickInterval: 0.5,
                    minorTicks: true,
                    minorTickInterval: 0.1,
                    gridLineWidth: 1,
                    gridLineColor: 'red',
                    labels: {
                        enabled: false
                    },
                    title: {
                        text: null
                    },

                };

                chartRhytm.value.push({
                    chart: {

                        scrollablePlotArea: {
                            minWidth: 20000,
                            scrollPositionX: 0
                        },
                        spacingBottom: 20,
                        scrollbar: {
                            enabled: true,
                        },


                    },
                    title: {
                        text: dataset.leadName.replace('MDC_ECG_LEAD_', 'LEAD '),
                        align: 'left',
                        margin: 0,
                        x: 30
                    },
                    credits: {
                        enabled: false
                    },
                    scrollbar: {
                        enabled: true
                    },
                    legend: {
                        enabled: false
                    },
                    xAxis: {
                        tickInterval: 0.5,
                        minorTicks: true,
                        minorTickInterval: 0.1,
                        gridLineWidth: 1,
                        gridLineColor: 'red',
                        labels: {
                            enabled: false
                        },
                        title: {
                            text: null
                        },
                        min: 0,
                        max: 15000,
                        scrollbar: {
                            enabled: true,
                        },
                    },
                    yAxis: axesOptions,
                    tooltip: {
                        enabled: true
                    },
                    series: [{
                        data: dataset.data,
                        name: dataset.leadName.replace('MDC_ECG_LEAD_', 'LEAD '),
                        type: dataset.type,
                        color: '#434348',
                        fillOpacity: 0.3,
                        tooltip: {
                            valueSuffix: ' ' + dataset.unit
                        }
                    }]
                });
            })
            e.data[0].beat.mdcEcgLeadS.forEach((dataset: any, i: any) => {
                dataset.data = dataset.digits
                dataset.valueDecimals = 0
                let axesOptions = {
                    tickInterval: 0.5,
                    minorTicks: true,
                    minorTickInterval: 0.1,
                    gridLineWidth: 1,
                    gridLineColor: 'red',
                    labels: {
                        enabled: false
                    },
                    title: {
                        text: null
                    },

                };
                chartBeat.value.push({
                    chart: {
                        marginLeft: 40, 
                        spacingTop: 20,
                        spacingBottom: 20,
                        scrollablePlotArea: {
                            minWidth: 5000,
                            scrollPositionX: 0
                        },
                        scrollbar: {
                            enabled: true,
                        },
                    },
                    title: {
                        text: dataset.leadName.replace('MDC_ECG_LEAD_', 'LEAD '),
                        align: 'left',
                        margin: 0,
                        x: 30
                    },
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled: false
                    },
                    xAxis: {
                        tickInterval: 0.5,
                        minorTicks: true,
                        minorTickInterval: 0.1,
                        gridLineWidth: 1,
                        gridLineColor: 'red',
                        labels: {
                            enabled: false
                        },
                        title: {
                            text: null
                        },
                        min: 0,
                        max: 1300,
                        scrollbar: {
                            enabled: true,
                        },
                    },
                    yAxis: axesOptions,
                    tooltip: {
                        enabled: true
                    },
                    series: [{
                        data: dataset.data,
                        name: dataset.leadName.replace('MDC_ECG_LEAD_', 'LEAD '),
                        type: dataset.type,
                        color: '#434348',
                        fillOpacity: 0.3,
                        tooltip: {
                            valueSuffix: ' ' + dataset.unit
                        }
                    }]
                });
            })
        })
}


const kembaliKeun = () => {
    modalInput.value = false
    input.value = {
        tanggal: new Date()
    }
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)

    object.ekg = dataSAVE.value

    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'data': object
    }
    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            loadRiwayat()
            modalInput.value = false
        }).catch((e: any) => {
            isLoading.value = false
        })

}


onMounted(() => {

});

loadRiwayat()
</script>
<style  lang="scss">
@import '/@src/scss/abstracts/all';

.list-widget {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    ul {
        li {
            &:not(:last-child) {
                margin-bottom: 12px;
            }

            a {
                font-family: let(--font);
                display: flex;
                justify-content: space-between;
                color: let(--light-text);

                &:hover,
                &:focus {
                    color: let(--primary);
                }
            }
        }
    }
}

.is-dark {
    .list-widget {
        @include vuero-card--dark;

        ul {
            li {
                a {
                    &:hover {
                        color: let(--primary);
                    }
                }
            }
        }
    }
}

.widget-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;

    .left {
        display: flex;
        align-items: center;
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .right {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        .tag {
            font-family: let(--font);
        }

        .right-icon {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 32px;
            width: 32px;
            min-width: 32px;
            border-radius: let(--radius-rounded);
            color: let(--light-text-light-12);
            transition: all 0.3s; // transition-all test

            &.has-indicator {
                &::after {
                    content: '';
                    position: absolute;
                    top: 3px;
                    right: 4px;
                    height: 10px;
                    width: 10px;
                    border-radius: let(--radius-rounded);
                    background: let(--secondary);
                    border: 1.8px solid let(--white);
                }
            }

            svg {
                height: 18px;
                width: 18px;
                transition: stroke 0.3s;
            }
        }
    }

    h3 {
        font-family: let(--font-alt);
        font-size: 0.9rem;
        color: let(--dark-text);
        font-weight: 600;

        &.is-bigger {
            font-size: 1rem;
        }
    }

    .action-icon {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 32px;
        width: 32px;
        min-width: 32px;
        border-radius: let(--radius-rounded);
        color: let(--light-text-light-12);
        transition: all 0.3s; // transition-all test

        svg {
            height: 18px;
            width: 18px;
            transition: stroke 0.3s;
        }
    }
}

.is-dark {
    .widget-toolbar {
        h3 {
            color: let(--dark-dark-text);
        }

        .right {
            .right-icon {
                &.has-indicator {
                    &::after {
                        border-color: let(--dark-sidebar-light-6);
                    }
                }
            }
        }
    }
}

small.is-tanggal {
    color: let(--light-text);
}

.is-dark-text {
    color: let(--light-text);
}
</style>