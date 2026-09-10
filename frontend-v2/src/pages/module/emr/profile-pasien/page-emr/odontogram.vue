<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="columns is-12">
    <VCard>
      <!-- <div class="column is-12">
          <div class="columns is-multiline p-3">
            <div class="column is-6">
              <div class="column is-12 " v-for="(data, index) in detailOdontoA">
                <VField>
                    <h1 style="font-weight: bold;margin-bottom:0rem"> {{ data.label }} : </h1>
                    <VControl>
                      <VTextarea rows="12" :placeholder="data.placeholder" v-if="data.type == 'text'"
                        v-model="input[data.model]"></VTextarea>
                      <VInput :placeholder="data.placeholder" v-if="data.type == 'input'" class="input"
                        v-model="input[data.model]"></VInput>
                    </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-6">
              <div class="column is-12 " v-for="(data, index) in detailOdontoB">
                <VField>
                    <h1 style="font-weight: bold;margin-bottom:0rem"> {{ data.label }} : </h1>
                    <VControl>
                      <VTextarea rows="12" :placeholder="data.placeholder" v-if="data.type == 'text'"
                        v-model="input[data.model]"></VTextarea>
                      <VInput :placeholder="data.placeholder" v-if="data.type == 'input'" class="input"
                        v-model="input[data.model]"></VInput>
                    </VControl>
                </VField>
              </div>
            </div>
          </div>
      </div> -->
      
      <div class="column is-12">
        <div class="column is-12"> 
            <!-- <div :style="{ backgroundImage: 'url(' + MARKINGSITE + ')' }">-->
            <div >
                <canvas id="markingsite" ref="markingsite" width="1200" height="500" model="input['objodontogram']" @click="handleCanvasClick">
                Your browser does not support the HTML5 canvas tag.</canvas>                
            </div>            
        </div>      
      </div>
      <div class="column is-12">
        <VButton type="button"  outlined color="danger" raised icon="feather:trash"
            @click="clearCanvas('markingsite')"> Clear
        </VButton>&nbsp;
        <VButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="primary"  outlined raised v-tooltip.top="'S'" @click="toggle($event, items)"> ODONTOGRAM
        </VButton>

        <OverlayPanel ref="overlaypanel" style="width:60%">
          <div>
            <div class="columns is-multiline p-1">
                
                        <div class="column is-1">
                            <button  @click="TambalanAmalgam()" title="Tambalan Amalgam (amf)">                                 
                                <img class="img-btn" src="/images/odontogram/TambalanAmalgam.png">   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button  ng-class="{'activeBtn': activeStatus == 51}" @click="TambalanGIF()" title="Glass Ionomer Filling (gif) / Tambalan GIC Sewarna Gigi">
                                <img class="img-btn" src="/images/odontogram/TambalanComposite.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button  ng-class="{'activeBtn': activeStatus == 52}" @click="FissureSealant()" title="Fissure Sealant (fis)">
                                <img class="img-btn" src="/images/odontogram/FissureSealant.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 76}" @click="TambalanLogamEmas()" title="Tambalan Logam Emas">
                                <img class="img-btn" src="/images/odontogram/LogamEmas.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 58}" @click="Normal()" title="Normal/Baik (sou)">
                                <img class="img-btn" src="/images/odontogram/Normal.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 75}" @click="MPM()" title="Missing Post Mortem (mpm) / Gigi tercabut sampai akar setelah kematian">
                                <img class="img-btn" src="/images/odontogram/MPM.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 53}" @click="GigiNonVital()" title="Gigi Non-Vital (nvt)">
                                    <img class="img-btn" src="/images/odontogram/GigiNonVital.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 54}" @click="RCT()" title="Perawatan Saluran Akar (rct)">
                                    <img class="img-btn" src="/images/odontogram/RCT.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 67}" @click="GigiNonVitalAtas()" title="Gigi Non-Vital (nvt)">
                                    <img class="img-btn" src="/images/odontogram/GigiNonVitalAtas.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 68}" @click="RCTAtas()" title="Perawatan Saluran Akar (rct)">
                                    <img class="img-btn" src="/images/odontogram/RCTAtas.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 64}" @click="SisaAkar()" title="Retained Root (rrt) / Sisa Akar">
                                    <img class="img-btn" src="/images/odontogram/SisaAkarr.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 65}" @click="MIS()" title="Missing (mis) / Gigi Hilang">
                                    <img class="img-btn" src="/images/odontogram/mis.png" >   
                            </button>
                        </div>


                        
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 60}" @click="Caries()" title="Caries = Tambalan Sementara (car)">
                                    <img class="img-btn" src="/images/odontogram/Caries.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 61}" @click="Fracture()" title="Fracture (cfr)">
                                    <img class="img-btn" src="/images/odontogram/Fracture.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 62}" @click="FMC()" title="Full Metal Crown pada gigi vital (fmc) / Mahkota Logam">
                                    <img class="img-btn" src="/images/odontogram/FMC.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 63}" @click="POC()" title="Porcelain Metal Crown pada gigi vital (poc) / Mahkota Porcelain">
                                    <img class="img-btn" src="/images/odontogram/POC.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 55}" @click="NON()" title="Gigi tidak ada, tidak diketahui ada atau tidak ada (non)">
                                    <img class="img-btn" src="/images/odontogram/NON.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 56}" @click="UNE()" title="Un-Erupted (une)">
                                    <img class="img-btn" src="/images/odontogram/UNE.png" >   
                            </button>
                        </div>






                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 57}" @click="PRE()" title="Partial Erupted (pre)">
                                    <img class="img-btn" src="/images/odontogram/PRE.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 59}" @click="Anomali()" title="Anomali (ano) , Pegshaped, micro, fusi, etc">
                                    <img class="img-btn" src="/images/odontogram/ANO.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 66}" @click="IPX()" title="Implant (ipx)">
                                    <img class="img-btn" src="/images/odontogram/IPX.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 69}" @click="PRD()" title="Partial Denture">
                                    <img class="img-btn" src="/images/odontogram/PRD.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 70}" @click="FLD()" title="Full Denture">
                                    <img class="img-btn" src="/images/odontogram/FLD.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 74}" @click="IMX()" title="Impacted Non-Visible (imx) / Gigi impaksi tidak terlihat secara klinis">
                                    <img class="img-btn" src="/images/odontogram/IMX.png" >   
                            </button>
                        </div>
                        <!-- JEMBATAN -->
                        
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 71}" @click="Jembatan1()" title="Bridge (Jembatan)">
                                    <img class="img-btn" src="/images/odontogram/Jembatan1.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 72}" @click="Jembatan2()" title="Bridge (Jembatan)">
                                    <img class="img-btn" src="/images/odontogram/Jembatan2.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 73}" @click="Jembatan3()" title="Bridge (Jembatan)">
                                    <img class="img-btn" src="/images/odontogram/Jembatan3.png" >   
                            </button>
                        </div>

                        <div class="column is-1">
                                <button     ng-class="{'activeBtn': activeStatus == 77}" @click="TambalanComposite()" title="Tambalan Composite (cof)">
                                        <img class="img-btn" src="/images/odontogram/Arsir.png" >   
                                </button>
                        </div>

                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 78}" @click="PanahKiri()" title="Migrasi Ke Kiri">
                                    <img class="img-btn" src="/images/odontogram/PanahKiri.png" >   
                            </button>
                        </div>

                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 79}" @click="PanahKanan()" title="Migrasi Ke Kanan">
                                    <img class="img-btn" src="/images/odontogram/PanahKanan.png" >   
                            </button>
                        </div>


                        
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 80}" @click="RotasiKananAtas()" title="Rotasi Ke Kanan">
                                    <img class="img-btn" src="/images/odontogram/rotasikananatas.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 81}" @click="RotasiKananBawah()" title="Rotasi Ke Kanan">
                                    <img class="img-btn" src="/images/odontogram/rotasikananbawah.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 82}" @click="RotasiKiriAtas()" title="Rotasi Ke Kiri">
                                    <img class="img-btn" src="/images/odontogram/rotasikiriatas.png" >   
                            </button>
                        </div>
                        <div class="column is-1">
                            <button     ng-class="{'activeBtn': activeStatus == 83}" @click="RotasiKiriBawah()" title="Rotasi Ke Kiri">
                                    <img class="img-btn" src="/images/odontogram/rotasikiribawah.png" >   
                            </button>
                        </div>
            </div>
        </div>
        </OverlayPanel>
        </div>
      
      
      <div class="column is-12">
          <div class="columns is-multiline p-3">
            <div class="column is-6">
              <div class="column is-12 " v-for="(data, index) in detailOdontoC">
                <VField>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <h1 style="font-weight: bold;margin-bottom:0rem"> {{ data.label }} : </h1>
                    </div>
                    <div class="column is-9">
                      <VControl>
                        <VTextarea rows="12" :placeholder="data.placeholder" v-if="data.type == 'text'"
                          v-model="input[data.model]"></VTextarea>
                        <VInput :placeholder="data.placeholder" v-if="data.type == 'input'" class="input"
                          v-model="input[data.model]"></VInput>
                      </VControl>
                    </div>
                  </div>
                </VField>
              </div>
            </div>
            <div class="column is-6">
              <div class="column is-12 " v-for="(data, index) in detailOdontoD">
                <VField>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <h1 style="font-weight: bold;margin-bottom:0rem"> {{ data.label }} : </h1>
                    </div>
                    <div class="column is-9">
                      <VControl>
                        <VTextarea rows="12" :placeholder="data.placeholder" v-if="data.type == 'text'"
                          v-model="input[data.model]"></VTextarea>
                        <VInput :placeholder="data.placeholder" v-if="data.type == 'input'" class="input"
                          v-model="input[data.model]"></VInput>
                      </VControl>
                    </div>
                  </div>
                </VField>
              </div>
            </div>
          </div>
      </div>
      
      
      
      
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/odontogram'
import $ from "jquery";
import RadioButton from 'primevue/radiobutton';
import sleep from '/@src/utils/sleep'
import { Vue, Component, Ref } from 'vue-property-decorator';

