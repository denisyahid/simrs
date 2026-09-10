<style lang="scss">
h1 {
    font-weight: bold;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    text-align: center !important;
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: bold;
    overflow: hidden;
    background-color: aquamarine;
    vertical-align: middle;
    padding: 10px 5px;
    word-break: normal;
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
import Checkbox from 'primevue/checkbox';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import Fieldset from 'primevue/fieldset';
// import * as EMR from '../page-emr-plugins/monitoring&evaluasi-resusitasi'

// Judul
useHead({
    title: 'Persetujuan Tindakan Kedokteran - ' + import.meta.env.VITE_PROJECT,
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
const newDate = new Date();
const input: any = ref({
    JAM: newDate
})
const dataTTD: any = ref([])
const d_Dokter: any = ref([])
const route = useRoute()
const pasien: any = ref({})
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
    filter: '',
    airway: [],
    disability: []
})
const COLLECTION: any = ref('PersetujuanTindakanKedokteranHD') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
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
    // if (NOREC_EMRPASIEN.value == '') return
    await useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
                dataTTD.value = response[0]
            }
        })
    H.tandaTangan().set("TTDpi1", dataTTD.value.TTDpi1)
    H.tandaTangan().set("TTDpi2", dataTTD.value.TTDpi2)
    H.tandaTangan().set("TTDptk1", dataTTD.value.TTDptk1)
    H.tandaTangan().set("TTDptk2", dataTTD.value.TTDptk2)
    H.tandaTangan().set("TTDptk3", dataTTD.value.TTDptk3)

    let dataRegis = H.setObjectRegistrasi(pasien.value.registrasi)
    let dataPasien = H.setObjectPasien(pasien.value)
    // console.log(dataPasien)
    input.value.TBNamaPasien = dataPasien.namapasien
    input.value.DTanggalLahir = dataPasien.tgllahir
    input.value.TBSTahun = calculateAge(dataPasien.tgllahir)
    input.value.TBJenisKelamin = dataPasien.jeniskelamin
    input.value.TBAlamat = dataPasien.alamatlengkap
    // input.value.TBNomorTeleponPasien = dataPasien.notelepon ? dataPasien.notelepon : dataPasien.nohp;
    // input.value.DDRuangan = dataRegis.namaruangan
    // input.value.DDDokter = dataRegis.dokter
    // input.value.TBnik = dataPasien.noidentitas
    // input.value.TBAgama = dataPasien.agama
    // input.value.TBnoRM = dataPasien.nocm
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value

    object['TTDpi1'] = H.tandaTangan().get("TTDpi1");
    object['TTDpi2'] = H.tandaTangan().get("TTDpi2");
    object['TTDptk1'] = H.tandaTangan().get("TTDptk1");
    object['TTDptk2'] = H.tandaTangan().get("TTDptk2");
    object['TTDptk3'] = H.tandaTangan().get("TTDptk3");
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
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}
// const fetchDokter = async (filter: any) => {
//     await useApi().get(
//         `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//     ).then((response) => {
//         d_Dokter.value = response
//     })
// }

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

// Loopingan
let Table1 = ref([
    {
        detail: [
            { type: 'label', label: 'Diagnosis (WD & DD)' },
            { type: 'tb' },
            { type: 'cb' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'Dasar Diagnosis' },
            { type: 'tb' },
            { type: 'cb' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'Tindakan Kedokteran' },
            { type: 'tb' },
            { type: 'cb' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'Indikasi Tindakan' },
            { type: 'tb' },
            { type: 'cb' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'Tata Cara' },
            { type: 'tb' },
            { type: 'cb' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'Tujuan' },
            { type: 'tb' },
            { type: 'cb' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'Risiko' },
            { type: 'tb' },
            { type: 'cb' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'Komplikasi)' },
            { type: 'tb' },
            { type: 'cb' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'Prognosis' },
            { type: 'tb' },
            { type: 'cb' },
        ]
    },
    {
        detail: [
            { type: 'label', label: 'Alternatif & Risiko' },
            { type: 'tb' },
            { type: 'cb' },
        ]
    }
])
let Table2 = ref([
    {}, {}, {}, {}, {},
    {}, {}, {}, {}, {},
    {}, {}, {}, {}, {}
])

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
            isLoading.value = false
            console.log(responselast)
            if (responselast.length) {
                for (var x = 0; x < responselast.length; x++) {
                    responselast[x].no = x + 1
                    responselast[x].id = ''
                }
                listTemplateFix.value = responselast //set ke inputan
                showModalTemplateFix.value = true
            } else {
                H.alert('warning', 'Data tidak ada')
            }
        })
}
const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('error', 'Nama Template harus diisi untuk menyimpan.');
        return;
    }
    let ID = input.id ? input.id : ''
    let object: any = {}
    object = input.value
    object.nocm = pasien.value.nocm
    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
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
        `/emr/simpan-emr-template`, json).then((response: any) => {
            isLoading.value = false
            input.value.namatemplate = null
        }).catch((e: any) => {
            isLoading.value = false
        })
}

