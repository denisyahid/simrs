<template>
  <ConfirmDialog group="templating">
    <template #message="slotProps">
      <div style="width:500px;height:300px;">
        <table style="width:100%;height:100%;border-collapse: collapse">
          <tr>
            <td style="text-align:center;vertical-align:middle">
              <i :class="slotProps.message.icon" style="font-size:125px;text-align:center;color:#FDDA0D"></i>
            </td>
          </tr>
          <tr>
            <td style="padding:7px;text-align:center">
              <p style="font-size:large">{{ slotProps.message.message }}</p>
            </td>
          </tr>
        </table>
      </div>
    </template>
  </ConfirmDialog>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :isHideCetak="false">
            </ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
      <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
        @click="pilihTemplateFix(index)"> Pilih Template
      </VButton>
    </div>

    <hr class="m-0">

    <div class="column is-12">
      <h1>Nama Template&emsp;&emsp;
        <span style="color:red">**Hanya diisi jika ingin membuat template</span>
      </h1>
      <VField>
        <VControl>
          <VTextarea v-model="input.namatemplate" rows="1">
          </VTextarea>
        </VControl>
      </VField>
    </div>

    <hr class="m-0">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-2">
            <h1 style="font-weight: bold">NAMA PASIEN:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.namaPasien" rows="1"> </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
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
          <div class="column is-2">
            <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
          </div>
          <div class="column is-10" style="display: flex">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label" :label="items.label"
                  color="primary" circle />
              </VControl>
            </VField>
          </div>

          <div class="column is-2">
            <h1 style="font-weight: bold">No. Rekam Medis:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="columns is-multiline">
          <div class="column is-2">
            <h1 style="font-weight: bold">Extend Life PD Transfer Set:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.extendLife" rows="1"> </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>

        <div class="columns is-multiline">
          <div class="column is-2">
            <h1 style="font-weight: bold">Ultraclamp:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.ultracamp" rows="1"> </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>

        <div class="columns is-multiline">
          <div class="column is-2">
            <h1 style="font-weight: bold">T202:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.t202" rows="1"> </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>

        <div class="columns is-multiline">
          <div class="column is-2">
            <h1 style="font-weight: bold">Titanium Adaptor:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.titanium" rows="1"> </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>

        <div class="columns is-multiline">
          <div class="column is-2">
            <h1 style="font-weight: bold">DOKTER:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <AutoComplete v-model="input.CBDokter" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari nama dokter" />
              </VControl>
            </VField>
          </div>
        </div>

        <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
          @close="showModalTemplate = false">
          <template #content>
            <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                <div style="overflow-y:auto;" class="mt-1">
                  <table class="tg table-tg" v-if="listTemplate.length > 0">
                    <thead>
                      <tr>
                        <td class="tg-0lax text-center" width="5%">#</td>
                        <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                        <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                        <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                        <td class="tg-0lax text-center" width="20%">No EMR</td>
                        <td class="tg-0lax text-center" width="20%">Dokter</td>
                        <td class="tg-0lax text-center" width="20%">Penyakit</td>
                        <td class="tg-0lax text-center" width="20%">Section</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplate">
                      <tr>
                        <td style="width:5%;text-align:center">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                            color="info" v-tooltip-prime.top="'Pilih'">
                          </VIconButton>
                        </td>
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
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.riwayatpenyakit }}</span><br>
                        </td>
                        <td style="width:25%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </form>
          </template>
        </VModal>
        <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
          @close="showModalTemplateFix = false">
          <template #content>
            <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                <span style="font-size:9pt;font-weight:bold">List Template</span>
                <div style="overflow-y:auto;" class="mt-1">
                  <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                    <thead>
                      <tr>
                        <td class="tg-0lax text-center" width="15%">#</td>
                        <td class="tg-0lax text-center" width="15%">No</td>
                        <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                        <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                        <td class="tg-0lax text-center" width="50%">Nama Template</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplateFix">
                      <tr>
                        <td style="width:15%;text-align:center">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                            color="info" v-tooltip-prime.top="'Pilih'">
                          </VIconButton>
                        </td>
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
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </form>
          </template>
        </VModal>
      </VCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onMounted, nextTick, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import MultiSelect from 'primevue/multiselect'
