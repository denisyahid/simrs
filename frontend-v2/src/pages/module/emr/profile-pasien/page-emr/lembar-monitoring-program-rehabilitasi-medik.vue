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
                            <VButton type="button" rounded outlined :disabled="!NOREC_EMRPASIEN ? true : false" color="warning" raised icon="lnir lnir-printer"
                                @click="print()"> Cetak
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
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-12">

                        <table border="1px">
                            <thead>
                                <tr>
                                    <th rowspan="2"  width="300px">Tujuan Layanan</th>
                                    <th rowspan="2" width="100px">Kunjungan per minggu</th>
                                    <th colspan="5">Jadwal Terapi</th>
                                    <th rowspan="2" width="200px">Keterangan</th>
                                </tr>
                                <tr>
                                    <th>Senin</th>
                                    <th>Selasa</th>
                                    <th>Rabu</th>
                                    <th>Kamis</th>
                                    <th>Jumat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(datas,i) in jadwalTerapi">
                                    <td>{{ datas.layanan }}</td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input[datas.kunjungan+i]"/>
                                            </VControl>
                                        </VField>
                                    </td>
                                    <td width="210px" v-for="(data) in datas.hari">
                                        <div class="column p-0">
                                            <div class="columns">
                                                <div class="column is-1 m-3">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.modelCek+i]" color="primary" class="p-0"
                                                                style="color:black;" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column">
                                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="feather:search">
                                                            <AutoComplete v-model="input[data.modelSelect+i]"
                                                            :suggestions="d_Pegawai"
                                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                              :field="'label'" placeholder="Cari komite medis" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input[datas.descrip+i]" />
                                            </VControl>
                                        </VField>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Evaluasi</td>
                                    <td colspan="7">
                                        <VField>
                                                <VDatePicker v-model="input.tglEvaluasi" mode="dateTime"
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
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
import AutoComplete from 'primevue/autocomplete';
import { useUserSession } from '/@src/stores/userSession'


// =================== Start DataSourceTable =====================

let jadwalTerapi = ([
    {
        "layanan" : "Tindakan Dokter",
        "kunjungan" : "kunjunganPerMinggu",
        "hari" : [
            {
                "modelCek" : "senin_",
                "value" : "senin",
                "modelSelect" : "senPegawai_"
            },
            {
                "modelCek" : "selasa__",
                "value" : "selasa",
                "modelSelect" : "selPegawai_"
            },
            {
                "modelCek" : "rabu_",
                "value" : "rabu",
                "modelSelect" : "rabPegawai_"
            },
            {
                "modelCek" : "kamis_",
                "kamis" : "kasmis",
                "modelSelect" : "kamPegawai_"
            },
            {
                "modelCek" : "jumat_",
                "value" : "jumat", 
                "modelSelect" : "jumPegawai_"
            },
        ],
        "descrip" : "keterangan_"
    },
    {
        "layanan" : "Tindakan Fisioterapi",
        "kunjungan" : "kunjunganPerMinggu",
        "hari" : [
            {
                "modelCek" : "senin_",
                "value" : "senin",
                "modelSelect" : "senPegawai_"
            },
            {
                "modelCek" : "selasa__",
                "value" : "selasa",
                "modelSelect" : "selPegawai_"
            },
            {
                "modelCek" : "rabu_",
                "value" : "rabu",
                "modelSelect" : "rabPegawai_"
            },
            {
                "modelCek" : "kamis_",
                "kamis" : "kasmis",
                "modelSelect" : "kamPegawai_"
            },
            {
                "modelCek" : "jumat_",
                "value" : "jumat", 
                "modelSelect" : "jumPegawai_"
            },
        ],
        "descrip" : "keterangan_",
    },
    {
        "layanan" : "Spesialis Lain / Pemeriksa Penunjang",
        "kunjungan" : "kunjunganPerMinggu",
        "hari" : [
            {
                "modelCek" : "senin_",
                "value" : "senin",
                "modelSelect" : "senPegawai_"
            },
            {
                "modelCek" : "selasa__",
                "value" : "selasa",
                "modelSelect" : "selPegawai_"
            },
            {
                "modelCek" : "rabu_",
                "value" : "rabu",
                "modelSelect" : "rabPegawai_"
            },
            {
                "modelCek" : "kamis_",
                "kamis" : "kasmis",
                "modelSelect" : "kamPegawai_"
            },
            {
                "modelCek" : "jumat_",
                "value" : "jumat", 
                "modelSelect" : "jumPegawai_"
            },
        ],
        "descrip" : "keterangan_",
    },
   
])




// =================== End DataSourceTable =====================

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

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Pegawai:any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('LembarMonitoringRehabilitasiMedik') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
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

const olah = ()=>{
    
    let objData = {
        'tindakanDokter' : {
            input
        }
    }
    console.log(objData)
}

const kembaliKeun = () => {
    window.history.back()
}

const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}

const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

setView()
loadRiwayat()
</script>

<style lang="scss">
table {
    border-collapse: collapse;
    width: 100%;
}

table,
thead,
tr,
th {
    text-align: center;
    vertical-align: top !important;
}

tbody,
tr,
td {
    padding: 5px !important;
}
</style>