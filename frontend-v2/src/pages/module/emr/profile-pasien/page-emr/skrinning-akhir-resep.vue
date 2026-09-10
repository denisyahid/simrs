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
      <div class="column is-3">
        <span class="bold-sar">No Resep</span>
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="item.noresep" />
          </VControl>
        </VField>
      </div>
      <div class="column">
        <table class="table-sar">
          <thead>
            <tr>
              <th class="th-sar">No</th>
              <th class="th-sar">Rincian</th>
              <th class="th-sar">Sesuai</th>
              <th class="th-sar">Tidak Sesuai</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(data) in rincian">
              <td class="td-sar" style="text-align: center;">{{ data.no }}</td>
              <td class="td-sar">{{data.rincian }}</td>
              <td class="td-sar" style="text-align: center;">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.sesuai.model]" :true-value="data.sesuai.value" class="p-0"
                      color="primary" squer />
                  </VControl>
                </VField>
              </td>
              <td class="td-sar" style="text-align: center;">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.tidakSesuai.model]" :true-value="data.tidakSesuai.value" class="p-0"
                      color="primary" squer />
                  </VControl>
                </VField>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </VCard>
  </div>

  <div class="column is-4">
    <VCard>
      <div class="column">
        <span class="bold-sar">Tanggal & Jam Skrinning</span>
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
        <TandaTangan :elemenID="'signaturePetugas'" :width="'180'" :height="'180'" class="dek" />
      </div>
      <div class="column">
        <span class="bold-sar">Nama Petugas Skrinning</span>
        <VField class="pt-3">
          <VControl class="prime-auto">
            <AutoComplete v-model="input.petugasSkrining" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" placeholder="Cari Petugas..." @item-select="setTandaTanganPetugas($event)" />
          </VControl>
        </VField>
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
// import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/skrinning-akhir-resep'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let rincian = ref(EMR.rincian())


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
const input: any = ref({
  tglDibuat : new Date()
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
        if (response[0].ttdPetugas) {
          H.tandaTangan().set("signaturePetugas", response[0].ttdPetugas)
        }
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
  object.ttdPetugas = H.tandaTangan().get("signaturePetugas")
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

const setTandaTanganPetugas = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signaturePetugas", element.ttd)
      }else{
        H.tandaTangan().set("signaturePetugas", '')
      }
    })
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
.bold-sar {
  font-weight: bold;
}

.table-sar {
  width: 100%;
  border: 1px solid black;
}

.th-sar,
.td-sar {
  border: 1px solid black;
  padding: 5px;
}

.th-sar {
  text-align: center !important;
}
</style>