// getDataExist()
fetchPasien()
</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Persetujuan Tindakan Kedokteran</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                        isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                    </VButton>
                </div>

                <hr class="m-0">

                <div class="column is-12">
                    <h1>Nama Template&emsp;&emsp;
                        <span style="color:red">**Hanya diisi jika ingin membuat template</span>
                    </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.namatemplate" rows="1">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>

                <hr class="m-0">

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="PEMBERIAN INFORMASI" style="margin-bottom: 10px">
                        <div class="column is-12">
                            <table class="tg">
                                <tr>
                                    <td style="vertical-align: middle;text-align: center;">Dokter Pelaksana Tindakan
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="text" class="input"
                                                v-model="input.TBDokterPelaksanaTindakan" />
                                        </VControl>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;text-align: center;">Pemberi Informasi</td>
                                    <td>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBPemberiInformasi" />
                                        </VControl>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;text-align: center;">Penerima Informasi / Pemberi
                                        Persetujuan*</td>
                                    <td>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBPenerimaInformasi" />
                                        </VControl>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="column is-12">
                            <table class="tg">
                                <tr>
                                    <th width="10%">NO</th>
                                    <th width="25%">JENIS INFORMASI</th>
                                    <th width="25%">ISI INFORMASI</th>
                                    <th width="25%">TANDA (v)</th>
                                </tr>
                                <tr v-for="(dataRow, row) in Table1" :key="row">
                                    <td style="text-align: center;">{{ row + 1 }}</td>
                                    <td v-for="(dataCol, col) in dataRow.detail" :key="col" style="text-align: center;">
                                        <div v-if="dataCol.type == 'label'">
                                            {{ dataCol.label }}
                                        </div>
                                        <div v-if="dataCol.type == 'tb'">
                                            <VControl>
                                                <VInput type="text" class="input"
                                                    v-model="input['tb_table1_' + row + '_' + col]" />
                                            </VControl>
                                        </div>
                                        <div v-if="dataCol.type == 'cb'">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="true" label=""
                                                    v-model="input['cb_table1_' + row + '_' + col]" />
                                            </VControl>
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Lain-lain</td>
                                    <td>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBTable1Lainlain1" />
                                        </VControl>
                                    </td>
                                    <td>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBTable1Lainlain2" />
                                        </VControl>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="vertical-align: middle;">
                                        Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar
                                        dan jelas dan memberikan kesempatan untuk bertanya dan/atau berdiskusi
                                    </td>
                                    <td style="text-align: center;vertical-align: middle;">
                                        <div class="column is-12"><b>Tanda Tangan</b></div>
                                        <TandaTangan :elemenID="'TTDpi1'" :width="'150'" :height="'150'" class="dek" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="vertical-align: middle;">
                                        Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas
                                        yang saya beri tanda/paraf di kolom kanannya, dan telah memahaminya
                                    </td>
                                    <td style="text-align: center;vertical-align: middle;">
                                        <div class="column is-12"><b>Tanda Tangan</b></div>
                                        <TandaTangan :elemenID="'TTDpi2'" :width="'150'" :height="'150'" class="dek" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="font-weight: bold;"><i>*Bila Pasien tidak kompeten atau tidak
                                            mau menerima informasi, maka penerima informasi adalah Wali atau Keluarga
                                            terdekat</i></td>
                                </tr>
                            </table>
                        </div>
                    </Fieldset>

                    <Fieldset :toggleable="true" legend="PERSETUJUAN TINDAKAN KEDOKTERAN" style="margin-bottom: 10px">
                        <p>Yang bertandatangan di bawah ini, Saya,</p>
                        <div class="columns">
                            <div class="column is-4">Nama :</div>
                            <div class="column is-8">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column is-4">Umur :</div>
                            <div class="column is-8">
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBSTahun" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Tahun</VButton>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column is-4">Jenis Kelamin :</div>
                            <div class="column is-8">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBJenisKelamin" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column is-4">Alamat :</div>
                            <div class="column is-8">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBAlamat" />
                                </VControl>
                            </div>
                        </div>

                        <p>dengan ini menyatakan Persetujuan untuk dilakukannya</p>
                        <div class="columns">
                            <div class="column is-4">Tindakan :</div>
                            <div class="column is-8">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBTindakan" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column is-4">terhadap Saya /</div>
                            <div class="column is-8">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBTerhadapSaya" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column is-4">Saya*Bernama</div>
                            <div class="column is-8">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBSayaBernama" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column is-4">Umur :</div>
                            <div class="column is-8">
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBSTahun2" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Tahun</VButton>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column is-4">Jenis Kelamin :</div>
                            <div class="column is-8">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBJenisKelamin2" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column is-4">Alamat :</div>
                            <div class="column is-8">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBAlamat2" />
                                </VControl>
                            </div>
                        </div>

                        <p>
                            Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di
                            atas kepada saya, termasuk risiko dan komplikasi yang mungkin timbul.
                        </p>
                        <p>
                            Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan
                            tindakan kedokteran bukanlah keniscayaan, melainkan sangat bergantung kepada izin Tuhan Yang
                            Maha Esa.
                        </p>
                        <div class="column is-12">
                            <VField label="Garut">
                                <VField addons>
                                    <VDatePicker v-model="input.DTptk" mode="datetime" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                    <VControl class="field-addon-body">
                                        <VButton static>WIB</VButton>
                                    </VControl>
                                </VField>
                            </VField>
                            <VField label="Saksi :">
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBSaksi" />
                                    </VControl>
                                </VField>
                            </VField>
                        </div>
                        <div class="column is-12 columns">
                            <div class="column is-4" style="text-align: center;vertical-align: middle;">
                                <div class="column is-12"><b>Yang Menyatakan*<br>Pasien / Keluarga Pasien</b></div>
                                <TandaTangan :elemenID="'TTDptk1'" :width="'150'" :height="'150'" class="dek" />
                            </div>
                            <div class="column is-4" style="text-align: center;vertical-align: middle;">
                                <div class="column is-12"><b>Perawat<br>&nbsp;</b></div>
                                <TandaTangan :elemenID="'TTDptk2'" :width="'150'" :height="'150'" class="dek" />
                            </div>
                            <div class="column is-4" style="text-align: center;vertical-align: middle;">
                                <div class="column is-12"><b>Dokter Penanggungjawab<br>Pelayanan (DPJP)</b></div>
                                <TandaTangan :elemenID="'TTDptk3'" :width="'150'" :height="'150'" class="dek" />
                            </div>
                        </div>
                    </Fieldset>

                    <Fieldset :toggleable="true" legend="PERSETUJUAN TINDAKAN HEMODIALISIS" style="margin-bottom: 10px">
                        <p>
                            Saya Memahami perlunya dan manfaat tindakan Hemodialisis yang telah dijelaskan seperti di
                            atas kepada saya, terhadap resiko dan komplikasi yang mungkin timbul.
                        </p>
                        <p>
                            Saya juga menyadari oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan
                            tindakan kedokteran
                            bukanlah keniscayaan, melainkan sangat tergantung kepada ijin Tuhan Yang Maha Esa.
                        </p>
                        <p>Dengan ini saya menyatakan setuju untuk dilakukannya tindakan Hemodialisis</p>
                        <table class="tg" style="margin-top: 10px;">
                            <tr>
                                <th rowspan="2">Tgl/Jam</th>
                                <th rowspan="2">Yang Menyatakan Persetujuan</th>
                                <th colspan="2">TANDA TANGAN</th>
                            </tr>
                            <tr>
                                <th>Pihak Pasien</th>
                                <th>Pihak Rumah Sakit</th>
                            </tr>
                            <tr v-for="(dataRow, index) in Table2" :key="index">
                                <td>
                                    <VDatePicker v-model="input['DTTable2_' + index]" mode="datetime" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable2_' + index + '_1']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable2_' + index + '_2']" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['TBTable2_' + index + '_3']" />
                                    </VControl>
                                </td>
                            </tr>
                        </table>
                    </Fieldset>
                </div>

                <!-- form baru -->
            </div>
        </div>
    </div>
</template>
