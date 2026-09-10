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

  <div class="column is-12">
    <VCard>
      <div class="column">
        <h1 style="font-weight: bold;">Yang bertanda tangan di bawah ini</h1>
        <div class="column is-4">
          <span class="label-ppap">Nama</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.penangungJawab" />
            </VControl>
          </VField>
        </div>

        <div class="columns is-multiline pl-3 pr-3 pt-3">
          <div class="column is-4">
            <span class="label-ppap">Jenis Kelamin</span>
            <div class="columns is-multiline pt-4">
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKelaminPNJ" true-value="Laki-laki" class="p-0" label="Laki-laki"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKelaminPNJ" true-value="Perempuan" class="p-0" label="Perempuan"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-2">
            <span class="label-ppap">Tanggal Lahir</span>
            <VDatePicker class="pt-3" v-model="input.tglLahirPNJ" color="green" trim-weeks mode="date"
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
          </div>
        </div>

        <div class="column is-7 pt-0">
          <span class="label-ppap">Alamat</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="input.alamatPNJ" rows="2" placeholder="Alamat ...">
              </VTextarea>
            </VControl>
          </VField>
        </div>

        <div class="column is-3">
          <span class="label-ppap">Dalam hal ini bertindak sebagai</span>
          <VField>
            <VControl class="prime-auto">
              <AutoComplete v-model="item.wali" :suggestions="d_Wali" @complete="fetchWali($event)" :optionLabel="'label'"
                :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                placeholder="Cari ..." class="mt-2" />
            </VControl>
          </VField>
        </div>

        <div class="column is-4">
          <span class="label-ppap">Nama</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.namaPasien" />
            </VControl>
          </VField>
        </div>

        <div class="columns is-multiline pl-3 pr-3 pt-3">
          <div class="column is-4 pb-0">
            <span class="label-ppap">Jenis Kelamin</span>
            <div class="columns is-multiline pt-4">
              <div class="column pb-0">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKelaminPas" true-value="Laki-laki" class="p-0" label="Laki-laki"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column pb-0">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKelaminPas" true-value="Perempuan" class="p-0" label="Perempuan"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-4 pb-0">
            <span class="label-ppap">No Rekam Medis</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.norm" />
              </VControl>
            </VField>
          </div>

          <div class="column is-3 pb-0">
            <span class="label-ppap">Tanggal Lahir</span>
            <VDatePicker class="pt-3" v-model="input.tglLahirPasien" color="green" trim-weeks mode="date"
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
          </div>
        </div>

        <div class="column pt-0">
          <span class="label-ppap">Alamat</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="input.alamatPasien" rows="2" placeholder="Alamat ...">
              </VTextarea>
            </VControl>
          </VField>
        </div>

        <div class="column pt-0">
          <p>Dengan ini menyatakan PERSETUJUAN untuk ikut dalam pelayanan ambulan gawat darurat.</p>
          <p>Saya memahami sebagaimana telah dijelaskan seperti diatas kepada saya, termasuk resiko yang mungkin timbul</p>
          <p>Saya bertanggung jawab secara penuh atas segala akibat yang mungkin timbul, disaat saya ikut dalam pelayanan ambulan gawat darurat</p>
        </div>

        <div class="columns is-multiline pt-5" style="justify-content: space-around;">
          <div class="column is-4" style="text-align: center;">
            <TandaTangan :elemenID="'signaturePegawai'" :width="'180'" :height="'180'" class="dek" />
            <div class="column pl-0 pr-0 pt-5">
              <span class="label-ppap">Paramedis yang merawat</span>
              <VField class="pt-3">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.pegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" @item-select="setTandaTanganPerawat($event)"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai..." />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4" style="text-align: center;">
            <TandaTangan :elemenID="'signPembuatPernyataan'" :width="'180'" :height="'180'" class="dek" />
            <div class="column pl-0 pr-0 pt-5">
              <span class="label-ppap">Yang Membuat Pernyataan</span>
              <VField class="pt-3">
                <VControl>
                  <VInput type="text" class="input" v-model="input.pembuatPernyataan" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
</template>




<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
// import Fieldset from 'primevue/fieldset';
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
const d_Wali: any = ref([])
const d_Pegawai: any = ref([])
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
        if (response[0].ttdPegawai) {
          H.tandaTangan().set("signaturePegawai", response[0].ttdPegawai)
        }
        if (response[0].ttdPembuatPernyataan) {
          H.tandaTangan().set("signPembuatPernyataan", response[0].ttdPembuatPernyataan)
        }
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
  object.ttdPegawai = H.tandaTangan().get("signaturePegawai")
  object.ttdPembuatPernyataan = H.tandaTangan().get("signPembuatPernyataan")
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

const fetchWali = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Wali.value = response
  })
}


const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
    input.value.namaPasien = props.pasien.namapasien
    input.value.jnsKelaminPas = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.tglLahirPasien = props.pasien.tgllahir
    input.value.alamatPasien = props.pasien.alamatlengkap
}

const setTandaTanganPerawat = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signaturePegawai", element.ttd)
      }else{
        H.tandaTangan().set("signaturePegawai", '')
      }
    })
}


setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">

.label-ppap{
  font-weight: 500;
}

</style>
