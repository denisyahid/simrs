<style lang="scss"></style>
<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Pemberian Informasi Rawat Inap</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideST :isHideCetak="true">
            </ButtonEmr>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-12 pb-0">
            <h1>Saya yang bertanda-tangan di bawah ini :</h1>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold">Nama</h1>
            <VControl>
              <VInput v-model="input.namaTandaTangan" class="input" type="text" />
            </VControl>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold">Tanggal Lahir</h1>
            <VDatePicker v-model="input.tanggalTandaTanganSaya" mode="date" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold">Jenis Kelamin</h1>
            <div style="display: flex">
              <VField v-for="items in JenisKelamin" :key="items.value">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.jeniskelaminSayaTandaTangan" class="pt-1 pb-1" :true-value="items.label"
                    :label="items.label" color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold">Alamat</h1>
            <VField>
              <VTextarea rows="2" v-model="input.TAAlamat_BTTD"></VTextarea>
            </VField>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold">No Kartu Identitas</h1>
            <VControl>
              <VInput v-model="input.kartuIdentitasSaya" class="input" type="text" />
            </VControl>
          </div>
          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>
          <div class="column is-12 is-flex pb-0">
            <h1 class="p-2">Selaku Diri Saya</h1>
            <VField class="p-0">
              <VControl>
                <VCheckbox class="p-2" true-value="Istri" v-model="input.hubunganPasien" label="Istri" />
              </VControl>
            </VField>

            <VField class="p-0">
              <VControl>
                <VCheckbox class="p-2" true-value="Suami" v-model="input.hubunganPasien" label="Suami" />
              </VControl>
            </VField>

            <VField class="p-0">
              <VControl>
                <VCheckbox class="p-2" true-value="Anak" v-model="input.hubunganPasien" label="Anak" />
              </VControl>
            </VField>

            <VField class="p-0">
              <VControl>
                <VCheckbox class="p-2" true-value="Ayah" v-model="input.hubunganPasien" label="Ayah" />
              </VControl>
            </VField>

            <VField class="p-0">
              <VControl>
                <VCheckbox class="p-2" true-value="Ibu" v-model="input.hubunganPasien" label="Ibu" />
              </VControl>
            </VField>

            <VField class="p-0">
              <VControl>
                <VCheckbox class="p-2" true-value="Lainnya" v-model="input.hubunganPasien" label="Lainnya" />
              </VControl>
            </VField>

            <VField v-if="input.hubunganPasien == 'Lainnya'" class="p-0">
              <VControl>
                <VInput v-model="input.hubunganPasienLainnya" class="input" type="text" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12 pb-0">
            <h1 style="font-weight: bold;">Saya*, dengan identitas pasien :</h1>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold">No. Rekam Medis:</h1>
            <VControl>
              <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
            </VControl>
          </div>

          <div class="column is-4">
            <h1 style="font-weight: bold">Nama Pasien</h1>
            <VControl>
              <VInput v-model="input.namaPasien" class="input" type="text" disabled />
            </VControl>
          </div>

          <div class="column is-4">
            <h1 style="font-weight: bold">Tanggal Lahir Pasien</h1>
            <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" disabled />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>

          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold">Jenis Kelamin</h1>
            <div style="display: flex">
              <VField v-for="items in JenisKelamin" :key="items.value">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                    :label="items.label" color="primary" disabled circle />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-8 pt-0">
            <h1 style="font-weight: bold">Diagnosa Pasien</h1>
            <VControl>
              <VTextarea v-model="input.diagnosaPasien" class="input" type="text" rows="2" />
            </VControl>
          </div>
          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>
          <h1 class="p-2">Dengan ini saya menyatakan bahwa saya <span style="font-weight: bold;">Menyetujui</span>
            persyaratan dan
            peraturan Rawat Inap setelah mendapatkan dan memahami penjelasan tentang :</h1>

          <div class="column is-12 is-flex pt-0" v-for="(item, index) in jenisPersetujuan" :key="index">
            <VControl raw subcontrol>
              <VCheckbox v-model="input[item.model]" class="pt-1 pb-1" :true-value="item.model" :label="item.label"
                  color="primary" circle />
              </VControl>
          </div>
          <h1 class="p-2">
            Demikianlah surat persetujuan ini saya buat dalam keadaan sehat dan penuh kesadaran untuk
            dapat
            dipergunakan sebagaimana mestinya
          </h1>
          <div class="column is-flex is-12 pt-0" style="justify-content: space-between;">
            <div class="column is-4" style="text-align:center">
              <h1 style="font-weight: bold">Petugas Pemberi Informasi</h1>
              <TandaTangan elemenID="petugasPemberiInformasi" :width="'150'" :height="'150'" class="dek" />
              <VControl class="pt-3">
                <AutoComplete v-model="input.petugasRegistrasi" :suggestions="d_Petugas"
                  @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
              </VControl>
            </div>

            <div class="column is-4" style="text-align:center">
              <h1 style="font-weight: bold">Yang Membuat Persetujuan</h1>
              <TandaTangan elemenID="penerimaInformasi" :width="'150'" :height="'150'" class="dek" />
              <VControl class="pt-3">
                <VInput type="text" class="input" placeholder="Pasien / Keluarga" v-model="input.namaMembuatPersetujuan" />
              </VControl>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR3 from '../page-emr-plugins/pemberian-informasi-rawat-inap'

useHead({ title: 'Pemberian Informasi Rawat Inap - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let JenisKelamin = ref(EMR3.JenisKelamin())
let jenisPersetujuan: any = ref(EMR3.jenisPersetujuan())
const user = useUserSession().getUser().pegawai;
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

const route = useRoute()
const pasien: any = ref({})
const d_pegawai: any = ref([])
const d_Petugas = ref([]);
const d_Ruangan: any = ref([])
const d_Perawat = ref([]);
const loadData: any = ref(true)
const dataTTD: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date,
    jam: new Date
  },
  airway: [],
  disability: []

})

const COLLECTION: any = ref('PemberianInformasiRawatInap') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  kebjamKedatangan: new Date(),
  kebjamAsesmenAwal: new Date(),
  kebtanggalKedatangan: new Date(),
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const loadRiwayat = async () => {
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      H.tandaTangan().set('penerimaInformasi', dataTTD.value.penerimaInformasi)
      H.tandaTangan().set('petugasPemberiInformasi', dataTTD.value.petugasPemberiInformasi)
    } else {
      setAutoFill2();
    }
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['petugasPemberiInformasi'] = H.tandaTangan().get('petugasPemberiInformasi')
  object['penerimaInformasi'] = H.tandaTangan().get('penerimaInformasi')
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
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
    d_pegawai.value = response
  })
}
const fetchPetugas = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
    d_Petugas.value = response
  })
}
const fetchRuangan = async (filter: any) => {
  await useApi().get(`emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`).then((response) => {
    d_Ruangan.value = response
  })
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
const setAutoFill2 = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.CBPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.alamat = props.pasien.alamatlengkap
  input.value.disetujui = props.pasien.namapasien
  input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.kartuIdentitas = props.pasien.noidentitas
  input.value.petugasRegistrasi = { label: user.namaLengkap, value: user.id }
}
</script>