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


    <vCard>
      <h3 class="title is-5 mb-2">
        Keluar Ruang Pemulihan
      </h3>
      <div class="columns is-multiline">
        <div class="column is-12">

          <table class="table is-striped is-fullwidth">
            <thead>
              <tr>
                <th scope="col">ALDRETE SKOR</th>
                <th scope="col">Aktivitas</th>
                <th scope="col">Sirkulasi</th>
                <th scope="col">Pernafasan</th>
                <th scope="col">Kesadaran</th>
                <th scope="col">Warna Kulit</th>
                <th scope="col">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Saat Masuk Ruang Pemulihan</td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.masukAktivitas" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.sirkulasiMasuk" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.pernafasanMasuk" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.kesadaranMasuk" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.warnaKulitMasuk" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.totalMasuk" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td>Saat Keluar Ruang Pemulihan</td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.masukAktiKeluar" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.sirkulasiKeluar" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.pernafasanKeluar" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.kesadaranKeluar" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.warnaKulitKeluar" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VInput v-model="input.totalKeluar" type="text" placeholder="" />
                    </VControl>
                  </VField>
                </td>
              </tr>
            </tbody>
          </table>
          <hr>
          <h3>
            Bila Skor VAS &lt; 4 dan Aldrete Skor > 8 </h3>
        </div>
      </div>
      <p>
      <h3 class="title is-5">
        Keluar Ruang Pulih
      </h3>
      </p>
      <hr>
      <div class="columns is-multiline">
        <div class="column is-4">
          <span class="label-apas">Pasien sudah bisa pindah ke : </span>
          <VField class="pt-3">
            <VControl>
              <VInput v-model="input.pasienPindahKe" rows="2">
              </VInput>
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-apas">Keluar ruang pulih pukul : </span>
          <VField class="pt-3">
            <VControl>
              <VInput v-model="input.keluarRuangPulih" type="time" />
            </VControl>
          </VField>
        </div>
      </div>
      <p>
      <h3 class="title is-5">
        Tanda - Tanda Vital
      </h3>
      </p>
      <hr>
      <div class="columns is-multiline">
        <div class="column is-4">
          <span class="label-apas">TD :</span>
          <VField class="pt-3">
            <VControl>
              <VInput v-model="input.td" rows="2">
              </VInput>
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <span class="label-apas">N : </span>
          <VField class="pt-3">
            <VControl>
              <VInput v-model="input.n" rows="2">
              </VInput>
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <span class="label-apas">RR : </span>
          <VField class="pt-3">
            <VControl>
              <VInput v-model="input.rr" rows="2">
              </VInput>
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <span class="label-apas">SpO2 : </span>
          <VField class="pt-3">
            <VControl>
              <VInput v-model="input.spO2" rows="2">
              </VInput>
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-6">
          <span class="label-apas">Catatan Khusus</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="input.catatanKhusus" rows="3"></VTextarea>
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-apas">Petugas</span>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.pegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Cari Petugas..." />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <span class="label-apas">Nama & TTD DPJP</span>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Cari Dokter..." />
            </VControl>
          </VField>
        </div>
      </div>

    </vCard>


  </div>


  <div class="column">
    <!-- form emr -->
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
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
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


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

  await useApi().get(
    "emr/auto-fill?nocmfk=" + ID_PASIEN +
    "&collection=VitalSign" +
    "&field=kesadaran,spO2,refleksMenelan,tekananDarah,respirasi,suhu,nadi,lainLain"
  ).then((response) => {

    input.value.td = response.tekananDarah
    input.value.n = response.nadi
    input.value.rr = response.pernafasan
    input.value.spO2 = response.spO2
  })
}
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
</style>
