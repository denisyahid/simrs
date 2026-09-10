<style lang="scss">
h1 {
    font-weight: bold !important
}
</style>
<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Slip Admission</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true" isHideST></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0" style="font-size: large;font-weight: bold;">
                            I. Waktu
                        </div>
                        <div class="column is-6">
                            <h2>Hari/Tgl/Jam</h2>
                            <VDatePicker v-model="input.DTWaktu" mode="datetime" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-6">
                            <h2>Dari</h2>
                            <Multiselect v-model="input.SWaktu" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_dari" :searchable="true" track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                            <VControl class="prime-auto" v-if="input.SWaktu == 2">
                                <AutoComplete v-model="input.DDPoliklinik" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" placeholder="Ketika nama ruangan..." />
                            </VControl>
                        </div>
                    </div>

                    <div class="columns is-multiline">
                        <div class="column is-12 pt-0 pb-0" style="font-size: large;font-weight: bold;">
                            II. Identitas Pasien
                        </div>
                        <div class="column is-3">
                            <h2>Nama Pasien</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h2>No RM</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNoRM" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <h2>Umur/Tgl. Lahir</h2>
                            <div class="columns">
                                <div class="column is-6">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBUmur" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VDatePicker v-model="input.DTglLahir" mode="date" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3">
                            <h2>Jenis Kelamin</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBJenisKelamin" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h2>HP/No. Telp</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNomorHP" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h2>Penanggung Jawab</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBPenanggungJawab" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h2>No.KTP/SIM/Paspor</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNoKTP" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <h2>Alamat</h2>
                            <VField>
                                <VTextarea rows="2" v-model="input.TAAlamat"></VTextarea>
                            </VField>
                        </div>
                    </div>

                    <div class="columns is-multiline">
                        <div class="column is-12 pt-0 pb-0" style="font-size: large;font-weight: bold;">
                            III. Diagnosa
                        </div>
                        <div class="column is-3">
                            <h2>Plan Of Action</h2>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="MRS" label="MRS"
                                            v-model="input.CBMRS_POA" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Operasi"
                                            label="Operasi" v-model="input.CBOperasi_POA" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Persalinan"
                                            label="Persalinan" v-model="input.CBPersalinan_POA" />
                                    </VControl>
                                </div>
                                <div class="column is-6 pt-0">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="ODC" label="ODC"
                                            v-model="input.CBODC_POA" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3">
                            <h2>Dokter Yang Merawat</h2>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.DDDokterMerawat" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h2>Cara Pembayaran</h2>
                            <Multiselect v-model="input.SCaraPembayaran" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_CP" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                            <VControl v-if="input.SCaraPembayaran == 3">
                                <VInput type="text" class="input" v-model="input.TBAsuransiLainnya"
                                    placeholder="Asuransi..." />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h2>Hak Perawatan Di Kelas</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBHakPerawatan" />
                            </VControl>
                        </div>
                    </div>

                    <div class="columns is-multiline">
                        <div class="column is-12 pt-0 pb-0" style="font-size: large;font-weight: bold;">
                            IV. Permintaan Kamar / TT Rawat Inap
                        </div>
                        <div class="column is-3">
                            <h2>Kelas Perawatan</h2>
                            <Multiselect v-model="input.SKelasPerawatan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_KP" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                            <VControl v-if="input.SKelasPerawatan == 7" class="prime-auto">
                                <AutoComplete v-model="input.DDRuangIntensive" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" placeholder="Ketika nama ruangan..." />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h2>Konfirmasi Tempat</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBKonfirmasiTempat" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h2>Petugas Ruangan</h2>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.DDPetugasRuangan" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Ketika nama petugas..." />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h2>Ketersediaan Tempat</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBKetersediaanTempat" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h2>Situasi Kamar</h2>
                            <Multiselect v-model="input.SSituasiKamar" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_SK" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3">
                            <h2>Kesiapan Kamar</h2>
                            <Multiselect v-model="input.SKesiapanKamar" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_KK" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-6">
                            <h2>Rencana Tindakan</h2>
                            <VField>
                                <VTextarea rows="2" v-model="input.TARencanaTindakan"></VTextarea>
                            </VField>
                        </div>
                    </div>

                    <div class="columns is-multiline">
                        <div class="column is-12 pt-0 pb-0" style="font-size: large;font-weight: bold;">
                            V. Estimasi Biaya
                        </div>
                        <div class="column is-3">
                            <h2>Estimasi Biaya</h2>
                            <Multiselect v-model="input.SEB" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_EB" :searchable="true" track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3">
                            <h2>Informasi General Consent</h2>
                            <Multiselect v-model="input.SCIGC" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_IGC" :searchable="true" track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-6">
                            <h2>Informasi Cara Pembayaran & Perlengkapan/Persyaratan</h2>
                            <Multiselect v-model="input.SICP" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_ICP" :searchable="true" track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                    </div>

                    <div class="columns is-multiline">
                        <div class="column is-12 pt-0 pb-0" style="font-size: large;font-weight: bold;">
                            VI. Rekam Medik Lama / Terdahulu
                        </div>
                        <div class="column is-3">
                            <Multiselect v-model="input.SRML" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_RML" :searchable="true" track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                    </div>

                    <div class="columns is-multiline">
                        <div class="column is-12 pt-0 pb-0" style="font-size: large;font-weight: bold;">
                            VII. Pengantar List & Pasien oleh
                        </div>
                        <div class="column is-3">
                            <Multiselect v-model="input.SPL" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_PL" :searchable="true" track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                    <div class="columns">
                        <div class="column is-4" style="text-align: center;">
                            <h1>Pasien / Keluarga</h1>
                            <TandaTangan :elemenID="'TTDPasien'" :width="'150'" :height="'150'" class="dek" />
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBPasien_ttd" />
                            </VControl>
                        </div>
                        <div class="column is-4" style="text-align: center;">
                            <h1>Petugas Ruangan Rawat Inap</h1>
                            <TandaTangan :elemenID="'TTDRI'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.DDPetugasRI" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Ketika nama petugas..." />
                            </VControl>
                        </div>
                        <div class="column is-4" style="text-align: center;">
                            <h1>Petugas Admission</h1>
                            <TandaTangan :elemenID="'TTDAdmission'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.DDAdmission" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Ketika nama admission..." />
                            </VControl>
                        </div>
                    </div>

                </div>
                <!-- form baru -->
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
// import InputText from 'primevue/inputtext';

