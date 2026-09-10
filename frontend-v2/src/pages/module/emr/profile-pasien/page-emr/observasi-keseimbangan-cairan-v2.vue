<template>
  <VCard>
    <div class="form-layout is-stacked-2" v-if="!props.hideButtons">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" @simpan="simpan"
                @kembaliKeun="kembaliKeun" :isLoading="isLoading"></ButtonEmr>
              <VButton type="button" rounded outlined color="dark" raised icon="feather:save" :loading="isLoading"
                @click="tutupJadwal()"> Tutup Jadwal Monitoring
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="previewTutupJadwalClick()"> Preview
              </VButton>
            </div>
          </div>
        </div>

      </div>
    </div>
    <Dialog v-model:visible="previewTutupJadwal" header="Preview" :style="{ width: '100rem' }">
      <PreviewObservasiKeseimbanganCairan :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :listTutupJadwal="listTutupJadwal" @load-riwayat="loadRiwayatTutupJadwal"></PreviewObservasiKeseimbanganCairan>
    </Dialog>

    <span style="font-weight: bold; color: darkgray;">VI. Balans Cairan</span>
    <hr style="border-top: 1px dashed red;background-color:white" class="mb-1 mt-0 pt-0">
    <div class="column columns is-multiline">
      <div class="column is-12 pl-0 pb-0 ml-5" style="text-align: left;">
        <VButtons style="justify-content:start">
          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
            v-tooltip.bubble="'Tambah '">
          </VIconButton>
          <VIconButton class="mt-1" v-if="input.details.length > 1" type="button" raised circle icon="feather:trash"
            @click="removeItem(index)" color="danger">
          </VIconButton>
        </VButtons>
      </div>
      <div class="column is-12" style="overflow:auto">
        <table class="table-bordered" style="width: 100%; border-collapse: collapse; border: 1px solid black;">
          <thead>
            <tr>
              <th class="col-frozen" style="text-align: center; height: 5rem; width: 200px;border: 1px solid black; background-color: #e8e7e6;">
                Tanggal Penginputan
              </th>
              <th style="border: 1px solid black; height: 5rem; width: 200px; background-color: #62b3f5" v-for="(data, index) in input.details" :key="index">
                <VDatePicker v-model="data['DT_INTAKE']" mode="datetime" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" style="width: 200px; text-align:center;" />
                    </VControl>
                  </template>
                </VDatePicker>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="col-frozen" style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #f5ef4c">
                INTAKE
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #f5ef4c"></td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 2rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #92e0a7">
                Makan
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #92e0a7"></td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #e8e7e6; ">
                  <div style="margin-top: 15px;">
                    Oral
                  </div>
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #e8e7e6; padding: 10px">
                <VControl>
                    <VInput type="text" class="input" v-model="data['TB_ORAL']" />
                </VControl>
              </td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #e8e7e6; ">
                  <div style="margin-top: 15px;">
                    Enteral
                  </div>
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #e8e7e6; padding: 10px">
                <VControl>
                    <VInput type="text" class="input" v-model="data['TB_ENTERAL']" />
                </VControl>
              </td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #e8e7e6; ">
                  <div style="margin-top: 15px;">
                    Jumlah/Jam
                  </div>
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #e8e7e6; padding: 10px">
                <VControl>
                    <VInput type="text" class="input" v-model="data['JUMLAH31']" />
                </VControl>
              </td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 2rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #92e0a7">
                Transfusi
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #92e0a7"></td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #e8e7e6; padding: 10px">
                <VControl>
                  <VInput type="text" class="input" v-model="input.freeTransfusiI" placeholder="I"/>
                </VControl>
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #e8e7e6; padding: 10px">
                <VControl>
                    <VInput type="text" class="input" v-model="data['I']" />
                </VControl>
              </td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #e8e7e6; padding: 10px">
                <VControl>
                  <VInput type="text" class="input" v-model="input.freeTransfusiII" placeholder="II"/>
                </VControl>
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #e8e7e6; padding: 10px">
                <VControl>
                    <VInput type="text" class="input" v-model="data['II']"/>
                </VControl>
              </td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #e8e7e6; padding: 10px">
                <VControl>
                  <VInput type="text" class="input" v-model="input.freeTransfusiIII" placeholder="III"/>
                </VControl>
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #e8e7e6; padding: 10px">
                <VControl>
                    <VInput type="text" class="input" v-model="data['III']"/>
                </VControl>
              </td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #e8e7e6; padding: 10px">
                <VControl>
                  <VInput type="text" class="input" v-model="input.freeTransfusiIV" placeholder="IV"/>
                </VControl>
                  <!-- <div style="margin-top: 15px;">
                    IV
                  </div> -->
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #e8e7e6; padding: 10px">
                <VControl>
                    <VInput type="text" class="input" v-model="data['IV']" />
                </VControl>
              </td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #e8e7e6; padding: 10px">
                <VControl>
                  <VInput type="text" class="input" v-model="input.freeTransfusiV" placeholder="V"/>
                </VControl>
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #e8e7e6; padding: 10px">
                <VControl>
                    <VInput type="text" class="input" v-model="data['V']" />
                </VControl>
              </td>
            </tr>
            <tr v-for="(dataTransfusi, colIndex) in input.detailsTransfusi" :key="colIndex">
              <td class="col-frozen"
                style="height: 4.9rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6; padding: 10px">
                <VControl>
                  <VInput type="text" class="input" v-model="dataTransfusi[`transfusiFree${index + 1}_${colIndex + 1}`]"
                  :placeholder="toRoman(colIndex + 6)"/>
                </VControl>
                <!-- <div style="margin-top: 15px;">
                  {{ toRoman(colIndex + 6) }}
                </div> -->
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #e8e7e6; padding: 10px">
                <VControl>
                  <VInput type="text" class="input"
                    v-model="dataTransfusi[`transfusi${index + 1}_${colIndex + 1}`]" />
                </VControl>
              </td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
                Jumlah/jam
                <div class=" columns is-multiline is-12" style="text-align: center;  ">
                  <div class="column is-6" style="text-align: center;">
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemTransfusi()"
                      color="info" v-tooltip.bubble="'Tambah '">
                    </VIconButton>
                  </div>
                  <div class="column is-6" style="text-align: center;">
                    <VIconButton v-if="input.detailsTransfusi.length >= 1" type="button" raised circle
                      icon="feather:trash" @click="removeItemTransfusi(index)" color="danger">
                    </VIconButton>
                  </div>
                </div>
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="height: 5rem; background-color: #e8e7e6; padding: 10px">
                <VControl>
                  <VInput type="text" class="input" v-model="data['JUMLAH32']" />
                </VControl>
              </td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 2rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #92e0a7">
                Parenteral
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #92e0a7"></td>
            </tr>
            <tr>
              <td class="col-frozen" style="height: 5rem; background-color: #62b3f5; padding: 5px; padding-top: 8px; width: 200px;">
                <div v-for="(data, index) in input.detailss" :key="index" style="margin-bottom: 10px;">
                  <VControl>
                    <VInput type="text" class="input" v-model="data['FREETEXT']" />
                  </VControl>
                </div>
                <span style="font-weight: bold; font-size: 11pt; text-align: center;">
                  Jumlah
                </span>
                <div class=" columns is-multiline is-12" style="text-align: center;  ">
                  <div class="column is-6" style="text-align: center;">
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemPar()" color="info"
                      v-tooltip.bubble="'Tambah '">
                    </VIconButton>
                  </div>
                  <div class="column is-6" style="text-align: center;">
                    <VIconButton v-if="input.detailss.length > 1" type="button" raised circle icon="feather:trash"
                      @click="removeItemPar(input.detailss.length - 1)" color="danger">
                    </VIconButton>
                  </div>
                </div>
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="height: 5rem; background-color: #62b3f5">
                <div v-for="(row, rowIndex) in input.detailss" :key="rowIndex" style="margin-top:10px; padding-left: 10px; padding-right: 10px;">
                  <VControl>
                    <VInput type="text" class="input" v-model="row[`FREE${index + 1}_${rowIndex + 1}`]" />
                  </VControl>
                </div>
              </td>
            </tr>
            <tr>
              <td class="col-frozen" style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #92e0a7">
                Total Input
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #92e0a7; padding:10px; text-align: center;">
                {{ data['totalInput'] }}
              </td>
            </tr>
            <tr>
              <td class="col-frozen" style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #f5ef4c">
                Output
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #f5ef4c"></td>
            </tr>
            <tr>
              <td class="col-frozen"
                style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5;">
                <div v-for="(data, index) in input.detailOutputText" :key="index" style="padding: 10px;">
                  <VControl>
                    <VInput type="text" class="input" v-model="data['FREETEXT']" />
                  </VControl>
                </div>
                Jumlah
                <div class=" columns is-multiline is-12" style="text-align: center;  ">
                  <div class="column is-6" style="text-align: center;">
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemOut()" color="info"
                      v-tooltip.bubble="'Tambah '">
                    </VIconButton>
                  </div>
                  <div class="column is-6" style="text-align: center;">
                    <VIconButton v-if="input.detailout.length > 1" type="button" raised circle icon="feather:trash"
                      @click="removeItemOut(input.detailout.length - 1)" color="danger">
                    </VIconButton>
                  </div>
                </div>
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="height: 5rem; background-color: #62b3f5">
                <div v-for="(rows, rowsIndex) in input.detailout" :key="rowsIndex" style="padding: 10px">
                  <VControl>
                    <VInput type="text" class="input" v-model="rows[`JUMLAH2${index + 1}_${rowsIndex + 1}`]" />
                  </VControl>
                </div>
              </td>
            </tr>
            <tr>
              <td class="col-frozen" style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #92e0a7">
                Total Output
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #92e0a7; padding:10px; text-align: center;">
                {{ data['totalOutput'] }}
              </td>
            </tr>
            <tr>
              <td class="col-frozen" style="height: 2rem; border-bottom: 1px solid black;
                font-weight: bold; font-size: 11pt; text-align: center;
                background-color: #f5ef4c">
                BALANCE
              </td>
              <td v-for="(data, index) in input.details" :key="index" style="background-color: #f5ef4c; padding: 10px;">
                <VControl>
                  <VInput type="text" class="input" v-model="data['TB']" />
                </VControl>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </VCard>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, watchEffect, nextTick, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import Fieldset from 'primevue/fieldset';
