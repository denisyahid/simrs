<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton
                icon="lnir lnir-arrow-left rem-100"
                light
                dark-outlined
                @click="kembaliKeun()"
              >
                Kembali
              </VButton>
              <VButton
                type="button"
                rounded
                outlined
                color="primary"
                raised
                icon="feather:save"
                :loading="isLoading"
                @click="simpan()"
              >
                Simpan
              </VButton>
              <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                :disabled="isDisabled" @click="print">
                Cetak
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
        <div class="is-flex">
          <div class="column is-2">
            <h1 style="font-weight: bold">NAMA PASIEN:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.namaPasien" class="input" type="text" disabled />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="is-flex">
          <div class="column is-2">
            <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker
                v-model="input.tanggalLahirPasien"
                mode="date"
                trim-weeks
                :max-date="new Date()"
              >
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" disabled />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
        </div>
        <div class="is-flex">
          <div class="column is-2">
            <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
          </div>
          <div class="column is-10" style="display: flex">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.jeniskelamin"
                  class="pt-1 pb-1"
                  :true-value="items.label"
                  :label="items.label"
                  color="primary"
                  disabled
                  circle
                />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="is-flex">
          <div class="column is-2">
            <h1 style="font-weight: bold">No. Rekam Medis:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput
                  type="number"
                  class="input"
                  placeholder="No. Rekam Medis"
                  v-model="input.norm"
                  disabled
                />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="is-flex">
          <div class="column is-2">
            <h1 style="font-weight: bold">Indikasi:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="Indikasi"
                  v-model="input.indikasi"
                />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="is-flex">
          <div class="column is-2">
            <h1 style="font-weight: bold">Sonographer:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <AutoComplete
                  placeholder="Sonographer"
                  v-model="input.sonographer"
                  :suggestions="d_Dokter"
                  @complete="fetchDokter($event)"
                  :optionLabel="'label'"
                  :dropdown="true"
                  :minLength="10"
                  :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'"
                  :field="'label'"
                  class="mt-2"
                />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="is-flex">
          <div class="column is-2">
            <h1 style="font-weight: bold">Reviewer:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <AutoComplete
                  placeholder="Reviewer"
                  v-model="input.reviewer"
                  :suggestions="d_Dokter"
                  @complete="fetchDokter($event)"
                  :optionLabel="'label'"
                  :dropdown="true"
                  :minLength="10"
                  :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'"
                  :field="'label'"
                  class="mt-2"
                />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="is-flex">
          <div class="column is-2">
            <h1 style="font-weight: bold">Tanggal Eksaminasi:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker
                v-model="input.tglEksaminasi"
                mode="date"
                trim-weeks
                :max-date="new Date()"
              >
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" disabled />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
        </div>

        <div class="is-flex">
          <div class="column is-2">
            <h1 style="font-weight: bold">Study Type:</h1>
          </div>
          <div class="column is-10">
            <VField class="is-autocomplete-select" v-slot="{ id }">
              <VControl>
                <Multiselect
                  v-model="input.studyType"
                  placeholder="--Pilih--"
                  label="label"
                  :options="studyType"
                  :searchable="true"
                  track-by="label"
                  mode="single"
                  autocomplete="off"
                >
                </Multiselect>
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-12" v-if="input.studyType === 'TransThoracaEchoBayi'">
          <Fieldset :toggleable="true" legend="Trans Thoraca Echo Bayi">
            <div class="is-12">
              <div
                class="column is-multiline p-3"
                v-for="(item, index) in resultTransThoracaEchoBayi" :key="item.index"
              >
                <h1 style="font-weight: bold">{{ item.label }}:</h1>
                <VField class="ml-2" addons>
                  <VControl>
                    <VInput
                      v-model="input[item.model]"
                      type="text"
                      :placeholder="item.label"
                    />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>{{ item.addons }} </VButton>
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-12 is-flex">
              <div class="column is-14">
                <h1 style="font-weight: bold">Finding:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea
                      v-model="input.finding"
                      type="text"
                      placeholder="Finding"
                    />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-12 is-flex">
              <div class="column is-14">
                <h1 style="font-weight: bold">Conclusion:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea
                      v-model="input.conclusion"
                      type="text"
                      placeholder="Conclusion"
                    />
                  </VControl>
                </VField>
              </div>
            </div>
          </Fieldset>
        </div>

        <div class="column is-12" v-if="input.studyType === 'TransThoracaEchoDewasa'">
          <Fieldset :toggleable="true" legend="Trans Thoraca Echo Dewasa">
            <div class="is-12">
              <div
                class="column is-multiline p-3"
                v-for="(item, index) in resultTransThoracaEchoDewasa" :key="item.index"
              >
                <h1 style="font-weight: bold">{{ item.label }}:</h1>
                <VField class="ml-2" addons>
                  <VControl>
                    <VInput
                      v-model="input[item.model]"
                      type="text"
                      :placeholder="item.label"
                      :true-value="item.model"
                    />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>{{ item.addons }} </VButton>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Finding">
                <div class="is-12 is-flex" v-for="(item, index) in findingTransThoracaEchoDewasa" :key="item.index">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">{{ item.label }}:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea
                          v-model="input[item.model]"
                          type="text"
                          :placeholder="item.label"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="is-12 is-flex">
              <div class="column is-14">
                <h1 style="font-weight: bold">Conclusion:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea
                      v-model="input.conclusion"
                      type="text"
                      placeholder="Conclusion"
                    />
                  </VControl>
                </VField>
              </div>
            </div>
          </Fieldset>
        </div>

        <div class="column is-12" v-if="input.studyType === 'LowerExtermityDuplexUltrasoundUSGDoppler'">
          <Fieldset :toggleable="true" legend="Lower Extermity Duplex Ultrasound USG Doppler">
            <div class="is-12 is-flex">
              <div class="column is-14">
                <h1 style="font-weight: bold">Finding:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea
                      v-model="input.finding"
                      type="text"
                      placeholder="Finding"
                    />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-12 is-flex">
              <div class="column is-14">
                <h1 style="font-weight: bold">Conclusion:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea
                      v-model="input.conclusion"
                      type="text"
                      placeholder="Conclusion"
                    />
                  </VControl>
                </VField>
              </div>
            </div>
          </Fieldset>
        </div>

        <div class="column is-12" v-if="input.studyType === 'CarotidDuplexUltrasound'">
          <Fieldset :toggleable="true" legend="Lower Extermity Duplex Ultrasound USG Doppler">
            <div class="is-12 is-flex">
              <div class="column is-14">
                <h1 style="font-weight: bold">Finding:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea
                      v-model="input.finding"
                      type="text"
                      placeholder="Finding"
                    />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-12 is-flex">
              <div class="column is-14">
                <h1 style="font-weight: bold">Conclusion:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea
                      v-model="input.conclusion"
                      type="text"
                      placeholder="Conclusion"
                    />
                  </VControl>
                </VField>
              </div>
            </div>
          </Fieldset>
        </div>
      </VCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import MultiSelect from 'primevue/multiselect'
