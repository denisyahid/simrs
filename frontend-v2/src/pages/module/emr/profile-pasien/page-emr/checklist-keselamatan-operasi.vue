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
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Rencana Tindakan </h1>
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
                <Fieldset :toggleable="true" legend="SIGN IN (Sebelum Pembiusan)">
                    <div class="column is-12">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Perawat RR dan Tim Anestesi </h1>

                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight:bold;">Konfirmasi </h1>
                        <div class="columns is-multiline pt-3 pl-3">

                            <div class="column is-3" v-for="(data, i) in konfirmasi">
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
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Jenis Operasi </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Jenis Operasi"
                                            v-model="input.jenisOperasi" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Lokasi Operasi </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Lokasi Operasi"
                                            v-model="input.lokasiOperasi" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12" v-for="(datas, i) in tandaOperasi">
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
                            <div class="column is-12" v-for="(datas, i) in riwayatAlergi">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                <div class="columns is-multiline">
                                    <div class="column is-4" v-for="(data, b) in datas.value">
                                        <VField v-if="data.type == 'checkBox'">
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[data.model + b]" :true-value="data.subTitle"
                                                    :label="data.subTitle" class="p-0" color="primary" square />
                                            </VControl>
                                        </VField>
                                        <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                            style="margin-left: -8rem;">
                                            <VControl raw subcontrol>
                                                <input v-model="input[data.model + b]" class="input p-0" />
                                            </VControl>
                                        </VField>
                                    </div>

                                </div>


                            </div>



                        </div>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight:bold;">Pemerikasaan Tanda Vital </h1>
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
                    </div>
                    <div class="column is-12" v-for="(datas, i) in resiko">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                        <div class="columns is-multiline">
                            <div class="column is-4" v-for="(data, b) in datas.value">
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
                    <div class="column is-12" v-for="(datas, i) in implant">
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
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Perawat RR </h1>
                                <VField>
                                    <VControl>
                                        <AutoComplete v-model="input.perawatRR" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Perawat Asisten Anestesi </h1>
                                <VField>
                                    <VControl>
                                        <AutoComplete v-model="input.perawatAnestesi" :suggestions="d_Pegawai"
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
                <Fieldset :toggleable="true" legend="TIME OUT (Setelah dibius sesaat sebelum sayatan kulit)">
                    <div class="column is-12">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Dokter bedah, anestesi, asisten, instrumen
                            menyebutkan namam dan perannya dan dipimpin oleh perawat asisten anestesi </h1>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Konfirmasi seluruh anggota tim telah
                            memperkenalkan nama dan perannya masing-masing (Operator, Anestesi, Asisten, Instrumen) </h1>
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Asisten I </h1>
                                <VField>
                                    <VControl>
                                        <AutoComplete v-model="input.asistenI" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Asisten II </h1>
                                <VField>
                                    <VControl>
                                        <AutoComplete v-model="input.asistenII" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Instrumen </h1>
                                <VField>
                                    <VControl>
                                        <AutoComplete v-model="input.instrumen" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari nama Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Konfirmasi secara Verbail </h1>
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Pasien </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Nama Pasien"
                                            v-model="input.namaPasien" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal Lahir </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Nama Pasien"
                                            v-model="input.tanggalLahirPasien" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Lokasi Operasi </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Lokasi Operasi"
                                            v-model="input.lokasiOperasi" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jenis Operasi </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Jenis Operasi"
                                            v-model="input.jenisOperasi" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jenis Implant </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Jenis Implant"
                                            v-model="input.jenisImplant" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-8">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Operator antisipasi kejadian
                                    kritis(rencana yang harus dilakukan, lama operasi, kehilangan darah) </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Antisipasi"
                                            v-model="input.antipasi" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-8">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Tim Anestesi (hal-hal khusus yang
                                    perlu diperhatikan) </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Hal Yang Perlu diperhatikan"
                                            v-model="input.halDiperhatikan" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-12 p-0" v-for="(data) in alkes">
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
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Antibiotika profilaksis yang diberikan </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Antibiotika"
                                            v-model="input.antibiotika" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Dosis </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Nama Pasien"
                                            v-model="input.dosis" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jam </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Jam"
                                            v-model="input.jamPemberian" />
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/checklist-keselamatan-operasi'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let konfirmasi = ref(EMR.konfirmasi())
let tandaOperasi = ref(EMR.tandaOperasi())
let riwayatAlergi = ref(EMR.riwayatAlergi())
let tandaVitalSI = ref(EMR.tandaVitalSI())
let resiko = ref(EMR.resiko())
let implant = ref(EMR.implant())
let alkes = ref(EMR.alkes())



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

const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {
    input.value.namaPasien = props.pasien.namapasien
    input.value.tanggalLahirPasien = props.pasien.tgllahir
}
setView()
setAutoFill()
loadRiwayat()
</script>
