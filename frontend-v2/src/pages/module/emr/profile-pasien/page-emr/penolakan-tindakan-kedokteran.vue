<style>
table {
  width: 100% !important;
}
</style>
<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Penolakan Tindakan Kedokteran</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun">
            </ButtonEmr>
          </div>
        </div>
      </div>

      <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalTemplate = false">
        <template #content>
          <form class="modal-form">
            <div class="column is-12 pt-0 pb-0">
              <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
              <div style="overflow-y:auto;" class="mt-1">
                <table class="tg table-tg" v-if="listTemplate.length > 0">
                  <thead>
                    <tr>
                      <td class="tg-0lax text-center" width="15%">Tanggal Input</td>
                      <td class="tg-0lax text-center" width="15%">Tanggal Registrasi</td>
                      <td class="tg-0lax text-center" width="15%">No Registrasi</td>
                      <td class="tg-0lax text-center" width="15%">No EMR</td>
                      <td class="tg-0lax text-center" width="20%">Dokter</td>
                      <td class="tg-0lax text-center" width="15%">Section</td>
                      <td class="tg-0lax text-center" width="5%">#</td>
                    </tr>
                  </thead>
                  <tbody v-for="resep in listTemplate">
                    <tr>
                      <td style="width:15%;text-align:center">
                        <span class="mb-2">{{ resep.created_at }}</span><br>
                      </td>
                      <td style="width:15%;text-align:center">
                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                      </td>
                      <td style="width:15%;text-align:center">
                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                      </td>
                      <td style="width:15%;text-align:center">
                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                      </td>
                      <td style="width:20%;text-align:center">
                        <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                      </td>
                      <td style="width:15%;text-align:center">
                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center">
                        <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                          color="info" v-tooltip-prime.top="'Pilih'">
                        </VIconButton>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </form>
        </template>
      </VModal>

      <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
        @close="showModalTemplateFix = false">
        <template #content>
          <form class="modal-form">
            <div class="column is-12 pt-0 pb-0">
              <span style="font-size:9pt;font-weight:bold">List Template</span>
              <div style="overflow-y:auto;" class="mt-1">
                <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                  <thead>
                    <tr>
                      <td class="tg-0lax text-center" width="5%">No</td>
                      <td class="tg-0lax text-center" width="15%">Tanggal Dibuat</td>
                      <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                      <td class="tg-0lax text-center" width="25%">Nama Template</td>
                      <td class="tg-0lax text-center" width="15%">#</td>
                    </tr>
                  </thead>
                  <tbody v-for="resep in listTemplateFix">
                    <tr>
                      <td style="width:5%;text-align:center">
                        <span class="mb-2">{{ resep.no }}</span><br>
                      </td>
                      <td style="width:15%;text-align:center">
                        <span class="mb-2">{{ resep.created_at }}</span><br>
                      </td>
                      <td style="width:20%;text-align:center">
                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                      </td>
                      <td style="width:25%;text-align:center">
                        <span class="mb-2">{{ resep.namatemplate }}</span><br>
                      </td>
                      <td style="width:15%;text-align:center">
                        <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                          color="info" v-tooltip-prime.top="'Pilih'">
                        </VIconButton>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </form>
        </template>
      </VModal>

      <!-- form baru -->

      <div class="column">
        <div class="column buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
          <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
            @click="pilihTemplateFix(index)"> Pilih Template
          </VButton>
        </div>

        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

        <div class="column">
          <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
              template</span></h1>
          <VField>
            <VControl>
              <VTextarea v-model="input.namatemplate" rows="1">
              </VTextarea>
            </VControl>
          </VField>
        </div>

        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">

        <div class="column">
          <div class="column pt-0 pb-1" style="text-align: center;">
            <h1 style="font-weight: bold;">PEMBERIAN INFORMASI (INFORMATION)</h1>
          </div>
          <table class="table is-bordered  m-0">
            <tr>
              <th style="width: 30%;">
                Dokter Pelaksana Tindakan<br>
                <i>Doctor in charge / operator</i>
              </th>
              <th style="width: 70%;">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th style="width: 30%;">
                Pemberi Informasi<br>
                <i>Informer</i>
              </th>
              <th style="width: 70%;">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.DDPemberiInformasi" :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th style="width: 30%;">
                Peneriman Informasi/Pemberi Penolakan<br>
                <i>Recipient / Approver*</i>
              </th>
              <th style="width: 70%;">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBPemberiPersetujuan" />
                </VControl>
              </th>
            </tr>
          </table>
          <table class="table is-bordered  m-0">
            <thead>
              <tr>
                <th style="text-align: center;">JENIS INFORMASI<br><i>Type of information</i></th>
                <th style="text-align: center;">ISI INFORMASI<br><i>Description of the information</i></th>
                <th style="text-align: center;">TANDA (✓)<br><i>Checklist (✓)</i></th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th>1. Diagnosis (WD Dan DD)<br><i>Diagnosis</i></th>
                <th>
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextDiagnosisWD" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;">
                  <VCheckbox v-model="input.pemeriksaanDiagnosis" true-value="Ya" color="primary" />
                </th>
              </tr>
              <tr>
                <th>
                  2. Dasar Diagnosis<br>
                  <i>Underlying Diagnosis</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.Diagnosistextdasar" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-cumn">
                  <VCheckbox v-model="input.diagnosisTextDasar" true-value="Ya" color="primary" />
                </th>
              </tr>
            </thead>

            <thead>
              <tr>
                <th>
                  3. Tindakan Kedokteran<br>
                  <i>Medical Action</i>
                </th>
                <th>
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextTindakanKedokteran" true-value="a" color="primary"
                      rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;">
                  <VCheckbox v-model="input.TindakanKedokteranCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
              <tr>
                <th>
                  4. Indikasi Tindakan<br>
                  <i>Indication of Action</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextIndikasiTindakan" true-value="a" color="primary"
                      rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-column">
                  <VCheckbox v-model="input.pemeriksaantextIndikasiTindakanCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
              <tr>
                <th>
                  5. Tata Cara<br>
                  <i>Procedures</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextTataCara" true-value="a" color="primary" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-column">
                  <VCheckbox v-model="input.pemeriksaantextTataCaraCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
              <tr>
                <th>
                  6. Tujuan<br>
                  <i>Purpose</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextTujuan" true-value="a" color="primary" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-column">
                  <VCheckbox v-model="input.tujuanCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
              <tr>
                <th>
                  7. Risiko<br>
                  <i>Risk</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextRisiko" true-value="a" color="primary" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-column">
                  <VCheckbox v-model="input.pemeriksaantextRisikoCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
              <tr>
                <th>
                  8. Komplikasi<br>
                  <i>Complication</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextKomplikasi" true-value="a" color="primary" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-column">
                  <VCheckbox v-model="input.pemeriksaantextKomplikasiCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th>
                  9. Prognosis<br>
                  <i>Prognosis</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextPrognosis" true-value="a" color="primary" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;">
                  <VCheckbox v-model="input.pemeriksaantextPrognosisCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
            </thead>
            <tr>
              <th>
                10. Alternatif Dan Risko<br>
                <i>Alternative & Risk</i>
              </th>
              <th class="yes-column">
                <VField>
                  <VTextarea v-model="input.pemeriksaantextAlternatif" true-value="a" color="primary" rows="2" />
                </VField>
              </th>
              <th style="text-align: center;" class="yes-column">
                <VCheckbox v-model="input.pemeriksaantextAlternatifCheckbox" true-value="Ya" color="primary" />
              </th>
            </tr>
            <thead>
              <tr>
                <th>
                  11. Lain-lain<br>
                  <i>Other</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextLainLain" true-value="a" color="primary" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;">
                  <VCheckbox v-model="input.pemeriksaantextLainLainCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
            </thead>

          </table>
          <table class="table is-bordered ">
            <tr>
              <th style="width: 70%;">
                Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar dan jelas dan
                memberikan kesempatan untuk bertanya dan/atau berdiskusi<br>
                <i>I have explained the information correctly, clearly and provide opportunity to ask and or discuss</i>
              </th>
              <th style="width: 30%;text-align:center">
                <span>Tanda Tangan Dokter</span><br>
                <TandaTangan :elemenID="'TTDmenyatakanMenerangkanInformasi'" :width="'150'" :height="'150'"
                  class="dek" />
              </th>
            </tr>
            <tr>
              <th style="width: 70%;">
                Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas yang saya beri
                tanda/paraf di kolom kanannya, dan telah memahaminya<br>
                <i>I have received the information as I have given signature in the right column and have understood</i>
              </th>
              <th style="width: 30%;text-align:center">
                <span>Tanda Tangan (Pasien/Keluarga)</span><br>
                <TandaTangan :elemenID="'TTDmenyatakanMenerimaInformasi'" :width="'150'" :height="'150'" class="dek" />
              </th>
            </tr>
            <tr>
              <th colspan="2">
                *Bila Pasien tidak kompeten atau tidak mau menerima informasi, maka penerima informasi adalah Wali atau
                Keluarga terdekat<br>
                If the patient is incompetent or do not want to receive the information, then information are given to
                his or
                her parents, spouse, next of kin or the guardian
              </th>
            </tr>
          </table>

          <div class="is-12">
            <h1 style="font-weight: bold;">PENOLAKAN TINDAKAN KEDOKTERAN (WAIVER FOR MEDICAL ACTION)</h1>
          </div>
          <div class="is-12">
            <h1 style="font-weight: bold;">Yang bertandatangan di bawah ini, Saya<br><i>The undersigned below, I</i>
            </h1>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Nama :<br><i>Name</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.namaPasien" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Umur :<br><i>Age</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.umurPasien" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Jenis Kelamin :<br><i>Gender</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.jeniskelamin" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Alamat :<br><i>Address</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.alamat" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-5">
              <h1 style="font-weight: bold">dengan ini menyatakan Penolakan untuk dilakukannya tindakan : </h1>
            </div>
            <div class="column is-7">
              <VField>
                <VControl>
                  <VInput v-model="input.persetujuan" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="is-12">
            <h1 style="font-weight: bold;">Terhadap, saya</h1>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Nama :<br><i>Name</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.namaPasienTerhadap" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Umur :<br><i>Age</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.umurPasienTerhadap" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Jenis Kelamin :<br><i>Age</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.jeniskelaminTerhadap" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Alamat :<br><i>Address</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.alamatTerhadap" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>
          <p>Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada
            saya,
            termasuk risiko dan
            komplikasi yang mungkin timbul.</p>
          <p>Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan
            kedokteran
            bukanlah
            keniscayaan, melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa.</p>
          <p>II Have Fully Understand the need and the benefits of these action which has been explained to me,
            including the
            risks and any
            complication that might be occur. <br>
            I understand that the practice of medicine is not an exact science, hence the success of the medical action
            is not
            an absolute thing but it is
            very dependent on the permission of God Almighty.</p>
        </div>
        <div class="column columns">
          <div class="column is-4">
            <h1>Garut</h1>
            <VField addons>
              <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
              <VControl class="field-addon-body">
                <VButton static>WIB</VButton>
              </VControl>
            </VField>
          </div>

          <div class="column is-4">
            <h1>Saksi : </h1>
            <VControl>
              <VInput v-model="input.TBSaksi" class="input" type="text" />
            </VControl>
          </div>
        </div>

        <div class="columns">
          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1 style="font-weight: bold;">Yang Menyatakan*</h1>
              <TandaTangan :elemenID="'TTDmenyatakan'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <VInput v-model="input.namaPasienmenyatakan" class="input mt-2" type="text" />
              </VControl>
            </div>
          </div>

          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1 style="font-weight: bold;">Pihak Keluarga</h1>
              <TandaTangan :elemenID="'TTDpihakkeluarga'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <VInput v-model="input.pihakKeluarga" class="input mt-2" type="text" />
              </VControl>
            </div>
          </div>

          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1 style="font-weight: bold;">Pihak Rumah Sakit</h1>
              <TandaTangan :elemenID="'TTDpihak'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.DDDPihakRumahSakit" :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
              </VControl>
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
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';

