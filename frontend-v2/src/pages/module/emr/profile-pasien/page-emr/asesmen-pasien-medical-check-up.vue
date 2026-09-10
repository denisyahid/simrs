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
      <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
      <div class="columns is-multiline mt-2 ml-1 mr-1" v-else>
            <VCard>
                <VTabs slider centered selected="perawat" :tabs="[
                    { label: 'Medis', value: 'medis' },
                    { label: 'Perawat', value: 'perawat' }]">
                    <template #tab="{ activeValue }">
                        <div v-if="activeValue == 'perawat'">
                            <div class="columns is-multiline p-1">
                              <div class="column is-12">
                                 <VCard>
                                  <div class="columns is-multiline">
                                    <div class="column is-4">
                                      <VField label="Tgl/Jam Kunjungan">
                                        <VControl class="prime-auto">
                                          <Calendar  v-model="input.tanggalKunjungan" selectionMode="single" :manualInput="false"
                                              class="w-100 mb-4 " :showIcon="true"  :dateFormat="H.dateTimeFormat().prime.date" inputId="single"  :hideOnRangeSelection="true"  />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-4">
                                      <VField label="Usia Saat Kunjungan">
                                        <VControl>
                                          <VInput v-model="input.umur"></VInput>
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-4">
                                      <VField class="is-autocomplete-select" label="PPJA">
                                        <VControl icon="feather:search">
                                          <AutoComplete v-model="input.perawatfk" :suggestions="d_Perawat" @complete="fetchPerawat($event)"
                                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="ketik Nama Perawat" />
                                        </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                 </VCard>
                              </div>
                              <div class="column is-12">
                                <Fieldset :toggleable="true" legend="ANAMNESIS">
                                  <div class="columns is-multiline">
                                    <div :class="'column ' + an.column" :key="index" v-for="(an,index) in ANAMNESIS">
                                      <VField :label="an.field">
                                         <VControl raw subcontrol v-if="an.type == 'checkbox'">
                                            <VCheckbox :true-value="true" :label="an.label" class="p-0" color="primary" square
                                              v-model="input[an.name]" />
                                          </VControl>
                                        <VInput v-if="an.type == 'input'" v-model="input[an.name]" :placeholder="an.placeholder"></VInput>
                                        <VTextarea v-if="an.type == 'textarea'" :rows="an.row" v-model="input[an.name]" :placeholder="an.placeholder"></VTextarea>
                                      </VField>
                                    </div>
                                  </div>
                                </Fieldset>
                                <Fieldset :toggleable="true" legend="KHUSUS TENAGA KERJA">
                                  <div class="columns is-multiline">
                                    <div :class="'column ' + kh.column" :key="index" v-for="(kh,index) in KHUSUSTENAGAKERJA">
                                      <VField :label="kh.field" v-if="kh.name == 'khususTenagaKerja' || input.khususTenagaKerja == true ">
                                         <VControl raw subcontrol v-if="kh.type == 'checkbox'">
                                            <VCheckbox :true-value="true" :label="kh.label" class="p-0" color="primary" square
                                              v-model="input[kh.name]" />
                                          </VControl>
                                        <VInput v-if="kh.type == 'input'" v-model="input[kh.name]" :placeholder="kh.placeholder"></VInput>
                                        <VTextarea v-if="kh.type == 'textarea'" :rows="kh.row" v-model="input[kh.name]" :placeholder="kh.placeholder"></VTextarea>
                                      </VField>
                                    </div>
                                  </div>
                                </Fieldset>
                                <Fieldset :toggleable="true" legend="STATUS SOSIAL EKONOMI KULTURAL SPIRITUAL">
                                  <div class="columns is-multiline">
                                     <div :class="'column ' + ss.column" :key="index" v-for="(ss,index) in STATUSSOSIAL">
                                      <VField :label="ss.field">
                                        <VInput v-if="ss.type == 'input'" v-model="input[ss.name]" :placeholder="ss.placeholder"></VInput>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField>
                                        <p>Adakah faktor tradisi/ budaya/keyakinan yang berkaitan dengan pelayanan kesehatan:</p>
                                        <div class="columns is-multiline">
                                          <div class="column is-4">
                                            <VControl raw subcontrol>
                                              <VCheckbox :true-value="false" label="Tidak" class="p-0 mt-2" color="primary" square
                                                  v-model="input.adaFaktorTradisi" />
                                              </VControl>
                                          </div>
                                          <div class="column is-4">
                                            <VControl raw subcontrol>
                                              <VCheckbox :true-value="true" label="Ya,Jelaskan." class="p-0 mt-2" color="primary" square
                                                v-model="input.adaFaktorTradisi" />
                                            </VControl>
                                          </div>
                                        </div>
                                      </VField>
                                      <VField v-if="input.adaFaktorTradisi == true">
                                        <VControl>
                                          <VInput v-model="input.FaktorTradisi" placeholder="faktor tradisi/ budaya/keyakinan yang berkaitan dengan pelayanan kesehatan"></VInput>
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField>
                                        <p>Hambatan komunikasi:</p>
                                        <div class="columns is-multiline">
                                          <div class="column is-4">
                                            <VControl raw subcontrol>
                                              <VCheckbox :true-value="false" label="Tidak" class="p-0 mt-2" color="primary" square
                                                  v-model="input.adaHambatanMedis" />
                                              </VControl>
                                          </div>
                                          <div class="column is-4">
                                            <VControl raw subcontrol>
                                              <VCheckbox :true-value="true" label="Ya,Jelaskan." class="p-0 mt-2" color="primary" square
                                                v-model="input.adaHambatanMedis" />
                                            </VControl>
                                          </div>
                                        </div>
                                      </VField>
                                      <VField v-if="input.adaHambatanMedis == true">
                                        <VControl>
                                          <VInput v-model="input.hambatanMedis" placeholder="Hambatan Medis"></VInput>
                                        </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                </Fieldset>
                                <Fieldset :toggleable="true" legend="STATUS PSIKOLOGI">
                                  <div class="columns is-multiline">
                                    <div :class="'column ' + sp.column" :key="index" v-for="(sp,index) in STATUSPSIKOLOGI">
                                      <VField :label="sp.field">
                                         <VControl raw subcontrol v-if="sp.type == 'checkbox'">
                                            <VCheckbox :true-value="true" :label="sp.label" class="p-0" color="primary" square
                                              v-model="input[sp.name]" />
                                          </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                </Fieldset>
                                <Fieldset :toggleable="true" legend="Tanda tanda vital">
                                  <div class="columns is-multiline">
                                    <div class="column is-3" :key="index" v-for="(data ,index) in TANDAVITAL">
                                      <p class="mb-3">{{ data.title }}</p>
                                      <VField addons v-if="data.satuan">
                                        <VControl expanded>
                                          <VInput type="text" class="input" v-model="input[data.model]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ data.satuan }}</VButton>
                                        </VControl>
                                      </VField>
                                      <VField v-else>
                                        <VControl raw subcontrol>
                                          <input v-model="input[data.model]" class="input" />
                                        </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                  <VCard>
                                    <div class="column is-multiline">
                                      <h1 style="font-weight:500">Glasgow Coma Scale ( GCS )</h1>
                                      <div class="column is-12">
                                        <div class="column is-12" style="border-bottom: solid 1px;">
                                            <div class="columns is-multiline">
                                                <div class="column is-1">
                                                    <h1 class="emr">No</h1>
                                                </div>
                                                <div class="column is-4">
                                                    <h1 class="emr">Parameter</h1>
                                                </div>
                                                <div class="column is-6">
                                                    <h1 class="emr">Pengkajian</h1>
                                                </div>
                                                <div class="column">
                                                    <h1 class="emr">Nilai</h1>
                                                </div>
                                            </div>
                                            <div class="column is-12">
                                              <div class="columns is-multiline" :key="index" v-for="(gg,index) in GLASGOWS">
                                                <div class="column is-1">
                                                  <h1 class="emr">{{ index + 1 }}</h1>
                                                </div>
                                                <div class="column is-4">
                                                  <h1 class="emr">{{ gg.label }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                  <VField class="">
                                                    <div class="column is-12 pb-0" :key="index" v-for="(value,index) in gg.children">
                                                      <VControl raw subcontrol>
                                                        <VCheckbox v-model="input[gg.model]" class="p-0"
                                                          :true-value="value" :label="value.label"
                                                          color="primary" circle />
                                                      </VControl>
                                                    </div>
                                                  </VField>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" :key="index" v-for="(value,index) in gg.children">
                                                        <h1 class="emr">{{ value.score }}</h1>
                                                    </div>
                                                </div>
                                              </div>
                                            </div>
                                        </div>
                                      </div>
                                        <div class="column is-3" style="margin-left: auto;">
                                            <VField label="Jumlah Total">
                                                <VControl raw subcontrol>
                                                    <input v-model="input.jumlahNilai" class="input" disabled />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                  </VCard>
                                </Fieldset>
                                <Fieldset :toggleable="true" legend="FUNGSIONAL">
                                  <div class="columns is-multiline">
                                    <div :class="'column ' + data.column" v-for="(data,index) in FUNGSIONAL" :key="index">
                                      <VField :label="data.field">
                                        <VControl>
                                          <VInput v-if="data.type == 'input'" v-model="input[data.name]" :placeholder="data.placeholder"></VInput>
                                        </VControl>
                                        <div class="columns is-multiline" v-if="data.type == 'checkbox'">
                                          <div class="column is-4" v-for="(data2,index2) in data.children" :key="index2">
                                            <VControl raw subcontrol>
                                              <VCheckbox v-model="input[data.model]" class="p-0"
                                                :true-value="data2" :label="data2.label"
                                                color="primary" circle />
                                              </VControl>
                                          </div>
                                        </div>
                                      </VField>
                                    </div>
                                  </div>
                                </Fieldset>
                                <Fieldset :toggleable="true" legend="RISIKO JATUH">
                                  <div class="columns is-multiline p-3">
                                    <h1 style="font-weight:bold;font-size:14px">SKRINING RISIKO CEDERA/ JATUH (Usia 13 - 60 Tahun) menggunakan Up and Go Test</h1>
                                     <div class="column is-12">
                                        <div class="column is-12" style="border-bottom: solid 1px;">
                                            <div class="columns is-multiline">
                                                <div class="column is-5">
                                                    <h1 class="emr">Kategori Indikator Penilaian</h1>
                                                </div>
                                                <div class="column is-6">
                                                    <h1 class="emr">Penilaian</h1>
                                                </div>
                                                <div class="column">
                                                    <h1 class="emr">Nilai</h1>
                                                </div>
                                                <div class="column is-12">
                                                 <div class="columns is-multiline" :key="index" v-for="(gg,index) in RESIKOJATUH">
                                                  <div class="column is-5">
                                                    <h1 class="emr">{{ gg.label }}</h1>
                                                  </div>
                                                  <div class="column is-5 pt-0">
                                                    <VField class="">
                                                      <div class="column is-12 pb-0" :key="index" v-for="(value,index) in gg.children">
                                                        <VControl raw subcontrol>
                                                          <VCheckbox v-model="input[gg.model]" class="p-0"
                                                            :true-value="value" :label="value.label"
                                                            color="primary" circle />
                                                        </VControl>
                                                      </div>
                                                    </VField>
                                                  </div>
                                                  <div class="column pt-0">
                                                      <div class="column pb-0" :key="index" v-for="(value,index) in gg.children">
                                                          <h1 class="emr">{{ value.score }}</h1>
                                                      </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                        </div>
                                     </div>
                                     <div class="column is-9">

                                     </div>
                                     <div class="column is-3" style="margin-left: auto;">
                                      <VField label="Jumlah">
                                        <VControl raw subcontrol>
                                          <input v-model="input.jumlahResikoJatuh" class="input" disabled />
                                        </VControl>
                                      </VField>
                                     </div>
                                     <div class="column is-4">
                                      <VField label="Hasil">
                                      </VField>
                                      <VField>
                                        <VControl raw subcontrol>
                                          <div class="column is-12 pb-0">
                                            <VControl raw subcontrol>
                                              <VCheckbox v-model="input.hasilResikoJatuh" class="p-0"
                                                true-value="TidakBerisiko" label="Tidak Berisiko"
                                              color="primary" circle />
                                            </VControl>
                                          </div>
                                        </VControl>
                                      </VField>
                                      <VField>
                                        <VControl raw subcontrol>
                                          <div class="column is-12 pb-0">
                                            <VControl raw subcontrol>
                                              <VCheckbox v-model="input.hasilResikoJatuh" class="p-0"
                                                true-value="ResikoRendah" label="Resiko Rendah"
                                              color="primary" circle />
                                            </VControl>
                                          </div>
                                        </VControl>
                                      </VField>
                                      <VField>
                                        <VControl raw subcontrol>
                                          <div class="column is-12 pb-0">
                                            <VControl raw subcontrol>
                                              <VCheckbox v-model="input.hasilResikoJatuh" class="p-0"
                                                true-value="ResikoTinggi" label="Resiko Tinggi"
                                              color="primary" circle />
                                            </VControl>
                                          </div>
                                        </VControl>
                                      </VField>
                                     </div>
                                     <div class="column is-4">
                                      <VField label="Keterangan">
                                        <VInput v-model="input.keteranganResiko"></VInput>
                                      </VField>
                                     </div>
                                  </div>
                                </Fieldset>
                                <Fieldset :toggleable="true" legend="MASALAH KEPERAWATAN">
                                  <div class="columns is-multiline p-3">
                                    <div class="column is-6" :key="index" v-for="(data,index) in MASALAHKEPERAWATAN">
                                      <VField>
                                        <VControl raw subcontrol>
                                          <div class="column is-12 pb-0">
                                            <VControl raw subcontrol>
                                              <VCheckbox v-model="input[data.model + index]" class="p-0"
                                                :true-value="data.label" :label="data.label"
                                              color="primary" />
                                            </VControl>
                                          </div>
                                        </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                </Fieldset>
                                <Fieldset :toggleable="true" legend="MASALAH KEPERAWATAN">
                                  <div class="columns is-multiline p-3">
                                    <div class="column is-6" :key="index" v-for="(data,index) in TINDAKANKEPERWATAN">
                                      <VField>
                                        <VControl raw subcontrol>
                                          <div class="column is-12 pb-0">
                                            <VControl raw subcontrol>
                                              <VCheckbox v-model="input[data.model + index]" class="p-0"
                                                :true-value="data.label" :label="data.label"
                                              color="primary" />
                                            </VControl>
                                          </div>
                                        </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                </Fieldset>
                              </div>
                                 <div class="column is-6">
                                    <VCard class="border-card pink">
                                        <div class="columns is-multiline">
                                            <div class="column is-6">
                                                <h1 class="emr">Tanggal dan Jam</h1>
                                                <VField>
                                                    <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
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
                                            <div class="column is-6">
                                                <h1 class="emr">Perawat</h1>
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:search">
                                                        <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai"
                                                            :attr={id}
                                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            placeholder="Cari Perawat" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </VCard>
                                  </div>
                            </div>
                        </div>
                        <div v-if="activeValue == 'medis'">
                           <div class="columns is-multiline p-1">
                              <div class="column is-12">
                                 <VCard>
                                  <div class="columns is-multiline">
                                    <div class="column is-4">
                                      <VField label="Tgl/Jam Kunjungan">
                                        <VControl class="prime-auto">
                                          <Calendar  v-model="input.medisTanggalKunjungan" selectionMode="single" :manualInput="false"
                                              class="w-100 mb-4 " :showIcon="true"  :dateFormat="H.dateTimeFormat().prime.date" inputId="single"  :hideOnRangeSelection="true"  />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-4">
                                      <VField label="Usia Saat Kunjungan">
                                        <VControl>
                                          <VInput v-model="input.medisUmur"></VInput>
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-4">
                                      <VField class="is-autocomplete-select" label="PPJA">
                                        <VControl icon="feather:search">
                                          <AutoComplete v-model="input.medisPerawatfk" :suggestions="d_Perawat" @complete="fetchPerawat($event)"
                                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="ketik Nama Perawat" />
                                        </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                 </VCard>
                              </div>
                              <div class="column is-12" :key="index" v-for="(data,index) in PERAWAT">
                                 <Fieldset :toggleable="true" :legend="data.title">
                                  <div class="columns is-multiline">
                                    <div :class="'column ' + data2.column" v-for="(data2,index2) in data.children" :key="index2">
                                       <VField :label="data2.field">
                                        <VControl>
                                          <VInput v-if="data2.type == 'input'" v-model="input[data2.name]" :placeholder="data2.placeholder"></VInput>
                                          <VTextarea v-if="data2.type == 'textarea'" rows="2" v-model="input[data2.name]" :placeholder="data2.placeholder"></VTextarea>
                                        </VControl>
                                        <div class="columns is-multiline" v-if="data2.type == 'checkbox'">
                                          <div class="column is-4" v-for="(data3,index3) in data2.children" :key="index3">
                                            <VControl raw subcontrol>
                                              <VCheckbox v-model="input[data2.name]" class="p-0"
                                                :true-value="data3" :label="data3.label"
                                                color="primary" circle />
                                              </VControl>
                                          </div>
                                        </div>
                                      </VField>
                                    </div>
                                  </div>
                                 </Fieldset>
                              </div>
                              <div class="column is-4" style="margin-left: auto;">
                                <VCard>
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
                                  <div>
                                    <TandaTangan :elemenID="'signature_dokter'" :width="'180'" :height="'180'"></TandaTangan>
                                  </div>
                                  <div>
                                    <h1 class="p-0" style="font-weight: bold;">Nama Dokter</h1>
                                    <VField>
                                      <VControl class="prime-auto">
                                        <AutoComplete v-model="input.medisPerawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                          :field="'label'" placeholder="Pegawai..." class="mt-2" @item-select="setTandaTangan($event,'signature_dokter')" />
                                      </VControl>
                                    </VField>
                                  </div>
                                </VCard>
                              </div>
                           </div>
                        </div>
                    </template>
                </VTabs>
            </VCard>
      </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted,onBeforeMount } from 'vue'
