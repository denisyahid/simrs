<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="mt-5">
        <Fieldset :toggleable="true">
          <div class="column is-12">
            <span>Hal-hal istimewa yang berhubungan dengan kondisi pasien :</span>
          </div>
          <div class="column is-8" style="margin-bottom: 20px;">
            <VField>
              <VControl>
                <VTextarea v-model="input.Diagnosa" rows="3">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <!-- ---------------------------------- -->
          <div class="columns is-multiline pl-4 pr-4" style="display: flex; align-items: center; margin-bottom: 1rem;">
            <div class="column is-4" style="flex-grow: 1;">
              <span style="display: block; width: 100%; text-align: center; margin-bottom: 0.5rem;">Diagnosis
                Keperawatan :</span>
            </div>
            <div class="column is-4" style="flex-grow: 1;">
              <span style="display: block; width: 100%; text-align: center; margin-bottom: 0.5rem;">Belum teratasi
                :</span>
            </div>
            <div class="column is-4" style="flex-grow: 1;">
              <span style="display: block; width: 100%; text-align: center; margin-bottom: 0.5rem;">Sudah teratasi
                :</span>
            </div>
          </div>

          <div class="columns is-multiline pl-4 pr-4" style="display: flex; align-items: center; margin-bottom: 1rem;">
            <div class="column is-4" style="flex-grow: 1;">
              <VField label="1 :" style="margin-bottom: 0;">
                <VControl>
                  <VInput type="text" v-model="input.DiagnosisKeperawatan1" placeholder="Diagnosis 1..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4" style="flex-grow: 1; display: flex; justify-content: center;">
              <VField style="margin-bottom: 0;">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.belumTeratasi" :true-value="true" :label="''" class="p-0" color="primary"
                    square />
                </VControl>
              </VField>
            </div>
            <div class="column is-4" style="flex-grow: 1; display: flex; justify-content: center;">
              <VField style="margin-bottom: 0;">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.sudahTeratasi" :true-value="true" :label="''" class="p-0" color="primary"
                    square />
                </VControl>
              </VField>
            </div>
          </div>
          <!-- ----------------------------------------- -->
          <div class="columns is-multiline pl-4 pr-4" style="display: flex; align-items: center; margin-bottom: 1rem;">
            <div class="column is-4" style="flex-grow: 1;">
              <VField label="2 :" style="margin-bottom: 0;">
                <VControl>
                  <VInput type="text" v-model="input.DiagnosisKeperawatan2" placeholder="Diagnosis 2..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4" style="flex-grow: 1; display: flex; justify-content: center;">
              <VField style="margin-bottom: 0;">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.belumTeratasi2" :true-value="true" :label="''" class="p-0" color="primary"
                    square />
                </VControl>
              </VField>
            </div>
            <div class="column is-4" style="flex-grow: 1; display: flex; justify-content: center;">
              <VField style="margin-bottom: 0;">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.sudahTeratasi2" :true-value="true" :label="''" class="p-0" color="primary"
                    square />
                </VControl>
              </VField>
            </div>
          </div>
          <!-- ------------------------------ -->
          <div class="columns is-multiline pl-4 pr-4" style="display: flex; align-items: center; margin-bottom: 1rem;">
            <div class="column is-4" style="flex-grow: 1;">
              <VField label="3 :" style="margin-bottom: 0;">
                <VControl>
                  <VInput type="text" v-model="input.DiagnosisKeperawatan3" placeholder="Diagnosis 3..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4" style="flex-grow: 1; display: flex; justify-content: center;">
              <VField style="margin-bottom: 0;">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.belumTeratasi3" :true-value="true" :label="''" class="p-0" color="primary"
                    square />
                </VControl>
              </VField>
            </div>
            <div class="column is-4" style="flex-grow: 1; display: flex; justify-content: center;">
              <VField style="margin-bottom: 0;">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.sudahTeratasi3" :true-value="true" :label="''" class="p-0" color="primary"
                    square />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12"
            style="background-color: gainsboro; height: 30px; width: 100%; border-top: 1px solid; display: flex; justify-content: center; align-items: center;">
            <span style="font-size: 14px;"><b>RECOMMENDATIONS</b></span>
          </div>

          <!-- <div class="column is-12">
            <span>Kilometer :</span>
          </div> -->
          <div class="column is-12">
            <div class="columns is-vcentered">
              <!-- Kolom untuk input Umur -->
              <div class="column is-5">
                <span>Konsultasi :</span>
                <VField style="margin-top: 10px;">
                  <VControl>
                    <VInput type="text" v-model="input.konsultasi1" placeholder="Konsultasi..." />
                  </VControl>
                </VField>
              </div>

              <!-- Kolom untuk input Kiri -->
              <div class="column is-6">
                <span>Rencana Pemeriksaan lab / radiologi :</span>
                <VField style="margin-top: 10px;">
                  <VControl>
                    <VInput type="text" v-model="input.labRadiologi"
                      placeholder="Rencana Pemeriksaan lab / radiologi..." />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12">
            <div class="columns is-vcentered">
              <!-- Kolom untuk input Umur -->
              <div class="column is-5">
                <span>Therapy :</span>
                <VField style="margin-top: 10px;">
                  <VControl>
                    <VInput type="text" v-model="input.Therapy" placeholder="Therapy..." />
                  </VControl>
                </VField>
              </div>

              <!-- Kolom untuk input Kiri -->
              <div class="column is-6">
                <span>Fisioterapi / Mobilisasi :</span>
                <VField style="margin-top: 10px;">
                  <VControl>
                    <VInput type="text" v-model="input.Fisioterapi_Mobilisasi"
                      placeholder="Fisioterapi / Mobilisasi..." />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12">
            <div class="columns is-vcentered">
              <!-- Kolom untuk input Umur -->
              <div class="column is-5">
                <span>Persiapan pulang :</span>
                <VField style="margin-top: 10px;">
                  <VControl>
                    <VInput type="text" v-model="input.Persiapanpulang" placeholder="Persiapan pulang..." />
                  </VControl>
                </VField>
              </div>

              <!-- Kolom untuk input Kiri -->
              <div class="column is-6">
                <span>Rencana tindakan lebih lanjut :</span>
                <VField style="margin-top: 10px;">
                  <VControl>
                    <VInput type="text" v-model="input.Rencana_tindakan_lebih_lanjut"
                      placeholder="Rencana tindakan lebih lanjut..." />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="colum is-12" style="margin-top: 10px; margin-bottom: 10px;">
            <span>Note : <b>Obat, barang dan dokumen yang disertakan</b></span>
          </div>

          <div class="column is-12">
            <div class="columns is-vcentered">
              <!-- Kolom untuk input Umur -->
              <div class="column is-5">
                <span>RMI :</span>
                <VField addons style="width: 250px; margin-top: 10px;">
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="RMI..." v-model="input.RMI" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <!-- Kolom untuk input Kiri -->
              <div class="column is-6">
                <span>ECHO :</span>
                <VField addons style="width: 250px; margin-top: 10px;">
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="ECHO..." v-model="input.ECHO" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>lembar</VButton>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <!-- ------------------ -->

          <div class="column is-12">
            <div class="columns is-vcentered">
              <!-- Kolom untuk input Umur -->
              <div class="column is-5">
                <span>Hasil lab :</span>
                <VField addons style="width: 250px; margin-top: 10px;">
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Hasil lab..." v-model="input.Hasillab" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <!-- Kolom untuk input Kiri -->
              <div class="column is-6">
                <span>MRA :</span>
                <VField addons style="width: 250px; margin-top: 10px;">
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="MRA..." v-model="input.MRA" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>lembar</VButton>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <!-- -------------------------- -->

          <div class="column is-12">
            <div class="columns is-vcentered">
              <!-- Kolom untuk input Umur -->
              <div class="column is-5">
                <span>Foto Rontgen :</span>
                <VField addons style="width: 250px; margin-top: 10px;">
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Foto Rontgen..." v-model="input.FotoRontgen" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <!-- Kolom untuk input Kiri -->
              <div class="column is-6">
                <span>Hasil USG :</span>
                <VField addons style="width: 250px; margin-top: 10px;">
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Hasil USG..." v-model="input.HasilUSG" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>lembar</VButton>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <!-- ------------------------------ -->

          <div class="column is-12">
            <div class="columns is-vcentered">
              <!-- Kolom untuk input Umur -->
              <div class="column is-5">
                <span>CT Scan :</span>
                <VField addons style="width: 250px; margin-top: 10px;">
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="CT Scan..." v-model="input.CTScan" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>lembar</VButton>
                  </VControl>
                </VField>
              </div>

              <!-- Kolom untuk input Kiri -->
              <div class="column is-6">
                <span>Hasil EKG :</span>
                <VField addons style="width: 250px; margin-top: 10px;">
                  <VControl expanded>
                    <VInput type="text" class="input" placeholder="Hasil EKG..." v-model="input.HasilEKG" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>lembar</VButton>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <!-- ---------------------------------- -->
          <div class="column is-12" style="margin-top: 10px;">
            <div class="columns is-vcentered">
              <!-- Kolom untuk input Umur -->
              <div class="column is-5">
                <span>Gigi Palsu :</span>
                <VField style="margin-top: 10px;">
                  <VControl>
                    <VInput type="text" v-model="input.GisiPalsu" placeholder="Gigi Palsu..." />
                  </VControl>
                </VField>
              </div>

              <!-- Kolom untuk input Kiri -->
              <div class="column is-6">
                <span>Kaca mata :</span>
                <VField style="margin-top: 10px;">
                  <VControl>
                    <VInput type="text" v-model="input.Kacamata" placeholder="Kaca mata..." />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <!-- --------------------------- -->

          <div class="column is-12" style="margin-top: 10px;">
            <div class="columns is-vcentered">
              <!-- Kolom untuk input Umur -->
              <div class="column is-5">
                <span>Alat bantu dengar :</span>
                <VField style="margin-top: 10px;">
                  <VControl>
                    <VInput type="text" v-model="input.alatBantudengar" placeholder="Alat bantu dengar..." />
                  </VControl>
                </VField>
              </div>

              <!-- Kolom untuk input Kiri -->
              <div class="column is-6">
                <span>Obat-obatan :</span>
                <VField style="margin-top: 10px;">
                  <VControl>
                    <VInput type="text" v-model="input.Obatobatan" placeholder="Obat-obatan..." />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12">
            <span>Perubahan kondisi selama Transport :</span>
            <div class="columns is-multiline p-3">
              <div class="column is-2" v-for="(data) in PerubahanKondisiTransport">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline p-3" v-if="input.kondisiKeluar == 'Lainnya'">
              <div class="column">
                <VField>
                  <VControl>
                    <VInput type="text" v-model="input.kondisiKeluarLainya" placeholder="Masukan Lainnya..." />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12">
            <span>Bila ya, Sebutkan perubahan yang terjadi dan penanganan yang diberikan kepada pasien :</span>
          </div>
          <div class="column is-8">
            <VField>
              <VControl>
                <VTextarea v-model="input.BilaYA" rows="3">
                </VTextarea>
              </VControl>
            </VField>
          </div>

          <div class="column is-12">
            <span>Lain-lain :</span>
          </div>
          <div class="column is-8" style="margin-bottom: 20px;">
            <VField>
              <VControl>
                <VTextarea v-model="input.Lainlain" rows="3">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </Fieldset>
      </div>

      <div class="columns is-multiline p-5">
        <div class="column is-12">
          <VCard>
            <div clas="mt-5">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4" style="text-align: center;">
                    <h1 class="p-0" style="font-weight: bold; text-align: center; margin-bottom: 5px;">Disetujui</h1>
                    <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-4" style="text-align: center;">
                    <h1 class="p-0" style="font-weight: bold; text-align: center; margin-bottom: 5px;">Mengetahui</h1>
                    <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-4" style="text-align: center;">
                    <h1 class="p-0" style="font-weight: bold; text-align: center; margin-bottom: 5px;">Diserahkan</h1>
                    <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>

                  <div class="column is-4">
                    <h1 class="p-0" style="font-weight: bold; text-align: center;">Pasien / Penanggung Jawab</h1>
                    <VField style="margin-top: 7px;">
                      <VControl>
                        <VInput type="text" v-model="input.Penanggung_Jawab"
                          placeholder="Nama Pasien / Penanggung pasien..." />
                      </VControl>
                    </VField>
                    <VField style="margin-top: 7px;" label="Contact No :">
                      <VControl>
                        <VInput type="number" v-model="input.Contact" placeholder="Contact No..." />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1 class="p-0" style="font-weight: bold; text-align: center;">Dokter yang merawat</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.DokteryangMerawat" :suggestions="d_Dokter"
                          @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Nama Dokter yang merawat..." class="mt-2" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1 class="p-0" style="font-weight: bold; text-align: center;">Parawat / Incharge</h1>
                    <VField style="margin-top: 7px;">
                      <VControl>
                        <VInput type="text" v-model="input.ParawatIncharge" placeholder="Nama  Parawat / Incharge..." />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.PerawatIncharge11" :suggestions="d_Petugas"
                          @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Nama petugas yang menyerahkan..." class="mt-2" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-12">
          <VCard>
            <div>
              <div class="columns is-multiline">
                <div class="column is-6" style="text-align: center;">
                  <h1 class="p-0" style="font-weight: bold; text-align: center; margin-bottom: 5px;">Diterima</h1>
                  <TandaTangan :elemenID="'signature_4'" :width="'180'" :height="'180'"></TandaTangan>
                </div>
                <div class="column is-6" style="text-align: center;">
                  <h1 class="p-0" style="font-weight: bold; text-align: center; margin-bottom: 5px;">Dibukukan</h1>
                  <TandaTangan :elemenID="'signature_5'" :width="'180'" :height="'180'"></TandaTangan>
                </div>
                <div class="column is-6" style="text-align: center;">
                  <h1 class="p-0" style="font-weight: bold; text-align: center;">Parawat / Incharge</h1>
                  <VField style="margin-top: 7px;">
                    <VControl>
                      <VInput type="text" v-model="input.ParawatIncharge2" placeholder="Nama  Parawat / Incharge..." />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.PerawatIncharge22" :suggestions="d_Petugas"
                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Nama petugas yang menerima..." class="mt-2" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" style="text-align: center;">
                  <h1 class="p-0" style="font-weight: bold; text-align: center;">Ward Clerk</h1>
                  <VField style="margin-top: 7px;">
                    <VControl>
                      <VInput type="text" v-model="input.WardClerk" placeholder="Ward Clerk..." />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
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
import { useUserSession } from '/@src/stores/userSession'


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

