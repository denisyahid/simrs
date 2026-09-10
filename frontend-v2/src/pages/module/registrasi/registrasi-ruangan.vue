<template>
  <ConfirmDialog />
  <div class="form-layout is-stacked">

    <div class="business-dashboard hr-dashboard">
      <div class="columns is-multiline">
        <div class="column is-12" v-if="isLoadingPasien">
          <div class="block-green">
            <div class="flex-list-inner mb-4">
              <div class="flex-table-item grid-item mb-4" v-for="key in 1" :key="key">
                <VFlexTableCell :column="{ grow: true, media: true }">
                  <VPlaceloadAvatar size="medium" />
                  <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                </VFlexTableCell>
                <VFlexTableCell :column="{ align: 'end' }">
                  <VPlaceload width="10%" class="mx-1" />
                </VFlexTableCell>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12" v-if="!isLoadingPasien">
          <div class="block-header">
            <div class="left">
              <div class="current-user">
                <VAvatar size="medium" :picture="pasien.jeniskelamin ==
                  'PEREMPUAN' ? '/images/avatars/svg/vuero-4.svg' : '/images/avatars/svg/vuero-1.svg'" squared />
                <h3>{{ pasien.namapasien }}</h3>
              </div>
            </div>
            <div class="center">
              <div class="columns">
                <div class="column">
                  <h4 class="block-heading">No RM</h4>
                  <p class="block-text"> {{ pasien.nocm }}</p>
                  <h4 class="block-heading">Tgl Lahir </h4>
                  <p class="block-text"> {{ pasien.tgllahir }}</p>
                </div>
                <div class="column">
                  <h4 class="block-heading">NIK </h4>
                  <p class="block-text"> {{ pasien.noidentitas }}</p>
                  <h4 class="block-heading">Kelamin</h4>
                  <p class="block-text"> {{ pasien.jeniskelamin }}</p>
                </div>
              </div>
            </div>
            <div class="right">
              <div class="columns">
                <div class="column">
                  <h4 class="block-heading">No HP</h4>
                  <p class="block-text">{{ pasien.nohp }}</p>
                  <h4 class="block-heading">Alamat</h4>
                  <p class="block-text">{{ pasien.alamatlengkap }}
                  </p>
                </div>
                <div class="column">
                  <h4 class="block-heading">Umur</h4>
                  <VTag color="purple" :label="pasien.umur" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- <div class="column is-12" >
                     <InfoPasien></InfoPasien>
                </div> -->
      </div>
    </div>

    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ NOREC_PD != undefined ? 'Edit Registrasi' : 'Registrasi' }}</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <template v-if="NOREC_PD != undefined || item.NOREC_PD != undefined">
                <VButton color="info" icon="lnil lnil-printer rem-100" light rounded outlined :loading="isLoadingCetak"
                  @click="cetakSEP(item)">
                  Cetak SEP
                </VButton>
                <VButton color="info" icon="lnil lnil-printer rem-100" light rounded outlined :loading="isLoadingCetak"
                  @click="cetakBuktiPendaftaran(item)">
                  Cetak Antrian
                </VButton>
                <VButton color="info" icon="lnil lnil-printer rem-100" light rounded outlined :loading="isLoadingCetak"
                  @click="cetakLabelPrev(item)">
                  Cetak Label
                </VButton>
                <VButton color="info" icon="lnil lnil-printer rem-100" light rounded outlined :loading="isLoadingCetak"
                  @click="cetakkartuPasien(item)">
                  Cetak Kartu
                </VButton>
                <VButton color="info" icon="lnil lnil-printer rem-100" light rounded outlined :loading="isLoadingCetak"
                  @click="cetakIdentitasPasien(item)">
                  Cetak Identitas
                </VButton>
              </template>
              <VButton v-if="item.NOREC_PD && item.kelompokpasien != item.idKelompokPasienUMUM"
                icon="lnir lnir-plus rem-100" light color="info" outlined @click="asuransi()">
                Asuransi
              </VButton>
              <RouterLink :to="{ name: 'module-registrasi-pasien-lama', }" v-if="item.NOREC_PD">
                <VIconButton class="mr-5 is-pulled-right" type="button" color="info" rounded circle raised
                  icon="fas fa-users" v-tooltip.bubble="'Pasien Lama'">
                </VIconButton>
              </RouterLink>
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="cancelRegistrasi()">
                Batal
              </VButton>
              <VButton type="button" color="primary" rounded outlined raised icon="feather:save" :disabled="isDisabled"
                :loading="isLoading" @click="checkIsExsist()"> Simpan </VButton>
            </div>
          </div>
        </div>
      </div>

      <div class="form-body">

        <div class="form-section is-grey">
          <div class="form-section-header">
            <div class="left">
              <!-- <h3>Detail</h3> -->
            </div>
            <div class="right">

            </div>
          </div>

          <div class="form-section-inner is-horizontal">
            <VField horizontal label=" Tanggal">
              <VDatePicker v-model="item.tglregistrasi" mode="dateTime" style="width: 100%;">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>

            <VField horizontal label="&nbsp;">
              <VControl>
                <VSwitchBlock v-model="item.isRawatInap" label="Rawat Inap" color="danger"
                  @change="changeSwitch(item.isRawatInap)" />
              </VControl>
              <VControl>
                <VRadio v-model="item.isRawatGabung" :value="true" label="Rawat Gabung" name="isRawatGabung" square
                  color="primary" v-if="item.isRawatInap"
                  style="margin-top: 5px; margin-left: 30px; font-size: 0.9rem;" />
                <VRadio v-model="item.isKelasTitip" :value="true" label="Kelas Titip" name="isKelasTitip" square
                  color="primary" v-if="item.isRawatInap"
                  style="margin-top: 5px; margin-left: 30px; font-size: 0.9rem;" />
                <VRadio v-model="item.isNaikKelas" :value="true" label="Naik Kelas" name="isNaikKelas" square
                  color="primary" v-if="item.isRawatInap"
                  style="margin-top: 5px; margin-left: 30px; font-size: 0.9rem;" />
              </VControl>
            </VField>
            <VField horizontal label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:home" fullwidth>
                <Multiselect mode="single" v-model="item.ruangan" :options="d_Ruangan" placeholder="Pilih data"
                  :searchable="true" :attrs="{ id }" autocomplete="off" @select="changeRuang(item.ruangan)" />
              </VControl>
            </VField>
            <VField v-if="item.isRawatInap && item.ruangan" horizontal label="Kelas Kamar"
              class="is-rounded-select_Z  is-autocomplete-select">
              <VField v-slot="{ id }">
                <VControl icon="feather:layers" fullwidth>
                  <Multiselect mode="single" v-model="item.kelas" :options="d_Kelas" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT"
                    @select="changeKelas(item.kelas)" />
                </VControl>
              </VField>
              <VField v-slot="{ id }" v-if="item.kelas" horizontal label="Kelas Ditanggung/Rawat">
                <VControl icon="feather:layers" fullwidth>
                  <Multiselect mode="single" v-model="item.kelasRawat" :options="d_KelasDefault"
                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                    :loading="isLoadingTT" />
                </VControl>
              </VField>
            </VField>
            <VField v-if="item.isRawatInap && item.kelas && item.ruangan" horizontal label="Kamar"
              class="is-rounded-select_Z  is-autocomplete-select">
              <VField v-slot="{ id }">
                <VControl icon="fas fa-hospital-alt">
                  <Multiselect mode="single" v-model="item.kamar" :options="d_Kamar" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT"
                    @select="changeKamar(item.kamar)" />
                </VControl>
              </VField>
              <VField v-slot="{ id }" subcontrol v-if="item.kamar && item.ruangan">
                <VControl icon="fas fa-bed">
                  <Multiselect mode="single" v-model="item.bed" :options="d_TempatTidur" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT" />
                </VControl>
              </VField>
            </VField>

            <div class="columns is-mulitline p-3">
              <div class="column is-2 pt-2 pb-0 pr-1">
                <div class="pt-1" style="display: flex;justify-content: end;">
                  <label
                    style="font-family: var(--font);font-size: 0.9rem;color: var(--light-text) !important;font-weight: 400;">Asal
                    Rujukan</label>
                </div>

              </div>
              <div class="column ml-2 pt-1 pb-0 pr-0"
                v-if="item.asalrujukan != 5 || item.asalrujukan != null ? 'is-10' : 'is-4 pr-2'">
                <VField horizontal class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }" required-field>
                  <VControl icon="feather:git-merge" fullwidth>
                    <Multiselect mode="single" v-model="item.asalrujukan" :options="d_AsalRujukan"
                      placeholder="Pilih data" :searchable="true" autocomplete="off" :attrs="{ id }" track-by="value" />
                  </VControl>
                </VField>
              </div>

              <div class="column ml-2 p-0 mt-1" v-if="item.asalrujukan != 5">
                <VField>
                  <VControl icon="feather:bookmark">
                    <VInput type="text" v-model="item.keteranganasalrujukan" placeholder="Asal Rujukan lebih jelas"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </div>

              <!-- <div class="column p-0">
                                <VField horizontal label="Asal Rujukan" class="is-rounded-select_Z  is-autocomplete-select"
                                    v-slot="{ id }" required-field>
                                    <VControl icon="feather:git-merge" fullwidth>
                                        <Multiselect mode="single" v-model="item.asalrujukan" :options="d_AsalRujukan"
                                            placeholder="Pilih data" :searchable="true" autocomplete="off" :attrs="{ id }"
                                            track-by="value" />
                                    </VControl>
                                </VField>
                            </div> -->
            </div>
            <VField horizontal label="Kebangsaan" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="fas fa-calculator" fullwidth>
                <Multiselect mode="single" v-model="item.kebangsaan" :options="d_Kebangsaan" placeholder="Pilih data"
                  :searchable="true" :attrs="{ id }" autocomplete="off" />
              </VControl>
            </VField>
            <VField horizontal label="Pembiayaan" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="fas fa-calculator" fullwidth>
                <Multiselect mode="single" v-model="item.kelompokpasien" :options="d_KelompokPasien"
                  placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                  @select="changeKelompok(item.kelompokpasien)" />
              </VControl>
            </VField>
            <VField v-if="item.kelompokpasien" horizontal label="Penjamin"
              class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:command" fullwidth>
                <Multiselect mode="single" v-model="item.rekanan" :options="d_Rekanan" placeholder="Pilih data"
                  :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT" />
              </VControl>
            </VField>
            <VField horizontal label="Tipe Layanan" style="display: none !important">
              <VControl>
                <VRadio v-model="item.jenispelayanan" v-for="items of d_JenisPelayanan" :key="items.id"
                  :value="items.id" :label="items.jenispelayanan" name="{{items.id}}" color="primary" />
                <!-- <VRadio v-model="item.jenispelayanan" value="Eksekutif" label="Eksekutif" color="primary" /> -->
              </VControl>

            </VField>

            <VField horizontal label="Dokter" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                <!-- <Multiselect mode="single" v-model="item.dokter" placeholder="Pilih data" :searchable="true"
                                    :filter-results="false" :min-chars="0" :resolve-on-load="false" :delay="0" :options="async function (query: any) {
                                                                                                    return await fetchDokter(query)
                                                                                                }" autocomplete="off" /> -->
                <AutoComplete v-model="item.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'namalengkap'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'namalengkap'" placeholder="ketik nama Dokter" />


              </VControl>
            </VField>
            <!-- <VField v-if="item.isRawatInap" horizontal label="Dokter Rawat Bersama"
                            class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                <AutoComplete v-model="item.dokterRawatBersama" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'namalengkap'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namalengkap'"
                                    placeholder="ketik nama Dokter" />


                            </VControl>
                        </VField> -->
            <VField horizontal label="Catatan">
              <VControl fullwidth>
                <VTextarea class="textarea" v-model="item.catatan" rows="4"
                  placeholder="catatan registrasi (optional) ..." autocomplete="off" autocapitalize="off"
                  spellcheck="true" />

              </VControl>
            </VField>
          </div>

          <div class="form-section-inner is-horizontal mt-5">
            <div class="columns is-multiline mt-5">
              <div class="column is-2"></div>
              <div class="column is-10 mt-5">
                <div class="fieldset-heading text-center">
                  <h2 class="required-field"><b>Penanggung Jawab Pasien</b></h2>
                  <i style="color: red; font-size: 10pt;"></i>
                </div>

                <div class="columns is-multiline" style="margin-top: 20px;">
                  <div class="column is-12">
                    <VField>
                      <VLabel class="required-field">Nama Penanggung Jawab</VLabel>
                      <VControl icon="feather:bookmark">
                        <VInput type="text" v-model="item.penanggungJawabP" placeholder="" class="is-rounded_Z"
                          required />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6" style="display: none !important">
                    <VField>
                      <VLabel>No RM Penanggung Jawab</VLabel>
                      <VControl icon="feather:search">
                        <VInput type="text" v-model="item.nocmpj" v-on:keyup.enter="fetchPasien(item.nocmpj)"
                          placeholder="No MR" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select" v-slot="{ id }">
                      <VLabel class="required-field">Hubungan Dengan Pasien</VLabel>
                      <VControl icon="feather:search">
                        <Multiselect mode="single" v-model="item.hubunganP" :options="d_HubunganPasien"
                          autocomplete="off" placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                          track-by="value" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <div class="is-flex flex-col">
                      <p class="required-field" style="text-align: left;">No Telepon</p>

                      <!-- <VCheckbox v-model="item.nohppsama" class="ml-auto" true-value="Ya" label="Sama" color="primary" circle style="padding: 0px;"/> -->
                    </div>
                    <VField>
                      <VControl icon="feather:phone">
                        <VInput type="text" v-model="item.telponP" placeholder="" class="is-rounded_Z" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VLabel>Bahasa Sehari-hari</VLabel>
                      <VControl icon="feather:bookmark">
                        <VInput type="text" v-model="item.bahasaP" placeholder="" class="is-rounded_Z" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VLabel class="">Umur</VLabel>
                      <VControl icon="feather:bookmark">
                        <VInput type="text" v-model="item.umurP" placeholder="" class="is-rounded_Z" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                      <VLabel class="" style="text-align: left;">Pekerjaan</VLabel>
                      <VControl icon="feather:search">
                        <Multiselect mode="single" v-model="item.pekerjaanP" :options="d_Pekerjaan"
                          placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <div class="is-flex flex-col">
                      <p class="required-field" style="text-align: left;">Alamat Penanggung Jawab</p>
                      <VCheckbox v-model="item.alamatpsama" class="ml-auto" true-value="Ya" label="Sama" color="primary"
                        circle style="padding: 0px;" />
                    </div>
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.alamatP" rows="4" placeholder="Alamat Lengkap">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-section is-grey">
          <div class="form-section-inner is-horizontal">


          </div>
        </div>
      </div>
    </div>
  </div>

  <VModal :open="modalJumlahLabel" title="Jumlah Label Dicetak" :noclose="false" size="small" actions="right"
    @close="modalJumlahLabel = false">
    <template #content>
      <form class="modal-form">
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField>
                <VLabel>Jumlah Label</VLabel>
                <VControl>
                  <VInput type="text" v-model="item.jumlahLabel" class="is-rounded_Z" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="cetakLabel(selectedPasien)" :loading="isLoading" color="primary" raised>
        Cetak</VButton>
    </template>
  </VModal>

  <!-- <VModal :open="modalAsuransi" title="Surat Eligibilitas Peserta" :noclose="false" size="big" actions="right"
    @close="modalAsuransi = false">
    <template #content>
        <PemakaianAsuransi></PemakaianAsuransi>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="saveTglPelayanan()" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
                                                                                        </template>
                                                                                      </VModal> -->
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import InfoPasien from '/@src/pages/include/info-pasien.vue';
import * as qzService from '/@src/utils/qzTrayService'
import moment from 'moment'

