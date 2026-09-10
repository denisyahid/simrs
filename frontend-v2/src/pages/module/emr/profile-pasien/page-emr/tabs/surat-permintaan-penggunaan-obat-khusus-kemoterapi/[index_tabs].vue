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
              @simpan="simpan" @kembaliKeun="kembaliKeun" @simpanTemplate="simpanTemplate"></ButtonEmr>
          </div>
        </div>
        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-1 mb-1">
        <div style="text-align: center;font-size: large;font-weight: bold;">
          <VTag :class="isSave ? 'has-background-success' : 'has-background-danger'"
            style="color:white;width: 100%;font-size: large;">
            {{ isSave ? 'Form Sudah Tersimpan / Data Sudah Ada' : 'Form Belum Tersimpan' }}
          </VTag>
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
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="15%">Tanggal Input</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="15%">Tanggal Registrasi</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="15%">No Registrasi</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="15%">No EMR</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="20%">Dokter</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="15%">Section</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="5%">#</td>
                    </tr>
                  </thead>
                  <tbody v-for="resep in listTemplate">
                    <tr>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.created_at }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)"
                          color="info" v-tooltip-prime.top="'Pilih'">
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
                <table style="border: 1px solid black;" v-if="listTemplateFix.length > 0">
                  <thead>
                    <tr>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="5%">No</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="15%">Tanggal Dibuat</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="20%">Nama Ruangan</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="25%">Nama Template</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                        width="15%">#</td>
                    </tr>
                  </thead>
                  <tbody v-for="resep in listTemplateFix">
                    <tr>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.no }}</span><br>
                      </td>
                      <td style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.created_at }}</span><br>
                      </td>
                      <td style="width:20%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                      </td>
                      <td style="width:25%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.namatemplate }}</span><br>
                      </td>
                      <td
                        style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;padding: 3px;">
                        <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
                          color="info" v-tooltip-prime.top="'Pilih'">
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

      <div class="columns is-multiline p-2">
        <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
          <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
            @click="pilihTemplateFix(index)"> Pilih Template
          </VButton>
          <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
            @click="pilihTemplate(index)"> Pilih Riwayat
          </VButton>
        </div>

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>

        <div class="column is-12">
          <div class="columns">
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
          </div>
        </div>

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>

        <div class="column is-12">
          <div class="column is-flex p-0">
            <div class="column is-2">
              <h1 style="font-weight: bold">Nama Pasien :</h1>
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
              <h1 style="font-weight: bold">No RM :</h1>
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
              <h1 style="font-weight: bold">Tanggal Lahir Pasien :</h1>
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
              <h1 style="font-weight: bold">Jenis Kelamin : </h1>
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

          <div class=" is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Cara Bayar :</h1>
            </div>
            <div class="is-10 is-flex">
              <VField>
                <VControl>
                  <VCheckbox color="primary" label="JKN" v-model="input.caraBayar" true-value="JKN" />
                </VControl>
              </VField>

              <VField>
                <VControl>
                  <VCheckbox color="primary" label="IKS" v-model="input.caraBayar" true-value="IKS" />
                </VControl>
              </VField>

              <VField>
                <VControl>
                  <VCheckbox color="primary" label="Umum" v-model="input.caraBayar" true-value="Umum" />
                </VControl>
              </VField>

              <VField>
                <VControl>
                  <VCheckbox color="primary" label="WNA" v-model="input.caraBayar" true-value="WNA" />
                </VControl>
              </VField>

              <VField>
                <VControl>
                  <VCheckbox color="primary" label="Lainnya" v-model="input.caraBayar" true-value="Lainnya" />
                </VControl>
              </VField>

              <VField>
                <VControl>
                  <VInput placeholder="Lainnya" v-model="input.caraBayarLainnya" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-flex p-0">
            <div class="column is-2">
              <h1 style="font-weight: bold">Diagnosis :</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VTextarea v-model="input.diagnosis" class="input" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1 class="bold" style="font-size:larger;">
                  PEMERIKSAAN FISIK :
                </h1>
              </div>
              <div class="column is-3">
                <h1>Tekanan Darah</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarahObgyn" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>mmHg</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Nadi</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="" v-model="input.nadiObgyn" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/menit</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>RR</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="" v-model="input.rr" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static></VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Suhu</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="" v-model="input.celciusObgyn" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>°C </VButton>
                  </VControl>
                </VField>
              </div>

              <div class="column is-3">
                <h1>Perfomance Status</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="" v-model="input.perfomanceStatus" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Ket</VButton>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <Fieldset legend="Data Penunjang" :toggleable="true">
            <div class="columns is-12">
              <div class="column is-12 pb-0">
                <h1 class="bold" style="font-size:larger;">
                  - Hasil PA :
                </h1>
                <div class="column is-flex p-0">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">- Biopsi PA:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.biopsi" class="input" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-flex p-0">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">- IHC dan Hormonal:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.ihc" class="input" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                - Hasil Lboratorium (dilampirkan)
                <div class="column is-flex p-0">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">- DL</h1>
                  </div>
                  <div class="column is-10"></div>
                </div>

                <div class="column is-flex p-0">
                  <div class="column is-12">
                    <h1 style="font-weight: bold">- Kimia Darah (SGOT, SGPT, Albumin, Globulin, Bilirubin Total,
                      Bilirubin
                      Direk,
                      BUN, Serum
                      Creatinin, Uric Acid, LDH, UL, Elektrolit</h1>
                  </div>
                </div>

                <div class="column is-flex p-0">
                  <div class="column is-12">
                    <h1 style="font-weight: bold">- HbS Ag, Anti HCV, Anti HIV</h1>
                  </div>
                </div>

                <div class="column is-flex p-0">
                  <div class="column is-12">
                    <h1 style="font-weight: bold">- Foto Rontgen / CT Scans / USG</h1>
                  </div>
                </div>
                <div class="column is-12">


                  <div class="columns is-multiline mt-5" v-if="isLoading">
                    <VPlaceloadText :lines="1" class="p-2" />
                    <div class="column is-12" v-for="key in 2" :key="key">
                      <VPlaceloadWrap>
                        <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                        <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                        <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                        <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                      </VPlaceloadWrap>
                    </div>
                  </div>
                  <div class="columns is-multiline" v-else>
                    <div class="column is-12" v-if="dataSource.length">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <BerkasPasienView :data="dataSource" @edit="edit" @hapus="hapus" @lihat="lihat" :hide="false">
                          </BerkasPasienView>
                        </div>
                      </div>
                    </div>
                    <div class="column is-12" v-else>
                      <div class="page-placeholder">
                        <div class="placeholder-content">
                          <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                            alt="" />
                          <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                            alt="" />
                          <h3>{{ H.assets().notFound }}</h3>
                          <p class="is-larger">
                            {{ H.assets().notFoundSubtitle }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="column is-flex p-0">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">- Lain - lain</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput v-model="input.lainDataPenunjang" class="input" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </Fieldset>

          <Fieldset legend="Obat Khusus Yang Akan Diberikan" :toggleable="true">
            <div class="column is-flex p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Nama Obat Keras</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaobatOral" class="input" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-flex p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Dosis</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.dosisObat" class="input" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-flex p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Lama Pemberian</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.lamaPemberian" class="input" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-flex p-0">
              <div class="column is-12">
                <h1 style="font-weight: bold">Obat terapi sistemik intravena, subcutan di tulis di halaman berikutnya.
                </h1>
              </div>
            </div>

            <div class="column is-flex p-0">
              <div class="column is-2">
                <h1 style="font-weight: bold">Alasan Pemberian</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.alasanPemberian" class="input" />
                  </VControl>
                </VField>
              </div>
            </div>
          </Fieldset>

          <div class="columns is-multiline" style="margin-top: 1rem;">
            <div class="column is-12">
              <div class="column is-3 is-flex">
                <h1 style="font-weight: bold" class="mr-2">Tanggal, </h1>
                <VField>
                  <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="columns is-12" style="justify-content: space-between;">
                <div class="column is-3" style="text-align: center;">
                  <h1 style="font-weight: bold;">Dokter Penanggung Jawab Pasien, </h1>
                  <TandaTangan :elemenID="'dpjpPasien'" :width="'180'" :height="'180'"></TandaTangan>
                  <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.dokterRawat" :suggestions="d_Pegawai"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3" style="text-align: center;">
                  <h1 style="font-weight: bold;"> Konsultan Hemato Onkologi Medik,</h1>
                  <TandaTangan :elemenID="'konsultanHemato'" :width="'180'" :height="'180'"></TandaTangan>
                  <VField class="mt-3">
                    <VControl>
                      <AutoComplete v-model="input.konsultan" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai" />
                    </VControl>
                  </VField>
                </div>

              </div>

            </div>
          </div>

          <div class="columns is-multiline" style="margin-top: 1rem;">
            <div class="column is-12">
              <div class="columns is-12" style="justify-content: center;">
                <div class="column is-3" style="text-align: center;">
                  <h1 style="font-weight: bold;">Mengetahui, <br>Wakil Direktur Pelayanan <br>RSUD Bali Mandara</h1>
                  <TandaTangan :elemenID="'wadir'" :width="'180'" :height="'180'"></TandaTangan>
                  <VField class="is-autocomplete-select pt-3">
                    <VControl>
                      <AutoComplete v-model="input.wakilDirektur" :suggestions="d_Pegawai"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                    </VControl>
                  </VField>
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
import { h, reactive, ref, computed, watch, onBeforeMount } from "vue";
import { useRoute, useRouter, onBeforeRouteLeave } from "vue-router";
import { useHead } from "@vueuse/head";
import * as H from "/@src/utils/appHelper";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import AutoComplete from "primevue/autocomplete";
import MultiSelect from "primevue/multiselect";
import * as EMR from "../../../page-emr-plugins/kriteria-masuk-hcu";
import TandaTangan from "../../../page-emr-plugins/tanda-tangan.vue";
import Fieldset from "primevue/fieldset";
import BerkasPasienView from '../../berkas-pasien-preview.vue'
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'

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
const route = useRoute()
const router = useRouter()
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const isLoading: any = ref(false);
const isDisabled: any = ref(false);
const isLoadingVitalSign: any = ref(false);
const isSave: any = ref(false)
const dataTTD: any = ref([]);
const d_Perawat: any = ref([]);
const d_Dokter: any = ref([]);
const d_Pegawai: any = ref([]);
const d_Ruangan: any = ref([]);
const dataSource: any = ref([]);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: "",
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
});
const COLLECTION: any = ref("SuratPermintaanPenggunaanObatKhususKemoterapi"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggal: new Date(),
});

