<template>
  <div class="form-layout is-stacked">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Form Insiden Internal</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="back()">
                Kembali
              </VButton>
              <VButton type="submit" color="primary" raised icon="feather:save" :loading="isLoadBtnSave" @click="simpan()" :disabled="isDisabled" v-if="!isInfo">
                Save
              </VButton>
            </div>
          </div>
        </div>
      </div>

      <div class="form-body p-2">
        <div class="business-dashboard hr-dashboard">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="block-header" v-if="isLoadData">
                <VPlaceload height="100px" width="100%" class="mx-2" />
              </div>
              <div class="block-header" v-else>
                <div class="left">
                  <div class="current-user">
                    <VAvatar size="medium" :picture="pasien.jeniskelamin == 'PEREMPUAN'
                      ? '/images/avatars/svg/vuero-4.svg'
                      : '/images/avatars/svg/vuero-1.svg'
                      " squared />
                    <h3>{{ pasien.namapasien }}</h3>
                  </div>
                </div>
                <div class="center">
                  <div class="columns">
                    <div class="column">
                      <h4 class="block-heading">No RM</h4>
                      <p class="block-text">{{ pasien.nocm }}</p>
                      <h4 class="block-heading">Tgl Lahir</h4>
                      <p class="block-text">{{ pasien.tgllahir }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">NIK</h4>
                      <p class="block-text">{{ pasien.noidentitas }}</p>
                      <h4 class="block-heading">Kelamin</h4>
                      <p class="block-text">{{ pasien.jeniskelamin }}</p>
                    </div>
                  </div>
                </div>
                <div class="right">
                  <div class="columns">
                    <div class="column is-8">
                      <h4 class="block-heading">Kelompok Pasien</h4>
                      <p class="block-text">{{ item.kelompokpasien }}</p>
                      <h4 class="block-heading">Ruangan</h4>
                      <p style="color:white">{{ item.namaruangan }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">Umur</h4>
                      <VTag color="orange" :label="pasien.umur" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="form-body pt-2">
    <VCard v-if="isLoadData">
      <VPlaceloadText :lines="100" width="100%" last-line-width="25%" />
    </VCard>
    <VCard v-else>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-3">
            <h1 style="font-weight: bold;" class="mb-2">Tanggal & waktu Insiden</h1>
            <VField>
              <VDatePicker v-model="item.waktuKejadian" mode="dateTime" style="width: 100%" trim-weeks
                :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" :disabled="isInfo"/>
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>

          <div class="column is-5">
            <h1 style="font-weight: bold;" class="mb-2">Keselamatan</h1>
            <VField class=" is-autocomplete-select">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.keselamatanfk" :options="d_KeselamatanReal"
                  placeholder="Pilih Keselamatan" :searchable="true" :class="isInfo ? 'bold' : ''" @select="getInit(item.keselamatanfk)" :disabled="isInfo" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;" class="mb-2">Jenis Keselamatan</h1>
            <VField>
              <VControl icon="feather:search">
                <Dropdown v-model="item.jeniskeselamatanfk" :options="d_JenisKeselamatan" optionLabel="label"
                  placeholder="Jenis Keselamatan" style="width: 100%; font-weight:bold" :filter="true" disabled
                  appendTo="body" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12 pt-0">
        <h1 style="font-weight: bold;" class="mb-2">Insiden</h1>
        <VField>
          <VControl>
            <VTextarea v-model="item.insiden" rows="3" :disabled="isInfo">
            </VTextarea>
          </VControl>
        </VField>
      </div>

      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Jenis Insiden</h1>
        <div class="columns is-multiline">
          <div class="column is-4" v-for="(jenis, i) in sourceJenisKeselamatan">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="item.jenis" :true-value="jenis.id" :label="jenis.jeniskesalamatan" class="p-0"
                  color="primary" circle :disabled="isInfo"/>
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Orang Pertama Yang Melaporkan Insiden</h1>
        <div class="columns is-multiline">
          <div class="column is-6" v-for="(pelopor, i) in listPelapor">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="item.pelopor" :true-value="pelopor.id" :label="pelopor.nama" class="p-0"
                  color="primary" circle :disabled="isInfo" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Lokasi Insiden</h1>
        <VField>
          <VControl>
            <VTextarea v-model="item.lokasiInsiden" rows="3" :disabled="isInfo">
            </VTextarea>
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Insiden Menyangkut Pasien</h1>
        <div class="columns is-multiline">
          <div class="column is-6" v-for="(insiden, i) in listInsidenPasien">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="item.insidenPasien" :true-value="insiden.id" :label="insiden.nama" class="p-0"
                  color="primary" circle :disabled="isInfo"/>
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Insiden terjadi pada pasien : ( jiwa dan sub spesialisasnya)
        </h1>
        <div class="columns is-multiline">
          <div class="column is-6" v-for="(jiwa, i) in listJiwa">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="item.jiwa" :true-value="jiwa.id" :label="jiwa.nama" class="p-0" color="primary"
                  circle />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Unit Kerja Penyebab</h1>
        <VField>
          <VControl>
            <VTextarea v-model="item.unitKerjaPenyebab" rows="3" :disabled="isInfo">
            </VTextarea>
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Akibat Insiden Terhadap Pasien</h1>
        <div class="columns is-multiline">
          <div class="column is-6" v-for="(akibatInsiden, i) in listAkibatInsiden">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="item.akibatInsiden" :true-value="akibatInsiden.id" :label="akibatInsiden.nama"
                  class="p-0" color="primary" circle :disabled="isInfo"/>
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Tindakan yang Dilakukan Segera Setelah Kejadian, dan hasilnya
        </h1>
        <VField>
          <VControl>
            <VTextarea v-model="item.setelahKejadian" rows="3" :disabled="isInfo">
            </VTextarea>
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Apakah Kejadian yang sama pernah terjadi di Unit Kerja Lain?
        </h1>
        <div class="columns is-multiline">
          <div class="column is-4" v-for="(akibatKejadian, i) in listAkibatKejadian">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="item.akibatKejadian" :true-value="akibatKejadian.id" :label="akibatKejadian.nama"
                  class="p-0" color="primary" circle :disabled="isInfo" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Tindakan dilakukan oleh</h1>
        <div class="columns is-multiline">
          <div class="column is-4" v-for="(pelakuTindakan, i) in listPelakuTindakan">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="item.pelakuTindakan" :true-value="pelakuTindakan.id" :label="pelakuTindakan.nama"
                  class="p-0" color="primary" circle :disabled="isInfo" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Kapan dan Langkah apa yang telah diambil pada Unit kerja
          Tersebut
          untuk mencegah terulangnya kejadiannya yang sama</h1>
        <VField>
          <VControl>
            <VTextarea v-model="item.pencegah" rows="3" :disabled="isInfo">
            </VTextarea>
          </VControl>
        </VField>
      </div>

      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h1 style="font-weight: bold;" class="mb-2">Pembuat Laporan</h1>
            <VField>
              <VControl class="prime-auto" >
                <AutoComplete v-model="item.pembuatLaporan" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Pegawai..." :disabled="isInfo" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;" class="mb-2">Tanggal Laporan</h1>
            <VField>
              <VDatePicker v-model="item.tglLaporan" mode="dateTime" style="width: 100%" trim-weeks
                :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" :disabled="isInfo"/>
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h1 style="font-weight: bold;" class="mb-2">Penerima Laporan (Ka.RU/Ka.Ins)</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="item.penerimaLaporan" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Pegawai..." :disabled="isInfo" :class="isInfo ? 'bold' : ''"  />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;" class="mb-2">Tanggal Terima</h1>
            <VField>
              <VDatePicker v-model="item.tglTerima" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" :disabled="isInfo"/>
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Grading Risiko Kejadian (Diisi oleh atasan pelapor)</h1>
        <div class="columns is-multiline">
          <div class="column is-3" v-for="(gradingResiko, i) in listGradingResiko">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="item.gradingResiko" :true-value="gradingResiko.id" :label="gradingResiko.nama"
                  class="p-0" color="primary" circle :disabled="isInfo"/>
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </VCard>
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
import { useToaster } from '/@src/composable/toaster'
import { async } from '@firebase/util';
import Calendar from 'primevue/calendar';

useHead({
  title: 'Form Insiden Internal - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let NOREC_PD = useRoute().query.norec_pd as string
let NOCMFK = useRoute().query.nocmfk as string
let NOREC_LII = useRoute().query.norec_lii as string
let INFO = useRoute().query.info as string
let pasien: any = ref({})

const isDisabled: any = ref(false)
const isInfo: any = ref(INFO == 'detail' ? true : false)
const isLoadData: any = ref(true)
const isLoadBtnSave: any = ref(false)
const d_KeselamatanReal: any = ref([])
const d_Keselamatan: any = ref([])
const d_Pegawai: any = ref([])
const d_JenisKeselamatan: any = ref([])
const sourceJenisKeselamatan: any = ref([])
const item: any = reactive({
  waktuKejadian: new Date(),
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
})
const dataSourceRiwayat: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
let listColor: any = ref(Object.keys(useThemeColors()))

const listPelapor = [
  { "id": 1, "nama": "Karyawan : Dokter / Perawat / Petugas Lainnya" },
  { "id": 2, "nama": "Keluarga / Pendamping Pasien" },
  { "id": 3, "nama": "Pasien" },
  { "id": 4, "nama": "Pengunjung" },
  { "id": 5, "nama": "Lain-lain" }
];

const listInsidenPasien = [
  { "id": 1, "nama": "Pasien Rawat Inap" },
  { "id": 2, "nama": "Pasien Rawat Jalan" },
  { "id": 3, "nama": "Pasien IGD" },
  { "id": 4, "nama": " Lain-lain" },
];

const listJiwa = [
  { "id": 1, "nama": "Anak Remaja" },
  { "id": 2, "nama": "Napza" },
  { "id": 3, "nama": "Dewasa" },
  { "id": 4, "nama": "Lansia" },
  { "id": 5, "nama": "GMO" },
  { "id": 6, "nama": "ELektromedik" },
  { "id": 7, "nama": "  Lain-lain" },
];

const listAkibatInsiden = [
  { "id": 1, "nama": "Kematian" },
  { "id": 2, "nama": "Cedera Irreversibe / Cedera Berat" },
  { "id": 3, "nama": "Cedera Reversibel / Cedera Sedang" },
  { "id": 4, "nama": "Cedera Ringan" },
  { "id": 5, "nama": "Tidak Ada Cedera" },
]
const listAkibatKejadian = [
  { "id": 1, "nama": "Ya" },
  { "id": 2, "nama": "Tidak" },
]
const listPelakuTindakan = [
  { "id": 1, "nama": "Tim : Terdiri" },
  { "id": 2, "nama": "Dokter" },
  { "id": 3, "nama": "Petugas Lainnya" },
  { "id": 4, "nama": "Perawat" },
]
const listGradingResiko = [
  { "id": 1, "nama": "BIRU" },
  { "id": 2, "nama": "HIJAU" },
  { "id": 3, "nama": "KUNING" },
  { "id": 4, "nama": "MERAH" },
]


const headerPasien = async () => {
  let response = await useApi().get(`/diagnosa/header-pasien?nocmfk=${NOCMFK}&norec_pd=${item.NOREC_PD}`)
      pasien.value = response.pasien
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.RUANGAN_ASAL = response.pasien.objectruanganasalfk
      item.KELAS_LAST = response.last_registrasi.objectkelasfk
      item.registrasi = response.last_registrasi
      item.namaruangan = response.last_registrasi.namaruangan
      item.nosep = response.last_registrasi.nosep
      item.kelompokpasien = response.last_registrasi.kelompokpasien
      item.kelompokpasienfk = response.last_registrasi.objectkelompokpasienlastfk
      item.namaruangan = response.last_registrasi.namaruangan
      item.tglmasuk = response.last_registrasi.tglmasuk
      item.tglpulang = response.last_registrasi.tglpulang
      item.objectruanganasalfk = response.last_registrasi.objectruanganasalfk
      item.nocm = response.pasien.nocm
}

const fetchDropdown = async () => {
  let response = await useApi().get(`/pmkp/get-data-combo-pmkp`)
  d_KeselamatanReal.value = response.insidenkeselamtanpasien.map((e: any) => {
    return { label: e.keselamatan, value: e.id }
  })
  d_Keselamatan.value = response.insidenkeselamtanpasien.map((e: any) => {
    return { label: e.keselamatan, value: e }
  })
  d_JenisKeselamatan.value = response.jeniskeselamatan.map((e: any) => {
    return { label: e.jeniskesalamatan, value: e.id }
  })
  sourceJenisKeselamatan.value = response.jeniskeselamatan
  isLoadData.value = false
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const getInit = (e: any) => {
  console.log(e)
  let jnskeselamatanfk
  d_Keselamatan.value.forEach((element)=>{
    if(element.value.id == e){
      jnskeselamatanfk = element.value.jeniskesalamatanfk
    }
  })

  d_JenisKeselamatan.value.forEach((element: any) => {
    if (element.value == jnskeselamatanfk) {
      item.jeniskeselamatanfk = element
    }
  });
}

const simpan = async () => {
  if (!item.keselamatanfk) {
    H.alert('error', 'Keselamatan Tidak Boleh Kosong')
    return
  }
  if (!item.waktuKejadian) {
    H.alert('error', 'Waktu Kejadian Tidak Boleh Kosong')
    return
  }
  if (!item.insiden) {
    H.alert('error', 'Insiden Tidak Boleh Kosong')
    return
  }
  if (!item.pembuatLaporan) {
    H.alert('error', 'Pembuat Laporan Tidak Boleh Kosong')
    return
  }
  if (!item.tglLaporan) {
    H.alert('error', 'Tanggal Laporan Tidak Boleh Kosong')
    return
  }
  if (!item.tglTerima) {
    H.alert('error', 'Tanggal Terima Laporan Tidak Boleh Kosong')
    return
  }
  if (!item.penerimaLaporan) {
    H.alert('error', 'Penerima Laporan Tidak Boleh Kosong')
    return
  }

  isLoadBtnSave.value = true

  let objSave = {
    'data': {
      norec: item.norec ? item.norec : '',
      nocm: item.nocm,
      namapasien: pasien.value.namapasien,
      tglahir: pasien.value.tgllahir,
      ruanganfk: item.RUANGAN_LAST,
      umur: pasien.value.umur,
      jeniskelaminfk: pasien.value.objectjeniskelaminfk,
      penanggungbiayapasienfk: item.kelompokpasienfk,
      tglmasuk: item.tglmasuk,
      tglinsiden: H.formatDate(item.waktuKejadian, 'YYYY-MM-DD HH:mm:ss'),
      insiden: item.insiden ? item.insiden : null,
      jenisinsiden: item.jenis ? item.jenis : null,
      pelaporinsiden: item.pelopor ? item.pelopor : null,
      tempatinsiden: item.lokasiInsiden ? item.lokasiInsiden : null,
      insidenterjadi: item.insidenPasien ? item.insidenPasien : null,
      jiwa: item.jiwa ? item.jiwa : null,
      unitterkait: item.unitKerjaPenyebab ? item.unitKerjaPenyebab : null,
      akibatinsiden: item.akibatInsiden ? item.akibatInsiden : null,
      penanganan: item.setelahKejadian ? item.setelahKejadian : null,
      dilakukanoleh: item.pelakuTindakan ? item.pelakuTindakan : null,
      kejadiansama: item.akibatKejadian ? item.akibatKejadian : null,
      langkahpenanganan: item.pencegah ? item.pencegah : null,
      pembuatlaporan: item.pembuatLaporan ? item.pembuatLaporan.label : null,
      pembuatlaporanfk: item.pembuatLaporan ? item.pembuatLaporan.value : null,
      tgllapor: H.formatDate(item.tglLaporan, 'YYYY-MM-DD HH:mm:ss'),
      penerimalaporan: item.penerimaLaporan ? item.penerimaLaporan.label : null,
      penerimalaporanfk: item.penerimaLaporan ? item.penerimaLaporan.value : null,
      tglterima: H.formatDate(item.tglTerima, 'YYYY-MM-DD HH:mm:ss'),
      grading: item.gradingResiko ? item.gradingResiko : null,
      insidenkeselamatanfk: item.keselamatanfk,
      noregistrasifk: item.NOREC_PD,
    }
  }

  await useApi().post('pmkp/simpan-laporan-insiden-internal', objSave).then((response) => {
    isLoadBtnSave.value = false
    isDisabled.value = true
  })
}

const back = () => {
  window.history.back()
}

const loadRiwayat = async ()=>{
  let response = await useApi().get(`pmkp/get-daftar-laporan-insiden-internal?norec=${NOREC_LII}`)
  let data = response.data[0]
  item.keselamatanfk = data.insidenkeselamatanfk
  getInit(item.keselamatanfk)
  item.norec = data.norec
  item.tglmasuk = data.tglmasuk
  item.waktuKejadian = data.tglinsiden
  item.insiden = data.insiden
  item.jenis = data.jenisinsiden
  item.pelopor = data.pelaporinsiden
  item.lokasiInsiden = data.tempatinsiden
  item.insidenPasien = data.insidenterjadi
  item.jiwa = data.jiwa
  item.unitKerjaPenyebab = data.unitterkait
  item.akibatInsiden = data.akibatinsiden
  item.setelahKejadian = data.penanganan
  item.akibatKejadian = data.kejadiansama
  item.pelakuTindakan = data.dilakukanoleh
  item.pencegah = data.langkahpenanganan
  item.pembuatLaporan = { label: data.pembuatlaporan, value: data.pembuatlaporanfk }
  item.tglLaporan = data.tgllapor
  item.penerimaLaporan = {label : data.penerimalaporan , value : data.penerimalaporanfk }
  item.tglTerima = data.tglterima
  item.gradingResiko = data.grading
}

headerPasien()
fetchDropdown()
if(NOREC_LII){
  loadRiwayat()
}

</script>

<style lang="scss" scoped>
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';

.list-view-v1 {
  .list-view-item {
    @include vuero-r-card;

    margin-bottom: 16px;
    padding: 16px;

    .list-view-item-inner {
      display: flex;
      align-items: center;

      .meta-left {
        margin-left: 16px;

        h3 {
          font-family: var(--font-alt);
          color: var(--dark-text);
          font-weight: 600;
          font-size: 1rem;
          line-height: 1;
        }

        >span:not(.tag) {
          font-size: 0.9rem;
          color: var(--light-text);

          svg {
            height: 12px;
            width: 12px;
          }
        }
      }

      .meta-right {
        margin-left: auto;
        display: flex;
        justify-content: flex-end;
        align-items: center;

        .tags {
          margin-right: 30px;
          margin-bottom: 0;

          .tag {
            margin-bottom: 0;
          }
        }

        .stats {
          display: flex;
          align-items: center;
          margin-right: 30px;

          .stat {
            display: flex;
            align-items: center;
            flex-direction: column;
            text-align: center;
            color: var(--light-text);

            >span {
              font-family: var(--font);

              &:first-child {
                font-size: 1.2rem;
                font-weight: 600;
                color: var(--dark-text);
                line-height: 1.4;
              }

              &:nth-child(2) {
                text-transform: uppercase;
                font-family: var(--font-alt);
                font-size: 0.75rem;
              }
            }

            svg {
              height: 16px;
              width: 16px;
            }

            i {
              font-size: 1.4rem;
            }
          }

          .separator {
            height: 25px;
            width: 2px;
            border-right: 1px solid var(--fade-grey-dark-3);
            margin: 0 16px;
          }
        }

        .network {
          display: flex;
          justify-content: flex-end;
          align-items: center;
          min-width: 145px;

          >span {
            font-family: var(--font);
            font-size: 0.9rem;
            color: var(--light-text);
            margin-left: 6px;
          }
        }

        .dropdown {
          margin-left: 30px;
        }
      }
    }
  }
}

.is-dark {
  .list-view-v1 {
    .list-view-item {
      @include vuero-card--dark;

      .list-view-item-inner {
        .meta-left {
          h3 {
            color: var(--dark-dark-text) !important;
          }
        }

        .meta-right {
          .stats {
            .stat {
              span {
                &:first-child {
                  color: var(--dark-dark-text);
                }
              }
            }

            .separator {
              border-color: var(--dark-sidebar-light-16) !important;
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .list-view-v1 {
    .list-view-item {
      .list-view-item-inner {
        position: relative;
        flex-direction: column;

        .v-avatar {
          margin-bottom: 10px;
        }

        .meta-left {
          margin-left: 0;
        }

        .meta-right {
          flex-direction: column;
          margin-left: 0;

          .tags {
            margin: 10px 0;
          }

          .stats {
            margin: 10px 0;
          }

          .network {
            margin: 10px 0 0;
            justify-content: center;

            >span {
              display: none;
            }
          }

          .dropdown {
            position: absolute;
            top: 0;
            right: 0;
            margin-left: 0;
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .list-view-v1 {
    display: flex;
    flex-wrap: wrap;

    .list-view-item {
      margin: 10px;
      width: calc(50% - 20px);

      .list-view-item-inner {
        position: relative;
        flex-direction: column;

        .v-avatar {
          margin-bottom: 10px;
        }

        .meta-left {
          margin-left: 0;
        }

        .meta-right {
          flex-direction: column;
          margin-left: 0;

          .tags {
            margin: 10px 0;
          }

          .stats {
            margin: 10px 0;
          }

          .network {
            margin: 10px 0 0;
            justify-content: center;

            >span {
              display: none;
            }
          }

          .dropdown {
            position: absolute;
            top: 0;
            right: 0;
            margin-left: 0;
          }
        }
      }
    }
  }
}

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item[data-v-4206d2a0]::before {
  content: none;
}

.p-inputtext .p-component {
  border-top-left-radius: 15px;
  border-bottom-left-radius: 15px;
  display: none;
}

.is-rounded-select {
  .p-calendar {
    border-radius: 20px !important;

    .p-inputtext {
      border-top-left-radius: 15px !important;
      border-bottom-left-radius: 15px !important;
    }
  }

  .p-calendar-w-btn .p-datepicker-trigger {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-top-right-radius: 15px !important;
    border-bottom-right-radius: 15px !important;
  }
}

.bold{
  font-weight:bold
}
</style>
