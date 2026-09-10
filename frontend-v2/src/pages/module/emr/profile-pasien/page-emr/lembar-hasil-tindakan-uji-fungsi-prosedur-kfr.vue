<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                Kembali
              </VButton>
              <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                :disabled="isDisabled" @click="print">
                Cetak
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()">
                Simpan
              </VButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">NAMA PASIEN:</h1>
            </div>
            <div class="column pt-0 pl-0 is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
            </div>
            <div class="column pt-0 pl-0 is-10">
              <VField>
                <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" disabled />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>

          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
            </div>
            <div class="column pt-0 pl-0 is-10" style="display: flex">
              <VField v-for="items in JenisKelamin" :key="items.value">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                    :label="items.label" color="primary" disabled circle />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">No. Rekam Medis:</h1>
            </div>
            <div class="column pt-0 pl-0 is-10">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">Alamat:</h1>
            </div>
            <div class="column pt-0 pl-0 is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.alamatLengkap" class="input" type="text" disabled />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">NO TELEFON:</h1>
            </div>
            <div class="column pt-0 pl-0 is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.noTelfon" class="input" type="text" disabled />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">TANGGAL PEMERIKSAAN:</h1>
            </div>
            <div class="column pt-0 pl-0 is-10">
              <VField>
                <VDatePicker v-model="input.tanggalPemeriksaan" mode="datetime" trim-weeks :max-date="new Date()">
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
          </div>

          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">DIAGNOSA FUNGSIONAL:</h1>
            </div>
            <div class="column pt-0 pl-0 is-10">
              <VField>
                <VControl>
                  <VTextarea v-model="input.diagnosaFungsional" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">DIAGNOSA MEDIS:</h1>
            </div>
            <div class="column pt-0 pl-0 is-10">
              <VField>
                <VControl>
                  <VTextarea v-model="input.diagnosaMedis" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-12">
              <h1 style="font-weight: bold">INSTRUMEN UJI FUNGSI PROSEDUR KFR:</h1>
            </div>
            <!-- <div class="column pt-0 pl-0 is-10">
              <VField>
                <VControl>
                  <VTextarea v-model="input.instrumenUjiFungsi" class="input" type="text" />
                </VControl>
              </VField>
            </div> -->
          </div>

          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">HASIL YANG DI DAPATKAN:</h1>
            </div>
            <div class="column pt-0 pl-0 is-10">
              <VField>
                <VControl>
                  <VTextarea v-model="input.hasilYangDidapatkan" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">KESIMPULAN:</h1>
            </div>
            <div class="column pt-0 pl-0 is-10">
              <VField>
                <VControl>
                  <VTextarea v-model="input.kesimpulan" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column pt-0 pl-0 is-12 is-flex">
            <div class="column pt-0 pl-0 is-2">
              <h1 style="font-weight: bold">Rekomendasi:</h1>
            </div>
            <div class="column is-10 pt-0 pl-0">
              <VCheckbox class="fontcheckbox" v-model="input.evaluasiMinggu" true-value="2 minggu" color="primary" />
              <span v-html="highlightMatch('2 minggu')" class="highlighted-label"></span><br>
              <VCheckbox class="fontcheckbox" v-model="input.evaluasiMinggu" style="margin-top: -20px;"
                true-value="Rujuk balik" color="primary" /><span v-html="highlightMatch('Rujuk balik')"
                class="highlighted-label"></span><br>
              <VCheckbox class="fontcheckbox" v-model="input.evaluasiMinggu" style="margin-top: -20px;"
                true-value="Stop terapi" color="primary" /><span v-html="highlightMatch('Stop terapi')"
                class="highlighted-label"></span><br>
              <VCheckbox class="fontcheckbox" v-model="input.evaluasiMinggu" style="margin-top: -20px;"
                true-value="Lainnya2" color="primary" /><span v-html="highlightMatch('Lainnya')"
                class="highlighted-label"></span><br>
              <textarea v-model="input.textLainnya2" class="textarea"
                v-if="input.evaluasiMinggu === 'Lainnya2'" placeholder=""></textarea>
            </div>
          </div>

          <div class="column is-4">
            <VField label="Dokter">
              <VControl icon="" fullwidth class="prime-auto ">
                <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari..." />
              </VControl>
            </VField>
          </div>
        </div>
      </VCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from "@vueuse/core";
