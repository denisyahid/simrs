<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" @simpanTemplate="simpanTemplate"></ButtonEmr>
          </div>
        </div>
        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-3">
        <VButton type="button" class="mr-3" rounded outlined color="primary" raised icon="feather:folder"
          :loading="isLoading" @click="pilihTemplateFix(index)"> Pilih Template
        </VButton>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-12">
            <h1>Nama Template&emsp;&emsp;
              <span style="color:red">**Hanya diisi jika ingin membuat template</span>
            </h1>
            <VField>
              <VControl>
                <VInput v-model="input.namatemplate">
                </VInput>
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold">Nama Pasien</h1>
            <VControl>
              <VInput v-model="input.namaPasien" class="input" type="text" disabled />
            </VControl>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold">Tanggal Lahir Pasien</h1>
            <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" disabled />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold">Jenis Kelamin</h1>
            <div class="is-flex">
              <VField v-for="items in JenisKelamin" :key="items.value">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                    :label="items.label" color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold">No. Rekam Medis</h1>
            <VControl>
              <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
            </VControl>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold">Tanggal dan Jam</h1>
            <VDatePicker v-model="input.tglPembuatan" mode="dateTime" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal dan Jam" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold">Ruangan</h1>
            <VControl>
              <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Cari Ruangan" />
            </VControl>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold;">Regimen</h1>
            </div>
            <div class="is-10 column">
              <VField>
                <VControl>
                  <VInput class="input" placeholder="Regimen" v-model="input.regimen" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold;">Seri</h1>
            </div>
            <div class="is-10 column">
              <VField>
                <VControl>
                  <VInput class="input" placeholder="Seri" v-model="input.Seri" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold;">Hari Ke</h1>
            </div>
            <div class="is-10 column">
              <VField>
                <VControl>
                  <VInput class="input" placeholder="Hari Ke" v-model="input.hariKe" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold;">Riwayat Alergi / Hypersensitivitas :</h1>
            </div>
            <div class="is-10 column">
              <VField>
                <VControl>
                  <VInput class="input" placeholder="Riwayat Alergi / Hypersensitivitas"
                    v-model="input.riwayatAlergi" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="is-12 column">
            <div style="max-width: 100%">
              <table class="table-pri">
                <thead>
                  <tr style="">
                    <th class="th-pri" style="vertical-align: inherit; text-align: center">
                      KRITERIA
                    </th>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center">Obat 1</th>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center">Obat 2</th>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center">Obat 3</th>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center">Obat 4</th>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center">Obat 5</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="td-pri">Nama Obat</td>
                    <template v-for="(item, index) in 5" :key="index">
                      <td class="td-pri">
                        <VField>
                          <VTextarea rows="2" v-model="input[`obat-${index}`]"></VTextarea>
                        </VField>
                      </td>
                    </template>
                  </tr>
                  <tr v-for="(item, index) in pernyataanKriteria" :key="index">
                    <td class="td-pri">{{ item.label }}</td>
                    <template v-for="(detail, key) in item.detailObat" :key="key">
                      <td class="td-pri">
                        <template v-for="(item2, key2) in detail.checkBoxObat" :key="key2">
                          <VControl>
                            <VCheckbox v-model="input[`${detail.labelObat}`]" :label="item2.label"
                              :true-value="item2.value" color="primary" />
                          </VControl>
                        </template>
                      </td>
                    </template>
                  </tr>
                  <tr>
                    <td class="td-pri">Nama dan Tanda Tangan Perawat Kemoterapi</td>
                    <template v-for="(item, index) in 5" :key="index">
                      <td class="td-pri" style="text-align: center;">
                        <TandaTangan :elemenID="`TTDPerawat1-obat-${item}`" :width="'150'" :height="'150'"
                          class="dek" />
                        <VField class="mt-3">
                          <VControl>
                            <AutoComplete v-model="input[`NamaTTDPerawat1-obat-${item}`]" :suggestions="d_Pegawai"
                              :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Pilih Perawat 1" />
                          </VControl>
                        </VField>

                        <TandaTangan :elemenID="`TTDPerawat2-obat-${item}`" :width="'150'" :height="'150'"
                          class="dek" />
                        <VField class="mt-3">
                          <VControl>
                            <AutoComplete v-model="input[`NamaTTDPerawat2-obat-${item}`]" :suggestions="d_Pegawai"
                              :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Pilih Perawat 2" />
                          </VControl>
                        </VField>
                      </td>
                    </template>

                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </VCard>
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
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
                  </VControl>
                </VField>
              </div>
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
                <VIconButton type="button" raised circle icon="fas fa-pencil-alt" @click="editTemplate(slotProps.data)"
                  color="info" v-tooltip-prime.top="'Edit'" v-if="!isAlltemplate">
                </VIconButton>
                <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                  color="success" v-tooltip-prime.top="'Pilih'">
                </VIconButton>
              </VButtons>
            </template>
          </Column>
          <Column field="namatemplate" header="Nama" :sortable="true"></Column>
          <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
            <template #body="slotProps">
              {{ slotProps.data.registrasi.namaruangan }}
            </template>
          </Column>
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
import { h, reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import MultiSelect from 'primevue/multiselect'
import * as EMR from '../page-emr-plugins/asuhan-keperawatan-dan-observasi-pasien-hemodialisa'
import * as EMR2 from '../page-emr-plugins/timeout-pemberian-obat-kemoterapi'
import Fieldset from 'primevue/fieldset'
import Calendar from 'primevue/calendar'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let JenisKelamin: any = ref(EMR.JenisKelamin())
let JenisPasien: any = ref(EMR.JenisPasien())
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
let StatusPernikahan: any = ref(EMR.StatusPernikahan())
let pernyataanKriteria: any = ref(EMR2.pernyataanKriteria())
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
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const d_Obat: any = ref([])
const d_dokter: any = ref([])
const d_Dokter: any = ref([])
const d_Perawat: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const isAlltemplate: any = ref(false)
const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const dataTTD: any = ref([])
const idTemplate: any = ref('');
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('FormulirTimeoutPemberianObatKemoterapi')
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date(),
  tanggalDanJam: new Date()
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      if (response.length > 0) {
        isDisabled.value = false
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        for (let i = 1; i <= 5; i++) {
          const ttdPerawat1 = `TTDPerawat1-obat-${i}`
          const ttdPerawat2 = `TTDPerawat2-obat-${i}`
          H.tandaTangan().set(ttdPerawat1, dataTTD.value[ttdPerawat1])
          H.tandaTangan().set(ttdPerawat2, dataTTD.value[ttdPerawat2])
        }
      } else {
        isDisabled.value = true
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  for (let i = 1; i <= 5; i++) {
    const ttdPerawat1 = `TTDPerawat1-obat-${i}`
    const ttdPerawat2 = `TTDPerawat2-obat-${i}`

    object[ttdPerawat1] = H.tandaTangan().get(ttdPerawat1)
    object[ttdPerawat2] = H.tandaTangan().get(ttdPerawat2)

    console.log(object[ttdPerawat1], object[ttdPerawat2])
  }
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
  input.value.namaruangan = props.registrasi.namaruangan
}

const fetchPerawat = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Perawat.value = response
    })
}

