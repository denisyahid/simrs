<style lang="scss">
h1 {
    font-weight: bold;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    text-align: center !important;
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: bold;
    overflow: hidden;
    background-color: aquamarine;
    vertical-align: middle;
    padding: 10px 5px;
    word-break: normal;
}
</style>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import Checkbox from 'primevue/checkbox';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset';
import Gambarin from '../page-emr-plugins/img-draw.vue'
import { useToaster } from '/@src/composable/toaster'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import TListOrderResep from '../t-list-order-resep.vue'
import TListOrderResepModal from '../t-list-order-resep-modal.vue'
import Dropdown from 'primevue/dropdown';
import { elements } from '/@src/data/landing/components'
import Dialog from 'primevue/dialog';
import TWidgetListResep from '../t-widget-list-resep.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

// import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import * as EMR from '../page-emr-plugins/monitoring&evaluasi-resusitasi'

// Loopingan
let ListBEBAS = ref([

])


// Judul
useHead({
    title: 'Asesmen Awal Medis Gawat Darurat - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string


const isloadingCopy: any = ref(false)
const isloadingPaketObat: any = ref(false)
const isloadingTambahPaket: any = ref(false)
const modalInput: any = ref(false)
const modalConfirm: any = ref(false)
const disabledRuangan: any = ref(false)
const listChecked: any = ref([])
const dataSourceStokProduk: any = ref([])
const selected_count = ref(0);
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
const isMerge: any = ref(false)
const isLoadRiwayatOLD: any = ref(false)
const isLoadBtnEdit: any = ref(false)
const listSIMRSLama: any = ref([])
const d_Produk: any = ref([])
const selectedProduct: any = ref();
const dataProductTampil: any = ref([]);
// const d_ProdukDef: any = ref([])
const filterLayanan: any = ref('')
// const listChecked1: any = ref([])
const listDataSigna = ref([
  { "id": 1, "nama": "P", "isChecked": false },
  { "id": 2, "nama": "S", "isChecked": false },
  { "id": 3, "nama": "Sr", "isChecked": false },
  { "id": 4, "nama": "M", "isChecked": false }
])
for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i];
  if (i <= 9 && element != 'primary')
    listColor.value.push(element)
}
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const isDetail: any = ref([true])
const dataSource: any = ref([])
const data2: any = ref([])
const d_Ruangan: any = ref([])
const isSimpan = ref(false)
const showRacikanDose = ref(false)
const showRacikanDoseFalse = ref(false)
const isLoadingTT = ref(false)
const d_Diagnosa = ref([])
const d_satuanResep = ref([])
const d_produk: any = ref([])
const d_ProdukDef: any = ref([])
const d_tglKadaluarsa: any = ref([])
const d_satuan: any = ref([])
const d_asalProduk: any = ref([])
const d_aturanPakai: any = ref([])
const d_kemasan: any = ref([])
const d_jenisRacikan: any = ref([])
const d_route: any = ref([])
const d_resepHariini: any = ref([])
const infoStok: any = ref('List Informasi Stok')
const dataSelected: any = ref({})
const confirm = useConfirm();
const selectedTabs: any = ref()
const listRiwayat: any = ref([])
const dataProdukDetail: any = ref([])
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const isNORM: any = ref(false)
const isLoadingRiwayat = ref(false)
const isLastObatByDate = ref(false)
const dataSourcePaketObat = ref([])
const metaKey = ref(false);
const activeValue: any = ref(1)

const modalDataPaketObat: any = ref(false)
const emit = defineEmits<{
  (e: 'update:selected', value: string): void,
  (e: 'berhasilSimpan', value: bool): void,
}>()

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
const newDate = new Date();
const input: any = ref({
    HjamKedatangan: newDate,
    HjamAW: newDate,
    details: [{
        no: 1,
    }],
})
const dataTTD: any = ref([])
const route = useRoute()
const d_Dokter: any = ref([])
// const d_Diagnosa = ref([])
const pasien: any = ref({})
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
    filter: '',
    airway: [],
    disability: []
})
const COLLECTION: any = ref('AsesmenAwalMedisGawatDarurat') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const loadRiwayat1 = async () => {
    // if (NOREC_EMRPASIEN.value == '') return
    await useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
                dataTTD.value = response[0]
            }
        })
    H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
    H.tandaTangan().set("TTDDokter2", dataTTD.value.TTDDokter2)
    await loadGambar("GambarTubuh", dataTTD.value.GambarTubuh)
}