import * as EMR from '../page-emr-plugins/asuhan-keperawatan-dan-observasi-pasien-hemodialisa'
import Fieldset from 'primevue/fieldset'
import Calendar from 'primevue/calendar'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import moment from 'moment'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let JenisKelamin: any = ref(EMR.JenisKelamin())
let JenisPasien: any = ref(EMR.JenisPasien())
let JenisHD: any = ref(EMR.JenisHD())
let kesadaran: any = ref(EMR.kesadaran())
let kesadaranUmum: any = ref(EMR.kesadaranUmum())
let vitalSign: any = ref(EMR.vitalSign())
let Konjungtiva: any = ref(EMR.Konjungtiva())
let Ekstremitas: any = ref(EMR.Ekstremitas())
let aksesVaskular: any = ref(EMR.aksesVaskular())
let resikoJatuh: any = ref(EMR.resikoJatuh())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let pemantauanTindakanKeperawatan: any = ref(EMR.pemantauanTindakanKeperawatan())
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
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
const pasien: any = ref({})
const confirm = useConfirm();
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const showModalTemplateFix: any = ref(false)
const showModalTemplate: any = ref(false)
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const d_Obat: any = ref([])
const d_dokter: any = ref([])
const d_Dokter: any = ref([])
const d_Perawat: any = ref([])
const dataTTD: any = ref([])
const isDialisisEvent = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  norec_apd: props.registrasi.apd.norec_apd,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('ResepKhususCAPDPaketAwal')
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const input: any = ref({
  tanggal: new Date(),
  detailPelaksanaan: [{ no: 1 }],
  detailObatResep: [{ no: 1 }],

  sisaPriming: 0,
  TransfusiAtauObat: 0,
  washOut: 0,
  minum: 0,

  totalBalance: 0,

  ultrafiltrasi: 0,
  kencing: 0,
  muntah: 0,
  drain: 0,

  penilaianNyeriYa: '',
  penilaianNyeriTidak: '',
  skalaNyeri: '',
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}


const loadRiwayat = async () => {
  try {
    const response: any = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}`);
    if (response.length) {
      input.value = response[0];
      if (NOREC_EMRPASIEN.value === '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
    } else {
      setAutoFill();
    }
  } catch (error) {
    console.error('Error loading data:', error);
  }
};


const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object['ttdPerawatKanulasiAkses'] = H.tandaTangan().get("ttdPerawatKanulasiAkses");
  object['ttdPerawatTerminasi'] = H.tandaTangan().get("ttdPerawatTerminasi");
  object['ttdPerawatPenanggungJawab'] = H.tandaTangan().get("ttdPerawatPenanggungJawab");
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  console.log('JSON', json)
  // return json;
  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}

const print = async () => {
  H.printBlade(
    `emr/cetak-formulir-asuhan-keperawatan-dan-observasi-pasien-hemodialisa?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  )
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.tglPembuatan = new Date()
}

const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Perawat.value = response
    })
}

const addNewPelaksanaan = () => {
  input.value.detailPelaksanaan.push({
    no: input.value.detailPelaksanaan[input.value.detailPelaksanaan.length - 1].no + 1,
  })
}

const removePelaksanaan = (index: any) => {
  input.value.detailPelaksanaan.splice(index, 1)
}

const addNewObat = () => {
  input.value.detailObatResep.push({
    no: input.value.detailObatResep[input.value.detailObatResep.length - 1].no + 1,
  })
}

const removeObat = (index: any) => {
  input.value.detailObatResep.splice(index, 1)
}

const jumlahCairanMasuk = computed(() => {
  return (
    Number(input.value.sisaPriming || 0) +
    Number(input.value.TransfusiAtauObat || 0) +
    Number(input.value.washOut || 0) +
    Number(input.value.minum || 0)
  )
})

const jumlahCairanKeluar = computed(() => {
  return (
    Number(input.value.ultrafiltrasi || 0) +
    Number(input.value.kencing || 0) +
    Number(input.value.muntah || 0) +
    Number(input.value.drain || 0)
  )
})

const jumlahlBalance = computed(() => {
  return Number(jumlahCairanMasuk.value) - Number(jumlahCairanKeluar.value)
})


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
    let rouutename = from?.name;
    let indexTabs = route.params.index_tabs;
    let cacheKey = `TAB~${props.registrasi.noregistrasi}~${rouutename}~${indexTabs}`;

    // Simpan cache saat berpindah halaman (kecuali ke profile-pasien)
    if (to.name !== 'profile-pasien') {
      H.cacheEMR().set(cacheKey, input.value);
      console.log(`Cache disimpan untuk ${cacheKey}`);
    }

    // Hapus cache hanya jika tujuan adalah 'profile-pasien'
    if (to.name === 'profile-pasien') {
      H.cacheEMR().remove(cacheKey);
      console.log(`Cache dihapus karena berpindah ke profile-pasien: ${cacheKey}`);
    }

  } catch (error) {
    console.error('Error saat menyimpan/menghapus cache:', error);
  }
  next();
});

const d_Pegawai: any = ref([])
const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Pegawai.value = response
}


onMounted(() => {
  setView()
  loadRiwayat()
  // loadRiwayatSM()
  setAutoFill()
  fetchPerawat({ query: '' })
  // fetchObat({ query: '' })
})

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      console.log(responselast)
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          responselast[x].id = ''
        }
        listTemplateFix.value = responselast //set ke inputan
        showModalTemplateFix.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}
const addTemplate = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  input.value['id'] = ''
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}
const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
  let ID = input.id ? input.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
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

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    input.value.namatemplate = null
  }).catch((e: any) => {
    isLoading.value = false
  })
}


</script>

<style lang="scss">
h1 {
  font-weight: bold;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100% !important;
}

.tg td {
  border: 1px solid black !important;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  text-align: center !important;
  border: 1px solid black !important;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: bold;
  overflow: hidden;
  background-color: aquamarine;
  vertical-align: middle;
  padding: 10px 5px;
  word-break: normal;
}

.p-fieldset-content {
  background-color: white !important;
}
</style>
