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
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <table border="1" width="100%" style="border: 1px solid var(--fade-grey-dark-3);" class="table-v-center">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KRITERIA</th>
                                <th>YA</th>
                                <th>TIDAK</th>
                            </tr>
                        </thead>
                        <tbody style="vertical-align: middle;">
                            <tr>
                                <td>1</td>
                                <td>Usia lanjut dengan dimensia</td>
                                <td>
                                    <VCheckbox
                                        v-model="input.dimensia"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.dimensia"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    Pasien dengan fungsi kognitif rendah
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.kognitif"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.kognitif"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    Pasien dengan risiko tinggi
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.risikoTinggi"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.risikoTinggi"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    Potensi komplain tinggi
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.komplain"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.komplain"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    Kasus dengan penyakit kronis, katastropik, terminal
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.kasusPenyakit"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.kasusPenyakit"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>
                                    Status fungsional rendah, kebutuhan bantuan ADL (<i> Activity Daily Living </i>) <br>
                                    yang tinggi
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.kebutuhanBantuan"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.kebutuhanBantuan"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>
                                    Pasien dengan riwayat penggunaan peralatan medis dimasa lalu
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.penggunaanMedis"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.penggunaanMedis"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>
                                    Pasien dengan riwayat Gangguan Mental, upaya bunuh diri, <br>
                                    krisis keluarga, isu sosial a.l. terlantar, tinggal sendiri, <br>
                                    narkoba.
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.riwayatGangguan"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.riwayatGangguan"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>
                                    Pasien sering masuk IGD, readmisi RS
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.seringIGD"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.seringIGD"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>
                                    Perkiraan asuhan dgn biaya tinggi
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.biayaTinggi"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.biayaTinggi"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>
                                    Kemungkinan sistem pembiayaan yang kompleks, adanya <br>
                                    masalah finansial
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.biayaKompleks"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.biayaKompleks"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>
                                    Kasus yang melebihi rata rata lama dirawat
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.lamaDirawat"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.lamaDirawat"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>
                                    Kasus yang diidentifikasi rencana pemulangnya <br>
                                    penting/beresiko atau yang membutuhkan kontinuitas <br>
                                    pelayanan
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.butuhKontinuitas"
                                        color="primary"
                                        true-value="Ya"
                                    />
                                </td>
                                <td>
                                    <VCheckbox
                                        v-model="input.butuhKontinuitas"
                                        color="primary"
                                        true-value="Tidak"
                                    />
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <VField label="Kesimpulan :">
                                                <VControl>
                                                    <VTextarea
                                                        v-model="input.kesimpulan"
                                                        rows="3"
                                                        placeholder=""
                                                    />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <b> (*) Hapus yang tidak perlu, dan hapus (*) dalam form text Kesimpulan </b> <br>
                                            <p style="color: #777">
                                                contoh : <br>
                                                Bedasarkan kesimpulan diatas Memenuhi untuk mendapatkan pelayanan dari Manager Pelayanan Pasien (MPP) <br>
                                                atau <br>
                                                Bedasarkan kesimpulan diatas Tidak Memenuhi untuk mendapatkan pelayanan dari Manager Pelayanan Pasien (MPP)
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </VCard>
            <VCard class="mt-5">
                <div class="columns is-multiline m-0">
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-6"></div>
                            <div class="column is-6">
                                <VField label="Garut">
                                    <VDatePicker v-model="input.tanggalPengisian" mode="datetime" trim-weeks :max-date="new Date()">
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
                            <div class="column is-6"></div>
                            <div class="column is-6">
                                <div style="text-align:center;">
                                    <h1>Perawat / Bidan Pemeriksa</h1>
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
        kesimpulan: 'Bedasarkan kesimpulan diatas Memenuhi / Tidak Memenuhi * untuk mendapatkan pelayanan dari Manager Pelayanan Pasien (MPP)',
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
    input.value.skor.forEach(element => {
        if(element) {
            newSkor +=  parseInt(element)
        }
    });
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
