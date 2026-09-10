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
      <div class="columns is-multiline">
        <div class="column is-12">
          <h1 style="font-weight: bold;" class="text-center mb-5">KASUS TRAUMA NON KECELAKAAN LALU LINTAS YANG DIJAMIN
            BPJS
            KESEHATAN</h1>
        </div>
        <div class="column is-12">
          <h1 style="font-weight: bold;">Yang bertanda tangan di bawah ini
          </h1>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nama : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Nama " v-model="input.namaPenanggungJawab" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Alamat : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Alamat " v-model="input.alamatPenanggungJawab" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Hubungan dengan pasien : </span>
              </div>

              <div class="column is-6">
                <div class="column is-12 p-0">
                  <div class="columns is-multiline">
                    <div class="column is-12" v-if="d_Hubungan.length == 0">
                      <VPlaceloadText :lines="1" />
                    </div>
                    <div v-for="items in d_Hubungan" :key="items.value" class="column is-4">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.hubunganDenganPasien" class="pt-1 pb-1 " :true-value="items.label"
                            :label="items.label" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <h1 style="font-weight: bold;">
            Adalah penanggung jawab pasien atas nama
          </h1>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nama Pasien: </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Nama " v-model="input.namaPasien" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> No Kartu BPJS : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder=" Nomor Kartu BPJS" v-model="input.nobpjs" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> No Ruangan : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nomor Rekam Medis : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder=" Nomor Rekam Medis" v-model="input.noRekamMedis" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Tanggal Mulai Dirawat : </span>
              </div>
              <div class="column is-6">
                <VField class="mt-2">
                  <VDatePicker v-model="input.tglDirawat" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
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
          </div>
          <div class="column is-12 p-0 mt-1 ml-2">
            <span>
              Dirawat di RS Transmedic diakibatkan oleh kejadiaan bukan kecelakaan lalu lintas dengan kronologis (urutan
              kejadian) sebagai berikut :
            </span>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Hari : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <Multiselect mode="single" v-model="input.hari" :options="d_Hari" placeholder="Hari Kejadian"
                      :searchable="true" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Tanggal dan Waktu Kejadian : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VDatePicker v-model="input.tanggalWaktuKejadian" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal Kejadian" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Lokasi Kejadian : </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VTextarea rows="2" placeholder="Lokasi Kejadian..." v-model="input.lokasi"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-8">
                <VField label="Detail Kejadian">
                  <VTextarea rows="5" placeholder="Detail Kejadian..." v-model="input.detail"></VTextarea>
                </VField>
              </div>
            </div>
          </div>
          <div class="column mt-3 is-4">
            <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
            <VField>
              <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
                :max-date="new Date()">
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
          <div class="column is-12">
            <div class="columns is-multiline">

              <div class="column is-6">
                <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
              </div>
              <div class="column is-6 ">
                <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
              </div>
              <div class="column is-4">
                <h1 class="p-0" style="font-weight: bold;">Yang Menjelaskan </h1>
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.yangMenjelaskan" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Yang Menjelaskan..." class="mt-2"
                      @item-select="setTandaTangan($event, 'signature_1')" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 is-offset-2">
                <h1 class="p-0" style="font-weight: bold;">Pasien/Penanggung Jawab Pasien</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Pasien/Penanggung Jawab Pasien"
                      v-model="input.pasienPenanggungJawab" />
                  </VControl>
                </VField>
              </div>
            </div>
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
const d_Ruangan: any = ref([])
const d_Hubungan: any = ref([])
const d_Dokter: any = ref([])
const d_Hari: any = ref([
  { value: 'Senin', label: 'Senin' },
  { value: 'Selasa', label: 'Selasa' },
  { value: 'Rabu', label: 'Rabu' },
  { value: 'Kamis', label: 'Kamis' },
  { value: 'Jumat', label: 'Jumat' },
  { value: 'Sabtu', label: 'Sabtu' },
  { value: 'Minggu', label: 'Minggu' },
]);
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
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
        }
        if (response[0].tandaTanganPasien) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPasien)
        }
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
  object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
  object.tandaTanganPasien = H.tandaTangan().get("signature_2")
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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchHubungan = async () => {
  const response = await useApi().get(
    `/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga`)
  d_Hubungan.value = response
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const setTandaTangan = async (e: any, i: any) => {
  // await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
  //   if (element) {
  //     H.tandaTangan().set("signature_" + i, element.ttd)
  //   } else {
  //     H.tandaTangan().set("signature_" + i, '')
  //   }
  // })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

  input.value.namaPasien = props.pasien.namapasien
  input.value.noRekamMedis = props.pasien.nocm
  input.value.nobpjs = props.pasien.nobpjs
  input.value.tglPembuatan = new Date()
  input.value.tanggalWaktuKejadian = new Date()
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.tglDirawat = props.registrasi.tglregistrasi
}
console.log(JSON.stringify(props.pasien))
setView()
setAutoFill()
loadRiwayat()
fetchHubungan();
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
