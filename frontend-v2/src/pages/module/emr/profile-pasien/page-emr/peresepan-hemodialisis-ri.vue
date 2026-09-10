<style lang="scss"></style>
<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Peresepan Hemodialisis Rawat Inap</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"
                isHideST></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
          @close="showModalTemplate = false">
          <template #content>
            <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                <div style="overflow-y:auto;" class="mt-1">
                  <table class="tg table-tg" v-if="listTemplate.length > 0">
                    <thead>
                      <tr>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="15%">Tanggal Input</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="15%">Tanggal Registrasi</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="15%">No Registrasi</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="15%">No EMR</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="20%">Dokter</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="15%">Section</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="5%">#</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplate">
                      <tr>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.created_at }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)"
                            color="info" v-tooltip-prime.top="'Pilih'">
                          </VIconButton>
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
                  <table style="border: 1px solid black;" v-if="listTemplateFix.length > 0">
                    <thead>
                      <tr>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="5%">No</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="15%">Tanggal Dibuat</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="20%">Nama Ruangan</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="25%">Nama Template</td>
                        <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                          width="15%">#</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplateFix">
                      <tr>
                        <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.no }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.created_at }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                        <td style="width:25%;text-align:center;border:1px solid black;vertical-align: middle;">
                          <span class="mb-2">{{ resep.namatemplate }}</span><br>
                        </td>
                        <td
                          style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;padding: 3px;">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                            color="info" v-tooltip-prime.top="'Pilih'">
                          </VIconButton>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </form>
          </template>
</VModal> -->

        <!-- form baru -->
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-3">
              <span>Tanggal</span>
              <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-3">
              <span>Nama Pasien</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.namaPasien" />
              </VControl>
            </div>
            <div class="column is-3">
              <span>Umur</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.umurPasien" />
              </VControl>
            </div>
            <div class="column is-3">
              <span>Jenis Kelamin</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.jenisKelaminPasien" />
              </VControl>
            </div>
            <div class="column is-3 pt-0">
              <span>Ruangan</span>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
              </VControl>
            </div>
            <div class="column is-3 pt-0">
              <span>No CM</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.rmPasien" />
              </VControl>
            </div>
            <div class="column is-6 pt-0">
              <span>Diagnosis</span>
              <VField>
                <VTextarea rows="1" v-model="input.diagnosis"></VTextarea>
              </VField>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-4">
              <span>Riwayat HD</span>
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                      v-model="input.riwayatHD" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Reguler" label="Reguler"
                      v-model="input.riwayatHD" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Antar Waktu" label="Antar Waktu"
                      v-model="input.riwayatHD" />
                  </VControl>
                </div>
                <div class="column is-12 pt-0">
                  <span>HD Terakhir</span>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.HDTerakhir" />
                  </VControl>
                </div>
                <div class="column is-6 pt-0">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Traveling Dialisis"
                      label="Traveling Dialisis" v-model="input.travelingDialisis" />
                  </VControl>
                </div>
                <div class="column is-6 pt-0">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                      v-model="input.TDTidak" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <span>Jenis Tindakan</span>
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Cito" label="Cito"
                      v-model="input.jenisTindakan" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Elektif" label="Elektif"
                      v-model="input.jenisTindakan" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Reguler" label="Reguler"
                      v-model="input.jenisTindakan" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <span>Lama HD</span>
              <VControl class="mb-3">
                <VInput type="text" class="input" v-model="input.lamaHD" />
              </VControl>
              <span>Kec. Aliran Darah (QB)</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.aliranDarah" />
              </VControl>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-3">
              <span>Ultrafiltrasi</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.ultrafiltrasi" />
              </VControl>
            </div>
            <div class="column is-3">
              <span>Luas Membran</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.luasMembran" />
              </VControl>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Low Flux" label="Low Flux"
                      v-model="input.lowFlux" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="High Flux" label="High Flux"
                      v-model="input.highFlux" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <span>Anticoagulan</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.anticoagulan" />
              </VControl>
            </div>
            <div class="column is-3">
              <span>Akses Vaskuler</span>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="AV Shunt" label="AV Shunt"
                      v-model="input.avShunt" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="D. Lument" label="D. Lument"
                      v-model="input.dLument" />
                  </VControl>
                </div>
                <div class="column is-6 pt-0">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="V. Femoralis" label="V. Femoralis"
                      v-model="input.vFemoralis" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-6 pt-0">
              <span>Intruksi Khusus</span>
              <VField>
                <VTextarea rows="2" v-model="input.intruksiKhusus"></VTextarea>
              </VField>
            </div>
            <div class="column is-6 pt-0">
              <span>Dr. Konsultan</span>
              <VControl class="prime-auto">
                  <AutoComplete v-model="input.drKonsultan" :suggestions="d_Dokter"
                      @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                      :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                      :field="'label'" class="mt-2" />
              </VControl>
              <!-- <VControl>
                <VInput type="text" class="input" v-model="input.drKonsultan" />
              </VControl> -->
            </div>
            <div class="column is-12 pt-0 pb-0">
                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-4" style="margin-left: auto;text-align: center;">
              <span style="font-weight: bold;">Dokter Yang Meminta</span><br>
              <TandaTangan :elemenID="'TTDDokterYM'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                  <AutoComplete v-model="input.dokterYM" :suggestions="d_Dokter"
                      @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                      :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                      :field="'label'" class="mt-2" />
              </VControl>
            </div>
          </div>
        </div>
        <!-- form baru -->
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
// import InputText from 'primevue/inputtext';

