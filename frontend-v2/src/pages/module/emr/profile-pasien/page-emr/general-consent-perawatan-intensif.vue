<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> Persetujuan Umum/General Consent Intensif</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
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
            <h1 style="font-weight: bold;" class="is-pulled-right"> 003.16/FORM/7/RMIK/2022/Rev. 00</h1>
          </div>
          <div class="column is-12">
            <h1 style="font-weight: bold;">Yang bertanda tangan di bawah ini
            </h1>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Nama : </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nama " v-model="input.namaPasien" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>


            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Alamat : </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Alamat " v-model="input.alamatPasien" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> No Telp : </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="No Telp " v-model="input.noTelp" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <h1 style="font-weight: bold;">
              Selaku Pasien/Wali hukum Rumah Sakit ini dengan menyatakan persetujuan
            </h1>
            <h1 style="font-weight: bold;" class="mt-3">
              A. PERSETUJUAN UNTUK PERAWATAN DAN PENGOBATAN
            </h1>
            <div class="column is-12 p-0 ml-2">
              <div class="columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span>
                    1. Saya menyetujui untuk perawatan di Rumah Sakit ini sebagai pasien ruang perawatan intensif</span>
                </div>
                <div class="column is-4 pt-0">
                  <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="fa:users" fullwidth class="prime-auto ">
                      <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">

              <span>
                2. Saya mengetahui bahwa pasien/saya memiliki kondisi yang membutuhkan perawatan medis, pasien/saya
                mengizinkan dokter dan profesional tenaga kesehatan lainnya untuk melakukan prosedur diagnostik dan
                untuk memberikan pengobatan medis seperti yang dilakukan dalam profesional mereka. Prosedur diagnostik
                dan perawatan medis termasuk terapi tidak terbatas pada EKG, X-RAY, tes darah, terapi fisik, pemberian
                obat suntik dan cairan infus.Persetujuan yang saya berikan tidak termasuk persetujuan untuk prosedur/
                tindakan invasif (misalnya, operasi) atau tindakan yang mempunyai resiko tinggi.</span>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>
                3. Saya sadar bahwa praktek kedokteran dan bedah bukan ilmu pasti dan saya mengakui tidak ada jaminan atas
                hasil apapun terhadap perawatan prosedur atau pemeriksaan apapun yang dilakukan kepada pasien/saya.</span>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>
                4. Saya mengerti dan memahami bahwa :</span>

              <div class="column is-12 p-0 mt-1 ml-2">
                <span>
                  a. Saya memiliki hak untuk mengajukan pertanyaan tentang pengobatan yang diusulkan ( termasuk identitas
                  setiap orang yang memberikan atau mengamati pengobatan ) setiap saat.</span>
              </div>
              <div class="column is-12 p-0 mt-1 ml-2">
                <span>
                  b. Saya mengerti dan memahami bahwa saya memiliki hak untuk persetujuan atau menolak persetujuan untuk
                  setiap prosedur / tindakan invasif (misalnya operasi) atau tindakan yang mempunyai resiko tinggi. Jika
                  saya memutuskan untuk menghentikan perawatan medis untuk diri saya sendiri. Saya memahami dan menyadari
                  bahwa Rumah Sakit ini atau dokter tidak bertanggungjawab atas hasil yang merugikan saya.
                </span>
              </div>
            </div>
            <h1 style="font-weight: bold;" class="mt-3">
              B. PERSETUJUAN PELEPASAN INFORMASI
            </h1>
            <div class="column is-12 p-0 mt-1 ml-2">

              <span>
                Saya memahami informasi yang ada didalam diri saya, termasuk diagnosis, hasil laboratorium dan hasil tes
                diagnostik yang akan digunakan untuk perawatan medis, Rumah Sakit ini akan menjamin kerahasiaannya. Saya
                memberi wewenang kepada RS untuk memberikan informasi tentang tentang diagnosis, hasil pelayanan dan
                pengobatan bila diperlukan untuk memproses klaim asuransi / perusahaan dan atau lembaga pemerintah. Sesuai
                kewajiban simpan rahasia kedokteran dan mengacu pada peraturan menteri kesehatan refublik indonesia no.
                36/MENKES/III/2008, Saya memberi wewenang kepada Rumah Sakit ini untuk memberikan informasi tentang
                diagnosis, hasil pelayanan dan pengobatan saya kepada anggota keluarga saya dan kepada:</span>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Nama : </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nama " v-model="input.nama" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Telephone : </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Telephone " v-model="input.telephone" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Hubungan dengan Pasien : </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder=" Hubungan dengan Pasien  "
                        v-model="input.hubunganDgPasien" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <span>Saya menyatakan bahwa pernyataan diatas dibuat dengan penuh kesadaran dan tanpa paksaan. </span>
            </div>
            <h1 style="font-weight: bold;" class="mt-3">
              C. HAK DAN TANGGUNG JAWAB PASIEN
            </h1>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>
                Saya memiliki hak untuk mengambil bagian dalam keputusan mengenai penyakit saya dan dalam hal perawatan
                medis dan rencana pengobatan. Saya telah mendapat informasi tentang “Hak dan tanggungjawab pasien” di Rumah Sakit Bali Mandara melalui Leaflet dan banner yang disediakan oleh petugas.</span>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>

                Saya memiliki hak untuk mendapatkan pelayanan kerohanian sesuai agama dan kepercayaan yang saya anut,
                agama pasien/saya</span>
            </div>
            <div class="column is-6 p-0 mt-1 ml-2">

              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder=" " v-model="input.hakAgama" />
                </VControl>
              </VField>


            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>

                Saya/pasien memahami bahwa Rumah Sakit ini tidak bertanggungjawab atas kehilangan barang-barang pribadi dan
                barang berharga yang dibawa ke Rumah Sakit.</span>
            </div>
            <h1 style="font-weight: bold;" class="mt-3">
              D. INFORMASI RAWAT INAP
            </h1>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>
                Saya tidak diperkenankan untuk membawa barang-barang berharga keruang perawatan intensif, jika ada anggota
                keluarga atau teman harus diminta untuk membawa pulang. Bila tidak ada anggota keluarga, Rumah Sakit ini
                menyediakan tempat penitipan barang milik pasien ditempat resmi yang telah disediakan RS. Saya telah
                menerima informasi tentang peraturan yang diberlakukan oleh Rumah Sakit dan saya beserta keluarga bersedia
                untuk mematuhinya, termasuk akan mematuhi jam berkunjung pasien sesuai dengan aturan di rumah
                sakit.
              </span>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>
                Anggota keluarga pasien tidak dapat menunggu di dalam ruangan perawatan intensif, namun dapat menunggu di
                ruang yang sudah disediakan RS. Penunggu pasien bersedia untuk selalu memakai tanda pengenal khusus yang
                diberikan oleh Rumah Sakit ini dan demi keamanan, seluruh pasien setiap keluarga dan siapapun yang akan
                mengunjungi pasien / saya diluar jam berkunjung bersedia untuk diminta / diperiksa identitasnya.

              </span>
            </div>
            <h1 style="font-weight: bold;" class="mt-3">
              E. PRIVASI
            </h1>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>
              </span>
            </div>
            <div class="column is-6 p-0 mt-1 ml-2">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder=" " v-model="input.privasi" />
                </VControl>
              </VField>
            </div>
            <h1 style="font-weight: bold;" class="mt-3">
              F. INFORMASI BIAYA
            </h1>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>Pihak Pembayar :
              </span>
            </div>
            <div class="column is-6 p-0 mt-1 ml-2">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder=" " v-model="input.pihakPembayar" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>Pribadi</span>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>Saya berkewajiban untuk membayar biaya perawatan yang telah diberikan oleh Rumah Sakit ini</span>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>Jaminan : </span>
            </div>
            <div class="column is-6 p-0 mt-1 ml-2">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder=" " v-model="input.jaminan" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>Saya akan tunduk pada ketentuan yang ditetapkan oleh badan penjamin/asuransi yang akan membiayai
                perawatan saya.</span>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>Saya memahami tentang informasi biaya pengobatan atau biaya tindakan yang dijelaskan oleh petugas
                Rumah Sakit</span>
            </div>
            <div class="column is-12 p-0 mt-1 ml-2">
              <span>
                Dengan tandatangan saya dibawah, saya menyatakan bahwa saya telah membaca dan memahami item pada
                Persetujuan Umum / General Consent.

              </span>
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
                      <AutoComplete v-model="input.yangMenjelaskan" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Yang Menjelaskan..." class="mt-2" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4 is-offset-2">
                  <h1 class="p-0" style="font-weight: bold;">Pasien/Penanggung Jawab Pasien</h1>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Pasien/Penanggung Jawab Pasien" v-model="input.pasienPenanggungJawab" />
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
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const user = useUserSession().getUser().pegawai
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
const d_Hubungan: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
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
const d_Kelas: any = ref([
  { label: 'KELAS 1', value: 'KELAS 1' },
  { label: 'KELAS 2', value: 'KELAS 2' },
  { label: 'KELAS 3', value: 'KELAS 3' }
])
// List Pilihan
const d_Keterangan: any = ref([
  { label: 'Atas Permintaan Sendiri', value: 'Atas Permintaan Sendiri' },
  { label: 'Kelas Sesuai Jaminan Penuh', value: 'Kelas Sesuai Jaminan Penuh' }
])
const dokterJabatan: any = ref([
  { label: 'Umum', value: 'Umum' },
  { label: 'Specialis', value: 'Specialis' },
  { label: 'Subspecialis', value: 'Subspecialis' }
])
const dataTTD: any = ref([])
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        // if (response[0].tandaTanganPerawat) {
        //   H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
        // }
        // if (response[0].tandaTanganPasien) {
        //   H.tandaTangan().set("signature_2", response[0].tandaTanganPasien)
        // }
        dataTTD.value = response[0]
        H.tandaTangan().set("signature_1", dataTTD.value.tandaTanganPerawat)
        H.tandaTangan().set("signature_2", dataTTD.value.tandaTanganPasien)
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      } else {
        setAutoFill()
      }
    })
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const fetchDokter = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Dokter.value = response
}
const fetchDropdown = async () => {
  // const response = await useApi().get(
  //   `/emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=&limit=10`)
  // d_KelompokPasien.value = response
  const response = await useApi().get(
    `/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=&limit=10`)
  d_Hubungan.value = response
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
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}
function setAutoFill () {
  input.value.yangMenjelaskan = { label: user.namaLengkap, value: user.id }
  input.value.nama = props.pasien.namapasien
  input.value.jenisKelaminPasien = props.pasien.jeniskelamin
  input.value.telephone = props.pasien.nohp
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  // input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.ruangan = props.registrasi.namaruangan
  input.value.jaminan = props.registrasi.namarekanan
  input.value.dirawatDiRuang = props.registrasi.namaruangan
  input.value.hubunganDgPasien = props.pasien.hubungankeluarga ?? '-'
  input.value.pihakPembayar = props.registrasi.kelompokpasien
  input.value.tglPembuatan = new Date()
  input.value.tglDirawat = props.registrasi.tglregistrasi
}
// const setTandaTangan = async (e: any) => {
//   const response = await useApi().get(
//     `/emr/tanda-tangan/${e.value.value}`)
//   if (response != null) {
//     H.tandaTangan().set("signature_1", response.ttd)
//     input.value.tandaTanganPerawat = response.ttd
//   } else {
//     H.tandaTangan().set("signature_1", '')
//   }
// }

setView()
fetchDropdown()
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
}</style>
