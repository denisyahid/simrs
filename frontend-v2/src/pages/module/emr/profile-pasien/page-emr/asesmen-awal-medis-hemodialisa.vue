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
import Checkbox from 'primevue/checkbox';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import Gambarin from '../page-emr-plugins/img-draw.vue'
import ConfirmDialog from 'primevue/confirmdialog'
import moment from 'moment'
import { useConfirm } from "primevue/useconfirm"
import { FilterMatchMode } from 'primevue/api';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
// import * as EMR from '../page-emr-plugins/monitoring&evaluasi-resusitasi'

// Loopingan
let ListBEBAS = ref([

])


// Judul
useHead({
  title: 'Asesmen Awal Medis Hemodialisa - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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
const newDate = new Date();
const input: any = ref({
  HjamKedatangan: newDate,
  HjamAW: newDate,
  DTttd: newDate,
  details: [{
    no: 1,
  }],
})
const dataTTD: any = ref([])
const isAlltemplate: any = ref(false)
const idTemplate: any = ref('');
const route = useRoute()
const d_Dokter: any = ref([])
const d_Diagnosa = ref([])
const pasien: any = ref({})
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
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
  filter: '',
  airway: [],
  disability: []
})
const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const COLLECTION: any = ref('AsesmenAwalMedisHemodialisa') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const userLogin = useUserSession().getUser()
const confirm = useConfirm();
const isLoading = ref(false)
const isResumeMedis: any = ref(false);
const isAktive = ref()
const kelompokUser = route.query.kelompokuser ?? userLogin.kelompokUser.kelompokUser
// const loadRiwayat = async () => {
//   // if (NOREC_EMRPASIEN.value == '') return
//   await useApi().get(
//     `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
//       if (response.length) {
//         input.value = response[0] //set ke inputan
//         if (NOREC_EMRPASIEN.value == '') {
//           NOREC_EMRPASIEN.value = response[0].emrpasienfk
//         }
//         dataTTD.value = response[0]
//       } else {
//         // isLoading.value = true
//         // const responseTglRuangan = await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
//         // const responseHistori = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
//         // isLoading.value = false
//         // if (responseTglRuangan.length && responseHistori.length) {
//         //   console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan)
//         //   var tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
//         //   var convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
//         //   var tgl_Sekarang = moment();
//         //   isLoading.value = false
//         //   const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
//         //   if (responseTglRuangan[0].registrasi.namaruangan.trim() == H.setObjectRegistrasi(pasien.value.registrasi).namaruangan.trim() && calculateDays < 90) {
//         //     confirm.require({
//         //       message: 'Asesmen Awal Medis Hemodialisa sudah pernah diinput ' + calculateDays + ' hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya ?',
//         //       group: 'templating',
//         //       header: 'Asesmen Awal Medis Hemodialisa',
//         //       icon: 'pi pi-exclamation-circle',
//         //       accept: () => {
//         //         if (responseHistori.length) {
//         //           input.value = responseHistori[0] //set ke inputan
//         //           input.value.namatemplate = ''
//         //           // console.log(input.value)
//         //           isLoading.value = false // Set isLoading to false after loading history data
//         //         } else {
//         //           H.alert('warning', 'Data tidak ada')
//         //           isLoading.value = false
//         //         }
//         //       },
//         //       reject: () => {
//         //         isLoading.value = false // Ensure isLoading is set to false if rejected
//         //       }
//         //     })
//         //   }
//         // } else {
//         //   console.log('Data EMR sebelumnya tidak ada!')
//         //   isLoading.value = false // Set isLoading to false when no previous data is found
//         // }
//         // console.log("Ruangan pasien sekarang : " + props.registrasi.namaruangan)
//       }
//     })
//   H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
//   await loadGambar("GambarParu", dataTTD.value.GambarParu)
//   await loadGambar("GambarPerut", dataTTD.value.GambarPerut)
// }

