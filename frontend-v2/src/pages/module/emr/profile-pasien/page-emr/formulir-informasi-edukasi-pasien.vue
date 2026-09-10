<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Catatan Informasi dan Edukasi</h3>
            </div>
            <div class="right is-flex">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate()" @kembaliKeun="kembaliKeun"></ButtonEmr>
              <VButton type="button" rounded outlined color="info" raised icon="feather:save" :loading="isLoading"
                @click="preview" class="ml-2"> Preview
              </VButton>
            </div>
          </div>
          <!-- <VButton type="button" rounded outlined color="info" raised icon="feather:save" @click="pilihTemplate()" :loading="isLoading"> Riwayat</VButton> -->
        </div>
        <div class="column is-12 mb-0">
          <div class="columns is-mobile is-centered">
            <div class="column is-8">
              <h1>Nama Template&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin
                  membuat
                  template</span></h1>
              <VField>
                <VControl>
                  <VInput v-model="input.namatemplate">
                  </VInput>
                </VControl>
              </VField>
            </div>
            <div class="column is-auto mt-5" style="display: flex; gap: 5px;"> <!-- Flexbox with gap -->
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" :loading="isLoading"
                @click="pilihTemplateFix(index)">
                Pilih Template
              </VButton>
              <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :loading="isLoading"
                @click="pilihTemplate(index)">
                Pilih Riwayat
              </VButton>
            </div>
          </div>
        </div>

        <hr>

        <!-- form baru -->
        <div class="is-flex">
          <div class="column is-6">
            <div class="columns is-multiline">
              <!-- <pre>{{ riwayat_Load }}</pre> -->
              <div class="column is-12" v-for="(item, index) in input.details" :key="index">
                <div class="column">
                  <VButtons style="justify-content:end">
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem(item)" color="info"
                      v-tooltip.bubble="'Tambah Form'">
                    </VIconButton>
                    <VIconButton class="mt-1" v-if="item.no > 1" type="button" raised circle icon="feather:trash"
                      @click="removeItem(index)" color="danger" :disabled="item.disabledForm">
                    </VIconButton>
                  </VButtons>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <h1 class="mb-3" style="font-weight: bold;">Tenaga Medis</h1>
                    <div class="column is-12">
                      <VField>
                        <VControl>
                          <VInput v-model="item.tenagaMedis" class="input" disabled />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column is-6">
                    <h1 class="mb-3" style="font-weight: bold;">Hubungan Dengan Pasien</h1>
                    <div class="column is-12">
                      <VField class="is-autocomplete-select" v-slot="{ id }">
                        <VControl>
                          <Multiselect v-model="item.hubpasien" :attrs="{ value }" placeholder="--Pilih--" label="label"
                            :options="d_hubungan" :searchable="true" track-by="label" mode="single" autocomplete="off"
                            :disabled="item.disabledForm">
                          </Multiselect>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column is-6">
                    <h1 class="mb-3" style="font-weight: bold;">Nama</h1>
                    <div class="column is-12">
                      <VField>
                        <VControl>
                          <VInput v-model="item.nama" class="input" :disabled="item.disabledForm" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column is-6">
                    <h1 class="emr mb-5" style="font-weight: bold;">Tanggal dan Jam</h1>
                    <VField>
                      <VDatePicker v-model="item.tanggalJam" mode="dateTime" style="width: 100%" trim-weeks
                        :disabled="item.disabledForm">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"
                                :disabled="item.disabledForm" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <h1 class="mb-3" style="font-weight: bold;">Durasi Edukasi</h1>
                    <div class="column is-12">
                      <VField>
                        <VControl>
                          <input v-model="item.durasiedukasi" class="input" :disabled="item.disabledForm" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column is-12">
                    <h1 class="mb-3" style="font-weight: bold;">Metode Edukasi</h1>
                    <div class="is-12">
                      <VField>
                        <VControl>
                          <Multiselect v-model="item.metodeedukasi" :attrs="{ value }" placeholder="--Pilih--"
                            label="label" :options="metodeEdukasi" :searchable="true" track-by="label" mode="single"
                            autocomplete="off" :disabled="item.disabledForm">
                          </Multiselect>
                        </VControl>
                      </VField>
                    </div>
                    <div class="mt-3 is-12" v-if="item.metodeedukasi == 'Lainnya'">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.metodeedukasilain" class="input"
                            placeholder="Keterangan Lainnya" :disabled="item.disabledForm" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column is-12">
                    <h1 class="mb-3 emr" style="font-weight: bold;">Materi Informasi dan Edukasi</h1>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.mie" rows="7" :disabled="item.disabledForm">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <h1 class="mb-3" style="font-weight: bold;">Respon</h1>
                    <div class="column is-12">
                      <VField class="is-autocomplete-select" v-slot="{ id }">
                        <VControl>
                          <Multiselect v-model="item.respon" :attrs="{ value }" placeholder="--Pilih--" label="label"
                            :options="d_respon" :searchable="true" track-by="label" mode="single" autocomplete="off"
                            :disabled="item.disabledForm">
                          </Multiselect>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column is-12" style="text-align: center;">
                    <TandaTangan :elemenID="'TTDPenerimaEdukasi-' + index" :key="index" :width="'150'"
                      :height="'150'" />
                    <VControl>
                      <VInput type="text" v-model="item.ttdpenerimaedukasi" class="input"
                        placeholder="TTD Penerima Edukasi" :disabled="item.disabledForm" />
                    </VControl>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="column is-6">
            <VCard>
              <div class="columns is-multiline" style="height: 500px; overflow-y: scroll;">
                <div class="column is-12 p-0 m-0">
                  <div class="column" style="text-align: center;">
                    <h1 style="font-weight: bold;">RIWAYAT EDUKASI</h1>
                  </div>
                  <div class="column is-multiline columns">
                    <VField label="Semua Periode" class="text-muted px-0">
                      <VControl>
                        <VSwitchBlock class="switch-profesi" v-model="isNowPeriode" @change="loadRiwayatOld()"
                          color="success" />
                      </VControl>
                    </VField>
                  </div>
                  <!-- <pre>{{ props.pasien.registrasi.namaruangan }}</pre> -->
                  <!-- <pre>{{ riwayatCatatanEdukasi }}</pre> -->
                </div>
                <div class="column">
                  <VCard>
                    <div class="column is-12" v-if="isloadingLAMPAU">
                      <div class="flex-list-inner mb-2">
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
                      <div v-if="riwayatCatatanEdukasi.length === 0">
                        <VPlaceholderSection :title="H.assets().notFound" class="my-6">
                          <template #image>
                            <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" style="width: 100px;" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                              alt="" style="width: 100px;" />
                          </template>
                        </VPlaceholderSection>
                      </div>
                      <div v-else>
                        <div v-for="(item, index) in riwayatCatatanEdukasi" :key="index">
                          <span style="font-weight: bold;">Tanggal & Jam Dibuat : </span><span>{{
                            formatDate(item.created_at)
                          }}</span><br>
                          <span style="font-weight: bold;">Nama Pasien : </span><span>{{ item.pasien.namapasien
                          }}</span><br>
                          <span style="font-weight: bold;">Section : </span><span>{{ item.pasien.registrasi.namaruangan
                          }}</span><br>
                          <div class="column" style="height: 300px; overflow-y: auto;">
                            <div class="is-12" v-for="(item2, index2) in item.details">
                              <table class="table-pri">
                                <tr>
                                  <td class="td-pri" style="font-weight: bold;" width="20%">Tanggal dan Jam Edukasi</td>
                                  <td class="td-pri">{{ H.formatDateIndo(item2.tanggalJam) || '-' }}</td>
                                </tr>
                                <tr>
                                  <td class="td-pri" style="font-weight: bold;" width="20%">Dibuat Oleh</td>
                                  <!-- <td v-if="props.pasien.registrasi.namaruangan === 'POLI FISIOTERAPI' && item2.flag === 'Perawat'" class="td-pri">{{ item2.tenagaMedis }} - Fisioterapi</td>
                                  <td v-else class="td-pri">{{ item2.tenagaMedis }} - {{ item2.flag }}</td> -->
                                  <td class="td-pri">
                                    <!-- <pre>{{ props.pasien.registrasi.namaruangan  }}</pre> -->
                                    {{ item2.tenagaMedis }} - {{ props.pasien.registrasi.namaruangan === 'POLI FISIOTERAPI' && item2.flag === 'perawat' ? 'Fisioterapi' : item2.flag }}
                                  </td>
                                </tr>
                                <tr>
                                  <td class="td-pri" style="font-weight: bold;" width="20%">Metode Edukasi</td>
                                  <td class="td-pri">{{ item2.metodeedukasi }}</td>
                                </tr>
                                <tr>
                                  <td class="td-pri" style="font-weight: bold;" width="20%">Durasi Edukasi</td>
                                  <td class="td-pri">{{ item2.durasiedukasi }}</td>
                                </tr>
                                <tr>
                                  <td class="td-pri" style="font-weight: bold;" width="20%">Materi Informasi dan Edukasi
                                  </td>
                                  <td class="td-pri">{{ item2.mie }}</td>
                                </tr>
                                <tr>
                                  <td>
                                    <VIconButton circle icon="feather:copy" color="warning" raised bold
                                      @click="copy(index, index2)" class="ml-1" v-tooltip-prime.top="'Copy'">
                                    </VIconButton>
                                    <VIconButton circle icon="feather:copy" color="info" raised bold
                                      @click="copyTTD(index, index2)" class="ml-1" v-tooltip-prime.top="'Copy TTD'">
                                    </VIconButton>
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </VCard>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Dialog :header="'Riwayat Catatan'" v-model:visible="modalRiwayat" :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '65vw' }" :maximizable="true" :modal="true">
    <div class="columns is-multiline">
      <div class="column ">
        <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--green);">
          <div class="columns is-multiline">
            <div class="column is-12">
              <table class="tg">
                <tbody v-for="(itemski, index2) in riwayat_Load" :key="index2">
                  <thead class="tg">
                    <tr>
                      <th style="text-align: center;">{{ H.formatDateIndoSimple(itemski.tanggalJam) }}</th>
                    </tr>
                  </thead>
                  <tr style="background-color: var(--danger--light-color);">
                    <td colspan="12">
                      <div style="margin-top: 30px;">
                        <div class="columns is-multiline">
                          <div class="column is-6">
                            <h1 class="mb-3" style="font-weight: bold;">Hubungan Dengan Pasien</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                              <VControl>
                                <Multiselect v-model="itemski.hubpasien" :attrs="{ value }" placeholder="--Pilih--"
                                  label="label" :options="d_hubungan" :searchable="true" track-by="label" mode="single"
                                  disabled autocomplete="off" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <h1 class="mb-3" style="font-weight: bold;">Nama</h1>
                            <VField>
                              <VControl>
                                <VInput v-model="itemski.nama" disabled class="input" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <h1 class="mb-3" style="font-weight: bold;">Durasi Edukasi</h1>
                            <VField>
                              <VControl>
                                <input v-model="itemski.durasiedukasi" disabled class="input" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <h1 class="mb-3" style="font-weight: bold;">Metode Edukasi</h1>
                            <VField>
                              <VControl>
                                <input v-model="itemski.metodeedukasi" disabled class="input" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <h1 class="mb-3 emr" style="font-weight: bold;">Materi Informasi dan Edukasi</h1>
                            <VField>
                              <VControl>
                                <VTextarea v-model="itemski.mie" disabled rows="3"></VTextarea>
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <h1 class="mb-3" style="font-weight: bold;">Respon</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                              <VControl>
                                <Multiselect v-model="itemski.respon" disabled :attrs="{ value }"
                                  placeholder="--Pilih--" label="label" :options="d_respon" :searchable="true"
                                  track-by="label" mode="single" autocomplete="off" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <hr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Dialog>

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
                  <td class="tg-0lax text-center" width="5%">#</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                  <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                  <td class="tg-0lax text-center" width="20%">No EMR</td>
                  <td class="tg-0lax text-center" width="20%">Dokter</td>
                  <td class="tg-0lax text-center" width="20%">Penyakit</td>
                  <td class="tg-0lax text-center" width="20%">Section</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:5%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
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
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.riwayatpenyakit }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
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
      }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="8"
        :loading="isLoading" paginator tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
        :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack" breakpoint="960px">
        <template #header>
          <div class="columns is-multiline">
            <div class="column is-8">
              <VField>
                <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
                </VControl>
              </VField>
            </div>
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
            </VButtons>
          </template>
        </Column>
        <Column field="namatemplate" header="Nama" :sortable="true"></Column>
        <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
          <template #body="slotProps">
            {{ slotProps.data.registrasi.namaruangan }}
          </template>
        </Column>
        <Column field="created_at" header="Tanggal" :sortable="true">
          <template #body="slotProps">
            <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
          </template>
        </Column>
      </DataTable>
    </template>
  </VModal>

  <Dialog v-model:visible="isDialogPreview" modal header="Preview" :style="{ width: '90vw' }">
    <EdukasiPreview :edukasi="riwayatCatatanEdukasi" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="isDialogPreview = false">
        Tutup
      </VButton>
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
import * as EMR2 from '../page-emr-plugins/formulir-informasi-edukasi-pasien'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import EdukasiPreview from './edukasi-preview.vue'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
useHead({
  title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let metodeEdukasi: any = ref(EMR2.metodeEdukasi())

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

const route = useRoute()
const pasien: any = ref({})
const d_pegawai: any = ref([])
const d_Dokter = ref([])
const riwayat_Load: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date,
    jam: new Date
  },
  airway: [],
  disability: []

})

