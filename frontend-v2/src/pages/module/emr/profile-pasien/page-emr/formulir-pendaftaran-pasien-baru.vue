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
// import Fieldset from 'primevue/fieldset';
// import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import * as EMR from '../page-emr-plugins/formulir-pendaftaran-pasien-baru';

// Loopingan
let DataPenanggungJawab = ref([
    {
        value: [
            { type: "judul", subTitle: "Nama Penanggung Jawab", subTitle2: "Person in Charge" },
            { type: "titik" },
            { type: "textbox", model: "namaPenanggungJawabPasien" },
        ]
    },
    {
        value: [
            { type: "judul", subTitle: "Hubungan/No Telpon", subTitle2: "Relation/Telephone" },
            { type: "titik" },
            { type: "textbox", model: "hubunganNoTelponPasien" },
        ]
    },
    {
        value: [
            { type: "judul", subTitle: "Alamat", subTitle2: "Address" },
            { type: "titik" },
            { type: "textbox", model: "alamatPasien" },
        ]
    },
])

let DataPetugasPendaftaran = ref([
    {
        value: [
            { type: "judul", subTitle: "Unit yang dituju", subTitle2: "to Unit" },
            { type: "titik" },
            { type: "textbox", model: "unitYangDitujuPetugas" },
        ]
    },
])



