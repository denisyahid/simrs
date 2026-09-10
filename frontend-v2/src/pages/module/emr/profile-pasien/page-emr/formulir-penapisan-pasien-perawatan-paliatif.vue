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
      <div class="column">
        <h1 style="font-weight:bold">1. Penyakit Dasar</h1>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'2'"
                    label="a. Kanker (metastasis / rekuren)"
                    v-model="input.Kankermetastasisrekuren"
                    @change="updateScorePenyakitDasar"
                />
            </VControl>
          </div>
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'2'"
                    label="e. Penyakit jantung berat : CHF,CAD berat, CM (LVEF < 25%)"
                    v-model="input.PenyakitjantungberatCHF"
                    @change="updateScorePenyakitDasar"
                />
            </VControl>
          </div>
          <div class="column is-4" style="margin-top: 10px;text-align: center;">
            <span>Skoring</span><br>
            <span>Skor 2, tiap poin</span><br>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'2'"
                    label="b. PPOK lanjut"
                    v-model="input.PPOKlanjut"
                    @change="updateScorePenyakitDasar"
                />
            </VControl>
          </div>
          <div class="column is-4" style="margin-top: 10px;"></div>
          <div class="column is-4" style="margin-top: 10px;">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.skoring2" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'2'"
                    label="c. Stroke (dengan penurunan fungsional > 50%"
                    v-model="input.Strokedenganpenurunanfungsional"
                    @change="updateScorePenyakitDasar"
                />
            </VControl>
          </div>
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'2'"
                    label="f. HIV / AIDS"
                    v-model="input.HIVAIDS"
                    @change="updateScorePenyakitDasar"
                />
            </VControl>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'2'"
                    label="d. Penyakit ginjal kronis"
                    v-model="input.Penyakitginjalkronis"
                    @change="updateScorePenyakitDasar"
                />
            </VControl>
          </div>
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'2'"
                    label="g. Kelainan kongenital berat"
                    v-model="input.Kelainankongenitalberat"
                    @change="updateScorePenyakitDasar"
                />
            </VControl>
          </div>
        </div>
      </div>
      <div class="column">
        <h1 style="font-weight:bold">2. Penyakit Komorbiditas</h1>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'1'"
                    label="a. Penyakit hati kronis"
                    v-model="input.Penyakithatikronis"
                    @change="updateScorePenyakitKomorbiditas"
                />
            </VControl>
          </div>
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'1'"
                    label="d. Gagal jantung kongestif"
                    v-model="input.Gagaljantungkongestif"
                    @change="updateScorePenyakitKomorbiditas"
                />
            </VControl>
          </div>
          <div class="column is-4" style="margin-top: 10px;text-align: center;">
            <span>Skoring</span><br>
            <span>Skor 1, tiap poin</span><br>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'1'"
                    label="b. Penyakit ginjal moderat"
                    v-model="input.Penyakitginjalmoderat"
                    @change="updateScorePenyakitKomorbiditas"
                />
            </VControl>
          </div>
          <div class="column is-4" style="margin-top: 10px;"></div>
          <div class="column is-4" style="margin-top: 10px;">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.skoring1" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'1'"
                    label="c. PPOK moderat"
                    v-model="input.PPOKmoderat"
                    @change="updateScorePenyakitKomorbiditas"
                />
            </VControl>
          </div>
          <div class="column is-4" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'1'"
                    label="e. Kondisi / komplikasi lain"
                    v-model="input.Kondisikomplikasilain"
                    @change="updateScorePenyakitKomorbiditas"
                />
            </VControl>
          </div>
        </div>
      </div>
      <div class="column">
        <h1 style="font-weight:bold">3. Status Fungsional Pasien</h1>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-8 is-flex ml-5">
            <span>Menggunakan status ECOG (Eastern Cooperative Oncology Group) </span><br>
            <span>Derajat Skala</span>
            </div>
            <div class="column is-4" style="margin-top: 10px;text-align: center;">
              <span>Skor Spesifik Di</span><br>
              <span>Bawah Ini</span>
            </div>
        </div>
        <div class="column is-8 is-flex ml-5">
          <span><b>Derajat Skala</b></span>
          </div>

        <div class="column is-12 is-flex ml-5">
            <table width="100%" border="1">
                <tr>
                    <td width="20%" style="text-align: center;vertical-align: middle;"><b>0</b></td>
                    <td>
                      <div class="column is-12" style="margin-top: 10px;">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                :true-value="'0'"
                                label="Aktif penuh, dapat melakukan kegiatan tanpa hambatan."
                                v-model="input.Aktifpenuh"
                            />
                        </VControl>
                      </div>
                    </td>
                    <td width ="20%" style="text-align: center;">Skor 0</td>
                </tr>
                <tr>
                    <td width="20%" style="text-align: center;vertical-align: middle;"><b>1</b></td>
                    <td>
                      <div class="column is-12" style="margin-top: 10px;">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                :true-value="'0'"
                                label="Terdapat hambatan dalam aktivitas berat tetapi mampu berjalan dan dapat
                                  melakukan pekerjaan ringan, seperti pekerjaan rumah dan kantor yang ringan."
                                v-model="input.Terdapathambatan"
                            />
                        </VControl>
                      </div>
                    </td>
                    <td width ="20%" style="text-align: center;vertical-align: middle;">Skor 0</td>
                </tr>
                <tr>
                    <td width="20%" style="text-align: center;vertical-align: middle;"><b>2</b></td>
                    <td>
                      <div class="column is-12" style="margin-top: 10px;">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                :true-value="'1'"
                                label="Dapat berjalan, dapat mengurus diri sendiri, tetapi tidak dapat melakukan semua
                                  aktivitas pada lebih dari 50% jam bangun"
                                v-model="input.Dapatberjalan"
                            />
                        </VControl>
                      </div>
                    </td>
                    <td width ="20%" style="text-align: center;vertical-align: middle;">Skor 1</td>
                </tr>
                <tr>
                    <td width="20%" style="text-align: center;vertical-align: middle;"><b>3</b></td>
                    <td>
                      <div class="column is-12" style="margin-top: 10px;">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                :true-value="'2'"
                                label="Dapat mengurus diri sendiri secara terbatas, lebih banyak menghabiskan waktunya
                                  di tempat tidur atau kursi roda, lebih dari 50% jam bangun"
                                v-model="input.Dapatmengurusdiri"
                            />
                        </VControl>
                      </div>
                    </td>
                    <td width ="20%" style="text-align: center;vertical-align: middle;">Skor 2</td>
                </tr>
                <tr>
                    <td width="20%" style="text-align: center;vertical-align: middle;"><b>4</b></td>
                    <td>
                      <div class="column is-12" style="margin-top: 10px;">
                        <VControl raw subcontrol>
                            <VCheckbox
                                class="p-0"
                                color="primary"
                                square
                                :true-value="'3'"
                                label="Tidak dapat mengurus diri sendiri, sebagian besar waktu di tempat tidur, kondisi
                                  berat / catat"
                                v-model="input.Tidakdapatmengurusdiri"
                            />
                        </VControl>
                      </div>
                    </td>
                    <td width ="20%" style="text-align: center;vertical-align: middle;">Skor 3</td>
                </tr>
            </table>
          </div>

          <div class="column is-12 is-flex ml-5">
            <div class="column is-9" style="margin-top: 10px;">
              <h1 style="font-weight:bold">4. Kriteria Lain Yang Perlu Dipertimbangkan</h1>
            </div>
            <div class="column is-3" style="margin-top: 10px;text-align: center;">
              <h1 style="font-weight:bold">Skor 1 untuk tiap</h1><br>
              <h1 style="font-weight:bold">kondisi</h1>
            </div>
          </div>

        <div class="column is-12 is-flex ml-5">
          <div class="column is-6" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'1'"
                    label="a. Tidak akan menjalani pengobatan kuratif"
                    v-model="input.pengobatankuratif"
                    @change="updateScoreKriterialain"
                />
            </VControl>
          </div>
          <div class="column is-3" style="margin-top: 10px;"></div>
          <div class="column is-3" style="margin-top: 10px;">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBpengobatankuratif" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-6" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'1'"
                    label="b. Kondisi penyakit berat dan memilih untuk tidak melanjutkan terapi"
                    v-model="input.tidakmelanjutkanterapi"
                    @change="updateScoreKriterialain2"
                />
            </VControl>
          </div>
          <div class="column is-3" style="margin-top: 10px;"></div>
          <div class="column is-3" style="margin-top: 10px;">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBtidakmelanjutkanterapi" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-6" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'1'"
                    label="c. Nyeri tidak teratasi lebih dari 24 jam"
                    v-model="input.Nyeritidakteratasi"
                    @change="updateScoreKriterialain3"
                />
            </VControl>
          </div>
          <div class="column is-3" style="margin-top: 10px;"></div>
          <div class="column is-3" style="margin-top: 10px;">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBNyeritidakteratasi" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
          <div class="column is-6" style="margin-top: 10px;">
            <VControl raw subcontrol>
                <VCheckbox
                    class="p-0"
                    color="primary"
                    square
                    :true-value="'1'"
                    label="d. Memiliki keluhan yang tidak terkontrol (contoh : mual dan muntah)"
                    v-model="input.Memilikikeluhan"
                    @change="updateScoreKriterialain4"
                />
            </VControl>
          </div>
          <div class="column is-3" style="margin-top: 10px;"></div>
          <div class="column is-3" style="margin-top: 10px;">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBMemilikikeluhan" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 is-flex ml-5">
        <div class="column is-6" style="margin-top: 10px;">
          <VControl raw subcontrol>
              <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="'1'"
                  label="e. Memiliki kondisi psikososial dan spiritual yang perlu perhatian"
                  v-model="input.spiritual"
                  @change="updateScoreKriterialain5"
              />
          </VControl>
        </div>
        <div class="column is-3" style="margin-top: 10px;"></div>
        <div class="column is-3" style="margin-top: 10px;">
          <VField>
            <VControl>
              <VInput type="text" class="input" v-model="input.TBspiritual" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="column is-12 is-flex ml-5">
        <div class="column is-6" style="margin-top: 10px;">
          <VControl raw subcontrol>
              <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="'1'"
                  label="f. Sering berkunjung ke Unit Gawat Darurat / dirawat di rumah sakit (> 1X/bulan untuk diagnosis yang sama)"
                  v-model="input.SeringkeIGD"
                  @change="updateScoreKriterialain6"
              />
          </VControl>
        </div>
        <div class="column is-3" style="margin-top: 10px;"></div>
        <div class="column is-3" style="margin-top: 10px;">
          <VField>
            <VControl>
              <VInput type="text" class="input" v-model="input.TBSeringkeIGD" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="column is-12 is-flex ml-5">
        <div class="column is-6" style="margin-top: 10px;">
          <VControl raw subcontrol>
              <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="'1'"
                  label="g. Lebih dari satu kali didiagnosis yang sama dalam 30 hari"
                  v-model="input.didiagnosisyangsama"
                  @change="updateScoreKriterialain7"
              />
          </VControl>
        </div>
        <div class="column is-3" style="margin-top: 10px;"></div>
        <div class="column is-3" style="margin-top: 10px;">
          <VField>
            <VControl>
              <VInput type="text" class="input" v-model="input.TBdidiagnosisyangsama" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="column is-12 is-flex ml-5">
        <div class="column is-6" style="margin-top: 10px;">
          <VControl raw subcontrol>
              <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="'1'"
                  label="h. Memiliki lama perawatan tanpa kemajuan yang bermakna"
                  v-model="input.tanpakemajuan"
                  @change="updateScoreKriterialain8"
              />
          </VControl>
        </div>
        <div class="column is-3" style="margin-top: 10px;"></div>
        <div class="column is-3" style="margin-top: 10px;">
          <VField>
            <VControl>
              <VInput type="text" class="input" v-model="input.TBtanpakemajuan" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="column is-12 is-flex ml-5">
        <div class="column is-6" style="margin-top: 10px;">
          <VControl raw subcontrol>
              <VCheckbox
                  class="p-0"
                  color="primary"
                  square
                  :true-value="'1'"
                  label="i. Lama rawat yang panjang di ICU tanpa kemajuan"
                  v-model="input.ICUtanpakemajuan"
                  @change="updateScoreKriterialain9"
              />
          </VControl>
        </div>
        <div class="column is-3" style="margin-top: 10px;"></div>
        <div class="column is-3" style="margin-top: 10px;">
          <VField>
            <VControl>
              <VInput type="text" class="input" v-model="input.TBICUtanpakemajuan" />
            </VControl>
          </VField>
        </div>
      </div>
      </div>

      <div class="column">
        <h1 style="font-weight:bold">Petunjuk Skoring :</h1>
        <div class="column p-3">
          <p style="text-align:justify">Skor total 0-2 (Tidak perlu intervensi paliatif)</p>
          <p style="text-align:justify" class="pt-4">Skor total = 3 (Observasi)</p>
          <p style="text-align:justify" class="pt-4">Skor total ≥ 4 <b>(Perlu Konsul Paliatif)</b></p>
        </div>
      </div>
      <div class="column">
        <h1 style="font-weight:bold">Mengharapkan bantuan Tim Paliatif dalam hal : (silahkan pilih)</h1>
        <div class="column p-3">
          <p style="text-align:justify">1. Penanganan masalah fisik / bio-psikososial / spiritual (bimbingan rohani).</p>
          <p style="text-align:justify" class="pt-4">2. Kebutuhan memfasilitasi home visit oleh Yayasan Kanker Indonesia (bagi pasien penderita kanker dan wilayah
            tinggal di Garut dan sekitarnya.</p>
          <p style="text-align:justify" class="pt-4">3. Kebutuhan untuk pendampingan, baik bagi pasien maupun keluarga (seperti : berita buruk / keputusan medis).</p>
          <p style="text-align:justify" class="pt-4">4. Kebutuhan untuk rujuk balik atau rujuk bawah, karena sudah tidak ada tindakan kuratif yang memungkinkan untuk
            dilakukan.</p>
          <p style="text-align:justify" class="pt-4">5. Dan lain-lain.</p>
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
const d_Wali: any = ref([])
const dataTTD: any = ref([])
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
  tglPermintaan: new Date(),
  Kankermetastasisrekuren: '',
  PenyakitjantungberatCHF: '',
  PPOKlanjut: '',
  Strokedenganpenurunanfungsional: '',
  HIVAIDS: '',
  Penyakitginjalkronis: '',
  Kelainankongenitalberat: '',
  Penyakithatikronis: '',
  Gagaljantungkongestif: '',
  Penyakitginjalmoderat: '',
  PPOKmoderat: '',
  Kondisikomplikasilain: '',
  skoring1: '0',
  skoring2: '0',
  pengobatankuratif: '',
  TBpengobatankuratif: '0',
  tidakmelanjutkanterapi: '',
  TBtidakmelanjutkanterapi: '0',
  Nyeritidakteratasi: '',
  TBNyeritidakteratasi: '0',
  Memilikikeluhan: '',
  TBMemilikikeluhan: '0',
  spiritual: '',
  TBspiritual: '0',
  SeringkeIGD: '',
  TBSeringkeIGD: '0',
  didiagnosisyangsama: '',
  TBdidiagnosisyangsama: '0',
  tanpakemajuan: '',
  TBtanpakemajuan: '0',
  tanpakemajuan: '',
  TBtanpakemajuan: '0'
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
        dataTTD.value = response[0]
        H.tandaTangan().set('TTDdokter', dataTTD.value.TTDdokter)
        H.tandaTangan().set('TTDpasienKeluarga', dataTTD.value.TTDpasienKeluarga)
        H.tandaTangan().set('TTDSaksi', dataTTD.value.TTDSaksi)
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object['TTDdokter'] = H.tandaTangan().get('TTDdokter')
  object['TTDpasienKeluarga'] = H.tandaTangan().get('TTDpasienKeluarga')
  object['TTDSaksi'] = H.tandaTangan().get('TTDSaksi')
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
  input.value.namaPasien = props.pasien.namapasien
  input.value.jnsKlmPasien = props.pasien.jeniskelamin
  input.value.tglLahirPasien = props.pasien.tgllahir
  input.value.norm = props.pasien.nocm
}

