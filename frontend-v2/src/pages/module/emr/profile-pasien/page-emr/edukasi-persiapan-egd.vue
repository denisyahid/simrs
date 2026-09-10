<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>
                EDUKASI PERSIAPAN PASIEN ESOPHAGO GASTRO DUODENOSCOPY (EGD)
              </h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideST>
              </ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField label="Ruangan">
                <VControl class="prime-auto">
                    <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan"
                        @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Ketika nama ruangan..." />
                </VControl>
              </VField>
          </div>
          <div class="column is-4">
            <VField>

            </VField>
          </div>
          <div class="column is-4">
            <VField label="Tanggal">
              <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                  </template>
              </VDatePicker>
            </VField>
          </div>
          </div>
        </div>
        <div class="column is-12">
          <table class="table is-bordered is-fullwidth">
              <tr>
                <th width="2%">No</th>
                <th width="58%">Penjelasan Yang Diberikan</th>
                <th width="20%">Keterangan</th>
                <th width="20%">Tanggal</th>
              </tr>
              <tr>
                <th>
                  1.
                </th>
                <th>
                  Puasa makan dan minum 8 jam sebelum Tindakan
                </th>
                <th>
                  <VField>
                      <VTextarea rows="7" cols="50" v-model="input.puasa"></VTextarea>
                  </VField>
                </th>
                <th>
                  <VDatePicker v-model="input.tglPuasa" mode="datetime" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </th>
              </tr>
              <tr>
                <th>
                  2.
                </th>
                <th>
                  Stop obat-obatan seperti Aspirin, warfarin dan Clopidogrel ( 5 hari) dan Sukralfat (1 hari)sebelum Tindakan karena akan mengaburkan pemeriksaan
                </th>
                <th>
                  <VField>
                      <VTextarea rows="7" cols="50" v-model="input.stopObat"></VTextarea>
                  </VField>
                </th>
                <th>
                  <VDatePicker v-model="input.tglStopObat" mode="datetime" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </th>
              </tr>
              <tr>
                <th>
                  3.
                </th>
                <th>
                  Bila pasien minum obat Hipertensi dan gula tetap diminum sebelum memulai puasa
                </th>
                <th>
                  <VField>
                      <VTextarea rows="7" cols="50" v-model="input.hipertensi"></VTextarea>
                  </VField>
                </th>
                <th>
                  <VDatePicker v-model="input.tglHipertensi" mode="datetime" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </th>
              </tr>
              <tr>
                <th>
                  4.
                </th>
                <th>
                  Kie bila ada gigi palsu mohon di lepas dan Tidak memakai aksesoris (perhiasan, kutex), pasien datang ke rumah sakit PUKUL 
                  <VDatePicker style="width: 20%;" v-model="input.pukulGigiPalsu" mode="time" is24hr>
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:clock" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </th>
                <th>
                  <VField>
                      <VTextarea rows="7" cols="50" v-model="input.gigiPalsu"></VTextarea>
                  </VField>
                </th>
                <th>
                  <VDatePicker v-model="input.tglGigiPalsu" mode="datetime" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </th>
              </tr>
              <tr>
                <th>
                  5.
                </th>
                <th>
                  a. Cek Laboratorium DL dan Faal Hemostasis <br>
                  Hemoglobin harus ≥ 8 g/dl <br>
                  Faal hemostatis (BT,Ct dalam batas normal) <br>
                  HbsAg, AntiHcv (sesuai indikasi) <br>
                  b. EKG <br>
                  c.  Berat badan/Tinggi Badan pasien (untuk pasien dengan pembiusan umum/general anastesi) <br>
                  d. Kie pasien bila ada rencana konsul ke poli lain sesuai intruksi Dokter untuk persiapan Endoskopi <br>
                  e. Untuk pasien yang menggunakan pembiusan umum dikonsulkan ke TIM Anastesi  sebelum tindakan
                  </th>
                <th>
                  <VField>
                      <VTextarea rows="7" cols="50" v-model="input.cekLab"></VTextarea>
                  </VField>
                </th>
                <th>
                  <VDatePicker v-model="input.tglCekLab" mode="datetime" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </th>
              </tr>
              <tr>
                <th>
                  6.
                </th>
                <th>
                  Perkiraan biaya ±Rp <VControl style="width: 30%;">
                      <VInput type="text" class="input" v-model="input.ISISENDIRI" />
                  </VControl> (untuk Pasien Umum Kie ke admission membawa form perkiraan biaya)
                  </th>
                <th>
                  <VField>
                      <VTextarea rows="7" cols="50" v-model="input.pekiraanBiaya"></VTextarea>
                  </VField>
                </th>
                <th>
                  <VDatePicker v-model="input.tglPekiraanBiaya" mode="datetime" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </th>
              </tr>
              <tr>
                <th>
                  7.
                </th>
                <th>
                  Ada Persetujuan Tindakan (infomed concent)
                </th>
                <th>
                  <VField>
                      <VTextarea rows="7" cols="50" v-model="input.persetujuanTindakan"></VTextarea>
                  </VField>
                </th>
                <th>
                  <VDatePicker v-model="input.tglPersetujuanTindakan" mode="datetime" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </th>
              </tr>
              <tr>
                <th>
                  8.
                </th>
                <th>
                  Ada keluarga yang menemani (untuk pasien rawat jalan) dan ada perawat yang mengantar (untuk pasien rawat INAP)
                </th>
                <th>
                  <VField>
                      <VTextarea rows="7" cols="50" v-model="input.keluargaMenemani"></VTextarea>
                  </VField>
                </th>
                <th>
                  <VDatePicker v-model="input.tglKeluargaMenemani" mode="datetime" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </th>
              </tr>
              <tr>
                <th>
                  9.
                </th>
                <th>
                  Tanggal Tindakan EGD dan Jika terjadi pembatalan Tindakan berikan alasan, Tulis keluhan pasien dan Diagnose pasien
                </th>
                <th>
                  <VField>
                      <VTextarea rows="7" cols="50" v-model="input.tindakanEGD"></VTextarea>
                  </VField>
                </th>
                <th>
                  <VDatePicker v-model="input.tglTindakanEGD" mode="datetime" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                      </template>
                  </VDatePicker>
                </th>
              </tr>
          </table>
          <div class="columns" style="padding-top: 40px; padding-bottom: 60px;">
            <div class="column is-4" style="text-align: center;">
                <h1>Pasien / Keluarga Yang menerima penjelasan</h1>
                <!-- <TandaTangan :elemenID="'TTDPasien'" :width="'150'" :height="'150'" class="dek" /> -->
                <VControl>
                    <VInput type="text" class="input" v-model="input.TBPasien_ttd" />
                </VControl>
            </div>
            <div class="column is-4">
              
            </div>
            <div class="column is-4" style="text-align: center;">
                <h1>Perawat Yang Memberikan</h1>
                <!-- <TandaTangan :elemenID="'TTDRI'" :width="'150'" :height="'150'" class="dek" /> -->
                <VControl class="prime-auto">
                    <AutoComplete v-model="input.DDPetugasRI" :suggestions="d_Petugas"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Ketika nama petugas..." />
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
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';

