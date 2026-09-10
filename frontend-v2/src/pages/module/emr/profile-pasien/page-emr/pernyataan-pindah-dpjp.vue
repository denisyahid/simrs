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
      <div class="column pb-0">
        <h1 style="font-weight:bold">Yang bertanda tangan dibawah ini</h1>
        <div class="column is-6">
          <span class="label-ppd">Nama</span>
          <VField class="mt-3">
            <VControl>
              <VInput type="text" class="input" v-model="input.namaPenangungJwb" />
            </VControl>
          </VField>
        </div>
        <div class="columns is-multiline pt-3 pl-3 pr-3">
          <div class="column is-4 pb-0">
            <span class="label-ppd">Jenis Kelamin</span>
            <div class="columns is-multiline pt-5 pl-5 pr-5">
              <div class="column p-0">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKlmPenangungJwb" class="p-0" true-value="Laki-laki" label="Laki-laki"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column p-0">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKlmPenangungJwb" class="p-0" true-value="Perempuan" label="Perempuan"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-3 pb-0">
            <span class="label-ptd">Tanggal Lahir</span>
            <VDatePicker class="p-3 pb-0" v-model="input.tglLahirPenangungJwb" color="green" trim-weeks mode="date"
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
        <div class="column is-7 pt-0">
          <span class="label-ppd">Alamat</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="input.alamat" rows="1" placeholder="Alamat ...">
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </div>
      <hr>

      <div class="column pb-0 pt-0">
        <h1 style="font-weight:bold">Dalam hal ini bertindak sebagai diri sendiri/suami/istri/ayah/ibu/wali
          penanggung jawab dari pasien</h1>
        <div class="column is-6">
          <span class="label-ppd">Nama</span>
          <VField class="mt-3">
            <VControl>
              <VInput type="text" class="input" v-model="input.namaPasien" />
            </VControl>
          </VField>
        </div>
        <div class="columns is-multiline pt-3 pl-3 pr-3">
          <div class="column is-4 pb-0">
            <span class="label-ppd">Jenis Kelamin</span>
            <div class="columns is-multiline pt-5 pl-5 pr-5">
              <div class="column p-0">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKlmPasien" class="p-0" true-value="Laki-laki" label="Laki-laki"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column p-0">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKlmPasien" class="p-0" true-value="Perempuan" label="Perempuan"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-3 pb-0">
            <span class="label-ppd">No Rekam Medis</span>
            <VField class="mt-3">
              <VControl>
                <VInput type="text" class="input" v-model="input.norm" />
              </VControl>
            </VField>
          </div>

          <div class="column is-3 pb-0">
            <span class="label-ppd">Tanggal Lahir</span>
            <VDatePicker class="p-3 pb-0" v-model="input.tglLahirPasien" color="green" trim-weeks mode="date"
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
        <div class="columns is-multiline pt-3 pl-3 pr-3">
          <div class="column is-6">
            <span class="label-ppd">Dirawat Diruangan</span>
            <VField class="mt-3">
              <VControl>
                <VInput type="text" class="input" v-model="input.ruangRawat" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <span class="label-ppd">Kelas</span>
            <VField class="mt-3">
              <VControl>
                <VInput type="text" class="input" v-model="input.kelas" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <hr>

      <div class="column pt-0">
        <h1 style="font-weight:bold">Memohon ketersediaan dokter untuk melimpahkan tanggung jawab perawatan dan
          pengobatan atas pasien tersebut diatas dari</h1>
        <div class="column is-4">
          <span class="label-ppd">Dokter</span>
          <VField class="mt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.pegawaiDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Cari Dokter..." class="mt-2" />
            </VControl>
          </VField>
        </div>
        <div class="column">
          <span class="label-ppd">Sebagai Dokter</span>
          <div class="columns is-multiline pt-3">
            <div class="column is-4">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.umum" class="p-0" true-value="Umum" label="Umum" color="primary" circle />
                </VControl>
              </VField>
              <VField class="mt-3">
                <VControl>
                  <VInput type="text" class="input" v-model="input.ketDokterUmum" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.spesialis" class="p-0" true-value="Spesialis" label="Spesialis"
                    color="primary" circle />
                </VControl>
              </VField>
              <VField class="mt-3">
                <VControl>
                  <VInput type="text" class="input" v-model="input.ketDokterSpesialis" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.subspesialis" class="p-0" true-value="Subspesialis" label="Subspesialis"
                    color="primary" circle />
                </VControl>
              </VField>
              <VField class="mt-3">
                <VControl>
                  <VInput type="text" class="input" v-model="input.ketDokterSubspesialis" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column">
          <span class="label-ppd">Ke</span>
        </div>
        <div class="column is-4">
          <span class="label-ppd">Dokter</span>
          <VField class="mt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.dokterTujuan" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Cari Dokter..." class="mt-2" />
            </VControl>
          </VField>
        </div>
        <div class="column">
          <span class="label-ppd">Sebagai Dokter</span>
          <div class="columns is-multiline pt-3">
            <div class="column is-4">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.tujuanUmum" class="p-0" true-value="Umum" label="Umum" color="primary"
                    circle />
                </VControl>
              </VField>
              <VField class="mt-3">
                <VControl>
                  <VInput type="text" class="input" v-model="input.ketTujuanDokterUmum" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.tujuanSpesialis" class="p-0" true-value="Spesialis" label="Spesialis"
                    color="primary" circle />
                </VControl>
              </VField>
              <VField class="mt-3">
                <VControl>
                  <VInput type="text" class="input" v-model="input.ketTujuanDokterSpesialis" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.tujuanSubspesialis" class="p-0" true-value="Subspesialis" label="Subspesialis"
                    color="primary" circle />
                </VControl>
              </VField>
              <VField class="mt-3">
                <VControl>
                  <VInput type="text" class="input" v-model="input.ketTujuanDokterSubspesialis" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column">
        <p style="font-weight:bold">Atas segala perhatian, perawat dan pengobatan yang telah diberikan kami
          sampaikan banyak terimakasih</p>
        <div class="columns is-multiline pt-5" style="justify-content: space-around;">
          <div class="column is-3" style="text-align:center">
            <TandaTangan :elemenID="'signaturePetugas'" :width="'180'" :height="'180'" class="dek" />
            <div class="column p-0 mt-5" style="text-align: left;">
              <span class="label-ppd">Petugas / Perawat</span>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.petugas" :suggestions="d_Petugas" @complete="fetchPetugas($event)"  @item-select="setTandaTanganPerawat($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Petugas..." class="mt-2" />
              </VControl>
            </div>
          </div>
          <div class="column is-3" style="text-align:center">
            <TandaTangan :elemenID="'signaturePemberiPern'" :width="'180'" :height="'180'" class="dek" />
            <div class="column p-0 mt-5" style="text-align: left;">
              <span class="label-ptd">Yang Memberi Pernyataan</span>
              <VField class="pt-2">
                <VControl>
                  <VInput type="text" class="input" v-model="input.pemberiPernyataan" />
                </VControl>
              </VField>
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
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
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
const d_Dokter: any = ref([])
const d_Petugas: any = ref([])
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
        input.value = response[0]
        if (response[0].ttdPetugas) {
          H.tandaTangan().set("signaturePetugas", response[0].ttdPetugas)
        }
        if (response[0].ttdPemberiPern) {
          H.tandaTangan().set("signaturePemberiPern", response[0].ttdPemberiPern)
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
  object.ttdPetugas = H.tandaTangan().get("signaturePetugas")
  object.ttdPemberiPern = H.tandaTangan().get("signaturePemberiPern")
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
  input.value.jnsKlmPasien = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tglLahirPasien = props.pasien.tgllahir
  input.value.ruangRawat = props.registrasi.namaruangan
  input.value.kelas = props.registrasi.namakelas
}

const fetchDokter = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchPetugas = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const setTandaTanganPerawat = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signaturePetugas", element.ttd)
    } else {
      H.tandaTangan().set("signaturePetugas", '')
    }
  })
}


setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.label-ppd {
  font-weight: 500;
}
</style>
