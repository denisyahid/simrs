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

            <div class="columns is-multiline column">
                <div class="column is-4 pb-0">
                    <h2>Pasien / Penangggungjawab</h2>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.TBNama" />
                    </VControl>
                </div>
                <div class="column is-4 pb-0">
                    <h2>Jenis Kelamin</h2>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.TBJenisKelamin" />
                    </VControl>
                </div>
                <div class="column is-4 pb-0">
                    <h2>Umur</h2>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.TBUmur" />
                    </VControl>
                </div>
                <div class="column is-4">
                    <h2>No. Telp/HP</h2>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.TBNoTlp" />
                    </VControl>
                </div>
                <div class="column is-4">
                    <h2>Alamat</h2>
                    <VField>
                        <VTextarea rows="2" v-model="input.TAAlamat"></VTextarea>
                    </VField>
                </div>
                <div class="column is-12 pt-0 pb-0">
                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                </div>
                <div class="column is-12 pt-0 pb-0">
                    <h1>Data Pasien</h1>
                </div>
                <div class="column is-4 pb-0">
                    <h2>Nama</h2>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                    </VControl>
                </div>
                <div class="column is-4 pb-0">
                    <h2>Tempat, Tanggal Lahir</h2>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.TBTempat" placeholder="Tempat..." />
                    </VControl>
                    <VDatePicker v-model="input.DTglLahir" mode="date" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                </div>
                <div class="column is-4 pb-0">
                    <h2>Jenis Kelamin</h2>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.TBJenisKelaminPasien" />
                    </VControl>
                </div>
                <div class="column is-4 pb-0">
                    <h2>Jaminan</h2>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.TBJaminanPasien" />
                    </VControl>
                </div>
                <div class="column is-4 pb-0">
                    <h2>Hak Pertanggungan Kelas</h2>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.TBHakPasien" />
                    </VControl>
                </div>
                <div class="column is-4">
                    <h2>Alamat</h2>
                    <VField>
                        <VTextarea rows="2" v-model="input.TAAlamatPasien"></VTextarea>
                    </VField>
                </div>
                <div class="column is-12 pt-0 pb-0">
                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                </div>
                <div class="column is-12">
                    <h1>Pernyataan:</h1>
                    <p>Dengan ini menyatakan bahwa :</p>
                    <p>1. Saya penanggung jawab pasien atas nama tersebut diatas mengajukan permohonan : </p>
                    <p>A. Permintaan kelas perawatan rawat inap yang Lebih Tinggi dari Hak Pertanggungan BPJS Non
                        PBI </p>
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h2>Dari Hak Kelas</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBHakKelas" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <h2>Naik Kelas Ke</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNaikKelas" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <h2>Diagnosa Awal</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBDiagnosaAwal" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0 pb-0">
                            <h2>Tindakan Awal</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBTindakanAwal" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0 pb-0">
                            <h2>Selisih Biaya</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBSelisihBiaya" />
                            </VControl>
                        </div>
                        <div class="column is-12 pb-0">
                            <h2>B. Saya Pasien Jaminan Umum meminta untuk Dirawat di Kelas</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBPasienJaminan" />
                            </VControl>
                        </div>
                        <div class="column is-12">
                            <h2>C. Permintaan kelas perawatan rawat inap yang lebih tinggi dari Hak Pertanggungan
                                Asuransi Lainnya</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBPermintaanKelas" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0">
                            <h2>Dari Hak Kelas</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBDariHakKelas" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0">
                            <h2>Naik Kelas Ke</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNaikKelasKe" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0">
                            <h2>Selisih Biaya</h2>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBSelisihBiaya2" />
                            </VControl>
                        </div>
                    </div>
                    <p>2. Saya bersedia membayar Kelebihan Biaya yang dibebankan atas pelayanan yang diberikan di
                        UPTD.RSUD
                        Bali Mandara Provinsi Bali : </p>
                    <p>3. Apabila terjadi sesuatu hal terkait dengan keputusan yang saya ambil, maka hal-hal
                        tersebut
                        merupakan tanggung jawab saya, pasien dan keluarga sepenuhnya, tidak ada sangkut pautnya
                        dengan
                        pihak Rumah Sakit ataupun melakukan tuntutan kepada pihak Rumah Sakit. </p>
                    <p>4. Secara sadar saya mengerti dan memahami sepenuhnya penjelasan yang diberikan pihak Rumah
                        Sakit
                        tentang Fasilitas, Sarana serta Tarif / Biaya yang ditetapkan dan diberlakukan di UPTD.RSUD
                        Bali
                        Mandara Provinsi Bali </p>
                    <p>Demikian Surat Pernyataan ini saya buat dengan sesungguhnya untuk dipergunakan sebagaimana
                        mestinya.
                    </p>
                    <div class="column is-12 pt-1 pb-0">
                        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                    </div>
                    <div class="columns is-multiline column">
                        <div class="column is-6" style="text-align: center !important;">
                            <p style="font-weight: bold;">Petugas Admission</p>
                            <h1>Tgl (Dates)</h1>
                            <div class="is-flex" style="justify-content: center;">
                                <VDatePicker v-model="input.DTanggalAdmission1" mode="date" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar">
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </div>
                            <TandaTangan :elemenID="'TTDAdmission1'" :width="'150'" :height="'150'" class="dek" />
                            <div class="is-flex" style="justify-content: center;">
                                <VControl class="prime-auto mt-5" style="width: 50%;">
                                    <AutoComplete v-model="input.DDPetugasRI" :suggestions="d_Petugas"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Ketika nama petugas..." />
                                </VControl>
                            </div>
                        </div>
                        <div class="column is-6" style="text-align: center !important;">
                            <p style="font-weight: bold;">Pasien / Keluarga</p>
                            <h1>Tgl (Dates)</h1>
                            <div class="is-flex" style="justify-content: center;">
                                <VDatePicker v-model="input.DTanggalAdmission2" mode="date" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar">
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </div>
                            <TandaTangan :elemenID="'TTDAdmission2'" :width="'150'" :height="'150'" class="dek" />
                            <div class="is-flex" style="justify-content: center;">
                                <VField class="mt-5" style="width: 50%">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.pasienTTDText" />
                                    </VControl>
                                </VField>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
