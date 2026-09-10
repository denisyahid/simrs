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
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-12">
            <h1 style="font-weight: bold;" class="is-pulled-right"> 006.16/FORM/7/RMIK/2022/Rev. 00</h1>
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
                      <VInput type="text" class="input" placeholder="Nama " v-model="input.nama" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Nomor Identitas : </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nomor Identitas " v-model="input.nik" />
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
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Hubungan dengan pasien "
                        v-model="input.hubunganDgPasien" />
                    </VControl>
                  </VField>
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
                  <span> Nomor Rekam Medis : </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder=" Nomor Rekam Medis" v-model="input.nocm" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>


            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span>Jaminan perawatan : </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Jaminan perawatan"
                        v-model="input.jaminanPerawatan" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>
                Sesuai dengan Keputusan Menteri Kesehatan Nomor 4718 Petunjuk Tenis Klaim Penggantian Biaya Pelayanan
                Pasien Corona Virus Disease 2019 (Covid-19) bagi Rumah Sakit Penyelenggara Pelayanan Corona Virus Disease
                2019 (Covid-19), maka selama saya/keluarga saya dirawat di Rumah Sakit ini untuk kasus Covid-19 dan tidak
                dipungut biaya pelayanan kesehatan yang telah diberikan oleh pihak rumah sakit.

              </span>
            </div>


            <div class="column is-4">
              <h1 style="font-weight: bold;"> Tanggal </h1>
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
                      <AutoComplete v-model="input.yangMenjelaskan" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Yang Menjelaskan..." class="mt-2" @item-select="setTandaTangan($event)" />
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
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

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
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const d_Hubungan: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
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
// List Pilihan
const d_Kelas: any = ref([
  { label: 'KELAS 1', value: 'KELAS 1' },
  { label: 'KELAS 2', value: 'KELAS 2' },
  { label: 'KELAS 3', value: 'KELAS 3' }
])
// List Pilihan
const d_Keterangan: any = ref([
  { label: 'Atas Permintaan Sendiri', value: 'Atas Permintaan Sendiri' },
  { label: 'Kelas Sesuai Jaminan Penuh', value: 'Kelas Sesuai Jaminan Penuh' }
])
const dokterJabatan: any = ref([
  { label: 'Umum', value: 'Umum' },
  { label: 'Specialis', value: 'Specialis' },
  { label: 'Subspecialis', value: 'Subspecialis' }
])
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
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
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Dokter.value = response
}
const fetchDropdown = async () => {
  // const response = await useApi().get(
  //   `/emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=&limit=10`)
  // d_KelompokPasien.value = response
  const response = await useApi().get(
    `/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=&limit=10`)
  d_Hubungan.value = response
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

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

  input.value.namaPasien = props.pasien.namapasien
  input.value.jenisKelaminPasien = props.pasien.jeniskelamin
  input.value.noTelp = props.pasien.nohp
  input.value.nocm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.dokterRawat = props.registrasi.dokter
  input.value.dirawatDiRuang = props.registrasi.namaruangan
  input.value.kelas = props.registrasi.namakelas
  input.value.kelompokPasien = props.registrasi.kelompokpasien
  input.value.tglPembuatan = new Date()
  input.value.tglDirawat = props.registrasi.tglregistrasi

}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}


setView()
setAutoFill()
fetchDropdown()
loadRiwayat()
</script>
<style>
#signature {
  border: double 3px transparent;
  border-radius: 5px;
  background-image: linear-gradient(white, white),
    radial-gradient(circle at top left, #4bc5e8, #9f6274);
  background-origin: border-box;
  background-clip: content-box, border-box;
}

.container {
  width: "100%";
  padding: 8px 16px;
}

.buttons {
  display: flex;
  gap: 8px;
  justify-content: center;
  margin-top: 8px;
}</style>
