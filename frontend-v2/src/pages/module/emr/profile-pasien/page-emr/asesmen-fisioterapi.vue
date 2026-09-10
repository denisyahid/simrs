<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Asesmen Fisioterapi</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-3">
            <h1>Tanggal & Jam:</h1>
            <VDatePicker v-model="input.tanggalDanJam" mode="datetime" trim-weeks is24hr>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>

          <div class="column is-12 pt-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0">
          </div>

          <div class="is-12 column columns is-multiline pt-0">
            <div class="column is-12 pb-0">
              <h1>Rujukan</h1>
            </div>
            <div class="column is-12">
              <div class="is-flex column p-0">
                <VControl raw subcontrol>
                  <VCheckbox type="text" color="primary" label="Ya, dari" v-model="input.rujukan"
                    true-value="Ya, dari" />
                </VControl>
                <div class="columns column m-0 p-0">
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VInput type="text" class="input" placeholder="RS" v-model="input.rujukanDariRS" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VInput type="text" class="input" placeholder="Puskesmas" v-model="input.rujukanDariPuskesmas" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VInput type="text" class="input" placeholder="Dokter" v-model="input.rujukanDariDokter" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VInput type="text" class="input" placeholder="Lainnya" v-model="input.rujukanDariLainnya" />
                    </VControl>
                  </div>
                </div>
              </div>
              <div class="column p-0 is-flex">
                <VControl raw subcontrol>
                  <VCheckbox type="text" color="primary" label="Tidak" v-model="input.rujukan" true-value="Tidak" />
                </VControl>
                <div class="columns column p-0">
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VCheckbox type="text" color="primary" label="Datang Sendiri" v-model="input.rujukanTidak"
                        true-value="Datang Sendiri" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VCheckbox type="text" color="primary" label="Diantar" v-model="input.rujukanTidak"
                        true-value="Diantar" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VInput type="text" color="primary" placeholder="Keterangan Diantar"
                        v-model="input.rujukanTidakketerangan" />
                    </VControl>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="is-12 column pt-0">
            <h1>Riwayat Alergi:</h1>
            <VField>
              <VTextarea type="text" class="input" v-model="input.riwayatAlergi" rows="2" />
            </VField>
          </div>

          <div class="column is-12 pt-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-0">
          </div>

          <div class="columns is-multiline column pt-0">
            <div class="column is-12 pb-1">
              <h1 style="font-style: italic;">ANANMESA</h1>
            </div>

            <div class="column is-6">
              <h1>Keluhan Utama:</h1>
              <VField>
                <VTextarea rows="3" v-model="input.keluhanUtama"></VTextarea>
              </VField>
            </div>

            <div class="column is-6">
              <h1>Riwayat Penyakit Sekarang:</h1>
              <VField>
                <VTextarea rows="3" v-model="input.riwayatPenyakitSekarang"></VTextarea>
              </VField>
            </div>

            <div class="column is-12 pb-0 pt-0">
              <h1>Riwayat Penyakit Dahulu dan Penyerta:</h1>
              <VField>
                <VTextarea rows="2" v-model="input.riwayatPenyakitDahulu"></VTextarea>
              </VField>
              <div class="is-flex">
                <VField class="mr-2">
                  <VControl>
                    <VCheckbox v-model="input.diabetes" label="Diabetes Militus" color="primary"
                      true-value="Diabetes Militus" />
                  </VControl>
                </VField>

                <VField>
                  <VControl>
                    <VCheckbox v-model="input.Jantung" label="Jantung" color="primary" true-value="Jantung" />
                  </VControl>
                </VField>

                <VField>
                  <VControl>
                    <VCheckbox v-model="input.rhematoid" label="Rhematoid Artitis" color="primary"
                      true-value="Rhematoid Artitis" />
                  </VControl>
                </VField>

                <VField>
                  <VControl>
                    <VCheckbox v-model="input.hipertensi" label="Hypertensi" color="primary" true-value="Hypertensi" />
                  </VControl>
                </VField>

                <VField>
                  <VControl>
                    <VCheckbox v-model="input.riwayat" label="Riwayat Alergi" color="primary"
                      true-value="Riwayat Alergi" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-6 pt-0">
              <h1>Pemeriksaan Fisik :</h1>
              <VField>
                <VTextarea type="text" class="input" v-model="input.pemeriksaanFisik" rows="3" />
              </VField>
            </div>

            <div class="column is-6 pt-0">
              <h1>Kemampuan Fungsional :</h1>
              <VField>
                <VTextarea type="text" class="input" v-model="input.kemampuanFungsional" rows="3" />
              </VField>
            </div>

            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-7">
                  <h1>BERAPAKAH SKALA NYERI ANDA ?</h1>
                  <div class="columns pt-4">
                    <div class="column" style="text-align: center" v-for="(image, i) in listImageNyeri.detail">
                      <VAvatar size="medium" :picture="image.img" style="cursor: pointer !important"
                        :class="isAktive == i ? 'active' : ''" @click="skor(image, i)" />
                      <p>{{ image.descNilai }}</p>
                      <p>{{ image.nama }}</p>
                    </div>
                  </div>
                </div>
                <div class="column is-5">
                  <h1>Score</h1>
                  <div class="pt-2">
                    <VField v-for="skor in listSkoringNyeri.detail">
                      <VControl raw subcontrol class="p-0">
                        <VCheckbox class="pt-0" v-model="input.skoringNyeri" :true-value="skor.descNilai"
                          :label="skor.nama" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>

            <div class="column is-6">
              <h1>Resiko Jatuh :</h1>
              <VField>
                <VTextarea type="text" class="input" v-model="input.resikoJatuh" rows="3" />
              </VField>
            </div>

            <div class="column is-6">
              <h1>Pemeriksaan Khusus</h1>
              <VField>
                <VTextarea type="text" class="input" v-model="input.pemeriksaanKhusus" rows="3" />
              </VField>
            </div>

            <div class="column is-6">
              <h1>Pengukuran Khusus</h1>
              <VField>
                <VTextarea type="text" class="input" v-model="input.pengukuranKhusus" rows="2" />
              </VField>
            </div>

            <div class="column is-6">
              <h1>Data Penunjang</h1>
              <VField>
                <VTextarea type="text" class="input" v-model="input.dataPenunjang" rows="2" />
              </VField>
            </div>

            <div class="column is-6">
              <h1>Diagnosis Fisioterapi</h1>
              <VField>
                <VTextarea type="text" class="input" v-model="input.diagnosisFisioterapi" rows="3" />
              </VField>
            </div>

            <div class="column is-6 columns is-multiline">
              <div class="column is-6 pt-0 pb-0 is-flex" style="align-items: end;">
                <h1>Intervensi</h1>
              </div>
              <div class="column is-6 pt-0 pb-0 is-flex" style="justify-content: right;">
                <VIconButton type="button" raised circle icon="fas fa-file-medical-alt" @click="tindakan()"
                  :loading="isLoading" color="success" v-tooltip-prime.top="'Tindakan'">
                </VIconButton>
              </div>
              <div class="column is-12 pt-0 pb-0">
                <VField>
                  <VTextarea type="text" class="input" v-model="input.intervensi" rows="3" />
                </VField>
              </div>
            </div>

            <div class="column is-4 mt-3" style="margin-left: auto;text-align:center">
              <h1>Garut , Tanggal dan Jam</h1>
              <VDatePicker v-model="input.tanggalTTD" mode="datetime" style="width: 100%" class="p-2" trim-weeks
                :max-date="new Date()" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
              <TandaTangan :elemenID="'TTDFisioterapi'" :width="'150'" :height="'150'" class="dek" />
              <VField class="mt-3">
                <VControl>
                  <AutoComplete v-model="input.pegawaiFisioterapi" :suggestions="d_Perawat" :optionLabel="'label'"
                    @complete="fetchPegawai($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Pegawai" />
                </VControl>
              </VField>
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
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import * as EMR from '../page-emr-plugins/asesmen-fisioterapi.ts'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())

useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

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
const user = useUserSession().getUser().pegawai

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const route = useRoute()
const loadData: any = ref(true)
const isLoading: any = ref(false)
const d_Perawat: any = ref([])
const dataTTD: any = ref([])
const resCPPT: any = ref();
const resNS: any = ref();
const isResumeMedis: any = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('AsesmenFisioterapi')
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date(),
  tanggalDanJam: new Date(),
  tanggalTTD: new Date(),
})
const loadRiwayat = async () => {
  isLoading.value = true
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
    H.tandaTangan().set("TTDFisioterapi", dataTTD.value.TTDFisioterapi)
    const dataNS = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + "&field=keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatalergi,keadaanumumobgyn,gcse,gcsm,gcsv,tekananDarahObgyn,nadiObgyn,nafasObgyn,celciusObgyn,sao2Obgyn,beratBadanObgyn,tinggiBadanObgyn")
    const dataCPPT = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail&flag=perawat" + "&field=O,A,riwayatkeluar,statuskeluar,perlukontrol")
    if (dataNS != null) {
      resNS.value = dataNS;
    }
    if (dataCPPT != null) {
      resCPPT.value = dataCPPT;
    }
    isLoading.value = false
    // H.alert('info', 'Data berhasil dimuat')
  } else {
    // input.value.pegawaiFisioterapi = { label: user.namaLengkap, value: user.id }
    const dataNS = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + "&field=keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatalergi,keadaanumumobgyn,gcse,gcsm,gcsv,tekananDarahObgyn,nadiObgyn,nafasObgyn,celciusObgyn,sao2Obgyn,beratBadanObgyn,tinggiBadanObgyn")
    const dataCPPT = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail&flag=perawat" + "&field=O,A,riwayatkeluar,statuskeluar,perlukontrol")
    if (dataNS != null) {
      resNS.value = dataNS;
      input.value.keluhanUtama = dataNS.keluhanutama
      input.value.riwayatAlergi = dataNS.riwayatalergi
      input.value.riwayatPenyakitSekarang = dataNS.riwayatpenyakit
      input.value.riwayatPenyakitDahulu = dataNS.riwayatpenyakitdahulu
      // input.value.
    }
    if (dataCPPT != null) {
      resCPPT.value = dataCPPT;
      input.value.pemeriksaanFisik = dataCPPT.O
      input.value.diagnosisFisioterapi = dataCPPT.A
      input.value.riwayatkeluar = dataCPPT.riwayatkeluar
      input.value.statuskeluar = dataCPPT.statuskeluar
      input.value.perlukontrol = dataCPPT.perlukontrol
    }
    isLoading.value = false
    // H.alert('info', 'Data berhasil dimuat')
  }
}

