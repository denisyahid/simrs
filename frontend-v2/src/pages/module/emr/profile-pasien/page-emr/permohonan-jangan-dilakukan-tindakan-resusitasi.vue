<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="column is-12">
        <VCard>
            <p class="p-2 paraf">&nbsp;&nbsp;&nbsp;&nbsp;Saya dokter yang bertanda tangan dibawah ini menginstruksikan kepada seluruh staf medis dan staf klinis lainnya
            untuk tetap melakukan tindakan suportif untuk mencegah henti napas dan henti jantung tanpa melakukan intubasi,
            ventilasi dan pijatan jantung.</p>
            <i><p class="p-2 paraf">&nbsp;&nbsp;&nbsp;&nbsp;I am the signee he doctor below instructed to all the medical staff and other clinical staffs to continue giving supportive
                action to prevent respiratory arrest and cardiac arrest without intubation, ventilation and cardiac massage</p></i>
            <p class="p-2 paraf">&nbsp;&nbsp;&nbsp;&nbsp;Yang dimaksud tindakan suportif adalah pembukaan jalan napas non invasive, mengontrol perdarahan, memposisikan
                pasien dengan nyaman dan pemberian obat-obatan anti nyeri pada pasien :</p>
            <i><p class="p-2 paraf">&nbsp;&nbsp;&nbsp;&nbsp;The meaning of supportive action is non invasive opening air way, bleeding control, positioning of patient in the comfortable
                position, and giving pain killer medicines to the patient:</p></i>

            <div class="column is-6">
                <h1 style="font-weight: bold;">Identitas Pasien</h1>
                <h1 style="font-weight: bold;"><i>Patient identity</i></h1>
            </div>
            <div class="column is-12 is-flex ml-5">
                <div class="column is-3" style="margin-top: 10px;">
                    <span class="label-pso">Nama lengkap pasien</span><br>
                    <span class="label-pso"><i>Patient’s complete name</i></span>
                </div>
                <div class="column is-6">
                    <VField>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.namaPasien" />
                    </VControl>
                    </VField>
                </div>
            </div>
            <div class="column is-12 is-flex ml-5">
                <div class="column is-3">
                    <span>Umur/Jenis Kelamin</span><br>
                    <span><i>Age/sex</i></span>
                </div>
                <div class="column is-6">
                    <VField class="mt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.jenisKelamin" />
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="column is-12 is-flex ml-5">
                <div class="column is-3">
                    <span class="label-pso">Alamat:</span><br>
                    <span class="label-pso"><i>Address</i></span>
                </div>
                <div class="column is-7">
                    <VField>
                    <VControl>
                        <VTextarea v-model="input.alamat" class="input" type="text" placeholder="Alamat" />
                    </VControl>
                    </VField>
                </div>
            </div>
          <div class="column is-12 is-flex ml-5">
                <div class="column is-3 pb-0">
                    <span class="label-pso">Nomor Rekam Medis</span><br>
                    <span class="label-pso"><i>Medical Record No</i></span>
                </div>
                <div class="column is-3 pb-0">
                    <VField>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.norm" />
                    </VControl>
                    </VField>
                </div>
            </div>
            <div class="column is-12 is-flex ml-5">
                <div class="column is-3">
                    <span style="font-weight:500;">Diagnose Medis</span><br>
                    <span style="font-weight:500;"><i>Medical Diagnoses</i></span>
                </div>
                <div class="column is-6">
                    <VField class="mt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.DiagnosaMedis" />
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="column is-12 is-flex ml-5">
                <div class="column is-3">
                    <span style="font-weight:500;">Alasan DNR</span><br>
                    <span style="font-weight:500;"><i>Reason DNR</i></span>
                </div>
                <div class="column is-6">
                    <VField class="mt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.AlasanDNR" />
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="column is-12 is-flex ml-5">
                <div class="column is-3">
                    <span style="font-weight:500;">Yang menetapkan DNR</span><br>
                    <span style="font-weight:500;"><i>The stated DNR</i></span>
                </div>
                <div class="column is-3">
                    <span>1. Dr DPJP</span><br>
                    <span><i>Responsible doctor</i></span>
                </div>
                <div class="column is-6">
                    <VField class="mt-3">
                        <VControl>
                            <AutoComplete v-model="input.DrDPJP" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" />
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="column is-12 is-flex ml-5">
                <div class="column is-3">
                </div>
                <div class="column is-3">
                    <span>2. Dr Spesialis Saraf</span><br>
                    <span><i>Neurologist</i></span>
                </div>
                <div class="column is-6">
                    <VField class="mt-3">
                        <VControl>
                            <AutoComplete v-model="input.DrSpesialisSaraf" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" />
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="column is-12 is-flex ml-5">
                <div class="column is-3">
                </div>
                <div class="column is-3">
                    <span>3. Dr Spesialis Anasthesi</span><br>
                    <span><i>Intensivist&Anesthesiologist</i></span>
                </div>
                <div class="column is-6">
                    <VField class="mt-3">
                        <VControl>
                            <AutoComplete v-model="input.DrSpesialisAnasthesi" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" />
                        </VControl>
                    </VField>
                </div>
            </div>
                <p class="p-2 paraf">Keputusan DNR diatas diambil setelah DPJP memberikan penjkelasan dan informasi dan juga telah memberikan
                    kesempatan untuk bertanya sehubungan dengan DNR kepada pasien/anggota keluarga:</p>
                <i><p class="p-2 paraf">DNR decision was taken after the responsible doctor gave explanation and information and also has given opportunities to
                    ask questions related to DNR to the patient/family member:</p></i>
            <div class="column">
                <div class="column is-12 is-flex ml-5">
                <div class="column is-3">
                    <span style="font-weight:500;">Nama</span><br>
                    <span style="font-weight:500;"><i>Name</i></span>
                </div>
                <div class="column is-6">
                    <VField>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.namaPenangungJwb" />
                    </VControl>
                    </VField>
                </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                    <div class="column is-3">
                        <span style="font-weight:500;">Umur/Jenis Kelamin</span><br>
                        <span style="font-weight:500;"><i>Age/sex</i></span>
                    </div>
                    <div class="column is-6">
                        <VField class="mt-3">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.jenisKelaminPenangungJwb" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                    <div class="column is-3">
                        <span class="label-pso">Alamat:</span><br>
                        <span class="label-pso"><i>Address</i></span>
                    </div>
                    <div class="column is-7">
                        <VField>
                        <VControl>
                            <VTextarea v-model="input.alamatPenangungJwb" class="input" type="text" placeholder="Alamat" />
                        </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                    <div class="column is-3" style="margin-top: 10px;">
                    <span class="label-pso">Hubungan dengan pasien :</span><br>
                    <span class="label-pso"><i>Relation to patient</i></span>
                    </div>
                    <VField class="column is-4">
                        <VControl class="prime-auto">
                        <AutoComplete v-model="input.wali" :suggestions="d_Wali" @complete="fetchWali($event)" :optionLabel="'label'"
                            :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Cari ..." class="mt-2" />
                        </VControl>
                    </VField>
                </div>  
                <div class="column is-12 is-flex ml-5">
                    <div class="column is-3" style="margin-top: 10px;">
                    <span class="label-pso">Tanda Tangan :</span><br>
                    <span class="label-pso"><i>Sign</i></span>
                    </div>
                    <div class="column is-3" style="text-align:center">
                        <TandaTangan :elemenID="'TTDKeluarga'" :width="'150'" :height="'150'" class="dek" />
                        <div class="column p-0 mt-5" style="text-align: left;">
                        </div>
                    </div>
                </div>  
            </div>
            <p class="p-2 paraf">&nbsp;&nbsp;&nbsp;&nbsp;Jika yang diatas tidak dimungkinkan maka dokter yang bertanda tangan dibawah ini memberikan perintah DNR
            berdasarkan pada keputusan tiga orang dokter yang menyatakan bahwa Resusitasi jantung paru (RJP) akan mendatangkan
            hasil yang tidak efektif</p>
            <i><p class="p-2 paraf" style="font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;If the mention above is impossible done then the signee doctor will give DNR instruction based on the decision of three
                doctors that stated CPR (Cardiopulmonal Ressusitation) will give uneffective results</p></i>

            <div class="column is-12 is-flex ml-5">
                <div class="column is-4"></div>
                <div class="column is-4"></div>
                <div class="column is-4">
                <span style="font-weight:500">Garut</span>
                <VDatePicker v-model="input.tglDibuat" color="green" class="pt-3" trim-weeks mode="datetime"
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
            </div>

            <div class="columns is-multiline pt-5" style="justify-content: space-around;">

            <div class="column is-4" style="text-align:center"></div>
            <div class="column is-4" style="text-align:center"></div>
            <div class="column is-3" style="text-align:center">
                <span class="label-pso">Dokter Penanggung Jawab Pelayanan (DPJP)</span><br>
                <TandaTangan :elemenID="'TTDdokter'" :width="'150'" :height="'150'" class="dek" />
                <div class="column p-0 mt-5" style="text-align: left;">
                <VControl>
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" />
                </VControl>
                </div>
            </div>
            </div>
        </VCard>

    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
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
        COLLECTION: ''
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Wali: any = ref([])
const d_Dokter: any = ref([])
const dataTTD: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('PermohonanJanganDilakukanTidakanResusitasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    waktuDibuat: new Date()
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
                dataTTD.value = response[0]
                H.tandaTangan().set('TTDdokter', dataTTD.value.TTDdokter)
                H.tandaTangan().set('TTDKeluarga', dataTTD.value.TTDKeluarga)
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object['TTDdokter'] = H.tandaTangan().get('TTDdokter')
    object['TTDKeluarga'] = H.tandaTangan().get('TTDKeluarga')
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
    input.value.jenisKelamin = props.pasien.umur + '/' + props.pasien.jeniskelamin
    input.value.tglLahir  = props.pasien.tgllahir
    input.value.norm  = props.pasien.nocm
    input.value.alamat = props.pasien.alamatlengkap
    input.value.DrDPJP = props.registrasi.dokter
    input.value.DDDokter = props.registrasi.dokter
    input.value.tglDibuat = new Date()

}


const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const fetchWali = async (filter: any) => {

await useApi().get(
  `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
).then((response) => {
  d_Wali.value = response
})
}

setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.paraf {
    text-align: justify;
}
</style>