useHead({
  title: 'Edukasi Persiapan EGD - ' + import.meta.env.VITE_PROJECT,
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
const d_Petugas: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({})
const COLLECTION: any = ref('EdukasiPersiapanEDG') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  pukulGigiPalsu : new Date(),
})
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const d_freknyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang Timbul' }, { value: 3, label: 'Terus Menerus' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lain-lain' }])
const d_gangguanpsikologis: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Gelisah' }, { value: 3, label: 'Takut' }, { value: 4, label: 'Sedih' }, { value: 5, label: 'Rendah diri' }, { value: 6, label: 'Acuh tak acuh' }, { value: 7, label: 'Mudah tersinggung' }, { value: 8, label: 'Menarik diri' }])
const d_masalahkawin: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_kekerasan: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaankesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi Lainnya' }])
const d_rohaniawan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_penurunanbb: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }, { value: 3, label: '1-5 kg' }, { value: 4, label: '6-10 kg' }, { value: 5, label: '11-15 kg' }, { value: 6, label: '>15 kg' }])
const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_mengontrolbab: any = ref([{ value: 1, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 2, label: 'Kadang inkontinen (1xseminggu)' }, { value: 3, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 1, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 2, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 3, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 1, label: 'Butuh pertolongan orang lain' }, { value: 2, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 1, label: 'Tergantung pertolongan orang lain' }, { value: 2, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 3, label: 'Mandiri' }])
const d_makan: any = ref([{ value: 1, label: 'Tidak mampu' }, { value: 2, label: 'Perlu seseorang menolong memotong makanan' }, { value: 3, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Dengan kursi roda' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 1, label: 'Tergantung orang lain' }, { value: 2, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 3, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Butuh Pertolongan' }, { value: 3, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 1, label: 'Teragantung orang lain' }, { value: 2, label: 'Mandiri' }])
const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const d_Ruangan: any = ref([]);

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  );
  d_Ruangan.value = response;
};