const fetchWali = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Wali.value = response
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
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}

const updateScorePenyakitDasar = () => {
  const score1 = parseInt(input.value.Kankermetastasisrekuren) || 0
  const score2 = parseInt(input.value.PenyakitjantungberatCHF) || 0
  const score3 = parseInt(input.value.PPOKlanjut) || 0
  const score4 = parseInt(input.value.Strokedenganpenurunanfungsional) || 0
  const score5 = parseInt(input.value.HIVAIDS) || 0
  const score6 = parseInt(input.value.Penyakitginjalkronis) || 0
  const score7 = parseInt(input.value.Kelainankongenitalberat) || 0

  input.value.skoring2 = (score1 + score2 + score3 + score4 + score5 + score6 + score7).toString()
}

const updateScorePenyakitKomorbiditas = () => {
  const score1 = parseInt(input.value.Penyakithatikronis) || 0
  const score2 = parseInt(input.value.Gagaljantungkongestif) || 0
  const score3 = parseInt(input.value.Penyakitginjalmoderat) || 0
  const score4 = parseInt(input.value.PPOKmoderat) || 0
  const score5 = parseInt(input.value.Kondisikomplikasilain) || 0

  input.value.skoring1 = (score1 + score2 + score3 + score4 + score5).toString()
}

// const updateScoreKriterialaintotal = () => {
//   const score1 = parseInt(input.value.pengobatankuratif) || 0
//   const score2 = parseInt(input.value.tidakmelanjutkanterapi) || 0
//   const score3 = parseInt(input.value.Nyeritidakteratasi) || 0
//   const score4 = parseInt(input.value.Memilikikeluhan) || 0
//   const score5 = parseInt(input.value.spiritual) || 0
//   const score6 = parseInt(input.value.SeringkeIGD) || 0
//   const score7 = parseInt(input.value.didiagnosisyangsama) || 0
//   const score8 = parseInt(input.value.tanpakemajuan) || 0
//   const score9 = parseInt(input.value.ICUtanpakemajuan) || 0

