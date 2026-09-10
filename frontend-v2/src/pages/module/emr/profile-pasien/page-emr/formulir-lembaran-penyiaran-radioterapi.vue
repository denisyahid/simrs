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
              @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideST :isHideCetak="true">
            </ButtonEmr>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
              @click="simpanTemplate()"> Simpan Template
            </VButton>
            <VButton type="button" rounded outlined color="warning" raised icon="feather:folder" :loading="isLoading"
              @click="pilihTemplateFix(index)"> Pilih Template
            </VButton>
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :loading="isLoading"
              @click="pilihTemplate(index)"> Pilih Riwayat
            </VButton>
          </div>

          <div class="column is-12 p-0">
            <hr class="m-0">
          </div>

          <div class="column is-12">
            <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                template</span></h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.namatemplate" rows="1">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;">Nama Pasien</h1>
            <VControl>
              <VInput v-model="input.namaPasien" class="input" type="text" disabled />
            </VControl>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;">Tanggal Lahir Pasien</h1>
            <VDatePicker disabled v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" disabled />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;">Jenis Kelamin</h1>
            <div style="display: flex;">
              <VField v-for="items in JenisKelamin" :key="items.value">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1 " :true-value="items.label"
                    :label="items.label" color="primary" circle disabled />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold;">No. Rekam Medis</h1>
            <VControl>
              <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
            </VControl>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold;">Tanggal Kunjungan</h1>
            <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()" disabled>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" disabled />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>
          <div class="column is-12 is-flex">
            <VField v-for="(items) in jenisTindakanRadioterapi" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input[items.value]" class="pt-1 pb-1 " :true-value="items.model"
                  :label="items.label" color="primary" circle />
              </VControl>
            </VField>
            <VField v-for="items in teknikPenyinaran" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.teknikPenyinaran" class="pt-1 pb-1 " :true-value="items.value"
                  :label="items.label" color="primary" circle />
              </VControl>
            </VField>
          </div>

          <div class="column is-12 columns pb-0">
            <div class="column is-4">
              <h1 style="font-weight: bold;">
                Pesawat
              </h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl>
                  <Multiselect v-model="input.pesawat" placeholder="--Pilih--" label="label" :options="pesawat"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>

            <div class="column is-4">
              <h1 style="font-weight: bold;">
                Energy
              </h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl>
                  <Multiselect v-model="input.energi" placeholder="--Pilih--" label="label" :options="energy"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>

            <div class="column is-4">
              <h1 style="font-weight: bold;">
                Accessories
              </h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl>
                  <Multiselect v-model="input.accessories" placeholder="--Pilih--" label="label" :options="accessories"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="columns is-multiline p-3">
            <div class="column is-3" v-for="(data, i) in formField ">
              <div class=" columns is-multiline">
                <div class="column is-12">
                  <span> {{ data.label }} : </span>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" :placeholder="data.label" v-model="input[data.value]" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>{{ data.addons }} </VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0">
            <h1 style="font-weight: bold">Keterangan:</h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.keterangan" rows="5" placeholder="Keterangan" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </div>
  </div>


  <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalTemplate = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table style="border: 1px solid black;" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="5%">#</td>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="10%">Tanggal
                    Input</td>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="10%">Tanggal
                    Registrasi</td>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">Nama
                    Ruangan</td>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">No
                    Registrasi</td>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">Pegawai</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:5%;text-align:center;vertical-align: middle;border: 1px solid black;padding: 5px;">
                    <VIconButton type="button" raised circle icon="fas fa-search" @click="addRiwayat(resep)"
                      color="info" v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                  <td style="width:10%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:10%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.user_input.namalengkap }}</span><br>
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
    @close="isAlltemplate = false; showModalTemplateFix = false">
    <template #content>
      <DataTable :pt="{
        table: { style: 'min-width: 50rem; min-height: 10rem;' },
        column: {
          bodycell: ({ state }) => ({
            class: [{ 'pt-0 pb-0': state['d_editing'] }]
          })
        }
      }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="10" paginator
        tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
        :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack" breakpoint="960px">
        <template #header>
          <div class="columns is-multiline">
            <div class="column is-8">
              <VField>
                <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
              </VField>
            </div>
            <div class="column is-4"></div>
          </div>
        </template>
        <template #empty> No customers found. </template>
        <template #loading>
          <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
          <p style="color:white">Loading data, please wait...</p>
        </template>
        <Column headerStyle="width: 8rem">
          <template #body="slotProps">
            <VButtons>
              <VIconButton color="danger" light raised circle icon="lucide:x" @click="deleteTemplate(slotProps.data.id)"
                v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
              <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Pilih'">
              </VIconButton>
              <!-- <VIconButton type="button" raised circle icon="fas fa-pencil-alt" @click="editTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Edit'" v-if="!isAlltemplate">
              </VIconButton> -->
            </VButtons>
          </template>
        </Column>
        <Column field="namatemplate" header="Nama" :sortable="true"></Column>
        <!-- <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
              <template #body="slotProps">
                {{ slotProps.data.registrasi.namaruangan }}
              </template>
            </Column> -->
        <Column field="created_at" header="Tanggal" :sortable="true">
          <template #body="slotProps">
            <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
          </template>
        </Column>
      </DataTable>
    </template>
  </VModal>

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import MultiSelect from 'primevue/multiselect';
import * as EMR from '../page-emr-plugins/lembaran-penyiaran-radioterapi'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
const user = useUserSession().getUser().kelompokUser.kelompokUser
let jenisTindakanRadioterapi = ref(EMR.jenisTindakanRadioterapi())
let JenisKelamin = ref(EMR.JenisKelamin())
let energy = ref(EMR.energy())
let accessories: any = ref(EMR.accessories())
let formField: any = ref(EMR.formField())
let pesawat = ref(EMR.pesawat())
let teknikPenyinaran = ref(EMR.teknikPenyinaran())
const idTemplate: any = ref('');
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
const RiwayatPsikososial: any = ref([
  { label: 'Baik', value: 'Baik' },
  { label: 'Tidak Baik', value: 'Tidak Baik' }
])

