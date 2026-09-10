<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Checklist Pemberian Rawat Inap</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
          </div>
        </div>
      </div>

      <div class="columns is-multiline m-0">
        <div class="column is-12">
          <div class="buttons is-flex" style="align-items: center;">
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :isLoading="isLoading"
              @click="pilihTemplate(index)"> Lihat Riwayat
            </VButton>
          </div>
        </div>

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>

        <div class="column is-4">
          <h1 style="font-weight: bold">No. Rekam Medis:</h1>
          <VControl>
            <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
          </VControl>
        </div>

        <div class="column is-4">
          <h1 style="font-weight: bold">Nama Pasien</h1>
          <VControl>
            <VInput v-model="input.namaPasien" class="input" type="text" />
          </VControl>
        </div>

        <div class="column is-4">
          <h1 style="font-weight: bold">Tanggal Lahir</h1>
          <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>

        <div class="column is-4 pb-0">
          <h1 style="font-weight: bold">Jenis Kelamin</h1>
          <div style="display: flex">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label" :label="items.label"
                  color="primary" circle />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>

        <div class="column is-12">
          <table class="table-pri">
            <thead style="text-align: center;">
              <th class="th-pri" width="10%">No</th>
              <th class="th-pri">Uraian Pemberi informasi</th>
              <th class="th-pri">Checklist</th>
            </thead>
            <tbody>
              <tr>
                <td class="td-pri" style="text-align: center;">1</td>
                <td>
                  <b>Kamar Perawatan, Fasilitas Kamar Perawatan dan Cara Pembayaran</b><br>
                  <i>(Room type/class, room facilities, payment method)</i><br>
                  Penjelasan tentang tipe kamar, fasilitas, dan cara pembayaran.
                </td>
                <td class="td-pri" style="text-align: center;">
                  <VControl>
                    <VCheckbox color="primary" true-value="Ya" v-model="input.checklist1" />
                  </VControl>
                </td>
              </tr>
              <tr>
                <td class="td-pri" style="text-align: center;">2</td>
                <td class="td-pri">
                  <b>Penanggungjawab Pasien (Nama dan Hubungan dengan Pasien)</b><br>
                  <i>(Person in charge or responsible for the patient, Name and the relation with the patient)</i>
                </td>
                <td class="td-pri" style="text-align: center;">
                  <VControl>
                    <VCheckbox color="primary" true-value="Ya" v-model="input.checklist2" />
                  </VControl>
                </td>
              </tr>
              <tr>
                <td class="td-pri" style="text-align: center;">3</td>
                <td class="td-pri">
                  <b>Estimasi Biaya</b><br>
                  <i>(Cost Estimation)</i>
                </td>
                <td class="td-pri" style="text-align: center;">
                  <VControl>
                    <VCheckbox color="primary" true-value="Ya" v-model="input.checklist3" />
                  </VControl>
                </td>
              </tr>
              <tr>
                <td class="td-pri" style="text-align: center;">4</td>
                <td class="td-pri">
                  <b>Informasi Denda / Selisih Naik Kelas / Pengurusan Laporan Polisi *</b><br>
                  <i>(Penalty information / deviation for changing room class / police report arrangement)</i>
                </td>
                <td class="td-pri" style="text-align: center;">
                  <VControl>
                    <VCheckbox color="primary" true-value="Ya" v-model="input.checklist4" />
                  </VControl>
                </td>
              </tr>
              <tr>
                <td class="td-pri" style="text-align: center;">5</td>
                <td class="td-pri">
                  <b>Peraturan Ranap (Jam Kunjung, dilarang merokok di area RS)</b><br>
                  <i>(Inpatient service rules, visiting hours, smoking prohibition in hospital buildings and areas)</i>
                </td>
                <td class="td-pri" style="text-align: center;">
                  <VControl>
                    <VCheckbox color="primary" true-value="Ya" v-model="input.checklist5" />
                  </VControl>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="column is-12">
          <p>Apakah pasien / penanggungjawab mengerti dengan penjelasan petugas rawat inap</p>
          <i>is the patient / charge fully understand with these information from admission staff</i>
        </div>
        <div class="column is-12 p-0 is-flex">
          <VControl>
            <VCheckbox class="pb-0 pt-0" true-value="Mengerti" v-model="input.checklist6" color="primary" />
            <span>Mengerti / <i>Understand</i></span>
          </VControl>
        </div>
        <div class="column is-12 p-0 is-flex">
          <VControl>
            <VCheckbox true-value="Belum Mengerti" v-model="input.checklist6" color="primary" />
            <span>Belum mengerti, perlu dijelaskan ulang / <i>Not yet uderstand, need to be re-explained</i></span>
          </VControl>
        </div>

        <div class="column is-flex is-12 pt-0" style="justify-content: space-between;">
            <div class="column is-4" style="text-align:center">
              <h1 style="font-weight: bold">Petugas Pendaftaran Rawat Inap</h1>
              <TandaTangan elemenID="petugasPemberiInformasi" :width="'150'" :height="'150'" class="dek" />
              <VControl class="pt-3">
                <AutoComplete v-model="input.petugasRegistrasi" :suggestions="d_Petugas"
                  @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
              </VControl>
            </div>

            <div class="column is-4" style="text-align:center">
              <h1 style="font-weight: bold">Pasien/Penanggung jawab Pasien</h1>
              <TandaTangan elemenID="penerimaInformasi" :width="'150'" :height="'150'" class="dek" />
              <VControl class="pt-3">
                <VInput type="text" class="input" placeholder="Pasien / Keluarga" v-model="input.penanggungJawabPasien" />
              </VControl>
            </div>
          </div>

        <!-- <div class="is-flex is-12 pt-0" style="justify-content: space-between;">
          <div class="column is-4 mt-3" style="text-align:center">
            <VCard class="border-card pink">
              <div class="column is-12">
                <h1 style="font-weight: bold">Petugas Pendaftaran Rawat Inap</h1>
                <TandaTangan elemenID="petugasPemberiInformasi" :width="'150'" :height="'150'" class="dek" />
                <VField class="pt-3">
                  <VControl>
                    <AutoComplete v-model="input.petugasRegistrasi" :suggestions="d_Petugas"
                      @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
            </VCard>
          </div>

          <div class="column is-4 mt-3" style="text-align:center">
            <VCard class="border-card pink">
              <div class="column is-12">
                <h1 style="font-weight: bold">Pasien/Penanggungjawab Pasien</h1>
                <TandaTangan elemenID="penerimaInformasi" :width="'150'" :height="'150'" class="dek" />
              </div>
              <div class="column pt-0">
                <VField class="is-autocomplete-select">
                  <VControl>
                    <VInput type="text" class="input" placeholder="Pasien / Keluarga" v-model="item.namaPasien" />
                  </VControl>
                </VField>
              </div>
            </VCard>
          </div>
        </div> -->
      </div>
    </div>
  </div>

  <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalTemplate = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                  <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                  <td class="tg-0lax text-center" width="20%">No EMR</td>
                  <td class="tg-0lax text-center" width="20%">Dokter</td>
                  <td class="tg-0lax text-center" width="20%">Section</td>
                  <td class="tg-0lax text-center" width="5%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>

  <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
    @close="showModalTemplateFix = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Template</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplateFix.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="15%">No</td>
                  <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                  <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                  <td class="tg-0lax text-center" width="50%">Nama Template</td>
                  <td class="tg-0lax text-center" width="15%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplateFix">
                <tr>
                  <td style="width:15%;text-align:center">
                    <span class="mb-2">{{ resep.no }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:50%;text-align:center">
                    <span class="mb-2">{{ resep.namatemplate }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>
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
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
import * as EMR2 from '../page-emr-plugins/asesmen-awal-keperawatan-igd'
import * as EMR3 from '../page-emr-plugins/pemberian-informasi-rawat-inap'

useHead({
  title: 'Surat Penyataan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let JenisKelamin = ref(EMR3.JenisKelamin())
let jenisPersetujuan: any = ref(EMR3.jenisPersetujuan())
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
const d_pegawai: any = ref([])
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

const COLLECTION: any = ref('ChecklistPemberianInformasiRawatInap') //table mongodb

const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  kebjamKedatangan: new Date(),
  kebjamAsesmenAwal: new Date(),
  kebtanggalKedatangan: new Date(),
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const dataTTD: any = ref();
const user = useUserSession().getUser().pegawai;


let listHipertensi: any = ref(EMR.hipertensi())
let listDiabetes: any = ref(EMR.diabetes())
let listDyslipidemia: any = ref(EMR.dyslipidemia())
let listDuaPilihan: any = ref(EMR.duaPilihan())
let listAgama: any = ref(EMR.agama())
let listStatus: any = ref(EMR.status())
let listKeluarga: any = ref(EMR.keluarga())
let listTempatTinggal: any = ref(EMR.tempatTinggal())
let listPsikologis: any = ref(EMR.psikologis())
let listMore: any = ref(EMR.more())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let resikoNutrisional: any = ref(EMR.resikoNutrisional())
let fungsionalPertama: any = ref(EMR.fungsionalPertama())
let listRangeNilaiPoin: any = ref(EMR.nilaiPoin())
let listDESCNilai: any = ref(EMR.descNilai())
let pertanyaanA: any = ref(EMR.pertanyaanA())
let pertanyaanB: any = ref(EMR.pertanyaanB())
let pertanyaanC: any = ref(EMR.pertanyaanC())
let dropdownAllo: any = ref([
  "Suami/Istri",
  "Orang tua",
  "Anak",
  "Lainnya"
])
let detailSkriningNutrisi = ref(EMR2.detailSkriningNutrisi())
let statusFungsional: any = ref(EMR2.statusFungsional())

const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      H.tandaTangan().set('penerimaInformasi', dataTTD.value.penerimaInformasi)
      H.tandaTangan().set('petugasPemberiInformasi', dataTTD.value.petugasPemberiInformasi)
    } else {
      input.value.petugasRegistrasi = { label: user.namaLengkap, value: user.id }
    }
  })
}

