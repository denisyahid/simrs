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
                <h1 style="font-weight: bold; text-align: center;"> Hak dan Kewajiban Pasien 007.0/FORM/7/RMIK/2022/Rev. 00
                </h1>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h1 class="mt-3 mb-1" style="font-weight: bold;"> Hak Pasien sesuai dengan UU Republik Indonesia No.
                            44 Tahun 2009 Tentang Rumah Sakit</h1>
                        <div class="column is-12">
                            <p>
                                1. Saya menyetujui untuk perawatan di Rumah Sakit ini sebagai pasien ruang perawatan
                                intensif
                            </p>
                            <p>
                                2. Memperoleh informasi tentang hak dan kewajiban pasien.
                            </p>
                            <p>
                                3. Memperoleh layanan yang manusiawi, adil, jujur, dan tanpa diskriminasi.
                            </p>
                            <p>
                                4. Memperoleh layanan kesehatan yang bermutu sesuai dengan standar profesi dan standar
                                prosedur operasional.
                            </p>
                            <p>
                                5. Memperoleh layanan yang efektif dan efisien sehingga pasien terhindar dari kerugian
                                fisik dan materi.
                            </p>
                            <p>
                                6. Mengajukan pengaduan atas kualitas pelayanan yang didapatkan
                            </p>
                            <p>
                                7. Memilih dokter dan kelas perawatan sesuai dengan keinginannya dan peraturan yang
                                berlaku di Rumah Sakit.
                            </p>
                            <p>
                                8. Meminta konsultasi tentang penyakit yang dideritanya kepada dokter lain (second
                                opinion) yang mempunyai Surat Izin Prakter (SIP) baik di dalam maupun di luar Rumah
                                Sakit.
                            </p>
                            <p>
                                9. Mendapatkan privasi dan kerahasiaan penyakit yang diderita termasuk data-data
                                medisnya.
                            </p>
                            <p>
                                10. Memberikan persetujuan atau menolak atas tindakan yang akan dilakukan oleh tenaga
                                kesehatan terhadapat penyakit yang dideritanya.
                            </p>
                            <p>
                                11. Mendapat informasi yang meilputi diagnosis dan tata cara tindakan medis, tujuan
                                tindakan medis, alternatif tindakan, risiko dan komplikasi yang mungkin terjadi, dan
                                prognosis terhadap tindakan yang dilakukan serta perkiraan biaya pengobatan.
                            </p>
                            <p>
                                12. Didampingi keluarganya dalam keadaan kritis.
                            </p>
                            <p>
                                13. Menjalankan ibadah sesuai agama atau kepercayaan yang dianutnya selama hal itu tidak
                                mengganggu pasien lainnya.
                            </p>
                            <p>
                                14. Memperoleh keamanan dan keselamatan dirinya selama dalam perawatan Rumah Sakit.
                            </p>
                            <p>
                                15. Mengajukan usul, saran, perbaikan atas perlakukan Rumah Sakit terhadap dirinya.
                            </p>
                            <p>
                                16. Menolak layanan bimbingan rohani yang tidak sesuai dengan agama dan kepercayaan yang
                                dianutnya.
                            </p>
                            <p>
                                17. Menggugat dan / atau menuntut rumah sakit apabila rumah sakit diduga memberikan
                                pelayanan yang tidak sesuai dengan standar baik secara perdata maupun pidana.
                            </p>
                        </div>


                        <h1 class="mt-3 mb-1" style="font-weight: bold;"> Kewajiban Pasien sesuai dengan UU Republik
                            Indonesia No. 29 Tahun 2004 Tentang Prakter Kedokteran

                        </h1>
                        <div class="column is-12">
                            <p>
                                Kewajiban pasien adakah sesuatu yang harus diperbarui atau yang harus dilakukan oleh
                                pasien
                            </p>
                            <p>
                                1. Memberikan informasi yang lengkap dan jujur tentang masalah kesehatannya kepada
                                dokter yang merawat.
                            </p>
                            <p>
                                2. Mematuhi nasihat dokter atau dokter gigi dan perawat dalam pengobatannya.
                            </p>
                            <p>
                                3. Mematuhi ketentuan / peraturan dan tata tertib yang berlaku di Rumah Sakit.
                            </p>
                            <p>
                                4. Memberikan imbalan jasa atau pelayanan yang diterima.
                            </p>
                            <p>
                                5. Berkewajiban memenuhi hal-hal yang telah disepakati / perjanjian yang telah dibuatnya

                            </p>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;"> Tanggal </h1>
                            <VField>
                                <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
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
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                                </div>
                                <div class="column is-6 ">
                                    <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                                </div>
                                <div class="column is-4 ">
                                    <h1 class="p-0" style="font-weight: bold;">Yang membuat pernyataan</h1>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Yang membuat pernyataan"
                                                v-model="input.yangMembuatPernyataan" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4 is-offset-2">
                                    <h1 class="p-0" style="font-weight: bold;">Mengetahui Petugas Pendaftaran</h1>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.petugasPendaftaran" :suggestions="d_Dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="Petugas..." class="mt-2"
                                                @item-select="setTandaTangan($event)" />
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
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
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
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const d_ruangan: any = ref([])
const d_agama: any = ref([])
const d_Dokter: any = ref([])
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
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
                input.value = response[0] //set ke inputan
                if (response[0].tandaTanganPasien) {
                    H.tandaTangan().set("signature_1", response[0].tandaTanganPasien)
                }
                if (response[0].tandaTanganPetugas) {
                    H.tandaTangan().set("signature_2", response[0].tandaTanganPetugas)
                }
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}
const fetchAgama = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/agama_m?select=id,agama&param_search=agama&query=${filter.query}&limit=10`)
    d_agama.value = response
}
const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_ruangan.value = response
}
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Dokter.value = response
}
const setTandaTangan = async (e: any) => {
    const response = await useApi().get(
        `/emr/tanda-tangan/${e.value.value}`)
    if (response != null) {
        H.tandaTangan().set("signature_2", response.ttd)
        input.value.tandaTanganPetugas = response.ttd
    } else {
        H.tandaTangan().set("signature_2", '')
    }
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.tandaTanganPasien = H.tandaTangan().get("signature_1")
    object.tandaTanganPetugas = H.tandaTangan().get("signature_2")
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
    input.value.jenisKelaminPasien = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.telepon = props.pasien.nohp
    input.value.alamatPasien = props.pasien.alamatlengkap
    input.value.dokterRawat = props.registrasi.dokter
    input.value.pihakPembayar = props.registrasi.kelompokpasien
    input.value.tglPembuatan = new Date()
}
setView()
setAutoFill()
loadRiwayat()
</script>
<style>
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

.buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin-top: 8px;
}
</style>
