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
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-2" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> I. Diagnosa Masuk </h1>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="" v-model="input.diagnosismasuk" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl>
                                    <AutoComplete v-model="input.kddiagnosismasuk" :suggestions="d_Diagnosa"
                                        @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Diagnosa" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-2" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> II. Diagnosa Utama </h1>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="" v-model="input.diagnosisawal" />
                                </VControl>

                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl>
                                    <AutoComplete v-model="input.kddiagnosisawal" :suggestions="d_Diagnosa"
                                        @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Diagnosa" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-2" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> III. Diagnosa Tambahan </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <AutoComplete v-model="input.namadiagnosatambahan" :suggestions="d_Diagnosa"
                                        @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari Diagnosa" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold;margin-bottom: 0.5rem;"> IV. Tindakan Prosedur </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.tindakanprosedur" rows="2">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold;margin-bottom: 0.5rem;"> V. Alasan Dirawat </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.alasandirawat" rows="2">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold;margin-bottom: 0.5rem;"> VI. Ringkasan Penyakit </h1>
                    <div class="column is-12 p-0">
                        <div class="is-flex">
                            <div class="column is-2" style="margin-top:0.5rem">
                                <h1 style="font-weight: bold;"> Riwayat Penyakit Sekarang </h1>
                            </div>
                            <div class="column is-6">
                                <VField>
                                    <VControl>
                                        <VTextarea v-model="input.ringkasanriwayatpenyakit" rows="2">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12 p-0">
                        <div class="is-flex">
                            <div class="column is-2" style="margin-top:0.5rem">
                                <h1 style="font-weight: bold;"> Pemeriksaan Fisik </h1>
                            </div>
                            <div class="column is-6">
                                <VField>
                                    <VControl>
                                        <VTextarea v-model="input.pemeriksaanfisik" rows="2">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="is-flex">
                            <div class="columns is-2" style=" margin-top: 1rem">
                                <h1 style="font-weight: bold;"> Pemeriksaan Penunjang </h1>
                            </div>
                            <div class="columns is-6" style=" margin-top: 1rem">
                                <VField v-for="items in pemeriksaanPenunjang" :key="items.value" style="padding:0px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input.pemeriksaanpenunjang" class="pt-1 pb-1 pl-6"
                                            :true-value="items.label" :label="items.label" color="primary" square />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-8 p-0">
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.ketpemeriksaanpenunjang" rows="2">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>

                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold;margin-bottom: 0.5rem;"> VII. Obat selama perawatan </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.terapi" rows="2">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold;margin-bottom: 0.5rem;"> VIII. Hasil Konsul </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.hasilkonsultasi" rows="2">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>

                <div class="column is-12" style="margin-top: 1rem;">
                    <h1 style="font-weight: bold;margin-bottom: 0.5rem;"> IX. Kondisi Saat Pulang</h1>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <VField v-for="items in kondisiSaatPulang" :key="items.value" style="padding:0px;">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.kondisiwaktukeluar" class="pt-1 pb-1 pl-6"
                                        :true-value="items.label" :label="items.label" color="primary" square />
                                </VControl>
                            </VField>
                        </div>

                    </div>

                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold;margin-bottom: 0.5rem;"> X. Tindak Lanjut/Anjuran/Edukasi </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.instruksianjuran" rows="2">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12" style="margin-top: 1rem;">
                    <h1 style="font-weight: bold;margin-bottom: 0.5rem;">XI. Pengobatan Lanjutan</h1>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="columns is-8" style="margin-top: 0.5rem;">
                                <VField v-for="items in pengobatanLanjutan" :key="items.value" style="padding:0px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input.pengobatanlanjutan" class="pt-1 pb-1 pl-6"
                                            :true-value="items.label" :label="items.label" color="primary" square />
                                    </VControl>
                                </VField>

                            </div>
                            <div class="column is-4 pt-1">
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder=""
                                            v-model="input.keteranganlainnya" />
                                    </VControl>
                                </VField>
                            </div>


                        </div>
                    </div>

                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-2" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Tanggal Kontrol Poliklinik </h1>
                        </div>
                        <div class="column is-4">
                            <VDatePicker v-model="input.tglkontrolpoli" mode="dateTime" style="width: 100%" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-6" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Jika muncul keluhan nyeri/rasa sakit tidak berkurang dengan obat
                                anda atau tidak lebih buruk dan kebutuhan mendesak, segera datang ke IGD Rumah Sakit </h1>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="" v-model="input.rumahsakittujuan" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-8">
                    <h1 style="font-weight: bold;margin-bottom: 0.5rem;"> XII. Terapi Pulang </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.terapipulang" rows="2">
                            </VTextarea>
                        </VControl>
                    </VField>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


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
const d_Diagnosa: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const dataSource: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const pemeriksaanPenunjang: any = ref([
    { label: 'Laboratorium', value: 'Loboratorium' },
    { label: 'Rotgen', value: 'Rotgen' },
    { label: 'CT Scan/MRI/USG', value: 'CT Scan/MRI/USG' },
])
const kondisiSaatPulang: any = ref([
    { label: 'Sembuh', value: 'Sembuh' },
    { label: 'Membaik', value: 'Membaik' },
    { label: 'Belum Sembuh', value: 'Belum Sembuh' },
    { label: 'Meninggal', value: 'Meninggal' },
    { label: 'Atas Permintaan Sendiri', value: 'Atas Permintaan Sendiri' },
    { label: 'Pindah RS Lain', value: 'Pindah RS Lain' },
])
const pengobatanLanjutan: any = ref([
    { label: 'Poliklinik', value: 'Poliklinik' },
    { label: 'RS Lain', value: 'RS Lain' },
    { label: 'Puskesmas', value: 'Puskesmas' },
    { label: 'Dokter Luar', value: 'Dokter Luar' },
])
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tglkontrolpoli: new Date(),
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

