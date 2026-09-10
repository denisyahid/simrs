<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Intervensi dan Asesmen Ulang Nyeri</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column is-12" style="margin-top: 30px;">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1 class="mb-3 emr">Nama Template&emsp;&emsp;**Hanya diisi jika ingin membuat template</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.namatemplate" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
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
        <div class="column is-12" style="overflow-x: auto; max-width: 150%;">
          <table style="width: 300%;">
            <thead style="text-align: center;">
              <tr>
                <th class="th-pri" rowspan="2">Skor Nyeri</th>
                <th class="th-pri" rowspan="2">Pasero-Mc Caffery Opioid-Induced Sedation Scale (POSS)</th>
                <th class="th-pri" rowspan="2">Intervensi Non-Farmakologi</th>
                <th class="th-pri" rowspan="2">Pengkajian Ulang</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td class="td-pri">
                  <span>(NRS, WBS, FLACC) :</span>
                  <div class="column is-12">
                    <span>0 = Tidak nyeri</span>
                  </div>
                  <div class="column is-12">
                    <span>1-4 = Nyeri ringan</span>
                  </div>
                  <div class="column is-12">
                    <span>5-6 = Nyeri sedang</span>
                  </div>
                  <div class="column is-12">
                    <span>7-10 = Nyeri berat</span>
                  </div>
                  <span>BPS :</span>
                  <div class="column is-12">
                    <span>&lt;5 = Pasien bebas nyeri</span>
                  </div>
                  <div class="column is-12">
                    <span>≥5 = Pasien nyeri perlu diterapi</span>
                  </div>
                  <span>NPA:</span>
                  <div class="column is-12">
                    <span>&lt;5 = Nyeri ringan (Nurse Comfort Measure)</span>
                  </div>
                  <div class="column is-12">
                    <span>>5 = Nyeri sedang (Paracetamol)</span>
                  </div>
                  <div class="column is-12">
                    <span>>10 = Nyeri berat (NCM, paracetamol, narkotik)</span>
                  </div>
                </td>
                <td class="td-pri">
                  <div class="column is-12">
                    <span>4 : Somnolent, minimal/tidak respon terhadap rangsangan fisik</span>
                  </div>
                  <div class="column is-12">
                    <span>3 : Sering mengantuk, bisa dibangunkan, mudah tertidur saat sedang bicara</span>
                  </div>
                  <div class="column is-12">
                    <span>2 : Agak mengantuk, mudah dibangunkan</span>
                  </div>
                  <div class="column is-12">
                    <span>1 : Bangun dan sadar</span>
                  </div>
                  <div class="column is-12">
                    <span>S : Tidur, mudah dibangunkan</span>
                  </div>
                  <div class="column is-12">
                    <span>0 : Tidak menggunakan sedasi</span>
                  </div>
                </td>
                <td class="td-pri">
                  <div class="column is-12">
                    <span>1 : Pemberian kompres dingin</span>
                  </div>
                  <div class="column is-12">
                    <span>2 : Pemberian kompres hangat</span>
                  </div>
                  <div class="column is-12">
                    <span>3 : Pemberian posisi</span>
                  </div>
                  <div class="column is-12">
                    <span>4 : Pijat</span>
                  </div>
                  <div class="column is-12">
                    <span>5 : Pemberian terapi musik</span>
                  </div>
                  <div class="column is-12">
                    <span>6 : TENS</span>
                  </div>
                  <div class="column is-12">
                    <span>7 : Relaksasi dan pernapasan</span>
                  </div>
                </td>
                <td class="td-pri">
                  <div class="column is-12">
                    <span>1 : Nyeri ringan diulang setiap 6 jam</span>
                  </div>
                  <div class="column is-12">
                    <span>2 : Nyeri sedang, setiap 60 menit setelah obat diberikan sampai intensitas nyeri ringan yang
                      tolerable</span>
                  </div>
                  <div class="column is-12">
                    <span>3 : Nyeri berat setiap 30 menit setelah obat diberikan sampai intensitas nyeri ringan yang
                      tolerable</span>
                  </div>
                </td>
              </tr>
            </tfoot>
          </table>
          <table style="width: 300%;">
            <thead style="text-align: center;">
              <tr>
                <th class="th-pri" rowspan="2">Aksi</th>
                <th class="th-pri" rowspan="2">Tanggal & Jam</th>
                <th class="th-pri" rowspan="2">Skor Nyeri</th>
                <th class="th-pri" rowspan="2">Skor Sedasi</th>
                <th class="th-pri" rowspan="2">Tekanan Darah</th>
                <th class="th-pri" rowspan="2">Nadi</th>
                <th class="th-pri" rowspan="2">Suhu</th>
                <th class="th-pri" rowspan="2">Respirasi</th>
                <th class="th-pri" colspan="2">Perawat / Bidan</th>
                <th class="th-pri" rowspan="2">Tanggal & Jam</th>
                <th class="th-pri" colspan="3">Intervensi Farmakologi</th>
                <th class="th-pri" rowspan="2">Intervensi Non Farmakologi</th>
                <th class="th-pri" colspan="2">Perawat / Bidan</th>
                <th class="th-pri" rowspan="2">Wakti Kaji Ulang</th>
              </tr>
              <tr>
                <th class="th-pri">Nama Perawat / Bidan</th>
                <th class="th-pri">Paraf</th>
                <th class="th-pri">Nama Obat</th>
                <th class="th-pri">Dosis & Frekuensi</th>
                <th class="th-pri">Rute</th>
                <th class="th-pri">Nama Perawat / Bidan</th>
                <th class="th-pri">Paraf</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="18" class="td-pri">
                  <div class="columns is-multiline">
                    <div class="column is-12 is-flex is-align-items-center" v-if="filterData.length == 0">
                      <VButtons style="justify-content:space-around">
                        <VIconButton type="button" raised circle icon="feather:plus"
                          @click="addNewItem(input.details.length - 1)" color="info" v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                      </VButtons>
                    </div>
                    <div class="column is-9">
                      <VField label="Periode" style="margin-bottom: 6px;" />
                      <VDatePicker v-model="item.qFilterTgl" is-range color="pink" locale="id" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                          <VField addons>
                            <VControl icon="feather:calendar">
                              <VInput :value="inputValue.start" v-on="inputEvents.start" />
                            </VControl>
                            <VControl>
                              <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                            </VControl>
                            <VControl icon="feather:calendar">
                              <VInput :value="inputValue.end" v-on="inputEvents.end" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </div>
                  </div>
                </td>
              </tr>
              <tr v-for="(item, index) in filterData" :key="index">
                <td class="td-pri">
                  <VIconButton circle icon="feather:plus" color="primary" raised bold @click="addNewItem(index)"
                    class="ml-1 mr-1" v-tooltip-prime.top="'Tambah'" :loading="isLoading" />
                  <VIconButton circle icon="feather:trash-2" color="danger" raised bold @click="removeItem(index)"
                    class="ml-1 mr-1" v-tooltip-prime.top="'Hapus'" :loading="isLoading" v-if="item.no > 1" />
                </td>
                <td class="td-pri">
                  <VField>
                    <VDatePicker v-model="item.tanggalJam" mode="dateTime" style="width: 100%" trim-weeks
                      :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField>
                    <VControl>
                      <VInput v-model="item.skornyeri" class="input" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField>
                    <VControl>
                      <VInput v-model="item.skorsedasi" class="input" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField>
                    <VControl>
                      <VInput v-model="item.tekanandarah" class="input" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField>
                    <VControl>
                      <VInput v-model="item.nadi" class="input" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField>
                    <VControl>
                      <VInput v-model="item.suhu" class="input" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField>
                    <VControl>
                      <VInput v-model="item.respirasi" class="input" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri" colspan="2">
                  <VField>
                    <VControl>
                      <AutoComplete v-model="item.parafPerawatNama" :suggestions="d_pegawai"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..." />
                    </VControl>
                  </VField>
                </td>
                <!-- <td class="td-pri">
                  <VField>
                    <VControl>
                      <TandaTangan :elemenID="`parafPerawat_${index}`" :width="'150'" :height="'150'" class="dek" />
                    </VControl>
                  </VField>
                </td> -->
                <td class="td-pri">
                  <VField>
                    <VDatePicker v-model="item.tanggalJamKaji" mode="dateTime" style="width: 100%" trim-weeks
                      :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField>
                    <VControl>
                      <AutoComplete v-model="item.namaObat" :suggestions="d_ObatRS" @complete="fetchObat($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField>
                    <VControl>
                      <VInput v-model="item.dosisFrekuensi" class="input" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField>
                    <VControl>
                      <VInput v-model="item.rute" class="input" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri">
                  <VField>
                    <VControl>
                      <VInput v-model="item.intervensiNonFarmakologi" class="input" />
                    </VControl>
                  </VField>
                </td>
                <td class="td-pri" colspan="2">
                  <VField>
                    <VControl>
                      <AutoComplete v-model="item.parafBidanNama" :suggestions="d_pegawai"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..." />
                    </VControl>
                  </VField>
                </td>
                <!-- <td class="td-pri">
                  <VField>
                    <VControl>
                      <TandaTangan :elemenID="`parafBidan_${index}`" :width="'150'" :height="'150'" class="dek" />
                    </VControl>
                  </VField>
                </td> -->
                <td class="td-pri">
                  <VField>
                    <VDatePicker v-model="item.waktuKajiUlang" mode="dateTime" style="width: 100%" trim-weeks
                      :max-date="new Date()" :disabled="item.disabledForm">
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
                </td>
              </tr>
            </tfoot>
          </table>
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
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick } from 'vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
import { v4 as uuidv4 } from 'uuid';

