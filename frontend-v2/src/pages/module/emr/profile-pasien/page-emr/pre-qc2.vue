<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top: 15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>{{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <div class="buttons">
                  <!-- <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                      Kembali
                  </VButton> -->
                  <VButton type="button" :loading="isLoading" rounded outlined color="info" raised icon="fas fa-history"
                      @click="riwayatcatatan()"> Riwayat Catatan
                  </VButton>
                  <VButton type="button" :loading="isLoading" rounded outlined color="warning" raised icon="lnir lnir-printer"
                      @click="print()"> Cetak
                  </VButton>
                  <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                      :loading="isLoading" @click="simpan()"> Simpan
                  </VButton>
                  <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                    @click="simpanTemplate()" v-if="!isHideST"> Simpan Template
                  </VButton>
              </div>
            </div>
          </div>
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

        <hr>

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
          <div class="column is-6">
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
          <div class="column is-6">
            <h1 style="font-weight: bold;">Pesawat:</h1>
            <VField>
              <VControl>
                <VInput v-model="input.pesawat" class="input is-rounded" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column" style="overflow: auto;">
          <table class="table-rpo">
            <thead>
              <tr>
                <th class="th-rpo">Item yang harus sudah lengkap dalam 1-5 kali</th>
                <th class="th-rpo">Checklist(✔)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="td-pri">
                  Status pre-qc 2 dari poliklinik sudah lengkap dan ditanda tangani oleh petugas dengan nama jelas
                </td>
                <td class="td-pri" style="text-align: center;">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.statusPreQc2" true-value="Ya" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="td-pri">
                  Data TPS/Blok/Bolus
                </td>
                <td class="td-pri" style="text-align: center;">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.dataTPS" true-value="Ya" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="td-pri">
                  QA(Max 2 kali radiasi)
                </td>
                <td class="td-pri" style="text-align: center;">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.qa" true-value="Ya" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="td-pri">
                  Verifikasi Sinar(Max 3 kali radiasi)
                </td>
                <td class="td-pri" style="text-align: center;">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.verifikasiSinar" true-value="Ya" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="td-pri">
                  Acc ronde besar
                </td>
                <td class="td-pri" style="text-align: center;">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.accRonde" true-value="Ya" />
                    </VControl>
                  </VField>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="is-12">
          <p>Catatan: bila salah satu data di atas belum dilengkapi, maka pasien tidak dapat dilanjutkan radiasinya dan di kembalikan ke poli klinik</p>
        </div>
        <div class="is-12 is-flex">
          <VField>
            <VControl>
              <VCheckbox v-model="input.keterangan" true-value="Lengkap" />
            </VControl>
          </VField>
          <p class="mt-4">Dengan ini petugas menyatakan bahwa data yang dimaksud <span style="font-weight: bold;">LENGKAP</span></p>
        </div>
      </div>
    </div>
  </div>

  <VModal :open="showModalRiwayatCatatan" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalRiwayatCatatan = false">
    <template #content>
      <DataTable :pt="{
        table: { style: 'min-width: 50rem; min-height: 10rem;' },
        column: {
          bodycell: ({ state }) => ({
            class: [{ 'pt-0 pb-0': state['d_editing'] }]
          })
        }
      }" v-model:filters="filtersTemplate" :value="listRiwayatCatatan" :metaKeySelection="false" :rows="10" paginator
        tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listRiwayatCatatan.length"
        :globalFilterFields="['namatemplate', 'registrasi.namaruangan', 'registrasi.noregistrasi']" responsiveLayout="stack" breakpoint="960px">
        <template #header>
          <div class="columns is-multiline">
              <div class="column is-8">
                  <VField>
                      <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Riwayat" />
                  </VField>
              </div>
          </div>
        </template>
        <template #empty> No customers found. </template>
        <template #loading>
          <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
          <p style="color:white">Loading data, please wait...</p>
        </template>
        <Column header="#" headerStyle="width: 3rem">
          <template #body="slotProps">
            <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayatTemplate(slotProps.data)"
              color="info" v-tooltip-prime.top="'Pilih'">
            </VIconButton>
          </template>
        </Column>
        <Column field="created_at" header="Tanggal" :sortable="true">
          <template #body="slotProps">
            <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
          </template>
        </Column>
        <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
          <template #body="slotProps">
            {{ slotProps.data.registrasi.namaruangan }}
          </template>
        </Column>
        <Column field="registrasi.noregistrasi" header="No Reg" :sortable="true">
          <template #body="slotProps">
            {{ slotProps.data.registrasi.noregistrasi }}
          </template>
        </Column>
        <Column field="user_input.namalengkap" header="Author" :sortable="true">
          <template #body="slotProps">
            {{ slotProps.data.user_input.namalengkap }}
          </template>
        </Column>
      </DataTable>
    </template>
  </VModal>

  <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
      @close="showModalTemplateFix = false">
      <template #content>
          <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                  <span style="font-size:9pt;font-weight:bold">List Template</span>
                  <div style="overflow-y:auto;" class="mt-1">
                      <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                          <thead>
                              <tr>
                                  <td class="tg-0lax text-center" width="5%">No</td>
                                  <td class="tg-0lax text-center" width="15%">Tanggal Dibuat</td>
                                  <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                                  <td class="tg-0lax text-center" width="25%">Nama Template</td>
                                  <td class="tg-0lax text-center" width="15%">#</td>
                              </tr>
                          </thead>
                          <tbody v-for="resep in listTemplateFix">
                              <tr>
                                  <td style="width:5%;text-align:center">
                                      <span class="mb-2">{{ resep.no }}</span><br>
                                  </td>
                                  <td style="width:15%;text-align:center">
                                      <span class="mb-2">{{ resep.created_at }}</span><br>
                                  </td>
                                  <td style="width:20%;text-align:center">
                                      <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                  </td>
                                  <td style="width:25%;text-align:center">
                                      <span class="mb-2">{{ resep.namatemplate }}</span><br>
                                  </td>
                                  <td style="width:15%;text-align:center">
                                      <VIconButton type="button" raised circle icon="fas fa-plus"
                                          @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                      </VIconButton>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
          </form>
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
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useToaster } from '/@src/composable/toaster'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';

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
const listRiwayatCatatan: any = ref([])
const showModalRiwayatCatatan: any = ref(false)
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

