<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top: 15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Monitoring dan Evaluasi Gizi</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST isHideCetak></ButtonEmr>
            </div>
          </div>
        </div>

        <div class="column is-12">
          <div class="column p-0">
            <div class="column p-0" style="overflow: auto;">
              <table class="table-rpo" style="width: 120%">
                <thead>
                  <tr>
                    <th rowspan="2" class="th-rpo">#</th>
                    <th rowspan="2" class="th-rpo">Tanggal/Jam</th>
                    <th rowspan="2" class="th-rpo" style="width: 25%">Terapi Gizi</th>
                    <th colspan="4" class="th-rpo">Monitoring</th>
                    <th rowspan="2" class="th-rpo">Evaluasi Tindak Lanjut</th>
                    <!-- <th rowspan="2" class="th-rpo">#</th> -->
                  </tr>
                  <tr>
                    <th class="th-rpo">BB (kg) / Lingkar Lengan Atas (cm)</th>
                    <th class="th-rpo">Biokimia</th>
                    <th class="th-rpo" style="width: 23%">
                      Klinis** (Terkait terapi gizi)
                    </th>
                    <th class="th-rpo">Dietary Asupan Makan (%)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="td-rpo"></td>
                    <td class="td-rpo" style="text-align: center">
                      <VDatePicker v-model="input.tgltindakan" mode="dateTime" style="width: 100%">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </td>
                    <td class="td-rpo">
                      <div class="is-12">
                        <h1 style="font-weight: bold">Diet Awal:</h1>
                        <div class="is-flex">
                          <VField>
                            <VControl>
                              <VCheckbox class="p-2" color="primary" v-model="input.dietAwal" label="Oral"
                                true-value="Oral" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl>
                              <VCheckbox class="p-2" color="primary" v-model="input.dietAwal" label="NGT"
                                true-value="NGT" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl>
                              <VCheckbox class="p-2" color="primary" v-model="input.dietAwal" label="Puasa"
                                true-value="Puasa" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="is-flex">
                          <VField>
                            <VControl>
                              <VCheckbox class="p-2" color="primary" v-model="input.dietAwal" label="Parenteral"
                                true-value="Parenteral" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl>
                              <VInput v-model="input.keteranganParanetral" placeholder="Keterangan" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                      <div class="is-12">
                        <h1 style="font-weight: bold">Diet Awal:</h1>
                        <div class="is-flex">
                          <div class="column">
                            <h1>Energi :</h1>
                          </div>
                          <VField addons>
                            <VControl>
                              <VInput v-model="input.energi" placeholder="Energi" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>kkal</VButton>
                            </VControl>
                          </VField>
                        </div>

                        <div class="is-flex">
                          <div class="column">
                            <h1>Protein :</h1>
                          </div>
                          <VField addons>
                            <VControl>
                              <VInput v-model="input.protein" placeholder="Protein" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>gram</VButton>
                            </VControl>
                          </VField>
                        </div>

                        <div class="is-flex">
                          <div class="column pl-0">
                            <h1 style="font-weight: bold;">Lainnya</h1>
                            <VField>
                              <VTextarea rows="2" v-model="input.LainnyaDietAwal"></VTextarea>
                            </VField>
                          </div>
                        </div>
                      </div>
                    </td>

                    <td class="td-rpo">
                      <VField>
                        <VControl>
                          <VInput v-model="input.bbLingkarLengan" placeholder="BB/Lingkar Lengan Atas" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-rpo">
                      <VField>
                        <VControl>
                          <VInput v-model="input.biokimia" placeholder="Biokimia" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-rpo">
                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="input.Mual" label="Mual" true-value="Mual" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="input.Muntah" label="Muntah" true-value="Muntah" />
                          </VControl>
                        </VField>
                      </div>

                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="input.Diare" label="Diare" true-value="Diare" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="input.Konstipasi" label="Konstipasi"
                              true-value="Konstipasi" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="input.KesulitanMengunyah" label="Kesulitan menguyah "
                              true-value="Kesulitan menguyah" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="input.kesulitanMenelan" label="Kesulitan menelan "
                              true-value="Kesulitan menelan" />
                          </VControl>
                        </VField>
                      </div>

                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="input.Hipertensi" label="Hipertensi"
                              true-value="Hipertensi" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="input.Odema" label="Odema/Osites"
                              true-value="Odema/Osites" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="input.lain" true-value="Lain" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VInput v-model="input.lainnya" placeholder="Lainnya" />
                          </VControl>
                        </VField>
                      </div>
                    </td>

                    <td class="td-rpo">
                      <VField>
                        <VControl>
                          <VInput v-model="input.dietary" placeholder="Dietary" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-rpo">
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.evaluasi" rows="3" placeholder="Evaluasi Tindak Lanjut" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr v-for="(item, index) in input.details" :key="index + 2">
                    <td style="vertical-align: inherit" class="td-rpo">
                      <div class="column">
                        <VButtons style="justify-content:space-around">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                            color="info" v-tooltip.bubble="'Tambah '">
                          </VIconButton>
                          <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                            @click="removeItem(index)" color="danger">
                          </VIconButton>
                        </VButtons>
                      </div>
                    </td>
                    <td class="td-rpo" style="text-align: center">
                      <VDatePicker mode="dateTime" style="width: 100%" v-model="item[`tanggal`]">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </td>
                    <td class="td-rpo">
                      <div class="is-12">
                        <div class="is-flex">
                          <VField>
                            <VControl>
                              <VCheckbox class="p-2" color="primary" v-model="item[`dietTetap`]" label="Diet Tetap"
                                true-value="Diet Tetap" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="is-flex">
                          <VField>
                            <VControl>
                              <VCheckbox class="p-2" color="primary" v-model="item[`perubahanDiet`]"
                                label="Perubahan Diet" true-value="Perubahan Diet" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="is-flex">
                          <VField>
                            <VControl>
                              <VInput v-model="item[`keteranganDiet`]" placeholder="Keterangan" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </td>

                    <td class="td-rpo">
                      <VField>
                        <VControl>
                          <VInput v-model="item[`bbLingkarLengan`]" placeholder="BB/Lingkar Lengan Atas" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-rpo">
                      <VField>
                        <VControl>
                          <VInput v-model="item[`biokimia`]" placeholder="Biokimia" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-rpo">
                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="item[`Mual`]" label="Mual" true-value="Mual" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="item[`Muntah`]" label="Muntah" true-value="Muntah" />
                          </VControl>
                        </VField>
                      </div>

                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="item[`Diare`]" label="Diare" true-value="Diare" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="item[`Konstipasi`]" label="Konstipasi"
                              true-value="Konstipasi" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="item[`KesulitanMengunyah`]" label="Kesulitan menguyah"
                              true-value="Kesulitan menguyah" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="item[`kesulitanMenelan`]" label="Kesulitan menelan"
                              true-value="Kesulitan menelan" />
                          </VControl>
                        </VField>
                      </div>

                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="item[`Hipertensi`]" label="Hipertensi"
                              true-value="Hipertensi" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="item[`Odema`]" label="Odema/Osites"
                              true-value="Odema/Osites" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="is-flex">
                        <VField>
                          <VControl>
                            <VCheckbox class="p-2" v-model="item[`lain`]" true-value="Lain" />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VInput v-model="item[`lainnya`]" placeholder="Lainnya" />
                          </VControl>
                        </VField>
                      </div>
                    </td>

                    <td class="td-rpo">
                      <VField>
                        <VControl>
                          <VInput v-model="item[`dietary`]" placeholder="Dietary" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-rpo">
                      <VField>
                        <VControl>
                          <VTextarea v-model="item[`evaluasi`]" rows="3" placeholder="Evaluasi Tindak Lanjut" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="columns m-0">
              <div class="column is-4" style="margin-left: auto;text-align: center;">
                <h1 style="font-weight: bold">Garut</h1>
                <VDatePicker v-model="input.tanggal" mode="dateTime" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
                <TandaTangan :elemenID="'TTDAhliGizi'" :width="'150'" :height="'150'" class="dek mt-2" />
                <AutoComplete v-model="input.ahliGizi" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  class="mt-2" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
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
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick } from "vue";
import { useRoute, useRouter, onBeforeRouteLeave } from "vue-router";
import { useHead } from "@vueuse/head";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import ButtonEmr from "../page-emr-plugins/button-emr.vue";
import * as H from "/@src/utils/appHelper";
import AutoComplete from "primevue/autocomplete";
import Fieldset from "primevue/fieldset";
import * as EMR from "../page-emr-plugins/asesmen-awal-keper-rj";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";

