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
              @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="column is-12 mt-5">
    <div class="columns is-multiline mt-5">
      <div class="column is-6">
        <h1 style="font-weight: bold;">Data Subyektif (anamnesis)</h1>
        <div class="columns is-multiline">
          <div class="column is-4">
            <VCheckbox class="fontcheckbox" v-model="input.batuk" true-value="Batuk" color="primary" /><span
              v-html="highlightMatch('Batuk')" class="highlighted-label"></span><br>
            <VCheckbox class="fontcheckbox" v-model="input.pilek" true-value="Pilek" color="primary" /><span
              v-html="highlightMatch('Pilek')" class="highlighted-label"></span><br>
            <VCheckbox class="fontcheckbox" v-model="input.gigipalsu" true-value="Gigi Palsu" color="primary" /><span
              v-html="highlightMatch('Gigi Palsu')" class="highlighted-label"></span><br>
          </div>
          <div class="column is-4">
            <VCheckbox class="fontcheckbox" v-model="input.pusing" true-value="Pusing" color="primary" /><span
              v-html="highlightMatch('Pusing')" class="highlighted-label"></span><br>
            <VCheckbox class="fontcheckbox" v-model="input.mual" true-value="Mual" color="primary" /><span
              v-html="highlightMatch('Mual')" class="highlighted-label"></span><br>
            <VCheckbox class="fontcheckbox" v-model="input.lainnya1" true-value="Lainnya" color="primary" />
            <textarea v-model="input.lainnya" class="textarea" placeholder="" rows="1"></textarea>
          </div>
          <div class="column is-4">
            <VCheckbox class="fontcheckbox" v-model="input.sesaknafas" true-value="Sesak Nafas" color="primary" /><span
              v-html="highlightMatch('Sesak Nafas')" class="highlighted-label"></span><br>
            <VCheckbox class="fontcheckbox" v-model="input.puasa" true-value="Puasa" color="primary" /><span
              v-html="highlightMatch('Puasa')" class="highlighted-label"></span><br>
          </div>
        </div>
      </div>
      <div class="column is-6">
        <h1 style="font-weight: bold;">Riwayat penyakit</h1>
        <div class="columns is-multiline">
          <div class="column is-4">
            <VCheckbox class="fontcheckbox" v-model="input.dm" true-value="DM" color="primary" /><span
              v-html="highlightMatch('DM')" class="highlighted-label"></span><br>
            <VCheckbox class="fontcheckbox" v-model="input.hipertensi" true-value="Hipertensi" color="primary" /><span
              v-html="highlightMatch('Hipertensi')" class="highlighted-label"></span><br>
            <VCheckbox class="fontcheckbox" v-model="input.asthma" true-value="Asthma" color="primary" /><span
              v-html="highlightMatch('Asthma')" class="highlighted-label"></span><br>
          </div>
          <div class="column is-4">
            <VCheckbox class="fontcheckbox" v-model="input.tb" true-value="TB Paru" color="primary" /><span
              v-html="highlightMatch('TB Paru')" class="highlighted-label"></span><br>
            <VCheckbox class="fontcheckbox" v-model="input.ami" true-value="AMI" color="primary" /><span
              v-html="highlightMatch('AMI')" class="highlighted-label"></span><br>
            <VCheckbox class="fontcheckbox" v-model="input.chf" true-value="CHF" color="primary" /><span
              v-html="highlightMatch('CHF')" class="highlighted-label"></span><br>
          </div>
          <div class="column is-4">
            <VCheckbox class="fontcheckbox" v-model="input.hepatitis" true-value="Hepatitis B-C" color="primary" /><span
              v-html="highlightMatch('Hepatitis B-C')" class="highlighted-label"></span><br>
            <VCheckbox class="fontcheckbox" v-model="input.hiv" true-value="HIV / AIDS" color="primary" /><span
              v-html="highlightMatch('HIV / AIDS')" class="highlighted-label"></span><br>
            <VCheckbox class="fontcheckbox" v-model="input.lainnya2" true-value="Lainnya2" color="primary" />
            <textarea v-model="input.lainnya22" class="textarea" placeholder="" rows="1"></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="column is-12 mb-0 mt-0">
    <div class="columns is-multiline">
      <div class="column is-6">
        <div class="columns is-multiline">
          <div class="column is-12 pt-0 mt-2">
            <h1 style="">Data obyektif (pemeriksaan fisik)</h1>
          </div>
        </div>
      </div>
      <div class="column is-6">
        <div class="columns is-multiline">
          <div class="column is-12 pt-0 mt-2">
            <h1 style=" text-align: right">Hasil pemeriksaan penunjang yang telah teridentifikasi</h1>
          </div>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="column is-12 mb-0 mt-0">
    <div class="columns is-multiline">
      <div class="column is-6">
        <div class="columns is-multiline">
          <div class="column is-4">
            <h1>Pemeriksaan</h1>
          </div>
          <div class="column is-2">
            <h1>Jam :</h1>
          </div>
          <div class="column is-6">
            <VField>
              <VDatePicker v-model="input.jamPemeriksaan" color="green" mode="time" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:clock">
                      <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-4" style="margin-top: -20px;">
            <h1>Tekanan darah</h1>
          </div>
          <div class="column is-8" style="margin-top: -20px;">
            <VField addons>
              <VControl expanded>
                <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarahObgyn" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>mmHg</VButton>
              </VControl>
            </VField>
          </div>
          <div class="column is-4" style="margin-top: -10px;">
            <h1>Frekuensi nafas</h1>
          </div>
          <div class="column is-8" style="margin-top: -10px;">
            <VField addons>
              <VControl expanded>
                <VInput type="text" class="input" placeholder="" v-model="input.nafasObgyn" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>x/menit</VButton>
              </VControl>
            </VField>
          </div>
          <div class="column is-4" style="margin-top: -10px;">
            <h1>Nadi</h1>
          </div>
          <div class="column is-8" style="margin-top: -10px;">
            <VField addons>
              <VControl expanded>
                <VInput type="text" class="input" placeholder="" v-model="input.nadiObgyn" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>x/menit</VButton>
              </VControl>
            </VField>
          </div>
          <div class="column is-4" style="margin-top: -10px;">
            <h1>Suhu aksila</h1>
          </div>
          <div class="column is-8" style="margin-top: -10px;">
            <VField addons>
              <VControl expanded>
                <VInput type="text" class="input" placeholder="" v-model="input.celciusObgyn" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>°C </VButton>
              </VControl>
            </VField>
          </div>
          <div class="column is-4" style="margin-top: -10px;">
            <h1>Abdomen</h1>
          </div>
          <div class="column is-8" style="margin-top: -10px;">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="" v-model="input.abdomen" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4" style="margin-top: -10px;">
            <h1>Thorax</h1>
          </div>
          <div class="column is-8" style="margin-top: -10px;">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="" v-model="input.thorax" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4" style="margin-top: -10px;">
            <h1>Extermitas</h1>
          </div>
          <div class="column is-8" style="margin-top: -10px;">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="" v-model="input.extermitas" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-6">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h1>Secara benar</h1>
          </div>
          <div class="column is-6">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="" v-model="input.secaraBenar" />
              </VControl>
            </VField>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <VCheckbox class="fontcheckbox" v-model="input.rontgen" true-value="Foto Rontgen" color="primary" /><span
              v-html="highlightMatch('Foto Rontgen')" class="highlighted-label"></span>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <VCheckbox class="fontcheckbox" v-model="input.laboratorium" true-value="Laboratorium" color="primary" />
            <span v-html="highlightMatch('Laboratorium')" class="highlighted-label"></span>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <VCheckbox class="fontcheckbox" v-model="input.ctscan" true-value="CT-Scan" color="primary" /><span
              v-html="highlightMatch('CT-Scan')" class="highlighted-label"></span>
          </div>
          <div class="column is-1" style="margin-top: -5px;">
            <VCheckbox class="fontcheckbox" v-model="input.ya1" true-value="Ya" color="primary" />
          </div>
          <div class="column is-5" style="margin-top: -5px;">
            <textarea v-model="input.textya1" class="textarea" placeholder="" rows="1"></textarea>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <VCheckbox class="fontcheckbox" v-model="input.mri" true-value="MRI" color="primary" /><span
              v-html="highlightMatch('MRI')" class="highlighted-label"></span>
          </div>
          <div class="column is-1" style="margin-top: -5px;">
            <VCheckbox class="fontcheckbox" v-model="input.ya2" true-value="Ya" color="primary" />
          </div>
          <div class="column is-5" style="margin-top: -5px;">
            <textarea v-model="input.textya2" class="textarea" placeholder="" rows="1"></textarea>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <VCheckbox class="fontcheckbox" v-model="input.usg" true-value="USG" color="primary" /><span
              v-html="highlightMatch('USG')" class="highlighted-label"></span>
          </div>
          <div class="column is-1" style="margin-top: -5px;">
            <VCheckbox class="fontcheckbox" v-model="input.ya3" true-value="Ya" color="primary" />
          </div>
          <div class="column is-5" style="margin-top: -5px;">
            <textarea v-model="input.textya3" class="textarea" placeholder="" rows="1"></textarea>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <VCheckbox class="fontcheckbox" v-model="input.ekg" true-value="EKG" color="primary" /><span
              v-html="highlightMatch('EKG')" class="highlighted-label"></span>
          </div>
          <div class="column is-1" style="margin-top: -5px;">
            <VCheckbox class="fontcheckbox" v-model="input.ya4" true-value="Ya" color="primary" />
          </div>
          <div class="column is-5" style="margin-top: -5px;">
            <textarea v-model="input.textya4" class="textarea" placeholder="" rows="1"></textarea>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <h1>Riwayat operasi sebelumnya</h1>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <textarea v-model="input.riwayatoperasi" class="textarea" placeholder="" rows="1"></textarea>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <h1>Riwayat alergi</h1>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <textarea v-model="input.riwayatalergi" class="textarea" placeholder="" rows="1"></textarea>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <h1>Dokumen rekam medis terkait</h1>
          </div>
          <div class="column is-6" style="margin-top: -5px;">
            <textarea v-model="input.dokumenrm" class="textarea" placeholder="" rows="1"></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="column is-12 mb-0 mt-0">
    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-12 pt-0 mt-5">
            <h1>Catatan Penting</h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.catatanpenting" placeholder="" rows="3"></VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="column is-12 mb-0 mt-0">
    <div class="columns is-multiline">
      <div class="column is-6">
        <div class="columns is-multiline">
          <div class="column is-12 pt-0 mt-5">
            <h1>Diagnosa pra-operasi</h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.diagnosapra" placeholder="" rows="3"></VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-12 pt-0">
            <h1>Posisi pasien dalam operasi</h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.posisiop" placeholder="" rows="3"></VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-12 pt-0">
            <h1>Profilaksis</h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.profilaksis" placeholder="" rows="1"></VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-12 pt-0">
            <h1>Alat khusus</h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.alatkhusus" placeholder="" rows="1"></VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-6">
        <div class="columns is-multiline">
          <div class="column is-12 pt-0 mt-5">
            <h1>Persiapan darah</h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.persiapandarah" placeholder="" rows="1"></VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-12 pt-0">
            <h1>Rencana operasi</h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.rencanaoperasi" placeholder="" rows="12"></VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="column is-12 mb-0 mt-0">
    <div class="columns is-multiline">
      <div class="column is-6 mt-5">
        <div class="columns is-multiline">
          <div class="column is-12 pt-0 mt-5">
            <h1>Dengan ini saya menyatakan bahwa saya telah menerima
              informasi tentang tujuan dan pentingnya dilakukan assesment
              pra operasi dan penandaan pada area operasi saya mengerti
              serta memahami hal tersebut</h1>
            <div class="column" style="text-align:center;">
              <h1>Tanda tangan pasien / keluarga</h1>
              <TandaTangan :elemenID="'TTDDokter1'" :width="'150'" :height="'150'" class="dek" />
              <VField class="is-autocomplete-select">
                <VControl>
                  <VInput type="text" class="input" placeholder="" v-model="input.ttdPasien" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-6 mt-5">
        <div class="columns is-multiline mt-5">
          <div class="column" style="text-align:center; margin-top: 45px;">
            <h1>Operator</h1>
            <!-- <TandaTangan :elemenID="'TTDDokter2'" :width="'150'" :height="'150'" class="dek" /> -->
            <VControl class="prime-auto">
              <AutoComplete v-model="input.CBDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" class="mt-2" />
            </VControl>
          </div>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="column is-12 mt-5">
    <h1 style="font-size:larger;font-weight:bold; text-align: center;">SITE MARKING LAKI LAKI</h1>
    <div class="columns is-multiline" style="margin-top: 50px;">
      <div class="column is-6" style="padding-left: 20px;">
        <div class="columns is-multiline">
          <div class="column is-2">
            <span class="label-ro">Prosedur</span>
          </div>
          <div class="column is-9">
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.namaprosedur" style="margin-top: -15px;" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-6" style="padding-right: 20px;">
        <div class="columns is-multiline">
          <div class="column is-3">
            <span class="label-ro">Tgl Prosedur</span>
          </div>
          <div class="column is-9">
            <VDatePicker v-model="input.tglProsedur" class="pt-3" mode="dateTime"
              style="width: 100%; margin-top: -15px;" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal Prosedur" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
      </div>
      <div class="column is-12 mb-0 mt-5">
        <div class="columns is-multiline">
          <div class="column is-6 mt-5">
            <ImgDraw elemenID="Gambar1" height="780" width="600" imageSrc="/images/simrs/fullbody1.png" />
            <ImgDraw elemenID="Gambar6" height="300" width="600" imageSrc="/images/simrs/kaki.png" class="mt-5" />
          </div>
        </div>
      </div>
      <div class="column is-6 mt-5">
            <ImgDraw elemenID="Gambar2" height="300" width="700" imageSrc="/images/simrs/kepalasamping.png" />
            <ImgDraw elemenID="Gambar3" height="300" width="600" imageSrc="/images/simrs/kepaladepan.png" />
            <ImgDraw elemenID="Gambar4" height="300" width="600" imageSrc="/images/simrs/tanganatas.png" />
            <ImgDraw elemenID="Gambar5" height="300" width="600" imageSrc="/images/simrs/tanganbawah.png" />
          </div>
      <div class="column is-12" align="center">
        <!-- <ImgDraw elemenID="Gambar" height="500" width="900" imageSrc="/images/simrs/odontogram.png"/>  -->
        <ImgDrawOdon elemenID="Gambar7" height="490" width="900" imageSrc="/images/simrs/odon.png"
          :valueImg="input.Gambar" class="mb-5" />
        <!-- <TOdontogram></TOdontogram> -->
      </div>
      <div class="column is-12 mb-0 mt-5">
        <h1 style="font-size:14px;font-weight:bold; text-align: center;">Saya menyatakan bahwa lokasi operasi yang telah
          ditetapkan pada diagram adalah benar</h1>
      </div>
      <div class="column is-12 mb-0 mt-5">
        <div class="columns is-multiline">
          <div class="column is-6 mt-5">
            <div class="columns is-multiline">
              <div class="column is-12 pt-0 mt-5">
                <div class="column" style="text-align:center;">
                  <h1>Nama dan Tanda tangan pasien</h1>
                  <TandaTangan :elemenID="'TTDDokter3'" :width="'150'" :height="'150'" class="dek" />
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.ttdPasien1" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-6 mt-5">
            <div class="columns is-multiline">
              <div class="column" style="text-align:center; margin-top: 14px;">
                <h1>Nama dan Tanda tangan dokter yang merawat</h1>
                <!-- <TandaTangan :elemenID="'TTDDokter4'" :width="'150'" :height="'150'" class="dek" /> -->
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.CBDokter1" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                </VControl>
              </div>
            </div>
          </div>
        </div>
      </div>
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
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import ImgDraw from '../page-emr-plugins/img-draw.vue'
import ImgDrawOdon from '../page-emr-plugins/img-draw-odontogram.vue'

