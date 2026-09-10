<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top: 15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Perencanaan Radiasi Externa</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideST
                :isHideCetak="true">
              </ButtonEmr>
            </div>

          </div>
        </div>

        <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
          <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
            @click="simpanTemplate()"> Simpan Template
          </VButton>
          <VButton type="button" rounded outlined color="warning" raised icon="feather:folder" :loading="isLoading"
            @click="pilihTemplateFix(index)"> Pilih Template
          </VButton>
          <VButton type="button" rounded outlined color="info" raised icon="fas fa-history" :loading="isLoading"
            @click="pilihTemplate(index)"> Pilih Riwayat
          </VButton>
        </div>

        <div class="column is-12 p-0">
          <hr class="m-0">
        </div>

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
        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">Nama Pasien:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.namaPasien" class="input" disabled />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">No RM:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.norm" class="input" disabled />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
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

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
          </div>
          <div class="column is-10" style="display: flex">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label" :label="items.label"
                  color="primary" disabled circle />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="is-flex">
          <div class="column is-4">
            <h1 style="font-weight: bold">Tanggal:</h1>
            <VField>
              <VDatePicker v-model="input.tanggalAwal" mode="date" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput class="input is-rounded" :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;">Jam Kedatangan:</h1>
            <VField>
              <VDatePicker v-model="input.kebjamKedatangan" color="green" mode="time" is24hr>
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
          <div class="column is-4">
            <h1 style="font-weight: bold;">Jam Asesmen Awal:</h1>
            <VField>
              <VDatePicker v-model="input.kebjamAsesmenAwal" color="green" mode="time" is24hr>
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

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">Diagnosis dan Stadium Lengkap:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.diagnosisStadiumLengkap" class="input" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="columns">
          <div class="column is-12">
            <h1 style="font-weight: bold">Rencana Pengobatan / Tujuan Radiasi:</h1>
            <div class="columns is-multiline p-3">
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.rtDefinitif" label="RT Definitif" true-value="RT Definitif"
                      color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.kemoradiasi" label="Kemoradiasi" true-value="Kemoradiasi"
                      color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.preOperasi" label="Pre Operasi" true-value="Pre Operasi"
                      color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.postOperasi" label="Post Operasi" true-value="Post Operasi"
                      color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.paliatif" label="Paliatif" true-value="Paliatif" color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.lainLain" label="Lain-lain" true-value="Lain-lain" color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.reiradiasi" label="Reiradiasi" true-value="Reiradiasi" color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.postKemoterapi" label="Post Kemoterapi" true-value="Post Kemoterapi"
                      color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>

        <div class="columns">
          <div class="column is-12">
            <h1 style="font-weight: bold">Teknik Radiasi:</h1>
            <div class="columns is-multiline p-3">
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.threeD" label="3D" true-value="3D" color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.IMRT" label="IMRT" true-value="IMRT" color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.IGRT" label="IGRT" true-value="Pre Operasi" color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.srs" label="SRS" true-value="SRS" color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.SRT" label="SRT" true-value="SRT" color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.SBRT" label="SBRT" true-value="SBRT" color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-one-quarter">
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.VMAT" label="VMAT" true-value="VMAT" color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>

        <div class="is-flex p-0 is-12">
          <div class="column is-2">
            <h1 style="font-weight: bold">Target dan Dosis Radiasi:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.targetDanDosisRadiasi" class="input" placeholder="Target dan Dosis Radiasi" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column" style="overflow: auto;">
          <table class="table-rpo">
            <thead>
              <tr>
                <th class="th-rpo">Organ</th>
                <th class="th-rpo">Batasan Dosis</th>
                <th class="th-rpo">#</th>
              </tr>
            </thead>
            <tbody v-for="(item, index) in input.details" :key="index">
              <tr>
                <td class="td-rpo">
                  <VField>
                    <VControl>
                      <VInput v-model="item.organ" class="input" placeholder="Organ" />
                    </VControl>
                  </VField>
                </td>

                <td class="td-rpo">
                  <VField>
                    <VControl>
                      <VInput v-model="item.batasanDosis" class="input" placeholder="Batasan Dosis" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-rpo" style="vertical-align: inherit">
                  <div class="column">
                    <VButtons style="justify-content:space-around">
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                        v-tooltip.bubble="'Tambah '">
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
        </div>

        <div class="is-flex p-0 is-12">
          <div class="column is-2">
            <h1 style="font-weight: bold">Catatan:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.catatan" class="input" placeholder="Catatan" />
              </VControl>
            </VField>
          </div>
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
            <table style="border: 1px solid black;" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="5%">#</td>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="10%">Tanggal
                    Input</td>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="10%">Tanggal
                    Registrasi</td>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">Nama
                    Ruangan</td>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">No
                    Registrasi</td>
                  <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">Pegawai</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:5%;text-align:center;vertical-align: middle;border: 1px solid black;padding: 5px;">
                    <VIconButton type="button" raised circle icon="fas fa-search" @click="addRiwayat(resep)"
                      color="info" v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                  <td style="width:10%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:10%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center;vertical-align: middle;border: 1px solid black;">
                    <span class="mb-2">{{ resep.user_input.namalengkap }}</span><br>
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
            <div class="column is-4"></div>
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
              <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Pilih'">
              </VIconButton>
              <!-- <VIconButton type="button" raised circle icon="fas fa-pencil-alt" @click="editTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Edit'" v-if="!isAlltemplate">
              </VIconButton> -->
            </VButtons>
          </template>
        </Column>
        <Column field="namatemplate" header="Nama" :sortable="true"></Column>
        <!-- <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
              <template #body="slotProps">
                {{ slotProps.data.registrasi.namaruangan }}
              </template>
            </Column> -->
        <Column field="created_at" header="Tanggal" :sortable="true">
          <template #body="slotProps">
            <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
          </template>
        </Column>
      </DataTable>
    </template>
  </VModal>