const loadRiwayat = () => {
    isLoading.value = true
    useApi().get(
        `/emr/get-resume-medis?nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}`).then((response: any) => {
            isLoading.value = false
            input.value = response.data[0]
            input.value.kddiagnosismasuk = {
                label : response.data[0].namadiagnosa2,
                value : response.data[0].kddiagnosa2
            }
            input.value.kddiagnosisawal = {
                label : response.data[0].namadiagnosa2,
                value : response.data[0].kddiagnosa2
            }
            
        })
}

const simpan = async () => {
    let formData = {
        'norec': input.value.norec != undefined ? input.value.norec : '',
        'noregistrasifk': props.registrasi.norec_apd,
        'diagnosismasuk': input.value.diagnosismasuk,
        'kddiagnosismasuk': input.value.kddiagnosismasuk.value,
        'diagnosisawal': input.value.diagnosisawal,
        'kddiagnosisawal': input.value.kddiagnosisawal.value,
        'namadiagnosatambahan' : input.value.namadiagnosatambahan.label ? input.value.namadiagnosatambahan.label : null,
        'tindakanprosedur': input.value.tindakanprosedur,
        'alasandirawat': input.value.alasandirawat,
        'ringkasanriwayatpenyakit': input.value.ringkasanriwayatpenyakit,
        'tglkontrolpoli': H.formatDate(input.value.tglkontrolpoli, 'YYYY-MM-DD HH:mm'),
        'pemeriksaanpenunjang': input.value.pemeriksaanpenunjang,
        'terapi': input.value.terapi,
        'hasilkonsultasi': input.value.hasilkonsultasi,
        'kondisiwaktukeluar': input.value.kondisiwaktukeluar,
        'instruksianjuran': input.value.instruksianjuran,
        'pengobatandilanjutkan': input.value.pengobatanlanjutan,
        'rumahsakittujuan': input.value.rumahsakittujuan,
        'pemeriksaanfisik': input.value.pemeriksaanfisik,
    }
    isLoading.value = true
    await useApi().post('/emr/simpan-resume', formData).then((r) => {
        isLoading.value = false
        // loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })

}
const fetchDiagnosa = async (filter: any) => {
    let query = filter ? `&query=${filter.query}&limit=10` : ''
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=namadiagnosa${query}`)
    d_Diagnosa.value = response
}


const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {

}

setView()
setAutoFill()
// loadRiwayat()
// fetchDiagnosa()
</script>
  
<style lang="scss"></style>
