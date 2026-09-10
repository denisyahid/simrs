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
                <h1 style="font-weight:bold">Yang bertanda tangan dibawah ini</h1>
                <div class="column is-4">
                    <span class="label-ptj">Dokter</span>
                    <VField class="mt-3">
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.dokter" :suggestions="d_Dokter"
                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Cari Dokter..." class="mt-2" />
                        </VControl>
                    </VField>
                </div>
                <div class="column">
                    <span class="label-ptj">Sebagai Dokter</span>
                    <div class="columns is-multiline pt-3">
                        <div class="column is-4">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.isUmum" class="p-0" true-value="Umum" label="Umum"
                                        color="primary" circle />
                                </VControl>
                            </VField>
                            <VField class="mt-3">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.ketDokterUmum" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.isSpesialis" class="p-0" true-value="Spesialis"
                                        label="Spesialis" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField class="mt-3">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.ketDokterSpesialis" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.isSubspesialis" class="p-0" true-value="Subspesialis"
                                        label="Subspesialis" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField class="mt-3">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.ketDokterSubspesialis" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column mt-5">
                <h1 style="font-weight:bold">Dalam hal ini bertindak sebagai dokter penanggung jawab pelayanan dari pasien
                </h1>
                <div class="column is-6">
                    <span class="label-ppd">Nama</span>
                    <VField class="mt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.namaPasien" />
                        </VControl>
                    </VField>
                </div>
                <div class="columns is-multiline pt-3 pl-3 pr-3">
                    <div class="column is-4 pb-0">
                        <span class="label-ppd">Jenis Kelamin</span>
                        <div class="columns is-multiline pt-5 pl-5 pr-5">
                            <div class="column p-0">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input.jnsKlmPasien" class="p-0" true-value="Laki-laki"
                                            label="Laki-laki" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column p-0">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input.jnsKlmPasien" class="p-0" true-value="Perempuan"
                                            label="Perempuan" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <div class="column is-3 pb-0">
                        <span class="label-ppd">No Rekam Medis</span>
                        <VField class="mt-3">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.norm" />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-3 pb-0">
                        <span class="label-ppd">Tanggal Lahir</span>
                        <VDatePicker class="p-3 pb-0" v-model="input.tglLahirPasien" color="green" trim-weeks mode="date"
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
                <div class="columns is-multiline pt-3 pl-3 pr-3">
                    <div class="column is-5">
                        <span class="label-ppd">Dirawat Diruangan</span>
                        <VField class="mt-3">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.ruangRawat" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <span class="label-ppd">Kelas</span>
                        <VField class="mt-3">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.kelas" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <span class="label-ppd">Diagnosa</span>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.diagnosa" :suggestions="d_Diagnosa"
                                @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Cari Diagnosa..." class="mt-3" />
                        </VControl>
                    </div>
                </div>
            </div>

            <div class="column mt-5">
                <p style="text-align: justify;">Dengan ini saya melimpahkan tanggung jawab perawatan dan pengobatan atas
                    pasien tersebut di atas kepada
                    Atas segala perhatian, perawatan dan pengobatan yang telah di berikan kami sampaikan banyak terimakasih
                </p>
            </div>

            <div class="column is-3">
                <span class="label-ptj">Bandung</span>
                <VDatePicker class="p-3 pb-0" v-model="input.tglDisetujui" color="green" trim-weeks mode="date"
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
                    <TandaTangan :elemenID="'signatureDokterPenerima'" :width="'180'" :height="'180'" class="dek" />
                    <div class="column p-0 mt-5" style="text-align: left;">
                        <span class="label-ptj">Dokter yang menerima pelimpahan</span>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.dokterPenerima" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" @item-select="setTandaTanganDokterPenerima($event)"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Dokter..." class="mt-2" />
                        </VControl>
                    </div>
                </div>
                <div class="column is-3" style="text-align:center">
                    <TandaTangan :elemenID="'signatureDokterPelimpah'" :width="'180'" :height="'180'" class="dek" />
                    <div class="column p-0 mt-5" style="text-align: left;">
                        <span class="label-ptj">Dokter yang melimpahkan</span>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.dokterPelimpah" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" @item-select="setTandaTanganDokterPelimpah($event)"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Dokter..." class="mt-2" />
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
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
        COLLECTION: '',
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Diagnosa: any = ref([])
const d_Dokter: any = ref([])
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
    tglDisetujui : new Date()
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
                if (response[0].ttdDokterPenerima) {
                    H.tandaTangan().set("signatureDokterPenerima", response[0].ttdDokterPenerima)
                }
                if (response[0].ttdDokterPelimpah) {
                    H.tandaTangan().set("signatureDokterPelimpah", response[0].ttdDokterPelimpah)
                }
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.ttdDokterPenerima = H.tandaTangan().get("signatureDokterPenerima")
    object.ttdDokterPelimpah = H.tandaTangan().get("signatureDokterPelimpah")
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
const setAutoFill = async () => {
    input.value.namaPasien = props.pasien.namapasien
    input.value.jnsKlmPasien = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.tglLahirPasien = props.pasien.tgllahir
    input.value.ruangRawat = props.registrasi.namaruangan
    input.value.kelas = props.registrasi.namakelas
}

const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const setTandaTanganDokterPelimpah = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signatureDokterPelimpah", element.ttd)
      }else{
        H.tandaTangan().set("signatureDokterPelimpah", '')
      }
    })
}

const setTandaTanganDokterPenerima = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signatureDokterPenerima", element.ttd)
      }else{
        H.tandaTangan().set("signatureDokterPenerima", '')
      }
    })
}

setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.label-ptj {
    font-weight: 500;
}
</style>
