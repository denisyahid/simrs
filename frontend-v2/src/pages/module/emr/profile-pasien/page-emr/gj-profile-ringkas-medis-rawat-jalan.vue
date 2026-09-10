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

      <div class="column is-12">
        <VField>
          <h1 style="font-weight: bold;">Riwayat Alergi : </h1>
          <VControl class="mt-2">
            <VTextarea rows="3" placeholder="Tulis Riwayat Alergi..." v-model="input.riwayatAlergi"></VTextarea>
          </VControl>
        </VField>
      </div>

      <div class="column is-12">
        <table class="table-fkprj mt-3">
          <thead>
            <tr class="tr-fkprj">
              <th class="th-fkprj" width="2%" rowspan="2" style="vertical-align: inherit;">NO</th>
              <th class="th-fkprj" style="text-align: center;">Tanggal</th>
              <th class="th-fkprj" style="text-align: center;">Diagnosis</th>
              <th class="th-fkprj" style="text-align: center;">Pemeriksaan Penunjang</th>
              <th class="th-fkprj" style="text-align: center;">Terapi/Obat-Obatan</th>
              <th class="th-fkprj" style="text-align: center;">PPA (Nama & TDD)</th>
              <th class="th-fkprj" style="vertical-align:inherit;text-align: center;" width="2%">#
              </th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in input.diagnosaUtama" :key="index">
            <tr class="tr-fkprj">
              <td class="td-fkprj">{{ item.no }}</td>
              <td class="td-fkprj">
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
              </td>
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
              <td class="td-fkprj">
                <VField class="mt-2">
                  <VControl>
                    <VTextarea rows="3" v-model="item.pemeriksaanPenunjang" placeholder="pemerikasaan penunjang">
                    </VTextarea>
                  </VControl>
                </VField>
              </td>
              <td class="td-fkprj">
                <VField class="mt-2">
                  <VControl>
                    <VTextarea rows="3" v-model="item.terapiObat" placeholder="Terapi/Obat-Obatan	">
                    </VTextarea>
                  </VControl>
                </VField>
              </td>
              <td class="td-fkprj">
                <TandaTangan :elemenID="'signature_' + [index]" :width="'150'" :height="'150'" class="dek" />
                <!-- <TandaTangan class="mt-2" :elemenID="'signature_' + [index]" :width="'180'" :height="'180'"></TandaTangan> -->
                <VField class="is-autocomplete-select mt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.perawatOK" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama petugas"
                      @item-select="setTandaTangan($event, 'signature_1')" />
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


    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'

const dataTTD: any = ref([])
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
const d_Pegawai: any = ref([])
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
  diagnosaUtama: [{ no: 1 }]
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0]
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk

        }
        dataTTD.value = response[0].diagnosaUtama
      }
    })
  dataTTD.value.forEach((element: any, i: any) => {
    H.tandaTangan().set("signature_" + [i], element.ttd)
  })
}

// const loadRiwayat = async () => {
//   await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
//     if (response.length > 0) {
//       input.value = response[0]
//       dataTTD.value = response[0].details

//       if (NOREC_EMRPASIEN.value == '') {
//         NOREC_EMRPASIEN.value = response[0].emrpasienfk
//       }
//     }
//   })
//   dataTTD.value.forEach((element: any, i: any) => {
//     console.log(element.ttd);
//     H.tandaTangan().set("signature_" + [i], element.ttd)
//   })
// }

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

  json.data.diagnosaUtama.forEach((element: any, i: any) => {
    element.ttd = H.tandaTangan().get("signature_" + [i])
  });
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
// const fetchDiagnosaTindakan = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/diagnosatindakan_m?select=kddiagnosatindakan,namadiagnosatindakan&param_search=kddiagnosatindakan&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_DiagnosaICD9.value = response
//   })
// }


const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const setTandaTangan = async (e: any, i: any) => {
  // await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
  //   if (element) {
  //     H.tandaTangan().set("signature_" + i, element.ttd)
  //   } else {
  //     H.tandaTangan().set("signature_" + i, '')
  //   }
  // })
}



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
  input.value.norm = dataPasien.nocm
  input.value.nama = dataPasien.namapasien
  input.value.tgllahir = dataPasien.tgllahir
  input.value.alamat = dataPasien.alamatlengkap
  input.value.poliklinik = dataRegis.namaruangan
  input.value.umur = dataPasien.umur
  interface JenisKelaminOption {
    value: string;
  }
  JenisKelamin.value.forEach((element: JenisKelaminOption) => {
    if (element.value == dataPasien.jeniskelamin) {
      input.value.jenisKelamin = element.value;
    }
  });

  input.value.tglPembuatan = new Date()
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
