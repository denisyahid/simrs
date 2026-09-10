<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="column is-12">
    <VCard>
      <div class="column mt-5" style="overflow: auto;">
        <div class="columns is-multiline">
          <div class="column is-6">
            <div class="columns is-multiline">
              <div class="column is-4">
                <span class="label-ro">Jenis Operasi</span>
              </div>
              <div class="column is-8">
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.jenisoperasi" style="margin-top: -15px;" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-6">
            <div class="columns is-multiline">
              <div class="column is-4">
                <span class="label-ro">Tgl Tindakan</span>
              </div>
              <div class="column is-8">
                <VDatePicker v-model="input.tglTindakan" class="pt-3" mode="dateTime"
                  style="width: 100%; margin-top: -15px;" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal Tindakan" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
            </div>
          </div>
          <div class="column is-6">
            <div class="columns is-multiline">
              <div class="column is-4">
                <span class="label-ro">Teknik Anesthesia</span>
              </div>
              <div class="column is-8">
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.teknikanestesi" style="margin-top: -15px;" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>

        <!-- checklist -->

        <div class="column is-12 forCB" style="margin-left: -10px;">
          <h1 style="font-weight: bold">Listrik</h1>

          <div v-for="diagnosis in listrikOptions" :key="diagnosis.value" class="checkbox-container">
            <VCheckbox class="fontcheckbox" v-model="input[diagnosis.value]" :true-value="diagnosis.text"
              :label="diagnosis.text" color="primary" />
          </div>
        </div>

        <div class="column is-12 forCB" style="margin-left: -10px;">
          <h1 style="font-weight: bold">Gas Medis</h1>

          <div v-for="diagnosis in gasmedisOptions" :key="diagnosis.value" class="checkbox-container">
            <VCheckbox class="fontcheckbox" v-model="input[diagnosis.value]" :true-value="diagnosis.text"
              :label="diagnosis.text" color="primary" />
          </div>
        </div>

        <div class="column is-12 forCB" style="margin-left: -10px;">
          <h1 style="font-weight: bold">Mesin Anestesia</h1>

          <div v-for="diagnosis in mesinanastesiaOptions" :key="diagnosis.value" class="checkbox-container">
            <VCheckbox class="fontcheckbox" v-model="input[diagnosis.value]" :true-value="diagnosis.text"
              :label="diagnosis.text" color="primary" />
          </div>
        </div>

        <div class="column is-12 forCB" style="margin-left: -10px;">
          <h1 style="font-weight: bold">Manajemen Jalan Nafas</h1>

          <div v-for="diagnosis in manajemenjalannifasOptions" :key="diagnosis.value" class="checkbox-container">
            <VCheckbox class="fontcheckbox" v-model="input[diagnosis.value]" :true-value="diagnosis.text"
              :label="diagnosis.text" color="primary" />
          </div>
        </div>

        <div class="column is-12 forCB" style="margin-left: -10px;">
          <h1 style="font-weight: bold">Pemantauan</h1>

          <div v-for="diagnosis in pemantauanOptions" :key="diagnosis.value" class="checkbox-container">
            <VCheckbox class="fontcheckbox" v-model="input[diagnosis.value]" :true-value="diagnosis.text"
              :label="diagnosis.text" color="primary" />
          </div>
        </div>

        <div class="column is-12 forCB" style="margin-left: -10px;">
          <h1 style="font-weight: bold">Lain-lain</h1>

          <div v-for="diagnosis in lainlainOptions" :key="diagnosis.value" class="checkbox-container">
            <VCheckbox class="fontcheckbox" v-model="input[diagnosis.value]" :true-value="diagnosis.text"
              :label="diagnosis.text" color="primary" />
          </div>
        </div>

        <div class="column is-12 forCB" style="margin-left: -10px;">
          <h1 style="font-weight: bold">Obat-obat</h1>

          <div v-for="diagnosis in obatobatOptions" :key="diagnosis.value" class="checkbox-container">
            <VCheckbox class="fontcheckbox" v-model="input[diagnosis.value]" :true-value="diagnosis.text"
              :label="diagnosis.text" color="primary" />
          </div>

          <textarea v-model="input.textLainnya1" class="textarea" placeholder="Tuliskan obat lainnya..."></textarea>
        </div>


      </div>
      <div class="columns">
        <div class="column is-6">
          <div class="column" style="text-align:center;">
            <h1><b>Pemeriksa <br> (Dokter Spesialis Anastesi / Perawat Anastesi) </b></h1>
            <TandaTangan :elemenID="'TTDPetugas1'" :width="'150'" :height="'150'" class="dek" />
            <VControl class="prime-auto">
              <AutoComplete v-model="input.CBPetugas1" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" class="mt-2" />
            </VControl>
          </div>
        </div>
        <div class="column is-6">
          <div class="column" style="text-align:center;">
            <h1><b>DPJP<br><br></b></h1>
            <TandaTangan :elemenID="'TTDPetugas2'" :width="'150'" :height="'150'" class="dek" />
            <VControl class="prime-auto">
              <AutoComplete v-model="input.CBPetugas2" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" class="mt-2" />
            </VControl>
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
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
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
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const dataTTD: any = ref([])
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
  riwayatAlergi: [{
    no: 1,
  }],
  daftarObat: [{
    no: 1,
  }],

  mesinanastesia: 'Mesin Anastesia terhubung dengan sumber listrik, indikator (+) menyala ',
  layar: 'Layar pemantauan terhubung dengan sumber listrik, indikator (+)',
  syringe: 'Syringe pump terhubung dengan sumber listrik, indikator (+)',
  defibrilator1: 'Defibrilator terhubung dengan sumber listrik, indikator (+)',

  selang1: 'Selang oksigen terhubung anatara sumber gas dan mesin anastesia.',
  flow: 'Flow meter O² di mesin anastesia berfungsi, aliran gas keluar dari mesin dapat dirasakan.',
  compressed: 'Compressed air terhubung antara sumber gas dengan mesin anastesia.',
  defibrilator: `Flow meter 'Air' di mesin anastesia berfungsi, aliran gas keluar mesin dapat dirasakan.`,
  compressed2: 'N₂O terhubung antara sumber gas dengan mesin anastesia.',
  flown2o: 'Flow meter N₂O di mesin anastesia berfungsi, aliran gas keluar dari mesin dapat dirasakan.',

  power: 'Power ON',
  self: 'Self callibration: DONE',
  bocor: 'Tidak ada kebocoran sirkuit nafas',
  zat2: 'Zat volatil terisi',
  power2: 'Absorber CO₂ dalam kondisi baik',

  sungkup: 'Sungkup muka dalam ukuran yang benar.',
  oropharygeal: 'Oropharygeal airway (Guedel) dalam ukuran yang benar.',
  batang: 'Batang laringoskop berisi baterai.',
  bilah: 'Bilah alringoskop dalam ukuran yang benar.',
  gagang: 'Gagang dan bilah laringoskop berfungsi baik',
  ett: 'ETT atau LMA dalam ukuran yang benar, tidak bocor.',
  stilet: 'Stilet (introduser).',
  semprit: 'Semprit untuk mengembangkan cuff.',
  forceps: 'Forceps Magill.',

  kabel: 'Kabel EKG dengan layar pemantau.',
  elektroda: 'Elektroda EKG dalam jumlah dan ukuran sesuai.',
  nibp: 'NIBP terhubung dengan layar pantau, ukuran manset sesuai.',
  zat: 'SpO2 terhubung dengan layar pantau, berfungsi baik.',
  kapnografi: 'Kapnografi terhubung dengan layar pantau, berfungsi baik.',
  pemantau: 'Pemantau suhu terhubung dengan layar pantau.',

  stetoskop: 'Stetoskop tersedia.',
  suction: 'Suction berfungsi baik.',
  selang: 'Selang suction terhubung, kateter suction dalam ukuran yang benar',
  plester: 'Plester untuk fiksasi',
  blanket: 'Blanket roll / hemotherm / radiant heater terhubung sumber listrik, berfungsi baik',
  lidocaine: 'Lidocaine spray / jelly',
  defibrillator: 'Defibrillator jelly',

  epinefrin: 'Epinefrin',
  atropin: 'Atropin',
  sedatif: 'Sedatif (midazolam / propofol / etomidat / ketamin / tiopental)',
  opiat: 'Opiat / opioid',
  pelumpuh: 'Pelumpuh otot',
  antibiotika: 'Antibiotika',
  lainnya1: 'Lain-lain',
});

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const d_Petugas: any = ref([])
const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
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
        H.tandaTangan().set("TTDPetugas1", dataTTD.value.TTDPetugas1)
        H.tandaTangan().set("TTDPetugas2", dataTTD.value.TTDPetugas2)
      }
    })
}

