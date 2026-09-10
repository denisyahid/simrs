<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="search-widget">
        <div class="field">
          <div class="columns is-multiline">
            <div class="column is-2">
              <VButton rounded icon="feather:plus" raised bold @click="add()" color="success" outlined
                :loading="isLoading" class="mr-2">Tambah </VButton>
            </div>
            <div class="column is-10">
              <div class="control">
                <input type="text" v-model="filter" class="input" placeholder="Cari..." />
                <button class="searcv-button" type="button" :loading="isLoading" @click="loadRiwayat">
                  <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                </button>
              </div>
            </div>

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
      <div class="columns is-multiline" v-else>
        <div class="column is-12" v-if="filteredList.length">
          <div class="columns is-multiline">
            <div class="column is-6">
              <UIWidget class="calendar-widget" v-for="(items, index) in filteredList" :key="items.id">
                <template #header>
                  <div class="widget-toolbar">
                    <div class="left">
                      <h3><span class="is-dark-text">Author :</span> {{ items.user_input ?
                        items.user_input.namalengkap
                        : '' }}</h3>
                      <VTag rounded :label="H.formatDateIndoSimple(items.created_at)" color="orange" class="ml-4" />
                      <VTag rounded :label="'Minggu ke-' + items.gestationalAges" />
                    </div>

                    <div class="right">
                      <VIconButton circle icon="feather:edit" raised bold @click="edit(items)" color="info" outlined
                        class="mr-2"> </VIconButton>
                      <VIconButton circle icon="feather:trash" raised bold @click="remove(items)" color="danger"
                        outlined :loading="items.loadingHapus"> </VIconButton>

                    </div>
                  </div>
                </template>
                <template #body>
                  <div class="calendar-widget-inner">
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.weight ? items.weight : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">Kg</a></span>
                        <span> Berat Badan </span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.length ? items.length : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">
                            cm</a></span>
                        <span> Panjang Badan </span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.headCircumference ? items.headCircumference : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">
                            cm</a></span>
                        <span> Lingkar kepala </span>
                      </div>
                    </div>


                  </div>

                  <div class="column is-12">
                    <VMessage color="primary">Catatan : {{ items.catatan ? items.catatan : '-' }}</VMessage>
                  </div>
                  <!-- <small class="is-tanggal">28 minutes ago</small> -->
                </template>
              </UIWidget>
            </div>
            <div class="column is-6">
              <VCard style="border-radius: 16px;">
                <Chart type="line" :data="chartData" :options="chartOptions" :height="300" class="h-30rem" />
              </VCard>
            </div>
          </div>
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

  <Dialog v-model:visible="modalInput" modal :header="(input.id ? 'Ubah ' : 'Tambah ') + 'Catatan Vital Sign'"
    :style="{ width: '50vw' }">
    <VCard>
      <div class="columns is-multiline">
        <div class="column is-4">
          <VField label="Usia Kehamilan"></VField>
          <VField class="is-autocomplete-select" v-slot="{ id }">
            <VControl icon="">
              <Multiselect v-model="input.gestationalAges" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="inputOptions" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </VControl>
          </VField>
        </div>

        <div class="column is-4">
          <VField label="Berat Badan"></VField>
          <VField addons>
            <VControl expanded>
              <VInput type="number" class="input" placeholder="Berat Badan" v-model="input.weight" />
            </VControl>
            <VControl class="field-addon-body">
              <VButton static>Kg</VButton>
            </VControl>
          </VField>
        </div>

        <div class="column is-4">
          <VField label="Panjang Badan"></VField>
          <VField addons>
            <VControl expanded>
              <VInput type="number" class="input" placeholder="Panjang Badan" v-model="input.length" />
            </VControl>
            <VControl class="field-addon-body">
              <VButton static>Cm</VButton>
            </VControl>
          </VField>
        </div>

        <div class="column is-4">
          <VField label="Lingkar Kepalas"></VField>
          <VField addons>
            <VControl expanded>
              <VInput type="number" class="input" placeholder="Lingkar Kepalas" v-model="input.headCircumference" />
            </VControl>
            <VControl class="field-addon-body">
              <VButton static>Cm</VButton>
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <VField label="Catatan">
            <VControl>
              <VTextarea rows="2" placeholder="Tulis Catatan..." v-model="input.catatan"></VTextarea>
            </VControl>
          </VField>
        </div>
      </div>
    </VCard>
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" class="mr-2" light dark-outlined @click="modalInput = false">
        Tutup
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
        @click="simpan()"> {{ (input.id ? 'Ubah' : 'Tambah ') }}
      </VButton>
    </template>
  </Dialog>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useConfirm } from 'primevue/useconfirm'
