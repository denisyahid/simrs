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
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="columns is-multiline p-3">
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Tanggal dan Jam</h1>
                                <VField>
                                    <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Dokter Pelaksana Tindakan</h1>
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="input.dokterTindakan" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Pemberi Informasi</h1>
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="input.pemberiInformasi" :suggestions="d_Pegawai"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Cari Pegawai" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;">Penerima informasi</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="input.bahasaPasien" placeholder="Penerima Informasi" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <Fieldset class="p-fieldsets" legend="PEMBERIAN INFORMASI" :toggleable="true">
                            <div class="columns is-multiline">
                                <table align="center" class="triase" style="width: 90%; margin-top: 2rem;">
                                    <tr>
                                        <th colspan="4" style="text-align : center"> PEMBERIAN INFORMASI </th>
                                    </tr>
                                    <tr>
                                        <th style="text-align : center"> No </th>
                                        <th style="text-align : center"> Jenis Informasi </th>
                                        <th style="text-align: center"> Isi Informasi </th>
                                        <th style="text-align: center"> Checklist </th>
                                    </tr>
                                    <tr v-for="(datas) in DaftarInformasi">
                                        <td>{{ datas.no }}</td>
                                        <td>{{ datas.Kriteria }}</td>
                                        <td>
                                            <div class="columns is 12">
                                                <div class="column" v-for="(dot) in datas.keterangan">
                                                    <VField v-if="dot.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[dot.model]" :true-value="dot.title"
                                                                :label="dot.title" color="primary" square />
                                                        </VControl>
                                                    </VField>

                                                    <VField v-else="dot.type == 'textArea'">
                                                        <VControl>
                                                            <VTextarea rows="2" v-model="input[dot.model]"
                                                                placeholder="Isi Informasi..." />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                        </td>
                                        <td>
                                            <div class="columns is-multiline">
                                                <div class="column is-12">
                                                    <VField v-for="(check) in datas.pilihan">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" v-model="input[check.model]"
                                                                :true-value="check.label" :label="check.label"
                                                                color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                </table>

                            </div>

                        </Fieldset>
                    </div>
                    <div class="column is-12 pb-1">
                        <h1 style="font-weight: bold;"> Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas
                            secara benar dan jelas dan memberikan kesempatan untuk bertanya dan/ atau berdiskusi</h1>
                        <div class="columns is-multiline" style="margin-top: 1rem;">
                            <div class="column is-12">
                                <div class="columns is-12" style="text-align: center;">
                                    <div class="column is-4">
                                    </div>
                                    <div class="column is-4" style="text-align: center;">
                                        <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                                        <VField class="is-autocomplete-select mt-3" v-slot="{ id }">

                                            <VControl icon="feather:search">
                                                <AutoComplete v-model="input.namaPegawai" :suggestions="d_Pegawai"
                                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="Cari nama Pegawai" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="column is-12 pb-1">
                        <h1 style="font-weight: bold;"> Dengan ini menyatakan bahwa saya telah menerima informasi
                            sebagaimana di atas yang saya beri tanda paraf di kolom kanannya dan telah memahaminya</h1>
                        <div class="columns is-multiline" style="margin-top: 1rem;">
                            <div class="column is-12">
                                <div class="columns is-12" style="text-align: center;">
                                    <div class="column is-4">
                                    </div>
                                    <div class="column is-4" style="text-align: center;">
                                        <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                                        <VField class="mt-3">
                                            <VControl>
                                                <VInput type="text" v-model="input.namaTTD" placeholder="Nama Lengkap" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="column is-12 pb-1">
                        <h1 style="font-weight: bold;"> **Bila pasien tidak kompeten atau tidak mau menerima informasi, maka
                            penerima informasi adalah wali atau keluarga terdekat</h1>
                        <div class="columns is-multiline" style="margin-top: 1rem;">
                            <div class="column is-12">
                                <div class="columns is-12" style="text-align: center;">
                                    <div class="column is-4">
                                    </div>
                                    <div class="column is-4" style="text-align: center;">
                                        <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan>
                                        <VField class="mt-3">
                                            <VControl>
                                                <VInput type="text" v-model="input.namaWali" placeholder="Nama Lengkap" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <Fieldset class="p-fieldsets" legend="PENOLAKAN ANESTESI/SEDASI" :toggleable="true">
                            <div class="column is-12">
                                <h1 style="font-weight: bold;">Yang bertanda tangan dibawah ini :
                                </h1>
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-2" style="margin-top:0.5rem">
                                            <h1 style="font-weight: bold;"> Nama Lengkap : </h1>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Nama Lengkap"
                                                        v-model="input.nama" />
                                                </VControl>

                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-2" style="margin-top:0.5rem">
                                            <h1 style="font-weight: bold;"> Jenis Kelamin : </h1>
                                        </div>
                                        <div class="columns is-6" style=" margin-top: 1rem">
                                            <VField v-for="items in JenisKelamin" :key="items.value" style="padding:0px;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1 "
                                                        :true-value="items.label" :label="items.label" color="primary"
                                                        square />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12 p-0">
                                 
                                    <div class="column is-12 p-0">
                                        <div class="is-flex">
                                            <div class="column is-2" style="margin-top:0.5rem">
                                                <h1 style="font-weight: bold;"> Alamat : </h1>
                                            </div>
                                            <div class="column is-6">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" class="input" placeholder="Alamat"
                                                            v-model="input.alamat" />
                                                    </VControl>

                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="flex">
                                        <h1 style="font-weight: bold">
                                            dengan ini menyatakan menolak untuk dilakukannya tindakan terhadap
                                        </h1>
                                        <div class="column is-3" style="margin-top:-1rem;">
                                            <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="fa:users" fullwidth class="prime-auto ">
                                                    <AutoComplete v-model="input.hubunganPenjamin" :suggestions="d_Hubungan"
                                                        @complete="fetchHubungan($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik untuk mencari..." />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>

                                </div>
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-2" style="margin-top:0.5rem">
                                            <h1 style="font-weight: bold;"> saya, Yang bernama : </h1>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Nama Lengkap"
                                                        v-model="input.namaPasien" />
                                                </VControl>

                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-2" style="margin-top:0.5rem">
                                            <h1 style="font-weight: bold;"> Jenis Kelamin : </h1>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Jenis Kelamin"
                                                        v-model="input.jenisKelaminPasien" />
                                                </VControl>

                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-2" style="margin-top:0.5rem">
                                            <h1 style="font-weight: bold;"> No. Rekam Medis : </h1>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VInput type="number" class="input" placeholder="No. Rekam Medis"
                                                        v-model="input.norm" />
                                                </VControl>

                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-2" style="margin-top:1rem">
                                            <h1 style="font-weight: bold;"> Tanggal Lahir : </h1>
                                        </div>
                                        <div class="column is-6" style=" margin-top: 0.5rem">
                                            <VField>
                                                <VDatePicker v-model="input.tanggalLahirPasien" mode="dateTime"
                                                    style="width: 100%" trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" placeholder="Tanggal"
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                        </div>

                                    </div>
                                </div>
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-2" style="margin-top:0.5rem">
                                            <h1 style="font-weight: bold;"> Alamat : </h1>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Alamat"
                                                        v-model="input.alamatPasien" />
                                                </VControl>

                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <h1 style="font-weight: bold"> Saya memahami perlunya dan manfaat tindakan tersebut
                                        sebagaimana telah dijelaskan seperti di atas kepada saya, termasuk risiko dan
                                        komplikasi yang mungkin timbul.
                                        Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti, maka
                                        keberhasilan tindakan kedokteran bukanlah keniscayaan, melainkan sangat bergantung
                                        kepada Izin Tuhan Yang Maha Esa.</h1>
                                </div>
                                <div class="column is-12">
                                    <h1 style="font-weight: bold">
                                        Alasan Penolakan
                                    </h1>

                                    <VField>
                                        <VControl>
                                            <VTextarea rows="2" v-model="input.alasanPenolakan"
                                                placeholder="Isi Informasi..." />
                                        </VControl>
                                    </VField>
                                </div>

                            </div>
                        </Fieldset>
                    </div>
                </div>
                <div class="column is-12 pb-1">
                        
                        <div class="columns is-multiline" style="margin-top: 1rem;">
                            <div class="column is-12">
                                <div class="columns is-12">
                                    <div class="column is-4" style="text-align: center;">
                                        <TandaTangan :elemenID="'signature_4'" :width="'180'" :height="'180'"></TandaTangan>
                                        <VField class="mt-3">
                                            <h1 style="font-weight: bold;"> Saksi 1 :</h1>
                                            <VControl>
                                                <VInput type="text" v-model="input.namaSaksi1" placeholder="Nama Lengkap" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4" style="text-align: center;">
                                        <TandaTangan :elemenID="'signature_5'" :width="'180'" :height="'180'"></TandaTangan>
                                        <VField class="mt-3">
                                            <h1 style="font-weight: bold;"> Saksi 2 :</h1>
                                            <VControl>
                                                <VInput type="text" v-model="input.namaSaksi2" placeholder="Nama Lengkap" />
                                            </VControl>
                                        </VField>
                                    </div>
                                        <div class="column is-4" style="text-align: center;">
                                        <TandaTangan :elemenID="'signature_6'" :width="'180'" :height="'180'"></TandaTangan>
                                        <VField class="mt-3">
                                            <h1 style="font-weight: bold;"> Yang Menyatakan :</h1>
                                            <VControl>
                                                <VInput type="text" v-model="input.namaYangMenyatakan" placeholder="Nama Lengkap" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    
                                </div>

                            </div>
                        </div>
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
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Fieldset from 'primevue/fieldset';
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as EMR from '../page-emr-plugins/penolakan-tindakan-anastesi'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let DaftarInformasi = ref(EMR.DaftarInformasi())

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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Hubungan: any = ref([])
const isLoading: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('PenolakanTindakanAnastesi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date()
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
                if (response[0].tandaTanganPegawai) {
                    H.tandaTangan().set("signature_1", response[0].tandaTanganPegawai)
                }
                if (response[0].tandaTanganWali1) {
                    H.tandaTangan().set("signature_2", response[0].tandaTanganWali1)
                }
                if (response[0].tandaTanganWali2) {
                    H.tandaTangan().set("signature_3", response[0].tandaTanganWali2)
                }
                if (response[0].tandaTanganSaksi1) {
                    H.tandaTangan().set("signature_4", response[0].tandaTanganSaksi1)
                }
                if (response[0].tandaTanganSaksi2) {
                    H.tandaTangan().set("signature_5", response[0].tandaTanganSaksi2)
                }
    
                if (response[0].tandaTanganPernyataan) {
                    H.tandaTangan().set("signature_6", response[0].tandaTanganPernyataan)
                }

            }
        })
}
const JenisKelamin: any = ref([
    { label: 'Laki - Laki', value: 'Laki - Laki' },
    { label: 'Perempuan', value: 'Perempuan' }
])
const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchHubungan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=${filter.query}&limit=10`)
    d_Hubungan.value = response
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.tandaTanganPegawai = H.tandaTangan().get("signature_1")
    object.tandaTanganWali1 = H.tandaTangan().get("signature_2")
    object.tanganTanganWali2 = H.tandaTangan().get("signature_3")
    object.tandaTanganSaksi1 = H.tandaTangan().get("signature_4")
    object.tandaTanganSaksi2 = H.tandaTangan().get("signature_5")
    object.tandaTanganPernyataan = H.tandaTangan().get("signature_6")
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
    input.value.jenisKelaminPasien = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.tanggalLahirPasien = props.pasien.tgllahir
    input.value.alamatPasien = props.pasien.alamatlengkap
    input.value.dokterRawat = props.registrasi.dokter
    input.value.tglPembuatan = new Date()
}
const setTandaTangan = async (e: any) => {
    const response = await useApi().get(
        `/emr/tanda-tangan/${e.value.value}`)
    if (response != null) {
        H.tandaTangan().set("signature_1", response.ttd)
        input.value.tandaTanganPegawai = response.ttd
    } else {
        H.tandaTangan().set("signature_1", '')
    }
}
setView()
setAutoFill()
loadRiwayat()
</script>
<style lang="scss">
#signature {
    border: double 3px transparent;
    border-radius: 5px;
    background-image: linear-gradient(white, white),
        radial-gradient(circle at top left, #4bc5e8, #9f6274);
    background-origin: border-box;
    background-clip: content-box, border-box;
}

.container {
    width: "100%";
    padding: 8px 16px;
}

.p-fieldsets .p-fieldset-content {
    background: #ffffff;
}

table.triase {
    border-collapse: collapse;
    width: 100%;
}

table.triase,
.triase th,
.triase td {
    border: 0.5px solid grey;
}


.triase th,
.triase td {
    padding: 8px;
    vertical-align: middle !important;
}
</style>
