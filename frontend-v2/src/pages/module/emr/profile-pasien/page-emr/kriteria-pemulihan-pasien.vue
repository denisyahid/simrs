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


  <div class="column">
    <div class="columns is-multiline">
      <div class="column is-4">
        <VCard style="background-color: rgb(244 244 244);">
          <div class="columns is-multiline">
            <div class="column is-3 pr-0">
              <VField>
                <VControl>
                  <VTextarea v-model="input.bromageScore" rows="2">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column pl-0" style="text-align: center;align-self: center;">
              <span style="font-size: 15px; font-weight: bold;">BROMAGE SCORE</span>
            </div>
          </div>
        </VCard>

        <VCard class="mt-5" style="padding-bottom: 19.1rem;">
          <div class="columns is-mulitline" style="border-bottom: 1px solid black;">
            <div class="column is-9 pb-1">
              <span>Kriteria</span>
            </div>
            <div class="column pb-1" style="text-align: center;">
              <span>Skor</span>
            </div>
          </div>

          <div class="column pl-0">
            <span>Aktivitas Motorik</span>
            <div class="columns is-multiline pl-5 mt-3" v-for="(data) in bromage">
              <div class="column is-9 p-0">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.model]" :true-value="data.value" class="p-0" :label="data.label"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column p-0" style="text-align: center;">
                <span>{{ data.value.skor }}</span>
              </div>
            </div>
          </div>

          <div class="columns is-multiline pt-5">
            <div class="column is-9">
              <span style="font-weight: 500;">TOTAL SKOR</span>
            </div>
            <div class="column">
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="input.totalSkorbromage" />
                </VControl>
              </VField>
            </div>
          </div>

          <hr class="m-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
          <div class="column">
            <i>Bila skor &lt;= 2, pasien dapat pindah ke ruangan rawat</i>
          </div>
        </VCard>
      </div>

      <div class="column is-4">
        <VCard style="background-color: rgb(244 244 244);">
          <div class="columns is-multiline">
            <div class="column is-3 pr-0">
              <VField>
                <VControl>
                  <VTextarea v-model="input.ramsayScore" rows="2">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column pl-0" style="text-align: center;align-self: center;">
              <span style="font-size: 15px; font-weight: bold;">RAMSAY SCORE</span>
            </div>
          </div>
        </VCard>

        <VCard class="mt-5" style="padding-bottom: 10.4rem;">
          <div class="columns is-mulitline" style="border-bottom: 1px solid black;">
            <div class="column is-9 pb-1">
              <span>Kriteria</span>
            </div>
            <div class="column pb-1" style="text-align: center;">
              <span>Skor</span>
            </div>
          </div>

          <div class="columns is-multiline pt-3" v-for="(data) in ramsay">
            <div class="column is-9 pb-0 pt-1">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.value" class="p-0" :label="data.label"
                    color="primary" square />
                </VControl>
              </VField>
            </div>
            <div class="column pb-0 pt-1" style="text-align: center;">
              <span>{{ data.value.skor }}</span>
            </div>
          </div>

          <div class="columns is-multiline pt-3">
            <div class="column is-9">
              <span style="font-weight: 500;">TOTAL SKOR</span>
            </div>
            <div class="column">
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="input.totalSkorRamsay" />
                </VControl>
              </VField>
            </div>
          </div>

          <hr class="m-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
          <div class="column">
            <i>Bila skor &lt;= 2, pasien dapat pindah ke ruangan rawat</i>
          </div>
        </VCard>
      </div>

      <div class="column is-4">
        <VCard style="background-color: rgb(244 244 244);">
          <div class="columns is-multiline">
            <div class="column is-3 pr-0">
              <VField>
                <VControl>
                  <VTextarea v-model="input.stewardScore" rows="2">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column pl-0" style="text-align: center;align-self: center;">
              <span style="font-size: 15px; font-weight: bold;">STEWARD SCORE</span>
            </div>
          </div>
        </VCard>

        <VCard class="mt-5">
          <div class="columns is-mulitline" style="border-bottom: 1px solid black;">
            <div class="column is-9 pb-1">
              <span>Kriteria</span>
            </div>
            <div class="column pb-1" style="text-align: center;">
              <span>Skor</span>
            </div>
          </div>

          <div class="column pl-0 pr-0" v-for="(datas) in steward">
            <span>{{ datas.title }}</span>
            <div class="column pb-0">
              <div class="columns is-multiline pr-3 pl-3 pt-3" v-for="(data) in datas.detail">
                <div class="column is-9 p-0">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[data.model]" :true-value="data.value" class="p-0" :label="data.label"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column p-0 ml-4" style="text-align: center;">
                  <span>{{ data.value.skor }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="columns is-multiline pt-5">
            <div class="column is-9">
              <span style="font-weight: 500;">TOTAL SKOR</span>
            </div>
            <div class="column">
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="input.totalSkorSteward" />
                </VControl>
              </VField>
            </div>
          </div>

          <hr class="m-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">

          <div class="column">
            <i>Bila skor &gt; 5, dan tanpa skor 0 pasien dapat pindah ke ruang rawat</i>
          </div>
        </VCard>
      </div>
    </div>

    <div class="column is-5 p-0">
      <span>Pasien sudah bisa pindah ke</span>
      <VField class="pt-3">
        <VControl>
          <VInput v-model="input.tmptPasienPindah" type="text" />
        </VControl>
      </VField>
    </div>

    <div class="column p-0 mt-5">
      <Fieldset :toggleable="true" legend="Tanda-tanda Vital">
        <div class="columns is-multiline p-3">
          <div class="column is-3 pb-0" v-for="(data) in vitalSign">
            <span>{{ data.label }}</span>
            <VField addons class="mt-3">
              <VControl expanded>
                <VInput type="text" class="input" v-model="input[data.model]" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>{{ data.satuan }}</VButton>
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column pt-0">
          <span>Catatan Khusus</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="item.catatanKhusus" rows="2" placeholder="Catatan Khusus ...">
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </Fieldset>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/kriteria-pemulihan-pasien'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let bromage = ref(EMR.bromage())
let ramsay = ref(EMR.ramsay())
let steward = ref(EMR.steward())
let vitalSign = ref(EMR.vitalSign())

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
const d_Obat: any = ref([])
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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN +
        "&collection=VitalSign" +
        "&field=tekananDarah,pernapasan,nadi,SPO2"
    ).then((response) => {
        input.value.tekananDarah = response.tekananDarah
        input.value.pernapasan = response.pernapasan
        input.value.nadi = response.nadi
        input.value.spo2 = response.SPO2
    })
}

