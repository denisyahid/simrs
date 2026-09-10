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
              <VButton
                icon="lnir lnir-arrow-left rem-100"
                light
                dark-outlined
                @click="kembaliKeun()"
              >
                Kembali
              </VButton>
              <VButton
                type="button"
                rounded
                outlined
                color="primary"
                raised
                icon="feather:save"
                :loading="isLoading"
                @click="simpan()"
              >
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
          <div class="column is-2">
            <h1 style="font-weight: bold">NAMA PASIEN:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.namaPasien" class="input" type="text" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker
                v-model="input.tanggalLahirPasien"
                mode="date"
                trim-weeks
                :max-date="new Date()"
              >
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput
                        :value="inputValue"
                        placeholder="Tanggal"
                        disabled
                      />
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
                <VCheckbox
                  v-model="input.jeniskelamin"
                  class="pt-1 pb-1"
                  :true-value="items.label"
                  :label="items.label"
                  color="primary"
                  disabled
                  circle
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">No. Rekam Medis:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="No. Rekam Medis"
                  v-model="input.norm"
                  disabled
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">TANGGAL Kedatangan:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker
                v-model="input.tanggalKunjunganPasien"
                mode="dateTime"
                style="width: 100%"
                disabled
              >
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput
                        :value="inputValue"
                        placeholder="Tanggal"
                        disabled
                      />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>

          <div class="column is-12">
            <div class="column is-12">
              <h1 style="font-weight: bold">Lapangan:</h1>
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="input.lapangan" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-3">
              <h1 style="font-weight: bold">Lng</h1>
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="input.lng" />
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <h1 style="font-weight: bold">Lat</h1>
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="input.lat" />
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <h1 style="font-weight: bold">Vrt</h1>
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="input.vrt" />
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <h1 style="font-weight: bold">Paraf Dokter</h1>
              <VField class="is-rounded-select is-autocomplete-select">
                <VControl icon="fa:user-md" class="prime-auto-cus">
                  <AutoComplete
                    v-model="input.dokterParaf"
                    :suggestions="d_Dokter"
                    :optionLabel="'label'"
                    @complete="listPegawai($event)"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="Dokter..."
                  />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from "@vueuse/core";
import { useApi } from "/@src/composable/useApi";
import { h, reactive, ref, computed, watch } from "vue";
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
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: "",
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
});
const COLLECTION: any = ref("FormulirVerifikasiEpid"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggal: new Date(),
});
const setView = () => {
  useHead({
    title: props.FORM_NAME + " - " + import.meta.env.VITE_PROJECT,
  });
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
  useViewWrapper().setFullWidth(true);
};

const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      if (response.length > 0) {
        isDisabled.value = false;
        input.value = response[0]; //set ke inputan
        if (NOREC_EMRPASIEN.value == "") {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk;
        }
        dataTTD.value = response[0];
      } else {
        isDisabled.value = true;
      }
    });
  H.tandaTangan().set("TTDperawat1", dataTTD.value.TTDperawat);
};

const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Perawat.value = response;
    });
};

const getDataExist = async () => {
  await useApi()
    .get(
      `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response) => {
      // input.value.beratBadan = response.beratBadan
      // input.value.tinggiBadan = response.tinggiBadan
      // input.value.IMT = response.IMT
      console.log();
    });
};

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
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
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

const print = async () => {
  H.printBlade(
    `emr/cetak-formulir-permintaan-konseling-gizi?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  );
};

const listPegawai = async () => {
  await useApi().get(
    // `/dashboard/radiologi/get-only-dokter`).then((response: any) => {   // default
    `/dashboard/radiologi/get-only-dokter-kemoterapi`).then((response: any) => {
      console.log(response.data)
      d_Dokter.value = response.data.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
      })
    })
}
listPegawai();

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  input.value.tglPembuatan = new Date();

  isLoadingVitalSign.value = true;
  await useApi()
    .get(
      "emr/auto-fill?norec_pd=" +
        props.registrasi.norec_pd +
        "&collection=VitalSign" +
        "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
    )
    .then((response) => {
      if (response != null) {
        input.value.beratBadan = response.beratBadan;
        input.value.tinggiBadan = response.tinggiBadan;
        input.value.IMT = response.IMT;
        input.value.lingkarPerut = response.lingkarPerut;
        input.value.tekananDarah = response.tekananDarah;
        input.value.pernapasan = response.pernapasan;
        input.value.suhu = response.suhu;
        input.value.nadi = response.nadi;
      }
      isLoadingVitalSign.value = false;
    });
};

const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response;
    });
};

setView();
getDataExist();
loadRiwayat();
setAutoFill();
fetchPerawat({ query: "" });
fetchDokter({ query: "" });
</script>