useHead({
  title: 'Intervensi dan Asesmen Ulang Nyeri - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
let detailStatusFungsional = ref(EMR.detailStatusFungsional())
let statusFungsional: any = ref(EMR.statusFungsional())
let DiagnosaKeperawatanRanap: any = ref(EMR.DiagnosaKeperawatanRanap())
let RencanaKeperawatan: any = ref(EMR.RencanaKeperawatan())
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
  disability: [],
  qFilterTgl: {
    start: new Date(new Date().setDate(new Date().getDate() - 3)),
    end: new Date()
  }
})

const COLLECTION: any = ref('IntervensiDanAsesmenUlangNyeri')

const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{
    id: uuidv4(),
    no: 1,
  }],
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const filterMenu: any = ref('')
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const d_ObatRS: any = ref([])
const dataTTD = ref([])
const dataDetailTTD = ref([])

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

const loadRiwayat = () => {
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      dataDetailTTD.value = response[0].details
      await nextTick(() => {
        dataDetailTTD.value.forEach((item2, index2) => {
          if (!item2.parafBidan) {
            H.tandaTangan().set(`parafBidan_${index2}`, item2.parafBidan);
          } else {
            H.tandaTangan().set(`parafBidan_${index2}`, item2.parafBidan);
          }
          if (!item2.parafPerawat) {
            H.tandaTangan().set(`parafPerawat_${index2}`, item2.parafPerawat);
          } else {
            H.tandaTangan().set(`parafPerawat_${index2}`, item2.parafPerawat);
          }
        });
      })
    } else {
      isLoading.value = false
    }
  })
}

