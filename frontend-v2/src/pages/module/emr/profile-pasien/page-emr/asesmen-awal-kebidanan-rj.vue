<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

.p-fieldset-legend {
    margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
    background: none;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

table.assesment {
    border-collapse: collapse;
    width: 100%;
}


.assesment th {
    text-align: center !important;
    border-bottom: 1px solid black;
    // border: 1px solid black;
}

.assesment th,
.assesment td {
    padding: 8px;
    vertical-align: middle !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}
</style>
<style lang="scss">
.table-fro {
    width: 100%;
    border: 1px solid black;
}

.th-fro,
.td-fro {
    padding: 7px;
    border: 1px solid black;
    vertical-align: inherit;
}

.setFRO-center {
    text-align: center !important;
}

.p-fieldset-legend {
    margin-left: 15px;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150%;
}


.tg2 {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg2 td {
    // border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg2 th {
    // border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg td {
    // border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    // border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: middle
}
</style>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-kebidanan-rj'

// Loopingan
let detailRiwayatKehamilan = ref(EMR.detailRiwayatKehamilan())
let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
let detailStatusFungsional = ref(EMR.detailStatusFungsional())
let detailRencanaKebidanan = ref(EMR.detailRencanaKebidanan())
let statusFungsional: any = ref(EMR.statusFungsional())

// Judul
useHead({
    title: 'Asesmen Awal Kebidanan Rawat Jalan - ' + import.meta.env.VITE_PROJECT,
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
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
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
    filter: '',
    airway: [],
    disability: []
})
const COLLECTION: any = ref('AsesmenAwalKebidananRJ') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const loadRiwayat = async () => {
    // if (NOREC_EMRPASIEN.value == '') return
    await useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
                dataTTD.value = response[0]
            }
        })
    H.tandaTangan().set("TTDBidan", dataTTD.value.TTDBidan)
    // H.tandaTangan().set("TTDDokterPemeriksa", dataTTD.value.TTDDokterPemeriksa)
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object['TTDBidan'] = H.tandaTangan().get("TTDBidan");
    // object['TTDDokterPemeriksa'] = H.tandaTangan().get("TTDDokterPemeriksa");
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    console.log(json)

    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            // NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
        })

    // console.log(resultValue)
}
const kembaliKeun = () => {
    window.history.back()
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}
const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
        input.value.beratBadan = response.beratBadan ? response.beratBadan : ''
        input.value.tinggiBadan = response.tinggiBadan ? response.tinggiBadan : ''
        input.value.IMT = response.IMT ? response.IMT : response.IMT
        input.value.lingkarPerut = response.lingkarPerut ? response.lingkarPerut : ''
        input.value.tekananDarah = response.tekananDarah ? response.tekananDarah : ''
        input.value.nadi = response.nadi ? response.nadi : ''
        input.value.suhu = response.suhu ? response.suhu : ''
        input.value.pernapasan = response.pernapasan ? response.pernapasan : ''
    })
}
const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

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

watch(
    () => Object.keys(input.value).filter(key => key.startsWith('checkboxSN_')).map(key => input.value[key]),
    (newValues) => {
        let sum = 0
        newValues.forEach((checkboxValue, index) => {
            const [_, rowIndex, itemIndex] = Object.keys(input.value).filter(key => key.startsWith('checkboxSN_'))[index].split('_')
            const nilaiRow = detailSkriningNutrisi.value[parseInt(rowIndex)]?.child?.[parseInt(itemIndex) + 1]
            if (checkboxValue && nilaiRow && !isNaN(parseInt(nilaiRow.caption))) {
                sum += parseInt(nilaiRow.caption)
            }
        })
        input.value.jumlahNilaiSN = sum
    },
    { deep: true }
)

watch(
  () => input.value.jumlahNilaiSN,
  (newValue) => {
    if (newValue >= 0 && newValue <= 1) {
      input.value.CBrisikoRendahPDDK = "Risiko rendah (MST 0-1)";
      input.value.CBrisikoSedangPDDK = null;
      input.value.CBrisikoTinggiPDDK = null;
    } else if (newValue >= 2 && newValue <= 3) {
      input.value.CBrisikoRendahPDDK = null;
      input.value.CBrisikoSedangPDDK = "Risiko sedang (MST 2-3)";
      input.value.CBrisikoTinggiPDDK = null;
    } else if (newValue >= 4) {
      input.value.CBrisikoRendahPDDK = null;
      input.value.CBrisikoSedangPDDK = null;
      input.value.CBrisikoTinggiPDDK = "Risiko tinggi (MST 4-5)";
    }
  }
);

