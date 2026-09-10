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
        <div class="column is-12">
          <VCard>
            <div class="columns is-multiline">
              <div class="column is-12">
                <h1 style="font-weight: bold;">Pasien</h1>
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2" style="margin-top:0.5rem">
                      <span> Nama : </span>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Nama " v-model="input.namaPasien" />
                        </VControl>

                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2" style="margin-top:0.5rem">
                      <span> No. RM : </span>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="number" class="input" placeholder="No. RM" v-model="input.norm" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2" style="margin-top:1rem">
                      <span> Tanggal Dirawat : </span>
                    </div>
                    <div class="column is-6" style=" margin-top: 0.5rem">
                      <VField>
                        <VDatePicker v-model="input.tglDirawat" mode="dateTime" style="width: 100%" trim-weeks
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
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2" style="margin-top:1rem">
                      <span> Alamat Lengkap : </span>
                    </div>
                    <div class="column is-6" style=" margin-top: 0.5rem">
                      <VField>
                        <VControl>
                          <VTextarea placeholder="Alamat Pasien" rows="2" v-model="input.alamatPasien" ></VTextarea>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                    <div class="column is-12 p-0">
                      <div class="is-flex">
                        <div class="column is-2" style="margin-top:0.5rem">
                        <span> Alamat Lengkap : </span>
                        </div>
                      <div class="column is-6">
                        <VControl>
                          <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                            <div class="column is-12" v-if="d_JenisKelamin.length == 0">
                              <VPlaceloadText :lines="1" />
                            </div>
                            <div class="column is-4 mt-2 p-0" v-for="items in d_JenisKelamin" :key="items.id">
                              <VRadio v-model="input.jenisKelaminPasien" :value="items.label" class="p-0 mb-3" :label="items.label"
                                square color="primary" />
                            </div>
                          </div>
                        </VControl>
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-12">
        <Fieldset :toggleable="true" :legend="data.title" v-for="(data,index) in ANAMNESIS" :key="index">
          <div class="columns is-multiline">
            <div :class="'column '  +  data2.column" v-for="(data2 ,index2) in data.children"  :key="index2">
              <div v-if="data.category  == 'vitalSign'">
                <VField :label="data2.label"></VField>
                <VField addons  v-if="data.category  == 'vitalSign'">
                  <VControl expanded>
                    <VInput type="text" class="input" :placeholder="data2.label + '...'" v-model="input[data2.model]" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>{{ data2.satuan }}</VButton>
                  </VControl>
                </VField>
              </div>
              <div v-else>
                <VField :label="data2.label">
                  <VControl>
                    <VInput v-if="data2.type == 'input'" v-model="input[data2.model]"  :placeholder="data2.label + '...'" ></VInput>
                    <VTextarea :rows="2" v-if="data2.type == 'textarea'" :placeholder="data2.label + '...'"  v-model="input[data2.model]" ></VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
         <Fieldset class="p-fieldsets" legend="Pengkajian" :toggleable="true">
          <div class="column is-12">
              <div class="column is-12" style="border-bottom: solid 1px;">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <h1 class="emr">Parameter</h1>
                  </div>
                  <div class="column is-5">
                    <h1 class="emr">Kondisi</h1>
                  </div>
                  <div class="column is-3">
                    <h1 class="emr">Penjelasan</h1>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline" :key="index" v-for="(pp,index) in PENGKAJIAN">
                  <div class="column is-4">
                    <h1 class="emr">{{ pp.label }}</h1>
                  </div>
                  <div class="column is-5">
                    <div class="columns is-multiline">
                      <div class="column is-4 pb-0" :key="index" v-for="(value,index) in pp.children">
                        <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[pp.model]" class="p-0" :true-value="value" :label="value.label" color="primary" circle />
                            </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VTextarea :rows="2" class="mt-2" v-model="input[pp.model + '_value']" :placeholder="pp.label + '..'"></VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                 <VField label="Diagnosis">
                    <VTextarea :rows="2" class="mt-2" v-model="input.diagnosis" placeholder="Diagnosis..."></VTextarea>
                </VField>
              </div>
              <div class="column is-12">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.statusPasien" class="p-0" :true-value="'FIT'" label="FIT" color="primary" circle />
                  </VControl>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.statusPasien" class="p-0" :true-value="'Rekomendasi'" label="Rekomendasi" color="primary" circle />
                  </VControl>
                </VField>
                <VField label="Rekomendasi" v-if="input.statusPasien == 'Rekomendasi'">
                  <VTextarea :rows="2" class="mt-2" v-model="input.rekomendasi" placeholder="Rekomendasi..."></VTextarea>
                </VField>
              </div>
          </div>
         </Fieldset>
        </div>
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
              <VField>
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
              <div>
                <TandaTangan :elemenID="'signature_dokter'" :width="'180'" :height="'180'"></TandaTangan>
              </div>
              <div>
                <h1 class="p-0" style="font-weight: bold;">Nama Dokter</h1>
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.medisPerawat" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                      :field="'label'" placeholder="Dokter..." class="mt-2" @item-select="setTandaTangan($event,'signature_dokter')" />
                  </VControl>
                </VField>
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
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/ringkasan-mcu'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let ANAMNESIS = ref(EMR.ANAMNESIS())
let PENGKAJIAN = ref(EMR.PENGKAJIAN())

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
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
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
const COLLECTION: any = ref(props.COLLECTION)
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const loadData:any = ref(true);
const d_JenisKelamin:any = ref([]);
const loadRiwayat = async () => {

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
const fetchJenisKelamin = async () => {
  const response = await useApi().get(
    `/emr/dropdown/jeniskelamin_m?select=id,jeniskelamin&param_search=&`)
  d_JenisKelamin.value = response
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
  input.value.namaPasien = props.pasien.namapasien
  input.value.jenisKelaminPasien = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.dokterRawat = props.registrasi.dokter
  input.value.dirawatDiRuang = props.registrasi.namaruangan
  input.value.kelas = props.registrasi.namakelas
  input.value.kelompokPasien = props.registrasi.kelompokpasien
  input.value.tglPembuatan = new Date()
  input.value.tglDirawat = props.registrasi.tglregistrasi
  fetchJenisKelamin();

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
const setTandaTangan = async (e: any ,signature :any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set(signature, response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set(signature, '')
  }
}
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

h1.emr {
    font-weight: bold;
}
table tr {
  // border:  1px solid black;
}

</style>
