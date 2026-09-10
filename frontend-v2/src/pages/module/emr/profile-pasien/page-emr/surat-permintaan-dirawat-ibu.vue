<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Surat Permintaan Dirawat Bayi</h3>
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
            <Fieldset legend="PERMINTAAN DIRAWAT" :toggleable="true">
              <div class="columns is-multiline">
                <div class="column is-12 is-flex">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Dari:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Ketik untuk mencari poliklinik ..." class="mt-2" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1>Kepada Yth.Petugas Admission:</h1>
                  </div>
                </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1>Mohon ditindak lanjuti permintaan dirawat inap pasien :</h1>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">NAMA PASIEN:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput v-model="input.namaPasien" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"/>
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
                  </div>
                  <div class="column is-10" style="display: flex">
                    <VField v-for="items in JenisKelamin" :key="items.value">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                          :label="items.label" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">No. Rekam Medis:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div v-if="isVisible" class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">No SPRI:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="No SPRI" v-model="input.nosurat" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Diagnosa:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.diagnosa" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Rencana Tindakan:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.rencanaTindakan" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">TANGGAL TINDAKAN:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VDatePicker v-model="input.tanggalTindakan" mode="date" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">TANGGAL RAWAT INAP:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VDatePicker v-model="input.tanggalRawatInap" mode="date" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Dokter yang Merawat (DPJP):</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Persiapan:</h1>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.persiapan" true-value="Puasa" color="primary" label="Puasa" />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.persiapan" true-value="Diet" color="primary" label="Diet" />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.persiapan" true-value="Lainnya" color="primary" label="Lainnya" />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <VInput v-model="input.ketPersiapanLainnya" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.persiapan" true-value="Tidak Perlu Persiapan" color="primary"
                          label="Tidak Perlu Persiapan" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Kebutuhan Pelayanan Pasien:</h1>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.pelayananPasien" true-value="Preventif" color="primary"
                          label="Preventif" />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.pelayananPasien" true-value="Kuratif" color="primary"
                          label="Kuratif" />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-3">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.pelayananPasien" true-value="Rehabilitatif" color="primary"
                          label="Rehabilitatif" />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.pelayananPasien" true-value="Paliatif" color="primary"
                          label="Paliatif" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-flex justify-content-end">
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

                <div class="column is-12 is-flex ml-5 justify-content-end">
                  <div class="column is-4" style="text-align: center">
                    <h1 style="font-weight: bold">Dokter Yang Merawat</h1>
                    <TandaTangan :elemenID="'TTDdokter'" :width="'150'" :height="'150'" class="dek" style="display: none !important"/>
                    <VField>
                      <VControl>
                        <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>

            <Fieldset legend="KEPASTIAN TEMPAT DIRAWAT" :toggleable="true">
              <div class="columns is-multiline">
                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1 style="font-weight: bold">
                      Kepada Yth Dokter: {{ input.DDDokter?.label }}
                    </h1>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12 is-flex">
                  <div class="column is-12m is-flex">
                    <h1 style="font-weight: bold; padding: 10px">Pasien Tersebut diatas</h1>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.ruanganKeterangan" true-value="Telah Mendapatkan" color="primary"
                          label="Telah Mendapatkan" />
                      </VControl>
                    </VField>

                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.ruanganKeterangan" true-value="Tidak Mendapatkan" color="primary"
                          label="Tidak Mendapatkan" />
                      </VControl>
                    </VField>
                    <h1 style="font-weight: bold; padding: 10px">Rawat Inap</h1>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Ruangan Rawat Inap:</h1>
                  </div>
                  <div class="column is-10">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.ruanganRawatInap" />
                    </VControl>
                    <!-- <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.ruanganRawatInap" :suggestions="d_Ruangan"
                          @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Ketik untuk mencari ruangan" class="mt-2" />
                      </VControl>
                    </VField> -->
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Cara Pembayaran :</h1>
                  </div>
                  <div class="is-10 is-flex">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.pembayaran" true-value="Umum" color="primary" label="Umum" />
                      </VControl>
                    </VField>

                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.pembayaran" true-value="BPJS PBI" color="primary" label="BPJS PBI" />
                      </VControl>
                    </VField>

                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.pembayaran" true-value="BPJS NON PBI" color="primary"
                          label="BPJS NON PBI" />
                      </VControl>
                    </VField>

                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.pembayaran" true-value="ASURANSI LAINNYA" color="primary"
                          label="ASURANSI LAINNYA" />
                      </VControl>
                    </VField>

                    <VField>
                      <VControl>
                        <VInput v-model="input.ketPembayaran" class="input" type="text" placeholder="Ket. Pembayaran" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1 style="font-weight: bold">Nama Penanggung Jawab:</h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <VInput v-model="input.kepalaPenanggungJawab" class="input" type="text"
                          placeholder="Nama Penanggung Jawab" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1 style="font-weight: bold">Hubungan dengan Pasien:</h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <VInput v-model="input.hubungan" class="input" type="text"
                          placeholder="Hubungan dengan Pasien" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1 style="font-weight: bold">Alamat:</h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.alamat" class="input" type="text" placeholder="Alamat" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1 style="font-weight: bold">No. Telepon:</h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <VInput v-model="input.telepon" class="input" type="text" placeholder="No. Telepon" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-6"></div>
                <div class="column is-6 is-flex" style="justify-content: center;">
                  <VField>
                    <h1 style="font-weight: bold">Garut</h1>
                    <VDatePicker v-model="input.tanggalAdmission" color="green" trim-weeks mode="dateTime"
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

                <div class="column is-12 is-flex ml-5 justify-content-between">
                  <div class="column is-6" style="text-align: center">
                    <h1 style="font-weight: bold">Pasien / Penanggung Jawab</h1>
                    <TandaTangan :elemenID="'TTDpasien'" :width="'150'" :height="'150'" class="dek" />
                    <VField>
                      <VControl>
                        <VInput v-model="input.CBPasien" class="input" type="text"
                          placeholder="Pasien / Penanggung Jawab" />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column is-6" style="text-align: center">
                    <h1 style="font-weight: bold">Petugas Admission</h1>
                    <TandaTangan :elemenID="'TTDadmission'" :width="'150'" :height="'150'" class="dek" />
                    <VField>
                      <VControl>
                        <AutoComplete v-model="input.petugasAddmision" :suggestions="d_Petugas"
                          @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
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
import { useRoute, onBeforeRouteLeave } from 'vue-router'
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
const isVisible = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('SuratPermintaanDirawatIbu') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggalKontrol: new Date(),
  tanggal: new Date(),
})
const route = useRoute()
const setView = () => {
  useHead({
    title: 'Surat Permintaan Dirawat Bayi' + ' - ' + import.meta.env.VITE_PROJECT,
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
      setAutoFill2()
      dataTTD.value = response[0]
      H.tandaTangan().set('TTDdokter', dataTTD.value.TTDdokter)
      H.tandaTangan().set('TTDpasien', dataTTD.value.TTDpasien)
      H.tandaTangan().set('TTDadmission', dataTTD.value.TTDadmission)
    }
    else {
      setAutoFill()
      setAutoFill2()
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
  object['TTDpasien'] = H.tandaTangan().get('TTDpasien')
  object['TTDadmission'] = H.tandaTangan().get('TTDadmission')
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: route.name,
    name_form: 'Surat Permintaan Dirawat Bayi',
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi().post(`/emr/simpan-emr-surket`, json).then((response: any) => {
    isLoading.value = false
    NOREC_EMRPASIEN.value = response.norec_emr
    sudahDisimpan.value = true
    loadRiwayat()
  })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const setAutoFill = async () => {
        const response = await useApi().get(
            `/emr/get-ibu?nocm=${props.pasien.nocm}`
          )
          if(response.length > 0){
            const dataIbu = await useApi().get("emr/auto-fill?nocmfk=" + response[0].nocmfk + "&norec_pd=" + response[0].norec_pd + "&collection=SuratPermintaanDirawat")
            if (dataIbu != null) {
              //input.value = dataIbu
              //input.value.emrpasienfk = null
                input.value.ruangan = dataIbu.ruangan
                //input.value.namaPasien = dataIbu.namaPasien
                //input.value.tanggalLahirPasien = dataIbu.tanggalLahirPasien
                //input.value.jeniskelamin = dataIbu.jeniskelamin
                //input.value.norm = dataIbu.norm
                input.value.namaPasien = props.pasien.namapasien
                input.value.jeniskelamin = props.pasien.jeniskelamin
                input.value.norm = props.pasien.nocm
                input.value.tanggalLahirPasien = props.pasien.tgllahir
                input.value.nosurat = dataIbu.nosurat
                input.value.diagnosa = dataIbu.diagnosa
                input.value.rencanaTindakan = dataIbu.rencanaTindakan
                input.value.tanggalTindakan = dataIbu.tanggalTindakan
                input.value.tanggalRawatInap = dataIbu.tanggalRawatInap
                input.value.DDDokter = dataIbu.DDDokter
                input.value.persiapan = dataIbu.persiapan
                input.value.ketPersiapanLainnya = dataIbu.ketPersiapanLainnya
                input.value.persiapan = dataIbu.persiapan
                input.value.pelayananPasien = dataIbu.pelayananPasien
                input.value.tanggal = dataIbu.tanggal
                input.value.DDDokter = dataIbu.DDDokter
                input.value.ruanganKeterangan = dataIbu.ruanganKeterangan
                input.value.ruanganRawatInap = dataIbu.ruanganRawatInap
                input.value.pembayaran = dataIbu.pembayaran
                input.value.ketPembayaran = dataIbu.ketPembayaran
                input.value.kepalaPenanggungJawab = dataIbu.kepalaPenanggungJawab
                input.value.hubungan = dataIbu.hubungan
                input.value.alamat = dataIbu.alamat
                input.value.telepon = dataIbu.telepon
                input.value.tanggalAdmission = dataIbu.tanggalAdmission
                input.value.CBPasien = dataIbu.CBPasien
                input.value.petugasAddmision = dataIbu.petugasAddmision
            } else{
              setAutoFillAfter()
            }
          } else{
            setAutoFillAfter()
          }
}

const setAutoFillAfter = async () => {
  
  
  input.value.alamat = props.pasien.alamatlengkap
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi
  input.value.DDDokter =  { label: props.registrasi?.dokter || '', value: props.registrasi?.iddokter || ''};
  input.value.ruangan = props.registrasi.namaruangan
  input.value.telepon = props.pasien.nohp
  input.value.kepalaPenanggungJawab = props.pasien.penanggungjawab ?? '-'
  input.value.tglPembuatan = new Date()
  input.value.tanggalAdmission = new Date()
  input.value.hubungan = props.pasien.hubungankeluarga ?? '-'
  input.value.tanggalTindakan = new Date()
  input.value.tanggalRawatInap = new Date()
  const response_AsmedIGD = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenAwalMedisGawatDarurat" + `&field=TADiagnosis`)
  if (response_AsmedIGD != null) {
    input.value.diagnosa = response_AsmedIGD.TADiagnosis
  }

  const response_RingkasanPulang = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=RingkasanPulang" + `&field=DiagnosaUtama,TindakanDikerjakan`)
  if (response_RingkasanPulang != null) {
    input.value.diagnosa = response_RingkasanPulang.DiagnosaUtama
    input.value.rencanaTindakan = response_RingkasanPulang.TindakanDikerjakan
  }
}

const setAutoFill2 = async () => {
  input.value.nosurat = props.registrasi.nosurat
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
let sudahDisimpan = ref(false)
onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
        if (!sudahDisimpan.value) {
            console.log("DISIMPAN",sudahDisimpan.value);

            const konfirmasi = H.alert('warning', 'Belum Disimpan!!!');
            if (!konfirmasi) {
            return next(false);
            }
        }

    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});

setView()
loadRiwayat()
</script>