import OverlayPanel from 'primevue/overlaypanel';



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let detailKeperawatan = ref(EMR.detailKeperawatan())
let detailOdontoA = ref(EMR.detailOdontoA())
let detailOdontoB = ref(EMR.detailOdontoB())
let detailOdontoC = ref(EMR.detailOdontoC())
let detailOdontoD = ref(EMR.detailOdontoD())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let skoringJatuh: any = ref(EMR.skoringJatuh())
let resikoNutrisional: any = ref(EMR.listNutrisional())
let statusPasien: any = ref(EMR.statusPasien())
let pemeriksaanFisik: any = ref(EMR.pemeriksaanFisik())
let keperawatan: any = ref(EMR.keperawatan())

const selected: any = ref({})
const overlaypanel: any = ref();

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
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Perawat: any = ref([])
const d_Dokter: any = ref([])
const d_DiagnosaKeperawatan: any = ref([])
const isAktive = ref()
const jenisKelamin: any = ref('')
const MARKINGSITE: any = ref('')
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('odontogram') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  diagnosaKeper: [{ no: 1 }],
})


var elements: any[] = [];
//================================================================================
            var data2 = []
            var isianColor = "white"
            var kdColorKaries = "#B8B7B7"
            var kdColorTambalanLogam = "#000000"
            var kdColorTambalanNonLogam = "#82D9D9"
            var kdColorMahkotaLogam = "#014C0A"
            var kdColorMerah = "Red"
            var kdColorMahkotaNonLogam = "#40F5F9"
            var typeIsian = ""

            //-----------------------------------------
            var kdColorTambalanAmalgam = "#000000"
            var kdColorTambalanComposite = "#33a909"
            var kdColorFissureSealant = "#d36596"
            var kdColorNormal = "#ffffff"
            var kdColorLogamEmas = "#f30505"
            //-----------------------------------------

            var dataLoad = []
            var activeStatus;
            
            //-----------------------------------------
            const TambalanAmalgam = () => {
                isianColor = kdColorTambalanAmalgam
                typeIsian = "selai"
                activeStatus =50

                console.log('TambalanAmalgam');
            }
            const TambalanGIF = () => {
                isianColor = kdColorTambalanComposite
                typeIsian = "selai"
                activeStatus =51
                console.log('TambalanGIF');
            }
            const FissureSealant = () => {
                isianColor = kdColorFissureSealant
                typeIsian = "selai"
                activeStatus =52
            }
            const GigiNonVital = () => {
                typeIsian = "nonvital"
                activeStatus =53
            }
            const RCT = () => {
                typeIsian = "rct"
                activeStatus =54
            }
            const NON = () => {
                typeIsian = "non"
                activeStatus =55
            }
            const UNE = () => {
                typeIsian = "une"
                activeStatus =56
            }
            const PRE = () => {
                typeIsian = "pre"
                activeStatus =57
            }
            const Normal = () => {
                isianColor = kdColorNormal
                typeIsian = "selai"
                activeStatus =58
            }
            const Anomali = () => {
                typeIsian = "ano"
                activeStatus =59
            }
            const Caries = () => {
                isianColor = kdColorNormal
                typeIsian = "caries"
                activeStatus = 60
            }
            const Fracture = () => {
                typeIsian = "fracture"
                activeStatus = 61
            }
            const FMC = () => {
                isianColor = kdColorNormal
                typeIsian = "fmc"
                activeStatus = 62
            }
            const POC = () => {
                typeIsian = "poc"
                activeStatus = 63
            }
            const SisaAkar = () => {
                typeIsian = "SisaAkar"
                activeStatus = 64
            }
            const MIS = () => {
                typeIsian = "mis"
                activeStatus = 65
            }
            const IPX = () => {
                typeIsian = "ipx"
                activeStatus =66
            }
            const GigiNonVitalAtas = () => {
                typeIsian = "nonvitalatas"
                activeStatus =67
            }
            const RCTAtas = () => {
                typeIsian = "rctatas"
                activeStatus =68
            }
            const PRD = () => {
                typeIsian = "prd"
                activeStatus =69
            }
            const FLD = () => {
                typeIsian = "fld"
                activeStatus =70
            }
            //jembatan
            
            const Jembatan1 = () => {
                typeIsian = "jembatan1"
                activeStatus =71
            }
            const Jembatan2 = () => {
                typeIsian = "jembatan2"
                activeStatus =72
            }
            const Jembatan3 = () => {
                typeIsian = "jembatan3"
                activeStatus =73
            }

            
            const IMX = () => {
                typeIsian = "imx"
                activeStatus =74
            }
            const MPM = () => {
                typeIsian = "mpm"
                activeStatus =75
            }

            
            const TambalanLogamEmas = () => {
                isianColor = kdColorLogamEmas
                typeIsian = "selai"
                activeStatus =76
            }
            const TambalanComposite = () => {
                typeIsian = "composite"
                activeStatus =77
            }

            
            const PanahKiri = () => {
                typeIsian = "panahkiri"
                activeStatus =78
            }

            
            const PanahKanan = () => {
                typeIsian = "panahkanan"
                activeStatus =79
            }

            
            const RotasiKananAtas = () => {
                typeIsian = "rotasikananatas"
                activeStatus =80
            }
            const RotasiKananBawah = () => {
                typeIsian = "rotasikananbawah"
                activeStatus =81
            }
            const RotasiKiriAtas = () => {
                typeIsian = "rotasikiriatas"
                activeStatus =82
            }
            const RotasiKiriBawah = () => {
                typeIsian = "rotasikiribawah"
                activeStatus =83
            }
            //-----------------------------------------



            const  isiColor =(element: any) => {        
              
              let sigCanvas: any = document.getElementById("markingsite");
              let context = sigCanvas.getContext("2d"); 
                if (typeIsian == "selai") {
                    console.log(element.seg + " - " + element.type);

                    if(element.typejenis == '0') {
                      if (element.seg == 1) {
                          context.beginPath();
                          context.moveTo(element.left - 13, element.top + 1);
                          context.lineTo(element.left + 43, element.top + 1);
                          context.lineTo(element.left + 29, element.top + 15);
                          context.lineTo(element.left + 1, element.top + 15);
                          context.lineTo(element.left - 13, element.top + 1);
                          context.closePath();
                          context.lineWidth = "3";
                          context.strokeStyle = "black";
                          context.stroke();
                          context.fillStyle = isianColor;
                          context.fill();
                      } else if (element.seg == 2) {
                          context.beginPath();
                          context.moveTo(element.left + 1, element.top + 1);
                          context.lineTo(element.left + 15, element.top - 13);
                          context.lineTo(element.left + 15, element.top + 43);
                          context.lineTo(element.left + 1, element.top + 29);
                          context.lineTo(element.left + 1, element.top + 1);
                          context.closePath();
                          context.lineWidth = "3";
                          context.strokeStyle = "black";
                          context.stroke();
                          context.fillStyle = isianColor;
                          context.fill();
                      } else if (element.seg == 3) {
                          context.beginPath();
                          context.moveTo(element.left + 1, element.top + 1);
                          context.lineTo(element.left + 29, element.top + 1);
                          context.lineTo(element.left + 43, element.top + 15);
                          context.lineTo(element.left -13, element.top + 15);
                          context.lineTo(element.left + 1 , element.top + 1);
                          context.closePath();
                          context.lineWidth = "3";
                          context.strokeStyle = "black";
                          context.stroke();
                          context.fillStyle = isianColor;
                          context.fill();
                      } else if (element.seg == 4) {
                          context.beginPath();
                          context.moveTo(element.left + 1, element.top - 13);
                          context.lineTo(element.left + 15, element.top + 1);
                          context.lineTo(element.left + 15, element.top + 29);
                          context.lineTo(element.left + 1, element.top + 43);
                          context.lineTo(element.left + 1, element.top - 13);
                          context.closePath();
                          context.lineWidth = "3";
                          context.strokeStyle = "black";
                          context.stroke();
                          context.fillStyle = isianColor;
                          context.fill();
                      } else if (element.seg == 5) {
                          context.beginPath();
                          context.moveTo(element.left+1, element.top+1);
                          context.lineTo(element.left + 29, element.top+1);
                          context.lineTo(element.left + 29, element.top + 29);
                          context.lineTo(element.left+1, element.top + 29);
                          context.lineTo(element.left+1, element.top + 1);
                          context.closePath();
                          context.lineWidth = "3";
                          context.strokeStyle = "black";
                          context.stroke();
                          context.fillStyle = isianColor;
                          context.fill();
                      }
                    } else if(element.typejenis == '1') {
                      if (element.seg == 1) {
                          context.beginPath();
                          context.moveTo(element.left - 13, element.top + 1);
                          context.lineTo(element.left + 43, element.top + 1);
                          context.lineTo(element.left + 29, element.top + 30);
                          context.lineTo(element.left + 1, element.top + 30);
                          context.lineTo(element.left - 13, element.top + 1);
                          context.closePath();
                          context.lineWidth = "3";
                          context.strokeStyle = "black";
                          context.stroke();
                          context.fillStyle = isianColor;
                          context.fill();
                      } else if (element.seg == 2) {
                          context.beginPath();
                          context.moveTo(element.left + 1, element.top + 15);
                          context.lineTo(element.left + 15, element.top - 13);
                          context.lineTo(element.left + 15, element.top + 43);
                          //context.lineTo(element.left + 1, element.top + 29);
                          context.lineTo(element.left + 1, element.top + 15);
                          context.closePath();
                          context.lineWidth = "3";
                          context.strokeStyle = "black";
                          context.stroke();
                          context.fillStyle = isianColor;
                          context.fill();
                      } else if (element.seg == 3) {
                          context.beginPath();
                          context.moveTo(element.left + 1, element.top - 13);
                          context.lineTo(element.left + 29, element.top - 13);
                          context.lineTo(element.left + 43, element.top + 15);
                          context.lineTo(element.left -13, element.top + 15);
                          context.lineTo(element.left + 1 , element.top - 13);
                          context.closePath();
                          context.lineWidth = "3";
                          context.strokeStyle = "black";
                          context.stroke();
                          context.fillStyle = isianColor;
                          context.fill();
                      } else if (element.seg == 4) {
                          context.beginPath();
                          context.moveTo(element.left + 1, element.top - 13);
                          context.lineTo(element.left + 15, element.top + 15);
                          //context.lineTo(element.left + 15, element.top + 29);
                          context.lineTo(element.left + 1, element.top + 43);
                          context.lineTo(element.left + 1, element.top - 13);
                          context.closePath();
                          context.lineWidth = "3";
                          context.strokeStyle = "black";
                          context.stroke();
                          context.fillStyle = isianColor;
                          context.fill();
                      }
                    }
                    element.type = typeIsian
                    element.color = isianColor
                } else if (element.seg == 5 && typeIsian != "selai" && typeIsian != "composite" ) {
                    
                    if (typeIsian == "nonvital") {
                        context.beginPath();
                        context.moveTo(element.left + 15 , element.top + 80);
                        context.lineTo(element.left + 30, element.top + 46);
                        context.lineTo(element.left , element.top + 46);

                        context.closePath();
                        context.lineWidth = "2";
                        context.strokeStyle = "black";
                        context.stroke(); 

                    } else if (typeIsian == "rct") {
                        context.beginPath();
                        context.moveTo(element.left + 15 , element.top + 80);
                        context.lineTo(element.left + 30, element.top + 46);
                        context.lineTo(element.left , element.top + 46);

                        context.closePath();
                        context.lineWidth = "2";
                        context.strokeStyle = "black";
                        context.stroke(); 
                        
                        context.fillStyle = "black";
                        context.fill();
                    } else if (typeIsian == "non") {
                        context.font = "bold 24px Tahoma";
                        context.fillStyle = "black";
                        context.fillText("NON", element.left - 14, element.top - 22);
                    } else if (typeIsian == "une") {
                        context.font = "bold 24px Tahoma";
                        context.fillStyle = "black";
                        context.fillText("UNE", element.left - 12, element.top - 22);
                    } else if (typeIsian == "pre") {
                        context.font = "bold 24px Tahoma";
                        context.fillStyle = "black";
                        context.fillText("PRE", element.left - 10, element.top - 22);
                    } else if (typeIsian == "ano") {
                        context.font = "bold 24px Tahoma";
                        context.fillStyle = "black";
                        context.fillText("ANO", element.left - 11, element.top - 22);
                    }  else if (typeIsian == "ipx") {
                        context.font = "bold 24px Tahoma";
                        context.fillStyle = "black";
                        context.fillText("IPX", element.left - 8, element.top + 84);
                    }  else if (typeIsian == "prd") {
                        context.font = "bold 24px Tahoma";
                        context.fillStyle = "black";
                        context.fillText("PRD", element.left - 8, element.top + 84);
                    } else if (typeIsian == "fld") {
                        context.font = "bold 24px Tahoma";
                        context.fillStyle = "black";
                        context.fillText("FLD", element.left - 8, element.top + 84);
                    } else if (typeIsian == "imx") {
                        context.font = "bold 24px Tahoma";
                        context.fillStyle = "black";
                        context.fillText("IMX", element.left - 8, element.top + 84);
                    } else if (typeIsian == "caries") {
                        context.beginPath();
                        context.moveTo(element.left+2, element.top+2);
                        context.lineTo(element.left + 28, element.top+2);
                        context.lineTo(element.left + 28, element.top + 28);
                        context.lineTo(element.left+2, element.top + 28);
                        context.lineTo(element.left+2, element.top + 2);
                        context.closePath();
                        context.lineWidth = "5";
                        context.strokeStyle = "black";
                        context.stroke();
                    } else if (typeIsian == "fracture") {
                        context.font = "bold 60px Tahoma";
                        context.fillStyle = "black";
                        context.fillText("#", element.left - 9, element.top + 37);
                    } else if (typeIsian == "fmc") {
                        context.beginPath();
                        context.moveTo(element.left-15, element.top-15);
                        context.lineTo(element.left + 45, element.top-15);
                        context.lineTo(element.left + 45, element.top + 45);
                        context.lineTo(element.left-15, element.top + 45);
                        context.lineTo(element.left-15, element.top -15);
                        context.closePath();
                        context.lineWidth = "6";
                        context.strokeStyle = "black";
                        context.stroke();
                        //context.fillStyle = isianColor;
                        //context.fill();
                    } else if (typeIsian == "poc") {
                        context.beginPath();
                        context.moveTo(element.left-17, element.top-17);
                        context.lineTo(element.left + 47, element.top-17);
                        context.lineTo(element.left + 47, element.top + 47);
                        context.lineTo(element.left-17, element.top + 47);
                        context.lineTo(element.left-17, element.top -17);
                        context.closePath();
                        context.lineWidth = "10";
                        context.strokeStyle = "black";
                        context.stroke();

                        context.beginPath();
                        context.moveTo(element.left - 9, element.top - 15); //bawah kiri
                        context.lineTo(element.left - 9, element.top + 45); // atas kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.moveTo(element.left - 3, element.top - 15); //bawah kiri
                        context.lineTo(element.left - 3, element.top + 45); // atas kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 3, element.top - 15); // bawah kanan
                        context.lineTo(element.left + 3, element.top + 45); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 9, element.top - 15); // bawah kanan
                        context.lineTo(element.left + 9, element.top + 45); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 15, element.top - 15); // bawah kanan
                        context.lineTo(element.left + 15, element.top + 45); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 21, element.top - 15); // bawah kanan
                        context.lineTo(element.left + 21, element.top + 45); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 27, element.top - 15); // bawah kanan
                        context.lineTo(element.left + 27, element.top + 45); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 33, element.top - 15); // bawah kanan
                        context.lineTo(element.left + 33, element.top + 45); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 39, element.top - 15); // bawah kanan
                        context.lineTo(element.left + 39, element.top + 45); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 
                        
                    } else if (typeIsian == "SisaAkar") {
                        context.beginPath();
                        context.moveTo(element.left - 9, element.top - 35); //bawah kiri
                        context.lineTo(element.left + 15, element.top + 55); // atas kiri
                        context.closePath();
                        context.lineWidth = "5";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.moveTo(element.left + 15, element.top + 55); //bawah kiri
                        context.lineTo(element.left + 41, element.top - 45); // atas kiri
                        context.closePath();
                        context.lineWidth = "5";
                        context.strokeStyle = "black";
                        context.stroke();                        
                    } else if (typeIsian == "mis") {
                        context.beginPath();
                        context.moveTo(element.left - 6, element.top - 26); //bawah kiri
                        context.lineTo(element.left + 38, element.top + 56); // atas kiri
                        context.closePath();
                        context.lineWidth = "5";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.moveTo(element.left + 38, element.top - 26); //bawah kiri
                        context.lineTo(element.left - 6, element.top + 56); // atas kiri
                        context.closePath();
                        context.lineWidth = "5";
                        context.strokeStyle = "black";
                        context.stroke();                      
                    } else if (typeIsian == "nonvitalatas") {
                        context.beginPath();
                        context.moveTo(element.left + 15 , element.top - 50);
                        context.lineTo(element.left + 30, element.top - 16);
                        context.lineTo(element.left , element.top + - 16);

                        context.closePath();
                        context.lineWidth = "2";
                        context.strokeStyle = "black";
                        context.stroke(); 

                    } else if (typeIsian == "rctatas") {
                        context.beginPath();
                        context.moveTo(element.left + 15 , element.top - 50);
                        context.lineTo(element.left + 30, element.top - 16);
                        context.lineTo(element.left , element.top + - 16);

                        context.closePath();
                        context.lineWidth = "2";
                        context.strokeStyle = "black";
                        context.stroke(); 
                        
                        context.fillStyle = "black";
                        context.fill();
                    }  else if (typeIsian == "jembatan1") {
                        context.beginPath();
                        context.moveTo(element.left + 15, element.top-15);
                        context.lineTo(element.left + 15, element.top-35);
                        context.closePath();
                        context.lineWidth = "5";
                        context.strokeStyle = "black";
                        context.stroke(); 



                        context.beginPath();
                        context.moveTo(element.left + 15, element.top-35);
                        context.lineTo(element.left + 47, element.top-35);
                        context.closePath();
                        context.lineWidth = "6";
                        context.strokeStyle = "black";
                        context.stroke();
                        //context.fillStyle = isianColor;
                        //context.fill();
                    }  else if (typeIsian == "jembatan2") {
                        context.beginPath();
                        context.moveTo(element.left - 17, element.top-35);
                        context.lineTo(element.left + 47, element.top-35);
                        context.closePath();
                        context.lineWidth = "6";
                        context.strokeStyle = "black";
                        context.stroke();
                        //context.fillStyle = isianColor;
                        //context.fill();
                    }  else if (typeIsian == "jembatan3") {
                        context.beginPath();
                        context.moveTo(element.left + 15, element.top-15);
                        context.lineTo(element.left + 15, element.top-35);
                        context.closePath();
                        context.lineWidth = "5";
                        context.strokeStyle = "black";
                        context.stroke(); 



                        context.beginPath();
                        context.moveTo(element.left + 15, element.top-35);
                        context.lineTo(element.left - 32, element.top-35);
                        context.closePath();
                        context.lineWidth = "6";
                        context.strokeStyle = "black";
                        context.stroke();
                    }  else if (typeIsian == "mpm") {
                        context.beginPath();
                        context.arc((element.left + 15), (element.top + 15), 30, 0, 2 * Math.PI);
                        context.lineWidth = "5";
                        context.strokeStyle = "black";
                        context.stroke();
                    }  else if (typeIsian == "panahkiri") {
                        context.beginPath();
                        context.moveTo(element.left - 5, element.top-30);
                        context.lineTo(element.left + 35, element.top-30);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        context.beginPath();
                        context.moveTo(element.left - 5, element.top-30);
                        context.lineTo(element.left + 7, element.top- 37);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        
                        context.beginPath();
                        context.moveTo(element.left - 5, element.top-30);
                        context.lineTo(element.left + 7, element.top-23);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();
                        //context.fillStyle = isianColor;
                        //context.fill();
                    }   else if (typeIsian == "panahkanan") {
                        context.beginPath();
                        context.moveTo(element.left - 5, element.top-30);
                        context.lineTo(element.left + 35, element.top-30);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        context.beginPath();
                        context.moveTo(element.left + 35, element.top-30);
                        context.lineTo(element.left + 23, element.top- 37);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        
                        context.beginPath();
                        context.moveTo(element.left +35, element.top-30);
                        context.lineTo(element.left + 23, element.top-23);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();
                        //context.fillStyle = isianColor;
                        //context.fill();
                    }   else if (typeIsian == "rotasikananatas") {

                        context.beginPath();
                        context.moveTo(element.left, element.top-18);
                        context.lineTo(element.left, element.top-22);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        context.beginPath();
                        context.moveTo(element.left ,element.top - 22);
                        context.bezierCurveTo(element.left ,element.top - 37,element.left + 30,element.top -37,element.left + 32,element.top -37);
                        context.lineWidth=4;
                        context.stroke();

                        
                        context.beginPath();
                        context.moveTo(element.left + 35, element.top-37);
                        context.lineTo(element.left + 23, element.top- 42);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        
                        context.beginPath();
                        context.moveTo(element.left +35, element.top-37);
                        context.lineTo(element.left + 23, element.top-30);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();
                        
                    }   else if (typeIsian == "rotasikananbawah") {

                        context.beginPath();
                        context.moveTo(element.left, element.top+ 58);
                        context.lineTo(element.left, element.top+ 62);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        context.beginPath();
                        context.moveTo(element.left ,element.top + 62);
                        context.bezierCurveTo(element.left ,element.top +77,element.left + 30,element.top +77,element.left + 32,element.top +77);
                        context.lineWidth=4;
                        context.stroke();

                        
                        context.beginPath();
                        context.moveTo(element.left + 35, element.top+77);
                        context.lineTo(element.left + 23, element.top+82);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        
                        context.beginPath();
                        context.moveTo(element.left +35, element.top+77);
                        context.lineTo(element.left + 23, element.top+70);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();
                        
                    }   else if (typeIsian == "rotasikiriatas") {

                        context.beginPath();
                        context.moveTo(element.left + 30, element.top-18);
                        context.lineTo(element.left + 30, element.top-22);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        context.beginPath();
                        context.moveTo(element.left + 30,element.top - 22);
                        context.bezierCurveTo(element.left + 30,element.top - 37,element.left ,element.top -37,element.left -2,element.top -37);
                        context.lineWidth=4;
                        context.stroke();

                        
                        context.beginPath();
                        context.moveTo(element.left - 5, element.top-37);
                        context.lineTo(element.left + 7, element.top- 42);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        
                        context.beginPath();
                        context.moveTo(element.left - 5, element.top-37);
                        context.lineTo(element.left + 7, element.top-30);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();
                        
                    }   else if (typeIsian == "rotasikiribawah") {

                        context.beginPath();
                        context.moveTo(element.left + 30, element.top+ 58);
                        context.lineTo(element.left + 30, element.top+ 62);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        context.beginPath();
                        context.moveTo(element.left + 30,element.top + 62);
                        context.bezierCurveTo(element.left + 30,element.top +77,element.left,element.top +77,element.left - 2,element.top +77);
                        context.lineWidth=4;
                        context.stroke();

                        
                        context.beginPath();
                        context.moveTo(element.left - 5, element.top+77);
                        context.lineTo(element.left + 7, element.top+82);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();

                        
                        context.beginPath();
                        context.moveTo(element.left - 5, element.top+77);
                        context.lineTo(element.left + 7, element.top+70);
                        context.closePath();
                        context.lineWidth = "4";
                        context.strokeStyle = "black";
                        context.stroke();
                        
                    } 
                    //-----------------------------------------------------------------------------------------


                    element.type = typeIsian
                    element.color = "black"
                } else if( typeIsian == "composite") {
                    console.log(element.seg);

                    
                    if(element.typejenis == '0') {
                      if (element.seg == 1) {

                          context.beginPath();
                          context.moveTo(element.left - 9, element.top); //bawah kiri
                          context.lineTo(element.left - 9, element.top + 5); // atas kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.moveTo(element.left - 3, element.top); //bawah kiri
                          context.lineTo(element.left - 3, element.top + 10); // atas kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 3, element.top); // bawah kanan
                          context.lineTo(element.left + 3, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 9, element.top); // bawah kanan
                          context.lineTo(element.left + 9, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 15, element.top); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 21, element.top); // bawah kanan
                          context.lineTo(element.left + 21, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 27, element.top); // bawah kanan
                          context.lineTo(element.left + 27, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 33, element.top); // bawah kanan
                          context.lineTo(element.left + 33, element.top + 10); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 39, element.top); // bawah kanan
                          context.lineTo(element.left + 39, element.top + 5); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
                      } else if (element.seg == 2) {

                          context.beginPath();
                          context.moveTo(element.left + 10, element.top - 10); //bawah kiri
                          context.lineTo(element.left + 15, element.top - 10 ); // atas kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.moveTo(element.left + 4, element.top - 3); //bawah kiri
                          context.lineTo(element.left + 15, element.top - 3); // atas kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left, element.top + 3); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 3); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left, element.top + 9); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 9); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left , element.top + 15); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left , element.top + 21); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 21); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left , element.top + 27); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 27); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 4, element.top + 33); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 33); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 10, element.top + 39); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 39); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
                      } else if (element.seg == 3) {

                          context.beginPath();
                          context.moveTo(element.left - 9, element.top + 10); //bawah kiri
                          context.lineTo(element.left - 9, element.top + 15); // atas kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.moveTo(element.left - 3, element.top + 5); //bawah kiri
                          context.lineTo(element.left - 3, element.top + 15); // atas kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 3, element.top); // bawah kanan
                          context.lineTo(element.left + 3, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 9, element.top); // bawah kanan
                          context.lineTo(element.left + 9, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 15, element.top); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 21, element.top); // bawah kanan
                          context.lineTo(element.left + 21, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 27, element.top); // bawah kanan
                          context.lineTo(element.left + 27, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 33, element.top + 5); // bawah kanan
                          context.lineTo(element.left + 33, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 39, element.top + 10); // bawah kanan
                          context.lineTo(element.left + 39, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
                      } else if (element.seg == 4) {

                          context.beginPath();
                          context.moveTo(element.left, element.top - 10); //bawah kiri
                          context.lineTo(element.left + 5, element.top - 10 ); // atas kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.moveTo(element.left , element.top - 3); //bawah kiri
                          context.lineTo(element.left + 11, element.top - 3); // atas kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left, element.top + 3); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 3); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left, element.top + 9); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 9); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left , element.top + 15); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 15); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left , element.top + 21); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 21); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left , element.top + 27); // bawah kanan
                          context.lineTo(element.left + 15, element.top + 27); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left, element.top + 33); // bawah kanan
                          context.lineTo(element.left + 11, element.top + 33); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left, element.top + 39); // bawah kanan
                          context.lineTo(element.left + 5, element.top + 39); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
                      } else if (element.seg == 5) {

                          context.beginPath();
                          context.moveTo(element.left, element.top + 7); //bawah kiri
                          context.lineTo(element.left + 7, element.top ); // atas kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.moveTo(element.left , element.top + 14); //bawah kiri
                          context.lineTo(element.left + 14, element.top); // atas kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left, element.top + 21); // bawah kanan
                          context.lineTo(element.left + 21, element.top ); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left, element.top + 28); // bawah kanan
                          context.lineTo(element.left + 28, element.top ); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          
                          context.beginPath();
                          context.lineTo(element.left + 5 , element.top + 30); // bawah kanan
                          context.lineTo(element.left + 30, element.top + 5); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 12, element.top + 30); // bawah kanan
                          context.lineTo(element.left + 30, element.top + 12); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 19, element.top + 30); // bawah kanan
                          context.lineTo(element.left + 30, element.top + 19); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
      
                          context.beginPath();
                          context.lineTo(element.left + 26, element.top + 30); // bawah kanan
                          context.lineTo(element.left + 30, element.top + 26); //bawah kiri
                          context.closePath();
                          context.lineWidth = "1";
                          context.strokeStyle = "black";
                          context.stroke(); 
                      }
                    } else if(element.typejenis == '1') {
                      
                      if (element.seg == 1) {

                        context.beginPath();
                        context.moveTo(element.left - 9, element.top); //bawah kiri
                        context.lineTo(element.left - 9, element.top + 11); // atas kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.moveTo(element.left - 3, element.top); //bawah kiri
                        context.lineTo(element.left - 3, element.top + 22); // atas kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 3, element.top); // bawah kanan
                        context.lineTo(element.left + 3, element.top + 30); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 9, element.top); // bawah kanan
                        context.lineTo(element.left + 9, element.top + 30); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 15, element.top); // bawah kanan
                        context.lineTo(element.left + 15, element.top + 30); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 21, element.top); // bawah kanan
                        context.lineTo(element.left + 21, element.top + 30); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 27, element.top); // bawah kanan
                        context.lineTo(element.left + 27, element.top + 30); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 33, element.top); // bawah kanan
                        context.lineTo(element.left + 33, element.top + 22); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 39, element.top); // bawah kanan
                        context.lineTo(element.left + 39, element.top + 11); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 
                        } else if (element.seg == 2) {

                        context.beginPath();
                        context.moveTo(element.left + 11, element.top - 10); //bawah kiri
                        context.lineTo(element.left + 15, element.top - 10 ); // atas kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.moveTo(element.left + 8, element.top - 3); //bawah kiri
                        context.lineTo(element.left + 15, element.top - 3); // atas kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 5, element.top + 3); // bawah kanan
                        context.lineTo(element.left + 15, element.top + 3); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 3, element.top + 9); // bawah kanan
                        context.lineTo(element.left + 15, element.top + 9); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left, element.top + 15); // bawah kanan
                        context.lineTo(element.left + 15, element.top + 15); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 3, element.top + 21); // bawah kanan
                        context.lineTo(element.left + 15, element.top + 21); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 5, element.top + 27); // bawah kanan
                        context.lineTo(element.left + 15, element.top + 27); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 8, element.top + 33); // bawah kanan
                        context.lineTo(element.left + 15, element.top + 33); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 11, element.top + 39); // bawah kanan
                        context.lineTo(element.left + 15, element.top + 39); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 
                        } else if (element.seg == 3) {

                        context.beginPath();
                        context.moveTo(element.left - 9, element.top); //bawah kiri
                        context.lineTo(element.left - 9, element.top + 15); // atas kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.moveTo(element.left - 3, element.top - 8); //bawah kiri
                        context.lineTo(element.left - 3, element.top + 15); // atas kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 3, element.top - 15 ); // bawah kanan
                        context.lineTo(element.left + 3, element.top + 15); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 9, element.top - 15); // bawah kanan
                        context.lineTo(element.left + 9, element.top + 15); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 15, element.top - 15); // bawah kanan
                        context.lineTo(element.left + 15, element.top + 15); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 21, element.top - 15); // bawah kanan
                        context.lineTo(element.left + 21, element.top + 15); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 27, element.top - 15); // bawah kanan
                        context.lineTo(element.left + 27, element.top + 15); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 33, element.top - 8); // bawah kanan
                        context.lineTo(element.left + 33, element.top + 15); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left + 39, element.top); // bawah kanan
                        context.lineTo(element.left + 39, element.top + 15); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 
                        } else if (element.seg == 4) {

                        context.beginPath();
                        context.moveTo(element.left, element.top - 10); //bawah kiri
                        context.lineTo(element.left + 3, element.top - 10 ); // atas kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.moveTo(element.left , element.top - 3); //bawah kiri
                        context.lineTo(element.left + 5, element.top - 3); // atas kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left, element.top + 3); // bawah kanan
                        context.lineTo(element.left + 8, element.top + 3); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left, element.top + 9); // bawah kanan
                        context.lineTo(element.left + 11, element.top + 9); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left , element.top + 15); // bawah kanan
                        context.lineTo(element.left + 15, element.top + 15); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left , element.top + 21); // bawah kanan
                        context.lineTo(element.left + 11, element.top + 21); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left , element.top + 27); // bawah kanan
                        context.lineTo(element.left + 8, element.top + 27); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left, element.top + 33); // bawah kanan
                        context.lineTo(element.left + 5, element.top + 33); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 

                        context.beginPath();
                        context.lineTo(element.left, element.top + 39); // bawah kanan
                        context.lineTo(element.left + 3, element.top + 39); //bawah kiri
                        context.closePath();
                        context.lineWidth = "1";
                        context.strokeStyle = "black";
                        context.stroke(); 
                        } 


                    }
                    
                } else if (element.type != '') {
                    if (element.type != 'white') {
                        data2.push(element)
                    }
                }

            }