import { onBeforeRouteLeave, useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import Calendar from 'primevue/calendar';
import * as EMR from '../page-emr-plugins/asesmen-pasien-medical-check-up'
import moment from 'moment'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let asesmenPasienMedicalCheckUp = ref(EMR.asesmenPasienMedicalCheckUp())
let ANAMNESIS = ref(EMR.ANAMNESIS())
let KHUSUSTENAGAKERJA = ref(EMR.KHUSUSTENAGAKERJA())
let STATUSSOSIAL = ref(EMR.STATUSSOSIAL())
let STATUSPSIKOLOGI = ref(EMR.STATUSPSIKOLOGI())
let TANDAVITAL = ref(EMR.TANDAVITAL())
let GLASGOWS = ref(EMR.GLASGOWS())
let FUNGSIONAL = ref(EMR.FUNGSIONAL())
let RESIKOJATUH = ref(EMR.RESIKOJATUH())
let MASALAHKEPERAWATAN = ref(EMR.MASALAHKEPERAWATAN())
let TINDAKANKEPERWATAN = ref(EMR.TINDAKANKEPERWATAN())
let PERAWAT = ref(EMR.PERAWAT())
const loadData: any = ref(true)
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
const route = useRoute()
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  umur : props.pasien.umur,
  medisUmur : props.pasien.umur,
  suku : props.pasien.suku,
  pendidikan : props.pasien.pendidikan,
  agama : props.pasien.agama,
  kebangsaan : props.pasien.kebangsaan,
  tanggalKunjungan :moment(props.registrasi.tglregistrasi || new Date()).format("DD-MM-YYYY hh:mm:ss") ,
  medisTanggalKunjungan :moment(props.registrasi.tglregistrasi || new Date()).format("DD-MM-YYYY hh:mm:ss") ,
  perawatfk :  { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  medisPerawatfk :  { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk },
  tanggal : new Date(),
  tglPembuatan : new Date(),
})
const d_Perawat : any = ref([])
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {

  loadData.value = true
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0]
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }
  loadData.value = false
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
const fetchPerawat = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}
const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}
watch(() => [
    input.value.responVerbal,
    input.value.responMotorik,
    input.value.responBukaMata,
], () => {
    let poin1 = input.value.responVerbal ? parseInt(input.value.responVerbal.score) : 0
    let poin2 = input.value.responMotorik ? parseInt(input.value.responMotorik.score) : 0
    let poin3 = input.value.pergerakan ? parseInt(input.value.responBukaMata.score) : 0
    const total = poin1 + poin2 + poin3
    input.value.jumlahNilai = total
})
watch(() => [
    input.value.caraJalanSempoyongan,
    input.value.memegangangKursiSaatJalan,
], () => {
    let poin1 = input.value.caraJalanSempoyongan ? parseInt(input.value.caraJalanSempoyongan.score) : 0
    let poin2 = input.value.memegangangKursiSaatJalan ? parseInt(input.value.memegangangKursiSaatJalan.score) : 0
    const total = poin1 + poin2
    input.value.jumlahResikoJatuh = total
})
const setTandaTangan = async (e: any ,signature :any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set(signature, response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set(signature, '')
  }
}
onBeforeMount(async () => {
  try {
    await loadRiwayat()
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

h1.emr {
    font-weight: bold;
}
</style>
