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
    waktuTataLaksana: new Date,
    waktuKontrol: new Date,
    jamKedatangan: new Date,
    jamAsesmenAwal: new Date,
    tanggalKedatangan: new Date,
    details: [{
        no: 1,
    }],
    instruksi:[{
        no:1
    }]
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
const COLLECTION: any = ref('TbakKeperawatan') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const loadRiwayat = async () => {
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
    // H.tandaTangan().set("TTDBidan", dataTTD.value.TTDBidan)
    H.tandaTangan().set("TTDDokterPemeriksa", dataTTD.value.TTDDokterPemeriksa)
}
const simpan = () => {
    const ID = input.value.id || '';
    const object = { ...input.value };

    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
    object.pasien = H.setObjectPasien(props.pasien);
    object.registrasi = H.setObjectRegistrasi(props.registrasi);

    const json = {
        id: ID,
        norec_emr: NOREC_EMRPASIEN.value,
        collection: COLLECTION.value,
        url_form: props.FORM_URL,
        name_form: props.FORM_NAME,
        jenis_emr: 'tbak-keperawatan',
        data: object,
    };

    isLoading.value = true;

    useApi().post('/emr/simpan-emr', json)
        .then((response) => {
            isLoading.value = false;
            NOREC_EMRPASIEN.value = response.norec_emr;
            input.value.id = response.id;
        })
        .catch((error) => {
            isLoading.value = false;
            console.error("Error saving EMR:", error); // Log the error for debugging
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

watch(
  () => activeValue.value,
  (value) => {
    if (value == 2) {
      loadRiwayat(false)
    }
    if (value == 3) {
      loadRiwayatVerif(true)
    }
  }
)
watch(() => isNORM.value, (newValue, oldValue) => {
  if (newValue == true) {
    loadRiwayat(false)
    loadRiwayatVerif(false)
  }
})
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
const changeFilter = (e: any) => {
  loadRiwayat(false)
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
                            <h3>TBAK Keperawatan</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <!--
                                <VButton type="button" rounded outlined color="warning"
                                    :disabled="NOREC_EMRPASIEN == undefined" raised icon="lnir lnir-printer"
                                    @click="print()"> Cetak
                                </VButton>
                                -->
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading || isLoadingPasien" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
                      <div class="column is-4">
                                <h1 style="font-weight: bold;" class="pb-1">Tanggal</h1>
                                <VField>
                                    <VDatePicker v-model="input.tanggalKedatangan" mode="date" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"
                                                        class="is-rounded" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold;" class="pb-1">Jam</h1>
                                <VField>
                                    <VDatePicker v-model="input.jamKedatangan" color="green" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:clock">
                                                    <VInput class="input form-timepicker is-rounded" :value="inputValue"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                        <div class="columns is-multiline p-2">
                            <div class="column is-12">
                                    <div class="column is-10">
                                    <h1 style="font-weight: bold;" class="pb-1">Nama</h1>

                                        <VField>
                                            <VTextarea v-model="input.TAANama" rows="3">
                                            </VTextarea>
                                        </VField>
                                    </div>
                            </div>
                           </div>
                    <div class="columns">
                    <div class="column is-8"></div>
                    <div class="column is-4">
                        <VField label="Garut">
                            <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                        <div class="column" style="text-align:center;">
                            <h1>Tanda Tangan Bidan</h1>
                            <TandaTangan :elemenID="'TTDBidan'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.CBBidan" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
            </div>
        </div>
    </div>
</template>
