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
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
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
            <h1 style="font-weight: bold">Alloanamesis:</h1>
          </div>
          <div class="column" style="display: flex">
            <VField>
              <VControl>
                <VCheckbox v-model="input.loanamesis" class="pt-1 pb-1" label="Suami/Istri" true-value="Suami/Istri"
                  color="primary" circle />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VCheckbox v-model="input.loanamesis" class="pt-1 pb-1" label="orang Tua" true-value="orang Tua"
                  color="primary" circle />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VCheckbox v-model="input.loanamesis" class="pt-1 pb-1" label="Anak" true-value="Anak" color="primary"
                  circle />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VCheckbox v-model="input.loanamesis" class="pt-1 pb-1" label="Lainnya" true-value="Lainnya"
                  color="primary" circle />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="Lainnya" v-model="input.loanamesisLainnya" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="is-flex">
          <div class="column is-2">
            <h1 style="font-weight: bold">ANAMNESIS:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.anamnesis" class="input" placeholder="Anamnesis" rows="5" />
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
              <h1>Keadaan Umum</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.keadaanumumobgyn" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
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
              <h1>Respirasi</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="" v-model="input.nafasObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x/menit</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
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
            <div class="column is-3 pt-0">
              <h1>SaO2</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="" v-model="input.sao2Obgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>%</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <h1>Berat Badan</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Berat Badan" v-model="input.beratbadanObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>kg</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <h1>Tinggi Badan</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Tinggi Badan" v-model="input.tinggibadanObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>cm</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 pb-0 pt-0">
              <h1>GCS : </h1>
            </div>
            <div class="column is-3 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>E</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E" label="label" :options="d_gcse"
                    :searchable="true" track-by="label" mode="single" autocomplete="off"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>V</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V" label="label" :options="d_gcsv"
                    :searchable="true" track-by="label" mode="single" autocomplete="off"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>M</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M" label="label" :options="d_gcsm"
                    :searchable="true" track-by="label" mode="single" autocomplete="off"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class=" is-12">
          <div class="column is-12 pb-0">
            <h1 class="bold" style="font-size:larger;">
              PEMERIKSAAN FISIK :
            </h1>
          </div>
          <div class="is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Kepala:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VTextarea v-model="input.Kepala" class="input" placeholder="Anamnesis" rows="5" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="is-flex is-12">
          <div class="column is-2">
            <h1 style="font-weight: bold">Mata :</h1>
          </div>
          <div class="columns pl-3 is-multiline is-flex-wrap-wrap p-0">
            <div class="column is-4 is-flex" v-for="(items, index) in MATA" :key="index">
              <VField class="p-0">
                <VControl>
                  <VCheckbox color="primary" v-model="input[`checkbox-${items.model}`]" :true-value="items.value" />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput v-model="input[items.model]" class="input" :placeholder="items.label" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="is-flex is-12">
          <div class="column is-2">
            <h1 style="font-weight: bold">THT :</h1>
          </div>
          <div class="columns pl-3 is-multiline is-flex-wrap-wrap p-0">
            <div class="column is-4 is-flex" v-for="(items, index) in THT" :key="index">
              <VField class="p-0">
                <VControl>
                  <VCheckbox color="primary" v-model="input[`checkbox-${items.model}`]" :true-value="items.value" />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput v-model="input[items.model]" class="input" :placeholder="items.label" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="is-flex is-12">
          <div class="column is-2">
            <h1 style="font-weight: bold">LEHER :</h1>
          </div>
          <div class="columns pl-3 is-multiline is-flex-wrap-wrap p-0">
            <div class="column is-8 is-flex">
              <VField class="p-0">
                <VControl>
                  <VCheckbox color="primary" v-model="input.JVP" true-value="JVP" />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput v-model="input.JVPKeterangan" class="input" placeholder="JVP" />
                </VControl>
              </VField>
            </div>
            <div class="column is-8">
              <VField>
                <VControl>
                  <VCheckbox v-model="input.pembesaranKelenjar" label="Pembesaran Kelenjar"
                    true-value="Pembesaran Kelenjar" />
                </VControl>
              </VField>
            </div>

            <div class="column is-8">
              <VField>
                <VControl>
                  <VCheckbox v-model="input.kakuKuduk" label="Kaku kuduk" true-value="Kaku kuduk" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">Thoraks:</h1>
          </div>
          <div class="column is-10 is-flex">
            <VField>
              <VControl>
                <VCheckbox v-model="input.simteris" label="Thoraks" true-value="Simetris / Asimetris" />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VInput v-model="input.simterisKet" class="input" placeholder="Simetris / Asimetris" rows="5" />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VCheckbox v-model="input.retaksi" label="Retaksi" true-value="Retaksi" />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VInput v-model="input.retaksiKet" class="input" placeholder="Retaksi" rows="5" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">Cor:</h1>
          </div>
          <div class="column is-10 is-flex">
            <VField>
              <VControl>
                <VCheckbox v-model="input.cor" true-value="S1/S2" />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VInput v-model="input.ORKet" class="input" placeholder="Reguler / Ireguler" rows="5" />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VCheckbox v-model="input.Murmur" label="Murmur" true-value="Murmur" />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VInput v-model="retaksiKet" class="input" placeholder="Retaksi" rows="5" />
              </VControl>
            </VField>

            <VField class="ml-5">
              <VControl>
                <VInput v-model="input.lainnya" class="input" placeholder="Lainnya" rows="5" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="is-flex is-12">
          <div class="column is-2">
            <h1 style="font-weight: bold">PULMO :</h1>
          </div>
          <div class="columns pl-3 is-multiline is-flex-wrap-wrap p-0">
            <div class="column is-4 is-flex" v-for="(items, index) in PULMO" :key="index">
              <VField class="p-0">
                <VControl>
                  <VCheckbox color="primary" v-model="input[`checkbox-${items.model}`]" :true-value="items.value" />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput v-model="input[items.model]" class="input" :placeholder="items.label" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="is-flex is-12">
          <div class="column is-2">
            <h1 style="font-weight: bold">Abdomen :</h1>
          </div>
          <div class="columns pl-3 is-multiline is-flex-wrap-wrap p-0">
            <div class="column is-4 is-flex" v-for="(items, index) in Abdomen" :key="index">
              <VField class="p-0">
                <VControl>
                  <VCheckbox color="primary" v-model="input[`checkbox-${items.model}`]" :true-value="items.value" />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput v-model="input[items.model]" class="input" :placeholder="items.label" />
                </VControl>
              </VField>
            </div>
            <div class="is-12 is-flex">
              <div class="is-2">
                <h1 style="font-weight: bold">Peristaltik:</h1>
              </div>
              <div class="columns pl-3 is-multiline is-flex-wrap-wrap p-0">
                <div class="is-flex">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.peristaltik" label="Meningkat" true-value="Meningkat" />
                    </VControl>
                  </VField>

                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.peristaltik" label="Menurun" true-value="Menurun" />
                    </VControl>
                  </VField>

                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.peristaltik" label="Normal" true-value="Normal" />
                    </VControl>
                  </VField>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="column is-12 is-flex" style="margin-left: 23vh;">
          <VField>
            <VControl>
              <VCheckbox label="Ascites" v-model="input.ascites" true-value="Ascites" />
            </VControl>
          </VField>

          <VField>
            <VControl>
              <VCheckbox label="Nyeri tekan lokasi:" v-model="input.nyeri" true-value="Nyeri" />
            </VControl>
          </VField>

          <VField>
            <VControl>
              <VInput v-model="input.lainnyaAbdonem" class="input" placeholder="Lainnya" rows="5" />
            </VControl>
          </VField>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">Hepar:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.hepar" class="input" placeholder="Hepar" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">Lien:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.Lien" class="input" placeholder="Lien" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-12 is-flex">
          <div class="is-2">
            <h1 style="font-weight: bold">Extreamitas:</h1>
          </div>
          <div class="is-10 is-flex">
            <VField>
              <VControl>
                <VCheckbox label="Hangat" v-model="input.hangat" true-value="Hangat" />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VCheckbox label="Dingin" v-model="input.Dingin" true-value="Dingin" />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VCheckbox label="Odema" v-model="input.Odema" true-value="Odema" />
              </VControl>
            </VField>

            <VField class="mr-3">
              <VControl>
                <VInput v-model="input.keteranganOdema" class="input" placeholder="Odema" />
              </VControl>
            </VField>

            <VField>
              <VControl>
                <VInput v-model="input.lainnyaExtreamitas" class="input" placeholder="Lainnya" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">Lainnya:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.LainnyaStatusGeneralis" class="input" placeholder="Lainnya" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="is-12 p-3">
          <table class="table-rpo">
            <thead>
              <tr>
                <th class="th-rpo">Kriteria</th>
                <th class="th-rpo">Normal/ringan = 0</th>
                <th class="th-rpo">*</th>
                <th class="th-rpo">Sedang = 1</th>
                <th class="th-rpo">*</th>
                <th class="th-rpo">Berat = 2</th>
                <th class="th-rpo">*</th>
              </tr>
            </thead>

          </table>
        </div>

        <div class="column is-4 mt-3" style="margin-left: auto">
          <VCard class="border-card pink">
            <div class="column is-9">
              <VField>
                <h1 style="font-weight: bold">Garut , tanggal dan jam</h1>
              </VField>
              <VField>
                <VDatePicker v-model="item.tanggal" mode="dateTime" style="width: 100%" trim-weeks
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
              <TandaTangan :elemenID="'TTDAhliGizi'" :width="'150'" :height="'150'" class="dek" />
              <AutoComplete v-model="input.ahliGizi" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" />
            </div>
          </VCard>
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
import * as EMR2 from "../page-emr-plugins/asesmen-gizi-geriatri-rawat-jalan";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";

useHead({
  title: "Asesmen Gizi Bayi dan Anak Rawat Jalan - " + import.meta.env.VITE_PROJECT,
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

const COLLECTION: any = ref("AsesmenGiziGeriatriRawatJalan"); //table mongodb

const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggalJam: new Date(),
  kebjamKedatangan: new Date(),
  kebjamAsesmenAwal: new Date(),
  tanggalAwal: new Date(),
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

setAutoFill();
getDataExist();
fetchPasien();
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