useHead({ title: 'Peresepan Hemodialisis Rawat Inap - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({})
const dataTTD: any = ref([])
// const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
// const listTemplate: any = ref([])
// const showModalTemplate: any = ref(false)
// const listTemplateFix: any = ref([])
// const showModalTemplateFix: any = ref(false)

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
const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0]
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
    H.tandaTangan().set("TTDDokterYM", dataTTD.value.TTDDokterYM)
  } else {
    setAutoFill()
    // input.value.DD = { label: user.namaLengkap, value: user.id }
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  object['TTDDokterYM'] = H.tandaTangan().get("TTDDokterYM");
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
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false
  })
}
// const fetchPegawai = async (filter: any) => {
//   await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
// }
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchRuangan = async (filter: any) => {
  await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&limit=10&query=${filter.query}`).then((response) => { d_Ruangan.value = response })
}

const setAutoFill = async () => {
  let d = input.value
  d.tanggal = new Date()
  d.namaPasien = props.pasien.namapasien
  d.umurPasien = calculateAge(props.pasien.tgllahir)
  d.jenisKelaminPasien = props.pasien.jeniskelamin
  d.rmPasien = props.pasien.nocm
  d.ruangan = { value: props.registrasi.objectruanganlastfk, label: props.registrasi.namaruangan }
  d.dokterYM = { value: props.registrasi.iddokter, label: props.registrasi.dokter }
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

// const simpanTemplate = () => {
//   if (!input.value.namatemplate) {
//     H.alert('warning', "Nama Template wajib diisi")
//     console.log()
//     return;
//   }
//   let ID = input.id ? input.id : ''
//   let object: any = {}

//   object = input.value
//   object.pasien = H.setObjectPasien(props.pasien)
//   object.registrasi = H.setObjectRegistrasi(props.registrasi)
//   let json = {
//     'id': ID,
//     'norec_emr': NOREC_EMRPASIEN.value,
//     'collection': COLLECTION.value,
//     'url_form': props.FORM_URL,
//     'name_form': props.FORM_NAME,
//     'jenis_emr': 'asesmen_medis',
//     'data': object
//   }
//   isLoading.value = true

//   useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
//     isLoading.value = false
//     input.value.namatemplate = null
//   }).catch((e: any) => {
//     isLoading.value = false
//   })
// }
// const pilihTemplate = async (index: any) => {
//   isLoading.value = true
//   useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
//     isLoading.value = false
//     if (responselast.length) {
//       listTemplate.value = responselast //set ke inputan
//       showModalTemplate.value = true
//     } else {
//       H.alert('warning', 'Data tidak ada')
//     }
//   })
// }
// const addTemplate = (response: any) => {
//   input.value = response
//   delete input.value['id']
//   delete input.value['_id']
//   input.value.namatemplate = null
//   showModalTemplateFix.value = false
//   H.alert('info', 'Template berhasil ditambahkan')
// }
// const addRiwayat = (response: any) => {
//   input.value = response //set ke inputan
//   delete input.value.namatemplate;
//   delete input.value['_id'];
//   showModalTemplate.value = false
//   showModalTemplateFix.value = false
// }
// const pilihTemplateFix = async (index: any) => {
//   isLoading.value = true
//   useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
//     isLoading.value = false
//     if (responselast.length) {
//       for (var x = 0; x < responselast.length; x++) {
//         responselast[x].no = x + 1
//         responselast[x].id = ''
//       }
//       listTemplateFix.value = responselast //set ke inputan
//       showModalTemplateFix.value = true
//     } else {
//       H.alert('warning', 'Data tidak ada')
//     }
//   })
// }

// ===== ARRAY =====
// const d_perluTidakPerlu: any = ref([
//     { value: 1, label: 'Perlu' },
//     { value: 2, label: 'Tidak Perlu' }
// ])
// const d_tidakAda: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ada' }
// ])
// const d_tidakYa: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ya' }
// ])
// const d_yaTidak: any = ref([
//     { value: 1, label: 'Ya' },
//     { value: 2, label: 'Tidak' }
// ])
// const d_tidakAda_ada: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ya' }
// ])
</script>