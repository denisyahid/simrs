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
                @kembaliKeun="kembaliKeun" isHideCetak isHideST></ButtonEmr>
            </div>
          </div>
        </div>
  
      </div>
    </div>
  
  
    <div class="column">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-4">
            <span>
                Tanggal Orientasi <i>(Orientation Date)</i> :
            </span>
          </div>
          <div class="column is-4">
            
          </div>
          <div class="column is-4">
            Ruangan <i>(Ward)</i> :
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-3">
            <VDatePicker v-model="input.tanggal" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                </template>
            </VDatePicker>
          </div>
          <div class="column is-5">
            
          </div>
          <div class="column is-3">
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.Ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari ruangan ..."
                  class="is-rounded" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column" style="overflow: auto; width:100%;">
          <table class="table is-striped is-fullwidth table-popri">
            <thead>
              <tr>
                <th class="th-popri" width="3%">No</th>
                <th class="th-popri" width="77">Materi Orientasi</th>
                <th class="th-popri" width="10%">Ya</th>
                <th class="th-popri" width="10%">Tidak</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="td-popri">
                  <span>1</span>
                </td>
                <td class="td-popri">
                    <span>
                        Peraturan dan tata tertib UPTD.RSUD Bali Mandara Provinsi Bali
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center; color: black;">
                    <VRadio
                        v-model="input.peraturan"
                        value="Ya"
                        name="peraturan"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.peraturan"
                        value="Tidak"
                        name="peraturan"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>2</span>
                </td>
                <td class="td-popri">
                    <span>
                        Hak dan Kewajiban Pasien
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.Kewajiban"
                        value="Ya"
                        name="kewajiban"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.Kewajiban"
                        value="Tidak"
                        name="kewajiban"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>3</span>
                </td>
                <td class="td-popri">
                    <span>
                        Perawat dan Dokter Penanggung Jawab
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.penanggungjawab"
                        value="Ya"
                        name="penanggungjawab"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.penanggungjawab"
                        value="Tidak"
                        name="penanggungjawab"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>4</span>
                </td>
                <td class="td-popri">
                    <span>
                        Jam Visite Dokter
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.visite"
                        value="Ya"
                        name="visite"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.visite"
                        value="Tidak"
                        name="visite"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>5</span>
                </td>
                <td class="td-popri">
                    <span>
                        Waktu Berkunjung
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.berkunjung"
                        value="Ya"
                        name="waktuberkunjung"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.berkunjung"
                        value="Tidak"
                        name="waktuberkunjung"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>6</span>
                </td>
                <td class="td-popri">
                    <span>
                        Layanan Obat
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.layananobat"
                        value="Ya"
                        name="layananobat"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.layananobat"
                        value="Tidak"
                        name="layananobat"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>7</span>
                </td>
                <td class="td-popri">
                    <span>
                        Jadwal Pemberian Makanan Pasien
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.jadwalmakan"
                        value="Ya"
                        name="jadwalmakan"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.jadwalmakan"
                        value="Tidak"
                        name="jadwalmakan"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>8</span>
                </td>
                <td class="td-popri">
                    <span>
                        Administrasi dan Pembayaran
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.administrasi"
                        value="Ya"
                        name="administrasi"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.administrasi"
                        value="Tidak"
                        name="administrasi"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>9</span>
                </td>
                <td class="td-popri">
                    <span>
                        Penjelasan Penggunaan Gelang Identitas Pasien
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.gelangidentitas"
                        value="Ya"
                        name="gelangidentitias"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.gelangidentitas"
                        value="Tidak"
                        name="gelangidentitias"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>10</span>
                </td>
                <td class="td-popri">
                    <span>
                        Fasilitas (Jenis dan Cara Penggunaan) :
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.fasilitas"
                        value="Ya"
                        name="fasilitas"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.fasilitas"
                        value="Tidak"
                        name="fasilitas"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <!-- Fasilitas -->
              <tr v-if="input.fasilitas == 'Ya'">
                <td class="td-popri">
                  <span>&nbsp;</span>
                </td>
                <td class="td-popri">
                    <span>
                        Kamar Mandi
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.kamarmandi"
                        value="Ya"
                        name="kamarmandi"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.kamarmandi"
                        value="Tidak"
                        name="kamarmandi"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr v-if="input.fasilitas == 'Ya'">
                <td class="td-popri">
                  <span>&nbsp;</span>
                </td>
                <td class="td-popri">
                    <span>
                        Kipas Angin / AC
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.kipas"
                        value="Ya"
                        name="kipas"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.kipas"
                        value="Tidak"
                        name="kipas"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr v-if="input.fasilitas == 'Ya'">
                <td class="td-popri">
                  <span>&nbsp;</span>
                </td>
                <td class="td-popri">
                    <span>
                        Westafel
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.wastafel"
                        value="Ya"
                        name="wastafel"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.wastafel"
                        value="Tidak"
                        name="wastafel"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr v-if="input.fasilitas == 'Ya'">
                <td class="td-popri">
                  <span>&nbsp;</span>
                </td>
                <td class="td-popri">
                    <span>
                        Lemari Pasien
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.lemaripasien"
                        value="Ya"
                        name="lemari"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.lemaripasien"
                        value="Tidak"
                        name="lemari"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr v-if="input.fasilitas == 'Ya'">
                <td class="td-popri">
                  <span>&nbsp;</span>
                </td>
                <td class="td-popri">
                    <span>
                        Televisi
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.televisi"
                        value="Ya"
                        name="televisi"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.televisi"
                        value="Tidak"
                        name="televisi"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr v-if="input.fasilitas == 'Ya'">
                <td class="td-popri">
                  <span>&nbsp;</span>
                </td>
                <td class="td-popri">
                    <span>
                        Telepon
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.telepon"
                        value="Ya"
                        name="telepon"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.telepon"
                        value="Tidak"
                        name="telepon"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr v-if="input.fasilitas == 'Ya'">
                <td class="td-popri">
                  <span>&nbsp;</span>
                </td>
                <td class="td-popri">
                    <span>
                        Tempat Sampah
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.sampah"
                        value="Ya"
                        name="sampah"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.sampah"
                        value="Tidak"
                        name="sampah"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr v-if="input.fasilitas == 'Ya'">
                <td class="td-popri">
                  <span>&nbsp;</span>
                </td>
                <td class="td-popri">
                    <span>
                        Kotak Saran / Kepuasan Pelanggan
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.kotaksaran"
                        value="Ya"
                        name="kotaksaran"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.kotaksaran"
                        value="Tidak"
                        name="kotaksaran"
                        color="danger"
                        square
                    />
                </td>
              </tr>

              <tr>
                <td class="td-popri">
                  <span>11</span>
                </td>
                <td class="td-popri">
                    <span>
                        Layanan Pengaduan
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.pengaduan"
                        value="Ya"
                        name="pengaduan"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.pengaduan"
                        value="Tidak"
                        name="pengaduan"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>12</span>
                </td>
                <td class="td-popri">
                    <span>
                        Keadaan Darurat / Jalur Evakuasi
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.keadaandarurat"
                        value="Ya"
                        name="keadaandarurat"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.keadaandarurat"
                        value="Tidak"
                        name="keadaandarurat"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>13</span>
                </td>
                <td class="td-popri">
                    <span>
                        Larangan Merokok Bagi Pengunjung dan Pasien
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.laranganrokok"
                        value="Ya"
                        name="laranganmerokok"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.laranganrokok"
                        value="Tidak"
                        name="laranganmerokok"
                        color="danger"
                        square
                    />
                </td>
              </tr>
              <tr>
                <td class="td-popri">
                  <span>14</span>
                </td>
                <td class="td-popri">
                    <span>
                        Menjelaskan Enam (6) Langkah Cuci Tangan
                    </span>
                </td>
                <td class="td-popri leftx" style="text-align: center;">
                    <VRadio
                        v-model="input.langkahcuci"
                        value="Ya"
                        name="langkahcuci"
                        color="success"
                        square
                    />
                </td>
                <td class="td-popri rightx" style="text-align: center;">
                    <VRadio
                        v-model="input.langkahcuci"
                        value="Tidak"
                        name="langkahcuci"
                        color="danger"
                        square
                    />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </VCard>
                  <div class="columns">
                    <div class="column is-1"></div>
                    <div class="column is-4" style="text-align:center;">
                      <span style="font-weight: bold;">Penerima Orientasi <br>
                        (Orientation Receiver)</span> <br>
                        <TandaTangan :elemenID="'TTDPenerima'" :width="'150'" :height="'150'" class="dek" style="margin-top: 2rem;"/>
                        <VField style="margin-top: 10px;">
                          <VControl>
                            <VTextarea v-model="input.penerimaOrientasi" placeholder="" rows="1">
                            </VTextarea>
                          </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                    </div>
                    <div class="column is-4">
                          <div class="column" style="text-align:center;">
                            <span style="font-weight: bold;">Pemberi Orientasi <br>
                              (Orientation Giver)</span> <br>
                              <TandaTangan :elemenID="'TTDPemberi'" :width="'150'" :height="'150'" class="dek" style="margin-top: 2rem;"/>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input.CBBidan" :suggestions="d_Petugas"
                              @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                              :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              class="mt-2" />
                            </VControl>
                          </div>
                        </div>
                      <div class="column is-1"></div>
                    </div>
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
  import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
  import Fieldset from 'primevue/fieldset';
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
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
  const { y } = useWindowScroll()
  const isStuck = computed(() => { return y.value > 30 })
  const isLoading: any = ref(false)
  const d_Obat: any = ref([])
  const d_Pegawai: any = ref([])
  const d_Dokter: any = ref([])
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
  })
  const COLLECTION: any = ref(props.COLLECTION) //table mongodb
  const NOREC_EMRPASIEN: any = ref('')
  const peraturan:any = ref(false);
  const input: any = ref({})
  const dataTTD: any = ref([])
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
          dataTTD.value = response[0]
          console.log(dataTTD.value)
          H.tandaTangan().set('TTDPenerima', dataTTD.value.TTDPenerima)
          H.tandaTangan().set('TTDPemberi', dataTTD.value.TTDPemberi)
        }
      })
  }
  
  const d_Ruangan: any = ref([])
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}

const d_Petugas = ref([]);

const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiPerawat&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

  const fetchDokter = async (filter: any) => {
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
      d_Dokter.value = response
    })
  }

  const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
  
    let object: any = {}
  
    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDPenerima'] = H.tandaTangan().get('TTDPenerima')
    object['TTDPemberi'] = H.tandaTangan().get('TTDPemberi')
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
  
  
  const kembaliKeun = () => {
    window.history.back()
  }
  const setAutoFill = async () => {
  
  }
  setView()
  loadRiwayat()
  </script>
  
  <style lang="scss">
  .table-popri {
    width: 80%;
    border: 1px solid black;
  }
  
  .th-popri {
    text-align: center !important;
  }
  
  .th-popri,
  .td-popri {
    padding: 2px;
    border: 1px solid black;
    vertical-align: inherit;
  }

  .td-popri span{
    color: black;
  }

  .leftx{
    background-color: green;
  }

  .rightx{
    background-color: red;
  }
  
  .setpopri-center {
    text-align: center !important;
  }
  
  .p-fieldset-legend {
    margin-left: 15px;
  }
  </style>
  