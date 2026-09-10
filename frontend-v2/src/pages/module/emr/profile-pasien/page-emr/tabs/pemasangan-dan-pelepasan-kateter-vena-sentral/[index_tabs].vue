<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }} {{ route.params.index_tabs }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpanTemplate="simpanTemplate"
                            @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
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
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Input</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Registrasi</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">No Registrasi</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">No EMR</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="20%">Halaman</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Section</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="5%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.index_tabs }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addRiwayat(resep)" color="info" v-tooltip-prime.top="'Pilih'">
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
                        <table style="border: 1px solid black;" v-if="listTemplateFix.length > 0">
                            <thead>
                                <tr>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="5%">No</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Dibuat</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="20%">Nama Ruangan</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="25%">Nama Template</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplateFix">
                                <tr>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.no }}</span><br>
                                    </td>
                                    <td
                                        style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td
                                        style="width:20%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td
                                        style="width:25%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.namatemplate }}</span><br>
                                    </td>
                                    <td
                                        style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;padding: 3px;">
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
    </VModal>

    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
            <VCard v-else>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                                isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                            </VButton>
                            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                                isLoading="false" @click="pilihTemplate(index)"> Pilih Riwayat
                            </VButton>
                        </div>

                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>

                        <div class="column is-12">
                            <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                                    template</span></h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.namatemplate" rows="1">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>

                    </div>
                    <div class="container">
                        <table class="table is-bordered is-fullwidth">
                            <thead>
                                <tr>
                                  <th colspan="5">
                                      <p style="font-size:20px; font-weight: bold;">Dipasang Oleh :</p>
                                      <VControl class="prime-auto">
                                      <div>
                                      <span style="font-size:15px;">Pegawai :
                                      <AutoComplete v-model="input.CBBidan" :suggestions="d_Dokter" 
                                          @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                          :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                          style="width: 17rem; margin-left:90px;"
                                          class="mt-2" />
                                      </span>
                                      </div>
                                      <div style="display: flex; align-items: center;margin-top: 1rem;">
                                          <span style="font-size:15px;">Jabatan : </span>
                                      
                                      <VField style="max-width: 20rem;margin-left: 7rem">
                                          <VTextarea v-model="input.jabatan" placeholder="Sebutkan" color="primary" rows="1" />
                                          </VField>
                                      
                                      </div>
                                      <div>
                                      <h1 style="position:relative; top:5rem; ">Tanda Tangan :</h1>
                                      <TandaTangan :elemenID="'TTDPegawai'" :width="'150'" :height="'150'" class="dek" style="margin-left:12rem;" />
                                      
                                  </div>
                                  </VControl>
                                  </th>
                              </tr>
                            </thead>
      
                            <thead>
                               <tr>
                                 <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">PEMASANGAN
                              </th>
                            </tr>
                            <tr>
                              <th colspan ="3">
                            <div class="columns is-multiline" style="margin-left: 10px">
                               <h1 class="mb-3" style="font-weight: bold; position: relative; top: 20px;">Tanggal</h1>
                                      <div class="column is-4">
                                         
                                          <VField>
                                              <VDatePicker v-model="input.tanggalpasang" mode="date" trim-weeks
                                                >
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
      
                                      <h1 class="mb-3" style="font-weight: bold; position: relative; top: 20px;">Jam </h1>
                                      <div class="column is-4">
                                          
                                          <VField>
                                              <VDatePicker v-model="input.jampasang" mode="time" style="width: 100%"
                                                  trim-weeks>
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
                                      <h1 class="mb-3" style="font-weight: bold; position: relative; top: 20px;">Wita </h1>
                                  </div>
                              </th>
                          </tr>
                          
                            </thead>
                            <thead>
                            <tr>
                          <th>Pemasangan sebelum MRS
                              <div style="margin-left:10px">
                                  <span> YA                       
                                      <VCheckbox v-model="input.MRS" true-value="Ya" color="primary" />   
                                  </span>
                                  <span> TIDAK
                                      <VCheckbox v-model="input.MRS1" true-value="Tidak" color="primary" />
                                  </span>
                                      <VField>              
                                          <VTextarea v-model="input.mrs" placeholder="Sebutkan" color="primary" rows="1"/>
                                      </VField>
                              </div>
                          </th>
                          <th colspan="2">Jumlah Insersi
                              <VField style="margin-top:50px">              
                                  <VTextarea v-model="input.jumlahinsersi" placeholder="Sebutkan" color="primary" rows="1" />
                              </VField>
                          </th>                  
                              </tr>
      
                              <tr>
                                  <th class="yes-column">
                                  <div>
                                      <h1 style="font-size:17px; font-weight:bold; margin-bottom:16px;">Tipe</h1>
                                  </div>
                                  <div>
                                      <span>                       
                                      <VCheckbox v-model="input.uac" true-value="Ya" color="primary" />   
                                      UAC
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.uvc" true-value="Ya" color="primary" />   
                                      UVC
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.single" true-value="Ya" color="primary" />   
                                      Single Lumen
                                      </span>
                                  </div>
                                  <div>
                                      <span>                       
                                      <VCheckbox v-model="input.double" true-value="Ya" color="primary" />   
                                      Double Lumen
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.picc" true-value="Ya" color="primary" />   
                                      PICC
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.pacc" true-value="Ya" color="primary" />   
                                      PAC
                                      </span>
                                  </div>
                                  <div>
                                      <div style="display: flex; align-items: center; gap: 8px;">
                                      <span>                       
                                      <VCheckbox v-model="input.lain" true-value="Ya" color="primary" />   
                                      Lain-lain
                                      </span>
                                      <VField style="max-width: 800px;">
                                          <VTextarea v-model="input.lain1" placeholder="Sebutkan" color="primary" rows="1" />
                                          </VField>
                                      </div>
                                  </div>
                                  </th>
                                  <th colspan="2">
                                      <h1 style="font-size:17px; font-weight:bold; margin-bottom:16px;">Kateter</h1>
                                      <div style="display: flex; flex-direction: column; gap: 12px;">
                                      
                                      <div style="display: flex; align-items: center; gap: 20px;">
                                          <span>Ukuran:</span>
                                          <VField style="max-width: 500px; position:relative; left:6px;">
                                          <VTextarea v-model="input.ukur" placeholder="Sebutkan" color="primary" rows="1" />
                                          </VField>
                                      </div>
      
                                      <div style="display: flex; align-items: center; gap: 20px;">
                                          <span>Panjang:</span>
                                          <VField style="max-width: 500px;">
                                          <VTextarea v-model="input.panjang" placeholder="Sebutkan" color="primary" rows="1" />
                                          </VField>
                                      </div>
      
                                      <div style="display: flex; align-items: center; gap: 20px;">
                                          <span>Lokasi:</span>
                                          <VField style="max-width: 500px; position:relative; left:11px;">
                                          <VTextarea v-model="input.lokasi" placeholder="Sebutkan" color="primary" rows="1" />
                                          </VField>
                                      </div>
                                      
                                      </div>
                                  </th>
                                  </tr>
                            </thead>
      
                            <thead>
                            <tr>
                            <th style="width:50%;">Konfirmasi X-Ray
                              <div>
                                  <span>                       
                                      <VCheckbox v-model="input.xray" true-value="Ya" color="primary" />   
                                      TIDAK
                                  </span>
                                  <span>                       
                                      <VCheckbox v-model="input.xray1" true-value="Ya" color="primary" />   
                                      YA
                                  </span>
                                  <span>                       
                                      <VCheckbox v-model="input.xray2" true-value="Ya" color="primary" />   
                                      N/A
                                  </span>
                              </div>
                            </th>
                            <th  colspan="2">Penarikan Kateter
                              <div>
                                      <div style="display: flex; align-items: center; gap: 10px;">
                                          <span>                       
                                          <VCheckbox v-model="input.penkat" true-value="Ya" color="primary" />   
                                          Tidak
                                           </span>
                                      <span>                       
                                      <VCheckbox v-model="input.penkat1" true-value="Ya" color="primary" />   
                                      YA
                                      </span>
                                      <VField style="max-width: 800px; gap: 20px;">
                                          <VTextarea v-model="input.penkat2" placeholder="Sebutkan" color="primary" rows="1" />
                                          </VField>
                                          <span>cm</span>
                                      </div>
                                  </div>
                            </th>
                          </tr>
      
                          <tr>
                            <th  colspan="3">Kode Produksi Kateter <i style="font-size:10px;">(Tempelkan Stiker)</i>
                              <VField>
                                          <VTextarea v-model="input.prod" placeholder="Sebutkan" color="primary" />
                                          </VField>
                          </th>
                          </tr>
                            </thead>
      
                            <thead>
                              <tr>
                                 <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">PELEPASAN
                                  </th>
                              </tr>
                              <tr>
                                  <th colspan="2">
                                      <span style="font-size:20px; font-weight: bold;">Dilepas Oleh :</span>
                                      <VControl class="prime-auto">
                                      <div>
                                      <span style="font-size:15px;">Pegawai :
                                      <AutoComplete v-model="input.Lepas" :suggestions="d_Dokter" 
                                          @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                          :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                          style="width: 17rem; margin-left:90px;"
                                          class="mt-2" />
                                      </span>
                                      </div>
                                      <div style="display: flex; align-items: center;margin-top: 1rem;">
                                          <span style="font-size:15px;">Jabatan : </span>
                                      
                                      <VField style="max-width: 20rem;margin-left: 7rem">
                                          <VTextarea v-model="input.jabatan2" placeholder="Sebutkan" color="primary" rows="1" />
                                          </VField>
                                      
                                      </div>
                                      <div>
                                      <h1 style="position:relative; top:5rem; font-weight: bold; ">Tanda Tangan :</h1>
                                      <TandaTangan :elemenID="'TTDLepas'" :width="'150'" :height="'150'" class="dek" style="margin-left:12rem;" />
                                      
                                  </div>
                                  </VControl>
                                  <div style="margin-top: 3rem;">
                                  <span>
                                      Alasan dilepas : 
                                  </span>
                                  <div>
                                      <span>                       
                                      <VCheckbox v-model="input.lep1" true-value="Ya" color="primary" />   
                                      Tidak diperlukan lagi
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.lep2" true-value="Ya" color="primary" />   
                                      Tersumbat
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.lep3" true-value="Ya" color="primary" />   
                                      Infiltrasi
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.lep4" true-value="Ya" color="primary" />   
                                      Extravasasi
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.lep5" true-value="Ya" color="primary" />   
                                      Malposisi
                                      </span>
                                  </div>
                                  <div style="display: flex; align-items: center; gap: 10px;">
                                      <span>                       
                                      <VCheckbox v-model="input.lep6" true-value="Ya" color="primary" />   
                                      Terlepas
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.lep7" true-value="Ya" color="primary" />   
                                      Bocor
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.lep8" true-value="Ya" color="primary" />   
                                      Lain-lain
                                      </span>
                                      <VField style="max-width: 800px; gap: 20px;">
                                          <VTextarea v-model="input.lep9" placeholder="Sebutkan" color="primary" rows="1" />
                                          </VField>
                                  </div>
                                  </div>
                                  <div style="margin-top: 3rem;">
                                      <span>Suspect Infeksi : </span>
                                      <span>                       
                                      <VCheckbox v-model="input.inf1" true-value="Ya" color="primary" />   
                                      Tidak
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.inf2" true-value="Ya" color="primary" />   
                                      Ya
                                      </span>
                                  </div>
      
                                  <div style="display: flex; align-items: center; gap: 2rem; margin-top: 3rem;">
                                      <span>Blood Kultur : </span>
                                      <span>                       
                                      <VCheckbox v-model="input.kul1" true-value="Ya" color="primary" />   
                                      Tidak
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.kul2" true-value="Ya" color="primary" />   
                                      Ya
                                      </span>
                                      <span>
                                          Lokasi Pengambilan
                                      </span>
                                      <VField style="max-width: 50rem;">
                                          <VTextarea v-model="input.kul2Text" placeholder="Sebutkan" color="primary" rows="1" />
                                          </VField>
                                  </div>
      
                                  <div>
                                      <span>Hasil : </span>
                                      <VField>
                                          <VTextarea v-model="input.kul3" placeholder="Sebutkan" color="primary" />
                                          </VField>
                                  </div>
      
                                  <div style="display: flex; align-items: center; gap: 2rem; margin-top: 3rem;">
                                      <span>Kultur Ujung Kateter :</span>
                                      <span>                       
                                      <VCheckbox v-model="input.uju1" true-value="Ya" color="primary" />   
                                      Tidak
                                      </span>
                                      <span>                       
                                      <VCheckbox v-model="input.uju2" true-value="Ya" color="primary" />   
                                      Ya
                                      </span>
                                      <span>
                                          Hasil
                                      </span>
                                      <VField style="max-width: 50rem;">
                                          <VTextarea v-model="input.uju3" placeholder="Sebutkan" color="primary" rows="1" />
                                          </VField>
                                  </div>
                                  </th>
                              </tr>
                            </thead>
                        </table>
                         <table class="table is-bordered is-fullwidth">
                            <thead>
                              <tr>
                                <th class="grey-background" style="font-size: 19px;background-color: #0000CD; color: #FFFFFF" colspan="5" >Komentar</th>
                              </tr>
                            </thead>
                            <tr>
                              <td colspan="5">
                                  <VField>
                                          <VTextarea v-model="input.kom" placeholder="Sebutkan" color="primary"/>
                              </VField>
                              </td>
                            </tr>
                          </table>
                      </div>
                </div>
            </VCard>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave, onBeforeRouteUpdate } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import TandaTangan from '../../../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
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

