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
      <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
      <VCard v-else>
          <VTabs slider centered selected="perawat" :tabs="[
            { label: 'Asesmen Keperawatan Pasien HD', value: 'perawat' },
            { label: 'Asesmen Medis Pasien HD', value: 'medis' }]">
            <template #tab="{ activeValue }">
              <div v-if="activeValue == 'perawat'">
                <div class="columns is-multiline p-1">
                  <div class="column is-12">
                    <VCard>
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <VField label="Tgl/Jam Kunjungan">
                            <VControl class="prime-auto">
                              <Calendar  v-model="input.tanggalKunjungan" selectionMode="single" :manualInput="false"
                                class="w-100 mb-4 " :showIcon="true"  :dateFormat="H.dateTimeFormat().prime.date" inputId="single"  :hideOnRangeSelection="true"  />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-4">
                          <VField label="Usia Saat Kunjungan">
                            <VControl>
                              <VInput v-model="input.umur"></VInput>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-4">
                          <VField class="is-autocomplete-select" label="PPJA">
                            <VControl icon="feather:search">
                              <AutoComplete v-model="input.perawatfk" :suggestions="d_Perawat" @complete="fetchPerawat($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                :field="'label'" placeholder="ketik Nama Perawat" />
                            </VControl>
                        </VField>
                      </div>
                      </div>
                    </VCard>
                  </div>
                  <div class="column is-12">
                     <Fieldset :toggleable="true" legend="ANAMNESIS">
                       <div class="columns is-multiline">
                        <div :class="'column ' + data.column" v-for="(data,index) in ANAMNESIS" :key="index">
                          <VField :label="data.label">
                            <VControl>
                              <VTextarea :placeholder="data.label +  '...'" :rows="data.row || 2" v-if="data.type == 'textarea'"></VTextarea>
                              <VInput :placeholder="data.label +  '...'"  v-if="data.type == 'text'"></VInput>
                                <div class="columns is-multiline" v-if="data.type == 'checkbox'">
                                  <div :class="'column ' +data2.column " v-for="(data2,index2) in data.children" :key="index2">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data2.model]" class="p-0"
                                          :true-value="data2" :label="data2.label"
                                          color="primary" circle />
                                        </VControl>
                                  </div>
                                </div>
                            </VControl>
                          </VField>
                        </div>
                       </div>
                     </Fieldset>
                  </div>
                  <div class="column is-12">
                     <Fieldset :toggleable="true" legend="TANDA VITAL">
                      <div class="columns is-multiline">
                        <div :class="'column ' +data.column" :key="index" v-for="(data ,index) in TANDATANDAVITAL">
                          <div v-if="data.type == 'labelinfo'">
                            <h1 style="font-weight:bold;color:var(--dark-text)">{{ data.label }}</h1>
                          </div>
                          <div v-else>
                           <p class="mb-3">{{ data.label }}</p>
                            <VField addons v-if="data.satuan">
                                        <VControl expanded>
                                          <VInput type="text" class="input" v-model="input[data.model]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ data.satuan }}</VButton>
                                        </VControl>
                            </VField>
                            <VField v-else>
                              <VControl raw subcontrol>
                                <input v-model="input[data.model]" class="input" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                     </Fieldset>
                  </div>
                  <div class="column is-12">
                    <Fieldset :toggleable="true" legend="Glasgow Coma Scale ( GCS )">
                      <div class="columns is-multiline">
                          <div class="column is-12">
                              <div class="columns is-multiline">
                                <div class="column is-1">
                                  <h1 class="emr">No</h1>
                                </div>
                                <div class="column is-4">
                                  <h1 class="emr">Parameter</h1>
                                </div>
                                <div class="column is-6">
                                  <h1 class="emr">Pengkajian</h1>
                                </div>
                                <div class="column">
                                  <h1 class="emr">Nilai</h1>
                                </div>
                              </div>
                               <div class="column is-12" style="border-bottom: solid 1px;">
                               </div>
                              <div class="column is-12">
                                <div class="columns is-multiline" :key="index" v-for="(gg,index) in GLASGOWS">
                                  <div class="column is-1">
                                    <h1 class="emr">{{ index + 1 }}</h1>
                                  </div>
                                  <div class="column is-4">
                                    <h1 class="emr">{{ gg.label }}</h1>
                                  </div>
                                  <div class="column is-5 pt-0">
                                    <VField class="">
                                      <div class="column is-12 pb-0" :key="index" v-for="(value,index) in gg.children">
                                        <VControl raw subcontrol>
                                          <VCheckbox v-model="input[gg.model]" class="p-0"
                                          :true-value="value" :label="value.label"
                                          color="primary" circle />
                                        </VControl>
                                      </div>
                                    </VField>
                                  </div>
                                  <div class="column pt-0">
                                    <div class="column pb-0" :key="index" v-for="(value,index) in gg.children">
                                      <h1 class="emr">{{ value.score }}</h1>
                                    </div>
                                  </div>

                                </div>
                                <div class="column is-3" style="margin-left: auto;">
                                  <VField label="Jumlah Total">
                                    <VControl raw subcontrol>
                                      <input v-model="input.jumlahNilai" class="input" disabled />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                            </div>
                      </div>
                    </Fieldset>
                  </div>
                  <div class="column is-12">
                    <Fieldset :toggleable="true" legend="STATUS FUNGSIONAL">
                      <div class="columns is-multiline">
                         <div :class="'column ' + data.column" v-for="(data,index) in STATUSFUNGSIONAL" :key="index">
                            <VField :label="data.label">
                              <VControl>
                                <VInput v-if="data.type == 'text'" v-model="input[data.model]" :placeholder="data.label + '...'"></VInput>
                                <VTextarea v-if="data.type == 'textarea'" rows="2" v-model="input[data.model]" :placeholder="data.label"></VTextarea>
                              </VControl>
                              <div class="columns is-multiline" v-if="data.type == 'checkbox'">
                                <div class="column is-2" v-for="(data2,index2) in data.children" :key="index2">
                                  <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.name]" class="p-0"
                                      :true-value="data2" :label="data2.label"
                                      color="primary" circle />
                                    </VControl>
                                </div>
                              </div>
                            </VField>
                          </div>
                      </div>
                    </Fieldset>
                  </div>
                  <VCard>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-7">
                          <h1 style="font-weight: bold;">Skoring nyeri (Wong Baker Faces)</h1>
                            <div class="columns pt-4">
                              <div class="column" style="text-align: center;" v-for="(image, i) in listImageNyeri.detail" :key="i">
                                <VAvatar size="medium" :picture="image.img"
                                  style="cursor:pointer !important"
                                  :class="isAktive == i ? 'active' : ''" @click="skor(image, i)" />
                                  <p>{{ image.descNilai }}</p>
                                <p>{{ image.nama }}</p>
                              </div>
                            </div>
                        </div>
                        <div class="column is-5">
                          <h1 style="font-weight: bold;">Score</h1>
                            <div class="pt-4">
                              <VField v-for="(skor,i) in listSkoringNyeri.detail" :key="i">
                                <VControl raw subcontrol class="p-0">
                                  <VCheckbox class="pt-0" v-model="input.skoringNyeri"
                                    :true-value="skor.descNilai" :label="skor.nama" color="primary"
                                    circle />
                                  </VControl>
                              </VField>
                            </div>
                        </div>
                      </div>
                    </div>
                  </VCard>
                  <div class="column is-12">
                    <Fieldset :toggleable="true" legend="SKRINING NUTRISI">
                      <div class="columns is-multiline">
                          <div class="column is-12">
                              <div class="columns is-multiline">
                                <div class="column is-5">
                                  <h1 class="emr">Indikator Penilaian malnutrisi</h1>
                                </div>
                                <div class="column is-6">
                                  <h1 class="emr">Pengkajian</h1>
                                </div>
                                <div class="column">
                                  <h1 class="emr">Nilai</h1>
                                </div>
                                <div class="column is-12" style="border-bottom: solid 1px;">
                                </div>
                                 <div class="column is-12">
                                <div class="columns is-multiline" :key="index" v-for="(gg,index) in RESIKONUTRISINAL">
                                  <div class="column is-1">
                                    <h1 class="emr">{{ index + 1 }}</h1>
                                  </div>
                                  <div class="column is-4">
                                    <h1 class="emr">{{ gg.label }}</h1>
                                  </div>
                                  <div class="column is-5 pt-0">
                                    <VField class="">
                                      <div class="column is-12 pb-0" :key="index" v-for="(value,index) in gg.children">
                                        <VControl raw subcontrol>
                                          <VCheckbox v-model="input[gg.model]" class="p-0"
                                          :true-value="value" :label="value.label"
                                          color="primary" circle />
                                        </VControl>
                                      </div>
                                    </VField>
                                  </div>
                                  <div class="column pt-0">
                                    <div class="column pb-0" :key="index" v-for="(value,index) in gg.children">
                                      <h1 class="emr">{{ value.value }}</h1>
                                    </div>
                                  </div>
                                </div>
                                <div class="column is-3" style="margin-left: auto;">
                                  <VField label="Jumlah Total">
                                    <VControl raw subcontrol>
                                      <input v-model="input.jumlahNilaiNutrisi" class="input" disabled />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                              </div>
                          </div>
                      </div>
                    </Fieldset>
                  </div>
                </div>
              </div>
              <div v-if="activeValue == 'medis'">
                <div class="columns is-multiline p-1">
                  <div class="column is-12">
                    <VCard>
                    </VCard>
                  </div>
                </div>
              </div>
            </template>
          </VTabs>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/asesmen-hemodialisa'
