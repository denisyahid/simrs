<template>
    <ConfirmDialog />
    <div class="form-layout is-stacked-2">

        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Ordotogram</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <!-- <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton> -->
                            <!-- <VButton type="submit" color="primary" raised icon="feather:save" :loading="isLoading"
                                @click="simpan()"> Save
                            </VButton> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="column">
                <!-- <canvas id="myCanvas" width="1100" height="450" style="width:69rem;border:1px solid #000000;">
                    Your browser does not support the HTML5 canvas tag.</canvas> -->
            </div>
            <!-- <div class="column">
                <VButtons>
                    <VButton color="primary" raised @click="BelumErupsi">
                        Button
                    </VButton>
                    <VButton color="info" raised @click="showModal">
                        Show Modal
                    </VButton>
                    <VButton color="success" raised>
                        Button
                    </VButton>
                    <VButton color="warning" raised>
                        Button
                    </VButton>
                    <VButton color="danger" elevated>
                        Button
                    </VButton>
                </VButtons>
            </div> -->
            <!-- <div class="form-body p-2">
                <div class="business-dashboard hr-dashboard">
                    <div class="columns is-multiline">
                        <div class="column is-12" v-if="isLoadingPasien">
                            <PlaceloadHeader class="m-3" />
                        </div>
                        <div class="column is-12" v-if="!isLoadingPasien">
                            <HeadPasien :pasien="pasien" class="m-3" /> 
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

    <Dialog v-model:visible="modalInput" modal header="Odontogram" :style="{ width: '80vw' }" :draggable="false">
        <div class="column">
            <canvas id="myCanvas" width="1100" height="450">
                Your browser does not support the HTML5 canvas tag.</canvas>
        </div>
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-2">
                    <VButton color="primary" class="is-fullwidth" raised @click="BelumErupsi"
                        :outlined="activeStatus == 1 ? false : true">
                        <b class="mr-5">UE</b> Belum Erupsi
                    </VButton>
                </div>
                <div class="column is-2">
                    <VButton color="info" class="is-fullwidth" raised @click="ErupsiSebagian"
                        :outlined="activeStatus == 2 ? false : true">
                        <b class="mr-5">PE</b> Erupsi Sebagian
                    </VButton>
                </div>
                <div class="column is-2">
                    <VButton color="success" class="is-fullwidth" raised @click="AnomaliBentuk"
                        :outlined="activeStatus == 3 ? false : true">
                        <b class="mr-5">A</b> Anomali Bentuk
                    </VButton>
                </div>
                <div class="column is-2">
                    <VButton color="success" class="is-fullwidth" raised @click="Karies"
                        :outlined="activeStatus == 4 ? false : true">
                        <b class="mr-5 bg-button karies"></b> Karies
                    </VButton>
                </div>
                <div class="column is-2">
                    <VButton color="success" class="is-fullwidth" raised @click="NonVital"
                        :outlined="activeStatus == 13 ? false : true">
                        <b class="mr-5" style="color:red">/</b> Non Vital
                    </VButton>
                </div>
                <div class="column is-2">
                    <VButton color="success" class="is-fullwidth" raised @click="TambalanLogam"
                        :outlined="activeStatus == 5 ? false : true">
                        <b class="mr-5 bg-button tmblLogam" style="right: auto;"></b> Tambalan Logam
                    </VButton>
                </div>
            </div>
            <!-- <VButtons>
                <VButton color="primary" raised @click="BelumErupsi" :outlined="activeStatus == 1 ? false : true">
                    <b class="mr-5">UE</b> Belum Erupsi
                </VButton>
                <VButton color="info" raised @click="ErupsiSebagian" :outlined="activeStatus == 2 ? false : true">
                    <b class="mr-5">PE</b> Erupsi Sebagian
                </VButton>
                <VButton color="success" raised @click="AnomaliBentuk" :outlined="activeStatus == 3 ? false : true">
                    <b class="mr-5">A</b> Anomali Bentuk
                </VButton>
                <VButton color="success" raised @click="AnomaliBentuk" :outlined="activeStatus == 3 ? false : true">
                    <b class="mr-5">A</b> Karies
                </VButton>
                <VButton color="success" raised @click="AnomaliBentuk" :outlined="activeStatus == 3 ? false : true">
                           <b class="mr-5">A</b> Non Vital
                        </VButton>
                        <VButton color="success" raised @click="AnomaliBentuk" :outlined="activeStatus == 3 ? false : true">
                           <b class="mr-5">A</b> Tambalan Logam
                        </VButton>
                        <VButton color="warning" raised @click="GigiTiruanLepas" :outlined="activeStatus == 12 ? false : true">
                           <b class="mr-5">UE</b> Gigi Tiruan Lepas
                        </VButton>
                        <VButton color="danger" raised @click="SisaAkar" :outlined="activeStatus == 9 ? false : true">
                            Sisa Akar
                        </VButton>
            </VButtons> -->
        </div>
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-3">
                    <VButton color="primary" class="is-fullwidth" raised @click="TambalanNonLogam"
                        :outlined="activeStatus == 6 ? false : true">
                        <b class="mr-5 bg-button tmblNonLogam"></b> Tambalan Non Logam
                    </VButton>
                </div>
                <div class="column is-3">
                    <VButton color="warning" class="is-fullwidth" raised @click="MahkotaLogam"
                        :outlined="activeStatus == 7 ? false : true">
                        <b class="mr-5  bg-button mktLogam"></b> Mahkota Logam
                    </VButton>
                </div>
                <div class="column is-3">
                    <VButton color="warning" class="is-fullwidth" raised @click="mahkotaNonLogam"
                        :outlined="activeStatus == 8 ? false : true">
                        <b class="mr-5 bg-button mktNonLogam"></b> Mahkota Non Logam
                    </VButton>
                </div>
                <div class="column is-2">
                    <VButton color="danger" class="is-fullwidth" raised @click="SisaAkar"
                        :outlined="activeStatus == 9 ? false : true">
                        <b class="mr-5">√</b> Sisa Akar
                    </VButton>
                </div>
                <div class="column is-1">
                    <VButton color="black" class="is-fullwidth" raised @click="Clear"
                        :outlined="activeStatus == 14 ? false : true">
                        Clear
                    </VButton>
                </div>
            </div>
        </div>
        <template #footer>
            <VButton color="primary" icon="feather:save" raised @click="save" :loading="isLoading">Simpan</VButton>
        </template>
    </Dialog>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import sleep from '/@src/utils/sleep'
