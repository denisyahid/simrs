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
        <VCard>
            <div class="column is-12">
                <div class="columns is-multiline">

                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosa </h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.diagnosa" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Diagnosa" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tindakan Operasi</h1>
                        <VField>
                            <VControl>
                                <VInput type="text" class="input" placeholder="" v-model="input.tindakanOperasi" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-8">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Implant yang dipasang (jumlah, jenis,
                            kode/stiker wajib dicantumkan)</h1>
                        <VField>
                            <VControl>
                                <VInput type="text" class="input" placeholder="" v-model="input.implantPasang" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-5" style=" margin-top: 0.5rem">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal Pasang Implant</h1>
                        <VField>
                            <VDatePicker v-model="input.tanggalPasang" mode="dateTime" style="width: 100%" trim-weeks
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
                    <div class="column is-5" style=" margin-top: 0.5rem">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal Cabut Implant</h1>
                        <VField>
                            <VDatePicker v-model="input.tanggalCabut" mode="dateTime" style="width: 100%" trim-weeks
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
            <div class="column is-12">
                <table class="tg">
                    <thead>

                        <tr>
                            <td class="tg-0lax text-center" style="font">Jenis Implant</td>
                            <td class="tg-0lax">Kode / Stiker </td>
                            <td class="tg-0lax">Keterangan </td>
                            <td class="tg-0lax">#</td>

                        </tr>
                    </thead>
                    <tbody v-for="(item, index) in input.details" :key="index">
                        <tr>
                            <td>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="item.jenisImplant" placeholder="" />
                                    </VControl>
                                </VField>
                            </td>

                            <td style="width:20%">
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="item.kode" placeholder="" />
                                    </VControl>
                                </VField>
                            </td>

                            <td style="width:25%">
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="item.keterangan" placeholder="" />
                                    </VControl>
                                </VField>
                            </td>
                            <td rowspan="2" style="width:13%;vertical-align: middle;">
                                <div class="columns is-multiline">
                                    <div class="column is-6">
                                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                                            color="info" v-tooltip.bubble="'Tambah '">
                                        </VIconButton>
                                    </div>
                                    <div class="column is-6 ml-3-min">
                                        <VIconButton v-if="index > 0" type="button" raised circle icon="feather:trash"
                                            @click="removeItem(index)" color="danger">
                                        </VIconButton>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="column is-12">
                <h1 style="font-weight : bold;"> Instruksi dan Edukasi (Tindak Lanjut)</h1>
                <h1>1. Kontrol sesuai instruksi dokter bedah</h1>
                <h1>2. Laporkan jika ada masalah terkait pemasangan implant</h1>
            </div>
            <div class="column is-4" style=" margin-top: 0.5rem">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Bandung,</h1>
                        <VField>
                            <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
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
        </VCard>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_Diagnosa: any = ref([])
const input: any = ref({
    tanggal: new Date(),
    tanggalPasang: new Date(),
    tanggalCabut: new Date(),
    details: [{
        no: 1,
    }]
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
}
const loadRiwayat = () => {
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
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
setAutoFill()
loadRiwayat()
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
