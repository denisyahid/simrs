<template>
  <ConfirmDialog />
  <div class="form-layout is-stacked">

    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header pr-2">
        <div class="form-header-inner">
          <div class="left">
            <h3>Registrasi</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <!-- <VDropdown icon="feather:more-vertical" spaced right v-if="item.NOREC_PD" class="mt-1-min mr-2"
                v-tooltip.bubble="'CETAK'">
                <template #content>
                  <a role="menuitem" @click="cetakSEP(item)" class="dropdown-item is-media">
                    <div class="icon">
                      <i aria-hidden="true" class="lnil lnil-printer"></i>
                    </div>
                    <div class="meta">
                      <span>Cetak SEP</span>
                      <span>Cetak Surat Elegibilitas </span>
                    </div>
                  </a>
                  <a @click="cetakBuktiPendaftaran(item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i class="fas fa-print" aria-hidden="true"></i>
                    </div>
                    <div class="meta">
                      <span>Poli Antrian</span>
                      <span>Cetak Nomor</span>
                    </div>
                  </a>
                  <a @click="cetakLabel(item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i class="fas fa-print" aria-hidden="true"></i>
                    </div>
                    <div class="meta">
                      <span>Label Pasien</span>
                      <span>Cetak Label</span>
                    </div>
                  </a>
                  <a @click="cetakkartuPasien(item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i class="fas fa-print" aria-hidden="true"></i>
                    </div>
                    <div class="meta">
                      <span>Kartu Pasien</span>
                      <span>Cetak Kartu</span>
                    </div>
                  </a>
                  <a @click="cetakGelangPasien(item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i class="fas fa-print" aria-hidden="true"></i>
                    </div>
                    <div class="meta">
                      <span>Gelang Pasien</span>
                      <span>Cetak Gelang </span>
                    </div>
                  </a>
                </template>
              </VDropdown>
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
              </VButton> -->

              <!-- DEFAULT -->
              <!-- <VButton type="button" color="primary" rounded outlined raised icon="feather:save" :disabled="isDisabled"
                :loading="isLoading" @click="checkIsExsist()"> Simpan </VButton> -->  
                
                <!-- NEW   -->
              <VButton type="button" color="info" rounded outlined raised   @click="loadRiwayat()"> Riwayat </VButton>
              <VButton type="button" color="primary" rounded outlined raised icon="feather:save" :disabled="isDisabled"
                :loading="isLoading" @click="saveRegistrasi()"> Simpan </VButton>

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
              <VControl style="display: none !important">
                <VSwitchBlock v-model="item.isRawatInap" label="Rawat Inap" color="danger"
                  @change="changeSwitch(item.isRawatInap)" />

              </VControl>
              <VControl>
                <!-- <VRadio v-model="item.isRawatGabung" :value="true" label="Rawat Gabung" name="isRawatGabung"
                    color="primary" id="isRawatGabung" style="margin-top: 5px; margin-left: 30px; font-size: 0.9rem;"
                    v-if="item.isRawatInap" /> -->
                <!-- <VRadio v-model="item.isRawatGabung" :value="true" label="Rawat Gabung" name="isRawatGabung" square
                  color="primary" v-if="item.isRawatInap"
                  style="margin-top: 5px; margin-left: 30px; font-size: 0.9rem;" /> -->

              </VControl>
            </VField>
            <!-- <VField horizontal label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:home" fullwidth>
                <Multiselect mode="single" v-model="item.ruangan" :options="d_Ruangan" placeholder="Pilih data"
                  :searchable="true" :attrs="{ id }" autocomplete="off" @select="changeRuang(item.ruangan)" />
              </VControl>
            </VField>
            <VField v-if="item.isRawatInap" horizontal label="Kelas Rawat"
              class="is-rounded-select_Z  is-autocomplete-select">
              <VField v-slot="{ id }">
                <VControl icon="feather:layers" fullwidth>
                  <Multiselect mode="single" v-model="item.kelasRawat" :options="d_Kelas" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT"
                    @select="changeKelas(item.kelasRawat)" />
                </VControl>
              </VField>
              <VField v-slot="{ id }" v-if="item.kelasRawat" horizontal label="Kelas Ditanggung">
                <VControl icon="feather:layers" fullwidth>
                  <Multiselect mode="single" v-model="item.kelas" :options="d_KelasDefault" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT" />
                </VControl>
              </VField>
            </VField>
            <VField v-if="item.isRawatInap && item.kelasRawat" horizontal label="Kamar"
              class="is-rounded-select_Z  is-autocomplete-select">
              <VField v-slot="{ id }">
                <VControl icon="fas fa-hospital-alt">
                  <Multiselect mode="single" v-model="item.kamar" :options="d_Kamar" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT"
                    @select="changeKamar(item.kamar)" />
                </VControl>
              </VField>
              <VField v-slot="{ id }" subcontrol v-if="item.kamar">
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
            </div>

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
            <VField horizontal label="Tipe Layanan">
              <VControl>
                <VRadio v-model="item.jenispelayanan" v-for="items of d_JenisPelayanan" :key="items.id"
                  :value="items.id" :label="items.jenispelayanan" name="{{items.id}}" color="primary" />
              </VControl>

            </VField> -->

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
            <VField addons horizontal label="BB">
              <VControl expanded>
                <VInput type="text" class="input" placeholder="" v-model="item.bb" disabled />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>kg</VButton>
              </VControl>
            </VField>
            <VField addons horizontal label="TB">
              <VControl expanded>
                <VInput type="text" class="input" placeholder="" v-model="item.tb" disabled />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>cm</VButton>
              </VControl>
            </VField>
            <VField horizontal label="Diagnosa">
              <VControl fullwidth>
                <VTextarea class="textarea" v-model="item.diagnosa" rows="1" placeholder="" autocomplete="off"
                  autocapitalize="off" spellcheck="true" disabled />

              </VControl>
            </VField>
            <VField horizontal label="Catatan">
              <VControl fullwidth>
                <VTextarea class="textarea" v-model="item.catatan" rows="4"
                  placeholder="catatan registrasi (optional) ..." autocomplete="off" autocapitalize="off"
                  spellcheck="true" />

              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 mt-0">
          <div class="form-section pt-0 pl-0">
            <div class="form-section-inner">
              <h3 class="" style="font-size: 13pt; font-weight: bold; margin-top: 50px; margin-bottom: 20px;">Detail
                Pemeriksaan In Vivo</h3>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <UIWidget class="search-widget">
                    <template #body>
                      <div class="field">
                        <div class="control">
                          <input type="text" v-model="filterLayanan" class="input" placeholder="Search..." />
                          <button class="searcv-button" type="button">
                            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                          </button>
                        </div>
                      </div>
                    </template>
                  </UIWidget>
                </div>
                <div class="column is-12 h-400-o">
                  <!-- <pre>{{ filteredLayanan }}</pre> -->
                  <div class="column is-12" v-for="items in filteredLayanan" :key="items.id">
                    <div class="group-header">
                      <VIconBox color="maroon" style="width:30px;height:30px; min-width: 30px; ">
                        <i aria-hidden="true" class="lnir lnir-flask-alt" style="font-size: 1rem"></i>
                      </VIconBox>
                      <h4 class="ml-1">{{ items.detailjenisproduk }}</h4>
                    </div>

                    <div class="columns is-multiline mb-3">
                      <div class="column is-4" v-for="itemProd in items.details" :key="itemProd.id">
                        <VField grouped>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="item.produkCeklis[itemProd.id]" :label="itemProd.namaproduk"
                              color="info" @change="getSelected()" />
                          </VControl>
                        </VField>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="item.catataninvivo" rows="2" placeholder="Tambahan Tindakan"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="column is-12 mt-0" style="display: none !important">
          <div class="form-section pt-0 pl-0">
            <div class="form-section-inner">
              <h3 class="" style="font-size: 13pt; font-weight: bold; margin-top: 50px; margin-bottom: 20px;">Terapi
                Radioaktif</h3>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <UIWidget class="search-widget">
                    <template #body>
                      <div class="field">
                        <div class="control">
                          <input type="text" v-model="filterLayanan" class="input" placeholder="Search..." />
                          <button class="searcv-button" type="button">
                            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                          </button>
                        </div>
                      </div>
                    </template>
                  </UIWidget>
                </div>
                <div class="column is-12 h-400-o">
                  <div class="column is-12">
                    <div class="columns is-multiline mb-3">
                      <div class="column is-4" v-for="itemProd in d_Terapi" :key="itemProd.value">
                        <VField grouped>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="item.produkCeklisRadioaktif[itemProd.value]" :label="itemProd.label"
                              color="info" @change="getSelectedRadioaktif()" />
                          </VControl>
                        </VField>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="item.catatanterapi" rows="2" placeholder="catatan"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12" style="display: none !important">
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="item.kesimpulanterapi" rows="2" placeholder="kesimpulan"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="column is-12 mt-0" style="display: none !important">
          <div class="form-section pt-0 pl-0">
            <div class="form-section-inner">
              <h3 class="" style="font-size: 13pt; font-weight: bold; margin-top: 50px; margin-bottom: 20px;">Permintaan
                Terapi</h3>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <h3 class="" style="font-size: 11pt;">Dosis Terapi</h3>
                </div>
                <div class="column is-9">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="Terapi Iodium" v-model="item.terapiIodium" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mCi</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="column is-12 mt-0">
          <div class="form-section pt-0 pl-0">
            <div class="form-section-inner">
              <h3 class="" style="font-size: 13pt; font-weight: bold; margin-top: 50px; margin-bottom: 20px;">Permintaan
                Diagnostik In Vivo</h3>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <h3 class="" style="font-size: 11pt;">Jenis Radionuklida</h3>
                </div>
                <div class="column is-9">
                  <VField horizontal class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:home" fullwidth>
                      <Multiselect mode="single" v-model="item.radionuklida" :options="d_Radionuklida"
                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h3 class="" style="font-size: 11pt;">Jenis Farmaka</h3>
                </div>
                <div class="column is-9">
                  <VField horizontal class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:home" fullwidth>
                      <Multiselect mode="single" v-model="item.farmaka" :options="d_Farmaka" placeholder="Pilih data"
                        :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="item.catatanfarmaka" rows="2" placeholder="lain-lain"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h3 class="" style="font-size: 11pt;">Dosis/Aktifitas Radiofarmaka</h3>
                </div>
                <div class="column is-9">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="Terapi Radiofarmaka"
                        v-model="item.terapiRadiofarmaka" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mCi</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- <TLaboratorium></TLaboratorium>
          <TRadiologi></TRadiologi> -->
      </div>
    </div>
  </div>

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
  <Dialog v-model:visible="modalRiwayat" modal header="Riwayat In Vivo" :style="{ width: '50vw' }">
        <TRiwayatOrderRad  :items="listRiwayat" @hapusItems="DialogConfirm" @lihatCetakan="lihatCetakan" :isHideCetak='hideCetak' :isShowHasil='showHasil' >  
        </TRiwayatOrderRad>
  </Dialog>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import InfoPasien from '/@src/pages/include/info-pasien.vue';