import { useThemeColors } from '/@src/composable/useThemeColors'
import Dialog from 'primevue/dialog';
import Chart from 'primevue/chart';

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
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
  }
)

const modalInput: any = ref(false)
const isLoading: any = ref(false)
const listColor: any = ref(Object.keys(useThemeColors()))
const chartData = ref();
const chartOptions = ref();
const d_gestationalAges: any = ref(
  Array.from({ length: 15 }, (_, i) => {
    const week = 22 + i * 2;
    return { value: week, label: `${week} minggu` };
  })
);

const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: props.registrasi
})
const COLLECTION: any = ref('FentonPretermGrowthChartGirls')
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date()
})
const filter: any = ref('')
const listVital: any = ref([])
const filteredList = computed(() => {
  if (!filter.value) {
    return listVital.value
  }

  return listVital.value.filter((items: any) => {
    return (
      items.user_input.namalengkap.match(new RegExp(filter.value, 'i'))
    )
  })
})
const inputOptions = ref<any[]>([]);

const loadRiwayat = () => {
  isLoading.value = true;
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  ).then((response) => {
    isLoading.value = false;

    if (response.length) {
      listVital.value = response;

      // Get existing gestationalAges from listVital
      const existingAges = listVital.value.map((item: any) => item.gestationalAges);

      // Filter the options to exclude existing gestationalAges
      inputOptions.value = d_gestationalAges.value.filter(
        (option: any) => !existingAges.includes(option.value)
      );

      // Use the response data to set the chart
      chartData.value = setChartData(listVital.value);
      chartOptions.value = setChartOptions();
    } else {
      // If no data, include all gestational ages
      inputOptions.value = d_gestationalAges.value;
      listVital.value = []; // Reset the list
      chartData.value = setChartData([]);
      chartOptions.value = setChartOptions();
    }
  }).catch((error) => {
    isLoading.value = false;
    console.error("Error loading riwayat:", error);
    // Handle error case
    inputOptions.value = d_gestationalAges.value;
  });
};



const add = () => {
  input.value = {
    tanggal: new Date()
  }
  modalInput.value = true
}
const simpan = () => {
  if (!input.value.gestationalAges) {
    H.alert('warning', 'Usia Kehamilan wajib diisi');
    return;
  }

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
      input.value.id = ''
      NOREC_EMRPASIEN.value = ''
      loadRiwayat()
      modalInput.value = false
    }).catch((e: any) => {
      isLoading.value = false
    })

}
const edit = (e: any) => {
  input.value = e
  NOREC_EMRPASIEN.value = e.emrpasienfk
  modalInput.value = true
}
const remove = async (e: any) => {
  e.loadingHapus = true

  await useApi().post(
    `/emr/hapus-emr`, {
    'norec': e.emrpasienfk,
    'collection': COLLECTION.value
  }).then((response: any) => {
    e.loadingHapus = false
    listVital.value.forEach((element: any, i: any) => {
      if (e.id == element.id) {
        listVital.value.splice(i, 1);
      }
    });
  })
  loadRiwayat();
}
const setChartOptions = () => {
  return {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
        labels: {
          font: {
            size: 14
          },
          boxWidth: 20,
          padding: 15
        }
      },
      title: {
        display: true,
        text: 'Fenton Preterm Growth Chart Boys',
        font: {
          size: 16,
          weight: 'bold'
        }
      }
    },
    scales: {
      x: {
        display: true,
        title: {
          display: true,
          text: 'Gestational Age (Weeks)',
          font: {
            size: 14
          }
        },
        type: 'category',
        labels: Array.from({ length: 15 }, (_, i) => (22 + i * 2).toString()), // Fixed label generation
      },
      lengthAndCircumference: {
        type: 'linear',
        position: 'left',
        min: 15,
        max: 60,
        ticks: {
          stepSize: 5
        },
        title: {
          display: true,
          text: 'Centimeters',
          font: {
            size: 18,
          }
        }
      },
      weight: {
        type: 'linear',
        position: 'right',
        min: 0,
        max: 4,
        ticks: {
          stepSize: 0.5
        },
        title: {
          display: true,
          text: 'Kilograms',
          font: {
            size: 18,
          }
        }
      }
    }
  };
};