useHead({
  title: "Monitoring dan Evaluasi Gizi - " + import.meta.env.VITE_PROJECT,
});
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
useViewWrapper().setFullWidth(true);
let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;
let norec_emr = useRoute().query.norec_emr as string;
let JenisKelamin = ref(EMR.JenisKelamin());

const props = withDefaults(
  defineProps<{
    pasien?: any;
    registrasi?: any;
    FORM_NAME?: string;
    FORM_URL?: string;
    COLLECTION?: string;
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: "",
    FORM_URL: "",
    COLLECTION: "",
  }
);

const route = useRoute();
const pasien: any = ref({});
const d_pegawai: any = ref([]);
const d_Dokter: any = ref([]);
const loadData: any = ref(true);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date(),
    jam: new Date(),
  },
  airway: [],
  disability: [],
});

const COLLECTION: any = ref("MonitoringDanEvaluasiGizi"); //table mongodb
const user = useUserSession().getUser().pegawai;
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  details: [{
    no: 1,
    tanggal: new Date()
  }],
});
const { y } = useWindowScroll();
const isStuck = computed(() => {
  return y.value > 30;
});
const isLoading = ref(false);
const isAktive = ref();
const d_respon: any = ref([
  {
    value: 1,
    label:
      "Tidak respon sama sekali (tidak antusias dan keinginan belajar) (No Response (No Enthusiasm and Willingness to Learn))",
  },
  {
    value: 2,
    label:
      "Tidak paham (ingin belajar tapi kesulitan mengerti) (Not Understand (Want to Learn but have difficulty to understand))",
  },
  {
    value: 3,
    label:
      "Paham hal yang diajarkan tapi tidak bisa menjelaskan sendiri (Understand things that are thought but cannot explain it well)",
  },
  {
    value: 4,
    label:
      "Dapat menjelaskan apa yang telah diajarkan tapi harus dibantu educator (Able to explain the things which are taught but have to help by educator)",
  },
  {
    value: 5,
    label:
      "Dapat menjelaskan apa yang telah diajarkan tapi tanpa dibantu (Able to explain the things which are taught without helps)",
  },
]);
const d_hubungan: any = ref([
  { value: 1, label: "Anak" },
  { value: 2, label: "Orang Tua" },
  { value: 3, label: "Pasien Sendiri" },
  { value: 4, label: "Saudara Kandung" },
  { value: 5, label: "Suami/Istri" },
  { value: 6, label: "Teman" },
  { value: 7, label: "Lainnya" },
]);

