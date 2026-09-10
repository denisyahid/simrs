<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
          <div class="form-header-inner">
            <div class="left">
              <h3>Triage Pasien IGD</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST>
              </ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->
        <div class="column is-12">
          <div class="column is-12">
            <div class="columns">
              <div class="column is-4">
                <VField label="Tanggal Kedatangan">
                  <VDatePicker v-model="input.DTanggalKedatangan" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Jam Kedatangan">
                  <VDatePicker v-model="input.TjamKedatangan" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:clock" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Jam Triage">
                  <VDatePicker v-model="input.TjamTriage" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:clock" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
          </div>

          <hr>

          <div class="column is-12 is-flex" style="justify-content: center;font-size: large;">
            <h1>PRIMARY SURVEY</h1>
          </div>
          <div class="column is-6" align="right" style="margin-left: auto;">
            <VButton type="button" rounded outlined color="info" icon="feather:link" isLoading="false"
              @click="batasNormal()">
              Batas Normal
            </VButton>
          </div>
          <div class="column is-12 pt-2">
            <table class="tg">
              <thead>
                <tr>
                  <th>AIRWAY</th>
                  <th>BREATHING</th>
                  <th>CIRCULATION</th>
                  <th>DISABILITY/NEUROLOGICAL</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="column" v-for="(data) in ListAirway">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="data.caption" :label="data.caption"
                          v-model="input[data.caption]" />
                      </VControl>
                    </div>
                  </td>
                  <td>
                    <div class="column" v-for="(data) in ListBreathing">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="data.caption" :label="data.caption"
                          v-model="input[data.caption]" />
                      </VControl>
                    </div>
                  </td>
                  <td>
                    <div>
                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-bottom: -10px;">
                          <label style="font-weight: bold">Nadi</label>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Kuat" label="Kuat"
                              v-model="input.CBNadi" />
                          </VControl>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Lemah" label="Lemah"
                              v-model="input.CBNadi" />
                          </VControl>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Tidak Ada Nadi"
                              label="Tidak Ada Nadi" v-model="input.CBNadi" />
                          </VControl>
                        </div>
                      </div>
                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-bottom: -10px;">
                          <label style="font-weight: bold">CRT</label>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="< 2" label="< 2"
                              v-model="input.CBCRT" />
                          </VControl>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="> 2" label="> 2"
                              v-model="input.CBCRT" />
                          </VControl>
                        </div>
                      </div>
                      <div class="column">
                        <VField label="Warna Kulit">
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TBWarnakulit" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-bottom: -10px;">
                          <label style="font-weight: bold">Perdarahan</label>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Terkontrol" label="Terkontrol"
                              v-model="input.CBPerdarahan" />
                          </VControl>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Tidak terkontrol"
                              label="Tidak terkontrol" v-model="input.CBPerdarahan" />
                          </VControl>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Tidak ada" label="Tidak ada"
                              v-model="input.CBPerdarahan" />
                          </VControl>
                        </div>
                      </div>
                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-bottom: -10px;">
                          <label style="font-weight: bold">Turgor Kulit</label>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Baik" label="Baik"
                              v-model="input.CBTurgorKulit" />
                          </VControl>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Buruk" label="Buruk"
                              v-model="input.CBTurgorKulit" />
                          </VControl>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="columns is-multiline">
                      <div class="column is-12" style="margin-bottom: -10px;">
                        <label style="font-weight: bold">Respon</label>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Alert" label="Alert"
                            v-model="input.CBRespon" />
                        </VControl>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Pain" label="Pain"
                            v-model="input.CBRespon" />
                        </VControl>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Verbal" label="Verbal"
                            v-model="input.CBRespon" />
                        </VControl>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Unrespon" label="Unrespon"
                            v-model="input.CBRespon" />
                        </VControl>
                      </div>
                    </div>
                    <div class="columns is-multiline">
                      <div class="column is-12" style="margin-bottom: -10px;">
                        <label style="font-weight: bold">Pupil</label>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Isokor" label="Isokor"
                            v-model="input.CBPupil" />
                        </VControl>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Anisokor" label="Anisokor"
                            v-model="input.CBPupil" />
                        </VControl>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Midriasis" label="Midriasis"
                            v-model="input.CBPupil" />
                        </VControl>
                      </div>
                    </div>
                    <div class="columns is-multiline">
                      <div class="column is-12" style="margin-bottom: -10px;">
                        <label style="font-weight: bold">Refleks : </label>
                      </div>
                      <div class="column is-5">
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TB1Refelks" />
                        </VControl>
                      </div>
                      <div class="column is-2" style="text-align: center;vertical-align: middle;">/
                      </div>
                      <div class="column is-5">
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TB2Refelks" />
                        </VControl>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="4">
                    <div class="column" style="font-weight: bold;">Kategori Triage : </div>
                    <div class="columns is-multiline" style="margin-left: 8px;">
                      <div class="column is-2">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="1 (Segera)" label="1 (Segera)"
                            v-model="input.CBKT" />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="2 (10 Menit)" label="2 (10 Menit)"
                            v-model="input.CBKT" />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="3 (30 Menit)" label="3 (30 Menit)"
                            v-model="input.CBKT" />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="4 (60 Menit)" label="4 (60 Menit)"
                            v-model="input.CBKT" />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="5 (120 Menit)" label="5 (120 Menit)"
                            v-model="input.CBKT" />
                        </VControl>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="columns is-multiline column">
              <div class="column is-12 pb-0 pt-5">
                <h1>Keadaan Umum</h1>
              </div>
              <div class="column is-4">
                <Multiselect v-model="input.keadaanumum" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-6"></div>
              <div class="column is-12 pt-0">
                <h1 class="pt-1 pb-1">GCS</h1>
                <div class="columns">
                  <div class="column is-4">
                    <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>E</VButton>
                      </VControl>
                      <VControl>
                        <VInput type="text" class="input" maxLength="1" v-model="input.TBeGCS" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>V</VButton>
                      </VControl>
                      <VControl>
                        <VInput type="text" class="input" maxLength="1" v-model="input.TBvGCS" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>M</VButton>
                      </VControl>
                      <VControl>
                        <VInput type="text" class="input" maxLength="1" v-model="input.TBmGCS" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <span style="font-weight: bold;">Tanda-tanda Vital</span>
              </div>
              <div class="column is-4">
                <VField addons style="padding: 5px;padding-top:0px" label="Suhu : ">
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBcelciusTTV" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>°C</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField addons style="padding: 5px;padding-top:0px" label="Pernafasan : ">
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBPernafasanTTV" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/mnt</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField addons style="padding: 5px;padding-top:0px" label="Berat Badan : ">
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBberatBadanTTV" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Kg</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField addons style="padding: 5px;padding-top:0px" label="Nadi : ">
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBnadiTTV" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/mnt</VButton>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4">
                <VField addons style="padding: 5px;padding-top:0px" label="Tekanan Darah : ">
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBtekananDarahTTV" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>mmHg</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField addons style="padding: 5px;padding-top:0px" label="Tinggi Badan : ">
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBtinggiBadanTTV" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>cm</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField addons style="padding: 5px;padding-top:0px" label="SaO2 : ">
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBnspo2TTV" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>%</VButton>
                  </VControl>
                </VField>
                <VControl>
                  <VCheckbox class="m-0" v-model="input.device" true-value="Device" label="Device" color="primary"
                    circle />
                </VControl>
              </div>
              <div class="column is-12 p-0"></div>
              <template v-if="input.device == 'Device'">
                <div class="column is-2 pt-0 pb-0">
                  <span>NC</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.NC" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>I/m</VButton>
                    </VControl>
                  </VField>
                </div>
              </template>

              <template v-if="input.device == 'Device'">
                <div class="column is-2 pt-0 pb-0">
                  <span>SM</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.SM" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>I/m</VButton>
                    </VControl>
                  </VField>
                </div>
              </template>

              <template v-if="input.device == 'Device'">
                <div class="column is-2 pt-0 pb-0">
                  <span>NRM</span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.NRM" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>I/m</VButton>
                    </VControl>
                  </VField>
                </div>
              </template>

              <template v-if="input.device == 'Device'">
                <div class="column is-2 pt-0 pb-0">
                  <span>CPAP</span>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.cpap" />
                    </VControl>
                  </VField>
                </div>
              </template>

              <template v-if="input.device == 'Device'">
                <div class="column is-2 pt-0 pb-0">
                  <span>VENTI</span>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.venti" />
                    </VControl>
                  </VField>
                </div>
              </template>
            </div>
          </div>

          <div class="columns">
            <div class="column is-8"></div>
            <div class="column is-4">
              <div class="column" style="text-align:center;">
                <h1>Nama dan Tanda tangan Petugas Triage</h1>
                <TandaTangan :elemenID="'TTDPetugas'" :width="'150'" :height="'150'" class="dek" />
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.CBPetugas" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                </VControl>
              </div>
            </div>
          </div>
        </div>

        <!-- form baru -->
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

