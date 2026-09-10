<template>
    <div class="personal-dashboard personal-dashboard-v1">
        <!--Personal Dashboard V1-->
        <!--Header-->
        <div class="dashboard-header">
            <!-- <VAvatar picture="/images/icons/files/bpjs.svg" size="large" /> -->
            <div class="start">
                <h3>Antrean Online</h3>
                <p>Mengatasi antrean peserta yang menumpuk di fasilitas kesehatan</p>
            </div>
        </div>

        <!--Body-->
        <div class="dashboard-body">
            <div class="columns is-multiline">
                <div class="column is-12">
                    <VTag @click="showModalFilterWaktu()" color="danger" rounded elevated class="is-pulled-right" style="cursor:pointer">{{
                    item.tglwaktutunggu == undefined ? H.formatDate(item.tglwaktutunggu, 'MMMM-YYYY') : H.formatDate(item.tglwaktutunggu, 'MMMM-YYYY')
                    }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
                </div>
                <!-- rata rata waktu  -->
                <div class="column is-12" v-if="isLoading">
                    <VPlaceloadWrap class="my-4">
                        <VPlaceload height="80px" width="20%" class="mx-2" />
                        <VPlaceload height="80px" width="20%" class="mx-2" />
                        <VPlaceload height="80px" width="20%" class="mx-2" />
                        <VPlaceload height="80px" width="20%" class="mx-2" />
                        <VPlaceload height="80px" width="20%" class="mx-2" />
                        <VPlaceload height="80px" width="20%" class="mx-2" />
                    </VPlaceloadWrap>
                    <VPlaceloadWrap>
                        <VPlaceload height="80px" width="60%" class="mx-2" />
                        <VPlaceload height="80px" width="40%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-12" v-else>
                    <div class="columns is-multiline">
                        <div class="column is-2">
                            <div class="dashboard-card is-warning" style="min-height: auto !important;padding: 20px;">
                                <i aria-hidden="true" class="lnil lnil-alarm-clock"></i>
                                <div class="cta-content">
                                <h4>{{ avg_1 }}</h4>
                                <p class="white-text">Waktu Tunggu<br>Admisi</p>
                                </div>
                            </div>
                        </div>
                        <div class="column is-2">
                            <div class="dashboard-card is-danger" style="min-height: auto !important;padding: 20px;">
                                <i aria-hidden="true" class="lnil lnil-alarm-clock"></i>
                                <div class="cta-content">
                                <h4>{{ avg_2 }}</h4>
                                <p class="white-text">Waktu Layan<br>Admisi</p>
                                </div>
                            </div>
                        </div>
                        <div class="column is-2">
                            <div class="dashboard-card is-upgrade" style="min-height: auto !important;padding: 20px;">
                                <i aria-hidden="true" class="lnil lnil-alarm-clock"></i>
                                <div class="cta-content">
                                <h4>{{ avg_3 }}</h4>
                                <p class="white-text">Waktu Tunggu<br>Poli</p>
                                </div>
                            </div>
                        </div>
                        <div class="column is-2">
                            <div class="dashboard-card is-warning" style="min-height: auto !important;padding: 20px;">
                                <i aria-hidden="true" class="lnil lnil-alarm-clock"></i>
                                <div class="cta-content">
                                <h4>{{ avg_4 }}</h4>
                                <p class="white-text">Waktu Layan<br>Poli</p>
                                </div>
                            </div>
                        </div>
                        <div class="column is-2">
                            <div class="dashboard-card is-danger" style="min-height: auto !important;padding: 20px;">
                                <i aria-hidden="true" class="lnil lnil-alarm-clock"></i>
                                <div class="cta-content">
                                <h4>{{ avg_5 }}</h4>
                                <p class="white-text">Waktu Tunggu<br>Farmasi</p>
                                </div>
                            </div>
                        </div>
                        <div class="column is-2">
                            <div class="dashboard-card is-upgrade" style="min-height: auto !important;padding: 20px;">
                                <i aria-hidden="true" class="lnil lnil-alarm-clock"></i>
                                <div class="cta-content">
                                <h4>{{ avg_6 }}</h4>
                                <p class="white-text">Waktu Layan<br>Farmasi</p>
                                </div>
                            </div>
                        </div>
                        <div class="column is-8">
                            <div class="dashboard-card is-success" style="min-height: auto !important;padding: 20px;">
                                <i aria-hidden="true" class="lnil lnil-rocket"></i>
                                <div class="cta-content">
                                <h4>{{ avg_all }}</h4>
                                <p class="white-text">Total Waktu Layan (Admisi - Farmasi)</p>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="dashboard-card is-info" style="min-height: auto !important;padding: 20px;">
                                <i aria-hidden="true" class="lnil lnil-clipboard"></i>
                                <div class="cta-content">
                                <h4>{{ jumlahAntrean }}</h4>
                                <p class="white-text">Jumlah Antrean Total</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- chart waktu antrian -->
                <div class="column is-8" v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="400px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-8" v-else>
                    <div class="dashboard-card">
                        <ApexChart
                        :height="WaktuTungguOptions.chart.height"
                        :type="WaktuTungguOptions.chart.type"
                        :series="WaktuTungguOptions.series"
                        :options="WaktuTungguOptions">
                        </ApexChart>
                    </div>
                </div>

                <!--List Widget V1a-->
                <div class="column is-4" v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="400px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-4" v-else>
                    <div class="dashboard-card">
                        <div class="card-head">
                            <h3 class="dark-inverted">Keterangan</h3>
                        </div>
                        <div class="active-list">
                            <div class="checkboxes-list">
                                <!-- List item -->
                                <div class="list-item">
                                    <!-- Animated checkbox-->
                                    <VIconWrap icon="fas fa-circle" style="color:#DD7C7C" />
                                    <div class="item-meta">
                                    <span class="dark-inverted">Taks 1</span>
                                    <span>Waktu Tunggu Registrasi</span>
                                    </div>
                                </div>
                                <!-- List item -->
                                <div class="list-item">
                                    <!-- Animated checkbox-->
                                    <VIconWrap icon="fas fa-circle" style="color:#7FDDE3" />
                                    <div class="item-meta">
                                    <span>Taks 2</span>
                                    <span class="dark-inverted">Waktu Pelayanan Registrasi</span>
                                    </div>
                                </div>
                                <!-- List item -->
                                <div class="list-item">
                                    <!-- Animated checkbox-->
                                    <VIconWrap icon="fas fa-circle" style="color:#8387E9" />
                                    <div class="item-meta">
                                    <span>Taks 3</span>
                                    <span class="dark-inverted">Waktu Tunggu Poli</span>
                                    </div>
                                </div>
                                <!-- List item -->
                                <div class="list-item">
                                    <!-- Animated checkbox-->
                                    <VIconWrap icon="fas fa-circle" style="color:#DB7EE3" />
                                    <div class="item-meta">
                                    <span>Taks 4</span>
                                    <span class="dark-inverted">Waktu Pelayanan Poli</span>
                                    </div>
                                </div>
                                <!-- List item -->
                                <div class="list-item">
                                    <!-- Animated checkbox-->
                                    <VIconWrap icon="fas fa-circle" style="color:#E1D777" />
                                    <div class="item-meta">
                                    <span>Taks 5</span>
                                    <span class="dark-inverted">Waktu Tunggu Farmasi</span>
                                    </div>
                                </div>
                                <!-- List item -->
                                <div class="list-item">
                                    <!-- Animated checkbox-->
                                    <VIconWrap icon="fas fa-circle" style="color:#99E780" />
                                    <div class="item-meta">
                                    <span>Taks 6</span>
                                    <span class="dark-inverted">Waktu Pelayanan Farmasi</span>
                                    </div>
                                </div>
                                <!-- List item -->
                                <div class="list-item">
                                    <!-- Animated checkbox-->
                                    <VIconWrap icon="fas fa-circle" style="color:#7CE0B2" />
                                    <div class="item-meta">
                                    <span>Taks 7</span>
                                    <span class="dark-inverted">Total Waktu (Admisi - Farmasi)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- chart total poli -->
                <div class="column is-12" v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="200px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-12" v-else>
                    <div class="stat-widget grouped-stat-widget is-straight">
                        <div class="widget-head">
                            <h3 class="dark-inverted">Jumlah Antrean Per Poli</h3>
                        </div>
                        <div class="columns is-multiline mb-5 text-center" v-for="(item, index) in listPoli" :key="index">
                            <div class="column is-3" v-for="(data, index2) in item" :key="index2">
                                <div class="group-content">
                                <div class="chart-container">
                                    <ApexChart
                                        :id="'group-radial-'+ index + '-' + index2"
                                        :height="JumlahAntreanPoliOptions[data.namapoli].chart.height"
                                        :type="JumlahAntreanPoliOptions[data.namapoli].chart.type"
                                        :series="JumlahAntreanPoliOptions[data.namapoli].series"
                                        :options="JumlahAntreanPoliOptions[data.namapoli]"
                                    >
                                    </ApexChart>
                                </div>
                                <span class="dark-inverted">{{ data.total }}</span>
                                <p>{{ data.namapoli.toUpperCase() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column is-12">
                    <VTag @click="showModalFilterAntrean()" color="danger" rounded elevated class="is-pulled-right" style="cursor:pointer">{{
                    item.tglantrean == undefined ? H.formatDateToLocalString(new Date()) : H.formatDateToLocalString(item.tglantrean)
                    }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
                </div>

                <div class="column is-5" v-if="isLoading2">
                    <VPlaceloadWrap>
                        <VPlaceload height="500px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-5" v-else>
                    <div class="dashboard-card">
                        <highcharts :options="chartSumberData"></highcharts>
                    </div>
                </div>
                <div class="column is-7" v-if="isLoading2">
                    <VPlaceloadWrap>
                        <VPlaceload height="500px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-7" v-else>
                    <div class="dashboard-card">
                        <div class="card-head">
                        <h3 class="dark-inverted">Status Antrian</h3>
                        </div>
                        <ApexChart
                        id="bar-chart"
                        :height="StatusAntrianBarOptions.chart.height"
                        :type="StatusAntrianBarOptions.chart.type"
                        :series="StatusAntrianBarOptions.series"
                        :options="StatusAntrianBarOptions"
                        >
                        </ApexChart>
                    </div>  
                </div>

                <!--Table Antrian Per Tanggal-->
                <div class="column is-12" v-if="isLoading2">
                    <VPlaceloadWrap>
                        <VPlaceload height="500px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-12" v-else>
                    <div class="stat-widget table-widget-v1 is-straight">
                        <div class="widget-head">
                            <h3 class="dark-inverted">Antreal Per Tanggal</h3>
                            <!-- <button class="button v-button is-primary is-elevated">
                                <span class="icon is-small">
                                    <i aria-hidden="true" class="fas fa-plus"></i>
                                </span>
                                <span>Add Antrean</span>
                            </button> -->
                        </div>

                        <VSimpleDatatables :options="{ perPage: 5, }" v-if="!ds_antreanpertanggal.loading">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Kode Booking</th>
                                    <th scope="col">NIK</th>
                                    <th scope="col">No Antrean</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Jam Praktek</th>
                                    <th scope="col">Sumber Data</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in ds_antreanpertanggal" :key="index">
                                    <td class="is-media">
                                    <VAvatar
                                            :initials="item.kodepoli"
                                            size="medium"
                                            :color="item.status === 'Selesai dilayani' ? 'success' : 
                                                item.status === 'Belum dilayani' ? 'danger' : 
                                                item.status === 'Sedang dilayani' ? 'warning' : 
                                                item.status === 'Batal' ? 'danger' : 'h-green'
                                            "
                                            squared="true"
                                        />
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.kodebooking }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.nik }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.noantrean }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.tanggal }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.jampraktek }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.sumberdata }}</span>
                                        </div>
                                    </td>
                                    <!-- <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ H.formatDate(new Date(item.estimasidilayani), "YYYY-MM-DD HH:mm") }}</span>
                                            <span>Esitimasi Dilayani</span>
                                        </div>
                                    </td> -->
                                    <td>
                                        <div class="tag-wrap">
                                            <VTag
                                            :class="[
                                                item.status === 'Selesai dilayani' && 'is-green',
                                                item.status === 'Belum dilayani' && 'is-danger',
                                                item.status === 'Sedang dilayani' && 'is-warning',
                                                item.status === 'Batal' && 'is-danger',
                                            ]"
                                            :label="item.status"
                                            elevated
                                            />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </VSimpleDatatables>
                    </div>
                </div>

                <!--Table Antrian Belum Dilayani-->
                <!-- <div class="column is-12">
                    <div class="stat-widget table-widget-v1 is-straight">
                        <div class="widget-head">
                            <h3 class="dark-inverted">Antreal Belum Dilayani</h3>
                        </div>

                        <VSimpleDatatables v-if="!ds_antreanbelumdilayani.loading">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Kode Booking</th>
                                    <th scope="col">NIK</th>
                                    <th scope="col">No Antrean</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Jam Praktek</th>
                                    <th scope="col">Sumber Data</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in ds_antreanbelumdilayani" :key="index">
                                    <td class="is-media">
                                    <VAvatar
                                            :initials="item.kodepoli"
                                            size="medium"
                                            color="info"
                                            squared="true"
                                        />
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.kodebooking }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.nik }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.noantrean }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.tanggal }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.jampraktek }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.sumberdata }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="tag-wrap">
                                            <VTag
                                            :class="[
                                                item.status === 'Selesai dilayani' && 'is-green',
                                                item.status === 'Belum dilayani' && 'is-danger',
                                                item.status === 'Sedang dilayani' && 'is-warning',
                                                item.status === 'Batal' && 'is-danger',
                                            ]"
                                            :label="item.status"
                                            elevated
                                            />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </VSimpleDatatables>
                    </div>
                </div> -->

            </div>
        </div>
    </div>
    <VModal :open="modalFilterWaktu" title="Filter Periode" :noclose="true" size="small" actions="right"
    @close="modalFilterWaktu = false">
        <template #content>
        <form class="modal-form">
            <div class="columns">
                <div class="column is-12">
                    <VField class=" is-rounded-select is-autocomplete-select">
                        <VLabel>Periode</VLabel>
                        <VControl>
                            <Calendar v-model="item.tglwaktutunggu" view="month" dateFormat="mm/yy" style="width: 100%" />
                        </VControl>
                    </VField>
                </div>
            </div>
        </form>
        </template>
        <template #action>
        <VButton icon="feather:search" @click="fetchDashboardPerbulan()" :loading="isLoading" color="primary" raised>
            Filter</VButton>
        </template>
    </VModal>
    <VModal :open="modalFilterAntrean" title="Filter Periode" :noclose="true" size="small" actions="right"
    @close="modalFilterAntrean = false">
        <template #content>
        <form class="modal-form">
            <div class="columns">
                <div class="column is-12" style="text-align: center">
                    <VField class="is-centered">
                    <v-date-picker v-model="item.tglantrean" class="is-centered" trim- :max-date="new Date()" />
                    </VField>
                </div>
            </div>
        </form>
        </template>
        <template #action>
        <VButton icon="feather:search" @click="fetchAntreanPerTanggal()" :loading="isLoading2" color="primary" raised>
            Filter</VButton>
        </template>
    </VModal>
