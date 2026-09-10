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
                            <VButton type="button" rounded outlined :disabled="!NOREC_EMRPASIEN ? true : false"
                                color="warning" raised icon="lnir lnir-printer" @click="print()"> Cetak
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
            <VCard class="border-card pink">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">Yth :</h1>
                        <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                <AutoComplete v-model="input.dokter" :suggestions="d_dokter" @complete="fetchDokter($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6" style="margin-left: 3rem;">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">
                            Mohon perawatan pasien selanjutnya di ruangan
                        </h1>
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="input.namaRuangan" placeholder="" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold; margin-bottom: 1rem; ">
                        Keluhan Saat Ini
                    </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.Keluhan" rows="2">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold; margin-bottom: 1rem; ">
                        Pemeriksaan Penunjang
                    </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.pemeriksaanPenunjang" rows="2">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">
                        Diagnosa
                    </h1>
                    <VField>
                        <VControl>
                            <VInput type="text" v-model="input.namadiagnosa" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold; margin-bottom: 1rem; ">
                        Tindakan yang dilakukan di ruangan
                    </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.tindakan" rows="2">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">
                        Terapi / pengobatan yang diberikan
                    </h1>
                    <table class="tg">
                        <thead>
                            <tr>
                                <td class="tg-0lax">Nama Obat </td>
                                <td class="tg-0lax">Aturan Pakai</td>
                                <td class="tg-0lax">Rute Pemberian</td>
                                <td class="tg-0lax">#</td>

                            </tr>
                        </thead>
                        <tbody v-for="(item, index) in input.details" :key="index">
                            <tr>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" v-model="item.namaObat" placeholder="Nama Obat" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" v-model="item.aturanPakai" placeholder="Aturan Pakai" />
                                        </VControl>
                                    </VField>
                                </td>

                                <td style="width:25%">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" v-model="item.rutePemberian" placeholder="Rute Pemberian" />
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
                <div class="column is-8">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">
                        Alasan Masuk ICU
                    </h1>
                    <VField>
                        <VControl>
                            <VInput type="text" v-model="input.alasanMasukICU" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="colum is-12">
                    <h1 style="font-weight: bold; ">
                        PRIORITAS I
                    </h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in Prioritas1">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">
                        Alasan Masuk HCU
                    </h1>
                    <VField>
                        <VControl>
                            <VInput type="text" v-model="input.alasanMasukHCU" placeholder="" />
                        </VControl>
                    </VField>
                </div>
                <div class="colum is-12 pt-1">
                    <h1 style="font-weight: bold; ">
                        PRIORITAS II
                    </h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in Prioritas2">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="colum is-12 pt-1">
                    <h1 style="font-weight: bold; ">
                        PRIORITAS III
                    </h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in Prioritas3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </VCard>
            <VCard class="border-card pink" style="margin-top: 2rem;">
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">
                                Dokter Pengirim
                            </h1>
                            <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.dokterPengirim" :suggestions="d_dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="ketik untuk mencari..." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">
                                DPJP
                            </h1>
                            <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.dokterDPJP" :suggestions="d_dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" disabled :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="ketik untuk mencari..." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">
                                Petugas Penerima
                            </h1>
                            <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.petugasPE" :suggestions="d_dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="ketik untuk mencari..." />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </Vcard>
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
import AutoComplete from 'primevue/autocomplete';
import * as EMR from '../page-emr-plugins/asesmen-masuk-care-unit'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let Prioritas1 = ref(EMR.Prioritas1())
let Prioritas2 = ref(EMR.Prioritas2())
let Prioritas3 = ref(EMR.Prioritas3())

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
const COLLECTION: any = ref('AsesmenPasienMasukCareUnit') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_dokter: any = ref([])
const input: any = ref({
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
    input.value.namaRuangan = props.registrasi.namaruangan
    input.value.dokterDPJP = props.registrasi.dokter
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
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_dokter.value = response
}

const diagnosa = async () => {
    await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
        input.value.namadiagnosa = response[0].namadiagnosa
    })
}
const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
    });
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}

const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}`)
}


diagnosa()
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