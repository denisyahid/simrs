<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Surat Pernyataan Alih Rawat Ke Rumah Sakit Lain</h3>
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
                    <h1 style="font-weight: bold;">Yang Bertanda tangan dibawah ini :</h1>
                  </div>
                </div>

                <div class="column">
                  <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                      <span>1. Nama :</span><br>
                  </div>
                  <div class="column is-7">
                      <VField>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.namaPenangungJwb" />
                      </VControl>
                      </VField>
                  </div>
                  </div>
                  <div class="column is-12 is-flex ml-5">
                      <div class="column is-2" style="margin-top: 10px;">
                          <span>2. Umur</span>
                      </div>
                      <div class="column is-3">
                          <VField class="mt-3">
                              <VControl>
                                  <VInput type="text" class="input" v-model="input.UmurPenangungJwb" />
                              </VControl>
                          </VField>
                      </div>
                      <div class="column is-2" style="margin-top: 10px;">
                          <span>Jenis Kelamin:</span>
                      </div>
                      <div class="column is-4" style="display: flex;margin-top: 10px;">
                      <VField v-for="items in JenisKelamin" :key="items.value">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.jenisKelaminPenangungJwb" class="pt-1 pb-1" :true-value="items.label"
                            :label="items.label" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column is-12 is-flex ml-5">
                      <div class="column is-2">
                          <span>4. Alamat :</span>
                      </div>
                      <div class="column is-6">
                          <VField>
                              <VTextarea rows="2" v-model="input.alamatPenangungJwb"></VTextarea>
                          </VField>
                      </div>
                  </div>
                  <div class="column is-12 is-flex ml-5">
                      <div class="column is-2" style="margin-top: 10px;">
                          <span>5. Pekerjaan :</span>
                      </div>
                      <div class="column is-3">
                          <VField class="mt-3">
                              <VControl>
                                  <VInput type="text" class="input" v-model="input.pekerjaanpenannggungjawab" />
                              </VControl>
                          </VField>
                      </div>
                    </div>
                  <div class="column is-12 is-flex ml-5">
                      <div class="column is-3">
                          <span>6. Kartu Tanda Pengenal :</span>
                      </div>
                      <div class="column is-2">
                          <VControl raw subcontrol>
                              <VCheckbox
                                  class="p-0"
                                  color="primary"
                                  square
                                  :true-value="KTP"
                                  label="KTP"
                                  v-model="input.KTP"
                              />
                          </VControl>
                      </div>
                      <div class="column is-2">
                          <VControl raw subcontrol>
                              <VCheckbox
                                  class="p-0"
                                  color="primary"
                                  square
                                  :true-value="SIM"
                                  label="SIM"
                                  v-model="input.SIM"
                              />
                          </VControl>
                      </div>
                      <div class="column is-2">
                          <VControl raw subcontrol>
                              <VCheckbox
                                  class="p-0"
                                  color="primary"
                                  square
                                  :true-value="Paspor"
                                  label="Paspor, Nomor"
                                  v-model="input.Paspor"
                              />
                          </VControl>
                      </div>
                      <div class="column is-3" style="margin-top: -10px;">
                        <VField class="mt-3">
                              <VControl>
                                  <VInput type="text" class="input" v-model="input.Nomor" />
                              </VControl>
                          </VField>
                      </div>
                    </div>
                    <div class="column is-12 is-flex ml-5">
                        <div class="column is-3" style="margin-top: 10px;">
                        <span>7. Hubungan dengan pasien :</span><br>
                        </div>
                        <VField class="column is-4">
                            <VControl class="prime-auto">
                            <AutoComplete v-model="input.penanggungjawab" :suggestions="d_PenangungJwb" @complete="fetchPenanggungJawab($event)" :optionLabel="'label'"
                                :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Cari ..." class="mt-2" />
                            </VControl>
                        </VField>
                    </div> 
                    <div class="column is-12 is-flex ml-5">
                      <div class="column is-2" style="margin-top: 10px;">
                          <span>8. No. Telp/HP :</span>
                      </div>
                      <div class="column is-3">
                          <VField class="mt-3">
                              <VControl>
                                  <VInput type="text" class="input" v-model="input.nohppenanggungjawab" />
                              </VControl>
                          </VField>
                      </div>
                    </div>
                    <div class="column is-12 is-flex ml-5">
                      <div class="column is-6" style="margin-top: 10px;">
                          <span style="font-weight: bold;">Dengan ini menyatakan setuju untuk melakukan alih rawat inap ke Rumah Sakit:</span>
                      </div>
                      <div class="column is-6">
                          <VField class="mt-3">
                              <VControl>
                                  <VInput type="text" class="input" v-model="input.nohp" />
                              </VControl>
                          </VField>
                      </div>
                    </div>
                </div>

                  <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                      <span>a. Nama :</span><br>
                  </div>
                  <div class="column is-7">
                      <VField>
                      <VControl>
                          <VInput type="text" class="input" v-model="input.namaPasien" />
                      </VControl>
                      </VField>
                  </div>
                  </div>
                  <div class="column is-12 is-flex ml-5">
                      <div class="column is-2" style="margin-top: 10px;">
                          <span>b. Umur</span>
                      </div>
                      <div class="column is-3">
                          <VField class="mt-3">
                              <VControl>
                                  <VInput type="text" class="input" v-model="input.UmurPasien" />
                              </VControl>
                          </VField>
                      </div>
                      <div class="column is-2" style="margin-top: 10px;">
                          <span>Jenis Kelamin:</span>
                      </div>
                      <div class="column is-4" style="display: flex;margin-top: 10px;">
                      <VField v-for="items in JenisKelamin" :key="items.value">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                            :label="items.label" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-12 is-flex ml-5">
                    <div class="column is-2 pb-0">
                      <span class="label-pso">c. Agama</span>
                    </div>
                    <div class="column is-3 pb-0">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.AgamaPasien" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column is-12 is-flex ml-5">
                      <div class="column is-2">
                          <span>d. Alamat :</span>
                      </div>
                      <div class="column is-6">
                          <VField>
                              <VTextarea rows="2" v-model="input.alamatPasien"></VTextarea>
                          </VField>
                      </div>
                  </div>
                  <div class="column is-12 is-flex ml-5">
                    <div class="column is-2 pb-0">
                      <span class="label-pso">e. Diagnosa</span>
                    </div>
                    <div class="column is-7 pb-0">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.DiagnosaPasien" />
                        </VControl>
                      </VField>
                    </div>
                  </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;">Dengan Alasan:</h1>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column">
                    <div class="column is-12 pl-5 pr-5 pb-0">
                        <p class="pb-3 p-justify">
                          a. Rawat Inap di Rumah Sakit Umum Bali Mandara
                        </p>
                        <p class="pb-3 p-justify">
                          b. Keluarga saya ingin dirawat di rumah sakit tersebut
                        </p>
                        <p class="pb-3 p-justify">
                          c. Rujukan / Perawatan / Tindak lanjut
                        </p>
                    </div>
                  </div>
                </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1>Segala penjelasan di Admission, dokter jaga IGD, Poliklinik, MOD, DPJP, tanpa paksaan dari pihak manapun, segala resiko saya siap bertanggung jawab</h1>
                  </div>
                </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1>Demikian surat pernyataan ini saya buat, untuk dapat dipergunakan sebagaimana mestinya</h1>
                  </div>
                </div>

               
                <div class="column is-12 is-flex">
                  <div class="column is-4"></div>
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
                  <div class="column is-4"></div>
                  <div class="column is-4" style="text-align: center">
                    <span>Yang membuat pernyataan</span><br>
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
                <div class="column is-12 is-flex ml-5 justify-content-start">
                  <div class="column is-4" style="text-align: center">
                    <span>Mengetahui </span><br>
                    <span>Petugas Admisssion</span><br>
                    <TandaTangan :elemenID="'TTDPetugas'" :width="'150'" :height="'150'" class="dek" />
                    <VField>
                      <VControl>
                        <AutoComplete v-model="input.PetugasAdmission" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                   <div class="column is-4"></div>
                   <div class="column is-4" style="text-align: center">
                    <span>Dokter Jaga / Poliklinik / MOD / DPJP</span><br>
                    <TandaTangan :elemenID="'TTDdokter'" :width="'150'" :height="'150'" class="dek" />
                    <VField>
                      <VControl>
                        <AutoComplete v-model="input.Dokterjaga" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
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
const d_PenangungJwb: any = ref([])
const d_Dokter: any = ref([])
const d_hari: any = ref([{ value: 1, label: 'Senin' }, { value: 2, label: 'Selasa' }, { value: 3, label: 'Rabu' }, { value: 4, label: 'Kamis' }, { value: 5, label: 'Jumat' }, { value: 6, label: 'Sabtu' }, { value: 7, label: 'Minggu' }])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('SuratPernyataanAlihRawatKeRumahSakitLain') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggalAwalRawat: new Date(),
  tanggalAkhirRawat: new Date(),
  hari: null
})
const route = useRoute()
const setView = () => {
  useHead({
    title: 'Surat Pernyataan Alih Ke Rumah Sakit Lain' + ' - ' + import.meta.env.VITE_PROJECT,
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
      H.tandaTangan().set('TTDPetugas', dataTTD.value.TTDPetugas)
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
  object['TTDPetugas'] = H.tandaTangan().get('TTDPetugas')
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: route.name,
    name_form: 'Surat Pernyataan Alih Ke Rumah Sakit Lain',
    jenis_emr: 'asesmen_medis',
    data: object,
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

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.UmurPasien = props.pasien.umur
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.DDDokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
  input.value.ruanganPerawatan = props.registrasi.namaruangan
  input.value.tanggal = new Date()
  input.value.AgamaPasien = props.pasien.agama
  input.value.alamatPasien = props.pasien.alamatlengkap
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

const fetchPenanggungJawab = async (filter: any) => {

await useApi().get(
    `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
).then((response) => {
    d_PenangungJwb.value = response
})
}

setView()
loadRiwayat()
</script>