import * as qzService from '/@src/utils/qzTrayService'
import TLaboratorium from './order-laboratorium.vue'
import TRadiologi from './order-radiologi.vue'
import TRiwayatOrderRad from '../t-riwayat-order-rad.vue'
import Dialog from "primevue/dialog"

useHead({
  title: 'Registrasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
let NOREC_RESERVASI = useRoute().query.norec_online as string
let STATUSPASIEN = useRoute().query.statuspasien as string
let NOREGISTRASI = "";
const hideCetak = ref(true);
const showHasil = ref(true);
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
  produkCeklis: [],
  produkCeklisRadioaktif: [],
  idKelompokPasienUMUM: null,
  pasien: {},
})
const confirm = useConfirm();
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const d_Radionuklida: any = ref([])
const d_Farmaka: any = ref([])
const d_RuanganRI: any = ref([])
const d_RuanganRJ: any = ref([])
const d_AsalRujukan: any = ref([])
const d_KelompokPasien: any = ref([])
const d_JenisPelayanan: any = ref([])
const listRiwayat = ref([])
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const d_ProdukDef: any = ref([])
const d_Rekanan: any = ref([])
const d_Dokter: any = ref([])
const d_Kelas: any = ref([])
const d_Produk: any = ref([])
const d_KelasDefault: any = ref([])
const d_Kamar: any = ref([])
const d_TempatTidur: any = ref([])
const d_Terapi: any = ref([{ value: 1, label: 'Hipertiroidism' }, { value: 2, label: 'Karsinoma Tiroid' }, { value: 3, label: 'Hemangioma' }, { value: 4, label: 'Koloid' }, { value: 5, label: 'Nyeri tulang pallatif' }, { value: 6, label: 'Kersinoma hepat' }, { value: 7, label: 'Sinovektomi' }])
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const isLoadingTT: any = ref(false)
const isLoadingPasien: any = ref(false)
const modalRiwayat: any = ref(false)
const modalAsuransi: any = ref(false)
const listChecked: any = ref([])
const filterLayanan: any = ref('')
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
  return y.value > 30
})
const props = withDefaults(
  defineProps<{
    pasien?: any;
    registrasi?: any;
    FORM_NAME?: string;
    FORM_URL?: string;
    COLLECTION?: string;
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: "",
    FORM_URL: "",
    COLLECTION: "",
  }
);

