<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="column">
      <VCard>
        <div class="column is-12 p-2">
          <table class="tg">
            <thead>
              <tr>
                <td class="td-fkprj" colspan="2">
                  <div class="columns is-multiline">
                    <div class="column is-2" style="text-align: center;margin-top: 15px;">
                      <span>Tanggal / Jam</span>
                    </div>
                    <div class="column">
                      <VField class="mt-2">
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
                  </div>

                </td>
              </tr>
            </thead>
            <tbody>
              <tr class="tr-fkprj">
                <td class="td-fkprj" width="10%" style="text-align:center;vertical-align: inherit;">
                  <span style="font-size:35pt;font-weight:bold">S</span>
                </td>
                <td class="td-fkprj">
                  <VField>
                    <VControl>
                      <VTextarea rows="3" v-model="input.hasilSub">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr class="tr-fkprj">
                <td class="td-fkprj" width="10%" style="text-align:center">
                  <span style="font-size:35pt;font-weight:bold">O</span>
                </td>
                <td class="td-fkprj">
                  <VField>
                    <VControl>
                      <VTextarea rows="3" v-model="input.hasilObj">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr class="tr-fkprj">
                <td class="td-fkprj" width="10%" style="text-align:center;vertical-align: inherit;">
                  <span style="font-size:35pt;font-weight:bold">A</span>
                </td>
                <td class="td-fkprj">
                  <div class="column">
                    <span style="font-size:11pt;font-weight:bold">Diagnosis Keperawatan</span>
                    <div class="mt-1">
                      <table width="100%">
                        <thead>
                          <tr class="tr-fkprj">
                            <th class="td-fkprj" width="2%" style="vertical-align: inherit;text-align: center">NO</th>
                            <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">Diagnosa
                              Keperawatan
                            </th>
                            <th class="td-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;"
                              width="7%">#</th>
                          </tr>
                        </thead>
                        <tbody v-for="(items, index) in input.diagnosaKeper" :key="index">
                          <tr class="tr-fkprj">
                            <td class="td-fkprj" style="vertical-align:inherit;text-align:center">{{ items.no }}</td>
                            <td class="td-fkprj">
                              <div class="column p-1">
                                <VField>
                                  <VControl class="prime-auto">
                                    <AutoComplete v-model="items.diagnosaKeperawatan" :suggestions="d_DiagnosaKeperawatan"
                                      @complete="fetchDiagnosaKeperawatan($event)" :optionLabel="'caption'"
                                      :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                      :field="'caption'" placeholder="Cari Diagnosa Keperawatan ..." class="mt-2"
                                      @item-select="changeData(items.diagnosaKeperawatan)" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="td-fkprj" style="vertical-align: inherit;">
                              <div class="column p-0">
                                <VButtons style="justify-content: space-around;">
                                  <VIconButton type="button" raised circle icon="feather:plus"
                                    @click="addNewDiagnosaKeper()" color="info">
                                  </VIconButton>
                                  <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                    icon="feather:trash" @click="removeItemDiagnosaKeper(index)" color="danger">
                                  </VIconButton>
                                </VButtons>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div class="column">
                    <span style="font-size:11pt;font-weight:bold">Diagnosis Dokter</span>
                    <div style="overflow-y:auto;" class="mt-1">
                      <table width="120%">
                        <thead>
                          <tr class="tr-fkprj">
                            <th class="td-fkprj" width="2%" style="vertical-align: inherit;">NO</th>
                            <th class="td-fkprj" width="23%" style="vertical-align:inherit;text-align: center;">Jenis
                              Diagnosa
                            </th>
                            <th class="td-fkprj" width="25%" style="vertical-align:inherit;text-align: center;">Diagnosa
                              Dokter
                            </th>
                            <th class="td-fkprj" style="vertical-align:inherit;text-align: center;">Diagnosa ICD 10</th>
                            <th class="td-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;"
                              width="12%">
                              #
                            </th>
                          </tr>
                        </thead>
                        <tbody v-for="(items, index) in input.details" :key="index">
                          <tr class="tr-fkprj">
                            <td class="td-fkprj" style="vertical-align:inherit;text-align:center">{{ items.no }}</td>
                            <td class="td-fkprj">
                              <div class="column p-1">
                                <VField>
                                  <VControl class="prime-auto">
                                    <AutoComplete v-model="items.jenisDiagnosa" :suggestions="d_JenisDiagnosa"
                                      @complete="fetchJenisDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                      :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                      placeholder="Cari Jenis Diagnosa..." class="mt-2" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="td-fkprj">
                              <div class="column pt-3 pb-0">
                                <VField>
                                  <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="items.diagnosaDokter" placeholder="Diagnosa Dokter" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="td-fkprj">
                              <div class="column p-1">
                                <VField>
                                  <VControl class="prime-auto">
                                    <AutoComplete v-model="items.diagnosaIcd10" :suggestions="d_Diagnosa"
                                      @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                      :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                      placeholder="Cari Diagnosa ICD 10 ..." class="mt-2" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="td-fkprj" style="vertical-align: inherit;">
                              <div class="column p-0">
                                <VButtons style="justify-content: space-around;">
                                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                                    color="info">
                                  </VIconButton>
                                  <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                    icon="feather:trash" @click="removeItem(index)" color="danger">
                                  </VIconButton>
                                </VButtons>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                </td>
              </tr>
              <tr class="tr-fkprj">
                <td class="td-fkprj" width="10%" style="text-align:center;vertical-align: inherit;">
                  <span style="font-size:35pt;font-weight:bold">P</span>
                </td>
                <td class="td-fkprj">
                  <VField>
                    <VControl>
                      <VTextarea rows="3" v-model="input.hasilPe">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr class="tr-fkprj">
                <td class="td-fkprj" colspan="2">
                  <div class="columns is-multiline" v-if="kelompokUser == 'dokter'">
                    <div class="column is-2" style="text-align: center;margin-top: 10px;">
                      <span>DPJP</span>
                    </div>
                    <div class="column">
                      <VField class="is-autocomplete-select" v-slot="{ id }">
                        <VControl icon="feather:search">
                          <AutoComplete v-model="input.dokterDpjp" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Dokter" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="columns is-multiline" v-else>
                    <div class="column is-2" style="text-align: center;margin-top: 10px;">
                      <span>Perawat</span>
                    </div>
                    <div class="column">
                      <VField class="is-autocomplete-select" v-slot="{ id }">
                        <VControl icon="feather:search">
                          <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Perawat" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </td>

              </tr>
            </tbody>
          </table>
        </div>
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
const d_Diagnosa: any = ref([])
const d_JenisDiagnosa: any = ref([])
const d_DiagnosaKeperawatan: any = ref([])
const d_Dokter: any = ref([])
const dataTTD: any = ref([])
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
  details: [{ no: 1 }],
  diagnosaKeper: [{ no: 1 }],
})
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&kelompokPegawai=${kelompokUser}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}