const dataTTD: any = ref([]);
const d_Ruangan: any = ref([]);
const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
    tanggal: new Date(),
  });
};
const removeItem = (index: any) => {
  input.value.details.splice(index, 1);
};

const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
    H.tandaTangan().set("TTDAhliGizi", dataTTD.value.TTDAhliGizi)
  } else {
    let d = input.value
    d.ahliGizi = { label: user.namaLengkap, value: user.id }
    d.tanggal = new Date()
    d.tgltindakan = new Date()
  }
}
const simpan = () => {
  let ID = input.value.id ? input.value.id : "";
  let object: any = {};

  object = input.value;
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object["TTDAhliGizi"] = H.tandaTangan().get("TTDAhliGizi");

  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: "asesmen_medis",
    data: object,
  };

  isLoading.value = true
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false
  })
};

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then(response => {
    d_Dokter.value = response;
  });
};

onBeforeMount(async () => {
  try {
    await loadRiwayat();
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`);
    if (cache) input.value = cache;
    loadData.value = false;
  } catch (error) {
    console.error("Error mount cache TAB EMR:", error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name;
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value);
  } catch (error) {
    console.error("Error leave cache TAB EMR:", error);
  }
  next();
});
</script>

<style lang="scss">
.table-rpo {
  width: 100%;
  border: 1px solid;
}

.th-rpo,
.td-rpo {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.th-rpo {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
