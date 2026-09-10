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

  <div class="column is-12">
    <VCard>
      <div class="column">
        <h1 style="font-weight:bold">Yang bertanda tangan dibawah ini</h1>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-3" style="margin-top: 10px;">
            <span class="label-pso">Nama :</span>
          </div>
          <div class="column is-6">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.namaPenangungJwb" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
            <div class="column is-3">
              <span class="label-pso">Tanggal Lahir:</span>
            </div>
            <div class="column is-5">
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
          </div>
          <div class="column is-12 is-flex ml-5">
              <div class="column is-3">
                <span class="label-pso">Alamat:</span>
              </div>
              <div class="column is-7">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.alamat" class="input" type="text" placeholder="Alamat" />
                  </VControl>
                </VField>
              </div>
          </div>
          <div class="column is-12 is-flex ml-5">
            <div class="column is-3" style="margin-top: 10px;">
              <span class="label-pso">Hubungan dengan pasien :</span>
            </div>
              <VField class="column is-4">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.wali" :suggestions="d_Wali" @complete="fetchWali($event)" :optionLabel="'label'"
                    :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                    placeholder="Cari ..." class="mt-2" />
                </VControl>
              </VField>
          </div>
      </div>

      <div class="column">
        <h1 style="font-weight:bold">Dengan ini menyatakan permintaan untuk mendapat second opinion atas :</h1>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-3" style="margin-top: 10px;">
            <span class="label-pso">Nama</span>
          </div>
          <div class="column is-6">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.namaPasien" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-3 pb-0">
            <span class="label-pso">Tanggal Lahir</span>
          </div>
          <div class="column is-3 pb-0">
              <VDatePicker v-model="input.tglLahirPasien" color="green" trim-weeks mode="date"
                :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }" class="pb-0">
                  <VField>
                    <VControl icon="feather:calendar">
                      <VInput type="text"  :value="inputValue" v-on="inputEvents"
                        class="is-rounded_Z" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
        </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-3 pb-0">
            <span class="label-pso">No.RM</span>
          </div>
          <div class="column is-3 pb-0">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.norm" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-3" style="margin-top: 10px;">
            <span class="label-pso">Diagnosa</span>
          </div>
          <div class="column is-6">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.Diagnosa" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <h1 style="font-weight:bold">Dan menyatakan bahwa :</h1>
        <div class="column p-3">
          <p style="text-align:justify">1. Saya memahami perlunya dan manfaat second opinion tersebut sebagaimana telah dijelaskan
            kepada saya</p>
          <p style="text-align:justify" class="pt-4">2. Saya telah mendapatkan kesempatan untuk bertanya dan telah mendapat jawaban yang
            memuaskan</p>
          <p style="text-align:justify" class="pt-4">3. Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti dan selalu
            berkembang, maka perbedaan pendapat ahli adalah biasa terjadi dalam dunia kedokteran</p>
          <p style="text-align:justify" class="pt-4">4. Saya menyadari beban biaya second opinion menjadi tanggung jawab saya.</p>
        </div>
      </div>

      <div class="column is-12 is-flex ml-5">
        <div class="column is-4"></div>
        <div class="column is-4"></div>
        <div class="column is-4">
          <span class="label-pso">Garut</span>
          <VDatePicker v-model="input.tglPermintaan" color="green" class="pt-3" trim-weeks mode="datetime"
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
      </div>

      <div class="columns is-multiline pt-5" style="justify-content: space-around;">
        <div class="column is-3" style="text-align:center">
          <span class="label-pso">Saksi</span><br>
          <TandaTangan :elemenID="'TTDSaksi'" :width="'150'" :height="'150'" class="dek" />
          <div class="column p-0 mt-5" style="text-align: left;">
            <VField class="pt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.saksi" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-3" style="text-align:center">
          <span class="label-pso">Pasien / Keluarga</span><br>
          <TandaTangan :elemenID="'TTDpasienKeluarga'" :width="'150'" :height="'150'" class="dek" />
          <div class="column p-0 mt-5" style="text-align: left;">
            <VField class="pt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.PasienKeluarga" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-3" style="text-align:center">
          <span class="label-pso">Dokter yang merawat</span><br>
          <TandaTangan :elemenID="'TTDdokter'" :width="'150'" :height="'150'" class="dek" />
          <div class="column p-0 mt-5" style="text-align: left;">
            <VControl>
              <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" :field="'label'" />
            </VControl>
          </div>
        </div>
      </div>  

      <div class="column">
        <h1 style="font-weight:bold">* Coret yang tidak perlu</h1>
      </div>
      <div class="column">
        <h1 style="font-weight:bold">Bila pasien tidak kompeten atau tidak mau menerima informasi, maka wali atau seseorang yang diberi hak
          untuk menyetujui tindakan terhadap pasien tersebut</h1>
      </div>

    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
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
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Wali: any = ref([])
const dataTTD: any = ref([])
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
  tglPermintaan: new Date()
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
        dataTTD.value = response[0]
        H.tandaTangan().set('TTDdokter', dataTTD.value.TTDdokter)
        H.tandaTangan().set('TTDpasienKeluarga', dataTTD.value.TTDpasienKeluarga)
        H.tandaTangan().set('TTDSaksi', dataTTD.value.TTDSaksi)
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object['TTDdokter'] = H.tandaTangan().get('TTDdokter')
  object['TTDpasienKeluarga'] = H.tandaTangan().get('TTDpasienKeluarga')
  object['TTDSaksi'] = H.tandaTangan().get('TTDSaksi')
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

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jnsKlmPasien = props.pasien.jeniskelamin
  input.value.tglLahirPasien = props.pasien.tgllahir
  input.value.norm = props.pasien.nocm
}

const fetchWali = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Wali.value = response
  })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
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


setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.label-pso {
  font-weight: 500;
}
</style>