import * as ICU from '../page-emr-plugins/monitoring-icu'
import PreviewMonitoringIcu from '../page-emr/preview-monitoring-icu.vue'
import PreviewObservasiKeseimbanganCairan from '../page-emr/preview-observasi-keseimbangan-cairan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import InputText from 'primevue/inputtext';
import { alatBantu } from '../page-emr-plugins/asesmen-awal-keperawatan-rawat-inap'
import { followersList } from '/@src/data/widgets/ui/followers'
import { tagList1, tagList2 } from '/@src/data/widgets/ui/tagList'
import { tabs } from '/@src/data/widgets/ui/tabList'
import { iconList } from '/@src/data/widgets/ui/menuList'
// import { notifications } from '/@src/data/widgets/ui/notificationList'
import { trendWidgetChartOptions } from '/@src/data/widgets/charts/trendWidgetChart'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useConfirm } from 'primevue/useconfirm'
import Dialog from 'primevue/dialog';
import Chart from 'primevue/chart';
import moment from 'moment'
import { v4 as uuidv4 } from 'uuid';


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let waktu = ref(ICU.waktu())
let listDescripWaktu = ref(ICU.listDescripWaktu())
let description = ref(ICU.dataTableDescrip())
let statusRespirasi = ref(ICU.statusRespirasi())
let hemodinamik = ref(ICU.Hemodinamik())
let tableBalansCairan = ref(ICU.tableBalansCairan())
let titleMore = ref(ICU.titleMore())

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
    hideButtons?: boolean
    dataTertutup?: any
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
    hideButtons: false,
    dataTertutup: {},
  }
)
const previewTutupJadwal = ref(false);
const listTutupJadwal: any = ref([]);
const inputVitalSign = ref(false);
const listVital: any = ref([])
const modalInput: any = ref(false)
const chartData = ref();
const chartOptions = ref();
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isLoadingHolder: any = ref(false)
const medikasi = ref([]);
const d_Obat: any = ref([])
const d_AlatBantu: any = ref([])
const d_CRT: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const formTambahAlat: any = ref(false)
const formIntake: any = ref(false)
const formOutput: any = ref(false)
const formTambahMedikasi: any = ref(false)
const showFormInput: any = ref(false)
// const showFormMedikasi: any = ref(false)
const dataSourceMedikasi: any = ref([])
const dataSourceAlatInvasif: any = ref([])
const dataSourceIntake: any = ref([]);
const dataSourceOutput: any = ref([]);
const item: any = reactive({})
const user = useUserSession().getUser().pegawai;
const filter: any = ref('')
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref(props.norecTertutup)
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const hours = new Date().setHours(0, 0, 0, 0);
const lisRute: any = ref([{ value: 'Oral', label: 'Oral' }, { value: 'Injeksi', label: 'Injeksi' }, { value: 'nebul', label: 'nebul' }, { value: 'Lainnya', label: 'Lainnya' }])
const listInjeksi: any = ref([{ value: '/24', label: '/24' }, { value: '/12', label: '/12' }, { value: '/8', label: '/8' }, { value: '/6', label: '/6' }, { value: 'Lainnya', label: 'Lainnya' }])
const input: any = ref({
  tanggal: new Date(),
  listVitalSignDetails: [{
    no: 1,
    id: uuidv4(),
  }],
  listSistemSaraf: [{
    no: 1,
    id: uuidv4(),
  }],
  listStatusRespirasi: [{
    no: 1,
    id: uuidv4(),
  }],
  balansCairanIntake: [{
    no: 1,
    id: uuidv4(),
  }],
  details: [{
    NO: 1,
    DT_INTAKE: new Date(),
  }],
  detailss: [{
    NO: 1,
  }],
  detailsTransfusi: [{
    NO: 1,
  }],
  detailout: [{
    NO: 1,
  }],
  details1: [{
    TGL: '',
    KET: '',
  }],
  details2: [{
    TGL_SARAF: new Date(),
  }],
  details3: [{
    TGL_RESPIRASI: new Date(),
  }],
  details4: [{
    TGL_HEMODINAMIK: new Date(),
  }],
  D_1_CPO: new Date(),
  detailsCPO: [{ no: 1, DTanggal_IP: new Date() }],
  detailsCPO2: [{
    no: 1,
    Time_0: hours, Time_1: hours, Time_2: hours, Time_3: hours, Time_4: hours, Time_5: hours, Time_6: hours, Time_7: hours, Time_8: hours, Time_9: hours,
    Time_10: hours, Time_11: hours, Time_12: hours, Time_13: hours, Time_14: hours, Time_15: hours, Time_16: hours, Time_17: hours, Time_18: hours, Time_19: hours,
    Time_20: hours, Time_21: hours, Time_22: hours, Time_23: hours, Time_24: hours, Time_25: hours, Time_26: hours, Time_27: hours, Time_28: hours, Time_29: hours,
    Time_30: hours, Time_31: hours, Time_32: hours, Time_33: hours, Time_34: hours, Time_35: hours, Time_36: hours, Time_37: hours, Time_38: hours, Time_39: hours, Time_40: hours, Time_41: hours
  }],
  detailsCPO3: [{ tanggalPengisian: "", hariKe: "" }],
  detailOutputText: [{
    no: 1
  }]
})
const resetInput: any = ref({
  id: '',
  tanggal: new Date(),
  listVitalSignDetails: [{
    no: 1,
    id: uuidv4(),
  }],
  listSistemSaraf: [{
    no: 1,
    id: uuidv4(),
  }],
  listStatusRespirasi: [{
    no: 1,
    id: uuidv4(),
  }],
  balansCairanIntake: [{
    no: 1,
    id: uuidv4(),
  }],
  details: [{
    NO: 1,
    DT_INTAKE: new Date(),
  }],
  detailss: [{
    NO: 1,
  }],
  detailsTransfusi: [{
    NO: 1,
  }],
  detailout: [{
    NO: 1,
  }],
  details1: [{
    TGL: '',
    KET: '',
  }],
  details2: [{
    TGL_SARAF: new Date(),
  }],
  details3: [{
    TGL_RESPIRASI: new Date(),
  }],
  details4: [{
    TGL_HEMODINAMIK: new Date(),
  }],
  D_1_CPO: new Date(),
  detailsCPO: [{ no: 1, DTanggal_IP: new Date() }],
  detailsCPO2: [{
    no: 1,
    Time_0: hours, Time_1: hours, Time_2: hours, Time_3: hours, Time_4: hours, Time_5: hours, Time_6: hours, Time_7: hours, Time_8: hours, Time_9: hours,
    Time_10: hours, Time_11: hours, Time_12: hours, Time_13: hours, Time_14: hours, Time_15: hours, Time_16: hours, Time_17: hours, Time_18: hours, Time_19: hours,
    Time_20: hours, Time_21: hours, Time_22: hours, Time_23: hours, Time_24: hours, Time_25: hours, Time_26: hours, Time_27: hours, Time_28: hours, Time_29: hours,
    Time_30: hours, Time_31: hours, Time_32: hours, Time_33: hours, Time_34: hours, Time_35: hours, Time_36: hours, Time_37: hours, Time_38: hours, Time_39: hours, Time_40: hours, Time_41: hours
  }],
  detailsCPO3: [{ tanggalPengisian: "", hariKe: "" }],
})

