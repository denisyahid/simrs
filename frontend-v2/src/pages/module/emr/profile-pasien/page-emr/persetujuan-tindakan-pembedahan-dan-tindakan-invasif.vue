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

  <VCard>
    <h3 class="title is-5 mb-2">Persetujuan Tindakan Pembedahan dan Invasif</h3>
    <p>
    <div class="columns is-multiline">
      <div class="column is-3">
        <h1 class="mt-3">Dokter Pelaksana Tindakan</h1>
      </div>
      <div class="column is-6">
        <VField class="pt-3">
          <VControl class="prime-auto">
            <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
              @item-select="setTandaTanganDokter($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
              placeholder="Cari Dokter..." />
          </VControl>
        </VField>
      </div>
    </div>

    <div class="columns is-multiline">
      <div class="column is-3">
        <h1 class="mt-3">Pemberi Informasi</h1>
      </div>
      <div class="column is-6">
        <VField class="pt-3">
          <VControl class="prime-auto">
            <AutoComplete v-model="input.pegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" placeholder="Cari Pemberi Informasi..." />
          </VControl>
        </VField>
      </div>
    </div>

    <div class="columns is-multiline">
      <div class="column is-3">
        <h1 class="mt-3">Penerima Informasi</h1>
      </div>
      <div class="column is-6">
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.penerimaInformasi" />
          </VControl>
        </VField>
      </div>
    </div>

    <div class="columns is-multiline">
      <div class="column is-3">
        <h1 class="mt-3">Tanggal dan Jam</h1>
      </div>
      <div class="column is-6">
        <VDatePicker class="pt-3" v-model="input.tglDibuat" color="green" trim-weeks mode="datetime"
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
    </p>
  </VCard>

  <vCard>
    <h3 class="title is-5 mb-2">Informasi Anastesi</h3>
    <p>
    <table class="table is-hoverable is-fullwidth">
      <thead>
        <tr>
          <th scope="col"></th>
          <th scope="col" style="width:30%">Jenis Informasi</th>
          <th scope="col" style="width:60%">Isi Informasi</th>
          <th scope="col" class="is-end">Tanda <br>(V)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            1
          </td>
          <td>Diagnosis (WD & DD)</td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.diagnosis" class="is-primary-focus" rows="2" placeholder="Diagnosis...">
                </VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionDiagnosis" color="primary" />
            </VControl>
          </td>
        </tr>
        <tr>
          <td>
            2
          </td>
          <td>Dasar Diagnosis</td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.dasarDiagnosis" class="is-primary-focus" rows="2"
                  placeholder="Dasar Diagnosis...">
                </VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionDasarDiagnosis" color="primary" />
            </VControl>
          </td>
        </tr>
        <tr>
          <td>
            3
          </td>
          <td>Tindakan Kedokteran</td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.tindakanKedokteran" class="is-primary-focus" rows="2"
                  placeholder="Tindakan Kedokteran...">
                </VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionTindakanKedokteran" color="primary" />
            </VControl>
          </td>
        </tr>
        <tr>
          <td>
            4
          </td>
          <td>Indikasi Tindakan</td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.indikasiTindakan" class="is-primary-focus" rows="2"
                  placeholder="Indikasi Tindakan...">
                </VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionIndikasiTindakan" color="primary" />
            </VControl>
          </td>
        </tr>
        <tr>
          <td>
            5
          </td>
          <td>
            Tata Cara <br>
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionUSPDTYP" color="primary"
                label="uraian singkat prosedur dan tahapan yang penting" />
            </VControl>
          </td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.tataCara" class="is-primary-focus" rows="2" placeholder="Tata Cara...">
                </VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionTataCara" color="primary" />
            </VControl>
          </td>
        </tr>
        <tr>
          <td>
            6
          </td>
          <td>Tujuan</td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.tujuan" class="is-primary-focus" rows="2" placeholder="Tujuan..."></VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionTujuan" color="primary" />
            </VControl>
          </td>
        </tr>
        <tr>
          <td>
            7
          </td>
          <td>Risiko</td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.risiko" class="is-primary-focus" rows="2" placeholder="Risiko..."></VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionRisiko" color="primary" />
            </VControl>
          </td>
        </tr>
        <tr>
          <td>
            8
          </td>
          <td>Komplikasi</td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.komplikasi" class="is-primary-focus" rows="2" placeholder="Komplikasi...">
                </VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionKomplikasi" color="primary" />
            </VControl>
          </td>
        </tr>
        <tr>
          <td>
            9
          </td>
          <td>Prognosis
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionPrognosisVisual" color="primary" label="Prognosis Visual"
                style="padding: 0 !important" />
            </VControl>
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionPrognosisFungsi" color="primary" label="Prognosis Fungsi"
                style="padding: 0 !important" />
            </VControl>
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionPrognosisKesembuhan" color="primary" label="Prognosis Kesembuhan"
                style="padding: 0 !important" />
            </VControl>
          </td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.prognosis" class="is-primary-focus" rows="2" placeholder="Prognosis...">
                </VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionPrognosis" color="primary" />
            </VControl>
          </td>
        </tr>
        <tr>
          <td>
            10
          </td>
          <td>Alternatif & Risiko</td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.alternatifDanRisiko" class="is-primary-focus" rows="2"
                  placeholder="Alternatif & Risiko...">
                </VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionAlternatifDanRisiko" color="primary" />
            </VControl>
          </td>
        </tr>
        <tr>
          <td>
            11
          </td>
          <td>Manajemen Nyeri Pasca Operasi</td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.mnpo" class="is-primary-focus" rows="2"
                  placeholder="Manajemen Nyeri Pasca Operasi ..."></VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionMnpo" color="primary" />
            </VControl>
          </td>
        </tr>
        <tr>
          <td>
            12
          </td>
          <td>Hal yang akan dilakukan untuk menyelamatkan pasien</td>
          <td>
            <VField>
              <VControl>
                <VTextarea v-model="input.halMenyelamatkanPasien" class="is-primary-focus" rows="2"
                  placeholder="Hal yang akan dilakukan untuk menyelamatkan pasien..."></VTextarea>
              </VControl>
            </VField>
          </td>
          <td class="is-end">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.optionHalMenyelamatkanPasien" color="primary" />
            </VControl>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="columns is-multiline">
      <div class="column is-10">
        <h1 class="mt-3">Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar dan jelas dan
          memberikan
          kesempatan untuk bertanya dan/atau berdiskusi</h1>
      </div>
      <div class="column is-2">
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.pernyantaanKeterangan" />
          </VControl>
        </VField>
      </div>
    </div>

    <div class="columns is-multiline">
      <div class="column is-10">
        <h1 class="mt-3">Dengan ini menyatakan bahwa saya telah menerima informmasi sebagaimana di atas yang saya beri
          tanda/paraf di kolom kanannya, dan telah memahaminya </h1>
      </div>
      <div class="column is-2">
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.memahamiInformasi" />
          </VControl>
        </VField>
      </div>
    </div>

    </p>
  </vCard>

  <vCard>
    <h3 class="title is-5 mb-2">Persetujuan Tindakan Anastesi</h3>
    <p>
    <div class="columns is-multiline">
      <div class="column is-4">
        <h1 class="mt-3">Yang bertanda tangan dibawah ini, saya :
        </h1>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-4">
        <h1 class="mt-3">Nama :</h1>
      </div>
      <div class="column is-8">
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.namaPTA" />
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-4">
        <h1 class="mt-3">Umur :</h1>
      </div>
      <div class="column is-8">
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.umurPTA" />
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-4">
        <h1 class="mt-3">Jenis Kelamin :</h1>
      </div>
      <div class="column is-8">
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.jenisKelaminPTA" />
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-4">
        <h1 class="mt-3">Alamat :</h1>
      </div>
      <div class="column is-8">
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.alamatPTA" />
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-4">
        <h1 class="mt-3">Dengan ini menyatakan persetujuan untuk dilakukan tindakan terhadap saya /</h1>
      </div>
      <div class="column is-8">
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.pernyataanPTA" />
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-4">
        <h1 class="mt-3">saya, Yang bernama</h1>
      </div>
      <div class="column is-8">
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.yangBernamaPTA" />
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-4">
        <h1 class="mt-3">Tanggal Lahir</h1>
      </div>
      <div class="column is-8">
        <VDatePicker class="pt-3" v-model="input.tglLahirPTA" color="green" trim-weeks mode="date" :max-date="new Date()">
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
    <div class="columns is-multiline">
      <div class="column is-4">
        <h1 class="mt-3">Jenis Kelamin</h1>
      </div>
      <div class="column is-8">
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.jeniskelamin2PTA" />
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-4">
        <h1 class="mt-3">Alamat</h1>
      </div>
      <div class="column is-8">
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.alamat2PTA" />
          </VControl>
        </VField>
      </div>
    </div>
    Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan kepada saya,termasuk risiko dan
    komplikasi yang mungkin timbul. <br>
    Saya juga menyadari bahwa ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan (Anastesi) bukanlah
    keniscayaan melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa.
    </p>
  </vCard>
  <vCard>
    <p>
    <div class="columns is-multiline">
      <div class="column is-4">
        <h1 class="mt-3">Tanggal dan Jam</h1>
      </div>
      <div class="column is-8">
        <VDatePicker class="pt-3" v-model="input.tglDibuat" color="green" trim-weeks mode="datetime"
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
    <div class="columns is-multiline">
      <div class="column is-4" style="text-align: center;">
        <p class="label-ppap">Saksi 1</p>
        <TandaTangan :elemenID="'signatureSaksi1'" :width="'180'" :height="'180'" class="dek" />
        <div class="column pl-0 pr-0 pt-5">
          <VField class="pt-3">
            <VControl class="prime-auto">
              <VInput type="text" v-model="input.saksi1" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="column is-4" style="text-align: center;">
        <p class="label-ppap">Saksi 2</p>
        <TandaTangan :elemenID="'signatureSaksi2'" :width="'180'" :height="'180'" class="dek" />
        <div class="column pl-0 pr-0 pt-5">
          <VField class="pt-3">
            <VControl class="prime-auto">
              <VInput type="text" v-model="input.saksi2" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="column is-4" style="text-align: center;">
        <p class="label-ppap">Yang menyatakan</p>
        <TandaTangan :elemenID="'signatureYangMenyatakan'" :width="'180'" :height="'180'" class="dek" />
        <div class="column pl-0 pr-0 pt-5">
          <VField class="pt-3">
            <VControl class="prime-auto">
              <VInput type="text" v-model="input.yangMenyatakan" />
            </VControl>
          </VField>
        </div>
      </div>

    </div>
    </p>
  </vCard>

  <div class="column">
    <!-- form emr -->
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
import Fieldset from 'primevue/fieldset';
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
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
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
        if (response[0].ttdsaksi1) {
          H.tandaTangan().set("signatureSaksi1", response[0].ttdsaksi1)
        }
        if (response[0].ttdsaksi2) {
          H.tandaTangan().set("signatureSaksi2", response[0].ttdsaksi2)
        }
        if (response[0].ttdyangMenyatakan) {
          H.tandaTangan().set("signatureYangMenyatakan", response[0].ttdyangMenyatakan)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.ttdsaksi1 = H.tandaTangan().get("signatureSaksi1")
  object.ttdsaksi2 = H.tandaTangan().get("signatureSaksi2")
  object.ttdyangMenyatakan = H.tandaTangan().get("signatureYangMenyatakan")
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

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}
setView()
setAutoFill()
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
