<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> Surat Keterangan Kematian </h3>
          </div>
          <div class="right">
            <div class="buttons">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST :isHideCetakWNA="false"></ButtonEmr>
            </div>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-3">
            <span>Kode RS</span>
            <VControl>
              <VInput type="text" class="input" v-model="input.kodeRS" />
            </VControl>
          </div>
          <div class="column is-3">
            <span>Nomor</span>
            <VControl>
              <VInput type="text" class="input" v-model="input.nomor" />
            </VControl>
          </div>
          <div class="column is-3">
            <span>No RM</span>
            <VControl>
              <VInput type="text" class="input" v-model="input.norm" />
            </VControl>
          </div>
          <div class="column is-3">
            <span>Nomor Urut Pencatatan</span>
            <VControl>
              <VInput type="text" class="input" v-model="input.noUrutPencatatan" />
            </VControl>
          </div>
          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>
          <div class="column is-12 pt-0 pb-0" style="font-weight: bold;">
            I. Identitas Pasien
          </div>
          <div class="column is-3">
            <span>Nama Lengkap</span>
            <VControl>
              <VInput type="text" class="input" v-model="input.namaLengkap" />
            </VControl>
          </div>
          <div class="column is-3">
            <span>NIK/No. Paspor</span>
            <VControl>
              <VInput type="text" class="input" v-model="input.NIK" />
            </VControl>
          </div>
          <div class="column is-3">
            <span>Nomor Kartu Keluarga</span>
            <VControl>
              <VInput type="text" class="input" v-model="input.NKK" />
            </VControl>
          </div>
          <div class="column is-3">
            <span>Jenis Kelamin</span>
            <VControl>
              <VInput type="text" class="input" v-model="input.jenisKlm" />
            </VControl>
          </div>
          <div class="column is-3 pt-0">
            <span>Tempat Lahir</span>
            <VControl>
              <VInput type="text" class="input" v-model="input.tmptLhr" />
            </VControl>
          </div>
          <div class="column is-3 pt-0">
            <span>Tanggal Lahir</span>
            <VDatePicker v-model="input.tglLahir" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date">
              <template #default="{ inputValue, inputEvents }">
                <VControl icon="feather:calendar">
                  <VInput type="text" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-3 pt-0">
            <span>Agama</span>
            <VControl>
              <VInput type="text" class="input" v-model="input.agama" />
            </VControl>
          </div>
          <div class="column is-3 pt-0">
            <span>Kewarnegaraan</span>
            <VControl>
              <VInput type="text" class="input" v-model="input.kewarnegaraan" />
            </VControl>
          </div>
          <div class="column is-4 pt-0">
            <span>Alamat</span>
            <VField>
              <VTextarea v-model="input.alamatTempTingl" rows="2">
              </VTextarea>
            </VField>
          </div>
          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>
          <div class="column is-12 pt-0 pb-0" style="font-weight: bold;">
            II. Keterangan Khusus Kematian
          </div>
          <div class="column is-12">
            <span>Tempat Meninggal</span><br>
            <div class="columns">
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Ruang Rawat" label="Ruang Rawat"
                    v-model="input.tempatMeninggal" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Instalasi Gawat Darurat"
                    label="Instalasi Gawat Darurat" v-model="input.tempatMeninggal" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Ruang Bersalin" label="Ruang Bersalin"
                    v-model="input.tempatMeninggal" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Diterima di RS dalam keadaan meninggal"
                    label="Diterima di RS dalam keadaan meninggal" v-model="input.tempatMeninggal" />
                </VControl>
              </div>
            </div>
          </div>
          <div class="column is-12 columns is-multiline m-0 p-0">
            <div class="column is-6 pt-0" v-if="input.tempatMeninggal == 'Ruang Rawat'">
              <div class="columns m-0">
                <div class="column is-6 p-0">
                  <span>Lama Dirawat</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.lamaDirawatHari_RR"
                        placeholder="ruang rawat..." />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Hari</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6 p-0">
                  <span>Jam</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.lamaDirawatJam_RR"
                        placeholder="ruang rawat..." />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Jam</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-6 pt-0" v-if="input.tempatMeninggal == 'Instalasi Gawat Darurat'">
              <div class="columns m-0">
                <div class="column is-6 p-0">
                  <span>Lama Dirawat</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.lamaDirawatHari_IGD"
                        placeholder="instalasi gawat darurat..." />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Hari</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6 p-0">
                  <span>Jam</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.lamaDirawatJam_IGD"
                        placeholder="instalasi gawat darurat..." />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Jam</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-6 pt-0" v-if="input.tempatMeninggal == 'Ruang Bersalin'">
              <div class="columns m-0">
                <div class="column is-6 pb-0 pl-0 pt-0">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Bayi lahir meninggal"
                      label="Bayi lahir meninggal" v-model="input.bayiLahirMeninggal" />
                  </VControl>
                </div>
                <div class="column is-6 pb-0 pt-0">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Ibu melahirkan meninggal"
                      label="Ibu melahirkan meninggal" v-model="input.ibuMelahirkanMeninggal" />
                  </VControl>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-3 pt-0">
            <span>Waktu Meninggal</span>
            <VDatePicker v-model="input.waktuMeninggal" mode="datetime" trim-weeks is24hr>
              <template #default="{ inputValue, inputEvents }">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" v-on="inputEvents" />
                </VControl>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-3 pt-0">
            <span>Perkiraan Waktu Meninggal</span>
            <VDatePicker v-model="input.perkiraanWaktuMeninggal" mode="datetime" trim-weeks is24hr>
              <template #default="{ inputValue, inputEvents }">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" v-on="inputEvents" />
                </VControl>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-3 pt-0">
            <span>Waktu Pemeriksaan</span>
            <VDatePicker v-model="input.waktuPemeriksaan" mode="datetime" trim-weeks is24hr>
              <template #default="{ inputValue, inputEvents }">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" v-on="inputEvents" />
                </VControl>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>
          <div class="column is-12 pt-0 pb-0" style="font-weight: bold;">
            III. Penyebab Kematian
          </div>
          <div class="column is-12 pb-0">
            <span>Dasar Diagnosis</span>
          </div>
          <div class="column is-3 pt-0">
            <VControl raw subcontrol>
              <VCheckbox class="p-0" color="primary" square true-value="Catatan Medis" label="1. Catatan Medis"
                v-model="input.catatanMedis" />
            </VControl>
          </div>
          <div class="column is-3 pt-0">
            <VControl raw subcontrol>
              <VCheckbox class="p-0" color="primary" square true-value="Pemeriksaan Luar Jenasah"
                label="2. Pemeriksaan Luar Jenasah" v-model="input.pemeriksaanLuarJenasah" />
            </VControl>
          </div>
          <div class="column is-3 pt-0">
            <VControl raw subcontrol>
              <VCheckbox class="p-0" color="primary" square true-value="Otopsi Foresik" label="3. Otopsi Foresik"
                v-model="input.otopsiForesik" />
            </VControl>
          </div>
          <div class="column is-3 pt-0">
            <VControl raw subcontrol>
              <VCheckbox class="p-0" color="primary" square true-value="Otopsi Klinik" label="4. Otopsi Klinik"
                v-model="input.otopsiKlinik" />
            </VControl>
          </div>
          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>
          <div class="column is-12 pt-0 pb-0" style="font-weight: bold;">
            IV. Kelompok Penyebab Kematian
          </div>
          <div class="column is-12">
            <div class="columns">
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Alamiah" label="Alamiah"
                    v-model="input.alamiah" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Tidak Alamiah" label="Tidak Alamiah"
                    v-model="input.tidakAlamiah" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Tidak Dapat Ditentukan"
                    label="Tidak Dapat Ditentukan" v-model="input.tidakDD_KPK" />
                </VControl>
              </div>
            </div>
          </div>
          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>
          <div class="column is-3" style="margin-left: auto;text-align: center;">
            <span>Garut</span>
            <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks is24hr>
              <template #default="{ inputValue, inputEvents }">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" v-on="inputEvents" />
                </VControl>
              </template>
            </VDatePicker>
            <span>Dokter Pemeriksaan</span><br>
            <TandaTangan :elemenID="'TTDDokterPemeriksa'" :width="'150'" :height="'150'" class="dek" />
            <VControl class="prime-auto">
              <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" class="mt-2" />
            </VControl>
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
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
// import * as EMR from '../page-emr-plugins/surat-keterangan-kematian'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
// let dataKhusus = ref(EMR.header())
// let hubungan = ref(EMR.hubunganKepalaRumah())
// let tempatMeninggal = ref(EMR.tempatMeninggal())
// let statusJenazah = ref(EMR.statusJenazah())
// let penyebabKematian = ref(EMR.penyebabKematian())
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
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('SuratKeteranganKematian') //table mongodb
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
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      H.tandaTangan().set("TTDDokterPemeriksa", response[0].TTDDokterPemeriksa)
    }
    else {
      setAutoFill()
    }
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}
  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  object.TTDDokterPemeriksa = H.tandaTangan().get("TTDDokterPemeriksa")
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': route.name,
    'name_form': 'Surat Keterangan Kematian',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true
  useApi().post(`/emr/simpan-emr-surket`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const setAutoFill = () => {
  let d = input.value
  d.namaLengkap = props.pasien.namapasien
  d.norm = props.pasien.nocm
  d.jenisKlm = props.pasien.jeniskelamin
  d.tglLahir = props.pasien.tgllahir
  d.NIK = props.pasien.noidentitas
  d.alamatTempTingl = props.pasien.alamatlengkap
  d.tmptLhr = props.pasien.tempatlahir
  d.agama = props.pasien.agama
  d.kewarnegaraan = props.pasien.objectkebangsaanfk == 1 ? 'WNI' : (props.pasien.objectkebangsaanfk == 2 ? 'WNA KITAS' : 'WNA NON KITAS')
  d.waktuMeninggal = new Date()
  d.perkiraanWaktuMeninggal = new Date()
  d.waktuPemeriksaan = new Date()
  d.tanggal = new Date()
  d.dokterPemeriksa = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
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
.title-skk {
  font-weight: bold;
}

.label-skk {
  font-weight: 500;
}
</style>
