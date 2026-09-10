<template>
  <div>
    <div class="form-layout is-stacked-2" style="
    width: 100%;
    max-width: none;">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                  Kembali
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
    <div style="
    padding: 10px 10px 0 10px;max-width: 1380px;
    margin: 0 auto;">
      <div class="form-outer">
        <div class="form-body">
          <div class="columns is-multiline">
            <div class="column is-12 ">
              <VCard>
                <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
                  <div class="tabs-wrapper" :class="['tab-naver']">
                    <div class="tabs-inner">
                      <div class="tabs is-boxed">
                        <ul>
                          <li v-for="(tab, key) in menuEMR" :key="key"
                            :class="[activeValue === tab.value && 'is-active']">
                            <slot name="tab-link" :active-value="activeValue" :tab="tab" :index="key" :toggle="toggle">
                              <a tabindex="0" @keydown.space.prevent="toggle(tab.value)" @click="toggle(tab.value)">
                                <VIcon v-if="tab.icon" :icon="tab.icon" />
                                <span>
                                  <slot name="tab-link-label" :active-value="activeValue" :tab="tab" :index="key">
                                    {{ tab.label }}
                                  </slot>
                                </span>
                              </a>
                            </slot>
                          </li>
                          <li v-if="sliderClass" class="tab-naver"></li>
                        </ul>
                      </div>
                    </div>

                    <div class="tab-content is-active">
                      <Transition :name="'fade-fast'" mode="out-in">
                        <slot name="tab" :active-value="activeValue"></slot>
                      </Transition>
                    </div>
                  </div>
                  <!-- <div class="columns is-multiline">
                                        <div class="column is-4" v-for="items in tabs" :key="items.value">
                                            <VCard class="p-2" style="cursor: pointer; "
                                                @click="item.selectedMenu[items.value] = !item.selectedMenu[items.value]"
                                                :class="item.selectedMenu[items.value] == true ? 'checked-menu' : 'checked-no'">
                                                <div class="tile-grid-items">
                                                    <div class="flex"
                                                        style="justify-content: space-between;align-itemss: end;">
                                                        <h3 style="font-weight: 600;" :for="'check-' + items.value">{{
                                                            items.label
                                                        }}</h3>
                                                        <VControl>
                                                            <VSwitchBlock v-model="item.selectedMenu[items.value]"
                                                                color="info" @change="checkMenu(items)" />
                                                        </VControl>
                                                    </div>
                                                </div>
                                            </VCard>
                                        </div>

                                    </div> -->
                </div>
              </VCard>
            </div>
            <!-- <canvas id="markingsite" width="400px" height="400px"></canvas> -->

            <div class="column is-12 mt-3-min">
              <VCardHead :title="menuEMR[0].label" v-if="item.selectedMenu[0] == true || activeValue == 0"
                class="border-card pink">

                <div class="columns is-multiline p-3">

                  <div class="column is-12">
                    <VField label="Keluhan Utama">
                      <VControl>
                        <VTextarea class="textarea" v-model="input.keluhanUtama" rows="2" placeholder="Keluhan Utama ..."
                          autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField label="Riwayat Penyakit">
                      <VControl>
                        <VTextarea class="textarea" v-model="input.riwayatPenyakit" rows="2"
                          placeholder="Riwayat Penyakit ..." autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField label="Riwayat Pengobatan">
                      <VControl>
                        <VTextarea class="textarea" v-model="input.riwayatPengobatan" rows="2"
                          placeholder="Riwayat Pengobatan ..." autocomplete="off" autocapitalize="off"
                          spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField label="Riwayat Alergi">
                      <VControl>
                        <div class="column is-3 p-0" v-for="items in d_Alergi" :key="items.value">
                          <RadioButton v-model="input.riwayatAlergi" :name="'tes'" :inputId="items.value"
                            :value="items" />
                          <label :for="items.value" class="ml-2 radio" style="padding:0.5rem">{{
                            items.label }}</label>
                        </div>
                        <div class="column is-12">
                          <VField label="Keterangan">
                            <VControl icon="feather:bookmark">
                              <VInput type="text" v-model="input.keteranganAlergi" placeholder="Keterangan..." />
                            </VControl>
                          </VField>
                        </div>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </VCardHead>
              <VCardHead :title="menuEMR[1].label" v-if="item.selectedMenu[1] == true || activeValue == 1"
                class="mt-2 border-card warning">
                <div class="columns is-multiline p-3">
                  <div class="column is-4">
                    <VField label="Jenis Psikologi">
                      <VControl>
                        <div class="columns is-multiline">
                          <div class="column is-6 p-0 mt-1" v-for="items in d_JenisPsikologi" :key="items.value">
                            <RadioButton v-model="input.jenisPsikologi" :name="'tes'" :inputId="items.value"
                              :value="items" />
                            <label :for="items.value" class="ml-2 radio p-1">{{ items.label
                            }}</label>
                          </div>
                          <div class="column is-12">
                            <VField label="Keterangan">
                              <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="input.keteranganJenisPsikologi"
                                  placeholder="Keterangan..." />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="Sosial Ekonomi">
                      <VControl>
                        <VTextarea class="textarea" v-model="input.sosialEkonomi" rows="2"
                          placeholder="Sosial Ekonomi  ..." autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="Spiritual">
                      <VControl>
                        <VTextarea class="textarea" v-model="input.spiritual" rows="2" placeholder="Spiritual  ..."
                          autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </VCardHead>
              <VCardHead :title="menuEMR[2].label" v-if="item.selectedMenu[2] == true || activeValue == 2"
                class="mt-2 border-card danger">
                <div class="columns is-multiline p-3">
                  <div class="column is-12">
                    <VCardHead title="Keadaan Umum" class="border-card info">
                      <div class="columns is-multiline p-3">
                        <div class="column is-3">
                          <VField label="Sadar Baik/alert">
                            <VControl icon="feather:bookmark">
                              <VInput type="number" v-model="input.sadarBaik" placeholder="Sadar Baik/alert"
                                class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="Respon dengan kata-kata/voice">
                            <VControl icon="feather:bookmark">
                              <VInput type="number" v-model="input.responDenganKata"
                                placeholder="Respon dengan kata-kata/voice" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="Respon jika dirangsang nyeri/pain">
                            <VControl icon="feather:bookmark">
                              <VInput type="number" v-model="input.responRangsang"
                                placeholder="Respon jika dirangsang nyeri/pain" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="Pasien Tidak Sadar/unresponsive">
                            <VControl icon="feather:bookmark">
                              <VInput type="number" v-model="input.pasienTidakSadar"
                                placeholder="Pasien Tidak Sadar/unresponsive" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="Gelisah atau bingung">
                            <VControl icon="feather:bookmark">
                              <VInput type="number" v-model="input.gelisahBingung" placeholder="Gelisah atau bingung"
                                class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="Acute confessional states">
                            <VControl icon="feather:bookmark">
                              <VInput type="number" v-model="input.acuteConfessionalStates"
                                placeholder="Acute confessional states" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </VCardHead>
                  </div>
                  <div class="column is-12">
                    <VCardHead title="Vital Sign" class="border-card success">
                      <div class="columns is-multiline p-3">
                        <div class="column is-3">
                          <VField label="Tinggi Badan"></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="Tinggi Badan" v-model="input.tinggiBadan" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>Cm</VButton>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="Berat Badan"></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="Berat Badan" v-model="input.beratBadan" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>Kg</VButton>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="Tekanan Darah"></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="Tekanan Darah"
                                v-model="input.tekananDarah" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>mmHG</VButton>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="Nadi"></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>BPM</VButton>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="Suhu"></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="Suhu" v-model="input.suhu" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>°C </VButton>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="Pernapasan"></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="Pernapasan" v-model="input.pernapasan" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>XPM</VButton>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="SPO2"></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="SPO2" v-model="input.SPO2" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>%</VButton>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="Lingkar Perut"></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="Lingkar Perut"
                                v-model="input.lingkarPerut" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>Cm</VButton>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField label="IMT"></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="IMT" v-model="input.IMT" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static></VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </VCardHead>
                  </div>
                  <div class="column is-12">
                    <VCardHead title="Anatomi Tubuh" class="border-card yellow">
                      <div class="columns is-multiline p-3">
                        <div class="column is-4">
                          <VField label="Pilih Jenis Kelamin"></VField>
                          <div class="columns is-multiline">
                            <div class="column is-6">
                              <div class="form-tambahan-radio">
                                <RadioButton v-model="jenisKelamin" inputId="jk1" name="LAKI-LAKI" value="LAKI-LAKI" />
                                <label for="jk1" class="ml-2">LAKI-LAKI</label>
                              </div>
                            </div>
                            <div class="column is-6">
                              <div class="form-tambahan-radio">
                                <RadioButton v-model="jenisKelamin" inputId="jk2" name="PEREMPUAN" value="PEREMPUAN" />
                                <label for="jk2" class="ml-2">PEREMPUAN</label>
                              </div>
                            </div>
                          </div>

                        </div>
                        <div class="columns is-multiline ">
                          <div class="column is-8 ">
                            <div :style="'background-image:url(' + MARKINGSITE + ')'" style="      text-align: center;

        z-index: 9999;
        background-repeat: no-repeat;
        background-position: center;
        width: 200px;
        height: 600px;">
                              <canvas id="markingsite" height="600" width="200"></canvas>
                            </div>
                            <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                              @click="clearCanvas('markingsite')"> Clear
                            </VButton>
                          </div>
                          <div class="column is-4 ">
                            <Fieldset legend="Status Generalis" :toggleable="true" :collapsed="false" class="mt-2">
                              <div class="columns is-multiline p-2">
                                <div class="column is-12 p-0">
                                  <VControl>
                                    <div class="columns is-multiline">
                                      <div class="column is-3">
                                        <label class="label mt-2">Kepala</label>
                                      </div>
                                      <div class="column is-5">
                                        <VRadio v-model="input.Kepala" :value="'TN'" :label="'Tidak Normal'"
                                          name="TN_kepala" square color="primary" />
                                      </div>
                                      <div class="column is-4">
                                        <VRadio v-model="input.Kepala" :value="'N'" :label="'Normal'" name="TN_kepala"
                                          square color="primary" />
                                      </div>
                                    </div>
                                  </VControl>
                                </div>
                                <div class="column is-12  p-0">
                                  <VControl>
                                    <div class="columns is-multiline">
                                      <div class="column is-3">
                                        <label class="label mt-2">Leher</label>
                                      </div>
                                      <div class="column is-5">
                                        <VRadio v-model="input.Leher" :value="'TN'" :label="'Tidak Normal'"
                                          name="TN_Leher" square color="primary" />
                                      </div>
                                      <div class="column is-4">
                                        <VRadio v-model="input.Leher" :value="'N'" :label="'Normal'" name="TN_Leher"
                                          square color="primary" />
                                      </div>
                                    </div>
                                  </VControl>
                                </div>
                                <div class="column is-12  p-0">
                                  <VControl>
                                    <div class="columns is-multiline">
                                      <div class="column is-3">
                                        <label class="label mt-2">Thoraks</label>
                                      </div>
                                      <div class="column is-5">
                                        <VRadio v-model="input.Thoraks" :value="'TN'" :label="'Tidak Normal'"
                                          name="TN_Thoraks" square color="primary" />
                                      </div>
                                      <div class="column is-4">
                                        <VRadio v-model="input.Thoraks" :value="'N'" :label="'Normal'" name="TN_Thoraks"
                                          square color="primary" />
                                      </div>
                                    </div>
                                  </VControl>
                                </div>
                                <!-- as -->
                                <div class="column is-12  p-0">
                                  <VControl>
                                    <div class="columns is-multiline">
                                      <div class="column is-3">
                                        <label class="label mt-2">Jantung</label>
                                      </div>
                                      <div class="column is-5">
                                        <VRadio v-model="input.Jantung" :value="'TN'" :label="'Tidak Normal'"
                                          name="TN_Jantung" square color="primary" />
                                      </div>
                                      <div class="column is-4">
                                        <VRadio v-model="input.Jantung" :value="'N'" :label="'Normal'" name="TN_Jantung"
                                          square color="primary" />
                                      </div>
                                    </div>
                                  </VControl>
                                </div>
                                

                                <div class="column is-12  p-0">
                                  <VControl>
                                    <div class="columns is-multiline">
                                      <div class="column is-3">
                                        <label class="label mt-2">Paru</label>
                                      </div>
                                      <div class="column is-5">
                                        <VRadio v-model="input.Paru" :value="'TN'" :label="'Tidak Normal'" name="TN_Paru"
                                          square color="primary" />
                                      </div>
                                      <div class="column is-4">
                                        <VRadio v-model="input.Paru" :value="'N'" :label="'Normal'" name="TN_Paru" square
                                          color="primary" />
                                      </div>
                                    </div>
                                  </VControl>
                                </div>
                                <div class="column is-12  p-0">
                                  <VControl>
                                    <div class="columns is-multiline">
                                      <div class="column is-3">
                                        <label class="label mt-2">Abdomen</label>
                                      </div>
                                      <div class="column is-5">
                                        <VRadio v-model="input.Abdomen" :value="'TN'" :label="'Tidak Normal'"
                                          name="TN_Abdomen" square color="primary" />
                                      </div>
                                      <div class="column is-4">
                                        <VRadio v-model="input.Abdomen" :value="'N'" :label="'Normal'" name="TN_Abdomen"
                                          square color="primary" />
                                      </div>
                                    </div>
                                  </VControl>
                                </div>
                                <div class="column is-12  p-0">
                                  <VControl>
                                    <div class="columns is-multiline">
                                      <div class="column is-3">
                                        <label class="label mt-2">Genitalia</label>
                                      </div>
                                      <div class="column is-5">
                                        <VRadio v-model="input.Genitalia" :value="'TN'" :label="'Tidak Normal'"
                                          name="TN_Genitalia" square color="primary" />
                                      </div>
                                      <div class="column is-4">
                                        <VRadio v-model="input.Genitalia" :value="'N'" :label="'Normal'"
                                          name="TN_Genitalia" square color="primary" />
                                      </div>
                                    </div>
                                  </VControl>
                                </div>


                                <div class="column is-12  p-0">
                                  <VControl>
                                    <div class="columns is-multiline">
                                      <div class="column is-3">
                                        <label class="label mt-2">Ekstremitas</label>
                                      </div>
                                      <div class="column is-5">
                                        <VRadio v-model="input.Ekstremitas" :value="'TN'" :label="'Tidak Normal'"
                                          name="TN_Ekstremitas" square color="primary" />
                                      </div>
                                      <div class="column is-4">
                                        <VRadio v-model="input.Ekstremitas" :value="'N'" :label="'Normal'"
                                          name="TN_Ekstremitas" square color="primary" />
                                      </div>
                                      <div class="column is-12 mt-5-min">
                                        <VControl icon="feather:bookmark">
                                          <VInput type="text" v-model="input.EkstremitasText" placeholder="Ekstremitas"
                                            class="is-rounded" />
                                        </VControl>
                                      </div>
                                    </div>
                                  </VControl>
                                </div>
                                <div class="column is-12  p-0">
                                  <VControl>
                                    <div class="columns is-multiline">
                                      <div class="column is-3">
                                        <label class="label mt-2">Status
                                          Lokalis</label>
                                      </div>
                                      <div class="column is-5">
                                        <VRadio v-model="input.StatusLokalis" :value="'TN'" :label="'Tidak Normal'"
                                          name="TN_StatusLokalis" square color="primary" />
                                      </div>
                                      <div class="column is-4">
                                        <VRadio v-model="input.StatusLokalis" :value="'N'" :label="'Normal'"
                                          name="TN_StatusLokalis" square color="primary" />
                                      </div>
                                    </div>
                                  </VControl>
                                </div>
                              </div>
                            </Fieldset>
                          </div>
                        </div>
                      </div>
                    </VCardHead>
                  </div>
                </div>
              </VCardHead>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import TRiwayatOrderLab from './profile-pasien/t-riwayat-order-lab.vue'