let dropdownAllo: any = ref([
  "Suami/Istri",
  "Orang tua",
  "Anak",
  "Lainnya"
])

const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    H.tandaTangan().set("TTDPegawai", response[0]['TTDPegawai'])
  } else {
    getDataExist()
  }
}
const filterMenu: any = ref('')
const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDPegawai'] = H.tandaTangan().get("TTDPegawai");
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

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const getDataExist = () => {
  input.value.tanggal = new Date()
  input.value.kebjamKedatangan = new Date()
  input.value.kebjamAsesmenAwal = new Date()
}

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
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


// const simpanTemplate = () => {
//   if (!input.value.namatemplate) {
//     H.alert('warning', "Nama Template wajib diisi")
//     return;
//   }
//   let ID = input.id ? input.id : ''

//   let object: any = {}

//   object = input.value
//   object.nocm = pasien.value.nocm

//   object.pasien = H.setObjectPasien(pasien.value)
//   object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
//   let json = {
//     'id': ID,
//     'norec_emr': NOREC_EMRPASIEN.value,
//     'collection': COLLECTION.value,
//     'url_form': props.FORM_URL,
//     'name_form': props.FORM_NAME,
//     'jenis_emr': 'asesmen_medis',
//     'data': object
//   }
//   isLoading.value = true

//   useApi().post(
//     `/emr/simpan-emr-template`, json).then((response: any) => {
//       isLoading.value = false
//       input.value.namatemplate = null
//     }).catch((e: any) => {
//       isLoading.value = false
//     })
// }

// const pilihTemplate = async (index: any) => {
//   isLoading.value = true
//   useApi().get(
//     `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
//       isLoading.value = false
//       if (responselast.length) {
//         listTemplate.value = responselast //set ke inputan
//         showModalTemplate.value = true
//       } else {
//         H.alert('warning', 'Data tidak ada')
//       }
//     })
// }

// const addTemplate = (response: any) => {
//   console.log(response)
//   input.value = response //set ke inputan
//   input.value.namatemplate = null
// }

// const pilihTemplateFix = async (index: any) => {
//   isLoading.value = true
//   useApi().get(
//     `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
//       isLoading.value = false
//       console.log(responselast)
//       if (responselast.length) {
//         for (var x = 0; x < responselast.length; x++) {
//           responselast[x].no = x + 1
//           responselast[x].id = ''
//         }
//         listTemplateFix.value = responselast //set ke inputan
//         showModalTemplateFix.value = true
//       } else {
//         H.alert('warning', 'Data tidak ada')
//       }
//     })
// }
</script>

<style lang="scss">
.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

table.assesment {
  border-collapse: collapse;
  width: 100%;
}

.tables tr td {
  border: 1px solid #b3b3b3;
}

.tables tr th {
  border: 1px solid #b3b3b3;
}

.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
  // border: 1px solid black;
}

.assesment th,
.assesment td {
  padding: 8px;
  vertical-align: middle !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

.table.is-borderless {
  border: none !important;
  background-color: transparent;
}

.table.is-borderless th,
tr,
td {
  border: none !important;
  background-color: transparent !important;
}

.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

.td-po {
  border: 0.5px solid black !important;
  white-space: pre-line;
}

table.assesment {
  border-collapse: collapse;
  width: 100%;
}

.grey-background {
  background-color: #d3d3d3;
  /* Grey color */
}

.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
  // border: 1px solid black;
}

.assesment th,
.assesment td {
  padding: 8px;
  vertical-align: middle !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

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

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
}


.tg2 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg2 td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg2 th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: middle
}
</style>