const lihatCetakan = async (e: any) => {
if (e.norec_hr == null) {
  H.alert('error','Belum ada hasil Radiologi');
  return;
}
  H.printBlade("radiologi/cetak-ekspertise-manual?echo=true&norec=" + e.norec_hr);
}
console.log('registrasi', props.registrasi)
console.log('pasien', props.pasien)
const pasienByID = (id: any) => {
  isLoadingPasien.value = true
  let paramsEdit = ''
  if (item.NOREC_PD != '' && item.NOREC_APD != '') {
    paramsEdit = `&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`
  }
  useApi().get(
    "emr/auto-fill?norec_pd=" + item.NOREC_PD +
    "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + "&field=tinggibadanObgyn,beratbadanObgyn"
  ).then((response) => {
    item.bb = response.beratbadanObgyn
    item.tb = response.tinggibadanObgyn
    console.log(response)
  })

  useApi().get(
    `/laboratorium/list-tindakan-for-order?ruanganfk=331&idkebangsaan=${props.pasien.objectkebangsaanfk}&kelasfk=${props.registrasi.apd.objectkelasfk}`).then((response: any) => {
      d_Produk.value = response.list_tindakan
      console.log('test lab produk', d_Produk.value)
    })


  useApi().get(
    `/nuklir/list-dropdown`).then((response: any) => {
      d_RuanganRJ.value = response.ruanganLab.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
      d_Radionuklida.value = response.radionuklidadiagnostik.map((e: any) => { return { label: e.radionuklida, value: e.id, default: e } })
      d_Farmaka.value = response.farmaka.map((e: any) => { return { label: e.farmaka, value: e.id, default: e } })
      item.ruangan = d_RuanganRJ.value[0].value
    })
  useApi().get(
    `/registrasi/pasien-registrasi?id=${id}${paramsEdit}`).then((response: any) => {
      pasien.value = response.pasien
      d_KelasDefault.value = response.kelas.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
      d_RuanganRI.value = response.ruangan_RI.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
      d_AsalRujukan.value = response.asalrujukan.map((e: any) => { return { label: e.asalrujukan, value: e.id, default: e } })
      d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { label: e.kelompokpasien, value: e.id, default: e } })
      // d_Dokter.value = response.dokter.map((e: any) => { return { label: e.kelompokpasien, value: e.id, default: e } })
      d_JenisPelayanan.value = response.jenispelayanan
      item.idKelompokPasienBPJS = response.idKelompokPasienBPJS
      item.idKelompokPasienUMUM = response.idKelompokPasienUMUM
      item.jenispelayanan = response.jenispelayanan[0].id
      for (let x = 0; x < response.asalrujukan.length; x++) {
        const element = response.asalrujukan[x];
        if (element.asalrujukan.toLowerCase() == 'datang sendiri') {
          item.asalrujukan = element.id
          break
        }
      }
      if (response.registrasi == null) {
        d_Ruangan.value = d_RuanganRJ.value
        if (NOREC_RESERVASI != undefined) {
          setFromReservasi(RESERVASI)
        }
      } else {
        let regis = response.registrasi
        if (ID_PASIEN == regis.nocmfk) {
          setFromRegistrasi(regis)
        }
        item.NOREGISTRASI = response.registrasi.noregistrasi
      }
      isLoadingPasien.value = false
    })

}
const getSelected = () => {
  if (item.produkCeklis.length > 0) {
    var arrobj = Object.keys(item.produkCeklis)
    for (var x = 0; x < arrobj.length; x++) {
      const element = arrobj[x];
      if (item.produkCeklis[parseInt(element)] == true) {
        for (var i = 0; i < d_ProdukDef.value.length; i++) {
          const element2 = d_ProdukDef.value[i];
          if (element2.id == element) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element3 = listChecked.value[z];
              if (element3.namaproduk == element2.namaproduk) {
                listChecked.value.splice(z, 1)
              }
            }
            listChecked.value.push({ namaproduk: element2.namaproduk, hargasatuan: element2.hargasatuan, id: element2.id })
          }
        }
      } else {
        for (var i = 0; i < d_ProdukDef.value.length; i++) {
          const element2 = d_ProdukDef.value[i];
          if (element2.id == element) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element3 = listChecked.value[z];
              if (element3.namaproduk == element2.namaproduk) {
                listChecked.value.splice(z, 1)
              }
            }
          }
        }
      }
    }

  }

}