const listrikOptions = [
  { text: "Mesin Anastesia terhubung dengan sumber listrik, indikator (+) menyala ", value: "mesinanastesia" },
  { text: "Layar pemantauan terhubung dengan sumber listrik, indikator (+)", value: "layar" },
  { text: "Syringe pump terhubung dengan sumber listrik, indikator (+)", value: "syringe" },
  { text: "Defibrilator terhubung dengan sumber listrik, indikator (+)", value: "defibrilator1" },
];

const gasmedisOptions = [
  { text: "Selang oksigen terhubung anatara sumber gas dan mesin anastesia.", value: "selang1" },
  { text: "Flow meter O² di mesin anastesia berfungsi, aliran gas keluar dari mesin dapat dirasakan.", value: "flow" },
  { text: "Compressed air terhubung antara sumber gas dengan mesin anastesia.", value: "compressed" },
  { text: "Flow meter 'Air' di mesin anastesia berfungsi, aliran gas keluar mesin dapat dirasakan.", value: "defibrilator" },
  { text: "N₂O terhubung antara sumber gas dengan mesin anastesia.", value: "compressed2" },
  { text: "Flow meter N₂O di mesin anastesia berfungsi, aliran gas keluar dari mesin dapat dirasakan.", value: "flown2o" },
];

