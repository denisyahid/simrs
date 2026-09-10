<template>
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
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <TabView>
                    <TabPanel header="Form A">

                        <div class="columns is-multiline">
                            <div class="column is-12">
                                Hasil Pemeriksaan, Analisa, Rencana Penatalaksanaan Pasien
                            </div>
                            <div class="column is-12">
                                <table class="tg">
                                    <thead>
                                        <tr>
                                            <td class="tg-0lax text-center" rowspan="2">Tanggal/Jam</td>
                                            <td class="tg-0lax text-center" colspan="4">Catatan Case Manager Form A</td>
                                            <td class="tg-0lax text-center" rowspan="2">Tanda Tangan</td>
                                            <td class="tg-0lax text-center" rowspan="2">#</td>
                                        </tr>
                                        <tr>
                                            <td class="tg-0lax">Identifikasi </td>
                                            <td class="tg-0lax">Assesmen </td>
                                            <td class="tg-0lax">Identifikasi Masalah</td>
                                            <td class="tg-0lax">Perencanaan</td>
                                        </tr>
                                    </thead>
                                    <tbody v-for="(item, index) in input.details_forma" :key="index">
                                        <tr>
                                            <td style="width:15%">
                                                <VField>
                                                    <VControl class="prime-auto">
                                                        <Calendar v-model="item.tgl" selectionMode="single"
                                                            :manualInput="false" class="w-100" :showIcon="true" showTime
                                                            hourFormat="24" :date-format="'yy-mm-dd'" />
                                                    </VControl>
                                                </VField>
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.Identifikasi" rows="5"
                                                            placeholder="Identifikasi...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.Assesmen" rows="5"
                                                            placeholder="Assesmen...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.IdentifikasiMasalah" rows="5"
                                                            placeholder="Identifikasi Masalah...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.Perencanaan" rows="5"
                                                            placeholder="Perencanaan...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>

                                            <td style="width:15%">
                                                <VField>
                                                    <VControl class="prime-auto">
                                                        <TandaTangan :elemenID="item.signature" :width="'180'"
                                                            :height="'180'"></TandaTangan>
                                                        <AutoComplete v-model="item.tandaTangan" :suggestions="d_Dokter"
                                                            @complete="fetchDokter($event)" :optionLabel="'label'"
                                                            :dropdown="true" :minLength="3" :appendTo="'body'"
                                                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            placeholder="Nama Pegawai..." class="mt-2" />
                                                    </VControl>

                                                </VField>
                                            </td>
                                            <td rowspan="2" style="width:7%;vertical-align: middle;">
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
                            </div>
                        </div>
                    </TabPanel>
                    <TabPanel header="Form B">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                Hasil Pemeriksaan, Analisa, Rencana Penatalaksanaan Pasien
                            </div>
                            <div class="column is-12">
                                <table class="tg">
                                    <thead>
                                        <tr>
                                            <td class="tg-0lax text-center" rowspan="4">Tanggal/Jam</td>
                                            <td class="tg-0lax text-center" colspan="3">Catatan Case Manager Form B</td>
                                            <td class="tg-0lax text-center" rowspan="4">Tanda Tangan</td>
                                            <td class="tg-0lax text-center" rowspan="4">#</td>
                                        </tr>
                                        <tr>
                                            <td class="tg-0lax">Pelaksanaan </td>
                                            <td class="tg-0lax">Monitoring </td>
                                            <td class="tg-0lax">Fasilitas, Koordinasi, Kolaborasi </td>
                                        </tr>
                                        <tr>

                                            <td class="tg-0lax">Advokasi</td>

                                            <td class="tg-0lax">Hasil Pelayanan</td>
                                            <td class="tg-0lax">Terminasi </td>
                                        </tr>
                                    </thead>
                                    <tbody v-for="(item, index) in input.details_formb" :key="index">
                                        <tr>
                                            <td style="width:15%">
                                                <VField>
                                                    <VControl class="prime-auto">
                                                        <Calendar v-model="item.tgl" selectionMode="single"
                                                            :manualInput="false" class="w-100" :showIcon="true" showTime
                                                            hourFormat="24" :date-format="'yy-mm-dd'" />
                                                    </VControl>
                                                </VField>
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.Pelaksanaan" rows="5"
                                                            placeholder="Pelaksanaan...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>

                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.Advokasi" rows="5"
                                                            placeholder="Advokasi...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>

                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.Monitoring" rows="5"
                                                            placeholder="Monitoring...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.Hasil" rows="5"
                                                            placeholder="Hasil Pelayanan ...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>

                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.Fasilitas" rows="5"
                                                            placeholder="Fasilitas, Koordinasi, Kolaborasi...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="item.Terminasi" rows="5"
                                                            placeholder="Terminasi...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>

                                            </td>

                                            <td style="width:15%">
                                                <VField>
                                                    <VControl class="prime-auto">
                                                        <TandaTangan :elemenID="item.signature" :width="'180'"
                                                            :height="'180'"></TandaTangan>
                                                        <AutoComplete v-model="item.tandaTangan" :suggestions="d_Dokter"
                                                            @complete="fetchDokter($event)" :optionLabel="'label'"
                                                            :dropdown="true" :minLength="3" :appendTo="'body'"
                                                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            placeholder="Nama Pegawai..." class="mt-2" />
                                                    </VControl>

                                                </VField>
                                            </td>
                                            <td rowspan="2" style="width:7%;vertical-align: middle;">
                                                <div class="columns is-multiline">
                                                    <div class="column is-6">
                                                        <VIconButton type="button" raised circle icon="feather:plus"
                                                            @click="addNewItemB()" color="info"
                                                            v-tooltip.bubble="'Tambah '">
                                                        </VIconButton>
                                                    </div>
                                                    <div class="column is-6 ml-3-min">
                                                        <VIconButton v-if="index > 0" type="button" raised circle
                                                            icon="feather:trash" @click="removeItemB(index)" color="danger">
                                                        </VIconButton>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </TabPanel>

                </TabView>

            </VCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import sleep from '/@src/utils/sleep'
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
const input: any = ref({
    details_forma: [{
        no: 1,
        tgl: new Date(),
        signature: 'signature_1'
    }],
    details_formb: [{
        no: 1,
        tgl: new Date(),
        signature: 'signatureb_1'
    }]
})
const d_Dokter: any = ref([])
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
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan 
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
                if (response[0].details_forma.length) {
                    for (let x = 0; x < response[0].details_forma.length; x++) {
                        const element = response[0].details_forma[x];
                        element.tgl = H.formatDate(element.tgl, 'YYYY-MM-DD HH:mm')
                        await sleep(1000)
                        H.tandaTangan().set(element.signature, element.tandaTanganCnvs)
                    }
                }

                if (response[0].details_formb.length) {
                    for (let x = 0; x < response[0].details_formb.length; x++) {
                        const element = response[0].details_formb[x];
                        element.tgl = H.formatDate(element.tgl, 'YYYY-MM-DD HH:mm')
                        await sleep(1000)
                        H.tandaTangan().set(element.signature, element.tandaTanganCnvs)
                    }
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
    for (let x = 0; x < object.details_forma.length; x++) {
        const element = object.details_forma[x];
        element.tandaTanganCnvs = H.tandaTangan().get(element.signature)

    }
    for (let x = 0; x < object.details_formb.length; x++) {
        const element = object.details_formb[x];
        element.tandaTanganCnvs = H.tandaTangan().get(element.signature)

    }
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
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Dokter.value = response
}

const addNewItem = () => {
    // if (listItem.value[listItem.value.length - 1].tgl == null) {
    //     H.alert('error', 'Tanggal di pilih')
    //     return
    // }
    input.value.details_forma.push({
        no: input.value.details_forma[input.value.details_forma.length - 1].no + 1,
        tgl: new Date(),
        signature: 'signature_' + (input.value.details_forma[input.value.details_forma.length - 1].no + 1),
    });
}
const removeItem = (index: any) => {
    input.value.details_forma.splice(index, 1)
}
const addNewItemB = () => {
    // if (listItem.value[listItem.value.length - 1].tgl == null) {
    //     H.alert('error', 'Tanggal di pilih')
    //     return
    // }
    input.value.details_formb.push({
        no: input.value.details_formb[input.value.details_formb.length - 1].no + 1,
        tgl: new Date(),
        signature: 'signatureb_' + (input.value.details_formb[input.value.details_formb.length - 1].no + 1),
    });
}
const removeItemB = (index: any) => {
    input.value.details_formb.splice(index, 1)
}


setView()
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