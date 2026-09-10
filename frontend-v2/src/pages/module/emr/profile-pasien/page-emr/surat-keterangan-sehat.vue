<style lang="scss"></style>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
// import Checkbox from 'primevue/checkbox';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({
  title: 'Surat Keterangan Sehat - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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
const route = useRoute()
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date,
    jam: new Date
  },
  airway: [],
  disability: []

})

const COLLECTION: any = ref('SuratKeteranganSehat') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  // kebjamKedatangan: new Date(),
  // kebjamAsesmenAwal: new Date(),
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
function calculateAge(birthdate) {
  const today = new Date();
  const birthDate = new Date(birthdate);
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  return age;
}
const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
  } else {
    setAutoFill()
    let dataRegis = H.setObjectRegistrasi(pasien.value.registrasi)
    let dataPasien = H.setObjectPasien(pasien.value)
    input.value.DDRuangan = dataRegis.namaruangan
    input.value.DDDokter = dataRegis.dokter
    input.value.TBNamaPasien = dataPasien.namapasien
    input.value.DTanggalLahir = dataPasien.tgllahir
    input.value.TBSTahun = calculateAge(dataPasien.tgllahir)
    input.value.TBJenisKelamin = dataPasien.jeniskelamin
    input.value.TAAlamat = dataPasien.alamatlengkap
  }
}
const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': route.name,
    'name_form': 'Surat Keterangan Sehat',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }

  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr-surket`, json).then((response: any) => {
      isLoading.value = false
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const kembaliKeun = () => {
  window.history.back()
}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  // NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`
  ).then((response) => {
    d_Dokter.value = response
  })
}
// const fetchRuangan = async (filter: any) => {
//     await useApi().get(
//         `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`
//     ).then((response) => {
//         d_Ruangan.value = response
//     })
// }
const getDataExist = async () => {
  await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
    if (response != null || response != undefined) {
      input.value.TBSBeratBadan = response.beratBadan
      input.value.TBSTinggiBadan = response.tinggiBadan
      // input.value.IMT = response.IMT
      // input.value.lingkarPerut = response.lingkarPerut
      // input.value.TBSNadi = response.nadi
      // input.value.TBSSuhu = response.suhu
      input.value.TBSTekananDarah = response.tekananDarah
      // input.value.TBSPernafasan = response.pernapasan
      // input.value.TBSSaO2 = response.SPO2
    }
  })
}

watch(
    () => [
        input.value.TBSBeratBadan,
        input.value.TBSTinggiBadan],
    (value) => {
        let txtFirstNumberValue: any = input.value.TBSBeratBadan;
        let txtSecondNumberValue: any = input.value.TBSTinggiBadan;
        let result: any = parseFloat(txtFirstNumberValue) / (parseFloat(txtSecondNumberValue) / 100
            * parseFloat(txtSecondNumberValue) / 100);

        input.value.TBSbmi = parseFloat(result).toFixed(2)
        if (isNaN(input.value.TBSbmi)) {
            input.value.TBSbmi = 0
        }
    }
)

