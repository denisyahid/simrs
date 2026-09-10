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

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Asesmen Psikologis</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun" :isHideCetak="true"
                                isHideST>
                            </ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="columns is-multiline" style="padding: 10px;">
                    <div class="column is-12" style="margin-bottom: 10px;">
                        <h1 style="font-weight: bold;">Tanggal & Jam</h1>
                        <VField addons>
                            <VDatePicker v-model="input.DTform" mode="datetime" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal dan Jam" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <Fieldset :toggleable="true" legend="A. Data Awal" style="margin-bottom: 10px" class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-12" style="font-weight: bold;">Rujukan :</div>
                            <div class="column is-8 columns is-multiline">
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya, dari"
                                            label="Ya, dari" v-model="input.CBRujukanYa" />
                                    </VControl>
                                </div>
                                <div class="column is-3" style="text-align: center;">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.CBtidakRujukan" />
                                    </VControl>
                                </div>
                                <div class="column is-12 p-0"></div>
                                <div class="column is-4" v-if="input.CBRujukanYa == 'Ya, dari'">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="RS" label="RS"
                                            v-model="input.CBrsRujukan" />
                                    </VControl>
                                    <VControl style="margin-top: 5px">
                                        <VInput type="text" class="input" v-model="input.TBrsRujukan" />
                                    </VControl>
                                </div>
                                <div class="column is-4" v-if="input.CBRujukanYa == 'Ya, dari'">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Puskesmas"
                                            label="Puskesmas" v-model="input.CBPuskesmasRujukan" />
                                    </VControl>
                                    <VControl style="margin-top: 5px">
                                        <VInput type="text" class="input" v-model="input.TBPuskesmasRujukan" />
                                    </VControl>
                                </div>
                                <div class="column is-4" v-if="input.CBRujukanYa == 'Ya, dari'">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Dokter" label="Dokter"
                                            v-model="input.CBDokterRujukan" />
                                    </VControl>
                                    <VControl style="margin-top: 5px">
                                        <VInput type="text" class="input" v-model="input.TBDokterRujukan" />
                                    </VControl>
                                </div>
                                <div class="column is-4" v-if="input.CBRujukanYa == 'Ya, dari'">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                            label="Lainnya" v-model="input.CBLainnyaRujukan" />
                                    </VControl>
                                    <VControl style="margin-top: 5px">
                                        <VInput type="text" class="input" v-model="input.TBLainnyaRujukan" />
                                    </VControl>
                                </div>
                                <div class="column is-4" v-if="input.CBRujukanYa == 'Ya, dari'">
                                    <VField label="DxRujukan">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBDxRujukan" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="columns is-multiline">
                            <div class="column is-12" style="font-weight: bold;">Anamnesa :</div>
                            <div class="column is-12" style="margin-top: -10px;">
                                <VField>
                                    <VTextarea rows="2" v-model="input.TAAnamnesaDA"></VTextarea>
                                </VField>
                            </div>
                        </div>
                    </Fieldset>
                    <Fieldset :toggleable="true" legend="B. Status Psikologis" style="margin-bottom: 10px"
                        class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-12" style="font-weight: bold;">OBSERVASI</div>
                            <div class="column is-3">
                                <VField label="Penampilan">
                                    <VTextarea rows="2" v-model="input.TApenampilanSP"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Ekspresi wajah">
                                    <VTextarea rows="2" v-model="input.TAExpresiSP"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Perasaan (Mood)">
                                    <VTextarea rows="2" v-model="input.TAperasaanSP"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Fungsi Umum">
                                    <VTextarea rows="2" v-model="input.TAfungsiUmumSP"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Intelektual">
                                    <VTextarea rows="2" v-model="input.TAintelektualSP"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Lain-lain">
                                    <VTextarea rows="2" v-model="input.TAlainlainSP"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Isi Pikir">
                                    <VTextarea rows="2" v-model="input.TAisiPikirSP"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Afeksi">
                                    <VTextarea rows="2" v-model="input.TAafeksiSP"></VTextarea>
                                </VField>
                            </div>
                        </div>
                    </Fieldset>
                    <Fieldset :toggleable="true" legend="C. Tes Psikologis" style="margin-bottom: 10px"
                        class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <VField>
                                    <label>Tes Psikologi yang diberikan :</label>
                                    <VTextarea rows="2" v-model="input.TAtpydTP"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField>
                                    <label>Hasil Tes Psikologis :</label>
                                    <VTextarea rows="2" v-model="input.TAExpresiTP"></VTextarea>
                                </VField>
                            </div>
                        </div>
                    </Fieldset>
                    <Fieldset :toggleable="true" legend="D. INTERVENSI / PSIKOTERAPI" style="margin-bottom: 10px"
                        class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <VField>
                                    <label>Target Intervensi :</label>
                                    <VTextarea rows="2" v-model="input.TAperasaanTP"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField>
                                    <label>Intervensi yang dilakukan sekarang :</label>
                                    <VTextarea rows="2" v-model="input.TAfungsiUmumTP"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField>
                                    <label>Intervensi Selanjutnya :</label>
                                    <VTextarea rows="2" v-model="input.TAintelektualTP"></VTextarea>
                                </VField>
                            </div>
                        </div>
                    </Fieldset>
                    <Fieldset :toggleable="true" legend="E. Proses Psikoterapi Konseling" style="margin-bottom: 10px"
                        class="column is-12">
                        <VField>
                            <VTextarea rows="2" v-model="input.TAppk"></VTextarea>
                        </VField>
                    </Fieldset>
                    <Fieldset :toggleable="true" legend="F. Prognosis" style="margin-bottom: 10px" class="column is-12">
                        <VField>
                            <VTextarea rows="2" v-model="input.TAprognosis"></VTextarea>
                        </VField>
                    </Fieldset>
                    <Fieldset :toggleable="true" legend="G. Tindak Lanjut" style="margin-bottom: 10px"
                        class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <VField label="Pertemuan Selanjutnya :">
                                    <VTextarea rows="2" v-model="input.TAps"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Dirujuk kepada :">
                                    <VTextarea rows="2" v-model="input.TAdk"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Diakhiri tanggal :">
                                    <VDatePicker v-model="input.DdiakhiriTanggal" mode="date" trim-weeks
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
                    </Fieldset>
                    <div class="column is-12 columns">
                        <div class="column is-8"></div>
                        <div class="column is-4" style="text-align: center;">
                            <h1>Nama dan tanda tangan Psikolog</h1>
                            <TandaTangan :elemenID="'TTDPsikolog'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.DDPsikolog" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import * as EMR from '../page-emr-plugins/monitoring&evaluasi-resusitasi'

// Judul
useHead({title: 'Asesmen Psikologis - ' + import.meta.env.VITE_PROJECT})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const user = useUserSession().getUser().pegawai;
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
const input: any = ref({})
const dataTTD: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
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
const COLLECTION: any = ref('AsesmenPsikologis') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const loadRiwayat = async () => {
    await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        if (response.length) {
            input.value = response[0] //set ke inputan
            if (NOREC_EMRPASIEN.value == '') {
                NOREC_EMRPASIEN.value = response[0].emrpasienfk
            }
            dataTTD.value = response[0]
            H.tandaTangan().set("TTDPsikolog", dataTTD.value.TTDPsikolog)
        } else {
            input.value.DDPsikolog = { label: user.namaLengkap, value: user.id }
            input.value.DTform = new Date()
        }
    })
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    object['TTDPsikolog'] = H.tandaTangan().get("TTDPsikolog");
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

const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}

// const fetchDokter = async (filter: any) => {
//     await useApi().get(
//         `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//     ).then((response) => {
//         d_Dokter.value = response
//     })
// }

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
