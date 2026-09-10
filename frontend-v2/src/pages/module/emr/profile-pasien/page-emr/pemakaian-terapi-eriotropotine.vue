<style lang="scss">
.table {
  border-collapse: collapse;
  width: 100% !important;
}

// .columns.is-multiline {
//   flex-wrap: nowrap;
// }

// .column.pl-0 {
//   overflow-x: auto;
//   min-width: 100%;
// }

.text-merah {
    color: red;
}

.text-coklat {
    color: brown;
}

.table td {
  height: 4rem !important;
  text-align: center !important;
  border: 1px solid black !important;
  // border-left: none !important;
}

label {
  color: black !important;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150% !important;
}

.tg2 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 120% !important;
}

.tg2 td {
  height: 4rem !important;
  border: 1px solid black !important;
  vertical-align: middle !important;
  padding: 5px !important;
}

.tg2 th {
  height: 4rem !important;
  border: 1px solid black !important;
  vertical-align: middle !important;
  padding: 5px !important;
  text-align: center !important;
}

.tg3 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100% !important;
}

.tg3 td {
  height: 4rem !important;
  border: 1px solid black !important;
  vertical-align: middle !important;
  padding: 5px !important;
}

.tg3 th {
  text-align: center !important;
  height: 4rem !important;
  border: 1px solid black !important;
  vertical-align: middle !important;
  padding: 5px !important;
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
  vertical-align: middle
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 0px;
}

.alert-nobg {
  background: none !important;
  height: 5em;
  width: 5em;
}

.cb-1 .checkbox {
  color: red !important;
}

