<template>
  <div class="form-layout is-stacked-2" style="width: 100%;max-width: none;">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> Catatan Kegiatan Onkologi Radiasi<span v-if="isStuck"></span></h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpanCatatan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
              @click="simpanTemplate()"> Simpan Template
            </VButton>
            <VButton type="button" rounded outlined color="warning" raised icon="feather:folder" :loading="isLoading"
              @click="pilihTemplateFix(index)"> Pilih Template
            </VButton>
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :loading="isLoading"
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

          <div class="column is-12 p-0">
            <hr class="m-0">
          </div>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-3" style="margin-top: 10px;">
                <h1 class="mb-12 emr" style="font-weight: bold;">Keadaan Umum</h1>
                <VField class="is-autocomplete-select">
                  <VControl icon="feather:search">
                    <Multiselect v-model="input.keadaanumumobgyn" :attrs="{ value }" placeholder="--Pilih--"
                      label="label" :options="d_keadaanumum" :searchable="true" track-by="label" mode="single"
                      autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <h1 class="mb-3 emr" style="font-weight: bold;">GCS</h1>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>E</VButton>
                      </VControl>
                      <VControl expanded>
                        <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E" label="label"
                          :options="d_gcse" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>V</VButton>
                      </VControl>
                      <VControl expanded>
                        <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V" label="label"
                          :options="d_gcsv" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>M</VButton>
                      </VControl>
                      <VControl expanded>
                        <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M" label="label"
                          :options="d_gcsm" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-3 is-flex" style="justify-content: center;align-items: center;">
                <VButton type="button" rounded outlined color="info" raised icon="feather:copy" :isLoading="isLoading"
                  @click="copyTTV()"> Copy TTV
                </VButton>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-3">
                <h1 style="font-weight: bold;">Tekanan Darah</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarah" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>mmHg</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <h1 style="font-weight: bold;">PR</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="PR" v-model="input.nadi" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/menit</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <h1 style="font-weight: bold;">RR</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="RR" v-model="input.nafas" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/menit</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <h1 style="font-weight: bold;">Suhu</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Suhu" v-model="input.celcius" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>°C </VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1 style="font-weight: bold;">SaO2</h1>
                <VField addons>
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Saturasi O2 (SpO2)" v-model="input.sao2" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>%</VButton>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <table class="table-pri">
              <thead v-for="(item, index) in input.details" :key="index">
                <!-- <tr class="tr-pri">
                  <td class="td-pri">
                    <h1 style="font-weight: bold"> Aksi</h1>
                  </td>
                  <td>
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
                </tr> -->
                <tr class="tr-pri">
                  <td class="td-pri" style="text-align: center;vertical-align: middle;">
                    <h1 style="font-weight: bold">Tanggal</h1>
                  </td>
                  <td class="td-pri" style="vertical-align: middle;">
                    <VDatePicker v-model="item.tanggalCatatan" mode="dateTime" trim-weeks is24hr>
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal & Jam" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td class="td-pri" style="text-align: center;vertical-align: middle;">
                    <h1 style="font-weight: bold">Tindakan</h1>
                  </td>
                  <td class="td-pri" style="vertical-align: middle;">
                    <VControl>
                      <VTextarea placeholder="Tindakan..." v-model="item.tindakan" rows="2" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td class="td-pri" style="text-align: center;vertical-align: middle;">
                    <h1 style="font-weight: bold">Keterangan</h1>
                  </td>
                  <td class="td-pri" style="vertical-align: middle;">
                    <VField>
                      <VTextarea placeholder="Keterangan..." v-model="item.keteranganCatatan" rows="2" />
                    </VField>
                  </td>
                </tr>
                <tr>
                  <td class="td-pri" style="text-align: center;vertical-align: middle;">
                    <h1 style="font-weight: bold">Keluhan</h1>
                  </td>
                  <td class="td-pri" style="vertical-align: middle;">
                    <VField>
                      <VTextarea placeholder="keluhan" v-model="item.keluhan" rows="2" />
                    </VField>
                  </td>
                </tr>
              </thead>
            </table>

            <div class="column is-6 pt-0">
              <VCard>
                <div class="column is-12" v-if="isloadingLAMPAU">
                  <div class="flex-list-inner mb-2 mt-5">
                    <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
                      <VPlaceloadWrap>
                        <VPlaceloadAvatar size="small" />
                        <VPlaceloadText last-line-width="60%" class="mx-2" />
                        <VPlaceload class="mx-2" disabled />
                        <VPlaceload class="mx-2 h-hidden-tablet-p" />
                        <VPlaceload class="mx-2 h-hidden-tablet-p" />
                        <VPlaceload class="mx-2" />
                      </VPlaceloadWrap>
                    </div>
                  </div>
                </div>
                <div class="column is-12 p-0" v-else-if="!isloadingLAMPAU">
                  <div v-if="riwayatFormulirPenyiaranRadioterapi.length === 0">
                    <VPlaceholderSection :title="H.assets().notFound" class="my-6">
                      <template #image>
                        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" style="width: 100px;" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                          style="width: 100px;" />
                      </template>
                    </VPlaceholderSection>
                  </div>
                  <div v-else>
                    <div class="column is-12 pt-0 is-flex" style="justify-content: center;">
                      <h1 style="font-weight: bold;">Riwayat Catatan</h1>
                    </div>
                    <div style="overflow: auto; height: 50vh;">
                      <table class="tg">
                        <thead>
                          <tr>
                            <th class="th-rpo" width="20%"
                              style="vertical-align:inherit;text-align: center;background-color: lightgray;">
                              Tanggal
                            </th>
                            <th class="th-rpo" width="20%"
                              style="vertical-align:inherit;text-align: center;background-color: lightgray;">Tindakan
                            </th>
                            <th class="th-rpo" width="20%"
                              style="vertical-align:inherit;text-align: center;background-color: lightgray;">
                              Keterangan
                            </th>
                            <th class="th-rpo" width="20%"
                              style="vertical-align:inherit;text-align: center;background-color: lightgray;">Keluhan
                            </th>
                            <th class="th-rpo" width="20%"
                              style="vertical-align:inherit;text-align: center;background-color: lightgray;">Petugas
                            </th>
                          </tr>
                        </thead>
                        <tbody v-for="(item, index) in riwayatFormulirPenyiaranRadioterapi" :key="index">
                          <tr v-for="(item2, index2) in item.details" :key="index2">
                            <td class="td-rpo" style="text-align: center;width: 20%;">
                              <div class="p-1">
                                {{ H.formatTanggal(item2.tanggalCatatan) }}
                              </div>
                            </td>
                            <td class="td-rpo" style="text-align: center;width: 20%;">
                              <div class="p-1">
                                {{ item2.tindakan }}
                              </div>
                            </td>
                            <td class="td-rpo" style="text-align: center;width: 20%;">
                              <div class="p-1">
                                {{ item2.keteranganCatatan }}
                              </div>
                            </td>
                            <td class="td-rpo" style="text-align: center;width: 20%;">
                              <div class="p-1">
                                {{ item2.keluhan }}
                              </div>
                            </td>
                            <td class="td-rpo" style="text-align: center;width: 20%;">
                              <div class="p-1">
                                {{ item.user_input.namalengkap }}
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </VCard>
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
                      <td
                        style="width:5%;text-align:center;vertical-align: middle;border: 1px solid black;padding: 5px;">
                        <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)"
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
            :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack"
            breakpoint="960px">
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
                  <VIconButton color="danger" light raised circle icon="lucide:x"
                    @click="deleteTemplate(slotProps.data.id)" v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
                  <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                    color="info" v-tooltip-prime.top="'Pilih'">
                  </VIconButton>
                  <VIconButton type="button" raised circle icon="fas fa-pencil-alt"
                    @click="editTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Edit'"
                    v-if="!isAlltemplate">
                  </VIconButton>
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
    </div>
  </div>

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteUpdate, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as EMR from "../page-emr-plugins/catatan-kegiatan-runit-pelayanan-onkologi-radiasi";
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
  }
)
const idTemplate: any = ref('');
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isloadingLAMPAU: any = ref(false)
const isDisabled: any = ref(false)
const userLogin = useUserSession().getUser()
const isInput: any = ref(false);
const user = useUserSession().getUser().kelompokUser.kelompokUser
const riwayatFormulirPenyiaranRadioterapi = ref([]);
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  selectedMenu: [false],
  filter: '',
  lab: [],
  lab_GROUP: [],
  radiologi: [],
  patologi: []
})
//template dan riwayat
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const pegawaiId = useUserSession().getUser().pegawai.id
const COLLECTION: any = ref('CatatanKegiatanRadioterapi') //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
const input: any = ref({
  details: [{
    no: 1,
    tanggalCatatan: new Date(),
  }]
})
const riwayatResep: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])

