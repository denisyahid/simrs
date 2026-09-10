<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>KRITERIA MASUK PICU</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12" style="margin-top: 30px;">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <h1 class="mb-3 emr">Nama Template&emsp;&emsp;**Hanya diisi jika ingin membuat template</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.namatemplate" rows="1">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h1 class="mb-3" style="font-weight: bold;">Tanggal Lahir</h1>
                                    <VField>
                                        <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 class="mb-3" style="font-weight: bold;">Jam </h1>
                                    <VField>
                                        <VDatePicker v-model="input.kebjamAsesmenAwal" mode="time" style="width: 100%"
                                            trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Jam"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <br>
                <hr><br>
                  <div class="container">
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
                            <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">1. VITAL SIGN
                            </th>
                      </tr>
                      <tr>
                      <th>a. Frekuensi denyut jantung &lt; Persentil 10 sesuai usia (Bradikardia) atau Frekuensi denyut jantung > 2SD sesuai usia (takikardi)
                        <br> <i>Frequency of the heart beat &lt; percentile 10 realted age (Bradicardia) or frequency of the heart beat > 2 SD realted age (Tachicardia)</i>
                      </th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.vitA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.vitA1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>b. Tekanan darah sistolik kurang dari persentil 5 atau &lt; 2SD sesuai usia <br><i>Systolic blood pressure less than percentile 5 or &lt; 2SD related age</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.vitB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.vitB1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>c. Disfungsi pernafasan index O2 &lt; 200 (ARDS); 200-300 (ALI), dibutuhkan FiO2 > 50% untuk mempertahankan saturasi perifer > 92% 
                        <br><i>Disfungsional breath index O2 &lt; 200 (ARDS); 200-300(ALI), is needed FiO1 > 50% to keep peripheral saturation > 92 %</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.vitC" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.vitC1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                      </thead>

                      <thead>
                         <tr>
                            <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">2. NILAI LABORATORIUM <i> Pathology Value </i> 
                            </th>
                      </tr>
                      <tr>
                      <th>a. Kadar Natrium serum &lt; 125mEq/L atau > 160 mEq/L
                        <br> <i>Levels of serum sodium &lt; 125 mEq/L or >160 mEq/L</i>
                      </th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.laboA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.laboA1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>b. Kadar Kalium serum &lt; 2,5 mEq/L atau 7,0 mEq/L <br><i>Levels of serum potassium &lt; 2,5 mEq/L or > 7,0 mEq/L</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.laboB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.laboB1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>c. PaO2 &lt; 60 mmHg
                        <br><i>PaO2 &lt; 60mmHg</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.laboC" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.laboC1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>d. PCO2 > 50 mmHg
                        <br><i>PCO2> 50 mmHg</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.laboD" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.laboD1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>e. pH &lt; 7,1 atau > 7,7
                        <br><i>pH &lt; 7,1 or > 7,7</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.laboE" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.laboE1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>f. Serum glukosa > 30 mg/dl
                        <br><i>Serum glucose > 300 mg/dl</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.laboF" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.laboF1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>g. Serum kalsium > 15 mg/dl
                        <br><i>Serum calcium > 15 mg/dl</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.laboG" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.laboG1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                      </thead>

                      <thead>
                         <tr>
                            <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">3. NILAI RADIOLOGI Radiology Value 
                            </th>
                      </tr>
                      <tr>
                      <th>a. Perdarahan cerebral, contusion atau perdarahan subarachnoid dengan penurunan status mental.
                        <br> <i>Cerebral hemorrhage, contusion, or subarachnoid hemorrhage with a decrease in mental status</i>
                      </th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.radiA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.radiA1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>b. Ruptur viscera, blader, liver, varises esofagus, perdarahan dengan gangguan hemodinamik
                        <br><i>Ruptured viscera, bladder, liver, esaphageal varices, bleeding of the uterus with hemodynamic disturbances.</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.vitB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.vitB1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                      </thead>

                      <thead>
                         <tr>
                            <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">4. ECG
                            </th>
                      </tr>
                      <tr>
                      <th>a. Infark miokard dengan aritmia kompleks, gangguan hemodinamik atau gagal jantung kongestif
                        <br> <i>Complex myocardial infarction with arrhythmia, hemodynamic disturbances or congestive heart failure</i>
                      </th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.ecgA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.ecgA1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>b. Ventrikular takikardi atau ventricular fibrilasi
                        <br><i>Ventricular tachycardia or ventricular fibrillation</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.ecgB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.ecgB1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>c. Blok jantung komplit dengan hemodinamik tidak stabil
                        <br><i>Complete heart block with unstable haemodynamic.</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.ecgB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.ecgB1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                      </thead>

                      <thead>
                         <tr>
                            <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">5. PEMERIKSAAN FISIK Physical Examination
                            </th>
                      </tr>
                      <tr>
                      <th>a. Pupil anisokor pada pasientidak sadar.
                        <br> <i>Pupil anisokor the unconscious patient.</i>
                      </th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fiA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fiA1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>b. Anuria
                        <br><i>Anuria</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fiB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fiB1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>c. Obstruksi saluran napas
                        <br><i>Airway obstruction</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fiC" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fiC1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>d. Koma (sesuai pediatric coma scale) 
                        <br><i>Coma (based on pediatric coma scale)</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fiD" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fiD1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>e. Kejang berulang atau status epileptikus 
                        <br><i>Recurrent seizures or refracter seizures</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fiE" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fiE1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>f. Sianosis 
                        <br><i>Cyanotic</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fiF" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fiF1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>g. Tamponade jantung atau pneumothorax 
                        <br><i>Cardiac tamponade or pneumothorax</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fiG" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fiG1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                      </thead>
                      
                  </table>
                   <table class="table is-bordered is-fullwidth mt-4">
                      <thead>
                        <tr>
                          <th class="grey-background" style="font-size: 19px">Kesimpulan</th>
                          <th class="yes-column grey-background"></th>
                        </tr>
                      </thead>
                      <tr>
                        <td colspan="2">
                          <VField>
                             <b style="font-size: 17px">BERDASARKAN KONDISI DI ATAS MAKA MEMENUHI INI INDIKASI MASUK PICU DENGAN PRIORITAS. <br>
                            <i>BBased on the condition above, he/she fit to the indication for admitting PICU with Priority </i></b>
                            
                          </VField>
                        </td>
                      </tr>
                      <tr>
                  <th>Alat transportasi yang digunakan :<br> <i>Transport equipments needed </i> </th>
                </tr>
                <tr>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.bran" true-value="Ya" color="primary" />
                      <span>brancard </span>
                    </div>
                  </th>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.kurs" true-value="Tidak" color="primary" />
                      <span>Kursi Roda</span>
                    </div>
                  </th>
                </tr>

                <tr>
                  <th>Pendamping selama transfer : <br> <i>Transfer Escort </i></th>
                </tr>
                <tr>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.docter" true-value="Ya" color="primary" />
                      <span>Dokter / <i>Doctor</i></span>
                    </div>
                  </th>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.paramedic" true-value="Tidak" color="primary" />
                      <span>Paramedis / <i>Paramedic </i></span>
                    </div>
                  </th>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.caregiver" true-value="Tidak" color="primary" />
                      <span>Care Giver/POS</span>
                    </div>
                  </th>
                </tr>

               <tr>
                  <th>Alat medis yang dibawa selama transfer : <br> <i>Medical equipments needed while transfer</i> </th>
                </tr>
                <tr>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.hooh" true-value="Ya" color="primary" />
                      <span>YA</span>
                      <VField>
                        <VTextarea v-model="input.textAreaValue" placeholder="Sebutkan" color="primary" />
                      </VField>
                    </div>
                  </th>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.ora" true-value="Tidak" color="primary" />
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
                            <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                        <div class="column" style="text-align:center;">
                            <h1>Tanda Tangan</h1>
                            <TandaTangan :elemenID="'TTDBidan'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.CBBidan" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
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
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
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

    <!-- <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
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
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
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
      COLLECTION: 'kriteriakeluarperinatologi',
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
  