// Loopingan
let ListAirway = ref([
  { caption: "Bebas" },
  { caption: "Gargling" },
  { caption: "Stridor" },
  { caption: "Wheezing" },
  { caption: "Ronchi" },
  { caption: "Terintubasi" }
])
let ListBreathing = ref([
  { caption: "Spontan" },
  { caption: "Tachipneu" },
  { caption: "Dispneu" },
  { caption: "Apneu" },
  { caption: "Ventilasi Mekanik" },
  { caption: "Memakai Ventilator" }
])

// Judul
useHead({ title: 'Triage Pasien IGD - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const newDate = new Date();
const user = useUserSession().getUser().pegawai;
const zeroTime = new Date(new Date().setHours(0, 0, 0, 0));
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
const loadData: any = ref(true)
const COLLECTION: any = ref('TriagePasienIGD') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const d_Petugas: any = ref([])
const isLoading = ref(false)
const isAktive = ref()
const isStuck = computed(() => {
  return y.value > 30
})
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
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
const input: any = ref({
  DTanggalKedatangan: newDate,
  TjamKedatangan: newDate,
  TjamTriage: newDate,
  CBBaikKU: 'Baik',

})
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date,
    jam: new Date
  },
  filter: '',
  airway: [],
  disability: []
})
const fetchPetugas = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10&nyariperawatsamadokter=true`).then((response) => {
    d_Petugas.value = response
  })
}
const loadRiwayat = async () => {
  const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
    H.tandaTangan().set("TTDPetugas", dataTTD.value.TTDPetugas)
  } else {
    getDataExist();
  }
}
const getDataExist = () => {
  input.value.TBeGCS = 4
  input.value.TBvGCS = 5
  input.value.TBmGCS = 6
  input.value.keadaanumum = 1
  input.value.CBPetugas = { label: user.namaLengkap, value: user.id }
}
const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  // let countAirway = 0;
  // let countBreath = 0;

  // for (let kAirway = 0; kAirway < ListAirway.value.length; kAirway++) {
  //   const element = input.value['CBairway_' + kAirway] ?? undefined;
  //   if (element == undefined || element == false) {
  //     countAirway++;
  //   }
  // }

  // for (let kBreath = 0; kBreath < ListBreathing.value.length; kBreath++) {
  //   const element = input.value['CBbreathing_' + kBreath] ?? undefined;
  //   if (element == undefined || element == false) {
  //     countBreath++;
  //   }
  // }

  // if (!input.value.CBairway) {
  //   H.alert('warning', 'Silahkan pilih data AIRWAY');
  //   return;
  // }

  // if (!input.value.CBbreathing) {
  //   H.alert('warning', 'Silahkan pilih data BREATHING');
  //   return;
  // }

  if (!input.value.CBNadi) {
    H.alert('warning', 'Silahkan pilih Circulation Nadi');
    return;
  }

  if (!input.value.CBCRT) {
    H.alert('warning', 'Silahkan pilih Circulation CRT');
    return;
  }

  if (!input.value.TBWarnakulit) {
    H.alert('warning', 'Silahkan isi Circulation Warna Kulit');
    return;
  }

  if (!input.value.CBPerdarahan) {
    H.alert('warning', 'Silahkan isi Circulation Pendarahan');
    return;
  }

  if (!input.value.CBTurgorKulit) {
    H.alert('warning', 'Silahkan isi Circulation Tugor Kulit');
    return;
  }

  if (!input.value.CBRespon) {
    H.alert('warning', 'Silahkan isi Circulation Respon');
    return;
  }

  if (!input.value.CBPupil) {
    H.alert('warning', 'Silahkan Pilih Circulation Pupil');
    return;
  }

  if (!input.value.CBKT) {
    H.alert('warning', 'Silahkan pilih Kategori Triage');
    return;
  }

  if (!input.value.TBeGCS || !input.value.TBvGCS || !input.value.TBmGCS) {
    H.alert('warning', 'Silahkan isi GCS');
    return;
  }

  if (!input.value.TBcelciusTTV || !input.value.TBPernafasanTTV || !input.value.TBberatBadanTTV ||
    !input.value.TBnadiTTV || !input.value.TBtekananDarahTTV || !input.value.TBnspo2TTV ||
    !input.value.TBtinggiBadanTTV
  ) {
    console.log(input.value)
    H.alert('warning', 'Silahkan Lengkapi Tanda Tangan Vital');
    return;
  }

  if (!input.value.CBPetugas || H.tandaTangan().get("TTDPetugas") == undefined) {
    H.alert('warning', 'Silahkan Tanda Tangan dan Pilih Petugas');
    return;
  }

  object = input.value
  object['TTDPetugas'] = H.tandaTangan().get("TTDPetugas");
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
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

var normal = 1;
function batasNormal() {
  let d = input.value
  if (normal == 1) {
    d.Bebas = 'Bebas'
    d.Spontan = 'Spontan'
    d.CBNadi = 'Kuat'
    d.CBCRT = '< 2'
    d.TBWarnakulit = 'Normal'
    d.CBPerdarahan = 'Tidak ada'
    d.CBTurgorKulit = 'Baik'
    d.CBRespon = 'Alert'
    d.CBPupil = 'Isokor'
    d.TB1Refelks = '+'
    d.TB2Refelks = '+'
    d.CBKT = '3 (30 Menit)'
    normal = normal - 1;
    return normal;
  } else {
    d.Bebas = undefined
    d.Spontan = undefined
    d.CBNadi = undefined
    d.CBCRT = undefined
    d.TBWarnakulit = undefined
    d.CBPerdarahan = undefined
    d.CBTurgorKulit = undefined
    d.CBRespon = undefined
    d.CBPupil = undefined
    d.TB1Refelks = undefined
    d.TB2Refelks = undefined
    d.CBKT = undefined
    normal = normal + 1
    return normal;
  }
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
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
h1 {
  font-weight: bold;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100% !important;
}

.tg td {
  border-style: solid;
  border-color: lightgray;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 10px;
  word-break: normal;
}

.tg th {
  text-align: center !important;
  border-style: solid;
  border-color: lightgray;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: bold;
  overflow: hidden;
  background-color: rgb(238, 238, 238);
  vertical-align: middle;
  padding: 10px 5px;
  word-break: normal;
}
</style>
