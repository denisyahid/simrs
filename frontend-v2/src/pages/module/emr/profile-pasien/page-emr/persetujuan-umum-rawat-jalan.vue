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
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h1 class="mt-5 mb-1" style="font-weight: bold;">Saya yang bertanda tangan dibawah ini :</h1>
                        <div class="column is-12 p-0">
                            <div class="is-flex">
                                <div class="column is-3" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Nama : </h1>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Nama " v-model="input.nama" />
                                        </VControl>

                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 p-0">
                            <div class="is-flex">
                                <div class="column is-3" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Tanggal Lahir : </h1>
                                </div>
                                <div class="column is-6">
                                    <VDatePicker v-model="item.tgllahir" color="green" trim-weeks :input="'YYYY-MM-DD'"
                                        mode="date" :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar">
                                                    <VInput type="text" placeholder="Select a date" :value="inputValue"
                                                        v-on="inputEvents" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 p-0">
                            <div class="is-flex">
                                <div class="column is-3" style="margin-top:0.3rem">
                                    <h1 style="font-weight: bold;">Jenis Kelamin : </h1>
                                </div>
                                <div class="columns is-6" style="margin-top:.5rem;margin-left:0px">
                                    <VField v-for="items in JenisKelamin" :key="items.value" style="padding:0px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.jenisKelamin" class="pt-1 pb-1 "
                                                :true-value="items.value" :label="items.label" color="primary" square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 p-0">
                            <div class="is-flex">
                                <div class="column is-3" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;">Alamat : </h1>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                              <VTextarea rows="2" class="input" placeholder="Alamat"
                                                  v-model="input.alamat" ></VTextarea>
                                        </VControl>

                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 p-0">
                            <div class="is-flex">
                                <div class="column is-3" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;">Pekerjaan : </h1>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Pekerjaan"
                                                v-model="input.pekerjaan" />
                                        </VControl>

                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 p-0">
                            <div class="is-flex">
                                <div class="column is-3" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;">Hubungan dengan pasien : </h1>
                                </div>
                                <div class="columns is-6" style="margin-top:1.2rem;margin-left:0px">
                                    <VField v-for="item in hubunganPasien" :key="item.value" style="padding:0px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.hubunganPasien" class="pt-1 pb-1 "
                                                :true-value="item.value" :label="item.label" color="primary" square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="column is-12 ">
                            <h1 style="font-weight: bold">Hubungan dengan Pasien</h1>
                            <div class="columns is-multiline">
                                <div class="column is-2">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.merokok" true-value="Ya" label="Ya (Berapa pack/hari)"
                                                color="danger" circle />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div> -->

                        <div class="column is-12 mt-3">
                            <h1 style="font-weight: bold;">Dengan ini menyatakan bahwa : </h1>
                        </div>
                        <div class="column is-12 pb-0">
                            <h1>
                                1. Selama mendapatkan pelayanan di rawat jalan Rumah Sakit ini saya bersedia dilakukan pemeriksaan
                                dan tindakan medis, keperawatan serta pemeriksaan penunjang lainnya.
                            </h1>
                        </div>
                        <div class="column is-12 pb-0">
                            <h1>
                                2. Di unit pelayanan Rawat jalan Rumah Sakit ini, ada keterlibatan mahasiswa magang dalam memberikan
                                   pelayanan yang di dampingi oleh petugas Rumah Sakit baik dokter, perawat, bidan maupun tenaga lainnya.
                            </h1>
                        </div>
                        <div class="column is-12 pb-0">
                            <h1>
                                3. Selama mendapat pelayanan di rawat jalan Rumah Sakit ini jika saya memerlukan pelayanan tindakan medis
                                   invasif maka saya akan diberikan penjelasan oleh dokter sebelum pasien menyatakan persetujuannya untuk dilakukan tindakan tersebut (informed consent).
                            </h1>
                        </div>
                        <div class="column is-12 pb-0">
                            <h1>
                                4. Selama mendapat pelayanan rawat jalan di Rumah Sakit ini pasien tidak dianjurkan untuk membawa uang yang berlebihan, mengenakan barang
                                   berharga dalam jumlah yang berlebihan / banyak. Kehilangan atau kerusakan barang bukan merupakan tanggung jawab Rumah Sakit ini.
                            </h1>
                        </div>
                        <div class="column is-12">
                            <h1>
                                5. Pasien dan keluarga bersedia mengikuti peraturan dan tata tertib Rumah Sakit ini.
                            </h1>
                        </div>

                        <div class="column is-4">
                            <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
                            <VField class="mt-3">
                                <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
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
                        <div class="column is-12">
                            <div class="columns is-multiline">

                                <div class="column is-6">
                                    <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                                </div>
                                <div class="column is-6 ">
                                    <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                                </div>
                                <div class="column is-4">
                                    <h1 class="p-0" style="font-weight: bold;">Petugas Rumah Sakit </h1>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.petugasPerawat" :suggestions="d_Dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="Yang Menjelaskan..." class="mt-2"
                                                @item-select="setTandaTangan($event)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4 is-offset-2">
                                    <h1 class="p-0" style="font-weight: bold;">Pasien/Keluarga Pasien</h1>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Pasien/Penanggung Jawab Pasien"
                                                v-model="input.yangMembuatPernyataan" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
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
const d_ruangan: any = ref([])
const d_agama: any = ref([])
const d_Dokter: any = ref([])
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

// List Pilihan
const JenisKelamin: any = ref([
    { label: 'Laki - Laki', value: 'L' },
    { label: 'Perempuan', value: 'P' }
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
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Dokter.value = response
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
    input.value.jenisKelaminPasien = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.telepon = props.pasien.nohp
    input.value.alamatPasien = props.pasien.alamatlengkap
    input.value.dokterRawat = props.registrasi.dokter
    input.value.pihakPembayar = props.registrasi.kelompokpasien
    input.value.tglPembuatan = new Date()
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