const toRoman = (num: number): string => {
  const romanMap = [
    { value: 1000, numeral: 'M' },
    { value: 900, numeral: 'CM' },
    { value: 500, numeral: 'D' },
    { value: 400, numeral: 'CD' },
    { value: 100, numeral: 'C' },
    { value: 90, numeral: 'XC' },
    { value: 50, numeral: 'L' },
    { value: 40, numeral: 'XL' },
    { value: 10, numeral: 'X' },
    { value: 9, numeral: 'IX' },
    { value: 5, numeral: 'V' },
    { value: 4, numeral: 'IV' },
    { value: 1, numeral: 'I' },
  ];

  let result = '';
  for (const { value, numeral } of romanMap) {
    while (num >= value) {
      result += numeral;
      num -= value;
    }
  }
  return result;
};

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const addNewItemCPO = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.detailsCPO[input.value.detailsCPO.length - 1].no + 1,
    DTanggal_IP: new Date()
  }
  input.value.detailsCPO.push(newItem);

  if (!input.value.detailsCPO2.length) return; // Prevent errors if details2 is empty

  const lastItem = input.value.detailsCPO2[input.value.detailsCPO2.length - 1]; // Get the last item

  // Generate a new object dynamically
  let newItem2 = {
    no: lastItem.no + 1, // Increment no
  };

  // Dynamically add Time_x fields based on the last item's keys
  Object.keys(lastItem).forEach((key) => {
    if (key.startsWith("Time_")) {
      newItem2[key] = hours; // Assign default value
    }
  });

  // Push the new item to details2
  input.value.detailsCPO2.push(newItem2);
}

const removeItemCPO = (index: any) => {
  input.value.detailsCPO.splice(index, 1)
  input.value.detailsCPO2.splice(index, 1)
}

// const filteredList = computed(() => {
//   if (!filter.value) {
//     // return listVital.value
//   }

//   return listVital.value.filter((items: any) => {
//     return (
//       items.user_input.namalengkap.match(new RegExp(filter.value, 'i'))
//     )
//   })
// })

const add = () => {
  showFormInput.value = true
}

const addVitalSign = () => {
  input.value = {
    tanggal: new Date()
  }
  modalInput.value = true
}

const filteredListVitalSign = computed(() => {
  if (!filter.value) {
    return listVital.value
  }

  return listVital.value.filter((items: any) => {
    return (
      items.user_input.namalengkap.match(new RegExp(filter.value, 'i'))
    )
  })

})

