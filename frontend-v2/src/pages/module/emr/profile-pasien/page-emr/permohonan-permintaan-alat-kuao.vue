<style lang="scss">
// .table {
//     border-collapse: collapse;
//     width: 100%;
// }

// .table td { border: 1px solid black !important }

// .table th {
//     text-align: center !important;
//     border: 1px solid black !important;
// }

h1 {
    font-weight: bold !important
}
</style>
<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Permohonan Permintaan Alat Khusus Untuk Acara Operasi</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="false" isHideST :isLockSimpan="idDisabledBtn"></ButtonEmr>

                        <!-- <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                            :disabled="isDisabled" @click="print">
                            Cetak
                        </VButton> -->
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <h2>Tanggal Rencana Operasi</h2>
                            <VDatePicker v-model="input.tglRencanaOperasi" mode="date" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-6">
                            <h2>Ruangan Satelit</h2>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'namaruangan'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namaruangan'" />
                            </VControl>
                        </div>
                        <div class="column is-12">
                            <h2>Kamar Operasi</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.kamarOperasi" />
                            </VControl>
                        </div>
                        <div class="columns is-12 is-multiline">
                            <div class="is-3" style="margin-top: 10px;margin-left: 20px;">
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" @click="DiagnosaAsmed()">
                                Diagnosa ASMED
                            </VButton>
                            </div>
                            <div class="is-3" style="margin-left: 10px;margin-top: 10px;">
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" @click="DiagnosaCPPT()">
                                Diagnosa CPPT
                            </VButton>
                            </div>
                        </div>
                        <div class="column is-12">
                            <h2>Diagnosa Medis</h2>
                            <VField>
                                <VTextarea rows="1" v-model="input.diagnosaMedis"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <h2>Rencana Tindakan</h2>
                            <VField>
                                <VTextarea rows="1" v-model="input.rencanaTindakan"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12 pt-0">
                            <h2>Rencana Anastesi</h2>
                            <VField>
                                <VTextarea rows="1" v-model="input.rencanaAnastesi"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12 pt-0">
                            <h2>Jenis Operasi</h2>
                            <VField>
                                <VTextarea rows="1" v-model="input.jenisOperasi"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12 pt-0">
                            <h2>Operator</h2>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.operator" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                        </div>
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-12 pt-0">
                            <h2>Peralatan Khusus yang dipesan</h2>
                            <VField>
                                <VTextarea rows="5" v-model="input.peralatanKhususYangDipesan"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12 pt-0">
                            <h2>Implan yang dipesan</h2>
                            <VField>
                                <VTextarea rows="5" v-model="input.implanYangDipesan"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-4 pt-0" style="text-align: center;">
                            <h2 style="font-weight: bold;">Pemohon</h2>
                            <TandaTangan :elemenID="'TTDPemohon'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.pemohon" :suggestions="d_Pegawai" class="mt-2"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0" style="text-align: center;">
                            <h2 style="font-weight: bold;">Depo Farmasi</h2>
                            <TandaTangan :elemenID="'TTDDepoFarmasi'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.depoFarmasi" :suggestions="d_Pegawai" class="mt-2"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0" style="text-align: center;">
                            <h2 style="font-weight: bold;">Kepala Ruangan</h2>
                            <TandaTangan :elemenID="'TTDKepalaRuangan'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.kepalaRuangan" :suggestions="d_Pegawai" class="mt-2"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useToaster } from '/@src/composable/toaster'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
// import InputText from 'primevue/inputtext';

