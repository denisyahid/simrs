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

    <div class="column is-12">
        <VCard>
            <div class="column">
                <span>Yang bertandatangan di bawah ini :</span><br>
                <span><i>The undersigned below:</i></span>
                <div class="column is-12 is-flex ml-5">
                <div class="column is-3">
                    <span>Nama</span><br>
                    <span><i>Name</i></span>
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
                        <span>Umur/Jenis Kelamin</span><br>
                        <span><i>Age/sex</i></span>
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
                        <span>Alamat:</span><br>
                        <span><i>Address</i></span>
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
                    <div class="column is-3">
                        <span>No. KTP/Tanda Pengenal</span><br>
                        <span><i>Identity/ Passport numbers</i></span>
                    </div>
                    <div class="column is-6">
                        <VField class="mt-3">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TandaPengenal" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                    <div class="column is-3" style="margin-top: 10px;">
                    <span>Hubungan dengan pasien :</span><br>
                    <span><i>Relation with patient:</i></span>
                    </div>
                    <VField class="column is-4">
                        <VControl class="prime-auto">
                        <AutoComplete v-model="input.penanggungjawab" :suggestions="d_PenangungJwb" @complete="fetchPenanggungJawab($event)" :optionLabel="'label'"
                            :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Cari ..." class="mt-2" />
                        </VControl>
                    </VField>
                </div>  
            </div>

            <div class="column is-12 is-flex ml-5">
                <div class="column is-3" style="margin-top: 10px;">
                    <span>Nama lengkap pasien</span><br>
                    <span><i>Patient’s complete name</i></span>
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
                    <span>Tanggal Lahir:</span><br>
                    <span><i>Date of birth</i></span>
                </div>
                <div class="column is-7">
                    <VDatePicker class="p-3 pb-0" v-model="input.tglLhrPasien" color="green" trim-weeks mode="date"
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }" class="pb-0">
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
          <div class="column is-12 is-flex ml-5">
                <div class="column is-3 pb-0">
                    <span>No RM</span><br>
                    <span><i>Medical record Number</i></span>
                </div>
                <div class="column is-3 pb-0">
                    <VField>
                    <VControl>
                        <VInput type="text" class="input" v-model="input.norm" />
                    </VControl>
                    </VField>
                </div>
            </div>

            <hr>
            <div class="column">
                <h1 style="font-weight:bold">Dengan ini menyatakan: :</h1>
               
                <div class="column is-12 pl-5 pr-5 pb-0">
                    <p class="pb-3 p-justify">1. PERMOHONAN TIDAK DILAKUKAN TINDAKAN RESUSITASI JANTUNG PARU/DNR
                        (DO NOT RESUSCITATE) PADA PASIEN TERSEBUT DIATAS</p>
                    <p class="pb-3 p-justify"><i>APPLICATION FOR DO NOT DO CARDIO-PULMONARY RESUCITATION/ DNR (DO NOT
                        RESUSCITATE) IN THE ABOVE PATIENT</i></p>
                    <p class="pb-3 p-justify">2. Saya telah diberikan informasi dan penjelasan dari dokter tentang tindakan resusitasi jantung paru
                        apabila pasien mengalami henti nafas dan henti jantung</p>
                    <p class="pb-3 p-justify"><i>I have been given information and explanation from the doctor about cardiopulmonary
                        resuscitation if the patient has stopped breathing and cardiac arrest</i></p>
                    <p class="pb-3 p-justify">3. Saya memahami perlunya dan manfaat dari tindakan resusitsi jantung paru yaitu untuk tindakan
                        menyelamatkan nyawa pasien dan apabila tindakan resusitasi jantung paru tersebut tidak dilakukan
                        maka pasien akan mengalami resiko kematian</p>
                    <p class="pb-3 p-justify"><i>I fully understand the need and benefit of cardiopulmonary resuscitation which is to save the
                        patient’s life and if cardiopulmonary resuscitation is not carried out, the patient will be at risk of
                        death.</i></p>
                    <p class="pb-3 p-justify">4. Saya bertanggung jawab secara penuh atas segala akibat yang timbul apabila tidak dilakukan
                        tindakan resusitasi jantung paru.</p>
                    <p class="pb-3 p-justify"><i>I am fully responsible for any possibilities that might arise as consequences for refusing
                        resuscitation.</i></p>
                </div>
            </div>

            <div class="column p-0 mt-5 is-3">
                <span class="label-ptd">Garut,</span>
                <VDatePicker class="p-3 pb-0" v-model="input.tglDibuat" color="green" trim-weeks mode="datetime"
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

            <div class="columns is-multiline pt-5" style="justify-content: space-around;">
                <div class="column is-3" style="text-align:center">
                    <span class="label-pso">Yang menyatakan</span><br>
                    <div class="column is-12" style="text-align:center">
                        <span><i>Consignee</i></span>
                    </div>
                    <TandaTangan :elemenID="'TTDYangMembuatPernyataan'" :width="'150'" :height="'150'" class="dek" />
                    <div class="column p-0 mt-5" style="text-align: left;">
                    <VField class="pt-3">
                        <VControl>
                        <VInput type="text" class="input" v-model="input.pembuatPernyataan" />
                        </VControl>
                    </VField>
                    </div>
                </div>
                <div class="column is-3" style="text-align:center">
                    <span class="label-pso">Dokter DPJP</span><br>
                    <div class="column is-12" style="text-align:center">
                        <span><i>Doctors</i></span>
                    </div>
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

        <div class="columns is-multiline pt-5" style="justify-content: space-around;">
          <div class="column is-3" style="text-align:center">
            <span class="label-pso">Saksi Keluarga</span><br>
            <div class="column is-12" style="text-align:center">
                <span><i>Family</i></span>
            </div>
            <TandaTangan :elemenID="'TTDSaksiKeluarga'" :width="'150'" :height="'150'" class="dek" />
            <div class="column p-0 mt-5" style="text-align: left;">
              <VField class="pt-3">
                <VControl>
                  <VInput type="text" class="input" v-model="input.SaksiKeluarga" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-3" style="text-align:center">
            <span class="label-pso">Saksi RS</span><br>
            <span><i>Hospital’ staff</i></span>
            <TandaTangan :elemenID="'TTDSaksiRS'" :width="'150'" :height="'150'" class="dek" />
            <div class="column p-0 mt-5" style="text-align: left;">
              <VControl>
                <AutoComplete v-model="input.SaksiRS" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
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
const d_PenangungJwb: any = ref([])
const dataTTD: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tglDibuat: new Date()
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
                // if (response[0].ttdSaksi_I) {
                //     H.tandaTangan().set("signatureSaksi_I", response[0].ttdSaksi_I)
                // }
                // if (response[0].ttdSaksi_II) {
                //     H.tandaTangan().set("signatureSaksi_II", response[0].ttdSaksi_II)
                // }
                // if (response[0].ttdSaksi_III) {
                //     H.tandaTangan().set("signatureSaksi_III", response[0].ttdSaksi_III)
                // }
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
                dataTTD.value = response[0]
                H.tandaTangan().set('TTDdokter', dataTTD.value.TTDdokter)
                H.tandaTangan().set('TTDYangMembuatPernyataan', dataTTD.value.TTDYangMembuatPernyataan)
                H.tandaTangan().set('TTDSaksiRS', dataTTD.value.TTDSaksiRS)
                H.tandaTangan().set('TTDSaksiKeluarga', dataTTD.value.TTDSaksiKeluarga)
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    // object.ttdSaksi_I = H.tandaTangan().get("signatureSaksi_I")
    // object.ttdSaksi_II = H.tandaTangan().get("signatureSaksi_II")
    // object.ttdSaksi_III = H.tandaTangan().get("signatureSaksi_III")
    object['TTDdokter'] = H.tandaTangan().get('TTDdokter')
    object['TTDYangMembuatPernyataan'] = H.tandaTangan().get('TTDYangMembuatPernyataan')
    object['TTDSaksiRS'] = H.tandaTangan().get('TTDSaksiRS')
    object['TTDSaksiKeluarga'] = H.tandaTangan().get('TTDSaksiKeluarga')
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
    input.value.tglLahirPasien = props.pasien.tgllahir
    input.value.norm = props.pasien.nocm
    input.value.tglLhrPasien = props.pasien.tgllahir
    input.value.alamatPasien = props.pasien.alamatlengkap
}

const fetchPenanggungJawab = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
    ).then((response) => {
        d_PenangungJwb.value = response
    })
}

const d_Pegawai: any = ref([])
const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const d_Dokter: any = ref([])
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`
    ).then((response) => {
        d_Dokter.value = response
    })
}


setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.label-ptd {
    font-weight: 500;
}

.p-justify {
    text-align: justify;
}
.label-pso{
    font-weight: bold;
}
</style>
