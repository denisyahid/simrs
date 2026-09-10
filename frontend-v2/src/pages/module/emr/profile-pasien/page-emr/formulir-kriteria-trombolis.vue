<style lang="scss">
font,
td {
  color: black !important;
}

.skuy.p-tabview .p-tabview-panels {
  background: #ffffff;
  padding: 1rem;
  border: 0 none;
  color: red;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}


.table {
  border-collapse: collapse;
}

.table,
.tr,
.th,
.td {
  border: 1.6px solid black !important;
}

.th,
.td {
  padding: 10px;
}

.th {
  font-weight: 500 !important;
  color: black !important;
  text-align: center !important;
}



.skuy.p-tabview .p-tabview-panels {
  background: #ffffff;
  padding: 1rem;
  border: 0 none;
  color: red;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var();
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  border-color: var();
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top
}
</style>


<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
          </div>
        </div>
      </div>

      <div class="column pl-0 pr-0 pt-0">
        <div class="column is-12">
          <div class="columns is-multiline p-3">
            <div class="column is-12">
              <table class="table" width="100%" border="1">
                <tbody>
                  <tr class="tr">
                    <td class="td" width="6%" height="42" style="text-align: center; vertical-align: middle;">
                      <font style="font-size: 18px"><strong>NO</strong>
                      </font>
                    </td>
                    <td class="td" height="42" colspan="2" style="text-align: center; vertical-align: middle;">
                      <font style="font-size: 18px"><strong>Kriteria Trombolisis </strong>
                      </font>
                    </td>
                    <td class="td" width="11%" height="42" style="text-align: center; vertical-align: middle;">
                      <font style="font-size: 18px"><strong>Ceklis</strong>
                      </font>
                    </td>

                  </tr>
                  <tr class="tr">
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">
                      <font style="font-size: 14px">1
                      </font>
                    </td>
                    <td class="td" width="19%" rowspan="3"
                      style="text-align: center; vertical-align: middle;background-color: lightgray;font-weight: bold;color:white">
                      <font style="font-size: 14px">
                        Kriteria Code Stroke

                      </font>
                    </td>
                    <td class="td" width="64%" style="text-align: left; vertical-align: middle;">
                      <font style="font-size: 14px"> Kriteria Trombolisis</font>
                    </td>
                    <td class="td" style="text-align: center;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kriteriaTrombolisisCheck" class="p-0 check" color="primary"
                            circle />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">2</td>
                    <td class="td" width="64%" style="text-align: left; vertical-align: middle;">
                      <font style="font-size: 14px">
                        Onset ≤ 4,5 jam (serangan stroke pertama kali)
                      </font>
                    </td>
                    <td class="td" style="text-align: center;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.onsetCheck" class="p-0 check" color="primary" circle />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">3</td>
                    <td class="td" width="64%">
                      <font style="font-size: 14px">
                        Diagnosis klinis stroke dengan deficit neurologis yang jelas
                      </font>
                    </td>
                    <td class="td" style="text-align: center;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.strokeDeficitNeurologisCheck" class="p-0 check" color="primary"
                            circle />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">4</td>
                    <td class="td" colspan="2">
                      <font style="font-size: 14px">
                        Tidak ada gambaran perdarahan intrakranial pada ct scan
                      </font>
                    </td>
                    <td class="td" style="text-align: center;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.gambaranPerdarahanIntrakranialCtScanCheck" class="p-0 check"
                            color="primary" circle />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">5</td>
                    <td class="td" colspan="2">
                      <font style="font-size: 14px">
                        Pasien dan keluarga menyetujui inform concent secara tertulis
                      </font>
                    </td>
                    <td class="td" style="text-align: center;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.persetujuanGeneralConsentCheck" class="p-0 check" color="primary"
                            circle />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">6</td>
                    <td class="td" colspan="2">
                      <font style="font-size: 14px">
                        Defisit neurologis sedang – berat (score NIHSS ≥ 5)
                      </font>
                    </td>
                    <td class="td" style="text-align: center;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.defisitNeurologisSedangBeratCheck" class="p-0 check" color="primary"
                            circle />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">7</td>
                    <td class="td" colspan="2">
                      <font style="font-size: 14px">
                        Tidak ada riwayat trauma kepala/stroke iskemik 3 bulan terakhir
                      </font>
                    </td>
                    <td class="td" style="text-align: center;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.traumaStrokeIskemik3BulanTerakhirCheck" class="p-0 check"
                            color="primary" circle />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">8</td>
                    <td class="td" colspan="2">
                      <font style="font-size: 14px">
                        Tidak ada gambaran infark multilobar
                      </font>
                    </td>
                    <td class="td" style="text-align: center;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.gambaranInfarkMultilobarCheck" class="p-0 check" color="primary"
                            circle />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">9</td>
                    <td class="td" colspan="2">
                      <font style="font-size: 14px">
                        Tidak ada kejang pada saat onset stroke
                      </font>
                    </td>
                    <td class="td" style="text-align: center;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kejangSaatOnsetStrokeCheck" class="p-0 check" color="primary"
                            circle />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">
                      <font style="font-size: 14px">10
                      </font>
                    </td>
                    <td class="td" colspan="2">
                      <font style="font-size: 14px">
                        Tidak ada riwayat tumor intracranial/intraaxial
                      </font>
                    </td>
                    <td class="td" style="text-align: center;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.riwayatTumorIntracranialIntraaxialCheck" class="p-0 check"
                            color="primary" circle />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">
                      <font style="font-size: 14px">11
                      </font>
                    </td>
                    <td class="td" colspan="2">
                      <font style="font-size: 14px">
                        Tidak ada riwayat pembedahan mayor, trauma berat, perdarahan GIT/traktus urinarius, perdarahan
                        intracranial dalam 3 bulan terakhir
                      </font>
                    </td>
                    <td class="td" style="text-align: center;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.riwayatPembedahanMayorTraumaBeratDll3BulanTerakhirCheck"
                            class="p-0 check" color="primary" circle />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td">&nbsp;</td>
                    <td class="td" colspan="2">&nbsp;</td>
                    <td class="td">&nbsp;</td>
                  </tr>
                </tbody>
              </table>
            </div>


            <div class="column is-12">
              <div class="column is-4" style="margin-left: auto;">
                <VCard>
                  <div class="column is-12 p-1" style="text-align: center;">
                    <h1 style="font-weight: bold" class="mb-2">Garut</h1>
                    <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                    <div class="column is-12">
                      <TandaTangan :elemenID="'TTDDokter'" :width="'180'" :height="'180'"></TandaTangan>
                    </div>
                    <div class="column is-12">
                      <h1 class="p-0" style="font-weight: bold">Dokter Pelaksana</h1>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.dokterPelaksana" :suggestions="d_Dokter"
                          @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Ketik untuk mencari..." class="mt-2" />
                      </VControl>
                    </div>
                  </div>
                </VCard>
              </div>
            </div>
          </div>
        </div>
      </div>
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
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
// import Fieldset from 'primevue/fieldset';

const STATUSLOKALISIMG: any = ref('')
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
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Kelas: any = ref([])
const d_Perawat: any = ref([])
const item: any = reactive({})
const COLLECTION: any = ref("FormulirKriteriaTrombolisis") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  terapiTindakan: [{
    no: 1
  }],
  tglDibuat: new Date,
})
const setView = () => {
  useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT, })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)

}
const loadRiwayat = () => {
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      if (response[0]['TTDDokter'] != null) {
        H.tandaTangan().set("TTDDokter", response[0]['TTDDokter'])
      }
    } else {
      input.value.tanggal = new Date()
    }
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDDokter'] = H.tandaTangan().get("TTDDokter")
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
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
    d_Dokter.value = response
  })
}
setView()
loadRiwayat()
</script>