const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Dokter = ref([])
const d_Pegawai = ref([])
const d_Diagnosa: any = ref([])
const d_Ruangan: any = ref([])
const dataTTD: any = ref([])
const loadData: any = ref(true)
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
    jampasang: new Date(),
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
    loadData.value = true
    await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`).then((response: any) => {
        if (response.length) {
            input.value = response[0]
            if (NOREC_EMRPASIEN.value == '') {
                NOREC_EMRPASIEN.value = response[0].emrpasienfk
            }
            dataTTD.value = response[0]
            loadData.value = false
            nextTick(() => {
                H.tandaTangan().set("TTDPegawai", dataTTD.value.TTDPegawai)
                H.tandaTangan().set("TTDLepas", dataTTD.value.TTDLepas)
            })
        }
    })
    loadData.value = false
}


const d_Kamar: any = ref([])
const fetchKamarOperasi = async (filter: any) => {
    await useApi().get(
        `/dashboard/list-bedah`
    ).then((response) => {
        d_Kamar.value = response.kamaroperasi
    })
}

const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}

const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
}

const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
}

async function fetchDokter(filter: any) {
    let query = ''
    if (filter) {
        query = filter.query
    }
    const response = await useApi().get(`/general/dokter-paging?name= ${query}&limit=10`)
    // d_Dokters.value =
    d_Dokter.value = response.dokter.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
    })
    // return response.dokter.map((item: any) => {
    //     return { value: item.id, label: item.namalengkap, default: item }
    // })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    object['TTDPegawai'] = H.tandaTangan().get("TTDPegawai");
    object['TTDLepas'] = H.tandaTangan().get("TTDLepas");
    if (route.params.index_tabs) {
        object.index_tabs = parseInt(route.params.index_tabs)
    }
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
            loadRiwayat()
        }).catch((e: any) => {
            isLoading.value = false
        })
}
const kembaliKeun = () => {
    window.history.back()
}

const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('warning', "Nama Template wajib diisi")
        console.log()
        return;
    }
    let ID = input.id ? input.id : ''
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
    input.value = response
    delete input.value['id']
    delete input.value['_id']
    input.value.namatemplate = null
    showModalTemplateFix.value = false
    H.alert('info', 'Template berhasil ditambahkan')
}

const addRiwayat = (response: any) => {
    input.value = response //set ke inputan
    delete input.value.namatemplate;
    delete input.value['_id'];
    showModalTemplate.value = false
    showModalTemplateFix.value = false
}

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
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
const setAutoFill = async () => {

}
onMounted(async () => {
    try {
        await nextTick(); // Ensures the DOM is fully updated before execution
        await loadRiwayat(); // Now runs after the page is rendered

        let rouutename = route.name + '-' + route.params.index_tabs;
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`);

        if (cache) input.value = cache;
    } catch (error) {
        console.error('Error mount cache TAB EMR:', error);
    }
});

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});
watch(
    () => route.params.index_tabs,
    (newValue, oldValue) => {
        input.value = {}
        input.value.jampasang = new Date()
        loadRiwayat()
        let rouutename = route.name + '-' + route.params.index_tabs
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
        if (cache) {
            input.value = cache
        }

    })
