<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="column is-12">
    <VCard>
      <div class="column mt-5" style="overflow: auto;">
        <table class="table-rpo">
          <thead>
            <tr>
              <th class="th-rpo" width="15%">JAM/TGL</th>
              <th class="th-rpo">NO DX</th>
              <th class="th-rpo">TINDAKAN KEPERAWATAN</th>
              <th class="th-rpo">EVALUASI</th>
              <th class="th-rpo">PARAF & NAMA TERANG</th>
              <th class="th-rpo" width="5%">#</th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in input.details" :key="index">
            <tr>
              <td class="td-rpo" style="text-align: center;">
                <VDatePicker v-model="item.tgltindakan" mode="dateTime" style="width: 100%;" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-rpo">
                <VField class="">
                  <VControl>
                    <VTextarea rows="6" v-model="item.nodx"></VTextarea>
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo">
                <VField class="">
                  <VControl>
                    <VTextarea rows="6" v-model="item.tindakankeperawatan"></VTextarea>
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo">
                <VField class="">
                  <VControl>
                    <VTextarea rows="6" v-model="item.evaluasi"></VTextarea>
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo">
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="item.paraf" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo" style="vertical-align: inherit">
                <div class="column">
                  <VButtons style="justify-content:space-around">
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem(index)" color="info"
                      v-tooltip.bubble="'Tambah '">
                    </VIconButton>
                    <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                      @click="removeItem(index)" color="danger">
                    </VIconButton>
                  </VButtons>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
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
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const d_Obat: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('ImplementasiKeperawatanIGD') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{
    no: 1,
    tgltindakan: new Date(),
    paraf: { label: user.namaLengkap, value: user.id }
  }],
})
const setView = () => {
  useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
    }
  })
}

const simpan = async () => {
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
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    NOREC_EMRPASIEN.value = response.norec_emr
    input.value.id = response.id
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
    d_Pegawai.value = response
  })
}

const addNewItem = (e: any) => {
  input.value.details.splice(e+1, 0, {
    no: input.value.details.length > 0 ? input.value.details[input.value.details.length - 1].no + 1 : 1,
    tgltindakan: new Date(),
    paraf: { label: user.namaLengkap, value: user.id }
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}
setView()
loadRiwayat()
</script>

<style lang="scss">
.table-rpo {
  width: 100%;
  border: 1px solid;
}

.th-rpo,
.td-rpo {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.th-rpo {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>