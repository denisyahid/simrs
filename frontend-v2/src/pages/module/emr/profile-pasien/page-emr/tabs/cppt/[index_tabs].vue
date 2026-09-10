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
      <VCard>
        <div class="column is-12 p-2">
          <table class="tg">
            <thead>
              <tr>
                <td class="td-fkprj" colspan="2">
                  <div class="columns is-multiline">
                    <div class="column is-2" style="text-align: center;margin-top: 15px;">
                      <span>Tanggal / Jam</span>
                    </div>
                    <div class="column">
                      <VField class="mt-2">
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
                    </div>
                  </div>
  
                </td>
              </tr>
            </thead>
            <tbody>
              <!-- <tr class="tr-fkprj">
                <td class="td-fkprj" colspan="2">
                  <VField class="mt-2">
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
                </td>
              </tr> -->
              <tr class="tr-fkprj">
                <td class="td-fkprj" width="10%" style="text-align:center">
                  <span style="font-size:35pt;font-weight:bold">S</span>
                </td>
                <td class="td-fkprj">
                  <VField>
                    <VControl>
                      <VTextarea rows="3" v-model="input.hasilAssesmen"
                        placeholder="Hasil assesmen dan penatalaksanaan pasien...">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr class="tr-fkprj">
                <td class="td-fkprj" width="10%" style="text-align:center">
                  <span style="font-size:35pt;font-weight:bold">O</span>
                </td>
                <td class="td-fkprj">
                  <VField>
                    <VControl>
                      <VTextarea rows="3" v-model="input.hasilAssesmen"
                        placeholder="Hasil assesmen dan penatalaksanaan pasien...">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr class="tr-fkprj">
                <td class="td-fkprj" width="10%" style="text-align:center">
                  <span style="font-size:35pt;font-weight:bold">A</span>
                </td>
                <td class="td-fkprj">
                  <div style="overflow-y:auto;">
                    <table width="120%">
                      <thead>
                        <tr class="tr-fkprj">
                          <th class="td-fkprj" width="2%" style="vertical-align: inherit;">NO</th>
                          <th class="td-fkprj" width="23%" style="vertical-align:inherit;text-align: center;">Jenis Diagnosa
                          </th>
                          <th class="td-fkprj" width="25%" style="vertical-align:inherit;text-align: center;">Diagnosa
                            Dokter
                          </th>
                          <th class="td-fkprj" style="vertical-align:inherit;text-align: center;">Diagnosa ICD 10</th>
                          <th class="td-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;" width="12%">#
                          </th>
                        </tr>
                      </thead>
                      <tbody v-for="(items, index) in input.detailDiagnosa" :key="index">
                        <tr class="tr-fkprj">
                          <td class="td-fkprj" style="vertical-align:inherit;text-align:center">{{ items.no }}</td>
                          <td class="td-fkprj">
                            <div class="column p-1">
                              <VField>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.jenisDiagnosa" :suggestions="d_JenisDiagnosa"
                                    @complete="fetchJenisDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Jenis Diagnosa..." class="mt-2" />
                                </VControl>
                              </VField>
                            </div>
                          </td>
                          <td class="td-fkprj">
                            <div class="column pt-3 pb-0">
                              <VField>
                                <VControl icon="feather:bookmark">
                                  <VInput type="text" v-model="items.diagnosaDokter" placeholder="Diagnosa Dokter" />
                                </VControl>
                              </VField>
                            </div>
                          </td>
                          <td class="td-fkprj">
                            <div class="column p-1">
                              <VField>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.diagnosaIcd10" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Diagnosa ICD 10 ..." class="mt-2" />
                                </VControl>
                              </VField>
                            </div>
                          </td>
                          <td class="td-fkprj" style="vertical-align: inherit;">
                            <div class="column p-0">
                              <VButtons style="justify-content: space-around;">
                                <VIconButton type="button" raised circle icon="feather:plus" @click="addDiagnosa()"
                                  color="info">
                                </VIconButton>
                                <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                                  @click="removeDiagnosa(index)" color="danger">
                                </VIconButton>
                              </VButtons>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
  
                </td>
              </tr>
              <tr class="tr-fkprj">
                <td class="td-fkprj" width="10%" style="text-align:center">
                  <span style="font-size:35pt;font-weight:bold">P</span>
                </td>
                <td class="td-fkprj">
                  <VField>
                    <VControl>
                      <VTextarea rows="3" v-model="item.hasilAssesmen"
                        placeholder="Hasil assesmen dan penatalaksanaan pasien...">
                      </VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr class="tr-fkprj">
                <td class="td-fkprj" colspan="2">
                  <div class="column p-1">
                    <VField class="is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="input.dokterDpjp" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Perawat" />
                      </VControl>
                    </VField>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
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
  import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
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
      COLLECTION: '',
    }
  )
  const { y } = useWindowScroll()
  const isStuck = computed(() => { return y.value > 30 })
  const isLoading: any = ref(false)
  const d_Obat: any = ref([])
  const d_Pegawai: any = ref([])
  const d_Diagnosa: any = ref([])
  const d_JenisDiagnosa: any = ref([])
  const d_Dokter: any = ref([])
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
    tglPembuatan: new Date(),
    detailDiagnosa: [
      { no: 1 },
    ],
  })
  
  const setView = () => {
    useHead({
      title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
  }
  const loadRiwayat = async () => {
    console.log(props.registrasi)
    // if (NOREC_EMRPASIEN.value == '') return
    await useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        if (response.length) {
          input.value = response[0] //set ke inputan
          if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
          }
          dataTTD.value = response[0].details
        }
      })
    dataTTD.value.forEach((element: any, i: any) => {
      H.tandaTangan().set("signature_" + [i], element.ttd)
    })
  
    // await fetchDokter(props.registrasi.dokter)
    // d_Dokter.value.forEach(element => {
    //   if (element.value == props.registrasi.objectpegawaifk) {
    //     input.value.detailDiagnosa.forEach(element => {
    //       element.
    //     });
    //     console.log(input.value.detailDiagnosa)
    //     debugger
    //   }
    // });
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
    // json.data.details.forEach((element: any, i: any) => {
    //   element.ttd = H.tandaTangan().get("signature_" + [i])
    // });
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
  const fetchDokter = async (filter: any) => {
    let data = filter.query ? filter.query : filter
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${data}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
      d_Dokter.value = response
    })
  }
  
  const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
  }
  
  const fetchJenisDiagnosa = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/jenisdiagnosa_m?select=id,jenisdiagnosa&param_search=jenisdiagnosa&query=${filter.query}&limit=10`)
    d_JenisDiagnosa.value = response
  }
  
  const kembaliKeun = () => {
    window.history.back()
  }
  
  const addDiagnosa = () => {
    input.value.detailDiagnosa.push({
      no: input.value.detailDiagnosa[input.value.detailDiagnosa.length - 1].no + 1,
    })
  }
  
  const removeDiagnosa = (index: any) => {
    input.value.detailDiagnosa.splice(index, 1)
  }
  
  
  
  
  
  
  setView()
  // setAutoFill()
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
  
  .tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
  }
  
  .tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
  }
  
  .tg th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
  }
  
  .tg .tg-0lax {
    text-align: left;
    vertical-align: top
  }
  </style>
  