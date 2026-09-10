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
        <VTabs slider centered selected="PENGKAJIAN FISIOTERAPI" :tabs="[
          { label: 'PENGKAJIAN FISIOTERAPI', value: 'PENGKAJIAN FISIOTERAPI' },
          { label: 'KOLOM INTERVENSI', value: 'KOLOM INTERVENSI' }]">
          <template #tab="{ activeValue }">
            <div v-if="activeValue == 'PENGKAJIAN FISIOTERAPI'">
              <div class="column is-12">
                <VCard>
                   <div class="columns is-multiline">
                      <div class="column is-4">
                        <VField label="Tanggal">
                          <VControl class="prime-auto">
                            <Calendar  v-model="input.tanggal" selectionMode="single" :manualInput="false"
                              class="w-100 mb-4 " :showIcon="true"  :dateFormat="H.dateTimeFormat().prime.date" inputId="single"  :hideOnRangeSelection="true"  />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4">
                        <VField class="is-autocomplete-select" label="DPJP">
                          <VControl icon="feather:search">
                            <AutoComplete v-model="input.dokterDpjp" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                              :field="'label'" placeholder="ketik Nama Dokter" />
                          </VControl>
                        </VField>
                      </div>
                   </div>
                </VCard>
              </div>
              <div class="column is-12">
                <Fieldset :toggleable="true" :legend="data.title" v-for="(data,index) in ASESMENFISIOTERAPI" :key="index">
                  <div class="columns is-multiline">
                     <div :class="'column ' + data2.column" :key="index2" v-for="(data2,index2) in data.children">
                      <div class="column is-12" v-if="data2.type == 'heading'">
                        <h1>{{ data2.label }}</h1>
                        <div :class="'column ' + data3.column"  :key="index3" v-for="(data3,index3) in  data2.children">
                          <VField :label="data3.label">
                             <div class="columns is-multiline" v-if="data3.type == 'checkbox'">
                                <div class="column is-4 pb-0" :key="index4" v-for="(data4,index4) in data3.children">
                                  <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data4.model]" class="p-0" :true-value="data4" :label="data4.label" color="primary" circle />
                                      </VControl>
                                  </VField>
                                </div>
                              </div>
                            <VInput v-if="data3.type == 'text'" v-model="input[data3.model]" :placeholder="data3.label +'...'"></VInput>
                            <VTextarea v-if="data3.type == 'textarea'" :rows="data3.row || 2" v-model="input[data3.model]" :placeholder="data3.label + '...'"></VTextarea>
                          </VField>
                        </div>
                      </div>
                      <VField :label="data2.label" v-else>
                          <div class="columns is-multiline" v-if="data2.type == 'checkbox'">
                            <div class="column is-4 pb-0" :key="index3" v-for="(data3,index3) in data2.children">
                              <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data2.model]" class="p-0" :true-value="data3.value" :label="data3.label" color="primary" circle />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                          <VInput v-if="data2.type == 'text'" v-model="input[data2.model]" :placeholder="data2.label + '...'"></VInput>
                          <VTextarea v-if="data2.type == 'textarea'" :rows="data2.row || 2" v-model="input[data2.model]" :placeholder="data2.label + '...'"></VTextarea>
                      </VField>
                     </div>
                  </div>
                </Fieldset>
              </div>
              <div class="column is-12" style="margin-left: auto;">
                <VCard>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <h1 style="font-weight: bold;"> {{ H.EMR().city }}, Tanggal </h1>
                        <div class="column is-4">
                          <VField class="mt-5">
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
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <TandaTangan :elemenID="'signature_pasien'" :width="'180'" :height="'180'"></TandaTangan>
                    </div>
                    <div class="column is-6">
                      <h1 class="p-0" style="font-weight: bold;">Nama Dokter</h1>
                        <VField>
                          <VControl class="prime-auto">
                            <AutoComplete v-model="input.dpjp2" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                              :field="'label'" placeholder="Dokter..." class="mt-2" />
                          </VControl>
                        </VField>
                    </div>
                  </div>
                </VCard>
              </div>
            </div>
            <div v-if="activeValue =='KOLOM INTERVENSI'">
              <div class="column is-12">
                <VCard>
                   <div class="columns is-multiline">
                    <table class="table-po">
                      <thead>
                        <tr class="tr-po">
                          <th class="th-po" width="15%" style="vertical-align:inherit;text-align: center;">Tanggal Jam</th>
                          <th class="th-po" width="20%" style="vertical-align:inherit;text-align: center;">Nama</th>
                          <th class="th-po" width="20%" style="vertical-align:inherit;text-align: center;">Intervensi</th>
                          <th class="th-po" width="20%" style="vertical-align:inherit;text-align: center;">Tanda Tangan Pasien</th>
                          <th class="th-po" width="20%" style="vertical-align:inherit;text-align: center;">Tanda Tangan Terapis</th>
                          <th class="th-po" width="5%" style="vertical-align:inherit;text-align: center;">#</th>
                        </tr>
                      </thead>
                      <tbody v-for="(itemss, index) in input.Interbensi" :key="index">
                          <tr class="tr-po">
                            <td class="td-po">
                              <div class="column p-1">
                              <VField>
                                <VControl class="prime-auto">
                                  <Calendar  v-model="itemss.tanggal" selectionMode="single" :manualInput="false"
                                    class="w-100 mb-4 " :showIcon="true"  :dateFormat="H.dateTimeFormat().prime.date" inputId="single"
                                    :hideOnRangeSelection="true"  showTime hourFormat="24"  />
                                  </VControl>
                              </VField>
                              </div>
                            </td>
                            <td class="td-po">
                              <div class="column p-1">
                                <VField>
                                  <VInput v-model="itemss.nama" placeholder="Nama..."></VInput>
                                </VField>
                              </div>
                            </td>
                            <td class="td-po">
                              <div class="column p-1">
                                <VField>
                                  <VTextarea v-model="itemss.intervensi" placeholder="Intervensi..."></VTextarea>
                                </VField>
                              </div>
                            </td>
                            <td class="td-po">
                              <div class="column p-1">
                                  <TandaTangan :elemenID="'signature_pasien_' + index" :width="'180'" :height="'180'"></TandaTangan>
                              </div>
                            </td>
                            <td class="td-po">
                              <div class="column p1">
                                <div>
                                  <TandaTangan :elemenID="'signature_dokter_' + index" :width="'180'" :height="'180'"></TandaTangan>
                                </div>
                                <div>
                                  <h1 class="p-0" style="font-weight: bold;">Nama Dokter</h1>
                                  <VField>
                                    <VControl class="prime-auto">
                                      <AutoComplete v-model="itemss.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="Dokter..." class="mt-2" @item-select="setTandaTangan($event,`signature_dokter_${index}`)" />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                            </td>
                            <td class="td-po" style="vertical-align: inherit;">
                              <div class="column is-12 pl-0 pr-0">
                                <VButtons style="justify-content:space-between">
                                  <VIconButton type="button" raised circle icon="feather:plus" v-tooltip-prime.bottom="'Tambah'"
                                    @click="addInterbensi(itemss)" outlined color="info">
                                  </VIconButton>
                                  <VIconButton type="button" raised circle v-tooltip-prime.bottom="'Hapus'" outlined
                                    :loading="itemss.isLoadBtnInterbensi" icon="feather:trash"
                                    @click="removeInterbensi(itemss, index)" color="danger">
                                  </VIconButton>
                                </VButtons>
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
        </VTabs>
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
import * as EMR from '../page-emr-plugins/asesmen-fisioterapi-anak'
import Calendar from 'primevue/calendar';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let ASESMENFISIOTERAPI = ref(EMR.ASESMENFISIOTERAPI())

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
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const loadData:any = ref(true)
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
  tanggal : new Date(),
  tglPembuatan : new Date(),
  Interbensi: [{
    no: 1,
    tanggal : new Date(),
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
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
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

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const setTandaTangan = async (e: any ,signature :any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set(signature, response.ttd)
    input.value.signature = response.ttd
  }
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.dpjp =  { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
  input.value.dpjp2 =  { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
}
const addInterbensi = async (data: any) => {
  input.value.Interbensi.push({
    no: input.value.Interbensi[input.value.Interbensi.length - 1].no + 1,
    tanggal: new Date(),
  });
}
const removeInterbensi = async (data: any, index:any) => {
  data.isLoadBtnInterbensi = true
  input.value.Interbensi.splice(index, 1)
  data.isLoadBtnInterbensi = false
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
.table-po {
  width: 100% !important;
  border-collapse: collapse !important;
}

.table-po,
.tr-po,
.th-po,
.td-po {
  border: 0.5px solid black !important;
}

.th-po,
.td-po {
  padding: 8px !important;
}

.bold {
  font-weight: bold;
}
</style>
