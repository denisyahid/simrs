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
  <div class="column">
    <div class="columns is-multiline">
      <div class="column is-12">
        <VCard>
          <h1 style="font-weight: bold; margin-bottom: 1.5rem;">1. Persetujuan Tindakan Anestesi dan Sedasi Sedang dan
            Dalam
          </h1>
          <div class="columns is-multiline">
            <div class="column is-6" v-for="(data) in antisipasiKondisi">
              <p style="font-weight: light; margin-bottom: 1rem;">{{ data.label }}
              </p>
              <VField>
                <VControl>
                  <VInput type="text" v-model="input[data.model]" :placeholder="data.label" />
                </VControl>
              </VField>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
        <VCard>
          <h1 style="font-weight: bold; margin-bottom: 1.5rem;">2. Informasi Anestesi
          </h1>
          <div class="columns is-multiline">
            <table align="center" class="triase" style="width: 90%; margin-top: 2rem;">
              <tr>
                <th colspan="4" style="text-align : center"> PEMBERIAN INFORMASI </th>
              </tr>
              <tr>
                <th style="text-align : center"> No </th>
                <th style="text-align : center"> Jenis Informasi </th>
                <th style="text-align: center"> Isi Informasi </th>
                <th style="text-align: center"> Checklist </th>
              </tr>
              <tr v-for="(datas) in DaftarInformasi">
                <td>{{ datas.no }}</td>
                <td>{{ datas.Kriteria }}</td>
                <td>
                  <div class="columns is 12">
                    <div class="column" v-for="(dot) in datas.keterangan">
                      <VField v-if="dot.type == 'checkBox'">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input[dot.model]" :true-value="dot.title" :label="dot.title" color="primary"
                            square />
                        </VControl>
                      </VField>

                      <VField v-else="dot.type == 'textArea'">
                        <VControl>
                          <VTextarea rows="2" v-model="input[dot.model]" placeholder="Isi Informasi..." />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <VField v-for="(check) in datas.pilihan">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" v-model="input[check.model]" :true-value="check.label"
                            :label="check.label" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
            <div class="column is-12 pb-1">
              <h2 style="font-weight: bold;"> Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas
                secara benar dan jelas dan memberikan kesempatan untuk bertanya dan/ atau berdiskusi</h2>
              <div class="columns is-multiline" style="margin-top: 1rem;">
                <div class="column is-12">
                  <div class="columns is-12" style="text-align: center;">
                    <div class="column is-4">
                    </div>
                    <div class="column is-4" style="text-align: center;">
                      <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                      <VField class="is-autocomplete-select mt-3" v-slot="{ id }">

                        <VControl icon="feather:search">
                          <AutoComplete v-model="input.namaPegawai" :suggestions="d_Pegawai"
                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Cari nama Pegawai" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12 pb-1">
              <h2 style="font-weight: bold;"> Dengan ini menyatakan bahwa saya telah menerima informasi
                sebagaimana di atas yang saya beri tanda paraf di kolom kanannya dan telah memahaminya</h2>
              <div class="columns is-multiline" style="margin-top: 1rem;">
                <div class="column is-12">
                  <div class="columns is-12" style="text-align: center;">
                    <div class="column is-4">
                    </div>
                    <div class="column is-4" style="text-align: center;">
                      <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                      <VField class="mt-3">
                        <VControl>
                          <VInput type="text" v-model="input.namaTTD" placeholder="Nama Lengkap" />
                        </VControl>
                      </VField>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
        <VCard>
          <h1 style="font-weight: bold; margin-bottom: 1.5rem;">3. Persetujuan Tindakan Anestesi
          </h1>
          <div class="column is-12">
            <h1 style="font-weight: bold;">Yang bertanda tangan dibawah ini :
            </h1>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Nama Lengkap : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nama Lengkap" v-model="input.nama" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Umur : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Umur" v-model="input.umur" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Jenis Kelamin : </h1>
                </div>
                <div class="columns pl-2 is-6" style=" margin-top: 1rem">
                  <VField v-for="items in JenisKelamin" :key="items.value" style="padding:0px;">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.jenisKelamin" class="pt-1 pb-1 " :true-value="items.label"
                        :label="items.label" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Alamat : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VTextarea rows="2" v-model="input.alamat" placeholder="Alamat..." />
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex" style="margin-top:0.5rem">
                <div class="column is-5">
                  <h1 style="font-weight: bold">
                    dengan ini menyatakan persetujuan untuk dilakukan tindakan anestesi terhadapt saya /
                  </h1>
                </div>
                <div class="column  is-3" style="margin-top:-1rem;">
                  <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="fa:users" fullwidth class="prime-auto ">
                      <AutoComplete v-model="input.hubunganPenjamin" :suggestions="d_Hubungan"
                        @complete="fetchHubungan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> saya, Yang bernama : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nama Lengkap" v-model="input.namaPasien" />
                    </VControl>

                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Umur : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Umur" v-model="input.umurPasien" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Jenis Kelamin : </h1>
                </div>
                <div class="columns pl-2 is-6" style=" margin-top: 1rem">
                  <VField v-for="items in JenisKelamin" :key="items.value" style="padding:0px;">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.jenisKelaminPasien" class="pt-1 pb-1 " :true-value="items.label"
                        :label="items.label" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <h1 style="font-weight: bold;"> Alamat : </h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VTextarea rows="2" v-model="input.alamatPasien" placeholder="Alamat..." />
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <h2 style="font-weight:bold">
                Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan
                seperti di atas kepada saya, termasuk risiko dan komplikasi yang mungkin timbul.
                Saya juga menyadarai bahwa ilmu kedokteran bukanlah ilmu pasti,maka keberhasilan tindakan
                kedokteran (anestesi) bukanlah keniscayaan melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa
              </h2>
            </div>
          </div>
        </VCard>
      </div>
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
import * as EMR from '../page-emr-plugins/persetujuan-tindakan-anestesi-dan-sedasi-sedang-dan-dalam'


let antisipasiKondisi = ref(EMR.antisipasiKondisi());
let DaftarInformasi = ref(EMR.daftarInformasi());

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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Hubungan: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].tandaTanganPegawai) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPegawai)
        }
        if (response[0].tandaTanganPasien) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPasien)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object.tandaTanganPegawai = H.tandaTangan().get("signature_1")
  object.tandaTanganPasien = H.tandaTangan().get("signature_2")
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
  console.log(object);

  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }


const fetchHubungan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=${filter.query}&limit=10`)
  d_Hubungan.value = response
}


const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}

const JenisKelamin: any = ref([
  { label: 'Laki - Laki', value: 'Laki - Laki' },
  { label: 'Perempuan', value: 'Perempuan' }
])
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

table.triase {
  border-collapse: collapse;
  width: 100%;
}

table.triase,
.triase th,
.triase td {
  border: 0.5px solid grey;
}


.triase th,
.triase td {
  padding: 8px;
  vertical-align: middle !important;
}
</style>
