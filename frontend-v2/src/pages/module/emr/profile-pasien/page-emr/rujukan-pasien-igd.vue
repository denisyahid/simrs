<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="mt-5">
        <Fieldset :toggleable="true">
          <span>Kepada Yth.</span>
          <div class="columns is-vcentered"
            style="display: flex; align-items: center; justify-content: flex-start; gap: 1rem; flex-wrap: wrap;">
            <div class="column is-narrow">
              <span>Teman Sejawat/Dr/Bag :</span>
            </div>
            <div class="column is-4">
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" v-model="input.TemanSejawat" />
                </VControl>
              </VField>
            </div>
            <div class="column is-narrow">
              <span>Di :</span>
            </div>
            <div class="column is-4">
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" v-model="input.Di1" />
                </VControl>
              </VField>
            </div>
          </div>

          <!-- ---------------------------------- -->
          <div class="column  is-12">
            <span>Dengan hormat,</span>
          </div>
          <div class="column is-12">
            <span>Mohon pemeriksaan/perawatan/pengobatan lebih lanjut pasien:</span>
          </div>
          <div class="columns is-multiline pl-4 pr-4">
            <div class="column is-12">
              <div class="columns is-vcentered p-3">
                <!-- Input text di sebelah kiri -->
                <div class="column is-4">
                  <VField label="Nama :" style="margin-right: 8px;"></VField>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="Nama..." v-model="input.nama" />
                    </VControl>
                  </VField>
                </div>

                <!-- Checkboxes di sebelah kanan -->
                <div class="column is-6">
                  <VField label="Jenis Kelamin :"></VField>
                  <div class="field is-grouped is-grouped-multiline">
                    <div class="control" v-for="(data, index) in jenisKelamin" :key="index" style="margin-right: 20px;">
                      <label class="checkbox">
                        <VCheckbox v-model="input[data.model]" :true-value="data.label" color="primary" circle />
                        <span style="margin-left: 8px;">{{ data.label }}</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="columns is-multiline pl-4 pr-4">
            <div class="column is-12" style="margin-top: -30px;">
              <div class="columns is-vcentered">
                <!-- Kolom untuk input Umur -->
                <div class="column is-4">
                  <VField label="Umur :">
                    <VControl>
                      <VInput type="text" v-model="input.umur" placeholder="Umur..." />
                    </VControl>
                  </VField>
                </div>

                <!-- Kolom untuk input Kiri -->
                <div class="column is-4">
                  <VField label="Diagnosis kerja :">
                    <VControl>
                      <VInput type="text" v-model="input.Diagnosiskerja" placeholder="Diagnosis kerja..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-8">
              <VField label="Alamat :">
                <VControl>
                  <VTextarea v-model="input.catatan" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-8">
              <VField label="Riwayat penyakit :">
                <VControl>
                  <VTextarea v-model="input.Riwayatpenyakit" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="columns is-multiline pl-4 pr-4">
            <div class="column is-12">
              <div class="columns is-vcentered">
                <!-- Kolom untuk input Umur -->
                <div class="column is-4">
                  <VField label="Pemeriksaan fisik :">
                    <VControl>
                      <VInput type="text" v-model="input.Pemeriksaanfisik" placeholder="Pemeriksaan fisik..." />
                    </VControl>
                  </VField>
                </div>

                <!-- Kolom untuk input Kiri -->
                <div class="column is-4">
                  <VField label="Penunjang :">
                    <VControl>
                      <VInput type="text" v-model="input.Penunjang" placeholder="Penunjang..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-8">
              <VField label="Terapi/Prosedur/Intervensi yang telah diberikan :">
                <VControl>
                  <VTextarea v-model="input.Terapiprosedur" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="columns is-multiline pl-4 pr-4">
            <div class="column is-12">
              <div class="columns is-vcentered">
                <!-- Kolom untuk input Umur -->
                <div class="column is-4">
                  <VField label="Alasan rujuk :">
                    <VControl>
                      <VInput type="text" v-model="input.Alasanrujuk" placeholder="Alasan rujuk..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>

          <div class="columns is-multiline pl-4 pr-4">
            <div class="column is-12">
              <div class="columns is-vcentered">
                <!-- Kolom untuk input Umur -->
                <div class="column is-5">
                  <VField label="Kebutuhan Pelayanan Lanjutan di RS Rujukan :">
                    <VControl>
                      <VInput type="text" v-model="input.Kebutuhanpelayan"
                        placeholder="Kebutuhan Pelayanan Lanjutan di RS Rujukan..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>

          <div class="columns is-multiline pl-4 pr-4">
            <div class="column is-12">
              <div class="columns is-vcentered">
                <!-- Kolom untuk input Umur -->
                <div class="column is-6">
                  <VField label="Nama petugas yang menyetujui rujukan di RS yang dituju :">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.namaPetugas" :suggestions="d_Petugas"
                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Nama petugas yang menyetujui rujukan di RS yang dituju..." class="mt-2" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="No. Hp :">
                    <VControl>
                      <VInput type="number" v-model="input.nomorHP" placeholder="Nomor Hp..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>

        </Fieldset>
      </div>

      <div class="columns is-multiline p-5">
        <div class="column is-12">
          <VCard>
            <div clas="mt-5">
              <div class="columns is-multiline is-variable is-1">
                <div class="column is-4">
                  <h1 style="font-weight: bold;"> Garut, Tanggal </h1>
                  <VField class="mt-3">
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
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;"> Dokter yang merawat </h1>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.dpjpIGD" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter yang merawat..."
                        class="mt-2" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4" style="text-align: center;">
                    <h1 class="p-0" style="font-weight: bold; text-align: center;">Disetujui</h1>
                    <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-4" style="text-align: center;">
                    <h1 class="p-0" style="font-weight: bold; text-align: center;">Diserahkan</h1>
                    <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-4" style="text-align: center;">
                    <h1 class="p-0" style="font-weight: bold; text-align: center;">Diterima</h1>
                    <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-4">
                    <h1 class="p-0" style="font-weight: bold; text-align: center;">Pasien/Penanggungjawab</h1>
                    <VField style="margin-top: 7px;">
                      <VControl>
                        <VInput type="text" v-model="input.PasienPenanggungjawab"
                          placeholder="Pasien/Penanggungjawab..." />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1 class="p-0" style="font-weight: bold; text-align: center;">Petugas</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.namaPetugas2" :suggestions="d_Petugas"
                          @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Nama petugas..." class="mt-2" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1 class="p-0" style="font-weight: bold; text-align: center;">Petugas</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.namaPetugas3" :suggestions="d_Petugas"
                          @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Nama petugas..." class="mt-2" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </VCard>
        </div>
      </div>


    </VCard>
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
import { useUserSession } from '/@src/stores/userSession'



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

