<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr
              :NOREC_EMRPASIEN="NOREC_EMRPASIEN"
              :COLLECTION="COLLECTION"
              :isLoading="isLoading"
              @simpan="simpan"
              @kembaliKeun="kembaliKeun"
            ></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
      <VCard v-else>
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
                  <VField class="is-autocomplete-select" label="PPJA">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                        :field="'label'" placeholder="ketik untuk mencari.." />
                        </VControl>
                  </VField>
                </div>
            </div>
          </VCard>
          <VCard class="mt-5">
            <div class="column is-12" style="width: 100%;overflow: auto;">
                <table class="tg" style="width: 150%;">
                    <tr>
                      <th rowspan="2" style="min-width:20px;left:0;z-index:2;background-color: aliceblue;">No</th>
                      <th rowspan="2" style="min-width:100px;left:0;z-index:2;background-color: aliceblue;">PARAMETER</th>
                      <th rowspan="2" style="min-width:400px;left:0;z-index:2;background-color: aliceblue;">SKRINNING</th>
                      <th rowspan="2" style="min-width:200px;left:0;z-index:2;background-color: aliceblue;">SKOR ACUAN	</th>
                      <th :colspan="jumlahImdex" class="text-left bg-th" style="min-width:200px;text-align:center">Tanggal</th>
                    </tr>
                    <tr>
                      <th class="bg-th" style="width:400px" :key="index" v-for="index in jumlahImdex">
                        <VDatePicker v-model="input.tglPencegahan[index]" color="green" trim-weeks :input="'YYYY-MM-DD'"
                          mode="dateTime" is24hr>
                          <template #default="{ inputValue, inputEvents }">
                            <VField>
                              <VControl icon="feather:calendar">
                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                  class="is-rounded_Z input-30" />
                              </VControl>
                            </VField>
                          </template>
                        </VDatePicker>
                      </th>
                    </tr>
                    <tr :key="index" v-for="(data, index) in ASESMEN_AWAL">
                      <template v-for="(children, index2) in data.children">
                        <template v-if="children.type === 'checkbox' && children.isLoop">
                          <td v-for="index3 in jumlahImdex" :key="`${index2}-${index3}`">
                            <VCheckbox  @change="setSkor(children.model + index3,children.value,index3)" :label="children.label" v-model="input[children.model + index3]" color="primary" square :true-value="children.value" v-if="children.type == 'checkbox'"/>
                          </td>
                        </template>
                        <template v-else-if="children.type === 'input' && children.isLoop">
                          <td v-for="index3 in jumlahImdex" :key="`${index2}-${index3}`">
                            <VField>
                              <VInput :placeholder="children.placeholder" v-model="input[children.model + index3]"></VInput>
                            </VField>
                          </td>
                        </template>
                        <template v-else>
                          <td
                            :key="index2"
                            :rowspan="children.rowspan || 1"
                            :colspan="children.colspan || 1"
                          >
                            <p v-if="children.type === 'label'">
                              {{ children.label }}
                            </p>
                            <VCheckbox :label="children.label" v-model="input[children.model + index3]" color="primary" square :true-value="children.value" v-if="children.type == 'checkbox'"/>
                          </td>
                        </template>
                      </template>
                    </tr>
                </table>
            </div>
          </VCard>
          <VCard class="mt-5">
            <h1 style="font-weight:bold;font-size:17px">PROTOKOL INTERVENSI PENCEGAHAN JATUH - PASIEN GERIATRIK	</h1>
              <div class="column is-12" style="width: 100%;overflow: auto;">
                <table class="tg" style="width: 150%;">
                    <tr>
                      <th rowspan="2" style="min-width:1px;left:0;z-index:2;background-color: aliceblue;">No</th>
                      <th rowspan="2" style="width:100px;left:0;z-index:2;background-color: aliceblue;">Standar Risiko Rendah	</th>
                      <th :colspan="jumlahImdex" class="text-left bg-th" style="min-width:200px;text-align:center">Tanggal {{ jumlahImdex}}</th>
                    </tr>
                    <tr>
                      <th class="bg-th" style="width:10px" :key="index" v-for="index in jumlahImdex">
                        <VDatePicker v-model="input.tglPencegahan[0]" color="green" trim-weeks :input="'YYYY-MM-DD'"
                          mode="dateTime" is24hr>
                          <template #default="{ inputValue, inputEvents }">
                            <VField>
                              <VControl icon="feather:calendar">
                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                  class="is-rounded_Z input-30" />
                              </VControl>
                            </VField>
                          </template>
                        </VDatePicker>
                      </th>
                    </tr>
                    <tr :key="index" v-for="(data, index) in PROTOKOL_INTERVENSI">
                      <template v-for="(children, index2) in data.children">
                        <template v-if="children.type === 'checkbox' && children.isLoop">
                          <td style="width:10px" v-for="index3 in jumlahImdex" :key="`${index2}-${index3}`">
                            <VCheckbox  @change="setSkor(children.model + index3,children.value,index3)" :label="children.label" v-model="input[children.model + index3]" color="primary" square :true-value="children.value" v-if="children.type == 'checkbox'"/>
                          </td>
                        </template>
                        <template v-else-if="children.type === 'input' && children.isLoop">
                          <td v-for="index3 in jumlahImdex" :key="`${index2}-${index3}`">
                            <VField>
                              <VInput :placeholder="children.placeholder" v-model="input[children.model + index3]"></VInput>
                            </VField>
                          </td>
                        </template>
                        <template v-else>
                          <td
                          style="width:5px"
                            :key="index2"
                            :rowspan="children.rowspan || 1"
                            :colspan="children.colspan || 1"
                          >
                            <p v-if="children.type === 'label'">
                              {{ children.label }}
                            </p>
                            <VCheckbox :label="children.label" v-model="input[children.model + index3]" color="primary" square :true-value="children.value" v-if="children.type == 'checkbox'"/>
                          </td>
                        </template>
                      </template>
                    </tr>
                </table>
              </div>
          </VCard>
            <VCard class="mt-5">
              <div class="column is-12" style="width: 100%;overflow: auto;">
                <table class="tg" style="width: 150%;">
                    <tr>
                      <th rowspan="2" style="min-width:20px;left:0;z-index:2;background-color: aliceblue;">No</th>
                      <th rowspan="2" style="min-width:30px;left:0;z-index:2;background-color: aliceblue;">Standar Risiko Sedang</th>
                      <th :colspan="jumlahImdex" class="text-left bg-th" style="min-width:200px;text-align:center">Tanggal {{ jumlahImdex}}</th>
                    </tr>
                     <tr>
                      <th class="bg-th" style="width:300px" :key="index" v-for="index in jumlahImdex">
                        <VDatePicker v-model="input.tglPencegahan[index]" color="green" trim-weeks :input="'YYYY-MM-DD'"
                          mode="dateTime" is24hr>
                          <template #default="{ inputValue, inputEvents }">
                            <VField>
                              <VControl icon="feather:calendar">
                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                  class="is-rounded_Z input-30" />
                              </VControl>
                            </VField>
                          </template>
                        </VDatePicker>
                      </th>
                    </tr>
                    <tr :key="index" v-for="(data, index) in STANDAR_RESIKO_SEDANG">
                      <template v-for="(children, index2) in data.children">
                        <template v-if="children.type === 'checkbox' && children.isLoop">
                          <td v-for="index3 in jumlahImdex" :key="`${index2}-${index3}`">
                            <VCheckbox  @change="setSkor(children.model + index3,children.value,index3)" :label="children.label" v-model="input[children.model + index3]" color="primary" square :true-value="children.value" v-if="children.type == 'checkbox'"/>
                          </td>
                        </template>
                        <template v-else-if="children.type === 'input' && children.isLoop">
                          <td v-for="index3 in jumlahImdex" :key="`${index2}-${index3}`">
                            <VField>
                              <VInput :placeholder="children.placeholder" v-model="input[children.model + index3]"></VInput>
                            </VField>
                          </td>
                        </template>
                        <template v-else>
                          <td
                            :key="index2"
                            :rowspan="children.rowspan || 1"
                            :colspan="children.colspan || 1"
                          >
                            <p v-if="children.type === 'label'">
                              {{ children.label }}
                            </p>
                            <VCheckbox :label="children.label" v-model="input[children.model + index3]" color="primary" square :true-value="children.value" v-if="children.type == 'checkbox'"/>
                          </td>
                        </template>
                      </template>
                    </tr>
                </table>
              </div>
          </VCard>
          <VCard class="mt-5">
              <div class="column is-12" style="width: 100%;overflow: auto;">
                <table class="tg" style="width: 150%;">
                    <tr>
                      <th rowspan="2" style="min-width:20px;left:0;z-index:2;background-color: aliceblue;">No</th>
                      <th rowspan="2" style="min-width:30px;left:0;z-index:2;background-color: aliceblue;">Standar Risiko Tinggi</th>
                      <th :colspan="jumlahImdex" class="text-left bg-th" style="min-width:200px;text-align:center">Tanggal</th>
                    </tr>
                     <tr>
                      <th class="bg-th" style="width:300px" :key="index" v-for="index in jumlahImdex">
                        <VDatePicker v-model="input.tglPencegahan[index]" color="green" trim-weeks :input="'YYYY-MM-DD'"
                          mode="dateTime" is24hr>
                          <template #default="{ inputValue, inputEvents }">
                            <VField>
                              <VControl icon="feather:calendar">
                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                  class="is-rounded_Z input-30" />
                              </VControl>
                            </VField>
                          </template>
                        </VDatePicker>
                      </th>
                    </tr>
                    <tr :key="index" v-for="(data, index) in STANDAR_RESIKO_TINGGI">
                      <template v-for="(children, index2) in data.children">
                        <template v-if="children.type === 'checkbox' && children.isLoop">
                          <td v-for="index3 in jumlahImdex" :key="`${index2}-${index3}`">
                            <VCheckbox  @change="setSkor(children.model + index3,children.value,index3)" :label="children.label" v-model="input[children.model + index3]" color="primary" square :true-value="children.value" v-if="children.type == 'checkbox'"/>
                          </td>
                        </template>
                        <template v-else-if="children.type === 'input' && children.isLoop">
                          <td v-for="index3 in jumlahImdex" :key="`${index2}-${index3}`">
                            <VField>
                              <VInput :placeholder="children.placeholder" v-model="input[children.model + index3]"></VInput>
                            </VField>
                          </td>
                        </template>
                        <template v-else>
                          <td
                            :key="index2"
                            :rowspan="children.rowspan || 1"
                            :colspan="children.colspan || 1"
                          >
                            <p v-if="children.type === 'label'">
                              {{ children.label }}
                            </p>
                            <VCheckbox :label="children.label" v-model="input[children.model + index3]" color="primary" square :true-value="children.value" v-if="children.type == 'checkbox'"/>
                          </td>
                        </template>
                      </template>
                    </tr>
                </table>
              </div>
          </VCard>
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
import AutoComplete from 'primevue/autocomplete'
import Fieldset from 'primevue/fieldset'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/resiko-jatuh-ontarion'
import moment from 'moment'
import Calendar from 'primevue/calendar';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let ASESMEN_AWAL = ref(EMR.ASESMEN_AWAL())
let PROTOKOL_INTERVENSI = ref(EMR.PROTOKOL_INTERVENSI())
let STANDAR_RESIKO_SEDANG = ref(EMR.STANDAR_RESIKO_SEDANG())
let STANDAR_RESIKO_TINGGI = ref(EMR.STANDAR_RESIKO_TINGGI())
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
const isStuck = computed(() => {
  return y.value > 30
})
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const  jumlahImdex:any = ref(3)
const input: any = ref({
  tglPencegahan: [],
  tglProtokolJatuh: [],
  tglStandarResikoTinggi: [],
  tglStandarResikoSedang: [],
  detailsTglSkor: [{
    no: 1,
    tgl: new Date(),
  }]
})
const loadData: any = ref(false)
const d_Ruangan: any = ref([])
const setSkor = (key: any, value: any, index: number) => {
  if (key == `mobilitas_${index}` || key == `transferDariTempatTidur_${index}`) {
    input.value[`totalTransferMobilitas_${index}`] = parseFloat(input.value[`totalTransferMobilitas_${index}`]  ? input.value[`totalTransferMobilitas_${index}`]  : 0) + parseFloat(value.skor);
  }else{
    input.value[`skor_${index}`] = parseFloat(input.value[`skor_${index}`]  ? input.value[`skor_${index}`]  : 0) + parseFloat(value.skor);
  }
  let total = parseFloat(input.value[`skor_${index}`] || 0 ) + parseFloat(input.value[`totalTransferMobilitas_${index}`] || 0);
  input.value[`skorTotal_${index}`] = total;

};

