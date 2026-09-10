<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }} {{ route.params.index_tabs }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpanTemplate="simpanTemplate" @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>

  <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalTemplate = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Tanggal Input</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Tanggal Registrasi</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">No Registrasi</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">No EMR</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="20%">Halaman</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Section</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="5%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.index_tabs }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>

  <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
    @close="showModalTemplateFix = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Template</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table style="border: 1px solid black;" v-if="listTemplateFix.length > 0">
              <thead>
                <tr>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="5%">No</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Tanggal Dibuat</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="20%">Nama Ruangan</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="25%">Nama Template</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplateFix">
                <tr>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.no }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.namatemplate }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;padding: 3px;">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>

  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
      <VCard v-else>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
                @click="pilihTemplateFix(index)"> Pilih Template
              </VButton>
              <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
                @click="pilihTemplate(index)"> Pilih Riwayat
              </VButton>
            </div>

            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>

            <div class="column is-12">
              <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                  template</span></h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.namatemplate" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column p-0">
              <div class="columns is-multiline column">
                <div class="columns is-multiline" v-for="(items, index) in input.listVitalSignDetails" :key="index">
                  <div class="column is-12 is-flex">
                    <VButton color="primary" raised icon="fas fa-plus" class="mr-3" @click="addNewItemVitalSign()"
                      :isLoading="isLoading"> Tambah </VButton>
                    <VButton color="danger" v-if="items.no > 1" raised icon="fas fa-trash" class="mr-3"
                      @click="removeItemVitalSign(index)" :isLoading="isLoading"> Hapus </VButton>
                  </div>
                  <div class="column is-3">
                    <VField label="Tanggal">
                      <VDatePicker v-model="items.tanggal" mode="dateTime" style="width: 100%" trim-weeks
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
                    <VField label="Tekanan Darah"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="items.tekananDarah"
                          :tabindex="3" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>mmHG</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Suhu"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" placeholder="Suhu" v-model="items.suhu" :tabindex="4" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>°C </VButton>
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-3">
                    <VField label="RR"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" placeholder="RR" v-model="items.pernapasan" :tabindex="6" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/menit</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="SpO2"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" placeholder="SaO2" v-model="items.SPO2" :tabindex="7" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="MAP"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" placeholder="MAP" v-model="items.MAP" :tabindex="8" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Cm</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Nadi"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" placeholder="Nadi" v-model="items.nadi" :tabindex="8" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/mnt</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>


            <div class="column is-12">
              <div class="columns is-multiline mt-5" v-if="isLoading">
                <VPlaceloadText :lines="1" class="p-2" />
                <div class="column is-12" v-for="key in 2" :key="key">
                  <VPlaceloadWrap>
                    <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                    <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                    <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                    <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                  </VPlaceloadWrap>
                </div>
              </div>
              <div v-else>
                <div v-for="(items, index) in listVital" :key="index" style="height: 400px; overflow-y: auto;"
                  class="mt-3">
                  <div class="column is-12">
                    <VCard style="border-radius: 16px;">
                      <Chart type="line" :data="chartData" :options="chartOptions" :height="300" class="h-30rem" />
                    </VCard>
                  </div>
                </div>
              </div>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave, onBeforeRouteUpdate } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import TandaTangan from '../../../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import Chart from 'primevue/chart';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
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

const chartData = ref()
const chartOptions = ref()
const listVital: any = ref([])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Dokter = ref([])
const d_Pegawai = ref([])
const d_Diagnosa: any = ref([])
const d_Ruangan: any = ref([])
const dataTTD: any = ref([])
const loadData: any = ref(true)
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
  listVitalSignDetails: [
    {
      no: 1,
    }
  ],
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
// const loadRiwayat = async () => {
//   loadData.value = true;
//   try {
//     const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`);
//     if (response.length) {
//       input.value = response[0];
//       listVital.value = response;

//       if (NOREC_EMRPASIEN.value === '') {
//         NOREC_EMRPASIEN.value = response[0].emrpasienfk;
//       }

//       dataTTD.value = response[0];
//       H.tandaTangan().set("TTDLaporanOperasi", dataTTD.value.TTDLaporanOperasi);
//       H.tandaTangan().set("TTDOperator", dataTTD.value.TTDOperator);

//       if (!input.value.listVitalSignDetails) {
//         input.value.listVitalSignDetails = [];
//       } else {

//         input.value.listVitalSignDetails = response[0].listVitalSignDetails || [];
//       }

//       if (route.params.index_tabs) {
//         chartData.value = setChartData(listVital.value);
//         chartOptions.value = setChartOptions();
//       } else {
//         chartData.value = null;
//         chartOptions.value = null;
//       }
//     }
//   } catch (error) {
//     console.error('Error loading riwayat:', error);
//   } finally {
//     loadData.value = false;
//   }
// }

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  object['TTDLaporanOperasi'] = H.tandaTangan().get("TTDLaporanOperasi");
  object['TTDOperator'] = H.tandaTangan().get("TTDOperator");
  if (route.params.index_tabs) {
    object.index_tabs = parseInt(route.params.index_tabs)
  }
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
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const kembaliKeun = () => {
  window.history.back()
}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    console.log()
    return;
  }
  let ID = input.id ? input.id : ''
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

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    input.value.namatemplate = null
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      listTemplate.value = responselast //set ke inputan
      showModalTemplate.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const addTemplate = (response: any) => {
  input.value = response
  delete input.value['id']
  delete input.value['_id']
  input.value.namatemplate = null
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}

const addRiwayat = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      for (var x = 0; x < responselast.length; x++) {
        responselast[x].no = x + 1
        responselast[x].id = ''
      }
      listTemplateFix.value = responselast //set ke inputan
      showModalTemplateFix.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}
