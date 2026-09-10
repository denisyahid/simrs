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
      <h1 style="font-weight: 500;">RIWAYAT PENGGUNAAN OBAT</h1>
      <div class="column" style="overflow: auto;">
        <table class="table-rpo">
          <thead>
            <tr>
              <th class="th-rpo" width="3%">No</th>
              <th class="th-rpo">Nama Obat</th>
              <th class="th-rpo">Bentuk/Sediaan</th>
              <th class="th-rpo">Dosis</th>
              <th class="th-rpo">Aturan Pakai</th>
              <th class="th-rpo" width="10%">#</th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in input.details" :key="index">
            <tr>
              <td class="td-rpo" style="text-align: center;">{{ item.no }}</td>
              <td class="td-rpo">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.obat" :suggestions="d_Obat" @complete="fetchObat($event)"
                      :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" placeholder="ketik nama obat" />
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo">
                <VField class="">
                  <VControl>
                    <VInput type="text" v-model="item.aturanPakai" />
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo">
                <VField class="">
                  <VControl>
                    <VInput type="text" v-model="item.bentuk" />
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo">
                <VField class="">
                  <VControl>
                    <VInput type="text" v-model="item.dosis" />
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo" style="vertical-align: inherit">
                <div class="column">
                  <VButtons style="justify-content:space-around">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
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

      <div class="column">
        <span style="font-weight: 500;">Catatan Petugas</span>
        <VField class="pt-3">
          <VControl>
            <VTextarea v-model="input.catatanPetugas" rows="2">
            </VTextarea>
          </VControl>
        </VField>
      </div>
    </VCard>
  </div>

  <div class="column is-12">
    <VCard>
      <div class="column is-3" style="margin-left: auto;">
        <span style="font-weight: 500;">Bandung</span>
        <VDatePicker v-model="input.tglDibuat" mode="dateTime" class="pt-3" style="width: 100%" trim-weeks
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

      <div class="columns is-multiline pt-5" style="justify-content: space-around;">
        <div class="column is-4">
          <div class="column p-0 mt-3" style="text-align: center;">
            <TandaTangan :elemenID="'signatureKeluarga'" :width="'180'" :height="'180'" class="dek" />
          </div>
          <div class="column">
            <span style="font-weight: 500;">Nama Pasien / Keluarga</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="item.namaBersangkutan" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-4">
          <div class="column p-0 mt-3" style="text-align: center;">
            <TandaTangan :elemenID="'signaturePetugas'" :width="'180'" :height="'180'" class="dek" />
          </div>
          <div class="column">
            <span style="font-weight: 500;">Petugas</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.petugas" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Petugas..." @item-select="setTandaTanganPegawai($event)" />
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
import Fieldset from 'primevue/fieldset';
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
const d_Pegawai: any = ref([])
const d_Obat: any = ref([])
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
  details: [{
    no: 1,
  }],
  tglDibuat : new Date()
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
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].ttdKeluarga) {
          H.tandaTangan().set("signatureKeluarga", response[0].ttdKeluarga)
        }
        if (response[0].ttdPetugas) {
          H.tandaTangan().set("signaturePetugas", response[0].ttdPetugas)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.ttdKeluarga = H.tandaTangan().get("signatureKeluarga")
  object.ttdPetugas = H.tandaTangan().get("signaturePetugas")
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

const setTandaTanganPegawai = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signaturePetugas", element.ttd)
      }else{
        H.tandaTangan().set("signaturePetugas", '')
      }
    })
}

const fetchObat = async (filter: any) => {

  await useApi().get(
    `emr/get-obat?filter=${filter.query}`
  ).then((response) => {
    d_Obat.value = response
  })
}

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
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
