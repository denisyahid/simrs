<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                  :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-12">
              <h1 class="mb-3 emr">Nama Template&emsp;&emsp;**Hanya diisi jika ingin membuat template</h1>
              <VField>
                  <VControl>
                      <VTextarea v-model="input.namatemplate" rows="1">
                      </VTextarea>
                  </VControl>
              </VField>
          </div>
          <div class="column is-6">
              <h1 class="mb-3 emr">Pilih Template</h1>
              <div class="columns is-multiline">
                  <div class="column is-9">
                  <VField>
                      <VControl>
                          <input v-model="input.template" class="input" disabled/>
                      </VControl>
                  </VField>
                  </div>
                  <div class="column is-3">
                  <VIconButton type="button" raised circle icon="lnir lnir-checkmark-circle" @click="pilihTemplateFix(index)"
                      color="success" v-tooltip-prime.top="'Template'">
                  </VIconButton>
                  </div>
              </div>
          </div>
          <div class="column is-6">
              <h1 class="mb-3 emr">Pilih Riwayat</h1>
              <div class="columns is-multiline">
                  <div class="column is-9">
                  <VField>
                      <VControl>
                          <input v-model="input.template" class="input" disabled/>
                      </VControl>
                  </VField>
                  </div>
                  <div class="column is-3">
                  <VIconButton type="button" raised circle icon="lnir lnir-checkmark-circle" @click="pilihTemplate(index)"
                      color="success" v-tooltip-prime.top="'Riwayat'">
                  </VIconButton>
                  </div>
              </div>
          </div>
          <div class="column is-2" style="margin-top: 100px;">
            <h1 style="font-weight: bold">NAMA PASIEN</h1>
          </div>
          <div class="column is-10" style="margin-top: 100px;">
            <VField>
              <VControl>
                <VInput v-model="input.namaPasien" class="input" type="text" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker
                v-model="input.tanggalLahirPasien"
                mode="date"
                trim-weeks
                :max-date="new Date()"
              >
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" disabled />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">JENIS KELAMIN</h1>
          </div>
          <div class="column is-10" style="display: flex">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.jeniskelamin"
                  class="pt-1 pb-1"
                  :true-value="items.label"
                  :label="items.label"
                  color="primary"
                  circle
                  disabled
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">No. Rekam Medis</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="No. Rekam Medis"
                  v-model="input.norm"
                  disabled
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">TANGGAL Kunjungan</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker
                v-model="input.tanggalKunjunganPasien"
                mode="dateTime"
                style="width: 100%"
                trim-weeks
                :max-date="new Date()"
                disabled
              >
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" disabled />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-6">
              <h1 style="font-weight: bold">Pesawat</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl>
                  <Multiselect
                    v-model="input.pesawat"
                    placeholder="--Pilih--"
                    label="label"
                    :options="pesawat"
                    :searchable="true"
                    track-by="label"
                    mode="single"
                    autocomplete="off"
                  >
                  </Multiselect>
                </VControl>
              </VField>
            </div>

            <div class="column is-6">
              <h1 style="font-weight: bold">Energy</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl>
                  <Multiselect
                    v-model="input.energi"
                    placeholder="--Pilih--"
                    label="label"
                    :options="energy"
                    :searchable="true"
                    track-by="label"
                    mode="single"
                    autocomplete="off"
                  >
                  </Multiselect>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="columns is-multiline p-3">
            <div class="column is-4" v-for="(data, i) in keperluanFisika">
              <div class="columns is-multiline">
                <div class="column is-12" style="margin-top: 0.5rem">
                  <span> {{ data.label }} : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        :placeholder="data.label"
                        v-model="input[data.value]"
                      />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>{{ data.addons }} </VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-4">
            <h1 style="font-weight: bold">Paraf QA</h1>
            <VField class="is-autocomplete-select">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.parafQA" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" />
              </VControl>
            </VField>
          </div>
        </div>
      </VCard>
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
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                      color="info" v-tooltip-prime.top="'Pilih'">
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
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                      color="info" v-tooltip-prime.top="'Pilih'">
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
import { h, reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import MultiSelect from 'primevue/multiselect'
import * as EMR from '../page-emr-plugins/fisika-radioterapi'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string

let JenisKelamin = ref(EMR.JenisKelamin())
let energy = ref(EMR.energy())
let keperluanFisika = ref(EMR.keperluanFisika())
let parafQa = ref(EMR.parafQa())
let pesawat = ref(EMR.pesawat())

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
const RiwayatPsikososial: any = ref([
  { label: 'Baik', value: 'Baik' },
  { label: 'Tidak Baik', value: 'Tidak Baik' },
])

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const dataTTD: any = ref([])
const d_Perawat: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('FormulirFisika') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date(),
})
const listTemplate:any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix:any = ref([])
const showModalTemplateFix: any = ref(false)





const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      if (response.length > 0) {
        isDisabled.value = false
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
      } else {
        isDisabled.value = true
      }
    })
  H.tandaTangan().set('TTDperawat1', dataTTD.value.TTDperawat)
}

const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Perawat.value = response
    })
}
const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then(response => {
      d_Dokter.value = response;
    });
};
const getDataExist = async () => {
  await useApi()
    .get(
      `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response) => {
      // input.value.beratBadan = response.beratBadan
      // input.value.tinggiBadan = response.tinggiBadan
      // input.value.IMT = response.IMT
      console.log()
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object.riwayatPsikososial = RiwayatPsikososial.value
  object['TTDperawat1'] = H.tandaTangan().get('TTDperawat1')
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const simpanTemplate = () => {
  if(!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
let ID = input.id ? input.id : ''

let object: any = {}

object = input.value
object.nocm = props.pasien.nocm

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
        for(var x = 0; x < responselast.length; x++){
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

const print = async () => {
  H.printBlade(
    `emr/cetak-formulir-permintaan-konseling-gizi?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  )
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi
  input.value.tglPembuatan = new Date()

  isLoadingVitalSign.value = true
  await useApi()
    .get(
      'emr/auto-fill?norec_pd=' +
        props.registrasi.norec_pd +
        '&collection=VitalSign' +
        '&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi'
    )
    .then((response) => {
      if (response != null) {
        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
        input.value.IMT = response.IMT
        input.value.lingkarPerut = response.lingkarPerut
        input.value.tekananDarah = response.tekananDarah
        input.value.pernapasan = response.pernapasan
        input.value.suhu = response.suhu
        input.value.nadi = response.nadi
      }
      isLoadingVitalSign.value = false
    })
}

watch(
  () => [
    input.value.turunBeratBadan,
    input.value.tidakAdaTurunBeratBadan,
    input.value.asupanMakan,
  ],
  () => {
    let poin1 = input.value.turunBeratBadan ? parseInt(input.value.turunBeratBadan) : 0
    let poin2 = input.value.tidakAdaTurunBeratBadan
      ? parseInt(input.value.tidakAdaTurunBeratBadan)
      : 0
    let poin3 = input.value.asupanMakan ? parseInt(input.value.asupanMakan) : 0

    const total = poin1 + poin2 + poin3
    input.value.totalSkor = total
  }
)

setView()
getDataExist()
loadRiwayat()
setAutoFill()
fetchPerawat({ query: '' })
fetchDokter({ query: '' })
</script>
