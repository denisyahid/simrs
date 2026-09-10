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
    <div class="columns is-multiline p-5">
      <div class="column is-12">
        <VCard>
          <Fieldset :toggleable="true" legend="TRIAGE PASIEN DEWASA">
            <div class="columns is-multiline">
              <div class="column is-12">
                <table border="1" class="triase">
                  <thead>
                    <tr>
                      <th>PEMERIKSAAN</th>
                      <th class="bg-danger">RESUSITASI</th>
                      <th class="bg-warning">URGENT</th>
                      <th class="bg-green">NON URGENT</th>
                      <th>TANDA TANDA VITAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(datas, i) in  PasienDewasa">
                      <td width="18%" style="font-weight: 600;">{{ datas.label }}</td>
                      <td v-for="data in datas.children">
                        <div class="column is-12" v-for="checkbox in data">
                          <VField v-if="checkbox.type == 'checkbox'">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[checkbox.model]" class="p-0" :true-value="checkbox.value"
                                :label="checkbox.label" color="primary" circle />
                            </VControl>
                          </VField>
                          <VField v-if="checkbox.type == 'input'" :label="checkbox.label">
                            <VControl raw subcontrol>
                              <VInput type="text" class="mt-3" v-model="input[checkbox.model]"></VInput>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Fieldset>
          <div class="mt-5">
            <Fieldset :toggleable="true" legend="TRIAGE PASIEN BAYI DAN ANAK">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <table border="1" class="triase">
                    <thead>
                      <tr>
                        <th class="bg-danger">RESUSITASI</th>
                        <th class="bg-warning">URGENT</th>
                        <th class="bg-green">NON URGENT</th>
                        <th>TANDA TANDA VITAL</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td width="25%" style="font-weight: 500;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.koma" class="p-0" true-value="Koma" label="Koma"
                                color="primary" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField label="Terdapat tanda prioritas:">
                            <VControl raw subcontrol>
                              <VInput class="input" v-model="input.tandaTandaPrioritas"></VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.tidakAdaTandaGawatDarurat" class="p-0"
                                true-value="Tidak ada tanda gawat darurat" label="Tidak ada tanda gawat darurat"
                                color="primary" circle />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField label="Frekuensi nadi:">
                            <VControl raw subcontrol>
                              <VInput class="input" v-model="input.frekuensiNadi"></VInput>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td width="25%" style="font-weight: 500;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kejang" class="p-0" true-value="Kejang" label="Kejang"
                                color="primary" />
                            </VControl>
                          </VField>
                        </td>
                        <td rowspan="3">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.tidakAdaTandaGawatDarurat" class="p-0"
                                true-value="Tidak ada tanda gawat darurat" label="Tidak ada tanda gawat darurat"
                                color="primary" circle />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.suhuSangatPanas40" class="p-0"
                                true-value="Suhu sangat panas >40° C" label="Suhu sangat panas >40° C" color="primary"
                                circle />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.traumaPerluTindakanBedah" class="p-0"
                                true-value="Trauma/perlu tindakan bedah segera" label="Trauma/perlu tindakan bedah segera"
                                color="primary" circle />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.trismus" class="p-0" true-value="Trismus" label="Trismus"
                                color="primary" circle />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.palor" class="p-0" true-value="Palor (sangat Pucat)"
                                label="Palor (sangat Pucat)" color="primary" circle />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.keracunan" class="p-0" true-value="Keracunan" label="Keracunan"
                                color="primary" circle />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.nyeriHebat" class="p-0" true-value="Nyeri hebat"
                                label="Nyeri hebat" color="primary" circle />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.gelisah" class="p-0" true-value="gelisah" label="gelisah"
                                color="primary" circle />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.giziBuruk" class="p-0" true-value="gizi buruk" label="gizi buruk"
                                color="primary" circle />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.udemaKeduaKaki" class="p-0" true-value="udema kedua tungkai"
                                label="udema kedua tungkai" color="primary" circle />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.lukaBajarLuas" class="p-0" true-value="luka bakar luas"
                                label="luka bakar luas" color="primary" circle />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.tidakAdaTandaPrioritas" class="p-0" true-value="Ya"
                                label="Tidak ada tanda prioritas" color="primary" circle />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField label="Frekuensi nafas:">
                            <VControl raw subcontrol>
                              <VInput class="input" v-model="input.frekuensiNafas"></VInput>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td width="25%" style="font-weight: 500;">
                          <div class="">
                            <VField label="Jalan nafas dan pernafasan:">
                            </VField>
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.anakJalanNafas" class="p-0" true-value="Obstruksi jalan nafas"
                                  label="Obstruksi jalan nafas" color="primary" />
                              </VControl>
                            </VField>
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.anakJalanNafas" class="p-0" true-value="Sianosis"
                                  label="Sianosis" color="primary" />
                              </VControl>
                            </VField>
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.anakJalanNafas" class="p-0" true-value="Sesak nafas berat"
                                  label="Sesak nafas berat" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td></td>
                        <td>
                          <VField label="Suhu:">
                            <VControl raw subcontrol>
                              <VInput class="input" v-model="input.anakSuhu"></VInput>
                            </VControl>
                          </VField>
                          <VField label="Riwayat alergi:">
                            <VControl raw subcontrol>
                              <VInput class="input" v-model="input.anakRiwayatAlergi"></VInput>
                            </VControl>
                          </VField>
                          <VField label="Makanan:">
                            <VControl raw subcontrol>
                              <VInput class="input" v-model="input.anakMakanan"></VInput>
                            </VControl>
                          </VField>
                          <VField label="Obat:">
                            <VControl raw subcontrol>
                              <VInput class="input" v-model="input.anakObat"></VInput>
                            </VControl>
                          </VField>
                          <VField label="Lain-lain:">
                            <VControl raw subcontrol>
                              <VInput class="input" v-model="input.anakLainLain"></VInput>
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr>
                        <td width="25%" style="font-weight: 500;">
                          <div class="">
                            <VField label="Sirkulasi:">
                            </VField>
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.anakSirkulasi" class="p-0"
                                  true-value="Akral dingin,nadi cepat &lemah" label="Akral dingin,nadi cepat &lemah"
                                  color="primary" />
                              </VControl>
                            </VField>
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.anakSirkulasi" class="p-0"
                                  true-value="Akral dingin,CRT >3 detik" label="Akral dingin,CRT >3 detik"
                                  color="primary" />
                              </VControl>
                            </VField>
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.anakSirkulasi" class="p-0"
                                  true-value="Dehidrasi berat (KU lemah,mata cekung,turgor sangat menurun)"
                                  label="Dehidrasi berat (KU lemah,mata cekung,turgor sangat menurun)" color="primary" />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </Fieldset>
            <div clas="mt-5">
              <div class="column is-4">
                <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
                <VField class="mt-3">
                  <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
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
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-6 ">
                    <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-4">
                    <h1 class="p-0" style="font-weight: bold;">DPJP IGD</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.dpjpIGD" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="DPJP IGG..." class="mt-2"
                          @item-select="setTandaTangan($event, 'signature_1')" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 is-offset-2">
                    <h1 class="p-0" style="font-weight: bold;">PPJP IGD</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.ppjpIGD" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="PPJP IGD..." class="mt-2"
                          @item-select="setTandaTangan($event, 'signature_2')" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </VCard>
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
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/triase-igd'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let PasienDewasa = ref(EMR.PasienDewasa())
let PasienBayi = ref(EMR.PasienBayi())


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
const d_Petugas: any = ref([])
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
        if (response[0].tandaTanganDpjpIGD) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDpjpIGD)
        }
        if (response[0].tandaTanganPPJPIGD) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPPJPIGD)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.tandaTanganDpjpIGD = H.tandaTangan().get("signature_1")
  object.tandaTanganPPJPIGD = H.tandaTangan().get("signature_2")
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
const setTandaTangan = async (e: any, i: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_" + i, element.ttd)
    } else {
      H.tandaTangan().set("signature_" + i, '')
    }
  })
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
table.triase {
  border-collapse: collapse;
  width: 100%;
}

table.triase,
th,
.triase td {
  border: 1px solid black;
}

table.triase,
th {
  // text-align: center;

}

.bg-green {
  background-color: var(--primary);
}

.bg-warning {
  background-color: var(--warning);
}

.bg-danger {
  background-color: var(--danger);
}

.triase th,
td {
  padding: 8px;
  vertical-align: top !important;
}
</style>
