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
        <div class="column">
            <Fieldset :toggleable="true" legend="Status Anestesi / Sedasi">
                <div class="column">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <span class="label-apas">Tanggal Dan Jam</span>
                            <VField class="column is-10 p-0 mt-3">
                                <VDatePicker v-model="input.tglDanJam" mode="dateTime" style="width: 100%" trim-weeks
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
                        <div class="column is-4">
                            <span class="label-apas">Instrumen</span>
                            <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
                                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                <AutoComplete v-model="input.instrumen" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <span class="label-apas">DPJP</span>
                            <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
                                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                <AutoComplete v-model="input.dpjp" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" disabled
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <span class="label-apas">Dokter Anestesi</span>
                            <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
                                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                <AutoComplete v-model="input.dokterAnestesi" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <span class="label-apas">Dokter Bedah Asisten</span>
                            <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
                                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                <AutoComplete v-model="input.dokterBedahAsisten" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <span class="label-apas">Asisten</span>
                            <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
                                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                <AutoComplete v-model="input.asisten" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-4" v-for="(data) in statusAnestesi">
                            <span class="label-apas">{{ data.label }}</span>
                            <VField class="pt-3">
                                <VControl>
                                    <VTextarea v-model="input[data.model]" rows="2" :placeholder="data.label"></VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </Fieldset>
        </div>
        <div class="column">
            <Fieldset :toggleable="true" legend="Re Assesment">
                <div class="column is-12" v-for="(datas) in asesmentAnestesi">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.subtitle }}</h1>
                    <div class="columns is-multiline">
                        <div class="column" :class="{'is-2': datas.subtitle === 'Monitoring', 'is-4': datas.subtitle === 'Cek list persiapan Anestesi'}" v-for="(data) in datas.values">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" class="p-0" color="primary" square />
                                </VControl>
                            </VField>                              
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-5">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jam Operasi Mulai</h1>
                            <VDatePicker v-model="input.jamStartOperasi" color="green" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-6" v-for="(datas) in detailsAnestesi">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">{{datas.subTitle}}</h1>
                            <VField v-if="datas.type == 'input'">
                                <VControl>
                                    <VInput type="text" class="input"  v-model="input[datas.model]" />
                                </VControl>
                            </VField>
                            <VField v-if="datas.type == 'checkbox'">
                                <div class="columns is-multiline mt-3 ml-2">
                                    <div class="column is-2" v-for="(data) in datas.values">
                                        <VField>
                                            <VControl raw subcontrol>
                                            <VCheckbox :true-value="data.subTitle" :label="data.subTitle" class="p-0" color="primary" square v-model="input[data.model]" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </VField> 
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanda-tanda vital Pra-Induksi dan EKG</h1>
                            <VField>
                                <VControl raw subcontrol>
                                    <VTextarea class="textarea" v-model="input.tandaVitalPraInduksi" rows="2" autocomplete="off" autocapitalize="off" spellcheck="true" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </Fieldset>
        </div>
         <!-- form emr -->
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
  import * as EMR from '../page-emr-plugins/pra-anestesia-sedasi'
  
  

  let statusAnestesi    = ref(EMR.statusAnestesi());
  let asesmentAnestesi  = ref(EMR.asesmentAnestesi());
  let detailsAnestesi  = ref(EMR.detailsAnestesi());

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
  const input: any = ref({
    tglDanJam : new Date(),
    jamStartOperasi: new Date(),
    tglDibuat: new Date()
  })
  const setView = () => {
    useHead({
      title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
  }

  const loadRiwayat = () => {
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
    input.value.dpjp = props.registrasi.dokter;
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
  