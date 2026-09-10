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
    <div class="column">
      <Fieldset :toggleable="true" legend="A. RIWAYAT ALERGI OBAT">
        <div class="column" style="overflow:auto">
          <table class="table-fro">
            <thead>
              <tr>
                <th class="th-fro setFRO-center" rowspan="2" width="18%">Tanggal</th>
                <th class="th-fro setFRO-center" rowspan="2">Obat yang Menimbulkan Alergi</th>
                <th class="th-fro setFRO-center" colspan="4">Keparahan Reaksi Alergi</th>
                <th class="th-fro setFRO-center" rowspan="2">Bentuk Reaksi Alergi</th>
                <th class="th-fro setFRO-center" rowspan="2" width="8%">#</th>
              </tr>
              <tr>
                <th class="th-fro setFRO-center">Berat</th>
                <th class="th-fro setFRO-center">Sedang</th>
                <th class="th-fro setFRO-center">Ringan</th>
                <th class="th-fro setFRO-center">Tidak Tahu</th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.riwayatAlergi" :key="index">
              <tr>
                <td class="td-fro">
                  <VDatePicker v-model="item.tglAlergi" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField class="pb-0 pt-3">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </td>
                <td class="td-fro">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="item.obat" :suggestions="d_Obat" @complete="fetchObat($event)"
                        :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" placeholder="ketik nama obat" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro setFRO-center">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="item.keparahanReaksi" true-value="Berat" class="p-0" color="primary" squere />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro setFRO-center">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="item.keparahanReaksi" true-value="Sedang" class="p-0" color="primary" squere />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro setFRO-center">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="item.keparahanReaksi" true-value="Ringan" class="p-0" color="primary" squere />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro setFRO-center">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="item.keparahanReaksi" true-value="Tidak Tahu" class="p-0" color="primary"
                        squere />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro setFRO-center">
                  <VField>
                    <VControl>
                      <VTextarea v-model="item.bentukAlergi" rows="2">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro setFRO-center">
                  <VButtons style="justify-content:space-around">
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemRiwayat()" color="info"
                      v-tooltip.bubble="'Tambah '">
                    </VIconButton>
                    <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                      @click="removeItemRiwayat(index)" color="danger">
                    </VIconButton>
                  </VButtons>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="columns is-multiline p-3">
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.alergi" true-value="TTA" class="p-0" color="primary"
                  label="Tidak tahu ada alergi" squere />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.alergi" true-value="TAA" class="p-0" color="primary" label="Tidak ada alergi"
                  squere />
              </VControl>
            </VField>
          </div>
        </div>
      </Fieldset>
    </div>

    <div class="column">
      <Fieldset :toggleable="true" legend="B. DAFTAR OBAT YANG DIBAWA DARI RUMAH">
        <div class="column" style="overflow:auto">
          <table class="table-fro">
            <thead>
              <tr>
                <th class="th-fro setFRO-center" rowspan="2" width="3%">No</th>
                <th class="th-fro setFRO-center" rowspan="2" width="18%">Obat-obatan</th>
                <th class="th-fro setFRO-center" rowspan="2">Dosis</th>
                <th class="th-fro setFRO-center" rowspan="2">Waktu Pemberian</th>
                <th class="th-fro setFRO-center" rowspan="2">Dosis Terakhir</th>
                <th class="th-fro setFRO-center" colspan="2">Dilanjutkan Saat Rawat Inap</th>
                <th class="th-fro setFRO-center" colspan="2">Dilanjutkan Saat Pulang</th>
                <th class="th-fro setFRO-center" rowspan="2" width="8%">#</th>
              </tr>
              <tr>
                <th class="th-fro setFRO-center">Ya</th>
                <th class="th-fro setFRO-center">Tidak</th>
                <th class="th-fro setFRO-center">Ya</th>
                <th class="th-fro setFRO-center">Tidak</th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.obatDariRumah" :key="index">
              <tr>
                <td class="td-fro">
                  <span>{{ item.no }}</span>
                </td>
                <td class="td-fro">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="item.obat" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="item.dosis" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="item.waktuPemberian" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro">
                  <VDatePicker v-model="item.dosisTerakhir" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField class="pb-0 pt-3">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </td>
                <td class="td-fro setFRO-center">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="item.dilanjutSaatRI" true-value="Ya" class="p-0" color="primary" squere />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro setFRO-center">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="item.dilanjutSaatRI" true-value="Tidak" class="p-0" color="primary" squere />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro setFRO-center">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="item.dilanjutSaatPulang" true-value="Ya" class="p-0" color="primary" squere />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro setFRO-center">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="item.dilanjutSaatPulang" true-value="Tidak" class="p-0" color="primary"
                        squere />
                    </VControl>
                  </VField>
                </td>
                <td class="td-fro setFRO-center">
                  <VButtons style="justify-content:space-around">
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemObatRumah()"
                      color="info" v-tooltip.bubble="'Tambah '">
                    </VIconButton>
                    <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                      @click="removeItemObatRumah(index)" color="danger">
                    </VIconButton>
                  </VButtons>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="column is-8">
          <span class="label-peso">Catatan Petugas</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="input.catatanPetugas" rows="2">
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </Fieldset>
    </div>
    <div class="column">
      <div class="columns is-multiline pt-5" style="justify-content: space-around;">
        <div class="column is-3 p-0">
          <div class="column pt-0">
            <span>Tanggal/Jam</span>
            <VDatePicker v-model="input.tglDokter" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField class="pb-0 pt-3">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column p-0 mt-3" style="text-align: center;">
            <TandaTangan :elemenID="'signatureDokter'" :width="'180'" :height="'180'" class="dek" />
          </div>
          <div class="column">
            <span style="font-weight: 500;">Dokter</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)" @item-select="setTandaTanganDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Dokter..." />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-3 p-0">
          <div class="column pt-0">
            <span>Tanggal/Jam</span>
            <VDatePicker v-model="input.tglPerawat" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField class="pb-0 pt-3">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column p-0 mt-3" style="text-align: center;">
            <TandaTangan :elemenID="'signaturePerawat'" :width="'180'" :height="'180'" class="dek" />
          </div>
          <div class="column">
            <span style="font-weight: 500;">Perawat</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.petugas" :suggestions="d_Pegawai" @complete="fetchPegawai($event)" @item-select="setTandaTanganPerawat($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Perawat..." />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-3 p-0">
          <div class="column pt-0">
            <span>Tanggal/Jam</span>
            <VDatePicker v-model="input.tglFarmasi" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField class="pb-0 pt-3">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column p-0 mt-3" style="text-align: center;">
            <TandaTangan :elemenID="'signatureFarmasi'" :width="'180'" :height="'180'" class="dek" />
          </div>
          <div class="column">
            <span style="font-weight: 500;">Farmasi</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.farmasi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)" @item-select="setTandaTanganFarmasi($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Petugas..." />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </div>

    <div class="column">
      <VCard>
        <div class="column">
          <span>Pasien/Keluarga telah dijelaskan tentang obat yang digunakan sekarang</span>
          <p>(Jika pasien tidak mampu menerima informasi, maka penerima informasi adalah keluarga terdekat)</p>
        </div>
        <div class="columns is-multiline" style="text-align: -webkit-center;">
          <div class="column">
            <div class="column is-3">
              <TandaTangan :elemenID="'signatureKeluarga'" :width="'180'" :height="'180'" class="dek" />
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VInput type="text" v-model="input.perwakilanKeluarga" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>

    <div class="column">
      <VCard>
        <div class="column">
          <span>Bila keluarga menolak menyerahkan, alasan penolakan</span>
          <VField>
            <VControl>
              <VTextarea v-model="input.alasanMenolak" rows="2">
              </VTextarea>
            </VControl>
          </VField>
        </div>
        <div class="columns is-multiline" style="text-align: -webkit-center;">
          <div class="column">
            <div class="column is-3">
              <TandaTangan :elemenID="'signatureKeluargaMenoloak'" :width="'180'" :height="'180'" class="dek" />
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VInput type="text" v-model="input.perwakilanKeluargaMenolak" />
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
  riwayatAlergi: [
    {
      no: 1,
      tglAlergi: new Date()
    },
  ],
  obatDariRumah: [
    {
      no: 1,
    }
  ],
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
        if (response[0].ttdPerawat) {
          H.tandaTangan().set("signaturePerawat", response[0].ttdPerawat)
        }
        if (response[0].ttdDokter) {
          H.tandaTangan().set("signatureDokter", response[0].ttdDokter)
        }
        if (response[0].ttdFarmasi) {
          H.tandaTangan().set("signatureFarmasi", response[0].ttdFarmasi)
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
  object.ttdPerawat = H.tandaTangan().get("signaturePerawat")
  object.ttdDokter = H.tandaTangan().get("signatureDokter")
  object.ttdFarmasi = H.tandaTangan().get("signatureFarmasi")
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

const fetchObat = async (filter: any) => {

  await useApi().get(
    `emr/get-obat?filter=${filter.query}`
  ).then((response) => {
    d_Obat.value = response
  })
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

const setTandaTanganDokter = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signatureDokter", element.ttd)
      }else{
        H.tandaTangan().set("signatureDokter", '')
      }
    })
}

const setTandaTanganPerawat = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signaturePerawat", element.ttd)
      }else{
        H.tandaTangan().set("signaturePerawat", '')
      }
    })
}

const setTandaTanganFarmasi = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signatureFarmasi", element.ttd)
      }else{
        H.tandaTangan().set("signatureFarmasi", '')
      }
    })
}


const addNewItemRiwayat = () => {
  input.value.riwayatAlergi.push({
    no: input.value.riwayatAlergi[input.value.riwayatAlergi.length - 1].no + 1,
  });
}
const removeItemRiwayat = (index: any) => {
  input.value.riwayatAlergi.splice(index, 1)
}

const addNewItemObatRumah = () => {
  input.value.obatDariRumah.push({
    no: input.value.obatDariRumah[input.value.obatDariRumah.length - 1].no + 1,
  });
}
const removeItemObatRumah = (index: any) => {
  input.value.obatDariRumah.splice(index, 1)
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