const getSelectedRadioaktif = () => {
  if (item.kesimpulanterapi == undefined) {
    item.kesimpulanterapi = ''
  }
  if (item.produkCeklisRadioaktif.length > 0) {
    var arrobj = Object.keys(item.produkCeklisRadioaktif)
    console.log(arrobj)
    for (var x = 0; x < arrobj.length; x++) {
      const element = arrobj[x];
      for (var y = 0; y < d_Terapi._value.length; y++) {
        const element2 = d_Terapi._value[y];
        if (element2.value == element) {
          if (item.produkCeklisRadioaktif[parseInt(element)] == true) {
            item.kesimpulanterapi = item.kesimpulanterapi.replace(element2.label + ", ", "")
            item.kesimpulanterapi += element2.label + ", "
          } else {
            item.kesimpulanterapi = item.kesimpulanterapi.replace(element2.label + ", ", "")
          }
        }
      }
      // const element2 = d_Terapi.value[i];
      // if (element2.id == element) {
      //     for (var z = 0; z < listChecked.value.length; z++) {
      //         const element3 = listChecked.value[z];
      //         if (element3.namaproduk == element2.namaproduk) {
      //             listChecked.value.splice(z, 1)
      //         }
      //     }
      //     listChecked.value.push({ namaproduk: element2.namaproduk, hargasatuan: element2.hargasatuan, id: element2.id })
      // }



    }

  }

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
  d_Ruangan.value = d_RuanganRJ.value
  item.isRawatInap = regis.israwatinap
  if (regis.israwatinap == true) {
    d_Ruangan.value = d_RuanganRI.value
  }
  item.ruangan = regis.objectruanganlastfk
  await changeRuang(item.ruangan)
  item.tglregistrasi = new Date(regis.tglregistrasi)
  item.asalrujukan = regis.asalrujukanfk
  item.kelompokpasien = regis.objectkelompokpasienlastfk
  changeKelompok(item.kelompokpasien)
  item.rekanan = regis.objectrekananfk
  item.jenispelayanan = regis.jenispelayananfk
  if (regis.objectpegawaifk != null)
    item.dokter = { id: regis.objectpegawaifk, namalengkap: regis.dokter }
  item.kelasRawat = regis.objectkelasrawatfk
  item.kelas = regis.objectkelasfk
  await changeKelas(item.kelasRawat)
  item.catatan = regis.catatan
  item.statuspasien = regis.statuspasien
  item.kamar = regis.objectkamarfk
  await changeKamar(item.kamar)
  if (d_TempatTidur.value.length) {
    d_TempatTidur.value.push({
      "label": regis.tempattidur,
      "value": regis.nobed,
      "default": {
        "id": regis.nobed,
        "reportdisplay": regis.tempattidur,
        "nomorbed": regis.nomorbed,
        "objectkamarfk": item.kamar
      }
    })
  } else {
    d_TempatTidur.value = [{
      "label": regis.tempattidur,
      "value": regis.nobed,
      "default": {
        "id": regis.nobed,
        "reportdisplay": regis.tempattidur,
        "nomorbed": regis.nomorbed,
        "objectkamarfk": item.kamar
      }
    }]
  }
  item.bed = regis.nobed
}
const changeSwitch = (e: any) => {
  delete item.ruangan
  d_Ruangan.value = []
  if (e == true) { d_Ruangan.value = d_RuanganRI.value } else { d_Ruangan.value = d_RuanganRJ.value }
}
const changeRuang = (e: any) => {
  item.kelas = null
  item.kelasRawat = null
  if (e) {
    if (item.isRawatInap == true) {
      setKelas(e)
    }
  }
}
const setKelas = (e: any) => {
  isLoadingTT.value = true
  d_Kelas.value = []
  useApi().get(
    `/registrasi/kelas-by-ruangan?id=${e}`)
    .then((response: any) => {
      isLoadingTT.value = false
      if (response.length == 1) {
        item.kelas = response[0].id
      }
      d_Kelas.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
    })
    .catch((error: any) => { isLoadingTT.value = false })
}
const filteredLayanan = computed(() => {

  if (!filterLayanan.value) {
    return d_Produk.value
  }
  var filtered: any = [];

  for (let i = 0; i < d_Produk.value.length; i++) {
    const element = d_Produk.value[i];
    filtered.push({
      'detailjenisproduk': element.detailjenisproduk,
      'id': element.id,
      'details': []
    })
    for (let ii = 0; ii < element.details.length; ii++) {
      const element2 = element.details[ii];
      if (element2.namaproduk.match(new RegExp(filterLayanan.value, 'i'))) {
        filtered[filtered.length - 1].details.push(element2)
      }
    }
  }
  return filtered;
})

