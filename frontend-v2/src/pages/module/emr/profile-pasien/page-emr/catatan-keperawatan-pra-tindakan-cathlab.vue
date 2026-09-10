<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
         <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
        <div class="column is-12" v-else>
            <VCard>
                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="RENCANA TINDAKAN">
                        <div class="columns is-multiline pt-3 pl-3">
                            <div class="column is-6" v-for="(data, i) in rencanaTindakan">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model + i]" :true-value="data.nama"
                                            :label="data.nama" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </Fieldset>
                </div>
                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="CEKLIST PRA TINDAKAN CATHLAB">
                        <div class="column is-12">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">VERIFIKASI PASIEN DAN DOKUMEN</h1>
                            <div class="columns is-multiline" v-for="(data) in checkList">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">{{ data.title }}</h1>
                                </div>
                                <div class="column pt-1 pl-3" v-for="(datas) in data.value">
                                    <VField v-if="datas.type == 'checkbox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" v-model="input[datas.model]" :true-value="datas.subTitle"
                                                :label="datas.subTitle" color="primary" square />
                                        </VControl>
                                    </VField>
                                    <VField v-else :label="datas.subTitle" style="margin-top: -1rem; margin-left: -6rem;">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model]" class="input pt-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Khusus Pasien dengan General Anestesi</h1>
                            <div class="columns is-multiline" v-for="(data) in generalAnestesi">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">{{ data.title }}</h1>
                                </div>
                                <div class="column pt-1 pl-3" v-for="(datas) in data.value">
                                    <VField v-if="datas.type == 'checkbox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" v-model="input[datas.model]" :true-value="datas.subTitle"
                                                :label="datas.subTitle" color="primary" square />
                                        </VControl>
                                    </VField>
                                    <VField v-else :label="datas.subTitle" style="margin-top: -1rem; margin-left: -6rem;">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model]" class="input pt-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">PERSIAPAN FISIK PASIEN</h1>
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <h1 style="font-weight:bold;">
                                            Puasa
                                        </h1>

                                    </div>
                                    <div class="column is-4">
                                        <h1 style="font-weight:bold;">
                                            Makan Terakhir Pukul
                                        </h1>
                                        <VField>
                                            <VDatePicker v-model="input.waktuPemeriksaan" mode="dateTime"
                                                style="width: 100%" trim-weeks :max-date="new Date()">
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField>
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" placeholder="Waktu Pemeriksaan"
                                                                v-on="inputEvents" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </VField>

                                    </div>
                                    <div class="column is-4">
                                        <h1 style="font-weight:bold;">
                                            Minum Terakhir Pukul
                                        </h1>
                                        <VField>
                                            <VDatePicker v-model="input.waktuPemeriksaan" mode="dateTime"
                                                style="width: 100%" trim-weeks :max-date="new Date()">
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField>
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" placeholder="Waktu Pemeriksaan"
                                                                v-on="inputEvents" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="columns is-multiline" v-for="(data) in fisikPasien">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">{{ data.title }}</h1>
                                </div>
                                <div class="column pt-1 pl-3" v-for="(datas) in data.value">
                                    <VField v-if="datas.type == 'checkbox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" v-model="input[datas.model]" :true-value="datas.subTitle"
                                                :label="datas.subTitle" color="primary" square />
                                        </VControl>
                                    </VField>
                                    <VField v-else :label="datas.subTitle" style="margin-top: -1rem; margin-left: -6rem;">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model]" class="input pt-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">PEMERIKSAAN PENUNJANG (wajib)</h1>
                            <div class="columns is-multiline" v-for="(data) in pemeriksaanPenunjang">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">{{ data.title }}</h1>
                                </div>
                                <div class="column pt-1 pl-3" v-for="(datas) in data.value">
                                    <VField v-if="datas.type == 'checkbox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" v-model="input[datas.model]" :true-value="datas.subTitle"
                                                :label="datas.subTitle" color="primary" square />
                                        </VControl>
                                    </VField>
                                    <VField v-else :label="datas.subTitle" style="margin-top: -1rem; margin-left: -6rem;">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model]" class="input pt-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">PEMERIKSAAN TAMBAHAN (serah terima dokumen)
                            </h1>
                            <div class="columns is-multiline" v-for="(data) in pemeriksaanTambahan">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">{{ data.title }}</h1>
                                </div>
                                <div class="column pt-1 pl-3" v-for="(datas) in data.value">
                                    <VField v-if="datas.type == 'checkbox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" v-model="input[datas.model]" :true-value="datas.subTitle"
                                                :label="datas.subTitle" color="primary" square />
                                        </VControl>
                                    </VField>
                                    <VField v-else :label="datas.subTitle" style="margin-top: -1rem; margin-left: -6rem;">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model]" class="input pt-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">TERAPI FARMAKOLOGI</h1>
                            <div class="columns is-multiline" v-for="(data) in farmakologi">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">{{ data.title }}</h1>
                                </div>
                                <div class="column pt-1 pl-3" v-for="(datas) in data.value">
                                    <VField v-if="datas.type == 'checkbox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" v-model="input[datas.model]" :true-value="datas.subTitle"
                                                :label="datas.subTitle" color="primary" square />
                                        </VControl>
                                    </VField>
                                    <VField v-else :label="datas.subTitle" style="margin-top: -1rem; margin-left: -6rem;">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model]" class="input pt-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <table class="tg">
                                <thead>
                                    <tr>
                                        <td class="tg-0lax text-center" rowspan="2">Tanggal/Jam</td>
                                        <td class="tg-0lax text-center" colspan="4">Terapi obat saat ini</td>

                                    </tr>
                                    <tr>
                                        <td class="tg-0lax">Nama Obat </td>
                                        <td class="tg-0lax">Dosis </td>
                                        <td class="tg-0lax">#</td>

                                    </tr>
                                </thead>
                                <tbody v-for="(item, index) in input.details" :key="index">
                                    <tr>
                                        <td style="width:25%">
                                            <VField>
                                                <VControl class="prime-auto">
                                                    <Calendar v-model="item.waktuPemberian" selectionMode="single"
                                                        :manualInput="false" class="w-100" :showIcon="true" showTime
                                                        hourFormat="24" :date-format="'yy-mm-dd'" />
                                                </VControl>
                                            </VField>
                                        </td>

                                        <td>
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="item.namaObat" placeholder="Nama Obat" />
                                                </VControl>
                                            </VField>
                                        </td>

                                        <td style="width:25%">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="item.dosis" placeholder="Nama Obat" />
                                                </VControl>
                                            </VField>
                                        </td>
                                        <td rowspan="2" style="width:13%;vertical-align: middle;">
                                            <div class="columns is-multiline">
                                                <div class="column is-6">
                                                    <VIconButton type="button" raised circle icon="feather:plus"
                                                        @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                                    </VIconButton>
                                                </div>
                                                <div class="column is-6 ml-3-min">
                                                    <VIconButton v-if="index > 0" type="button" raised circle
                                                        icon="feather:trash" @click="removeItem(index)" color="danger">
                                                    </VIconButton>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>



                                </tbody>
                            </table>
                            <h1 style="font-weight: bold;">
                                (Pasien diantar ke Cath Lab dengan membawa file lengkap dan obat-obatan)
                            </h1>
                        </div>

                    </Fieldset>
                </div>


            </VCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/catatan-keperawatan-pra-tindakan-cathlab'
import Fieldset from 'primevue/fieldset';
import Calendar from 'primevue/calendar';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let checkList = ref(EMR.checkList())
let generalAnestesi = ref(EMR.generalAnestesi())
let fisikPasien = ref(EMR.fisikPasien())
let pemeriksaanPenunjang = ref(EMR.pemeriksaanPenunjang())
let pemeriksaanTambahan = ref(EMR.pemeriksaanTambahan())
let farmakologi = ref(EMR.farmakologi())
let rencanaTindakan = ref(EMR.rencanaTindakan())

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

const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('CatatanKeperawatanPraTindakanCathlab') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    waktuPemeriksaan: new Date(),
    details: [{
        no: 1,
        tgl: new Date(),
    }]
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
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
const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}
setView()

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




</script>
<style lang="scss">
.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    border-color: var(--fade-grey-dark-3);
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
    vertical-align: top
}
</style>
