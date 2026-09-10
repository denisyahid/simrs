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
      <div class="column is-12">
        <h1 class="title-aapm">A. Airway</h1>
        <div class="column" v-for="(data) in airway">
          <span v-if="data.title" class="subTitle-aapm">{{ data.title }}</span>
          <div class="columns is-multiline" :class="data.title != '' ? 'p-3' : ''">
            <div class="column" v-for="(pilihan) in data.value" :class="data.title != '' ? 'is-4' : 'is-2'">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label" class="p-0" :label="pilihan.label"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column is-12" v-for="(datas) in breatCircu">
        <h1 class="title-aapm">{{ datas.title }}</h1>
        <div class="column" v-for="(data) in datas.detail">
          <span class="subTitle-aapm">{{ data.subTitle }}</span>
          <div class="columns is-multiline p-3">
            <div class="column pb-0" v-for="(pilihan) in data.value" :class="data.value.length == 2 ? 'is-4' : ''">
              <VField v-if="pilihan.type == 'checkBox'">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label" class="p-0" :label="pilihan.label"
                    color="primary" circle />
                </VControl>
              </VField>
              <span v-if="pilihan.type == 'textBox'" class="subTitle-aapm">{{ pilihan.label }}</span>
              <VField class="pt-3 column is-9 pb-0" v-if="pilihan.type == 'textBox'">
                <VControl>
                  <VInput type="text" class="input" v-model="input[pilihan.model]" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <h1 class="title-aapm">D. Disability</h1>
        <div class="column" v-for="(data) in disability">
          <span class="subTitle-aapm">{{ data.title }}</span>
          <div class="columns is-multiline p-3">
            <div class="column pb-0" v-for="(pilihan) in data.value" v-if="data.type == 'checkBox'"
              :class="data.title == 'Respon Cahaya' ? 'is-2' : ''">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.code" class="p-0" :label="pilihan.label"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-3" v-for="(pilihan) in data.value" v-else>
              <span class="subTitle-aapm">{{ pilihan.label }}</span>
              <VField class="pt-3">
                <VControl>
                  <VInput type="text" class="input" v-model="input[pilihan.model]" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column is-12" v-for="(datas) in exposure">
        <h1 class="title-aapm">{{ datas.title }}</h1>
        <div class="column" v-for="(data) in datas.detail">
          <span class="subTitle-aapm">{{ data.subTitle }}</span>
          <div class="columns is-multiline" :class="data.type == 'checkBox' ? 'p-3' : ''">
            <div class="column is-3" v-for="(pilihan) in data.value" v-if="data.type == 'textBox'">
              <VField class="pt-3">
                <VControl>
                  <VInput type="text" class="input" v-model="input[pilihan.model]" />
                </VControl>
              </VField>
            </div>
            <div class="column" v-for="(pilihan) in data.value" v-else>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.code" class="p-0" :label="pilihan.label"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column">
        <Fieldset :toggleable="true" legend="WB">
          <div class="columns is-multiline is-12 pl-5 pr-5 pt-5 pb-0">
            <div class="column is-7">
              <h1 style="font-weight: bold;">Skoring nyeri (Wong Baker Faces)</h1>
              <div class="columns pt-4">
                <div class="column" style="text-align: center;" v-for="(image, i) in listImageNyeri.detail">
                  <VAvatar size="medium" :picture="image.img" :class="isAktive == i ? 'active' : ''"
                    @click="test(image, i)" />
                  <p>{{ image.descNilai }}</p>
                  <p>{{ image.nama }}</p>
                </div>
              </div>
            </div>
            <div class="column is-5">
              <h1 style="font-weight: bold;">Score</h1>
              <div class="pt-4">
                <VField v-for="(skor) in listSkoringNyeri.detail">
                  <VControl raw subcontrol class="p-0">
                    <VCheckbox class="pt-0" v-model="input.skor" :true-value="skor.descNilai" :label="skor.nama"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

      <div class="column">
        <Fieldset :toggleable="true" legend="VAS ">
          <div class="column is-5">
            <h1 style="font-weight: bold;">VAS </h1>
            <div class="column is-8">
              <h1 style="font-weight: bold; text-align: center;" class="mb-4">{{
                input.skorVas }}</h1>
              <Slider v-model="input.vas" :min="0" :max="10" />
            </div>
          </div>

          <div class="column is-12 pb-0">
            <span style="font-weight: 500;">Perjalanan Nyeri</span>
            <div class="columns is-multiline p-3">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.perjalananNyeri" true-value="Ya" class="p-0" label="Ya Sebutkan"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="input.kualitasLainnya" placeholder="Kualitas Lainnya" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.perjalananNyeri" true-value="Tidak" class="p-0" label="Tidak"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 pt-0">
            <span style="font-weight: 500;">Tipe</span>
            <div class="columns is-multiline p-3">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.tipe" true-value="Akut" class="p-0" label="Akut" color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.perjalananNyeri" true-value="Kronik" class="p-0" label="Kronik"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

      <div class="column">
        <h1 class="title-aapm">F. Farenheit(Suhu Tubuh)</h1>
        <div class="columns is-multiline p-3">
          <div class="column is-3">
            <span>Suhu Axila</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.suhuAxila" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <span>Suhu Rectal</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.suhuRectal" />
              </VControl>
            </VField>
          </div>
        </div>

        <span style="font-weight: 500;" class="pl-3">Diagnosa Keperawatan</span>
        <div class="columns is-multiline pt-3 pl-5 pr-5">
          <div class="column is-4">
            <span>Hpertermi</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.hpertermi" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <span>Hipotermi</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.Hipotermi" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <h1 class="title-aapm">G. Tahap Berduka</h1>
        <div class="column is-12 pb-0" v-for="(datas) in tahapanBerduka">
          <span style="font-weight: 500;">{{ datas.title }}</span>
          <div class="columns is-multiline p-3">
            <div class="column" v-for="(data) in datas.value" :class="data.type == 'checkBox' ? 'is-1' : 'is-4'">
              <VField v-if="data.type == 'checkBox'">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.label" class="p-0" :label="data.label"
                    color="primary" circle />
                </VControl>
              </VField>
              <VField v-else>
                <VControl>
                  <VInput type="text" v-model="input[data.model]" placeholder="Jelaskan" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column" v-for="(datas) in konsepDiri">
        <h1 class="title-aapm">{{ datas.title }}</h1>
        <div class="column" v-for="(data) in datas.detail">
          <span style="font-weight: 500;">{{ data.subTitle }}</span>
          <div class="columns is-multiline pt-3 pb-0 pl-3 pr-3">
            <div class="column" v-for="(pilihan) in data.value" :class="pilihan.type == 'checkBox' ? 'is-2' : 'is-4'">
              <VField v-if="pilihan.type == 'checkBox'">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label" class="p-0" :label="pilihan.label"
                    color="primary" circle />
                </VControl>
              </VField>
              <VField v-else>
                <VControl>
                  <VInput type="text" v-model="input[pilihan.model]" :placeholder="pilihan.label" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-8 p-0" v-if="data.optional">
            <span style="font-weight: 500;">{{ data.optional.label }}</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="item.tindakan" rows="2">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <h1 class="title-aapm">J. Spiritual</h1>
        <div class="column is-12" v-for="(datas) in spiritual">
          <span style="font-weight: 500;">{{ datas.title }}</span>
          <div class="columns is-multiline pt-3 pl-3 pb-0 pr-0">
            <div class="column is-2" v-for="(data) in datas.value">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.label" class="p-0" :label="data.label"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-5 pt-0 pb-0" v-if="datas.optional">
            <span style="font-weight: 500;">{{ datas.optional.title }}</span>
            <VField class="pt-3 column is-10 p-0">
              <VControl>
                <VInput type="text" v-model="input[datas.optional.model]" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column" v-for="(datas) in again">
        <h1 class="title-aapm">{{ datas.title }}</h1>
        <div class="column" v-for="(data) in datas.detail">
          <span>{{ data.subTitle }}</span>
          <div class="columns is-multiline p-3">
            <div class="column is-2" v-for="(pilihan) in data.value">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label" class="p-0" :label="pilihan.label"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column">
        <h1 class="title-aapm pb-3">Daftar Masalah Keperawatan</h1>
        <div class="column">
          <p class="pb-3">1. Kecemasan atau ketakutan individu/keluarga berhubungan dengan kematian</p>
          <p class="pb-3">2. Berduka berhubungan dengan penyakit terminal dan kematian</p>
          <p class="pb-3">3. Perubahan proses keluarga berhubungan dengan takut akan kematian keluarga</p>
          <p class="pb-3">4. Resiko terhadap distres spiritual</p>
        </div>
      </div>

      <div class="column is-4" style="margin-left:auto">
        <div class="column is-7">
          <h1 class="title-aapm pb-3">Bandung</h1>
          <VDatePicker v-model="input.tglDibuat" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column pt-0">
          <VField>
            <h1 style="font-weight: bold;">Dokter</h1>
          </VField>
          <VField class="is-autocomplete-select" v-slot="{ id }">
            <VControl icon="feather:search">
              <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="ketik nama Dokter" />
            </VControl>
          </VField>
        </div>
        <div class="column pt-0">
          <VField>
            <h1 style="font-weight: bold;">Perawat/Bidan</h1>
          </VField>
          <VField class="is-autocomplete-select" v-slot="{ id }">
            <VControl icon="feather:search">
              <AutoComplete v-model="input.pegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="ketik nama Perawat/Bidan" />
            </VControl>
          </VField>
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
import Slider from 'primevue/slider';
import AutoComplete from 'primevue/autocomplete';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/asesmen-awal-pasien-menjelang-akhir-hayat'
import Fieldset from 'primevue/fieldset';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let airway = ref(EMR.airway())
let breatCircu = ref(EMR.breathingCirculation())
let disability = ref(EMR.disability())
let exposure = ref(EMR.exposure())
let tahapanBerduka = ref(EMR.tahapanBerduka())
let konsepDiri = ref(EMR.konsepDiri())
let spiritual = ref(EMR.spiritual())
let again = ref(EMR.again())

