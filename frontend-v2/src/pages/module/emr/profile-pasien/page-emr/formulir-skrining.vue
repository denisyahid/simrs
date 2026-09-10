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
      <div class="column pb-0">
        <h1>Anamnesa dan Riwayat (14 Hari SMRS)</h1>
        <div class="columns is-multiline pt-5 pl-3 pr-3" style="border-bottom: 1px solid black;">
          <div class="column is-1">
            <span>NO</span>
          </div>
          <div class="column is-8">
            <span>PERTANYAAN</span>
          </div>
          <div class="column is-3">
            <span>SKOR</span>
          </div>
        </div>
        <div class="column">
          <div class="columns is-multiline" v-for="(data) in anamnesa">
            <div class="column is-1 pl-0 pt-3 pr-3">
              <span>{{ data.no }}</span>
            </div>
            <div class="column is-5 is-1 pl-0 pt-3 pr-3">
              <span>{{ data.pertanyaan }}</span>
            </div>
            <div class="column is-3 is-1 pl-0 pt-3 pr-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.value" class="p-0" color="primary" squer />
                </VControl>
              </VField>
            </div>
            <div class="column is-1 is-1 pl-5 pt-3 pr-3">
              <span>{{ data.skor }}</span>
            </div>
          </div>
        </div>
        <div class="column is-10">
          <div class="column is-2" style="margin-left: auto;">
            <span>TOTAL SKOR</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.totalSkorAnamenesa" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column pt-0">
        <h1>Gejala Klinis</h1>
        <div class="columns is-multiline pt-5 pl-3 pr-3" style="border-bottom: 1px solid black;">
          <div class="column is-1">
            <span>NO</span>
          </div>
          <div class="column is-8">
            <span>PERTANYAAN</span>
          </div>
          <div class="column is-3">
            <span>SKOR</span>
          </div>
        </div>
        <div class="column">
          <div class="columns is-multiline" v-for="(data) in gejalaKlinis">
            <div class="column is-1 pl-0 pt-3 pr-3">
              <span>{{ data.no }}</span>
            </div>
            <div class="column is-5 is-1 pl-0 pt-3 pr-3">
              <span>{{ data.pertanyaan }}</span>
            </div>
            <div class="column is-3 is-1 pl-0 pt-3 pr-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.value" class="p-0" color="primary" squer />
                </VControl>
              </VField>
            </div>
            <div class="column is-1 is-1 pl-5 pt-3 pr-3">
              <span>{{ data.skor }}</span>
            </div>
          </div>
        </div>
        <div class="column is-10">
          <div class="column is-2" style="margin-left: auto;">
            <span>TOTAL SKOR</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.totalSkorGejala" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <h1>Skor</h1>
        <div class="column pl-0 pr-0">
          <table class="table-fs">
            <thead>
              <tr>
                <th class="th-fs">Pemeriksaan</th>
                <th class="th-fs">Kriteria</th>
                <th class="th-fs">Skor</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td rowspan="5" class="td-fs" style="vertical-align: inherit; text-align: center;">Anamnesa dan Riwayat
                  (14 hari SMRS)</td>
                <td class="td-fs">Demam</td>
                <td class="td-fs">0 = Tidak Ada</td>
              </tr>
              <tr>
                <td class="td-fs">Batuk/Pilek/Nyeri Tenggorokan</td>
                <td class="td-fs">1 = Ada Salah Satu</td>
              </tr>
              <tr>
                <td class="td-fs">Nyeri Otot</td>
                <td class="td-fs">2 = Ada > 1</td>
              </tr>
              <tr>
                <td class="td-fs">Kontak erat/Kasus konfirmasi</td>
                <td class="td-fs">3 = Jika Kontak (+), Swab (+)</td>
              </tr>
              <tr>
                <td class="td-fs">Riwayat Pemeriksaan Swab test (Pn Cr)</td>
                <td class="td-fs"></td>
              </tr>

              <tr>
                <td rowspan="4" class="td-fs" style="vertical-align: inherit; text-align: center;">Gejala Klinis</td>
                <td class="td-fs">Suhu 38°C</td>
                <td class="td-fs">0 = Tidak Ada</td>
              </tr>
              <tr>
                <td class="td-fs">Sesak Nafas</td>
                <td class="td-fs">1 = Jika hipertermia</td>
              </tr>
              <tr>
                <td class="td-fs"></td>
                <td class="td-fs">2 = Hipotermia</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="column">
        <table class="table-fs">
          <thead>
            <tr>
              <th rowspan="2" class="th-fs" style="vertical-align: inherit;" width="10%">SKOR = 0</th>
              <th style="font-weight: 400;" class="th-desc"> 1. Pasien/pengunjung boleh melanjutkan ke area pelayanan
                kesehatan di zona non COVID-19</th>
            </tr>
            <tr>
              <th style="font-weight: 400;" class="th-desc">2. Memakai masker, cuci tangan dengan sabun dan air mengalir
                serta penerapan jarak fisik > 1m</th>
            </tr>
          </thead>
        </table>
      </div>

      <div class="column">
        <table class="table-fs">
          <thead>
            <tr>
              <th rowspan="4" class="th-fs" style="vertical-align: inherit;" width="10%">SKOR = 1-3</th>
              <th style="font-weight: 400;" class="th-desc"> 1. Penunjung yang memiliki skor 1-3 diterapkan sistem
                penanganan pasien gejala COVID-19</th>
            </tr>
            <tr>
              <th style="font-weight: 400;" class="th-desc">2. Pasien diarahkan ke IGD khusus COVID-19 untuk evaluasi
                lebih lanjut</th>
            </tr>
            <tr>
              <th style="font-weight: 400;" class="th-desc">3. Pasien menunggu di ruang COVID-19</th>
            </tr>
            <tr>
              <th style="font-weight: 400;" class="th-desc">4. Memakai masker, cuci tangan dengan sabun dan air mengalir
                serta penerapan jarak fisik > 1m</th>
            </tr>
          </thead>
        </table>
      </div>
    </VCard>
  </div>

  <div class="column is-4" style="margin-left:auto">
    <VCard>
      <div class="column is-8">
        <h1>Bandung</h1>
        <VDatePicker v-model="input.tglDibuat" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks
          :max-date="new Date()">
          <template #default="{ inputValue, inputEvents }">
            <VField>
              <VControl icon="feather:calendar" fullwidth>
                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
              </VControl>
            </VField>
          </template>
        </VDatePicker>
      </div>
      <div class="column">
        <h1>Petugas Skrining</h1>
        <VField class="pt-3">
          <VControl class="prime-auto">
            <AutoComplete v-model="input.petugasSkrining" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" placeholder="Cari Pegawai..." />
          </VControl>
        </VField>
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
import * as EMR from '../page-emr-plugins/formulir-skrining'
import AutoComplete from 'primevue/autocomplete';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let anamnesa = ref(EMR.anamnesa())
let gejalaKlinis = ref(EMR.gejalaKlinis())

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
const d_Pegawai: any = ref([])
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
  tglDibuat : new Date
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

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => { }

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

