<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                Kembali
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
              </VButton>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="column is-12 pb-0">
        <h1 class="h1-trj">Diagnosa dan Rencana Tindakan Keperawatan</h1>
        <div class="column is-12 pt-2">
          <span class="sub-title-trj">Diagnosis/indikasi dilakukan resusitasi jantung paru</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="item.rencanaTindakan" rows="3" placeholder="Rencana Tindakan ...">
              </VTextarea>
            </VControl>
          </VField>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-3">
              <span class="sub-title-trj">Dimulai pada pukul</span>
              <VDatePicker class="column is-5" v-model="input.waktuMulai" color="green" mode="time" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl>
                      <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-3">
              <span class="sub-title-trj">Diakhiri pada pukul</span>
              <VDatePicker class="column is-5" v-model="input.waktuSelesai" color="green" mode="time" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl>
                      <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column">
              <span class="sub-title-trj">Lama resusitasi jantung paru</span>
              <VField class="mt-2">
                <VControl>
                  <VInput type="text" class="input" v-model="input.lamaResusitansi" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-12 pt-0 pb-0">
        <h1 class="h1-trj">Resusitasi jantung paru dilakukan oleh</h1>
        <div class="columns is-multiline pl-3 pr-3">
          <div class="column is-6">
            <div class="column is-3 p-0">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.isDokter" class="pl-0" true-value="Dokter" label="Dokter" color="primary"
                    circle />
                </VControl>
              </VField>
            </div>
            <VField class="column is-10 p-0">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Dokter..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <div class="column is  -3 p-0">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.isPerawat" class="pl-0" true-value="Perawat" label="Perawat" color="primary"
                    circle />
                </VControl>
              </VField>
            </div>
            <VField class="column is-10 p-0">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.perawat" :suggestions="d_Perawat" @complete="fetchPerawat($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Perawat..." />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="columns is-multiline">
          <div class="column is-3">
            <span class="sub-title-trj">Dilakukan intubasi pada jam</span>
            <VDatePicker class="column is-5" v-model="input.waktuIntubasi" color="green" mode="time" is24hr>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl>
                    <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-5">
            <span class="sub-title-trj">Ukuran ETT</span>
            <VField class="mt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.ukuranETT" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <span class="sub-title-trj">Batas</span>
            <VField class="mt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.batas" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <h1 class="h1-trj">Pelaksaan intubasi</h1>
        <div class="columns is-multiline pl-3">
          <div class="column is-2">
            <VField style="position: relative;top: 12px;">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.pelaksanaIntubasi" class="pl-0" true-value="Dokter" label="Dokter"
                  color="primary" circle />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField style="position: relative;top: 12px;">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.pelaksanaIntubasi" class="pl-0" true-value="Perawat" label="Perawat"
                  color="primary" circle />
              </VControl>
            </VField>
          </div>
          <div class="column pt-0 mt-1">
            <span class="sub-title-trj">Jenis intubasi</span>
            <VField class="mt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.jenisIntubasi" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="columns is-multiline pl-3">
          <div class="column is-6">
            <span class="sub-title-trj">Pemasangan akses intra vena</span>
            <VField class="mt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.pmsAkses" />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <span class="sub-title-trj">Cairan IV yang dipakai</span>
            <VField class="mt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.cairanDipakai" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12" style="overflow-y: auto;">
        <table class="table-trj" width="180%">
          <thead>
            <tr>
              <th class="th-trj center-trj" colspan="11">Penatalaksanaan Resusitasi Jantung Paru (Monitoring
                Obat-obatan, Defibrilasi dan lain-lain)</th>
            </tr>
            <tr>
              <th class="th-trj vc-trj" rowspan="2" width="8%">
                Tanggal Jam
              </th>
              <th class="th-trj center-trj" colspan="5">Observasi</th>
              <th class="th-trj center-trj" colspan="3">Terapi</th>
              <th class="th-trj vc-trj" rowspan="2" width="12%">Keterangan</th>
              <th class="th-trj vc-trj" rowspan="2" width="3%">#</th>
            </tr>
            <tr>
              <th class="th-trj" width="5%">Nadi</th>
              <th class="th-trj" width="5%">RR</th>
              <th class="th-trj" width="6%">Tekanan Darah</th>
              <th class="th-trj" width="11%">Gambaran EKG</th>
              <th class="th-trj" width="5%">SPO2</th>
              <th class="th-trj" width="8%">Nama</th>
              <th class="th-trj" width="5%">Dosis</th>
              <th class="th-trj" width="5%">Rute</th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in input.details" :key="index">
            <tr>
              <td class="td-trj">
                <VDatePicker class="pt-5 pb-5 pl-2 pr-2" v-model="item.tglResusitasi" color="green" trim-weeks
                  mode="dateTime" :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }" class="pb-0">
                    <VField>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                          class="is-rounded_Z" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-trj">
                <div class="column pt-5 pb-5 pl-2 pr-2">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.nadi" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-trj">
                <div class="column pt-5 pb-5 pl-2 pr-2">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.pernapasan" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-trj">
                <div class="column pt-5 pb-5 pl-2 pr-2">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.tekananDarah" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-trj">
                <div class="column pt-5 pb-5 pl-2 pr-2">
                  <VField>
                    <VControl>
                      <VTextarea v-model="item.gambaranEkg" rows="2" placeholder="Gambaran EKG ...">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-trj">
                <div class="column pt-5 pb-5 pl-2 pr-2">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.nama" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-trj">
                <div class="column pt-5 pb-5 pl-2 pr-2">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.spo2" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-trj">
                <div class="column pt-5 pb-5 pl-2 pr-2">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.dosis" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-trj">
                <div class="column pt-5 pb-5 pl-2 pr-2">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.rute" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-trj">
                <div class="column pb-0">
                  <VField>
                    <VControl>
                      <VTextarea v-model="item.keterangan" rows="2" placeholder="Keterangan ...">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-trj center-trj">
                <div class="column">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                    v-tooltip.bubble="'Tambah '">
                  </VIconButton>
                  <VIconButton class="mt-1" type="button" v-if="index > 0" raised circle icon="feather:trash"
                    @click="removeItem(index)" v-tooltip.bubble="'Hapus'" color="danger">
                  </VIconButton>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="column is-12">
        <Fieldset :toggleable="true" legend="DEFRIBRILASI">
          <table class="table-trj" width="100%">
            <thead>
              <tr>
                <th class="th-trj" width="1%">NO</th>
                <th class="th-trj">Ritme EKG</th>
                <th class="th-trj">Waktu</th>
                <th class="th-trj">Ritme EKG</th>
                <th class="th-trj">Joules</th>
                <th class="th-trj center-trj" width="12%">#</th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.defribrilasis" :key="index">
              <tr>
                <td class="td-trj vc-trj">
                  <span style="font-weight: 500;" class="p-3">1</span>
                </td>
                <td class="td-trj">
                  <div class="column">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.ritmeEKG" />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td class="td-trj">
                  <div class="column">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.waktu" />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td class="td-trj">
                  <div class="column">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.ritmeEkg" />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td class="td-trj">
                  <div class="column">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.joules" />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td class="td-trj">
                  <div class="column">
                    <VButtons>
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemDefribrilasis()"
                        color="info" v-tooltip.bubble="'Tambah'">
                      </VIconButton>
                      <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                        @click="removeItemDefribrilasis(index)" v-tooltip.bubble="'Hapus'" color="danger">
                      </VIconButton>
                    </VButtons>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </Fieldset>
      </div>

      <div class="column is-12">
        <VCard>
          <div class="column is-12">
            <span class="sub-title-trj">Hasil Akhir Resusitasi Jantung Paru</span>
            <VField>
              <VControl>
                <VTextarea v-model="item.hasilAkhir" rows="2" placeholder="Keterangan ...">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="columns is-multiline p-3">
            <div class="column is-2">
              <span class="sub-title-trj">Keterangan :</span>
            </div>
            <div class="column is-6">
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="item.ketHasilAkhir" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column">
            <span class="sub-title-trj">Outcome resusitasi jantung paru</span>
            <div class="columns is-multiline">
              <div class="column is-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.outcomeRes" true-value="ICU" label="ICU" color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.outcomeRes" true-value="Ruang Umum" label="Ruang Umum" color="primary"
                      circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.outcomeRes" true-value="Meninggal" label="Meninggal" color="primary"
                      circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

        </VCard>
      </div>

      <div class="column is-4" style="margin-left: auto;">
        <VCard>
          <div class="column pb-0">
            <span class="label-apta">Tanggal dan Jam</span>
            <VDatePicker v-model="input.tglDibuat" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField  class="column is-8 p-0 mt-3">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>

          <div class="column">
            <span class="label-ppap">Dokter</span>
              <VField class="pt-3">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.perawat" :suggestions="d_Perawat" @complete="fetchPerawat($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Perawat..." />
                </VControl>
              </VField>
          </div>
          <div class="column">
            <span class="label-ppap">Perawat</span>
              <VField class="pt-3">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Dokter..." />
                </VControl>
              </VField>
          </div>
        </VCard>
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
import Fieldset from 'primevue/fieldset';
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
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
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
  }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('TindakanResusitasiJantungParu') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_Dokter = ref([])
const d_Perawat = ref([])
const input: any = ref({
  waktuMulai: new Date(),
  waktuSelesai: new Date(),
  waktuIntubasi: new Date(),
  tglDibuat : new Date(),
  details: [{
    tglResusitasi: new Date(),
  }],
  defribrilasis: [{
    no: 1,
  }]
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

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchPerawat = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
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
const addNewItemDefribrilasis = () => {
  input.value.defribrilasis.push({
    no: input.value.defribrilasis[input.value.defribrilasis.length - 1].no + 1,
  });
}
const removeItemDefribrilasis = (index: any) => {
  input.value.defribrilasis.splice(index, 1)
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
.h1-trj {
  font-weight: bold;
}

.sub-title-trj {
  font-weight: 500 !important;
}

.table-trj {
  border-collapse: collapse;
}

.th-trj,
.td-trj {
  border: 1px solid black;
}

.center-trj {
  text-align: center !important;
}

.vc-trj {
  vertical-align: inherit !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