const COLLECTION: any = ref('FormulirCatatanInformasiEdukasi') //table mongodb

const NOREC_EMRPASIEN: any = ref('')
const ID: any = ref('')
const input: any = ref({
  tanggalJam: new Date(),
  details: [{
    no: 1,
    // isDisabled: false
  }]
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const modalRiwayat = ref(false)
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const isAlltemplate: any = ref(false);
const filterMenu: any = ref('')
const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const d_respon: any = ref([{ value: 1, label: 'Tidak respon sama sekali (tidak antusias dan keinginan belajar) (No Response (No Enthusiasm and Willingness to Learn))' },
{ value: 2, label: 'Tidak paham (ingin belajar tapi kesulitan mengerti) (Not Understand (Want to Learn but have difficulty to understand))' },
{ value: 3, label: 'Paham hal yang diajarkan tapi tidak bisa menjelaskan sendiri (Understand things that are thought but cannot explain it well)' },
{ value: 4, label: 'Dapat menjelaskan apa yang telah diajarkan tapi harus dibantu educator (Able to explain the things which are taught but have to help by educator)' },
{ value: 5, label: 'Dapat menjelaskan apa yang telah diajarkan tapi tanpa dibantu (Able to explain the things which are taught without helps)' }])
const d_hubungan: any = ref([{ value: 1, label: 'Anak' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Pasien Sendiri' }, { value: 4, label: 'Saudara Kandung' }, { value: 5, label: 'Suami/Istri' }, { value: 6, label: 'Teman' }, { value: 7, label: 'Lainnya' }])

const dataTTD: any = ref([]);
const user = useUserSession().getUser().kelompokUser.kelompokUser
const createdBy = useUserSession().getUser().pegawai.namaLengkap
const riwayatCatatanEdukasi: any = ref([]);
const isloadingLAMPAU = ref(false)
const userLogin = useUserSession().getUser().pegawai.namaLengkap
const disabledForm: any = ref(false)
let isNewItemAdded = false;
const previewEdukasi: any = ref([])
const isDialogPreview: any = ref(false)
const isNowPeriode: any = ref(false)

//fungsi untuk update tanda tangan jika klik tanda plus
const updateTandaTanganKeys = () => {
  const backupTTD = {};
  input.value.details.forEach((item) => {
    if (item.TTDPenerimaEdukasi && H.tandaTangan().get(item.TTDPenerimaEdukasi)) {
      backupTTD[item.TTDPenerimaEdukasi] = H.tandaTangan().get(item.TTDPenerimaEdukasi);
    }
  });

  input.value.details.forEach((item, index) => {
    const oldKey = item.TTDPenerimaEdukasi;
    const newKey = `TTDPenerimaEdukasi-${index}`;
    item.TTDPenerimaEdukasi = newKey;

    if (oldKey && backupTTD[oldKey]) {
      H.tandaTangan().set(newKey, backupTTD[oldKey]);
    }
  });
};


const addNewItem = async () => {
  let newItem = {
    no: input.value.details.length ? input.value.details[0].no + 1 : 1,
    nama: props.pasien.namapasien,
    tanggal: new Date(),
    tanggalJam: new Date(),
    tenagaMedis: userLogin,
    flag: user,
    TTDPenerimaEdukasi: '',
    ttdpenerimaedukasi: props.pasien.namapasien,
  };

  if (user === 'perawat') {
    newItem.mie = `KIE prosedur pemeriksaan di poliklinik: \nKIE prosedur berkas administrasi (pembiayaan) rawat jalan: \nKIE prosedur kontrol kembali:\nKIE prosedur pemeriksaan penunjang: \nKIE prosedur persiapan tindakan medis: \nKIE perawatan pasien dirumah:`;
  } else if (user === 'dokter') {
    newItem.mie = `Penjelasan penyakitnya: \npenyebab, tanda dan gejala, prognosa: \nPenggunaan alat kedokteran: \nPenjelasan komplikasi yang mungkin terjadi: \nHasil pemeriksaan: \nPerkiraan hari rawat: \nTindakan medis: \nLainnya: \n`;
  }

  input.value.details.unshift(newItem);
  updateTandaTanganKeys();
  await nextTick();

  input.value.details.forEach((item, index) => {
    if (item.TTDPenerimaEdukasi) {
      H.tandaTangan().set(item.TTDPenerimaEdukasi, item.ttd || null);
    }
  });
};

const removeItem = async (index) => {
  input.value.details.splice(index, 1);
  updateTandaTanganKeys();

  await nextTick();
  input.value.details.forEach((item, index) => {
    if (item.TTDPenerimaEdukasi) {
      H.tandaTangan().set(item.TTDPenerimaEdukasi, item.ttd || null);
    }
  });
  await simpan()
};

const loadRiwayat = async () => {
  const response = await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  );

  if (response.length) {
    input.value = response[0];
    if (!input.value.id) {
      input.value.id = response[0].id;
    }
    riwayat_Load.value = response[0].details.map((item: any, index: any) => {
      const normalizedTenagaMedis = item.tenagaMedis.trim().toLowerCase();
      const normalizedUserLogin = userLogin.trim().toLowerCase();

      const isSameUser = normalizedTenagaMedis === normalizedUserLogin;
      const disabled = !isSameUser;

      item.disabledForm = disabled;
      item.isSameUser = isSameUser;
      return item;
    });

    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk;
    }

    dataTTD.value = response[0].details

    await nextTick(() => {
      dataTTD.value.forEach((item2, index2) => {
        if (item2.ttd) {
          H.tandaTangan().set(`TTDPenerimaEdukasi-${index2}`, item2.ttd);
        }
      });
    });

  }
}


const loadRiwayatOld = async () => {
  let nowPeriode = ``
  try {
    isloadingLAMPAU.value = true;
    nowPeriode = isNowPeriode.value ? `` : `&norec_pd=${NOREC_PD}`;
    const response = await useApi().get(`/emr/get-emr-history-edukasi?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}${nowPeriode}&flag=`);
    if (response.length) {
      let sortedData = response.sort((a, b) => b.no - a.no);
      const filteredSortedData = sortedData.filter(item => !item.hasOwnProperty('namatemplate')).map(item => {
        if (Array.isArray(item.details)) { item.details.sort((a, b) => b.no - a.no); }
        return item;
      });
      riwayatCatatanEdukasi.value = filteredSortedData;
    } else {
      H.alert('warning', 'Data tidak ada!')
    }
    isloadingLAMPAU.value = false;
  } catch (error) {
    H.alert('error', 'Terjadi Kesalahan')
    console.error("Error loading data:", error);
  }
}

const copy = (indexRiwayat, indexDetail) => {
  let selectedRiwayat = riwayatCatatanEdukasi.value[indexRiwayat];
  if (!selectedRiwayat) {
    console.error("Error: Riwayat tidak ditemukan untuk index yang diberikan.");
    return;
  }

  let selectedDetail = selectedRiwayat.details[indexDetail];
  if (!selectedDetail) {
    console.error("Error: Detail tidak ditemukan untuk index yang diberikan.");
    return;
  }

  let newItem = {
    tenagaMedis: selectedDetail.tenagaMedis,
    hubpasien: selectedDetail.hubpasien,
    nama: selectedDetail.nama,
    tanggalJam: selectedDetail.tanggalJam,
    durasiedukasi: selectedDetail.durasiedukasi,
    metodeedukasi: selectedDetail.metodeedukasi,
    metodeedukasilain: selectedDetail.metodeedukasilain,
    mie: selectedDetail.mie,
    tenagaMedis: userLogin,
    respon: selectedDetail.respon,
    disabledForm: false,
    ttd: selectedDetail.ttd,
  };


  Object.assign(input.value.details[0], newItem);
  console.log("Data yang disalin ditambahkan ke item baru:", input.value.details[0]);
  const ttdKey = `TTDPenerimaEdukasi-${0}`;
  H.tandaTangan().set(ttdKey, selectedDetail.ttd || null);
  H.alert("info", "Data berhasil disalin pada lembaran baru");
};
const copyTTD = (indexRiwayat, indexDetail) => {
  let selectedRiwayat = riwayatCatatanEdukasi.value[indexRiwayat];

  if (!selectedRiwayat) {
    console.error("Error: Riwayat tidak ditemukan untuk index yang diberikan.");
    return;
  }

  let selectedDetail = selectedRiwayat.details[indexDetail];

  if (!selectedDetail) {
    console.error("Error: Detail tidak ditemukan untuk index yang diberikan.");
    return;
  }

  console.log("Data yang disalin:", selectedDetail);

  let newItem = {
    ttd: selectedDetail.ttd,
  };


  Object.assign(input.value.details[0], newItem);
  console.log("Data yang disalin ditambahkan ke item baru:", input.value.details[0]);
  const ttdKey = `TTDPenerimaEdukasi-${0}`;
  H.tandaTangan().set(ttdKey, selectedDetail.ttd || null);
  H.alert("info", "Data berhasil disalin pada lembaran baru");
};
const formatDate = (date: any) => {
  const options: Intl.DateTimeFormatOptions = {
    timeZone: 'Asia/Jakarta',
    weekday: 'long',
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  };

  // Format tanggal dan waktu menggunakan Intl.DateTimeFormat
  return new Intl.DateTimeFormat('id-ID', options).format(new Date(date));
};

const simpan = async () => {
  ID.value = input.value.id ? input.value.id : '';
  let object: any = {};

  if (input.value.kebrujukan == 'TIDAK') {
    if (input.value.kebrujuklanjutan == 'DIANTAR') {
      input.value.kebketrujukan = input.value.kebketrujukan;
    }
  }

  if (input.value.kebpilihanallo == 'Lainnya') {
    input.value.kebpilihanallo = input.value.keballoanamnesis;
  }

  if (input.value.kualitasnyeri == 'LAINNYA') {
    input.value.kualitasnyeri = input.value.kualitasnyerilain;
  }

  if (input.value.pembiayaankesehatan == 'ASURANSI') {
    input.value.pembiayaankesehatan = input.value.ketpembiayaankesehatan;
  }

  object = input.value;
  object.nocm = pasien.value.nocm;
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate;
  }

  let existingDetails = object.details || [];
  let pushData: any = []
  let updatedDetails = input.value.details.map((detail: any) => {
    let existing = existingDetails.find(
      (existingDetail: any) =>
        existingDetail.nama === detail.nama &&
        existingDetail.createdby === detail.createdby &&
        existingDetail.tanggalJam === detail.tanggalJam &&
        existingDetail.flag === detail.flag
    );
    if (existing && existing.createdby !== userLogin) {
      return existing;
    }
    console.log("EXISTING", JSON.stringify(existing, null, 2));
    if (existing && existing.createdby === userLogin) {
      return {
        ...existing,
        flag: user,
        createdby: existing.createdby,
        durasiedukasi: existing.durasiedukasi,
      };
    }
    console.log("flag", JSON.stringify(existing, null, 2));
    return {
      ...existing,
      flag: user,
      createdby: detail.createdby || userLogin,
    };
  });

  if (updatedDetails.length > 0) {
    updatedDetails.forEach((detail: any, index: any) => {
      const ttdPenerimaEdukasi = H.tandaTangan().get(`TTDPenerimaEdukasi-${index}`);
      detail.ttd = ttdPenerimaEdukasi;
      pushData.push(detail)
    })
  }

  object.details = pushData;

  let json = {
    id: ID.value,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  };

  isLoading.value = true;
  await useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false;
    loadRiwayatOld();
  }).catch((e: any) => {
    isLoading.value = false;
  });
};