useHead({ title: 'Penolakan Tindakan Kedokteran - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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

const route = useRoute()
const pasien: any = ref({})
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const dataTTD: any = ref([])
const item: any = reactive({})
const COLLECTION: any = ref('PenolakanTindakanKedokteran') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  DTttd: new Date()
})
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)

const loadRiwayat = async () => {
  isLoading.value = true
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      H.tandaTangan().set('TTDmenyatakanMenerangkanInformasi', dataTTD.value.TTDmenyatakanMenerangkanInformasi)
      H.tandaTangan().set('TTDmenyatakanMenerimaInformasi', dataTTD.value.TTDmenyatakanMenerimaInformasi)
      H.tandaTangan().set('TTDmenyatakan', dataTTD.value.TTDmenyatakan)
      H.tandaTangan().set('TTDpihakkeluarga', dataTTD.value.TTDpihakkeluarga)
      H.tandaTangan().set('TTDpihak', dataTTD.value.TTDpihak)

      isLoading.value = false
    } else {
      isLoading.value = false
      H.alert('info', 'Data berhasil dimuat')
    }
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
  object['TTDmenyatakanMenerangkanInformasi'] = H.tandaTangan().get('TTDmenyatakanMenerangkanInformasi')
  object['TTDmenyatakanMenerimaInformasi'] = H.tandaTangan().get('TTDmenyatakanMenerimaInformasi')
  object['TTDmenyatakan'] = H.tandaTangan().get('TTDmenyatakan')
  object['TTDpihakkeluarga'] = H.tandaTangan().get('TTDpihakkeluarga')
  object['TTDpihak'] = H.tandaTangan().get('TTDpihak')
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
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const simpanTemplate = () => {
  if(!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
  let ID = input.id ? input.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
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

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    input.value.namatemplate = null
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      listTemplate.value = responselast //set ke inputan
      showModalTemplate.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const addTemplate = (response: any) => {
  input.value = response //set ke inputan
  input.value.namatemplate = null
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      console.log(responselast)
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          responselast[x].id = ''
        }
        listTemplateFix.value = responselast //set ke inputan
        showModalTemplateFix.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
    d_Pegawai.value = response
  })
}

const setAutoFill = async () => {
  input.value.namaPasienTerhadap = props.pasien.namapasien
  input.value.jeniskelaminTerhadap = props.pasien.jeniskelamin
  input.value.alamatTerhadap = props.pasien.alamatlengkap
  input.value.umurPasienTerhadap = props.pasien.umur
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
    await setAutoFill()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});
</script>
