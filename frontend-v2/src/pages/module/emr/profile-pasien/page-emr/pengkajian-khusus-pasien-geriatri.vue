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
                            <VButton type="button" rounded outlined :disabled="!NOREC_EMRPASIEN ? true : false"
                                color="warning" raised icon="lnir lnir-printer" @click="print()"> Cetak
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

    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="column is-12">
                    <div class="colum is-12">
                        <div class="columns is-multiline">
                            <div class="column is-1 pb-0">
                                <h1>No</h1>
                            </div>
                            <div class="column is-4 pb-0">
                                <h1>Parameter</h1>
                            </div>
                            <div class="column is-5 pb-0 pr-0">
                                <h1>Pengkajian</h1>
                            </div>
                            <div class="column is-2 pb-0 pl-0" style="text-align: center;">
                                <h1>nilai</h1>
                            </div>
                        </div>
                        <hr class="mt-3">
                    </div>
                    <div class="columns is-multiline pb-1" v-for="(datas) in dataTable">
                        <div class="column is-1">
                            <h1>{{ datas.no }}</h1>
                        </div>
                        <div class="column is-4">
                            <h1>{{ datas.paramter }}</h1>
                        </div>
                        <div class="column is-7 mt-3">
                            <div class="columns is-multiline " v-for="(data, i) in datas.pengkajian">
                                <div class="column is-10 pt-0 pb-0">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[datas.model]" :true-value="data.value"
                                                class="p-0 mb-2" :label="data.title" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column pt-0 pb-0">
                                    <h1>{{ data.poin }}</h1>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <VCard class="border-card pink">
                                    <h1 class="mb-4">Keterangan</h1>
                                        <VField v-for="(data) in descripPoin">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model]" disabled :true-value="data.value"
                                                class="p-0" :label="data.title" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </VCard>
                            </div>
                            <div class="column is-3" style="margin-left: auto;">
                                <h1>Jumlah Total</h1>
                                <VField class="mt-2">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.jumlahTotal" disabled />
                                    </VControl>
                                </VField>
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
import { reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/pengkajian-khusus-pasien-geriatri'
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let dataTable = ref(EMR.dataTable())
let descripPoin = ref(EMR.listDescriptionPoin())


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
const isLoading: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('PengkajianKhususPasienGeriatri') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const d_Petugas: any = ref({})
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
            }
        })
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

const fetchPetugas = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Petugas.value = response
}

const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const getPoinDescrip = (e:any)=>{
    console.log(e)
    let mandiri = {
        "poin" : 100,
        "title" : "100 = Mandiri",
    }
    let ringan = {
        "poin" : 99,
        "title" : "91 - 99 = Ketergantungan Ringan",
    }
    let sedang = {
        "poin" : 90,
        "title" : "61 - 90 = Ketergantungan Sedang",
    }
    let berat = {
        "poin" : 60,
        "title" : "21 - 60 = Ketergantungan Berat",
    }
    let total = {
        "poin" : 20,
        "title" : "0 - 20 = Ketergantungan Total",
    }

    descripPoin.value.forEach((element:any) => {
        if(e <= 20 && e <= element.value.poin){
            input.value.ketPoin = total
        }
        else if(e <= 60 && e <= element.value.poin){
            input.value.ketPoin = berat
        }
        else if(e <= 90 && e <= element.value.poin){
            input.value.ketPoin = sedang
        }
        else if(e <= 99 && e <= element.value.poin){
            input.value.ketPoin = ringan
        }
        else if(e == 100 && e == element.value.poin){
            input.value.ketPoin = mandiri
        }

    });
}


setView()
loadRiwayat()



watch(() => [
    input.value.makan,
    input.value.mandi,
    input.value.kerapihan,
    input.value.berpakaian,
    input.value.BAB,
    input.value.BAK,
    input.value.toilet,
    input.value.berpindahTempat,
    input.value.mobilitas,
    input.value.menaikiTangga,
    // input.value.point2
], () => {
    let poin1 = input.value.makan ? parseInt(input.value.makan.poin) : 0
    let poin2 = input.value.mandi ? parseInt(input.value.mandi.poin) : 0
    let poin3 = input.value.kerapihan ? parseInt(input.value.kerapihan.poin) : 0
    let poin4 = input.value.berpakaian ? parseInt(input.value.berpakaian.poin) : 0
    let poin5 = input.value.BAB ? parseInt(input.value.BAB.poin) : 0
    let poin6 = input.value.BAK ? parseInt(input.value.BAK.poin) : 0
    let poin7 = input.value.toilet ? parseInt(input.value.toilet.poin) : 0
    let poin8 = input.value.berpindahTempat ? parseInt(input.value.berpindahTempat.poin) : 0
    let poin9 = input.value.mobilitas ? parseInt(input.value.mobilitas.poin) : 0
    let poin10 = input.value.menaikiTangga ? parseInt(input.value.menaikiTangga.poin) : 0

    const jumlahNilai = poin1 + poin2 + poin3 + poin4 + poin5 + poin6 + poin7 + poin8 + poin9 + poin10
    input.value.jumlahTotal = jumlahNilai
    getPoinDescrip(jumlahNilai)
})



</script>

<style lang="scss"> h1 {
     font-weight: bold;
 }
</style>