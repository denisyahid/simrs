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
                <h1 style="font-weight: bold; text-align: center;"> PASIEN DAN/ATAU WALI HUKUM HARUS MEMBACA, MEMAHAMI DAN
                    MENGISI INFORMASI BERIKUT
                </h1>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h1 class="mt-5 mb-1" style="font-weight: bold;"> Yang bertanda tangan dibawah ini :</h1>
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
                                    <h1 style="font-weight: bold;"> Alamat : </h1>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Alamat" v-model="input.alamat" />
                                        </VControl>

                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 p-1">
                            <div class="is-flex">
                                <div class="column is-2" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> No. Telepon : </h1>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder="No Telepon"
                                                v-model="input.notlp" />
                                        </VControl>

                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 ">
                            <p> Selaku Pasien/Wali hukum Rumah Sakit ini dengan menyatakan persetujuan
                            </p>
                        </div>
                        <h1 class="mt-3 mb-1" style="font-weight: bold;"> A. PERSETUJUAN UNTUK PERAWATAN DAN PENGOBATAN</h1>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold">
                                    1. Saya menyetujui untuk perawatan di Rumah Sakit ini sebagai pasien ruang perawatan
                                    intensif
                                </h1>
                                <div class="column is-3" style="margin-top:-1rem;">
                                    <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="fa:home" fullwidth class="prime-auto ">
                                            <AutoComplete v-model="input.hubunganPenjamin" :suggestions="d_ruangan"
                                                @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik untuk mencari..." />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                        </div>
                        <div class="column is-12 ">
                            <h1 style="font-weight: bold">
                                2. Saya mengetahui bahwa pasien/saya memiliki kondisi yang membutuhkan perawatan medis,
                                pasien/saya mengizinkan dokter dan profesional tenaga kesehatan lainnya untuk melakukan
                                prosedur diagnostik dan untuk memberikan pengobatan medis seperti yang dilakukan dalam
                                profesional mereka. Prosedur diagnostik dan perawatan medis termasuk terapi tidak terbatas
                                pada EKG, X-RAY, tes darah, terapi fisik, pemberian obat suntik dan cairan infus.Persetujuan
                                yang saya berikan tidak termasuk persetujuan untuk prosedur/ tindakan invasif (misalnya,
                                operasi) atau tindakan yang mempunyai resiko tinggi.
                            </h1>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold">
                                3. Saya sadar bahwa praktek kedokteran dan bedah bukan ilmu pasti dan saya mengakui tidak
                                ada jaminan atas hasil apapun terhadap perawatan prosedur atau pemeriksaan apapun yang
                                dilakukan kepada pasien/saya.
                            </h1>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold">
                                4. Saya mengerti dan memahami bahwa :
                            </h1>
                            <p class="ml-3 mt-2 mb-1"> a. Saya memiliki hak untuk mengajukan pertanyaan tentang pengobatan
                                yang diusulkan ( termasuk identitas setiap orang yang memberikan atau mengamati pengobatan )
                                setiap saat.</p>
                            <p class="ml-3 mt-2 mb-1"> b. Saya mengerti dan memahami bahwa saya memiliki hak untuk
                                persetujuan atau menolak persetujuan untuk setiap prosedur / tindakan invasif (misalnya
                                operasi) atau tindakan yang mempunyai resiko tinggi. Jika saya memutuskan untuk menghentikan
                                perawatan medis untuk diri saya sendiri. Saya memahami dan menyadari bahwa Rumah Sakit ini
                                atau dokter tidak bertanggungjawab atas hasil yang merugikan saya.

                            </p>
                        </div>

                        <h1 class="mt-3 mb-1" style="font-weight: bold;"> B. PERSETUJUAN PELEPASAN INFORMASI</h1>

                        <div class="column is-12 p-0">
                            <h1 class="mt-3 mb-1 ml-3" style="font-weight: bold;">
                                Saya memahami informasi yang ada didalam diri saya, termasuk diagnosis, hasil laboratorium
                                dan hasil tes diagnostik yang akan digunakan untuk perawatan medis, Rumah Sakit ini akan
                                menjamin kerahasiaannya. Saya memberi wewenang kepada RS untuk memberikan informasi tentang
                                tentang diagnosis, hasil pelayanan dan pengobatan bila diperlukan untuk memproses klaim
                                asuransi / perusahaan dan atau lembaga pemerintah. Sesuai kewajiban simpan rahasia
                                kedokteran dan mengacu pada peraturan menteri kesehatan refublik indonesia no.
                                36/MENKES/III/2008, Saya memberi wewenang kepada Rumah Sakit ini untuk memberikan informasi
                                tentang diagnosis, hasil pelayanan dan pengobatan saya kepada anggota keluarga saya dan
                                kepada:
                            </h1>
                            <div class="is-flex">
                                <div class="column is-2" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Nama : </h1>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Nama Lengkap"
                                                v-model="input.namaLengkap" />
                                        </VControl>

                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12 p-0">
                            <div class="is-flex">
                                <div class="column is-2" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Telephone : </h1>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder="Telepon"
                                                v-model="input.telepon" />
                                        </VControl>

                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12 p-0">
                            <div class="is-flex">
                                <div class="column is-2" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Hubungan dengan Pasien : </h1>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Alamat" v-model="input.norm" />
                                        </VControl>

                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 p-0">
                            <div class="is-flex">
                                <div class="column is-2" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Nama : </h1>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Nama Lengkap"
                                                v-model="input.namaLengkap" />
                                        </VControl>

                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 p-0">
                            <div class="is-flex">
                                <div class="column is-2" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Telephone : </h1>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VInput type="number" class="input" placeholder="Telepon"
                                                v-model="input.telepon" />
                                        </VControl>

                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12 p-0">
                            <div class="is-flex">
                                <div class="column is-2" style="margin-top:0.5rem">
                                    <h1 style="font-weight: bold;"> Hubungan dengan Pasien : </h1>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Alamat"
                                                v-model="input.hubunganDenganPasien" />
                                        </VControl>

                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12 p-1">
                            <h1 style="font-weight: bold;">
                                Saya menyatakan bahwa pernyataan diatas dibuat dengan penuh kesadaran dan tanpa paksaan.
                            </h1>

                        </div>
                        <div class="column is-12 p-1">
                            <h1 style="font-weight: bold;">
                                C. HAK DAN TANGGUNG JAWAB PASIEN
                            </h1>

                        </div>
                        <div class="column is-12 p-1">
                            <h1>
                                Saya memiliki hak untuk mengambil bagian dalam keputusan mengenai penyakit saya dan dalam
                                hal perawatan medis dan rencana pengobatan. Saya telah mendapat informasi tentang “Hak dan
                                tanggungjawab pasien” di Rumah Sakit ini melalui Leaflet dan banner yang disediakan oleh
                                petugas.</h1>
                        </div>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold">
                                    Saya memiliki hak untuk mendapatkan pelayanan kerohanian sesuai agama dan kepercayaan
                                    yang saya anut, agama saya
                                </h1>
                                <div class="column is-3" style="margin-top:-1rem;">
                                    <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="fa:bookmark" fullwidth class="prime-auto ">
                                            <AutoComplete v-model="input.agama" :suggestions="d_agama"
                                                @complete="fetchAgama($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="ketik untuk mencari..." />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold">
                                Saya / pasien memahami bahwa Rumah Sakit ini tidak bertanggungjawab atas kehilangan
                                barang-barang pribadi dan barang berharga yang dibawa ke Rumah Sakit.
                            </h1>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold">
                                D. INFORMASI RAWAT INAP
                            </h1>
                            <div class="column is-12">
                                <h1 style="font-weight: bold;  text-align: justify;">
                                    Saya tidak diperkenankan untuk membawa barang-barang berharga keruang rawat inap, jika
                                    ada anggota keluarga atau teman harus diminta untuk membawa pulang uang atau perhiasan.
                                    Bila tidak ada anggota keluarga, RS sakit menyediakan tempat penitipan barang milik
                                    pasien ditempat resmi yang telah disediakan RS. Saya telah menerima informasi tentang
                                    peraturan yang diberlakukan oleh Rumah Sakit dan saya beserta keluarga bersedia untuk
                                    mematuhinya, termasuk akan mematuhi jam berkunjung pasien sesuai dengan aturan di rumah
                                    sakit.
                                </h1>
                            </div>
                            <div class="column is-12">
                                <h1 style="font-weight: bold; text-align: justify;">Anggota keluarga pasien/saya yang
                                    menunggu pasien (sebanyak 1 orang) bersedia untuk selalu memakai tanda pengenal khusus
                                    yang diberikan oleh Rumah Sakit ini dan demi keamanan seluruh pasien setiap keluarga dan
                                    siapapun yang akan megunjungi pasien/saya diluar jam berkunjung bersedia untuk
                                    diminta/diperiksa identitasnya dan memakai identitias yang diberikan oleh Rumah Sakit.
                                    Saya tidak diperkenankan untuk membawa barang-barang berharga keruang rawat inap, jika
                                    ada anggota keluarga atau teman harus diminta untuk membawa pulang uang atau perhiasan.
                                    Bila tidak ada anggota keluarga, RS sakit menyediakan tempat penitipan barang milik
                                    pasien ditempat resmi yang telah disediakan RS. Saya telah menerima informasi tentang
                                    peraturan yang diberlakukan oleh Rumah Sakit dan saya beserta keluarga bersedia untuk
                                    mematuhinya, termasuk akan mematuhi jam berkunjung pasien sesuai dengan aturan di rumah
                                    sakit.
                                </h1>
                            </div>


                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold">
                                E. PRIVASI
                            </h1>
                        </div>
                        <div class="column is-12">
                            <h1>
                                Saya megijinkan / tidak mengijinkan (coret salah satu) Rumah Sakit memberikan akses bagi :
                                keluarga dan handai taulan serta orang-orang yang akan menjenguk pasien/saya saat jam
                                berkunjung (sebutkan nama bila ada permintaan khusus yang tidak diijinkan)
                            </h1>
                            <VField>

                                <VControl>
                                    <VInput type="text" v-model="input.nadi" placeholder="Nadi.." />
                                </VControl>
                            </VField>

                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold">
                                F.INFORMASI BIAYA
                            </h1>
                        </div>
                        <div class="column is-12">
                            <h1>
                                Pihak Pembayar :
                            </h1>
                            <VField>

                                <VControl>
                                    <VInput type="text" v-model="input.pihakPembayar" placeholder="Pihak Pembayar.." />
                                </VControl>
                            </VField>

                        </div>


                        <div class="column is-12 p-1">
                            <h1 style="font-weight: bold;">
                                Demikian pernyataan ini saya buat dengan sebenernya dan penuh kesadaran tanpa paksaan dari
                                pihak manapun.
                            </h1>
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
                                <div class="column is-4">
                                    <h1 class="p-0" style="font-weight: bold;">Yang Menjelaskan </h1>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.petugasPerawat" :suggestions="d_Dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="Yang Menjelaskan..." class="mt-2"
                                                @item-select="setTandaTangan($event)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4 is-offset-2">
                                    <h1 class="p-0" style="font-weight: bold;">Pasien/Penanggung Jawab Pasien</h1>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Pasien/Penanggung Jawab Pasien"
                                                v-model="input.yangMembuatPernyataan" />
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

// List Pilihan
const JenisKelamin: any = ref([
    { label: 'Laki - Laki', value: 'Laki - Laki' },
    { label: 'Perempuan', value: 'Perempuan' }
])
const dokterJabatan: any = ref([
    { label: 'Umum', value: 'Umum' },
    { label: 'Specialis', value: 'Specialis' },
    { label: 'Subspecialis', value: 'Subspecialis' }
])

const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (response[0].tandaTanganPerawat) {
                    H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
                }
                if (response[0].tandaTanganPasien) {
                    H.tandaTangan().set("signature_2", response[0].tandaTanganPasien)
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
        H.tandaTangan().set("signature_1", response.ttd)
        input.value.tandaTanganPerawat = response.ttd
    } else {
        H.tandaTangan().set("signature_1", '')
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
    object.tandaTanganPasien = H.tandaTangan().get("signature_2")
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
