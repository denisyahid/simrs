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
      <VCard>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-6">
              <Fieldset :toggleable="true" legend="Disis Oleh dokter pemeriksa">
                <div class="column is-12 p-2">
                  <div class="column is-12 p-0">
                    <VField label="Diagnosa Utama :">
                      <VControl>
                        <VInput v-model="input.diagnosaUtama" class="input" placeholder="Diagnosa utama">
                        </VInput>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12 p-0 my-4">
                    <h1 style="font-weight:bold" class="mb-3 emr">Prisipal Diagnosa</h1>
                  </div>
                  <div class="column is-12 p-0">
                    <VField label="Diagnosa Skunder :">
                      <VControl>
                        <VInput v-model="input.diagnosaSkunder" class="input" placeholder="Diagnosa skunder..">
                        </VInput>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12 mt-4 p-0" v-for="(item, index) in input.secondaryDiagnosa">
                    <div class="columns is-multiline">
                      <div class="column is-10">
                        <VField label="Secondary Diagnosa :">
                          <VControl>
                            <VInput v-model="item.secondaryDiagnosa" class="input" placeholder="Secondary Diagnosa...">
                            </VInput>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2 mt-5">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                          v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="ml-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItem(index)" color="danger">
                        </VIconButton>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 mt-4 p-0" v-for="(item, index) in input.tindakan">
                    <div class="columns is-multiline">
                      <div class="column is-10">
                        <VField label="Tindakan :">
                          <VControl>
                            <VInput v-model="item.tindakan" class="input" placeholder="Tindakan...">
                            </VInput>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2 mt-5">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemTindakan()"
                          color="info" v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="ml-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItemTindakan(index)" color="danger">
                        </VIconButton>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 mt-4 p-0">
                    <div class="mb-3">
                      <span>Pemeriksaan penunjang diagnosa</span><br>
                      <span class="p-2">Patalogi Klinik Patalogi anatomi radiologi,CT Scan,ECG,EEG,dll</span>
                    </div>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.pemeriksaanPenunjang" rows="3" placeholder="Pemeriksaan penunjang..">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12 mt-4 p-0">
                    <VField label="Terapi Obat / Resep :">
                      <VControl>
                        <VTextarea v-model="input.terapiObat" rows="3" placeholder="Terapi atau obat..">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-6">
              <Fieldset :toggleable="true" legend="Disis oleh koder rekam medis ICD 10">
                <div class="column is-12 p-2">
                  <div class="column is-12 p-0">
                    <VField label="Diagnosa utama :">
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.diagnosaUtamaICD" :suggestions="d_DiagnosaICD10"
                          @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Cari Diagnosa Utama..." class="mt-2" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12 p-0 my-4">
                    <h1 style="font-weight:bold" class="mb-3 emr">Prisipal Diagnosa</h1>
                  </div>
                  <div class="column is-12 p-0">
                    <VField label="Diagnosa  Sekunder :">
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.diagnosaSkunderICD" :suggestions="d_DiagnosaICD10"
                          @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Cari Diagnosa Sekunder..." class="mt-2" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12 p-0 mt-2" v-for="(item, index) in input.icdSecondaryDiagnosa">
                    <div class="columns is-multiline">
                      <div class="column is-10">
                        <VField label="Secondary Diagnosa :">
                          <VControl class="prime-auto">
                            <AutoComplete v-model="item.secondaryDiagnosaICD" :suggestions="d_DiagnosaICD10"
                              @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Secondary Diagnosa..." class="mt-2" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2 mt-5">
                        <VIconButton type="button" raised circle icon="feather:plus"
                          @click="addNewItemIcdSecondaryDiagnosa()" color="info" v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="ml-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItemIcdSecondaryDiagnosa(index)" color="danger">
                        </VIconButton>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 p-0 mt-2" v-for="(item, index) in input.icdTindakan">
                    <div class="columns is-multiline">
                      <div class="column is-10">
                        <VField label="Tindakan :">
                          <VControl class="prime-auto">
                            <AutoComplete v-model="item.tindakanICD" :suggestions="d_DiagnosaICD9"
                              @complete="fetchDiagnosaTindakan($event)" :optionLabel="'label'" :dropdown="true"
                              :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Tindakan..." class="mt-2" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2 mt-5">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemIcdTindakan()"
                          color="info" v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="ml-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItemIcdTindakan(index)" color="danger">
                        </VIconButton>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 mt-4 p-0">
                    <div class="mb-3">
                      <span>Pemeriksaan penunjang diagnosa</span><br>
                      <span class="p-2">Patalogi Klinik Patalogi anatomi radiologi,CT Scan,ECG,EEG,dll</span>
                    </div>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.pemeriksaanPenunjangICD" rows="3" placeholder="Pemeriksaan penunjang..">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-4">
              <VCard>
                <div class="column">
                  <span class="bold-sar">Bandung</span>
                  <VField class="column is-8 p-0 mt-3">
                    <VDatePicker v-model="input.tglDibuat" mode="dateTime" style="width: 100%" trim-weeks
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
                <div class="column" style="text-align: center;">
                  <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'" class="dek" />
                </div>
                <div class="column">
                  <span class="bold-sar">Dokter yang merawat</span>
                  <VField class="pt-3">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.dokterPerawat" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Dokter..." />
                    </VControl>
                  </VField>
                </div>
              </VCard>
            </div>
          </div>
        </div>
        <div class="column">
          <h1 style="font-weight:bold">Catatan:</h1>
          <div class="p-2">
            <div class="column is-12">
              <ul>
                <li> 1. Harap diisi lengkap dan jelas oleh dokter yang merawat</li>
                <li> 2. Diagnosa harus mengacu pada ICD 10</li>
                <li> 3. Prosedur / tindakan harus mengacu pada ICD 9</li>
                <li> 4. kode ICD 10 dan ICD 9 CM diisi oleh dokter/koder diSub Bagian Rekam Medis dan Hukum</li>
                <li> 5. Pengisian data ke Sub Bagian Rekam Medis dan hukum paling lambat 1x24 jam setelah selesai
                  pemeriksaan</li>
              </ul>
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
const d_Dokter: any = ref([])
const d_DiagnosaICD10: any = ref([])
const d_DiagnosaICD9: any = ref([])
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
  secondaryDiagnosa: [{ no: 1 }],
  tindakan: [{ no: 1 }],
  icdSecondaryDiagnosa: [{ no: 1 }],
  icdTindakan: [{ no: 1 }],
  tglDibuat: new Date()
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
        if (response[0].tandaTanganDokter) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDokter)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganDokter = H.tandaTangan().get("signature_1")
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


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchDiagnosa = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`
  ).then((response) => {
    d_DiagnosaICD10.value = response
  })
}
const fetchDiagnosaTindakan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/diagnosatindakan_m?select=kddiagnosatindakan,namadiagnosatindakan&param_search=kddiagnosatindakan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_DiagnosaICD9.value = response
  })
}
const addNewItem = () => {
  input.value.secondaryDiagnosa.push({
    no: input.value.secondaryDiagnosa[input.value.secondaryDiagnosa.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.secondaryDiagnosa.splice(index, 1)
}
const addNewItemTindakan = () => {
  input.value.tindakan.push({
    no: input.value.tindakan[input.value.tindakan.length - 1].no + 1,
  });
}
const removeItemTindakan = (index: any) => {
  input.value.tindakan.splice(index, 1)
}
const addNewItemIcdSecondaryDiagnosa = () => {
  input.value.icdSecondaryDiagnosa.push({
    no: input.value.icdSecondaryDiagnosa[input.value.icdSecondaryDiagnosa.length - 1].no + 1,
  });
}
const removeItemIcdSecondaryDiagnosa = (index: any) => {
  input.value.icdSecondaryDiagnosa.splice(index, 1)
}
const addNewItemIcdTindakan = () => {
  input.value.icdTindakan.push({
    no: input.value.icdTindakan[input.value.icdTindakan.length - 1].no + 1,
  });
}
const removeItemIcdTindakan = (index: any) => {
  input.value.icdTindakan.splice(index, 1)
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

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
</style>
