<style lang="scss"></style>
<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
          </div>
        </div>
      </div>
      <div class="columns is-multiline p-2">
        <div class="column is-4">
          <h1 style="font-weight: bold;">Nama Ruangan</h1>
          <VControl class="prime-auto">
            <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" class="mt-1" />
          </VControl>
        </div>
        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="column is-12">
          <table class="tg" style="width: 100%">
            <thead>
              <tr>
                <th class="th-pri" style="text-align: center;" width="50%">Kriteria Fisiologis</th>
                <th class="th-pri" style="text-align: center;" width="25%">YA</th>
                <th class="th-pri" style="text-align: center;" width="25%">TIDAK</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(items, index) in kriteria " :key="index">
                <td class="td-pri">{{ items.label }}</td>
                <td class="td-pri">
                  <VControl>
                    <VCheckbox v-model="input[items.model]" class="pt-1 pb-1" true-value="YA" label="YA" color="primary"
                      square />
                  </VControl>
                </td>
                <td class="td-pri">
                  <VControl>
                    <VCheckbox v-model="input[items.model]" class="pt-1 pb-1" true-value="TIDAK" label="TIDAK"
                      color="primary" square />
                  </VControl>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="column is-12">
          <h1 style="font-weight: bold">Kesimpulan</h1>
          <h1 style="font-weight: bold">Berdasarkan kondisi diatas, maka pasien memenuhi kriterika masuk ke Ruang Stroke Corner.</h1>
        </div>

        <div class="columns is-multiline column is-12">
          <div class="column is-4">
            <h1 style="font-weight: bold;margin-bottom: 5px;">Alat Transport yang dibutuhkan:</h1>
            <div class="columns is-multiline">
              <div class="column is-6">
                <VControl>
                  <VCheckbox class="p-0" v-model="input.alatTransport" label="Brancard" color="primary"
                    true-value="Brancard" circle />
                </VControl>
              </div>
              <div class="column is-6">
                <VControl>
                  <VCheckbox class="p-0" v-model="input.alatTransport" label="Kursi Roda" color="primary"
                    true-value="Kursi Roda" circle />
                </VControl>
              </div>
            </div>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;margin-bottom: 5px;">Pendamping selama transport:</h1>
            <div class="columns is-multiline">
              <div class="column is-6">
                <VControl>
                  <VCheckbox class="p-0" v-model="input.pendampingSaatTransfer1" label="Dokter" color="primary"
                    true-value="Dokter" circle />
                </VControl>
              </div>
              <div class="column is-6">
                <VControl>
                  <VCheckbox class="p-0" v-model="input.pendampingSaatTransfer2" label="Perawat" color="primary"
                    true-value="Perawat" circle />
                </VControl>
              </div>
              <div class="column is-6">
                <VControl>
                  <VCheckbox class="p-0" v-model="input.pendampingSaatTransfer3" label="Asisten Perawat" color="primary"
                    true-value="Asisten Perawat" circle />
                </VControl>
              </div>
            </div>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;margin-bottom: 5px;">Alat Medis yang diperlukan selama transport:</h1>
            <div class="columns is-multiline">
              <div class="column is-6">
                <VControl>
                  <VCheckbox class="p-0" v-model="input.alatMedisOption" label="Ya, sebutkan" color="primary"
                    true-value="Ya" circle />
                </VControl>
              </div>
              <div class="column is-6">
                <VControl>
                  <VCheckbox class="p-0" v-model="input.alatMedisOption" label="Tidak" color="primary"
                    true-value="Tidak" circle />
                </VControl>
              </div>
              <div class="column is-12 pt-0" v-if="input.alatMedisOption == 'Ya'">
                <VControl>
                  <VInput v-model="input.alatMedis" type="text" placeholder="Alat Medis" class="w-full" />
                </VControl>
              </div>
            </div>
          </div>
        </div>

        <div class="column is-6" style="margin-left: auto;display: flex;justify-content: center;">
          <div class="column is-5 p-0">
            <h1 style="font-weight: bold">Garut</h1>
            <VDatePicker v-model="input.tanggal" mode="datetime" style="width: 100%" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal dan Jam" v-on="inputEvents" />
                </VControl>
              </template>
            </VDatePicker>
          </div>
        </div>

        <div class="column is-12 is-flex">
          <div class="column is-6" style="text-align: center">
            <h1 style="font-weight: bold">Dokter DPJP</h1>
            <TandaTangan :elemenID="'TTDdokterDPJP'" :width="'150'" :height="'150'" class="dek" />
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dokterDPJP" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ..."
                  class="mt-2 is-3" />
              </VControl>
            </VField>
          </div>

          <div class="column is-6" style="text-align: center">
            <h1 style="font-weight: bold">Dokter Pemeriksa</h1>
            <TandaTangan :elemenID="'TTDdokterPemeriksa'" :width="'150'" :height="'150'" class="dek" />
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ..."
                  class="mt-2 is-3" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from "@vueuse/core";
import { useApi } from "/@src/composable/useApi";
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from "@vueuse/head";
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from "/@src/utils/appHelper";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import AutoComplete from "primevue/autocomplete";
import MultiSelect from "primevue/multiselect";
import * as EMR from "../page-emr-plugins/kriteria-masuk-stroke-corner";
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
    FORM_NAME: null,
    FORM_URL: null,
  }
);
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
const item: any = reactive({});
const COLLECTION: any = ref("FormulirKriteriaMasukStrokeCorner"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggal: new Date(),
});
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
      dataTTD.value = response[0]
      H.tandaTangan().set("TTDdokterDPJP", dataTTD.value.TTDdokterDPJP)
      H.tandaTangan().set("TTDdokterPemeriksa", dataTTD.value.TTDdokterPemeriksa)
    } else {
      setAutoFill();
    }
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : "";
  let object: any = {};

  object = input.value;
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  object['TTDdokterDPJP'] = H.tandaTangan().get("TTDdokterDPJP");
  object['TTDdokterPemeriksa'] = H.tandaTangan().get("TTDdokterPemeriksa");
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
  }).catch((e: any) => {
    isLoading.value = false;
  });
};

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  input.value.tglPembuatan = new Date();
};

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
    d_Dokter.value = response
  })
}
const fetchRuangan = async (filter: any) => {
  await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`).then((response) => {
    d_Ruangan.value = response
  })
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await setView();
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
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
</script>