const simpan = () => {
  let ID = input.value.userBy == kelompokUser && input.value.id ? input.value.id : ''
  // let userBy = input.value.userBy != kelompokUser 
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
    'userBy': kelompokUser,
    'data': object
  }
  console.log(json)
  debugger
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

const fetchDiagnosaKeperawatan = async (filter: any) => {
  await useApi().get(`/emr/get-dropdown-diagnosa-keperawatan?search=${filter.query}`).then((response) => {
    d_DiagnosaKeperawatan.value = response
  })
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchJenisDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/jenisdiagnosa_m?select=id,jenisdiagnosa&param_search=jenisdiagnosa&query=${filter.query}&limit=10`)
  d_JenisDiagnosa.value = response
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const addNewDiagnosaKeper = () => {
  input.value.diagnosaKeper.push({
    no: input.value.diagnosaKeper[input.value.diagnosaKeper.length - 1].no + 1,
  });
}
const removeItemDiagnosaKeper = (index: any) => {
  input.value.diagnosaKeper.splice(index, 1)
}
const kembaliKeun = () => {
  window.history.back()
}

const changeData = (e: any) => {
  console.log(e)
}
const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
}

const setAutoFillSO = async () => {

  await useApi().get(
    "emr/auto-fill?nocmfk=" + ID_PASIEN +
    "&collection=pengajianAwalRawatJalan" +
    "&field=alasanKunjunagn,biologi,hubunganPasien,ketLaporkan,statusPsikologisPasien,nadi,beratBadan,pernapasan,statusGizi,suhu,tekananDarah,tinggiBadan,totalSkor,adaDiagnosaKhusus"
  ).then((response) => {
    input.value.hasilSub = `Alasan Kunjungan : ${response.alasanKunjunagn}, Biologi : ${response.biologi}, Hubungan Pasien : ${response.hubunganPasien}, ` +
      `Dilaporkan ke : ${response.ketLaporkan}, Status Psikologis : ${response.statusPsikologisPasien}`
    input.value.hasilObj = `TD : ${response.tekananDarah} mmHg, Nadi : ${response.nadi} x/mnt, Napas : ${response.pernapasan} x/mnt, ` +
      `Suhu : ${response.suhu} °C, TB : ${response.tinggiBadan}, BB : ${response.beratBadan}, Gizi : ${response.statusGizi}, ` +
      `Skor : ${response.totalSkor}, Diagnosis Khusus : ${response.adaDiagnosaKhusus} `
    // input.value.beratBadan = response.beratBadan
    // input.value.tinggiBadan = response.tinggiBadan
    // input.value.IMT = response.IMT
    // input.value.lingkarPerut = response.lingkarPerut
    // input.value.tekananDarah = response.tekananDarah
    // input.value.pernapasan = response.pernapasan
    // input.value.suhu = response.suhu
    // input.value.nadi = response.nadi
  })

  
}




if (kelompokUser == 'perawat') {
  setAutoFillSO()
}

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

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
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
