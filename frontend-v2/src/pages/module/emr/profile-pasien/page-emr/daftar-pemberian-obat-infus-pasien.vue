<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="column">
    <!-- form emr -->
    <VCard>
      <VButtons class="mb-0">
        <VButton color="primary" raised icon="fas fa-plus" @click="tambahBaris"> Tambah Baris </VButton>
        <VButton color="info" raised icon="fas fa-plus"> Tambah Kolom </VButton>
      </VButtons>
      <DataTable scrollable scrollHeight="300px" class="p-datatable-sm" showGridlines :value="sourceKolom">
        <ColumnGroup type="header">
          <Row>
            <Column header="Tgl Obat Masuk" style="min-width: 150px;" frozen class="font-bold" :rowspan="3" />
            <Column header="Nama Obat/Infus" style="min-width: 300px;" frozen class="font-bold" :rowspan="3" />
            <Column header="Dosis" style="min-width: 100px;" frozen class="font-bold" :rowspan="3" />
            <Column header="Rute" style="min-width: 100px;" frozen class="font-bold" :rowspan="3" />
            <!-- <Column :header="data" style="min-width: 100px;border-right:1px solid black;text-align: center"
              class="font-bold" :colspan="3" /> -->
          </Row>
          <Row>
            <Column header="Jam" style="min-width: 100px;" class="font-bold" />
            <Column header="Petugas" style="min-width: 300px;" class="font-bold" />
            <Column header="Keterangan" style="min-width: 300px;border-right:1px solid black;" class="font-bold" />
            <Column style="min-width: 400px;display: none;" />
          </Row>
        </ColumnGroup>
        <Column field="tanggalObat" frozen />
        <Column field="namaObat" frozen />
        <Column field="dosis" frozen />
        <Column field="rute" frozen />
        <!-- <Column field="tanggal" frozen :value="sourceKolom.baris" /> -->
      </DataTable>

    </VCard>

  </div>

  <VModal :open="modalTambahBaris" title="Detail Status Pulang" actions="right" size="small"
    @close="modalTambahBaris = false">

    <template #content>
      <div class="column is-9 pb-0">
        <VField label="Tanggal Obat">
          <VDatePicker v-model="item['tanggalObat']" mode="date" style="width: 100%" trim-weeks :max-date="new Date()">
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
      <div class="column pb-0">
        <VField label="Nama Obat/Infus">
          <VControl>
            <VTextarea class="textarea" v-model="item['namaObat']" rows="1" placeholder="Keterangan Tata Laksana"
              autocomplete="off" autocapitalize="off" spellcheck="true" />
          </VControl>
        </VField>
      </div>
      <div class="column pb-0">
        <VField label="Dosis">
          <VControl>
            <VTextarea class="textarea" v-model="item['dosis']" rows="1" placeholder="Keterangan Tata Laksana"
              autocomplete="off" autocapitalize="off" spellcheck="true" />
          </VControl>
        </VField>
      </div>
      <div class="column">
        <VField label="Rute">
          <VControl class="prime-auto">
            <AutoComplete v-model="item['routeFarmasi']" :suggestions="d_RouteFarmasi" @complete="fetchRoute($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" placeholder="Cari Dokter Pemeriksa..." class="mt-2" />
          </VControl>
        </VField>
      </div>
    </template>
    <template #action>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:trash" :loading="isLoading"
        @click="simpan()"> Simpan
      </VButton>
    </template>
  </VModal>
</template>


