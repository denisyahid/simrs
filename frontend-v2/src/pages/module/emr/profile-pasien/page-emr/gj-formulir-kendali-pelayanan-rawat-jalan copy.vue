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
    <VCard>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-3" style="margin-top:0.5rem">
            <h1 style="font-weight: bold;"> Nama : </h1>
          </div>
          <div class="column is-6">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="Nama " v-model="input.nama" />
              </VControl>

            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-3" style="margin-top:0.5rem">
            <h1 style="font-weight: bold;">No. Rekam Medis : </h1>
          </div>
          <div class="column is-6">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="Nama " v-model="input.norm" />
              </VControl>

            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-3" style="margin-top:0.5rem">
            <h1 style="font-weight: bold;"> Tanggal Lahir / Umur : </h1>
          </div>
          <div class="column is-3">
            <VDatePicker v-model="input.tgllahir" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date"
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div style="align-self: center;">
            <h1 style="font-weight: bold;">/</h1>
          </div>
          <div class="column is-3">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.umur" />
              </VControl>

            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-3" style="margin-top:0.3rem">
            <h1 style="font-weight: bold;">Jenis Kelamin : </h1>
          </div>
          <div class="columns is-6" style="margin-top:.5rem;margin-left:0px">
            <VField v-for="items in JenisKelamin" :key="items.value" style="padding:0px;">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jenisKelamin" class="pt-1 pb-1 " :true-value="items.value" :label="items.label"
                  color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-3" style="margin-top:0.5rem">
            <h1 style="font-weight: bold;">Alamat : </h1>
          </div>
          <div class="column is-6">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.alamat" />
              </VControl>

            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-3" style="margin-top:0.5rem">
            <h1 style="font-weight: bold;">Poliklinik : </h1>
          </div>
          <div class="column is-6">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.poliklinik" />
              </VControl>

            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0 mt-3">
        <h1 style="font-weight: bold;">Diisi Dokter yang memeriksa : </h1>
      </div>

      <div class="column is-12">
        <div class="column is-12 p-0">
          <h1 style="font-weight: bold;">Diagnosa utama : </h1>
          <table class="table-fkprj mt-3">
            <thead>
              <tr class="tr-fkprj">
                <th class="th-fkprj" width="2%" rowspan="2" style="vertical-align: inherit;">NO</th>
                <th class="th-fkprj" style="text-align: center;">ICD 10</th>
                <th class="th-fkprj" style="vertical-align:inherit;text-align: center;" width="2%">#
                </th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.diagnosaUtama" :key="index">
              <tr class="tr-fkprj">
                <td class="td-fkprj">{{ item.no }}</td>
                <td class="td-fkprj">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.diagnosaUtama" :suggestions="d_DiagnosaICD10"
                        @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Cari Diagnosa Utama..." class="mt-2" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fkprj" style="vertical-align: inherit    ;">
                  <div class="column">
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                      v-tooltip.bubble="'Tambah '">
                    </VIconButton>
                    <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                      @click="removeItem(index)" color="danger">
                    </VIconButton>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
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
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'

const JenisKelamin: any = ref([
  { label: 'Laki - Laki', value: 'Laki-Laki' },
  { label: 'Perempuan', value: 'Perempuan' }
])


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
const d_DiagnosaICD10: any = ref([])
const input: any = ref({
  diagnosaUtama: [{ no: 1, }]
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

const addNewItem = () => {
  input.value.diagnosaUtama.push({
    no: input.value.diagnosaUtama[input.value.diagnosaUtama.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.diagnosaUtama.splice(index, 1)
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

  let dataPasien = H.setObjectPasien(props.pasien)
  let dataRegis = H.setObjectRegistrasi(props.registrasi)
  console.log(dataRegis)
  input.value.norm = dataPasien.nocm
  input.value.nama = dataPasien.namapasien
  input.value.tgllahir = dataPasien.tgllahir
  input.value.alamat = dataPasien.alamatlengkap
  input.value.poliklinik = dataRegis.namaruangan
  input.value.umur = dataPasien.umur
  JenisKelamin.value.forEach(element => {
    if (element.value == dataPasien.jeniskelamin) {
      input.value.jenisKelamin = element.value
    }
  });
}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.table-fkprj {
  width: 100% !important;
  border-collapse: collapse !important;
}

.table-fkprj,
.tr-fkprj,
.th-fkprj,
.td-fkprj {
  border: 1.6px solid black !important;
}

.th-fkprj,
.td-fkprj {
  padding: 8px !important;
}
</style>
