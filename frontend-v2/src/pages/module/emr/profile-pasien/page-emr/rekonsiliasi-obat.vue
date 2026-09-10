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
      <div class="column" style="text-align: center;">
        <h1 class="title-ro">A. RIWAYAT ALERGI OBAT</h1>
      </div>
      <div class="column" style="overflow: auto;">
        <table class="table-ro">
          <thead>
            <tr>
              <th class="th-ro" rowspan="2" width="18%">Tanggal</th>
              <th class="th-ro" rowspan="2">Obat Yang Menimbulkan Alergi</th>
              <th class="th-ro" colspan="4">Keparahan Reaksi Alergi (✓)</th>
              <th class="th-ro" rowspan="2">Bentuk Reaksi Alergi</th>
              <th class="th-ro" rowspan="2" width="8%">#</th>
            </tr>
            <tr>
              <th class="th-ro">Berat</th>
              <th class="th-ro">Sedang</th>
              <th class="th-ro">Ringan</th>
              <th class="th-ro">Tidak Tahu</th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in input.riwayatAlergi" :key="index">
            <tr>
              <td class="td-ro">
                <div class="column">
                  <VDatePicker v-model="item.tglRiwayatAlergi" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks
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
              </td>
              <td class="td-ro">
                <div class="column">
                  <VField>
                    <VControl>
                      <VTextarea v-model="item.obatMenimbulkanAlergi" rows="2"
                        placeholder="Obat yang menimbulkan alergi...'">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-ro">
                <VField class="p-3" style="text-align: center;">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.berat" true-value="Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </td>
              <td class="td-ro">
                <VField class="p-3" style="text-align: center;">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.sedang" true-value="Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </td>
              <td class="td-ro">
                <VField class="p-3" style="text-align: center;">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.ringan" true-value="Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </td>
              <td class="td-ro">
                <VField class="p-3" style="text-align: center;">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.tidakTahu" true-value="Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </td>
              <td class="td-ro">
                <div class="column">
                  <VField>
                    <VControl>
                      <VTextarea v-model="item.bentukReaksiAlergi" rows="2" placeholder="Bentuk Reaksi Alergi...'">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-ro">
                <div class="column" style="text-align: center;">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                    v-tooltip.bubble="'Tambah '">
                  </VIconButton>
                  <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                    @click="removeItem(index)" color="danger">
                  </VIconButton>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="column pb-0">
        <div class="column" style="text-align: center;">
          <h1 class="title-ro">B. DAFTAR OBAT YANG DIBAWA DARI RUMAH</h1>
          <span class="label-ro">(Termasuk Obat Rutin,vitamin,herbal, dan lain-lain)</span>
        </div>
      </div>
      <div class="column" style="overflow: auto;">
        <table class="table-ro">
          <thead>
            <tr>
              <th class="th-ro" rowspan="2">No</th>
              <th class="th-ro" rowspan="2">Obat-Obatan</th>
              <th class="th-ro" rowspan="2" width="10%">Dosis</th>
              <th class="th-ro" rowspan="2">Waktu Pemberian</th>
              <th class="th-ro" rowspan="2">Dosis Terkahir (Tgl/Jam)</th>
              <th class="th-ro" colspan="2">Dilanjutkan Saat Rawat Inap (✓)</th>
              <th class="th-ro" colspan="2">Dilanjutkan Saat Pulang (✓)</th>
              <th class="th-ro" rowspan="2" width="8%">#</th>
            </tr>
            <tr>
              <th class="th-ro">Ya</th>
              <th class="th-ro">Tidak</th>
              <th class="th-ro">Ya</th>
              <th class="th-ro">Tidak</th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in input.daftarObat" :key="index">
            <tr>
              <td class="td-ro">{{ item.no }}</td>
              <td class="td-ro">
                <div class="column">
                  <VField>
                    <VControl>
                      <VTextarea v-model="item.obatDirumah" rows="2" placeholder="Obat-Obatan...'">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-ro">
                <div class="column">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="item.dosis" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-ro">
                <div class="column">
                  <VField>
                    <VControl>
                      <VTextarea v-model="item.waktuPemberian" rows="2" placeholder="Waktu Pemeberian...'">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-ro">
                <div class="column">
                  <VDatePicker v-model="item.dosisTerakhir" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks
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
              </td>
              <td class="td-ro">
                <VField class="p-3" style="text-align: center;">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.dilanjutRawatInap" true-value="Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </td>
              <td class="td-ro">
                <VField class="p-3" style="text-align: center;">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.dilanjutRawatInap" true-value="Tidak" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </td>
              <td class="td-ro">
                <VField class="p-3" style="text-align: center;">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.dilanjutSaatPulang" true-value="Ya" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </td>
              <td class="td-ro">
                <VField class="p-3" style="text-align: center;">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.dilanjutSaatPulang" true-value="Tidak" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </td>
              <td class="td-ro">
                <div class="column" style="text-align: center;">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemDaftarObat()"
                    color="info" v-tooltip.bubble="'Tambah '">
                  </VIconButton>
                  <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                    @click="removeItemDaftarObat(index)" color="danger">
                  </VIconButton>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="item.alergi" true-value="TTA" class="p-0" label="Tidak tahu ada alergi"
                  color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="item.alergi" true-value="TAA" class="p-0" label="Tidak ada alergi" color="primary"
                  square />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-5 p-0">
          <span class="label-ro">Catatan Petugas</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="item.catatanPetugas" rows="2" placeholder="Catatan Petugas...'">
              </VTextarea>
            </VControl>
          </VField>

        </div>
      </div>

      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <span class="label-ro">Dokter</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Dokter..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-4 pb-0">
            <span class="label-ro">Tanggal</span>
            <VDatePicker v-model="input.tglDokter" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks
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
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <span class="label-ro">Perawat</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Perawat..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-4 pb-0">
            <span class="label-ro">Tanggal</span>
            <VDatePicker v-model="input.tglPerawat" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks
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
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <span class="label-ro">Farmasi</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.farmasi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Farmasi..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-4 pb-0">
            <span class="label-ro">Tanggal</span>
            <VDatePicker v-model="input.tglFarmasi" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks
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
      </div>

    </VCard>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="column is-12">
        <div class="column p-0">
          <h1 class="title-ro">Pasien/Keluarga telah dijelaskan tentang obat yang digunakan sekarang</h1>
        </div>
        <div class="column p-0 mt-3">
          <span class="label-ro">(jika pasien tidak mampu menerima informasi, maka penerima informasi adalah keluarga
            terdekat)</span>
        </div>
      </div>
      <div class="column is-6">
        <span class="label-ro">Nama</span>
        <VField class="pt-3">
          <VControl>
            <VInput type="text" v-model="input.penerimaInformasi" />
          </VControl>
        </VField>
      </div>

      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-6">
            <span class="label-ro">Bila Keluarga menolak menyerahkan, alasan penolakan</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.alasanPenolakan" rows="2" placeholder="Alasan Penolakan...'">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <span class="label-ro">Nama</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.penolak" />
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
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
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
  riwayatAlergi: [{
    no: 1,
  }],
  daftarObat: [{
    no: 1,
  }],
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

const addNewItem = () => {
  input.value.riwayatAlergi.push({
    no: input.value.riwayatAlergi[input.value.riwayatAlergi.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.riwayatAlergi.splice(index, 1)
}

const addNewItemDaftarObat = () => {
  input.value.daftarObat.push({
    no: input.value.daftarObat[input.value.daftarObat.length - 1].no + 1,
  });
}
const removeItemDaftarObat = (index: any) => {
  input.value.daftarObat.splice(index, 1)
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

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


setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.table-ro {
  width: 100%;
  border: 1px solid;
}

.th-ro,
.td-ro {
  border: 1px solid;
  padding: 7px;
}

.th-ro {
  text-align: center !important;
  vertical-align: inherit;
}

.td-ro {
  vertical-align: inherit;
}

.label-ro {
  font-weight: 500;
}

.title-ro {
  font-weight: bold;
}
</style>