const simpan = () => {

  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
  object['petugasPemberiInformasi'] = H.tandaTangan().get('petugasPemberiInformasi')
  object['penerimaInformasi'] = H.tandaTangan().get('penerimaInformasi')
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  console.log(json)

  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      // NOREC_EMRPASIEN.value = response.norec_emr
    }).catch((e: any) => {
      isLoading.value = false
    })

  // console.log(resultValue)
}

const simpanTemplate = () => {
  if(!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
  let ID = input.id ? input.id : ''

  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
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
    `/emr/simpan-emr-template`, json).then((response: any) => {
      isLoading.value = false
      input.value.namatemplate = null
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      if (responselast.length) {
        listTemplate.value = responselast //set ke inputan
        showModalTemplate.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

const addTemplate = (response: any) => {
  console.log(response)
  input.value = response //set ke inputan
  input.value.namatemplate = null
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      console.log(responselast)
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          responselast[x].id = ''
        }
        listTemplateFix.value = responselast //set ke inputan
        showModalTemplateFix.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
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
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_pegawai.value = response
  })
}

const skor = (e: any, i: any) => {

  let listSkor = listSkoringNyeri.value.detail

  listSkor.forEach((element: any) => {
    if (element.descNilai == e.descNilai) {
      input.value.skoringNyeri = e.descNilai
    }
  });
  isAktive.value = i

}
const d_Petugas = ref([]);

const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const d_Perawat = ref([]);

const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}