watch(
  () => input.value,
  (newValue) => {
    let totalAllSkor = 0;

    statusFungsional.value.statusFungsional.forEach((item, indexFungsi) => {
      let totalSkor = 0;

      item.detail.forEach((detail, indexDetail) => {
        if (detail.type === "checkbox" && input.value[`checkBox_${item.fungsi}_${indexDetail}`]) {
          const value = item.detail.find(d => d.caption === input.value[`checkBox_${item.fungsi}_${indexDetail}`])?.value;
          totalSkor += parseInt(detail.value, 10);
        }

        if (detail.type === "textbox" && input.value[`textbox_${item.fungsi}_${indexDetail}`]) {
          const value = item.detail.find(d => d.caption === "Ket")?.value;
          totalSkor += parseInt(detail.value, 10);
        }
      });

      const skorDetail = item.detail.find(d => d.type === "skor");
      if (skorDetail) {
        input.value[`textbox_${item.fungsi}_skor`] = totalSkor;
        totalAllSkor += totalSkor;
      }
    });

    input.value.TBtotal2SF = totalAllSkor;
  },
  { deep: true }
);


watch(
  () => input.value.TBtotal2SF,
  (newValue) => {

    if (newValue >= 0 && newValue <= 4) {
      input.value.CBKetergantunganTotal = "Ketergantungan total (0-4)";
      input.value.CBKetergantunganBerat = false;
      input.value.CBKetergantunganSedang = false;
      input.value.CBKetergantunganRingan = false;
      input.value.CBKMandiriK = false;
    } else if (newValue >= 5 && newValue <= 8) {
      input.value.CBKetergantunganTotal = false;
      input.value.CBKetergantunganBerat = "Ketergantungan berat (5-8)";
      input.value.CBKetergantunganSedang = false;
      input.value.CBKetergantunganRingan = false;
      input.value.CBKMandiriK = false;
    } else if (newValue >= 9 && newValue <= 11) {
      input.value.CBKetergantunganTotal = false;
      input.value.CBKetergantunganBerat = false;
      input.value.CBKetergantunganSedang = "Ketergantungan sedang (9-11)";
      input.value.CBKetergantunganRingan = false;
      input.value.CBKMandiriK = false;
    } else if (newValue >= 12 && newValue <= 19) {
      input.value.CBKetergantunganTotal = false;
      input.value.CBKetergantunganBerat = false;
      input.value.CBKetergantunganSedang = false;
      input.value.CBKetergantunganRingan = "Ketergantungan ringan (12-19)";
      input.value.CBKMandiriK = false;
    } else if (newValue >= 20) {
      input.value.CBKetergantunganTotal = false;
      input.value.CBKetergantunganBerat = false;
      input.value.CBKetergantunganSedang = false;
      input.value.CBKetergantunganRingan = false;
      input.value.CBKMandiriK = "Ketergantungan mandiri (20 keatas)";
    }
  }
);

fetchDokter()
getDataExist()
fetchPasien()

