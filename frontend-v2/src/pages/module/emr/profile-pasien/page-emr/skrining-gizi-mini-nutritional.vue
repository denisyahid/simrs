<template>
    <MasterEMR :isTTD="true" :fieldTTD="'peralihanDPJP'" @simpan="simpan()" @simpanTemplate="simpanTemplate()"
        :ID_PASIEN="ID_PASIEN" :NOREC_PD="NOREC_PD" :norec_emr="norec_emr" :input="input" :FORM_NAME="props.FORM_NAME"
        :FORM_URL="props.FORM_URL" :registrasi="props.registrasi" :pasien="props.pasien" :COLLECTION="props.COLLECTION"
        ref="masterRef" :isLoading="isLoading">
        <template #content>
            <VCard>
                <div class="columns is-multiline m-0">
                    <div class="column is-4">
                        <h1 style="font-weight: bold">Ruangan:</h1>
                        <VControl>
                            <VInput type="text" class="input" placeholder="Ruangan" v-model="input.ruangan" disabled />
                        </VControl>
                    </div>

                    <div class="column is-4">
                        <h1 style="font-weight: bold">Tanggal</h1>
                        <VField>
                            <VDatePicker v-model="input.tanggal" mode="date" style="width: 100%;" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField style="margin-bottom: 0.70rem;">
                                        <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>

                    <div class="column is-4">
                        <h1 style="font-weight: bold">Jam</h1>
                        <VDatePicker v-model="input.Jam" mode="time" is24hr>
                            <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:clock" fullwidth>
                                    <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                            </template>
                        </VDatePicker>
                    </div>
                </div>
            </VCard>
            <VCard class="mt-5">
                <h4 class="text-center mb-5" style="font-size: 15px;">
                    Lengkapi Skrining gizi berikut dengan mengisi kotak yang tersedia dengan angka yang sesuai
                </h4>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <table border="1" width="100%" style="" class="table-v-center">
                        <thead>
                            <tr>
                                <th colspan="3">Skrining</th>
                            </tr>
                        </thead>
                        <tbody style="vertical-align: middle;">
                            <tr>
                                <td rowspan="5">A</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="background-color: #e8e7e6;">
                                    1. Apakah asupan makanan berkurang selama 3 bulan terakhir&nbsp;
                                    karena kehilangan nafsu makan, gangguan pencernaan, kesulitan mengunyah&nbsp;
                                    atau menelan?
                                </td>
                            </tr>
                            <tr>
                                <td>0 = Asupan makan sangat berkurang</td>
                                <td>
                                    <VCheckbox v-model="input.asupanMakan" color="primary" :true-value="0" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>1 = Asupan makan agak berkurang</td>
                                <td>
                                    <VCheckbox v-model="input.asupanMakan" color="primary" :true-value="1" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>2 = Asupan makan tidak berkurang</td>
                                <td>
                                    <VCheckbox v-model="input.asupanMakan" color="primary" :true-value="2" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <!-- B -->
                            <tr>
                                <td rowspan="6">B</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="background-color: #e8e7e6;">
                                    2. Penurunan berat badan selama 3 bulan terakhir
                                </td>
                            </tr>
                            <tr>
                                <td>0 = Penurunan berat badan lebih dari 3 kg</td>
                                <td>
                                    <VCheckbox v-model="input.turunBB" color="primary" :true-value="0" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>1 = Tidak tahu</td>
                                <td>
                                    <VCheckbox v-model="input.turunBB" color="primary" :true-value="1" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>2 = Penurunan berat badan antara 1 hingga 3 kg</td>
                                <td>
                                    <VCheckbox v-model="input.turunBB" color="primary" :true-value="2" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>3 = Tidak ada penurunan berat badan</td>
                                <td>
                                    <VCheckbox v-model="input.turunBB" color="primary" :true-value="3" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <!-- C -->
                            <tr>
                                <td rowspan="5">C</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="background-color: #e8e7e6;">
                                    3. Mobilitas
                                </td>
                            </tr>
                            <tr>
                                <td>0 = Terbatas ditempat tidur atau kirsi</td>
                                <td>
                                    <VCheckbox v-model="input.mobilitas" color="primary" :true-value="0" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>1 = Mampu bangun dari tempat tidur/kursi tetapi tidak bepergian keluar rumah</td>
                                <td>
                                    <VCheckbox v-model="input.mobilitas" color="primary" :true-value="1" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>2 = Dapat bepergian keluar rumah</td>
                                <td>
                                    <VCheckbox v-model="input.mobilitas" color="primary" :true-value="2" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <!-- D -->
                            <tr>
                                <td rowspan="4">D</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="background-color: #e8e7e6;">
                                    4. Menderita tekanan psikologis atau penyakit berat dalam 3 bulan terakhir
                                </td>
                            </tr>
                            <tr>
                                <td>0 = Ya</td>
                                <td>
                                    <VCheckbox v-model="input.psikologis" color="primary" :true-value="0" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>2 = Tidak</td>
                                <td>
                                    <VCheckbox v-model="input.psikologis" color="primary" :true-value="2" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <!-- E -->
                            <tr>
                                <td rowspan="5">E</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="background-color: #e8e7e6;">
                                    5. Gangguan Neuropsikologis
                                </td>
                            </tr>
                            <tr>
                                <td>0 = Depresi berat atau kepikunan berat</td>
                                <td>
                                    <VCheckbox v-model="input.neuropsikologis" color="primary" :true-value="0" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>1 = Kepikunan ringan</td>
                                <td>
                                    <VCheckbox v-model="input.neuropsikologis" color="primary" :true-value="1" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>2 = Tidak ada gangguan psikologis</td>
                                <td>
                                    <VCheckbox v-model="input.neuropsikologis" color="primary" :true-value="2" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <!-- F1 -->
                            <tr>
                                <td rowspan="6">F1</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="background-color: #e8e7e6;">
                                    6. Indeks Masa Tubuh (IMT) (berat dalam kg)/(tinggi dalam m)&sup2;
                                </td>
                            </tr>
                            <tr>
                                <td>0 = IMT kurang dari 19</td>
                                <td>
                                    <VCheckbox v-model="input.imt" color="primary" :true-value="0" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>1 = IMT 19 hingga kurang dari 21</td>
                                <td>
                                    <VCheckbox v-model="input.imt" color="primary" :true-value="1" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>2 = IMT 21 hingga 23</td>
                                <td>
                                    <VCheckbox v-model="input.imt" color="primary" :true-value="2" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>3 = IMT 23 atau lebih</td>
                                <td>
                                    <VCheckbox v-model="input.imt" color="primary" :true-value="3" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center" style="background-color: #e8e7e6;">
                                    <span style="font-weight: bold;">
                                        BILA DATA IMT TIDAK ADA, GANTI PERTANYAAN F1 DENGAN F2
                                    </span>
                                    <br>
                                    <span style="font-weight: bold;">
                                        ABAIKAN PERTANYAAN F2 BILA PERTANYAAN F1 SUDAH DAPAT DIISI
                                    </span>
                                </td>
                            </tr>
                            <!-- F2 -->
                            <tr>
                                <td rowspan="6">F2</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="background-color: #e8e7e6;">
                                    7. Lingkar betis (cm)
                                </td>
                            </tr>
                            <tr>
                                <td>0 = Lingkar betis kurang dari 31</td>
                                <td>
                                    <VCheckbox v-model="input.imtf2" color="primary" :true-value="0" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                            <tr>
                                <td>3 = Lingkar betis sama dengan atau lebih besar dari 31</td>
                                <td>
                                    <VCheckbox v-model="input.imtf2" color="primary" :true-value="3" @change.stop="handleSkor()"/>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-center" style="font-weight: bold; background-color: #e8e7e6;">
                                    Skor Skrining (Skor maksimal 14)
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Skor 12 - 14
                                </td>
                                <td>Status Gizi Normal</td>
                                <td>
                                    <VCheckbox v-model="input.giziNormal" color="primary" disabled/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Skor 8 - 11
                                </td>
                                <td>Beresiko malnutrisi</td>
                                <td>
                                    <VCheckbox v-model="input.resikomal" color="primary" disabled/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Skor 0 - 7
                                </td>
                                <td>Malnutrisi</td>
                                <td>
                                    <VCheckbox v-model="input.malnutrisi" color="primary" disabled/>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: end; padding-right: 20px; font-weight: bold; font-size: 15px;" colspan="3">
                                    Total Skor : {{ input.totalSkor }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </VCard>
            <!-- <VCard class="mt-5">
                <div class="columns is-multiline m-0">
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-6"></div>
                            <div class="column is-6">
                                <VField label="Garut">
                                    <VDatePicker v-model="input.tanggalPengisian" mode="datetime" trim-weeks
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
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <div style="text-align:center;" class="">
                                    <h1>Dokter Penanggung Jawab Pelayanan</h1>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.DokterPenanggungJawab" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" class="mt-2" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div style="text-align:center;">
                                    <h1>Dokter Pemeriksa</h1>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" class="mt-2" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </VCard> -->
        </template>
    </MasterEMR>
</template>

<script setup lang="ts">
import MasterEMR from './master-emr.vue'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const masterRef = ref(null)
const dataPasien = '';
const d_Dokter: any = ref([]);
const d_Petugas: any = ref([]);
const dataTTD: any = ref([])
const NOREC_EMRPASIEN: any = ref('')
const isLoading: any = ref(false);
const COLLECTION: any = ref(props.COLLECTION) //table mongodb

const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
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
const input: any = ref({
    tanggal: new Date(),
    Jam: new Date(),
    tanggalPengisian: new Date(),
    totalSkor: 0,
})

const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    DEPARTEMEN_FK: props.registrasi.objectdepartemenfk,
    registrasi: {
        ruanganfk: props.registrasi.objectruanganlastfk,
        departemenfk: props.registrasi.objectdepartemenfk,
    }
})