import { useApi } from "/@src/composable/useApi";
import { h, reactive, ref, computed, watch, onBeforeMount } from "vue";
import { useRoute } from "vue-router";
import { useHead } from "@vueuse/head";
import * as H from "/@src/utils/appHelper";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import AutoComplete from "primevue/autocomplete";
import MultiSelect from "primevue/multiselect";
import * as EMR from "../page-emr-plugins/lembaran-penyiaran-radioterapi";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";

let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;

let jenisTindakanRadioterapi = ref(EMR.jenisTindakanRadioterapi());
let JenisKelamin = ref(EMR.JenisKelamin());
let energy = ref(EMR.energy());
let accessories: any = ref(EMR.accessories());
let posisiMeja: any = ref(EMR.formField());

const props = withDefaults(
  defineProps<{
    pasien?: any;
    registrasi?: any;
    FORM_NAME?: string;
    FORM_URL?: string;
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: "",
    FORM_URL: "",
  }
);
const RiwayatPsikososial: any = ref([
  { label: "Baik", value: "Baik" },
  { label: "Tidak Baik", value: "Tidak Baik" },
]);

const { y } = useWindowScroll();
const isStuck = computed(() => {
  return y.value > 30;
});
const isLoading: any = ref(false);
const isDisabled: any = ref(false);
const isLoadingVitalSign: any = ref(false);
const dataTTD: any = ref([]);
const d_Perawat: any = ref([]);
const d_Dokter: any = ref([]);
const filterMenu: any = ref('')
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: "",
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
});
const COLLECTION: any = ref("LembarHasilTindakanUjiFungsiProsedurKFR");
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggalPemeriksaan: new Date(),
});
const setView = () => {
  useHead({
    title: props.FORM_NAME + " - " + import.meta.env.VITE_PROJECT,
  });
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
  useViewWrapper().setFullWidth(true);
};

const loadRiwayat = async () => {
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      // dataTTD.value = response[0]
    } else {
      setAutoFill();
      useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail&flag=dokter&ruangan=" + props.registrasi.namaruangan + "&field=O").then((responses) => {
        if (responses != null) {
          // input.value.TAAnamnesa = responses.S
          input.value.hasilYangDidapatkan = responses.O
          // input.value.TBDiagnosisMedis = responses.A
          // input.value.TATataLaksanaKFR = responses.P
          isLoading.value = false
        } else {
          console.log('Data CPPT Detail Kosong')
          isLoading.value = false
        }
      })
    }
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : "";
  let object: any = {};

  object = input.value;
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: "asesmen_medis",
    data: object,
  };
  isLoading.value = true;
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false;
    NOREC_EMRPASIEN.value = response.norec_emr;
    loadRiwayat();
  })
    .catch((e: any) => {
      isLoading.value = false;
    });
};

const kembaliKeun = () => {
  window.history.back();
};

// const print = async () => {
//   H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
// }

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.alamatLengkap = props.pasien.alamatlengkap;
  input.value.noTelfon = props.pasien.nohp;
  input.value.DDDokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
};

// const fetchDetailCPPT = async (index: any) => {
//   await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail&flag=dokter" + "&field=S,O,A,P").then((responses) => {
//     if (responses != null) {
//       input.value.hasilYangDidapatkan = responses.O
//       isLoading.value = false
//       console.log('cppt', responses)
//     } else {
//       console.log('Data CPPT Detail Kosong')
//       isLoading.value = false
//     }
//   })
// }

const getDataFisio = async () => {
  // isLoading.value = true

  await useApi().get(`emr/get-data-formulir-fisik?nocmfk=${ID_PASIEN}`).then((response) => {
    if (response != null) {
      input.value.diagnosaFungsional = response.TBDiagnosisFungsi
      input.value.diagnosaMedis = response.TBDiagnosisMedis
      input.value.evaluasiMinggu = response.evaluasiMinggu
    }
    // isLoading.value = false
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => {
    d_Dokter.value = response;
  });
};

watch(
  () => input.value.CBDokter,
  (newVal) => {

    if (newVal.value === 396) {
      input.value.spesialisBertandaTangan = "Penyakit Dalam";
      input.value.jabatanBertandaTangan = "Dokter Penanggung Jawab HD";
    }
    if (newVal.value === 367) {
      input.value.spesialisBertandaTangan = "-";
      input.value.jabatanBertandaTangan = "Dokter Pelaksana HD";
    }
  }
);

function highlightMatch(text) {
  if (!filterMenu.value) return text;

  const term = new RegExp(`(${filterMenu.value})`, 'gi');
  return text.replace(term, '<span style="background-color: yellow;">$1</span>');
}


loadRiwayat()
getDataFisio()
// fetchDetailCPPT()
setView()
// fetchDokter({ query: "" });
</script>
