<template>
  <div class="dashboard-body">
    <div class="columns is-multiline">
      <div class="column is-6">
        <VButton circle icon="feather:check-circle" raised bold color="info" @click="kyc()" outlined class="mr-2"
          :disabled="setting.client_id_IHS == undefined">
          Verifikasi Profil (KYC)
        </VButton>
      </div>
      <div class="column is-6">

        <VTag @click="showModalFilter()" color="danger" rounded elevated class="is-pulled-right" style="cursor:pointer">{{
          H.formatDateToLocalString(item.periode.start) == H.formatDateToLocalString(item.periode.end) ?
          H.formatDateToLocalString(item.periode.start) :
          H.formatDateToLocalString(item.periode.start) + ' - ' + (item.periode.end ?
            H.formatDateToLocalString(item.periode.end) : '')
        }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
      </div>
      <!-- <VLoader size="large" :active="isLoading" :translucent="true"> -->

      <div class="columns is-multiline" v-if="isLoading" style=" display: contents;">
        <div class="column is-6">
          <VCard>
            <div class="columns is-multiline">
              <div class="column is-6" v-for="i in 4">
                <VPlaceloadWrap>
                  <VPlaceload height="120px" width="100%" class="mx-2" />
                </VPlaceloadWrap>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-6">
          <VCard>
            <VPlaceloadWrap>
              <VPlaceload height="250px" width="100%" class="mx-2" />
            </VPlaceloadWrap>
          </VCard>
        </div>
        <div class="column is-6">
          <VCard>
            <VPlaceloadWrap>
              <VPlaceload height="250px" width="100%" class="mx-2" />
            </VPlaceloadWrap>
          </VCard>
        </div>
        <div class="column is-6">
          <VCard>
            <VPlaceloadWrap>
              <VPlaceload height="250px" width="100%" class="mx-2" />
            </VPlaceloadWrap>
          </VCard>
        </div>
      </div>
      <div class="columns is-multiline" v-else>
        <div class="column is-6">
          <div class="dashboard-card">
            <h4 class="dark-inverted">Resume</h4>
            <div class="quick-stats">
              <div class="quick-stats-inner">
                <div class="quick-stat">
                  <VBlock :title="item.encounter" subtitle="Encounter" center m-responsive t-responsive>
                    <template #icon>
                      <VIconBox color="purple" rounded>
                        <i aria-hidden="true" class="lnil lnil-users"></i>
                      </VIconBox>
                    </template>
                  </VBlock>
                </div>
                <div class="quick-stat">
                  <VBlock :title="item.condition" subtitle="Condition" center m-responsive t-responsive>
                    <template #icon>
                      <VIconBox color="orange" rounded>
                        <i aria-hidden="true" class="lnil lnil-sthethoscope"></i>
                      </VIconBox>
                    </template>
                  </VBlock>
                </div>
                <div class="quick-stat">
                  <VBlock :title="item.observation" subtitle="Observation" center m-responsive t-responsive>
                    <template #icon>
                      <VIconBox color="green" rounded>
                        <i aria-hidden="true" class="lnil lnil-notification"></i>
                      </VIconBox>
                    </template>
                  </VBlock>
                </div>
                <div class="quick-stat">
                  <VBlock :title="item.medicationdispense" subtitle="Medication" center m-responsive t-responsive>
                    <template #icon>
                      <VIconBox color="info" rounded>
                        <i aria-hidden="true" class="lnil lnil-medicine-alt"></i>
                      </VIconBox>
                    </template>
                  </VBlock>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-6">
          <div class="dashboard-card">

            <ApexChart id="apex-chart-22" :height="270" :type="'area'" :series="customersOptions.series"
              :options="customersOptions">
            </ApexChart>
          </div>
        </div>
        <div class="column is-6">

          <div class="dashboard-card is-gauge">
            <h4 class="dark-inverted">Total Medication</h4>

            <highcharts :options="chartMedication"></highcharts>
          </div>
        </div>
        <div class="column is-6">
          <div class="dashboard-card">
            <h4 class="dark-inverted">Top 10 Diagnosis</h4>

            <Chart type="bar" :data="chartData" :options="chartOptions" :height="170" class="h-30rem" />
          </div>
        </div>
      </div>
      <!-- </VLoader> -->
    </div>
  </div>
  <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
    @close="modalFilter = false">
    <template #content>
      <form class="modal-form">
        <div class="columns">
          <div class="column is-12" style="text-align: center">
            <VField class="is-centered">
              <v-date-picker v-model="item.periode" class="is-centered" is-range trim-weeks :max-date="new Date()" />
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:search" @click="fetchData()" :loading="isLoading" color="primary" raised>
        Filter</VButton>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'
import { teamGaugeOptions } from '/@src/data/dashboards/personal-v1/teamGaugeChart'
import Chart from 'primevue/chart';
import * as H from '/@src/utils/appHelper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { reactive, ref } from 'vue';
import { useApi } from '/@src/composable/useApi'
const themeColors = useThemeColors()
const modalFilter = ref(false)
const isLoading = ref(false)
const showModalFilter = () => {
  modalFilter.value = true
}
const item: any = reactive({
  periode: reactive({
    start: new Date(new Date().setDate(new Date().getDate() - 5)),
    end: new Date(),
  }),
  encounter: 0,
  condition: 0,
  observation: 0,
  medicationdispense: 0,
})
const setting: any = ref({})
let colorss = Object.values(themeColors)
const chartMedication: any = ref({
  title: {
    text: '',
    align: 'left'
  },
  credits: {
    enabled: false
  }
})

const diagnosisChartOptions: any = ref({
  series: [
    {
      name: 'Jumlah',
      data: [],
    },
  ],
  chart: {
    height: 265,
    type: 'bar',
    toolbar: {
      show: false,
    },
  },
  plotOptions: {
    bar: {
      dataLabels: {
        position: 'top', // top, center, bottom
      },
    },
  },
  dataLabels: {
    enabled: true,
    formatter: function (val: string) {
      return val
    },
    offsetY: -20,
    style: {
      fontSize: '12px',
      colors: ['#304758'],
    },
  },
  xaxis: {
    categories: [],
    position: 'top',
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#D8E3F0',
          colorTo: '#BED1E6',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5,
        },
      },
    },
    tooltip: {
      enabled: true,
    },
  },
  yaxis: {
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
    labels: {
      show: false,
      formatter: function (val: string) {
        return val
      },
    },
  },
  colors: [themeColors.green, themeColors.secondary, themeColors.orange],
  title: {
    text: 'Top 10 Diagnosis',
    align: 'left',
  },
})