const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Pegawai.value = response
}

const setView = () => {
  useHead({ title: props.FORM_NAME + " - " + import.meta.env.VITE_PROJECT, });
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
  useViewWrapper().setFullWidth(true);
};

const loadRiwayat = async () => {
let tabs = route.params.index_tabs < 1 ? route.params.index_tabs - 1 : 1
  isLoading.value = true
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`)
  let check = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${tabs}&check_first_tab=true`)
  if(response.length && check.length != 0) {
        isSave.value = true
        isLoading.value = false
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("dpjpPasien", dataTTD.value.dpjpPasien)
        H.tandaTangan().set("konsultanHemato", dataTTD.value.konsultanHemato)
        H.tandaTangan().set("wadir", dataTTD.value.wadir)
  }else {
        if (check.length == 0 && route.params.index_tabs != 1) {
            H.alert('warning', 'Halaman sebelumnnya belum disimpan!');
        }
        isSave.value = false
        setAutoFill()
  }
//   await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`).then(async (response: any) => {
//     if (response.length) {
//       isSave.value = true
//       isLoading.value = false
//       input.value = response[0] //set ke inputan
//       if (NOREC_EMRPASIEN.value == '') {
//         NOREC_EMRPASIEN.value = response[0].emrpasienfk
//       }
//       dataTTD.value = response[0]
//       H.tandaTangan().set("dpjpPasien", dataTTD.value.dpjpPasien)
//       H.tandaTangan().set("konsultanHemato", dataTTD.value.konsultanHemato)
//       H.tandaTangan().set("wadir", dataTTD.value.wadir)
//     } else {
//       await setAutoFill();
//       isLoading.value = false
//     }
//   })
  // H.tandaTangan().set("TTDDokterPemeriksa", dataTTD.value.TTDDokterPemeriksa)
}

