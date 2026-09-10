<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div class="column">
    <div class="columns is-multiline p-0">
      <div class="column is-12">
        <VCard>
          <div class="tabs-wrapper" :class="['tab-naver']">
            <div class="tabs-inner">
              <div class="tabs is-boxed">
                <ul>
                  <li v-for="(tab, key) in tabs" :key="key" :class="[activeValue === tab.value && 'is-active']">
                    <slot name="tab-link" :active-value="activeValue" :tab="tab" :index="key" :toggle="toggle">
                      <a tabindex="0" @keydown.space.prevent="toggle(tab.value)" @click="toggle(tab.value)">
                        <VIcon v-if="tab.icon" :icon="tab.icon" />
                        <span>
                          <slot name="tab-link-label" :active-value="activeValue" :tab="tab" :index="key">
                            {{ tab.label }}
                          </slot>
                        </span>
                      </a>
                    </slot>
                  </li>
                </ul>
              </div>
            </div>

            <div class="tab-content is-active">
              <Transition :name="'fade-fast'" mode="out-in">
                <slot name="tab" :active-value="activeValue"></slot>
              </Transition>
            </div>
          </div>
        </VCard>
        <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
        <VCard v-else class=" mt-5">
          <div class="columns is-multiline" v-if="activeValue == 1">
            <div class="column is-12">
              <Fieldset :toggleable="true" :legend="'Diagnosa'">
                <div class="columns is-multiline p-3">
                  <div class="column is-6">
                    <span style="font-weight: 600;">Diagnosa Masuk</span>
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <VField class="is-autocomplete-select mt-5" v-slot="{ id }" label="Nama Diagnosa">
                          <VControl icon="feather:search">
                            <AutoComplete v-model="input.diagnosamasukfk" :suggestions="d_Diagnosa"
                              @complete="fetchDiagnosa($event)" :optionLabel="'nama'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                              placeholder="Masukan kode atau nama diagnosa.." :field="'nama'" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField label="Diagnosis">
                          <VTextarea v-model="input.keterangandiagnosamasuk" rows="3" placeholder="Diagnosis"></VTextarea>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-6">
                    <span style="font-weight: 600;">Diagnosa Keluar</span>
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <VField class="is-autocomplete-select mt-5" v-slot="{ id }" label="Nama Diagnosa">
                          <VControl icon="feather:search">
                            <AutoComplete v-model="input.diagnosakeluarfk" :suggestions="d_Diagnosa"
                              @complete="fetchDiagnosa($event)" :optionLabel="'nama'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                              placeholder="Masukan kode atau nama diagnosa.." :field="'nama'" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField label="Diagnosis">
                          <VTextarea v-model="input.keterangandiagnosakeluar" rows="3" placeholder="Diagnosis">
                          </VTextarea>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-6">
              <Fieldset :toggleable="true" :legend="'Antibiotik Profillaksis'">
                <div class="columns is-multiline p-3">
                  <div class="column is-6">
                    <VField label="Antibiotik Profillaksis">
                      <VInput placeholder="Antibiotik Profillaksis" v-model="input.antibiotikprofillaksis"></VInput>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Dosis">
                      <VInput placeholder="Dosis" v-model="input.dosis"></VInput>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField class="is-autocomplete-select mt-5" v-slot="{ id }" label="Ruangan">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="input.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" placeholder="Ruangan.." :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField class="is-autocomplete-select mt-5" v-slot="{ id }" label="Waktu">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="input.jeniswaktufk" :suggestions="d_Waktu" @complete="fetchWaktu($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" placeholder="waktu.." :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-6">
              <Fieldset :toggleable="true" :legend="'Culture Urine'">
                <div class="columns is-multiline p-3">
                  <div class="column is-12">
                    <VField label="Culture Urine">
                      <VTextarea v-model="input.cultururine" placeholder="Culture Urine.." rows="3"></VTextarea>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-6">
              <Fieldset :toggleable="true" :legend="'Culture Darah'">
                <div class="columns is-multiline p-3">
                  <div class="column is-12">
                    <VField label="Culture Darah">
                      <VTextarea v-model="input.culturdarah" placeholder="Culture Darah.." rows="3"></VTextarea>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-6">
              <Fieldset :toggleable="true" :legend="'Culture Sputum'">
                <div class="columns is-multiline p-3">
                  <div class="column is-12">
                    <VField label="Culture Sputum">
                      <VTextarea v-model="input.cultursputum" placeholder="Culture Sputum.." rows="3"></VTextarea>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-12">
              <Fieldset :toggleable="true" :legend="'Faktor Resiko'">
                <div class="columns is-multiline p-3">
                  <div class="column is-2" v-for="(data, index) in pengkasjianSurveelans" :key="index">
                    <span class="mb-5">{{ data.title }}</span>
                    <VField class="mt-1" v-for="(item, index2) in data.value">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[item.model]" :true-value="item.value" :label="item.subTitle" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Temp">
                      <VTextarea v-model="input.temp" placeholder="Temp.." rows="3"></VTextarea>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Hasil Kultur">
                      <VTextarea v-model="input.hasilKultur" placeholder="Hasil Kultur.." rows="3"></VTextarea>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-4">
              <VField label="Tanggal Input">
                <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
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
          </div>
          <div class="columns is-multiline" v-else>
            <div class="column is-12 ">
              <div class="form-section">
                <h3 class="has-text-centered" style="font-weight:600">Riwayat </h3>
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <div class="update-item is-dark-bordered-12 " style="display: block;" v-if="dataSource.length == 0">
                      <div class="search-results-wrapper">
                        <div class="search-results-body ">
                          <!--Search Placeholder -->
                          <div class="page-placeholder">
                            <div class="placeholder-content">
                              <img class="light-image" style=" max-width: 340px;"
                                src="/@src/assets/illustrations/placeholders/search-7.svg" alt="" />
                              <img class="dark-image" style=" max-width: 340px;"
                                src="/@src/assets/illustrations/placeholders/search-7-dark.svg" alt="" />
                              <h3>Data belum ada.</h3>
                              <p class="is-larger">
                                Sepertinya data ini belum di inputkan,
                                silahkan melakukan penginputan terlebih
                                dahulu.
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="list-widget" :class="['is-straight']" v-else>
                      <div class="timeline-wrapper" v-for="(data, index) in dataSource" :key="index">
                        <div class="timeline-header"></div>
                        <div class="timeline-wrapper-inner pt-0">
                          <div class="timeline-container">
                            <div class="timeline-item is-unread ">
                              <div class="date">
                                <span>{{ data.tglsurveilans }}</span>
                              </div>
                              <div :class="'dot is-primary'"></div>
                              <div class="content-wrap is-grey">
                                <div class="content-box ">
                                  <div class="status"></div>
                                  <VIconBox size="small" :color="'facebook'" rounded>
                                    <i :class="'fa fa-envelope'" aria-hidden="true"></i>
                                  </VIconBox>
                                  <div class="box-text" style="width:70%">
                                    <div class="meta-text">
                                      <table class="tb-order">
                                        <tr>
                                          <td>No Survailens</td>
                                          <td>:</td>
                                          <td class="text-value">{{ data.nosurvailens }}</td>
                                        </tr>
                                        <tr>
                                          <td>Ruangan </td>
                                          <td>:</td>
                                          <td class="text-value">{{ data.namaruangan }}</td>
                                        </tr>
                                        <tr>
                                          <td>Nocm /Noregis</td>
                                          <td>:</td>
                                          <td class="text-value">{{ data.nocmregis }} </td>
                                        </tr>
                                      </table>
                                      <div class="is-divider p-0 m-2"></div>
                                      <div>
                                        <p class=" is-clickable"
                                          style="  display: flex;  justify-content: space-between; width: 500px;">
                                          <span class="status mt-1" style="position: absolute;"></span> <span
                                            class="ml-3"> {{ data.namapasien }}
                                          </span>
                                        </p>
                                      </div>

                                    </div>
                                  </div>
                                  <div class="box-end" style="width:30%">
                                    <div class="columns is-multiline">
                                      <div class="column is-12 mt-3">
                                      </div>
                                      <div class="column is-12 ">
                                        <VIconButton icon="feather:trash" color="danger" raised circle
                                          class="mr-2 is-pulled-right" v-tooltip.bubble="'Hapus'"
                                          @click="hapusSurveilans(data)">
                                        </VIconButton>
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
                </div>
              </div>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/pengkajian-surveilans'
