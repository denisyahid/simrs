<template>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
             <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>
  
      </div>
    </div>
  
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline" style="margin-top: 50px;">
            <div class="column is-6" style="padding-left: 20px;">
                <div class="columns is-multiline">
                    <div class="column is-2">
                        <span class="label-ro">Nama Operasi</span>
                    </div>
                    <div class="column is-9">
                        <VField class="pt-3">
                            <VControl>
                                <VInput type="text" v-model="input.namaoperasi" style="margin-top: -15px;"/>
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-6" style="padding-right: 20px;">
                <div class="columns is-multiline">
                    <div class="column is-2">
                        <span class="label-ro">Tgl Operasi</span>
                    </div>
                    <div class="column is-10">
                        <VDatePicker v-model="input.tglOperasi" class="pt-3" mode="dateTime" style="width: 100%; margin-top: -15px;" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                            <VField>
                            <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" placeholder="Tanggal Operasi" v-on="inputEvents" />
                            </VControl>
                            </VField>
                        </template>
                        </VDatePicker>
                    </div>
                </div>
            </div>
        </div>
        <div class="column mt-2" style="overflow: auto;">
          <table class="table-rpo">
            <thead>
              <tr>
                <th class="th-rpo" width="5%" rowspan="2">NO</th>
                <th class="th-rpo" width="30%" rowspan="2">NAMA ALAT</th>
                <th class="th-rpo" rowspan="2">KODE</th>
                <th class="th-rpo" rowspan="2">JML</th>
                <th class="th-rpo" colspan="4">JUMLAH</th>
                <th class="th-rpo" width="20%" rowspan="2">KET.</th>
                <th class="th-rpo" rowspan="2" width="10%">#</th>
              </tr>
              <tr>
                <th class="th-rpo">A</th>
                <th class="th-rpo">B</th>
                <th class="th-rpo">C</th>
                <th class="th-rpo">D</th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.details" :key="index">
              <tr>
                <td class="td-rpo" style="text-align: center;">
                    <span class="label-ro">{{ index+1 }}</span>
                </td>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="1" v-model="item.namaalat"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="1" v-model="item.kode"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="1" v-model="item.jumlah"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="1" v-model="item.jumlaha"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="1" v-model="item.jumlahb"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="1" v-model="item.jumlahc"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="1" v-model="item.jumlahd"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="1" v-model="item.keterangan"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo" style="vertical-align: inherit">
                    <div class="column">
                    <VButtons style="justify-content:space-around">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                        v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                        @click="removeItem(index)" color="danger">
                        </VIconButton>
                    </VButtons>
                    </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="column is-12 mt-5" style="padding-left: 20px;">
            <div class="columns is-multiline">
                <div class="column is-1">
                    <span class="label-ro">Tgl Steril</span>
                </div>
                <div class="column is-11">
                    <VDatePicker v-model="input.tglSteril" class="pt-3" mode="dateTime" style="width: 100%; margin-top: -15px;" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                        <VField>
                        <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal Steril" v-on="inputEvents" />
                        </VControl>
                        </VField>
                    </template>
                    </VDatePicker>
                </div>
            </div>
        </div>
        <div class="column is-12" style="padding-left: 20px;">
            <div class="columns is-multiline">
                <div class="column is-1">
                    <span class="label-ro">Tgl Expired</span>
                </div>
                <div class="column is-11">
                    <VDatePicker v-model="input.tglExpired" class="pt-3" mode="dateTime" style="width: 100%; margin-top: -15px;" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                        <VField>
                        <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal Expired" v-on="inputEvents" />
                        </VControl>
                        </VField>
                    </template>
                    </VDatePicker>
                </div>
            </div>
        </div>
        <div class="column is-12" style="padding-left: 20px;">
            <div class="columns is-multiline">
                <div class="column is-1">
                    <span class="label-ro">Kode Alat</span>
                </div>
                <div class="column is-11">
                    <VField class="pt-3">
                        <VControl>
                            <VInput type="text" v-model="input.kodealat" style="margin-top: -15px;"/>
                        </VControl>
                    </VField>
                </div>
            </div>
        </div>
        <div class="columns" style="margin-top: 100px;">
          <div class="column is-3">
            <div class="column" style="text-align:center;">
              <h1>Cross check pengepakan <br> CSSD</h1>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.CBPetugas1" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="
                  'label'" class="mt-2" />
              </VControl>
              <TandaTangan :elemenID="'TTDPetugas1'" :width="'150'" :height="'150'" class="dek mt-5" />
            </div>
          </div>
          <div class="column is-3">
            <div class="column" style="text-align:center;">
              <h1>Sebelum operasi <br> Instrumen</h1>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.CBPetugas2" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="
                  'label'" class="mt-2" />
              </VControl>
              <TandaTangan :elemenID="'TTDPetugas2'" :width="'150'" :height="'150'" class="dek mt-5" />
            </div>
          </div>
          <div class="column is-3">
            <div class="column" style="text-align:center;">
              <h1>Setelah Operasi <br> Sirkuler</h1>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.CBPetugas3" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="
                  'label'" class="mt-2" />
              </VControl>
              <TandaTangan :elemenID="'TTDPetugas3'" :width="'150'" :height="'150'" class="dek mt-5" />
            </div>
          </div>
          <div class="column is-3">
            <div class="column" style="text-align:center;">
              <h1>Pengembalian ke CSSD <br> CSSD</h1>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.CBPetugas4" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="
                  'label'" class="mt-2" />
              </VControl>
              <TandaTangan :elemenID="'TTDPetugas4'" :width="'150'" :height="'150'" class="dek mt-5" />
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
  const d_Pegawai: any = ref([])
  const d_Obat: any = ref([])
  const dataTTD: any = ref([])
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
  })
  const COLLECTION: any = ref('ImplementasiKeperawatan') //table mongodb
  const NOREC_EMRPASIEN: any = ref('')
  const input: any = ref({
    details: [{
      no: 1,
    }],
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
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        if (response.length) {
          input.value = response[0] //set ke inputan
          if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
          }
          if (response[0].ttdKeluarga) {
            H.tandaTangan().set("signatureKeluarga", response[0].ttdKeluarga)
          }
          if (response[0].ttdPetugas) {
            H.tandaTangan().set("signaturePetugas", response[0].ttdPetugas)
          }
          dataTTD.value = response[0]
          H.tandaTangan().set("TTDPetugas1", dataTTD.value.TTDPetugas1)
          H.tandaTangan().set("TTDPetugas2", dataTTD.value.TTDPetugas2)
          H.tandaTangan().set("TTDPetugas3", dataTTD.value.TTDPetugas3)
          H.tandaTangan().set("TTDPetugas4", dataTTD.value.TTDPetugas4)
        }
      })
  }

  const d_Petugas: any = ref([])
    const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
    }
  
  const simpan = async () => {
    let ID = input.value.id ? input.value.id : ''
  
    let object: any = {}
  
    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object['TTDPetugas1'] = H.tandaTangan().get("TTDPetugas1");
    object['TTDPetugas2'] = H.tandaTangan().get("TTDPetugas2");
    object['TTDPetugas3'] = H.tandaTangan().get("TTDPetugas3");
    object['TTDPetugas4'] = H.tandaTangan().get("TTDPetugas4");
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
        loadRiwayat();
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
  
  const setTandaTanganPegawai = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
      if (element) {
        H.tandaTangan().set("signaturePetugas", element.ttd)
      } else {
        H.tandaTangan().set("signaturePetugas", '')
      }
    })
  }
  
  const fetchObat = async (filter: any) => {
  
    await useApi().get(
      `emr/get-obat?filter=${filter.query}`
    ).then((response) => {
      d_Obat.value = response
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
  
  setView()
  loadRiwayat()
  </script>
  
  <style lang="scss">
  .table-rpo {
    width: 100%;
    border: 1px solid;
  }
  
  .th-rpo,
  .td-rpo {
    padding: 7px;
    border: 1px solid black;
    vertical-align: inherit;
  }
  
  .th-rpo {
    text-align: center !important;
  }
  
  .p-fieldset-legend {
    margin-left: 15px;
  }
  </style>