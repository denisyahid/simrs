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


  <div class="column">

    <VCard>
      <div class="column is-12">
        <VCard class="p-3">
          <h1 style="font-weight: bold; margin-bottom: 1rem;"> DETAIL KUNJUNGAN PASIEN</h1>
          <div class="column is-12 px-2">
            <div class="columns is-multiline mb-5">
              <div class="column is-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Allo Anamnesis" label="Allo Anamnesis"
                      v-model="input.allow" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Auto Anamnesis" label="Auto Anamnesis"
                      v-model="input.allow" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-4">
                <VField label="Usia Saat Kunjuangan">
                  <VControl>
                    <VInput type="text" class="input" placeholder="Umur " v-model="input.umur" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Tanggal Registrasi">
                  <VDatePicker v-model="input.tanggalWaktuRegistrasi" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" readonly />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Tanggal/Jam Kunjuangan">
                  <VDatePicker v-model="input.tanggalWaktuKunjuangan" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal Waktu Kunjungan" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-6">
                <VField label="Alasan Kunjungan/keluhan Utama">
                  <VTextarea rows="2" placeholder="Alasan Kunjungan......" v-model="input.alasanKunjunagn"></VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <VField label="Riwayat Penyakit Dahulu">
                  <VTextarea rows="2" placeholder="Riwayat Penyakit Dahulu......" v-model="input.riwayatPenyakitDahulu">
                  </VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <VField label="Riwayat Alergi">
                  <VTextarea rows="2" placeholder="Riwayat Alergi......" v-model="input.riwayatAlergi"></VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <VField label="Riwayat Obat">
                  <VTextarea rows="2" placeholder="Riwayat Obat......" v-model="input.riwayatObat"></VTextarea>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-6">
                <VField label="Poli">
                  <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="RIWAYAT PSIKOSOSIAL DAN SPRITUAL" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12 P-0">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> Hubungan pasien dengan keluarga : </span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2" v-for="(data, i) in hubunganPasien">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.hubunganPasien" :true-value="data.title" :label="data.title"
                            class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-12 P-0">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> Status psikologis : </span>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2" v-for="(data, i) in statusPsikologisPasien">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.statusPsikologisPasien" :true-value="data.value" :label="data.label"
                            class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-12 P-0">
                <div class="column is-4" style="margin-top:0.5rem">
                  <span style="font-weight: bold;"> Spritual : </span>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-12 P-0">
                    <div class="is-flex" v-for="(data, i) in spritualPasien">
                      <div class="column is-4">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[spritualPasien.model]" :true-value="data.value" :label="data.title"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="... " v-model="input[data.ketValue]" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Pemeriksaan Fisik" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline p-3">
              <div class="column is-3" v-for="(data, i) in vitalSign ">
                <div class=" columns is-multiline">
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> {{ data.label }} : </span>
                  </div>
                  <div class="column is-12">
                    <VPlaceloadText :lines="1" v-if="isLoadingVitalSign" />
                    <VField addons v-else>
                      <VControl>
                        <VInput type="text" class="input" :placeholder="data.label" v-model="input[data.model]" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>{{ data.addon }} </VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12 p-3">
            <VField label="Status Gizi">
              <VTextarea rows="3" placeholder="Status Gizi......" v-model="input.statusGizi"></VTextarea>
            </VField>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Skrining Gizi" :toggleable="true">
          <h1 style="font-weight: bold;">Berdasarkan Mainutrition Screening Tooll (MST)</h1>
          <div class="column is-12">
            <div class="columns is-multiline px-5" style="border-bottom: 1px solid;">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-10">
                    <h1 class="emr">Parameter</h1>
                  </div>
                  <div class="column is-2">
                    <h1 class="emr">Nilai</h1>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline px-3">
              <div class="column is-12" v-for="(data, i) in skriningGizi">
                <span class="label-pengkajian"> {{ data.label }}</span>
                <div class="px-3">
                  <div class=" px-1" v-for="(dataDetail, i) in data.children">
                    <div class="columns is-multiline">
                      <div class="column is-8">
                        <span class="label-pengkajian"> {{ dataDetail.label }}</span>
                      </div>
                      <div class="column is-2">
                        <VField v-if="dataDetail.children == null">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[dataDetail.model]" :true-value="dataDetail.value"
                              :label="dataDetail.text" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2">
                        {{ dataDetail.value }}
                      </div>
                    </div>
                    <div class="px-1" v-if="dataDetail.children">
                      <div class="columns is-multiline px-1" v-for="(dataDetailChildren, i) in dataDetail.children">
                        <div class="column is-8">
                        </div>
                        <div class="column is-2">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[dataDetailChildren.model]" :true-value="dataDetailChildren.value"
                                :label="dataDetailChildren.label" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-2">
                          {{ dataDetailChildren.value }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-10">
                <div class="is-pulled-right">
                  <span style="font-weight: bold;font-size:15px;" class="label-pengkajian mt-3">Total
                    Skor</span>
                  <VField class="pt-3">
                    <VControl>
                      <VInput type="text" v-model="input.totalSkor" disabled />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12">
                <span class="label-pengkajian"> 3. Pasien dengan diagnosis khusus</span>
                <div class="columns is-multiline p-3">
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.adaDiagnosaKhusus" true-value="Ya" label="Ya" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.adaDiagnosaKhusus" true-value="Tidak" label="Tidak" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline px-3">
                  <div class="column is-2" v-if="input.adaDiagnosaKhusus == 'Ya'" v-for="(data, index) in diagnosaKhusus">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.label" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12" v-if="input.diagnosaKhusus == 'lain_lain'">
                    <VField label="Diagnosa Lain">
                      <VTextarea rows="2" placeholder="Diagnosa Lain......" v-model="input.diagnosaLain"></VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline px-3">
                  <div class="column is-12">
                    <p style="font-weight: bold;font-size:12px;">(Bila skor ≥ 2 dan atau pasien dengan diagnosi s/ kondisi
                      khusus
                      dilaporkan ke dokter
                      pemeriksa)</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Status Fungsional" :toggleable="true">
          <div class="columns is-multiline px-3">
            <div class="column is-3" v-for="(data, index) in statusFungsional">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.label" class="p-0"
                    color="primary" square />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Dilaporkan Pada Dokter">
                <VDatePicker v-model="input.tanggalWaktuDilaporkanPadaDokter" mode="dateTime" style="width: 100%"
                  trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Waktu pelaporan ke dokter" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Skrining Resiko Jatuh / Cidera dan Nyeri" :toggleable="true">
          <h1 style="font-weight: bold;">SKRINING RISIKO CEDERA / JATUH :</h1>
          <div class="columns is-multiline px-3 mt-5">
            <div class="column is-12" v-for="(data, index) in skriningResikoJatuh">
              <span class="label-pengkajian">{{ data.title }}</span>
              <div class="columns is-multiline mt-3 px-3">
                <div class="column is-3" v-for="(detail, index) in data.value">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[detail.model]" :true-value="detail.value" :label="detail.subTitle"
                        class="p-0" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <span class="label-pengkajian">Diberitahukan ke dokter</span>
              <div class="columns  is-flex is-multiline mt-3 px-3">
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.diberitahuanKeDokter" true-value="ya" label="Ya" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="input.diberitahuanKeDokter == 'ya'">
                  <VField label="Dilaporkan Pada Dokter">
                    <VDatePicker v-model="input.tanggalWaktuCederaDiberitahuanPadaDokter" mode="dateTime"
                      style="width: 100%" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal Waktu Kunjungan" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.diberitahuanKeDokter" true-value="tidak" label="Tidak" class="p-0"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <h1 style="font-weight: bold;">SKRINING NYERI :</h1>
          <div class="columns is-multiline px-3 mt-5">
            <div class="column is-12" v-for="(data, index) in skriningNyeri">
              <span class="label-pengkajian">{{ data.label }}</span>
              <div class="columns is-flex is-multiline mt-3 px-3">
                <div class="column is-3" v-for="(detail, index2) in data.children">
                  <VField v-if="detail.type == 'checkbox'">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[detail.model]" :true-value="detail.value" :label="detail.label"
                        class="p-0" color="primary" square />
                    </VControl>
                  </VField>
                  <div class="column" v-if="detail.type == 'date'">
                    <VField label="Dilaporkan Pada Dokter">
                      <VDatePicker v-model="input.tanggalWaktuNyeriDiberitahuanPadaDokter" mode="dateTime"
                        style="width: 100%" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal Waktu Kunjungan" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <VField v-if="detail.type == 'text'" :label="detail.label">
                    <VControl>
                      <VInput type="text" class="input" :placeholder="detail.label" v-model="input[detail.model]" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Detail keperawatan" :toggleable="true">
          <div class="column p-5">
            <span style="font-size:11pt;font-weight:bold">Daftar Masalah Keperawatan</span>
            <div class="mt-1">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VField class="cis-rounded-select_Z  is-autocomplete-select">
                    <VLabel class="required-field">Diagnosa Keperawatan</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="input.diagnosaKeperawatn" :options="d_DiagnosaKeperawatan"
                        placeholder="Pilih data..." :searchable="true" optionLabel="label" :loading="isLoadingDropdown"
                        @select="changeDiagnosaKeperawatan(input.diagnosaKeperawatn)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">

                  <VField class="cis-rounded-select_Z  is-autocomplete-select">
                    <VLabel class="required-field">Tujuan Keperawatan</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="input.tujuanKeperawatan" :options="d_TujuanKeperawatan"
                        placeholder="Pilih data..." :searchable="true" :loading="isLoadingDropdown"
                        @select="changeTujuanKeperawatan(input.tujuanKeperawatan)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField class="cis-rounded-select_Z  is-autocomplete-select">
                    <VLabel class="required-field">Intervensi Keperawatan</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="input.intervensiKeperawatan" :options="d_IntervensiKeperawatan"
                        placeholder="Pilih data..." :searchable="true" :loading="isLoadingDropdown"
                        @select="changeIntervensiKeperawatan(input.intervensiKeperawatan)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField class="cis-rounded-select_Z  is-autocomplete-select">
                    <VLabel class="required-field">Implementasi Keperawatan</VLabel>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="input.implementasiKeperawatan" :loading="isLoadingDropdown"
                        :options="d_ImplemtasiKeperawatan" placeholder="Pilih data..." :searchable="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-4" style="margin-left: auto;">
        <VCard>
          <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
          <VField>
            <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
          <div>
            <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
          </div>
          <div>
            <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.petugasPerawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Pegawai..." class="mt-2" @item-select="setTandaTangan($event)" />
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
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
import * as EMR from '../page-emr-plugins/pengkajian-awal-pj'
import { async } from '@firebase/util'
import MultiSelect from 'primevue/multiselect';




let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let hubunganPasien = ref(EMR.hubunganPasien())
let spritualPasien = ref(EMR.spritualPasien())
let statusPsikologisPasien = ref(EMR.statusPsikologisPasien())
let vitalSign = ref(EMR.vitalSign())
let skriningGizi = ref(EMR.skriningGizi())
let diagnosaKhusus = ref(EMR.diagnosaKhusus())
let statusFungsional = ref(EMR.statusFungsional())
let skriningResikoJatuh = ref(EMR.skriningResikoJatuh())
let skriningNyeri = ref(EMR.skriningNyeri())
let detailKeperawatan = ref(EMR.detailKeperawatan())

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
const d_hari = [
  { value: 'Senin', label: 'Senin' },
  { value: 'Selasa', label: 'Selasa' },
  { value: 'Rabu', label: 'Rabu' },
  { value: 'Kamis', label: 'Kamis' },
  { value: 'Jumat', label: 'Jumat' },
  { value: 'Sabtu', label: 'Sabtu' },
  { value: 'Minggu', label: 'Minggu' },
]
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isLoadingDropdown: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const d_DiagnosaKeperawatan: any = ref([])
const d_TujuanKeperawatan: any = ref([])
const d_IntervensiKeperawatan: any = ref([])
const d_ImplemtasiKeperawatan: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('pengajianAwalRawatJalan') //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
const input: any = ref({
  diagnosaKeper: [{ no: 1 }],
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0]
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].diagnosaKeperawatn) {
          changeDiagnosaKeperawatan(response[0].diagnosaKeperawatn)
          input.value.tujuanKeperawatan = response[0].tujuanKeperawatan
        }
        if (response[0].tujuanKeperawatan) {
          changeTujuanKeperawatan(response[0].tujuanKeperawatan)
          input.value.intervensiKeperawatan = response[0].intervensiKeperawatan
        }
        if (response[0].intervensiKeperawatan) {
          changeIntervensiKeperawatan(response[0].intervensiKeperawatan)
          input.value.implementasiKeperawatan = response[0].implementasiKeperawatan
        }
      }
    })
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
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

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}
// const fetchDiagnosaKeperawatan = async (filter: any) => {
//   await useApi().get(`/emr/get-dropdown-diagnosa-keperawatan?search=${filter.query}`).then((response) => {
//     d_DiagnosaKeperawatan.value = response
//   })
// }