const route = useRoute()
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
const dataTTD: any = ref([])
const filterMenu: any = ref('')
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('AssesmenPraOperasiLaki') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  riwayatAlergi: [{
    no: 1,
  }],
  daftarObat: [{
    no: 1,
  }],
  jamPemeriksaan: new Date(),
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const d_Petugas: any = ref([])
const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
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
        H.tandaTangan().set("TTDDokter1", dataTTD.value.TTDDokter1)
        H.tandaTangan().set("TTDDokter2", dataTTD.value.TTDDokter2)
        H.tandaTangan().set("TTDDokter3", dataTTD.value.TTDDokter3)
        H.tandaTangan().set("TTDDokter4", dataTTD.value.TTDDokter4)

        loadGambar("Gambar1", dataTTD.value.Gambar1)
        loadGambar("Gambar2", dataTTD.value.Gambar2)
        loadGambar("Gambar3", dataTTD.value.Gambar3)
        loadGambar("Gambar4", dataTTD.value.Gambar4)
        loadGambar("Gambar5", dataTTD.value.Gambar5)
        loadGambar("Gambar6", dataTTD.value.Gambar6)
        loadGambar("Gambar7", dataTTD.value.Gambar7)
      }
    })


}

const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById(element_id);
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    let imagess = value
    let background = new Image();
    background.src = imagess
    background.onload = function () {
      context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height);
    }
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object['TTDDokter1'] = H.tandaTangan().get("TTDDokter1");
  object['TTDDokter2'] = H.tandaTangan().get("TTDDokter2");
  object['TTDDokter3'] = H.tandaTangan().get("TTDDokter3");
  object['TTDDokter4'] = H.tandaTangan().get("TTDDokter4");

  object['Gambar1'] = H.tandaTangan().get("Gambar1");
  object['Gambar2'] = H.tandaTangan().get("Gambar2");
  object['Gambar3'] = H.tandaTangan().get("Gambar3");
  object['Gambar4'] = H.tandaTangan().get("Gambar4");
  object['Gambar5'] = H.tandaTangan().get("Gambar5");
  object['Gambar6'] = H.tandaTangan().get("Gambar6");
  object['Gambar7'] = H.tandaTangan().get("Gambar7");
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': route.name,
    'name_form': 'Assesment Pra Operasi Laki-Laki',
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

function highlightMatch(text) {
  if (!filterMenu.value) return text;

  const term = new RegExp(`(${filterMenu.value})`, 'gi');
  return text.replace(term, '<span style="background-color: yellow;">$1</span>');
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
