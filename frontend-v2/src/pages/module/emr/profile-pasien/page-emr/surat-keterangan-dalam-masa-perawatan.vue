<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Surat Keterangan Dalam Masa Perawatan (Surat Kontrol)</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>
      </div>

      <div class="columns is-multiline p-2">
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12 is-flex">
              <div class="column is-12">
                <h1>Yang bertanda tangan dibawah ini:</h1>
              </div>
            </div>

            <div class="column is-12 is-flex ml-5">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-12">
                <h1>Menerangkan dengan sebenarnya bahwa :</h1>
              </div>
            </div>

            <div class="column is-12 is-flex ml-5">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex ml-5">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex ml-5">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-12 is-flex ml-5">
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

            <div class="column is-12 is-flex ml-5">
              <div class="column is-2">
                <h1 style="font-weight: bold">Diagnosa:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.diagnosa" class="input" type="text" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex ml-5">
              <div class="column is-2">
                <h1 style="font-weight: bold">Terapi:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.terapi" class="input" type="text" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex ml-5">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL KONTROL:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalKontrol" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-12">
                <h1>Pasien tersebut di atas <span style="font-weight: bold">Masih Dalam Pengawasan dengan tindak
                    lanjut</span> yang
                  disarankan :</h1>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VCheckbox color="primary" v-model="input.rawatJalan" label="Perawatan RAWAT JALAN"
                      true-value="Perawatan RAWAT JALAN" false-value="" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 mt-3">
                <p><span style="font-weight: bold">POLIKLINIK</span> yang dituju :</p>
              </div>
              <div class="column is-5">
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.poli" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari poliklinik ..."
                      class="mt-2" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-12 is-flex">
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VCheckbox color="primary" v-model="input.perawatanHemodialisa" label="Perawatan HEMODIALISA"
                      true-value="Perawatan HEMODIALISA" false-value="" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-12 is-flex">
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VCheckbox color="primary" v-model="input.perawatanChemoTeraphy" label="Perawatan CHEMOTERAPHY SERI"
                      true-value="Perawatan CHEMOTERAPHY" false-value="" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-8">
                <VField>
                  <VControl>
                    <VInput v-model="input.keteranganPerawatanChemoteraphy" class="input"
                      label="KETERANGAN PERAWATAN CHEMOTERAPHY" type="text" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-12">
                <h1>
                  Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
                </h1>
              </div>
            </div>

            <div class="column is-12 is-flex ml-5 justify-content-end">
              <div class="column is-6" style="text-align: center">
                <h1 style="font-weight: bold">Garut</h1>
                <div class="column is-12 pt-0 is-flex" style="justify-content: center;">
                  <VDatePicker v-model="input.tanggal" color="green" trim-weeks mode="dateTime" :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }" class="pb-0">
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                          class="is-rounded_Z" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
                <h1 style="font-weight: bold">Dokter Yang Merawat</h1>
                <!-- <TandaTangan :elemenID="'TTDdokter'" :width="'150'" :height="'150'" class="dek" /> -->
                <VField>
                  <VControl>
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" style="width: 65%;" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

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
const route = useRoute()
const { y } = useWindowScroll();
const isStuck = computed(() => {
  return y.value > 30;
});
const isLoading: any = ref(false);
const i: any = ref(false);
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
const COLLECTION: any = ref("SuratKeteranganDalamMasaPerawatan"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggalKontrol: new Date(),
  tanggal: new Date(),
});
const setView = () => {
  useHead({
    title: "Surat Keterangan Dalam Masa Perawatan (Surat Kontrol)" + " - " + import.meta.env.VITE_PROJECT,
  });
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
  useViewWrapper().setFullWidth(true);
};

const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
      }
    })
  // H.tandaTangan().set("TTDdokter", dataTTD.value.TTDdokter)
  H.tandaTangan().set("TTDpasien", dataTTD.value.TTDpasien)
}

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
console.log(props.pasien);

const simpan = () => {
  let ID = input.value.id ? input.value.id : "";

  let object: any = {};

  object = input.value;
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  // object['TTDdokter'] = H.tandaTangan().get("TTDdokter");
  object['TTDpasien'] = H.tandaTangan().get("TTDpasien");
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: route.name,
    name_form: 'Surat Keterangan Dalam Masa Perawatan(Surat Kontrol)',
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

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.alamatLengkap = props.pasien.alamatlengkap;
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  input.value.DDDokter = props.registrasi.dokter;
  input.value.tglPembuatan = new Date();

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

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
}

const d_Ruangan: any = ref([])
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

setView();
getDataExist();
loadRiwayat();
setAutoFill();
fetchDokter({ query: "" });
fetchRuangan({ query: "" });
</script>
