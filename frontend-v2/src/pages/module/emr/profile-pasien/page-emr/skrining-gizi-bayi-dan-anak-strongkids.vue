<template>
    <MasterEMR :isTTD="true" :fieldTTD="'peralihanDPJP'" @simpan="simpan()" @simpanTemplate="simpanTemplate()" :ID_PASIEN="ID_PASIEN" 
       :NOREC_PD="NOREC_PD" :norec_emr="norec_emr" :input="input" :FORM_NAME="props.FORM_NAME" 
       :FORM_URL="props.FORM_URL" :registrasi="props.registrasi" :pasien="props.pasien"
       :COLLECTION="props.COLLECTION" ref="masterRef" :isLoading="isLoading">
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
                <div class="column is-12 pb-0 mb-0">
                    <table border="1" width="100%" style="" class="table-v-center">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>PERTANYAAN</th>
                                <th style="text-align: center;">YA</th>
                                <th style="text-align: center;">TIDAK</th>
                            </tr>
                        </thead>
                        <tbody style="vertical-align: middle;">
                            <tr>
                                <td>1</td>
                                <td>
                                    Apakah terdapat penyakit atau keadaan (lihat daftar) yang mengakibatkan&nbsp;
                                    pasien beresiko malnutrisi, atau apakah pasien rencana operasi mayor?
                                </td>
                                <td>
                                    <VCheckbox @change.stop="handleSkor()"
                                        v-model="input.penyakitDaftar"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox @change.stop="handleSkor()"
                                        v-model="input.penyakitDaftar"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    Berdasarkan penilaian klinis, apakah pasien berstatus gizi kurang / buruk?
                                </td>
                                <td>
                                    <VCheckbox @change.stop="handleSkor()"
                                        v-model="input.kurangGizi"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox @change.stop="handleSkor()"
                                        v-model="input.kurangGizi"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <ul style="margin: 10px;">
                                        <li>
                                            <span>
                                                Apakah terdapat <b>SALAH SATU</b> dari kondisi berikut?
                                            </span>
                                            <ul style="margin-left: 20px; list-style-type: disc;">
                                                <li>Diare &ge; 5 kali/hari atau muntah > 3 kali/hari</li>
                                                <li>Asupan makanan berkurang selama beberapa hari terakhir</li>
                                                <li>Mendapatkan intervensi nutrisi</li>
                                                <li>Tidak mampu mengonsumsi nutrisi adekuat karena nyeri</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <VCheckbox @change.stop="handleSkor()"
                                        v-model="input.kondisiTertentu"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox @change.stop="handleSkor()"
                                        v-model="input.kondisiTertentu"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    Apakah terdapat penurunan berat badan (untuk bayi &lt; 1 tahun: berat badan tidak naik) selama beberapa bulan terakhir?
                                </td>
                                <td>
                                    <VCheckbox @change.stop="handleSkor()"
                                        v-model="input.turunBB"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox @change.stop="handleSkor()"
                                        v-model="input.turunBB"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <b>Total Skor</b>
                                </td>
                                <td colspan="2" style="text-align: center;">
                                    <b>{{ input.totalSkor }}</b>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="column is-12 mt-3">
                    <table border="1" width="100%" style="" class="table-v-center">
                        <thead>
                            <tr>
                                <th colspan="4" style="text-align: center;">
                                    Daftar Penyakit/ Keadaan Beresiko Malnutrisi
                                </th>
                            </tr>
                        </thead>
                        <tbody style="vertical-align: middle;">
                            <tr>
                                <td>Anorexia nervosa</td>
                                <td>Dismaturitas/premature (Usia koreksi 6 bulan)</td>
                                <td>Penyakit hati kronik</td>
                                <td>Penyakit metabolik</td>
                            </tr>
                            <tr>
                                <td>Luka Bakar</td>
                                <td>Penyakit jantung kronik</td>
                                <td>Penyakit gagal ginjal kronik</td>
                                <td>Trauma</td>
                            </tr>
                            <tr>
                                <td>Displasia Bronkopulmoner (Usia max 2 tahun)</td>
                                <td>Penyakit infeksi HIV</td>
                                <td>Pankreatitis</td>
                                <td>Retardasi mental</td>
                            </tr>
                            <tr>
                                <td>Penyakit celiac</td>
                                <td>Inflamatory bowel disease</td>
                                <td>Short bowel syndrome</td>
                                <td>Cystic fibrosis</td>
                            </tr>
                            <tr>
                                <td>Kanker</td>
                                <td>Penyakit otot</td>
                                <td colspan="2">Lain - lain (bedasarkan pertimbangan dokter)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </VCard>
       </template>
    </MasterEMR>
   </template>
   
   <script setup lang="ts">
   import  MasterEMR from './master-emr.vue'
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
        totalSkor: 0
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
       console.log("Pasien",props.pasien);
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
    if(!input.value.namatemplate) {
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
       input.value.namatemplate =  null
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
    let ival = input.value;
    if(ival.penyakitDaftar && ival.penyakitDaftar == 'Ya') {
        newSkor = newSkor + 2
    }

    if(ival.kurangGizi && ival.kurangGizi == 'Ya') {
        newSkor = newSkor + 1
    }

    if(ival.kondisiTertentu && ival.kondisiTertentu == 'Ya') {
        newSkor = newSkor + 1
    }

    if(ival.turunBB && ival.turunBB == 'Ya') {
        newSkor = newSkor + 1
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
   
   .table-v-center td{
    vertical-align: middle !important;
    padding: 5px;
    padding-left :10px;
   }
   </style>
   