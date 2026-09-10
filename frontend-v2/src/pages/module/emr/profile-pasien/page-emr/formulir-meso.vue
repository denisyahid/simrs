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
      <div class="column is-12" v-for="(datas) in formulir">
        <span class="label-meso" v-if="datas.title">{{ datas.title }}</span>
        <div class="columns is-multiline p-3">
          <div class="column is-4 pb-0" v-for="(data) in datas.detail">
            <VField v-if="datas.type == 'checkBox'">
              <VControl raw subcontrol>
                <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" class="p-0"
                  color="primary" square />
              </VControl>
            </VField>
            <span class="label-meso" v-if="datas.type == 'textBox'">{{ data.label }}</span>
            <VField class="pt-3" v-if="datas.type == 'textBox'">
              <VControl>
                <VInput type="text" v-model="input[data.model]" />
              </VControl>
            </VField>
            <span class="label-meso" v-if="datas.type == 'select'">{{ data.label }}</span>
            <VField class="pt-3" v-if="datas.type == 'select'">
              <VControl class="prime-auto">
                <AutoComplete v-model="input[data.model]" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Diagnosa..." />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12" v-for="(datas) in reaksiObat">
        <h1>{{ datas.title }}</h1>
        <div class="column" v-for="(data) in datas.detail">
          <span class="label-meso" v-if="data.subTitle">{{ data.subTitle }}</span>
          <div class="columns is-multiline" :class="data.subTitle ? 'p-3' : 'pl-3 pr-3 pb-3'">
            <div class="column" v-if="data.type == 'checkBox'" v-for="(pilihan) in data.value"
              :class="pilihan.code == 'GI' ? 'is-4' : 'is-3'">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.code" :label="pilihan.label" class="p-0"
                    color="primary" square />
                </VControl>
              </VField>
            </div>

            <div class="column is-3 pb-0" v-if="data.type == 'date'" v-for="(item) in data.value">
              <VDatePicker class="pt-3" v-model="input[item.model]" color="green" trim-weeks mode="datetime"
                :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }" class="pb-0">
                  <VField>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                        class="is-rounded_Z" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>

            <div class="column is-8 pb-0" v-if="data.type == 'textArea'" v-for="(items) in data.value">
              <VField>
                <VControl>
                  <VTextarea v-model="input[items.model]" rows="3" :placeholder="data.subTitle + '...'">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>

        </div>
      </div>

      <div class="column" style="overflow: auto;">
        <table class="table-fm">
          <thead>
            <tr>
              <th class="th-fm" colspan="8" style="text-align: center;">OBAT</th>
            </tr>
            <tr>
              <th rowspan="2" class="th-fm" style="vertical-align: inherit;">Nama (Nama Dagang Pabrik)</th>
              <th rowspan="2" class="th-fm" style="text-align: center;vertical-align: inherit;">Bentuk Sediaan</th>
              <th rowspan="2" class="th-fm" width="7%">Beri Tanda Untuk Obat Yang Dicurigai</th>
              <th colspan="4" class="th-fm" style="text-align: center;">Pemberian</th>
              <th rowspan="2" class="th-fm" style="text-align: center;vertical-align: inherit;">Indikasi(Penggunaan)</th>
              <th rowspan="2" class="th-fm" style="text-align: center;vertical-align: inherit;">#</th>
            </tr>
            <tr>
              <th class="th-fm" style="text-align: center;vertical-align: inherit;">Rute</th>
              <th class="th-fm" style="text-align: center;vertical-align: inherit;">Dosis/Waktu</th>
              <th class="th-fm" style="text-align: center;vertical-align: inherit;">Tgl Mulai</th>
              <th class="th-fm" style="text-align: center;vertical-align: inherit;">Tgl Akhir</th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in input.details" :key="index">
            <tr>
              <td class="td-fm">
                <VField class="p-3">
                  <VControl>
                    <VInput type="text" v-model="item.namaDagangan" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fm">
                <VField class="p-3">
                  <VControl>
                    <VInput type="text" v-model="item.bentukSediaan" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fm">
                <VField class="p-3" style="text-align: center;">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.diCurigai" true-value="Iya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </td>
              <td class="td-fm">
                <VField class="p-3">
                  <VControl>
                    <VInput type="text" v-model="item.rute" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fm">
                <VField class="p-3">
                  <VControl>
                    <VInput type="text" v-model="item.dosis" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fm">
                <VDatePicker class="p-3" v-model="item.tglMulai" color="green" trim-weeks mode="date"
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                          class="is-rounded_Z" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-fm">
                <VDatePicker class="p-3" v-model="item.tglSelesai" color="green" trim-weeks mode="date"
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                          class="is-rounded_Z" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-fm">
                <VField class="p-3">
                  <VControl>
                    <VTextarea v-model="item.indikasi" rows="2" placeholder="Indikasi...'">
                    </VTextarea>
                  </VControl>
                </VField>
              </td>
              <td class="td-fm" style="vertical-align: inherit;">
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
      <div class="column" v-for="(datas) in reaksi">
        <span class="label-meso">{{ datas.title }}</span>
        <div class="columns is-multiline p-3">
          <div class="column is-3" v-for="(data) in datas.value">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input[data.model]" :true-value="data.label" class="p-0" :label="data.label"
                  color="primary" circle />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <span class="label-meso">Garut</span>
        <div class="column is-4">
          <span class="label-meso">Nama</span>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.pegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Cari Pegawai..." />
            </VControl>
          </VField>
          <div class="columns is-multiline p-3">
            <div class="column">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.pegawaiSebagai" true-value="Dokter" class="p-0" label="Dokter" color="primary"
                    circle />
                </VControl>
              </VField>
            </div>
            <div class="column">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.pegawaiSebagai" true-value="Perawat" class="p-0" label="Perawat"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.pegawaiSebagai" true-value="Farmasi" class="p-0" label="Farmasi"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column p-0">
            <span class="label-meso">Asal Ruangan/Poliklinik</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.ruanganAsal" disabled
                  style="font-weight: bold !important;background: darkgrey;" :optionLabel="'label'" :dropdown="true"
                  :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="Cari Ruangan..." />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <Fieldset :toggleable="true" legend="SKOR ALGORITMA NARANJO">
          <span class="label-meso">Digunakan untuk menilai kemungkinan bahwa perubahan status klinik pasien sebagai akibat
            efek samping
            obat(ESO)</span>
          <div class="column">
            <span class="label-meso">Cara</span>
            <div class="column">
              <table border="1px" width="100%">
                <tr v-for="(data) in skorAlgoritma">
                  <th border="1px" style="padding:7px">{{ data.no }}</th>
                  <th border="1px" style="padding:7px">{{ data.value }}</th>
                </tr>
                <tr>
                  <th></th>
                  <th>
                    <table border="1px" width="100%">
                      <tr v-for="(data) in skor">
                        <th border="1px" width="45%">{{ data.title }}</th>
                        <th>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.descSkor" :true-value="data.value" class="p-0"
                                :label="data.value.keterangan" color="primary" circle disabled />
                            </VControl>
                          </VField>
                        </th>
                      </tr>
                    </table>
                  </th>
                </tr>
              </table>
            </div>
          </div>

          <div class="column">
            <table border="1px" width="100%">
              <thead>
                <tr>
                  <th border="1px" style="padding:7px;text-align:center" width="5%">NO</th>
                  <th border="1px" style="padding:7px;text-align:center" width="65%">PERTANYAAN ALGORITMA</th>
                  <th border="1px" style="padding:7px;text-align:center">SKALA</th>
                  <th border="1px" style="padding:7px;text-align:center" width="8%">SKOR</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data) in pertanyaan">
                  <td border="1px" style="padding:7px;text-align: center;">
                    <span class="label-meso">{{ data.no }}</span>
                  </td>
                  <td border="1px" style="padding:7px">
                    <span class="label-meso">{{ data.pertanyaan }}</span>
                  </td>
                  <td border="1px" style="padding:7px">
                    <div class="column pt-0 pb-0 mb-1" v-for="(pilihan) in data.skala">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.value" class="p-0"
                            :label="pilihan.label" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                  <td border="1px" style="padding:7px;text-align: center;">
                    <div class="column pt-0 pb-0 mb-1" v-for="(nilai) in data.skor">
                      <span class="label-meso">{{ nilai }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="3" style="vertical-align: inherit;">
                    <div class="column is-2" style="margin-left:auto ;">
                      <span style="font-weight: bold;">TOTAL SKOR</span>
                    </div>
                  </td>
                  <td>
                    <div class="column">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.totalSkor" disabled />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </Fieldset>
      </div>

    </VCard>

    <div class="column is-3 p-0 mt-5" style="margin-left: auto;">
      <VCard>
        <div class="column">
          <h1 style="font-weight: bold;">Garut</h1>
          <VDatePicker v-model="input.tglDibuat" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column">
          <h1>Pelapor</h1>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.pelapor" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Pelapor..." />
              </VControl>
            </VField>
        </div>
      </VCard>
    </div>

    <div class="column p-0 mt-5">
      <VCard style="text-align: center;">
        <span class="label-meso">Kirimkan formulir yang sudah diisi kepada : Sekretaris Tim Farmasi dan Terapi d/a Instalasi Farmasi Rumah Sakit ini</span>
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
import * as EMR from '../page-emr-plugins/formulir-meso'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let formulir = ref(EMR.formulir())
let reaksiObat = ref(EMR.reaksiObat())
let reaksi = ref(EMR.reaksi())
let skorAlgoritma = ref(EMR.skorAlgoritma())
let skor = ref(EMR.skor())
let pertanyaan = ref(EMR.pertanyaan())

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
const d_Diagnosa: any = ref([])
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref(
  {
    details: [{
      no: 1,
    }]
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

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.ruanganAsal = props.registrasi.namaruangan
}

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const skorDescription = (e: any) => {

  let sangatMungkin = {
    "keterangan": "Sangat mungkin ESO (High Probable)",
    "poin": 9
  }
  let mungkin = {
    "keterangan": "Mungkin ESO(Probable)",
    "poin": 8
  }
  let dicurigai = {
    "keterangan": "Dicurigai ESO(Possible)",
    "poin": 4
  }
  let diragukan = {
    "keterangan": "Diragukan ESO(Doubtful)",
    "poin": 0
  }

  skor.value.forEach((element: any) => {
    if (e <= 0 && e <= element.value.poin) {
      input.value.descSkor = diragukan
    } else if (e <= 4 && e <= element.value.poin) {
      input.value.descSkor = dicurigai
    } else if (e <= 8 && e <= element.value.poin) {
      input.value.descSkor = mungkin
    } else if (e >= 9 && e >= element.value.poin) {
      input.value.descSkor = sangatMungkin
    }
  });

}


setView()
setAutoFill()
loadRiwayat()


watch(() => [
  input.value.penelitianSebelumnya,
  input.value.reaksiObatDicurigai,
  input.value.berkurangSaatBerhenti,
  input.value.munculKembali,
  input.value.penyebabAlternatif,
  input.value.munculKembaliSaatPlacebo,
  input.value.terdeteksiDalamDarah,
  input.value.reaksiLebihBerat,
  input.value.reaksiMirip,
  input.value.reaksiDikonfirmasi,
  // input.value.descSkor
], () => {
  let poin1 = input.value.penelitianSebelumnya ? parseInt(input.value.penelitianSebelumnya.poin) : 0
  let poin2 = input.value.reaksiObatDicurigai ? parseInt(input.value.reaksiObatDicurigai.poin) : 0
  let poin3 = input.value.berkurangSaatBerhenti ? parseInt(input.value.berkurangSaatBerhenti.poin) : 0
  let poin4 = input.value.munculKembali ? parseInt(input.value.munculKembali.poin) : 0
  let poin5 = input.value.penyebabAlternatif ? parseInt(input.value.penyebabAlternatif.poin) : 0
  let poin6 = input.value.munculKembaliSaatPlacebo ? parseInt(input.value.munculKembaliSaatPlacebo.poin) : 0
  let poin7 = input.value.terdeteksiDalamDarah ? parseInt(input.value.terdeteksiDalamDarah.poin) : 0
  let poin8 = input.value.reaksiLebihBerat ? parseInt(input.value.reaksiLebihBerat.poin) : 0
  let poin9 = input.value.reaksiMirip ? parseInt(input.value.reaksiMirip.poin) : 0
  let poin10 = input.value.reaksiDikonfirmasi ? parseInt(input.value.reaksiDikonfirmasi.poin) : 0

  const total = poin1 + poin2 + poin3 + poin4 + poin5 + poin6 + poin7 + poin8 + poin9 + poin10
  // console.log(input.value.descSkor)
  skorDescription(total)
  input.value.totalSkor = total

})
</script>

<style lang="scss">
.label-meso {
  font-weight: 500;
}

.table-fm {
  border: 1px solid !important;
  border-collapse: collapse !important;
  width: 100% !important;
}

.td-fm,
.th-fm {
  border: 1px solid !important;
  padding: 7px !important;
}

.td-fm {
  vertical-align: inherit;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