setView()
setAutoFill()
loadRiwayat()


watch(() => [
  input.value.suhu,
  input.value.pernafasan,
], () => {
  let poin1 = input.value.suhu ? parseInt(input.value.suhu.poin) : 0
  let poin2 = input.value.pernafasan ? parseInt(input.value.pernafasan.poin) : 0

  const total = poin1 + poin2
  input.value.totalSkorGejala = total

})

watch(() => [
  input.value.isDemam,
  input.value.isNyeri,
  input.value.kasusKonfirmasi,
  input.value.riwayatPCR,
], () => {
  let poin1 = input.value.isDemam ? parseInt(input.value.isDemam.poin) : 0
  let poin2 = input.value.isNyeri ? parseInt(input.value.isNyeri.poin) : 0
  let poin3 = input.value.kasusKonfirmasi ? parseInt(input.value.kasusKonfirmasi.poin) : 0
  let poin4 = input.value.riwayatPCR ? parseInt(input.value.riwayatPCR.poin) : 0

  const total = poin1 + poin2 + poin3 + poin4
  input.value.totalSkorAnamenesa = total

})


</script>

<style lang="scss">
.table-fs {
  width: 100%;
  border: 1px solid black;
}

.th-fs {
  text-align: center !important;
}

.th-desc {
  padding: 7px;
  border: 1px solid black;
}

.th-fs,
.td-fs {
  padding: 7px;
  border: 1px solid black;
}</style>
