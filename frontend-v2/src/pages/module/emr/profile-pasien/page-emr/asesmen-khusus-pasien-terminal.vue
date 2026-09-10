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

  <div class="column is-12" v-if="loadData">
    <VPlaceload height="37rem" width="100%" class="mx-2" />
  </div>
  <div v-else>
    <div class="column is-12 p-2">
      <div class="column is-12">
        <VCard class="border-card pink">
          <div class="columns is-multiline">
            <div class="column is-3">
              <h1>Kontrol Tanggal</h1>
              <VDatePicker class="pt-3 pb-0 pl-0" v-model="input.tglKontrol" color="green" trim-weeks mode="dateTime"
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
            <div class="column is-4">
              <h1>Informasi diperoleh dari</h1>
              <VField class="mt-2">
                <VControl>
                  <VInput type="text" class="input" v-model="input.asalInformasi" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1>Penanggung jawab</h1>
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.penanggungJawab" :suggestions="d_Hubungan"
                    @complete="fetchPenangungJawab($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Penangung Jawab..."
                    class="mt-2" />
                </VControl>
              </VField>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
        <VCard class="border-card pink">
          <div class="column is-12" v-for="(datas) in dataPengkajian">
            <h1>{{ datas.title }}</h1>
            <div class="column is-12" v-for="(data) in datas.detail">
              <span style="font-weight: 500;margin-bottom: 5px;">{{ data.subTitle }}</span>
              <div class="columns is-multiline">
                <div class="column" v-for="(pilihan) in data.value"
                  :class="pilihan.type == 'checkBox' ? 'is-2 mt-3 ml-4 pr-0' : 'is-5 pb-0 pl-0 pr-0'">
                  <VField>
                    <VControl raw subcontrol v-if="pilihan.type == 'checkBox'">
                      <VCheckbox v-model="input[data.model]" class="p-0" :true-value="pilihan.label"
                        :label="pilihan.label" color="primary" circle />
                    </VControl>
                    <VControl v-else>
                      <small style="color: #dc3545!important;" :class="!pilihan.penjelasan ? 'none' : ''">* {{
                        pilihan.penjelasan }}</small>
                      <VInput type="text" class="input" v-model="input[pilihan.model]" :placeholder="pilihan.label" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
        <Fieldset :toggleable="true" legend="DAFTAR MASALAH KEPERAWATAN">
          <div class="column is-12">
            <h1 v-for="(data) in dataMasalahKeper">{{ data }}</h1>
          </div>
        </Fieldset>
      </div>
    </div>

    <div class="column is-3">
      <h1>Tanggal dan Jam</h1>
      <VDatePicker class="pt-3 pb-0 pl-0" v-model="input.tanggalMedik" color="green" trim-weeks mode="dateTime"
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

    <div class="column is-12">
      <div class="columns is-multiline">

        <div class="column is-4">
          <div class="column is-6">
            <TandaTangan :elemenID="'signatureDokter'" :width="'153'" :height="'158'" class="canvaCust"></TandaTangan>
          </div>
          <h1 style="font-weight: bold; margin-bottom: 1rem;">
            Dokter
          </h1>

          <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
              <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="ketik untuk mencari..."
                @item-select="setTandaTangan($event, 'signatureDokter')" />
            </VControl>
          </VField>
        </div>

        <div class="column is-4" style="margin-left: auto;">
          <div class="column is-6">
            <TandaTangan :elemenID="'signaturePerawat'" :width="'153'" :height="'158'"></TandaTangan>
          </div>
          <h1 style="font-weight: bold; margin-bottom: 1rem;">
            Perawat
          </h1>
          <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
              <AutoComplete v-model="input.perawat" :suggestions="d_perawat" @complete="fetchPerawat($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="ketik untuk mencari..."
                @item-select="setTandaTangan($event, 'signaturePerawat')" />
            </VControl>
          </VField>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-khusus-pasien-terminal'

const d_Hubungan: any = ref([])


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let dataPengkajian = ref(EMR.dataTerminal())
let dataMasalahKeper = ref(EMR.masalahKeperawatan())
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

const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const d_perawat: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('AsesmenKhususPasienTerminal') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tglKontrol: new Date()
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length > 0) {
    input.value = response[0] //set ke inputan
    if (response[0].tandaTanganPerawat) {
      H.tandaTangan().set("signaturePerawat", response[0].tandaTanganPerawat)
    }
    if (response[0].tandaTanganDokter) {
      H.tandaTangan().set("signatureDokter", response[0].tandaTanganDokter)
    }
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    } else { }
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.tandaTanganPerawat = H.tandaTangan().get("signaturePerawat")
  object.tandaTanganDokter = H.tandaTangan().get("signatureDokter")
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
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}


const fetchPerawat = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_perawat.value = response
  })
}

const fetchPenangungJawab = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Hubungan.value = response
  })
}

const fetchDokter = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const setTandaTangan = async (e: any, idTTD: any) => {
  let response = await useApi().get(`/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set(idTTD, response.ttd)
  } else {
    H.tandaTangan().set(idTTD, '')
  }
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
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

setView()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

h1 {
  font-weight: bold;
}

.none {
  display: none;
}

// .canvaCust {
//   position: relative;
//   left: -10px;
//   top: -5px;
// }
</style>
