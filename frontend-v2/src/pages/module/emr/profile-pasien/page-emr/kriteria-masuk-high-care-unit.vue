<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Kriteria Masuk High Care Unit</h3>
            </div>
            <div class="right buttons">
              <!-- <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                              :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                              @kembaliKeun="kembaliKeun"></ButtonEmr> -->
              <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                :disabled="!NOREC_EMRPASIEN || NOREC_EMRPASIEN.length == 0" @click="print()"> Cetak
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
              </VButton>
              <!-- <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpanTemplate()"> Simpan Template
              </VButton> -->
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="column is-12">
          <table class="table is-bordered is-fullwidth">
            <thead>
              <tr>
                <th>KRITERIA FISIOLOGIS</th>
                <th>YA (✓)</th>
                <th>TIDAK (✓)</th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th class="grey-background">1. VITALSIGN</th>
                <th></th>
                <th></th>
              </tr>
              <tr>
                <th>Nilai pemantauan EWS oranye, merah dengan distres napas</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.EWS" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.EWS" true-value="Tidak" color="primary" />
                </th>
              </tr>
            </thead>

            <thead>
              <tr>
                <th class="grey-background">2. PEMERIKSAAN FISIK</th>
                <th></th>
                <th></th>
              </tr>
              <tr>
                <th>A. Oliguria - Anuria</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.oliguria" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.oliguria" true-value="Tidak" color="primary" />
                </th>
              </tr>
              <tr>
                <th>B. Klinis dehidrasi berat</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.dehidrasi" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.dehidrasi" true-value="Tidak" color="primary" />
                </th>
              </tr>
              <tr>
                <th>C. Kejang berulang atau status epileptikus</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.kejang" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.kejang" true-value="Tidak" color="primary" />
                </th>
              </tr>
              <tr>
                <th>D. Tamponade jantung atau pneumothorax dengan gangguan hemodinamik</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.tamponade" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.tamponade" true-value="Tidak" color="primary" />
                </th>
              </tr>
              <tr>
                <th>E. Overload cairan</th>
                <th class="yes-column">
                  <VCheckbox v-model="input.overload" true-value="Ya" color="primary" />
                </th>
                <th class="no-column">
                  <VCheckbox v-model="input.overload" true-value="Tidak" color="primary" />
                </th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th class="grey-background">3. ECG</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tr>
              <th>A. Infark miokard dengan aritmia kompleks, gagal jantung kongestif yang berpotensi mengancam nyawa
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.Infark" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.Infark" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <thead>
              <tr>
                <th class="grey-background">4. NILAI LABORATORIUM</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tr>
              <th>A. Kadar natrium darah &lt; 125 mmol/L, Hipernatremi > 155 mmol/L</th>
              <th class="yes-column">
                <VCheckbox v-model="input.Kadar" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.Kadar" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <tr>
              <th>B. Kadar kalium serum &lt; 2,5 mmol/L, Hiperkalemi > 5 mmol/L</th>
              <th class="yes-column">
                <VCheckbox v-model="input.kalium" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.kalium" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <tr>
              <th>C. PaO<sub>2</sub> &lt; 50 mmHg dengan oksigen ruangan</th>
              <th class="yes-column">
                <VCheckbox v-model="input.oksigen" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.oksigen" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <tr>
              <th>D. pH &lt; 7,1 atau > 7,7 dengan oksigen ruangan</th>
              <th class="yes-column">
                <VCheckbox v-model="input.ruangan" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.ruangan" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <tr>
              <th>E. Serum glukosa > 800 mg/dl</th>
              <th class="yes-column">
                <VCheckbox v-model="input.Serum" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.Serum" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <tr>
              <th>F. Serum kalsium > 15 mg/dl</th>
              <th class="yes-column">
                <VCheckbox v-model="input.kalsium" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.kalsium" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <tr>
              <th>G. Kadar keton urin > + 3</th>
              <th class="yes-column">
                <VCheckbox v-model="input.urin" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.urin" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <tr>
              <th>H. Kadar BUN/SC darah yang membutuhkan RRT segera</th>
              <th class="yes-column">
                <VCheckbox v-model="input.darah" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.darah" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <tr>
              <th>I. Kadar obat atau substansi kimia dalam darah telah melebihi dosis toksis yang mengganggu hemodinamik
                dan
                berpotensi mengancam nyawa (alergi/anafilaksis)</th>
              <th class="yes-column">
                <VCheckbox v-model="input.obat" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.obat" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <thead>
              <tr>
                <th class="grey-background">5. NILAI RADIOLOGI</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tr>
              <th>A. Gambaran CT Scan abnormal yang berpotensi mengancam nyawa (perdarahan cerebral, contusion atau
                perdarahan
                subarachnoid) dengan atau tanpa penurunan status mental</th>
              <th class="yes-column">
                <VCheckbox v-model="input.CT" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.CT" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <tr>
              <th>B. Ruptur viscera, blader, liver, varices esofagus, perdarahan dengan atau tanpa gangguan hemodinamik
              </th>
              <th class="yes-column">
                <VCheckbox v-model="input.blader" true-value="Ya" color="primary" />
              </th>
              <th class="no-column">
                <VCheckbox v-model="input.blader" true-value="Tidak" color="primary" />
              </th>
            </tr>
            <tr>
              <th class="grey-background">6. Pasien dengan keperluan persiapan dan pemantauan pra-intra-paska operasi
              </th>
              <!-- <th class="yes-column grey-background">
                          <VCheckbox v-model="input.persiapan" true-value="Ya" color="primary" />
                        </th>
                        <th class="no-column grey-background">
                          <VCheckbox v-model="input.persiapan" true-value="Tidak" color="primary" />
                        </th> -->
              <th></th>
              <th></th>
            </tr>
            <tr>
              <th>
                <VField>
                    <VTextarea rows="2" v-model="input.persiapanText1"></VTextarea>
                </VField>
              </th>
              <th colspan="2">
                <VField>
                  <VTextarea rows="2" v-model="input.persiapanText2"></VTextarea>
                </VField>
              </th>
            </tr>
          </table>
          <table class="table is-bordered is-fullwidth mt-4">
            <thead>
              <tr>
                <th class="grey-background">Kesimpulan</th>
                <th class="yes-column grey-background"></th>
              </tr>
            </thead>
            <tr>
              <td colspan="2">
                <VField>
              <th>Memenuhi indikasi masuk HCU</th>
              <VTextarea v-model="input.hcu" placeholder="Kesimpulan" color="primary" />
              </VField>
              </td>
            </tr>6.
            <tr>
              <th>Alat transport yang digunakan</th>
            </tr>
            <tr>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.alat" true-value="Ya" color="primary" />
                  <span>Brancard</span>
                </div>
              </th>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.alat" true-value="Tidak" color="primary" />
                  <span>Kursi roda</span>
                </div>
              </th>
            </tr>

            <tr>
              <th>Pendamping selama transfer</th>
            </tr>
            <tr>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.pendampingDokter" true-value="Ya" color="primary" />
                  <span>Dokter</span>
                </div>
              </th>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.pendampingPerawat" true-value="Tidak" color="primary" />
                  <span>Perawat</span>
                </div>
              </th>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.pendampingCG" true-value="Tidak" color="primary" />
                  <span>Care giver/POS</span>
                </div>
              </th>
            </tr>

            <tr>
              <th>Perlu alat medis selama transfer:</th>
            </tr>
            <tr>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.perluAlatMedis" true-value="Ya" color="primary" />
                  <span>YA, Sebutkan</span>
                  <VField>
                    <VTextarea v-model="input.perluAlatMedisDetail" placeholder="Sebutkan" color="primary" />
                  </VField>
                </div>
              </th>
              <th>
                <div class="checkbox-container">
                  <VCheckbox v-model="input.perluAlatMedis" true-value="Tidak" color="primary" />
                  <span>TIDAK</span>
                </div>
              </th>
            </tr>

          </table>
        </div>

        <br>
        <hr><br>
        <div class="columns">
          <div class="column is-8"></div>
          <div class="column is-4">
            <VField label="Garut">
              <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </VField>
            <div class="is-flex" style="justify-content: center;">
              <TandaTangan :elemenID="'TTDPegawai'" :width="'150'" :height="'150'" class="dek" />
            </div>
            <div class="column" style="text-align:center;">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.pegawai" :suggestions="d_pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
              </VControl>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalTemplate = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                  <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                  <td class="tg-0lax text-center" width="20%">No EMR</td>
                  <td class="tg-0lax text-center" width="20%">Dokter</td>
                  <td class="tg-0lax text-center" width="20%">Section</td>
                  <td class="tg-0lax text-center" width="5%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
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
                  <td class="tg-0lax text-center" width="15%">No</td>
                  <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                  <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                  <td class="tg-0lax text-center" width="50%">Nama Template</td>
                  <td class="tg-0lax text-center" width="15%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplateFix">
                <tr>
                  <td style="width:15%;text-align:center">
                    <span class="mb-2">{{ resep.no }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:50%;text-align:center">
                    <span class="mb-2">{{ resep.namatemplate }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal> -->
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
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'

useHead({
  title: 'Kriteria Masuk HCU - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
let detailStatusFungsional = ref(EMR.detailStatusFungsional())
let statusFungsional: any = ref(EMR.statusFungsional())
let DiagnosaKeperawatanRanap: any = ref(EMR.DiagnosaKeperawatanRanap())
let RencanaKeperawatan: any = ref(EMR.RencanaKeperawatan())
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
const d_pegawai: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({})
const COLLECTION: any = ref('KriteriaMasukHighCareUnit') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const d_freknyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang Timbul' }, { value: 3, label: 'Terus Menerus' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lain-lain' }])
const d_gangguanpsikologis: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Gelisah' }, { value: 3, label: 'Takut' }, { value: 4, label: 'Sedih' }, { value: 5, label: 'Rendah diri' }, { value: 6, label: 'Acuh tak acuh' }, { value: 7, label: 'Mudah tersinggung' }, { value: 8, label: 'Menarik diri' }])
const d_masalahkawin: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_kekerasan: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaankesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi Lainnya' }])
const d_rohaniawan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_penurunanbb: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }, { value: 3, label: '1-5 kg' }, { value: 4, label: '6-10 kg' }, { value: 5, label: '11-15 kg' }, { value: 6, label: '>15 kg' }])
const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_mengontrolbab: any = ref([{ value: 1, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 2, label: 'Kadang inkontinen (1xseminggu)' }, { value: 3, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 1, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 2, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 3, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 1, label: 'Butuh pertolongan orang lain' }, { value: 2, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 1, label: 'Tergantung pertolongan orang lain' }, { value: 2, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 3, label: 'Mandiri' }])
const d_makan: any = ref([{ value: 1, label: 'Tidak mampu' }, { value: 2, label: 'Perlu seseorang menolong memotong makanan' }, { value: 3, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Dengan kursi roda' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 1, label: 'Tergantung orang lain' }, { value: 2, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 3, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Butuh Pertolongan' }, { value: 3, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 1, label: 'Teragantung orang lain' }, { value: 2, label: 'Mandiri' }])
const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)

