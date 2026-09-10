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
        <div class="column">
          <table class="table-hie">
            <thead>
              <tr>
                <th class="th-hie">No</th>
                <th class="th-hie">Metode Edukasi</th>
                <th class="th-hie">Respon</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(data) in metodeEdukasi">
                <td class="td-hie">{{ data.no }}</td>
                <td class="td-hie">{{ data.metode }}</td>
                <td class="td-hie">{{ data.respon }}</td>
              </tr>
            </tbody>
          </table>
        </div>
  
        <div class="column" style="overflow:auto">
          <table class="table-hiet">
            <thead>
              <tr>
                <th class="th-hie" colspan="8">CATATAN EDUKASI</th>
              </tr>
              <tr>
                <th class="th-hie">Tgl dan Jam</th>
                <th class="th-hie">Materi Informasi dan Edukasi</th>
                <th class="th-hie">Siapa yang diedukasi</th>
                <th class="th-hie">Metode Edukasi</th>
                <th class="th-hie">Respon</th>
                <th class="th-hie">Tempat</th>
                <th class="th-hie">Nama Edukator</th>
                <th class="th-hie" width="10%">Paraf</th>
                <th class="th-hie" width="5%">#</th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.details" :key="index">
              <tr>
                <td class="td-hie">
                  <VDatePicker class="p-3" v-model="item.tglCatatan" color="green" trim-weeks mode="datetime"
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }" class="pb-0">
                      <VField>
                        <VControl icon="feather:calendar">
                          <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                            class="is-rounded_Z" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </td>
                <td class="td-hie">
                  <VField class="p-3">
                    <VControl>
                      <VTextarea v-model="item.catatanEdukasi" rows="2">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-hie">
                  <VField class="p-3">
                    <VControl>
                      <VTextarea v-model="item.diEdukasi" rows="2">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-hie">
                  <VField class="p-3">
                    <VControl>
                      <VTextarea v-model="item.metodeEdukasi" rows="2">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-hie">
                  <VField class="p-3">
                    <VControl>
                      <VTextarea v-model="item.respon" rows="2">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-hie">
                  <VField class="p-3">
                    <VControl>
                      <VTextarea v-model="item.tempat" rows="2">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-hie">
                  <VField class="pt-3">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.edukator" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"  @item-select="setTandaTanganPegawai($event,index)"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Edukator..." />
                    </VControl>
                  </VField>
                </td>
                <td class="td-hie">
                  <div class="column" style="text-align:center">
                    <TandaTangan :elemenID="'signaturePegawai_' + [index]" :width="'150'" :height="'150'" class="dek" />
                  </div>
                </td>
                <td class="td-hie">
                  <div class="column" style="text-align: center;">
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                      v-tooltip.bubble="'Tambah '">
                    </VIconButton>
                    <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                      @click="removeItem(index)" color="danger">
                    </VIconButton>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="column">
            <span class="label-hie">*Catatan : Untuk Responden dengan Respon nilai 1 dan 2, Edukasi harap diulangi kembali</span>
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
  import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
  import * as EMR from '../page-emr-plugins/histori-informasi-dan-edukasi-terintegrasi'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useUserSession } from '/@src/stores/userSession'
  
  
  let ID_PASIEN = useRoute().query.nocmfk as string
  let NOREC_PD = useRoute().query.norec_pd as string
  let norec_emr = useRoute().query.norec_emr as string
  
  let metodeEdukasi = ref(EMR.metodeEdukasi())
  let kebutuhanEdukasi = ref(EMR.kebutuhanEdukasi())
  
  
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
  const dataTTD: any = ref([])
  const d_Pegawai: any = ref([])
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
    details: [{
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
  const loadRiwayat = async () => {
    // if (NOREC_EMRPASIEN.value == '') return
   await useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        if (response.length) {
          input.value = response[0] //set ke inputan
          dataTTD.value = response[0].details
  
          if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
          }
        }
      })
  
    dataTTD.value.forEach((element: any, i: any) => {
      // console.log(element.ttd)
      H.tandaTangan().set("signaturePegawai_" + [i], element.ttd)
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
    json.data.details.forEach((element: any, i: any) => {
      element.ttd = H.tandaTangan().get("signaturePegawai_" + [i])
    });
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
    input.value.details.push({
      no: input.value.details[input.value.details.length - 1].no + 1,
    });
  }
  const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
  }
  
  const fetchPegawai = async (filter: any) => {
      const response = await useApi().get(
          `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
      d_Pegawai.value = response
  }
  
  const setTandaTanganPegawai = async (e: any,i:any) => {
      await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
        if(element){
          H.tandaTangan().set("signaturePegawai_"+i, element.ttd)
        }else{
          H.tandaTangan().set("signaturePegawai_"+i, '')
        }
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
  
  .label-hie{
    font-weight:500;
  }
  
  .table-hie {
    width: 100%;
    border: 1px solid black
  }
  
  .table-hiet {
    width: 130%;
    border: 1px solid black
  }
  
  .th-hie,
  .td-hie {
    border: 1px solid black;
    padding: 7px;
  }
  
  .th-hie {
    text-align: center !important;
  }
  </style>
  
  