</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Asesmen Awal Kebidanan Rawat Jalan</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun" :isHideCetak="true">
                            </ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <Fieldset :toggleable="true" legend="A. ANAMNESIS" style="margin-bottom: 10px">
                    <div class="columns is-multiline" style="padding: 10px; padding-top: 0px">
                        <div class="column is-6">
                            <VField label="Keluhan Utama : " bold>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keluhan Utama..."
                                        v-model="input.TBKeluhanUtama" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Riwayat Penyakit Sekarang : ">
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Riwayat Penyakit Sekarang..."
                                        v-model="input.TBRiwayatPenyakitSekarang" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <span style="font-weight:bold;">Riwayat Menstruasi : </span>
                        </div>
                        <div class="column is-4">
                            <VField label="Menarche Umur : " addons>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Umur..." v-model="input.TBUmur" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Tahun</VButton>
                                </VControl>
                            </VField>
                            <VField label="Volume : " addons>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Volume..."
                                        v-model="input.TBVolume" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>cc</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField addons label="Siklus : ">
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Siklus..."
                                        v-model="input.TBSiklus" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Hari</VButton>
                                </VControl>
                            </VField>
                            <div class="columns is-multiline column is-12">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Teratur"
                                            label="Teratur" v-model="input.CBTeratur" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak Teratur"
                                            label="Tidak Teratur" v-model="input.CBTidakTeratur" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-4">
                            <VField label="Keluhan Saat Haid" addons>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keluhan Saat Haid..."
                                        v-model="input.TBKeluhanSaatHaid" />
                                </VControl>
                            </VField>
                            <VField label="Lama : " addons>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Lama..." v-model="input.TBLama" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>Hari</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <span style="font-weight: bold; text-align: center">Riwayat kehamilan, persalinan dan nifas
                                yang lalu</span>
                            <div class="column" style="overflow: auto;">
                                <table class="tg">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;vertical-align: middle;" rowspan="3">No</th>
                                            <th style="text-align: center;vertical-align: middle;" rowspan="3">Tanggal
                                                Partus</th>
                                            <th style="text-align: center;vertical-align: middle;" colspan="3">Umur
                                                Hamil</th>
                                            <th style="text-align: center;vertical-align: middle;" rowspan="3">Jenis
                                                Partus</th>
                                            <th style="text-align: center;vertical-align: middle;" colspan="2">Penolong
                                            </th>
                                            <th style="text-align: center;vertical-align: middle;" colspan="3">Anak</th>
                                            <th style="text-align: center;vertical-align: middle;" colspan="3">Keadaan
                                                Anak Sekarang</th>
                                            <th style="text-align: center;vertical-align: middle;" rowspan="3">
                                                Keterangan/Komplikasi</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center;vertical-align: middle;" rowspan="2">Abortus
                                            </th>
                                            <th style="text-align: center;vertical-align: middle;" rowspan="2">Prematur
                                            </th>
                                            <th style="text-align: center;vertical-align: middle;" rowspan="2">Aterm
                                            </th>
                                            <th style="text-align: center;vertical-align: middle;" rowspan="2">Nakes
                                            </th>
                                            <th style="text-align: center;vertical-align: middle;" rowspan="2">Non</th>
                                            <th style="text-align: center;vertical-align: middle;" colspan="2">JK</th>
                                            <th style="text-align: center;vertical-align: middle;" rowspan="2">BBL</th>
                                            <th style="text-align: center;vertical-align: middle;" colspan="2">Hidup
                                            </th>
                                            <th style="text-align: center;vertical-align: middle;" rowspan="2">Meninggal
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center;vertical-align: middle;">Laki-laki</th>
                                            <th style="text-align: center;vertical-align: middle;">Perempuan</th>
                                            <th style="text-align: center;vertical-align: middle;">Normal</th>
                                            <th style="text-align: center;vertical-align: middle;">Cacat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, rowIndex) in detailRiwayatKehamilan" :key="rowIndex">
                                            <td style="text-align: center;" width="50px">{{ rowIndex + 1 }}</td>
                                            <td width="200px" v-for="(item, itemIndex) in row.child" :key="itemIndex"
                                                :colspan="item.colspan">
                                                <VField style="padding:0px 10px;" v-if="item.type == 'textbox'">
                                                    <VControl>
                                                        <VInput
                                                            v-model="input['textboxRK_' + rowIndex + '_' + itemIndex]"
                                                            class="input">
                                                        </VInput>
                                                    </VControl>
                                                </VField>
                                                <VField style="padding:0px 10px;" v-if="item.type == 'tanggal'">
                                                    <VDatePicker
                                                        v-model="input['tanggalRK_' + rowIndex + '_' + itemIndex]"
                                                        mode="date" style="width: 100%" trim-weeks
                                                        :max-date="new Date()">
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
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span style="font-weight: bold;">Riwayat Pemakaian Alat Kontrasepsi</span>
                            <div class="column is-12 columns is-multiline">
                                <div class="column is-4 columns is-multiline">
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                label="Tidak" v-model="input.CBTidakRPAK" />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="YA" label="YA"
                                                v-model="input.CBYaRPAK" />
                                        </VControl>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <VField label="Jenis : ">
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Jenis..."
                                                v-model="input.TBJenisRPAK" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField label="Lama pemakaian : ">
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Lama pemakaian..."
                                                v-model="input.TBLamaPemakaianRPAK" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span style="font-weight: bold;">Riwayat Hamil Ini : </span>
                            <div class="columns is-multiline column is-12">
                                <div class="column is-6">
                                    <VField label="Hari pertama haid terakhir : ">
                                        <VControl>
                                            <VInput type="text" class="input"
                                                placeholder="Hari pertama haid terakhir..."
                                                v-model="input.TBhphtRPAK" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField label="Tafsiran partus : ">
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Tafsiran partus..."
                                                v-model="input.TBtafsiranPartusRPAK" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <span style="font-weight: bold;">Ante Natal Care : </span>
                            <div class="column is-12 columns is-multiline">
                                <div class="column is-4 columns is-multiline">
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                label="Tidak" v-model="input.CBtidakANC" />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                                                v-model="input.CByaANC" />
                                        </VControl>
                                    </div>
                                </div>
                                <div class="column is-1">Di : </div>
                                <div class="columns is-multiline column is-7">
                                    <div class="column is-3">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Dokter Kandungan"
                                                label="Dokter Kandungan" v-model="input.CBDokterKandungan" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Dokter Umum"
                                                label="Dokter Umum" v-model="input.CBDokterUmum" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Bidan"
                                                label="Bidan" v-model="input.CBBidan" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                                label="Lainnya" v-model="input.CBLainnyaANC" />
                                        </VControl>
                                        <VControl style="margin-top: 5px">
                                            <VInput type="text" class="input" placeholder="Isi disini..."
                                                v-model="input.TBLainnyaANC" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12 columns is-multiline">
                                <div class="column is-6 columns is-multiline">
                                    <div class="column is-12">
                                        <span>Frekuensi : </span>
                                    </div>
                                    <div class="column is-3">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="1x" label="1x"
                                                v-model="input.CB1xFrekuensi" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="2x" label="2x"
                                                v-model="input.CB2xFrekuensi" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="3x" label="3x"
                                                v-model="input.CB3xFrekuensi" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="4x" label="4x"
                                                v-model="input.CB4xFrekuensi" />
                                        </VControl>
                                    </div>
                                </div>
                                <div class="column is-6 columns is-multiline">
                                    <div class="column is-12">
                                        <span>Imunisasi TT : </span>
                                    </div>
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                label="Tidak" v-model="input.CBTidakImunisasiTT" />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                                                v-model="input.CBYaImunisasiTT" />
                                        </VControl>
                                        <VField addons style="margin-top:5px;">
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Isi disini..."
                                                    v-model="input.TByaKali" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>Kali</VButton>
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12 columns is-multiline">
                                <div class="column is-12">
                                    <span>Keluhan saat hamil</span>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Mual" label="Mual"
                                            v-model="input.CBMualKSH" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Muntah" label="Muntah"
                                            v-model="input.CBMuntahKSH" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Perdarahan"
                                            label="Perdarahan" v-model="input.CBPerdarahanKSH" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Pusing" label="Pusing"
                                            v-model="input.CBPusingKSH" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Sakit Kepala"
                                            label="Sakit Kepala" v-model="input.CBSakitKepalaKSH" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                            label="Lainnya" v-model="input.CBLainnyaKSH" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="..."
                                                v-model="input.TBlainnyaKSH" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div class="column is-12">
                                <VField label="Riwayat Penyakit Terdahulu">
                                    <VTextarea rows="2" placeholder="..." v-model="input.TARiwayatPenyakitTerdahulu">
                                    </VTextarea>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField label="Riwayat Pengobatan">
                                    <VTextarea rows="2" placeholder="..." v-model="input.TARiwayatPengobatan">
                                    </VTextarea>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField label="Riwayat Penyakit Keluarga">
                                    <VTextarea rows="2" placeholder="..." v-model="input.TARiwayatPenyakitKeluarga">
                                    </VTextarea>
                                </VField>
                            </div>
                            <div class="column is-12 columns is-multiline">
                                <div class="column is-4 columns is-multiline">
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                label="Tidak" v-model="input.CBtidakRiwataAlergi" />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                                                v-model="input.CByaRiwataAlergi" />
                                        </VControl>
                                    </div>
                                </div>
                                <div class="column is-7 columns is-multiline">
                                    <div class="column is-12">
                                        <span>Jenis Alergi : </span>
                                    </div>
                                    <div class="column is-4">
                                        <VControl raw subcontrol style="margin-bottom: 5px;">
                                            <VCheckbox class="p-0" color="primary" square true-value="Obat" label="Obat"
                                                v-model="input.CBObatJA" />
                                        </VControl>
                                        <VField horizontal>
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.TBObatJA" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VControl raw subcontrol style="margin-bottom: 5px;">
                                            <VCheckbox class="p-0" color="primary" square true-value="Makanan"
                                                label="Makanan" v-model="input.CBMakananJA" />
                                        </VControl>
                                        <VField horizontal>
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.TBMakananJA" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VControl raw subcontrol style="margin-bottom: 5px;">
                                            <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                                label="Lainnya" v-model="input.CBLainnyaJA" />
                                        </VControl>
                                        <VField horizontal>
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.TBLainnyaJA" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="B. STATUS FISIK" style="margin-bottom: 10px;">
                    <div class="columns is-multiline" style="padding: 10px; padding-top: 0px">
                        <div class="column is-12">
                            <span>Keadaan umum : </span>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-6 columns is-multiline">
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Baik" label="Baik"
                                            v-model="input.CBBaikKU" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Sedang" label="Sedang"
                                            v-model="input.CBSedangKU" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Buruk" label="Buruk"
                                            v-model="input.CBBurukKU" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="column is-6">
                                <VField label="GCS : ">
                                    <VField horizontal>
                                        <VField addons style="padding: 10px;padding-top:0px">
                                            <VControl class="field-addon-body">
                                                <VButton static>E</VButton>
                                            </VControl>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.TBeGCS" />
                                            </VControl>
                                        </VField>
                                        <VField addons style="padding: 10px;padding-top:0px">
                                            <VControl class="field-addon-body">
                                                <VButton static>V</VButton>
                                            </VControl>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.TBvGCS" />
                                            </VControl>
                                        </VField>
                                        <VField addons style="padding: 10px;padding-top:0px">
                                            <VControl class="field-addon-body">
                                                <VButton static>M</VButton>
                                            </VControl>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.TBmGCS" />
                                            </VControl>
                                        </VField>
                                    </VField>
                                </VField>
                            </div>
                            <div class="column is-12 columns is-multiline">
                                <div class="column is-12">
                                    <span>Tanda-tanda Vital</span>
                                </div>
                                <div class="column is-3">
                                    <VField addons style="padding: 5px;padding-top:0px" label="Tekanan Darah : ">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBtekananDarahTTV" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>mmHg</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField addons style="padding: 5px;padding-top:0px" label="Suhu : ">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBcelciusTTV" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>°C</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField addons style="padding: 5px;padding-top:0px" label="Berat Badan : ">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBberatBadanTTV" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>Kg</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField addons style="padding: 5px;padding-top:0px" label="Nadi : ">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBnadiTTV" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>x/mnt</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField addons style="padding: 5px;padding-top:0px" label="SaO2 : ">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBnsao2TTV" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>%</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField addons style="padding: 5px;padding-top:0px" label="Tinggi Badan : ">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBtinggiBadanTTV" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>cm</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField addons style="padding: 5px;padding-top:0px" label="Respirasi : ">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBrespirasiTTV" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>x/mnt</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span style="font-weight: bold;">Pemeriksaan Khusus Obsteri</span>
                        </div>
                        <div class="column is-12">
                            <span>Pemeriksaan Luar : </span>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-4">
                                <VField label="Denyut Jantung Janin : ">
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBdenyutJantungJanin" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>x/menit</VButton>
                                        </VControl>
                                    </VField>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="TFU (Mc Donald) : ">
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBtfu" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>cm</VButton>
                                        </VControl>
                                    </VField>
                                </VField>
                            </div>
                            <div class="column is-4"></div>
                            <div class="column is-4">
                                <VField label="Tinggi fundus uteri : ">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBtinggiFundusUteri" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Letak anak : ">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBletakAnak" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="His : ">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBhis" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField label="Lainnya : ">
                                    <VTextarea rows="2" v-model="input.TAlainnyaPKO"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-6 columns is-multiline">
                                <div class="column is-12">
                                    <span>Pemeriksa Dalam (Nama Pemeriksa & Waktu) : </span>
                                </div>
                                <div class="column is-6">
                                    <VField label="Nama Pemeriksa">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBNamaPemeriksaPD" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField label="Waktu">
                                        <VDatePicker mode="dateTime"  :is24hr="true" v-model="input.DTWaktuPD"
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="C. ASESMEN NYERI" style="margin-bottom: 10px">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <VField label="Skala nyeri (NRS/WBS/FLACC) : ">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBskalaNyeriAN"
                                        placeholder="Skala nyeri (NRS/WBS/FLACC)..." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Lokasi : ">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLokasiAN" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6 columns is-multiline">
                            <div class="column is-12">
                                <span>Frekuensi Nyeri</span>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Jarang" label="Jarang"
                                        v-model="input.CBJarangFN" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Hilang Timbul"
                                        label="Hilang Timbul" v-model="input.CBHilangTimbulFN" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Terus Menerus"
                                        label="Terus Menerus" v-model="input.CBTerusMenerusFN" />
                                </VControl>
                            </div>
                        </div>
                        <div class="column is-6">
                            <VField label="Lama Nyeri : ">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLamaNyeriAN" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-12">
                                <span>Kualitas Nyeri : </span>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tumpul" label="Tumpul"
                                        v-model="input.CBTumpulKN" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tajam" label="Tajam"
                                        v-model="input.CBTajamKN" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Panas/Terbakar"
                                        label="Panas/Terbakar" v-model="input.CBPanasTerbakarKN" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol style="margin-bottom: 5px;">
                                    <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                        label="Lain-lain" v-model="input.CBLainlainKN" />
                                </VControl>
                                <VField horizontal>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBMakananJA" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-6">
                            <VField label="Faktor yang memperberat : ">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBFaktorMemperberatAN" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Faktor yang meringankan nyeri : ">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBFaktorMeringankanAN"
                                        placeholder="Faktor yang meringankan nyeri" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="D. KONDISI PSIKOLOGI, SOSIAL, EKONOMI DAN SPIRITUAL"
                    style="margin-bottom: 10px">
                    <div class="column is-multiline">
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-12">
                                <span>Gangguan Psikologis</span>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak Ada"
                                        label="Tidak Ada" v-model="input.CBTidakAdaGP" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Gelisah" label="Gelisah"
                                        v-model="input.CBGelisahGP" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Takut" label="Takut"
                                        v-model="input.CBTakutGP" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Sedih" label="Sedih"
                                        v-model="input.CBSedihGP" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Rendah Diri"
                                        label="Rendah Diri" v-model="input.CBRendahDiriGP" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Acuh tak acuh"
                                        label="Acuh tak acuh" v-model="input.CBAcuhTakAcuhGP" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Mudah Tersinggung"
                                        label="Mudah Tersinggung" v-model="input.CBMudahTersinggungGP" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Menarik Diri"
                                        label="Menarik Diri" v-model="input.CBMenarikDiriGP" />
                                </VControl>
                            </div>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-12">
                                <span>Status Pernikahan</span>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Single" label="Single"
                                        v-model="input.CBSingleSP" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol style="margin-bottom: 5px;">
                                    <VCheckbox class="p-0" color="primary" square true-value="Menikah" label="Menikah"
                                        v-model="input.CBMenikahSP" />
                                </VControl>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBMenikahSP" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Kali</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Bercerai" label="Bercerai"
                                        v-model="input.CBBerceraiSP" />
                                </VControl>
                            </div>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-12">
                                <span>Umur Pertama kali menikah</span>
                            </div>
                            <div class="column is-4">
                                <VField label="&nbsp;">
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBupkm" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>Tahun</VButton>
                                        </VControl>
                                    </VField>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Kawin dengan suami 1">
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBupkm" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>Tahun</VButton>
                                        </VControl>
                                    </VField>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Ke 2,3">
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBke23" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>Tahun</VButton>
                                        </VControl>
                                    </VField>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-12">
                                <span>Masalah Perkawinan</span>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak Ada"
                                        label="Tidak Ada" v-model="input.CBTidakAdaMP" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Ada" label="Ada"
                                        v-model="input.CBAdaMP" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VField label="Jelaskan">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBJelaskanMP" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-12">
                                <span>Mengalami Kekerasan Fisik</span>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak Ada"
                                        label="Tidak Ada" v-model="input.CBTidakAdaMKF" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Ada" label="Ada"
                                        v-model="input.CBAdaMKF" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VField label="Jelaskan">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBJelaskanMKF" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-12">
                            <VField label="Keyakinan dan nilai pribadi">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBkeyakinanDanNilaiPribadi" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-12">
                                <span>Pembiayaan Kesehatan</span>
                            </div>
                            <div class="column is-6">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Biaya sendiri/keluarga"
                                        label="Biaya sendiri/keluarga" v-model="input.CBBiayaSendiriAtauKeluargaPK" />
                                </VControl>
                            </div>
                            <div class="column is-6">
                                <VControl raw subcontrol style="margin-bottom: 5px;">
                                    <VCheckbox class="p-0" color="primary" square true-value="Asuransi Lainnya"
                                        label="Asuransi Lainnya" v-model="input.CBAsuransiLainnyaPK" />
                                </VControl>
                                <VField horizontal>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBAsuransiLainnyaPK" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-12">
                            <VField label="Kebiasaan adat istiadat yang mempengaruhi kesehatan : ">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBkaiymk" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-12">
                                <span>Dukungan sosial dari</span>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Suami" label="Suami"
                                        v-model="input.CBSuamiDSD" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Orang Tua"
                                        label="Orang Tua" v-model="input.CBOrangTuaDSD" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Keluarga" label="Keluarga"
                                        v-model="input.CBKeluargaDSD" />
                                </VControl>
                            </div>
                            <div class="column is-3">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                                        v-model="input.CBLainnyaDSD" />
                                </VControl>
                            </div>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-12">
                                <span>Kebiasaan Ibu</span>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Merokok" label="Merokok"
                                        v-model="input.CBMerokokKI" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Minum Alkohol"
                                        label="Minum Alkohol" v-model="input.CBMinumAlkoholKI" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                                        v-model="input.CBLainnyaKI" />
                                </VControl>
                            </div>
                        </div>
                        <div class="column is-12 columns is-multiline">
                            <div class="column is-12">
                                <span>Perlu Rohaniawan</span>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                                        v-model="input.CBYaPR" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                        v-model="input.CBTidakPR" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="E. ASESMEN KEBUTUHAN INFORMASI DAN EDUKASI">
                    <div class="column is-12">
                        <span>Lihat pada form komunikasi dan edukasi</span>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="F. SKRINNING NUTRISI">
                    <div class="column is-12" style="overflow: auto;">
                        <table class="tg2">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th style="text-align: center;vertical-align: middle;">Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, rowIndex) in detailSkriningNutrisi" :key="rowIndex">
                                    <td width="200px" v-for="(item, itemIndex) in row.child" :key="itemIndex"
                                        :colspan="item.colspan" :rowspan="item.rowspan" :style="item.style">
                                        <VField style="padding: 0px 10px;" v-if="item.type === 'textbox'">
                                            <VControl raw subcontrol>
                                                <input v-model="input.jumlahNilaiSN" class="input" disabled />
                                            </VControl>
                                        </VField>
                                        <VField style="padding: 0px 10px;" v-if="item.type === 'nilai'">
                                            <h1>{{ item.caption }}</h1>
                                        </VField>
                                        <VField style="padding: 0px 10px;" v-if="item.type === 'text'">
                                            <span>{{ item.caption }}</span>
                                        </VField>
                                        <VControl raw subcontrol v-if="item.type === 'checkbox'">
                                            <VCheckbox class="p-0" color="primary" square :true-value="item.caption"
                                                :label="item.caption"
                                                v-model="input['checkboxSN_' + rowIndex + '_' + itemIndex]" />
                                        </VControl>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="column is-12 columns is-multiline">
                        <div class="column is-12">
                            <span>Pasien dengan diagnosa khusus : </span>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                                    v-model="input.CByaPDDK" />
                            </VControl>
                        </div>
                        <div class="column is-6">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                    v-model="input.CBtidakPDDK" />
                            </VControl>
                        </div>
                        <div class="column is-12">
                            <span>Nilai : </span>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Tidak (0)" label="Tidak (0)"
                                    v-model="input.CBtidakadaPDDK" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Risiko rendah (MST 0-1)"
                                    label="Risiko rendah (MST 0-1)" v-model="input.CBrisikoRendahPDDK" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Risiko sedang (MST 2-3)"
                                    label="Risiko sedang (MST 2-3)" v-model="input.CBrisikoSedangPDDK" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Risiko tinggi (MST 4-5)"
                                    label="Risiko tinggi (MST 4-5)" v-model="input.CBrisikoTinggiPDDK" />
                            </VControl>
                        </div>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="G. STATUS FUNGSIONAL">
                    <div class="column" style="overflow: auto;">
                        <table class="tg">
                            <thead>
                                <tr>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">No</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">Fungsi</th>
                                    <th style="text-align: center;vertical-align: middle;" colspan="4">Skor</th>
                                    <th style="text-align: center;vertical-align: middle;" rowspan="2">Skor</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;vertical-align: middle;">0</th>
                                    <th style="text-align: center;vertical-align: middle;">1</th>
                                    <th style="text-align: center;vertical-align: middle;">2</th>
                                    <th style="text-align: center;vertical-align: middle;">3</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in statusFungsional.statusFungsional" :key="index">
                                    <td><h1 style="font-weight: bold;">{{ index + 1 }}<br></h1></td>
                                    <td><h1 style="font-weight: bold;">{{ item.fungsi }}<br></h1></td>
                                    <td v-for="(detail, indexDetail) in item.detail" :key="indexDetail">
                                        <VField v-if="detail.type == 'checkbox' ">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square :label="detail.caption" :true-value="detail.caption" v-model="input[`checkBox_${item.fungsi}_${indexDetail}`]"/>
                                            </VControl>
                                        </VField>
                                        <VField v-if="detail.type == 'textbox' ">
                                            <VControl raw subcontrol>
                                                <VInput v-model="input[`textbox_${item.fungsi}_${indexDetail}`]" class="input" :placeholder="`${detail.caption} ${item.fungsi}`" />
                                            </VControl>
                                        </VField>
                                        <VField v-if="detail.type == 'skor' ">
                                          <VControl raw subcontrol>
                                            <VInput v-model="input[`textbox_${item.fungsi}_skor`]" class="input" :placeholder="detail.caption" disabled />
                                          </VControl>
                                        </VField>

                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <h1>Keterangan</h1>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Ketergantungan total (0-4)"
                                                        label="Ketergantungan total (0-4)"
                                                        v-model="input.CBKetergantunganTotal" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Ketergantungan berat (5-8)"
                                                        label="Ketergantungan berat (5-8)"
                                                        v-model="input.CBKetergantunganBerat" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Ketergantungan sedang (9-11)"
                                                        label="Ketergantungan sedang (9-11)"
                                                        v-model="input.CBKetergantunganSedang" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Ketergantungan ringan(12-19)"
                                                        label="Ketergantungan ringan(12-19)"
                                                        v-model="input.CBKetergantunganRingan" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        true-value="Mandiri (20)" label="Mandiri (20)"
                                                        v-model="input.CBKMandiriK" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="column" style="text-align:center;">
                                            <span>Total</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="column">&nbsp;</div>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBtotal2SF" disabled />
                                        </VControl>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="H. ASESMEN RISIKO JATUH">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <span>1. Perhatikan cara berjalan pasien saat akan duduk di kursi. Apakah pasien tampak
                                tidak seimbang
                                (sempoyongan/limbung)?</span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                                            v-model="input.CBYaARJ1" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.CBTidakARJ1" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <span>2. Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai benda lain
                                sebagai
                                penopang saat akan duduk?</span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                                            v-model="input.CBYaARJ2" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.CBTidakARJ2" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="columns column is-12 is-multiline">
                            <div class="column is-12">
                                <h1>Hasil : </h1>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square
                                        true-value="Tidak berIsiko (tidak ditemukan a dan b)"
                                        label="Tidak berIsiko (tidak ditemukan a dan b)"
                                        v-model="input.CBTidakBeresikoHasil" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square
                                        true-value="Risiko rendah ( a atau b ditemukan)"
                                        label="Risiko rendah ( a atau b ditemukan)"
                                        v-model="input.CBEesikoRendahHasil" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square
                                        true-value="Risiko tinggi ( a dan b ditemukan)"
                                        label="Risiko tinggi ( a dan b ditemukan)"
                                        v-model="input.CBResikoTinggiHasil" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns column is-12 is-multiline">
                            <div class="column is-12">
                                <h1>Tindakan : </h1>
                            </div>
                            <div class="column is-12 columns">
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak ada tindakan"
                                            label="Tidak ada tindakan" v-model="input.CBTidakAdaTindakan" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Edukasi"
                                            label="Edukasi" v-model="input.CBEdukasiTindakan" />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            true-value="Pasang penanda risiko jatuh" label="Pasang penanda risiko jatuh"
                                            v-model="input.CBPasangPenandaResikoJatuh" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="I. RIWAYAT PENGGUNAAN OBAT">
                    <VField>
                        <VTextarea rows="2" v-model="input.TARiwayatPengunaanObat"></VTextarea>
                    </VField>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="J. DIAGNOSA KEBIDANAN">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>G</VButton>
                                </VControl>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBgDK" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>P</VButton>
                                </VControl>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBpDK" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>UK</VButton>
                                </VControl>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBukDK" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>Minggu</VButton>
                                </VControl>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBmingguDK" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>Hari</VButton>
                                </VControl>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBhariDK" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="column">
                        <VField>
                            <VTextarea rows="2" v-model="input.TA1dk"></VTextarea>
                        </VField>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>P</VButton>
                                </VControl>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBp2DK" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>A</VButton>
                                </VControl>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBaDK" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="column">
                        <VField>
                            <VTextarea rows="2" v-model="input.TA2dk"></VTextarea>
                        </VField>
                    </div>
                    <div class="column">
                        <VField label="Akseptor baru kontrasepsi">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBAkseptorBaruKontraSepsi" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column">
                        <VField label="Akseptor lama kontrasepsi">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBAkseptorLamaKontraSepsi" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="columns">
                        <div class="column is-6">
                            <VField label="Akseptor Lama">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBAkseptorLama" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Ganti cara ke kontrasepsi">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBGantiCaraKeKontrasepsi" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="column">
                        <span>PUS / WUS dengan pilihan kontrasepsi yang belum rasional</span>
                    </div>
                    <div class="column">
                        <VField>
                            <VTextarea rows="2" v-model="input.TApusWus"></VTextarea>
                        </VField>
                    </div>
                </Fieldset>

                <br>
                <hr><br>

                <Fieldset :toggleable="true" legend="K. RENCANA KEBIDANAN">
                    <table class="tg2">
                        <tr v-for="(row, rowIndex) in detailRencanaKebidanan" :key="rowIndex">
                            <td width="200px" v-for="(item, itemIndex) in row.child" :key="itemIndex">
                                <VControl raw subcontrol v-if="item.type == 'checkbox'">
                                    <VCheckbox class="p-0" color="primary" square :true-value="item.caption"
                                        :label="item.caption"
                                        v-model="input['checkboxRK_' + rowIndex + '_' + itemIndex]" />
                                </VControl>
                                <div class="columns" v-if="item.type == 'checkboxTB'" style="padding: 5px">
                                    <div class="column is-1">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="true"
                                                v-model="input['checkboxTBRK_' + rowIndex + '_' + itemIndex]" />
                                        </VControl>
                                    </div>
                                    <div class="column is-11">
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Jelaskan..."
                                                v-model="input['cbTextboxRK_' + rowIndex + '_' + itemIndex]" />
                                        </VControl>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </Fieldset>

                <br>
                <hr><br>
                <div class="columns">
                    <div class="column is-8"></div>
                    <div class="column is-4">
                        <VField label="Garut">
                            <VDatePicker v-model="input.DTttd" mode="datetime"  :is24hr="true" :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                        <div class="column" style="text-align:center;">
                            <h1>Tanda Tangan Bidan</h1>
                            <TandaTangan :elemenID="'TTDBidan'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.CBBidan" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                </div>
                <!-- form baru -->
            </div>
        </div>

    </div>
</template>
