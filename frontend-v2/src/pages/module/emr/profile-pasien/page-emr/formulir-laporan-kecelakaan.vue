<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                Kembali
              </VButton>
              <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                :disabled="isDisabled" @click="print"> Cetak
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
              </VButton>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-2">
            <h1 style="font-weight: bold;">
              NAMA PASIEN
            </h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.namaPasien" rows="1">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold;">
              TANGGAL LAHIR PASIEN
            </h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
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
          <div class="column is-2">
            <h1 style="font-weight: bold;">
              JENIS KELAMIN
            </h1>
          </div>
          <div class="column is-10" style="display: flex;">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1 " :true-value="items.label"
                  :label="items.label" color="primary" circle />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold;">
              DIPERIKSA TANGGAL
            </h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.tgldiperiksa" mode="date" trim-weeks :max-date="new Date()">
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
          <div class="column is-2">
            <h1 style="font-weight: bold;">
              JENIS RADIASI
            </h1>
          </div>
          <div class="column is-10">
            <VField>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.eksternal" class="pt-1 pb-1 " true-value="Radiasi Eksternal"
                    label="Radiasi Eksternal" color="primary" square />
                </VControl>
              </VField>
            </VField>
          </div>
          <div class="column is-2"></div>
          <div class="column is-10">
            <VField>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.internal" class="pt-1 pb-1 " true-value="Radiasi Internal"
                    label="Radiasi Internal" color="primary" square />
                </VControl>
              </VField>
            </VField>
          </div>
        </div>
      </VCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import MultiSelect from 'primevue/multiselect';
import * as EMR from '../page-emr-plugins/pengkajian-keperawatan-rawat-jalan'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let JenisKelamin: any = ref(EMR.JenisKelamin())
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


const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const d_dokter: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('FormulirPermintaanKonselingGizi') //table mongodb
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
      if (response.length > 0) {
        isDisabled.value = false
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        } else { }
      } else {
        isDisabled.value = true
      }
    })
}
const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Dokter.value = response
}

const diagnosa = async () => {
  await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
    input.value.namadiagnosa = response[0].namadiagnosa
  })
}

const dokterDPJP = async () => {
  await useApi().get(`emr/get-dokter-dpjp?nocmfk=${ID_PASIEN}`).then((response) => {
    input.value.dokter = response.dokter
  })
}

const getDataExist = async () => {
  await useApi().get(
    `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  ).then((response) => {
    input.value.beratBadan = response.beratBadan
    input.value.tinggiBadan = response.tinggiBadan
    input.value.IMT = response.IMT
    console.log()
  })
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
      NOREC_EMRPASIEN.value = response.norec_emr
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}

const print = async () => {
  H.printBlade(`emr/cetak-formulir-permintaan-konseling-gizi?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.tglPembuatan = new Date()
}

setView()
getDataExist()
diagnosa()
dokterDPJP()
loadRiwayat()
setAutoFill()
</script>
