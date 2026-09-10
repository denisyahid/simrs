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
      <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
      <VCard v-else>
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField label="Tgl/Jam Kunjungan">
                      <VControl class="prime-auto">
                        <Calendar  v-model="input.tanggalKunjungan" selectionMode="single" :manualInput="false"
                          class="w-100 mb-4 " :showIcon="true"  :dateFormat="H.dateTimeFormat().prime.date" inputId="single"  :hideOnRangeSelection="true"  />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField class="is-autocomplete-select" label="DPJP">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="input.dokterDPJP" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                          :field="'label'" placeholder="ketik untuk mencari.." />
                          </VControl>
                    </VField>
                  </div>
              </div>
            </VCard>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" :legend="'Pengkajian Awal Rawat Inap Neonatus'">
              <div class="columns is-multiline p-2">
                <div :class="'column ' + data.column" :key="index" v-for="(data,index) in PENGKAJIANAWAL">
                   <VField :label="data.label" v-if="data.type == 'text'">
                    <VInput v-model="input[data.model]" v-if="data.type == 'input'"></VInput>
                   </VField>
                    <VField class="is-rounded-select is-autocomplete-select" :label="data.label" v-if="data.type == 'autocomplete'">
                        <VControl icon="feather:search" class="prime-auto-select">
                          <AutoComplete v-model="input[data.model]" :suggestions="d_Ruangan"
                            @complete="fetchRuangan($event)" :optionLabel="'label'"
                            :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Ketik untuk mecari..." />
                         </VControl>
                    </VField>
                   <div v-else class="columns is-multiline">
                    <div class="column is-12">
                      <VField :label="data.label"></VField>
                    </div>
                    <div  :class="['column ', data2.column || 'is-2']"  v-for=" (data2,index2) in data.children" :key="index2">
                        <VField>
                          <VControl>
                            <VCheckbox  class="p-0" color="primary" square :true-value="data2.value" :label="data2.value"
                              v-model="input[data2.model]" @change="handleCheckboxChange(data2)"></VCheckbox>
                          </VControl>
                        </VField>
                        <div  v-for="(data3, index3) in data2.children" :key="index3" :class="['column', data3.column || 'is-12']">
                          <VField v-if="data3.isHide">
                            <VControl>
                              <VInput v-model="input[data3.model]" :placeholder="data3.label"></VInput>
                            </VControl>
                          </VField>
                        </div>
                    </div>
                   </div>
                </div>
              </div>
            </Fieldset>
          </div>
            <div class="column is-12">
              <Fieldset :toggleable="true" :legend="'I. Anamnesis'">
                <div class="columns is-multiline p-2">
                  <div class="column is-6" v-for="(data,index) in ANAMNESIS" :key="index">
                    <VField :label="data.label">
                      <VControl>
                        <VInput v-model="input[data.model]" :placeholder="data.label"></VInput>
                      </VControl>
                     </VField>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-12">
              <Fieldset :toggleable="true" :legend="'Riwayat Biopsikososial, Kultural, Spiritual & Ekonomi'">
                <div :class="'column ' + data.column" :key="index" v-for="(data,index) in RIWAYATBIOPSIKOSOSISAL">
                   <VField :label="data.label" v-if="data.type == 'text'">
                    <VInput v-model="input[data.model]"></VInput>
                   </VField>
                   <div v-else class="columns is-multiline">
                    <div class="column is-12" v-if="data.type == 'checkbox'">
                      <VField :label="data.label"></VField>
                    </div>
                    <div  :class="['column ', data2.column || 'is-2']"  v-for=" (data2,index2) in data.children" :key="index2">
                        <VField>
                          <VControl>
                            <VCheckbox  class="p-0" color="primary" square :true-value="data2" :label="data2.value"
                              v-model="input[data2.model]" @change="handleCheckboxChange(data2)"></VCheckbox>
                          </VControl>
                        </VField>
                        <div  v-for="(data3, index3) in data2.children" :key="index3" :class="['column', data3.column || 'is-12']">
                          <div class="p-1" v-if="data3.isHide">
                            <VField addons v-if="data3.satuan">
                              <VControl expanded>
                                <VInput type="text" class="input" v-model="input[data3.model]" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>{{ data3.satuan }}</VButton>
                              </VControl>
                            </VField>
                            <VField>
                              <VControl>
                                <VInput v-model="input[data3.model]" :placeholder="data3.label"></VInput>
                              </VControl>
                            </VField>
                          </div>
                        </div>
                    </div>
                   </div>
                </div>
              </Fieldset>
            </div>
        </div>
      </VCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { onBeforeRouteLeave, useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import Calendar from 'primevue/calendar'
import * as EMR from '../page-emr-plugins/pengkajian-awal-rawat-inap-neonatus'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let PENGKAJIANAWAL = ref(EMR.PENGKAJIANAWAL())
let ANAMNESIS = ref(EMR.ANAMNESIS())
let RIWAYATBIOPSIKOSOSISAL = ref(EMR.RIWAYATBIOPSIKOSOSISAL())

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
const loadData: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const d_Agama: any = ref([])
const route = useRoute()
const d_Dokter: any = ref([])
const showChildren: any = ref([])
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
const loadRiwayat =async () => {
  loadData.value = true
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0]
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }
  loadData.value = false
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
const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
}
const fetchAgama = async () => {
    d_Agama.isLoading = true;
    const response = await useApi().get(
        `/emr/dropdown/agama_m?select=id,agama&param_search=agama&limit=10`)
    d_Agama.value = response
    d_Agama.isLoading = false;
}
const loadMoreData = ()=>{
 fetchAgama();
}
onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalKunjungan = moment(props.registrasi.tglregistrasi || new Date()).format("DD-MM-YYYY hh:mm:ss");
  input.value.dokterDPJP =  { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
  input.value.ruanganfk =  { label: props.registrasi.namaruangan, value: props.registrasi.ruanganfk }
  loadMoreData();
}
const handleCheckboxChange = (data2:any) => {
  if (data2.isShowChildren) {
    data2.children.forEach((child :any) => {
      child.isHide = input[data2.model] !== data2.value;
    });
  }
};
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
