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
                    <div class="column is-5">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosis </h1>
                        <VField>
                            <VControl>
                                <VTextarea rows="2" placeholder="Tulis Catatan..." v-model="input.diagnosis"></VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-5">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Program Rehabilitasi Medik </h1>
                        <VField>
                            <VControl>
                                <VTextarea rows="2" placeholder="Tulis Catatan..." v-model="input.programRehabilitas">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <table class="tg">
                    <thead>
                        <tr>
                            <td class="tg-0lax text-center" rowspan="2">Program</td>
                            <td class="tg-0lax text-center" rowspan="2">Tanggal</td>
                            <td class="tg-0lax text-center" colspan="4">Tanda Tangan</td>

                        </tr>
                        <tr>
                            <td class="tg-0lax" style="text-align: center;">Nama Pasien </td>
                            <td class="tg-0lax" style="text-align: center">Dokter </td>
                            <td class="tg-0lax" style="text-align: center">Perawat </td>
                            <td class="tg-0lax">#</td>

                        </tr>
                    </thead>
                    <tbody v-for="(item, index) in input.details" :key="index">

                        <tr>
                            <td style="width:20%;vertical-align: middle;">
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="item.namaProgram" placeholder="" />
                                    </VControl>
                                </VField>
                            </td>
                            <td style="width:20%;vertical-align: middle;">
                                <VField>
                                    <VControl class="prime-auto">
                                        <Calendar v-model="item.waktuPemeriksaan" selectionMode="single"
                                            :manualInput="false" class="w-100" :showIcon="true" showTime hourFormat="24"
                                            :date-format="'yy-mm-dd'" />
                                    </VControl>
                                </VField>
                            </td>


                            <td style="width:20%">
                                <div class="column is12">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" v-model="item.namaPasien" placeholder="" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column p-0 mt-3" style="text-align: center;">
                                    <TandaTangan :elemenID="'signature1_' + [index]" :width="'150'" :height="'150'"
                                        class="dek" />

                                </div>


                            </td>

                            <td style="width:20%">
                                <div class="column is12">
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="item.dokterPemeriksa" :suggestions="d_Dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="Cari Dokter Pemeriksa..." class="mt-2" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column p-0 mt-3" style="text-align: center;">
                                    <TandaTangan :elemenID="'signature2_' + [index]" :width="'150'" :height="'150'"
                                        class="dek" />
                                    <!-- <TandaTangan :elemenID="'signature'[index]" :width="'150'" :height="'150'" /> -->
                                </div>
                            </td>
                            <td style="width:20%">
                                <div class="column is12">
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="item.perawat" :suggestions="d_Dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="Cari Dokter Pemeriksa..." class="mt-2" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column p-0 mt-3" style="text-align: center;">
                                    <TandaTangan :elemenID="'signature3_' + [index]" :width="'150'" :height="'150'"
                                        class="dek" />
                                    <!-- <TandaTangan :elemenID="'signature'[index]" :width="'150'" :height="'150'" /> -->
                                </div>
                            </td>
                            <td rowspan="2" style="width:15%;vertical-align: middle;">
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
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import Calendar from 'primevue/calendar';


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
const d_Dokter: any = ref([])
const dataTTD: any = ref([])
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
const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
    dataTTD.value.forEach((element: any, i: any) => {
        H.tandaTangan().set("signature1_" + [i], element.ttd)
        H.tandaTangan().set("signature2_" + [i], element.ttd1)
        H.tandaTangan().set("signature2_" + [i], element.ttd2)
    })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
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
    json.data.details.forEach((element: any, i: any) => {
        element.ttd = H.tandaTangan().get("signature1_" + [i])
        element.ttd1 = H.tandaTangan().get("signature2_" + [i])
        element.ttd2 = H.tandaTangan().get("signature3_" + [i])

    }
    );
    console.log(json.data.details)
    debugger

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
const setAutoFill = async () => {
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
