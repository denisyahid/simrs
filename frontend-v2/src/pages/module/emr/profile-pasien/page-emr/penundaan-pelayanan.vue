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
      <div class="column is-12">
        <span class="label-pp">Jenis Penundaan Pelayanan</span>
        <div class="columns is-multiline p-3">
          <div class="column is-3" v-for="(data) in jenisPenundaan">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input[data.model]" :true-value="data.code" class="p-0" :label="data.label"
                  color="primary" squer />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <span class="label-pp">Alasan Penundaan</span>
        <div class="columns is-multiline p-3">
          <div class="column is-3" v-for="(data) in alasanPenundaan">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input[data.model]" :true-value="data.code" class="p-0" :label="data.label"
                  color="primary" squer />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <span class="label-pp">Alternatif yang ditawarkan</span>
        <div class="columns is-multiline pt-3 pl-3 pr-3">
          <div class="column is-3 pb-0">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jadwalkanUlang" true-value="JU" class="p-0"
                  label="Dijadwalkan ulang dengan prioritas" color="primary" squer />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 pb-0">
            <span class="label-pp">Tanggal</span>
            <VDatePicker v-model="item.tglPindah" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks>
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

        <div class="columns is-multiline pl-3 pr-3">
          <div class="column is-3 pb-0">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.dirujuk" true-value="rujuk" class="p-0" label="Dirujuk ke faskes lain"
                  color="primary" squer />
              </VControl>
            </VField>
          </div>
          <div class="column is-4 pb-0">
            <span class="label-pp">Faskes tujuan</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.faskesTujuan" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline pl-3 pr-3 pt-3">
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.alternatifLain" true-value="lain" class="p-0" label="Lain-lain" color="primary"
                  squer />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <span class="label-pp"></span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.ketLain" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <div class="column is-8">
          <span class="label-pp">Risiko/manfaat tindakan alternatif</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="input.resikoTindakanAlternatif" rows="2"
                placeholder="Risiko/manfaat tindakan alternatif...'">
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </div>
      <div class="column">
        <div class="column is-8">
          <span class="label-pp">Risiko dan manfaat penundaan</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="input.resikoPenundaan" rows="2" placeholder="Risiko dan manfaat penundaan...'">
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </div>
    </VCard>
  </div>

  <div class="column">
    <VCard>
      <div class="column">
        <span class="label-pp">Saya yang bertanda tangan dibawah ini, telah mendapatkan penjelasan dan mengenai alasan penundaan pelayanan
          tersebut beserta alternatif tindakan yang dapat saya lakukan.</span>
      </div>
      <div class="columns is-multiline pt-5" style="justify-content: space-around;">
        <div class="column is-4" style="text-align: center;">
          <TandaTangan :elemenID="'signatureWali'" :width="'180'" :height="'180'" class="dek" />
          <div class="column pl-0 pr-0 pt-5">
            <span class="label-pp">Yang Menyetujui Pasien/Keluarga/Wali</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" class="input" v-model="input.menyetujui" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4" style="text-align: center;">
          <TandaTangan :elemenID="'signaturePegawai'" :width="'180'" :height="'180'" class="dek" />
          <div class="column pl-0 pr-0 pt-5">
            <span class="label-pp">Yang Memberi Informasi</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.pegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)" @item-select="setTandaTanganPerawat($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Pegawai..." />
              </VControl>
            </VField>
          </div>
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
// import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/penundaan-pelayanan'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let jenisPenundaan = ref(EMR.jenisPenundaan())
let alasanPenundaan = ref(EMR.alasanPenundaan())

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
        if (response[0].ttdWali) {
          H.tandaTangan().set("signatureWali", response[0].ttdWali)
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
  object.ttdWali = H.tandaTangan().get("signatureWali")
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

const setTandaTanganPerawat = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signaturePegawai", element.ttd)
      }else{
        H.tandaTangan().set("signaturePegawai", '')
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




</script>

<style lang="scss">
.label-pp{
  font-weight: 500;
}
</style>