const changeKelas = async (e: any) => {
  d_Kamar.value = []
  delete item.kamar
  if (e && item.ruangan) {
    isLoadingTT.value = true
    await useApi().get(
      `/registrasi/kamar-by-kelas?id=${e}&idRuangan=${item.ruangan}&isRG=false`)
      .then((response: any) => {
        isLoadingTT.value = false
        d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
      })
      .catch((error: any) => { isLoadingTT.value = false })
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

const loadRiwayat = () => {
  modalRiwayat.value=true
  listRiwayat.value = []
  let nocm = props.pasien ? props.pasien.nocm : pasien.value.nocm
  let noregistrasi = props.registrasi ? props.registrasi.noregistrasi : item.registrasi.noregistrasi
  useApi().get(
    `/radiologi/riwayat-order?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&noregistrasi=${item.NOREGISTRASI}`).then((response: any) => {
      let z = 0
      for (let x = 0; x < response.length; x++) {
        const element = response[x];
        element.icon = 'fa fa-radiation'
        element.color = listColor2.value[z]
        element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
        if (z > 4) {
          z = 0
        }
        z++
      }
      listRiwayat.value = response
    })
}
const DialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapusItems(e)

    },
    reject: () => { },
  })
}
const hapusItems = (e: any) => {
  if (e.status != 'pending') {
    H.alert('error', 'Order sudah diverifikasi')
    return
  }
  useApi().post(
    `/radiologi/delete-order-rad`, { noorder: e.noorder }).then((response: any) => {
      isLoading.value = false
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
    })
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
    await useApi().get(
      `/registrasi/penjamin-by-kelompokpasien?id=${e}`)
      .then((response: any) => {
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
      })
      .catch((error: any) => { isLoadingTT.value = false })
  }

}