const loadRiwayat = async () => {
  loadData.value = true
  let response = await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  )
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
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalKunjungan = moment(props.registrasi.tglregistrasi || new Date()).format("DD-MM-YYYY hh:mm:ss");
  input.value.ruanganfk =  { label: props.registrasi.namaruangan, value: props.registrasi.objectruanganfk }
}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;

  // font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg tr {
  height: 20px;
}

tr{
  vertical-align: middle;
  text-align: center;
  td{
    vertical-align: middle;
    text-align: center;
  }
}
.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  vertical-align: middle;
  // font-size: 14px;
  font-weight: bold;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top
}

.input-30 {
  height: 30px;
}

.bg-colatas {
  position: sticky;
  background-color: aliceblue;
  left: 0;
  z-index: 2;
}

.bg-colatas2 {
  position: sticky;
  background-color: aliceblue;
  left: 150px;
  z-index: 2;
}

.bg-colatas3 {
  position: sticky;
  background-color: aliceblue;
  left: 301px;
  z-index: 2;
}


.bg-col {
  position: sticky;
  background-color: aliceblue;
  left: 0;
  z-index: 2;
}

.bg-col2 {
  position: sticky;
  background-color: aliceblue;
  left: 57px;
  z-index: 2;
}

.bg-col3 {
  position: sticky;
  background-color: aliceblue;
  left: 357px;
  z-index: 2;
}



.bg-th {
  text-align: center;
  background-color: #dedfe2d3;
}
</style>
