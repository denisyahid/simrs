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

  <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
  <div class="column is-12" v-else>
    <VCard>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-6">
            <span class="label-khcu">Yth</span>
            <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
              <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                <AutoComplete v-model="input.kpdPegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="ketik untuk mencari..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <span class="label-khcu">Mohon perawatan pasien selanjutnya di ruangan</span>
            <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
              <VControl icon="fas fa-home" fullwidth class="prime-auto ">
                <AutoComplete v-model="input.ruanganSelanjutnya" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="ketik untuk mencari..." />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column" v-for="(data) in pemeriksaan" :class="data.label == 'Diagnosa' ? 'is-5' : 'is-4'">
            <span class="label-khcu">{{ data.label }}</span>
            <VField class="pt-3" v-if="data.label != 'Diagnosa'">
              <VControl>
                <VTextarea v-model="input[data.model]" rows="3" :placeholder="data.label + '...'">
                </VTextarea>
              </VControl>
            </VField>
            <VField v-else class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="item.wali" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari ..." />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <span class="label-khcu">Alasan keluar ICU</span>
        <div class="column p-2" v-for="(data) in alasanKeluarHCU">
          <VField>
            <VControl raw subcontrol>
              <VCheckbox v-model="input.alasanKeluar" :true-value="data.code" :label="data.label" class="p-0"
                color="primary" square />
            </VControl>
          </VField>
        </div>
      </div>
    </VCard>

    <div class="column">
      <VCard>
        <div class="column">
          <span class="label-khcu">Atas perhatiannya sejawat, kami ucapkan terima kasih</span>
          <div class="column is-3">
            <span class="label-khcu">Bandung</span>
            <VDatePicker class="pt-3" v-model="input.tglPersetujuan" color="green" trim-weeks mode="date"
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
          <div class="columns is-multiline pt-0 pl-3 pr-3">
            <div class="column">
              <span class="label-khcu">Petugas Pengirim</span>
              <VField class="pt-3">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.pegawaiPengirim" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas Pengirim..." />
                </VControl>
              </VField>
            </div>
            <div class="column">
              <span class="label-khcu">DPJP</span>
              <VField class="pt-3">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dokterDPJP" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" disabled
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai..." />
                </VControl>
              </VField>
            </div>
            <div class="column">
              <span class="label-khcu">Patugas Penerima</span>
              <VField class="pt-3">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.petugasPenerima" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas Penerima..." />
                </VControl>
              </VField>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


// ===================

let pemeriksaan = ([
  {
    "label": "Keluhan Saat ini",
    "model": "keluhanSaatIni"
  },
  {
    "label": "Pemeriksaan Fisik",
    "model": "pemeriksaanFisik"
  },
  {
    "label": "Pemeriksaan Penunjang",
    "model": "pemeriksaanPenunjang"
  },
  {
    "label": "Diagnosa",
    "model": "diagnosa"
  },
  {
    "label": "Rencana Terapi di Rungan Rawat Biasa",
    "model": "rencanaTerapi"
  },
])

let alasanKeluarHCU = ([
  {
    "label": "Penyakit atau keadaan pasien telah membaik dan cukup stabil, sehingga tidak memerlukan terapi atau pemantuan intensif",
    "code": "KPM"
  },
  {
    "label": "Hasil pemantauan intensif atau terapi tidak bermanfaat atau tidak memberikan hasil yang berarti bagi pasien terutama pada pasien yang tidak menggunakan alat bantu mekanik (ventilasi mekanik)",
    "code": "HPI"
  },
  {
    "label": "Pasien atau keluarga menolak untuk perawatan lanjut karena berbagai sebab dan pihak keluarga atau pasien (keluar atas permintaan)",
    "code": "PMP"
  },
])






// ===================


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

const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const d_Pegawai: any = ref([])
const d_Diagnosa: any = ref([])
const d_Ruangan: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tglPersetujuan: new Date()
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {

  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0]
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
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

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.dokterDPJP = props.registrasi.dokter
}
setView()
setAutoFill()

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
</script>

<style lang="scss">
.label-khcu {
  font-weight: 500;
}

.p-disabled,
.p-component:disabled {
  color: rgb(58, 57, 55);
  font-weight: 500;
}
</style>
