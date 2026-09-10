<template>
    <div class="personal-dashboard personal-dashboard-v1">
        <div class="dashboard-body">
            <div class="columns is-multiline">
                <div class="column is-12">
                    <VTag @click="showModalFilter()" color="danger" rounded elevated class="is-pulled-right" style="cursor:pointer">{{
                    item.tglfilter == undefined ? H.formatDate(item.tglfilter, 'DD-MMMM-YYYY') : H.formatDate(item.tglfilter, 'DD-MMMM-YYYY')
                    }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
                </div>

                <div class="column is-12" v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="100px" width="25%" class="mx-2" />
                        <VPlaceload height="100px" width="25%" class="mx-2" />
                        <VPlaceload height="100px" width="25%" class="mx-2" />
                        <VPlaceload height="100px" width="25%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-12" v-else>
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <div class="dashboard-card">
                                <VBlock :title="total_sep_rajal" subtitle="SEP Rawat Jalan" center>
                                <template #icon>
                                    <VIconBox color="warning" rounded>
                                    <i aria-hidden="true" class="lnil lnil-sthethoscope"></i>
                                    </VIconBox>
                                </template>
                                </VBlock>
                            </div>
                        </div>
                        <div class="column is-3">
                            <div class="dashboard-card">
                                <VBlock :title="total_sep_ranap" subtitle="SEP Rawat Inap" center>
                                <template #icon>
                                    <VIconBox color="purple" rounded>
                                    <i aria-hidden="true" class="lnil lnil-hospital-bed-alt-2"></i>
                                    </VIconBox>
                                </template>
                                </VBlock>
                            </div>
                        </div>
                        <div class="column is-3">
                            <div class="dashboard-card">
                                <VBlock :title="total_sep_terbit" subtitle="SEP Terbit" center>
                                <template #icon>
                                    <VIconBox color="info" rounded>
                                    <i aria-hidden="true" class="lnil lnil-cloudy-sun"></i>
                                    </VIconBox>
                                </template>
                                </VBlock>
                            </div>
                        </div>
                        <div class="column is-3">
                            <div class="dashboard-card">
                                <VBlock :title="total_sep_blmterbit" subtitle="SEP Belum Terinput" center>
                                <template #icon>
                                    <VIconBox color="danger" rounded>
                                    <i aria-hidden="true" class="lnil lnil-harddrive"></i>
                                    </VIconBox>
                                </template>
                                </VBlock>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="column is-6">
                    <div class="dashboard-card">
                        <h4 class="dark-inverted">Rekap SEP</h4>
                        <div class="quick-stats">
                            <div class="quick-stats-inner">
                                <div class="quick-stat">
                                    <VBlock :title="total_sep_rajal" subtitle="SEP Rawat Jalan" center m-responsive t-responsive>
                                        <template #icon>
                                        <VIconBox color="warning" rounded>
                                            <i aria-hidden="true" class="lnil lnil-sthethoscope"></i>
                                        </VIconBox>
                                        </template>
                                    </VBlock>
                                </div>
                                <div class="quick-stat">
                                    <VBlock :title="total_sep_ranap" subtitle="SEP Rawat Inap" center m-responsive t-responsive>
                                        <template #icon>
                                        <VIconBox color="purple" rounded>
                                            <i aria-hidden="true" class="lnil lnil-hospital-bed-alt-2"></i>
                                        </VIconBox>
                                        </template>
                                    </VBlock>
                                </div>
                                <div class="quick-stat">
                                    <VBlock :title="total_sep_terbit" subtitle="SEP Terbit" center m-responsive t-responsive>
                                        <template #icon>
                                        <VIconBox color="info" rounded>
                                            <i aria-hidden="true" class="lnil lnil-cloudy-sun"></i>
                                        </VIconBox>
                                        </template>
                                    </VBlock>
                                </div>
                                <div class="quick-stat">
                                    <VBlock title="0" subtitle="Sep Belum Terinput" center m-responsive t-responsive>
                                        <template #icon>
                                        <VIconBox color="danger" rounded>
                                            <i aria-hidden="true" class="lnil lnil-harddrive"></i>
                                        </VIconBox>
                                        </template>
                                    </VBlock>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="column is-5" v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="300px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-5" v-else>
                    <div class="dashboard-card">
                        <h4 class="dark-inverted">Total SEP Per Ruangan</h4>
                        <highcharts :options="chartSEPbyruangan"></highcharts>
                    </div>
                </div>
                <div class="column is-7" v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="300px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-7" v-else>
                    <div class="dashboard-card">
                        <!-- <div class="card-head"> -->
                            <h4 class="dark-inverted">Top 10 Diagnosis</h4>
                        <!-- </div> -->
                        <Chart type="bar" :data="chartData" :options="chartOptions" :height="160" class="h-30rem" />
                    </div>
                </div>

                <div class="column is-6" v-if="isLoadingPA == true || isLoading == true">
                    <VPlaceloadWrap>
                        <VPlaceload height="335px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-6" v-else>
                    <div class="dashboard-card">
                        <!-- <div class="card-head"> -->
                            <h4 class="dark-inverted">Faskes Rujukan</h4>
                        <!-- </div> -->
                        <ApexChart
                        id="bar-chart-rujukanrs"
                        :height="FaskesRujukanBarOptions.chart.height"
                        :type="FaskesRujukanBarOptions.chart.type"
                        :series="FaskesRujukanBarOptions.series"
                        :options="FaskesRujukanBarOptions"
                        >
                        </ApexChart>
                    </div>
                </div>

                <div class="column is-6" v-if="isLoadingKlaim">
                    <VPlaceloadWrap>
                        <VPlaceload height="335px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-6" v-else>
                    <div class="dashboard-card">
                        <!-- <div class="card-head"> -->
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <h4 class="dark-inverted has-text-weight-medium">Total SEP Ter Klaim</h4>
                                </div>
                                <div class="column is-6">
                                    <VTag @click="showModalFilterKlaim()" color="danger" rounded elevated class="is-pulled-right" style="cursor:pointer">{{
                                    item.tglfilterklaim == undefined ? H.formatDate(item.tglfilterklaim, 'MMMM-YYYY') : H.formatDate(item.tglfilterklaim, 'MMMM-YYYY')
                                    }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
                                </div>
                            </div>
                        <!-- </div> -->
                        <ApexChart
                        id="interviews-chart"
                        :height="lineTrandKlaim.chart.height"
                        :type="lineTrandKlaim.chart.type"
                        :series="lineTrandKlaim.series"
                        :options="lineTrandKlaim"
                        >
                        </ApexChart>
                    </div>
                </div>

                <div class="column is-12" v-if="ds_listdataupdatetanggalpulang.loading">
                    <VPlaceloadWrap>
                        <VPlaceload height="500px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-12" v-else>
                    <div class="stat-widget table-widget-v1 is-straight">
                        <div class="widget-head">
                            <h3 class="dark-inverted">List Update Tanggal Pulang</h3>
                        </div>

                        <VSimpleDatatables :options="{ perPage: 5, }">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Nama</th>
                                    <!-- <th scope="col">No Kartu</th> -->
                                    <th scope="col">No SEP</th>
                                    <th scope="col">Tanggal SEP</th>
                                    <th scope="col">Jenis Pelayanan</th>
                                    <th scope="col">Tanggal Pulang</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in ds_listdataupdatetanggalpulang" :key="index">
                                    <td class="is-media">
                                    <VAvatar
                                            :initials="item.nama.substring(0,2)"
                                            size="medium"
                                            color="success"
                                            squared="true"
                                        />
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.nama }}</span>
                                        </div>
                                    </td>
                                    <!-- <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.noKartu }}</span>
                                        </div>
                                    </td> -->
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.noSep }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.tglSep }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.jnsPelayanan == "1" ? "Rawat Inap" : "Rawat Jalan" }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.tglPulang }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="double-line">
                                            <span class="dark-inverted">{{ item.status }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </VSimpleDatatables>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal filter tanggal -->
    <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
    @close="modalFilter = false">
        <template #content>
        <form class="modal-form">
            <div class="columns">
                <div class="column is-12" style="text-align: center">
                    <VField class="is-centered">
                    <v-date-picker v-model="item.tglfilter" class="is-centered" :max-date="new Date()" />
                    </VField>
                </div>
            </div>
        </form>
        </template>
        <template #action>
        <VButton icon="feather:search" @click="filterData()" :loading="isLoading" color="primary" raised>
            Filter</VButton>
        </template>
    </VModal>

    <!-- modal filter bulan -->
    <VModal :open="modalFilterKlaim" title="Filter Periode" :noclose="true" size="small" actions="right"
    @close="modalFilterKlaim = false">
        <template #content>
        <form class="modal-form">
            <div class="columns">
                <div class="column is-12">
                    <VField class=" is-rounded-select is-autocomplete-select">
                        <VLabel>Periode</VLabel>
                        <VControl>
                            <Calendar v-model="item.tglfilterklaim" view="month" dateFormat="mm/yy" style="width: 100%" />
                        </VControl>
                    </VField>
                </div>
            </div>
        </form>
        </template>
        <template #action>
        <VButton icon="feather:search" @click="fetchdataklaim()" :loading="isLoadingKlaim" color="primary" raised>
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
import Chart from 'primevue/chart';

//dummy
import { customersOptions } from '/@src/data/dashboards/personal-v1/customersChart'

useHead({
  title: 'Dashboard Vclaim - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const themeColors = useThemeColors()
let ds_datakunjungan: any = ref([])
let ds_dataklaim: any = ref([])
let ds_listdataupdatetanggalpulang: any = ref([])
let ds_datapemakaianasuransi: any = ref([])
let ref_ruangan: any = ref([])
let total_sep_rajal: any = ref(0)
let total_sep_ranap: any = ref(0)
let total_sep_terbit: any = ref(0)
let total_sep_blmterbit: any = ref(0)
let isLoading = ref(false)
let isLoadingKlaim = ref(false)
let isLoadingPA = ref(false)
let modalFilter = ref(false)
let modalFilterKlaim = ref(false)
let colorss = Object.values(themeColors)
const item: any = reactive({
    tglfilter: new Date(),
    tglfilterklaim: new Date(new Date().getFullYear(), new Date().getMonth() - 3),
})

let chartSEPbyruangan: any = ref({
    chart: {
        height: 300,
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
      text: '',
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
let FaskesRujukanBarOptions: any = ref({
    series: [],
    chart: {
        height: 245,
        type: 'bar',
        toolbar: {
            show: false,
        },
    },
})
const chartData = ref({
  labels: ['Jumlah'],
  datasets: []
});
const chartOptions = ref({
  scales: {
    y: {
      beginAtZero: true
    }
  },
  legend: {
    display: true,
    labels: {
      fontSize: 20,
      fontColor: '#595d6e',
    }
  },
  tooltips: {
    enabled: true
  },
  plugins: {
    title: {
      display: true,
      text: ''
    },
    datalabels: {
      anchor: "end", // Position of the value relative to the bar
      align: "end", // Alignment of the value relative to the bar
      color: "black", // Color of the value
      font: {
        weight: "bold", // Font weight of the value
      },
      formatter: (value: any) => value, // Function to format the value (default is to display the raw value)
    },
  },
});

let lineTrandKlaim: any = ref({
    series: [],
    chart: {
        type: 'area',
        toolbar: {
            show: false,
        },
    },
})
const clearRef = () => {
    total_sep_rajal.value = 0
    total_sep_ranap.value = 0
    total_sep_terbit.value = 0
    total_sep_blmterbit.value = 0
}

// collect data
const fetchdatakunjungan = async () => {
    clearRef()
    if(!item.tglfilter) {
        item.tglfilter = new Date()
    }

    var jsonRanap = {
        "url": `Monitoring/Kunjungan/Tanggal/${H.formatDate(item.tglfilter,'YYYY-MM-DD')}/JnsPelayanan/1`,
        "method": "GET",
        "data": null
    }

    var jsonRajal = {
        "url": `Monitoring/Kunjungan/Tanggal/${H.formatDate(item.tglfilter,'YYYY-MM-DD')}/JnsPelayanan/2`,
        "method": "GET",
        "data": null
    }

    isLoading.value = true
    const [resRajal, resRanap] = await Promise.all([
        useApi().postNoMessage(`/bridging/bpjs/tools`, jsonRanap),
        useApi().postNoMessage(`/bridging/bpjs/tools`, jsonRajal),
    ]);

    let responseData1 = resRajal.metaData.code == 200 ? resRajal.response.sep : [];
    let responseData2 = resRanap.metaData.code == 200 ? resRanap.response.sep : [];

    total_sep_ranap.value = responseData1.length;
    total_sep_rajal.value = responseData2.length;
    ds_datakunjungan.value = [...responseData1, ...responseData2]
    isLoading.value = false
    modalFilter.value = false
    // render chart
    fetchPemakaianAsuransi()
    RekapSEP()
    BarTop10diagnosis()
    PieSEPByRuangan()

}
const fetchdataklaim = async () => {
    const tahun = H.formatDate(item.tglfilterklaim, "YYYY")
    const bulan = H.formatDate(item.tglfilterklaim, "MM")
    const tanggal = getDatesInMonth(tahun, bulan);
    // const tanggal = getDatesBetween(item.tglfilterklaim.start, item.tglfilterklaim.end)
    ds_dataklaim.value = []
    isLoadingKlaim.value = true
    for (let i = 0; i < tanggal.length; i++) {
        const element = H.formatDate(tanggal[i], 'YYYY-MM-DD');
        // Rawat Jalan
        var jsonKlaimRJ = {
            url: `Monitoring/Klaim/Tanggal/${element}/JnsPelayanan/2/Status/3`,
            method: "GET",
            data: null
        }
        await useApi()
        .postNoMessage(`/bridging/bpjs/tools`, jsonKlaimRJ)
        .then((response: any) => {
            const data = response.response.klaim
            let total = 0
            for (let i = 0; i < data.length; i++) {
                const element2 = data[i];
                // total = total + parseFloat(element2.biaya.bySetujui)
                total = total + 1
            }
            ds_dataklaim.value.push({jns: "Rawat Jalan", tgl: element, total: total})
        }).catch((err) => {
            console.log(err)
        })

        // Rawat Jalan
        var jsonKlaimRJ = {
            url: `Monitoring/Klaim/Tanggal/${element}/JnsPelayanan/1/Status/3`,
            method: "GET",
            data: null
        }
        await useApi()
        .postNoMessage(`/bridging/bpjs/tools`, jsonKlaimRJ)
        .then((response: any) => {
            const data = response.response.klaim
            let total = 0
            for (let i = 0; i < data.length; i++) {
                const element2 = data[i];
                // total = total + parseFloat(element2.biaya.bySetujui)
                total = total + 1
            }
            ds_dataklaim.value.push({jns: "Rawat Inap", tgl: element, total: total})
        }).catch((err) => {
            console.log(err)
        })
    }

    isLoadingKlaim.value = false
    modalFilterKlaim.value = false
    lineTrendKlaim()
}
const fetchlistdataupdatetanggalpulang = async () => {
    const bulan = H.formatDate(item.tglfilter, "M")
    const tahun = H.formatDate(item.tglfilter, "YYYY")
    var json = {
        url: `Sep/updtglplg/list/bulan/${bulan}/tahun/${tahun}/`,
        method: "GET",
        data: null
    }

    ds_listdataupdatetanggalpulang.value.loading = true
    await useApi()
    .postNoMessage(`/bridging/bpjs/tools`, json)
    .then((response: any) => {
        ds_listdataupdatetanggalpulang.value.loading = false
        if(response.metaData.code == 200)
            ds_listdataupdatetanggalpulang.value = response.response.list

    }).catch((err) => {
        ds_listdataupdatetanggalpulang.value.loading = false
        ds_listdataupdatetanggalpulang.value = []
    })

}
const fetchPemakaianAsuransi = async () => {
    isLoadingPA.value = true
    await useApi()
    .postNoMessage(`/bridging/bpjs/get-list-pemakaian-asuransi?tgl=${H.formatDate(item.tglfilter, "YYYY-MM-DD")}`)
    .then((response: any) => {
        isLoadingPA.value = false
        ds_datapemakaianasuransi.value = response.response.data

        BarFaskesRujukan()

    }).catch((err) => {
        isLoadingPA.value = false
        ds_datapemakaianasuransi.value = []
    })
}
const fetchrefruangan = async () => {
    let json = {
        "url": `ref/poli`,
        "jenis": "antrean",
        "method": "GET",
        "data": null
    }

    isLoading.value = true
    await useApi()
    .postNoMessage(`/bridging/bpjs/tools`, json)
    .then((response: any) => {
        isLoading.value = false
        ref_ruangan.value = response.response
        fetchdatakunjungan()
    }).catch((err) => {
        isLoading.value = false
        ref_ruangan.value = []
    })
}

const RekapSEP = () => {
    var data = ds_datakunjungan.value;
    total_sep_terbit.value = data.length
}
const BarTop10diagnosis = () => {
    var data = ds_datakunjungan.value;

    const diasnosisCounts: any = {};
    data.forEach((data) => {
        const name = data.diagnosa
        if(diasnosisCounts[name]) {
            diasnosisCounts[name]++
        } else {
            diasnosisCounts[name] = 1
        }
    })
    const dataDiagnosis = Object.keys(diasnosisCounts).map((name) => ({
        name: name,
        count: diasnosisCounts[name],
    }));

    dataDiagnosis.sort((a, b) => b.count - a.count);
    const Top10Dianosis = dataDiagnosis.slice(0, 10);

    const seriesTop10diagnosa: any = []
    for (let i = 0; i < Top10Dianosis.length; i++) {
        const datanya = Top10Dianosis[i]
        seriesTop10diagnosa.push(
        {
          label: datanya.name,
          backgroundColor: colorss[i] == '#fff' ? 'rgb(230, 41, 100)' : colorss[i],
          borderColor: colorss[i] == '#fff' ? 'rgb(230, 41, 100)' : colorss[i],
          data: [datanya.count]
        })
    }
    // render chart
    chartData.value.datasets = seriesTop10diagnosa
}
const BarFaskesRujukan = async () => {
    var datapa = ds_datapemakaianasuransi.value;
    var datakunj = ds_datakunjungan.value;

    let totalinput = 0
    datakunj.forEach((item) => {
        datapa.forEach((item2) => {
            // sep belum input
            if(item.noSep == item2.nosep) {
                totalinput = totalinput + 1
            }
        })
    })
    // sep belum input
    let totalterbit = total_sep_terbit.value
    total_sep_blmterbit.value = parseInt(totalterbit) - parseInt(totalinput)

    // bar chart
    const faskesCounts: any = {};
    for (let i = 0; i < datapa.length; i++) {
        const element = datapa[i];
        const name = element.nmprovider
        if(name == null) continue
        if(faskesCounts[name]) {
            faskesCounts[name]++
        } else {
            faskesCounts[name] = 1
        }
    }

    const seriesFaskes = Object.keys(faskesCounts).map((name) => ({
        name: name,
        data: [
            {
                x: 'jumlah',
                y: faskesCounts[name],
            }
        ],
    }));

    console.log(datapa)
    console.log(seriesFaskes)
    // render chart
    FaskesRujukanBarOptions.value = {
        series: seriesFaskes,
        chart: {
            height: 245,
            type: 'bar',
            toolbar: {
                show: false,
            },
        },
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
const PieSEPByRuangan = () => {
    var data = ds_datakunjungan.value;
    var ruangan = ref_ruangan.value;

    const sepRuanganCounts: any = {};
    data.forEach((data) => {
        let name = data.poli
        if(name !== null) {
            if(name !== "IGD") {
                const dataruangan = ruangan.filter(x => x.kdsubspesialis == name && x.kdpoli == name)
                name = dataruangan[0].nmsubspesialis
            }
        } else {
            name = "Rawat Inap"
        }

        if(sepRuanganCounts[name]) {
            sepRuanganCounts[name]++
        } else {
            sepRuanganCounts[name] = 1
        }
    })
    const dataSEPRuangan = Object.keys(sepRuanganCounts).map((name) => ({
        name: name,
        y: sepRuanganCounts[name],
    }));

    const seriesSEPByRuangan: any = []
    seriesSEPByRuangan.push(
        {
            name: "Total SEP",
            colorByPoint: true,
            data: dataSEPRuangan
        }
    )
    // render pie chart
    chartSEPbyruangan.value.series = seriesSEPByRuangan
}

const lineTrendKlaim = () => {
    const data = ds_dataklaim.value
    let categori: any = [];
    let grouping: any = {};
    data.forEach((item) => {
        if(!categori.includes(item.tgl))
            categori.push(item.tgl)

        if(!grouping[item.jns]) {
            grouping[item.jns] = []
        } else {
            grouping[item.jns].push({tgl:item.tgl, total:item.total})
        }
    })

    const series: any = [];
    Object.keys(grouping).map((name) => {
        const data: any = [];
        categori.forEach((item) => {
            let idx = grouping[name].filter(x => x.tgl == item)
            if(idx[0]) data.push(idx[0].total)
            else data.push(0)
        })
        series.push({name: name,data: data})
    })

    // render chart
    lineTrandKlaim.value = {
        series: series,
        chart: {
            height: 240,
            type: 'area',
            toolbar: {
                show: false,
            },
        },
        colors: [themeColors.info, themeColors.accent],
        title: {
            text: '',
            align: 'left',
        },
        legend: {
            position: 'top',
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: [2, 2, 2],
            curve: 'smooth',
        },
        xaxis: {
            type: 'datetime',
            categories: categori,
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm',
            },
        },
    }
}

const showModalFilterKlaim = () => {
    modalFilterKlaim.value = true
}

const showModalFilter = () => {
    modalFilter.value = true
}

const filterData = () => {
    fetchdatakunjungan()
    fetchlistdataupdatetanggalpulang()
}

const getDatesInMonth = (year: number, month: number) => {
  const dates: Date[] = [];
  const startDate = new Date(year, month - 1, 1); // Month dalam JavaScript dimulai dari 0

  while (startDate.getMonth() === month - 1) {
    dates.push(H.formatDate(new Date(startDate), "YYYY-MM-DD"));
    startDate.setDate(startDate.getDate() + 1);
  }

  return dates;
}

const getDatesBetween = (startDate: any, endDate: any) => {
  const dates = [];
  let currentDate = new Date(startDate);

  while (currentDate <= endDate) {
    dates.push(new Date(currentDate));
    currentDate.setDate(currentDate.getDate() + 1);
  }

  return dates;
}

function getRandomArbitrary(min, max) {
    return Math.random() * (max - min) + min;
}

fetchrefruangan()
fetchdataklaim()
fetchlistdataupdatetanggalpulang()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/module/integrasi-sistem/dashboard-vclaim';
</style>
<style lang="scss">
.dashboard-card .media-flex-center .flex-meta span:first-child,
.dashboard-card .media-flex-center .flex-meta>a:first-child {
  font-family: var(--font-alt);
  font-size: 25px;
  color: var(--dark-text);
  font-weight: 600;
    height: 2rem;
}

.dashboard-card .v-icon .lnil {
  font-size: 2.4rem;

}

.dashboard-card .v-icon {
  height: 60px;
  width: 60px
}

.personal-dashboard-v1 .dashboard-body .dashboard-card .quick-stats .quick-stats-inner .quick-stat {
  padding: 25px 10px
}
</style>
