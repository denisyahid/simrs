<style lang="scss">
h1 {
  font-weight: bold;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 270%;
}

.tg td {
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  text-align: center !important;
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: bold;
  overflow: hidden;
  background-color: aquamarine;
  vertical-align: middle;
  padding: 10px 5px;
  word-break: normal;
}

.field.has-addons .control:not(:last-child) {
  flex: 1 1 10%;
}
@media screen and (max-width: 2000px) and (min-width: 1462px) {
  .tg {
    width: 170%;
  }
}
</style>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import { v4 as uuidv4 } from 'uuid';
import moment from 'moment'
import DataTable from 'primevue/datatable';
// import Checkbox from 'primevue/checkbox';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({
  title: 'Resume Tindakan Hemodialisis - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const d_Petugas: any = ref([])
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
const dataFormTindakanResume: any = ref([]);
const resumeTindakan: any = ref(false)
const selectedItem: any = ref(null);
const route = useRoute()
const pasien: any = ref({})
const loadData: any = ref(true)
const d_mrs: any = ref([{ value: 1, label: 'Rawat Jalan' }, { value: 2, label: 'Rawat Inap' }])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date,
    jam: new Date
  },
  airway: [],
  disability: [],
  resumeTindakan: [],
  filterTgl: reactive({
      start: new Date(),
      end: new Date(),
  }),

})
const COLLECTION: any = ref('ResumeTindakanHemodialisis') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  resumeTindakan: [{
    no: 1,
    id: uuidv4(),
  }],
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    dataFormTindakanResume.value = response[0].resumeTindakan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }
}
const addResumeTindakan = (e: any) => {
  if (!Array.isArray(dataFormTindakanResume.value)) {
    dataFormTindakanResume.value = [];
  }
  // console.log('masuk',e)
  if (e.no) {
    // console.log('edit uy', e)
    dataFormTindakanResume.value.forEach((element: any) => {
      if (e.no == element.no) {
        element.no = e.no
        element.tanggal = e.tanggal
        element.bbKering = e.bbKering
        element.bbPre = e.bbPre
        element.bbPost = e.bbPost
        element.tekananDarahPre = e.tekananDarahPre
        element.tekananDarahPost = e.tekananDarahPost
        element.nadi = e.nadi
        element.lama = e.lama
        element.heparinisasi = e.heparinisasi
        element.rataQBB = e.rataQBB
        element.ufGoal = e.ufGoal
        element.ufRemoved = e.ufRemoved
        element.transfusi = e.transfusi
        element.obatIntraHD = e.obatIntraHD
        element.mrs = e.mrs
        element.penyulit = e.penyulit
        element.petugas = e.petugas
      }
    });
  } else {
    dataFormTindakanResume.value.push({
      no: dataFormTindakanResume.value.length + 1,
      tanggal: e.tanggal,
      bbKering: e.bbKering,
      bbPre: e.bbPre,
      bbPost: e.bbPost,
      tekananDarahPre: e.tekananDarahPre,
      tekananDarahPost: e.tekananDarahPost,
      nadi: e.nadi,
      lama: e.lama,
      heparinisasi: e.heparinisasi,
      rataQBB: e.rataQBB,
      ufGoal: e.ufGoal,
      ufRemoved: e.ufRemoved,
      transfusi: e.transfusi,
      obatIntraHD: e.obatIntraHD,
      mrs: e.mrs,
      penyulit: e.penyulit,
      petugas: e.petugas
    })
  }
  resumeTindakan.value = false
  clear()
}


// const editIntake = (e: any) => {
//   // console.log('edit', e);
//   showresumeTindakan(e);
// };
const editIntake = (e: any) => {
  const eCopy = JSON.parse(JSON.stringify(e, (key, value) => {
    if (typeof value === 'object' && value !== null) {
      return Object.assign({}, value);
    }
    return value;
  }));
  showresumeTindakan(eCopy);
};

