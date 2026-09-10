<template>
    <div>
        <div class="form-layout is-stacked-2" style="
    width: 100%;
    max-width: none;">
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

        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-3">
                    <h1 style="font-weight: bold;" class="mb-2">Tanggal & waktu Insiden</h1>
                    <VField>
                        <VDatePicker v-model="item.tanggal" mode="dateTime" style="width: 100%" trim-weeks
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

                <div class="column is-5">
                    <h1 style="font-weight: bold;" class="mb-2">Keselamatan</h1>
                    <VField>
                        <VControl icon="feather:search" class="prime-auto">
                            <Dropdown v-model="item.keselamatanfk" :options="d_Keselamatan" :optionLabel="'label'"
                                placeholder="Pilih Keselamatan" :optionValue="'value'" style="width: 100%;" :filter="true"
                                appendTo="body" showClear @change="getJenisKeselamatan(item.keselamatanfk)" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <h1 style="font-weight: bold;" class="mb-2">Jenis Keselamatan</h1>
                    <VField>
                        <VControl icon="feather:search" class="prime-auto">
                            <Dropdown v-model="item.jeniskeselamatanfk" :options="d_JenisKeselamatan" :optionLabel="'label'"
                                placeholder="Pilih Jenis Keselamatan" :optionValue="'value'" style="width: 100%;" :filter="true" readonly
                                appendTo="body" showClear />
                        </VControl>
                    </VField>
                </div>
            </div>
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
import Dropdown from 'primevue/dropdown';
import TRiwayatOrderLab from './profile-pasien/t-riwayat-order-lab.vue'
import Checkbox from 'primevue/checkbox';
import RadioButton from 'primevue/radiobutton';
import InputSwitch from 'primevue/inputswitch';
import InputNumber from 'primevue/inputnumber';
import SelectButton from 'primevue/selectbutton';
import Fieldset from 'primevue/fieldset';
import sleep from '/@src/utils/sleep'
import $ from "jquery";


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
        selected: any
        type: any
        align: any
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
        selected: undefined,
        type: undefined,
        align: undefined,
    }
)

const isLoadingPasien: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)

}

const listChecked: any = ref([])
const selected_count = ref(0);
const selectedTabs: any = ref()

const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const pasien: any = ref({})
const d_Keselamatan: any = ref([])
const d_JenisKeselamatan: any = ref([])
const isLoading = ref(false)
const d_Pegawai = ref([])
const listRiwayat = ref([])
const route = useRoute()


const activeValue: any = ref(0)

const fetchDropdown = () => {
    useApi().get(`/pmkp/get-data-combo-pmkp`).then((response: any) => {
        d_Keselamatan.value = response.insidenkeselamtanpasien.map((e: any) => {
            return { label: e.keselamatan, value: e.id, other: e }
        })
        d_JenisKeselamatan.value = response.jeniskeselamatan.map((e: any) => {
            return { label: e.jeniskesalamatan, value: e.id }
        })
        console.log(d_Keselamatan)
    })
}

function simpan() {
    let object: any = {}
    let ID = ''

    // if (activeValue.value == 0) {
    object = input.value
    ID = input.value.id ? input.value.id : ''

    let sigCanvas = document.getElementById("markingsite");
    if (sigCanvas) {
        let context = sigCanvas.getContext("2d");
        const dataURL = sigCanvas.toDataURL();
        input.value.anatomiTubuh = dataURL
        object.anatomiTubuh = dataURL
    }

    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmenawal',
        'data': object
    }

    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const getJenisKeselamatan = (e:any)=>{
    console.log(e.other)
    // item.value.jeniskeselamatanfk = {}
}
function kembaliKeun() {
    window.history.back()
}

fetchDropdown()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';
</style>
