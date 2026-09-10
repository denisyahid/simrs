<template>
    <MasterEMR :isTTD="true" :fieldTTD="'peralihanDPJP'" @simpan="simpan()" @simpanTemplate="simpanTemplate()"
        :ID_PASIEN="ID_PASIEN" :NOREC_PD="NOREC_PD" :norec_emr="norec_emr" :input="input" :FORM_NAME="props.FORM_NAME"
        :FORM_URL="props.FORM_URL" :registrasi="props.registrasi" :pasien="props.pasien" :COLLECTION="props.COLLECTION"
        ref="masterRef" :isLoading="isLoading">
        <template #content>
            <VCard>
                <div class="columns is-multiline m-0">
                    <div class="column is-12">
                        <h1 style="font-weight: bold">Ruangan:</h1>
                        <VControl>
                            <VInput type="text" class="input" placeholder="Ruangan" v-model="input.ruangan" disabled />
                        </VControl>
                    </div>
                </div>
                <div class="columns is-multiline m-0">
                    <div class="column is-4">
                        <span class="label-apb">Tanggal Tindakan</span>
                        <VField class="pt-2">
                            <VControl raw subcontrol>
                                <VInput type="date" v-model="input.dateTindakan"></VInput>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <span class="label-apb">Jam mulai tindakan</span>
                        <VField class="pt-2">
                            <VControl raw subcontrol>
                                <VInput type="time" v-model="input.jamMulaiTIndakan"></VInput>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <span class="label-apb">Jam selesai tindakan</span>
                        <VField class="pt-2">
                            <VControl raw subcontrol>
                                <VInput type="time" v-model="input.jamSelesaiTindakan"></VInput>
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline m-0">
                    <div class="column is-4">
                        <div>
                            <h1>Dokter Operator</h1>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.dokterOperator" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                    :field="'label'" class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-4">
                        <div>
                            <h1>Asisten bedah</h1>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.asistenbedah" :suggestions="d_Petugas"
                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                    :field="'label'" class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-4">
                        <div>
                            <h1>Perawat</h1>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.perawat" :suggestions="d_Petugas"
                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                    :field="'label'" class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline pl-3">
                    <div class="column is-12">
                        <div class="columns is-multiline pt-3">
                            <div class="column is-4">
                                <VField label="Diagnosa Prabedah">
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="" v-model="input.diagnosaPrabedah" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Jenis Pembedahan">
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="" v-model="input.jenisPembedahan" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Diagnosa pasca bedah">
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="" v-model="input.diagnosaPascaBedah" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline pl-3">
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">Keadaan prabedah</h1>
                        <div class="columns is-multiline pt-3">
                            <div class="column is-4">
                                <VField label="Kesadaran">
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="" v-model="input.keadaanKesadaran" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Kondisi Luka">
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="" v-model="input.keadaanKondisiLuka" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField label="Riwayat Alergi">
                                            <VControl raw subcontrol>
                                                <VCheckbox true-value="Ya" label="Ya" class="p-0"
                                                    color="primary" square v-model="input.riwayatAlergi" />
        
                                                    <VInput type="text" class="input" placeholder="" v-model="input.detailRiwayatAlergi" v-if="input.riwayatAlergi == 'Ya'" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VControl raw subcontrol>
                                                <VCheckbox true-value="Tidak" label="Tidak" class="p-0"
                                                    color="primary" square v-model="input.riwayatAlergi" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </VCard>
            <VCard class="mt-5">
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <h1 style="font-weight: bold; margin-bottom: 1.5rem;">
                        Pemberian Anestesi Local
                    </h1>
                    <table border="1" width="100%" style="border: 1px solid var(--fade-grey-dark-3);"
                        class="table-v-center">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th style="width: 100px;">JAM</th>
                                <th>NAMA OBAT</th>
                                <th>CARA PEMBERIAN</th>
                                <th>LOKASI</th>
                                <th>TOTAL DOSIS</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody style="vertical-align: middle;">
                            <tr v-for="(item, index) in input.details">
                                <td>
                                    {{ index+1 }}
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput type="time" v-model="item.jam" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField class="pt-2">
                                        <VControl raw subcontrol>
                                            <VInput type="text" class="input" v-model="item.namaObat" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="item.caraPemberian" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="item.lokasi" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="item.totalDosis" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VIconButton type="button" raised circle icon="feather:plus"
                                        outlined color="info" @click="addItemDetail()">
                                    </VIconButton>
                                    <VIconButton type="button" raised circle icon="lucide:trash" v-if="index > 0"
                                        outlined class="ml-2" color="danger" @click="deleteItemDetail(index)">
                                    </VIconButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0 mt-3">
                    <div style="overflow: auto;">
                        <table class="tg">
                            <thead>
                                <tr>
                                    <th width="10%" class="col-stuck">Waktu</th>
                                    <th v-for="index in jumlahIndexVS">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['waktu_' + index]" type="time"
                                                    placeholder="Pick an hour" />
                                            </VControl>
                                        </VField>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>Suhu</span>
                                    </td>
                                    <td v-for="index in jumlahIndexVS">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['suhu_' + index]" type="number" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>N</span>
                                    </td>
                                    <td v-for="index in jumlahIndexVS">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['n_' + index]"
                                                    type="number" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>TD</span>
                                    </td>
                                    <td v-for="index in jumlahIndexVS">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['td_' + index]"
                                                    type="text" class="input" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>Respirasi</span>
                                    </td>
                                    <td v-for="index in jumlahIndexVS">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['respirasi_' + index]"
                                                    type="number" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>Kesadaran</span>
                                    </td>
                                    <td v-for="index in jumlahIndexVS">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['kesadaran_' + index]"
                                                    type="number" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <span>Skala Nyeri</span>
                                    </td>
                                    <td v-for="index in jumlahIndexVS">
                                        <VField>
                                            <VControl>
                                                <VInput v-model="input['skalaNyeri_' + index]"
                                                    type="number" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-stuck bg-colatas">
                                        <!-- <VIconButton color="info" light raised circle
                                        icon="feather:plus-circle" /> -->
                                        <VButton type="button" color="info" raised rounded
                                        icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                        @click="addIndexVS()">
                                        Tambah
                                        </VButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="columns is-multiline pt-3">
                    <div class="column is-12">
                        <h1 style="font-weight: bold;" class="text-center">Grafik Tanda Vital</h1>
                        <VCard style="border-radius: 16px;">
                            <highcharts :options="chartOptions"></highcharts>
                        </VCard>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;" class="text-center">Grafik Tekanan Darah</h1>
                        <VCard style="border-radius: 16px;">
                            <highcharts :options="chartOptionsTD"></highcharts>
                        </VCard>
                    </div>
                </div>
            </VCard>
            <VCard class="mt-5">
                <div class="columns is-multiline m-0">
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-6"></div>
                            <div class="column is-6">
                                <VField label="Garut">
                                    <VDatePicker v-model="input.tanggalPengisian" mode="datetime" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-6"></div>
                            <div class="column is-6">
                                <div style="text-align:center;">
                                    <h1>Petugas Pemeriksa</h1>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.petugasPemeriksa" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" class="mt-2" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </VCard>
        </template>
    </MasterEMR>