import Checkbox from 'primevue/checkbox';
import RadioButton from 'primevue/radiobutton';
import InputSwitch from 'primevue/inputswitch';
import InputNumber from 'primevue/inputnumber';
import SelectButton from 'primevue/selectbutton';
import Fieldset from 'primevue/fieldset';
import sleep from '/@src/utils/sleep'
import $ from "jquery";


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    selected: any
    type: any
    align: any
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    selected: undefined,
    type: undefined,
    align: undefined,
  }
)

const isLoadingPasien: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)

  jenisKelamin.value = props.pasien.jeniskelamin.toUpperCase()
  MARKINGSITE.value = '/images/simrs/' + (jenisKelamin.value == 'PEREMPUAN' ? 'cewek.png' : 'cowok.png')
}
const menuEMR: any = ref([
  { label: 'Anamnesis', value: 0, icon: 'fas fa-list', collection: 'Anamnesis' },
  { label: 'Pemeriksaan Psikologis', value: 1, icon: 'fas fa-list', collection: 'PemeriksaanPsikologis' },
  { label: 'Pemeriksaan Fisik', value: 2, icon: 'fas fa-list', collection: 'PemeriksaanFisik' },
])
const d_Alergi: any = ref([
  { label: 'Obat', value: 0 },
  { label: 'Makanan', value: 1 },
  { label: 'Udara', value: 2 },
  { label: 'Tidak Ada', value: 4 },
  { label: 'Lainnya', value: 3 },
])
const d_JenisPsikologi: any = ref([
  { label: 'Tidak ada kelainan', value: 0 },
  { label: 'Cemas', value: 1 },
  { label: 'Takut', value: 2 },
  { label: 'Marah', value: 3 },
  { label: 'Sedih', value: 4 },
  { label: 'Lainnya', value: 5 },
])
const d_Status: any = ref([
  { label: 'Kepala', value: 0 },
  { label: 'Leher', value: 1 },
  { label: 'Thoraks', value: 2 },
  { label: 'Jantung', value: 3 },
  { label: 'Paru', value: 4 },
  { label: 'Abdomen', value: 5 },
  { label: 'Genitalia', value: 5 },
  { label: 'Ekstremitas', value: 5 },
  { label: 'Status Lokalis', value: 5 },
])
const COLLECTION: any = ref('AsesmenAwal')
const NOREC_EMRPASIEN: any = ref('')
NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
const input: any = ref({})
const input2: any = ref({})
const input3: any = ref({})
const router = useRouter()
const listChecked: any = ref([])
const selected_count = ref(0);
const selectedTabs: any = ref()
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i];
  if (i <= 9 && element != 'primary')
    listColor.value.push(element)
}
const options = ref(['LAKI-LAKI', 'PEREMPUAN']);
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const jenisKelamin: any = ref('')
const MARKINGSITE: any = ref('')
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const isLoading = ref(false)
const d_Pegawai = ref([])
const listRiwayat = ref([])
const route = useRoute()
const emit = defineEmits<{
  (e: 'update:selected', value: string): void
}>()