<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_RouteFarmasi: any = ref([])
const d_Pegawai: any = ref([])
const sourceKolom: any = ref([
  {
    "tanggalObat": "2023-09-05",
    "namaObat": "Paracetamol",
    "dosis": "40mg",
    "rute": "IV",
    "order": [
      {
        "tgl": "2023-09-05",
        "jam": "20:22",
        "petugas": "Reza Trisnan Aziz, A.Md.Kep",
        "ket": "IGD. Double check Ns. dhinda",
      },
      {
        "tgl": "2023-09-05",
        "jam": "20:22",
        "petugas": "Reza Trisnan Aziz, A.Md.Kep",
        "ket": "IGD. Double check Ns. dhinda",
      },
      {
        "tgl": "2023-09-05",
        "jam": "20:22",
        "petugas": "Reza Trisnan Aziz, A.Md.Kep",
        "ket": "IGD. Double check Ns. dhinda",
      },
    ]
    // "tgl" : "2023-09-05",
    // "jam" : "20:22",
    // "petugas" : "Reza Trisnan Aziz, A.Md.Kep",
    // "ket" : "IGD. Double check Ns. dhinda",
  },

])


const modalTambahBaris: any = ref(false)
const modalTambahKolom: any = ref(false)
const data: any = ref([
  {
    "tanggal": "12-20-2013",
  },
  {
    "tanggal": "10-30-2032"
  }
])
const d_Dokter: any = ref([])
const item: any = ref({})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}

const simpan = () => {

  let ID = input.value.id ? input.value.id : ''
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
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const sales = ref([
  { product: 'Bamboo Watch', lastYearSale: 51, thisYearSale: 40, lastYearProfit: 54406, thisYearProfit: 43342 },
  { product: 'Black Watch', lastYearSale: 83, thisYearSale: 9, lastYearProfit: 423132, thisYearProfit: 312122 },
  { product: 'Blue Band', lastYearSale: 38, thisYearSale: 5, lastYearProfit: 12321, thisYearProfit: 8500 },
  { product: 'Blue T-Shirt', lastYearSale: 49, thisYearSale: 22, lastYearProfit: 745232, thisYearProfit: 65323 },
  { product: 'Brown Purse', lastYearSale: 17, thisYearSale: 79, lastYearProfit: 643242, thisYearProfit: 500332 },
  { product: 'Chakra Bracelet', lastYearSale: 52, thisYearSale: 65, lastYearProfit: 421132, thisYearProfit: 150005 },
  { product: 'Galaxy Earrings', lastYearSale: 82, thisYearSale: 12, lastYearProfit: 131211, thisYearProfit: 100214 },
  { product: 'Game Controller', lastYearSale: 44, thisYearSale: 45, lastYearProfit: 66442, thisYearProfit: 53322 },
  { product: 'Gaming Set', lastYearSale: 90, thisYearSale: 56, lastYearProfit: 765442, thisYearProfit: 296232 },
  { product: 'Gold Phone Case', lastYearSale: 75, thisYearSale: 54, lastYearProfit: 21212, thisYearProfit: 12533 }
]);

const formatCurrency = (value: any) => {
  return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
};

const lastYearTotal = computed(() => {
  let total = 0;
  for (let sale of sales.value) {
    total += sale.lastYearProfit;
  }

  return formatCurrency(total);
});

const thisYearTotal = computed(() => {
  let total = 0;
  for (let sale of sales.value) {
    total += sale.thisYearProfit;
  }

  return formatCurrency(total);
});

const tambahBaris = () => {
  console.log(sourceKolom.value)
  // modalTambahBaris.value = true
  // if(input.value.datas.Baris.length >= 1){
  //   input.value.datas.Baris.namaObat = ''
  //   input.value.datas.Baris.push({
  //   no: input.value.datas.Baris[input.value.datas.Baris.length - 1].no + 1,
  //   })
  // }
}


const fetchRoute = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/routefarmasi?select=id,name&param_search=name&query=${filter.query}&limit=10`
  ).then((response) => {
    d_RouteFarmasi.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
  // width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;

  // font-size: 14px;
  overflow: hidden;
  padding: 7px;
  word-break: normal;
}

.tg tr {
  height: 20px;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  vertical-align: middle;
  // font-size: 14px;
  text-align: center !important;
  font-weight: bold;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.col-stuck {
  width: 150px;
  position: sticky;
  left: 0;
  z-index: 2;
  background-color: aliceblue;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