function copyTTV() {
  let data = input.value
  let ttv = ''
  if (data.keadaanumumobgyn != null) {
    ttv += `Keadaan Umum : ${data.keadaanumumobgyn}\n`
  }
  if (data.gcse != null && data.gcsv != null && data.gcsm != null) {
    ttv += `GCS E: ${data.gcse} V: ${data.gcsv} M: ${data.gcsm}\n`
  }
  if (data.tekananDarah != null) {
    ttv += `Tekanan Darah : ${data.tekananDarah}\n`
  }
  if (data.nadi != null) {
    ttv += `PR : ${data.nadi}\n`
  }
  if (data.nafas != null) {
    ttv += `RR : ${data.nafas}\n`
  }
  if (data.celcius != null) {
    ttv += `Suhu : ${data.celcius}\n`
  }
  if (data.sao2 != null) {
    ttv += `SaO2 : ${data.sao2}\n`
  }

  data.details[0].keteranganCatatan = ttv
}

//load riwayat catatan kegiatan
const loadRiwayat = async () => {
  try {
    isloadingLAMPAU.value = true;
    const response = await useApi().get(`/emr/get-emr-history-ct?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`);
    const ns = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + "&field=tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,beratbadanObgyn,tinggibadanObgyn")
    if (response.data.length) {
      if (ns != null) {
        input.value.keadaanumumobgyn = ns.keadaanumumobgyn ? ns.keadaanumumobgyn : ''
        input.value.gcse = ns.gcse ? ns.gcse : ''
        input.value.gcsv = ns.gcsv ? ns.gcsv : ''
        input.value.gcsm = ns.gcsm ? ns.gcsm : ''
        input.value.tekananDarah = ns.tekananDarahObgyn ? ns.tekananDarahObgyn : ''
        input.value.nadi = ns.nadiObgyn ? ns.nadiObgyn : ''
        input.value.nafas = ns.nafasObgyn ? ns.nafasObgyn : ''
        input.value.celcius = ns.celciusObgyn ? ns.celciusObgyn : ''
        input.value.sao2 = ns.sao2Obgyn ? ns.sao2Obgyn : ''
      }
      let sortedData = response.data.sort((a, b) => b.no - a.no);
      const filteredSortedData = sortedData.filter(item => !item.hasOwnProperty('namatemplate')).map(item => {
        if (Array.isArray(item.details)) {
          item.details.sort((a, b) => b.no - a.no);
        }
        return item;
      });
      riwayatFormulirPenyiaranRadioterapi.value = filteredSortedData;
    } else {
      // setAutoFill()
      if (ns != null) {
        input.value.keadaanumumobgyn = ns.keadaanumumobgyn ? ns.keadaanumumobgyn : ''
        input.value.gcse = ns.gcse ? ns.gcse : ''
        input.value.gcsv = ns.gcsv ? ns.gcsv : ''
        input.value.gcsm = ns.gcsm ? ns.gcsm : ''
        input.value.tekananDarah = ns.tekananDarahObgyn ? ns.tekananDarahObgyn : ''
        input.value.nadi = ns.nadiObgyn ? ns.nadiObgyn : ''
        input.value.nafas = ns.nafasObgyn ? ns.nafasObgyn : ''
        input.value.celcius = ns.celciusObgyn ? ns.celciusObgyn : ''
        input.value.sao2 = ns.sao2Obgyn ? ns.sao2Obgyn : ''
      }
    }
    isloadingLAMPAU.value = false;
  } catch (error) {
    console.error("Error loading data:", error);
  }
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
const editTemplate = async (dt: any) => {
  if (!dt) return;
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  input.value = dt;
  idTemplate.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
  checkTemplate.value = true
}
const simpanCatatan = () => {
  if (checkTemplate.value == true) {
    H.alert('warning', 'Simpan template ya, bukan simpan data :)')
    return;
  }
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
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
    `/emr/simpan-emr`, json).then(async (response: any) => {
      NOREC_EMRPASIEN.value = response.norec_emr
      await loadRiwayat()
      input.value.details[0].tindakan = undefined
      input.value.details[0].keteranganCatatan = undefined
      input.value.details[0].keluhan = undefined
      isLoading.value = false
    }).catch((e: any) => {
      isLoading.value = false
    })
}
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