useHead({
  title: 'Registrasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let isEdit = (useRoute().query.edit as string) === 'true';
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
let NOREC_RESERVASI = useRoute().query.norec_online as string
let STATUSPASIEN = useRoute().query.statuspasien as string
let NOREGISTRASI = "";

let RESERVASI = {
  'norec_online': useRoute().query.norec_online as string,
  'ruangan': useRoute().query.ruangan as string,
  'dokter': useRoute().query.dokter as string,
  'dokter_name': useRoute().query.dokter_name as string,
  'kelompok': useRoute().query.kelompok as string,
  'tanggalreservasi': useRoute().query.tanggalreservasi as string,
}
const item: any = reactive({
  tglregistrasi: new Date(),
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
  NOREGISTRASI: NOREGISTRASI != undefined ? NOREGISTRASI : '',
  idKelompokPasienBPJS: [],
  idKelompokPasienUMUM: null,
  isRawatGabung: false
})
const confirm = useConfirm();
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const d_RuanganRI: any = ref([])
const d_RuanganRJ: any = ref([])
const d_AsalRujukan: any = ref([])
const d_KelompokPasien: any = ref([])
const d_Kebangsaan: any = ref([])
const d_JenisPelayanan: any = ref([])
const d_Rekanan: any = ref([])
const d_Dokter: any = ref([])
const d_Kelas: any = ref([])
const d_KelasDefault: any = ref([])
const d_Kamar: any = ref([])
const d_TempatTidur: any = ref([])
let d_Pekerjaan: any = ref([])
let d_HubunganPasien: any = ref([])
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const isLoadingTT: any = ref(false)
const isLoadingPasien: any = ref(false)
const isShowPenanggung: any = ref(false)
const modalAsuransi: any = ref(false)
let modalJumlahLabel: any = ref(false)
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoadingCetak = ref(false);
const isPICcompleted = ref(true);

const pasienByID = (id: any) => {
  let paramsEdit = ''
  isLoadingPasien.value = true
  if (item.NOREC_PD != '' && item.NOREC_APD != '') {
    paramsEdit = `&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`
  }

  useApi().get(`/registrasi/pasien-registrasi-rev?id=${id}${paramsEdit}`).then((response: any) => {
    let ps = response.pasien ? response.pasien : null;
    let reg = response.registrasi ? response.registrasi : null;
    pasien.value = response.pasien

    //? Autofill
    item.penanggungJawabP = ps.penanggungjawab != null ? ps.penanggungjawab : ps.namapasien;
    item.telponP = ps.telponpenanggungjawab != null ? ps.telponpenanggungjawab : ps.nohp;
    item.hubunganP = ps.hubungankeluargapj != null ? ps.hubungankeluargapj : 1;
    item.alamatP = ps.alamatrmh != null ? ps.alamatrmh : ps.alamatlengkap;
    item.nocmpj = ps.nocmpj
    item.bahasaP = ps.bahasa
    item.jenisKelP = ps.jeniskelaminpenanggungjawab
    item.umurP = ps.umurpenanggungjawab
    item.pekerjaanP = ps.pekerjaanpenangggungjawab
    item.kebangsaan = ps.objectkebangsaanfk

    if (!reg) {
      d_Ruangan.value = d_RuanganRJ.value
      if (NOREC_RESERVASI != undefined) { setFromReservasi(RESERVASI) }
    } else {
      if (ID_PASIEN == reg.nocmfk) { setFromRegistrasi(reg) }
      item.NOREGISTRASI = reg.noregistrasi
    }

  }).finally(() => {
    isLoadingPasien.value = false
  })
}
const setFromReservasi = (e: any) => {
  item.tglregistrasi = new Date(e.tanggalreservasi)
  item.ruangan = e.ruangan
  item.kelompokpasien = e.kelompok
  item.noreservasi = e.noreservasi
  changeKelompok(item.kelompokpasien)
  item.dokter = { id: e.dokter, namalengkap: e.dokter_name }
}
const setFromRegistrasi = async (regis: any) => {
  if (regis.objectdepartemenfk == 16) {
    d_Ruangan.value = d_RuanganRI.value
    item.isRawatInap = true
  } else {
    d_Ruangan.value = d_RuanganRJ.value
  }

  //? Autofill
  item.tglregistrasi = new Date(regis.tglregistrasi)
  item.ruangan = regis.objectruanganlastfk
  await changeRuang(item.ruangan)
  item.kelas = regis.objectkelasfk
  await changeKelas(item.kelas)
  item.kelasRawat = regis.kelasrawatfk
  await setKamar(regis)
  item.kamar = regis.objectkamarfk
  await setTempatTidur(regis)
  item.bed = regis.nobed
  item.asalrujukan = regis.asalrujukanfk
  item.kelompokpasien = regis.objectkelompokpasienlastfk
  await changeKelompok(item.kelompokpasien)
  item.rekanan = regis.objectrekananfk
  item.jenispelayanan = regis.jenispelayananfk
  item.catatan = regis.catatan
  item.statuspasien = regis.statuspasien

  if (regis.objectpegawaifk) {
    item.dokter = { id: regis.objectpegawaifk, namalengkap: regis.dokter }
  }
}
const changeSwitch = (e: any) => {
  delete item.ruangan
  d_Ruangan.value = []
  if (e == true) {
    d_Ruangan.value = d_RuanganRI.value
  } else {
    d_Ruangan.value = d_RuanganRJ.value
  }
}
const changeRuang = (e: any) => {
  item.kelasRawat = null
  item.kelas = null
  item.kdruanganbpjs = null;
  d_Ruangan.value.forEach((element: any) => {
    if (element.default.id == e) {
      item.kdruanganbpjs = element.default.kdsubspesialisbpjs;
      if (element.default.namaruangan.indexOf('VIP') > -1 && element.default.objectdepartemenfk != 16) {
        item.jenispelayanan = 1
        item.kelasRawat = 6
      } else {
        item.jenispelayanan = 1
      }
    }
  });
  if (e) {
    if (item.isRawatInap == true) {
      setKelas(e)
    }
  }
}
const setKelas = (e: any) => {
  isLoadingTT.value = true
  d_Kelas.value = []
  useApi().get(`/registrasi/kelas-by-ruangan?id=${e}`).then((response: any) => {
    isLoadingTT.value = false
    if (response.length == 1) {
      item.kelasRawat = response[0].id
    }
    d_Kelas.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
  }).catch((error: any) => {
    isLoadingTT.value = false
  })
}
const changeKelas = async (e: any) => {
  d_Kamar.value = []
  delete item.kamar
  if (e && item.ruangan) {
    isLoadingTT.value = true
    await useApi().get(`/registrasi/kamar-by-kelas?id=${e}&idRuangan=${item.ruangan}&isRG=${item.isRawatGabung}`).then((response: any) => {
      isLoadingTT.value = false
      d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
    }).catch((error: any) => {
      isLoadingTT.value = false
    })
  }

}
const changeKamar = async (e: any) => {
  d_TempatTidur.value = []
  if (e) {
    for (let x = 0; x < d_Kamar.value.length; x++) {
      const element = d_Kamar.value[x];
      if (element.value == e) {
        d_TempatTidur.value = await element.default.details.map((e: any) => { return { label: e.reportdisplay, value: e.id, default: e } })
      }
    }
  }
}

const cancelRegistrasi = () => {
  window.history.back()
}

const changeKelompok = async (e: any) => {
  d_Rekanan.value = []
  for (let x = 0; x < item.idKelompokPasienBPJS.length; x++) {
    const element = item.idKelompokPasienBPJS[x];
    if (e == element) {
      if (pasien.value.nobpjs == null || pasien.value.nobpjs == '') {
        H.alert('warning', 'NO BPJS anda masih kosong, harap lengkapi data')
      } else if (pasien.value.nobpjs.length != 13) {
        H.alert('warning', 'NO BPJS anda tidak sesuai, harap sesuaikan data ')
      }
    }
  }

  delete item.rekanan
  if (e) {
    isLoadingTT.value = true
    await useApi().get(`/registrasi/penjamin-by-kelompokpasien?id=${e}`).then((response: any) => {
      isLoadingTT.value = false
      if (response.length > 0) {
        d_Rekanan.value = response.map((e: any) => { return { label: e.namarekanan, value: e.id, default: e } })
        if (response.length == 1) {
          item.rekanan = response[0].id
        } else {
          if (item.idKelompokPasienBPJS == e) {
            for (let z = 0; z < d_Rekanan.value.length; z++) {
              const element = d_Rekanan.value[z];
              if (element.label.toLowerCase().indexOf('kesehatan') > -1) {
                item.rekanan = element.value
                break
              }
            }
          }
        }
      }
    }).catch((error: any) => {
      isLoadingTT.value = false
    })
  }
}

const checkIsExsist = async () => {
  isLoading.value = true
  if (!item.NOREC_PD) {
    console.log('Ruangan', item.ruangan)
    if (item.ruangan != 322 && item.ruangan != 323 && item.ruangan != 288 && item.ruangan != 217 && item.ruangan != 330) {
      console.log('Halo masuk penjagaan')
      let respon = await useApi().get(`registrasi/pasien-hari-ini?nocmfk=${ID_PASIEN}`)
      if (respon != null) {
        console.log(respon)
        if (respon.id == item.ruangan && respon.tglclosing == null) {
          H.alert('warning', `Pasien Sudah teregistrasi hari ini di ${respon.namaruangan}`);
          isLoading.value = false
          return
        } else {
          if (item.kelompokpasien == 2 && respon.objectkelompokpasienlastfk == 2) {
            H.alert('warning', `Pasien Sudah teregistrasi hari ini di ${respon.namaruangan}`);
            isLoading.value = false
            return
          } else {
            if (respon.objectkelompokpasienlastfk == 1) {
              if (respon.tglclosing == null) {
                H.alert('warning', `Pasien Sudah teregistrasi umum hari ini di ${respon.namaruangan} dan belum closing`);
              } else {
                await saveRegistrasi()
                isLoading.value = false
              }
            } else {
              await saveRegistrasi()
              isLoading.value = false
            }
          }
        }
      } else {
        await saveRegistrasi()
        isLoading.value = false
      }

      // if (respon != null && item.kelompokpasien == 2) {
      //   H.alert('warning', `Pasien Sudah teregistrasi hari ini di ${respon.namaruangan}`); 
      //   isLoading.value = false
      //   return
      // } else if (respon != null && item.kelompokpasien == 1 && ) {
      //   H.alert('warning', `Pasien Sudah teregistrasi hari ini di ${respon.namaruangan}`); 
      //   isLoading.value = false
      //   return
      // } else {
      //   await saveRegistrasi()
      //   isLoading.value = false
      // }
    } else {
      let respon = await useApi().get(`registrasi/pasien-hari-ini?nocmfk=${ID_PASIEN}`)
      if (respon != null) {
        if (respon.objectkelompokpasienlastfk == 1) {
          if (respon.tglclosing == null) {
            H.alert('warning', `Pasien Sudah teregistrasi UMUM hari ini di ${respon.namaruangan} dan belum closing`);
          } else {
            await saveRegistrasi()
            isLoading.value = false
          }
        } else {
          await saveRegistrasi()
          isLoading.value = false
        }
      } else {
        await saveRegistrasi()
        isLoading.value = false
      }


    }
  } else {
    isLoading.value = false
    await saveRegistrasi()
  }
}

const saveRegistrasi = async () => {
  //? Validasi
  if (!item.penanggungJawabP ||
    !item.telponP ||
    !item.hubunganP ||
    !item.alamatP) {
    H.alert('warning', 'Penanggung Jawab Pasien Belum Lengkap!');
    isShowPenanggung.value = true;
    return
  }
  if (!item.tglregistrasi) { H.alert('warning', 'Tgl Registrasi harus di isi'); return }
  if (!item.dokter && item.ruangan.objectdepartemenfk != 9) { H.alert('warning', 'Dokter Wajib Diisi !!'); return }
  if (!item.ruangan) { H.alert('warning', 'Ruangan harus di isi'); return }
  if (!item.kebangsaan) { H.alert('warning', 'Kebangsaan harus di isi'); return }
  if (!item.asalrujukan) { H.alert('warning', 'Asal Rujukan  harus di isi'); return }
  if (!item.kelompokpasien) { H.alert('warning', 'Pembiayaan harus di isi'); return }
  if (!item.jenispelayanan) { H.alert('warning', 'Tipe Layanan harus di isi'); return }
  if (item.isRawatInap == true) {
    if (!item.kelas) { H.alert('warning', 'Kelas Dirawat harus di isi'); return }
    if (!item.kelasRawat) { H.alert('warning', 'Kelas Ditanggung harus di isi'); return }
    if (!item.kamar) { H.alert('warning', 'Kamar harus di isi'); return }
    if (!item.bed && item.isRawatGabung == false) { H.alert('warning', 'Bed harus di isi'); return }
  }

  isLoading.value = true;
  let jsonPasien = {
    'pasien': {
      'id': ID_PASIEN ? ID_PASIEN : '',
      'penanggungjawab': item.penanggungJawabP != undefined ? item.penanggungJawabP : null,
      'nocmpj': item.nocmpj != undefined ? item.nocmpj : null,
      'hubungankeluargapj': item.hubunganP != undefined ? item.hubunganP : null,
      'telponpenanggungjawab': item.telponP != undefined ? item.telponP : null,
      'bahasa': item.bahasaP != undefined ? item.bahasaP : null,
      'objectkebangsaanfk': item.kebangsaan != undefined ? item.kebangsaan : null,
      'jeniskelaminpenanggungjawab': item.jenisKelP != undefined ? item.jenisKelP : null,
      // 'umurpenanggungjawab': item.umurP != undefined ? item.umurP : null,
      'pekerjaanpenangggungjawab': item.pekerjaanP != undefined ? item.pekerjaanP : null,
      'alamatrmh': item.alamatP != undefined ? item.alamatP : null,
    }
  }

  await useApi().post(`/registrasi/save-pasien-pj`, jsonPasien).then(async (rePasien: any) => {
    isLoading.value = false
    ID_PASIEN = rePasien.data.id
  }).catch((e: any) => {
    isLoading.value = false
    console.clear()
    console.log(e)
  })

  let json = {
    'pasiendaftar': {
      'norec': item.NOREC_PD ? item.NOREC_PD : '',
      'nocmfk': ID_PASIEN,
      'tglregistrasi': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
      'objectruanganlastfk': item.ruangan,
      'objectruangantujuanfk': item.ruangan,
      'asalrujukanfk': item.asalrujukan,
      'keteranganasalrujukan': item.keteranganasalrujukan ? item.keteranganasalrujukan : null,
      'objectkelompokpasienlastfk': item.kelompokpasien,
      'jenispelayananfk': item.jenispelayanan,
      'objectpegawaifk': item.dokter ? item.dokter.id : null,
      'objectpegawairawatbersamafk': item.dokterRawatBersama ? item.dokterRawatBersama.id : null,
      'objectkelasfk': item.isRawatInap ? item.kelas : (item.kelasRawat ? item.kelasRawat : null),
      'objectkelasrawatfk': item.isRawatInap ? item.kelasRawat : (item.kelas ? item.kelas : null),
      'israwatinap': item.isRawatInap ? item.isRawatInap : false,
      'catatan': item.catatan ? item.catatan : null,
      'statuspasien': STATUSPASIEN ? STATUSPASIEN : 'LAMA',
      'objectrekananfk': item.rekanan != undefined ? item.rekanan : null,
      'nocm': pasien.value.nocm,
      'namapasien': pasien.value.namapasien,
      'antrianpasienregistrasifk': NOREC_RESERVASI ? NOREC_RESERVASI : null,
      'iskelastitip': item.isKelasTitip ? item.isKelasTitip : null,
      'isnaikkelas': item.isNaikKelas ? item.isNaikKelas : null,
    },
    'antrianpasiendiperiksa': {
      'norec': item.NOREC_APD ? item.NOREC_APD : '',
      'objectkamarfk': item.kamar ? item.kamar : null,
      'objectkelasfk': item.isRawatInap ? item.kelas : (item.kelasRawat ? item.kelasRawat : null),
      'objectkelasrawatfk': item.isRawatInap ? item.kelasRawat : (item.kelas ? item.kelas : null),
      'objectbedfk': item.bed ? item.bed : null,
      'objectpegawaifk': item.dokter ? item.dokter.id : null,
      'nobed': item.bed ? item.bed : null,
      'israwatgabung': item.isRawatGabung ? item.isRawatGabung : null,
      'tglregistrasi': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
    }
  }

  let url = '/registrasi/save-registrasi';
  if (isEdit && item.isRawatInap) {
    url = '/registrasi/edit-mutasi';
  } else if (item.isRawatInap) {
    url = '/registrasi/save-mutasi';
  }

  isLoading.value = true
  await useApi().post(url, json).then(async (response: any) => {
    isLoading.value = false
    isDisabled.value = true
    if (!item.NOREC_PD) {
      saveAdminAuto(response.registrasi.pd.norec, response.registrasi.apd.norec, pasien.value.objectkebangsaanfk)
    }
    // if( item.isRawatInap == true){
    //   let jsonKamar = {idruangan : item.ruangan, idkelas : item.kelasRawat}
    //   useApi().postNoMessage(`/bridging/bpjs/update-kamar`, jsonKamar).then((response: any) => {})
    // }
    item.NOREC_PD = response.registrasi.pd.norec
    item.NOREC_APD = response.registrasi.apd.norec
    item.NOREGISTRASI = response.registrasi.apd.noregistrasi
    if (item.kelompokpasien != 2) {
      sendAntrol();
    }
    for (let xx = 0; xx < item.idKelompokPasienBPJS.length; xx++) {
      const element = item.idKelompokPasienBPJS[xx];
      if (element == item.kelompokpasien) {
        tambahAsuransi(item.NOREC_PD)
      }
    }
  }).catch((e: any) => {
    isLoading.value = false
    if (e.message == 'Bed Sudah Terisi, Silakan Pilih Bed Lain') {
      changeKelas(item.kelas)
    }
    console.clear()
    console.log(e)
  })
  isLoading.value = false
}

const sendAntrol = async () => {
  let status = false;
  let error = '';
  let kodeDokterBPJS;
  let jeniskunjungan = 1;
  let nomorreferensi = '';
  let kdpoli = item.kdruanganbpjs;
  try {

    const jsonJadwalDokter = {
      "url": `jadwaldokter/kodepoli/${kdpoli}/tanggal/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
      "jenis": "antrean",
      "method": "GET",
      "data": null
    }
    const res = await useApi().postBPJS(`/bridging/bpjs/tools`, jsonJadwalDokter)
    if (res.metaData.code == 200) {
      for (var i = res.response.length - 1; i >= 0; i--) {
        const element = res.response[i]
        const dokternya = item.dokter ? item.dokter.kddokterbpjs : ""
        if (element.kodedokter == dokternya) {
          kodeDokterBPJS = {
            "jadwal": element.jadwal,
            "namadokter": element.namadokter,
            "kodedokter": element.kodedokter,
          }
          break;
        }
      }
    }
    // End Penentuan Jenis Kunjungan

    const jsonAntrol = {
      "noregistrasifk": item.NOREC_PD,
      "kodedokter": kodeDokterBPJS ? kodeDokterBPJS.kodedokter : "",
      "namadokter": kodeDokterBPJS ? kodeDokterBPJS.namadokter : "",
      "jampraktek": kodeDokterBPJS ? kodeDokterBPJS.jadwal : "",
      "jeniskunjungan": 1,
      "nomorreferensi": '',
    }
    await useApi().postNoMessage(`/bridging/antrol/sendDataAntrean`, jsonAntrol).then(async (response: any) => {
      console.log('JSON ANTROL', response);
      if (response.metadata.code == 200) {
        status = true
        // send taks id 1
        const jsont1 = {
          "noregistrasifk": item.NOREC_PD,
          "taskid": 1,
          "waktu": new Date().getTime(),
        }
        await useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont1).then(async (rt1: any) => {
          if (rt1.metaData.code == 200) {
            // send taks id 2
            const jsont2 = {
              "noregistrasifk": item.NOREC_PD,
              "taskid": 2,
              "waktu": moment(new Date()).add(5, 'm').valueOf(),
            }
            await useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont2).then(async (rt2: any) => {
              if (rt2.metaData.code == 200) {
                // send taks id 3
                const jsont3 = {
                  "noregistrasifk": item.NOREC_PD,
                  "taskid": 3,
                  "waktu": moment(new Date()).add(10, 'm').valueOf(),
                }
                await useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont3).then(async (rt3: any) => {
                  if (rt3.metaData.code == 200) {
                    const jsont4 = {
                      "noregistrasifk": item.NOREC_PD,
                      "taskid": 4,
                      "waktu": moment(new Date()).add(20, 'm').valueOf(),
                    }
                    await useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont4).then(async (rt4: any) => {
                      if (rt4.metaData.code == 200) {
                        const jsont5 = {
                          "noregistrasifk": item.NOREC_PD,
                          "taskid": 5,
                          "waktu": moment(new Date()).add(30, 'm').valueOf(),
                        }
                        await useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont4).then(async (rt5: any) => {

                        })
                      }
                    })
                  }
                })
              }
            })
          }
        })
      } else {
        error = response.metadata.message
        status = false
      }
    }).catch((e: any) => {
      status = false
      console.log('CATCH ERR ANTROL', e);
    })

    const result = {
      "status": status,
      "error": error
    }

    console.log('RESULT BPJS ANTROL', result);
  } catch (errorT) {
    console.log('CATCH ERR ANTROL', errorT);
  }

}

const fetchDokter = async (filter: any) => {
  const response = await useApi().get(`/registrasi/dokter-paging?name=${filter.query}&limit=10`)
  d_Dokter.value = response.dokter
}
const tambahAsuransi = (norec_pd: any) => {
  router.push({
    name: 'module-registrasi-pemakaian-asuransi',
    query: {
      norec_pd: norec_pd,
      nocmfk: pasien.value.nocmfk,
      //   norec_apd: norec_apd
    }
  })
  // modalAsuransi.value = true
}
const asuransi = () => {
  router.push({
    name: 'module-registrasi-pemakaian-asuransi',
    query: {
      norec_pd: item.NOREC_PD,
      nocmfk: pasien.value.nocmfk,
    }
  })
}
const cetakSEP = async (e: any) => {
  isLoadingCetak.value = true;
  await H.printBlade('registrasi/pemakaian-asuransi/sep?norec_pd=' + e.NOREC_PD + "&pdf=true");
  isLoadingCetak.value = false;

  // qzService.printData('registrasi/pemakaian-asuransi/sep?norec_pd=' + e.NOREC_PD + "&pdf=true", 'SEP', 1);
}

const cetakLabelPrev = async (e: any) => {
  modalJumlahLabel.value = true
}

const cetakLabel = async (e: any) => {
  modalJumlahLabel.value = false
  isLoadingCetak.value = true;
  await qzService.printData(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${item.NOREGISTRASI}`, 'LABEL PASIEN', item.jumlahLabel);
  isLoadingCetak.value = false;
}

const cetakkartuPasien = async (e: any) => {
  isLoadingCetak.value = true;
  await qzService.printData(`dashboard/registrasi/cetak-kartu-pasien?pdf=true&norec_pd=${item.NOREC_PD}`, 'KARTU PASIEN', 1);
  isLoadingCetak.value = false;
}

const cetakIdentitasPasien = async (e: any) => {
  console.log("data Iden", e);
  let statusBPJS = '';
  if (e.kelompokpasien && e.kelompokpasien == 2) {
    let token = await grecaptcha.execute(import.meta.env.VITE_CAPTCHA_SITEKEY, { action: 'submit' })
    let json = {
      "url": `Peserta/nokartu/${pasien.value.nobpjs}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
      "method": "GET",
      "data": null,
      "token": token
    }
    await useApi().postBPJS(`/bridging/bpjs/tools`, json).then((res) => {
      if (res.metaData.code == 200) {
        statusBPJS = res.response.peserta.statusPeserta.keterangan;
      }
    })
  }

  isLoadingCetak.value = true;
  await qzService.printData(`dashboard/registrasi/cetak-identitas-pasien?pdf=true&noregistrasi=${e.NOREGISTRASI}&statusbpjs=${statusBPJS}`, 'TRACER GANJIL', 1)
  isLoadingCetak.value = false;
}

const cetakBuktiPendaftaran = async (e: any) => {
  isLoadingCetak.value = true;
  await qzService.printData(`report/bukti-pendaftaran?pdf=true&noregistrasi=${e.NOREC_PD}&norec_pd=${e.NOREC_PD}`, 'BUKTI PENDAFATARN', 1);
  isLoadingCetak.value = false;
}
const cetakGelangPasien = (e: any) => {
  qzService.printData(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${e.NOREGISTRASI}`, 'GELANG PASIEN', 1)
}
const cetakTracerMedik = (noreg: any, nocm: any) => {
  if (parseInt(nocm) % 2 === 0) {
    qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`, 'TRACER GENAP', 1)
  } else {
    qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`, 'TRACER GANJIL', 1)
  }
  //  H.printBlade(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`);
}
const saveAdminAuto = (norec_pd: any, norec_apd: any, kebangsaan: any) => {
  let json = {
    norec: norec_pd,
    norec_apd: norec_apd,
    objectkebangsaanfk: kebangsaan
  }
  useApi().postNoMessage(`/registrasi/save-adminsitrasi`, json).then((response: any) => { })
}

const clearInput = () => {
  delete item.kelas
}

const getRuangan = () => {
  let query = '';
  useApi().get(`/registrasi/list-ruangan-ri-rj?query=${query}`).then((response: any) => {
    d_RuanganRJ.value = response.ruangan_RJ.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    d_RuanganRI.value = response.ruangan_RI.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    d_Ruangan.value = d_RuanganRJ.value
  });
}

const getKelompokPasien = () => {
  useApi().get(`/registrasi/list-kelompokpasien-all`).then((response: any) => {
    d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { label: e.kelompokpasien, value: e.id, default: e } })
    item.idKelompokPasienBPJS = response.idKelompokPasienBPJS
    item.idKelompokPasienUMUM = response.idKelompokPasienUMUM
    d_KelasDefault.value = response.kelas.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
  });
}

const asalRujukan = () => {
  useApi().get(`/registrasi/list-asalrujukan-pasien`).then((response: any) => {
    d_AsalRujukan.value = response.asalrujukan.map((e: any) => { return { label: e.asalrujukan, value: e.id, default: e } })
    // d_Dokter.value = response.dokter.map((e: any) => { return { label: e.kelompokpasien, value: e.id, default: e } })
    d_JenisPelayanan.value = response.jenispelayanan
    item.jenispelayanan = response.jenispelayanan[0].id
    for (let x = 0; x < response.asalrujukan.length; x++) {
      const element = response.asalrujukan[x];
      if (element.asalrujukan.toLowerCase() == 'datang sendiri') {
        item.asalrujukan = element.id
        break
      }
    }
  });
}

async function listDropdown() {
  const response = await useApi().get(`/registrasi/list-dropdown`)
  d_HubunganPasien.value = response.hubunganpasien.map((e: any) => { return { label: e.hubungankeluarga, value: e.id } })
  d_Kebangsaan.value = response.kebangsaan.map((e: any) => { return { label: e.name, value: e.id } })
  d_Pekerjaan.value = response.pekerjaan.map((e: any) => { return { label: e.pekerjaan, value: e.id, default: e } })
}

const setKamar = (e: any) => {
  if (d_Kamar.value.length) {
    d_Kamar.value.push({
      "label": e.namakamar,
      "value": e.objectkamarfk,
      "default": {}
    })
  } else {
    d_Kamar.value.push({
      "label": e.namakamar,
      "value": e.objectkamarfk,
      "default": {}
    })
  }
}

const setTempatTidur = (e: any) => {
  if (d_TempatTidur.value.length) {
    d_TempatTidur.value.push({
      "label": e.tempattidur,
      "value": e.nobed,
      "default": {
        "id": e.nobed,
        "reportdisplay": e.tempattidur,
        "nomorbed": e.nomorbed,
        "objectkamarfk": item.kamar
      }
    })
  } else {
    d_TempatTidur.value = [{
      "label": e.tempattidur,
      "value": e.nobed,
      "default": {
        "id": e.nobed,
        "reportdisplay": e.tempattidur,
        "nomorbed": e.nomorbed,
        "objectkamarfk": item.kamar
      }
    }]
  }
}

onMounted(async () => {
  await getRuangan();
  listDropdown()
  getKelompokPasien();
  asalRujukan();
  pasienByID(ID_PASIEN)
})
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
</style>