import moment from 'moment';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let NOREC_APD = useRoute().query.norec_apd as string
let pengkasjianSurveelans = ref(EMR.pengkajianSurveilans())

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
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const dataSource: any = ref([])
const loadData: any = ref(true)
const d_Diagnosa: any = ref([])
const activeValue: any = ref(1)
const d_Waktu: any = ref([])
const tabs: any = ref([
  { label: 'Buat Baru', value: 1, icon: 'fas fa-plus' },
  { label: 'Riwayat', value: 2, icon: 'fas fa-list' }
])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  tanggal: new Date(),
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('pengkajianSurveilans') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date(),
})
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
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  if (!input.value.diagnosamasukfk) {
    H.alert('warning', 'Diagnosa Masuk Wajib Diisi !');
    return;
  }
  if (!input.value.diagnosakeluarfk) {
    H.alert('warning', 'Diagnosa Keluar Wajib Diisi !');
    return;
  }
  console.log(input.value.ruanganfk);
  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  var datahead = {
    norec: input.value.norec ?? '',
    norec_pd: NOREC_PD,
    norec_apd: NOREC_APD,
    tglsurveilans: moment(input.value.tanggal).format("YYYY-MM-DD HH:mm:ss"),
    diagnosamasukfk: input.value.diagnosamasukfk ? input.value.diagnosamasukfk.id : '',
    diagnosakeluarfk: input.value.diagnosakeluarfk ? input.value.diagnosakeluarfk.id : '',
    keterangandiagnosamasuk: input.value.keterangandiagnosamasuk,
    keterangandiagnosakeluar: input.value.keterangandiagnosakeluar,
    antibiotikprofillaksis: input.value.antibiotikprofillaksis,
    dosis: input.value.dosis,
    ruanganfk: input.value.ruanganfk && input.value.ruanganfk.value,
    jeniswaktufk: input.value.jeniswaktufk && input.value.jeniswaktufk.value,
    cultursputum: input.value.cultursputum,
    culturdarah: input.value.culturdarah,
    cultururine: input.value.cultururine
  }

  var faktorresiko = {
    statusgizi: input.value.statusGizi,
    dm: input.value.dm,
    guladarah: input.value.gulaDarah,
    merokok: input.value.merokok,
    obesitas: input.value.obesitas,
    pemeriksaankultur: input.value.pemeriksaanKultrur,
    temp: input.value.temp,
    hasilkultur: input.value.hasilKultur,
    tglinput: moment(input.value.tanggal).format("YYYY-MM-DD HH:mm:ss")
  }
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  let data = {
    datahead: datahead,
    faktorresiko: faktorresiko
  }

  isLoading.value = true
  useApi().postNoMessage(
    `/ppi/save-data-surveilans`, data).then((response: any) => {
      useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
          isLoading.value = false
          NOREC_EMRPASIEN.value = response.norec_emr
          input.value.id = response.id
        }).catch((e: any) => {
          isLoading.value = false
        })
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

