<template>
  <ConfirmDialog group="templating">
    <template #message="slotProps">
      <div style="width:500px;height:300px;">
        <table style="width:100%;height:100%;border-collapse: collapse">
          <tr>
            <td style="text-align:center;vertical-align:middle">
              <i :class="slotProps.message.icon" style="font-size:125px;text-align:center;color:#FDDA0D"></i>
            </td>
          </tr>
          <tr>
            <td style="padding:7px;text-align:center">
              <p style="font-size:large">{{ slotProps.message.message }}</p>
            </td>
          </tr>
        </table>
      </div>
    </template>
  </ConfirmDialog>

  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Catatan Komplementer</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column is-12" style="margin: 10px;">
          <div class="columns is-mobile is-centered">
            <div class="column is-auto" style="display: flex; gap: 5px;"> <!-- Flexbox with gap -->
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

        <div class="column is-12 mb-0">
          <h1>Nama Template&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin
              membuat
              template</span></h1>
          <VField>
            <VControl>
              <VTextarea v-model="input.namatemplate" rows="1">
              </VTextarea>
            </VControl>
          </VField>
        </div>

        <hr>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-4">
              <h1 style="font-weight: bold;">Tanggal Kedatangan</h1>
              <VField>
                <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Jam Kedatangan</h1>
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
              <h1 style="font-weight: bold;">Jam Asesmen Awal</h1>
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
        </div>

        <div class="column is-12 mb-0 mt-0">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0">
                  <h1 style="font-weight: bold;">Rujukan</h1>
                </div>
                <div class="column is-4 pt-0">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan" true-value="YA" label="Ya"
                        color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4 pt-0">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan" true-value="TIDAK"
                        label="Tidak" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 pl-0 pt-0" v-if="input.kebrujukan == 'YA'">
              <div class="column is-12 pt-0">
                <h1 class="bold">Dari</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="heightinput input" placeholder="Keterangan Rujukan"
                      v-model.number="input.TBKetRujukanDari" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-6" v-else-if="input.kebrujukan == 'TIDAK'">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0">
                  <h1 class="bold">
                    Kedatangan
                  </h1>
                </div>
                <div class="column is-4 pt-0">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujuklanjutan" @change=""
                        true-value="SENDIRI" label="Sendiri" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4 pt-0">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujuklanjutan" true-value="DIANTAR"
                        label="Diantar" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12" v-if="input.kebrujukan == 'TIDAK' && input.kebrujuklanjutan == 'DIANTAR'">
              <div class="columns is-multiline">
                <div class="column is-2 center">
                  <h3 style="font-weight: bold;">
                    Diantar Oleh
                  </h3>
                </div>
                <div class="column is-10">
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" placeholder="Diantar Oleh"
                        v-model.number="input.TBDiantarOleh" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12">
          <div class="column is-12" align="center">
            <ImgDraw elemenID="Gambar" height="750" width="900" imageSrc="/images/simrs/catatan-komplementer.png" />
          </div>
          <h1 style="font-weight:bold">Hasil Pemeriksaan</h1>
          <div class="column is-12">
            <VField>
              <VControl>
                <VTextarea v-model="input.hasilpemeriksaan" placeholder="" rows="5">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>

        <!-- form baru -->

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
            <table class="tg table-tg" style="border-collapse: collapse; width: 100%;" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" style="width: 5%; padding: 9px;">#</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">Tanggal Input</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">Tanggal Registrasi</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">No Registrasi</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">No EMR</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">Dokter</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">Penyakit</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">Section</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="resep in listTemplate" :key="resep.id">
                  <td style="width: 5%; text-align: center; padding: 4px;">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.created_at }}</span>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.registrasi.tglregistrasi }}</span>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.registrasi.noregistrasi }}</span>
                  </td>
                  <td style="width: 20%; text-align: center; padding: 9px;">
                    <span>{{ resep.pasien.nocm }}</span>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.dpjpUtama }}</span>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.riwayatpenyakit }}</span>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.registrasi.namaruangan }}</span>
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
              <VIconButton type="button" raised circle icon="fas fa-eye" @click="showTemplate(slotProps.data)"
                color="success" v-tooltip-prime.top="'Lihat'">
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

  <VModal :open="showModalObat" title="Riwayat Obat" :noclose="true" size="large" actions="right"
    @close="showModalObat = false">
    <template #content>
      <DataTable :pt="{
        table: { style: 'min-width: 50rem; min-height: 10rem;' },
        column: {
          bodycell: ({ state }) => ({
            class: [{ 'pt-0 pb-0': state['d_editing'] }]
          })
        }
      }" v-model:selection="ObatSelected" :value="listSIMRSLama" :metaKeySelection="metaKey" :rows="10" paginator
        tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listSIMRSLama.length" responsiveLayout="stack"
        breakpoint="960px">
        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
        <Column field="namaobat" header="Nama" :sortable="true">
          <template #body="slotProps">
            <span>{{ slotProps.data.namaobat + ' - ' + slotProps.data.jenisobat }}</span>
          </template>
        </Column>
        <Column field="noorder" header="No Resep" :sortable="true"></Column>
        <Column field="noregistrasi" header="No Registrasi" :sortable="true"></Column>
        <Column field="namalengkap" header="Dokter" :sortable="true" style="width: 150px;;"></Column>
        <Column field="tglorder" header="Tanggal" :sortable="true">
          <template #body="slotProps">
            <span>{{ H.formatDateToLocalString(slotProps.data.tglorder) }}</span>
          </template>
        </Column>
      </DataTable>
    </template>
    <template #action>
      <VButton type="button" color="primary" raised @click="addToInput()">
        Tambah
      </VButton>
    </template>
  </VModal>

  <VModal :open="showModalDetailTemplate" title="Detail Template" :noclose="true" size="large" actions="right"
    @close="selectedTemplate = null; showModalDetailTemplate = false">
    <template #content>
      <DetailTemplate v-if="selectedTemplate" :input="selectedTemplate" :items="item" :kelompokUser="kelompokUser" />
      <VPlaceloadWrap v-else v-for="key in 6" :key="key">
        <VPlaceload width="100%" height="50px" class="mx-1 mt-2" />
      </VPlaceloadWrap>
    </template>
  </VModal>


