<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Formulir Triage Pasien</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton
                icon="lnir lnir-arrow-left rem-100"
                light
                dark-outlined
                @click="kembaliKeun()"
              >
                Kembali
              </VButton>

              <VButton
                type="button"
                rounded
                outlined
                color="primary"
                raised
                icon="feather:save"
                :loading="isLoading || isLoadingPasien"
                @click="simpan()"
              >
                Simpan
              </VButton>
            </div>
          </div>
        </div>
      </div>
      <div class="column">
        <VCard>
          <div class="column is-12">
            <div class="search-widget">
              <div class="field">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">No. RM:</h1>
                    <VField>
                      <VControl icon="fas fa-bookmark">
                        <input
                          v-model="input.qnocm"
                          type="text"
                          class="input"
                          :disabled="NOCM ? true : false"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Nama Pasien:</h1>
                    <VField>
     
                      <VControl icon="fas fa-bookmark">
                        <input v-model="input.qnama" type="text" class="input" disabled />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Tanggal Lahir:</h1>
                    <VField>
                     
                      <VControl icon="fas fa-bookmark">
                        <input v-model="input.tgllahir" type="text" class="input" disabled/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Telepon:</h1>
                    <VField>
                      <VControl icon="fas fa-bookmark">
                        <input v-model="input.qtelepon" type="text" class="input" disabled/>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Jenis Kelamin:</h1>
                    <VField>
                      <VControl icon="fas fa-bookmark">
                        <input v-model="input.jeniskelamin" type="text" class="input" disabled />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-8">
                    <h1 style="font-weight: bold">Alamat:</h1>
                    <VField>
                      <VControl icon="fas fa-bookmark">
                        <input
                          v-model="input.qalamat"
                          type="text"
                          class="input"
                          disabled
                        />
                      </VControl>
                    </VField>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>

          <Divider />

          <div class="columns is-12">
            <VCard class="border-card pink">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <h1 style="font-weight: bold">Cara Datang :</h1>
                  <div class="columns is-mulitiline">
                    <div class="column is-3" v-for="data in CaraDatang">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox
                            v-model="input[data.model]"
                            :true-value="data.title"
                            :label="data.title"
                            color="primary"
                            square
                          />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-5" style="margin-left: -10rem">
                  <h1 style="font-weight: bold">Rujukan Konfirmasi SPGDT :</h1>
                  <div class="columns is-mulitiline">
                    <div class="column is-2" v-for="data in Pilihan">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox
                            v-model="input[data.model]"
                            :true-value="data.title"
                            :label="data.title"
                            color="primary"
                            square
                          />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>

                <div class="column is-3">
                  <h1 style="font-weight: bold" class="pb-3">Asal Rujukan :</h1>
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        v-model="input.asalRujukan"
                        placeholder="Asal Rujukan"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
              <VControl class="prime-auto">
                <VField>
                  <VLabel>Tanggal & Jam Masuk </VLabel>
                  <Calendar
                    v-model="input.waktuPemeriksaan"
                    selectionMode="single"
                    :manualInput="false"
                    class="w-100"
                    :showIcon="true"
                    showTime
                    hourFormat="24"
                    :date-format="H.dateTimeFormat().prime.dateTime"
                  />
                </VField>
              </VControl>
            </div>
                <div class="column is-3">
                  <h1 style="font-weight: bold" class="pb-3">Pengantar Pasien :</h1>
                  <VField>
                    <VControl>
                      <VInput
                        type="text"
                        v-model="input.pengantarPasien"
                        placeholder="Pengantar Pasien"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold">Kesadaran :</h1>
                  <div class="columns is-mulitiline">
                    <div class="column is-2" v-for="data in KesadaranPasien">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox
                            v-model="input[data.model]"
                            :true-value="data.title"
                            :label="data.title"
                            color="primary"
                            square
                          />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-8 mt-0">
                  <VField>
                    <h1 style="font-weight: bold" class="pb-3">
                      Keluhan Utama/Tanda/Diskriminator :
                    </h1>
                    <VControl>
                      <VTextarea
                        class="textarea"
                        v-model="input.keluhan"
                        rows="2"
                        placeholder="Keterangan Tata Laksana"
                        autocomplete="off"
                        autocapitalize="off"
                        spellcheck="true"
                      />
                    </VControl>
                  </VField>
                </div>
              </div>
              <Divider />

              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1" style="text-align: center">
                  Pemeriksaan
                </h3>

                <div class="columns is-multiline">
                  <div class="column is-4">
                    <h1 style="font-weight: bold">Jalan Nafas :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-4" v-for="data in JalanNafas">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
            
                  <div class="column is-8">
                    <h1 style="font-weight: bold">Pernapasan :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-2" v-for="data in Pernapasan">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-4">
                    <h1 style="font-weight: bold">Regularitas Nadi :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-4" v-for="data in Regularitas">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-4">
                    <h1 style="font-weight: bold">Akral Dingin :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-4" v-for="data in akralDingin">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12">
                    <h1 style="font-weight: bold">Kriteria Isolasi :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column" v-for="data in KriteriaIsolasi" :class="data.title == 'Riwayat Bepergian dari daerah Endimik' ? 'is-3' : 'is-2'">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-6">
                    <h1 style="font-weight: bold">Riwayat Alergi :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-3" v-for="data in riwayatAlergi">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-3">
                        <VField>
                          <VControl>
                            <VInput
                              type="text"
                              v-model="input.keteranganAlergi"
                              placeholder="Keterangan"
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-8">
                    <h1 style="font-weight: bold">Skala Nyeri :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-2" v-for="data in skalaNyeri" :class="data.title == '1-3 = Nyeri Ringan' || '4-6 = Nyeri Sedang' ? 'is-3' : 'is-2'">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-4">
                    <h1 style="font-weight: bold">Jenis Nyeri :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-4" v-for="data in JenisNyeri">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold" class="pb-3">Lokasi :</h1>
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          v-model="input.lokasiNyeri"
                          placeholder="Lokasi"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold" class="pb-3">Frekuensi :</h1>
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          v-model="input.frekuensi"
                          placeholder="Frekuensi"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold" class="pb-3">Karakteristik :</h1>
                    <VField>
                      <VControl>
                        <VInput
                          type="text"
                          v-model="input.karakteristik"
                          placeholder="Karakteristik"
                        />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold" class="pb-3">Durasi :</h1>
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="input.durasi" placeholder="Durasi" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VCardHead title="Anatomi Tubuh" class="border-card yellow">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <div
                            :style="'background-image:url(' + MARKINGSITE + ')'"
                            style="
                              text-align: center;

                              z-index: 9999;
                              background-repeat: no-repeat;
                              background-position: center;
                              width: 200px;
                              height: 600px;
                            "
                          >
                            <canvas id="markingsite" height="400" width="200"></canvas>
                          </div>
                          <VButton
                            type="button"
                            rounded
                            outlined
                            color="danger"
                            raised
                            icon="feather:trash"
                            @click="clearCanvas('markingsite')"
                          >
                            Clear
                          </VButton>
                        </div>
                        <div class="column is-4" style="margin-left: -10rem">
                          <h1 style="font-weight: bold" class="pb-3">
                            Keterangan Anatomi Tubuh :
                          </h1>
                          <VField>
                            <VControl>
                              <VTextarea
                                class="textarea"
                                v-model="input.keteranganAnatomiTubuh"
                                rows="5"
                                placeholder="Keterangan Anatomi Tubuh"
                                autocomplete="off"
                                autocapitalize="off"
                                spellcheck="true"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </VCardHead>
                  </div>
                  <div class="column is-8">
                    <h1 style="font-weight: bold">Status Psikologis :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-2" v-for="data in statusPsikolog">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-6">
                    <h1 style="font-weight: bold">Risiko Jatuh :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-3" v-for="data in resikoJatuh">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-6">
                    <h1 style="font-weight: bold">Kategori Triase :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-3" v-for="data in kategoriTriase">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold">Butuh Isolasi :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-3" v-for="data in butuhIsolasi">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold">Butuh Dekontaminasi :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-3" v-for="data in butuhDekontaminasi">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-3">
                    <h1 style="font-weight: bold">Kasus PKT :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-3" v-for="data in pkt">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-8">
                    <h1 style="font-weight: bold">Disposisi Pasien :</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-2" v-for="data in disposisiPasien">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.title"
                              :label="data.title"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-6 mt-0">
                    <h1 style="font-weight: bold">Dokter Jaga :</h1>
                    <VField class="is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete
                          v-model="input.dokterJaga"
                          :suggestions="d_Dokter"
                          @complete="fetchDokter($event)"
                          :optionLabel="'label'"
                          :dropdown="true"
                          :minLength="3"
                          :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'"
                          :field="'label'"
                          placeholder="ketik nama Dokter"
                        />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </VCard>
          </div>
        </VCard>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog'