const COLLECTION: any = ref("PreQC2"); //table mongodb

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
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  );
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
  await useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      if (response.length) {
        input.value = response[0]; //set ke inputan
        if (NOREC_EMRPASIEN.value == "") {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk;
        }
        dataTTD.value = response[0];
      }
    });
  H.tandaTangan().set("TTDAhliGizi", dataTTD.value.TTDAhliGizi);
};
const simpan = () => {
  let ID = input.value.id ? input.value.id : "";

  let object: any = {};

  object = input.value;
  object.nocm = pasien.value.nocm;

  object.pasien = H.setObjectPasien(pasien.value);
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi);
  object["TTDAhliGizi"] = H.tandaTangan().get("TTDAhliGizi");

  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: "monitoring-dan-evaluasi-gizi",
    name_form: props.FORM_NAME,
    jenis_emr: "asesmen_medis",
    data: object,
  };
  console.log(json);

  isLoading.value = true;
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false;
      // NOREC_EMRPASIEN.value = response.norec_emr
    })
    .catch((e: any) => {
      isLoading.value = false;
    });

  // console.log(resultValue)
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

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
};

const addTemplate = (response: any) => {
  console.log(response)
  input.value = response //set ke inputan
  input.value.namatemplate = null

  showModalTemplateFix.value = false
  H.alert('success', 'Berhasil di tambahkan');
}

const addRiwayatTemplate = (response: any) => {
  console.log(response)
  input.value = response //set ke inputan
  input.value.namatemplate = null

  showModalRiwayatCatatan.value = false
  H.alert('success', 'Berhasil di tambahkan');
}

const riwayatcatatan = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      if (responselast.length) {
        listRiwayatCatatan.value = responselast //set ke inputan
        showModalRiwayatCatatan.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

const simpanTemplate = () => {
  if(!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
    let ID = input.id ? input.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
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

    useApi().post(
        `/emr/simpan-emr-template`, json).then((response: any) => {
            isLoading.value = false
            input.value.namatemplate = null
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const pilihTemplate = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
            isLoading.value = false
            if (responselast.length) {
                listTemplate.value = responselast //set ke inputan 
                showModalTemplate.value = true
            } else {
                H.alert('warning', 'Data tidak ada')
            }
        })
}


const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
        console.log(responselast)
        if (responselast.length) {
            for (var x = 0; x < responselast.length; x++) {
                responselast[x].no = x + 1
                responselast[x].id = ''
            }
            listTemplateFix.value = responselast //set ke inputan 
            showModalTemplateFix.value = true
        } else {
            H.alert('warning', 'Data tidak ada')
        }
    })
}

onMounted(() => {
  setAutoFill();
  getDataExist();
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
