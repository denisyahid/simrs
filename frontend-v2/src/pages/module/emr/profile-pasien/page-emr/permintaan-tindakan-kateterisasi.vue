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
                        @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">

                    <table style="width: 100%;" border="1">
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Nama : </h1>
                                </div> 
                            </td>
                            <td colspan="2">
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Nama " v-model="input.namaPasien" />
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Tanggal Lahir : </h1>
                                </div>
                            </td>
                            <td>
                                <div class="column is-12">
                                    <VField>
                                        <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" />
                                                </VControl>
                                            </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </td>
                            <td>
                                <div class="column is-12">
                                    <div class="column is-6" style="margin-top:0.3rem">
                                        <h1 style="font-weight: bold;">Jenis Kelamin : </h1>
                                    </div>
                                    <div class="columns is-2" style="margin-top: 10px;">
                                        <VField v-for="items in JenisKelamin" :key="items.value">
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                                                :label="items.label" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> No telepon/HP(WA) : </h1>
                                </div> 
                            </td>
                            <td colspan="2">
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="No Telp " v-model="input.telepon" />
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Alamat</h1>
                                </div> 
                            </td>
                            <td colspan="2">
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="" v-model="input.alamatPasien" />
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Ruangan</h1>
                                </div> 
                            </td>
                            <td colspan="2">
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="" v-model="input.ruangan" />
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Diagnosa</h1>
                                </div> 
                            </td>
                            <td colspan="2">
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="" v-model="input.diagnosa" />
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Cara bayar</h1>
                                </div> 
                            </td>
                            <td colspan="2">
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="columns is-2" style="margin-top: 10px;">
                                            <VField v-for="items in Carabayar" :key="items.value">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input.carabayar" class="pt-1 pb-1" :true-value="items.label"
                                                    :label="items.label" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-2" >
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.carabayarlaintb" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                
                                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;">Permintaan rawat inap</h1>
                                </div> 
                            </td>
                            <td colspan="2">
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-2" >
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Suite"
                                                    label="Suite"
                                                    v-model="input.Suiteckb"
                                                />
                                            </VControl> 
                                        </div>
                                        <div class="column is-2" >
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="VVIP"
                                                    label="VVIP"
                                                    v-model="input.VVIPckb"
                                                />
                                            </VControl> 
                                        </div>
                                        <div class="column is-2" >
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="VIP"
                                                    label="VIP"
                                                    v-model="input.VIPsckb"
                                                />
                                            </VControl> 
                                        </div>
                                        <div class="column is-2" >
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="KlsI"
                                                    label="Kls I"
                                                    v-model="input.Kls1ckb"
                                                />
                                            </VControl> 
                                        </div>
                                        <div class="column is-2" >
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="KlsII"
                                                    label="Kls II"
                                                    v-model="input.Kls2ckb"
                                                />
                                            </VControl> 
                                        </div>
                                        <div class="column is-2" >
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="KlsIII"
                                                    label="Kls III"
                                                    v-model="input.Kls3ckb"
                                                />
                                            </VControl> 
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-2" >
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Intensif"
                                                    label="Intensif"
                                                    v-model="input.Intensifckb"
                                                />
                                            </VControl> 
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Jenis tindakan : </h1>
                                </div> 
                            </td>
                            <td colspan="2">
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="" v-model="input.jenisTindakan" />
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Jenis anastesi</h1>
                                </div> 
                            </td>
                            <td colspan="2">
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-2" >
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Lokal"
                                                    label="Lokal"
                                                    v-model="input.Lokalckb"
                                                />
                                            </VControl> 
                                        </div>
                                        <div class="column is-2" >
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="General"
                                                    label="General"
                                                    v-model="input.Generalckb"
                                                />
                                            </VControl> 
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;">Ruang cath lab</h1>
                                </div> 
                            </td>
                            <td colspan="2">
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-2" >
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="PJT"
                                                    label="PJT"
                                                    v-model="input.PJTckb"
                                                />
                                            </VControl> 
                                        </div>
                                        <div class="column is-2" >
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="ruanglain"
                                                    label="Ruang lainnya"
                                                    v-model="input.ruanglainckb"
                                                />
                                            </VControl> 
                                        </div>
                                        
                                        <div class="column is-2" >
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.Ruanglaintb" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Tanggal tindakan : </h1>
                                </div>
                            </td>
                            <td colspan="2">
                                <div class="column is-12">
                                    <VDatePicker v-model="input.tanggalTindakan" color="green" trim-weeks mode="dateTime"
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                                            <VField>
                                            <VControl icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                                class="is-rounded_Z" />
                                            </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Dokter Operator : </h1>
                                </div>
                            </td>
                            <td colspan="2">
                                <VField>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.dokterOperator" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Yang Menjelaskan..." class="mt-2"/>
                                    </VControl>
                                </VField>
                            </td>
                        </tr>
                    </table>
                    <div class="column is-6"></div>
                    <div class="column is-6 is-flex" style="justify-content: center;">
                    <VField>
                        <h1 style="font-weight: bold">Garut</h1>
                        <VDatePicker v-model="input.tanggalAdmission" color="green" trim-weeks mode="dateTime"
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                            <VControl icon="feather:calendar">
                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                class="is-rounded_Z" />
                            </VControl>
                            </VField>
                        </template>
                        </VDatePicker>
                    </VField>
                    </div>

                    <div class="column is-12 is-flex ml-5 justify-content-between">
                        <div class="column is-6" style="text-align: center">
                        <h1 style="font-weight: bold">Dokter pengirim,</h1>
                        <VField>
                        <VControl>
                            <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter"
                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                        </VControl>
                        </VField>
                    </div>

                    <div class="column is-6" style="text-align: center">
                        <h1 style="font-weight: bold">PJ ruangan</h1>
                        <VField>
                        <VControl>
                            <AutoComplete v-model="input.petugasAddmision" :suggestions="d_Pegawai"
                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                        </VControl>
                        </VField>
                    </div>
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
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/lembaran-penyiaran-radioterapi'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let JenisKelamin = ref(EMR.JenisKelamin())
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
const d_ruangan: any = ref([])
const d_agama: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const user = useUserSession().getUser().pegawai