//================================================================================



/*
const drawBase64Image =(): void {
        // Get the canvas element
        const canvas = this.$refs.canvas as HTMLCanvasElement;
        const ctx = canvas.getContext('2d');
        
        // Base64 source
        const base64Image = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGElEQVQYlWNgYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg==';
        
        // Create an image element
        const image = new Image();
        image.src = base64Image;

        // Draw the image onto the canvas when it's loaded
        image.onload = () => {
          ctx.drawImage(image, 0, 0);
        };
      }

      // Call drawBase64Image method after the component is mounted
      mounted() {
        this.drawBase64Image();
      }*/
    

const toggle = (event, e) => {
  overlaypanel.value.toggle(event);
  selected.value = e
}


const setView = () => {
  //MARKINGSITE.value = '/images/odontogram/odontogram.png'
  MARKINGSITE.value = "data:image/png;base64,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"


}
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
        }

        
        if (response[0].objodontogram !== '') {
          
            /*var canvas =document.getElementById('markingsite'); 
            var ctx = canvas.getContext('2d');

            var img = new Image();
            var img.src = "data:image/png;base64," + response[0].objodontogram;

            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
              
*/
          MARKINGSITE.value = "data:image/png;base64," + response[0].objodontogram
          console.log(MARKINGSITE.value);
        }
      }
    })
}

