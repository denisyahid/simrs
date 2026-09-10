<style lang="scss">
th {
    text-align: center !important;
    vertical-align: middle !important;
    font-weight: bold !important;
    border: 1px solid black !important;
}

td {
    border: 1px solid black !important;
    padding: 5px !important;
}
</style>
<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Formulir Persetujuan Prosedur Tindakan Onkologi Radiasi</h3>
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
                        <div class="column is-4 pb-0">
                            <h1>Nama Dokter Pemberi Penjelasan</h1>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.dokterPemberiPenjelasan" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                        </div>
                        <div class="column is-4 pb-0">
                            <h1>Nama Pasien</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.namaPasien" />
                            </VControl>
                        </div>
                        <div class="column is-4 pb-0">
                            <h1>Nomor Rekam Medis</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.noRM" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <div class="columns">
                                <div class="column is-4">
                                    <h1>Tempat</h1>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.tempatLahir"
                                            placeholder="Tempat lahir..." />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <h1>Tanggal Lahir</h1>
                                    <VDatePicker v-model="input.tanggalLahir" mode="date" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </div>
                                <div class="column is-4">
                                    <h1>Umur</h1>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.umurPasien" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-2">
                            <h1>Jenis Kelamin</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.jenisKelamin" />
                            </VControl>
                        </div>
                        <div class="column is-4" v-if="input.jenisKelamin == 'Perempuan'">
                            <div class="columns">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Sedang Mengandung"
                                            label="Sedang Mengandung" v-model="input.mengandung" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            true-value="Tidak Sedang Mengandung" label="Tidak Sedang Mengandung"
                                            v-model="input.mengandung" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4" v-else></div>
                        <div class="column is-4 pt-0">
                            <h1>Alamat Pasien</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.alamatPasien"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                            <h1>**) Jika penerima informasi bukan pasien , lengkapi isian pada kolom di bawah</h1>
                        </div>
                        <div class="column is-4">
                            <h1>Nama Penerima Informasi</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.namaPenerimaInformasi" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <h1>Hubungan Dengan Pasien</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.hubunganDenganPasien" />
                            </VControl>
                        </div>
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-12">
                            <table style="width: 100% !important;border-collapse: collapse;">
                                <tr>
                                    <th style="width: 33%;">Jenis Informasi</th>
                                    <th style="width: 33%;">Jenis Informasi</th>
                                    <th style="width: 33%;">Tanda Paraf</th>
                                </tr>
                                <tr>
                                    <td>Prosedur tindakan yang akan dilakukan</td>
                                    <td>
                                        <h1>Pada pasien ini akan dilakukan prosedur tindakan Onkologi Radiasi berupa
                                        </h1>
                                        <VField>
                                            <VTextarea rows="1" v-model="input.prosedurTindakanOnkologi"></VTextarea>
                                        </VField>
                                        <h1>dengan spesifikasi</h1>
                                        <VField>
                                            <VTextarea rows="1" v-model="input.prosedurTindakanOnkologiSpesifikasi">
                                            </VTextarea>
                                        </VField>
                                    </td>
                                    <th>
                                        <TandaTangan :elemenID="'TTDProsedurTindakanDilakukan'" :width="'150'"
                                            :height="'150'" class="dek" />
                                    </th>
                                </tr>
                                <tr>
                                    <td>Persiapan pasien menghadapi prosedur tindakan Onkologi Radiasi</td>
                                    <td>
                                        <h1>Pasien yang menjalani prosedur ini, kami nyatakan perlu / **) tidak perlu
                                            melakukan persiapan
                                            menghadapi prosedur tersebut. Persiapan yang perlu dilakukan adalah</h1>
                                        <VField>
                                            <VTextarea rows="1" v-model="input.pasienMenjalaninProsedur"></VTextarea>
                                        </VField>
                                    </td>
                                    <th>
                                        <TandaTangan :elemenID="'TTDPersiapanPasienMenghadapi'" :width="'150'"
                                            :height="'150'" class="dek" />
                                    </th>
                                </tr>
                                <tr>
                                    <td>Hal-hal yang perlu menjadi perhatian</td>
                                    <td>
                                        <h1>Sesuai dengan identifikasi resiko prosedur yang akan dilakukan (terlampir).
                                        </h1>
                                        <VField>
                                            <VTextarea rows="1" v-model="input.sesuaiDenganIdentifikasi"></VTextarea>
                                        </VField>
                                    </td>
                                    <th>
                                        <TandaTangan :elemenID="'TTDHalYangPerluMenjadiPerhatian'" :width="'150'"
                                            :height="'150'" class="dek" />
                                    </th>
                                </tr>
                            </table>
                            <table style="width: 100% !important;border-collapse: collapse;">
                                <tr>
                                    <td style="width: 66%;">Dengan ini menyatakan bahwa saya telah menerangkan hal-hal
                                        di atas secara jelas dan
                                        memberikan kesempatan untuk bertanya dan atau berdiskusi</td>
                                    <th style="width: 33%;">
                                        <h1 style="font-weight: bold;">Tanda Tangan Dokter</h1>
                                        <TandaTangan :elemenID="'TTDTelahMenerangkanJelas'" :width="'150'"
                                            :height="'150'" class="dek" />
                                    </th>
                                </tr>
                                <tr>
                                    <td style="width: 66%;">Dengan ini menyatakan bahwa saya telah menerima informasi
                                        dari dokter sebagaimana di
                                        atas kemudian saya beri tanda/paraf</td>
                                    <th style="width: 33%;">
                                        <h1 style="font-weight: bold;">Tanda Tangan Pasien/Wali Pasien</h1>
                                        <TandaTangan :elemenID="'TTDMenerimaInformasi'" :width="'150'" :height="'150'"
                                            class="dek" />
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        *)Coret pada pernyataan yang tidak sesuai<br>
                                        **)Bila pasien tidak kompeten atau tidak mau menerima informasi maka penerima
                                        informasi adalah wali atau
                                        keluarga
                                        terdekat (suami, istri, ayah/ibu kandung, kakak/adik kandung, anak kandung)
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="column is-12 pt-0 pb-0" style="text-align: center;">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                            <h1 style="font-weight: bold;">PERSETUJUAN PROSEDUR TINDAKAN ONKOLOGI RADIASI</h1>
                        </div>
                        <div class="column is-12 pb-0">
                            Yang bertanda tangan di bawah ini, saya
                        </div>
                        <div class="column is-3">
                            <h1>Nama</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.namaPersetujuan" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1>Umur</h1>
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.umurPersetujuan" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Tahun</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1>Jenis Kelamin</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.jenisKelaminPersetujuan" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1>Alamat</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.alamatPersetujuan"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12">
                            Menyatakan :<br>
                            1. Saya memahami perlunya dan manfaat prosedur tindakan Onkologi Radiasi sebagaimana telah
                            dijelaskan seperti
                            di atas kepada saya, termasuk resiko yang mungkin timbul.<br>
                            2. SETUJU dilakukannya prosedur tindakan onkologi radiasi berupa
                            *) terhadap diri saya / pihak yang saya wakili .<br><br>
                            <h1>Garut</h1>
                            <VDatePicker v-model="input.tanggal" mode="datetime" trim-weeks style="width: 20%;">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-12">
                            <div class="columns">
                                <div class="column is-3" style="text-align: center;">
                                    <h1 style="font-weight: bold;">Yang Menyatakan</h1>
                                    <TandaTangan :elemenID="'TTDYangMenyatakan'" :width="'150'" :height="'150'"
                                        class="dek mb-2" />
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.yangMenyatakan" />
                                    </VControl>
                                </div>
                                <div class="column is-3" style="text-align: center;">
                                    <h1 style="font-weight: bold;">Dokter</h1>
                                    <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'"
                                        class="dek mb-2" />
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.dokter" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" />
                                    </VControl>
                                </div>
                                <div class="column is-3" style="text-align: center;">
                                    <h1 style="font-weight: bold;">Saksi 1</h1>
                                    <TandaTangan :elemenID="'TTDSaksi1'" :width="'150'" :height="'150'"
                                        class="dek mb-2" />
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.saksi1" />
                                    </VControl>
                                </div>
                                <div class="column is-3" style="text-align: center;">
                                    <h1 style="font-weight: bold;">Saksi 2</h1>
                                    <TandaTangan :elemenID="'TTDSaksi2'" :width="'150'" :height="'150'"
                                        class="dek mb-2" />
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.saksi2" />
                                    </VControl>
                                </div>
                            </div>
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