setView()
setAutoFill()
loadRiwayat()


watch(() => [
  input.value.aktivitasMotorik_1,
  input.value.aktivitasMotorik_2,
  input.value.aktivitasMotorik_3,
  input.value.aktivitasMotorik_4,
], () => {
  let poin1 = input.value.aktivitasMotorik_1 ? parseInt(input.value.aktivitasMotorik_1.skor) : 0
  let poin2 = input.value.aktivitasMotorik_2 ? parseInt(input.value.aktivitasMotorik_2.skor) : 0
  let poin3 = input.value.aktivitasMotorik_3 ? parseInt(input.value.aktivitasMotorik_3.skor) : 0
  let poin4 = input.value.aktivitasMotorik_4 ? parseInt(input.value.aktivitasMotorik_4.skor) : 0

  const jumlahNilai = poin1 + poin2 + poin3 + poin4
  input.value.totalSkorbromage = jumlahNilai
})

watch(() => [
  input.value.poinRamsay_1,
  input.value.poinRamsay_2,
  input.value.poinRamsay_3,
  input.value.poinRamsay_4,
  input.value.poinRamsay_5,
  input.value.poinRamsay_6,
], () => {
  let poin1 = input.value.poinRamsay_1 ? parseInt(input.value.poinRamsay_1.skor) : 0
  let poin2 = input.value.poinRamsay_2 ? parseInt(input.value.poinRamsay_2.skor) : 0
  let poin3 = input.value.poinRamsay_3 ? parseInt(input.value.poinRamsay_3.skor) : 0
  let poin4 = input.value.poinRamsay_4 ? parseInt(input.value.poinRamsay_4.skor) : 0
  let poin5 = input.value.poinRamsay_5 ? parseInt(input.value.poinRamsay_5.skor) : 0
  let poin6 = input.value.poinRamsay_6 ? parseInt(input.value.poinRamsay_6.skor) : 0

  const jumlahNilai = poin1 + poin2 + poin3 + poin4 + poin5 + poin6
  input.value.totalSkorRamsay = jumlahNilai
})

watch(() => [
  input.value.poinKesadaran_1,
  input.value.poinKesadaran_2,
  input.value.poinKesadaran_3,
  input.value.poinRespirasi_1,
  input.value.poinRespirasi_2,
  input.value.poinRespirasi_3,
  input.value.poinAktifasiMotorikST_1,
  input.value.poinAktifasiMotorikST_2,
  input.value.poinAktifasiMotorikST_3,
], () => {
  let poin1 = input.value.poinKesadaran_1 ? parseInt(input.value.poinKesadaran_1.skor) : 0
  let poin2 = input.value.poinKesadaran_2 ? parseInt(input.value.poinKesadaran_2.skor) : 0
  let poin3 = input.value.poinKesadaran_3 ? parseInt(input.value.poinKesadaran_3.skor) : 0
  let poin4 = input.value.poinRespirasi_1 ? parseInt(input.value.poinRespirasi_1.skor) : 0
  let poin5 = input.value.poinRespirasi_2 ? parseInt(input.value.poinRespirasi_2.skor) : 0
  let poin6 = input.value.poinRespirasi_3 ? parseInt(input.value.poinRespirasi_3.skor) : 0
  let poin7 = input.value.poinAktifasiMotorikST_1 ? parseInt(input.value.poinAktifasiMotorikST_1.skor) : 0
  let poin8 = input.value.poinAktifasiMotorikST_2 ? parseInt(input.value.poinAktifasiMotorikST_2.skor) : 0
  let poin9 = input.value.poinAktifasiMotorikST_3 ? parseInt(input.value.poinAktifasiMotorikST_3.skor) : 0

  const jumlahNilai = poin1 + poin2 + poin3 + poin4 + poin5 + poin6 + poin7 + poin8 + poin9
  input.value.totalSkorSteward = jumlahNilai
})




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