const checkIsExsist = async () => {
  isLoading.value = true
  if (!item.NOREC_PD) {
    let respon = await useApi().get(`registrasi/pasien-hari-ini?nocmfk=${ID_PASIEN}`)
    isLoading.value = false
    if (respon != null) {
      confirm.require({
        message: `Pasien Sudah teregistrasi hari ini di ${respon.namaruangan}, Lanjutkan registrasi ?`,
        header: 'Konfirmasi Registrasi Pasien',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: async () => {
          await saveRegistrasi()
        },
        reject: () => { },
      })
    } else {
      await saveRegistrasi()
    }
  } else {
    isLoading.value = false
    await saveRegistrasi()
  }
}

const saveRegistrasi = async () => {

  // if (!item.tglregistrasi) { H.alert('warning', 'Tgl Registrasi harus di isi'); return }
  // if (!item.ruangan) { H.alert('warning', 'Ruangan harus di isi'); return }
  // if (!item.asalrujukan) { H.alert('warning', 'Asal Rujukan  harus di isi'); return }
  // if (!item.kelompokpasien) { H.alert('warning', 'Pembiayaan harus di isi'); return }
  // if (!item.jenispelayanan) { H.alert('warning', 'Tipe Layanan harus di isi'); return }
  // if (item.isRawatInap == true) {
  //   if (!item.kelasRawat) { H.alert('warning', 'Kelas Dirawat harus di isi'); return }
  //   if (!item.kelas) { H.alert('warning', 'Kelas Ditanggung harus di isi'); return }
  //   if (!item.kamar) { H.alert('warning', 'Kamar harus di isi'); return }
  //   if (!item.bed) { H.alert('warning', 'Bed harus di isi'); return }
  // }



  var arrobj = Object.keys(item.produkCeklis)
  var data2 = []
  for (var i = arrobj.length - 1; i >= 0; i--) {
    if (item.produkCeklis[parseInt(arrobj[i])] == true) {
      var data = {
        no: i + 1,
        produkfk: arrobj[i],
        qtyproduk: 1,
        objectkelasfk: item.kelas ? item.kelas : null,
        nourut: null,
      }
      data2.push(data)
    }
  }
  if(data2.length < 1){
    H.alert('warning','Tindakan/Pemeriksaan Harus di isi!')
    return
  }

  let json = {
    'pasiendaftar': {
      'norec': '',
      'nocmfk': ID_PASIEN,
      'norec_pd':NOREC_PD,
      'tglregistrasi': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
      'objectruanganlastfk': item.ruangan ? item.ruangan :null,
      'asalrujukanfk': item.asalrujukan ? item.asalrujukan : null,
      'keteranganasalrujukan': item.keteranganasalrujukan ? item.keteranganasalrujukan : null,
      'objectkelompokpasienlastfk': item.kelompokpasien ? item.kelompokpasien : null,
      'jenispelayananfk': item.jenispelayanan ? item.jenispelayanan : null,
      'objectpegawaifk': item.dokter ? item.dokter.id : null,
      'objectpegawairawatbersamafk': item.dokterRawatBersama ? item.dokterRawatBersama.id : null,
      'objectkelasfk': item.kelas ? item.kelas : null,
      'objectkelasrawatfk': item.kelasRawat ? item.kelasRawat : null,
      'israwatinap': item.isRawatInap ? item.isRawatInap : false,
      'catatan': item.catatan ? item.catatan : null,
      'statuspasien': STATUSPASIEN ? STATUSPASIEN : 'LAMA',//item.statuspasien ? item.statuspasien : 'LAMA',
      'objectrekananfk': item.rekanan != undefined ? item.rekanan : null,
      'nocm': pasien.value.nocm,
      'namapasien': pasien.value.namapasien,
      'antrianpasienregistrasifk': NOREC_RESERVASI ? NOREC_RESERVASI : null,
    },
    'antrianpasiendiperiksa': {
      'norec': '',
      'objectkamarfk': item.kamar ? item.kamar : null,
      'nobed': item.bed ? item.bed : null,
      'israwatgabung': item.isRawatGabung ? item.isRawatGabung : null,
      'objectruanganfk':331
    },
    'objSave': {
      'tanggal': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
      'tglrencanaterapi': null,
      'pegawaiorderfk': item.dokter ? item.dokter.id : null,
      'tb': item.tb ? item.tb : null,
      'bb': item.bb ? item.bb : null,
      'radionuklidafk': item.radionuklida ? item.radionuklida : null,
      'farmakafk': item.farmaka ? item.farmaka : null,
      'objectruangantujuanfk': 331,
      'tgloperasi': null,
      'norec_so': '',
      'catatanKlinis': item.catataninvivo ? item.catataninvivo : null,
      'catatanterapi': item.catatanterapi ? item.catatanterapi : null,
      'terapiIodium': item.terapiIodium ? item.terapiIodium : null,
      'terapiRadiofarmaka': item.terapiRadiofarmaka ? item.terapiRadiofarmaka : null,
      'kesimpulanterapi': item.kesimpulanterapi ? item.kesimpulanterapi : null,
      'catatanfarmaka': item.catatanfarmaka ? item.catatanfarmaka : null,
      'keterangan': null,
      'prefix': 'NV',
      'iscito': false,
      'qtyproduk': data2.length,
      'details': data2,
    }
  }
  isLoading.value = true
  // let url = '/registrasi/save-registrasi-nuklir'; //DEFAULT
  let url = '/registrasi/save-registrasi-nuklir-rev';
  // if (item.isRawatInap) {
  //   url = '/dashboard/save-rencana-mutasi';
  // }
  await useApi().post(url, json).then((response: any) => {
    isLoading.value = false
    isDisabled.value = true
    if (!item.NOREC_PD) {
      saveAdminAuto(response.registrasi.pd.norec, response.registrasi.apd.norec)
    }
    // if( item.isRawatInap == true){
    //   let jsonKamar = {idruangan : item.ruangan, idkelas : item.kelas}
    //   useApi().postNoMessage(`/bridging/bpjs/update-kamar`, jsonKamar).then((response: any) => {})

    // }
    item.NOREC_PD = response.registrasi.pd.norec
    item.NOREC_APD = response.registrasi.apd.norec
    item.NOREGISTRASI = response.registrasi.apd.noregistrasi
    // for (let xx = 0; xx < item.idKelompokPasienBPJS.length; xx++) {
    //   const element = item.idKelompokPasienBPJS[xx];
    //   if (element == item.kelompokpasien) {
    //     tambahAsuransi(item.NOREC_PD)
    //   }
    // }
    // cetakTracerMedik(response.registrasi.apd.noregistrasi,response.registrasi.nocm);
  }).catch((e: any) => {
    isLoading.value = false

    if (e.message == 'Bed Sudah Terisi, Silakan Pilih Bed Lain') {
      changeKelas(item.kelasRawat)
    }
    console.clear()
    console.log(e)
  })
  isLoading.value = false
}

