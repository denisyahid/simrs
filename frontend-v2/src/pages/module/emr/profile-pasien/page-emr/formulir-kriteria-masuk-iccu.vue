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
                color="warning"
                raised
                icon="lnir lnir-printer"
                :disabled="isDisabled"
                @click="print"
              >
                Cetak
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
                <VInput v-model="input.namaPasien" class="input" type="text" />
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
                        v-on="inputEvents"
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
                  type="number"
                  class="input"
                  placeholder="No. Rekam Medis"
                  v-model="input.norm"
                />
              </VControl>
            </VField>
          </div>

          <div class="column is-12">
            <table class="tg" style="width: 100%">
              <thead>
                <tr>
                  <th class="th-pri" width="50%">Kriteria Fisiologis</th>
                  <th class="th-pri" width="30%">YA</th>
                  <th class="th-pri" width="30%">TIDAK</th>
                </tr>
              </thead>
              <tbody>
                <template v-for="(kategori, index) in kriteria" :key="index">
                  <tr class="tr-pri">
                    <h1 style="font-weight: bold" class="ml-2f">{{ kategori.label }}</h1>
                  </tr>
                  <tr v-for="(item, idx) in kategori.detail" :key="idx">
                    <td class="td-pri">{{ item.deskripsi }}</td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox
                            v-model="input[item.model]"
                            class="pt-1 pb-1"
                            true-value="YA"
                            label="YA"
                            color="primary"
                            square
                          />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox
                            v-model="input[item.model]"
                            class="pt-1 pb-1"
                            true-value="TIDAK"
                            label="TIDAK"
                            color="primary"
                            square
                          />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>

          <div class="column is-12">
            <div class="column is-12">
              <h1 style="font-weight: bold">Kesimpulan</h1>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold">Berdasarkan indikasi diatas, maka memenuhi masuk iccu dengan prioritas:</h1>
              <VField>
                <VControl class="prime-auto">
                  <VInput v-model="input.prioritasIccu" type="text" color="primary" class="input" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Alat Transport yang digunakan:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VCheckbox
                    v-model="input.alatTransport"
                    label="Brancard"
                    color="primary"
                    true-value="Brancard"
                    circle
                  />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VCheckbox
                    v-model="input.alatTransport"
                    label="Kursi Roda"
                    color="primary"
                    true-value="Kursi Roda"
                    circle
                  />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Pendamping saat transfer:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VCheckbox
                    v-model="input.pendampingSaatTransfer"
                    label="Dokter"
                    color="primary"
                    true-value="Dokter"
                    circle
                  />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VCheckbox
                    v-model="input.pendampingSaatTransfer"
                    label="Perawat"
                    color="primary"
                    true-value="Perawat"
                    circle
                  />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VCheckbox
                    v-model="input.pendampingSaatTransfer"
                    label="Caregiver/POS"
                    color="primary"
                    true-value="Caregiver/POS"
                    circle
                  />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12">
            <div class="column is-12">
              <h1 style="font-weight: bold">Alat Medis yang dibawa saat transfer</h1>
            </div>
            <div class="column is-12 is-flex">
              <VField>
                <VControl>
                  <VCheckbox
                    v-model="input.alatMedisCheckBox"
                    label="Ya, sebutkan"
                    color="primary"
                    true-value="Ya"
                    circle
                  />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput
                    v-model="input.alatMedis"
                    type="text"
                    placeholder="Alat Medis"
                    class="w-full"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VCheckbox
                    v-model="input.alatMedisCheckBox"
                    label="Tidak"
                    color="primary"
                    true-value="Tidak"
                    circle
                  />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12">
            <div class="column is-12" style="align-items: center">
              <h1 style="font-weight: bold" class="ml-3">Nama DPJP dan Tanda Tangan</h1>
              <div class="column is-flex is-6">
                <h1 style="font-weight: bold" class="mr-3">Garut,</h1>
                <VField>
                  <VControl>
                    <VDatePicker v-model="input.tanggalTandaTangan" mode="dateTime" style="width: 100%" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal dan Jam" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <TandaTangan :elemenID="'TTDdokter'" :width="'150'" :height="'150'" class="dek" />
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.dokterBertugas" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ..."
                      class="mt-2 is-3" />
                  </VControl>
                </VField>
              </div>
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
import * as EMR from "../page-emr-plugins/kriteria-masuk-iccu";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";

let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;
let JenisKelamin = ref(EMR.JenisKelamin());
let kriteria: any = ref(EMR.kriteria());


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
const d_Ruangan: any = ref([]);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: "",
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
});
const COLLECTION: any = ref("FormulirKriteriaMasukIccu"); //table mongodb
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

const loadRiwayat = async () => {
    // if (NOREC_EMRPASIEN.value == '') return
    await useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
                dataTTD.value = response[0]
            }
        })
    H.tandaTangan().set("TTDdokter", dataTTD.value.TTDdokter)
    // H.tandaTangan().set("TTDDokterPemeriksa", dataTTD.value.TTDDokterPemeriksa)
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

const simpan = () => {
  let ID = input.value.id ? input.value.id : "";

  let object: any = {};

  object = input.value;
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  object['TTDdokter'] = H.tandaTangan().get("TTDdokter");
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

const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
}

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

setView();
getDataExist();
loadRiwayat();
setAutoFill();
fetchRuangan({ query: "" });
fetchDokter({ query: "" });
</script>
