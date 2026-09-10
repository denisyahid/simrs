<style lang="scss">
.table {
  border-collapse: collapse;
  width: 150% !important;
}

.table td {
  border: 1px solid black !important;
  text-align: center !important;
  color: black !important;
}

.table th {
  text-align: center !important;
  vertical-align: middle !important;
  border: 1px solid black !important;
  color: black !important;
}

.table2 {
  border-collapse: collapse;
  width: 450% !important;
}

.table2 td {
  border: 1px solid black !important;
  text-align: center !important;
  color: black !important;
  vertical-align: middle !important;
}

.table2 th {
  text-align: center !important;
  vertical-align: middle !important;
  border: 1px solid black !important;
  color: black !important;
}

h1 {
  font-weight: bold !important;
}

h2 {
  color: black !important;
}
</style>
<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Identifikasi Bayi Baru Lahir</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :isHideCetak="true"
                isHideST></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->
        <!-- <pre>{{ props.registrasi }}</pre> -->
        <!-- <pre>{{ props.pasien }}</pre> -->

        <div class="column">
          <div class="columns">
            <div class="column is-6">
              <h2>Nama Ibu Bayi</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.ibubayi" />
              </VControl>
              <h2>Nama Ayah Bayi</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.ayah" />
              </VControl>
              <h2>Alamat</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.alamat" />
              </VControl>
              <h2>Ruangan</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.ruangan" />
              </VControl>
            </div>
            <div class="column is-6">
              <h2>Tanggal Lahir</h2>
              <VControl>
                <VDatePicker v-model="input.tanggalObservasi" mode="date" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VControl>
              <h2>Jenis Kelamin</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.jeniskel" />
              </VControl>
              <h2>Berat Badan</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.bb" />
              </VControl>
              <h2>Panjang Badan</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.pb" />
              </VControl>
              <h2>LK/LD</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.lk" />
              </VControl>
              <h2>Anus</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.anus" />
              </VControl>
              <h2>Kelainan</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.kelain" />
              </VControl>
            </div>
          </div>
          <div class="column" style="overflow:auto">
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
              <thead>
                <tr>
                  <th style="text-align: center; border: 1px solid black; padding: 10px;">
                    Sidik Telapak Kaki Kiri Bayi
                  </th>
                  <th style="text-align: center; border: 1px solid black; padding: 10px;">
                    Sidik Telapak Kaki Kanan Bayi
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="height: 15cm; border: 1px solid black; text-align: center; vertical-align: top;">
                  </td>
                  <td style="height: 15cm; border: 1px solid black; text-align: center; vertical-align: top;">
                  </td>
                </tr>
              </tbody>
            </table>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
              <thead>
                <tr>
                  <th style="text-align: center; border: 1px solid black; padding: 10px; width: 46%;">
                    <div>Sidik Jari Ibu</div>
                    <div>Tangan Kanan Ibu</div>
                  </th>
                  <th style="text-align: center; border: 1px solid black; padding: 10px; width: 50%;">
                    <div>Saksi</div>
                    <div>Petugas Yang Merawat Bayi</div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="height: 200px; border: 1px solid black; text-align: center; vertical-align: top;">
                  </td>
                  <td style="height: 200px; border: 1px solid black; text-align: center; vertical-align: top;">
                  </td>
                </tr>
              </tbody>
            </table>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
              <thead>
                <tr>
                  <th style="text-align: center; border: 1px solid black; padding: 10px; width: 46%;">
                    <div>Yang Bertanggung Jawab</div>
                    <div>Mengambil Bayi Saat Pulang</div>
                  </th>
                  <th style="text-align: center; border: 1px solid black; padding: 10px; width: 50%;">
                    <div style="text-align: center;">
                      <h1>Garut, {{ input.tanggalHariIni }}</h1>
                    </div>
                    <div>Yang Menyerahkan perawat/bidan</div>
                    <div>Ruangan {{ props.registrasi.namaruangan }}</div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="height: 200px; border: 1px solid black; text-align: center; vertical-align: top;">
                    <TandaTangan :elemenID="`tandaTanganBertanggungJawab`" :width="'150'" :height="'150'" class="dek" />
                    <div class="column is-12">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.bertanggungJawab" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td style="height: 200px; border: 1px solid black; text-align: center; vertical-align: top;">
                    <div class="column is-12">
                      <img
                        :src="'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + (input.perawatBidanMenyerahkan ? input.perawatBidanMenyerahkan.label : '-')">
                      <br>
                      <br>
                      <br>
                      <VField class="is-rounded-select is-autocomplete-select">
                        <VControl icon="fa:user" class="prime-auto-cus">
                          <AutoComplete v-model="input.perawatBidanMenyerahkan" :suggestions="d_Pegawai"
                            :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Perawat / Bidan" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
              </tbody>
              <i>Catatan: *) Coret yang tidak benar</i>
            </table>

          </div>
        </div>

        <!-- form baru -->
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({ title: 'Identifikasi Bayi Baru Lahir - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('IdentifikasiBayiBaruLahir') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const hours = new Date().setHours(0, 0, 0, 0);
const input: any = ref({
  D_1_CPO: new Date(),
  details: [{ no: 1, DTanggal_IP: new Date() }],
  tanggalHariIni: new Date().toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
})

const lisRute: any = ref([{ value: 'Oral', label: 'Oral' }, { value: 'Injeksi', label: 'Injeksi' }, { value: 'nebul', label: 'nebul' }])
const listInjeksi: any = ref([{ value: '/24', label: '/24' }, { value: '/12', label: '/12' }, { value: '/8', label: '/8' }, { value: '/6', label: '/6' }])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
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
const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  } else {
    // input.value.DD = { label: user.namaLengkap, value: user.id }
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
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
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => { d_Dokter.value = response })
}
const addNewItem = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details[input.value.details.length - 1].no + 1,
    DTanggal_IP: new Date()
  }
  input.value.details.push(newItem);
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}
const removeItem2 = (index: any) => {
  input.value.details2.splice(index, 1)
}
function getAlphabet(index) {
  return String.fromCharCode(65 + index);
}
const setAutoFill = async () => {
  input.value.ibubayi = props.pasien.namaibu
  input.value.ayah = props.pasien.namaayah
  input.value.alamat = props.pasien.alamatlengkap
  input.value.ruangan = props.registrasi.namaruangan
  input.value.tanggalObservasi = props.pasien.tgllahir
  input.value.jeniskel = props.pasien.jeniskelamin
}
onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
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

// Array
const ArrayKu: any = ref(Array(35).fill(0))
setAutoFill()
</script>
