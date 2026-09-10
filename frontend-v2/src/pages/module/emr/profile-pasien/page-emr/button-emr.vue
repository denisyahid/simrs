<template>
    <div class="buttons">
        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
            Kembali
        </VButton>
        <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
            @click="print()"> Cetak
        </VButton>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
            :loading="isLoading" @click="simpan()"> Simpan
        </VButton>
    </div>
</template>
                        

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as H from '/@src/utils/appHelper'
import Slider from 'primevue/slider';
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import * as EMR from '../page-emr-plugins/asesmen-medis-igd'


useHead({
    title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let anamnesa = ref(EMR.anamnesa())
let listFaktorResJantung = ref(EMR.listFaktorResJantung())
let keadaanUmum = ref(EMR.keadaanUmum())
let keadaanUmum2 = ref(EMR.keadaanUmum2())
let pemeriksaanFisik = ref(EMR.pemeriksaanFisik())
let kesadaran = ref(EMR.kesadaran())
let descRangeKesadaran = ref(EMR.descRangeKesadaran())
let Pragnosis = ref(EMR.Pragnosis())
let pemeriksaanPenunjang = ref(EMR.pemeriksaanPenunjang())
let Edukasi = ref(EMR.Edukasi())
let pilihanRencana = ref(EMR.pilihanRencana())
let keadaanPasien = ref(EMR.keadaanPasien())
const isLoading = ref(false)

const print = async () => {
    H.printBlade(`emr/cetak-asesmen-medis-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const simpan = () => {

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
    // console.log(json)

    // // isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            // NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
        })

    // console.log(resultValue)
}

const kembaliKeun = () => {
    window.history.back()
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



</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

.p-fieldset-legend {
    margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
    background: none;
}

.checkbox.is-outlined {
    padding: unset !important;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

h1.emr {
    font-weight: bold;
}

table.assesment {
    border-collapse: collapse;
    width: 100%;
}


.assesment th {
    text-align: center !important;
    border-bottom: 1px solid black;
    // border: 1px solid black;
}


.assesment th,
td {
    padding: 8px;
    vertical-align: middle !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}
</style>
