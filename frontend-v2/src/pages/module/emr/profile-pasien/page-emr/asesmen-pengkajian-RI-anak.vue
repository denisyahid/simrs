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
            <Fieldset :toggleable="true" legend="Asesmen Awal Keperawatan">
              <div class="columns is-multiline p-2">
                <div class="column is-12">
                  <VField label="Sumber Informasi">

                  </VField>
                </div>
                <div class="column is-2">
                    <VCheckbox  true-value="Pasien" label="Pasien" class="p-0" color="primary" square
                            v-model="input.sumberInformasi" ></VCheckbox>
                </div>
                <div class="column is-3">
                    <VCheckbox  true-value="Keluarga/Orang Lain" label="Keluarga/Orang Lain" class="p-0" color="primary" square
                            v-model="input.sumberInformasi" ></VCheckbox>
                </div>
                <div class="column is-6" v-if="input.sumberInformasi == 'Keluarga/Orang Lain'">
                    <VField>
                      <VInput v-model="input.valueSumberInformasi" placeholder="Sebutkan"></VInput>
                    </VField>
                </div>
                <div class="column is-12">
                  <VField label="Cara Masuk">
                  </VField>
                </div>
                <div class="column is-3">
                    <VCheckbox  true-value="Jalan dengan bantuan" label="Jalan dengan bantuan" class="p-0" color="primary" square
                            v-model="input.caraMasuk" ></VCheckbox>
                </div>
                <div class="column is-2">
                    <VCheckbox  true-value="Kursi Roda" label="Kursi Roda" class="p-0" color="primary" square
                            v-model="input.caraMasuk" ></VCheckbox>
                </div>
                <div class="column is-2">
                    <VCheckbox  true-value="Lain-lain" label="Lain-lain" class="p-0" color="primary" square
                            v-model="input.caraMasuk" ></VCheckbox>
                </div>
                <div class="column is-5" v-if="input.caraMasuk == 'Lain-lain'">
                    <VField>
                      <VInput v-model="input.valueCaraMasuk" placeholder="Jelaskan"></VInput>
                    </VField>
                </div>
                <div class="column is-12">
                  <VField label="Asal Masuk">
                  </VField>
                </div>
                <div class="column is-4">
                    <VField class="is-autocomplete-select">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="input.asalMasuk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                          :field="'label'" placeholder="ketik untuk mencari.." />
                          </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <VCheckbox  true-value="Lain-lain" label="Lain-lain" class="p-0" color="primary" square
                            v-model="input.asalMasuk" ></VCheckbox>
                </div>
                <div class="column is-5" v-if="input.asalMasuk == 'Lain-lain'">
                    <VField>
                      <VInput v-model="input.valueAsalMasuk" placeholder="Jelaskan"></VInput>
                    </VField>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" :legend="data.label" :key="index" v-for="(data,index) in ASESMEN">
              <div class="columns is-multiline">
                <div :class="'column ' + data2.column" :key="index2" v-for="(data2 ,index2) in data.children">
                  <div class="columns is-multiline" v-if="data2.type == 'checkbox'">
                    <div class="column is-12">
                      <VField :label="data2.label"></VField>
                    </div>
                    <div :class="['column ', data3.column || 'is-2']"  v-for=" (data3,index3) in data2.children" :key="index3">
                      <VField v-if="data3.type == 'checkbox'">
                        <VControl>
                          <VCheckbox  v-if="data3.type == 'checkbox'"  class="p-0" color="primary" square :true-value="data3.value" :label="data3.value"
                            v-model="input[data2.model]"  @change="handleCheckboxChange(data2.children, index3)"></VCheckbox>
                        </VControl>
                      </VField>
                      <VField   v-for="(data4,index4) in data3.children" :key="index4">
                        <VInput v-if="data4.isHide != true && data4.type == 'text'" v-model="input[data4.model || data2.model +'_lain']" :placeholder="data4.placeholder"></VInput>
                        <VControl class="prime-auto" v-if="data4.type == 'dateTime' && data4.isHide != true">
                          <Calendar  v-model="input[data4.model]" selectionMode="single" :manualInput="false"
                            class="w-100 mb-4 " :showIcon="true"  :dateFormat="H.dateTimeFormat().prime.date" inputId="single"  :hideOnRangeSelection="true"  />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                   <VField addons v-if="data.label == 'TANDA VITAL'">
                      <VControl expanded>
                        <VInput type="text" class="input" v-model="input[data2.model]" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>{{ data2.satuan }}</VButton>
                      </VControl>
                    </VField>
                  <VField :label="data2.label" v-else>
                    <VTextarea v-if="data2.type == 'textarea' && data2.isHide != true" v-model="input[data2.model]" rows="2" :placeholder="data2.label + '...'"></VTextarea>
                    <VInput v-if="data2.type == 'text'  && data2.isHide != true" v-model="input[data2.model]" rows="2" :placeholder="data2.label + '...'"></VInput>
                  </VField>
                </div>
              </div>
            </Fieldset>
            <Fieldset :toggleable="true" :legend="'Glasgow Coma Scale ( GCS )'">
               <div class="column is-12">
                <div class="column is-12" style="border-bottom: solid 1px;">
                  <div class="columns is-multiline">
                    <div class="column is-1">
                      <h1 class="emr">No</h1>
                    </div>
                    <div class="column is-4">
                      <h1 class="emr">Parameter</h1>
                    </div>
                    <div class="column is-6">
                      <h1 class="emr">Pengkajian</h1>
                    </div>
                    <div class="column">
                      <h1 class="emr">Nilai</h1>
                    </div>
                  </div>
                   <div class="column is-12">
                    <div class="columns is-multiline" :key="index" v-for="(gg,index) in GLASGOWS">
                      <div class="column is-1">
                        <h1 class="emr">{{ index + 1 }}</h1>
                      </div>
                      <div class="column is-4">
                        <h1 class="emr">{{ gg.label }}</h1>
                      </div>
                      <div class="column is-5 pt-0">
                        <VField class="">
                          <div class="column is-12 pb-0" :key="index" v-for="(value,index) in gg.children">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[gg.model]" class="p-0"
                                :true-value="value" :label="value.label" color="primary" circle />
                            </VControl>
                          </div>
                        </VField>
                      </div>
                      <div class="column pt-0">
                        <div class="column pb-0" :key="index" v-for="(value,index) in gg.children">
                          <h1 class="emr">{{ value.score }}</h1>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-3" style="margin-left: auto;">
                <VField label="Jumlah Total">
                  <VControl raw subcontrol>
                    <input v-model="input.jumlahNilai" class="input" disabled />
                  </VControl>
                </VField>
              </div>
            </Fieldset>
            <Fieldset :toggleable="true" :legend="data.label" :key="index" v-for="(data,index) in ASESMEN2">
              <div class="columns is-multiline">
                <div :class="'column ' + data2.column" :key="index2" v-for="(data2 ,index2) in data.children">
                  <div class="columns is-multiline" v-if="data2.type == 'checkbox'">
                    <div class="column is-12">
                      <VField :label="data2.label"></VField>
                    </div>
                    <div :class="['column ', data3.column || 'is-2']"  v-for=" (data3,index3) in data2.children" :key="index3">
                      <VField v-if="data3.type == 'checkbox'">
                        <VControl>
                          <VCheckbox  v-if="data3.type == 'checkbox'"  class="p-0" color="primary" square :true-value="data3.value" :label="data3.value"
                            v-model="input[data2.model]"  @change="handleCheckboxChange(data2.children, index3)"></VCheckbox>
                        </VControl>
                      </VField>
                      <VField :label="data3.label" v-if="data3.type == 'text'  && data3.isHide != true">
                        <VInput  v-model="input[data3.model]"  :placeholder="data3.label + '...'"></VInput>
                      </VField>
                      <VField   v-for="(data4,index4) in data3.children" :key="index4">
                        <VInput v-if="data4.isHide != true && data4.type == 'text'" v-model="input[data4.model || data2.model +'_lain']" :placeholder="data4.placeholder"></VInput>
                        <VControl class="prime-auto" v-if="data4.type == 'dateTime' && data4.isHide != true">
                          <Calendar  v-model="input[data4.model]" selectionMode="single" :manualInput="false"
                            class="w-100 mb-4 " :showIcon="true"  :dateFormat="H.dateTimeFormat().prime.date" inputId="single"  :hideOnRangeSelection="true"  />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <VField :label="data2.label" v-else>
                    <VTextarea v-if="data2.type == 'textarea' && data2.isHide != true" v-model="input[data2.model]" rows="2" :placeholder="data2.label + '...'"></VTextarea>
                    <VInput v-if="data2.type == 'text'  && data2.isHide != true" v-model="input[data2.model]" rows="2" :placeholder="data2.label + '...'"></VInput>
                  </VField>
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
import moment from 'moment'
import * as EMR from '../page-emr-plugins/asesmen-pengkajian-RI-anak'
import Calendar from 'primevue/calendar';
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let ASESMEN = ref(EMR.ASESMEN())
let ASESMEN2 = ref(EMR.ASESMEN2())
let GLASGOWS = ref(EMR.GLASGOWS())

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
  hppt: new Date(),
  perkiraanMenstruasiBerikutnya : new Date()
})
const loadData:any = ref(false)
const d_Ruangan:any = ref([])
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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }

  const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
  }
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalKunjungan = moment(props.registrasi.tglregistrasi || new Date()).format("DD-MM-YYYY hh:mm:ss");
  input.value.dokterDPJP =  { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
  input.value.asalMasuk = { label: props.registrasi.namaruangan ,value : props.registrasi.objectruanganfk}
   await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd="  + NOREC_PD +
        "&collection=VitalSign" +
        "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,tinggiBadan"
    ).then((response) => {

        input.value.nadi = response ? response.nadi : ''
        input.value.tekananDarah = response ? response.tekananDarah : ''
        input.value.pernapasan = response ? response.pernapasan : ''
        input.value.lingkarPerut = response ? response.lingkarPerut : ''
        input.value.suhuBadan = response ? response.suhu : ''
        input.value.beratBadan = response ? response.beratBadan : ''
        input.value.tinggiBadan = response ? response.tinggiBadan : ''
        input.value.pernafasan = response ? response.pernapasan : ''
        input.value.imt = response ? response.IMT : ''
    })
}
const handleCheckboxChange = (children:any, index:any) => {
   if (children[index].type === 'checkbox' && children[index].isShowChildren) {
    children[index].children.forEach((child:any) => {
      child.isHide = false;
    });
  }
};
watch(() => [
    input.value.responVerbal,
    input.value.responMotorik,
    input.value.responBukaMata,
], () => {
    let poin1 = input.value.responVerbal ? parseInt(input.value.responVerbal.score) : 0
    let poin2 = input.value.responMotorik ? parseInt(input.value.responMotorik.score) : 0
    let poin3 = input.value.pergerakan ? parseInt(input.value.responBukaMata.score) : 0
    const total = poin1 + poin2 + poin3
    input.value.jumlahNilai = total
})
watch(() => [
    input.value.caraJalanSempoyongan,
    input.value.memegangangKursiSaatJalan,
], () => {
    let poin1 = input.value.caraJalanSempoyongan ? parseInt(input.value.caraJalanSempoyongan.score) : 0
    let poin2 = input.value.memegangangKursiSaatJalan ? parseInt(input.value.memegangangKursiSaatJalan.score) : 0
    const total = poin1 + poin2
    input.value.jumlahResikoJatuh = total
})
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
