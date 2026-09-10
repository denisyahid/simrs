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
    <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
    <VCard v-else>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-3">
            <span class="label-apta">Kelas/Kamar</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.kelas" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <span class="label-apta">Diagnosa Medik</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.diagnosaMedik" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <span class="label-apta">DPJP</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.dpjp" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <h1>ASESMEN DILAKUKAN OLEH DOKTER</h1>
        <div class="column is-3">
          <span class="label-apta">Tanggal Pengkajian</span>
          <VDatePicker v-model="input.tglPengkajian" mode="dateTime" style="width: 100%" trim-weeks
            :max-date="new Date()">
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

      <div class="column">
        <span class="label-apta">1. Gejala-gejala</span>
        <div class="column" v-for="(datas) in gejala">
          <span class="label-apta">{{ datas.title }}</span>
          <div class="columns is-multiline pt-3 pl-3 pr-3 pb-0">
            <div class="column is-3" v-for="(data) in datas.detail">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.code" class="p-0" :label="data.label"
                    color="primary" square />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4 pt-0" v-if="datas.optional">
            <span v-if="datas.optional.label">{{ datas.optional.label }}</span>
            <VField :class="datas.optional.label ? 'pt-3' : ''">
              <VControl>
                <VInput type="text" v-model="input[datas.optional.model]" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <span class="label-apta">2. Faktor-faktor yang meningkatkan dan membangkitkan gejala fisik</span>
        <div class="columns is-multiline pt-3 pl-3 pr-3">
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.faktorPembangitGejalaFisik" true-value="MAF" class="p-0"
                  label="Melakukan Aktifitas Fisik" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.faktorPembangitGejalaFisik" true-value="PP" class="p-0" label="Pindah Posisi"
                  color="primary" square />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column pt-0 is-4">
          <VField>
            <VControl>
              <VInput type="text" v-model="input.ketFaktor" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column is-4">
        <span class="label-apta">3.Diagnosa/asessment</span>
        <VField class="pl-3 pt-3">
          <VControl>
            <VInput type="text" v-model="input.diagnosa" />
          </VControl>
        </VField>
      </div>
      <div class="column is-4">
        <span class="label-apta">4.Perencanaan</span>
        <VField class="pl-3 pt-3">
          <VControl>
            <VInput type="text" v-model="input.Perencanaan" />
          </VControl>
        </VField>
      </div>

      <div class="column">
        <h1>ASESMEN KEPERAWATAN(dilakukan oleh perawat)</h1>
        <div class="column is-2">
          <span class="label-apta">Tanggal Pengkajian</span>
          <VDatePicker v-model="input.tglPengkajian" mode="dateTime" style="width: 100%" trim-weeks
            :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>

        <div class="column">
          <span class="label-apta">1. Orientasi spiritual pasien dan keluarga</span>
          <div class="column">
            <span class="label-apta">Apakah perlu pelayanan spiritual</span>
            <div class="columns is-multiline pt-3">
              <div class="column is-1">
                <span class="label-apta">Ya, Oleh</span>
              </div>
              <div class="column is-4">
                <VField class="column is-9 p-0">
                  <VControl>
                    <VInput type="text" v-model="input.pelayananSpiritual" />
                  </VControl>
                </VField>
              </div>
              <div class="column pt-4">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.setujuPelSpiritual" true-value="Tidak" class="p-0" label="Tidak"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>

        <div class="column">
          <span class="label-apta">2.Urusan dan kebutuhan spiritual pasiendan keluarga seperti putus asa dan penderitaan
            rasa bersalah atau
            pengampunan</span>
          <div class="column">
            <span class="label-apta">Perlu didoakan</span>
            <div class="columns is-multiline pt-3">
              <div class="column is-1">
                <span class="label-apta">Ya, Oleh</span>
              </div>
              <div class="column is-4">
                <VField class="column is-9 p-0">
                  <VControl>
                    <VInput type="text" v-model="input.penDoa" />
                  </VControl>
                </VField>
              </div>
              <div class="column pt-4">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.perluDoa" true-value="Tidak" class="p-0" label="Tidak" color="primary"
                      square />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>

        <div class="column">
          <span class="label-apta">3. Status psikososial pasien dan keluarga</span>
          <div class="column">
            <span class="label-apta">Apakah ada orang yang ingin dihubungi saat ini</span>
            <div class="columns is-multiline pt-3">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.perluDihubungi" true-value="Tidak" class="p-0" label="Tidak" color="primary"
                      square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-1">
                <span class="label-apta">Ya, Siapa</span>
              </div>
              <div class="column is-4 pt-2">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="input.menghubungi" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline pt-3">
              <div class="column is-2">
                <span class="label-apta">Hubungan dengan pasien</span>
              </div>
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="input.hubunganDenganPasien" />
                  </VControl>
                </VField>
              </div>
            </div>

          </div>
        </div>

        <div class="column pt-0 " v-for="(datas) in reaksi">
          <span class="label-apta">{{ datas.title }}</span>
          <div class="columns is-multiline pl-3 pt-3 pr-3 pb-2">
            <div class="column" v-for="(data) in datas.detail" :class="data.code == 'KMPB' ? 'is-5' : 'is-4'">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.code" class="p-0" :label="data.label"
                    color="primary" square />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-5 pt-0" v-if="datas.optional">
            <span v-if="datas.optional.label">{{ datas.optional.label }}</span>
            <VField :class="datas.optional.label ? 'pt-3' : ''">
              <VControl>
                <VInput type="text" v-model="input[datas.optional.model]" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-5 pt-0 ml-3 mr-3">
          <span class="label-apta">Jika ya, Apakah anda yang mampu merawat pasien dirumah ? Ya, Oleh</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.pengurusPasienDirumah" />
            </VControl>
          </VField>
          <VField>
            <VControl raw subcontrol>
              <VCheckbox v-model="input.fasilitasRSdirumah" true-value="fasilitasRS" class="p-0"
                label="Jika tidak apakah perlu difasilitasi oleh RS" color="primary" square />
            </VControl>
          </VField>
        </div>

        <div class="column">
          <span class="label-apta">11. Perencanaan lain</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.perencanaanLain" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column">
        <h1>Yang melakukan Asesmen</h1>
        <div class="column is-3 pl-0">
          <span class="label-apta">Bandung</span>
          <VDatePicker class="pt-3" v-model="input.tglDibuat" color="green" trim-weeks mode="datetime"
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

        <div class="columns is-multiline pt-5" style="justify-content: space-around;">
          <div class="column is-4" style="text-align: center;">
            <TandaTangan :elemenID="'signatureDokter'" :width="'180'" :height="'180'" class="dek" />
            <div class="column pl-0 pr-0 pt-5">
              <span class="label-ppap">Dokter</span>
              <VField class="pt-3">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    @item-select="setTandaTanganDokter($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                    placeholder="Cari Dokter..." />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4" style="text-align: center;">
            <TandaTangan :elemenID="'signaturePerawat'" :width="'180'" :height="'180'" class="dek" />
            <div class="column pl-0 pr-0 pt-5">
              <span class="label-ppap">Perawat</span>
              <VField class="pt-3">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    @item-select="setTandaTanganPerawat($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                    placeholder="Cari Perawat..." />
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
// import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/asesmen-pasien-tahap-akhir-kehidupan'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let gejala = ref(EMR.gejala())
let reaksi = ref(EMR.reaksi())

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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const d_Dokter: any = ref([])
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
const input: any = ref({
  tglPengkajian: new Date,
  tglDibuat: new Date,
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
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (response[0].ttdDokter) {
      H.tandaTangan().set("signatureDokter", response[0].ttdDokter)
    }
    if (response[0].ttdPerawat) {
      H.tandaTangan().set("signaturePerawat", response[0].ttdPerawat)
    }
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.ttdDokter = H.tandaTangan().get("signatureDokter")
  object.ttdPerawat = H.tandaTangan().get("signaturePerawat")
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

const setTandaTanganDokter = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signatureDokter", element.ttd)
    } else {
      H.tandaTangan().set("signatureDokter", '')
    }
  })
}

const setTandaTanganPerawat = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signaturePerawat", element.ttd)
    } else {
      H.tandaTangan().set("signaturePerawat", '')
    }
  })
}


const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}
setView()
setAutoFill()
loadRiwayat()


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
</script>

<style lang="scss">
.label-apta {
  font-weight: 500;
}
</style>
