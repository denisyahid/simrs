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
          <div class="column is-12 is-flex">
            <div class="column is-12">
              <h1>Saya yang bertanda-tangan dibawah ini :</h1>
            </div>
          </div>

          <div class="column is-12 is-flex ml-5">
            <div class="column is-2">
              <h1 style="font-weight: bold">NAMA:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <AutoComplete v-model="input.CBDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex ml-5">
            <div class="column is-2">
              <h1 style="font-weight: bold">SPESIALIS:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.spesialisBertandaTangan" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex ml-5">
            <div class="column is-2">
              <h1 style="font-weight: bold">Jabatan:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.jabatanBertandaTangan" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-12">
              <h1>Menerangkan bahwa penderita :</h1>
            </div>
          </div>

          <div class="column is-12 is-flex ml-5">
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
                        <VInput :value="inputValue" placeholder="Tanggal" disabled />
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
                    :label="items.label" color="primary" disabled circle />
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
                  <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex ml-5">
            <div class="column is-2">
              <h1 style="font-weight: bold">Alamat:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.alamatLengkap" class="input" type="text" disabled />
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
              <h1 style="font-weight: bold">Tanggal Input:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VDatePicker v-model="input.tanggalInput" mode="date" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-12">
              <h1>Memang benar membutuhkan pemasangan Ventilator :</h1>
            </div>
          </div>

          <div class="column is-12  ml-5" v-for="(items, index) in input.detailPemasangan" :key="index">
            <div class="column is-12 is-flex">
              <VButton class="button is-danger" @click="deleteItem(index)" v-if="index > 0">
                <span class="icon is-small">
                  <i class="fas fa-trash"></i>
                </span>
              </VButton>
              <VButton class="button is-info" @click="addNewItem">
                <span class="icon is-small">
                  <i class="fas fa-plus"></i>
                </span>
              </VButton>
            </div>
            <div class="column is-12 is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Device</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="items.device" placeholder="Device" class="input" type="text" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Mulai Pemasangan:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="items.mulaiPemasangan" mode="datetime" trim-weeks is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Akhir Pemasangan :</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="items.akhirPemasangan" mode="datetime" trim-weeks is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12 is-flex ml-5">
            <div class="column is-2">
              <h1 style="font-weight: bold">Alasan Pemasangan:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VTextarea rows="5" v-model="input.alasanPemasangan" class="input" type="text" />
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
import { useRoute, onBeforeRouteLeave } from "vue-router";
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
const route = useRoute();
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
const COLLECTION: any = ref("FormulirPenggunaanVentilator"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggal: new Date(),
  jamKunjungan: new Date(),
  jamSelesai: new Date(),
  detailPemasangan: [{
    no: 1
  }]
});
const addNewItem = () => {
  input.value.detailPemasangan.push({
    no: input.value.detailPemasangan[input.value.detailPemasangan.length - 1].no + 1,
  });
}
const deleteItem = (index) => {
  input.value.detailPemasangan.splice(index, 1)
}
const setView = () => {
  useHead({
    title: props.FORM_NAME + " - " + import.meta.env.VITE_PROJECT,
  });
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
  useViewWrapper().setFullWidth(true);
};

const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      H.tandaTangan().set("TTDdokter", dataTTD.value.TTDdokter)
      H.tandaTangan().set("TTDpasien", dataTTD.value.TTDpasien)
    } else {
      input.value.keterangan = "Single Use Dialiser"
    }
  })
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
  object['TTDpasien'] = H.tandaTangan().get("TTDpasien");

  if (object['TTDpasien'] == imgDefault) {
    H.alert('warning', "Tanda tangan pasien wajib diisi!")
    return;
  }

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
      sudahDisimpan.value = true
      loadRiwayat();
    })
    .catch((e: any) => {
      isLoading.value = false;
    });
};

const kembaliKeun = () => {
  window.history.back();
};
console.log(props.registrasi);

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&noregistrasi=${props.registrasi.noregistrasi}&pdf=true`)
};

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.alamatLengkap = props.pasien.alamatlengkap;
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  input.value.CBDokter = props.registrasi.dokter;
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

let sudahDisimpan = ref(false)
onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
        if (!sudahDisimpan.value) {
            console.log("DISIMPAN",sudahDisimpan.value);

            const konfirmasi = H.alert('warning', 'Belum Disimpan!!!');
            if (!konfirmasi) {
            return next(false);
            }
        }

    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});

setView();
getDataExist();
loadRiwayat();
setAutoFill();
fetchDokter({ query: "" });
var imgDefault = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII='
</script>