// const loadRiwayat = () => {
//   isLoadingHolder.value = true
//   useApi().get(
//     `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=MonitoringICU&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
//       if (response.length) {
//         input.value = response[0] //set ke inputan
//         description.value = response[0].monitoringICU
//         medikasi.value = response[0].medikasi
//         dataSourceAlatInvasif.value = response[0].pemasanganAlat
//         tableBalansCairan.value = response[0].balansCairan
//         chartData.value = setChartData(listVital.value);
//         chartOptions.value = setChartOptions();
//         if (NOREC_EMRPASIEN.value == '') {
//           NOREC_EMRPASIEN.value = response[0].emrpasienfk
//         }
//       }
//     })
//   isLoadingHolder.value = false
// }
const loadRiwayat = async () => {
  isLoading.value = true;
  try {
    const response = await useApi().get(
      `/emr/get-emr-monitoring-icu-jadwal-terbuka?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&statusJadwal=Terbuka`
    );

    isLoading.value = false;

    if (response?.length) {
      input.value = {
        ...input.value,
        ...response[0],
        details: response[0].details || input.value.details,
        detailsCPO: response[0].detailsCPO || input.value.detailsCPO,
      };

      listVital.value = response;
      dataSourceAlatInvasif.value = response[0].pemasanganAlat || [];
      dataSourceMedikasi.value = response[0].medikasi || [];
      dataSourceIntake.value = response[0].balansCairanIntake || [];
      dataSourceOutput.value = response[0].balansCairanOutput || [];
      chartData.value = setChartData(listVital.value);
      chartOptions.value = setChartOptions();
      H.alert('success', 'Berhasil memuat riwayat');
    } else {
      H.alert('info', 'EMR Belum diinput');
    }
  } catch (error) {
    isLoading.value = false;
    console.log(JSON.stringify(error));
    H.alert('error', 'Gagal memuat riwayat');
  }
};
const loadRiwayatTutupJadwal = async () => {
  isLoading.value = true;
  try {
    const response = await useApi().get(
      `/emr/get-emr-monitoring-icu-jadwal-tertutup?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=ObservasiKeseimbanganCairan&emrpasienfk=${NOREC_EMRPASIEN.value}&statusJadwal=Tertutup`
    );
    listTutupJadwal.value = response;
    isLoading.value = false;
  } catch (error) {
    isLoading.value = false;
    console.log(JSON.stringify(error));
    H.alert('error', 'Gagal memuat riwayat');
  }
};
const previewTutupJadwalClick = () => {
  previewTutupJadwal.value = true;
};

const onCellEditComplete = (event: any) => {
  let { data, newValue, field, newData } = event;
  console.log(event)
  if (newValue != undefined) {
    data[field] = newValue
    // console.log(event)
  }

}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  // object.monitoringICU = description.value
  // object.medikasi = medikasi.value
  // object.balansCairan = tableBalansCairan.value
  object.pemasanganAlat = dataSourceAlatInvasif.value
  object.medikasi = dataSourceMedikasi.value
  object.balansCairanIntake = dataSourceIntake.value
  object.balansCairanOutput = dataSourceOutput.value
  object.statusJadwal = "Terbuka"
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': "MonitoringICU",
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
      modalInput.value = false
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const tutupJadwal = async () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.pemasanganAlat = dataSourceAlatInvasif.value
  object.medikasi = dataSourceMedikasi.value
  object.balansCairanIntake = dataSourceIntake.value
  object.balansCairanOutput = dataSourceOutput.value
  object.statusJadwal = "Tertutup"
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
  try {
    const response = await useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false;
      NOREC_EMRPASIEN.value = response.norec_emr;
      input.value.id = response.id;
      modalInput.value = false;

      resetInput();

      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false;
    });
  } catch (e) {
    isLoading.value = false;
  }
}


const addNewItem = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details[input.value.details.length - 1].no + 1
  }
  input.value.details.push(newItem);
}
const removeItem = (index: any) => {
  let urut = input.value.details.length - 1
  input.value.details.splice(urut, 1)
}
const addNewItemPar = () => {
  let newItem: any = {};

  let lastItem = input.value.detailss.length > 0 ? input.value.detailss[input.value.detailss.length - 1] : null;
  newItem.no = lastItem ? lastItem.no + 1 : 1;

  input.value.detailss.push(newItem);
};

const removeItemPar = (index: number) => {
  if (input.value.detailss.length > 0) {
    input.value.detailss.splice(index, 1);
  }
};

const addNewItemTransfusi = () => {
  const newRow: Record<string, string> = {};
  const rowIndex = input.value.detailsTransfusi.length + 1;
  const colCount = 5; // Adjust the number of columns as needed

  for (let colIndex = 1; colIndex <= colCount; colIndex++) {
    newRow[`transfusi${rowIndex}_${colIndex}`] = ""; // Unique key for each column
  }

  input.value.detailsTransfusi.push(newRow);
};


const removeItemTransfusi = (index: number) => {
  input.value.detailsTransfusi.splice(index, 1);
};

const addNewItemOut = () => {
  if (!input.value.detailout) {
    input.value.detailout = [];
  }
  if(!input.value.detailOutputText) {
    input.value.detailOutputText = [
      {no: 1}
    ];
  }

  let newItem: any = {};
  let newDetailOut: any = {};
  let lastItem = input.value.detailout.length > 0 ? input.value.detailout[input.value.detailout.length - 1] : null;
  let lastTextOutput = input.value.detailOutputText.length > 0 ? input.value.detailOutputText[input.value.detailOutputText.length - 1] : null;
  newItem.no = lastItem ? lastItem.no + 1 : 1;
  newDetailOut.no = lastTextOutput ? lastTextOutput.no + 1 : 1;

  input.value.detailOutputText.push(newDetailOut);
  input.value.detailout.push(newItem);
};


const removeItemOut = (index: number) => {
  if (!input.value.detailout || input.value.detailout.length === 0) return;
  if (!input.value.detailOutputText || input.value.detailOutputText.length === 0) return;

  input.value.detailout.splice(index, 1);
  input.value.detailOutputText.splice(index, 1);
};


const addNewItem1 = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details1[input.value.details1.length - 1].no + 1
  }
  input.value.details1.push(newItem);
}
const removeItem1 = (index: any) => {
  let urut = input.value.details1.length - 1
  input.value.details1.splice(urut, 1)
}

const addNewItem2 = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details2[input.value.details2.length - 1].no + 1
  }
  input.value.details2.push(newItem);
}
const removeItem2 = (index: any) => {
  let urut = input.value.details2.length - 1
  input.value.details2.splice(urut, 1)
}

const addNewItem3 = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details3[input.value.details3.length - 1].no + 1
  }
  input.value.details3.push(newItem);
}
const removeItem3 = (index: any) => {
  let urut = input.value.details3.length - 1
  input.value.details3.splice(urut, 1)
}

