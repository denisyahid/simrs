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
            <div class="column is-4">
              <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Kode</h1>
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="" v-model="input.kode" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Diagnosa</h1>
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="" v-model="input.diagnosis" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12" v-for="(datas) in prosedur">
          <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
          <div class="columns is-multiline">
            <div class="column is-3" v-for="(data) in datas.value">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle" :label="data.subTitle" class="p-0"
                    color="primary" square />
                </VControl>
              </VField>


            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField>
                <h1 style="font-weight: bold;">Tanggal Operasi</h1>
              </VField>
              <VField>
                <VDatePicker v-model="input.tanggalOperasi" mode="dateTime" style="width: 100%" trim-weeks
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
            <div class="column is-4">
              <h1 style="font-weight:bold;">Jam Mulai Operasi</h1>
              <VDatePicker class="column is-8" v-model="input.jamMasuk" color="green" mode="time" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:clock">
                      <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4" style="margin-left: -4rem;">
              <h1 style="font-weight:bold;">Jam Selesai Operasi</h1>
              <VDatePicker class="column is-7" v-model="input.jamSelesai" color="green" mode="time" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:clock">
                      <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>

          </div>
        </div>
        <div class="column is-12" v-for="(datas) in pemberian">
          <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
          <div class="columns is-multiline">
            <div class="column is-3" v-for="(data) in datas.value">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle" :label="data.subTitle" class="p-0"
                    color="primary" square />
                </VControl>
              </VField>


            </div>
          </div>

        </div>
        <div class="column is-12 mt-3">
          <h1 style="font-weight: bold; margin-bottom: 1rem;"> Jika Ya, isi kolom dengan lengkap </h1>
          <table class="tm">
            <thead>
              <tr>
                <td class="tm-0lax">Nama Antibiotik Profilaksist</td>
                <td class="tm-0lax">Waktu Pemberian (Jam)</td>
                <td class="tm-0lax">Cara Pemberian (IV drio. IV Bolus)</td>
                <td class="tm-0lax">Dosis (mg, gr)</td>
                <td class="tm-0lax">#</td>

              </tr>
            </thead>
            <tbody v-for="(item, index) in input.details" :key="index">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="item.namaObat" placeholder="Nama Obat" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="item.jamPemberian" type="time"/>
                    </VControl>
                  </VField>
                </td>

                <td style="width:25%">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="item.Cara" placeholder="Rute" />
                    </VControl>
                  </VField>
                </td>
                <td style="width:25%">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="item.dosis" placeholder="Rute" />
                    </VControl>
                  </VField>
                </td>
                <td rowspan="2" style="width:13%;vertical-align: middle;">
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                        v-tooltip.bubble="'Tambah '">
                      </VIconButton>
                    </div>
                    <div class="column is-6 ml-3-min">
                      <VIconButton v-if="index > 0" type="button" raised circle icon="feather:trash"
                        @click="removeItem(index)" color="danger">
                      </VIconButton>
                    </div>
                  </div>
                </td>
              </tr>



            </tbody>
          </table>

        </div>
        <div class="column is-8">
          <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Catatan</h1>
          <VField>
            <VControl>
              <VTextarea v-model="input.catatan" rows="2">
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </VCard>
    </div>
    <div class="column is-4 mt-3" style="margin-left: auto;">
      <VCard class="border-card pink">
        <div class="column is-12">
          <VField>
            <h1 style="font-weight: bold;">Tanggal / Jam</h1>
          </VField>
          <VField>
            <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
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
        <div class="column pt-0">
          <VField>
            <h1 style="font-weight: bold;">Perawat</h1>
          </VField>
          <VField class="is-autocomplete-select" v-slot="{ id }">
            <VControl icon="feather:search">
              <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="ketik nama petugas" />
            </VControl>
          </VField>
        </div>
        <div class="column pt-0">
          <VField>
            <h1 style="font-weight: bold;">Dokter</h1>
          </VField>
          <VField class="is-autocomplete-select" v-slot="{ id }">
            <VControl icon="feather:search">
              <AutoComplete v-model="input.dokter" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="ketik nama petugas" />
            </VControl>
          </VField>
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
import { useViewWrapper } from '/@src/stores/viewWrapper'
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
const d_Pegawai: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})

let prosedur = ref([
  {
    "title": "Nama Prosedur Operasi",
    "value": [
      {
        "subTitle": "Bersih",
        "type": "checkBox",
        "model": "prosedurOperasi_",
      },
      {
        "subTitle": "Bersih Terkontaminasi",
        "type": "checkBox",
        "model": "prosedurOperasi_",
      },
      {
        "subTitle": "Terkontaminasi",
        "type": "checkBox",
        "model": "prosedurOperasi_",
      },
      {
        "subTitle": "Kotor",
        "type": "checkBox",
        "model": "prosedurOperasi_",
      },
    ]
  }
])

let pemberian = ref([
  {
    "title": "Pemberian Antibiotik Profilaksis",
    "value": [
      {
        "subTitle": "Bersih",
        "type": "checkBox",
        "model": "pemberian_",
      },
      {
        "subTitle": "Bersih Terkontaminasi",
        "type": "checkBox",
        "model": "pemberian_",
      }
    ]
  }
])
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggalOperasi: new Date(),
  jamMasuk: new Date(),
  jamSelesai: new Date(),
  jamPemberian: new Date(),
  tanggal: new Date(),
  details: [{
    no: 1,
    tgl: new Date(),
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

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
    tgl: new Date(),
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.diagnosa = props.registrasi.diagnosis

}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.tm {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;

}

.tm td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 3px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tm th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 3px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tm .tg-0lax {
  text-align: left;
  vertical-align: top
}
</style>
