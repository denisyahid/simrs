<template>
  <div class="form-layout is-stacked">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Form Lembar Kerja Investigasi</h3>
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

      <div class="column is-12 pt-0">
        <h1 style="font-weight: bold;" class="mb-2">Penyebab Langsung Insiden</h1>
        <VField>
          <VControl>
            <VTextarea v-model="item.penyebabInsiden" rows="3" :disabled="isInfo">
            </VTextarea>
          </VControl>
        </VField>
      </div>

      <div class="column is-12 pt-0">
        <h1 style="font-weight: bold;" class="mb-2">Penyebab Yang Melatarbelangkangi / akar masalah insiden</h1>
        <VField>
          <VControl>
            <VTextarea v-model="item.akarMasalah" rows="3" :disabled="isInfo">
            </VTextarea>
          </VControl>
        </VField>
      </div>

      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-4">
            <h1 style="font-weight: bold;" class="mb-2">Rekomendasi</h1>
            <VField>
              <VControl>
                <VTextarea v-model="item.rekomendasi" rows="3" :disabled="isInfo">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;" class="mb-2">Penangung Jawab</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="item.penangungJawabRekomendasi" :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai..."
                  :disabled="isInfo" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;" class="mb-2">Tanggal</h1>
            <VField>
              <VDatePicker v-model="item.tglRekomendasi" mode="dateTime" style="width: 100%" trim-weeks
                :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" :disabled="isInfo" />
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
          <div class="column is-4">
            <h1 style="font-weight: bold;" class="mb-2">Tindakan yang akan dilakukan</h1>
            <VField>
              <VControl>
                <VTextarea v-model="item.tindakanDilakukan" rows="3" :disabled="isInfo">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;" class="mb-2">Penangung Jawab</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="item.penangungJawabTindakan" :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai..."
                  :disabled="isInfo" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;" class="mb-2">Tanggal</h1>
            <VField>
              <VDatePicker v-model="item.tglTindakan" mode="dateTime" style="width: 100%" trim-weeks
                :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" :disabled="isInfo" />
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
          <div class="column is-4">
             <h1 style="font-weight: bold;" class="mb-2">Nama</h1>
            <VField>
              <VControl icon="feather:bookmark">
                <input v-model="item.nama" type="text" class="input"/>
              </VControl>
            </VField>
          </div>
           <div class="column is-4">
              <h1 style="font-weight: bold;" class="mb-2">Tanggal Mulai Investigasi</h1>
              <VField>
                <VDatePicker v-model="item.tglMulaiInvestigasi" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" :disabled="isInfo" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
           <div class="column is-4">
              <h1 style="font-weight: bold;" class="mb-2">Tanggal Selesai Investigasi</h1>
              <VField>
                <VDatePicker v-model="item.tglSelesaiInvestigasi" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" :disabled="isInfo" />
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
          <div class="column is-4">
             <h1 style="font-weight: bold;" class="mb-2">Investigasi Lengkap</h1>
            <VField>
              <VControl icon="feather:bookmark">
                <input v-model="item.investigasiLengkap" type="text" class="input"/>
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
             <h1 style="font-weight: bold;" class="mb-2">Investigasi Lebih Lanjut</h1>
            <VField>
              <VControl icon="feather:bookmark">
                <input v-model="item.investigasiLebihLanjut" type="text" class="input"/>
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold;" class="mb-2">Regreding</h1>
            <VField>
                <VControl icon="feather:search">
                  <Dropdown v-model="item.regreding" :options="listRegreding" :optionLabel="'nama'" 
                    placeholder="Regreding" style="width: 100%;" :filter="true" showClear />
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
  title: 'Lembar Kerja Investigasi-Sederhana - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let NOREC_PD = useRoute().query.norec_pd as string
let NOCMFK = useRoute().query.nocmfk as string
let NOREC_LII = useRoute().query.norec_lii as string
// let NOREC_LK = useRoute().query.norec_lk as string
let NOREC = useRoute().query.norec as string
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

