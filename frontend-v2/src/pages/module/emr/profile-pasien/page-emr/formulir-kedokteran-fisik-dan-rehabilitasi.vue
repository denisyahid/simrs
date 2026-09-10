<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Formulir Kedokteran Fisik dan Rehabilitasi</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun">
                            </ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <hr style="margin-top: 0px;">

                <div class="column is-4" style="vertical-align: middle;">
                    <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                        isLoading="false" @click="pilihRiwayat(index)"> Lihat Riwayat
                    </VButton>
                </div>

                <hr>

                <div class="column is-12 columns is-multiline">
                    <div class="column is-4">
                        <VField label="No. Rekam Medis">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNomorRM" disabled />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Nama Pasien">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNamaPasien" disabled />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Tanggal Lahir">
                            <VDatePicker v-model="input.DTanggalLahir" mode="date" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" disabled />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Alamat">
                            <VTextarea v-model="input.TAAlamatPasien"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="No.Telp/Hp">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNomorTeleponPasien" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Hubungan dengan tertanggung">
                            <VControl>
                                <Multiselect v-model="input.MHubunganDenganTertanggung" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_hubungan" :searchable="true"
                                    track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </VControl>
                        </VField>
                        <VControl v-if="input.MHubunganDenganTertanggung === 5">
                            <VInput type="text" class="input" v-model="input.TBLainlainHDT"
                                placeholder="Lain-lainnya..." />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <VField label="Dokter">
                            <VControl icon="" fullwidth class="prime-auto ">
                                <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Ketik untuk mencari..." />
                            </VControl>
                        </VField>
                    </div>
                </div>

                <hr>

                <div class="column is-12 columns is-multiline">
                    <div class="column is-6">
                        <VField label="Tanggal Pelayanan">
                            <VDatePicker v-model="input.DTanggalPelayanan" mode="date" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Pemeriksaan Penunjang">
                            <VTextarea v-model="input.TAPemeriksaanPenunjang"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Anamnesa">
                            <VTextarea v-model="input.TAAnamnesa"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <label style="font-weight: lighter;">Tata Laksana KFR (ICD9CM)</label>
                            <VTextarea v-model="input.TATataLaksanaKFR"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <label style="font-weight: lighter;">Pemeriksaan Fisik dan Uji Fungsi</label>
                            <VTextarea v-model="input.TAPemeriksaanFisikDanUjiFungsi"></VTextarea>
                        </VField>
                    </div>
                   <div class="column is-6">
                <!-- Commented out unused field for potential future use -->
                <!-- <VField label="Anjuran">
                    <VControl>
                        <VTextarea type="text" class="input" v-model="input.TBAnjuran" />
                    </VControl>
                </VField> -->

                <label style="font-weight: normal;">Anjuran</label><br>

                <VCheckbox 
                    class="fontcheckbox" 
                    v-model="input.anjuranPerminggu" 
                    true-value="2X perminggu, goal VAS 1-2" 
                    color="primary" />
                <span v-html="highlightMatch('2X perminggu, goal VAS 1-2')" class="highlighted-label"></span><br>
                    <br>
                <VCheckbox 
                    class="fontcheckbox" 
                    v-model="input.anjuranProgram" 
                    style="margin-top: -2px;" 
                    true-value="Home exc program" 
                    color="primary"  />
                <span v-html="highlightMatch('Home exc program')" class="highlighted-label"></span><br>
                <br>
                <VCheckbox 
                    class="fontcheckbox" 
                    v-model="input.anjuranLain" 
                    style="margin-top: -20px;" 
                    true-value="Lainnya1" 
                    color="primary"  />
                <span v-html="highlightMatch('Lainnya')" class="highlighted-label"></span><br>
                <textarea 
                    v-model="input.textLainnya1" 
                    class="textarea" 
                    v-if="input.anjuranLain === 'Lainnya1'" 
                    placeholder="Specify other recommendations here"
                ></textarea>
                </div>

                    <div class="column is-6">
                        <VField label="Diagnosis Medis (ICD 10)">
                            <VControl>
                                <VTextarea type="text" class="input" v-model="input.TBDiagnosisMedis" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <label style="font-weight: normal;">Evaluasi</label><br>
                            <VCheckbox class="fontcheckbox" v-model="input.evaluasiMinggu"
                            true-value="2 minggu"
                            color="primary" /><span
                            v-html="highlightMatch('2 minggu')"
                            class="highlighted-label"></span><br>
                            <br>
                            <VCheckbox class="fontcheckbox" v-model="input.evaluasiMinggu" style="margin-top: -20px;"
                            true-value="Rujuk balik"
                            color="primary" /><span
                            v-html="highlightMatch('Rujuk balik')"
                            class="highlighted-label"></span><br>
                            <br>
                            <VCheckbox class="fontcheckbox" v-model="input.evaluasiMinggu" style="margin-top: -20px;"
                            true-value="Stop terapi"
                            color="primary" /><span
                            v-html="highlightMatch('Stop terapi')"
                            class="highlighted-label"></span><br>
                            <br>
                            <VCheckbox class="fontcheckbox" v-model="input.evaluasiMinggu" style="margin-top: -20px;"
                            true-value="Lainnya2"
                            color="primary" /><span
                            v-html="highlightMatch('Lainnya')"
                            class="highlighted-label"></span><br>
                            <br>
                            <textarea v-model="input.textLainnya2" class="textarea" v-if="input.evaluasiMinggu === 'Lainnya2'"
                                placeholder=""></textarea>
                    </div>
                    <div class="column is-6">
                        <VField label="Diagnosis Fungsi (ICD 10)">
                            <VControl>
                                <VTextarea type="text" class="input" v-model="input.TBDiagnosisFungsi" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Suspek penyakit akibat kerja">
                            <div class="column columns is-multiline">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.CBSuspekPenyakit" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                                            v-model="input.CBSuspekPenyakit" />
                                    </VControl>
                                    <VControl style="margin-top: 5px">
                                        <VInput type="text" class="input" v-model="input.TBSuspekPenyakit" />
                                    </VControl>
                                </div>
                            </div>
                        </VField>
                    </div>
                </div>

                <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
                    @close="showModalTemplate = false">
                    <template #content>
                        <form class="modal-form">
                            <div class="column is-12 pt-0 pb-0">
                                <p style="font-size:9pt;font-weight:bold;padding: 7px;">List Riwayat</p>
                                <div style="overflow-y:auto;">
                                    <table style="width: 100%;border: 1px solid black;" v-if="listTemplate.length > 0">
                                        <thead>
                                            <tr>
                                                <td style="text-align: center;font-weight: bold;" width="15%">Tanggal
                                                    Input</td>
                                                <td style="text-align: center;font-weight: bold;" width="15%">Tanggal
                                                    Registrasi</td>
                                                <td style="text-align: center;font-weight: bold;" width="15%">No
                                                    Registrasi</td>
                                                <td style="text-align: center;font-weight: bold;" width="15%">No EMR
                                                </td>
                                                <!-- <td style="text-align: center;font-weight: bold;" width="20%">Dokter</td> -->
                                                <td style="text-align: center;font-weight: bold;" width="20%">Section
                                                </td>
                                                <!-- <td style="text-align: center;font-weight: bold;" width="10%">#</td> -->
                                            </tr>
                                        </thead>
                                        <tbody v-for="dataR in listTemplate">
                                            <tr style="border: 1px solid black;">
                                                <td class="padding" style="text-align:center;vertical-align: middle;">
                                                    <span class="mb-2">{{ dataR.created_at }}</span><br>
                                                </td>
                                                <td class="padding" style="text-align:center;vertical-align: middle;">
                                                    <span class="mb-2">{{ dataR.registrasi.tglregistrasi }}</span><br>
                                                </td>
                                                <td class="padding" style="text-align:center;vertical-align: middle;">
                                                    <span class="mb-2">{{ dataR.registrasi.noregistrasi }}</span><br>
                                                </td>
                                                <td class="padding" style="text-align:center;vertical-align: middle;">
                                                    <span class="mb-2">{{ dataR.pasien.nocm }}</span><br>
                                                </td>
                                                <!-- <td class="padding" style="text-align:center;vertical-align: middle;">
                                                    <span class="mb-2">{{ dataR.dpjpUtama }}</span><br>
                                                </td> -->
                                                <td class="padding" style="text-align:center;vertical-align: middle;">
                                                    <span class="mb-2">{{ dataR.registrasi.namaruangan }}</span><br>
                                                </td>
                                                <!-- <td class="padding" style="text-align:center;vertical-align: middle;">
                                                    <VIconButton type="button" raised circle icon="fas fa-plus"
                                                        @click="addTemplate(dataR)" color="info"
                                                        v-tooltip-prime.top="'Pilih'">
                                                    </VIconButton>
                                                </td> -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </template>
                </VModal>

                <!-- form baru -->
            </div>
        </div>
    </div>
