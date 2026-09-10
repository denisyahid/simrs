<template>
    <!-- <FloatingButtonScroll @click="scrollToBottom()" style="margin-right:5rem"/> -->
     <div class="form-layout is-stacked-2" style="
     width: 100%;
     max-width: none;">
         <div class="form-outer" style="margin-top:15px">
             <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                 <div class="form-header-inner">
                     <div class="left">
                         <h3> {{ props.FORM_NAME }} <span v-if="isStuck"> (001.2/FORM/7/RMIK/2022/Rev. 00)</span></h3>
                     </div>
                     <div class="right">
                         <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                             @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
 
                     </div>
                 </div>
             </div>
 
         </div>
     </div>
     <!-- <div class="scroll-container-rev" ref="scrollContainer"> -->
         <div class="columns is-multiline p-2">
             <div class="column is-12">
                 <VCard>
                     <div class="columns is-multiline mt-3">
                         <div class="column is-3">
                             <VField label="DPJP Utama" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                 <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                     <AutoComplete v-model="input.dpjpUtama" :suggestions="d_Dokter"
                                         @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                         :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                         placeholder="ketik untuk mencari..." />
                                 </VControl>
                             </VField>
                         </div>
                         <div class="column is-3">
                             <VField label="Dokter Rawat Bersama" class="is-rounded-select_Z  is-autocomplete-select"
                                 v-slot="{ id }">
                                 <VControl icon="fa:users" fullwidth class="prime-auto ">
                                     <AutoComplete v-model="input.dokterRawatBersama" :suggestions="d_Dokter"
                                         @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                         :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                         placeholder="ketik untuk mencari..." />
                                 </VControl>
                             </VField>
                         </div>
                         <div class="column is-2 mt-5">
                             <VControl >
                                 <VSwitchBlock v-model="isCPPTOLD" label="CPPT LAMPAU" color="danger"/>
                             </VControl>
                         </div>
                         <div class="column is-4" style=" margin-top: 2.8rem;" v-if="isloadingLAMPAU">
                             <VProgress size="tiny" color="info" />
                         </div>
                         <div class="column is-12" v-if="isCPPTOLD && input2.length">
                             <VCard class="tg-card">
                                 <h3 class="title is-5 mb-2">Riwayat CPPT</h3>
                                 <div class="columns is-multiline mt-3">
                                     <div class="column is-12 CPPT_HEIGHT" >
                                         <table class="tg" >
                                             <thead>
                                                 <tr>
                                                     <td class="tg-0lax text-center" rowspan="2">Tanggal/Jam</td>
                                                     <td class="tg-0lax text-center" colspan="4">Catatan Asuhan Medis dan Tenaga Medis
                                                         Kesehatan Lain</td>
                                                     <td class="tg-0lax text-center" rowspan="2">Intruksi PPA</td>
                                                     <td class="tg-0lax text-center" rowspan="2">Verifikasi DPJP</td>
                                                 
                                                 </tr>
                                                 <tr>
                                                     <td class="tg-0lax  text-center" colspan="4">SOAP </td>
                                                 </tr>
                                             </thead>
                                             <tbody v-for="(itemss, index) in input2" :key="index">
                                                 <tr style="background: #d6d4d4;">
                                                     <td >
                                                         <VIconButton circle icon="feather:chevron-down" raised bold v-tooltip.bubble="'Hide Expand'" v-if="itemss.show" @click="itemss.show = false"> </VIconButton>
                                                         <VIconButton circle icon="feather:chevron-right" raised bold v-tooltip.bubble="' Expand'" v-if="!itemss.show" @click="itemss.show = true"> </VIconButton>
                                                     
                                                         <!-- <VIcon icon="feather:chevron-down" v-if="itemss.show" @click="itemss.show = false" class="is-clickable" />
                                                         <VIcon icon="feather:chevron-right" v-if="!itemss.show" @click="itemss.show = true" class="is-clickable" /> -->
                                                     </td>
                                                     <td colspan="7">
                                                         <span class="text-normal-1 bold">
                                                             <b>   {{ itemss.registrasi ? itemss.registrasi.noregistrasi :'' }} 
                                                                 - {{ itemss.registrasi ?  H.formatDateIndo( itemss.registrasi.tglregistrasi) :'' }} 
                                                                 - {{  itemss.registrasi ? itemss.registrasi.namaruangan :'' }} 
                                                                 - {{  itemss.registrasi ? itemss.noemr :''   }}</b>
                                                         </span>
                                                     </td>
                                                 </tr>
                                                 <tr  v-for="(item, index2) in itemss.details" :key="index2" v-if="itemss.show">
                                                         <td style="width:15%">
                                                             <VField>
                                                                 <VControl class="prime-auto">
                                                                     <Calendar v-model="item.tgl" selectionMode="single" :manualInput="true"
                                                                         class="w-100" :showIcon="true" showTime hourFormat="24"
                                                                         :date-format="'yy-mm-dd'" :disabled="true" />
                                                                 </VControl>
                                                             </VField>
                                                         </td>
                                                         <td colspan="4">
                                                             <VField>
                                                                 <VControl>
                                                                     <VTextarea v-model="item.SOAP" rows="10" placeholder="SOAP..."  :disabled="true">
                                                                     </VTextarea>
                                                                 </VControl>
                                                             </VField>
                                                             <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-copy"
                                                             @click="copyToClipboard(item.SOAP)" > Copy
                                                             </VButton>
                                                             <VField class="is-rounded-select_Z  is-autocomplete-select mt-3" v-slot="{ id }">
                                                                 <VControl icon="fa:stethoscope" fullwidth class="prime-auto ">
                                                                     <AutoComplete v-model="item.tenagaMedis" :suggestions="d_Pegawai"
                                                                         @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                                         :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                         :field="'label'" placeholder="Tenaga Medis..." 
                                                                         :disabled="true"/>
                                                                 </VControl>
                                                             </VField>
                                                         </td>
                                                         <td>
                                                             <VField>
                                                                 <VControl>
                                                                     <VTextarea v-model="item.intruksiPPA" rows="10"
                                                                         placeholder="Intruksi PPA..." :disabled="true">
                                                                     </VTextarea>
                                                                 </VControl>
                                                             </VField>
                                                         </td>
                                                         <td style="width:15%">
                                                             <VField>
                                                                 <VControl class="prime-auto">
                                                                     <VTextarea v-model="item.keteranganVerifikasiDPJP" rows="5"
                                                                         placeholder="Keterangan..." :disabled="item.keteranganVerifikasiDPJP2">
                                                                     </VTextarea>
                                                                     <Calendar v-model="item.tglVerifikasi" selectionMode="single"
                                                                         :manualInput="true" class="w-100 mt-2" :showIcon="true" showTime
                                                                         hourFormat="24" :date-format="'yy-mm-dd'" placeholder="yy-mm-dd HH:mm"
                                                                         :disabled="true" />
                                                                     <AutoComplete v-model="item.dokterDPJP" :suggestions="d_Dokter"
                                                                         @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                                         :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                         :field="'label'" placeholder="Verifikasi DPJP..." class="mt-2"
                                                                         :disabled="true" />
                                                                 </VControl>
 
                                                             </VField>
                                                         </td>
                                                     </tr>
                                             </tbody>
                                         </table>
                                     </div>
                                 </div>
                             </VCard>
                             <div class="content mt-3 mb-0">
                                 <div class="is-divider mt-3 mb-2" :data-content="'CPPT Sekarang '+ (input.registrasi ? input.registrasi.noregistrasi :'')"></div>
                             </div>
                         </div>
                         <div class="column is-12">
                             <table class="tg">
                                 <thead>
                                     <tr>
                                         <td class="tg-0lax text-center" rowspan="2">Tanggal/Jam</td>
                                         <td class="tg-0lax text-center" colspan="4">Catatan Asuhan Medis dan Tenaga Medis
                                             Kesehatan Lain</td>
                                         <td class="tg-0lax text-center" rowspan="2">Intruksi PPA</td>
                                         <td class="tg-0lax text-center" rowspan="2">Verifikasi DPJP</td>
 
                                         <td class="tg-0lax text-center" rowspan="2">#</td>
                                     </tr>
                                     <tr>
                                         <td class="tg-0lax  text-center" colspan="4">SOAP </td>
                                         <!-- <td class="tg-0lax">O </td>
                                         <td class="tg-0lax">A</td>
                                         <td class="tg-0lax">P</td> -->
                                     </tr>
                                 </thead>
                                 <tbody v-for="(item, index) in input.details" :key="index">
                                     <tr>
                                         <td style="width:15%">
                                             <VField>
                                                 <VControl class="prime-auto">
                                                     <Calendar v-model="item.tgl" selectionMode="single" :manualInput="true"
                                                         class="w-100" :showIcon="true" showTime hourFormat="24"
                                                         :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                                                 </VControl>
                                             </VField>
                                         </td>
 
                                         <td colspan="4">
                                             <VField>
                                                 <VControl>
                                                     <VTextarea v-model="item.SOAP" rows="10" placeholder="SOAP..."  :disabled="item.SOAP2">
                                                     </VTextarea>
                                                 </VControl>
                                             </VField>
                                         </td>
                                         <td>
                                             <VField>
                                                 <VControl>
                                                     <VTextarea v-model="item.intruksiPPA" rows="10"
                                                         placeholder="Intruksi PPA..." :disabled="item.intruksiPPA2">
                                                     </VTextarea>
                                                 </VControl>
                                             </VField>
                                         </td>
                                         <!-- <td>
                                             <VField>
                                                 <VControl>
                                                     <VTextarea v-model="item.O" rows="5" placeholder="Objective...">
                                                     </VTextarea>
                                                 </VControl>
                                             </VField>
                                         </td>
                                         <td>
                                             <VField>
                                                 <VControl>
                                                     <VTextarea v-model="item.A" rows="5" placeholder="Analysis...">
                                                     </VTextarea>
                                                 </VControl>
                                             </VField>
                                         </td>
                                         <td>
                                             <VField>
                                                 <VControl>
                                                     <VTextarea v-model="item.P" rows="5" placeholder="Planning...">
                                                     </VTextarea>
                                                 </VControl>
                                             </VField>
                                         </td> -->
                                         <td style="width:15%">
                                             <VField>
                                                 <VControl class="prime-auto">
 
                                                     <VTextarea v-model="item.keteranganVerifikasiDPJP" rows="5"
                                                         placeholder="Keterangan..." :disabled="item.keteranganVerifikasiDPJP2">
                                                     </VTextarea>
 
                                                     <Calendar v-model="item.tglVerifikasi" selectionMode="single"
                                                         :manualInput="true" class="w-100 mt-2" :showIcon="true" showTime
                                                         hourFormat="24" :date-format="'yy-mm-dd'" placeholder="yy-mm-dd HH:mm"
                                                         :disabled="item.tglVerifikasi2" />
                                                     <AutoComplete v-model="item.dokterDPJP" :suggestions="d_Dokter"
                                                         @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                         :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                         :field="'label'" placeholder="Verifikasi DPJP..." class="mt-2"
                                                         :disabled="item.dokterDPJP2" />
                                                 </VControl>
 
                                             </VField>
                                         </td>
                                         <td rowspan="2" style="width:7%;vertical-align: middle;">
                                             <div class="columns is-multiline">
                                                 <div class="column is-6">
                                                     <VIconButton type="button" raised circle icon="feather:plus"
                                                         @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                                     </VIconButton>
                                                 </div>
                                                 <div class="column is-6 ml-3-min">
                                                     <VIconButton v-if="index > 0" type="button" raised circle
                                                         icon="feather:trash" @click="removeItem(index)" color="danger">
                                                     </VIconButton>
                                                 </div>
                                             </div>
                                         </td>
                                     </tr>
 
                                     <tr>
                                         <td></td>
                                         <td colspan="4">
                                             <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                 <VControl icon="fa:stethoscope" fullwidth class="prime-auto ">
                                                     <AutoComplete v-model="item.tenagaMedis" :suggestions="d_Pegawai"
                                                         @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                         :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                         :field="'label'" placeholder="Tenaga Medis..." 
                                                         :disabled="item.tenagaMedis2"/>
                                                 </VControl>
                                             </VField>
                                         </td>
                                         <td></td>
                                     </tr>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </VCard>
             </div>
         </div>
     <!-- </div> -->
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
 import Dropdown from 'primevue/dropdown';
 import Calendar from 'primevue/calendar';
 import ButtonEmr from '../page-emr-plugins/button-emr.vue'
 import { v4 as uuidv4 } from 'uuid';
 import sleep from '/@src/utils/sleep'
 import FloatingButtonScroll from "../../float-scroll.vue";
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
 const isloadingLAMPAU: any = ref(false)
 const isCPPTOLD: any = ref(false)
 const scrollContainer = ref(null);
 const item: any = reactive({
     NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
     NOREC_APD: '',
     registrasi: {},
     selectedMenu: [false]
 })
 const pegawaiId = useUserSession().getUser().pegawai.id
 const COLLECTION: any = ref('CatatanPerkembanganPasienTerintegrasi') //table mongodb
 const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
 const input: any = ref({
     details: [{
         uuid: uuidv4(),
         no: 1,
         tgl: new Date(),
         tglVerifikasi: new Date(),
     }]
 })
 const input2: any = ref({
     registrasi: {},
     details: []
 })
 const d_Dokter: any = ref([])
 const d_Pegawai: any = ref([])
 const setView = () => {
     useHead({
         title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
     })
     useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
     useViewWrapper().setFullWidth(true)
 }
 const loadRiwayat = async () => {
     await setAutoFill()
     useApi().get(
         `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
             if (response.length) {
                 for (let x = 0; x < response[0].details.length; x++) {
                     const element = response[0].details[x];
                     element.tgl = H.formatDate(new Date(element.tgl),'YYYY-MM-DD HH:mm')
                     element.tglVerifikasi = H.formatDate(new Date(element.tglVerifikasi),'YYYY-MM-DD HH:mm')
                 }
                 input.value = response[0] //set ke inputan 
                 setValueDisabled()
                 if (NOREC_EMRPASIEN.value == '') {
                     NOREC_EMRPASIEN.value = response[0].emrpasienfk
                 }
             }
         })
 }
 
 const loadRiwayatOld = async () => {
     isloadingLAMPAU.value = true
     let paramsPD = ``
     // if(isCPPTOLD.value == true){
     //      paramsPD = ``
     // }
     // await sleep(1000)
     useApi().get(
         `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((response: any) => {
             
             isloadingLAMPAU.value = false
             if (response.length) {
                 let dataOLD = []
                 for (let x = 0; x < response.length; x++) {
                     const element = response[x];
                     element.show = true
                     if(element.registrasi.norec_pd != props.registrasi.norec_pd){
                         for (let y = 0; y < element.details.length; y++) {
                             const element2 = element.details[y];
                             element2.tgl = H.formatDate(new Date(element2.tgl),'YYYY-MM-DD HH:mm')
                             element2.tglVerifikasi = H.formatDate(new Date(element2.tglVerifikasi),'YYYY-MM-DD HH:mm')
                         }
                       
                         dataOLD.push(element)
                     }
                 }
                 
                 input2.value = dataOLD
             }else{
                 H.alert('warning','Data Tidak Ada')
             }
         })
 }
 
 const simpan = () => {
     let ID = input.value.id ? input.value.id : ''
     let object: any = {}
     if (input.value.details) {
         for (let x = 0; x < input.value.details.length; x++) {
             const element = input.value.details[x];
             if (element.tgl2 != undefined) delete element.tgl2;
             if (element.SOAP2 != undefined) delete element.SOAP2;
             if (element.intruksiPPA2 != undefined) delete element.intruksiPPA2;
             if (element.keteranganVerifikasiDPJP2 != undefined) delete element.keteranganVerifikasiDPJP2;
             if (element.tglVerifikasi2 != undefined) delete element.tglVerifikasi2;
             if (element.dokterDPJP2 != undefined) delete element.dokterDPJP2;
             if (element.tenagaMedis2 != undefined) delete element.tenagaMedis2;
         }
     }
 
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
         `/emr/simpan-emr-cppt`, json).then((response: any) => {
             isLoading.value = false
             NOREC_EMRPASIEN.value = response.norec_emr
             input.value.id = response.id
             setValueDisabled()
         }).catch((e: any) => {
             isLoading.value = false
         })
 }
 
 const kembaliKeun = () => {
     window.history.back()
 }
 const fetchDokter = async (filter: any) => {
     const response = await useApi().get(
         `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
     d_Dokter.value = response
 }
 
 const fetchPegawai = async (filter: any) => {
     const response = await useApi().get(
         `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
     d_Pegawai.value = response
 }
 
 const addNewItem = () => {
  
     input.value.details.push({
         uuid: uuidv4(),
         no: input.value.details[input.value.details.length - 1].no + 1,
         tgl: new Date(),
         tglVerifikasi: new Date(),
         SOAP: `S  : \n\nO : \n\nA  :\n\nP  :`
     });
 }
 const removeItem = (index: any) => {
     input.value.details.splice(index, 1)
 }
 const setAutoFill = async () => {
     input.value.dpjpUtama = props.registrasi.dokter
     input.value.details.forEach(element => {
         element.dokterDPJP = {label: props.registrasi.dokter,value:props.registrasi.objectpegawaifk}
         element.tenagaMedis = {label : useUserSession().getUser().pegawai.namaLengkap, value :  useUserSession().getUser().pegawai.id }
     });
 
   await useApi().get(
         "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
         "&collection=VitalSign" + "&field=tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,beratBadan,SPO2"
     ).then((response) => {
       if(response == null)return
       let data = ''
       data += response.suhu ? `     Suhu : ${response.suhu} °C\n`: ''
       data += response.nadi ? `     Nadi : ${response.nadi} x/mnt\n`: ''
       data += response.pernapasan ? `     Pernafasan : ${response.pernapasan} x/mnt\n`: ''
       data += response.tekananDarah ? `     Tekanan Darah : ${response.tekananDarah} mmHg\n`: ''
       data += response.tinggiBadan ? `     Tinggi Badan : ${response.tinggiBadan} Cm\n`: ''
       data += response.beratBadan ? `     Berat Badan : ${response.beratBadan} Kg\n`: ''
       data += response.SPO2 ? `     SPO2 : ${response.SPO2} %\n`: ''
       data += response.IMT ? `     IMT : ${response.IMT}\n`: ''
 
       input.value.details[0].SOAP = data != '' ? `S  : \n\nO : \n${data}\nA  :\n\nP  :` :''
 
     })
 }
 
 const setValueDisabled = () => {
     if (input.value.details) {
         for (let x = 0; x < input.value.details.length; x++) {
             const element = input.value.details[x];
             if(element.tenagaMedis){
                 if(element.tenagaMedis.value != pegawaiId){
                     element.tgl2 = true
                     element.SOAP2 = true
                     element.intruksiPPA2 = true
                     element.tenagaMedis2 = true
                 }
             }
             if(element.dokterDPJP){
                 if(element.dokterDPJP.value != pegawaiId){
                     element.keteranganVerifikasiDPJP2 =   true//element.keteranganVerifikasiDPJP ? true:false
                     element.dokterDPJP2 =  element.dokterDPJP ? true:false
                     element.tglVerifikasi2 =  element.tglVerifikasi ? true:false
                 }
             }
         }
     }
 }
 const copyToClipboard = (e:any)=> {
 
       const textArea = document.createElement('textarea');
       textArea.value = e;
 
       // Make sure the text area is not visible
       textArea.style.position = 'fixed';
       textArea.style.top = '0';
       textArea.style.left = '0';
       textArea.style.opacity = 0;
 
       document.body.appendChild(textArea);
       textArea.select();
 
       try {
         const successful = document.execCommand('copy');
         if (successful) {
             H.alert('info', 'Text copied to clipboard')
         } else {
             H.alert('error','Failed to copy text')
         
         }
       } catch (err) {
         console.error('Unable to copy text: ', err);
       }
 
       document.body.removeChild(textArea);
 }
 
 const scrollToBottom = () => {
     if (scrollContainer.value) {
     scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
     }
 }
 watch(
   () => isCPPTOLD.value, () => {
     if(isCPPTOLD.value){
         loadRiwayatOld()
     }
   }
 )
 setView()
 loadRiwayat()
 </script>
 
 <style lang="scss">
 .CPPT_HEIGHT{
     overflow: auto; height: 600px;
 }
 .tg {
     border-collapse: collapse;
     border-spacing: 0;
     width: 100%;
     background-color:var(--white);
 }
 .tg-card{
     background-color:#feffed;
 }
 .is-dark {
     .tg {
         background-color: var(--dark-sidebar-light-6)
     }
     .tg-card{
         background-color:var(--dark-sidebar-light-6)
     }
 }
 
 .tg td {
     border-color: var(--fade-grey-dark-2);
     border-style: solid;
     border-width: 1px;
     font-family: Arial, sans-serif;
     font-size: 14px;
     overflow: hidden;
     padding: 10px 5px;
     word-break: normal;
 }
 
 .tg th {
     border-color: var(--fade-grey-dark-3);
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
     vertical-align: top
 }
 .scroll-container-rev{
     height:1000px;
     overflow:auto;
 }
 </style>