</template>
<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'

import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { onceImageErrored } from '/@src/utils/via-placeholder'

import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as H from '/@src/utils/appHelper'
import Calendar from 'primevue/calendar';

useHead({
  title: 'Dashboard Antrian Online - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const router = useRouter()
const route = useRoute()

const themeColors = useThemeColors()
let ds_dasboardperbulan: any = ref([])
let ds_antreanpertanggal: any = ref([])
let ds_antreanbelumdilayani: any = ref([])
let listPoli: any = ref([])
let avg_1: any = ref(0)
let avg_2: any = ref(0)
let avg_3: any = ref(0)
let avg_4: any = ref(0)
let avg_5: any = ref(0)
let avg_6: any = ref(0)
let avg_all: any = ref(0)
let jumlahAntrean: any = ref(0)
let isLoading = ref(false)
let isLoading2 = ref(false)
let modalFilterWaktu = ref(false)
let modalFilterAntrean = ref(false)
const item: any = reactive({
    tglwaktutunggu: new Date(),
    tglantrean: new Date()
})
let WaktuTungguOptions: any = ref({
    series: [],
    chart: {
        height: 400,
        type: 'area',
        toolbar: {
            show: false,
        },
    },
})
let JumlahAntreanPoliOptions: any = ref([])
let StatusAntrianBarOptions: any = ref({
    series: [],
    chart: {
        height: 345,
        type: 'bar',
        toolbar: {
            show: false,
        },
    },
})
let chartSumberData: any = ref({
    chart: {
        height: 400,
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
      text: 'Sumber Data',
      align: 'left'
    },
    credits: {
      enabled: false
    },
    tooltip: {
      pointFormat: '{series.name}: <b>{point.y}</b>'
    },
    accessibility: {
      point: {
        valueSuffix: '%'
      }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
    series: []
})
let ColorSeries: any = ref([
    '#7CE0B2',
    '#99E780',
    '#E1D777',
    '#DD7C7C',
    '#7FDDE3',
    '#8387E9',
    '#DB7EE3',
])
let ColorSeriesPoli: any = ref([
    '#E14545',
    '#D545E1',
    '#454CE1',
    '#45D8E1',
    '#45E15E',
    '#9DE145',
    '#E18745',
])

const clearRef = () => {
    avg_1.value = 0
    avg_2.value = 0
    avg_3.value = 0
    avg_4.value = 0
    avg_5.value = 0
    avg_6.value = 0
    avg_all.value = 0
    jumlahAntrean.value = 0
} 

const fetchDashboardPerbulan = async ()  => {
    clearRef()
    if(!item.tglwaktutunggu) {
        item.tglwaktutunggu = new Date()
    }

    let json = {
        "url": `dashboard/waktutunggu/bulan/${H.formatDate(item.tglwaktutunggu,'MM')}/tahun/${H.formatDate(item.tglwaktutunggu,'YYYY')}/waktu/rs`,
        "jenis": "antrean",
        "method": "GET",
        "data": null
    }

    isLoading.value = true
    await useApi()
    .postNoMessage(`/bridging/bpjs/tools`, json)
    .then((response: any) => {
        isLoading.value = false
        ds_dasboardperbulan.value = response.response.list

        // Start Hitung waktu rata rata
        WaktuRata()
        ChartWaktuTunggu()
        JumlahAntreanPoli()

        modalFilterWaktu.value = false
    }).catch((err) => {
        ds_dasboardperbulan.value = []
        isLoading.value = false
    })
}
const WaktuRata = () => {
    var data = ds_dasboardperbulan.value
    for (let i = 0; i < data.length; i++) {
        const element = data[i];
        jumlahAntrean.value = jumlahAntrean.value + element.jumlah_antrean
        avg_1.value = avg_1.value + element.avg_waktu_task1
        avg_2.value = avg_2.value + element.avg_waktu_task2
        avg_3.value = avg_3.value + element.avg_waktu_task3
        avg_4.value = avg_4.value + element.avg_waktu_task4
        avg_5.value = avg_5.value + element.avg_waktu_task5
        avg_6.value = avg_6.value + element.avg_waktu_task6
    }

    if (jumlahAntrean.value > 0) {
        let totaldata = data.length;
        let total_1 = avg_1.value/totaldata;
        let total_2 = avg_2.value/totaldata;
        let total_3 = avg_3.value/totaldata;
        let total_4 = avg_4.value/totaldata;
        let total_5 = avg_5.value/totaldata;
        let total_6 = avg_6.value/totaldata;
        avg_1.value = formatJam(total_1);
        avg_2.value = formatJam(total_2);
        avg_3.value = formatJam(total_3);
        avg_4.value = formatJam(total_4);
        avg_5.value = formatJam(total_5);
        avg_6.value = formatJam(total_6);
        avg_all.value = formatJam((total_1+total_2+total_3+total_4+total_5+total_6)/6);
    }
}
const ChartWaktuTunggu = () => {
    var data = ds_dasboardperbulan.value
    // ambil tanggal dulu
    var tgl = []
    for (let i = 0; i < data.length; i++) {
        const element = data[i];
        if (!tgl.includes(element.tanggal))
            tgl.push(element.tanggal)

    }
    tgl.sort(compareDates);
    var tglseminggulast = tgl.slice(-7);

    var dt1 = []
    var dt2 = []
    var dt3 = []
    var dt4 = []
    var dt5 = []
    var dt6 = []
    var dttotal = []
    for (let j = 0; j < tglseminggulast.length; j++) {
        const element = tglseminggulast[j];
        let t1 = 0;
        let t2 = 0;
        let t3 = 0;
        let t4 = 0;
        let t5 = 0;
        let t6 = 0;
        let ttotal = 0;
        let length = 0;
        for (let k = 0; k < data.length; k++) {
            const element2 = data[k];
            if(element == element2.tanggal) {
                length = length + 1
                t1 = t1 + element2.avg_waktu_task1
                t2 = t2 + element2.avg_waktu_task2
                t3 = t3 + element2.avg_waktu_task3
                t4 = t4 + element2.avg_waktu_task4
                t5 = t5 + element2.avg_waktu_task5
                t6 = t6 + element2.avg_waktu_task6
            }
        }
        t1 = t1/length
        t2 = t2/length
        t3 = t3/length
        t4 = t4/length
        t5 = t5/length
        t6 = t6/length
        let total = (t1 + t2 + t3 + t4 + t5 + t6)/6
        dt1.push(formatMenit(t1));
        dt2.push(formatMenit(t2));
        dt3.push(formatMenit(t3));
        dt4.push(formatMenit(t4));
        dt5.push(formatMenit(t5));
        dt6.push(formatMenit(t6));
        dttotal.push(formatMenit(total));
    }

    var series = [
        {
            name: 'Task 1',
            data: dt1,
        },
        {
            name: 'Task 2',
            data: dt2,
        },
        {
            name: 'Task 3',
            data: dt3,
        },
        {
            name: 'Task 4',
            data: dt4,
        },
        {
            name: 'Task 5',
            data: dt5,
        },
        {
            name: 'Task 6',
            data: dt6,
        },
        {
            name: 'Task 7',
            data: dttotal,
        },
    ]
    WaktuTungguOptions.value = {
        series: series,
        chart: {
            height: 400,
            type: 'area',
            toolbar: {
                show: false,
            },
        },
        colors: [ColorSeries.value[3], 
        ColorSeries.value[4], 
        ColorSeries.value[5], 
        ColorSeries.value[6], 
        ColorSeries.value[2], 
        ColorSeries.value[1], 
        ColorSeries.value[0]],
        title: {
            text: 'Rata rata waktu tunggu',
            align: 'left',
        },
        legend: {
            position: 'top',
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: [2, 2, 2, 2, 2, 2, 2],
            curve: 'smooth',
        },
        xaxis: {
            type: 'datetime',
            categories: tglseminggulast,
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm',
            },
            y: {
                formatter: function (val) {
                    return val + ' Menit'; // Label yang akan ditambahkan ke dalam tooltip
                },
            },
        }, 
    }
    
}
const JumlahAntreanPoli = () => {
    JumlahAntreanPoliOptions.value = []
    listPoli.value = []

    let namapoli = []
    let totalpoli = []
    let totalNilai = 0
    for (let i = 0; i < ds_dasboardperbulan.value.length; i++) {
        const element = ds_dasboardperbulan.value[i];
        totalNilai = totalNilai + element.jumlah_antrean
        if(totalpoli[element.namapoli] == undefined)
            totalpoli[element.namapoli] = 0

        totalpoli[element.namapoli] = totalpoli[element.namapoli] + element.jumlah_antrean
        if (!namapoli.includes(element.namapoli))
            namapoli.push(element.namapoli)
    }
    // gabungkan totalnya
    for (let i = 0; i < namapoli.length; i++) {
        const datanya = namapoli[i]
        let persentase = 0
        if (totalNilai > 0) {
            persentase = (parseFloat(totalpoli[datanya]) / parseFloat(totalNilai)) * 100;
        }
        listPoli.value.push({ namapoli: namapoli[i], total: totalpoli[datanya], persentase: persentase.toFixed(2) });
        // render chartnya
        JumlahAntreanPoliOptions.value[datanya] = {
            series: [persentase.toFixed(2)],
            chart: {
                height: 125,
                type: 'radialBar',
                offsetY: -10,
                toolbar: {
                show: false,
                },
            },
            colors: [ColorSeriesPoli.value[parseInt(getRandomArbitrary(0,6))]],
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '35%',
                    },
                    dataLabels: {
                        show: false,
                    },
                },
            },
            labels: [''],
        }
    }
    listPoli.value = pecahArray(listPoli.value, 4);
    console.log(listPoli.value)
}
function compareDates(a, b) {
  return new Date(a) - new Date(b);
}
function getRandomArbitrary(min, max) {
    return Math.random() * (max - min) + min;
}
const formatJam = (jam) => {
    var angka = jam;

    // Menghitung jam, menit, dan detik
    var jam = Math.floor(angka / 3600);
    var sisaDetik = angka % 3600;
    var menit = Math.floor(sisaDetik / 60);
    var detik = Math.round(sisaDetik % 60);

    // Mengonversi ke format jam (hh:mm:ss)
    var formatJam = (jam < 10 ? '0' : '') + jam + ':' +
                    (menit < 10 ? '0' : '') + menit + ':' +
                    (detik < 10 ? '0' : '') + detik;
    return formatJam;
}
const formatMenit = (jam) => {
    // Angka yang akan diubah ke format menit
    var angka = jam;

    // Menghitung menit dan detik
    var menit = Math.floor(angka / 60);
    var detik = Math.round(angka % 60);

    // Mengonversi ke format menit (mm:ss)
    var formatMenit = (menit < 10 ? '0' : '') + menit + ':' +
                    (detik < 10 ? '0' : '') + detik;
    return formatMenit;
}
function pecahArray(arrayAsal, panjangPerPecahan) {
  const hasilPecahan = [];
  
  for (let i = 0; i < arrayAsal.length; i += panjangPerPecahan) {
    const pecahan = arrayAsal.slice(i, i + panjangPerPecahan);
    hasilPecahan.push(pecahan);
  }
  
  return hasilPecahan;
}