let listHipertensi: any = ref(EMR.hipertensi())
let listDiabetes: any = ref(EMR.diabetes())
let listDyslipidemia: any = ref(EMR.dyslipidemia())
let listDuaPilihan: any = ref(EMR.duaPilihan())
let listAgama: any = ref(EMR.agama())
let listStatus: any = ref(EMR.status())
let listKeluarga: any = ref(EMR.keluarga())
let listTempatTinggal: any = ref(EMR.tempatTinggal())
let listPsikologis: any = ref(EMR.psikologis())
let listMore: any = ref(EMR.more())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let resikoNutrisional: any = ref(EMR.resikoNutrisional())
let fungsionalPertama: any = ref(EMR.fungsionalPertama())
let listRangeNilaiPoin: any = ref(EMR.nilaiPoin())
let listDESCNilai: any = ref(EMR.descNilai())
let pertanyaanA: any = ref(EMR.pertanyaanA())
let pertanyaanB: any = ref(EMR.pertanyaanB())
let pertanyaanC: any = ref(EMR.pertanyaanC())
let dropdownAllo: any = ref([
  "Suami/Istri",
  "Orang tua",
  "Anak",
  "Lainnya"
])

const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    H.tandaTangan().set("TTDPegawai", response[0]['TTDPegawai'])
  } else {
    getDataExist()
  }
}
const filterMenu: any = ref('')
const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDPegawai'] = H.tandaTangan().get("TTDPegawai");
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
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}`).then((response) => {
    d_pegawai.value = response
  })
}

const getDataExist = () => {
  input.value.tanggal = new Date()
  input.value.kebjamKedatangan = new Date()
  input.value.kebjamAsesmenAwal = new Date()
}

const print = async () => {
  H.printBlade(`emr/cetak-formulir-kriteria-masuk-hcu?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
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