import AutoComplete from 'primevue/autocomplete'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import moment from 'moment'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Badge from 'primevue/badge'
import Tag from 'primevue/tag'
import Divider from 'primevue/divider'
import sleep from '/@src/utils/sleep'
import Calendar from 'primevue/calendar'
import Checkbox from 'primevue/checkbox'
import RadioButton from 'primevue/radiobutton'
import InputSwitch from 'primevue/inputswitch'
import InputNumber from 'primevue/inputnumber'
import SelectButton from 'primevue/selectbutton'
import Fieldset from 'primevue/fieldset'
import $ from 'jquery'
import * as EMR from '../emr/profile-pasien/page-emr-plugins/input-triage'

let ALAMAT: any = useRoute().query.alamat as string
let NOCM: any = useRoute().query.nocm as string
let namaPasien: any = useRoute().query.namapasien as string
let tglLahir: any = useRoute().query.tgllahir as string
let jenisKelamin: any = useRoute().query.jeniskelamin as string
let tglMasuk: any = useRoute().query.tglemr as string
let Keluhan: any = useRoute().query.keluhan as string
let telepon: any = useRoute().query.notelepon as string
let NOEMR: any = useRoute().query.noemr as string


const MARKINGSITE: any = ref('')
const setView = () => {
  useHead({
    title: 'Input Triage Pasien - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)

  //   jenisKelamin.value = props.pasien.jeniskelamin.toUpperCase()
  MARKINGSITE.value = '/images/simrs/cowok.png'
}

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
const COLLECTION: any = ref('Triage')
const route = useRoute()
const pasien: any = ref({})
const d_Dokter: any = ref([])
const NOREC_EMRPASIEN: any = ref('')
const loadData: any = ref(true)
const isLoadingPasien: any = ref(false)

const item: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  qnama: namaPasien,
})
const input: any = ref({
  waktuPemeriksaan: new Date(),
  qalamat: ALAMAT,
  qnocm: NOCM ? NOCM : '',
  qnama: namaPasien,
  qtelepon: telepon,
  tglEMR: tglMasuk,
  keluhan : Keluhan,
  tgllahir: tglLahir,
  jeniskelamin : jenisKelamin,
  details: [
    {
      no: 1,
      tgl: new Date(),
    },
  ],
})

