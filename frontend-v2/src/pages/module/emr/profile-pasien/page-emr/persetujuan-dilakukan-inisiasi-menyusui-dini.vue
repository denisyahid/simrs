<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Persetujuan Dilakukan Inisiasi Menyusu Dini (IMD)</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="column">
    <div class="form-container">
      <h2>Surat Persetujuan IMD (Inisiasi Menyusu Dini)</h2>
      <p>Saya yang bertanda tangan di bawah ini :</p>
      <div class="input-group">
        <label>Nama:</label>
        <input type="text" v-model="input.nama" style="font-size:14px; width: 400px;" placeholder="Nama lengkap" />
      </div>
      <div class="input-group">
        <label>Tgl. Lahir/Umur:</label>
        <VField class="pt-3" style="width: 400px;">
          <VControl class="prime-auto">
            <!-- Date Picker -->
            <VDatePicker class="pt-3" v-model="input.tgllahir" color="green" mode="datetime">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl>
                    <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VControl>
        </VField>
      </div>
      <div class="input-group">
        <label>Alamat:</label>
        <textarea v-model="input.alamat" style="font-size:14px; width: 400px;" placeholder="Alamat lengkap"></textarea>
      </div>

      <p>Dengan ini menyatakan <b>SETUJU</b> untuk dilakukan IMD (Inisiasi Menyusu Dini) terhadap bayi saya dengan harapan:</p>
      <ul>
        <li>Menurunkan angka mortalitas bayi</li>
        <li>Mempertahankan suhu bayi tetap hangat</li>
        <li>Menenangkan ibu dan bayi serta meregulasi pernafasan dan detak jantung bayi</li>
        <li>Memungkinkan bayi menemukan sendiri payudara dan melihat, sehingga ibu jarang menemukan kesulitan menyusui
        </li>
        <li>Meningkatkan jalinan kasih sayang ibu, ayah, dan bayinya</li>
      </ul>

      <p>Demikian persetujuan ini saya buat dengan penuh kesadaran dan tanpa paksaan.</p>

      <div class="sign-section">
        <div>
          <label>Garut, Tanggal dan jam:</label>
          <VControl class="prime-auto">
            <VDatePicker class="pt-3" v-model="input.tanggalttd" color="green" mode="datetime">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl>
                    <VInput class="input form-datepicker" :value="inputValue" v-on="inputEvents"
                      placeholder="Select date" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VControl>
        </div>
      </div>

      <div class="signature">
        <div>
          <p>Yang Membuat Pernyataan</p>
          <br><br>
          <TandaTangan :elemenID="'TTDYBPernyataan'" :width="'150'" :height="'150'" class="dek" />
        </div>
        <div>
          <p>Saksi dari Rumah Sakit</p>
          <br><br>
          <TandaTangan :elemenID="'TTDSDRSakit'" :width="'150'" :height="'150'" class="dek" />
          <VControl class="prime-auto">
            <AutoComplete v-model="input.saksi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
              :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Saksi" />
            <!-- <AutoComplete v-model="input.CBBidan" :suggestions="d_pegawai" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" class="mt-2" /> -->
          </VControl>
        </div>
      </div>
      <br>
      <br>
      <p>Data Ibu Dan Bayi :</p>
      <div class="input-group">
        <label>Diagnosa Ibu saat MRIS :</label>
        <input type="text" v-model="input.diagnosa" style="font-size:14px; width: 400px;" placeholder="Diagnosa" />
      </div>
      <div class="input-group">
        <label>Jenis Persalinan:</label>
        <input type="text" v-model="input.jenispersalinan" style="font-size:14px; width: 400px;" placeholder="Jenis persalinan" />
      </div>
      <div class="input-group">
        <label>Indikasi (kalau ada):</label>
        <textarea v-model="input.Indikasi" style="font-size:14px;width: 400px;" placeholder="Indikasi"></textarea>
      </div>
      <div class="input-group">
        <label>Bayi lahir tanggal/jam:</label>
        <VControl class="prime-auto">
          <!-- Date Picker -->
          <VDatePicker class="pt-3" v-model="input.bayilahir" color="green" mode="datetime">
            <template #default="{ inputValue, inputEvents }">
              <VField style="width: 400px;">
                <VControl>
                  <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </VControl>
      </div>
      <div>
        <ul>
          <!-- Tangis Section -->
          <li>Tangis:</li>
          <div>
            <VCheckbox v-model="input.kuat" value="kuat" color="primary">Kuat</VCheckbox>
            <VCheckbox v-model="input.merintih" value="merintih" color="primary">Merintih</VCheckbox>
            <VCheckbox v-model="input.sesak" value="sesak" color="primary">Sesak</VCheckbox>
          </div>

          <!-- Gerak Section -->
          <li>Gerak:</li>
          <div>
            <VCheckbox v-model="input.aktif" value="aktif" color="primary">Aktif</VCheckbox>
            <VCheckbox v-model="input.lemah" value="lemah" color="primary">Lemah</VCheckbox>
            <VCheckbox v-model="input.tidak_gerak" value="tidak bergerak" color="primary">Tidak Bergerak</VCheckbox>
          </div>

          <!-- Warna Kulit Section -->
          <li>Warna Kulit:</li>
          <div>
            <VCheckbox v-model="input.kemerahan" value="kemerahan" color="primary">Kemerahan</VCheckbox>
            <VCheckbox v-model="input.pucat" value="pucat" color="primary">Pucat</VCheckbox>
          </div>

          <!-- Kelainan Section -->
          <li>Kelainan:</li>
          <div>
            <textarea v-model="input.kelainan" style="font-size:14px;" placeholder="Kelainan"></textarea>
          </div>
        </ul>
      </div>
      <p>Observasi IMD tanggal:</p>
      <div class="column" style="overflow: auto;">
        <table class="table-lo">
          <thead>
            <tr>
              <th class="th-lo" width="10%">Jam IMD</th>
              <th class="th-lo" width="10%">Jam mulai menghisap</th>
              <th class="th-lo" width="10%">Jam selesai IMD</th>
              <th class="th-lo" width="10%">Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="td-lo">
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <!-- Date Picker -->
                    <VDatePicker class="pt-3" v-model="input.jamimd" color="green" mode="datetime">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl>
                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VControl>
                </VField>
              </td>
              <td class="td-lo">
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <!-- Date Picker -->
                    <VDatePicker class="pt-3" v-model="input.mulaihisap" color="green" mode="datetime">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl>
                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VControl>
                </VField>
              </td>
              <td class="td-lo">
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <!-- Date Picker -->
                    <VDatePicker class="pt-3" v-model="input.jamselesaiimd" color="green" mode="datetime">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl>
                            <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VControl>
                </VField>
              </td>
              <td class="td-lo">
                <VField class="p-3">
                  <VControl>
                    <textarea v-model="input.keterangan" style="font-size:14px; " placeholder="Keterangan"></textarea>
                  </VControl>
                </VField>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="sign-section">
          <div>
            <label>Garut:</label>
            <VControl class="prime-auto">
              <VDatePicker class="pt-3" v-model="input.tanggalttdlast" color="green" mode="date">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl>
                      <VInput class="input form-datepicker" :value="inputValue" v-on="inputEvents"
                        placeholder="Select date" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VControl>
          </div>
        </div>
        <div class="column is-3">
          <p>Perawat/Bidan</p>
          <br>
          <TandaTangan :elemenID="'TTDBidan'" :width="'150'" :height="'150'" class="dek" />
          <VControl class="prime-auto">
            <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
              :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Perawat/Bidan" />
            <!-- <AutoComplete v-model="input.CBBidan" :suggestions="d_pegawai" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" class="mt-2" style="width: 400px;" /> -->
          </VControl>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
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

  const route = useRoute()
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
      COLLECTION: 'PersetujuanDilakukanInisialMenyusuiDini',
    }
  )
  const { y } = useWindowScroll()
  const isStuck = computed(() => { return y.value > 30 })
  const isLoading: any = ref(false)
  const pasien: any = ref({})
  const d_Obat: any = ref([])
  const d_Dokter: any = ref([])
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
  })
  const COLLECTION: any = ref('PersetujuanDilakukanInisialMenyusuiDini') //table mongodb
  const NOREC_EMRPASIEN: any = ref('')
  const input: any = ref({})
  const setView = () => {
    useHead({
      title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
  }

  const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  console.log(props);
  // NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

fetchPasien()

  const dataTTD: any = ref([]);
  const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        if (response.length) {
          input.value = response[0] //set ke inputan
          if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
          }
          dataTTD.value = response[0];
          H.tandaTangan().set("TTDYBPernyataan", dataTTD.value.TTDYBPernyataan);
          H.tandaTangan().set("TTDSDRSakit", dataTTD.value.TTDSDRSakit);
          H.tandaTangan().set("TTDBidan", dataTTD.value.TTDBidan);
        } else {
          let dataPasien = H.setObjectPasien(pasien.value)
          console.log(dataPasien);  
          input.value.nama = dataPasien.namapasien
          input.value.tgllahir = dataPasien.tgllahir
          input.value.alamat = dataPasien.alamatlengkap
        }
      })
  }

  const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDYBPernyataan'] = H.tandaTangan().get("TTDYBPernyataan");
    object['TTDSDRSakit'] = H.tandaTangan().get("TTDSDRSakit");
    object['TTDBidan'] = H.tandaTangan().get("TTDBidan");
    let json = {
      'id': ID,
      'norec_emr': NOREC_EMRPASIEN.value,
      'collection': COLLECTION.value,
      'url_form': route.name,
      'name_form': 'Persetujuan Dilakukan Inisiasi Menyusui Dini',
      'jenis_emr': 'asesmen_medis',
      'data': object
    }
    isLoading.value = true
    console.log(json)
    useApi().post(
      `/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        NOREC_EMRPASIEN.value = response.norec_emr
        loadRiwayat();
        input.value.id = response.id
      }).catch((e: any) => {
        isLoading.value = false
      })
  }
  // const fetchDokter = async (filter: any) => {
  //   await useApi().get(
  //     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  //   ).then((response) => {
  //     d_pegawai.value = response
  //   })
  // }

  const d_Pegawai: any = ref([])
  const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
      `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
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

  const kembaliKeun = () => {
    window.history.back()
  }
  const setAutoFill = async () => {

  }
  setView()
  setAutoFill()
  loadRiwayat()
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

  .form-container {
    max-width: 800px;
    margin: auto;
    padding: 20px;
    border: 2px solid #000;
    border-radius: 10px;
  }

  h2 {
    text-align: center;
    margin-bottom: 20px;
  }

  .input-group {
    margin-bottom: 15px;
  }

  .input-group label {
    display: block;
    font-weight: bold;
  }

  input,
  textarea {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
  }

  textarea {
    resize: vertical;
    height: 80px;
  }

  ul {
    margin: 15px 0;
    padding-left: 20px;
  }

  li {
    margin-bottom: 10px;
  }

  .sign-section {
    display: flex;
    justify-content: space-between;
    margin: 20px 0;
  }

  input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  .signature {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
  }

  .signature div {
    text-align: center;
  }

  .signature-line {
    border-bottom: 1px solid #000;
    width: 200px;
    margin: 20px auto;
  }
</style>