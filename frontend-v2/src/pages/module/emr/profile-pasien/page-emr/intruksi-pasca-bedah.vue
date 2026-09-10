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
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-4">
            <span class="label-ipb">Bila kesakitan</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.bilaKesakitan" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <span class="label-ipb">Lapor, Beri</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.lapor" />
              </VControl>
            </VField>
          </div>
          <div class="column is-5">
            <span class="label-ipb">Bila mual/ muntah Miringkan kepala, bila perlu lakukan suction</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.bilaMual" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-3">
            <span class="label-ipb">Antibiotik</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.antiBiotik" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <span class="label-ipb">Obat-obatan lain</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.obatLain" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <span class="label-ipb">Minum</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.minum" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <span class="label-ipb">Infus</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.infus" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <h1 class="title-ipb">Monitor</h1>
        <div class="columns is-multiline p-3">
          <div class="column">
            <span class="label-ipb">Tensi</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.tensi" />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <span class="label-ipb">Nadi</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.nadi" />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <span class="label-ipb">Nafas</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.nafas" />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <span class="label-ipb">Setiap</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.setiap" />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <span class="label-ipb">Selama</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.selama" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <h1 class="title-ipb">Segera Lapor Bila</h1>
        <div class="column is-3 pb-0 mt-2">
          <VField>
            <VControl raw subcontrol>
              <VCheckbox v-model="input.obstruksi" true-value="obstruksi" class="p-0" label="Obstruksi, Nafas sesak"
                color="primary" square />
            </VControl>
          </VField>
        </div>
        <div class="columns is-multiline pl-3 pt-5 pr-3">
          <div class="column is-3 pb-0">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.sistolik" true-value="sistolik" class="p-0" label="Tekanan Darah Sistolik <="
                  color="primary" square />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VInput type="text" v-model="input.nilaiSistolik" placeholder="mmHg" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 pb-0">
            <span class="label-ipb">atau Diastolik</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.nilaiDiastolik" placeholder="mmHg" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="columns is-multiline p-3">
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.nadiMelambat" true-value="melambat" class="p-0" label="Nadi < " color="primary"
                  square />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VInput type="text" v-model="input.nilaiNadiKurangDari" placeholder="Kali Permenit" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <span class="label-ipb">atau > </span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.nilaiNadiLebihDari" placeholder="Kali Permenit" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="columns is-multiline pt-3 pl-3 pr-3 pb-0">
          <div class="column" v-for="(data) in listCheck" :class="data.code == 'PDL' ? 'is-4' : 'is-3'">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input[data.model]" :true-value="data.code" class="p-0" :label="data.label"
                  color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4 pt-0">
          <span class="label-ipb">Lain-lain</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.lainnya" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column">
        <h1 class="title-ipb">Catatan Perawat</h1>
        <div class="columns is-multiline p-3">
          <div class="column is-4 pb-0">
            <span class="label-ipb">Tanggal dan Waktu</span>
            <VField class="column is-8 p-0 mt-3">
              <VDatePicker v-model="input.tglWaktu" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
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
          <div class="column is-4 pb-0">
            <span class="label-ipb">Catatan</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.catatan" rows="1" placeholder="Catatan...'">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline pl-3 pr-3">
          <div class="column is-2 pb-0">
            <span class="label-ipb">Jam keluar RR</span>
            <VField class="mt-3">
              <VDatePicker v-model="input.jamKeluar" color="green" mode="time" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl>
                      <VInput class="input form-timepicker" :value="inputValue" placeholder="Jam Keluar"
                        v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-3 pb-0">
            <span class="label-ipb">Pindah ke</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.pindahKe" />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <span class="label-ipb">Dokter Anestesi</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Dokter..." />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <span class="label-ipb">Perawat</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.pegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Perawat..." />
              </VControl>
            </VField>
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
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'

// ===================

const listCheck = ([
  {
    "label": "Suhu Tubuh > 37 C",
    "model": "suhuTubuh",
    "code": "ST"
  },
  {
    "label": "Kontraksi uterus lembek",
    "model": "kontraksi",
    "code": "KUL"
  },
  {
    "label": "Perdarahan aktif per vagina",
    "model": "pendarahan",
    "code": "PAV"
  },
  {
    "label": "Rasa tercekik pada leher",
    "model": "tercekik",
    "code": "RTA"
  },
  {
    "label": "Produksi drain aktif",
    "model": "produksi",
    "code": "PDA"
  },
  {
    "label": "Hematoma atau perdarahan pada luka operasi",
    "model": "hematoma",
    "code": "PDL"
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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
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
  tglWaktu : new Date,
  jamKeluar : new Date
})
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
        input.value = response[0] //set ke inputan
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

}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.label-ipb{
  font-weight: 500;
}

.title-ipb{
  font-weight: bold;
}
</style>