let listImageNyeri = ref(
  {
    "nama": "Hurts", "detail": [
      {
        "nama": "No Hurt", "descNilai": 0,
        "img": "/images/skalanyeri/1.png"
      },
      {
        "nama": "Hurts Little Bit", "descNilai": 2,
        "img": "/images/skalanyeri/2.png",
      },
      {
        "nama": "Hurts Little More", "descNilai": 4,
        "img": "/images/skalanyeri/3.png",
      },
      {
        "nama": "Hurts Even More", "descNilai": 6,
        "img": "/images/skalanyeri/4.png",
      },
      {
        "nama": "Hurts Whole Lot", "descNilai": 8,
        "img": "/images/skalanyeri/5.png",
      },
      {
        "nama": "Hurts whorts", "descNilai": 10,
        "img": "/images/skalanyeri/6.png",
      }]
  }
)
let listSkoringNyeri: any = ref(
  {
    "nama": "Score ", "detail": [
      { "nama": "0 - 1 = Tidak Ada Nyeri", "descNilai": 0 },
      { "nama": "2 - 3 = Sedikit Nyeri", "descNilai": 2 },
      { "nama": "4 - 5 = Cukup Nyeri", "descNilai": 4 },
      { "nama": "6 - 7 = Lumayan Nyeri", "descNilai": 6 },
      { "nama": "8 - 9 = Sangat Nyeri", "descNilai": 8 },
      { "nama": "10 = Amat Sangat Nyeri", "descNilai": 10 },
    ]
  }
)


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
const loadData: any = ref(true)
const isLoading: any = ref(false)
const isAktive = ref()
const d_Dokter = ref([])
const d_Pegawai = ref([])
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
  tglDibuat: new Date()
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
  let response = await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }
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

const test = (e: any, i: any) => {

  let listSkor = listSkoringNyeri.value.detail

  listSkor.forEach((element: any) => {
    if (element.descNilai == e.descNilai) {
      input.value.skor = e.descNilai
    }
  });
  isAktive.value = i

}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}

const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Pegawai.value = response
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}


setView()
setAutoFill()

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


watch(() =>
  input.value.vas,
  () => {

    if (input.value.vas == 0) {
      input.value.skorVas = input.value.vas + " = Tidak Nyeri"
    } else if (input.value.vas <= 3) {
      input.value.skorVas = input.value.vas + " = Nyeri Ringan"
    } else if (input.value.vas <= 6) {
      input.value.skorVas = input.value.vas + " = Nyeri Sedang"
    } else {
      input.value.skorVas = input.value.vas + " = Nyeri Berat"
    }
  })


</script>

<style lang="scss">
.title-aapm {
  font-weight: bold !important;
}

.subTitle-aapm {
  font-weight: 500 !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
