<template>
  <div class="form-layout is-stacked-2" style="width: 100%;max-width: none;">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}<span v-if="isStuck"></span></h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpanCatatan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
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

          <div class="column is-12 p-0">
            <hr class="m-0">
          </div>

          <div class="column is-12 p-0">
            <div class="column is-12">
              <h1 style="font-size: larger; font-weight: bold">INFORMASI UMUM</h1>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Alamat</h1>
              <h1 style="font-weight: bold">(sesuai identitas)</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.alamatSI" class="input" type="text"/>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Alamat Tinggal</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.alamatT" class="input" type="text"/>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Pekerjaan</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.pekerjaan" class="input" type="text"/>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Dokter Pengirim</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <AutoComplete v-model="input.dokterPengirim" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" style="width: 100%;"/>
                  <!-- <VInput v-model="input.dokterPengirim" class="input" type="text"/> -->
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">RS/Klinik Pengirim</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.rsKlinik" class="input" type="text"/>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Dokter Radioterapi</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <AutoComplete v-model="input.dokterRad" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" style="width: 100%;"/>
                  <!-- <VInput v-model="input.dokterRad" class="input" type="text"/> -->
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 p-0">
            <hr class="m-0">
          </div>

          <div class="column is-12 p-0">
            <div class="column is-12">
              <h1 style="font-size: larger; font-weight: bold">INFORMASI PENYAKIT</h1>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">DIAGNOSIS</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VTextarea v-model="input.diagnosa" class="input" type="text"/>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">STADIUM/TNM</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.stadium" class="input" type="text"/>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">PATOLOGI ANATOMI</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.patologiAn" class="input" type="text"/>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Kode ICD-10</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <AutoComplete v-model="input.icd10" :suggestions="d_Diagnosa"
                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                    :field="'label'" placeholder=" ICD 10 ..." class="mt-2" />
                  <!-- <VInput v-model="input.icd10" class="input" type="text"/> -->
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Kode ICDO</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.icdO" class="input" type="text"/>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Kode ICD9CM</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <AutoComplete v-model="item.icd9" :suggestions="d_Diagnosa9"
                    @complete="fetchDiagnosa9($event)" :optionLabel="'label'" :dropdown="true"
                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                    placeholder="ketik untuk mencari " />
                  <!-- <VInput v-model="input.icd9" class="input" type="text"/> -->
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Tanggal Keluar/Meninggal</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VDatePicker v-model="input.tglKM" mode="date">
                    <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                    </template>
                </VDatePicker>
                  <!-- <VInput v-model="input.tglKM" class="input" type="text"/> -->
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 p-0">
            <hr class="m-0">
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
                      <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">Tanggal
                        Input</td>
                      <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">Tanggal
                        Registrasi</td>
                      <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">No
                        Registrasi</td>
                      <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">User</td>
                      <!-- <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="16%">Dokter</td> -->
                      <!-- <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="16%">Section
                      </td> -->
                    </tr>
                  </thead>
                  <tbody v-for="resep in listTemplate">
                    <tr>
                      <td
                        style="width:5%;text-align:center;vertical-align: middle;border: 1px solid black;padding: 5px;">
                        <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)"
                          color="info" v-tooltip-prime.top="'Pilih'">
                        </VIconButton>
                      </td>
                      <td style="width:25%;text-align:center;vertical-align: middle;border: 1px solid black;">
                        <span class="mb-2">{{ resep.created_at }}</span><br>
                      </td>
                      <td style="width:25%;text-align:center;vertical-align: middle;border: 1px solid black;">
                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                      </td>
                      <td style="width:25%;text-align:center;vertical-align: middle;border: 1px solid black;">
                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                      </td>
                      <!-- <td style="width:20%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                  </td> -->
                      <td style="width:25%;text-align:center;vertical-align: middle;border: 1px solid black;">
                        <span class="mb-2">{{ resep.user_input.namauser }}</span><br>
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
            :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack"
            breakpoint="960px">
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
                  <VIconButton color="danger" light raised circle icon="lucide:x"
                    @click="deleteTemplate(slotProps.data.id)" v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
                  <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                    color="info" v-tooltip-prime.top="'Pilih'">
                  </VIconButton>
                  <VIconButton type="button" raised circle icon="fas fa-pencil-alt"
                    @click="editTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Edit'"
                    v-if="!isAlltemplate">
                  </VIconButton>
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
    </div>
  </div>

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteUpdate, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as EMR from "../page-emr-plugins/pengkajian-extravasasi-pada-pasien-kemoterapi";
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let JenisKelamin = EMR.JenisKelamin();
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
const idTemplate: any = ref('');
const d_Diagnosa = ref([])
const d_Diagnosa9: any = ref([])
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isloadingLAMPAU: any = ref(false)
const isDisabled: any = ref(false)
const userLogin = useUserSession().getUser()
const isInput: any = ref(false);
const user = useUserSession().getUser().kelompokUser.kelompokUser
const riwayatFormulirPenyiaranRadioterapi = ref([]);
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  selectedMenu: [false],
  filter: '',
  lab: [],
  lab_GROUP: [],
  radiologi: [],
  patologi: []
})
//template dan riwayat
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const pegawaiId = useUserSession().getUser().pegawai.id
const COLLECTION: any = ref('FormulirIdentitasDanInformasiTentangPasien') //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
const input: any = ref({
  details: [{
    no: 1,
  }]
})
const riwayatResep: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])

