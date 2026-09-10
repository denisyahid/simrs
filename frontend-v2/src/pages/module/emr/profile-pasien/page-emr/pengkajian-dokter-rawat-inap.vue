<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <!-- <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div> -->
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                Kembali
              </VButton>
              <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer" @click="print()">
                Cetak
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
              </VButton>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div class="column">
    <VCard>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="A. ANAMNESIS" :toggleable="true">
          <div class="columns is-multiline" v-for="(data, index) in anamnesis">
            <div :class="'column p-3 is-' + data.column" v-if="data.type == 'textarea'">
              <p class="mb-2">{{ data.label }}</p>
              <VField>
                <VControl>
                  <VTextarea v-model="input[data.model]" rows="3" :placeholder="data.placeholder">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-3" v-if="data.type == 'checkbox'">
              <div class="column is-12" style="margin-top:0.5rem">
                <span> {{ data.label }} : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(checkBox, check) in data.children">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.statusGizi" :true-value="checkBox.value" :label="checkBox.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="B. PEMERIKSAAN FISIK" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> KEADAAN UMUM :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in KeadaanUmum">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.KeadaanUmum" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> KESADARAN :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in Kesadaran">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Kesadaran" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> KEPALA :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in kepala">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kepala" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketKepala" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> MATA :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in mata">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.mata" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketMata" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> HIDUNG :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in hidung">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.hidung" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketHidung" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> GIGI & MULUT :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in gigiMulut">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.gigiMulut" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketGigiMulut" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> TENGGOROKAN :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in tenggorokan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.tenggorokan" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketTenggorokan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> TELINGA :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in telinga">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.telinga" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketTelinga" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> LEHER :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in leher">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.leher" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketLeher" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> THORAKS :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in thoraks">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.thoraks" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketThoraks" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> JANTUNG :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in jantung">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.jantung" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketJantung" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> PARU :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in paru">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.paru" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketParu" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> ABDOMEN :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in abdomen">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.abdomen" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketAbdomen" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> GENITALIA & ANUS :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in genitaliaAnus">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.genitaliaAnus" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... "
                          v-model="input.ketGenitaliaAnus" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> EKSTREMITAS :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in ekstremitas">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.ekstremitas" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketEkstremitas" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> KULIT :</span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2" v-for="(data, i) in kulit">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kulit" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="............... " v-model="input.ketKulit" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="C. HASIL PEMERIKSAAN PENUNJANG" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <span> Laboratorium : </span>
                <VField>
                  <VTextarea rows="2" placeholder="Laboratorium....." v-model="input.laboratorium"></VTextarea>
                </VField>
              </div>
              <div class="column is-12">
                <span> Radiologi : </span>
                <VField>
                  <VTextarea rows="2" placeholder="Radiologi......" v-model="input.radiologi">
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-12">
                <span> Penunjang Lain : </span>
                <VField>
                  <VTextarea rows="2" placeholder="Penunjang Lain......" v-model="input.penunjangLain">
                  </VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="D. DIAGNOSIS/ASSESMENT" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VTextarea rows="3" placeholder="....." v-model="input.diagnosisAssesment"></VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="E. MASALAH MEDIS DAN KEPERAWATAN" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VTextarea rows="3" placeholder="....." v-model="input.masalahMedisKeperawatan"></VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="F. TATA LAKSANA (EDUKASI, PEMERIKSAAN DIAGNOSTIK, TERAPI)"
          :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VTextarea rows="3" placeholder="....." v-model="input.tataLaksana"></VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="G. INSTRUKSI" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VTextarea rows="3" placeholder="....." v-model="input.instruksi"></VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="H. SASARAN" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VTextarea rows="3" placeholder="....." v-model="input.sasaran"></VTextarea>
                </VField>
              </div>
              <div class="column is-4">
                <span> Rencana Lamat Rawat : </span>
                <VField>
                  <VTextarea rows="1" placeholder="Rencana Lama Rawat......" v-model="input.rencanaLamaRawat">
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-4">
                <span> Rencana Tanggal Pulang : </span>
                <VField>
                  <VDatePicker v-model="input.tanggalRencanaPulang" mode="date" style="width: 100%" >
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" readonly />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-4 mt-5">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.belumBisaDitetapkan" true-value="belumBisaDitetapkan"
                      label="Belum Bisa Ditetapkan" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-4">
            <h1 style="font-weight: bold;">Tanggal dan Jam</h1>
            <VDatePicker v-model="input.tglPembuatan" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-4 is-offset-2">
            <h1 style="font-weight: bold;">Tanggal Edukasi</h1>
            <VDatePicker v-model="input.tglEdukasi" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-6">
            <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
          </div>
          <div class="column is-6 ">
            <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
          </div>
          <div class="column is-4">
            <h1 class="p-0" style="font-weight: bold;">Dokter</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dokterPengkajian" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Dokter..." class="mt-2" @item-select="setTandaTangan($event)" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4 is-offset-2">
            <h1 class="p-0" style="font-weight: bold;">Pasien/Keluarga</h1>
            <VField>
              <VControl>
                <VInput type="text" class="input prime-auto" placeholder="Pasien/Keluarga" v-model="input.namapasien"
                  :suggestions="d_Pasien" @complete="fetchPasien($event)" />
              </VControl>
            </VField>
          </div>
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
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/pengkajian-dokter-awal-ri'



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let pengkajianUmum = ref(EMR.pengkajianUmum())
let anamnesis = ref(EMR.anamnesis())
let KeadaanUmum = ref(EMR.KeadaanUmum())
let Kesadaran = ref(EMR.Kesadaran())
let kepala = ref(EMR.kepala())
let mata = ref(EMR.mata())
let hidung = ref(EMR.hidung())
let gigiMulut = ref(EMR.gigiMulut())
let tenggorokan = ref(EMR.tenggorokan())
let telinga = ref(EMR.telinga())
let leher = ref(EMR.leher())
let thoraks = ref(EMR.thoraks())
let jantung = ref(EMR.jantung())
let paru = ref(EMR.paru())
let abdomen = ref(EMR.abdomen())
let genitaliaAnus = ref(EMR.genitaliaAnus())
let ekstremitas = ref(EMR.ekstremitas())
let kulit = ref(EMR.kulit())
let pemeriksaanFisik1 = ref(EMR.pemeriksaanFisik1())

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
const d_Pasien: any = ref([])
const d_Ruangan: any = ref([])
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
        if (response[0].tandaTanganDokter) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDokter)
        }
        if (response[0].tandaTanganPasien) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPasien)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}

const print = async () => {
    // await useApi().get(`emr/cetak?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    H.printBlade(`emr/cetak-pengkajian-dokter-ri?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganDokter = H.tandaTangan().get("signature_1")
  object.tandaTanganPasien = H.tandaTangan().get("signature_2")
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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.tandaTanganDokter)
    input.value.tandaTanganDokter = response.tandaTanganDokter
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}
const fetchPasien = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pasien_m?select=id,namapasien&param_search=namapasien&query=${filter.query}&limit=10`)
  d_Pasien.value = response
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
  input.value.namapasien = props.pasien.namapasien

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
