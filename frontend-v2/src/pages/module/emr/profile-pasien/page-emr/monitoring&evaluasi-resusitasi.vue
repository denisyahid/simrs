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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as EMR from '../page-emr-plugins/monitoring&evaluasi-resusitasi'
// import Fieldset from 'primevue/fieldset';

// Loopingan
let Table1 = ref(EMR.Table1())
let Table2 = ref(EMR.Table2())
let Table3 = ref(EMR.Table3())

// Judul
useHead({
    title: 'Monitoring dan Evaluasi Resusitasi - ' + import.meta.env.VITE_PROJECT,
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
function getStartOfDay(date) {
    const newDate = new Date(date);
    newDate.setHours(0, 0, 0, 0);
    return newDate;
}
const newDate = new Date();
const startOfDay = getStartOfDay(newDate);
const input: any = ref({
    TJam: newDate,
    Jam1Catatan: startOfDay,
    Jam2Catatan: startOfDay
});

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
const COLLECTION: any = ref('MonitoringDanEvaluasiResusitasi') //table mongodb
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
    // H.tandaTangan().set("TTDPetugas", dataTTD.value.TTDPetugas)
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    // object['TTDPetugas'] = H.tandaTangan().get("TTDPetugas");
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
</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Monitoring dan Evaluasi Resusitasi</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12">
                    <table width="100%">
                        <tr>
                            <td width="50%" style="text-align: center;vertical-align: middle;">URUTAN RESUSITASI</td>
                            <td width="50%">
                                <div class="columns column is-12">
                                    <div class="column is-6">
                                        <VField label="Tanggal :">
                                            <VDatePicker v-model="input.DTanggal" mode="date" trim-weeks
                                                :max-date="new Date()">
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <VField label="Jam :">
                                            <VDatePicker v-model="input.TJam" mode="time" is24hr>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VControl icon="feather:clock" fullwidth>
                                                        <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="column is-12">
                    <table class="tg">
                        <tr>
                            <th width="15%">Jam</th>
                            <th width="15%">Laboratorium</th>
                            <th width="15%">Jam</th>
                            <th width="15%">Radiologi</th>
                            <th width="15%">Jam</th>
                            <th width="15%">Prosedur</th>
                        </tr>
                        <tr v-for="(dataRow, rowIndex) in Table1" :key="rowIndex" style="vertical-align: middle;">
                            <td v-for="(dataColumn, itemIndex) in dataRow.detail" :key="itemIndex"
                                :rowspan="dataColumn.rowspan" :colspan="dataColumn.colspan">
                                <div class="column is-12" v-if="dataColumn.type == 'time'">
                                    <VDatePicker
                                        :modelValue="input['table1_time_' + rowIndex + '_' + itemIndex] || (input['table1_time_' + rowIndex + '_' + itemIndex] = new Date(new Date().setHours(0, 0, 0, 0)))"
                                        @update:modelValue="val => input['table1_time_' + rowIndex + '_' + itemIndex] = val"
                                        mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:clock" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'tbs'">
                                    <VField :label="dataColumn.label">
                                        <VField addons>
                                            <VControl>
                                                <VInput type="text" class="input"
                                                    v-model="input['table1_tbs_' + rowIndex + '_' + itemIndex]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ dataColumn.satuan }}</VButton>
                                            </VControl>
                                        </VField>
                                    </VField>
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'tb'">
                                    <VField :label="dataColumn.label">
                                        <VControl>
                                            <VInput type="text" class="input"
                                                v-model="input['table1_tb_' + rowIndex + '_' + itemIndex]" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'tb2'">
                                    <VField :label="dataColumn.label">
                                        <VControl>
                                            <VInput type="text" class="input"
                                                v-model="input['table1_tb_' + rowIndex + '_' + itemIndex]" />
                                        </VControl>
                                    </VField>
                                    <VField :label="dataColumn.label2">
                                        <VControl>
                                            <VInput type="text" class="input"
                                                v-model="input['table1_tb2_' + rowIndex + '_' + itemIndex]" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'label'">
                                    {{ dataColumn.label }}
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'chesttb'">
                                    <div class="column">
                                        <VField label="Chest tb">
                                            <VField addons>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBSChestTB" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>oleh</VButton>
                                                </VControl>
                                            </VField>
                                        </VField>
                                    </div>
                                    <div class="column is-12" style="margin-left: 10px;">
                                        <div class="column">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Prod Ka"
                                                    label="Prod Ka" v-model="input.CBProdKa" />
                                            </VControl>
                                            <VField style="margin-top:5px;">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBProdKa" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Prod Ki"
                                                    label="Prod Ki" v-model="input.CBProdKa" />
                                            </VControl>
                                            <VField style="margin-top:5px;">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBProdKi" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'ngt'">
                                    <div class="column">
                                        <VField label="NGT">
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.TBNGT" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column">
                                        <VField label="oleh">
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.TBNGToleh" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-12" style="margin-left: 10px;">
                                        <div class="column">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Prod"
                                                    label="Prod" v-model="input.CBProdNGT" />
                                            </VControl>
                                            <VField style="margin-top:5px;" addons>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBProdNGT" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>ml</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Warna"
                                                    label="Warna" v-model="input.CBWarnaNGT" />
                                            </VControl>
                                            <VField style="margin-top:5px;">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBWarnaNGT" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'rt'">
                                    <label>RT</label>
                                    <div class="column is-12" style="margin-left: 10px;">
                                        <div class="column">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Tonus"
                                                    label="Tonus" v-model="input.CBTonusRT" />
                                            </VControl>
                                        </div>
                                        <div class="column">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Darah"
                                                    label="Darah" v-model="input.CBDarahRT" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'ku'">
                                    <label>Kateter Urin</label>
                                    <div class="column is-12" style="margin-left: 10px;">
                                        <div class="column">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Prod"
                                                    label="Prod" v-model="input.CBProdKU" />
                                            </VControl>
                                            <VField addons style="margin-top:5px;">
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBSProdKU" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>ml</VButton>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Warna"
                                                    label="Warna" v-model="input.CBWarnaKU" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="column is-12">
                    <div class="column is-12"
                        style="font-weight: bold;text-align: center;font-size: large;margin: 10px;">MONITORING
                        CAIRAN</div>
                    <table class="tg">
                        <tr>
                            <th width="15%">Lokasi</th>
                            <th width="15%">Jenis Cairan</th>
                            <th width="15%">Jumlah (ml)</th>
                            <th width="15%">Lokasi</th>
                            <th width="15%">Jenis Cairan</th>
                            <th width="15%">Jumlah ( ml)</th>
                        </tr>
                        <tr v-for="(dataRow, rowIndex) in Table2" :key="rowIndex" style="vertical-align: middle;">
                            <td v-for="(dataColumn, itemIndex) in dataRow.detail" :key="itemIndex"
                                :rowspan="dataColumn.rowspan" :colspan="dataColumn.colspan">
                                <div class="column is-12" v-if="dataColumn.type == 'label'">
                                    {{ dataColumn.label }}
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'tb'">
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['table2_tb_' + rowIndex + '_' + itemIndex]" />
                                    </VControl>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="column is-12" style="overflow: auto;">
                    <div class="column is-12"
                        style="font-weight: bold;text-align: center;font-size: large;margin: 10px;">MONITORING
                        TANDA VITAL DAN AGD</div>
                    <table class="tg" style="width: 150%;">
                        <tr>
                            <th>Jenis &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; Tgl / Jam</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Nama dan paraf petugas</th>
                        </tr>
                        <tr v-for="(dataRow, rowIndex) in Table3" :key="rowIndex" style="vertical-align: middle;">
                            <td v-for="(dataColumn, itemIndex) in dataRow.detail" :key="itemIndex"
                                :rowspan="dataColumn.rowspan" :colspan="dataColumn.colspan">
                                <div class="column is-12" v-if="dataColumn.type == 'label'">
                                    {{ dataColumn.label }}
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'tb'">
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['table3_tb_' + rowIndex + '_' + itemIndex]" />
                                    </VControl>
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'datetime'">
                                    <VDatePicker v-model="input['table3_dt_' + rowIndex + '_' + itemIndex]"
                                        mode="datetime" trim-weeks :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'cb'">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="dataColumn.label"
                                            :label="dataColumn.label"
                                            v-model="input['table3_cb_' + rowIndex + '_' + itemIndex]" />
                                    </VControl>
                                </div>
                                <div class="column is-12" v-if="dataColumn.type == 'cbtb'">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="dataColumn.label"
                                            :label="dataColumn.label"
                                            v-model="input['table3_CBtb1_' + rowIndex + '_' + itemIndex]" />
                                    </VControl>
                                    <VControl style="margin-top: 5px">
                                        <VInput type="text" class="input"
                                            v-model="input['table3_cbTB2_' + rowIndex + '_' + itemIndex]" />
                                    </VControl>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="column is-12" style="margin-top: 10px;">
                    <table class="tg">
                        <tr>
                            <th width="50%">Jam</th>
                            <th width="50%">Catatan</th>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle;text-align: center;">
                                <VDatePicker v-model="input.Jam1Catatan" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:clock" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </td>
                            <td style="vertical-align: middle;text-align: center;">
                                <VField>
                                    <VTextarea rows="2" v-model="input.TA1Catatan"></VTextarea>
                                </VField>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle;text-align: center;">
                                <VDatePicker v-model="input.Jam2Catatan" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:clock" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </td>
                            <td style="vertical-align: middle;text-align: center;">
                                <VField>
                                    <VTextarea rows="2" v-model="input.TA2Catatan"></VTextarea>
                                </VField>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- form baru -->
            </div>
        </div>
    </div>
</template>