import Calendar from 'primevue/calendar';
import moment from 'moment'


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
const loadData : any = ref(false)
const isAktive = ref()

let ANAMNESIS = ref(EMR.ANAMNESIS())
let TANDATANDAVITAL = ref(EMR.TANDATANDAVITAL())
let GLASGOWS = ref(EMR.GLASGOWS())
let STATUSFUNGSIONAL = ref(EMR.STATUSFUNGSIONAL())
let listImageNyeri: any = ref(EMR.IMGNYERI())
let listSkoringNyeri: any = ref(EMR.SKORNYERI())
let RESIKONUTRISINAL: any = ref(EMR.RESIKONUTRISINAL())


const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {

  loadData.value = true
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0]
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }
  loadData.value = false
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
const skor = (e: any, i: any) => {

    let listSkor = listSkoringNyeri.value.detail

    listSkor.forEach((element: any) => {
        if (element.descNilai == e.descNilai) {
            input.value.skoringNyeri = e.descNilai
        }
    });
    isAktive.value = i

}
const setAutoFill = async () => {
  input.value.tanggalKunjungan  = moment(props.registrasi.tglregistrasi || new Date()).format("DD-MM-YYYY hh:mm:ss");
  input.value.umur = props.pasien.umur;
  input.value.perawatfk =  { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
  await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd="  + NOREC_PD +
        "&collection=VitalSign" +
        "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,tinggiBadan"
  ).then((response) => {
        input.value.nadi = response ? response.nadi : ''
        input.value.tekananDarah = response ? response.tekananDarah : ''
        input.value.pernapasan = response ? response.pernapasan : ''
        input.value.lingkarPerut = response ? response.lingkarPerut : ''
        input.value.suhuBadan = response ? response.suhu : ''
        input.value.beratBadan = response ? response.beratBadan : ''
        input.value.tinggiBadan = response ? response.tinggiBadan : ''
        input.value.pernafasan = response ? response.pernapasan : ''
        input.value.imt = response ? response.IMT : ''
  })

}
watch(() => [
    input.value.responBukaMata,
    input.value.responMotorik,
    input.value.responVerbal,
], () => {
    let poin1 = input.value.responBukaMata ? parseInt(input.value.responBukaMata.score) : 0
    let poin2 = input.value.responMotorik ? parseInt(input.value.responMotorik.score) : 0
    let poin3 = input.value.responVerbal ? parseInt(input.value.responVerbal.score) : 0
    const total = poin1 + poin2 + poin3
    input.value.jumlahNilai = total
})
watch(() => [
    input.value.penurunanBeratBadan,
    input.value.penurunanNafsuMakan,
    input.value.responVerbal,
], () => {
    let poin1 = input.value.penurunanBeratBadan ? parseInt(input.value.penurunanBeratBadan.value) : 0
    let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.value) : 0
    const total = poin1 + poin2
    input.value.jumlahNilaiNutrisi = total
})
setView()
setAutoFill()
loadRiwayat()
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
.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

</style>