</template>

<script setup lang="ts">
import MasterEMR from './master-emr.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let route = useRoute()
const masterRef = ref(null)
const dataPasien = '';
const d_Dokter: any = ref([]);
const d_Petugas: any = ref([]);
const dataTTD: any = ref([])
const NOREC_EMRPASIEN: any = ref('')
const isLoading: any = ref(false);
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const jumlahIndexVS = ref(15)
const sudahDisimpan: any = ref(false)

let chartOptions = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
            },
            enableMouseTracking: false
        }
    },
    series: []
});

let chartOptionsTD = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
                // style: {
                //   fontSize: '20px'
                // },
            },
            enableMouseTracking: false
        }
    },
    series: []
});

const chartHigh = (e: any) => {
    let labels = []
    let seriesKesadaran = []
    let seriesSuhu = []
    let seriesRespirasi = []
    let seriesSaturasi = []
    let seriesSkala = []
    for (let x = 0; x < jumlahIndexVS.value; x++) {
        if (e['suhu_' + x.toString()] != undefined) {
            seriesSuhu.push(parseFloat(e['suhu_' + x.toString()]))
        }
        if (e['n_' + x.toString()] != undefined) {
            seriesSaturasi.push(parseFloat(e['n_' + x.toString()]))
        }
        if (e['waktu_' + x.toString()] != undefined) {
            labels.push(e['waktu_' + x.toString()])
        }
        if (e['respirasi_' + x.toString()] != undefined) {
            seriesRespirasi.push(parseFloat(e['respirasi_' + x.toString()]))
        }
        if (e['kesadaran_' + x.toString()] != undefined) {
            seriesKesadaran.push(parseFloat(e['kesadaran_' + x.toString()]))
        }
        if (e['skalaNyeri_' + x.toString()] != undefined) {
            seriesSkala.push(parseFloat(e['skalaNyeri_' + x.toString()]))
        }
    }

    chartOptions.xAxis.categories = labels;
    chartOptions.series =
        [{
            name: 'Suhu',
            color: 'red',
            lineWidth: 4,
            marker: {
                radius: 4
            },
            data: seriesSuhu
        },
        {
            name: 'N',
            data: seriesSaturasi
        },
        {
            name: 'Respirasi',
            data: seriesRespirasi
        },
        {
            name: 'Kesadaran',
            data: seriesKesadaran
        },
        {
            name: 'Skala Nyeri',
            data: seriesSkala
        },
    ]
}

