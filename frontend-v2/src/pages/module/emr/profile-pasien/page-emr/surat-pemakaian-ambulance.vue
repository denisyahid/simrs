<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Surat Pemakaian Ambulance</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div>
          <div class="columns is-multiline pr-3 pl-3">
            <div class="column is-4" style="margin-top: 7px">
              <span>Tanggal :</span>
              <VDatePicker v-model="input.tanggal1" mode="dateTime" style="width: 100%" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
          </div>
          <!-- ---------------------------------- -->
          <div class="column is-12 pt-0">
            <span>Mobil ambulance :</span>
          </div>
          <div class="columns is-multiline pl-4 pr-4">
            <div class="column is-12">
              <div class="columns is-vcentered">
                <!-- Kolom untuk input Umur -->
                <div class="column is-4">
                  <VField label="Ambulance RSUD Bali Mandara : DK :">
                    <VControl>
                      <VInput type="text" v-model="input.ambulanRSUDB"
                        placeholder="Ambulance RSUD Bali Mandara : DK..." />
                    </VControl>
                  </VField>
                </div>

                <!-- Kolom untuk input Kiri -->
                <div class="column is-4">
                  <VField label="Faskes tujuan / pengirim :">
                    <VControl>
                      <VInput type="text" v-model="input.Tujuanpengirim" placeholder="Faskes tujuan/pengirim..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <!-- ---------------------------------------- -->
            <div class="column is-12">
              <span>Kilometer :</span>
            </div>
            <div class="column is-12">
              <div class="columns is-vcentered">
                <!-- Kolom untuk input Umur -->
                <div class="column is-4">
                  <VField label="Berangkat :">
                    <VControl>
                      <VInput type="text" v-model="input.Berangkat" placeholder="Berangkat..." />
                    </VControl>
                  </VField>
                </div>

                <!-- Kolom untuk input Kiri -->
                <div class="column is-4">
                  <VField label="Tiba :">
                    <VControl>
                      <VInput type="text" v-model="input.Tiba" placeholder="Tiba..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12">
              <div class="columns is-vcentered">
                <!-- Kolom untuk input Umur -->
                <div class="column is-8">
                  <VField label="Selisih Kilometer :">
                    <VControl>
                      <VInput type="text" v-model="input.Selisihkilometer" placeholder="Selisih Kilometer..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>

            <!-- ----------------------------------------- -->
            <div class="column is-8">
              <VField label="Diagnosa :">
                <VControl>
                  <VTextarea v-model="input.Diagnosa" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-8">
              <VField label="Kondisi saat dirujuk :">
                <VControl>
                  <VTextarea v-model="input.Kondisisaatdirujuk" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-8">
              <VField label="Alasan dirujuk :">
                <VControl>
                  <VTextarea v-model="input.Alasandirujuk" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="columns is-multiline p-5">
          <div class="column is-12">
            <VCard>
              <div clas="mt-5">
                <div class="columns is-multiline is-variable is-1">
                  <div class="column is-3">
                    <h1 style="font-weight: bold;"> Diserahkan, Pukul :</h1>
                    <VField class="mt-1">
                      <VDatePicker v-model="input.tglDiserahkan" mode="dateTime" style="width: 100%" trim-weeks>
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
                  <div class="column is-3">
                    <h1 style="font-weight: bold;"> Diterima, Pukul :</h1>
                    <VField class="mt-1">
                      <VDatePicker v-model="input.tglDiterima" mode="dateTime" style="width: 100%" trim-weeks>
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

                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-3" style="text-align: center;">
                      <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                    </div>
                    <div class="column is-3" style="text-align: center;">
                      <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
                    </div>
                    <div class="column is-3" style="text-align: center;">
                      <!-- <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan> -->
                    </div>
                    <div class="column is-3" style="text-align: center;">
                      <!-- <TandaTangan :elemenID="'signature_4'" :width="'180'" :height="'180'"></TandaTangan> -->
                    </div>
                    <div class="column is-3">
                      <h1 class="p-0" style="font-weight: bold; text-align: center;">Petugas pengirim</h1>
                      <VField>
                        <VControl class="prime-auto">
                          <AutoComplete v-model="input.namaPetugasPengirim" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Nama petugas..." class="mt-2" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <h1 class="p-0" style="font-weight: bold; text-align: center;">Petugas penerima</h1>
                      <VControl>
                          <VInput type="text" class="input mt-2" v-model="input.namaPetugasPenerima" placeholder="Nama petugas..."/>
                      </VControl>
                      <!-- <VField>
                        <VControl class="prime-auto">
                          <AutoComplete v-model="input.namaPetugasPenerima" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Nama petugas..." class="mt-2" />
                        </VControl>
                      </VField> -->
                    </div>
                    <div class="column is-3">
                      <h1 class="p-0" style="font-weight: bold; text-align: center;">Keluarga pasien</h1>
                      <TandaTangan :elemenID="'ttdPasien'" :width="'180'" :height="'180'"></TandaTangan>
                      <VField style="margin-top: 7px;">
                        <VControl>
                          <VInput type="text" v-model="input.KeluargaPasien" placeholder="Nama Keluarga pasien..." />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <h1 class="p-0" style="font-weight: bold; text-align: center;">Sopir ambulance</h1>
                      <VControl class="prime-auto">
                          <AutoComplete v-model="input.SopirAmbulance" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder=" Nama Sopir Ambulance..." class="mt-2" />
                      </VControl>
                      <!-- <VField style="margin-top: 7px;">
                        <VControl>
                          <VInput type="text" v-model="input.SopirAmbulance" placeholder=" Nama Sopir Ambulance..." />
                        </VControl>
                      </VField> -->
                    </div>
                  </div>
                </div>
              </div>
            </VCard>
          </div>
        </div>
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
import { useUserSession } from '/@src/stores/userSession'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()

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
    FORM_NAME: 'Surat Pemakaian Ambulance',
    FORM_URL: 'surat-pemakaian-ambulance',
    COLLECTION: 'SuratPemakaianAmbulance',
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
const COLLECTION: any = ref('SuratPemakaianAmbulance') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({ title: 'Surat Pemakaian Ambulance - ' + import.meta.env.VITE_PROJECT })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const dataTTD: any = ref({})
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0].ttdPasien
      // if (response[0].ttdPasien) {
      //   H.tandaTangan().set("ttdPasien", response[0].ttdPasien)
      // }
      // if (response[0].tandaTanganPetugas1) {
      //   H.tandaTangan().set("signature_2", response[0].tandaTanganPetugas1)
      // }
      // if (response[0].tandaTanganPetugas2) {
      //   H.tandaTangan().set("signature_3", response[0].tandaTanganPetugas2)
      // }
      // if (response[0].tandaTanganPetugas3) {
      //   H.tandaTangan().set("signature_4", response[0].tandaTanganPetugas3)
      // }
    } else {
      input.value.tanggal1 = new Date()
    }
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.ttdPasien = H.tandaTangan().get("ttdPasien")
  object.tandaTanganPetugas1 = H.tandaTangan().get("signature_2")
  object.tandaTanganPetugas2 = H.tandaTangan().get("signature_3")
  object.tandaTanganPetugas3 = H.tandaTangan().get("signature_4")
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': route.name,
    'name_form': 'Surat Pemakaian Ambulance',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    NOREC_EMRPASIEN.value = response.norec_emr
    input.value.id = response.id;
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

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
setView()
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
