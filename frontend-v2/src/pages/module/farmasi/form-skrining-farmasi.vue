<template>
  <div class="form-layout is-stacked">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Form Skrining Farmasi</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="back()">
                Kembali
              </VButton>
              <VButton type="submit" color="primary" raised icon="feather:save" :loading="isLoadBtnSave" @click="simpan()"
                :disabled="isDisabled" v-if="!isInfo">
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
        <div class="columns is-mulitline">
          <div class="column is-4">
            <h1 style="font-weight: bold;">Rincian</h1>
          </div>
          <div class="column is-3">
            <h1 style="font-weight: bold;">Masalah</h1>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;">Penjelasan / Rekomendasi</h1>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">1. Penulisan Jelas dan Benar</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.penulisan" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.penulisan" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketPenulisan" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">2. Tanggal Resep</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.tglResep" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.tglResep" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketTglResep" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">3. No RM</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.norm" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.norm" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketNoRm" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">4. Nama Pasien</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msNamaPasien" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msNamaPasien" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketNamaPasien" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">5. TGL Lahir</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msTglLahir" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msTglLahir" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketTglLahir" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">6. Berat Badan</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msBeratBadan" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msBeratBadan" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketBeratBadan" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">7. Nama Dokter</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msNamaDok" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msNamaDok" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketNamaDok" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">8. Ruang Periksa</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msRuangPerkisa" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msRuangPerkisa" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketRuangPeriksa" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">9. Status Jaminan</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msStatusJaminan" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msStatusJaminan" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketStatusJaminan" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">10. Nama Obat</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msNamaObat" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msNamaObat" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketNamaObat" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">11. Kekuatan, Dosis</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msKekuatan" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msKekuatan" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketKekuatan" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">12. Jumlah Obat</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msJmlObat" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msJmlObat" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketJmlObat" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">13. Stabilitas</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msStabilitas" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msStabilitas" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketStabilitas" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">14. Aturan/Cara Pakai</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msAturan" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msAturan" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketAturan" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">15. Indikasi</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msIndikasi" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msIndikasi" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketIndikasi" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">16. Alergi</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msAlergi" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msAlergi" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketAlergi" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">17. Konsumsi Obat Lain</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msObatLain" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msObatLain" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketObatLain" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">18. Duplikat Obat</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msDublikatObat" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msDublikatObat" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketDublikatObat" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">19. Interaksi Obat</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msInteraksiObat" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msInteraksiObat" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketInteraksiObat" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">20. Antibiotik (1 > Item)</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msAntibiotik" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msAntibiotik" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketAntibiotik" />
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-4 pb-0">
            <h1 style="font-weight: bold;" class="mb-2">21. Poli Farmasi (5 > item)</h1>
          </div>
          <div class="column is-3 pb-0">
            <VRadio v-model="item.msPoliFarmasi" value="0" label="Ya" color="info" class="pl-0" />
            <VRadio v-model="item.msPoliFarmasi" value="1" label="Tidak" color="primary" />
          </div>
          <div class="column is-5 pb-0">
            <VField>
              <input type="text" class="input" v-model="item.ketPoliFarmasi" />
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField label="Nama Penyekrining Resep">
              <input type="text" class="input" v-model="item.penyekrining" />
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Nama Peracik">
              <input type="text" class="input" v-model="item.namaPeracik" />
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Nama Pengecek">
              <input type="text" class="input" v-model="item.namaPengecek" />
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <h1 style="font-weight: bold;" class="mb-2">Ceklis Prinsip 7 Benar</h1>
        <div class="columns is-multiline">
          <div class="column is-3" v-for="(data) in listCheckList">
            <Checkbox v-model="listPrinsip" :inputId="data.id" :name="data.nama" :value="data.id" />
            <label for="ingredient1" class="ml-2">{{ data.nama }}</label>
            <!-- <VCheckbox v-model="item[data.model]" :true-value="data.id" :label="data.nama" class="p-0" @click=addCek(item[data.model]) color="primary"
                  circle /> -->
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VField label="Nama Penyerah Obat">
              <input type="text" class="input" v-model="item.penyerahObat" />
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Nama Penerima Obat">
              <input type="text" class="input" v-model="item.penerimaObat" />
            </VField>
          </div>
          <div class="column is-5" style="margin-left: 3rem;">
            <h1 style="font-weight: bold;" class="mb-1">Cek All</h1>
            <VRadio v-model="item.cekAll" value="0" label="Tidak Semua" color="info" class="pl-0" />
            <VRadio v-model="item.cekAll" value="1" label="Ada Semua" color="primary" />
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
import Checkbox from 'primevue/checkbox';

