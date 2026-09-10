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

    <div class="column is-12">
        <VCard>
            <div class="column is-12">
                <Fieldset :toggleable="true" legend="Pemeriksaan IN-VITRO / RIA dan IRMA">
                    <div class="columns is-multiline">
                        <div class="column is-5">
                            <h1 style="font-weight: bold;">Evaluasi Tiroid : </h1>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.t3" true-value="T3"
                                                label="T3" color="primary" circle />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.ft4" true-value="FT4"
                                                label="FT4" color="primary" circle />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.tshs" true-value="TSHs"
                                                label="TSHs" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.tiroglobulin" true-value="Tiroglobulin"
                                                label="Tiroglobulin" color="primary" circle />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.antitpo" true-value="Antibodi antimikrosomal / Anti TPO"
                                                label="Antibodi antimikrosomal / Anti TPO" color="primary" circle />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.antitiroglobulin" true-value="Antibodi antitiroglobulin"
                                                label="Antibodi antitiroglobulin" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-1">
                            <h1 style="font-weight: bold;">Catatan : </h1>
                        </div>
                        <div class="column is-6">
                            <h1 style="font-weight: normal;">1. Penderita harap memberitahu petugas bila sedang hamil, menyusui atau sedang menggunakan obat-obatan yang mungkin dapat mengganggu pemeriksaan</h1>
                            <h1 style="font-weight: normal;">2. Pemeriksaan renografi baru dapat dilaksanakan +- 2 minggu setelah IVP/pyelografi</h1>
                            <h1 style="font-weight: normal;">3. Untuk pemeriksaan sidik seluruh tubuh I-131 & pengobatan I-131 penderita harus puasa paling kurang 6 jam</h1>
                            <h1 style="font-weight: normal; margin-top: 20px;">*Coret yang tidak perlu</h1>
                        </div>
                    </div>
                </Fieldset>
            </div>
        </VCard>
    </div>

    <div class="column is-12">
        <VCard>
            <div class="column is-12">
                <Fieldset :toggleable="true" legend="Pemeriksaan IN-VIVO">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <h1 style="font-weight: bold;">KELENJAR TIROID & PARATIROID </h1>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidiktiroid" true-value="Sidik kelenjar tiroid"
                                        label="Sidik kelenjar tiroid" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.ujitangkap" true-value="Uji tangkap"
                                        label="Uji tangkap" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.ujisupresi" true-value="Uji supresi"
                                        label="Uji supresi" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.ujistimulasi" true-value="Uji stimulasi"
                                        label="Uji stimulasi" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidikparatiroid" true-value="Sidik kelenjar paratiroid"
                                        label="Sidik kelenjar paratiroid" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold;">PARU-PARU </h1>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidikventilasi" true-value="Sidik ventilasi paru"
                                        label="Sidik ventilasi paru" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidikferfusi" true-value="Sidik ferfusi paru"
                                        label="Sidik ferfusi paru" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.regionalparu" true-value="Penentuan fungsi regional paru"
                                        label="Penentuan fungsi regional paru" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.alveolarparu" true-value="Permeabilitas alveolar paru"
                                        label="Permeabilitas alveolar paru" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold;">HATI KANDUNGAN EMPEDU & LIMPA </h1>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidikhati" true-value="Sidik hati"
                                        label="Sidik hati" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.liver" true-value="Liver blood pool soan"
                                        label="Liver blood pool soan" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.hepatobiliaris" true-value="Sidik sistem hepatobiliaris"
                                        label="Sidik sistem hepatobiliaris" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidiklimpa" true-value="Sidik limpa"
                                        label="Sidik limpa" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold;">GINJAL & KANDUNGAN KEMIH </h1>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.renografi" true-value="Renografi"
                                        label="Renografi" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField style="margin-left: 40px;">
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.konvensional" true-value="Konvensional"
                                        label="Konvensional" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField style="margin-left: 40px;">
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.diuresis" true-value="Diuresis"
                                        label="Diuresis" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField style="margin-left: 40px;">
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.kaptopril" true-value="Kaptopril"
                                        label="Kaptopril" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.lajufitrasi" true-value="Laju fitrasi glomerulus"
                                        label="Laju fitrasi glomerulus" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.darahginjal" true-value="Aliran darah ginjal efektif"
                                        label="Aliran darah ginjal efektif" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.ginjal" true-value="Sidik ginjal"
                                        label="Sidik ginjal" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sistografi" true-value="Sistografi"
                                        label="Sistografi" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold;">KARDIOVASKULER </h1>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.miokard" true-value="Sidik perfusi miokard"
                                        label="Sidik perfusi miokard" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.muga" true-value="Sidik jantung-MUGA"
                                        label="Sidik jantung-MUGA" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.ventrikel" true-value="Penilaian fungsi ventrikel kiri"
                                        label="Penilaian fungsi ventrikel kiri" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.studilintas" true-value="Studi lintas pertama"
                                        label="Studi lintas pertama" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.shunt" true-value="Deteksi & kuantifikasi shunt"
                                        label="Deteksi & kuantifikasi shunt" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.filbografi" true-value="Filbografi/Venografi"
                                        label="Filbografi/Venografi" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold;">SALURAN CERNA </h1>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.saliva" true-value="Sidik kelenjar saliva"
                                        label="Sidik kelenjar saliva" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.esofagus" true-value="Waktu transit esofagus"
                                        label="Waktu transit esofagus" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.refluks" true-value="Deteksi refluks gastro-ensofagus"
                                        label="Deteksi refluks gastro-ensofagus" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.pengosongan" true-value="Waktu pengosongan lambung"
                                        label="Waktu pengosongan lambung" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.intestinal" true-value="Lokalisasi perdarahan intestinal"
                                        label="Lokalisasi perdarahan intestinal" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.divertikulum" true-value="Deteksi divertikulum meckel"
                                        label="Deteksi divertikulum meckel" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold;">ONKOLOGI & ORTHOPEDI </h1>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidiktulang" true-value="Sidik tulang"
                                        label="Sidik tulang" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField style="margin-left: 40px;">
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.statik" true-value="Statik"
                                        label="Statik" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField style="margin-left: 40px;">
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.dinamik" true-value="Dinamik (3 fase)"
                                        label="Dinamik (3 fase)" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidiktubuh" true-value="Sidik seluruh tubuh dengan"
                                        label="Sidik seluruh tubuh dengan" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField style="margin-left: 40px;">
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.Tc99m" true-value="Tc99m MIBI"
                                        label="Tc99m MIBI" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField style="margin-left: 40px;">
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.i131" true-value="I-131"
                                        label="I-131" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidikpayudara" true-value="Sidik payudara"
                                        label="Sidik payudara" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sentinel" true-value="Deteksi sentinel node"
                                        label="Deteksi sentinel node" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold;">LAIN-LAIN </h1>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidikotak" true-value="Sidik otak"
                                        label="Sidik otak" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.dakriosistografi" true-value="Dakriosistografi"
                                        label="Dakriosistografi" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.limfoskintigrafi" true-value="Limfoskintigrafi"
                                        label="Limfoskintigrafi" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.lokalisasi" true-value="Lokalisasi infeksi / Inflamasi"
                                        label="Lokalisasi infeksi / Inflamasi" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidiktestis" true-value="Sidik testis"
                                        label="Sidik testis" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.sidiketambutol" true-value="Sidik etambutol"
                                        label="Sidik etambutol" color="primary" circle />
                                </VControl>
                            </VField>
                            <div class="columns is-multiline">
                                <div class="column is-2">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.kosong" true-value=""
                                                label="" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-10">
                                    <VField class="pt-3">
                                        <VControl>
                                            <VInput type="text" v-model="input.ketKosong" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3">
                            <h1 style="font-weight: bold;">PET/CT Scan </h1>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.onkologi" true-value="Onkologi"
                                        label="Onkologi" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.kardiologi" true-value="Kardiologi"
                                        label="Kardiologi" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.neurologi" true-value="Neurologi"
                                        label="Neurologi" color="primary" circle />
                                </VControl>
                            </VField>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.lainlain" true-value="Lain-lain"
                                                label="Lain-lain" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="pt-3">
                                        <VControl>
                                            <VInput type="text" v-model="input.ketLainlain" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>
            </div>
        </VCard>
    </div>

    <div class="column is-12">
        <VCard>
            <div class="column is-12">
                <Fieldset :toggleable="true" legend="TERAPI RADIOAKTIF">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.hipertiroidism" true-value="Hipertiroidism"
                                        label="Hipertiroidism" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.karsinoma" true-value="Karsinoma tiroid"
                                        label="Karsinoma tiroid" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.hemangioma" true-value="Hemangioma"
                                        label="Hemangioma" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.koloid" true-value="Koloid"
                                        label="Koloid" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.pallatif" true-value="Nyeri tulang pallatif"
                                        label="Nyeri tulang pallatif" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.hemangioma" true-value="Kersinoma hepat"
                                        label="Kersinoma hepat" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox" v-model="input.koloid" true-value="Sinovektomi"
                                        label="Sinovektomi" color="primary" circle />
                                </VControl>
                            </VField>
                            <div class="columns is-multiline">
                                <div class="column is-2">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.kosong2" true-value=""
                                                label="" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-10">
                                    <VField class="pt-3">
                                        <VControl>
                                            <VInput type="text" v-model="input.ketKosong2" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.kosong3" true-value=""
                                                label="" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-10">
                                    <VField class="pt-3">
                                        <VControl>
                                            <VInput type="text" v-model="input.ketKosong3" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold;">Berikan tanda "✓" pada pemeriksaan/terapi </h1>
                        </div>
                    </div>
                </Fieldset>
            </div>
        </VCard>
    </div>
  
    <div class="column is-12">
      <VCard>
        <div class="column">
  
          <div class="columns is-multiline pt-5" style="justify-content: space-around;">
            <div class="column is-4" style="text-align: center;">
              
            </div>
            <div class="column is-4" style="text-align: center;">
                <TandaTangan :elemenID="'signaturePegawai'" :width="'180'" :height="'180'" class="dek" />
              <div class="column pl-0 pr-0 pt-5">
                <span class="label-ppap">Tanda Tangan Dokter</span>
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.pegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"  @item-select="setTandaTanganPerawat($event)"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai..." />
                  </VControl>
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
  import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  import ButtonEmr from '../page-emr-plugins/button-emr.vue'
  import AutoComplete from 'primevue/autocomplete';
  import Fieldset from 'primevue/fieldset';
  import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useUserSession } from '/@src/stores/userSession'
  
  
  let alasanPulang: any = ([
    {
      "label": "a. Menolak rawat inap",
      "model": "alasanPulang",
    },
    {
      "label": "b. Pindah RS/alih rawat",
      "model": "alasanPulang",
    },
    {
      "label": "c. Merasa sembuh",
      "model": "alasanPulang",
    },
    {
      "label": "d. Merasa tidak ada perubahan",
      "model": "alasanPulang",
    },
    {
      "label": "e. Merasa tidak ada harapan",
      "model": "alasanPulang",
    },
    {
      "label": "f. Ekonomi",
      "model": "alasanPulang",
    },
    {
      "label": "g. Tidak puas dengan pelayanan",
      "model": "alasanPulang",
    },
    {
      "label": "h. Lain-lain",
      "model": "alasanPulang",
    },
  ])
  
  //
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
  const d_Wali: any = ref([])
  const d_Pegawai: any = ref([])
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
  })
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
  const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        if (response.length) {
          input.value = response[0] //set ke inputan
          if (response[0].ttdPegawai) {
            H.tandaTangan().set("signaturePegawai", response[0].ttdPegawai)
          }
          if (response[0].ttdPembuatPernyataan) {
            H.tandaTangan().set("signPembuatPernyataan", response[0].ttdPembuatPernyataan)
          }
          if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
          }
        }
      })
  }
  
  const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
  
    let object: any = {}
  
    object = input.value
    object.ttdPegawai = H.tandaTangan().get("signaturePegawai")
    object.ttdPembuatPernyataan = H.tandaTangan().get("signPembuatPernyataan")
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
      `/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        NOREC_EMRPASIEN.value = response.norec_emr
        input.value.id = response.id
      }).catch((e: any) => {
        isLoading.value = false
      })
  }
  
  const fetchWali = async (filter: any) => {
  
    await useApi().get(
      `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
    ).then((response) => {
      d_Wali.value = response
    })
  }
  
  
  const fetchPegawai = async (filter: any) => {
  
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
      d_Pegawai.value = response
    })
  }
  
  const kembaliKeun = () => {
    window.history.back()
  }
  const setAutoFill = async () => {
    input.value.namaPasien = props.pasien.namapasien
    input.value.norm = props.pasien.nocm
    input.value.tglLahirPasien = props.pasien.tgllahir
  }
  
  const setTandaTanganPerawat = async (e: any) => {
      await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
        if(element){
          H.tandaTangan().set("signaturePegawai", element.ttd)
        }else{
          H.tandaTangan().set("signaturePegawai", '')
        }
      })
  }
  setView()
  setAutoFill()
  loadRiwayat()
  </script>
  
  <style lang="scss">
  .label-ppap {
    font-weight: 500;
  }
  </style>
  