const fetchAntreanPerTanggal = async ()  => {
    if(!item.tglantrean) {
        item.tglantrean = new Date()
    }

    let json = {
        "url": `antrean/pendaftaran/tanggal/${H.formatDate(item.tglantrean,'YYYY-MM-DD')}`,
        "jenis": "antrean",
        "method": "GET",
        "data": null
    }

    isLoading2.value = true
    await useApi()
    .postNoMessage(`/bridging/bpjs/tools`, json)
    .then((response: any) => {
        isLoading2.value = false
        ds_antreanpertanggal.value = response.response

        ChartSumberData()
        ChartStatusAntrian()
        // console.log(response)

        modalFilterAntrean.value = false
    }).catch((err) => {
        ds_antreanpertanggal.value = []
        isLoading2.value = false
    })
}

const ChartSumberData = () => {
    var data = ds_antreanpertanggal.value
    var sumberdata = []
    let totalsumberdata = []

    for (let i = 0; i < data.length; i++) {
        const element = data[i];

        if(totalsumberdata[element.sumberdata] == undefined)
            totalsumberdata[element.sumberdata] = 1
        else
            totalsumberdata[element.sumberdata] = totalsumberdata[element.sumberdata] + 1

        if (!sumberdata.includes(element.sumberdata))
            sumberdata.push(element.sumberdata)
    }

    let seriesData = []
    for (let i = 0; i < sumberdata.length; i++) {
        const datanya = sumberdata[i]
        seriesData.push({
            name: datanya,
            y: totalsumberdata[datanya]
        })
    }

    let seriesSumberData = [{
        name: "Sumber Data",
        colorByPoint: true,
        data: seriesData
    }]

    chartSumberData.value = {
        chart: {
            height: 400,
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Sumber Data',
            align: 'left'
        },
        credits: {
            enabled: false
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: seriesSumberData
    }
}
const ChartStatusAntrian = () => {
    var data = ds_antreanpertanggal.value
    var statusAntrian = []
    let totalstatusAntrian = []
    for (let i = 0; i < data.length; i++) {
        const element = data[i];

        if(totalstatusAntrian[element.status] == undefined)
            totalstatusAntrian[element.status] = 1
        else
            totalstatusAntrian[element.status] = totalstatusAntrian[element.status] + 1

        if (!statusAntrian.includes(element.status))
            statusAntrian.push(element.status)
    }

    let seriesStatusAntrian = []
    for (let i = 0; i < statusAntrian.length; i++) {
        const datanya = statusAntrian[i]
        seriesStatusAntrian.push({
            name: datanya,
            data: [
                {
                    x: 'jumlah',
                    y: totalstatusAntrian[datanya]
                }
            ]
        })
    }
    // render chart
    StatusAntrianBarOptions.value = {
        series: seriesStatusAntrian,
        chart: {
            height: 345,
            type: 'bar',
            toolbar: {
                show: false,
            },
        },
        colors: [
        ColorSeries.value[parseInt(getRandomArbitrary(0,6))],
        ColorSeries.value[parseInt(getRandomArbitrary(0,6))],
        ColorSeries.value[parseInt(getRandomArbitrary(0,6))],
        ColorSeries.value[parseInt(getRandomArbitrary(0,6))],
        ColorSeries.value[parseInt(getRandomArbitrary(0,6))],
        ColorSeries.value[parseInt(getRandomArbitrary(0,6))],
        ],
        dataLabels: {
            enabled: false,
        },
        noData: {
            text: 'No Data Avaliable',
        },
        xaxis: {
            type: 'category',
            tickPlacement: 'on',
            labels: {
                rotate: -45,
                rotateAlways: true,
            },
        },
    } 
}

const fetchAntreanBelumDilayani = async ()  => {
    let json = {
        "url": `antrean/pendaftaran/aktif`,
        "jenis": "antrean",
        "method": "GET",
        "data": null
    }

    // isLoading.value = true
    await useApi()
    .postNoMessage(`/bridging/bpjs/tools`, json)
    .then((response: any) => {
        // isLoading.value = false
        ds_antreanbelumdilayani.value = response.response
        // console.log(response)
    }).catch((err) => {
        ds_antreanbelumdilayani.value = []
        // isLoading.value = false
    })
}

const showModalFilterWaktu = () => {
  modalFilterWaktu.value = true
}

const showModalFilterAntrean = () => {
  modalFilterAntrean.value = true
}


fetchDashboardPerbulan()
fetchAntreanPerTanggal()
fetchAntreanBelumDilayani()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/module/integrasi-sistem/antrol';
</style>