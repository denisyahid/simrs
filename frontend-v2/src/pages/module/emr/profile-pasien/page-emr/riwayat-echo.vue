<template>
  <section>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <VButton type="button" rounded outlined color="primary" raised icon="feather:refresh-cw"
                :loading="isLoading" @click="loadRiwayat"> Cari
              </VButton>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="columns is-multiline p-2" style="max-width: 1380px; margin: 0 auto;">
      <div class="column is-12">

        <TRiwayatOrderRad title="" straight class="list-widget-v3" :items="listRiwayat" @hapusItems="DialogConfirm"
          @hasilItems="hasilItems" @expertiseItems="expertiseItems" squared colored :isHideEdit="true">
        </TRiwayatOrderRad>

      </div>
    </div>
    <Dialog v-model:visible="modalExpertise" modal header="Ekspertise" :style="{ width: '50vw' }">

      <div class="columns is-multiline">
        <div class="column is-4">
          <VField>
            <VLabel>Nama Pelayanan</VLabel>
            <VControl icon="feather:box">
              <VInput type="text" v-model="item.namaproduk" placeholder="Nama Pelayanan" class="is-rounded" disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField label="Tanggal">
            <VDatePicker v-model="item.tanggal" mode="dateTime" style="width: 100%">
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
          <VField>
            <VLabel class="required-field">Dokter</VLabel>
            <VControl icon="feather:search" class="prime-auto-select">
              <Dropdown v-model="item.dokterpemeriksa" :options="d_Pegawai" :optionLabel="'label'" class="is-rounded"
                placeholder="Dokter" style="width: 100%;" showClear :filter="true" />
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <VField>
            <VControl>
              <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="5" placeholder="Keterangan"
                autocomplete="off" autocapitalize="off" spellcheck="true" />
            </VControl>
          </VField>
        </div>
      </div>
      <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="clearFilter()">
          Tutup
        </VButton>
        <VButton type="button" rounded outlined color="info" raised icon="feather:printer" @click="cetakExpertise()">
          Cetak
        </VButton>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingPop"
          @click="saveExpertise()"> Simpan
        </VButton>
      </template>
    </Dialog>
  </section>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import TRiwayatOrderRad from '../t-riwayat-order-rad.vue'