let jenisKelamin = [
  {
    label: "Laki-laki",
    model: "jeniskelamin",
  },
  {
    label: "Perempuan",
    model: "jeniskelamin",
  }
]

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Petugas: any = ref([])
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
        if (response[0].tandaTanganPasien) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPasien)
        }
        if (response[0].tandaTanganPetugas1) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPetugas1)
        }
        if (response[0].tandaTanganPetugas2) {
          H.tandaTangan().set("signature_3", response[0].tandaTanganPetugas2)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.tandaTanganPasien = H.tandaTangan().get("signature_1")
  object.tandaTanganPetugas1 = H.tandaTangan().get("signature_2")
  object.tandaTanganPetugas2 = H.tandaTangan().get("signature_3")
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


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const setTandaTangan = async (e: any, i: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_" + i, element.ttd)
    } else {
      H.tandaTangan().set("signature_" + i, '')
    }
  })
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
table.triase {
  border-collapse: collapse;
  width: 100%;
}

table.triase,
th,
.triase td {
  border: 1px solid black;
}

table.triase,
th {
  // text-align: center;

}

.bg-green {
  background-color: var(--primary);
}

.bg-warning {
  background-color: var(--warning);
}

.bg-danger {
  background-color: var(--danger);
}

.triase th,
td {
  padding: 8px;
  vertical-align: top !important;
}
</style>
