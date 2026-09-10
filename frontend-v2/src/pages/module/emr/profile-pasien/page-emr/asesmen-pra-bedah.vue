<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>

  <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
  <div v-else>
    <div class="column">
      <VCard>
        <div class="column is-3 pt-0 pb-0">
          <span class="label-apb">Tanggal Asesmen</span>
          <VDatePicker class="pt-3 pb-0 pl-0" v-model="input.tglAsesmen" color="green" trim-weeks mode="dateTime"
            :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }" class="pb-0">
              <VField>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                    class="is-rounded_Z" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
      </VCard>
    </div>

    <div class="column">
      <VCard>
        <div class="column pt-0 pb-0" v-for="(datas) in riwayat">
          <span class="label-apb">{{ datas.title }}</span>
          <div class="columns is-multiline p-3" v-if="datas.detail">
            <div class="column" v-for="(data) in datas.detail" :class="data.type == 'textBox' ? 'is-4' : 'is-3'">
              <VField v-if="data.type == 'checkBox'">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.label" class="p-0" :label="data.label"
                    color="primary" square />
                </VControl>
              </VField>
              <VField v-else>
                <VControl>
                  <VInput type="text" v-model="input[data.model]" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-8" v-else>
            <VField>
              <VControl>
                <VInput type="text" v-model="input[datas.model]" />
              </VControl>
            </VField>
          </div>
        </div>
      </VCard>
    </div>

    <div class="column">
      <Fieldset :toggleable="true" legend="1. STATUS FISIK">
        <div class="column">
          <span class="label-apb">Pemeriksaan Fisik</span>
        </div>
        <div class="column pb-0 pt-0">
          <div class="columns is-multiline p-3">
            <div class="column" v-for="(data) in pemeriksaanFisik">
              <span class="label-apb">{{ data.label }}</span>
              <VField addons v-if="data.satuan" class="pt-3">
                <VControl expanded>
                  <VInput type="text" class="input" v-model="input[data.model]" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>{{ data.satuan }}</VButton>
                </VControl>
              </VField>
              <VField v-else class="pt-3">
                <VControl>
                  <VInput type="text" v-model="input[data.model]" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column">
          <span class="label-apb">Pemeriksaan Tanda Vital</span>
          <div class="columns is-multiline pt-3 pl-3 pr-3">
            <div class="column" v-for="(data) in pemeriksaanVital" :class="data.model == 'ketPemVital' ? 'is-5' : 'is-2'">
              <VField v-if="data.label">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" color="primary"
                    square class="p-0" />
                </VControl>
              </VField>
              <VField v-else>
                <VControl>
                  <VInput type="text" v-model="input[data.model]" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

      </Fieldset>
    </div>

    <div class="column">
      <Fieldset :toggleable="true" legend="Analisis">
        <div class="column">
          <span class="label-apb">Diagnosis Pra Operasi</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.diagPraOperasi" />
            </VControl>
          </VField>
        </div>
      </Fieldset>
    </div>

    <div class="column">
      <Fieldset :toggleable="true" legend="Rencana Tindakan">
        <div class="column pb-0" v-for="(data) in rencanaTindakan">
          <span class="label-apb">{{ data.label }}</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input[data.model]" />
            </VControl>
          </VField>
        </div>
      </Fieldset>
    </div>

    <div class="column is-4" style="margin-left: auto;">
      <VCard>
        <div class="column pb-0">
          <span class="label-apb">Tanggal dan Jam</span>
          <VField class="column is-8 p-0 mt-3">
            <VDatePicker v-model="input.tglDibuat" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
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
        <div class="column">
          <span class="label-apb">Nama Pasien</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.namaPasien" />
            </VControl>
          </VField>
        </div>
        <div class="column pb-0">
          <span class="label-apb">Dokter</span>
          <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
              <AutoComplete v-model="input.dokterSpesialis" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="ketik untuk mencari..." />
            </VControl>
          </VField>
        </div>
      </VCard>
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
import * as EMR from '../page-emr-plugins/asesmen-pra-bedah'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let riwayat = ref(EMR.riwayat())
let pemeriksaanFisik = ref(EMR.pemeriksaanFisik())
let pemeriksaanVital = ref(EMR.pemeriksaanVital())
let rencanaTindakan = ref(EMR.rencanaTindakan())

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
const loadData: any = ref(true)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
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
  tglDibuat: new Date()
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }
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
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
}
setView()
setAutoFill()

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
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
</script>

<style lang="scss">
.label-apb {
  font-weight: 500;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
