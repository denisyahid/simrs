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
      <div class="columns is-multiline">
        <div class="column is-12">
          <h1 style="font-weight: bold;" class="text-center mb-5">SURAT PERINTAH RAWAT INAP</h1>
        </div>
        <div class="column is-12">
          <h1 style="font-weight: 600;">Mohon dirawat seorang penderita :
          </h1>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nama Pasien: </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Nama Pasien... " v-model="input.namaPasien" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> No.RM : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No.RM... " v-model="input.norekammedis" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Tanggal Mulai Dirawat : </span>
              </div>
              <div class="column is-6">
                <VField class="mt-2">
                  <VDatePicker v-model="input.tglDirawat" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Jenis Kelamin : </span>
              </div>
              <div class="column is-6">
                <VControl>
                  <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                    <div class="column is-12" v-if="d_JK.length == 0">
                      <VPlaceloadText :lines="1" />
                    </div>
                    <div class="column is-4 mt-2 p-0" v-for="items in d_JK" :key="items.id">
                      <VRadio v-model="input.jeniskelamin" :value="items.label" class="p-0 mb-3" :label="items.label"
                        square color="primary" />
                    </div>
                  </div>
                </VControl>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Pekerjaan : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Pekerjaan... " v-model="input.pekerjaan" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Indikasi Rawat : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VTextarea rows="2" placeholder="Indikasi Rawat..." v-model="input.lokasi"></VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Ruang Perawatan : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="column mt-3 is-4">
              <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
              <VField>
                <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-4">
                  <h1 class="p-0" style="font-weight: bold;">Dokter </h1>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.dokterRSD" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Yang Menjelaskan..." class="mt-2"
                        @item-select="setTandaTangan($event, 'signature_1')" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <Fieldset :toggleable="true" legend="Konfirmasi Petugas Penerimaan Pasien Rawat Inap (ADMISSION)">
          <div class="columns is-multiline mt-2">
            <div class="column is-12 px-4">
              <div class="column is-12 mt-3">
                <div class="is-flex">
                  <div class="column is-4 mt-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.test" true-value="false" label="Ruang Rawat Penuh Untuk" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-8">
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.ruanganPenuh" :suggestions="d_Ruangan"
                          @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Ketik unutk mencari..." class="mt-2"
                          @item-select="setTandaTangan($event, 'signature_1')" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="is-flex">
                  <div class="column is-4 mt-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.test" true-value="true" label="Pasien bisa dirawat diruang rawat"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <h1 style="font-weight: 600;">Pasien Sudah Bisa Dirawat :</h1>
              </div>
              <div class="column is-12 py-0 px-4">
                <div class="columns is-multiline mt-3">
                  <div class="column is-6">
                    <VField label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:home" fullwidth>
                        <Multiselect mode="single" v-model="input.ruangan" :options="d_RuanganRI" placeholder="Pilih data"
                          :searchable="true" :attrs="{ id }" autocomplete="off" @select="changeRuang(input.ruangan)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Kelas" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:layers" fullwidth>
                        <Multiselect mode="single" v-model="input.kelas" :options="d_Kelas" placeholder="Pilih data"
                          :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT"
                          @select="changeKelas(input.kelas, input.ruangan)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Kamar" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="fas fa-hospital-alt">
                        <Multiselect mode="single" v-model="input.kamar" :options="d_Kamar" placeholder="Pilih data"
                          :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 p-0">
                  <div class="column mt-3 is-4">
                    <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
                    <VField>
                      <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <div class="column is-12 px-4">
                    <div class="columns is-multiline">
                      <div class="column is-6">
                        <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                      </div>
                    </div>
                    <div class="columns is-multiline">
                      <div class="column is-4">
                        <h1 class="p-0" style="font-weight: bold;">Petugas TPP </h1>
                        <VField>
                          <VControl class="prime-auto">
                            <AutoComplete v-model="input.petugasRanap" :suggestions="d_Pegawai"
                              @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Ketik untuk melihat..." class="mt-2"
                              @item-select="setTandaTangan($event, 'signature_2')" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset :toggleable="true" legend="Konfirmasi Petugas Penerimaan Pasien Rawat Inap (ADMISSION)">
          <div class="columns is-multiline mt-2">
            <div class="column is-12 px-4">
              <div class="column is-12 mt-3">
                <div class="columns is-multiline mt-2">
                  <div class="column" v-for="(data,index) in checked">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <hr>
                  </div>
                  <div class="column is-12">
                    <h1 style="font-weight: 600;">Pasien Sudah Bisa Dirawat :</h1>
                  </div>
                  <div class="column is-12 py-0 px-4">
                    <div class="columns is-multiline">
                      <div class="column is-6">
                        <VField label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:home" fullwidth>
                            <Multiselect mode="single" v-model="input.dokterRuangan" :options="d_RuanganRI"
                              placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                              @select="changeRuangDokter(input.dokterRuangan)" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <VField label="Kelas" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:layers" fullwidth>
                            <Multiselect mode="single" v-model="input.dokterKelas" :options="d_KelasDokter"
                              placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                              :loading="isLoadingTT" @select="changeKelasDokter(input.dokterKelas, input.dokterRuangan)" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <VField label="Kamar" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="fas fa-hospital-alt">
                            <Multiselect mode="single" v-model="input.dokterKamar" :options="d_KamarDokter"
                              placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                              :loading="isLoadingTT" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 p-0">
                    <div class="column mt-3 is-4">
                      <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
                      <VField>
                        <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
                          :max-date="new Date()">
                          <template #default="{ inputValue, inputEvents }">
                            <VField>
                              <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                              </VControl>
                            </VField>
                          </template>
                        </VDatePicker>
                      </VField>
                    </div>
                    <div class="column is-12 px-4">
                      <div class="columns is-multiline">
                        <div class="column is-6">
                          <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan>
                        </div>
                        <div class="column is-6 ">
                          <TandaTangan :elemenID="'signature_4'" :width="'180'" :height="'180'"></TandaTangan>
                        </div>
                        <div class="column is-4">
                          <h1 class="p-0" style="font-weight: bold;">Dokter </h1>
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.dokterRanap" :suggestions="d_Dokter"
                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Yang Menjelaskan..." class="mt-2"
                                @item-select="setTandaTangan($event, 'signature_1')" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-4 is-offset-2">
                          <h1 class="p-0" style="font-weight: bold;">Petugas TPP </h1>
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.petugasRanap2" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Ketik untuk melihat..." class="mt-2"
                                @item-select="setTandaTangan($event, 'signature_2')" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
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
import * as EMR from '../page-emr-plugins/surat-pengantar-rawat-inap'



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let checked = ref(EMR.checked())

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
const d_Ruangan: any = ref([])
const d_RuanganRI: any = ref([])
const d_Kamar: any = ref([])
const d_Dokter: any = ref([])
const d_JK: any = ref([])
const d_Kelas: any = ref([])
const d_KamarDokter: any = ref([])
const d_KelasDokter: any = ref([])
const isLoadingTT = ref(false);
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
const dataTTD: any = ref([])
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
        dataTTD.value = response[0]
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        // if (response[0].tandaTanganDokterRSD) {
        //   H.tandaTangan().set("signature_1", response[0].tandaTanganDokterRSD)
        // }
        // if (response[0].tandaTanganPetugasTpp) {
        //   H.tandaTangan().set("signature_2", response[0].tandaTanganPetugasTpp)
        // }
        // if (response[0].tandaTanganDokterRSD2) {
        //   H.tandaTangan().set("signature_3", response[0].tandaTanganDokterRSD2)
        // }
        // if (response[0].tandaTanganPetugasTpp2) {
        //   H.tandaTangan().set("signature_4", response[0].tandaTanganPetugasTpp2)
        // }
        H.tandaTangan().set("signature_1", dataTTD.value.tandaTanganDokterRSD)
        H.tandaTangan().set("signature_2", dataTTD.value.tandaTanganPetugasTpp)
        H.tandaTangan().set("signature_3", dataTTD.value.tandaTanganDokterRSD2)
        H.tandaTangan().set("signature_4", dataTTD.value.tandaTanganPetugasTpp2)
        if (response[0].ruangan) {
          changeRuang(response[0].ruangan)
          input.value.kelas = response[0].kelas
        }
        if (response[0].kelas) {
          changeKelas(response[0].kelas, response[0].ruangan)
          input.value.kamar = response[0].kamar
        }
        if (response[0].dokterRuangan) {
          changeRuangDokter(response[0].dokterRuangan)
          input.value.dokterKelas = response[0].dokterKelas
        }
        if (response[0].dokterKelas) {
          changeKelasDokter(response[0].dokterKelas, response[0].dokterRuangan)
          input.value.dokterKamar = response[0].dokterKamar
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
  object.tandaTanganDokterRSD = H.tandaTangan().get("signature_1")
  object.tandaTanganPetugasTpp = H.tandaTangan().get("signature_2")
  object.tandaTanganDokterRSD2 = H.tandaTangan().get("signature_3")
  object.tandaTanganPetugasTpp2 = H.tandaTangan().get("signature_4")
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
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10&settingdatafix=objectdepartemenfk,kdDepartemenRanapFix`)
  d_Ruangan.value = response
}
const fetchRuanganRI = async () => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&settingdatafix=objectdepartemenfk,kdDepartemenRanapFix`)
  d_RuanganRI.value = response
}
const fetchJenisKelamin = async () => {
  const response = await useApi().get(
    `/emr/dropdown/jeniskelamin_m?select=id,jeniskelamin&param_search=&`)
  d_JK.value = response
}
const setTandaTangan = async (e: any, i: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_" + i, element.ttd)
    }
  })
}
const changeKelas = async (e: any, ruangan: any) => {
  d_Kamar.value = []
  input.value.kamar=null;
  isLoadingTT.value = true
  await useApi().get(
    `/registrasi/kamar-by-kelas?id=${e}&idRuangan=${ruangan}&isRG=false`)
    .then((response: any) => {
      isLoadingTT.value = false
      d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
    })
    .catch((error: any) => { isLoadingTT.value = false })
}
const changeKelasDokter = async (e: any, ruangan: any) => {
  d_KamarDokter.value = []
  input.value.dokterKamar =null;
  isLoadingTT.value = true
  await useApi().get(
    `/registrasi/kamar-by-kelas?id=${e}&idRuangan=${ruangan}&isRG=false`)
    .then((response: any) => {
      isLoadingTT.value = false
      d_KamarDokter.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
    })
    .catch((error: any) => { isLoadingTT.value = false })
}
const changeRuang = (e: any) => {
    input.value.kelas =null;
    input.value.kamar =null;
    setKelas(e)
}
const setKelas = (e: any) => {
  isLoadingTT.value = true
  d_Kelas.value = []
  useApi().get(
    `/registrasi/kelas-by-ruangan?id=${e}`)
    .then((response: any) => {
      if (response) {
        d_Kelas.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
      }
      isLoadingTT.value = false
    })
    .catch((error: any) => { isLoadingTT.value = false })
}

const changeRuangDokter = (e: any) => {
  if (e) {
    setKelasDokter(e)
  }
}
const setKelasDokter = (e: any) => {
  isLoadingTT.value = true
  input.value.dokterKamar =null;
  input.value.dokterKelas =null;
  d_KelasDokter.value = []
  useApi().get(
    `/registrasi/kelas-by-ruangan?id=${e}`)
    .then((response: any) => {
      isLoadingTT.value = false
      d_KelasDokter.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
    })
    .catch((error: any) => { isLoadingTT.value = false })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  fetchJenisKelamin()
  await fetchRuanganRI()
  input.value.tglPembuatan = new Date()
  input.value.namaPasien = props.pasien.namapasien
  input.value.norekammedis = props.pasien.nocm
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.tglPembuatan = new Date()
  input.value.tanggalWaktuKejadian = new Date()
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.tglDirawat = props.registrasi.tglregistrasi

}
console.log(JSON.stringify(props.pasien));

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