const addNewItem4 = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details4[input.value.details4.length - 1].no + 1
  }
  input.value.details4.push(newItem);
}
const removeItem4 = (index: any) => {
  let urut = input.value.details4.length - 1
  input.value.details4.splice(urut, 1)
}

const addNewItemVitalSign = () => {
  input.value.listVitalSignDetails.unshift({
    no: input.value.listVitalSignDetails[input.value.listVitalSignDetails.length - 1].no + 1,
    tgltindakan: new Date(),
  });
}
const removeItemVitalSign = (index: any) => {
  input.value.listVitalSignDetails.splice(index, 1)
}

const addNewSistemSaraf = () => {
  input.value.listSistemSaraf.unshift({
    no: input.value.listSistemSaraf[input.value.listSistemSaraf.length - 1].no + 1,
    id: uuidv4(),
  });
}
const removeSistemSaraf = (index: any) => {
  input.value.listSistemSaraf.splice(index, 1)
}

const addNewStatusRespirasi = () => {
  input.value.listStatusRespirasi.unshift({
    no: input.value.listStatusRespirasi[input.value.listStatusRespirasi.length - 1].no + 1,
    id: uuidv4(),
  });
}
const removeStatusRespirasi = (index: any) => {
  input.value.listStatusRespirasi.splice(index, 1)
}


const addtoSourceAlat = (e: any) => {
  if (!e.namaAlat) {
    return H.alert('error', 'Nama Alat Harus diisi')
  }
  if (!e.pasang) {
    return H.alert('error', 'Tanggal Pasang Harus diisi')
  }
  if (!e.cabut) {
    return H.alert('error', 'Tanggal Cabut Harus diisi')
  }
  if (!e.hariKe) {
    return H.alert('error', 'Hari Ke Harus diisi')
  }
  if (e.no) {
    dataSourceAlatInvasif.value.forEach((element: any) => {
      if (e.no == element.no) {
        element.alatInvasif = e.namaAlat
        element.tglPasang = e.pasang
        element.tglCabut = e.cabut
        element.hari = e.hariKe
      }
    });
  } else {
    dataSourceAlatInvasif.value.push({
      no: dataSourceAlatInvasif.value.length + 1,
      alatInvasif: e.namaAlat,
      tglPasang: e.pasang,
      tglCabut: e.cabut,
      hari: e.hariKe
    })
  }
  formTambahAlat.value = false
  clear()
}

const addDataSourceOutput = (e: any) => {
  if (!Array.isArray(dataSourceOutput.value)) {
    dataSourceOutput.value = [];
  }
  if (e.no) {
    dataSourceOutput.value.forEach((element: any) => {
      if (e.no == element.no) {
        element.namaOutput = e.namaOutput
        element.jenisOutput = e.jenisOutput
        element.mlOutput = e.mlOutput
        element.tanggalOutput = e.tanggalOutput
        element.totalOutput = e.totalOutput
      }
    });
  } else {
    dataSourceOutput.value.push({
      no: dataSourceOutput.value.length + 1,
      namaOutput: e.namaOutput,
      jenisOutput: e.jenisOutput,
      mlOutput: e.mlOutput,
      tanggalOutput: e.tanggalOutput,
      totalOutput: e.totalOutput
    })
  }
  formOutput.value = false
  clear()
}

const addtoSourceIntake = (e: any) => {
  if (!Array.isArray(dataSourceIntake.value)) {
    dataSourceIntake.value = [];
  }
  if (e.no) {
    dataSourceIntake.value.forEach((element: any) => {
      if (e.no == element.no) {
        element.namaIntake = e.namaIntake
        element.jenisIntake = e.jenisIntake
        element.mlIntake = e.mlIntake
        element.tanggalIntake = e.tanggalIntake
        element.totalIntake = e.totalIntake
      }
    });
  } else {
    dataSourceIntake.value.push({
      no: dataSourceIntake.value.length + 1,
      namaIntake: e.namaIntake,
      jenisIntake: e.jenisIntake,
      mlIntake: e.mlIntake,
      tanggalIntake: e.tanggalIntake,
      totalIntake: e.totalIntake
    })
  }
  formIntake.value = false
  clear()
}

const addtoSourceMedikasi = (e: any) => {
  if (!e.namaObat) {
    return H.alert('error', 'Nama Obat Harus diisi')
  }
  if (!e.dosis) {
    return H.alert('error', 'Dosis Harus diisi')
  }
  if (!e.waktu) {
    return H.alert('error', 'Waktu Hari')
  }
  if (e.no) {
    dataSourceMedikasi.value.forEach((element: any) => {
      if (e.no == element.no) {
        element.namaObat = e.namaObat
        element.dosis = e.dosis
        element.waktu = e.waktu
      }
    });
  } else {
    dataSourceMedikasi.value.push({
      no: dataSourceMedikasi.value.length + 1,
      namaObat: e.namaObat,
      dosis: e.dosis,
      waktu: e.waktu,
    })
  }
  formTambahMedikasi.value = false
  clear()
}

const editOutput = (e: any) => {
  showFormOutput(e)
  console.log(e)
}

const deleteOutput = (i: any) => {
  dataSourceOutput.value.splice(i, 1)
}

const editIntake = (e: any) => {
  showFormIntake(e)
  console.log(e)
}

const deleteIntake = (i: any) => {
  dataSourceIntake.value.splice(i, 1)
}

const editMedikasi = (e: any) => {
  showFormMedikasi(e)
  console.log(e)
}

const deleteMedikasi = (i: any) => {
  dataSourceMedikasi.value.splice(i, 1)
}

const editItemAlat = (e: any) => {
  showFormPemasanganAlat(e)
  console.log(e)
}

const deleteItemAlat = (i: any) => {
  dataSourceAlatInvasif.value.splice(i, 1)
}

const clear = () => {
  delete item.namaAlat
  delete item.tglPasang
  delete item.tglCabut
  delete item.hariKe
}

const fetchAlatBantu = () => {
  d_AlatBantu.value = [{ label: 'NC' }, { label: 'SM' }, { label: 'RM' }, { label: 'NRM' }, { label: 'HFNC' }, { label: 'Ventilator' }, { label: 'NGT' }]
}

const fetchCRT = () => {
  d_CRT.value = [{ label: "< 3" }, { label: "> 3" }]
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.norm = props.pasien.nocm
  input.value.namaPasien = props.pasien.namapasien
}

const showFormPemasanganAlat = (e: any) => {
  item.no = e.no ? e.no : ''
  item.namaAlat = e.alatInvasif ? e.alatInvasif : '',
    item.pasang = e.tglPasang ? e.tglPasang : '',
    item.cabut = e.tglCabut ? e.tglCabut : '',
    item.hariKe = e.hari ? e.hari : '',
    formTambahAlat.value = true
}

