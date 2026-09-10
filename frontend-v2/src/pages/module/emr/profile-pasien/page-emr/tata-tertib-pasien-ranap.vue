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
                <h1 style="font-weight: bold; text-align: center;">021.0/FORM/7/RMIK/2022/Rev. 00
                </h1>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h1 class="mt-3 mb-1" style="font-weight: bold;">1. Pintu masuk dan keluar hanya melalui pintu utama
                           .</h1>
                        <h1 class="mt-3 mb-1" style="font-weight: bold;">2. Ketentuan waktu berkunjung.</h1>
                        <div class="column is-12">
                            <p>
                                Waktu berkunjung pasien :
                            </p>
                            <p>
                                Senin - Sabtu
                            </p>
                            <p>
                                16.00 - 17.00
                            </p>
                            <p>
                                Minggu dan Hari Libur Nasional
                            </p>
                            <p>
                                Pagi 11.00 - 12.00
                            </p>
                            <p>
                                Sore 16.00 - 17.00
                            </p>
                            <p>
                                Pengunjung pasien yang dirawat di ruang intensive tidak diperkenankan masuk ke ruang
                                intensive, kecuali keluarga dan telah mendapat ijin dari dokter/perawat di ruang intensive.
                            </p>
                            <p>
                                Pengunjung hanya diperbolehkan menunggu di ruang tunggu yang telah disediakan.


                            </p>

                        </div>


                        <h1 class="mt-3 mb-1" style="font-weight: bold;">
                            3. Untuk kepentingan kesehatan, anak dibawah umur 12 (dua belas) tahun tidak
                            diiizinkan/diperkenankan memasuki area ruang rawat inap.

                        </h1>
                        <h1 class="mt-3 mb-1" style="font-weight: bold;">
                            4. Untuk keselamatan dan kenyamanan pasien :
                        </h1>


                        <div class="column is-12">
                            <p>
                                a. Penunggu pasien dibatasi hanay 1 (satu) orang.
                            </p>
                            <p>

                                b. Jumlah pengunjung yang masuk ruang perawatan pada saat bersamaan tidak lebih dari 2 (dua)
                                orang.
                            </p>
                            <p>
                                c. Pengunjung dalam keadaan sehat.
                            </p>
                            <p>

                                d. Pengunjung menjaga ketenangan saat berbicara.


                            </p>

                        </div>
                        <h1 class="mt-3 mb-1" style="font-weight: bold;">
                            5. Tidak diperkenankan membawa barang berharga/perhiasan,tikar/alas tidur dan alat/barang
                            elektronik. Rumah Sakit tidak bertanggung jawab atas kehilangan, pencurian atau kerusakan
                            terhadap barang tersebut.

                        </h1>
                        <h1 class="mt-3 mb-1" style="font-weight: bold;">
                            6. Dilarang merokok selama berada di lingkungan rumah sakit termasuk di area parkir

                        </h1>
                        <h1 class="mt-3 mb-1" style="font-weight: bold;">
                            7. Dilarang membawa bahan berbahaya, narkoba, minuman keras, senjata api, senjata tajam, hewan
                            peliharaan, dan lain-lain yang membahayakan ke dalam area rumah sakit.
                        </h1>
                        <h1 class="mt-3 mb-1" style="font-weight: bold;">
                            8. Dilarang mengambil gambar/ foto, video dan suara pasien dan seluruh karyawan rumah sakit.
                        </h1>
                        <h1 class="mt-3 mb-1" style="font-weight: bold;">
                            9. Dilarang melakukan perbuatan yang melanggar hukum di area rumah sakit termasuk tindakan
                            asusila.
                        </h1>
                        <h1 class="mt-3 mb-1" style="font-weight: bold;">
                            10. Penunggu pasien wajib menggunakan tanda pengenal selama berada di area Rumah Sakit.
                        </h1>
                        <h1 class="mt-3 mb-1" style="font-weight: bold;">
                            11. Seluruh barang inventaris kamar perawatan adalah milik Rumah Sakit. Apabila terjadi
                            kerusakan atau kehilangan, maka pasien wajib mengganti sesuai dengan ketentuan yang berlaku.


                        </h1>

                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                                </div>
                                <div class="column is-6 ">
                                    <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                                </div>
                                <div class="column is-4 ">
                                    <h1 class="p-0" style="font-weight: bold;">Pasien/Penanggung Jawab Pasien
                                    </h1>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Pasien/Penanggung Jawab Pasien" v-model="input.pasienPenanggungJawab" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4 is-offset-2">
                                    <h1 class="p-0" style="font-weight: bold;">Yang Menjelaskan</h1>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.yangMenjelaskan" :suggestions="d_Dokter"
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