watch(
    () => input.value,
    (newValue, oldValue) => {
        let rouutename = route.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
        let timeout = null;
        if (timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(() => {
            H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, newValue);
        }, 500);
    }, { deep: true })
// watch(
//   () => route.params.index_tabs,
//   (newValue, oldValue) => {
//     console.log("watch 1");
//     try {
//       let rouutename = route.name + '-' + route.params.index_tabs
//       H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
//       console.log(`TAB~${props.registrasi.noregistrasi}~${rouutename}`);
//       input.value = {}
//       console.log(input.value);
//     } catch (error) {
//       console.error('Error leave cache TAB EMR:', error);
//     }
//   }
// );
// watch(
//   () => route.params.index_tabs,
//   async (newValue, oldValue) => {
//     console.log("watch 2");
//     input.value = {}
//       try {
//         await loadRiwayat()
//         let rouutename = route.name + '-' + route.params.index_tabs
//         let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
//         console.log(`TAB~${props.registrasi.noregistrasi}~${rouutename}` ,JSON.stringify(cache));
//         console.log(Object.keys(cache).length);
//         if (cache){
//           input.value = cache
//         }else{
//           input.value ={}
//         }
//       } catch (error) {
//         console.error('Error mount cache TAB EMR:', error);
//       }
//     }
// );

setView()
setAutoFill()
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