// const simpanTemplate = () => {
//   if (!input.value.namatemplate) {
//     H.alert('warning', "Nama Template wajib diisi")
//     return;
//   }
//   let ID = input.id ? input.id : ''

//   let object: any = {}

//   object = input.value
//   object.nocm = pasien.value.nocm

//   object.pasien = H.setObjectPasien(pasien.value)
//   object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
//   let json = {
//     'id': ID,
//     'norec_emr': NOREC_EMRPASIEN.value,
//     'collection': COLLECTION.value,
//     'url_form': props.FORM_URL,
//     'name_form': props.FORM_NAME,
//     'jenis_emr': 'asesmen_medis',
//     'data': object
//   }
//   isLoading.value = true

//   useApi().post(
//     `/emr/simpan-emr-template`, json).then((response: any) => {
//       isLoading.value = false
//       input.value.namatemplate = null
//     }).catch((e: any) => {
//       isLoading.value = false
//     })
// }

// const pilihTemplate = async (index: any) => {
//   isLoading.value = true
//   useApi().get(
//     `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
//       isLoading.value = false
//       if (responselast.length) {
//         listTemplate.value = responselast //set ke inputan
//         showModalTemplate.value = true
//       } else {
//         H.alert('warning', 'Data tidak ada')
//       }
//     })
// }

