<style lang="scss">
.table-rpo {
  width: 100%;
  border: 1px solid black;
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
</style>
<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> Penjadwalan Radioterapi</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <!-- <div class="buttons is-flex" style="align-items: center;">
          <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :isLoading="isLoading"
            @click="pilihTemplate(index)"> Lihat Riwayat
          </VButton>
        </div>

        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1"> -->

        <div class="column is-12 columns">
          <div class="column is-5">
            <VField label="Diagnosa">
              <VTextarea rows="1" v-model="input.TBDiagnosa"></VTextarea>
            </VField>
          </div>
          <div class="column is-5">
            <VField label="Permintaan Terapi">
              <VTextarea rows="1" v-model="input.TAPermintaanTerapi"></VTextarea>
            </VField>
          </div>
        </div>
        <div class="column pt-0" style="overflow: auto;">
          <table class="table-rpo">
            <thead>
              <tr>
                <th class="th-rpo" width="15%" rowspan="2">Program</th>
                <th class="th-rpo" rowspan="2">Tanggal</th>
                <th class="th-rpo" colspan="3">TTD</th>
                <th class="th-rpo" rowspan="2" width="10%">#</th>
              </tr>
              <tr>
                <th class="th-rpo">Pasien</th>
                <th class="th-rpo">Dokter</th>
                <th class="th-rpo">Terapis</th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.details" :key="index">
              <tr>
                <td class="td-rpo">
                  <VField class="">
                    <VControl>
                      <VTextarea rows="6" v-model="item.program"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo" style="text-align: center;">
                  <VDatePicker v-model="item.tanggal" mode="date" style="width: 100%;" :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </td>
                <td class="td-rpo" style="text-align: center;">
                  <TandaTangan :elemenID="`parafPasien_${index}`" :width="'150'" :height="'150'" class="dek" />
                </td>
                <td class="td-rpo">
                  <VField class="pt-3">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.parafDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..." />
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo">
                  <VField class="pt-3">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.parafPegawai" :suggestions="d_Pegawai"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..." />
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

        <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
          @close="showModalTemplate = false">
          <template #content>
            <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                <p style="font-size:9pt;font-weight:bold;padding: 7px;">List Riwayat</p>
                <div style="overflow-y:auto;">
                  <table style="width: 100%;border: 1px solid black;" v-if="listTemplate.length > 0">
                    <thead>
                      <tr>
                        <td style="text-align: center;font-weight: bold;" width="15%">Tanggal
                          Input</td>
                        <td style="text-align: center;font-weight: bold;" width="15%">Tanggal
                          Registrasi</td>
                        <td style="text-align: center;font-weight: bold;" width="15%">No
                          Registrasi</td>
                        <td style="text-align: center;font-weight: bold;" width="15%">No EMR
                        </td>
                        <!-- <td style="text-align: center;font-weight: bold;" width="20%">Dokter</td> -->
                        <td style="text-align: center;font-weight: bold;" width="20%">Section
                        </td>
                        <td style="text-align: center;font-weight: bold;" width="10%">#</td>
                      </tr>
                    </thead>
                    <tbody v-for="dataR in listTemplate">
                      <tr style="border: 1px solid black;">
                        <td class="padding" style="text-align:center;vertical-align: middle;">
                          <span class="mb-2">{{ dataR.created_at }}</span><br>
                        </td>
                        <td class="padding" style="text-align:center;vertical-align: middle;">
                          <span class="mb-2">{{ dataR.registrasi.tglregistrasi }}</span><br>
                        </td>
                        <td class="padding" style="text-align:center;vertical-align: middle;">
                          <span class="mb-2">{{ dataR.registrasi.noregistrasi }}</span><br>
                        </td>
                        <td class="padding" style="text-align:center;vertical-align: middle;">
                          <span class="mb-2">{{ dataR.pasien.nocm }}</span><br>
                        </td>
                        <!-- <td class="padding" style="text-align:center;vertical-align: middle;">
                                                    <span class="mb-2">{{ dataR.dpjpUtama }}</span><br>
                                                </td> -->
                        <td class="padding" style="text-align:center;vertical-align: middle;">
                          <span class="mb-2">{{ dataR.registrasi.namaruangan }}</span><br>
                        </td>
                        <td class="padding" style="text-align:center;vertical-align: middle;">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(dataR)"
                            color="info" v-tooltip-prime.top="'Pilih'">
                          </VIconButton>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </form>
          </template>
        </VModal>
      </div>
    </div>
  </div>

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const jenisPegawai = useUserSession().getUser().pegawai.jenisPegawai.jenispegawai.trim()
const user = useUserSession().getUser().pegawai;
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
const d_Dokter: any = ref([])
const d_Obat: any = ref([])
const dataTTD: any = ref([])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('PenjadwalanRadioterapi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{
    no: 1,
  }],
})
const route = useRoute()
const setView = () => {
  useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
// const loadRiwayat = async () => {
//   await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((response: any) => {
//     if (response.length) {
//       input.value = response[0]
//       if (NOREC_EMRPASIEN.value == '') {
//         NOREC_EMRPASIEN.value = response[0].emrpasienfk
//       }
//       dataTTD.value = response[0]
//       for (let i = 0; i <= input.value.details.length; i++) {
//         await nextTick();
//         const fieldName = `parafPasien_${i}`;
//         H.tandaTangan().set(fieldName, dataTTD.value[fieldName]);
//       }
//     } else {
//       setAutoFill()
//     }
//   })
// }
const loadRiwayat = async () => {
  try {
    const response: any = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`);
    if (response.length) {
      input.value = response[0];
      if (NOREC_EMRPASIEN.value === '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
      dataTTD.value = response[0];
      for (let i = 0; i < input.value.details.length; i++) {
        await nextTick();//test bawa di sini
        const fieldName = `parafPasien_${i}`;
        H.tandaTangan().set(fieldName, dataTTD.value[fieldName]);
      }
    } else {
      setAutoFill();
    }
  } catch (error) {
    console.error('Error loading data:', error);
  }
};

const setAutoFill = async () => {
  input.value.details.forEach((element: any) => {
    element.tanggal = new Date();
    element.parafDokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
    element.parafPegawai = { label: user.namaLengkap, value: user.id }
  });
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  const lastDetail = input.value.details[input.value.details.length - 1];

  if (!lastDetail.program) {
    H.alert("warning", "Program Radioterapi Harus Diisi");
    return;
  }
  if (!lastDetail.tanggal) {
    H.alert("warning", "Tanggal Radioterapi Harus Diisi");
    return;
  }
  if (!lastDetail.parafDokter?.label) {
    H.alert("warning", "Dokter Harus Diisi dengan pilihan yang tersedia");
    return;
  }
  if (!lastDetail.parafPegawai?.label) {
    H.alert("warning", "Pegawai Harus Diisi dengan pilihan yang tersedia");
    return;
  }

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  for (let i = 0; i <= input.value.details.length; i++) {
    object[`parafPasien_${i}`] = H.tandaTangan().get(`parafPasien_${i}`);
  }
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
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&query=${filter.query}&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
    d_Pegawai.value = response
  })
}

const addNewItem = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details[input.value.details.length - 1].no + 1,
    tanggal: new Date(),
  }
  input.value.details.push(newItem);
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
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
</script>