const showFormIntake = (e: any) => {
  item.no = e.no ? e.no : ''
  item.namaIntake = e.namaIntake ? e.namaIntake : '',
    item.jenisIntake = e.jenisIntake ? e.jenisIntake : '',
    item.mlIntake = e.mlIntake ? e.mlIntake : '',
    item.tanggalIntake = e.tanggalIntake ? e.tanggalIntake : '',
    item.totalIntake = e.totalIntake ? e.totalIntake : '',
    formIntake.value = true
}

const showFormOutput = (e: any) => {
  item.no = e.no ? e.no : ''
  item.namaOutput = e.namaOutput ? e.namaOutput : '',
    item.jenisOutput = e.jenisOutput ? e.jenisOutput : '',
    item.mlOutput = e.mlOutput ? e.mlOutput : '',
    item.tanggalOutput = e.tanggalOutput ? e.tanggalOutput : '',
    item.totalOutput = e.totalOutput ? e.totalOutput : '',
    formOutput.value = true
}

const showFormMedikasi = (e: any) => {
  item.no = e.no ? e.no : ''
  item.namaAlat = e.alatInvasif ? e.alatInvasif : '',
    item.pasang = e.tglPasang ? e.tglPasang : '',
    item.cabut = e.tglCabut ? e.tglCabut : '',
    item.hariKe = e.hari ? e.hari : '',
    formTambahMedikasi.value = true
}

const totalColumns = computed(() => {
  if (!input.value.detailsCPO2.length) return 3; // Default value if empty

  // Get count of "Time_x" fields from the first entry in details2
  const timeCount = input.value.detailsCPO2.reduce((max, item) => {
    const count = Object.keys(item).filter((key) => key.startsWith("Time_")).length;
    return Math.max(max, count); // Ensure it accounts for all items
  }, 0);

  return timeCount + 3; // Include static columns (e.g., "Jam", "Paraf 1", etc.)
});

const addNewDetail3 = () => {
  if (!input.value.detailsCPO2.length) return;

  // Find the latest Time_x index
  const latestIndex = Object.keys(input.value.detailsCPO2[0])
    .filter(key => key.startsWith("Time_"))
    .map(key => parseInt(key.split("_")[1]))
    .sort((a, b) => b - a)[0] || 0;

  // Add 7 new Time_x fields reactively
  input.value.detailsCPO2 = input.value.detailsCPO2.map(item => {
    let newItem = { ...item }; // Create a new reactive object
    for (let i = 1; i <= 7; i++) {
      newItem[`Time_${latestIndex + i}`] = hours; // Assign hours dynamically
    }
    return newItem;
  });

  // Update ArrayKu dynamically
  ArrayKu.value = Object.keys(input.value.detailsCPO2[0]).filter(k => k.startsWith("Time_"));

  console.log(input.value.detail2);
  // Push a new details3 entry
  input.value.detailsCPO3.push({ tanggalPengisian: "", hariKe: "" });
};

const ArrayKu = computed(() => {
  if (!input.value.detailsCPO2.length) return []; // If empty, return an empty array

  // Get the highest number of "Time_x" fields across all `details2` items
  const maxTimeCount = input.value.detailsCPO2.reduce((max, item) => {
    const count = Object.keys(item).filter((key) => key.startsWith("Time_")).length;
    return Math.max(max, count);
  }, 0);

  return Array(maxTimeCount).fill(null); // Return an array of that size
});


const removeDetail3 = () => {
  if (!input.value.details2.length || !input.value.details3.length) return;

  // Remove the latest entry from details3
  input.value.details3.pop();

  // Find the latest 7 Time_x indexes
  const timeKeys = Object.keys(input.value.details2[0])
    .filter(key => key.startsWith("Time_"))
    .map(key => parseInt(key.split("_")[1]))
    .sort((a, b) => b - a); // Sort descending

  if (timeKeys.length) {
    const latestIndexes = timeKeys.slice(0, 7); // Get the last 7 indexes

    // Remove the latest 7 Time_x fields reactively
    input.value.details2 = input.value.details2.map(item => {
      let newItem = { ...item };
      latestIndexes.forEach(index => delete newItem[`Time_${index}`]); // Remove each Time_x
      return newItem;
    });

    // Update ArrayKu dynamically
    ArrayKu.value = Object.keys(input.value.details2[0]).filter(k => k.startsWith("Time_"));
  }
};

const setChartData = (data: any) => {
  const documentStyle = getComputedStyle(document.documentElement);
  let labels = []
  let seriesNadi = []
  let seriesSuhu = []
  let seriesTekananDarah = []
  let seriesNafas = []
  let seriesSpO2 = []
  let seriesMap = []
  // console.log('QOI',data)

  for (var i = data.length - 1; i >= 0; i--) {
    const element = data[i]
    // console.log('QOI', element)
    for (var j = element.listVitalSignDetails.length - 1; j >= 0; j--) {
      const element2 = element.listVitalSignDetails[j]
      labels.push(H.formatDate(element2.tanggal, 'lll'))
      seriesNadi.push((element2.nadi ? parseFloat(element2.nadi) : 0))
      seriesSuhu.push((element2.suhu ? parseFloat(element2.suhu) : 0))
      seriesTekananDarah.push((element2.tekananDarah ? parseFloat(element2.tekananDarah) : 0))
      seriesNafas.push((element2.pernapasan ? parseFloat(element2.pernapasan) : 0))
      seriesSpO2.push((element2.SPO2 ? parseFloat(element2.SPO2) : 0))
      seriesMap.push((element2.MAP ? parseFloat(element2.MAP) : 0))
    }
  }

  return {
    labels: labels,
    datasets: [
      {
        label: 'Nadi',
        data: seriesNadi,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--blue-500')
      },
      {
        label: 'Suhu',
        data: seriesSuhu,
        fill: false,
        borderDash: [5, 5],
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--green-500')
      },
      {
        label: 'Tekanan Darah',
        data: seriesTekananDarah,
        fill: true,
        borderColor: documentStyle.getPropertyValue('--red-500'),
        tension: 0.4,
        backgroundColor: 'rgba(255,167,38,0.2)'
      },
      {
        label: 'Nafas',
        data: seriesNafas,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--black-500')
      },
      {
        label: 'SpO2',
        data: seriesSpO2,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--green-500')
      },
      {
        label: 'MAP',
        data: seriesMap,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--red-100')
      }
    ]
  };
};

const setChartOptions = () => {
  const documentStyle = getComputedStyle(document.documentElement);
  const textColor = documentStyle.getPropertyValue('--text-color');
  const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
  const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

  return {
    maintainAspectRatio: false,
    aspectRatio: 0.6,
    plugins: {
      legend: {
        labels: {
          color: textColor
        }
      }
    },
    scales: {
      x: {
        ticks: {
          color: textColorSecondary
        },
        grid: {
          color: surfaceBorder
        }
      },
      y: {
        ticks: {
          color: textColorSecondary
        },
        grid: {
          color: surfaceBorder
        }
      }
    }
  };
}

