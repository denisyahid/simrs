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
                        <span>{{ items.suhu ? items.suhu : '-' }}<a style="font-size: 0.8rem; color: var(--dark-text);">
                            C</a></span>
                        <span> Suhu </span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.nadi ? items.nadi : '-' }}<a style="font-size: 0.8rem; color: var(--dark-text);">
                            x/mnt</a></span>
                        <span> Nadi </span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.pernapasan ? items.pernapasan : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">
                            x/mnt</a></span>
                        <span> Pernafasan </span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span style=" white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">{{
                          items.tekananDarah ? items.tekananDarah : '-' }}<a
                            style="font-size: 0.6rem; color: var(--dark-text);">
                            mmHg</a></span>
                        <span> Tekanan Darah </span>
                      </div>
                    </div>
                  </div>
                  <div class="calendar-widget-inner">
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.tinggiBadan ? items.tinggiBadan : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">
                            Cm</a></span>
                        <span> Tinggi Badan</span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.beratBadan ? items.beratBadan : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">
                            Kg</a></span>
                        <span> Berat Badan</span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.SPO2 ? items.SPO2 : '-' }}<a style="font-size: 0.8rem; color: var(--dark-text);">
                            %</a></span>
                        <span> SpO2 </span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.lingkarPerut ? items.lingkarPerut : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">
                            Cm</a></span>
                        <span> Lingkar Perut</span>
                      </div>
                    </div>
                  </div>
                  <div class="calendar-widget-inner">
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.GCSe ? items.GCSe : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);"></a></span>
                        <span> GCS E</span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.GCSv ? items.GCSv : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);"></a></span>
                        <span> GCS V</span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.GCSm ? items.GCSm : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);"></a></span>
                        <span> GCS M</span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ items.SkorSkalaNyeri ? items.SkorSkalaNyeri : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);"></a></span>
                        <span> Skala Nyeri</span>
                        <span>{{ items.SkalaNyeri ? items.SkalaNyeri : '-' }}</span>
                      </div>
                    </div>
                  </div>
                  <VTag rounded :label="'IMT'" outlined color="orange" class="mr-2" />
                  <VTag rounded :label="(items.IMT ? items.IMT : '-')" color="orange" class="mr-2" />
                  <VTag rounded :label="H.formatDateIndoSimple(items.tanggal)" outlined class="is-pulled-right" />
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

  <Dialog v-model:visible="modalInput" modal :header="(input.id ? 'Ubah' : 'Tambah ') + ' Catatan Observasi Komprehensif'"
    :style="{ width: '50vw' }">
    <VCard :style="{ height: '25vw' }">
      <div class="columns is-multiline">
        <div class="column is-3">
          <VField label="Tanggal">
            <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks>
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
          <VField label="Suhu"></VField>
          <VField addons>
            <VControl expanded>
              <VInput type="text" class="input" placeholder="Suhu" v-model="input.suhu" :tabindex="4" />
            </VControl>
            <VControl class="field-addon-body">
              <VButton static>°C </VButton>
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="PR"></VField>
          <VField addons>
            <VControl expanded>
              <VInput type="text" class="input" placeholder="PR" v-model="input.nadi" :tabindex="5" />
            </VControl>
            <VControl class="field-addon-body">
              <VButton static>x/menit</VButton>
            </VControl>
          </VField>
        </div>

        <div class="column is-12">
          <VField label="Skala Nyeri"></VField>
          <div class="columns is-multiline">
            <div class="column is-3">
              <Multiselect v-model="input.SkorSkalaNyeri" :attrs="{ label }" placeholder="--Pilih--" label="label"
                :options="d_skorskalanyeri" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
          </div>
        </div>
      </div>
    </VCard>
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalInput = false">
        Tutup
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
        @click="simpan()"> {{ (input.id ? 'Ubah' : 'Tambah ') }}
      </VButton>
    </template>
  </Dialog>
</template>
<script setup lang="ts">
import { followersList } from '/@src/data/widgets/ui/followers'
import { tagList1, tagList2 } from '/@src/data/widgets/ui/tagList'
import { tabs } from '/@src/data/widgets/ui/tabList'
import { iconList } from '/@src/data/widgets/ui/menuList'
// import { notifications } from '/@src/data/widgets/ui/notificationList'
import { trendWidgetChartOptions } from '/@src/data/widgets/charts/trendWidgetChart'
import { onceImageErrored } from '/@src/utils/via-placeholder'
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
useHead({
  title: 'Catatan Observasi Komprehensif - ' + import.meta.env.VITE_PROJECT,
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
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: props.registrasi
})
const COLLECTION: any = ref('catatanobservasikompren')
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date()
})
const filter: any = ref('')
const listVital: any = ref([])
const d_skalanyeri: any = ref([{ value: 'NRS', label: 'NRS' }, { value: 'WBS', label: 'WBS' }, { value: 'FLACC', label: 'FLACC' }, { value: 'BPS', label: 'BPS' }, { value: 'NPA', label: 'NPA' }])
const d_skorskalanyeri: any = ref([
  { value: 1, label: '1' },
  { value: 2, label: '2' },
  { value: 3, label: '3' },
  { value: 4, label: '4' },
  { value: 5, label: '5' },
  { value: 6, label: '6' },
  { value: 7, label: '7' },
  { value: 8, label: '8' },
  { value: 9, label: '9' },
  { value: 10, label: '10' },
])
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

const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
        if (response) {
            input.value.nadi = response.nadi
            input.value.suhu = response.suhu
            input.value.SkorSkalaNyeri = response.SkorSkalaNyeri
        }
    })
}

const loadRiwayat = async () => {
  isLoading.value = true
  try {
    const response = await useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    if (response?.length) {
      listVital.value = response;
      chartData.value = setChartData(listVital.value);
      chartOptions.value = setChartOptions();
    }
  } catch (error) {
    console.error('Failed to load riwayat:', error);
  } finally {
    isLoading.value = false;
  }
}

const add = () => {
  input.value = {
    tanggal: new Date()
  }
  modalInput.value = true
  getDataExist()
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
}
const setChartData = (data: any) => {
  const documentStyle = getComputedStyle(document.documentElement);
  let labels = []
  let seriesNadi = []
  let seriesSuhu = []
  let seriesPernafasan = []
  for (var i = data.length - 1; i >= 0; i--) {
    const element = data[i]
    labels.push(H.formatDate(element.tanggal, 'lll'))
    seriesNadi.push((element.nadi ? parseFloat(element.nadi) : 0))
    seriesSuhu.push((element.suhu ? parseFloat(element.suhu) : 0))
    seriesPernafasan.push((element.pernapasan ? parseFloat(element.pernapasan) : 0))
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
        borderColor: documentStyle.getPropertyValue('--teal-500')
      },
      {
        label: 'Pernafasan',
        data: seriesPernafasan,
        fill: true,
        borderColor: documentStyle.getPropertyValue('--orange-500'),
        tension: 0.4,
        backgroundColor: 'rgba(255,167,38,0.2)'
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