const checkTemplate: any = ref(false)
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
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  input.value['id'] = ''
  showModalTemplate.value = false
  showModalTemplateFix.value = false
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
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&nocmfk=${ID_PASIEN}`).then((responselast: any) => {
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

useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});

// const addNewItem = () => {
//   input.value.details.push({
//     no: input.value.details[input.value.details.length - 1].no + 1,
//   });
// }
// const removeItem = (index: any) => {
//   input.value.details.splice(index, 1)
// }
// const setAutoFill = async () => {
//   const fieldsVitalSign = "tinggiBadan,IMT,lingkarPerut,tekananDarah,keadaanumumobgyn,keadaanumum,pernapasan,suhu,nadi,beratBadan,SPO2";
//   const fieldsAsesmen = "tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn";

//   const fetchData = async (collection, fields) => {
//     return await useApi().get(`emr/auto-fill?norec_pd=${props.registrasi.norec_pd}&collection=${collection}&field=${fields}`);
//   };

//   const parseResponse = (response) => {
//     if (!response) return "";

//     const fieldsMap = {
//       celciusObgyn: "Suhu : ",
//       nadiObgyn: "Nadi : ",
//       nafasObgyn: "Pernafasan : ",
//       tekananDarahObgyn: "Tekanan Darah : ",
//       sao2Obgyn: "SAO2 : ",
//     };

//     let data = "";
//     Object.entries(fieldsMap).forEach(([key, label]) => {
//       if (response[key]) data += `     ${label}${response[key]}\n`;
//     });

//     return data;
//   };

//   const setValues = (response) => {
//     if (!response) return;

//     input.value = {
//       ...input.value,
//       tekananDarahObgyn: response.tekananDarahObgyn || response.tekananDarah,
//       nadiObgyn: response.nadiObgyn || response.nadi,
//       nafasObgyn: response.nafasObgyn || response.pernapasan,
//       celciusObgyn: response.celciusObgyn || response.suhu,
//       sao2Obgyn: response.sao2Obgyn || response.SPO2,
//       gcse: response.gcse,
//       gcsv: response.gcsv,
//       gcsm: response.gcsm,
//       kebpilihanallo: response.kebpilihanallo,
//       keadaanumum: response.keadaanumumobgyn || response.keadaanumum,
//       beratbadanObgyn: response.beratbadanObgyn || response.beratBadan,
//       tinggibadanObgyn: response.tinggibadanObgyn || response.tinggiBadan,
//       keteranganCatatan: parseResponse(response),
//     };
//     console.log("RES", response);
//     input.value.details.forEach(item => {

//       item.keteranganCatatan = parseResponse(response);
//     });
//   };

//   let response = await fetchData("VitalSign", fieldsVitalSign);
//   if (!response) response = await fetchData("AsesmenAwalKeperawatanPasienRawatJalanNurse", fieldsAsesmen);
//   if (!response) response = await fetchData("AsesmenAwalKebidananRawatJalanNurse", fieldsAsesmen);
//   if (!response) response = await fetchData("AsesmenAwalKeperawatanPasienRawatJalan", fieldsAsesmen);

//   setValues(response);
// }
</script>

<style lang="scss">
hr {
  border-top: 1px solid hsl(0deg 6.81% 88.68%);
  display: block;
  height: 2px;
  margin: 0px;
}

.tg {
  width: 100% !important;
  border: 1px solid black !important;
}

.th-rpo,
.td-rpo {
  padding: 2px;
  border: 1px solid black !important;
  vertical-align: inherit;
}

.th-rpo {
  text-align: center !important;
}
</style>
