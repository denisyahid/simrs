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
              @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideCetak></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-4">
            <h1 style="font-weight: bold">Nama Pasien</h1>
            <VControl>
              <VInput v-model="input.namaPasien" class="input" type="text" disabled />
            </VControl>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold">Tanggal Lahir Pasien</h1>
            <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" disabled />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;">Jenis Kelamin</h1>
            <div style="display: flex;">
              <VField v-for="items in JenisKelamin" :key="items.value">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1 " :true-value="items.label"
                    :label="items.label" color="primary" circle disabled />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold;">No. Rekam Medis</h1>
            <VControl>
              <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
            </VControl>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold;">Tanggal Kunjungan</h1>
            <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()" disabled>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" disabled />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>
          <div class="is-12 is-flex p-3">
            <div class="columns is-multiline">
              <div class="column is-3 is-mulitiline" v-for="items in kebutuhanPasien" :key="items.value">
                <h1 style="font-weight: bold">
                  {{ items.label }}
                </h1>
                <VField>
                  <VInput type="text" class="input" :placeholder="items.label" v-model="input[items.value]" />
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12">
            <BerkasSkemaPenyinaran :pasien="props.pasien" :registrasi="props.registrasi"></BerkasSkemaPenyinaran>
          </div>
        </div>

      </VCard>
    </div>
  </div>


  <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalTemplate = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                  <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                  <td class="tg-0lax text-center" width="20%">No EMR</td>
                  <td class="tg-0lax text-center" width="20%">Dokter</td>
                  <td class="tg-0lax text-center" width="20%">Section</td>
                  <td class="tg-0lax text-center" width="5%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:5%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
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
                  <td class="tg-0lax text-center" width="15%">No</td>
                  <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                  <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                  <td class="tg-0lax text-center" width="50%">Nama Template</td>
                  <td class="tg-0lax text-center" width="15%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplateFix">
                <tr>
                  <td style="width:15%;text-align:center">
                    <span class="mb-2">{{ resep.no }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:50%;text-align:center">
                    <span class="mb-2">{{ resep.namatemplate }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
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
import { h, reactive, ref, computed, watch } from "vue";
import { useRoute } from "vue-router";
import { useHead } from "@vueuse/head";
import * as H from "/@src/utils/appHelper";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import AutoComplete from "primevue/autocomplete";
import MultiSelect from "primevue/multiselect";
import * as EMR from "../page-emr-plugins/skema-penyinaran-radioterapi";
import FileUpload from "primevue/fileupload";
import BerkasPasienView from './berkas-pasien-preview.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import BerkasSkemaPenyinaran from './berkas-skema-penyinaran.vue'

useHead({
  title: 'Formulir Skema Penyinaran Radioterapi - ' + import.meta.env.VITE_PROJECT,
})

let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;

let JenisKelamin = ref(EMR.JenisKelamin());
let kebutuhanPasien = ref(EMR.kebutuhanPasien());

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

const { y } = useWindowScroll();
const isStuck = computed(() => {
  return y.value > 30;
});
const isLoading: any = ref(false);
const isDisabled: any = ref(false);
const isLoadingVitalSign: any = ref(false);
const dataTTD: any = ref([]);
const d_Perawat: any = ref([]);
const filePasien: any = ref();
const dataSource: any = ref([]);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: "",
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
});
const COLLECTION: any = ref("FormulirSkemaPenyinaranRadioterapi"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggalKunjunganPasien: new Date(),
});
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)



const setView = () => {
  useHead({
    title: props.FORM_NAME + " - " + import.meta.env.VITE_PROJECT,
  });
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
  useViewWrapper().setFullWidth(true);
};

const loadRiwayat = async () => {
  isLoading.value = true;
  useApi().get(`/emr/get-skema-penyinaran?nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}`).then((response: any) => {
    dataSource.value = response.data;
  });
  isLoading.value = false;
};

const loadRiwayatEMR = async () => {
  isLoading.value = true;
  const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }
  isLoading.value = false;
};

const add = () => {
  filePasien.value = null;
  input.value = {};
};

const onSelect = async (filez: any) => {
  const file = filez.files[0];
  if (file.size > 10000000) {
    H.alert("error", "Maksimal file size adalah 10 MB");
    return;
  }

  if (file.type != "application/pdf" && file.type.indexOf("image/") > -1 == false) {
    H.alert("error", "File yang diizinkan dalam bentuk format PDF/Image");
    return;
  }
  filePasien.value = file;
  console.log("File to be uploaded:", filePasien.value);
};

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
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
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
    loadRiwayatEMR()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const simpanBerkas = async () => {
  if (!filePasien.value) {
    H.alert("error", "File harus diunggah");
    return;
  }

  try {
    const formData = new FormData();
    // formData.append("filePasien", filePasien.value);
    formData.append("norec", input.value.norec || "");
    formData.append("noregistrasi", props.registrasi.noregistrasi);
    formData.append("nocm", props.pasien.nocm);
    formData.append("norec_apd", props.registrasi.norec_apd);
    formData.append("PosisiPasien", input.value.PosisiPasien || null);
    formData.append("PeyanggaKepala", input.value.PeyanggaKepala || null);
    formData.append("PenyanggaBadan", input.value.PenyanggaBadan || null);
    formData.append("PeyanggaTangan", input.value.PeyanggaTangan || null);
    formData.append("Aksesoris", input.value.Aksesoris || null);
    formData.append("Lainnya", input.value.Lainnya || null);

    formData.forEach((value, key) => {
      console.log(key + ":", value);
    });

    isLoading.value = true;
    await useApi().post("/emr/simpan-skema-penyinaran", formData);
    loadRiwayat();
  } catch (error) {
    console.error("Error saving data:", error);
    H.alert("error", "Gagal menyimpan data. Silakan coba lagi.");
  } finally {
    isLoading.value = false;
  }
};


const simpanTemplate = () => {
  if(!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
  let ID = input.id ? input.id : ''

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

const addTemplate = (response: any) => {
  console.log(response)
  input.value = response //set ke inputan
  input.value.namatemplate = null
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

const lihat = async (e: any) => {
  H.openFile('berkaspasien/00000' + e.nocm + '/' + e.namafile);
}

const hapus = async (e: any) => {
  e.loadingHapus = true

  await useApi().post(
    `/emr/hapus-skema-penyinaran`, {
    'norec': e.norec,
  }).then((response: any) => {
    e.loadingHapus = false
    loadRiwayat()
  })
}

const kembaliKeun = () => {
  window.history.back();
};

const print = async () => {
  H.printBlade(
    `emr/cetak-formulir-skema-penyinaran-radioterapi?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
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

setView();
loadRiwayat();
loadRiwayatEMR()
setAutoFill();
</script>
