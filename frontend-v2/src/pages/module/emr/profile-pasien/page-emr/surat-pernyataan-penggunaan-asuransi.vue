<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Surat Pernyataan Penggunaan Asuransi</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <Div class="column">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <h1>Nama Penanggung Jawab</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <h1>Tanggal Lahir</h1>
                            <VDatePicker v-model="input.DTanggalLahir" mode="date" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-6 pt-0">
                            <h1>Jenis Kelamin</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBJenisKelamin" />
                            </VControl>
                        </div>
                        <div class="column is-6 pt-0">
                            <h1>No.KTP/SIM/Kitas</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNoKTP" />
                            </VControl>
                        </div>
                        <div class="column is-12 pt-0">
                            <h1>Alamat</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TAAlamat"></VTextarea>
                            </VField>
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                    <div class="column is-4  pb-0">
                        <h1>Persetujuan :</h1>
                        <div class="columns">
                            <div class="column is-6 pl-0">
                                <VControl>
                                    <VCheckbox v-model="input.CBPersetujuan" label="Setuju" class="pt-1 pb-1"
                                        true-value="Setuju" color="primary" />
                                </VControl>
                            </div>
                            <div class="column is-6">
                                <VControl>
                                    <VCheckbox v-model="input.CBPersetujuan" label="Menolak" class="pt-1 pb-1"
                                        true-value="Menolak" color="primary" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <label>
                            Dengan ini menyatakan <span style="font-weight: bold !important;">{{ input.CBPersetujuan
                                }}</span> atas
                            penjelasan yang diberikan oleh petugas
                            UPTD.RSUD Bali Mandara Provinsi Bali berupa **): <br>
                            1) <b>Hanya</b> menggunakan <b>Asuransi BPJS Kesehatan</b> sebagai <b>penanggung
                                pelayanan</b><br>
                            <div class="column is-3 pt-0 pb-0 pl-0">
                                <Multiselect v-model="input.SAsuransi" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_asuransi" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            2) Memiliki Asuransi selain BPJS Kesehatan yaitu Asuransi
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBAsuransiLainnya"
                                    placeholder="Asuransi lainnya..." style="width: 25% !important;" />
                            </VControl>
                            **), <b>TIDAK</b>diperbolehkan melakukan Reiumberse Klaim terhadap Asuransi lain tersebut
                            jika status Pasien
                            <b>Sesuai Hak Kelas Asuransi BPJS Kesehatan.</b><br>
                            3) <VControl>
                                <VInput type="text" class="input" v-model="input.TBLainnya" placeholder="..." />
                            </VControl>
                        </label>
                    </div>
                    <div class="column is-12">
                        <h1>Terhadap :</h1>
                        <div class="columns is-multiline">
                            <div class="column is-2 pl-0">
                                <VControl>
                                    <VCheckbox v-model="input.CBTerhadap" label="Diri Saya Sendiri" class="pt-1 pb-1"
                                        true-value="Diri Saya Sendiri" color="primary" />
                                </VControl>
                            </div>
                            <div class="column is-2">
                                <VControl>
                                    <VCheckbox v-model="input.CBTerhadap" label="Suami" class="pt-1 pb-1"
                                        true-value="Suami" color="primary" />
                                </VControl>
                            </div>
                            <div class="column is-2">
                                <VControl>
                                    <VCheckbox v-model="input.CBTerhadap" label="Anak" class="pt-1 pb-1"
                                        true-value="Anak" color="primary" />
                                </VControl>
                            </div>
                            <div class="column is-2">
                                <VControl>
                                    <VCheckbox v-model="input.CBTerhadap" label="Ayah" class="pt-1 pb-1"
                                        true-value="Ayah" color="primary" />
                                </VControl>
                            </div>
                            <div class="column is-2">
                                <VControl>
                                    <VCheckbox v-model="input.CBTerhadap" label="Ibu Saya" class="pt-1 pb-1"
                                        true-value="Ibu Saya" color="primary" />
                                </VControl>
                            </div>
                            <div class="column is-2">
                                <VControl>
                                    <VCheckbox v-model="input.CBTerhadap" label="Saudara Saya   " class="pt-1 pb-1"
                                        true-value="Saudara Saya    " color="primary" />
                                </VControl>
                            </div>
                            <Div class="column is-12 pt-0">
                                dengan :
                            </Div>
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1>Nomor Rekam Medis</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNomorRM2" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Nama</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNama2" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Tanggal Lahir</h1>
                                <VDatePicker v-model="input.DTanggalLahir2" mode="date" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </div>

                            <div class="column is-4 pt-0 pb-0">
                                <h1>Umur</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBUmur2" />
                                </VControl>
                            </div>
                            <div class="column is-4 pt-0 pb-0">
                                <h1>Jenis Kelamin</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBJenisKelamin2" />
                                </VControl>
                            </div>
                            <div class="column is-4 pt-0 pb-0">
                                <h1>No.KTP/SIM/Kitas</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNoKTP2" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Dirawat Di Ruangan</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBRuanganRawat" />
                                </VControl>
                            </div>
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                    <div class="column is-12">
                        <label>
                            Demikian surat pernyataan persetujuan ini saya buat dengan penuh kesadaran dan tanpa adanya
                            paksaan
                        </label>
                    </div>

                    <div class="columns is-multiline">
                        <div class="column is-6"> </div>
                        <div class="column is-6 is-flex" style="justify-content: center;">
                            <VDatePicker v-model="input.DTTanggalHariIni" mode="date" trim-weeks
                                style="width: 25% !important;">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-6" style="text-align: center;">
                            <h1>Yang Menjelaskan</h1>
                            <TandaTangan :elemenID="'TTDYangMenjelaskan'" :width="'150'" :height="'150'" class="dek" />
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBYangMenjelaskan" />
                            </VControl>
                        </div>
                        <div class="column is-6" style="text-align: center;">
                            <h1>Yang Membuat Pernyataan</h1>
                            <TandaTangan :elemenID="'TTDYangMembuatPernyataan'" :width="'150'" :height="'150'"
                                class="dek" />
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBMembuatPernyataan" />
                            </VControl>
                        </div>
                        <Div class="column is-12">
                            <label>
                                **) Isi dengan lengkap<br>
                                * Centang yang dipilih
                            </label>
                        </Div>
                    </div>
                </Div>


                <!-- form baru -->

            </div>
        </div>

    </div>