const mesinanastesiaOptions = [
  { text: "Power ON", value: "power" },
  { text: "Self callibration: DONE", value: "self" },
  { text: "Tidak ada kebocoran sirkuit nafas", value: "bocor" },
  { text: "Zat volatil terisi", value: "zat2" },
  { text: "Absorber CO₂ dalam kondisi baik", value: "power2" },
];

const manajemenjalannifasOptions = [
  { text: "Sungkup muka dalam ukuran yang benar.", value: "sungkup" },
  { text: "Oropharygeal airway (Guedel) dalam ukuran yang benar.", value: "oropharygeal" },
  { text: "Batang laringoskop berisi baterai.", value: "batang" },
  { text: "Bilah alringoskop dalam ukuran yang benar.", value: "bilah" },
  { text: "Gagang dan bilah laringoskop berfungsi baik", value: "gagang" },
  { text: "ETT atau LMA dalam ukuran yang benar, tidak bocor.", value: "ett" },
  { text: "Stilet (introduser).", value: "stilet" },
  { text: "Semprit untuk mengembangkan cuff.", value: "semprit" },
  { text: "Forceps Magill.", value: "forceps" },
];

const pemantauanOptions = [
  { text: "Kabel EKG dengan layar pemantau.", value: "kabel" },
  { text: "Elektroda EKG dalam jumlah dan ukuran sesuai.", value: "elektroda" },
  { text: "NIBP terhubung dengan layar pantau, ukuran manset sesuai.", value: "nibp" },
  { text: "SpO2 terhubung dengan layar pantau, berfungsi baik.", value: "zat" },
  { text: "Kapnografi terhubung dengan layar pantau, berfungsi baik.", value: "kapnografi" },
  { text: "Pemantau suhu terhubung dengan layar pantau.", value: "pemantau" },
];

const lainlainOptions = [
  { text: "Stetoskop tersedia.", value: "stetoskop" },
  { text: "Suction berfungsi baik.", value: "suction" },
  { text: "Selang suction terhubung, kateter suction dalam ukuran yang benar", value: "selang" },
  { text: "Plester untuk fiksasi", value: "plester" },
  { text: "Blanket roll / hemotherm / radiant heater terhubung sumber listrik, berfungsi baik", value: "blanket" },
  { text: "Lidocaine spray / jelly", value: "lidocaine" },
  { text: "Defibrillator jelly", value: "defibrillator" },
];

const obatobatOptions = [
  { text: "Epinefrin", value: "epinefrin" },
  { text: "Atropin", value: "atropin" },
  { text: "Sedatif (midazolam / propofol / etomidat / ketamin / tiopental)", value: "sedatif" },
  { text: "Opiat / opioid", value: "opiat" },
  { text: "Pelumpuh otot", value: "pelumpuh" },
  { text: "Antibiotika", value: "antibiotika" },
  { text: "Lain-lain", value: "lainnya1" }
];

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object['TTDPetugas1'] = H.tandaTangan().get("TTDPetugas1");
  object['TTDPetugas2'] = H.tandaTangan().get("TTDPetugas2");
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

  if (!input.value.CBPetugas1 || H.tandaTangan().get("TTDPetugas1") == undefined) {
    H.alert('warning', 'Silahkan Tanda Tangan dan Pilih Perawat Poliklinik/IGD');
    return;
  }

  if (!input.value.CBPetugas2 || H.tandaTangan().get("TTDPetugas2") == undefined) {
    H.alert('warning', 'Silahkan Tanda Tangan dan Pilih Perawat Koordinator Jaga Poliklinik/IGD');
    return;
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

const addNewItem = () => {
  input.value.riwayatAlergi.push({
    no: input.value.riwayatAlergi[input.value.riwayatAlergi.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.riwayatAlergi.splice(index, 1)
}

const addNewItemDaftarObat = () => {
  input.value.daftarObat.push({
    no: input.value.daftarObat[input.value.daftarObat.length - 1].no + 1,
  });
}
const removeItemDaftarObat = (index: any) => {
  input.value.daftarObat.splice(index, 1)
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}


setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.table-ro {
  width: 100%;
  border: 1px solid;
}

.th-ro,
.td-ro {
  border: 1px solid;
  padding: 7px;
}

.th-ro {
  text-align: center !important;
  vertical-align: inherit;
}

.td-ro {
  vertical-align: inherit;
}

.label-ro {
  font-weight: 500;
}

.title-ro {
  font-weight: bold;
}
</style>