// const addTemplate = (response: any) => {
//   console.log(response)
//   input.value = response //set ke inputan
//   input.value.namatemplate = null
// }

// const pilihTemplateFix = async (index: any) => {
//   isLoading.value = true
//   useApi().get(
//     `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
//       isLoading.value = false
//       console.log(responselast)
//       if (responselast.length) {
//         for (var x = 0; x < responselast.length; x++) {
//           responselast[x].no = x + 1
//           responselast[x].id = ''
//         }
//         listTemplateFix.value = responselast //set ke inputan
//         showModalTemplateFix.value = true
//       } else {
//         H.alert('warning', 'Data tidak ada')
//       }
//     })
// }
</script>

<style lang="scss">
.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

table.assesment {
  border-collapse: collapse;
  width: 100%;
}


.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
  // border: 1px solid black;
}

.assesment th,
.assesment td {
  padding: 8px;
  vertical-align: middle !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

.table.is-borderless {
  border: none !important;
  background-color: transparent;
}

.table.is-borderless th,
tr,
td {
  border: none !important;
  background-color: transparent !important;
}

.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

table.assesment {
  border-collapse: collapse;
  width: 100%;
}

.grey-background {
  background-color: #d3d3d3;
  /* Grey color */
}

.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
  // border: 1px solid black;
}

.assesment th,
.assesment td {
  padding: 8px;
  vertical-align: middle !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

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

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
}


.tg2 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg2 td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg2 th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: middle
}
</style>