useHead({ title: 'Formulir Persetujuan Prosedur Tindakan Onkologi Radiasi - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const COLLECTION: any = ref('PersetujuanProsedurTindakanOnkologiRadiasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const user = useUserSession().getUser().pegawai;
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const pasien: any = ref({})
const input: any = ref({
    tanggal: new Date(),
})
const dataTTD: any = ref([])
// const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
// const listTemplate: any = ref([])
// const showModalTemplate: any = ref(false)
// const listTemplateFix: any = ref([])
// const showModalTemplateFix: any = ref(false)

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
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDProsedurTindakanDilakukan", dataTTD.value.TTDProsedurTindakanDilakukan)
        H.tandaTangan().set("TTDPersiapanPasienMenghadapi", dataTTD.value.TTDPersiapanPasienMenghadapi)
        H.tandaTangan().set("TTDHalYangPerluMenjadiPerhatian", dataTTD.value.TTDHalYangPerluMenjadiPerhatian)
        H.tandaTangan().set("TTDTelahMenerangkanJelas", dataTTD.value.TTDTelahMenerangkanJelas)
        H.tandaTangan().set("TTDMenerimaInformasi", dataTTD.value.TTDMenerimaInformasi)
        H.tandaTangan().set("TTDYangMenyatakan", dataTTD.value.TTDYangMenyatakan)
        H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
        H.tandaTangan().set("TTDSaksi1", dataTTD.value.TTDSaksi1)
        H.tandaTangan().set("TTDSaksi2", dataTTD.value.TTDSaksi2)
    } else {
        console.log(props.pasien);
        input.value.namaPasien = props.pasien.namapasien
        input.value.noRM = props.pasien.nocm
        input.value.tempatLahir = props.pasien.tempatlahir
        input.value.tanggalLahir = props.pasien.tgllahir
        input.value.umurPasien = calculateAge(props.pasien.tgllahir)
        input.value.jenisKelamin = props.pasien.jeniskelamin
        input.value.alamatPasien = props.pasien.alamatlengkap
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object['TTDProsedurTindakanDilakukan'] = H.tandaTangan().get("TTDProsedurTindakanDilakukan");
    object['TTDPersiapanPasienMenghadapi'] = H.tandaTangan().get("TTDPersiapanPasienMenghadapi");
    object['TTDHalYangPerluMenjadiPerhatian'] = H.tandaTangan().get("TTDHalYangPerluMenjadiPerhatian");
    object['TTDTelahMenerangkanJelas'] = H.tandaTangan().get("TTDTelahMenerangkanJelas");
    object['TTDMenerimaInformasi'] = H.tandaTangan().get("TTDMenerimaInformasi");
    object['TTDYangMenyatakan'] = H.tandaTangan().get("TTDYangMenyatakan");
    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
    object['TTDSaksi1'] = H.tandaTangan().get("TTDSaksi1");
    object['TTDSaksi2'] = H.tandaTangan().get("TTDSaksi2");
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
// const fetchPegawai = async (filter: any) => {
//     await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {d_Pegawai.value = response})
// }
const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => { d_Dokter.value = response })
}

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