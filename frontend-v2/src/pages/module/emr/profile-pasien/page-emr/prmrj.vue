<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Lembar Hasil Tindakan Uji Fungsi Rehabilitasi Medik</h3>
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
                <div class="form-body p-2">
                    <div class="business-dashboard hr-dashboard">
                        <div class="columns is-multiline">
                            <div class="column is-12" v-if="isLoadingPasien">
                                <PlaceloadHeader class="m-3" />
                            </div>
                            <div class="column is-12" v-if="!isLoadingPasien">
                                <div class="column is-12" style="overflow-x:auto;">
                                    <table width="150%" border="1px">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th width="10%">Tgl Berkunjung</th>
                                                <th width="15%">Poli</th>
                                                <th width="15%">Diagnosa</th>
                                                <th width="18%">Penatalaksaan</th>
                                                <th width="20%">Riwayat Rawat Inap / Prosedur Operasi</th>
                                                <th width="18%">Petugas Kesehatan</th>
                                                <th width="15%">#</th>
                                            </tr>
                                        </thead>
                                        <tbody v-for="(item, index) in input.details" :key="index">
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{ index + 1 }}
                                                </td>
                                                <td>
                                                    <div class="column pb-0">
                                                        <VField>
                                                            <VDatePicker v-model="item.tglBerkunjung" mode="dateTime"
                                                                style="width: 100%" trim-weeks :max-date="new Date()">
                                                                <template #default="{ inputValue, inputEvents }">
                                                                    <VField>
                                                                        <VControl icon="feather:calendar" fullwidth>
                                                                            <VInput :value="inputValue"
                                                                                placeholder="Tanggal" v-on="inputEvents" />
                                                                        </VControl>
                                                                    </VField>
                                                                </template>
                                                            </VDatePicker>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="column">
                                                        <VField>
                                                            <VControl class="prime-auto">
                                                                <AutoComplete v-model="item.poli" :suggestions="d_Poli"
                                                                    @complete="fetchPoli($event)" :optionLabel="'label'"
                                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                    placeholder="Cari Poli..." class="mt-2" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="column">
                                                        <VField>
                                                            <VControl class="prime-auto">
                                                                <AutoComplete v-model="item.diagnosa"
                                                                    :suggestions="d_Diagnosa"
                                                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'"
                                                                    :dropdown="true" :minLength="2" :appendTo="'body'"
                                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                    placeholder="Cari Diagnosa..." class="mt-2" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="column">
                                                        <VField>
                                                            <VControl>
                                                                <VTextarea v-model="item.penatalaksaan" rows="3"
                                                                    placeholder="Penatalaksaan ...">
                                                                </VTextarea>
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="column">
                                                        <VField>
                                                            <VControl>
                                                                <VTextarea v-model="item.prosedurOperasi" rows="3"
                                                                    placeholder="Riwayat Rawat Inap / Prosedur Operasi ...">
                                                                </VTextarea>
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="column">
                                                        <VField>
                                                            <VControl class="prime-auto">
                                                                <AutoComplete v-model="item.petugas"
                                                                    :suggestions="d_Petugas"
                                                                    @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                                    :dropdown="true" :minLength="2" :appendTo="'body'"
                                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                    placeholder="Cari Petugas..." class="mt-2" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="column">
                                                        <div class="column is-6">
                                                            <VIconButton type="button" raised circle icon="feather:plus"
                                                                @click="addNewItem()" color="info"
                                                                v-tooltip.bubble="'Tambah '">
                                                            </VIconButton>
                                                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised
                                                                circle icon="feather:trash" @click="removeItem(index)"
                                                                color="danger">
                                                            </VIconButton>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="column is-4" style="margin-left:auto ;">
                                    <VCard>
                                        <div class="column pt-0">
                                            <h1 class="mb-3" style="font-weight: bold;">Tanggal dan Jam</h1>
                                            <VField>
                                                <VDatePicker v-model="input.tglKomit" mode="dateTime"
                                                    style="width: 100%" trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" placeholder="Tanggal"
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                        </div>
                                        <div class="column is-12 pt-0">
                                            <h1 class="mb-1" style="font-weight: bold;">Petugas / Dokter</h1>
                                            <VField>
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="input.petugasOrDokter" :suggestions="d_Petugas"
                                                        @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="2" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="Cari Petugas / Dokter..." class="mt-2" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </VCard>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'

useHead({
    title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
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
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)
const pasien: any = ref({})
const isLoadingPasien: any = ref(false)
const items = ref([]);
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
    airway: [],
    disability: []

})

const COLLECTION: any = ref('PRMRJ') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    details: [{
        no: 1,
        tglBerkunjung: new Date(),
    }]
})
const value = ref("");
const d_Petugas: any = ref([])
const d_Poli: any = ref([])
const d_Diagnosa: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)


const getDokter = async (filter: any) => {
    console.log(filter)
    // let filter = input.value.filter ? `?filter=${input.value.filter}` : ''
    // await useApi().get(`/emr/get-list-dokter${filter}`).then((response) => {
    //     d_Dokter.value = response.map((e: any) => {
    //         return { label: e.namalengkap, value: e.namalengkap }
    //     })
    // })
}

const loadRiwayat = async () => {

    await useApi().get(
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

    let ID = input.id ? input.id : ''

    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm
    // console.log(object)
    // debugger
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
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })

    // console.log(resultValue)
}

const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tglBerkunjung: new Date(),
    });
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}

const kembaliKeun = () => {
    window.history.back()
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const fetchPoli = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Poli.value = response
}
const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
}
const fetchPetugas = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Petugas.value = response
}

const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}`)
}



fetchPasien()
loadRiwayat()

watch(() => [
    input.value.kesadaranE,
    input.value.kesadaranM,
    input.value.kesadaranV,
    input.value.totalKesadaran,
    // input.value.point2
], () => {
    let poin1 = input.value.kesadaranE ? parseInt(input.value.kesadaranE) : 0
    let poin2 = input.value.kesadaranM ? parseInt(input.value.kesadaranM) : 0
    let poin3 = input.value.kesadaranV ? parseInt(input.value.kesadaranV) : 0

    const jumlahNilai = poin1 + poin2 + poin3
    input.value.totalKesadaran = jumlahNilai
})

</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

th {
    text-align: center !important;
}

td {
    vertical-align: inherit !important;
}

// .v-avatar.is-medium.active {
//     padding: 3px;
//     background: var(--success);
//     display: inline-table !important;
// }

// .p-fieldset-legend {
//     margin-left: 14px;
// }

// .p-fieldset .p-fieldset-content {
//     background: none;
// }

// .checkbox.is-outlined {
//     padding: unset !important;
// }

// // .p-fieldset.p-component{
// //     border-left: ;
// // }

// h1.emr {
//     font-weight: bold;
// }

// table.prmrj {
//     border-collapse: collapse;
//     width: 100%;
//     border: 1px solid black;
// }


// .prmrj th,
// .prmrj td {
//     padding: 8px;
//     border: 1px solid black;
//     vertical-align: middle !important;
// }

// hr {
//     background-color: hsl(0deg 6.81% 88.68%);
//     // border: none;
//     display: block;
//     height: 2px;
//     margin: 1rem 0;
// }
</style>