const listColor: any = ref(Object.keys(useThemeColors()))

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const options = ref(['LAKI-LAKI', 'PEREMPUAN'])

let CaraDatang = ref(EMR.CaraDatang())
let Pilihan = ref(EMR.Pilihan())
let KesadaranPasien = ref(EMR.KesadaranPasien())
let JalanNafas = ref(EMR.JalanNafas())
let Pernapasan = ref(EMR.Pernapasan())
let frekuensiNadi = ref(EMR.frekuensiNadi())
let Regularitas = ref(EMR.Regularitas())
let akralDingin = ref(EMR.akralDingin())
let KriteriaIsolasi = ref(EMR.KriteriaIsolasi())
let riwayatAlergi = ref(EMR.riwayatAlergi())
let skalaNyeri = ref(EMR.skalaNyeri())
let JenisNyeri = ref(EMR.JenisNyeri())
let statusPsikolog = ref(EMR.statusPsikolog())
let resikoJatuh = ref(EMR.resikoJatuh())
let kategoriTriase = ref(EMR.kategoriTriase())
let butuhIsolasi = ref(EMR.butuhIsolasi())
let butuhDekontaminasi = ref(EMR.butuhDekontaminasi())
let pkt = ref(EMR.pkt())
let disposisiPasien = ref(EMR.disposisiPasien())

