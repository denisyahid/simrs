<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr
              :NOREC_EMRPASIEN="NOREC_EMRPASIEN"
              :COLLECTION="COLLECTION"
              :isLoading="isLoading"
              @simpan="simpan"
              @kembaliKeun="kembaliKeun"
            ></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div
      class="column is-12 buttons mb-0 mt-0"
      style="margin: 10px; vertical-align: middle"
    >
      <VButton
        type="button"
        rounded
        outlined
        color="primary"
        raised
        icon="feather:folder"
        isLoading="false"
        @click="pilihTemplateFix(index)"
      >
        Pilih Template
      </VButton>
      <VButton
        type="button"
        rounded
        outlined
        color="info"
        raised
        icon="feather:file-text"
        :loading="isLoading"
        @click="pilihTemplate(index)"
      >
        Pilih Riwayat
      </VButton>
    </div>

    <hr class="m-0" />

    <div class="column is-12">
      <h1>
        Nama Template&emsp;&emsp;
        <span style="color: red">**Hanya diisi jika ingin membuat template</span>
      </h1>
      <VField>
        <VControl>
          <VTextarea v-model="input.namatemplate" rows="1"> </VTextarea>
        </VControl>
      </VField>
    </div>

    <VModal
      :open="showModalTemplateFix"
      title="Template"
      :noclose="true"
      size="medium"
      actions="right"
      @close="showModalTemplateFix = false"
    >
      <template #content>
        <form class="modal-form">
          <div class="column is-12 pt-0 pb-0">
            <span style="font-size: 9pt; font-weight: bold">List Template</span>
            <div style="overflow-y: auto" class="mt-1">
              <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                <thead>
                  <tr>
                    <td class="tg-0lax text-center" width="15%">#</td>
                    <td class="tg-0lax text-center" width="15%">No</td>
                    <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                    <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                    <td class="tg-0lax text-center" width="50%">Nama Template</td>
                  </tr>
                </thead>
                <tbody v-for="resep in listTemplateFix">
                  <tr>
                    <td style="width: 15%; text-align: center">
                      <VIconButton
                        type="button"
                        raised
                        circle
                        icon="fas fa-plus"
                        @click="addTemplate(resep)"
                        color="info"
                        v-tooltip-prime.top="'Pilih'"
                      >
                      </VIconButton>
                    </td>
                    <td style="width: 15%; text-align: center">
                      <span class="mb-2">{{ resep.no }}</span
                      ><br />
                    </td>
                    <td style="width: 20%; text-align: center">
                      <span class="mb-2">{{ resep.created_at }}</span
                      ><br />
                    </td>
                    <td style="width: 20%; text-align: center">
                      <span class="mb-2">{{ resep.registrasi.namaruangan }}</span
                      ><br />
                    </td>
                    <td style="width: 50%; text-align: center">
                      <span class="mb-2">{{ resep.namatemplate }}</span
                      ><br />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </form>
      </template>
    </VModal>
    <VModal
      :open="showModalTemplate"
      title="Riwayat"
      :noclose="true"
      size="large"
      actions="right"
      @close="showModalTemplate = false"
    >
      <template #content>
        <form class="modal-form">
          <div class="column is-12 pt-0 pb-0">
            <span style="font-size: 9pt; font-weight: bold">List Riwayat</span>
            <div style="overflow-y: auto" class="mt-1">
              <table class="tg table-tg" v-if="listTemplate.length > 0">
                <thead>
                  <tr>
                    <td class="tg-0lax text-center" width="5%">#</td>
                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                    <td class="tg-0lax text-center" width="20%">Section</td>
                  </tr>
                </thead>
                <tbody v-for="resep in listTemplate">
                  <tr>
                    <td style="width: 5%; text-align: center">
                      <VIconButton
                        type="button"
                        raised
                        circle
                        icon="fas fa-plus"
                        @click="addTemplate(resep)"
                        color="info"
                        v-tooltip-prime.top="'Pilih'"
                      >
                      </VIconButton>
                    </td>
                    <td style="width: 25%; text-align: center">
                      <span class="mb-2">{{ resep.created_at }}</span
                      ><br />
                    </td>
                    <td style="width: 25%; text-align: center">
                      <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span
                      ><br />
                    </td>
                    <td style="width: 25%; text-align: center">
                      <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span
                      ><br />
                    </td>
                    <td style="width: 20%; text-align: center">
                      <span class="mb-2">{{ resep.pasien.nocm }}</span
                      ><br />
                    </td>
                    <td style="width: 20%; text-align: center">
                      <span class="mb-2">{{ resep.dpjpUtama }}</span
                      ><br />
                    </td>
                    <td style="width: 25%; text-align: center">
                      <span class="mb-2">{{ resep.registrasi.namaruangan }}</span
                      ><br />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </form>
      </template>
    </VModal>
    <VCard>
      <div class="column">
        <h1>Yang bertanda tangan dibawah ini:</h1>
        <div class="column mr-3 is-multiline columns">
          <div class="column p-0 ml-3 is-2">
            <p>Nama Operator:</p>
          </div>
          <div class="column p-0 ml-3">
            <VField>
              <VControl>
                <AutoComplete
                  v-model="input.petugas"
                  :suggestions="d_Petugas"
                  @complete="fetchPegawai($event)"
                  :optionLabel="'label'"
                  :dropdown="true"
                  :minLength="3"
                  :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'"
                  :field="'label'"
                  class="mt-2"
                />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column mr-3 is-multiline columns">
          <div class="column p-0 ml-3 is-2">
            <p>Bagian/ SMF:</p>
          </div>
          <div class="column p-0 ml-3">
            <VField>
              <VControl>
                <VInput v-model="input.bagian" class="input" type="text" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 is-flex">
        <div class="column is-12">
          <h1>Menerangkan dengan sebenarnya bahwa penderita:</h1>
        </div>
      </div>

      <div class="column is-12 is-flex ml-5">
        <div class="column is-2">
          <h1 style="font-weight: bold">NAMA PASIEN:</h1>
        </div>
        <div class="column is-10">
          <VField>
            <VControl>
              <VInput v-model="input.namaPasien" class="input" type="text" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column is-12 is-flex ml-5">
        <div class="column is-2">
          <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
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
                    <VInput :value="inputValue" placeholder="Tanggal" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>
      </div>

      <div class="column is-12 is-flex ml-5">
        <div class="column is-2">
          <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
        </div>
        <div class="column is-10" style="display: flex">
          <VField>
            <VControl raw subcontrol>
              <VCheckbox
                v-model="input.jeniskelamin"
                class="pt-1 pb-1"
                true-value="Laki-laki"
                label="Laki-laki"
                color="primary"
                circle
              />
            </VControl>
          </VField>
          <VField>
            <VControl raw subcontrol>
              <VCheckbox
                v-model="input.jeniskelamin"
                class="pt-1 pb-1"
                true-value="Perempuan"
                label="Perempuan"
                color="primary"
                circle
              />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column is-12 is-flex ml-5">
        <div class="column is-2">
          <h1 style="font-weight: bold">STATUS:</h1>
        </div>
        <div class="column is-10" style="display: flex">
          <VField>
            <VControl raw subcontrol>
              <VCheckbox
                v-model="input.status"
                class="pt-1 pb-1"
                true-value="P"
                label="P"
                color="primary"
                circle
              />
            </VControl>
          </VField>
          <VField>
            <VControl raw subcontrol>
              <VCheckbox
                v-model="input.status"
                class="pt-1 pb-1"
                true-value="I"
                label="I"
                color="primary"
                circle
              />
            </VControl>
          </VField>
          <VField>
            <VControl raw subcontrol>
              <VCheckbox
                v-model="input.status"
                class="pt-1 pb-1"
                true-value="I"
                label="A1"
                color="primary"
                circle
              />
            </VControl>
          </VField>
          <VField>
            <VControl raw subcontrol>
              <VCheckbox
                v-model="input.status"
                class="pt-1 pb-1"
                true-value="A2"
                label="A2"
                color="primary"
                circle
              />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column is-12 is-flex ml-5">
        <div class="column is-2">
          <h1 style="font-weight: bold">No. Rekam Medis:</h1>
        </div>
        <div class="column is-10">
          <VField>
            <VControl>
              <VInput
                type="text"
                class="input"
                placeholder="No. Rekam Medis"
                v-model="input.norm"
              />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column is-12 is-flex ml-5">
        <div class="column is-2">
          <h1 style="font-weight: bold">Diagnosa:</h1>
        </div>
        <div class="column is-10">
          <VField>
            <VControl>
              <VTextarea v-model="input.diagnosa" class="input" type="text" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column is-12 is-flex">
        <div class="column is-12">
          <h1>Memang benar dilakukan tindakan operasi :</h1>
        </div>
      </div>

      <div class="column is-12 is-flex ml-5">
        <div class="column is-2">
          <h1 style="font-weight: bold">NAMA TINDAKAN:</h1>
        </div>
        <div class="column is-10">
          <VField>
            <VControl>
              <VTextarea v-model="input.namaTindakan" class="input" type="text" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column is-12 is-flex ml-5">
        <div class="column is-2">
          <h1 style="font-weight: bold">JENIS ANESTESI:</h1>
        </div>
        <div class="column is-10" style="display: flex">
          <VField>
            <VControl raw subcontrol>
              <VCheckbox
                v-model="input.jenisAnestesi"
                class="pt-1 pb-1"
                true-value="Lokal"
                label="Lokal"
                color="primary"
                circle
              />
            </VControl>
          </VField>
          <VField>
            <VControl raw subcontrol>
              <VCheckbox
                v-model="input.jenisAnestesi"
                class="pt-1 pb-1"
                true-value="Umum"
                label="Umum"
                color="primary"
                circle
              />
            </VControl>
          </VField>
          <VField>
            <VControl raw subcontrol>
              <VCheckbox
                v-model="input.jenisAnestesi"
                class="pt-1 pb-1"
                true-value="Lumbal"
                label="Lumbal"
                color="primary"
                circle
              />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column is-12 is-flex ml-5">
        <div class="column is-2">
          <h1 style="font-weight: bold">TANGGAL:</h1>
        </div>
        <div class="column is-10">
          <VField>
            <VDatePicker v-model="input.tanggal" mode="date" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput
                      :value="inputValue"
                      placeholder="Tanggal"
                      v-on="inputEvents"
                    />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>
      </div>

      <div class="column mr-3 is-multiline columns">
        <div class="column p-0 ml-3 is-2">
          <p>Dokter:</p>
        </div>
        <div class="column p-0 ml-3">
          <VField>
            <VControl>
              <AutoComplete
                v-model="input.DDDokter"
                :suggestions="d_Dokter"
                @complete="fetchDokter($event)"
                :optionLabel="'label'"
                :dropdown="true"
                :minLength="3"
                :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'"
                :field="'label'"
                class="mt-2"
              />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column is-4" style="text-align: center">
        <h1 style="font-weight: bold">Tanda Tangan Pasien</h1>
        <TandaTangan :elemenID="'ttdPasien'" :width="'150'" :height="'150'" class="dek" />
      </div>
      <div class="column is-4" style="text-align: center">
        <h1 style="font-weight: bold">BPJS</h1>
        <TandaTangan :elemenID="'BPJS'" :width="'150'" :height="'150'" class="dek" />
        <VInput v-model="input.NamaBPJS" class="input mt-2" type="text" />
      </div>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
  h,
  reactive,
  ref,
  computed,
  watch,
  onBeforeMount,
  onMounted,
  watchEffect,
} from 'vue'
import { useRoute, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import * as EMR from '../page-emr-plugins/lembar-registrasi-pacu-jantung'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset'
import ImgDraw from '../page-emr-plugins/img-draw.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let Gejala = EMR.Gejala()
let EKG = EMR.EKG()
let Etiologi = EMR.Etiologi()
let Single = EMR.Single()
let Double = EMR.Double()
let LeadAntrium = EMR.LeadAntrium()
let LeadVerinteken = EMR.LeadVerintekel()
let indikasiPenggantian = EMR.IndikasiPenggantian()
let indikasiPenggantianLead = EMR.indikasiPengngantianLead()
let indikasiPenggantianCatatan = EMR.IndikasiPenggantianCatatan()
let keteranganlainnya = EMR.KeteranganLainnya()
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
const isAktive = ref()

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const route = useRoute()
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const d_Perawat: any = ref([])
const d_Ruangan: any = ref([])
const d_produk = ref([])
const d_ObatRS = ref([])
const d_Dokter = ref([])
const d_Petugas = ref([])
const dataTTD: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const COLLECTION: any = ref('SuratBuktiPemintaanOperasiJantung') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const loadRiwayat = async () => {
  let response = await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  )
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
    H.tandaTangan().set('ttdPasien', dataTTD.value.ttdPasien)
    H.tandaTangan().set('BPJS', dataTTD.value.BPJS)
  }
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

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}
console.log

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object['ttdPasien'] = H.tandaTangan().get('ttdPasien')
  object['BPJS'] = H.tandaTangan().get('BPJS')
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
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
      input.value.id = response.id
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const print = async () => {
  H.printBlade(
    `emr/formulir-catatan-pemberian-obat-kemoterapi?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  )
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.tanggalKunjunganPasien = new Date()
  input.value.ruangan = props.registrasi.namaruangan
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

const fetchPegawai = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Petugas.value = response
    })
}

const addTemplate = (response: any) => {
  console.log(response)

  const excludedFields = [
    'namatemplate',
    '_id',
    'norm',
    'nama',
    'jenisKelamin',
    'alamat',
    'namaPasien',
    'jenisKelaminPasien',
    'norm',
    'tanggalLahirPasien',
    'alamatPasien',
  ]

  input.value = Object.keys(response).reduce((acc, key) => {
    if (!excludedFields.includes(key)) {
      acc[key] = response[key]
    }
    return acc
  }, {})

  input.value['id'] = ''
  showModalTemplateFix.value = false
  showModalTemplate.value = false
  H.alert('success', 'Berhasil ditambahkan')
  setAutoFill()
}

const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi()
    .get(
      `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`
    )
    .then((responselast: any) => {
      isLoading.value = false
      if (responselast.length) {
        listTemplate.value = responselast
        showModalTemplate.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi()
    .get(`/emr/get-emr-template?collection=${COLLECTION.value}`)
    .then((responselast: any) => {
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

onBeforeMount(async () => {
  try {
    await setView()
    await loadRiwayat()
    await setAutoFill()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error)
  }
})
onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error)
  }
  next()
})
</script>