function getAlphabet(index) {
  return String.fromCharCode(65 + index);
}

watch(
  () => input.value.tglintravena,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tglintravena, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi1 = calculateDays + 1
  }
)

watch(
  () => input.value.tglcvc,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tglcvc, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi2 = calculateDays + 1
  }
)

watch(
  () => input.value.tglcath,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tglcath, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi3 = calculateDays + 1
  }
)

watch(
  () => input.value.tglngt,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tglngt, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi4 = calculateDays + 1
  }
)

watch(
  () => input.value.tgltrakeostomy,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tgltrakeostomy, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi5 = calculateDays + 1
  }
)

watch(
  () => input.value.tglett,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tglett, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi6 = calculateDays + 1
  }
)

watch(
  () => input.value.tgllainlain,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tgllainlain, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi7 = calculateDays + 1
  }
)

const rowHeights = ref<number[]>([]); // Store row heights
const rowHeights2 = ref<number[]>([]);
const observers: ResizeObserver[] = []; // Store ResizeObservers

const observeResize = () => {
  // Disconnect and clear previous observers
  observers.forEach(observer => observer.disconnect());
  observers.length = 0;

  nextTick(() => {
    input.value.detailsCPO.forEach((_, index) => {
      // First and Second Tables
      const leftRow = document.querySelector(`#left-row-${index}`);
      const rightRow = document.querySelector(`#right-row-${index}`);

      // Third and Fourth Tables
      const left2Row = document.querySelector(`#left2-row-${index}`);
      const right2Row = document.querySelector(`#right2-row-${index}`);

      if (leftRow && rightRow) {
        const observer = new ResizeObserver(() => syncRowHeights());
        observer.observe(leftRow);
        observer.observe(rightRow);
        observers.push(observer);
      }

      if (left2Row && right2Row) {
        const observer2 = new ResizeObserver(() => syncRowHeights2());
        observer2.observe(left2Row);
        observer2.observe(right2Row);
        observers.push(observer2);
      }
    });
  });
};

const syncRowHeights = () => {
  nextTick(() => {
    rowHeights.value = input.value.detailsCPO.map(() => 0); // Reset heights

    input.value.detailsCPO.forEach((_, index) => {
      const leftRow = document.querySelector(`#left-row-${index}`);
      const rightRow = document.querySelector(`#right-row-${index}`);

      if (leftRow && rightRow) {
        const maxHeight = Math.max(leftRow.clientHeight, rightRow.clientHeight);
        rowHeights.value[index] = maxHeight;
      }
    });
  });
};

const syncRowHeights2 = () => {
  nextTick(() => {
    if (!input.value?.detailsCPO?.length) {
      rowHeights2.value = [];
      return;
    }
    rowHeights2.value = input.value.detailsCPO.map(() => 0); // Reset heights

    input.value.detailsCPO.forEach((_, index) => {
      const left2Row = document.querySelector(`#left2-row-${index}`);
      const right2Row = document.querySelector(`#right2-row-${index}`);

      if (left2Row && right2Row) {
        const maxHeight = Math.max(left2Row.clientHeight, right2Row.clientHeight);
        rowHeights2.value[index] = maxHeight;
      }
    });
  });
};

// watch(
//   () => input.value.detailsCPO.length,
//   () => {
//     nextTick(() => {
//       observeResize();
//       syncRowHeights();
//     });
//   },
//   { deep: true }
// );

// watch(
//   () => input.value.detailsCPO3.length,
//   () => {
//     nextTick(() => {
//       observeResize();
//       syncRowHeights2();
//     });
//   },
//   { deep: true }
// );

onMounted(() => {
  observeResize();
  syncRowHeights();
  syncRowHeights2();
});

// Cleanup observers on unmount
onUnmounted(() => {
  observers.forEach(observer => observer.disconnect());
  observers.length = 0;
});

// watch(
//   () => input.value.details['TB_ORAL'],
//         input.value.details['TB_ENTERAL'],
//         input.value.details['JUMLAH31'],
//         input.value.details['I'],
//         input.value.details['II'],
//         input.value.details['III'],
//         input.value.details['IV'],
//         input.value.details['V'],
//         input.value.details['JUMLAH32'],
//         input.value.details['FREE'],
//   (newValue, oldValue) => {
//     data['JUMLAH2'] = parseFloat(input.value.details['TB_ORAL']) +
//         parseFloat(input.value.details['TB_ENTERAL']) +
//         parseFloat(input.value.details['JUMLAH31']) +
//         parseFloat(input.value.details['I']) +
//         parseFloat(input.value.details['II']) +
//         parseFloat(input.value.details['III']) +
//         parseFloat(input.value.details['IV']) +
//         parseFloat(input.value.details['V']) +
//         parseFloat(input.value.details['JUMLAH32']) +
//         parseFloat(input.value.details['FREE'])
//   })