const deleteIntake = (e: any) => {
  console.log(e)
  dataFormTindakanResume.value.splice(e, 1)
}
const showresumeTindakan = (e: any) => {
  // console.log(e.petugas.label)
  item.no = e.no ? e.no : ''
  item.tanggal = e.tanggal ? e.tanggal : '',
  item.bbKering = e.bbKering ? e.bbKering : '',
  item.bbPre = e.bbPre ? e.bbPre : '',
  item.bbPost = e.bbPost ? e.bbPost : '',
  item.tekananDarahPre = e.tekananDarahPre ? e.tekananDarahPre : '',
  item.tekananDarahPost = e.tekananDarahPost ? e.tekananDarahPost : '',
  item.nadi = e.nadi ? e.nadi : '',
  item.lama = e.lama ? e.lama : '',
  item.heparinisasi = e.heparinisasi ? e.heparinisasi : '',
  item.rataQBB = e.rataQBB ? e.rataQBB : '',
  item.ufGoal = e.ufGoal ? e.ufGoal : '',
  item.ufRemoved = e.ufRemoved ? e.ufRemoved : '',
  item.transfusi = e.transfusi ? e.transfusi : '',
  item.obatIntraHD = e.obatIntraHD ? e.obatIntraHD : '',
  item.mrs = e.mrs ? e.mrs : '',
  item.penyulit = e.penyulit ? e.penyulit : '',
  item.petugas = e.petugas ? e.petugas : ''
  resumeTindakan.value = true
}
const clear = () => {
  delete item.no
  delete item.tanggal
  delete item.bbKering
  delete item.bbPre
  delete item.bbPost
  delete item.tekananDarahPre
  delete item.tekananDarahPost
  delete item.nadi
  delete item.lama
  delete item.heparinisasi
  delete item.rataQBB
  delete item.ufGoal
  delete item.ufRemoved
  delete item.transfusi
  delete item.obatIntraHD
  delete item.mrs
  delete item.penyulit
  delete item.petugas
}
const formatDateIndoSimple = (date: any) => {
  if (date) {
    return moment(date).format('DD-MM-YYYY')
  }
}
const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  object.nocm = pasien.value.nocm
  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
  object.resumeTindakan = dataFormTindakanResume.value
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  console.log(json)

  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      // NOREC_EMRPASIEN.value = response.norec_emr
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}
const kembaliKeun = () => {
  window.history.back()
}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
  console.log(norec_emr)
}

const dataSourceFiltered = computed(() => {
  let tglAwal = H.formatDate(item.filterTgl?.start ?? new Date(), 'YYYY-MM-DD')
  let tglAkhir = H.formatDate(item.filterTgl?.end ?? new Date(), 'YYYY-MM-DD')
  if (!item.filterTgl?.start && !item.filterTgl?.end) {
    return dataFormTindakanResume.value.map((item: any, index: number) => ({
      ...item,
      originalIndex: index,
    }));
  }

  return dataFormTindakanResume.value
    .map((item: any, index: number) => ({
      ...item,
      originalIndex: index,
    }))
    .filter((items: any) => {
      let tgl = H.formatDate(items.tanggal, 'YYYY-MM-DD')
      return tgl >= tglAwal && tgl <= tglAkhir
    });
});

// Loopingan
const TableArray = ([
  {}, {}, {}, {}, {},
  {}, {}, {}, {}, {},
  {}, {}, {}, {}, {},
])

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
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
const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('error', 'Nama Template harus diisi untuk menyimpan.');
    return;
  }
  let ID = input.id ? input.id : ''
  let object: any = {}
  object = input.value
  object.nocm = pasien.value.nocm
  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
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

// getDataExist()

fetchPasien()
</script>

