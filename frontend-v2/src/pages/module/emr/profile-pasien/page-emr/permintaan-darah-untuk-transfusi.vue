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
            <div class="column is-12" v-for="(datas) in dataRS">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column is-3" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'checkBox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle" :label="data.subTitle"
                                    class="p-0" color="primary" square />
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
        </VCard>
    </div>
    <div class="column is-12">
        <VCard>
            <div class="column is-12" v-for="(datas) in dataPasien">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column is-3" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'checkBox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle" :label="data.subTitle"
                                    class="p-0" color="primary" square />
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
        </VCard>
    </div>
    <div class="column is-12">
        <VCard>
            <h1 style="font-weight: bold">3. Jenis dan jumlah darah yang dibutuhkan serta waktu yang diinginkan : </h1>
            <div class="columns is-multiline">
                <table class="trs" style="width: 90%; margin-top: 2rem; text-align: scenter">
                    <!-- <tr>
                        <th colspan="5" style="text-align : center">  </th>
                    </tr> -->
                    <tr>
                        <th style="text-align : center"> No </th>
                        <th style="text-align : center"> Jenis Darah </th>
                        <th style="text-align: center"> Gol Darah dan rhesus </th>
                        <th style="text-align: center"> Jumlah Unit/ml </th>
                        <th style="text-align: center"> Tanggal </th>
                    </tr>
                    <tr v-for="(datas) in jenis">
                        <td>{{ datas.no }}</td>
                        <td>{{ datas.Kriteria }}</td>
                        <td>
                            <div class="columns is 12">
                                <div class="column" v-for="(dot) in datas.keterangan">
                                    <VField v-if="dot.type == 'checkBox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[dot.model]" :true-value="dot.title" :label="dot.title"
                                                color="primary" square />
                                        </VControl>
                                    </VField>

                                    <VField v-else="dot.type == 'textBox'">
                                        <VControl raw subcontrol>
                                            <input v-model="input[dot.model]" class="input p-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                        </td>
                        <td>
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField v-for="(check) in datas.pilihan">
                                        <VControl raw subcontrol>
                                            <input v-model="input[check.model]" class="input p-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VField v-for="(cik) in datas.waktu">
                                        <VDatePicker v-model="input[cik.model]" mode="dateTime" style="width: 100%"
                                            trim-weeks :max-date="new Date()">
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
                            </div>
                        </td>

                    </tr>
                </table>

            </div>
        </VCard>
    </div>
    <div class="column is-4 mt-3" style="margin-left: auto;">
        <VCard class="border-card pink">
            <div class="column is-9">
                <VField>
                    <h1 style="font-weight: bold;">Garut , tanggal dan jam</h1>
                </VField>
                <VField>
                    <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                            <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="" v-on="inputEvents" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </VField>
            </div>
            <div class="column is-6">
                <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
            </div>
            <div class="column">
                
                <h1 style="font-weight: bold;">Dokter</h1>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                        <AutoComplete v-model="input.penangungJawab" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik nama petugas" />
                    </VControl>
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
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import * as EMR from '../page-emr-plugins/permintaan-darah-untuk-transfusi'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let dataRS = ref(EMR.dataRS())
let dataPasien = ref(EMR.dataPasien())
let jenis = ref(EMR.jenis())
let lainnya = ref(EMR.lainnya())

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
const d_Petugas: any = ref([])
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
    tanggal: new Date(),
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
}
const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
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
    input.value.namaPasien = props.pasien.namapasien
    input.value.jenisKelaminPasien = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.tanggalLahirPasien = props.pasien.tgllahir
    input.value.alamatPasien = props.pasien.alamatlengkap
    input.value.noidentitas = props.pasien.noidentitas
    input.value.umurPasien = props.pasien.umur
}
setView()
setAutoFill()
loadRiwayat()
</script>
<style lang="scss">
#signature {
    border: double 3px transparent;
    border-radius: 5px;
    background-image: linear-gradient(white, white),
        radial-gradient(circle at top left, #4bc5e8, #9f6274);
    background-origin: border-box;
    background-clip: content-box, border-box;
}

.container {
    width: "100%";
    padding: 8px 16px;
}

.p-fieldsets .p-fieldset-content {
    background: #ffffff;
}

table.trs {
    border-collapse: collapse;
    width: 100%;
}

table.trs,
.trs th,
.trs td {
    border: 0.5px solid grey;
}


.trs th,
.trs td {
    padding: 8px;
    vertical-align: middle !important;
}
</style>