</template>

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

// Judul
useHead({
    title: 'Formulir Kedokteran Fisik dan Rehabilitasi - ' + import.meta.env.VITE_PROJECT,
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
// const newDate = new Date();
// const zeroTime = new Date(new Date().setHours(0, 0, 0, 0));
// const dataTTD: any = ref([])
const d_hubungan: any = ref([
    { value: 1, label: 'Pasien Sendiri' },
    { value: 2, label: 'Suami/Istri' },
    { value: 3, label: 'Anak' },
    { value: 4, label: 'Orang tua' },
    { value: 5, label: 'Lainnya' }
])
const filterMenu: any = ref('')
const input: any = ref({})
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
const COLLECTION: any = ref('FormulirKedokteranFisikDanRehabilitasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const isAktive = ref()
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    } else {
        let dataRegis = H.setObjectRegistrasi(pasien.value.registrasi)
        let dataPasien = H.setObjectPasien(pasien.value)
        input.value.TBNomorRM = dataPasien.nocm
        input.value.TBNamaPasien = dataPasien.namapasien
        input.value.DTanggalLahir = dataPasien.tgllahir
        input.value.TAAlamatPasien = dataPasien.alamatlengkap
        input.value.TBNomorTeleponPasien = dataPasien.nohp
        input.value.DDDokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
        useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail&flag=dokter&ruangan=" + props.registrasi.namaruangan + "&field=S,O,A,P").then((responses) => {
            if (responses != null) {
                input.value.TAAnamnesa = responses.S
                input.value.TAPemeriksaanFisikDanUjiFungsi = responses.O
                input.value.TBDiagnosisMedis = responses.A
                input.value.TATataLaksanaKFR = responses.P
                isLoading.value = false
            } else {
                console.log('Data CPPT Detail Kosong')
                isLoading.value = false
            }
        })
    }
}

function highlightMatch(text) {
  if (!filterMenu.value) return text;

  const term = new RegExp(`(${filterMenu.value})`, 'gi');
  return text.replace(term, '<span style="background-color: yellow;">$1</span>');
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
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}
const d_Dokter: any = ref([])
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const pilihRiwayat = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&isriwayat=true`).then((responselast: any) => {
            isLoading.value = false
            if (responselast.length) {
                listTemplate.value = responselast //set ke inputan
                showModalTemplate.value = true
            } else {
                H.alert('warning', 'Data tidak ada')
            }
        })
}

const addTemplate = (response: any) => {
    // console.log(response)
    input.value = response //set ke inputan
    input.value.namatemplate = null
    showModalTemplate.value = false
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

const setAutoFill = async () => {
//   input.value.TBAnjuran = '2X perminggu',
//   input.value.TBEvaluasi = '5X, goal VAS-2'
useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=CPPTDetail" + "&field=intruksiPPA"
  ).then((response) => {
    if (response) {
        input.value.TATataLaksanaKFR = response.intruksiPPA
    }
  })
}

setAutoFill()
fetchPasien()
</script>

<style lang="scss">
.padding {
    padding: 5px;
}
</style>