const fetchDokter = async (filter: any) => {
    d_Dokter.value = await H.fetchDokter(filter);
}

const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
}


const setAutoFill = async () => {
    input.value.DPJP = props.registrasi.dokter
    input.value.DokterPenanggungJawab = props.registrasi.dokter
    input.value.ruangan = props.registrasi.namaruangan
};

const loadRiwayat = async () => {
    isLoading.value = true;
    await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        isLoading.value = false;
        if (response.length) {
            input.value = response[0] //set ke inputan
            if (NOREC_EMRPASIEN.value == '') {
                NOREC_EMRPASIEN.value = response[0].emrpasienfk
            }
            dataTTD.value = response[0]
            H.tandaTangan().set('TTDpasien', dataTTD.value.TTDpasien)
        }
        else {
            setAutoFill()
        }
    })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    console.log("Pasien", props.pasien);
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDpasien'] = H.tandaTangan().get('TTDpasien')

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
            loadRiwayat();
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('warning', "Nama Template wajib diisi")
        return;
    }
    let ID = input.id ? input.id : ''

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
        `/emr/simpan-emr-template`, json).then((response: any) => {
            isLoading.value = false
            input.value.namatemplate = null
        }).catch((e: any) => {
            isLoading.value = false
        })
}


const triggerAllData = async () => {
    if (masterRef.value) {
        let ss = await masterRef.value.loadRiwayat()
        if (ss != null) {
            input.value = ss
        }
    }
}