const handlerRujukanChange = (val: any) => {
  console.log(val);
  if (val === "YA") {

  }
}

const print = async () => {
  H.printBlade(`emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
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


fetchPasien()


const setAutoFill = async () => {
  const fieldsVitalSign = "tinggiBadan,IMT,lingkarPerut,tekananDarah,keadaanumumobgyn,keadaanumum,pernapasan,suhu,nadi,beratBadan,SPO2";
  const fieldsAsesmen = "tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn";

  const fetchData = async (collection, fields) => {
    return await useApi().get(`emr/auto-fill?norec_pd=${props.registrasi.norec_pd}&collection=${collection}&field=${fields}`);
  };

  const parseResponse = (response) => {
    if (!response) return "";

    const fieldsMap = {
      riwayatpenyakit: "Riwayat Penyakit :",
      riwayatpenyakitdahulu: "Riwayat Penyakit Dahulu :",
      riwayatpengobatan: "Riwayat Pengobatan :",
      riwayatpenyakitkeluarga: "Riwayat Penyakit Keluarga :",
      riwayatalergi: "Riwayat Alergi :"
    };

    let data = "";
    Object.entries(fieldsMap).forEach(([key, label]) => {
      if (response[key]) data += `${label}${response[key]}\n\n`;
    });

    return data;
  };

  const setValues = (response) => {
    if (!response) return;

    input.value = {
      ...input.value,
      tekananDarah: response.tekananDarahObgyn || response.tekananDarah,
      nadi: response.nadiObgyn || response.nadi,
      nafas: response.nafasObgyn || response.pernapasan,
      celcius: response.celciusObgyn || response.suhu,
      sao2: response.sao2Obgyn || response.SPO2,
      gcse: response.gcse,
      gcsv: response.gcsv,
      gcsm: response.gcsm,
      kebpilihanallo: response.kebpilihanallo,
      keadaanumum: response.keadaanumumobgyn || response.keadaanumum,
      beratbadanObgyn: response.beratbadanObgyn || response.beratBadan,
      tinggibadanObgyn: response.tinggibadanObgyn || response.tinggiBadan,
      anamnesis: parseResponse(response),
    };
  };

  let response = await fetchData("VitalSign", fieldsVitalSign);
  if (!response) response = await fetchData("AsesmenAwalKeperawatanPasienRawatJalanNurse", fieldsAsesmen);
  if (!response) response = await fetchData("AsesmenAwalKebidananRawatJalanNurse", fieldsAsesmen);
  if (!response) response = await fetchData("AsesmenAwalKeperawatanPasienRawatJalan", fieldsAsesmen);

  setValues(response);
}

const setAutoFill2 = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.CBPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.alamat = props.pasien.alamatlengkap
  input.value.disetujui = props.pasien.namapasien
  input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.kartuIdentitas = props.pasien.noidentitas
}

const d_Ruangan: any = ref([])
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter?.query}&limit=10`
  )
  d_Ruangan.value = response
}

setAutoFill();
setAutoFill2();
fetchRuangan();
</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

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

// .p-fieldset.p-component{
//     border-left: ;
// }

table.assesment {
  border-collapse: collapse;
  width: 100%;
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

// tr:hover {
//     background-color: #f5f5f5;
// }</style>

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
