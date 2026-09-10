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
    title: 'Status Hemodialisis - ' + import.meta.env.VITE_PROJECT,
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
const COLLECTION: any = ref('StatusHemodialisa') //table mongodb
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
// const loadRiwayat = async () => {
//     let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
//     if (response.length) {
//         input.value = response[0] //set ke inputan
//         if (NOREC_EMRPASIEN.value == '') {
//             NOREC_EMRPASIEN.value = response[0].emrpasienfk
//         }
//     } else {
//         let dataRegis = H.setObjectRegistrasi(pasien.value.registrasi)
//         let dataPasien = H.setObjectPasien(pasien.value)
//         input.value.TBAlamat = dataPasien.alamatlengkap
//         input.value.TBNamaPasien = dataPasien.namapasien
//         input.value.DTanggalLahir = dataPasien.tgllahir
//         input.value.TBNomorTeleponPasien = dataPasien.notelepon ? dataPasien.notelepon : dataPasien.nohp;
//     }
//     // input.value.DDRuangan = dataRegis.namaruangan
//     // input.value.DDDokter = dataRegis.dokter
//     // input.value.TBSTahun = calculateAge(dataPasien.tgllahir)
//     // input.value.TBJenisKelamin = dataPasien.jeniskelamin
//     // input.value.TBAgama = dataPasien.agama
// }
const loadRiwayat = async () => {
  try {
    const response: any = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`);
    if (response.length) {
      input.value = response[0];
      if (NOREC_EMRPASIEN.value === '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
      // dataTTD.value = response[0];
      // for (let i = 0; i < input.value.details.length; i++) {
      //   await nextTick();//test bawa di sini
      //   const fieldName = `parafPasien_${i}`;
      //   H.tandaTangan().set(fieldName, dataTTD.value[fieldName]);
      // }
    } else {
      setAutoFill();
    }
  } catch (error) {
    console.error('Error loading data:', error);
  }
};
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

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
    console.log(json)

    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            // NOREC_EMRPASIEN.value = response.norec_emr
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
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}
const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
        if (response != null || response != undefined) {
            // input.value.TBSBeratBadan = response.beratBadan
            // input.value.TBSTinggiBadan = response.tinggiBadan
            // input.value.IMT = response.IMT
            // input.value.lingkarPerut = response.lingkarPerut
            // input.value.nadiObgyn = response.nadi
            // input.value.celciusObgyn = response.suhu
            // input.value.TBSTekananDarah = response.tekananDarah
            // input.value.nafasObgyn = response.pernapasan
            // input.value.sao2Obgyn = response.SPO2
        }
    })
}
// const print = async () => {
//     H.printBlade(`emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
// }

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

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
            isLoading.value = false
            console.log(responselast)
            if (responselast.length) {
                for (var x = 0; x < responselast.length; x++) {
                    responselast[x].no = x + 1
                    responselast[x].id = ''
                }
                listTemplateFix.value = responselast //set ke inputan
                showModalTemplateFix.value = true
            } else {
                H.alert('warning', 'Data tidak ada')
            }
        })
}
const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('error', 'Nama Template harus diisi untuk menyimpan.');
        return;
    }
    let ID = input.id ? input.id : ''
    let object: any = {}
    object = input.value
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
    isLoading.value = true

    useApi().post(
        `/emr/simpan-emr-template`, json).then((response: any) => {
            isLoading.value = false
            input.value.namatemplate = null
        }).catch((e: any) => {
            isLoading.value = false
        })
}

// getDataExist()
fetchPasien()
</script>

<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Status Hemodialisis</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                        isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                    </VButton>
                </div>

                <hr class="m-0">

                <div class="column is-12">
                    <h1>Nama Template&emsp;&emsp;
                        <span style="color:red">**Hanya diisi jika ingin membuat template</span>
                    </h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.namatemplate" rows="1">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>

                <hr class="m-0">

                <div class="column is-12">
                    <label style="font-weight: bold;text-decoration: underline;">IDENTITAS PASIEN</label>
                    <div class="column is-12 columns">
                        <div class="column is-4">Nama :</div>
                        <div class="column is-8">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNamaPasien" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns" style="margin-top: -20px;">
                        <div class="column is-4">Tanggal lahir :</div>
                        <div class="column is-8">
                            <VDatePicker v-model="input.DTanggalLahir" mode="date" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                    <div class="column is-12 columns" style="margin-top: -20px;">
                        <div class="column is-4">Alamat :</div>
                        <div class="column is-8">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBAlamat" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns" style="margin-top: -20px;">
                        <div class="column is-4">Telp :</div>
                        <div class="column is-8">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBNomorTeleponPasien" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns" style="margin-top: -20px;">
                        <div class="column is-4">Diagnosa :</div>
                        <div class="column is-8">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBDiagnosa" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-12 columns" style="margin-top: -20px;">
                        <div class="column is-4">Mulai HD :</div>
                        <div class="column is-8">
                            <VDatePicker v-model="input.DTMulaiHD" mode="datetime" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                    <div class="column is-12 columns" style="margin-top: -20px;">
                        <div class="column is-4">Cara bayar :</div>
                        <div class="column is-8">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBCaraBayar" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <div class="column is-12" style="margin-top: 20px;">
                    <label style="font-weight: bold;text-decoration: underline;">AKSES VASKULER</label>
                    <div class="columns">
                        <div class="columns is-multiline column is-6">
                            <div class="column">1. Cimino Shunt</div>
                            <div class="columns column is-12">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ada" label="Ada"
                                            v-model="input.CBCiminoShunt" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak ada"
                                            label="Tidak ada" v-model="input.CBCiminoShunt" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="columns is-multiline column is-6">
                            <div class="column">Tanggal Operasi</div>
                            <div class="columns column is-12">
                                <div class="column is-6">
                                    <VField label="A.">
                                        <VDatePicker v-model="input.DTTanggalOperasi1" mode="datetime" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField label="B.">
                                        <VDatePicker v-model="input.DTTanggalOperasi2" mode="datetime" trim-weeks
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

                    <div class="columns">
                        <div class="columns is-multiline column is-6">
                            <div class="column">2. Femoral</div>
                            <div class="columns column is-12">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ada" label="Ada"
                                            v-model="input.CBFemoral" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak ada"
                                            label="Tidak ada" v-model="input.CBFemoral" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="columns is-multiline column is-6">
                            <div class="column">Riwayat Alergi</div>
                            <div class="columns column is-12">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.CBRiwayatAlergi" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                                            v-model="input.CBRiwayatAlergi" />
                                    </VControl>
                                    <VControl style="margin-top: 5px">
                                        <VInput type="text" class="input" v-model="input.TBRiwayatAlergi" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12"><i>*) coret yang tidak perlu</i></div>
                </div>
                <!-- form baru -->

            </div>
        </div>

    </div>
</template>