useHead({ title: 'Permohonan Permintaan Alat Khusus Untuk Acara Operasi - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let IS_NEWRESEP = useRoute().query.isnewresep ? useRoute().query.isnewresep as string : false
const COLLECTION: any = ref('PermohonanPermintaanAlatKUAO')
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({})
const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
const idDisabledBtn: any = ref(false)
const d_Ruangan: any = ref([])
// const d_Dokter: any = ref([])
// const listTemplate: any = ref([])
// const showModalTemplate: any = ref(false)
// const listTemplateFix: any = ref([])
// const showModalTemplateFix: any = ref(false)


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

let PilihdiagnosaAsmed = 0;
let PilihdiagnosaCPPT = 0;

const DiagnosaAsmed = async () => {
  PilihdiagnosaAsmed++;
  if  (PilihdiagnosaAsmed >= 2) {
    input.value.diagnosaMedis = '';
    PilihdiagnosaAsmed = 0;
  } else {
    const response_Asmed = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenMedisRawatJalan" + `&field=TADiagnosa`)
    if (response_Asmed != null) {
    input.value.diagnosaMedis = response_Asmed.TADiagnosa
    }
  }
}

const DiagnosaCPPT = async () => {
  PilihdiagnosaCPPT++;
  if  (PilihdiagnosaCPPT >= 2) {
    input.value.diagnosaMedis = '';
    PilihdiagnosaCPPT = 0;
  } else {
    const response_CPPT = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail" + `&field=A`)
    if (response_CPPT != null) {
    input.value.diagnosaMedis = response_CPPT.A
    }
  }
}

const setAutoFill = async () => {
    const response_PersetujuanTindakan = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=PersetujuanTindakanKedokteran" + `&field=persetujuan`)
    if (response_PersetujuanTindakan != null) {
    input.value.rencanaTindakan = response_PersetujuanTindakan.persetujuan
    }
}

const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDPemohon", dataTTD.value.TTDPemohon)
        H.tandaTangan().set("TTDDepoFarmasi", dataTTD.value.TTDDepoFarmasi)
        H.tandaTangan().set("TTDKepalaRuangan", dataTTD.value.TTDKepalaRuangan)
        // idDisabledBtn.value = true
    } else {
        let d = input.value
        // d.tglRencanaOperasi = new Date()
        // input.value.DD = { label: user.namaLengkap, value: user.id }
    }
}

const simpan = () => {
    if(!input.value.ruangan)
    {
        useToaster().error(`Silahkan Pilih Ruangan Terlebih Dahulu!`);
        return;
    }
    if (!input.value.tglRencanaOperasi) {
        useToaster().error(`Silahkan Isi Tanggal Operasi!`);
        return;
    }

    if(!input.value.kamarOperasi)
    {
        useToaster().error(`Silahkan Isi Kamar Operasi!`);
        return;
    }

    if(!input.value.peralatanKhususYangDipesan )
    {
        useToaster().error(`Silahkan Isi Peralatan Khusus!`);
        return;
    }
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object['TTDPemohon'] = H.tandaTangan().get("TTDPemohon");
    object['TTDDepoFarmasi'] = H.tandaTangan().get("TTDDepoFarmasi");
    object['TTDKepalaRuangan'] = H.tandaTangan().get("TTDKepalaRuangan");
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
    var objSave = {
        'tglrencanaoperasi' : input.value.tglRencanaOperasi ? input.value.tglRencanaOperasi : null,
        'ruangansatelit' : input.value.ruangan ? input.value.ruangan.id : null,
        'noregistrasifk' : props.registrasi.norec_apd,
        'nopasiendaftar' : props.registrasi.norec_pd,
        'kamaroperasi' : input.value.kamarOperasi ? input.value.kamarOperasi: null,
        'diagnosamedis' : input.value.diagnosaMedis ? input.value.diagnosaMedis : null,
        'rencanatindakan' : input.value.rencanaTindakan ? input.value.rencanaTindakan : null,
        'rencanaoperasi' : input.value.rencanaAnastesi ? input.value.rencanaAnastesi : null,
        'jenisoperasi' : input.value.jenisOperasi ? input.value.jenisOperasi : null,
        'operator' : input.value.operator ? input.value.operator.label : null,
        'peralatankhusus' : input.value.peralatanKhususYangDipesan ? input.value.peralatanKhususYangDipesan : null,
        'implandipesan' : input.value.implanYangDipesan ? input.value.implanYangDipesan : null,
        'pemohon' : input.value.pemohon ? input.value.pemohon.label : null,
        'farmasi' : input.value.depoFarmasi ? input.value.depoFarmasi.label : null,
        'kepalaruangan' : input.value.kepalaRuangan ? input.value.kepalaRuangan.label : null,
        'norec_emr' : NOREC_EMRPASIEN.value,
    }
    console.log('data apa',objSave)
    useApi().post(
        `/farmasi/simpan-permohonan-permintaan-alat-kuao`, objSave).then((response: any) => {
            // isLoading.value = false
            sendNotification(response);
        }).catch((e: any) => {
            // isLoading.value = false
        })

    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        idDisabledBtn.value = true
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
        idDisabledBtn.value = true
    })
    idDisabledBtn.value = true;
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}
// const fetchDokter = async (filter: any) => {
//     await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {d_Dokter.value = response})
// }

const fetchRuangan = async (filter: any) => {
    await useApi().get(`/farmasi/input-resep-cbo?isnewresep=${IS_NEWRESEP}&departemenfk=14`).then((response)=> {d_Ruangan.value = response.ruangan})
}

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&noregistrasi=${props.registrasi.noregistrasi}&pdf=true`)
};

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

setAutoFill();
</script>