let SudahBelum = [
  {
    label: "",
    model: "sudah1",
  },
  {
    label: "",
    model: "belum1",
  }
]

let PerubahanKondisiTransport = [
  {
    label: "Tidak",
    model: "Tidak",
  },
  {
    label: "Ya",
    model: "Ya",
  }
]

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Petugas: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('CatatanPemindahanPasienAntarRS') //table mongodb
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
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].tandaTanganPasien) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPasien)
        }
        if (response[0].tandaTanganPetugas1) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPetugas1)
        }
        if (response[0].tandaTanganPetugas2) {
          H.tandaTangan().set("signature_3", response[0].tandaTanganPetugas2)
        }
        if (response[0].tandaTanganPetugas3) {
          H.tandaTangan().set("signature_4", response[0].tandaTanganPetugas3)
        }
        if (response[0].tandaTanganPetugas4) {
          H.tandaTangan().set("signature_5", response[0].tandaTanganPetugas4)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.tandaTanganPasien = H.tandaTangan().get("signature_1")
  object.tandaTanganPetugas1 = H.tandaTangan().get("signature_2")
  object.tandaTanganPetugas2 = H.tandaTangan().get("signature_3")
  object.tandaTanganPetugas3 = H.tandaTangan().get("signature_4")
  object.tandaTanganPetugas4 = H.tandaTangan().get("signature_5")
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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const setTandaTangan = async (e: any, i: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_" + i, element.ttd)
    } else {
      H.tandaTangan().set("signature_" + i, '')
    }
  })
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
table.triase {
  border-collapse: collapse;
  width: 100%;
}

table.triase,
th,
.triase td {
  border: 1px solid black;
}

table.triase,
th {
  // text-align: center;

}

.bg-green {
  background-color: var(--primary);
}

.bg-warning {
  background-color: var(--warning);
}

.bg-danger {
  background-color: var(--danger);
}

.triase th,
td {
  padding: 8px;
  vertical-align: top !important;
}
</style>