.cb-2 .checkbox {
  color: brown !important;
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
import moment from 'moment'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import { v4 as uuidv4 } from 'uuid';
import Checkbox from 'primevue/checkbox';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({
  title: 'Pemakaian Terapi Eritropoietine - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const d_Dokter: any = ref([])
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
const confirm = useConfirm();
const route = useRoute()
const pasien: any = ref({})
const loadData: any = ref(true)
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
  disability: []

})
const COLLECTION: any = ref('PemakaianTerapiEriotropotine') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{
    no: 1,
    id: uuidv4(),
    // terapi: Array(31).fill().map(() => ({ terapi: null }))
    terapi: Array(31).fill(''),
    terapi2: Array(31).fill('')
  }]
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
// const loadRiwayat = async () => {
//   let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
//   if (response.length) {
//     input.value = response[0] //set ke inputan
//     if (NOREC_EMRPASIEN.value == '') {
//       NOREC_EMRPASIEN.value = response[0].emrpasienfk
//     }
//   } else {
//     isLoading.value = true
//     const responseTglRuangan = await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
//     const responseHistori = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
//     isLoading.value = false
//     if (responseTglRuangan.length && responseHistori.length) {
//       console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan)
//       var tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
//       var convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
//       var tgl_Sekarang = moment();
//       isLoading.value = false
//       const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
//       if (responseTglRuangan[0].registrasi.namaruangan.trim() === props.registrasi.namaruangan.trim() && calculateDays < 90) {
//         confirm.require({
//           message: 'Peresepan Hemodialisis sudah pernah diinput ' + calculateDays + ' hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya ?',
//           group: 'templating',
//           header: 'Peresepan Hemodialisis',
//           icon: 'pi pi-exclamation-circle',
//           accept: () => {
//             if (responseHistori.length) {
//               input.value = responseHistori[0] //set ke inputan
//               input.value.namatemplate = ''
//               // console.log(input.value)
//               isLoading.value = false // Set isLoading to false after loading history data
//             } else {
//               H.alert('warning', 'Data tidak ada')
//               isLoading.value = false
//             }
//           },
//           reject: () => {
//             isLoading.value = false // Ensure isLoading is set to false if rejected
//           }
//         })
//       }
//     } else {
//       console.log('Data EMR sebelumnya tidak ada!')
//       H.alert('warning', 'Data EMR sebelumnya tidak ada!');
//       isLoading.value = false // Set isLoading to false when no previous data is found
//     }
//     console.log("Ruangan pasien sekarang : " + props.registrasi.namaruangan)
//     H.alert('info', 'Ruangan Pasien saat ini: ' + props.registrasi.namaruangan);
//   }
// }
const loadRiwayat = async () => {
  try {
    const response: any = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`);
    if (response.length) {
      input.value = response[0];

      if (input.value.details && Array.isArray(input.value.details)) {
        input.value.details.forEach((detail, detailIndex) => {
          if (detail.terapi) {
            // Check if 'terapi2' is not already present
            if (!detail.terapi2) {
              // Ensure 'terapi' is an array of strings
              detail.terapi = detail.terapi.map(item => item);

              // Initialize 'terapi2' with empty strings
              detail.terapi2 = Array(detail.terapi.length).fill('');

              // Loop through 'terapi' array to populate 'terapi2'
              detail.terapi.forEach((item, index) => {
                if (item === 'Zat Besi') {
                  detail.terapi2[index] = 'Zat Besi';
                }
              });
            } else {
              console.log(`terapi2 already present in details[${detailIndex}].`);
            }
          } else {
            console.log(`No 'terapi' found in details[${detailIndex}].`);
          }
        });

        console.log(input.value);
      } else {
        console.log("No 'details' array found.");
      }
      


      if (NOREC_EMRPASIEN.value === '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
      // dataTTD.value = response[0];
      // for (let i = 0; i < input.value.details.length; i++) {
      //   await nextTick();//test bawa di sini
      //   const fieldName = `parafPasien_${i}`;
      //   H.tandaTangan().set(fieldName, dataTTD.value[fieldName]);
      // }
    } else {
      setAutoFill();
    }
  } catch (error) {
    console.error('Error loading data:', error);
  }
};
const simpan = () => {
  if (!input.value.details.length) {
    H.alert('error', 'Data tidak boleh kosong');
    return;
  }

  let ID = input.value.id ? input.value.id : ''
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
    'name_form': 'Pemakaian Terapi Eriotropotine',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  // console.log(json)
  // return
  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      // NOREC_EMRPASIEN.value = response.norec_emr
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Dokter.value = response
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

// Loopingan
const TableArray = ([
  {
    label: 'Tanggal',
    detail: [
      { type: 'tgl' },
      { type: 'tgl' },
      { type: 'tgl' },
      { type: 'tgl' }
    ]
  },
  {
    label: 'BB Kering (Kg)',
    detail: [
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' }
    ]
  },
  {
    label: 'Frekuensi HD Per Minggu',
    detail: [
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' }
    ]
  },
  {
    label: 'Lama HD (Jam)',
    detail: [
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' }
    ]
  },
  {
    label: 'Luas Membran Dialiser (m2)',
    detail: [
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' }
    ]
  },
  {
    label: 'Flow Dialisat (ML/mnt)',
    detail: [
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' }
    ]
  },
  {
    label: 'Jenis Akses Vaskular',
    detail: [
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' }
    ]
  },
  {
    label: 'Ukuran Jarum Fistula',
    detail: [
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' }
    ]
  },
  {
    label: 'Heparin Awal (Unit)',
    detail: [
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' }
    ]
  },
  {
    label: 'Heparin Pemeliharaan (Unit)',
    detail: [
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' },
      { type: 'tb' }
    ]
  },
  {
    label: 'Nama dan TTD Dokter',
    detail: [
      { type: 'dd' },
      { type: 'dd' },
      { type: 'dd' },
      { type: 'dd' }
    ]
  }
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

const removeItem = async () => {
  if (input.value.details.length > 0) {
    input.value.details.splice(input.value.details.length - 1, 1);
  }
};

const addNewItem = async () => {
  let newItem = {
    // no: input.value.details.length ? input.value.details[0].no + 1 : 1,
    no: input.value.details.length ? input.value.details[input.value.details.length - 1].no + 1 : 1,
    id: uuidv4(),
    terapi: Array(31).fill(null),
    terapi2: Array(31).fill(null)
  };

  input.value.details.push(newItem);
};

// getDataExist()
fetchPasien()
</script>

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
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Pemakaian Terapi Eritropoietine</h3>
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
        <!-- form baru -->

        <div class="column" style="overflow-x: auto;">
          <div class="column columns is-flex" style="overflow-x: auto; width: 100%;">
            <div class="column is-8 columns is-multiline"
              style="font-weight: bold;font-size: large;align-items: center;">

            </div>
            <div class="column is-4" style="text-align: center;">
              <VButton type="button" rounded color="dark" class="mb-3"> Tambah Kolom
              </VButton>
              <VButtons style="justify-content:space-around">
                <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                  v-tooltip.bubble="'Tambah '">
                </VIconButton>
                <VIconButton class="mt-1" v-if="input.details.length > 1" type="button" raised circle
                  icon="feather:trash" @click="removeItem()" color="danger">
                </VIconButton>
              </VButtons>
            </div>
          </div>

          <div class="column is-12 is-flex" style="overflow-x: auto;">
            <div class="column is-5 pr-0 pl-0" style="overflow-x: auto;" v-for="(data, index) in input.details"
              :key="index">
              <table class="tg2" style="width: 100% !important;">
                <thead>
                  <!-- <th class="th-pri">No</th> -->
                  <th colspan="3" rowspan="3" class=" th-pri">
                    <VField>
                      <VControl>
                        <VInput v-model="data.bulan" placeholder="Bulan Input" />
                      </VControl>
                    </VField>
                  </th>
                </thead>
                <thead>
                  <!-- <th class="th-pri">No</th> -->
                  <th colspan="3" rowspan="3" class=" th-pri">
                    <VField>
                      <VControl>
                        <VDatePicker v-model="data.tanggal" mode="date" trim-weeks :max-date="new Date()"
                          style="width: 100%">
                          <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" readonly />
                            </VControl>
                          </template>
                        </VDatePicker>
                      </VControl>
                    </VField>
                  </th>
                </thead>
                <thead>
                  <!-- <th class="th-pri">No</th> -->
                  <th colspan="3" rowspan="3" class=" th-pri">
                    <VField>
                      <VControl>
                        <VInput v-model="data.hb" placeholder="HB" />
                      </VControl>
                    </VField>
                  </th>
                </thead>
                <thead>
                  <!-- <th class="th-pri">No</th> -->
                  <th colspan="3" rowspan="3" class=" th-pri">
                    <VField>
                      <VControl>
                        <VInput v-model="data.jenisTerapi" placeholder="Jenis Terapi" />
                      </VControl>
                    </VField>
                  </th>
                </thead>
                <tbody>
                  <tr>
                    <th class="th-pri" style="text-center" width="20%">Tanggal</th>
                    <th rowspan="3" class="th-pri text-merah" width="40%">EPO</th>
                    <th rowspan="2" class="th-pri text-coklat" width="50%">ZAT BESI</th>
                  </tr>
                </tbody>
                <tbody>
                  <tr v-for="(item, itemIndex) in 31" :key="itemIndex">
                    <td class="td-pri text-center">{{ itemIndex + 1 }}</td>
                    <td class="td-pri cb-1">
                      <VCheckbox v-model="data.terapi[`${itemIndex}`]" label="EPO" true-value="ERI"
                        false-value="null"/>
                    </td>
                    <td class="td-pri cb-2">
                      <VCheckbox v-model="data.terapi2[`${itemIndex}`]" label="Zat Besi" true-value="Zat Besi"
                        false-value="null"/>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>