import Dialog from "primevue/dialog"
import Dropdown from "primevue/dropdown"

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
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const isLoading: any = ref(false)
const isLoadingPop: any = ref(false)
const listRiwayat = ref([])
const item: any = reactive({})
const d_Pegawai = ref([])
const modalExpertise = ref(false)
const norecHasilRadiologi: any = ref('')
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadDrop = () => {
  useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const loadRiwayat = () => {
  isLoading.value = true
  listRiwayat.value = []
  useApi().get(
    `/radiologi/riwayat-order?nocmfk=${ID_PASIEN}&nocm=${props.pasien.nocm}&norec_pd=${props.registrasi.norec_pd}&noregistrasi=${props.registrasi.noregistrasi}&echo=true`).then((response: any) => {
      let z = 0
      isLoading.value = false
      for (let x = 0; x < response.length; x++) {
        const element = response[x];
        element.icon = 'fa fa-radiation'
        element.color = listColor2.value[z]
        element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
        if (z > 4) {
          z = 0
        }
        z++
      }
      listRiwayat.value = response
    })
}
const DialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapusItems(e)

    },
    reject: () => { },
  })
}
const hapusItems = (e: any) => {
  if (e.status != 'pending') {
    H.alert('error', 'Order sudah diverifikasi')
    return
  }
  useApi().post(
    `/radiologi/delete-order-rad`, { noorder: e.noorder }).then((response: any) => {
      isLoading.value = false
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
    })
}
var HttpClient = function () {
  this.get = function (aUrl, aCallback) {
    var anHttpRequest = new XMLHttpRequest();
    anHttpRequest.onreadystatechange = function () {
      if (anHttpRequest.readyState == 4 && anHttpRequest.status < 400)
        aCallback(anHttpRequest.responseText);
    }

    anHttpRequest.open("GET", aUrl, true);
    anHttpRequest.send(null);
  }
}
const hasilItems = (e: any) => {
  e.details.forEach((dataItem: any) => {
    dataItem.radiologiId = '00000184-3'
    if (dataItem.radiologiId === null || dataItem.radiologiId === '') {
      H.alert('warning', 'Hasil belum ada')
    } else {
      let viewer = null
      let patienIdMr = dataItem.radiologiId.replace('null', '1')
      let idRuangan = e.objectruangantujuanfk;
      useApi().postNoMessage(`/general/api-tools`, {
        'method': 'get',
        'url': import.meta.env.VITE_URL_PACS_ENGINE + '/dcm4chee-arc/aets/TRANSMEDIC/rs/studies?limit=1&includefield=all&offset=0&PatientID=' + patienIdMr,
        'headers': {}
      }).then((response: any) => {
        if (response.response == null) {
          H.alert('warning', 'Hasil foto belum dikirim ke PACS')
        } else {
          let data = response.response
          viewer = data[0]["0020000D"].Value[0]
          window.open(import.meta.env.VITE_URL_PACS_VIEWER
            + "/viewer/" + idRuangan
            + "/" + dataItem.norec_pp
            + "/" + props.registrasi.norec_pd
            + "/" + e.noorder + "/" + viewer, "pacs");
        }
      })


      // let client = new HttpClient();


      // let errorFunc = function () {
      //   H.alert('error', 'Ada kesalahan pada jaringan ke server')
      // }
      // let awal = true
      //
      // let noMrFunc = function (response) {
      //   if (response === undefined || response === null || response == '') {
      //     if (awal) {
      //       awal = false
      //       client.get(import.meta.env.VITE_URL_PACS_ENGINE + '/dcm4chee-arc/aets/TRANSMEDIC/rs/'
      //         + 'studies?limit=1&includefield=all&offset=0&PatientID=' + patienIdMr.split('-')[0],
      //         noMrFunc, errorFunc)
      //     } else {
      //       H.alert('warning', 'Hasil foto belum dikirim ke PACS')
      //     }
      //   } else {
      //     let data = JSON.parse(response)
      //     viewer = data[0]["0020000D"].Value[0]
      //     window.open(import.meta.env.VITE_URL_PACS_VIEWER
      //       + "/viewer/" + idRuangan
      //       + "/" + dataItem.norec_pp
      //       + "/" + norec_pd
      //       + "/" + dataItem.noorder + "/" + viewer, "pacs");
      //   }
      // }

      // client.get(import.meta.env.VITE_URL_PACS_ENGINE + '/dcm4chee-arc/aets/TRANSMEDIC/rs/' +
      //   'studies?limit=1&includefield=all&offset=0&PatientID=' + patienIdMr, noMrFunc, errorFunc)
    }
  });
}
const expertiseItems = (e: any) => {

  item.norec_pp = e.norec_pp
  item.tanggal = new Date()
  item.namaproduk = e.namaproduk
  item.noregistrasifk = props.registrasi.norec_pd

  useApi().get(
    `/radiologi/get-expertise?norec_pp=${e.norec_pp}`).then((response: any) => {
      if (response != null) {
        norecHasilRadiologi.value = response.norec
        item.norec = response.norec
        item.tanggal = new Date(response.tanggal)
        d_Pegawai.value.forEach(element => {
          if (element.value == response.pegawaifk) {
            item.dokterpemeriksa = element
          }
        });

        item.keterangan = response.keterangan
      } else {
        setTemplate();
      }
    })
  modalExpertise.value = true
}
const setTemplate = () => {
  useApi().get(
    `/general/template-expertise`).then((response: any) => {

      let defaultselected = {}
      for (let i = 0; i < response.data.length; i++) {
        const element = response.data[i];
        if (element.default) {
          defaultselected = element;
        }
      }
      item.keterangan = defaultselected.template;
    });
}

const saveExpertise = async (e: any) => {
  norecHasilRadiologi.value = ''
  if (!item.dokterpemeriksa) {
    H.alert('error', 'Dokter harus di isi')
    return
  }
  let objSave = {
    hasil: {
      keterangan: item.keterangan,
      pelayananpasienfk: item.norec_pp,
      noregistrasifk: item.noregistrasifk,
      pegawaifk: item.dokterpemeriksa.value
    }
  }
  isLoadingPop.value = true
  useApi().post(
    `/radiologi/save-expertise`, objSave).then((response: any) => {
      isLoadingPop.value = false
      norecHasilRadiologi.value = response.result.norec
    }, (error) => {
      isLoadingPop.value = false
    })
}
const cetakExpertise = () => {
  H.printBlade("radiologi/cetak-ekspertise?echo=true&norec=" + norecHasilRadiologi.value);
}
const kembaliKeun = () => {
  window.history.back()
}

setView()
loadRiwayat()
loadDrop()
</script>
