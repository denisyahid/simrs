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
        <div class="column is-4">
          <span style="font-weight: 500;">Ruangan</span>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Cari Ruangan..." />
            </VControl>
          </VField>
        </div>

        <div class="column" style="overflow: auto;">
          <table class="table-lo">
            <thead>
              <tr>
                <th  class="th-lo" rowspan="2">Tgl.</th>
                <th  class="th-lo" rowspan="2">TERAPI GIZI</th>
                <th  class="th-lo" colspan="4">MONITORING</th>
                <th  class="th-lo" rowspan="2">EVALUASI TINDAK LANJUT</th>
              </tr>
              <tr>
                <th class="th-lo">Antropometri</th>
                <th class="th-lo">BIOKIMIA Terkait Gizi</th>
                <th class="th-lo">KLINIS** (Terkait Gizi)</th>
                <th class="th-lo">ASUPAN Zat Gizi</th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.details" :key="index">
              <tr>
                <td class="td-lo">
                    <VDatePicker 
                    class="p-3" 
                    v-model="item.jam" 
                    color="green" 
                    mode="datetime" 
                    is24hr
                  >
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl>
                          <VInput 
                            class="input form-timepicker" 
                            :value="inputValue" 
                            v-on="inputEvents" 
                          />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </td>
                <td class="td-lo">
                  <VField class="p-3">
                    <VControl>
                        <VField v-if="index > 0" class="p-3">
                            <div class="diet-form" >
                                <label for="diet">Diet:</label>
                                <VTextarea v-model="item.diet" rows="2" placeholder="Diet...">
                                </VTextarea>
                              </div>
                        </VField>
                        <VField v-else>
                            <label>Diet awal:</label>
                            <ul>
                              <li>- N. Parenteral</li>
                              <li>- N. Enteral</li>
                            </ul>
                            <div class="checkbox-group">
                              <label>
                                <input type="checkbox" v-model="input.oral" name="oral" /> Oral
                              </label>
                              <label>
                                <input type="checkbox" v-model="input.ngt" name="ngt" /> NGT
                              </label>
                            </div>
                            <div class="zat-gizi">
                                <label>Zat gizi:</label>
                                <div>
                                  <label>Energi: <input v-model="input.energi" type="text" class="large-font" placeholder="____" /> kkal</label>
                                </div>
                                <div>
                                  <label>Protein: <input v-model="input.protein" type="text" class="large-font" placeholder="____" /> gr</label>
                                </div>
                                <div>
                                  <label>Cairan: <input v-model="input.cairan" type="text" class="large-font" placeholder="____" /> mltr</label>
                                </div>
                            </div>
                            
                              <!-- Jenis Formula -->
                              <div class="jenis-formula">
                                <label>Jenis formula:</label>
                                <input type="text" v-model="input.jenisformula" class="large-font" placeholder="Jenis formula..." />
                              </div>
                                <!-- Frekuensi -->
                                <div class="frekuensi">
                                    <label>Frekuensi:</label>
                                    <input type="text" v-model="input.frekuensi" class="large-font" placeholder="Frekuensi..." />
                                </div>
                        </VField>                        
                    </VControl>
                  </VField>
                </td>
                <td class="td-lo">
                  <VField>
                      <VTextarea rows="8" v-model="item.antropometri" ></VTextarea>
                  </VField>
                </td>
                <td class="td-lo">
                  <VField>
                      <VTextarea rows="8" v-model="item.biokimia" ></VTextarea>
                  </VField>
                </td>
                <td class="td-lo">
                  <VField>
                      <VTextarea rows="8" v-model="item.klinis" ></VTextarea>
                  </VField>
                </td>
                <td class="td-lo">
                  <VField>
                      <VTextarea rows="8" v-model="item.asupan" ></VTextarea>
                  </VField>
                </td>
                <td class="td-lo">
                  <VField>
                    <VControl>
                        <VTextarea v-model="item.evaluasi" rows="8" placeholder="Keterangan...">
                        </VTextarea>
                    </VControl>
                  </VField>
                </td>
                <!-- <td class="td-lo">
                  <VField class="p-3">
                    <VControl>
                        <VTextarea v-model="item.keterangan" rows="2" placeholder="Keterangan...">
                        </VTextarea>
                    </VControl>
                  </VField>
                </td> -->
                <!-- <td class="td-lo">
                  <VField class="p-3">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.paraf" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas..." />
                    </VControl>
                  </VField>
                </td> -->
                <!-- <td class="td-lo">
                  <VField class="p-3">
                    <VControl>
                      <VTextarea v-model="item.keterangan" rows="2" placeholder="Keterangan...">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td> -->
                <td class="td-lo" style="vertical-align: inherit;text-align: center;">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                    v-tooltip.bubble="'Tambah '">
                  </VIconButton>
                  <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                    @click="removeItem(index)" color="danger">
                  </VIconButton>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </VCard>
    </div>
  
    <div class="column is-4" style="margin-left: auto;">
      <VCard>
        <div class="column is-8">
          <h1 style="font-weight: bold;">Garut</h1>
          <VDatePicker class="pt-3" v-model="input.tglDibuat" color="green" mode="datetime">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VControl>
                  <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <!-- Ini awalnya dokter, diubah menjadi Ahli Gizi -->
        <div class="column pt-0">
          <h1 style="font-weight: bold;">Ahli Gizi</h1>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.dokter" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Cari Pegawai..." />
            </VControl>
          </VField>
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
      COLLECTION: 'AsuhanGiziNeonatus',
    }
  )
  const { y } = useWindowScroll()
  const isStuck = computed(() => { return y.value > 30 })
  const isLoading: any = ref(false)
  const d_Ruangan: any = ref([])
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
  const input: any = ref({
    tglDibuat : new Date,
    details: [{
      no: 1,
      jam: new Date
    }]
  })
  const setView = () => {
    useHead({
      title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
  }
  let megatron = ref([
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Gagal tumbuh",
                    "model": "gagalTumbuh",
                    "type": "checkbox"
                },
                {
                    "subTitle": "Biokimia",
                    "model": "biokimia_monitoring",
                    "type": "textfiled"
                },
                {
                    "subTitle": "Berat badan",
                    "model": "berat_badan_monitoring",
                    "type": "textfiled"
                },
            ],
        },

    ])
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
    console.log(json)
    useApi().post(
      `/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        NOREC_EMRPASIEN.value = response.norec_emr
        input.value.id = response.id
      }).catch((e: any) => {
        isLoading.value = false
      })
  }
  
  const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
  }
  
  const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
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
  
  const addNewItem = () => {
    input.value.details.push({
      no: input.value.details[input.value.details.length - 1].no + 1,
    });
  }
  const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
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
  .table-lo {
    width: 100%;
    border: 1px solid black;
  }
  .large-font {
    font-size: 14px;  /* Ukuran font lebih besar */
}
  .th-lo,
  .td-lo {
    padding: 7px;
    border: 1px solid black;
  }
  
  .th-lo {
    text-align: center !important;
  }
  .checkbox-group {
    margin-top: 5px;
  }
  
.zat-gizi {
    margin-bottom: 15px;
  }
  
  .zat-gizi div {
    margin-bottom: 10px;
  }
  
  .jenis-formula,
  .frekuensi {
    margin-bottom: 10px;
  }
  input[type="text"] {
    width: 80%;
    padding: 5px;
    margin-left: 5px;
  }
  .diet-form {
    border: 1px solid #ddd;
    padding: 10px;
    width: 300px;
    margin: 20px auto;
    font-family: Arial, sans-serif;
  }
  
  .diet-form label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
  }
  </style>
  