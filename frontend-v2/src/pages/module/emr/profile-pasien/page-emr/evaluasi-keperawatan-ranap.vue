<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Evaluasi Keperawatan</h3>
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

                <Fieldset :toggleable="true" legend="Data MRS" class="mt-3 mb-3">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VField label="Ruangan Asal">
                                <VControl icon="feather:map-pin">
                                    <VInput type="text" placeholder="" autocomplete="off" v-model="input.namaruangan"
                                        disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Diagnosa Medis : ">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.diagnosamedis"
                                        placeholder="Diagnosa Medis..." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Tanggal / Jam MRS">
                                <VDatePicker v-model="input.tanggalmrs" mode="datetime" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>
                </Fieldset>

                <hr>

                <Fieldset :toggleable="true" legend="Evaluasi" class="mt-3 mb-3">
                    <div class="column is-12 columns is-multiline">
                        <div class="column is-12 pb-0">
                            <table class="tg2" style="width: auto;">
                                <thead>
                                    <tr>
                                        <th class="th-popri" width="20%">Tanggal / Jam</th>
                                        <th class="th-popri" width="10%">No. Dx</th>
                                        <th class="th-popri" width="40%">Evaluasi</th>
                                        <th class="th-popri" width="30%">Nama / Paraf</th>
                                        <th class="th-popri" width="10%">#</th>
                                    </tr>
                                </thead>
                                <tbody v-for="(item, index) in input.details" :key="index">
                                    <tr>
                                        <td class="td-popri">
                                            <VDatePicker v-model="item.tgljamrencana" mode="datetime"
                                                style="width: 100%; padding-top:10px" is24hr>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField style="margin-bottom: 0.70rem;">
                                                        <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" placeholder="Tanggal"
                                                                v-on="inputEvents" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </td>
                                        <td class="td-popri" style="text-align: center;">
                                            <!-- <span>{{ index + 1 }}</span> -->
                                            <VControl>
                                                <VInput type="text" class="input" v-model="item.TBNodx" />
                                            </VControl>
                                        </td>
                                        <td class="td-popri">
                                            <div class="columns" style="padding: 5px">
                                                <div class="column is-12">
                                                    <div class="columns">
                                                        <div class="column is-12">
                                                            <h1>S</h1>
                                                            <VField>
                                                                <VTextarea rows="2" v-model="item.evalS">
                                                                </VTextarea>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                    <div class="columns">
                                                        <div class="column is-12">
                                                            <h1>O</h1>
                                                            <VField>
                                                                <VTextarea rows="2" v-model="item.evalO">
                                                                </VTextarea>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                    <div class="columns">
                                                        <div class="column is-12">
                                                            <h1>A</h1>
                                                            <VField>
                                                                <VTextarea rows="2" v-model="item.evalA">
                                                                </VTextarea>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                    <div class="columns">
                                                        <div class="column is-12">
                                                            <h1>P</h1>
                                                            <VField>
                                                                <VTextarea rows="2" v-model="item.evalP">
                                                                </VTextarea>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="td-popri">
                                            <VField>
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="item.pegawai" :suggestions="d_Petugas"
                                                        @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="Cari Pegawai..." />
                                                </VControl>
                                            </VField>
                                        </td>
                                        <!-- <td class="td-popri">
                                            <TandaTangan :elemenID="'TTDBidan'" :width="'150'" :height="'150'" class="dek" />
                                        </td> -->
                                        <td class="td-popri">
                                            <VButtons>
                                                <VIconButton type="button" raised circle icon="feather:plus"
                                                    @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                                </VIconButton>
                                                <VIconButton v-if="index > 0" class="mt-1" type="button" raised circle
                                                    icon="feather:trash" @click="removeItem(index)" color="danger">
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
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';


// Judul
useHead({ title: 'Evaluasi Keperawatan Rawat Inap - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const user = useUserSession().getUser().pegawai;
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
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&query=${filter.query}&param_search=namalengkap&limit=10`).then((response) => {
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
const COLLECTION: any = ref('EvaluasiKeperawatan') //table mongodb
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
const input: any = ref({
    details: [{
        no: 1,
        tgljamrencana: new Date(),
        pegawai: { label: user.namaLengkap, value: user.id }
    }],
    namaruangan: props.registrasi.namaruangan,
    tanggalmrs: new Date()
})

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
    // object['ttdPegawai'] = H.tandaTangan().get("ttdPegawai");
    // object['TTDDokterPemeriksa'] = H.tandaTangan().get("TTDDokterPemeriksa");
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
    input.value.details.unshift({
        no: input.value.details.length > 0 ? input.value.details[0].no + 1 : 1,
        tgljamrencana: new Date(),
        pegawai: { label: user.namaLengkap, value: user.id }
    });
};
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
    text-align: center !important;
    font-weight: bold;
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

.p-fieldset-content {
    background-color: white !important;
}
</style>