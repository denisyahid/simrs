<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="column is-12">
        <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
        <VCard v-else>
            <div class="column is-12">
                <Fieldset :toggleable="true" legend="SIGN IN">
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">Verifikasi :</h1>
                        <div class="columns is-multiline pt-3 pl-3">
                            <div class="column is-3" v-for="(data, i) in verifikasi">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model + i]" :true-value="data.value"
                                            :label="data.title" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Operator </h1>
                                <VField>
                                    <VControl>
                                        <AutoComplete v-model="input.operator" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Anastesi </h1>
                                <VField>
                                    <VControl>
                                        <AutoComplete v-model="input.anastesi" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Tindakan </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Nama Tindakan"
                                            v-model="input.namaTindakan" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">Kelengkapan :</h1>
                        <div class="columns is-multiline pt-3 pl-3">
                            <div class="column is-2" v-for="(data, i) in kelengkapan">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model + i]" :true-value="data.value"
                                            :label="data.title" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight:bold;">Pemerikasaan Tanda Vital </h1>
                    </div>

                    <div class="column is-12 p-0" v-for="(data) in tandaVitalSI">
                        <h1 class="emr mb-3">{{ data.title }}</h1>
                        <div class="columns is-multiline pl-3 pr-3">
                            <div class="column is-2" v-for="(detail) in data.value">
                                <p>{{ detail.subTitle }}</p>
                                <VField addons v-if="detail.type == 'textfiled'">
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="input[detail.model]" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>{{ detail.satuan }}</VButton>
                                    </VControl>
                                </VField>

                                <VField v-else-if="detail.type == 'textbox'">
                                    <VControl>
                                        <input v-model="input[detail.model]" class="input"
                                            :placeholder="detail.subTitle + ' ...'" />
                                    </VControl>
                                </VField>

                                <VField v-else>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                            :label="detail.satuan" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12" v-for="(datas, i) in resiko">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                        <div class="columns is-multiline">
                            <div class="column is-3" v-for="(data, b) in datas.value">
                                <VField v-if="data.type == 'checkBox'">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle"
                                            :label="data.subTitle" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>
                                <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                    style="margin-left: -4rem;">
                                    <VControl raw subcontrol>
                                        <input v-model="input[data.model + b]" class="input p-0" />
                                    </VControl>
                                </VField>
                            </div>

                        </div>


                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal Verifikasi </h1>
                                <VField>
                                    <VDatePicker v-model="input.tglVerifikasi" mode="dateTime" style="width: 100%"
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
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Dokter/ Perawat </h1>
                                <VField>
                                    <VControl>
                                        <AutoComplete v-model="input.dokter" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                </Fieldset>
            </div>
            <div class="column is-12">
                <Fieldset :toggleable="true" legend="TIME OUT">
                    <div class="column is-12" v-for="(datas, i) in time">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                        <div class="columns is-multiline">
                            <div class="column is-3" v-for="(data, b) in datas.value">
                                <VField v-if="data.type == 'checkBox'">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle"
                                            :label="data.subTitle" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>
                                <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                    style="margin-left: -4rem;">
                                    <VControl raw subcontrol>
                                        <input v-model="input[data.model + b]" class="input p-0" />
                                    </VControl>
                                </VField>
                            </div>

                        </div>


                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Obat </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Nama Obat"
                                            v-model="input.namaObat" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal Diberikan </h1>
                                <VField>
                                    <VDatePicker v-model="input.tanggalDiberikan" mode="dateTime" style="width: 100%"
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
                    <div class="column is-12">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Antibiotik Profilaksis</h1>
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Apakah sudah diberikan dalam waktu sekurangnya
                            30-60 menit ?</h1>
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Obat </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Nama Obat"
                                            v-model="input.namaObat2" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Dosis Obat </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Nama Obat"
                                            v-model="input.dosisObat" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal diberikan</h1>
                                <VField>
                                    <VDatePicker v-model="input.tanggalDiberikan" mode="dateTime" style="width: 100%"
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
                            <div class="column is-3" style="margin-top: 2rem;">
                                <VField>
                                    <VControl>
                                        <VRadio v-model="input.Konstipasi" value="Ya" label="Tidak" name="Konstipasi"
                                            color="info" square style="font-size: 15px" />

                                    </VControl>
                                </VField>
                            </div>
                        </div>

                    </div>
                    <div class="column is-12" v-for="(datas, i) in foto">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                        <div class="columns is-multiline">
                            <div class="column is-3" v-for="(data, b) in datas.value">
                                <VField v-if="data.type == 'checkBox'">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle"
                                            :label="data.subTitle" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>
                                <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                    style="margin-left: -4rem;">
                                    <VControl raw subcontrol>
                                        <input v-model="input[data.model + b]" class="input p-0" />
                                    </VControl>
                                </VField>
                            </div>

                        </div>


                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal Verifikasi </h1>
                                <VField>
                                    <VDatePicker v-model="input.tglVerifikasi2" mode="dateTime" style="width: 100%"
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
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Perawat Sirkuler </h1>
                                <VField>
                                    <VControl>
                                        <AutoComplete v-model="input.perawatSirkuler" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </Fieldset>
            </div>
            <div class="column is-12">
                <Fieldset :toggleable="true" legend="SIGN OUT">
                    <div class="column is-12" v-for="(datas, i) in signOut">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                        <div class="columns is-multiline">
                            <div class="column is-3" v-for="(data, b) in datas.value">
                                <VField v-if="data.type == 'checkBox'">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle"
                                            :label="data.subTitle" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>
                                <VField :label="data.subTitle" v-else-if="data.type == 'textBox'">
                                    <VControl raw subcontrol>
                                        <input v-model="input[data.model + b]" class="input p-0" />
                                    </VControl>
                                </VField>
                            </div>

                        </div>


                    </div>
                    <div class="column is-12" v-for="(datas, i) in bahanPemeriksaan">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                        <div class="columns is-multiline">
                            <div class="column is-3" v-for="(data, b) in datas.value">
                                <VField v-if="data.type == 'checkBox'">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle"
                                            :label="data.subTitle" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>
                                <VField :label="data.subTitle" v-else-if="data.type == 'textBox'">
                                    <VControl raw subcontrol>
                                        <input v-model="input[data.model + b]" class="input p-0" />
                                    </VControl>
                                </VField>
                            </div>

                        </div>


                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight:bold;">Pemerikasaan Tanda Vital </h1>
                    </div>
                    <div class="column is-12 p-0" v-for="(data) in tandaVitalSO">
                        <h1 class="emr mb-3">{{ data.title }}</h1>
                        <div class="columns is-multiline pl-3 pr-3">
                            <div class="column is-3" v-for="(detail) in data.value">
                                <p>{{ detail.subTitle }}</p>
                                <VField addons v-if="detail.type == 'textfiled'">
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="input[detail.model]" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>{{ detail.satuan }}</VButton>
                                    </VControl>
                                </VField>

                                <VField v-else-if="detail.type == 'textbox'">
                                    <VControl>
                                        <input v-model="input[detail.model]" class="input"
                                            :placeholder="detail.subTitle + ' ...'" />
                                    </VControl>
                                </VField>

                                <VField v-else>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                            :label="detail.satuan" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12" v-for="(datas, i) in lukaOperasi">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                        <div class="columns is-multiline">
                            <div class="column is-3" v-for="(data, b) in datas.value">
                                <VField v-if="data.type == 'checkBox'">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle"
                                            :label="data.subTitle" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>
                                <VField :label="data.subTitle" v-else-if="data.type == 'textBox'">
                                    <VControl raw subcontrol>
                                        <input v-model="input[data.model + b]" class="input p-0" />
                                    </VControl>
                                </VField>
                            </div>

                        </div>


                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal Verifikasi </h1>
                                <VField>
                                    <VDatePicker v-model="input.tglVerifikasi" mode="dateTime" style="width: 100%"
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
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Dokter Operator </h1>
                                <VField>
                                    <VControl>
                                        <AutoComplete v-model="input.dokterOperator" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </Fieldset>
            </div>

        </VCard>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/checklist-keselamatan-bedah'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let verifikasi = ref(EMR.verifikasi())
let kelengkapan = ref(EMR.kelengkapan())
let tandaVitalSI = ref(EMR.tandaVitalSI())
let resiko = ref(EMR.resiko())
let time = ref(EMR.time())
let foto = ref(EMR.foto())
let signOut = ref(EMR.signOut())
let bahanPemeriksaan = ref(EMR.bahanPemeriksaan())
let tandaVitalSO = ref(EMR.tandaVitalSO())
let lukaOperasi = ref(EMR.lukaOperasi())

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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const d_Pegawai: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggalDiberikan: new Date(),
    tglVerifikasi: new Date(),
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
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

}
setView()
setAutoFill()

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
