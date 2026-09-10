<style lang="scss">
h1 {
  font-weight: bold !important;
}

table {
  width: 100% !important;
  border-collapse: collapse !important;
}

.table-list-darah th {
  text-align: center !important;
  vertical-align: middle !important;
  font-weight: bold !important;
  border: 1px solid black !important;
  background-color: lightcoral !important;
}

.table-list-darah td {
  border: 1px solid black !important;
  vertical-align: middle !important;
  padding: 3px !important;
}
</style>
<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Formulir Pembatalan Penggunaan Komponen Darah Yang Sudah Uji Pratransfusi</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                :registrasi="props.registrasi" @simpan="simpan" @simpanTemplate="simpanTemplate"
                @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-12 buttons mb-0 mt-0 pb-1" style="margin:10px;vertical-align:middle">
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" :loading="isLoading"
                @click="pilihTemplateFix(index)"> Pilih Template
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

            <!-- <div class="column is-3">
              <span>Nama Pasien</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.namaPasien" disabled />
              </VControl>
            </div>

            <div class="column is-3">
              <span>Nomor RM</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.nocmPasien" disabled />
              </VControl>
            </div> -->

            <div class="column is-3">
              <span>Golongan Darah</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.golonganDarah" />
              </VControl>
            </div>

            <div class="column is-3">
              <span>Rhesus</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.rhesus" />
              </VControl>
            </div>

            <div class="column is-3">
              <span>No. Permintaan</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.nomorPermintaan" />
              </VControl>
            </div>

            <div class="column is-3">
              <span>Tgl. Permintaan</span>
              <VDatePicker v-model="input.tglPermintaan" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>

            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>

            <div class="column is-3">
              <span>Tgl. & Jam Pembatalan</span>
              <VDatePicker v-model="input.tglPembatalan" mode="datetime" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>

            <div class="column is-3">
              <span>Ruangan</span>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
              </VControl>
            </div>

            <div class="column is-3">
              <span>Petugas Yang Membatalkan</span>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.petugasYangMembatalkan" :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
              </VControl>
            </div>

            <div class="column is-3">
              <span>Alasan Pembatalan</span>
              <VField>
                <VTextarea rows="2" v-model="input.alasanPembatalan"></VTextarea>
              </VField>
            </div>

            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>

            <div class="column is-3">
              <span>Yang Menerima Pembatalan</span>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.penerimaPembatalan" :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
              </VControl>
            </div>

            <div class="column is-12 columns is-multiline m-0">
              <div class="column is-9 is-flex is-align-items-center">
                <h1>DARAH YANG DIBATALKAN</h1>
              </div>
              <div class="column is-3 py-1 ml-auto buttons pb-0 mb-0" style="text-align: center !important;">
                <VButton type="button" rounded color="info" raised icon="feather:droplet" :loading="isLoading"
                  @click="addNewItem('listDarah')">
                  Tambah Data
                </VButton>
                <VButton type="button" rounded color="danger" raised icon="feather:delete" :loading="isLoading"
                  :disabled="input.listDarah && input.listDarah.length == 1" @click="removeItem('listDarah')"> Hapus
                  Data
                </VButton>
              </div>

              <table class="table-list-darah">
                <tr>
                  <th rowspan="2" style="width: 25%;">No. Kantong Darah</th>
                  <th rowspan="2" style="width: 25%;">Jenis Komponen Darah</th>
                  <th rowspan="2" style="width: 25%;">Jumlah (CC)</th>
                  <th colspan="2" style="width: 25%;">Petugas</th>
                </tr>
                <tr>
                  <!-- <th colspan="2">Nama</th> -->
                  <th>Nama</th>
                  <th>TTD</th>
                </tr>
                <tr v-for="(item, index) in input.listDarah">
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.noKantongDarah" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.jenisKomponenDarah" />
                    </VControl>
                  </td>
                  <td>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.jumlah" />
                    </VControl>
                  </td>
                  <td>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.namaPetugas" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" />
                    </VControl>
                  </td>
                  <td>
                    <TandaTangan :elemenID="`TTD_Petugas_${index}`" :width="'150'" :height="'150'" class="dek" />
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <!-- form baru -->
      </div>
    </div>
  </div>

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
            <div class="column is-4 is-flex" style="justify-content: center;">
              <VControl>
                <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
              </VControl>
            </div>
          </div>
        </template>
        <template #empty> No templates found. </template>
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
              <VIconButton type="button" raised circle icon="fas fa-pencil-alt" @click="editTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Edit'" v-if="!isAlltemplate">
              </VIconButton>
            </VButtons>
          </template>
        </Column>
        <Column field="namatemplate" header="Nama" :sortable="true"></Column>
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
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api';