onBeforeMount(async () => {
  try {
    await loadRiwayat()
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
const setAutoFill = async () => {
  const fieldsVitalSign = "tinggiBadan,IMT,lingkarPerut,tekananDarah,keadaanumumobgyn,keadaanumum,pernapasan,suhu,nadi,beratBadan,SPO2";
  const fieldsAsesmen = "nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,tekananDarahObgyn,tinggibadanObgyn,beratbadanObgyn";

  const fetchData = async (collection, fields) => {
    return await useApi().get(`emr/auto-fill?norec_pd=${props.registrasi.norec_pd}&collection=${collection}&field=${fields}`);
  };

  const parseResponse = (response) => {
    if (!response) return "";

    const fieldsMap = {
      celciusObgyn: "Suhu : ",
      nadiObgyn: "Nadi : ",
      nafasObgyn: "Pernafasan : ",
      tekananDarahObgyn: "Tekanan Darah : ",
      tinggiBadan: "Tinggi Badan : ",
      beratBadan: "Berat Badan : ",
      sao2Obgyn: "SPO2 : ",
      IMT: "IMT : ",
      kebpilihanallo: "Kebutuhan Pilihan Allo : ",
      keluhanutama: "Keluhan Utama : ",
      riwayatpenyakit: "Riwayat Penyakit : ",
      riwayatpenyakitdahulu: "Riwayat Penyakit Dahulu : ",
      riwayatpengobatan: "Riwayat Pengobatan : ",
      riwayatpenyakitkeluarga: "Riwayat Penyakit Keluarga : ",
      riwayatalergi: "Riwayat Alergi : "
    };

    let data = "";
    Object.entries(fieldsMap).forEach(([key, label]) => {
      if (response[key]) data += `     ${label}${response[key]}\n`;
    });

    return data;
  };

  const setValues = (response) => {
    if (!response) return;

    input.value = {
      ...input.value,
      tekananDarah: response.tekananDarahObgyn || response.tekananDarah,
      nadi: response.nadiObgyn || response.nadi,
      nafas: response.nafasObgyn || response.pernapasan,
      celcius: response.celciusObgyn || response.suhu,
      sao2: response.sao2Obgyn || response.SPO2,
      gcse: response.gcse,
      gcsv: response.gcsv,
      gcsm: response.gcsm,
      kebpilihanallo: response.kebpilihanallo,
      keadaanumum: response.keadaanumumobgyn || response.keadaanumum,
      TBSTinggiBadan: response.tinggibadanObgyn,
      TBSBeratBadan: response.beratbadanObgyn,
      TBSTekananDarah: response.tekananDarahObgyn,
      anamnesis: parseResponse(response),
    };
  };

  let response = await fetchData("VitalSign", fieldsVitalSign);
  if (!response) response = await fetchData("AsesmenAwalKeperawatanPasienRawatJalanNurse", fieldsAsesmen);
  if (!response) response = await fetchData("AsesmenAwalKebidananRawatJalanNurse", fieldsAsesmen);

  setValues(response);
}



getDataExist()
fetchPasien()
</script>

<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Surat Keterangan Sehat</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun" :isHideCetakWNA="false"></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="columns is-multiline column is-12">
          <!-- <div class="column is-6">
                        <VField label="Section">
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.DDRuangan" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" disabled />
                            </VControl>
                        </VField>
                    </div> -->
          <div class="column is-6">
            <VField label="Dokter">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
              </VControl>
            </VField>
          </div>
        </div>

        <hr>

        <div class="columns is-multiline column is-12">
          <div class="column is-6">
            <div class="column columns is-multiline">
              <div class="column is-3">Nama</div>
              <div class="column is-9">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                </VControl>
              </div>

              <div class="column is-3">Tanggal Lahir/Umur</div>
              <div class="column is-4">
                <VDatePicker v-model="input.DTanggalLahir" mode="date" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-4">
                <VField addons>
                  <VControl>
                    <VInput type="number" class="input" v-model="input.TBSTahun" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Tahun</VButton>
                  </VControl>
                </VField>
              </div>

              <div class="column is-3">Jenis Kelamin</div>
              <div class="column is-9">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBJenisKelamin" />
                </VControl>
              </div>
            </div>
          </div>
          <div class="column is-6">
            <div class="column columns is-multiline">
              <div class="column is-3">Alamat</div>
              <div class="column is-9">
                <VField>
                  <VTextarea rows="2" v-model="input.TAAlamat"></VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div class="columns is-multiline column is-12">
          <div class="column is-4">
            <div class="column is-12">
              <VField addons label="Tinggi Badan">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBSTinggiBadan" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>cm</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField addons label="Berat Badan">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Kg</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField addons label="TD">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBSTekananDarah" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mmHg</VButton>
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4">
            <div class="column is-12">
              <VField addons label="BMI">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBSbmi" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>kg/m<sup>2</sup></VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField addons label="Buta Warna">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBButaWarna" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField addons label="Gol. Darah">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBGolDarah" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4">
            <VField label="Keterangan">
              <VTextarea rows="2" v-model="input.TAKeterangan"></VTextarea>
            </VField>
          </div>
        </div>
        <!-- form baru -->

      </div>
    </div>

  </div>
</template>
