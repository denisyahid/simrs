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
    width: 150%;
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
import { h, reactive, ref, computed} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as H from '/@src/utils/appHelper'
import * as EMR from '../page-emr-plugins/form-pemeriksaan-fisik-tki'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

// Loopingan
let detailAnamnesa1 = ref(EMR.detailAnamnesa1())
let detailAnamnesa2 = ref(EMR.detailAnamnesa2())
let detailTandaVital = ref(EMR.detailTandaVital())
let detailTandaVitalTable = ref(EMR.detailTandaVitalTable())

// Judul
useHead({
    title: 'Form Pemeriksaan Fisik TKI - ' + import.meta.env.VITE_PROJECT,
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
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&limit=10`)
    d_Dokter.value = response
}
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
const COLLECTION: any = ref('FormPemeriksaanFisikCalonTKI') //table mongodb
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
    H.tandaTangan().set("TTDsuratPernyataan", dataTTD.value.TTDsuratPernyataan)
    H.tandaTangan().set("TTDDokterPemeriksa", dataTTD.value.TTDDokterPemeriksa)
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object['TTDsuratPernyataan'] = H.tandaTangan().get("TTDsuratPernyataan");
    object['TTDDokterPemeriksa'] = H.tandaTangan().get("TTDDokterPemeriksa");
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

// onBeforeMount(async () => {
//     try {
//         await loadRiwayat()
//         let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
//         if (cache) input.value = cache
//         loadData.value = false
//     } catch (error) {
//         console.error('Error mount cache TAB EMR:', error);
//     }
// });
// onBeforeRouteLeave((to, from, next) => {
//     try {
//         let rouutename = from?.name
//         H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
//     } catch (error) {
//         console.error('Error leave cache TAB EMR:', error);
//     }
//     next();
// });

fetchDokter()
getDataExist()
fetchPasien()

</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Form Pemeriksaan Fisik Calon TKI</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun" :isHideCetak="true">
                            </ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
                <div class="columns is-multiline" style="margin-top: 20px;padding: 12px;padding-top: 0px;">
                    <div class="column is-12" style="text-align: center;font-weight: bold;">ANAMNESA</div>
                    <div class="column is-12">1. Riwayat Penyakit Sekarang : </div>
                    <div class="columns is-multiline column is-12">
                        <div class="column is-3" v-for="(data, index) in detailAnamnesa1" :key="index">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square :true-value="data.caption"
                                        :label="data.caption" v-model="input['CBRPS1_' + index]" />
                                </VControl>
                            </VField>
                        </div>
                    </div><br>
                    <div class="column is-12">2. Riwayat kebiasaan : </div>
                    <div class="column is-3" v-for="(data, index) in detailAnamnesa2" :key="index">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.caption"
                                    :label="data.caption" v-model="input['CBRPS2_' + index]" />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-12">3. Riwayat penyakit dahulu : </div>
                    <div class="column is-12">
                        <VField>
                            <VControl>
                                <VInput type="text" class="input" placeholder="Riwayat Penyakit Dahulu"
                                    v-model="input.TBriwayatPenyakitDahulu" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">4. Riwayat penyakit keluarga : </div>
                    <div class="column is-12">
                        <VField>
                            <VControl>
                                <VInput type="text" class="input" placeholder="Riwayat Penyakit Keluarga"
                                    v-model="input.TBriwayatPenyakitKeluarga" />
                            </VControl>
                        </VField>
                    </div>
                </div>

                <Fieldset :toggleable="true" legend="Nama Paduan ART Orisinal">
                    <div class="columns is-multiline" style="padding: 10px; padding-top: 0px">
                        <div class="column is-12" style="text-align: center;font-weight: bold;">
                            <span>Surat Pernyataan</span>
                        </div>
                        <div class="column is-12">
                            <span>Saya menyatakan bahwa informasi yang saya berikan di atas adalah benar. Saya
                                menyetujui hasil pemeriksaan
                                saya dapat diberikan kepada petugas meds atau pihak ain yang berwenang.</span>
                        </div>
                        <div class="column is-6" style="text-align: center;">
                            <div class="column is-12" style="font-weight: bold;">
                                <span>Tanggal</span>
                            </div>
                            <VDatePicker v-model="input.tanggalSuratPernyataan" mode="date" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-6" style="text-align: center;">
                            <div class="column is-12" style="font-weight: bold;">
                                <span>Tanda tangan</span>
                            </div>
                            <TandaTangan :elemenID="'TTDsuratPernyataan'" :width="'150'" :height="'150'" class="dek" />
                        </div>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <div class="columns is-multiline" style="margin-top: 20px;padding: 12px;padding-top: 0px;">
                    <div class="column is-12" style="text-align: center;font-weight: bold;">Pemeriksaan Tanda Vital
                    </div>
                    <div class="columns is-multiline column is-12">
                        <div class="column is-4" v-for="(data, index) in detailTandaVital" :key="index">
                            <span style="padding: 10px;">{{ data.caption }}</span>
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="..."
                                        v-model="input['TBStv_' + index]" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>{{ data.satuan }}</VButton>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <br><br>

                <div class="columns is-multiline column is-12" style="margin-top: 20px;padding: 12px;padding-top: 0px;">
                    <div class="column is-12" style="overflow: auto;">
                        <table class="tg">
                            <thead>
                                <tr>
                                    <th style="text-align: center;vertical-align: middle;">Pemeriksaan Fisik</th>
                                    <th style="text-align: center;vertical-align: middle;">Normal</th>
                                    <th style="text-align: center;vertical-align: middle;">Abnormal</th>
                                    <th style="text-align: center;vertical-align: middle;">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, rowIndex) in detailTandaVitalTable" :key="rowIndex">
                                    <td width="200px" v-for="(item, itemIndex) in row.child" :key="itemIndex"
                                        :colspan="item.colspan">
                                        <VField style="padding:0px 10px;" v-if="item.type == 'textbox'">
                                            <VControl>
                                                <VInput v-model="input['textboxTVT_' + rowIndex + '_' + itemIndex]"
                                                    class="input">
                                                </VInput>
                                            </VControl>
                                        </VField>
                                        <VField style="padding:0px 10px;" v-if="item.type == 'text'">
                                            <span>{{ item.caption }}</span>
                                        </VField>
                                        <VField style="padding:0px 10px;" v-if="item.type == 'list'">
                                            <span><b>Otot :</b></span>
                                            <ol style="padding:10px">
                                                <li>Trofi</li>
                                                <li>Tonus</li>
                                                <li>Kekuatan</li>
                                            </ol>
                                        </VField>
                                        <VField
                                            style="padding:0px; background-color: lightgray; color: black;font-size: large;"
                                            v-if="item.type == 'head'">
                                            <span style="padding: 10px;">{{ item.caption }}</span>
                                        </VField>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="column is-12" style="margin-top: 10px;">
                        <VField label="Kesimpulan Hasil Pemeriksaan Fisik">
                            <VTextarea rows="2" placeholder="Kesimpulan hasil pemeriksaan fisik"
                                v-model="input.TAKesimpulanHasilPemeriksaanFisik"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-12 columns is-multiline">
                        <div class="column is-4">
                            <VField label="Tanggal">
                                <VDatePicker v-model="input.DateTanggal" mode="date" trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-4" style="text-align:center">
                            <div class="column is-12" style="font-weight: bold;">
                                <span>Tanda tangan</span>
                            </div>
                            <TandaTangan :elemenID="'TTDDokterPemeriksa'" :width="'150'" :height="'150'" class="dek" />
                        </div>
                        <div class="column is-4">
                            <VField label="Nama Dokter Pemeriksa">
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.ComboBoxDP" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="Nama Dokter..." class="mt-2" />
                                </VControl>
                            </VField>
                            <VField label="No. SIP">
                                <VControl>
                                    <VInput type="text" class="input" placeholder="NO. SIP..."
                                        v-model="input.TBnoSIP" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
            </div>
        </div>

    </div>
</template>