// Judul
useHead({
    title: 'Formulir Pendaftaran Pasien Baru - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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
const newDate = new Date();
const d_Petugas: any = ref([])
const input: any = ref({
    alamatTempatTinggalPasien: "\nDesa :\n\nKabupaten :",
})
const dataTTD: any = ref([])
const route = useRoute()
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
const COLLECTION: any = ref('FormulirIdentitasPasien') //table mongodb
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
    H.tandaTangan().set("TTDPetugas", dataTTD.value.TTDPetugas)
    H.tandaTangan().set("TTDPasien", dataTTD.value.TTDPasien)
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    object['TTDPetugas'] = H.tandaTangan().get("TTDPetugas");
    object['TTDPasien'] = H.tandaTangan().get("TTDPasien");
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

const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
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

// getDataExist()
fetchPasien()
// setAutoFill()
</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Formulir Pendaftaran Pasien Baru</h3>
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

                <div class="columns is-multiline" style="padding: 10px;">
                    <div class="column is-12">
                        <h1 style="font-weight : bold;">Diisi oleh pasien <i>(Filled by Patient)</i></h1>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">Nama Pasien</h1>
                                </VField>
                                <VField>
                                    <p>(<i>Full Name</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-top: 0rem;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.namaPasien" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">No. KTP/KITAS/PASPORT</h1>
                                </VField>
                                <VField>
                                    <p>(<i>ID Card Number</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-top: 0rem;">
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.noIdentitasPasien" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <VField style="display: flex;">
                                            <h1 style="font-weight : bold;">Kewarganegaraan</h1>
                                            <h1 style="font-weight: bold;">&nbsp;:</h1>
                                            <VControl>
                                                <VInput style="width: 80%;" type="text" class="input ml-4" v-model="input.kewarganegaraanPasien" />
                                            </VControl>
                                        </VField>
                                        <VField style="margin-top: -2rem;">
                                            <p>( <i>Nationality</i> )</p>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField style="display: flex;">
                                            <h1 style="font-weight : bold;">Etnis</h1>
                                            <h1 style="font-weight: bold;">&nbsp;:</h1>
                                            <VControl>
                                                <VInput style="width: 80%;" type="text" class="input ml-4" v-model="input.etnisPasien" />
                                            </VControl>
                                        </VField>
                                        <VField style="margin-top: -2rem;">
                                            <p>(<i>Ethnic</i>)</p>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">Jenis Kelamin</h1>
                                </VField>
                                <VField>
                                    <p>(<i>Sex</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-top: 0rem;">
                                <div class="columns is-mulitline">
                                    <div class="column is-2">
                                        <VControl raw subcontrol>
                                            <VField>
                                                <VCheckbox
                                                class="p-0"
                                                square
                                                    color="primary"
                                                    true-value="lakiLaki"
                                                    label="Laki - laki"
                                                    v-model="input.kelaminLakiPasien"
                                                />
                                            </VField>
                                            <VField style="text-align:center; margin-top: -1rem;">
                                                <p>(<i>Male</i>)</p>
                                            </VField>
                                        </VControl>
                                    </div>
                                    <div class="column is-2">
                                        <VControl raw subcontrol>
                                            <VField>
                                                <VCheckbox
                                                class="p-0"
                                                square
                                                    color="primary"
                                                    true-value="Perempuan"
                                                    label="Perempuan"
                                                    v-model="input.kelaminPerempuanPasien"
                                                />
                                            </VField>
                                            <VField style="text-align:center; margin-top: -1rem;">
                                                <p>(<i>Female</i>)</p>
                                            </VField>
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">Agama</h1>
                                </VField>
                                <VField>
                                    <p>(<i>Religion</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-left: -1rem;">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VField>
                                                    <VCheckbox
                                                    class="p-0"
                                                    square
                                                        color="primary"
                                                        true-value="Islam"
                                                        label="Islam"
                                                        v-model="input.islamPasien"
                                                    />
                                                </VField>
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VField>
                                                    <VCheckbox
                                                    class="p-0"
                                                    square
                                                        color="primary"
                                                        true-value="Khatolik"
                                                        label="Khatolik"
                                                        v-model="input.khatolikPasien"
                                                    />
                                                </VField>
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VField>
                                                    <VCheckbox
                                                    class="p-0"
                                                    square
                                                        color="primary"
                                                        true-value="Kristen"
                                                        label="Kristen"
                                                        v-model="input.kristenPasien"
                                                    />
                                                </VField>
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VField>
                                                    <VCheckbox
                                                    class="p-0"
                                                    square
                                                        color="primary"
                                                        true-value="Hindu"
                                                        label="Hindu"
                                                        v-model="input.hinduPasien"
                                                    />
                                                </VField>
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VField>
                                                    <VCheckbox
                                                    class="p-0"
                                                    square
                                                        color="primary"
                                                        true-value="Budha"
                                                        label="Budha"
                                                        v-model="input.budhaPasien"
                                                    />
                                                </VField>
                                            </VControl>
                                        </div>
                                        <div class="column is-2">
                                            <VControl raw subcontrol>
                                                <VField>
                                                    <VCheckbox
                                                    class="p-0"
                                                    square
                                                        color="primary"
                                                        true-value="Konghucu"
                                                        label="Konghucu"
                                                        v-model="input.konghucuPasien"
                                                    />
                                                </VField>
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <VControl raw subcontrol style="display: flex;">
                                                <VField>
                                                    <VCheckbox
                                                    class="p-0"
                                                    square
                                                        color="primary"
                                                        true-value="Lainnya"
                                                        label="Lainnya"
                                                        v-model="input.LainnyaPasien"
                                                    />
                                                </VField>
                                                <p style="margin-left: -6rem; margin-top: -3px;">(<i>others</i>)</p>
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">Tempat/Tgl Lahir</h1>
                                </VField>
                                <VField>
                                    <p>(<i>Place & Date of Birth</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-left: -1rem;">
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.tempatTglLahirPasien" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">Nama Ibu Kandung</h1>
                                </VField>
                                <VField>
                                    <p>(<i>Mother's Name</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-left: -1rem;">
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.namaIbuKandungPasien" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">Pekerjaan Pasien</h1>
                                </VField>
                                <VField>
                                    <p>(<i>Occupation</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-left: -1rem;">
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.pekerjaanPasien" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">Status Perkawinan</h1>
                                </VField>
                                <VField>
                                    <p>(<i>Marital status</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-left: -1rem;">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-3">
                                            <VControl raw subcontrol>
                                                <VField>
                                                    <VCheckbox
                                                    class="p-0"
                                                    square
                                                        color="primary"
                                                        true-value="BelumMenikah"
                                                        label="Belum Menikah"
                                                        v-model="input.belumMenikahPasien"
                                                    />
                                                </VField>
                                                <VField style="margin-left: 24px; margin-top: -1rem;">
                                                    <p>(<i>Single</i>)</p>
                                                </VField>
                                            </VControl>
                                        </div>
                                        <div class="column is-3">
                                            <VControl raw subcontrol>
                                                <VField>
                                                    <VCheckbox
                                                    class="p-0"
                                                    square
                                                        color="primary"
                                                        true-value="Menikah"
                                                        label="Menikah"
                                                        v-model="input.menikahPasien"
                                                    />
                                                </VField>
                                                <VField style="margin-left: 24px; margin-top: -1rem;">
                                                    <p>(<i>Married</i>)</p>
                                                </VField>
                                            </VControl>
                                        </div>
                                        <div class="column is-3">
                                            <VControl raw subcontrol>
                                                <VField>
                                                    <VCheckbox
                                                    class="p-0"
                                                    square
                                                        color="primary"
                                                        true-value="Janda"
                                                        label="Janda"
                                                        v-model="input.jandaPasien"
                                                    />
                                                </VField>
                                                <VField style="margin-left: 24px; margin-top: -1rem;">
                                                    <p>(<i>Widow</i>)</p>
                                                </VField>
                                            </VControl>
                                        </div>
                                        <div class="column is-3">
                                            <VControl raw subcontrol>
                                                <VField>
                                                    <VCheckbox
                                                    class="p-0"
                                                    square
                                                        color="primary"
                                                        true-value="Duda"
                                                        label="Duda"
                                                        v-model="input.dudaPasien"
                                                    />
                                                </VField>
                                                <VField style="margin-left: 24px; margin-top: -1rem;">
                                                    <p>(<i>Widower</i>)</p>
                                                </VField>
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">Pendidikan</h1>
                                </VField>
                                <VField>
                                    <p>(<i>Last Education</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-left: -1rem;">
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.pendidikanPasien" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">Alamat Tempat Tinggal</h1>
                                </VField>
                                <VField>
                                    <p>(<i>Address</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-left: -1rem;">
                                <div class="column is-12">
                                    <VField>
                                        <VTextarea rows="4" class="textarea" v-model="input.alamatTempatTinggalPasien"></VTextarea>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">Telepon</h1>
                                </VField>
                                <VField>
                                    <p>(<i>Telephone</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-left: -1rem;">
                                <div class="columns is-multiline">
                                    <div class="column is-6">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.noTeleponPasien" />
                                        </VControl>
                                    </div>
                                    <div class="column is-6" style="display: flex;">
                                        <h1 style="font-weight : bold;">Email :</h1>
                                        <VControl>
                                            <VInput type="text" style="width: 300px;" class="input ml-4" v-model="input.emailPasien" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField>
                                    <h1 style="font-weight : bold;">Cara Pembayaran</h1>
                                </VField>
                                <VField>
                                    <p>(<i>Method of Payment</i>)</p>
                                </VField>
                            </div>
                            <div class="column is-1">
                                <h1 style="font-weight : bold;">:</h1>
                            </div>
                            <div class="column is-8" style="margin-left: -1rem;">
                                <div class="columns is-multiline">
                                    <div class="column is-2">
                                        <VControl raw subcontrol>
                                            <VField>
                                                <VCheckbox
                                                class="p-0"
                                                square
                                                    color="primary"
                                                    true-value="Umum"
                                                    label="Umum"
                                                    v-model="input.umumPasien"
                                                />
                                            </VField>
                                            <VField style="text-align:center; margin-top: -1rem;">
                                                <p>(<i>Cash</i>)</p>
                                            </VField>
                                        </VControl>
                                    </div>
                                    <div class="column is-2">
                                        <VControl raw subcontrol>
                                            <VField>
                                                <VCheckbox
                                                class="p-0"
                                                square
                                                    color="primary"
                                                    true-value="BPJS"
                                                    label="BPJS"
                                                    v-model="input.BPJSPasien"
                                                />
                                            </VField>
                                        </VControl>
                                    </div>
                                    <div class="column is-3">
                                        <div class="columns is-multiline">
                                          <div class="column is-12">
                                              <VControl raw subcontrol>
                                                  <VField>
                                                      <VCheckbox
                                                      class="p-0"
                                                      square
                                                          color="primary"
                                                          true-value="AsuransiLainnya"
                                                          label="Asuransi Lainnya"
                                                          v-model="input.AsuransiLainnyaPasien"
                                                      />
                                                  </VField>
                                                  <VField class="ml-5" style="margin-top: -1rem;">
                                                    <p>(<i>Other Insurance</i>)</p>
                                                </VField>
                                              </VControl>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="column is-4">
                                      <VControl>
                                          <VInput style="margin-left: -3rem;" type="text" class="input" v-model="input.asuransiLainnyaPasienText" />
                                      </VControl>
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="columns is-multiline" style="padding: 10px;">
                    <div class="column is-12">
                        <h1 style="font-weight : bold;">Diisi oleh pasien <i>(Filled by Patient)</i></h1>
                    </div>
                    <div class="column is-12" v-for="(datas, index) in DataPenanggungJawab" :key="index">
                        <div class="columns is-multiline">
                            <template v-for="(data, i) in datas.value" :key="i">
                                <!-- Title Section -->
                                <div
                                    class="column is-3"
                                    v-if="data && data.type === 'judul'"
                                >
                                    <VField>
                                        <h1 style="font-weight: bold;">{{ data.subTitle }}</h1>
                                    </VField>
                                    <VField>
                                        <p>(<i>{{ data.subTitle2 }}</i>)</p>
                                    </VField>
                                </div>
                                
                                <!-- Colon Section -->
                                <div
                                    class="column is-1"
                                    v-if="data && data.type === 'titik'"
                                >
                                    <h1 style="font-weight: bold;">:</h1>
                                </div>
                                
                                <!-- Input Section -->
                                <div
                                    class="column is-8"
                                    style="margin-top: 0rem;"
                                    v-if="data && data.type === 'textbox'"
                                >
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input[data.model]" />
                                    </VControl>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                </div>

                <hr>
                <div class="columns is-multiline" style="padding: 10px;">
                    <div class="column is-12">
                        <h1 style="font-weight : bold;">Diisi oleh <i>petugas pendaftaran (Filled by Officer)</i></h1>
                    </div>
                    <div class="column is-12" v-for="(datas, index) in DataPetugasPendaftaran" :key="index">
                        <div class="columns is-multiline">
                            <template v-for="(data, i) in datas.value" :key="i">
                                <!-- Title Section -->
                                <div
                                    class="column is-3"
                                    v-if="data && data.type === 'judul'"
                                >
                                    <VField>
                                        <h1 style="font-weight: bold;">{{ data.subTitle }}</h1>
                                    </VField>
                                    <VField>
                                        <p>(<i>{{ data.subTitle2 }}</i>)</p>
                                    </VField>
                                </div>
                                
                                <!-- Colon Section -->
                                <div
                                    class="column is-1"
                                    v-if="data && data.type === 'titik'"
                                >
                                    <h1 style="font-weight: bold;">:</h1>
                                </div>
                                
                                <!-- Input Section -->
                                <div
                                    class="column is-8"
                                    style="margin-top: 0rem;"
                                    v-if="data && data.type === 'textbox'"
                                >
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input[data.model]" />
                                    </VControl>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                </div>

                <!-- form baru -->
            </div>
        </div>
    </div>
</template>