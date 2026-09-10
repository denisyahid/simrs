<template>
    <ConfirmDialog />
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Observasi Keseimbangan Cairan</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true">
                            </ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                        isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                    </VButton>
                    <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                        isLoading="false" @click="pilihTemplate(index)"> Pilih Riwayat
                    </VButton>
                </div>

                <hr>

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

                <hr>

                <Fieldset :toggleable="true" legend="Pemasukan & Pengeluaran" class="mt-3 mb-3" style="overflow: auto;">
                    <div class="column" style="overflow: auto;">
                        <table class="tg" style="overflow: auto; max-width: 1900px;">
                            <thead>
                                <tr>
                                    <th style="text-align: center;vertical-align: middle;" colspan="8">Pemasukan</th>
                                    <th style="text-align: center;vertical-align: middle;" colspan="11" >Pengeluaran</th>
                                </tr>
                                <tr>
                                    <!-- Start Pemasukan -->
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3" width="17%">Tgl/Jam</th>
                                    <th style="text-align: center;vertical-align: middle;" colspan="2">Parenteral</th>
                                    <th style="text-align: center;vertical-align: middle;" colspan="2">Oral</th>
                                    <th style="text-align: center;vertical-align: middle;" colspan="2">Enterial</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">Total Cairan Masuk</th>
                                    <!-- End Pemasukan -->

                                    <!-- Start Pengeluaran -->
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">Muntah Warna</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">Drain WSD</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">BAK (cc) Warna</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">BAB</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">NGT (cc)</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">IWL (cc)</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">Total Cairan Keluar</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">CVP</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="3">#</th>
                                    <!-- End Pengeluaran -->
                                </tr>
                                <tr>
                                    <!-- Start Pemasukan -->
                                    <th style="text-align: center;vertical-align: middle;">Jenis</th>
                                    <th style="text-align: center;vertical-align: middle;">Jml(cc)</th>
                                    <th style="text-align: center;vertical-align: middle;">Jenis</th>
                                    <th style="text-align: center;vertical-align: middle;">Jml(cc)</th>
                                    <th style="text-align: center;vertical-align: middle;">Jenis</th>
                                    <th style="text-align: center;vertical-align: middle;">Jml(cc)</th>
                                    <!-- End Pemasukan -->

                                </tr>
                            </thead>
                            <tbody v-for="(item, index) in input.details" :key="index">
                                <tr>
                                    <td>
                                        <VDatePicker v-model="item.tgljamrencana" mode="datetime" style="width: 100%; padding-top:10px" trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.70rem;">
                                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VInput type="text" class="input" placeholder=""
                                                    v-model="item.jnsparental" />
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.jmlparental" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder=""
                                                v-model="item.jnsoral" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.jmloral" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder=""
                                                v-model="item.jnsenterial" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.jmlenterial" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.totalcairanmsk" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.mutahwarna" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.drainwsd" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.bakwarna" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.bab" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.ngt" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.iwl" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.totalcairanklr" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder=""
                                                v-model="item.cvp" />
                                        </VControl>
                                    </td>

                                    <td>
                                        <VButtons>
                                            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                                                v-tooltip.bubble="'Tambah '">
                                            </VIconButton>
                                            <VIconButton v-if="index > 0" class="mt-1" type="button" raised circle icon="feather:trash"
                                                @click="removeItem(index)" color="danger">
                                            </VIconButton>
                                        </VButtons>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Fieldset>
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
                                    <td class="tg-0lax text-center" width="15%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="15%">Tanggal Registrasi</td>
                                    <td class="tg-0lax text-center" width="15%">No Registrasi</td>
                                    <td class="tg-0lax text-center" width="15%">No EMR</td>
                                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                                    <td class="tg-0lax text-center" width="15%">Section</td>
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
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
                                    <td class="tg-0lax text-center" width="5%">No</td>
                                    <td class="tg-0lax text-center" width="15%">Tanggal Dibuat</td>
                                    <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                                    <td class="tg-0lax text-center" width="25%">Nama Template</td>
                                    <td class="tg-0lax text-center" width="15%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplateFix">
                                <tr>
                                    <td style="width:5%;text-align:center">
                                        <span class="mb-2">{{ resep.no }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.namatemplate }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
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
import { h, reactive, ref, computed, watch, onBeforeMount, watchEffect } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'

// Judul
useHead({
    title: 'Observasi Keseimbangan Cairan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
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
const fetchPetugas = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&limit=10`).then((response) => {
        d_Petugas.value = response
    })
}
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
const d_Petugas: any = ref([])
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
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const alertMid = ref(false);
const d_Dokter: any = ref([])
const confirm = useConfirm();
const input: any = ref({
  details: [{
    jmlparental: 0,
    jmloral: 0,
    jmlenterial: 0,
    totalcairanmsk: 0,
    mutahwarna: 0,
    drainwsd: 0,
    bakwarna: 0,
    bab: 0,
    ngt: 0,
    iwl: 0,
    totalcairanklr: 0,
  }]
});

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const loadRiwayat = async () => {
    isLoading.value = true
    let histori = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (histori.length) {
        input.value = histori[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = histori[0].emrpasienfk
        }
    }
    isLoading.value = false
}
const simpan = () => {
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
    console.log(json)

    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const simpanTemplate = () => {
    if(!input.value.namatemplate) {
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

watchEffect(() => {
  if (!input.value?.details?.length) return; // Pastikan details ada dan memiliki elemen

  input.value.details.forEach((item) => {
    let total =
      parseFloat(item.jmlparental || 0) +
      parseFloat(item.jmloral || 0) +
      parseFloat(item.jmlenterial || 0);

    item.totalcairanmsk = total;
    let totalkeluar =
      parseFloat(item.mutahwarna || 0) +
      parseFloat(item.drainwsd || 0) +
      parseFloat(item.bakwarna || 0) +
      parseFloat(item.bab || 0) +
      parseFloat(item.ngt || 0) +
      parseFloat(item.iwl || 0);

    item.totalcairanklr = totalkeluar;
    item.cvp = total - totalkeluar;
  });
});

const setRoutingEMR = (form: any, norec_emr: any) => {

let query: any = {}
let params: any = {}
console.log("DATA ITEM", item);

if (NOREC_EMRPASIEN.value != '') {
  query = {
    nocmfk: pasien.value.nocmfk,
    norec_pasien_daftar: item.NOREC_PD,
    norec_pd: item.NOREC_PD,
    norec_apd: item.NOREC_APD,
    jenisobgyn: '',
    norec_emr: NOREC_EMRPASIEN.value,
  }
} else {
  query = {
    nocmfk: pasien.value.nocmfk,
    norec_pasien_daftar: item.NOREC_PD,
    norec_pd: item.NOREC_PD,
    norec_apd: item.NOREC_APD,
    jenisobgyn: '',
  }
}

console.log(query)
if (form.indexOf('index_tab') > -1) {
  params = {
    index_tabs: 1
  }
}
router.push({
  name: form,
  query: query,
  params: params
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

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
    confirm.require({
    message: 'Apakah anda yakin ingin menghapus kolom?',
    header: 'Hapus Kolom',
    icon: 'pi pi-exclamation-triangle',
    acceptClass: 'p-button-danger',
    accept: () => {
      input.value.details.splice(index, 1);
    },
    reject: () => {
      console.log('Penghapusan dibatalkan');
    }
  });
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

fetchPasien();

</script>

<style lang="scss">
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
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg2 th {
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
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
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

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 0px;
}
.alert-nobg {
    background: none !important;
    height: 5em;
    width: 5em;

}
</style>
