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
      <div class="column">
        <h1>Kode Sumber Data</h1>
      </div>
      <div class="column">
        <Fieldset :toggleable="true" legend="PENDERITA">
          <div class="column is-5">
            <span class="label-peso">Nama Penderita</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.namaPenderita" />
              </VControl>
            </VField>
          </div>
          <div class="columns is-multiline pl-3 pr-3 pt-3">
            <div class="column is-3">
              <span class="label-peso">Umur</span>
              <VField class="pt-3">
                <VControl>
                  <VInput type="text" v-model="input.umur" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <span class="label-peso">Suku</span>
              <VField class="pt-3">
                <VControl>
                  <VInput type="text" v-model="input.suku" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <span class="label-peso">Berat Badan</span>
              <VField class="pt-3">
                <VControl>
                  <VInput type="text" v-model="input.beratBadan" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <span class="label-peso">Pekerjaan</span>
              <VField class="pt-3">
                <VControl>
                  <VInput type="text" v-model="input.pekerjaan" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column">
            <span class="label-peso">Jenis Kelamin</span>
            <div class="columns is-multiline pl-3 pr-3 pt-3">
              <div class="column" v-for="(data) in jenisKelamin">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jenisKelamin" :true-value="data.code" class="p-0" :label="data.label"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-5">
            <span class="label-peso">Penyakit Utama</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.penyakitUtama" rows="2">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column">
            <span class="label-peso">Kesudahan Penyakit Utama</span>
            <div class="columns is-multiline pl-3 pr-3 pt-3">
              <div class="column" v-for="(data) in kesudahanPenyakit">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.kesudahanPenyakit" :true-value="data.code" class="p-0" :label="data.label"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column">
            <span class="label-peso">Penyakit / kondisi lain yang menyertai</span>
            <div class="columns is-multiline pl-3 pr-3 pt-3">
              <div class="column is-4" v-for="(data) in kondisiMenyertai">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.kesudahanPenyakit" :true-value="data.code" class="p-0" :label="data.label"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.ketKesudahanPenyakit" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

        </Fieldset>
      </div>

      <div class="column">
        <Fieldset :toggleable="true" legend="EFEK SAMPING OBAT">
          <div class="column">
            <div class="columns is-multilin pt-3">
              <div class="column pb-1" v-for="(data) in efekSampingObat">
                <span class="label-peso">{{ data.label }}</span>
                <VField class="pt-3">
                  <VControl>
                    <VTextarea v-model="input[data.model]" rows="2">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column">
            <div class="columns is-multiline pt-3 pb-0">
              <div class="column is-4 pb-0">
                <span style="font-weight: 500;">Saat/Tanggal Mula Terjadi</span>
                <VField class="column is-8 p-0 mt-3">
                  <VDatePicker v-model="input.tglMulai" mode="dateTime" style="width: 100%" trim-weeks
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
              </div>
              <div class="column is-3 pb-0">
                <span style="font-weight: 500;">Kesudahan ESO</span>
                <VDatePicker v-model="input.tglKesudahan" mode="dateTime" class="pt-3" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
            </div>
          </div>

          <div class="column">
            <div class="columns is-multiline pl-3 pr-3 pb-2">
              <div class="column" v-for="(data) in kondisi">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.kondisiKesudahan" :true-value="data.code" class="p-0" :label="data.label"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-7">
            <span class="label-peso">Riwayat ESO yang Pernah Dialami</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.riwayatEso" rows="2">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </Fieldset>
      </div>

      <div class="column">
        <Fieldset :toggleable="true" legend="OBAT">
          <div class="column mb-5" style="overflow: auto;">
            <table class="table-peso">
              <thead>
                <tr>
                  <th rowspan="2" class="th-peso">Nama Dagang/Generik/Industri Farmasi</th>
                  <th rowspan="2" class="th-peso">Bentuk Sediaan</th>
                  <th rowspan="2" class="th-peso">Obat JKN</th>
                  <th rowspan="2" class="th-peso">No. Bets</th>
                  <th rowspan="2" class="th-peso">Obat yang Dicurigai</th>
                  <th colspan="4" class="th-peso">Pemberian</th>
                  <th rowspan="2" class="th-peso">Indikasi Penggunaan</th>
                  <th rowspan="2" class="th-peso">#</th>
                </tr>
                <tr>
                  <th class="th-peso">Cara</th>
                  <th class="th-peso">Dosis/Waktu</th>
                  <th class="th-peso">Tgl Mulai</th>
                  <th class="th-peso">Tgl Akhir</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in input.details" :key="index">
                  <td class="td-peso">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="item.namaGenerik" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-peso">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="item.bentukSediaan" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-peso" style="text-align:center">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="item.isObatJKN" true-value="Ya" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-peso">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="item.noBets" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-peso">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="item.obatDicurigai" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-peso">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="item.cara" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-peso">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="item.dosWaktu" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-peso">
                    <VDatePicker v-model="item.tglMulaiPemberian" mode="dateTime" style="width: 100%" trim-weeks
                      :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </td>
                  <td class="td-peso">
                    <VDatePicker v-model="item.tglSelesaiPemberian" mode="dateTime" style="width: 100%" trim-weeks
                      :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </td>
                  <td class="td-peso">
                    <VField>
                      <VControl>
                        <VTextarea v-model="item.indikasiPenggunaan" rows="2">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-peso">
                    <VButtons style="justify-content:space-around">
                      <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                        v-tooltip.bubble="'Tambah '">
                      </VIconButton>
                      <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                        @click="removeItem(index)" color="danger">
                      </VIconButton>
                    </VButtons>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column">
                <span class="label-peso">Keterangan Tambahan</span>
                <VField class="pt-3">
                  <VControl>
                    <VTextarea v-model="input.ketTambahan" rows="2">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
              <div class="column">
                <span class="label-peso">Data Laboratorium (Bila Ada)</span>
                <VField class="pt-3">
                  <VControl>
                    <VTextarea v-model="input.dataLab" rows="2">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-6">
            <div class="columns is-multiline">
              <div class="column">
                <span class="label-peso">Tgl Pemeriksaan</span>
                <VField class="pt-3">
                  <VDatePicker v-model="input.tglPemeriksaan" mode="dateTime" style="width: 100%" trim-weeks
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
              </div>

              <div class="column">
                <span class="label-peso">Bandung</span>
                <VField class="pt-3">
                  <VDatePicker v-model="input.tglDibuat" mode="dateTime" style="width: 100%" trim-weeks
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
              </div>
            </div>
          </div>

          <div class="column is-6" style="text-align: center;">
            <TandaTangan :elemenID="'signaturePetugas'" :width="'180'" :height="'180'" class="dek" />
          </div>
          <div class="column is-6">
            <span style="font-weight: 500;">Pelapor</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.pelapor" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Petugas..." @item-select="setTandaTanganPetugas($event)" />
              </VControl>
            </VField>
          </div>
        </Fieldset>
      </div>

      <div class="column">
        <Fieldset :toggleable="true" legend="PENGIRIM">
          <div class="column is-5">
            <span class="label-peso">Nama</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.namaPengirim" />
              </VControl>
            </VField>
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-3">
                <span class="label-peso">Keahlian</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.keahlianPengirim" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-5">
                <span class="label-peso">Instansi</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.InstansiPengirim" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-5">
                <span class="label-peso">Alamat</span>
                <VField class="pt-3">
                  <VControl>
                    <VTextarea v-model="input.alamatPengirim" rows="2">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <span class="label-peso">Nomor Telepon</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.noTelp" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

        </Fieldset>
      </div>

      <div class="column">
        <Fieldset :toggleable="true" legend="PENJELASAN">
          <div class="column">
            <div class="columns is-multiline" v-for="(data) in penjelasan">
              <div class="column is-1" style="text-align: center;">
                <span class="label-peso">{{ data.no }}</span>
              </div>
              <div class="column">
                <span class="label-peso">{{ data.desc }}</span>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

      <div class="column">
        <Fieldset :toggleable="true" legend="ALGORITMA NARANJO">
          <div class="column" style="overflow: auto;">
            <table class="table-skorPeso">
              <thead>
                <tr>
                  <th rowspan="2" class="th-peso" width="3%">No</th>
                  <th rowspan="2" class="th-peso" width="35%">Pertanyaan/Questions</th>
                  <th colspan="3" class="th-peso" width="15%">Scale</th>
                </tr>
                <tr>
                  <th class="th-peso">Ya</th>
                  <th class="th-peso">Tidak</th>
                  <th class="th-peso">Tidak diketahui</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data) in algoritmaNaranjo">
                  <td class="td-peso" style="text-align:center"><span class="label-peso">{{ data.no }}</span></td>
                  <td class="td-peso"><span class="label-peso">{{ data.pertanyaan }}</span></td>
                  <td class="td-peso" style="text-align:center;">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[data.scaleYes.model]" :true-value="data.scaleYes.value" class="p-0"
                          :label="data.scaleYes.label" color="primary" squere />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-peso" style="text-align:center;">


                  </td>
                  <td class="td-peso" style="text-align:center;">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[data.scaleUnknown.model]" :true-value="data.scaleUnknown.value"
                          class="p-0" :label="data.scaleUnknown.label" color="primary" squere />
                      </VControl>
                    </VField>
                  </td>
                </tr>
                <tr>
                  <td class="td-peso" colspan="2" style="text-align:center">
                    <span class="label-peso">TOTAL SCORE</span>
                  </td>
                  <td class="td-peso">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="input.skorYa" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-peso">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="input.skorNo" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-peso">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="input.skorUnknown" />
                      </VControl>
                    </VField>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </Fieldset>
      </div>

      <div class="column">
        <h1 style="font-weight: bold;">NARANJO PROBABILITY SCALE</h1>
        <div class="columns is-multiline pl-5 pt-5 pr-5">
          <div class="column is-1 p-0">
            <span class="label-peso">Score</span>
          </div>
          <div class="column is-2 p-0">
            <span class="label-peso">Category</span>
          </div>
        </div>
        <div class="columns is-multiline pl-5 pt-3 pr-5" v-for="(data) in descScore">
          <div class="column is-1 p-0">
            <span>{{ data.score }}</span>
          </div>
          <div class="column is-2 p-0">
            <span>{{ data.category }}</span>
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
import * as EMR from '../page-emr-plugins/pelaporan-efek-samping-obat'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import { kesadaran } from '../page-emr-plugins/asesmen-keperawatan-igd'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let jenisKelamin = ref(EMR.jenisKelamin())
let kesudahanPenyakit = ref(EMR.kesudahanPenyakit())
let kondisiMenyertai = ref(EMR.kondisiMenyertai())
let efekSampingObat = ref(EMR.efekSampingObat())
let kondisi = ref(EMR.kondisi())
let penjelasan = ref(EMR.penjelasan())
let algoritmaNaranjo = ref(EMR.algoritmaNaranjo())
let descScore = ref(EMR.descScore())

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
const input: any = ref({
  details: [{
    no: 1,
  }],
})
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
          H.tandaTangan().set("signaturePetugas", response[0].ttdPegawai)
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
  object.ttdPegawai = H.tandaTangan().get("signaturePetugas")
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

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.namaPenderita = props.pasien.namapasien
  input.value.umur = props.pasien.umur
  input.value.suku = props.pasien.suku
  input.value.pekerjaan = props.pasien.pekerjaan

  await useApi().get(
    "emr/auto-fill?nocmfk=" + ID_PASIEN +
    "&collection=VitalSign" +
    "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {

    input.value.beratBadan = response ? response.beratBadan : ''
  })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const setTandaTanganPetugas = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signaturePetugas", element.ttd)
      }else{
        H.tandaTangan().set("signaturePetugas", '')
      }
    })
}