import Ordotogram from '../page-emr-plugins/ordotogram.vue'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'

useHead({
    title: 'Odontogram - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
let NOREC_APD = useRoute().query.norec_apd as string

const isLoadingPasien: any = ref(false)
const isTrueUE: any = ref(true)

const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)

const confirm = useConfirm()
const activeValue = ref('icd10')
let data2 = ref([])
const d_JenisDiagnosa: any = ref([])
const d_Diagnosa: any = ref([])
const pasien: any = ref({})
const { y } = useWindowScroll()
const dataSourceX: any = ref([])
const dataSourceIX: any = ref([])
const listColor: any = ref(Object.keys(useThemeColors()))
const isStuck = computed(() => { return y.value > 30 })
const modalInput: any = ref(false)
const colors: reactive = ({
    kdColorKaries: '#B8B7B7',
    kdColorTambalanLogam: '#F544ED',
    kdColorTambalanNonLogam: '#82D9D9',
    kdColorMahkotaLogam: '#014C0A',
    kdColorMahkotaNonLogam: '#40F5F9'
})
const modalInput9: any = ref(false)
let isianColor: any = reactive
let typeIsian: any = reactive
const isLoading: any = ref(false)
const activeStatus: any = ref()
const isDetail: any = ref([false])

// const isikeunColor = async () => {
//     let eel = [];
//     useApi().get('emr/get-data-odontogram?nocm=' + props.pasien.nocm).then(function (e) {
//         // let dataTransaksi = e.data.data
//         e.data.forEach((element: any) => {
//             typeIsian = element.type
//             isianColor = element.color
//             eel = element
//             isiColor(eel)
//         })
//     });
// }

const save = async () => {

    let objHead = {
        'norec_pd': NOREC_PD,
        'nocm': props.pasien.nocm,
        'namapasien': props.pasien.namapasien,
        'jeniskelamin': props.pasien.jeniskelamin,
        'noregistrasi': props.registrasi.noregistrasi,
        'umur': props.pasien.umur,
        'kelompokpasien': props.registrasi.kelompokpasien,
        'tglregistrasi': props.registrasi.tglregistrasi,
        'norec': NOREC_APD,//norec_apd
        'namakelas': props.registrasi.namakelas,
        'namaruangan': props.registrasi.namaruangan
    }

    if (data2.value.length == 0) {
        H.alert('error', 'Belum ada yang dipilih')
        return
    }
    isLoading.value = true
    // for (let i = data2.value.length - 1; i >= 0; i--) {
    //     if (data2[i].type == undefined) {
    //         data2[i].type = ''
    //         data2[i].color = "black"
    //     }
    // }
    let objSave = {
        'head': objHead,
        'data': data2.value
    }

    await useApi().post('emr/simpan-transaksi-odontogram', objSave).then((response) => {
        isLoading.value = false
    })
}

const BelumErupsi = () => {
    typeIsian = "UE"
    activeStatus.value = 1
}

const ErupsiSebagian = () => {
    typeIsian = "PE"
    activeStatus.value = 2
}

const AnomaliBentuk = () => {
    typeIsian = "A"
    activeStatus.value = 3
}

const NonVital = () => {
    typeIsian = "/"
    activeStatus.value = 13

}

const Karies = () => {
    isianColor = colors.kdColorKaries
    typeIsian = "selai"
    activeStatus.value = 4
}

const TambalanLogam = () => {
    isianColor = colors.kdColorTambalanLogam
    typeIsian = "selai"
    activeStatus.value = 5
}

const TambalanNonLogam = () => {
    isianColor = colors.kdColorTambalanNonLogam
    typeIsian = "selai"
    activeStatus.value = 6
}

const MahkotaLogam = () => {
    isianColor = colors.kdColorMahkotaLogam
    typeIsian = "selai"
    activeStatus.value = 7
}

const mahkotaNonLogam = () => {
    isianColor = colors.kdColorMahkotaNonLogam
    typeIsian = "selai"
    activeStatus.value = 8
}

const SisaAkar = () => {
    typeIsian = "SisaAkar"
    activeStatus.value = 9
}

const gigiHilang = () => {
    typeIsian = "gigiHilang"
    activeStatus.value = 10
}

const Jembatan = () => {
    typeIsian = "Jembatan"
    activeStatus.value = 11
}

const GigiTiruanLepas = () => {
    typeIsian = "GigiTiruanLepas"
    activeStatus.value = 12
}

const Clear = () => {
    isianColor = "white"
    typeIsian = "selai"
    activeStatus.value = 14
}


const showModal = async () => {
    modalInput.value = true
    await sleep(1000)
    let elem: any = document.getElementById('myCanvas');
    let elemLeft = elem.offsetLeft
    let elemTop = elem.offsetTop
    let context = elem.getContext('2d')

    let elements: any = [];

    const getMousePos = (canvas: any, evt: any) => {
        let rect = canvas.getBoundingClientRect();
        return {
            x: evt.clientX - rect.left,
            y: evt.clientY - rect.top
        };
    }

    const isikeunColor = () => {
        let eel = [];
        useApi().get('emr/get-data-odontogram?nocm=' + props.pasien.nocm).then(function (e) {
            if (e.data != null) {
                e.data.forEach((element: any) => {
                    typeIsian = element.type
                    isianColor = element.color
                    eel = element
                    isiColor(eel)
                })
            }
            console.log(eel)
        });
    }

    isikeunColor()

    elem.addEventListener('click', function (event: any) {
        console.log(elemTop)
        var x = event.pageX - (elemLeft + 150),
            y = event.pageY - (elemTop - 80)
        // console.log('nilai x ' + x)
        elements.forEach(function (element: any) {
            // console.log(element.left)
            if (y > element.top + 110 && y < element.top + 110 + element.height && x > element.left && x < element.left + element.width) {
                // console.log('lolos : ' + element.left)
                isiColor(element)
            }
        });

    }, false);

    let listSrc = [
        { id: 50052, no: '18' },
        { id: 50053, no: '17' },
        { id: 50054, no: '16' },
        { id: 50055, no: '15' },
        { id: 50056, no: '14' },
        { id: 50057, no: '13' },
        { id: 50058, no: '12' },
        { id: 50059, no: '11' },

        { id: 50060, no: '21' },
        { id: 50061, no: '22' },
        { id: 50062, no: '23' },
        { id: 50063, no: '24' },
        { id: 50064, no: '25' },
        { id: 50065, no: '26' },
        { id: 50066, no: '27' },
        { id: 50067, no: '28' },

        { id: 50068, no: '55' },
        { id: 50069, no: '54' },
        { id: 50070, no: '53' },
        { id: 50071, no: '52' },
        { id: 50072, no: '51' },

        { id: 50073, no: '61' },
        { id: 50074, no: '62' },
        { id: 50075, no: '63' },
        { id: 50076, no: '64' },
        { id: 50077, no: '65' },

        { id: 50078, no: '85' },
        { id: 50079, no: '84' },
        { id: 50080, no: '83' },
        { id: 50081, no: '82' },
        { id: 50082, no: '81' },

        { id: 50083, no: '71' },
        { id: 50084, no: '72' },
        { id: 50085, no: '73' },
        { id: 50086, no: '74' },
        { id: 50087, no: '75' },

        { id: 50088, no: '48' },
        { id: 50089, no: '47' },
        { id: 50090, no: '46' },
        { id: 50091, no: '45' },
        { id: 50092, no: '44' },
        { id: 50093, no: '43' },
        { id: 50094, no: '42' },
        { id: 50095, no: '41' },

        { id: 50096, no: '31' },
        { id: 50097, no: '32' },
        { id: 50098, no: '33' },
        { id: 50099, no: '34' },
        { id: 50100, no: '35' },
        { id: 50101, no: '36' },
        { id: 50102, no: '37' },
        { id: 50103, no: '38' }
    ]


    let nextKotak = 0
    let besarKotak = 65
    let spc = 0
    let ididKlik = 50051
    let idSrc = 0

    //sayur kol 1
    for (let i = 0; i < 5; i++) {

        context.moveTo(40 + (i * besarKotak) + spc, 40);
        context.lineTo(100 + (i * besarKotak) + spc, 40);
        context.lineTo(100 + (i * besarKotak) + spc, 100);
        context.lineTo(40 + (i * besarKotak) + spc, 100);
        context.lineTo(40 + (i * besarKotak) + spc, 40);

        context.lineTo(55 + (i * besarKotak) + spc, 55);
        context.lineTo(55 + (i * besarKotak) + spc, 85);
        context.lineTo(40 + (i * besarKotak) + spc, 100);

        context.moveTo(55 + (i * besarKotak) + spc, 85);
        context.lineTo(85 + (i * besarKotak) + spc, 85);
        context.lineTo(100 + (i * besarKotak) + spc, 100);

        context.moveTo(85 + (i * besarKotak) + spc, 85);
        context.lineTo(85 + (i * besarKotak) + spc, 55);
        context.lineTo(100 + (i * besarKotak) + spc, 40);

        context.moveTo(85 + (i * besarKotak) + spc, 55);
        context.lineTo(55 + (i * besarKotak) + spc, 55);



        context.stroke();


        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 57 + (i * besarKotak) + spc, 120);

        idSrc = idSrc + 1
        elements.push(
            //A1
            {
                colour: 'red',
                width: 30, height: 15,
                top: 40, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 1, kol: i + 1, seg: 1, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 55, left: 85 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 1, kol: i + 1, seg: 2, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 30, height: 15,
                top: 85, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 1, kol: i + 1, seg: 3, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 55, left: 40 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 1, kol: i + 1, seg: 4, posisina: 'pinggir'
            },
            {
                colour: 'yellow',
                width: 30, height: 30,
                top: 55, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 5,
                brs: 1, kol: i + 1, seg: 5, posisina: 'pinggir'
            }
        );
        ididKlik = ididKlik + 5
    }


    /// SAYUR 1 nukadua
    spc = 325
    for (let i = 0; i < 6; i++) {
        // if (i == 8) {
        //    spc = 20
        //}


        context.moveTo(40 + (i * besarKotak) + spc, 40);
        context.lineTo(100 + (i * besarKotak) + spc, 40);
        context.lineTo(100 + (i * besarKotak) + spc, 100);
        context.lineTo(40 + (i * besarKotak) + spc, 100);
        context.lineTo(40 + (i * besarKotak) + spc, 40);

        context.lineTo(55 + (i * besarKotak) + spc, 70);
        context.lineTo(40 + (i * besarKotak) + spc, 100);

        context.moveTo(85 + (i * besarKotak) + spc, 70);
        context.lineTo(100 + (i * besarKotak) + spc, 100);

        context.moveTo(85 + (i * besarKotak) + spc, 70);
        context.lineTo(100 + (i * besarKotak) + spc, 40);

        context.moveTo(85 + (i * besarKotak) + spc, 70);
        context.lineTo(55 + (i * besarKotak) + spc, 70);

        context.stroke();


        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 57 + (i * besarKotak) + spc, 120);

        idSrc = idSrc + 1

        elements.push(
            //A1
            {
                colour: 'red',
                width: 30, height: 30,
                top: 40, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 1, kol: i + 1, seg: 1, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 55, left: 85 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 1, kol: i + 1, seg: 2, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 30, height: 30,
                top: 85, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 1, kol: i + 1, seg: 3, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 55, left: 40 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 1, kol: i + 1, seg: 4, posisina: 'tengah'
            }
        );
        ididKlik = ididKlik + 4
    }

    ///SAYUR 1 nu kadua


    ///sayur 1 nu katilu

    spc = 715
    for (let i = 0; i < 5; i++) {
        // if (i == 8) {
        //    spc = 20
        //}


        context.moveTo(40 + (i * besarKotak) + spc, 40);
        context.lineTo(100 + (i * besarKotak) + spc, 40);
        context.lineTo(100 + (i * besarKotak) + spc, 100);
        context.lineTo(40 + (i * besarKotak) + spc, 100);
        context.lineTo(40 + (i * besarKotak) + spc, 40);

        context.lineTo(55 + (i * besarKotak) + spc, 55);
        context.lineTo(55 + (i * besarKotak) + spc, 85);
        context.lineTo(40 + (i * besarKotak) + spc, 100);

        context.moveTo(55 + (i * besarKotak) + spc, 85);
        context.lineTo(85 + (i * besarKotak) + spc, 85);
        context.lineTo(100 + (i * besarKotak) + spc, 100);

        context.moveTo(85 + (i * besarKotak) + spc, 85);
        context.lineTo(85 + (i * besarKotak) + spc, 55);
        context.lineTo(100 + (i * besarKotak) + spc, 40);

        context.moveTo(85 + (i * besarKotak) + spc, 55);
        context.lineTo(55 + (i * besarKotak) + spc, 55);



        context.stroke();


        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 57 + (i * besarKotak) + spc, 120);

        idSrc = idSrc + 1

        elements.push(
            //A1
            {
                colour: 'red',
                width: 30, height: 15,
                top: 40, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 1, kol: i + 1, seg: 1, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 55, left: 85 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 1, kol: i + 1, seg: 2, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 30, height: 15,
                top: 85, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 1, kol: i + 1, seg: 3, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 55, left: 40 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 1, kol: i + 1, seg: 4, posisina: 'pinggir'
            },
            {
                colour: 'yellow',
                width: 30, height: 30,
                top: 55, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 5,
                brs: 1, kol: i + 1, seg: 5, posisina: 'pinggir'
            }
        );
        ididKlik = ididKlik + 5
    }


    ///sayur 1 nu katilu

    spc = 0
    //sayur kol 2
    for (let i = 0; i < 2; i++) {

        context.moveTo(235 + (i * besarKotak) + spc, 150);
        context.lineTo(235 + (i * besarKotak) + spc, 210);
        context.lineTo(295 + (i * besarKotak) + spc, 210);
        context.lineTo(295 + (i * besarKotak) + spc, 150);
        context.lineTo(235 + (i * besarKotak) + spc, 150);

        context.moveTo(235 + (i * besarKotak) + spc, 150);
        context.lineTo(250 + (i * besarKotak) + spc, 165);
        context.lineTo(250 + (i * besarKotak) + spc, 195);
        context.lineTo(235 + (i * besarKotak) + spc, 210);

        context.moveTo(250 + (i * besarKotak) + spc, 195);
        context.lineTo(280 + (i * besarKotak) + spc, 195);
        context.lineTo(295 + (i * besarKotak) + spc, 210);

        context.moveTo(280 + (i * besarKotak) + spc, 195);
        context.lineTo(280 + (i * besarKotak) + spc, 165);
        context.lineTo(295 + (i * besarKotak) + spc, 150);

        context.moveTo(280 + (i * besarKotak) + spc, 165);
        context.lineTo(250 + (i * besarKotak) + spc, 165);

        context.stroke();

        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 252 + (i * besarKotak) + spc, 230);

        idSrc = idSrc + 1

        elements.push(
            {
                colour: 'red',
                width: 30, height: 15,
                top: 150, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 2, kol: i + 1, seg: 1, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 165, left: 280 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 2, kol: i + 1, seg: 2, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 30, height: 15,
                top: 195, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 2, kol: i + 1, seg: 3, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 165, left: 235 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 2, kol: i + 1, seg: 4, posisina: 'pinggir'
            },
            {
                colour: 'yellow',
                width: 30, height: 30,
                top: 165, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 5,
                brs: 2, kol: i + 1, seg: 5, posisina: 'pinggir'
            }
        );
        ididKlik = ididKlik + 5
    }



    //nu beda 2		
    spc = 130
    //sayur kol 2
    for (let i = 0; i < 6; i++) {

        context.moveTo(235 + (i * besarKotak) + spc, 150);
        context.lineTo(235 + (i * besarKotak) + spc, 210);
        context.lineTo(295 + (i * besarKotak) + spc, 210);
        context.lineTo(295 + (i * besarKotak) + spc, 150);
        context.lineTo(235 + (i * besarKotak) + spc, 150);

        context.lineTo(250 + (i * besarKotak) + spc, 180);
        context.lineTo(235 + (i * besarKotak) + spc, 210);

        context.moveTo(280 + (i * besarKotak) + spc, 180);
        context.lineTo(295 + (i * besarKotak) + spc, 210);

        context.moveTo(280 + (i * besarKotak) + spc, 180);
        context.lineTo(295 + (i * besarKotak) + spc, 150);

        context.moveTo(280 + (i * besarKotak) + spc, 180);
        context.lineTo(250 + (i * besarKotak) + spc, 180);


        //context.lineTo(55 + (i * besarKotak) + spc, 55);
        //context.lineTo(55 + (i * besarKotak) + spc, 85);
        //context.lineTo(40 + (i * besarKotak) + spc, 100);

        // context.moveTo(55 + (i * besarKotak) + spc, 85);
        // context.lineTo(85 + (i * besarKotak) + spc, 85);
        //context.lineTo(100 + (i * besarKotak) + spc, 100);

        // context.moveTo(85 + (i * besarKotak) + spc, 85);
        // context.lineTo(85 + (i * besarKotak) + spc, 55);
        // context.lineTo(100 + (i * besarKotak) + spc, 40);

        // context.moveTo(85 + (i * besarKotak) + spc, 55);
        // context.lineTo(55 + (i * besarKotak) + spc, 55);


        context.stroke();

        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 252 + (i * besarKotak) + spc, 230);

        idSrc = idSrc + 1

        elements.push(
            {
                colour: 'red',
                width: 30, height: 30,
                top: 150, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 2, kol: i + 1, seg: 1, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 165, left: 280 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 2, kol: i + 1, seg: 2, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 30, height: 30,
                top: 195, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 2, kol: i + 1, seg: 3, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 165, left: 235 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 2, kol: i + 1, seg: 4, posisina: 'tengah'
            }
        );
        ididKlik = ididKlik + 4
    }


    spc = 520
    //sayur kol 2
    for (let i = 0; i < 2; i++) {

        context.moveTo(235 + (i * besarKotak) + spc, 150);
        context.lineTo(235 + (i * besarKotak) + spc, 210);
        context.lineTo(295 + (i * besarKotak) + spc, 210);
        context.lineTo(295 + (i * besarKotak) + spc, 150);
        context.lineTo(235 + (i * besarKotak) + spc, 150);

        context.moveTo(235 + (i * besarKotak) + spc, 150);
        context.lineTo(250 + (i * besarKotak) + spc, 165);
        context.lineTo(250 + (i * besarKotak) + spc, 195);
        context.lineTo(235 + (i * besarKotak) + spc, 210);

        context.moveTo(250 + (i * besarKotak) + spc, 195);
        context.lineTo(280 + (i * besarKotak) + spc, 195);
        context.lineTo(295 + (i * besarKotak) + spc, 210);

        context.moveTo(280 + (i * besarKotak) + spc, 195);
        context.lineTo(280 + (i * besarKotak) + spc, 165);
        context.lineTo(295 + (i * besarKotak) + spc, 150);

        context.moveTo(280 + (i * besarKotak) + spc, 165);
        context.lineTo(250 + (i * besarKotak) + spc, 165);

        context.stroke();

        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 252 + (i * besarKotak) + spc, 230);

        idSrc = idSrc + 1

        elements.push(
            {
                colour: 'red',
                width: 30, height: 15,
                top: 150, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 2, kol: i + 1, seg: 1, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 165, left: 280 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 2, kol: i + 1, seg: 2, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 30, height: 15,
                top: 195, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 2, kol: i + 1, seg: 3, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 165, left: 235 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 2, kol: i + 1, seg: 4, posisina: 'pinggir'
            },
            {
                colour: 'yellow',
                width: 30, height: 30,
                top: 165, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 5,
                brs: 2, kol: i + 1, seg: 5, posisina: 'pinggir'
            }
        );
        ididKlik = ididKlik + 5
    }

    spc = 0
    //sayur kol 3
    for (let i = 0; i < 2; i++) {

        context.moveTo(235 + (i * besarKotak) + spc, 260);
        context.lineTo(235 + (i * besarKotak) + spc, 320);
        context.lineTo(295 + (i * besarKotak) + spc, 320);
        context.lineTo(295 + (i * besarKotak) + spc, 260);
        context.lineTo(235 + (i * besarKotak) + spc, 260);

        context.moveTo(235 + (i * besarKotak) + spc, 260);
        context.lineTo(250 + (i * besarKotak) + spc, 275);
        context.lineTo(250 + (i * besarKotak) + spc, 305);
        context.lineTo(235 + (i * besarKotak) + spc, 320);

        context.moveTo(250 + (i * besarKotak) + spc, 305);
        context.lineTo(280 + (i * besarKotak) + spc, 305);
        context.lineTo(295 + (i * besarKotak) + spc, 320);

        context.moveTo(280 + (i * besarKotak) + spc, 305);
        context.lineTo(280 + (i * besarKotak) + spc, 275);
        context.lineTo(295 + (i * besarKotak) + spc, 260);

        context.moveTo(280 + (i * besarKotak) + spc, 275);
        context.lineTo(250 + (i * besarKotak) + spc, 275);

        context.stroke();

        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 252 + (i * besarKotak) + spc, 340);

        idSrc = idSrc + 1


        elements.push(
            {
                colour: 'red',
                width: 30, height: 15,
                top: 260, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 3, kol: i + 1, seg: 1, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 275, left: 280 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 3, kol: i + 1, seg: 2, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 30, height: 15,
                top: 305, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 3, kol: i + 1, seg: 3, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 275, left: 235 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 3, kol: i + 1, seg: 4, posisina: 'pinggir'
            },
            {
                colour: 'yellow',
                width: 30, height: 30,
                top: 275, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 5,
                brs: 3, kol: i + 1, seg: 5, posisina: 'pinggir'
            }
        );
        ididKlik = ididKlik + 5
    }
    spc = 130
    //sayur kol 3
    for (let i = 0; i < 6; i++) {

        context.moveTo(235 + (i * besarKotak) + spc, 260);
        context.lineTo(235 + (i * besarKotak) + spc, 320);
        context.lineTo(295 + (i * besarKotak) + spc, 320);
        context.lineTo(295 + (i * besarKotak) + spc, 260);
        context.lineTo(235 + (i * besarKotak) + spc, 260);

        context.lineTo(250 + (i * besarKotak) + spc, 290);
        context.lineTo(235 + (i * besarKotak) + spc, 320);

        context.moveTo(280 + (i * besarKotak) + spc, 290);
        context.lineTo(295 + (i * besarKotak) + spc, 320);


        context.moveTo(280 + (i * besarKotak) + spc, 290);
        context.lineTo(295 + (i * besarKotak) + spc, 260);

        context.moveTo(280 + (i * besarKotak) + spc, 290);
        context.lineTo(250 + (i * besarKotak) + spc, 290);


        context.stroke();

        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 252 + (i * besarKotak) + spc, 340);

        idSrc = idSrc + 1


        elements.push(
            {
                colour: 'red',
                width: 30, height: 30,
                top: 260, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 3, kol: i + 1, seg: 1, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 275, left: 280 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 3, kol: i + 1, seg: 2, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 30, height: 30,
                top: 305, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 3, kol: i + 1, seg: 3, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 275, left: 235 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 3, kol: i + 1, seg: 4, posisina: 'tengah'
            }
        );
        ididKlik = ididKlik + 4
    }
    spc = 520
    //sayur kol 3
    for (let i = 0; i < 2; i++) {

        context.moveTo(235 + (i * besarKotak) + spc, 260);
        context.lineTo(235 + (i * besarKotak) + spc, 320);
        context.lineTo(295 + (i * besarKotak) + spc, 320);
        context.lineTo(295 + (i * besarKotak) + spc, 260);
        context.lineTo(235 + (i * besarKotak) + spc, 260);

        context.moveTo(235 + (i * besarKotak) + spc, 260);
        context.lineTo(250 + (i * besarKotak) + spc, 275);
        context.lineTo(250 + (i * besarKotak) + spc, 305);
        context.lineTo(235 + (i * besarKotak) + spc, 320);

        context.moveTo(250 + (i * besarKotak) + spc, 305);
        context.lineTo(280 + (i * besarKotak) + spc, 305);
        context.lineTo(295 + (i * besarKotak) + spc, 320);

        context.moveTo(280 + (i * besarKotak) + spc, 305);
        context.lineTo(280 + (i * besarKotak) + spc, 275);
        context.lineTo(295 + (i * besarKotak) + spc, 260);

        context.moveTo(280 + (i * besarKotak) + spc, 275);
        context.lineTo(250 + (i * besarKotak) + spc, 275);

        context.stroke();

        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 252 + (i * besarKotak) + spc, 340);

        idSrc = idSrc + 1


        elements.push(
            {
                colour: 'red',
                width: 30, height: 15,
                top: 260, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 3, kol: i + 1, seg: 1, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 275, left: 280 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 3, kol: i + 1, seg: 2, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 30, height: 15,
                top: 305, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 3, kol: i + 1, seg: 3, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 275, left: 235 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 3, kol: i + 1, seg: 4, posisina: 'pinggir'
            },
            {
                colour: 'yellow',
                width: 30, height: 30,
                top: 275, left: 250 + (i * besarKotak) + spc,
                id: ididKlik + 5,
                brs: 3, kol: i + 1, seg: 5, posisina: 'pinggir'
            }
        );
        ididKlik = ididKlik + 5
    }
    spc = 0
    //sayur kol 4
    for (let i = 0; i < 5; i++) {

        context.moveTo(40 + (i * besarKotak) + spc, 430);
        context.lineTo(100 + (i * besarKotak) + spc, 430);
        context.lineTo(100 + (i * besarKotak) + spc, 370);
        context.lineTo(40 + (i * besarKotak) + spc, 370);
        context.lineTo(40 + (i * besarKotak) + spc, 430);

        context.moveTo(40 + (i * besarKotak) + spc, 370);
        context.lineTo(55 + (i * besarKotak) + spc, 385);
        context.lineTo(55 + (i * besarKotak) + spc, 415);
        context.lineTo(40 + (i * besarKotak) + spc, 430);

        context.moveTo(55 + (i * besarKotak) + spc, 415);
        context.lineTo(85 + (i * besarKotak) + spc, 415);
        context.lineTo(100 + (i * besarKotak) + spc, 430);

        context.moveTo(85 + (i * besarKotak) + spc, 415);
        context.lineTo(85 + (i * besarKotak) + spc, 385);
        context.lineTo(100 + (i * besarKotak) + spc, 370);

        context.moveTo(85 + (i * besarKotak) + spc, 385);
        context.lineTo(55 + (i * besarKotak) + spc, 385);

        context.stroke();

        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 57 + (i * besarKotak) + spc, 450);

        idSrc = idSrc + 1

        elements.push(
            {
                colour: 'red',
                width: 30, height: 15,
                top: 370, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 3, kol: i + 1, seg: 1, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 385, left: 85 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 3, kol: i + 1, seg: 2, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 30, height: 15,
                top: 415, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 3, kol: i + 1, seg: 3, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 385, left: 40 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 3, kol: i + 1, seg: 4, posisina: 'pinggir'
            },
            {
                colour: 'yellow',
                width: 30, height: 30,
                top: 385, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 5,
                brs: 3, kol: i + 1, seg: 5, posisina: 'pinggir'
            }
        );
        ididKlik = ididKlik + 5

    }
    spc = 325
    //sayur kol TENGAH KA 4
    for (let i = 0; i < 6; i++) {

        context.moveTo(40 + (i * besarKotak) + spc, 430);
        context.lineTo(100 + (i * besarKotak) + spc, 430);
        context.lineTo(100 + (i * besarKotak) + spc, 370);
        context.lineTo(40 + (i * besarKotak) + spc, 370);
        context.lineTo(40 + (i * besarKotak) + spc, 430);


        context.lineTo(55 + (i * besarKotak) + spc, 400);
        context.lineTo(40 + (i * besarKotak) + spc, 370);

        context.moveTo(85 + (i * besarKotak) + spc, 400);
        context.lineTo(100 + (i * besarKotak) + spc, 430);

        context.moveTo(85 + (i * besarKotak) + spc, 400);
        context.lineTo(100 + (i * besarKotak) + spc, 370);


        context.moveTo(85 + (i * besarKotak) + spc, 400);
        context.lineTo(55 + (i * besarKotak) + spc, 400);

        context.stroke();

        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 57 + (i * besarKotak) + spc, 450);

        idSrc = idSrc + 1

        elements.push(
            {
                colour: 'red',
                width: 30, height: 30,
                top: 370, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 3, kol: i + 1, seg: 1, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 385, left: 85 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 3, kol: i + 1, seg: 2, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 30, height: 30,
                top: 415, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 3, kol: i + 1, seg: 3, posisina: 'tengah'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 385, left: 40 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 3, kol: i + 1, seg: 4, posisina: 'tengah'
            }
        );
        ididKlik = ididKlik + 4

    }
    spc = 715
    //sayur kol 4
    for (let i = 0; i < 5; i++) {

        context.moveTo(40 + (i * besarKotak) + spc, 430);
        context.lineTo(100 + (i * besarKotak) + spc, 430);
        context.lineTo(100 + (i * besarKotak) + spc, 370);
        context.lineTo(40 + (i * besarKotak) + spc, 370);
        context.lineTo(40 + (i * besarKotak) + spc, 430);

        context.moveTo(40 + (i * besarKotak) + spc, 370);
        context.lineTo(55 + (i * besarKotak) + spc, 385);
        context.lineTo(55 + (i * besarKotak) + spc, 415);
        context.lineTo(40 + (i * besarKotak) + spc, 430);

        context.moveTo(55 + (i * besarKotak) + spc, 415);
        context.lineTo(85 + (i * besarKotak) + spc, 415);
        context.lineTo(100 + (i * besarKotak) + spc, 430);

        context.moveTo(85 + (i * besarKotak) + spc, 415);
        context.lineTo(85 + (i * besarKotak) + spc, 385);
        context.lineTo(100 + (i * besarKotak) + spc, 370);

        context.moveTo(85 + (i * besarKotak) + spc, 385);
        context.lineTo(55 + (i * besarKotak) + spc, 385);

        context.stroke();

        context.font = "20px Tahoma";
        context.fillStyle = "black";
        context.fillText(listSrc[idSrc].no, 57 + (i * besarKotak) + spc, 450);

        idSrc = idSrc + 1

        elements.push(
            {
                colour: 'red',
                width: 30, height: 15,
                top: 370, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 1,
                brs: 3, kol: i + 1, seg: 1, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 385, left: 85 + (i * besarKotak) + spc,
                id: ididKlik + 2,
                brs: 3, kol: i + 1, seg: 2, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 30, height: 15,
                top: 415, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 3,
                brs: 3, kol: i + 1, seg: 3, posisina: 'pinggir'
            },
            {
                colour: 'red',
                width: 15, height: 30,
                top: 385, left: 40 + (i * besarKotak) + spc,
                id: ididKlik + 4,
                brs: 3, kol: i + 1, seg: 4, posisina: 'pinggir'
            },
            {
                colour: 'yellow',
                width: 30, height: 30,
                top: 385, left: 55 + (i * besarKotak) + spc,
                id: ididKlik + 5,
                brs: 3, kol: i + 1, seg: 5, posisina: 'pinggir'
            }
        );
        ididKlik = ididKlik + 5

    }

    function isiColor(element: any) {

        if (typeIsian == "selai") {
            if (element.posisina == 'pinggir') {
                if (element.seg == 1) {
                    context.beginPath();
                    context.moveTo(element.left - 15, element.top);
                    context.lineTo(element.left - 15 + 60, element.top);
                    context.lineTo(element.left - 15 + 60 - 15, element.top + 15);
                    context.lineTo(element.left - 15 + 60 - 15 - 30, element.top + 15);
                    context.lineTo(element.left - 15, element.top);
                    context.closePath();
                    context.lineWidth = "1";
                    context.strokeStyle = "black";
                    context.stroke();
                    context.fillStyle = isianColor;
                    context.fill();
                }
                if (element.seg == 2) {
                    context.beginPath();
                    context.moveTo(element.left + 15, element.top - 15);
                    context.lineTo(element.left + 15, element.top - 15 + 60);
                    context.lineTo(element.left + 15 - 15, element.top - 15 + 60 - 15);
                    context.lineTo(element.left + 15 - 15, element.top - 15 + 60 - 15 - 30);
                    context.lineTo(element.left + 15, element.top - 15);
                    context.closePath();
                    context.lineWidth = "1";
                    context.strokeStyle = "black";
                    context.stroke();
                    context.fillStyle = isianColor;
                    context.fill();
                }
                if (element.seg == 3) {
                    context.beginPath();
                    context.moveTo(element.left - 15, element.top + 15);
                    context.lineTo(element.left, element.top);
                    context.lineTo(element.left + 30, element.top);
                    context.lineTo(element.left + 30 + 15, element.top + 15);
                    context.lineTo(element.left - 15, element.top + 15);
                    context.closePath();
                    context.lineWidth = "1";
                    context.strokeStyle = "black";
                    context.stroke();
                    context.fillStyle = isianColor;
                    context.fill();
                }
                if (element.seg == 4) {
                    context.beginPath();
                    context.moveTo(element.left, element.top - 15);
                    context.lineTo(element.left + 15, element.top - 15 + 15);
                    context.lineTo(element.left + 15, element.top - 15 + 15 + 30);
                    context.lineTo(element.left + 15 - 15, element.top - 15 + 15 + 30 + 15);
                    context.lineTo(element.left, element.top - 15);
                    context.closePath();
                    context.lineWidth = "1";
                    context.strokeStyle = "black";
                    context.stroke();
                    context.fillStyle = isianColor;
                    context.fill();
                }
                if (element.seg == 5) {
                    context.beginPath();
                    context.moveTo(element.left, element.top);
                    context.lineTo(element.left + 30, element.top);
                    context.lineTo(element.left + 30, element.top + 30);
                    context.lineTo(element.left, element.top + 30);
                    context.lineTo(element.left, element.top);
                    context.closePath();
                    context.lineWidth = "1";
                    context.strokeStyle = "black";
                    context.stroke();
                    context.fillStyle = isianColor;
                    context.fill();
                }
            }
            if (element.posisina == 'tengah') {
                if (element.seg == 1) {
                    context.beginPath();
                    context.moveTo(element.left - 15, element.top);
                    context.lineTo(element.left - 15 + 60, element.top);
                    context.lineTo(element.left - 15 + 60 - 15, element.top + 30);
                    context.lineTo(element.left - 15 + 60 - 15 - 30, element.top + 30);
                    context.lineTo(element.left - 15, element.top);
                    context.closePath();
                    context.lineWidth = "1";
                    context.strokeStyle = "black";
                    context.stroke();
                    context.fillStyle = isianColor;
                    context.fill();
                }
                if (element.seg == 2) {
                    context.beginPath();
                    context.moveTo(element.left + 15, element.top - 15);
                    context.lineTo(element.left + 15, element.top - 15 + 60);
                    context.lineTo(element.left + 15 - 15, element.top - 15 + 60 - 30);
                    context.lineTo(element.left + 15, element.top - 15);
                    context.closePath();
                    context.lineWidth = "1";
                    context.strokeStyle = "black";
                    context.stroke();
                    context.fillStyle = isianColor;
                    context.fill();
                }
                if (element.seg == 3) {
                    context.beginPath();
                    context.moveTo(element.left - 15, element.top + 15);
                    context.lineTo(element.left, element.top - 15);
                    context.lineTo(element.left + 30, element.top - 15);
                    context.lineTo(element.left + 30 + 15, element.top + 15);
                    context.lineTo(element.left - 15, element.top + 15);
                    context.closePath();
                    context.lineWidth = "1";
                    context.strokeStyle = "black";
                    context.stroke();
                    context.fillStyle = isianColor;
                    context.fill();
                }
                if (element.seg == 4) {
                    context.beginPath();
                    context.moveTo(element.left, element.top - 15);
                    context.lineTo(element.left + 15, element.top + 15);

                    context.lineTo(element.left + 15 - 15, element.top - 15 + 15 + 30 + 15);
                    context.lineTo(element.left, element.top - 15);
                    context.closePath();
                    context.lineWidth = "1";
                    context.strokeStyle = "black";
                    context.stroke();
                    context.fillStyle = isianColor;
                    context.fill();
                }

            }
            element.type = typeIsian
            element.color = isianColor
        }
        if (element.seg == 5 && typeIsian != "selai") {
            console.log('test')
            if (typeIsian == "UE") {
                context.font = "20px Tahoma";
                context.fillStyle = "black";
                context.fillText("UE", element.left + 3, element.top + 24);
            }
            if (typeIsian == "PE") {
                context.font = "20px Tahoma";
                context.fillStyle = "black";
                context.fillText("PE", element.left + 3, element.top + 24);
            }
            if (typeIsian == "A") {
                context.font = "20px Tahoma";
                context.fillStyle = "green";
                context.fillText("A", element.left + 8, element.top + 24);
            }
            if (typeIsian == "/") {
                context.font = "25px Tahoma";
                context.fillStyle = "red";
                context.fillText("/", element.left + 10, element.top + 24);
            }
            if (typeIsian == "SisaAkar") {
                context.font = "25px Tahoma";
                context.fillStyle = "blue";
                context.fillText("√", element.left + 4, element.top + 27);
            }
            if (typeIsian == "gigiHilang") {
                context.font = "35px Tahoma";
                context.fillStyle = "Red";
                context.fillText("X", element.left + 3, element.top + 28);
            }
            if (typeIsian == "Jembatan") {
                context.beginPath();
                context.lineWidth = "7";
                context.strokeStyle = "green";
                context.moveTo(element.left + 2, element.top + 20);
                context.lineTo(element.left + 28, element.top + 20);
                context.stroke();
            }
            if (typeIsian == "GigiTiruanLepas") {
                context.beginPath();
                context.lineWidth = "7";
                context.strokeStyle = "#EED63F";
                context.moveTo(element.left + 2, element.top + 20);
                context.lineTo(element.left + 28, element.top + 20);
                context.stroke();
            }
            element.type = typeIsian
            element.color = "black"
        }
        if (element.type != '' || element.type) {
            if (element.type != 'white') {
                data2.value.push(element)
            }
        }
        // if (element.type != '') {
        //     if (element.type != 'white') {

        //         for (let i = data2.value.length - 1; i >= 0; i--) {
        //             if (element.id == data2[i].id) {
        //                 data2.splice(i, 1)
        //             }

        //         }
        //         data2.value.push(element)
        //     }
        // }

    }
    
    getMousePos
}

showModal()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/input-diagnosis';

.karies {
    border: 1px solid rgb(184, 183, 183);
    background: rgb(184, 183, 183);
}

.mktLogam {
    border: 1px solid #014C0A;
    background: #014C0A;
}

.mktNonLogam {
    border: 1px solid #40F5F9;
    background: #40F5F9;
}

.tmblLogam {
    border: 1px solid #F544ED;
    background: #F544ED;
}

.tmblNonLogam {
    border: 1px solid #82D9D9;
    background: #82D9D9;
}

.bg-button {
    right: 17px;
    position: relative;
    margin: padding;
    padding-left: 20px;
}

.p-dialog-title {
    font-weight: 600;
    font-size: 1.25rem;
}
</style>