const fetchDiagnosa = async (filter: any) => {
    let q = '';
    console.log("DATA FILTER", filter)
    if (filter != undefined) {
        q = filter.query;
    }

    // if(filter.query)
    const response = await useApi().get(`/diagnosa/diagnosa-x-paging?name=${q}&limit=10`)
    d_Diagnosa.value = response.diagnosa.map((item: any) => {
        return { value: item.id, label: item.kddiagnosa + ' -- ' + item.namadiagnosa }
    })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
    object['TTDDokter2'] = H.tandaTangan().get("TTDDokter2");
    object['GambarTubuh'] = H.tandaTangan().get("GambarTubuh");
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
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const tabs: any = ref([
  { label: 'Order', value: 1, icon: 'lnir lnir-medicine-alt' },
  { label: 'Riwayat', value: 2, icon: 'fas fa-list' },
  { label: 'Riwayat Resep Verif', value: 3, icon: 'fas fa-list' }
])
// const fetchDiagnosa = async (filter: any) => {
//     const response = await useApi().get(`/diagnosa/diagnosa-x-paging?name=${filter.query}&limit=10`)
//     d_Diagnosa.value = response.diagnosa.map((item: any) => {
//         return { value: item.id, label: item.kddiagnosa + ' -- ' + item.namadiagnosa }
//     })
// }
const loadGambar = async (element_id: string, value: string) => {
    let sigCanvas: any = document.getElementById(element_id);
    if (sigCanvas) {
        let context = sigCanvas.getContext("2d");
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        let imagess = value
        let background = new Image();
        background.src = imagess
        background.onload = function () {
            context.drawImage(background, 0, 0, 1200, 800);
        }
    }
}
watch(
  () => activeValue.value,
  (value) => {
    if (value == 2) {
      loadRiwayat1(false)
    }
    if (value == 3) {
      loadRiwayat1Verif(true)
    }
  }
)
watch(() => isNORM.value, (newValue, oldValue) => {
  if (newValue == true) {
    loadRiwayat1(false)
    loadRiwayat1Verif(false)
  }
})
onBeforeMount(async () => {
    try {
        await loadRiwayat1()
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
const changeFilter = (e: any) => {
  loadRiwayat1(false)
}

async function loadRiwayat1Verif(isVerify: any) {
  listRiwayat.value = []
  let params = `&norec_pd=${item.NOREC_PD}`
  if (isNORM.value == true) {
    params = ``
  }
  let penulis = ''
  if (item.filterPenulis) {
    penulis = `&penulisresepfk=${item.filterPenulis.id}`
  }
  await useApi().get(
    `/farmasi/riwayat-resep-verif?nocmfk=${ID_PASIEN}&verif=${isVerify}${params}${penulis}`).then((response: any) => {
      let z = 0
      for (let x = 0; x < response.length; x++) {
        const element = response[x];
        element.isExpand = true
        element.icon = 'fa-inverse lnir lnir-medicine-alt'
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

const getProdukListStok = async (e: any) => {

  let namaproduk
  await useApi().get(`farmasi/get-stok-produk-by-ruangan?produkfk=${e}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
      namaproduk = element.namaproduk
    });
    dataSourceStokProduk.value = response
    infoStok.value = `List Stok Produk ${namaproduk} Per Ruangan`
  })

}

function pasienByID(id: any) {
  if (props.pasien != undefined) {
    pasien.value = props.pasien
    item.NOREC_APD = props.registrasi.norec_apd
    item.RUANGAN_LAST = props.registrasi.objectruanganlastfk
    item.DEPARTEMEN_FK = props.registrasi.objectdepartemenfk
    item.registrasi = props.registrasi
  } else {
    isLoadingPasien.value = true
    useApi().get(`/general/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
      pasien.value = response.pasien
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.registrasi = response.last_registrasi
      isLoadingPasien.value = false
      // fetchTindakan(item.RUANGAN_LAST)
    })
  }

}

async function loadDrop() {
  const response = await useApi().get(`/farmasi/input-resep-cbo?ruanganfk=${item.RUANGAN_LAST}&departemenfk=${item.DEPARTEMEN_FK}`)
  d_aturanPakai.value = response.signa.map((e: any) => { return { aturanpakai: e.signa, id: e.id } })
  d_kemasan.value = response.jeniskemasan
  // d_Ruangan.value = response.ruanganFarmasi
  d_Ruangan.value = response.ruangan
  d_jenisRacikan.value = response.jenisracikan//.map((e: any) => { return { label: e.jenisracikan, value: e } })
  d_route.value = response.route//.map((e: any) => { return { label: e.name, value: e } })
  d_satuanResep.value = response.satuanresep//.map((e: any) => { return { label: e.satuanresep, value: e } })
  d_asalProduk.value = response.asalproduk//.map((e: any) => { return { label: e.asalproduk, value: e } })
  item.jenisKemasan = response.jeniskemasan[1]
  item.tarifadminresep = response.tarifadminresep ? response.tarifadminresep : 0
  console.log(response.ruangan_Def[0])
  console.log(d_Ruangan.value[0])
  d_Ruangan.value.forEach((element: any) => {
        if (element.id == response.ruangan_Def[0].id) {
            item.ruangan = element
        }
    });

  disabledRuangan.value = false;

  response.penulisresep.forEach(element => {
    if (item.registrasi.objectpegawaifk == element.id) {
      item.pegawaiOrder = element
      // item.filterPenulis = element
      return
    }
  });
}

function changeProduk(e: any) {
  chack(ID_PASIEN, e.id)
  if (e != null && e != undefined) {
    GETKONVERSI()
    getProdukListStok(e.id)
  }
}

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

function GETKONVERSI() {

  d_satuan.value = item.produk.konversisatuan
  if (item.produk.konversisatuan.length == 0) {
    d_satuan.value = [
      {
        ssid: item.produk.ssid, satuanstandar: item.produk.satuanstandar
      }]
  }
  item.produk.konversisatuan.forEach((element: any) => {
    if (element.ssid == item.produk.ssid) {
      item.satuan = element
      return
      // item.satuan = { ssid: element.ssid, satuanstandar: element.satuanstandar, nilaikonversi: element.nilaikonversi }
      // return
    }
  });
  d_satuan.value.forEach((element: any) => {
    if (element.ssid == item.produk.ssid) {
      item.satuan = element
    }
  })

  item.nilaiKonversi = 1
  isLoading.value = true
  dataProdukDetail.value = []
  useApi().get('/farmasi/get-produkdetail?produkfk=' + item.produk.id + '&ruanganfk=' + item.ruangan.id +
    "&kpid=" + item.registrasi.objectkelompokpasienlastfk +
    "&norec_apd=" + item.NOREC_APD).then(function (response: any) {
      if (response.detail.length > 0) {
        dataProdukDetail.value = response.detail
        item.stok = response.jmlstok / item.nilaiKonversi
        if (response.kekuatan == undefined || response.kekuatan == 0) {
          response.kekuatan = 1
        }
        item.kekuatan = response.kekuatan
        item.sediaan = response.sediaan
        item.tglKadaluarsa = response.detail[0]
        if (dataSelected.value.no != undefined) {

          item.jumlah = dataSelected.value.jumlah
          item.dosis = dataSelected.value.dosis
          item.jumlahxmakan = (parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan)
          item.nilaiKonversi = dataSelected.value.nilaikonversi
          d_satuan.value.forEach((element: any) => {
            if (element.ssid == dataSelected.value.satuanviewfk) {
              item.satuan = element
            }
          })
          item.hargaSatuan = dataSelected.value.hargasatuan
          item.hargadiskon = dataSelected.value.hargadiscount
          item.hargaNetto = dataSelected.value.harganetto
          item.total = dataSelected.value.total
        } else {
          if (!isMerge.value) {
            item.jumlah = 1
          }

        }

        setNorecSPD()
        isLoading.value = false
      } else {
        if (response.jmlstok == 0) {
          useToaster().warn(`Stok ${item.produk.namaproduk} belum ada`)
        }
        item.hargaSatuan = 0
        item.hargadiskon = 0
        item.stok = response.jmlstok
        item.hargaNetto = 0
        item.total = 0
        isLoading.value = false
      }

    });

}

const chack = async (nocmfk, produkid) => {
  await useApi().get(`/farmasi/check-obat-periode?produkfk=${produkid}&nocmfk=${nocmfk}`).then((response) => {
    if (response != null) {
      isLastObatByDate.value = true
      item.namaproduk = response.namaproduk
      item.tglpelayanan = response.tglpelayanan
    }
  })

}

// getDataExist()
fetchPasien()
</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
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

                <!-- form baru -->

                <div class="columns is-multiline p-2">
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Data">
                            <div class="column is-12">
                                <VField>
                                    <VLabel>Data Subjektif</VLabel>
                                    <VTextarea v-model="input.dataSubjektif" rows="3">
                                    </VTextarea>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField>
                                    <VLabel>Data Objektif</VLabel>
                                    <VTextarea v-model="input.dataObjektif" rows="3">
                                    </VTextarea>
                                </VField>
                            </div>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Diagnosis Pasca Operasi (ICD-10)" style="margin-bottom: 10px">
                            <VField>
                                <VControl>
                                    <AutoComplete v-model="input.diagnosaPraBedah" :suggestions="d_Diagnosa"
                                        @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Diagnosa" />
                                </VControl>
                            </VField>
                            <VField>
                                <VTextarea rows="2" v-model="input.disgnosisPasca"></VTextarea>
                            </VField>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Rencana Kerja Dokter (Plan Of Care)"
                            style="margin-bottom: 10px">
                            <div class="column is-12">
                                <div style="overflow-y:auto;" class="mt-1">
                                    <table class="tabels" border="1" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%" style="vertical-align: inherit;text-align:center">
                                                    NO</th>
                                                <th width="25%" style="vertical-align: inherit;text-align:center">
                                                    MASALAH</th>
                                                <th width="25%" style="vertical-align: inherit;text-align:center">
                                                    RENCANA INTERVENSI</th>
                                                <th width="25%" style="vertical-align: inherit;text-align:center">
                                                    TARGET (WAKTU & KONDISI YANG DIHARAPKAN)
                                                </th>
                                                <th style="vertical-align: inherit;text-align:center;" width="8%">
                                                    #
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody v-for="(input, index) in input.details" :key="index">
                                            <tr>
                                                <td class="td-po" style="text-align: center;">
                                                    <span class="label-ro">{{ index+1 }}</span>
                                                </td>
                                                <td class="td-po">
                                                    <div class="pb-0">
                                                        <VField>
                                                            <VControl icon="feather:bookmark">
                                                                <VInput type="text" v-model="input.daftarMasalah"
                                                                    placeholder="Daftar Masalah" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="pb-0">
                                                        <VField>
                                                            <VControl icon="feather:bookmark">
                                                                <VInput type="text" v-model="input.rencanaIntervensi"
                                                                    placeholder="Rencana Intervensi" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-po">
                                                    <div class="pb-0">
                                                        <VField>
                                                            <VControl icon="feather:bookmark">
                                                                <VInput type="text" v-model="input.target"
                                                                    placeholder="Target" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td class="td-rpo" style="vertical-align: inherit">
                                                    <VButtons style="justify-content:space-around">
                                                        <VIconButton type="button" raised circle icon="feather:plus"
                                                            @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                                        </VIconButton>
                                                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised
                                                            circle icon="feather:trash" @click="removeItem(index)"
                                                            color="danger">
                                                        </VIconButton>
                                                    </VButtons>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </Fieldset>
                    </div>
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Instruksi" style="margin-bottom: 10px">
                            <VField>
                                <VTextarea rows="2" v-model="input.TAInstruksi"></VTextarea>
                            </VField>
                        </Fieldset>
                    </div>

                    <div class="column is-12 mt-5">
                        <div class="columns column is-12">
                            <div class="column is-6">
                                <div class="column" style="text-align:center;">
                                    <h1>Tanda Tangan Dokter Pengkaji</h1>
                                    <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" />
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.CBDokter" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" class="mt-2" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="column" style="text-align:center;">
                                    <h1>Tanda Tangan DPJP</h1>
                                    <TandaTangan :elemenID="'TTDDokter2'" :width="'150'" :height="'150'" class="dek" />
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.CBDokter2" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" class="mt-2" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- form baru -->
            </div>
        </div>
    </div>
</template>