useHead({ title: `Formulir Pembatalan Penggunaan Komponen Darah Yang Sudah Uji Pratransfusi - ` + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('FormulirPembatalanPenggunaanKomponenDarah') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const idTemplate: any = ref('');
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isSave = ref(false);
const isLoading = ref(false)
const checkTemplate: any = ref(false)
const isAlltemplate: any = ref(false);
const pasien: any = ref({})
const input: any = ref({})
// const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
// const listTemplate: any = ref([])
// const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });

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

const setAutoFill = async () => {
  let d = input.value;
  let reg = props.registrasi;
  let ps = props.pasien;

  // d.namaPasien = ps.namapasien;
  // d.nocmPasien = ps.nocm;
  d.tglPermintaan = new Date();
  d.tglPembatalan = new Date();
  d.ruangan = { value: reg.objectruanganfk, label: reg.namaruangan };
  d.listDarah = [{}];
}

const loadRiwayat = async () => {
  isLoading.value = true
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
    if (response.length) {
      isSave.value = true
      let res = response[0]
      input.value = res //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = res.emrpasienfk
      }
      for (let i = 0; i <= input.value.listDarah.length - 1; i++) {
        await nextTick();
        const fieldName = `TTD_Petugas_${i}`;
        H.tandaTangan().set(fieldName, res[fieldName]);
      }
    } else {
      await setAutoFill()
      H.alert('info', 'Pengambilan data berhasil!')
    }
  }).catch((e: any) => {
    console.log(e)
    H.alert('error', 'Terjadi kesalahan saat mengambil data')
  }).finally(() => {
    isLoading.value = false
  });
}

const addNewItem = (inputan: any) => {
  let newItem: any = {};
  let d = input.value;

  if (inputan == 'listDarah') {
    newItem = {
      namaPetugas: { value: user.id, label: user.namaLengkap }
    }
  }

  d[inputan].push(newItem);
}

const removeItem = (inputan: any) => {
  let index = input.value[inputan].length - 1;

  if (index == 0) {
    H.alert('warning', 'Data tidak boleh kurang dari 1!')
    return;
  } else {
    input.value[inputan].splice(index, 1)
  }
}

const simpan = async () => {
  if (checkTemplate.value == true) {
    H.alert('warning', 'Simpan template ya, bukan simpan data :)')
    return;
  }

  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  delete object.namatemplate

  for (let i = 0; i <= input.value.listDarah.length - 1; i++) {
    object[`TTD_Petugas_${i}`] = H.tandaTangan().get(`TTD_Petugas_${i}`);
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
    loadRiwayat();
  }).catch((e: any) => {
    console.log(e)
  }).finally(() => {
    isLoading.value = false
  });
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap,ttd&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => { d_Dokter.value = response })
}
const fetchRuangan = async (filter: any) => {
  await useApi().get(`emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
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

watch(isAlltemplate, (newValue) => {
  pilihTemplateFix()
})

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    console.log()
    return;
  }
  let ID = idTemplate.value ? idTemplate.value : ''
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

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    checkTemplate.value = false
    isAlltemplate.value = false
    input.value.namatemplate = null
    input.value.id = ''
  }).catch((e: any) => {
    isLoading.value = false
  })
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
  isAlltemplate.value = false
  input.value.namatemplate = dt.namatemplate;
  checkTemplate.value = true
}
const addTemplate = (response: any) => {
  input.value = response
  delete input.value['id']
  input.value.namatemplate = null
  showModalTemplateFix.value = false
  isAlltemplate.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}

const pilihTemplateFix = async (index: any) => {
  let allTemplate = isAlltemplate.value ? `&isAll=true` : ''
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}${allTemplate}`).then((responselast: any) => {
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

// ===== ARRAY =====
// const d_tidakAda: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ada' }
// ])
// const d_tidakYa: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ya' }
// ])
// const d_yaTidak: any = ref([
//     { value: 1, label: 'Ya' },
//     { value: 2, label: 'Tidak' }
// ])
// const d_tidakAda_ada: any = ref([
//     { value: 1, label: 'Tidak' },
//     { value: 2, label: 'Ya' }
// ])
</script>