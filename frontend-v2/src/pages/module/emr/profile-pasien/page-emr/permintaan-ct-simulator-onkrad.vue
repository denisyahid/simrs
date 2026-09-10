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
              @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="column">
    <VCard>
      <div class="column is-12">
        <h1>Nama Template&emsp;&emsp;
          <span style="color:red">**Hanya diisi jika ingin membuat template</span>
        </h1>
        <VField>
          <VControl>
            <VTextarea v-model="input.namatemplate" rows="1">
            </VTextarea>
          </VControl>
        </VField>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
          @click="pilihTemplateFix(index)"> Pilih Template
        </VButton>
      </div>
      <div class="columns is-multiline column">
        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-2">
            <h1 style="font-weight: bold">NAMA PASIEN:</h1>
          </div>
          <div class="column pt-0 pl-0 is-10">
            <VField>
              <VControl>
                <VInput v-model="input.namaPasien" class="input" type="text" disabled />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-2">
            <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
          </div>
          <div class="column pt-0 pl-0 is-10">
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

        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-2">
            <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
          </div>
          <div class="column pt-0 pl-0 is-10" style="display: flex">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label" :label="items.label"
                  color="primary" disabled circle />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-2">
            <h1 style="font-weight: bold">No. Rekam Medis:</h1>
          </div>
          <div class="column pt-0 pl-0 is-10">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-2">
            <h1 style="font-weight: bold">Alamat:</h1>
          </div>
          <div class="column pt-0 pl-0 is-10">
            <VField>
              <VControl>
                <VInput v-model="input.alamatLengkap" class="input" type="text" disabled />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-2">
            <h1 style="font-weight: bold">NO TELEFON:</h1>
          </div>
          <div class="column pt-0 pl-0 is-10">
            <VField>
              <VControl>
                <VInput v-model="input.noTelfon" class="input" type="text" disabled />
              </VControl>
            </VField>
          </div>
        </div>

      </div>

      <div class="column is-12 columns is-multiline">
        <div class="column pt-0 pl-0 is-6 is-flex">
          <div class="column pt-0 pl-0 is-12">
            <VField label="Tanggal Permintaan">
              <VDatePicker v-model="input.tanggalPermintaan" mode="date" trim-weeks :max-date="new Date()">
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
        </div>

        <div class="column pt-0 pl-0 is-6 is-flex">
          <div class="column pt-0 pl-0 is-12">
            <VField label="Nama Poli">
              <VControl>
                <AutoComplete v-model="input.kontrolKembaliKe" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column pt-0 pl-0 is-6 is-flex">
          <div class="column pt-0 pl-0 is-12">
            <VField label="Tipe Pasien">
              <VControl>
                <VInput v-model="input.tipePasien" class="input" type="text" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column pt-0 pl-0 is-6 is-flex">
          <div class="column pt-0 pl-0 is-12">
            <VField label="Nama DPJP">
              <VControl>
                <AutoComplete v-model="input.dpjp" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari DPJP" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-3">
            <h1 style="font-weight: bold">Resume Klinis dan Diagnosis:</h1>
          </div>
          <div class="column pt-0 pl-0 is-9">
            <VField>
              <VControl>
                <VTextarea v-model="input.resumeKlinisDanDiagnosis" class="input" type="text" row="5" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-3">
            <h1 style="font-weight: bold">Jenis Permintaan:</h1>
          </div>
          <div class="column pt-0 pl-0 is-9">
            <VField>
              <VControl>
                <VTextarea v-model="input.jenisPermintaan" class="input" type="text" row="5" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-3">
            <h1 style="font-weight: bold">Scan Area:</h1>
          </div>
          <div class="column pt-0 pl-0 is-9">
            <VField>
              <VControl>
                <VTextarea v-model="input.scanArea" class="input" type="text" row="5" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column">
          <table class="table-pri">
            <tr>
              <td class="td-pri" width="30%">
                <span>Beri Tanda (&#10004;)</span>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.ctPlanning" true-value="CT Planning" label="CT Planning" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.pengecilanLapangan" true-value="Pengecilan Lapangan"
                      label="Pengecilan Lapangan" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.ctEvaluasi" true-value="CT Evaluasi" label="CT Evaluasi" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.kontur3Slices" true-value="Kontur 3 Slices" label="Kontur 3 Slices" />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox v-model="input.virtualSimulator" true-value="Virtual Simulator"
                      label="Virtual Simulator" />
                  </VControl>
                </VField>
              </td>
              <td class="td-pri">
                <table class="table-pri">
                  <thead>
                    <!-- <th>Nama</th> -->
                    <th colspan="3" class="th-pri">Keterangan</th>
                    <th class="th-pri">Ya</th>
                    <th class="th-pri">Tidak</th>
                  </thead>
                  <tbody>
                    <tr>
                      <!-- <td rowspan="2" class="td-pri">Kontras</td> -->
                      <td colspan="3" class="td-pri">Intravena (IV)</td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.intravena" true-value="YA"/>
                      </td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.intravena" true-value="TIDAK"/>
                      </td>
                    </tr>
                    <tr>
                      <!-- <td rowspan="2" class="td-pri">Kirim Data</td> -->
                      <td colspan="3" class="td-pri">Oral</td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.oral" true-value="YA"/>
                      </td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.oral" true-value="TIDAK"/>
                      </td>
                    </tr>
                    <tr>
                      <!-- <td rowspan="2" class="td-pri">Print Hasil CT</td> -->
                      <td colspan="3" class="td-pri">Unique</td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.unique" true-value="YA"/>
                      </td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.unique" true-value="TIDAK"/>
                      </td>
                    </tr>
                    <tr>
                      <!-- <td rowspan="2" class="td-pri">Print Hasil CT</td> -->
                      <td colspan="3" class="td-pri">PACS</td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.pacs" true-value="YA"/>
                      </td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.pacs" true-value="TIDAK"/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" class="td-pri">Kertas Glossy</td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.kertasGlossy" true-value="YA"/>
                      </td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.kertasGlossy" true-value="TIDAK"/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" class="td-pri">Film</td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.film" true-value="YA"/>
                      </td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.film" true-value="TIDAK"/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" class="td-pri">CD</td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.cd" true-value="YA"/>
                      </td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.cd" true-value="TIDAK"/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" class="td-pri">Tarif termasuk paket</td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.tarif" true-value="YA"/>
                      </td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.tarif" true-value="TIDAK"/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" class="td-pri">Pembayaran</td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.pembayaran" true-value="YA"/>
                      </td>
                      <td class="td-pri">
                        <VCheckbox v-model="input.pembayaran" true-value="TIDAK"/>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </table>

        </div>

        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-3">
            <h1 style="font-weight: bold">Lain - lain:</h1>
          </div>
          <div class="column pt-0 pl-0 is-9">
            <VField>
              <VControl>
                <VTextarea v-model="input.lainlain" class="input" type="text" row="5" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-3">
            <h1 style="font-weight: bold">Catatan Khusus :</h1>
          </div>
          <div class="column pt-0 pl-0 is-9">
            <VField>
              <VControl>
                <VTextarea v-model="input.catatanKhusus" class="input" type="text" row="5" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="colum">
          <table class="table-pri">
            <thead>
              <th class="th-pri">Nama</th>
              <th class="th-pri">Keterangan</th>
            </thead>
            <tbody>
              <tr>
                <td class="td-pri">
                  Ureum
                </td>
                <td class="td-pri">
                  <VField>
                    <VInput v-model="input.Ureum" class="input" type="text" />
                  </VField>
                </td>
              </tr>

              <tr>
                <td class="td-pri">
                  Kreatinin
                </td>
                <td class="td-pri">
                  <VField>
                    <VInput v-model="input.Kreatinin" class="input" type="text" />
                  </VField>
                </td>
              </tr>

              <tr>
                <td class="td-pri">
                  eGFR
                </td>
                <td class="td-pri">
                  <VField>
                    <VInput v-model="input.eGFR" class="input" type="text" />
                  </VField>
                </td>
              </tr>

              <tr>
                <td class="td-pri">
                  Foto MRI/CTLama
                </td>
                <td class="td-pri">
                  <VField>
                    <VCheckbox v-model="input.fotoMRI" true-value="Disertakan" label="Disertakan"/>
                  </VField>
                  <VField>
                    <VCheckbox v-model="input.fotoMRI" true-value="Tidak" label="Tidak"/>
                  </VField>
                  <VField>
                    <VInput v-model="input.jumlahFoto" class="input" type="text" />
                  </VField>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-3">
            <h1 style="font-weight: bold">No Exam :</h1>
          </div>
          <div class="column pt-0 pl-0 is-9">
            <VField>
              <VControl>
                <VInput v-model="input.catatanKhusus" class="input" type="text" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-3">
            <h1 style="font-weight: bold">Tanggal :</h1>
          </div>
          <div class="column pt-0 pl-0 is-9">
            <VField>
              <VDatePicker v-model="input.tanggal" mode="date" trim-weeks :max-date="new Date()">
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
        </div>

        <div class="column pt-0 pl-0 is-12 is-flex">
          <div class="column pt-0 pl-0 is-3">
            <h1 style="font-weight: bold">Dokter yang Meminta :</h1>
          </div>
          <div class="column pt-0 pl-0 is-9">
            <VField>
                <AutoComplete v-model="input.dokterMeminta" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Dokter" />
            </VField>
          </div>
        </div>

        <!-- <pre>{{ props }}</pre> -->
      </div>
    </VCard>
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
  title: 'Permintaan CT Simulator Onkrad - ' + import.meta.env.VITE_PROJECT,
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
const COLLECTION: any = ref("PermintaanCTSimulatorOnkrad"); //table mongodb
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

};

const loadRiwayatEMR = async () => {
  isLoading.value = true;
  const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0]
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  }
  isLoading.value = false;
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
    // loadRiwayat()
    loadRiwayatEMR()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
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

const kembaliKeun = () => {
  window.history.back();
};

const print = async () => {
  H.printBlade(
    `emr/cetak-formulir-skema-penyinaran-radioterapi?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  );
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.alamatLengkap = props.pasien.alamatlengkap;
  input.value.noTelfon = props.pasien.nohp;
  input.value.tipePasien = props.registrasi.kelompokpasien
}
const d_Ruangan: any = ref([])
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}

const d_Pegawai: any = []
const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Pegawai.value = response
}

setView()
setAutoFill()
loadRiwayat()
loadRiwayatEMR()
</script>
