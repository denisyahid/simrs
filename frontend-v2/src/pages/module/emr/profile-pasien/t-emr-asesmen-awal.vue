<template>
    <VCard radius="rounded">
        <div class="columns column">
            <h3 class="title is-5 mb-2 mr-1">Asesmen Awal </h3>
        </div>
        
        <div class="columns is-multiline">
            <div class="column is-6 is-offset-6  p-0">
                <!-- <VIconButton circle color="success" class="mr-2 is-pulled-right" icon="fas fa-search" raised bold
                    @click="fetchAsesmen()">
                </VIconButton> -->
                <UIWidget class="search-widget" style="border:none;padding: 0;">
                    <template #body>
                        <div class="field" style="padding: 2px">
                            <div class="control">
                                <input v-model="filters" class="input custom-text-filter" placeholder="Filter" />
                                <button class="searcv-button">
                                    <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </UIWidget>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline ">
                    <!-- <div class="column is-8">
                        <VCard class="mb-5-min" style="background-color: #E9ECEF;">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                </div>
                            </div>
                        </VCard>
                    </div>
                    <div class="column is-4">
                        <VCard class="mb-5-min" style="background-color: #E9ECEF;">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                </div>
                            </div>
                        </VCard>
                    </div> -->
                    <!-- <div class="column is-12 mt-5-min" v-if="dataAsesmen.length == 0">
                                    <VCard>
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <div class="page-placeholder">
                                                    <div class="placeholder-content">
                                                        <img class="light-image" style=" max-width: 340px;"
                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                        <img class="dark-image" style=" max-width: 340px;"
                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                        <h3>{{ H.assets().notFound }}</h3>
                                                        <p class="is-larger">
                                                            {{ H.assets().notFoundSubtitle }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </VCard>
                                </div> -->
                    <!-- v-else -->
                    <div class="column is-12" :class="showDetail ? '' : 'show-detail'">
                        <div class="is-raised s-card bb-1 mt-0 mb-2 p-4 bg-grey"
                            v-for="(items, index) in dataSourcefiltered" :key="items.norec">
                            <div class="card-head">
                                <VIconButton class="mr-2" icon="fas fa-notes-medical" color="danger" raised bold>
                                </VIconButton>
                                <VBlock :title="items.namalengkap" :subtitle="items.noemr" center>
                                    <VTag color="green" :label="items.noregistrasi" class="mt-1" outlined />

                                    <template #action>
                                        <VIconButton class="is-rounded is-small mr-2" circle outlined
                                            @click="() => { emits('editEMR', items) }"  icon="feather:edit" color="info" raised bold>
                                        </VIconButton>
                                        <VIconButton class="is-rounded is-small mr-2" circle outlined
                                            @click="hapusEMR(items)" :loading="items.loadingHapus" icon="feather:trash"
                                            color="danger" raised bold>
                                        </VIconButton>
                                        <VIconButton class="is-rounded is-small mr-2" circle outlined
                                            :loading="items.loading"
                                            :icon="'fas ' + (!items.isdetail ? 'fa-chevron-down' : 'fa-chevron-up')"
                                            color="dark" raised bold @click="klikDetail(items, index)">
                                        </VIconButton>
                                        <VButton class="is-rounded is-small " circle outlined color="warning" raised bold>
                                            {{ H.formatDateIndoSimple(items.tglemr) }}
                                        </VButton>
                                    </template>
                                </VBlock>
                            </div>
                            <div class="card-inner" v-if="items.isdetail">
                                <div class="columns is-multiline">

                                    <div class="column is-3">
                                        <div class="pt-1 pl-3 pr-3">
                                            <span class="txt-diagnosa mt-2"> <i aria-hidden="true"
                                                    class="fas fa-circle bullet"></i> Anamnesis</span>
                                            <table class="w-100 tb-order mt-1">
                                                <tr>
                                                    <td class="txt-1">Keluhan Utama</td>
                                                </tr>
                                                <tr>
                                                    <td class="txt-2">
                                                        <VCard class="p-2" style="height:165px">
                                                            {{ items.AsesmenAwal ? items.AsesmenAwal.keluhanUtama :
                                                                ''
                                                            }}
                                                        </VCard>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="txt-1">Riwayat Penyakit</td>
                                                </tr>
                                                <tr>
                                                    <td class="txt-2">
                                                        <VCard class="p-2" style="height:165px">
                                                            {{
                                                                items.AsesmenAwal ? items.AsesmenAwal.riwayatPenyakit :
                                                                ''
                                                            }}
                                                        </VCard>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="txt-1">Riwayat Alergi </td>
                                                </tr>
                                                <tr>
                                                    <td class="txt-2">
                                                        <VCard class="p-2" style="height:165px">
                                                            {{ items.AsesmenAwal ? (items.AsesmenAwal.riwayatAlergi
                                                                ?
                                                                items.AsesmenAwal.riwayatAlergi.label : '') : '' }}
                                                        </VCard>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="txt-1">Riwayat Pengobatan </td>
                                                </tr>
                                                <tr>
                                                    <td class="txt-2">
                                                        <VCard class="p-2" style="height:165px">
                                                            {{ items.AsesmenAwal ?
                                                                items.AsesmenAwal.riwayatPengobatan : '' }}
                                                        </VCard>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="column is-9">
                                        <div class="pt-1 pl-3 pr-3">

                                            <span class="txt-diagnosa mt-2"> <i aria-hidden="true"
                                                    class="fas fa-circle bullet"></i> Pemeriksaan Psikologi, Ekonomi
                                                sosial & Spiritual :</span>

                                            <div class="columns is-multiline">
                                                <div class="column is-4">
                                                    <span class="txt-1">Status Psikologi </span>
                                                    <VCard class="p-2" style="height:50px">
                                                        {{ items.AsesmenAwal ?
                                                            (items.AsesmenAwal.jenisPsikologi ?
                                                                items.AsesmenAwal.jenisPsikologi.label : '') :
                                                            '' }}
                                                    </VCard>
                                                </div>
                                                <div class="column is-4">
                                                    <span class="txt-1">Sosial Ekonomi </span>
                                                    <VCard class="p-2" style="height:50px">
                                                        {{ items.AsesmenAwal ?
                                                            items.AsesmenAwal.sosialEkonomi :
                                                            '' }}
                                                    </VCard>
                                                </div>
                                                <div class="column is-4">
                                                    <span class="txt-1"> Spiritual </span>
                                                    <VCard class="p-2" style="height:50px">
                                                        {{ items.AsesmenAwal ?
                                                            items.AsesmenAwal.spiritual : '' }}
                                                    </VCard>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="pt-1 pl-3 pr-3">

                                            <span class="txt-diagnosa mt-2"> <i aria-hidden="true"
                                                    class="fas fa-circle bullet"></i> Pemeriksaan Fisik : </span>
                                            <div class="columns is-multiline">

                                                <div class="column is-7">
                                                    <span class="txt-1">Vital Sign </span>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-6">
                                                            <div class="stat-widget followers-stat-widget-v1 p-2">
                                                                <div class="follow-block">
                                                                    <div class="follow-count">
                                                                        <span class="dark-inverted vital">
                                                                            Tinggi Badan
                                                                        </span>
                                                                        <span class="vital-value">{{
                                                                            items.AsesmenAwal ?
                                                                            items.AsesmenAwal.tinggiBadan
                                                                            : '' }}<span class="vital-satuan">Cm</span>
                                                                        </span>
                                                                    </div>
                                                                    <a href="#" class="go-icon">
                                                                        <img src="/images/simrs/tinggibadan.png">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="column is-6">
                                                            <div class="stat-widget followers-stat-widget-v1 p-2">
                                                                <div class="follow-block">
                                                                    <div class="follow-count">
                                                                        <span class="dark-inverted vital">
                                                                            Berat Badan
                                                                        </span>
                                                                        <span class="vital-value">{{
                                                                            items.AsesmenAwal ?
                                                                            items.AsesmenAwal.beratBadan
                                                                            : '' }}<span class="vital-satuan">Kg</span>
                                                                        </span>
                                                                    </div>
                                                                    <a href="#" class="go-icon">
                                                                        <img src="/images/simrs/beratbadan.png">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="column is-12">
                                                            <div class="stat-widget followers-stat-widget-v1 p-2">
                                                                <div class="follow-block">
                                                                    <div class="follow-count">
                                                                        <span class="dark-inverted vital">
                                                                            Tekanan Darah
                                                                        </span>
                                                                        <div>
                                                                            <img src="/images/simrs/tekanandarah.png">
                                                                            <span class="vital-value ml-5"
                                                                                style="display:inline;">{{
                                                                                    items.AsesmenAwal ?
                                                                                    items.AsesmenAwal.tekananDarah
                                                                                    : '' }}<span
                                                                                    class="vital-satuan">mmHG</span>
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="column is-12">
                                                            <div class="stat-widget followers-stat-widget-v1 p-2">
                                                                <div class="follow-block">
                                                                    <div class="follow-count">
                                                                        <span class="dark-inverted vital">
                                                                            Denyut Nadi
                                                                        </span>
                                                                        <div>
                                                                            <img src="/images/simrs/denyut.png">
                                                                            <span class="vital-value ml-5"
                                                                                style="display:inline;">{{
                                                                                    items.AsesmenAwal ?
                                                                                    items.AsesmenAwal.nadi
                                                                                    : '' }}<span class="vital-satuan">BPM</span>
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="column is-6">
                                                            <div class="stat-widget followers-stat-widget-v1 p-2">
                                                                <div class="follow-block">
                                                                    <div class="follow-count">
                                                                        <span class="dark-inverted vital">
                                                                            Suhu
                                                                        </span>
                                                                        <span class="vital-value">{{
                                                                            items.AsesmenAwal ?
                                                                            items.AsesmenAwal.suhu
                                                                            : '' }}<span class="vital-satuan">°C
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                    <a href="#" class="go-icon">
                                                                        <img src="/images/simrs/suhu.png">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="column is-6">
                                                            <div class="stat-widget followers-stat-widget-v1 p-2">
                                                                <div class="follow-block">
                                                                    <div class="follow-count">
                                                                        <span class="dark-inverted vital">
                                                                            Napas
                                                                        </span>
                                                                        <span class="vital-value">{{
                                                                            items.AsesmenAwal ?
                                                                            items.AsesmenAwal.pernapasan
                                                                            : '-' }}<span class="vital-satuan">XPM</span>
                                                                        </span>
                                                                    </div>
                                                                    <a href="#" class="go-icon">
                                                                        <img src="/images/simrs/nafas.png">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="column is-12">
                                                            <div class="stat-widget followers-stat-widget-v1 p-2">
                                                                <div class="follow-block">
                                                                    <div class="follow-count">
                                                                        <span class="dark-inverted vital">
                                                                            SPO2
                                                                        </span>
                                                                        <div>
                                                                            <img src="/images/simrs/spo.png">
                                                                            <span class="vital-value ml-5"
                                                                                style="display:inline;">{{
                                                                                    items.AsesmenAwal ?
                                                                                    items.AsesmenAwal.SPO2
                                                                                    : '-' }}<span class="vital-satuan">%</span>
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="column is-6">
                                                            <div class="stat-widget followers-stat-widget-v1 p-2">
                                                                <div class="follow-block">
                                                                    <div class="follow-count">
                                                                        <span class="dark-inverted vital">
                                                                            IMT
                                                                        </span>
                                                                        <span class="vital-value">{{
                                                                            items.AsesmenAwal ?
                                                                            items.AsesmenAwal.IMT
                                                                            : '-' }}<span class="vital-satuan"></span>
                                                                        </span>
                                                                    </div>
                                                                    <a href="#" class="go-icon">
                                                                        <img src="/images/simrs/imt.png">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="column is-6">
                                                            <div class="stat-widget followers-stat-widget-v1 p-2">
                                                                <div class="follow-block">
                                                                    <div class="follow-count">
                                                                        <span class="dark-inverted vital">
                                                                            Lingkar Perut
                                                                        </span>
                                                                        <span class="vital-value">{{
                                                                            items.AsesmenAwal ?
                                                                            items.AsesmenAwal.lingkarPerut
                                                                            : '-' }}<span class="vital-satuan">Cm</span>
                                                                        </span>
                                                                    </div>
                                                                    <a href="#" class="go-icon">
                                                                        <img src="/images/simrs/lingkarperut.png">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="column is-12">
                                                                        <span class="txt-1">Lain-Lain </span>
                                                                        <VCard class="p-2">

                                                                        </VCard>
                                                                    </div> -->
                                                    </div>

                                                </div>
                                                <div class="column is-5">
                                                    <span class="txt-1"> Anatomi Tubuh </span>
                                                    <!-- <VCard class="p-2" v-if="!isAsesmen">

                                                        <img v-if="props.pasien.jeniskelamin.toUpperCase() == 'PEREMPUAN'"
                                                            src="/images/simrs/cewek.png">
                                                        <img v-else src="/images/simrs/cowok.png">
                                                    </VCard> -->

                                                    <VCard class="p-2">
                                                        <div :style="'background-image:url(' + MARKINGSITE + ')'" style="      text-align: center;
    
    z-index: 9999;
    background-repeat: no-repeat;
    background-position: center;
    width: 200px;
    height: 600px">
                                                            <canvas id="markingsite" height="600" width="200"></canvas>
                                                        </div>
                                                        <!-- <img :src="items.AsesmenAwal ? items.AsesmenAwal.anatomiTubuh:''"> -->
                                                    </VCard>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="column is-12">
                                        <span class="txt-1">Keaadaan Umum </span>
                                        <div class="s-card">
                                            <div class="columns is-multiline">
                                                <div class="column is-6">
                                                    <highcharts :options="items.AsesmenAwal.chartSadar"></highcharts>
                                                    <highcharts :options="items.AsesmenAwal.chartResponseKata"></highcharts>
                                                    <highcharts :options="items.AsesmenAwal.chartResponseNyeri"></highcharts>
                                                    <!-- <ApexChart 
                                                        :height="items.AsesmenAwal.chartSadar.chart.height" :type="'bar'"
                                                        :series="items.AsesmenAwal.chartSadar.series"
                                                        :options="items.AsesmenAwal.chartSadar">
                                                    </ApexChart>
                                                    <ApexChart 
                                                        :height="items.AsesmenAwal.chartResponseKata.chart.height" :type="'bar'"
                                                        :series="items.AsesmenAwal.chartResponseKata.series"
                                                        :options="items.AsesmenAwal.chartResponseKata">
                                                    </ApexChart>
                                                    <ApexChart 
                                                        :height="items.AsesmenAwal.chartResponseNyeri.chart.height" :type="'bar'"
                                                        :series="items.AsesmenAwal.chartResponseNyeri.series"
                                                        :options="items.AsesmenAwal.chartResponseNyeri">
                                                    </ApexChart> -->

                                                </div>
                                                <div class="column is-6">
                                                    <highcharts :options="items.AsesmenAwal.chartPasienTidak"></highcharts>
                                                    <highcharts :options="items.AsesmenAwal.chartGelisah"></highcharts>
                                                    <highcharts :options="items.AsesmenAwal.chartAcute"></highcharts>
                                                    <!-- <ApexChart 
                                                        :height="items.AsesmenAwal.chartPasienTidak.chart.height" :type="'bar'"
                                                        :series="items.AsesmenAwal.chartPasienTidak.series"
                                                        :options="items.AsesmenAwal.chartPasienTidak">
                                                    </ApexChart>
                                                    <ApexChart 
                                                        :height="items.AsesmenAwal.chartGelisah.chart.height" :type="'bar'"
                                                        :series="items.AsesmenAwal.chartGelisah.series"
                                                        :options="items.AsesmenAwal.chartGelisah">
                                                    </ApexChart>
                                                    <ApexChart 
                                                        :height="items.AsesmenAwal.chartAcute.chart.height" :type="'bar'"
                                                        :series="items.AsesmenAwal.chartAcute.series"
                                                        :options="items.AsesmenAwal.chartAcute">
                                                    </ApexChart> -->
                                                 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </VCard>
</template>
<script setup lang="ts">

import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, PropType, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import * as H from '/@src/utils/appHelper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import ApexChart from 'vue3-apexcharts'
import sleep from '/@src/utils/sleep'
const emits = defineEmits<{
    (e: 'showRiwayat'): void,
    (e: 'hiddenRiwayat'): void,
    (e: 'reloadRiwayat'): void,
    (e: 'billingPasien'): void
    (e: 'editEMR', value: any): void,
}>()
const props = defineProps({
    registrasi: {
        type: Object as PropType<any>,
    },
    pasien: {
        type: Object as PropType<any>,
    },
    riwayatAsesmen: {
        type: Object as PropType<any>,
    },
    hideRiwayat: {
        type: Boolean
    },
    isLoadingRiwayat: {
        type: Boolean
    },
    isPasienAktif: {
        type: Boolean
    },
})
const route = useRoute()
const ID_PASIEN: any = route.query.nocmfk
const NOREC_PD: any = route.query.norec_pd
const themeColors = useThemeColors()
const isDetail: any = ref([true])
const router = useRouter()
const filters = ref('')
const showDetail = ref(false)
const dataAsesmen: any = ref([])
const isAsesmen: any = ref(false)
const MARKINGSITE: any = ref()
if (props.pasien.jeniskelamin) {
    // MARKINGSITE.value = '/images/simrs/' + (props.pasien.jeniskelamin == 'PEREMPUAN' ? 'cewek.png' : 'cowok.png')
    MARKINGSITE.value = '/images/simrs/' + (props.pasien.jeniskelamin.toUpperCase() == 'PEREMPUAN' ? 'cewek.png' : 'cowok.png')
}

isDetail.value[1] = true

// console.log(props.pasien)

const dataSourcefiltered: any = computed(() => {
    if (!filters.value) {
        return dataAsesmen.value
    }

    let key = new RegExp(filters.value, 'i')
    return dataAsesmen.value.filter((item: any) => {
        return (
            item.tglemr.match(key)
            || item.noemr.match(key)
            || item.noregistrasi.match(key)
            || item.namalengkap.match(key)
        )
    })
})
// categories: [
//             'Sadar baik/alert   ',
//             'Respon dengan kata-kata / voice',
//             'Respon nyeri',
//             'Pasien Tidak sadar',
//             'Gelisah atau bingung',
//             'Acute confessional states',
//         ],
const chartHeigth = ref(120)
let chartSadarHigh: any = ref({
    chart: {
        type: 'bar',
        height: 60
    },
    title: {
        text: '',
        align: 'left'
    },
   
    xAxis: {
        categories: ['Sadar baik/alert'],
        title: {
            text: null
        },
        gridLineWidth: 1,
        lineWidth: 0
    },
    yAxis: {
        min: 0,
        max:5,
        allowDecimals:false,
        // range:10,
        title: {
            text: '',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        },
        gridLineWidth: 0
    },
    tooltip: {
        valueSuffix: ' '
    },
    plotOptions: {
        bar: {
            borderRadius: '50%',
            dataLabels: {
                enabled: true
            },
            groupPadding: 0.1,
            minPointLength: 1,
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor:
           '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [{
            name: 'Sadar baik/alert',
            color: '#FFB62E',
            data: [2]
        }]
    
});
const chartSadarDef: any = {
    chart: {
        type: 'bar',
        height: 60
    },
    title: {
        text: '',
        align: 'left'
    },
   
    xAxis: {
        categories: ['Sadar baik/alert'],
        title: {
            text: null
        },
        gridLineWidth: 1,
        lineWidth: 0,
        offset:62,
    },
    yAxis: {
        min: 0,
        max:5,
        allowDecimals:false,
        // range:10,
        title: {
            text: '',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        },
        gridLineWidth: 0
    },
    tooltip: {
        valueSuffix: ' '
    },
    plotOptions: {
        bar: {
            borderRadius: '50%',
            dataLabels: {
                enabled: false
            },
            groupPadding: 0.1,
            minPointLength: 1,
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor:
           '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: []
    // title: {
    //     text: 'Sadar baik/alert',
    //     margin: 0,
    //     offsetX: 0,
    //     offsetY: 30,
    //     floating: false,
    //     style: {
    //         fontSize: '9px',
    //         fontWeight: 'normal',
    //         fontFamily: undefined,
    //         color: '#263238'
    //     },
    // },
    // series: [],
    // colors: ['#FFB62E'],
    // chart: {
    //     type: 'bar',
    //     height: chartHeigth.value,
    //     toolbar: {
    //         show: false
    //     }
    // },
    // plotOptions: {
    //     bar: {
    //         borderRadius: 4,
    //         horizontal: true,
    //         // columnWidth: '70%',
    //         barHeight: 20,
    //     }
    // },
    // dataLabels: {
    //     enabled: false
    // },
    // xaxis: {
    //     categories: ['Sadar baik/alert'],
    //     // min: 0,
    //     // max: 5,
    //     range: 1,
    //     // decimalsInFloat:1,
    //     labels: {
    //         show: true,
    //     }
    // },
    // yaxis: {
    //     labels: {
    //         show: false,
    //     }
    // }
}

const chartResponseKata: any = {
    chart: {
        type: 'bar',
        height: 60
    },
    title: {
        text: '',
        align: 'left'
    },
   
    xAxis: {
        categories: ['Respon dengan kata-kata / voice'],
        title: {
            text: null
        },
        gridLineWidth: 1,
        lineWidth: 0,
        // offset:40,
    },
    yAxis: {
        min: 0,
        max:5,
        allowDecimals:false,
        // range:10,
        title: {
            text: '',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        },
        gridLineWidth: 0
    },
    tooltip: {
        valueSuffix: ' '
    },
    plotOptions: {
        bar: {
            borderRadius: '50%',
            dataLabels: {
                enabled: false
            },
            groupPadding: 0.1,
            minPointLength: 1,
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor:
           '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: []
    // title: {
    //     text: 'Respon dengan kata-kata / voice',
    //     margin: 0,
    //     offsetX: 0,
    //     offsetY: 30,
    //     floating: false,
    //     style: {
    //         fontSize: '9px',
    //         fontWeight: 'normal',
    //         fontFamily: undefined,
    //         color: '#263238'
    //     },
    // },
    // series: [],
    // colors: ['#41B983'],
    // chart: {
    //     type: 'bar',
    //     height: chartHeigth.value,
    //     toolbar: {
    //         show: false
    //     }
    // },
    // plotOptions: {
    //     bar: {
    //         borderRadius: 4,
    //         horizontal: true,
    //         columnWidth: '70%',
    //         barHeight: 20,
    //     }
    // },
    // dataLabels: {
    //     enabled: false
    // },
    // xaxis: {
    //     categories: ['Respon dengan kata-kata / voice'],
    //     min: 0,
    //     max: 5,
    //     labels: {
    //         show: true,
    //     }
    // },
    // yaxis: {
    //     labels: {
    //         show: false,
    //     }
    // }
}
const chartResponseNyeri: any = {
    // title: {
    //     text: 'Respon jika dirangsang nyeri / pain',
    //     margin: 0,
    //     offsetX: 0,
    //     offsetY: 30,
    //     floating: false,
    //     style: {
    //         fontSize: '9px',
    //         fontWeight: 'normal',
    //         fontFamily: undefined,
    //         color: '#263238'
    //     },
    // },
    // series: [],
    // colors: ['#41B983'],
    // chart: {
    //     type: 'bar',
    //     height: chartHeigth.value,
    //     toolbar: {
    //         show: false
    //     }
    // },
    // plotOptions: {
    //     bar: {
    //         borderRadius: 4,
    //         horizontal: true,
    //         columnWidth: '70%',
    //         barHeight: 20,
    //     }
    // },
    // dataLabels: {
    //     enabled: false
    // },
    // xaxis: {
    //     categories: ['Respon jika dirangsang nyeri / pain'],
    //     min: 0,
    //     max: 5,
    //     labels: {
    //         show: true,
    //     }
    // },
    // yaxis: {
    //     labels: {
    //         show: false,
    //     }
    // }
    chart: {
        type: 'bar',
        height: 60
    },
    title: {
        text: '',
        align: 'left'
    },
   
    xAxis: {
        categories: ['Respon jika dirangsang nyeri / pain'],
        title: {
            text: null
        },
        gridLineWidth: 1,
        lineWidth: 0,
        offset:5,
    },
    yAxis: {
        min: 0,
        max:5,
        allowDecimals:false,
        // range:10,
        title: {
            text: '',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        },
        gridLineWidth: 0
    },
    tooltip: {
        valueSuffix: ' '
    },
    plotOptions: {
        bar: {
            borderRadius: '50%',
            dataLabels: {
                enabled: false
            },
            groupPadding: 0.1,
            minPointLength: 1,
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor:
           '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: []
}
const chartPasienTidak: any ={
    // title: {
    //     text: 'Pasien Tidak sadar / unresponsive',
    //     margin: 0,
    //     offsetX: 0,
    //     offsetY: 30,
    //     floating: false,
    //     style: {
    //         fontSize: '9px',
    //         fontWeight: 'normal',
    //         fontFamily: undefined,
    //         color: '#263238'
    //     },
    // },
    // series: [],
    // colors: ['#ED1C24'],
    // chart: {
    //     type: 'bar',
    //     height: chartHeigth.value,
    //     toolbar: {
    //         show: false
    //     }
    // },
    // plotOptions: {
    //     bar: {
    //         borderRadius: 4,
    //         horizontal: true,
    //         columnWidth: '70%',
    //         barHeight: 20,
    //     }
    // },
    // dataLabels: {
    //     enabled: false
    // },
    // xaxis: {
    //     categories: ['Pasien Tidak sadar / unresponsive'],
    //     min: 0,
    //     max: 5,
    //     labels: {
    //         show: true,
    //     }
    // },
    // yaxis: {
    //     labels: {
    //         show: false,
    //     }
    // }
    chart: {
        type: 'bar',
        height: 60
    },
    title: {
        text: '',
        align: 'left'
    },
   
    xAxis: {
        categories: ['Pasien Tidak sadar / unresponsive'],
        title: {
            text: null
        },
        gridLineWidth: 1,
        lineWidth: 0
    },
    yAxis: {
        min: 0,
        max:5,
        allowDecimals:false,
        // range:10,
        title: {
            text: '',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        },
        gridLineWidth: 0
    },
    tooltip: {
        valueSuffix: ' '
    },
    plotOptions: {
        bar: {
            borderRadius: '50%',
            dataLabels: {
                enabled: false
            },
            groupPadding: 0.1,
            minPointLength: 1,
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor:
           '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: []
}
const chartGelisah: any ={
    chart: {
        type: 'bar',
        height: 60
    },
    title: {
        text: '',
        align: 'left'
    },
   
    xAxis: {
        categories: ['Gelisah atau bingung'],
        title: {
            text: null
        },
        gridLineWidth: 1,
        lineWidth: 0,
        offset:32,
    },
    yAxis: {
        min: 0,
        max:5,
        allowDecimals:false,
        // range:10,
        title: {
            text: '',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        },
        gridLineWidth: 0
    },
    tooltip: {
        valueSuffix: ' '
    },
    plotOptions: {
        bar: {
            borderRadius: '50%',
            dataLabels: {
                enabled: false
            },
            groupPadding: 0.1,
            minPointLength: 1,
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor:
           '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: []
    // title: {
    //     text: 'Gelisah atau bingung',
    //     margin: 0,
    //     offsetX: 0,
    //     offsetY: 30,
    //     floating: false,
    //     style: {
    //         fontSize: '9px',
    //         fontWeight: 'normal',
    //         fontFamily: undefined,
    //         color: '#263238'
    //     },
    // },
    // series: [],
    // colors: ['#41B983'],
    // chart: {
    //     type: 'bar',
    //     height: chartHeigth.value,
    //     toolbar: {
    //         show: false
    //     }
    // },
    // plotOptions: {
    //     bar: {
    //         borderRadius: 4,
    //         horizontal: true,
    //         columnWidth: '70%',
    //         barHeight: 20,
    //     }
    // },
    // dataLabels: {
    //     enabled: false
    // },
    // xaxis: {
    //     categories: ['Gelisah atau bingung'],
    //     min: 0,
    //     max: 5,
    //     labels: {
    //         show: true,
    //     }
    // },
    // yaxis: {
    //     labels: {
    //         show: false,
    //     }
    // }
}
const chartAcute: any = {
    chart: {
        type: 'bar',
        height: 60
    },
    title: {
        text: '',
        align: 'left'
    },
   
    xAxis: {
        categories: ['Acute confessional states'],
        title: {
            text: null
        },
        gridLineWidth: 1,
        lineWidth: 0,
        offset:10,
    },
    yAxis: {
        min: 0,
        max:5,
        allowDecimals:false,
        // range:10,
        title: {
            text: '',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        },
        gridLineWidth: 0
    },
    tooltip: {
        valueSuffix: ' '
    },
    plotOptions: {
        bar: {
            borderRadius: '50%',
            dataLabels: {
                enabled: false
            },
            groupPadding: 0.1,
            minPointLength: 1,
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor:
           '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: []
    // title: {
    //     text: 'Acute confessional states',
    //     margin: 0,
    //     offsetX: 0,
    //     offsetY: 30,
    //     floating: false,
    //     style: {
    //         fontSize: '9px',
    //         fontWeight: 'normal',
    //         fontFamily: undefined,
    //         color: '#263238'
    //     },
    // },
    // series: [],
    // colors: ['#FFB62E'],
    // chart: {
    //     type: 'bar',
    //     height: chartHeigth.value,
    //     toolbar: {
    //         show: false
    //     }
    // },
    // plotOptions: {
    //     bar: {
    //         borderRadius: 4,
    //         horizontal: true,
    //         columnWidth: '70%',
    //         barHeight: 20,
    //     }
    // },
    // dataLabels: {
    //     enabled: false
    // },
    // xaxis: {
    //     categories: ['Acute confessional states'],
    //     min: 0,
    //     max: 5,
    //     labels: {
    //         show: true,
    //     }
    // },
    // yaxis: {
    //     labels: {
    //         show: false,
    //     }
    // }
}
const fetchAsesmen = async () => {
    dataAsesmen.value = []
    let params = `&nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`
    await useApi().get(
        `/emr/riwayat-emr?jenis_emr=asesmenawal${params}`).then((response: any) => {
            response.AsesmenAwal = {}
            // response.AsesmenAwal = {}
            // response.AsesmenAwal = {}

            dataAsesmen.value = response
            if (dataAsesmen.value.length) {
                klikDetail(dataAsesmen.value[0], 0)
            }
        })
}

const klikDetail = async (e: any, index: any) => {
    isAsesmen.value = true
    
    for (let x = 0; x < dataAsesmen.value.length; x++) {
        const element = dataAsesmen.value[x];
        if (x != index) {
            element.isdetail = false
        }
    }
    e.isdetail = !e.isdetail
    if (e.isdetail) {
        e.loading = true
        await useApi().get(
            `/emr/riwayat-emr-detail?norec=${e.norec}&collection=AsesmenAwal`).then(async(response: any) => {
                e.AsesmenAwal = response.AsesmenAwal ? response.AsesmenAwal : {}
                isAsesmen.value = response.AsesmenAwal ? (response.AsesmenAwal.anatomiTubuh ? true : false) : false
                e.AsesmenAwal.chartSadar = chartSadarDef
                
                e.AsesmenAwal.chartSadar.series = [{ name: 'Jumlah', color:'#FFB62E', data: [0] }]
                if (response.AsesmenAwal?.sadarBaik) {
                    e.AsesmenAwal.chartSadar.series = [{ name: 'Jumlah',  color:'#FFB62E',data: [response.AsesmenAwal.sadarBaik] }]
                }

                e.AsesmenAwal.chartResponseNyeri = chartResponseNyeri
                e.AsesmenAwal.chartResponseNyeri.series = [{ name: 'Jumlah', color:'#41B983', data: [0] }]
                if (response.AsesmenAwal?.responDenganKata) {
                    e.AsesmenAwal.chartResponseNyeri.series = [{ name: 'Jumlah', color:'#41B983', data: [response.AsesmenAwal.responDenganKata] }]
                }

                e.AsesmenAwal.chartPasienTidak = chartPasienTidak
                e.AsesmenAwal.chartPasienTidak.series = [{ name: 'Jumlah', color:'#ED1C24', data: [0] }]
                if (response.AsesmenAwal?.pasienTidakSadar) {
                    e.AsesmenAwal.chartPasienTidak.series = [{ name: 'Jumlah', color:'#ED1C24', data: [response.AsesmenAwal.pasienTidakSadar] }]
                }
           
                e.AsesmenAwal.chartGelisah = chartGelisah
                e.AsesmenAwal.chartGelisah.series = [{ name: 'Jumlah', color:'#41B983', data: [0] }]
                if (response.AsesmenAwal?.gelisahBingung) {
                    e.AsesmenAwal.chartGelisah.series = [{ name: 'Jumlah', color:'#41B983', data: [response.AsesmenAwal.gelisahBingung] }]
                }

                e.AsesmenAwal.chartAcute = chartAcute
                e.AsesmenAwal.chartAcute.series = [{ name: 'Jumlah', color:'#FFB62E', data: [0] }]
                if (response.AsesmenAwal?.acuteConfessionalStates) {
                    e.AsesmenAwal.chartAcute.series = [{ name: 'Jumlah', color:'#FFB62E', data: [response.AsesmenAwal.acuteConfessionalStates] }]
                }
                e.AsesmenAwal.chartResponseKata = chartResponseKata
                e.AsesmenAwal.chartResponseKata.series = [{ name: 'Jumlah', color:'#41B983', data: [0] }]
                if (response.AsesmenAwal?.responDenganKata) {
                    e.AsesmenAwal.chartResponseKata.series = [{ name: 'Jumlah',  color:'#41B983',data: [response.AsesmenAwal.responDenganKata] }]
                }
           
                
             

                if (response.AsesmenAwal && response.AsesmenAwal.anatomiTubuh) {
                    await sleep(1000)
                    MARKINGSITE.value = '/images/simrs/' + (response.AsesmenAwal.pasien.jeniskelamin.toUpperCase() == 'PEREMPUAN' ? 'cewek.png' : 'cowok.png')
                    let sigCanvas: any = document.getElementById('markingsite');
                    if (sigCanvas != null) {
                        let context = sigCanvas.getContext("2d");
                        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
                        let imagess = response.AsesmenAwal.anatomiTubuh
                        let background = new Image();
                        background.src = imagess
                        background.onload = function () {
                            context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height);
                        }
                    }
                }

                // e.AsesmenAwal = response.AsesmenAwal ? response.AsesmenAwal : {}
                // e.AsesmenAwal = response.AsesmenAwal ? response.AsesmenAwal : {}
                e.loading = false
            })
    }
    showDetail.value = e.isdetail
}
// const editEMR = (e: any) => {
//     router.push({
//         name: 'module-emr-asesmen-awal',
//         query: {
//             norec_pd: NOREC_PD,
//             nocmfk: ID_PASIEN,
//             norec_emr: e.norec,
//         }
//     })
// }
const hapusEMR = async (e: any) => {
    e.loadingHapus = true
    // confirm.require({
    //     group: 'positionDialog',
    //     message: H.alertHapus(),
    //     header: 'Info ',
    //     icon: 'pi pi-info-circle',
    //     acceptClass: 'p-button-danger',
    //     position: 'top',
    //     accept: async () => {
    await useApi().post(
        `/emr/hapus-emr`, {
        'norec': e.norec,
        'collection': 'AsesmenAwal'
    }).then((response: any) => {
        e.loadingHapus = false

        dataAsesmen.value.forEach((element: any, i: any) => {
            if (e.norec == element.norec) {
                dataAsesmen.value.splice(i, 1);
            }
        });
    })
    //     },
    //     reject: () => {
    //     }
    // });

}
onMounted(async () => {
    // await fetchAsesmen()
})
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/emr-detail';

.show-detail {
    height: 200px;
    overflow: auto;
}

.vital {
    color: #A2A5B9 !important;
    font-size: 0.75rem !important;
}

.vital-value {
    color: #283252 !important;
    font-weight: bold;
    font-size: 0.8rem !important;
}

.vital-satuan {
    margin-left: 5px;
    color: #A2A5B9 !important;
    font-size: 0.8rem !important;
    display: inline !important;
}

.stat-widget {
    &.followers-stat-widget-v1 {
        .follow-block {
            display: flex;
            align-items: center;

            .follow-icon {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 50px;
                width: 50px;
                min-width: 50px;
                border-radius: var(--radius-rounded);
                background: var(--white);
                color: var(--primary);
                border: 1px solid var(--fade-grey-dark-3);
                box-shadow: var(--light-box-shadow);

                &.is-squared {
                    border-radius: 10px;
                }

                &.is-primary {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);
                    color: var(--smoke-white);
                }

                svg {
                    height: 18px;
                    width: 18px;
                }

                i {
                    font-size: 18px;
                }
            }

            .follow-count {
                margin-left: 12px;

                span {
                    display: block;

                    &:first-child {
                        font-family: var(--font-alt);
                        font-size: 1.15rem;
                        font-weight: 600;
                        color: var(--dark-text);
                    }

                    &:nth-child(2) {
                        font-size: 0.95rem;
                        color: var(--light-text);
                    }
                }
            }

            .go-icon {
                position: relative;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 36px;
                width: 36px;
                border-radius: var(--radius-rounded);
                background: var(--widget-grey);
                color: var(--light-text);
                margin-left: auto;

                &.is-squared {
                    border-radius: 10px;
                }

                svg {
                    height: 16px;
                    width: 16px;
                    stroke-width: 3px;
                }
            }
        }
    }
}

.is-dark {
    .stat-widget {
        @include vuero-card--dark;

        &.followers-stat-widget-v1 {
            .follow-block {
                .follow-icon:not(.is-primary) {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);
                    color: var(--primary);
                }

                .follow-icon {
                    &.is-primary {
                        border-color: var(--primary);
                        background: var(--primary);
                    }
                }
            }

            .go-icon {
                background: var(--dark-sidebar-light-2);
                border: 1px solid var(--dark-sidebar-light-12);
            }
        }
    }
}
</style>