const simpan = () => {
    console.log( COLLECTION.value)
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}


  var canvas =document.getElementById('markingsite'); 

  // Load the background image
  // Convert canvas to dataURL and log to console
  const dataURL = canvas.toDataURL();
  console.log(dataURL);
                 
  // Convert to Base64 string
  const base64 = dataURL.split(',')[1];
  console.log(base64);
  

  object = input.value
  object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object.objodontogram = base64
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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }
const clearCanvas = (canvas: any) => {

  var sigCanvas: any = document.getElementById(canvas);
  var context = sigCanvas.getContext("2d");
  context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);

  setView()
  markignSite();
  // setAutoFill()
  // loadRiwayat()
  // var defImg = new Image();
  // defImg.src = MARKINGSITE.value; 
  // sigCanvas.width = defImg.width;
  // sigCanvas.height = defImg.height;
  
  // context.drawImage(defImg, 0, 0)

}
const skor = (e: any, i: any) => {

  let listSkor = listSkoringNyeri.value.detail

  listSkor.forEach((element: any) => {
    if (element.descNilai == e.descNilai) {
      input.value.skoringNyeri = e.descNilai
    }
  });
  isAktive.value = i

}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  await useApi().get(
    "emr/auto-fill?nocmfk=" + ID_PASIEN +
    "&collection=VitalSign" +
    "&field=beratBadan,tinggiBadan"
  ).then((response) => {
    if (response != null) {
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
    }
  })
  input.value.tglPembuatan = new Date()
}
const getPosition = (mouseEvent: any, sigCanvas: any) => {
  let rect = sigCanvas.getBoundingClientRect();
  return {
    X: mouseEvent.clientX - rect.left,
    Y: mouseEvent.clientY - rect.top
  };
}
/*
const canvasOdonto = () => {

  var imyCanvas="";
  var canvas: any  = document.getElementById('myCanvas');
  var context = canvas.getContext('2d');

  var img = new Image();
  img.onload = () => {
    context.drawImage(img, 0, 0);
  };
  img.src = imyCanvas;

}*/