const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  try {
    const response = await useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    );

    if (response.length) {
      input.value = response[0]; // Set ke inputan
      if (NOREC_EMRPASIEN.value === '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
      dataTTD.value = response[0];
    } else {
      isLoading.value = true;

      const responseTglRuangan = await useApi().get(
        `/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`
      );
      const responseHistori = await useApi().get(
        `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`
      );

      isLoading.value = false;

      if (responseTglRuangan.length && responseHistori.length) {
        console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan);

        const tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
        const convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
        const tgl_Sekarang = moment();
        const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');

        H.alert('info', 'Asesmen Awal Medis Hemodialisa sudah pernah diinput ' + calculateDays + ' hari dari tanggal registrasi pasien ini.');

        if (
          responseTglRuangan[0].registrasi.namaruangan.trim() === props.registrasi.namaruangan.trim() &&
          // responseTglRuangan[0].registrasi.namaruangan.trim() === H.setObjectRegistrasi(pasien.value.registrasi).namaruangan.trim() &&
          calculateDays < 90
        ) {
          confirm.require({
            message: `Asesmen Awal Medis Hemodialisa sudah pernah diinput ${calculateDays} hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya?`,
            group: 'templating',
            header: 'Asesmen Awal Medis Hemodialisa',
            icon: 'pi pi-exclamation-circle',
            accept: () => {
              if (responseHistori.length) {
                input.value = responseHistori[0];
                input.value.namatemplate = '';
                isLoading.value = false;
              } else {
                H.alert('warning', 'Data tidak ada');
                isLoading.value = false;
              }
            },
            reject: () => {
              isLoading.value = false;
            }
          });
        }
      } else {
        H.alert('warning', 'Data EMR sebelumnya tidak ada!');
        isLoading.value = false;
      }

      // console.log("Ruangan pasien sekarang : " + props.registrasi.namaruangan);
      H.alert('info', 'Ruangan Pasien saat ini: ' + props.registrasi.namaruangan);
    }

    H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter);
    await loadGambar("GambarParu", dataTTD.value.GambarParu);
    await loadGambar("GambarPerut", dataTTD.value.GambarPerut);
  } catch (error) {
    console.error("Error loading data:", error);
    isLoading.value = false;
  }
};



// const simpan = () => {
//   let ID = input.value.id ? input.value.id : ''
//   let object: any = {}
//   object = input.value
//   object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
//   object['GambarParu'] = H.tandaTangan().get("GambarParu");
//   // object['GambarPerut'] = H.tandaTangan().get("GambarPerut");
//   object.pasien = H.setObjectPasien(props.pasien)
//   object.registrasi = H.setObjectRegistrasi(props.registrasi)
//   let json = {
//     'id': ID,
//     'norec_emr': NOREC_EMRPASIEN.value,
//     'collection': COLLECTION.value,
//     'url_form': props.FORM_URL,
//     'name_form': props.FORM_NAME,
//     'jenis_emr': 'asesmen_medis',
//     'data': object
//   }
//   isLoading.value = true

//   // if (ID && NOREC_EMRPASIEN.value) isResumeMedis.value = false;

//   useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
//     if (isResumeMedis.value) {
//       makeRingkasanData(json);
//     }
//     isLoading.value = false
//     NOREC_EMRPASIEN.value = response.norec_emr
//     input.value.id = response.id
//   }).catch((e: any) => {
//     isLoading.value = false
//   })
// }

const simpan = async () => {
  let ID = input.value.id ? input.value.id : '';
  let object: any = {};
  object = input.value;
  object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
  object['GambarParu'] = H.tandaTangan().get("GambarParu");
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);

  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': 'Asesmen Medis',
    'jenis_emr': 'asesmen_medis',
    'data': object
  };
  isLoading.value = true;

  if (ID && NOREC_EMRPASIEN.value) isResumeMedis.value = false;

  try {
    const response: any = await useApi().post(`/emr/simpan-emr`, json);
    if (isResumeMedis.value) {
      const result = await makeRingkasanData(json);
      if (!result) {
        H.alert('error', 'Pembuatan ringkasan keluar gagal');
        return;
      }
    }

    isLoading.value = false;
    NOREC_EMRPASIEN.value = response.norec_emr;
    input.value.id = response.id;
  } catch (e: any) {
    isLoading.value = false;
    H.alert('error', 'Simpan EMR gagal');
  }
};


function checkResume() {
  // if not dokter just end
  if (kelompokUser && kelompokUser.toUpperCase() != 'DOKTER') {
    return;
  }

  let params = `?norec_pd=${props.registrasi.norec_pd}&norec_apd=${props.registrasi.norec_apd}`
  let uri = `/emr/check-resume-medis${params}`;
  useApi().get(uri).then((res) => {
    console.log(`res Check Resume`, res);
    if (res) {
      isResumeMedis.value = true;
    }
  })
}

