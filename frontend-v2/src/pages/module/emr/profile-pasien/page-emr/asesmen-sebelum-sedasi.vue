<template>
  <section>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">

        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

      </div>
    </div>


    <div class="column">
      <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
      <VCard v-else>
        <div class="column" v-for="(datas) in pengkajianSebelum">
          <span v-if="datas.title">{{ datas.title }}</span>
          <div class="columns is-multiline" :class="datas.title ? 'pt-3 pl-4 pr-3' : ''">
            <div class="column" v-for="(data) in datas.detail" :class="data.type == 'checkBox' ? 'is-1' : 'is-4'">
              <span v-if="data.type != 'checkBox'">{{ data.label }}</span>
              <VField v-if="data.type == 'textarea'">
                <VControl>
                  <VTextarea v-model="input[data.model]" rows="2">
                  </VTextarea>
                </VControl>
              </VField>
              <VField v-else-if="data.type == 'checkBox'">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.label" class="p-0" :label="data.label"
                    color="primary" squere />
                </VControl>
              </VField>
              <VField v-else>
                <VControl>
                  <VInput type="text" v-model="input[data.model]" :placeholder="data.placeholder" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column ">
          <div class="column pl-0" style="border-bottom: 1px solid black;">
            <h1 class="title-ass">Penilaian Sistem</h1>
          </div>
          <div class="columns is-multiline p-3">
            <div class="column is-6" v-for="(datas) in riwayatSistem">
              <span class="label-ass">{{ datas.title }}</span>
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[datas.detail.model]" :true-value="datas.detail.label" class="p-0"
                      :label="datas.detail.label" color="primary" squere />
                  </VControl>
                </VField>
              </div>
              <div class="columns is-multiline pl-5 pr-5 pt-3 ml-3 mr-3" v-for="(data) in datas.detail.value">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.model]" :true-value="data.code" class="p-0" :label="data.label"
                      color="primary" squere />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

        </div>

        <div class="column">

          <div class="column pl-0" style="border-bottom: 1px solid black;">
            <h1 class="title-ass">Pemeriksaan Fisik</h1>
          </div>
          <div class="columns is-multiline p-3">
            <div class="column is-6" v-for="(datas) in pemeriksaanFisik">
              <span class="label-ass">{{ datas.title }}</span>
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[datas.detail.model]" :true-value="datas.detail.label" class="p-0"
                      :label="datas.detail.label" color="primary" squere />
                  </VControl>
                </VField>
              </div>
              <div class="columns is-multiline pl-5 pr-5 pt-3 ml-3 mr-3" v-for="(data) in datas.detail.value">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.model]" :true-value="data.code" class="p-0" :label="data.label"
                      color="primary" squere />
                  </VControl>
                </VField>
              </div>
              <div class="column is-9" v-if="datas.optional">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="input[datas.optional]" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

        </div>

        <div class="column pt-0 ">
          <span class="label-ass">Catatan tambahan</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="input.catatanTambahan" rows="2">
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </VCard>
    </div>
  </section>
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
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/asesmen-sebelum-sedasi'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let pengkajianSebelum = ref(EMR.pengkajianSebelum())
let riwayatSistem = ref(EMR.riwayatSistem())
let pemeriksaanFisik = ref(EMR.pemeriksaanFisik())

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
const loadRiwayat = async () => {

  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
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

const kembaliKeun = () => {
  window.history.back()
}

setView()

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
.label-ass {
  font-weight: 500;
}

.title-ass {
  font-weight: bold;
}
</style>