const markignSite = () => {
  let sigCanvas: any = document.getElementById("markingsite");
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  
  //var elemLeft = sigCanvas.offsetLeft
  //var elemTop = sigCanvas.offsetTop
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "black";
  context.lineJoin = "round";
  context.lineWidth = 1;

  

//=======================================

var listSrc = [
                { id: 1, no: '18' },
                { id: 2, no: '17' },
                { id: 3, no: '16' },
                { id: 4, no: '15' },
                { id: 5, no: '14' },
                { id: 6, no: '13' },
                { id: 7, no: '12' },
                { id: 8, no: '11' },

                { id: 9, no: '21' },
                { id: 10, no: '22' },
                { id: 11, no: '23' },
                { id: 12, no: '24' },
                { id: 13, no: '25' },
                { id: 14, no: '26' },
                { id: 15, no: '27' },
                { id: 16, no: '28' },

                { id: 17, no: '55' },
                { id: 18, no: '54' },
                { id: 19, no: '53' },
                { id: 20, no: '52' },
                { id: 21, no: '51' },

                { id: 22, no: '61' },
                { id: 23, no: '62' },
                { id: 24, no: '63' },
                { id: 25, no: '64' },
                { id: 26, no: '65' },

                { id: 27, no: '85' },
                { id: 28, no: '84' },
                { id: 29, no: '83' },
                { id: 30, no: '82' },
                { id: 31, no: '81' },

                { id: 32, no: '71' },
                { id: 33, no: '72' },
                { id: 34, no: '73' },
                { id: 35, no: '74' },
                { id: 36, no: '75' },

                { id: 37, no: '48' },
                { id: 38, no: '47' },
                { id: 39, no: '46' },
                { id: 40, no: '45' },
                { id: 41, no: '44' },
                { id: 42, no: '43' },
                { id: 43, no: '42' },
                { id: 44, no: '41' },

                { id: 45, no: '31' },
                { id: 46, no: '32' },
                { id: 47, no: '33' },
                { id: 48, no: '34' },
                { id: 49, no: '35' },
                { id: 50, no: '36' },
                { id: 51, no: '37' },
                { id: 52, no: '38' }
            ]



            var nextKotak = 0
            var besarKotak = 65
            var spc = 0
            var ididKlik = 0
            var idSrc = 0

            var typeGigi=0;
            //sayur kol 1
            for (var i = 0; i < 16; i++) {
                if (i == 8) {
                    spc = 20
                }

                if(i == 5 || i == 6 || i == 7 || i == 8 || i == 9 || i == 10) {
                    context.moveTo(40 + (i * besarKotak) + spc, 40);
                    context.lineTo(100 + (i * besarKotak) + spc, 40);
                    context.lineTo(100 + (i * besarKotak) + spc, 100);
                    context.lineTo(40 + (i * besarKotak) + spc, 100);
                    context.lineTo(40 + (i * besarKotak) + spc, 40);

                    context.moveTo(40 + (i * besarKotak) + spc, 40);
                    context.lineTo(55 + (i * besarKotak) + spc, 70);
                    context.lineTo(40 + (i * besarKotak) + spc, 100);

                    context.moveTo(100 + (i * besarKotak) + spc, 40);
                    context.lineTo(85 + (i * besarKotak) + spc, 70);
                    context.lineTo(100 + (i * besarKotak) + spc, 100);

                    context.moveTo(55 + (i * besarKotak) + spc, 70);
                    context.lineTo(85 + (i * besarKotak) + spc, 70);
                    
                    typeGigi=1;
                } else {
                    context.moveTo(40 + (i * besarKotak) + spc, 40);
                    context.lineTo(100 + (i * besarKotak) + spc, 40);
                    context.lineTo(100 + (i * besarKotak) + spc, 100);
                    context.lineTo(40 + (i * besarKotak) + spc, 100);
                    context.lineTo(40 + (i * besarKotak) + spc, 40);

                    context.lineTo(55 + (i * besarKotak) + spc, 55);
                    context.lineTo(55 + (i * besarKotak) + spc, 85);
                    context.lineTo(40 + (i * besarKotak) + spc, 100);

                    context.moveTo(55 + (i * besarKotak) + spc, 85);
                    context.lineTo(85 + (i * besarKotak) + spc, 85);
                    context.lineTo(100 + (i * besarKotak) + spc, 100);

                    context.moveTo(85 + (i * besarKotak) + spc, 85);
                    context.lineTo(85 + (i * besarKotak) + spc, 55);
                    context.lineTo(100 + (i * besarKotak) + spc, 40);

                    context.moveTo(85 + (i * besarKotak) + spc, 55);
                    context.lineTo(55 + (i * besarKotak) + spc, 55);

                    
                    typeGigi=0;
                }

                context.stroke();


                context.font = "20px Tahoma";
                context.fillStyle = "black";
                context.fillText(listSrc[idSrc].no, 57 + (i * besarKotak) + spc, 120);

                idSrc = idSrc + 1

                elements.push(
                    //A1
                    {
                        colour: 'black',
                        width: 30, height: 15,
                        top: 40, left: 55 + (i * besarKotak) + spc,
                        id: ididKlik + 1,
                        brs: 1, kol: i + 1, seg: 1, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 15, height: 30,
                        top: 55, left: 85 + (i * besarKotak) + spc,
                        id: ididKlik + 2,
                        brs: 1, kol: i + 1, seg: 2, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 30, height: 15,
                        top: 85, left: 55 + (i * besarKotak) + spc,
                        id: ididKlik + 3,
                        brs: 1, kol: i + 1, seg: 3, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 15, height: 30,
                        top: 55, left: 40 + (i * besarKotak) + spc,
                        id: ididKlik + 4,
                        brs: 1, kol: i + 1, seg: 4, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 30, height: 30,
                        top: 55, left: 55 + (i * besarKotak) + spc,
                        id: ididKlik + 5,
                        brs: 1, kol: i + 1, seg: 5, typejenis: typeGigi
                    }
                );
                ididKlik = ididKlik + 5
            }

            spc = 0 
            //sayur kol 2
            for (var i = 0; i < 10; i++) {
                if (i == 5) {
                    spc = 20
                }




                if(i == 2 || i == 3 || i == 4 || i == 5 || i == 6 || i == 7) {
                    
                    context.moveTo(235 + (i * besarKotak) + spc, 150);
                    context.lineTo(235 + (i * besarKotak) + spc, 210);
                    context.lineTo(295 + (i * besarKotak) + spc, 210);
                    context.lineTo(295 + (i * besarKotak) + spc, 150);
                    context.lineTo(235 + (i * besarKotak) + spc, 150);

                    
                    context.moveTo(235 + (i * besarKotak) + spc, 150);
                    context.lineTo(250 + (i * besarKotak) + spc, 180);
                    context.lineTo(235 + (i * besarKotak) + spc, 210);

                    context.moveTo(295 + (i * besarKotak) + spc, 150);
                    context.lineTo(280 + (i * besarKotak) + spc, 180);
                    context.lineTo(295 + (i * besarKotak) + spc, 210);

                    context.moveTo(250 + (i * besarKotak) + spc, 180);
                    context.lineTo(280 + (i * besarKotak) + spc, 180);


                    
                    typeGigi=1;
                } else {

                    context.moveTo(235 + (i * besarKotak) + spc, 150);
                    context.lineTo(235 + (i * besarKotak) + spc, 210);
                    context.lineTo(295 + (i * besarKotak) + spc, 210);
                    context.lineTo(295 + (i * besarKotak) + spc, 150);
                    context.lineTo(235 + (i * besarKotak) + spc, 150);

                    context.moveTo(235 + (i * besarKotak) + spc, 150);
                    context.lineTo(250 + (i * besarKotak) + spc, 165);
                    context.lineTo(250 + (i * besarKotak) + spc, 195);
                    context.lineTo(235 + (i * besarKotak) + spc, 210);

                    context.moveTo(250 + (i * besarKotak) + spc, 195);
                    context.lineTo(280 + (i * besarKotak) + spc, 195);
                    context.lineTo(295 + (i * besarKotak) + spc, 210);

                    context.moveTo(280 + (i * besarKotak) + spc, 195);
                    context.lineTo(280 + (i * besarKotak) + spc, 165);
                    context.lineTo(295 + (i * besarKotak) + spc, 150);

                    context.moveTo(280 + (i * besarKotak) + spc, 165);
                    context.lineTo(250 + (i * besarKotak) + spc, 165);

                    typeGigi=0;
                }

                context.stroke();

                context.font = "20px Tahoma";
                context.fillStyle = "black";
                context.fillText(listSrc[idSrc].no, 252 + (i * besarKotak) + spc, 230);

                idSrc = idSrc + 1

                elements.push(
                    {
                        colour: 'black',
                        width: 30, height: 15,
                        top: 150, left: 250 + (i * besarKotak) + spc,
                        id: ididKlik + 1,
                        brs: 2, kol: i + 1, seg: 1, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 15, height: 30,
                        top: 165, left: 280 + (i * besarKotak) + spc,
                        id: ididKlik + 2,
                        brs: 2, kol: i + 1, seg: 2, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 30, height: 15,
                        top: 195, left: 250 + (i * besarKotak) + spc,
                        id: ididKlik + 3,
                        brs: 2, kol: i + 1, seg: 3, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 15, height: 30,
                        top: 165, left: 235 + (i * besarKotak) + spc,
                        id: ididKlik + 4,
                        brs: 2, kol: i + 1, seg: 4, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 30, height: 30,
                        top: 165, left: 250 + (i * besarKotak) + spc,
                        id: ididKlik + 5,
                        brs: 2, kol: i + 1, seg: 5, typejenis: typeGigi
                    }
                );
                ididKlik = ididKlik + 5
            }

            spc = 0
            //sayur kol 3
            for (var i = 0; i < 10; i++) {
                if (i == 5) {
                    spc = 20
                }

                
                if(i == 2 || i == 3 || i == 4 || i == 5 || i == 6 || i == 7) {
                    
                    context.moveTo(235 + (i * besarKotak) + spc, 260);
                    context.lineTo(235 + (i * besarKotak) + spc, 320);
                    context.lineTo(295 + (i * besarKotak) + spc, 320);
                    context.lineTo(295 + (i * besarKotak) + spc, 260);
                    context.lineTo(235 + (i * besarKotak) + spc, 260);

                    
                    context.moveTo(235 + (i * besarKotak) + spc, 260);
                    context.lineTo(250 + (i * besarKotak) + spc, 290);
                    context.lineTo(235 + (i * besarKotak) + spc, 320);

                    context.moveTo(295 + (i * besarKotak) + spc, 260);
                    context.lineTo(280 + (i * besarKotak) + spc, 290);
                    context.lineTo(295 + (i * besarKotak) + spc, 320);

                    context.moveTo(250 + (i * besarKotak) + spc, 290);
                    context.lineTo(280 + (i * besarKotak) + spc, 290);

                    typeGigi = 1;
                    
                    
                } else {
                    context.moveTo(235 + (i * besarKotak) + spc, 260);
                    context.lineTo(235 + (i * besarKotak) + spc, 320);
                    context.lineTo(295 + (i * besarKotak) + spc, 320);
                    context.lineTo(295 + (i * besarKotak) + spc, 260);
                    context.lineTo(235 + (i * besarKotak) + spc, 260);

                    context.moveTo(235 + (i * besarKotak) + spc, 260);
                    context.lineTo(250 + (i * besarKotak) + spc, 275);
                    context.lineTo(250 + (i * besarKotak) + spc, 305);
                    context.lineTo(235 + (i * besarKotak) + spc, 320);

                    context.moveTo(250 + (i * besarKotak) + spc, 305);
                    context.lineTo(280 + (i * besarKotak) + spc, 305);
                    context.lineTo(295 + (i * besarKotak) + spc, 320);

                    context.moveTo(280 + (i * besarKotak) + spc, 305);
                    context.lineTo(280 + (i * besarKotak) + spc, 275);
                    context.lineTo(295 + (i * besarKotak) + spc, 260);

                    context.moveTo(280 + (i * besarKotak) + spc, 275);
                    context.lineTo(250 + (i * besarKotak) + spc, 275);

                    typeGigi = 0;
                }

                context.stroke();

                context.font = "20px Tahoma";
                context.fillStyle = "black";
                context.fillText(listSrc[idSrc].no, 252 + (i * besarKotak) + spc, 340);

                idSrc = idSrc + 1


                elements.push(
                    {
                        colour: 'black',
                        width: 30, height: 15,
                        top: 260, left: 250 + (i * besarKotak) + spc,
                        id: ididKlik + 1,
                        brs: 3, kol: i + 1, seg: 1, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 15, height: 30,
                        top: 275, left: 280 + (i * besarKotak) + spc,
                        id: ididKlik + 2,
                        brs: 3, kol: i + 1, seg: 2, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 30, height: 15,
                        top: 305, left: 250 + (i * besarKotak) + spc,
                        id: ididKlik + 3,
                        brs: 3, kol: i + 1, seg: 3, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 15, height: 30,
                        top: 275, left: 235 + (i * besarKotak) + spc,
                        id: ididKlik + 4,
                        brs: 3, kol: i + 1, seg: 4, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 30, height: 30,
                        top: 275, left: 250 + (i * besarKotak) + spc,
                        id: ididKlik + 5,
                        brs: 3, kol: i + 1, seg: 5, typejenis: typeGigi
                    }
                );
                ididKlik = ididKlik + 5
            }

            spc = 0
            //sayur kol 4
            for (var i = 0; i < 16; i++) {
                if (i == 8) {
                    spc = 20
                }

                
                if(i == 5 || i == 6 || i == 7 || i == 8 || i == 9 || i == 10) {
                    context.moveTo(40 + (i * besarKotak) + spc, 430);
                    context.lineTo(100 + (i * besarKotak) + spc, 430);
                    context.lineTo(100 + (i * besarKotak) + spc, 370);
                    context.lineTo(40 + (i * besarKotak) + spc, 370);
                    context.lineTo(40 + (i * besarKotak) + spc, 430);

                    context.moveTo(40 + (i * besarKotak) + spc, 370);
                    context.lineTo(55 + (i * besarKotak) + spc, 400);
                    context.lineTo(40 + (i * besarKotak) + spc, 430);

                    context.moveTo(100 + (i * besarKotak) + spc, 370);
                    context.lineTo(85 + (i * besarKotak) + spc, 400);
                    context.lineTo(100 + (i * besarKotak) + spc, 430);

                    context.moveTo(55 + (i * besarKotak) + spc, 400);
                    context.lineTo(85 + (i * besarKotak) + spc, 400);
                    
                    typeGigi=1;
                } else {
                    context.moveTo(40 + (i * besarKotak) + spc, 430);
                    context.lineTo(100 + (i * besarKotak) + spc, 430);
                    context.lineTo(100 + (i * besarKotak) + spc, 370);
                    context.lineTo(40 + (i * besarKotak) + spc, 370);
                    context.lineTo(40 + (i * besarKotak) + spc, 430);

                    context.moveTo(40 + (i * besarKotak) + spc, 370);
                    context.lineTo(55 + (i * besarKotak) + spc, 385);
                    context.lineTo(55 + (i * besarKotak) + spc, 415);
                    context.lineTo(40 + (i * besarKotak) + spc, 430);

                    context.moveTo(55 + (i * besarKotak) + spc, 415);
                    context.lineTo(85 + (i * besarKotak) + spc, 415);
                    context.lineTo(100 + (i * besarKotak) + spc, 430);

                    context.moveTo(85 + (i * besarKotak) + spc, 415);
                    context.lineTo(85 + (i * besarKotak) + spc, 385);
                    context.lineTo(100 + (i * besarKotak) + spc, 370);

                    context.moveTo(85 + (i * besarKotak) + spc, 385);
                    context.lineTo(55 + (i * besarKotak) + spc, 385);

                    typeGigi=0;
                }

                context.stroke();

                context.font = "20px Tahoma";
                context.fillStyle = "black";
                context.fillText(listSrc[idSrc].no, 57 + (i * besarKotak) + spc, 450);

                idSrc = idSrc + 1

                elements.push(
                    {
                        colour: 'black',
                        width: 30, height: 15,
                        top: 370, left: 55 + (i * besarKotak) + spc,
                        id: ididKlik + 1,
                        brs: 3, kol: i + 1, seg: 1, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 15, height: 30,
                        top: 385, left: 85 + (i * besarKotak) + spc,
                        id: ididKlik + 2,
                        brs: 3, kol: i + 1, seg: 2, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 30, height: 15,
                        top: 415, left: 55 + (i * besarKotak) + spc,
                        id: ididKlik + 3,
                        brs: 3, kol: i + 1, seg: 3, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 15, height: 30,
                        top: 385, left: 40 + (i * besarKotak) + spc,
                        id: ididKlik + 4,
                        brs: 3, kol: i + 1, seg: 4, typejenis: typeGigi
                    },
                    {
                        colour: 'black',
                        width: 30, height: 30,
                        top: 385, left: 55 + (i * besarKotak) + spc,
                        id: ididKlik + 5,
                        brs: 3, kol: i + 1, seg: 5, typejenis: typeGigi
                    }
                );
                ididKlik = ididKlik + 5

            }
//=======================================

  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {

    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        console.log('Touch start', coors)
        this.isDrawing = true;
      },
      touchmove: function (coors: any) {
        if (this.isDrawing) {
          context.lineTo(coors.x, coors.y);
          context.stroke();
        }
      },
      touchend: function (coors: any) {
        if (this.isDrawing) {
          this.touchmove(coors);
          this.isDrawing = false;
          console.log('Touched', coors)
        }
      }
    };


    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;

      if (obj.offsetParent) {

        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        }

        while ((obj = obj.offsetParent) != null);
      }


      drawer[event.type](coors);
    }


    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);


    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();

    }, false);
  } else {

    $("#markingsite").mousedown(function (mouseEvent: any) {

      //const rect = canvas.getBoundingClientRect();
      //const x = mouseEvent.clientX - sigCanvas.left;
      //const y = mouseEvent.clientY - sigCanvas.top;
      
      let position = getPosition(mouseEvent, sigCanvas);


      // console.log("Canvas clicked at:", position.X, position.Y);

      let x=position.X;
      let y=position.Y;

      const element: any= ref();
      /*element.left=40
      element.top=40
      
      context.beginPath();
                        context.moveTo(element.left - 13, element.top + 1);
                        context.lineTo(element.left + 43, element.top + 1);
                        context.lineTo(element.left + 29, element.top + 15);
                        context.lineTo(element.left + 1, element.top + 15);
                        context.lineTo(element.left - 13, element.top + 1);
                        context.closePath();
                        context.lineWidth = "3";
                        context.strokeStyle = "black";
                        context.stroke();
                        context.fillStyle = isianColor;
                        context.fill();*/

      // console.log('elem x:' + element.left + ' y:' + element.top + ' width:' + element.width + ' height:' + element.height);
      //console.log('elem x:' + x + '-' + element.left + ' y:' + y + '-' + element.top + ' width:' + element.width + ' height:' + element.height);

      elements.forEach(function (element: any) {
        
                    //var x = element.pageX,// - elemLeft,
                    //y = element.pageY //- elemTop;
        //console.log('elem x:' + element.left + ' y:' + element.top + ' width:' + element.width + ' height:' + element.height);

                    if (y > element.top + 55 && y < element.top + 55 + element.height && x > element.left && x < element.left + element.width) {
                        
                        // console.log('xx elem x:' + element.left + ' y:' + element.top + ' width:' + element.width + ' height:' + element.height);
                        isiColor(element)
                    }
                });
      
      /*context.moveTo(position.X, position.Y);
      context.beginPath();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });*/
    });

  }
}
const drawLine = (mouseEvent: any, sigCanvas: any, context: any) => {

  let position = getPosition(mouseEvent, sigCanvas);

  context.lineTo(position.X, position.Y);
  context.stroke();
}
const finishDrawing = (mouseEvent: any, sigCanvas: any, context: any) => {
  drawLine(mouseEvent, sigCanvas, context);

  context.closePath();
  $(sigCanvas).unbind("mousemove")
    .unbind("mouseup")
    .unbind("mouseout");
}
const fetchPerawat = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}