const filterData = computed(() => {
  let start = new Date(item.qFilterTgl.start);
  start.setHours(0, 0, 0, 0);

  let end = new Date(item.qFilterTgl.end);
  end.setHours(23, 59, 59, 999);

  const filtered = input.value.details.filter((detail) => {
    const detailDate = new Date(detail.tanggalJam);
    return detailDate >= start && detailDate <= end;
  });

  return filtered;
});

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
  let pushData: any = []
  if (object.details.length >= 0) {
    object.details.forEach((element: any, i: any) => {
      const parafBidan = H.tandaTangan().get(`parafBidan_${i}`);
      const parafPerawat = H.tandaTangan().get(`parafPerawat_${i}`);
      element.parafBidan = parafBidan
      element.parafPerawat = parafPerawat
    })
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
  console.log(json)

  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
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

const fetchObat = async (filter: any) => {
  const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  response.map((element: any) => {
    element.label = element.productname,
      element.value = element.id
  })
  d_ObatRS.value = response
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

const handlerRujukanChange = (val: any) => {
  console.log(val);
  if (val === "YA") {

  }
}

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
}

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details.length + 1,
    tanggalJam: new Date(),
    id: uuidv4(),
  });
}

const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

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

fetchPasien()

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

.grey-background {
  background-color: #d3d3d3;
  /* Grey color */
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
</style>
<style lang="scss">
.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
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
</style>