</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import DetailTemplate from '../page-emr-plugins/showdetail/asesmen-keperawatan-rajal.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import moment from 'moment'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import ImgDraw from '../page-emr-plugins/img-draw.vue'


useHead({
  title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
let detailStatusFungsional = ref(EMR.detailStatusFungsional())
let statusFungsional: any = ref(EMR.statusFungsional())

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

const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const route = useRoute()
const pasien: any = ref({})
const d_pegawai: any = ref([])
const metaKey = ref(true);
const loadData: any = ref(true)
const listSIMRSLama: any = ref([])
const showModalObat: any = ref(false);
const ObatSelected: any = ref()
const isAlltemplate: any = ref(false);
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
//
const COLLECTION: any = ref('CatatanKomplementer') //table mongodb

const NOREC_EMRPASIEN: any = ref('')
const selectedTemplate: any = ref();
const input: any = ref({
  kebjamKedatangan: new Date(),
  kebjamAsesmenAwal: new Date(),
  kebtanggalKedatangan: new Date(),
})

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const filterMenu: any = ref('')
const showModalDetailTemplate: any = ref(false);

const confirm = useConfirm();
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
let listHipertensi: any = ref(EMR.hipertensi())
let listDiabetes: any = ref(EMR.diabetes())
let listDyslipidemia: any = ref(EMR.dyslipidemia())
let listDuaPilihan: any = ref(EMR.duaPilihan())
let listAgama: any = ref(EMR.agama())
let listStatus: any = ref(EMR.status())
let listKeluarga: any = ref(EMR.keluarga())
let listTempatTinggal: any = ref(EMR.tempatTinggal())
let listPsikologis: any = ref(EMR.psikologis())
let listMore: any = ref(EMR.more())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let resikoNutrisional: any = ref(EMR.resikoNutrisional())
let fungsionalPertama: any = ref(EMR.fungsionalPertama())
let listRangeNilaiPoin: any = ref(EMR.nilaiPoin())
let listDESCNilai: any = ref(EMR.descNilai())
let pertanyaanA: any = ref(EMR.pertanyaanA())
let pertanyaanB: any = ref(EMR.pertanyaanB())
let pertanyaanC: any = ref(EMR.pertanyaanC())
let dropdownAllo: any = ref([
  "Suami/Istri",
  "Orang tua",
  "Anak",
  "Lainnya"
])

console.log('form_name', props.FORM_NAME)

const loadRiwayat = async () => {
  isLoading.value = true
  let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  isLoading.value = false
  if (responsex.length) {
    input.value = responsex[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = responsex[0].emrpasienfk
    }
    await loadGambar("Gambar", input.value.Gambar)
  } else {
    // isLoading.value = true
    // const responseTglRuangan = await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
    // const responseHistori = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
    // isLoading.value = false
    // if (responseTglRuangan.length && responseHistori.length) {
    //   console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan)
    //   var tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
    //   var convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
    //   var tgl_Sekarang = moment();
    //   isLoading.value = false
    //   const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    //   if (responseTglRuangan[0].registrasi.namaruangan.trim() == H.setObjectRegistrasi(pasien.value.registrasi).namaruangan.trim() && calculateDays < 90) {
    //     confirm.require({
    //       message: 'Asesmen Keperawatan sudah pernah diinput ' + calculateDays + ' hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya ?',
    //       group: 'templating',
    //       header: 'Informasi Asesmen Keperawatan',
    //       icon: 'pi pi-exclamation-circle',
    //       accept: () => {
    //         if (responseHistori.length) {
    //           input.value = responseHistori[0] //set ke inputan
    //           input.value.namatemplate = ''
    //           // console.log(input.value)
    //           isLoading.value = false // Set isLoading to false after loading history data
    //         } else {
    //           H.alert('warning', 'Data tidak ada')
    //           isLoading.value = false
    //         }
    //       },
    //       reject: () => {
    //         isLoading.value = false // Ensure isLoading is set to false if rejected
    //       }
    //     })
    //   }
    // } else {
    //   console.log('Data EMR sebelumnya tidak ada!')
    //   isLoading.value = false // Set isLoading to false when no previous data is found
    // }
    // console.log("Ruangan pasien sekarang : " + H.setObjectRegistrasi(pasien.value.registrasi).namaruangan)
  }
}

const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById('Gambar');
  console.log("SIG CANVAS", sigCanvas);
  console.log("SIG CANVAS", sigCanvas);
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    let imagess = input.value.Gambar
    let background = new Image();
    background.src = imagess
    background.onload = function () {
      context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height);
    }
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  // if (input.value.kebrujukan == 'TIDAK') {
  //   if (input.value.kebrujuklanjutan == 'DIANTAR') {
  //     input.value.kebketrujukan = input.value.kebketrujukan;
  //   }
  // }

  // if (input.value.kebpilihanallo == 'Lainnya') {
  //   input.value.kebpilihanallo = input.value.keballoanamnesis;
  // }

  // if (input.value.kualitasnyeri == 'LAINNYA') {
  //   input.value.kualitasnyeri = input.value.kualitasnyerilain
  // }

  // if (input.value.pembiayaankesehatan == 'ASURANSI') {
  //   input.value.pembiayaankesehatan = input.value.ketpembiayaankesehatan
  // }

  object = input.value
  object.nocm = pasien.value.nocm
  object['Gambar'] = H.tandaTangan().get("Gambar");

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
  console.log(json)

  isLoading.value = true
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
    // NOREC_EMRPASIEN.value = response.norec_emr
  }).catch((e: any) => {
    isLoading.value = false
  })

  // console.log(resultValue)
}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
  let ID = input.value.id ? input.value.id : ''
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
  isLoading.value = true;
  try {
    const responselast = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`);
    if (responselast.length) {
      listTemplate.value = responselast; // Set ke inputan
      showModalTemplate.value = true;
    } else {
      H.alert('warning', 'Data tidak ada');
    }
  } catch (error) {
    console.error(error);
    H.alert('error', 'Terjadi kesalahan saat mengambil data');
  } finally {
    isLoading.value = false;
  }
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  try {
    const responselast = await useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&isAll=${isAlltemplate.value}`);
    if (responselast.length) {
      responselast.forEach((item: any, index: number) => {
        item.no = index + 1;
      });
      listTemplateFix.value = responselast; // Set ke inputan
      showModalTemplateFix.value = true;
    } else {
      H.alert('warning', 'Data tidak ada');
    }
  } catch (error) {
    console.error(error);
    H.alert('error', 'Terjadi kesalahan saat mengambil data');
  } finally {
    isLoading.value = false;
  }
}

