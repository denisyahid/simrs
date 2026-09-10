<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Surat Keterangan Gawat Darurat</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-12 pb-0">
            <h1>Yang bertanda tangan dibawah ini:</h1>
          </div>
          <div class="column is-6">
            <h1 style="font-weight: bold">NAMA:</h1>
            <VControl>
              <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" />
            </VControl>
          </div>

          <div class="column is-6">
            <h1 style="font-weight: bold">Jabatan:</h1>
            <VControl>
              <VInput v-model="input.jabatanBertandaTangan" class="input" type="text" />
            </VControl>
          </div>

          <div class="column is-12 pb-0">
            <h1>Yang bertanda tangan dibawah ini:</h1>
          </div>

          <div class="column is-4">
            <h1 style="font-weight: bold">NAMA PASIEN:</h1>
            <VControl>
              <VInput v-model="input.namaPasien" class="input" type="text" />
            </VControl>
          </div>

          <div class="column is-4">
            <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
            <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"/>
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>

          <div class="column is-4 columns is-multiline" style="display: flex">
            <div class="column is-12 pb-0">
              <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
            </div>
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label" :label="items.label"
                  color="primary" circle />
              </VControl>
            </VField>
          </div>

          <div class="column is-4">
            <VField>
              <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              <VControl>
                <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" />
              </VControl>
            </VField>
          </div>

          <div class="column is-4">
            <h1 style="font-weight: bold">Tindakan:</h1>
            <div class="column is-12 is-flex">
              <VField>
                <VControl>
                  <VCheckbox v-model="input.tindakan" label="Perlu" class="pt-1 pb-1" true-value="Perlu" false-value=""
                    color="primary" />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VCheckbox v-model="input.tindakan" label="Tidak Perlu" class="pt-1 pb-1" true-value="Tidak Perlu"
                    false-value="" color="primary" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-4">
            <h1 style="font-weight: bold">Diagnosa:</h1>
            <VControl>
              <VTextarea v-model="input.diagnosa" class="input" type="text" rows="1" />
            </VControl>
          </div>

          <div class="column is-12 is-flex">
            <div class="columns is-multiline">
              <div class="column is-6">
                <h1>Memang benar telah mendapat penanganan <span style="font-weight: bold">GAWAT DARURAT
                    (EMERGENCY)</span>
                  dan
                  <span style="font-weight: bold">{{ input.tindakan }}</span> untuk
                </h1>
              </div>
              <div class="column is-3">
                <VField>
                    <VTextarea rows="2" cols="16" v-model="input.untuk"></VTextarea>
                </VField>
              </div>
              <div class="column is-3">
                <span>
                  Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
                </span>
              </div>
            </div>
          </div>

          <div class="column is-8"></div>
          <div class="column is-4">
            <VField label="Garut">
              <VDatePicker v-model="input.tanggal" mode="date" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </VField>
            <div class="column pt-0" style="text-align:center;">
              <!-- <h1>Tanda Tangan</h1> -->
              <!-- <TandaTangan :elemenID="'TTDdokter'" :width="'150'" :height="'150'" class="dek" /> -->
              <VControl class="prime-auto">
                <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
              </VControl>
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
import { useRoute, onBeforeRouteLeave } from "vue-router";
import { useHead } from "@vueuse/head";
import * as H from "/@src/utils/appHelper";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import AutoComplete from "primevue/autocomplete";
import * as EMR from "../page-emr-plugins/lembaran-penyiaran-radioterapi";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

useHead({
  title: "Surat Keterangan Gawat Darurat" + " - " + import.meta.env.VITE_PROJECT,
});
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
useViewWrapper().setFullWidth(true);

let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;

let jenisTindakanRadioterapi = ref(EMR.jenisTindakanRadioterapi());
let JenisKelamin = ref(EMR.JenisKelamin());
let energy = ref(EMR.energy());
let accessories: any = ref(EMR.accessories());
let posisiMeja: any = ref(EMR.formField());
const user = useUserSession().getUser().pegawai;
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
const route = useRoute()
const RiwayatPsikososial: any = ref([
  { label: "Baik", value: "Baik" },
  { label: "Tidak Baik", value: "Tidak Baik" },
]);

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
const COLLECTION: any = ref("SuratKeteranganGawatDarurat"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggal: new Date(),
  jamKunjungan: new Date(),
  jamSelesai: new Date(),
  untuk: "Rawat inap"
});

const loadRiwayat = async () => {
  const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    if (!input.value.id) {
      input.value.id = response[0].id;
    }
    dataTTD.value = response[0]
    // H.tandaTangan().set("TTDdokter", dataTTD.value.TTDdokter)
    H.tandaTangan().set("TTDpasien", dataTTD.value.TTDpasien)
  } else {
    setAutoFill();
  }
}

const simpan = () => {
  if (input.value.tindakan == null) {
    H.alert('info', 'Tindakan harus diisi')
    return
  }
  if(input.value.tindakan == 'Perlu'){
    if (!input.value.untuk) {
      H.alert('info', 'Keterangan tindakan harus diisi')
      return
    }
  }
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
    name_form: 'Surat Keterangan Gawat Darurat',
    jenis_emr: "asesmen_medis",
    data: object,
  };
  isLoading.value = true;
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false;
    NOREC_EMRPASIEN.value = response.norec_emr;
    sudahDisimpan.value = true
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false;
  });
};

const kembaliKeun = () => {
  window.history.back();
};

const setAutoFill = () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.alamatLengkap = props.pasien.alamatlengkap;
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  input.value.DDDokter = props.registrasi.dokter;
  input.value.tglPembuatan = new Date();
  input.value.jabatanBertandaTangan = 'Dokter Umum'
};

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => {
    d_Dokter.value = response;
  });
};

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
}
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

loadRiwayat();
</script>