// Generate the labels from 22 to 50 with a step size of 2
const labels = Array.from({ length: 15 }, (_, i) => 22 + i * 2);

const setChartData = (data) => {
  const documentStyle = getComputedStyle(document.documentElement);

  // Check if data is properly populated
  if (!data || data.length === 0) {
    console.warn("No data found for the chart");
    return {
      labels: [],
      datasets: []
    };
  }

  // Sort the data based on gestational age
  data.sort((a, b) => (a.gestationalAge || 0) - (b.gestationalAge || 0));

  // Use d_gestationalAges to get the correct labels
  const gestationalAgeLabels = d_gestationalAges.value.map(option => option.label);

  // Map the data
  const weight = data.map(item => parseFloat(item.weight) || 0).reverse();
  const length = data.map(item => parseFloat(item.length) || 0).reverse();
  const headCircumference = data.map(item => parseFloat(item.headCircumference) || 0).reverse();

  return {
    labels: gestationalAgeLabels,
    datasets: [
      {
        label: 'Length (cm)',
        data: length,
        borderColor: documentStyle.getPropertyValue('--green-500'),
        borderWidth: 2,
        fill: false,
        pointRadius: 3,
        pointHoverRadius: 5,
        tension: 0.4,
        yAxisID: 'lengthAndCircumference'
      },
      {
        label: 'Head Circumference (cm)',
        data: headCircumference,
        borderColor: documentStyle.getPropertyValue('--purple-500'),
        borderWidth: 2,
        fill: false,
        pointRadius: 3,
        pointHoverRadius: 5,
        tension: 0.4,
        yAxisID: 'lengthAndCircumference'
      },
      {
        label: 'Weight (kg)',
        data: weight,
        borderColor: documentStyle.getPropertyValue('--blue-500'),
        borderWidth: 2,
        fill: false,
        pointRadius: 3,
        pointHoverRadius: 5,
        tension: 0.4,
        yAxisID: 'weight'
      }
    ]
  };
};

onMounted(() => {

});
watch(
  () => [
    input.value.beratBadan,
    input.value.tinggiBadan],
  (value) => {
    let txtFirstNumberValue: any = input.value.beratBadan;
    let txtSecondNumberValue: any = input.value.tinggiBadan;
    let result: any = parseFloat(txtFirstNumberValue) / (parseFloat(txtSecondNumberValue) / 100
      * parseFloat(txtSecondNumberValue) / 100);

    input.value.IMT = parseFloat(result).toFixed(2)
    if (isNaN(input.value.IMT)) {
      input.value.IMT = 0
    }
  }
)
loadRiwayat()
</script>
<style lang="scss">
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
        font-family: var(--font);
        display: flex;
        justify-content: space-between;
        color: var(--light-text);

        &:hover,
        &:focus {
          color: var(--primary);
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
            color: var(--primary);
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
      font-family: var(--font);
    }

    .right-icon {
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 32px;
      width: 32px;
      min-width: 32px;
      border-radius: var(--radius-rounded);
      color: var(--light-text-light-12);
      transition: all 0.3s; // transition-all test

      &.has-indicator {
        &::after {
          content: '';
          position: absolute;
          top: 3px;
          right: 4px;
          height: 10px;
          width: 10px;
          border-radius: var(--radius-rounded);
          background: var(--secondary);
          border: 1.8px solid var(--white);
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
    font-family: var(--font-alt);
    font-size: 0.9rem;
    color: var(--dark-text);
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
    border-radius: var(--radius-rounded);
    color: var(--light-text-light-12);
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
      color: var(--dark-dark-text);
    }

    .right {
      .right-icon {
        &.has-indicator {
          &::after {
            border-color: var(--dark-sidebar-light-6);
          }
        }
      }
    }
  }
}

small.is-tanggal {
  color: var(--light-text);
}

.is-dark-text {
  color: var(--light-text);
}
</style>