// const checkAsalRujuk = (e:any)=>{
//     item.asalrujukan = e
//     console.log(item.asalrujukan)
// }

const fetchDokter = async (filter: any) => {
  if (!filter.query) {

  }

  const response = await useApi().get(
    `/registrasi/dokter-paging?name=${filter.query}&limit=10`)
  d_Dokter.value = response.dokter
  // return response.dokter.map((item: any) => {
  //     return { value: item.id, label: item.namalengkap, default: item }
  // })
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
const cetakSEP = (e: any) => {
  H.printBlade('registrasi/pemakaian-asuransi/sep?norec_pd=' + e.NOREC_PD + "&pdf=true");
  // qzService.printData('registrasi/pemakaian-asuransi/sep?norec_pd=' + e.NOREC_PD + "&pdf=true", 'SEP', 1);
}

const cetakLabel = async (e: any) => {
  qzService.printData(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.NOREGISTRASI}`, 'LABEL PASIEN', 1);
}

const cetakkartuPasien = async (e: any) => {
  qzService.printData(`dashboard/registrasi/cetak-kartu-pasien?pdf=true&norec_pd=${e.NOREC_PD}`, 'KARTU PASIEN', 1);
}

const cetakBuktiPendaftaran = (e: any) => {
  qzService.printData(`report/bukti-pendaftaran?pdf=true&noregistrasi=${e.NOREC_PD}&norec_pd=${e.NOREC_PD}`, 'BUKTI PENDAFATARN', 1);
}
const cetakGelangPasien = (e: any) => {
  qzService.printData(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${e.NOREGISTRASI}`,
    'GELANG PASIEN', 1)
}
const cetakTracerMedik = (noreg: any, nocm: any) => {
  if (parseInt(nocm) % 2 === 0) {
    qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`, 'TRACER GENAP', 1)
  } else {
    qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`, 'TRACER GANJIL', 1)
  }
  //  H.printBlade(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`);
}
const saveAdminAuto = (norec_pd: any, norec_apd: any) => {
  let json = {
    norec: norec_pd,
    norec_apd: norec_apd
  }
  useApi().postNoMessage(
    `/registrasi/save-adminsitrasi`, json).then((response: any) => { })
}
const clearInput = () => {
  delete item.kelasRawat
}


pasienByID(ID_PASIEN)
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

// .dropdown.is-dots .is-trigger {
//     background: var(--red) !important;
// }
// .dropdown.is-dots .is-trigger svg {
//     color: white;
// }</style>