const chartHighTD = (e: any) => {
    let labels = []
    let seriesDiastolik = []
    let seriesSistolik = []

    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndexVS.value; x++) {
        if (e['td_' + x.toString()] != undefined) {
            let inputString = e['td_' + x.toString()];
            let resultArray = inputString.split('/').map(Number);
            seriesSistolik.push(parseFloat(resultArray[0]))
            seriesDiastolik.push(parseFloat(resultArray[1]))
        }
    }

    chartOptionsTD.xAxis.categories = labels
    chartOptionsTD.series =
    [{
        name: 'Sistolik',
        color: 'red',
        lineWidth: 4,
        marker: {
            radius: 4
        },
        data: seriesSistolik
    }, {
        name: 'Diastolik',
        color: 'blue',
        data: seriesDiastolik
    },]
}

const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
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
const input: any = ref({
    tanggal: new Date(),
    Jam: new Date(),
    tanggalPengisian: new Date(),
    details: [
        {jam: new Date(), namaObat: null, caraPemberian: null, lokasi: null, totalDosis: null}
    ],
})

const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    DEPARTEMEN_FK: props.registrasi.objectdepartemenfk,
    registrasi: {
        ruanganfk: props.registrasi.objectruanganlastfk,
        departemenfk: props.registrasi.objectdepartemenfk,
    }
})


const fetchDokter = async (filter: any) => {
    d_Dokter.value = await H.fetchDokter(filter);
}

const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
}


const setAutoFill = async () => {
    input.value.DPJP = props.registrasi.dokter
    input.value.DokterPenanggungJawab = props.registrasi.dokter
    input.value.ruangan = props.registrasi.namaruangan
};

const loadRiwayat = async () => {
    isLoading.value = true;
    await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        isLoading.value = false;
        if (response.length) {
            input.value = response[0] //set ke inputan
            if (NOREC_EMRPASIEN.value == '') {
                NOREC_EMRPASIEN.value = response[0].emrpasienfk
            }

            chartHigh(response[0])
            chartHighTD(response[0])
            sudahDisimpan.value = true;
        }
        else {
            setAutoFill()
            sudahDisimpan.value = true;
        }
    })
    sudahDisimpan.value = true;
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    console.log("Pasien", props.pasien);
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDpasien'] = H.tandaTangan().get('TTDpasien')

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
            loadRiwayat();
            sudahDisimpan.value = true;
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('warning', "Nama Template wajib diisi")
        return;
    }
    let ID = input.id ? input.id : ''

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
        `/emr/simpan-emr-template`, json).then((response: any) => {
            sudahDisimpan.value = true;
            isLoading.value = false
            input.value.namatemplate = null
        }).catch((e: any) => {
            isLoading.value = false
        })
}


const triggerAllData = async () => {
    if (masterRef.value) {
        let ss = await masterRef.value.loadRiwayat()
        sudahDisimpan.value = true;
        if (ss != null) {
            input.value = ss
        }
    }
}

const addIndexVS = () => {
    jumlahIndexVS.value = jumlahIndexVS.value + 1;
}

const addItemDetail = () => {
    input.value.details.push({
        jam: new Date(),
        namaObat: null,
        caraPemberian: null,
        lokasi: null,
        totalDosis: null
    })
}

const deleteItemDetail = (index) => {
    input.value.details.splice(index, 1);
}

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name + '-' + route.params.index_tabs
        if (!sudahDisimpan.value) {
            console.log("DISIMPAN",sudahDisimpan.value);
            
            const konfirmasi = H.alert('warning', 'Belum Disimpan!!!');
            if (!konfirmasi) {
                return next(false);
            }
        }
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});

watch(
  input,
  (newVal, oldVal) => {
    console.log("watch triggered", newVal);
    sudahDisimpan.value = false;
  },
  { deep: true }
)


onMounted(() => {
    triggerAllData()
    setView()
    fetchDokter();
    loadRiwayat();
})


</script>

<style lang="scss">
.text-bold {
    font-weight: bold;
}

.table-v-center td {
    vertical-align: middle !important;
    padding: 5px;
    padding-left: 10px;
}
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
</style>