const tindakan = async () => {
  isLoading.value = true
  const data_t = await useApi().get(`/kasir/billing?norec_pd=${NOREC_PD}&istindakan=true`)
  const details_dt = data_t.detail
  let text = '';
  for (let index = 0; index < details_dt.length; index++) {
    const element = details_dt[index];
    if (element.details.length > 0) {
      for (let fKey = 0; fKey < element.details.length; fKey++) {
        const dtTindakan = element.details[fKey];
        if (dtTindakan.namaproduk.toLowerCase().indexOf('biaya registrasi pasien') == -1) {
          let tindakan = dtTindakan.namaproduk
          var namaTindakan = tindakan.trim();
          text += '# ' + namaTindakan + '\n';
        }
      }
    }

  }

  input.value.intervensi = text
  isLoading.value = false
}

const simpan = () => {
  if (!input.value.diagnosisFisioterapi || input.value.diagnosisFisioterapi && input.value.diagnosisFisioterapi.replace(/\s/g, "").length < 3) {
    H.alert('error', 'Diagnosis Fisioterapi, ' + 'diisi minimal 3 karakter');
    return;
  }
  if(!input.value.keluhanUtama){
    H.alert('info', 'Keluhan Utama Wajib Diisi')
    return
  }
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object['TTDFisioterapi'] = H.tandaTangan().get("TTDFisioterapi");
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  console.log("IS RESUME", isResumeMedis.value)
  useApi().post(`/emr/simpan-emr`, json).then(async (response: any) => {
    makeRingkasanData(json).then((res) => {

    }).catch((err) => {
      console.log('err Ringkasan', err)
    });
    isLoading.value = false
    NOREC_EMRPASIEN.value = response.norec_emr
    // if (isResumeMedis.value) {
    // }
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

async function makeRingkasanData(json: any) {
  return new Promise((resolve, reject) => {
    try {
      let tanggaldatang = '';
      let dpjpUtamas: any = {
        value: json.data.user_input ? json.data.user_input.pegawaifk : json.data.registrasi.objectpegawaifk,
        label: json.data.user_input ? json.data.user_input.namalengkap : json.data.registrasi.dokter,
      };

      let fisik = '';
      fisik += resNS.value.celcius ? `Suhu : ${resNS.value.celcius} °C\n` : 'Suhu : -\n'
      fisik += resNS.value.nadi ? `Nadi : ${resNS.value.nadi} x/mnt\n` : 'Nadi : -\n'
      fisik += resNS.value.nafas ? `Pernafasan : ${resNS.value.nafas} x/mnt\n` : 'Pernafasan : -\n'
      fisik += resNS.value.tekananDarah ? `Tekanan Darah : ${resNS.value.tekananDarah} mmHg\n` : 'Tekanan Darah : -n\n'
      fisik += resNS.value.tinggiBadan ? `Tinggi Badan : ${resNS.value.tinggiBadan} Cm\n` : 'Tinggi Badan : -\n'
      fisik += resNS.value.beratBadan ? `Berat Badan : ${resNS.value.beratBadan} Kg\n` : 'Berat Badan : -\n'
      fisik += resNS.value.spo2 ? `SPO2 : ${resNS.value.spo2} %\n` : ''

      let object = {
        "detailDS": [
          {
            "no": 1,
            "TADiagnosaSekunder": ""
          }
        ],
        "detailDT": [
          {
            "no": 1,
            "TADeskripsiTindakan": ""
          }
        ],
        "waktuTataLaksana": json.data.tanggalDanJam,
        "waktuKontrol": json.data.tanggalDanJam,
        "jamKedatangan": json.data.tanggalDanJam,
        "jamAsesmenAwal": json.data.tanggalDanJam,
        "riwayatkeluar": resCPPT.value?.riwayatkeluar,
        "statuskeluar": resCPPT.value?.statuskeluar,
        "perlukontrol": resCPPT.value?.perlukontrol,
        "tanggalKedatangan": json.data.tanggalDanJam,
        "dpjpUtama": dpjpUtamas,
        "TAKondisiSaatMasuk": json.data.keluhanUtama,
        "TADiagnosisPrimer": json.data.diagnosisFisioterapi ?? null,
        "gcse": resNS.value?.gcse,
        "gcsv": resNS.value?.gcsv,
        "gcsm": resNS.value?.gcsm,
        "kesanUmum": resNS.value.keadaanumumobgyn,
        "nadi": resNS.value.nadiObgyn,
        "nafas": resNS.value.nafasObgyn,
        "celcius": resNS.value.celciusObgyn,
        "tekananDarah": resNS.value.tekananDarahObgyn ?? '',
        "anamnesis": resNS.value.anamnesisObgyn ?? '',
        "pemeriksaanfisik": json.data.pemeriksaanFisik,
        "intruksi": json.data.intervensi ?? '',
        "hasilpemeriksaanpenunjang": json.data.dataPenunjang ?? '',
        "sumber": "AssFisioterapi"
      }
      object.nocm = json.data.pasien.nocm
      object.pasien = json.data.pasien
      object.registrasi = json.data.registrasi
      let sendData = {
        'id': '',
        'norec_emr': '',
        'collection': 'RingkasanKeluar',
        'url_form': 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
        'name_form': 'Ringkasan Keluar',
        'jenis_emr': 'asesmen_medis',
        'data': object
      }

      useApi().postNoMessage(
        `/emr/simpan-emr`, sendData).then(async (response: any) => {
          isLoading.value = false
          H.alert('success', 'Ringkasan keluar berhasil dibuat');
          return resolve(true)
        }).catch((e: any) => {
          isLoading.value = true
          H.alert('error', 'Ringkasan keluar gagal dibuat');
          return resolve(false)
        })
    } catch (error) {
      reject(error)
    }
  })

}

function checkResume() {
  let params = `?norec_pd=${props.registrasi.norec_pd}&norec_apd=${props.registrasi.norec_apd}`
  let uri = `/emr/check-resume-medis${params}`;
  useApi().get(uri).then((res) => {
    console.log(`res Check Resume`, res);
    if (res) {
      isResumeMedis.value = true;
    }
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
    d_Perawat.value = response
  })
}

// DI ONMOUNTED YA MENGHINDARI LAG VIEWS BELUM TERLOAD / BLANK PUTIH
onMounted(async () => {
  await loadRiwayat();
  checkResume();
})

onBeforeMount(async () => {
  try {
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});
onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});
</script>

<style lang="scss">
h1 {
  font-weight: bold;
}
</style>
