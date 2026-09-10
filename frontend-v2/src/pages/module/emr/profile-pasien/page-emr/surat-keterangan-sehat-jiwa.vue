<style lang="scss"></style>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
// import Checkbox from 'primevue/checkbox';
// import Fieldset from 'primevue/fieldset';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({
    title: 'Surat Keterangan Sehat Jiwa - ' + import.meta.env.VITE_PROJECT,
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
const route = useRoute()
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const d_Diagnosa: any = ref([])
const loadData: any = ref(true)
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
const COLLECTION: any = ref('SuratKeteranganSehatJiwa') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    // kebjamKedatangan: new Date(),
    // kebjamAsesmenAwal: new Date(),
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
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
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    } else {
        let dataRegis = H.setObjectRegistrasi(pasien.value.registrasi)
        let dataPasien = H.setObjectPasien(pasien.value)
        // input.value.DDRuangan = dataRegis.namaruangan
        input.value.DDDokter = { label: 'dr. IDA AYU PUTU ASTHI DAMAYANTI, SpKJ', value: 384 }
        input.value.TBNamaPasien = dataPasien.namapasien
        input.value.DTanggalLahir = dataPasien.tgllahir
        input.value.TBSTahun = calculateAge(dataPasien.tgllahir)
        // input.value.TBJenisKelamin = dataPasien.jeniskelamin
        input.value.TAAlamat = dataPasien.alamatlengkap
        input.value.TBAgama = dataPasien.agama
        input.value.TBStatus = dataPasien.statusperkawinan
        input.value.TBPekerjaan = dataPasien.pekerjaan
        input.value.TBPendidikan = dataPasien.pendidikan
        input.value.tempatlahir = dataPasien.tempatlahir
    }
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    // object.tempatLahir = H.setObjectPasien(pasien.value).tempatlahir
    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': route.name,
        'name_form': 'Surat Keterangan Sehat Jiwa',
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    console.log(json)

    isLoading.value = true
    useApi().post(`/emr/simpan-emr-surket`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const kembaliKeun = () => {
    window.history.back()
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    // NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    // console.log(norec_emr)
}
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`
    ).then((response) => {
        d_Dokter.value = response
    })
}
const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}
// const fetchDiagnosa = async (filter: any) => {
//     await useApi().get(
//         `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`
//     ).then((response) => {
//         d_Diagnosa.value = response
//     })
// }
const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
        if (response != null || response != undefined) {
            // input.value.TBSBeratBadan = response.beratBadan
            // input.value.TBSTinggiBadan = response.tinggiBadan
            // input.value.IMT = response.IMT
            // input.value.lingkarPerut = response.lingkarPerut
            // input.value.nadiObgyn = response.nadi
            // input.value.celciusObgyn = response.suhu
            // input.value.TBSTekananDarah = response.tekananDarah
            // input.value.nafasObgyn = response.pernapasan
            // input.value.sao2Obgyn = response.SPO2
        }
    })
}
const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
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


// getDataExist()
fetchPasien()
</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Surat Keterangan Sehat Jiwa</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"
                                :isHideCetakWNA="false"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="columns is-multiline column is-12">
                    <!-- <div class="column is-6">
                        <VField label="Section">
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.DDRuangan" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" disabled />
                            </VControl>
                        </VField>
                    </div> -->
                    <div class="column is-6">
                        <VField label="Dokter">
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VControl>
                        </VField>
                    </div>
                </div>

                <hr>

                <div class="columns is-multiline column is-12">
                    <div class="column is-6">
                        <div class="column columns is-multiline">
                            <div class="column is-3">Nama</div>
                            <div class="column is-9">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                                </VControl>
                            </div>

                            <div class="column is-3">Tanggal Lahir/Umur</div>
                            <div class="column is-4">
                                <VDatePicker v-model="input.DTanggalLahir" mode="date" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-4">
                                <VField addons>
                                    <VControl>
                                        <VInput type="number" class="input" v-model="input.TBSTahun" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Tahun</VButton>
                                    </VControl>
                                </VField>
                            </div>

                            <div class="column is-3">Tempat Lahir</div>
                            <div class="column is-9">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.tempatlahir" />
                                </VControl>
                            </div>

                            <div class="column is-3">Pendidikan</div>
                            <div class="column is-9">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBPendidikan" />
                                </VControl>
                            </div>

                            <div class="column is-3">Alamat</div>
                            <div class="column is-9">
                                <VField>
                                    <VTextarea rows="2" v-model="input.TAAlamat"></VTextarea>
                                </VField>
                            </div>

                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="column columns is-multiline">
                            <div class="column is-3">Pernikahan</div>
                            <div class="column is-9">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBStatus" />
                                </VControl>
                            </div>

                            <div class="column is-3">Agama</div>
                            <div class="column is-9">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBAgama" />
                                </VControl>
                            </div>

                            <div class="column is-3">Pekerjaan</div>
                            <div class="column is-9">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBPekerjaan" />
                                </VControl>
                            </div>

                            <div class="column is-3">Untuk Persyaratan</div>
                            <div class="column is-9">
                                <VField>
                                    <VTextarea rows="2" v-model="input.TAUntukPersyaratan"></VTextarea>
                                </VField>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- form baru -->

            </div>
        </div>

    </div>
</template>