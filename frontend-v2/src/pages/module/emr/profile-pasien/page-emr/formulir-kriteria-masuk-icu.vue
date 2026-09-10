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
      <div class="columns is-multiline p-2">
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-2">
              <h1 style="font-weight: bold">Nama Pasien :</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.namaPasien" class="input" type="text" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold">Tanggal Lahir Pasien :</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
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
            <div class="column is-2">
              <h1 style="font-weight: bold">Jenis Kelamin :</h1>
            </div>
            <div class="column is-10" style="display: flex">
              <VField v-for="items in JenisKelamin" :key="items.value">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                    :label="items.label" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold">No. Rekam Medis :</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" />
                </VControl>
              </VField>
            </div>

            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>

            <div class="column is-12">
              <table class="tg" style="width: 100%">
                <thead>
                  <tr>
                    <th class="th-pri" width="50%">Kriteria Fisiologis</th>
                    <th class="th-pri" style="text-align: center;" width="30%">YA</th>
                    <th class="th-pri" style="text-align: center;" width="30%">TIDAK</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(kategori, index) in kriteria" :key="index">
                    <tr class="tr-pri">
                      <h1 style="font-weight: bold" class="ml-2">{{ kategori.label }}</h1>
                    </tr>
                    <tr v-for="(item, idx) in kategori.detail" :key="idx">
                      <td class="td-pri" v-if="item.model !== 'kebutuhanMonitoringIntesif'">{{ item.deskripsi }}</td>
                      <td class="td-pri" v-if="item.model !== 'kebutuhanMonitoringIntesif'">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input[item.model]" class="pt-1 pb-1" true-value="YA" label="YA"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" v-if="item.model !== 'kebutuhanMonitoringIntesif'">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input[item.model]" class="pt-1 pb-1" true-value="TIDAK" label="TIDAK"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" v-if="item.model === 'kebutuhanMonitoringIntesif'">
                        <VField>
                          <VControl>
                            <VTextarea v-model="input.kebutuhanMonitoringIntesif1" class="pt-1 pb-1"
                              placeholder="Ketik ..." color="primary" square />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" v-if="item.model === 'kebutuhanMonitoringIntesif'" colspan="2">
                        <VField>
                          <VControl>
                            <VTextarea v-model="input.kebutuhanMonitoringIntesif2" class="pt-1 pb-1"
                              placeholder="Ketik ..." color="primary" square />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>

            <div class="column is-12 pt-0">
              <div class="column is-12">
                <h1 style="font-weight: bold">Kesimpulan</h1>
              </div>
              <div class="column is-12 pt-0">
                <div class="is-flex">
                  <h1 style="font-weight: bold">Memenuhi indikasi masuk ICU dengan Prioritas:</h1>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.memenuhiIndikasiMasukICU" class="pt-1 pb-1" true-value="1" label="1"
                        color="primary" square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.memenuhiIndikasiMasukICU" class="pt-1 pb-1" true-value="2" label="2"
                        color="primary" square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.memenuhiIndikasiMasukICU" class="pt-1 pb-1" true-value="3" label="3"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <VField>
                  <VControl class="prime-auto">
                    <VInput v-model="input.keteranganMemenuhiIndikasiMasukICU" class="input" type="text"
                      placeholder="Ketik ..." />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 pt-0">
              <div class="columns is-multiline">
                <div class="column is-4 pb-0">
                  <h1 style="font-weight: bold">Alat Transport yang digunakan:</h1>
                </div>
                <div class="column is-12 is-flex pt-0">
                  <VControl>
                    <VCheckbox v-model="input.alatTransport" label="Brancard" color="primary" true-value="Brancard"
                      circle />
                  </VControl>
                  <VControl>
                    <VCheckbox v-model="input.alatTransport" label="Kursi Roda" color="primary" true-value="Kursi Roda"
                      circle />
                  </VControl>
                </div>
              </div>
            </div>

            <div class="column is-12 pt-0">
              <div class="columns is-multiline">
                <div class="column is-4 pb-0">
                  <h1 style="font-weight: bold">Pendamping saat transfer:</h1>
                </div>
                <div class="column is-12 pt-0 is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.PSTDokter" label="Dokter" color="primary" true-value="Dokter" circle />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.PSTPerawat" label="Perawat" color="primary" true-value="Perawat"
                        circle />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.PSTCaregiver" label="Caregiver/POS" color="primary"
                        true-value="Caregiver/POS" circle />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12 pt-0">
              <div class="colums is-multiline">
                <div class="column is-12 pl-0 pt-0">
                  <h1 style="font-weight: bold">Alat Medis yang dibawa saat transfer</h1>
                </div>
                <div class="column is-12 is-flex pb-0 pt-0 pl-0">
                  <VControl>
                    <VCheckbox v-model="input.alatMedis" label="Ya, sebutkan" color="primary" true-value="Ya" circle />
                  </VControl>
                  <VControl v-if="input.alatMedis == 'Ya'">
                    <VInput v-model="input.alatMedisDetail" type="text" placeholder="Alat Medis" class="w-full" />
                  </VControl>
                </div>
                <div class="column is-12 pt-0 pl-0">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.alatMedis" label="Tidak" color="primary" true-value="Tidak" circle />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12 pt-0">
              <div class="columns">
                <div class="column is-4" style="margin-left: auto;">
                  <h1 style="font-weight: bold">Garut</h1>
                  <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal dan Jam" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                  <div class="is-flex mt-2" style="justify-content: center;">
                    <TandaTangan :elemenID="'TTDPegawai'" :width="'150'" :height="'150'" class="dek" />
                  </div>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.dokterBertugas" :suggestions="d_Pegawai"
                      @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                      placeholder="Ketik untuk mencari ..." class="mt-2 is-3" />
                  </VControl>
                </div>
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
import * as EMR from "../page-emr-plugins/kriteria-masuk-icu";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

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
const isStuck = computed(() => { return y.value > 30; });
const isLoading: any = ref(false);
const isDisabled: any = ref(false);
const isLoadingVitalSign: any = ref(false);
const dataTTD: any = ref([]);
const d_Perawat: any = ref([]);
const d_Pegawai: any = ref([]);
const d_Ruangan: any = ref([]);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: "",
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
});
const COLLECTION: any = ref("FormulirKriteriaMasukICU"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({});
const setView = () => {
  useHead({ title: props.FORM_NAME + " - " + import.meta.env.VITE_PROJECT, });
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
  useViewWrapper().setFullWidth(true);
};

const loadRiwayat = async () => {
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      H.tandaTangan().set("TTDPegawai", response[0]['TTDPegawai'])
    } else {
      input.value.tanggal = new Date()
    }
  })
}

const print = async () => {
  H.printBlade(`emr/cetak-formulir-kriteria-masuk-icu?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
};

const simpan = () => {
  let ID = input.value.id ? input.value.id : "";
  let object: any = {};

  object = input.value;
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  object['TTDPegawai'] = H.tandaTangan().get("TTDPegawai");
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
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false;
  });
};

const setAutoFill = () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  input.value.tglPembuatan = new Date();
};

const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
    d_Pegawai.value = response
  })
}

setView();
loadRiwayat();
setAutoFill();
</script>