useHead({ title: 'Pindah Pulang - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let NOREC_PD = useRoute().query.norec_pd as string
let NOCMFK = useRoute().query.nocmfk as string
let NOREC_RESEP = useRoute().query.strukresep as string
let NOREC_SCRINING = useRoute().query.norec_scrining as string

let pasien: any = ref({})
const listCheckList = [
  { "id": 1, "nama": "Benar Pasien", "model": "bPasein" },
  { "id": 2, "nama": "Benar Indikasi", "model": "bIndikasi" },
  { "id": 3, "nama": "Benar Obat", "model": "bObat" },
  { "id": 4, "nama": "Benar Dosis", "model": "bDosis" },
  { "id": 5, "nama": "Benar Cara Pemberian", "model": "bCaraPemberian" },
  { "id": 6, "nama": "Benar Waktu Pemberian", "model": "bWaktuPemberian" },
  { "id": 7, "nama": "Benar Dokumentasi", "model": "bDokumentasi" },
];

const isLoadData: any = ref(true)
const isDisabled: any = ref(false)
const isLoadBtnSave: any = ref(false)
const d_KeselamatanReal: any = ref([])
const d_Keselamatan: any = ref([])
const listPrinsip: any = ref([])
const d_Pegawai: any = ref([])
const d_JenisKeselamatan: any = ref([])
const sourceJenisKeselamatan: any = ref([])
const item: any = reactive({
  waktuKejadian: new Date(),
  penulisan: '1',
  tglResep: '1',
  norm: '1',
  msNamaPasien: '1',
  msTglLahir: '1',
  msBeratBadan: '1',
  msNamaDok: '1',
  msRuangPerkisa: '1',
  msStatusJaminan: '1',
  msNamaObat: '1',
  msKekuatan: '1',
  msJmlObat: '1',
  msStabilitas: '1',
  msAturan: '1',
  msIndikasi: '1',
  msAlergi: '1',
  msObatLain: '1',
  msDublikatObat: '1',
  msInteraksiObat: '1',
  msAntibiotik: '1',
  msPoliFarmasi: '1',
})
const dataSourceRiwayat: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
let listColor: any = ref(Object.keys(useThemeColors()))

const headerPasien = async () => {
  let response = await useApi().get(`/diagnosa/header-pasien?nocmfk=${NOCMFK}&norec_pd=${NOREC_PD}`)
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
  isLoadData.value = false
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

const simpan = async (e: any) => {

  if (listPrinsip.value.length == 0) {
    H.alert('error', 'Ceklis Prinsip Benar Tidak Boleh Kosong')
    return
  }
  if (!item.cekAll) {
    H.alert('error', 'Cek All Tidak Boleh Kosong')
    return
  }
  let listPrinsipBenar = []
  listPrinsip.value.forEach((element: any) => {
    listPrinsipBenar = [...new Set([...listPrinsipBenar, element])]
  });

  isLoadBtnSave.value = true
  let objSave = {
    "norec": item.norec ? item.norec : '',
    "norec_apd": item.NOREC_APD,
    "objectruanganfk": item.RUANGAN_LAST,
    "rpenulis": item.penulisan,
    "rtanggalresep": item.tglResep,
    "rmr": item.norm,
    "rpasien": item.msNamaPasien,
    "rtanggallahir": item.msTglLahir,
    "rberatbedan": item.msBeratBadan,
    "rdokter": item.msNamaDok,
    "rruang": item.msRuangPerkisa,
    "rstatusjamin": item.msStatusJaminan,
    "robat": item.msNamaObat,
    "rkekuatan": item.msKekuatan,
    "rjumlahobat": item.msJmlObat,
    "rstabilitas": item.msStabilitas,
    "raturan": item.msAturan,
    "rindikasiobat": item.msIndikasi,
    "ralergi": item.msAlergi,
    "rkonsumsi": item.msObatLain,
    "rduplikat": item.msDublikatObat,
    "rinteraksi": item.msInteraksiObat,
    "rantibiotik": item.msAntibiotik,
    "rpolifarmasi": item.msPoliFarmasi,
    "namapenyekriningresep": item.penyekrining,
    "namaperacik": item.namaPeracik,
    "namapengecek": item.namaPengecek,
    "namapenyrahobat": item.penyerahObat,
    "namapenerimaobat": item.penerimaObat,
    "prinsipbesar": listPrinsipBenar,
    "strukresepfk": NOREC_RESEP,
    "ketpenulis": item.ketPenulisan ? item.ketPenulisan : '',
    "kettanggal": item.ketTglResep ? item.ketTglResep : '',
    "ketrm": item.ketNoRm ? item.ketNoRm : '',
    "ketpasien": item.ketNamaPasien ? item.ketNamaPasien : '',
    "kettanggallahir": item.ketTglLahir ? item.ketTglLahir : '',
    "ketberat": item.ketBeratBadan ? item.ketBeratBadan : '',
    "ketdokter": item.ketNamaDok ? item.ketNamaDok : '',
    "ketruang": item.ketRuangPeriksa ? item.ketRuangPeriksa : '',
    "ketstatus": item.ketStatusJaminan ? item.ketStatusJaminan : '',
    "ketobat": item.ketNamaObat ? item.ketNamaObat : '',
    "ketkekuatan": item.ketKekuatan ? item.ketKekuatan : '',
    "ketjumlah": item.ketJmlObat ? item.ketJmlObat : '',
    "ketstabilitas": item.ketStabilitas ? item.ketStabilitas : '',
    "ketaturan": item.ketAturan ? item.ketAturan : '',
    "ketalergi": item.ketAlergi ? item.ketAlergi : '',
    "ketkonsumsi": item.ketObatLain ? item.ketObatLain : '',
    "ketduplikasi": item.ketDublikatObat ? item.ketDublikatObat : '',
    "ketinteraski": item.ketInteraksiObat ? item.ketInteraksiObat : '',
    "ketantibiotik": item.ketAntibiotik ? item.ketAntibiotik : '',
    "ketpolifarmasi": item.ketPoliFarmasi ? item.ketPoliFarmasi : '',
    "ketindikasi": item.ketIndikasi ? item.ketIndikasi : '',
    "rcek": item.cekAll
  }
  console.log(objSave)
  await useApi().post('farmasi/save-skrining-farmasi',objSave).then((response)=>{
    isDisabled.value = true
  }).catch((e:any)=>{
     console.log(e)
  })
  isLoadBtnSave.value = false
}

const loadRiwayat = async ()=>{
  
  let response = await useApi().get(`farmasi/get-skrining-farmasi?strukresepfk=${NOREC_RESEP}`)
  item.norec = response.norec
  item.penulisan = response.rpenulis
  item.tglResep = response.rtanggalresep
  item.norm = response.rmr
  item.msNamaPasien = response.rpasien
  item.msTglLahir = response.rtanggallahir
  item.msBeratBadan = response.rberatbedan
  item.msNamaDok = response.rdokter
  item.msRuangPerkisa = response.rruang
  item.msStatusJaminan = response.rstatusjamin
  item.msNamaObat = response.robat
  item.msKekuatan = response.rkekuatan
  item.msJmlObat = response.rjumlahobat
  item.msStabilitas = response.rstabilitas
  item.msAturan = response.raturan
  item.msIndikasi = response.rindikasiobat
  item.msAlergi = response.ralergi
  item.msObatLain = response.rkonsumsi
  item.msDublikatObat = response.rduplikat
  item.msInteraksiObat = response.rinteraksi
  item.msAntibiotik = response.rantibiotik
  item.msPoliFarmasi = response.rpolifarmasi

  item.penyekrining = response.namapenyekriningresep
  item.namaPeracik = response.namaperacik
  item.namaPengecek = response.namapengecek
  item.penyerahObat = response.namapenyrahobat
  item.penerimaObat = response.namapenerimaobat

  item.ketPenulisan = response.ketpenulis
  item.ketTglResep = response.kettanggal
  item.ketNoRm = response.ketrm
  item.ketNamaPasien = response.ketpasien
  item.ketTglLahir = response.kettanggallahir
  item.ketBeratBadan = response.ketberat
  item.ketRuangPeriksa = response.ketruang
  item.ketStatusJaminan = response.ketstatus
  item.ketNamaDok = response.ketdokter
  item.ketNamaObat = response.ketobat
  item.ketKekuatan = response.ketkekuatan
  item.ketJmlObat = response.ketjumlah
  item.ketStabilitas = response.ketstabilitas
  item.ketAturan = response.ketaturan
  item.ketAlergi = response.ketalergi
  item.ketObatLain = response.ketkonsumsi
  item.ketDublikatObat = response.ketduplikasi
  item.ketInteraksiObat = response.ketinteraski
  item.ketAntibiotik = response.ketantibiotik
  item.ketPoliFarmasi = response.ketpolifarmasi
  item.ketIndikasi = response.ketindikasi
  item.cekAll = response.rcek

  let listPrinsipBenar = response.prinsipbesar.split(",")
  listPrinsipBenar.forEach((elem)=>{
    listPrinsip.value.push(parseInt(elem))
  })
}

const back = () => {
  window.history.back()
}

loadRiwayat()
headerPasien()

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

.bold {
  font-weight: bold
}
</style>
