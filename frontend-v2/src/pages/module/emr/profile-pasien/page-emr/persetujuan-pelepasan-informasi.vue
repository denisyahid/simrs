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
            <h1 style="font-weight: bold;">Yang bertanda tangan dibawah ini :
            </h1>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Nama Lengkap : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nama Lengkap" v-model="input.nama" />
                    </VControl>

                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Jenis Kelamin : </h1>
                </div>
                <div class="columns is-6" style=" margin-top: 1rem">
                  <VField v-for="items in JenisKelamin" :key="items.value" style="padding:0px;">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.jenisKelamin" class="pt-1 pb-1 " :true-value="items.label"
                        :label="items.label" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:1rem">
                  <h1 style="font-weight: bold;"> Tanggal Lahir : </h1>
                </div>
                <div class="column is-6" style=" margin-top: 0.5rem">
                  <VField>
                    <VDatePicker v-model="input.tanggalLahir" mode="dateTime" style="width: 100%" trim-weeks
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
              <div class="column is-12 p-0">
                <div class="is-flex">
                  <div class="column is-2" style="margin-top:0.5rem">
                    <h1 style="font-weight: bold;"> Alamat : </h1>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Alamat" v-model="input.alamat" />
                      </VControl>

                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="column is-12">
              <div class="flex">
                <h1 style="font-weight: bold">
                  Dalam hal ini bertindak sebagai
                </h1>
                <div class="column is-3" style="margin-top:-1rem;">
                  <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="fa:users" fullwidth class="prime-auto ">
                      <AutoComplete v-model="input.hubunganPenjamin" :suggestions="d_Hubungan"
                        @complete="fetchHubungan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
              </div>

            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Nama : </h1>
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
                  <h1 style="font-weight: bold;"> Jenis Kelamin : </h1>
                </div>
                <div class="columns is-6" style=" margin-top: 1rem">
                  <VField v-for="items in JenisKelamin" :key="items.value" style="padding:0px;">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.jenisKelaminPasien" class="pt-1 pb-1 " :true-value="items.label"
                        :label="items.label" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> No. Rekam Medis : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="number" class="input" placeholder="No. Rekam Medis" v-model="input.norm" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:1rem">
                  <h1 style="font-weight: bold;"> Tanggal Lahir : </h1>
                </div>
                <div class="column is-6" style=" margin-top: 0.5rem">
                  <VField>
                    <VDatePicker v-model="input.tanggalLahirPasien" mode="dateTime" style="width: 100%" trim-weeks
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
            <div class="column is-12 p-1">
              <h1 style="font-weight: bold;">Dengan ini saya</h1>
            </div>
            <div class="column is-12 p-1 ml-2">
              <span>1. Memahami informasi yang ada di dalam diri saya, termasuk diagnosa, hasil laboratorium, 
                dan hasil tes diagnostik yang akan di gunakan untuk perawatan medis akan dijamin kerahasiaannya oleh rumah sakit.</span>
            </div>
            <div class="column is-12 p-1">
              <span>2. Memberikan wewenang kepada rumah sakit untuk memberikan informasi tentang rahasia kedokteran
                 saya bila diperlukan untuk memproses klaim asuransi termasuk namun tidak terbatas pada BPJS,
                  asuransi kesehatan lainnya, Jamkesda, perusahaan, dan atau lembaga pemerintah lainnya.  </span>
            </div>
            <div class="column is-12 p-1">
              <span>3. Memberikan wewenang kepada rumah sakit untuk memberikan tentang data dan informasi kesehatan saya kepada keluarga terdekat saya, yaitu </span>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> a. Nama : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nama " v-model="input.namaMenyetujuiA" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Alamat : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Alamat " v-model="input.alamatMenyetujuiA" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Telp : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Telp " v-model="input.telpMenyetujuiA" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Hubungan dengan pasien : </h1>
                </div>
                <div class="column is-6">
                  <VField v-for="items in d_Hubungan" :key="items.value" style="padding:0px;">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.hubunganDenganPasienA" class="pt-1 pb-1 " :true-value="items.label" :label="items.label"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Lain-lain : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Lain-lain " v-model="input.lainMenyetujuiA" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>


            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> b. Nama : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nama " v-model="input.namaMenyetujuiB" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Alamat : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Alamat " v-model="input.alamatMenyetujuiB" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Telp : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Telp " v-model="input.telpMenyetujuiB" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Hubungan dengan pasien : </h1>
                </div>
                <div class="column is-6">
                  <VField v-for="items in d_Hubungan" :key="items.value" style="padding:0px;">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.hubunganDenganPasienB" class="pt-1 pb-1 " :true-value="items.label" :label="items.label"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Lain-lain : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Lain-lain " v-model="input.lainMenyetujuiB" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>


            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> c. Nama : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nama " v-model="input.namaMenyetujuiC" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Alamat : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Alamat " v-model="input.alamatMenyetujuiC" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Telp : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Telp " v-model="input.telpMenyetujuiC" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Hubungan dengan pasien : </h1>
                </div>
                <div class="column is-6">
                  <VField v-for="items in d_Hubungan" :key="items.value" style="padding:0px;">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.hubunganDenganPasienC" class="pt-1 pb-1 " :true-value="items.label" :label="items.label"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Lain-lain : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Lain-lain " v-model="input.lainMenyetujuiC" />
                    </VControl>
                  </VField>
                </div>
              </div>
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
                  <h1 class="p-0" style="font-weight: bold;">Saksi I</h1>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.saksi" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Perawat..." class="mt-2"
                        @item-select="setTandaTangan($event)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4 is-offset-2">
                  <h1 class="p-0" style="font-weight: bold;">Yang membuat pernyataan</h1>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Yang membuat pernyataan"
                        v-model="input.yangMembuatPernyataan" />
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
const d_KelompokPasien: any = ref([])
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
const JenisKelamin: any = ref([
  { label: 'Laki - Laki', value: 'Laki - Laki' },
  { label: 'Perempuan', value: 'Perempuan' }
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
const fetchHubungan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=${filter.query}&limit=10`)
  d_Hubungan.value = response
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
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.dokterRawat = props.registrasi.dokter
  input.value.dirawatDiRuang = props.registrasi.namaruangan
  input.value.kelas = props.registrasi.namakelas
  input.value.kelompokPasien = props.registrasi.kelompokpasien
  input.value.tglPembuatan = new Date()
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
}
</style>