setView()
setAutoFill()
loadRiwayat()


watch(() => [
  input.value.adaLaporan_Y,
  input.value.adaLaporan_N,
  input.value.adaLaporan_U,
  input.value.terjadiESO_Y,
  input.value.terjadiESO_N,
  input.value.terjadiESO_U,
  input.value.efekSampingMembaik_Y,
  input.value.efekSampingMembaik_N,
  input.value.efekSampingMembaik_U,
  input.value.efekSampingBerulang_Y,
  input.value.efekSampingBerulang_N,
  input.value.efekSampingBerulang_U,
  input.value.alternatifPenyebab_Y,
  input.value.alternatifPenyebab_N,
  input.value.alternatifPenyebab_U,
  input.value.munculKembaliESO_Y,
  input.value.munculKembaliESO_N,
  input.value.munculKembaliESO_U,
  input.value.terdeteksiDD_Y,
  input.value.terdeteksiDD_N,
  input.value.terdeteksiDD_U,
  input.value.efekSampingBP_Y,
  input.value.efekSampingBP_N,
  input.value.efekSampingBP_U,
  input.value.pernahMengalami_Y,
  input.value.pernahMengalami_N,
  input.value.pernahMengalami_U,
  input.value.efekSampingDK_Y,
  input.value.efekSampingDK_N,
  input.value.efekSampingDK_U,
  // input.value.point2
], () => {
  let poin1Y = input.value.adaLaporan_Y ? parseInt(input.value.adaLaporan_Y.poin) : 0
  let poin1N = input.value.adaLaporan_N ? parseInt(input.value.adaLaporan_N.poin) : 0
  let poin1U = input.value.adaLaporan_U ? parseInt(input.value.adaLaporan_U.poin) : 0
  let poin2Y = input.value.terjadiESO_Y ? parseInt(input.value.terjadiESO_Y.poin) : 0
  let poin2N = input.value.terjadiESO_N ? parseInt(input.value.terjadiESO_N.poin) : 0
  let poin2U = input.value.terjadiESO_U ? parseInt(input.value.terjadiESO_U.poin) : 0
  let poin3Y = input.value.efekSampingMembaik_Y ? parseInt(input.value.efekSampingMembaik_Y.poin) : 0
  let poin3N = input.value.efekSampingMembaik_N ? parseInt(input.value.efekSampingMembaik_N.poin) : 0
  let poin3U = input.value.efekSampingMembaik_U ? parseInt(input.value.efekSampingMembaik_U.poin) : 0
  let poin4Y = input.value.efekSampingBerulang_Y ? parseInt(input.value.efekSampingBerulang_Y.poin) : 0
  let poin4N = input.value.efekSampingBerulang_N ? parseInt(input.value.efekSampingBerulang_N.poin) : 0
  let poin4U = input.value.efekSampingBerulang_U ? parseInt(input.value.efekSampingBerulang_U.poin) : 0
  let poin5Y = input.value.alternatifPenyebab_Y ? parseInt(input.value.alternatifPenyebab_Y.poin) : 0
  let poin5N = input.value.alternatifPenyebab_N ? parseInt(input.value.alternatifPenyebab_N.poin) : 0
  let poin5U = input.value.alternatifPenyebab_U ? parseInt(input.value.alternatifPenyebab_U.poin) : 0
  let poin6Y = input.value.munculKembaliESO_Y ? parseInt(input.value.munculKembaliESO_Y.poin) : 0
  let poin6N = input.value.munculKembaliESO_N ? parseInt(input.value.munculKembaliESO_N.poin) : 0
  let poin6U = input.value.munculKembaliESO_U ? parseInt(input.value.munculKembaliESO_U.poin) : 0
  let poin7Y = input.value.terdeteksiDD_Y ? parseInt(input.value.terdeteksiDD_Y.poin) : 0
  let poin7N = input.value.terdeteksiDD_N ? parseInt(input.value.terdeteksiDD_N.poin) : 0
  let poin7U = input.value.terdeteksiDD_U ? parseInt(input.value.terdeteksiDD_U.poin) : 0
  let poin8Y = input.value.efekSampingBP_Y ? parseInt(input.value.efekSampingBP_Y.poin) : 0
  let poin8N = input.value.efekSampingBP_N ? parseInt(input.value.efekSampingBP_N.poin) : 0
  let poin8U = input.value.efekSampingBP_U ? parseInt(input.value.efekSampingBP_U.poin) : 0
  let poin9Y = input.value.pernahMengalami_Y ? parseInt(input.value.pernahMengalami_Y.poin) : 0
  let poin9N = input.value.pernahMengalami_N ? parseInt(input.value.pernahMengalami_N.poin) : 0
  let poin9U = input.value.pernahMengalami_U ? parseInt(input.value.pernahMengalami_U.poin) : 0
  let poin10Y = input.value.efekSampingDK_Y ? parseInt(input.value.efekSampingDK_Y.poin) : 0
  let poin10N = input.value.efekSampingDK_N ? parseInt(input.value.efekSampingDK_N.poin) : 0
  let poin10U = input.value.efekSampingDK_U ? parseInt(input.value.efekSampingDK_U.poin) : 0

  const jumlahNilai_Y = poin1Y + poin2Y + poin3Y + poin4Y + poin5Y + poin6Y + poin7Y + poin8Y + poin9Y + poin10Y
  const jumlahNilai_N = poin1N + poin2N + poin3N + poin4N + poin5N + poin6N + poin7N + poin8N + poin9N + poin10N
  const jumlahNilai_U = poin1U + poin2U + poin3U + poin4U + poin5U + poin6U + poin7U + poin8U + poin9U + poin10U

  input.value.skorYa = jumlahNilai_Y
  input.value.skorNo = jumlahNilai_N
  input.value.skorUnknown = jumlahNilai_U
})


</script>

<style lang="scss">
.table-peso {
  width: 110%;
  border: 1px solid black;
}

.table-skorPeso {
  width: 100%;
  border: 1px solid black;
}

.th-peso {
  text-align: center !important;
}

.th-peso,
.td-peso {
  padding: 7px;
  vertical-align: inherit;
  border: 1px solid black;
}

.label-peso {
  font-weight: 500;
}
</style>
