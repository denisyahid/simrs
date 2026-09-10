<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
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
                    <h1>Saya yang bertanda tangan dibawah ini :</h1>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2"  style="margin-top: 10px;">
                    <h1>Nama :</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <AutoComplete v-model="input.NamaPetugas" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2"  style="margin-top: 10px;">
                    <h1>Jabatan :</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput v-model="input.jabatan" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2" style="margin-top: 10px;">
                    <h1>NIP :</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput v-model="input.NIP" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1>Menerangkan dengan sebenarnya bahwa :</h1>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2"  style="margin-top: 10px;">
                    <h1>Nama :</h1>
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
                  <div class="column is-2" style="margin-top: 10px;">
                    <h1>Tanggal Lahir / Umur:</h1>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <VInput v-model="input.Umurpasien" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <h1>JENIS KELAMIN:</h1>
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
                  <div class="column is-2">
                    <h1>Alamat:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.alamat" class="input" type="text" placeholder="Alamat" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1>Diagnosa:</h1>
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
                  <div class="column is-2" style="margin-top: 10px;">
                    <h1>Dirawat dari Tanggal:</h1>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VDatePicker v-model="input.tanggalAwalRawat" mode="date" trim-weeks>
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
                  <div class="column is-1" style="text-align: center;margin-top: 10px;">
                    <span>s/d</span>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VDatePicker v-model="input.tanggalAkhirRawat" mode="date" trim-weeks>
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
                  <div class="column is-2">
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" v-model="input.hari" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>Hari</VButton>
                        </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3" style="margin-top: 10px;">
                    <h1>Ruang Perawatan : <b>Ruang</b></h1>
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

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1>Demikian Surat Keterangan ini dibuat dengan sebenarnya untuk keperluan sebagaimana semestinya.</h1>
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
                    <span>Dokter penanggung Jawab Pasien</span><br>
                    <TandaTangan :elemenID="'TTDdokter'" :width="'150'" :height="'150'" class="dek" />
                    <VField>
                      <VControl>
                        <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5 justify-content-end">
                  <div class="column is-2" style="text-align: right;margin-top: 10px;"><span>NIP:</span></div>
                  <div class="column is-4" style="text-align: center">
                      <VField>
                        <VControl>
                          <VInput v-model="input.NIP" class="input" type="text" />
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
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('SuratKeteranganDirawatOpname') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggalAwalRawat: new Date(),
  tanggalAkhirRawat: new Date(),
  hari: null
})
const route = useRoute()
const setView = () => {
  useHead({
    title: 'Surat Keterangan Dirawat/ Opname' + ' - ' + import.meta.env.VITE_PROJECT,
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
      H.tandaTangan().set('TTDpasien', dataTTD.value.TTDpasien)
      H.tandaTangan().set('TTDadmission', dataTTD.value.TTDadmission)
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
  object['TTDpasien'] = H.tandaTangan().get('TTDpasien')
  object['TTDadmission'] = H.tandaTangan().get('TTDadmission')
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: route.name,
    name_form: 'Surat Keterangan Dirawat/ Opname',
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
  input.value.NamaPetugas = { label: user.namaLengkap, value: user.id }
  input.value.namaPasien = props.pasien.namapasien
  input.value.CBPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.Umurpasien = props.pasien.umur
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.alamat = props.pasien.alamatlengkap
  input.value.pekerjaan = props.pasien.pekerjaan
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi
  input.value.DDDokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
  input.value.telepon = props.pasien.nohp
  input.value.kepalaPenanggungJawab = props.pasien.penanggungjawab ?? '-'
  input.value.tanggal = new Date()
  input.value.tanggalAdmission = new Date()
  input.value.hubungan = props.pasien.hubungankeluarga ?? '-'
  input.value.tanggalTindakan = new Date()
  input.value.tanggalRawatInap = new Date()
  input.value.NIP = props.registrasi.nip
  input.value.ruanganPerawatan = props.registrasi.namaruangan
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

function parseTanggal(tanggalStr: string) {
  const [dd, mm, yyyy] = tanggalStr.split('-')
  return new Date(`${yyyy}-${mm}-${dd}`)
}


watch(
  () => [input.value.tanggalAwalRawat, input.value.tanggalAkhirRawat],
  ([awal, akhir]) => {
    if (awal && akhir) {
      // Tidak perlu parse kalau sudah Date
      const tglAwal = new Date(awal)
      const tglAkhir = new Date(akhir)

      const selisihMs = tglAkhir.getTime() - tglAwal.getTime()
      const selisihHari = Math.ceil(selisihMs / (1000 * 60 * 60 * 24))

      input.value.hari = selisihHari >= 0 ? selisihHari : 0
    } else {
      input.value.hari = null
    }
  },
  { immediate: true }
)




setView()
loadRiwayat()
</script>
