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

    <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
    <div class="columns is-multiline p-2" v-else>
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-5">
                                <h1 style="font-weight : bold; margin-bottom: 1rem;"> Jenis Tindakan</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.jenisTindakan" placeholder="Jenis Tindakan" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-5">
                                <h1 style="font-weight : bold; margin-bottom: 1rem;"> Lokasi Pungsi / Insisi</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.lokasiPungsi" placeholder="Lokasi Pungsi" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>
            </VCard>
        </div>

        <div class="column is-12">
            <Fieldset :toggleable="true" legend="Keadaan Umum Pasien">
                <div class="column is-12" v-for="(datas) in TandaVital">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline" v-if="datas.type == 'checkBox'">
                        <div class="column" v-for="(data) in datas.value">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="pb-0" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline" v-if="datas.type == 'textBox'">
                        <div class="column is-3" v-for="(data) in datas.value">
                            <h1 style="font-weight: bold;">{{ data.subTitle }}</h1>
                            <VField addons v-if="data.satuan">
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input[data.model]" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>{{ data.satuan }}</VButton>
                                </VControl>
                            </VField>
                            <VField v-else>
                                <VControl raw subcontrol>
                                    <input v-model="input[data.subTitle]" class="input" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>
                <div class="column is-12" v-for="(datas) in kesadaran">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline" v-if="datas.type == 'checkBox'">
                        <div class="column" v-for="(data) in datas.value">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="pb-0 pt-0" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline" v-if="datas.type == 'textBox'">
                        <div class="column is-3" v-for="(data) in datas.value">
                            <h1 style="font-weight: bold;">{{ data.subTitle }}</h1>
                            <VField addons v-if="data.satuan">
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input[data.model]" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>{{ data.satuan }}</VButton>
                                </VControl>
                            </VField>
                            <VField v-else>
                                <VControl raw subcontrol>
                                    <input v-model="input[data.subTitle]" class="input" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>

            </Fieldset>
        </div>

        <div class="column is-12">
            <Fieldset :toggleable="true" legend="Riwayat Kesehatan">
                <div class="column is-12" v-for="(datas) in riwayatKesehatan">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0">
                                <VControl raw subcontrol>
                                    <input v-model="input[data.model]" class="input p-0" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>

            </Fieldset>

        </div>

        <div class="column is-12">
            <Fieldset :toggleable="true" legend="Riwayat Tindakan Sebelumnya">
                <div class="column is-12" v-for="(datas) in riwayatTindakan">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0">

                                <VDatePicker v-model="input.tanggalTindakan" mode="dateTime" style="width: 100%" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>

                            </VField>
                        </div>
                    </div>

                </div>
                <div class="column is-12" v-for="(datas) in lokasiStent">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline" v-if="datas.type == 'checkBox'">
                        <div class="column" v-for="(data) in datas.value">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="pb-0" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline" v-if="datas.type == 'textBox'">
                        <div class="column is-3" v-for="(data) in datas.value">
                            <h1 style="font-weight: bold;">{{ data.subTitle }}</h1>
                            <VField addons v-if="data.satuan">
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input[data.model]" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>{{ data.satuan }}</VButton>
                                </VControl>
                            </VField>
                            <VField v-else>
                                <VControl raw subcontrol>
                                    <input v-model="input[data.subTitle]" class="input" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>
                <div class="column is-12" v-for="(datas) in riwayatCABG">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0">

                                <VDatePicker v-model="input.tanggalCABG" mode="dateTime" style="width: 100%" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>

                            </VField>
                        </div>
                    </div>

                </div>
                <div class="column is-12" v-for="(datas) in riwayatEP">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0">

                                <VDatePicker v-model="input.tanggalEP" mode="dateTime" style="width: 100%" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>

                            </VField>
                        </div>
                    </div>

                </div>
                <div class="column is-12" v-for="(datas) in pemasanganAlatJantung">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0">
                                <VControl raw subcontrol>
                                    <input v-model="input[data.model]" class="input p-0" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>
            </Fieldset>
        </div>

        <div class="column is-12">
            <Fieldset :toggleable="true" legend="Riwayat Pengobatan">
                <div class="column is-12" v-for="(datas) in riwayatPengobatan">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0">
                                <VControl raw subcontrol>
                                    <input v-model="input[data.model]" class="input p-0" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>
                <div class="column is-12" v-for="(datas) in Pilihan">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0"
                                style="margin-left:-5rem">
                                <VControl raw subcontrol>
                                    <input v-model="input[data.model]" class="input p-0" />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox2'" class="p-0"
                                style="margin-top: -1.5rem;">
                                <VDatePicker v-model="input.tanggalMetamorfin" mode="dateTime" style="width: 100%"
                                    trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>

                            </VField>
                        </div>
                    </div>

                </div>
                <h1 style="font-weight: bold"> (Khusus tindakan PPM Obat Antikoagulan/Antiplatelet sudah di tunda/stop)
                </h1>
            </Fieldset>
        </div>

        <div class="column is-12">
            <Fieldset :toggleable="true" legend="Pemeriksaan Penunjang">
                <div class="column is-6">
                    <h1 style="font-weight: bold"> Laboratorium</h1>
                    <h1 style="font-weight: bold"> Tanggal</h1>
                    <VField>
                        <VDatePicker v-model="input.tanggalCABG" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                                <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>

                    </VField>

                </div>
                <div class="column is-12" v-for="(datas) in HB">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column is-2" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkBox'">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                        :label="data.subTitle" class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" style="margin-top: -1rem;">
                                <VControl raw subcontrol>
                                    <input v-model="input[data.model]" class="input p-0" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>
                <div class="column is-6">
                    <h1 style="font-weight: bold"> Ekokardiografi</h1>
                    <h1 style="font-weight: bold"> Ejeksi Fraksi</h1>
                    <div class="column is-12">

                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Ejeksi Fraksi" v-model="input.ejeksi" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>%</VButton>
                            </VControl>
                        </VField>
                    </div>
                </div>

            </Fieldset>
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
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-pra-tindakan-cathlab'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let TandaVital = ref(EMR.TandaVital())
let kesadaran = ref(EMR.kesadaran())
let riwayatKesehatan = ref(EMR.riwayatKesehatan())
let riwayatTindakan = ref(EMR.riwayatTindakan())
let lokasiStent = ref(EMR.lokasiStent())
let riwayatCABG = ref(EMR.riwayatCABG())
let riwayatEP = ref(EMR.riwayatEP())
let pemasanganAlatJantung = ref(EMR.pemasanganAlatJantung())
let riwayatPengobatan = ref(EMR.riwayatPengobatan())
let Pilihan = ref(EMR.Pilihan())
let HB = ref(EMR.HB())


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
const COLLECTION: any = ref('AsesmenPraTindakanCathlab') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggalTindakan: new Date(),
    tanggalCABG: new Date(),
    tanggalEP: new Date(),
    tanggalWastarin: new Date(),
    tanggalMetamorfin: new Date()
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
const setAutoFill = async () => {
    input.value.tanggal = new Date()
    input.value.waktuAsesmen = new Date()
    input.value.dokterAsesmen = {
        label: H.pegawaiLogin().namalengkap,
        value: H.pegawaiLogin().id
    }

    await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN +
        "&collection=VitalSign" +
        "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,SPO2"
    ).then((response) => {

        input.value.beratBadan = response.beratBadan ? response.beratBadan : ''
        input.value.tinggiBadan = response.tinggiBadan ? response.tinggiBadan : ''
        input.value.IMT = response.IMT ? response.IMT : ''
        input.value.lingkarPerut = response.lingkarPerut ? response.lingkarPerut : ''
        input.value.tekananDarah = response.tekananDarah ? response.tekananDarah : ''
        input.value.pernapasan = response.pernapasan ? response.pernapasan : ''
        input.value.suhu = response.suhu ? response.suhu : ''
        input.value.nadi = response.nadi ? response.nadi : ''
    })
}
setAutoFill()
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