//   input.value.skoring1 = (score1 + score2 + score3 + score4 + score5 + score6 + score7 + score8 + score9).toString()
// } 

const updateScoreKriterialain = () => {
  const score1 = parseInt(input.value.pengobatankuratif) || 0

  input.value.TBpengobatankuratif = score1.toString()
} 

const updateScoreKriterialain2 = () => {
  const score1 = parseInt(input.value.tidakmelanjutkanterapi) || 0

  input.value.TBtidakmelanjutkanterapi = score1.toString()
}

const updateScoreKriterialain3 = () => {
  const score1 = parseInt(input.value.Nyeritidakteratasi) || 0

  input.value.TBNyeritidakteratasi = score1.toString()
} 

const updateScoreKriterialain4 = () => {
  const score1 = parseInt(input.value.Memilikikeluhan) || 0

  input.value.TBMemilikikeluhan = score1.toString()
}

const updateScoreKriterialain5 = () => {
  const score1 = parseInt(input.value.spiritual) || 0

  input.value.TBspiritual = score1.toString()
} 

const updateScoreKriterialain6 = () => {
  const score1 = parseInt(input.value.SeringkeIGD) || 0

  input.value.TBSeringkeIGD = score1.toString()
} 

const updateScoreKriterialain7 = () => {
  const score1 = parseInt(input.value.didiagnosisyangsama) || 0

  input.value.TBdidiagnosisyangsama = score1.toString()
} 

const updateScoreKriterialain8 = () => {
  const score1 = parseInt(input.value.tanpakemajuan) || 0

  input.value.TBtanpakemajuan = score1.toString()
} 

const updateScoreKriterialain9 = () => {
  const score1 = parseInt(input.value.ICUtanpakemajuan) || 0

  input.value.TBICUtanpakemajuan = score1.toString()
} 




setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.label-pso {
  font-weight: 500;
}
</style>
