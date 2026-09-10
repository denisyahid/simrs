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
        <h1 class="h1-ti">Tujuan Transfer</h1>
        <div class="columns is-multiline p-3">
          <div class="column pb-0" v-for="(data) in tujuanTranfer">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input[data.model]" :true-value="data.label" class="p-0" :label="data.label"
                  color="primary" circle />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-7 pt-0">
          <VField>
            <VControl>
              <VTextarea v-model="item.ketTujuanTransfer" rows="2" placeholder="Tujuan Tranasfer lainnya ...">
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </div>
    </VCard>
  </div>

  <div class="column is-12">
    <VCard>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-3">
            <span class="span-ti">Tanggal Transfer</span>
            <VDatePicker class="pt-3" v-model="input.tglBerangkat" mode="dateTime" style="width: 100%" trim-weeks
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
          <div class="column is-4">
            <span class="span-ti">Ruangan Asal</span>
            <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
              <VControl icon="feather:search">
                <AutoComplete v-model="input.ruanganAsal" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  disabled :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama ruangan" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <span class="span-ti">Ruangan Tujuan</span>
            <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
              <VControl icon="feather:search">
                <AutoComplete v-model="input.ruanganTujuan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="ketik nama ruangan" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4">
            <span>Indikasi Rawat Inap</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea class="textarea" v-model="input.indikasiRawatInap" rows="2" placeholder="Keluhan Utama ..."
                  autocomplete="off" autocapitalize="off" spellcheck="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <span>Rawat Kesehatan</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea class="textarea" v-model="input.riwayatKesehatan" rows="2" placeholder="Keluhan Utama ..."
                  autocomplete="off" autocapitalize="off" spellcheck="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <span>Pemeriksaan Fisik</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea class="textarea" v-model="input.pemeriksaanFisik" rows="2" placeholder="Keluhan Utama ..."
                  autocomplete="off" autocapitalize="off" spellcheck="true" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column pl-0 pr-0">
          <span class="span-ti">Pemeriksaan Diagnostik</span>
          <div class="columns is-multiline p-3">
            <div class="column" v-for="(data) in pemeriksaanDiagnostik">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" v-model="input[data.model]" :true-value="data.label" :label="data.label"
                    color="primary" square />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4" v-for="(data) in dataTransfer">
            <span class="span-ti">{{ data.label }}</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea class="textarea" v-model="input[data.model]" rows="2" :placeholder="data.label + '...'"
                  autocomplete="off" autocapitalize="off" spellcheck="true" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </VCard>

    <div class="column pl-0 pr-0">
      <Fieldset :toggleable="true" legend="KONDISI SAAT TRANSFER">
        <div class="columns is-multiline">
          <div class="column" v-for="(data) in kondisiSaatTransfer" :class="data.type == 'textarea' ? 'is-6' : 'is-3'">
            <span class="span-ti">{{ data.label }}</span>
            <VField class="pt-3" v-if="data.type == 'textarea'">
              <VControl>
                <VTextarea class="textarea" v-model="input[data.model]" rows="2" :placeholder="data.label + '...'"
                  autocomplete="off" autocapitalize="off" spellcheck="true" />
              </VControl>
            </VField>
            <VField v-else class="pt-3">
              <VControl>
                <VInput type="text" v-model="input[data.model]" />
              </VControl>
            </VField>
          </div>
        </div>
      </Fieldset>
    </div>

    <div class="column pl-0 pr-0">
      <Fieldset :toggleable="true" legend="PERALATAN YANG TERPASANG">
        <div class="column" v-for="(datas) in peralatanTerpasang">
          <span class="span-ti">{{ datas.title }}</span>
          <div class="columns is-multiline pl-4 pr-4 pt-3">
            <div class="column is-3" v-for="(data) in datas.value">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" v-model="input[data.model]" :true-value="data.label" :label="data.label"
                    color="primary" square />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-7 pt-0 pb-0" v-if="datas.optional">
            <VField>
              <VControl>
                <VInput type="text" v-model="input[datas.optional.model]" />
              </VControl>
            </VField>
          </div>
        </div>
      </Fieldset>
    </div>

    <div class="column pl-0 pr-0">
      <Fieldset :toggleable="true" legend="KONDISI SAAT DITERIMA">
        <div class="column is-4">
          <span class="span-ti">Tanggal Terima</span>
          <VDatePicker class="pt-3" v-model="input.tglTerima" mode="dateTime" style="width: 100%" trim-weeks
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
          <span class="span-ti">Kondisi saat terima</span>
          <div class="columns is-multiline p-3">
            <div class="column is-4">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" v-model="input.kondisiTerima" true-value="SKT"
                    label="Sesuai kondisi saat transfer" color="primary" square />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" v-model="input.kondisiTerima" true-value="TSKT"
                    label="Tidak sesuai kondisi saat transfer" color="primary" square />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-8">
          <span class="span-ti">Catatan khusus</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="input.catatanKhusus" rows="3" placeholder="Catatan Khusus ...">
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </Fieldset>
    </div>

    <div class="column is-12 pl-0 pr-0">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-5">
            <div class="column p-0 mt-3" style="text-align: center;">
              <TandaTangan :elemenID="'signaturePendamping'" :width="'150'" :height="'150'" class="dek" />
            </div>

            <div class="column">
              <span style="font-weight: 500;">Dokter/Petugas/Perawat yang mendampingi</span>
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dokterPendamping" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai Pendamping..."
                    class="mt-2" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-5">
            <div class="column p-0 mt-3" style="text-align: center;">
              <TandaTangan :elemenID="'signaturePenerima'" :width="'150'" :height="'150'" class="dek" />
            </div>

            <div class="column">
              <span style="font-weight: 500;">Dokter/Petugas/Perawat yang menerima</span>
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dokterPenerima" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai Penerima..." class="mt-2" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
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
import * as EMR from '../page-emr-plugins/transfer-internal'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let tujuanTranfer = ref(EMR.tujuanTranfer())
let pemeriksaanDiagnostik = ref(EMR.pemeriksaanDiagnostik())
let dataTransfer = ref(EMR.dataTransfer())
let kondisiSaatTransfer = ref(EMR.kondisiSaatTransfer())
let peralatanTerpasang = ref(EMR.peralatanTerpasang())


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
const d_Ruangan: any = ref([])
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
        if (response[0].ttdPendamping) {
          H.tandaTangan().set("signaturePendamping", response[0].ttdPendamping)
        }
        if (response[0].ttdPenerima) {
          H.tandaTangan().set("signaturePenerima", response[0].ttdPenerima)
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
  object.pasien = H.setObjectPasien(props.pasien)
  object.ttdPendamping = H.tandaTangan().get("signaturePendamping")
  object.ttdPenerima = H.tandaTangan().get("signaturePenerima")
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
const setAutoFill = async () => {
  input.value.ruanganAsal = props.registrasi.namaruangan
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const fetchRuangan = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}

setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.h1-ti {
  font-weight: bold !important;
}

.span-ti {
  font-weight: 500;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