const handleSkor = () => {
    input.value.totalSkor = 0;
    let newSkor = 0;
    let keysInput = Object.keys(input.value);
    for (let index = 0; index < keysInput.length; index++) {
        let object = keysInput[index];
        const element = input.value[object];
        if(!isNaN(element) && 
        object != 'totalSkor' && 
        object != 'tanggal' && 
        object != 'Jam' && 
        object != 'tanggalPengisian' && 
        object != 'namatemplate' &&
        object != 'malnutrisi' &&
        object != 'resikomal' &&
        object != 'giziNormal' &&
        object != 'DokterPenanggungJawab'
    ) {
            if(element == false || element == null) {
                continue;
            }
            newSkor += parseInt(element);
        }
    }
    if(newSkor > 14) {
        newSkor = 14;
    }
    if(newSkor <= 7) {
        input.value.malnutrisi = true;
        input.value.resikomal = false;
        input.value.giziNormal = false;
    } else if(newSkor > 7 && newSkor <= 11) {
        input.value.malnutrisi = false;
        input.value.resikomal = true;
        input.value.giziNormal = false;
    } else {
        input.value.malnutrisi = false;
        input.value.resikomal = false;
        input.value.giziNormal = true;
    }
    input.value.totalSkor = newSkor;
}


onMounted(() => {
    triggerAllData()
    setView()
    fetchDokter();
    setAutoFill();
})


</script>

<style lang="scss">
.text-bold {
    font-weight: bold;
}

.table-v-center td {
    vertical-align: middle !important;
    padding: 5px;
    padding-left: 10px;
}
</style>