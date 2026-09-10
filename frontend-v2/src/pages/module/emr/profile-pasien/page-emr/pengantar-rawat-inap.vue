<template>
  <div class="form-layout is-stacked-2">
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

  <div class="column is-12">
    <VCard>
      <div class="column is-12" style="overflow-y: auto;">
        <table class="table-pri">
          <thead>
            <tr class="tr-pri">
              <th class="th-pri" width="2%" rowspan="2" style="vertical-align: inherit;">NO</th>
              <th class="th-pri" colspan="3" style="text-align: center;">Pasien</th>
              <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;">Ruangan Tujuan
              </th>
              <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;">Diagnosa</th>
              <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;">Rencana
                Tindakan
              </th>
              <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;">Jenis
                Pelayanan</th>
              <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;">DPJP</th>
              <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;">Mengetahui
              </th>
              <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;" width="2%">#
              </th>
            </tr>
            <tr class="tr-pri">
              <th class="th-pri" width="10%">Nama</th>
              <th class="th-pri" width="10%">Alamat</th>
              <th class="th-pri" width="10%">Asal Ruangan</th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in input.details" :key="index">
            <tr class="tr-pri">
              <td class="td-pri">{{ item.no }}</td>
              <td class="td-pri">
                <span style="font-weight: 500;">{{ props.pasien.namapasien }}</span>
              </td>
              <td class="td-pri">
                <span style="font-weight: 500;">{{ props.pasien.alamatlengkap }}</span>
              </td>
              <td class="td-pri">
                <span style="font-weight: 500;">{{ props.registrasi.namaruangan }}</span>
              </td>
              <td class="td-pri">
                <div class="column p-1">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.poli" :suggestions="d_Poli" @complete="fetchPoli($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Poli..." class="mt-2" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-pri">
                <div class="column p-1">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.diagnosa_1" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Diagnosa..." class="mt-2" />
                    </VControl>
                  </VField>
                </div>
                <div class="column p-1">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.diagnosa_2" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Diagnosa..." class="mt-2" />
                    </VControl>
                  </VField>
                </div>
                <div class="column p-1">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.diagnosa_3" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Diagnosa..." class="mt-2" />
                    </VControl>
                  </VField>
                </div>
                <div class="column p-1">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.diagnosa_4" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Diagnosa..." class="mt-2" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-pri">
                <div class="column p-0 mt-3">
                  <VField>
                    <VControl>
                      <VTextarea v-model="item.rencanaTindakan" rows="8" placeholder="Rencana Tindakan ...">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-pri">
                <div class="column p-0 mt-3" v-for="(data) in jnsPelayanan">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="item.jnsPelayanan" :true-value="data.code" class="p-0" :label="data.label"
                        color="primary" circle />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-pri">
                <div class="column p-1">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.dokterRawat" disabled
                        style="font-weight: bold !important;background: darkgrey;" :optionLabel="'label'" :dropdown="true"
                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Cari DPJP..." class="mt-2" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-pri">
                <div class="column p-0 mt-3" style="text-align: center;">
                  <TandaTangan :elemenID="'signature_' + [index]" :width="'150'" :height="'150'" class="dek" />
                  <!-- <TandaTangan :elemenID="'signature'[index]" :width="'150'" :height="'150'" /> -->
                </div>

                <div class="column">
                  <span style="font-weight: 500;">Dokter Pemeriksa</span>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.dokterPemeriksa" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"  @item-select="setTandaTanganDokter($event,index)"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Dokter Pemeriksa..."
                        class="mt-2" />
                    </VControl>
                  </VField>
                </div>
              </td>
              <td class="td-pri" style="vertical-align: inherit    ;">
                <div class="column">
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
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'

// =====================================

const jnsPelayanan = [
  {
    "label": "Pelayanan Preventif",
    "code": "PPR",
  },
  {
    "label": "Pelayanan Paliatif",
    "code": "PPA",
  },
  {
    "label": "Pelayanan Rehabilitatif",
    "code": "PRE",
  },
  {
    "label": "Pelayanan Kuratif",
    "code": "PKF",
  },
  {
    "label": "Pelayanan Cathclab/OK",
    "code": "PCA",
  },
  {
    "label": "Pelayanan Intensif",
    "code": "PIN",
  },
]


// =====================================

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
  }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Poli: any = ref([])
const d_Diagnosa: any = ref([])
const d_Dokter: any = ref([])
const dataTTD: any = ref([])
const d_DokterDPJP: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('PengantarRawatInap') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref(
  {
    details: [{
      no: 1,
      namaPasien: props.pasien.namapasien,
      alamatLengkap: props.pasien.alamatlengkap,
      ruanganAsal: props.registrasi.namaruangan,
    }]
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
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length > 0) {
      input.value = response[0]
      dataTTD.value = response[0].details

      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
    }
  })

  dataTTD.value.forEach((element: any, i: any) => {
    H.tandaTangan().set("signature_" + [i], element.ttd)
  })
  // debug/ger
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
    element.ttd = H.tandaTangan().get("signature_" + [i])
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

const kembaliKeun = () => {
  window.history.back()
}

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
    namaPasien: props.pasien.namapasien,
    alamatLengkap: props.pasien.alamatlengkap,
    ruanganAsal: props.registrasi.namaruangan,
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const fetchPoli = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Poli.value = response
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const setTandaTanganDokter = async (e: any,i:any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signature_"+i, element.ttd)
      }else{
        H.tandaTangan().set("signature_"+i, '')
      }
    })
}
// const fetchDokterDPJP = async () => {
//   await useApi().get('emr/get-dokter-dpjp?nocmfk=' + ID_PASIEN).then((response) => {
//     input.dpjp = response.dokter
//   })
// }
const setAutoFill = async () => {
  input.value.dokterRawat = props.registrasi.dokter
}

setView()
loadRiwayat()
setAutoFill()
// fetchDokterDPJP()
</script>

<style lang="scss">
.table-pri {
  width: 250% !important;
  border-collapse: collapse !important;
}

.table-pri,
.tr-pri,
.th-pri,
.td-pri {
  border: 1.6px solid black !important;
}

.th-pri,
.td-pri {
  padding: 8px !important;
}
</style>