onBeforeMount(async () => {
  try {

    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
  loadData.value = false
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});
const fetchDiagnosa = async (filter: any) => {

  let nama = filter.query ? `?name=${filter.query}` : ''
  const response = await useApi().get(`/emr/get-data-diagnosa${nama}`)
  d_Diagnosa.value = response
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const fetchWaktu = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/jeniswaktu_m?select=id,jeniswaktu&param_search=jeniswaktu&query=${filter.query}&limit=10`)
  d_Waktu.value = response
}
const toggle = (value: string) => {
  activeValue.value = value
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}
const fetchData = async () => {
  useApi().get(`/ppi/get-data-history-surveilans?nocmfk=${ID_PASIEN}`).then((response: any) => {
    dataSource.value = response.data
  })
  console.log(dataSource.value.length);
}
const hapusSurveilans = async (data: any) => {
  console.log(data);

  let objectSave = {
    norec: data.norec
  }
  useApi().post(`/ppi/hapus-data-surveilans`, objectSave).then((response) => {
    fetchData()
  })
}
watch(
  () => activeValue.value,
  (value) => {
    if (value == 2) {
      fetchData()
    }
  }
)
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

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

.is-dark .td-label {

  color: var(--dark-dark-text);
}

.list-widget {
  @include vuero-l-card;

  padding: 30px;

  &:not(:last-child) {
    margin-bottom: 1.5rem;
  }

  &.is-straight {
    @include vuero-s-card;
  }

  .widget-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 32px;
    margin-bottom: 10px;

    h3 {
      color: var(--dark-text);
      font-size: 1.1rem;
      font-weight: 500;
    }
  }

  .inner-list {
    padding: 10px 0;

    .inner-list-item {
      +.inner-list-item {
        margin-top: 24px;
      }
    }
  }
}

.is-dark {
  .list-widget {
    @include vuero-card--dark;
  }
}

.tb-order {
  color: var(--light-text-dark-10);
  font-family: var(--font);
  font-weight: 300;

  .text-value {
    font-family: var(--font-alt);
    color: var(--dark-text);
    font-weight: 600;
  }

  td {
    padding: 0 3px 0 0;
  }
}

.list-widget {
  .icon-timeline {
    .timeline-item {
      position: relative;
      display: flex;
      padding-bottom: 30px;

      &::after {
        content: '';
        position: absolute;
        top: 36px;
        left: 18px;
        width: 1px;
        height: calc(100% - 36px);
        border-left: 1px solid var(--fade-grey-dark-3);
      }

      .timeline-icon {
        position: relative;
        // height: 36px;
        height: auto;
        width: 36px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: var(--white);
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-rounded);
        color: var(--light-text);
        box-shadow: var(--light-box-shadow);

        &::after {
          content: '';
          position: absolute;
          top: 17px;
          left: 40px;
          width: 20px;
          height: 1px;
          border-top: 1px solid var(--fade-grey-dark-3);
        }

        &.is-squared {
          border-radius: 10px;

          img {
            border-radius: 10px;
          }
        }

        &.is-primary {
          background: var(--primary);
          border-color: var(--primary);
          box-shadow: var(--primary-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-info {
          background: var(--info);
          border-color: var(--info);
          box-shadow: var(--info-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-success {
          background: var(--success);
          border-color: var(--success);
          box-shadow: var(--success-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-orange {
          background: var(--orange);
          border-color: var(--orange);
          box-shadow: var(--orange-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-yellow {
          background: var(--yellow);
          border-color: var(--yellow);

          svg {
            color: var(--smoke-white);
          }
        }

        img {
          display: block;
          height: 28px;
          width: 28px;
          border-radius: var(--radius-rounded);
        }

        svg {
          height: 16px;
          width: 16px;
          stroke-width: 1.6px;
        }
      }

      .timeline-content {
        margin-left: 34px;
        line-height: 1.2;
        width: 100%;

        span {
          font-size: 0.85rem;
          color: var(--light-text);

        }

        .is-danger {
          span.icon {
            color: var(--danger--color-invert);
          }

        }

        .is-warning {
          span.icon {
            color: var(--warning--color-invert);
          }

        }

        span.td-label {
          color: var(--dark-text);
        }

        p {
          font-family: var(--font-alt);
          font-size: 0.95rem;
          font-weight: 500;
          color: var(--dark-text);
          width: 220px;
          white-space: nowrap;
          overflow: hidden !important;
          text-overflow: ellipsis;
        }
      }
    }
  }
}

.is-dark {
  .list-widget {
    .icon-timeline {
      .timeline-item {
        &::after {
          border-color: var(--dark-sidebar-light-12) !important;
        }

        .timeline-icon:not(.is-primary):not(.is-info):not(.is-success):not(.is-orange):not(.is-yellow) {
          background: var(--dark-sidebar-light-3) !important;
          border-color: var(--dark-sidebar-light-12) !important;
        }

        .timeline-icon {
          &::after {
            border-color: var(--dark-sidebar-light-12) !important;
          }

          &.is-primary {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: var(--primary-box-shadow);

            svg {
              color: var(--smoke-white);
            }
          }
        }

        .timeline-content {
          p {
            color: var(--dark-dark-text);
          }
        }
      }
    }
  }
}

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item::before {
  display: none;
}
</style>