import * as EMR from '../page-emr-plugins/echo-poli-jantung'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string

let JenisKelamin = ref(EMR.JenisKelamin())
let studyType: any = ref(EMR.studyType())
let resultTransThoracaEchoBayi: any = ref(EMR.resultTransThoracaEchoBayi())
let resultTransThoracaEchoDewasa: any = ref(EMR.resultTransThoracaEchoDewasa())
let findingTransThoracaEchoDewasa: any = ref(EMR.findingTransThoracaEchoDewasa())

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
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const dataTTD: any = ref([])
const d_Perawat: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('FormulirEcho')

const COLLECTION_TransThoracaEchoBayi: any = ref('TransThoracaEchoBayi')
const COLLECTION_TransThoracaEchoDewasa: any = ref('TransThoracaEchoDewasa')
const COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler: any = ref('LowerExtermityDuplexUltrasoundUSGDoppler')
const COLLECTION_CarotidDuplexUltrasound: any = ref('CarotidDuplexUltrasound')


const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date(),
  tglEksaminasi: new Date(),
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const loadRiwayat = () => {
  let selectedCollection = COLLECTION.value;

  if (input.value.studyType === 'TransThoracaEchoBayi') {
    selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
  } else if (input.value.studyType === 'TransThoracaEchoDewasa') {
    selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
  } else if (input.value.studyType === 'LowerExtermityDuplexUltrasoundUSGDoppler') {
    selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
  } else if (input.value.studyType === 'CarotidDuplexUltrasound') {
    selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
  }

  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${selectedCollection}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      if (response.length > 0) {
        isDisabled.value = false;
        input.value = response[0]; // Set ke inputan
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
        dataTTD.value = response[0];
      } else {
        isDisabled.value = true;
        resetInput(); // Reset input jika data kosong
      }
      setAutoFill();
    })
    .catch(() => {
      isDisabled.value = true;
      resetInput(); // Reset input jika terjadi error
      setAutoFill();
    });
};

