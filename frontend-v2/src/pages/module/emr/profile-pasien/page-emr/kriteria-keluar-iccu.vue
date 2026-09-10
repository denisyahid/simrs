<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>KRITERIA KELUAR RUANGAN ICCU</h3>
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
                           <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">1. KRITERIA KLINIS
                        </th>
                      </tr>
                      <tr>
                      <th>a. Nyeri dada angina tidak ada/ membaik secara signifikan <i>(Pain Scale 3)</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.kriA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.kriA1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>b. Sesak nafas tidak ada/ membaik secara signifikan</th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.kriB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.kriB1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>c. Palpitasi dan gejala hemodinamik tidak stabil sudah tidak ada</th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.kriC" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.kriC1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                    <th>d. Perbaikan khas fungsional gagal jantung</th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.kriD" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.kriD1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                      </thead>

                      <thead>
                        <tr>
                           <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">2. KRITERIA VITAL SIGN
                            </th>
                        </tr>
                      <tr>
                      <th>a. Nadi &>50 atau &lt; 120kali/menit</th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.sigA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.sigA1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>b. Tekanan darah sistolik > 80 mmHg atau tanpa penurunan 20 mmHg dari tekanan darah sistolik biasanya</th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.sigB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.sigB1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>c. <i> Mean Arterial Pressure 50-150 mmHg </i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.sigC" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.sigC1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>d. Laju respirasi &lt; 35 kali/menit</th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.sigD" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.sigD1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                      </thead>

                      <thead>
                        <tr>
                           <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">3. KRITERIA ECG
                            </th>
                        </tr>
                      <tr>
                      <th>a. ST Elevasi dengan evolusi lengkap (Q wave, T Inversi)</th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.ecgA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.ecgA1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>b. Perbaikan ST Depresi atau ST Depresi yang menetap tanpa dinamika yang bermakna </th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.ecgB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.ecgB1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>c. S1 Q3 T3 yang membaik atau menetap pada emboli paru</th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.ecgC" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.ecgC1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>d. Perbaikan atau menetapnya PR Depresi</th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.ecgD" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.ecgD1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>e. LBBB yang menetap</th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.ecgE" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.ecgE1" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>f. AF NVR</th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.ecgF" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.ecgF1" true-value="Tidak" color="primary" />
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
                             <b style="font-size: 17px">BERDASARKAN KONDISI DI ATAS MAKA MEMENUHI INDIKASI MASUK ICCU DENGAN PRIORITAS .................</b>
                            
                          </VField>
                        </td>
                      </tr>
                      <tr>
                  <th>Alat transportasi yang digunakan :<br> <i>Transport equipments needed </i> </th>
                </tr>
                <tr>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.brancard" true-value="Ya" color="primary" />
                      <span>Brancard</span>
                    </div>
                  </th>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.kursiroda" true-value="Tidak" color="primary" />
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
                      <span>Care Giver/POS </span>
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
                      <span>YA,</span>
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
      COLLECTION: 'kriteriakeluarruangiccu',
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
  