const setAutoFill = async () => {

}

const addNewItemVitalSign = () => {
  input.value.listVitalSignDetails.unshift({
    no: input.value.listVitalSignDetails[input.value.listVitalSignDetails.length - 1].no + 1,
    tgltindakan: new Date(),
  });
}
const removeItemVitalSign = (index: any) => {
  input.value.listVitalSignDetails.splice(index, 1)
}

const setChartData = (data: any) => {
  const documentStyle = getComputedStyle(document.documentElement);
  let labels = []
  let seriesNadi = []
  let seriesSuhu = []
  let seriesTekananDarah = []
  let seriesNafas = []
  let seriesSpO2 = []
  let seriesMap = []
  // console.log('QOI',data)

  for (var i = data.length - 1; i >= 0; i--) {
    const element = data[i]
    // console.log('QOI', element)
    for (var j = element.listVitalSignDetails.length - 1; j >= 0; j--) {
      const element2 = element.listVitalSignDetails[j]
      labels.push(H.formatDate(element2.tanggal, 'lll'))
      seriesNadi.push((element2.nadi ? parseFloat(element2.nadi) : 0))
      seriesSuhu.push((element2.suhu ? parseFloat(element2.suhu) : 0))
      seriesTekananDarah.push((element2.tekananDarah ? parseFloat(element2.tekananDarah) : 0))
      seriesNafas.push((element2.pernapasan ? parseFloat(element2.pernapasan) : 0))
      seriesSpO2.push((element2.SPO2 ? parseFloat(element2.SPO2) : 0))
      seriesMap.push((element2.MAP ? parseFloat(element2.MAP) : 0))
    }
  }

  return {
    labels: labels,
    datasets: [
      {
        label: 'Nadi',
        data: seriesNadi,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--blue-500')
      },
      {
        label: 'Suhu',
        data: seriesSuhu,
        fill: false,
        borderDash: [5, 5],
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--green-500')
      },
      {
        label: 'Tekanan Darah',
        data: seriesTekananDarah,
        fill: true,
        borderColor: documentStyle.getPropertyValue('--red-500'),
        tension: 0.4,
        backgroundColor: 'rgba(255,167,38,0.2)'
      },
      {
        label: 'Nafas',
        data: seriesNafas,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--black-500')
      },
      {
        label: 'SpO2',
        data: seriesSpO2,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--green-500')
      },
      {
        label: 'MAP',
        data: seriesMap,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--red-100')
      }
    ]
  };
};

const setChartOptions = () => {
  const documentStyle = getComputedStyle(document.documentElement);
  const textColor = documentStyle.getPropertyValue('--text-color');
  const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
  const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

  return {
    maintainAspectRatio: false,
    aspectRatio: 0.6,
    plugins: {
      legend: {
        labels: {
          color: textColor
        }
      }
    },
    scales: {
      x: {
        ticks: {
          color: textColorSecondary
        },
        grid: {
          color: surfaceBorder
        }
      },
      y: {
        ticks: {
          color: textColorSecondary
        },
        grid: {
          color: surfaceBorder
        }
      }
    }
  };
}
const loadRiwayat = async () => {
  loadData.value = true;
  try {
    const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`);
    if (response.length) {
      input.value = response[0];
      listVital.value = response;

      if (NOREC_EMRPASIEN.value === '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }

      dataTTD.value = response[0];
      H.tandaTangan().set("TTDLaporanOperasi", dataTTD.value.TTDLaporanOperasi);
      H.tandaTangan().set("TTDOperator", dataTTD.value.TTDOperator);

      if (!input.value.listVitalSignDetails) {
        input.value.listVitalSignDetails = [];
      } else {
        input.value.listVitalSignDetails = response[0].listVitalSignDetails || [];
      }

      if (route.params.index_tabs === 1) {
        chartData.value = setChartData(listVital.value);
        chartOptions.value = setChartOptions();
      } else {
        chartData.value = null;
        chartOptions.value = null;
      }
    }
  } catch (error) {
    console.error('Error loading riwayat:', error);
  } finally {
    loadData.value = false;
  }
};

watch(
  () => route.params.index_tabs,
  (newIndexTabs) => {
    console.log('Index Tabs Berubah:', newIndexTabs);
    console.log('Input Value Sebelum Reset:', input.value);

    if (newIndexTabs) {
      // Reset hanya listVitalSignDetails, bukan seluruh input.value
      input.value.listVitalSignDetails = [];
      loadRiwayat();

      // Ambil cache jika ada
      let rouutename = route.name + '-' + newIndexTabs;
      let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`);
      if (cache) {
        // Gabungkan cache dengan input.value yang ada
        input.value = { ...input.value, ...cache };
      }
    }

    console.log('Input Value Setelah Reset:', input.value);
  },
  { immediate: true }
);

onBeforeMount(async () => {
  try {
    await loadRiwayat();
    let rouutename = route.name + '-' + route.params.index_tabs;
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`);
    if (cache) {
      // Gabungkan cache dengan input.value yang ada
      input.value = { ...input.value, ...cache };
    }
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from.name + '-' + route.params.index_tabs;
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value);
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});

watch(
  () => input.value,
  (newValue) => {
    let rouutename = route.name + '-' + route.params.index_tabs;
    let timeout = null;
    if (timeout) {
      clearTimeout(timeout);
    }
    timeout = setTimeout(() => {
      H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, newValue);
    }, 500);
  },
  { deep: true }
);


setView();
setAutoFill();
</script>

<style lang="scss">
.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
