<template>
    <div>
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
                <div class="form-body p-2">
                    <div class="business-dashboard hr-dashboard">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <VCard class="border-card pink">
                                    <div class="columns is-multiline p-3">
                                        <div class="column is-7">
                                            <div class="column is-12 p-0">
                                                <h1 style="font-weight: bold;">Cara Masuk</h1>
                                                <div class="is-flex">
                                                    <VField v-for="(caramasuk) in listCaraMasuk">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.caramasuk" :true-value="caramasuk"
                                                                :label="caramasuk" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="column is-12 p-0">
                                                <h1 style="font-weight: bold;">Alat Bantu</h1>
                                                <div class="is-flex">
                                                    <VField v-for="(alat) in listAlatBantu">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.alatbantu" :true-value="alat"
                                                                :label="alat" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="column is-12 p-0">
                                                <h1 style="font-weight: bold;">Kasus</h1>
                                                <div class="is-flex">
                                                    <VField v-for="(kasus) in listKasus">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.kasus" :true-value="kasus"
                                                                :label="kasus" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column is-5">
                                            <h1 style="font-weight: bold;">Tanggal dan Jam datang</h1>
                                            <div class="columns pt-3 pb-0">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VDatePicker v-model="input.tanggalKedatangan" mode="dateTime"
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
                                            <div class="column is-12 p-0" style="position: relative;top:-18px">
                                                <h1 style="font-weight: bold;">Keluhan Utama</h1>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.keluhanUtama" rows="3"
                                                            placeholder="Keluhan Utama ...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>

                                            </div>
                                            <div class="column is-12 p-0">
                                                <h1 style="font-weight: bold;">Resiko Jatuh </h1>
                                                <div class="columns is-6">
                                                    <VField v-for="items in pilihan" :key="items.value">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.resikoJatuh" :true-value="items.label"
                                                                :label="items.label" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </VCard>

                                <VCard class="border-card pink mt-5">
                                    <!-- Table -->
                                    <div class="column is-12">
                                        <table class="tc" style="width: 90%; margin-top: 2rem;">

                                            <tr v-for="(datas) in daftarList">

                                                <td style="width: 10%;">
                                                    <h1 style="font-weight: bold; margin-top: 2rem;">{{ datas.Kriteria }}</h1></td>
                                                <td>
                                                    <div class="columns is-multiline pl-3 pt-3">
                                                        <div class="column is-12">
                                                            <VField v-for="(check, i) in datas.kolom1">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="pt-0" v-model="input[check.model + i]"
                                                                        :true-value="check.label" :label="check.label"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-12">
                                                            <VField v-for="(bro, i) in datas.kolom2">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="pt-1" v-model="input[bro.model + i]"
                                                                        :true-value="bro.label" :label="bro.label"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-12">
                                                            <VField v-for="(crew, i) in datas.kolom3">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="pt-1" v-model="input[crew.model + i]"
                                                                        :true-value="crew.label" :label="crew.label"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-12">
                                                            <VField v-for="(dew, i) in datas.kolom4">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="pt-0" v-model="input[dew.model + i]"
                                                                        :true-value="dew.label" :label="dew.label"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="column is 12">
                                                        <div class="column is-10 ml-5" v-for="(dot, i) in datas.keterangan">
                                                            <h1>{{ dot.label }}</h1>
                                                            <VField>
                                                                <VControl>
                                                                    <VInput type="text" v-model="input[dot.model]"
                                                                        placeholder="" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>

                                                </td>
                                                

                                            </tr>
                                            <tr>
                                                <td style="font-weight: bold; font-size: 20px; margin-top: 2rem;"> Kategori </td>
                                                <td> 
                                                    <VField style="background-color: red;">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.resusitasi" true-value="Ya"
                                                            label=" MERAH" color="danger" style="font-weight: bold; font-size: 20px;" square />
                                                    </VControl>
                                                    <h1 style="margin-top: -1rem; margin-left: 1rem;"> RESUSITASI </h1>
                                                </VField>
                                                
                                                   
                                                </td>
                                                <td> 
                                                    <VField style="background-color: yellow;">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.urgensi" true-value="Ya"
                                                            label="KUNING" color="danger" style="font-weight: bold; font-size: 20px;" square />
                                                    </VControl>
                                                    <h1 style="margin-top: -1rem; margin-left: 1rem; font-color:white;"> URGENT </h1>
                                                </VField>   
                                                </td>
                                                <td> 
                                                    <VField style="background-color: green;">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.nonUrgent" true-value="Ya"
                                                            label="HIJAU" color="danger" style="font-weight: bold; font-size: 20px;" square />
                                                    </VControl>
                                                    <h1 style="margin-top: -1rem; margin-left: 1rem;"> NON URGENT </h1>
                                                </VField>
                                               
                                                   
                                                </td>
                                                <td> 
                                                    <VField style="background-color: gray;">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.doa" true-value="Ya"
                                                            label="HITAM" color="danger" style="font-weight: bold; font-size: 20px;" square />
                                                    </VControl>
                                                    <h1 style="margin-top: -1rem; margin-left: 1rem; font-colour:rgb(180, 35, 35); font-weight: bold;"> DOA </h1>
                                                </VField>
                                               
                                                   
                                                </td>
                                                <td></td>
                                            </tr>
                                        </table>

                                    </div>
                                    <hr class="dashed-line">
                                    <div class="column is-12">
                                        <h1 style="font-weight: bold;"> Nyeri</h1>
                                        <div class="is-flex">
                                            <div class="columns is-6">
                                                <VField v-for="items in pilihan" :key="items.value">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input.nyeri" :true-value="items.label"
                                                            :label="items.label" color="primary" square />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="columns is-6 ml-6">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" class="input" placeholder="Lokasi Nyeri"
                                                            v-model="input.lokasiNyeri" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                        <div class="column is-12" style="margin-top: -1rem;">
                                            <p> * Bila ya lanjutkan ke pengkajian nyeri</p>
                                        </div>

                                    </div>
                                    <hr class="dashed-line">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;"> Pengkajian Nyeri</h1>
                                    <div class="columns is-6">
                                        <VField v-for="items in metoda" :key="items.value">
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input.metodaPengkajianNyeri" :true-value="items.label"
                                                    :label="items.label" color="primary" square />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="columns is-multiline is-12  pr-5  pb-0">
                                        <div class="column is-6">
                                            <h1 style="font-weight: bold;">Skoring nyeri (Wong Baker Faces)</h1>
                                            <div class="columns pt-4">
                                                <div class="column" style="text-align: center;"
                                                    v-for="(image, i) in listImageNyeri.detail">
                                                    <VAvatar size="medium" :picture="image.img"
                                                        :class="isAktive == i ? 'active' : ''" @click="test(image, i)" />
                                                    <p>{{ image.descNilai }}</p>
                                                    <p>{{ image.nama }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-6">
                                            <h1 style="font-weight: bold;">Score</h1>
                                            <div class="pt-4">
                                                <div class="columns is-multiline">
                                                    <VField v-for="(skor) in listSkoringNyeri.detail">
                                                        <VControl raw subcontrol class="p-0">
                                                            <VCheckbox class="pt-0" v-model="input.skoringNyeri"
                                                                :true-value="skor.descNilai" :label="skor.nama"
                                                                color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h1 style="font-weight: bold;"> Skala Nyeri</h1>
                                    <div class="column is-12">
                                        <h1 style="font-weight: bold; margin-bottom: 1rem;"> Penyebab</h1>
                                        <div class="columns is-9">

                                            <VField v-for="items in penyebab" :key="items.value">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input.penyebab" :true-value="items.nama"
                                                        :label="items.nama" color="primary" square />
                                                </VControl>
                                            </VField>


                                        </div>
                                    </div>
                                    <div class="column is-12">
                                        <h1 style="font-weight: bold; margin-bottom: 1rem;"> Rasa Nyeri</h1>
                                        <div class="columns is-12">
                                            <VField v-for="items in rasaNyeri" :key="items.value">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input.rasanyeri" :true-value="items.nama"
                                                        :label="items.nama" color="primary" square />
                                                </VControl>
                                            </VField>


                                        </div>
                                    </div>
                                    <div class="column is-12">
                                        <h1 style="font-weight: bold; margin-bottom: 1rem;"> Nyeri Menjalar</h1>
                                        <div class="columns is-9">

                                            <VField v-for="items in nyeriMenjalar" :key="items.value">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input.nyerimenjalar" :true-value="items.value"
                                                        :label="items.nama" color="primary" square />
                                                </VControl>
                                            </VField>


                                        </div>
                                    </div>
                                    <div class="column is-12">
                                        <h1 style="font-weight: bold; margin-bottom: 1rem;"> Lama Nyeri</h1>
                                        <div class="columns is-9">

                                            <VField v-for="items in nyeriLama" :key="items.value">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input.nyeriLama" :true-value="items.nama"
                                                        :label="items.nama" color="primary" square />
                                                </VControl>
                                            </VField>


                                        </div>
                                    </div>
                                    <div class="column is-12">
                                        <h1 style="font-weight: bold; margin-bottom: 1rem;"> Seberapa sering</h1>
                                        <div class="columns is-9">

                                            <VField v-for="items in nyeriLama" :key="items.value">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input.frekuensi" :true-value="items.nama"
                                                        :label="items.nama" color="primary" square />
                                                </VControl>
                                            </VField>


                                        </div>
                                    </div>
                                </VCard>

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
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/histori-triase'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let daftarList = ref(EMR.daftarList())

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
const isAktive = ref()
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('HistoriTriase') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
// list pilihan 
const listCaraMasuk = ref(['Sendiri', 'Ambulan', 'Diantar Polisi'])
const listAlatBantu = ref(['Jalan Kaki', 'Brankard', 'Kursi Roda', 'Tongkat / Walker'])
const listKasus = ref(['Non Trauma', 'Trauma'])
const JalanNapas = ref([
    { label: 'Sumbatan', value: 'Sumbatan' },
    { label: 'Bebas', value: 'Bebas' },
    { label: 'Ancaman Sumbatan', value: 'Bebas' },
])
let listImageNyeri = ref(
    {
        "nama": "Hurts", "detail": [
            {
                "nama": "No Hurt", "descNilai": 0,
                "img": "/images/skalanyeri/1.png"
            },
            {
                "nama": "Hurts Little Bit", "descNilai": 2,
                "img": "/images/skalanyeri/2.png",
            },
            {
                "nama": "Hurts Little More", "descNilai": 4,
                "img": "/images/skalanyeri/3.png",
            },
            {
                "nama": "Hurts Even More", "descNilai": 6,
                "img": "/images/skalanyeri/4.png",
            },
            {
                "nama": "Hurts Whole Lot", "descNilai": 8,
                "img": "/images/skalanyeri/5.png",
            },
            {
                "nama": "Hurts whorts", "descNilai": 10,
                "img": "/images/skalanyeri/6.png",
            }]
    }
)
let listSkoringNyeri: any = ref(
    {
        "nama": "Score ", "detail": [
            { "nama": "0 - 1 = Tidak Ada Nyeri", "descNilai": 0 },
            { "nama": "2 - 3 = Sedikit Nyeri", "descNilai": 2 },
            { "nama": "4 - 5 = Cukup Nyeri", "descNilai": 4 },
            { "nama": "6 - 7 = Lumayan Nyeri", "descNilai": 6 },
            { "nama": "8 - 9 = Sangat Nyeri", "descNilai": 8 },
            { "nama": "10 = Amat Sangat Nyeri", "descNilai": 10 },
        ]
    }
)
const pilihan = ref([
    { label: 'Ya', value: 'Ya' },
    { label: 'Tidak', value: 'Tidak' },
])
const metoda = ref([
    { label: 'Numeratic Rating Scale', value: 'Numeratic Rating Scale' },
    { label: 'Wong-Baker Faces', value: 'Wong-Baker Faces' },
])
const penyebab = ref([
    { "nama": "Sulit Dinilai", "value": "Sulit Dinilai" },
    { "nama": "Trauma Tajam", "value": "Trauma Tajam" },
    { "nama": "Trauma Tumpul", "value": "Trauma Tumpul" },
])
const rasaNyeri = ref([
    { "nama": "Sulit Dinilai ", "value": "Sulit Dinilai" },
    { "nama": "Seperti Tertimpa", "value": "Seperti Tertimpa" },
    { "nama": "Seperti Tertusuk", "value": "Seperti Tertusuk" },
    { "nama": "Seperti Terbakar", "value": "Seperti Terbakar" }

])
const nyeriMenjalar = ref([
    { "nama": "Sulit Dinilai ", "value": "Sulit Dinilai" },
    { "nama": "Tidak ", "value": "Tidak" },
    { "nama": "Ya", "value": "Ya" }
])
const nyeriLama = ref([
    { "nama": "< 3 bulan(akut)", "value": "< 3 bulan(akut)" },
    { "nama": "> 3 bulan(kronik)", "value": "> 3 bulan(kronik)" },
    { "nama": "Ya", "value": "Ya" },
])
const frekuensi = ref([
    { "nama": "< 30 menit", "value": "< 30 menit" },
    { "nama": "> 30 menit", "value": "> 30 menit" },
    { "nama": "1-2 jam", "value": "1-2 jam" },
    { "nama": "3-4 jam", "value": "3-4 jam" },
])
const pernapasan1 = ref([
    { "label": "Henti Nafas", "value": "Henti Nafas" },
    { "label": "Napas (< 10x/mnt)", "value": "Napas (< 10x/mnt)" },
    { "label": "Napas (> 32x/mnt)", "value": "Napas (> 32x/mnt)" },
])
const pernapasan2 = ref([
    { "label": "25 - 32 x/mnt", "value": "25 - 32 x/mnt" },
    { "label": "Whezing/ Mengi", "value": "Whezing/ Mengi" },
])
const pernapasan3 = ref([
    { "label": "Normal", "value": "Normal" },
    { "label": "Napas (10 - 24 x/mnt)", "value": "Napas (10 - 24 x/mnt)" },
])
const pernapasan4 = ref([
    { "label": "Henti Napas", "value": "Henti Napas" },
])
const sirkulasi1 = ref([
    { "label": "Henti Jantung", "value": "Henti Jantung" },
    { "label": "Nadi Lemah", "value": "Nadi Lemah" },
    { "label": "Nadi (< 50x/mnt)", "value": "Nadi (< 50x/mnt)" },
    { "label": "Nadi (< 120x/mnt)", "value": "Nadi (< 120x/mnt)" },
    { "label": "Akral Dingin", "value": "Akral Dingin" },
    { "label": "CRT >2 detik", "value": "CRT >2 detik" },
    { "label": "Nyeri Dada (iskemik)", "value": "Nyeri Dada (iskemik)" },
])
const sirkulasi2 = ref([
    { "label": "Nadi Kuat", "value": "Nadi Kuat" },
    { "label": "Nadi (110 - 120x/mnt) ", "value": "Nadi (110 - 120x/mnt)  " },
    { "label": "Nadi (51 - 59x/mnt)", "value": "Nadi (51 - 59x/mnt)" },
    { "label": "Akral Hangat", "value": "Akral Hangat" },
    { "label": "CRT <2 detik", "value": "CRT <2 detik" },
    { "label": "Skala Nyeri >=4", "value": "Skala Nyeri >=4" },
    { "label": "Sistol > 160 mmHg", "value": "Sistol > 160 mmHg" },
    { "label": "Diastol > 100 mmHg", "value": "Diastol > 100 mmHg" }
])
const sirkulasi3 = ref([
    { "label": "Nadi Kuat", "value": "Nadi Kuat" },
    { "label": "Nadi (60 - 100x/mnt)", "value": "Nadi (60 - 100x/mnt)" },
    { "label": "Akral Hangat", "value": "Akral Hangat" },
    { "label": "CRT < 2 detik", "value": "CRT < 2 detik" },
    { "label": "Skala Nyeri <= 3", "value": "Skala Nyeri <= 3" },
])
const sirkulasi4 = ref([
    { "label": "Henti Jantung", "value": "Henti Jantung" },
    { "label": "EKG Asistol", "value": "EKG Asistol" }
])
const kesadaran = ref([
    { "label": "GCS <=12", "value": "GCS <=12" },
    { "label": "Kejang", "value": "Kejang" },
    { "label": "Gelisah", "value": "Gelisah" }
])
const kesadaran1 = ref([
    { "label": "GCS > 12", "value": "GCS > 12" }
])
const kesadaran2 = ref([
    { "label": "GCS = 15", "value": "GCS = 15" }
])
const kesadaran3 = ref([
    { "label": "GCS = 15", "value": "GCS = 15" }
])
const kesadaran4 = ref([
    { "label": "GCS 3", "value": "GCS 3" },
    { "label": "Pupil", "value": "Pupil" },
    { "label": "Madriasis Max", "value": "Madriasis Max" },
])


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
const setAutoFill = async () => {
    await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN +
        "&collection=VitalSign" +
        "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
    ).then((response) => {

        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
        input.value.IMT = response.IMT
        input.value.lingkarPerut = response.lingkarPerut
        input.value.tekananDarah = response.tekananDarah
        input.value.pernapasan = response.pernapasan
        input.value.suhu = response.suhu
        input.value.nadi = response.nadi
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
const test = (e: any, i: any) => {

    let listSkor = listSkoringNyeri.value.detail

    listSkor.forEach((element: any) => {
        if (element.descNilai == e.descNilai) {
            input.value.skoringNyeri = e.descNilai
        }
    });
    isAktive.value = i

}

setAutoFill()
setView()
loadRiwayat()
</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.tc {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tc td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 1px 1px;
    
    word-break: normal;
}

.tc th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
    vertical-align: center;
}

.tc .tg-0lax {
    text-align: left;
    vertical-align: top
}

.dashed-line {
    border: 1px dashed grey;
}

.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}
</style>
