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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import * as EMR from '../page-emr-plugins/monitoring&evaluasi-resusitasi'

// Judul
useHead({
    title: 'Kriteria Hemodialisa Akut (CITO) - ' + import.meta.env.VITE_PROJECT,
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
const input: any = ref({
    JAM: newDate
})
const dataTTD: any = ref([])
const d_Dokter: any = ref([])
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
const COLLECTION: any = ref('KriteriaHemodialisaAkutCito') //table mongodb
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
    H.tandaTangan().set("TTD", dataTTD.value.TTD)
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    object['TTD'] = H.tandaTangan().get("TTD");
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

// Loopingan
let DataTable = ref([
    {
        style: 'background-color: lightblue;font-weight:bold;padding:10px',
        detail: [
            { type: 'label', label: '1. NILAI LABORATORIUM', colspan: 3 }
        ]
    },
    {
        detail: [
            { type: 'label', label: 'a. Kadar kalium serum > 6,5 mEq/L' },
            { type: 'cb', label: 'Ya' },
            { type: 'cb', label: 'Tidak' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'b. pH darah 7,2' },
            { type: 'cb', label: 'Ya' },
            { type: 'cb', label: 'Tidak' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'c. HCO3 < 12 mEq/L' },
            { type: 'cb', label: 'Ya' },
            { type: 'cb', label: 'Tidak' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'd. Disnatremia berat (Na > 160 mEq/L atau < 115 mEq/L)' },
            { type: 'cb', label: 'Ya' },
            { type: 'cb', label: 'Tidak' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'e. Ureum darah > 200mg/dL' },
            { type: 'cb', label: 'Ya' },
            { type: 'cb', label: 'Tidak' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'f. Keracunan akut (aspirin, metanol, lithium) / overdosis obat-obatan yang terdialisis' },
            { type: 'cb', label: 'Ya' },
            { type: 'cb', label: 'Tidak' },
        ]
    },
    {
        style: 'background-color: lightblue;font-weight:bold;padding:10px',
        detail: [
            { type: 'label', label: '2. NILAI RADIOLOGI', colspan: 3 }
        ]
    },
    {
        detail: [
            { type: 'label', label: 'a. Edema paru' },
            { type: 'cb', label: 'Ya' },
            { type: 'cb', label: 'Tidak' },
        ]
    },
    {
        style: 'background-color: lightblue;font-weight:bold;padding:10px',
        detail: [
            { type: 'label', label: '3. PEMERIKSAAN FISIK', colspan: 3 }
        ]
    },
    {
        detail: [
            { type: 'label', label: 'a. Anuria (produksi urin < 50 cc/12 jam)' },
            { type: 'cb', label: 'Ya' },
            { type: 'cb', label: 'Tidak' },
        ]
    },
    {
        style: 'background-color: lightblue;font-weight:bold;padding:10px',
        detail: [
            { type: 'label', label: '4. KLINIS', colspan: 3 }
        ]
    },
    {
        detail: [
            { type: 'label', label: 'a. Penurunan fungsi ginjal disertai sindrom uremia berat (muntah-muntah hebat, gastritis dengan perdarahan)' },
            { type: 'cb', label: 'Ya' },
            { type: 'cb', label: 'Tidak' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'b. Uremia disertai kondisi sepsis' },
            { type: 'cb', label: 'Ya' },
            { type: 'cb', label: 'Tidak' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'c. Ensefalopati uremikum' },
            { type: 'cb', label: 'Ya' },
            { type: 'cb', label: 'Tidak' },
        ]
    },
    {
        detail: [
            { type: 'ta', label: 'c. Ensefalopati uremikum' },
            { type: 'ttd', label: '', colspan: 2 }
        ]
    },
])

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
const simpanTemplate = () => {
  if (!input.value.namatemplate) {
        H.alert('error', 'Nama Template harus diisi untuk menyimpan.');
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

// getDataExist()
fetchPasien()
</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Kriteria Hemodialisa Akut (CITO)</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                        isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                    </VButton>
                </div>

                <hr class="m-0">

                <div class="column is-12">
                    <h1>Nama Template&emsp;&emsp;
                        <span style="color:red">**Hanya diisi jika ingin membuat template</span>
                    </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.namatemplate" rows="1">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>

                <hr class="m-0">

                <div class="column is-12">
                    <table class="tg">
                        <thead>
                            <tr>
                                <th width="60%">KRITERIA FISIOLOGIS</th>
                                <th width="20%">YA (✔)</th>
                                <th width="20%">TIDAK (✔)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(dataRow, row) in DataTable" :key="row">
                                <td v-for="(dataCol, col) in dataRow.detail" :key="col" :style="dataRow.style"
                                    :colspan="dataCol.colspan" :rowspan="dataCol.rowspan">
                                    <div v-if="dataCol.type == 'label'">
                                        {{ dataCol.label }}
                                    </div>
                                    <div v-if="dataCol.type == 'ta'">
                                        <VField label="Kesimpulan">
                                            <VTextarea rows="2" v-model="input.TAKesimpulan"></VTextarea>
                                        </VField>
                                    </div>
                                    <div v-if="dataCol.type == 'ttd'">
                                        <VField label="Garut">
                                            <VDatePicker v-model="input.JAM" mode="time" is24hr>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VControl icon="feather:clock" fullwidth>
                                                        <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                        <div class="column" style="text-align:center;">
                                            <TandaTangan :elemenID="'TTD'" :width="'150'" :height="'150'" class="dek" />
                                            <!-- <VControl class="prime-auto">
                                                <AutoComplete v-model="input.CBDokter" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'"
                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                    :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                            </VControl> -->
                                        </div>
                                    </div>
                                    <div v-if="dataCol.type == 'cb'" style="text-align: center;">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square :true-value="dataCol.label"
                                                label="" v-model="input['cb_' + row]" />
                                        </VControl>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="margin-top: 5px;">CATATAN : Mohon diisi tanda centang(✔) yang sesuai</p>
                </div>

                <!-- form baru -->
            </div>
        </div>
    </div>
</template>