const print = async () => {
  let selectedCollection = COLLECTION.value;

  if (input.value.studyType === 'TransThoracaEchoBayi') {
    selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
    H.printBlade(
    `emr/cetak-formulir-echo-poli-jantung-TransThoracaEchoBayi?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${selectedCollection}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  );
  } else if (input.value.studyType === 'TransThoracaEchoDewasa') {
    selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
    H.printBlade(
    `emr/cetak-formulir-echo-poli-jantung-TransThoracaEchoDewasa?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${selectedCollection}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
  } else if (input.value.studyType === 'LowerExtermityDuplexUltrasoundUSGDoppler') {
    selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
    H.printBlade(
      `emr/cetak-formulir-echo-poli-jantung-LowerExtermityDuplexUltrasoundUSGDoppler?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${selectedCollection}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
  } else if (input.value.studyType === 'CarotidDuplexUltrasound') {
    selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
    H.printBlade(
      `emr/cetak-formulir-echo-poli-jantung-CarotidDuplexUltrasound?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${selectedCollection}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
  }

};

const getDataExist = () => {
  let selectedCollection = COLLECTION.value;

  if (input.value.studyType === 'TransThoracaEchoBayi') {
    selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
  } else if (input.value.studyType === 'TransThoracaEchoDewasa') {
    selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
  } else if (input.value.studyType === 'LowerExtermityDuplexUltrasoundUSGDoppler') {
    selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
  } else if (input.value.studyType === 'CarotidDuplexUltrasound') {
    selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
  }

  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${selectedCollection}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      if (response.length > 0) {
        isDisabled.value = false;
        input.value = response[0]; // Set ke inputan
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
        dataTTD.value = response[0];
      } else {
        isDisabled.value = true;
        resetInput(); // Reset input jika data kosong
      }
      setAutoFill();
    })
    .catch(() => {
      isDisabled.value = true;
      resetInput(); // Reset input jika terjadi error
      setAutoFill();
    });
};


const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)

  let selectedCollection = COLLECTION.value;
  if (input.value.studyType === 'TransThoracaEchoBayi') {
    selectedCollection = COLLECTION_TransThoracaEchoBayi.value;

    let json = {
      id: ID,
      norec_emr: NOREC_EMRPASIEN.value,
      collection: COLLECTION_TransThoracaEchoBayi.value,
      url_form: props.FORM_URL,
      name_form: props.FORM_NAME,
      jenis_emr: 'asesmen_medis',
      data: object,
    }

    isLoading.value = true
    useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
  } else if (input.value.studyType === 'TransThoracaEchoDewasa') {
    selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;

    let json = {
      id: ID,
      norec_emr: NOREC_EMRPASIEN.value,
      collection: COLLECTION_TransThoracaEchoDewasa.value,
      url_form: props.FORM_URL,
      name_form: props.FORM_NAME,
      jenis_emr: 'asesmen_medis',
      data: object,
    }

    isLoading.value = true
    useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
  } else if (input.value.studyType === 'LowerExtermityDuplexUltrasoundUSGDoppler') {
    selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;

    let json = {
      id: ID,
      norec_emr: NOREC_EMRPASIEN.value,
      collection: COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value,
      url_form: props.FORM_URL,
      name_form: props.FORM_NAME,
      jenis_emr: 'asesmen_medis',
      data: object,
    }

    isLoading.value = true
    useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
  } else if (input.value.studyType === 'CarotidDuplexUltrasound') {
    selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;

    let json = {
      id: ID,
      norec_emr: NOREC_EMRPASIEN.value,
      collection: COLLECTION_CarotidDuplexUltrasound.value,
      url_form: props.FORM_URL,
      name_form: props.FORM_NAME,
      jenis_emr: 'asesmen_medis',
      data: object,
    }

    isLoading.value = true
    useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
  }

  console.log(selectedCollection)
}


const kembaliKeun = () => {
  window.history.back()
}

const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.tglPembuatan = new Date()
}


watch(
  () => input.value.studyType,
  (newVal) => {
    resetInput();
    loadRiwayat();
  }
)

const resetInput = () => {
  input.value = {
    tanggal: new Date(),
    tglEksaminasi: new Date(),
    studyType: input.value.studyType,
    namaPasien: props.pasien.namapasien,
    jeniskelamin: props.pasien.jeniskelamin,
    norm: props.pasien.nocm,
    tanggalLahirPasien: props.pasien.tgllahir,
    tanggalKunjunganPasien: props.registrasi.tglregistrasi,
  }
}

setView()
fetchDokter({ query: '' })
setAutoFill()
</script>