const activeValue: any = ref(0)
const sliderClass = computed(() => {
  if (!props.slider) {
    return ''
  }

  if (props.type === 'rounded') {
    if (props.tabs.length === 3) {
      return 'is-triple-slider'
    }
    if (props.tabs.length === 2) {
      return 'is-slider'
    }

    return ''
  }

  if (!props.type) {
    if (props.tabs.length === 3) {
      return 'is-squared is-triple-slider'
    }
    if (props.tabs.length === 2) {
      return 'is-squared is-slider'
    }
  }

  return ''
})

const loadRiwayat = async() => {
  // NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
  // let collection = menuEMR.value.map((a:any) => a.collection).join(",");
  if (NOREC_EMRPASIEN.value == '') return
  const response =  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
      if (response.length) {
        // if (activeValue.value == 0) {
        input.value = response[0]
        // } else if (activeValue.value == 1) {
        //     input.value = response[0]
        // } else if (activeValue.value == 2) {
        //     input.value = response[0]
        if (response[0].anatomiTubuh) {
          let sigCanvas: any = document.getElementById('markingsite');
          if (sigCanvas) {

            let context = sigCanvas.getContext("2d");
            context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
            let imagess = response[0].anatomiTubuh
            let background = new Image();
            background.src = imagess
            background.onload = function () {
              context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height);
            }
          }
        }

        // }
      }
  
}
const pasienByID = (id: any) => {
  isLoadingPasien.value = true
  useApi().get(
    `/general/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
      pasien.value = response.pasien
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.registrasi = response.last_registrasi
      pasien.value.registrasi = item.registrasi
      isLoadingPasien.value = false
      jenisKelamin.value = response.pasien.jeniskelamin.toUpperCase()
      MARKINGSITE.value = '/images/simrs/' + (jenisKelamin.value == 'PEREMPUAN' ? 'cewek.png' : 'cowok.png')
      // fetchTindakan(item.RUANGAN_LAST)
    })
}

const fetchDropdown = () => {
  useApi().get(
    `/laboratorium/list-dropdown`).then((response: any) => {
      d_Ruangan.value = response.ruanganLab.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
      item.ruanganTujuan = d_Ruangan.value[0].value
      item.departemenfk = d_Ruangan.value[0].default.objectdepartemenfk
    })
}
function simpan() {
  let object: any = {}
  let ID = ''

  // if (activeValue.value == 0) {
  object = input.value
  ID = input.value.id ? input.value.id : ''

  let sigCanvas = document.getElementById("markingsite");
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    const dataURL = sigCanvas.toDataURL();
    input.value.anatomiTubuh = dataURL
    object.anatomiTubuh = dataURL
  }

  // } else if (activeValue.value == 1) {
  //     object = input.value
  //     ID = input.value.id ? input.value.id : ''

  // } else if (activeValue.value == 2) {
  //     let sigCanvas = document.getElementById("markingsite");
  //     if (sigCanvas) {
  //         let context = sigCanvas.getContext("2d");
  //         const dataURL = sigCanvas.toDataURL();
  //         input.value.anatomiTubuh = dataURL
  //     }
  //     object = input.value
  //     ID = input.value.id ? input.value.id : ''
  // }

  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmenawal',
    'data': object
  }

  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      // router.replace({
      //     name: 'module-emr-asesmen-awal',
      //     query: {
      //         norec_pd: pasien.value.registrasi.norec_pd,
      //         nocmfk: pasien.value.nocmfk,
      //         norec_emr: response.norec_emr,
      //     }
      // })
    }).catch((e: any) => {
      isLoading.value = false
    })
}

function kembaliKeun() {
  window.history.back()
}
const checkMenu = (e: any) => {
  console.log(item.selectedMenu)
}
const toggle = (value: string) => {
  activeValue.value = value
}

const getPosition = (mouseEvent: any, sigCanvas: any) => {
  let rect = sigCanvas.getBoundingClientRect();
  return {
    X: mouseEvent.clientX - rect.left,
    Y: mouseEvent.clientY - rect.top
  };
}
const markignSite = () => {
  let sigCanvas: any = document.getElementById("markingsite");
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "red";
  context.lineJoin = "round";
  context.lineWidth = 2;
  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {

    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        this.isDrawing = true;
      },
      touchmove: function (coors: any) {
        if (this.isDrawing) {
          context.lineTo(coors.x, coors.y);
          context.stroke();
        }
      },
      touchend: function (coors: any) {
        if (this.isDrawing) {
          this.touchmove(coors);
          this.isDrawing = false;
        }
      }
    };


    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;

      if (obj.offsetParent) {

        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        }

        while ((obj = obj.offsetParent) != null);
      }


      drawer[event.type](coors);
    }


    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);


    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();

    }, false);
  } else {

    $("#markingsite").mousedown(function (mouseEvent: any) {

      let position = getPosition(mouseEvent, sigCanvas);
      context.moveTo(position.X, position.Y);
      context.beginPath();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });
    });

  }
}
const drawLine = (mouseEvent: any, sigCanvas: any, context: any) => {

  let position = getPosition(mouseEvent, sigCanvas);

  context.lineTo(position.X, position.Y);
  context.stroke();
}
const finishDrawing = (mouseEvent: any, sigCanvas: any, context: any) => {
  drawLine(mouseEvent, sigCanvas, context);

  context.closePath();
  $(sigCanvas).unbind("mousemove")
    .unbind("mouseup")
    .unbind("mouseout");
}

const clearCanvas = (canvas: any) => {

  var sigCanvas: any = document.getElementById(canvas);
  var context = sigCanvas.getContext("2d");
  context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);

}

const getDataExist = async () => {
  await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
    input.value.beratBadan = response.beratBadan
    input.value.tinggiBadan = response.tinggiBadan
    input.value.IMT = response.IMT
    input.value.lingkarPerut = response.lingkarPerut
    input.value.nadi = response.nadi
    input.value.suhu = response.suhu
    input.value.tekananDarah = response.tekananDarah
    input.value.pernapasan = response.pernapasan
    input.value.SPO2 = response.SPO2
  })
}
watch(
  () => selectedTabs,
  (value) => {
    activeValue.value = value
  }
)

watch(activeValue, (value: any) => {
  emit('update:selected', value)

})
watch(
  () => activeValue.value,
  (value) => {
    loadRiwayat()

  }
)
watch(
  () => [
    input.value.beratBadan,
    input.value.tinggiBadan],
  (value) => {
    let txtFirstNumberValue: any = input.value.beratBadan;
    let txtSecondNumberValue: any = input.value.tinggiBadan;
    let result: any = parseFloat(txtFirstNumberValue) / (parseFloat(txtSecondNumberValue) / 100
      * parseFloat(txtSecondNumberValue) / 100);

    input.value.IMT = parseFloat(result).toFixed(2)
  }
)
watch(
  () =>
    jenisKelamin.value,
  (newValue, oldValue) => {
    console.log(jenisKelamin.value)
  }
)
onMounted(() => {
  watch(
    () => activeValue.value,
    async (value) => {
      if (value == 2) {
        await sleep(1000)
        markignSite()
      }

    }
  )
})

onBeforeMount(async() => {
  try {
    await loadRiwayat()
    let cache =  H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if(cache) input.value = cache
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
getDataExist()
// loadRiwayat()
// markignSite()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';
</style>
