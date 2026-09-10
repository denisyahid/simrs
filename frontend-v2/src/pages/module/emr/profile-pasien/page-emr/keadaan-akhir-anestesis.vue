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

  <VCard radius="small" v-for="(datas) in keadaanAkhirAnestesis">
    <h3 class="title is-5 mb-2">{{ datas.judul }}</h3>
    <p>
    <div class="columns is-multiline">
      <div class="column is-6" v-for="(data) in datas.desc">
        <span class="label-apas">{{ data.label }}</span>
        <VField class="pt-3">
          <VControl>
            <VInput v-model="input[data.modal]" rows="2" :placeholder="data.label">
            </VInput>
          </VControl>
        </VField>
      </div>
    </div>
    </p>
  </VCard>

  <VCard radius="small" v-for="(datas) in rangkumanPenggunaan">
    <h3 class="title is-5 mb-3 mt-3">{{ datas.title }}</h3>
    <p v-for="(obat) in datas.desc">
    <h3>
      <b>
        {{ obat.sub_title }} :
      </b>
    </h3>
    </p>
    <p>
    <div class="columns is-multiline" v-for="(data) in datas.desc">
      <div class="column is-4" v-for="(values) in data.value">
        <span v-if="values.head_label ?? ''">
          <h3><b>{{ values.head_label }}</b></h3>
        </span>
        <span class="label-apas">{{ values.label }}</span>
        <VField class="pt-3">
          <VControl>
            <VInput v-model="input[values.model]" rows="2" :placeholder="values.satuan">
            </VInput>
          </VControl>
        </VField>
      </div>
    </div>
    </p>
    <P v-for="(data) in obatInput" class="mt-3">
    <h3>
      <b>
        {{ data.title }}
      </b>
    </h3>
    <div class="columns is-multiline">
      <div class="column is-8" v-for="(datas) in data.desc">
        <span class="label-apas">{{ datas.label }}</span>
        <VField class="pt-3">
          <VControl>
            <VInput v-model="input[datas.model]" rows="2" :placeholder="datas.label">
            </VInput>
          </VControl>
        </VField>
      </div>
    </div>
    </P>
    <P v-for="(data) in obatOutput" class="mt-3">
    <h3>
      <b>
        {{ data.title }}
      </b>
    </h3>
    <div class="columns is-multiline">
      <div class="column is-8" v-for="(datas) in data.desc">
        <span class="label-apas">{{ datas.label }}</span>
        <VField class="pt-3">
          <VControl>
            <VInput v-model="input[datas.model]" rows="2" :placeholder="datas.label">
            </VInput>
          </VControl>
        </VField>
      </div>
    </div>
    </P>
    <p>
    <div class="columns is-multiline">
      <div class="column is-4">
        <span class="label-apas">Bandung</span>
        <VDatePicker v-model="input.tglDibuatObat" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
          <template #default="{ inputValue, inputEvents }">
            <VField class="pt-3">
              <VControl icon="feather:calendar" fullwidth>
                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
              </VControl>
            </VField>
          </template>
        </VDatePicker>
      </div>
    </div>
    </p>
    <P>
    <div class="columns is-multiline">
      <div class="column is-4" style="text-align: center;">
        <TandaTangan :elemenID="'signatureDokterObat'" :width="'180'" :height="'180'" class="dek" />
      </div>
    </div>
    </P>
    <p>
    <div class="columns is-multiline">
      <div class="column is-4">
        <span class="label-apas">Dokter</span>
        <VField class="pt-3">
          <VControl class="prime-auto">
            <AutoComplete v-model="input.dokterObat" :suggestions="d_Dokter" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
              @item-select="setTandaTanganDokterObat($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
              placeholder="Cari Dokter..." />
          </VControl>
        </VField>
      </div>
    </div>
    </p>
  </VCard>

  <vCard>
    <div class="columns is-multiline">
      <div class="column is-3">
        <h3>Instruksi Pasca Bedah</h3>
      </div>
      <div class="column is-9">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.rawatRuangan" label="Rawat Runagan" color="primary" />
            </VControl>
          </div>
          <div class="column is-3">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.icu" label="ICU" color="primary" />
            </VControl>
          </div>
          <div class="column is-3">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.mcu" label="MCU" color="primary" />
            </VControl>
          </div>
          <div class="column is-3">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.ods" label="ODS" color="primary" />
            </VControl>
          </div>
          <div class="column is-3">
            <VControl raw subcontrol>
              <VCheckbox v-model="input.rr" label="RR" color="primary" />
            </VControl>
          </div>
        </div>
      </div>

    </div>
    <div class="columns is-multiline">
      <div class="column is-12">
        <span class="label-apas">Oxygen</span>
        <VField class="pt-3">
          <VControl>
            <VInput v-model="input.oxygen" rows="2">
            </VInput>
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-12">
        <span class="label-apas">Infus</span>
        <VField class="pt-3">
          <VControl>
            <VInput v-model="input.infus" rows="2">
            </VInput>
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-8">
        <span class="label-apas">Diet</span>
        <p>
          <VControl raw subcontrol>
            <VCheckbox v-model="input.dietBolehMakan" label="Boleh makan sepert biasa setelah sadar penuh"
              color="primary" />
          </VControl>
        </p>
        <p>
          <VControl raw subcontrol>
            <VCheckbox v-model="input.dietSesuaiIntruksi" label="Sesuai dengan intruksi dari Dr." color="primary" />
          </VControl>
        </p>
        <p>
          <VField>
            <VControl>
              <VInput v-model="input.textIntruksiDr">
              </VInput>
            </VControl>
          </VField>
        </p>
        <p>
          <VControl raw subcontrol>
            <VCheckbox v-model="dietPuasaSementara"
              label="Puasa sementara sampai ada intruksi selanjutnya, dan untuk penetral nutrisi dapat diberikan"
              color="primary" />
          </VControl>
        </p>
        <p>
          <VField>
            <VControl>
              <VInput v-model="input.textIntruksiPuasa">
              </VInput>
            </VControl>
          </VField>
        </p>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-12">
        <span class="label-apas">Analgesik</span>
        <VField class="pt-3">
          <VControl>
            <VTextarea v-model="input.analgesik" rows="2">
            </VTextarea>
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-12">
        <span class="label-apas">Obat - obat lain</span>
        <VField class="pt-3">
          <VControl>
            <VTextarea v-model="input.obatObatLain" rows="2"></VTextarea>
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-8">
        <span class="label-apas">Posisi</span>
        <p>
          <VControl raw subcontrol>
            <VCheckbox v-model="input.mobilitas" label="Mobilitas bebas setelah sadar penuh" color="primary" />
          </VControl>
        </p>
        <p>
          <VControl raw subcontrol>
            <VCheckbox v-model="input.supine" label="Supine (flat on bed) dengan 1 bantal sampai jam" color="primary" />
          </VControl>
        </p>
        <p>
          <VField class="pt-3" style="width: 30%">
            <VControl>
              <VInput v-model="input.jamSupine" type="time" />
            </VControl>
          </VField>
        </p>
        <p>
          <VControl raw subcontrol>
            <VCheckbox v-model="input.supineHeadUp"
              label="Supine dengan posisi head up 30 sampai ada intruksi selanjutnya" color="primary" />
          </VControl>
        </p>
        <p>
          <VControl raw subcontrol>
            <VCheckbox v-model="input.posisiHeadUp" label="Posisi head up 30 + leteral decubitus (posisi lonsillectomy)"
              color="primary" />
          </VControl>
        </p>
        <p>
          <VControl raw subcontrol>
            <VCheckbox v-model="input.posisiLainnya" label="Posisi lainnya" color="primary" />
          </VControl>
        </p>
        <p>
          <VField>
            <VControl>
              <VInput v-model="input.textPosisiLainnya">
              </VInput>
            </VControl>
          </VField>
        </p>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-12">
        <span class="label-apas">Khusus</span>
        <p>
          <VControl raw subcontrol>
            <VCheckbox v-model="input.bolehPulang" label="Boleh pulang setelah sadar penuh dan didampingi orang dewasa"
              color="primary" />
          </VControl>
        </p>
        <p>
          <VControl raw subcontrol>
            <VCheckbox v-model="input.perhatikanTanda"
              label="Perhatikan tanda - tanda vital nyeri, mual dan muntah, gatal apabila ada keluhan seperti yang disebutkan lapor"
              color="primary" />
          </VControl>
        </p>
        <p>
          <VControl raw subcontrol>
            <VCheckbox v-model="input.pemeriksaanLab" label="Pemeriksaan laboratorium pasca operasi" color="primary" />
          </VControl>
        </p>
        <p>
          <VField>
            <VControl>
              <VInput v-model="input.textKhusus">
              </VInput>
            </VControl>
          </VField>
        </p>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-12">
        <span class="label-apas">Lain - lain</span>
        <VField class="pt-3">
          <VControl>
            <VTextarea v-model="input.khususObatLain" rows="2"></VTextarea>
          </VControl>
        </VField>
      </div>
    </div>
    <p>
    <div class="columns is-multiline">
      <div class="column is-4">
        <span class="label-apas">Bandung</span>
        <VDatePicker v-model="input.tglDibuatIPB" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
          <template #default="{ inputValue, inputEvents }">
            <VField class="pt-3">
              <VControl icon="feather:calendar" fullwidth>
                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
              </VControl>
            </VField>
          </template>
        </VDatePicker>
      </div>
    </div>
    </p>
    <P>
    <div class="columns is-multiline">
      <div class="column is-4" style="text-align: center;">
        <TandaTangan :elemenID="'signatureDokterIPB'" :width="'180'" :height="'180'" class="dek" />
      </div>
    </div>
    </P>
    <p>
    <div class="columns is-multiline">
      <div class="column is-4">
        <span class="label-apas">Dokter</span>
        <VField class="pt-3">
          <VControl class="prime-auto">
            <AutoComplete v-model="input.dokterIPB" :suggestions="d_Dokter" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
              @item-select="setTandaTanganDokterIPB($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
              placeholder="Cari Dokter..." />
          </VControl>
        </VField>
      </div>
    </div>
    </p>
  </vCard>

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
import * as EMR from '../page-emr-plugins/keadaan-akhir-anestesis'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let keadaanAkhirAnestesis = ref(EMR.keadaanAkhirAnestesis())
let rangkumanPenggunaan = ref(EMR.rangkumanPenggunaan())
let obatInput = ref(EMR.obatInput())
let obatOutput = ref(EMR.obatOutput())


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
const input: any = ref({
  tglDibuatIPB: new Date()
})
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
        if (response[0].ttdObat) {
          H.tandaTangan().set("signatureDokterObat", response[0].ttdObat)
        }
        if (response[0].ttdIPB) {
          H.tandaTangan().set("signatureDokterIPB", response[0].ttdIPB)
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
  object.ttdObat = H.tandaTangan().get("signatureDokterObat")
  object.ttdIPB = H.tandaTangan().get("signatureDokterIPB")
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

const setTandaTanganDokterObat = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signatureDokterObat", element.ttd)
    } else {
      H.tandaTangan().set("signatureDokterObat", '')
    }
  })
}
const setTandaTanganDokterIPB = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signatureDokterIPB", element.ttd)
    } else {
      H.tandaTangan().set("signatureDokterIPB", '')
    }
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

    input.value.kesadaran = response.kesadaran
    input.value.spO2 = response.spO2
    input.value.refleksMenelan = response.refleksMenelan
    input.value.tekananDarah = response.tekananDarah
    input.value.respirasi = response.respirasi
    input.value.suhu = response.suhu
    input.value.nadi = response.nadi
    input.value.lainLain = response.lainLain
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
