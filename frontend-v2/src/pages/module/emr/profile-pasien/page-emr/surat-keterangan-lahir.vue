<style lang="scss">
.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  text-align: center !important;
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: bold;
  overflow: hidden;
  background-color: aquamarine;
  vertical-align: middle;
  padding: 10px 5px;
  word-break: normal;
}
</style>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import Checkbox from 'primevue/checkbox';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({
  title: 'Surat Keterangan Lahir - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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
const route = useRoute()
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const d_Diagnosa: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date,
    jam: new Date
  },
  airway: [],
  disability: []

})
const COLLECTION: any = ref('SuratKeteranganLahir')
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  TBTanggalLahirPasien: null,
  TBSUmurPasien: "",
});
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
function calculateAge(birthdate) {
  const today = new Date();
  const birthDate = new Date(birthdate);
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  return age;
}
const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  } else {
    let dataRegis = H.setObjectRegistrasi(pasien.value.registrasi)
    let dataPasien = H.setObjectPasien(pasien.value)
    input.value.DDDokter = dataRegis.dokter
    input.value.TBNamaPasien = dataPasien.namapasien
    input.value.norm = dataPasien.nocm
    input.value.TBTanggalLahirPasien = dataPasien.tgllahir
    input.value.TBSUmurPasien = calculateAge(dataPasien.tgllahir)
    input.value.TBJenisKelaminPasien = dataPasien.jeniskelamin
    input.value.TAAlamatPasien = dataPasien.alamatlengkap
  }
}
watch(
  () => input.value.TBTanggalLahirPasien,
  (newVal) => {
    if (newVal) {
      input.value.TBSUmurPasien = calculateAge(newVal);
    }
  }
);
const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': route.name,
    'name_form': 'Surat Keterangan Lahir',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  console.log(json)

  isLoading.value = true
  useApi().post(`/emr/simpan-emr-surket`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const kembaliKeun = () => {
  window.history.back()
}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
  console.log(norec_emr)
}
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}
// const fetchDiagnosa = async (filter: any) => {
//     await useApi().get(
//         `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`
//     ).then((response) => {
//         d_Diagnosa.value = response
//     })
// }
const getDataExist = async () => {
  await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
    if (response != null || response != undefined) {
      input.value.TBSBeratBadan = response.beratBadan
      input.value.TBSTinggiBadan = response.tinggiBadan
      // input.value.IMT = response.IMT
      // input.value.lingkarPerut = response.lingkarPerut
      input.value.TBSNadi = response.nadi
      // input.value.celciusObgyn = response.suhu
      input.value.TBSTekananDarah = response.tekananDarah
      input.value.TBSPernafasan = response.pernapasan
      // input.value.sao2Obgyn = response.SPO2
    }
  })
}
const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
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


getDataExist()
fetchPasien()
</script>

<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Surat Keterangan Lahir</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

        <div class="columns is-multiline column is-12">
          <div class="column is-12">
            <div class="column is-12 is-flex">
              <div class="column is-2 p-0">
                <h1 class="mb-3" style="font-weight: bold;">No RM Pasien:</h1>
              </div>
              <div class="column is-10 p-0">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-12 is-flex">
              <div class="column is-2 p-0">
                <h1 class="mb-3" style="font-weight: bold;">Nama Pasien:</h1>
              </div>
              <div class="column is-10 p-0">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-2 p-0">
                <h1 class="mb-3" style="font-weight: bold;">Jenis Kelamin:</h1>
              </div>
              <div class="column is-10 p-0">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBJenisKelaminPasien" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-2 p-0">
                <h1 class="mb-3" style="font-weight: bold;">Tanggal Lahir Pasien:</h1>
              </div>
              <div class="column is-10 p-0">
                <VField>
                  <VDatePicker v-model="input.TBTanggalLahirPasien" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()" :disabled="item.disabledForm">
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

            <div class="column is-12 is-flex">
              <div class="column is-2 p-0">
                <h1 class="mb-3" style="font-weight: bold;">Umur Pasien:</h1>
              </div>
              <div class="column is-10 p-0">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBSUmurPasien" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-multiline columns is-12 ml-1">
              <div class="is-6 mr-3">
                <h1 class="mb-3" style="font-weight: bold;">Berat Badan</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBBeratBadan" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>kg</VButton>
                  </VControl>
                </VField>
              </div>
              <div class=" is-6">
                <h1 class="mb-3" style="font-weight: bold;">Panjang</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBPanjang" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>cm</VButton>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-2 p-0">
                <h1 class="mb-3" style="font-weight: bold;">Dokter Penolong:</h1>
              </div>
              <div class="column is-10 p-0">
                <VField>
                  <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" />
                </VField>
              </div>
            </div>

            <div class="column is-multiline columns is-12 ml-1">
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VCheckbox class="p-0" color="primary" square true-value="Lahir Prematur" label="Lahir Prematur"
                      v-model="input.TBLahirPrematur" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VCheckbox class="p-0" color="primary" square true-value="Hidup" label="Hidup"
                      v-model="input.TBHidup" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VCheckbox class="p-0" color="primary" square true-value="Normal" label="Normal"
                      v-model="input.TBnormal" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VCheckbox class="p-0" color="primary" square true-value="SC" label="SC" v-model="input.TBsc" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
