<style lang="scss">
h1 {
    font-weight: bold;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 225%;
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import Checkbox from 'primevue/checkbox';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({
    title: 'Pemakaian Therapy Eritropoitine - ' + import.meta.env.VITE_PROJECT,
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
    airway: [],
    disability: []

})
const COLLECTION: any = ref('PemakaianTherapyEritropoitine') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    // kebjamKedatangan: new Date(),
    // kebjamAsesmenAwal: new Date(),
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    }
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
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
    console.log(json)

    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            // NOREC_EMRPASIEN.value = response.norec_emr
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
// Loopingan
let Table1 = ref([
    {}, {}, {}, {}, {},
    {}, {}, {}, {}, {},
    {}, {}, {}, {}, {},

])

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
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Pemakaian Therapy Eritropoitine</h3>
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
                    <div class="column is-12">
                        <VField label="Nama Obat :">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNamaObat" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12" style="overflow: auto;">
                        <table class="tg">
                            <tr>
                                <th rowspan="2">NO</th>
                                <th colspan="2">JANUARI</th>
                                <th colspan="2">FEBROARI</th>
                                <th colspan="2">MARET</th>
                                <th colspan="2">APRIL</th>
                                <th colspan="2">MEI</th>
                                <th colspan="2">JUNI</th>
                                <th colspan="2">JULI</th>
                                <th colspan="2">AGUSTUS</th>
                                <th colspan="2">SEPTEMBER</th>
                                <th colspan="2">OKTOBER</th>
                                <th colspan="2">NOVEMBER</th>
                                <th colspan="2">DESEMBER</th>
                            </tr>
                            <tr>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                                <th>ERI</th>
                                <th>ZAT BESI</th>
                            </tr>
                            <tr v-for="(dataRow, index) in Table1" :key="index">
                                <td style="text-align: center;vertical-align: middle;">
                                    {{ index + 1 }}
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable1_' + index + '_1']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable1_' + index + '_2']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable1_' + index + '_3']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable1_' + index + '_4']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable1_' + index + '_5']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable1_' + index + '_6']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable1_' + index + '_7']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable1_' + index + '_8']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable1_' + index + '_9']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_10']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_11']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_12']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_13']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_14']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_15']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_16']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_17']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_18']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_19']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_20']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_21']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_22']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_23']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBTable1_' + index + '_24']" />
                                    </VControl>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- form baru -->

            </div>
        </div>
    </div>
</template>
