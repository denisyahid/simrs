<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Surat Pernyataan Pengambilan Keputusan Tindakan Medis Pada Pasien Tidak Sadar Tanpa Pengantar/Keluarga Terdekat</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
            </div>
          </div>
        </div>
      </div>

      <div class="columns is-multiline p-2">
        <div class="column is-12">
          <div class="is-12">
            <Fieldset>
              <div class="columns is-multiline">
                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1>Dengan memperhatikan semua tanda dan gejala yang dijumpai pada saat ini di RSUD Bali Mandara atas seorang pasien dengan data sebagai berikut :</h1>
                    <h1><i>By considering all the signs and symptoms encountered currently in Bali Mandara General Hospital for a patient with the following data:</i></h1>
                  </div>
                </div>

                <div class="column">
                  <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                      <span>Nama lengkap</span><br>
                      <span><i>Full name</i></span>
                  </div>
                  <div class="column is-6">
                      <VField>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.namaPasien" />
                      </VControl>
                      </VField>
                  </div>
                  </div>
                  <div class="column is-12 is-flex ml-5">
                      <div class="column is-3">
                          <span>Umur</span><br>
                          <span><i>Age</i></span>
                      </div>
                      <div class="column is-6">
                          <VField class="mt-3">
                              <VControl>
                                  <VInput type="text" class="input" v-model="input.UmurPasien" />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                  <div class="column is-12 is-flex ml-5">
                      <div class="column is-3">
                          <span>Jenis Kelamin:</span><br>
                          <span><i>Sex</i></span>
                      </div>
                      <div class="column is-4" style="display: flex">
                      <VField v-for="items in JenisKelamin" :key="items.value">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                            :label="items.label" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column is-12 is-flex ml-5">
                      <div class="column is-3">
                          <span>No. Rekam Medis</span><br>
                          <span><i>medical record number</i></span>
                      </div>
                      <div class="column is-6">
                          <VField class="mt-3">
                              <VControl>
                                  <VInput type="text" class="input" v-model="input.norm" />
                              </VControl>
                          </VField>
                      </div>
                  </div>
                  <div class="column is-12 is-flex ml-5">
                      <div class="column is-3" style="margin-top: 10px;">
                      <span>Dirawat di ruangan :</span><br>
                      <span><i>Treated in the room</i></span>
                      </div>
                      <div class="column is-5">
                        <VField>
                          <VControl>
                            <AutoComplete v-model="input.ruanganPerawatan" :suggestions="d_Ruangan"
                              @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="ketik untuk mencari ruangan..." />
                          </VControl>
                        </VField>
                      </div>
                  </div>  
                </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1>Serta telah melalui pertimbangan ilmu kedokteran, etika profesi kedokteran dan mengingat sumpah dokter, kami menyatakan dengan sesungguhnya bahwa pada :</h1>
                    <h1><i>And has been through consideration of medical science, ethics and the medical profession to remember the oath doctors, we declare truthfully that on :</i></h1>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-1" style="margin-top: 10px;">
                    <span>Hari:</span><br>
                    <span><i>Day</i></span>
                  </div>
                  <div class="column is-3" style="margin-top: 10px;">
                    <Multiselect v-model="input.SHari" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_hari" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-1" style="margin-top: 10px;">
                    <span>Tanggal:</span><br>
                    <span><i>Date</i></span>
                  </div>
                  <div class="column is-3">
                    <VDatePicker class="p-3 pb-0" v-model="input.tanggalPernyataan" color="green" trim-weeks mode="date"
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                                <VControl icon="feather:calendar">
                                    <VInput type="text" placeholder="Select a date" :value="inputValue"
                                        v-on="inputEvents" class="is-rounded_Z" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-1" style="margin-top: 10px;">
                    <span>Pukul:</span><br>
                    <span><i>time</i></span>
                  </div>
                  <div class="column is-3">
                    <VDatePicker class="p-3 pb-0" v-model="input.tanggalPernyataan" color="green" trim-weeks mode="Time"
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                                <VControl icon="feather:calendar">
                                    <VInput type="text" placeholder="Select a date" :value="inputValue"
                                        v-on="inputEvents" class="is-rounded_Z" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column">
                    <div class="column is-12 pl-5 pr-5 pb-0">
                        <p class="pb-3 p-justify">1. Bahwa pasien dalam keadaan gawat darurat yang perlu memperoleh tindakan medis segera untuk upaya menyelamatkan
                          jiwa dimana penundaan tindakan medis akan membahayakan pasien, dan
                        </p>
                        <p class="pb-3 p-justify"><i>That patients in a state of emrgency that needs immediate medical action to obtain lafe-saving efforts in which delay would
                          endanger the patient’s, medical treatment, and
                        </i></p>
                        <p class="pb-3 p-justify">2. Bahwa pasien dalam keadaan tidak sadar dan tidak mampu menerima penjelasan tentang keadaan medis yang dihadapi
                          untuk memutuskan sesuatu terhadap dirinya, dan
                        </p>
                        <p class="pb-3 p-justify"><i>That the patient is unconscious and unable to accept the explanation of medical conditions encountered to decide anything
                          against him, and
                        </i></p>
                        <p class="pb-3 p-justify">3. Bahwa pasien tidak disertai pendamping yang mempunyai pertalian urutan keluarga terdekat, yang berwenang memberi persetujuan/ijin tindakan medis atas dirinya,</p>
                        <p class="pb-3 p-justify"><i>That the patient does not have a companion who accompanied the order of immediate family ties, the authorities gave approval / permit medical procedures on him,</i></p>
                        <p class="pb-3 p-justify">4. Saya bertanggung jawab secara penuh atas segala akibat yang timbul apabila tidak dilakukan
                            tindakan resusitasi jantung paru.</p> 
                        <p class="pb-3 p-justify"><i>I am fully responsible for any possibilities that might arise as consequences for refusing
                            resuscitation.</i></p>
                    </div>
                  </div>
                </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1>maka diputuskan melakukan segala tindakan medis yang dianggap perlu tanpa menunggu persetujuan pasien mengingat hal tersebut di atas, (butir 1-3 seperti ketentuan standar profesi di RSUD Bali Mandara).</h1>
                    <h1><i>it was decided to perform all acts that are considered medically necessary without waiting for the consent of the patient considering the above, (1-3 grains such as provision of professional standards in Bali Mandara General Hospital).</i></h1>
                  </div>
                </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1>Pernyataan ini dibuat untuk melengkapi dokumen medis RSUD Bali Mandara dan sebagai bukti kesungguhan kami menunaikan tugas melakukan upaya terbaik dalam pertolongan pasien dengan :</h1>
                    <h1><i>This statement was made to complete the Bali Mandara General Hospital medical documents and as evidence of our determination to fulfill the task of making the best effort in the rescue of patients with :</i></h1>
                  </div>
                </div>

                <div class="column is-12 is-flex">
                  <div class="column">
                      <div class="column is-12 is-flex ml-5">
                        <div class="column is-2">
                            <span>Diagnosa Medis :</span><br>
                            <span><i>Medical diagnosis</i></span>
                        </div>
                        <div class="column is-7">
                            <VField>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.DiagnosaMedis" />
                            </VControl>
                            </VField>
                        </div>
                      </div>
                      <div class="column is-12 is-flex ml-5">
                        <div class="column is-2">
                            <span>Tindakan Medis :</span><br>
                            <span><i>Medical action</i></span>
                        </div>
                        <div class="column is-7">
                            <VField>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TindakanMedis" />
                            </VControl>
                            </VField>
                        </div>
                      </div>
                  </div>
                </div>

               

                <div class="column is-12 is-flex">
                  <div class="column is-4"></div>
                  <div class="column is-4">
                    <h1 style="font-weight: bold" class="mr-5">Garut</h1>
                    <VField>
                      <VDatePicker v-model="input.tanggal" color="green" trim-weeks mode="dateTime"
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
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="column is-4"></div>
                  <div class="column is-4" style="text-align: center;">
                    <span>Yang membuat pernyataan</span><br>
                    <span><i>Who made the statement</i></span>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="column is-4"></div>
                  <div class="column is-4" style="text-align: center;">
                    <span>Dokter yang melakukan tindakan medis,</span><br>
                    <span><i>Doctors who perform medical acts,</i></span>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5 justify-content-start">
                  <div class="column is-4" style="text-align: center">
                    <span>Dokter I / DPJP/ MOD</span><br>
                    <span><i>Doctor I</i></span><br>
                    <TandaTangan :elemenID="'TTDdokter'" :width="'150'" :height="'150'" class="dek" />
                    <VField>
                      <VControl>
                        <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                   <div class="column is-4"></div>
                  <div class="column is-4" style="text-align: center">
                    <span>Pasien / Keluarga Pasien</span><br>
                    <span><i>Patient / Patient’s Family</i></span><br>
                    <TandaTangan :elemenID="'TTDPasienKeluarga'" :width="'150'" :height="'150'" class="dek" />
                    <div class="column is-12" style="text-align: left;">
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" class="input" v-model="input.PasienKeluarga" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import MultiSelect from 'primevue/multiselect'
import * as EMR from '../page-emr-plugins/lembaran-penyiaran-radioterapi'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string