const fetchObat = async (filter: any) => {
  const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  response.map((element: any) => {
    element.label = element.productname,
      element.value = element.id
  })
  d_Obat.value = response
}


const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}

const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
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

const d_Pegawai: any = ref([])

const fetchPegawai = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const d_Ruangan: any = ref([])

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  );
  d_Ruangan.value = response;
};

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    console.log("get props", props)
    H.alert('error', 'Nama Template harus diisi untuk menyimpan.');
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

  useApi().post(
    `/emr/simpan-emr-template`, json).then((response: any) => {
      isLoading.value = false
      input.value.namatemplate = null
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      console.log(responselast)
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          // responselast[x].id = ''
        }
        listTemplateFix.value = responselast //set ke inputan
        showModalTemplateFix.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}


const editTemplate = async (dt: any) => {
  if (!dt) return;
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  console.log("DT", dt)
  input.value = dt //set ke inputan
  isAlltemplate.value = false;
  idTemplate.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
}

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(
    `/emr/hapus-template`, json).then((response: any) => {
      if (response.status !== 500) {
        isLoading.value = false
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
}

const addTemplate = (response) => {
  input.value = response;
  delete input.value['_id'];
  input.value.namatemplate = null;
  showModalTemplateFix.value = false;
  H.alert('success', 'Berhasil ditambahkan');
};

watch(isAlltemplate, (newValue) => {
  pilihTemplateFix()
})

setView()
loadRiwayat()
setAutoFill()
fetchPerawat({ query: '' })
fetchObat({ query: '' })
</script>
