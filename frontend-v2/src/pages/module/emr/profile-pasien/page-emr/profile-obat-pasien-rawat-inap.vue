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
      <div class="column" style="overflow: auto;">
        <table class="table-popri">
          <thead>
            <tr>
              <th class="th-popri" width="3%">No</th>
              <th class="th-popri">Nama Obat, Kekuatan, Bentuk Sediaan</th>
              <th class="th-popri">Dosis, Aturan Pakai, Rute Pemberian</th>
              <th class="th-popri" width="10%">TGL Mulai</th>
              <th class="th-popri" width="10%">TGL Stop</th>
              <th class="th-popri" width="10%">TGL Pemberian</th>
              <th class="th-popri" width="8%">Waktu Pemberian</th>
              <th class="th-popri">Nama / Paraf Farmasi</th>
              <th class="th-popri">Nama / Paraf Perawat</th>
              <th class="th-popri">#</th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in input.details" :key="index">
            <tr>
              <td class="td-popri" style="text-align: center; ">
                <span>1</span>
              </td>
              <td class="td-popri">
                <VField>
                  <VControl>
                    <VTextarea v-model="item.obatNamaKekuatan" rows="3">
                    </VTextarea>
                  </VControl>
                </VField>
              </td>
              <td class="td-popri">
                <VField>
                  <VControl>
                    <VTextarea v-model="item.dosisAturPakai" rows="3">
                    </VTextarea>
                  </VControl>
                </VField>
              </td>
              <td class="td-popri">
                <VDatePicker v-model="item.tglMulai" mode="date" style="width: 100%" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-popri">
                <VDatePicker v-model="item.tglStop" mode="date" style="width: 100%" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-popri">
                <VDatePicker v-model="item.tglPemberian" mode="date" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-popri">
                <VDatePicker v-model="item.waktuPemberian" color="green" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl>
                        <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-popri">
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="item.farmasi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Farmasi..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-popri">
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="item.perawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Perawat..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-popri">
                <VButtons style="justify-content: space-around;">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                    v-tooltip.bubble="'Tambah '">
                  </VIconButton>
                  <VIconButton v-if="index > 0" class="mt-1" type="button" raised circle icon="feather:trash"
                    @click="removeItem(index)" color="danger">
                  </VIconButton>
                </VButtons>
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
  details: [{
    no: 1,
    waktuPemberian : new Date()
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
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
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

}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.table-popri {
  width: 150%;
  border: 1px solid black;
}

.th-popri {
  text-align: center !important;
}

.th-popri,
.td-popri {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setpopri-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
