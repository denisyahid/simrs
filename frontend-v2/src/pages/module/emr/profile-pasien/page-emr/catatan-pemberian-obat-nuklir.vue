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
              <h3>Catatan Pemberian Obat Kedokteran Nuklir</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :isHideCetak="true"
                isHideST></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column">
          <div class="columns">
            <div class="column is-6">
              <h2>Alergi Terhadap Obat</h2>
              <VField>
                <VTextarea rows="2" v-model="input.TAAlergiObat"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <h2>Berat Badan</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBBeratBadan" />
              </VControl>
            </div>
          </div>

          <!-- Intruksi Pengobatan -->
          <div class="column" style="overflow:auto">
            <table class="table">
              <tr>
                <th colspan="10" style="background-color: palegreen;">Intruksi Pengobatan</th>
              </tr>
              <tr>
                <th style="background-color: palegreen;width:10%">#</th>
                <th style="background-color: palegreen;width:5%">No</th>
                <th style="background-color: palegreen;width:10%">Hari, Tanggal</th>
                <th style="background-color: palegreen;width:10%">Nama Obat</th>
                <th style="background-color: palegreen;width:10%">Dosis</th>
                <th style="background-color: palegreen;width:10%">Frekuensi</th>
                <th style="background-color: palegreen;width:10%">Rute</th>
                <th style="background-color: palegreen;width:10%">Paraf Dokter</th>
                <th style="background-color: palegreen;width:10%">Perawat 1</th>
                <th style="background-color: palegreen;width:10%">Perawat 2</th>
              </tr>
              <tr v-for="(item, index) in input.details" :key="index">
                <td style="vertical-align: inherit">
                  <div class="column">
                    <VButtons style="justify-content:space-around">
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                        v-tooltip.bubble="'Tambah '">
                      </VIconButton>
                      <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                        @click="removeItem(index)" color="danger">
                      </VIconButton>
                    </VButtons>
                  </div>
                </td>
                <td>{{ getAlphabet(index) }}</td>
                <td>
                  <VDatePicker v-model="item.DTanggal_IP" mode="date" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </td>
                <td>
                  <VField>
                    <VTextarea rows="2" v-model="item.TBNamaObat"></VTextarea>
                  </VField>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="item.TBDosis" />
                  </VControl>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="item.Frekuensi" />
                  </VControl>
                </td>
                <td>
                    <Multiselect v-model="item.TBRute" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="lisRute" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                </td>
                <td>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="item.DDParafDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </td>
                <td>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="item.DDPerawat1" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </td>
                <td>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="item.DDPerawat2" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </td>
              </tr>
            </table>
          </div>
          <div class="column">
            <h1 style="color: red;">Untuk Keselamatan Pasien :</h1>
            <h2>DOKTER :</h2>
            <span>
              1. Tulisakan nama obat termasuk dosis, frekuensi dan rute<br>
              2. Tulisan harus jelas dan terbaca, serta tanda tangan untuk keabsahan instruksi/resep<br>
              3. Tanda tangan dokter dalam catatan pengobatan harus dilakukan dalam 24 jam<br>
              4. Semua obat yang diberikan selama dirawat harus dicatat dalam catatan pengobatan<br>
              5. Pembatalan/penghentian diberi tanda 2 garis miring (//) pada kolom pemberian terakhir dan ditulis
              "STOP"
            </span>
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

useHead({ title: 'Catatan Pemberian Obat Kedokteran Nuklir - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('CatatanPemberianObatNuklir') //table mongodb
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
  details: [{ no: 1, DTanggal_IP: new Date() }]
})
const lisRute: any = ref([{ value: 'Oral', label: 'Oral' }, { value: 'Injeksi', label: 'Injeksi' }, {value:'nebul', label:'nebul' }])
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
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
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
const addNewItem2 = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details2[input.value.details2.length - 1].no + 1,
    Time_0: hours, Time_1: hours, Time_2: hours, Time_3: hours, Time_4: hours, Time_5: hours, Time_6: hours, Time_7: hours, Time_8: hours, Time_9: hours,
    Time_10: hours, Time_11: hours, Time_12: hours, Time_13: hours, Time_14: hours, Time_15: hours, Time_16: hours, Time_17: hours, Time_18: hours, Time_19: hours,
    Time_20: hours, Time_21: hours, Time_22: hours, Time_23: hours, Time_24: hours, Time_25: hours, Time_26: hours, Time_27: hours, Time_28: hours, Time_29: hours,
    Time_30: hours, Time_31: hours, Time_32: hours, Time_33: hours, Time_34: hours
  }
  input.value.details2.push(newItem);
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
</script>
