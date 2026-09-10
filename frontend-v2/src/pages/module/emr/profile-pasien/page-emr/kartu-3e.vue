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


.tg2 {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg2 td {
    // border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg2 th {
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
import * as EMR from '../page-emr-plugins/kartu-3e'

// Loopingan
let detailHDD = ref(EMR.detailHDD())
let detailHDD2 = ref(EMR.detailHDD2())
let detailPemantauanBayi = ref(EMR.detailPemantauanBayi())
// let detailRencanaKebidanan = ref(EMR.detailRencanaKebidanan())

// Judul
useHead({
    title: 'Kartu 3E - ' + import.meta.env.VITE_PROJECT,
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
// const fetchDokter = async (filter: any) => {
//     await useApi().get(
//         `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//     ).then((response) => {
//         d_Dokter.value = response
//     })
// }
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
const d_Dokter: any = ref([])
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
const COLLECTION: any = ref('Kartu3E') //table mongodb
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
    // H.tandaTangan().set("TTDBidan", dataTTD.value.TTDBidan)
    // H.tandaTangan().set("TTDDokterPemeriksa", dataTTD.value.TTDDokterPemeriksa)
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    // object['TTDBidan'] = H.tandaTangan().get("TTDBidan");
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

watch(
    () => Object.keys(input.value).filter(key => key.startsWith('checkboxSN_')).map(key => input.value[key]),
    (newValues) => {
        let sum = 0
        newValues.forEach((checkboxValue, index) => {
            const [_, rowIndex, itemIndex] = Object.keys(input.value).filter(key => key.startsWith('checkboxSN_'))[index].split('_')
            const nilaiRow = detailSkriningNutrisi.value[parseInt(rowIndex)]?.child?.[parseInt(itemIndex) + 1]
            if (checkboxValue && nilaiRow && !isNaN(parseInt(nilaiRow.caption))) {
                sum += parseInt(nilaiRow.caption)
            }
        })
        input.value.jumlahNilaiSN = sum
    },
    { deep: true }
)


// fetchDokter()
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
                            <h3>Kartu 3E (TRIPLE ELIMINASI HIV, SIFILIS DAN HEP B)</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun" :isHideCetak="true">
                            </ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <Fieldset :toggleable="true" legend="Status Kehamilan">
                    <div class="columns" style="padding: 10px;">
                        <div class="column is-6">
                            <VField label="Status GPA : ">
                                <div class="columns">
                                    <VField addons class="column is-4">
                                        <VControl class="field-addon-body">
                                            <VButton static>G</VButton>
                                        </VControl>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBstatusG" />
                                        </VControl>
                                    </VField>
                                    <VField addons class="column is-4">
                                        <VControl class="field-addon-body">
                                            <VButton static>P</VButton>
                                        </VControl>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBstatusP" />
                                        </VControl>
                                    </VField>
                                    <VField addons class="column is-4">
                                        <VControl class="field-addon-body">
                                            <VButton static>A</VButton>
                                        </VControl>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBstatusA" />
                                        </VControl>
                                    </VField>
                                </div>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField label="Tgl Taksiran Partus : ">
                                <VField addons>
                                    <VDatePicker v-model="input.DTaksiranPartus" mode="date" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Umur Kehamilan : ">
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBUmurKehamilan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Minggu</VButton>
                                    </VControl>
                                </VField>
                            </VField>
                        </div>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="Hasil Deteksi Dini">
                    <div class="column" style="margin-bottom: -10px;"><b>1. </b></div>
                    <div class="column">
                        <table class="tg2">
                            <thead>
                                <tr>
                                    <th style="text-align: center;vertical-align: middle;">Jenis Screening/Test</th>
                                    <th style="text-align: center;vertical-align: middle;">Tgl Screening/Test</th>
                                    <th style="text-align: center;vertical-align: middle;">Kode Specimen</th>
                                    <th style="text-align: center;vertical-align: middle;">Hasil Screening</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, rowIndex) in detailHDD" :key="rowIndex">
                                    <td width="100px" v-for="(item, itemIndex) in row.child" :key="itemIndex"
                                        :colspan="item.colspan">
                                        <VField style="padding:0px 10px;" v-if="item.type == 'textbox'">
                                            <VControl>
                                                <VInput v-model="input['textboxHDD_' + rowIndex + '_' + itemIndex]"
                                                    class="input">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                        <VField style="padding:0px 10px;" v-if="item.type == 'text'">
                                            <span>{{ item.caption }}</span>
                                        </VField>
                                        <VField style="padding:0px 10px;" v-if="item.type == 'tanggal'">
                                            <VDatePicker v-model="input['tanggalHDD_' + rowIndex + '_' + itemIndex]"
                                                mode="date" trim-weeks :max-date="new Date()">
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                        <div class="columns" v-if="item.type == '2cb'">
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" circle
                                                        true-value="Non Reaktif" label="Non Reaktif"
                                                        v-model="input['CBnonReaktifHDD_' + rowIndex + '_' + itemIndex]" />
                                                </VControl>
                                            </div>
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" circle true-value="Reaktif"
                                                        label="Reaktif"
                                                        v-model="input['CBReaktifHDD_' + rowIndex + '_' + itemIndex]" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="column">
                        <table class="tg">
                            <tbody>
                                <tr v-for="(row, rowIndex) in detailHDD2" :key="rowIndex">
                                    <td width="100px" v-for="(item, itemIndex) in row.child" :key="itemIndex"
                                        :colspan="item.colspan">
                                        <VField style="padding:0px 10px;" v-if="item.type == 'textbox'">
                                            <VControl>
                                                <VInput v-model="input['textboxHDD_' + rowIndex + '_' + itemIndex]"
                                                    class="input">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                        <VField style="padding:0px 10px;" v-if="item.type == 'text'">
                                            <span>{{ item.caption }}</span>
                                        </VField>
                                        <div style="background-color: lightgray;font-size: large;padding: 10px"
                                            v-if="item.type == 'head'">
                                            <span>{{ item.caption }}</span>
                                        </div>
                                        <div style="font-size: large;padding: 10px;padding-bottom: 0px;padding-top: 0px;"
                                            v-if="item.type == 'subhead'">
                                            <span>{{ item.caption }}</span>
                                        </div>
                                        <VField style="padding:0px 10px;" v-if="item.type == 'tanggal'"
                                            :label="item.caption">
                                            <VDatePicker v-model="input['tanggalIbuhamilmasukPDP_' + rowIndex + '_' + itemIndex]"
                                                mode="date" trim-weeks :max-date="new Date()">
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                        <div class="columns" v-if="item.type == '2cb'">
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" circle true-value="Ya"
                                                        label="Ya"
                                                        v-model="input['CBYaHDD2_' + rowIndex + '_' + itemIndex]" />
                                                </VControl>
                                            </div>
                                            <div class="column is-6">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" circle true-value="Tidak"
                                                        label="Tidak"
                                                        v-model="input['CBTidakHDD2_' + rowIndex + '_' + itemIndex]" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="Pertanyaan Sesudah Persalinan">
                    <div class="columns is-multiline">
                        <div class="column is-12"><b>1. Status</b></div>
                        <div class="column is-12 columns">
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Abortus" label="Abortus"
                                        v-model="input.CBAbortus" />
                                </VControl>
                            </div>
                            <div class="column is-3"><b>(berhenti)</b> </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Melahirkan"
                                        label="Melahirkan" v-model="input.CBMelahirkan" />
                                </VControl>
                            </div>
                            <div class="column is-3">(Lanjut ke pertanyaan berikutnya)</div>
                        </div>
                        <div class="column is-12 columns">
                            <div class="column is-4">
                                <VField label="Tgl dan Jam Persalinan">
                                    <VDatePicker v-model="input.DTPersalinan" mode="datetime" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Jumlah anak dilahirkan : ">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.CBjumlahAnakDilahirkan" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Tempat persalinan : ">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.CBtempatPersalinan" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <div class="column" style="text-align: center;vertical-align: middle;font-size: large">
                    <b>PEMANTAUAN BAYI</b>
                </div>
                <Fieldset :toggleable="true" legend="Pemantauan Bayi Dari Ibu Hepatitis B">
                    <div class="columns is-multiline">
                        <div class="column is-12"><b>1. TGL/Jam Pemberian : </b></div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-3">
                                <VField label="a. HBO : ">
                                    <VDatePicker v-model="input.DThbo" mode="datetime" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="b. HBIG : ">
                                    <VDatePicker v-model="input.DThbig" mode="datetime" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="c. DPT/HB1 : ">
                                    <VDatePicker v-model="input.DThb1" mode="datetime" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="d. DPT/HB2 : ">
                                    <VDatePicker v-model="input.DThb2" mode="datetime" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="e. DPT/HB3 : ">
                                    <VDatePicker v-model="input.DThb3" mode="datetime" trim-weeks
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
                        <div class="column is-12"><b>2. Pemeriksaan bayi (9-12 bulan) : </b></div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-4">a. HBsAg</div>
                            <div class="column is-3">
                                <VField label="Tanggal" horizontal>
                                    <VDatePicker v-model="input.DHBsAg" mode="date" trim-weeks :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-1">Hasil : </div>
                            <div class="column is-4 columns">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="1. Non Reaktif"
                                            label="1. Non Reaktif" v-model="input.CBnonReaktif" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="2. Reaktif"
                                            label="2. Reaktif" v-model="input.CBreaktif" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-4">b. Anti HBs</div>
                            <div class="column is-3">
                                <VField label="Tanggal" horizontal>
                                    <VDatePicker v-model="input.DantiHBs" mode="date" trim-weeks :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-1">Hasil : </div>
                            <div class="column is-4 columns">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="1. Non Reaktif"
                                            label="1. Non Reaktif" v-model="input.CBnonReaktif2" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="2. Reaktif"
                                            label="2. Reaktif" v-model="input.CBreaktif2" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="Pemantauan Bayi Dari Ibu HIV">
                    <table class="tg2">
                        <thead>
                            <tr>
                                <th style="text-align: center;vertical-align: middle;">No</th>
                                <th style="text-align: center;vertical-align: middle;">Jenis Pemantauan</th>
                                <th style="text-align: center;vertical-align: middle;">Tanggal</th>
                                <th style="text-align: center;vertical-align: middle;">Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, rowIndex) in detailPemantauanBayi" :key="rowIndex">
                                <td width="5%">{{ rowIndex + 1 }}</td>
                                <td width="100px" v-for="(item, itemIndex) in row.child" :key="itemIndex"
                                    :colspan="item.colspan">
                                    <VField style="padding:0px 10px;" v-if="item.type == 'textbox'">
                                        <VControl>
                                            <VInput
                                                v-model="input['textboxPemantauanBayi_' + rowIndex + '_' + itemIndex]"
                                                class="input">
                                            </VInput>
                                        </VControl>
                                    </VField>
                                    <VField style="padding:0px 10px;" v-if="item.type == 'text'">
                                        <span>{{ item.caption }}</span>
                                    </VField>
                                    <VField style="padding:0px 10px;" v-if="item.type == 'tanggal'">
                                        <VDatePicker
                                            v-model="input['tanggalPemantauanBayi_' + rowIndex + '_' + itemIndex]"
                                            mode="date" trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                    <div class="columns" v-if="item.type == '2cb'">
                                        <div class="column is-6">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" circle true-value="Non Reaktif"
                                                    label="Non Reaktif"
                                                    v-model="input['CBnonReaktifPemantauanBayi_' + rowIndex + '_' + itemIndex]" />
                                            </VControl>
                                        </div>
                                        <div class="column is-6">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" circle true-value="Reaktif"
                                                    label="Reaktif"
                                                    v-model="input['CBReaktifPemantauanBayi_' + rowIndex + '_' + itemIndex]" />
                                            </VControl>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="Pemantauan Bayi Dari Ibu Sifilis">
                    <div class="columns is-multiline">
                        <div class="column is-6">1. Bayi dari ibu sifilis dirujuk : </div>
                        <div class="column is-6 columns">
                            <div class="column is-6">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" circle true-value="Ya" label="Ya"
                                        v-model="input.CByaSifilis" />
                                </VControl>
                            </div>
                            <div class="column is-6">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" circle true-value="Tidak" label="Tidak"
                                        v-model="input.CBtidakSifilis" />
                                </VControl>
                            </div>
                        </div>
                        <div class="column is-12">2. Usia &lt; 2 tahun diperiksa sifilis : </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" circle true-value="Ya" label="Ya"
                                    v-model="input.CByaDiperiksaSifilis" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" circle true-value="Tidak" label="Tidak"
                                    v-model="input.CBtidakDiperiksaSifilis" />
                            </VControl>
                        </div>
                        <div class="column is-6 columns is-multiline">
                            <div class="column is-12">
                                <VField label="Tanggal" addons>
                                    <VDatePicker v-model="input.DTdiperiksaSifilis" mode="datetime" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-12">Hasil : </div>
                            <div class="column is-6">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" circle true-value="Non Reaktif"
                                        label="Non Reaktif" v-model="input.CBnonReaktifSifilis" />
                                </VControl>
                            </div>
                            <div class="column is-6">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" circle true-value="Reaktif" label="Reaktif"
                                        v-model="input.CBreaktifSifilis" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                </Fieldset>
            </div>
        </div>
    </div>
</template>