const currentPage: any = ref({
  limit: 20,
  rows: 50,
})

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch {}
  return 1
})

const isMozilla = () => {
  return navigator.userAgent.indexOf('Firefox') !== -1
}

const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}

const loadRiwayat = async () => {
  let response = await useApi().get(
    `/emr/get-emr-igd?qnama=${namaPasien}&collection=${COLLECTION.value}&noemr=${NOEMR}`
  )
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    if (response[0].anatomiTubuh) {
      let sigCanvas: any = document.getElementById('markingsite')
      if (sigCanvas) {
        let context = sigCanvas.getContext('2d')
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height)
        let imagess = response[0].anatomiTubuh
        let background = new Image()
        background.src = imagess
        background.onload = function () {
          context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height)
        }
      }
    }
  }
  loadData.value = false
}

const simpan = () => {
  let object: any = {}
  let ID = input.value.id ? input.value.id : ''
  
  let sigCanvas = document.getElementById('markingsite')
  if (sigCanvas) {
    let context = sigCanvas.getContext('2d')
    const dataURL = sigCanvas.toDataURL()
    input.value.anatomiTubuh = dataURL
    object.anatomiTubuh = dataURL
  }

  object = input.value
  object.nocm = input.value.qnocm ?  input.value.qnocm : ''
  object.namapasien = input.value.qnama
  object.tgllahir = input.value.tgllahir
  object.alamat = input.value.qalamat ?  input.value.qalamat : ''
  object.notelepon = input.value.qtelepon
  object.jeniskelamin = input.value.jeniskelamin
  // object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)

  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  
  console.log(json)
  debugger
  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr-igd`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}

const getPosition = (mouseEvent: any, sigCanvas: any) => {
  let rect = sigCanvas.getBoundingClientRect()
  return {
    X: mouseEvent.clientX - rect.left,
    Y: mouseEvent.clientY - rect.top,
  }
}
const markignSite = () => {
  let sigCanvas: any = document.getElementById('markingsite')
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  let context = sigCanvas.getContext('2d')
  context.strokeStyle = 'red'
  context.lineJoin = 'round'
  context.lineWidth = 2
  let is_touch_device = 'ontouchstart' in document.documentElement

  if (is_touch_device) {
    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath()
        context.moveTo(coors.x, coors.y)
        this.isDrawing = true
      },
      touchmove: function (coors: any) {
        if (this.isDrawing) {
          context.lineTo(coors.x, coors.y)
          context.stroke()
        }
      },
      touchend: function (coors: any) {
        if (this.isDrawing) {
          this.touchmove(coors)
          this.isDrawing = false
        }
      },
    }

    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY,
      }

      let obj = sigCanvas

      if (obj.offsetParent) {
        do {
          coors.x -= obj.offsetLeft
          coors.y -= obj.offsetTop
        } while ((obj = obj.offsetParent) != null)
      }

      drawer[event.type](coors)
    }

    sigCanvas.addEventListener('touchstart', draw, false)
    sigCanvas.addEventListener('touchmove', draw, false)
    sigCanvas.addEventListener('touchend', draw, false)

    sigCanvas.addEventListener(
      'touchmove',
      function (event: any) {
        event.preventDefault()
      },
      false
    )
  } else {
    $('#markingsite').mousedown(function (mouseEvent: any) {
      let position = getPosition(mouseEvent, sigCanvas)
      context.moveTo(position.X, position.Y)
      context.beginPath()
      $(this)
        .mousemove(function (mouseEvent: any) {
          drawLine(mouseEvent, sigCanvas, context)
        })
        .mouseup(function (mouseEvent: any) {
          finishDrawing(mouseEvent, sigCanvas, context)
        })
        .mouseout(function (mouseEvent: any) {
          finishDrawing(mouseEvent, sigCanvas, context)
        })
    })
  }
}
const drawLine = (mouseEvent: any, sigCanvas: any, context: any) => {
  let position = getPosition(mouseEvent, sigCanvas)

  context.lineTo(position.X, position.Y)
  context.stroke()
}
const finishDrawing = (mouseEvent: any, sigCanvas: any, context: any) => {
  drawLine(mouseEvent, sigCanvas, context)

  context.closePath()
  $(sigCanvas).unbind('mousemove').unbind('mouseup').unbind('mouseout')
}

const clearCanvas = (canvas: any) => {
  var sigCanvas: any = document.getElementById(canvas)
  var context = sigCanvas.getContext('2d')
  context.clearRect(0, 0, sigCanvas.width, sigCanvas.height)
}
onMounted( async () => {
        await sleep(1000)
        markignSite()
      }
)
setView()
loadRiwayat()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/timeline-css';

.fs-075 {
  font-size: 0.9rem;
}

.is-navbar {
  .form-layout {
    margin-top: 30px;
  }
}

.form-layout {
  // max-width: 740px;
  margin: 0 auto;

  &.is-separate {
    // max-width: 1040px;

    .form-outer {
      background: none;
      border: none;

      .form-body {
        display: flex;

        .form-section {
          flex-grow: 2;
          padding: 10px;
          width: 50%;

          .form-section-inner {
            @include vuero-s-card;

            padding: 40px;

            &.has-padding-bottom {
              padding-bottom: 60px;
              height: 100%;
            }

            > h3 {
              font-family: var(--font-alt);
              font-size: 1.2rem;
              font-weight: 600;
              color: var(--dark-text);
              margin-bottom: 30px;
            }

            .columns {
              .column {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
              }
            }

            .radio-boxes {
              display: flex;
              justify-content: space-between;
              margin-left: -8px;
              margin-right: -8px;

              .radio-box {
                position: relative;
                width: calc(50% - 16px);
                margin: 8px;

                &:focus-within {
                  border-radius: 3px;
                  outline-offset: var(--accessibility-focus-outline-offset);
                  outline-width: var(--accessibility-focus-outline-width);
                  outline-style: var(--accessibility-focus-outline-style);
                  outline-color: var(--primary);
                }

                input {
                  position: absolute;
                  top: 0;
                  left: 0;
                  height: 100%;
                  width: 100%;
                  opacity: 0;
                  cursor: pointer;

                  &:checked {
                    + .radio-box-inner {
                      background: var(--primary);
                      border-color: var(--primary);
                      box-shadow: var(--primary-box-shadow);

                      .fee,
                      p {
                        color: var(--smoke-white);
                      }
                    }
                  }
                }

                .radio-box-inner {
                  background: var(--white);
                  border: 1px solid var(--fade-grey-dark-3);
                  text-align: center;
                  border-radius: var(--radius);
                  font-family: var(--font);
                  font-weight: 600;
                  font-size: 0.9rem;
                  transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                    height 0.3s, width 0.3s;
                  padding: 30px 20px;

                  .fee {
                    font-family: var(--font);
                    font-weight: 700;
                    color: var(--dark-text);
                    font-size: 2.4rem;
                    line-height: 1;

                    span {
                      &::after {
                        content: '$';
                        position: relative;
                        top: -10px;
                        font-size: 1.5rem;
                      }
                    }
                  }

                  p {
                    font-family: var(--font-alt);
                  }
                }
              }
            }

            .control {
              > p {
                padding-top: 12px;

                > span {
                  display: block;
                  font-size: 0.9rem;

                  span {
                    font-weight: 500;
                    color: var(--dark-text);
                  }
                }
              }
            }
          }

          .form-section-outer {
            .checkboxes {
              padding: 16px 0;

              .checkbox {
                padding: 0;
                font-size: 0.9rem;
              }
            }

            .button-wrap {
              .button {
                min-height: 60px;
                font-size: 1.05rem;
                font-weight: 600;
                font-family: var(--font-alt);
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .form-layout {
    &.is-separate {
      .form-outer {
        background: none !important;

        .form-body {
          .form-section {
            .form-section-inner {
              @include vuero-card--dark;

              > h3 {
                color: var(--dark-dark-text);
              }

              .radio-boxes {
                .radio-box {
                  input:checked + .radio-box-inner {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    .fee,
                    p {
                      color: var(--smoke-white);
                    }
                  }

                  .radio-box-inner {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);

                    .fee {
                      color: var(--dark-dark-text);
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;
          flex-direction: column;

          .form-section {
            width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;

          // flex-direction: column;

          .form-section {
            // width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

.all-projects {
  .all-projects-header {
    display: flex;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    margin-bottom: 1.5rem;

    .header-item {
      width: 25%;
      border-right: 1px solid var(--fade-grey-dark-3);

      &:last-child {
        border-right: none;
      }

      .item-inner {
        text-align: center;

        .lnil,
        .lnir {
          font-size: 2.2rem;
          margin-bottom: 6px;
          color: var(--primary);
        }

        span {
          display: block;
          font-family: var(--font);
          font-weight: 600;
          font-size: 1.4rem;
          color: var(--dark-text);
        }

        p {
          font-family: var(--font-alt);
        }
      }
    }
  }

  .projects-card-grid {
    .grid-item {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 220px;
      padding: 20px;
      background: var(--white);
      border: 1px solid var(--fade-grey-dark-3);
      border-radius: var(--radius-large);

      .top-section {
        .head {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 8px;

          h3 {
            font-size: 1rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
          }
        }

        .body {
          p {
            font-family: var(--font);
            color: var(--light-text);
          }
        }
      }

      .bottom-section {
        display: flex;

        .foot-block {
          margin-right: 30px;

          .heading {
            font-family: var(--font-alt);
            font-size: 0.75rem;
            color: var(--light-text-dark-22);
          }

          > p {
            padding-top: 5px;
          }

          .developers {
            display: flex;

            .v-avatar {
              margin-right: 6px;
            }
          }
        }
      }
    }
  }
}

.heading {
  font-family: var(--font-alt);
  font-size: 0.75rem;
  color: var(--light-text-dark-22);
}

.is-dark {
  .all-projects {
    .all-projects-header {
      background: var(--dark-sidebar-light-6);
      border-color: var(--dark-sidebar-light-12);

      .header-item {
        border-color: var(--dark-sidebar-light-18);

        span {
          color: var(--dark-dark-text);
        }

        i {
          color: var(--primary) !important;
        }
      }
    }

    .projects-card-grid {
      .grid-item {
        background: var(--dark-sidebar-light-6);
        border-color: var(--dark-sidebar-light-12);

        .top-section {
          .head {
            h3 {
              color: var(--dark-dark-text);
            }
          }
        }

        .bottom-section {
          .foot-block {
            .heading {
              color: var(--light-text-dark-12);
            }
          }
        }
      }
    }
  }
}

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  max-width: 30% !important;
}
</style>