// import Fieldset from 'primevue/fieldset';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import AutoComplete from 'primevue/autocomplete';
import { useUserSession } from '/@src/stores/userSession'
// import InputText from 'primevue/inputtext';
// import RadioButton from 'primevue/radiobutton';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import Textarea from "primevue/textarea";
// import InputNumber from "primevue/inputnumber";
import Dropdown from "primevue/dropdown";
// import Button from "primevue/button";
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
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)
const dataTTD: any = ref([]);
const d_Petugas: any = ref([])
const ingredient = ref('');
const route = useRoute()
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
const COLLECTION: any = ref('SuratPernyataanPermintaanKelasRI') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    DTanggalAdmission1: new Date(),
    DTanggalAdmission2: new Date()
})
const setView = () => {
    useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
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
const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDAdmission1", dataTTD.value.TTDAdmission1)
        H.tandaTangan().set("TTDAdmission2", dataTTD.value.TTDAdmission2)
    } else {
        // input.value.TBNama = props.pasien.namapasien
        // input.value.TBJenisKelamin = props.pasien.jeniskelamin
        // input.value.TBUmur = calculateAge(props.pasien.tgllahir)
        // input.value.TBNoTlp = props.pasien.nohp
        // input.value.TAAlamat = props.pasien.alamatlengkap
        input.value.TBNamaPasien = props.pasien.namapasien
        input.value.DTglLahir = props.pasien.tgllahir
        input.value.TBJenisKelaminPasien = props.pasien.jeniskelamin
        input.value.TBJaminanPasien = props.registrasi.namarekanan
        input.value.TBHakPasien = props.registrasi.namakelas
        input.value.TAAlamatPasien = props.pasien.alamatlengkap
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDAdmission1'] = H.tandaTangan().get("TTDAdmission1");
    object['TTDAdmission2'] = H.tandaTangan().get("TTDAdmission2");
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
        NOREC_EMRPASIEN.value = response.norec_emr
        input.value.id = response.id
        loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })
}

onBeforeMount(async () => {
    try {
        await setView()
        await loadRiwayat()
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
<style lang="scss">
h1 {
    font-weight: bold !important;
}

label {
    color: black !important;
}
</style>