const showTemplate = async (d: any) => {
  selectedTemplate.value = d;
  console.log("FROM D", d);

  showModalDetailTemplate.value = true;
}

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
  console.log(norec_emr)
}

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
    d_pegawai.value = response
  })
}
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
        pilihTemplateFix();
      } else {
        H.alert('danger', response.message);
      }
    }).catch((e: any) => {
      isLoading.value = false
      H.alert('danger', e);
    })
}

const addTemplate = (response) => {
  console.log(response);
  input.value = response;
  input.value.namatemplate = null;
  isAlltemplate.value = false;
  showModalTemplateFix.value = false;
  H.alert('success', 'Berhasil ditambahkan');
};
onBeforeMount(async () => {
  try {
    await loadRiwayat()
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


onMounted(() => {
  fetchPasien()
})
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.center {
  text-align: center;
}

.vm {
  vertical-align: middle;
}

.bold {
  font-weight: bold;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

.table.is-borderless th,
tr,
td {
  border: none !important;
  background-color: transparent !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 0px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
}

.tg2 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg2 td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg2 th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.fontcheckbox {
  padding: 0px;
  padding-top: 5px;
  padding-left: 5px;
}

.fontcheckbox label {
  color: black;
}


.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: middle
}

mark {
  background-color: yellow;
  /* Pastikan warna yang Anda inginkan ditulis di sini */
  color: black;
  /* Warna teks jika perlu */
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
  -moz-appearance: textfield;
  /* Firefox */
}
</style>