const kembaliKeun = () => {
  window.history.back()
}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
  console.log(norec_emr)
}

const fetchDokter = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_pegawai.value = response
  })
}

const skor = (e: any, i: any) => {

  let listSkor = listSkoringNyeri.value.detail

  listSkor.forEach((element: any) => {
    if (element.descNilai == e.descNilai) {
      input.value.skoringNyeri = e.descNilai
    }
  });
  isAktive.value = i

}

const getDataExist = async () => {
  await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {

    if (response != null || response != undefined) {
      input.value.beratbadanObgyn = response.beratBadan
      input.value.tinggibadanObgyn = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.nadiObgyn = response.nadi
      input.value.celciusObgyn = response.suhu
      input.value.tekananDarahObgyn = response.tekananDarah
      input.value.nafasObgyn = response.pernapasan
      input.value.sao2Obgyn = response.SPO2
    }
  })
}

const handlerRujukanChange = (val: any) => {
  console.log(val);
  if (val === "YA") {

  }
}

const print = async () => {
  H.printBlade(`emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await loadRiwayatOld()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
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

const setAutoFill = async () => {
  input.value.details.forEach((item) => {
    // Hanya mengisi nilai jika properti tidak memiliki nilai sebelumnya
    if (!item.nama) item.nama = props.pasien.namapasien;
    if (!item.ttdpenerimaedukasi) item.ttdpenerimaedukasi = props.pasien.namapasien;
    if (!item.tenagaMedis) item.tenagaMedis = userLogin;
    if (!item.tanggalJam) item.tanggalJam = new Date();
    item.flag = user; // Ini diizinkan untuk diubah

    if (user === 'perawat') {
      item.mie =
        item.mie ||
        `KIE prosedur pemeriksaan di poliklinik: \nKIE prosedur berkas administrasi (pembiayaan) rawat jalan: \nKIE prosedur kontrol kembali:\nKIE prosedur pemeriksaan penunjang: \nKIE prosedur persiapan tindakan medis: \nKIE perawatan pasien dirumah:`;
    } else if (user === 'dokter') {
      item.mie =
        item.mie ||
        `Penjelasan penyakitnya: \npenyebab, tanda dan gejala, prognosa: \nPenggunaan alat kedokteran: \nPenjelasan komplikasi yang mungkin terjadi: \nHasil pemeriksaan: \nPerkiraan hari rawat: \nTindakan medis: \nLainnya: \n`;
    }
  });
}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
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
    `/emr/get-emr-template?collection=${COLLECTION.value}&isAll=${isAlltemplate.value}`).then((responselast: any) => {
      isLoading.value = false
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          // responselast[x].id = ''
        }
        listTemplateFix.value = responselast //set ke inputan
        showModalTemplateFix.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

const addTemplate = (response) => {
  const { tenagaMedis, tanggalJam, nama, ttdpenerimaedukasi, ...filteredDetails } = response.details[0];
  input.value.details[0] = filteredDetails;

  delete input.value.namatemplate;
  delete input.value['_id'];
  input.value['id'] = '';

  console.log(JSON.stringify(input.value, null, 2));
  input.value.details.forEach((item, index) => {
    item.nama = props.pasien.namapasien;
    item.tenagaMedis = userLogin;
    item.ttdpenerimaedukasi = props.pasien.namapasien;
  });
  isAlltemplate.value = false;
  showModalTemplateFix.value = false;
  showModalTemplate.value = false;
  H.alert('success', 'Berhasil ditambahkan');
};


const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(
    `/emr/hapus-template`, json).then((response: any) => {
      if (response.status !== 500) {
        isLoading.value = false
        isAlltemplate.value = false;
        H.alert('sucess', response.message);
        showModalTemplate.value = false;
        showModalTemplateFix.value = false;
      } else {
        H.alert('danger', response.message);
      }
    }).catch((e: any) => {
      isLoading.value = false
      H.alert('danger', e);
    })
}

const preview = () => {
  previewEdukasi.value = [];
  riwayatCatatanEdukasi.value.forEach((riwayat) => {
    if (riwayat.details) {
      previewEdukasi.value.push(...riwayat.details);
    }
  });
  console.log(riwayatCatatanEdukasi.value);
  isDialogPreview.value = true;
};

onMounted(() => {
  setAutoFill()
  getDataExist()
  fetchPasien()
})



</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

table.assesment {
  border-collapse: collapse;
  width: 100%;
}


.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
  // border: 1px solid black;
}

.assesment th,
.assesment td {
  padding: 8px;
  vertical-align: middle !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

.table.is-borderless {
  border: none !important;
  background-color: transparent;
}

.table.is-borderless th,
tr,
td {
  border: none !important;
  background-color: transparent !important;
}

// tr:hover {
//     background-color: #f5f5f5;
// }</style>