useHead({ title: 'Slip Admission - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('SlipAdmission') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({})
const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
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
function calculateAge(birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    return age;
}
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        H.tandaTangan().set("TTDPasien", response[0].TTDPasien)
        H.tandaTangan().set("TTDRI", response[0].TTDRI)
        H.tandaTangan().set("TTDAdmission", response[0].TTDAdmission)
    } else {
        let data = input.value
        data.DDAdmission = { label: user.namaLengkap, value: user.id }
        data.DTWaktu = new Date()
        data.TBNamaPasien = props.pasien.namapasien
        data.TBNoRM = props.pasien.nocm
        data.TBUmur = calculateAge(props.pasien.tgllahir)
        data.DTglLahir = props.pasien.tgllahir
        data.TAAlamat = props.pasien.alamatlengkap
        data.TBJenisKelamin = props.pasien.jeniskelamin
        data.TBNoKTP = props.pasien.noidentitas
        data.TBNomorHP = props.pasien.nohp
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    object['TTDPasien'] = H.tandaTangan().get("TTDPasien");
    object['TTDRI'] = H.tandaTangan().get("TTDRI");
    object['TTDAdmission'] = H.tandaTangan().get("TTDAdmission");
    object.pasien = H.setObjectPasien(pasien.value)
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
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const fetchRuangan = async (filter: any) => {
    await useApi().get(`emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`).then((response) => { d_Ruangan.value = response })
}

const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => { d_Pegawai.value = response })
}

const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => { d_Dokter.value = response })
}

const d_dari: any = ref([
    { value: 0, label: 'IGD' },
    { value: 1, label: 'VK' },
    { value: 2, label: 'Poliklinik' }
]);
const d_CP: any = ref([
    { value: 0, label: 'Umum' },
    { value: 1, label: 'BPJS PBI' },
    { value: 2, label: 'BPJS non PBI' },
    { value: 3, label: 'Asuransi Lainnya' }
]);
const d_KP: any = ref([
    { value: 0, label: 'Non Kelas' },
    { value: 1, label: 'III' },
    { value: 2, label: 'II' },
    { value: 3, label: 'I' },
    { value: 4, label: 'VIP' },
    { value: 5, label: 'VVIP' },
    { value: 6, label: 'Suite' },
    { value: 7, label: 'R. Intensive' }
]);
const d_SK: any = ref([
    { value: 0, label: 'Siap' },
    { value: 1, label: 'Tidak' }
]);
const d_KK: any = ref([
    { value: 0, label: 'Bisa Dikirim' },
    { value: 1, label: 'Tidak' }
]);
const d_EB: any = ref([
    { value: 'Ada', label: 'Ada' },
    { value: 'Tidak', label: 'Tidak' }
]);
const d_IGC: any = ref([
    { value: 'Ya', label: 'Ya' },
    { value: 'Tidak', label: 'Tidak' }
]);
const d_ICP: any = ref([
    { value: 'Ya', label: 'Ya' },
    { value: 'Tidak', label: 'Tidak' }
]);
const d_RML: any = ref([
    { value: 'Ada', label: 'Ada' },
    { value: 'Tidak', label: 'Tidak' }
]);
const d_PL: any = ref([
    { value: 'IGD', label: 'IGD' },
    { value: 'VK', label: 'VK' },
    { value: 'Poli', label: 'Poli' },
    { value: 'Admission', label: 'Admission' }
]);


onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
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