let jenisTindakanRadioterapi = ref(EMR.jenisTindakanRadioterapi())
let JenisKelamin = ref(EMR.JenisKelamin())
let energy = ref(EMR.energy())
let accessories: any = ref(EMR.accessories())
let posisiMeja: any = ref(EMR.formField())
const user = useUserSession().getUser().pegawai

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
  }
)
const RiwayatPsikososial: any = ref([
  { label: 'Baik', value: 'Baik' },
  { label: 'Tidak Baik', value: 'Tidak Baik' },
])

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const i: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const dataTTD: any = ref([])
const d_Perawat: any = ref([])
const d_Dokter: any = ref([])
const d_hari: any = ref([{ value: 1, label: 'Senin' }, { value: 2, label: 'Selasa' }, { value: 3, label: 'Rabu' }, { value: 4, label: 'Kamis' }, { value: 5, label: 'Jumat' }, { value: 6, label: 'Sabtu' }, { value: 7, label: 'Minggu' }])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('SuratPernyataanPengambilanKeputusanTindakanMedisPadaPasienTidakSadar') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggalAwalRawat: new Date(),
  tanggalAkhirRawat: new Date(),
  hari: null
})
const route = useRoute()
const setView = () => {
  useHead({
    title: 'Surat Pernyataan Pengambilan Keputusan Tindakan Medis Pada Pasien Tidak Sadar Tanpa Pengantar/Keluarga Terdekat' + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      H.tandaTangan().set('TTDdokter', dataTTD.value.TTDdokter)
      H.tandaTangan().set('TTDPasienKeluarga', dataTTD.value.TTDPasienKeluarga)
    }
    else {
      setAutoFill()
    }
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDdokter'] = H.tandaTangan().get('TTDdokter')
  object['TTDPasienKeluarga'] = H.tandaTangan().get('TTDPasienKeluarga')
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: route.name,
    name_form: 'Surat Pernyataan Pengambilan Keputusan Tindakan Medis Pada Pasien Tidak Sadar Tanpa Pengantar/Keluarga Terdekat',
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi().post(`/emr/simpan-emr-surket`, json).then((response: any) => {
    isLoading.value = false
    NOREC_EMRPASIEN.value = response.norec_emr
    loadRiwayat()
  })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.UmurPasien = props.pasien.umur
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.DDDokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
  input.value.ruanganPerawatan = props.registrasi.namaruangan
  input.value.tanggal = new Date()
}


const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}

const d_Ruangan: any = ref([])
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}

const d_Petugas = ref([]);

const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

setView()
loadRiwayat()
</script>
