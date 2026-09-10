<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
      <VCard v-else>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField>
                <h1 style="font-weight: bold">Masuk RS Tanggal</h1>
              </VField>
              <VField>
                <VDatePicker v-model="input.tglregis" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" disabled />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <h1 style="font-weight: bold">Pengkajian Tanggal</h1>
              </VField>
              <VField>
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
        </div>
        <div class="column is-12" v-for="(datas, i) in anamesa">
          <h1 style="font-weight: bold; margin-bottom: 1rem">{{ datas.title }}</h1>
          <div class="columns is-multiline">
            <div class="column is-4" v-for="(data, b) in datas.detail">
              <VField v-if="data.type == 'checkBox'">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle" :label="data.subTitle"
                    class="p-0" color="primary" square />
                </VControl>
              </VField>
              <VField :label="data.subTitle" v-else-if="data.type == 'textBox'">
                <VControl raw subcontrol>
                  <input v-model="input[data.model + b]" class="input p-0" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-8">
          <h1 style="font-weight: bold; margin-bottom: 0.5rem">Alasan Masuk</h1>
          <VField>
            <VControl>
              <VTextarea v-model="input.alasanMasuk" rows="2"> </VTextarea>
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <Fieldset :toggleable="true" legend="1. STATUS FISIK">
            <div class="column is-12">
              <h1 style="font-weight: bold; margin-bottom: 1rem">A. Tanda Vital</h1>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField label="Suhu"></VField>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="Suhu" v-model="input.suhu" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>°C </VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField label="Nadi"></VField>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>BPM</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-8" v-for="(datas, i) in StatusNadi">
                  <h1 style="font-weight: bold; margin-bottom: 1rem">
                    {{ datas.title }}
                  </h1>
                  <div class="columns is-multiline">
                    <div class="column is-2" v-for="(data, i) in datas.detail" style="margin-top: 1rem">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input[data.model + i]" :true-value="data.subTitle" :label="data.subTitle"
                            class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-3">
                  <VField label="Nadi"></VField>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mm/Hg</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="Nadi"></VField>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mm/Hg</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold; margin-bottom: 0.5rem">B. Kesadaran</h1>
              <h1 style="font-weight: bold; margin-bottom: 0.5rem">GCS</h1>
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem">E</h1>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.E" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem">V</h1>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.V" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem">M</h1>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.M" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem">Skor</h1>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.skor" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12" v-for="(datas, i) in option">
              <h1 style="font-weight: bold; margin-bottom: 1rem">{{ datas.title }}</h1>
              <div class="columns is-multiline">
                <div class="column is-3" v-for="(data, b) in datas.detail">
                  <VField v-if="data.type == 'checkBox'">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle" :label="data.subTitle"
                        class="p-0" color="primary" square />
                    </VControl>
                  </VField>
                  <VField :label="data.subTitle" v-else-if="data.type == 'textBox'">
                    <VControl raw subcontrol>
                      <input v-model="input[data.model + b]" class="input p-0" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12">
          <Fieldset :toggleable="true" legend="2. STATUS BIO - PSIKO - SOSIO SPIRITUAL - KULTURAL">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem">Pola Makan</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="" v-model="input.polaMakan_" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/hari</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 style="font-weight: bold">Terakhir Jam</h1>
                  <VDatePicker class="column is-8" v-model="input.jamMakan" color="green" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem">Pola Minum</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="" v-model="input.polaMinum_" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>cc/hari</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 style="font-weight: bold">Terakhir Jam</h1>
                  <VDatePicker class="column is-8" v-model="input.jamMinum" color="green" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem">BAK</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="" v-model="input.BAK" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/hari</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 style="font-weight: bold">Terakhir Jam</h1>
                  <VDatePicker class="column is-8" v-model="input.jamBAK" color="green" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-3">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem; margin-left: -3rem">
                    Warna
                  </h1>
                  <VField style="margin-left: -3rem">
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.warna" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem">BAB</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="" v-model="input.BAK" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/hari</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 style="font-weight: bold">Terakhir Jam</h1>
                  <VDatePicker class="column is-8" v-model="input.jamBAB" color="green" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-12" v-for="(datas, i) in statusMental">
                  <h1 style="font-weight: bold; margin-bottom: 1rem">
                    {{ datas.title }}
                  </h1>
                  <div class="columns is-multiline">
                    <div class="column is-4" v-for="(data, b) in datas.detail">
                      <VField v-if="data.type == 'checkBox'">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle" :label="data.subTitle"
                            class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                      <VField :label="data.subTitle" v-else-if="data.type == 'textBox'">
                        <VControl raw subcontrol>
                          <input v-model="input[data.model + b]" class="input p-0" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>

        <div class="column is-12">
          <Fieldset :toggleable="true" legend="3. STATUS EKONOMI">
            <div class="column is-12" v-for="(datas, i) in statusEkonomi">
              <h1 style="font-weight: bold; margin-bottom: 1rem">{{ datas.title }}</h1>
              <div class="columns is-multiline">
                <div class="column is-2" v-for="(data, b) in datas.detail">
                  <VField v-if="data.type == 'checkBox'">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle" :label="data.subTitle"
                        class="p-0" color="primary" square />
                    </VControl>
                  </VField>
                  <VField :label="data.subTitle" v-else-if="data.type == 'textBox'">
                    <VControl raw subcontrol>
                      <input v-model="input[data.model + b]" class="input p-0" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12">
          <Fieldset :toggleable="true" legend="4. RIWAYAT KESEHATAN">
            <div class="column is-12" v-for="(datas, i) in riwayat">
              <h1 style="font-weight: bold; margin-bottom: 1rem">{{ datas.title }}</h1>
              <div class="columns is-multiline">
                <div class="column is-12" v-for="(data, b) in datas.detail">
                  <VField v-if="data.type == 'checkBox'">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle" :label="data.subTitle"
                        class="p-0" color="primary" square />
                    </VControl>
                  </VField>
                  <VField :label="data.subTitle" v-else-if="data.type == 'textBox'">
                    <VControl raw subcontrol>
                      <input v-model="input[data.model + b]" class="input p-0" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12">
          <Fieldset :toggleable="true" legend="4. RIWAYAT ALERGI">

          </Fieldset>
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
import AutoComplete from 'primevue/autocomplete'
import Fieldset from 'primevue/fieldset'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/asesmen-awal-eswl'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let anamesa = ref(EMR.anamesa())
let StatusNadi = ref(EMR.StatusNadi())
let option = ref(EMR.option())
let statusMental = ref(EMR.statusMental())
let statusEkonomi = ref(EMR.statusEkonomi())
let riwayat = ref(EMR.riwayat())

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
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
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
  selectedMenu: [false],
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date(),
  tglregis: new Date(),
  jamMakan: new Date(),
  jamMinum: new Date(),
  jamBAK: new Date(),
  jamBAB: new Date(),
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
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
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
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

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

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tglregis = props.registrasi.tglregistrasi
}
setView()
setAutoFill()

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