const loadRiwayat2 = () => {
  isLoading.value = true
  useApi().get(`/emr/berkas-pasien?nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}`).then((response: any) => {
    isLoading.value = false
    dataSource.value = response.data
  })
}
const simpan = () => {
  let ID = input.value.id ? input.value.id : "";
  let object: any = {};

  object = input.value;
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
    if (route.params.index_tabs) {
        object.index_tabs = parseInt(route.params.index_tabs)
    }
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  object['dpjpPasien'] = H.tandaTangan().get("dpjpPasien");
  object['konsultanHemato'] = H.tandaTangan().get("konsultanHemato");
  object['wadir'] = H.tandaTangan().get("wadir");
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
    H.alert('error', e)
    isLoading.value = false;
  });
};
const setAutoFill = async () => {
  const response_NS = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + `&field=tekananDarahObgyn,nadiObgyn,nafasObgyn,celciusObgyn`)
  if (response_NS != null) {
      input.value.tekananDarahObgyn = response_NS.tekananDarahObgyn;
      input.value.rr = response_NS.nafasObgyn;
      input.value.nadiObgyn = response_NS.nadiObgyn;
      input.value.celciusObgyn = response_NS.celciusObgyn;
  }
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  input.value.dokterRawat = props.registrasi.dokter;
  input.value.tglPembuatan = new Date();

  const responseHistori = await useApi().get(`/emr/get-emr-history-terakhir-v2?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
  if (responseHistori && responseHistori != '') {
    let d = input.value
    input.value = responseHistori
    input.value.tanggal = responseHistori.registrasi.tglregistrasi
    delete d.id

    dataTTD.value = responseHistori
    H.tandaTangan().set("dpjpPasien", dataTTD.value.dpjpPasien)
    H.tandaTangan().set("konsultanHemato", dataTTD.value.konsultanHemato)
    H.tandaTangan().set("wadir", dataTTD.value.wadir)
  }

    if (route.params.index_tabs > 1) {
        let rouutename = route.name + '-' + (route.params.index_tabs - 1)
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
        if (cache) {
            console.log('cache', cache);
        }
    }
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
    d_Dokter.value = response
  })
}

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
    } catch (error) {
        console.error('Error mount cache TAB EMR:', error);
    }
});

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name;
        let indexTabs = route.params.index_tabs;
        let cacheKey = `TAB~${props.registrasi.noregistrasi}~${rouutename}~${indexTabs}`;

        if (to.name !== 'profile-pasien') {
            H.cacheEMR().set(cacheKey, input.value);
            console.log(`Cache disimpan untuk ${cacheKey}`);
        }

        if (to.name === 'profile-pasien') {
            H.cacheEMR().remove(cacheKey);
            console.log(`Cache dihapus karena berpindah ke profile-pasien: ${cacheKey}`);
        }

    } catch (error) {
        console.error('Error saat menyimpan/menghapus cache:', error);
    }
    next();
});

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    console.log()
    return;
  }
  let ID = input.id ? input.id : ''
  let object: any = {}

  object = input.value
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
    input.value.namatemplate = null
  }).catch((e: any) => {
    isLoading.value = false
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

const addTemplate = (response: any) => {
  const skipKeys = ['id', '_id', 'namatemplate', 'namaPasien', 'norm', 'tanggalLahirPasien', 'jeniskelamin']; // Keys to be skipped

  for (const key in response) {
    if (!skipKeys.includes(key)) {
      input.value[key] = response[key]; // Only update allowed keys
    }
  }
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}

const addRiwayat = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&isAll=true`).then((responselast: any) => {
    isLoading.value = false
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

watch(
    () => route.params.index_tabs,
    (newValue, oldValue) => {
        input.value = {}
        loadRiwayat()
        let rouutename = route.name + '-' + route.params.index_tabs
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
        if (cache) {
            input.value = cache
        }
})

watch(
    () => input.value,
    (newValue, oldValue) => {
        let rouutename = route.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
        let timeout = null;
        if (timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(() => {
            H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, newValue);
        }, 500);
    }, { deep: true }
)

setView();
loadRiwayat();
loadRiwayat2();
</script>