</template>

<script setup lang="ts">
import { useWindowScroll } from "@vueuse/core";
import { useApi } from "/@src/composable/useApi";
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick, onMounted } from "vue";
import { useRoute, useRouter, onBeforeRouteLeave } from "vue-router";
import { useHead } from "@vueuse/head";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import ButtonEmr from "../page-emr-plugins/button-emr.vue";
import * as H from "/@src/utils/appHelper";
import AutoComplete from "primevue/autocomplete";
import Fieldset from "primevue/fieldset";
import * as EMR from "../page-emr-plugins/asesmen-awal-keper-rj";
import * as EMR2 from "../page-emr-plugins/asesmen-gizi-geriatri-rawat-jalan";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";
import { useToaster } from '/@src/composable/toaster'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

useHead({
  title: "Perencanaan Radiasi Eksterna - " + import.meta.env.VITE_PROJECT,
});
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
useViewWrapper().setFullWidth(true);
let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;
let norec_emr = useRoute().query.norec_emr as string;
let JenisKelamin = ref(EMR.JenisKelamin());
let ANTROPOMETRI: any = ref(EMR2.ANTROPOMETRI());
let BioKimias1: any = ref(EMR2.BioKimias1());
let BioKimias2: any = ref(EMR2.BioKimias2());
let KLINIS: any = ref(EMR2.KLINIS());
let DIAGNOSANUTRISI: any = ref(EMR2.DIAGNOSANUTRISI());
let KEBUTUHANNUTRISI: any = ref(EMR2.KEBUTUHANNUTRISI());
let TANDAVITAL: any = ref(EMR2.TANDAVITAL());
let MATA: any = ref(EMR2.MATA());
let THT: any = ref(EMR2.THT());
let PULMO: any = ref(EMR2.PULMO());
let Abdomen: any = ref(EMR2.Abdomen());
const idTemplate: any = ref('');
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});

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
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
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

const COLLECTION: any = ref("PerencaanRadiasiEksterna"); //table mongodb

const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggalJam: new Date(),
  kebjamKedatangan: new Date(),
  kebjamAsesmenAwal: new Date(),
  tanggalAwal: new Date(),
  details: [
    {
      no: 1,
    }
  ]
});
const { y } = useWindowScroll();
const isStuck = computed(() => {
  return y.value > 30;
});
const isLoading = ref(false);
const isAktive = ref();

const dataTTD: any = ref([]);
const d_Ruangan: any = ref([]);


const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`);
  d_Ruangan.value = response;
};

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
    nama: props.pasien.namapasien,
    tanggal: new Date(),
    tanggalJam: new Date(),
  });
};
const removeItem = (index: any) => {
  input.value.details.splice(index, 1);
};

const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0]; //set ke inputan
      if (NOREC_EMRPASIEN.value == "") {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
      dataTTD.value = response[0];
    }
  });
};

const simpan = () => {
  let ID = input.value.id ? input.value.id : "";
  let object: any = {};

  object = input.value;
  object.nocm = pasien.value.nocm;

  object.pasien = H.setObjectPasien(pasien.value);
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi);

  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }

  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }

  isLoading.value = true;
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false;
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false;
  });
};

const kembaliKeun = () => {
  window.history.back();
};
const fetchPasien = () => {
  pasien.value = props.pasien;
  pasien.value.registrasi = props.registrasi;
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : "";
  console.log(norec_emr);
};

const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then(response => {
      d_Dokter.value = response;
    });
};
const getDataExist = async () => {
  await useApi()
    .get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`)
    .then(response => {
      if (response != null || response != undefined) {
        input.value.beratbadanObgyn = response.beratBadan;
        input.value.tinggibadanObgyn = response.tinggiBadan;
        input.value.IMT = response.IMT;
        input.value.lingkarPerut = response.lingkarPerut;
        input.value.nadiObgyn = response.nadi;
        input.value.celciusObgyn = response.suhu;
        input.value.tekananDarahObgyn = response.tekananDarah;
        input.value.nafasObgyn = response.pernapasan;
        input.value.sao2Obgyn = response.SPO2;
      }
    });
};

const handlerRujukanChange = (val: any) => {
  console.log(val);
  if (val === "YA") {
  }
};

const print = async () => {
  H.printBlade(
    `emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  );
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

const simpanTemplate = () => {
  if (input.value.namatemplate == null) {
    H.alert('warning', 'Isi nama template terlebih dahulu!')
    return;
  }
  let ID = idTemplate.value ? idTemplate.value : ''
  let object: any = {}

  object = input.value
  object.nocm = props.pasien.nocm
  object.pasien = H.setObjectPasien(props.pasien)
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
    checkTemplate.value = false
    input.value.namatemplate = null
    delete object.namatemplate;
    delete object['_id'];
    delete object.nocm;
    delete object.pasien;
    delete object.regisstrasi;
    object.id = '';
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const addTemplate = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  input.value['id'] = ''
  setAutoFill();
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      for (var x = 0; x < responselast.length; x++) {
        responselast[x].no = x + 1
      }
      listTemplateFix.value = responselast //set ke inputan
      showModalTemplateFix.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
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

const addRiwayat = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(`/emr/hapus-template`, json).then((response: any) => {
    if (response.status !== 500) {
      isLoading.value = false;
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
  showModalTemplateFix.value = false;
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
};

onMounted(() => {
  setAutoFill();
  // getDataExist();
  fetchPasien();
})

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