// List Pilihan
const Carabayar: any = ref([
    { label: 'Umum', value: 'Umum' },
    { label: 'IKS', value: 'IKS' },
    { label: 'BPJS', value: 'BPJS' },
    { label: 'Lain-lain', value: 'Lain-lain' },
])
const hubunganPasien: any = ref([
    { label: 'Diri sendiri', value: 'Diri sendiri' },
    { label: 'Ayah', value: 'Ayah' },
    { label: 'Ibu', value: 'Ibu' },
    { label: 'Istri', value: 'Istri' },
    { label: 'Suami', value: 'Suami' },
    { label: 'Anak', value: 'Anak' },
])

const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (response[0].tandaTanganPerawat) {
                    H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
                }
                if (response[0].tandaTanganPasien) {
                    H.tandaTangan().set("signature_2", response[0].tandaTanganPasien)
                }
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}
const fetchAgama = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/agama_m?select=id,agama&param_search=agama&query=${filter.query}&limit=10`)
    d_agama.value = response
}
const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_ruangan.value = response
}
const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
        d_Pegawai.value = response
    })
}
const setTandaTangan = async (e: any) => {
    const response = await useApi().get(
        `/emr/tanda-tangan/${e.value.value}`)
    if (response != null) {
        H.tandaTangan().set("signature_1", response.ttd)
        input.value.tandaTanganPerawat = response.ttd
    } else {
        H.tandaTangan().set("signature_1", '')
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
    object.tandaTanganPasien = H.tandaTangan().get("signature_2")
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
    input.value.jeniskelamin = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.telepon = props.pasien.nohp
    input.value.alamatPasien = props.pasien.alamatlengkap
    input.value.dokterRawat = props.registrasi.dokter
    input.value.pihakPembayar = props.registrasi.kelompokpasien
    input.value.tanggalLahirPasien = props.pasien.tgllahir
    input.value.ruangan = props.registrasi.namaruangan
    input.value.carabayar = props.registrasi.carabayar
    input.value.tanggalAdmission = new Date()
    input.value.tglPembuatan = new Date()
    input.value.tanggalTindakan = new Date()
    input.value.DDDokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
    input.value.petugasAddmision = { label: user.namaLengkap, value: user.id }
    const response_AsmedRajal = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenMedisRawatJalan" + `&field=TADiagnosa`)
    if (response_AsmedRajal != null) {
        input.value.diagnosa = response_AsmedRajal.TADiagnosa
    }
}

setView()
setAutoFill()
loadRiwayat()
</script>
<style>
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

.buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin-top: 8px;
}
</style>
