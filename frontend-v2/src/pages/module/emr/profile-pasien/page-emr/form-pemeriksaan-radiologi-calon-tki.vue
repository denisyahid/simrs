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
        <Fieldset :toggleable="true" legend="FORM PEMERIKSAAN RADIOLOGI CALON TKI">
          <!-- ---------------------------------- -->
          <div class="columns is-multiline pl-4 pr-4">
            <div class="column is-12">
              <div class="columns is-vcentered">
                <!-- Kolom untuk input Umur -->
                <div class="column is-4">
                  <span style="margin-bottom: 10px;">Nama :</span>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.nama1" placeholder="Nama Lengkap..." />
                    </VControl>
                  </VField>
                </div>

                <!-- Kolom untuk input Kiri -->
                <div class="column is-4">
                  <span>Umur :</span>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.Umur1" placeholder="Umur..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <!-- ---------------------------------------- -->
            <div class="column is-12">
              <div class="columns is-vcentered">
                <!-- Kolom untuk input Kiri -->
                <div class="column is-4">
                  <span>Tanggal periksa :</span>
                  <VDatePicker v-model="input.TanggalPeriksa" mode="dateTime" class="mt-4" style="width: 100%"
                    trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>

                <!-- Kolom untuk input jk -->
                <div class="column is-12">
                  <span>Jenis kelamin :</span>
                  <div style="display: flex; align-items: center; margin-top: 10px;">
                    <div v-for="(data) in jenisKelamin" :key="data.model" style="margin-right: 20px;">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                            class="p-0" color="primary" square circle />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- ----------------------------------------- -->

            <div class="column is-12">
              <span><b>a. JENIS PEMERIKSAAN</b></span>
              <div class="column is-12">
                <span>1.</span>
                <div v-for="(data) in JenisPemeriksaan" :key="data.model"
                  style="margin-right: 20px; margin-bottom: 10px;">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" v-model="input.Lainlain2" />
                    </VControl>
                  </VField>
                </div>
                <!-- ----------------------------------------- -->

                <div class="column is-6">
                  <span>2. Lain-lain :</span>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" v-model="input.jenis_kateter" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>



            <!-- ----------------------------------------- -->
            <div class="column is-8">
              <VField>
                <span>b. HASILPEMBACAAN/EKSPERTISE :</span>
                <VControl>
                  <VTextarea v-model="input.hasilpembacaan" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-8">
              <VField>
                <span>c. KESIMPULAN</span>
                <VControl>
                  <VTextarea v-model="input.kesimpulan" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>

        </Fieldset>
      </div>

      <div class="column is-5" style="float: right;">
        <VCard>
          <div class="column is-12 p-2">
            <h1 style="font-weight: bold;" class="mb-2">Tanggal </h1>
            <VField>
              <VDatePicker v-model="input.Tanggal1" mode="dateTime" style="width: 100%" trim-weeks
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
            <div class="column is-12" style="text-align: center;">
              <span>Tanda tangan dokter pemeriksa</span>
              <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
            </div>
            <div class="column is-12">
              <h1 class="p-0" style="font-weight: bold; text-align: center;">Nama dokter pemeriksa </h1>
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.perawat" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama dokter pemeriksa..." class="mt-2"
                    @item-select="setTandaTangan($event)" />
                </VControl>
              </VField>
            </div>
          </div>
        </VCard>
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

let JenisPemeriksaan = [
  {
    label: "Torax",
    model: "Torax1",
  },
  {
    label: "PA",
    model: "PA1",
  },
  {
    label: "AP",
    model: "AP1",
  },
  {
    label: "Lateral torax",
    model: "Lateraltorax",
  },
  {
    label: "Torax oblik",
    model: "Toraxoblik",
  },
  {
    label: "Top lordotik",
    model: "Toplordotik",
  },
  {
    label: "Lain-lain : ",
    model: "Lainlain",
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
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.tandaTanganPasien = H.tandaTangan().get("signature_1")
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
