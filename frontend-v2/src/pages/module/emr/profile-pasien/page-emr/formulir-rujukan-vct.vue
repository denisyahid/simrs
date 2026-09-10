<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

.p-fieldset-legend {
    margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
    background: none;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

table.assesment {
    border-collapse: collapse;
    width: 100%;
}


.assesment th {
    text-align: center !important;
    border-bottom: 1px solid black;
    // border: 1px solid black;
}

.assesment th,
.assesment td {
    padding: 8px;
    vertical-align: middle !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}
</style>
<style lang="scss">
.table-fro {
    width: 100%;
    border: 1px solid black;
}

.th-fro,
.td-fro {
    padding: 7px;
    border: 1px solid black;
    vertical-align: inherit;
}

.setFRO-center {
    text-align: center !important;
}

.p-fieldset-legend {
    margin-left: 15px;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    // border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    // border-color: var(--fade-grey-dark-3);
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
</style>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
// import * as EMR from '../page-emr-plugins/formulir-rujukan-vct'

// Loopingan
// let detailTable = ref(EMR.detailTable())
// let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
// let detailStatusFungsional = ref(EMR.detailStatusFungsional())
// let detailRencanaKebidanan = ref(EMR.detailRencanaKebidanan())

// Judul
useHead({
    title: 'Formulir Rujukan VCT - ' + import.meta.env.VITE_PROJECT,
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
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
const d_Petugas: any = ref([])
// const d_Ruangan: any = ref([])
// const d_DiagnosaICD10: any = ref([])
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
const COLLECTION: any = ref('FormulirRujukanVCT') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
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
    H.tandaTangan().set("TTDPertama", dataTTD.value.TTDPertama)
    H.tandaTangan().set("TTDKedua", dataTTD.value.TTDKedua)
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object['TTDPertama'] = H.tandaTangan().get("TTDPertama");
    object['TTDKEdua'] = H.tandaTangan().get("TTDKEdua");
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
            // NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
        })

    // console.log(resultValue)
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
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
}
const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
        input.value.beratBadan = response.beratBadan ? response.beratBadan : ''
        input.value.tinggiBadan = response.tinggiBadan ? response.tinggiBadan : ''
        input.value.IMT = response.IMT ? response.IMT : response.IMT
        input.value.lingkarPerut = response.lingkarPerut ? response.lingkarPerut : ''
        input.value.tekananDarah = response.tekananDarah ? response.tekananDarah : ''
        input.value.nadi = response.nadi ? response.nadi : ''
        input.value.suhu = response.suhu ? response.suhu : ''
        input.value.pernapasan = response.pernapasan ? response.pernapasan : ''
    })
}
const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}`)
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

fetchPetugas()
getDataExist()
fetchPasien()

</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Formulir Rujukan VCT</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun" :isHideCetak="true">
                            </ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
                <div class="columns is-multiline" style="padding: 10px;">
                    <div class="column is-3">
                        <div class="column"><b>Tanggal Konfirmasi Tes HIV : </b></div>
                        <div class="column" style="margin-top: -10px;">
                            <VDatePicker v-model="input.TanggalKonfirmasiTesHIV" mode="datetime" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                    <div class="column is-3"></div>
                    <div class="column is-6">
                        <div class="column"><b>Keadaan Pada Saat Rujukan : </b></div>
                        <div class="column" style="margin-top: -10px;">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBkpsr" />
                            </VControl>
                        </div>
                    </div>
                    <div class="columns is-multiline column is-12">
                        <div class="column is-12"><b>Stand Klinis WHO : </b></div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="I" label="I"
                                    v-model="input.CBi" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="II" label="II"
                                    v-model="input.CBii" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="III" label="III"
                                    v-model="input.CBiii" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="IV" label="IV"
                                    v-model="input.CBiv" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns is-multiline">
                        <div class="column is-3">
                            <VField addons label="Berat Badan">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBberatbadan" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Kg</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1"></div>
                        <div class="column is-4 columns is-multiline">
                            <div class="column is-12"><b>Status Fungsional : </b></div>
                            <div class="columns column is-12" style="margin-top: -15px;">
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="K" label="K"
                                            v-model="input.CBk" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Amb" label="Amb"
                                            v-model="input.CBamb" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Bar" label="Bar"
                                            v-model="input.CBbar" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <VField label="Regimen">
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Regimen yang diberikan..."
                                        v-model="input.TBRegimenYangDiberikan" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <div class="column"><b>IO yang terjadi/terapi : </b></div>
                            <VField>
                                <VTextarea rows="2" v-model="input.TAIOyangTerjadi"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <div class="column"><b>Profilaksis IO : </b></div>
                            <VField>
                                <VTextarea rows="2" v-model="input.TAProfilaksisIO"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12">
                            Bersama ini kami lampirkan pula salinan ikhtisar perawatan HIV dan ART
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline" style="padding: 10px; padding-top: 0px;">
                    <div class="column is-8"> </div>
                    <div class="column is-4">
                        <VField label="Garut">
                            <VDatePicker v-model="input.Dt1" mode="datetime" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                        <div class="column" style="text-align:center;">
                            <TandaTangan :elemenID="'TTDPertama'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto" style="margin-top: 10px;">
                                <AutoComplete v-model="input.CBPetugas" :suggestions="d_Petugas"
                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <h1 style="padding: 10px;padding-top: 0px;"><b>UNTUK DIISI DAN DIKEMBALIKAN KE UNIT PENGIRIM</b></h1>
                <div class="columns is-multiline" style="padding: 10px;">
                    <div class="column is-4">
                        <VField label="Nama Pasien : ">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Umur : ">
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBumur" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Tahun</VButton>
                                </VControl>
                            </VField>
                        </VField>
                    </div>
                    <VField label="Jenis Kelamin" class="column is-4">
                        <div class="columns" style="margin-top: 7px;">
                            <div class="column is-6">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Laki-laki"
                                        label="Laki-laki" v-model="input.CBjenisKelamin" />
                                </VControl>
                            </div>
                            <div class="column is-6">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Perempuan"
                                        label="Perempuan" v-model="input.CBjenisKelamin" />
                                </VControl>
                            </div>
                        </div>
                    </VField>
                    <div class="column is-6">
                        <div class="column">Tanggal Pasien Melapor : </div>
                        <div class="column" style="margin-top: -10px;">
                            <VDatePicker v-model="input.DTanggalPasienMelapor" mode="date" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="column">Nama Unit Layanan Kesehatan (Tempat Berobat Baru) : </div>
                        <div class="column">
                            <VControl style="margin-top: -10px;">
                                <VInput type="text" class="input" v-model="input.TBnamaUnitPelayanan" />
                            </VControl>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline" style="padding: 10px; padding-top: 0px;">
                    <div class="column is-8"> </div>
                    <div class="column is-4">
                        <VField label="Garut">
                            <VDatePicker v-model="input.Dt2" mode="datetime" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                        <div class="column" style="text-align:center;">
                            <TandaTangan :elemenID="'TTDKedua'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto" style="margin-top: 10px;">
                                <AutoComplete v-model="input.CBPetugas2" :suggestions="d_Petugas"
                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- form baru -->
        </div>
    </div>
</template>