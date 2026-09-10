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
            <div class="column is-3">
              <VField>
                <VDatePicker v-model="input.tanggalLahir" mode="date" trim-weeks :max-date="new Date()">
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
            <div class="column is-2" style="margin-top: 10px;">
            <span class="label-ppap">Jenis Kelamin</span>
            </div>
            <div class="column is-4">
            <div class="columns is-multiline pt-4">
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKelamin" true-value="Laki-laki" class="p-0" label="Laki-laki"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKelamin" true-value="Perempuan" class="p-0" label="Perempuan"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
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
          <div class="column">
            <h1 style="font-weight:bold">Dengan ini saya meminta pelayanan atau bimbingan rohani dari agama :</h1>
            <div class="column is-12 is-flex ml-5">
              <div class="column is-2" style="margin-top: 10px;">
                <span class="label-pso">Terhadap :</span>
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
      </div>

      <div class="column">
        <div class="column is-12 is-flex ml-5">
          <div class="column is-2" style="margin-top: 10px;">
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
          <div class="column is-2 pb-0">
            <span class="label-pso">Umur</span>
          </div>
          <div class="column is-6">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.umurPasien" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
              <div class="column is-2">
                <span class="label-pso">Alamat:</span>
              </div>
              <div class="column is-7">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.alamatPasien" class="input" type="text" placeholder="Alamat" />
                  </VControl>
                </VField>
              </div>
          </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-2 pb-0">
            <span class="label-pso">Agama</span>
          </div>
          <div class="column is-3 pb-0">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.AgamaPasien" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <h1 style="font-weight:bold">Demikian dan terima kasih sebelumnya :</h1>
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
          <span class="label-pso">Tim Rohaniawan</span><br>
          <TandaTangan :elemenID="'TTDRohaniawan'" :width="'150'" :height="'150'" class="dek" />
          <div class="column p-0 mt-5" style="text-align: left;">
            <VControl>
              <AutoComplete v-model="input.Rohaniawan" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" :field="'label'" />
            </VControl>
          </div>
        </div>
        <div class="column is-3" style="text-align:center">
          <span class="label-pso">Saksi Perawat/Bidan</span><br>
          <TandaTangan :elemenID="'TTDSaksiPerawat'" :width="'150'" :height="'150'" class="dek" />
          <div class="column p-0 mt-5" style="text-align: left;">
            <VField class="pt-2">
              <VControl>
                <AutoComplete v-model="input.SaksiPerawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-3" style="text-align:center">
          <span class="label-pso">Pasien/Keluarga</span><br>
          <TandaTangan :elemenID="'TTDPasienKeluarga'" :width="'150'" :height="'150'" class="dek" />
          <div class="column p-0 mt-5" style="text-align: left;">
            <VField class="pt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.PasienKeluarga" />
              </VControl>
            </VField>
          </div>
        </div>
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
        H.tandaTangan().set('TTDPasienKeluarga', dataTTD.value.TTDPasienKeluarga)
        H.tandaTangan().set('TTDSaksiPerawat', dataTTD.value.TTDSaksiPerawat)
        H.tandaTangan().set('TTDRohaniawan', dataTTD.value.TTDRohaniawan)
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object['TTDPasienKeluarga'] = H.tandaTangan().get('TTDPasienKeluarga')
  object['TTDSaksiPerawat'] = H.tandaTangan().get('TTDSaksiPerawat')
  object['TTDRohaniawan'] = H.tandaTangan().get('TTDRohaniawan')
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
  input.value.umurPasien = props.pasien.umur
  input.value.AgamaPasien = props.pasien.agama
  input.value.alamatPasien = props.pasien.alamatlengkap
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