async function fetchDiagnosaKeperawatan() {
  await useApi().get(`/emr/list-diagnosa-keperawatan`).then((response) => {
    d_DiagnosaKeperawatan.value = response.diagnosaKeperawatan.map((e: any) => {
      return { value: e.id, label: e.diagnosakep, default: e }
    })
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
async function changeDiagnosaKeperawatan(event: any) {
  let query = event == '' ? '' : event;
  isLoadingDropdown.value = true;
  await useApi().get(`/emr/list-tujuan-keperawatan?objectdiagnosakepfk=${query}`).then((response: any) => {
    d_TujuanKeperawatan.value = response.tujuanPerawat.map((e: any) => {
      return { value: e.id, label: e.tujuankep, default: e }
    })
    isLoadingDropdown.value = false;
  })
}

async function changeTujuanKeperawatan(event: any) {
  let query = event == '' ? '' : event;
  isLoadingDropdown.value = true;
  await useApi().get(`/emr/list-intervensi?objecttujuankeperawatan=${query}`).then((response: any) => {
    d_IntervensiKeperawatan.value = response.intervensi.map((e: any) => {
      return { value: e.id, label: e.name, default: e }
    })
    isLoadingDropdown.value = false;
  })
}
async function changeIntervensiKeperawatan(event: any) {
  let query = event == '' ? '' : event;
  isLoadingDropdown.value = true;
  await useApi().get(`/emr/list-implementasi?objectintervensifk=${query}`).then((response: any) => {
    d_ImplemtasiKeperawatan.value = response.implementasi.map((e: any) => {
      return { value: e.id, label: e.name, default: e }
    })
    isLoadingDropdown.value = false;
  })
}


watch(() => [
  input.value.turunBeratBadan,
  input.value.tidakAdaTurunBeratBadan,
  input.value.asupanMakan,
], () => {


  let poin1 = input.value.turunBeratBadan ? parseInt(input.value.turunBeratBadan) : 0
  let poin2 = input.value.tidakAdaTurunBeratBadan ? parseInt(input.value.tidakAdaTurunBeratBadan) : 0
  let poin3 = input.value.asupanMakan ? parseInt(input.value.asupanMakan) : 0

  const total = poin1 + poin2 + poin3
  input.value.totalSkor = total
})
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi
  input.value.tanggalWaktuKunjuangan = new Date()
  input.value.tglPembuatan = new Date()
  input.value.umur = props.pasien.umur
  input.value.namaruangan = props.registrasi.namaruangan

  isLoadingVitalSign.value = true;
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {
    if(response != null){
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.tekananDarah = response.tekananDarah
      input.value.pernapasan = response.pernapasan
      input.value.suhu = response.suhu
      input.value.nadi = response.nadi
    }
    isLoadingVitalSign.value =false;
  })
}

setView()
setAutoFill()
loadRiwayat()
fetchDiagnosaKeperawatan()
</script>

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

.label-pengkajian {
  font-weight: 400;
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

