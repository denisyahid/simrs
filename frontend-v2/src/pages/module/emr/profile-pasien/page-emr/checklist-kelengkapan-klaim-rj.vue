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
        <div class="column mt-5" style="overflow: auto;">
          <table class="table-ro">
            <thead>
              <tr>
                <th class="th-ro" width="5%">NO</th>
                <th class="th-ro">URAIAN KELENGKAPAN</th>
                <th class="th-ro">CHECKLIST</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="td-ro">1</td>
                <td class="td-ro">
                    <span class="label-ro">SEP ( <i>Lengkap TTD Pasien dan Tanggal Pelayanan Sesuai tanggal MRS</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index1" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">2</td>
                <td class="td-ro">
                    <span class="label-ro">Print Out E-Klaim INA-CBGs ( <i>Dilengkapi Oleh Petugas Koding</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index2" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">3</td>
                <td class="td-ro">
                    <span class="label-ro">Surat Rujukan Faskes Asli / Surat Dalam Masa Perawatan Asli</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index3" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">4</td>
                <td class="td-ro">
                    <span class="label-ro">Surat Keterangan Gawat Darurat Asli ( <i>Untuk Pasien Kasus Emergency</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index4" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">5</td>
                <td class="td-ro">
                    <span class="label-ro">Resume Medis Rawat Jalan Asli <i> (Wajib Lengkap Sesuai Penegakkan Diagnosa (<b>Anamnese, Keadaan Umum, Pemeriksaan Penunjang, Diagnosa Utama,Diagnosa Sekunder, Tindakan, Terapi, Kondisi Keluar</b>)) dan Terbaca </i></span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index5" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">6</td>
                <td class="td-ro">
                    <span class="label-ro">Laporan Kepolisian / Surat Jaminan Jasa Raharja / Surat Tidak Dijamin Jasa
                        Raharja ( <i>Untuk Pasien Kasus Kecelakaan Lalu Lintas</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index6" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">7</td>
                <td class="td-ro">
                    <span class="label-ro">Billing Rawat Jalan</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index7" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">8</td>
                <td class="td-ro">
                    <span class="label-ro">Hasil Pemeriksaan Penunjang Medis ( <i>Laboratorium, Rongent, Endoskopi, ECHO,
                    EKG, Lab. PA, CT-Scan, Spirometry, EEG, MRI, Kulture Darah, BTA, Body
                    Pletysismograph, Bronscoscopy</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index8" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">9</td>
                <td class="td-ro">
                    <span class="label-ro">Protokol Terapi, Formulir Penggunaan Obat dan Barcode Pemberian Obat
                    <b>Albumin, Streptokinase, Deferiprone, Deferoksamin, Deferasirox, Anti
                    Hemophilia, Chemoterapy</b> (<i>bila diberikan</i>)</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index9" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">10</td>
                <td class="td-ro">
                    <span class="label-ro">Copy Laporan Operasi/ Tindakan Kedokteran (<i>bila dilakukan operasi/tindakan
                        kedokteran, Lengkap dan Terbaca</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index10" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">11</td>
                <td class="td-ro">
                    <span class="label-ro">Monitoring Tranfusi, Lembar Darah (<i>bila dilakukan tranfusi</i>)</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index11" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">12</td>
                <td class="td-ro">
                    <span class="label-ro">Laporan Tindakan Hemodialisa ( <i>bila dilakukan Hemodialisa</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index12" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="columns">
          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1><b>TTD Perawat Poliklinik/IGD </b></h1>
              <TandaTangan :elemenID="'TTDPetugas1'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.CBPetugas1" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="
                  'label'" class="mt-2" />
              </VControl>
            </div>
          </div>
          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1><b>TTD Koordinator Jaga Poliklinik/IGD</b></h1>
              <TandaTangan :elemenID="'TTDPetugas2'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.CBPetugas2" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="
                  'label'" class="mt-2" />
              </VControl>
            </div>
          </div>
          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1><b>TTD Penerima Berkas</b></h1>
              <TandaTangan :elemenID="'TTDPetugas3'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.CBPetugas3" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="
                  'label'" class="mt-2" />
              </VControl>
            </div>
          </div>
        </div>
        <div class="column is-12 ml-5">
          <div class="column p-0">
            <h1 class="title-ro"><i>Keterangan:</i></h1>
          </div>
          <div class="column p-0">
            <span class="label-ro"><i>1) Wajib Dicentang pada kolom Checklist setelah dilengkapi</i></span>
          </div>
          <div class="column p-0">
            <span class="label-ro"><i>2) Wajib dilengkapi semua uraian kelengkapan sesuai kasus</i></span>
          </div>
          <div class="column p-0">
            <span class="label-ro"><i>3) Berkas Wajib Diurut Sesuai Urutan Checklist</i></span>
          </div>
        </div>
      </VCard>
    </div>




    <!-- batas kedua -->




    <div class="column is-12">
      <VCard>
        <div class="column mt-5" style="overflow: auto;">
          <table class="table-ro">
            <thead>
              <tr>
                <th class="th-ro" width="5%">NO</th>
                <th class="th-ro">URAIAN KELENGKAPAN</th>
                <th class="th-ro">CHECKLIST</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="td-ro">1</td>
                <td class="td-ro">
                    <span class="label-ro">SEP ( <i>Lengkap TTD Pasien dan Tanggal Pelayanan Sesuai tanggal MRS</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index13" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">2</td>
                <td class="td-ro">
                    <span class="label-ro">Print Out E-Klaim INA-CBGs ( <i>Dilengkapi Oleh Petugas Koding</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index14" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">3</td>
                <td class="td-ro">
                    <span class="label-ro">Surat Rujukan Faskes Asli / Surat Dalam Masa Perawatan Asli</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index15" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">4</td>
                <td class="td-ro">
                    <span class="label-ro">Surat Keterangan Gawat Darurat Asli ( <i>Untuk Pasien Kasus Emergency</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index16" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">5</td>
                <td class="td-ro">
                    <span class="label-ro">Resume Medis Rawat Jalan Asli <i> (Wajib Lengkap Sesuai Penegakkan Diagnosa (<b>Anamnese, Keadaan Umum, Pemeriksaan Penunjang, Diagnosa Utama,Diagnosa Sekunder, Tindakan, Terapi, Kondisi Keluar</b>)) dan Terbaca </i></span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index17" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">6</td>
                <td class="td-ro">
                    <span class="label-ro">Laporan Kepolisian / Surat Jaminan Jasa Raharja / Surat Tidak Dijamin Jasa
                        Raharja ( <i>Untuk Pasien Kasus Kecelakaan Lalu Lintas</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index18" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">7</td>
                <td class="td-ro">
                    <span class="label-ro">Billing Rawat Jalan</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index19" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">8</td>
                <td class="td-ro">
                    <span class="label-ro">Hasil Pemeriksaan Penunjang Medis ( <i>Laboratorium, Rongent, Endoskopi, ECHO,
                    EKG, Lab. PA, CT-Scan, Spirometry, EEG, MRI, Kulture Darah, BTA, Body
                    Pletysismograph, Bronscoscopy</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index20" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">9</td>
                <td class="td-ro">
                    <span class="label-ro">Protokol Terapi, Formulir Penggunaan Obat dan Barcode Pemberian Obat
                    <b>Albumin, Streptokinase, Deferiprone, Deferoksamin, Deferasirox, Anti
                    Hemophilia, Chemoterapy</b> (<i>bila diberikan</i>)</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index21" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">10</td>
                <td class="td-ro">
                    <span class="label-ro">Copy Laporan Operasi/ Tindakan Kedokteran (<i>bila dilakukan operasi/tindakan
                        kedokteran, Lengkap dan Terbaca</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index22" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">11</td>
                <td class="td-ro">
                    <span class="label-ro">Monitoring Tranfusi, Lembar Darah (<i>bila dilakukan tranfusi</i>)</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index23" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-ro">12</td>
                <td class="td-ro">
                    <span class="label-ro">Laporan Tindakan Hemodialisa ( <i>bila dilakukan Hemodialisa</i> )</span>
                </td>
                <td class="td-ro">
                  <div class="column">
                    <VField class="p-3" style="text-align: center;">
                        <VControl raw subcontrol>
                        <VCheckbox v-model="input.index24" true-value="Ya" class="p-0" color="primary" square />
                        </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="columns">
          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1><b>TTD Perawat Poliklinik/IGD</b></h1>
              <TandaTangan :elemenID="'TTDPetugas4'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.CBPetugas4" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="
                  'label'" class="mt-2" />
              </VControl>
            </div>
          </div>
          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1><b>TTD Koordinator Jaga Poliklinik/IGD</b></h1>
              <TandaTangan :elemenID="'TTDPetugas5'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.CBPetugas5" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="
                  'label'" class="mt-2" />
              </VControl>
            </div>
          </div>
          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1><b>TTD Penerima Berkas</b></h1>
              <TandaTangan :elemenID="'TTDPetugas6'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.CBPetugas6" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="
                  'label'" class="mt-2" />
              </VControl>
            </div>
          </div>
        </div>
        <div class="column is-12 ml-5">
          <div class="column p-0">
            <h1 class="title-ro"><i>Keterangan:</i></h1>
          </div>
          <div class="column p-0">
            <span class="label-ro"><i>1) Wajib Dicentang pada kolom Checklist setelah dilengkapi</i></span>
          </div>
          <div class="column p-0">
            <span class="label-ro"><i>2) Wajib dilengkapi semua uraian kelengkapan sesuai kasus</i></span>
          </div>
          <div class="column p-0">
            <span class="label-ro"><i>3) Berkas Wajib Diurut Sesuai Urutan Checklist</i></span>
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
  // import Fieldset from 'primevue/fieldset';
  // import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
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
  const d_Dokter: any = ref([])
  const d_Pegawai: any = ref([])
  const dataTTD: any = ref([])
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
    riwayatAlergi: [{
      no: 1,
    }],
    daftarObat: [{
      no: 1,
    }],
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
          H.tandaTangan().set("TTDPetugas1", dataTTD.value.TTDPetugas1)
          H.tandaTangan().set("TTDPetugas2", dataTTD.value.TTDPetugas2)
          H.tandaTangan().set("TTDPetugas3", dataTTD.value.TTDPetugas3)
          H.tandaTangan().set("TTDPetugas4", dataTTD.value.TTDPetugas4)
          H.tandaTangan().set("TTDPetugas5", dataTTD.value.TTDPetugas5)
          H.tandaTangan().set("TTDPetugas6", dataTTD.value.TTDPetugas6)
        }
      })
  }
  
  const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
  
    let object: any = {}
  
    object = input.value
    object['TTDPetugas1'] = H.tandaTangan().get("TTDPetugas1");
    object['TTDPetugas2'] = H.tandaTangan().get("TTDPetugas2");
    object['TTDPetugas3'] = H.tandaTangan().get("TTDPetugas3");
    object['TTDPetugas4'] = H.tandaTangan().get("TTDPetugas4");
    object['TTDPetugas5'] = H.tandaTangan().get("TTDPetugas5");
    object['TTDPetugas6'] = H.tandaTangan().get("TTDPetugas6");
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

    if (!input.value.CBPetugas1 || H.tandaTangan().get("TTDPetugas1") == undefined) {
        H.alert('warning', 'Silahkan Tanda Tangan dan Pilih Perawat Poliklinik/IGD');
        return;
    }

    if (!input.value.CBPetugas2 || H.tandaTangan().get("TTDPetugas2") == undefined) {
        H.alert('warning', 'Silahkan Tanda Tangan dan Pilih Perawat Koordinator Jaga Poliklinik/IGD');
        return;
    }

    if (!input.value.CBPetugas3 || H.tandaTangan().get("TTDPetugas3") == undefined) {
        H.alert('warning', 'Silahkan Tanda Tangan dan Pilih Penerima Berkas');
        return;
    }

    if (!input.value.CBPetugas4 || H.tandaTangan().get("TTDPetugas4") == undefined) {
        H.alert('warning', 'Silahkan Tanda Tangan dan Pilih Perawat Poliklinik/IGD');
        return;
    }

    if (!input.value.CBPetugas5 || H.tandaTangan().get("TTDPetugas5") == undefined) {
        H.alert('warning', 'Silahkan Tanda Tangan dan Pilih Perawat Koordinator Jaga Poliklinik/IGD');
        return;
    }

    if (!input.value.CBPetugas6 || H.tandaTangan().get("TTDPetugas6") == undefined) {
        H.alert('warning', 'Silahkan Tanda Tangan dan Pilih Penerima Berkas');
        return;
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
  