//load riwayat catatan kegiatan
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0]
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
  // H.tandaTangan().set("TTDdokter", dataTTD.value.TTDdokter)
  // H.tandaTangan().set("TTDDokterPemeriksa", dataTTD.value.TTDDokterPemeriksa)
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
const editTemplate = async (dt: any) => {
  if (!dt) return;
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  input.value = dt;
  idTemplate.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
  checkTemplate.value = true
}
const simpanCatatan = () => {
  if (checkTemplate.value == true) {
    H.alert('warning', 'Simpan template ya, bukan simpan data :)')
    return;
  }
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
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
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
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

const checkTemplate: any = ref(false)
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

const addTemplate = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  input.value['id'] = ''
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const addRiwayat = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
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

useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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
const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.alamatSI = props.pasien.alamatlengkap;
  input.value.alamatT = props.pasien.alamatlengkap;
  input.value.pekerjaan = props.pasien.pekerjaan;
  input.value.pekerjaan = props.pasien.pekerjaan;

  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.alamatLengkap = props.pasien.alamatlengkap;
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  input.value.dokterPemeriksa = props.registrasi.dokter;
  input.value.tglPembuatan = new Date();
  input.value.ruangan = props.registrasi.namaruangan;
  input.value.caraBayar = props.registrasi.kelompokpasien;
  input.value.Siklus = 'Ke:'
};
const d_Ruangan: any = ref([])
const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}
const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then(response => {
      d_Dokter.value = response;
    });
};

const d_ObatRS: any = ref([])
const fetchObat = async (filter: any) => {
  const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  response.map((element: any) => {
    element.label = element.productname,
      element.value = element.id
  })
  d_ObatRS.value = response
}
const addNewItem = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details[input.value.details.length - 1].no + 1
  }
  input.value.details.unshift(newItem);
}
const removeItem = (index: any) => {
  let urut = input.value.details.length - 1
  input.value.details.splice(urut, 1)
}
const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(`/diagnosa/diagnosa-x-paging?name=${filter.query}&limit=10`)
    d_Diagnosa.value = response.diagnosa.map((item: any) => {
        return { value: item.id, label: item.kddiagnosa + ' -- ' + item.namadiagnosa }
    })
}
const fetchDiagnosa9 = async (filter: any) => {
    let query = ''
    if (filter) {
        query = filter.query.toLowerCase()
    }
    const response = await useApi().get(
        `/diagnosa/diagnosa-ix-paging?name=${query}&limit=10`)

    for (let x = 0; x < response.diagnosatindakan.length; x++) {
        const element = response.diagnosatindakan[x];
        element.label = element.kddiagnosatindakan + ' - ' + element.namadiagnosatindakan
    }
    d_Diagnosa9.value = response.diagnosatindakan
    // return response.diagnosatindakan.map((item: any) => {
    //     return { value: item.id, label: item.kddiagnosatindakan + ' - ' + item.namadiagnosatindakan, default: item }
    // })
}
setAutoFill();
</script>

<style lang="scss">
hr {
  border-top: 1px solid hsl(0deg 6.81% 88.68%);
  display: block;
  height: 2px;
  margin: 0px;
}

.tg {
  width: 100% !important;
  border: 1px solid black !important;
}

.th-rpo,
.td-rpo {
  padding: 2px;
  border: 1px solid black !important;
  vertical-align: inherit;
}

.th-rpo {
  text-align: center !important;
}
</style>