<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Resume Tindakan Hemodialisis</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

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

        <div class="columns is-multiline px-3 mt-3">
          <div class="column is-6">
            <VButton color="primary" class="mr-3 mt-3" raised icon="fas fa-plus" @click="showresumeTindakan">Tambah Tindakan
            </VButton>
          </div>
          <div class="column is-6">
            <VField>
              <VLabel>Periode</VLabel>
              <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                      <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" v-on="inputEvents.start" />
                          </VControl>
                          <VControl subcontrol icon="feather:calendar">
                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                          </VControl>
                      </VField>
                  </template>
              </VDatePicker>
            </VField>
          </div>
        </div>

        <VModal is="form" :open="resumeTindakan" title="Resume Tindakan" size="small" actions="right"
          @close="resumeTindakan = false">
          <template #content>
            <div class="modal-form" style="height: 600px;">
              <div class="field">
                <label>Tanggal</label>
                <div class="control">
                  <VDatePicker class="pt-3" v-model="item.tanggal" color="green" trim-weeks mode="date"
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
                </div>
              </div>
              <div class="field">
                <label>BB Kering</label>
                <div class="control">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="BB Kering" v-model="item.bbKering" />
                    </VControl>
                    <VControl class="">
                      <VButton static>Kg</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>BB PRE</label>
                <div class="control">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.bbPre" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>BB Post</label>
                <div class="control">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.bbPost" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>Tekanan Darah PRE</label>
                <div class="control">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.tekananDarahPre" />
                    </VControl>
                    <VControl class="">
                      <VButton static>mmHg</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>Tekanan Darah POST</label>
                <div class="control">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.tekananDarahPost" />
                    </VControl>
                    <VControl>
                      <VButton static>mmHg</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>Nadi</label>
                <div class="control">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.nadi" />
                    </VControl>
                    <VControl class="">
                      <VButton static>x/menit</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>Lama (Jam)</label>
                <div class="control">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.lama" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>Heparinisasi</label>
                <div class="control">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.heparinisasi" />
                    </VControl>
                    <VControl class="">
                      <VButton static>International Unit/cc</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>Rata-rata QB</label>
                <div class="control">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.rataQBB" />
                    </VControl>
                    <VControl class="">
                      <VButton static>ml/menit</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>UF Goal (lt)</label>
                <div class="control">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.ufGoal" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>UF Removed (lt)</label>
                <div class="control">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.ufRemoved" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>Transfusi</label>
                <div class="control">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.transfusi" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>Obat-obat Intra HD</label>
                <div class="control">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.obatIntraHD" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>MRS/Rawat Jalan</label>
                <div class="control">
                  <VField>
                    <VControl>
                      <VSelect v-model="item.mrs">
                        <VOption value="MRS">
                          MRS
                        </VOption>
                        <VOption value="Rawat Jalan">
                          Rawat Jalan
                        </VOption>
                      </VSelect>
                      <!-- <VInput type="text" class="input" v-model="item.mrs" /> -->
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>Penyulit On HD Tindakan</label>
                <div class="control">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.penyulit" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="field">
                <label>Petugas</label>
                <div class="control">
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="item.petugas" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </template>
          <template #action>
            <VButton type="submit" color="primary" raised @click="addResumeTindakan(item)">Tambah</VButton>
          </template>
        </VModal>

        <div class="column is-12" style="overflow: auto;">
          <table class="tg">
            <thead>
              <tr>
                <th rowspan="2">Aksi</th>
                <th rowspan="2">No</th>
                <th rowspan="2">TGL</th>
                <th rowspan="2">BB Kering</th>
                <th colspan="2">BB (Kg)</th>
                <th colspan="2">Tekanan Darah</th>
                <th rowspan="2">Nadi</th>
                <th rowspan="2">Lama (Jam)</th>
                <th rowspan="2">Heparinisasi</th>
                <th rowspan="2">Rata-rata QB</th>
                <th rowspan="2">UF Goal (lt)</th>
                <th rowspan="2">UF Removed (lt)</th>
                <th rowspan="2">Transfusi</th>
                <th rowspan="2">Obat-obat Intra HD</th>
                <th rowspan="2">MRS/Rawat Jalan</th>
                <th rowspan="2">Penyulit On HD Tindakan</th>
                <th rowspan="2">Petugas</th>
              </tr>
              <tr>
                <th>pre</th>
                <th>post</th>
                <th>pre</th>
                <th>post</th>
              </tr>
            </thead>
            <tbody style="text-align: center;">
              <tr v-for="(item, index) in dataSourceFiltered" :key="index">
                <td>
                  <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                    v-tooltip.top="'Edit'" @click="editIntake(item)">
                  </VIconButton>
                  <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                    v-tooltip.top="'Hapus'" @click="deleteIntake(index)">
                  </VIconButton>
                </td>
                <td>
                  <span>{{ item.no }}</span>
                </td>
                <td>
                  <span>{{ formatDateIndoSimple(item.tanggal) }}</span>
                </td>
                <td>
                  <span>{{ item.bbKering }}</span>
                </td>
                <td>
                  <span>{{ item.bbPre }}</span>
                </td>
                <td>
                  <span>{{ item.bbPost }}</span>
                </td>
                <td>
                  <span>{{ item.tekananDarahPre }}</span>
                </td>
                <td>
                  <span>{{ item.tekananDarahPost }}</span>
                </td>
                <td>
                  <span>{{ item.nadi }}</span>
                </td>
                <td>
                  <span>{{ item.lama }}</span>
                </td>
                <td>
                  <span>{{ item.heparinisasi }}</span>
                </td>
                <td>
                  <span>{{ item.rataQBB }}</span>
                </td>
                <td>
                  <span>{{ item.ufGoal }}</span>
                </td>
                <td>
                  <span>{{ item.ufRemoved }}</span>
                </td>
                <td>
                  <span>{{ item.transfusi }}</span>
                </td>
                <td>
                  <span>{{ item.obatIntraHD }}</span>
                </td>
                <td>
                  <span>{{ item.mrs }}</span>
                </td>
                <td>
                  <span>{{ item.penyulit }}</span>
                </td>
                <td>
                  <span>{{ item.petugas.label }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- <hr>
        <div class="column is-12">
          <h1 class="font-bold">Riwayat Sebelumnya</h1>
        </div>

        <div class="column is-12" style="overflow: auto;">
          <table class="tg">
            <thead>
              <tr>
                <th rowspan="2">TGL</th>
                <th rowspan="2">BB Kering</th>
                <th colspan="2">BB (Kg)</th>
                <th colspan="2">Tekanan Darah</th>
                <th rowspan="2">Nadi</th>
                <th rowspan="2">Lama (Jam)</th>
                <th rowspan="2">Heparinisasi</th>
                <th rowspan="2">Rata-rata QB</th>
                <th rowspan="2">UF Goal (lt)</th>
                <th rowspan="2">UF Removed (lt)</th>
                <th rowspan="2">Transfusi</th>
                <th rowspan="2">Obat-obat Intra HD</th>
                <th rowspan="2">MRS/Rawat Jalan</th>
                <th rowspan="2">Penyulit On HD Tindakan</th>
                <th rowspan="2">Petugas</th>
              </tr>
              <tr>
                <th>pre</th>
                <th>post</th>
                <th>pre</th>
                <th>post</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in TableArray" :key="index">
                <td>
                  <VDatePicker v-model="input['Dtgl_' + index]" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBBBKering_' + index]" />
                  </VControl>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBBBpre_' + index]" />
                  </VControl>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBBBpost_' + index]" />
                  </VControl>
                </td>
                <td>
                  <VField addons style="padding: 5px;padding-top:0px">
                    <VControl>
                      <VInput type="text" class="input" v-model="input['TBTDpre_' + index]" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mmHg</VButton>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField addons style="padding: 5px;padding-top:0px">
                    <VControl>
                      <VInput type="text" class="input" v-model="input['TBTDpost_' + index]" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mmHg</VButton>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBNadi_' + index]" />
                  </VControl>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBLamaJam_' + index]" />
                  </VControl>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBHeparinisasi_' + index]" />
                  </VControl>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBRataRataQB_' + index]" />
                  </VControl>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBUFGoal_' + index]" />
                  </VControl>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBUFRemoved_' + index]" />
                  </VControl>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBTransfusi_' + index]" />
                  </VControl>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBObatObatIntraHD_' + index]" />
                  </VControl>
                </td>
                <td height="150px;" width="200px;">
                  <VControl class="prime-auto">
                    <Multiselect v-model="input['TBMRSRJ_' + index]" :attrs="{ value }" placeholder="" label="label"
                      :options="d_mrs" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </td>
                <td>
                  <VControl>
                    <VInput type="text" class="input" v-model="input['TBPenyulitOnHDTindakan_' + index]" />
                  </VControl>
                </td>
                <td>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input['DDPetugas_' + index]" :suggestions="d_Petugas"
                      @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                  </VControl>
                </td>
              </tr>
            </tbody>
          </table>
        </div> -->

        <!-- form baru -->

      </div>
    </div>
  </div>
</template>