const getDatesBetween = (startDate: any, endDate: any) => {
  const dates = [];
  let currentDate = new Date(startDate);

  while (currentDate <= endDate) {
    dates.push(new Date(currentDate));
    currentDate.setDate(currentDate.getDate() + 1);
  }

  return dates;
}

let cat = []
const daCat = getDatesBetween(item.periode.start, item.periode.end);

for (let x = 0; x < daCat.length; x++) {
  let element = daCat[x];
  cat.push(H.formatDate(element, 'YYYY-MM-DD'))
}


const customersOptions: any = ref({
  series: [
    {
      name: 'RAWAT JALAN',
      data: [],
    },
    {
      name: 'RAWAT INAP',
      data: [],
    }],
  chart: {
    height: 295,
    type: 'area',
    toolbar: {
      show: false,
    },
  },
  colors: [themeColors.accent, themeColors.info],
  title: {
    text: 'Trend Encounter',
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
    categories: cat
  },
  tooltip: {
    x: {
      format: 'dd/MM/yy HH:mm',
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


const options17 = ref({
  series: [],
  chart: {
    height: 295,
    type: 'pie',
  },
  colors: [
    themeColors.accent,
    themeColors.info,
    themeColors.green,
    themeColors.purple,
    themeColors.orange,
    themeColors.accent,
    themeColors.info,
    themeColors.green,
    themeColors.purple,
    themeColors.orange,
    themeColors.accent,
    themeColors.info,
    themeColors.green,
    themeColors.purple,
    themeColors.orange,
  ],
  labels: [],
  responsive: [
    {
      breakpoint: 480,
      options: {
        chart: {
          width: 315,
          toolbar: {
            show: false,
          },
        },
        legend: {
          position: 'top',
        },
      },
    },
  ],
  legend: {
    position: 'right',
    horizontalAlign: 'center',
  },
})

const fetchData = async () => {

  let dari = H.formatDate(item.periode.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.periode.end, 'YYYY-MM-DD')

  let cat = []
  const daCat = getDatesBetween(item.periode.start, item.periode.end);

  for (let x = 0; x < daCat.length; x++) {
    let element = daCat[x];
    cat.push(H.formatDate(element, 'YYYY-MM-DD'))
  }
  isLoading.value = true
  modalFilter.value = false
  customersOptions.value.xaxis.categories = cat

  let seriesMedication = []
  let catMedication: any = []
  let catDiagnosis = []
  let seriesDiagnosis: any = []

  const encounter = await useApi().get(`/bridging/satusehat/get-list?dari=${dari}&sampai=${sampai}&resourcetype=Encounter`)
  const condition = await useApi().get(`/bridging/satusehat/get-list?dari=${dari}&sampai=${sampai}&resourcetype=Condition`)
  const observation = await useApi().get(`/bridging/satusehat/get-list?dari=${dari}&sampai=${sampai}&resourcetype=Observation`)
  const medicationdispense = await useApi().get(`/bridging/satusehat/get-list?dari=${dari}&sampai=${sampai}&resourcetype=MedicationDispense`)
  isLoading.value = false

  item.encounter = encounter.length
  item.condition = condition.length
  item.observation = observation.length
  item.medicationdispense = medicationdispense.length
  let seriesRAJAL = [0, 0, 0, 0, 0, 0, 0]
  let seriesRANAP = [0, 0, 0, 0, 0, 0, 0]
  for (let x = 0; x < cat.length; x++) {
    const element = cat[x];
    for (let y = 0; y < encounter.length; y++) {
      const element2 = encounter[y];
      if (element == element2.period.start.substring(0, 10)) {
        if (element2.class.code == 'AMB') {
          seriesRAJAL[x] = seriesRAJAL[x] + 1
        }
        else {
          seriesRANAP[x] = seriesRANAP[x] + 1
        }
      }
    }

  }

  customersOptions.value.series[0].data = seriesRAJAL
  customersOptions.value.series[1].data = seriesRANAP


  let arrDiag = []
  for (let y = 0; y < condition.length; y++) {
    const element2 = condition[y];
    arrDiag.push({
      name: element2.code.coding[0].code
      // + ' - ' + element2.code.coding[0].display
      , count: 1
    })
  }


  // Create an object to store the counts
  const nameCounts: any = {};

  // Loop through the array and count by name
  arrDiag.forEach((item) => {
    const name = item.name;
    if (nameCounts[name]) {
      nameCounts[name]++; // Increment the count if the name exists
    } else {
      nameCounts[name] = 1; // Initialize the count to 1 if the name doesn't exist
    }
  });
  const resultDiag = Object.keys(nameCounts).map((name) => ({
    name: name,
    count: nameCounts[name],
  }));
  resultDiag.sort((a, b) => b.count - a.count);

  const documentStyle = getComputedStyle(document.documentElement);
  resultDiag.forEach((element, index) => {
    if (index < 10) {

      catDiagnosis.push(element.name)
      seriesDiagnosis.push(
        {
          label: element.name,
          backgroundColor: colorss[index] == '#fff' ? 'rgb(230, 41, 100)' : colorss[index],
          borderColor: colorss[index] == '#fff' ? 'rgb(230, 41, 100)' : colorss[index],
          data: [element.count]
        })
    }
  });

  chartData.value.datasets = seriesDiagnosis

  setChartMedication(medicationdispense)

}

const setChartMedication = (medicationdispense: any) => {

  let seriesMedication = []
  let catMedication: any = []
  let arrMedci = []
  for (let y = 0; y < medicationdispense.length; y++) {
    const element2 = medicationdispense[y];
    arrMedci.push({
      'name': element2.medicationReference.display,
      'count': element2.quantity.value
    })
  }


  // Create an object to store the grouped and summed results
  const groupedCounts: any = {};

  // Loop through the input array and perform the grouping and counting
  arrMedci.forEach((item) => {
    const { name, count } = item;
    if (groupedCounts[name]) {
      // If the name already exists in the groupedCounts object, add the count to the existing value
      groupedCounts[name].count += count;
    } else {
      // If the name doesn't exist, create a new entry in the groupedCounts object
      groupedCounts[name] = { name, count };
    }
  });

  // Convert the groupedCounts object back to an array of objects
  const resultArray: any = Object.values(groupedCounts);

  resultArray.sort((a: any, b: any) => b.count - a.count);
  let catMedicatioDIs = []
  resultArray.forEach((element: any, index: any) => {
    // if (index < 10) {
    // catMedication.push(element.name)
    seriesMedication.push(element.count)
    catMedication.push({
      name: element.name,
      y: element.count
    })
    // }
  });
  console.log(catMedication)



  chartMedication.value = ({
    chart: {
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
    series: [{
      name: 'Medication',
      colorByPoint: true,
      data: catMedication
    }]
  });

}
const kyc = () => {
  let url = ''
  if (window.location.host.indexOf('localhost') > -1) {
    url = H.apiBackend().replace('service/','')
  }else{
    url = H.apiBackend()
  }


  window.open(url + "kyc-satset?petugas=" + H.namaPegawai()
    + "&id=" + setting.value.client_id_IHS
    + "&secret=" + setting.value.client_secret_IHS
    + "&profile=" + H.namaProfile(), "_blank");

}
const fetchSetting = async () => {
  const response = await useApi().get(`/bridging/satusehat/get-setting`)
  setting.value = response

}
fetchSetting()
fetchData()
</script>
<style lang="scss">
.quick-stat .media-flex-center .flex-meta span:first-child,
.quick-stat .media-flex-center .flex-meta>a:first-child {
  font-family: var(--font-alt);
  font-size: 25px;
  color: var(--dark-text);
  font-weight: 600;
}

.quick-stat .v-icon .lnil {
  font-size: 2.4rem;

}

.quick-stat .v-icon {
  height: 60px;
  width: 60px
}

.personal-dashboard-v1 .dashboard-body .dashboard-card .quick-stats .quick-stats-inner .quick-stat {
  padding: 25px 10px
}
</style>