const setTandaTangan = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_1", element.ttd)
    } else {
      H.tandaTangan().set("signature_1", '')
    }
  })
}


const fetchDiagnosaKeperawatan = async (filter: any) => {
  await useApi().get(`/emr/get-dropdown-diagnosa-keperawatan?search=${filter.query}`).then((response) => {
    d_DiagnosaKeperawatan.value = response
  })
}
const addNewDiagnosaKeper = () => {
  input.value.diagnosaKeper.push({
    no: input.value.diagnosaKeper[input.value.diagnosaKeper.length - 1].no + 1,
  });
}
const removeItemDiagnosaKeper = (index: any) => {
  input.value.diagnosaKeper.splice(index, 1)
}
watch(() => [
  input.value.penurunanBB,
  input.value.penurunanNafsuMakan,
  jenisKelamin.value,
], () => {

  let poin1 = input.value.penurunanBB ? parseInt(input.value.penurunanBB.poin) : 0
  let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.poin) : 0

  const total = poin1 + poin2
  input.value.totalNilaiPemeriksaanNutrisi = total

})
async function performAction() {
  await sleep(1000);
  markignSite();
}
performAction();
setView()
setAutoFill()
loadRiwayat()


</script>

<style lang="scss">

.gede {
      /*float: left;*/
      text-align: center;
      cursor: pointer;
      height: 100%;
      min-height: 40px;
      margin-top: auto;
      margin-bottom: auto;
      font-size: 11px;
      color: black;
      background-color: #fff;
      width: 50px;
      border: 1px  solid;
      /*padding: 5px;
      -webkit-box-shadow: 0px 3px 15px -1px rgba(0, 0, 0, 0.25);
      -moz-box-shadow: 0px 3px 15px -1px rgba(0, 0, 0, 0.25);
      box-shadow: 0px 3px 15px -1px rgba(0, 0, 0, 0.25);*/
}


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

.table-fkprj,
.tr-fkprj,
.th-fkprj,
.td-fkprj {
  border: 1.6px solid black !important;
}

.th-fkprj,
.td-fkprj {
  padding: 8px !important;
}
</style>
