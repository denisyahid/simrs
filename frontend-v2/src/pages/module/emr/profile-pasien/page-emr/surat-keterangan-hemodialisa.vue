<style lang="scss"></style>

<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Surat Keterangan Hemodialisa</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :isHideCetakWNA="false">
              </ButtonEmr>
            </div>
          </div>
          <!-- <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
              @click="pilihTemplateFix(index)"> Pilih Template
            </VButton>
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
              @click="pilihTemplate(index)"> Pilih Riwayat
            </VButton>
          </div>
          <div class="column is-12">
            <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                template</span></h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.namatemplate" rows="1">
                </VTextarea>
              </VControl>
            </VField>
          </div> -->
        </div>

        <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
              @click="pilihTemplateFix(index)"> Pilih Template
            </VButton>
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
              @click="pilihTemplate(index)"> Pilih Riwayat
            </VButton>
          </div>
          <div class="column is-12">
            <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                template</span></h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.namatemplate" rows="1">
                </VTextarea>
              </VControl>
            </VField>
          </div>

        <!-- form baru -->
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-12 pb-0" style="font-weight: bold;">Yang Bertanda Tangan Dibawah Ini :
            </div>
            <div class="column is-6 pt-1">
              <h2>Nama Dokter</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.DDDokter" disabled />
              </VControl>
            </div>
            <div class="column is-6 pt-1">
              <h2>Jabatan</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.jabatanDokter" disabled />
              </VControl>
            </div>
            <div class="column is-6 pt-1">
              <h2>Alamat</h2>
              <VField>
                <VTextarea rows="1" v-model="input.alamatDokter" disabled></VTextarea>
              </VField>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-12 pb-0" style="font-weight: bold;">Menerangkan Bahwa :</div>
            <div class="column is-6">
              <h2>Nama Pasien</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.namaPasien" />
              </VControl>
            </div>
            <div class="column is-6">
              <h2>Nomor Rekam Medis</h2>
              <VControl>
                <VInput type="text" class="input" v-model="input.rmPasien" />
              </VControl>
            </div>
            <div class="column is-6 pt-1">
              <h2>Tanggal Lahir</h2>
              <VDatePicker v-model="input.tglLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-6 pt-1">
              <h2>Alamat</h2>
              <VField>
                <VTextarea rows="2" v-model="input.alamatPasien"></VTextarea>
              </VField>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-12 pb-0">
              <span>
                Memang benar pasien tersebut diatas melakukan pemeriksaan di RSUD Bali Mandara dengan
                diagnosis
              </span>
            </div>
            <div class="column is-12 pt-0">
              <VField>
                <VTextarea rows="1" v-model="input.diagnosis" placeholder="Diagnosis..."></VTextarea>
              </VField>
            </div>
            <div class="column is-12 pb-0">
              <span>Pasien memerlukan terapi Hemodialisis sebanyak 3 X dalam seminggu dari </span>
            </div>
            <div class="column is-6 pt-0">
              <span>Tanggal</span>
              <VDatePicker v-model="input.tglAwal" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-6 pt-0">
              <span>Sampai dengan tanggal</span>
              <VDatePicker v-model="input.tglAkhir" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-12 pb-0">
              <span>
                Atas indikasi
              </span>
            </div>
            <div class="column is-12 pt-0">
              <VField>
                <VTextarea rows="1" v-model="input.indikasi" placeholder="Indikasi..."></VTextarea>
              </VField>
            </div>
            <div class="column is-12 pt-0">
              <span>
                Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.
              </span>
            </div>
          </div>
        </div>


        <!-- form baru -->

        <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
          @close="showModalTemplate = false">
          <template #content>
            <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                <div style="overflow-y:auto;" class="mt-1">
                  <table class="tg table-tg" v-if="listTemplate.length > 0">
                    <thead>
                      <tr>
                        <td class="tg-0lax text-center" width="5%">#</td>
                        <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                        <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                        <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                        <td class="tg-0lax text-center" width="20%">No EMR</td>
                        <td class="tg-0lax text-center" width="20%">Dokter</td>
                        <td class="tg-0lax text-center" width="20%">Penyakit</td>
                        <td class="tg-0lax text-center" width="20%">Section</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplate">
                      <tr>
                        <td style="width:5%;text-align:center">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                            color="info" v-tooltip-prime.top="'Pilih'">
                          </VIconButton>
                        </td>
                        <td style="width:25%;text-align:center">
                          <span class="mb-2">{{ resep.created_at }}</span><br>
                        </td>
                        <td style="width:25%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                        </td>
                        <td style="width:25%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.riwayatpenyakit }}</span><br>
                        </td>
                        <td style="width:25%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </form>
          </template>
        </VModal>
        <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
          @close="showModalTemplateFix = false">
          <template #content>
            <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                <span style="font-size:9pt;font-weight:bold">List Template</span>
                <div style="overflow-y:auto;" class="mt-1">
                  <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                    <thead>
                      <tr>
                        <td class="tg-0lax text-center" width="15%">#</td>
                        <td class="tg-0lax text-center" width="15%">No</td>
                        <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                        <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                        <td class="tg-0lax text-center" width="50%">Nama Template</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplateFix">
                      <tr>
                        <td style="width:15%;text-align:center">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                            color="info" v-tooltip-prime.top="'Pilih'">
                          </VIconButton>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.no }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.created_at }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                        <td style="width:50%;text-align:center">
                          <span class="mb-2">{{ resep.namatemplate }}</span><br>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </form>
          </template>
        </VModal>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import moment from 'moment'
import { useConfirm } from "primevue/useconfirm"

useHead({ title: 'Surat Keterangan Hemodialisa - ' + import.meta.env.VITE_PROJECT, })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
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
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const route = useRoute()
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const d_Diagnosa: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({})
const COLLECTION: any = ref('SuratKeteranganHemodialisa') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({});
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const isAktive = ref()
const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  } else {
    let d = input.value
    d.DDDokter = 'Prof. Dr. dr. I Gde Raka Widiana, Sp.PD-KGH'
    d.jabatanDokter = 'Dokter Spesialis Penyakit Dalam Konsultan Ginjal dan Hipertensi'
    d.alamatDokter = 'RSUD Bali Mandara, Jl. By Pass Ngurah Rai No. 548, Garut'
    d.namaPasien = props.pasien.namapasien
    d.rmPasien = props.pasien.nocm
    d.jenisKelaminPasien = props.pasien.jeniskelamin
    d.tglLahirPasien = props.pasien.tgllahir
    d.alamatPasien = props.pasien.alamatlengkap
    d.kondisiParu = 'Sehat/Normal'
  }
}
const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': route.name,
    'name_form': 'Surat Keterangan Hemodialisa',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }

  isLoading.value = true
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      listTemplate.value = responselast //set ke inputan
      showModalTemplate.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&nocmfk=${ID_PASIEN}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      for (var x = 0; x < responselast.length; x++) {
        responselast[x].no = x + 1
      }
      listTemplateFix.value = responselast //set ke inputan
      showModalTemplateFix.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}
const addTemplate = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  input.value['id'] = ''
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
  let ID = input.id ? input.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
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

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    input.value.namatemplate = null
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  // NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
function calculateAge(birthdate) {
  const today = new Date();
  const birthDate = new Date(birthdate);
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  return age;
}
onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
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
const d_kondisiParu: any = ref([
  { value: 'Sehat/Normal', label: 'Sehat/Normal' },
  { value: 'Tidak Sehat/Abnormal', label: 'Tidak Sehat/Abnormal' }
])
</script>
