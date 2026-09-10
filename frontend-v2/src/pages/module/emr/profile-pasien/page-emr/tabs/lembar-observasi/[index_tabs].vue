<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }} {{ route.params.index_tabs }}</h3>
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
      <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
      <div class="columns is-multiline" v-else>
        <div class="column is-12">
          <VCard >
            <div class="column is-4">
              <span style="font-weight: 500;">Ruangan</span>
              <VField class="pt-3">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                    :field="'label'" placeholder="Cari Ruangan..." />
                </VControl>
              </VField>
            </div>
            <div class="column" style="overflow: auto;">
              <table class="table-lo">
                <thead>
                  <tr>
                    <th class="th-lo" width="2%">No</th>
                    <th class="th-lo" width="10%">Jam</th>
                    <th class="th-lo" width="10%">Tekanan Darah</th>
                    <th class="th-lo" width="8%">Nadi</th>
                    <th class="th-lo" width="8%">RR</th>
                    <th class="th-lo" width="8%">Suhu</th>
                    <th class="th-lo" width="8%">SPO2</th>
                    <th class="th-lo" width="8%">GCS</th>
                    <th class="th-lo" width="8%">Skala Nyeri</th>
                    <th class="th-lo" width="13%">Paraf</th>
                    <th class="th-lo">Keterangan</th>
                    <th class="th-lo">#</th>
                  </tr>
                </thead>
                <tbody v-for="(item, index) in input.details" :key="index">
                  <tr>
                    <td class="td-lo">
                      <span>1</span>
                    </td>
                    <td class="td-lo">
                      <VDatePicker class="p-3" v-model="item.jam" color="green" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl>
                              <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </td>
                    <td class="td-lo">
                      <VField class="p-3">
                        <VControl>
                          <VInput type="text" v-model="item.tekananDarah" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-lo">
                      <VField class="p-3">
                        <VControl>
                          <VInput type="text" v-model="item.nadi" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-lo">
                      <VField class="p-3">
                        <VControl>
                          <VInput type="text" v-model="item.rr" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-lo">
                      <VField class="p-3">
                        <VControl>
                          <VInput type="text" v-model="item.suhu" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-lo">
                      <VField class="p-3">
                        <VControl>
                          <VInput type="text" v-model="item.spo2" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-lo">
                      <VField class="p-3">
                        <VControl>
                          <VInput type="text" v-model="item.gcs" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-lo">
                      <VField class="p-3">
                        <VControl>
                          <VInput type="text" v-model="item.skalaNyeri" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-lo">
                      <VField class="p-3">
                        <VControl class="prime-auto">
                          <AutoComplete v-model="item.paraf" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas..." />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-lo">
                      <VField class="p-3">
                        <VControl>
                          <VTextarea v-model="item.keterangan" rows="2" placeholder="Keterangan...">
                          </VTextarea>
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-lo" style="vertical-align: inherit;">
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                        v-tooltip.bubble="'Tambah '">
                      </VIconButton>
                      <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                        @click="removeItem(index)" color="danger">
                      </VIconButton>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </VCard>
        </div>
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <div class="column is-8">
              <h1 style="font-weight: bold;">Bandung</h1>
              <VDatePicker class="pt-3" v-model="input.tglDibuat" color="green" mode="datetime">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl>
                      <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column pt-0">
              <h1 style="font-weight: bold;">Dokter</h1>
              <VField class="pt-3">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                    :field="'label'" placeholder="Cari Dokter..." />
                </VControl>
              </VField>
            </div>
          </VCard>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted,onBeforeMount } from 'vue'
import { useRoute, useRouter,onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const loadData :any = ref(true)
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
const d_Ruangan: any = ref([])
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
let input: any = ref({
  tglDibuat: new Date,
  details: [{
    no: 1,
    jam: new Date
  }]
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  loadData.value = true
  // if (NOREC_EMRPASIEN.value == '') return
  input.value = {
    tglDibuat: new Date,
    details: [{
      no: 1,
      jam: new Date
    }]
  }
    await  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
    loadData.value = false
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value

  if (route.params.index_tabs) {
    object.index_tabs = parseInt(route.params.index_tabs)
  }
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

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
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

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}
onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let rouutename = route.name + '' + route.params.index_tabs
    console.log(rouutename);

    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name + '' + route.params.index_tabs

    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});
watch(
  () => route.params.index_tabs,
  (newValue, oldValue) => {
    loadRiwayat()
    let rouutename = route.name + '' + route.params.index_tabs;
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`);
    if (cache) input.value = cache;
  })
setView()
setAutoFill()
loadRiwayat()
</script>


<style lang="scss">
.table-lo {
  width: 100%;
  border: 1px solid black;
}

.th-lo,
.td-lo {
  padding: 7px;
  border: 1px solid black;
}

.th-lo {
  text-align: center !important;
}
</style>