const listRegreding = ref([
  {'id' : 1, 'nama' : 'Biru'},
  {'id' : 2, 'nama' : 'Hijau'},
  {'id' : 3, 'nama' : 'Kuning'},
  {'id' : 4, 'nama' : 'Merah'},
])

const fetchPegawai = async (filter: any) => {

  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

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
  isLoadData.value = false
  
}

const loadRiwayat = async()=>{
  isLoadData.value = true
let response = await useApi().get(`pmkp/get-daftar-lembar-investigasi-sederhana?norec=${NOREC}`)
  let data = response[0]
  isLoadData.value = false
  item.akarMasalah = data.latarbelakanginsiden
  item.penyebabInsiden = data.penyebabinsidenlangsung
  item.penangungJawabRekomendasi = {label : data.penanggungjawab , value : data.penanggungjawabfk }
  item.rekomendasi = data.rekomendasi
  item.tglRekomendasi = data.tanggalrekomendasi
  item.tindakanDilakukan = data.tindakan
  item.penangungJawabTindakan = { label: data.pegawai, value: data.pegawaifk }
  item.nama = data.namakepala
  item.tglMulaiInvestigasi = data.tanggalmulai
  item.tglSelesaiInvestigasi = data.tanggalakhir
  item.tglTindakan = data.tanggaltindakan
  item.investigasiLengkap = data.investigasilengkap
  item.investigasiLebihLanjut = data.investigasilanjutan
  listRegreding.value.forEach(element => {
    if(element.id == data.regradingfk){
       item.regreding = element
       return
    }
  });
}

const simpan = async () => {
  
  isLoadBtnSave.value = true

  let objSave = {
    'data': {
      norec: NOREC ? NOREC : '',
      insidenfk: NOREC_LII ? NOREC_LII : '',
      latarbelakanginsiden: item.akarMasalah ? item.akarMasalah : null,
      penyebabinsidenlangsung: item.penyebabInsiden ? item.penyebabInsiden : null,
      rekomendasi: item.rekomendasi ? item.rekomendasi : null,
      penanggungjawabfk: item.penangungJawabRekomendasi ? item.penangungJawabRekomendasi.value : null,
      tanggalrekomendasi: item.tglRekomendasi ? H.formatDate(item.tglRekomendasi, 'YYYY-MM-DD HH:mm:ss') : null,
      tindakan: item.tindakanDilakukan ? item.tindakanDilakukan : null,
      pegawaifk: item.penangungJawabTindakan ? item.penangungJawabTindakan.value : null,
      namakepala: item.nama ? item.nama : null,
      tanggalmulai: item.tglMulaiInvestigasi ? H.formatDate(item.tglMulaiInvestigasi, 'YYYY-MM-DD HH:mm:ss') : null,
      tanggalakhir: item.tglSelesaiInvestigasi ? H.formatDate(item.tglSelesaiInvestigasi, 'YYYY-MM-DD HH:mm:ss') : null,
      tanggaltindakan: item.tglTindakan ? H.formatDate(item.tglTindakan, 'YYYY-MM-DD HH:mm:ss') : null,
      investigasilengkap: item.investigasiLengkap ? item.investigasiLengkap : null,
      investigasilanjutan: item.investigasiLebihLanjut ? item.investigasiLebihLanjut : null,
      regrading: item.regreding ? item.regreding.nama : null,
      regradingfk: item.regreding ? item.regreding.id : null,
    }
  }

  await useApi().post('pmkp/simpan-lembar-kerja-investigasi', objSave).then((response) => {
    isLoadBtnSave.value = false
    isDisabled.value = true
  })
}

const back = () => {
  window.history.back()
}

headerPasien()
if (NOREC) {
  loadRiwayat()
}

</script>

<style lang="scss" scoped>
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

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