const route = useRoute()
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isLP: any = ref(false);
const isRiwayat: any = ref(false);
const isDisabled: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const dataTTD: any = ref([])
const d_Perawat: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('FormulirLembaranPenyiaranRadioterapi') //table mongodb
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
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
    if (response.length > 0) {
      isDisabled.value = false
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      H.tandaTangan().set("TTDperawat1", dataTTD.value.TTDperawat1)
    } else {
      await setAutoFill()
      isDisabled.value = true
    }
  })
}
const fetchPerawat = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Perawat.value = response })
}

const simpan = async () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  if (!object.teknikPenyinaran) {
    H.alert('error', 'Teknik Penyinaran belum diisi!')
    return;
  }
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object.riwayatPsikososial = RiwayatPsikososial.value
  object['TTDperawat1'] = H.tandaTangan().get("TTDperawat1")
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
  await useApi().post(`/emr/simpan-emr`, json).then(async (response: any) => {
    NOREC_EMRPASIEN.value = response.norec_emr
    if (isRiwayat.value) {
      listTemplate.value = []
      Object.keys(input.value).forEach(key => {
        input.value[key] = null;
      });
      setAutoFill()
      isRiwayat.value = false
      H.alert('success', 'Riwayat berhasil disimpan')
    } else {
      if (isLP.value) await makeCK(json);
      await loadRiwayat()
    }
    isLoading.value = false
  }).catch((e: any) => {
    isLoading.value = false
  })
}
function checkLP() {
  let params = `?norec_pd=${props.registrasi.norec_pd}&norec_apd=${props.registrasi.norec_apd}`
  let uri = `/emr/check-lembar-penyinaran${params}`;
  useApi().get(uri).then((res) => {
    if (res) {
      isLP.value = true;
    }
  })
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  input.value.tglPembuatan = new Date()
}

async function makeCK(json: any) {
  const cek = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&collection=FormulirLembaranPenyiaranRadioterapi`);
  return new Promise((resolve) => {
    let pasien = props.pasien;
    let object = {
      details: [
        {
          tanggalCatatan: new Date(),
          tindakan: `Radiasi : ${input.value.teknikPenyinaran}`,
          keteranganCatatan: `Fraksi Ke - ${cek.length}`,
          keluhan: '-'
        }
      ]
    }
    object.nocm = json.data.pasien.nocm
    object.pasien = json.data.pasien
    object.registrasi = json.data.registrasi
    let sendData = {
      'id': '',
      'norec_emr': '',
      'collection': 'CatatanKegiatanRadioterapi',
      'url_form': 'module-emr-profile-pasien-page-emr-catatan-kegiatan-radioterapi',
      'name_form': 'Catatan Kegiatan Radioterapi',
      'jenis_emr': 'asesmen_medis',
      'data': object
    }

    useApi().postNoMessage(`/emr/simpan-emr`, sendData).then(async (response: any) => {
      isLoading.value = false
      H.alert('success', 'Catatan Kegiatan berhasil dibuat');
      return resolve(true)
    }).catch((e: any) => {
      isLoading.value = true
      H.alert('error', 'Catatan Kegiatan gagal dibuat');
      return resolve(false)
    })
  })
}

const simpanTemplate = () => {
  if (input.value.namatemplate == null) {
    H.alert('warning', 'Isi nama template terlebih dahulu!')
    return;
  }
  let ID = idTemplate.value ? idTemplate.value : ''
  let object: any = {}

  object = input.value
  object.nocm = props.pasien.nocm
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

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    checkTemplate.value = false
    input.value.namatemplate = null
    delete object.namatemplate;
    delete object['_id'];
    delete object.nocm;
    delete object.pasien;
    delete object.regisstrasi;
    object.id = '';
  }).catch((e: any) => {
    isLoading.value = false
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

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true  
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&nocmfk=${ID_PASIEN}&isAll=true`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      for (var x = 0; x < responselast.length; x++) {
        responselast[x].no = x + 1
      }
      listTemplateFix.value = responselast //set ke inputan
      showModalTemplateFix.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      listTemplate.value = responselast //set ke inputan
      showModalTemplate.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const addRiwayat = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  isRiwayat.value = true
  showModalTemplate.value = false
  showModalTemplateFix.value = false
  H.alert('info', `Saat ini anda sedang mengakses riwayat tanggal ${response.created_at}`);
}

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(`/emr/hapus-template`, json).then((response: any) => {
    if (response.status !== 500) {
      isLoading.value = false;
      isAlltemplate.value = false;
      H.alert('sucess', response.message);
      pilihTemplateFix();
    } else {
      H.alert('danger', response.message);
    }
  }).catch((e: any) => {
    isLoading.value = false
    H.alert('danger', e);
  })
  showModalTemplateFix.value = false;
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

onMounted(() => {
  checkLP()
  setView()
})
</script>