</template>

<style lang="scss">
label {
    color: black !important;
}
</style>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

const d_asuransi: any = ref([{ value: 1, label: 'Umum/Pribadi' }, { value: 2, label: 'BPJS' }, { value: 3, label: 'Asuransi' }])
useHead({ title: 'Surat Pernyataan Penggunaan Asuransi - ' + import.meta.env.VITE_PROJECT })
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
const dataTTD: any = ref([]);
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

const COLLECTION: any = ref('SuratPernyataanPenggunaanAsuransi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    CBPersetujuan: 'Setuju',
    DTTanggalHariIni: new Date()
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
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDYangMembuatPernyataan", dataTTD.value.TTDYangMembuatPernyataan)
        H.tandaTangan().set("TTDYangMenjelaskan", dataTTD.value.TTDYangMenjelaskan)
    } else {
        let dataRegis = H.setObjectRegistrasi(props.registrasi)
        let dataPasien = H.setObjectPasien(props.pasien)
        input.value.TBNomorRM2 = dataPasien.nocm
        input.value.TBNama2 = dataPasien.namapasien
        input.value.DTanggalLahir2 = dataPasien.tgllahir
        input.value.TBUmur2 = calculateAge(dataPasien.tgllahir)
        input.value.TBJenisKelamin2 = dataPasien.jeniskelamin
        input.value.TBNoKTP2 = dataPasien.noidentitas
    }
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDYangMembuatPernyataan'] = H.tandaTangan().get("TTDYangMembuatPernyataan");
    object['TTDYangMenjelaskan'] = H.tandaTangan().get("TTDYangMenjelaskan");
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': route.name,
        'name_form': 'Surat Pernyataan Penggunaan Asuransi',
        'jenis_emr': 'asesmen_medis',
        'data': object
    }

    isLoading.value = true
    useApi().post(`/emr/simpan-emr-surket`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const getDataExist = async () => {

}
const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
}

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
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

// watch(() => input.value.DDariTanggal, 
// (newValue, oldValue) => {
//     let daysIn = moment(newValue).format("DD");
//     let daysKe = moment(input.value.DKeTanggal).format("DD");
//     let calculate = (parseInt(daysKe) - parseInt(daysIn)) + 1;
//     if(calculate < 0) {
//         input.value.TBTotalHari = 0; 
//     }else {
//         input.value.TBTotalHari = calculate
//     }

// })

// watch(() => input.value.DKeTanggal, 
// (newValue, oldValue) => {
//     let daysIn = moment(input.value.DDariTanggal).format("DD");
//     let daysKe = moment(newValue).format("DD");
//     let calculate = (parseInt(daysKe) - parseInt(daysIn)) + 1;
//     if(calculate < 0) {
//         input.value.TBTotalHari = 0; 
//     }else {
//         input.value.TBTotalHari = calculate
//     }
// })

// getDataExist()
</script>