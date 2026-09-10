<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Kriteria Masuk Ruangan ICCU</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                  :disabled="isDisabled" @click="print">
                  Cetak
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
              </div>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column">
          <table class="table is-bordered is-fullwidth">
            <thead>
              <tr>
                <th style="width: 80%;">KRITERIA FISIOLOGIS</th>
                <th style="width: 10%;">YA (✓)</th>
                <th style="width: 10%;">TIDAK (✓)</th>
              </tr>
            </thead>

            <thead>
              <tr>
                <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">1. KRITERIA KLINIS
                </th>
              </tr>
              <tr>
                <th>a. Nyeri dada khas angina atau ekuivalen angina</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kriA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kriA" true-value="Tidak" color="primary" />
                </th>
              </tr>
              <tr>
                <th>b. Sesak nafas yang terjadi saat istirahat, tidak membaik dengan posisi duduk</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kriB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kriB" true-value="Tidak" color="primary" />
                </th>
              </tr>
              <tr>
                <th>c. <i>Angina Class </i> (Sesuai kriteria <i>canadian cardiovascular society</i>) yang memburuk</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kriC" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kriC" true-value="Tidak" color="primary" />
                </th>
              </tr>
              <tr>
                <th>d. Palpitasi yang menyebabkan gejala ketidakstabilan hemodinamik</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kriD" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kriD" true-value="Tidak" color="primary" />
                </th>
              </tr>
              <tr>
                <th>e. Sesak nafas atau dyspnoe on effort dengan klas fungsional gagal jantung yang memburuk (sesuai
                  klas fungsional NYHA)</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kriE" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kriE" true-value="Tidak" color="primary" />
                </th>
              </tr>

            </thead>
            <thead>
              <tr>
                <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">2. KRITERIA VITAL SIGN
                </th>
              </tr>
              <tr>
                <th>a. Nadi &lt;40 atau >150kali/menit</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.sigA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.sigA" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>b. Tekanan darah sisolik &lt; 90 mmHg atau penurunan 20 mmHg dari tekanan darah sistolik biasanya
                </th>
                <th class="yes-column">
                  <VCheckbox v-model="input.sigB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.sigB" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>c. <i> Mean Arterial Pressure </i> mmHg atau > 150 mmHg</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.sigC" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.sigC" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>d. Laju respirasi > 35 kali/menit</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.sigD" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.sigD" true-value="Tidak" color="primary" />
                </th>
              </tr>

            </thead>

            <thead>
              <tr>
                <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">3. NILAI LABORATORIUM & RADIOLOGI
                </th>
              </tr>
              <tr>
                <th>a. Peningkatan Troponin yang signifikan yang menyokong IMA atau Miokarditis ( Troponin > 100 atau
                  peningkatan 20% dari baseline dalam 6 jam)</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.lrA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.lrA" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>b. Diseksi aorta dan aorta kritis dari CT Scan</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.lrB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.lrB" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>c. Kardiomegali dengan CTR > 70% menyokong kecurigaan efusi perikard atau tamponade</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.lrC" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.lrC" true-value="Tidak" color="primary" />
                </th>
              </tr>
            </thead>

            <thead>
              <tr>
                <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">4. KRITERIA ECG
                </th>
              </tr>
              <tr>
                <th>a. ST Elevasi spesifik 1mm pada lead II, III, Avf; V3-V6, I, aVL</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kcgA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kcgA" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>b. ST Depresi horizontal atau downslopping 1mm pada lead yang kompleks bersesuaian pada SKA</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kcgB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kcgB" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>c. S1 Q3 T3 yang menyokong klinis emboli paru</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kcgC" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kcgC" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>d. PR depresi yang menyokong klinis pericarditis akut</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kcgD" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kcgD" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>e. LBBB pada IMA atau gagal jantung</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kcgE" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kcgE" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>f. RBBB dengan gambar saddle back</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kcgF" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kcgF" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>g. Syndrome brugada type I dan II</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kcgG" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kcgG" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>h. VT stabil</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kcgH" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kcgH" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>i. Bradicardi dengan HT &lt; 50x/menit</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kcgI" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kcgI" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>j. AF RVR >150x/menit</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kcgJ" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kcgJ" true-value="Tidak" color="primary" />
                </th>
              </tr>
            </thead>

            <thead>
              <tr>
                <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">5. KRITERIA DIAGNOSIS
                </th>
              </tr>
              <tr>
                <th>a. Syok kardiogenik</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.diA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.diA" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>b. Aritmia jantung yang mengancam jiwa sebagai akibat dari penyakit jantung iskemik, kardiomiopati,
                  penyakit jantung reumatik, gangguan elektrolit, efek obat atau keracunan.</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.diB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.diB" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>c. Edema paru akut yang tidak teratasi dengan terapi awal dan tergantung dari penyakit dasarnya.
                </th>
                <th class="yes-column">
                  <VCheckbox v-model="input.diC" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.diC" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>d. Hipertensi emergency</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.diD" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.diD" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>e. Emboli paru masif</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.diE" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.diE" true-value="Tidak" color="primary" />
                </th>
              </tr>

              <tr>
                <th>f. Hipertensi pulmonal</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.diF" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.diF" true-value="Tidak" color="primary" />
                </th>
              </tr>

            </thead>

          </table>
          <table class="table is-bordered is-fullwidth mt-4">
            <thead>
              <tr>
                <th class="grey-background" style="font-size: 19px">Kesimpulan</th>
                <th class="yes-column grey-background"></th>
              </tr>
            </thead>
            <tr>
              <td colspan="2">
                <VField>
                  <b style="font-size: 17px">BERDASARKAN KONDISI DI ATAS MAKA MEMENUHI INDIKASI MASUK ICCU DENGAN
                    PRIORITAS</b>
                  <VField>
                    <VTextarea rows="1" v-model="input.prioritas"></VTextarea>
                  </VField>
                </VField>
              </td>
            </tr>
            <tr>
              <th>Alat transportasi yang digunakan :<br> <i>Transport equipments needed </i> </th>
            </tr>
            <tr>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.brancard" true-value="Ya" color="primary" />
                  <span>Brancard</span>
                </div>
              </th>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.kursiroda" true-value="Tidak" color="primary" />
                  <span>Kursi Roda</span>
                </div>
              </th>
            </tr>

            <tr>
              <th>Pendamping selama transfer : <br> <i>Transfer Escort </i></th>
            </tr>
            <tr>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.docter" true-value="Ya" color="primary" />
                  <span>Dokter / <i>Doctor</i></span>
                </div>
              </th>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.paramedic" true-value="Tidak" color="primary" />
                  <span>Paramedis / <i>Paramedic </i></span>
                </div>
              </th>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.caregiver" true-value="Tidak" color="primary" />
                  <span>Care Giver/POS </span>
                </div>
              </th>
            </tr>

            <tr>
              <th>Alat medis yang dibawa selama transfer : <br> <i>Medical equipments needed while transfer</i> </th>
            </tr>
            <tr>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.hooh" true-value="Ya" color="primary" />
                  <span>YA,</span>
                  <VField>
                    <VTextarea v-model="input.textAreaValue" placeholder="Sebutkan" color="primary" />
                  </VField>
                </div>
              </th>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.ora" true-value="Tidak" color="primary" />
                  <span>TIDAK</span>
                </div>
              </th>
            </tr>

          </table>
        </div>

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>

        <div class="columns">
          <div class="column is-8"></div>
          <div class="column is-4">
            <VField label="Garut">
              <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </VField>
            <div class="column pt-0" style="text-align:center;">
              <h1 style="font-weight: bold;">Tanda Tangan</h1>
              <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" placeholder="Ketik nama dokter..." />
              </VControl>
            </div>
          </div>
        </div>
      </div>
    </div>
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
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({})
const COLLECTION: any = ref('KriteriaMasukICCU') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT, })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      if (response[0]['TTDDokter'] != null) {
        H.tandaTangan().set("TTDDokter", response[0]['TTDDokter'])
      }
    } else {
      input.value.tanggal = new Date()
    }
  })
}

const print = async () => {
  H.printBlade(`emr/cetak-formulir-kriteria-masuk-iccu?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
};

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDDokter'] = H.tandaTangan().get("TTDDokter")
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
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => {
    d_Dokter.value = response
  })
}
setView()
loadRiwayat()
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