async function makeRingkasanData(json: any) {
  return new Promise((resolve) => {
    let tanggaldatang = '';
    let dpjpUtamas: any = {
      value: json.data.user_input ? json.data.user_input.pegawaifk : json.data.registrasi.objectpegawaifk,
      label: json.data.user_input ? json.data.user_input.namalengkap : json.data.registrasi.dokter,
    };

    let fisik = '';
    fisik += json.data.TBcelciusTTV ? `Suhu : ${json.data.TBcelciusTTV} °C\n` : 'Suhu : -\n'
    fisik += json.data.TBprTTV ? `Nadi : ${json.data.TBprTTV} x/mnt\n` : 'Nadi : -\n'
    fisik += json.data.TBrrTTV ? `Pernafasan : ${json.data.TBrrTTV} x/mnt\n` : 'Pernafasan : -\n'
    fisik += json.data.TBtekananDarahTTV ? `Tekanan Darah : ${json.data.TBtekananDarahTTV} mmHg\n` : 'Tekanan Darah : -n\n'
    fisik += json.data.TBSTinggiBadanSN ? `Tinggi Badan : ${json.data.TBSTinggiBadanSN} Cm\n` : 'Tinggi Badan : -\n'
    fisik += json.data.TBSBeratBadanSaatIni ? `Berat Badan : ${json.data.TBSBeratBadanSaatIni} Kg\n` : 'Berat Badan : -\n'
    fisik += json.data.TBnsao2TTV ? `SPO2 : ${json.data.TBnsao2TTV} %\n` : ''

    let object = {
      "detailDS": [
        {
          "no": 1,
          "TADiagnosaSekunder": ""
        }
      ],
      "detailDT": [
        {
          "no": 1,
          "TADeskripsiTindakan": ""
        }
      ],
      "waktuTataLaksana": json.data.DtanggalForm,
      "waktuKontrol": json.data.HjamKedatangan,
      "jamKedatangan": json.data.HjamKedatangan,
      "jamAsesmenAwal": json.data.HjamAW,
      "riwayatkeluar": null,
      "statuskeluar": null,
      "perlukontrol": null,
      "tanggalKedatangan": json.data.DtanggalForm,
      "dpjpUtama": dpjpUtamas,
      "TAKondisiSaatMasuk": '',
      "TADiagnosisPrimer": json.data.TADiagnosis ?? null,
      "gcse": json.data.TBeGCS,
      "gcsv": json.data.TBvGCS,
      "gcsm": json.data.TBmGCS,
      "kesanUmum": json.data.CBKU,
      "nadi": json.data.TBprTTV,
      "nafas": json.data.TBrrTTV,
      "celcius": json.data.TBcelciusTTV,
      "tekananDarah": json.data.TBtekananDarahTTV ?? '',
      "anamnesis": json.data.TAAnamnesis ?? '',
      "pemeriksaanfisik": fisik,
      "intruksi": json.data.TAInstruksi ?? '',
      "hasilpemeriksaanpenunjang": json.data.TAhpp ?? '',
      "sumber": "AsmedHemodialisa"
    }
    object.nocm = json.data.pasien.nocm
    object.pasien = json.data.pasien
    object.registrasi = json.data.registrasi
    let sendData = {
      'id': '',
      'norec_emr': '',
      'collection': 'RingkasanKeluar',
      'url_form': 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
      'name_form': 'Ringkasan Keluar',
      'jenis_emr': 'asesmen_medis',
      'data': object
    }

    useApi().postNoMessage(
      `/emr/simpan-emr`, sendData).then(async (response: any) => {
        isLoading.value = false
        H.alert('success', 'Ringkasan keluar berhasil dibuat');
        return resolve(true)
      }).catch((e: any) => {
        isLoading.value = true
        H.alert('error', 'Ringkasan keluar gagal dibuat');
        return resolve(false)
      })

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
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(`/diagnosa/diagnosa-x-paging?name=${filter.query}&limit=10`)
  d_Diagnosa.value = response.diagnosa.map((item: any) => {
    return { value: item.id, label: item.kddiagnosa + ' -- ' + item.namadiagnosa }
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
const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
  let ID = idTemplate.value ? idTemplate.value : ''
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

const addTemplate = (response: any) => {
  const skipKeys = ['id', '_id', 'namatemplate', 'namaPasien', 'norm', 'tanggalLahirPasien', 'jeniskelamin']; // Keys to be skipped

  for (const key in response) {
    if (!skipKeys.includes(key)) {
      input.value[key] = response[key]; // Only update allowed keys
    }
  }
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}

const editTemplate = async (dt: any) => {
  if (!dt) return;
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
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

const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}
const addNewItem = () => {
  let newItem: any = {}
  newItem = { no: input.value.details[input.value.details.length - 1].no + 1 }
  input.value.details.push(newItem);
}

const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById(element_id);
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    let imagess = value
    let background = new Image();
    background.src = imagess
    background.onload = function () {
      context.drawImage(background, 0, 0, 680, 680);
    }
  }
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    checkResume()
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

watch(isAlltemplate, (newValue) => {
  pilihTemplateFix()
})

// getDataExist()
fetchPasien()
// setAutoFill()
</script>

<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
          <div class="form-header-inner">
            <div class="left">
              <h3>Asesmen Awal Medis Hemodialisa</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :isHideCetak="true">
              </ButtonEmr>
            </div>
          </div>
        </div>
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

        <!-- form baru -->

        <div class="columns is-multiline p-2">
          <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
              @click="pilihTemplateFix(index)"> Pilih Template
            </VButton>
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
              @click="pilihTemplate(index)"> Pilih Riwayat
            </VButton>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

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

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          <div class="columns column is-12">
            <div class="column is-4">
              <VField label="Tanggal :">
                <VDatePicker v-model="input.DtanggalForm" mode="date" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Jam Kedatangan :">
                <VDatePicker v-model="input.HjamKedatangan" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:clock" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Jam Asesmen Awal :">
                <VDatePicker v-model="input.HjamAW" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:clock" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Alloanamesis">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Suami/Istri" label="Suami/Istri"
                      v-model="input.CBSuamiORIstriAlloa" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Orang Tua" label="Orang Tua"
                      v-model="input.CBOrangTuaAlloa" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Anak" label="Anak"
                      v-model="input.CBAnakAlloa" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                      v-model="input.CBLainnyaAlloa" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBLainnyaAlloa" />
                  </VControl>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Anamnesis">
              <div class="column is-12">
                <VField>
                  <VTextarea v-model="input.TAAnamnesis" rows="3">
                  </VTextarea>
                </VField>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Pemeriksaan Fisik">
              <div class="columns is-multiline" style="padding: 10px; padding-top: 0px">
                <div class="column is-12">
                  <span><b>A.Tanda-tanda Vital</b></span>
                </div>
                <div class="column is-12">
                  <span>Keadaan umum : </span>
                </div>
                <div class="column is-12 columns is-multiline">
                  <div class="column is-6 columns is-multiline">
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Baik" label="Baik"
                          v-model="input.CBKU" />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Sedang" label="Sedang"
                          v-model="input.CBKU" />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Buruk" label="Buruk"
                          v-model="input.CBKU" />
                      </VControl>
                    </div>
                  </div>
                  <div class="column is-6">
                    <VField label="GCS : ">
                      <VField horizontal>
                        <VField addons style="padding: 10px;padding-top:0px">
                          <VControl class="field-addon-body">
                            <VButton static>E</VButton>
                          </VControl>
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TBeGCS" />
                          </VControl>
                        </VField>
                        <VField addons style="padding: 10px;padding-top:0px">
                          <VControl class="field-addon-body">
                            <VButton static>V</VButton>
                          </VControl>
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TBvGCS" />
                          </VControl>
                        </VField>
                        <VField addons style="padding: 10px;padding-top:0px">
                          <VControl class="field-addon-body">
                            <VButton static>M</VButton>
                          </VControl>
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TBmGCS" />
                          </VControl>
                        </VField>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-12 columns is-multiline">
                    <div class="column is-3">
                      <VField addons style="padding: 5px;padding-top:0px" label="Tekanan Darah : ">
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TBtekananDarahTTV" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>mmHg</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField addons style="padding: 5px;padding-top:0px" label="PR : ">
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TBprTTV" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>x/mnt</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField addons style="padding: 5px;padding-top:0px" label="RR : ">
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TBrrTTV" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>x/mnt</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField addons style="padding: 5px;padding-top:0px" label="Suhu : ">
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TBcelciusTTV" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>°C</VButton>
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-3">
                      <VField addons style="padding: 5px;padding-top:0px" label="SaO2 : ">
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TBnsao2TTV" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>%</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <span><b>B.Status Generalis</b></span>
                </div>
                <div class="column is-12 columns is-multiline">
                  <div class="column is-12">
                    <VField label="Kepala">
                      <VTextarea v-model="input.TAKepalaSG" rows="2">
                      </VTextarea>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <div class="column"><b>Mata : </b></div>
                    <div class="columns">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Anemis" label="Anemis"
                            v-model="input.CBAnemisMata" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBAnemisMata" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ikterus" label="Ikterus"
                            v-model="input.CBIkterusMata" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBIkterusMata" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Refleks Pupil" label="Refleks Pupil"
                            v-model="input.CBRefleksPupilMata" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBRefleksPupilMata" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Oedema Palpebrae"
                            label="Oedema Palpebrae" v-model="input.CBOedemaPalpebraeMata" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBOedemaPalpebraeMata" />
                        </VControl>
                      </div>
                    </div>

                    <div class="column"><b>THT : </b></div>
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tonsil" label="Tonsil"
                            v-model="input.CBTonsilTHT" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBTonsilTHT" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Pharing" label="Pharing"
                            v-model="input.CBPharingTHT" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBPharingTHT" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Telinga" label="Telinga"
                            v-model="input.CBTelingaTHT" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBTelingaTHT" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Hidung" label="Hidung"
                            v-model="input.CBHidungTHT" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBHidungTHT" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Bibir" label="Bibir"
                            v-model="input.CBBibirTHT" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBBibirTHT" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain"
                            v-model="input.CBLainnyaTHT" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBLainnyaTHT" />
                        </VControl>
                      </div>
                    </div>

                    <div class="column"><b>Leher : </b></div>
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="JVP" label="JVP"
                            v-model="input.CBJVPLeher" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBJVPLeher" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Pembesaran Kelenjar"
                            label="Pembesaran Kelenjar" v-model="input.CBPembesaranKelenjarLeher" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Kaku Kuduk" label="Kaku Kuduk"
                            v-model="input.CBKakuKudukLeher" />
                        </VControl>
                      </div>
                    </div>

                    <div class="column"><b>Thoraks : </b></div>
                    <div class="columns is-multiline">
                      <div class="column is-3 columns is-multiline">
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Simetris" label="Simetris"
                              v-model="input.CBSimetrisThoraks" />
                          </VControl>
                        </div>
                        <div class="column is-6">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Asimetris" label="Asimetris"
                              v-model="input.CBAsimetrisThoraks" />
                          </VControl>
                        </div>
                        <div class="column is-12">
                          <VControl style="margin-top: 5px">
                            <VInput type="text" class="input" v-model="input.TBSimetrisORAsimetrisThoraks" />
                          </VControl>
                        </div>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Retraksi" label="Retraksi"
                            v-model="input.CBRetraksiThoraks" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBRetraksiThoraks" />
                        </VControl>
                      </div>
                    </div>

                    <div class="column"><b>-Cor : </b></div>
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="S1,S2" label="S1,S2"
                            v-model="input.CBS1S2Cor" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBS1S2Cor" />
                        </VControl>
                        <div class="columns" style="margin-top: 5px;">
                          <div class="column is-6">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square true-value="Reguler" label="Reguler"
                                v-model="input.CBRegulerCor" />
                            </VControl>
                          </div>
                          <div class="column is-6">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square true-value="Ireguler" label="Ireguler"
                                v-model="input.CBIregulerCor" />
                            </VControl>
                          </div>
                        </div>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Murmur" label="Murmur"
                            v-model="input.CBMurmurCor" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBMurmurCor" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain"
                            v-model="input.CBLainLainCor" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBLainLainCor" />
                        </VControl>
                      </div>
                    </div>

                    <div class="column"><b>-Pulmo : </b></div>
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ronchi" label="Ronchi"
                            v-model="input.CBRonchiPulmo" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBRonchiPulmo" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Wheezing" label="Wheezing"
                            v-model="input.CBWheezingPulmo" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBWheezingPulmo" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Vesikuler" label="Vesikuler"
                            v-model="input.CBVesikulerPulmo" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBVesikulerPulmo" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain"
                            v-model="input.CBLainnyaPulmo" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBLainnyaPulmo" />
                        </VControl>
                      </div>
                    </div>

                    <div class="column"><b>Abdomen : </b></div>
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Souffle" label="Souffle"
                            v-model="input.CBSouffleAbdomen" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Distensi" label="Distensi"
                            v-model="input.CBDistensiAbdomen" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Meteorismus" label="Meteorismus"
                            v-model="input.CBMeteorismusAbdomen" />
                        </VControl>
                      </div>
                    </div>
                    <div class="column">Peristaltik</div>
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Normal" label="Normal"
                            v-model="input.CBNormalPeristaltik" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Meningkat" label="Meningkat"
                            v-model="input.CBMeningkatPeristaltik" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Menurun" label="Menurun"
                            v-model="input.CBMenurunPeristaltik" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ascites" label="Ascites"
                            v-model="input.CBAscitesPeristaltik" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Nyeri tekan lokasi"
                            label="Nyeri tekan lokasi" v-model="input.CBNyeriTekanLokasiPeristaltik" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBNyeriTekanLokasiPeristaltik" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VField label="Hepar : ">
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TBHeparPeristaltik" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4">
                        <VField label="Lien : ">
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TBLienPeristaltik" />
                          </VControl>
                        </VField>
                      </div>
                    </div>

                    <div class="column"><b>Extremitas : </b></div>
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Hangat" label="Hangat"
                            v-model="input.CBHangatExtremitas" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Dingin" label="Dingin"
                            v-model="input.CBDinginExtremitas" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Odema" label="Odema"
                            v-model="input.CBOdemaExtremitas" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBOdemaExtremitas" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain"
                            v-model="input.CBLainlainExtremitas" />
                        </VControl>
                        <VControl style="margin-top: 5px">
                          <VInput type="text" class="input" v-model="input.TBLainlainExtremitas" />
                        </VControl>
                      </div>
                    </div>

                    <div class="column">
                      <VControl>
                        <label><b>Lain-lain : </b></label>
                        <VInput type="text" class="input" v-model="input.TBLainlainSG" style="margin-top:5px" />
                      </VControl>
                    </div>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12 columns" style="overflow: auto;text-align: center;">
            <div class="column is-12">
              <Gambarin elemenID="GambarParu" :valueImg="input.Gambar" height="400" width="850"
                imageSrc="/images/simrs/penyakitdalam.png" />
            </div>
            <!-- <div class="column is-2"></div>
            <div class="column is-5">
              <Gambarin elemenID="GambarPerut" height="680" width="680" imageSrc="/images/simrs/perut.jpeg" />
            </div> -->
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Status Cairan">
              <div class="columns is-multiline column is-12">
                <div class="column is-12" style="font-weight:bold">Kekurangan</div>
                <div class="column is-12 columns">
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Normal" label="Normal"
                        v-model="input.CBNormalKekurangan" />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Kelebihan" label="Kelebihan"
                        v-model="input.CBKelebihanKekurangan" />
                    </VControl>
                  </div>
                </div>
                <div class="column is-6">
                  <VField label="Berat badan saat ini :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSBeratBadanSaatIni" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Edema extremitas :">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBEdemaExtremitas" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <label style="font-weight:lighter">Berat Badan Post HD terakhir :</label>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBSBeratBadanPostHD" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Kg</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Acites :">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBAcites" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <label style="font-weight:lighter">Berat Badan Kering :</label>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBSBeratBadanKering" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Kg</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Kongesti paru :">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBKongestiParu" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Status Nutrisi">
              <div class="columns is-multiline column is-12">
                <div class="column is-12 columns">
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Buruk" label="Buruk"
                        v-model="input.CBBurukSN" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Sedang" label="Sedang"
                        v-model="input.CBSedangSN" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Baik" label="Baik"
                        v-model="input.CBBaikSN" />
                    </VControl>
                  </div>
                </div>
                <div class="column is-4">
                  <VField label="Tinggi Badan :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSTinggiBadanSN" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>cm</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="BMI :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSbmiSN" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg/m<sup>2</sup></VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-4 columns is-multiline">
                  <div class="column is-12" style="font-weight:lighter">Kategori BMI</div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Under" label="Under"
                        v-model="input.CBUnderKB" />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Normal" label="Normal"
                        v-model="input.CBNormalKB" />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Over" label="Over"
                        v-model="input.CBOverKB" />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Obese" label="Obese"
                        v-model="input.CBObeseKB" />
                    </VControl>
                  </div>
                </div>
                <div class="column is-4">
                  <label style="font-weight:lighter">Rata-rata peningkatan BB antar HD :</label>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBSRataRataPeningkatanBBAntarHD" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Kg</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <label style="font-weight:lighter">Kadar Albumin :</label>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBSKaadarAlbumin" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mg/DI</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4 columns is-multiline">
                  <div class="column is-12" style="font-weight:lighter">Nafsu Makan :</div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Kurang" label="Kurang"
                        v-model="input.CBKurangNM" />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Normal" label="Normal"
                        v-model="input.CBNormalNM" />
                    </VControl>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Evaluasi Dialisis (Diisi Sesuai Jenis Pasien)">
              <div class="column is-12 columns">
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Pasien Hemodialisis"
                      label="Pasien Hemodialisis" v-model="input.CBPasienHemodialisisTD" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Pasien Peritonial dialysis"
                      label="Pasien Peritonial dialysis" v-model="input.CBPasienPeritonialDialysisTD" />
                  </VControl>
                </div>
              </div>
              <div class="column is-12" style="font-weight:bold">A. Pasien Hemodialisis</div>
              <div class="column is-12 columns is-multiline">
                <div class="column is-6">
                  <VField label="Tgl mulai HD pertama :">
                    <VDatePicker v-model="input.DTglMulaiHDPertama" mode="date" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Frekuensi HD :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSFrekuensiHD" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/minggu</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Lama waktu setiap HD :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSLamaWaktuSetiapHD" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Jam</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <label style="font-weight:lighter">Dialiser yang digunakan, tipe :</label>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBDialiserYangDigunakan" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VField label="Luas membrane :">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLuasMembrane" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6 columns is-multiline">
                  <div class="column is-12" style="font-weight:lighter">Jenis :</div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Low Flux" label="Low Flux"
                        v-model="input.CBLowFluxJ" />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="High Flux" label="High Flux"
                        v-model="input.CBHighFluxJ" />
                    </VControl>
                  </div>
                </div>
              </div>
              <div class="column is-12 columns is-multiline" style="margin-top:10px">
                <div class="column is-12">Keluhan interdialitik yang sering timbul :</div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Kram" label="Kram"
                      v-model="input.CBKramKI" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Mual Muntah" label="Mual Muntah"
                      v-model="input.CBMualMuntahKI" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Hipertensi" label="Hipertensi"
                      v-model="input.CBHipertensiKI" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Dizziness" label="Dizziness"
                      v-model="input.CBDizzinessKI" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Hipoksemia" label="Hipoksemia"
                      v-model="input.CBHipoksemiaKI" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Komplikasi kardivaskuler"
                      label="Komplikasi kardivaskuler" v-model="input.CBKomplikasiKardivaskulerKI" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Tidak ada keluhan"
                      label="Tidak ada keluhan" v-model="input.CBTidakAdaKeluhanKI" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya, sebutkan"
                      label="Lainnya, sebutkan" v-model="input.CBLainnyaSebutkan" />
                  </VControl>
                  <VControl style="margin-top: 5px">
                    <VInput type="text" class="input" v-model="input.TBLainnyaSebutkan" />
                  </VControl>
                </div>
              </div>
              <div class="column is-12 columns is-multiline" style="margin-top:10px">
                <div class="column is-12">Adekuasi Dialisis :</div>
                <div class="column is-6">
                  <VField label="URR terakhir">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBURRTerakhir" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                  <div class="column is-12 columns">
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="dalam 6 bulan" label="dalam 6 bulan"
                          v-model="input.CBDalam6BulanURRTerakhir" />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="1 tahun terakhir"
                          label="1 tahun terakhir" v-model="input.CB1TahunTerakhirURRTerakhir" />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-6">
                  <VField label="Kt/v :">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBKTV" />
                    </VControl>
                  </VField>
                  <div class="column is-12 columns">
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="6 bulan" label="6 bulan"
                          v-model="input.CB6BulanKTV" />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="1 tahun terakhir"
                          label="1 tahun terakhir" v-model="input.CB1TahunTerakhirKTV" />
                      </VControl>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12" style="font-weight:bold">B. Pasien Peritonial dialysis</div>
              <div class="column is-12 columns is-multiline">
                <div class="column is-12">
                  <VField label="Total cairan keluar / hari :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSTotalCairanKeluar" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>MI</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Frekuensi pergantian /hari :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSFrekuensiPergantian" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>kali,</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <label style="font-weight:lighter">Konsentrasi cairan yang digunakan :</label>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBKCYD" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <label style="font-weight:lighter">Adekuasi Dialisis : Kt/V</label>
                </div>
                <div class="column is-6">
                  <label style="font-weight:lighter">Hasil PET :</label>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBHasilPET" />
                  </VControl>
                </div>
                <div class="column is-12">Pemakaian Eritropoitin :</div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Tidak ada" label="Tidak ada"
                      v-model="input.CBTidakAdaPE" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Ada" label="Ada"
                      v-model="input.CBAdaPE" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VField label="Sejak :">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBSejakPE" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Jenisnya :">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBJenisnyaPE" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Dosisnya :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSDosisnyaPE" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>x/minggu</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-12 columns">
                  <div class="column is-6">Riwayat Transfusi selama Dialisis :</div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Ada" label="Ada"
                        v-model="input.CBAdaRTSD" />
                    </VControl>
                  </div>
                </div>
                <div class="column is-12 columns">
                  <div class="column is-6">
                    <VField label="Produksi urine perhari :">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBProduksiUrinePerhari" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Warna">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBWarnaPUP" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Care Plan">
              <div class="column is-12">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Adekuasi Dialisis" label="Adekuasi Dialisis"
                    v-model="input.CBAdekuasiDialisis" />
                </VControl>
              </div>
              <div class="column is-12 columns is-multiline" style="margin-left:10px">
                <div class="column is-12">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Peresepan Hemodialisis"
                      label="Peresepan Hemodialisis" v-model="input.CBPeresepanHemodialisis" />
                  </VControl>
                </div>
                <div class="column is-12">
                  <VField label="Blood Flow rate :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSBloodFlowRate" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>ml/menit</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Anti Koagulan :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSAntiKoagulan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Unit</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="dosis awal :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSDosisAwal" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>unit</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Dosis pemeliharaan :">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSDosisPemeliharaan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Unit/jam</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Ukuran dialiser :">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBUkuranDialiser" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Frekuensi HD / minggu :">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBFrekuensiHDMinggu" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Akses Vaskuler :">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBAksesVaskulerPH" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12" style="margin-top:20px">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Peresepan CAPD" label="Peresepan CAPD"
                      v-model="input.CBPeresepanCAPD" />
                  </VControl>
                </div>
                <div class="column is-6">
                  <VField label="Frekuensi pergantian">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBFrekuensiPergantianPCAPD" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Konsetrasi">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBKonsetrasiPCAPD" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Status nutrisi" label="Status nutrisi"
                    v-model="input.CBStatusNutrisi" />
                </VControl>
              </div>
              <div class="column is-12 columns">
                <div class="column is-6">
                  <VField label="BB Kering">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBBBKering" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Diet">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBDiet" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Manajemen Anemia" label="Manajemen Anemia"
                    v-model="input.CBManajamenAnemia" />
                </VControl>
              </div>
              <div class="column is-12 columns is-multiline">
                <div class="column is-12">Th/Anemia</div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Terapi Besi" label="Terapi Besi"
                      v-model="input.CBTerapiBesi" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Terapi erythropoietin"
                      label="Terapi erythropoietin" v-model="input.CBTerapiErythropoietin" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Transfusi darah" label="Transfusi darah"
                      v-model="input.CBTranfusiDarah" />
                  </VControl>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Hasil Pemeriksaan Penunjang">
              <VField>
                <VTextarea rows="2" v-model="input.TAhpp"></VTextarea>
              </VField>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="DIAGNOSIS (ICD X)" style="margin-bottom: 10px">
              <div class="column">
                <VField>
                  <VTextarea rows="2" v-model="input.TADiagnosis"></VTextarea>
                </VField>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Rencana Kerja Dokter (Plan Of Care)" style="margin-bottom: 10px">
              <table class="tg">
                <thead>
                  <tr>
                    <th style="width: 30%;">Daftar Masalah</th>
                    <th style="width: 30%;">Rencana Intervensi</th>
                    <th style="width: 30%;">Target<br>(Kondisi yang diharapkan dan waktu)</th>
                    <th style="width: 10%;">#</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in input.details" :key="index">
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.TAdaftarMasalah"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.TArencanaIntervensi"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.TAtarget"></VTextarea>
                      </VField>
                    </td>
                    <td style="vertical-align: inherit">
                      <div class="column">
                        <VButtons style="justify-content:space-around">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                            color="info">
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
            </Fieldset>
          </div>
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Instruksi" style="margin-bottom: 10px">
              <VField>
                <VTextarea rows="2" v-model="input.TAInstruksi"></VTextarea>
              </VField>
            </Fieldset>
          </div>
          <div class="columns column is-12">
            <div class="column is-8"></div>
            <div class="column is-4">
              <VField label="Garut">
                <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>
              <div class="column" style="text-align:center;">
                <h1>Tanda Tangan Dokter</h1>
                <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" />
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.CBDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                </VControl>
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
        <!-- form baru -->
      </div>
    </div>
  </div>


</template>
