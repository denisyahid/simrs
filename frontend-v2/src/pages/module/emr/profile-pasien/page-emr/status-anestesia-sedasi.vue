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


  <div class="column">
    <VCard>
      <TabView :scrollable="true" class="skuy">
        <TabPanel header="A. ASESMEN PRA ANESTESIA / SEDASI">
          <div class="column">
            <VCard>
              <div class="columns is-multiline">
                <div class="column is-4">
                  <span class="label-sas">Diagnosa pra bedah</span>
                  <VField class="pt-3">
                    <VControl>
                      <VTextarea class="textarea" v-model="input.diagPraBedah" rows="2" autocomplete="off"
                        autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <span class="label-sas">Jenis Pembedahan</span>
                  <VField class="pt-3">
                    <VControl>
                      <VTextarea class="textarea" v-model="input.jnsPembedahan" rows="2" autocomplete="off"
                        autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <span class="label-sas">Diagnosa pasca bedah</span>
                  <VField class="pt-3">
                    <VControl>
                      <VTextarea class="textarea" v-model="input.diagPasBedah" rows="2" autocomplete="off"
                        autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </VCard>
          </div>
          <div class="column">
            <Fieldset :toggleable="true" legend="Assessment pra anestesia / sedasi">
              <div class="columns is-multiline pt-3 pl-3 pr-3">
                <div class="column is-4" v-for="(data) in sedasi">
                  <div class="column p-0">
                    <span>{{ data.title }}</span>
                    <VField class="pt-3">
                      <VControl>
                        <VTextarea class="textarea" v-model="input[data.model]" rows="2" autocomplete="off"
                          autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column pl-0 pr-0 pb-0">
                    <span>{{ data.opsi.label }}</span>
                    <div class="columns is-multiline pl-3 pr-3">
                      <div class="column pl-0 pb-0" v-for="(pilihan) in data.opsi.value">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label" :label="pilihan.label"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column pl-0 pr-0">
                    <span>{{ data.more.label }}</span>
                    <VField class="pt-3">
                      <VControl>
                        <VInput type="text" v-model="input[data.more.model]" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column">
            <VCard class="mt-3">
              <div class="column is-7">
                <span>Pemeriksaan Fisik dan Penunjang</span>
                <VField class="pt-3">
                  <VControl>
                    <VTextarea class="textarea" v-model="input.pmrksFisikPen" rows="2" autocomplete="off"
                      autocapitalize="off" spellcheck="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column" v-for="(datas) in pemeriksaanFisik">
                <span>{{ datas.title }}</span>
                <VField class="column is-4 p-0 mt-3">
                  <VControl>
                    <VInput type="text" v-model="input[datas.model]" />
                  </VControl>
                </VField>
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data) in datas.detail">
                    <span>{{ data.label }}</span>
                    <VField class="pt-3">
                      <VControl>
                        <VInput type="text" v-model="input[data.model]" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-7">
                <span>Lab dan Pemeriksaan tambahan</span>
                <VField class="pt-3">
                  <VControl>
                    <VTextarea class="textarea" v-model="input.pmrksTambahan" rows="2" autocomplete="off"
                      autocapitalize="off" spellcheck="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column">
                <span>Analisa Pemeriksaan Fisik</span>
                <VField class="pt-3">
                  <VControl>
                    <VTextarea class="textarea" v-model="input.analisPmrksFisik" rows="2" autocomplete="off"
                      autocapitalize="off" spellcheck="true" />
                  </VControl>
                </VField>
                <div class="columns is-multiline p-3" v-for="(datas) in poinPemeriksaanFisik">
                  <div class="column is-1" v-for="(data, i) in datas.label">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[datas.model + i]" :true-value="data" class="p-0" :label="data"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="columns is-multiline">
                  <div class="column is-7">
                    <span>Penyulit pra anestesia</span>
                    <VField class="pt-3">
                      <VControl>
                        <VTextarea class="textarea" v-model="input.pnyltPraAnest" rows="2" autocomplete="off"
                          autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <span>Informed Consent</span>
                    <div class="columns is-multiline">
                      <div class="column">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.inforConsent" true-value="Ya" label="Ya" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.inforConsent" true-value="Tidak" label="Tidak" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </VCard>
          </div>

          <div class="column">
            <Fieldset :toggleable="true" legend="Rencana teknik anestesia">
              .column
            </Fieldset>
          </div>
        </TabPanel>
        <TabPanel header="B. RE-ASSESSMENT PRA-ANESTESIA">

        </TabPanel>
        <TabPanel header="B. INTRUKSI PASCA BEDAH">
        </TabPanel>
      </TabView>

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
import * as EMR from '../page-emr-plugins/status-anestesia-sedasi'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let sedasi = ref(EMR.sedasi())
let pemeriksaanFisik = ref(EMR.pemeriksaanFisik())
let poinPemeriksaanFisik = ref(EMR.poinPemeriksaanFisik())

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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }

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
.label-sas {
  font-weight: 500;
}

.title-sas {
  font-weight: bold;
}
</style>
