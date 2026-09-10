<style lang="scss">
// .table {
//     border-collapse: collapse;
//     width: 100%;
// }

// .table td { border: 1px solid black !important }

// .table th {
//     text-align: center !important;
//     border: 1px solid black !important;
// }

h1 {
  font-weight: bold !important
}
</style>
<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Surat Permintaan Penggunaan Ruang Operasi IBSA</h3>
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
          <div class="column pb-0">
            <h1>Mohon disiapkan ruang operasi IBSA untuk pasien di bawah ini:</h1>
          </div>
          <div class="column columns is-multiline">
            <div class="column is-4">
              <label>Nama</label>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBNamaPasien" />
              </VControl>
            </div>
            <div class="column is-4">
              <label>Jenis Kelamin</label>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBJenisKelamin" />
              </VControl>
            </div>
            <div class="column is-4">
              <label>Nomor Rekam Medis</label>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBNoRM" />
              </VControl>
            </div>
            <div class="column is-6 pt-0 pb-0">
              <label>Diagnosis</label>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBDiagnosis" />
              </VControl>
            </div>
            <div class="column is-6 pt-0 pb-0">
              <label>Rencana Tindakan</label>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBRencanaTindakan" />
              </VControl>
            </div>
            <div class="column is-4">
              <label>Perkiraan Durasi</label>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBPerkiraanDurasi" />
              </VControl>
            </div>
            <div class="column is-4">
              <label>Hari, Tanggal</label>
              <VDatePicker v-model="input.DHariTanggal" mode="date" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4">
              <label>Acara ke / jam</label>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBAcaraKeJam" />
              </VControl>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <label>Persiapan Khusus</label>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBPersiapanKhusus" />
              </VControl>
            </div>
            <div class="column is-12">
              <h1>Terimakasih atas perhatiannya.</h1>
            </div>
          </div>
          <div class="column columns">
            <div class="column is-4" style="margin-left: auto;text-align:center;">
              <h1>Hormat kami,</h1>
              <TandaTangan :elemenID="'TTDPerawat'" :width="'150'" :height="'150'" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.DDPerawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
              </VControl>
            </div>
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
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
// import InputText from 'primevue/inputtext';

useHead({ title: 'Surat Permintaan Penggunaan Ruang Operasi IBSA - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('SuratPermintaanPenggunaanRuangOperasiIBSA') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({})
const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
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
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
    H.tandaTangan().set("TTDPerawat", dataTTD.value.TTDPerawat)
  } else {
    let dataRegis = props.registrasi;
    let dataPasien = props.pasien;
    input.value.DHariTanggal = new Date();
    input.value.DDPerawat = { label: user.namaLengkap, value: user.id }
    input.value.TBNamaPasien = dataPasien.namapasien
    input.value.TBJenisKelamin = dataPasien.jeniskelamin
    input.value.TBNoRM = dataRegis.nocmfk
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object['TTDPerawat'] = H.tandaTangan().get("TTDPerawat");
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
</script>