watchEffect(() => {
  let total = 0;
  let totalintake = 0;
  let totalall = 0;
  let totalInput = 0;
  let totalOutput = 0;
  let totalmakan = 0;
  let totaltransfusi = 0;
  if (!input.value?.details || !Array.isArray(input.value.details)) {
    return; // Exit early if details is not an array
  }
  input.value.details.forEach((a, index) => {
    let oral = parseFloat(a['TB_ORAL'] ?? 0)
    let enteral = parseFloat(a['TB_ENTERAL'] ?? 0)
    let jumlah31 = parseFloat(a['JUMLAH31'] ?? 0)
    let I = parseFloat(a['I'] ?? 0)
    let II = parseFloat(a['II'] ?? 0)
    let III = parseFloat(a['III'] ?? 0)
    let IV = parseFloat(a['IV'] ?? 0)
    let V = parseFloat(a['V'] ?? 0)
    let jumlah32 = parseFloat(a['JUMLAH32'] ?? 0)
    let free = parseFloat(a['FREE'] ?? 0)
    let jumlah2Total = 0
    if (input.value.detailout && Array.isArray(input.value.detailout)) {
      input.value.detailout.forEach((row, rowsIndex) => {
        const key = `JUMLAH2${index + 1}_${rowsIndex + 1}`
        const val = parseFloat(row[key] ?? 0)
        if (!isNaN(val)) {
          jumlah2Total += val
        }
      })
    }

    let parenteral1 = 0
    if (input.value.detailss && Array.isArray(input.value.detailss)) {
      input.value.detailss.forEach((ro, rowIndex) => {
        const key = `FREE${index + 1}_${rowIndex + 1}`
        const val = parseFloat(ro[key] ?? 0)
        if (!isNaN(val)) {
          parenteral1 += val
        }
      })
    }

    let parenteral2 = 0
    if (input.value.detailss && Array.isArray(input.value.detailss)) {
      input.value.detailss.forEach((ro, rowIndex) => {
        const key = `JUMLAH1${index + 1}_${rowIndex + 1}`
        const val = parseFloat(ro[key] ?? 0)
        if (!isNaN(val)) {
          parenteral2 += val
        }
      })
    }

    let totalTransfusiRow = 0
      if (input.value.detailsTransfusi && Array.isArray(input.value.detailsTransfusi)) {
        input.value.detailsTransfusi.forEach((col, colIndex) => {
          const key = `transfusi${index + 1}_${colIndex + 1}`
          const val = parseFloat(col[key] ?? 0)
          if (!isNaN(val)) {
            totalTransfusiRow += val
          }
        })
      }


    totalmakan = oral + enteral
    if (!isNaN(totalmakan)) {
        a['JUMLAH31'] = totalmakan
    } else {
        a['JUMLAH31'] = 0
    }

    totaltransfusi = I + II + III + IV + V + totalTransfusiRow
    if (!isNaN(totaltransfusi)) {
        a['JUMLAH32'] = totaltransfusi
    } else {
        a['JUMLAH32'] = 0
    }

    total = totalmakan + totaltransfusi
    if (!isNaN(total)) {
      a['JUMLAH1'] = total
      totalintake = total
    } else {
      a['JUMLAH1'] = 0
      totalintake = 0
    }

    totalall = (totalintake + parenteral1 + parenteral2) - jumlah2Total
    if (!isNaN(totalall)) {
      a['TB'] = totalall
    } else {
      a['TB'] = 0
    }

    totalInput = totalintake + parenteral1 + parenteral2;
    if(!isNaN(totalInput)) {
      a['totalInput'] = totalInput;
    }else {
      a['totalInput'] = 0;
    }

    totalOutput = jumlah2Total;
    if(!isNaN(totalOutput)) {
      a['totalOutput'] = totalOutput;
    }else {
      a['totalOutput'] = 0;
    }

  });
});

setView()
setAutoFill()
if(props.dataTertutup?.length) {
  if (props.dataTertutup?.length) {
      input.value = {
        ...input.value,
        ...props.dataTertutup[0],
        details: props.dataTertutup[0].details || input.value.details,
        detailsCPO: props.dataTertutup[0].detailsCPO || input.value.detailsCPO,
      };

      listVital.value = props.dataTertutup;
      dataSourceAlatInvasif.value = props.dataTertutup[0].pemasanganAlat || [];
      dataSourceMedikasi.value = props.dataTertutup[0].medikasi || [];
      dataSourceIntake.value = props.dataTertutup[0].balansCairanIntake || [];
      dataSourceOutput.value = props.dataTertutup[0].balansCairanOutput || [];
      chartData.value = setChartData(listVital.value);
      chartOptions.value = setChartOptions();
      H.alert('success', 'Data berhasil diambil, Status Jadwal Tertutup');
    } else {
      H.alert('info', 'Data berhasil diambil, Status Jadwal Tertutup');
    }

} else {
  loadRiwayat()
}
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
// @import '/@src/scss/custom/timeline-css';
// @import '/@src/scss/custom/timeline-css';

// .p-column-header-content {
//   justify-content: space-evenly;
// }

// .p-fieldset-legend {
//   margin-left: 15px;
// }

// .p-rowgroup-header {
//   background: beige !important;
// }

.tablecpo {
  border-collapse: collapse;
}

.tablecpo td {
  border: 1px solid black !important;
  text-align: center !important;
  color: black !important;
}

.tablecpo th {
  text-align: center !important;
  vertical-align: middle !important;
  border: 1px solid black !important;
  color: black !important;
}

.table2 {
  border-collapse: collapse;
}

.table2 td {
  border: 1px solid black !important;
  text-align: center !important;
  color: black !important;
  vertical-align: middle !important;
}

.table2 th {
  text-align: center !important;
  vertical-align: middle !important;
  border: 1px solid black !important;
  color: black !important;
}

.table-bordered {
  border: 1px solid black !important;
  tr {
    border: 1px solid black !important;
  }
  th {
    border: 1px solid black !important;
  }
  td {
    border: 1px solid black !important;
  }
  .col-frozen {
    position: sticky !important;
    left: 0;
    z-index: 2;
  }

}

.label-icu {
  font-weight: 500;
}

.list-widget {
  @include vuero-l-card;

  &.is-straight {
    @include vuero-s-card;
  }

  ul {
    li {
      &:not(:last-child) {
        margin-bottom: 12px;
      }

      a {
        font-family: var(--font);
        display: flex;
        justify-content: space-between;
        color: var(--light-text);

        &:hover,
        &:focus {
          color: var(--primary);
        }
      }
    }
  }
}


.widget-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 12px;

  .left {
    display: flex;
    align-items: center;
  }

  .center {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .right {
    display: flex;
    align-items: center;
    justify-content: flex-end;

    .tag {
      font-family: var(--font);
    }

    .right-icon {
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 32px;
      width: 32px;
      min-width: 32px;
      border-radius: var(--radius-rounded);
      color: var(--light-text-light-12);
      transition: all 0.3s; // transition-all test

      &.has-indicator {
        &::after {
          content: '';
          position: absolute;
          top: 3px;
          right: 4px;
          height: 10px;
          width: 10px;
          border-radius: var(--radius-rounded);
          background: var(--secondary);
          border: 1.8px solid var(--white);
        }
      }

      svg {
        height: 18px;
        width: 18px;
        transition: stroke 0.3s;
      }
    }
  }

  h3 {
    font-family: var(--font-alt);
    font-size: 0.9rem;
    color: var(--dark-text);
    font-weight: 600;

    &.is-bigger {
      font-size: 1rem;
    }
  }

  .action-icon {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 32px;
    width: 32px;
    min-width: 32px;
    border-radius: var(--radius-rounded);
    color: var(--light-text-light-12);
    transition: all 0.3s; // transition-all test

    svg {
      height: 18px;
      width: 18px;
      transition: stroke 0.3s;
    }
  }
}

.is-dark {
  .widget-toolbar {
    h3 {
      color: var(--dark-dark-text);
    }

    .right {
      .right-icon {
        &.has-indicator {
          &::after {
            border-color: var(--dark-sidebar-light-6);
          }
        }
      }
    }
  }
}

small.is-tanggal {
  color: var(--light-text);
}

.is-dark-text {
  color: var(--light-text);
}

.fake-input {
  display: block;
  width: 100%;
  height: 2.5rem;
  border: 1px solid transparent;
  background: transparent;
}
</style>
